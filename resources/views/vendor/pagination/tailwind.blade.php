@if ($paginator->hasPages())
    <nav class="flex justify-center gap-1">
        @if ($paginator->onFirstPage())
            <span class="px-3 py-2 text-sm text-stone-300 rounded-lg">Prev</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-sm text-forest-700 hover:bg-hill-100 rounded-lg">Prev</a>
        @endif
        @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
            @if ($page == $paginator->currentPage())
                <span class="px-3 py-2 text-sm font-semibold bg-forest-700 text-white rounded-lg">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="px-3 py-2 text-sm text-stone-600 hover:bg-hill-100 rounded-lg">{{ $page }}</a>
            @endif
        @endforeach
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-sm text-forest-700 hover:bg-hill-100 rounded-lg">Next</a>
        @else
            <span class="px-3 py-2 text-sm text-stone-300 rounded-lg">Next</span>
        @endif
    </nav>
@endif
