@if ($paginator->hasPages())
    <nav class="hillnest-pagination" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="hillnest-pagination__btn is-disabled">Prev</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="hillnest-pagination__btn">Prev</a>
        @endif

        @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
            @if ($page == $paginator->currentPage())
                <span class="hillnest-pagination__page is-active">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="hillnest-pagination__page">{{ $page }}</a>
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="hillnest-pagination__btn">Next</a>
        @else
            <span class="hillnest-pagination__btn is-disabled">Next</span>
        @endif
    </nav>
@endif
