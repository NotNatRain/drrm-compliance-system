@extends('layouts.app')

@push('styles')
<style>
    :root {
        --drill-orange: #FF6F00;
        --drill-orange-hover: #E65100;
        --drill-orange-light: #FFF3E0;
        --drill-orange-muted: #FB8C00;
    }
    .text-drill-orange { color: var(--drill-orange) !important; }
    .drill-orange-border { border-left: 0.25rem solid var(--drill-orange) !important; }
    .drill-orange-border-top { border-top: 4px solid var(--drill-orange) !important; }
    
    .btn-drill-orange { 
        background-color: var(--drill-orange); 
        border-color: var(--drill-orange); 
        color: white; 
    }
    .btn-drill-orange:hover { 
        background-color: var(--drill-orange-hover); 
        border-color: var(--drill-orange-hover); 
        color: white;
    }
    .drill-table-header {
        background-color: var(--drill-orange-light);
        color: var(--drill-orange-hover);
    }
    .card-drill-header {
        border-bottom: 2px solid var(--drill-orange-light);
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="h4 mb-0 text-gray-800"><i class="fas fa-bell text-drill-orange me-2"></i>Drill Monitoring Dashboard: {{ $activeSchool->school_name }}</h2>
        <div class="d-flex gap-2">
            <form action="{{ route('drill-monitoring.dashboard') }}" method="GET" class="d-flex gap-2">
                <select name="school_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ $activeSchool->id == $school->id ? 'selected' : '' }}>
                            {{ $school->school_name }}
                        </option>
                    @endforeach
                </select>
            </form>
            <button class="btn btn-drill-orange btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#logMonitoringModal">
                <i class="fas fa-plus"></i> Log New Drill Monitoring
            </button>
            <div class="modal fade" id="logMonitoringModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Log New Drill Monitoring</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('drill-monitoring.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="unified_school_id" value="{{ $activeSchool->id }}">
                                <div class="mb-3">
                                    <label for="monitoring_date" class="form-label">Monitoring Date</label>
                                    <input type="date" class="form-control" id="monitoring_date" name="monitoring_date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="monitoring_time" class="form-label">Monitoring Time</label>
                                    <input type="time" class="form-control" id="monitoring_time" name="monitoring_time" required>
                                </div>
                                <div class="mb-3">
                                    <label for="drill_type" class="form-label">Drill Type</label>
                                    <select class="form-select" id="drill_type" name="drill_type" required>
                                        <option value="Fire">Fire</option>
                                        <option value="Earthquake">Earthquake</option>
                                        <option value="Flood">Flood</option>
                                        <option value="Typhoon">Typhoon</option>
                                        <option value="Bomb Threat">Bomb Threat</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="no_of_students" class="form-label">Number of Students</label>
                                    <input type="number" class="form-control" id="no_of_students" name="no_of_students" required>
                                </div>
                                <div class="mb-3">
                                    <label for="no_of_personnel" class="form-label">Number of Personnel</label>
                                    <input type="number" class="form-control" id="no_of_personnel" name="no_of_personnel" required>
                                </div>
                                <div class="mb-3">
                                    <label for="monitored_by" class="form-label">Monitored By</label>
                                    <input type="text" class="form-control" id="monitored_by" name="monitored_by" required>
                                </div>
                                <div class="mb-3">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card drill-orange-border shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-drill-orange text-uppercase mb-1">Total Drills Monitored</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_monitored'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Average Participants</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['avg_participants'], 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Historical Monitoring Table --}}
    <div class="card shadow mb-4 border-0">
        <div class="card-header py-3 d-flex justify-content-between bg-white card-drill-header">
            <h6 class="m-0 font-weight-bold text-drill-orange">Recent Drill Monitoring Records (Newest to Oldest)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" width="100%" cellspacing="0">
                    <thead class="drill-table-header">
                        <tr>
                            <th>Date</th>
                            <th>Drill Type</th>
                            <th>Time</th>
                            <th>Participants</th>
                            <th>Monitored By</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($monitorings as $log)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($log->monitoring_date)->format('M d, Y') }}</td>
                            <td>{{ $log->drill_type }}</td>
                            <td>{{ $log->monitoring_time }}</td>
                            <td>{{ $log->no_of_students + $log->no_of_personnel }}</td>
                            <td>{{ $log->monitored_by }}</td>
                            <td><span class="badge bg-{{ $log->status == 'Completed' ? 'success' : 'warning' }}">{{ $log->status }}</span></td>
                            <td>
                                <button class="btn btn-outline-dark btn-sm view-details" data-id="{{ $log->id }}">Details</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No monitoring records found for this school.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $monitorings->links() }}
            </div>
        </div>
    </div>

    {{-- Upcoming Scheduled Drills Table --}}
    <div class="card shadow mb-4 drill-orange-border-top">
        <div class="card-header py-3 bg-white card-drill-header">
            <h6 class="m-0 font-weight-bold text-drill-orange"><i class="fas fa-calendar-alt me-2"></i>Upcoming Scheduled Inspections</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Scheduled Date</th>
                            <th>Drill Type</th>
                            <th>Expected Participants</th>
                            <th>Coordinator</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upcomingDrills as $upcoming)
                        <tr>
                            <td class="fw-bold">{{ \Carbon\Carbon::parse($upcoming->scheduled_date)->format('F d, Y') }}</td>
                            <td>{{ $upcoming->drill_type }}</td>
                            <td>{{ $upcoming->participants_count ?? 'TBD' }}</td>
                            <td>{{ $upcoming->coordinator }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-3 text-muted">No upcoming drills scheduled.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
