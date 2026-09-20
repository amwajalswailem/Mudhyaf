@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin="">

    @php
        $isFavorited = auth()->check() && $event->isFavoritedBy(auth()->user());
    @endphp

    <section class="pt-28 pb-24 min-h-screen attraction-detail-shell">
        <div class="max-w-7xl mx-auto px-6 space-y-8">
            <div class="attraction-top-card">
                <div class="attraction-image-frame">
                    <img src="{{ $event->image_url }}"
                         alt="{{ $event->name }}"
                         class="attraction-image">
                    <div class="attraction-image-gradient"></div>
                    <div class="attraction-image-meta">
                        <span class="detail-chip">{{ optional($event->category)->name ?? 'Event' }}</span>
                        <span class="detail-chip">{{ number_format($event->reviews_avg_rating ?? 0, 1) }}/5 rating</span>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-12 gap-8">
                <div class="lg:col-span-7 attraction-copy-card">
                    <p class="detail-kicker detail-kicker-dark">Event</p>
                    <h1 class="detail-title detail-title-dark">{{ $event->name }}</h1>
                    <p class="detail-description detail-description-dark">
                        {{ $event->description ?? 'No description provided.' }}
                    </p>

                    <div class="attraction-actions">
                        @auth
                            <button
                                type="button"
                                onclick="toggleFavorite(this)"
                                data-id="{{ $event->id }}"
                                data-type="App\Models\Event"
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
                            <span class="attraction-note-value">{{ $event->address ?? 'Location information is unavailable.' }}</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 attraction-sidebar-card">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="detail-stat detail-stat-accent">
                            <span class="detail-stat-label">Category</span>
                            <span class="detail-stat-value">{{ optional($event->category)->name ?? '-' }}</span>
                        </div>
                        <div class="detail-stat">
                            <span class="detail-stat-label">Rating</span>
                            <span class="detail-stat-value">{{ number_format($event->reviews_avg_rating ?? 0, 1) }}/5</span>
                        </div>
                        <div class="detail-stat">
                            <span class="detail-stat-label">Start Time</span>
                            <span class="detail-stat-value">{{ $event->start_time?->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="detail-stat">
                            <span class="detail-stat-label">End Time</span>
                            <span class="detail-stat-value">{{ $event->end_time?->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="detail-stat">
                            <span class="detail-stat-label">Created By</span>
                            <span class="detail-stat-value">{{ optional($event->creator)->full_name ?? '-' }}</span>
                        </div>
                        <div class="detail-stat">
                            <span class="detail-stat-label">Approved By</span>
                            <span class="detail-stat-value">{{ optional($event->approver)->full_name ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="detail-map-card">
                        <div class="detail-map-head">
                            <div>
                                <p class="detail-panel-label">Map</p>
                                <p class="detail-map-title">OpenStreetMap</p>
                            </div>
                            <span class="detail-map-badge">No subscription</span>
                        </div>
                        <div id="event-map" class="detail-map"></div>
                    </div>
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
                        <h3 class="review-section-title">Event feedback</h3>
                        <p class="review-section-copy">See how people rated the event and add your own review.</p>
                    </div>
                    <div class="review-section-badge">
                        <i class="fa-solid fa-star"></i>
                        {{ number_format($event->reviews_avg_rating ?? 0, 1) }}/5 from {{ $event->reviews_count ?? 0 }} reviews
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-6">
                    <div class="lg:col-span-5">
                        @include('reviews.partials.form', [
                            'reviewable' => $event,
                            'reviewableType' => App\Models\Event::class,
                            'currentReview' => $currentReview,
                        ])
                    </div>

                    <div class="lg:col-span-7">
                        @include('reviews.partials.list', [
                            'reviews' => $eventReviews,
                            'heading' => 'Recent event reviews',
                            'subheading' => 'Latest impressions from attendees',
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
            const lat = parseFloat({{ $event->latitude ?? 27.5114 }});
            const lng = parseFloat({{ $event->longitude ?? 41.7208 }});
            const location = [lat, lng];

            const map = L.map('event-map', {
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
