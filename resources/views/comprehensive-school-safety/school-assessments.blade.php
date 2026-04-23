@extends('comprehensive-school-safety.layouts.app')
@section('activeMenu', 'assessments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #9c27b0 0%, #ce93d8 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-clipboard-check text-white" style="font-size: 1.5rem;"></i>
        </div>
        <div>
            <h2 class="csss-section-title mb-1">Assessments</h2>
            <p class="csss-muted mb-0">Academic Year {{ $currentAcademicYear ?? 'N/A' }}: {{ $assessments->count() }} assessment{{ $assessments->count() !== 1 ? 's' : '' }} on record</p>
        </div>
    </div>
</div>

<div class="alert alert-info mb-4" role="alert">
    <i class="fas fa-archive me-2"></i>
    Assessments automatically reset to blank every new academic year. Previous years are preserved in the archive history below.
</div>

<div style="margin-bottom: 1rem;">
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('comprehensive-school-safety.school.assessments.new', $school->id) }}" class="btn" style="background: linear-gradient(135deg, var(--csss-primary) 0%, var(--csss-primary-soft) 100%); color: white;">
            <i class="fas fa-plus me-2"></i> New Safety Assessment
        </a>
        @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('comprehensive-school-safety.school.assessments.questionnaire.edit', $school->id) }}" class="btn btn-outline-dark">
                <i class="fas fa-pen-ruler me-2"></i> Edit Assessment Questionnaires
            </a>
        @endif
    </div>
</div>

<!-- Assessments Table or Empty State -->
<div class="csss-card p-4">
    @if($assessments->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-check" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                            <h5 class="text-muted mb-3">No Assessments Found</h5>
                            <p class="text-muted mb-4">No assessments have been created for this school yet.</p>
                            <a href="{{ route('comprehensive-school-safety.school.assessments.new', $school->id) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create First Assessment
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                <tr style="background: #f8f9fa;">
                                    <th class="text-muted small">Assessment Title</th>
                                    <th class="text-muted small">Score</th>
                                    <th class="text-muted small">Status</th>
                                    <th class="text-muted small">Date Created</th>
                                    <th class="text-muted small text-end">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($assessments as $assessment)
                                    <tr>
                                        <td class="fw-500">{{ $assessment->assessment_code ?? ('ASMT-' . str_pad($assessment->id, 3, '0', STR_PAD_LEFT)) }}</td>
                                        <td>{{ $assessment->total_score ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-success">{{ $assessment->status ?? 'Active' }}</span>
                                        </td>
                                        <td class="text-muted small">{{ $assessment->created_at->format('M d, Y') }}</td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('comprehensive-school-safety.school.assessments.view', [$school->id, $assessment->id]) }}" class="btn btn-outline-secondary" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('comprehensive-school-safety.school.assessments.edit', [$school->id, $assessment->id]) }}" class="btn btn-outline-secondary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($assessments->hasPages())
                            <div class="mt-4">
                                {{ $assessments->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    @endif
</div>

<div class="csss-card p-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Assessment Archives</h5>
        <span class="badge bg-secondary">Assessment History by Academic Year</span>
    </div>

    @php
        $archiveRows = $archivedAssessmentHistory ?? collect();
    @endphp

    @if($archiveRows->isEmpty())
        <p class="text-muted mb-0">No archived assessment history yet.</p>
    @else
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr>
                        <th>Academic Year</th>
                        <th>Archived At</th>
                        <th>Assessment Count</th>
                        <th>Average Score</th>
                        <th>Latest Assessment Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($archiveRows as $archive)
                        @php
                            $payload = is_array($archive->payload) ? $archive->payload : [];
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $archive->academic_year }}</td>
                            <td>{{ $archive->archived_at ? $archive->archived_at->format('M d, Y h:i A') : 'N/A' }}</td>
                            <td>{{ (int) ($payload['assessment_count'] ?? 0) }}</td>
                            <td>{{ (float) ($payload['average_score'] ?? 0) }}</td>
                            <td>
                                @if(!empty($payload['latest_date']))
                                    {{ \Carbon\Carbon::parse($payload['latest_date'])->format('M d, Y') }}
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
