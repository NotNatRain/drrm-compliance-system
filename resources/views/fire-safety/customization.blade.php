@extends('layouts.fire-safety')

@section('title', 'Customization - Fire Safety')

@section('styles')
    <style>
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

        .sortable-handle {
            cursor: move;
            color: #6c757d;
            display: none; /* Hide sortable handles as requested */
        }

        .card-header .toggle-icon {
            cursor: pointer;
            transition: transform 0.3s;
            margin-right: 10px;
        }

        .card-collapsed .card-body {
            display: none;
        }

        .card-collapsed .toggle-icon {
            transform: rotate(-90deg);
        }
    </style>
@endsection

@section('page_title')
    @if(auth()->user()->role === 'admin')
        System Customization
    @elseif(auth()->user()->role === 'viewer')
        School Info
    @else
        Update School Info
    @endif
@endsection

@section('content')
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
                                            id="backup-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#backup-tab-pane"
                                            type="button"
                                            role="tab"
                                            aria-controls="backup-tab-pane"
                                            aria-selected="false">
                                        <i class="fas fa-database me-2"></i> Backup &amp; Restore
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
                                <div class="d-flex flex-wrap gap-1">
                                    <button class="btn btn-primary btn-sm flex-grow-1" data-bs-toggle="modal" data-bs-target="#addSchoolModal">
                                        <i class="fas fa-plus me-1"></i> Add
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary flex-grow-1" type="button" onclick="openSchoolHistoryModal()">
                                        <i class="fas fa-history me-1"></i> History
                                    </button>
                                </div>
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
                                                <th>Last Inspection</th>
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
                                                    @if($school->status === 'passed')
                                                        <span class="badge bg-success">PASSED</span>
                                                    @elseif($school->status === 'unconfigured')
                                                        <span class="badge bg-secondary">UNCONFIGURED</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">ONGOING IMPROVEMENT</span>
                                                    @endif
                                                </td>
                                                <td>{{ $school->buildings_count ?? 0 }}</td>
                                                <td>{{ $school->last_inspection_date ? \Carbon\Carbon::parse($school->last_inspection_date)->format('M d, Y') : 'Never' }}</td>
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
                                        <div class="progress-bar bg-success" style="width: {{ $schools->where('status', 'passed')->count() / max(1, $schools->count()) * 100 }}%" title="Passed">
                                            {{ $schools->where('status', 'passed')->count() }}
                                        </div>
                                        <div class="progress-bar bg-warning text-dark" style="width: {{ $schools->where('status', 'warning')->count() / max(1, $schools->count()) * 100 }}%" title="Ongoing Improvement">
                                            {{ $schools->where('status', 'warning')->count() }}
                                        </div>
                                        <div class="progress-bar bg-secondary" style="width: {{ $schools->where('status', 'unconfigured')->count() / max(1, $schools->count()) * 100 }}%" title="Unconfigured">
                                            {{ $schools->where('status', 'unconfigured')->count() }}
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-1 small text-muted">
                                        <span><i class="fas fa-square text-success me-1"></i> Passed</span>
                                        <span><i class="fas fa-square text-warning me-1"></i> Ongoing</span>
                                        <span><i class="fas fa-square text-secondary me-1"></i> Unconfigured</span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <h6>Quick Actions</h6>
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-sm btn-success" onclick="exportSchoolsData()">
                                            <i class="fas fa-file-export me-2"></i> Export Schools Data
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
                <div class="row align-items-start">
                    <!-- Building Types -->
                    <div class="col-md-6 mb-4">
                        <div class="card dashboard-card card-collapsed" id="building-types-card">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-chevron-down toggle-icon" onclick="toggleDivision(this, 'building-types-card')"></i>
                                    <i class="fas fa-building me-2"></i> Building Types
                                </h6>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addBuildingTypeModal">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="buildingTypesList" class="sortable-list row row-cols-1 row-cols-md-2 g-3">
                                    @foreach($buildingTypes as $type)
                                    <div class="col">
                                        <div class="config-item h-100 mb-0" data-id="{{ $type->id }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $type->name }}</strong>
                                                    <div class="text-muted small">{{ $type->description }}</div>
                                                </div>
                                                <div class="d-flex">
                                                    <span class="badge config-badge {{ $type->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $type->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <button class="btn btn-sm btn-outline-primary edit-config-btn"
                                                        data-id="{{ $type->id }}"
                                                        data-type="building_type"
                                                        data-name="{{ $type->name }}"
                                                        data-description="{{ $type->description }}"
                                                        data-is-active="{{ $type->is_active }}"
                                                        data-min-floors="{{ $type->min_floors ?? '' }}"
                                                        data-total-rooms="{{ $type->total_rooms ?? '' }}">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alarm Configuration: General Status -->
                    <div class="col-md-6 mb-4">
                        <div class="card dashboard-card card-collapsed mb-4" id="general-alarm-status-card">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-chevron-down toggle-icon" onclick="toggleDivision(this, 'general-alarm-status-card')"></i>
                                    <i class="fas fa-list-ul me-2"></i> General Alarm Status
                                </h6>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#addAlarmStatusModal">
                                    <i class="fas fa-plus"></i> Add Status
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="generalAlarmStatusesList" class="row row-cols-1 row-cols-md-2 g-3">
                                    @php
                                        $generalStatuses = $alarmStatusesByType->get("", collect());
                                        if ($generalStatuses->isEmpty()) $generalStatuses = $alarmStatusesByType->get(null, collect());
                                    @endphp
                                    @foreach($generalStatuses as $status)
                                    <div class="col">
                                        <div class="config-item h-100 mb-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong>{{ $status->name }}</strong>
                                                <span class="badge config-badge {{ $status->color_class ?? 'bg-secondary' }}">General</span>
                                            </div>
                                            <div class="mt-2 text-end">
                                                <button class="btn btn-sm btn-outline-primary edit-config-btn"
                                                        data-id="{{ $status->id }}"
                                                        data-type="alarm_status"
                                                        data-name="{{ $status->name }}"
                                                        data-description="{{ $status->description }}"
                                                        data-color-class="{{ $status->color_class ?? 'bg-secondary' }}">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="card dashboard-card card-collapsed" id="alarm-types-specific-card">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-chevron-down toggle-icon" onclick="toggleDivision(this, 'alarm-types-specific-card')"></i>
                                    <i class="fas fa-bell me-2"></i> Alarm Types &amp; Specific Statuses
                                </h6>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addAlarmTypeModal">
                                    <i class="fas fa-plus"></i> Add Type
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="alarmTypesList" class="row row-cols-1 row-cols-md-2 g-3">
                                @foreach($alarmTypes as $type)
                                    <div class="col">
                                        @php
                                            $specificStatuses = $alarmStatusesByType->get($type->id, collect());
                                        @endphp
                                        <div class="config-item h-100 mb-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong>{{ $type->name }}</strong>
                                                <span class="badge config-badge {{ $type->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $type->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                            @if($specificStatuses->isNotEmpty())
                                            <ul class="list-unstyled small mb-2 ms-3 mt-1">
                                                @foreach($specificStatuses as $status)
                                                <li><span class="badge {{ $status->color_class ?? 'bg-secondary' }} me-1">{{ $status->name }}</span></li>
                                                @endforeach
                                            </ul>
                                            @endif
                                            <div class="mt-2 text-end">
                                                <button class="btn btn-sm btn-outline-primary edit-config-btn"
                                                        data-id="{{ $type->id }}"
                                                        data-type="alarm_type"
                                                        data-name="{{ $type->name }}"
                                                        data-is-active="{{ $type->is_active }}"
                                                        data-statuses="{{ $specificStatuses->toJson() }}">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fire Extinguisher Configuration -->
                    <div class="col-md-6 mb-4">
                        <div class="card dashboard-card card-collapsed" id="extinguisher-config-card">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-chevron-down toggle-icon" onclick="toggleDivision(this, 'extinguisher-config-card')"></i>
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
                                    <div id="extinguisherTypesList" class="row row-cols-1 row-cols-md-2 g-3">
                                        @foreach($extinguisherTypes as $type)
                                        <div class="col">
                                            <div class="config-item h-100 mb-0">
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
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div>
                                    <h6>Extinguisher Status</h6>
                                    <div id="extinguisherStatusList" class="row row-cols-1 row-cols-md-2 g-3">
                                        @foreach($extinguisherStatuses as $status)
                                        <div class="col">
                                            <div class="config-item h-100 mb-0">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>{{ $status->name }}</strong>
                                                        @php
                                                            $statusRange = match(trim($status->name)) {
                                                                'Active' => '70-100',
                                                                'For Preventive Maintenance' => '20-69',
                                                                'Used' => '0-19',
                                                                'Used (Refill Request Needed)' => '0-19',
                                                                'Missing' => '0-100',
                                                                'For Purchased' => '0-100',
                                                                'Decommissioned' => '0-100',
                                                                default => null
                                                            };
                                                        @endphp
                                                        <div class="small text-muted">{{ $statusRange ? 'Accuracy: ' . $statusRange . '%' : 'Pressure: —' }}</div>
                                                    </div>
                                                    <span class="badge config-badge {{ $status->color_class ?? 'bg-secondary' }}">{{ $status->category ?? '—' }}</span>
                                                </div>
                                                <div class="mt-2">
                                                    <button class="btn btn-sm btn-outline-primary edit-config-btn"
                                                            data-id="{{ $status->id }}"
                                                            data-type="extinguisher_status"
                                                            data-name="{{ $status->name }}"
                                                            data-description="{{ $status->description }}"
                                                            data-pressure-min="{{ $status->pressure_min ?? '' }}"
                                                            data-pressure-max="{{ $status->pressure_max ?? '' }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Safety Features -->
                    <div class="col-md-6 mb-4">
                        <div class="card dashboard-card card-collapsed" id="safety-features-card">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-chevron-down toggle-icon" onclick="toggleDivision(this, 'safety-features-card')"></i>
                                    <i class="fas fa-shield-alt me-2"></i> Safety Features
                                </h6>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addSafetyFeatureModal">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="safetyFeaturesList" class="sortable-list row row-cols-1 row-cols-md-2 g-3">
                                    @foreach($safetyFeatures as $feature)
                                    <div class="col">
                                        <div class="config-item h-100 mb-0" data-id="{{ $feature->id }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $feature->name }}</strong>
                                                    <div class="text-muted small">{{ $feature->description }}</div>
                                                </div>
                                                <div class="d-flex">
                                                    <span class="badge config-badge bg-info">{{ $feature->category }}</span>
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
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Room Types & Calculated Priority -->
                    <div class="col-md-6 mb-4">
                        <div class="card dashboard-card card-collapsed" id="room-types-card">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-chevron-down toggle-icon" onclick="toggleDivision(this, 'room-types-card')"></i>
                                    <i class="fas fa-door-open me-2"></i> Room Types
                                </h6>
                                <div>
                                    <button class="btn btn-sm btn-info me-2" data-bs-toggle="modal" data-bs-target="#addCalculatedPriorityModal">
                                        <i class="fas fa-plus"></i> Add Priority
                                    </button>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomTypeModal">
                                        <i class="fas fa-plus"></i> Add Room Type
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <h6>Calculated Priority</h6>
                                    <div id="calculatedPriorityList" class="row row-cols-1 row-cols-md-2 g-3">
                                        @foreach(($calculatedPriorities ?? collect()) as $p)
                                        <div class="col">
                                            <div class="config-item h-100 mb-0">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>{{ $p->name }}</strong>
                                                        <div class="small text-muted">Max covered rooms: {{ $p->max_rooms_covered ?? '—' }} (max 5)</div>
                                                        <div class="small text-muted">Required extinguishers: {{ $p->required_extinguishers ?? 1 }}</div>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <button class="btn btn-sm btn-outline-primary edit-config-btn"
                                                            data-id="{{ $p->id }}"
                                                            data-type="calculated_priority"
                                                            data-name="{{ $p->name }}"
                                                            data-description="{{ $p->description }}"
                                                            data-max-rooms="{{ $p->max_rooms_covered ?? '' }}"
                                                            data-required-extinguishers="{{ $p->required_extinguishers ?? 1 }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div>
                                    <h6>Room Types</h6>
                                    <div id="roomTypesList" class="row row-cols-1 row-cols-md-2 g-3">
                                        @foreach(($roomTypes ?? collect()) as $rt)
                                        <div class="col">
                                            @php $p = ($calculatedPriorities ?? collect())->firstWhere('id', $rt->parent_id); @endphp
                                            <div class="config-item h-100 mb-0">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>{{ $rt->name }}</strong>
                                                        <div class="small text-muted">
                                                            Priority: {{ $p->name ?? '—' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <button class="btn btn-sm btn-outline-primary edit-config-btn"
                                                            data-id="{{ $rt->id }}"
                                                            data-type="room_type"
                                                            data-name="{{ $rt->name }}"
                                                            data-description="{{ $rt->description }}"
                                                            data-parent-id="{{ $rt->parent_id ?? '' }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- School Inspection Checklist -->
                    <div class="col-md-6 mb-4" id="inspection-checklist-container">
                        <div class="card dashboard-card card-collapsed" id="inspection-checklist-card">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-chevron-down toggle-icon" onclick="toggleDivision(this, 'inspection-checklist-card')"></i>
                                    <i class="fas fa-tasks me-2"></i> Inspection Checklist
                                </h6>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addInspectionChecklistModal">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="inspectionChecklistList" class="sortable-list row row-cols-1 row-cols-md-2 g-3">
                                    @foreach($inspectionChecklists as $item)
                                    <div class="col">
                                        <div class="config-item h-100 mb-0" data-id="{{ $item->id }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $item->name }}</strong>
                                                    <div class="text-muted small">{{ $item->description }}</div>
                                                </div>
                                                <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                            <div class="mt-2 text-end">
                                                <button class="btn btn-sm btn-outline-primary edit-config-btn"
                                                        data-id="{{ $item->id }}"
                                                        data-type="inspection_checklist"
                                                        data-name="{{ $item->name }}"
                                                        data-description="{{ $item->description }}"
                                                        data-is-active="{{ $item->is_active }}">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger delete-config-btn"
                                                        data-id="{{ $item->id }}"
                                                        data-type="inspection_checklist"
                                                        data-name="{{ $item->name }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Other Observers -->
                    <div class="col-md-6 mb-4" id="other-observers-container">
                        <div class="card dashboard-card card-collapsed" id="other-observers-card">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-chevron-down toggle-icon" onclick="toggleDivision(this, 'other-observers-card')"></i>
                                    <i class="fas fa-users-viewfinder me-2"></i> Other Observers
                                </h6>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addInspectionObserverModal">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="inspectionObserverList" class="sortable-list row row-cols-1 row-cols-md-2 g-3">
                                    @foreach($inspectionObservers as $item)
                                    <div class="col">
                                        <div class="config-item h-100 mb-0" data-id="{{ $item->id }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $item->name }}</strong>
                                                    <div class="text-muted small">{{ $item->description }}</div>
                                                </div>
                                            </div>
                                            <div class="mt-2 text-end">
                                                <button class="btn btn-sm btn-outline-primary edit-config-btn"
                                                        data-id="{{ $item->id }}"
                                                        data-type="inspection_observer"
                                                        data-name="{{ $item->name }}"
                                                        data-description="{{ $item->description }}"
                                                        data-is-active="{{ $item->is_active }}">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger delete-config-btn"
                                                        data-id="{{ $item->id }}"
                                                        data-type="inspection_observer"
                                                        data-name="{{ $item->name }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
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

            <!-- Backup & Restore Tab -->
            <div class="tab-pane fade" id="backup-tab-pane">
                <div class="row">
                    <div class="col-lg-10">
                        <div class="card dashboard-card mb-4">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-database me-2"></i> Backup &amp; Restore (Fire Safety)
                                </h6>
                                <button class="btn btn-sm btn-primary" type="button" onclick="createFireSafetyBackup()">
                                    <i class="fas fa-file-export me-1"></i> Create Backup
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning small">
                                    Restoring a backup will overwrite the current Fire Safety data in the database.
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Backup file</th>
                                                <th class="text-end">Size</th>
                                                <th class="text-end">Last modified</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="fireSafetyBackupsTbody">
                                            <tr><td colspan="4" class="text-muted text-center">Open this tab to load backups…</td></tr>
                                        </tbody>
                                    </table>
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
                                           value="{{ auth()->user()->school->school_name ?? '' }}" required
                                           {{ auth()->user()->role === 'viewer' ? 'readonly' : '' }}>
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
                                <textarea class="form-control" name="address" rows="3" required
                                          {{ auth()->user()->role === 'viewer' ? 'readonly' : '' }}>{{ auth()->user()->school->address ?? '' }}</textarea>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">School Head *</label>
                                    <input type="text" class="form-control" name="school_head"
                                           value="{{ auth()->user()->school->school_head ?? '' }}" required
                                           {{ auth()->user()->role === 'viewer' ? 'readonly' : '' }}>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">DRRM Coordinator *</label>
                                    <input type="text" class="form-control" name="school_drrm_coordinator"
                                           value="{{ auth()->user()->school->school_drrm_coordinator ?? '' }}" required
                                           {{ auth()->user()->role === 'viewer' ? 'readonly' : '' }}>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">School Head Contact Number</label>
                                    <input type="text" class="form-control" name="school_head_contact"
                                           value="{{ auth()->user()->school->school_head_contact ?? '' }}"
                                           {{ auth()->user()->role === 'viewer' ? 'readonly' : '' }}>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">DRRM Coordinator Contact Number</label>
                                    <input type="text" class="form-control" name="drrm_coordinator_contact"
                                           value="{{ auth()->user()->school->drrm_coordinator_contact ?? '' }}"
                                           {{ auth()->user()->role === 'viewer' ? 'readonly' : '' }}>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="email"
                                       value="{{ auth()->user()->school->email ?? '' }}"
                                       {{ auth()->user()->role === 'viewer' ? 'readonly' : '' }}>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Only school administrators can update this information. Changes will be reviewed by system administrators.
                            </div>

                            <div class="d-flex justify-content-end">
                                @if(auth()->user()->role !== 'viewer')
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Update School Information
                                </button>
                                @endif
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
                            <label class="form-label">Initial Status</label>
                            <input type="text" class="form-control bg-light" value="Unconfigured" readonly disabled>
                            <input type="hidden" name="status" value="unconfigured">
                            <small class="text-muted">Status is automatically determined based on configuration.</small>
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
                            <input type="text" class="form-control" id="editSchoolIdDisplay" required readonly>
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
                            <label class="form-label">Status (Dynamic)</label>
                            <select class="form-control bg-light" name="status" id="editSchoolStatus" readonly style="pointer-events: none;">
                                <option value="unconfigured">Unconfigured</option>
                                <option value="passed">Passed</option>
                                <option value="warning">Ongoing Improvement</option>
                            </select>
                            <small class="text-muted">This status is determined by the system based on safety configurations.</small>
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
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Limit number of floors (Optional)</label>
                                <input type="number" class="form-control" name="min_floors" id="addBuildingTypeMinFloors" min="0" placeholder="e.g. 0 or leave blank">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Limit number of rooms (Optional)</label>
                                <input type="number" class="form-control" name="total_rooms" id="addBuildingTypeTotalRooms" min="0" placeholder="e.g. 0 or leave blank">
                            </div>
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

    <!-- Add Safety Feature Modal -->
    <div class="modal fade" id="addSafetyFeatureModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title"><i class="fas fa-shield-alt me-2"></i> Add Safety Feature</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addSafetyFeatureForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Name *</label>
                            <input type="text" class="form-control" name="name" required placeholder="e.g. Fire Department Break-in Tools">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="2" placeholder="Optional description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" class="form-control" name="category" placeholder="e.g. Equipment">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveSafetyFeature()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Calculated Priority Modal -->
    <div class="modal fade" id="addCalculatedPriorityModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title"><i class="fas fa-calculator me-2"></i> Add Calculated Priority</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addCalculatedPriorityForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Priority name *</label>
                            <input type="text" class="form-control" name="name" required placeholder="e.g. Shared Coverage (Up to 3 Classrooms)">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Number of rooms covered *</label>
                            <input type="number" class="form-control" name="max_rooms_covered" required min="1" max="5" placeholder="1 to 5">
                            <small class="text-muted">Maximum allowed is 5 rooms.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Required calculated priority extinguisher *</label>
                            <input type="number" class="form-control" name="required_extinguishers" required min="1" max="5" value="1" placeholder="How many extinguishers are required for this priority?">
                            <small class="text-muted">Set how many extinguishers rooms under this calculated priority are allowed to host. Default is 1.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="2"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveCalculatedPriority()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Room Type Modal -->
    <div class="modal fade" id="addRoomTypeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title"><i class="fas fa-door-open me-2"></i> Add Room Type</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addRoomTypeForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Room type name *</label>
                            <input type="text" class="form-control" name="name" required placeholder="e.g. Computer Lab">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Calculated Priority *</label>
                            <select class="form-control" name="parent_id" required>
                                <option value="">Select priority</option>
                                @foreach(($calculatedPriorities ?? collect()) as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} (Up to {{ $p->max_rooms_covered ?? '—' }} rooms, {{ $p->required_extinguishers ?? 1 }} extinguisher(s))</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveRoomType()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Extinguisher Type Modal -->
    <div class="modal fade" id="addExtinguisherTypeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title"><i class="fas fa-fire-extinguisher me-2"></i> Add Extinguisher Type</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addExtinguisherTypeForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Type name *</label>
                            <input type="text" class="form-control" name="name" required placeholder="e.g. Dry Chemical (ABC)">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Code</label>
                            <input type="text" class="form-control" name="code" placeholder="e.g. ABC">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="2"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveExtinguisherType()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Extinguisher Status Modal -->
    <div class="modal fade" id="addExtinguisherStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title"><i class="fas fa-fire-extinguisher me-2"></i> Add Extinguisher Status</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addExtinguisherStatusForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Status name *</label>
                            <input type="text" class="form-control" name="name" required placeholder="e.g. Active">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pressure level range (psi) *</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" step="0.01" min="0" class="form-control" name="pressure_min" id="extStatusPressureMin" required placeholder="Min">
                                </div>
                                <div class="col-6">
                                    <input type="number" step="0.01" min="0" class="form-control" name="pressure_max" id="extStatusPressureMax" required placeholder="Max">
                                </div>
                            </div>
                            <small class="text-muted">Set the pressure range for this status (e.g. 100–125 psi).</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveExtinguisherStatus()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Alarm Type Modal -->
    <div class="modal fade" id="addAlarmTypeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title"><i class="fas fa-bell me-2"></i> Add Alarm Type</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addAlarmTypeForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Alarm type name *</label>
                            <input type="text" class="form-control" name="name" id="alarmTypeName" required placeholder="e.g. Smoke Detector">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Statuses for this alarm type * <small class="text-muted">(type a status, then another field will appear)</small></label>
                            <div id="alarmTypeStatusesContainer">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control alarm-status-input" name="statuses[]" placeholder="e.g. Functional">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveAlarmType()">
                        <i class="fas fa-save me-2"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Alarm Status Modal (General) -->
    <div class="modal fade" id="addAlarmStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i> Add General Alarm Status</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addAlarmStatusForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Status Name *</label>
                            <input type="text" class="form-control" name="name" required placeholder="e.g. Active">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Color Coding</label>
                            <select class="form-control" name="color_class">
                                <option value="bg-success">Green (Functional)</option>
                                <option value="bg-danger">Red (Non-Functional)</option>
                                <option value="bg-warning text-dark">Yellow (Maintenance)</option>
                                <option value="bg-secondary" selected>Gray (Neutral)</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveAlarmStatus()">Save Status</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="schoolHistoryModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #6c757d; color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-history me-2"></i> School's History (Removed Schools)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm" id="schoolHistoryTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Date Removed</th>
                                    <th>School</th>
                                    <th>DRRM Coordinator</th>
                                    <th>School Head</th>
                                    <th>Summary</th>
                                    <th>Evac. Plan Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data populated via JS -->
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

    <!-- Add Inspection Checklist Modal -->
    <div class="modal fade" id="addInspectionChecklistModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i> Add Checklist Item</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addInspectionChecklistForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Item / Task Name *</label>
                            <input type="text" class="form-control" name="name" required placeholder="e.g. Fire Hydrants Checked">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description (Optional)</label>
                            <textarea class="form-control" name="description" rows="2"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveInspectionConfig('inspection_checklist')">Save Item</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Inspection Observer Modal -->
    <div class="modal fade" id="addInspectionObserverModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i> Add Observer Type</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addInspectionObserverForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Observer Type Name *</label>
                            <input type="text" class="form-control" name="name" required placeholder="e.g. BFP Representative">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description (Optional)</label>
                            <textarea class="form-control" name="description" rows="2"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveInspectionConfig('inspection_observer')">Save Type</button>
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
    @else
        <!-- Contributor View: Only School Management Tab for their assigned school -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card dashboard-card mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="fas fa-school me-2"></i> My School Information
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($schools->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-school fa-3x text-muted mb-3"></i>
                                <p>You have not created or been assigned to a school yet.</p>
                                <a href="{{ route('fire-safety.dashboard') }}" class="btn btn-primary">Go to Dashboard to Setup</a>
                            </div>
                        @else
                            @php $mySchool = $schools->first(); @endphp
                            <div class="table-responsive">
                                <table class="table align-middle compact-mobile-table">
                                    <thead>
                                        <tr>
                                            <th>School Name</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>{{ $mySchool->school_name }}</strong>
                                                <div class="text-muted small">{{ $mySchool->address }}</div>
                                            </td>
                                            <td>
                                                @if($mySchool->status === 'passed')
                                                    <span class="badge bg-success">PASSED</span>
                                                @elseif($mySchool->status === 'unconfigured')
                                                    <span class="badge bg-secondary">UNCONFIGURED</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">ONGOING IMPROVEMENT</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-primary btn-sm edit-school-btn"
                                                        data-school-id="{{ $mySchool->id }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editSchoolModal">
                                                    <i class="fas fa-edit me-1"></i> Update Info
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>

    <script>
        // Server-provided configuration lists for dynamic edit forms
        window._calculatedPriorities = @json($calculatedPriorities ?? []);
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

            // Load backups list when Backup tab is opened
            document.getElementById('backup-tab')?.addEventListener('shown.bs.tab', function () {
                loadFireSafetyBackups();
            });

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
                    const configMinFloors = this.getAttribute('data-min-floors');
                    const configTotalRooms = this.getAttribute('data-total-rooms');
                    const configPressureMin = this.getAttribute('data-pressure-min');
                    const configPressureMax = this.getAttribute('data-pressure-max');
                    const configCategory = this.getAttribute('data-category');
                    const configParentId = this.getAttribute('data-parent-id');
                    const configMaxRooms = this.getAttribute('data-max-rooms');
                    const configRequiredExtinguishers = this.getAttribute('data-required-extinguishers');
                    editConfigItem(
                        configId,
                        configType,
                        configName,
                        configDescription,
                        configIsActive,
                        configMinFloors,
                        configTotalRooms,
                        configPressureMin,
                        configPressureMax,
                        configCategory,
                        configParentId,
                        configMaxRooms,
                        configRequiredExtinguishers
                    );
                });
            });

            // Set up delete config buttons
            document.querySelectorAll('.delete-config-btn').forEach(button => {
                button.addEventListener('click', async function() {
                    const configId = this.getAttribute('data-id');
                    const configType = this.getAttribute('data-type');
                    const configName = this.getAttribute('data-name');

                    if (configType === 'inspection_checklist' || configType === 'inspection_observer') {
                        const result = await Swal.fire({
                            title: 'Are you sure?',
                            text: `This will mark "${configName}" as Inactive. It will still be available for reference but can be hidden in active lists.`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#ffc107',
                            confirmButtonText: 'Yes, mark Inactive'
                        });

                        if (result.isConfirmed) {
                            try {
                                const fd = new FormData();
                                fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                                fd.append('_method', 'PUT');
                                fd.append('is_active', 0);
                                fd.append('name', configName);

                                const resp = await fetch(`/fire-safety/config/${configType}/${configId}`, {
                                    method: 'POST',
                                    headers: { 'Accept': 'application/json' },
                                    body: fd
                                });
                                const data = await resp.json();
                                if (resp.ok && data.success) {
                                    Swal.fire({ icon: 'success', title: 'Marked Inactive', timer: 1500, showConfirmButton: false }).then(() => location.reload());
                                } else {
                                    Swal.fire({ icon: 'error', title: 'Failed', text: data.message || 'Could not update status.' });
                                }
                            } catch (e) {
                                console.error(e);
                                Swal.fire({ icon: 'error', title: 'Error', text: 'System error occurred.' });
                            }
                        }
                    } else {
                        const result = await Swal.fire({
                            title: 'Are you sure?',
                            text: `You are about to delete "${configName}". This action cannot be undone.`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        });

                        if (result.isConfirmed) {
                            deleteConfigItem(configId, configType);
                        }
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
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load school data'
                });
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

                if (response.ok && data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'School added successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    let errorMsg = data.message || 'Failed to add school';
                    if (data.errors) {
                        errorMsg = Object.values(data.errors)[0][0];
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Notice',
                        text: errorMsg
                    });
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'Failed to add school. Please check your connection.'
                });
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

                if (response.ok && data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated',
                        text: 'School updated successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    let errorMsg = data.message || 'Failed to update school';
                    if (data.errors) {
                        errorMsg = Object.values(data.errors)[0][0];
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Notice',
                        text: errorMsg
                    });
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'Failed to update school'
                });
            }
        }

        // Admin: Delete school
        async function deleteSchool(schoolId, schoolName) {
            const result = await Swal.fire({
                title: 'Delete School?',
                html: `
                    <p class="mb-2">You are about to delete "<strong>${schoolName}</strong>".</p>
                    <p class="text-danger small mb-3">
                        This will also delete all associated buildings, alarm systems, fire extinguishers, rooms, and evacuation plans.
                        This action cannot be undone.
                    </p>
                    <div class="mb-2 text-start">
                        <label class="form-label fw-bold small">Confirm with your account password</label>
                        <input type="password" id="swal-delete-password" class="form-control form-control-sm" placeholder="Enter your password to enable deletion">
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                didOpen: () => {
                    const input = document.getElementById('swal-delete-password');
                    const confirmBtn = Swal.getConfirmButton();
                    confirmBtn.disabled = true;
                    input.addEventListener('input', () => {
                        confirmBtn.disabled = input.value.trim().length === 0;
                    });
                },
                preConfirm: () => {
                    const pwd = document.getElementById('swal-delete-password').value.trim();
                    if (!pwd) {
                        Swal.showValidationMessage('Password is required');
                        return false;
                    }
                    return pwd;
                }
            });

            if (!result.isConfirmed) return;

            try {
                const response = await fetch(`/fire-safety/school/${schoolId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ password: result.value })
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire(
                        'Deleted!',
                        'School has been deleted and archived.',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Denied',
                        text: data.message || 'Failed to delete school'
                    });
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'Failed to delete school'
                });
            }
        }

        // Admin: Open School History (removed schools)
        async function openSchoolHistoryModal() {
            const modalEl = document.getElementById('schoolHistoryModal');
            const tbody = document.querySelector('#schoolHistoryTable tbody');
            if (!modalEl || !tbody) return;

            tbody.innerHTML = '<tr><td colspan="6" class="text-center">Loading...</td></tr>';
            const modal = new bootstrap.Modal(modalEl);
            modal.show();

            try {
                const resp = await fetch('/fire-safety/school/history');
                const data = await resp.json();

                if (!resp.ok) {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">' + (data.message || 'Failed to load school history.') + '</td></tr>';
                    return;
                }
                if (!Array.isArray(data) || data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No removed schools found.</td></tr>';
                    return;
                }

                tbody.innerHTML = '';
                data.forEach(item => {
                    const d = item.removed_at ? new Date(item.removed_at).toLocaleString() : 'N/A';
                    const info = item.item_data || {};
                    const summary = `Bldgs: ${info.buildings ?? 0}, Alarms: ${info.alarm_systems ?? 0}, Ext.: ${info.extinguishers ?? 0}`;
                    let evacStatus = info.evacuation_coverage_status || 'unknown';
                    if (evacStatus === 'good') evacStatus = 'Good';
                    else if (evacStatus === 'fair') evacStatus = 'Fair';
                    else if (evacStatus === 'poor') evacStatus = 'Poor';
                    else evacStatus = 'No plans / Unknown';

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${d}</td>
                        <td>
                            <div class="fw-bold">${info.school_name || 'N/A'}</div>
                            <div class="small text-muted">${info.school_code || '—'}</div>
                            <div class="small text-muted text-truncate" style="max-width: 220px;">${info.address || ''}</div>
                        </td>
                        <td>${info.drrm_coordinator || 'N/A'}</td>
                        <td>${info.school_head || 'N/A'}</td>
                        <td>${summary}</td>
                        <td>${evacStatus}</td>
                    `;
                    tbody.appendChild(row);
                });
            } catch (e) {
                console.error(e);
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Failed to load school history.</td></tr>';
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

                if (response.ok && data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Building type added successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Notice',
                        text: data.message || 'Failed to add building type'
                    });
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'Failed to add building type'
                });
            }
        }

        // Add Alarm Type: dynamic status inputs (when user types, add another field)
        document.getElementById('addAlarmTypeModal')?.addEventListener('shown.bs.modal', function() {
            const container = document.getElementById('alarmTypeStatusesContainer');
            if (!container) return;
            container.querySelectorAll('.alarm-status-input').forEach(function(inp) {
                inp.removeEventListener('input', window._alarmStatusInputHandler);
            });
            window._alarmStatusInputHandler = function() {
                const inputs = container.querySelectorAll('.alarm-status-input');
                const last = inputs[inputs.length - 1];
                if (last && last.value.trim() !== '') {
                    const div = document.createElement('div');
                    div.className = 'input-group mb-2';
                    div.innerHTML = '<input type="text" class="form-control alarm-status-input" name="statuses[]" placeholder="e.g. Faulty">';
                    container.appendChild(div);
                    div.querySelector('input').addEventListener('input', window._alarmStatusInputHandler);
                }
            };
            container.querySelectorAll('.alarm-status-input').forEach(function(inp) {
                inp.addEventListener('input', window._alarmStatusInputHandler);
            });
        });

        async function saveSafetyFeature() {
            const form = document.getElementById('addSafetyFeatureForm');
            if (!form || !form.checkValidity()) {
                form.reportValidity();
                return;
            }
            const formData = new FormData(form);
            try {
                const response = await fetch('/fire-safety/config/safety-feature', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    Swal.fire({ icon: 'success', title: 'Saved', text: 'Safety feature added.', timer: 2000, showConfirmButton: false }).then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Notice', text: data.message || 'Failed to add safety feature.' });
                }
            } catch (e) {
                console.error(e);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to add safety feature.' });
            }
        }

        async function saveCalculatedPriority() {
            const form = document.getElementById('addCalculatedPriorityForm');
            if (!form || !form.checkValidity()) {
                form?.reportValidity();
                return;
            }
            const formData = new FormData(form);
            try {
                const response = await fetch('/fire-safety/config/calculated-priority', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    Swal.fire({ icon: 'success', title: 'Saved', text: 'Calculated priority added.', timer: 2000, showConfirmButton: false }).then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Notice', text: data.message || 'Failed to add calculated priority.' });
                }
            } catch (e) {
                console.error(e);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to add calculated priority.' });
            }
        }

        async function saveRoomType() {
            const form = document.getElementById('addRoomTypeForm');
            if (!form || !form.checkValidity()) {
                form?.reportValidity();
                return;
            }
            const formData = new FormData(form);
            try {
                const response = await fetch('/fire-safety/config/room-type', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    Swal.fire({ icon: 'success', title: 'Saved', text: 'Room type added.', timer: 2000, showConfirmButton: false }).then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Notice', text: data.message || 'Failed to add room type.' });
                }
            } catch (e) {
                console.error(e);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to add room type.' });
            }
        }

        // Backup & Restore (Fire Safety)
        async function loadFireSafetyBackups() {
            const tbody = document.getElementById('fireSafetyBackupsTbody');
            if (!tbody) return;
            tbody.innerHTML = '<tr><td colspan="4" class="text-muted text-center">Loading…</td></tr>';

            try {
                const resp = await fetch('/fire-safety/backup/list', { headers: { 'Accept': 'application/json' } });
                const data = await resp.json();
                if (!resp.ok || !data.success) {
                    tbody.innerHTML = `<tr><td colspan="4" class="text-danger text-center">${data.message || 'Failed to load backups.'}</td></tr>`;
                    return;
                }
                const files = data.files || [];
                if (!files.length) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-muted text-center">No backups found.</td></tr>';
                    return;
                }
                tbody.innerHTML = '';
                files.forEach(f => {
                    const tr = document.createElement('tr');
                    const sizeKb = Math.round((f.size || 0) / 1024);
                    const dt = f.last_modified ? new Date(f.last_modified * 1000).toLocaleString() : '—';
                    tr.innerHTML = `
                        <td><code>${f.name}</code></td>
                        <td class="text-end">${sizeKb} KB</td>
                        <td class="text-end">${dt}</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-danger" type="button" onclick="restoreFireSafetyBackup('${f.name.replace(/'/g, "\\'")}')">
                                <i class="fas fa-file-import me-1"></i> Restore
                            </button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            } catch (e) {
                console.error(e);
                tbody.innerHTML = '<tr><td colspan="4" class="text-danger text-center">Failed to load backups.</td></tr>';
            }
        }

        async function createFireSafetyBackup() {
            try {
                const resp = await fetch('/fire-safety/backup/create', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                const data = await resp.json();
                if (!resp.ok || !data.success) {
                    Swal.fire({ icon: 'error', title: 'Backup Failed', text: data.message || 'Failed to create backup.' });
                    return;
                }
                Swal.fire({ icon: 'success', title: 'Backup Created', text: `Saved as ${data.file}` });
                await loadFireSafetyBackups();
            } catch (e) {
                console.error(e);
                Swal.fire({ icon: 'error', title: 'Backup Failed', text: 'Failed to create backup.' });
            }
        }

        async function saveInspectionConfig(type) {
            const formId = type === 'inspection_checklist' ? 'addInspectionChecklistForm' : 'addInspectionObserverForm';
            const form = document.getElementById(formId);
            if (!form || !form.checkValidity()) {
                form?.reportValidity();
                return;
            }
            const formData = new FormData(form);
            try {
                const response = await fetch(`/fire-safety/config/${type}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    Swal.fire({ icon: 'success', title: 'Saved', text: 'Configuration saved.', timer: 2000, showConfirmButton: false }).then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Notice', text: data.message || 'Failed to save configuration.' });
                }
            } catch (e) {
                console.error(e);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to save configuration.' });
            }
        }

        async function restoreFireSafetyBackup(fileName) {
            const first = await Swal.fire({
                title: 'Restore backup?',
                text: `This will overwrite current Fire Safety data using: ${fileName}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Continue'
            });
            if (!first.isConfirmed) return;

            const second = await Swal.fire({
                title: 'Final confirmation',
                html: `<p class="mb-1"><strong>This is your last warning.</strong></p><p class="mb-0">Proceed restoring <code>${fileName}</code>?</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, restore now'
            });
            if (!second.isConfirmed) return;

            try {
                const resp = await fetch('/fire-safety/backup/restore', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ file: fileName })
                });
                const data = await resp.json();
                if (!resp.ok || !data.success) {
                    Swal.fire({ icon: 'error', title: 'Restore Failed', text: data.message || 'Failed to restore backup.' });
                    return;
                }
                Swal.fire({ icon: 'success', title: 'Restored', text: 'Backup restored successfully.' }).then(() => location.reload());
            } catch (e) {
                console.error(e);
                Swal.fire({ icon: 'error', title: 'Restore Failed', text: 'Failed to restore backup.' });
            }
        }

        async function saveExtinguisherType() {
            const form = document.getElementById('addExtinguisherTypeForm');
            if (!form || !form.checkValidity()) {
                form.reportValidity();
                return;
            }
            const formData = new FormData(form);
            try {
                const response = await fetch('/fire-safety/config/extinguisher-type', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    Swal.fire({ icon: 'success', title: 'Saved', text: 'Extinguisher type added.', timer: 2000, showConfirmButton: false }).then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Notice', text: data.message || 'Failed to add type.' });
                }
            } catch (e) {
                console.error(e);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to add extinguisher type.' });
            }
        }

        async function saveExtinguisherStatus() {
            const form = document.getElementById('addExtinguisherStatusForm');
            if (!form || !form.checkValidity()) {
                form.reportValidity();
                return;
            }
            const formData = new FormData(form);
            try {
                const response = await fetch('/fire-safety/config/extinguisher-status', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    Swal.fire({ icon: 'success', title: 'Saved', text: 'Extinguisher status added.', timer: 2000, showConfirmButton: false }).then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Notice', text: data.message || 'Failed to add status.' });
                }
            } catch (e) {
                console.error(e);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to add extinguisher status.' });
            }
        }

        async function saveAlarmStatus() {
            const form = document.getElementById('addAlarmStatusForm');
            if (!form || !form.checkValidity()) {
                form?.reportValidity();
                return;
            }
            const formData = new FormData(form);
            try {
                const response = await fetch('/fire-safety/config/alarm-status', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    Swal.fire({ icon: 'success', title: 'Saved', text: 'General alarm status added.', timer: 2000, showConfirmButton: false }).then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Notice', text: data.message || 'Failed to add status.' });
                }
            } catch (e) {
                console.error(e);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to add alarm status.' });
            }
        }

        async function saveAlarmType() {
            const form = document.getElementById('addAlarmTypeForm');
            if (!form || !form.checkValidity()) {
                form?.reportValidity();
                return;
            }
            const name = document.getElementById('alarmTypeName')?.value?.trim();
            if (!name) {
                Swal.fire({ icon: 'error', title: 'Required', text: 'Alarm type name is required.' });
                return;
            }
            const statusInputs = document.querySelectorAll('#alarmTypeStatusesContainer .alarm-status-input');
            const statuses = Array.from(statusInputs).map(i => i.value.trim()).filter(Boolean);
            if (statuses.length === 0) {
                Swal.fire({ icon: 'error', title: 'Required', text: 'At least one status is required for this alarm type.' });
                return;
            }
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('name', name);
            statuses.forEach(s => formData.append('statuses[]', s));
            try {
                const response = await fetch('/fire-safety/config/alarm-type', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: formData
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    Swal.fire({ icon: 'success', title: 'Saved', text: 'Alarm type added successfully!', timer: 2000, showConfirmButton: false }).then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Notice', text: data.message || 'Failed to add alarm type.' });
                }
            } catch (e) {
                console.error(e);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to add alarm type.' });
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

                if (response.ok && data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Created!',
                        text: 'User created successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    let errorMsg = data.message || 'Failed to create user';
                    if (data.errors) {
                        errorMsg = Object.values(data.errors)[0][0];
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Notice',
                        text: errorMsg
                    });
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'Failed to create user'
                });
            }
        }

        // Admin: Edit user
        async function editUser(userId) {
            // Load user data and show edit modal
            try {
                const response = await fetch(`/fire-safety/users/${userId}`);
                const user = await response.json();

                // You need to create an edit user modal
                Swal.fire({
                    title: 'Edit User',
                    text: `Edit user functionality for "${user.name}" is coming soon.`,
                    icon: 'info'
                });

            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load user data'
                });
            }
        }

        // Admin: Delete user
        async function deleteUser(userId, userName) {
            const result = await Swal.fire({
                title: 'Delete User?',
                text: `Are you sure you want to delete user "${userName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete user'
            });

            if (!result.isConfirmed) return;

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
                    Swal.fire(
                        'Deleted!',
                        'User has been deleted.',
                        'success'
                    );
                    loadUsers();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Denied',
                        text: 'Error: ' + (data.message || 'Failed to delete user')
                    });
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'Failed to delete user'
                });
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Information Updated',
                        text: 'School information updated successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Denied',
                        text: 'Error: ' + (data.message || 'Failed to update school information')
                    });
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'Failed to update school information'
                });
            }
        }

        // Export schools data
        async function exportSchoolsData() {
            const result = await Swal.fire({
                title: 'Export Data',
                text: 'Export all schools data to CSV?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, export'
            });

            if (result.isConfirmed) {
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Export Failed',
                        text: 'Failed to export data'
                    });
                }
            }
        }

        // Edit config item
        function editConfigItem(id, type, name, description, isActive, minFloors, totalRooms, pressureMin, pressureMax, category, parentId, maxRoomsCovered, requiredExtinguishers) {
            const isBuildingType = (type === 'building_type');
            const isExtinguisherStatus = (type === 'extinguisher_status');
            const isSafetyFeature = (type === 'safety_feature');
            const isCalculatedPriority = (type === 'calculated_priority');
            const isRoomType = (type === 'room_type');
            const isInspectionChecklist = (type === 'inspection_checklist');
            const isInspectionObserver = (type === 'inspection_observer');
            const isAlarmType = (type === 'alarm_type');
            const isAlarmStatus = (type === 'alarm_status');

            let alarmStatusesHtml = '';
            if (isAlarmType) {
                const statuses = JSON.parse(event.currentTarget.dataset.statuses || '[]');
                alarmStatusesHtml = `
                    <div class="mt-4 border-top pt-3">
                        <h6>Manage Statuses</h6>
                        <small class="text-muted d-block mb-3">Add or edit statuses for this alarm type. Deletion is not allowed.</small>
                        <div id="alarmEditStatusesList">
                            ${statuses.map((s, index) => `
                                <div class="config-item p-2 mb-2 bg-light">
                                    <input type="hidden" name="statuses[${index}][id]" value="${s.id}">
                                    <div class="row g-2">
                                        <div class="col-8">
                                            <input type="text" class="form-control form-control-sm" name="statuses[${index}][name]" value="${s.name}" required>
                                        </div>
                                        <div class="col-4">
                                            <select class="form-control form-control-sm" name="statuses[${index}][color_class]">
                                                <option value="bg-success" ${s.color_class === 'bg-success' ? 'selected' : ''}>Green (Functional)</option>
                                                <option value="bg-danger" ${s.color_class === 'bg-danger' ? 'selected' : ''}>Red (Non-Functional)</option>
                                                <option value="bg-warning text-dark" ${s.color_class === 'bg-warning text-dark' ? 'selected' : ''}>Yellow (Maintenance)</option>
                                                <option value="bg-secondary" ${s.color_class === 'bg-secondary' ? 'selected' : ''}>Gray</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-info w-100" onclick="addNewAlarmStatusToModal()">
                            <i class="fas fa-plus me-1"></i> Add More Status
                        </button>
                    </div>
                `;

                // Expose helper to add new status fields
                window.addNewAlarmStatusToModal = function() {
                    const list = document.getElementById('alarmEditStatusesList');
                    const index = list.children.length;
                    const html = `
                        <div class="config-item p-2 mb-2 bg-light">
                            <div class="row g-2">
                                <div class="col-8">
                                    <input type="text" class="form-control form-control-sm" name="statuses[${index}][name]" placeholder="New Status Name" required>
                                </div>
                                <div class="col-4">
                                    <select class="form-control form-control-sm" name="statuses[${index}][color_class]">
                                        <option value="bg-success">Green</option>
                                        <option value="bg-danger">Red</option>
                                        <option value="bg-warning">Yellow</option>
                                        <option value="bg-secondary" selected>Gray</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    `;
                    list.insertAdjacentHTML('beforeend', html);
                };
            }
            const alarmStatusColorHtml = isAlarmStatus ? `
                                    <div class="mb-3">
                                        <label class="form-label">Status Color</label>
                                        <select class="form-control" name="color_class">
                                            <option value="bg-success" ${event.currentTarget.dataset.colorClass === 'bg-success' ? 'selected' : ''}>Green (Functional)</option>
                                            <option value="bg-danger" ${event.currentTarget.dataset.colorClass === 'bg-danger' ? 'selected' : ''}>Red (Non-Functional)</option>
                                            <option value="bg-warning text-dark" ${event.currentTarget.dataset.colorClass === 'bg-warning text-dark' ? 'selected' : ''}>Yellow (Maintenance)</option>
                                            <option value="bg-secondary" ${event.currentTarget.dataset.colorClass === 'bg-secondary' ? 'selected' : ''}>Gray</option>
                                        </select>
                                    </div>
                                    ` : '';
            const pressureRangeHtml = isExtinguisherStatus ? `
                                    <div class="mb-3">
                                        <label class="form-label">Pressure level range (psi) *</label>
                                        <div class="row">
                                            <div class="col-6">
                                                <input type="number" step="0.01" min="0" class="form-control" name="pressure_min" placeholder="Min" value="${pressureMin !== null && pressureMin !== undefined && pressureMin !== '' ? pressureMin : ''}">
                                            </div>
                                            <div class="col-6">
                                                <input type="number" step="0.01" min="0" class="form-control" name="pressure_max" placeholder="Max" value="${pressureMax !== null && pressureMax !== undefined && pressureMax !== '' ? pressureMax : ''}">
                                            </div>
                                        </div>
                                    </div>
                                    ` : '';
            const buildingLimitsHtml = isBuildingType ? `
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Limit number of floors (Optional)</label>
                                            <input type="number" class="form-control" name="min_floors" min="0" placeholder="e.g. 0 or leave blank" value="${minFloors !== null && minFloors !== undefined && minFloors !== '' ? minFloors : ''}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Limit number of rooms (Optional)</label>
                                            <input type="number" class="form-control" name="total_rooms" min="0" placeholder="e.g. 0 or leave blank" value="${totalRooms !== null && totalRooms !== undefined && totalRooms !== '' ? totalRooms : ''}">
                                        </div>
                                    </div>
                                    ` : '';
            const calculatedPriorityHtml = isCalculatedPriority ? `
                                    <div class="mb-3">
                                        <label class="form-label">Number of rooms covered *</label>
                                        <input type="number" class="form-control" name="max_rooms_covered" min="1" max="5" required value="${maxRoomsCovered !== null && maxRoomsCovered !== undefined && maxRoomsCovered !== '' ? maxRoomsCovered : ''}">
                                        <small class="text-muted">Maximum allowed is 5 rooms.</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Required calculated priority extinguisher *</label>
                                        <input type="number" class="form-control" name="required_extinguishers" min="1" max="5" required value="${requiredExtinguishers !== null && requiredExtinguishers !== undefined && requiredExtinguishers !== '' ? requiredExtinguishers : '1'}">
                                        <small class="text-muted">Set how many extinguishers rooms under this calculated priority are allowed to host. Default is 1.</small>
                                    </div>
                                    ` : '';
            const roomTypePriorityHtml = isRoomType ? (() => {
                const list = Array.isArray(window._calculatedPriorities) ? window._calculatedPriorities : [];
                const selected = (parentId !== null && parentId !== undefined) ? String(parentId) : '';
                const opts = list.map(p => {
                    const idStr = String(p.id);
                    const max = p.max_rooms_covered ?? '';
                    const required = p.required_extinguishers ?? 1;
                    const label = `${p.name}${max ? ' (Up to ' + max + ' rooms' : ' ('}${max ? ', ' : ''}${required} extinguisher(s))`;
                    return `<option value="${idStr}" ${idStr === selected ? 'selected' : ''}>${label}</option>`;
                }).join('');
                return `
                                    <div class="mb-3">
                                        <label class="form-label">Calculated Priority *</label>
                                        <select class="form-control" name="parent_id" required>
                                            <option value="">Select priority</option>
                                            ${opts}
                                        </select>
                                    </div>
                `;
            })() : '';
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
                                    ${calculatedPriorityHtml}
                                    ${roomTypePriorityHtml}
                                    ${buildingLimitsHtml}
                                    ${pressureRangeHtml}
                                    ${alarmStatusColorHtml}
                                    ${isSafetyFeature && category !== null && category !== undefined ? `
                                    <div class="mb-3">
                                        <label class="form-label">Category</label>
                                        <input type="text" class="form-control" name="category" value="${category || ''}" placeholder="e.g. Equipment">
                                    </div>
                                    ` : ''}
                                    ${isActive !== null && isActive !== undefined ? `
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="editConfigActive" ${isActive === '1' ? 'checked' : ''}>
                                            <label class="form-check-label" for="editConfigActive">Active</label>
                                        </div>
                                    </div>
                                    ` : ''}
                                    ${alarmStatusesHtml}
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

                if (response.ok && data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'Configuration updated successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Notice',
                        text: data.message || 'Failed to update configuration'
                    });
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'Failed to update configuration'
                });
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

                if (response.ok && data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Configuration deleted successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Notice',
                        text: data.message || 'Failed to delete configuration'
                    });
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'Failed to delete configuration'
                });
            }
        }
        // Initialize card states
        document.addEventListener('DOMContentLoaded', function() {
            const cardStates = JSON.parse(localStorage.getItem('fireSafetyCardStates') || '{}');
            Object.keys(cardStates).forEach(cardId => {
                const card = document.getElementById(cardId);
                if (card && cardStates[cardId] === 'collapsed') {
                    card.classList.add('card-collapsed');
                }
            });
        });

        // Toggle division
        function toggleDivision(icon, cardId) {
            const card = icon.closest('.card');
            card.id = cardId; // Ensure card has ID for persistence
            card.classList.toggle('card-collapsed');

            const cardStates = JSON.parse(localStorage.getItem('fireSafetyCardStates') || '{}');
            cardStates[cardId] = card.classList.contains('card-collapsed') ? 'collapsed' : 'expanded';
            localStorage.setItem('fireSafetyCardStates', JSON.stringify(cardStates));
        }
    </script>
@endsection
