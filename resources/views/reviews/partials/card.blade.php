@php
    $entity = $review->entity;
    $entityType = class_basename($review->entity_type);
    $entityLabel = match ($entityType) {
        'Attraction' => 'Attraction',
        'Event' => 'Event',
        'Business' => 'Business',
        default => ucfirst($entityType),
    };
    $entityRoute = match ($entityType) {
        'Attraction' => 'attractions.show',
        'Event' => 'events.show',
        'Business' => 'businesses.show',
        default => null,
    };
    $hasEntity = $entity && $entityRoute;
    $showActions = $showActions ?? false;
@endphp

<article class="review-card">
    <div class="review-card-top">
        <div class="review-card-avatar">
            {{ account_initial($review->user->full_name) }}
        </div>

        <div class="review-card-meta">
            <div class="review-card-name-row">
                <h4 class="review-card-name">{{ $review->user->full_name ?? 'Traveler' }}</h4>
                @if(auth()->id() === $review->user_id)
                    <span class="review-card-badge">Your review</span>
                @endif
            </div>

            <p class="review-card-subtitle">
                {{ $entityLabel }}
                <span class="review-card-dot">&middot;</span>
                {{ $review->created_at?->format('M d, Y') }}
            </p>
        </div>
    </div>

    <div class="review-stars" aria-label="Rating {{ $review->rating }} out of 5">
        @for($star = 1; $star <= 5; $star++)
            <i class="fa-solid fa-star {{ $star <= $review->rating ? 'is-filled' : '' }}"></i>
        @endfor
        <span class="review-stars-score">{{ $review->rating }}/5</span>
    </div>

    @if($hasEntity)
        <a href="{{ route($entityRoute, $entity) }}" class="review-entity-link">
            {{ $entity->name ?? 'View place' }}
            <i class="fa-solid fa-arrow-up-right-from-square"></i>
        </a>
    @endif

    <p class="review-card-copy">
        {{ $review->comment ?: 'No comment left for this review.' }}
    </p>

    @if($showActions)
        <div class="review-card-actions">
            @if($hasEntity)
                <a href="{{ route($entityRoute, $entity) }}#review-form" class="review-card-action">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Edit
                </a>
            @endif

            <form action="{{ route('reviews.destroy', $review) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="review-card-action review-card-action-danger" onclick="return confirm('Remove this review?')">
                    <i class="fa-solid fa-trash"></i>
                    Delete
                </button>
            </form>
        </div>
    @endif
</article>
