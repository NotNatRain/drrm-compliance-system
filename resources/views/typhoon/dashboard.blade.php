{{-- resources/views/typhoon/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Typhoon/Flooding Monitoring')
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

    h1, h2, h3, h4, h5, .card-header-custom, .stat-value, .fw-bold {
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

    .stat-value {
        font-size: 2.25rem;
        font-weight: 800;
        color: var(--text-dark);
    }

    .stat-label {
        color: var(--text-muted);
        font-size: 0.9rem;
        font-weight: 500;
    }

    .badge-custom {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
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

    .text-muted {
        color: var(--text-muted) !important;
    }

    @keyframes typhoonSpin {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }

    /* ====================================================
     * 70/30 HYBRID MOBILE APPROACH — Typhoon Dashboard
     * Desktop layout preserved. Minimal tweaks only:
     *  1. Scrollable tables
     *  2. Slightly larger button tap targets
     *  3. Stack form columns in modals/forms
     * ==================================================== */
    /* Triggered by 1024px viewport lock — desktop layout preserved but mobile enhancements active */
    @media (max-width: 1024.1px) {
        .table-responsive {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
        }
        .table-custom {
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .btn:not(.btn-sm):not(.btn-xs):not(.btn-action) {
            min-height: 40px;
            padding-top: 0.45rem !important;
            padding-bottom: 0.45rem !important;
        }
        form .row > [class*="col-md-"],
        form .row > [class*="col-sm-"],
        .modal-body .row > [class*="col-md-"],
        .modal-body .row > [class*="col-sm-"] {
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div class="d-flex align-items-center">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-info border-0 me-3" title="Back">
                <i class="fas fa-chevron-left fa-lg"></i>
            </a>
            <div>
                <h1 class="h2 mb-0 fw-bold text-white">
                    <i class="fas fa-satellite-dish me-2" style="color: var(--accent-blue);"></i>
                    Typhoon & Flood <span style="color: var(--accent-blue);">Reporting System</span>
                </h1>
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
                <button class="btn btn-success px-3 fw-bold" onclick="document.getElementById('socialPrintModal').style.display='flex'" title="Send to Social">
                    <i class="fas fa-share-alt me-2"></i>Send to Social
                </button>
                @if(auth()->user()->role === 'admin')
                <button type="button" class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#createEvacCenterModal">
                    <i class="fas fa-plus-circle me-2"></i>Add Center
                </button>
                <button type="button" class="btn btn-secondary px-3" data-bs-toggle="modal" data-bs-target="#chooseSchoolModal" title="Choose Center">
                    <i class="fas fa-school"></i>
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Main Layout Grid --}}
    <div class="row g-4 mb-4">
        {{-- Left Section (65%) --}}
        <div class="col-lg-8">
            <div class="row g-4">
                {{-- 1st Row: Estimated Affected Population & Incident Monitoring --}}
                <div class="col-md-6">
                    <div class="dashboard-card">
                        <div class="card-header-custom"><i class="fas fa-users"></i>Estimated Affected Population</div>
                        <div class="p-4">
                            <div class="row g-0 align-items-center">
                                <div class="col-6 mb-4">
                                    <div class="stat-label">Total Families</div>
                                    <div class="stat-value text-primary">{{ $totalFamilies ?? 0 }}</div>
                                </div>
                                <div class="col-6 mb-4">
                                    <div class="stat-label">Total Individuals</div>
                                    <div class="stat-value">{{ $totalEvacuees ?? 0 }}</div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center justify-content-between p-3 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                        <div class="stat-label mb-0">Active Evacuation Centers</div>
                                        <div class="h4 mb-0 fw-bold text-primary">{{ $openEvacuationCentersCount ?? 0 }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="dashboard-card">
                        <div class="card-header-custom"><i class="fas fa-exclamation-triangle"></i>Incident Monitoring</div>
                        <div class="p-4">
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background: #fff5f5; border-left: 4px solid #dc3545;">
                                    <div>
                                        <div class="fw-bold" style="color: #991b1b;">Major Incidents</div>
                                        <small class="text-muted">High priority response</small>
                                    </div>
                                    <div class="stat-value" style="color: #dc3545;">{{ $incidentMonitoring['major'] ?? 0 }}</div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background: #f0fdf4; border-left: 4px solid #198754;">
                                    <div>
                                        <div class="fw-bold" style="color: #166534;">Minor Incidents</div>
                                        <small class="text-muted">Managed/Under control</small>
                                    </div>
                                    <div class="stat-value" style="color: #198754;">{{ $incidentMonitoring['minor'] ?? 0 }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2nd Row: Daily Rainfall (MM) & Weather Forecast --}}
                <div class="col-md-6">
                    <div class="dashboard-card">
                        <div class="card-header-custom"><i class="fas fa-cloud-showers-heavy"></i>Daily Rainfall (MM)</div>
                        <div class="p-4">
                            <div class="row">
                                <div class="col-6">
                                    <div class="p-3 text-center rounded" style="background: #f0f9ff;">
                                        <div class="stat-label">Bangal Station</div>
                                        <div class="h3 mb-0 fw-bold text-primary">{{ $rainfall['bangal'] ?? '0.0' }} <small>mm</small></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 text-center rounded" style="background: #f0f9ff;">
                                        <div class="stat-label">Kalaklan Station</div>
                                        <div class="h3 mb-0 fw-bold text-primary">{{ $rainfall['kalaklan'] ?? '0.0' }} <small>mm</small></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="progress" style="height: 6px; background: #f1f5f9;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 45%;"></div>
                                </div>
                                <small class="text-muted mt-2 d-block">Accumulated precipitation last 24h</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="dashboard-card">
                        <div class="card-header-custom"><i class="fas fa-cloud-sun-rain"></i>Weather Forecast</div>
                        <div class="p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="me-4">
                                    <i class="fas fa-cloud-showers-water fa-3x text-primary"></i>
                                </div>
                                <div>
                                    <div class="h3 mb-0 fw-bold">{{ $typhoonData->name ?? 'Moderate Rain' }}</div>
                                    <div class="text-muted">{{ $typhoonData->temp ?? '28' }}°C | {{ $typhoonData->wind ?? '15' }} km/h Wind</div>
                                </div>
                            </div>
                            <div class="small p-2 rounded bg-light border text-center">
                                <i class="fas fa-info-circle me-1 text-primary"></i> Public Storm Warning Signal #1 Active
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Section: Occupancy Overview (35%) --}}
        <div class="col-lg-4">
            <div class="dashboard-card d-flex flex-column">
                <div class="card-header-custom"><i class="fas fa-chart-pie"></i>Occupancy Overview</div>
                <div class="p-4 flex-grow-1 d-flex flex-column">
                    <div class="flex-grow-1" style="min-height: 250px; position: relative;">
                        <canvas id="occupancyChart"></canvas>
                    </div>
                    <div class="mt-4">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Total System Capacity</span>
                            <span class="fw-bold">85% Utilized</span>
                        </div>
                        <div class="progress" style="height: 8px; background: #f1f5f9;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 85%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ Active Typhoon Alert Banner (only shown when a TC is near Philippines) ═══ --}}
    @if(!empty($activeTyphoon))
    @php
        $signalColors = [
            1 => ['bg' => '#f59e0b', 'border' => '#d97706', 'text' => '#78350f', 'badge' => '#fde68a'],
            2 => ['bg' => '#f97316', 'border' => '#ea580c', 'text' => '#431407', 'badge' => '#fed7aa'],
            3 => ['bg' => '#ef4444', 'border' => '#dc2626', 'text' => '#450a0a', 'badge' => '#fecaca'],
            4 => ['bg' => '#9333ea', 'border' => '#7c3aed', 'text' => '#2e1065', 'badge' => '#e9d5ff'],
            5 => ['bg' => '#0f172a', 'border' => '#00d2ff', 'text' => '#e2e8f0', 'badge' => '#00d2ff'],
        ];
        $sc = $signalColors[$activeTyphoon['signal']] ?? $signalColors[1];
    @endphp
    <div class="mb-4" style="border-radius: 14px; overflow: hidden; box-shadow: 0 0 40px {{ $sc['border'] }}55;">
        <div style="background: linear-gradient(135deg, {{ $sc['bg'] }} 0%, {{ $sc['border'] }} 100%); padding: 0; position: relative; overflow: hidden;">
            
            {{-- Animated rotating storm background --}}
            <div style="position:absolute; top:-60px; right:-60px; width:220px; height:220px; border-radius:50%;
                        border: 3px solid rgba(255,255,255,0.1);
                        animation: typhoonSpin 8s linear infinite; pointer-events:none; opacity:0.3;">
            </div>
            <div style="position:absolute; top:-30px; right:-30px; width:160px; height:160px; border-radius:50%;
                        border: 3px solid rgba(255,255,255,0.15);
                        animation: typhoonSpin 5s linear infinite reverse; pointer-events:none; opacity:0.4;">
            </div>

            <div style="position:relative; z-index:2; display:flex; align-items:center; justify-content:space-between; padding: 1.25rem 1.75rem; gap: 1.5rem; flex-wrap:wrap;">
                
                {{-- Left: Storm icon + label --}}
                <div style="display:flex; align-items:center; gap:1rem;">
                    <div style="font-size:3rem; animation: typhoonSpin 4s linear infinite; line-height:1;">
                        🌀
                    </div>
                    <div>
                        <div style="font-size:0.65rem; color:rgba(255,255,255,0.75); text-transform:uppercase; letter-spacing:2px; font-weight:700; margin-bottom:0.2rem;">
                            ⚠ PAGASA Active Weather Alert — Directly Affecting Olongapo City Area
                        </div>
                        <div style="font-family:'Rajdhani',sans-serif; font-size:1.65rem; font-weight:800; color:#fff; letter-spacing:1px; line-height:1.1;">
                            Effects of <span style="color:rgba(255,255,255,0.75); font-weight:500;">{{ $activeTyphoon['category'] }}</span>
                            "<span style="color:#fff; font-weight:900;">{{ $activeTyphoon['name'] }}"</span>
                        </div>
                        <div style="font-size:0.78rem; color:rgba(255,255,255,0.8); margin-top:0.3rem;">
                            <i class="fas fa-map-marker-alt me-1"></i> Near <strong>Olongapo City · Zambales</strong>&nbsp;|&nbsp;
                            <i class="fas fa-ruler-combined me-1"></i> Distance: <strong>~{{ $activeTyphoon['distance_km'] ?? '?' }} km from Olongapo</strong>&nbsp;|&nbsp;
                            <i class="fas fa-wind me-1"></i> Max Wind: <strong>{{ $activeTyphoon['wind_kph'] > 0 ? $activeTyphoon['wind_kph'].' km/h' : '--' }}</strong>
                        </div>
                    </div>
                </div>

                {{-- Right: PAGASA Signal + source note --}}
                <div style="text-align:center; flex-shrink:0;">
                    <div style="font-size:0.6rem; color:rgba(255,255,255,0.7); text-transform:uppercase; letter-spacing:1px; margin-bottom:0.3rem;">TCWS Level</div>
                    <div style="background:rgba(255,255,255,0.15); border:2px solid rgba(255,255,255,0.4); border-radius:12px; padding:0.5rem 1.5rem; backdrop-filter:blur(6px);">
                        <div style="font-family:'Rajdhani',sans-serif; font-size:2.5rem; font-weight:900; color:#fff; line-height:1;">
                            #{{ $activeTyphoon['signal'] }}
                        </div>
                        <div style="font-size:0.65rem; color:rgba(255,255,255,0.7); letter-spacing:1px; text-transform:uppercase;">Signal No.</div>
                    </div>
                    <div style="font-size:0.55rem; color:rgba(255,255,255,0.5); margin-top:0.4rem;">
                        Source: GDACS · Auto-refreshes every 30 min
                    </div>
                </div>
            </div>

            {{-- Bottom stripe --}}
            <div style="background:rgba(0,0,0,0.25); padding:0.4rem 1.75rem; font-size:0.7rem; color:rgba(255,255,255,0.65); display:flex; align-items:center; gap:0.75rem;">
                <i class="fas fa-exclamation-circle me-1" style="color:rgba(255,255,255,0.9);"></i>
                <strong style="color:#fff;">DepEd Reminder:</strong>
                Classes in affected areas are automatically suspended under Tropical Cyclone Wind Signal {{ $activeTyphoon['signal'] >= 1 ? '#'.$activeTyphoon['signal'] : '' }}.
                All DepEd-Zambales schools must activate their DRRM protocols immediately.
            </div>
        </div>
    </div>
    @endif

    {{-- Bottom Row: Evacuation Centers Status --}}
    <div class="row g-4">
        <div class="col-12">
            @if(auth()->user()->role === 'contributor')
                @php $ec = $evacuationCenters->first(); @endphp
                @if($ec)
                <div class="dashboard-card overflow-hidden">
                    <div class="card-header-custom d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-school"></i> Your Managed School Center</span>
                        <div class="d-flex gap-2">
                            <span class="badge bg-success">CLEARED</span>
                            <span class="badge bg-primary">OCCUPIED</span>
                            <span class="badge bg-danger">FULL</span>
                            <span class="badge text-white" style="background-color: #6f42c1;">DECAMP</span>
                        </div>
                    </div>
                    <div class="p-4" style="background: white;">
                        <div class="row align-items-center">
                            <div class="col-md-4 border-end">
                                <div class="stat-label mb-1">Center Name</div>
                                <div class="h4 fw-bold text-primary mb-1">{{ $ec->school->school_name ?? $ec->identification }}</div>
                                <div class="small text-muted mb-3"><i class="fas fa-id-card me-1"></i> UID: {{ $ec->id }}</div>
                                
                                <div class="stat-label mb-1">Location</div>
                                <div class="small mb-0"><i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $ec->location }}</div>
                            </div>
                            <div class="col-md-5 px-4">
                                <div class="row text-center g-3">
                                    <div class="col-6">
                                        <div class="p-3 rounded bg-light border">
                                            <div class="stat-label">Current Load</div>
                                            <div class="stat-value text-info" style="font-size: 1.8rem;">{{ $ec->current_occupancy }}</div>
                                            <div class="small text-muted">Individuals</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3 rounded bg-light border">
                                            <div class="stat-label">Max Capacity</div>
                                            <div class="stat-value text-dark" style="font-size: 1.8rem;">{{ $ec->capacity > 0 ? $ec->capacity : '∞' }}</div>
                                            <div class="small text-muted">Limit</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span class="text-muted">Load Status</span>
                                        @php
                                            $loadPercent = $ec->capacity > 0 ? round(($ec->current_occupancy / $ec->capacity) * 100) : 0;
                                            $barColor = $loadPercent >= 90 ? 'bg-danger' : ($loadPercent >= 70 ? 'bg-warning' : 'bg-success');
                                        @endphp
                                        <span class="fw-bold">{{ $loadPercent }}% Full</span>
                                    </div>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar {{ $barColor }}" style="width: {{ min($loadPercent, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 text-center border-start">
                                <div class="stat-label mb-2">Operational Status</div>
                                @php
                                    $statusClass = 'success';
                                    $statusText = 'CLEARED';
                                    if($ec->usage_status === 'occupied') { $statusClass = 'primary'; $statusText = 'OCCUPIED'; }
                                    elseif($ec->usage_status === 'full') { $statusClass = 'danger'; $statusText = 'FULL'; }
                                    elseif($ec->usage_status === 'decamp') { $statusClass = 'custom'; $statusText = 'DECAMP'; }
                                @endphp
                                <span class="badge badge-custom bg-{{ $statusClass }} fs-6 mb-4 px-4 py-2" style="{{ $ec->usage_status === 'decamp' ? 'background-color: #6f42c1 !important;' : '' }}">
                                    {{ $statusText }}
                                </span>
                                
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary fw-bold"
                                            data-bs-toggle="modal"
                                            data-bs-target="#familyRegistrationModal"
                                            data-ec-id="{{ $ec->id }}"
                                            data-ec-name="{{ $ec->school->school_name ?? $ec->identification }}">
                                        <i class="fas fa-user-plus me-1"></i> Register Family
                                    </button>
                                    <a href="{{ route('typhoon.evacuation-center.show', $ec->id) }}" class="btn btn-outline-primary fw-bold">
                                        <i class="fas fa-external-link-alt me-1"></i> Manage Records
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-light p-3 border-top">
                        <div class="row align-items-center">
                            <div class="col-md-9">
                                <span class="small fw-bold text-muted text-uppercase me-2"><i class="fas fa-boxes me-1"></i> Resources:</span>
                                <span class="small text-dark">{{ $ec->emergency_resources ?: 'No reported resource shortages.' }}</span>
                            </div>
                            <div class="col-md-3 text-end">
                                <span class="small text-muted" style="font-size: 0.7rem;"><i>Updated: {{ $ec->updated_at->diffForHumans() }}</i></span>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="dashboard-card p-5 text-center">
                    <div class="opacity-50">
                        <i class="fas fa-school fa-4x mb-4"></i>
                        <h3>No School Assigned</h3>
                        <p>Please contact an administrator to assign you a school for management.</p>
                    </div>
                </div>
                @endif
            @else
                <!-- Admin Table View -->
                <div class="dashboard-card">
                    <div class="card-header-custom d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-hospital-user"></i>Evacuation Centers Status Monitoring</span>
                        <div class="d-flex gap-2">
                            <span class="badge bg-success">CLEARED</span>
                            <span class="badge bg-primary">OCCUPIED</span>
                            <span class="badge bg-danger">FULL</span>
                            <span class="badge text-white" style="background-color: #6f42c1;">DECAMP</span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">School / Center Name</th>
                                    <th>Location & Area</th>
                                    <th class="text-center">Max Capacity</th>
                                    <th class="text-center">Current Load</th>
                                    <th class="text-center">Operational Status</th>
                                    <th>Resource Inventory</th>
                                    <th class="pe-4 text-end">Management</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($evacuationCenters ?? [] as $ec)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold fs-6">{{ $ec->school->school_name ?? $ec->identification ?? ('Center #' . $ec->id) }}</div>
                                        <small class="text-muted">UID: {{ $ec->id }}</small>
                                    </td>
                                    <td>
                                        <div class="small"><i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ Str::limit($ec->location, 40) }}</div>
                                    </td>
                                    <td class="text-center fw-bold">{{ $ec->capacity > 0 ? $ec->capacity : 'Unlimited' }}</td>
                                    <td class="text-center">
                                        <div class="fw-bold text-info fs-5">{{ $ec->current_occupancy }}</div>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $statusClass = 'success';
                                            $statusText = 'CLEARED';
                                            if($ec->usage_status === 'occupied') { $statusClass = 'primary'; $statusText = 'OCCUPIED'; }
                                            elseif($ec->usage_status === 'full') { $statusClass = 'danger'; $statusText = 'FULL'; }
                                            elseif($ec->usage_status === 'decamp') { $statusClass = 'custom'; $statusText = 'DECAMP'; }
                                        @endphp
                                        <span class="badge badge-custom bg-{{ $statusClass }}" style="{{ $ec->usage_status === 'decamp' ? 'background-color: #6f42c1 !important;' : '' }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small text-truncate" style="max-width: 180px;" title="{{ $ec->emergency_resources }}">
                                            {{ $ec->emergency_resources ?? 'Standby' }}
                                        </div>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-action rounded-start"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#familyRegistrationModal"
                                                    data-ec-id="{{ $ec->id }}"
                                                    data-ec-name="{{ $ec->school->school_name ?? $ec->identification ?? ('Center #' . $ec->id) }}">
                                                <i class="fas fa-user-plus me-1"></i> Register
                                            </button>
                                            <a href="{{ route('typhoon.evacuation-center.show', $ec->id) }}" class="btn btn-sm btn-action rounded-end">
                                                <i class="fas fa-expand-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="opacity-50">
                                            <i class="fas fa-satellite fa-3x mb-3"></i>
                                            <h5>No Active Monitoring Stations</h5>
                                            <p>Please register evacuation centers to begin tracking.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
    {{-- Shared modals --}}
    @include('typhoon.partials.choose-school-modal')
    @include('typhoon.partials.create-evac-center-modal')
</div>

{{-- ===================== SOCIAL / PRINT OVERLAY ===================== --}}
<div id="socialPrintModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.92); z-index:9999; flex-direction:column; align-items:center; justify-content:flex-start; overflow-y:auto; padding: 1.5rem 0;">

    {{-- ACTION BUTTONS — outside the printable card --}}
    <div id="printActionBar" style="display:flex; align-items:center; gap:1rem; margin-bottom:1.25rem; width:1100px; max-width:96vw; justify-content:flex-end;">
        <span style="color:#8892b0; font-size:0.82rem; margin-right:auto; font-family:'Space Grotesk',sans-serif;">
            <i class="fas fa-info-circle me-1"></i> Preview your social media report. Click <strong style="color:#00d2ff;">Save as PDF</strong> to download.
        </span>
        <button onclick="printSocialCard()" style="background:#00d2ff; border:none; color:#0a1128; border-radius:8px; padding:0.6rem 1.5rem; font-weight:800; font-size:0.95rem; cursor:pointer; letter-spacing:0.5px; display:flex; align-items:center; gap:0.5rem; box-shadow:0 0 20px rgba(0,210,255,0.4);">
            <i class="fas fa-file-pdf"></i> Save as PDF
        </button>
        <button onclick="document.getElementById('socialPrintModal').style.display='none'" style="background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.25); color:#fff; border-radius:8px; padding:0.6rem 1.25rem; font-weight:700; font-size:0.95rem; cursor:pointer; display:flex; align-items:center; gap:0.5rem;">
            <i class="fas fa-times"></i> Close
        </button>
    </div>

    {{-- PRINT CARD (landscape, 1100px wide) --}}
    <div id="printCard" style="background: linear-gradient(135deg, #0a1128 0%, #112240 55%, #0d2137 100%); color: #e2e8f0; width: 1100px; max-width: 96vw; border-radius: 16px; box-shadow: 0 0 80px rgba(0,210,255,0.15); border: 1px solid rgba(0,210,255,0.2); font-family: 'Rajdhani', 'Space Grotesk', sans-serif; padding: 2.25rem; margin-bottom: 2rem;">

        {{-- Header with Logos --}}
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; padding-bottom:1.25rem; border-bottom:1px solid rgba(0,210,255,0.2);">
            <div style="display:flex; align-items:center; gap:1.25rem;">
                <img src="{{ asset('images/drrmis-logo-2.png') }}" alt="DRRMIS" style="height:65px; object-fit:contain;">
                <img src="{{ asset('images/What-Is-the-Difference-Between-DepEd-Seal-and-DepEd-Logo.png') }}" alt="DepEd" style="height:65px; object-fit:contain;">
                <img src="{{ asset('images/Layer-0-1.png') }}" alt="Logo" style="height:65px; object-fit:contain;">
                <div style="margin-left:0.5rem; padding-left:1.25rem; border-left:2px solid rgba(0,210,255,0.3);">
                    <div style="font-size:0.65rem; color:#8892b0; text-transform:uppercase; letter-spacing:2px;">Department of Education</div>
                    <div style="font-size:0.65rem; color:#8892b0; text-transform:uppercase; letter-spacing:1px;">Disaster Risk Reduction & Management</div>
                </div>
            </div>
            <div style="text-align:right;">
                <div style="font-size:1.75rem; font-weight:800; color:#00d2ff; letter-spacing:3px; line-height:1;">TYPHOON & FLOOD</div>
                <div style="font-size:1rem; font-weight:700; color:#ffffff; letter-spacing:2px;">REPORTING SYSTEM</div>
                <div style="font-size:0.72rem; color:#8892b0; margin-top:0.35rem; letter-spacing:0.5px;">📅 {{ now()->format('F d, Y  —  h:i A') }}</div>
            </div>
        </div>

        {{-- Stats Row --}}
        <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:1rem; margin-bottom:1.25rem;">
            <div style="background:rgba(0,210,255,0.08); border:1px solid rgba(0,210,255,0.2); border-radius:10px; padding:1.1rem; text-align:center;">
                <div style="font-size:0.65rem; color:#8892b0; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:0.35rem;">Total Families</div>
                <div style="font-size:2.5rem; font-weight:800; color:#00d2ff; line-height:1;">{{ $totalFamilies ?? 0 }}</div>
            </div>
            <div style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:10px; padding:1.1rem; text-align:center;">
                <div style="font-size:0.65rem; color:#8892b0; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:0.35rem;">Total Individuals</div>
                <div style="font-size:2.5rem; font-weight:800; color:#ffffff; line-height:1;">{{ $totalEvacuees ?? 0 }}</div>
            </div>
            <div style="background:rgba(220,53,69,0.1); border:1px solid rgba(220,53,69,0.3); border-radius:10px; padding:1.1rem; text-align:center;">
                <div style="font-size:0.65rem; color:#8892b0; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:0.35rem;">Major Incidents</div>
                <div style="font-size:2.5rem; font-weight:800; color:#ff6b6b; line-height:1;">{{ $incidentMonitoring['major'] ?? 0 }}</div>
            </div>
            <div style="background:rgba(25,135,84,0.1); border:1px solid rgba(46,204,113,0.3); border-radius:10px; padding:1.1rem; text-align:center;">
                <div style="font-size:0.65rem; color:#8892b0; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:0.35rem;">Minor Incidents</div>
                <div style="font-size:2.5rem; font-weight:800; color:#2ecc71; line-height:1;">{{ $incidentMonitoring['minor'] ?? 0 }}</div>
            </div>
        </div>

        {{-- Rainfall & Weather + Active Centers --}}
        <div style="display:grid; grid-template-columns: 1fr 1fr 0.6fr; gap:1rem; margin-bottom:1.25rem;">
            <div style="background:rgba(0,210,255,0.06); border:1px solid rgba(0,210,255,0.15); border-radius:10px; padding:1rem;">
                <div style="font-size:0.65rem; color:#00d2ff; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:0.75rem; font-weight:700;">📡 Daily Rainfall</div>
                <div style="display:flex; justify-content:space-around;">
                    <div style="text-align:center;">
                        <div style="font-size:0.65rem; color:#8892b0; margin-bottom:0.25rem;">Bangal Station</div>
                        <div style="font-size:2rem; font-weight:800; color:#00d2ff; line-height:1;">{{ $rainfall['bangal'] ?? '0.0' }}<span style="font-size:0.8rem; color:#8892b0;"> mm</span></div>
                    </div>
                    <div style="width:1px; background:rgba(0,210,255,0.15);"></div>
                    <div style="text-align:center;">
                        <div style="font-size:0.65rem; color:#8892b0; margin-bottom:0.25rem;">Kalaklan Station</div>
                        <div style="font-size:2rem; font-weight:800; color:#00d2ff; line-height:1;">{{ $rainfall['kalaklan'] ?? '0.0' }}<span style="font-size:0.8rem; color:#8892b0;"> mm</span></div>
                    </div>
                </div>
            </div>
            <div style="background:rgba(52,152,219,0.08); border:1px solid rgba(52,152,219,0.2); border-radius:10px; padding:1rem;">
                <div style="font-size:0.65rem; color:#00d2ff; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:0.75rem; font-weight:700;">🌩 Weather Forecast</div>
                <div style="font-size:1.6rem; font-weight:800; color:#ffffff; line-height:1; margin-bottom:0.25rem;">{{ $typhoonData->name ?? 'Moderate Rain' }}</div>
                <div style="font-size:0.82rem; color:#8892b0; margin-bottom:0.5rem;">{{ $typhoonData->temp ?? '28' }}°C &nbsp;|&nbsp; {{ $typhoonData->wind ?? '15' }} km/h Wind</div>
                <div style="font-size:0.72rem; background:rgba(255,183,3,0.12); border-radius:6px; padding:0.35rem 0.75rem; color:#f0b429; display:inline-block;">⚠ Storm Signal #1 Active</div>
            </div>
            <div style="background:rgba(0,210,255,0.06); border:1px solid rgba(0,210,255,0.15); border-radius:10px; padding:1rem; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                <div style="font-size:0.65rem; color:#8892b0; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:0.5rem; text-align:center;">Active Centers</div>
                <div style="font-size:3rem; font-weight:800; color:#00d2ff; line-height:1;">{{ $openEvacuationCentersCount ?? 0 }}</div>
            </div>
        </div>

        {{-- Evacuation Centers Table --}}
        <div style="margin-bottom:1rem;">
            <div style="font-size:0.7rem; color:#00d2ff; text-transform:uppercase; letter-spacing:2px; font-weight:700; margin-bottom:0.75rem; padding-bottom:0.5rem; border-bottom:1px solid rgba(0,210,255,0.2);">🏫 Evacuation Centers Status Monitoring</div>
            <table style="width:100%; border-collapse:collapse; font-size:0.82rem;">
                <thead>
                    <tr style="background:rgba(0,210,255,0.12);">
                        <th style="padding:0.6rem 0.75rem; text-align:left; color:#00d2ff; text-transform:uppercase; font-size:0.65rem; letter-spacing:1px;">Center / School</th>
                        <th style="padding:0.6rem 0.75rem; text-align:left; color:#00d2ff; text-transform:uppercase; font-size:0.65rem; letter-spacing:1px;">Location</th>
                        <th style="padding:0.6rem 0.75rem; text-align:center; color:#00d2ff; text-transform:uppercase; font-size:0.65rem; letter-spacing:1px;">Capacity</th>
                        <th style="padding:0.6rem 0.75rem; text-align:center; color:#00d2ff; text-transform:uppercase; font-size:0.65rem; letter-spacing:1px;">Occupancy</th>
                        <th style="padding:0.6rem 0.75rem; text-align:center; color:#00d2ff; text-transform:uppercase; font-size:0.65rem; letter-spacing:1px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($evacuationCenters ?? [] as $ec)
                    <tr style="border-bottom:1px solid rgba(255,255,255,0.05);">
                        <td style="padding:0.6rem 0.75rem; font-weight:700; color:#e2e8f0;">{{ $ec->school->school_name ?? $ec->identification ?? ('Center #' . $ec->id) }}</td>
                        <td style="padding:0.6rem 0.75rem; color:#8892b0; font-size:0.78rem;">{{ Str::limit($ec->location, 40) }}</td>
                        <td style="padding:0.6rem 0.75rem; text-align:center; color:#8892b0;">{{ $ec->capacity > 0 ? $ec->capacity : '∞' }}</td>
                        <td style="padding:0.6rem 0.75rem; text-align:center; font-weight:800; color:#00d2ff; font-size:1.05rem;">{{ $ec->current_occupancy }}</td>
                        <td style="padding:0.6rem 0.75rem; text-align:center;">
                            @php
                                $bc = $ec->usage_status === 'full' ? '#dc3545' : ($ec->usage_status === 'occupied' ? '#3498db' : '#28a745');
                                $bt = $ec->usage_status === 'full' ? 'FULL' : ($ec->usage_status === 'occupied' ? 'OCCUPIED' : 'CLEARED');
                            @endphp
                            <span style="background:{{ $bc }}22; color:{{ $bc }}; border:1px solid {{ $bc }}55; border-radius:50px; padding:0.2rem 0.8rem; font-size:0.7rem; font-weight:700; letter-spacing:0.5px;">{{ $bt }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center; color:#8892b0; padding:1.5rem;">No evacuation centers registered yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div style="margin-top:1.25rem; padding-top:0.9rem; border-top:1px solid rgba(0,210,255,0.12); display:flex; justify-content:space-between; align-items:center; font-size:0.68rem; color:#4a5568;">
            <span>Generated by DRRM Typhoon & Flood Reporting System</span>
            <span>{{ now()->format('Y') }} · DepEd DRRM Monitoring · Printed: {{ now()->format('M d, Y h:i A') }}</span>
        </div>
    </div>
</div>

{{-- Family Registration Modal (Already Updated) --}}
<div class="modal fade" id="familyRegistrationModal" tabindex="-1" aria-labelledby="familyRegistrationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('typhoon.families.store') }}" id="familyRegistrationForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--bg-dark); color: var(--accent-blue); border-bottom: 1px solid var(--glass-border);">
                    <h5 class="modal-title" id="familyRegistrationModalLabel">
                        <i class="fas fa-people-arrows me-2"></i> Register Family Evacuee
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Evacuation Center / School <span class="text-danger">*</span></label>
                        <select name="evacuation_center_id" id="modal_evacuation_center_id" class="form-select" required>
                            <option value="">-- Select Evacuation Center --</option>
                            @foreach($evacuationCenters ?? [] as $ec)
                                <option value="{{ $ec->id }}">
                                    {{ $ec->school->school_name ?? $ec->identification ?? ('Evacuation Center #' . $ec->id) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Family-level fields --}}
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h6 class="fw-bold text-primary"><i class="fas fa-user-tie"></i> Head of Family Details</h6>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-bold">Full Name (Head) <span class="text-danger">*</span></label>
                            <input type="text" name="head_family_name" id="input_head_name" class="form-control" placeholder="Full name of head" required
                                oninput="document.getElementById('hidden_head_name').value = this.value">
                            <input type="hidden" name="members[0][full_name]" id="hidden_head_name">
                            <input type="hidden" name="members[0][is_head]" value="1">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label small fw-bold">Age <span class="text-danger">*</span></label>
                            <input type="number" name="members[0][age]" class="form-control" placeholder="Age" required min="0" max="150">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label small fw-bold">Gender <span class="text-danger">*</span></label>
                            <select name="members[0][gender]" class="form-select" required>
                                <option value="">Select...</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label small fw-bold">Individual Needs (Optional)</label>
                            <input type="text" name="members[0][needs]" class="form-control" placeholder="Specific needs for the head (e.g. medication)">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Collective Family Needs (Optional)</label>
                            <textarea name="collective_needs" class="form-control" rows="1" placeholder="e.g. Rice, medicine, blankets (shared items)"></textarea>
                        </div>
                    </div>

                    <div class="mb-3 p-2 bg-light rounded">
                        <label class="form-label fw-bold small">Family Vulnerabilities / Special Concerns</label>
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="has_pregnant" value="1" id="flagPregnant">
                                <label class="form-check-label" for="flagPregnant">Pregnant</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="has_pwd" value="1" id="flagPwd">
                                <label class="form-check-label" for="flagPwd">PWD</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="has_senior" value="1" id="flagSenior">
                                <label class="form-check-label" for="flagSenior">Senior Citizen</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="has_lactating" value="1" id="flagLactating">
                                <label class="form-check-label" for="flagLactating">Lactating</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="has_child_under5" value="1" id="flagChild">
                                <label class="form-check-label" for="flagChild">Child under 5</label>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-bold mb-0">Other Family Members</label>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-member-btn">
                            <i class="fas fa-plus"></i> Add Member
                        </button>
                    </div>
                    
                    <div id="family-members-container">
                        {{-- Dynamic members start here --}}
                    </div>

                    <hr>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="confirm_check_in" id="confirmCheckIn" checked>
                        <label class="form-check-label" for="confirmCheckIn">
                            Check-in this family now (sets current date/time)
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background-color: #1B4C6D; color: white;">
                        <i class="fas fa-save"></i> Register Family
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@php
    $chartData = $evacuationCenters->map(function($ec) {
        return [
            'name' => \Illuminate\Support\Str::limit($ec->school->school_name ?? $ec->identification ?? 'Center #'.$ec->id, 15),
            'occupancy' => $ec->current_occupancy,
        ];
    })->values();
@endphp

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Setup Chart
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('occupancyChart').getContext('2d');
        
        // Prepare Data using robust JSON serialization
        const rawData = @json($chartData);

        const labels = rawData.map(d => d.name);
        const dataOccupancy = rawData.map(d => d.occupancy);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Current Occupancy',
                    data: dataOccupancy,
                    backgroundColor: 'rgba(0, 210, 255, 0.5)',
                    borderColor: 'rgba(0, 210, 255, 1)',
                    borderWidth: 2,
                    borderRadius: 5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grace: '10%',
                        grid: {
                            color: '#f1f5f9'
                        },
                        ticks: {
                            color: '#64748b'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#64748b'
                        }
                    }
                }
            }
        });
    });

    // 2. Family Modal Logic
    const familyModalEl = document.getElementById('familyRegistrationModal');
    if (familyModalEl) {
        familyModalEl.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const select = familyModalEl.querySelector('#modal_evacuation_center_id');
            if (button && select) {
                const ecId = button.getAttribute('data-ec-id');
                if (ecId) {
                    select.value = ecId;
                }
            }
        });
    }

    // Dynamic family members
    let memberIndex = 1;
    const addMemberBtn = document.getElementById('add-member-btn');
    if(addMemberBtn) {
        addMemberBtn.addEventListener('click', function() {
            const container = document.getElementById('family-members-container');
            const newRow = document.createElement('div');
            newRow.className = 'row g-2 mb-2 member-row border-bottom pb-2';
            newRow.innerHTML = `
                <div class="col-md-4">
                    <input type="text" name="members[${memberIndex}][full_name]" class="form-control" placeholder="Full name" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="members[${memberIndex}][age]" class="form-control" placeholder="Age" required min="0">
                </div>
                <div class="col-md-2">
                    <select name="members[${memberIndex}][gender]" class="form-select" required>
                        <option value="">Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="members[${memberIndex}][needs]" class="form-control" placeholder="Individual needs">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-member">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <input type="hidden" name="members[${memberIndex}][is_head]" value="0">
            `;
            container.appendChild(newRow);
            memberIndex++;

            newRow.querySelector('.remove-member').addEventListener('click', function() {
                newRow.remove();
            });
        });
    }

    // 3. Social Print Function
    function printSocialCard() {
        const card = document.getElementById('printCard');
        if (!card) return;

        const html = `<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Typhoon & Flood Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        @page { margin: 0; size: A4 landscape; }
        html, body {
            width: 100%;
            height: 100%;
            background: #0a1128 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            font-family: 'Rajdhani', 'Space Grotesk', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #printCard {
            width: 100%;
            max-width: 100%;
            border-radius: 0 !important;
            box-shadow: none !important;
            border: none !important;
            margin: 0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        @media print {
            @page { margin: 0; size: A4 landscape; }
        }
    </style>
</head>
<body>
    ${card.outerHTML}
    <script>
        window.onload = function() {
            window.print();
            setTimeout(function() { window.close(); }, 500);
        };
    <\/script>
</body>
</html>`;

        const printWin = window.open('', '_blank', 'width=1100,height=780');
        if (!printWin) {
            alert('Pop-up blocked! Please allow pop-ups for this page and try again.');
            return;
        }
        printWin.document.open();
        printWin.document.write(html);
        printWin.document.close();
    }
</script>
@endpush

@endsection