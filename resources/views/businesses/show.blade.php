@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin="">

    @php
        $isFavorited = auth()->check() && $business->isFavoritedBy(auth()->user());
    @endphp

    <section class="pt-28 pb-24 min-h-screen attraction-detail-shell">
        <div class="max-w-7xl mx-auto px-6 space-y-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <div class="lg:col-span-6 attraction-top-card">
                    <div class="attraction-image-frame business-image-frame">
                        <img src="{{ $business->image_url }}"
                             alt="{{ $business->name }}"
                             class="attraction-image">
                        <div class="attraction-image-gradient"></div>
                        <div class="attraction-image-meta">
                            <span class="detail-chip">{{ optional($business->category)->name ?? 'Business' }}</span>
                            <span class="detail-chip">Level {{ $business->price_level ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6 attraction-copy-card">
                    <p class="detail-kicker detail-kicker-dark">Business</p>
                    <h3 class="detail-title detail-title-dark attraction-title">{{ $business->name }}</h3>
                    <p class="detail-description detail-description-dark">
                        {{ $business->description ?? 'No description provided.' }}
                    </p>

                    <div class="attraction-actions">
                        @auth
                            <button
                                type="button"
                                onclick="toggleFavorite(this)"
                                data-id="{{ $business->id }}"
                                data-type="App\Models\Business"
                                data-favorited="{{ $isFavorited ? '1' : '0' }}"
                                class="favorite-action {{ $isFavorited ? 'is-favorited' : '' }}">
                                <svg class="favorite-icon {{ $isFavorited ? 'text-red-500' : 'text-gray-400' }}"
                                     fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                                <span class="favorite-label">{{ $isFavorited ? 'Saved' : 'Favorite' }}</span>
                            </button>
                        @endauth

                        <div class="attraction-note">
                            <span class="attraction-note-label">Location</span>
                            <span class="attraction-note-value">{{ $business->address ?? 'Location information is unavailable.' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="attraction-sidebar-card">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="detail-stat detail-stat-accent">
                        <span class="detail-stat-label">Category</span>
                        <span class="detail-stat-value">{{ optional($business->category)->name ?? '-' }}</span>
                    </div>
                    <div class="detail-stat">
                        <span class="detail-stat-label">Rating</span>
                        <span class="detail-stat-value">{{ number_format($business->reviews_avg_rating ?? 0, 1) }}/5</span>
                    </div>
                    <div class="detail-stat">
                        <span class="detail-stat-label">Phone</span>
                        <span class="detail-stat-value">{{ $business->phone ?? '-' }}</span>
                    </div>
                    <div class="detail-stat">
                        <span class="detail-stat-label">Working Hours</span>
                        <span class="detail-stat-value">{{ $business->working_hours ?? '-' }}</span>
                    </div>
                </div>

                <div class="detail-map-card">
                    <div class="detail-map-head">
                        <div>
                            <p class="detail-panel-label">Business</p>
                            <p class="detail-map-title">OpenStreetMap</p>
                        </div>
                        <span class="detail-map-badge">No subscription</span>
                    </div>
                    <div id="business-map" class="detail-map"></div>
                </div>
            </div>

            <div class="review-section-shell">
                @if(session('success'))
                    <div class="profile-alert profile-alert-success mb-4">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <div class="review-section-head">
                    <div>
                        <p class="detail-panel-label">Reviews</p>
                        <h3 class="review-section-title">Guest feedback</h3>
                        <p class="review-section-copy">Read honest ratings before you visit or add your own.</p>
                    </div>
                    <div class="review-section-badge">
                        <i class="fa-solid fa-star"></i>
                        {{ number_format($business->reviews_avg_rating ?? 0, 1) }}/5 from {{ $business->reviews_count ?? 0 }} reviews
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-6">
                    <div class="lg:col-span-5">
                        @include('reviews.partials.form', [
                            'reviewable' => $business,
                            'reviewableType' => App\Models\Business::class,
                            'currentReview' => $currentReview,
                        ])
                    </div>

                    <div class="lg:col-span-7">
                        @include('reviews.partials.list', [
                            'reviews' => $businessReviews,
                            'heading' => 'Recent business reviews',
                            'subheading' => 'What guests say about this place',
                        ])
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
    <script>
        window.addEventListener('load', function () {
            const lat = parseFloat({{ $business->latitude ?? 27.5114 }});
            const lng = parseFloat({{ $business->longitude ?? 41.7208 }});
            const location = [lat, lng];

            const map = L.map('business-map', {
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
                .bindPopup(@json($business->name), {
                    closeButton: false,
                    autoClose: false,
                    closeOnClick: false
                })
                .openPopup();
        });
    </script>

@endsection
