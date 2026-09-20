@extends('layouts.app')

@section('content')

    <section class="pt-32 pb-24 bg-gray-50 min-h-screen">

        <div class="max-w-7xl mx-auto px-6">

            {{-- Title --}}
            <div class="text-center mb-14">
                <h2 class="text-4xl font-bold text-[--deep-green]">
                    Explore Events
                </h2>
                <p class="text-gray-500 mt-3">
                    Discover upcoming activities and experiences in Hail
                </p>
            </div>

            {{-- FILTER SECTION --}}
            <form method="GET"
                  class="bg-white p-6 rounded-3xl shadow-md mb-14 grid grid-cols-1 md:grid-cols-4 gap-6">

                {{-- Search --}}
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search events..."
                       class="border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[--primary-gold]">

                {{-- Category --}}
                <select name="category"
                        class="border border-gray-200 rounded-xl px-4 py-3">
                    <option value="">All Categories</option>

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach

                </select>

                <button type="submit"
                        class="bg-[--primary-gold] text-white rounded-xl font-bold hover:opacity-90 transition">
                    Filter
                </button>

            </form>

            {{-- EVENTS GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">

                @forelse($events as $event)

                    <div class="bg-white rounded-[2rem] overflow-hidden shadow-lg hover:shadow-2xl transition border border-gray-100">

                        {{-- Image --}}
                        <div class="h-64 overflow-hidden relative">
                            <img src="{{ $event->image_url ?? 'https://via.placeholder.com/600x400' }}"
                                 class="w-full h-full object-cover hover:scale-110 transition duration-700">
                            @auth
                                <button
                                    onclick="toggleFavorite(this)"
                                    data-id="{{ $event->id }}"
                                    data-type="App\Models\Event"
                                    class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white shadow-md flex items-center justify-center transition hover:scale-110">

                                    <svg class="w-6 h-6 {{ $event->isFavoritedBy(auth()->user()) ? 'text-red-500' : 'text-gray-400' }}"
                                         fill="currentColor"
                                         viewBox="0 0 24 24">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5
                2 5.42 4.42 3 7.5 3
                c1.74 0 3.41.81 4.5 2.09
                C13.09 3.81 14.76 3 16.5 3
                19.58 3 22 5.42 22 8.5
                c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>

                                </button>
                            @endauth
                        </div>

                        <div class="p-8">

                            {{-- Title --}}
                            <h4 class="text-2xl font-bold text-gray-800 mb-3">
                                {{ $event->name }}
                            </h4>

                            {{-- Description --}}
                            <p class="text-gray-500 mb-5 line-clamp-2">
                                {{ $event->description }}
                            </p>

                            {{-- Meta Info --}}
                            <div class="space-y-2 text-sm text-gray-500 mb-6">

                                <div>
                                    📍 {{ $event->address }}
                                </div>

                                <div>
                                    🕒 {{ optional($event->start_time)->format('M d, Y H:i') }}
                                </div>
                            </div>

                            <div class="flex justify-between items-center">

                            <span class="text-[--deep-green] font-semibold text-sm">
                                {{ optional($event->category)->name }}
                            </span>

                                <a href="{{ route('events.show', $event) }}"
                                   class="text-[--primary-gold] font-bold flex items-center gap-2">
                                    View
                                </a>

                            </div>

                        </div>

                    </div>

                @empty
                    <p class="col-span-3 text-center text-gray-400">
                        No events found.
                    </p>
                @endforelse

            </div>

            {{-- Pagination --}}
            <div class="mt-14">
                {{ $events->withQueryString()->links() }}
            </div>

        </div>

    </section>

@endsection
