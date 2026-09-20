@extends('admin.layouts.app')

@section('role', 'Admin')

@section('content')
    <div class="space-y-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[--primary-gold]">Event</p>
                <h2 class="text-3xl font-extrabold text-gray-800 mt-2">{{ $event->name }}</h2>
                <p class="text-gray-500 mt-2">Administrative detail view with map preview and moderation context.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.events.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-white border border-gray-200 text-gray-700 font-bold shadow-sm hover:shadow-md transition">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back
                </a>
                <a href="{{ route('admin.events.edit', $event) }}"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-[--deep-green] text-white font-bold shadow-lg hover:shadow-xl transition">
                    <i class="fa-solid fa-pen"></i>
                    Edit
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-6 rounded-3xl overflow-hidden bg-white border border-gray-100 shadow-xl">
                <div class="relative h-full min-h-[420px]">
                    <img src="{{ $event->image_url }}" alt="{{ $event->name }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    <div class="absolute left-6 bottom-6 right-6 text-white">
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 border border-white/20 text-xs font-bold uppercase tracking-[0.2em]">
                                {{ optional($event->category)->name ?? 'Event' }}
                            </span>
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 border border-white/20 text-xs font-bold uppercase tracking-[0.2em]">
                                {{ number_format($event->reviews_avg_rating ?? 0, 1) }}/5
                            </span>
                        </div>
                        <h3 class="text-3xl font-extrabold mt-4">{{ $event->name }}</h3>
                        <p class="text-white/80 mt-2 max-w-2xl">
                            {{ $event->description ?? 'No description available.' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6 bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Created By</p>
                        <p class="mt-2 font-bold text-gray-800">{{ optional($event->creator)->full_name ?? '-' }}</p>
                    </div>
                    <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Approved By</p>
                        <p class="mt-2 font-bold text-gray-800">{{ optional($event->approver)->full_name ?? '-' }}</p>
                    </div>
                    <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Reviews</p>
                        <p class="mt-2 font-bold text-gray-800">{{ $event->reviews_count ?? 0 }}</p>
                    </div>
                    <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Rating</p>
                        <p class="mt-2 font-bold text-gray-800">{{ number_format($event->reviews_avg_rating ?? 0, 1) }}/5</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Start Time</p>
                        <p class="mt-2 font-bold text-gray-800">{{ $event->start_time?->format('M d, Y H:i') ?? '-' }}</p>
                    </div>
                    <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400">End Time</p>
                        <p class="mt-2 font-bold text-gray-800">{{ $event->end_time?->format('M d, Y H:i') ?? '-' }}</p>
                    </div>
                </div>

                <div class="mt-4 rounded-2xl border border-gray-100 bg-gray-50 p-5">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Address</p>
                    <p class="mt-2 text-gray-700 leading-7">{{ $event->address ?? 'Location information unavailable.' }}</p>
                </div>
            </div>
        </div>

        <div class="admin-map-shell p-6">
            <div class="flex items-center justify-between gap-3 mb-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-gray-400">Map</p>
                    <h3 class="text-2xl font-extrabold text-gray-800 mt-1">OpenStreetMap</h3>
                </div>
                <span class="admin-map-badge">No subscription</span>
            </div>
            <div id="admin-event-show-map" class="w-full h-[420px] rounded-2xl overflow-hidden"></div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
    <script>
        window.addEventListener('load', function () {
            const lat = parseFloat({{ $event->latitude ?? 27.5114 }});
            const lng = parseFloat({{ $event->longitude ?? 41.7208 }});
            const location = [lat, lng];

            const map = L.map('admin-event-show-map', {
                scrollWheelZoom: false,
                zoomControl: true,
                dragging: true,
                doubleClickZoom: true,
                touchZoom: true,
            }).setView(location, 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            L.marker(location)
                .addTo(map)
                .bindPopup(@json($event->name), {
                    closeButton: false,
                    autoClose: false,
                    closeOnClick: false
                })
                .openPopup();
        });
    </script>
@endsection
