@php
    $reviewableType = $reviewableType ?? $reviewable::class;
    $currentRating = old('rating', $currentReview->rating ?? 5);
    $currentComment = old('comment', $currentReview->comment ?? '');
    $formAction = $currentReview ? route('reviews.update', $currentReview) : route('reviews.store');
    $buttonLabel = $currentReview ? 'Update review' : 'Submit review';
@endphp

<div class="review-form-shell" id="review-form">
    <div class="review-form-head">
        <div class="review-form-icon">
            <i class="fa-solid fa-star"></i>
        </div>
        <div>
            <p class="detail-panel-label">Your review</p>
            <h3 class="review-form-title">
                {{ $currentReview ? 'Edit your experience' : 'Share your experience' }}
            </h3>
            <p class="review-form-copy">
                {{ $currentReview ? 'Update your rating or comment any time.' : 'Rate this place and help other travelers choose.' }}
            </p>
        </div>
    </div>

    @auth
        <form action="{{ $formAction }}" method="POST" class="review-form">
            @csrf
            @if($currentReview)
                @method('PUT')
            @endif

            <input type="hidden" name="entity_type" value="{{ $reviewableType }}">
            <input type="hidden" name="entity_id" value="{{ $reviewable->id }}">

            <div class="review-rating-group">
                <span class="review-rating-label">Rating</span>
                <div class="review-rating">
                    @for($star = 5; $star >= 1; $star--)
                        <input type="radio"
                               id="rating-{{ $reviewable->id }}-{{ $star }}"
                               name="rating"
                               value="{{ $star }}"
                               {{ (int) $currentRating === $star ? 'checked' : '' }}>
                        <label for="rating-{{ $reviewable->id }}-{{ $star }}" class="review-rating-option">
                            <i class="fa-solid fa-star"></i>
                            <span>{{ $star }}</span>
                        </label>
                    @endfor
                </div>
                @error('rating')
                    <p class="profile-form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="review-comment-group">
                <label for="comment-{{ $reviewable->id }}" class="review-rating-label">Comment</label>
                <textarea id="comment-{{ $reviewable->id }}"
                          name="comment"
                          rows="5"
                          class="review-comment">{{ $currentComment }}</textarea>
                @error('comment')
                    <p class="profile-form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="review-form-footer">
                <button type="submit" class="review-submit-btn">
                    <i class="fa-solid fa-paper-plane"></i>
                    {{ $buttonLabel }}
                </button>
            </div>
        </form>

        @if($currentReview)
            <form action="{{ route('reviews.destroy', $currentReview) }}" method="POST" class="review-delete-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="review-delete-btn" onclick="return confirm('Remove this review?')">
                    <i class="fa-solid fa-trash"></i>
                    Delete review
                </button>
            </form>
        @endif
    @else
        <div class="review-guest">
            <p class="review-guest-copy">Login to leave a review and rate this place.</p>
            <a href="{{ route('login') }}" class="review-guest-btn">Login</a>
        </div>
    @endauth
</div>
