@extends('comprehensive-school-safety.layouts.app')
@section('activeMenu', 'facilities')
@section('headerLabel', $school->name ?? 'Facilities')

@section('content')
<div class="d-flex justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h2 class="csss-section-title mb-1">Safety Facilities Dashboard</h2>
        <p class="csss-muted mb-0">Read-only references from Fire Safety, with risk and action tracking for this school.</p>
    </div>
    <div class="text-end">
        <div class="badge bg-dark text-white px-3 py-2 mb-2">{{ $assessmentSummary['building_count'] ?? 0 }} Fire Safety references</div>
        <p class="csss-muted small mb-0">Average score: <strong>{{ $assessmentSummary['average_score'] ?? 0 }}</strong></p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="csss-card p-4 h-100">
            <p class="csss-muted small mb-2">Average safety score</p>
            <h3 class="fw-bold mb-0">{{ $assessmentSummary['average_score'] ?? 0 }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="csss-card p-4 h-100">
            <p class="csss-muted small mb-2">Good</p>
            <h3 class="fw-bold mb-0 text-success">{{ $assessmentSummary['good_count'] ?? 0 }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="csss-card p-4 h-100">
            <p class="csss-muted small mb-2">Fair</p>
            <h3 class="fw-bold mb-0 text-warning">{{ $assessmentSummary['fair_count'] ?? 0 }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="csss-card p-4 h-100">
            <p class="csss-muted small mb-2">Needs attention</p>
            <h3 class="fw-bold mb-0 text-danger">{{ $assessmentSummary['poor_count'] ?? 0 }}</h3>
        </div>
    </div>
</div>

<div class="csss-card p-4 mb-4">
    <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
        <div>
            <h5 class="fw-bold mb-1">Fire Safety References</h5>
            <p class="csss-muted small mb-0">Buildings are referenced from the Fire Safety module, not duplicated here.</p>
        </div>
        <span class="badge bg-light text-dark">Read only</span>
    </div>

    @if(($fireSafetyBuildings ?? collect())->isEmpty())
        <div class="text-center py-4 border rounded-3 bg-light">
            <i class="fas fa-building-circle-exclamation text-muted" style="font-size: 2rem;"></i>
            <p class="csss-muted mt-3 mb-0">No Fire Safety buildings are linked to this school yet.</p>
        </div>
    @else
        <div class="d-flex flex-wrap gap-2">
            @foreach($fireSafetyBuildings as $building)
                <span class="badge rounded-pill text-bg-light border px-3 py-2">
                    {{ $building->building_name ?? ('Building ' . $building->building_no) }}
                </span>
            @endforeach
        </div>
    @endif
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="csss-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Risk Register</h5>
                    <p class="csss-muted small mb-0">Risk is derived from Fire Safety scores, plans, alarms, and extinguisher coverage.</p>
                </div>
            </div>

            <div class="d-grid gap-3">
                @forelse($riskRegister as $item)
                    <div class="border rounded-3 p-3 {{ $item['needs_attention'] ? 'bg-light' : '' }}">
                        <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $item['title'] }}</h6>
                                <p class="csss-muted small mb-0">{{ $item['summary'] }}</p>
                            </div>
                            <span class="badge bg-{{ $item['color'] ?? 'secondary' }}">{{ $item['status'] }}</span>
                        </div>
                        <div class="row g-2 small text-muted">
                            <div class="col-md-3">Score: <strong>{{ $item['score'] }}</strong></div>
                            <div class="col-md-3">Exits: <strong>{{ $item['exits'] }}</strong></div>
                            <div class="col-md-3">Alarms: <strong>{{ $item['alarms'] }}</strong></div>
                            <div class="col-md-3">Extinguishers: <strong>{{ $item['extinguishers'] }}</strong></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 border rounded-3 bg-light">
                        <i class="fas fa-shield-halved text-muted" style="font-size: 2rem;"></i>
                        <p class="csss-muted mt-3 mb-0">No risk records available yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="csss-card p-4 h-100">
            <h5 class="fw-bold mb-3">Reference Facilities</h5>
            <p class="csss-muted small mb-3">Read-only references from evacuation centers, assembly areas, and school profile data.</p>

            <div class="d-grid gap-3">
                @foreach($referenceFacilities as $facility)
                    <div class="border rounded-3 p-3 bg-white">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $facility['label'] }}</h6>
                                <p class="mb-1">{{ $facility['value'] }}</p>
                                <small class="csss-muted">{{ $facility['meta'] }}</small>
                            </div>
                            <span class="badge bg-secondary-subtle text-dark">Reference</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="csss-card p-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h5 class="fw-bold mb-1">Action Tracker</h5>
            <p class="csss-muted small mb-0">Track the next steps that need to be completed for Fire Safety and school safety readiness.</p>
        </div>
        <span class="badge bg-danger">Open items</span>
    </div>

    @if(($actionItems ?? collect())->isEmpty())
        <div class="text-center py-4 border rounded-3 bg-light">
            <i class="fas fa-circle-check text-success" style="font-size: 2rem;"></i>
            <p class="csss-muted mt-3 mb-0">No open actions right now.</p>
        </div>
    @else
        <div class="row g-3">
            @foreach($actionItems as $action)
                <div class="col-md-6 col-xl-4">
                    <div class="border rounded-3 p-3 h-100 bg-white">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                            <h6 class="fw-bold mb-0">{{ $action['title'] }}</h6>
                            <span class="badge bg-{{ $action['color'] ?? 'danger' }}">{{ $action['status'] }}</span>
                        </div>
                        <p class="csss-muted small mb-0">{{ $action['action'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
