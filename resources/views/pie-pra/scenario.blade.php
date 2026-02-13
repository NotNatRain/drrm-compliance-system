@extends('layouts.app')

@section('title', 'PIE-PRA Scenario')
@section('hide_main_nav', '1')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('pie-pra.dashboard') }}" class="btn btn-outline-secondary border-0 p-2">
            <i class="fas fa-arrow-left fa-lg"></i>
        </a>
        <h1 class="h5 mb-0" style="color:#1B4C6D;">
            <i class="fas fa-crosshairs"></i> {{ $scenario->name }}
        </h1>
    </div>

    <div class="card shadow mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h6 class="text-muted">Hazard Type</h6>
                    <p class="mb-0">{{ ucfirst($scenario->hazard_type) }}</p>
                </div>
                <div class="col-md-3">
                    <h6 class="text-muted">Lead Time</h6>
                    <p class="mb-0">{{ $scenario->lead_time_hours }} hours</p>
                </div>
                <div class="col-md-3">
                    <h6 class="text-muted">Recommendations</h6>
                    <p class="mb-0">{{ $scenario->recommendations_count }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header" style="background-color:#1B4C6D;color:white;">
            <h5 class="mb-0"><i class="fas fa-school"></i> School Recommendations</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>School</th>
                            <th>Priority</th>
                            <th>Activate as Evac Center?</th>
                            <th>Suspend Classes</th>
                            <th>Start Evacuation</th>
                            <th>Pre-Position Resources (estimate)</th>
                            <th>Academic Continuity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($scenario->recommendations as $rec)
                            <tr>
                                <td>{{ $rec->school->school_name ?? 'Unknown' }}</td>
                                <td>
                                    <span class="badge bg-{{ $rec->priority_score >= 75 ? 'danger' : ($rec->priority_score >= 50 ? 'warning text-dark' : 'success') }}">
                                        {{ $rec->priority_score }}
                                    </span>
                                </td>
                                <td>
                                    @if($rec->activate_as_evac_center)
                                        <span class="badge bg-success">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">Optional</span>
                                    @endif
                                </td>
                                <td>{{ optional($rec->recommended_suspend_classes_at)->format('M d, H:i') ?? '—' }}</td>
                                <td>{{ optional($rec->recommended_start_evac_at)->format('M d, H:i') ?? '—' }}</td>
                                <td>
                                    @php $r = $rec->preposition_resources ?? []; @endphp
                                    <small>
                                        Food: {{ $r['food_packs'] ?? 0 }},
                                        Water (L): {{ $r['water_liters'] ?? 0 }},
                                        Kits: {{ $r['sleeping_kits'] ?? 0 }}
                                    </small>
                                </td>
                                <td style="max-width:240px;">
                                    <small>{{ $rec->academic_continuity_notes }}</small>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

