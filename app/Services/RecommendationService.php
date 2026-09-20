<?php

namespace App\Services;

use App\Models\Attraction;
use App\Models\Business;
use App\Models\Event;
use App\Models\RecommendationLog;
use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class RecommendationService
{
    public function preferencesFor(User $user): ?UserPreference
    {
        return $user->userPreference()->first();
    }

    public function savePreferences(User $user, array $data): UserPreference
    {
        return UserPreference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'preferred_price_level' => $data['preferred_price_level'] ?? null,
                'min_budget' => $data['min_budget'] ?? null,
                'max_budget' => $data['max_budget'] ?? null,
                'preferred_categories' => array_values(array_filter($data['preferred_categories'] ?? [])),
                'preferred_tags' => array_values(array_filter($data['preferred_tags'] ?? [])),
                'preferred_visit_time' => $data['preferred_visit_time'] ?? null,
                'updated_at' => now(),
            ]
        );
    }

    public function recommendFor(User $user, ?string $scope = null, int $limit = 9): Collection
    {
        $preferences = $this->preferencesFor($user);
        $preferredCategories = collect($preferences?->preferred_categories ?? [])->map(fn ($value) => (int) $value)->filter()->values()->all();
        $preferredTags = collect($preferences?->preferred_tags ?? [])->map(fn ($value) => strtolower((string) $value))->filter()->values()->all();
        $preferredPriceLevel = $preferences?->preferred_price_level;
        $minBudget = $preferences?->min_budget !== null ? (float) $preferences->min_budget : null;
        $maxBudget = $preferences?->max_budget !== null ? (float) $preferences->max_budget : null;

        $historyCategories = $this->historyCategoryIds($user);
        $excluded = $this->alreadySeenIds($user);

        $candidates = collect();

        if ($scope === null || $scope === 'attractions') {
            $candidates = $candidates->merge(
                Attraction::with(['category'])
                    ->withAvg('reviews', 'rating')
                    ->withCount(['reviews', 'favorites'])
                    ->latest()
                    ->get()
                    ->map(function (Attraction $item) use ($preferredCategories, $preferredTags, $preferredPriceLevel, $minBudget, $maxBudget, $historyCategories) {
                        return $this->scoreCandidate($item, $preferredCategories, $preferredTags, $preferredPriceLevel, $minBudget, $maxBudget, $historyCategories);
                    })
            );
        }

        if ($scope === null || $scope === 'businesses') {
            $candidates = $candidates->merge(
                Business::with(['category'])
                    ->withAvg('reviews', 'rating')
                    ->withCount(['reviews', 'favorites'])
                    ->where('status', 'approved')
                    ->latest()
                    ->get()
                    ->map(function (Business $item) use ($preferredCategories, $preferredTags, $preferredPriceLevel, $minBudget, $maxBudget, $historyCategories) {
                        return $this->scoreCandidate($item, $preferredCategories, $preferredTags, $preferredPriceLevel, $minBudget, $maxBudget, $historyCategories);
                    })
            );
        }

        if ($scope === null || $scope === 'events') {
            $candidates = $candidates->merge(
                Event::with(['category'])
                    ->withAvg('reviews', 'rating')
                    ->withCount(['reviews', 'favorites'])
                    ->where('status', 'approved')
                    ->latest()
                    ->get()
                    ->map(function (Event $item) use ($preferredCategories, $preferredTags, $preferredPriceLevel, $minBudget, $maxBudget, $historyCategories) {
                        return $this->scoreCandidate($item, $preferredCategories, $preferredTags, $preferredPriceLevel, $minBudget, $maxBudget, $historyCategories);
                    })
            );
        }

        return $candidates
            ->reject(fn ($item) => in_array($item['entity_key'], $excluded, true))
            ->sortByDesc('score')
            ->values()
            ->take($limit);
    }

    public function recordRecommendationBatch(User $user, Collection $recommendations): void
    {
        foreach ($recommendations as $recommendation) {
            RecommendationLog::create([
                'user_id' => $user->id,
                'entity_type' => $recommendation['entity_type'],
                'entity_id' => $recommendation['entity_id'],
                'score' => $recommendation['score'],
                'reason' => $recommendation['reasons'],
                'generated_at' => now(),
            ]);
        }
    }

    public function metrics(): array
    {
        return [
            'total_logs' => RecommendationLog::count(),
            'unique_users' => RecommendationLog::query()->distinct()->count('user_id'),
            'today_logs' => RecommendationLog::whereDate('generated_at', today())->count(),
            'top_entities' => RecommendationLog::with('entity')
                ->selectRaw('entity_type, entity_id, COUNT(*) as total, MAX(score) as best_score')
                ->groupBy('entity_type', 'entity_id')
                ->orderByDesc('total')
                ->limit(4)
                ->get(),
            'recent_logs' => RecommendationLog::with(['user', 'entity'])
                ->latest('generated_at')
                ->get(),
        ];
    }

    private function scoreCandidate(Model $item, array $preferredCategories, array $preferredTags, ?string $preferredPriceLevel, ?float $minBudget, ?float $maxBudget, array $historyCategories): array
    {
        $entityType = class_basename($item);
        $categoryId = data_get($item, 'category_id');
        $priceLevel = data_get($item, 'price_level');
        $avgRating = (float) ($item->reviews_avg_rating ?? 0);
        $reviewCount = (int) ($item->reviews_count ?? 0);
        $favoriteCount = (int) ($item->favorites_count ?? 0);

        $score = 0;
        $reasons = [];

        if ($avgRating > 0) {
            $score += $avgRating * 16;
            $reasons[] = 'Strong review score';
        }

        if ($reviewCount > 0) {
            $score += min(18, $reviewCount * 1.4);
        }

        if ($favoriteCount > 0) {
            $score += min(12, $favoriteCount * 1.2);
        }

        if ($preferredPriceLevel && $priceLevel) {
            if ((string) $preferredPriceLevel === (string) $priceLevel) {
                $score += 24;
                $reasons[] = 'Matches your budget';
            } else {
                $score += max(0, 8 - abs((int) $preferredPriceLevel - (int) $priceLevel) * 3);
            }
        }

        if ($minBudget !== null && $maxBudget !== null && $priceLevel) {
            $budgetHint = (float) $priceLevel;
            if ($budgetHint >= $minBudget && $budgetHint <= $maxBudget) {
                $score += 8;
                $reasons[] = 'Fits your budget range';
            }
        }

        if ($categoryId && in_array((int) $categoryId, $preferredCategories, true)) {
            $score += 20;
            $reasons[] = 'Matches your preferred categories';
        }

        if ($categoryId && in_array((int) $categoryId, $historyCategories, true)) {
            $score += 10;
            $reasons[] = 'Based on your activity';
        }

        $entityTag = strtolower($entityType);
        $categoryName = strtolower((string) optional($item->category)->name);

        if (in_array($entityTag, $preferredTags, true)) {
            $score += 6;
            $reasons[] = 'Matches your saved interests';
        }

        if ($categoryName && in_array($categoryName, $preferredTags, true)) {
            $score += 6;
            $reasons[] = 'Matches your saved interests';
        }

        if ($entityType === 'Business' && $priceLevel === null) {
            $score += 2;
        }

        $reasons[] = $categoryId ? 'Category: ' . optional($item->category)->name : 'Top rated content';

        return [
            'entity_type' => get_class($item),
            'entity_key' => get_class($item) . ':' . $item->id,
            'entity_id' => $item->id,
            'score' => round($score, 2),
            'reasons' => array_values(array_unique($reasons)),
            'item' => $item,
        ];
    }

    private function historyCategoryIds(User $user): array
    {
        $favorites = $user->favorites()->with('entity.category')->get()->pluck('entity.category_id');
        $reviews = $user->reviews()->with('entity.category')->get()->pluck('entity.category_id');

        return collect([$favorites, $reviews])
            ->flatten()
            ->filter()
            ->map(fn ($value) => (int) $value)
            ->unique()
            ->values()
            ->all();
    }

    private function alreadySeenIds(User $user): array
    {
        $favorites = $user->favorites()->get()->map(function ($favorite) {
            return $favorite->entity_type . ':' . $favorite->entity_id;
        });

        $reviews = $user->reviews()->get()->map(function ($review) {
            return $review->entity_type . ':' . $review->entity_id;
        });

        return $favorites->merge($reviews)->unique()->values()->all();
    }
}
