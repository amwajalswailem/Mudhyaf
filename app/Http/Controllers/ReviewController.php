<?php

namespace App\Http\Controllers;

use App\Models\Attraction;
use App\Models\Business;
use App\Models\Event;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    private const REVIEWABLE_TYPES = [
        'App\Models\Attraction',
        'App\Models\Business',
        'App\Models\Event',
    ];

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'entity_type' => ['required', 'string', Rule::in(self::REVIEWABLE_TYPES)],
            'entity_id' => ['required', 'integer'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $entity = $this->resolveEntity($validated['entity_type'], (int) $validated['entity_id']);

        $review = Review::firstOrNew([
            'user_id' => $request->user()->id,
            'entity_type' => $validated['entity_type'],
            'entity_id' => $entity->id,
        ]);

        $isNew = ! $review->exists;
        $review->rating = $validated['rating'];
        $review->comment = $validated['comment'] ?? null;

        if ($isNew) {
            $review->created_at = now();
        }

        $review->save();

        return back()->with('success', $isNew
            ? 'Your review has been added.'
            : 'Your review has been updated.');
    }

    public function update(Request $request, Review $review): RedirectResponse
    {
        abort_unless($review->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $review->update([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return back()->with('success', 'Your review has been updated.');
    }

    public function destroy(Request $request, Review $review): RedirectResponse
    {
        abort_unless($review->user_id === $request->user()->id, 403);

        $review->delete();

        return back()->with('success', 'Your review has been removed.');
    }

    private function resolveEntity(string $entityType, int $entityId)
    {
        return match ($entityType) {
            Attraction::class => Attraction::findOrFail($entityId),
            Business::class => Business::where('status', 'approved')->findOrFail($entityId),
            Event::class => Event::where('status', 'approved')->findOrFail($entityId),
            default => abort(404),
        };
    }
}
