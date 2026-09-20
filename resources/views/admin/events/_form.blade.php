@php
    $event = $event ?? null;
    $isEdit = isset($event);
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    {{-- LEFT SIDE --}}
    <div class="space-y-6">

        {{-- Name --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Event Name</label>
            <input type="text"
                   name="name"
                   value="{{ old('name', $event->name ?? '') }}"
                   class="w-full px-4 py-3 rounded-xl border border-gray-200">
            @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
            <textarea name="description"
                      rows="4"
                      class="w-full px-4 py-3 rounded-xl border border-gray-200">{{ old('description', $event->description ?? '') }}</textarea>
        </div>

        {{-- Address --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Address</label>
            <input type="text"
                   name="address"
                   value="{{ old('address', $event->address ?? '') }}"
                   class="w-full px-4 py-3 rounded-xl border border-gray-200">
        </div>

        {{-- Category --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Category</label>
            <select name="category_id"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id', $event->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Start Time --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Start Time</label>
            <input type="datetime-local"
                   name="start_time"
                   value="{{ old('start_time', isset($event->start_time) ? $event->start_time->format('Y-m-d\TH:i') : '') }}"
                   class="w-full px-4 py-3 rounded-xl border border-gray-200">
        </div>

        {{-- End Time --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">End Time</label>
            <input type="datetime-local"
                   name="end_time"
                   value="{{ old('end_time', isset($event->end_time) ? $event->end_time->format('Y-m-d\TH:i') : '') }}"
                   class="w-full px-4 py-3 rounded-xl border border-gray-200">
        </div>

        {{-- Image --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Image</label>
            <input type="file"
                   name="image"
                   accept="image/*"
                   class="w-full">

            @if($isEdit && $event->image)
                <img src="{{ $event->image_url }}"
                     class="w-32 h-32 mt-4 rounded-xl object-cover border">
            @endif
        </div>

    </div>

    {{-- RIGHT SIDE --}}
    <div>

        <div class="admin-map-shell p-5">
            <div class="flex items-center justify-between gap-3 mb-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700">
                        Select Location
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Click on the map or drag the marker to set coordinates.</p>
                </div>
                <span class="admin-map-badge">
                    OpenStreetMap
                </span>
            </div>

            <div id="admin-event-map"
                 class="w-full h-[400px] rounded-2xl border border-gray-200 shadow overflow-hidden"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                <input type="text"
                       id="latitude"
                       name="latitude"
                       placeholder="Latitude"
                       value="{{ old('latitude', $event->latitude ?? '') }}"
                       class="px-4 py-3 rounded-xl border border-gray-200 bg-white">

                <input type="text"
                       id="longitude"
                       name="longitude"
                       placeholder="Longitude"
                       value="{{ old('longitude', $event->longitude ?? '') }}"
                       class="px-4 py-3 rounded-xl border border-gray-200 bg-white">
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
        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');
        const fallbackLat = 27.5114;
        const fallbackLng = 41.7208;

        const lat = parseFloat(latitudeInput.value) || fallbackLat;
        const lng = parseFloat(longitudeInput.value) || fallbackLng;
        const location = [lat, lng];

        const map = L.map('admin-event-map', {
            scrollWheelZoom: false
        }).setView(location, 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const marker = L.marker(location, { draggable: true }).addTo(map);

        function updateCoordinates(latLng) {
            latitudeInput.value = latLng.lat;
            longitudeInput.value = latLng.lng;
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
