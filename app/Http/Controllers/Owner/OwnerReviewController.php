<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Event;
use App\Models\Review;
use Illuminate\View\View;

class OwnerReviewController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $businessIds = $user->businesses()->pluck('id');
        $eventIds = $user->createdEvents()->pluck('id');

        $businessReviews = collect();
        $eventReviews = collect();

        if ($businessIds->isNotEmpty()) {
            $businessReviews = Review::with(['user', 'entity'])
                ->where('entity_type', Business::class)
                ->whereIn('entity_id', $businessIds)
                ->latest('created_at')
                ->get();
        }

        if ($eventIds->isNotEmpty()) {
            $eventReviews = Review::with(['user', 'entity'])
                ->where('entity_type', Event::class)
                ->whereIn('entity_id', $eventIds)
                ->latest('created_at')
                ->get();
        }

        $allReviews = $businessReviews
            ->concat($eventReviews)
            ->sortByDesc(fn ($review) => $review->created_at?->timestamp ?? 0)
            ->values();

        $summary = [
            'total' => $allReviews->count(),
            'business_count' => $businessReviews->count(),
            'event_count' => $eventReviews->count(),
            'business_avg' => $businessReviews->avg('rating') ?: 0,
            'event_avg' => $eventReviews->avg('rating') ?: 0,
            'overall_avg' => $allReviews->avg('rating') ?: 0,
        ];

        return view('owner.reviews.index', compact(
            'allReviews',
            'businessReviews',
            'eventReviews',
            'summary'
        ));
    }
}
