<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mudhyaf | Discover the Heart of Hail</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@700&family=Noto+Sans+Arabic:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
</head>
<body class="antialiased">

<!-- Navigation -->
@php
        $favoriteCount = auth()->check() ? auth()->user()->favorites()->count() : 0;

    $accountInitial = auth()->check()
        ? account_initial(auth()->user()->full_name)
        : 'U';
    $dashboardRoute = auth()->check() && auth()->user()->isAdmin()
            ? route('admin.dashboard')
            : (auth()->check() && auth()->user()->isBusinessOwner() ? route('owner.dashboard') : route('my_profile.show'));
        $dashboardLabel = auth()->check() && auth()->user()->isAdmin()
            ? 'Dashboard'
            : (auth()->check() && auth()->user()->isBusinessOwner() ? 'My Business' : 'My Profile');
@endphp
<nav style="    padding: 10px;" class="fixed w-full z-50 glass-nav shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="nav-shell">
            <a href="{{ route('home') }}" class="nav-brand">
                <img src="{{ asset('logo.png') }}" alt="Mudhyaf Logo" class="nav-logo">
            </a>

            <div class="nav-links hidden lg:flex">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active text-[--primary-gold]' : '' }}">Home</a>
                <a href="{{ route('attractions.index') }}" class="nav-link {{ request()->routeIs('attractions.*') ? 'active text-[--primary-gold]' : '' }}">Attractions</a>
                <a href="{{ route('events.index') }}" class="nav-link {{ request()->routeIs('events.*') ? 'active text-[--primary-gold]' : '' }}">Events</a>
                <a href="{{ route('businesses.index') }}" class="nav-link {{ request()->routeIs('businesses.*') ? 'active text-[--primary-gold]' : '' }}">Businesses</a>
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active text-[--primary-gold]' : '' }}">About Us</a>
                <a href="{{ route('home') }}#footer" class="nav-link">Contact Us</a>
            </div>

            <div class="nav-actions">

                @auth
                    @if(auth()->user()->isUser())
                    <a href="{{ route('my_profile.show') }}" class="nav-fav" aria-label="Favorites">
                        <i class="fa-solid fa-heart"></i>
                        <span class="nav-fav-badge">{{ $favoriteCount }}</span>
                    </a>
                    @endif
                @endauth

                @guest
                    <div class="hidden sm:flex items-center gap-3">
                        <a href="{{ route('login') }}" class="nav-ghost-btn">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary px-5 py-2.5 rounded-full shadow-lg font-semibold">Join Now</a>
                    </div>
                @endguest

                @auth
                    <div class="relative hidden lg:block account-menu-wrapper">
                        <button type="button" class="account-trigger" data-dropdown-target="account-menu">
                            <span class="account-avatar">{{ $accountInitial }}</span>
                            <span class="account-name">{{ mb_substr(auth()->user()->full_name ?? 'Account', 0, 14) }}</span>
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </button>

                        <div id="account-menu" class="account-dropdown hidden">
                            <a href="{{ $dashboardRoute }}" class="account-dropdown-item">
                                <i class="fa-solid fa-user"></i>
                                <span>{{ $dashboardLabel }}</span>
                            </a>
                            @if(auth()->user()->isUser())
                            <a href="{{ route('recommendations.index') }}" class="account-dropdown-item">
                                <i class="fa-solid fa-wand-magic-sparkles"></i>
                                <span>Recommendations</span>
                            </a>
                            <a href="{{ route('my_profile.show') }}" class="account-dropdown-item">
                                <i class="fa-solid fa-user"></i>
                                <span>My Profile</span>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="account-dropdown-item">
                                <i class="fa-solid fa-gear"></i>
                                <span>Edit Profile</span>
                            </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="account-dropdown-item account-dropdown-danger w-full text-left">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                <button type="button" class="mobile-menu-btn lg:hidden" data-mobile-target="mobile-menu" aria-label="Open menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="mobile-menu hidden lg:hidden">
            <div class="mobile-menu-links">
                <a href="{{ route('home') }}" class="mobile-menu-link">Home</a>
                <a href="{{ route('attractions.index') }}" class="mobile-menu-link">Attractions</a>
                <a href="{{ route('events.index') }}" class="mobile-menu-link">Events</a>
                <a href="{{ route('businesses.index') }}" class="mobile-menu-link">Businesses</a>
                <a href="{{ route('about') }}" class="mobile-menu-link">About Us</a>
                <a href="{{ route('home') }}#footer" class="mobile-menu-link">Contact Us</a>
            </div>

            <div class="mobile-account">
                @guest
                    <a href="{{ route('login') }}" class="mobile-menu-link">Login</a>
                    <a href="{{ route('register') }}" class="mobile-menu-link mobile-menu-link-primary">Join Now</a>
                @endguest

                @auth
                    <div class="mobile-account-header">
                        <span class="account-avatar">{{ $accountInitial }}</span>
                        <div>
                            <p class="mobile-account-name">{{ auth()->user()->full_name }}</p>
                            <p class="mobile-account-role">{{ strtoupper(auth()->user()->role) }}</p>
                        </div>
                    </div>
                    <a href="{{ $dashboardRoute }}" class="mobile-menu-link">{{ $dashboardLabel }}</a>
                        @if(auth()->user()->isUser())
                    <a href="{{ route('recommendations.index') }}" class="mobile-menu-link">Recommendations</a>
                    <a href="{{ route('my_profile.show') }}" class="mobile-menu-link">My Profile</a>
                    <a href="{{ route('profile.edit') }}" class="mobile-menu-link">Edit Profile</a>
                        @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="mobile-menu-link mobile-menu-link-danger w-full text-left">Logout</button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</nav>

