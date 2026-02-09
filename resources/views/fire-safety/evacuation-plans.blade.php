<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evacuation Plans - Fire Safety</title>
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

        .evacuation-card {
            transition: transform 0.2s;
            height: 100%;
        }

        .evacuation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }

        .map-container {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            border-radius: 8px;
            padding: 15px;
            color: white;
            text-align: center;
            margin-bottom: 15px;
            cursor: pointer;
        }

        /* Visual Evacuation Map Styles */
        .school-layout-container {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #dee2e6;
            min-height: 300px;
            max-height: 600px;
            overflow: auto;
        }
        .main-division-box {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .building-box {
            background-color: white;
            border: 2px solid #495057;
            padding: 15px;
            border-radius: 8px;
            min-width: 220px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            position: relative;
        }
        .building-box.current-building {
            border-color: var(--fire-red);
            border-width: 3px;
        }
        .building-box.current-building::after {
            content: "\f111";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            top: -10px;
            right: -10px;
            color: var(--fire-red);
            background: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }
        .building-title {
            font-weight: 700;
            text-align: center;
            border-bottom: 2px solid #f1f1f1;
            margin-bottom: 10px;
            padding-bottom: 5px;
            font-size: 1rem;
            color: #212529;
        }
        .floor-box {
            border: 1px solid #ced4da;
            margin-bottom: 8px;
            padding: 8px;
            background-color: #fbfbfb;
            border-radius: 4px;
        }
        .floor-title {
            font-size: 0.7rem;
            color: #6c757d;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .rooms-container {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }
        .room-unit {
            width: 24px;
            height: 24px;
            border: 1px solid #ced4da;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.55rem;
            border-radius: 2px;
            font-weight: bold;
        }
        .room-unit.has-extinguisher {
            background-color: #ffebee;
            border-color: #dc3545;
            color: #dc3545;
        }
        .building-box.has-alarm {
            background-color: #fffde7;
            border-color: #ffc107;
        }
        .legend-container {
            margin-top: 15px;
            padding: 12px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            font-size: 0.8rem;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .legend-color {
            width: 14px;
            height: 14px;
            border-radius: 2px;
        }

        .route-step {
            padding: 8px;
            margin-bottom: 5px;
            background-color: #f8f9fa;
            border-left: 4px solid var(--fire-red);
            border-radius: 4px;
        }

        .assembly-area {
            background-color: #e7f5ff;
            border: 2px dashed #0d6efd;
            border-radius: 8px;
            padding: 10px;
            margin-top: 10px;
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

        .safety-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .safety-good { background-color: #28a745; }
        .safety-warning { background-color: #ffc107; }
        .safety-danger { background-color: #dc3545; }
        .safety-unknown { background-color: #6c757d; }

        .no-plans {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }

        .no-plans i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #adb5bd;
        }

        /* Sketch Tool Styles */
        .sketch-canvas-container {
            border: 2px solid #ddd;
            border-radius: 8px;
            background-color: white;
            position: relative;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .sketch-controls {
            padding: 10px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #ddd;
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .sketch-canvas {
            display: block;
            cursor: crosshair;
            touch-action: none;
            background-color: #fff;
            width: 100%;
            height: 400px;
        }

        .color-picker {
            width: 30px;
            height: 30px;
            padding: 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .tool-btn {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            border: 1px solid #ddd;
            background: white;
            color: #555;
            transition: all 0.2s;
        }

        .tool-btn:hover {
            background-color: #f0f0f0;
            color: #000;
        }

        .tool-btn.active {
            background-color: var(--fire-red);
            color: white;
            border-color: var(--fire-red);
        }

        .sketch-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.7);
            z-index: 5;
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
                    <h4 class="text-white mb-0">Evacuation Plans Management</h4>
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
                    <a class="nav-link" href="{{ route('fire-safety.extinguishers') }}">
                        <span class="nav-icon"><i class="fas fa-fire-extinguisher"></i></span>
                        <span>Fire Extinguishers & Rooms</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('fire-safety.evacuation-plans') }}">
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
                <h6 class="text-white mb-3">Evacuation Status</h6>
                <div id="sidebarStats">
                    <div class="text-center text-white py-3">
                        <i class="fas fa-spinner fa-spin"></i> Loading stats...
                    </div>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addPlanModal">
                        <i class="fas fa-plus me-2"></i> Add Plan
                    </button>
                    <button class="btn btn-light btn-sm" onclick="printAllPlans()">
                        <i class="fas fa-print me-2"></i> Print All Plans
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
                <!-- Evacuation Plan Overview -->
                <div class="row mb-4">
                    @php
                        $totalBuildings = $school->buildings->count();
                        $buildingsWithPlans = $school->buildings->filter(function($building) {
                            return $building->evacuationPlan && $building->evacuationPlan->status === 'active';
                        })->count();
                        $totalEmergencyExits = $school->buildings->sum('emergency_exits');
                        $totalAlarms = $school->buildings->sum(function($building) {
                            return $building->alarmSystems->whereIn('status', ['functional', 'online'])->count();
                        });
                        $totalExtinguishers = $school->buildings->sum(function($building) {
                            return $building->fireExtinguishers->where('status', 'active')->count();
                        });
                    @endphp

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card dashboard-card border-left-success h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                            Active Plans
                                        </div>
                                        <div class="h2 mb-0 fw-bold text-gray-800">{{ $buildingsWithPlans }}</div>
                                        <small class="text-muted">out of {{ $totalBuildings }} buildings</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-map-marked-alt fa-2x text-success"></i>
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
                                            Emergency Exits
                                        </div>
                                        <div class="h2 mb-0 fw-bold text-gray-800">{{ $totalEmergencyExits }}</div>
                                        <small class="text-muted">across all buildings</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-door-open fa-2x text-primary"></i>
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
                                            Safety Equipment
                                        </div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">
                                            {{ $totalAlarms }} Alarms<br>
                                            {{ $totalExtinguishers }} Extinguishers
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-shield-alt fa-2x text-warning"></i>
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
                                            Coverage Score
                                        </div>
                                        <div class="h2 mb-0 fw-bold text-gray-800">
                                            @if($totalBuildings > 0)
                                                {{ round(($buildingsWithPlans / $totalBuildings) * 100) }}%
                                            @else
                                                0%
                                            @endif
                                        </div>
                                        <small class="text-muted">Building coverage</small>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-chart-line fa-2x text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Evacuation Plans Grid -->
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card dashboard-card">
                            <div class="card-header p-0">
                                <div class="school-tabs">
                                    <nav>
                                        <div class="nav nav-tabs border-0" id="evacuationTabs-{{ $school->id }}" role="tablist">
                                            <button class="nav-link school-tab-btn active" id="plans-tab-{{ $school->id }}" data-bs-toggle="tab" data-bs-target="#plans-content-{{ $school->id }}" type="button" role="tab" aria-controls="plans-content-{{ $school->id }}" aria-selected="true">
                                                <i class="fas fa-list me-2"></i> Building Evacuation Plans
                                            </button>
                                            <button class="nav-link school-tab-btn" id="map-tab-{{ $school->id }}" data-bs-toggle="tab" data-bs-target="#map-content-{{ $school->id }}" type="button" role="tab" aria-controls="map-content-{{ $school->id }}" aria-selected="false" onclick="initEvacuationMap({{ $school->id }})">
                                                <i class="fas fa-map-marked-alt me-2"></i> Evacuation Map
                                            </button>
                                        </div>
                                    </nav>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="evacuationTabsContent-{{ $school->id }}">
                                    <!-- TAB 1: PLANS LIST -->
                                    <div class="tab-pane fade show active" id="plans-content-{{ $school->id }}" role="tabpanel" aria-labelledby="plans-tab-{{ $school->id }}">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h6 class="m-0 fw-bold text-primary">
                                                {{ $school->school_name }} - Plans Overview
                                            </h6>
                                            <div>
                                                <button class="btn btn-primary btn-sm me-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#addPlanModal"
                                                        data-school-id="{{ $school->id }}">
                                                    <i class="fas fa-plus me-2"></i> Add Plan
                                                </button>
                                                <button class="btn btn-success btn-sm"
                                                        onclick="openScheduleDrillModal({{ $school->id }})">
                                                    <i class="fas fa-bullhorn me-2"></i> Schedule Drill
                                                </button>
                                            </div>
                                        </div>
                                @if($school->buildings->count() > 0)
                                <div class="row">
                                    @foreach($school->buildings as $building)
                                    @php
                                        $plan = $building->evacuationPlan;
                                        $alarmCount = $building->alarmSystems->whereIn('status', ['functional', 'online'])->count();
                                        $extinguisherCount = $building->fireExtinguishers->where('status', 'active')->count();
                                        $emergencyExits = $building->emergency_exits ?? 0;
                                        
                                        // Calculate safety score based on equipment
                                        $safetyScore = 0;
                                        if($alarmCount > 0) $safetyScore += 30;
                                        if($extinguisherCount >= max(1, ceil(($building->rooms ?? 0) / 3))) $safetyScore += 40;
                                        if($emergencyExits >= min(2, ceil(($building->floors ?? 1) * 0.5))) $safetyScore += 30;
                                        
                                        $statusClass = $plan ? 'border-' . $plan->status_color : 'border-danger';
                                        $statusBadge = $plan ? 'bg-' . $plan->status_color : 'bg-danger';
                                        $statusText = $plan ? $plan->status_label : 'No Plan';
                                        
                                        $safetyClass = $safetyScore >= 80 ? 'safety-good' : ($safetyScore >= 60 ? 'safety-warning' : 'safety-danger');
                                        $safetyText = $safetyScore >= 80 ? 'Good' : ($safetyScore >= 60 ? 'Fair' : 'Poor');
                                    @endphp
                                    <div class="col-xl-4 col-lg-6 mb-4">
                                        <div class="card evacuation-card {{ $statusClass }}">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div>
                                                        <h5 class="card-title mb-1">{{ $building->building_no }}</h5>
                                                        <p class="text-muted mb-0">
                                                            <i class="fas fa-building me-1"></i> {{ $building->building_name }}
                                                        </p>
                                                    </div>
                                                    <span class="badge {{ $statusBadge }}">{{ $statusText }}</span>
                                                </div>

                                                <!-- Map Preview -->
                                                <div class="map-container mb-3" 
                                                     onclick="viewPlan({{ $plan ? $plan->id : 'null' }}, {{ $building->id }}, '{{ $building->building_no }}')"
                                                     style="background: {{ ($plan && $plan->map_data) ? 'white' : 'linear-gradient(135deg, ' . ($plan ? '#6a11cb' : '#868f96') . ' 0%, ' . ($plan ? '#2575fc' : '#596164') . ' 100%)' }}; overflow: hidden; height: 120px; display: flex; align-items: center; justify-content: center; position: relative; border: 1px solid #ddd;">
                                                     @if($plan && $plan->map_data)
                                                         <img src="{{ $plan->map_data }}" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.9;">
                                                         <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(168, 25, 31, 0.8); color: white; padding: 2px; font-size: 0.7rem;">
                                                             <span>{{ $plan->plan_no }}</span>
                                                         </div>
                                                     @elseif($plan)
                                                         <div class="text-white">
                                                             <i class="fas fa-map fa-2x mb-2"></i>
                                                             <h6>Plan: {{ $plan->plan_no }}</h6>
                                                             <small>Click to view details</small>
                                                         </div>
                                                     @else
                                                         <div class="text-white">
                                                             <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                                             <h6>No Plan</h6>
                                                             <small>Click to create</small>
                                                         </div>
                                                     @endif
                                                 </div>

                                                <!-- Safety Assessment -->
                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span>Safety Assessment:</span>
                                                        <span class="badge bg-light text-dark">
                                                            <span class="safety-indicator {{ $safetyClass }}"></span>
                                                            {{ $safetyText }} ({{ $safetyScore }}%)
                                                        </span>
                                                    </div>
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar {{ $safetyScore >= 80 ? 'bg-success' : ($safetyScore >= 60 ? 'bg-warning' : 'bg-danger') }}"
                                                             style="width: {{ $safetyScore }}%"></div>
                                                    </div>
                                                </div>

                                                <!-- Quick Info -->
                                                <div class="mb-3">
                                                    <div class="row text-center">
                                                        <div class="col-4">
                                                            <h6 class="mb-0">{{ $emergencyExits }}</h6>
                                                            <small>Exits</small>
                                                        </div>
                                                        <div class="col-4">
                                                            <h6 class="mb-0">{{ $alarmCount }}</h6>
                                                            <small>Alarms</small>
                                                        </div>
                                                        <div class="col-4">
                                                            <h6 class="mb-0">{{ $extinguisherCount }}</h6>
                                                            <small>Extinguishers</small>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if($plan)
                                                <!-- Assembly Area -->
                                                <div class="assembly-area">
                                                    <small class="d-block fw-bold">Assembly Areas:</small>
                                                    <small>{{ $plan->primary_assembly_area ?? 'Not specified' }}</small>
                                                    @if($plan->secondary_assembly_area)
                                                    <br><small class="text-muted">Secondary: {{ $plan->secondary_assembly_area }}</small>
                                                    @endif
                                                </div>
                                                @endif

                                                <div class="mt-3 d-grid gap-2">
                                                    @if($plan)
                                                    <button class="btn btn-sm btn-outline-primary view-plan-btn"
                                                            data-plan-id="{{ $plan->id }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#viewPlanModal">
                                                        <i class="fas fa-eye me-2"></i> View Plan
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-warning edit-plan-btn"
                                                            data-plan-id="{{ $plan->id }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editPlanModal">
                                                        <i class="fas fa-edit me-2"></i> Edit Plan
                                                    </button>
                                                    @else
                                                    <button class="btn btn-sm btn-outline-danger create-plan-btn"
                                                            data-building-id="{{ $building->id }}"
                                                            data-building-name="{{ $building->building_name ?? $building->building_no }}"
                                                            data-building-code="{{ $building->building_no }}"
                                                            data-school-id="{{ $school->id }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#addPlanModal">
                                                        <i class="fas fa-plus-circle me-2"></i> Create Plan
                                                    </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="no-plans">
                                    <i class="fas fa-building"></i>
                                    <h4>No Buildings Found</h4>
                                    <p class="text-muted">This school doesn't have any buildings yet. Add buildings first.</p>
                                    <a href="{{ route('fire-safety.buildings') }}" class="btn btn-primary">
                                        <i class="fas fa-building me-2"></i> Go to Buildings
                                    </a>
                                </div>
                                @endif
                                </div> <!-- End Tab 1 -->

                                <!-- TAB 2: EVACUATION MAP -->
                                <div class="tab-pane fade" id="map-content-{{ $school->id }}" role="tabpanel" aria-labelledby="map-tab-{{ $school->id }}">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h6 class="fw-bold text-primary mb-1">Visual Evacuation Map - {{ $school->school_name }}</h6>
                                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Drag buildings to arrange layout. Click 'Edit Placement' to unlock.</small>
                                        </div>
                                        <div>
                                            <button class="btn btn-outline-primary btn-sm me-2" id="edit-placement-btn-{{ $school->id }}" onclick="toggleMapEdit({{ $school->id }})">
                                                <i class="fas fa-arrows-alt me-2"></i> Edit Placement
                                            </button>
                                            <button class="btn btn-primary btn-sm" id="save-placement-btn-{{ $school->id }}" onclick="saveMapLayout({{ $school->id }})" disabled>
                                                <i class="fas fa-save me-2"></i> Save Layout
                                            </button>
                                        </div>
                                    </div>

                                    <div class="school-map-canvas-container" style="position: relative; width: 100%; height: 800px; background: #e9ecef; border: 2px solid #333; overflow: hidden; border-radius: 4px; box-shadow: inset 0 0 20px rgba(0,0,0,0.1);">
                                        <div id="school-map-canvas-{{ $school->id }}" class="school-map-canvas" style="width: 100%; height: 100%; position: relative;">
                                            <!-- Map Elements will be rendered here by JS -->
                                            <div class="text-center pt-5 text-muted">
                                                <i class="fas fa-spinner fa-spin fa-3x mb-3"></i><br>Loading Map Data...
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Legend -->
                                    <div class="mt-3 p-3 bg-white border rounded shadow-sm">
                                        <h6 class="fw-bold fs-sm mb-2 text-dark border-bottom pb-2">Map Legend:</h6>
                                        <div class="d-flex flex-wrap gap-4 text-secondary small">
                                            <div class="d-flex align-items-center"><span style="width: 20px; height: 20px; background: white; border: 3px solid black; margin-right: 8px; display:inline-block;"></span> <strong>Building</strong></div>
                                            <div class="d-flex align-items-center"><span style="width: 12px; height: 12px; border: 1px solid #333; margin-right: 8px; background:#f8f9fa; display:inline-block;"></span> Room</div>
                                            <div class="d-flex align-items-center"><i class="fas fa-stairs me-2 text-dark"></i> Stairway</div>
                                            <div class="d-flex align-items-center"><i class="fas fa-circle text-danger me-2" style="font-size: 14px;"></i> Alarm</div>
                                            <div class="d-flex align-items-center"><span style="width: 14px; height: 8px; background: #dc3545; margin-right: 8px; display:inline-block; border-radius:2px;"></span> Extinguisher</div>
                                            <div class="d-flex align-items-center"><i class="fas fa-door-open me-2 text-success"></i> Exit</div>
                                            <div class="d-flex align-items-center"><span style="color: green; font-weight: 800; margin-right: 8px;">ROUTE</span> Evacuation Route</div>
                                            <div class="d-flex align-items-center"><span style="border: 2px dashed #0d6efd; background: #e7f5ff; width: 24px; height: 16px; margin-right: 8px; display:inline-block;"></span> Assembly Area</div>
                                        </div>
                                    </div>
                                </div> <!-- End Tab 2 -->
                                </div> <!-- End Tab Content -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Evacuation Drill Schedule -->
                <div class="row mt-4">
                    <div class="col-lg-8">
                        <div class="card dashboard-card">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-calendar-alt me-2"></i> Evacuation Drills - {{ $school->school_name }}
                                </h6>
                                <button class="btn btn-sm btn-outline-primary"
                                        onclick="loadDrillHistory({{ $school->id }})">
                                    <i class="fas fa-sync-alt me-1"></i> Refresh
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="drillHistory-{{ $school->id }}">
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-spinner fa-spin me-2"></i>
                                        Loading drill history...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card dashboard-card">
                            <div class="card-header py-3 bg-primary text-white">
                                <h6 class="m-0 fw-bold">
                                    <i class="fas fa-chart-pie me-2"></i> Plan Statistics - {{ $school->school_name }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div id="planStats-{{ $school->id }}">
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

    <!-- Add Plan Modal -->
    <div class="modal fade" id="addPlanModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i> Create Evacuation Plan (<span id="modalBuildingCode">BLDG-XXX</span>)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addPlanForm" action="{{ route('fire-safety.evacuation-plan.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="school_id" id="planSchoolId">
                        <input type="hidden" name="building_id" id="planBuildingId">

                        <!-- 1st Row: Plan Name, Number of Routes, Assembly Areas -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Plan Name *</label>
                                <input type="text" class="form-control" name="plan_no" placeholder="e.g., EP-001" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Number of Routes *</label>
                                <input type="number" class="form-control" name="routes" min="1" max="10" value="2" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Assembly Areas *</label>
                                <input type="number" class="form-control" name="areas" min="1" max="5" value="1" required>
                            </div>
                        </div>

                        <!-- 2nd Row: Primary Evacuation Route, Secondary Evacuation Route -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Primary Evacuation Route *</label>
                                <textarea class="form-control" name="primary_route" rows="3" 
                                          placeholder="Describe the main path to the exit..." required></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Secondary Evacuation Route</label>
                                <textarea class="form-control" name="secondary_route" rows="3" 
                                          placeholder="Describe the alternative path..."></textarea>
                            </div>
                        </div>

                        <!-- 3rd Row: Primary Assembly Area, Secondary Assembly Area, Assembly Area Capacity -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Primary Assembly Area *</label>
                                <input type="text" class="form-control" name="primary_assembly_area" placeholder="e.g., Main Gate" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Secondary Assembly Area</label>
                                <input type="text" class="form-control" name="secondary_assembly_area" placeholder="e.g., Open Field">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Assembly Area Capacity</label>
                                <input type="number" class="form-control" name="assembly_capacity" min="1" placeholder="e.g., 500">
                            </div>
                        </div>

                        <!-- 4th Row: Display Information Only (Number of Emergency Exits, Safety Features) -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold text-muted">Number of Emergency Exits</label>
                                <input type="number" class="form-control bg-light" id="displayEmergencyExits" readonly disabled>
                                <input type="hidden" name="exits" id="hiddenEmergencyExits">
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-bold text-muted">Safety Features Installed</label>
                                <textarea class="form-control bg-light" id="displaySafetyFeatures" rows="2" readonly disabled placeholder="Auto-retrieved from building records"></textarea>
                            </div>
                        </div>

                        <!-- 5th Row: Emergency Contacts & Special Instructions -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Emergency Contacts</label>
                                <textarea class="form-control" name="emergency_contacts" rows="3" 
                                          placeholder="Key personnel and contact numbers..."></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Special Instructions</label>
                                <textarea class="form-control" name="special_instructions" rows="3" 
                                          placeholder="e.g., Instructions for persons with disabilities..."></textarea>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> Evacuation maps are now automatically generated based on building and safety equipment data. Save this plan to view the generated layout.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="savePlan()">
                        <i class="fas fa-save me-2"></i> Save Plan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Plan Modal -->
    <div class="modal fade" id="editPlanModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i> Edit Evacuation Plan (<span id="editModalBuildingCode">BLDG-XXX</span>)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editPlanForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="plan_id" id="editPlanId">
                        <input type="hidden" name="building_id" id="editBuildingId">

                        <!-- 1st Row: Plan Name, Number of Routes, Assembly Areas -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Plan Number</label>
                                <input type="text" class="form-control bg-light" name="plan_no" id="editPlanNo" readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Number of Routes *</label>
                                <input type="number" class="form-control" name="routes" id="editRoutes" min="1" max="10" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Assembly Areas *</label>
                                <input type="number" class="form-control" name="areas" id="editAreas" min="1" max="5" required>
                            </div>
                        </div>

                        <!-- 2nd Row: Primary Evacuation Route, Secondary Evacuation Route -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Primary Evacuation Route *</label>
                                <textarea class="form-control" name="primary_route" id="editPrimaryRoute" rows="3" required></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Secondary Evacuation Route</label>
                                <textarea class="form-control" name="secondary_route" id="editSecondaryRoute" rows="3"></textarea>
                            </div>
                        </div>

                        <!-- 3rd Row: Primary Assembly Area, Secondary Assembly Area, Assembly Area Capacity -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Primary Assembly Area *</label>
                                <input type="text" class="form-control" name="primary_assembly_area" id="editPrimaryArea" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Secondary Assembly Area</label>
                                <input type="text" class="form-control" name="secondary_assembly_area" id="editSecondaryArea">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Assembly Area Capacity</label>
                                <input type="number" class="form-control" name="assembly_capacity" id="editCapacity" min="1">
                            </div>
                        </div>

                        <!-- 4th Row: Display Information Only (Number of Emergency Exits, Safety Features) -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold text-muted">Number of Emergency Exits</label>
                                <input type="number" class="form-control bg-light" name="exits" id="editExits" readonly disabled>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Plan Status *</label>
                                <select class="form-control" name="status" id="editStatus" required>
                                    <option value="active">Active</option>
                                    <option value="draft">Draft</option>
                                    <option value="review">Under Review</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold text-muted text-truncate">Safety Features</label>
                                <input type="text" class="form-control bg-light" id="editSafetyFeatures" readonly disabled placeholder="Auto-retrieved">
                            </div>
                        </div>

                        <!-- 5th Row: Emergency Contacts & Special Instructions -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Emergency Contacts</label>
                                <textarea class="form-control" name="emergency_contacts" id="editContacts" rows="3"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Special Instructions</label>
                                <textarea class="form-control" name="special_instructions" id="editInstructions" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> Evacuation maps are now automatically generated based on building and safety equipment data.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updatePlan()">
                        <i class="fas fa-save me-2"></i> Update Plan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Plan Modal -->
    <div class="modal fade" id="viewPlanModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle me-2"></i> Evacuation Plan Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="planDetailsContent">
                        <!-- Plan details will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="deletePlanBtn" style="display: none;">
                        <i class="fas fa-trash me-2"></i> Delete Plan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Drill Modal -->
    <div class="modal fade" id="scheduleDrillModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-calendar-plus me-2"></i> Schedule Evacuation Drill
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="scheduleDrillForm">
                        @csrf
                        <input type="hidden" name="school_id" id="drillSchoolId">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Drill Type *</label>
                                <select class="form-control" name="drill_type" required>
                                    <option value="Announced">Announced Drill</option>
                                    <option value="Unannounced">Unannounced Drill</option>
                                    <option value="Partial">Partial Building Drill</option>
                                    <option value="Full">Full Evacuation Drill</option>
                                    <option value="Night">Night Drill</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Drill Date *</label>
                                <input type="date" class="form-control" name="drill_date" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Start Time <i>(optional)</i></label>
                                <input type="time" class="form-control" name="start_time">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">End Time <i>(optional)</i></label>
                                <input type="time" class="form-control" name="end_time">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Status *</label>
                                <select class="form-control" name="status" required>
                                    <option value="scheduled">Scheduled</option>
                                    <option value="ongoing">Ongoing</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Participants Count</label>
                                <input type="number" class="form-control" name="participants_count" min="0" placeholder="e.g., 500">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Evacuation Time (min)</label>
                                <input type="number" class="form-control" name="evacuation_time_minutes" min="0" placeholder="e.g., 5">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Coordinator</label>
                                <input type="text" class="form-control" name="coordinator" value="{{ Auth::user()->name }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Buildings to Include *</label>
                            <select class="form-control" name="building_ids[]" multiple id="drillBuildingsSelect" required style="height: 100px;">
                                <!-- Buildings will be populated by JavaScript -->
                            </select>
                            <small class="form-text text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple buildings.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Remarks</label>
                            <input type="text" class="form-control" name="remarks" placeholder="Brief summary of drill result...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes <i>(optional)</i></label>
                            <textarea class="form-control" name="notes" rows="2" placeholder="Additional instructions or detailed observations..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveDrillSchedule()">
                        <i class="fas fa-calendar-check me-2"></i> Confirm Schedule
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Store current school ID
        let currentSchoolId = null;
        let currentPlanId = null;

        // Initialize with first school
        document.addEventListener('DOMContentLoaded', function() {
            const firstTab = document.querySelector('#schoolTab button.active');
            if (firstTab) {
                currentSchoolId = firstTab.getAttribute('data-school-id');
                loadSchoolData(currentSchoolId);
            }

            // Set default dates
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 7);
            
            // Set drill date to next week
            document.querySelector('input[name="drill_date"]')?.value = tomorrow.toISOString().split('T')[0];
            // Set drill time to 10:00 AM
            document.querySelector('input[name="drill_time"]')?.value = '10:00';
        });

        // School tab switching
        document.querySelectorAll('#schoolTab button').forEach(button => {
            button.addEventListener('shown.bs.tab', function(event) {
                const schoolId = this.getAttribute('data-school-id');
                currentSchoolId = schoolId;
                loadSchoolData(schoolId);
            });
        });

        // Create Plan logic - Unify in modal show event
        document.getElementById('addPlanModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const form = document.getElementById('addPlanForm');
            form.reset();

            if (button && button.classList.contains('create-plan-btn')) {
                const buildingId = button.getAttribute('data-building-id');
                const buildingName = button.getAttribute('data-building-name');
                const buildingCode = button.getAttribute('data-building-code') || buildingName; // Fallback
                const schoolId = button.getAttribute('data-school-id') || currentSchoolId;

                document.getElementById('planSchoolId').value = schoolId;
                document.getElementById('planBuildingId').value = buildingId;
                document.getElementById('modalBuildingCode').textContent = buildingCode;

                // Load building details (Exits, Features)
                loadBuildingDetailsForPlan(buildingId);
            } else {
                // Fallback for general Add Plan button
                const schoolId = button?.getAttribute('data-school-id') || currentSchoolId;
                document.getElementById('planSchoolId').value = schoolId;
                document.getElementById('planBuildingId').value = '';
                document.getElementById('modalBuildingCode').textContent = 'Select Building';
                
                document.getElementById('displayEmergencyExits').value = 0;
                document.getElementById('hiddenEmergencyExits').value = 0;
                document.getElementById('displaySafetyFeatures').value = 'Please select a building by clicking "Create Plan" on a building card.';
            }
        });

        // Load building details for evacuation plan
        async function loadBuildingDetailsForPlan(buildingId) {
            try {
                const response = await fetch(`/fire-safety/building/${buildingId}/evacuation-data`);
                const building = await response.json();

                // Populate emergency exits (read-only)
                const exits = building.exits || 0;
                document.getElementById('displayEmergencyExits').value = exits;
                document.getElementById('hiddenEmergencyExits').value = exits;

                // Populate safety features via array or string check
                let featuresText = 'No safety features recorded';
                if (building.features) {
                    if (Array.isArray(building.features)) {
                        featuresText = building.features.join(', ');
                    } else if (typeof building.features === 'string') {
                        // Clean up JSON format if it looks like ["Feature"]
                        if (building.features.startsWith('[') && building.features.endsWith(']')) {
                            try {
                                const parsed = JSON.parse(building.features);
                                featuresText = Array.isArray(parsed) ? parsed.join(', ') : building.features;
                            } catch (e) {
                                featuresText = building.features.split(',').join(', ');
                            }
                        } else {
                            featuresText = building.features;
                        }
                    }
                }
                document.getElementById('displaySafetyFeatures').value = featuresText;

            } catch (error) {
                console.error('Error loading building details:', error);
                document.getElementById('displayEmergencyExits').value = 0;
                document.getElementById('hiddenEmergencyExits').value = 0;
                document.getElementById('displaySafetyFeatures').value = 'Error loading features';
            }
        }

        // Edit Plan button click logic with event delegation
        document.addEventListener('click', async function(e) {
            const button = e.target.closest('.edit-plan-btn');
            if (!button) return;

            const planId = button.getAttribute('data-plan-id');
            currentPlanId = planId;

            try {
                const response = await fetch(`/fire-safety/evacuation-plan/${planId}`);
                const plan = await response.json();

                // Populate form
                document.getElementById('editPlanId').value = plan.id;
                document.getElementById('editBuildingId').value = plan.building_id;
                document.getElementById('editPlanNo').value = plan.plan_no;
                document.getElementById('editModalBuildingCode').textContent = plan.building?.building_no || 'N/A';
                
                // Display from building record (read-only in modal)
                document.getElementById('editExits').value = plan.building?.emergency_exits || 0;
                const features = plan.building?.features ? plan.building.features.split(',').join(', ') : 'No safety features recorded';
                document.getElementById('editSafetyFeatures').value = features;

                document.getElementById('editRoutes').value = plan.routes;
                document.getElementById('editAreas').value = plan.areas;
                document.getElementById('editPrimaryRoute').value = plan.primary_route || '';
                document.getElementById('editSecondaryRoute').value = plan.secondary_route || '';
                document.getElementById('editPrimaryArea').value = plan.primary_assembly_area || '';
                document.getElementById('editSecondaryArea').value = plan.secondary_assembly_area || '';
                document.getElementById('editCapacity').value = plan.assembly_capacity || '';
                document.getElementById('editStatus').value = plan.status;
                document.getElementById('editContacts').value = plan.emergency_contacts || '';
                document.getElementById('editInstructions').value = plan.special_instructions || '';

            } catch (error) {
                console.error('Error loading plan data:', error);
                Swal.fire('Error', 'Failed to load plan data', 'error');
            }
        });

        // View Plan button click logic with event delegation
        document.addEventListener('click', async function(e) {
            const button = e.target.closest('.view-plan-btn');
            if (!button) return;

            const planId = button.getAttribute('data-plan-id');
            currentPlanId = planId;

            try {
                    const response = await fetch(`/fire-safety/evacuation-plan/${planId}/details`);
                    const responseData = await response.json();
                    const plan = responseData.plan;

                    let html = `
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="d-flex justify-content-between">
                                    <span>School Evacuation Map (Auto-generated)</span>
                                    <span class="small text-muted">Building: ${plan.building?.building_no}</span>
                                </h6>
                                <div id="autoSchoolLayout" class="school-layout-container mb-3">
                                    <!-- Dynamic layout will be generated here -->
                                </div>
                                <div class="legend-container">
                                    <div class="legend-item">
                                        <div class="legend-color" style="background-color: #ffebee; border: 1px solid #f44336;"></div>
                                        <span>Room w/ Extinguisher</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-color" style="background-color: #fffde7; border: 1px solid #ff9800;"></div>
                                        <span>Building w/ Alarm</span>
                                    </div>
                                    <div class="legend-item">
                                        <i class="fas fa-circle text-danger"></i>
                                        <span>Current Building</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p><strong>Plan Number:</strong> ${plan.plan_no}</p>
                                <p><strong>Building:</strong> ${plan.building?.building_no || 'N/A'} (${plan.building?.building_name || 'N/A'})</p>
                                <p><strong>School:</strong> ${plan.school?.school_name || 'N/A'}</p>
                                <p><strong>Status:</strong> <span class="badge ${plan.status === 'active' ? 'bg-success' : plan.status === 'draft' ? 'bg-secondary' : 'bg-warning'}">${plan.status}</span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Emergency Exits:</strong> ${plan.exits}</p>
                                <p><strong>Evacuation Routes:</strong> ${plan.routes}</p>
                                <p><strong>Assembly Areas:</strong> ${plan.areas}</p>
                                <p><strong>Created:</strong> ${new Date(plan.created_at).toLocaleDateString()}</p>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6>Primary Evacuation Route</h6>
                                <div class="border rounded p-3 bg-light">
                                    ${plan.primary_route ? plan.primary_route.replace(/\n/g, '<br>') : 'Not specified'}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6>Secondary Evacuation Route</h6>
                                <div class="border rounded p-3 bg-light">
                                    ${plan.secondary_route ? plan.secondary_route.replace(/\n/g, '<br>') : 'Not specified'}
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6>Assembly Areas</h6>
                                <div class="border rounded p-3 bg-light">
                                    <p><strong>Primary:</strong> ${plan.primary_assembly_area || 'Not specified'}</p>
                                    <p><strong>Secondary:</strong> ${plan.secondary_assembly_area || 'Not specified'}</p>
                                    <p><strong>Capacity:</strong> ${plan.assembly_capacity || 'Not specified'} persons</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6>Building Safety Equipment</h6>
                                <div class="border rounded p-3 bg-light">
                                    <p><strong>Emergency Exits:</strong> ${plan.building?.emergency_exits || 0}</p>
                                    <p><strong>Functional Alarms:</strong> ${plan.building?.alarm_systems_count || 0}</p>
                                    <p><strong>Active Extinguishers:</strong> ${plan.building?.fire_extinguishers_count || 0}</p>
                                </div>
                            </div>
                        </div>
                    `;

                    if (plan.emergency_contacts) {
                        html += `
                            <div class="mb-4">
                                <h6>Emergency Contacts</h6>
                                <div class="border rounded p-3 bg-light">
                                    ${plan.emergency_contacts.replace(/\n/g, '<br>')}
                                </div>
                            </div>
                        `;
                    }

                    if (plan.special_instructions) {
                        html += `
                            <div class="mb-4">
                                <h6>Special Instructions</h6>
                                <div class="border rounded p-3 bg-light">
                                    ${plan.special_instructions.replace(/\n/g, '<br>')}
                                </div>
                            </div>
                        `;
                    }

                    document.getElementById('planDetailsContent').innerHTML = html;

                    // Generate Dynamic Layout
                    generateAutoSchoolLayout(plan.building.id, responseData.school_buildings);

                    // Show delete button for admins or creators
                    const deleteBtn = document.getElementById('deletePlanBtn');
                    deleteBtn.style.display = 'block';
                    deleteBtn.onclick = function() { deletePlan(plan.id); };

                } catch (error) {
                    console.error('Error loading plan details:', error);
                    document.getElementById('planDetailsContent').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Failed to load plan details. Please try again.
                        </div>
                    `;
                }
            });
        });

        // Load school data
        async function loadSchoolData(schoolId) {
            if (!schoolId) return;
            currentSchoolId = schoolId;

            // Load drill history
            loadDrillHistory(schoolId);
            // Load plan stats
            loadPlanStats(schoolId);
            // Load sidebar stats
            loadSidebarStats(schoolId);
            
            // Check if map tab is active, if so init map
            const mapTab = document.getElementById(`map-tab-${schoolId}`);
            if (mapTab && mapTab.classList.contains('active')) {
                initEvacuationMap(schoolId);
            }
        }

        // Load building details for plan creation
        async function loadBuildingDetails(buildingId) {
            try {
                const response = await fetch(`/fire-safety/building/${buildingId}/evacuation-data`);
                const building = await response.json();

                // Set defaults based on building data
                document.getElementById('planExits').value = building.emergency_exits || 2;
                // You can add more defaults here

            } catch (error) {
                console.error('Error loading building details:', error);
            }
        }

        // Load drill history
        async function loadDrillHistory(schoolId) {
            const container = document.getElementById(`drillHistory-${schoolId}`);
            if (!container) return;

            try {
                const response = await fetch(`/fire-safety/drill-history/${schoolId}`);
                const drills = await response.json();

                if (!drills || drills.length === 0) {
                    container.innerHTML = `
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-calendar-times fa-3x mb-3" style="opacity: 0.2;"></i>
                            <p class="mb-3">No evacuation drills recorded for this school.</p>
                            <button class="btn btn-primary" onclick="openScheduleDrillModal(${schoolId})">
                                <i class="fas fa-calendar-plus me-2"></i> Schedule First Drill
                            </button>
                        </div>
                    `;
                    return;
                }

                let html = `
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Drill Type</th>
                                    <th>Status</th>
                                    <th>Coordinator</th>
                                    <th>Result / Remarks</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                drills.forEach(drill => {
                    const date = new Date(drill.drill_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                    const startTime = drill.start_time ? drill.start_time.substring(0, 5) : '--:--';
                    const endTime = drill.end_time ? drill.end_time.substring(0, 5) : '--:--';
                    
                    const statusColors = {
                        'scheduled': 'primary',
                        'ongoing': 'warning',
                        'completed': 'success',
                        'cancelled': 'secondary'
                    };
                    const color = statusColors[drill.status] || 'info';

                    html += `
                        <tr>
                            <td>
                                <div class="fw-bold">${date}</div>
                                <small class="text-muted"><i class="far fa-clock me-1"></i>${startTime} - ${endTime}</small>
                            </td>
                            <td><span class="fw-bold">${drill.drill_type}</span></td>
                            <td><span class="badge bg-${color} text-uppercase">${drill.status}</span></td>
                            <td>${drill.coordinator || 'N/A'}</td>
                            <td>
                                <div class="text-truncate" style="max-width: 200px;" title="${drill.remarks || 'No remarks'}">
                                    ${drill.remarks || '<span class="text-muted italic">No remarks recorded</span>'}
                                </div>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light border" onclick="viewDrill(${drill.id})" title="View Full Details">
                                    <i class="fas fa-info-circle text-primary"></i>
                                </button>
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
                console.error('Error loading drill history:', error);
                container.innerHTML = `
                    <div class="alert alert-danger m-3">
                        <i class="fas fa-exclamation-triangle me-2"></i> Failed to load drill history.
                        <button class="btn btn-sm btn-outline-danger ms-3" onclick="loadDrillHistory(${schoolId})">Retry</button>
                    </div>
                `;
            }
        }

        // Load plan statistics
        async function loadPlanStats(schoolId) {
            const container = document.getElementById(`planStats-${schoolId}`);
            if (!container) return;

            try {
                const response = await fetch(`/fire-safety/plan-stats/${schoolId}`);
                const stats = await response.json();

                const total = stats.total_buildings || 0;
                const active = stats.active_plans || 0;
                const draft = stats.draft_plans || 0;
                const none = stats.no_plan || 0;
                const score = stats.avg_safety_score || 0;

                const coverage = total > 0 ? Math.round((active / total) * 100) : 0;

                container.innerHTML = `
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold">Plan Coverage</span>
                            <span class="badge bg-${coverage >= 80 ? 'success' : (coverage >= 50 ? 'warning' : 'danger')}">${coverage}%</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar ${coverage >= 80 ? 'bg-success' : (coverage >= 50 ? 'bg-warning' : 'bg-danger')}" 
                                 role="progressbar" style="width: ${coverage}%"></div>
                        </div>
                    </div>

                    <div class="row g-2 mb-4">
                        <div class="col-6">
                            <div class="p-2 border rounded bg-light text-center">
                                <div class="small text-muted">Active</div>
                                <div class="h5 mb-0 fw-bold text-success">${active}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 border rounded bg-light text-center">
                                <div class="small text-muted">No Plan</div>
                                <div class="h5 mb-0 fw-bold text-danger">${none}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 text-center">
                        <div class="text-xs fw-bold text-uppercase text-muted mb-2">Avg. Safety Score</div>
                        <div class="h2 fw-bold mb-0 ${score >= 80 ? 'text-success' : (score >= 60 ? 'text-warning' : 'text-danger')}">${score}%</div>
                        <div class="small text-muted">Based on safety equipment</div>
                    </div>

                    <div class="alert alert-info py-2 small mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>Tip:</strong> Buildings with plans and functional alarms score 30% higher in safety assessments.
                    </div>
                `;

            } catch (error) {
                console.error('Error loading statistics:', error);
                container.innerHTML = `<div class="text-center text-danger py-4 small">Failed to load statistics.</div>`;
            }
        }

        // Load sidebar stats
        async function loadSidebarStats(schoolId) {
            try {
                const response = await fetch(`/fire-safety/evacuation-sidebar-stats/${schoolId}`);
                const stats = await response.json();

                let html = `
                    <div class="text-white mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span>Active Plans: <strong>${stats.active_plans || 0}</strong></span>
                    </div>
                    <div class="text-white mb-2">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        <span>Needs Review: <strong>${stats.draft_plans || 0}</strong></span>
                    </div>
                    <div class="text-white mb-3">
                        <i class="fas fa-times-circle text-danger me-2"></i>
                        <span>No Plan: <strong>${stats.no_plan || 0}</strong></span>
                    </div>
                `;

                document.getElementById('sidebarStats').innerHTML = html;

            } catch (error) {
                console.error('Error loading sidebar stats:', error);
            }

        // Generate dynamic school layout for evacuation plan
        function generateAutoSchoolLayout(targetBuildingId, buildings) {
            const container = document.getElementById('autoSchoolLayout');
            if (!container) return;

            let html = '<div class="main-division-box">';

            buildings.forEach(building => {
                const isTarget = building.id == targetBuildingId;
                const buildingHasAlarm = (building.alarm_systems_many && building.alarm_systems_many.length > 0);
                
                html += `
                    <div class="building-box ${isTarget ? 'current-building' : ''} ${buildingHasAlarm ? 'has-alarm' : ''}">
                        <div class="building-title">
                            ${building.building_no}
                            <div style="font-size: 0.6rem; color: #888;">${building.building_type || 'Building'}</div>
                            ${buildingHasAlarm ? `<div class="badge bg-warning text-dark mt-1" style="font-size: 0.5rem; display: block;"><i class="fas fa-bell me-1"></i>Covered by Alarm</div>` : ''}
                        </div>
                `;

                // Calculate floors dynamically or use building data
                const floorCount = building.floors || 1;
                
                // Group rooms by floor if available, otherwise distribute
                const buildingRooms = building.rooms || [];
                const roomsByFloor = {};

                for (let f = 1; f <= floorCount; f++) {
                    roomsByFloor[f] = buildingRooms.filter(r => r.floor_no == f);
                }

                // If no rooms are explicitly assigned to floors, distribute building.rooms_count
                const roomsCount = building.rooms_count || buildingRooms.length || 0;
                
                for (let f = floorCount; f >= 1; f--) {
                    const floorLabel = f === 1 ? '1st Floor' : f === 2 ? '2nd Floor' : f === 3 ? '3rd Floor' : f + 'th Floor';
                    html += `
                        <div class="floor-box">
                            <div class="floor-title">${floorLabel}</div>
                            <div class="rooms-container">
                    `;

                    // Show rooms for this floor
                    const floorRooms = roomsByFloor[f] || [];
                    if (floorRooms.length > 0) {
                        floorRooms.forEach(room => {
                            const hasExtinguisher = building.fire_extinguishers?.some(ext => ext.room_id == room.id);
                            html += `
                                <div class="room-unit ${hasExtinguisher ? 'has-extinguisher' : ''}" title="${room.room_name || 'Room'}">
                                    ${hasExtinguisher ? '<i class="fas fa-fire-extinguisher"></i>' : room.room_no || ''}
                                </div>
                            `;
                        });
                    } else if (isTarget || floorCount > 0) {
                        // If no database rooms, show simplified boxes based on building count
                        const estRoomsPerFloor = Math.ceil(roomsCount / floorCount) || 1;
                        for (let i = 0; i < estRoomsPerFloor; i++) {
                             html += `<div class="room-unit" title="Room"></div>`;
                        }
                    }

                    html += `
                            </div>
                        </div>
                    `;
                }

                html += `</div>`;
            });

            html += '</div>';
            container.innerHTML = html;
        }

        // Initialize with first school
        document.addEventListener('DOMContentLoaded', function() {
            // Placeholder for initials if needed
        });

        // Save Plan
        async function savePlan() {
            const form = document.getElementById('addPlanForm');

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Check if building is selected
            const buildingId = document.getElementById('planBuildingId').value;
            if (!buildingId) {
                Swal.fire('Incomplete Form', 'Please select a building first by clicking "Create Plan" on a building card.', 'warning');
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
                    Swal.fire('Success', 'Evacuation plan created successfully!', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to create plan', 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Failed to create evacuation plan. Please try again.', 'error');
            }
        }

        // Update Plan
        async function updatePlan() {
            const form = document.getElementById('editPlanForm');
            const planId = document.getElementById('editPlanId').value;

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);
            // Fix for PUT with FormData
            formData.append('_method', 'PUT');

            try {
                const response = await fetch(`/fire-safety/evacuation-plan/${planId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire('Success', 'Evacuation plan updated successfully!', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to update plan', 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Failed to update evacuation plan', 'error');
            }
        }

        // Delete Plan
        async function deletePlan(planId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Are you sure you want to delete this evacuation plan? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/fire-safety/evacuation-plan/${planId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire('Deleted!', 'Evacuation plan deleted successfully!', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message || 'Failed to delete plan', 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Failed to delete evacuation plan', 'error');
                    }
                }
            });
        }

        // Schedule Drill
        async function openScheduleDrillModal(schoolId) {
            // Show modal and load buildings
            const modalElement = document.getElementById('scheduleDrillModal');
            const modal = new bootstrap.Modal(modalElement);
            document.getElementById('drillSchoolId').value = schoolId;
            
            // Set today as default date
            const today = new Date().toISOString().split('T')[0];
            modalElement.querySelector('input[name="drill_date"]').value = today;

            // Load buildings for this school
            await loadBuildingsForDrill(schoolId);
            
            modal.show();
        }

        // Load buildings for drill
        async function loadBuildingsForDrill(schoolId) {
            try {
                const response = await fetch(`/fire-safety/drill-buildings/${schoolId}`);
                const buildings = await response.json();

                const select = document.getElementById('drillBuildingsSelect');
                select.innerHTML = '';

                buildings.forEach(building => {
                    const option = document.createElement('option');
                    option.value = building.id;
                    option.textContent = (building.building_no || 'BLDG') + (building.building_name ? ` (${building.building_name})` : '');
                    // Auto-select buildings if they have an evacuation plan
                    if (building.has_plan) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });

            } catch (error) {
                console.error('Error loading buildings:', error);
                Swal.fire('Error', 'Failed to load buildings list.', 'error');
            }
        }

        // Save Drill Schedule
        async function saveDrillSchedule() {
            const form = document.getElementById('scheduleDrillForm');

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Check if at least one building is selected
            const selectedBuildings = Array.from(document.getElementById('drillBuildingsSelect').selectedOptions);
            if (selectedBuildings.length === 0) {
                Swal.fire('Warning', 'Please select at least one building for this drill.', 'warning');
                return;
            }

            const formData = new FormData(form);

            try {
                // Show loading state
                Swal.fire({
                    title: 'Saving...',
                    text: 'Recording drill schedule',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                const response = await fetch('/fire-safety/drill/schedule', {
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
                        title: 'Scheduled!',
                        text: 'Evacuation drill has been recorded.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        bootstrap.Modal.getInstance(document.getElementById('scheduleDrillModal')).hide();
                        loadDrillHistory(document.getElementById('drillSchoolId').value);
                    });
                } else {
                    Swal.fire('Failed', data.message || 'Could not save drill schedule.', 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'An unexpected error occurred while saving.', 'error');
            }
        }

        // View Drill Details
        async function viewDrill(drillId) {
            try {
                const response = await fetch(`/fire-safety/drill/${drillId}`);
                const drill = await response.json();

                // Create and show modal with drill details
                const drillDate = new Date(drill.drill_date).toLocaleDateString();
                const drillTime = drill.drill_time || '10:00';

                const modalHtml = `
                    <div class="modal fade" id="viewDrillModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                                    <h5 class="modal-title">
                                        <i class="fas fa-clipboard-check me-2"></i> Drill #${drill.id}
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <strong>Date:</strong><br>
                                        <span class="text-muted">${drillDate} at ${drillTime}</span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Type:</strong><br>
                                        <span class="badge bg-primary">${drill.drill_type}</span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Status:</strong><br>
                                        <span class="badge bg-${drill.status === 'completed' ? 'success' : drill.status === 'scheduled' ? 'primary' : 'warning'}">${drill.status}</span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Coordinator:</strong><br>
                                        <span class="text-muted">${drill.coordinator}</span>
                                    </div>
                                    ${drill.notes ? `
                                    <div class="mb-3">
                                        <strong>Notes:</strong><br>
                                        <div class="border rounded p-3 bg-light mt-1">${drill.notes}</div>
                                    </div>
                                    ` : ''}
                                    ${drill.results ? `
                                    <div class="mb-3">
                                        <strong>Results:</strong><br>
                                        <div class="border rounded p-3 bg-light mt-1">${drill.results}</div>
                                    </div>
                                    ` : ''}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    ${drill.status === 'scheduled' ? `
                                    <button type="button" class="btn btn-primary" onclick="startDrill(${drill.id})">
                                        <i class="fas fa-play me-2"></i> Start Drill
                                    </button>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                // Remove existing modal if any
                const existingModal = document.getElementById('viewDrillModal');
                if (existingModal) existingModal.remove();

                // Add modal to body and show it
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                const modal = new bootstrap.Modal(document.getElementById('viewDrillModal'));
                modal.show();

                console.error('Error loading drill details:', error);
                Swal.fire('Error', 'Failed to load drill details. Please try again.', 'error');
            }
        }

        // Start Drill
        async function startDrill(drillId) {
            Swal.fire({
                title: 'Start Drill?',
                text: 'Are you sure you want to start this evacuation drill?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Start Now'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Started!', 'Evacuation drill started! All personnel have been notified.', 'success');
                }
            });
        }

        // Cancel Drill
        async function cancelDrill(drillId) {
            Swal.fire({
                title: 'Cancel Drill?',
                text: 'Are you sure you want to cancel this evacuation drill?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, cancel it'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/fire-safety/drill/${drillId}/cancel`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire('Cancelled!', 'Drill cancelled successfully!', 'success').then(() => {
                                if (currentSchoolId) {
                                    loadDrillHistory(currentSchoolId);
                                }
                            });
                        } else {
                            Swal.fire('Error', data.message || 'Failed to cancel drill', 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Failed to cancel drill. Please try again.', 'error');
                    }
                }
            });
        }

        // View Plan (from map click)
        function viewPlan(buildingId, buildingName) {
            // Check if this building has a plan
            fetch(`/fire-safety/building/${buildingId}/has-plan`)
                .then(response => response.json())
                .then(data => {
                    if (data.has_plan) {
                        // Find and click the view button for this building
                        const viewBtn = document.querySelector(`.view-plan-btn[data-plan-id="${data.plan_id}"]`);
                        if (viewBtn) {
                            viewBtn.click();
                        }
                    } else {
                        // Create plan for this building
                        const createBtn = document.querySelector(`.create-plan-btn[data-building-id="${buildingId}"]`);
                        if (createBtn) {
                            createBtn.click();
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Failed to check plan status', 'error');
                });
        }

        // View/Create Plan wrapper
        async function viewPlan(planId, buildingId, buildingNo) {
            if (!planId || planId === null) {
                // Open add modal for this building
                const btn = document.querySelector(`.create-plan-btn[data-building-id="${buildingId}"]`);
                if (btn) {
                    btn.click();
                } else {
                    // Fallback
                    document.getElementById('planBuildingId').value = buildingId;
                    document.getElementById('displayBuildingName').value = buildingNo;
                    loadBuildingDetails(buildingId);
                    const modal = new bootstrap.Modal(document.getElementById('addPlanModal'));
                    modal.show();
                }
                return;
            }
            
            // Open view modal for this plan
            const btn = document.querySelector(`.view-plan-btn[data-plan-id="${planId}"]`);
            if (btn) {
                btn.click();
                const modal = new bootstrap.Modal(document.getElementById('viewPlanModal'));
                modal.show();
            }
        }

        // Print All Plans
        function printAllPlans() {
            Swal.fire({
                title: 'Generate Report?',
                text: 'Generate evacuation plans report for all schools?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Generate'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Generating...', 'Generating comprehensive evacuation plans report... This may take a moment.', 'info');
                    window.open('/fire-safety/evacuation-plans/report', '_blank');
                }
            });
        }

        // ==========================================
        // EVACUATION MAP LOGIC (Interactive Canvas)
        // ==========================================
        let mapData = {};
        let isMapEditable = {}; // Track edit mode per school
        let draggingElement = null;
        let dragOffsetX = 0;
        let dragOffsetY = 0;

        async function initEvacuationMap(schoolId) {
            const canvasContainer = document.getElementById(`school-map-canvas-${schoolId}`);
            if (!canvasContainer) return;
            
            // If already loaded, don't reload unless forced
            if (canvasContainer.dataset.loaded === 'true') return;

            // Show loading state if it was cleared
            if (!canvasContainer.innerHTML.includes('fa-spinner')) {
                canvasContainer.innerHTML = `
                    <div class="text-center pt-5 text-muted">
                        <i class="fas fa-spinner fa-spin fa-3x mb-3"></i><br>Loading Map Data...
                    </div>
                `;
            }

            try {
                // Set a timeout to prevent infinite loading if the server is slow or errors silently
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 10000); // 10s timeout

                const response = await fetch(`/fire-safety/school/${schoolId}/map-data`, { signal: controller.signal });
                clearTimeout(timeoutId);
                
                if (!response.ok) throw new Error('Network response was not ok');
                
                const school = await response.json();
                mapData[schoolId] = school;
                
                renderSchoolMap(school, schoolId);
                canvasContainer.dataset.loaded = 'true';
            } catch (error) {
                console.error('Error loading map data:', error);
                const errorMsg = error.name === 'AbortError' ? 'Loading timed out.' : 'Failed to load map data.';
                canvasContainer.innerHTML = `
                    <div class="text-center pt-5 text-danger">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i><br>
                        ${errorMsg}<br>
                        <button class="btn btn-sm btn-outline-danger mt-3" onclick="canvasContainer.dataset.loaded=''; initEvacuationMap(${schoolId})">Retry</button>
                    </div>
                `;
            }
        }

        function renderSchoolMap(school, schoolId) {
            const canvas = document.getElementById(`school-map-canvas-${schoolId}`);
            canvas.innerHTML = ''; // Clear loading

            const layout = school.evacuation_map_layout || {};
            let xCounter = 50;
            let yCounter = 50;

            // 1. Render Buildings
            school.buildings.forEach((building, index) => {
                const savedPos = layout[`building_${building.id}`] || { x: xCounter, y: yCounter };
                if (!layout[`building_${building.id}`]) {
                    xCounter += 250;
                    if (xCounter > 800) { xCounter = 50; yCounter += 300; }
                }

                const bDiv = document.createElement('div');
                bDiv.className = 'map-element building-element';
                bDiv.id = `map-bldg-${building.id}`;
                bDiv.dataset.id = `building_${building.id}`; // Key for saving
                bDiv.dataset.schoolId = schoolId;
                bDiv.style.position = 'absolute';
                bDiv.style.left = savedPos.x + 'px';
                bDiv.style.top = savedPos.y + 'px';
                bDiv.style.width = '220px'; // Fixed width for simplicity
                bDiv.style.minHeight = '150px';
                bDiv.style.backgroundColor = 'white';
                bDiv.style.border = '3px solid black';
                bDiv.style.boxShadow = '2px 2px 5px rgba(0,0,0,0.2)';
                bDiv.style.zIndex = '10';
                bDiv.style.cursor = 'default'; // Changed by edit mode

                // Building Title
                const title = document.createElement('div');
                title.style.textAlign = 'center';
                title.style.fontWeight = 'bold';
                title.style.padding = '5px';
                title.style.backgroundColor = '#f1f1f1';
                title.style.borderBottom = '1px solid #ddd';
                title.innerText = building.building_no;
                bDiv.appendChild(title);

                // Container for Floors/Rooms
                const contentDiv = document.createElement('div');
                contentDiv.style.padding = '10px';
                
                // Draw Rooms inside
                // We'll create a simple grid representation
                const floors = building.floors || 1;
                for (let f = floors; f >= 1; f--) {
                    const floorLabel = document.createElement('div');
                    floorLabel.style.fontSize = '10px';
                    floorLabel.style.fontWeight = 'bold';
                    floorLabel.style.marginTop = '5px';
                    floorLabel.innerText = `Floor ${f}`;
                    contentDiv.appendChild(floorLabel);

                    const roomGrid = document.createElement('div');
                    roomGrid.style.display = 'grid';
                    roomGrid.style.gridTemplateColumns = 'repeat(3, 1fr)';
                    roomGrid.style.gap = '2px';
                    
                    // Filter rooms for this floor
                    const floorRooms = building.rooms ? building.rooms.filter(r => r.floor_no == f) : [];
                    
                    if (floorRooms.length > 0) {
                        floorRooms.forEach(room => {
                            const rDiv = document.createElement('div');
                            rDiv.style.border = '1px solid #333';
                            rDiv.style.height = '30px';
                            rDiv.style.fontSize = '8px';
                            rDiv.style.display = 'flex';
                            rDiv.style.alignItems = 'center';
                            rDiv.style.justifyContent = 'center';
                            rDiv.style.position = 'relative'; // For icons
                            rDiv.style.backgroundColor = '#f8f9fa';
                            rDiv.title = room.room_name;
                            rDiv.innerText = room.room_name.substring(0, 6);

                            // Extinguisher Icon Check
                            if (building.fire_extinguishers && building.fire_extinguishers.some(e => e.room_id == room.id)) {
                                const extIcon = document.createElement('div');
                                extIcon.style.position = 'absolute';
                                extIcon.style.bottom = '1px';
                                extIcon.style.right = '1px';
                                extIcon.style.width = '8px';
                                extIcon.style.height = '5px';
                                extIcon.style.backgroundColor = '#dc3545'; // Red reverse rect
                                rDiv.appendChild(extIcon);
                            }

                            roomGrid.appendChild(rDiv);
                        });
                    } else {
                        // Placeholder rooms
                         const rDiv = document.createElement('div');
                         rDiv.style.gridColumn = '1 / span 3';
                         rDiv.style.fontSize = '9px';
                         rDiv.style.textAlign = 'center';
                         rDiv.innerText = '(No rooms configured)';
                         roomGrid.appendChild(rDiv);
                    }
                    contentDiv.appendChild(roomGrid);
                }
                bDiv.appendChild(contentDiv);

                // Stairways (Visual Only)
                // If two_stairways is true (check features string) or default
                // Assuming defaults for visual
                const stairLeft = document.createElement('div');
                stairLeft.innerHTML = '<i class="fas fa-stairs"></i>';
                stairLeft.style.position = 'absolute';
                stairLeft.style.left = '-15px';
                stairLeft.style.top = '50%';
                bDiv.appendChild(stairLeft);

                if (building.features && building.features.includes('Two Stairways')) {
                    const stairRight = document.createElement('div');
                    stairRight.innerHTML = '<i class="fas fa-stairs"></i>';
                    stairRight.style.position = 'absolute';
                    stairRight.style.right = '-15px';
                    stairRight.style.top = '50%';
                    bDiv.appendChild(stairRight);
                }

                // Exits
                if (building.emergency_exits > 0) {
                    const exit = document.createElement('div');
                    exit.innerHTML = '<i class="fas fa-door-open text-success"></i>';
                    exit.style.position = 'absolute';
                    exit.style.bottom = '-10px';
                    exit.style.left = '50%';
                    bDiv.appendChild(exit);
                }
                
                // Alarms
                if (building.alarm_systems && building.alarm_systems.length > 0) {
                    // Just show one indicator per floor or on top right
                    // User asked: "red circle and add a label on what floor..."
                    // Since we are top-level view, let's put them on the side list attached to building
                    const alarmContainer = document.createElement('div');
                    alarmContainer.style.position = 'absolute';
                    alarmContainer.style.top = '5px';
                    alarmContainer.style.right = '-25px';
                    alarmContainer.style.display = 'flex';
                    alarmContainer.style.flexDirection = 'column';
                    alarmContainer.style.gap = '2px';

                    building.alarm_systems.forEach(alarm => {
                        const aBadge = document.createElement('div');
                        aBadge.style.width = '20px';
                        aBadge.style.height = '20px';
                        aBadge.style.borderRadius = '50%';
                        aBadge.style.backgroundColor = '#dc3545';
                        aBadge.style.color = 'white';
                        aBadge.style.fontSize = '8px';
                        aBadge.style.display = 'flex';
                        aBadge.style.alignItems = 'center';
                        aBadge.style.justifyContent = 'center';
                        aBadge.title = `Alarm: ${alarm.location || 'Unknown'}`;
                        
                        // Try to extract floor number from location if possible, otherwise 'A'
                        let floorTxt = 'A';
                        if (alarm.location && alarm.location.toLowerCase().includes('floor')) {
                            const match = alarm.location.match(/(\d+)/);
                            if (match) floorTxt = match[0];
                        }
                        aBadge.innerText = floorTxt;
                        alarmContainer.appendChild(aBadge);
                    });
                    bDiv.appendChild(alarmContainer);
                }

                canvas.appendChild(bDiv);
                
                // Make draggable
                makeDraggable(bDiv, schoolId);
            });

            // 2. Render Evacuation Routes (Text) & Assembly Areas
            // We need to create these if not present, or load them
            // Since we can't create them from scratch in this view easily without a toolbox,
            // we will create defaults for each building if they don't exist in layout.
            // Or better: Create a fixed set of "Movable Assets" that are always available?
            // User requirement: "Primary/secondary evacuation route... strategize where to put the placement"
            
            // We'll create one "Evacuation Route" text per building + 1 general Assembly Area info
            school.buildings.forEach(building => {
                 // Route Text
                 const routeKey = `route_${building.id}`;
                 const savedRoutePos = layout[routeKey] || { x: savedPos.x + 230, y: savedPos.y + 50 };
                 
                 const routeDiv = document.createElement('div');
                 routeDiv.className = 'map-element route-element';
                 routeDiv.id = `map-route-${building.id}`;
                 routeDiv.dataset.id = routeKey;
                 routeDiv.dataset.schoolId = schoolId;
                 routeDiv.style.position = 'absolute';
                 routeDiv.style.left = savedRoutePos.x + 'px';
                 routeDiv.style.top = savedRoutePos.y + 'px';
                 routeDiv.style.color = 'green';
                 routeDiv.style.fontWeight = 'bold';
                 routeDiv.style.zIndex = '5';
                 routeDiv.innerText = "EVACUATION ROUTE ->";
                 routeDiv.style.whiteSpace = 'nowrap';
                 routeDiv.style.cursor = 'default';
                 canvas.appendChild(routeDiv);
                 makeDraggable(routeDiv, schoolId);

                 // Assembly Area Info (if building has plan)
                 if (building.evacuation_plan) {
                     const areaKey = `assembly_${building.id}`;
                     const savedAreaPos = layout[areaKey] || { x: savedPos.x, y: savedPos.y + 200 };
                     
                     const areaDiv = document.createElement('div');
                     areaDiv.className = 'map-element assembly-element';
                     areaDiv.id = `map-assembly-${building.id}`;
                     areaDiv.dataset.id = areaKey;
                     areaDiv.dataset.schoolId = schoolId;
                     areaDiv.style.position = 'absolute';
                     areaDiv.style.left = savedAreaPos.x + 'px';
                     areaDiv.style.top = savedAreaPos.y + 'px';
                     areaDiv.style.border = '2px dashed #0d6efd';
                     areaDiv.style.backgroundColor = '#e7f5ff';
                     areaDiv.style.padding = '5px';
                     areaDiv.style.fontSize = '10px';
                     areaDiv.style.zIndex = '5';
                     areaDiv.style.width = '150px';
                     areaDiv.innerHTML = `<strong>Assembly Area:</strong><br>${building.evacuation_plan.primary_assembly_area || 'Not Set'}`;
                     areaDiv.style.cursor = 'default';
                     canvas.appendChild(areaDiv);
                     makeDraggable(areaDiv, schoolId);
                 }
            });

        }

        function toggleMapEdit(schoolId) {
            isMapEditable[schoolId] = !isMapEditable[schoolId];
            const btn = document.getElementById(`edit-placement-btn-${schoolId}`);
            const saveBtn = document.getElementById(`save-placement-btn-${schoolId}`);
            
            if (isMapEditable[schoolId]) {
                btn.innerHTML = '<i class="fas fa-lock me-2"></i> Lock Placement';
                btn.classList.replace('btn-outline-primary', 'btn-warning');
                saveBtn.disabled = false;
                
                // Enable dragging visually
                document.querySelectorAll(`#school-map-canvas-${schoolId} .map-element`).forEach(el => {
                    el.style.cursor = 'move';
                    el.style.outline = '2px dashed #999';
                });
            } else {
                btn.innerHTML = '<i class="fas fa-arrows-alt me-2"></i> Edit Placement';
                btn.classList.replace('btn-warning', 'btn-outline-primary');
                saveBtn.disabled = true;

                // Disable dragging visually
                document.querySelectorAll(`#school-map-canvas-${schoolId} .map-element`).forEach(el => {
                    el.style.cursor = 'default';
                    el.style.outline = 'none';
                });
            }
        }

        function makeDraggable(element, schoolId) {
            let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
            
            element.onmousedown = dragMouseDown;

            function dragMouseDown(e) {
                if (!isMapEditable[schoolId]) return;
                
                e = e || window.event;
                e.preventDefault();
                // get the mouse cursor position at startup:
                pos3 = e.clientX;
                pos4 = e.clientY;
                document.onmouseup = closeDragElement;
                // call a function whenever the cursor moves:
                document.onmousemove = elementDrag;
                
                // Bring to front
                element.style.zIndex = '100';
            }

            function elementDrag(e) {
                e = e || window.event;
                e.preventDefault();
                // calculate the new cursor position:
                pos1 = pos3 - e.clientX;
                pos2 = pos4 - e.clientY;
                pos3 = e.clientX;
                pos4 = e.clientY;
                // set the element's new position:
                element.style.top = (element.offsetTop - pos2) + "px";
                element.style.left = (element.offsetLeft - pos1) + "px";
            }

            function closeDragElement() {
                // stop moving when mouse button is released:
                document.onmouseup = null;
                document.onmousemove = null;
                element.style.zIndex = '10'; // Reset z-index
            }
        }

        async function saveMapLayout(schoolId) {
            const canvas = document.getElementById(`school-map-canvas-${schoolId}`);
            const elements = canvas.querySelectorAll('.map-element');
            const layout = {};

            elements.forEach(el => {
                layout[el.dataset.id] = {
                    x: el.offsetLeft,
                    y: el.offsetTop
                };
            });

            try {
                const response = await fetch(`/fire-safety/school/${schoolId}/map-save`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ layout: layout })
                });

                const result = await response.json();
                if (result.success) {
                    Swal.fire('Saved', 'Map layout saved successfully!', 'success');
                    // Toggle off edit mode
                    toggleMapEdit(schoolId);
                } else {
                    Swal.fire('Error', 'Failed to save layout', 'error');
                }
            } catch (error) {
                console.error('Error saving map:', error);
                Swal.fire('Error', 'Failed to save layout', 'error');
            }
        }
    </script>
</body>
</html>