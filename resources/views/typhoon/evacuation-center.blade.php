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
                    Evacuation Center - <span style="color: var(--accent-blue);">{{ $ec->school->school_name ?? $ec->identification ?? ('Center #' . $ec->id) }}</span>
                </h1>
                <div class="small text-white-50 mt-1">
                    <i class="fas fa-info-circle me-1"></i> Detailed Intelligence & Managed Records
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="text-end me-3 d-none d-md-block">
                <div class="small text-white-50">SYSTEM STATUS</div>
                <div class="small fw-bold text-success"><i class="fas fa-circle me-1" style="font-size: 8px;"></i> ONLINE</div>
            </div>
            <div class="badge bg-white text-dark border-0 p-3 shadow-sm">
                <i class="fas fa-clock me-2 text-primary"></i> {{ now()->format('M d, Y | h:i A') }}
            </div>
            <div class="btn-group shadow">
                <button class="btn btn-success px-3 fw-bold" onclick="document.getElementById('evacPrintModal').style.display='flex'" title="Print Evacuation Center">
                    <i class="fas fa-print me-2"></i>Print Evacuation Center
                </button>
                <button type="button" class="btn btn-primary px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#updateCenterStatusModal">
                    <i class="fas fa-edit me-2"></i>Update Site
                </button>
            </div>
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
                                @elseif($ec->usage_status === 'decamp')
                                    <span class="badge text-white shadow-sm px-3 py-2" style="background-color: #6f42c1;">DECAMP</span>
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
                            <option value="decamp" @selected($ec->usage_status === 'decamp')>DECAMP</option>
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

