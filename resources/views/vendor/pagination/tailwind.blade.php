@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center items-center">
    <div class="flex items-center gap-x-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="py-2 px-2.5 text-sm rounded-full text-gray-400 cursor-not-allowed">
                <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"></path>
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="py-2 px-2.5 text-sm rounded-full text-gray-800 hover:bg-gray-100 transition-colors duration-200">
                <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"></path>
                </svg>
            </a>
        @endif

        {{-- Current Page and Total Pages --}}
        <div class="flex items-center gap-x-1">
            <span class="flex justify-center items-center px-3 py-2 text-sm rounded-full border border-gray-200 bg-yellow-400 text-white font-semibold">
                {{ $paginator->currentPage() }}
            </span>
            <span class="flex justify-center items-center text-gray-500 py-2 px-1.5 text-sm">of</span>
            <span class="flex justify-center items-center text-gray-500 py-2 px-1.5 text-sm">
                {{ $paginator->lastPage() }}
            </span>
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="py-2 px-2.5 text-sm rounded-full text-gray-800 hover:bg-gray-100 transition-colors duration-200">
                <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6"></path>
                </svg>
            </a>
        @else
            <span class="py-2 px-2.5 text-sm rounded-full text-gray-400 cursor-not-allowed">
                <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6"></path>
                </svg>
            </span>
        @endif
    </div>
</nav>
@endif