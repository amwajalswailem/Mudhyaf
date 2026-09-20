<header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-8 sticky top-0 z-10 shadow-sm">

    {{-- LEFT SIDE --}}
    <div class="flex flex-col">
        <h1 class="text-xl font-bold text-gray-800">
            Dashboard
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Welcome back,
            <span class="font-semibold text-[--deep-green]">
                {{ auth()->user()->full_name }}
            </span>
        </p>
    </div>

    {{-- RIGHT SIDE --}}
    <div class="flex items-center gap-6">

        {{-- Role Badge --}}
        <span class="px-3 py-1 rounded-full text-xs font-bold
            {{ auth()->user()->isAdmin() ? 'bg-red-100 text-red-600' : '' }}
            {{ auth()->user()->isBusinessOwner() ? 'bg-blue-100 text-blue-600' : '' }}
            {{ auth()->user()->isUser() ? 'bg-gray-100 text-gray-600' : '' }}">
            {{ strtoupper(auth()->user()->role) }}
        </span>

        {{-- Profile Dropdown --}}
        <div class="relative group">

            <div class="flex items-center gap-3 cursor-pointer">

                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-gray-800">
                        {{ auth()->user()->full_name }}
                    </p>
                    <p class="text-xs text-gray-400 uppercase tracking-wider">
                        {{ auth()->user()->role }}
                    </p>
                </div>

                {{-- Avatar --}}
                <div class="w-10 h-10 bg-[--primary-gold] rounded-2xl flex items-center justify-center text-white font-bold text-lg">
                    {{ account_initial(auth()->user()->full_name) }}
                </div>
            </div>

            {{-- Dropdown Menu --}}
            <div class="absolute right-0 mt-3 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">

                <a href="{{ auth()->user()->isAdmin() ? route('admin.profile.edit') : (auth()->user()->isBusinessOwner() ? route('owner.profile.edit') : route('profile.edit')) }}"
                   class="block px-5 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-t-2xl">
                    <i class="fa-solid fa-user mr-2"></i>
                    Update Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-5 py-3 text-sm text-red-600 hover:bg-gray-50 rounded-b-2xl">
                        <i class="fa-solid fa-right-from-bracket mr-2"></i>
                        Logout
                    </button>
                </form>

            </div>
        </div>

    </div>
</header>
