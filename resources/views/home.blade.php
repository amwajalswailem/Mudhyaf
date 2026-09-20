@extends('layouts.app')

@section('content')
    @php
        $heroAttraction = $attractions->first();
        $heroBusiness = $businesses->first();
        $heroEvent = $events->first();

        $heroImages = [
            data_get($heroAttraction, 'image_url', asset('default-attraction.jpg')),
            data_get($heroBusiness, 'image_url', asset('business.jpg')),
            data_get($heroEvent, 'image_url', asset('event.jpg')),
        ];

        $priceLabels = [
            '1' => 'Budget friendly',
            '2' => 'Mid range',
            '3' => 'Premium',
        ];
    @endphp

    <main class="bg-[#f7f8f5]">
        <!-- Hero Section -->
        <section class="relative h-screen flex items-center justify-center hero-gradient pt-20">
            <div class="text-center px-4 max-w-4xl">
                <h1 class="text-white text-5xl md:text-7xl font-bold mb-6 leading-tight section-title">
                    Experience the Hospitality of Hail
                </h1>
                <p class="text-white text-lg md:text-xl mb-12 opacity-90 max-w-2xl mx-auto">
                    Your smart companion to explore historical landmarks, authentic dining, and local events in the heart of
                    Saudi Arabia.
                </p>
                <!-- Smart Planner Card -->
                <div class="relative max-w-6xl mx-auto -mt-2 z-20">
                    <div style="    border-radius: 20px;" class="bg-white rounded-half shadow-2xl border border-gray-100 p-4">
                        <form method="GET"
                              action="{{ route('explore.redirect') }}"
                              class="flex flex-col lg:flex-row items-center gap-4">
                            {{-- Search --}}
                            <div class="flex-1 w-full text-left border-r-0 lg:border-r border-gray-100 px-4">
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">
                                    Explore
                                </label>
                                <input type="text"
                                       name="search"
                                       placeholder="Attractions, Events..."
                                       class="w-full focus:outline-none text-gray-800 placeholder-gray-400">
                            </div>

                            {{-- Type --}}
                            <div class="flex-1 w-full text-left border-r-0 lg:border-r border-gray-100 px-4">
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">
                                    Type
                                </label>
                                <select name="type"
                                        class="w-full focus:outline-none text-gray-800 bg-transparent cursor-pointer">
                                    <option value="attractions">Attractions</option>
                                    <option value="events">Events</option>
                                    <option value="businesses">Businesses</option>
                                </select>
                            </div>

                            <button type="submit"
                                    class="px-10 py-4 btn-primary rounded-2xl font-bold">
                                Search
                            </button>

                        </form>
                    </div>


                </div>
            </div>
        </section>

        <section class="home-section pt-2">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @auth
                    <div class="recommendation-widget">
                        <div class="home-section-head">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.28em] text-[--primary-gold]">For You</p>
                                <h2 class="home-section-title">Personalized picks</h2>
                                <p class="home-section-copy">
                                    Recommendations based on your favorites, reviews, and recent views.
                                </p>
                            </div>

                            <a href="{{ route('recommendations.index') }}" class="home-section-link">
                                Open recommendations
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>

                        @if($recommendations->isEmpty())
                            <div class="home-empty-state">
                                Save a few places or leave a review to unlock personalized picks.
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($recommendations as $recommendation)
                                    @include('recommendations.partials.card', ['recommendation' => $recommendation])
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endauth

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="feature-card">
                        <div class="feature-icon mb-4"><i class="fa-solid fa-sliders"></i></div>
                        <h3 class="feature-title">Budget aware</h3>
                        <p class="feature-desc text-sm mt-2">Search experiences that fit the trip you want to spend on.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon mb-4"><i class="fa-solid fa-heart"></i></div>
                        <h3 class="feature-title">Save and review</h3>
                        <p class="feature-desc text-sm mt-2">Keep a shortlist of places and share feedback after visiting.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon mb-4"><i class="fa-solid fa-map-location-dot"></i></div>
                        <h3 class="feature-title">Local context</h3>
                        <p class="feature-desc text-sm mt-2">Use maps, addresses, and categories to choose faster.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="home-section">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="home-section-head">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.28em] text-[--primary-gold]">Featured Places</p>
                        <h2 class="home-section-title">Top attractions in Hail</h2>
                        <p class="home-section-copy">
                            Hand-picked attractions with ratings, categories, and location context.
                        </p>
                    </div>

                    <a href="{{ route('attractions.index') }}" class="home-section-link">
                        Browse all attractions
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                @if($attractions->isNotEmpty())
                    <div class="home-feature-grid">
                        @foreach($attractions as $attraction)
                            @php
                                $rating = $attraction->reviews_count
                                    ? number_format((float) $attraction->reviews_avg_rating, 1)
                                    : null;
                                $isFavorited = auth()->check() && in_array((string) $attraction->id, $favoriteMap[\App\Models\Attraction::class] ?? [], true);
                            @endphp
                            <article class="home-card">
                                <div class="home-card-media">
                                    <img src="{{ data_get($attraction, 'image_url', asset('default-attraction.jpg')) }}" alt="{{ $attraction->name }}">

                                    <div class="home-card-badges">
                                        <span class="home-card-badge">
                                            <i class="fa-solid fa-star text-[--primary-gold]"></i>
                                            {{ $rating ?? 'New' }}
                                        </span>

                                        @auth
                                            <button
                                                type="button"
                                                onclick="toggleFavorite(this)"
                                                data-id="{{ $attraction->id }}"
                                                data-type="App\Models\Attraction"
                                                data-favorited="{{ $isFavorited ? '1' : '0' }}"
                                                data-refresh-after-toggle="0"
                                                class="home-card-fav {{ $isFavorited ? 'is-favorited' : '' }}"
                                                aria-label="Toggle favorite"
                                            >
                                                <i class="fa-solid fa-heart {{ $isFavorited ? 'text-red-500' : 'text-slate-400' }}"></i>
                                            </button>
                                        @endauth

                                        @guest
                                            <a href="{{ route('login') }}" class="home-card-fav" aria-label="Login to save">
                                                <i class="fa-solid fa-heart text-slate-400"></i>
                                            </a>
                                        @endguest
                                    </div>
                                </div>

                                <div class="home-card-body">
                                    <div class="flex items-center justify-between gap-3 flex-wrap">
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[rgba(27,67,50,.08)] text-[--deep-green] text-[0.72rem] font-black uppercase tracking-[0.12em]">
                                            {{ $attraction->category?->name ?? 'Attraction' }}
                                        </span>
                                        <span class="home-card-submeta">
                                            <i class="fa-solid fa-comments mr-1"></i>
                                            {{ $attraction->reviews_count ?? 0 }} reviews
                                        </span>
                                    </div>

                                    <h3 class="home-card-title mt-3">{{ $attraction->name }}</h3>
                                    <p class="home-card-copy">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($attraction->description ?? ''), 120) }}
                                    </p>

                                    <div class="home-card-meta">
                                        <span class="home-card-submeta">
                                            <i class="fa-solid fa-location-dot mr-1"></i>
                                            {{ \Illuminate\Support\Str::limit($attraction->address ?? 'Hail, Saudi Arabia', 30) }}
                                        </span>

                                        <a href="{{ route('attractions.show', $attraction) }}" class="home-card-link">
                                            View
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="home-empty-state">No attractions available yet.</div>
                @endif
            </div>
        </section>

        <section class="home-section bg-white/70">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="home-section-head">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.28em] text-[--primary-gold]">Local Picks</p>
                        <h2 class="home-section-title">Popular businesses</h2>
                        <p class="home-section-copy">
                            Restaurants, cafés, and local businesses that are approved and ready to visit.
                        </p>
                    </div>

                    <a href="{{ route('businesses.index') }}" class="home-section-link">
                        Browse all businesses
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                @if($businesses->isNotEmpty())
                    <div class="home-feature-grid">
                        @foreach($businesses as $business)
                            @php
                                $rating = $business->reviews_count
                                    ? number_format((float) $business->reviews_avg_rating, 1)
                                    : null;
                                $isFavorited = auth()->check() && in_array((string) $business->id, $favoriteMap[\App\Models\Business::class] ?? [], true);
                            @endphp
                            <article class="home-card">
                                <div class="home-card-media">
                                    <img src="{{ data_get($business, 'image_url', asset('business.jpg')) }}" alt="{{ $business->name }}">

                                    <div class="home-card-badges">
                                        <span class="home-card-badge">
                                            <i class="fa-solid fa-star text-[--primary-gold]"></i>
                                            {{ $rating ?? 'New' }}
                                        </span>

                                        @auth
                                            <button
                                                type="button"
                                                onclick="toggleFavorite(this)"
                                                data-id="{{ $business->id }}"
                                                data-type="App\Models\Business"
                                                data-favorited="{{ $isFavorited ? '1' : '0' }}"
                                                data-refresh-after-toggle="0"
                                                class="home-card-fav {{ $isFavorited ? 'is-favorited' : '' }}"
                                                aria-label="Toggle favorite"
                                            >
                                                <i class="fa-solid fa-heart {{ $isFavorited ? 'text-red-500' : 'text-slate-400' }}"></i>
                                            </button>
                                        @endauth

                                        @guest
                                            <a href="{{ route('login') }}" class="home-card-fav" aria-label="Login to save">
                                                <i class="fa-solid fa-heart text-slate-400"></i>
                                            </a>
                                        @endguest
                                    </div>
                                </div>

                                <div class="home-card-body">
                                    <div class="flex items-center justify-between gap-3 flex-wrap">
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[rgba(27,67,50,.08)] text-[--deep-green] text-[0.72rem] font-black uppercase tracking-[0.12em]">
                                            {{ $business->category?->name ?? 'Business' }}
                                        </span>
                                        <span class="home-card-submeta">
                                            {{ $priceLabels[$business->price_level] ?? 'Flexible budget' }}
                                        </span>
                                    </div>

                                    <h3 class="home-card-title mt-3">{{ $business->name }}</h3>
                                    <p class="home-card-copy">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($business->description ?? ''), 120) }}
                                    </p>

                                    <div class="home-card-meta">
                                        <span class="home-card-submeta">
                                            <i class="fa-solid fa-location-dot mr-1"></i>
                                            {{ \Illuminate\Support\Str::limit($business->address ?? 'Hail, Saudi Arabia', 30) }}
                                        </span>

                                        <a href="{{ route('businesses.show', $business) }}" class="home-card-link">
                                            View
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="home-empty-state">No approved businesses available yet.</div>
                @endif
            </div>
        </section>

        <section class="home-section">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="home-section-head">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.28em] text-[--primary-gold]">Coming Up</p>
                        <h2 class="home-section-title">Upcoming events</h2>
                        <p class="home-section-copy">
                            Browse approved events in date order and plan what to do next.
                        </p>
                    </div>

                    <a href="{{ route('events.index') }}" class="home-section-link">
                        Browse all events
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                @if($events->isNotEmpty())
                    <div class="home-feature-grid">
                        @foreach($events as $event)
                            @php
                                $rating = $event->reviews_count
                                    ? number_format((float) $event->reviews_avg_rating, 1)
                                    : null;
                                $isFavorited = auth()->check() && in_array((string) $event->id, $favoriteMap[\App\Models\Event::class] ?? [], true);
                            @endphp
                            <article class="home-card">
                                <div class="home-card-media">
                                    <img src="{{ data_get($event, 'image_url', asset('event.jpg')) }}" alt="{{ $event->name }}">

                                    <div class="home-card-badges">
                                        <span class="home-card-badge">
                                            <i class="fa-solid fa-calendar-day text-[--primary-gold]"></i>
                                            {{ optional($event->start_time)->format('M d') ?? 'Soon' }}
                                        </span>

                                        @auth
                                            <button
                                                type="button"
                                                onclick="toggleFavorite(this)"
                                                data-id="{{ $event->id }}"
                                                data-type="App\Models\Event"
                                                data-favorited="{{ $isFavorited ? '1' : '0' }}"
                                                data-refresh-after-toggle="0"
                                                class="home-card-fav {{ $isFavorited ? 'is-favorited' : '' }}"
                                                aria-label="Toggle favorite"
                                            >
                                                <i class="fa-solid fa-heart {{ $isFavorited ? 'text-red-500' : 'text-slate-400' }}"></i>
                                            </button>
                                        @endauth

                                        @guest
                                            <a href="{{ route('login') }}" class="home-card-fav" aria-label="Login to save">
                                                <i class="fa-solid fa-heart text-slate-400"></i>
                                            </a>
                                        @endguest
                                    </div>
                                </div>

                                <div class="home-card-body">
                                    <div class="flex items-center justify-between gap-3 flex-wrap">
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[rgba(27,67,50,.08)] text-[--deep-green] text-[0.72rem] font-black uppercase tracking-[0.12em]">
                                            {{ $event->category?->name ?? 'Event' }}
                                        </span>
                                        <span class="home-card-submeta">
                                            {{ $rating ?? 'New' }}
                                        </span>
                                    </div>

                                    <h3 class="home-card-title mt-3">{{ $event->name }}</h3>
                                    <p class="home-card-copy">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($event->description ?? ''), 120) }}
                                    </p>

                                    <div class="home-card-meta">
                                        <span class="home-card-submeta">
                                            <i class="fa-solid fa-location-dot mr-1"></i>
                                            {{ \Illuminate\Support\Str::limit($event->address ?? 'Hail, Saudi Arabia', 30) }}
                                        </span>

                                        <a href="{{ route('events.show', $event) }}" class="home-card-link">
                                            View
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="home-empty-state">No approved events available yet.</div>
                @endif
            </div>
        </section>

        <section class="home-section pt-0 pb-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="home-cta-shell">
                    <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-6 items-center">
                        <div>
                            <h2 class="home-cta-title">Build your next Hail itinerary in one place.</h2>
                            <p class="home-cta-copy">
                                Save favorites, leave reviews, and let Mudhyaf help you decide faster by budget, category, and location.
                            </p>
                        </div>

                        <div class="home-cta-actions">
                            @guest
                                <a href="{{ route('register') }}" class="btn-primary px-6 py-3 rounded-full font-semibold shadow-lg">
                                    Create account
                                </a>
                            @endguest
                            @auth
                                <a href="{{ route('my_profile.show') }}" class="btn-primary px-6 py-3 rounded-full font-semibold shadow-lg">
                                    My profile
                                </a>
                            @endauth
                            <a href="{{ route('attractions.index') }}" class="nav-ghost-btn border-white/20 text-white px-6 py-3 rounded-full font-semibold">
                                Start exploring
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
