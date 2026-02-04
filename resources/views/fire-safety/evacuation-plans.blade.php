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
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-map me-2"></i> Building Evacuation Plans - {{ $school->school_name }}
                                </h6>
                                <div>
                                    <button class="btn btn-primary btn-sm me-2"
                                            data-bs-toggle="modal"
                                            data-bs-target="#addPlanModal"
                                            data-school-id="{{ $school->id }}">
                                        <i class="fas fa-plus me-2"></i> Add Plan
                                    </button>
                                    <button class="btn btn-success btn-sm"
                                            onclick="scheduleDrill({{ $school->id }})">
                                        <i class="fas fa-bullhorn me-2"></i> Schedule Drill
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
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
                                                            data-building-name="{{ $building->building_no }}"
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
                        <i class="fas fa-plus me-2"></i> Create Evacuation Plan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addPlanForm" action="{{ route('fire-safety.evacuation-plan.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="school_id" id="planSchoolId">
                        <input type="hidden" name="building_id" id="planBuildingId">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Plan Number *</label>
                                <input type="text" class="form-control" name="plan_no" placeholder="e.g., EP-001" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Building</label>
                                <input type="text" class="form-control" id="displayBuildingName" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Number of Emergency Exits *</label>
                                <input type="number" class="form-control" name="exits" id="planExits" min="1" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Number of Routes *</label>
                                <input type="number" class="form-control" name="routes" min="1" max="5" value="2" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Assembly Areas *</label>
                                <input type="number" class="form-control" name="areas" min="1" max="3" value="2" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Primary Evacuation Route *</label>
                            <textarea class="form-control" name="primary_route" rows="3" 
                                      placeholder="Describe the primary evacuation route (e.g., Exit through main doors, turn left, proceed to stairwell A, exit to front parking lot)" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Secondary Evacuation Route</label>
                            <textarea class="form-control" name="secondary_route" rows="3" 
                                      placeholder="Describe the secondary evacuation route (use if primary route is blocked)"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Primary Assembly Area *</label>
                                <input type="text" class="form-control" name="primary_assembly_area" placeholder="e.g., Front Parking Lot" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Secondary Assembly Area</label>
                                <input type="text" class="form-control" name="secondary_assembly_area" placeholder="e.g., Sports Field">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Assembly Area Capacity *</label>
                                <input type="number" class="form-control" name="assembly_capacity" min="1" placeholder="e.g., 500" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Plan Status *</label>
                                <select class="form-control" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="draft">Draft</option>
                                    <option value="review">Under Review</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Emergency Contacts</label>
                            <textarea class="form-control" name="emergency_contacts" rows="2" 
                                      placeholder="List emergency contacts (name, role, phone number)"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Special Instructions</label>
                            <textarea class="form-control" name="special_instructions" rows="3" 
                                      placeholder="Any special evacuation instructions for disabled persons, hazardous materials, etc."></textarea>
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
                        <i class="fas fa-edit me-2"></i> Edit Evacuation Plan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editPlanForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="plan_id" id="editPlanId">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Plan Number</label>
                                <input type="text" class="form-control" name="plan_no" id="editPlanNo" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Building</label>
                                <input type="text" class="form-control" id="editBuildingName" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Number of Emergency Exits *</label>
                                <input type="number" class="form-control" name="exits" id="editExits" min="1" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Number of Routes *</label>
                                <input type="number" class="form-control" name="routes" id="editRoutes" min="1" max="5" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Assembly Areas *</label>
                                <input type="number" class="form-control" name="areas" id="editAreas" min="1" max="3" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Primary Evacuation Route *</label>
                            <textarea class="form-control" name="primary_route" id="editPrimaryRoute" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Secondary Evacuation Route</label>
                            <textarea class="form-control" name="secondary_route" id="editSecondaryRoute" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Primary Assembly Area *</label>
                                <input type="text" class="form-control" name="primary_assembly_area" id="editPrimaryArea" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Secondary Assembly Area</label>
                                <input type="text" class="form-control" name="secondary_assembly_area" id="editSecondaryArea">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Assembly Area Capacity *</label>
                                <input type="number" class="form-control" name="assembly_capacity" id="editCapacity" min="1" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Plan Status *</label>
                                <select class="form-control" name="status" id="editStatus" required>
                                    <option value="active">Active</option>
                                    <option value="draft">Draft</option>
                                    <option value="review">Under Review</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Emergency Contacts</label>
                            <textarea class="form-control" name="emergency_contacts" id="editContacts" rows="2"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Special Instructions</label>
                            <textarea class="form-control" name="special_instructions" id="editInstructions" rows="3"></textarea>
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
        <div class="modal-dialog">
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

                        <div class="mb-3">
                            <label class="form-label">Drill Date *</label>
                            <input type="date" class="form-control" name="drill_date" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Drill Time *</label>
                            <input type="time" class="form-control" name="drill_time" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Drill Type *</label>
                            <select class="form-control" name="drill_type" required>
                                <option value="announced">Announced Drill</option>
                                <option value="unannounced">Unannounced Drill</option>
                                <option value="partial">Partial Building Drill</option>
                                <option value="full">Full Evacuation Drill</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Buildings to Include *</label>
                            <select class="form-control" name="building_ids[]" multiple id="drillBuildingsSelect" required>
                                <!-- Buildings will be populated by JavaScript -->
                            </select>
                            <small class="form-text">Hold Ctrl/Cmd to select multiple buildings</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Coordinator *</label>
                            <input type="text" class="form-control" name="coordinator" value="{{ Auth::user()->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Additional instructions..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveDrillSchedule()">
                        <i class="fas fa-calendar-check me-2"></i> Schedule Drill
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

        // Create Plan button click
        document.querySelectorAll('.create-plan-btn').forEach(button => {
            button.addEventListener('click', function() {
                const buildingId = this.getAttribute('data-building-id');
                const buildingName = this.getAttribute('data-building-name');
                const schoolId = this.getAttribute('data-school-id');

                document.getElementById('planSchoolId').value = schoolId;
                document.getElementById('planBuildingId').value = buildingId;
                document.getElementById('displayBuildingName').value = buildingName;

                // Load building details for defaults
                loadBuildingDetails(buildingId);
            });
        });

        // Add Plan modal open
        document.getElementById('addPlanModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            if (button && button.classList.contains('create-plan-btn')) return; // Already handled
            
            const schoolId = button?.getAttribute('data-school-id') || currentSchoolId;
            document.getElementById('planSchoolId').value = schoolId;
            
            // Reset form
            document.getElementById('addPlanForm').reset();
            document.getElementById('planBuildingId').value = '';
            document.getElementById('displayBuildingName').value = 'Select building after saving';
        });

        // Edit Plan button click
        document.querySelectorAll('.edit-plan-btn').forEach(button => {
            button.addEventListener('click', async function() {
                const planId = this.getAttribute('data-plan-id');
                currentPlanId = planId;

                try {
                    const response = await fetch(`/fire-safety/evacuation-plan/${planId}`);
                    const plan = await response.json();

                    // Populate form
                    document.getElementById('editPlanId').value = plan.id;
                    document.getElementById('editPlanNo').value = plan.plan_no;
                    document.getElementById('editBuildingName').value = plan.building?.building_no || 'N/A';
                    document.getElementById('editExits').value = plan.exits;
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
        });

        // View Plan button click
        document.querySelectorAll('.view-plan-btn').forEach(button => {
            button.addEventListener('click', async function() {
                const planId = this.getAttribute('data-plan-id');
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

            try {
                // Load drill history if container exists
                const drillContainer = document.getElementById(`drillHistory-${schoolId}`);
                if (drillContainer) {
                    await loadDrillHistory(schoolId);
                }

                // Load plan stats if container exists
                const statsContainer = document.getElementById(`planStats-${schoolId}`);
                if (statsContainer) {
                    await loadPlanStats(schoolId);
                }

                // Load sidebar stats
                const sidebarStats = document.getElementById('sidebarStats');
                if (sidebarStats) {
                    await loadSidebarStats(schoolId);
                }
            } catch (error) {
                console.error('Error loading school data:', error);
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
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-calendar-times fa-2x mb-3"></i>
                            <p>No evacuation drills scheduled.</p>
                            <button class="btn btn-sm btn-primary" onclick="scheduleDrill(${schoolId})">
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
                                    <th>Time</th>
                                    <th>Type</th>
                                    <th>Buildings</th>
                                    <th>Coordinator</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                drills.forEach(drill => {
                    const date = new Date(drill.drill_date).toLocaleDateString();
                    const time = drill.drill_time || '10:00';
                    const statusClass = drill.status === 'completed' ? 'success' : 
                                      drill.status === 'scheduled' ? 'primary' : 
                                      drill.status === 'cancelled' ? 'secondary' : 'warning';

                    html += `
                        <tr>
                            <td>${date}</td>
                            <td>${time}</td>
                            <td>${drill.drill_type}</td>
                            <td>${drill.buildings_count || 0} buildings</td>
                            <td>${drill.coordinator}</td>
                            <td><span class="badge bg-${statusClass}">${drill.status}</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="viewDrill(${drill.id})">
                                    <i class="fas fa-eye"></i>
                                </button>
                                ${drill.status === 'scheduled' ? `
                                <button class="btn btn-sm btn-outline-danger" onclick="cancelDrill(${drill.id})">
                                    <i class="fas fa-times"></i>
                                </button>
                                ` : ''}
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
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Failed to load drill history. Please try again.
                        <button class="btn btn-sm btn-light ms-3" onclick="loadDrillHistory(${schoolId})">
                            <i class="fas fa-redo"></i> Retry
                        </button>
                    </div>
                `;
            }
        }

        // Load plan statistics
        async function loadPlanStats(schoolId) {
            try {
                const response = await fetch(`/fire-safety/plan-stats/${schoolId}`);
                const stats = await response.json();

                const activePlans = stats.active_plans || 0;
                const totalBuildings = stats.total_buildings || 0;
                const coverage = totalBuildings > 0 ? Math.round((activePlans / totalBuildings) * 100) : 0;

                let html = `
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <h3>${coverage}% Coverage</h3>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar ${coverage >= 80 ? 'bg-success' : coverage >= 50 ? 'bg-warning' : 'bg-danger'}"
                                     style="width: ${coverage}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-4">
                            <h5 class="text-success">${activePlans}</h5>
                            <small>Active Plans</small>
                        </div>
                        <div class="col-4">
                            <h5 class="text-warning">${stats.draft_plans || 0}</h5>
                            <small>Draft Plans</small>
                        </div>
                        <div class="col-4">
                            <h5 class="text-danger">${totalBuildings - activePlans}</h5>
                            <small>No Plan</small>
                        </div>
                    </div>
                `;

                // Add recommendations if coverage is low
                if (coverage < 80) {
                    html += `
                        <hr>
                        <div class="mt-3">
                            <h6>Recommendations:</h6>
                            <ol class="small">
                                <li>Create evacuation plans for buildings without one</li>
                                <li>Review and update existing plans</li>
                                <li>Schedule evacuation drills</li>
                            </ol>
                        </div>
                    `;
                }

                document.getElementById(`planStats-${schoolId}`).innerHTML = html;

            } catch (error) {
                console.error('Error loading plan stats:', error);
                document.getElementById(`planStats-${schoolId}`).innerHTML = `
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
        async function scheduleDrill(schoolId) {
            // Show modal and load buildings
            const modal = new bootstrap.Modal(document.getElementById('scheduleDrillModal'));
            document.getElementById('drillSchoolId').value = schoolId;
            
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
                    option.textContent = building.building_no + (building.building_name ? ` (${building.building_name})` : '');
                    option.selected = building.has_plan; // Auto-select buildings with plans
                    select.appendChild(option);
                });

            } catch (error) {
                console.error('Error loading buildings:', error);
                Swal.fire('Error', 'Failed to load buildings. Please try again.', 'error');
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
                Swal.fire('Warning', 'Please select at least one building for the drill.', 'warning');
                return;
            }

            const formData = new FormData(form);

            try {
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
                    Swal.fire('Success', 'Evacuation drill scheduled successfully!', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to schedule drill', 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Failed to schedule evacuation drill', 'error');
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
    </script>
</body>
</html>