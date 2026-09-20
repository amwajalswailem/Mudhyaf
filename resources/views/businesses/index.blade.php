@extends('layouts.app')

@section('content')

    <section class="pt-28 pb-24 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6">

            <div class="text-center mb-14">
                <p class="detail-kicker detail-kicker-dark">Businesses</p>
                <h2 class="text-4xl font-bold text-[--deep-green] section-title mt-3">
                    Explore Businesses
                </h2>
                <p class="text-gray-500 mt-3 max-w-2xl mx-auto">
                    Cafes, restaurants, and local places in Hail with a cleaner catalog layout.
                </p>
            </div>

            <form method="GET" class="catalog-filter-card">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search businesses..."
                       class="catalog-filter-input">

                <select name="category" class="catalog-filter-input">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <select name="budget" class="catalog-filter-input">
                    <option value="">All Budgets</option>
                    <option value="1" {{ request('budget') == '1' ? 'selected' : '' }}>Economy</option>
                    <option value="2" {{ request('budget') == '2' ? 'selected' : '' }}>Standard</option>
                    <option value="3" {{ request('budget') == '3' ? 'selected' : '' }}>Luxury</option>
                </select>

                <button type="submit" class="catalog-filter-button">
                    Filter
                </button>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($businesses as $business)
                    <div class="catalog-card">
                        <div class="catalog-card-media business-card-media">
                            <img src="{{ $business->image_url }}"
                                 alt="{{ $business->name }}"
                                 class="catalog-card-background">
                            <div class="catalog-card-media-overlay"></div>
                            <div class="catalog-card-media-content">
                                <div>
                                    <p class="catalog-card-kicker">Business</p>
                                    <h4 class="catalog-card-title">{{ $business->name }}</h4>
                                </div>

                                @auth
                                    <button
                                        onclick="toggleFavorite(this)"
                                        data-id="{{ $business->id }}"
                                        data-type="App\Models\Business"
                                        class="catalog-favorite-btn">
                                        <svg class="w-6 h-6 {{ $business->isFavoritedBy(auth()->user()) ? 'text-red-500' : 'text-gray-400' }}"
                                             fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                    </button>
                                @endauth
                            </div>

                            <div class="catalog-card-metrics">
                                <span>{{ optional($business->category)->name ?? 'Uncategorized' }}</span>
                                <span>{{ $business->price_level ? 'Level ' . $business->price_level : 'Price N/A' }}</span>
                            </div>
                        </div>

                        <div class="catalog-card-body">
                            <p class="catalog-card-description">
                                {{ $business->description ?? 'No description provided.' }}
                            </p>

                            <div class="catalog-card-footer">
                                <span class="catalog-card-rating">
                                    <i class="fa-solid fa-star"></i>
                                    {{ number_format($business->reviews_avg_rating ?? 0, 1) }}
                                </span>
                                <a href="{{ route('businesses.show', $business) }}" class="catalog-card-link">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="col-span-3 text-center text-gray-400">No businesses found.</p>
                @endforelse
            </div>

            <div class="mt-14">
                {{ $businesses->withQueryString()->links() }}
            </div>
        </div>
    </section>

@endsection
