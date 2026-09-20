<aside class="w-72 bg-[--deep-green] text-white hidden lg:flex flex-col fixed h-full shadow-2xl">
    <div class="p-3 text-center">
        <a href="#" class="text-2xl flex items-center gap-2">

{{--            <span class="font-extrabold tracking-tight">Mudhyaf</span>--}}
{{--            <span class="text-[--primary-gold] arabic-brand font-bold">مضياف</span>--}}
            <img src="{{asset('admin.png')}}">
        </a>
        <div class="mt-2 inline-block bg-white/10 px-3 py-1 rounded-full border border-white/20">
                <span class="text-[10px] font-bold uppercase tracking-widest text-[--primary-gold]">
    {{ str_replace('_', ' ', strtoupper(auth()->user()->role)) }}
</span>
        </div>
    </div>
    <nav class="flex-1 px-4 space-y-4 mt-4">
        @if(auth()->user()->isAdmin())

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl font-semibold transition
       {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line w-5"></i>
                Dashboard Overview
            </a>

            {{-- Users --}}
            <a href="{{ route('admin.users.index') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl font-semibold transition  {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users w-5"></i>
                Manage Users
            </a>

            <a href="{{ route('admin.businesses.index') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl font-semibold transition
   {{ request()->routeIs('admin.businesses.*') ? 'active' : '' }}">
                <i class="fa-solid fa-store w-5"></i>
                Manage Businesses
            </a>

            {{-- Attractions --}}
            <a href="{{ route('admin.attractions.index') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl font-semibold transition
           {{ request()->routeIs('admin.attractions.*') ? 'active' : '' }}">
                <i class="fa-solid fa-map-location-dot w-5"></i>
                Manage Attractions
            </a>
            {{-- Events --}}
            {{-- Events --}}
            <a href="{{ route('admin.events.index') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl font-semibold transition
   {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar w-5"></i>
                Manage Events
            </a>

            <a href="{{ route('admin.reviews.index') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl font-semibold transition
   {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <i class="fa-solid fa-comments w-5"></i>
                Manage Reviews
            </a>

            <a href="{{ route('admin.recommendations.index') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl font-semibold transition
   {{ request()->routeIs('admin.recommendations.*') ? 'active' : '' }}">
                <i class="fa-solid fa-wand-magic-sparkles w-5"></i>
                Manage Recommendations
            </a>

            <a href="{{ route('admin.profile.edit') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl font-semibold transition
   {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-pen w-5"></i>
                Update Profile
            </a>

        @endif

            {{-- ================= BUSINESS OWNER ================= --}}
            @if(auth()->user()->isBusinessOwner())

                <a href="{{ route('owner.dashboard') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl font-semibold transition
               {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line w-5"></i>
                    My Dashboard
                </a>



                <a href="{{ route('owner.business.show') }}"
                   class="sidebar-link sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl font-semibold transition
   {{ request()->routeIs('owner.business.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-store w-5"></i>
                    My Business
                </a>

                <a href="{{ route('owner.events.index') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl font-semibold transition
   {{ request()->routeIs('owner.events.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-days w-5"></i>
                    My Events
                </a>

                <a href="{{ route('owner.reviews.index') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl font-semibold transition
   {{ request()->routeIs('owner.reviews.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-star-half-stroke w-5"></i>
                   My Reviews
                </a>
                <a href="{{ route('owner.profile.edit') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl font-semibold transition
               {{ request()->routeIs('owner.profile.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-pen w-5"></i>
                    Update Profile
                </a>
            @endif
    </nav>


    <div class="p-6 mt-auto">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                class="w-full flex items-center justify-center gap-2 bg-white/5 border border-white/10 py-3 rounded-2xl text-sm font-bold hover:bg-white/10 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>
