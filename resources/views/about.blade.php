@extends('layouts.app')

@section('content')
    <section class="pt-28 pb-24 min-h-screen bg-white">
        <div class="max-w-7xl mx-auto px-6 space-y-20">

            {{-- Title --}}
            <div class="text-center mb-14">
                <h2 class="text-4xl font-bold text-[--deep-green]">
                   About Us
                </h2>
                <p class="text-gray-500 mt-3">
                    Discover All Attractions & Events in Hail
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                <div class="lg:col-span-6 rounded-[2.5rem] overflow-hidden shadow-2xl relative min-h-[520px]">
                    <img src="https://assets.enuygun.com/media/lib/570x400/uploads/image/hail-54680.jpeg"
                         alt="Hail landscape"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-black/20 to-transparent"></div>
                    <div class="absolute left-8 bottom-8 text-white max-w-md">
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#f3d9a1]">About Mudhyaf</p>
                        <h1 class="text-4xl md:text-5xl font-extrabold mt-3 leading-tight">
                            Discover Hail with a smarter tourism experience
                        </h1>
                        <p class="mt-4 text-white/80 leading-7">
                            A curated platform for attractions, restaurants, cafés, and events in one place.
                        </p>
                    </div>
                </div>

                <div class="lg:col-span-6 flex flex-col justify-center">
                    <p class="detail-kicker detail-kicker-dark">Who we are</p>
                    <h2 class="text-4xl md:text-5xl font-extrabold text-[--deep-green] leading-tight mt-3">
                        Built to personalize travel in Hail
                    </h2>
                    <p class="text-gray-600 text-lg leading-8 mt-6 max-w-2xl">
                        Mudhyaf helps visitors find places that match their budget, preferences, and trip style.
                        Tourists can explore attractions, businesses, and events, while owners and admins manage
                        listings through a simple workflow.
                    </p>

                    <div class="grid sm:grid-cols-3 gap-4 mt-10">
                        <div class="feature-card">
                            <div class="feature-icon mb-4"><i class="fa-solid fa-compass"></i></div>
                            <h3 class="feature-title">Discover</h3>
                            <p class="feature-desc text-sm mt-2">Browse curated places across Hail.</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon mb-4"><i class="fa-solid fa-star"></i></div>
                            <h3 class="feature-title">Personalize</h3>
                            <p class="feature-desc text-sm mt-2">Recommendations based on budget and taste.</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon mb-4"><i class="fa-solid fa-shield-heart"></i></div>
                            <h3 class="feature-title">Trust</h3>
                            <p class="feature-desc text-sm mt-2">Admin moderation and verified feedback.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="stat-card bg-white p-6 rounded-3xl shadow-lg border border-gray-100">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Tourist Focus</p>
                    <p class="text-3xl font-extrabold text-[--deep-green] mt-3">100%</p>
                    <p class="text-sm text-gray-500 mt-2">Built around visitors first.</p>
                </div>
                <div class="stat-card bg-white p-6 rounded-3xl shadow-lg border border-gray-100">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Content Types</p>
                    <p class="text-3xl font-extrabold text-[--deep-green] mt-3">3</p>
                    <p class="text-sm text-gray-500 mt-2">Attractions, businesses, and events.</p>
                </div>
                <div class="stat-card bg-white p-6 rounded-3xl shadow-lg border border-gray-100">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Smart Filters</p>
                    <p class="text-3xl font-extrabold text-[--deep-green] mt-3">AI</p>
                    <p class="text-sm text-gray-500 mt-2">Recommendations by budget and preference.</p>
                </div>
                <div class="stat-card bg-white p-6 rounded-3xl shadow-lg border border-gray-100">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Local Support</p>
                    <p class="text-3xl font-extrabold text-[--deep-green] mt-3">24/7</p>
                    <p class="text-sm text-gray-500 mt-2">Always available for planning.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div class="story-card">
                    <p class="detail-kicker detail-kicker-dark">Mission</p>
                    <h3 class="text-3xl font-extrabold text-[--deep-green] mt-3">Make travel decisions simple</h3>
                    <p class="text-gray-600 leading-8 mt-4">
                        The platform reduces guesswork by combining trusted listings, reviews, favorites, and
                        budget-aware guidance into one experience. Tourists get clearer choices. Owners get a path
                        to publish and manage their listings. Admins keep the content accurate.
                    </p>
                </div>

                <div class="story-card">
                    <p class="detail-kicker detail-kicker-dark">What you can do</p>
                    <ul class="space-y-4 mt-4 text-gray-700">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-[--deep-green] mt-1"></i>
                            Explore attractions, businesses, and events in Hail.
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-[--deep-green] mt-1"></i>
                            Save favorites and write reviews on places you visit.
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-[--deep-green] mt-1"></i>
                            Business owners can manage listings and submit events for approval.
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-[--deep-green] mt-1"></i>
                            Admins moderate content to keep the platform reliable.
                        </li>
                    </ul>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('attractions.index') }}"
                   class="inline-flex items-center gap-3 px-8 py-3 rounded-full bg-[--deep-green] text-white font-semibold shadow-md hover:shadow-lg hover:scale-105 transition">
                    Start Exploring
                    <i class="fa-solid fa-arrow-right text-sm"></i>
                </a>
            </div>
        </div>
    </section>
@endsection
