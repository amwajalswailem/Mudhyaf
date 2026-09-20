<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessCategory;
use App\Models\Event;
use App\Traits\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminEventController extends Controller
{
    use UploadFile;

    public function index()
    {
        $events = Event::with(['creator', 'approver', 'category'])
            ->latest()
            ->get();

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $categories = BusinessCategory::orderBy('name')->get();

        return view('admin.events.create', compact('categories'));
    }

    public function show(Event $event)
    {
        $event->load(['creator', 'approver', 'category'])
            ->loadAvg('reviews', 'rating')
            ->loadCount(['reviews', 'favorites']);

        return view('admin.events.show', compact('event'));
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
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $this->upload($request->file('image'));
        }

        Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'category_id' => $request->category_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'image' => $imagePath,
            'created_by' => auth()->id(),
            'status' => 'pending',
            'created_at' => now(),
        ]);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully');
    }

    public function edit(Event $event)
    {
        $categories = BusinessCategory::orderBy('name')->get();

        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'category_id' => 'nullable|exists:business_categories,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $event->image = $this->upload($request->file('image'));
        }

        $event->update([
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'category_id' => $request->category_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully');
    }

    public function approve(Event $event)
    {
        $event->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        return back()->with('success', 'Event approved successfully');
    }

    public function reject(Request $request, Event $event)
    {
        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $event->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $validated['rejection_reason'] ?? null,
        ]);

        return back()->with('success', 'Event rejected successfully');
    }

    public function destroy(Event $event)
    {
        if ($event->image && Storage::disk('public')->exists($event->image)) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return back()->with('success', 'Event deleted successfully');
    }
}
