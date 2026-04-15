{{-- resources/views/schools-tab.blade.php --}}
<div class="schools-tab-container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 px-lg-5">
        <h2 class="fw-bold mb-0"><i class="fas fa-university me-2"></i> School Management</h2>
        <div class="school-controls-row">
            <button class="btn btn-dark px-4 py-2 shadow-sm rounded-3" onclick="openAddSchoolModal()">
                <i class="fas fa-plus-circle me-2"></i> Add School
            </button>

            <div class="dropdown school-filter-dropdown">
                <button class="btn btn-outline-dark px-4 py-2 rounded-3 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="schoolFilterMenuBtn">
                    <i class="fas fa-filter me-2"></i> Filter
                </button>
                <div class="dropdown-menu p-3 school-filter-menu" aria-labelledby="schoolFilterMenuBtn">
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

            <div class="school-search-col">
                <input
                    type="search"
                    id="schoolSearchInput"
                    class="form-control"
                    placeholder="Search school name"
                    aria-label="Search school name"
                    name="school-search-query"
                    autocomplete="off"
                    spellcheck="false"
                    autocapitalize="off"
                    autocorrect="off"
                    data-lpignore="true"
                    readonly
                    value=""
                >
            </div>
        </div>
    </div>

    <div id="schoolsGrid" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-5 px-lg-5">
        @foreach($allSchools as $school)
            <div class="col school-item-col" data-school-id="{{ $school->id }}" data-school-name="{{ strtolower($school->school_name) }}" data-created-at="{{ optional($school->created_at)->timestamp ?? 0 }}">
                <div class="card school-card h-100 border-0 shadow-lg rounded-4 overflow-hidden"
                     onclick="viewSchoolDetails({{ $school->id }})"
                     style="cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border-top: 5px solid #212529 !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="school-icon-wrapper rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-school text-dark fs-4"></i>
                            </div>
                            <div>
                                <h5 class="card-title fw-bold mb-0 text-truncate" style="max-width: 200px;">{{ $school->school_name }}</h5>
                                <small class="text-muted">ID: {{ $school->school_id ?: ($school->school_id_number ?: 'N/A') }}</small>
                            </div>
                        </div>

                        <div class="school-info-stack">
                            <p class="card-text mb-2 text-muted small">
                                <i class="fas fa-map-marker-alt me-2"></i> {{ Str::limit($school->address, 60) }}
                            </p>
                            <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                                <div class="info-group">
                                    <small class="d-block text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">School Head</small>
                                    <span class="small fw-semibold">{{ $school->school_head ?: 'Not set' }}</span>
                                </div>
                                <div class="info-group text-end">
                                    <small class="d-block text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">DRRM Coord.</small>
                                    <span class="small fw-semibold">{{ $school->drrm_coordinator ?: 'Not set' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Add School Card at the end --}}
        <div class="col add-school-col">
            <div class="card add-school-card h-100 border-2 border-dashed rounded-4 d-flex align-items-center justify-content-center"
                 onclick="openAddSchoolModal()"
                 style="cursor: pointer; min-height: 200px; border: 2px dashed #ccc; transition: all 0.3s ease;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-muted">
                    <div class="add-icon-wrapper mb-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border: 2px solid #ccc;">
                        <i class="fas fa-plus fs-3"></i>
                    </div>
                    <h5 class="fw-bold mb-0">Add School</h5>
                </div>
            </div>
        </div>
    </div>

    <div id="schoolsNoResults" class="alert alert-light border text-center mt-4 mx-lg-5 d-none">
        No schools found for this search/filter combination.
    </div>
</div>

