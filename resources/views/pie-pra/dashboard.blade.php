{{-- resources/views/pie-pra/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'PIE-PRA Dashboard')
@section('hide_main_nav', '1')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary border-0 p-2" title="Back to Main Dashboard">
            <i class="fas fa-arrow-left fa-lg"></i>
        </a>
        <div class="d-flex align-items-center gap-3">
            <h1 class="h4 mb-0" style="color:#1B4C6D;">
                <i class="fas fa-brain"></i> PIE-PRA: Pre-Disaster Intelligent Evacuation Predictor &amp; Resource Allocator
            </h1>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow h-100">
                <div class="card-body">
                    <h6 class="text-muted">Registered Volunteers</h6>
                    <h2 class="mb-0">{{ $volunteerCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow h-100">
                <div class="card-body">
                    <h6 class="text-muted">Active Volunteer Assignments</h6>
                    <h2 class="mb-0">{{ $activeAssignments }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow h-100">
                <div class="card-body">
                    <h6 class="text-muted">Recent Scenarios</h6>
                    <h2 class="mb-0">{{ $scenarios->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card shadow">
                <div class="card-header" style="background-color:#1B4C6D;color:white;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-crosshairs"></i> Run New Pre-Disaster Scenario</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('pie-pra.run') }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Scenario Name</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Typhoon Signal No. 3 within 24 hours" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Hazard Type</label>
                                <select name="hazard_type" class="form-select" required>
                                    <option value="typhoon">Typhoon</option>
                                    <option value="flood">Flood</option>
                                    <option value="earthquake">Earthquake</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Lead Time (hours)</label>
                                <input type="number" name="lead_time_hours" min="0" max="168" class="form-control" value="24" required>
                            </div>
                            <div class="col-md-8">
                                <small class="text-muted">
                                    PIE-PRA will rank schools, suggest when to suspend classes / start evacuation, and estimate resource pre-positioning.
                                </small>
                            </div>
                        </div>
                        <button type="submit" class="btn" style="background-color:#1B4C6D;color:white;">
                            <i class="fas fa-magic me-1"></i> Generate Recommendations
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card shadow h-100">
                <div class="card-header" style="background-color:#1B4C6D;color:white;">
                    <h5 class="mb-0"><i class="fas fa-clipboard-list"></i> Latest Scenarios</h5>
                </div>
                <div class="card-body">
                    @forelse($scenarios as $scenario)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $scenario->name }}</strong><br>
                                    <small class="text-muted">{{ ucfirst($scenario->hazard_type) }} • {{ $scenario->lead_time_hours }}h lead</small>
                                </div>
                                <a href="{{ route('pie-pra.scenario.show', $scenario->id) }}" class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No scenarios yet. Run one using the form.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

