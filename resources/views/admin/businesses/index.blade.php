@extends('admin.layouts.app')

@section('role', 'Admin')

@section('content')

    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-extrabold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-store text-indigo-600"></i>
            Manage Businesses
        </h2>
    </div>

    <div class="bg-white rounded-xl p-6 shadow border border-gray-200 overflow-x-auto">

        <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-4 text-left font-bold text-gray-600 border-r">Image</th>
                <th class="px-6 py-4 text-left font-bold text-gray-600 border-r">Business</th>
                <th class="px-6 py-4 text-left font-bold text-gray-600 border-r">Owner</th>
                <th class="px-6 py-4 text-left font-bold text-gray-600 border-r">Category</th>
                <th class="px-6 py-4 text-left font-bold text-gray-600 border-r">Status</th>
                <th class="px-6 py-4 text-center font-bold text-gray-600">Actions</th>
            </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

            @forelse($businesses as $business)
                <tr class="hover:bg-gray-50 transition border-b">

                    {{-- Image --}}
                    <td class="px-6 py-4 border-r">
                        <div class="w-16 h-16 rounded-xl overflow-hidden border border-gray-200 bg-gray-100 shadow-sm">
                            <img src="{{ $business->image_url }}"
                                 alt="{{ $business->name }}"
                                 class="w-full h-full object-cover">
                        </div>
                    </td>

                    {{-- Business --}}
                    <td class="px-6 py-4 font-semibold text-gray-800 border-r">
                        {{ $business->name }}
                    </td>

                    {{-- Owner --}}
                    <td class="px-6 py-4 text-gray-600 border-r">
                        <i class="fa-solid fa-user text-gray-400 mr-1"></i>
                        {{ optional($business->owner)->full_name }}
                    </td>

                    {{-- Category --}}
                    <td class="px-6 py-4 text-gray-600 border-r">
                        <i class="fa-solid fa-tag text-gray-400 mr-1"></i>
                        {{ optional($business->category)->name }}
                    </td>

                    {{-- Status --}}
                    <td class="px-6 py-4 border-r">
                        @php
                            $statusColors = [
                                'approved' => 'bg-green-100 text-green-700',
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'rejected' => 'bg-red-100 text-red-700',
                            ];
                        @endphp

                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusColors[$business->status] ?? '' }}">
                        {{ strtoupper($business->status) }}
                    </span>
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-4 text-center space-x-2">

                        <a href="{{ route('admin.businesses.show', $business) }}"
                           class="inline-flex px-3 py-2 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 transition">
                            <i class="fa-solid fa-eye"></i>
                        </a>

                        @if($business->status === 'pending')

                            {{-- Approve --}}
                            <form action="{{ route('admin.businesses.approve', $business) }}"
                                  method="POST"
                                  class="inline approve-form">
                                @csrf
                                <button type="button"
                                        class="approve-btn px-3 py-2 rounded-lg bg-green-100 text-green-700 hover:bg-green-200 transition">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>

                            {{-- Reject --}}
                            <form action="{{ route('admin.businesses.reject', $business) }}"
                                  method="POST"
                                  class="inline reject-form">
                                @csrf
                                <input type="hidden" name="rejection_reason" value="">
                                <button type="button"
                                        class="reject-btn px-3 py-2 rounded-lg bg-yellow-100 text-yellow-700 hover:bg-yellow-200 transition">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </form>

                        @endif

                        {{-- Delete --}}
                        <form action="{{ route('admin.businesses.destroy', $business) }}"
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
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                        <i class="fa-regular fa-folder-open mr-2"></i>
                        No businesses found
                    </td>
                </tr>
            @endforelse

            </tbody>
        </table>

    </div>


    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            function confirmAction(buttonClass, title, text, color) {
                document.querySelectorAll(buttonClass).forEach(btn => {
                    btn.addEventListener('click', function () {
                        const form = this.closest('form');

                        Swal.fire({
                            title: title,
                            text: text,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: color,
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Confirm'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            }

            function promptReject(buttonClass, title, text, color) {
                document.querySelectorAll(buttonClass).forEach(btn => {
                    btn.addEventListener('click', function () {
                        const form = this.closest('form');
                        const reasonInput = form.querySelector('input[name="rejection_reason"]');

                        Swal.fire({
                            title: title,
                            text: text,
                            input: 'textarea',
                            inputPlaceholder: 'Optional rejection reason',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: color,
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Confirm Reject'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                reasonInput.value = result.value || '';
                                form.submit();
                            }
                        });
                    });
                });
            }

            confirmAction('.approve-btn', 'Approve Business?', 'This business will become visible.', '#166534');
            promptReject('.reject-btn', 'Reject Business?', 'Add a reason if needed.', '#b45309');
            confirmAction('.delete-btn', 'Delete Business?', 'This action cannot be undone.', '#b91c1c');

        });
    </script>

@endsection
