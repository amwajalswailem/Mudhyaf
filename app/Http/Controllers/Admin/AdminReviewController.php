<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminReviewController extends Controller
{
    public function index(Request $request): View
    {
        $typeMap = [
            'attraction' => 'App\Models\Attraction',
            'business' => 'App\Models\Business',
            'event' => 'App\Models\Event',
        ];

        $type = $request->input('type');

        $reviewsQuery = Review::with(['user', 'entity'])->latest('created_at');

        if (isset($typeMap[$type])) {
            $reviewsQuery->where('entity_type', $typeMap[$type]);
        }

        $reviews = $reviewsQuery->get();

        $summary = [
            'total' => Review::count(),
            'attractions' => Review::where('entity_type', $typeMap['attraction'])->count(),
            'businesses' => Review::where('entity_type', $typeMap['business'])->count(),
            'events' => Review::where('entity_type', $typeMap['event'])->count(),
            'average' => round((float) Review::avg('rating'), 1),
        ];

        return view('admin.reviews.index', compact('reviews', 'summary', 'type'));
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Review removed successfully');
    }
}
