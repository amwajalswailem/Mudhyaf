<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessCategory;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        $query = Business::with(['category'])
            ->withAvg('reviews', 'rating')
            ->where('status', 'approved');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%")
                    ->orWhere('address', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('budget')) {
            $query->where('price_level', $request->budget);
        }

        $businesses = $query->latest()->paginate(9);
        $categories = BusinessCategory::orderBy('name')->get();

        return view('businesses.index', compact('businesses', 'categories'));
    }

    public function show(Business $business)
    {
        abort_if($business->status !== 'approved', 404);

        $business->load(['category', 'owner'])
            ->loadAvg('reviews', 'rating');

        $businessReviews = $business->reviews()
            ->with('user')
            ->latest()
            ->get();

        $currentReview = auth()->check()
            ? $business->reviews()->where('user_id', auth()->id())->first()
            : null;

        return view('businesses.show', compact('business', 'businessReviews', 'currentReview'));
    }
}
