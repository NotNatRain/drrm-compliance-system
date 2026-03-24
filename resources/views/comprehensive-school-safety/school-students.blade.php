@extends('comprehensive-school-safety.layouts.app')
@section('activeMenu', 'students')

@section('content')
<h2 class="csss-section-title mb-2">Student Records</h2>
<p class="csss-muted mb-4">{{ $students->count() }} student{{ $students->count() !== 1 ? 's' : '' }} on record</p>

<div style="margin-bottom: 1rem;">
    <button type="button" class="btn" style="background: linear-gradient(135deg, var(--csss-primary) 0%, var(--csss-primary-soft) 100%); color: white;" data-bs-toggle="modal" data-bs-target="#addStudentModal">
        <i class="fas fa-plus me-2"></i> Add Student
    </button>
</div>

<!-- Students Table or Empty State -->
<div class="csss-card p-4">
    @if($students->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-users" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                            <h5 class="text-muted mb-3">No Students Found</h5>
                            <p class="text-muted mb-4">No student records have been added for this school yet.</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                                <i class="fas fa-plus"></i> Add First Student
                            </button>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                <tr style="background: #f8f9fa;">
                                    <th class="text-muted small">Student Name</th>
                                    <th class="text-muted small">Grade/Level</th>
                                    <th class="text-muted small">Status</th>
                                    <th class="text-muted small">Date Enrolled</th>
                                    <th class="text-muted small text-end">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($students as $student)
                                    <tr>
                                        <td class="fw-500">{{ $student->name ?? 'Student' }}</td>
                                        <td>{{ $student->grade_level ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-success">Active</span>
                                        </td>
                                        <td class="text-muted small">{{ $student->created_at->format('M d, Y') }}</td>
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

                        @if($students->hasPages())
                            <div class="mt-4">
                                {{ $students->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    @endif
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" role="dialog" aria-labelledby="addStudentLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0" style="border-radius: 14px;">
            <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, var(--csss-primary) 0%, var(--csss-primary-soft) 100%); border-radius: 14px 14px 0 0; color: white;">
                <h5 class="modal-title fw-bold" id="addStudentLabel">Add Student</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('comprehensive-school-safety.school.students', $school->id) }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-600">Student Name</label>
                        <input type="text" name="student_name" class="form-control @error('student_name') is-invalid @enderror" required placeholder="Enter student name">
                        @error('student_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-600">Grade/Level</label>
                        <select name="grade_level" class="form-select @error('grade_level') is-invalid @enderror">
                            <option selected>Select grade level...</option>
                            <option value="Kindergarten">Kindergarten</option>
                            <option value="Grade 1">Grade 1</option>
                            <option value="Grade 2">Grade 2</option>
                            <option value="Grade 3">Grade 3</option>
                            <option value="Grade 4">Grade 4</option>
                            <option value="Grade 5">Grade 5</option>
                            <option value="Grade 6">Grade 6</option>
                            <option value="Grade 7">Grade 7</option>
                            <option value="Grade 8">Grade 8</option>
                            <option value="Grade 9">Grade 9</option>
                            <option value="Grade 10">Grade 10</option>
                            <option value="Grade 11">Grade 11</option>
                            <option value="Grade 12">Grade 12</option>
                        </select>
                        @error('grade_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-600">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="graduated">Graduated</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background: linear-gradient(135deg, var(--csss-primary) 0%, var(--csss-primary-soft) 100%); color: white;">
                        <i class="fas fa-save me-2"></i> Save Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
