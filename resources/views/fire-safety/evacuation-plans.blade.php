@extends('layouts.fire-safety')

@section('title', 'Evacuation Plans - Fire Safety')
<style>
    :root {
        --fire-red: #A8191F;
        --fire-dark-red: #8A1217;
        --fire-light-red: #F8D7DA;
        --charcoal: #36454F;       /* ← ADD THIS */
        --dark-charcoal: #2C3E50;  /* ← ADD THIS */
    }
    .top-nav {
        background: linear-gradient(135deg, var(--fire-red) 0%, var(--charcoal) 100%);
    }
    .sidebar {
        background: linear-gradient(180deg, var(--fire-red) 0%, var(--dark-charcoal) 100%);
    }
</style>
@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Evacuation Plans</h1>
            <button class="btn btn-primary" onclick="printAllPlans()">
                <i class="fas fa-print me-2"></i> Print Plans Report
            </button>
        </div>

        @if(!$activeSchool)
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> No active school selected. Please select a school from the dashboard.
            </div>
        @else
            @php $school = $activeSchool; @endphp
            <!-- Summary Stats -->
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
                                                @if(auth()->user()->role !== 'viewer')
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
                                                @endif
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
                                                    @if(auth()->user()->role !== 'viewer')
                                                    <button class="btn btn-sm btn-outline-warning edit-plan-btn"
                                                            data-plan-id="{{ $plan->id }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editPlanModal">
                                                        <i class="fas fa-edit me-2"></i> Edit Plan
                                                    </button>
                                                    @endif
                                                    @else
                                                    @if(auth()->user()->role !== 'viewer')
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
                                            @if(auth()->user()->role !== 'viewer')
                                            <button class="btn btn-outline-primary btn-sm me-2" id="edit-placement-btn-{{ $school->id }}" onclick="toggleMapEdit({{ $school->id }})">
                                                <i class="fas fa-arrows-alt me-2"></i> Edit Placement
                                            </button>
                                            <button class="btn btn-primary btn-sm" id="save-placement-btn-{{ $school->id }}" onclick="saveMapLayout({{ $school->id }})" disabled>
                                                <i class="fas fa-save me-2"></i> Save Layout
                                            </button>
                                            @endif
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
                                <button class="btn btn-sm btn-outline-primary" onclick="loadDrillHistory({{ $school->id }})">
                                    <i class="fas fa-sync-alt me-1"></i> Refresh
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="drillHistory-{{ $school->id }}">
                                    <!-- Drill history will be loaded here -->
                                    <div class="loading-placeholder">
                                        <i class="fas fa-spinner fa-spin"></i>
                                        <p>Loading drill history...</p>
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
        @endif
    </div>
@endsection

