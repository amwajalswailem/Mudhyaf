@extends('admin.layouts.app')

@section('role', 'Business Owner')

@section('content')
    @php
        $statusClasses = [
            'approved' => 'bg-green-100 text-green-700 ring-green-200',
            'pending' => 'bg-amber-100 text-amber-700 ring-amber-200',
            'rejected' => 'bg-rose-100 text-rose-700 ring-rose-200',
        ];
    @endphp

    <div class="space-y-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[--primary-gold]">Business Owner</p>
                <h2 class="text-3xl font-extrabold text-gray-800 mt-2">My Events</h2>
                <p class="text-gray-500 mt-2">Create, update, and track events pending admin approval.</p>
            </div>

            <a href="{{ route('owner.events.create') }}"
               class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-[--deep-green] text-white font-bold shadow-lg hover:shadow-xl transition">
                <i class="fa-solid fa-plus"></i>
                Create Event
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-green-800 font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white cardd rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-center">
                    <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 font-bold text-gray-600">Image</th>
                        <th class="px-6 py-4 font-bold text-gray-600">Event</th>
                        <th class="px-6 py-4 font-bold text-gray-600">Category</th>
                        <th class="px-6 py-4 font-bold text-gray-600">Dates</th>
                        <th class="px-6 py-4 font-bold text-gray-600">Status</th>
                        <th class="px-6 py-4 font-bold text-gray-600">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @forelse($events as $event)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="w-16 h-16 mx-auto overflow-hidden rounded-xl border border-gray-200 shadow-sm">
                                    <img src="{{ $event->image_url }}" alt="{{ $event->name }}" class="w-full h-full object-cover">
                                </div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-800">
                                {{ $event->name }}
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fa-solid fa-clock mr-1"></i>
                                    {{ $event->created_at?->format('M d, Y') }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ optional($event->category)->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-xs text-gray-600">
                                <div>{{ $event->start_time?->format('Y-m-d H:i') }}</div>
                                <div class="text-gray-400">to</div>
                                <div>{{ $event->end_time?->format('Y-m-d H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold ring-1 {{ $statusClasses[$event->status] ?? 'bg-gray-100 text-gray-700 ring-gray-200' }}">
                                    {{ strtoupper($event->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('events.show', $event) }}"
                                       target="_blank"
                                       class="px-3 py-2 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 transition">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('owner.events.edit', $event) }}"
                                       class="px-3 py-2 rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-200 transition">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="{{ route('owner.events.destroy', $event) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="delete-btn px-3 py-2 rounded-lg bg-rose-100 text-rose-700 hover:bg-rose-200 transition">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                No events created yet.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
