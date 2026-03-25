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
            <p class="csss-muted mb-0">{{ $assessments->count() }} assessment{{ $assessments->count() !== 1 ? 's' : '' }} on record</p>
        </div>
    </div>
</div>

<div style="margin-bottom: 1rem;">
    <a href="{{ route('comprehensive-school-safety.school.assessments.new', $school->id) }}" class="btn" style="background: linear-gradient(135deg, var(--csss-primary) 0%, var(--csss-primary-soft) 100%); color: white;">
        <i class="fas fa-plus me-2"></i> New Safety Assessment
    </a>
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
                                        <td class="fw-500">{{ $assessment->title ?? 'Assessment' }}</td>
                                        <td>{{ $assessment->total_score ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-success">{{ $assessment->status ?? 'Active' }}</span>
                                        </td>
                                        <td class="text-muted small">{{ $assessment->created_at->format('M d, Y') }}</td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-secondary" title="View" disabled>
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary" title="Edit" disabled>
                                                    <i class="fas fa-edit"></i>
                                                </button>
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

@endsection
