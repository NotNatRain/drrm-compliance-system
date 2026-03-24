@extends('comprehensive-school-safety.layouts.app')
@section('activeMenu', 'facilities')

@section('content')
<h2 class="csss-section-title mb-2">School Facilities</h2>
<p class="csss-muted mb-4">{{ $facilities->count() }} facilit{{ $facilities->count() !== 1 ? 'ies' : 'y' }} on record</p>

<div style="margin-bottom: 1rem;">
    <button type="button" class="btn" style="background: linear-gradient(135deg, var(--csss-primary) 0%, var(--csss-primary-soft) 100%); color: white;" data-bs-toggle="modal" data-bs-target="#addFacilityModal">
        <i class="fas fa-plus me-2"></i> Add Facility
    </button>
</div>

<!-- Facilities Table or Empty State -->
<div class="csss-card p-4">
    @if($facilities->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-building" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                            <h5 class="text-muted mb-3">No Facilities Found</h5>
                            <p class="text-muted mb-4">No facility records have been added for this school yet.</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFacilityModal">
                                <i class="fas fa-plus"></i> Add First Facility
                            </button>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                <tr style="background: #f8f9fa;">
                                    <th class="text-muted small">Facility Name</th>
                                    <th class="text-muted small">Type</th>
                                    <th class="text-muted small">Condition</th>
                                    <th class="text-muted small">Date Added</th>
                                    <th class="text-muted small text-end">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($facilities as $facility)
                                    <tr>
                                        <td class="fw-500">{{ $facility->name ?? 'Facility' }}</td>
                                        <td>{{ $facility->type ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-success">Good</span>
                                        </td>
                                        <td class="text-muted small">{{ $facility->created_at->format('M d, Y') }}</td>
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

                        @if($facilities->hasPages())
                            <div class="mt-4">
                                {{ $facilities->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    @endif
</div>

<!-- Add Facility Modal -->
<div class="modal fade" id="addFacilityModal" tabindex="-1" role="dialog" aria-labelledby="addFacilityLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0" style="border-radius: 14px;">
            <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, var(--csss-primary) 0%, var(--csss-primary-soft) 100%); border-radius: 14px 14px 0 0; color: white;">
                <h5 class="modal-title fw-bold" id="addFacilityLabel">Add New Facility</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('comprehensive-school-safety.school.facilities', $school->id) }}">
                @csrf
                <div class="modal-body p-4">
                    <p class="csss-muted small mb-4">Record a new physical asset or location.</p>

                    <div class="mb-3">
                        <label class="form-label fw-600">Select School</label>
                        <select name="school_id" class="form-select @error('school_id') is-invalid @enderror">
                            <option value="{{ $school->id }}" selected>{{ $school->name }}</option>
                            @foreach($allSchools as $s)
                                @if($s->id !== $school->id)
