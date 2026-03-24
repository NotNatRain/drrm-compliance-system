@extends('comprehensive-school-safety.layouts.app')
@section('activeMenu', 'assessments')

@section('content')
<h2 class="csss-section-title mb-4">New Safety Assessment</h2>
<p class="csss-muted mb-4">Complete the checklist below for the school facility.</p>

<form method="POST" action="{{ route('comprehensive-school-safety.school.assessments', $school->id) }}" class="position-relative">
    @csrf

    <!-- General Information Section -->
    <div class="csss-card p-4 mb-4">
        <h5 class="fw-bold mb-3">General Information</h5>
        <p class="csss-muted small mb-3">Complete the checklist below for the school facility.</p>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label fw-600">School Name</label>
                <input type="text" class="form-control" value="{{ $school->name }}" disabled>
                <input type="hidden" name="school_name" value="{{ $school->name }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600">Date Visited</label>
                <input type="date" name="date_visited" class="form-control @error('date_visited') is-invalid @enderror" required>
                @error('date_visited')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600">Assessed By</label>
                <input type="text" name="assessed_by" class="form-control @error('assessed_by') is-invalid @enderror" placeholder="Full Name of Assessor" required>
                @error('assessed_by')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Enabling Environment Section -->
    <div class="csss-card p-4 mb-4">
        <h5 class="fw-bold mb-3">Enabling Environment</h5>
        <p class="csss-muted small mb-3">Pillar Compliance Checklist</p>

        @foreach($enablingEnvironmentItems as $index => $item)
            <div class="assessment-item mb-4">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <strong class="text-primary flex-shrink-0">{{ $index + 1 }}</strong>
                    <span>{{ $item }}</span>
                </div>
                <div class="ps-5 d-flex gap-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="enabling_{{ $index }}" value="yes" id="enabling_yes_{{ $index }}">
                        <label class="form-check-label" for="enabling_yes_{{ $index }}">Yes</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="enabling_{{ $index }}" value="no" id="enabling_no_{{ $index }}">
                        <label class="form-check-label" for="enabling_no_{{ $index }}">No</label>
                    </div>
                </div>
                <textarea name="enabling_remarks_{{ $index }}" class="form-control form-control-sm ps-5" placeholder="Add remarks/observations..." rows="2"></textarea>
            </div>
        @endforeach
    </div>

    <!-- Safe Learning Facilities Section -->
    <div class="csss-card p-4 mb-4">
        <h5 class="fw-bold mb-3">Safe Learning Facilities</h5>
        <p class="csss-muted small mb-3">Pillar Compliance Checklist</p>

        @foreach($safeLearningFacilityItems as $index => $item)
            <div class="assessment-item mb-4">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <strong class="text-primary flex-shrink-0">{{ $index + 1 }}</strong>
                    <span>{{ $item }}</span>
                </div>
                <div class="ps-5 d-flex gap-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="facility_{{ $index }}" value="yes" id="facility_yes_{{ $index }}">
                        <label class="form-check-label" for="facility_yes_{{ $index }}">Yes</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="facility_{{ $index }}" value="no" id="facility_no_{{ $index }}">
                        <label class="form-check-label" for="facility_no_{{ $index }}">No</label>
                    </div>
                </div>
                <textarea name="facility_remarks_{{ $index }}" class="form-control form-control-sm ps-5" placeholder="Add remarks/observations..." rows="2"></textarea>
            </div>
        @endforeach
    </div>

    <!-- Disaster Risk Management Section -->
    <div class="csss-card p-4 mb-4">
        <h5 class="fw-bold mb-3">Disaster Risk Management</h5>
        <p class="csss-muted small mb-3">Pillar Compliance Checklist</p>

        @foreach($drmItems as $index => $item)
            <div class="assessment-item mb-4">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <strong class="text-primary flex-shrink-0">{{ $index + 1 }}</strong>
                    <span>{{ $item }}</span>
                </div>
                <div class="ps-5 d-flex gap-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="drm_{{ $index }}" value="yes" id="drm_yes_{{ $index }}">
                        <label class="form-check-label" for="drm_yes_{{ $index }}">Yes</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="drm_{{ $index }}" value="no" id="drm_no_{{ $index }}">
                        <label class="form-check-label" for="drm_no_{{ $index }}">No</label>
                    </div>
                </div>
                <textarea name="drm_remarks_{{ $index }}" class="form-control form-control-sm ps-5" placeholder="Add remarks/observations..." rows="2"></textarea>
            </div>
        @endforeach
    </div>

    <!-- Action Buttons -->
    <div class="csss-card p-4 d-flex gap-2 sticky-bottom">
        <button type="submit" class="btn" style="background: linear-gradient(135deg, var(--csss-primary) 0%, var(--csss-primary-soft) 100%); color: white;">
            <i class="fas fa-save me-2"></i> Save Assessment
        </button>
        <a href="{{ route('comprehensive-school-safety.school.assessments', $school->id) }}" class="btn btn-outline-secondary">
            <i class="fas fa-times me-2"></i> Cancel
        </a>
    </div>
</form>

<style>
    .assessment-item {
        padding: 1rem;
        background: #f9f7f4;
        border-radius: 8px;
    }

    .assessment-item strong {
        background: linear-gradient(135deg, var(--csss-primary) 0%, var(--csss-primary-soft) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .sticky-bottom {
        position: sticky;
        bottom: 1.5rem;
        box-shadow: 0 -8px 20px rgba(92, 64, 51, 0.12);
    }
</style>
@endsection
