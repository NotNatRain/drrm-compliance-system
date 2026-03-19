@if ($paginator->hasPages())
    <nav aria-label="Pagination" class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <p class="small text-muted mb-0" style="font-size: 0.78rem;">
                Showing
                <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                to
                <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                of
                <span class="fw-semibold">{{ $paginator->total() }}</span>
                results
            </p>
        </div>

        <ul class="pagination mb-0" style="gap: 4px;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" aria-hidden="true" style="
                        padding: 0.3rem 0.65rem;
                        font-size: 0.78rem;
                        border-radius: 6px;
                        border: 1px solid #dee2e6;
                        color: #adb5bd;
                        display: flex; align-items: center; gap: 4px;
                    "><i class="fas fa-chevron-left" style="font-size: 0.55rem;"></i> Prev</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" style="
                        padding: 0.3rem 0.65rem;
                        font-size: 0.78rem;
                        border-radius: 6px;
                        border: 1px solid #dee2e6;
                        color: #495057;
                        display: flex; align-items: center; gap: 4px;
                        text-decoration: none;
                        transition: all 0.2s;
                    " onmouseover="this.style.backgroundColor='#8A1217';this.style.borderColor='#8A1217';this.style.color='white';"
                       onmouseout="this.style.backgroundColor='';this.style.borderColor='#dee2e6';this.style.color='#495057';"
                    ><i class="fas fa-chevron-left" style="font-size: 0.55rem;"></i> Prev</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link" style="
                            padding: 0.3rem 0.55rem;
                            font-size: 0.78rem;
                            border-radius: 6px;
                            border: 1px solid #dee2e6;
                            color: #adb5bd;
                        ">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link" style="
                                    padding: 0.3rem 0.6rem;
                                    font-size: 0.78rem;
                                    border-radius: 6px;
                                    background-color: #A8191F;
                                    border-color: #A8191F;
                                    color: white;
                                    font-weight: 600;
                                ">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}" style="
                                    padding: 0.3rem 0.6rem;
                                    font-size: 0.78rem;
                                    border-radius: 6px;
                                    border: 1px solid #dee2e6;
                                    color: #495057;
                                    text-decoration: none;
                                    transition: all 0.2s;
                                " onmouseover="this.style.backgroundColor='#8A1217';this.style.borderColor='#8A1217';this.style.color='white';"
                                   onmouseout="this.style.backgroundColor='';this.style.borderColor='#dee2e6';this.style.color='#495057';"
                                >{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" style="
                        padding: 0.3rem 0.65rem;
                        font-size: 0.78rem;
                        border-radius: 6px;
                        border: 1px solid #dee2e6;
                        color: #495057;
                        display: flex; align-items: center; gap: 4px;
                        text-decoration: none;
                        transition: all 0.2s;
                    " onmouseover="this.style.backgroundColor='#8A1217';this.style.borderColor='#8A1217';this.style.color='white';"
                       onmouseout="this.style.backgroundColor='';this.style.borderColor='#dee2e6';this.style.color='#495057';"
                    >Next <i class="fas fa-chevron-right" style="font-size: 0.55rem;"></i></a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" aria-hidden="true" style="
                        padding: 0.3rem 0.65rem;
                        font-size: 0.78rem;
                        border-radius: 6px;
                        border: 1px solid #dee2e6;
                        color: #adb5bd;
                        display: flex; align-items: center; gap: 4px;
                    ">Next <i class="fas fa-chevron-right" style="font-size: 0.55rem;"></i></span>
                </li>
            @endif
        </ul>
    </nav>
@endif
