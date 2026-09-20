<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\BusinessCategory;
use App\Traits\UploadFile;
use Illuminate\Http\Request;

class OwnerBusinessController extends Controller
{
    use UploadFile;

    public function show()
    {
        $business = auth()->user()
            ->businesses()
            ->with(['category'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->first();

        if (!$business) {
            return redirect()->route('owner.dashboard')
                ->with('error', 'No business record was found for your account.');
        }

        return view('owner.business.show', compact('business'));
    }

    public function edit()
    {
        $business = auth()->user()->businesses()->first();

        if (!$business) {
            return redirect()->route('owner.dashboard')
                ->with('error', 'No business record was found for your account.');
        }

        $categories = BusinessCategory::orderBy('name')->get();

        return view('owner.business.edit', compact('business', 'categories'));
    }

    public function update(Request $request)
    {
        $business = auth()->user()->businesses()->first();

        if (!$business) {
            return redirect()->route('owner.dashboard')
                ->with('error', 'No business record was found for your account.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'working_hours' => 'nullable|string|max:255',
            'price_level' => 'nullable|in:1,2,3',
            'category_id' => 'required|exists:business_categories,id',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'image_url' => 'nullable|url|max:2048',
        ]);

        if ($request->hasFile('image_file')) {
            $business->image = $this->upload($request->file('image_file'), 'uploads/businesses', $business->name);
        } elseif ($request->filled('image_url')) {
            $business->image = $request->input('image_url');
        }

        $business->update($request->only([
            'name',
            'description',
            'address',
            'phone',
            'working_hours',
            'price_level',
            'category_id',
            'latitude',
            'longitude',
        ]));

        return redirect()->route('owner.business.show')
            ->with('success', 'Business updated successfully');
    }
}
