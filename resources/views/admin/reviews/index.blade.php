@extends('admin.layouts.app')

@section('role', 'Admin')

@section('content')
    @php
        $type = request('type', 'all');
        $typeClasses = fn ($key) => $type === $key
            ? 'bg-[--deep-green] text-white shadow-lg shadow-[--deep-green]/20'
            : 'bg-white text-gray-600 border border-gray-200 hover:border-[--deep-green]/30 hover:text-[--deep-green]';
    @endphp

    <div class="space-y-8">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-comments text-indigo-600"></i>
                    Manage Reviews
                </h2>
                <p class="text-gray-500 mt-2">Review feedback across attractions, businesses, and events.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6">
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Total Reviews</p>
                <p class="text-3xl font-extrabold text-[--deep-green] mt-3">{{ $summary['total'] }}</p>
            </div>
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6">
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Attractions</p>
                <p class="text-3xl font-extrabold text-blue-600 mt-3">{{ $summary['attractions'] }}</p>
            </div>
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6">
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Businesses</p>
                <p class="text-3xl font-extrabold text-amber-600 mt-3">{{ $summary['businesses'] }}</p>
            </div>
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6">
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Events</p>
                <p class="text-3xl font-extrabold text-emerald-600 mt-3">{{ $summary['events'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-5 lg:p-6">
            <div class="flex flex-wrap gap-3 mb-6">
                <a href="{{ route('admin.reviews.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl font-bold transition {{ $typeClasses('all') }}">
                    <i class="fa-solid fa-layer-group"></i>
                    All
                </a>
                <a href="{{ route('admin.reviews.index', ['type' => 'attraction']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl font-bold transition {{ $typeClasses('attraction') }}">
                    <i class="fa-solid fa-location-dot"></i>
                    Attractions
                </a>
                <a href="{{ route('admin.reviews.index', ['type' => 'business']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl font-bold transition {{ $typeClasses('business') }}">
                    <i class="fa-solid fa-store"></i>
                    Businesses
                </a>
                <a href="{{ route('admin.reviews.index', ['type' => 'event']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl font-bold transition {{ $typeClasses('event') }}">
                    <i class="fa-solid fa-calendar-days"></i>
                    Events
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left font-bold text-gray-600 border-r">User</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-600 border-r">Type</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-600 border-r">Place</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-600 border-r">Rating</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-600 border-r">Comment</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-600 border-r">Date</th>
                        <th class="px-6 py-4 text-center font-bold text-gray-600">Action</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @forelse($reviews as $review)
                        @php
                            $entity = $review->entity;
                            $entityType = class_basename($review->entity_type);
                        @endphp
                        <tr class="hover:bg-gray-50 transition border-b">
                            <td class="px-6 py-4 border-r font-semibold text-gray-800">
                                {{ $review->user->full_name ?? 'User' }}
                            </td>
                            <td class="px-6 py-4 border-r text-gray-600">
                                {{ $entityType }}
                            </td>
                            <td class="px-6 py-4 border-r text-gray-600">
                                {{ $entity->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 border-r">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-100 text-amber-700 font-bold">
                                    <i class="fa-solid fa-star"></i>
                                    {{ $review->rating }}/5
                                </span>
                            </td>
                            <td class="px-6 py-4 border-r text-gray-600 max-w-md">
                                <div class="line-clamp-2">
                                    {{ $review->comment ?: 'No comment' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 border-r text-gray-500">
                                {{ $review->created_at?->format('M d, Y') ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="delete-btn px-3 py-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                                <i class="fa-regular fa-message mr-2"></i>
                                No reviews found
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
