<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alarm Systems - Fire Safety</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --fire-red: #A8191F;
            --fire-dark-red: #8A1217;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        .top-nav {
            background-color: var(--fire-red);
            height: 60px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .sidebar {
            background-color: var(--fire-red);
            width: 250px;
            position: fixed;
            top: 60px;
            left: 0;
            bottom: 0;
            z-index: 1020;
            overflow-y: auto;
        }

        .main-content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 20px;
            min-height: calc(100vh - 60px);
            background-color: #f8f9fa;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9);
            padding: 12px 20px;
            display: flex;
            align-items: center;
        }

        .nav-link:hover, .nav-link.active {
            background-color: var(--fire-dark-red);
            color: white;
            text-decoration: none;
        }

        .nav-link.active {
            border-left: 4px solid white;
        }

        .nav-icon {
            width: 24px;
            margin-right: 10px;
            text-align: center;
        }

        .dashboard-card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .alarm-status {
            font-size: 1rem;
            font-weight: 500;
        }

        .status-functional { color: #28a745; }
        .status-online { color: #28a745; }
        .status-broken { color: #dc3545; }
        .status-offline { color: #dc3545; }
        .status-jammed { color: #ffc107; }
        .status-under-repair { color: #ffc107; }
        .status-maintenance { color: #ffc107; }
        .status-missing { color: #6c757d; }
        .status-not-installed { color: #6c757d; }
        .status-system-error { color: #dc3545; }
        .status-decommissioned { color: #6c757d; }

        .test-overdue {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
        }
        /* Update existing nav-tabs styles */
        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
        }

        .nav-tabs .nav-link {
            color: #495057;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-bottom: none;
            border-top-left-radius: 0.25rem;
            border-top-right-radius: 0.25rem;
            margin-bottom: -1px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .nav-tabs .nav-link:hover {
            color: white;
            background-color: #8A1217;
            border-color: #8A1217 #8A1217 #dee2e6;
        }

        .nav-tabs .nav-link.active {
            color: white !important;
            background-color: #8A1217 !important;
            border-color: #8A1217 #8A1217 #8A1217 !important;
            position: relative;
            z-index: 1;
        }

        .nav-tabs .nav-link:not(.active):not(:hover) {
            background-color: #f8f9fa;
        }

        .nav-tabs .nav-link:focus {
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(168, 25, 31, 0.25);
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="top-nav">
        <div class="container-fluid h-100">
            <div class="row h-100 align-items-center">
                <div class="col-auto">
                    <a href="{{ route('fire-safety.dashboard') }}" class="text-white text-decoration-none">
                        <i class="fas fa-arrow-left me-2"></i>
                        <i class="fas fa-fire me-2"></i>
                        <span class="fw-bold">Fire Safety Checklist System</span>
                    </a>
                </div>

                <div class="col text-center">
                    <h4 class="text-white mb-0">Alarm System Management</h4>
                </div>

                <div class="col-auto">
                    <div class="d-flex align-items-center">
                        <!-- Notifications -->
                        <div class="dropdown me-3">
                            <a href="#" class="text-white position-relative" data-bs-toggle="dropdown">
                                <i class="fas fa-bell fa-lg"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    0
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <h6 class="dropdown-header">Notifications</h6>
                                <div class="dropdown-item text-muted">No new notifications</div>
                            </div>
                        </div>

                        <div class="dropdown">
                            <a href="#" class="text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle fa-lg me-2"></i>
                                <span>{{ Auth::user()->name ?? 'User' }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('fire-safety.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                   <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="p-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('fire-safety.dashboard') }}">
                        <span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('fire-safety.buildings') }}">
                        <span class="nav-icon"><i class="fas fa-building"></i></span>
                        <span>Buildings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('fire-safety.alarm-systems') }}">
                        <span class="nav-icon"><i class="fas fa-bell"></i></span>
                        <span>Alarm Systems</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('fire-safety.extinguishers') }}">
                        <span class="nav-icon"><i class="fas fa-fire-extinguisher"></i></span>
                        <span>Fire Extinguishers & Rooms</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('fire-safety.evacuation-plans') }}">
                        <span class="nav-icon"><i class="fas fa-map-signs"></i></span>
                        <span>Evacuation Plans</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('fire-safety.customization') }}">
                        <span class="nav-icon"><i class="fas fa-cog"></i></span>
                        <span>Customization</span>
                    </a>
                </li>
            </ul>

            <hr class="bg-white my-4">

            <!-- Quick Actions -->
            <div class="mt-4">
                <h6 class="text-white mb-3">Quick Actions</h6>
                <div class="d-grid gap-2">
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addAlarmModal">
                        <i class="fas fa-plus me-2"></i> Add Alarm System
                    </button>
                    <button class="btn btn-light btn-sm" id="simulateAlarmBtn">
                        <i class="fas fa-bell me-2"></i> Simulate Alarm Test
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alarm Details & Update Modal -->
    <div class="modal fade" id="alarmDetailsModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i> Alarm System Details & Update
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="updateAlarmForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="alarm_id" id="updateAlarmId">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">School</label>
                                <p class="form-control-plaintext border-bottom" id="updateDisplaySchool">Loading...</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Building(s)</label>
                                <p class="form-control-plaintext border-bottom" id="updateDisplayBuildings">Loading...</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Alarm Code *</label>
                                <input type="text" class="form-control" name="code" id="updateAlarmCode" required>
                                <input type="hidden" id="originalAlarmCode">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Alarm Type</label>
                                <input type="text" class="form-control bg-light" id="updateAlarmTypeDisplay" readonly disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Status *</label>
                                <select class="form-control" name="status" id="updateStatusSelect" required>
                                    <!-- Options populated by JS -->
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Next Test Due *</label>
                                <input type="date" class="form-control" name="next_test_due" id="updateNextTestDue" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Manufacturer</label>
                                <input type="text" class="form-control" name="manufacturer" id="updateManufacturer">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Installation Date</label>
                                <input type="date" class="form-control" name="installation_date" id="updateInstallationDateInput">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Notes/Remarks</label>
                            <textarea class="form-control" name="notes" id="updateNotes" rows="3"></textarea>
                        </div>
                        
                        <div class="alert alert-info py-2">
                            <i class="fas fa-info-circle me-1"></i> <strong>Note:</strong> Updating this information will set the "As Of" (Last Test) date to today.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    @if(Auth::user()->role === 'admin')
                    <button type="button" class="btn btn-danger me-auto" onclick="removeAlarmSystem()">
                        <i class="fas fa-trash me-2"></i> Remove
                    </button>
                    @endif
                    <button type="button" class="btn btn-primary" onclick="updateAlarmSystem()">
                        <i class="fas fa-save me-2"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- THIS IS WHERE THE INSTRUCTION APPLIES (around line 130) -->
        @if($schools->isEmpty())
        <div class="row">
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-school fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted mb-3">No Schools Found</h4>
                        <p class="text-muted mb-4">You need to add a school that will be under inspection first.</p>
                        <a href="{{ route('fire-safety.dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Go to Dashboard to Add School
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- School Tabs (ALWAYS SHOWN) -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="schoolTab">
                            @foreach($schools as $school)
                            <li class="nav-item">
                                <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                        data-bs-toggle="tab"
                                        data-bs-target="#school-{{ $school->id }}"
                                        data-school-id="{{ $school->id }}">
                                    {{ $school->school_name }}
                                </button>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tab Content -->
        <div class="tab-content" id="schoolTabContent">
            @foreach($schools as $school)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="school-{{ $school->id }}">
                <!-- System Overview Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card dashboard-card border-left-success h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                            Functional
                                        </div>
                                        <div class="h2 mb-0 fw-bold text-gray-800">
                                            {{ $school->alarmSystems()->whereIn('status', ['functional', 'online'])->count() }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card dashboard-card border-left-danger h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs fw-bold text-danger text-uppercase mb-1">
                                            Issues
                                        </div>
                                        <div class="h2 mb-0 fw-bold text-gray-800">
                                            {{ $school->alarmSystems()->whereNotIn('status', ['functional', 'online'])->count() }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card dashboard-card border-left-warning h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                            Needs Testing
                                        </div>
                                        <div class="h2 mb-0 fw-bold text-gray-800">
                                            {{ $school->alarmSystems()->where('next_test_due', '<', now())->count() }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card dashboard-card border-left-info h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                            Last Inspected
                                        </div>
                                        <div class="small fw-bold text-gray-800">
                                            @php
                                                $latestTested = $school->alarmSystems()
                                                    ->whereNotNull('last_test')
                                                    ->orderBy('last_test', 'desc')
                                                    ->take(2)
                                                    ->get();
                                            @endphp
                                            @if($latestTested->count() > 0)
                                                @foreach($latestTested as $tested)
                                                    <div class="mb-1">
                                                        <strong>{{ $tested->code }}</strong><br>
                                                        <small class="text-muted">{{ \Carbon\Carbon::parse($tested->last_test)->format('Y-m-d') }}</small>
                                                    </div>
                                                @endforeach
                                            @else
                                                <span class="text-muted">No tests recorded</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-alt fa-2x text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alarm Systems Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card dashboard-card">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-list me-2"></i> Alarm Systems List - {{ $school->school_name }}
                                </h6>
                                <div class="d-flex">
                                    <button class="btn btn-sm ms-2" 
                                            style="background-color: #e9ecef; color: #495057; border: 1px solid #ced4da;"
                                            onclick="openAlarmHistoryModal({{ $school->id }})">
                                        <i class="fas fa-history me-1"></i> Removed Alarm System
                                    </button>
                                    <button class="btn btn-primary btn-sm add-alarm-btn ms-2"
                                            data-school-id="{{ $school->id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#addAlarmModal">
                                        <i class="fas fa-plus me-2"></i> Add New Alarm
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Code</th>
                                                <th>Building</th>
                                                <th>Type</th>
                                                <th>Status</th>
                                                <th>AS OF</th>
                                                <th>Next Test Due</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($school->alarmSystems as $alarm)
                                            @php
                                                $isOverdue = false; // Temporarily disable overdue check
                                                $building = $alarm->building ?? null;
                                            @endphp
                                            <tr class="{{ $isOverdue ? 'test-overdue' : '' }}">
                                                <td>{{ $alarm->code }}</td>
                                                <td>
                                                    @if($alarm->buildings->count() > 0)
                                                        @foreach($alarm->buildings as $b)
                                                            <span class="badge bg-secondary mb-1">{{ $b->building_no }}</span>
                                                        @endforeach
                                                    @elseif($alarm->building)
                                                        <span class="badge bg-secondary">{{ $alarm->building->building_no }}</span>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>{{ $alarm->alarm_type }}</td>
                                                <td>
                                                    @php
                                                        $statusClass = 'status-' . str_replace(' ', '-', strtolower($alarm->status));
                                                    @endphp
                                                    <span class="alarm-status {{ $statusClass }}">
                                                        <i class="fas fa-circle"></i> {{ ucfirst($alarm->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $alarm->last_test ? \Carbon\Carbon::parse($alarm->last_test)->format('Y-m-d') : 'Never' }}</td>
                                                <td>
                                                    {{ $alarm->next_test_due ? \Carbon\Carbon::parse($alarm->next_test_due)->format('Y-m-d') : 'Not set' }}
                                                    @if($isOverdue)
                                                        <span class="badge bg-danger ms-2">Overdue</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="btn btn-sm btn-outline-primary test-now-btn"
                                                                data-alarm-id="{{ $alarm->id }}"
                                                                data-alarm-code="{{ $alarm->code }}">
                                                            <i class="fas fa-play"></i> Test Now
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-info update-alarm-btn"
                                                                data-alarm-id="{{ $alarm->id }}">
                                                            <i class="fas fa-edit"></i> Details
                                                        </button>
                                                        @if(Auth::user()->role === 'admin')
                                                        <button class="btn btn-sm btn-outline-danger remove-alarm-btn-table"
                                                                onclick="currentAlarmId = '{{ $alarm->id }}'; removeAlarmSystem();">
                                                            <i class="fas fa-trash"></i> Remove
                                                        </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testing Schedule -->
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <div class="card dashboard-card">
                            <div class="card-header py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-calendar-check me-2"></i> Upcoming Tests - {{ $school->school_name }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                @foreach($school->alarmSystems->where('next_test_due', '>=', now())->sortBy('next_test_due')->take(5) as $alarm)
                                @php
                                    $nextTest = \Carbon\Carbon::parse($alarm->next_test_due);
                                    $borderClass = $nextTest->diffInDays(now()) <= 7 ? 'border-warning' : 'border-info';
                                @endphp
                                <div class="col-md-4 mb-3">
                                    <div class="card {{ $borderClass }}">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $alarm->code }}</h6>
                                            <p class="card-text mb-1">
                                            <p class="card-text mb-1">
                                                <small class="text-muted">Building: 
                                                    @if($alarm->buildings->count() > 0)
                                                        {{ $alarm->buildings->pluck('building_no')->implode(', ') }}
                                                    @else
                                                        {{ $alarm->building->building_no ?? 'N/A' }}
                                                    @endif
                                                </small>
                                            </p>
                                            </p>
                                            <p class="card-text mb-1">
                                                <small class="text-muted">Type: {{ $alarm->alarm_type }}</small>
                                            </p>
                                            <p class="card-text">
                                                <strong>Due: {{ \Carbon\Carbon::parse($alarm->next_test_due)->format('Y-m-d') }}</strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Add Alarm System Modal -->
    <div class="modal fade" id="addAlarmModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i> Add New Alarm System
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addAlarmForm" action="{{ route('fire-safety.alarm.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="school_id" id="modalSchoolId">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Alarm Code *</label>
                        <input type="text" class="form-control" name="code" id="alarmCode" placeholder="e.g., ALM-001" required onblur="checkAlarmCode(this.value)">
                        <div class="invalid-feedback" id="codeError">Alarm code already exists</div>
                    </div>
                <div class="mb-3">
                    <label class="form-label">Does this alarm cover multiple buildings?</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_multi_building" id="multiBuildingToggle" value="1">
                        <label class="form-check-label" for="multiBuildingToggle">Yes, it covers multiple buildings</label>
                    </div>
                </div>

                <div class="row" id="singleBuildingContainer">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Primary Building *</label>
                        <select class="form-control" name="building_id" id="buildingSelect">
                            <option value="">Select Building</option>
                            <!-- Buildings will be populated by JavaScript -->
                        </select>
                    </div>
                </div>

                <div class="row" id="multiBuildingContainer" style="display: none;">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Select All Buildings Covered *</label>
                        <div id="buildingsCheckboxList" class="border rounded p-3 bg-light" style="max-height: 200px; overflow-y: auto;">
                            <!-- Building checkboxes will be populated here -->
                            <p class="text-muted small mb-0">Select buildings...</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Floor *</label>
                        <select class="form-control" id="alarmFloorSelect" required disabled>
                            <option value="">Select Building First</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Specific Location *</label>
                        <input type="text" class="form-control" id="alarmSpecificLocation" placeholder="e.g., Hallway, Near Room 101" required>
                        <input type="hidden" name="location" id="finalLocation">
                    </div>
                </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Alarm Type *</label>
                                <select class="form-control" name="alarm_type" id="alarmTypeSelect" required>
                                    <option value="">Select Type</option>
                                    <option value="Bell">Bell</option>
                                    <option value="Mechanical">Mechanical</option>
                                    <option value="Digital">Digital</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status *</label>
                                <select class="form-control" name="status" id="statusSelect" required>
                                    <option value="">Select Status</option>
                                    <!-- Options will be populated based on alarm type -->
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Manufacturer (Optional)</label>
                                <input type="text" class="form-control" name="manufacturer" placeholder="Manufacturer name">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Installation Date</label>
                                <input type="date" class="form-control" name="installation_date" id="installationDate">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Last Test Date</label>
                                <input type="date" class="form-control" name="last_test" id="lastTestDate">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Next Test Due *</label>
                                <input type="date" class="form-control" name="next_test_due" id="nextTestDue" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes/Remarks</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Additional information..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveAlarmSystem()">
                        <i class="fas fa-save me-2"></i> Save Alarm System
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alarm System Removal Modal -->
    <div class="modal fade" id="alarmRemovalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Remove Alarm System</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="fw-bold">Are you sure you want to remove this alarm system?</p>
                    <p class="text-muted small">This action cannot be undone. All historical data for this alarm will be moved to the archives.</p>
                    
                    <div class="mt-4">
                        <label class="form-label fw-bold">Reason to be removed *</label>
                        <textarea class="form-control" id="alarmRemovalReason" rows="3" placeholder="Enter reason for removal..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="confirmRemoveAlarm()">
                        <i class="fas fa-trash-alt me-2"></i>Yes, Remove It!
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alarm System History Modal -->
    <div class="modal fade" id="alarmHistoryModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #6c757d; color: white;">
                    <h5 class="modal-title"><i class="fas fa-history me-2"></i>Alarm System's History</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm" id="alarmHistoryTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Date Removed</th>
                                    <th>Code</th>
                                    <th>Type</th>
                                    <th>Last Location</th>
                                    <th>Reason to be removed</th>
                                    <th>Last Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data populated via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Status options based on alarm type
        const statusOptions = {
            'Bell': ['Functional', 'Broken', 'Missing', 'Not Installed'],
            'Mechanical': ['Functional', 'Missing', 'Jammed', 'Under Repair', 'Not Installed'],
            'Digital': ['Online', 'Offline', 'Missing', 'Not Installed', 'System Error', 'Under Maintenance', 'Decommissioned']
        };

        // Store current school and alarm data
        let currentSchoolId = null;
        let currentAlarmId = null;
        let currentAlarmType = null;

        // School tab switching
        document.querySelectorAll('#schoolTab button').forEach(button => {
            button.addEventListener('click', function() {
                currentSchoolId = this.getAttribute('data-school-id');
                console.log('School changed to:', currentSchoolId);
            });
        });

        // Set initial school
        const firstTab = document.querySelector('#schoolTab button.active');
        if (firstTab) {
            currentSchoolId = firstTab.getAttribute('data-school-id');
        }

        // Toggle multi-building selection
        document.getElementById('multiBuildingToggle').addEventListener('change', function() {
            const singleContainer = document.getElementById('singleBuildingContainer');
            const multiContainer = document.getElementById('multiBuildingContainer');
            const buildingSelect = document.getElementById('buildingSelect');
            const floorSelect = document.getElementById('alarmFloorSelect');

            if (this.checked) {
                singleContainer.style.display = 'none';
                multiContainer.style.display = 'block';
                buildingSelect.removeAttribute('required');
                floorSelect.disabled = true;
                floorSelect.value = "";
                document.getElementById('alarmSpecificLocation').value = "Multiple Buildings";
                document.getElementById('finalLocation').value = "Multiple Buildings - Shared System";
            } else {
                singleContainer.style.display = 'flex';
                multiContainer.style.display = 'none';
                buildingSelect.setAttribute('required', 'required');
                // Re-enable floor select if a building is selected
                if (buildingSelect.value) {
                    floorSelect.disabled = false;
                } else {
                    floorSelect.disabled = true;
                }
                // Clear the multi-building location text
                document.getElementById('alarmSpecificLocation').value = "";
                document.getElementById('finalLocation').value = "";
            }
        });

        // Add Alarm button click
        document.querySelectorAll('.add-alarm-btn').forEach(button => {
            button.addEventListener('click', function() {
                const schoolId = this.getAttribute('data-school-id');
                document.getElementById('modalSchoolId').value = schoolId;
                
                // Reset toggle
                document.getElementById('multiBuildingToggle').checked = false;
                document.getElementById('singleBuildingContainer').style.display = 'flex';
                document.getElementById('multiBuildingContainer').style.display = 'none';

                // Load buildings for this school
                loadBuildings(schoolId);
            });
        });

        // Alarm type change handler for Add modal
        document.getElementById('alarmTypeSelect').addEventListener('change', function() {
            const type = this.value;
            const statusSelect = document.getElementById('statusSelect');

            statusSelect.innerHTML = '<option value="">Select Status</option>';

            if (type && statusOptions[type]) {
                statusOptions[type].forEach(status => {
                    const option = document.createElement('option');
                    option.value = status.toLowerCase().replace(' ', '_');
                    option.textContent = status;
                    statusSelect.appendChild(option);
                });
            }
        });

        // Update Alarm button click (using delegation)
        document.body.addEventListener('click', async function(e) {
            const button = e.target.closest('.update-alarm-btn');
            if (!button) return;
            
            console.log('Update button clicked for alarm:', button.getAttribute('data-alarm-id'));

            const alarmId = button.getAttribute('data-alarm-id');
            currentAlarmId = alarmId;

            try {
                const response = await fetch(`/fire-safety/alarm/${alarmId}`);
                const alarm = await response.json();

                currentAlarmType = alarm.alarm_type;

                // Populate form
                document.getElementById('updateAlarmId').value = alarmId;
                document.getElementById('updateAlarmCode').value = alarm.code;
                document.getElementById('originalAlarmCode').value = alarm.code;
                document.getElementById('updateAlarmTypeDisplay').value = alarm.alarm_type;
                document.getElementById('updateDisplaySchool').textContent = alarm.school ? alarm.school.school_name : 'N/A';
                
                // Display Buildings
                let buildingsStr = 'N/A';
                if (alarm.buildings && alarm.buildings.length > 0) {
                    buildingsStr = alarm.buildings.map(b => b.building_no).join(', ');
                } else if (alarm.building) {
                    buildingsStr = alarm.building.building_no;
                }
                document.getElementById('updateDisplayBuildings').textContent = buildingsStr;

                document.getElementById('updateManufacturer').value = alarm.manufacturer || '';
                document.getElementById('updateInstallationDateInput').value = alarm.installation_date || '';
                document.getElementById('updateNextTestDue').value = alarm.next_test_due || '';
                document.getElementById('updateNotes').value = alarm.notes || '';

                // Store installation date for validation
                document.getElementById('updateAlarmId').dataset.installationDate = alarm.installation_date || '';

                // Populate status options
                const statusSelect = document.getElementById('updateStatusSelect');
                statusSelect.innerHTML = '<option value="">Select Status</option>';

                let options = [...(statusOptions[alarm.alarm_type] || [])];
                if (!options.includes('Decommissioned')) options.push('Decommissioned');

                options.forEach(status => {
                    const option = document.createElement('option');
                    const statusValue = status.toLowerCase().replace(' ', '_');
                    option.value = statusValue;
                    option.textContent = status;
                    if (alarm.status === statusValue) {
                        option.selected = true;
                    }
                    statusSelect.appendChild(option);
                });

                // Show modal using Vanilla JS for maximum compatibility with BS5
                const modalEl = document.getElementById('alarmDetailsModal');
                const modal = new bootstrap.Modal(modalEl);
                modal.show();

            } catch (error) {
                console.error('Error loading alarm data:', error);
            }
        });

        // Test Now button click
        document.querySelectorAll('.test-now-btn').forEach(button => {
            button.addEventListener('click', function() {
                const alarmId = this.getAttribute('data-alarm-id');
                const alarmCode = this.getAttribute('data-alarm-code');

                Swal.fire({
                    title: 'Test Alarm?',
                    text: `Test alarm ${alarmCode} now? This will update the last test date to today.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Test Now'
                }).then((result) => {
                    if (result.isConfirmed) {
                        testAlarmSystem(alarmId);
                    }
                });
            });
        });

        // Simulate Alarm Button
        document.getElementById('simulateAlarmBtn').addEventListener('click', function() {
            Swal.fire({
                title: 'Simulate Alarm Test?',
                text: 'Are you sure you want to simulate an alarm test? This will trigger test alerts.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Simulate'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Simulating...', 'Alarm test simulation started! Testing all functional/online alarm systems...', 'info');
                }
            });
        });

        // Load buildings for a school
        async function loadBuildings(schoolId) {
            try {
                const response = await fetch(`/fire-safety/buildings/${schoolId}`);
                const buildings = await response.json();

                const select = document.getElementById('buildingSelect');
                const checkboxList = document.getElementById('buildingsCheckboxList');
                
                select.innerHTML = '<option value="">Select Building</option>';
                checkboxList.innerHTML = '';

                buildings.forEach(building => {
                    const buildingDisplayName = `${building.building_no} (${building.building_name || 'No Name'})`;
                    
                    // Dropdown option
                    const option = document.createElement('option');
                    option.value = building.id;
                    option.textContent = buildingDisplayName;
                    option.dataset.floors = building.floors;
                    select.appendChild(option);

                    // Checkbox for multi-building
                    const div = document.createElement('div');
                    div.className = 'form-check mb-2';
                    div.innerHTML = `
                        <input class="form-check-input" type="checkbox" name="building_ids[]" value="${building.id}" id="bldgCheck${building.id}">
                        <label class="form-check-label" for="bldgCheck${building.id}">
                            ${buildingDisplayName}
                        </label>
                    `;
                    checkboxList.appendChild(div);
                });

            } catch (error) {
                console.error('Error loading buildings:', error);
                Swal.fire('Notice', 'Failed to load buildings. Please check if buildings are added.', 'info');
            }
        }
        // Check if alarm code already exists
        async function checkAlarmCode(code) {
           // ... (existing code, not shown here to save tokens if possible, but replace tool requires context)
           // Actually, I should insert the new logic BEFORE checkAlarmCode or AFTER loadBuildings.
           // Since I'm replacing lines 889-889 (StartLine), I'll just insert the new logic there.
        }

        // Handle Building Selection (Populate Floors)
        document.getElementById('buildingSelect').addEventListener('change', function() {
            const select = this;
            const floorSelect = document.getElementById('alarmFloorSelect');
            floorSelect.innerHTML = '<option value="">Select Floor</option><option value="All Floors">All Floors</option>';
            floorSelect.disabled = true;

            const option = select.options[select.selectedIndex];
            if (!option || !option.value) return;

            const floors = parseInt(option.dataset.floors) || 1;
            floorSelect.disabled = false;
            
            // Ordinals helper
            const getOrdinal = (n) => {
                const s = ["th", "st", "nd", "rd"];
                const v = n % 100;
                return n + (s[(v - 20) % 10] || s[v] || s[0]);
            };

            for (let i = 1; i <= floors; i++) {
                const opt = document.createElement('option');
                opt.value = getOrdinal(i) + " Floor";
                opt.textContent = getOrdinal(i) + " Floor";
                floorSelect.appendChild(opt);
            }
        });

        // Check if alarm code already exists
        async function checkAlarmCode(code) {
            if (!code) return;

            try {
                const response = await fetch(`/fire-safety/check-alarm-code/${encodeURIComponent(code)}`);
                const data = await response.json();

                const codeInput = document.getElementById('alarmCode');
                const errorDiv = document.getElementById('codeError');

                if (data.exists) {
                    codeInput.classList.add('is-invalid');
                    errorDiv.textContent = 'Alarm code already exists. Please use a different code.';
                    return false;
                } else {
                    codeInput.classList.remove('is-invalid');
                    return true;
                }
            } catch (error) {
                console.error('Error checking alarm code:', error);
                return true;
            }
        }

        // Date validation
        function validateDates() {
            const installationDate = document.getElementById('installationDate').value;
            const lastTestDate = document.getElementById('lastTestDate').value;
            const nextTestDue = document.getElementById('nextTestDue').value;
            const today = new Date().toISOString().split('T')[0];

            let isValid = true;

            // Check installation date not in future
            if (installationDate && installationDate > today) {
                Swal.fire('Validation Error', 'Installation date cannot be in the future.', 'warning');
                isValid = false;
                return false;
            }

            // Check last test not in future
            if (lastTestDate && lastTestDate > today) {
                Swal.fire('Validation Error', 'Last test date cannot be in the future.', 'warning');
                isValid = false;
                return false;
            }

            // Check last test not before installation
            if (installationDate && lastTestDate && lastTestDate < installationDate) {
                Swal.fire('Validation Error', 'Last test date cannot be before installation date.', 'warning');
                isValid = false;
                return false;
            }

            // Check next test not before installation
            if (installationDate && nextTestDue && nextTestDue < installationDate) {
                Swal.fire('Validation Error', 'Next test due date cannot be before installation date.', 'warning');
                isValid = false;
                return false;
            }

            // Check next test not before last test
            if (lastTestDate && nextTestDue && nextTestDue < lastTestDate) {
                Swal.fire('Validation Error', 'Next test due date cannot be before last test date.', 'warning');
                isValid = false;
                return false;
            }

            return isValid;
        }

        // Save Alarm System
        async function saveAlarmSystem() {
            const form = document.getElementById('addAlarmForm');

            // Validate dates
            if (!validateDates()) {
                return;
            }

            // Validate alarm code
            const code = document.getElementById('alarmCode').value;
            const codeValid = await checkAlarmCode(code);
            if (!codeValid) {
                Swal.fire('Error', 'Please fix the alarm code error.', 'error');
                return;
            }

            // Check if building exists for this school
            const buildingSelect = document.getElementById('buildingSelect');
            if (buildingSelect.options.length <= 1) { // Only "Select Building" option
                Swal.fire('Warning', 'No buildings found for this school. Please add buildings first.', 'warning');
                return;
            }

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Get CSRF token - multiple ways
            let csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!csrfToken) {
                // Try from form
                csrfToken = form.querySelector('input[name="_token"]')?.value;

                if (!csrfToken) {
                    // Try Laravel's default CSRF field
                    csrfToken = document.querySelector('input[name="csrf_token"]')?.value;

                    if (!csrfToken) {
                        console.error('CSRF token not found anywhere');
                        Swal.fire('Error', 'Security token missing. Please refresh the page and try again.', 'error');
                        return;
                    }
                }
            }

            console.log('CSRF Token found:', csrfToken ? 'Yes' : 'No');

            // Combine Location
            const isMulti = document.getElementById('multiBuildingToggle').checked;
            
            if (isMulti) {
                // Check if at least one checkbox is checked
                const checked = document.querySelectorAll('input[name="building_ids[]"]:checked');
                if (checked.length === 0) {
                    Swal.fire('Missing Information', 'Please select at least one building.', 'warning');
                    return;
                }
                document.getElementById('finalLocation').value = "Multiple Buildings - Shared System";
            } else {
                const floor = document.getElementById('alarmFloorSelect').value;
                const specific = document.getElementById('alarmSpecificLocation').value.trim();
                
                if (!buildingSelect.value) {
                    Swal.fire('Missing Information', 'Please select a primary building.', 'warning');
                    return;
                }
                
                if (!floor || !specific) {
                    Swal.fire('Missing Information', 'Please select a floor and enter a specific location.', 'warning');
                    return;
                }
                document.getElementById('finalLocation').value = `${floor} - ${specific}`;
            }

            const formData = new FormData(form);

            // Log what we're sending for debugging
            console.log('Form action:', form.action);
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(formData)
                });

                console.log('Response status:', response.status);

                const data = await response.json();
                console.log('Response data:', data);

                if (data.success) {
                    Swal.fire('Success', 'Alarm system added successfully!', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to add alarm system', 'error');
                    if (data.errors) {
                        console.log('Validation errors:', data.errors);
                    }
                }

            } catch (error) {
                console.error('Error details:', error);
                Swal.fire('Error', 'Failed to add alarm system. Check console (F12) for details.', 'error');
            }
        }

        // Update Alarm System
        async function updateAlarmSystem() {
            const form = document.getElementById('updateAlarmForm');
            const alarmId = document.getElementById('updateAlarmId').value;
            const newCode = document.getElementById('updateAlarmCode').value;
            const oldCode = document.getElementById('originalAlarmCode').value;

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Code update confirmation
            if (newCode !== oldCode) {
                const confirmCode = await Swal.fire({
                    title: 'Update Alarm Code?',
                    text: `Are you sure you want to update the alarm code from "${oldCode}" to "${newCode}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#A8191F',
                    confirmButtonText: 'Yes, update it!',
                    cancelButtonText: 'No, keep original'
                });

                if (!confirmCode.isConfirmed) {
                    document.getElementById('updateAlarmCode').value = oldCode;
                    return;
                }
            }

            // Get dates
            const nextTestDue = document.getElementById('updateNextTestDue').value;
            const installationDate = document.getElementById('updateAlarmId').dataset.installationDate;
            const today = new Date().toISOString().split('T')[0];

            if (installationDate) {
                // Check next test not before installation
                if (nextTestDue && nextTestDue < installationDate) {
                    Swal.fire('Invalid Date', 'Next test due date cannot be before installation date.', 'warning');
                    return;
                }
            }

            const formData = new FormData(form);
            formData.append('_method', 'PUT');
            // Auto-update Last Test to Today
            formData.append('last_test', today);

            // Show loading
            Swal.fire({
                title: 'Updating...',
                text: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const response = await fetch(`/fire-safety/alarm/${alarmId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        title: 'Updated',
                        text: 'Alarm system details updated successfully!',
                        icon: 'success',
                        confirmButtonColor: '#A8191F'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to update alarm system', 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Failed to update alarm system', 'error');
            }
        }

        // Test Alarm System
        async function testAlarmSystem(alarmId) {
            try {
                const response = await fetch(`/fire-safety/alarm/${alarmId}/test`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire('Success', 'Alarm test completed successfully! Last test date updated.', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to test alarm system', 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Failed to test alarm system', 'error');
            }
        }

        // Open Removal Modal
        function removeAlarmSystem() {
            // currentAlarmId is already set by the button onclick
            document.getElementById('alarmRemovalReason').value = '';
            const modalEl = document.getElementById('alarmRemovalModal');
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }

        async function confirmRemoveAlarm() {
            const reason = document.getElementById('alarmRemovalReason').value;
            if (!reason.trim()) {
                Swal.fire('Reason Required', 'Please provide a reason for removal.', 'warning');
                return;
            }

            try {
                const response = await fetch(`/fire-safety/alarm/${currentAlarmId}/remove`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ reason: reason })
                });

                const data = await response.json();

                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('alarmRemovalModal'));
                    if(modal) modal.hide();
                    
                    Swal.fire('Removed', 'Alarm system has been archived successfully!', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to remove alarm system', 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Failed to remove alarm system', 'error');
            }
        }

        async function openAlarmHistoryModal(schoolId) {
            const modalEl = document.getElementById('alarmHistoryModal');
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            const tableBody = document.querySelector('#alarmHistoryTable tbody');
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Loading...</td></tr>';
            modal.show();

            try {
                const resp = await fetch(`/fire-safety/alarm/history/${schoolId}`);
                const data = await resp.json();

                if (data.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No removed alarm systems found.</td></tr>';
                    return;
                }

                tableBody.innerHTML = '';
                data.forEach(item => {
                    const removedAt = new Date(item.removed_at).toLocaleString();
                    const row = `
                        <tr>
                            <td>${removedAt}</td>
                            <td class="fw-bold text-danger">${item.item_code || 'N/A'}</td>
                            <td>${item.item_data.alarm_type || 'N/A'}</td>
                            <td>${item.item_data.building_name || 'N/A'}</td>
                            <td>${item.reason || 'No reason provided'}</td>
                            <td><span class="badge bg-secondary">${item.item_data.status}</span></td>
                        </tr>
                    `;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
            } catch (e) {
                console.error(e);
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Failed to load history.</td></tr>';
            }
        }


    </script>
    <!-- Placeholder removed -->
</body>
</html>
