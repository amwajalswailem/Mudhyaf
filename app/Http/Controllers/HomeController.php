<?php

namespace App\Http\Controllers;

use App\Models\Attraction;
use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\Event;
use App\Models\Review;
use App\Services\RecommendationService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(RecommendationService $recommendationService)
    {
        $attractions = Attraction::with(['category', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('reviews_avg_rating')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        $businesses = Business::with(['category'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('status', 'approved')
            ->orderByDesc('reviews_avg_rating')
            ->latest()
            ->take(3)
            ->get();

        $events = Event::with(['category'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('status', 'approved')
            ->orderBy('start_time')
            ->take(3)
            ->get();

        $stats = [
            'attractions' => Attraction::count(),
            'businesses' => Business::where('status', 'approved')->count(),
            'events' => Event::where('status', 'approved')->count(),
            'reviews' => Review::count(),
        ];

        $categories = BusinessCategory::orderBy('name')->get();
        $favoriteMap = [];

        if (auth()->check()) {
            $favoriteMap = auth()->user()
                ->favorites()
                ->get(['entity_type', 'entity_id'])
                ->groupBy('entity_type')
                ->map(function ($items) {
                    return $items->pluck('entity_id')->map(fn ($id) => (string) $id)->all();
                })
                ->all();
        }

        $recommendations = collect();

        if (auth()->check()) {
            $recommendations = $recommendationService->recommendFor(auth()->user(), null, 3);
        }

        return view('home', compact('attractions', 'businesses', 'events', 'stats', 'categories', 'favoriteMap', 'recommendations'));
    }

    public function about()
    {
        return view('about');
    }

    public function exploreRedirect(Request $request)
    {
        $filters = $request->only(['search', 'category', 'budget']);

        if ($request->type === 'businesses') {
            return redirect()->route('businesses.index', $filters);
        }

        if ($request->type === 'events') {
            return redirect()->route('events.index', $filters);
        }

        return redirect()->route('attractions.index', $filters);
    }
}
