<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Event;
use App\Models\Attraction;
use App\Models\RecommendationLog;
use App\Models\Review;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $eventIds = $user->createdEvents()->pluck('id');
        $businessIds = $user->businesses()->pluck('id');
        $business = $user->businesses()
            ->withAvg('reviews', 'rating')
            ->withCount(['reviews', 'favorites'])
            ->first();

        $approved = Event::where('created_by', $user->id)
            ->where('status', 'approved')
            ->count();

        $pending = Event::where('created_by', $user->id)
            ->where('status', 'pending')
            ->count();

        $rejected = Event::where('created_by', $user->id)
            ->where('status', 'rejected')
            ->count();

        $eventReviews = Review::where('entity_type', Event::class)
            ->whereIn('entity_id', $eventIds)
            ->count();

        $businessReviews = $business?->reviews_count ?? 0;
        $recommendationAppearances = RecommendationLog::where(function ($query) use ($businessIds, $eventIds) {
            $query->where(function ($subQuery) use ($businessIds) {
                $subQuery->where('entity_type', Business::class)
                    ->whereIn('entity_id', $businessIds);
            })->orWhere(function ($subQuery) use ($eventIds) {
                $subQuery->where('entity_type', Event::class)
                    ->whereIn('entity_id', $eventIds);
            });
        })->count();

        $stats = [
            'my_business' => $business,
            'my_events' => Event::where('created_by', $user->id)->count(),
            'my_attractions' => Attraction::where('created_by', $user->id)->count(),
            'approved_events' => $approved,
            'pending_events' => $pending,
            'rejected_events' => $rejected,
            'business_reviews' => $businessReviews,
            'event_reviews' => $eventReviews,
            'total_reviews' => $businessReviews + $eventReviews,
            'business_favorites' => $business?->favorites_count ?? 0,
            'business_rating' => $business?->reviews_avg_rating ?? 0,
            'recommendation_appearances' => $recommendationAppearances,
        ];

        return view('owner.dashboard', compact('stats'));
    }
}
