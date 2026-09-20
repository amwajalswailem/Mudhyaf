<?php

namespace App\Http\Controllers;

use App\Models\BusinessCategory;
use App\Services\RecommendationService;
use Illuminate\Support\Collection;

class ProfileController extends Controller
{
    public function show(RecommendationService $recommendationService)
    {
        $user = auth()->user();

        $favorites = $user->favorites()
            ->with('entity')
            ->latest()
            ->get();

        $reviews = $user->reviews()
            ->with('entity')
            ->latest()
            ->get();

        $favoriteSections = $favorites->groupBy(function ($favorite) {
            return class_basename($favorite->entity_type);
        })->map(function (Collection $items, string $type) {
            return [
                'type' => $type,
                'label' => $this->favoriteLabel($type),
                'icon' => $this->favoriteIcon($type),
                'show_route' => $this->favoriteShowRoute($type),
                'index_route' => $this->favoriteIndexRoute($type),
                'items' => $items,
            ];
        })->sortBy('label')->values();

        $reviewSections = $reviews->groupBy(function ($review) {
            return class_basename($review->entity_type);
        })->map(function (Collection $items, string $type) {
            return [
                'type' => $type,
                'label' => $this->reviewLabel($type),
                'icon' => $this->reviewIcon($type),
                'show_route' => $this->reviewShowRoute($type),
                'items' => $items,
            ];
        })->sortBy('label')->values();

        $stats = [
            'total_favorites' => $favorites->count(),
            'attractions' => $favorites->filter(fn ($favorite) => class_basename($favorite->entity_type) === 'Attraction')->count(),
            'events' => $favorites->filter(fn ($favorite) => class_basename($favorite->entity_type) === 'Event')->count(),
            'businesses' => $favorites->filter(fn ($favorite) => class_basename($favorite->entity_type) === 'Business')->count(),
            'reviews' => $reviews->count(),
        ];

        $preferences = $recommendationService->preferencesFor($user);
        $recommendedPlaces = $recommendationService->recommendFor($user, null, 6);
        $categories = BusinessCategory::orderBy('name')->get();

        return view('profile.tourist', compact(
            'user',
            'favorites',
            'favoriteSections',
            'reviewSections',
            'stats',
            'reviews',
            'preferences',
            'recommendedPlaces',
            'categories'
        ));
    }

    private function favoriteLabel(string $type): string
    {
        return match ($type) {
            'Attraction' => 'Attractions',
            'Event' => 'Events',
            'Business' => 'Businesses',
            default => ucfirst($type),
        };
    }

    private function favoriteIcon(string $type): string
    {
        return match ($type) {
            'Attraction' => 'fa-solid fa-location-dot',
            'Event' => 'fa-solid fa-calendar-days',
            'Business' => 'fa-solid fa-store',
            default => 'fa-solid fa-heart',
        };
    }

    private function favoriteShowRoute(string $type): ?string
    {
        return match ($type) {
            'Attraction' => 'attractions.show',
            'Event' => 'events.show',
            'Business' => 'businesses.show',
            default => null,
        };
    }

    private function favoriteIndexRoute(string $type): ?string
    {
        return match ($type) {
            'Attraction' => 'attractions.index',
            'Event' => 'events.index',
            'Business' => 'businesses.index',
            default => null,
        };
    }

    private function reviewLabel(string $type): string
    {
        return match ($type) {
            'Attraction' => 'Attraction Reviews',
            'Event' => 'Event Reviews',
            'Business' => 'Business Reviews',
            default => ucfirst($type) . ' Reviews',
        };
    }

    private function reviewIcon(string $type): string
    {
        return match ($type) {
            'Attraction' => 'fa-solid fa-location-dot',
            'Event' => 'fa-solid fa-calendar-days',
            'Business' => 'fa-solid fa-store',
            default => 'fa-solid fa-pen-ruler',
        };
    }

    private function reviewShowRoute(string $type): ?string
    {
        return match ($type) {
            'Attraction' => 'attractions.show',
            'Event' => 'events.show',
            'Business' => 'businesses.show',
            default => null,
        };
    }
}
