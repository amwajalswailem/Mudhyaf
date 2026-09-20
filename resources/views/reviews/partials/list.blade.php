@php
    $reviews = $reviews ?? collect();
    $heading = $heading ?? 'Recent reviews';
    $subheading = $subheading ?? 'What travelers are saying';
@endphp

<div class="review-list-shell">
    <div class="review-list-head">
        <div>
            <p class="detail-panel-label">Community feedback</p>
            <h3 class="review-list-title">{{ $heading }}</h3>
            <p class="review-list-copy">{{ $subheading }}</p>
        </div>

        <div class="review-list-count">
            <span>{{ $reviews->count() }}</span>
            reviews
        </div>
    </div>

    @if($reviews->isEmpty())
        <div class="review-empty">
            <i class="fa-regular fa-message"></i>
            <p>No reviews yet. Be the first to leave feedback.</p>
        </div>
    @else
        <div class="review-list">
            @foreach($reviews as $review)
                @include('reviews.partials.card', ['review' => $review, 'showActions' => false])
            @endforeach
        </div>
    @endif
</div>
