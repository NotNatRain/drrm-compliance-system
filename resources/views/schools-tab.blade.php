{{-- resources/views/schools-tab.blade.php --}}
<div class="schools-tab-container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 px-lg-5">
        <h2 class="fw-bold mb-0"><i class="fas fa-university me-2"></i> School Management</h2>
        <button class="btn btn-dark px-4 py-2 shadow-sm rounded-3" onclick="openAddSchoolModal()">
            <i class="fas fa-plus-circle me-2"></i> Add School
        </button>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-5 px-lg-5">
        @foreach($allSchools as $school)
            <div class="col">
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
        <div class="col">
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
</div>

<style>
    /* Spacing & Layout */
    .schools-tab-container {
        padding-bottom: 5rem;
    }
    
    /* Card Styles */
    .school-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
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
</style>