@yield('content')
<!-- Footer -->
<footer id="footer" class="bg-gradient-to-r from-[#1B4332] to-[#2d5a4d] text-white pt-12">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Top Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
            <!-- Logo and Brand -->
            <div class="flex flex-col items-start">
                <div class="flex items-center gap-4 mb-6">
                    <div class="bg-[#c5a059] p-3 rounded-xl">
                        <img src="{{asset('logo.png')}}" alt="Mudhyaf Logo" class="h-16 w-auto">
                    </div>
                    <div>

                        <p class="text-[#c5a059] font-semibold">Explore Hail's Beauty</p>
                    </div>
                </div>
                <p class="text-gray-200 leading-relaxed text-sm">Your smart companion to discover historical landmarks, authentic dining, and local events.</p>
            </div>

            <!-- Contact Info -->
            <div>
                <h5 class="font-bold text-lg mb-5 text-[#c5a059] flex items-center gap-2 uppercase tracking-wide">
                    <span>📞</span> Get in Touch
                </h5>
                <ul class="space-y-3 text-gray-100">
                    <li class="flex items-center gap-3 hover:text-[#c5a059] transition">
                        <span class="text-lg">▶</span>
                        <a href="tel:+966165555555">+966 16 555 5555</a>
                    </li>
                    <li class="flex items-center gap-3 hover:text-[#c5a059] transition">
                        <span class="text-lg">▶</span>
                        <a href="mailto:info@mudhyaf.sa">info@mudhyaf.sa</a>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="text-lg">▶</span>
                        <span>Hail, Saudi Arabia</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="text-lg">▶</span>
                        <span>Open 24/7</span>
                    </li>
                </ul>
            </div>

            <!-- Quick Links -->
            <div>
                <h5 class="font-bold text-lg mb-5 text-[#c5a059] flex items-center gap-2 uppercase tracking-wide">
                    <span>🔗</span> Explore
                </h5>
                <ul class="space-y-2.5 text-gray-100">
                    <li><a href="{{ route('attractions.index') }}" class="flex items-center gap-2 hover:text-[#c5a059] transition">Landmarks</a></li>
                    <li><a href="{{ route('businesses.index') }}" class="flex items-center gap-2 hover:text-[#c5a059] transition">Dining & Cafés</a></li>
                    <li><a href="{{ route('events.index') }}" class="flex items-center gap-2 hover:text-[#c5a059] transition">Events</a></li>
                    <li><a href="{{ route('businesses.index') }}" class="flex items-center gap-2 hover:text-[#c5a059] transition">Plan My Trip</a></li>
                </ul>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-white/20"></div>

        <!-- Bottom Section -->
        <div class="pt-3">
            <!-- Social & Copyright -->
            <div class="text-center">
                <p class="text-gray-300 text-sm pb-3">
                    &copy; 2026 <span class="text-[#c5a059] font-bold">Mudhyaf</span> - Hail Smart Tourism System
                </p>
            </div>


        </div>
    </div>
</footer>
<script>
    function toggleFavorite(button) {
        const entityId = button.dataset.id;
        const entityType = button.dataset.type;

        fetch("{{ route('favorites.toggle') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                entity_id: entityId,
                entity_type: entityType
            })
            })
            .then(res => res.json())
            .then(data => {
                const icon = button.querySelector("svg, i");
                const label = button.querySelector(".favorite-label");

                if (data.status === "added") {
                    button.dataset.favorited = "1";
                    button.classList.add("is-favorited");
                    if (icon) {
                        icon.classList.remove("text-gray-400", "text-slate-400");
                        icon.classList.add("text-red-500");
                    }
                    if (label) {
                        label.textContent = "Saved";
                    }
                } else {
                    button.dataset.favorited = "0";
                    button.classList.remove("is-favorited");
                    if (icon) {
                        icon.classList.remove("text-red-500");
                        icon.classList.add("text-gray-400");
                    }
                    if (label) {
                        label.textContent = "Favorite";
                    }

                    if (button.dataset.refreshAfterToggle === "1") {
                        window.location.reload();
                        return;
                    }
                }
            });
    }

    (function () {
        const dropdownTriggers = document.querySelectorAll('[data-dropdown-target]');
        const mobileTriggers = document.querySelectorAll('[data-mobile-target]');

        dropdownTriggers.forEach((trigger) => {
            trigger.addEventListener('click', function (event) {
                event.stopPropagation();
                const target = document.getElementById(this.dataset.dropdownTarget);
                document.querySelectorAll('.account-dropdown').forEach((menu) => {
                    if (menu !== target) {
                        menu.classList.add('hidden');
                    }
                });
                target.classList.toggle('hidden');
            });
        });

        mobileTriggers.forEach((trigger) => {
            trigger.addEventListener('click', function () {
                const target = document.getElementById(this.dataset.mobileTarget);
                target.classList.toggle('hidden');
            });
        });

        document.addEventListener('click', function (event) {
            if (!event.target.closest('.account-menu-wrapper') && !event.target.closest('[data-mobile-target]')) {
                document.querySelectorAll('.account-dropdown').forEach((menu) => menu.classList.add('hidden'));
            }

            const mobileMenu = document.getElementById('mobile-menu');
            const mobileButton = document.querySelector('[data-mobile-target="mobile-menu"]');

            if (
                mobileMenu &&
                mobileButton &&
                !event.target.closest('#mobile-menu') &&
                !event.target.closest('[data-mobile-target="mobile-menu"]')
            ) {
                mobileMenu.classList.add('hidden');
            }
        });
    })();
</script>
</body>
</html>
