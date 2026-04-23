@extends('comprehensive-school-safety.layouts.app')
@section('activeMenu', 'reports')

@push('styles')
<style>
    .lively-report-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        border-radius: 14px;
        border: 1px solid rgba(92, 64, 51, 0.08);
        background: linear-gradient(180deg, #fff 0%, #fffdfc 100%);
    }

    .lively-report-card .lively-report-icon {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .lively-report-card:hover {
        transform: translateY(-6px) scale(1.01);
        box-shadow: 0 16px 32px rgba(52, 39, 31, 0.16);
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
    <div class="col-md-4">
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

    <div class="col-md-4">
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

    <div class="col-md-4">
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
                    <div class="text-muted small mb-3">
                        <div><strong>Academic Year:</strong> {{ $currentAcademicYear ?? 'N/A' }}</div>
                        <div><strong>Latest Assessment:</strong> {{ $assessmentPreview['latest_code'] ?? 'N/A' }} ({{ $assessmentPreview['latest_date'] ?? 'N/A' }})</div>
                        <div><strong>Average Score:</strong> {{ $assessmentPreview['average_score'] ?? 0 }}</div>
                        <div><strong>Compliance Rate:</strong> {{ $assessmentPreview['compliance_rate'] ?? 0 }}% from {{ $assessmentPreview['total_reviewed'] ?? 0 }} reviewed checklist rows</div>
                    </div>
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
                    <div class="text-muted small mb-3">
                        <div><strong>Average Building Score:</strong> {{ $safetyIndexPreview['average_score'] ?? 0 }}</div>
                        <div><strong>Building Coverage:</strong> {{ $safetyIndexPreview['building_count'] ?? 0 }} buildings</div>
                        <div><strong>Priority Findings:</strong> High {{ $safetyIndexPreview['high_findings'] ?? 0 }}, Medium {{ $safetyIndexPreview['medium_findings'] ?? 0 }}, Low {{ $safetyIndexPreview['low_findings'] ?? 0 }}</div>
                    </div>
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
                        <i class="fas fa-archive" style="color: var(--csss-primary);"></i> Archives
                    </h6>
                    <div class="text-muted small mb-3">
                        <div><strong>Includes:</strong> Assessment History & Facility Records</div>
                        <div><strong>Total Snapshots:</strong> {{ ($archives ?? collect())->count() }}</div>
                        <div><strong>Latest Academic Year:</strong> {{ ($archives ?? collect())->first()->academic_year ?? 'N/A' }}</div>
                    </div>
                    <a href="{{ route('comprehensive-school-safety.school.reports.timeline-print', $school->id) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                        <i class="fas fa-print"></i> Print Archives
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="csss-card p-4 mt-4">
    <h5 class="fw-bold mb-3">Archive Records</h5>
    @if(($archives ?? collect())->isEmpty())
        <p class="text-muted mb-0">No archive snapshots yet.</p>
    @else
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr>
                        <th>Academic Year</th>
                        <th>Record Type</th>
                        <th>Snapshot Details</th>
                        <th>Archived At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(($archives ?? collect()) as $archive)
                        @php
                            $payload = is_array($archive->payload) ? $archive->payload : [];
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $archive->academic_year }}</td>
                            <td>{{ $archive->archive_type === 'facility' ? 'Facility Records' : 'Assessment History' }}</td>
                            <td>
                                @if($archive->archive_type === 'facility')
                                    Facilities captured: {{ (int) ($payload['facility_count'] ?? 0) }}
                                @else
                                    Assessments: {{ (int) ($payload['assessment_count'] ?? 0) }}, Avg Score: {{ (float) ($payload['average_score'] ?? 0) }}
                                @endif
                            </td>
                            <td>{{ $archive->archived_at ? $archive->archived_at->format('M d, Y h:i A') : 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
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
