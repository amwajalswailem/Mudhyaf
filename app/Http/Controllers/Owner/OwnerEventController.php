<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\BusinessCategory;
use App\Models\Event;
use App\Traits\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class OwnerEventController extends Controller
{
    use UploadFile;

    public function index()
    {
        $events = Event::with(['creator', 'approver', 'category'])
            ->where('created_by', auth()->id())
            ->latest()
            ->get();

        return view('owner.events.index', compact('events'));
    }

    public function create()
    {
        $categories = BusinessCategory::orderBy('name')->get();

        return view('owner.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'category_id' => 'nullable|exists:business_categories,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $this->upload($request->file('image'), 'uploads/events', $validated['name']);
        }

        Event::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'address' => $validated['address'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'image' => $imagePath,
            'created_by' => auth()->id(),
            'approved_by' => null,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        return redirect()->route('owner.events.index')
            ->with('success', 'Event submitted for admin approval.');
    }

    public function edit(Event $event)
    {
        abort_unless($event->created_by === auth()->id(), 403);

        $categories = BusinessCategory::orderBy('name')->get();

        return view('owner.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        abort_unless($event->created_by === auth()->id(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'category_id' => 'nullable|exists:business_categories,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($event->image && File::exists(public_path(ltrim($event->image, '/')))) {
                File::delete(public_path(ltrim($event->image, '/')));
            }

            $event->image = $this->upload($request->file('image'), 'uploads/events', $validated['name']);
        }

        $event->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'address' => $validated['address'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'status' => 'pending',
            'approved_by' => null,
        ]);

        return redirect()->route('owner.events.index')
            ->with('success', 'Event updated and sent back for approval.');
    }

    public function destroy(Event $event)
    {
        abort_unless($event->created_by === auth()->id(), 403);

        if ($event->image && File::exists(public_path(ltrim($event->image, '/')))) {
            File::delete(public_path(ltrim($event->image, '/')));
        }

        $event->delete();

        return back()->with('success', 'Event deleted successfully.');
    }
}
