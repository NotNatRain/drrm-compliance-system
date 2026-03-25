@extends('comprehensive-school-safety.layouts.app')
@section('activeMenu', 'dashboard')

@push('styles')
<style>
    .lively-kpi-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .lively-kpi-card .lively-kpi-icon {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .lively-kpi-card:hover {
        transform: translateY(-10px) scale(1.015);
        box-shadow: 0 20px 44px rgba(52, 39, 31, 0.2);
    }

    .lively-kpi-card:hover .lively-kpi-icon {
        transform: translateY(-2px) scale(1.08) rotate(-4deg);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.18);
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--csss-primary) 0%, var(--csss-primary-soft) 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-chart-line text-white" style="font-size: 1.5rem;"></i>
        </div>
        <div>
            <h2 class="csss-section-title mb-1">Dashboard</h2>
            <p class="csss-muted mb-0">School Safety Overview & Key Metrics</p>
        </div>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-5">
    <div class="col-md-3">
        <div class="csss-card p-4 lively-kpi-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="csss-muted small mb-2">Assessments Completed</p>
                    <h3 class="fw-bold mb-0">{{ $school->assessments()->count() }}</h3>
                </div>
                <div class="lively-kpi-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--csss-primary) 0%, var(--csss-primary-soft) 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check-circle text-white" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="csss-card p-4 lively-kpi-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="csss-muted small mb-2">Total Students</p>
                    <h3 class="fw-bold mb-0">{{ $school->students()->count() }}</h3>
                </div>
                <div class="lively-kpi-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #0066cc 0%, #0099ff 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-users text-white" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="csss-card p-4 lively-kpi-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="csss-muted small mb-2">Facilities</p>
                    <h3 class="fw-bold mb-0">{{ $school->facilities()->count() }}</h3>
                </div>
                <div class="lively-kpi-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #ff9800 0%, #ffb74d 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-building text-white" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="csss-card p-4 lively-kpi-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="csss-muted small mb-2">School ID</p>
                    <h6 class="fw-bold mb-0">{{ $school->school_id_number ?? 'Manual' }}</h6>
                </div>
                <div class="lively-kpi-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #6c757d 0%, #9ca3af 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-id-card text-white" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- School Information -->
