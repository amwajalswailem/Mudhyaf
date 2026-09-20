@extends('admin.layouts.app')

@section('role', 'Business Owner')

@section('content')
    <div class="space-y-10">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
            <div class="md:col-span-8">
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 h-full">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-extrabold text-gray-800 mb-3">My Business</h2>

                            @if($stats['my_business'])
                                <p class="text-lg font-bold text-gray-700">
                                    {{ $stats['my_business']->name }}
                                </p>

                                <p class="text-gray-500 mt-2 max-w-xl">
                                    {{ $stats['my_business']->description ?? 'No description available' }}
                                </p>

                                <span class="inline-block mt-4 px-4 py-1 rounded-full text-xs font-bold
                                    {{ $stats['my_business']->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $stats['my_business']->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $stats['my_business']->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ strtoupper($stats['my_business']->status) }}
                                </span>

                                <div class="grid grid-cols-3 gap-3 mt-6">
                                    <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Rating</p>
                                        <p class="text-xl font-extrabold text-[--deep-green] mt-2">{{ number_format($stats['business_rating'] ?? 0, 1) }}</p>
                                    </div>
                                    <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Reviews</p>
                                        <p class="text-xl font-extrabold text-[--deep-green] mt-2">{{ $stats['business_reviews'] ?? 0 }}</p>
                                    </div>
                                    <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Likes</p>
                                        <p class="text-xl font-extrabold text-[--deep-green] mt-2">{{ $stats['business_favorites'] ?? 0 }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="text-gray-400">You have not created a business yet.</p>
                            @endif
                        </div>

                        <div class="w-20 h-20 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center text-3xl">
                            <i class="fa-solid fa-store"></i>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Weather Widget (4 columns) --}}
            <div class="md:col-span-4">
                <div class="bg-gradient-to-br from-[--deep-green] to-[#2d5a44] p-8 rounded-3xl text-white shadow-lg h-full">

                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h4 class="text-xl font-bold">Hail, KSA</h4>
                            <p class="text-white/60">{{ now()->format('M d, Y') }}</p>
                        </div>

                        <i class="fa-solid fa-sun text-3xl text-[--primary-gold]"></i>
                    </div>

                    <div class="text-5xl font-bold mb-2">22°C</div>
                    <p class="font-medium text-white/80">
                        Perfect for outdoor exploration
                    </p>

                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
            <div class="md:col-span-4 space-y-8">
                <div class="bg-white p-6 rounded-3xl shadow-lg border border-gray-100 hover:shadow-2xl transition transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total Events</p>
                            <h2 class="text-3xl font-extrabold text-blue-600 mt-2">{{ $stats['my_events'] }}</h2>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl">
                            <i class="fa-solid fa-calendar-days"></i>
                        </div>
                    </div>
                </div>


                <div class="bg-white p-6 rounded-3xl shadow-lg border border-gray-100 hover:shadow-2xl transition transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Approved Events</p>
                            <h2 class="text-3xl font-extrabold text-green-600 mt-2">{{ $stats['approved_events'] }}</h2>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center text-2xl">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-lg border border-gray-100 hover:shadow-2xl transition transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Business Reviews</p>
                            <h2 class="text-3xl font-extrabold text-[--deep-green] mt-2">{{ $stats['business_reviews'] ?? 0 }}</h2>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-2xl">
                            <i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-lg border border-gray-100 hover:shadow-2xl transition transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Recommendation Appearances</p>
                            <h2 class="text-3xl font-extrabold text-[--deep-green] mt-2">{{ $stats['recommendation_appearances'] ?? 0 }}</h2>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                        </div>
                    </div>
                </div>

                <a href="{{ route('owner.reviews.index') }}"
                   class="bg-[--deep-green] text-white p-6 rounded-3xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1 block">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white/70 text-sm">Owner Reviews</p>
                            <h2 class="text-3xl font-extrabold mt-2">{{ $stats['total_reviews'] ?? 0 }}</h2>
                            <p class="text-white/70 text-sm mt-2">View feedback on your business and events</p>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-white/15 text-white flex items-center justify-center text-2xl">
                            <i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="md:col-span-8">
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 h-full">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Events Status Overview</h3>
                    <div id="eventsChart"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var options = {
                chart: {
                    type: 'area',
                    height: 350,
                    toolbar: { show: false }
                },
                series: [{
                    name: 'Events',
                    data: [
                        {{ $stats['approved_events'] }},
                        {{ $stats['pending_events'] }},
                        {{ $stats['rejected_events'] }}
                    ]
                }],
                xaxis: {
                    categories: ['Approved', 'Pending', 'Rejected']
                },
                colors: ['#16a34a'],
                fill: {
                    type: "gradient",
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.1,
                    }
                },
                stroke: { curve: 'smooth' },
                dataLabels: { enabled: false }
            };

            var chart = new ApexCharts(document.querySelector("#eventsChart"), options);
            chart.render();
        });
    </script>
@endsection
