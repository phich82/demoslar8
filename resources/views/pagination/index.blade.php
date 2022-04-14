@if ($paginator->hasPages())
    <ul class="pagination">
        <li class="page-item{{$paginator->onFirstPage() ? ' disabled' : ''}}">
            <a class="page-link" aria-label="Previous"
               href="{{ $paginator->onFirstPage() ? $paginator->url(1) : $paginator->previousPageUrl() }}"
            >
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Previous</span>
            </a>
        </li>

        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item disabled">
                    <span class="page-link">
                        <span style="position: relative; top: -2px;">{{ $element }}</span>
                    </span>
                </li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        <li class="page-item{{ $paginator->hasMorePages() ? '' : ' disabled' }}">
            <a class="page-link" aria-label="Next"
                href="{{ $paginator->hasMorePages() ? $paginator->nextPageUrl() : $paginator->lastPage() }}"
            >
                <span class="sr-only">Next</span>
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
@endif
