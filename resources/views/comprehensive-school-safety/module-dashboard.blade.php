@extends('comprehensive-school-safety.layouts.app')
@section('activeMenu', 'dashboard')
@section('headerLabel', 'All Schools')

@section('content')
@php
    $isAdminView = auth()->check() && auth()->user()->role === 'admin';
@endphp

<h2 class="csss-section-title mb-1">Schools Directory</h2>
<p class="csss-muted mb-4">Manage and monitor school safety across your district</p>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Statistics Cards -->
@if(!empty($setupNotice))
    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-exclamation-triangle"></i> {{ $setupNotice }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row mb-5 g-3">
    <div class="col-md-4">
        <div class="csss-card p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="csss-muted small mb-2">Schools in main directory</p>
                    <h3 class="fw-bold mb-0">{{ $stats['directory_total'] ?? 0 }}</h3>
                </div>
                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--csss-primary) 0%, var(--csss-primary-soft) 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-database text-white" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="csss-card p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="csss-muted small mb-2">Registered in this module</p>
                    <h3 class="fw-bold mb-0">{{ $stats['registered_comprehensive'] ?? 0 }}</h3>
                </div>
                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #5c4033 0%, #8b6914 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-clipboard-check text-white" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="csss-card p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="csss-muted small mb-2">Not registered here yet</p>
                    <h3 class="fw-bold mb-0">{{ $stats['pending_registration'] ?? 0 }}</h3>
                </div>
                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #6c757d 0%, #adb5bd 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-hourglass-half text-white" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schools Directory -->
<h4 class="fw-bold mb-3" id="schoolsDirectory">Schools Directory</h4>

@if($recentSchools->isEmpty())
    <div class="csss-card p-5 text-center">
        <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
        <h5 class="csss-muted">No schools registered in this module yet</h5>
        <p class="csss-muted mb-3">Add schools on <strong>DRRM Main Dashboard → Schools</strong>, then register them here to use Comprehensive School Safety.</p>
        @if($isAdminView && ($directorySchoolsForComprehensiveRegistration ?? collect())->isNotEmpty())
            <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#registerSchoolFromDirectoryModal">
                <i class="fas fa-link me-1"></i> Register school
            </button>
        @elseif($isAdminView && ($stats['directory_total'] ?? 0) === 0)
            <p class="small text-muted mb-0">There are no schools in the main directory yet.</p>
        @elseif($isAdminView)
            <p class="small text-muted mb-0">Every directory school is already registered for this module.</p>
        @endif
    </div>
@else
    <div class="row g-3" id="schoolsContainer">
        @foreach($recentSchools as $school)
            <div class="col-md-6 col-lg-4 school-card" data-school-name="{{ strtolower($school->name) }}">
                <div class="csss-card p-4 h-100 school-card-item" style="cursor: pointer;" onclick="window.location.href='{{ route('comprehensive-school-safety.school.assessments', $school->id) }}'">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1">{{ $school->name }}</h5>
                            <p class="csss-muted small mb-0">
                                @if($school->district || $school->division || $school->region)
                                    {{ collect([$school->region, $school->division, $school->district])->filter()->implode(', ') }}
                                @else
                                    No location info
                                @endif
                            </p>
                        </div>
                        <span class="badge bg-light text-dark" style="border-radius: 8px; font-size: 0.75rem;">
                            {{ $school->school_id_number ?? 'Manual' }}
                        </span>
                    </div>

                    <div style="border-top: 1px solid var(--csss-border); padding-top: 1rem; margin-top: 1rem;"></div>

                    <div class="row text-center g-2 mt-2">
                        <div class="col">
                            <p class="csss-muted small mb-0">Students</p>
                            <h6 class="fw-bold mb-0">{{ $school->students()->count() }}</h6>
                        </div>
                        <div class="col">
                            <p class="csss-muted small mb-0">Facilities</p>
                            <h6 class="fw-bold mb-0">{{ $school->facilities()->count() }}</h6>
                        </div>
                        <div class="col">
                            <p class="csss-muted small mb-0">Assessments</p>
                            <h6 class="fw-bold mb-0">{{ $school->assessments()->count() }}</h6>
                        </div>
                    </div>

                    @if($school->address)
                        <div class="mt-3 pt-3" style="border-top: 1px solid var(--csss-border);">
                            <p class="csss-muted small mb-0">
                                <i class="fas fa-map-marker-alt"></i> {{ $school->address }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        @if($isAdminView && ($directorySchoolsForComprehensiveRegistration ?? collect())->isNotEmpty())
        <div class="col-md-6 col-lg-4">
            <div class="csss-card p-4 h-100 d-flex flex-column justify-content-center align-items-center text-center school-card-item"
                 id="addSchoolCard"
                 style="cursor: pointer; border: 2px dashed var(--csss-border);">
                <div style="width: 72px; height: 72px; border-radius: 16px; background: linear-gradient(135deg, var(--csss-primary) 0%, var(--csss-primary-soft) 100%); display: flex; align-items: center; justify-content: center;" class="mb-3">
                    <i class="fas fa-link text-white" style="font-size: 1.75rem;"></i>
                </div>
                <h5 class="fw-bold mb-1">Register another school</h5>
                <p class="csss-muted mb-3">Choose a school from the main DRRM directory that is not yet in this module.</p>
                <button type="button" class="btn btn-dark w-100 js-open-register-from-directory" data-bs-toggle="modal" data-bs-target="#registerSchoolFromDirectoryModal">
                    <i class="fas fa-school me-1"></i> Register school
                </button>
            </div>
        </div>
        @endif
    </div>
@endif

<script>
document.querySelectorAll('.school-card-item').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-8px) scale(1.01)';
        this.style.boxShadow = '0 16px 36px rgba(92, 64, 51, 0.2)';
    });
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = '0 0.125rem 0.25rem rgba(0, 0, 0, 0.075)';
    });
});

const addSchoolCard = document.getElementById('addSchoolCard');
if (addSchoolCard) {
    addSchoolCard.addEventListener('click', function(ev) {
        if (ev.target.closest('.js-open-register-from-directory')) {
            return;
        }
        ev.preventDefault();
        if (typeof bootstrap === 'undefined') return;
        const m = document.getElementById('registerSchoolFromDirectoryModal');
        if (m) new bootstrap.Modal(m).show();
    });
}

document.querySelectorAll('.js-open-register-from-directory').forEach(button => {
    button.addEventListener('click', function(event) {
        event.stopPropagation();
    });
});
</script>

@endsection
