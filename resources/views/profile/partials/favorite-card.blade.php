@php
    $entity = $favorite->entity;
    $entityType = class_basename($favorite->entity_type);
    $displayType = ($section['type'] ?? null) === 'All' ? $entityType : ($section['type'] ?? $entityType);
    $label = ($section['type'] ?? null) === 'All' ? match ($entityType) {
        'Attraction' => 'Attractions',
        'Event' => 'Events',
        'Business' => 'Businesses',
        default => ucfirst($entityType),
    } : ($section['label'] ?? match ($entityType) {
        'Attraction' => 'Attractions',
        'Event' => 'Events',
        'Business' => 'Businesses',
        default => ucfirst($entityType),
    });
    $showRoute = $section['show_route'] ?? match ($entityType) {
        'Attraction' => 'attractions.show',
        'Event' => 'events.show',
        'Business' => 'businesses.show',
        default => null,
    };
    $hasEntity = $entity && $showRoute;
    $showHref = $hasEntity ? route($showRoute, $entity) : '#';
@endphp

<article class="favorite-card favorite-card-tile" data-favorite-card data-favorite-type="{{ $displayType }}">
    <div class="favorite-card-media">
        <img src="{{ $entity?->image_url ?: asset('default-attraction.jpg') }}"
             alt="{{ $entity?->name ?? 'Favorite item' }}">
        <div class="favorite-card-overlay"></div>

        @auth
            <button type="button"
                    onclick="toggleFavorite(this)"
                    data-id="{{ $favorite->entity_id }}"
                    data-type="{{ $favorite->entity_type }}"
                    data-favorited="1"
                    data-refresh-after-toggle="1"
                    class="favorite-unlike-btn"
                    aria-label="Unlike {{ $entity?->name ?? 'saved item' }}">
                <svg class="favorite-icon text-red-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
                <span class="favorite-label">Unlike</span>
            </button>
        @endauth
    </div>

    <div class="favorite-card-body">
        <div class="favorite-card-topline">
            <span class="favorite-card-type">{{ $label }}</span>
            <span class="favorite-card-date">
                {{ $favorite->created_at?->format('M d, Y') }}
            </span>
        </div>

        <h4 class="favorite-card-title">{{ $entity?->name ?? 'Saved item' }}</h4>
        <p class="favorite-card-copy">
            {{ $entity?->description ?? 'This saved item is no longer available.' }}
        </p>

        <div class="favorite-card-footer">
            @if($hasEntity)
                <a href="{{ $showHref }}" class="favorite-card-link">View details</a>
            @else
                <span class="favorite-card-muted">Unavailable</span>
            @endif

            <span class="favorite-card-meta">
                <i class="fa-regular fa-clock"></i>
                Saved {{ $favorite->created_at?->diffForHumans() ?? 'recently' }}
            </span>
        </div>
    </div>
</article>
