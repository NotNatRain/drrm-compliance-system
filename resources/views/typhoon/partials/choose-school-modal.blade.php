<div class="modal fade" id="chooseSchoolModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="background: var(--bg-dark); border-radius: 16px; overflow: hidden;">
            <div class="modal-header border-bottom border-white-10" style="background: rgba(0, 210, 255, 0.05); color: #00d2ff; padding: 1.25rem 1.5rem;">
                <h5 class="modal-title fw-bold" style="font-family: 'Sora', sans-serif; letter-spacing: 0.5px; font-size: 1.1rem;">
                    <i class="fas fa-school me-2"></i> SELECT EVACUATION CENTER HUB
                </h5>
                <button type="button" class="btn-close btn-close-white opacity-50" data-bs-dismiss="modal" style="font-size: 0.8rem;"></button>
            </div>
            
            <div class="modal-body p-0 d-flex flex-column" style="max-height: 70vh;">
                @if(($evacuationCenters ?? collect())->isEmpty())
                    <div class="text-center py-5 opacity-50">
                        <i class="fas fa-satellite fa-4x mb-4 text-white"></i>
                        <h4 class="text-white">NO REGISTERED CENTERS</h4>
                        <p class="text-white-50 mb-0">Use "Add Center" to register a new school into the monitoring system.</p>
                    </div>
                @else
                    <!-- Search Bar Section -->
                    <div class="p-3 border-bottom border-white-10" style="background: rgba(255, 255, 255, 0.02); z-index: 10;">
                        <div class="position-relative">
                            <i class="fas fa-search position-absolute" style="top: 50%; left: 1.2rem; transform: translateY(-50%); color: #00d2ff; opacity: 0.7;"></i>
                            <input type="text" id="modalSchoolSearch" class="form-control text-white shadow-none" 
                                   placeholder="Search by school name, ID, or location..." 
                                   style="background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(0, 210, 255, 0.2); padding: 0.75rem 1rem 0.75rem 3rem; border-radius: 10px; font-size: 0.95rem; font-family: 'Inter', sans-serif; transition: all 0.3s ease;">
                        </div>
                    </div>

                    <!-- Scrollable Grid -->
                    <div class="p-4 overflow-auto custom-modal-scrollbar" style="flex: 1;">
                        <div class="row g-4" id="modalSchoolGrid">
                            @foreach($evacuationCenters as $ec)
                                <div class="col-md-6 school-card-wrapper" data-name="{{ strtolower($ec->school_name ?? $ec->identification ?? '') }}" data-location="{{ strtolower($ec->location ?? $ec->address ?? '') }}">
                                    <div class="card h-100 border-0 shadow-sm" 
                                         onclick="window.location='{{ route('typhoon.evacuation-center.show', $ec->id) }}'"
                                         style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08) !important; border-radius: 12px; transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease; cursor: pointer;">
                                        <div class="card-body p-4 d-flex flex-column justify-content-center">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 rounded me-3" style="background: rgba(0, 210, 255, 0.1); border: 1px solid rgba(0, 210, 255, 0.2);">
                                                    <i class="fas fa-hotel text-info fs-5"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold text-white mb-1" style="font-family: 'Sora', sans-serif; font-size: 0.95rem; line-height: 1.3;">
                                                        {{ strtoupper($ec->school_name ?? $ec->identification ?? ('Center #' . $ec->id)) }}
                                                    </h6>
                                                    <div class="small text-white-50" style="font-size: 0.75rem; line-height: 1.4;">
                                                        <i class="fas fa-map-marker-alt me-1 text-danger opacity-75"></i>
                                                        {{ Str::limit($ec->location ?? $ec->address ?? 'No Location Set', 60) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Empty Search State (Hidden by default) -->
                        <div id="modalSearchEmptyState" class="text-center py-5 d-none">
                            <i class="fas fa-search-minus fa-3x mb-3 text-white opacity-25"></i>
                            <h6 class="text-white opacity-75" style="font-family: 'Sora', sans-serif;">No schools matched your search.</h6>
                            <p class="text-white-50 small">Try typing a different name or location.</p>
                        </div>
                    </div>
                @endif
        </div>
    </div>
</div>

<style>
    /* Styling for the modal search bar */
    #modalSchoolSearch::placeholder {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    #modalSchoolSearch:focus {
        border-color: #00d2ff !important;
        box-shadow: 0 0 0 4px rgba(0, 210, 255, 0.1) !important;
        outline: none;
    }
    
    /* Hover effects for school cards */
    .school-card-wrapper .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2) !important;
        border-color: rgba(0, 210, 255, 0.3) !important;
    }
    
    .school-card-wrapper .btn-outline-info:hover {
        background: rgba(0, 210, 255, 0.1);
        color: #00d2ff;
        border-color: #00d2ff;
    }

    /* Custom scrollbar for modal */
    .custom-modal-scrollbar::-webkit-scrollbar {
        width: 8px;
    }
    .custom-modal-scrollbar::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.2); 
    }
    .custom-modal-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1); 
        border-radius: 4px;
    }
    .custom-modal-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.2); 
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('modalSchoolSearch');
        const schoolCards = document.querySelectorAll('.school-card-wrapper');
        const emptyState = document.getElementById('modalSearchEmptyState');
        
        if (searchInput && schoolCards.length > 0) {
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.toLowerCase().trim();
                let visibleCount = 0;
                
                schoolCards.forEach(card => {
                    const name = card.getAttribute('data-name');
                    const location = card.getAttribute('data-location');
                    
                    if (name.includes(query) || location.includes(query)) {
                        card.style.display = '';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                if (emptyState) {
                    if (visibleCount === 0) {
                        emptyState.classList.remove('d-none');
                    } else {
                        emptyState.classList.add('d-none');
                    }
                }
            });
            
            // Clear search when modal closes
            const modalEl = document.getElementById('chooseSchoolModal');
            if (modalEl) {
                modalEl.addEventListener('hidden.bs.modal', function () {
                    searchInput.value = '';
                    schoolCards.forEach(card => card.style.display = '');
                    if (emptyState) emptyState.classList.add('d-none');
                });
                
                // Focus search input when modal opens
                modalEl.addEventListener('shown.bs.modal', function () {
                    searchInput.focus();
                });
            }
        }
    });
</script>

