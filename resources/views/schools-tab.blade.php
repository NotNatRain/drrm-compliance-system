{{-- resources/views/schools-tab.blade.php --}}
<style>
    /* =========================================================
     * School Tab — Wireframe Redesign
     * ========================================================= */
    :root {
        --st-navy: #0D1B36;
        --st-blue: #1E3A5F;
        --st-accent: #2563EB;
        --st-bg: #F8FAFC;
        --st-border: #E2E8F0;
    }

    #schoolsTabContent {
        animation: stFadeIn 0.35s ease both;
    }
    @keyframes stFadeIn {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Header */
    .school-mgmt-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .school-mgmt-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--st-navy);
    }

    .school-mgmt-title-icon {
        width: 36px;
        height: 36px;
        background: #EFF6FF;
        color: var(--st-accent);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    /* Controls Row */
    .school-controls-bar {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn-add-school {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 18px;
        border-radius: 8px;
        background: var(--st-navy);
        color: #fff;
        font-weight: 700;
        font-size: 0.9rem;
        border: none;
        cursor: pointer;
        white-space: nowrap;
        transition: background 0.2s;
    }
    .btn-add-school:hover { background: #1E3A5F; }

    /* Region Filter dropdown */
    .region-filter-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 14px;
        border-radius: 8px;
        background: #fff;
        border: 1px solid var(--st-border);
        font-weight: 600;
        font-size: 0.88rem;
        color: #374151;
        cursor: pointer;
        white-space: nowrap;
        transition: background 0.2s;
    }
    .region-filter-btn:hover { background: #F1F5F9; }

    /* Search */
    .school-search-wrap {
        position: relative;
        flex: 1 1 220px;
        max-width: 300px;
    }
    .school-search-wrap .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9CA3AF;
        font-size: 0.85rem;
        pointer-events: none;
    }
    .school-search-input {
        width: 100%;
        padding: 9px 12px 9px 34px;
        border-radius: 8px;
        border: 1px solid var(--st-border);
        font-size: 0.88rem;
        color: #374151;
        background: #fff;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .school-search-input:focus {
        border-color: var(--st-accent);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    /* Grid */
    .schools-grid-wrapper {
        background: transparent;
    }

    #schoolsGrid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    @media (max-width: 1024px) { #schoolsGrid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px)  { #schoolsGrid { grid-template-columns: 1fr; } }

    /* School Card */
    .school-item-col {
        border-radius: 12px;
        background: #FFFFFF;
        border: 1px solid var(--st-border);
        transition: box-shadow 0.2s, transform 0.2s;
    }
    .school-item-col:hover {
        box-shadow: 0 8px 24px rgba(13,27,54,0.1);
        transform: translateY(-2px);
    }

    .school-card-new {
        padding: 1.25rem 1.25rem 1rem;
        cursor: pointer;
        transition: background 0.2s;
        height: 100%;
        min-height: 170px;
    }
    .school-card-new:hover { background: #F8FAFC; }

    .school-card-header {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 0.75rem;
    }

    .school-card-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background: #EFF6FF;
        color: var(--st-accent);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .school-card-name {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--st-navy);
        line-height: 1.3;
    }
    .school-card-id {
        font-size: 0.75rem;
        color: #6B7280;
        margin-top: 2px;
    }

    .school-card-address {
        font-size: 0.78rem;
        color: #EF4444;
        display: flex;
        align-items: flex-start;
        gap: 5px;
        margin-bottom: 0.9rem;
        line-height: 1.4;
    }
    .school-card-address.not-set { color: #9CA3AF; font-style: italic; }

    .school-card-footer {
        display: flex;
        justify-content: space-between;
        padding-top: 0.6rem;
        border-top: 1px solid var(--st-border);
    }

    .school-card-meta-label {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #9CA3AF;
        margin-bottom: 2px;
    }
    .school-card-meta-value {
        font-size: 0.78rem;
        color: #374151;
        font-weight: 600;
    }
    .school-card-meta-value.not-set { color: #9CA3AF; font-style: italic; font-weight: 400; }

    /* No results */
    .school-no-results {
        text-align: center;
        padding: 3rem 1rem;
        color: #9CA3AF;
        display: none;
    }

    /* Focus highlight */
    .school-card-focus-highlight .school-card-new {
        background: #EFF6FF;
        box-shadow: inset 0 0 0 2px var(--st-accent);
        border-radius: 0;
        animation: cardPulse 1.3s ease-in-out 2;
    }
    @keyframes cardPulse {
        0%, 100% { background: #EFF6FF; }
        50% { background: #DBEAFE; }
    }

    /* Responsive header */
    @media (max-width: 640px) {
        .school-mgmt-header { flex-direction: column; align-items: flex-start; }
        .school-controls-bar { width: 100%; }
        .school-search-wrap { max-width: 100%; }
    }

    /* ==== Add School Modal Redesign ==== */
    .modal-add-school .modal-content {
        border: 0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(13,27,54,0.18);
    }
    .modal-add-school .modal-header {
        background: var(--st-navy);
        padding: 1.2rem 1.5rem;
        border-bottom: none;
    }
    .modal-add-school .modal-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .modal-add-school .modal-title .title-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }
    .modal-add-school .modal-body {
        padding: 1.5rem;
        max-height: 72vh;
        overflow-y: auto;
    }
    .form-section-label {
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--st-accent);
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--st-border);
    }
    .modal-add-school .form-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #374151;
        margin-bottom: 0.3rem;
    }
    .modal-add-school .form-control {
        border-radius: 8px;
        border: 1px solid #D1D5DB;
        font-size: 0.9rem;
        padding: 0.6rem 0.9rem;
        color: var(--st-navy);
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .modal-add-school .form-control:focus {
        border-color: var(--st-accent);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
    }
    .modal-add-school .form-control::placeholder { color: #9CA3AF; font-size: 0.88rem; }
    .modal-add-school .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--st-border);
        background: #FAFAFA;
    }
    .btn-save-school {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 22px;
        background: var(--st-navy);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-save-school:hover { background: #1E3A5F; }
    .btn-cancel-school {
        padding: 9px 18px;
        background: transparent;
        border: 1px solid #D1D5DB;
        border-radius: 8px;
        color: #374151;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-cancel-school:hover { background: #F3F4F6; }
</style>

{{-- Schools Tab Content --}}
<div class="mt-2 pb-5">
    {{-- Header --}}
    <div class="school-mgmt-header">
        <div class="school-mgmt-title">
            <div class="school-mgmt-title-icon"><i class="fas fa-school"></i></div>
            School Management
        </div>
        <div class="school-controls-bar">
            <button class="btn-add-school" onclick="openAddSchoolModal()">
                <i class="fas fa-plus"></i> Add School
            </button>

            {{-- Region filter --}}
            <div class="dropdown">
                <button class="region-filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" id="regionFilterBtn">
                    <i class="fas fa-filter" style="font-size:0.78rem;"></i> All Regions
                </button>
                <div class="dropdown-menu p-3" style="min-width:240px;" aria-labelledby="regionFilterBtn">
                    <p class="small fw-bold text-uppercase mb-2 text-muted">Sort By</p>
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="radio" name="schoolSortType" id="sortTypeAlphabetical" value="alphabetical" checked>
                        <label class="form-check-label" for="sortTypeAlphabetical">Alphabetical</label>
                    </div>
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="radio" name="schoolSortType" id="sortTypeDateAdded" value="date_added">
                        <label class="form-check-label" for="sortTypeDateAdded">Date Added</label>
                    </div>
                    <hr class="my-2">
                    <p class="small fw-bold text-uppercase mb-2 text-muted">Order</p>
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="radio" name="schoolSortOrder" id="sortOrderAsc" value="asc" checked>
                        <label class="form-check-label" for="sortOrderAsc">Ascending</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="schoolSortOrder" id="sortOrderDesc" value="desc">
                        <label class="form-check-label" for="sortOrderDesc">Descending</label>
                    </div>
                </div>
            </div>

            {{-- Search --}}
            <div class="school-search-wrap">
                <i class="fas fa-search search-icon"></i>
                <input
                    type="search"
                    id="schoolSearchInput"
                    class="school-search-input"
                    placeholder="Search school name"
                    aria-label="Search school name"
                    autocomplete="off"
                    spellcheck="false"
                    readonly
                    value=""
                >
            </div>
        </div>
    </div>

    {{-- Schools Grid --}}
    <div class="schools-grid-wrapper">
        <div id="schoolsGrid">
            @foreach($allSchools as $school)
                <div class="school-item-col" 
                     data-school-id="{{ $school->id }}" 
                     data-school-name="{{ strtolower($school->school_name) }}" 
                     data-created-at="{{ optional($school->created_at)->timestamp ?? 0 }}">
                    <div class="school-card-new" onclick="viewSchoolDetails({{ $school->id }})">
                        <div class="school-card-header">
                            <div class="school-card-icon"><i class="fas fa-school"></i></div>
                            <div>
                                <div class="school-card-name">{{ $school->school_name }}</div>
                                <div class="school-card-id">ID: {{ $school->school_id ?: ($school->school_id_number ?: 'N/A') }}</div>
                            </div>
                        </div>

                        @if($school->address)
                            <div class="school-card-address">
                                <i class="fas fa-map-marker-alt" style="margin-top:2px; flex-shrink:0;"></i>
                                <span>{{ Str::limit($school->address, 65) }}</span>
                            </div>
                        @else
                            <div class="school-card-address not-set">
                                <i class="fas fa-map-marker-alt" style="margin-top:2px; flex-shrink:0;"></i>
                                <span>Address not set</span>
                            </div>
                        @endif

                        <div class="school-card-footer">
                            <div>
                                <div class="school-card-meta-label">School Head</div>
                                <div class="school-card-meta-value {{ $school->school_head ? '' : 'not-set' }}">
                                    {{ $school->school_head ?: 'Not set' }}
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="school-card-meta-label">DRRM Coord.</div>
                                <div class="school-card-meta-value {{ $school->drrm_coordinator ? '' : 'not-set' }}">
                                    {{ $school->drrm_coordinator ?: 'Not set' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div id="schoolsNoResults" class="school-no-results">
            <i class="fas fa-search fa-2x mb-3 d-block"></i>
            No schools found for this search.
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const grid = document.getElementById('schoolsGrid');
        const sortTypeOptions = Array.from(document.querySelectorAll('input[name="schoolSortType"]'));
        const sortOrderOptions = Array.from(document.querySelectorAll('input[name="schoolSortOrder"]'));
        const searchInput = document.getElementById('schoolSearchInput');
        const noResults = document.getElementById('schoolsNoResults');

        if (!grid || !searchInput || !noResults) return;

        const schoolCols = () => Array.from(grid.querySelectorAll('.school-item-col'));

        // Anti-autofill
        searchInput.value = '';
        setTimeout(() => { searchInput.value = ''; }, 50);
        setTimeout(() => { searchInput.value = ''; }, 250);
        window.addEventListener('pageshow', () => { searchInput.value = ''; });

        const enableSearch = () => searchInput.removeAttribute('readonly');
        searchInput.addEventListener('focus', enableSearch, { once: true });
        searchInput.addEventListener('click', enableSearch, { once: true });

        function getChecked(options, fallback) {
            const found = options.find(o => o.checked);
            return found ? found.value : fallback;
        }

        function applyFilters() {
            const sortType = getChecked(sortTypeOptions, 'alphabetical');
            const sortOrder = getChecked(sortOrderOptions, 'asc');
            const kw = (searchInput.value || '').trim().toLowerCase();

            let visible = 0;
            const all = schoolCols();

            const filtered = all.filter(col => {
                const match = !kw || (col.dataset.schoolName || '').includes(kw);
                col.classList.toggle('d-none', !match);
                if (match) visible++;
                return match;
            });

            filtered.sort((a, b) => {
                let cmp = 0;
                if (sortType === 'date_added') {
                    cmp = Number(a.dataset.createdAt || 0) - Number(b.dataset.createdAt || 0);
                } else {
                    cmp = (a.dataset.schoolName || '').localeCompare(b.dataset.schoolName || '');
                }
                return sortOrder === 'desc' ? -cmp : cmp;
            });

            filtered.forEach(col => grid.appendChild(col));

            noResults.style.display = visible === 0 ? 'block' : 'none';
        }

        sortTypeOptions.forEach(o => o.addEventListener('change', applyFilters));
        sortOrderOptions.forEach(o => o.addEventListener('change', applyFilters));
        searchInput.addEventListener('input', applyFilters);

        applyFilters();
    });
</script>
