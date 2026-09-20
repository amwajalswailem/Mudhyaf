@extends('layouts.app')

@section('content')
    @php
        $favoriteMap = $favoriteSections->keyBy('type');
        $reviewMap = $reviewSections->keyBy('type');
        $allReviewCount = $stats['reviews'];
        $attractionReviewCount = $reviewMap->get('Attraction') ? $reviewMap->get('Attraction')['items']->count() : 0;
        $eventReviewCount = $reviewMap->get('Event') ? $reviewMap->get('Event')['items']->count() : 0;
        $businessReviewCount = $reviewMap->get('Business') ? $reviewMap->get('Business')['items']->count() : 0;

        $favoriteTabs = collect([
            ['key' => 'all', 'label' => 'All Likes', 'count' => $stats['total_favorites'], 'icon' => 'fa-solid fa-heart'],
            ['key' => 'Attraction', 'label' => 'Attractions', 'count' => $stats['attractions'], 'icon' => 'fa-solid fa-location-dot'],
            ['key' => 'Event', 'label' => 'Events', 'count' => $stats['events'], 'icon' => 'fa-solid fa-calendar-days'],
            ['key' => 'Business', 'label' => 'Businesses', 'count' => $stats['businesses'], 'icon' => 'fa-solid fa-store'],
        ]);

        $reviewTabs = collect([
            ['key' => 'all', 'label' => 'All Reviews', 'count' => $allReviewCount, 'icon' => 'fa-solid fa-pen-ruler'],
            ['key' => 'Attraction', 'label' => 'Attractions', 'count' => $attractionReviewCount, 'icon' => 'fa-solid fa-location-dot'],
            ['key' => 'Event', 'label' => 'Events', 'count' => $eventReviewCount, 'icon' => 'fa-solid fa-calendar-days'],
            ['key' => 'Business', 'label' => 'Businesses', 'count' => $businessReviewCount, 'icon' => 'fa-solid fa-store'],
        ]);

        $profileInitial = account_initial(auth()->user()->full_name);
        $memberSince = $user->created_at?->format('M Y') ?? 'Recently';
    @endphp

    <section class="pt-28 pb-24 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6 space-y-8">
            <div class="profile-hero">
                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="profile-avatar">{{ $profileInitial }}</div>
                        <div>
                            <p class="detail-kicker detail-kicker-dark">Tourist Profile</p>
                            <h2 class="profile-name">{{ $user->full_name }}</h2>
                            <p class="profile-meta">{{ $user->email }}</p>
                            <p class="profile-role">
                                {{ ucfirst($user->role) }} &middot; Member since {{ $memberSince }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('profile.edit') }}" class="profile-action-btn profile-action-btn-primary">
                            <i class="fa-solid fa-pen-to-square"></i>
                            Edit Profile
                        </a>
                        <a href="{{ route('attractions.index') }}" class="profile-action-btn">
                            <i class="fa-solid fa-compass"></i>
                            Explore More
                        </a>
                    </div>
                </div>

                <div class="profile-stats mt-6">
                    <div class="profile-stat">
                        <span class="profile-stat-label">All likes</span>
                        <span class="profile-stat-value">{{ $stats['total_favorites'] }}</span>
                    </div>
                    <div class="profile-stat">
                        <span class="profile-stat-label">Attractions</span>
                        <span class="profile-stat-value">{{ $stats['attractions'] }}</span>
                    </div>
                    <div class="profile-stat">
                        <span class="profile-stat-label">Events</span>
                        <span class="profile-stat-value">{{ $stats['events'] }}</span>
                    </div>
                    <div class="profile-stat">
                        <span class="profile-stat-label">Businesses</span>
                        <span class="profile-stat-value">{{ $stats['businesses'] }}</span>
                    </div>
                </div>
            </div>

            <div class="profile-section-switcher" role="tablist" aria-label="Profile content sections">
                <button type="button"
                        class="profile-section-switcher-btn is-active"
                        data-section-tab="likes"
                        role="tab"
                        aria-selected="true">
                    <span class="profile-section-switcher-icon">
                        <i class="fa-solid fa-heart"></i>
                    </span>
                    <span>
                        <span class="profile-section-switcher-title">Likes</span>
                        <span class="profile-section-switcher-copy">Saved places and favorites</span>
                    </span>
                </button>

                <button type="button"
                        class="profile-section-switcher-btn"
                        data-section-tab="reviews"
                        role="tab"
                        aria-selected="false">
                    <span class="profile-section-switcher-icon">
                        <i class="fa-solid fa-pen-ruler"></i>
                    </span>
                    <span>
                        <span class="profile-section-switcher-title">Reviews</span>
                        <span class="profile-section-switcher-copy">Ratings and feedback history</span>
                    </span>
                </button>
            </div>

            <div class="recommendation-widget mt-8">
                <div class="home-section-head">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.28em] text-[--primary-gold]">Recommendations</p>
                        <h2 class="home-section-title">Personalized for you</h2>
                        <p class="home-section-copy">
                            Fine tune your preferences and explore the items most likely to match your budget and taste.
                        </p>
                    </div>

                    <a href="{{ route('recommendations.index') }}" class="home-section-link">
                        Edit preferences
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                @if($recommendedPlaces->isEmpty())
                    <div class="home-empty-state">
                        Start saving favorites or reviewing places to unlock personalized recommendations.
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($recommendedPlaces->take(3) as $recommendation)
                            @include('recommendations.partials.card', ['recommendation' => $recommendation])
                        @endforeach
                    </div>
                @endif
            </div>

            @if(session('success'))
                <div class="profile-alert profile-alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="profile-section-panels">
                <div class="profile-section-panel is-active" data-section-panel="likes" role="tabpanel">
                    <div class="profile-tabs" role="tablist" aria-label="Profile likes tabs">
                        @foreach($favoriteTabs as $tab)
                            <button type="button"
                                    class="profile-tab {{ $loop->first ? 'is-active' : '' }}"
                                    data-profile-tab="{{ $tab['key'] }}"
                                    role="tab"
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                <i class="{{ $tab['icon'] }}"></i>
                                <span>{{ $tab['label'] }}</span>
                                <span class="profile-tab-count">{{ $tab['count'] }}</span>
                            </button>
                        @endforeach
                    </div>

                    @if($favorites->isEmpty())
                        <div class="empty-state mt-6">
                            <div class="empty-state-icon">
                                <i class="fa-regular fa-heart"></i>
                            </div>
                            <h3 class="empty-state-title">No likes yet</h3>
                            <p class="empty-state-copy">
                                Start exploring attractions, events, and businesses to save the places you like.
                            </p>
                            <a href="{{ route('attractions.index') }}" class="empty-state-action">
                                Explore now
                            </a>
                        </div>
                    @else
                        <div class="profile-panels mt-6">
                            <div class="profile-panel is-active" data-profile-panel="all" role="tabpanel">
                                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                                    @foreach($favorites as $favorite)
                                        @include('profile.partials.favorite-card', [
                                            'favorite' => $favorite,
                                            'section' => ['type' => 'All', 'label' => 'All Likes', 'show_route' => null],
                                        ])
                                    @endforeach
                                </div>
                            </div>

                            @foreach($favoriteTabs->slice(1) as $tab)
                                @php
                                    $section = $favoriteMap->get($tab['key']);
                                @endphp
                                <div class="profile-panel" data-profile-panel="{{ $tab['key'] }}" role="tabpanel" hidden>
                                    @if($section && $section['items']->isNotEmpty())
                                        <div class="favorites-section mt-0">
                                            <div class="favorites-section-head">
                                                <div class="flex items-center gap-3">
                                                    <div class="favorites-section-icon">
                                                        <i class="{{ $section['icon'] }}"></i>
                                                    </div>
                                                    <div>
                                                        <h3 class="favorites-section-title">{{ $section['label'] }}</h3>
                                                        <p class="favorites-section-subtitle">{{ $section['items']->count() }} saved items</p>
                                                    </div>
                                                </div>

                                                <a href="{{ route($section['index_route']) }}" class="favorites-section-link">
                                                    Browse all
                                                </a>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 mt-6">
                                                @foreach($section['items'] as $favorite)
                                                    @include('profile.partials.favorite-card', [
                                                        'favorite' => $favorite,
                                                        'section' => $section,
                                                    ])
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="{{ $tab['icon'] }}"></i>
                                            </div>
                                            <h3 class="empty-state-title">No {{ strtolower($tab['label']) }} yet</h3>
                                            <p class="empty-state-copy">
                                                Save a few {{ strtolower($tab['label']) }} to see them appear here.
                                            </p>
                                            <a href="{{ route('attractions.index') }}" class="empty-state-action">
                                                Explore now
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="profile-section-panel" data-section-panel="reviews" role="tabpanel" hidden>
                    <div class="profile-tabs" role="tablist" aria-label="Profile reviews tabs">
                        @foreach($reviewTabs as $tab)
                            <button type="button"
                                    class="profile-tab {{ $loop->first ? 'is-active' : '' }}"
                                    data-review-tab="{{ $tab['key'] }}"
                                    role="tab"
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                <i class="{{ $tab['icon'] }}"></i>
                                <span>{{ $tab['label'] }}</span>
                                <span class="profile-tab-count">{{ $tab['count'] }}</span>
                            </button>
                        @endforeach
                    </div>

                    @if($reviews->isEmpty())
                        <div class="empty-state mt-6">
                            <div class="empty-state-icon">
                                <i class="fa-solid fa-pen-ruler"></i>
                            </div>
                            <h3 class="empty-state-title">No reviews yet</h3>
                            <p class="empty-state-copy">
                                After you rate attractions, events, or businesses, your reviews will appear here.
                            </p>
                            <a href="{{ route('attractions.index') }}" class="empty-state-action">
                                Start reviewing
                            </a>
                        </div>
                    @else
                        <div class="profile-panels mt-6">
                            <div class="profile-panel is-active" data-review-panel="all" role="tabpanel">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    @foreach($reviews as $review)
                                        @include('reviews.partials.card', ['review' => $review, 'showActions' => true])
                                    @endforeach
                                </div>
                            </div>

                            @foreach($reviewTabs->slice(1) as $tab)
                                @php
                                    $section = $reviewMap->get($tab['key']) ?? [
                                        'type' => $tab['key'],
                                        'label' => $tab['label'],
                                        'icon' => $tab['icon'],
                                        'show_route' => null,
                                        'items' => collect(),
                                    ];
                                    $firstReviewEntity = $section['items']->first()?->entity ?? null;
                                @endphp
                                <div class="profile-panel" data-review-panel="{{ $tab['key'] }}" role="tabpanel" hidden>
                                    @if($section && $section['items']->isNotEmpty())
                                        <div class="favorites-section mt-0">
                                            <div class="favorites-section-head">
                                                <div class="flex items-center gap-3">
                                                    <div class="favorites-section-icon">
                                                        <i class="{{ $section['icon'] }}"></i>
                                                    </div>
                                                    <div>
                                                        <h3 class="favorites-section-title">{{ $section['label'] }}</h3>
                                                        <p class="favorites-section-subtitle">{{ $section['items']->count() }} written reviews</p>
                                                    </div>
                                                </div>

                                                @if($section['show_route'] && $firstReviewEntity)
                                                    <a href="{{ route($section['show_route'], $firstReviewEntity) }}" class="favorites-section-link">
                                                        Open place
                                                    </a>
                                                @endif
                                            </div>

                                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                                                @foreach($section['items'] as $review)
                                                    @include('reviews.partials.card', ['review' => $review, 'showActions' => true])
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="{{ $tab['icon'] }}"></i>
                                            </div>
                                            <h3 class="empty-state-title">No {{ strtolower($tab['label']) }} yet</h3>
                                            <p class="empty-state-copy">
                                                Leave a few {{ strtolower($tab['label']) }} to see them appear here.
                                            </p>
                                            <a href="{{ route('attractions.index') }}" class="empty-state-action">
                                                Explore now
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <script>
        (function () {
            const sectionTabs = document.querySelectorAll('[data-section-tab]');
            const sectionPanels = document.querySelectorAll('[data-section-panel]');

            const activateSection = (key) => {
                sectionTabs.forEach((tab) => {
                    const active = tab.dataset.sectionTab === key;
                    tab.classList.toggle('is-active', active);
                    tab.setAttribute('aria-selected', active ? 'true' : 'false');
                });

                sectionPanels.forEach((panel) => {
                    const active = panel.dataset.sectionPanel === key;
                    panel.classList.toggle('is-active', active);
                    panel.toggleAttribute('hidden', !active);
                });
            };

            sectionTabs.forEach((tab) => {
                tab.addEventListener('click', () => activateSection(tab.dataset.sectionTab));
            });

            const likesTabs = document.querySelectorAll('[data-profile-tab]');
            const likesPanels = document.querySelectorAll('[data-profile-panel]');

            const activateLikes = (key) => {
                likesTabs.forEach((tab) => {
                    const active = tab.dataset.profileTab === key;
                    tab.classList.toggle('is-active', active);
                    tab.setAttribute('aria-selected', active ? 'true' : 'false');
                });

                likesPanels.forEach((panel) => {
                    const active = panel.dataset.profilePanel === key;
                    panel.classList.toggle('is-active', active);
                    panel.toggleAttribute('hidden', !active);
                });
            };

            likesTabs.forEach((tab) => {
                tab.addEventListener('click', () => activateLikes(tab.dataset.profileTab));
            });

            const reviewTabs = document.querySelectorAll('[data-review-tab]');
            const reviewPanels = document.querySelectorAll('[data-review-panel]');

            const activateReviews = (key) => {
                reviewTabs.forEach((tab) => {
                    const active = tab.dataset.reviewTab === key;
                    tab.classList.toggle('is-active', active);
                    tab.setAttribute('aria-selected', active ? 'true' : 'false');
                });

                reviewPanels.forEach((panel) => {
                    const active = panel.dataset.reviewPanel === key;
                    panel.classList.toggle('is-active', active);
                    panel.toggleAttribute('hidden', !active);
                });
            };

            reviewTabs.forEach((tab) => {
                tab.addEventListener('click', () => activateReviews(tab.dataset.reviewTab));
            });
        })();
    </script>
@endsection
