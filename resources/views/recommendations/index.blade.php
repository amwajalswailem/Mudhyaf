@extends('layouts.app')

@section('content')
    @php
        $scopeTabs = collect([
            ['key' => null, 'label' => 'All', 'icon' => 'fa-solid fa-sparkles'],
            ['key' => 'attractions', 'label' => 'Attractions', 'icon' => 'fa-solid fa-location-dot'],
            ['key' => 'businesses', 'label' => 'Businesses', 'icon' => 'fa-solid fa-store'],
            ['key' => 'events', 'label' => 'Events', 'icon' => 'fa-solid fa-calendar-days'],
        ]);

        $selectedCategories = collect(old('preferred_categories', $preferences?->preferred_categories ?? []))->map(fn ($value) => (string) $value)->all();
        $selectedTags = collect(old('preferred_tags', $preferences?->preferred_tags ?? []))->map(fn ($value) => (string) $value)->all();
    @endphp
    <div class="pt-28 pb-24 recommendation-hero">
        <div>
            <p class="detail-kicker detail-kicker-dark">Personalized Recommendations</p>
            <h1 class="recommendation-title">Places matched to your budget and activity</h1>
            <p class="recommendation-copy">
                Use your preferences, favorites, views, and reviews to guide the ranking. Budget values follow the same
                1 / 2 / 3 structure used across the business schema.
            </p>
        </div>

        <div class="recommendation-hero-stats">
            <div class="recommendation-stat">
                <span>Top picks</span>
                <strong>{{ $recommendations->count() }}</strong>
            </div>
            <div class="recommendation-stat">
                <span>Scope</span>
                <strong>{{ $scope ? ucfirst($scope) : 'All' }}</strong>
            </div>
        </div>
    </div>

    <section class="pt-10 pb-10 bg-[#f7f8f5] min-h-screen">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="profile-alert profile-alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                <div class="xl:col-span-4">
                    <div class="recommendation-panel">
                        <div class="flex items-center justify-between gap-4 mb-6">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.28em] text-[--primary-gold]">Preferences</p>
                                <h2 class="text-2xl font-extrabold text-[--deep-green] mt-2">Tell us what you like</h2>
                            </div>
                            <div class="w-12 h-12 rounded-2xl bg-[rgba(27,67,50,.08)] text-[--deep-green] flex items-center justify-center">
                                <i class="fa-solid fa-sliders"></i>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('recommendations.preferences.update') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="home-field-label" for="preferred_price_level">Budget Level</label>
                                <select id="preferred_price_level" name="preferred_price_level" class="home-select">
                                    <option value="">Any budget</option>
                                    <option value="1" {{ old('preferred_price_level', $preferences?->preferred_price_level) === '1' ? 'selected' : '' }}>1 - Budget friendly</option>
                                    <option value="2" {{ old('preferred_price_level', $preferences?->preferred_price_level) === '2' ? 'selected' : '' }}>2 - Mid range</option>
                                    <option value="3" {{ old('preferred_price_level', $preferences?->preferred_price_level) === '3' ? 'selected' : '' }}>3 - Premium</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="home-field-label" for="min_budget">Min Budget</label>
                                    <input id="min_budget" type="number" step="0.01" min="0" name="min_budget" value="{{ old('min_budget', $preferences?->min_budget) }}" class="home-field" placeholder="Optional">
                                </div>
                                <div>
                                    <label class="home-field-label" for="max_budget">Max Budget</label>
                                    <input id="max_budget" type="number" step="0.01" min="0" name="max_budget" value="{{ old('max_budget', $preferences?->max_budget) }}" class="home-field" placeholder="Optional">
                                </div>
                            </div>

                            <div>
                                <label class="home-field-label" for="preferred_visit_time">Preferred Time</label>
                                <select id="preferred_visit_time" name="preferred_visit_time" class="home-select">
                                    <option value="">Any time</option>
                                    @foreach(['morning' => 'Morning', 'afternoon' => 'Afternoon', 'evening' => 'Evening', 'night' => 'Night'] as $value => $label)
                                        <option value="{{ $value }}" {{ old('preferred_visit_time', $preferences?->preferred_visit_time) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="home-field-label">Preferred Categories</label>
                                <div class="recommendation-check-grid">
                                    @foreach($categories as $category)
                                        <label class="recommendation-check">
                                            <input type="checkbox" name="preferred_categories[]" value="{{ $category->id }}" {{ in_array((string) $category->id, $selectedCategories, true) ? 'checked' : '' }}>
                                            <span>{{ $category->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label class="home-field-label">Preferred Content Types</label>
                                <div class="recommendation-check-grid recommendation-check-grid-compact">
                                    @foreach(['Attraction' => 'Attractions', 'Business' => 'Businesses', 'Event' => 'Events'] as $value => $label)
                                        <label class="recommendation-check">
                                            <input type="checkbox" name="preferred_tags[]" value="{{ $value }}" {{ in_array($value, $selectedTags, true) ? 'checked' : '' }}>
                                            <span>{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit" class="btn-primary w-full py-3 rounded-full font-semibold shadow-lg">
                                Save preferences
                            </button>
                        </form>
                    </div>
                </div>

                <div class="xl:col-span-8 space-y-5">
                    <div class="recommendation-tabs">
                        @foreach($scopeTabs as $tab)
                            <a href="{{ route('recommendations.index', $tab['key'] ? ['type' => $tab['key']] : []) }}"
                               class="recommendation-tab {{ ($scope ?? null) === $tab['key'] || ((is_null($scope) || $scope === '') && is_null($tab['key'])) ? 'is-active' : '' }}">
                                <i class="{{ $tab['icon'] }}"></i>
                                <span>{{ $tab['label'] }}</span>
                            </a>
                        @endforeach
                    </div>

                    @if($recommendations->isEmpty())
                        <div class="home-empty-state">
                            No recommendations yet. Save a few likes, views, or reviews to get a better ranking.
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @foreach($recommendations as $recommendation)
                                @include('recommendations.partials.card', ['recommendation' => $recommendation])
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
