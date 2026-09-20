<?php

namespace App\Http\Controllers;

use App\Models\Attraction;
use App\Models\BusinessCategory;
use Illuminate\Http\Request;

class AttractionController extends Controller
{
    public function publicIndex(Request $request)
    {
        $query = Attraction::with('category');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $attractions = $query->latest()->paginate(9);

        $categories = BusinessCategory::all();

        return view('attractions.index', compact('attractions', 'categories'));
    }

    public function show(Attraction $attraction)
    {
        $attraction->load(['category', 'creator'])
            ->loadAvg('reviews', 'rating')
            ->loadCount('reviews');

        $attractionReviews = $attraction->reviews()
            ->with('user')
            ->latest()
            ->get();

        $currentReview = auth()->check()
            ? $attraction->reviews()->where('user_id', auth()->id())->first()
            : null;

        return view('attractions.show', compact('attraction', 'attractionReviews', 'currentReview'));
    }
}
