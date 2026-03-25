@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center">

            {{-- Bouton Précédent --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">
                        <i class="bi bi-arrow-left-circle"></i> Précédent
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <i class="bi bi-arrow-left-circle"></i> Précédent
                    </a>
                </li>
            @endif

            {{-- Liens numérotés --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Bouton Suivant --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        Suivant <i class="bi bi-arrow-right-circle"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">
                        Suivant <i class="bi bi-arrow-right-circle"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
