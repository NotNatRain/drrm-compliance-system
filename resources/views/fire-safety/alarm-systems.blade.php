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
        /* Alarm modals: must sit in body and above backdrop so they are visible and clickable */
        #addAlarmModal, #updateAlarmModal, #alarmRemovalModal, #alarmHistoryModal {
            z-index: 1065 !important;
        }
        #addAlarmModal .modal-dialog, #updateAlarmModal .modal-dialog,
        #alarmRemovalModal .modal-dialog, #alarmHistoryModal .modal-dialog {
            z-index: 1066;
        }
        body.modal-open .modal-backdrop { z-index: 1060 !important; }
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
        .status-functional, .status-active, .status-online { color: #28a745; font-weight: bold; } /* Green */
        .status-missing, .status-broken, .status-maintenance, .status-decommissioned, .status-system-error { color: #dc3545; font-weight: bold; } /* Red */
        .status-offline, .status-not-yet-installed, .status-issues, .status-jammed, .status-under-repair { color: #ffc107; font-weight: bold; } /* Yellow */
        .status-not-installed { color: #6c757d; font-weight: bold; } /* Gray */

        .test-overdue {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
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
        </div>
    </div>

    <!-- Alarm Details & Update Modal -->
    <div class="modal fade" id="updateAlarmModal" tabindex="-1" aria-hidden="true">
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
                                    <button class="btn btn-primary btn-sm add-alarm-btn ms-2"
                                            data-school-id="{{ $school->id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#addAlarmModal">
                                        <i class="fas fa-plus me-2"></i> Add New Alarm
                                    </button>
                                    <button class="btn btn-sm ms-2"
                                            style="background-color: #e9ecef; color: #495057; border: 1px solid #ced4da;"
                                            onclick="openAlarmHistoryModal({{ $school->id }})">
                                        <i class="fas fa-history me-1"></i> Removed Alarm System
                                    </button>
                                    <a href="{{ route('fire-safety.report.alarm-details', $school->id) }}" target="_blank"
                                            class="btn btn-sm ms-2"
                                            style="background-color: #e9ecef; color: #495057; border: 1px solid #ced4da;">
                                        <i class="fas fa-print me-1"></i> Print Alarm Details
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Building</th>
                                                <th>Code</th>
                                                <th>Type</th>
                                                <th>Status</th>
                                                <th>AS OF</th>
                                                <th>Next Test Due</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                // Get all buildings with their alarms
                                                $buildingAlarms = [];
                                                foreach($school->buildings as $building) {
                                                    $alarms = $school->alarmSystems->filter(function($alarm) use ($building) {
                                                        return $alarm->buildings->contains('id', $building->id);
                                                    });
                                                    
                                                    if ($alarms->count() > 0) {
                                                        foreach($alarms as $alarm) {
                                                            $buildingAlarms[] = [
                                                                'building' => $building,
                                                                'alarm' => $alarm,
                                                                'has_alarm' => true
                                                            ];
                                                        }
                                                    } else {
                                                        $buildingAlarms[] = [
                                                            'building' => $building,
                                                            'alarm' => null,
                                                            'has_alarm' => false
                                                        ];
                                                    }
                                                }
                                            @endphp
                                            
                                            @forelse($buildingAlarms as $item)
                                                <tr class="{{ !$item['has_alarm'] ? 'table-warning' : '' }}">
                                                    <td>
                                                        <span class="badge bg-secondary">{{ $item['building']->building_no }}</span>
                                                        <small class="text-muted d-block">{{ $item['building']->building_name }}</small>
                                                    </td>
                                                    <td>
                                                        @if($item['has_alarm'])
                                                            {{ $item['alarm']->code }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($item['has_alarm'])
                                                            {{ $item['alarm']->alarm_type }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($item['has_alarm'])
                                                            @php
                                                                $statusClass = 'status-' . str_replace([' ', '_'], '-', strtolower($item['alarm']->status));
                                                                $displayStatus = ucwords(str_replace('_', ' ', $item['alarm']->status));
                                                            @endphp
                                                            <span class="alarm-status {{ $statusClass }}">
                                                                <i class="fas fa-circle"></i> {{ $displayStatus }}
                                                            </span>
                                                        @else
                                                            <span class="badge bg-warning text-dark">No Alarm Yet</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($item['has_alarm'])
                                                            {{ $item['alarm']->last_test ? \Carbon\Carbon::parse($item['alarm']->last_test)->format('Y-m-d') : 'Never' }}
                                                        @else
                                                            {{ \Carbon\Carbon::today()->format('Y-m-d') }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($item['has_alarm'])
                                                            {{ \Carbon\Carbon::parse($item['alarm']->next_test_due)->format('Y-m-d') }}
                                                            @if($item['alarm']->next_test_due < now())
                                                                <span class="badge bg-danger ms-2">Overdue</span>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($item['has_alarm'])
                                                            <div class="btn-group">
                                                                <button class="btn btn-sm btn-outline-primary test-now-btn"
                                                                        data-alarm-id="{{ $item['alarm']->id }}"
                                                                        data-alarm-code="{{ $item['alarm']->code }}">
                                                                    <i class="fas fa-play"></i> Test Now
                                                                </button>
                                                                <button class="btn btn-sm btn-outline-info update-alarm-btn"
                                                                        data-alarm-id="{{ $item['alarm']->id }}">
                                                                    <i class="fas fa-edit"></i> Details
                                                                </button>
                                                                @if(Auth::user()->role === 'admin')
                                                                <button class="btn btn-sm btn-outline-danger remove-alarm-btn-table"
                                                                        onclick="currentAlarmId = '{{ $item['alarm']->id }}'; removeAlarmSystem();">
                                                                    <i class="fas fa-trash"></i> Remove
                                                                </button>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <button class="btn btn-sm btn-primary add-alarm-btn"
                                                                    data-school-id="{{ $school->id }}"
                                                                    data-building-id="{{ $item['building']->id }}"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#addAlarmModal">
                                                                <i class="fas fa-plus"></i> Add Alarm
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4">
                                                        <div class="text-muted mb-2">No buildings found for this school.</div>
                                                        <a href="{{ route('fire-safety.buildings') }}" class="btn btn-sm btn-primary">
                                                            Manage Buildings
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforelse
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

    <!-- Add Alarm System Modal (moved to body by JS so it appears above backdrop) -->
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
                    <form id="addAlarmForm">
                        @csrf
                        <input type="hidden" name="school_id" id="modalSchoolId">
                        
                        <!-- Building Selection Logic -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="coversMultiple" name="covers_multiple">
                                    <label class="form-check-label fw-bold" for="coversMultiple">
                                        Yes, it covers multiple buildings
                                    </label>
                                </div>
                                <label class="form-label fw-bold">Building(s) *</label>
                                <select class="form-control" name="building_ids[]" id="addBuildingSelect" required>
                                    <!-- Populated via JS -->
                                </select>
                                <small class="text-muted" id="multiSelectHelp" style="display:none;">Hold Ctrl/Cmd to select multiple buildings</small>
                            </div>
                        </div>

                        <!-- Floor Selection - Only for Single Building -->
                        <div id="floorsContainer" style="display:none;" class="mb-3 p-3 bg-light rounded border">
                            <label class="form-label fw-bold mb-2">Select Floor Location *</label>
                            <select class="form-control" name="floor_id" id="addFloorSelect">
                                <option value="">Select Building First</option>
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Alarm Code *</label>
                                <input type="text" class="form-control" name="code" id="alarmCode" placeholder="e.g. ALARM-001" required onblur="checkAlarmCode(this.value)">
                                <div class="invalid-feedback" id="codeError">Alarm code already exists</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Alarm Type *</label>
                                <select class="form-control" name="alarm_type" id="addAlarmType" required>
                                    <option value="" disabled selected>Select Type</option>
                                    @foreach($alarmTypes as $type)
                                        <option value="{{ $type->name }}" data-type-id="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Status *</label>
                                <select class="form-control" name="status" id="addStatusSelect" required>
                                    <option value="functional">Functional (Active)</option>
                                    @foreach($alarmStatusesByType as $parentId => $statuses)
                                        <optgroup label="{{ \App\Models\SystemConfiguration::find($parentId)->name }}" data-parent-id="{{ $parentId }}">
                                            @foreach($statuses as $status)
                                                <option value="{{ $status->name }}">{{ $status->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Location Details *</label>
                                <input type="text" class="form-control" name="location" id="alarmSpecificLocation" placeholder="e.g. Main Lobby, Hallway" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Manufacturer</label>
                                <input type="text" class="form-control" name="manufacturer">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Installation Date</label>
                                <input type="date" class="form-control" name="installation_date" id="installationDate">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Last Test Date</label>
                                <input type="date" class="form-control" name="last_test" id="lastTestDate" max="{{ date('Y-m-d') }}">
                                <div class="form-text">Cannot be in the future.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Next Test Due *</label>
                                <input type="date" class="form-control" name="next_test_due" id="nextTestDue" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Notes/Remarks</label>
                            <textarea class="form-control" name="notes" rows="2" placeholder="Additional information..."></textarea>
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
    <div class="modal fade" id="alarmRemovalModal" tabindex="-1" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered">
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
    <div class="modal fade" id="alarmHistoryModal" tabindex="-1" aria-modal="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
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
        // Move alarm modals to body so they render above Bootstrap's backdrop and stay interactive
        (function moveAlarmModalsToBody() {
            var ids = ['addAlarmModal', 'updateAlarmModal', 'alarmRemovalModal', 'alarmHistoryModal'];
            function run() {
                ids.forEach(function(id) {
                    var el = document.getElementById(id);
                    if (el && el.parentNode !== document.body) {
                        document.body.appendChild(el);
                    }
                });
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', run);
            } else {
                run();
            }
        })();
                // Multi-Building Selection Logic
        document.addEventListener('DOMContentLoaded', function() {
            const addModalEl = document.getElementById('addAlarmModal');
            if (addModalEl) {
                const coversMultipleCheckbox = document.getElementById('coversMultiple');
                const buildingSelect = document.getElementById('addBuildingSelect');
                const multiSelectHelp = document.getElementById('multiSelectHelp');
                const floorsContainer = document.getElementById('floorsContainer');
                const floorSelect = document.getElementById('addFloorSelect');
                const typeSelect = document.getElementById('addAlarmType');
                const statusSelect = document.getElementById('addStatusSelect');
                
                // Map Alarm Type Name -> ID for filtering
                const alarmTypeIds = {};
                @foreach($alarmTypes as $type)
                    alarmTypeIds['{{ $type->name }}'] = {{ $type->id }};
                @endforeach

                // Filter status options based on alarm type
                function filterStatusOptions(selectElement, typeName) {
                    const typeId = alarmTypeIds[typeName];
                    const optgroups = selectElement.querySelectorAll('optgroup');
                    
                    optgroups.forEach(group => {
                        const parentId = group.getAttribute('data-parent-id');
                        if (parentId == typeId) {
                            group.style.display = '';
                            group.disabled = false;
                        } else {
                            group.style.display = 'none';
                            group.disabled = true;
                        }
                    });
                    
                    // Reset value if currently selected option is now hidden
                    const selectedOpt = selectElement.options[selectElement.selectedIndex];
                    if (selectedOpt && selectedOpt.parentElement.tagName === 'OPTGROUP' && selectedOpt.parentElement.disabled) {
                        selectElement.value = 'functional';
                    }
                }

                // Toggle Multiple/Single Building
                if (coversMultipleCheckbox) {
                    coversMultipleCheckbox.addEventListener('change', function() {
                        if (this.checked) {
                            buildingSelect.setAttribute('multiple', 'multiple');
                            buildingSelect.size = 4;
                            multiSelectHelp.style.display = 'block';
                            floorsContainer.style.display = 'none';
                            floorSelect.required = false;
                            floorSelect.value = "";
                            document.getElementById('alarmSpecificLocation').value = "Multiple Buildings - Shared System";
                        } else {
                            buildingSelect.removeAttribute('multiple');
                            buildingSelect.removeAttribute('size');
                            // If multiple selected, keep only first
                            if (buildingSelect.selectedOptions.length > 1) {
                                for (let i = 0; i < buildingSelect.options.length; i++) {
                                    buildingSelect.options[i].selected = (i === buildingSelect.selectedOptions[0].index);
                                }
                            }
                            multiSelectHelp.style.display = 'none';
                            handleBuildingChange();
                        }
                    });
                }

                // Handle Building Selection Change
                if (buildingSelect) {
                    buildingSelect.addEventListener('change', handleBuildingChange);
                }

                // Handle Alarm Type Change
                if (typeSelect) {
                    typeSelect.addEventListener('change', function() {
                        filterStatusOptions(statusSelect, this.value);
                    });
                }

                function handleBuildingChange() {
                    const isMultiple = coversMultipleCheckbox ? coversMultipleCheckbox.checked : false;
                    
                    if (!isMultiple && buildingSelect.value) {
                        const buildingId = buildingSelect.value;
                        floorsContainer.style.display = 'block';
                        floorSelect.required = true;
                        floorSelect.innerHTML = '<option value="">Loading...</option>';

                        fetch(`/fire-safety/building/${buildingId}`)
                            .then(r => r.json())
                            .then(data => {
                                floorSelect.innerHTML = '<option value="">Select Floor</option><option value="All Floors">All Floors</option>';
                                if (data.floors) {
                                    // Helper for ordinal floors
                                    const getOrdinal = (n) => {
                                        const s = ["th", "st", "nd", "rd"];
                                        const v = n % 100;
                                        return n + (s[(v - 20) % 10] || s[v] || s[0]);
                                    };
                                    
                                    for(let i = 1; i <= data.floors; i++) {
                                        const opt = document.createElement('option');
                                        opt.value = getOrdinal(i) + " Floor";
                                        opt.textContent = getOrdinal(i) + " Floor";
                                        floorSelect.appendChild(opt);
                                    }
                                }
                            })
                            .catch(e => {
                                console.error(e);
                                floorSelect.innerHTML = '<option value="">Error loading floors</option>';
                            });
                    } else {
                        floorsContainer.style.display = 'none';
                        floorSelect.required = false;
                    }
                }

                // Modal Show Event
                addModalEl.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const schoolId = button.getAttribute('data-school-id');
                    const buildingId = button.getAttribute('data-building-id');
                    
                    document.getElementById('modalSchoolId').value = schoolId;
                    
                    // Reset form
                    document.getElementById('addAlarmForm').reset();
                    if(coversMultipleCheckbox) {
                        coversMultipleCheckbox.checked = false;
                        coversMultipleCheckbox.dispatchEvent(new Event('change'));
                    }
                    
                    // Trigger type change to filter statuses initially
                    if(typeSelect) typeSelect.dispatchEvent(new Event('change'));

                    // Fetch buildings for this school
                    fetch(`/fire-safety/buildings/${schoolId}`)
                        .then(r => r.json())
                        .then(buildings => {
                            buildingSelect.innerHTML = '<option value="">Select Building</option>';
                            buildings.forEach(b => {
                                const option = document.createElement('option');
                                option.value = b.id;
                                option.text = `${b.building_no} - ${b.building_name}`;
                                option.dataset.floors = b.floors;
                                if (buildingId && b.id == buildingId) {
                                    option.selected = true;
                                }
                                buildingSelect.appendChild(option);
                            });
                            // Trigger change to setup floors
                            handleBuildingChange();
                        });
                });
            }
        });

        // Status options based on alarm type (Dynamic from DB)
        const statusOptions = {
            @foreach($alarmTypes as $type)
                '{{ $type->name }}': [
                    @php $typeStatuses = $alarmStatusesByType->get($type->id, collect()); @endphp
                    @foreach($typeStatuses as $s)
                        '{{ $s->name }}',
                    @endforeach
                ],
            @endforeach
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
                const modalEl = document.getElementById('updateAlarmModal');
                const modal = new bootstrap.Modal(modalEl); // Use direct constructor or getOrCreateInstance if safer
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
            if (!code || !currentSchoolId) return;

            try {
                const response = await fetch(`/fire-safety/check-alarm-code/${currentSchoolId}/${encodeURIComponent(code)}`);
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

            // Check building selection
            const buildingSelect = document.getElementById('addBuildingSelect');
            const isMultiple = document.getElementById('coversMultiple').checked;
            
            if (isMultiple) {
                // Check if at least one building is selected for multi-building
                const selectedBuildings = Array.from(buildingSelect.selectedOptions).map(opt => opt.value);
                if (selectedBuildings.length === 0 || (selectedBuildings.length === 1 && selectedBuildings[0] === '')) {
                    Swal.fire('Missing Information', 'Please select at least one building.', 'warning');
                    return;
                }
                // For multi-building, set location as "Multiple Buildings"
                document.getElementById('alarmSpecificLocation').value = "Multiple Buildings - Shared System";
            } else {
                // Single building validation
                if (!buildingSelect.value) {
                    Swal.fire('Missing Information', 'Please select a building.', 'warning');
                    return;
                }
                
                // Check floor selection for single building
                const floor = document.getElementById('addFloorSelect').value;
                const specific = document.getElementById('alarmSpecificLocation').value.trim();
                
                if (!floor) {
                    Swal.fire('Missing Information', 'Please select a floor.', 'warning');
                    return;
                }
                
                if (!specific) {
                    Swal.fire('Missing Information', 'Please enter a specific location.', 'warning');
                    return;
                }
            }

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Get CSRF token
            let csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!csrfToken) {
                csrfToken = form.querySelector('input[name="_token"]')?.value;
                if (!csrfToken) {
                    csrfToken = document.querySelector('input[name="csrf_token"]')?.value;
                    if (!csrfToken) {
                        console.error('CSRF token not found anywhere');
                        Swal.fire('Error', 'Security token missing. Please refresh the page and try again.', 'error');
                        return;
                    }
                }
            }

            console.log('CSRF Token found:', csrfToken ? 'Yes' : 'No');

            // Combine Location for single building
            if (!isMultiple) {
                const floor = document.getElementById('addFloorSelect').value;
                const specific = document.getElementById('alarmSpecificLocation').value.trim();
                document.getElementById('alarmSpecificLocation').value = `${floor} - ${specific}`;
            }

            const formData = new FormData(form);

            // Log what we're sending for debugging
            console.log('Form action:', '{{ route("fire-safety.alarm.store") }}');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }

            try {
                const response = await fetch('{{ route("fire-safety.alarm.store") }}', {
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
                    // Hide modal first so user can focus on message
                    const modalEl = document.getElementById('addAlarmModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();

                    Swal.fire('Success', 'Alarm system added successfully!', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    // Hide modal on error too so message is visible
                    const modalEl = document.getElementById('addAlarmModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();

                    let errorMessage = data.message || 'Failed to add alarm system';
                    if (data.errors) {
                        const errorList = Object.values(data.errors).flat().join('\n');
                        errorMessage += '\n' + errorList;
                    }
                    Swal.fire('Error', errorMessage, 'error');
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
                    // Hide modal first so user can focus on message
                    const modalEl = document.getElementById('updateAlarmModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();

                    Swal.fire({
                        title: 'Updated',
                        text: 'Alarm system details updated successfully!',
                        icon: 'success',
                        confirmButtonColor: '#A8191F'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    // Hide modal on error too so message is visible
                    const modalEl = document.getElementById('updateAlarmModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();

                    let errorMessage = data.message || 'Failed to update alarm system';
                    if (data.errors) {
                        const errorList = Object.values(data.errors).flat().join('\n');
                        errorMessage += '\n' + errorList;
                    }
                    Swal.fire('Error', errorMessage, 'error');
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
            // currentAlarmId is already set by the button onclick which is set in update-alarm-btn click handler or table button
            // If called from the Update Modal, we need to ensure currentAlarmId works
            if (!window.currentAlarmId) {
                 window.currentAlarmId = document.getElementById('updateAlarmId').value;
            }

            // Close update modal if open
            const updateModal = bootstrap.Modal.getInstance(document.getElementById('updateAlarmModal'));
            if (updateModal) {
                updateModal.hide();
            }

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

                const data = await response.json().catch(() => ({}));

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
                Swal.fire('Error', 'Failed to remove alarm system. Check console for details.', 'error');
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
