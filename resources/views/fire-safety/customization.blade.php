<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customization & Settings - Fire Safety</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .config-item {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: white;
            transition: all 0.3s;
        }

        .config-item:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .config-badge {
            font-size: 0.75rem;
            padding: 3px 8px;
            border-radius: 12px;
        }

        .settings-tabs {
            border-bottom: 2px solid #dee2e6;
        }

        .settings-tab-btn {
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

        .settings-tab-btn:hover {
            color: white;
            background-color: #8A1217;
            border-color: #8A1217 #8A1217 #dee2e6;
        }

        .settings-tab-btn.active {
            color: white !important;
            background-color: #8A1217 !important;
            border-color: #8A1217 #8A1217 #8A1217 !important;
            position: relative;
            z-index: 1;
        }

        .settings-tab-btn:not(.active):not(:hover) {
            color: #495057;
            background-color: #f8f9fa;
            border-color: #dee2e6 #dee2e6 #dee2e6;
        }

        .status-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .status-active { background-color: #28a745; }
        .status-inactive { background-color: #dc3545; }
        .status-draft { background-color: #ffc107; }

        .sortable-handle {
            cursor: move;
            color: #6c757d;
        }

        .sortable-ghost {
            opacity: 0.4;
            background-color: #f8f9fa;
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
                    <h4 class="text-white mb-0">
                        @if(auth()->user()->role === 'admin')
                            System Customization
                        @else
                            School Settings
                        @endif
                    </h4>
                </div>

                <div class="col-auto">
                    <div class="d-flex align-items-center">
                        <!-- User Accounts Link (Admin Only) -->
                        @if(auth()->user()->role === 'admin')
                        <a href="#users-tab-pane" class="text-white me-3 text-decoration-none" title="User Accounts" onclick="document.getElementById('users-tab').click()">
                            <i class="fas fa-users-cog fa-lg"></i>
                            <span class="d-none d-xl-inline ms-1">User Accounts</span>
                        </a>
                        @endif

                        <div class="dropdown">
                            <a href="#" class="text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle fa-lg me-2"></i>
                                <span>{{ Auth::user()->name }}</span>
                                <small class="ms-2 badge bg-light text-dark">{{ ucfirst(Auth::user()->role) }}</small>
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
                        <span>Fire Extinguishers</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('fire-safety.evacuation-plans') }}">
                        <span class="nav-icon"><i class="fas fa-map-signs"></i></span>
                        <span>Evacuation Plans</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('fire-safety.customization') }}">
                        <span class="nav-icon"><i class="fas fa-cog"></i></span>
                        @if(auth()->user()->role === 'admin')
                            <span>Customization</span>
                        @else
                            <span>Update School Info</span>
                        @endif
                    </a>
                </li>
            </ul>

            <hr class="bg-white my-4">

            <!-- User Info -->
            <div class="text-white small">
                <div class="mb-2">
                    <i class="fas fa-user me-2"></i>
                    <strong>{{ Auth::user()->name }}</strong>
                </div>
                <div class="mb-2">
                    <i class="fas fa-user-tag me-2"></i>
                    <span>{{ ucfirst(Auth::user()->role) }}</span>
                </div>
                @if(Auth::user()->school)
                <div class="mb-3">
                    <i class="fas fa-school me-2"></i>
                    <span>{{ Auth::user()->school->school_name }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @if(auth()->user()->role === 'admin')
        <!-- Admin View: Both System Customization and School Management -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-body p-0">
                        <div class="settings-tabs">
                            <nav>
                                <div class="nav nav-tabs border-0" id="settingsTab" role="tablist">
                                    <button class="nav-link settings-tab-btn active"
                                            id="school-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#school-tab-pane"
                                            type="button"
                                            role="tab"
                                            aria-controls="school-tab-pane"
                                            aria-selected="true">
                                        <i class="fas fa-school me-2"></i> School Management
                                    </button>
                                    <button class="nav-link settings-tab-btn"
                                            id="system-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#system-tab-pane"
                                            type="button"
                                            role="tab"
                                            aria-controls="system-tab-pane"
                                            aria-selected="false">
                                        <i class="fas fa-sliders-h me-2"></i> System Customization
                                    </button>
                                    <button class="nav-link settings-tab-btn"
                                            id="users-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#users-tab-pane"
                                            type="button"
                                            role="tab"
                                            aria-controls="users-tab-pane"
                                            aria-selected="false">
                                        <i class="fas fa-users me-2"></i> User Management
                                    </button>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="settingsTabContent">
            <!-- School Management Tab -->
            <div class="tab-pane fade show active" id="school-tab-pane">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card dashboard-card mb-4">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-school me-2"></i> All Schools
                                </h6>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSchoolModal">
                                    <i class="fas fa-plus me-2"></i> Add School
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>School Name</th>
                                                <th>School ID</th>
                                                <th>Status</th>
                                                <th>Buildings</th>
                                                <th>Last Updated</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($schools as $school)
                                            <tr>
                                                <td>
                                                    <strong>{{ $school->school_name }}</strong>
                                                    <div class="text-muted small">{{ $school->address }}</div>
                                                </td>
                                                <td>{{ $school->school_id }}</td>
                                                <td>
                                                    <span class="badge {{ $school->status === 'passed' ? 'bg-success' : ($school->status === 'unconfigured' ? 'bg-warning' : 'bg-danger') }}">
                                                        {{ strtoupper($school->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $school->buildings_count ?? 0 }}</td>
                                                <td>{{ $school->updated_at ? $school->updated_at->format('Y-m-d') : 'N/A' }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary edit-school-btn"
                                                            data-school-id="{{ $school->id }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editSchoolModal">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger delete-school-btn"
                                                            data-school-id="{{ $school->id }}"
                                                            data-school-name="{{ $school->school_name }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card dashboard-card">
                            <div class="card-header py-3 bg-primary text-white">
                                <h6 class="m-0 fw-bold">
                                    <i class="fas fa-chart-pie me-2"></i> Schools Overview
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <h6>Total Schools: {{ $schools->count() }}</h6>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" style="width: {{ $schools->where('status', 'passed')->count() / max(1, $schools->count()) * 100 }}%">
                                            Passed: {{ $schools->where('status', 'passed')->count() }}
                                        </div>
                                        <div class="progress-bar bg-warning" style="width: {{ $schools->where('status', 'unconfigured')->count() / max(1, $schools->count()) * 100 }}%">
                                            Unconfigured: {{ $schools->where('status', 'unconfigured')->count() }}
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <h6>Quick Actions</h6>
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-sm btn-success" onclick="exportSchoolsData()">
                                            <i class="fas fa-file-export me-2"></i> Export Schools Data
                                        </button>
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#importSchoolsModal">
                                            <i class="fas fa-file-import me-2"></i> Import Schools
                                        </button>
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Schools are the foundation of the fire safety system. Each school can have multiple buildings and equipment.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Customization Tab -->
            <div class="tab-pane fade" id="system-tab-pane">
                <div class="row">
                    <!-- Building Types -->
                    <div class="col-lg-6 mb-4">
                        <div class="card dashboard-card h-100">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-building me-2"></i> Building Types
                                </h6>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addBuildingTypeModal">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="buildingTypesList" class="sortable-list">
                                    @foreach($buildingTypes as $type)
                                    <div class="config-item" data-id="{{ $type->id }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $type->name }}</strong>
                                                <div class="text-muted small">{{ $type->description }}</div>
                                            </div>
                                            <div class="d-flex">
                                                <span class="badge config-badge {{ $type->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $type->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                                <i class="fas fa-grip-vertical sortable-handle ms-3" style="cursor: move;"></i>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <button class="btn btn-sm btn-outline-primary edit-config-btn"
                                                    data-id="{{ $type->id }}"
                                                    data-type="building_type"
                                                    data-name="{{ $type->name }}"
                                                    data-description="{{ $type->description }}"
                                                    data-is-active="{{ $type->is_active }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-config-btn"
                                                    data-id="{{ $type->id }}"
                                                    data-type="building_type"
                                                    data-name="{{ $type->name }}">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alarm Types & Statuses -->
                    <div class="col-lg-6 mb-4">
                        <div class="card dashboard-card h-100">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-bell me-2"></i> Alarm Configuration
                                </h6>
                                <div>
                                    <button class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addAlarmTypeModal">
                                        <i class="fas fa-plus"></i> Add Type
                                    </button>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#addAlarmStatusModal">
                                        <i class="fas fa-plus"></i> Add Status
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <h6>Alarm Types</h6>
                                    <div id="alarmTypesList">
                                        @foreach($alarmTypes as $type)
                                        <div class="config-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong>{{ $type->name }}</strong>
                                                <span class="badge config-badge {{ $type->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $type->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                            <div class="mt-2">
                                                <button class="btn btn-sm btn-outline-primary edit-config-btn"
                                                        data-id="{{ $type->id }}"
                                                        data-type="alarm_type"
                                                        data-name="{{ $type->name }}"
                                                        data-is-active="{{ $type->is_active }}">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div>
                                    <h6>Alarm Status Options</h6>
                                    <div id="alarmStatusList">
                                        @foreach($alarmStatuses as $status)
                                        <div class="config-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong>{{ $status->name }}</strong>
                                                <span class="badge config-badge {{ $status->color_class }}">{{ $status->category }}</span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fire Extinguisher Configuration -->
                    <div class="col-lg-6 mb-4">
                        <div class="card dashboard-card h-100">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-fire-extinguisher me-2"></i> Extinguisher Configuration
                                </h6>
                                <div>
                                    <button class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addExtinguisherTypeModal">
                                        <i class="fas fa-plus"></i> Add Type
                                    </button>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#addExtinguisherStatusModal">
                                        <i class="fas fa-plus"></i> Add Status
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <h6>Extinguisher Types</h6>
                                    <div id="extinguisherTypesList">
                                        @foreach($extinguisherTypes as $type)
                                        <div class="config-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong>{{ $type->name }}</strong>
                                                <span class="text-muted small">{{ $type->code }}</span>
                                            </div>
                                            <div class="mt-2">
                                                <button class="btn btn-sm btn-outline-primary edit-config-btn"
                                                        data-id="{{ $type->id }}"
                                                        data-type="extinguisher_type"
                                                        data-name="{{ $type->name }}"
                                                        data-code="{{ $type->code }}"
                                                        data-description="{{ $type->description }}">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div>
                                    <h6>Extinguisher Status</h6>
                                    <div id="extinguisherStatusList">
                                        @foreach($extinguisherStatuses as $status)
                                        <div class="config-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong>{{ $status->name }}</strong>
                                                <span class="badge config-badge {{ $status->color_class }}">{{ $status->category }}</span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Safety Features -->
                    <div class="col-lg-6 mb-4">
                        <div class="card dashboard-card h-100">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-shield-alt me-2"></i> Safety Features
                                </h6>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addSafetyFeatureModal">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="safetyFeaturesList" class="sortable-list">
                                    @foreach($safetyFeatures as $feature)
                                    <div class="config-item" data-id="{{ $feature->id }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $feature->name }}</strong>
                                                <div class="text-muted small">{{ $feature->description }}</div>
                                            </div>
                                            <div class="d-flex">
                                                <span class="badge config-badge bg-info">{{ $feature->category }}</span>
                                                <i class="fas fa-grip-vertical sortable-handle ms-3" style="cursor: move;"></i>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <button class="btn btn-sm btn-outline-primary edit-config-btn"
                                                    data-id="{{ $feature->id }}"
                                                    data-type="safety_feature"
                                                    data-name="{{ $feature->name }}"
                                                    data-description="{{ $feature->description }}"
                                                    data-category="{{ $feature->category }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        @else
        <!-- Contributor/School Admin View: Only School Information Update -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card dashboard-card mb-4">
                    <div class="card-header py-3 bg-primary text-white">
                        <h6 class="m-0 fw-bold">
                            <i class="fas fa-school me-2"></i> Update School Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <form id="updateSchoolForm">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="school_id" value="{{ auth()->user()->school_id }}">

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">School Name *</label>
                                    <input type="text" class="form-control" name="school_name" 
                                           value="{{ auth()->user()->school->school_name ?? '' }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">School ID *</label>
                                    <input type="text" class="form-control" name="school_id_display" 
                                           value="{{ auth()->user()->school->school_id ?? '' }}" required readonly>
                                    <small class="text-muted">School ID cannot be changed</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">School Address *</label>
                                <textarea class="form-control" name="address" rows="3" required>{{ auth()->user()->school->address ?? '' }}</textarea>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">School Head *</label>
                                    <input type="text" class="form-control" name="school_head" 
                                           value="{{ auth()->user()->school->school_head ?? '' }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">DRRM Coordinator *</label>
                                    <input type="text" class="form-control" name="school_drrm_coordinator" 
                                           value="{{ auth()->user()->school->school_drrm_coordinator ?? '' }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">School Head Contact Number</label>
                                    <input type="text" class="form-control" name="school_head_contact" 
                                           value="{{ auth()->user()->school->school_head_contact ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">DRRM Coordinator Contact Number</label>
                                    <input type="text" class="form-control" name="drrm_coordinator_contact" 
                                           value="{{ auth()->user()->school->drrm_coordinator_contact ?? '' }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="email" 
                                       value="{{ auth()->user()->school->email ?? '' }}">
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Only school administrators can update this information. Changes will be reviewed by system administrators.
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Update School Information
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card dashboard-card">
                    <div class="card-header py-3 bg-success text-white">
                        <h6 class="m-0 fw-bold">
                            <i class="fas fa-chart-line me-2"></i> School Statistics
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="display-4">{{ auth()->user()->school->status ? strtoupper(auth()->user()->school->status) : 'UNKNOWN' }}</div>
                            <div class="text-muted">Current Status</div>
                        </div>

                        <div class="mb-3">
                            <h6>Building Summary</h6>
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="h5 mb-0">{{ auth()->user()->school->buildings_count ?? 0 }}</div>
                                    <small class="text-muted">Buildings</small>
                                </div>
                                <div class="col-6">
                                    <div class="h5 mb-0">{{ auth()->user()->school->rooms_count ?? 0 }}</div>
                                    <small class="text-muted">Rooms</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6>Safety Equipment</h6>
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="h5 mb-0">{{ auth()->user()->school->alarm_systems_count ?? 0 }}</div>
                                    <small class="text-muted">Alarm Systems</small>
                                </div>
                                <div class="col-6">
                                    <div class="h5 mb-0">{{ auth()->user()->school->fire_extinguishers_count ?? 0 }}</div>
                                    <small class="text-muted">Extinguishers</small>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Contact system administrator for any issues or additional configuration needs.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Modals for Admin -->
    @if(auth()->user()->role === 'admin')
    <!-- Add School Modal -->
    <div class="modal fade" id="addSchoolModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i> Add New School
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addSchoolForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">School Name *</label>
                            <input type="text" class="form-control" name="school_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">School ID *</label>
                            <input type="text" class="form-control" name="school_id" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address *</label>
                            <textarea class="form-control" name="address" rows="3" required></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">School Head *</label>
                                <input type="text" class="form-control" name="school_head" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DRRM Coordinator *</label>
                                <input type="text" class="form-control" name="school_drrm_coordinator" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Initial Status *</label>
                            <select class="form-control" name="status" required>
                                <option value="unconfigured">Unconfigured</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveNewSchool()">
                        <i class="fas fa-save me-2"></i> Save School
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit School Modal -->
    <div class="modal fade" id="editSchoolModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i> Edit School
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editSchoolForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="school_id" id="editSchoolId">
                        <div class="mb-3">
                            <label class="form-label">School Name *</label>
                            <input type="text" class="form-control" name="school_name" id="editSchoolName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">School ID *</label>
                            <input type="text" class="form-control" name="school_id" id="editSchoolIdDisplay" required readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address *</label>
                            <textarea class="form-control" name="address" id="editSchoolAddress" rows="3" required></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">School Head *</label>
                                <input type="text" class="form-control" name="school_head" id="editSchoolHead" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DRRM Coordinator *</label>
                                <input type="text" class="form-control" name="school_drrm_coordinator" id="editSchoolDrrmCoordinator" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status *</label>
                            <select class="form-control" name="status" id="editSchoolStatus" required>
                                <option value="unconfigured">Unconfigured</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="passed">Passed</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateSchool()">
                        <i class="fas fa-save me-2"></i> Update School
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Building Type Modal -->
    <div class="modal fade" id="addBuildingTypeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i> Add Building Type
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addBuildingTypeForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Building Type Name *</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="buildingTypeActive" checked>
                                <label class="form-check-label" for="buildingTypeActive">Active</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveBuildingType()">
                        <i class="fas fa-save me-2"></i> Save Type
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus me-2"></i> Add New User
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address *</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role *</label>
                            <select class="form-control" name="role" id="userRoleSelect" required onchange="toggleAdminConfirm(this.value)">
                                <option value="contributor" selected>Contributor</option>
                                <option value="admin">Administrator</option>
                                <option value="school_admin">School Administrator</option>
                                <option value="inspector">Inspector</option>
                                <option value="viewer">Viewer</option>
                            </select>
                            <small class="text-muted">Default role is Contributor</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">School *</label>
                            <select class="form-control" name="school_id">
                                <option value="">Select School (Optional for Admin)</option>
                                @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Module Registration *</label>
                            <div class="card p-2 bg-light">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="modules[]" value="fire_safety" id="modFire" checked>
                                    <label class="form-check-label" for="modFire">Fire Safety</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="modules[]" value="typhoon_flood" id="modTyphoon">
                                    <label class="form-check-label" for="modTyphoon">Typhoon/Flood</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="modules[]" value="incidents" id="modIncidents">
                                    <label class="form-check-label" for="modIncidents">Incidents</label>
                                </div>
                            </div>
                            <small class="text-muted">Users can only access modules they are registered for.</small>
                        </div>
                        <div id="adminPasswordConfirm" style="display: none;" class="alert alert-danger">
                            <div class="mb-0">
                                <label class="form-label fw-bold">Admin Authorization Required</label>
                                <input type="password" class="form-control shadow-sm mb-2" name="admin_confirmation" placeholder="Enter YOUR password to create admin">
                                <small>Creating an admin account requires confirmation of your own password for security.</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Initial Password *</label>
                            <input type="password" class="form-control" name="password" required>
                            <small class="text-muted">User will be asked to change password on first login</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveNewUser()">
                        <i class="fas fa-save me-2"></i> Create User
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>

    <script>
        // Initialize sortable lists
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Sortable for building types
            const buildingTypesList = document.getElementById('buildingTypesList');
            if (buildingTypesList) {
                new Sortable(buildingTypesList, {
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    handle: '.sortable-handle',
                    onEnd: function(evt) {
                        updateBuildingTypesOrder();
                    }
                });
            }

            // Initialize Sortable for safety features
            const safetyFeaturesList = document.getElementById('safetyFeaturesList');
            if (safetyFeaturesList) {
                new Sortable(safetyFeaturesList, {
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    handle: '.sortable-handle',
                    onEnd: function(evt) {
                        updateSafetyFeaturesOrder();
                    }
                });
            }

            // Load users for admin
            if (document.getElementById('usersTableBody')) {
                loadUsers();
            }

            // Set up edit school buttons
            document.querySelectorAll('.edit-school-btn').forEach(button => {
                button.addEventListener('click', async function() {
                    const schoolId = this.getAttribute('data-school-id');
                    await loadSchoolForEdit(schoolId);
                });
            });

            // Set up delete school buttons
            document.querySelectorAll('.delete-school-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const schoolId = this.getAttribute('data-school-id');
                    const schoolName = this.getAttribute('data-school-name');
                    deleteSchool(schoolId, schoolName);
                });
            });

            // Set up edit config buttons
            document.querySelectorAll('.edit-config-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const configId = this.getAttribute('data-id');
                    const configType = this.getAttribute('data-type');
                    const configName = this.getAttribute('data-name');
                    const configDescription = this.getAttribute('data-description');
                    const configIsActive = this.getAttribute('data-is-active');
                    
                    // Show edit modal (you need to create this modal)
                    editConfigItem(configId, configType, configName, configDescription, configIsActive);
                });
            });

            // Set up delete config buttons
            document.querySelectorAll('.delete-config-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const configId = this.getAttribute('data-id');
                    const configType = this.getAttribute('data-type');
                    const configName = this.getAttribute('data-name');
                    
                    if (confirm(`Delete ${configName}?`)) {
                        deleteConfigItem(configId, configType);
                    }
                });
            });

            // Contributor: Set up school update form
            const updateSchoolForm = document.getElementById('updateSchoolForm');
            if (updateSchoolForm) {
                updateSchoolForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    await updateContributorSchool();
                });
            }
        });

        // Admin: Load school for editing
        async function loadSchoolForEdit(schoolId) {
            try {
                const response = await fetch(`/fire-safety/school/${schoolId}`);
                const school = await response.json();

                document.getElementById('editSchoolId').value = school.id;
                document.getElementById('editSchoolName').value = school.school_name;
                document.getElementById('editSchoolIdDisplay').value = school.school_id;
                document.getElementById('editSchoolAddress').value = school.address;
                document.getElementById('editSchoolHead').value = school.school_head;
                document.getElementById('editSchoolDrrmCoordinator').value = school.school_drrm_coordinator;
                document.getElementById('editSchoolStatus').value = school.status;

            } catch (error) {
                console.error('Error loading school:', error);
                alert('Failed to load school data');
            }
        }

        // Admin: Save new school
        async function saveNewSchool() {
            const form = document.getElementById('addSchoolForm');
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);

            try {
                const response = await fetch('/fire-safety/school', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    alert('School added successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to add school'));
                }

            } catch (error) {
                console.error('Error:', error);
                alert('Failed to add school');
            }
        }

        // Admin: Update school
        async function updateSchool() {
            const form = document.getElementById('editSchoolForm');
            const schoolId = document.getElementById('editSchoolId').value;

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);

            try {
                const response = await fetch(`/fire-safety/school/${schoolId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    alert('School updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to update school'));
                }

            } catch (error) {
                console.error('Error:', error);
                alert('Failed to update school');
            }
        }

        // Admin: Delete school
        async function deleteSchool(schoolId, schoolName) {
            if (!confirm(`Are you sure you want to delete "${schoolName}"?\n\nThis will also delete all associated buildings, equipment, and data.`)) {
                return;
            }

            try {
                const response = await fetch(`/fire-safety/school/${schoolId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    alert('School deleted successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to delete school'));
                }

            } catch (error) {
                console.error('Error:', error);
                alert('Failed to delete school');
            }
        }

        // Admin: Save building type
        async function saveBuildingType() {
            const form = document.getElementById('addBuildingTypeForm');
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);

            try {
                const response = await fetch('/fire-safety/config/building-type', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    alert('Building type added successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to add building type'));
                }

            } catch (error) {
                console.error('Error:', error);
                alert('Failed to add building type');
            }
        }

        // Toggle admin confirmation field
        function toggleAdminConfirm(role) {
            const confirmDiv = document.getElementById('adminPasswordConfirm');
            if (role === 'admin') {
                confirmDiv.style.display = 'block';
                confirmDiv.querySelector('input').setAttribute('required', 'required');
            } else {
                confirmDiv.style.display = 'none';
                confirmDiv.querySelector('input').removeAttribute('required');
            }
        }

        // Admin: Load users
        async function loadUsers() {
            try {
                const response = await fetch('/fire-safety/users');
                const users = await response.json();

                const tableBody = document.getElementById('usersTableBody');
                tableBody.innerHTML = '';

                users.forEach(user => {
                    const lastLogin = user.last_login_at ? new Date(user.last_login_at).toLocaleDateString() : 'Never';
                    
                    const row = `
                        <tr>
                            <td>
                                <strong>${user.name}</strong>
                                ${user.id === {{ auth()->id() }} ? '<span class="badge bg-primary ms-2">You</span>' : ''}
                            </td>
                            <td>${user.email}</td>
                            <td><span class="badge bg-info">${user.role}</span></td>
                            <td>${user.school?.school_name || 'N/A'}</td>
                            <td><span class="badge ${user.status === 'active' ? 'bg-success' : 'bg-secondary'}">${user.status}</span></td>
                            <td>${lastLogin}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="editUser(${user.id})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                ${user.id !== {{ auth()->id() }} ? `
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(${user.id}, '${user.name}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                ` : ''}
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });

            } catch (error) {
                console.error('Error loading users:', error);
                document.getElementById('usersTableBody').innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Failed to load users
                        </td>
                    </tr>
                `;
            }
        }

        // Admin: Save new user
        async function saveNewUser() {
            const form = document.getElementById('addUserForm');
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);

            try {
                const response = await fetch('/fire-safety/users', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    alert('User created successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to create user'));
                }

            } catch (error) {
                console.error('Error:', error);
                alert('Failed to create user');
            }
        }

        // Admin: Edit user
        async function editUser(userId) {
            // Load user data and show edit modal
            try {
                const response = await fetch(`/fire-safety/users/${userId}`);
                const user = await response.json();
                
                // You need to create an edit user modal
                // For now, just alert
                alert(`Edit user: ${user.name}`);
                
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to load user data');
            }
        }

        // Admin: Delete user
        async function deleteUser(userId, userName) {
            if (!confirm(`Delete user "${userName}"?`)) {
                return;
            }

            try {
                const response = await fetch(`/fire-safety/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    alert('User deleted successfully!');
                    loadUsers();
                } else {
                    alert('Error: ' + (data.message || 'Failed to delete user'));
                }

            } catch (error) {
                console.error('Error:', error);
                alert('Failed to delete user');
            }
        }

        // Admin: Update building types order
        async function updateBuildingTypesOrder() {
            const items = document.querySelectorAll('#buildingTypesList .config-item');
            const order = Array.from(items).map(item => item.getAttribute('data-id'));
            
            try {
                const response = await fetch('/fire-safety/config/building-type/order', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ order: order })
                });

                const data = await response.json();
                if (!data.success) {
                    console.error('Failed to update order');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Admin: Update safety features order
        async function updateSafetyFeaturesOrder() {
            const items = document.querySelectorAll('#safetyFeaturesList .config-item');
            const order = Array.from(items).map(item => item.getAttribute('data-id'));
            
            try {
                const response = await fetch('/fire-safety/config/safety-feature/order', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ order: order })
                });

                const data = await response.json();
                if (!data.success) {
                    console.error('Failed to update order');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Contributor: Update school information
        async function updateContributorSchool() {
            const form = document.getElementById('updateSchoolForm');
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);

            try {
                const response = await fetch('/fire-safety/my-school/update', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    alert('School information updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to update school information'));
                }

            } catch (error) {
                console.error('Error:', error);
                alert('Failed to update school information');
            }
        }

        // Export schools data
        async function exportSchoolsData() {
            if (confirm('Export all schools data to CSV?')) {
                try {
                    const response = await fetch('/fire-safety/schools/export');
                    const blob = await response.blob();
                    
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `schools_export_${new Date().toISOString().split('T')[0]}.csv`;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);
                    
                } catch (error) {
                    console.error('Error:', error);
                    alert('Failed to export data');
                }
            }
        }

        // Edit config item
        function editConfigItem(id, type, name, description, isActive) {
            // Create and show edit modal
            const modalHtml = `
                <div class="modal fade" id="editConfigModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                                <h5 class="modal-title">Edit ${type.replace('_', ' ')}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="editConfigForm">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="config_id" value="${id}">
                                    <input type="hidden" name="config_type" value="${type}">
                                    <div class="mb-3">
                                        <label class="form-label">Name *</label>
                                        <input type="text" class="form-control" name="name" value="${name}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" rows="3">${description || ''}</textarea>
                                    </div>
                                    ${isActive !== null ? `
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="editConfigActive" ${isActive === '1' ? 'checked' : ''}>
                                            <label class="form-check-label" for="editConfigActive">Active</label>
                                        </div>
                                    </div>
                                    ` : ''}
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" onclick="saveConfigUpdate()">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Remove existing modal if any
            const existingModal = document.getElementById('editConfigModal');
            if (existingModal) existingModal.remove();

            // Add modal to body and show it
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = new bootstrap.Modal(document.getElementById('editConfigModal'));
            modal.show();
        }

        // Save config update
        async function saveConfigUpdate() {
            const form = document.getElementById('editConfigForm');
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);
            const configType = formData.get('config_type');
            const configId = formData.get('config_id');

            try {
                const response = await fetch(`/fire-safety/config/${configType}/${configId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    alert('Configuration updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to update configuration'));
                }

            } catch (error) {
                console.error('Error:', error);
                alert('Failed to update configuration');
            }
        }

        // Delete config item
        async function deleteConfigItem(configId, configType) {
            try {
                const response = await fetch(`/fire-safety/config/${configType}/${configId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    alert('Configuration deleted successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to delete configuration'));
                }

            } catch (error) {
                console.error('Error:', error);
                alert('Failed to delete configuration');
            }
        }
    </script>
</body>
</html>