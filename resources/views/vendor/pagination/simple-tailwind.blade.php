@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" style="display: flex; align-items: center; justify-content: center; gap: 6px;">

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

    </nav>
@endif
