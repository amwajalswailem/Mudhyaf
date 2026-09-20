@extends('admin.layouts.app')

@section('role', 'Business Owner')

@section('content')
    @php
        $statusClasses = [
            'approved' => 'bg-green-100 text-green-700 ring-green-200',
            'pending' => 'bg-amber-100 text-amber-700 ring-amber-200',
            'rejected' => 'bg-rose-100 text-rose-700 ring-rose-200',
        ];
        $businessInitial = account_initial($business->name, 'B');
    @endphp

    <div class="space-y-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[--primary-gold]">Business Owner</p>
                <h2 class="text-3xl font-extrabold text-gray-800 mt-2">My Business</h2>
                <p class="text-gray-500 mt-2">Manage your listing, location, and business details.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('owner.dashboard') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-white border border-gray-200 text-gray-700 font-bold shadow-sm hover:shadow-md transition">
                    <i class="fa-solid fa-chart-line"></i>
                    Dashboard
                </a>
                <a href="{{ route('owner.business.edit') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-[--deep-green] text-white font-bold shadow-lg hover:shadow-xl transition">
                    <i class="fa-solid fa-pen"></i>
                    Update Business
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-5 bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="relative h-full min-h-[360px]">
                    <img src="{{ $business->image_url }}"
                         alt="{{ $business->name }}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    <div class="absolute left-6 bottom-6 right-6 text-white">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 border border-white/15 text-xs font-bold uppercase tracking-[0.2em]">
                            {{ optional($business->category)->name ?? 'Business' }}
                        </span>
                        <h3 class="text-3xl font-extrabold mt-3">{{ $business->name }}</h3>
                        <p class="text-white/80 mt-2 line-clamp-3">
                            {{ $business->description ?? 'No description available yet.' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7 bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
                <div class="flex flex-col gap-6 h-full">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-gray-400">Status</p>
                            <div class="mt-3 inline-flex px-4 py-1.5 rounded-full text-sm font-bold ring-1 {{ $statusClasses[$business->status] ?? 'bg-gray-100 text-gray-700 ring-gray-200' }}">
                                {{ strtoupper($business->status) }}
                            </div>
                        </div>

                        <div class="w-16 h-16 rounded-2xl bg-[--deep-green] text-white flex items-center justify-center text-2xl shadow-lg">
                            <span>{{ $businessInitial }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-gray-400">Category</p>
                            <p class="mt-2 font-bold text-gray-800">{{ optional($business->category)->name ?? '-' }}</p>
                        </div>
                        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-gray-400">Phone</p>
                            <p class="mt-2 font-bold text-gray-800">{{ $business->phone ?? '-' }}</p>
                        </div>
                        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-gray-400">Working Hours</p>
                            <p class="mt-2 font-bold text-gray-800">{{ $business->working_hours ?? '-' }}</p>
                        </div>
                        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-gray-400">Price Level</p>
                            <p class="mt-2 font-bold text-gray-800">{{ $business->price_level ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-100 bg-white p-5">
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-gray-400">Address</p>
                        <p class="mt-2 font-medium text-gray-700 leading-7">
                            {{ $business->address ?? 'Location information is unavailable.' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="rounded-2xl bg-[--deep-green]/5 p-4 border border-[--deep-green]/10">
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-gray-400">Rating</p>
                            <p class="mt-2 text-2xl font-extrabold text-[--deep-green]">
                                {{ number_format($business->reviews_avg_rating ?? 0, 1) }}
                            </p>
                        </div>
                        <div class="rounded-2xl bg-[--deep-green]/5 p-4 border border-[--deep-green]/10">
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-gray-400">Reviews</p>
                            <p class="mt-2 text-2xl font-extrabold text-[--deep-green]">
                                {{ $business->reviews_count ?? 0 }}
                            </p>
                        </div>
                        <div class="rounded-2xl bg-[--deep-green]/5 p-4 border border-[--deep-green]/10">
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-gray-400">Updated</p>
                            <p class="mt-2 text-sm font-bold text-[--deep-green]">
                                {{ $business->created_at?->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-7 bg-white rounded-3xl shadow-xl border border-gray-100 p-6">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-gray-400">Location</p>
                        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">OpenStreetMap view</h3>
                    </div>
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-[--deep-green]/10 text-[--deep-green] text-xs font-bold uppercase tracking-[0.18em]">
                        No subscription
                    </span>
                </div>

                <div id="owner-business-map" class="w-full h-[420px] rounded-2xl overflow-hidden"></div>
            </div>

            <div class="lg:col-span-5 bg-white rounded-3xl shadow-xl border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-gray-400">Quick actions</p>
                        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">Manage listing</h3>
                    </div>
                    <i class="fa-solid fa-store text-3xl text-[--deep-green]"></i>
                </div>

                <div class="mt-6 space-y-4">
                    <a href="{{ route('owner.business.edit') }}"
                       class="flex items-center justify-between rounded-2xl border border-gray-200 bg-gray-50 px-4 py-4 font-bold text-gray-700 hover:border-[--primary-gold] hover:text-[--deep-green] transition">
                        <span>Update business details</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <a href="{{ route('owner.dashboard') }}"
                       class="flex items-center justify-between rounded-2xl border border-gray-200 bg-gray-50 px-4 py-4 font-bold text-gray-700 hover:border-[--primary-gold] hover:text-[--deep-green] transition">
                        <span>Back to dashboard</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
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
            const lat = parseFloat({{ $business->latitude ?? 27.5114 }});
            const lng = parseFloat({{ $business->longitude ?? 41.7208 }});
            const location = [lat, lng];

            const map = L.map('owner-business-map', {
                scrollWheelZoom: false
            }).setView(location, 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            L.marker(location).addTo(map).bindPopup(@json($business->name)).openPopup();
        });
    </script>
@endsection
