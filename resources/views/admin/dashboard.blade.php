@extends('admin.layouts.app')

@section('role', 'Admin')

@section('content')

    {{-- HERO --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
        <div class="lg:col-span-8 bg-gradient-to-br from-[--deep-green] to-[#2d5a44] p-10 rounded-[2.5rem] text-white shadow-lg relative overflow-hidden">
            <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-white/10"></div>
            <div class="absolute -left-20 -bottom-20 w-72 h-72 rounded-full bg-[--primary-gold]/10"></div>

            <div class="relative">
                <div class="flex items-start justify-between gap-6">
                    <div>
                        <h2 class="text-3xl font-extrabold tracking-tight">Welcome back, Admin 👋</h2>
                        <p class="text-white/70 mt-2">Here’s what’s happening today in Mudhyaf.</p>
                    </div>

                    <div class="text-right">
                        <p class="text-white/70 text-sm">{{ now()->format('D, M d Y') }}</p>
                        <p class="text-[--primary-gold] font-extrabold text-lg">Hail, KSA</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                    <div class="bg-white/10 border border-white/10 rounded-2xl p-4">
                        <p class="text-white/70 text-xs font-bold uppercase tracking-widest">Users (7 days)</p>
                        <p class="text-2xl font-extrabold mt-2">{{ $usersLast7Days }}</p>
                    </div>
                    <div class="bg-white/10 border border-white/10 rounded-2xl p-4">
                        <p class="text-white/70 text-xs font-bold uppercase tracking-widest">Businesses (7 days)</p>
                        <p class="text-2xl font-extrabold mt-2">{{ $businessesLast7Days }}</p>
                    </div>
                    <div class="bg-white/10 border border-white/10 rounded-2xl p-4">
                        <p class="text-white/70 text-xs font-bold uppercase tracking-widest">Events (7 days)</p>
                        <p class="text-2xl font-extrabold mt-2">{{ $eventsLast7Days }}</p>
                    </div>
                    <div class="bg-white/10 border border-white/10 rounded-2xl p-4">
                        <p class="text-white/70 text-xs font-bold uppercase tracking-widest">Reviews (7 days)</p>
                        <p class="text-2xl font-extrabold mt-2">{{ $reviewsLast7Days }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- PENDING APPROVALS --}}
        <div class="lg:col-span-4 bg-white p-8 rounded-[2.5rem] shadow border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-extrabold text-gray-800">Pending Approvals</h3>
                <span class="text-xs bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full font-bold">
                    Action Needed
                </span>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between bg-gray-50 border border-gray-100 rounded-2xl p-4">
                    <div>
                        <p class="text-sm font-bold text-gray-800">Businesses</p>
                        <p class="text-xs text-gray-500">Waiting for approval</p>
                    </div>
                    <p class="text-2xl font-extrabold text-yellow-600">{{ $pendingBusinesses }}</p>
                </div>

                <div class="flex items-center justify-between bg-gray-50 border border-gray-100 rounded-2xl p-4">
                    <div>
                        <p class="text-sm font-bold text-gray-800">Events</p>
                        <p class="text-xs text-gray-500">Waiting for approval</p>
                    </div>
                    <p class="text-2xl font-extrabold text-yellow-600">{{ $pendingEvents }}</p>
                </div>

                <div class="mt-4 bg-[--deep-green]/5 border border-[--deep-green]/10 rounded-2xl p-4">
                    <p class="text-sm font-bold text-gray-800">Average Rating</p>
                    <p class="text-xs text-gray-500">Across all reviews</p>
                    <p class="text-3xl font-extrabold text-[--deep-green] mt-2">{{ $avgRating }}/5</p>
                </div>
            </div>
        </div>
    </div>

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6 mb-10">
        <div class="bg-white p-6 rounded-3xl shadow border border-gray-100">
            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest">Total Users</p>
            <h2 class="text-3xl font-extrabold mt-2">{{ $totalUsers }}</h2>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow border border-gray-100">
            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest">Businesses</p>
            <h2 class="text-3xl font-extrabold mt-2">{{ $totalBusinesses }}</h2>
            <p class="text-xs text-gray-500 mt-2">
                Approved: <span class="font-bold text-green-600">{{ $approvedBusinesses }}</span>
            </p>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow border border-gray-100">
            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest">Events</p>
            <h2 class="text-3xl font-extrabold mt-2">{{ $totalEvents }}</h2>
            <p class="text-xs text-gray-500 mt-2">
                Approved: <span class="font-bold text-green-600">{{ $approvedEvents }}</span>
            </p>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow border border-gray-100">
            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest">Reviews</p>
            <h2 class="text-3xl font-extrabold mt-2">{{ $totalReviews }}</h2>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow border border-gray-100">
            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest">Favorites</p>
            <h2 class="text-3xl font-extrabold mt-2">{{ $totalFavorites }}</h2>
        </div>

        <div class="bg-gradient-to-br from-[--primary-gold] to-[#d4a23a] text-white p-6 rounded-3xl shadow">
            <p class="text-white/80 text-xs font-bold uppercase tracking-widest">Conversion Hint</p>
            <h2 class="text-2xl font-extrabold mt-2">Boost Events</h2>
            <p class="text-sm text-white/80 mt-2">More events = more engagement.</p>
        </div>
    </div>

    <div class="mb-10">
        <a href="{{ route('admin.profile.edit') }}"
           class="bg-white p-6 rounded-3xl shadow border border-gray-100 hover:shadow-2xl transition transform hover:-translate-y-1 block">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-gray-500 text-sm">Admin Profile</p>
                    <h2 class="text-2xl font-extrabold text-gray-800 mt-2">{{ auth()->user()->full_name }}</h2>
                    <p class="text-gray-500 text-sm mt-2">Update your profile details from the dashboard shell.</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-[--deep-green]/10 text-[--deep-green] flex items-center justify-center text-2xl">
                    <i class="fa-solid fa-user-pen"></i>
                </div>
            </div>
        </a>
    </div>

    {{-- ROLE BREAKDOWN + TOP CATEGORIES --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-10">

        <div class="lg:col-span-5 bg-white p-8 rounded-[2.5rem] shadow border border-gray-100">
            <h3 class="text-lg font-extrabold text-gray-800 mb-6">Users by Role</h3>

            @php
                $adminCount = $usersByRole['admin'] ?? 0;
                $ownerCount = $usersByRole['business_owner'] ?? 0;
                $userCount  = $usersByRole['user'] ?? 0;
            @endphp

            <div class="space-y-4">
                <div class="flex items-center justify-between bg-gray-50 border border-gray-100 rounded-2xl p-4">
                    <p class="font-bold text-gray-800">Admins</p>
                    <p class="font-extrabold text-gray-800">{{ $adminCount }}</p>
                </div>
                <div class="flex items-center justify-between bg-gray-50 border border-gray-100 rounded-2xl p-4">
                    <p class="font-bold text-gray-800">Business Owners</p>
                    <p class="font-extrabold text-gray-800">{{ $ownerCount }}</p>
                </div>
                <div class="flex items-center justify-between bg-gray-50 border border-gray-100 rounded-2xl p-4">
                    <p class="font-bold text-gray-800">Tourist</p>
                    <p class="font-extrabold text-gray-800">{{ $userCount }}</p>
                </div>
            </div>
        </div>

        <div class="lg:col-span-7 bg-white p-8 rounded-[2.5rem] shadow border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-extrabold text-gray-800">Top Categories</h3>
                <span class="text-xs text-gray-400 font-bold">by number of businesses</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($topCategories as $cat)
                    <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 flex items-center justify-between">
                        <div>
                            <p class="font-bold text-gray-800">{{ $cat->name }}</p>
                            <p class="text-xs text-gray-500">Businesses in this category</p>
                        </div>
                        <span class="text-2xl font-extrabold text-[--deep-green]">{{ $cat->businesses_count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
