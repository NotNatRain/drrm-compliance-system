@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">

        {{-- Results Info --}}
        <div>
            <p style="font-size: 0.78rem; color: #6c757d; margin: 0;">
                {!! __('Showing') !!}
                @if ($paginator->firstItem())
                    <span style="font-weight: 600;">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span style="font-weight: 600;">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                {!! __('of') !!}
                <span style="font-weight: 600;">{{ $paginator->total() }}</span>
                {!! __('results') !!}
            </p>
        </div>

        {{-- Pagination Buttons --}}
        <div style="display: flex; align-items: center; gap: 3px;">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span style="
                    display: inline-flex; align-items: center; gap: 4px;
                    padding: 5px 10px;
                    font-size: 0.75rem; font-weight: 500;
                    color: #adb5bd;
                    background: #fff;
                    border: 1px solid #dee2e6;
                    border-radius: 5px;
                    cursor: not-allowed;
                "><i class="fas fa-chevron-left" style="font-size: 0.5rem;"></i> Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="
                    display: inline-flex; align-items: center; gap: 4px;
                    padding: 5px 10px;
                    font-size: 0.75rem; font-weight: 500;
                    color: #495057;
                    background: #fff;
                    border: 1px solid #dee2e6;
                    border-radius: 5px;
                    text-decoration: none;
                    transition: all 0.2s;
                " onmouseover="this.style.backgroundColor='#8A1217';this.style.borderColor='#8A1217';this.style.color='#fff';"
                   onmouseout="this.style.backgroundColor='#fff';this.style.borderColor='#dee2e6';this.style.color='#495057';"
                ><i class="fas fa-chevron-left" style="font-size: 0.5rem;"></i> Prev</a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span style="
                        display: inline-flex; align-items: center; justify-content: center;
                        padding: 5px 8px;
                        font-size: 0.75rem;
                        color: #adb5bd;
                        background: #fff;
                        border: 1px solid #dee2e6;
                        border-radius: 5px;
                    ">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" style="
                                display: inline-flex; align-items: center; justify-content: center;
                                padding: 5px 9px;
                                font-size: 0.75rem; font-weight: 700;
                                color: #fff;
                                background: #A8191F;
                                border: 1px solid #A8191F;
                                border-radius: 5px;
                            ">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" style="
                                display: inline-flex; align-items: center; justify-content: center;
                                padding: 5px 9px;
                                font-size: 0.75rem; font-weight: 500;
                                color: #495057;
                                background: #fff;
                                border: 1px solid #dee2e6;
                                border-radius: 5px;
                                text-decoration: none;
                                transition: all 0.2s;
                            " onmouseover="this.style.backgroundColor='#8A1217';this.style.borderColor='#8A1217';this.style.color='#fff';"
                               onmouseout="this.style.backgroundColor='#fff';this.style.borderColor='#dee2e6';this.style.color='#495057';"
                            >{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="
                    display: inline-flex; align-items: center; gap: 4px;
                    padding: 5px 10px;
                    font-size: 0.75rem; font-weight: 500;
                    color: #495057;
                    background: #fff;
                    border: 1px solid #dee2e6;
                    border-radius: 5px;
                    text-decoration: none;
                    transition: all 0.2s;
                " onmouseover="this.style.backgroundColor='#8A1217';this.style.borderColor='#8A1217';this.style.color='#fff';"
                   onmouseout="this.style.backgroundColor='#fff';this.style.borderColor='#dee2e6';this.style.color='#495057';"
                >Next <i class="fas fa-chevron-right" style="font-size: 0.5rem;"></i></a>
            @else
                <span style="
                    display: inline-flex; align-items: center; gap: 4px;
                    padding: 5px 10px;
                    font-size: 0.75rem; font-weight: 500;
                    color: #adb5bd;
                    background: #fff;
                    border: 1px solid #dee2e6;
                    border-radius: 5px;
                    cursor: not-allowed;
                ">Next <i class="fas fa-chevron-right" style="font-size: 0.5rem;"></i></span>
            @endif
        </div>
    </nav>
@endif