@section('modals')

    <!-- Add Plan Modal -->
    <div class="modal fade" id="addPlanModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i> Create Evacuation Plan (<span id="modalBuildingCode">—</span>)
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
                                <input type="hidden" name="status" value="active">
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
                    @if(auth()->user()->role !== 'viewer')
                    <button type="button" class="btn btn-primary" id="savePlanButton">
                        <i class="fas fa-save me-2"></i> Save Plan
                    </button>
                    @endif
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
                        <i class="fas fa-edit me-2"></i> Edit Evacuation Plan (<span id="editModalBuildingCode">—</span>)
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
                    @if(auth()->user()->role !== 'viewer')
                    <button type="button" class="btn btn-primary" onclick="updatePlan()">
                        <i class="fas fa-save me-2"></i> Update Plan
                    </button>
                    @endif
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
                    @if(auth()->user()->role !== 'viewer')
                    <button type="button" class="btn btn-danger" id="deletePlanBtn" style="display: none;">
                        <i class="fas fa-trash me-2"></i> Delete Plan
                    </button>
                    @endif
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
                        <i class="fas fa-calendar-plus me-2"></i> Schedule a Drill
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
                                <input type="date" class="form-control" name="drill_date" id="drillDate" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Start Time <i>(optional)</i></label>
                                <input type="time" class="form-control" name="start_time" id="startTime">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">End Time <i>(optional)</i></label>
                                <input type="time" class="form-control" name="end_time" id="endTime">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Status *</label>
                                <select class="form-control" name="status" required>
                                    <option value="scheduled">Scheduled</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                    <option value="postponed">Postponed</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Target Buildings *</label>
                            <div class="d-flex gap-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="building_scope" id="scopeAll" value="all" checked onchange="toggleBuildingSelection()">
                                    <label class="form-check-label" for="scopeAll">All Buildings</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="building_scope" id="scopeSpecific" value="specific" onchange="toggleBuildingSelection()">
                                    <label class="form-check-label" for="scopeSpecific">Specific Building/s</label>
                                </div>
                            </div>

                            <div id="specificBuildingsContainer" style="display: none;" class="border rounded p-3 bg-light">
                                <p class="small text-muted mb-2">Select the buildings participating in this drill:</p>
                                <div id="drillBuildingsList" class="row g-2">
                                    <!-- Populated via JS -->
                                    <div class="col-12 text-center py-2">
                                        <i class="fas fa-spinner fa-spin me-2"></i> Loading buildings...
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Participants Count</label>
                                <input type="number" class="form-control" name="participants_count" min="0" placeholder="e.g., 500">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Evacuation Time (minutes)</label>
                                <input type="number" class="form-control" name="evacuation_time_minutes" min="0" placeholder="e.g., 5">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Coordinator</label>
                                <input type="text" class="form-control" name="coordinator" value="{{ Auth::user()->name }}" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Remarks</label>
                            <input type="text" class="form-control" name="remarks" placeholder="Brief summary of drill result...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes <i>(optional)</i></label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Additional instructions or detailed observations..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    @if(auth()->user()->role !== 'viewer')
                    <button type="button" class="btn btn-primary" onclick="saveDrillSchedule()">
                        <i class="fas fa-calendar-check me-2"></i> Confirm Schedule
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Store current school ID
        let currentSchoolId = null;
        let currentPlanId = null;
        const userRole = "{{ auth()->user()->role }}";

        function checkViewerAccess(formId, buttonsId = null) {
            if (userRole === 'viewer') {
                if (formId) {
                    const form = document.getElementById(formId);
                    if (form) {
                        const elements = form.querySelectorAll('input, select, textarea, button:not([data-bs-dismiss="modal"])');
                        elements.forEach(el => el.disabled = true);
                    }
                }
                if (buttonsId) {
                    const buttons = document.getElementById(buttonsId);
                    if (buttons) buttons.style.display = 'none';
                }
            }
        }
        let drillsData = {}; // Store drill data by school ID

        // Initialize with first school
        // Initialize with active school
        document.addEventListener('DOMContentLoaded', function() {
            @if($activeSchool)
                currentSchoolId = "{{ $activeSchool->id }}";
                // Small delay to ensure DOM is fully ready
                setTimeout(() => loadSchoolData(currentSchoolId), 100);
            @endif

            // Set default drill date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('drillDate').value = tomorrow.toISOString().split('T')[0];


            // Add Plan Modal show event - FIXED
            document.getElementById('addPlanModal').addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const form = document.getElementById('addPlanForm');
                form.reset();

                // Reset displays
                document.getElementById('displaySafetyFeatures').value = 'Loading building data...';
                document.getElementById('displayEmergencyExits').value = '...';
                document.getElementById('modalBuildingCode').textContent = '—';
                document.getElementById('planBuildingId').value = '';
                document.getElementById('planSchoolId').value = '';

                if (button && button.classList.contains('create-plan-btn')) {
                    const buildingId = button.getAttribute('data-building-id');
                    const buildingName = button.getAttribute('data-building-name');
                    const buildingCode = button.getAttribute('data-building-code') || buildingName;
                    const schoolId = button.getAttribute('data-school-id') || currentSchoolId;

                    document.getElementById('planSchoolId').value = schoolId;
                    document.getElementById('planBuildingId').value = buildingId;
                    document.getElementById('modalBuildingCode').textContent = buildingCode;

                    // Load building details (Exits, Features)
                    loadBuildingDetailsForPlan(buildingId, schoolId);
                } else if (button && button.hasAttribute('data-school-id')) {
                    // General Add Plan button from header
                    const schoolId = button.getAttribute('data-school-id');
                    document.getElementById('planSchoolId').value = schoolId;
                    document.getElementById('modalBuildingCode').textContent = 'Select Building';
                    document.getElementById('displayEmergencyExits').value = 0;
                    document.getElementById('displaySafetyFeatures').value = 'Please select a building by clicking "Create Plan" on a building card.';
                }

                // Enforce viewer role restrictions
                checkViewerAccess('addPlanForm');
            });

            // Save Plan button click handler - FIXED
            document.getElementById('savePlanButton').addEventListener('click', savePlan);

            // Edit Plan button click handler with event delegation
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
                    document.getElementById('editExits').value = plan.building?.emergency_exits ?? '';

                    // Display safety features
                    let featuresText = 'No safety features recorded';
                    if (plan.building?.features) {
                        const features = plan.building.features;
                        if (typeof features === 'string') {
                            featuresText = features;
                        } else {
                            const featureLabels = [];
                            if (features.sprinklers) featureLabels.push('Sprinkler System');
                            if (features.emergency_lights) featureLabels.push('Emergency Lighting');
                            if (features.exit_signs) featureLabels.push('Exit Signs');
                            if (features.fire_doors) featureLabels.push('Fire Doors');
                            if (features.two_stairways) featureLabels.push('Two Stairways');
                            featuresText = featureLabels.length > 0 ? featureLabels.join(', ') : 'None recorded';
                        }
                    }
                    document.getElementById('editSafetyFeatures').value = featuresText;

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

                    // Enforce viewer role restrictions
                    checkViewerAccess('editPlanForm');

                } catch (error) {
                    console.error('Error loading plan data:', error);
                    Swal.fire('Error', 'Failed to load plan data', 'error');
                }
            });

            // View Plan button click handler with event delegation
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

                    // Generate Dynamic Layout if school_buildings data is available
                    if (responseData.school_buildings) {
                        generateAutoSchoolLayout(plan.building.id, responseData.school_buildings);
                    }

                    // Show delete button for admins or creators
                    const deleteBtn = document.getElementById('deletePlanBtn');
                    if (deleteBtn) {
                        deleteBtn.style.display = userRole === 'viewer' ? 'none' : 'block';
                        deleteBtn.onclick = function() { deletePlan(plan.id); };
                    }

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

            // Load initial data
            loadSchoolData(currentSchoolId);
        });

        // Save Plan via AJAX
        async function savePlan() {
             const form = document.getElementById('addPlanForm');
             if (!form.checkValidity()) {
                 form.reportValidity();
                 return;
             }

             const formData = new FormData(form);

             try {
                 // Show loading
                 Swal.fire({
                     title: 'Saving Plan...',
                     text: 'Please wait while we create the evacuation plan.',
                     allowOutsideClick: false,
                     didOpen: () => {
                         Swal.showLoading();
                     }
                 });

                 const response = await fetch(form.action, {
                     method: 'POST',
                     body: formData,
                     headers: {
                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                         'Accept': 'application/json'
                     }
                 });

                 const data = await response.json();

                 if (data.success) {
                     Swal.fire({
                         title: 'Success!',
                         text: 'Evacuation plan created successfully!',
                         icon: 'success',
                         timer: 2000,
                         showConfirmButton: false
                     }).then(() => {
                         // Reload page to show new plan
                         location.reload();
                     });
                 } else {
                     let errorMessage = data.message || 'Failed to create plan.';
                     if (data.errors) {
                         const errorList = Object.values(data.errors).flat().join('\n');
                         errorMessage += '\n' + errorList;
                     }
                     Swal.fire('Error', errorMessage, 'error');
                 }
             } catch (error) {
                 console.error('Error saving plan:', error);
                 Swal.fire('Error', 'An unexpected error occurred.', 'error');
             }
         }

        // Load building details for plan creation - FIXED
        async function loadBuildingDetailsForPlan(buildingId, schoolId) {
            try {
                const response = await fetch(`/fire-safety/building/${buildingId}/evacuation-data?school_id=${schoolId}`);
                if (!response.ok) throw new Error('Failed to load building data');

                const data = await response.json();

                if (data.success) {
                    const building = data.building;

                    // Display emergency exits
                    document.getElementById('displayEmergencyExits').value = building.emergency_exits || 0;
                    document.getElementById('hiddenEmergencyExits').value = building.emergency_exits || 0;

                    // Format and display safety features
                    document.getElementById('displaySafetyFeatures').value = building.features || 'No safety features recorded';

                } else {
                    throw new Error(data.message || 'Failed to load building data');
                }
            } catch (error) {
                console.error('Error loading building details:', error);
                document.getElementById('displayEmergencyExits').value = 'Error';
                document.getElementById('hiddenEmergencyExits').value = 0;
                document.getElementById('displaySafetyFeatures').value = 'Error loading safety features.';
                Swal.fire('Error', 'Failed to load building details. Please try again.', 'error');
            }
        }

        // Load school data
        async function loadSchoolData(schoolId) {
            if (!schoolId) return;
            currentSchoolId = schoolId;

            // Load drill history
            await loadDrillHistory(schoolId);
            // Load plan stats
            loadPlanStats(schoolId);

            // Check if map tab is active, if so init map
            const mapTab = document.getElementById(`map-tab-${schoolId}`);
            if (mapTab && mapTab.classList.contains('active')) {
                initEvacuationMap(schoolId);
            }
        }

        // Load drill history - FIXED
        async function loadDrillHistory(schoolId) {
            const container = document.getElementById(`drillHistory-${schoolId}`);
            if (!container) return;

            try {
                // Show loading state
                container.innerHTML = `
                    <div class="loading-placeholder">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>Loading drill history...</p>
                    </div>
                `;

                const response = await fetch(`/fire-safety/drill-history/${schoolId}`);
                if (!response.ok) throw new Error('Failed to load drill history');

                const drills = await response.json();
                drillsData[schoolId] = drills;

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
                        <table class="table drill-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Participants</th>
                                    <th>Evac. Time</th>
                                    <th>Coordinator</th>
                                    <th>Remarks</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                drills.forEach(drill => {
                    const drillDate = new Date(drill.drill_date).toLocaleDateString();
                    const statusColors = {
                        'scheduled': 'primary',
                        'completed': 'success',
                        'cancelled': 'danger',
                        'postponed': 'warning'
                    };
                    const statusColor = statusColors[drill.status] || 'secondary';

                    html += `
                        <tr>
                            <td>${drillDate}</td>
                            <td><span class="badge bg-info">${drill.drill_type}</span></td>
                            <td><span class="badge bg-${statusColor}">${drill.status}</span></td>
                            <td>${drill.participants_count || '-'}</td>
                            <td>${drill.evacuation_time_minutes ? drill.evacuation_time_minutes + ' min' : '-'}</td>
                            <td>${drill.coordinator || '-'}</td>
                            <td class="drill-remarks" title="${drill.remarks || ''}">${drill.remarks ? (drill.remarks.length > 30 ? drill.remarks.substring(0, 30) + '...' : drill.remarks) : '-'}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="viewDrillDetails(${drill.id})">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteDrill(${drill.id}, ${schoolId})">
                                    <i class="fas fa-trash"></i>
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
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Failed to load drill history. Please try again.
                        <button class="btn btn-sm btn-outline-danger ms-3" onclick="loadDrillHistory(${schoolId})">
                            Retry
                        </button>
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
                container.innerHTML = `
                    <div class="text-center py-4">
                        <p class="text-danger small mb-2">Failed to load statistics.</p>
                        <button class="btn btn-sm btn-outline-secondary" onclick="loadPlanStats(${schoolId})">Retry</button>
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

                for (let f = floorCount; f >= 1; f--) {
                    const floorLabel = f === 1 ? '1st Floor' : f === 2 ? '2nd Floor' : f === 3 ? '3rd Floor' : f + 'th Floor';
                    html += `
                        <div class="floor-box">
                            <div class="floor-title">${floorLabel}</div>
                            <div class="rooms-container">
                    `;

                    // Simplified room representation
                    const roomsCount = building.rooms_count || 4;
                    for (let i = 1; i <= Math.min(roomsCount, 6); i++) {
                        html += `<div class="room-unit" title="Room ${i}">${i}</div>`;
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

        // Save Plan - FIXED
        async function savePlan() {
            const form = document.getElementById('addPlanForm');
            const buildingId = document.getElementById('planBuildingId').value;

            if (!buildingId) {
                Swal.fire('Error', 'Please select a building first by clicking "Create Plan" on a building card.', 'error');
                return;
            }

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);

            try {
                Swal.fire({
                    title: 'Creating Plan...',
                    text: 'Please wait while we create the evacuation plan.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                Swal.close();

                if (data.success) {
                    // Hide modal
                    const modalEl = document.getElementById('addPlanModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();

                    Swal.fire({
                        title: 'Success!',
                        text: 'Evacuation plan created successfully!',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    let errorMessage = data.message || 'Failed to create plan';
                    if (data.errors) {
                        const errorList = Object.values(data.errors).flat().join('\n');
                        errorMessage += '\n' + errorList;
                    }
                    Swal.fire('Error', errorMessage, 'error');
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

        // Toggle Building Selection Display
        function toggleBuildingSelection() {
            const scope = document.querySelector('input[name="building_scope"]:checked').value;
            const container = document.getElementById('specificBuildingsContainer');
            container.style.display = scope === 'specific' ? 'block' : 'none';
        }

        // Open Schedule Drill Modal
        async function openScheduleDrillModal(schoolId) {
            const modalElement = document.getElementById('scheduleDrillModal');
            const modal = new bootstrap.Modal(modalElement);

            // Set school ID
            document.getElementById('drillSchoolId').value = schoolId;

            // Set default times
            document.getElementById('startTime').value = '09:00';
            document.getElementById('endTime').value = '10:00';

            // Reset scope to All
            document.getElementById('scopeAll').checked = true;
            toggleBuildingSelection();

            // Load buildings for this school
            const buildingsList = document.getElementById('drillBuildingsList');
            buildingsList.innerHTML = '<div class="col-12 text-center py-2"><i class="fas fa-spinner fa-spin me-2"></i> Loading buildings...</div>';

            try {
                const response = await fetch(`/fire-safety/drill-buildings/${schoolId}`);
                const buildings = await response.json();

                if (buildings && buildings.length > 0) {
                    let html = '';
                    buildings.forEach(b => {
                        html += `
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input building-checkbox" type="checkbox" value="${b.id}" id="drillBldg${b.id}">
                                    <label class="form-check-label small" for="drillBldg${b.id}">
                                        ${b.building_no}${b.building_name ? ' - ' + b.building_name : ''}
                                    </label>
                                </div>
                            </div>
                        `;
                    });
                    buildingsList.innerHTML = html;
                } else {
                    buildingsList.innerHTML = '<div class="col-12 text-center py-2 text-muted">No buildings found for this school.</div>';
                }
            } catch (error) {
                console.error('Error loading buildings for drill:', error);
                buildingsList.innerHTML = '<div class="col-12 text-center py-2 text-danger">Error loading buildings.</div>';
            }

            modal.show();

            // Enforce viewer role restrictions
            checkViewerAccess('scheduleDrillForm');
        }

        // Save Drill Schedule - FIXED
        async function saveDrillSchedule() {
            const form = document.getElementById('scheduleDrillForm');

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);
            const schoolId = document.getElementById('drillSchoolId').value;

            // Handle Buildings Selection
            const scope = document.querySelector('input[name="building_scope"]:checked').value;
            let buildingIds = [];

            if (scope === 'all') {
                // Get all buildings in the list
                document.querySelectorAll('.building-checkbox').forEach(cb => {
                    buildingIds.push(cb.value);
                });
            } else {
                // Get only checked buildings
                document.querySelectorAll('.building-checkbox:checked').forEach(cb => {
                    buildingIds.push(cb.value);
                });
            }

            if (buildingIds.length === 0) {
                Swal.fire('Error', 'Please select at least one building for the drill.', 'error');
                return;
            }

            // Append building_ids as array
            buildingIds.forEach(id => {
                formData.append('building_ids[]', id);
            });

            try {
                Swal.fire({
                    title: 'Scheduling Drill...',
                    text: 'Please wait while we schedule the drill.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
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

                Swal.close();

                if (data.success) {
                    // Hide modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('scheduleDrillModal'));
                    modal.hide();

                    Swal.fire({
                        title: 'Scheduled!',
                        text: 'Evacuation drill has been scheduled successfully.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Reload drill history for the current school
                        loadDrillHistory(schoolId);
                    });
                } else {
                    let errorMessage = data.message || 'Could not schedule drill';
                    if (data.errors) {
                        const errorList = Object.values(data.errors).flat().join('\n');
                        errorMessage += '\n' + errorList;
                    }
                    Swal.fire('Failed', errorMessage, 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'An unexpected error occurred while scheduling the drill.', 'error');
            }
        }

        // View Drill Details
        async function viewDrillDetails(drillId) {
            try {
                const response = await fetch(`/fire-safety/drill/${drillId}`);
                const drill = await response.json();

                const drillDate = new Date(drill.drill_date).toLocaleDateString();
                const statusColors = {
                    'scheduled': 'primary',
                    'completed': 'success',
                    'cancelled': 'danger',
                    'postponed': 'warning'
                };
                const statusColor = statusColors[drill.status] || 'secondary';

                Swal.fire({
                    title: `Drill Details - ${drill.drill_type}`,
                    html: `
                        <div class="text-start">
                            <p><strong>Date:</strong> ${drillDate}</p>
                            <p><strong>Status:</strong> <span class="badge bg-${statusColor}">${drill.status}</span></p>
                            <p><strong>Start Time:</strong> ${drill.start_time || 'Not specified'}</p>
                            <p><strong>End Time:</strong> ${drill.end_time || 'Not specified'}</p>
                            <p><strong>Participants:</strong> ${drill.participants_count || 'Not specified'}</p>
                            <p><strong>Evacuation Time:</strong> ${drill.evacuation_time_minutes ? drill.evacuation_time_minutes + ' minutes' : 'Not specified'}</p>
                            <p><strong>Coordinator:</strong> ${drill.coordinator || 'Not specified'}</p>
                            <p><strong>Remarks:</strong> ${drill.remarks || 'None'}</p>
                            ${drill.notes ? `<p><strong>Notes:</strong> ${drill.notes}</p>` : ''}
                        </div>
                    `,
                    confirmButtonText: 'Close'
                });

            } catch (error) {
                console.error('Error loading drill details:', error);
                Swal.fire('Error', 'Failed to load drill details.', 'error');
            }
        }

        // Delete Drill
        async function deleteDrill(drillId, schoolId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Are you sure you want to delete this drill record?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/fire-safety/drill/${drillId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire('Deleted!', 'Drill record deleted successfully!', 'success').then(() => {
                                loadDrillHistory(schoolId);
                            });
                        } else {
                            Swal.fire('Error', data.message || 'Failed to delete drill', 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Failed to delete drill record.', 'error');
                    }
                }
            });
        }

        // View Plan wrapper
        function viewPlan(planId, buildingId, buildingNo) {
            if (!planId || planId === null) {
                // Open add modal for this building
                const btn = document.querySelector(`.create-plan-btn[data-building-id="${buildingId}"]`);
                if (btn) {
                    btn.click();
                } else {
                    // Fallback
                    document.getElementById('planBuildingId').value = buildingId;
                    document.getElementById('modalBuildingCode').textContent = buildingNo || '—';
                    const modal = new bootstrap.Modal(document.getElementById('addPlanModal'));
                    modal.show();
                }
                return;
            }

            // Open view modal for this plan
            const btn = document.querySelector(`.view-plan-btn[data-plan-id="${planId}"]`);
            if (btn) {
                btn.click();
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
                    window.open('/fire-safety/evacuation-plans/report', '_blank');
                }
            });
        }

        // ==========================================
        // EVACUATION MAP LOGIC (Interactive Canvas)
        // ==========================================
        let mapData = {};
        let isMapEditable = {};

        async function initEvacuationMap(schoolId) {
            const canvasContainer = document.getElementById(`school-map-canvas-${schoolId}`);
            if (!canvasContainer) return;

            if (canvasContainer.dataset.loaded === 'true') return;

            canvasContainer.innerHTML = `
                <div class="text-center pt-5 text-muted">
                    <i class="fas fa-spinner fa-spin fa-3x mb-3"></i><br>Loading Map Data...
                </div>
            `;

            try {
                const response = await fetch(`/fire-safety/school/${schoolId}/map-data`);
                const school = await response.json();
                mapData[schoolId] = school;

                renderSchoolMap(school, schoolId);
                canvasContainer.dataset.loaded = 'true';
            } catch (error) {
                console.error('Error loading map data:', error);
                canvasContainer.innerHTML = `
                    <div class="text-center pt-5 text-danger">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i><br>
                        Failed to load map data.<br>
                        <button class="btn btn-sm btn-outline-danger mt-3" onclick="initEvacuationMap(${schoolId})">Retry</button>
                    </div>
                `;
            }
        }

        function renderSchoolMap(school, schoolId) {
            const canvas = document.getElementById(`school-map-canvas-${schoolId}`);
            canvas.innerHTML = '';

            // Simplified map rendering logic
            const buildings = school.buildings || [];
            let x = 50, y = 50;

            buildings.forEach((building, index) => {
                const bDiv = document.createElement('div');
                bDiv.className = 'map-element building-element';
                bDiv.id = `map-bldg-${building.id}`;
                bDiv.dataset.id = `building_${building.id}`;
                bDiv.dataset.schoolId = schoolId;
                bDiv.style.position = 'absolute';
                bDiv.style.left = x + 'px';
                bDiv.style.top = y + 'px';
                bDiv.style.width = '200px';
                bDiv.style.padding = '10px';
                bDiv.style.backgroundColor = 'white';
                bDiv.style.border = '3px solid black';
                bDiv.style.borderRadius = '5px';
                bDiv.style.boxShadow = '2px 2px 5px rgba(0,0,0,0.2)';
                bDiv.style.cursor = 'default';

                bDiv.innerHTML = `
                    <div style="text-align: center; font-weight: bold; margin-bottom: 5px;">
                        ${building.building_no}
                    </div>
                    <div style="font-size: 12px;">
                        <div>Floors: ${building.floors || 1}</div>
                        <div>Exits: ${building.emergency_exits || 0}</div>
                        <div>Alarms: ${building.alarm_systems_count || 0}</div>
                    </div>
                `;

                canvas.appendChild(bDiv);
                makeDraggable(bDiv, schoolId);

                x += 220;
                if (x > 800) {
                    x = 50;
                    y += 180;
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

                document.querySelectorAll(`#school-map-canvas-${schoolId} .map-element`).forEach(el => {
                    el.style.cursor = 'move';
                });
            } else {
                btn.innerHTML = '<i class="fas fa-arrows-alt me-2"></i> Edit Placement';
                btn.classList.replace('btn-warning', 'btn-outline-primary');
                saveBtn.disabled = true;

                document.querySelectorAll(`#school-map-canvas-${schoolId} .map-element`).forEach(el => {
                    el.style.cursor = 'default';
                });
            }
        }

        function makeDraggable(element, schoolId) {
            let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;

            element.onmousedown = dragMouseDown;

            function dragMouseDown(e) {
                if (!isMapEditable[schoolId]) return;

                e.preventDefault();
                pos3 = e.clientX;
                pos4 = e.clientY;
                document.onmouseup = closeDragElement;
                document.onmousemove = elementDrag;
            }

            function elementDrag(e) {
                e.preventDefault();
                pos1 = pos3 - e.clientX;
                pos2 = pos4 - e.clientY;
                pos3 = e.clientX;
                pos4 = e.clientY;
                element.style.top = (element.offsetTop - pos2) + "px";
                element.style.left = (element.offsetLeft - pos1) + "px";
            }

            function closeDragElement() {
                document.onmouseup = null;
                document.onmousemove = null;
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
@endsection
</html>
</html>
</html>
</html>
</html>
