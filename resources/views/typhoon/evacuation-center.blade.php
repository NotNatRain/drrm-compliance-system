@extends('layouts.app')

@section('title', 'Evacuation Center Intelligence')
@section('hide_main_nav', '1')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    .search-bar-container input::placeholder {
        color: rgba(255, 255, 255, 0.6);
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

    .profile-property {
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 1px;
    }

    .profile-value {
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1.25rem;
    }

    .table-custom thead th {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
        padding: 1rem;
    }

    .table-custom tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
    }

    .stat-icon-small {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
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
        width: 45px;
        height: 45px;
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
        font-family: 'Rajdhani', sans-serif;
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

    .profile-menu-btn {
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.06);
        color: #fff;
        border-radius: 999px;
        padding: 0.45rem 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
    }

    .profile-menu-btn:hover,
    .profile-menu-btn:focus {
        color: var(--accent-blue);
        border-color: rgba(0, 210, 255, 0.55);
    }

    .profile-menu .dropdown-menu {
        background: #0f1b3f;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 12px;
        min-width: 210px;
    }

    .profile-menu .dropdown-item {
        color: #dbeafe;
        font-weight: 600;
    }

    .profile-menu .dropdown-item:hover {
        background: rgba(0, 210, 255, 0.15);
        color: #fff;
    }

    .header-action-btn {
        min-height: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Unified Header --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        {{-- Left Side --}}
        <div class="d-flex align-items-center" style="width: 30%;">
            <a href="{{ route('typhoon.dashboard') }}" class="btn btn-outline-info border-0 me-3 shadow-sm" style="background: rgba(255,255,255,0.05);">
                <i class="fas fa-chevron-left"></i>
            </a>
            <div>
                <h1 class="h3 mb-0 fw-bold text-white">
                    {{ $ec->school_name ?? 'NOT DEFINED' }}
                </h1>
                <div class="small text-info opacity-75 fw-bold text-uppercase tracking-wider">EVACUATION HUB</div>
            </div>
        </div>

        {{-- Centered Navigation --}}
        <div class="header-nav-center">
            <a href="{{ route('typhoon.dashboard') }}" class="nav-link-custom">
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
            <button type="button" class="school-btn-custom active" data-bs-toggle="modal" data-bs-target="#chooseSchoolModal" title="Choose Evacuation Center">
                <i class="fas fa-school"></i>
            </button>
        </div>

        {{-- Right Side --}}
        <div class="d-flex align-items-center gap-3 justify-content-end" style="width: 30%;">
            <a href="{{ route('typhoon.evacuation-center.print', $ec->id) }}" target="_blank" class="btn btn-success px-3 fw-bold shadow-lg header-action-btn me-1" style="display:inline-flex; align-items:center; color:white; text-decoration:none;" title="Print Evacuation Center">
                <i class="fas fa-print me-2"></i>PRINT REPORT
            </a>
            <button type="button" class="btn btn-primary px-3 fw-bold shadow-lg header-action-btn" data-bs-toggle="modal" data-bs-target="#updateCenterStatusModal">
                <i class="fas fa-edit me-2"></i>UPDATE SITE
            </button>
            <div class="dropdown profile-menu">
                <button class="btn profile-menu-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                    <span>{{ auth()->user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('users.index') }}">
                            <i class="fas fa-user-cog me-2"></i>User Account
                        </a>
                    </li>
                    @if(auth()->user()->role === 'admin')
                        <li>
                            <a class="dropdown-item" href="{{ route('activity-logs.index') }}">
                                <i class="fas fa-history me-2"></i>Logs
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    {{-- Profile Row (Full Width) --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="dashboard-card border-0 shadow-lg" style="background: linear-gradient(135deg, #0f2154 0%, #1a3a8a 100%); color: white; border-radius: 16px; overflow: hidden;">
                <div class="row g-0 align-items-stretch">
                    <div class="col-md-3 p-4 border-end border-white-50 d-flex flex-column justify-content-center">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary bg-opacity-25 p-3 rounded-circle me-3">
                                <i class="fas fa-id-card-alt fa-2x text-info"></i>
                            </div>
                            <div>
                                <div class="profile-property text-white-50 mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">Identification Code</div>
                                <div class="h5 mb-0 fw-bold text-white">{{ $ec->identification ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-danger bg-opacity-25 p-3 rounded-circle me-3">
                                <i class="fas fa-map-marker-alt fa-2x text-danger"></i>
                            </div>
                            <div>
                                <div class="profile-property text-white-50 mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">Location / Address</div>
                                <div class="small mb-0 fw-bold text-white">{{ $ec->location ?? $ec->school->address ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 p-4 border-end border-white-50 d-flex flex-column justify-content-center text-center">
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="profile-property text-white-50 mb-1" style="font-size: 0.8rem; letter-spacing: 1.5px;">Max Capacity</div>
                                <div class="h2 mb-0 fw-bold text-white">{{ $ec->capacity ?? 0 }} <small class="text-white-50 fs-6">Individuals</small></div>
                            </div>
                            <div class="col-6">
                                <div class="profile-property text-white-50 mb-1" style="font-size: 0.8rem; letter-spacing: 1.5px;">Current Load</div>
                                <div class="h2 mb-0 fw-bold text-info">{{ $currentOccupancy }} <small class="text-white-50 fs-6">Individuals</small></div>
                            </div>
                        </div>
                        <div class="px-4">
                            @php
                                $loadPercent = $ec->capacity > 0 ? min(round(($currentOccupancy / $ec->capacity) * 100), 100) : 0;
                            @endphp
                            <div class="progress" style="height: 14px; background: rgba(255,255,255,0.1); border-radius: 20px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);">
                                <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" style="width: {{ $loadPercent }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2 px-1">
                                <div class="small text-white-50 fw-bold text-uppercase" style="font-size: 0.65rem;">Resource Utilization</div>
                                <div class="small text-white-50 fw-bold text-uppercase" style="font-size: 0.65rem;">{{ $loadPercent }}% Capacity Limit</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 p-4 d-flex flex-column justify-content-center text-center bg-black bg-opacity-10">
                        <div class="profile-property text-white-50 mb-2" style="font-size: 0.8rem; letter-spacing: 1.5px;">Site Readiness Status</div>
                        @if($ec->usage_status === 'full')
                            <div class="badge bg-danger shadow-sm px-4 py-3 h5 mb-3 w-100 fw-bold" style="border: 1px solid rgba(255,255,255,0.2);">AT CAPACITY</div>
                        @elseif($ec->usage_status === 'occupied')
                            <div class="badge bg-primary shadow-sm px-4 py-3 h5 mb-3 w-100 fw-bold" style="border: 1px solid rgba(255,255,255,0.2);"> STANDBY</div>
                        @elseif($ec->usage_status === 'decamp')
                            <div class="badge shadow-sm px-4 py-3 h5 mb-3 w-100 fw-bold" style="background-color: #6f42c1; border: 1px solid rgba(255,255,255,0.2);">DECAMP / CLOSING</div>
                        @else
                            <div class="badge bg-success shadow-sm px-4 py-3 h5 mb-3 w-100 fw-bold" style="border: 1px solid rgba(255,255,255,0.2);">CLEARED / READY</div>
                        @endif
                        
                        <div class="bg-white bg-opacity-10 p-2 rounded small text-info fw-bold" style="border: 1px dashed rgba(255,255,255,0.2);">
                            <i class="fas fa-boxes me-2"></i> {{ Str::limit($ec->emergency_resources ?? 'No inventory data encoded', 45) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        {{-- Current Occupants + Registry History --}}
        <div class="col-lg-12">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="dashboard-card shadow-lg border-0 h-100 position-relative pb-5" style="min-height: 520px;">
                        <div class="card-header-custom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="d-flex align-items-center">
                                <span><i class="fas fa-house-user me-2"></i>Current Occupants</span>
                                <span class="badge bg-primary bg-opacity-10 text-info px-3 py-2 ms-3" style="font-size: 0.7rem;">ACTIVE</span>
                            </div>
                            <div class="search-bar-container position-relative">
                                <i class="fas fa-search position-absolute top-50 translate-middle-y" style="left: 12px; font-size: 0.85rem;"></i>
                                <input type="text" id="searchActiveFamilies" class="form-control form-control-sm" placeholder="Search families..." style="padding-left: 32px; border-radius: 20px; width: 220px; background-color: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; transition: all 0.3s ease;">
                            </div>
                        </div>
                        <div class="table-responsive" style="height: calc(100% - 110px); overflow-y: auto;">
                            <table class="table table-custom table-hover mb-0">
                                <thead class="sticky-top">
                                    <tr>
                                        <th class="ps-4">Family ID</th>
                                        <th>Head of Family</th>
                                        <th class="text-center">Members</th>
                                        <th class="pe-4">Needs</th>
                                        <th class="text-center pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($families->whereNull('checked_out_at') as $family)
                                        <tr class="active-family-row">
                                            <td class="ps-4"><span class="badge bg-dark">#{{ $family->id }}</span></td>
                                            <td><div class="fw-bold fs-6 text-primary">{{ $family->head_family_name }}</div><small class="text-muted">{{ $family->created_at->format('M d, Y h:i A') }}</small></td>
                                            <td class="text-center"><span class="badge bg-light text-dark border p-2" style="min-width: 35px;">{{ $family->members_count }}</span></td>
                                            <td class="pe-4"><small class="text-truncate d-block" style="max-width: 180px;" title="{{ $family->needs_summary }}">{{ $family->needs_summary ?: '—' }}</small></td>
                                            <td class="text-center pe-4">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updateFamilyModal{{ $family->id }}">
                                                        <i class="fas fa-pen"></i>
                                                    </button>
                                                    <form id="decampFamilyForm{{ $family->id }}" method="POST" action="{{ route('typhoon.families.decamp', $family->id) }}" onsubmit="return confirm('Mark this family as decamped?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-person-walking-arrow-right"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 opacity-50">
                                                <i class="fas fa-house-user fa-3x mb-3 text-muted"></i>
                                                <p class="h6 mb-0">No families currently taking cover.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-white border-top py-3 px-4 d-flex justify-content-between align-items-center position-absolute w-100" style="bottom: 0; left: 0; border-radius: 0 0 16px 16px; z-index: 10;">
                            <span class="text-muted small fw-bold" id="infoActiveFamilies">Showing 0 of 0</span>
                            <nav>
                                <ul class="pagination pagination-sm mb-0" id="paginationActiveFamilies"></ul>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dashboard-card shadow-lg border-0 h-100 position-relative pb-5" style="min-height: 520px;">
                        <div class="card-header-custom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="d-flex align-items-center">
                                <span><i class="fas fa-archive me-2"></i>Registry History</span>
                                <span class="badge bg-secondary bg-opacity-25 text-white px-3 py-2 ms-3" style="font-size: 0.7rem;">DECAMPED</span>
                            </div>
                            <div class="search-bar-container position-relative">
                                <i class="fas fa-search position-absolute top-50 translate-middle-y" style="left: 12px; font-size: 0.85rem;"></i>
                                <input type="text" id="searchDecampedFamilies" class="form-control form-control-sm" placeholder="Search history..." style="padding-left: 32px; border-radius: 20px; width: 220px; background-color: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; transition: all 0.3s ease;">
                            </div>
                        </div>
                        <div class="table-responsive" style="height: calc(100% - 110px); overflow-y: auto;">
                            <table class="table table-custom table-hover mb-0">
                                <thead class="sticky-top">
                                    <tr>
                                        <th class="ps-4">Family ID</th>
                                        <th>Head of Family</th>
                                        <th class="text-center">Members</th>
                                        <th>Decamped At</th>
                                        <th class="pe-4 text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($families->whereNotNull('checked_out_at') as $family)
                                        <tr class="decamped-family-row">
                                            <td class="ps-4"><span class="badge bg-dark">#{{ $family->id }}</span></td>
                                            <td><div class="fw-bold fs-6 text-primary">{{ $family->head_family_name }}</div><small class="text-muted">Checked in: {{ $family->created_at->format('M d, Y h:i A') }}</small></td>
                                            <td class="text-center"><span class="badge bg-light text-dark border p-2" style="min-width: 35px;">{{ $family->members_count }}</span></td>
                                            <td><small>{{ optional($family->checked_out_at)->format('M d, Y h:i A') }}</small></td>
                                            <td class="pe-4 text-end">
                                                <button class="btn btn-sm btn-outline-primary fw-bold" data-bs-toggle="modal" data-bs-target="#historyViewModal{{ $family->id }}">
                                                    <i class="fas fa-eye me-1"></i> VIEW
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 opacity-50">
                                                <i class="fas fa-history fa-3x mb-3 text-muted"></i>
                                                <p class="h6 mb-0">No decamped family records yet.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-white border-top py-3 px-4 d-flex justify-content-between align-items-center position-absolute w-100" style="bottom: 0; left: 0; border-radius: 0 0 16px 16px; z-index: 10;">
                            <span class="text-muted small fw-bold" id="infoDecampedFamilies">Showing 0 of 0</span>
                            <nav>
                                <ul class="pagination pagination-sm mb-0" id="paginationDecampedFamilies"></ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($families as $family)
    @if(!$family->checked_out_at)
        <div class="modal fade" id="updateFamilyModal{{ $family->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" style="margin-top: 1.75rem; margin-bottom: 1.75rem;">
                <form method="POST" action="{{ route('typhoon.families.update', $family->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-content shadow">
                        <div class="modal-header" style="background-color: var(--card-header-bg); color: white;">
                            <div>
                                <h5 class="modal-title fw-bold mb-0"><i class="fas fa-user-edit me-2 text-info"></i>UPDATE FAMILY DETAILS</h5>
                                <small style="color: #a1b0cb;">Family ID #{{ $family->id }} &mdash; Checked in {{ $family->created_at->format('M d, Y h:i A') }}</small>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4 text-dark" style="max-height: 75vh; overflow-y: auto;">

                            {{-- ── SECTION 1: HEAD OF FAMILY DETAILS ── --}}
                            <div class="card mb-3 border-0 shadow-sm">
                                <div class="card-header fw-bold small text-uppercase" style="background: #eaf0fb; color: #1B4C6D;">
                                    <i class="fas fa-user-tie me-2"></i> Head of Family Details
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label small fw-bold">Full Name (Head) <span class="text-danger">*</span></label>
                                            <input type="text" name="head_family_name" class="form-control" value="{{ $family->head_family_name }}" required>
                                        </div>
                                        {{-- Head member age & gender --}}
                                        @php $headMember = $family->members->firstWhere('is_head', true); @endphp
                                        <div class="col-md-3 mb-2">
                                            <label class="form-label small fw-bold">Age</label>
                                            <input type="number" name="members[0][age]" class="form-control" value="{{ optional($headMember)->age }}" min="0" max="150">
                                            <input type="hidden" name="members[0][is_head]" value="1">
                                            <input type="hidden" name="members[0][full_name]" value="{{ $family->head_family_name }}">
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="form-label small fw-bold">Gender</label>
                                            <select name="members[0][gender]" class="form-select">
                                                <option value="">Select...</option>
                                                <option value="male" @selected(optional($headMember)->gender === 'male')>Male</option>
                                                <option value="female" @selected(optional($headMember)->gender === 'female')>Female</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label class="form-label small fw-bold">Contact Number</label>
                                            <input type="text" name="contact_number" class="form-control" value="{{ $family->contact_number }}" placeholder="e.g. 09XXXXXXXXX">
                                        </div>
                                        <div class="col-md-8 mb-2">
                                            <label class="form-label small fw-bold">Street / Purok</label>
                                            <input type="text" name="street" class="form-control" value="{{ $family->street }}" placeholder="Street or Purok name">
                                        </div>
                                        <div class="col-md-5 mb-2">
                                            <label class="form-label small fw-bold">Barangay</label>
                                            <input type="text" name="barangay" class="form-control" value="{{ $family->barangay }}" placeholder="Barangay">
                                        </div>
                                        <div class="col-md-7 mb-2">
                                            <label class="form-label small fw-bold">City / Municipality</label>
                                            <input type="text" name="city" class="form-control" value="{{ $family->city }}" placeholder="City or Municipality">
                                        </div>
                                    </div>

                                    {{-- Vulnerabilities --}}
                                    <div class="mt-3">
                                        <div class="p-3 rounded border" style="background: #f8f9fa;">
                                            <label class="form-label fw-bold small mb-2">Vulnerabilities / Special Concerns</label>
                                            <div class="d-flex flex-wrap gap-2 mb-2">
                                                @if($family->has_pregnant)
                                                    <span class="badge bg-danger rounded-pill px-3 py-2">Pregnant</span>
                                                @endif
                                                @if($family->has_pwd)
                                                    <span class="badge bg-danger rounded-pill px-3 py-2">PWD</span>
                                                @endif
                                                @if($family->has_senior)
                                                    <span class="badge bg-danger rounded-pill px-3 py-2">Senior Citizen</span>
                                                @endif
                                                @if($family->has_lactating)
                                                    <span class="badge border text-dark rounded-pill px-3 py-2 bg-light">Lactating</span>
                                                @endif
                                                @if($family->has_child_under5)
                                                    <span class="badge border text-dark rounded-pill px-3 py-2 bg-light">Child Under 5</span>
                                                @endif
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-6 col-md-4">
                                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="has_pregnant" value="1" id="upd_pregnant{{ $family->id }}" @checked($family->has_pregnant)><label class="form-check-label" for="upd_pregnant{{ $family->id }}">Pregnant</label></div>
                                                </div>
                                                <div class="col-6 col-md-4">
                                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="has_pwd" value="1" id="upd_pwd{{ $family->id }}" @checked($family->has_pwd)><label class="form-check-label" for="upd_pwd{{ $family->id }}">PWD</label></div>
                                                </div>
                                                <div class="col-6 col-md-4">
                                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="has_senior" value="1" id="upd_senior{{ $family->id }}" @checked($family->has_senior)><label class="form-check-label" for="upd_senior{{ $family->id }}">Senior</label></div>
                                                </div>
                                                <div class="col-6 col-md-4">
                                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="has_lactating" value="1" id="upd_lactating{{ $family->id }}" @checked($family->has_lactating)><label class="form-check-label" for="upd_lactating{{ $family->id }}">Lactating</label></div>
                                                </div>
                                                <div class="col-6 col-md-4">
                                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="has_child_under5" value="1" id="upd_child{{ $family->id }}" @checked($family->has_child_under5)><label class="form-check-label" for="upd_child{{ $family->id }}">Child Under 5</label></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Collective Needs --}}
                                    <div class="mt-3">
                                        <label class="form-label small fw-bold">Collective Family Needs</label>
                                        <div class="family-needs-builder" data-family-needs-builder="edit-{{ $family->id }}" data-need-options='@json($familyNeedOptions ?? [])' data-existing-needs='@json($family->needs->map(function ($need) { return ["need_name" => $need->need_name, "quantity" => $need->quantity, "is_custom" => $need->is_custom]; }))'></div>
                                        <small class="text-muted d-block mt-2">Choose a need and quantity. Selecting <strong>Others Please Specify</strong> will reveal a custom need field.</small>
                                    </div>
                                </div>
                            </div>

                            {{-- ── SECTION 2: OTHER FAMILY MEMBERS ── --}}
                            <div class="card mb-3 border-0 shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-center fw-bold small text-uppercase" style="background: #eaf0fb; color: #1B4C6D;">
                                    <span><i class="fas fa-users me-2"></i> Other Family Members</span>
                                    <button type="button" class="btn btn-sm btn-primary add-member-btn-edit" data-family="{{ $family->id }}" style="font-size:0.75rem;">
                                        <i class="fas fa-plus me-1"></i> Add Member
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div id="edit-members-container-{{ $family->id }}">
                                        @php $nonHeadMembers = $family->members->where('is_head', false)->values(); @endphp
                                        @foreach($nonHeadMembers as $mi => $member)
                                            <div class="row g-2 mb-2 align-items-end edit-member-row-{{ $family->id }}">
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-bold">Full Name</label>
                                                    <input type="text" name="other_members[{{ $mi }}][full_name]" class="form-control" value="{{ $member->full_name }}" placeholder="Member name">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label small fw-bold">Role</label>
                                                    <input type="text" name="other_members[{{ $mi }}][role]" class="form-control" value="{{ $member->role }}" placeholder="Son, Daughter...">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small fw-bold">Age</label>
                                                    <input type="number" name="other_members[{{ $mi }}][age]" class="form-control" value="{{ $member->age }}" min="0" max="150">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small fw-bold">Gender</label>
                                                    <select name="other_members[{{ $mi }}][gender]" class="form-select">
                                                        <option value="">Select...</option>
                                                        <option value="male" @selected($member->gender === 'male')>Male</option>
                                                        <option value="female" @selected($member->gender === 'female')>Female</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-1 d-flex align-items-end">
                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-edit-member w-100"><i class="fas fa-trash"></i></button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if($nonHeadMembers->isEmpty())
                                        <div class="text-muted small text-center py-2 no-edit-members-hint-{{ $family->id }}">
                                            <i class="fas fa-info-circle me-1"></i> No additional members. Click "Add Member" to include family members.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- ── SECTION 3: PERSONAL BELONGINGS ── --}}
                            <div class="card mb-3 border-0 shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-center fw-bold small text-uppercase" style="background: #eaf0fb; color: #1B4C6D;">
                                    <span><i class="fas fa-briefcase me-2"></i> Personal Belongings</span>
                                    <button type="button" class="btn btn-sm btn-outline-secondary add-belonging-btn-edit" data-family="{{ $family->id }}" style="font-size:0.75rem;">
                                        <i class="fas fa-plus me-1"></i> Add Item
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div id="edit-belongings-container-{{ $family->id }}">
                                        @php $belongings = is_array($family->personal_belongings) ? $family->personal_belongings : (json_decode($family->personal_belongings ?? '[]', true) ?? []); @endphp
                                        @foreach($belongings as $bi => $belonging)
                                            <div class="row g-2 mb-2 align-items-end edit-belonging-row">
                                                <div class="col-md-8">
                                                    <label class="form-label small fw-bold">Item</label>
                                                    <input type="text" name="edit_belongings[{{ $bi }}][name]" class="form-control" value="{{ is_array($belonging) ? ($belonging['name'] ?? '') : $belonging }}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label small fw-bold">Qty</label>
                                                    <input type="number" name="edit_belongings[{{ $bi }}][qty]" class="form-control" value="{{ is_array($belonging) ? ($belonging['qty'] ?? 1) : 1 }}" min="1">
                                                </div>
                                                <div class="col-md-1 d-flex align-items-end">
                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-edit-belonging w-100"><i class="fas fa-trash"></i></button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if(empty($belongings))
                                        <div class="text-muted small text-center py-2 no-edit-belongings-hint-{{ $family->id }}">
                                            <i class="fas fa-box-open me-1"></i> No items added yet.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- ── SECTION 4: PERSONAL PETS ── --}}
                            <div class="card mb-3 border-0 shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-center fw-bold small text-uppercase" style="background: #eaf0fb; color: #1B4C6D;">
                                    <span><i class="fas fa-paw me-2"></i> Personal Pets</span>
                                    <button type="button" class="btn btn-sm btn-outline-secondary add-pet-btn-edit" data-family="{{ $family->id }}" style="font-size:0.75rem;">
                                        <i class="fas fa-plus me-1"></i> Add Pet
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div id="edit-pets-container-{{ $family->id }}">
                                        @php $pets = is_array($family->personal_pets) ? $family->personal_pets : (json_decode($family->personal_pets ?? '[]', true) ?? []); @endphp
                                        @foreach($pets as $pi => $pet)
                                            <div class="row g-2 mb-2 align-items-end edit-pet-row">
                                                <div class="col-md-8">
                                                    <label class="form-label small fw-bold">Pet Type</label>
                                                    <input type="text" name="edit_pets[{{ $pi }}][name]" class="form-control" value="{{ is_array($pet) ? ($pet['name'] ?? '') : $pet }}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label small fw-bold">Qty</label>
                                                    <input type="number" name="edit_pets[{{ $pi }}][qty]" class="form-control" value="{{ is_array($pet) ? ($pet['qty'] ?? 1) : 1 }}" min="1">
                                                </div>
                                                <div class="col-md-1 d-flex align-items-end">
                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-edit-pet w-100"><i class="fas fa-trash"></i></button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if(empty($pets))
                                        <div class="text-muted small text-center py-2 no-edit-pets-hint-{{ $family->id }}">
                                            <i class="fas fa-paw me-1"></i> No pets added yet.
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>{{-- /modal-body --}}
                        <div class="modal-footer bg-light d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-danger fw-bold" onclick="if(confirm('Mark this family as decamped?')) { document.getElementById('decampFamilyForm{{ $family->id }}').submit(); }">DECAMP FAMILY</button>
                            <div>
                                <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">CANCEL</button>
                                <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">SAVE CHANGES</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endforeach

<!-- HISTORY VIEW MODALS FOR DECAMPED FAMILIES -->
@foreach($families as $family)
    @if($family->checked_out_at)
        <div class="modal fade" id="historyViewModal{{ $family->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="background-color: #f4f6fb;">
                    <div class="modal-header rounded-top-0 border-0" style="background-color: #17244c; color: white;">
                        <div>
                            <h5 class="modal-title fw-bold text-uppercase mb-1" style="font-family: 'Rajdhani', sans-serif; letter-spacing: 1px;"><i class="fas fa-clipboard-list me-2 text-info"></i>REGISTER HISTORY VIEW</h5>
                            <div style="font-size: 0.85rem; color: #a1b0cb;">Family ID #{{ $family->id }} - Decamped {{ optional($family->checked_out_at)->format('M d, Y h:i A') }}</div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-4">
                            <!-- FAMILY REGISTRATION DETAILS -->
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm rounded-3">
                                    <div class="card-header text-white fw-bold py-3 text-uppercase" style="background-color: #17244c; font-size: 0.85rem; letter-spacing: 0.5px;">
                                        <i class="fas fa-user-circle me-2"></i> FAMILY REGISTRATION DETAILS
                                    </div>
                                    <div class="card-body px-4 py-4">
                                        <div class="d-flex mb-3">
                                            <div class="text-muted fw-bold" style="width: 40%; font-size: 0.8rem;">HEAD OF FAMILY</div>
                                            <div class="fw-bold" style="width: 60%;">{{ $family->head_family_name }}</div>
                                        </div>
                                        <div class="d-flex mb-3">
                                            <div class="text-muted fw-bold" style="width: 40%; font-size: 0.8rem;">REGISTRANT NAME</div>
                                            <div class="fw-bold" style="width: 60%;">{{ optional($family->registrant)->name ?? 'N/A' }}</div>
                                        </div>
                                        <div class="d-flex mb-3">
                                            <div class="text-muted fw-bold" style="width: 40%; font-size: 0.8rem;">CONTACT NUMBER</div>
                                            <div class="fw-bold" style="width: 60%;">{{ $family->contact_number ?: 'N/A' }}</div>
                                        </div>
                                        <div class="d-flex mb-3">
                                            <div class="text-muted fw-bold" style="width: 40%; font-size: 0.8rem;">STREET ADDRESS</div>
                                            <div class="fw-bold" style="width: 60%;">{{ $family->street ?: 'N/A' }}</div>
                                        </div>
                                        <div class="d-flex mb-3">
                                            <div class="text-muted fw-bold" style="width: 40%; font-size: 0.8rem;">BARANGAY</div>
                                            <div class="fw-bold" style="width: 60%;">{{ $family->barangay ?: 'N/A' }}</div>
                                        </div>
                                        <div class="d-flex mb-3">
                                            <div class="text-muted fw-bold" style="width: 40%; font-size: 0.8rem;">CITY / MUNICIPALITY</div>
                                            <div class="fw-bold" style="width: 60%;">{{ $family->city ?: 'N/A' }}</div>
                                        </div>
                                        <div class="d-flex mb-3">
                                            <div class="text-muted fw-bold" style="width: 40%; font-size: 0.8rem;">CHECKED IN</div>
                                            <div class="fw-bold" style="width: 60%;">{{ $family->created_at->format('M d, Y h:i A') }}</div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="text-muted fw-bold" style="width: 40%; font-size: 0.8rem;">CHECKED OUT</div>
                                            <div class="fw-bold" style="width: 60%;">{{ optional($family->checked_out_at)->format('M d, Y h:i A') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- REGISTRATION FLAGS & REQUESTS -->
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm rounded-3">
                                    <div class="card-header text-white fw-bold py-3 text-uppercase" style="background-color: #17244c; font-size: 0.85rem; letter-spacing: 0.5px;">
                                        <i class="fas fa-flag me-2"></i> REGISTRATION FLAGS & REQUESTS
                                    </div>
                                    <div class="card-body px-4 py-4">
                                        <div class="mb-4">
                                            @if($family->has_pregnant)<span class="badge bg-danger rounded-pill px-3 py-2 me-1 mb-1">Pregnant</span>@endif
                                            @if($family->has_pwd)<span class="badge bg-danger rounded-pill px-3 py-2 me-1 mb-1">PWD</span>@endif
                                            @if($family->has_senior)<span class="badge bg-danger rounded-pill px-3 py-2 me-1 mb-1">Senior</span>@endif
                                            @if($family->has_lactating)<span class="badge border text-dark rounded-pill px-3 py-2 me-1 mb-1 bg-light">Lactating</span>@endif
                                            @if($family->has_child_under5)<span class="badge border text-dark rounded-pill px-3 py-2 mb-1 bg-light">Child Under 5</span>@endif
                                            @if(!$family->has_pregnant && !$family->has_pwd && !$family->has_senior && !$family->has_lactating && !$family->has_child_under5)
                                                <span class="text-muted fst-italic">No flags recorded</span>
                                            @endif
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="text-muted fw-bold mb-1" style="font-size: 0.8rem;">PERSONAL BELONGINGS</div>
                                            <div class="fw-bold fs-6">
                                                {{ is_array($family->personal_belongings) && count($family->personal_belongings) > 0 ? collect($family->personal_belongings)->map(function($b) { return $b['name'] . (isset($b['qty']) && $b['qty'] > 1 ? ' x'.$b['qty'] : ''); })->implode(', ') : 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="text-muted fw-bold mb-1" style="font-size: 0.8rem;">SPECIAL NEEDS</div>
                                            <div class="fw-bold fs-6">{{ $family->other_needs_details ?: 'N/A' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-muted fw-bold mb-1" style="font-size: 0.8rem;">FAMILY PETS</div>
                                            <div class="fw-bold fs-6">
                                                {{ is_array($family->personal_pets) && count($family->personal_pets) > 0 ? collect($family->personal_pets)->map(function($p) { return $p['name'] . (isset($p['qty']) && $p['qty'] > 1 ? ' x'.$p['qty'] : ''); })->implode(', ') : 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- HOUSEHOLD MEMBERS -->
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm rounded-3">
                                    <div class="card-header text-white fw-bold py-3 text-uppercase" style="background-color: #17244c; font-size: 0.85rem; letter-spacing: 0.5px;">
                                        <i class="fas fa-users me-2"></i> HOUSEHOLD MEMBERS
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                                            <table class="table table-hover mb-0" style="font-size: 0.9rem;">
                                                <thead class="bg-light sticky-top">
                                                    <tr>
                                                        <th class="text-muted fw-bold text-uppercase border-0 px-4 py-3" style="font-size: 0.75rem;">NAME</th>
                                                        <th class="text-muted fw-bold text-uppercase border-0 py-3" style="font-size: 0.75rem;">ROLE</th>
                                                        <th class="text-muted fw-bold text-uppercase border-0 py-3 text-center" style="font-size: 0.75rem;">AGE</th>
                                                        <th class="text-muted fw-bold text-uppercase border-0 py-3" style="font-size: 0.75rem;">GENDER</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($family->members as $member)
                                                    <tr>
                                                        <td class="fw-bold px-4 py-3">{{ $member->full_name }}</td>
                                                        <td class="py-3 text-muted">{{ $member->is_head ? 'Head' : 'Member' }}</td>
                                                        <td class="py-3 text-center">{{ $member->age }}</td>
                                                        <td class="py-3 text-uppercase" style="font-size: 0.8rem;">{{ $member->gender }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- REQUESTED NEEDS -->
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm rounded-3">
                                    <div class="card-header text-white fw-bold py-3 text-uppercase" style="background-color: #17244c; font-size: 0.85rem; letter-spacing: 0.5px;">
                                        <i class="fas fa-box-open me-2"></i> REQUESTED NEEDS
                                    </div>
                                    <div class="card-body px-4 py-4">
                                        @if($family->needs && $family->needs->count() > 0)
                                            @foreach($family->needs as $need)
                                                <span class="badge border text-dark rounded-2 px-3 py-2 me-2 mb-2 bg-white shadow-sm" style="font-weight: 500; font-size: 0.85rem;">{{ $need->need_name }} x{{ $need->quantity }}</span>
                                            @endforeach
                                        @else
                                            <div class="text-muted fst-italic">No requested needs recorded</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-white rounded-bottom-3 py-3">
                        <button type="button" class="btn btn-secondary px-4 fw-bold" style="background-color: #6c757d;" data-bs-dismiss="modal">CLOSE</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

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
                <div class="modal-body p-4 text-dark">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">SITE OPERATIONAL STATUS</label>
                        <select name="usage_status" class="form-select form-select-lg">
                            <option value="cleared" @selected($ec->usage_status === 'cleared')>CLEARED / STANDBY</option>
                            <option value="occupied" @selected($ec->usage_status === 'occupied')>OCCUPIED / ACTIVE</option>
                        </select>
                        <small class="text-muted">FULL and DECAMP are system-derived based on occupancy and family decamp records.</small>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">SITE CAPACITY</label>
                        <input type="number" name="capacity" class="form-control form-control-lg" min="0" value="{{ old('capacity', $ec->capacity) }}" placeholder="Enter evacuation capacity">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">RESOURCE INVENTORY SUMMARY</label>
                        <textarea name="emergency_resources" rows="2" class="form-control" placeholder="e.g. 50 Hygiene Kits, 100 Blankets">{{ old('emergency_resources', $ec->emergency_resources) }}</textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold text-muted small">LATEST SITUATION REPORT (SITREP)</label>
                        <textarea name="reports_status" rows="3" class="form-control" placeholder="Describe current issues, damages, or requests...">{{ old('reports_status', $ec->reports_status) }}</textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">SAVE CHANGES</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
/* ── Search Filters & Pagination ───────────────────────── */
document.addEventListener('DOMContentLoaded', function () {
    function setupTableSearchAndPagination(inputId, rowClass, paginationId, infoId, itemsPerPage) {
        const input = document.getElementById(inputId);
        const rows = Array.from(document.querySelectorAll('.' + rowClass));
        const pagination = document.getElementById(paginationId);
        const info = document.getElementById(infoId);
        
        if (!input || !pagination || !info) return;

        let currentPage = 1;
        let filteredRows = [...rows];

        function render() {
            rows.forEach(r => r.style.display = 'none');
            
            const totalItems = filteredRows.length;
            const totalPages = Math.ceil(totalItems / itemsPerPage) || 1;
            
            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;
            
            const start = (currentPage - 1) * itemsPerPage;
            const end = Math.min(start + itemsPerPage, totalItems);
            
            for (let i = start; i < end; i++) {
                filteredRows[i].style.display = '';
            }
            
            if (totalItems === 0) {
                info.innerHTML = 'Showing 0 records';
            } else {
                info.innerHTML = `Showing ${start + 1} to ${end} of ${totalItems}`;
            }
            
            let html = '';
            html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${currentPage - 1}">&laquo;</a></li>`;
            
            for (let p = 1; p <= totalPages; p++) {
                if (totalPages > 5 && p !== 1 && p !== totalPages && Math.abs(p - currentPage) > 1) {
                    if (Math.abs(p - currentPage) === 2) {
                        html += `<li class="page-item disabled"><a class="page-link" href="#">...</a></li>`;
                    }
                    continue;
                }
                html += `<li class="page-item ${p === currentPage ? 'active' : ''}"><a class="page-link" href="#" data-page="${p}">${p}</a></li>`;
            }
            
            html += `<li class="page-item ${currentPage === totalPages || totalPages === 0 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${currentPage + 1}">&raquo;</a></li>`;
            
            pagination.innerHTML = html;
            
            pagination.querySelectorAll('.page-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = parseInt(this.getAttribute('data-page'));
                    if (page && page >= 1 && page <= totalPages) {
                        currentPage = page;
                        render();
                    }
                });
            });
        }
        
        input.addEventListener('input', function () {
            const term = this.value.toLowerCase();
            filteredRows = rows.filter(row => row.textContent.toLowerCase().includes(term));
            currentPage = 1;
            render();
        });
        
        render(); // Initial load
    }
    
    setupTableSearchAndPagination('searchActiveFamilies', 'active-family-row', 'paginationActiveFamilies', 'infoActiveFamilies', 5);
    setupTableSearchAndPagination('searchDecampedFamilies', 'decamped-family-row', 'paginationDecampedFamilies', 'infoDecampedFamilies', 5);

    /* ── Family Needs Builder ───────────────────────────────── */
    function initializeFamilyNeedsBuilder(builder) {
        if (!builder || builder.dataset.initialized === '1') {
            return;
        }

        const needOptions = JSON.parse(builder.dataset.needOptions || '[]');
        const existingNeeds = JSON.parse(builder.dataset.existingNeeds || '[]');
        let rowIndex = 0;

        const buildOptions = (selectedValue = '') => {
            const options = ['<option value="">-- Select need --</option>'];

            needOptions.forEach((need) => {
                const selected = need === selectedValue ? ' selected' : '';
                options.push(`<option value="${need}"${selected}>${need}</option>`);
            });

            if (!needOptions.includes('Others Please Specify')) {
                const selected = selectedValue === 'Others Please Specify' ? ' selected' : '';
                options.push(`<option value="Others Please Specify"${selected}>Others Please Specify</option>`);
            }

            return options.join('');
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
                        ${buildOptions(selectedNeed && !isCustom ? selectedNeed : (isCustom ? 'Others Please Specify' : ''))}
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

        builder.dataset.initialized = '1';
    }

    document.querySelectorAll('[data-family-needs-builder]').forEach(initializeFamilyNeedsBuilder);
});
    /* ── Edit Modal: Dynamic Add Member / Belonging / Pet ── */
    document.addEventListener('click', function (e) {
        // Add Member
        if (e.target.closest('.add-member-btn-edit')) {
            const btn = e.target.closest('.add-member-btn-edit');
            const familyId = btn.dataset.family;
            const container = document.getElementById('edit-members-container-' + familyId);
            const hint = document.querySelector('.no-edit-members-hint-' + familyId);
            if (hint) hint.classList.add('d-none');
            const idx = container.querySelectorAll('.edit-member-row-' + familyId).length;
            const row = document.createElement('div');
            row.className = 'row g-2 mb-2 align-items-end edit-member-row-' + familyId;
            row.innerHTML = `
                <div class="col-md-4"><label class="form-label small fw-bold">Full Name</label><input type="text" name="other_members[${idx}][full_name]" class="form-control" placeholder="Member name"></div>
                <div class="col-md-3"><label class="form-label small fw-bold">Role</label><input type="text" name="other_members[${idx}][role]" class="form-control" placeholder="Son, Daughter..."></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Age</label><input type="number" name="other_members[${idx}][age]" class="form-control" min="0" max="150"></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Gender</label><select name="other_members[${idx}][gender]" class="form-select"><option value="">Select...</option><option value="male">Male</option><option value="female">Female</option></select></div>
                <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-outline-danger btn-sm remove-edit-member w-100"><i class="fas fa-trash"></i></button></div>
            `;
            container.appendChild(row);
        }

        // Remove Member
        if (e.target.closest('.remove-edit-member')) {
            e.target.closest('[class*="edit-member-row"]').remove();
        }

        // Add Belonging
        if (e.target.closest('.add-belonging-btn-edit')) {
            const btn = e.target.closest('.add-belonging-btn-edit');
            const familyId = btn.dataset.family;
            const container = document.getElementById('edit-belongings-container-' + familyId);
            const hint = document.querySelector('.no-edit-belongings-hint-' + familyId);
            if (hint) hint.classList.add('d-none');
            const idx = container.querySelectorAll('.edit-belonging-row').length;
            const row = document.createElement('div');
            row.className = 'row g-2 mb-2 align-items-end edit-belonging-row';
            row.innerHTML = `
                <div class="col-md-8"><label class="form-label small fw-bold">Item</label><input type="text" name="edit_belongings[${idx}][name]" class="form-control" placeholder="e.g. Blanket"></div>
                <div class="col-md-3"><label class="form-label small fw-bold">Qty</label><input type="number" name="edit_belongings[${idx}][qty]" class="form-control" value="1" min="1"></div>
                <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-outline-danger btn-sm remove-edit-belonging w-100"><i class="fas fa-trash"></i></button></div>
            `;
            container.appendChild(row);
        }

        // Remove Belonging
        if (e.target.closest('.remove-edit-belonging')) {
            e.target.closest('.edit-belonging-row').remove();
        }

        // Add Pet
        if (e.target.closest('.add-pet-btn-edit')) {
            const btn = e.target.closest('.add-pet-btn-edit');
            const familyId = btn.dataset.family;
            const container = document.getElementById('edit-pets-container-' + familyId);
            const hint = document.querySelector('.no-edit-pets-hint-' + familyId);
            if (hint) hint.classList.add('d-none');
            const idx = container.querySelectorAll('.edit-pet-row').length;
            const row = document.createElement('div');
            row.className = 'row g-2 mb-2 align-items-end edit-pet-row';
            row.innerHTML = `
                <div class="col-md-8"><label class="form-label small fw-bold">Pet Type</label><input type="text" name="edit_pets[${idx}][name]" class="form-control" placeholder="e.g. Dog"></div>
                <div class="col-md-3"><label class="form-label small fw-bold">Qty</label><input type="number" name="edit_pets[${idx}][qty]" class="form-control" value="1" min="1"></div>
                <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-outline-danger btn-sm remove-edit-pet w-100"><i class="fas fa-trash"></i></button></div>
            `;
            container.appendChild(row);
        }

        // Remove Pet
        if (e.target.closest('.remove-edit-pet')) {
            e.target.closest('.edit-pet-row').remove();
        }
    });
</script>
@endpush
@include('typhoon.partials.choose-school-modal')
@endsection