{{-- ===================== EVACUATION CENTER PRINT OVERLAY ===================== --}}
<div id="evacPrintModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.92); z-index:9999; flex-direction:column; align-items:center; justify-content:flex-start; overflow-y:auto; padding: 1.5rem 0;">

    {{-- ACTION BUTTONS --}}
    <div id="printActionBar" style="display:flex; align-items:center; gap:1rem; margin-bottom:1.25rem; width:1100px; max-width:96vw; justify-content:flex-end;">
        <span style="color:#8892b0; font-size:0.82rem; margin-right:auto; font-family:'Space Grotesk',sans-serif;">
            <i class="fas fa-info-circle me-1"></i> Previewing Evacuation Center Report. Click <strong style="color:#00d2ff;">Print / Save as PDF</strong> to continue.
        </span>
        <button onclick="printEvacCard()" style="background:#00d2ff; border:none; color:#0a1128; border-radius:8px; padding:0.6rem 1.5rem; font-weight:800; font-size:0.95rem; cursor:pointer; letter-spacing:0.5px; display:flex; align-items:center; gap:0.5rem; box-shadow:0 0 20px rgba(0,210,255,0.4);">
            <i class="fas fa-file-pdf"></i> Print / Save as PDF
        </button>
        <button onclick="document.getElementById('evacPrintModal').style.display='none'" style="background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.25); color:#fff; border-radius:8px; padding:0.6rem 1.25rem; font-weight:700; font-size:0.95rem; cursor:pointer; display:flex; align-items:center; gap:0.5rem;">
            <i class="fas fa-times"></i> Close
        </button>
    </div>

    {{-- PRINT CARD --}}
    <div id="printCard" style="background: linear-gradient(135deg, #0a1128 0%, #112240 55%, #0d2137 100%); color: #e2e8f0; width: 1100px; max-width: 96vw; border-radius: 16px; box-shadow: 0 0 80px rgba(0,210,255,0.15); border: 1px solid rgba(0,210,255,0.2); font-family: 'Rajdhani', 'Space Grotesk', sans-serif; padding: 2.5rem; margin-bottom: 2rem;">

        {{-- Header with Logos --}}
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.8rem; padding-bottom:1.5rem; border-bottom:1px solid rgba(0,210,255,0.2);">
            <div style="display:flex; align-items:center; gap:1.25rem;">
                <img src="{{ asset('images/drrmis-logo-2.png') }}" alt="DRRMIS" style="height:65px; object-fit:contain;">
                <img src="{{ asset('images/What-Is-the-Difference-Between-DepEd-Seal-and-DepEd-Logo.png') }}" alt="DepEd" style="height:65px; object-fit:contain;">
                <img src="{{ asset('images/Layer-0-1.png') }}" alt="Logo" style="height:65px; object-fit:contain;">
                <div style="margin-left:0.5rem; padding-left:1.25rem; border-left:2px solid rgba(0,210,255,0.3);">
                    <div style="font-size:0.65rem; color:#8892b0; text-transform:uppercase; letter-spacing:2px;">Department of Education</div>
                    <div style="font-size:0.85rem; color:#ffffff; font-weight:700; letter-spacing:1px;">EVACUATION CENTER INTELLIGENCE REPORT</div>
                </div>
            </div>
            <div style="text-align:right;">
                <div style="font-size:1.5rem; font-weight:800; color:#00d2ff; letter-spacing:1px; line-height:1;">{{ $ec->school->school_name ?? $ec->identification }}</div>
                <div style="font-size:0.8rem; color:#8892b0; margin-top:0.35rem;">{{ $ec->location ?? $ec->school->address }}</div>
                <div style="font-size:0.65rem; color:#00d2ff; margin-top:0.2rem; font-weight:700; letter-spacing:1px;">📅 {{ now()->format('F d, Y | h:i A') }}</div>
            </div>
        </div>

        {{-- Site Profile Stats --}}
        <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:1.25rem; margin-bottom:2rem;">
            <div style="background:rgba(0,210,255,0.08); border:1px solid rgba(0,210,255,0.2); border-radius:12px; padding:1.25rem; text-align:center;">
                <div style="font-size:0.65rem; color:#8892b0; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:0.4rem;">Operational Status</div>
                <div style="font-size:1.5rem; font-weight:800; color:#00d2ff; text-transform:uppercase;">{{ $ec->usage_status }}</div>
            </div>
            <div style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:12px; padding:1.25rem; text-align:center;">
                <div style="font-size:0.65rem; color:#8892b0; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:0.4rem;">Max Capacity</div>
                <div style="font-size:1.8rem; font-weight:800; color:#ffffff;">{{ $ec->capacity ?? '0' }}</div>
            </div>
            <div style="background:rgba(0,210,255,0.12); border:1px solid rgba(0,210,255,0.3); border-radius:12px; padding:1.25rem; text-align:center; box-shadow:0 0 15px rgba(0,210,255,0.1);">
                <div style="font-size:0.65rem; color:#00d2ff; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:0.4rem; font-weight:700;">Current Load</div>
                <div style="font-size:1.8rem; font-weight:800; color:#00d2ff;">{{ $currentOccupancy }}</div>
            </div>
            <div style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:12px; padding:1.25rem; text-align:center;">
                <div style="font-size:0.65rem; color:#8892b0; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:0.4rem;">Registry Count</div>
                <div style="font-size:1.8rem; font-weight:800; color:#ffffff;">{{ count($families) }}</div>
            </div>
        </div>

        {{-- Situation Report & Resources --}}
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem; margin-bottom:2rem;">
            <div style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.1); border-radius:12px; padding:1.25rem;">
                <div style="font-size:0.7rem; color:#00d2ff; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:0.8rem; font-weight:700;">📑 Latest Situation Narrative</div>
                <div style="font-size:0.9rem; color:#e2e8f0; line-height:1.5; min-height:80px;">
                    {{ $ec->reports_status ?: 'No situation report provided.' }}
                </div>
            </div>
            <div style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.1); border-radius:12px; padding:1.25rem;">
                <div style="font-size:0.7rem; color:#00d2ff; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:0.8rem; font-weight:700;">📦 Resource Shortages / Needs</div>
                <div style="font-size:0.9rem; color:#e2e8f0; line-height:1.5; min-height:80px;">
                    {{ $ec->emergency_resources ?: 'No resource shortages reported.' }}
                </div>
            </div>
        </div>

        {{-- Registry Table --}}
        <div>
            <div style="font-size:0.75rem; color:#00d2ff; text-transform:uppercase; letter-spacing:2px; font-weight:700; margin-bottom:1rem; padding-bottom:0.5rem; border-bottom:1px solid rgba(0,210,255,0.2);">📋 Master Evacuation Registry (Recent)</div>
            <table style="width:100%; border-collapse:collapse; font-size:0.85rem;">
                <thead>
                    <tr style="background:rgba(0,210,255,0.12);">
                        <th style="padding:0.6rem 0.75rem; text-align:left; color:#00d2ff; text-transform:uppercase; font-size:0.65rem; letter-spacing:1px;">Head of Family</th>
                        <th style="padding:0.6rem 0.75rem; text-align:center; color:#00d2ff; text-transform:uppercase; font-size:0.65rem; letter-spacing:1px;">Members</th>
                        <th style="padding:0.6rem 0.75rem; text-align:left; color:#00d2ff; text-transform:uppercase; font-size:0.65rem; letter-spacing:1px;">Vulnerabilities</th>
                        <th style="padding:0.6rem 0.75rem; text-align:left; color:#00d2ff; text-transform:uppercase; font-size:0.65rem; letter-spacing:1px;">Entry Date</th>
                        <th style="padding:0.6rem 0.75rem; text-align:left; color:#00d2ff; text-transform:uppercase; font-size:0.65rem; letter-spacing:1px;">Needs</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(collect($families)->take(12) as $family)
                    <tr style="border-bottom:1px solid rgba(255,255,255,0.05);">
                        <td style="padding:0.7rem 0.75rem; font-weight:700; color:#e2e8f0;">{{ $family->head_family_name }}</td>
                        <td style="padding:0.7rem 0.75rem; text-align:center; color:#ffffff;">{{ $family->members_count }}</td>
                        <td style="padding:0.7rem 0.75rem; color:#f59e0b; font-size:0.75rem;">
                            @php $v = []; 
                                if($family->has_pregnant) $v[]='PRG'; if($family->has_pwd) $v[]='PWD'; 
                                if($family->has_senior) $v[]='SNR'; if($family->has_lactating) $v[]='LAC';
                                if($family->has_child_under5) $v[]='CHD'; 
                            @endphp
                            {{ implode(', ', $v) ?: '—' }}
                        </td>
                        <td style="padding:0.7rem 0.75rem; color:#8892b0;">{{ $family->created_at->format('M d, Y') }}</td>
                        <td style="padding:0.7rem 0.75rem; color:#8892b0; font-size:0.75rem;">{{ Str::limit($family->collective_needs, 40) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center; color:#8892b0; padding:2rem;">No registered families in this center.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div style="margin-top:2rem; padding-top:1rem; border-top:1px solid rgba(0,210,255,0.12); display:flex; justify-content:space-between; align-items:center; font-size:0.7rem; color:#4a5568;">
            <span>Validated by: ________________________ (DRRM Center Lead)</span>
            <span>Electronic Copy • Printed: {{ now()->format('M d, Y h:i A') }}</span>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function printEvacCard() {
        const card = document.getElementById('printCard');
        const html = `<html><head><title>Print Report</title>
            <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
            <style>
                body { margin: 0; padding: 20px; background: #fff !important; color: #000 !important; font-family: 'Rajdhani', sans-serif; }
                #printCard { 
                    width: 1000px; margin: 0 auto; padding: 20px; border: 2px solid #000; color: #000 !important; 
                    background: none !important; box-shadow: none !important; 
                }
                div[style*="background:rgba"], div[style*="background:linear-gradient"] { 
                    background: none !important; border: 1px solid #ddd !important; color: #000 !important; 
                }
                span[style*="color:#00d2ff"], div[style*="color:#00d2ff"] { color: #000 !important; }
                table tr[style*="background:rgba"] { background: #f2f2f2 !important; }
                th { background: #eee !important; color: #000 !important; border-bottom: 2px solid #000 !important; }
                td, th { border-bottom: 1px solid #ccc !important; }
                * { print-color-adjust: exact !important; -webkit-print-color-adjust: exact !important; }
                @media print { @page { margin: 1cm; size: landscape; } .btn-group, #printActionBar { display:none; } }
            </style>
        </head><body><div id="printCard">${card.innerHTML}</div>
        <script>window.onload=function(){window.print();setTimeout(function(){window.close();},500);};<\/script>
        </body></html>`;

        const printWin = window.open('', '_blank', 'width=1100,height=780');
        if (!printWin) {
            alert('Pop-up blocked! Please allow pop-ups for this page.');
            return;
        }
        printWin.document.write(html);
        printWin.document.close();
    }
</script>
@endpush
@endsection

