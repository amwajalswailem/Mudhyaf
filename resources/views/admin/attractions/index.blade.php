@extends('admin.layouts.app')

@section('role', 'Admin')

@section('content')

    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-extrabold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-map-location-dot text-indigo-600"></i>
            Manage Attractions
        </h2>

        <a href="{{ route('admin.attractions.create') }}"
           class="flex items-center gap-2 px-6 py-3 rounded-2xl bg-[--deep-green] text-white font-bold shadow hover:opacity-90 transition">
            <i class="fa-solid fa-plus"></i>
            Add Attraction
        </a>
    </div>

    <div class="bg-white rounded-xl p-6 shadow border border-gray-200 overflow-x-auto">

        <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden text-center">
            <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-4 font-bold text-gray-600 border-r">Image</th>
                <th class="px-6 py-4 font-bold text-gray-600 border-r">Name</th>
                <th class="px-6 py-4 font-bold text-gray-600 border-r">Category</th>
                <th class="px-6 py-4 font-bold text-gray-600 border-r">Created By</th>
                <th class="px-6 py-4 font-bold text-gray-600">Actions</th>
            </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

            @forelse($attractions as $attraction)
                <tr class="hover:bg-gray-50 transition border-b">

                    {{-- Image --}}
                    <td class="px-6 py-4 border-r">
                        <div class="w-16 h-16 mx-auto overflow-hidden rounded-xl border border-gray-200 shadow-sm">
                            <img src="{{ $attraction->image_url }}"
                                 class="w-full h-full object-cover transition-transform duration-300 hover:scale-125">
                        </div>
                    </td>

                    {{-- Name --}}
                    <td class="px-6 py-4 font-semibold text-gray-800 border-r">
                        {{ $attraction->name }}
                    </td>

                    {{-- Category --}}
                    <td class="px-6 py-4 text-gray-600 border-r">
                        {{ optional($attraction->category)->name ?? '-' }}
                    </td>

                    {{-- Creator --}}
                    <td class="px-6 py-4 text-gray-600 border-r">
                        {{ optional($attraction->creator)->full_name ?? '-' }}
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-4 space-x-2">

                        {{-- Preview --}}
                        <button onclick="openModal({{ $attraction->id }})"
                                class="px-3 py-2 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 transition">
                            <i class="fa-solid fa-eye"></i>
                        </button>

                        <a href="{{ route('admin.attractions.show', $attraction) }}"
                           class="px-3 py-2 rounded-lg bg-indigo-100 text-indigo-700 hover:bg-indigo-200 transition">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>

                        {{-- Edit --}}
                        <a href="{{ route('admin.attractions.edit', $attraction) }}"
                           class="px-3 py-2 rounded-lg bg-yellow-100 text-yellow-700 hover:bg-yellow-200 transition">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        {{-- Delete --}}
                        <form action="{{ route('admin.attractions.destroy', $attraction) }}"
                              method="POST"
                              class="inline delete-form">
                            @csrf
                            @method('DELETE')

                            <button type="button"
                                    class="delete-btn px-3 py-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>

                    </td>
                </tr>

                <div id="modal-{{ $attraction->id }}"
                     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

                    <div class="bg-white rounded-2xl w-full max-w-2xl p-8 relative shadow-xl">

                        <button onclick="closeModal({{ $attraction->id }})"
                                class="absolute top-4 right-4 text-danger-500 hover:text-red-500">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>

                        <div class="text-center mb-4">
                            <img src="{{ $attraction->image_url }}"
                                 class="w-full h-64 object-cover rounded-xl mb-4 shadow">
                            <h3 class="text-2xl font-bold text-gray-800">
                                {{ $attraction->name }}
                            </h3>
                        </div>

                        <div class="space-y-3 text-gray-600">

                            <p>
                                <strong>Category:</strong>
                                {{ optional($attraction->category)->name ?? '-' }}
                            </p>

                            <p>
                                <strong>Created By:</strong>
                                {{ optional($attraction->creator)->full_name ?? '-' }}
                            </p>

                            <p>
                                <strong>Description:</strong><br>
                                {{ $attraction->description ?? 'No description available.' }}
                            </p>

                            @if($attraction->latitude && $attraction->longitude)
                                <p>
                                    <strong>Coordinates:</strong>
                                    {{ $attraction->latitude }},
                                    {{ $attraction->longitude }}
                                </p>
                            @endif

                        </div>

                    </div>
                </div>

            @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                        No attractions found
                    </td>
                </tr>
            @endforelse

            </tbody>
        </table>

    </div>


    {{-- Modal Script --}}
    <script>
        function openModal(id) {
            document.getElementById('modal-' + id).classList.remove('hidden');
            document.getElementById('modal-' + id).classList.add('flex');
        }

        function closeModal(id) {
            document.getElementById('modal-' + id).classList.add('hidden');
            document.getElementById('modal-' + id).classList.remove('flex');
        }
    </script>

@endsection
