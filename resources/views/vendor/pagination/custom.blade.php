@if ($paginator->hasPages())
    <ul class="pagination" role="navigation">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                <a href="javascript:void(0)" aria-label="Previous">
                    <i class="fa fa-caret-left" aria-hidden="true"></i>
                </a>
            </li>
        @else
            <li class="">
                <a href="{{ $paginator->previousPageUrl() }}" aria-label="@lang('pagination.previous')" rel="prev">
                    <i class="fa fa-caret-left" aria-hidden="true"></i>
                </a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class=" active" aria-current="page"><span>{{ $page }} <span class="sr-only">(current)</span></span></li>
                    @else
                        <li class=""><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                    <i class="fa fa-caret-right" aria-hidden="true"></i>
                </a>
            </li>
        @else
            <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                <a href="javascript:void(0)" aria-label="Next">
                    <i class="fa fa-caret-right" aria-hidden="true"></i>
                </a>
            </li>
        @endif
    </ul>
@endif
