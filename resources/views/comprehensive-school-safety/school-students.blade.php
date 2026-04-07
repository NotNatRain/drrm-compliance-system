@extends('comprehensive-school-safety.layouts.app')
@section('activeMenu', 'students')

@section('content')
<div class="d-flex justify-content-between align-items-start gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #0066cc 0%, #0099ff 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-file-import text-white" style="font-size: 1.5rem;"></i>
        </div>
        <div>
            <h2 class="csss-section-title mb-1">Student List Importer</h2>
            <p class="csss-muted mb-0">Import a student list, review records, and complete missing values directly on this page.</p>
        </div>
    </div>

</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <strong>There were validation issues:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="csss-card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Student Records</h5>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-secondary">{{ $students->total() }} total</span>
            <a href="{{ route('comprehensive-school-safety.school.students.export', $school->id) }}" class="btn btn-sm btn-outline-dark">
                <i class="fas fa-file-export me-1"></i> Export Student List
            </a>
        </div>
    </div>

    @if($students->isEmpty())
        <div class="text-center py-5 border rounded-3 bg-light">
            <i class="fas fa-users text-muted" style="font-size: 2.25rem;"></i>
            <p class="text-muted mt-3 mb-0">No student records yet. Import a list to begin.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr style="background: #f8f9fa;">
                        <th class="text-muted small">Student LRN</th>
                        <th class="text-muted small">First Name</th>
                        <th class="text-muted small">Middle Name</th>
                        <th class="text-muted small">Last Name</th>
                        <th class="text-muted small">Grade Level</th>
                        <th class="text-muted small">Section</th>
                        <th class="text-muted small">Guardian Name</th>
                        <th class="text-muted small">Guardian Contact</th>
                        <th class="text-muted small text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>{{ $student->student_lrn ?: '—' }}</td>
                            <td>{{ $student->first_name ?: '—' }}</td>
                            <td>{{ $student->middle_name ?: '—' }}</td>
                            <td>{{ $student->last_name ?: '—' }}</td>
                            <td>{{ $student->grade_level ?: '—' }}</td>
                            <td>{{ $student->section ?: '—' }}</td>
                            <td>{{ $student->guardian_name ?: '—' }}</td>
                            <td>{{ $student->guardian_contact ?: '—' }}</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editStudentModal{{ $student->id }}">
                                    <i class="fas fa-pen"></i>
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="editStudentModal{{ $student->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content border-0" style="border-radius: 14px;">
                                    <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, var(--csss-primary) 0%, var(--csss-primary-soft) 100%); border-radius: 14px 14px 0 0; color: white;">
                                        <h5 class="modal-title fw-bold">Edit Student</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form method="POST" action="{{ route('comprehensive-school-safety.school.students.update', [$school->id, $student->id]) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body p-4">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Student LRN</label>
                                                    <input type="text" name="student_lrn" class="form-control" value="{{ $student->student_lrn }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Grade Level</label>
                                                    <input type="text" name="grade_level" class="form-control" value="{{ $student->grade_level }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">First Name</label>
                                                    <input type="text" name="first_name" class="form-control" value="{{ $student->first_name }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Middle Name</label>
                                                    <input type="text" name="middle_name" class="form-control" value="{{ $student->middle_name }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Last Name</label>
                                                    <input type="text" name="last_name" class="form-control" value="{{ $student->last_name }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Section</label>
                                                    <input type="text" name="section" class="form-control" value="{{ $student->section }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Guardian Name</label>
                                                    <input type="text" name="guardian_name" class="form-control" value="{{ $student->guardian_name }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Guardian Contact</label>
                                                    <input type="text" name="guardian_contact" class="form-control" value="{{ $student->guardian_contact }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-dark">
                                                <i class="fas fa-save me-1"></i> Save Changes
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
            <div class="mt-4">
                {{ $students->links('pagination::bootstrap-5') }}
            </div>
        @endif
    @endif
</div>

<div class="alert alert-warning mb-4" role="alert">
    <i class="fas fa-circle-info me-2"></i>
    Before importing, please make sure your file includes these columns: Student LRN, First Name, Middle Name, Last Name, Grade Level, Section, Guardian Name, and Guardian Contact.
    The importer will still save whatever it can read and leave unmatched data empty for manual editing.
</div>

<div class="csss-card p-4 mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-lg-12">
            <form action="{{ route('comprehensive-school-safety.school.students.import', $school->id) }}" method="POST" enctype="multipart/form-data" class="row g-2">
                @csrf
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Import student list file</label>
                    <input type="file" name="student_list_file" class="form-control" accept=".csv,.txt,.xlsx" required>
                    <small class="text-muted">Supported files: CSV, TXT, XLSX</small>
                </div>
                <div class="col-md-4 d-grid">
                    <button type="submit" class="btn btn-dark mt-md-4">
                        <i class="fas fa-upload me-1"></i> Import List
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="csss-card p-4">
    <h5 class="fw-bold mb-3">Imported File Attachments</h5>
    @if(($attachments ?? collect())->isEmpty())
        <div class="text-center py-4 border rounded-3 bg-light">
            <i class="fas fa-paperclip text-muted" style="font-size: 1.8rem;"></i>
            <p class="text-muted mt-2 mb-0">No imported files recorded yet.</p>
        </div>
    @else
        <div class="list-group">
            @foreach($attachments as $attachment)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-file me-2"></i>{{ $attachment['name'] ?? 'Imported file' }}</span>
                    <small class="text-muted">{{ $attachment['uploaded_at'] ?? '' }}</small>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
