@if ($paginator->hasPages())
<nav role="navigation" aria-label="Paginación" class="d-flex justify-content-end">
    <ul class="pagination pagination-sm mb-0">
        {{-- Anterior --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link">Anterior</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Anterior</a>
            </li>
        @endif

        {{-- Números de página --}}
        @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
            @if ($page == $paginator->currentPage())
                <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
            @endif
        @endforeach

        {{-- Siguiente --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Siguiente</a>
            </li>
        @else
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link">Siguiente</span>
            </li>
        @endif
    </ul>
</nav>
@endif
