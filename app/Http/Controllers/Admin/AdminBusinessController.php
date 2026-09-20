<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Request;

class AdminBusinessController extends Controller
{
    public function index()
    {
        $businesses = Business::with(['owner', 'category'])
            ->latest()
            ->get();

        return view('admin.businesses.index', compact('businesses'));
    }

    public function show(Business $business)
    {
        $business->load(['owner', 'category'])
            ->loadAvg('reviews', 'rating')
            ->loadCount(['reviews', 'favorites']);

        return view('admin.businesses.show', compact('business'));
    }

    public function approve(Business $business)
    {
        $business->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        return back()->with('success', 'Business approved successfully');
    }

    public function reject(Request $request, Business $business)
    {
        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $business->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $validated['rejection_reason'] ?? null,
        ]);

        return back()->with('success', 'Business rejected successfully');
    }

    public function destroy(Business $business)
    {
        $business->delete();

        return back()->with('success', 'Business deleted successfully');
    }
}
