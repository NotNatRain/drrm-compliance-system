<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buildings - Fire Safety</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --fire-red: #A8191F;
            --fire-dark-red: #8A1217;
        }

        /* SweetAlert2 Custom Styling */
        .swal2-popup {
            border-radius: 15px !important;
        }
        .swal2-styled.swal2-confirm {
            background-color: var(--fire-red) !important;
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

        .building-card {
            transition: transform 0.2s;
            height: 100%;
        }

        .building-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }

        .compliance-meter {
            height: 10px;
            border-radius: 5px;
            background-color: #e9ecef;
            overflow: hidden;
            margin-top: 10px;
        }

        .compliance-fill {
            height: 100%;
            transition: width 0.5s;
        }

        .school-tabs {
            border-bottom: 2px solid #dee2e6;
        }

        .school-tab-btn {
            color: #495057;
            background-color: transparent;
            border: 1px solid transparent;
            border-top-left-radius: 0.25rem;
            border-top-right-radius: 0.25rem;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s;
            position: relative;
            margin-bottom: -2px;
        }

        .school-tab-btn:hover {
            color: white;
            background-color: #8A1217;
            border-color: #8A1217 #8A1217 #dee2e6;
        }

        .school-tab-btn.active {
            color: white !important;
            background-color: #8A1217 !important;
            border-color: #8A1217 #8A1217 #8A1217 !important;
            position: relative;
            z-index: 1;
        }

        .school-tab-btn:not(.active):not(:hover) {
            color: #495057;
            background-color: #f8f9fa;
            border-color: #dee2e6 #dee2e6 #dee2e6;
        }

        .school-tab-btn:focus {
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(168, 25, 31, 0.25);
        }

        .no-buildings {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }

        .no-buildings i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #adb5bd;
        }

        .status-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .status-compliant { background-color: #28a745; }
        .status-needs-attention { background-color: #ffc107; }
        .status-non-compliant { background-color: #dc3545; }

        .loading {
            opacity: 0.7;
            pointer-events: none;
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
                    <h4 class="text-white mb-0">Building Management</h4>
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
                    <a class="nav-link active" href="{{ route('fire-safety.buildings') }}">
                        <span class="nav-icon"><i class="fas fa-building"></i></span>
                        <span>Buildings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('fire-safety.alarm-systems') }}">
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

            <!-- Quick Stats -->
            <div class="mt-4">
                <h6 class="text-white mb-3">Building Safety Overview</h6>
                <div id="sidebarStats">
                    <!-- Stats will be loaded via JavaScript -->
                    <div class="text-center text-white py-3">
                        <i class="fas fa-spinner fa-spin"></i> Loading stats...
                    </div>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addBuildingModal">
                        <i class="fas fa-plus me-2"></i> Add Building
                    </button>
                    <button class="btn btn-light btn-sm" onclick="generateBuildingReport()">
                        <i class="fas fa-file-pdf me-2"></i> Safety Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @if($schools->isEmpty())
        <!-- No Schools Found Message -->
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
        <!-- School Tabs -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-body p-0">
                        <div class="school-tabs">
                            <nav>
                                <div class="nav nav-tabs border-0" id="schoolTab" role="tablist">
                                    @foreach($schools as $school)
                                    <button class="nav-link school-tab-btn {{ $loop->first ? 'active' : '' }}"
                                            id="school-tab-{{ $school->id }}"
                                            data-bs-toggle="tab"
                                            data-bs-target="#school-{{ $school->id }}"
                                            type="button"
                                            role="tab"
                                            aria-controls="school-{{ $school->id }}"
                                            aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                            data-school-id="{{ $school->id }}">
                                        {{ $school->school_name }}
                                    </button>
                                    @endforeach
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="schoolTabContent">
            @foreach($schools as $school)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="school-{{ $school->id }}">
                <!-- Building Summary -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card dashboard-card border-left-success h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                            Total Buildings
                                        </div>
                                        <div class="h2 mb-0 fw-bold text-gray-800">
                                            {{ $school->buildings->count() }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-building fa-2x text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card dashboard-card border-left-primary h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                            Total Floors
                                        </div>
                                        <div class="h2 mb-0 fw-bold text-gray-800">
                                            {{ $school->buildings->sum('floors') }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-layer-group fa-2x text-primary"></i>
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
                                            Total Rooms
                                        </div>
                                        <div class="h2 mb-0 fw-bold text-gray-800">
                                            {{ $school->buildings->sum('rooms') }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-door-closed fa-2x text-warning"></i>
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
                                            Minimum Fire Extinguishers
                                        </div>
                                        <div class="h2 mb-0 fw-bold text-gray-800">
                                            {{ $school->buildings->sum('required_extinguishers_count') }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-fire-extinguisher fa-2x text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buildings Grid -->
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card dashboard-card">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-building me-2"></i> Buildings - {{ $school->school_name }}
                                </h6>
                                <div>
                                    @if(auth()->user()->role === 'admin')
                                    <button class="btn btn-sm me-2" 
                                            style="background-color: #e9ecef; color: #495057; border: 1px solid #ced4da;"
                                            onclick="openBuildingHistoryModal({{ $school->id }})">
                                        <i class="fas fa-history me-1"></i> Removed Floor/Room
                                    </button>
                                    <button class="btn btn-primary btn-sm me-2 add-building-btn"
                                            data-school-id="{{ $school->id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#addBuildingModal">
                                        <i class="fas fa-plus me-2"></i> Add Building
                                    </button>
                                    <button class="btn btn-success btn-sm schedule-inspection-btn"
                                            data-school-id="{{ $school->id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#scheduleInspectionModal">
                                        <i class="fas fa-calendar-plus me-2"></i> Schedule Inspection
                                    </button>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                @if($school->buildings->count() > 0)
                                <div class="row">
                                    @foreach($school->buildings as $building)
                                    @php
                                        $compliance = \App\Http\Controllers\FireSafetyController::calculateBuildingCompliance($building);
                                        $statusClass = $compliance >= 80 ? 'border-success' : ($compliance >= 60 ? 'border-warning' : 'border-danger');
                                        $statusBadge = $compliance >= 80 ? 'bg-success' : ($compliance >= 60 ? 'bg-warning' : 'bg-danger');
                                        $statusText = $compliance >= 80 ? 'Compliant' : ($compliance >= 60 ? 'Needs Attention' : 'Non-Compliant');
                                    @endphp
                                    <div class="col-xl-4 col-lg-6 mb-4">
                                        <div class="card building-card {{ $statusClass }}">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div>
                                                        <h5 class="card-title mb-1">{{ $building->building_no }}</h5>
                                                        <p class="text-muted mb-0">
                                                            <i class="fas fa-map-marker-alt me-1"></i> {{ $school->school_name }}
                                                        </p>
                                                    </div>
                                                    <span class="badge {{ $statusBadge }}">{{ $statusText }}</span>
                                                </div>

                                                <div class="building-stats mb-3">
                                                    <div class="d-flex justify-content-between">
                                                        <span>Floors: <strong>{{ $building->floors }}</strong></span>
                                                        <span>Rooms: <strong>{{ $building->rooms }}</strong></span>
                                                        <span>Minimum Fire Extinguishers: <strong>{{ $building->required_extinguishers_count }}</strong></span>
                                                    </div>
                                                </div>

                                                <!-- Equipment Summary -->
                                                <div class="mb-3 p-3 bg-light rounded">
                                                    <small class="d-block mb-2">
                                                        @php
                                                            $alarmCount = $building->alarmSystems->count();
                                                            $extinguisherCount = $building->fireExtinguishers->count();
                                                        @endphp
                                                        <i class="fas fa-bell text-info me-1"></i> Alarms: <strong>{{ $alarmCount }}</strong>
                                                    </small>
                                                    <small class="d-block mb-2">
                                                        <i class="fas fa-fire-extinguisher text-danger me-1"></i> Extinguishers: <strong>{{ $extinguisherCount }}</strong>
                                                    </small>
                                                    <small class="d-block">
                                                        <i class="fas fa-door-open text-warning me-1"></i> Exits: <strong>{{ $building->emergency_exits ?? 0 }}</strong>
                                                    </small>
                                                </div>

                                                <div class="mt-3 d-grid gap-2">
                                                    <button class="btn btn-sm btn-outline-primary view-building-btn"
                                                            data-building-id="{{ $building->id }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#viewBuildingModal">
                                                        <i class="fas fa-eye me-2"></i> View Details / Update
                                                    </button>
                                                    @if(auth()->user()->role === 'admin')
                                                    <button class="btn btn-sm btn-outline-success inspect-building-btn"
                                                            data-building-id="{{ $building->id }}"
                                                            data-building-name="{{ $building->building_no }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#scheduleInspectionModal">
                                                        <i class="fas fa-clipboard-check me-2"></i> Inspect Now
                                                    </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="no-buildings">
                                    <i class="fas fa-building"></i>
                                    <h4>No Buildings Found</h4>
                                    <p class="text-muted">This school doesn't have any buildings yet. Add your first building to get started.</p>
                                    @if(auth()->user()->role === 'admin')
                                    <button class="btn btn-primary add-building-btn"
                                            data-school-id="{{ $school->id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#addBuildingModal">
                                        <i class="fas fa-plus me-2"></i> Add First Building
                                    </button>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Building Inspection Schedule -->
                <div class="row mt-4">
                    <div class="col-lg-8">
                        <div class="card dashboard-card">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-calendar-alt me-2"></i> Upcoming Inspections - {{ $school->school_name }}
                                </h6>
                                <button class="btn btn-sm btn-outline-primary"
                                        onclick="loadAllInspections({{ $school->id }})">
                                    <i class="fas fa-sync-alt me-1"></i> Refresh
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="inspectionsList-{{ $school->id }}">
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-spinner fa-spin me-2"></i>
                                        Loading inspections...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card dashboard-card">
                            <div class="card-header py-3 bg-primary text-white">
                                <h6 class="m-0 fw-bold">
                                    <i class="fas fa-chart-pie me-2"></i> Compliance Statistics - {{ $school->school_name }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div id="complianceStats-{{ $school->id }}">
                                    <div class="text-center py-4">
                                        <i class="fas fa-spinner fa-spin"></i> Loading statistics...
                                    </div>
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

    <!-- Add Building Modal -->
    <div class="modal fade" id="addBuildingModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i> Add New Building
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addBuildingForm" action="{{ route('fire-safety.building.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="school_id" id="buildingSchoolId">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Building Number/Code</label>
                                <input type="text" class="form-control" name="building_no" id="building_no" placeholder="e.g., BLDG-001, Main Building" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Building Name</label>
                                <input type="text" class="form-control" name="building_name" id="building_name" placeholder="e.g., Science Building, Gymnasium" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Year Constructed</label>
                                <input type="number" class="form-control" name="year_constructed" min="1900" max="{{ date('Y') }}" placeholder="e.g., 1990">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Renovation</label>
                                <input type="number" class="form-control" name="last_renovation" min="1900" max="{{ date('Y') }}" placeholder="e.g., 2020">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Number of Emergency Exits</label>
                                <input type="number" class="form-control" name="emergency_exits" min="0" value="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Building Type</label>
                                <select class="form-control" name="building_type" id="building_type_select" required>
                                    <option value="">Select Type</option>
                                    <option value="classroom">Classroom Building</option>
                                    <option value="administrative">Administrative Building</option>
                                    <option value="library">Library</option>
                                    <option value="laboratory">Laboratory</option>
                                    <option value="gymnasium">Gymnasium (1 Floor/1 Room)</option>
                                    <option value="cafeteria">Cafeteria (1 Floor/1 Room)</option>
                                </select>
                            </div>
                        </div>

                        <div id="roomFloorInputs">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Number of Floors</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="floors" id="buildingFloorsInput" min="1" max="50" value="1" readonly required>
                                        <button class="btn btn-outline-secondary" type="button" id="btnIncFloors" onclick="incrementValue('buildingFloorsInput')"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Total Rooms</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="rooms" id="buildingRoomsInput" min="1" value="1" readonly required>
                                        <button class="btn btn-outline-secondary" type="button" id="btnIncRooms" onclick="incrementValue('buildingRoomsInput')"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Min. Required Fire Extinguishers</label>
                            <input type="number" class="form-control" name="required_extinguishers" id="buildingReqExt" min="0" value="0">
                        </div>

                        <!-- Manage Floors & Rooms (Edit Mode Only) -->
                        <div id="manageFloorsRoomsSection" style="display: none;" class="mb-3 border rounded p-3 bg-light">
                            <h6 class="fw-bold mb-3"><i class="fas fa-tasks me-2"></i>Manage Reduction (Optional)</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-danger fw-bold">Remove Floor</label>
                                    <select class="form-control border-danger" id="removeFloorSelect" onchange="toggleFloorRemovalReason()">
                                        <option value="">-- No Floor to Remove --</option>
                                    </select>
                                    <small class="text-muted d-block mb-2">Removing a floor deletes its alarms, rooms, and extinguishers.</small>
                                    <div id="floorRemovalReasonSection" style="display: none;">
                                        <label class="form-label text-danger small fw-bold">Reason to be removed (Floor)</label>
                                        <textarea class="form-control border-danger mb-2" id="floorRemovalReason" rows="2" placeholder="State reason for removing this floor..."></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-danger fw-bold">Remove Room</label>
                                    <select class="form-control border-danger" id="removeRoomSelect" onchange="toggleRoomRemovalReason()">
                                        <option value="">-- No Room to Remove --</option>
                                    </select>
                                    <small class="text-muted d-block mb-2">Removing a room re-assigns or removes extinguishers.</small>
                                    <div id="roomRemovalReasonSection" style="display: none;">
                                        <label class="form-label text-danger small fw-bold">Reason to be removed (Room)</label>
                                        <textarea class="form-control border-danger" id="roomRemovalReason" rows="2" placeholder="State reason for removing this room..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Building Description</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Describe the building features, location, etc..."></textarea>
                        </div>

                     <div class="mb-3">
                        <label class="form-label">Safety Features Installed <small class="text-muted">(Optional - Select all that apply)</small></label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="sprinklers" id="sprinklers">
                                    <label class="form-check-label" for="sprinklers">Sprinkler System</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="emergency_lights" id="emergencyLights">
                                    <label class="form-check-label" for="emergencyLights">Emergency Lighting</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="exit_signs" id="exitSigns">
                                    <label class="form-check-label" for="exitSigns">Exit Signs</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="fire_doors" id="fireDoors">
                                    <label class="form-check-label" for="fireDoors">Fire Doors</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="two_stairways" id="twoStairways">
                                    <label class="form-check-label" for="twoStairways">Two Stairways</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="saveBuilding()">
                        <i class="fas fa-save me-2"></i> Save Building
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Inspection Modal -->
    <div class="modal fade" id="scheduleInspectionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-calendar-plus me-2"></i> Schedule Inspection
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="scheduleInspectionForm" action="{{ route('fire-safety.inspection.schedule') }}" method="POST">
                        @csrf
                        <input type="hidden" name="school_id" id="inspectionSchoolId">

                        <div class="mb-3">
                            <label class="form-label">Inspection Date *</label>
                            <input type="date" class="form-control" name="inspection_date" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Building *</label>
                            <select class="form-control" name="building_id" id="buildingSelect" required>
                                <option value="">Select Building</option>
                                <!-- Buildings will be populated by JavaScript -->
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Inspection Type *</label>
                            <select class="form-control" name="inspection_type" required>
                                <option value="">Select Type</option>
                                <option value="routine">Routine Safety Audit</option>
                                <option value="quarterly">Quarterly Inspection</option>
                                <option value="annual">Annual Comprehensive</option>
                                <option value="fire_drill">Fire Drill</option>
                                <option value="emergency">Emergency Inspection</option>
                                <option value="preventive">Preventive Maintenance</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Inspector *</label>
                            <input type="text" class="form-control" name="inspector" value="{{ Auth::user()->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes/Remarks</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Additional instructions or notes..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="saveInspection()">
                        <i class="fas fa-calendar-check me-2"></i> Schedule
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Building Modal -->
    <div class="modal fade" id="viewBuildingModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle me-2"></i> Building Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="buildingDetailsContent">
                        <!-- Building details will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer" id="buildingModalFooter">
                    <!-- Footer buttons will be injected by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Floor and Room History Modal -->
    <div class="modal fade" id="buildingHistoryModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #6c757d; color: white;">
                    <h5 class="modal-title"><i class="fas fa-history me-2"></i>Floor and Room's History</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm" id="buildingHistoryTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Date Removed</th>
                                    <th>Type</th>
                                    <th>Building</th>
                                    <th>Identifer</th>
                                    <th>Reason to be removed</th>
                                    <th>Involved Items (Archives)</th>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Global variables
        const USER_ROLE = "{{ auth()->user()->role }}";
        let currentSchoolId = null;

        function toggleFloorRemovalReason() {
            const select = document.getElementById('removeFloorSelect');
            const section = document.getElementById('floorRemovalReasonSection');
            if (select && select.value) {
                section.style.display = 'block';
            } else if (section) {
                section.style.display = 'none';
                const reasonInput = document.getElementById('floorRemovalReason');
                if(reasonInput) reasonInput.value = '';
            }
        }

        function toggleRoomRemovalReason() {
            const select = document.getElementById('removeRoomSelect');
            const section = document.getElementById('roomRemovalReasonSection');
            if (select && select.value) {
                section.style.display = 'block';
            } else if (section) {
                section.style.display = 'none';
                const reasonInput = document.getElementById('roomRemovalReason');
                if(reasonInput) reasonInput.value = '';
            }
        }

        async function openBuildingHistoryModal(schoolId) {
            const modalEl = document.getElementById('buildingHistoryModal');
            if(!modalEl) return;
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            const tableBody = document.querySelector('#buildingHistoryTable tbody');
            if(!tableBody) return;
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Loading...</td></tr>';
            modal.show();

            try {
                const resp = await fetch(`/fire-safety/building/history/${schoolId}`);
                const data = await resp.json();

                if (data.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No removed floors or rooms found.</td></tr>';
                    return;
                }

                tableBody.innerHTML = '';
                data.forEach(item => {
                    const removedAt = new Date(item.removed_at).toLocaleString();
                    const row = `
                        <tr>
                            <td>${removedAt}</td>
                            <td><span class="badge bg-secondary">${item.type}</span></td>
                            <td>${item.item_data.building_name || 'N/A'}</td>
                            <td class="fw-bold text-danger">${item.item_code || 'N/A'}</td>
                            <td>${item.reason || 'No reason provided'}</td>
                            <td>
                                <small>
                                    ${item.item_data.involved_alarms ? `Alarms: ${item.item_data.involved_alarms}<br>` : ''}
                                    ${item.item_data.involved_extinguishers ? `Extinguishers: ${item.item_data.involved_extinguishers}<br>` : ''}
                                    ${item.item_data.involved_rooms ? `Rooms: ${item.item_data.involved_rooms}<br>` : ''}
                                </small>
                            </td>
                        </tr>
                    `;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
            } catch (e) {
                console.error(e);
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Failed to load history.</td></tr>';
            }
        }
        function formatDate(dateString) {
            try {
                const date = new Date(dateString);
                if (isNaN(date.getTime())) {
                    return 'Invalid Date';
                }
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            } catch (error) {
                return 'Invalid Date';
            }
        }

        function getStatusClass(status) {
            const statusMap = {
                'scheduled': 'warning',
                'in_progress': 'info',
                'completed': 'success',
                'cancelled': 'secondary',
                'overdue': 'danger'
            };
            return statusMap[status] || 'secondary';
        }

        function getInspectionTypeText(type) {
            if (!type) return 'N/A';

            const typeMap = {
                // Inspection Types
                'routine': 'Routine Safety Audit',
                'quarterly': 'Quarterly Inspection',
                'annual': 'Annual Comprehensive',
                'fire_drill': 'Fire Drill',
                'emergency': 'Emergency Inspection',
                'preventive': 'Preventive Maintenance',

                // Status Types
                'scheduled': 'Scheduled',
                'in_progress': 'In Progress',
                'completed': 'Completed',
                'cancelled': 'Cancelled',
                'overdue': 'Overdue'
            };

            return typeMap[type.toLowerCase()] || type.charAt(0).toUpperCase() + type.slice(1);
        }

        // Store current school ID (already initialized above)

        // Initialize with first school
        document.addEventListener('DOMContentLoaded', function() {
            const firstTab = document.querySelector('#schoolTab button.active');
            if (firstTab) {
                currentSchoolId = firstTab.getAttribute('data-school-id');
                loadSchoolData(currentSchoolId);
            }

            // Set default date for inspection form to today
            const today = new Date().toISOString().split('T')[0];
            const dateInput = document.querySelector('input[name="inspection_date"]');
            if (dateInput) {
                dateInput.value = today;
                dateInput.min = today; // Prevent past dates
            }
        });

        // School tab switching
        document.querySelectorAll('#schoolTab button').forEach(button => {
            button.addEventListener('shown.bs.tab', function(event) {
                const schoolId = this.getAttribute('data-school-id');
                currentSchoolId = schoolId;
                loadSchoolData(schoolId);
            });
        });

        // Add Building button click
        document.querySelectorAll('.add-building-btn').forEach(button => {
            button.addEventListener('click', function() {
                const schoolId = this.getAttribute('data-school-id');
                document.getElementById('buildingSchoolId').value = schoolId;
            });
        });

        // Schedule Inspection button click
        document.querySelectorAll('.schedule-inspection-btn').forEach(button => {
            button.addEventListener('click', function() {
                const schoolId = this.getAttribute('data-school-id');
                document.getElementById('inspectionSchoolId').value = schoolId;
                loadBuildingsForInspection(schoolId);
            });
        });

        // Inspect Now button click (from building card)
        document.querySelectorAll('.inspect-building-btn').forEach(button => {
            button.addEventListener('click', function() {
                const buildingId = this.getAttribute('data-building-id');
                const buildingName = this.getAttribute('data-building-name');

                document.getElementById('inspectionSchoolId').value = currentSchoolId;

                // Set today's date as default
                const today = new Date().toISOString().split('T')[0];
                document.querySelector('input[name="inspection_date"]').value = today;

                // Pre-select the building
                const buildingSelect = document.getElementById('buildingSelect');
                buildingSelect.innerHTML = `<option value="${buildingId}" selected>${buildingName}</option>`;
            });
        });

        // View Building button click
        document.querySelectorAll('.view-building-btn').forEach(button => {
            button.addEventListener('click', async function() {
                const buildingId = this.getAttribute('data-building-id');

                // Show loading in modal
                document.getElementById('buildingDetailsContent').innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                        <p class="mt-2">Loading building details...</p>
                    </div>
                `;

                try {
                    const response = await fetch(`/fire-safety/building/${buildingId}`);
                    if (!response.ok) {
                        const errorText = await response.text();
                        let errorMessage = 'Failed to load building details. Please try again.';
                        try {
                            const errorData = JSON.parse(errorText);
                            errorMessage = errorData.error || errorMessage;
                        } catch (e) {
                            console.error('Error parsing error response:', errorText);
                        }
                        throw new Error(errorMessage);
                    }
                    const building = await response.json();

                    let html = `
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Building Code:</strong> ${building.building_no}</p>
                                <p><strong>Building Name:</strong> ${building.building_name || 'N/A'}</p>
                                <p><strong>School:</strong> ${building.school?.school_name || 'N/A'}</p>
                                <p><strong>Building Type:</strong> ${building.building_type || 'N/A'}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Floors:</strong> ${building.floors || 0}</p>
                                <p><strong>Standard Rooms:</strong> ${building.rooms || 0}</p>
                                <p><strong>Rooms Added:</strong> ${building.actual_rooms ? building.actual_rooms.length : 0}</p>
                                <p><strong>Minimum Required Extinguisher:</strong> ${building.required_extinguishers_count || 1}</p>
                                <p><strong>Emergency Exits:</strong> ${building.emergency_exits || 'N/A'}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Year Constructed:</strong> ${building.year_constructed || 'N/A'}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Last Renovation:</strong> ${building.last_renovation || 'N/A'}</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <p><strong>Description:</strong></p>
                            <div class="border rounded p-3">${building.description || 'No description available.'}</div>
                        </div>

                        <div class="mb-3">
                            <p><strong>Safety Features:</strong></p>
                            <div class="border rounded p-3">
                                ${(building.features && typeof building.features === 'string') ? building.features.split(',').map(feature =>
                                    `<span class="badge bg-info me-2 mb-2">${feature}</span>`
                                ).join('') : 'No safety features recorded.'}
                            </div>
                        </div>
                    `;

                    let footerHtml = '';
                    if (USER_ROLE === 'admin') {
                        html += `
                            <div class="alert alert-primary mt-3">
                                <i class="fas fa-question-circle me-2"></i>
                                <strong>Already Inspected building?</strong>
                            </div>
                        `;
                        footerHtml = `
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="editBuilding(${building.id})">
                                <i class="fas fa-edit me-2"></i> Update
                            </button>
                        `;
                    } else {
                        html += `
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle me-2"></i>
                                To update building information or remove this building, please contact the system administrator.
                            </div>
                        `;
                        footerHtml = `
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        `;
                    }

                    document.getElementById('buildingDetailsContent').innerHTML = html;
                    document.getElementById('buildingModalFooter').innerHTML = footerHtml;

                } catch (error) {
                    console.error('Error loading building details:', error);
                    document.getElementById('buildingDetailsContent').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            ${error.message || 'Failed to load building details. Please try again.'}
                        </div>
                    `;
                    document.getElementById('buildingModalFooter').innerHTML = `
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    `;
                }
            });
        });

        // Edit Building functionality
        async function editBuilding(buildingId) {
            try {
                const response = await fetch(`/fire-safety/building/${buildingId}`);
                const building = await response.json();

                // Close view modal
                const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewBuildingModal'));
                if (viewModal) viewModal.hide();

                // Open add/edit modal but with update behavior
                const editModalEl = document.getElementById('addBuildingModal');
                const editModal = new bootstrap.Modal(editModalEl);

                // Update modal title
                editModalEl.querySelector('.modal-title').innerHTML = '<i class="fas fa-edit me-2"></i> Update Building Information';

                // Set form action and method for update
                const form = document.getElementById('addBuildingForm');
                const originalAction = form.action;
                form.action = `/fire-safety/building/${buildingId}/update`;

                // Fill form data
                form.querySelector('[name="building_no"]').value = building.building_no;
                form.querySelector('[name="building_name"]').value = building.building_name || '';

                const floorsInput = form.querySelector('#buildingFloorsInput');
                floorsInput.value = building.floors;
                floorsInput.min = building.floors;
                floorsInput.readOnly = true;
                document.getElementById('btnIncFloors').style.display = 'block';

                const roomsInput = form.querySelector('#buildingRoomsInput');
                roomsInput.value = building.rooms;
                roomsInput.min = building.rooms;
                roomsInput.readOnly = true;
                document.getElementById('btnIncRooms').style.display = 'block';

                form.querySelector('[name="year_constructed"]').value = building.year_constructed || '';
                form.querySelector('[name="last_renovation"]').value = building.last_renovation || '';
                form.querySelector('[name="emergency_exits"]').value = building.emergency_exits || 0;
                const typeSelect = form.querySelector('[name="building_type"]');
                typeSelect.value = building.building_type || '';
                typeSelect.disabled = true; // Building type can't be edited

                form.querySelector('[name="description"]').value = building.description || '';

                // Handle Gymnasium/Cafeteria restriction
                const isMiniBldg = ['gymnasium', 'cafeteria'].includes(building.building_type?.toLowerCase());
                if (isMiniBldg) {
                    document.getElementById('roomFloorInputs').style.display = 'none';
                    floorsInput.value = 1;
                    roomsInput.value = 1;
                } else {
                    document.getElementById('roomFloorInputs').style.display = 'block';
                }

                // Populate Removal Selection
                const removeFloorSelect = document.getElementById('removeFloorSelect');
                const removeRoomSelect = document.getElementById('removeRoomSelect');
                removeFloorSelect.innerHTML = '<option value="">-- No Floor to Remove --</option>';
                removeRoomSelect.innerHTML = '<option value="">-- No Room to Remove --</option>';

                // Add floors
                for (let i = 1; i <= building.floors; i++) {
                    const opt = document.createElement('option');
                    opt.value = i;
                    opt.textContent = `Floor no. ${i}`;
                    removeFloorSelect.appendChild(opt);
                }

                // Add rooms
                if (building.rooms_list && Array.isArray(building.rooms_list)) {
                    building.rooms_list.forEach(room => {
                        const opt = document.createElement('option');
                        opt.value = room.id;
                        opt.textContent = `${room.room_name} (Floor ${room.floor_no})`;
                        opt.dataset.extinguisher = room.is_center_room ? 'yes' : 'no';
                        opt.dataset.hasOthers = room.has_other_rooms_on_floor ? 'yes' : 'no';
                        opt.dataset.floorNo = room.floor_no; // Add floor number for filtering
                        removeRoomSelect.appendChild(opt);
                    });
                }

                // Add event listener to filter rooms when a floor is selected for removal
                removeFloorSelect.addEventListener('change', function() {
                    const selectedFloor = this.value;
                    const roomOptions = removeRoomSelect.querySelectorAll('option');

                    roomOptions.forEach(option => {
                        if (option.value === '') return; // Skip the default option

                        if (selectedFloor && option.dataset.floorNo === selectedFloor) {
                            // Hide rooms from the selected floor
                            option.style.display = 'none';
                            option.disabled = true;
                        } else {
                            // Show all other rooms
                            option.style.display = 'block';
                            option.disabled = false;
                        }
                    });

                    // Reset room selection if the selected room is from the removed floor
                    const selectedRoomOption = removeRoomSelect.options[removeRoomSelect.selectedIndex];
                    if (selectedRoomOption && selectedRoomOption.dataset.floorNo === selectedFloor) {
                        removeRoomSelect.value = '';
                    }
                });

                document.getElementById('manageFloorsRoomsSection').style.display = isMiniBldg ? 'none' : 'block';
                document.getElementById('buildingReqExt').value = building.required_extinguishers || 0;

                // Handle checkboxes
                const features = building.features ? building.features.split(',') : [];
                form.querySelectorAll('[name="features[]"]').forEach(cb => {
                    cb.checked = features.includes(cb.value);
                });

                // Update save button to update button
                const saveBtn = editModalEl.querySelector('.btn-primary');
                const originalOnClick = saveBtn.getAttribute('onclick');
                saveBtn.innerHTML = '<i class="fas fa-save me-2"></i> Update Information';
                saveBtn.setAttribute('onclick', `updateBuilding(${buildingId}, '${building.building_no}')`);

                // Reset modal on hide
                editModalEl.addEventListener('hidden.bs.modal', function() {
                    form.action = originalAction;
                    form.reset();
                    editModalEl.querySelector('.modal-title').innerHTML = '<i class="fas fa-plus me-2"></i> Add New Building';
                    saveBtn.innerHTML = '<i class="fas fa-save me-2"></i> Save Building';
                    saveBtn.setAttribute('onclick', originalOnClick);
                    floorsInput.min = 1;
                    roomsInput.min = 1;
                    typeSelect.disabled = false;
                    document.getElementById('roomFloorInputs').style.display = 'block';
                    document.getElementById('manageFloorsRoomsSection').style.display = 'none';
                    document.getElementById('floorRemovalReasonSection').style.display = 'none';
                    document.getElementById('roomRemovalReasonSection').style.display = 'none';
                    document.getElementById('floorRemovalReason').value = '';
                    document.getElementById('roomRemovalReason').value = '';
                }, { once: true });

                editModal.show();

            } catch (error) {
                console.error('Error loading building for edit:', error);
                Swal.fire('Error', 'Failed to load building data for editing.', 'error');
            }
        }

        async function updateBuilding(buildingId, oldBuildingNo) {
            const form = document.getElementById('addBuildingForm');
            const formData = new FormData(form);
            const newBuildingNo = formData.get('building_no');

            if (newBuildingNo !== oldBuildingNo) {
                const result = await Swal.fire({
                    title: 'Confirmation',
                    text: "Are sure you want to update Building Code?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#A8191F',
                    confirmButtonText: 'Yes, update it!',
                    cancelButtonText: 'No, cancel!'
                });

                if (!result.isConfirmed) return;
                formData.append('removed_floor', floorToRemove);
                formData.append('floor_removal_reason', document.getElementById('floorRemovalReason').value);
            }

            // Room Removal Confirmation
            const roomToRemoveSelect = document.getElementById('removeRoomSelect');
            const roomIdToRemove = roomToRemoveSelect.value;
            if (roomIdToRemove) {
                const roomName = roomToRemoveSelect.options[roomToRemoveSelect.selectedIndex].text;
                const isCenter = roomToRemoveSelect.options[roomToRemoveSelect.selectedIndex].dataset.extinguisher === 'yes';
                const hasOthers = roomToRemoveSelect.options[roomToRemoveSelect.selectedIndex].dataset.hasOthers === 'yes';

                let message = `Are you sure you want to remove room ${roomName}?`;
                if (isCenter) {
                    if (hasOthers) {
                        message += `, fire extinguisher will be re-assigned to its nearest room`;
                    } else {
                        message += `, fire extinguiser will be completely removed as there aren't anymore rooms to this floor left to be reassigned`;
                    }
                }

                const result = await Swal.fire({
                    title: 'Room Removal Warning',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#A8191F',
                    confirmButtonText: 'Yes, remove room!',
                    cancelButtonText: 'No, cancel!'
                });

                if (!result.isConfirmed) return;
                formData.append('removed_room_id', roomIdToRemove);
                formData.append('room_removal_reason', document.getElementById('roomRemovalReason').value);
            }

            try {
                const response = await fetch(form.action, {
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
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#A8191F'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'Failed to update building.',
                        icon: 'error',
                        confirmButtonColor: '#A8191F'
                    });
                }
            } catch (error) {
                console.error('Error updating building:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'An unexpected error occurred.',
                    icon: 'error',
                    confirmButtonColor: '#A8191F'
                });
            }
        }

        // Add Gymnasium/Cafeteria locking logic for Add Mode
        document.getElementById('building_type_select').addEventListener('change', function() {
            const isMiniBldg = ['gymnasium', 'cafeteria'].includes(this.value.toLowerCase());
            const roomsIn = document.getElementById('buildingRoomsInput');
            const floorsIn = document.getElementById('buildingFloorsInput');
            const btnIncRooms = document.getElementById('btnIncRooms');
            const btnIncFloors = document.getElementById('btnIncFloors');

            if (isMiniBldg) {
                roomsIn.value = 1;
                floorsIn.value = 1;
                roomsIn.disabled = true;
                floorsIn.disabled = true;
                btnIncRooms.disabled = true;
                btnIncFloors.disabled = true;
                btnIncRooms.style.display = 'none';
                btnIncFloors.style.display = 'none';
            } else {
                roomsIn.disabled = false;
                floorsIn.disabled = false;
                btnIncRooms.disabled = false;
                btnIncFloors.disabled = false;
                btnIncRooms.style.display = 'block';
                btnIncFloors.style.display = 'block';
            }
        });

        // Load school data (inspections and stats)
        async function loadSchoolData(schoolId) {
            if (!schoolId) return;

            try {
                // Load inspections if container exists
                const inspectionsContainer = document.getElementById(`inspectionsList-${schoolId}`);
                if (inspectionsContainer) {
                    await loadInspections(schoolId);
                }

                // Load compliance stats if container exists
                const statsContainer = document.getElementById(`complianceStats-${schoolId}`);
                if (statsContainer) {
                    await loadComplianceStats(schoolId);
                }

                // Load sidebar stats if container exists
                const sidebarStats = document.getElementById('sidebarStats');
                if (sidebarStats) {
                    await loadSidebarStats(schoolId);
                }
            } catch (error) {
                console.error('Error loading school data:', error);
            }
        }

        // Load inspections for a school
        async function loadInspections(schoolId) {
            console.log('Loading inspections for school:', schoolId);

            const container = document.getElementById(`inspectionsList-${schoolId}`);
            if (!container) {
                console.error('Container not found for school:', schoolId);
                return;
            }

            try {
                const response = await fetch(`/fire-safety/inspections/${schoolId}`);
                console.log('Response status:', response.status);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const inspections = await response.json();
                console.log('Inspections loaded:', inspections);

                if (!inspections || inspections.length === 0) {
                    container.innerHTML = `
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-calendar-times fa-2x mb-3"></i>
                            <p>No upcoming inspections scheduled.</p>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#scheduleInspectionModal">
                                <i class="fas fa-calendar-plus me-1"></i> Schedule One Now
                            </button>
                        </div>
                    `;
                    return;
                }

                let html = `
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Building</th>
                                    <th>Type</th>
                                    <th>Inspector</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                inspections.forEach(inspection => {
                    const date = formatDate(inspection.inspection_date);
                    const statusClass = getStatusClass(inspection.status);
                    const statusText = getInspectionTypeText(inspection.status);
                    const typeText = getInspectionTypeText(inspection.inspection_type);

                    html += `
                        <tr>
                            <td>${date}</td>
                            <td>${inspection.building_name || 'N/A'}</td>
                            <td>${typeText}</td>
                            <td>${inspection.inspector || 'N/A'}</td>
                            <td>
                                <span class="badge bg-${statusClass}">${statusText}</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="viewInspection(${inspection.id})">
                                    <i class="fas fa-eye"></i>
                                </button>
                                ${inspection.status === 'scheduled' ? `
                                <button class="btn btn-sm btn-outline-danger" onclick="cancelInspection(${inspection.id})">
                                    <i class="fas fa-times"></i>
                                </button>
                                ` : `
                                <button class="btn btn-sm btn-outline-secondary" disabled>
                                    <i class="fas fa-times"></i>
                                </button>
                                `}
                            </td>
                        </tr>
                    `;
                });

                html += `
                            </tbody>
                        </table>
                    </div>
                `;

                container.innerHTML = html;

            } catch (error) {
                console.error('Error loading inspections:', error);
                container.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Failed to load inspections. Please try again.
                        <button class="btn btn-sm btn-light ms-3" onclick="loadInspections(${schoolId})">
                            <i class="fas fa-redo"></i> Retry
                        </button>
                    </div>
                `;
            }
        }

        // Load compliance statistics
        async function loadComplianceStats(schoolId) {
            try {
                const response = await fetch(`/fire-safety/compliance-stats/${schoolId}`);
                const stats = await response.json();

                const compliantCount = stats.compliant || 0;
                const needsAttentionCount = stats.needs_attention || 0;
                const nonCompliantCount = stats.non_compliant || 0;
                const total = compliantCount + needsAttentionCount + nonCompliantCount;
                const overallPercentage = total > 0 ? Math.round((compliantCount / total) * 100) : 0;

                let html = `
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <h3>${overallPercentage}% Overall</h3>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar ${overallPercentage >= 80 ? 'bg-success' : overallPercentage >= 60 ? 'bg-warning' : 'bg-danger'}"
                                     style="width: ${overallPercentage}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-4">
                            <h5 class="text-success">${compliantCount}</h5>
                            <small>Compliant</small>
                        </div>
                        <div class="col-4">
                            <h5 class="text-warning">${needsAttentionCount}</h5>
                            <small>Needs Attention</small>
                        </div>
                        <div class="col-4">
                            <h5 class="text-danger">${nonCompliantCount}</h5>
                            <small>Non-Compliant</small>
                        </div>
                    </div>
                `;

                // Add priorities if there are non-compliant buildings
                if (nonCompliantCount > 0) {
                    html += `
                        <hr>
                        <div class="mt-3">
                            <h6>Top Priorities:</h6>
                            <ol class="small">
                                <li>Address non-compliant buildings</li>
                                <li>Schedule immediate inspections</li>
                                <li>Review safety equipment</li>
                            </ol>
                        </div>
                    `;
                }

                document.getElementById(`complianceStats-${schoolId}`).innerHTML = html;

            } catch (error) {
                console.error('Error loading compliance stats:', error);
                document.getElementById(`complianceStats-${schoolId}`).innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Failed to load statistics.
                    </div>
                `;
            }
        }

        // Load sidebar stats
        async function loadSidebarStats(schoolId) {
            try {
                const response = await fetch(`/fire-safety/sidebar-stats/${schoolId}`);
                const stats = await response.json();

                let html = `
                    <div class="text-white mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span>Compliant: <strong>${stats.compliant || 0}</strong></span>
                    </div>
                    <div class="text-white mb-2">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        <span>Needs Attention: <strong>${stats.needs_attention || 0}</strong></span>
                    </div>
                    <div class="text-white mb-3">
                        <i class="fas fa-times-circle text-danger me-2"></i>
                        <span>Non-Compliant: <strong>${stats.non_compliant || 0}</strong></span>
                    </div>
                `;

                document.getElementById('sidebarStats').innerHTML = html;

            } catch (error) {
                console.error('Error loading sidebar stats:', error);
            }
        }

        // Load all inspections (for refresh button)
        async function loadAllInspections(schoolId) {
            await loadInspections(schoolId);
            Swal.fire({
                title: 'Refreshed!',
                text: 'Inspections have been reloaded.',
                icon: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        }

        // Load buildings for inspection dropdown
        async function loadBuildingsForInspection(schoolId) {
            try {
                const response = await fetch(`/fire-safety/buildings-list/${schoolId}`);
                const buildings = await response.json();

                const select = document.getElementById('buildingSelect');
                select.innerHTML = '<option value="">Select Building</option>';

                buildings.forEach(building => {
                    const option = document.createElement('option');
                    option.value = building.id;
                    option.textContent = building.building_no + (building.building_name ? ` (${building.building_name})` : '');
                    select.appendChild(option);
                });

            } catch (error) {
                console.error('Error loading buildings:', error);
                Swal.fire('Error', 'Failed to load buildings. Please try again.', 'error');
            }
        }

       // Save Building - FIXED VERSION
        async function saveBuilding() {
            const form = document.getElementById('addBuildingForm');

            // Basic validation for required fields only
            const buildingNo = form.querySelector('[name="building_no"]').value.trim();
            const buildingName = form.querySelector('[name="building_name"]').value.trim();

            if (!buildingNo || !buildingName) {
                Swal.fire('Validation Error', 'Building number and name are required.', 'warning');
                return;
            }

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Get CSRF token - FIXED
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Alternative fallback if meta tag method fails
            if (!csrfToken) {
                csrfToken = document.querySelector('input[name="_token"]')?.value;
                if (!csrfToken) {
                    Swal.fire('Error', 'CSRF token missing. Please refresh the page and try again.', 'error');
                    return;
                }
            }

            const formData = new FormData(form);
            const yearConstructed = parseInt(formData.get('year_constructed')) || 0;
            const lastRenovation = parseInt(formData.get('last_renovation')) || 0;

            // Validation
            if (yearConstructed && lastRenovation && lastRenovation < yearConstructed) {
                Swal.fire('Invalid Dates', 'Last renovation year cannot be earlier than the year constructed.', 'warning');
                return;
            }

            // Show loading state
            const saveButton = document.querySelector('#addBuildingModal .btn-primary');
            const originalText = saveButton.innerHTML;
            saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Saving...';
            saveButton.disabled = true;

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addBuildingModal'));
                    if (modal) modal.hide();

                    Swal.fire({
                        title: 'Success!',
                        text: 'Building added successfully!',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to add building', 'error');
                    saveButton.innerHTML = originalText;
                    saveButton.disabled = false;
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Failed to add building. Please check your connection and try again.', 'error');
                saveButton.innerHTML = originalText;
                saveButton.disabled = false;
            }
        }

        // Save Inspection
        async function saveInspection() {
            const form = document.getElementById('scheduleInspectionForm');

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire('Success', 'Inspection scheduled successfully!', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to schedule inspection', 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Failed to schedule inspection', 'error');
            }
        }

        // View Inspection Details
        async function viewInspection(inspectionId) {
            try {
                const response = await fetch(`/fire-safety/inspection/${inspectionId}`);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const inspection = await response.json();

                // Create and show modal
                const modalHtml = `
                    <div class="modal fade" id="viewInspectionModal" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                                    <h5 class="modal-title">
                                        <i class="fas fa-clipboard-check me-2"></i> Inspection #${inspection.id}
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <strong>Inspection Date:</strong><br>
                                                <span class="text-muted">${formatDate(inspection.inspection_date)}</span>
                                            </div>
                                            <div class="mb-3">
                                                <strong>Building:</strong><br>
                                                <span class="text-muted">${inspection.building_name || 'N/A'}</span>
                                            </div>
                                            <div class="mb-3">
                                                <strong>School:</strong><br>
                                                <span class="text-muted">${inspection.school?.school_name || 'N/A'}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <strong>Inspection Type:</strong><br>
                                                <span class="badge bg-primary">${getInspectionTypeText(inspection.inspection_type)}</span>
                                            </div>
                                            <div class="mb-3">
                                                <strong>Status:</strong><br>
                                                <span class="badge bg-${getStatusClass(inspection.status)}">${getInspectionTypeText(inspection.status)}</span>
                                            </div>
                                            <div class="mb-3">
                                                <strong>Inspector:</strong><br>
                                                <span class="text-muted">${inspection.inspector || 'N/A'}</span>
                                            </div>
                                        </div>
                                    </div>

                                    ${inspection.notes ? `
                                    <div class="mb-4">
                                        <strong>Notes:</strong>
                                        <div class="border rounded p-3 bg-light mt-1">${inspection.notes}</div>
                                    </div>
                                    ` : ''}

                                    ${inspection.findings ? `
                                    <div class="mb-4">
                                        <strong>Findings:</strong>
                                        <div class="border rounded p-3 bg-light mt-1">${inspection.findings}</div>
                                    </div>
                                    ` : ''}

                                    ${inspection.recommendations ? `
                                    <div class="mb-4">
                                        <strong>Recommendations:</strong>
                                        <div class="border rounded p-3 bg-light mt-1">${inspection.recommendations}</div>
                                    </div>
                                    ` : ''}

                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Created on: ${formatDate(inspection.created_at)}
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    ${inspection.status === 'scheduled' ? `
                                    <button type="button" class="btn btn-primary" onclick="startInspection(${inspection.id})">
                                        <i class="fas fa-play me-2"></i> Start Inspection
                                    </button>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                // Remove existing modal if any
                const existingModal = document.getElementById('viewInspectionModal');
                if (existingModal) existingModal.remove();

                // Add modal to body and show it
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                const modal = new bootstrap.Modal(document.getElementById('viewInspectionModal'));
                modal.show();

            } catch (error) {
                console.error('Error loading inspection details:', error);
                Swal.fire('Error', 'Failed to load inspection details. Please try again.', 'error');
            }
        }

        async function cancelInspection(inspectionId) {
            const result = await Swal.fire({
                title: 'Cancel Inspection?',
                text: "Are you sure you want to cancel this inspection? This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, cancel it!',
                cancelButtonText: 'No, keep it'
            });

            if (!result.isConfirmed) return;

            try {
                const response = await fetch(`/fire-safety/inspection/${inspectionId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire('Cancelled', 'Inspection has been cancelled.', 'success');
                    // Reload the current school's inspections
                    if (currentSchoolId) {
                        await loadInspections(currentSchoolId);
                    }
                } else {
                    Swal.fire('Error', data.message || 'Failed to cancel inspection', 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Failed to cancel inspection. Please try again.', 'error');
            }
        }

        // Start Inspection function
        async function startInspection(inspectionId) {
            // Redirect to inspection checklist page
            window.location.href = `/fire-safety/inspection/${inspectionId}/checklist`;
        }

        // Generate building report
        function generateBuildingReport() {
            Swal.fire({
                title: 'Generate Report?',
                text: "Generate comprehensive building safety report for all schools?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Generate',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Generating...',
                        text: 'Building safety report generation started... This may take a moment.',
                        icon: 'info',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    // In real implementation, this would generate PDF report
                }
            });
        }

        function incrementValue(inputId) {
            const input = document.getElementById(inputId);
            let value = parseInt(input.value) || 0;
            const max = parseInt(input.max) || 1000;

            if (value < max) {
                input.value = value + 1;
            }
        }

        // Reset form when opening Add Building modal in Add mode
        document.getElementById('addBuildingModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            // If button has data-school-id, it's the Add button (not Update)
            if (button && button.hasAttribute('data-school-id') && !button.hasAttribute('data-building-id')) {
                const form = document.getElementById('addBuildingForm');
                form.reset();
                form.action = "{{ route('fire-safety.building.store') }}";

                // Reset inputs to default
                // Reset inputs to default and unlock
                document.getElementById('buildingFloorsInput').value = 1;
                document.getElementById('buildingFloorsInput').min = 1;
                document.getElementById('buildingFloorsInput').readOnly = false;
                document.getElementById('btnIncFloors').style.display = 'none';

                document.getElementById('buildingRoomsInput').value = 1;
                document.getElementById('buildingRoomsInput').min = 1;
                document.getElementById('buildingRoomsInput').readOnly = false;
                document.getElementById('btnIncRooms').style.display = 'none';

                document.querySelector('#addBuildingModal .modal-title').innerHTML = '<i class="fas fa-plus me-2"></i> Add New Building';
                document.getElementById('manageFloorsRoomsSection').style.display = 'none';
                document.getElementById('buildingReqExt').value = 0;

                // Enable building type select
                document.getElementById('building_type_select').disabled = false;
            }
        });
    </script>
</body>
</html>