<style>
    /* Spacing & Layout */
    .schools-tab-container {
        padding-bottom: 5rem;
    }

    .school-controls-row {
        display: grid;
        grid-template-columns: auto auto minmax(220px, 280px);
        gap: 0.5rem;
        align-items: center;
    }

    .school-filter-menu {
        min-width: 240px;
    }

    .school-search-col {
        width: 100%;
    }

    /* Card Styles */
    .school-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
    }

    .school-card-focus-highlight {
        border-top-color: #0d6efd !important;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25), 0 20px 40px rgba(0,0,0,0.18) !important;
        animation: school-focus-pulse 1.3s ease-in-out 2;
    }

    @keyframes school-focus-pulse {
        0% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
        100% { transform: translateY(0); }
    }

    .school-card .card-body {
        padding: 1.5rem !important; /* Reduced padding as requested */
    }

    /* Add School Card Hover Effect */
    .add-school-card:hover {
        background-color: #212529 !important;
        border-color: #212529 !important;
    }

    .add-school-card:hover .card-body {
        color: #fff !important;
    }

    .add-school-card:hover .add-icon-wrapper {
        border-color: #fff !important;
    }

    .add-school-card:hover .add-icon-wrapper i {
        color: #fff !important;
    }

    /* Animation for hovering */
    @keyframes pulse-subtle {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
    }

    @media (max-width: 991px) {
        .school-controls-row {
            grid-template-columns: 1fr;
            width: 100%;
        }
    }

    @media (max-width: 767px) {
        .schools-tab-container .d-flex.justify-content-between.align-items-center {
            align-items: flex-start !important;
            flex-direction: column;
            gap: 1rem;
        }

        .school-controls-row {
            width: 100%;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const grid = document.getElementById('schoolsGrid');
        const sortTypeOptions = Array.from(document.querySelectorAll('input[name="schoolSortType"]'));
        const sortOrderOptions = Array.from(document.querySelectorAll('input[name="schoolSortOrder"]'));
        const searchInput = document.getElementById('schoolSearchInput');
        const noResults = document.getElementById('schoolsNoResults');

        if (!grid || !sortTypeOptions.length || !sortOrderOptions.length || !searchInput || !noResults) return;

        const schoolCols = Array.from(grid.querySelectorAll('.school-item-col'));
        const addSchoolCol = grid.querySelector('.add-school-col');

        // Ensure the search stays empty even if browser autofill tries to restore a value.
        searchInput.value = '';
        setTimeout(() => { searchInput.value = ''; }, 50);
        setTimeout(() => { searchInput.value = ''; }, 250);
        window.addEventListener('pageshow', () => { searchInput.value = ''; });

        // Keep readonly by default to block aggressive autofill, unlock only when user interacts.
        const enableSearchInput = () => {
            searchInput.removeAttribute('readonly');
        };
        searchInput.addEventListener('focus', enableSearchInput, { once: true });
        searchInput.addEventListener('click', enableSearchInput, { once: true });

        function getCheckedValue(options, fallback) {
            const selected = options.find((opt) => opt.checked);
            return selected ? selected.value : fallback;
        }

        function applySchoolFiltersAndSort() {
            const sortType = getCheckedValue(sortTypeOptions, 'alphabetical');
            const sortOrder = getCheckedValue(sortOrderOptions, 'asc');
            const keyword = (searchInput.value || '').trim().toLowerCase();

            let visibleCount = 0;
            const filtered = schoolCols.filter((col) => {
                const name = (col.dataset.schoolName || '').toLowerCase();
                const matches = !keyword || name.includes(keyword);
                col.classList.toggle('d-none', !matches);
                if (matches) visibleCount += 1;
                return matches;
            });

            filtered.sort((a, b) => {
                let comparison = 0;

                if (sortType === 'date_added') {
                    const aDate = Number(a.dataset.createdAt || '0');
                    const bDate = Number(b.dataset.createdAt || '0');
                    comparison = aDate - bDate;
                } else {
                    const aName = (a.dataset.schoolName || '').toLowerCase();
                    const bName = (b.dataset.schoolName || '').toLowerCase();
                    comparison = aName.localeCompare(bName);
                }

                return sortOrder === 'desc' ? comparison * -1 : comparison;
            });

            filtered.forEach((col) => grid.appendChild(col));
            if (addSchoolCol) grid.appendChild(addSchoolCol);

            noResults.classList.toggle('d-none', visibleCount > 0);
        }

        sortTypeOptions.forEach((opt) => opt.addEventListener('change', applySchoolFiltersAndSort));
        sortOrderOptions.forEach((opt) => opt.addEventListener('change', applySchoolFiltersAndSort));
        searchInput.addEventListener('input', applySchoolFiltersAndSort);

        applySchoolFiltersAndSort();
    });
</script>
