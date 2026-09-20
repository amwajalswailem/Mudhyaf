@extends('admin.layouts.app')

@section('role', 'Business Owner')

@section('content')
    @php
        $statusClasses = [
            'approved' => 'bg-green-100 text-green-700 ring-green-200',
            'pending' => 'bg-amber-100 text-amber-700 ring-amber-200',
            'rejected' => 'bg-rose-100 text-rose-700 ring-rose-200',
        ];
    @endphp

    <div class="space-y-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[--primary-gold]">Business Owner</p>
                <h2 class="text-3xl font-extrabold text-gray-800 mt-2">Update Business</h2>
                <p class="text-gray-500 mt-2">Update your details and move the map pin if needed.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('owner.business.show') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-white border border-gray-200 text-gray-700 font-bold shadow-sm hover:shadow-md transition">
                    <i class="fa-solid fa-store"></i>
                    Back to Business
                </a>
                <a href="{{ route('owner.dashboard') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-[--deep-green] text-white font-bold shadow-lg hover:shadow-xl transition">
                    <i class="fa-solid fa-chart-line"></i>
                    Dashboard
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-green-800 font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('owner.business.update') }}" method="POST" class="space-y-8" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <div class="lg:col-span-7 bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-gray-400">Business profile</p>
                            <h3 class="text-2xl font-extrabold text-gray-800 mt-1">Business information</h3>
                        </div>
                        <div class="inline-flex px-4 py-1.5 rounded-full text-sm font-bold ring-1 {{ $statusClasses[$business->status] ?? 'bg-gray-100 text-gray-700 ring-gray-200' }}">
                            {{ strtoupper($business->status) }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <div class="rounded-3xl border border-dashed border-gray-200 bg-gray-50 p-5">
                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-gray-400">Business Image</p>
                                        <h4 class="text-lg font-extrabold text-gray-800 mt-1">Upload a file or paste an image URL</h4>
                                        <p class="text-sm text-gray-500 mt-2">If both are provided, the uploaded file takes priority.</p>
                                    </div>
                                    <div class="w-28 h-28 rounded-3xl overflow-hidden bg-white border border-gray-200 shadow-sm flex-shrink-0">
                                        <img src="{{ $business->image_url }}"
                                             alt="{{ $business->name }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-5">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Image File</label>
                                        <input type="file" name="image_file" accept="image/*"
                                               class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 focus:border-[--primary-gold] focus:ring-4 focus:ring-[--primary-gold]/10 outline-none transition">
                                        @error('image_file') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Image URL</label>
                                        <input type="url" name="image_url" value="{{ old('image_url', \Illuminate\Support\Str::startsWith($business->image ?? '', ['http://', 'https://', '//']) ? $business->image : '') }}"
                                               placeholder="https://example.com/photo.jpg"
                                               class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-[--primary-gold] focus:ring-4 focus:ring-[--primary-gold]/10 outline-none transition">
                                        @error('image_url') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Business Name</label>
                            <input type="text" name="name" value="{{ old('name', $business->name) }}"
                                   class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-[--primary-gold] focus:ring-4 focus:ring-[--primary-gold]/10 outline-none transition">
                            @error('name') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                            <select name="category_id"
                                    class="w-full rounded-2xl border border-gray-200 px-4 py-3 bg-white focus:border-[--primary-gold] focus:ring-4 focus:ring-[--primary-gold]/10 outline-none transition">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (int) old('category_id', $business->category_id) === (int) $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $business->phone) }}"
                                   class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-[--primary-gold] focus:ring-4 focus:ring-[--primary-gold]/10 outline-none transition">
                            @error('phone') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Working Hours</label>
                            <input type="text" name="working_hours" value="{{ old('working_hours', $business->working_hours) }}"
                                   placeholder="e.g. 8:00 AM - 11:00 PM"
                                   class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-[--primary-gold] focus:ring-4 focus:ring-[--primary-gold]/10 outline-none transition">
                            @error('working_hours') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Price Level</label>
                            <select name="price_level"
                                    class="w-full rounded-2xl border border-gray-200 px-4 py-3 bg-white focus:border-[--primary-gold] focus:ring-4 focus:ring-[--primary-gold]/10 outline-none transition">
                                <option value="">Select price level</option>
                                <option value="1" {{ old('price_level', $business->price_level) === '1' ? 'selected' : '' }}>1 - Economy</option>
                                <option value="2" {{ old('price_level', $business->price_level) === '2' ? 'selected' : '' }}>2 - Standard</option>
                                <option value="3" {{ old('price_level', $business->price_level) === '3' ? 'selected' : '' }}>3 - Premium</option>
                            </select>
                            @error('price_level') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                            <textarea name="address" rows="3"
                                      class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-[--primary-gold] focus:ring-4 focus:ring-[--primary-gold]/10 outline-none transition">{{ old('address', $business->address) }}</textarea>
                            @error('address') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="5"
                                      class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-[--primary-gold] focus:ring-4 focus:ring-[--primary-gold]/10 outline-none transition">{{ old('description', $business->description) }}</textarea>
                            @error('description') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 space-y-8">
                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6">
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-gray-400">Coordinates</p>
                                <h3 class="text-2xl font-extrabold text-gray-800 mt-1">Move the pin</h3>
                            </div>
                            <i class="fa-solid fa-map-location-dot text-3xl text-[--deep-green]"></i>
                        </div>

                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $business->latitude) }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $business->longitude) }}">

                        <div id="owner-business-edit-map" class="w-full h-[420px] rounded-2xl overflow-hidden"></div>
                    </div>

                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-gray-400">Current values</p>
                        <div class="mt-4 space-y-3 text-sm">
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-gray-500">Latitude</span>
                                <span class="font-bold text-gray-800" id="lat-preview">{{ $business->latitude ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-gray-500">Longitude</span>
                                <span class="font-bold text-gray-800" id="lng-preview">{{ $business->longitude ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('owner.business.show') }}"
                           class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl border border-gray-200 bg-white px-5 py-3 font-bold text-gray-700 shadow-sm hover:shadow-md transition">
                            <i class="fa-solid fa-arrow-left"></i>
                            Cancel
                        </a>
                        <button type="submit"
                                class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl bg-[--deep-green] px-5 py-3 font-bold text-white shadow-lg hover:shadow-xl transition">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
    <script>
        window.addEventListener('load', function () {
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');
            const latPreview = document.getElementById('lat-preview');
            const lngPreview = document.getElementById('lng-preview');
            const fallbackLat = 27.5114;
            const fallbackLng = 41.7208;

            const lat = parseFloat(latitudeInput.value) || fallbackLat;
            const lng = parseFloat(longitudeInput.value) || fallbackLng;
            const location = [lat, lng];

            const map = L.map('owner-business-edit-map', {
                scrollWheelZoom: false
            }).setView(location, 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            const marker = L.marker(location, { draggable: true }).addTo(map);

            function updateCoordinates(latLng) {
                latitudeInput.value = latLng.lat;
                longitudeInput.value = latLng.lng;
                latPreview.textContent = latLng.lat.toFixed(6);
                lngPreview.textContent = latLng.lng.toFixed(6);
            }

            updateCoordinates({ lat, lng });

            map.on('click', function (event) {
                marker.setLatLng(event.latlng);
                updateCoordinates(event.latlng);
            });

            marker.on('dragend', function (event) {
                updateCoordinates(event.target.getLatLng());
            });
        });
    </script>
@endsection
