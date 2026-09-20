<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attraction;
use App\Models\BusinessCategory;
use App\Traits\UploadFile;
use Illuminate\Http\Request;

class AdminAttractionController extends Controller
{
    use UploadFile;

    public function index()
    {
        $attractions = Attraction::with(['creator', 'category'])
            ->latest()
            ->get();

        return view('admin.attractions.index', compact('attractions'));
    }

    public function create()
    {
        $categories = BusinessCategory::orderBy('name')->get();

        return view('admin.attractions.create', compact('categories'));
    }

    public function show(Attraction $attraction)
    {
        $attraction->load(['creator', 'category'])
            ->loadAvg('reviews', 'rating')
            ->loadCount(['reviews', 'favorites']);

        return view('admin.attractions.show', compact('attraction'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'category_id' => 'nullable|exists:business_categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $this->upload($request->file('image'));
        }

        Attraction::create([
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'category_id' => $request->category_id,
            'image' => $imagePath,
            'created_by' => auth()->id(),
            'created_at' => now(),
        ]);

        return redirect()->route('admin.attractions.index')
            ->with('success', 'Attraction created successfully');
    }

    public function edit(Attraction $attraction)
    {
        $categories = BusinessCategory::orderBy('name')->get();

        return view('admin.attractions.edit', compact('attraction', 'categories'));
    }

    public function update(Request $request, Attraction $attraction)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'category_id' => 'nullable|exists:business_categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $attraction->image = $this->upload($request->file('image'));
        }

        $attraction->update([
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('admin.attractions.index')
            ->with('success', 'Attraction updated successfully');
    }

    public function destroy(Attraction $attraction)
    {
        $attraction->delete();

        return back()->with('success', 'Attraction deleted successfully');
    }
}
