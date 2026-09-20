@php
    $item = $recommendation['item'];
    $entityType = class_basename($recommendation['entity_type']);
    $routeName = match ($entityType) {
        'Attraction' => 'attractions.show',
        'Business' => 'businesses.show',
        'Event' => 'events.show',
        default => null,
    };

    $badge = match ($entityType) {
        'Attraction' => 'fa-solid fa-location-dot',
        'Business' => 'fa-solid fa-store',
        'Event' => 'fa-solid fa-calendar-days',
        default => 'fa-solid fa-compass',
    };

    $label = match ($entityType) {
        'Attraction' => 'Attraction',
        'Business' => 'Business',
        'Event' => 'Event',
        default => $entityType,
    };
@endphp

<article class="recommendation-card">
    <div class="recommendation-card-media">
        <img src="{{ data_get($item, 'image_url', asset($entityType === 'Business' ? 'business.jpg' : ($entityType === 'Event' ? 'event.jpg' : 'default-attraction.jpg'))) }}" alt="{{ $item->name }}">
        <div class="recommendation-card-score">
            <i class="{{ $badge }}"></i>
            <span>{{ number_format($recommendation['score'], 1) }}</span>
        </div>
    </div>

    <div class="recommendation-card-body">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <span class="recommendation-card-type">{{ $label }}</span>
            <span class="recommendation-card-meta">
                <i class="fa-solid fa-star"></i>
                {{ number_format((float) data_get($item, 'reviews_avg_rating', 0), 1) }}
            </span>
        </div>

        <h3 class="recommendation-card-title">{{ $item->name }}</h3>
        <p class="recommendation-card-copy">
            {{ \Illuminate\Support\Str::limit(strip_tags($item->description ?? ''), 110) }}
        </p>

        <div class="recommendation-reasons">
            @foreach(array_slice($recommendation['reasons'] ?? [], 0, 3) as $reason)
                <span class="recommendation-reason">{{ $reason }}</span>
            @endforeach
        </div>

        <div class="recommendation-card-footer">
            <span class="recommendation-card-location">
                <i class="fa-solid fa-location-dot"></i>
                {{ \Illuminate\Support\Str::limit($item->address ?? 'Hail, Saudi Arabia', 28) }}
            </span>

            @if($routeName)
                <a href="{{ route($routeName, $item) }}" class="recommendation-card-link">
                    Open
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            @endif
        </div>
    </div>
</article>
