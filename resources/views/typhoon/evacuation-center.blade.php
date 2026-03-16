@extends('layouts.app')

@section('title', 'Evacuation Center Details')
@section('hide_main_nav', '1')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --bg-dark: #0a1128;
        --card-bg: #ffffff;
        --card-header-bg: #0f2154ff;
        --accent-blue: #00d2ff;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --glass-border: rgba(0, 0, 0, 0.05);
    }

    body {
        background-color: var(--bg-dark) !important;
        background-image: radial-gradient(circle at 50% 50%, #112240 0%, #0a1128 100%);
        color: var(--text-dark);
        font-family: 'Space Grotesk', 'Inter', sans-serif;
    }

    h1, h2, h3, h4, h5, .card-header-custom, .stat-value, .fw-bold, .profile-property, .profile-value {
        font-family: 'Rajdhani', sans-serif;
        letter-spacing: 0.5px;
    }

    .container-fluid {
        padding: 2rem;
    }

    .dashboard-card {
        background: var(--card-bg);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        transition: transform 0.2s ease;
        height: 100%;
        color: var(--text-dark);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .card-header-custom {
        background: var(--card-header-bg);
        color: #ffffff;
        padding: 1rem 1.5rem;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        border-bottom: 2px solid rgba(0,0,0,0.1);
    }

    .card-header-custom i {
        color: var(--accent-blue);
        margin-right: 10px;
        font-size: 1.1rem;
    }

    .profile-property {
        font-weight: 700;
        color: var(--text-muted);
        font-size: 0.85rem;
        text-transform: uppercase;
        margin-bottom: 0.25rem;
    }

    .profile-value {
        color: var(--text-dark);
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 1.25rem;
    }

    .table-custom {
        color: var(--text-dark);
    }

    .table-custom thead tr {
        background: #f8fafc;
    }

    .table-custom th {
        color: var(--text-muted);
        border-bottom: 1px solid #e2e8f0;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        font-weight: 600;
        padding: 1rem;
    }

    .table-custom td {
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        padding: 1rem;
    }

    .btn-action {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: var(--text-dark);
        transition: all 0.2s ease;
        font-weight: 600;
    }

    .btn-action:hover {
        background: var(--accent-blue);
        color: white;
        border-color: var(--accent-blue);
    }

    h1, h2, h3, h5 {
        color: #ffffff !important;
    }

    .dashboard-card h1, .dashboard-card h2, .dashboard-card h3, .dashboard-card h5, .dashboard-card .h3 {
        color: var(--text-dark) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div class="d-flex align-items-center">
            <a href="{{ route('typhoon.dashboard') }}" class="btn btn-outline-info border-0 me-3" title="Back">
                <i class="fas fa-chevron-left fa-lg"></i>
            </a>
            <div>
                <h1 class="h2 mb-0 fw-bold text-white">
                    <i class="fas fa-satellite-dish me-2" style="color: var(--accent-blue);"></i>
                    Typhoon & Flood <span style="color: var(--accent-blue);">Reporting System</span>
                </h1>
                <p class="text-white-50 mb-0"><i class="fas fa-school me-2"></i>{{ $ec->school->school_name ?? $ec->identification ?? ('Evacuation Center #' . $ec->id) }}</p>
            </div>
        </div>
        <div>
            <button type="button" class="btn btn-primary px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#updateCenterStatusModal">
                <i class="fas fa-edit me-2"></i>UPDATE SITE STATUS
            </button>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="dashboard-card mb-4">
                <div class="card-header-custom"><i class="fas fa-info-circle"></i>Evacuation Center Profile</div>
                <div class="p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="profile-property">Identification Code</div>
                            <div class="profile-value">{{ $ec->identification ?? '—' }}</div>
                            
                            <div class="profile-property">Location / Address</div>
                            <div class="profile-value">{{ $ec->location ?? $ec->school->address ?? '—' }}</div>
                            
                            <div class="profile-property">Operational Status</div>
                            <div class="profile-value">
                                @if($ec->usage_status === 'full')
                                    <span class="badge bg-danger shadow-sm px-3 py-2">AT CAPACITY</span>
                                @elseif($ec->usage_status === 'occupied')
                                    <span class="badge bg-primary shadow-sm px-3 py-2">OCCUPIED</span>
                                @else
                                    <span class="badge bg-success shadow-sm px-3 py-2">CLEARED / READY</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="profile-property">Max Capacity</div>
                            <div class="profile-value text-primary fs-3 fw-bold">{{ $ec->capacity ?? 0 }} <small class="text-muted fs-6">Individuals</small></div>
                            
                            <div class="profile-property">Current Load</div>
                            <div class="profile-value fs-3 fw-bold">{{ $currentOccupancy }} <small class="text-muted fs-6">Individuals</small></div>

                            <div class="profile-property">Resources Inventory</div>
                            <div class="profile-value fs-6">{{ $ec->emergency_resources ?? 'No inventory data encoded yet.' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-header-custom"><i class="fas fa-people-arrows"></i>Recent Family Registration History</div>
                <div class="table-responsive">
                    <table class="table table-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Timestamp</th>
                                <th>Head of Family</th>
                                <th class="text-center">Members</th>
                                <th>Vulnerability Flags</th>
                                <th>Collective Needs</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($families as $family)
                                <tr>
                                    <td><span class="fw-bold text-muted">{{ $family->created_at->format('M d, Y') }}</span><br><small>{{ $family->created_at->format('h:i A') }}</small></td>
                                    <td><div class="fw-bold fs-6">{{ $family->head_family_name }}</div></td>
                                    <td class="text-center"><span class="badge bg-light text-dark border">{{ $family->members_count }}</span></td>
                                    <td>
                                        @php $flags = []; @endphp
                                        @if($family->has_pregnant) @php $flags[] = 'Pregnant'; @endphp @endif
                                        @if($family->has_pwd) @php $flags[] = 'PWD'; @endphp @endif
                                        @if($family->has_senior) @php $flags[] = 'Senior'; @endphp @endif
                                        @if($family->has_lactating) @php $flags[] = 'Lactating'; @endphp @endif
                                        @if($family->has_child_under5) @php $flags[] = 'Child <5'; @endphp @endif
                                        
                                        @foreach($flags as $flag)
                                            <span class="badge bg-warning text-dark me-1" style="font-size: 0.65rem;">{{ $flag }}</span>
                                        @endforeach
                                        @if(empty($flags)) <small class="text-muted italic">None</small> @endif
                                    </td>
                                    <td><small class="text-truncate d-block" style="max-width: 150px;">{{ $family->collective_needs ?? '—' }}</small></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 opacity-50">
                                        <i class="fas fa-users-slash fa-3x mb-3 text-muted"></i>
                                        <p>No family registrations recorded for this site yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="dashboard-card mb-4">
                <div class="card-header-custom"><i class="fas fa-history"></i>Site Activity Logs</div>
                <div class="p-4">
                    @if($lastUsedAt)
                        <div class="mb-4">
                            <div class="profile-property">Last Activation Date</div>
                            <div class="fw-bold">{{ $lastUsedAt->format('F d, Y h:i A') }}</div>
                        </div>
                        <div>
                            <div class="profile-property">Latest Situation Summary</div>
                            <div class="p-3 bg-light rounded border">
                                <p class="mb-0 small">{{ $ec->reports_status ?? 'No recent narrative reports submitted.' }}</p>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5 opacity-50">
                            <i class="fas fa-calendar-times fa-3x mb-3"></i>
                            <p>This station has no recorded usage history.</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="dashboard-card">
                <div class="card-header-custom"><i class="fas fa-bullhorn"></i>Quick Announcements</div>
                <div class="p-4">
                    <div class="alert alert-info border-0 shadow-sm small">
                        <i class="fas fa-info-circle me-2"></i> Ensure all evacuees are provided with hygiene kits upon entry.
                    </div>
                    <div class="alert alert-warning border-0 shadow-sm small">
                        <i class="fas fa-exclamation-triangle me-2"></i> Report any infrastructure damage to the DRRM office immediately.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Update status / reports modal --}}
<div class="modal fade" id="updateCenterStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('typhoon.evacuation-center.update', $ec->id) }}">
            @csrf
            @method('PUT')
            <div class="modal-content shadow">
                <div class="modal-header" style="background-color: var(--card-header-bg); color: white;">
                    <h5 class="modal-title fw-bold"><i class="fas fa-sync-alt me-2 text-info"></i>UPDATE SITE INTELLIGENCE</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">SITE OPERATIONAL STATUS</label>
                        <select name="usage_status" class="form-select form-select-lg">
                            <option value="cleared" @selected($ec->usage_status === 'cleared')>CLEARED / STANDBY</option>
                            <option value="occupied" @selected($ec->usage_status === 'occupied')>OCCUPIED / ACTIVE</option>
                            <option value="full" @selected($ec->usage_status === 'full')>AT CAPACITY / FULL</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">RESOURCE INVENTORY SUMMARY</label>
                        <textarea name="emergency_resources" rows="2" class="form-control" placeholder="e.g. 50 Hygiene Kits, 100 Blankets">{{ old('emergency_resources', $ec->emergency_resources) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">LATEST SITUATION REPORT (SITREP)</label>
                        <textarea name="reports_status" rows="3" class="form-control" placeholder="Describe current issues, damages, or requests...">{{ old('reports_status', $ec->reports_status) }}</textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">SAVE CHANGES</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

