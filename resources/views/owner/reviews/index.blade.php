@extends('admin.layouts.app')

@section('role', 'Business Owner')

@section('content')
    @php
        $tab = request('tab', 'all');
        $tabStyles = fn ($key) => $tab === $key
            ? 'bg-[--deep-green] text-white shadow-lg shadow-[--deep-green]/20'
            : 'bg-white text-gray-600 border border-gray-200 hover:border-[--deep-green]/30 hover:text-[--deep-green]';
        $badgeStyles = fn ($key) => $tab === $key
            ? 'bg-white/20 text-white'
            : 'bg-gray-100 text-gray-600';
    @endphp

    <div class="space-y-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[--primary-gold]">Business Owner</p>
                <h2 class="text-3xl font-extrabold text-gray-800 mt-2">Reviews</h2>
                <p class="text-gray-500 mt-2">Feedback on your business and owner-created events.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('owner.profile.edit') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-white border border-gray-200 text-gray-700 font-bold shadow-sm hover:shadow-md transition">
                    <i class="fa-solid fa-user-pen"></i>
                    Update Profile
                </a>
                <a href="{{ route('owner.dashboard') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-[--deep-green] text-white font-bold shadow-lg hover:shadow-xl transition">
                    <i class="fa-solid fa-chart-line"></i>
                    Dashboard
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6">
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Total Reviews</p>
                <p class="text-3xl font-extrabold text-[--deep-green] mt-3">{{ $summary['total'] }}</p>
                <p class="text-sm text-gray-500 mt-2">All business and event feedback</p>
            </div>

            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6">
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Business Reviews</p>
                <p class="text-3xl font-extrabold text-blue-600 mt-3">{{ $summary['business_count'] }}</p>
                <p class="text-sm text-gray-500 mt-2">Average {{ number_format($summary['business_avg'], 1) }}/5</p>
            </div>

            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6">
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Event Reviews</p>
                <p class="text-3xl font-extrabold text-amber-600 mt-3">{{ $summary['event_count'] }}</p>
                <p class="text-sm text-gray-500 mt-2">Average {{ number_format($summary['event_avg'], 1) }}/5</p>
            </div>

            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6">
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Overall Rating</p>
                <p class="text-3xl font-extrabold text-emerald-600 mt-3">{{ number_format($summary['overall_avg'], 1) }}</p>
                <p class="text-sm text-gray-500 mt-2">Across your visible listings</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-5 lg:p-6">
            <div class="flex flex-wrap gap-3 mb-6">
                <a href="{{ route('owner.reviews.index', ['tab' => 'all']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl font-bold transition {{ $tabStyles('all') }}">
                    <i class="fa-solid fa-layer-group"></i>
                    All Reviews
                    <span class="ml-1 text-xs px-2 py-0.5 rounded-full {{ $badgeStyles('all') }}">{{ $allReviews->count() }}</span>
                </a>
                <a href="{{ route('owner.reviews.index', ['tab' => 'business']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl font-bold transition {{ $tabStyles('business') }}">
                    <i class="fa-solid fa-store"></i>
                    Business
                    <span class="ml-1 text-xs px-2 py-0.5 rounded-full {{ $badgeStyles('business') }}">{{ $businessReviews->count() }}</span>
                </a>
                <a href="{{ route('owner.reviews.index', ['tab' => 'events']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl font-bold transition {{ $tabStyles('events') }}">
                    <i class="fa-solid fa-calendar-days"></i>
                    Events
                    <span class="ml-1 text-xs px-2 py-0.5 rounded-full {{ $badgeStyles('events') }}">{{ $eventReviews->count() }}</span>
                </a>
            </div>

            @if($allReviews->isEmpty())
                <div class="rounded-3xl border border-dashed border-gray-200 bg-gray-50 px-6 py-14 text-center">
                    <div class="w-16 h-16 mx-auto rounded-3xl bg-white shadow-sm border border-gray-100 flex items-center justify-center text-2xl text-gray-400">
                        <i class="fa-regular fa-comment-dots"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mt-5">No reviews yet</h3>
                    <p class="text-gray-500 mt-2 max-w-md mx-auto">
                        Reviews for your business and your events will appear here once tourists start leaving feedback.
                    </p>
                </div>
            @else
                <div class="space-y-5">
                    @if($tab === 'business')
                        @php($collection = $businessReviews)
                    @elseif($tab === 'events')
                        @php($collection = $eventReviews)
                    @else
                        @php($collection = $allReviews)
                    @endif

                    <div class="grid gap-5">
                        @foreach($collection as $review)
                            @include('reviews.partials.card', ['review' => $review, 'showActions' => false])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
