@extends('admin.layouts.app')

@section('role', 'Admin')

@section('content')

    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-extrabold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-users text-indigo-600"></i>
            Manage Users
        </h2>
    </div>

    <div class="bg-white rounded-xl p-6 shadow border border-gray-200 overflow-x-auto">

        <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden text-center">
            <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-4 font-bold text-gray-600 border-r">Name</th>
                <th class="px-6 py-4 font-bold text-gray-600 border-r">Email</th>
                <th class="px-6 py-4 font-bold text-gray-600 border-r">Role</th>
                <th class="px-6 py-4 font-bold text-gray-600 border-r">Created</th>
                <th class="px-6 py-4 font-bold text-gray-600">Actions</th>
            </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

            @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition border-b">

                    {{-- Name --}}
                    <td class="px-6 py-4 font-semibold text-gray-800 border-r">
                        <i class="fa-solid fa-user text-gray-400 mr-1"></i>
                        {{ $user->full_name }}
                    </td>

                    {{-- Email --}}
                    <td class="px-6 py-4 text-gray-600 border-r">
                        <i class="fa-solid fa-envelope text-gray-400 mr-1"></i>
                        {{ $user->email }}
                    </td>

                    {{-- Role --}}
                    <td class="px-6 py-4 border-r">
                        @php
                            $roleColors = [
                                'admin' => 'bg-red-100 text-red-700',
                                'business_owner' => 'bg-blue-100 text-blue-700',
                                'user' => 'bg-gray-100 text-gray-700',
                            ];
                        @endphp

                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $roleColors[$user->role] ?? '' }}">
                        {{ strtoupper(str_replace('_', ' ', $user->role)) }}
                    </span>
                    </td>

                    {{-- Created --}}
                    <td class="px-6 py-4 text-gray-500 border-r">
                        <i class="fa-regular fa-calendar text-gray-400 mr-1"></i>
                        {{ $user->created_at?->format('Y-m-d') }}
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-4">
                        @if(!$user->isAdmin())
                        <form action="{{ route('admin.users.destroy', $user) }}"
                              method="POST"
                              class="inline delete-form">
                            @csrf
                            @method('DELETE')

                            <button type="button"
                                    class="delete-btn px-3 py-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                        <i class="fa-regular fa-folder-open mr-2"></i>
                        No users found
                    </td>
                </tr>
            @endforelse

            </tbody>
        </table>

    </div>
@endsection
