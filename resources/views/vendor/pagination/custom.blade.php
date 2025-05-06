@if ($paginator->hasPages())
    <nav>
        <ul class="pagination" style="display: flex; list-style: none; padding: 0;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')" style="margin-right: 5px;">
                    <span aria-hidden="true" style="display: inline-block; padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px; background-color: #f5f5f5;">&lsaquo;</span>
                </li>
            @else
                <li style="margin-right: 5px;">
                    <a href="{{ $paginator->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}" rel="prev" aria-label="@lang('pagination.previous')" style="display: inline-block; padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px; background-color: #fff; text-decoration: none; color: #007bff;">&lsaquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled" aria-disabled="true" style="margin-right: 5px;"><span style="display: inline-block; padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px; background-color: #f5f5f5;">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active" aria-current="page" style="margin-right: 5px;"><span style="display: inline-block; padding: 5px 10px; border: 1px solid #007bff; border-radius: 4px; background-color: #007bff; color: #fff;">{{ $page }}</span></li>
                        @else
                            <li style="margin-right: 5px;"><a href="{{ $url }}&{{ http_build_query(request()->except('page')) }}" style="display: inline-block; padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px; background-color: #fff; text-decoration: none; color: #007bff;">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li style="margin-right: 5px;">
                    <a href="{{ $paginator->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}" rel="next" aria-label="@lang('pagination.next')" style="display: inline-block; padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px; background-color: #fff; text-decoration: none; color: #007bff;">&rsaquo;</a>
                </li>
            @else
                <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')" style="margin-right: 5px;">
                    <span aria-hidden="true" style="display: inline-block; padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px; background-color: #f5f5f5;">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
