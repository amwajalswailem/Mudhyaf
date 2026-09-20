<?php

namespace App\Http\Controllers;

use App\Models\BusinessCategory;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function publicIndex(Request $request)
    {
        $query = Event::with('category')
            ->where('status', 'approved');

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $events = $query->latest()->paginate(9);

        $categories = BusinessCategory::all();

        return view('events.index', compact('events', 'categories'));
    }

    public function show(Event $event)
    {
        abort_if($event->status !== 'approved', 404);

        $event->load(['category', 'creator', 'approver'])
            ->loadAvg('reviews', 'rating')
            ->loadCount('reviews');

        $eventReviews = $event->reviews()
            ->with('user')
            ->latest()
            ->get();

        $currentReview = auth()->check()
            ? $event->reviews()->where('user_id', auth()->id())->first()
            : null;

        return view('events.show', compact('event', 'eventReviews', 'currentReview'));
    }
}
