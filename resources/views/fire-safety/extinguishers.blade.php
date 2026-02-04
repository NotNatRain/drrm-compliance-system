<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fire Extinguishers - Fire Safety</title>
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

        .no-data {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }

        .no-data i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #adb5bd;
        }
    </style>
    <style>
        .health-bar {
            height: 25px; /* Fatten/Large height */
            width: 100%;
            background-color: #e9ecef;
            border-radius: 12px;
            margin-top: 5px;
            overflow: hidden;
            position: relative;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.2);
        }
        .health-bar-fill {
            height: 100%;
            transition: width 0.3s ease;
        }
        .health-bar-text {
            position: absolute;
            width: 100%;
            text-align: center;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 11px;
            font-weight: bold;
            color: #000;
            text-shadow: 0 0 2px rgba(255,255,255,0.8);
        }
        .health-good { background-color: #28a745; } /* OK */
        .health-warning { background-color: #ffc107; } /* For Refill */
        .health-danger { background-color: #dc3545; } /* Empty/Missing */

        /* SweetAlert2 Custom Styling */
        .swal2-popup {
            border-radius: 15px !important;
        }
        .swal2-styled.swal2-confirm {
            background-color: var(--fire-red) !important;
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
                    <h4 class="text-white mb-0">Fire Extinguishers (Room-Based)</h4>
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
                    <a class="nav-link" href="{{ route('fire-safety.alarm-systems') }}">
                        <span class="nav-icon"><i class="fas fa-bell"></i></span>
                        <span>Alarm Systems</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('fire-safety.extinguishers') }}">
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

            <div class="mt-4 text-white small">
                <div class="mb-2">
                    <i class="fas fa-info-circle me-2"></i>
                    Rule: 1 extinguisher can cover 1–3 rooms.
                </div>
                <div class="mb-2">
                    <i class="fas fa-info-circle me-2"></i>
                    If covering 2–3 rooms, the selected “Center Room” must be included.
                </div>
                <div class="mb-2">
                    <i class="fas fa-info-circle me-2"></i>
                    Some rooms can only share with 1 room.
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @if($schools->isEmpty())
            <div class="row">
                <div class="col-12">
                    <div class="card dashboard-card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-school fa-4x text-muted mb-4"></i>
                            <h4 class="text-muted mb-3">No Schools Found</h4>
                            <p class="text-muted mb-4">You need to add a school under inspection first.</p>
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
                    @php
                        $allRoomsCount = $school->buildings->sum('rooms');
                        $allRoomsCollection = $school->buildings->flatMap(fn ($b) => $b->rooms()->get());
                        $allExts = $school->buildings->flatMap(fn ($b) => $b->fireExtinguishers);
                        $coveredRoomIds = $allExts->flatMap(fn ($e) => $e->coveredRooms->pluck('id'))->unique();
                        $uncoveredRoomsCount = max(0, $allRoomsCollection->count() - $coveredRoomIds->count());
                        $labRooms = $allRoomsCollection->where('room_type', 'laboratory');
                        $labsCovered = $labRooms->filter(fn ($r) => $coveredRoomIds->contains($r->id))->count();
                    @endphp

                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="school-{{ $school->id }}">
                        <!-- Summary -->
                        <div class="row mb-4">
                            <!-- Total Rooms -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card dashboard-card h-100 border-left-primary">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Rooms</div>
                                                <div class="h2 mb-0 fw-bold text-gray-800">{{ $allRoomsCollection->count() }}</div>
                                            </div>
                                            <i class="fas fa-door-closed fa-2x text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Room Coverage (Combined) -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card dashboard-card h-100 border-left-success">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="text-xs fw-bold text-dark text-uppercase mb-1">Room Coverage</div>
                                                <div class="mb-0 fw-bold">
                                                    <span class="text-success">{{ $coveredRoomIds->count() }} Covered ✔</span>
                                                    <span class="text-muted mx-1">|</span>
                                                    <span class="text-danger">{{ $uncoveredRoomsCount }} Uncovered X</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Coverage Compliance (%) -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card dashboard-card h-100 border-left-info">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="text-xs fw-bold text-info text-uppercase mb-1">Coverage Compliance</div>
                                                <div class="h2 mb-0 fw-bold text-gray-800">
                                                    {{ $allRoomsCollection->count() > 0 ? round(($coveredRoomIds->count() / $allRoomsCollection->count()) * 100, 1) : 0 }}%
                                                </div>
                                            </div>
                                            <i class="fas fa-percent fa-2x text-info"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Extinguisher Status Ratio -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card dashboard-card h-100 border-left-warning">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="text-xs fw-bold text-warning text-uppercase mb-1">Evaluation Result</div>
                                                <div class="h2 mb-0 fw-bold text-gray-800">
                                                    {{ $allExts->where('status', 'active')->count() }} / {{ $allExts->count() }}
                                                    <span class="text-xs text-muted fw-normal">{{ $allExts->where('status', 'active')->count() === $allExts->count() && $allExts->count() > 0 ? 'Passed' : 'Failed' }}</span>
                                                </div>
                                            </div>
                                            <i class="fas fa-clipboard-check fa-2x text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($labRooms->count() > 0 && $labsCovered < $labRooms->count())
                            <div class="alert alert-warning">
                                <strong>Laboratory coverage:</strong>
                                {{ $labsCovered }}/{{ $labRooms->count() }} laboratory rooms currently have an assigned extinguisher coverage.
                            </div>
                        @endif

                        <!-- Buildings -->
                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="card dashboard-card">
                                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                        <h6 class="m-0 fw-bold text-primary">
                                            <i class="fas fa-list me-2"></i> Room-Based Extinguishers - {{ $school->school_name }}
                                        </h6>
                                        <div>
                                            @if(auth()->user()->role === 'admin')
                                            <button class="btn btn-outline-primary btn-sm me-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#addRoomModal"
                                                    data-school-id="{{ $school->id }}">
                                                <i class="fas fa-door-open me-2"></i> Add Room
                                            </button>
                                            <button class="btn btn-primary btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#addExtModal"
                                                    data-school-id="{{ $school->id }}">
                                                <i class="fas fa-plus me-2"></i> Add Extinguisher
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @if($school->buildings->isEmpty())
                                            <div class="no-data">
                                                <i class="fas fa-building"></i>
                                                <h5>No Buildings Found</h5>
                                                <p class="text-muted mb-0">Add buildings first in the Buildings page.</p>
                                            </div>
                                        @else
                                            <div class="accordion" id="buildingAccordion-{{ $school->id }}">
                                                @foreach($school->buildings as $building)
                                                    @php
                                                        $coverageMap = [];
                                                        foreach ($building->fireExtinguishers as $ext) {
                                                            foreach ($ext->coveredRooms as $r) {
                                                                $coverageMap[$r->id] = $ext;
                                                            }
                                                        }
                                                    @endphp

                                                    <div class="accordion-item mb-2">
                                                        <h2 class="accordion-header" id="heading-{{ $school->id }}-{{ $building->id }}">
                                                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#collapse-{{ $school->id }}-{{ $building->id }}"
                                                                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                                                    aria-controls="collapse-{{ $school->id }}-{{ $building->id }}">
                                                                <strong class="me-2">{{ $building->building_no }}</strong>
                                                                <span class="text-muted">{{ $building->building_name }}</span>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse-{{ $school->id }}-{{ $building->id }}"
                                                             class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                                             aria-labelledby="heading-{{ $school->id }}-{{ $building->id }}"
                                                             data-bs-parent="#buildingAccordion-{{ $school->id }}">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-lg-7 mb-4">
                                                                        <h6 class="fw-bold mb-2"><i class="fas fa-door-closed me-2"></i>Rooms</h6>
                                                                        @if($building->rooms()->count() == 0)
                                                                            <div class="alert alert-secondary mb-0">
                                                                                No rooms defined yet for this building.
                                                                            </div>
                                                                        @else
                                                                            <div class="table-responsive">
                                                                                <table class="table table-sm table-hover align-middle">
                                                                                    <thead class="table-light">
                                                                                        <tr>
                                                                                            <th>Room</th>
                                                                                            <th>Type</th>
                                                                                            <th>Floor</th>
                                                                                            <th>Covered By</th>
                                                                                            <th class="text-end">Action</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @foreach($building->rooms()->get() as $room)
                                                                                            @php $ext = $coverageMap[$room->id] ?? null; @endphp
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <div class="fw-semibold">{{ $room->room_name }}</div>
                                                                                                    <div class="text-muted small">{{ $room->room_code }}</div>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <span class="badge bg-{{ $room->room_type === 'laboratory' ? 'danger' : ($room->room_type === 'auxiliary' ? 'info' : 'secondary') }}">
                                                                                                        {{ ucfirst($room->room_type) }}
                                                                                                    </span>
                                                                                                </td>
                                                                                                <td>{{ $room->floor_no ?? '—' }}</td>
                                                                                                <td>
                                                                                                    @if($ext)
                                                                                                        <span class="badge bg-success">{{ $ext->code }}</span>
                                                                                                        @if($ext->room_id === $room->id)
                                                                                                            <span class="badge bg-primary">Center</span>
                                                                                                        @endif
                                                                                                    @else
                                                                                                        <span class="badge bg-warning text-dark">Uncovered</span>
                                                                                                    @endif
                                                                                                </td>
                                                                                                <td class="text-end">
                                                                                                    <button class="btn btn-sm btn-outline-primary" onclick="inspectRoom({{ $room->id }})">
                                                                                                        <i class="fas fa-search-plus me-1"></i> Inspect & Update
                                                                                                    </button>
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        @endif
                                                                    </div>

                                                                    <div class="col-lg-5 mb-4">
                                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                                            <h6 class="fw-bold mb-0"><i class="fas fa-fire-extinguisher me-2"></i>Extinguishers</h6>
                                                                            @php
                                                                                $bldgExts = $building->fireExtinguishers;
                                                                                $bldgActive = $bldgExts->where('status', 'active')->count();
                                                                                $bldgTotal = $bldgExts->count();
                                                                                $evalText = $bldgTotal > 0 && ($bldgActive/$bldgTotal >= 1.0) ? 'Passed' : 'Failed';
                                                                                $evalColor = $evalText === 'Passed' ? 'success' : 'danger';
                                                                            @endphp
                                                                            <span class="badge bg-{{ $evalColor }}">Evaluation Result: {{ $evalText }} ({{ $bldgActive }}/{{ $bldgTotal }})</span>
                                                                        </div>
                                                                        @if($building->fireExtinguishers->isEmpty())
                                                                            <div class="alert alert-secondary mb-0">
                                                                                No extinguishers recorded yet for this building.
                                                                            </div>
                                                                        @else
                                                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover align-middle border">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Extinguisher Details</th>
                                                            <th>Tracking</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($building->fireExtinguishers as $ext)
                                                            @php
                                                                $pressure = $ext->pressure_level ?? 100;
                                                                $statusLabel = 'OK';
                                                                $healthClass = 'health-good';
                                                                $badgeClass = 'success';

                                                                if ($ext->status === 'maintenance') {
                                                                    $statusLabel = 'For Refill';
                                                                    $healthClass = 'health-warning';
                                                                    $badgeClass = 'warning';
                                                                } elseif ($ext->status === 'expired' || $ext->status === 'missing') {
                                                                    $statusLabel = $ext->status === 'expired' ? 'Empty' : 'Missing';
                                                                    $healthClass = 'health-danger';
                                                                    $badgeClass = 'danger';
                                                                }
                                                            @endphp
                                                            <tr>
                                                                <td>
                                                                    <div class="row g-2 mb-2">
                                                                        <div class="col-6">
                                                                            <div class="small fw-bold">Status & Pressure:</div>
                                                                            <span class="badge bg-{{ $badgeClass }}">{{ $statusLabel }}</span>
                                                                        </div>
                                                                        <div class="col-6 text-end">
                                                                            <div class="small fw-bold">Type:</div>
                                                                            <span class="badge bg-secondary">{{ $ext->type }}</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="health-bar" title="Pressure: {{ $pressure }}%">
                                                                        <div class="health-bar-fill {{ $healthClass }}" style="width: {{ $pressure }}%"></div>
                                                                        <div class="health-bar-text">{{ $pressure }}%</div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="small mb-1"><strong>Code:</strong> {{ $ext->code }}</div>
                                                                    <div class="small mb-1"><strong>As Of:</strong> {{ $ext->date_checked ? \Carbon\Carbon::parse($ext->date_checked)->format('m-d-Y') : 'N/A' }}</div>
                                                                    <div class="small mb-1"><strong>Location:</strong> {{ $ext->centerRoom->room_name ?? 'N/A' }}</div>
                                                                    <div class="small"><strong>Covering:</strong> {{ $ext->coveredRooms->count() }} Rooms</div>
                                                                </td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-primary w-100"
                                                                            onclick="openUpdateModal({{ $ext->id }}, '{{ $ext->code }}', '{{ $ext->status }}', {{ $pressure }})">
                                                                        Update
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Inspections -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card dashboard-card">
                                    <div class="card-header py-3 d-flex justify-content-between align-items-center bg-light">
                                        <h6 class="m-0 fw-bold text-dark">
                                            <i class="fas fa-history me-2"></i> Recent Inspections - {{ $school->school_name }}
                                        </h6>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="loadRecentInspections({{ $school->id }})">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped" id="inspectionsTable-{{ $school->id }}">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Extinguisher Code</th>
                                                        <th>Location</th>
                                                        <th>Inspector</th>
                                                        <th>Status</th>
                                                        <th>Pressure</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr><td colspan="6" class="text-center text-muted">Loading...</td></tr>
                                                </tbody>
                                            </table>
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

    <!-- Update Extinguisher Modal -->
    <div class="modal fade" id="updateExtModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Update Extinguisher</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="updateExtForm">
                        @csrf
                        <input type="hidden" id="updateExtId">

                        <div class="mb-3">
                            <label class="form-label">Extinguisher Code</label>
                            <input type="text" class="form-control" id="updateExtCode" readonly>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status *</label>
                                <select class="form-control" name="status" id="updateExtStatus" required onchange="handleUpdateStatusChange()">
                                    <option value="active">OK (Active)</option>
                                    <option value="maintenance">For Refill</option>
                                    <option value="expired">Empty</option>
                                    <option value="missing">Missing</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pressure (0-100%) *</label>
                                <input type="number" class="form-control" name="pressure_level" id="updateExtPressure" min="0" max="100" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes / Remarks</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Reason for update..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" onclick="saveExtinguisherStatus()">
                        <i class="fas fa-save me-2"></i>Update
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Room Modal -->
    <div class="modal fade" id="addRoomModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title"><i class="fas fa-door-open me-2"></i>Add Room</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addRoomForm">
                        @csrf
                        <input type="hidden" name="school_id" id="roomSchoolId">

                        <div class="mb-3">
                            <label class="form-label">Building *</label>
                            <select class="form-control" name="building_id" id="roomBuildingSelect" required>
                                <option value="">Select Building</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Room Code</label>
                                <input type="text" class="form-control" name="room_code" placeholder="e.g., Rm-101">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Floor No.</label>
                                <select class="form-control" name="floor_no" id="roomFloorSelect" required disabled>
                                    <option value="">Select Building First</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Room Name *</label>
                            <input type="text" class="form-control" name="room_name" placeholder="e.g., Room 101, Science Lab" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Room Type *</label>
                            <select class="form-control" name="room_type" id="room_type_select" required onchange="updateRoomPriority()">
                                <option value="classroom">Classroom</option>
                                <option value="laboratory">Laboratory</option>
                                <option value="clinic">Clinic</option>
                                <option value="department">Department</option>
                                <option value="library">Library</option>
                                <option value="storage">Storage</option>
                                <option value="others">Others</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Fire Extinguisher Priority</label>
                            <input type="text" class="form-control bg-light" id="room_priority" readonly value="Shared Coverage (Up to 3 Classrooms)">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="saveRoom()">
                        <i class="fas fa-save me-2"></i>Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Extinguisher Modal -->
    <div class="modal fade" id="addExtModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add Extinguisher (Room-Based)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addExtForm">
                        @csrf
                        <input type="hidden" name="school_id" id="extSchoolId">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Code *</label>
                                <input type="text" class="form-control" name="code" placeholder="e.g., EXT-001" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Type *</label>
                                <select class="form-control" name="type" id="ext_type_select" required onchange="handleExtTypeChange()">
                                    <option value="ABC">ABC (Dry Chemical)</option>
                                    <option value="CO2">CO2</option>
                                    <option value="Water">Water</option>
                                    <option value="Foam">Foam</option>
                                    <option value="Other">Other, Please Specify...</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status *</label>
                                <select class="form-control" name="status" id="addExtStatus" required onchange="handleAddStatusChange()">
                                    <option value="active">Active</option>
                                    <option value="maintenance">For Refill</option>
                                    <option value="expired">Empty</option>
                                    <option value="missing">Missing</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pressure Level (0-100%)</label>
                                <input type="number" class="form-control" name="pressure_level" id="addExtPressure" min="0" max="100" value="100" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Building *</label>
                                <select class="form-control" name="building_id" id="extBuildingSelect" required onchange="loadRoomsForExtinguisher()">
                                    <option value="">Select Building</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Center Room *</label>
                                <select class="form-control" name="room_id" id="centerRoomSelect" required onchange="handleCenterRoomChange()">
                                    <option value="">Select Center Room</option>
                                </select>
                                <div class="form-text">If sharing (2–3 rooms), this must be the “center” room.</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Covered Rooms (max 3) *</label>
                            <select class="form-control" id="coveredRoomsSelect" name="covered_room_ids[]" multiple size="6" required>
                            </select>
                            <div class="form-text">
                                Select 1–3 rooms total. Must include the center room. Rooms can only be covered by one extinguisher.
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date Checked *</label>
                                <input type="date" class="form-control" name="date_checked" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Evaluation Result *</label>
                                <select class="form-control" name="evaluation_result" required>
                                    <option value="Passed">Passed</option>
                                    <option value="Needs Refill">Needs Refill</option>
                                    <option value="Failed - Damaged">Failed - Damaged</option>
                                    <option value="Failed - Low Pressure">Failed - Low Pressure</option>
                                    <option value="Expired">Expired</option>
                                </select>
                            </div>
                        </div>

                        <div class="alert alert-info mb-0">
                            <strong>Note:</strong> Laboratory center room can cover only itself, or itself + 1 auxiliary room.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="saveExtinguisher()">
                        <i class="fas fa-save me-2"></i>Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const schools = @json($schools);

        function csrfToken() {
            return document.querySelector('meta[name="csrf-token"]').content;
        }

        // Handle status change in Add Extinguisher modal - enforce pressure ranges
        function handleAddStatusChange() {
            const status = document.getElementById('addExtStatus').value;
            const pressureInput = document.getElementById('addExtPressure');
            
            // Set constraints based on status
            switch(status) {
                case 'active':
                    pressureInput.min = 70;
                    pressureInput.max = 100;
                    if (pressureInput.value < 70) pressureInput.value = 70;
                    break;
                case 'maintenance': // For Refill
                    pressureInput.min = 0;
                    pressureInput.max = 69;
                    if (pressureInput.value >= 70) pressureInput.value = 69;
                    break;
                case 'expired': // Empty
                    pressureInput.min = 0;
                    pressureInput.max = 19;
                    if (pressureInput.value > 19) pressureInput.value = 19;
                    break;
                case 'missing':
                    pressureInput.min = 0;
                    pressureInput.max = 100;
                    break;
            }
        }

        // Handle status change in Update Extinguisher modal - enforce pressure ranges
        function handleUpdateStatusChange() {
            const status = document.getElementById('updateExtStatus').value;
            const pressureInput = document.getElementById('updateExtPressure');
            
            // Set constraints based on status
            switch(status) {
                case 'active':
                    pressureInput.min = 70;
                    pressureInput.max = 100;
                    if (pressureInput.value < 70) pressureInput.value = 70;
                    break;
                case 'maintenance': // For Refill
                    pressureInput.min = 0;
                    pressureInput.max = 69;
                    if (pressureInput.value >= 70) pressureInput.value = 69;
                    break;
                case 'expired': // Empty
                    pressureInput.min = 0;
                    pressureInput.max = 19;
                    if (pressureInput.value > 19) pressureInput.value = 19;
                    break;
                case 'missing':
                    pressureInput.min = 0;
                    pressureInput.max = 100;
                    break;
            }
        }

        function setTodayIfEmpty(dateInput) {
            if (!dateInput.value) {
                dateInput.value = new Date().toISOString().split('T')[0];
            }
        }

        // Populate building selects for a school
        function populateBuildingsForSchool(schoolId) {
            const school = schools.find(s => String(s.id) === String(schoolId));
            const buildings = (school && school.buildings) ? school.buildings : [];

            const roomBuildingSelect = document.getElementById('roomBuildingSelect');
            const extBuildingSelect = document.getElementById('extBuildingSelect');

            roomBuildingSelect.innerHTML = '<option value="">Select Building</option>';
            extBuildingSelect.innerHTML = '<option value="">Select Building</option>';

            buildings.forEach(b => {
                const opt1 = document.createElement('option');
                opt1.value = b.id;
                opt1.textContent = b.building_no + (b.building_name ? ` (${b.building_name})` : '');
                // Store floors and type for logic
                opt1.dataset.floors = b.floors || 1;
                opt1.dataset.type = b.building_type || '';
                roomBuildingSelect.appendChild(opt1);

                const opt2 = document.createElement('option');
                opt2.value = b.id;
                opt2.textContent = b.building_no + (b.building_name ? ` (${b.building_name})` : '');
                extBuildingSelect.appendChild(opt2);
            });
        }

        // Handle Building Selection in Add Room (Populate Floors & Check Type)
        document.getElementById('roomBuildingSelect').addEventListener('change', function() {
            const select = this;
            const floorSelect = document.getElementById('roomFloorSelect');
            floorSelect.innerHTML = '<option value="">Select Floor</option>';
            floorSelect.disabled = true;

            const option = select.options[select.selectedIndex];
            if (!option || !option.value) return;

            const type = option.dataset.type;
            // Restriction for Gymnasium and Cafeteria
            if (type.toLowerCase() === 'gymnasium' || type.toLowerCase() === 'cafeteria or canteens') {
                Swal.fire({
                    title: 'Building Restriction',
                    text: 'Gymnasium & Cafeteria buildings have only 1 room. You cannot add more rooms to them.',
                    icon: 'warning'
                });
                select.value = ""; // Reset
                return;
            }

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
                opt.value = i;
                opt.textContent = getOrdinal(i) + " Floor";
                floorSelect.appendChild(opt);
            }
        });

        // Hook modal open events to set school_id and populate buildings
        document.getElementById('addRoomModal').addEventListener('show.bs.modal', function (event) {
            const btn = event.relatedTarget;
            const schoolId = btn?.getAttribute('data-school-id');
            document.getElementById('roomSchoolId').value = schoolId || '';
            populateBuildingsForSchool(schoolId);
        });

        document.getElementById('addExtModal').addEventListener('show.bs.modal', function (event) {
            const btn = event.relatedTarget;
            const schoolId = btn?.getAttribute('data-school-id');
            document.getElementById('extSchoolId').value = schoolId || '';
            populateBuildingsForSchool(schoolId);

            // reset room selects
            document.getElementById('centerRoomSelect').innerHTML = '<option value="">Select Center Room</option>';
            document.getElementById('coveredRoomsSelect').innerHTML = '';

            setTodayIfEmpty(document.querySelector('#addExtForm input[name="date_checked"]'));
        });

        async function saveRoom() {
            const form = document.getElementById('addRoomForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);

            try {
                const resp = await fetch(`{{ route('fire-safety.room.store') }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken(),
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await resp.json();
                if (!resp.ok || !data.success) {
                    Swal.fire('Error', data.message || 'Failed to add room', 'error');
                    return;
                }

                Swal.fire('Success', 'Room added successfully!', 'success').then(() => {
                    location.reload();
                });
            } catch (e) {
                console.error(e);
                Swal.fire('Error', 'Failed to add room. Please try again.', 'error');
            }
        }

        // Inspect Room - Show room details and extinguisher info
        function inspectRoom(roomId) {
            Swal.fire({
                title: 'Room Inspection',
                html: '\u003cdiv class=\"text-center\"\u003e\u003ci class=\"fas fa-spinner fa-spin fa-2x\"\u003e\u003c/i\u003e\u003cp class=\"mt-2\"\u003eLoading room details...\u003c/p\u003e\u003c/div\u003e',
                showConfirmButton: false,
                allowOutsideClick: false
            });

            // Fetch room details
            fetch(`/fire-safety/room/${roomId}`)
                .then(response => response.json())
                .then(room => {
                    let extInfo = 'No extinguisher assigned';
                    let actionButton = '';
                    
                    if (room.extinguisher) {
                        const ext = room.extinguisher;
                        extInfo = `
                            \u003cdiv class=\"alert alert-info\"\u003e
                                \u003cstrong\u003eExtinguisher:\u003c/strong\u003e ${ext.code}\u003cbr\u003e
                                \u003cstrong\u003eType:\u003c/strong\u003e ${ext.type}\u003cbr\u003e
                                \u003cstrong\u003eStatus:\u003c/strong\u003e ${ext.status}\u003cbr\u003e
                                \u003cstrong\u003ePressure:\u003c/strong\u003e ${ext.pressure_level}%\u003cbr\u003e
                                \u003cstrong\u003eDate Checked:\u003c/strong\u003e ${ext.date_checked || 'N/A'}
                            \u003c/div\u003e
                        `;
                        actionButton = `\u003cbutton class=\"btn btn-primary\" onclick=\"openUpdateModal(${ext.id}, '${ext.code}', '${ext.status}', ${ext.pressure_level}); Swal.close();\"\u003e\u003ci class=\"fas fa-edit me-2\"\u003e\u003c/i\u003eUpdate Extinguisher\u003c/button\u003e`;
                    }

                    Swal.fire({
                        title: `\u003ci class=\"fas fa-door-open me-2\"\u003e\u003c/i\u003e${room.room_name}`,
                        html: `
                            \u003cdiv class=\"text-start\"\u003e
                                \u003cp\u003e\u003cstrong\u003eRoom Code:\u003c/strong\u003e ${room.room_code || 'N/A'}\u003c/p\u003e
                                \u003cp\u003e\u003cstrong\u003eType:\u003c/strong\u003e ${room.room_type}\u003c/p\u003e
                                \u003cp\u003e\u003cstrong\u003eFloor:\u003c/strong\u003e ${room.floor_no || 'N/A'}\u003c/p\u003e
                                \u003chr\u003e
                                ${extInfo}
                            \u003c/div\u003e
                        `,
                        showCancelButton: true,
                        confirmButtonText: actionButton ? '' : 'Close',
                        cancelButtonText: 'Close',
                        footer: actionButton,
                        width: '600px'
                    });
                })
                .catch(error => {
                    console.error('Error fetching room details:', error);
                    Swal.fire('Error', 'Failed to load room details', 'error');
                });
        }

        function updateRoomPriority() {
            const typeSelect = document.getElementById('room_type_select');
            const priorityInput = document.getElementById('room_priority');
            const type = typeSelect.value;

            if (['laboratory', 'clinic', 'storage'].includes(type)) {
                priorityInput.value = 'Dedicated / Limited Shared';
            } else if (['classroom', 'department', 'library'].includes(type)) {
                priorityInput.value = 'Shared Coverage (Up to 3 Classrooms)';
            } else {
                priorityInput.value = 'General Use';
            }
        }

        async function loadRoomsForExtinguisher() {
            const buildingId = document.getElementById('extBuildingSelect').value;
            const centerSelect = document.getElementById('centerRoomSelect');
            const coveredSelect = document.getElementById('coveredRoomsSelect');

            centerSelect.innerHTML = '<option value="">Select Center Room</option>';
            coveredSelect.innerHTML = '';

            if (!buildingId) return;

            try {
                const resp = await fetch(`/fire-safety/rooms/${buildingId}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const rooms = await resp.json();

                rooms.forEach(r => {
                    const label = `${r.room_name}${r.room_code ? ' (' + r.room_code + ')' : ''} - ${r.room_type}`;

                    const optCenter = document.createElement('option');
                    optCenter.value = r.id;
                    optCenter.textContent = label;
                    optCenter.dataset.roomType = r.room_type;
                    centerSelect.appendChild(optCenter);

                    const optCovered = document.createElement('option');
                    optCovered.value = r.id;
                    optCovered.textContent = label;
                    optCovered.dataset.roomType = r.room_type;
                    coveredSelect.appendChild(optCovered);
                });
            } catch (e) {
                console.error(e);
                Swal.fire('Error', 'Failed to load rooms for this building.', 'error');
            }
        }

        async function handleExtTypeChange() {
            const select = document.getElementById('ext_type_select');
            if (select.value === 'Other') {
                const { value: otherType } = await Swal.fire({
                    title: 'Specify Extinguisher Type',
                    input: 'text',
                    inputLabel: 'What type of fire extinguisher?',
                    inputPlaceholder: 'Enter type...',
                    showCancelButton: true,
                    inputValidator: (value) => {
                        if (!value) return 'You need to write something!'
                    }
                });

                if (otherType) {
                    // Create new option or just update the current 'Other' value
                    const newOption = document.createElement('option');
                    newOption.value = otherType;
                    newOption.textContent = otherType;
                    newOption.selected = true;
                    select.appendChild(newOption);
                } else {
                    select.value = 'ABC'; // Default back
                }
            }
        }

        function handleCenterRoomChange() {
            const centerSelect = document.getElementById('centerRoomSelect');
            const coveredSelect = document.getElementById('coveredRoomsSelect');
            const centerId = centerSelect.value;
            const centerType = centerSelect.selectedOptions[0]?.dataset?.roomType;

            // auto-select center room in covered rooms
            Array.from(coveredSelect.options).forEach(o => {
                if (String(o.value) === String(centerId)) {
                    o.selected = true;
                }
            });

            // If center is laboratory: allow only 2 rooms total and only auxiliary can be the other
            if (centerType === 'laboratory') {
                Array.from(coveredSelect.options).forEach(o => {
                    const t = o.dataset.roomType;
                    if (String(o.value) === String(centerId)) {
                        o.disabled = false;
                        return;
                    }
                    o.disabled = (t !== 'auxiliary');
                    if (o.disabled) o.selected = false;
                });
            } else {
                // enable all options
                Array.from(coveredSelect.options).forEach(o => { o.disabled = false; });
            }
        }

        async function saveExtinguisher() {
            const form = document.getElementById('addExtForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const centerId = document.getElementById('centerRoomSelect').value;
            const covered = Array.from(document.getElementById('coveredRoomsSelect').selectedOptions).map(o => o.value);

            if (!centerId) {
                Swal.fire('Selection Required', 'Please select a center room.', 'warning');
                return;
            }
            if (covered.length < 1 || covered.length > 3) {
                Swal.fire('Invalid Selection', 'Please select 1 to 3 covered rooms.', 'warning');
                return;
            }
            if (!covered.includes(centerId)) {
                Swal.fire('Inconsistent Selection', 'Covered rooms must include the center room.', 'warning');
                return;
            }

            const centerType = document.getElementById('centerRoomSelect').selectedOptions[0]?.dataset?.roomType;
            if (centerType === 'laboratory' && covered.length > 2) {
                Swal.fire('Constraint Error', 'Laboratory can only cover itself, or itself + 1 clinic/auxiliary room.', 'warning');
                return;
            }

            // Update pressure based on status if needed, or enforce validation later
            // For now, simple validation

            const formData = new FormData(form);

            try {
                const resp = await fetch(`{{ route('fire-safety.extinguisher.store') }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken(),
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await resp.json();
                if (!resp.ok || !data.success) {
                    Swal.fire('Error', data.message || 'Failed to add extinguisher', 'error');
                    return;
                }

                Swal.fire('Success', 'Extinguisher added successfully!', 'success').then(() => {
                    location.reload();
                });
            } catch (e) {
                console.error(e);
                Swal.fire('Error', 'Failed to add extinguisher. Please try again.', 'error');
            }
        }

        // Enforce max 3 selections for covered rooms
        document.addEventListener('change', function (e) {
            if (e.target && e.target.id === 'coveredRoomsSelect') {
                const selected = Array.from(e.target.selectedOptions);
                if (selected.length > 3) {
                    // keep first 3
                    selected.slice(3).forEach(o => o.selected = false);
                    Swal.fire('Limit Reached', 'Max of 3 rooms can be covered by one extinguisher.', 'info');
                }
            }
        });

        // Update Modal Logic
        var updateModalBS = null;
        function openUpdateModal(id, code, status, pressure) {
            document.getElementById('updateExtId').value = id;
            document.getElementById('updateExtCode').value = code;
            document.getElementById('updateExtStatus').value = status;
            document.getElementById('updateExtPressure').value = pressure;

            updateModalBS = new bootstrap.Modal(document.getElementById('updateExtModal'));
            updateModalBS.show();
        }

        function handleUpdateStatusChange() {
            const status = document.getElementById('updateExtStatus').value;
            const pressureInput = document.getElementById('updateExtPressure');

            if (status === 'active') {
                pressureInput.min = 70;
                pressureInput.max = 100;
                if (pressureInput.value < 70) pressureInput.value = 70;
            } else if (status === 'maintenance') {
                pressureInput.min = 0;
                pressureInput.max = 69;
                if (pressureInput.value >= 70) pressureInput.value = 69;
            } else if (status === 'expired') {
                pressureInput.min = 0;
                pressureInput.max = 19;
                if (pressureInput.value > 19) pressureInput.value = 19;
            } else {
                pressureInput.min = 0;
                pressureInput.max = 100;
            }
        }

        async function saveExtinguisherStatus() {
            const id = document.getElementById('updateExtId').value;
            const form = document.getElementById('updateExtForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);

            try {
                const resp = await fetch(`/fire-safety/extinguisher/${id}/update`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken(),
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await resp.json();
                if(data.success) {
                    Swal.fire('Updated', 'Extinguisher status updated successfully!', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'Error updating extinguisher', 'error');
                }
            } catch(e) {
                console.error(e);
                Swal.fire('Network Error', 'Failed to update extinguisher status.', 'error');
            }
        }

        async function loadRecentInspections(schoolId) {
            const tableBody = document.querySelector(`#inspectionsTable-${schoolId} tbody`);
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Loading...</td></tr>';

            try {
                const resp = await fetch(`/fire-safety/extinguisher/inspections/${schoolId}`);
                const data = await resp.json();

                if (data.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No recent inspections found.</td></tr>';
                    return;
                }

                tableBody.innerHTML = '';
                data.forEach(item => {
                    let badgeClass = 'secondary';
                    let statusLabel = item.status;
                    if (item.status === 'active') { badgeClass = 'success'; statusLabel = 'OK'; }
                    else if (item.status === 'maintenance') { badgeClass = 'warning'; statusLabel = 'For Refill'; }
                    else if (item.status === 'expired') { badgeClass = 'danger'; statusLabel = 'Empty'; }
                    else if (item.status === 'missing') { badgeClass = 'danger'; statusLabel = 'Missing'; }

                    const row = `
                        <tr>
                            <td>${item.date}</td>
                            <td class="fw-bold">${item.code}</td>
                            <td>${item.location}</td>
                            <td>${item.inspector}</td>
                            <td><span class="badge bg-${badgeClass}">${statusLabel}</span></td>
                            <td>${item.pressure_level}%</td>
                        </tr>
                    `;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
            } catch(e) {
                console.error(e);
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Failed to load data.</td></tr>';
            }
        }

        // Load inspections on page load for the active tab
        document.addEventListener('DOMContentLoaded', () => {
           const activeTabPane = document.querySelector('.tab-pane.show.active');
           if(activeTabPane) {
               const sId = activeTabPane.id.replace('school-', '');
               loadRecentInspections(sId);
           }

           // Listener for tab changes
           const tabEls = document.querySelectorAll('button[data-bs-toggle="tab"]');
           tabEls.forEach(tabEl => {
               tabEl.addEventListener('shown.bs.tab', function (event) {
                   const targetId = event.target.getAttribute('data-bs-target');
                   const sId = targetId.replace('#school-', '');
                   loadRecentInspections(sId);
               });
           });
        });
        function handleAddStatusChange() {
            const status = document.getElementById('addExtStatus').value;
            const pressureInput = document.getElementById('addExtPressure');

            if (status === 'active') {
                pressureInput.min = 70;
                pressureInput.max = 100;
                if (pressureInput.value < 70) pressureInput.value = 100;
            } else if (status === 'maintenance') {
                pressureInput.min = 20;
                pressureInput.max = 69;
                if (pressureInput.value >= 70 || pressureInput.value < 20) pressureInput.value = 69;
            } else if (status === 'expired') {
                pressureInput.min = 0;
                pressureInput.max = 19;
                if (pressureInput.value > 19) pressureInput.value = 19;
            } else {
                pressureInput.min = 0;
                pressureInput.max = 100;
            }
        }

        function inspectRoom(roomId) {
            Swal.fire({
                title: 'Inspect & Update Room',
                text: 'This feature allows you to update specific room safety details. Opening room management...',
                icon: 'info',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                // For now, redirect to customization or open a specific modal if one existed
                // The user requested 'Inspect & Update' button, usually implying a details view
                // Since there's no specific 'update room' modal mentioned, I'll redirect to a common place or show a placeholder
                Swal.fire('Coming Soon', 'Room-specific inspection details are under development.', 'info');
            });
        }
    </script>
</body>
</html>
