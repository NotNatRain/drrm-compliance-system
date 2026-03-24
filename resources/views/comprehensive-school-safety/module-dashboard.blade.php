@extends('comprehensive-school-safety.layouts.app')
@section('activeMenu', 'dashboard')

@section('content')
<h2 class="csss-section-title mb-1">Schools Directory</h2>
<p class="csss-muted mb-4">Manage and monitor school safety across your district</p>

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
                    <p class="csss-muted small mb-2">Total Schools</p>
                    <h3 class="fw-bold mb-0">{{ $stats['total_schools'] ?? 0 }}</h3>
                </div>
                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--csss-primary) 0%, var(--csss-primary-soft) 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-school text-white" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="csss-card p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="csss-muted small mb-2">From Fire Safety</p>
                    <h3 class="fw-bold mb-0">{{ $stats['registered_from_fire_safety'] ?? 0 }}</h3>
                </div>
                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #dc3545 0%, #ff6b6b 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-fire text-white" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="csss-card p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="csss-muted small mb-2">Manually Created</p>
                    <h3 class="fw-bold mb-0">{{ $stats['manually_created'] ?? 0 }}</h3>
                </div>
                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #28a745 0%, #51cf66 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-pencil-alt text-white" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schools Directory -->
<h4 class="fw-bold mb-3">Schools Directory</h4>

@if($recentSchools->isEmpty())
    <div class="csss-card p-5 text-center">
        <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
        <h5 class="csss-muted">No Schools Found</h5>
        <p class="csss-muted mb-3">Start by creating a new school record or registering schools from Fire Safety.</p>
        <div class="d-flex gap-2 justify-content-center">
            <a href="{{ route('comprehensive-school-safety.schools.create') }}" class="btn" style="background: linear-gradient(135deg, var(--csss-primary) 0%, var(--csss-primary-soft) 100%); color: white;">
                <i class="fas fa-plus"></i> Create New School
            </a>
            <a href="{{ route('comprehensive-school-safety.schools.register-existing') }}" class="btn btn-outline-dark">
                <i class="fas fa-link"></i> Register from Fire Safety
            </a>
        </div>
    </div>
@else
    <div class="row g-3" id="schoolsContainer">
        @foreach($recentSchools as $school)
            <div class="col-md-6 col-lg-4 school-card" data-school-name="{{ strtolower($school->name) }}">
                <div class="csss-card p-4 h-100 school-card-item" style="cursor: pointer;" onclick="window.location.href='{{ route('comprehensive-school-safety.school.dashboard', $school->id) }}'">
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
    </div>
@endif

<script>
document.querySelectorAll('.school-card-item').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-4px)';
        this.style.boxShadow = '0 10px 30px rgba(92, 64, 51, 0.15)';
    });
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = '0 0.125rem 0.25rem rgba(0, 0, 0, 0.075)';
    });
});
</script>

@endsection
