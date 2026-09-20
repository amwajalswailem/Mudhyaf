@extends('admin.layouts.app')

@section('role', 'Admin')

@section('content')
    @php
        $initial = account_initial($user->full_name);
    @endphp

    <div class="space-y-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[--primary-gold]">Administrator</p>
                <h2 class="text-3xl font-extrabold text-gray-800 mt-2">Update Profile</h2>
                <p class="text-gray-500 mt-2">Manage your account information from the admin dashboard.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-white border border-gray-200 text-gray-700 font-bold shadow-sm hover:shadow-md transition">
                    <i class="fa-solid fa-chart-line"></i>
                    Dashboard
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-green-800 font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-4">
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 h-full">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-20 h-20 rounded-3xl bg-[--deep-green] text-white flex items-center justify-center text-3xl shadow-lg">
                            {{ $initial }}
                        </div>
                        <h3 class="text-2xl font-extrabold text-gray-800 mt-5">{{ $user->full_name }}</h3>
                        <p class="text-gray-500 mt-2">{{ $user->email }}</p>
                        <span class="mt-4 inline-flex px-4 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold uppercase tracking-widest">
                            {{ strtoupper($user->role) }}
                        </span>
                    </div>

                    <div class="mt-8 space-y-4">
                        <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Member Since</p>
                            <p class="text-sm font-bold text-gray-800 mt-2">{{ $user->created_at?->format('M d, Y') ?? '-' }}</p>
                        </div>
                        <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Account Type</p>
                            <p class="text-sm font-bold text-gray-800 mt-2">Administrator</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8">
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
                    <h3 class="text-2xl font-extrabold text-gray-800 mb-2">Profile Details</h3>
                    <p class="text-gray-500 mb-8">Keep your admin contact details current.</p>

                    <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                                <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}"
                                       class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-[--primary-gold] focus:ring-4 focus:ring-[--primary-gold]/10 outline-none transition">
                                @error('full_name') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                       class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-[--primary-gold] focus:ring-4 focus:ring-[--primary-gold]/10 outline-none transition">
                                @error('email') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                                <input type="password" name="password"
                                       placeholder="Leave blank to keep current password"
                                       class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-[--primary-gold] focus:ring-4 focus:ring-[--primary-gold]/10 outline-none transition">
                                @error('password') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                                <input type="password" name="password_confirmation"
                                       class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-[--primary-gold] focus:ring-4 focus:ring-[--primary-gold]/10 outline-none transition">
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 justify-end">
                            <a href="{{ route('admin.dashboard') }}"
                               class="inline-flex items-center justify-center gap-2 rounded-2xl border border-gray-200 bg-white px-5 py-3 font-bold text-gray-700 shadow-sm hover:shadow-md transition">
                                <i class="fa-solid fa-arrow-left"></i>
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[--deep-green] px-5 py-3 font-bold text-white shadow-lg hover:shadow-xl transition">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
