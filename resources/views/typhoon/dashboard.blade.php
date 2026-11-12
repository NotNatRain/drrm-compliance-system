{{-- resources/views/typhoon/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Evacuation Monitoring')
@section('hide_main_nav', '1')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --bg-dark: #0a1128;
        --card-bg: #ffffff;
        --card-header-bg: #0f2154ff;
        --accent-blue: #00d2ff;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --glass-border: rgba(0, 0, 0, 0.05);
        --font-display: 'Sora', sans-serif;
        --font-body: 'Inter', sans-serif;
    }

    .search-bar-container input::placeholder {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    .search-bar-container input:focus {
        background-color: rgba(255, 255, 255, 0.15) !important;
        border-color: rgba(255, 255, 255, 0.5) !important;
        color: white !important;
        box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.1) !important;
        outline: none;
    }
    .search-bar-container i {
        color: rgba(255, 255, 255, 0.7) !important;
    }

    body {
        background-color: var(--bg-dark) !important;
        background-image: radial-gradient(circle at 50% 50%, #112240 0%, #0a1128 100%);
        color: var(--text-dark);
        font-family: var(--font-body);
    }

    h1, h2, h3, h4, h5, .card-header-custom, .stat-value, .fw-bold {
        font-family: var(--font-display);
        letter-spacing: 0.5px;
    }

    /* ── Full-screen context for dashboard ── */
    html {
        font-size: 80%;
    }
    html, body {
        height: 100%;
    }
    body > div,
    body > div > main {
        height: 100%;
    }
    /* Override app layout's py-4 padding for this page */
    main.py-4 {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
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

    .occupancy-filter-btn {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.35);
        color: #ffffff;
        font-size: 0.75rem;
        letter-spacing: 0.4px;
        text-transform: uppercase;
    }

    .occupancy-filter-btn:hover,
    .occupancy-filter-btn:focus {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    .occupancy-filter-menu {
        min-width: 230px;
        border: 1px solid #dbe7f5;
        box-shadow: 0 12px 24px rgba(15, 33, 84, 0.15);
        padding: 0.75rem;
    }

    .occupancy-chart-panel {
        flex: 1 1 auto;
        min-height: 0;
    }

    .occupancy-chart-scroll {
        min-width: 100%;
        height: 100%;
        position: relative;
    }

    .occupancy-summary {
        margin-top: 0.35rem !important;
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
     * 70/30 HYBRID MOBILE APPROACH â€” Typhoon Dashboard
     * Desktop layout preserved. Minimal tweaks only:
     *  1. Scrollable tables
     *  2. Slightly larger button tap targets
     *  3. Stack form columns in modals/forms
     * ==================================================== */
    /* Triggered by 1024px viewport lock â€” desktop layout preserved but mobile enhancements active */
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

    /* System Status Pulse */
    .status-pulse {
        width: 12px;
        height: 12px;
        background: #22c55e;
        border-radius: 50%;
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }
    
    .btn-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    /* Centered Navigation */
    .header-nav-center {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        background: rgba(255, 255, 255, 0.05);
        padding: 0.5rem 2rem;
        border-radius: 50px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
    }

    .nav-link-custom {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        font-family: var(--font-display);
        font-weight: 700;
        font-size: 1.1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        background: none;
        border: none;
        padding: 0.5rem 0.25rem;
    }

    .nav-link-custom:hover {
        color: var(--accent-blue);
    }

    .nav-link-custom.active {
        color: var(--accent-blue);
        text-shadow: 0 0 15px rgba(0, 210, 255, 0.5);
    }

    .notif-btn-custom {
        position: relative;
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.25rem;
        transition: all 0.3s ease;
    }

    .notif-btn-custom:hover, .notif-btn-custom.active {
        color: var(--accent-blue);
    }

    .school-btn-custom {
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.25rem;
        transition: all 0.3s ease;
        background: none;
        border: none;
    }

    .school-btn-custom:hover, .school-btn-custom.active {
        color: var(--accent-blue);
    }
    
    /* Sidebar Core Styles */
    .dashboard-wrapper {
        display: flex;
        width: 100%;
        height: 100vh;
    }

    .app-sidebar {
        width: 280px;
        background-color: var(--sidebar-bg);
        border-right: 1px solid rgba(255, 255, 255, 0.05);
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
    }

    .sidebar-header {
        padding: 24px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .sidebar-brand {
        font-family: 'Rajdhani', sans-serif;
        font-weight: 700;
        font-size: 1.25rem;
        color: #ffffff;
        line-height: 1.2;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .sidebar-brand span {
        color: var(--accent-blue);
        display: block;
        font-size: 0.85rem;
        font-weight: 500;
        letter-spacing: 1px;
    }

    .sidebar-menu {
        list-style: none;
        padding: 20px 12px;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 16px;
        color: rgba(255, 255, 255, 0.6);
        text-decoration: none;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 600;
        font-size: 1.05rem;
        letter-spacing: 0.5px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .sidebar-link i {
        font-size: 1.1rem;
        width: 20px;
        text-align: center;
    }

    .sidebar-link:hover {
        background: rgba(255, 255, 255, 0.03);
        color: #ffffff;
    }

    .sidebar-link.active {
        background: rgba(0, 210, 255, 0.1);
        color: var(--accent-blue);
        text-shadow: 0 0 10px rgba(0, 210, 255, 0.3);
    }

    .sidebar-link-toggle {
        width: 100%;
        border: 0;
        background: transparent;
        cursor: pointer;
        text-align: left;
    }

    .sidebar-link-toggle .sidebar-chevron {
        margin-left: auto;
        font-size: 0.75rem;
        transition: transform 0.2s ease;
    }

    .sidebar-link-toggle[aria-expanded="true"] .sidebar-chevron {
        transform: rotate(180deg);
    }

    .sidebar-submenu {
        list-style: none;
        padding: 4px 0 0 34px;
        margin: 0;
    }

    .sidebar-submenu a,
    .sidebar-submenu button {
        display: block;
        width: 100%;
        padding: 8px 12px;
        border: 0;
        border-radius: 6px;
        background: transparent;
        color: rgba(255, 255, 255, 0.55);
        text-align: left;
        text-decoration: none;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
    }

    .sidebar-submenu a:hover,
    .sidebar-submenu button:hover {
        background: rgba(255, 255, 255, 0.05);
        color: var(--accent-blue);
    }

    /* Right Main Content Panel */
    .content-panel {
        flex-grow: 1;
        min-width: 0;
        height: 100vh;
        overflow-y: auto;
    }

</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        {{-- Left Side --}}
        <div class="d-flex align-items-center" style="width: 30%;">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-info border-0 me-3" title="Back">
                <i class="fas fa-chevron-left fa-lg"></i>
            </a>
            <div>
                <h1 class="h3 mb-0 fw-bold text-white">
                    <i class="fas fa-satellite-dish me-2" style="color: var(--accent-blue);"></i>
                    Evacuation Management System
                </h1>
            </div>
        </div>


        
        {{-- Centered Navigation --}}
        <div class="header-nav-center">
            <a href="{{ route('typhoon.dashboard') }}" class="nav-link-custom active">
                Dashboard
            </a>
            <a href="{{ route('typhoon.notifications') }}" class="notif-btn-custom" title="Notifications">
                <i class="fas fa-bell"></i>
                @php
                    $unreadCount = \App\Models\FireSafetyNotification::forCompliance('typhoon_flood')->unread()->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.6rem; padding: 0.35em 0.65em;">
                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                    </span>
                @endif
            </a>
            <button type="button" class="school-btn-custom" data-bs-toggle="modal" data-bs-target="#chooseSchoolModal" title="Choose Evacuation Center">
                <i class="fas fa-school"></i>
            </button>
        </div>

        {{-- Right Side --}}
        <div class="d-flex align-items-center gap-3 justify-content-end" style="width: 30%;">
            <div class="text-white-50 small text-end">
                <div class="fw-bold text-white">{{ now()->format('M d, Y') }}</div>
                <div>{{ now()->format('h:i A') }}</div>
            </div>
            <div class="btn-group shadow-lg">
                <button class="btn btn-success px-3 fw-bold" onclick="document.getElementById('socialPrintModal').style.display='flex'" title="Send to Social">
                    <i class="fas fa-share-alt"></i>
                </button>
                @if(auth()->user()->role === 'admin')
                <button type="button" class="btn btn-warning text-white px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#announceSomethingModal">
                    <i class="fas fa-bullhorn"></i>
                </button>
                @endif
            </div>
        </div>
    </div>

{{-- Main Row spanning 100% width --}}
<div class="d-flex w-100 min-vh-100" style="overflow-x: hidden;">

    
    <!-- Left Navigation Sidebar -->
    <div class="app-sidebar">
        <div class="sidebar-header">
        <ul class="sidebar-menu">
            <li>
                <a href="#" class="sidebar-link active">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="#" class="sidebar-link">
                    <i class="fas fa-school"></i> Evacuation Centers
                </a>
            </li>
            <li>
                <button type="button" class="sidebar-link sidebar-link-toggle" data-bs-toggle="collapse" data-bs-target="#familiesSidebarMenu" aria-expanded="false" aria-controls="familiesSidebarMenu">
                    <i class="fas fa-users"></i>
                    <span>Families &amp; Individuals</span>
                    <i class="fas fa-chevron-down sidebar-chevron"></i>
                </button>
                <ul id="familiesSidebarMenu" class="sidebar-submenu collapse">
                    <li>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#familyRegistrationModal">
                            <i class="fas fa-user-plus me-2"></i> Register Family
                        </button>
                    </li>
                    <li>
                        <a href="#evacuationCentersTableBody">
                            <i class="fas fa-list me-2"></i> View Registered Families
                        </a>
                    </li>
                    <li>
                        <a href="dswdform.html">
                            <i class="fas fa-file-alt me-2"></i> DSWD Form
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#" class="sidebar-link">
                    <i class="fas fa-box-open"></i> Resource Inventory
                </a>
            </li>
            <li>
                <a href="#" class="sidebar-link">
                    <i class="fas fa-file-alt"></i> Incident Reports
                </a>
            </li>
        </ul>
    </div>

</div>

 <div class="flex-grow-1 p-4 d-flex flex-column gap-4" style="background-color: #0a1128; min-width: 0;">
            <div class="row g-3">
                  {{-- Left Card: Estimated Affected Population --}} 
    <div class="col-md-6"> 
        <div class="dashboard-card h-100"> 
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

    {{-- Right Card: Occupancy Overview --}} 
    <div class="col-md-6"> 
        <div class="dashboard-card d-flex flex-column h-100"> 
            <div class="card-header-custom justify-content-between"> 
                <div class="d-flex align-items-center"> 
                    <i class="fas fa-chart-pie"></i>Occupancy Overview 
                </div> 
                <div class="dropdown"> 
                    <button class="btn btn-sm occupancy-filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"> 
                        <i class="fas fa-filter"></i> Filter 
                    </button> 
                    <div class="dropdown-menu occupancy-filter-menu"> 
                        <div class="mb-2"> 
                            <label for="occupancySortOrder" class="form-label mb-1 small text-uppercase text-muted fw-semibold">Sort By</label> 
                            <select id="occupancySortOrder" class="form-select form-select-sm"> 
                                <option value="alphabetical">Alphabetical</option> 
                                <option value="highest">Highest to Lowest</option> 
                                <option value="newest">Newest to Lowest</option> 
                            </select> 
                        </div> 
                        <div> 
                            <div class="small text-uppercase text-muted fw-semibold mb-1">Direction</div> 
                            <div class="form-check"> 
                                <input class="form-check-input" type="radio" name="occupancyDirection" id="occupancyDirectionLtr" value="ltr" checked> 
                                <label class="form-check-label small" for="occupancyDirectionLtr">Left to Right</label> 
                            </div> 
                            <div class="form-check"> 
                                <input class="form-check-input" type="radio" name="occupancyDirection" id="occupancyDirectionRtl" value="rtl"> 
                                <label class="form-check-label small" for="occupancyDirectionRtl">Right to Left</label> 
                            </div> 
                        </div> 
                    </div> 
                </div> 
            </div> 
            <div class="p-2 flex-grow-1 d-flex flex-column justify-content-between"> 
                <div class="occupancy-chart-panel" style="position: relative; overflow-x: auto; width: 100%;"> 
                    <div id="occupancyChartScroll" class="occupancy-chart-scroll" style="width: 100%;"> 
                        <canvas id="occupancyChart" style="width: 100%; min-height: 200px;"></canvas> 
                    </div> 
                </div> 
                <div class="occupancy-summary"> 
                    <div class="d-flex justify-content-between small mb-1"> 
                        <span class="text-muted">Total System Capacity</span> 
                        <span class="fw-bold">{{ $totalSystemCapacity ?? 0 }}</span> 
                    </div> 
                    <div class="progress" style="height: 8px; background: #f1f5f9;"> 
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 100%;"></div> 
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

                  <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Family Encoding Mode <span class="text-danger">*</span></label>
                            <select name="registration_mode" id="familyRegistrationMode" class="form-select" required>
                                <option value="new" selected>Encode new family</option>
                                <option value="existing">Register existing</option>
                            </select>
                            <input type="hidden" name="existing_family_id" id="existingFamilyId" value="">
                        </div>
                        <div class="col-md-6 d-none" id="existingFamilySelectorWrap">
                            <label class="form-label small fw-bold">Registered Family in This Center</label>
                            <select id="existingFamilySelect" class="form-select">
                                <option value="">-- Select existing family --</option>
                            </select>
                            <small class="text-muted">Families registered in any evacuation center are available for selection.</small>
                        </div>
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
                        <!-- Head of Family Vulnerability Tags -->
                        <div class="col-12 vulnerability-wrapper mt-2">
                            <div class="p-2 bg-light rounded border">
                                <label class="form-label fw-bold small mb-1">Head Vulnerabilities / Special Concerns</label>
                                <select class="form-select form-select-sm mb-2 vulnerability-selector">
                                    <option value="">-- Add Concern --</option>
                                    <option value="flagPregnant">Pregnant</option>
                                    <option value="flagPwd">PWD</option>
                                    <option value="flagSenior">Senior Citizen</option>
                                    <option value="flagLactating">Lactating</option>
                                    <option value="flagChild">Child Under 5</option>
                                </select>
                                <div class="vulnerability-tags-container d-flex flex-wrap gap-2"></div>
                                <div class="d-none">
                                    <input class="form-check-input vulnerability-checkbox flagPregnant" type="checkbox" name="has_pregnant" value="1" id="flagPregnant">
                                    <input class="form-check-input vulnerability-checkbox flagPwd" type="checkbox" name="has_pwd" value="1" id="flagPwd">
                                    <input class="form-check-input vulnerability-checkbox flagSenior" type="checkbox" name="has_senior" value="1" id="flagSenior">
                                    <input class="form-check-input vulnerability-checkbox flagLactating" type="checkbox" name="has_lactating" value="1" id="flagLactating">
                                    <input class="form-check-input vulnerability-checkbox flagChild" type="checkbox" name="has_child_under5" value="1" id="flagChild">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <label class="form-label small fw-bold">Collective Family Needs <span class="text-danger">*</span></label>
                            <div class="family-needs-builder" data-family-needs-builder="create" data-need-options='@json($familyNeedOptions ?? [])' data-existing-needs='[]'></div>
                            <small class="text-muted d-block mt-2">Choose a need and quantity. Selecting <strong>Others Please Specify</strong> will reveal a custom need field.</small>
                        </div>
                    </div>

    {{-- â”€â”€ MAIN BODY: Sidebar + Center + Right â”€â”€ --}}
    <div class="evac-body">

        {{-- â”€â”€ LEFT SIDEBAR â”€â”€ --}}
        <aside class="evac-sidebar">
            <nav class="evac-nav">

                <a href="#" class="evac-nav-item" id="evac-nav-incident">
                    <div class="evac-nav-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <span class="evac-nav-label">Incident</span>
                </a>

                {{-- Register with dropdown --}}
                <div class="evac-nav-item evac-nav-has-sub" id="evac-nav-register-wrap">
                    <div class="evac-nav-item-inner" id="evac-nav-register-toggle">
                        <div class="evac-nav-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <span class="evac-nav-label">Register</span>
                        <i class="fas fa-chevron-down evac-nav-caret" id="evac-register-caret"></i>
                    </div>
                    <div class="evac-nav-sub" id="evac-nav-register-sub">
                        <a href="#" class="evac-nav-sub-item" id="registerNavNew">
                            <i class="fas fa-arrow-right evac-sub-arrow"></i> New
                        </a>
                        <a href="#" class="evac-nav-sub-item" id="registerNavExisting">
                            <i class="fas fa-arrow-right evac-sub-arrow"></i> Existing
                        </a>
                    </div>
                </div>

                <a href="#" class="evac-nav-item" id="evac-nav-school" data-bs-toggle="modal" data-bs-target="#chooseSchoolModal">
                    <div class="evac-nav-icon">
                        <i class="fas fa-school"></i>
                    </div>
                    <span class="evac-nav-label">School</span>
                </a>

                <a href="#" class="evac-nav-item" id="evac-nav-remarks">
                    <div class="evac-nav-icon">
                        <i class="fas fa-comment-dots"></i>
                    </div>
                </div>
<!--work in progress-->

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
@endif
@include('typhoon.family-registration-modal')

            </nav>
        </aside>

        {{-- â”€â”€ CENTER COLUMN â”€â”€ --}}
        <main class="evac-main">

            {{-- â”€â”€ SCHOOL STATUS / UPDATES (Typhoon Alert Banner) â”€â”€ --}}
            <section class="evac-panel evac-panel-alert">
                <div class="evac-panel-header">
                    <i class="fas fa-broadcast-tower"></i>
                    <span>School Status / Updates</span>
                </div>
                <div class="evac-panel-body">
                    @if(!empty($activeTyphoon))
                        @php
                            $signalColors = [
                                1 => ['bg' => '#f59e0b', 'border' => '#d97706', 'glow' => 'rgba(245,158,11,0.35)'],
                                2 => ['bg' => '#f97316', 'border' => '#ea580c', 'glow' => 'rgba(249,115,22,0.35)'],
                                3 => ['bg' => '#ef4444', 'border' => '#dc2626', 'glow' => 'rgba(239,68,68,0.35)'],
                                4 => ['bg' => '#9333ea', 'border' => '#7c3aed', 'glow' => 'rgba(147,51,234,0.35)'],
                                5 => ['bg' => '#0f172a',  'border' => '#00d2ff', 'glow' => 'rgba(0,210,255,0.35)'],
                            ];
                            $sc = $signalColors[$activeTyphoon['signal']] ?? $signalColors[1];
                        @endphp
                        <div class="evac-typhoon-alert" style="background: linear-gradient(135deg, {{ $sc['bg'] }} 0%, {{ $sc['border'] }} 100%); box-shadow: 0 0 30px {{ $sc['glow'] }};">
                            {{-- Animated storm rings --}}
                            <div class="evac-storm-ring evac-storm-ring-1"></div>
                            <div class="evac-storm-ring evac-storm-ring-2"></div>

                            <div class="evac-alert-content">
                                <div class="evac-alert-left">
                                    <div class="evac-storm-emoji">ðŸŒ€</div>
                                    <div class="evac-alert-text">
                                        <div class="evac-alert-sub">âš  PAGASA Active Weather Alert â€” Directly Affecting Olongapo City Area</div>
                                        <div class="evac-alert-title">
                                            Effects of <span class="evac-alert-cat">{{ $activeTyphoon['category'] }}</span>
                                            "<span class="evac-alert-name">{{ $activeTyphoon['name'] }}</span>"
                                        </div>
                                        <div class="evac-alert-meta">
                                            <span><i class="fas fa-map-marker-alt me-1"></i> Near <strong>Olongapo City Â· Zambales</strong></span>
                                            <span>|</span>
                                            <span><i class="fas fa-ruler-combined me-1"></i> ~{{ $activeTyphoon['distance_km'] ?? '?' }} km from Olongapo</span>
                                            <span>|</span>
                                            <span><i class="fas fa-wind me-1"></i> {{ $activeTyphoon['wind_kph'] > 0 ? $activeTyphoon['wind_kph'].' km/h' : '--' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="evac-alert-right">
                                    <div class="evac-signal-label">TCWS Level</div>
                                    <div class="evac-signal-badge">#{{ $activeTyphoon['signal'] }}</div>
                                    <div class="evac-signal-sub">Signal No.</div>
                                    <div class="evac-signal-source">Source: GDACS Â· Auto-refreshes every 30 min</div>
                                </div>
                            </div>
                            <div class="evac-alert-stripe">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                <strong>DepEd Reminder:</strong>
                                Classes in affected areas are automatically suspended under Tropical Cyclone Wind Signal {{ $activeTyphoon['signal'] >= 1 ? '#'.$activeTyphoon['signal'] : '' }}.
                                All DepEd-Zambales schools must activate their DRRM protocols immediately.
                            </div>
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div class="evac-empty-state">
                            <div class="evac-empty-icon">
                                <i class="fas fa-sun"></i>
                                <i class="fas fa-cloud evac-empty-cloud"></i>
                            </div>
                            <div class="evac-empty-title">No Active Typhoon Alerts</div>
                            <div class="evac-empty-sub">Weather conditions are currently normal. No tropical cyclone threats detected in the area.</div>
                        </div>
                    @endif
                </div>
            </section>

            {{-- â”€â”€ CURRENT STATUS OF SCHOOL â”€â”€ --}}
            <section class="evac-panel evac-panel-schools">
                <div class="evac-panel-header">
                    <i class="fas fa-map-marked-alt"></i>
                    <span>Current Status of School</span>
                    <div class="dropdown ms-auto">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle d-flex align-items-center gap-2" type="button" id="statusFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 20px; padding: 0.4rem 1rem;">
                            <i class="fas fa-filter"></i> <span id="currentStatusFilterText">All Status</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="statusFilterDropdown" style="border-radius: 12px; min-width: 150px; padding: 0.5rem;">
                            <li><a class="dropdown-item d-flex align-items-center justify-content-between filter-status-btn mb-1 rounded" href="#" data-status="all"><span>All Status</span><i class="fas fa-check text-primary check-icon"></i></a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item d-flex align-items-center justify-content-between filter-status-btn mb-1 rounded" href="#" data-status="cleared"><span class="evac-badge evac-badge-cleared w-100 text-center me-2" style="padding: 4px 8px;">CLEARED</span><i class="fas fa-check text-primary check-icon d-none"></i></a></li>
                            <li><a class="dropdown-item d-flex align-items-center justify-content-between filter-status-btn mb-1 rounded" href="#" data-status="occupied"><span class="evac-badge evac-badge-occupied w-100 text-center me-2" style="padding: 4px 8px;">OCCUPIED</span><i class="fas fa-check text-primary check-icon d-none"></i></a></li>
                            <li><a class="dropdown-item d-flex align-items-center justify-content-between filter-status-btn mb-1 rounded" href="#" data-status="full"><span class="evac-badge evac-badge-full w-100 text-center me-2" style="padding: 4px 8px;">FULL</span><i class="fas fa-check text-primary check-icon d-none"></i></a></li>
                            <li><a class="dropdown-item d-flex align-items-center justify-content-between filter-status-btn rounded" href="#" data-status="decamp"><span class="evac-badge evac-badge-decamp w-100 text-center me-2" style="padding: 4px 8px;">DECAMP</span><i class="fas fa-check text-primary check-icon d-none"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="evac-schools-table-wrap">
                    <table class="evac-schools-table" id="evacSchoolsTable">
                        <thead>
                            <tr>
                                <th class="th-name">Name of School</th>
                                <th class="th-status">Status</th>
                                <th class="th-families">No. Family</th>
                                <th class="th-individuals">No. Individual</th>
                            </tr>
                        </thead>
                        <tbody id="evacSchoolsTbody">
                            @forelse($evacuationCenters ?? [] as $ec)
                            <tr class="evac-school-row {{ $ec->is_cluster_priority ? 'evac-cluster-row' : '' }}"
                                data-href="{{ route('typhoon.evacuation-center.show', $ec->id) }}"
                                data-priority="{{ $ec->is_cluster_priority ? '1' : '0' }}"
                                onclick="window.location=this.dataset.href">
                                <td class="td-name">
                                    @if($ec->is_cluster_priority)
                                        <span class="evac-cluster-pin" title="Clustered / Priority School"><i class="fas fa-star"></i></span>
                                    @endif
                                    <span class="evac-school-name">{{ $ec->school_name ?? $ec->identification ?? ('Center #' . $ec->id) }}</span>
                                </td>
                                <td class="td-status">
                                    @php
                                        $st = $ec->usage_status ?? 'cleared';
                                        $statusMap = [
                                            'cleared'  => ['cls' => 'evac-badge-cleared',  'label' => 'CLEARED'],
                                            'occupied' => ['cls' => 'evac-badge-occupied', 'label' => 'OCCUPIED'],
                                            'full'     => ['cls' => 'evac-badge-full',     'label' => 'FULL'],
                                            'decamp'   => ['cls' => 'evac-badge-decamp',   'label' => 'DECAMP'],
                                        ];
                                        $sm = $statusMap[$st] ?? $statusMap['cleared'];
                                    @endphp
                                    <span class="evac-badge {{ $sm['cls'] }}">{{ $sm['label'] }}</span>
                                </td>
                                <td class="td-num">{{ $ec->families_count ?? 0 }}</td>
                                <td class="td-num">{{ $ec->current_occupancy ?? 0 }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="evac-empty-row">
                                    <i class="fas fa-school me-2 opacity-50"></i> No evacuation centers registered.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Footer --}}
                <div class="evac-pagination-bar">
                    <button class="evac-page-btn" id="evacPrevPage" disabled>
                        <i class="fas fa-chevron-left"></i> Prev
                    </button>
                    <span class="evac-page-info" id="evacPageInfo">Page 1 of â€”</span>
                    <button class="evac-page-btn" id="evacNextPage">
                        Next <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </section>

        </main>

        {{-- â”€â”€ RIGHT SIDEBAR â”€â”€ --}}
        <aside class="evac-right-sidebar">

            {{-- Guidelines Panel --}}
            <div class="evac-right-panel evac-guidelines-panel">
                <div class="evac-right-panel-body">
                    @if(!empty($activeTyphoon))
                        @php $sig = $activeTyphoon['signal']; @endphp
                        <div class="evac-guidelines-active">
                            <div class="evac-guideline-alert-icon">ðŸŒ€</div>
                            <div class="evac-guideline-signal-badge">Signal #{{ $sig }}</div>
                            <div class="evac-guideline-title">Active Typhoon Protocols</div>

                            <ul class="evac-guideline-list">
                                @if($sig >= 1)
                                    <li><i class="fas fa-ban text-warning me-2"></i><strong>Classes Suspended</strong> in all affected areas under Signal #{{ $sig }}.</li>
                                @endif
                                @if($sig >= 2)
                                    <li><i class="fas fa-door-open text-info me-2"></i><strong>Open Evacuation Centers</strong> â€” Coordinate with Barangay DRRM Officers.</li>
                                    <li><i class="fas fa-first-aid text-danger me-2"></i>Activate <strong>Emergency Response Teams</strong>.</li>
                                @endif
                                @if($sig >= 3)
                                    <li><i class="fas fa-broadcast-tower text-danger me-2"></i>Establish <strong>Emergency Communication Lines</strong>.</li>
                                    <li><i class="fas fa-truck text-warning me-2"></i>Pre-position <strong>relief goods</strong> at staging areas.</li>
                                @endif
                                @if($sig >= 4)
                                    <li><i class="fas fa-exclamation-triangle text-danger me-2"></i><strong>Mandatory evacuation</strong> of high-risk zones.</li>
                                @endif
                                <li><i class="fas fa-clipboard-check text-success me-2"></i>Submit <strong>DRRM Situation Report</strong> every 6 hours.</li>
                            </ul>

                            <div class="evac-guideline-footer">
                                <i class="fas fa-info-circle me-1"></i> Per DepEd-Zambales DRRM Guidelines
                            </div>
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div class="evac-right-empty">
                            <div class="evac-right-empty-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="evac-right-empty-title">No Active Alerts</div>
                            <div class="evac-right-empty-sub">
                                Guidelines will appear here when there is an active typhoon threat.
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Future Use Panel --}}
            <div class="evac-right-panel evac-future-panel">
                <div class="evac-right-panel-body evac-right-empty" style="min-height: 200px;">
                    <div class="evac-right-empty-icon" style="opacity:0.25;">
                        <i class="fas fa-cube"></i>
                    </div>
                    <div class="evac-right-empty-title" style="opacity:0.3;">Reserved</div>
                    <div class="evac-right-empty-sub" style="opacity:0.25;">This section is reserved for future features.</div>
                </div>
            </div>

        </aside>

    </div>{{-- /.evac-body --}}
</div>{{-- /.evac-dashboard-root --}}

{{-- â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ SHARED MODALS & PARTIALS â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
@include('typhoon.partials.choose-school-modal')
@include('typhoon.partials.create-evac-center-modal')
@include('typhoon.FamilyModal')






{{-- ===================== SOCIAL / PRINT OVERLAY ===================== --}}
<div id="socialPrintModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.92); z-index:9999; flex-direction:column; align-items:center; justify-content:flex-start; overflow-y:auto; padding: 1.5rem 0;">

    {{-- ACTION BUTTONS â€” outside the printable card --}}
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
    <div id="printCard" style="background: linear-gradient(135deg, #0a1128 0%, #112240 55%, #0d2137 100%); color: #e2e8f0; width: 1100px; max-width: 96vw; border-radius: 16px; box-shadow: 0 0 80px rgba(0,210,255,0.15); border: 1px solid rgba(0,210,255,0.2); font-family: 'Sora', 'Inter', sans-serif; padding: 2.25rem; margin-bottom: 2rem;">

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
                <div style="font-size:0.72rem; color:#8892b0; margin-top:0.35rem; letter-spacing:0.5px;">ðŸ“… {{ now()->format('F d, Y  â€”  h:i A') }}</div>
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
                <div style="font-size:0.65rem; color:#00d2ff; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:0.75rem; font-weight:700;">ðŸ“¡ Daily Rainfall</div>
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
                <div style="font-size:0.65rem; color:#00d2ff; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:0.75rem; font-weight:700;">ðŸŒ© Weather Forecast</div>
                <div style="font-size:1.6rem; font-weight:800; color:#ffffff; line-height:1; margin-bottom:0.25rem;">{{ $typhoonData->name ?? 'Moderate Rain' }}</div>
                <div style="font-size:0.82rem; color:#8892b0; margin-bottom:0.5rem;">{{ $typhoonData->temp ?? '28' }}Â°C &nbsp;|&nbsp; {{ $typhoonData->wind ?? '15' }} km/h Wind</div>
                <div style="font-size:0.72rem; background:rgba(255,183,3,0.12); border-radius:6px; padding:0.35rem 0.75rem; color:#f0b429; display:inline-block;">âš  Storm Signal #1 Active</div>
            </div>
            <div style="background:rgba(0,210,255,0.06); border:1px solid rgba(0,210,255,0.15); border-radius:10px; padding:1rem; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                <div style="font-size:0.65rem; color:#8892b0; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:0.5rem; text-align:center;">Active Centers</div>
                <div style="font-size:3rem; font-weight:800; color:#00d2ff; line-height:1;">{{ $openEvacuationCentersCount ?? 0 }}</div>
            </div>
        </div>

        {{-- Evacuation Centers Table --}}
        <div style="margin-bottom:1rem;">
            <div style="font-size:0.7rem; color:#00d2ff; text-transform:uppercase; letter-spacing:2px; font-weight:700; margin-bottom:0.75rem; padding-bottom:0.5rem; border-bottom:1px solid rgba(0,210,255,0.2);">ðŸ« Evacuation Centers Status Monitoring</div>
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
                        <td style="padding:0.6rem 0.75rem; font-weight:700; color:#e2e8f0;">{{ $ec->school_name ?? $ec->identification ?? ('Center #' . $ec->id) }}</td>
                        <td style="padding:0.6rem 0.75rem; color:#8892b0; font-size:0.78rem;">{{ Str::limit($ec->location, 40) }}</td>
                        <td style="padding:0.6rem 0.75rem; text-align:center; color:#8892b0;">{{ $ec->capacity > 0 ? $ec->capacity : 'âˆž' }}</td>
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
            <span>Generated by DRRM Typhoon & Flood Monitoring System</span>
            <span>{{ now()->format('Y') }} Â· DepEd DRRM Monitoring Â· Printed: {{ now()->format('M d, Y h:i A') }}</span>
        </div>
    </div>
</div>



@php
    $chartData = $evacuationCenters->map(function($ec) {
        $fullName = optional($ec->school)->school_name ?? $ec->identification ?? 'Center #'.$ec->id;
        return [
            'full_name' => $fullName,
            'display_name' => \Illuminate\Support\Str::limit($fullName, 12),
            'occupancy' => $ec->current_occupancy,
            'capacity' => $ec->capacity > 0 ? $ec->capacity : 0,
            'created_at' => optional($ec->created_at)->toDateTimeString(),
        ];
    })->values();
    $totalSystemCapacity = $evacuationCenters->sum(fn ($ec) => (int) ($ec->capacity ?? 0));
@endphp

{{-- ===================== SCRIPTS ===================== --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    /* ── Sidebar Dropdown Toggles ── */
    document.addEventListener('DOMContentLoaded', function () {
        function setupDropdown(toggleId, subId, caretId) {
            const toggleBtn = document.getElementById(toggleId);
            const subMenu   = document.getElementById(subId);
            const caret     = document.getElementById(caretId);
            if (toggleBtn && subMenu) {
                toggleBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    subMenu.classList.toggle('open');
                    if (caret) caret.classList.toggle('open');
                });
            }
        }
        
        setupDropdown('evac-nav-register-toggle', 'evac-nav-register-sub', 'evac-register-caret');
        setupDropdown('evac-nav-reports-toggle', 'evac-nav-reports-sub', 'evac-reports-caret');

        // Register sub-nav: programmatically open family modal to avoid e.preventDefault() conflict
        function openFamilyModalWithMode(mode) {
            const el = document.getElementById('familyRegistrationModal');
            if (!el) return;
            
            // Move modal directly to body to avoid z-index or stacking context issues
            if (el.parentElement !== document.body) {
                document.body.appendChild(el);
            }
            
            const modal = bootstrap.Modal.getOrCreateInstance(el);
            // Attach the mode as a temporary property so show.bs.modal can read it
            el.dataset.pendingMode = mode;
            modal.show();
        }
        const registerNavNew = document.getElementById('registerNavNew');
        const registerNavExisting = document.getElementById('registerNavExisting');
        if (registerNavNew) {
            registerNavNew.addEventListener('click', function(e) {
                e.preventDefault();
                openFamilyModalWithMode('new');
            });
        }
        if (registerNavExisting) {
            registerNavExisting.addEventListener('click', function(e) {
                e.preventDefault();
                openFamilyModalWithMode('existing');
            });
        }

        /* ── School Table Pagination ── */
        const ROWS_PER_PAGE = 10;
        const tbody = document.getElementById('evacSchoolsTbody');
        const prevBtn = document.getElementById('evacPrevPage');
        const nextBtn = document.getElementById('evacNextPage');
        const pageInfo = document.getElementById('evacPageInfo');

        if (!tbody || !prevBtn || !nextBtn || !pageInfo) return;

        // Separate clustered (priority) rows from regular rows
        const allRows = Array.from(tbody.querySelectorAll('tr.evac-school-row'));
        const priorityRows = allRows.filter(r => r.dataset.priority === '1');
        const regularRows  = allRows.filter(r => r.dataset.priority !== '1');

        // Re-order: priority rows first, then regular
        const orderedRows = [...priorityRows, ...regularRows];
        orderedRows.forEach(r => tbody.appendChild(r)); // re-append in order

        const totalRows  = orderedRows.length;
        const totalPages = Math.max(1, Math.ceil(totalRows / ROWS_PER_PAGE));
        let currentPage  = 1;

        function renderPage(page) {
            currentPage = page;
            const start = (page - 1) * ROWS_PER_PAGE;
            const end   = start + ROWS_PER_PAGE;
            orderedRows.forEach((row, idx) => {
                row.style.display = (idx >= start && idx < end) ? '' : 'none';
            });
            pageInfo.textContent = `Page ${page} of ${totalPages}`;
            prevBtn.disabled = page <= 1;
            nextBtn.disabled = page >= totalPages;
        }

        prevBtn.addEventListener('click', () => { if (currentPage > 1) renderPage(currentPage - 1); });
        nextBtn.addEventListener('click', () => { if (currentPage < totalPages) renderPage(currentPage + 1); });

        renderPage(1);
    });
</script>

<script>
    // 1. Setup Chart
    document.addEventListener('DOMContentLoaded', function() {
        const chartCanvas = document.getElementById('occupancyChart');
        if (!chartCanvas) {
            return;
        }

        const ctx = chartCanvas.getContext('2d');
        const scrollContainer = document.getElementById('occupancyChartScroll');
        const sortSelect = document.getElementById('occupancySortOrder');
        const directionRadios = document.querySelectorAll('input[name="occupancyDirection"]');

        const rawData = @json($chartData);
        let occupancyChart = null;

        const normalizeDate = (value) => {
            const timestamp = value ? new Date(value).getTime() : 0;
            return Number.isNaN(timestamp) ? 0 : timestamp;
        };

        const getFilteredData = () => {
            const sortMode = sortSelect ? sortSelect.value : 'alphabetical';
            const selectedDirection = document.querySelector('input[name="occupancyDirection"]:checked')?.value ?? 'ltr';
            const dataset = [...rawData];

            if (sortMode === 'highest') {
                dataset.sort((a, b) => (b.occupancy ?? 0) - (a.occupancy ?? 0));
            } else if (sortMode === 'newest') {
                dataset.sort((a, b) => normalizeDate(b.created_at) - normalizeDate(a.created_at));
            } else {
                dataset.sort((a, b) => (a.full_name ?? '').localeCompare(b.full_name ?? ''));
            }

            if (selectedDirection === 'rtl') {
                dataset.reverse();
            }

            return dataset;
        };

        const renderChart = () => {
            const filteredData = getFilteredData();
            const labels = filteredData.map((item) => item.display_name ?? item.full_name ?? '');
            const dataOccupancy = filteredData.map((item) => item.occupancy ?? 0);
            const dataCapacity = filteredData.map((item) => item.capacity ?? 0);
            const minWidth = Math.max((labels.length * 92), 420);

            if (scrollContainer) {
                scrollContainer.style.minWidth = `${minWidth}px`;
                scrollContainer.style.height = '100%';
            }
            chartCanvas.width = minWidth;
            chartCanvas.height = 220;

            if (occupancyChart) {
                occupancyChart.destroy();
            }

            occupancyChart = new Chart(ctx, {
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
                        barPercentage: 0.72,
                        categoryPercentage: 0.7,
                        maxBarThickness: 28,
                    }, {
                        label: 'Capacity',
                        data: dataCapacity,
                        backgroundColor: 'rgba(255, 193, 7, 0.45)',
                        borderColor: 'rgba(255, 193, 7, 1)',
                        borderWidth: 2,
                        borderRadius: 5,
                        barPercentage: 0.72,
                        categoryPercentage: 0.7,
                        maxBarThickness: 28,
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                title: function(context) {
                                    const dataIndex = context?.[0]?.dataIndex ?? 0;
                                    return filteredData[dataIndex]?.full_name ?? context?.[0]?.label ?? '';
                                },
                                label: function(context) {
                                    const label = context.dataset.label || 'Value';
                                    return `${label}: ${context.parsed.y}`;
                                }
                            }
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
                                color: '#64748b',
                                maxRotation: 0,
                                minRotation: 0,
                                autoSkip: false
                            }
                        }
                    }
                }
            });
        };

        renderChart();

        if (sortSelect) {
            sortSelect.addEventListener('change', renderChart);
        }
        directionRadios.forEach((radio) => {
            radio.addEventListener('change', renderChart);
        });
    });

    /* Family registration logic moved to family-registration-modal.blade.php.
    // 2. Family Modal Logic
    const existingFamiliesByCenter = @json($existingFamiliesByCenter ?? []);
    const familyModalEl = document.getElementById('familyRegistrationModal');
    const familyForm = document.getElementById('familyRegistrationForm');
    const modalCenterSelect = document.getElementById('modal_evacuation_center_id');
    const lockedCenterHint = document.getElementById('lockedCenterHint');
    const registrationModeSelect = document.getElementById('familyRegistrationMode');
    const existingFamilyWrap = document.getElementById('existingFamilySelectorWrap');
    const existingFamilySelect = document.getElementById('existingFamilySelect');
    const existingFamilyIdInput = document.getElementById('existingFamilyId');
    const membersContainer = document.getElementById('family-members-container');
    const addMemberBtn = document.getElementById('add-member-btn');
    const headNameInput = document.getElementById('input_head_name');
    const hiddenHeadNameInput = document.getElementById('hidden_head_name');
    const headAgeInput = familyForm ? familyForm.querySelector('input[name="members[0][age]"]') : null;
    const headGenderSelect = familyForm ? familyForm.querySelector('select[name="members[0][gender]"]') : null;
    const headVulnerabilityHint = document.getElementById('headVulnerabilityHint');
    const builderEl = document.querySelector('.family-needs-builder[data-family-needs-builder="create"]');
    
    // dropdown boxes
    const firesafetyBuildingsSelect = document.getElementById('firesafety_buildings');
    const roomIdSelect = document.getElementById('fire_safety_rooms');


    let memberIndex = 1;

    function initializeFamilyNeedsBuilder(builder) {
        if (!builder) {
            return;
        }

        const needOptions = JSON.parse(builder.dataset.needOptions || '[]');
        const existingNeeds = JSON.parse(builder.dataset.existingNeeds || '[]');
        let rowIndex = 0;

        const buildOptions = (selectedValue = '') => {
            const baseOptions = ['<option value="">-- Select need --</option>']
                .concat(needOptions.map((need) => {
                    const safeNeed = String(need).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
                    const selected = safeNeed === selectedValue ? ' selected' : '';
                    return `<option value="${safeNeed}"${selected}>${safeNeed}</option>`;
                }))
                .concat(needOptions.includes('Others Please Specify') ? [] : ['<option value="Others Please Specify">Others Please Specify</option>']);

            return baseOptions.join('');
        };

        const addRow = (need = {}, shouldFocus = false) => {
            const row = document.createElement('div');
            row.className = 'row g-2 mb-2 align-items-start family-need-row';
            row.dataset.rowIndex = String(rowIndex++);

            const selectedNeed = need.need_name || '';
            const isCustom = !!need.is_custom || (selectedNeed && !needOptions.includes(selectedNeed));
            const customNeedValue = isCustom ? selectedNeed : (need.custom_need || '');
            const quantityValue = need.quantity || 1;

            row.innerHTML = `
                <div class="col-md-6">
                    <select class="form-select family-need-select" name="needs[${row.dataset.rowIndex}][need_name]" required>
                        ${buildOptions(selectedNeed && !isCustom ? selectedNeed : '')}
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control family-need-quantity" name="needs[${row.dataset.rowIndex}][quantity]" min="1" max="999" value="${quantityValue}" placeholder="Qty" required>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-outline-danger w-100 family-need-remove">Remove</button>
                </div>
                <div class="col-12 family-need-custom-wrap ${isCustom ? '' : 'd-none'}">
                    <input type="text" class="form-control mt-1 family-need-custom" name="needs[${row.dataset.rowIndex}][custom_need]" placeholder="Please specify other need" value="${customNeedValue}">
                </div>
            `;

            const select = row.querySelector('.family-need-select');
            const customWrap = row.querySelector('.family-need-custom-wrap');
            const customInput = row.querySelector('.family-need-custom');
            const removeBtn = row.querySelector('.family-need-remove');

            select.addEventListener('change', function () {
                const isOther = this.value === 'Others Please Specify';
                customWrap.classList.toggle('d-none', !isOther);
                customInput.required = isOther;
                if (!isOther) {
                    customInput.value = '';
                }

                if (this.value && row === builder.lastElementChild) {
                    addRow({}, false);
                }
            });

            customInput.addEventListener('input', function () {
                if (this.value && row === builder.lastElementChild) {
                    addRow({}, false);
                }
            });

            removeBtn.addEventListener('click', function () {
                if (builder.children.length <= 1) {
                    select.value = '';
                    customInput.value = '';
                    customWrap.classList.add('d-none');
                    customInput.required = false;
                    row.querySelector('.family-need-quantity').value = 1;
                    return;
                }

                row.remove();
            });

            builder.appendChild(row);

            if (selectedNeed) {
                if (isCustom) {
                    select.value = 'Others Please Specify';
                    customWrap.classList.remove('d-none');
                    customInput.required = true;
                } else {
                    select.value = selectedNeed;
                }
            }

            if (shouldFocus) {
                select.focus();
            }
        };

        builder.innerHTML = '';
        if (existingNeeds.length > 0) {
            existingNeeds.forEach((need, index) => addRow(need, index === 0));
            addRow({}, false);
        } else {
            addRow({}, true);
        }
    }

    function getMemberVulnerabilityLabel(age) {
        const tags = [];
        if (age >= 60) {
            tags.push('Senior Citizen');
        }
        if (age >= 0 && age <= 5) {
            tags.push('Child under 5');
        }
        return tags.length ? tags.join(' | ') : 'None';
    }

    function refreshFamilyVulnerabilityFlags() {
        if (!familyForm) return;

        const ageInputs = familyForm.querySelectorAll('input[name*="[age]"]');
        let hasSenior = false;
        let hasChild = false;

        ageInputs.forEach((input) => {
            const age = Number(input.value);
            if (!Number.isNaN(age)) {
                if (age >= 60) hasSenior = true;
                if (age <= 5) hasChild = true;
            }
        });

        const seniorCheck = document.getElementById('flagSenior');
        const childCheck = document.getElementById('flagChild');
        if (seniorCheck) seniorCheck.checked = hasSenior;
        if (childCheck) childCheck.checked = hasChild;
    }

    function bindAgeAutoFlags(ageInput, hintEl) {
        if (!ageInput || !hintEl) return;
        const update = () => {
            const age = Number(ageInput.value);
            refreshFamilyVulnerabilityFlags();
        };
        ageInput.addEventListener('input', update);
        update();
    }

    // Add member function
    function addMemberRow(member = {}) {
        if (!membersContainer) return;

        const noMembersHint = document.getElementById('no-members-hint');
        if (noMembersHint) noMembersHint.classList.add('d-none');

        const row = document.createElement('div');
        row.className = 'row g-2 mb-3 member-row border-bottom pb-3';

        const familyRoleOptions = (typeof FAMILY_ROLES !== 'undefined' ? FAMILY_ROLES : [
            'Father','Mother','Son','Daughter','Grandfather','Grandmother',
            'Uncle','Aunt','Nephew','Niece','Cousin','Relative','Guardian','Family Friend'
        ]).map(r => `<option value="${r}" ${member.family_role === r ? 'selected' : ''}>${r}</option>`).join('');

        row.innerHTML = `
            <div class="col-md-5 mb-2">
                <label class="form-label small fw-bold">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="members[${memberIndex}][full_name]" class="form-control" placeholder="Full name" value="${member.full_name ?? ''}" required>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label small fw-bold">Age <span class="text-danger">*</span></label>
                <input type="number" name="members[${memberIndex}][age]" class="form-control member-age-input" placeholder="Age" value="${member.age ?? ''}" required min="0" max="150">
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label small fw-bold">Gender <span class="text-danger">*</span></label>
                <select name="members[${memberIndex}][gender]" class="form-select" required>
                    <option value="">Select...</option>
                    <option value="male" ${member.gender === 'male' ? 'selected' : ''}>Male</option>
                    <option value="female" ${member.gender === 'female' ? 'selected' : ''}>Female</option>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label small fw-bold">Family Role</label>
                <select name="members[${memberIndex}][family_role]" class="form-select">
                    <option value="">-- Role --</option>
                    ${familyRoleOptions}
                </select>
            </div>
            <div class="col-12 d-flex align-items-center justify-content-end mt-1">
                <button type="button" class="btn btn-outline-danger btn-sm remove-member">
                    <i class="fas fa-trash me-1"></i> Remove Member
                </button>
            </div>
            <input type="hidden" name="members[${memberIndex}][is_head]" value="0">
        `;
        membersContainer.appendChild(row);

        const ageInput = row.querySelector('.member-age-input');
        ageInput.addEventListener('input', refreshFamilyVulnerabilityFlags);

        row.querySelector('.remove-member').addEventListener('click', function() {
            row.remove();
            refreshFamilyVulnerabilityFlags();
            const noMembersHint2 = document.getElementById('no-members-hint');
            if (noMembersHint2 && membersContainer.children.length === 0) {
                noMembersHint2.classList.remove('d-none');
            }
        });

        memberIndex++;
    }

    function setNeedsBuilderExistingNeeds(needs = []) {
        if (!builderEl) return;
        builderEl.dataset.existingNeeds = JSON.stringify(needs);
        initializeFamilyNeedsBuilder(builderEl);
    }

    function clearFamilyDetails() {
        if (!familyForm) return;
        if (headNameInput) headNameInput.value = '';
        if (hiddenHeadNameInput) hiddenHeadNameInput.value = '';
        if (headAgeInput) headAgeInput.value = '';
        if (headGenderSelect) headGenderSelect.value = '';
        
        document.querySelectorAll('.vulnerability-checkbox').forEach(cb => cb.checked = false);
        document.querySelectorAll('.vulnerability-tags-container').forEach(c => c.innerHTML = '');

        if (membersContainer) {
            membersContainer.innerHTML = '';
        }
        // Reset no-members hint
        const noMembersHint = document.getElementById('no-members-hint');
        if (noMembersHint) noMembersHint.classList.remove('d-none');

        memberIndex = 1;
        if (existingFamilyIdInput) existingFamilyIdInput.value = '';
        setNeedsBuilderExistingNeeds([]);
        refreshFamilyVulnerabilityFlags();

        // Reset new fields: address, contact
        const familyForm2 = document.getElementById('familyRegistrationForm');
        if (familyForm2) {
            ['street','barangay','city','contact_number','other_needs_details'].forEach(name => {
                const el = familyForm2.querySelector(`[name="${name}"]`);
                if (el) el.value = '';
            });
        }
        // Reset other needs details panel
        const otherNeedsWrap = document.getElementById('otherNeedsDetailsWrap');
        if (otherNeedsWrap) otherNeedsWrap.classList.add('d-none');

        // Reset belongings & pets
        if (typeof resetBelongingsAndPets === 'function') resetBelongingsAndPets();
    }

    // Vulnerability Tag System Logic
    function addVulnerabilityTag(container, checkbox, label) {
        if (!container || !checkbox || checkbox.checked) return;
        const tag = document.createElement('span');
        tag.className = 'badge bg-primary d-flex align-items-center gap-2 py-2 px-3 shadow-sm';
        tag.style.borderRadius = '50px';
        tag.style.fontSize = '0.75rem';
        tag.innerHTML = `${label} <i class="fas fa-times" style="cursor:pointer;"></i>`;
        checkbox.checked = true;
        tag.querySelector('.fa-times').addEventListener('click', () => {
            checkbox.checked = false;
            tag.remove();
        });
        container.appendChild(tag);
    }

    // Event Delegation for the Dropdown
    document.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('vulnerability-selector')) {
            const select = e.target;
            const val = select.value;
            if (!val) return;
            const label = select.options[select.selectedIndex].text;
            const wrapper = select.closest('.vulnerability-wrapper');
            const container = wrapper.querySelector('.vulnerability-tags-container');
            const checkbox = wrapper.querySelector('.' + val);
            
            if (container && checkbox) {
                addVulnerabilityTag(container, checkbox, label);

                // If "Other / Special Needs" â€” show the details input
                if (val === 'flagOtherNeeds') {
                    const otherWrap = document.getElementById('otherNeedsDetailsWrap');
                    if (otherWrap) otherWrap.classList.remove('d-none');

                    // When the tag is removed, hide the details input
                    const tag = container.lastElementChild;
                    if (tag) {
                        const removeIcon = tag.querySelector('.fa-times');
                        if (removeIcon) {
                            removeIcon.addEventListener('click', () => {
                                const wrap = document.getElementById('otherNeedsDetailsWrap');
                                if (wrap) {
                                    wrap.classList.add('d-none');
                                    const input = wrap.querySelector('input');
                                    if (input) input.value = '';
                                }
                            });
                        }
                    }
                }
            }
            select.value = '';
        }
    });

    // Sync existing flags to tags (for auto-detection or edit mode)
    function syncVulnerabilityTags() {
        const headWrapper = document.querySelector('.vulnerability-wrapper');
        if (!headWrapper) return;
        const container = headWrapper.querySelector('.vulnerability-tags-container');

        ['flagPregnant', 'flagPwd', 'flagSenior', 'flagLactating', 'flagChild'].forEach(id => {
            const cb = document.getElementById(id);
            if (cb && cb.checked) {
                cb.checked = false; // Reset temporarily to let addVulnerabilityTag handle it
                const label = document.querySelector(`label[for="${id}"]`)?.textContent || id.replace('flag', '');
                addVulnerabilityTag(container, cb, label);
            }
        });
    }

    function centerFamilies(centerId) {
        // Keep compatibility with the existing per-center response while exposing
        // every registered family to every evacuation center.
        const allFamilies = Object.values(existingFamiliesByCenter || {}).flat();
        const uniqueFamilies = new Map();

        allFamilies.forEach((family) => {
            if (family && family.id != null) {
                uniqueFamilies.set(String(family.id), family);
            }
        });

        return Array.from(uniqueFamilies.values());
    }

    function refreshExistingFamilyChoices() {
        if (!existingFamilySelect || !modalCenterSelect) return;
        const families = centerFamilies(modalCenterSelect.value);

        existingFamilySelect.innerHTML = '<option value="">-- Select registered family --</option>';
        families.forEach((family) => {
            const status = family.checked_out_at ? 'History' : 'Current';
            const timestamp = family.created_at ? new Date(family.created_at).toLocaleDateString() : '';
            const label = `#${family.id} - ${family.head_family_name} (${status}${timestamp ? ' â€¢ ' + timestamp : ''})`;
            const option = document.createElement('option');
            option.value = family.id;
            option.textContent = label;
            existingFamilySelect.appendChild(option);
        });
    }

    function fillFormFromExistingFamily(family) {
        if (!family) return;
        const members = Array.isArray(family.members) ? family.members : [];
        const head = members.find((m) => !!m.is_head) || members[0] || { full_name: family.head_family_name, age: '', gender: '' };

        if (headNameInput) headNameInput.value = head.full_name || family.head_family_name || '';
        if (hiddenHeadNameInput) hiddenHeadNameInput.value = headNameInput ? headNameInput.value : '';
        if (headAgeInput) headAgeInput.value = head.age ?? '';
        if (headGenderSelect) headGenderSelect.value = head.gender ?? '';

        if (membersContainer) {
            membersContainer.innerHTML = '';
        }
        memberIndex = 1;

        members.filter((m) => !m.is_head).forEach((member) => addMemberRow(member));

        const pregnant = document.getElementById('flagPregnant');
        const pwd = document.getElementById('flagPwd');
        const senior = document.getElementById('flagSenior');
        const lactating = document.getElementById('flagLactating');
        const child = document.getElementById('flagChild');
        if (pregnant) pregnant.checked = !!family.has_pregnant;
        if (pwd) pwd.checked = !!family.has_pwd;
        if (senior) senior.checked = !!family.has_senior;
        if (lactating) lactating.checked = !!family.has_lactating;
        if (child) child.checked = !!family.has_child_under5;

        setNeedsBuilderExistingNeeds(family.needs || []);
        if (existingFamilyIdInput) {
            existingFamilyIdInput.value = family.id;
        }
        refreshFamilyVulnerabilityFlags();
    }

    if (headNameInput && hiddenHeadNameInput) {
        headNameInput.addEventListener('input', function () {
            hiddenHeadNameInput.value = this.value;
        });
    }

    if (addMemberBtn) {
        addMemberBtn.addEventListener('click', function() {
            addMemberRow({});
        });
    }

    if (registrationModeSelect) {
        registrationModeSelect.addEventListener('change', function () {
            const existingMode = this.value === 'existing';
            if (existingFamilyWrap) {
                existingFamilyWrap.classList.toggle('d-none', !existingMode);
            }
            clearFamilyDetails();
            if (existingMode) {
                refreshExistingFamilyChoices();
            }
        });
    }

    if (existingFamilySelect) {
        existingFamilySelect.addEventListener('change', function () {
            if (!existingFamilyIdInput) return;
            const selectedId = Number(this.value);
            existingFamilyIdInput.value = selectedId ? String(selectedId) : '';

            const families = centerFamilies(modalCenterSelect ? modalCenterSelect.value : '');
            const family = families.find((row) => Number(row.id) === selectedId);
            clearFamilyDetails();
            if (family) {
                fillFormFromExistingFamily(family);
            }
        });
    }

    if (modalCenterSelect) {
        modalCenterSelect.addEventListener('change', async function () {
            if (this.dataset.lockedValue) {
                this.value = this.dataset.lockedValue;
            }

            const schoolId = this.value;
            if (firesafetyBuildingsSelect) {
                firesafetyBuildingsSelect.innerHTML = '<option value="">-- Loading Buildings --</option>';
                if (roomIdSelect) {
                    roomIdSelect.innerHTML = '<option value="">-- Select Room --</option>';
                    roomIdSelect.disabled = true;
                }

                if (schoolId) {
                    try {
                        const response = await fetch(`/fire-safety/buildings/${schoolId}`);
                        const buildings = await response.json();
                        firesafetyBuildingsSelect.innerHTML = '<option value="">-- Select Building --</option>';
                        buildings.forEach(b => {
                            const opt = document.createElement('option');
                            opt.value = b.id;
                            const bName = b.building_name ? ` - ${b.building_name}` : '';
                            opt.textContent = `${b.building_no}${bName}`;
                            firesafetyBuildingsSelect.appendChild(opt);
                        });
                    } catch (e) {
                        firesafetyBuildingsSelect.innerHTML = '<option value="">-- Error loading buildings --</option>';
                    }
                } else {
                    firesafetyBuildingsSelect.innerHTML = '<option value="">-- Select Building --</option>';
                }
            }
        });
    }

    if (firesafetyBuildingsSelect) {
        firesafetyBuildingsSelect.addEventListener('change', async function () {
            const buildingId = this.value;
            if (roomIdSelect) {
                roomIdSelect.innerHTML = '<option value="">-- Loading Rooms --</option>';
                roomIdSelect.disabled = true;

                if (buildingId) {
                    try {
                        const response = await fetch(`/fire-safety/rooms/${buildingId}`);
                        const rooms = await response.json();
                        roomIdSelect.innerHTML = '<option value="">-- Select Room --</option>';
                        if (rooms && rooms.length > 0) {
                            // Filter for buffer or main evacuation rooms
                            const evacRooms = rooms.filter(r => r.Main_evac || r.Buffer_evac || r.is_evacuation_room);
                            
                            if (evacRooms.length > 0) {
                                roomIdSelect.disabled = false;
                                evacRooms.forEach(r => {
                                    const opt = document.createElement('option');
                                    opt.value = r.id;
                                    const rName = r.room_name ? ` - ${r.room_name}` : '';
                                    
                                    let typeText = '';
                                    if (r.Main_evac && r.Buffer_evac) typeText = ' (Main & Buffer)';
                                    else if (r.Main_evac) typeText = ' (Main)';
                                    else if (r.Buffer_evac) typeText = ' (Buffer)';
                                    else if (r.is_evacuation_room) typeText = ' (Evac)';
                                    
                                    opt.textContent = `${r.room_code || 'Room'}${rName}${typeText}`;
                                    roomIdSelect.appendChild(opt);
                                });
                            } else {
                                roomIdSelect.innerHTML = '<option value="">No evacuation rooms found in this building</option>';
                            }
                        } else {
                            roomIdSelect.innerHTML = '<option value="">No rooms found</option>';
                        }
                    } catch (e) {
                        roomIdSelect.innerHTML = '<option value="">-- Error loading rooms --</option>';
                    }
                } else {
                    roomIdSelect.innerHTML = '<option value="">-- Select Room --</option>';
                }
            }
        });
    }

    if (familyModalEl) {
        familyModalEl.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (button && modalCenterSelect) {
                const ecId = button.getAttribute('data-ec-id');
                if (ecId) {
                    modalCenterSelect.value = ecId;
                    modalCenterSelect.dataset.lockedValue = ecId;
                    modalCenterSelect.style.pointerEvents = 'none';
                    modalCenterSelect.style.backgroundColor = '#e9f2ff';
                    if (lockedCenterHint) lockedCenterHint.classList.remove('d-none');
                    // Automatically trigger building fetch for the pre-selected center
                    modalCenterSelect.dispatchEvent(new Event('change'));
                } else {
                    delete modalCenterSelect.dataset.lockedValue;
                    modalCenterSelect.style.pointerEvents = '';
                    modalCenterSelect.style.backgroundColor = '';
                    if (lockedCenterHint) lockedCenterHint.classList.add('d-none');
                }
            }

            const mode = (button && button.dataset.mode) || familyModalEl.dataset.pendingMode || 'new';
            delete familyModalEl.dataset.pendingMode; // clear after reading

            if (mode !== 'new') {
                if (registrationModeSelect) {
                    registrationModeSelect.value = mode;
                    registrationModeSelect.dispatchEvent(new Event('change'));
                }
                if (existingFamilyWrap) {
                    existingFamilyWrap.classList.toggle('d-none', mode !== 'existing');
                }
            } else {
                if (registrationModeSelect) {
                    registrationModeSelect.value = 'new';
                }
                if (existingFamilyWrap) {
                    existingFamilyWrap.classList.add('d-none');
                }
            }
            if (existingFamilySelect) {
                existingFamilySelect.value = '';
                refreshExistingFamilyChoices();
            }
            clearFamilyDetails();
        });

        familyModalEl.addEventListener('hidden.bs.modal', function () {
            if (!modalCenterSelect) return;
            delete modalCenterSelect.dataset.lockedValue;
            modalCenterSelect.style.pointerEvents = '';
            modalCenterSelect.style.backgroundColor = '';
            if (lockedCenterHint) lockedCenterHint.classList.add('d-none');
        });
    }

    */

    // 3. Social Print Function
    function printSocialCard() {
        const card = document.getElementById('printCard');
        if (!card) return;

        const html = `<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Typhoon & Flood Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        @page { margin: 0; size: A4 landscape; }
        html, body {
            width: 100%;
            height: 100%;
            background: #0a1128 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            font-family: 'Sora', 'Inter', sans-serif;
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

    // Search function for the Evacuation Centers Table
    const schoolSearchInput = document.getElementById('schoolSearchInput');
    const tableBody = document.getElementById('evacuationCentersTableBody');
    if (schoolSearchInput && tableBody) {
        schoolSearchInput.addEventListener('keyup', function() {
            const query = this.value.toLowerCase();
            const rows = tableBody.querySelectorAll('tr.school-row');
            rows.forEach(row => {
                const text = row.querySelector('.school-name-text')?.textContent.toLowerCase() || '';
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }

    // Status Filter for Evacuation Centers Table
    const filterStatusBtns = document.querySelectorAll('.filter-status-btn');
    const currentStatusFilterText = document.getElementById('currentStatusFilterText');
    const evacSchoolsTbody = document.getElementById('evacSchoolsTbody');
    
    if (filterStatusBtns.length > 0 && evacSchoolsTbody) {
        filterStatusBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const status = this.getAttribute('data-status');
                
                // Update checkmarks
                filterStatusBtns.forEach(b => {
                    const icon = b.querySelector('.check-icon');
                    if(icon) icon.classList.add('d-none');
                });
                const myIcon = this.querySelector('.check-icon');
                if(myIcon) myIcon.classList.remove('d-none');
                
                // Update dropdown button text
                if (status === 'all') {
                    currentStatusFilterText.textContent = 'All Status';
                } else {
                    currentStatusFilterText.textContent = status.toUpperCase();
                }

                // Filter rows
                const rows = evacSchoolsTbody.querySelectorAll('tr.evac-school-row');
                rows.forEach(row => {
                    if (status === 'all') {
                        row.style.display = '';
                        return;
                    }
                    const badge = row.querySelector('.td-status .evac-badge');
                    if (badge && badge.textContent.trim().toLowerCase() === status) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    }
</script>
@endsection
@include('typhoon.partials.choose-school-modal')

{{-- MODAL: QUICK ANNOUNCEMENT (Global for Admin) --}}
<div class="modal fade" id="announceSomethingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('typhoon.announcements.store') }}">
            @csrf
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header" style="background-color: var(--card-header-bg); color: white;">
                    <h5 class="modal-title fw-bold"><i class="fas fa-bullhorn me-2 text-info"></i>PUBLIC ANNOUNCEMENT</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-dark">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">TITLE / SUBJECT</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. System-wide Relief Distribution Notice" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">URGENCY LEVEL</label>
                        <select name="urgency" class="form-select">
                            <option value="info">INFO - Standard Update</option>
                            <option value="warning">WARNING - Important Notice</option>
                            <option value="danger">URGENT - Critical Requirement</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold text-muted small">MESSAGE CONTENT</label>
                        <textarea name="message" rows="4" class="form-control" placeholder="Type your announcement details here..." required></textarea>
                    </div>
                    <div class="mt-3 small text-muted italic">
                        <i class="fas fa-info-circle me-1"></i> This is a global announcement. It will be visible to ALL users across ALL evacuation centers.
                    </div>
                </div>
                <div class="modal-footer bg-light shadow-sm">
                    <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn btn-info text-white px-5 fw-bold shadow-sm">POST ANNOUNCEMENT</button>
                </div>
            </div>
        </form>
    </div>
</div>
