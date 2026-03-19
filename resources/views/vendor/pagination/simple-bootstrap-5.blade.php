@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{!! __('Pagination Navigation') !!}">
        <ul class="pagination mb-0" style="gap: 4px;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" style="
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
                    <span class="page-link" style="
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
