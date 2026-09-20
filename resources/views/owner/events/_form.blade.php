@php
    $isEdit = isset($event);
    $formId = $isEdit ? 'owner-event-edit-form' : 'owner-event-form';
@endphp

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <div class="lg:col-span-7 bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-gray-400">Event details</p>
                <h3 class="text-2xl font-extrabold text-gray-800 mt-1">Event information</h3>
            </div>
            <span class="inline-flex px-4 py-1.5 rounded-full text-sm font-bold bg-amber-100 text-amber-700 ring-1 ring-amber-200">
                Pending approval
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Event Name</label>
                <input type="text" name="name" value="{{ old('name', $event->name ?? '') }}"
                       class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-[--primary-gold] focus:ring-4 focus:ring-[--primary-gold]/10 outline-none transition">
                @error('name') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="5"
                          class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-[--primary-gold] focus:ring-4 focus:ring-[--primary-gold]/10 outline-none transition">{{ old('description', $event->description ?? '') }}</textarea>
                @error('description') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                <input type="text" name="address" value="{{ old('address', $event->address ?? '') }}"
                       class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-[--primary-gold] focus:ring-4 focus:ring-[--primary-gold]/10 outline-none transition">
                @error('address') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                <select name="category_id"
                        class="w-full rounded-2xl border border-gray-200 px-4 py-3 bg-white focus:border-[--primary-gold] focus:ring-4 focus:ring-[--primary-gold]/10 outline-none transition">
                    <option value="">Select category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (int) old('category_id', $event->category_id ?? 0) === (int) $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Image</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full rounded-2xl border border-gray-200 px-4 py-3 bg-white">
                @if($isEdit && !empty($event->image))
                    <img src="{{ $event->image_url }}" alt="{{ $event->name }}" class="mt-4 h-36 w-full rounded-2xl object-cover border border-gray-100">
                @endif
                @error('image') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Start Time</label>
                <input type="datetime-local" name="start_time"
                       value="{{ old('start_time', isset($event->start_time) ? $event->start_time->format('Y-m-d\TH:i') : '') }}"
                       class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-[--primary-gold] focus:ring-4 focus:ring-[--primary-gold]/10 outline-none transition">
                @error('start_time') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">End Time</label>
                <input type="datetime-local" name="end_time"
                       value="{{ old('end_time', isset($event->end_time) ? $event->end_time->format('Y-m-d\TH:i') : '') }}"
                       class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-[--primary-gold] focus:ring-4 focus:ring-[--primary-gold]/10 outline-none transition">
                @error('end_time') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="lg:col-span-5 space-y-8">
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6">
            <div class="flex items-center justify-between gap-4 mb-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-gray-400">Location</p>
                    <h3 class="text-2xl font-extrabold text-gray-800 mt-1">Pick the event pin</h3>
                </div>
                <i class="fa-solid fa-map-location-dot text-3xl text-[--deep-green]"></i>
            </div>

            <input type="hidden" id="{{ $formId }}-latitude" name="latitude" value="{{ old('latitude', $event->latitude ?? '') }}">
            <input type="hidden" id="{{ $formId }}-longitude" name="longitude" value="{{ old('longitude', $event->longitude ?? '') }}">

            <div id="{{ $formId }}-map" class="w-full h-[420px] rounded-2xl overflow-hidden"></div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <input type="text" readonly
                       id="{{ $formId }}-lat-preview"
                       value="{{ old('latitude', $event->latitude ?? '') }}"
                       class="rounded-2xl border border-gray-200 px-4 py-3 bg-gray-50 text-gray-700">
                <input type="text" readonly
                       id="{{ $formId }}-lng-preview"
                       value="{{ old('longitude', $event->longitude ?? '') }}"
                       class="rounded-2xl border border-gray-200 px-4 py-3 bg-gray-50 text-gray-700">
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-gray-400">Approval flow</p>
            <p class="mt-3 text-gray-700 leading-7">
                Events submitted by business owners are created with <strong>pending</strong> status and appear in the admin approval queue before going public.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('owner.events.index') }}"
               class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl border border-gray-200 bg-white px-5 py-3 font-bold text-gray-700 shadow-sm hover:shadow-md transition">
                <i class="fa-solid fa-arrow-left"></i>
                Cancel
            </a>
            <button type="submit"
                    class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl bg-[--deep-green] px-5 py-3 font-bold text-white shadow-lg hover:shadow-xl transition">
                <i class="fa-solid fa-paper-plane"></i>
                {{ $isEdit ? 'Update Event' : 'Submit Event' }}
            </button>
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
        const latitudeInput = document.getElementById('{{ $formId }}-latitude');
        const longitudeInput = document.getElementById('{{ $formId }}-longitude');
        const latPreview = document.getElementById('{{ $formId }}-lat-preview');
        const lngPreview = document.getElementById('{{ $formId }}-lng-preview');
        const fallbackLat = 27.5114;
        const fallbackLng = 41.7208;

        const lat = parseFloat(latitudeInput.value) || fallbackLat;
        const lng = parseFloat(longitudeInput.value) || fallbackLng;
        const location = [lat, lng];

        const map = L.map('{{ $formId }}-map', {
            scrollWheelZoom: false
        }).setView(location, 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const marker = L.marker(location, { draggable: true }).addTo(map);

        const updateCoordinates = (latLng) => {
            latitudeInput.value = latLng.lat;
            longitudeInput.value = latLng.lng;
            latPreview.value = latLng.lat.toFixed(6);
            lngPreview.value = latLng.lng.toFixed(6);
        };

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
