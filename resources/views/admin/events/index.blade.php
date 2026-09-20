@extends('admin.layouts.app')

@section('role', 'Admin')

@section('content')

    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-extrabold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-calendar-days text-indigo-600"></i>
            Manage Events
        </h2>

        <a href="{{ route('admin.events.create') }}"
           class="flex items-center gap-2 px-6 py-3 rounded-2xl bg-[--deep-green] text-white font-bold shadow hover:opacity-90 transition">
            <i class="fa-solid fa-plus"></i>
            Add Event
        </a>
    </div>

    <div class="bg-white rounded-xl p-6 shadow border border-gray-200 overflow-x-auto">

        <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden text-center">
            <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-4 border-r font-bold text-gray-600">Image</th>
                <th class="px-6 py-4 border-r font-bold text-gray-600">Event</th>
                <th class="px-6 py-4 border-r font-bold text-gray-600">Category</th>
                <th class="px-6 py-4 border-r font-bold text-gray-600">Dates</th>
                <th class="px-6 py-4 border-r font-bold text-gray-600">Status</th>
                <th class="px-6 py-4 font-bold text-gray-600">Actions</th>
            </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

            @forelse($events as $event)
                <tr class="hover:bg-gray-50 transition border-b">

                    {{-- Image --}}
                    <td class="px-6 py-4 border-r">
                        <div class="w-16 h-16 mx-auto overflow-hidden rounded-xl border border-gray-200 shadow-sm">
                            <img src="{{ $event->image_url }}"
                                 class="w-full h-full object-cover transition-transform duration-300 hover:scale-125">
                        </div>
                    </td>

                    {{-- Event --}}
                    <td class="px-6 py-4 border-r font-semibold text-gray-800">
                        {{ $event->name }}
                        <p class="text-xs text-gray-400">
                            <i class="fa-solid fa-user mr-1"></i>
                            {{ optional($event->creator)->full_name }}
                        </p>
                    </td>

                    {{-- Category --}}
                    <td class="px-6 py-4 border-r text-gray-600">
                        <i class="fa-solid fa-tag text-gray-400 mr-1"></i>
                        {{ optional($event->category)->name ?? '-' }}
                    </td>

                    {{-- Dates --}}
                    <td class="px-6 py-4 border-r text-xs text-gray-600">
                        <i class="fa-regular fa-clock text-gray-400"></i>
                        <div>
                            {{ $event->start_time?->format('Y-m-d H:i') }}
                        </div>
                        <div class="text-gray-400">to</div>
                        <div>
                            {{ $event->end_time?->format('Y-m-d H:i') }}
                        </div>
                    </td>

                    {{-- Status --}}
                    <td class="px-6 py-4 border-r">
                        @php
                            $colors = [
                                'approved' => 'bg-green-100 text-green-700',
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'rejected' => 'bg-red-100 text-red-700',
                            ];
                        @endphp

                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $colors[$event->status] ?? '' }}">
                        {{ strtoupper($event->status) }}
                    </span>
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-4 space-x-2">

                        {{-- Preview --}}
                        <button onclick="openModal({{ $event->id }})"
                                class="px-3 py-2 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 transition">
                            <i class="fa-solid fa-eye"></i>
                        </button>

                        <a href="{{ route('admin.events.show', $event) }}"
                           class="px-3 py-2 rounded-lg bg-indigo-100 text-indigo-700 hover:bg-indigo-200 transition">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>

                        {{-- Edit --}}
                        <a href="{{ route('admin.events.edit', $event) }}"
                           class="px-3 py-2 rounded-lg bg-yellow-100 text-yellow-700 hover:bg-yellow-200 transition">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        @if($event->status === 'pending')

                            {{-- Approve --}}
                            <form action="{{ route('admin.events.approve', $event) }}"
                                  method="POST"
                                  class="inline approve-form">
                                @csrf
                                <button type="button"
                                        class="approve-btn px-3 py-2 rounded-lg bg-green-100 text-green-700 hover:bg-green-200 transition">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>

                            {{-- Reject --}}
                              <form action="{{ route('admin.events.reject', $event) }}"
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
                        <form action="{{ route('admin.events.destroy', $event) }}"
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

                {{-- 🔥 Preview Modal --}}
                <div id="modal-{{ $event->id }}"
                     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

                    <div class="bg-white rounded-2xl w-full max-w-2xl p-6 relative shadow-xl">

                        <button onclick="closeModal({{ $event->id }})"
                                class="absolute top-4 right-4 text-gray-500 hover:text-red-500">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>

                        <img src="{{ $event->image_url }}"
                             class="w-full h-64 object-cover rounded-xl mb-4 shadow">

                        <h3 class="text-2xl font-bold text-gray-800 mb-3">
                            {{ $event->name }}
                        </h3>

                        <p class="text-gray-600 mb-3">
                            {{ $event->description ?? 'No description available.' }}
                        </p>

                        <div class="text-sm text-gray-500 space-y-1">
                            <p><strong>Start:</strong> {{ $event->start_time?->format('Y-m-d H:i') }}</p>
                            <p><strong>End:</strong> {{ $event->end_time?->format('Y-m-d H:i') }}</p>
                            <p><strong>Status:</strong> {{ strtoupper($event->status) }}</p>
                        </div>

                    </div>
                </div>

            @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                        No events found
                    </td>
                </tr>
            @endforelse

            </tbody>
        </table>

    </div>


    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function openModal(id){
            document.getElementById('modal-'+id).classList.remove('hidden');
            document.getElementById('modal-'+id).classList.add('flex');
        }

        function closeModal(id){
            document.getElementById('modal-'+id).classList.add('hidden');
            document.getElementById('modal-'+id).classList.remove('flex');
        }

        function confirmAction(selector, title, text, color){
            document.querySelectorAll(selector).forEach(btn=>{
                btn.addEventListener('click', function(){
                    const form = this.closest('form');
                    Swal.fire({
                        title: title,
                        text: text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: color,
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Confirm'
                    }).then((result)=>{
                        if(result.isConfirmed){
                            form.submit();
                        }
                    });
                });
            });
        }

        function promptReject(selector, title, text, color){
            document.querySelectorAll(selector).forEach(btn=>{
                btn.addEventListener('click', function(){
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
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Confirm Reject'
                    }).then((result)=>{
                        if(result.isConfirmed){
                            reasonInput.value = result.value || '';
                            form.submit();
                        }
                    });
                });
            });
        }

        confirmAction('.approve-btn','Approve Event?','This event will be visible.','#166534');
        promptReject('.reject-btn','Reject Event?','Add a reason if needed.','#b45309');
        confirmAction('.delete-btn','Delete Event?','This action cannot be undone.','#b91c1c');
    </script>

@endsection
