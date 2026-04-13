@extends('comprehensive-school-safety.layouts.app')
@section('activeMenu', 'reports')

@push('styles')
<style>
    .lively-report-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .lively-report-card .lively-report-icon {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .lively-report-card:hover {
        transform: translateY(-10px) scale(1.015);
        box-shadow: 0 20px 44px rgba(52, 39, 31, 0.2);
    }

    .lively-report-card:hover .lively-report-icon {
        transform: translateY(-2px) scale(1.08) rotate(-4deg);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.18);
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #e91e63 0%, #f06292 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-chart-pie text-white" style="font-size: 1.5rem;"></i>
        </div>
        <div>
            <h2 class="csss-section-title mb-1">Analytics & Reports</h2>
            <p class="csss-muted mb-0">{{ $school->name }}</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="csss-card p-4 lively-report-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <p class="csss-muted small mb-1">Assessments Completed</p>
                    <h3 class="fw-bold m-0">{{ $reportStats['assessments_completed'] }}</h3>
                </div>
                <div class="lively-report-icon" style="width: 45px; height: 45px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check-circle text-white" style="font-size: 1.5rem;"></i>
                </div>
            </div>
            <small class="csss-muted">of {{ $reportStats['total_assessments'] }} total</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="csss-card p-4 lively-report-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <p class="csss-muted small mb-1">Total Students</p>
                    <h3 class="fw-bold m-0">{{ $reportStats['total_students'] }}</h3>
                </div>
                <div class="lively-report-icon" style="width: 45px; height: 45px; background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-users text-white" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="csss-card p-4 lively-report-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <p class="csss-muted small mb-1">School Facilities</p>
                    <h3 class="fw-bold m-0">{{ $reportStats['total_facilities'] }}</h3>
                </div>
                <div class="lively-report-icon" style="width: 45px; height: 45px; background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-building text-white" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="csss-card p-4 lively-report-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <p class="csss-muted small mb-1">Total Assessments</p>
                    <h3 class="fw-bold m-0">{{ $reportStats['total_assessments'] }}</h3>
                </div>
                <div class="lively-report-icon" style="width: 45px; height: 45px; background: linear-gradient(135deg, #6f42c1 0%, #9b6ad1 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-chart-bar text-white" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="csss-card p-4 lively-report-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <p class="csss-muted small mb-1">Storage Items</p>
                    <h3 class="fw-bold m-0">{{ $reportStats['total_storage_items'] ?? 0 }}</h3>
                </div>
                <div class="lively-report-icon" style="width: 45px; height: 45px; background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-boxes-stacked text-white" style="font-size: 1.4rem;"></i>
                </div>
            </div>
            <a href="{{ route('comprehensive-school-safety.school.storage', $school->id) }}" class="btn btn-sm btn-outline-dark">
                <i class="fas fa-arrow-right me-1"></i> Open Storage
            </a>
        </div>
    </div>
</div>

<!-- Reports Section -->
<div class="csss-card p-4">
    <h5 class="fw-bold mb-4">Available Reports</h5>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card border-0" style="border-radius: 12px; background: linear-gradient(135deg, rgba(92, 64, 51, 0.05) 0%, rgba(139, 111, 71, 0.05) 100%); border-left: 4px solid var(--csss-primary);">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-2">
                        <i class="fas fa-file-pdf" style="color: var(--csss-primary);"></i> Assessment Report
                    </h6>
                    <p class="text-muted small mb-3">Comprehensive assessment compliance summary for this school.</p>
                    <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#printAssessmentModal">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0" style="border-radius: 12px; background: linear-gradient(135deg, rgba(92, 64, 51, 0.05) 0%, rgba(139, 111, 71, 0.05) 100%); border-left: 4px solid var(--csss-primary);">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-2">
                        <i class="fas fa-chart-pie" style="color: var(--csss-primary);"></i> Safety Index
                    </h6>
                    <p class="text-muted small mb-3">Current safety compliance index and rating for the school.</p>
                    <a href="{{ route('comprehensive-school-safety.school.reports.safety-index-print', $school->id) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                        <i class="fas fa-print"></i> Print
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0" style="border-radius: 12px; background: linear-gradient(135deg, rgba(92, 64, 51, 0.05) 0%, rgba(139, 111, 71, 0.05) 100%); border-left: 4px solid var(--csss-primary);">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-2">
                        <i class="fas fa-calendar-alt" style="color: var(--csss-primary);"></i> Timeline
                    </h6>
                    <p class="text-muted small mb-3">Assessment history and timeline of changes over time.</p>
                    <a href="{{ route('comprehensive-school-safety.school.reports.timeline-print', $school->id) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                        <i class="fas fa-print"></i> Print
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="printAssessmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-print me-2"></i>Print Assessment Report</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label fw-bold">Select Assessment *</label>
                <select id="assessmentPrintSelect" class="form-select">
                    <option value="">-- Select Assessment --</option>
                    @foreach($assessments as $assessment)
                        <option value="{{ $assessment->id }}">
                            CSSS-{{ str_pad((string) $assessment->id, 4, '0', STR_PAD_LEFT) }} - {{ $assessment->date_visited ? \Carbon\Carbon::parse($assessment->date_visited)->format('M d, Y') : 'No date' }}
                        </option>
                    @endforeach
                </select>
                <p class="small text-muted mt-2 mb-0">Choose one assessment to print with full criteria/questionnaire rows.</p>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-dark" onclick="openAssessmentPrint()">Print</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openAssessmentPrint() {
    const select = document.getElementById('assessmentPrintSelect');
    if (!select || !select.value) {
        alert('Please select an assessment first.');
        return;
    }

    const url = "{{ route('comprehensive-school-safety.school.reports.assessment-print', $school->id) }}" + '?assessment_id=' + encodeURIComponent(select.value);
    window.open(url, '_blank');
}
</script>
@endpush
@endsection