<div class="row g-3 mb-5">
    <div class="col-12">
        <div class="csss-card p-4">
            <h5 class="fw-bold mb-4">School Information</h5>
            @if(auth()->user()->role === 'admin')
                <p class="csss-muted mb-3">Select a school to set it as your active context across Assessments, Students, Facilities, and Reports.</p>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--csss-border);">
                                <th class="fw-bold" style="color: var(--csss-primary);">School Name</th>
                                <th class="fw-bold" style="color: var(--csss-primary);">Division</th>
                                <th class="fw-bold" style="color: var(--csss-primary);">District</th>
                                <th class="fw-bold" style="color: var(--csss-primary);">Current</th>
                                <th class="fw-bold text-end" style="color: var(--csss-primary);">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allSchools as $schoolItem)
                                <tr>
                                    <td class="fw-semibold">{{ $schoolItem->name }}</td>
                                    <td>{{ $schoolItem->division ?? 'N/A' }}</td>
                                    <td>{{ $schoolItem->district ?? 'N/A' }}</td>
                                    <td>
                                        @if((int)$schoolItem->id === (int)$school->id)
                                            <span class="badge bg-success-subtle text-success">Active</span>
                                        @else
                                            <span class="badge bg-light text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('comprehensive-school-safety.school.dashboard', $schoolItem->id) }}" class="btn btn-sm btn-outline-dark">
                                            Use This School
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center csss-muted py-4">No schools found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p class="csss-muted small mb-1">School Name</p>
                        <p class="fw-bold">{{ $school->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="csss-muted small mb-1">School Type</p>
                        <p class="fw-bold">{{ $school->school_type ?? 'Not specified' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <p class="csss-muted small mb-1">Region</p>
                        <p class="fw-bold">{{ $school->region ?? 'Not specified' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="csss-muted small mb-1">Division</p>
                        <p class="fw-bold">{{ $school->division ?? 'Not specified' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <p class="csss-muted small mb-1">District</p>
                        <p class="fw-bold">{{ $school->district ?? 'Not specified' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="csss-muted small mb-1">Contact</p>
                        <p class="fw-bold">{{ $school->contact_person ?? 'Not specified' }}</p>
                    </div>
                </div>

                @if($school->address)
                    <div class="row">
                        <div class="col-12">
                            <p class="csss-muted small mb-1">Address</p>
                            <p class="fw-bold">{{ $school->address }}</p>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

<!-- Recent Assessments -->
<div class="csss-card p-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Recent Assessments</h5>
        <a href="{{ route('comprehensive-school-safety.school.assessments', $school->id) }}" class="text-decoration-none" style="color: var(--csss-primary); font-size: 0.9rem;">
            View All <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    @if($school->assessments()->exists())
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr style="border-bottom: 2px solid var(--csss-border);">
                        <th class="fw-bold" style="color: var(--csss-primary);">Date Assessed</th>
                        <th class="fw-bold" style="color: var(--csss-primary);">Assessed By</th>
                        <th class="fw-bold" style="color: var(--csss-primary);">Status</th>
                        <th class="fw-bold text-center" style="color: var(--csss-primary);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($school->assessments()->latest()->take(5)->get() as $assessment)
                        <tr style="border-bottom: 1px solid var(--csss-border);">
                            <td>{{ $assessment->date_visited ? \Carbon\Carbon::parse($assessment->date_visited)->format('M d, Y') : 'N/A' }}</td>
                            <td>{{ $assessment->assessed_by ?? 'Unknown' }}</td>
                            <td>
                                <span class="badge bg-success-subtle text-success" style="border-radius: 8px;">Completed</span>
                            </td>
                            <td class="text-center">
                                <a href="#" class="text-decoration-none" style="color: var(--csss-primary); font-size: 0.9rem;">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-inbox" style="font-size: 2rem; color: #ccc; margin-bottom: 1rem; display: block;"></i>
            <p class="csss-muted">No assessments yet</p>
            <a href="{{ route('comprehensive-school-safety.school.assessments.new', $school->id) }}" 
               class="btn btn-sm" style="background: linear-gradient(135deg, var(--csss-primary) 0%, var(--csss-primary-soft) 100%); color: white;">
                Start New Assessment
            </a>
        </div>
    @endif
</div>

<!-- Students & Facilities Summary -->
<div class="row g-3">
    <div class="col-lg-6">
        <div class="csss-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Students Summary</h5>
                <a href="{{ route('comprehensive-school-safety.school.students', $school->id) }}" class="text-decoration-none" style="color: var(--csss-primary); font-size: 0.9rem;">
                    Manage
                </a>
            </div>

            @php
                $totalStudents = $school->students()->count();
                $activeStudents = 0;
                $graduatedStudents = 0;
                $inactiveStudents = 0;
            @endphp

            @if($totalStudents > 0)
                <div class="d-flex gap-3 mb-3">
                    <div>
                        <p class="csss-muted small mb-1">Active</p>
                        <h4 class="fw-bold mb-0" style="color: var(--csss-primary);">{{ $activeStudents }}</h4>
                    </div>
                    <div>
                        <p class="csss-muted small mb-1">Graduated</p>
                        <h4 class="fw-bold mb-0" style="color: #28a745;">{{ $graduatedStudents }}</h4>
                    </div>
                    <div>
                        <p class="csss-muted small mb-1">Inactive</p>
                        <h4 class="fw-bold mb-0" style="color: #6c757d;">{{ $inactiveStudents }}</h4>
                    </div>
                </div>
                <div class="progress rounded-3" style="height: 8px; background-color: var(--csss-border);">
                    <div class="progress-bar" role="progressbar" 
                         style="background: linear-gradient(135deg, var(--csss-primary) 0%, var(--csss-primary-soft) 100%); width: {{ ($activeStudents / $totalStudents * 100) }}%;">
                    </div>
                </div>
            @else
                <p class="csss-muted text-center py-3">No students registered yet</p>
            @endif
        </div>
    </div>

    <div class="col-lg-6">
        <div class="csss-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Facilities Summary</h5>
                <a href="{{ route('comprehensive-school-safety.school.facilities', $school->id) }}" class="text-decoration-none" style="color: var(--csss-primary); font-size: 0.9rem;">
                    Manage
                </a>
            </div>

            @php
                $goodFacilities = $school->facilities()->where('condition', 'Good')->count();
                $fairFacilities = $school->facilities()->where('condition', 'Fair')->count();
                $poorFacilities = $school->facilities()->where('condition', 'Poor')->count();
                $totalFacilities = $school->facilities()->count();
            @endphp

            @if($totalFacilities > 0)
                <div class="d-flex gap-3 mb-3">
                    <div>
                        <p class="csss-muted small mb-1">Good</p>
                        <h4 class="fw-bold mb-0" style="color: #28a745;">{{ $goodFacilities }}</h4>
                    </div>
                    <div>
                        <p class="csss-muted small mb-1">Fair</p>
                        <h4 class="fw-bold mb-0" style="color: #ff9800;">{{ $fairFacilities }}</h4>
                    </div>
                    <div>
                        <p class="csss-muted small mb-1">Poor</p>
                        <h4 class="fw-bold mb-0" style="color: #dc3545;">{{ $poorFacilities }}</h4>
                    </div>
                </div>
                <div class="progress rounded-3" style="height: 8px; background-color: var(--csss-border);">
                    <div class="progress-bar" role="progressbar" 
                         style="background: linear-gradient(135deg, #28a745 0%, #51cf66 100%); width: {{ ($goodFacilities / $totalFacilities * 100) }}%;">
                    </div>
                </div>
            @else
                <p class="csss-muted text-center py-3">No facilities registered yet</p>
            @endif
        </div>
    </div>
</div>

@endsection
