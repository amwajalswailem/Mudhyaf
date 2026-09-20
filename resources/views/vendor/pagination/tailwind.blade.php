@if ($paginator->hasPages())
    <nav role="navigation" class="flex items-center justify-center mt-12">
        <div class="flex items-center space-x-2">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 rounded-full bg-gray-100 text-gray-400 cursor-not-allowed">
                    <i class="fa-solid fa-chevron-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="px-4 py-2 rounded-full bg-white shadow hover:bg-gray-50 transition">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
            @endif


            {{-- Page Numbers --}}
            @foreach ($elements as $element)

                {{-- Separator --}}
                @if (is_string($element))
                    <span class="px-3 py-2 text-gray-400">...</span>
                @endif

                {{-- Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)

                        @if ($page == $paginator->currentPage())
                            <span class="px-4 py-2 rounded-full bg-gradient-to-r from-[--deep-green] to-emerald-600 text-white font-bold shadow">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="px-4 py-2 rounded-full bg-white shadow hover:bg-gray-50 transition">
                                {{ $page }}
                            </a>
                        @endif

                    @endforeach
                @endif

            @endforeach


            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="px-4 py-2 rounded-full bg-white shadow hover:bg-gray-50 transition">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            @else
                <span class="px-4 py-2 rounded-full bg-gray-100 text-gray-400 cursor-not-allowed">
                    <i class="fa-solid fa-chevron-right"></i>
                </span>
            @endif

        </div>
    </nav>
@endif
