@extends('layouts.fire-safety')

@section('title', 'Evacuation Plans - Fire Safety')
@section('page_title', 'Evacuation Plans')
@section('content')
    <div class="container-fluid">
        <style>
            .school-map-canvas-container {
                cursor: grab;
                transition: transform 0.1s ease-out;
            }
            .school-map-canvas-container:active { cursor: grabbing; }
            .map-element { transition: border-color 0.2s; }
            .map-element.building-element { box-shadow: 4px 4px 10px rgba(0,0,0,0.2) !important; }
            
            @media print {
                .no-print { display: none !important; }
                html, body { margin: 0; padding: 0; background: white; overflow: hidden !important; height: 100% !important; }
                .card { border: none !important; box-shadow: none !important; }
                @page { size: landscape; margin: 0; }
                header, .top-nav, .sidebar, .breadcrumb, .school-tabs, .card-header, .card-footer, .no-print, .btn, .mt-3.p-3.bg-white { display: none !important; }
                .container-fluid, .row, .col-12, .card, .card-body, .tab-content, .tab-pane { 
                    margin: 0 !important; 
                    padding: 0 !important; 
                    overflow: hidden !important;
                    background: white !important;
                }
                .school-map-canvas-container { 
                    width: 100vw !important; 
                    height: 100vh !important;
                    border: none !important;
                    box-shadow: none !important;
                    position: fixed !important;
                    top: 0 !important;
                    left: 0 !important;
                    z-index: 9999 !important;
                }
            }
            .facility-element { border-radius: 4px; display: flex; align-items: center; justify-content: center; text-align: center; color: white; font-weight: bold; font-size: 14px; text-shadow: 1px 1px 2px rgba(0,0,0,0.5); border: 2px solid rgba(0,0,0,0.2); }
        </style>

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
                        $buildingsWithPlans = $school->buildingsWithPlansCount;
                        $totalEmergencyExits = $school->totalEmergencyExits;
                        $totalAlarms = $school->totalFunctionalAlarms;
                        $totalExtinguishers = $school->totalActiveExtinguishers;
                    @endphp

                    <div class="col-xl-3 col-lg-4 col-md-6 col-6 mb-4">
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

                    <div class="col-xl-3 col-lg-4 col-md-6 col-6 mb-4">
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

                    <div class="col-xl-3 col-lg-4 col-md-6 col-6 mb-4">
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

                    <div class="col-xl-3 col-lg-4 col-md-6 col-6 mb-4">
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
                                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                            <h6 class="m-0 fw-bold text-primary">
                                                <i class="fas fa-chevron-down toggle-icon" onclick="toggleDivision(this, 'recent-inspections-card-{{ $school->id }}')"></i>
                                                Plans Overview
                                            </h6>
                                             <div>
                                                @php
                                                    $schoolPlan = $school->schoolEvacuationPlan;
                                                @endphp

                                                 @if(auth()->user()->role !== 'viewer')
                                                     @if(!$schoolPlan)
                                                         <button class="btn btn-primary btn-sm me-2 text-white"
                                                                 data-bs-toggle="modal"
                                                                 data-bs-target="#addSchoolPlanModal"
                                                                 data-school-id="{{ $school->id }}">
                                                             <i class="fas fa-plus me-2"></i> Add School Plan
                                                         </button>
                                                     @endif

                                                     <button class="btn btn-success btn-sm me-2"
                                                             onclick="openScheduleDrillModal({{ $school->id }})">
                                                         <i class="fas fa-bullhorn me-2"></i> Schedule Drill
                                                     </button>

                                                     <button class="btn btn-sm me-2" style="background-color: #e9ecef; border: 1px solid #ced4da; color: black; font-size: 0.75rem;" onclick="printAllPlans({{ $school->id }})">
                                                         <i class="fas fa-print me-1"></i> Print Plans Report
                                                     </button>

                                                     <div class="btn-group btn-group-sm no-print" role="group">
                                                         <input type="radio" class="btn-check" name="planViewMode-{{ $school->id }}" id="schoolView-{{ $school->id }}" autocomplete="off" checked onclick="togglePlanView('school', {{ $school->id }})">
                                                         <label class="btn btn-outline-primary" for="schoolView-{{ $school->id }}">School Plan</label>
                                                         <input type="radio" class="btn-check" name="planViewMode-{{ $school->id }}" id="buildingView-{{ $school->id }}" autocomplete="off" onclick="togglePlanView('building', {{ $school->id }})">
                                                         <label class="btn btn-outline-primary" for="buildingView-{{ $school->id }}">Buildings</label>
                                                     </div>
                                                 @endif
                                             </div>
                                         </div>

                                 <div id="school-plan-view-{{ $school->id }}">
                                     @if($schoolPlan)
                                        <div class="card mb-2 border-0 shadow-sm" style="background: white; border-radius: 12px; overflow: hidden; border: 1px solid #e3e6f0 !important;">
                                            <div class="row g-0">
                                                <div class="col-lg-4 bg-primary text-white d-flex flex-column align-items-center justify-content-center p-4">
                                                    <i class="fas fa-school fa-4x mb-3"></i>
                                                    <h3 class="fw-bold">{{ $schoolPlan->plan_no }}</h3>
                                                    <span class="badge bg-white text-primary px-3">Active Campus Plan</span>
                                                </div>
                                                <div class="col-lg-8 p-4">
                                                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                                        <span class="text-muted small"><i class="fas fa-info-circle me-1"></i> Strategy for entire campus and outdoor areas.</span>
                                                        <button class="btn btn-sm btn-primary" data-plan-id="{{ $schoolPlan->id }}" data-bs-toggle="modal" data-bs-target="#viewPlanModal">
                                                            <i class="fas fa-expand me-1"></i> View Plan
                                                        </button>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="p-3 border rounded bg-light mb-2 h-100">
                                                                <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.65rem;">Primary Assembly Area</small>
                                                                <div class="fw-bold">{{ $schoolPlan->primary_assembly_area ?? 'Not set' }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="p-3 border rounded bg-light h-100">
                                                                <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.65rem;">Assembly Capacity</small>
                                                                <div class="fw-bold">{{ $schoolPlan->assembly_capacity ?? 'N/A' }} Persons</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <small class="text-muted d-block fw-bold text-uppercase mb-1" style="font-size: 0.65rem;">Special Instructions</small>
                                                        <div class="small bg-light p-2 rounded border-start border-3 border-warning">{{ $schoolPlan->special_instructions ?? 'None' }}</div>
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-sm btn-warning text-white edit-school-plan-btn" data-plan-id="{{ $schoolPlan->id }}" data-bs-toggle="modal" data-bs-target="#editSchoolPlanModal">
                                                            <i class="fas fa-edit me-1"></i> Edit Plan
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-secondary" onclick="window.open('/fire-safety/reports/evacuation-plans/{{ $school->id }}', '_blank')">
                                                            <i class="fas fa-print me-1"></i> Print
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                     @else
                                        <div class="text-center py-5 border rounded bg-light mb-2 border-dashed" style="border-width: 2px !important; border-color: #dee2e6 !important;">
                                            <i class="fas fa-school fa-3x text-muted mb-3"></i>
                                            <h5>No School Plan Created</h5>
                                            <p class="text-muted small mb-3">Establish a comprehensive evacuation plan for the entire campus.</p>
                                            <button class="btn btn-primary btn-sm mt-1" data-bs-toggle="modal" data-bs-target="#addSchoolPlanModal" data-school-id="{{ $school->id }}">
                                                <i class="fas fa-plus me-1"></i> Create School Plan
                                            </button>
                                        </div>
                                     @endif
                                 </div>

                                 <div id="building-plans-view-{{ $school->id }}" style="display: none;">
                                 @if($school->buildings->count() > 0)
                                 <div class="row px-1 px-md-3">
                                     @foreach($school->buildings as $building)
                                    @php
                                        $plan = $building->evacuationPlan;
                                        $safetyScore = $building->safety_score;

                                        $hasSchoolPlan = ($school->schoolEvacuationPlan !== null);
                                        if ($plan) {
                                            $statusClass = 'border-' . $plan->status_color;
                                            $statusBadge = 'bg-' . $plan->status_color;
                                            $statusText = $plan->status_label;
                                        } elseif ($hasSchoolPlan) {
                                            $statusClass = 'border-success';
                                            $statusBadge = 'bg-success';
                                            $statusText = 'Passed';
                                        } else {
                                            $statusClass = 'border-danger';
                                            $statusBadge = 'bg-danger';
                                            $statusText = 'No Plan';
                                        }

                                        // New Safety Text requirements:
                                        // 100% = Perfect, 90% = Passed
                                        if ($safetyScore >= 100) {
                                            $safetyText = 'Perfect';
                                            $safetyClass = 'safety-good';
                                        } elseif ($safetyScore >= 90) {
                                            $safetyText = 'Passed';
                                            $safetyClass = 'safety-good';
                                        } elseif ($safetyScore >= 80) {
                                            $safetyText = 'Good';
                                            $safetyClass = 'safety-good';
                                        } elseif ($safetyScore >= 60) {
                                            $safetyText = 'Fair';
                                            $safetyClass = 'safety-warning';
                                        } else {
                                            $safetyText = 'Poor';
                                            $safetyClass = 'safety-danger';
                                        }
                                    @endphp
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-6 mb-4">
                                        <div class="card evacuation-card {{ $statusClass }} h-100 shadow-sm">
                                            <div class="card-body mobile-card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div class="text-truncate" style="max-width: 65%;">
                                                        <h6 class="card-title mb-0 fw-bold text-truncate">{{ $building->building_no }}</h6>
                                                        <div class="text-muted small text-truncate mobile-tiny-text">{{ $building->building_name }}</div>
                                                    </div>
                                                    <span class="badge {{ $statusBadge }} py-1 px-2 mobile-badge">{{ $statusText }}</span>
                                                </div>

                                                <!-- Map Preview -->
                                                <div class="map-container mb-3 mobile-mb-2"
                                                     onclick="viewPlan({{ $plan ? $plan->id : 'null' }}, {{ $building->id }}, '{{ $building->building_no }}')"
                                                     style="background: {{ ($plan && $plan->map_data) ? 'white' : 'linear-gradient(135deg, ' . ($plan ? '#6a11cb' : '#868f96') . ' 0%, ' . ($plan ? '#2575fc' : '#596164') . ' 100%)' }}; overflow: hidden; height: 120px; display: flex; align-items: center; justify-content: center; position: relative; border: 1px solid #ddd; border-radius: 4px;">
                                                     @if($plan && $plan->map_data)
                                                         <img src="{{ $plan->map_data }}" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.9;">
                                                         <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(168, 25, 31, 0.8); color: white; padding: 2px; font-size: 0.7rem;">
                                                             <span>{{ $plan->plan_no }}</span>
                                                         </div>
                                                     @elseif($plan)
                                                         <div class="text-white text-center">
                                                             <i class="fas fa-map fa-2x mb-1"></i>
                                                             <div class="small">{{ $plan->plan_no }}</div>
                                                         </div>
                                                     @else
                                                         <div class="text-white text-center">
                                                             @if($hasSchoolPlan)
                                                                <i class="fas fa-info-circle fa-2x mb-1"></i>
                                                                <div class="small" style="line-height: 1;">Optional<br><span style="font-size: 0.5rem;">(School Plan Active)</span></div>
                                                             @else
                                                                <i class="fas fa-exclamation-triangle fa-2x mb-1"></i>
                                                                <div class="small">No Plan</div>
                                                             @endif
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
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-sm btn-outline-warning edit-building-plan-btn w-50"
                                                                data-plan-id="{{ $plan->id }}"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editBuildingPlanModal">
                                                            <i class="fas fa-edit me-1"></i> Edit
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-secondary w-50"
                                                                onclick="window.open('/fire-safety/reports/evacuation-plans/{{ $school->id }}?building_id={{ $building->id }}', '_blank')">
                                                            <i class="fas fa-print me-1"></i> Print
                                                        </button>
                                                    </div>
                                                    @endif
                                                    @else
                                                    @if(auth()->user()->role !== 'viewer')
                                                    <button class="btn btn-sm btn-outline-danger create-building-plan-btn"
                                                            data-building-id="{{ $building->id }}"
                                                            data-building-name="{{ $building->building_name ?? $building->building_no }}"
                                                            data-building-code="{{ $building->building_no }}"
                                                            data-school-id="{{ $school->id }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#addBuildingPlanModal">
                                                        <i class="fas fa-plus-circle me-2"></i> Additional Evacuation Plan
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
                                </div> <!-- End building-plans-view -->
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
                                            <button class="btn btn-outline-success btn-sm me-2 no-print" onclick="openAddFacilityModal({{ $school->id }})">
                                                <i class="fas fa-plus me-2"></i> Add Facility
                                            </button>
                                            <button class="btn btn-outline-secondary btn-sm me-2 no-print" onclick="printEvacuationMap({{ $school->id }})">
                                                <i class="fas fa-print me-2"></i> Print Map
                                            </button>
                                            <button class="btn btn-primary btn-sm no-print" id="save-placement-btn-{{ $school->id }}" onclick="saveMapLayout({{ $school->id }})" disabled>
                                                <i class="fas fa-save me-2"></i> Save Layout
                                            </button>
                                            <button class="btn btn-outline-primary btn-sm me-2 no-print" id="edit-placement-btn-{{ $school->id }}" onclick="toggleMapEdit({{ $school->id }})">
                                                <i class="fas fa-arrows-alt me-2"></i> Edit Placement
                                            </button>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="school-map-canvas-container" style="position: relative; width: 100%; height: 800px; background: #e9ecef; border: 2px solid #333; overflow: hidden; border-radius: 4px; box-shadow: inset 0 0 20px rgba(0,0,0,0.1);">
                                        <div id="school-map-canvas-{{ $school->id }}" class="school-map-canvas" style="width: 100%; height: 100%; position: relative;">
                                            <div class="text-center pt-5 text-muted">
                                                <i class="fas fa-spinner fa-spin fa-3x mb-3"></i><br>Loading Map Data...
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3 p-3 bg-white border rounded shadow-sm">
                                        <h6 class="fw-bold fs-sm mb-2 text-dark border-bottom pb-2">Map Legend:</h6>
                                        <div class="d-flex flex-wrap gap-4 text-secondary small">
                                            <div class="d-flex align-items-center"><span style="width: 30px; height: 30px; background: white; border: 2px solid black; margin-right: 8px; display:inline-block;"></span> <strong>Building</strong></div>
                                            <div class="d-flex align-items-center"><span style="width: 24px; height: 1px; background: black; margin-right: 8px; display:inline-block;"></span> <strong>Floor Divider</strong></div>
                                            <div class="d-flex align-items-center"><span style="width: 15px; height: 15px; background: #f0f0f0; border: 1px solid #333; margin-right: 8px; display:inline-block;"></span> <strong>Room</strong></div>
                                            <div class="d-flex align-items-center"><span style="font-size: 16px; margin-right: 8px;">🧯</span> <strong>Fire Extinguisher</strong></div>
                                            <div class="d-flex align-items-center"><span style="font-size: 16px; margin-right: 8px;">🔔</span> <strong>Alarm System</strong></div>
                                            <div class="d-flex align-items-center"><span style="font-size: 14px; margin-right: 8px;">⚪</span> <strong>Smoke Detector</strong></div>
                                            <div class="d-flex align-items-center"><span style="background: green; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px; margin-right: 8px;">Plan OK</span> <strong>Evacuation Plan</strong></div>
                                            <div class="d-flex align-items-center"><span style="width: 20px; height: 10px; background: #28a745; border: 1px solid rgba(0,0,0,0.2); margin-right: 8px; display:inline-block;"></span> <strong>Campus Facility</strong></div>
                                        </div>
                                    </div>
                                </div>
                                </div> <!-- End Tab Content -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Evacuation Drill Schedule -->
                <div class="row mt-4">
                    <div class="col-lg-8 col-md-6">
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

                    <div class="col-lg-4 col-md-6">
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

    <!-- Add Building Plan Modal -->
    <div class="modal fade" id="addBuildingPlanModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i> Create Evacuation Plan (<span id="addBuildBldgCode">Loading...</span>)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addBuildingPlanForm" action="{{ route('fire-safety.evacuation-plan.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="school_id" class="school-id-field">
                        <input type="hidden" name="building_id" class="building-id-field">
                        <input type="hidden" name="plan_type" value="building">
                        <input type="hidden" name="status" value="active">

                        <h6 class="fw-bold text-primary mb-3">Individual Evacuation Plan Details</h6>
                        <hr>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Plan Name *</label>
                                <input type="text" class="form-control" name="plan_no" placeholder="e.g., EP-BLDG1" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Number of Routes *</label>
                                <input type="number" class="form-control" name="routes" min="1" max="10" value="2" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Primary Evacuation Route *</label>
                                <textarea class="form-control" name="primary_route" rows="3" placeholder="Describe main path to exit..." required></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Secondary Evacuation Route</label>
                                <textarea class="form-control" name="secondary_route" rows="3" placeholder="Describe alternative path..."></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold text-muted">Safety Features Installed</label>
                                <input type="text" class="form-control bg-light display-features" readonly disabled>
                                <input type="hidden" name="safety_features_installed" class="hidden-features">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveActivePlan('addBuildingPlanForm')">
                        <i class="fas fa-save me-2"></i> Save Plan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add School Plan Modal -->
    <div class="modal fade" id="addSchoolPlanModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i> Create Evacuation Plan (Entire School)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addSchoolPlanForm" action="{{ route('fire-safety.evacuation-plan.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="school_id" class="school-id-field">
                        <input type="hidden" name="plan_type" value="school">
                        <input type="hidden" name="status" value="active">

                        <h6 class="fw-bold text-primary mb-3">School-Wide Evacuation Plan Details</h6>
                        <hr>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Plan Name *</label>
                                <input type="text" class="form-control" name="plan_no" placeholder="e.g., EP-SCHOOL" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Number of Assembly Areas *</label>
                                <input type="number" class="form-control" name="areas" min="1" value="1" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Primary Assembly Area *</label>
                                <input type="text" class="form-control" name="primary_assembly_area" placeholder="e.g., Main Quadrangle" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Secondary Assembly Area</label>
                                <input type="text" class="form-control" name="secondary_assembly_area" placeholder="e.g., Back Gate Area">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Assembly Area Capacity</label>
                                <input type="number" class="form-control" name="assembly_capacity" placeholder="Total person capacity">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Special Instructions</label>
                                <textarea class="form-control" name="special_instructions" rows="3" placeholder="Any specific protocols..."></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Emergency Contacts</label>
                                <textarea class="form-control" name="emergency_contacts" rows="3" placeholder="Names and numbers..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveActivePlan('addSchoolPlanForm')">
                        <i class="fas fa-save me-2"></i> Save Plan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Building Plan Modal -->
    <div class="modal fade" id="editBuildingPlanModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i> Edit Evacuation Plan (<span class="edit-bldg-code">—</span>)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editBuildingPlanForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="plan_id" class="edit-plan-id">
                        <input type="hidden" name="building_id" class="building-id-field">

                        <h6 class="fw-bold text-primary mb-3">Edit Individual Plan Details</h6>
                        <hr>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Plan Name *</label>
                                <input type="text" class="form-control" name="plan_no" id="editBuildPlanNo" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Number of Routes *</label>
                                <input type="number" class="form-control" name="routes" id="editBuildRoutes" min="1" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Primary Evacuation Route *</label>
                                <textarea class="form-control" name="primary_route" id="editBuildPrimary" rows="3" required></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Secondary Evacuation Route</label>
                                <textarea class="form-control" name="secondary_route" id="editBuildSecondary" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Plan Status *</label>
                                <select class="form-control" name="status" id="editBuildStatus" required>
                                    <option value="active">Active</option>
                                    <option value="draft">Draft</option>
                                    <option value="review">Review</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Safety Features</label>
                                <input type="text" class="form-control" name="safety_features_installed" id="editBuildFeatures">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateActivePlan('editBuildingPlanForm')">
                        <i class="fas fa-save me-2"></i> Update Plan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit School Plan Modal -->
    <div class="modal fade" id="editSchoolPlanModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i> Edit Evacuation Plan (Entire School)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editSchoolPlanForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="plan_id" class="edit-plan-id">

                        <h6 class="fw-bold text-primary mb-3">Edit School-Wide Plan Details</h6>
                        <hr>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Plan Name *</label>
                                <input type="text" class="form-control" name="plan_no" id="editSchoolPlanNo" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Number of Assembly Areas *</label>
                                <input type="number" class="form-control" name="areas" id="editSchoolAreas" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Primary Assembly Area *</label>
                                <input type="text" class="form-control" name="primary_assembly_area" id="editSchoolPrimary" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Secondary Assembly Area</label>
                                <input type="text" class="form-control" name="secondary_assembly_area" id="editSchoolSecondary">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Assembly Area Capacity</label>
                                <input type="number" class="form-control" name="assembly_capacity" id="editSchoolCapacity">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Plan Status *</label>
                                <select class="form-control" name="status" id="editSchoolStatus" required>
                                    <option value="active">Active</option>
                                    <option value="draft">Draft</option>
                                    <option value="review">Review</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Special Instructions</label>
                                <textarea class="form-control" name="special_instructions" id="editSchoolInstructions" rows="3"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Emergency Contacts</label>
                                <textarea class="form-control" name="emergency_contacts" id="editSchoolContacts" rows="3"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateActivePlan('editSchoolPlanForm')">
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
                    @if(auth()->user()->role !== 'viewer')
                    <button type="button" class="btn btn-outline-secondary" id="printPlanBtn">
                        <i class="fas fa-print me-2"></i> Print Plan
                    </button>
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

    <!-- Facility Modals -->
    <div class="modal fade" id="addFacilityModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i> Add Facility</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addFacilityForm">
                        <input type="hidden" name="school_id" id="addFacilitySchoolId">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Facility Kind *</label>
                            <input type="text" class="form-control" name="name" placeholder="e.g., Covered Court, Parking" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea class="form-control" name="description" rows="2" placeholder="Small description..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Color / Legend *</label>
                            <div class="d-flex flex-wrap gap-2">
                                <label class="color-option"><input type="radio" name="color" value="#28a745" checked><span style="background:#28a745;"></span></label>
                                <label class="color-option"><input type="radio" name="color" value="#007bff"><span style="background:#007bff;"></span></label>
                                <label class="color-option"><input type="radio" name="color" value="#6f42c1"><span style="background:#6f42c1;"></span></label>
                                <label class="color-option"><input type="radio" name="color" value="#e83e8c"><span style="background:#e83e8c;"></span></label>
                                <label class="color-option"><input type="radio" name="color" value="#fd7e14"><span style="background:#fd7e14;"></span></label>
                                <label class="color-option"><input type="radio" name="color" value="#20c997"><span style="background:#20c997;"></span></label>
                                <label class="color-option"><input type="radio" name="color" value="#6c757d"><span style="background:#6c757d;"></span></label>
                                <style>.color-option input{display:none;} .color-option span{display:block;width:30px;height:30px;border-radius:4px;cursor:pointer;border:2px solid transparent;} .color-option input:checked+span{border-color:black;transform:scale(1.1);}</style>
                            </div>
                            <div class="invalid-feedback" id="addFacilityColorError"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="confirmCreateFacilityBtn" onclick="createNewFacility()">Create Facility</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editFacilityModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i> Update Facility</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editFacilityForm">
                        <input type="hidden" id="editFacilityId">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Facility Kind *</label>
                            <input type="text" class="form-control" name="name" id="editFacilityName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea class="form-control" name="description" id="editFacilityDesc" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Color / Legend *</label>
                            <div class="d-flex flex-wrap gap-2" id="editFacilityColorOptions">
                                <label class="color-option"><input type="radio" name="color" value="#28a745"><span style="background:#28a745;"></span></label>
                                <label class="color-option"><input type="radio" name="color" value="#007bff"><span style="background:#007bff;"></span></label>
                                <label class="color-option"><input type="radio" name="color" value="#6f42c1"><span style="background:#6f42c1;"></span></label>
                                <label class="color-option"><input type="radio" name="color" value="#e83e8c"><span style="background:#e83e8c;"></span></label>
                                <label class="color-option"><input type="radio" name="color" value="#fd7e14"><span style="background:#fd7e14;"></span></label>
                                <label class="color-option"><input type="radio" name="color" value="#20c997"><span style="background:#20c997;"></span></label>
                                <label class="color-option"><input type="radio" name="color" value="#6c757d"><span style="background:#6c757d;"></span></label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-danger" onclick="deleteFacility()">Delete</button>
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="updateFacilityAction()">Update Facility</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script>
        // Global variables
        let currentSchoolId = null;
        let currentPlanId = null;
        const userRole = "{{ auth()->user()->role }}";
        let drillsData = {};

        // Global check for viewer access
        function checkViewerAccess(formId) {
            if (userRole === 'viewer') {
                const form = document.getElementById(formId);
                if (form) {
                    const elements = form.querySelectorAll('input, select, textarea, button:not([data-bs-dismiss="modal"])');
                    elements.forEach(el => el.disabled = true);
                    const modal = form.closest('.modal');
                    if (modal) {
                        const saveBtn = modal.querySelector('.btn-primary');
                        if (saveBtn) saveBtn.style.display = 'none';
                    }
                }
            }
        }

        // Initialize state
        document.addEventListener('DOMContentLoaded', function() {
            @if($activeSchool)
                currentSchoolId = "{{ $activeSchool->id }}";
                loadSchoolData(currentSchoolId);
            @endif

            // Tomorrow's date for drill
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const drillInput = document.getElementById('drillDate');
            if (drillInput) drillInput.value = tomorrow.toISOString().split('T')[0];

            // Modal Trigger Listeners
            initModalListeners();
        });

        // Load all data for a school
        async function loadSchoolData(schoolId) {
            if (!schoolId) return;
            currentSchoolId = schoolId;

            await loadDrillHistory(schoolId);
            loadPlanStats(schoolId);
            loadSidebarStats(schoolId);

            // Check if map tab is active
            const mapTab = document.getElementById(`map-tab-${schoolId}`);
            if (mapTab && mapTab.classList.contains('active')) {
                if (typeof initEvacuationMap === 'function') initEvacuationMap(schoolId);
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
                const none = stats.no_plan || 0;
                const score = stats.avg_safety_score || 0;
                const coverage = total > 0 ? Math.round((active / total) * 100) : 0;

                // Update summary counters
                document.querySelectorAll('.active-plans-count').forEach(el => el.textContent = active);
                document.querySelectorAll('.total-buildings-count').forEach(el => el.textContent = total);
                document.querySelectorAll('.emergency-exits-count').forEach(el => el.textContent = stats.total_emergency_exits || 0);
                document.querySelectorAll('.total-alarms-count').forEach(el => el.textContent = stats.total_alarms || 0);
                document.querySelectorAll('.total-extinguishers-count').forEach(el => el.textContent = stats.total_extinguishers || 0);
                document.querySelectorAll('.coverage-percentage').forEach(el => el.textContent = coverage + '%');

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
                container.innerHTML = '<p class="text-danger small">Failed to load stats.</p>';
            }
        }

        async function loadSidebarStats(schoolId) {
            try {
                const response = await fetch(`/fire-safety/evacuation-sidebar-stats/${schoolId}`);
                const stats = await response.json();
                const container = document.getElementById('sidebarStats');
                if (!container) return;

                container.innerHTML = `
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
            } catch (error) { console.error('Error loading sidebar stats:', error); }
        }

        async function loadDrillHistory(schoolId) {
            const container = document.getElementById(`drillHistory-${schoolId}`);
            if (!container) return;

            try {
                container.innerHTML = '<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';
                const response = await fetch(`/fire-safety/drill-history/${schoolId}`);
                const drills = await response.json();
                drillsData[schoolId] = drills;

                if (!drills || drills.length === 0) {
                    container.innerHTML = `
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-calendar-times fa-3x mb-3" style="opacity: 0.2;"></i>
                            <p>No evacuation drills recorded.</p>
                            @if(auth()->user()->role !== 'viewer')
                            <button class="btn btn-primary" onclick="openScheduleDrillModal(${schoolId})">
                                <i class="fas fa-calendar-plus me-2"></i> Schedule First Drill
                            </button>
                            @endif
                        </div>
                    `;
                    return;
                }

                let html = '<div class="table-responsive"><table class="table table-hover"><thead><tr><th>Date</th><th>Type</th><th>Status</th><th>Participants</th><th>Evac. Time</th><th>Actions</th></tr></thead><tbody>';
                drills.forEach(drill => {
                    const statusColor = drill.status === 'completed' ? 'success' : (drill.status === 'scheduled' ? 'primary' : 'danger');
                    html += `
                        <tr>
                            <td>${new Date(drill.drill_date).toLocaleDateString()}</td>
                            <td><span class="badge bg-info">${drill.drill_type}</span></td>
                            <td><span class="badge bg-${statusColor}">${drill.status}</span></td>
                            <td>${drill.participants_count || '-'}</td>
                            <td>${drill.evacuation_time_minutes ? drill.evacuation_time_minutes + ' m' : '-'}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="viewDrillDetails(${drill.id})"><i class="fas fa-eye"></i></button>
                                @if(auth()->user()->role !== 'viewer')
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteDrill(${drill.id}, ${schoolId})"><i class="fas fa-trash"></i></button>
                                @endif
                            </td>
                        </tr>
                    `;
                });
                html += '</tbody></table></div>';
                container.innerHTML = html;
            } catch (error) {
                container.innerHTML = '<div class="alert alert-danger">Error loading drills.</div>';
            }
        }

        function initModalListeners() {
            // Add Building Plan
            const addBldgModal = document.getElementById('addBuildingPlanModal');
            if (addBldgModal) {
                addBldgModal.addEventListener('show.bs.modal', function(event) {
                    const btn = event.relatedTarget;
                    const bId = btn.getAttribute('data-building-id');
                    const bCode = btn.getAttribute('data-building-code');
                    const sId = btn.getAttribute('data-school-id') || currentSchoolId;

                    const form = document.getElementById('addBuildingPlanForm');
                    form.reset();
                    form.querySelector('.school-id-field').value = sId;
                    form.querySelector('.building-id-field').value = bId;
                    document.getElementById('addBuildBldgCode').textContent = bCode;

                    loadBuildingDetailsForPlan(bId, sId, form);
                    checkViewerAccess('addBuildingPlanForm');
                });
            }

            // Add School Plan
            const addSchoolModal = document.getElementById('addSchoolPlanModal');
            if (addSchoolModal) {
                addSchoolModal.addEventListener('show.bs.modal', function(event) {
                    const btn = event.relatedTarget;
                    const sId = btn.getAttribute('data-school-id') || currentSchoolId;
                    const form = document.getElementById('addSchoolPlanForm');
                    form.reset();
                    form.querySelector('.school-id-field').value = sId;
                    checkViewerAccess('addSchoolPlanForm');
                });
            }

            // Edit Building Plan
            const editBldgModal = document.getElementById('editBuildingPlanModal');
            if (editBldgModal) {
                editBldgModal.addEventListener('show.bs.modal', async function(event) {
                    const btn = event.relatedTarget;
                    const planId = btn.getAttribute('data-plan-id');
                    const form = document.getElementById('editBuildingPlanForm');
                    form.reset();

                    try {
                        const res = await fetch(`/fire-safety/evacuation-plan/${planId}`);
                        const data = await res.json();
                        form.querySelector('.edit-plan-id').value = data.id;
                        document.querySelectorAll('.edit-bldg-code').forEach(el => el.textContent = data.building?.building_no || '—');
                        document.getElementById('editBuildPlanNo').value = data.plan_no;
                        document.getElementById('editBuildRoutes').value = data.routes;
                        document.getElementById('editBuildPrimary').value = data.primary_route;
                        document.getElementById('editBuildSecondary').value = data.secondary_route;
                        document.getElementById('editBuildExits').value = data.exits;
                        document.getElementById('editBuildStatus').value = data.status;
                        document.getElementById('editBuildFeatures').value = data.safety_features_installed || '';
                        checkViewerAccess('editBuildingPlanForm');
                    } catch (e) { Swal.fire('Error', 'Failed to load plan', 'error'); }
                });
            }

            // Edit School Plan
            const editSchoolModal = document.getElementById('editSchoolPlanModal');
            if (editSchoolModal) {
                editSchoolModal.addEventListener('show.bs.modal', async function(event) {
                    const btn = event.relatedTarget;
                    const planId = btn.getAttribute('data-plan-id');
                    const form = document.getElementById('editSchoolPlanForm');
                    form.reset();

                    try {
                        const res = await fetch(`/fire-safety/evacuation-plan/${planId}`);
                        const data = await res.json();
                        form.querySelector('.edit-plan-id').value = data.id;
                        document.getElementById('editSchoolPlanNo').value = data.plan_no;
                        document.getElementById('editSchoolAreas').value = data.areas;
                        document.getElementById('editSchoolPrimary').value = data.primary_assembly_area;
                        document.getElementById('editSchoolSecondary').value = data.secondary_assembly_area;
                        document.getElementById('editSchoolCapacity').value = data.assembly_capacity;
                        document.getElementById('editSchoolStatus').value = data.status;
                        document.getElementById('editSchoolInstructions').value = data.special_instructions;
                        document.getElementById('editSchoolContacts').value = data.emergency_contacts;
                        checkViewerAccess('editSchoolPlanForm');
                    } catch (e) { Swal.fire('Error', 'Failed to load school plan', 'error'); }
                });
            }

            // View Plan Modal
            const viewModal = document.getElementById('viewPlanModal');
            if (viewModal) {
                viewModal.addEventListener('show.bs.modal', async function(event) {
                    const btn = event.relatedTarget;
                    const planId = btn.getAttribute('data-plan-id');
                    const content = document.getElementById('planDetailsContent');
                    content.innerHTML = '<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';

                    try {
                        const res = await fetch(`/fire-safety/evacuation-plan/${planId}/details`);
                        const responseData = await res.json();
                        const plan = responseData.plan;

                        content.innerHTML = `
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <p><strong>Plan Number:</strong> ${plan.plan_no}</p>
                                    <p><strong>Building:</strong> ${plan.building?.building_no || 'Entire School'}</p>
                                    <p><strong>Status:</strong> <span class="badge bg-success">${plan.status}</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Primary Assembly Area:</strong> ${plan.primary_assembly_area || 'N/A'}</p>
                                    <p><strong>Contacts:</strong> ${plan.emergency_contacts || 'N/A'}</p>
                                </div>
                            </div>
                            <div class="mb-3">
                                <h6>Primary Route</h6>
                                <div class="p-3 border rounded bg-light">${plan.primary_route || 'Not specified'}</div>
                            </div>
                        `;

                        if (responseData.school_buildings) {
                             // Initialize map if layout function exists
                             if (typeof generateAutoSchoolLayout === 'function') {
                                 const layoutContainer = document.createElement('div');
                                 layoutContainer.id = 'autoSchoolLayout';
                                 layoutContainer.className = 'mb-4';
                                 content.prepend(layoutContainer);
                                 generateAutoSchoolLayout(plan.building_id, responseData.school_buildings);
                             }
                        }

                        const deleteBtn = document.getElementById('deletePlanBtn');
                        const printBtn = document.getElementById('printPlanBtn');
                        if (deleteBtn) {
                            deleteBtn.style.display = userRole === 'viewer' ? 'none' : 'block';
                            deleteBtn.onclick = () => deletePlan(planId);
                        }
                        if (printBtn) {
                            printBtn.onclick = () => window.open(`/fire-safety/reports/evacuation-plans/${currentSchoolId}?plan_id=${planId}`, '_blank');
                        }
                    } catch (e) { content.innerHTML = '<p class="text-danger">Error loading details.</p>'; }
                });
            }
        }

        async function loadBuildingDetailsForPlan(buildingId, schoolId, form) {
            try {
                const res = await fetch(`/fire-safety/building/${buildingId}/evacuation-data`);
                const data = await res.json();
                if (data.success) {
                    const b = data.building;
                    const exitsField = form.querySelector('.display-exits');
                    const hiddenExits = form.querySelector('.hidden-exits');
                    const featuresField = form.querySelector('.display-features');
                    const hiddenFeatures = form.querySelector('.hidden-features');

                    if (exitsField) exitsField.value = b.emergency_exits || 0;
                    if (hiddenExits) hiddenExits.value = b.emergency_exits || 0;
                    if (featuresField) featuresField.value = b.features || 'None listed';
                    if (hiddenFeatures) hiddenFeatures.value = b.features || '';
                }
            } catch (error) { console.error('Error fetching building details:', error); }
        }

        async function saveActivePlan(formId) {
            const form = document.getElementById(formId);
            if (!form.checkValidity()) { form.reportValidity(); return; }

            const formData = new FormData(form);
            try {
                loadingSwal('Saving Plan...');
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                    body: formData
                });
                const data = await res.json();
                if (data.success) successSwal('Plan created!', () => location.reload());
                else errorSwal(data.message);
            } catch (e) { errorSwal('Unexpected error occurred.'); }
        }

        async function updateActivePlan(formId) {
            const form = document.getElementById(formId);
            if (!form.checkValidity()) { form.reportValidity(); return; }

            const planId = form.querySelector('.edit-plan-id').value;
            const formData = new FormData(form);
            formData.append('_method', 'PUT');

            try {
                loadingSwal('Updating Plan...');
                const res = await fetch(`/fire-safety/evacuation-plan/${planId}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                    body: formData
                });
                const data = await res.json();
                if (data.success) successSwal('Plan updated!', () => location.reload());
                else errorSwal(data.message);
            } catch (e) { errorSwal('Unexpected error occurred.'); }
        }

        function loadingSwal(text) { Swal.fire({ title: text, allowOutsideClick: false, didOpen: () => Swal.showLoading() }); }
        function successSwal(text, callback) { Swal.fire({ icon: 'success', title: 'Success', text: text, timer: 1500, showConfirmButton: false }).then(callback); }
        function errorSwal(text) { Swal.fire({ icon: 'error', title: 'Error', text: text }); }

        async function openScheduleDrillModal(sId) {
            const modalEl = document.getElementById('scheduleDrillModal');
            const modal = new bootstrap.Modal(modalEl);
            document.getElementById('drillSchoolId').value = sId;
            const list = document.getElementById('drillBuildingsList');
            list.innerHTML = '<div class="col-12 text-center py-3"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';

            try {
                const res = await fetch(`/fire-safety/drill-buildings/${sId}`);
                const buildings = await res.json();
                let html = '';
                if (buildings.length > 0) {
                    buildings.forEach(b => {
                        html += `<div class="col-md-6"><div class="form-check">
                            <input class="form-check-input building-checkbox" type="checkbox" value="${b.id}" id="drillBldg${b.id}">
                            <label class="form-check-label small" for="drillBldg${b.id}">${b.building_no}${b.building_name ? ' - ' + b.building_name : ''}</label>
                        </div></div>`;
                    });
                } else html = '<div class="col-12 text-center text-muted">No buildings found</div>';
                list.innerHTML = html;
                modal.show();
            } catch (e) { list.innerHTML = '<p class="text-danger">Error loading buildings.</p>'; }
        }

        async function saveDrillSchedule() {
            const form = document.getElementById('scheduleDrillForm');
            if (!form.checkValidity()) { form.reportValidity(); return; }
            const formData = new FormData(form);

            const scopeField = form.querySelector('input[name="building_scope"]:checked');
            const scope = scopeField ? scopeField.value : 'all';

            let checkboxes;
            if (scope === 'all') {
                checkboxes = document.querySelectorAll('.building-checkbox');
            } else {
                checkboxes = document.querySelectorAll('.building-checkbox:checked');
            }

            if (checkboxes.length === 0) {
                Swal.fire('Error', 'No buildings available or selected for this drill.', 'error');
                return;
            }

            checkboxes.forEach(cb => formData.append('building_ids[]', cb.value));

            try {
                loadingSwal('Scheduling...');
                const res = await fetch('/fire-safety/drill/schedule', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                    body: formData
                });
                const data = await res.json();
                if (data.success) successSwal('Drill scheduled!', () => location.reload());
                else errorSwal(data.message);
            } catch (e) { errorSwal('Error scheduling drill.'); }
        }

        async function viewDrillDetails(drillId) {
            try {
                const res = await fetch(`/fire-safety/drill/${drillId}`);
                const drill = await res.json();

                let bHtml = '';
                if (drill.buildings && drill.buildings.length > 0) {
                    drill.buildings.forEach(b => {
                        bHtml += `<span class="badge bg-secondary me-1">${b.building_no}</span>`;
                    });
                }

                Swal.fire({
                    title: 'Drill Details',
                    html: `
                        <div class="text-start">
                            <p><strong>Type:</strong> ${drill.drill_type}</p>
                            <p><strong>Date:</strong> ${new Date(drill.drill_date).toLocaleDateString()}</p>
                            <p><strong>Status:</strong> <span class="badge bg-info">${drill.status}</span></p>
                            <p><strong>Buildings involved:</strong><br>${bHtml || 'None'}</p>
                            <hr>
                            <p><strong>Participants:</strong> ${drill.participants_count || 'N/A'}</p>
                            <p><strong>Evac Time:</strong> ${drill.evacuation_time_minutes ? drill.evacuation_time_minutes + ' mins' : 'N/A'}</p>
                            <p><strong>Coordinator:</strong> ${drill.coordinator || 'N/A'}</p>
                            <p><strong>Remarks:</strong> ${drill.remarks || 'None'}</p>
                        </div>
                    `,
                    icon: 'info'
                });
            } catch (e) { errorSwal('Error loading drill details'); }
        }

        async function deleteDrill(drillId, schoolId) {
            const r = await Swal.fire({
                title: 'Cancel Drill?',
                text: "Are you sure you want to cancel this scheduled drill?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, cancel it!'
            });
            if (r.isConfirmed) {
                try {
                    const res = await fetch(`/fire-safety/drill/${drillId}/cancel`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
                    });
                    const d = await res.json();
                    if (d.success) successSwal('Drill cancelled!', () => loadDrillHistory(schoolId));
                    else errorSwal(d.message);
                } catch (e) { errorSwal('Error cancelling drill.'); }
            }
        }

        async function deletePlan(pId) {
            const r = await Swal.fire({ title: 'Delete Plan?', text: "You won't be able to revert this!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete it!' });
            if (r.isConfirmed) {
                try {
                    const res = await fetch(`/fire-safety/evacuation-plan/${pId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' } });
                    const d = await res.json();
                    if (d.success) successSwal('Deleted!', () => location.reload()); else errorSwal(d.message);
                } catch (e) { errorSwal('Error deleting plan.'); }
            }
        }

        function generateAutoSchoolLayout(targetBuildingId, buildings) {
            const container = document.getElementById('autoSchoolLayout');
            if (!container) return;
            let html = '<div class="main-division-box">';
            buildings.forEach(building => {
                const isTarget = building.id == targetBuildingId;
                const buildingHasAlarm = (building.alarm_systems_many && building.alarm_systems_many.length > 0);
                html += `<div class="building-box ${isTarget ? 'current-building' : ''} ${buildingHasAlarm ? 'has-alarm' : ''}">
                    <div class="building-title">${building.building_no}<div style="font-size: 0.6rem; color: #888;">${building.building_type || 'Building'}</div></div>`;
                const floorCount = building.floors || 1;
                for (let f = floorCount; f >= 1; f--) {
                    html += `<div class="floor-box"><div class="floor-title">${f === 1 ? '1st' : (f === 2 ? '2nd' : (f === 3 ? '3rd' : f + 'th'))} Floor</div><div class="rooms-container">`;
                    const roomsCount = building.rooms_count || 4;
                    for (let i = 1; i <= Math.min(roomsCount, 6); i++) html += `<div class="room-unit">${i}</div>`;
                    html += `</div></div>`;
                }
                html += `</div>`;
            });
            html += '</div>';
            container.innerHTML = html;
        }

        function toggleBuildingSelection() {
            const scope = document.querySelector('input[name="building_scope"]:checked').value;
            const container = document.getElementById('specificBuildingsContainer');
            if (container) container.style.display = scope === 'specific' ? 'block' : 'none';
        }

        function printAllPlans(sId) {
            window.open(`/fire-safety/reports/evacuation-plans/${sId}`, '_blank');
        }

        function viewPlan(pId, bId, bNo) {
            if (!pId) {
                const btn = document.querySelector(`.create-building-plan-btn[data-building-id="${bId}"]`);
                if (btn) btn.click();
            } else {
                const btn = document.querySelector(`.view-plan-btn[data-plan-id="${pId}"]`);
                if (btn) btn.click();
            }
        }

        // ==========================================
        // EVACUATION MAP LOGIC (Interactive Canvas)
        // ==========================================
        let mapDataArr = {};
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
                mapDataArr[schoolId] = school;

                // Sync facilities from layout to mapDataArr
                const savedLayout = school.evacuation_map_layout || {};
                school.facilities = [];
                Object.keys(savedLayout).forEach(id => {
                    if (id && String(id).startsWith('facility_')) {
                        school.facilities.push({
                            id: id,
                            ...savedLayout[id]
                        });
                    }
                });

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
            if (!canvas) return;
            canvas.innerHTML = '';

            const buildings = school.buildings || [];
            const facilities = school.facilities || [];
            
            // Fixed virtual size for consistent coordinates across devices
            canvas.style.width = '2400px'; 
            canvas.style.height = '1400px'; // Slightly reduced height to prevent excessive bottom space

            let x = 100, y = 100;
            const savedLayout = school.evacuation_map_layout || {};

            buildings.forEach((building, index) => {
                const buildingContainerDiv = document.createElement('div');
                buildingContainerDiv.className = 'map-element building-element';
                buildingContainerDiv.id = `map-bldg-${building.id}`;
                buildingContainerDiv.dataset.id = `building_${building.id}`;
                buildingContainerDiv.dataset.schoolId = schoolId;
                buildingContainerDiv.dataset.buildingId = building.id;
                buildingContainerDiv.dataset.buildingNo = building.building_no;
                buildingContainerDiv.style.position = 'absolute';

                // Building dimensions (reduced width; height calculated so rooms are square)
                const buildingWidth = 300;
                const floors = building.floors || 1;
                const roomsPerFloor = 3;
                // spacing between rooms/floors
                const roomGap = 10;
                const floorGap = 10;

                // compute room size based on available width
                const usableWidth = buildingWidth - roomGap * (roomsPerFloor + 1);
                const roomWidth = usableWidth / roomsPerFloor;
                const floorHeight = roomWidth; // square rooms
                // total building height = header (30px) + gaps + floors*floorHeight
                const buildingHeight = 30 + floorGap * (floors + 1) + floors * floorHeight;

                // Use saved coordinates/rotation if available (stored in DB JSON `evacuation_map_layout`)
                const savedLayout = school.evacuation_map_layout || school.map_layout || {};
                if (savedLayout && savedLayout[buildingContainerDiv.dataset.id]) {
                    buildingContainerDiv.style.left = savedLayout[buildingContainerDiv.dataset.id].x + 'px';
                    buildingContainerDiv.style.top = savedLayout[buildingContainerDiv.dataset.id].y + 'px';
                } else {
                    buildingContainerDiv.style.left = x + 'px';
                    buildingContainerDiv.style.top = y + 'px';

                    x += buildingWidth + 20;
                    if (x > 1200) { x = 50; y += buildingHeight + 50; }
                }

                buildingContainerDiv.style.width = buildingWidth + 'px';
                buildingContainerDiv.style.height = buildingHeight + 'px';
                buildingContainerDiv.style.backgroundColor = 'white';
                buildingContainerDiv.style.border = '3px solid black';
                buildingContainerDiv.style.borderRadius = '0px';
                buildingContainerDiv.style.boxShadow = '3px 3px 8px rgba(0,0,0,0.3)';
                buildingContainerDiv.style.cursor = 'default';
                buildingContainerDiv.style.overflow = 'hidden';
                buildingContainerDiv.style.padding = '0px';

                // Apply saved rotation (0/90/180/270)
                const savedRotation = (savedLayout && savedLayout[buildingContainerDiv.dataset.id] && savedLayout[buildingContainerDiv.dataset.id].rotation)
                    ? Number(savedLayout[buildingContainerDiv.dataset.id].rotation)
                    : 0;
                buildingContainerDiv.dataset.rotation = String(savedRotation);
                buildingContainerDiv.dataset.baseWidth = String(buildingWidth);
                buildingContainerDiv.dataset.baseHeight = String(buildingHeight);
                if (savedRotation) {
                    buildingContainerDiv.style.transformOrigin = 'top left';
                    buildingContainerDiv.style.transform = `rotate(${savedRotation}deg)`;
                }

                // Create building header
                const headerDiv = document.createElement('div');
                // make sure bell icon appears below header if it exists
                headerDiv.style.padding = '5px 8px';
                headerDiv.style.backgroundColor = '#007bff';
                headerDiv.style.color = 'white';
                headerDiv.style.fontSize = '12px';
                headerDiv.style.fontWeight = 'bold';
                headerDiv.style.borderBottom = '2px solid black';
                headerDiv.style.display = 'flex';
                headerDiv.style.justifyContent = 'space-between';
                headerDiv.style.alignItems = 'center';

                // Check if building has an evacuation plan (check both camelCase and snake_case)
                const hasPlan = building.evacuation_plan || building.evacuationPlan || school.evacuation_plan || school.evacuationPlan;

                headerDiv.innerHTML = `
                    <span>${building.building_no}</span>
                    <span style="font-size: 10px;">${building.building_name || ''}</span>
                    <span style="display:flex; align-items:center; gap:6px;">
                        <span style="background: ${hasPlan ? 'green' : 'red'}; padding: 2px 6px; border-radius: 3px; font-size: 9px;">
                            ${hasPlan ? 'Plan OK' : 'No Plan'}
                        </span>
                        <button type="button" class="btn btn-sm btn-light p-0 px-1" title="Rotate building" style="line-height:1;"
                            onmousedown="event.stopPropagation();"
                            onclick="rotateMapBuilding('${buildingContainerDiv.id}', ${schoolId}); event.stopPropagation();">
                            <i class="fas fa-rotate-right"></i>
                        </button>
                    </span>
                `;
                buildingContainerDiv.appendChild(headerDiv);

                // Create building interior canvas using SVG
                const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                svg.setAttribute('width', buildingWidth);
                svg.setAttribute('height', buildingHeight - 30);
                // allow pointer events so rooms and icons can be clicked
                svg.setAttribute('style', 'display: block; cursor: inherit;');

                // floorHeight and roomWidth already computed above for square rooms
                // const floorHeight = (buildingHeight - 30 - floorGap * (floors + 1)) / floors;
                // const roomWidth = (buildingWidth - roomGap * (roomsPerFloor + 1)) / roomsPerFloor;

                // Collect alarms for this building (supports both API shapes)
                const directAlarms = building.alarm_systems || building.alarmSystems || [];
                const coveredAlarms = building.alarm_systems_many || building.alarmSystemsMany || [];
                const alarms = [...directAlarms, ...coveredAlarms].filter((a, idx, arr) => {
                    const id = a && a.id ? a.id : null;
                    if (id === null) return true;
                    return arr.findIndex(x => x && x.id === id) === idx;
                });

                // Draw floors and rooms
                for (let floorIdx = 0; floorIdx < floors; floorIdx++) {
                    // bottom floor should appear lowest in the svg, so invert the y-coordinate calculation
                    const floorY = floorGap + (floors - 1 - floorIdx) * (floorHeight + floorGap);

                    // Draw horizontal lines for floor separators
                    if (floorIdx > 0) {
                        const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                        line.setAttribute('x1', '0');
                        line.setAttribute('y1', floorY);
                        line.setAttribute('x2', buildingWidth);
                        line.setAttribute('y2', floorY);
                        line.setAttribute('stroke', 'black');
                        line.setAttribute('stroke-width', '2');
                        svg.appendChild(line);
                    }

                    // Draw vertical lines for room separators
                    for (let roomIdx = 0; roomIdx < roomsPerFloor - 1; roomIdx++) {
                        const roomX = roomGap + (roomIdx + 1) * (roomWidth + roomGap);
                        const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                        line.setAttribute('x1', roomX);
                        line.setAttribute('y1', floorY);
                        line.setAttribute('x2', roomX);
                        line.setAttribute('y2', floorY + floorHeight);
                        line.setAttribute('stroke', 'black');
                        line.setAttribute('stroke-width', '1');
                        svg.appendChild(line);
                    }

                    // Draw a closing horizontal line at the bottom of the lowest floor (after inversion lowest floor is floorIdx 0)
                    if (floorIdx === 0) {
                        const bottomLine = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                        bottomLine.setAttribute('x1', '0');
                        bottomLine.setAttribute('y1', floorY + floorHeight);
                        bottomLine.setAttribute('x2', buildingWidth);
                        bottomLine.setAttribute('y2', floorY + floorHeight);
                        bottomLine.setAttribute('stroke', 'black');
                        bottomLine.setAttribute('stroke-width', '2');
                        svg.appendChild(bottomLine);
                    }

                    // Add rooms to each floor
                    const floorRooms = building.actual_rooms && Array.isArray(building.actual_rooms) ? building.actual_rooms.filter(r => (r.floor_no || r.floor) === (floorIdx + 1)) : [];

                    for (let roomIdx = 0; roomIdx < roomsPerFloor; roomIdx++) {
                        const roomX = roomGap + roomIdx * (roomWidth + roomGap);
                        const room = floorRooms.length > roomIdx ? floorRooms[roomIdx] : null;

                        // Draw room rectangle background (light gray)
                        const roomRect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
                        roomRect.setAttribute('x', roomX + 1);
                        roomRect.setAttribute('y', floorY + 1);
                        roomRect.setAttribute('width', roomWidth - 2);
                        roomRect.setAttribute('height', floorHeight - 2);
                        roomRect.setAttribute('fill', room ? '#f0f0f0' : '#ffffff');
                        roomRect.setAttribute('stroke', 'none');
                        if (room) {
                            roomRect.style.cursor = 'pointer';
                            roomRect.addEventListener('click', e => {
                                e.stopPropagation();
                                if (isMapEditable[schoolId]) return;
                                // navigate to extinguisher list for this room
                                openRoomExtinguishers(building.id, room.id, schoolId);
                            });
                        }
                        svg.appendChild(roomRect);

                        // Add room number/label
                        if (room) {
                            const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                            text.setAttribute('x', roomX + roomWidth / 2);
                            text.setAttribute('y', floorY + 12);
                            text.setAttribute('text-anchor', 'middle');
                            text.setAttribute('font-size', '9px');
                            text.setAttribute('font-weight', 'bold');
                            text.setAttribute('fill', '#333');
                            text.textContent = room.room_name || `R${room.id}`;
                            svg.appendChild(text);

                            // Add fire extinguisher icon if assigned to this room
                            const extinguishers = building.fire_extinguishers ? building.fire_extinguishers.filter(e => e.room_id === room.id) : [];
                            if (extinguishers.length > 0) {
                                const extIcon = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                                // place icon at center of room
                                extIcon.setAttribute('x', roomX + roomWidth / 2);
                                extIcon.setAttribute('y', floorY + floorHeight / 2 + 4);
                                extIcon.setAttribute('font-size', '16px');
                                extIcon.setAttribute('title', `${extinguishers.length} extinguisher(s)`);
                                extIcon.textContent = '🧯';
                                extIcon.style.cursor = 'pointer';
                                extIcon.onclick = e => {
                                    e.stopPropagation();
                                    if (isMapEditable[schoolId]) return;
                                    openRoomExtinguishers(building.id, room.id, schoolId);
                                };
                                svg.appendChild(extIcon);
                            }

                            // Add smoke detector for office/admin rooms
                            const isOfficeOrAdmin = room.room_type_config &&
                                                   (room.room_type_config.type_name &&
                                                   (room.room_type_config.type_name.toLowerCase().includes('office') ||
                                                    room.room_type_config.type_name.toLowerCase().includes('admin')));
                            if (isOfficeOrAdmin) {
                                const detectorIcon = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                                detectorIcon.setAttribute('x', roomX + roomWidth - 12);
                                detectorIcon.setAttribute('y', floorY + 14);
                                detectorIcon.setAttribute('font-size', '12px');
                                detectorIcon.setAttribute('title', 'Smoke detector');
                                detectorIcon.textContent = '⚪';
                                svg.appendChild(detectorIcon);
                            }
                        }
                    }
                    // place alarms icons on this floor, if any
                    const thisFloor = floorIdx + 1;

                    // Floor-specific alarms (exact floor match)
                    const floorAlarms = alarms.filter(a =>
                        a.floor_id &&
                        a.floor_id !== 'all' &&
                        Number(a.floor_id) === thisFloor
                    );
                    floorAlarms.forEach((a, idx) => {
                        const alarmIcon = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                        // place near left, inside this floor band
                        alarmIcon.setAttribute('x', roomGap + (idx * 18) + 5);
                        alarmIcon.setAttribute('y', floorY + floorHeight / 2);
                        alarmIcon.setAttribute('font-size', '16px');
                        alarmIcon.setAttribute('title', `Alarm: ${a.code}`);
                        alarmIcon.textContent = '🔔';
                        svg.appendChild(alarmIcon);
                    });

                }

                // All-floor / entire-building alarms: show once at the divider above the bottom floor (or top padding if 1 floor)
                const allFloorAlarms = alarms.filter(a => {
                    const v = (a && a.floor_id != null) ? String(a.floor_id).toLowerCase() : '';
                    return !v || v === 'all' || v.includes('all');
                });
                if (allFloorAlarms.length > 0) {
                    const dividerY = floorGap; // top of bottom floor band
                    allFloorAlarms.forEach((a, idx) => {
                        const alarmIcon = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                        // centered horizontally, on the divider between floors
                        alarmIcon.setAttribute('x', buildingWidth / 2 + idx * 18);
                        alarmIcon.setAttribute('y', dividerY);
                        alarmIcon.setAttribute('font-size', '16px');
                        alarmIcon.setAttribute('title', `Alarm (All Floors): ${a.code}`);
                        alarmIcon.textContent = '🔔';
                        svg.appendChild(alarmIcon);
                    });
                }

                // Draw outer border of building
                const outerBorder = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
                outerBorder.setAttribute('x', '0');
                outerBorder.setAttribute('y', '0');
                outerBorder.setAttribute('width', buildingWidth);
                outerBorder.setAttribute('height', buildingHeight - 30);
                outerBorder.setAttribute('fill', 'none');
                outerBorder.setAttribute('stroke', 'black');
                outerBorder.setAttribute('stroke-width', '3');
                svg.appendChild(outerBorder);

                buildingContainerDiv.appendChild(svg);

                // Add info button
                const infoBtn = document.createElement('div');
                infoBtn.style.position = 'absolute';
                infoBtn.style.bottom = '5px';
                infoBtn.style.left = '5px';
                infoBtn.style.cursor = 'pointer';
                infoBtn.style.fontSize = '14px';
                infoBtn.innerHTML = '<i class="fas fa-info-circle" style="color: #0066cc;"></i>';
                const planData = building.evacuation_plan || building.evacuationPlan;
                infoBtn.onclick = (e) => {
                    e.stopPropagation();
                    viewPlan(planData ? planData.id : 'null', building.id, building.building_no);
                };
                buildingContainerDiv.appendChild(infoBtn);

                canvas.appendChild(buildingContainerDiv);

                // Add click handler to open context menu
                buildingContainerDiv.addEventListener('click', function(e) {
                    if (!isMapEditable[schoolId] && !e.target.closest('svg') && e.target !== infoBtn) {
                        e.preventDefault();
                        showBuildingOptions(building.id, building.building_no, schoolId);
                    }
                });

                makeDraggable(buildingContainerDiv, schoolId);
            });

            // Render Facilities
            facilities.forEach(facility => {
                renderFacility(facility, schoolId, savedLayout);
            });

            // When locked, auto-fit all buildings to the visible canvas area
            applyMapFit(schoolId);
        }

        function renderFacility(facility, schoolId, savedLayout) {
            const canvas = document.getElementById(`school-map-canvas-${schoolId}`);
            if (!canvas) {
                console.error('Canvas not found for school:', schoolId);
                return;
            }
            
            const facilityDiv = document.createElement('div');
            facilityDiv.className = 'map-element facility-element';
            facilityDiv.id = facility.id;
            facilityDiv.dataset.id = facility.id;
            facilityDiv.dataset.type = 'facility';
            facilityDiv.dataset.schoolId = schoolId;
            facilityDiv.dataset.name = facility.name;
            facilityDiv.dataset.description = facility.description || '';
            facilityDiv.dataset.color = facility.color;
            
            facilityDiv.style.position = 'absolute';
            facilityDiv.style.width = '200px';
            facilityDiv.style.height = '100px';
            facilityDiv.style.backgroundColor = facility.color;
            facilityDiv.style.left = (facility.x || 300) + 'px';
            facilityDiv.style.top = (facility.y || 300) + 'px';
            facilityDiv.textContent = facility.name;
            
            facilityDiv.onclick = (e) => {
                if (isMapEditable[schoolId]) return;
                openEditFacilityModal(facility, schoolId);
            };

            canvas.appendChild(facilityDiv);
            makeDraggable(facilityDiv, schoolId);
        }

        function applyMapFit(schoolId) {
            const canvas = document.getElementById(`school-map-canvas-${schoolId}`);
            if (!canvas) return;

            const container = canvas.parentElement;
            if (!container) return;

            const els = Array.from(canvas.querySelectorAll('.map-element'));
            if (els.length === 0) {
                 const scale = container.clientWidth / parseFloat(canvas.style.width || 2400);
                 canvas.style.transformOrigin = 'top left';
                 canvas.style.transform = `scale(${scale})`;
                 return;
            }

            const pad = 40;
            let minX = Infinity, minY = Infinity, maxX = -Infinity, maxY = -Infinity;

            els.forEach(el => {
                const left = parseFloat(el.style.left);
                const top = parseFloat(el.style.top);
                const isFacility = el.classList.contains('facility-element');
                const bw = isFacility ? 200 : parseFloat(el.dataset.baseWidth || 300);
                const bh = isFacility ? 100 : parseFloat(el.dataset.baseHeight || 150);
                const rotation = parseInt(el.dataset.rotation || 0);
                
                const isRotated = (rotation % 180 !== 0);
                const w = isRotated ? bh : bw;
                const h = isRotated ? bw : bh;

                minX = Math.min(minX, left);
                minY = Math.min(minY, top);
                maxX = Math.max(maxX, left + w);
                maxY = Math.max(maxY, top + h);
            });

            const boundsW = Math.max(400, maxX - minX);
            const boundsH = Math.max(300, maxY - minY);
            const availW = container.clientWidth - pad * 2;
            const availH = container.clientHeight - pad * 2;
            
            let scale = Math.min(availW / boundsW, availH / boundsH);
            if (window.innerWidth < 768) scale = Math.max(scale, 0.4);
            
            const tx = (container.clientWidth - boundsW * scale) / 2 - minX * scale;
            let ty = (container.clientHeight - boundsH * scale) / 2 - minY * scale;
            
            // Adjust centering to allow placement at the top without forced empty space
            if (ty > pad && minY < 200) {
                ty = pad - minY * scale;
            }

            canvas.style.transformOrigin = 'top left';
            canvas.style.transform = `translate(${tx}px, ${ty}px) scale(${scale})`;
        }
         // Toggle division function
        function toggleDivision(icon, cardId) {
            const card = icon.closest('.card');
            card.id = cardId;
            card.classList.toggle('card-collapsed');

            const cardStates = JSON.parse(localStorage.getItem('fireSafetyExtCardStates') || '{}');
            cardStates[cardId] = card.classList.contains('card-collapsed') ? 'collapsed' : 'expanded';
            localStorage.setItem('fireSafetyExtCardStates', JSON.stringify(cardStates));
        }
        function rotateMapBuilding(elementId, schoolId) {
            const el = document.getElementById(elementId);
            if (!el) return;
            if (!isMapEditable[schoolId]) return;
            
            const bw = parseFloat(el.dataset.baseWidth || 300);
            const bh = parseFloat(el.dataset.baseHeight || 150);
            const current = Number(el.dataset.rotation || 0);
            const next = (current + 90) % 360;
            
            // Adjust position slightly to prevent building from flying off-canvas
            // 90deg rotation around top-left (0,0) moves (w,0) to (0,w) and (w,h) to (-h,w)
            // So if we rotate 90deg, the building's new bounding box starts H units to the left of the original.
            let x = parseFloat(el.style.left);
            let y = parseFloat(el.style.top);

            if (next === 90) x += bh;
            else if (next === 180) y += bh;
            else if (next === 270) x -= bh;
            else if (next === 0) y -= bh;

            el.dataset.rotation = String(next);
            el.style.left = x + 'px';
            el.style.top = y + 'px';
            el.style.transformOrigin = 'top left';
            el.style.transform = `rotate(${next}deg)`;
            
            clampMapElementToCanvas(el, schoolId);
        }

        function clampMapElementToCanvas(element, schoolId) {
            const canvas = document.getElementById(`school-map-canvas-${schoolId}`);
            if (!canvas) return;

            const isFacility = element.classList.contains('facility-element');
            const bw = isFacility ? 200 : parseFloat(element.dataset.baseWidth || 300);
            const bh = isFacility ? 100 : parseFloat(element.dataset.baseHeight || 150);
            const rotation = parseInt(element.dataset.rotation || 0);
            const isRotated = (rotation % 180 !== 0);
            const w = isRotated ? bh : bw;
            const h = isRotated ? bw : bh;

            const virtualW = parseFloat(canvas.style.width) || 2400;
            const virtualH = parseFloat(canvas.style.height) || 1400;

            const maxLeft = Math.max(0, virtualW - w);
            const maxTop = Math.max(0, virtualH - h);

            const left = Math.min(Math.max(0, element.offsetLeft), maxLeft);
            const top = Math.min(Math.max(0, element.offsetTop), maxTop);

            element.style.left = left + 'px';
            element.style.top = top + 'px';
        }

        function showBuildingOptions(buildingId, buildingNo, schoolId) {
            const menu = `
                <div class="contextMenu" id="buildingContextMenu" style="position: fixed; background: white; border: 1px solid #ccc; border-radius: 4px; box-shadow: 0 2px 10px rgba(0,0,0,0.2); z-index: 10000;">
                    <div style="padding: 10px; min-width: 200px;">
                        <h6 style="margin: 0 0 10px 0; padding-bottom: 10px; border-bottom: 1px solid #eee;">Add to Building ${buildingNo}</h6>
                        <button class="btn btn-sm btn-block btn-outline-primary mb-2" onclick="openAddRoom(${buildingId}, '${buildingNo}', ${schoolId}); closeContextMenu();" style="width: 100%; text-align: left; padding: 8px 10px; border-radius: 3px; display: block;">
                            <i class="fas fa-door-open me-2"></i> Add Room
                        </button>
                        <button class="btn btn-sm btn-block btn-outline-danger mb-2" onclick="openAddFloor(${buildingId}, '${buildingNo}', ${schoolId}); closeContextMenu();" style="width: 100%; text-align: left; padding: 8px 10px; border-radius: 3px; display: block;">
                            <i class="fas fa-layer-group me-2"></i> Add Floor
                        </button>
                        <button class="btn btn-sm btn-block btn-outline-warning mb-2" onclick="openAddExtinguisher(${buildingId}, '${buildingNo}', ${schoolId}); closeContextMenu();" style="width: 100%; text-align: left; padding: 8px 10px; border-radius: 3px; display: block;">
                            <i class="fas fa-fire-extinguisher me-2"></i> Add Extinguisher
                        </button>
                        <button class="btn btn-sm btn-block btn-outline-danger" onclick="openAddAlarm(${buildingId}, '${buildingNo}', ${schoolId}); closeContextMenu();" style="width: 100%; text-align: left; padding: 8px 10px; border-radius: 3px; display: block;">
                            <i class="fas fa-bell me-2"></i> Add Alarm
                        </button>
                    </div>
                </div>
            `;

            let existingMenu = document.getElementById('buildingContextMenu');
            if (existingMenu) {
                existingMenu.remove();
            }

            document.body.insertAdjacentHTML('beforeend', menu);

            const menuElement = document.getElementById('buildingContextMenu');
            menuElement.style.left = (event.pageX) + 'px';
            menuElement.style.top = (event.pageY) + 'px';

            document.addEventListener('click', closeContextMenuOnClickOutside);
        }

        function closeContextMenu() {
            const menu = document.getElementById('buildingContextMenu');
            if (menu) {
                menu.remove();
            }
        }

        function closeContextMenuOnClickOutside(e) {
            const menu = document.getElementById('buildingContextMenu');
            if (menu && !menu.contains(e.target) && e.target.id !== 'buildingContextMenu') {
                menu.remove();
                document.removeEventListener('click', closeContextMenuOnClickOutside);
            }
        }

        function openAddRoom(buildingId, buildingNo, schoolId) {
            // Redirect to buildings page or open modal to add room
            window.location.href = `/fire-safety/buildings?tab=tab-rooms&building=${buildingId}`;
        }

        function openAddFloor(buildingId, buildingNo, schoolId) {
            // Use the building edit modal
            Swal.fire({
                title: 'Add Floor to Building',
                html: `Building: <strong>${buildingNo}</strong><br><br>
                       <input type="number" id="floorInput" class="form-control" placeholder="Number of floors" min="1" max="5">`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Update',
                confirmButtonColor: '#dc3545'
            }).then(result => {
                if (result.isConfirmed) {
                    const floors = parseInt(document.getElementById('floorInput').value);
                    if (!isNaN(floors) && floors > 0) {
                        updateBuildingFloors(buildingId, floors);
                    } else {
                        errorSwal('Please enter a valid number of floors');
                    }
                }
            });
        }

        function updateBuildingFloors(buildingId, floors) {
            fetch(`/fire-safety/building/${buildingId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ floors: floors })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    successSwal('Floors updated successfully!');
                    // Refresh the map
                    const schoolId = data.school_id;
                    initEvacuationMap(schoolId);
                } else {
                    errorSwal(data.message || 'Failed to update floors');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorSwal('Error updating floors');
            });
        }

        function openAddExtinguisher(buildingId, buildingNo, schoolId) {
            // Redirect to extinguishers page
            window.location.href = `/fire-safety/extinguishers?tab=tab-extinguishers&building=${buildingId}`;
        }

        function openAddAlarm(buildingId, buildingNo, schoolId) {
            // Redirect to alarm systems page
            window.location.href = `/fire-safety/alarm-systems?building=${buildingId}`;
        }

        // removed openRoom; use openRoomExtinguishers for room clicks

        function openRoomExtinguishers(buildingId, roomId, schoolId) {
            // navigate to extinguisher list filtered by building and room
            window.location.href = `/fire-safety/extinguishers?tab=tab-extinguishers&building=${buildingId}&room=${roomId}`;
        }

        function toggleMapEdit(schoolId) {
            isMapEditable[schoolId] = !isMapEditable[schoolId];
            const btn = document.getElementById(`edit-placement-btn-${schoolId}`);
            const saveBtn = document.getElementById(`save-placement-btn-${schoolId}`);

            if (isMapEditable[schoolId]) {
                btn.innerHTML = '<i class="fas fa-lock me-2"></i> Lock Placement';
                btn.classList.replace('btn-outline-primary', 'btn-warning');
                if (saveBtn) saveBtn.disabled = false;

                document.querySelectorAll(`#school-map-canvas-${schoolId} .map-element`).forEach(el => {
                    el.style.cursor = 'move';
                    el.style.borderColor = '#ffc107';
                });
                // remove fit while editing
                applyMapFit(schoolId);
            } else {
                btn.innerHTML = '<i class="fas fa-arrows-alt me-2"></i> Edit Placement';
                btn.classList.replace('btn-warning', 'btn-outline-primary');
                if (saveBtn) saveBtn.disabled = true;

                document.querySelectorAll(`#school-map-canvas-${schoolId} .map-element`).forEach(el => {
                    el.style.cursor = 'default';
                    el.style.borderColor = 'black';
                });
                // re-fit when locked
                applyMapFit(schoolId);
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
                
                // Account for canvas scale during drag
                const canvas = document.getElementById(`school-map-canvas-${schoolId}`);
                const transform = canvas.style.transform;
                const scaleMatch = transform.match(/scale\(([^)]+)\)/);
                const scale = scaleMatch ? parseFloat(scaleMatch[1]) : 1;

                pos1 = (pos3 - e.clientX) / scale;
                pos2 = (pos4 - e.clientY) / scale;
                pos3 = e.clientX;
                pos4 = e.clientY;
                
                element.style.top = (element.offsetTop - pos2) + "px";
                element.style.left = (element.offsetLeft - pos1) + "px";
                clampMapElementToCanvas(element, schoolId);
            }

            function closeDragElement() {
                document.onmouseup = null;
                document.onmousemove = null;
            }
        }

        function printEvacuationMap(schoolId) {
            // Force fit one last time before printing
            applyMapFit(schoolId);
            setTimeout(() => {
                window.print();
            }, 500);
        }

        async function saveMapLayout(schoolId) {
            const canvas = document.getElementById(`school-map-canvas-${schoolId}`);
            const elements = canvas.querySelectorAll('.map-element');
            const layout = {};

            elements.forEach(el => {
                const id = el.dataset.id;
                if (el.classList.contains('facility-element')) {
                    layout[id] = {
                        type: 'facility',
                        name: el.dataset.name,
                        description: el.dataset.description,
                        color: el.dataset.color,
                        x: parseFloat(el.style.left),
                        y: parseFloat(el.style.top)
                    };
                } else {
                    layout[id] = {
                        x: parseFloat(el.style.left),
                        y: parseFloat(el.style.top),
                        rotation: Number(el.dataset.rotation || 0)
                    };
                }
            });

            try {
                loadingSwal('Saving layout...');
                const response = await fetch(`/fire-safety/school/${schoolId}/map-save`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ layout: layout })
                });

                const result = await response.json();
                if (result.success) {
                    successSwal('Map layout saved successfully!', () => {
                        if (isMapEditable[schoolId]) toggleMapEdit(schoolId);
                        location.reload(); 
                    });
                } else {
                    errorSwal(result.message || 'Failed to save layout');
                }
            } catch (error) {
                console.error('Error saving map:', error);
                errorSwal('Error connecting to server.');
            }
        }

        // Facility Actions
        function openAddFacilityModal(schoolId) {
            currentSchoolId = schoolId;
            const input = document.getElementById('addFacilitySchoolId');
            if (input) input.value = schoolId;
            
            const modalEl = document.getElementById('addFacilityModal');
            let modal = bootstrap.Modal.getInstance(modalEl);
            if (!modal) modal = new bootstrap.Modal(modalEl);
            modal.show();
        }

        function createNewFacility() {
            try {
                // 1. Get the form and verify it exists
                const form = document.getElementById('addFacilityForm');
                if (!form) {
                    alert('Debug: addFacilityForm not found');
                    return;
                }
                
                // 2. Extract values safely
                const nameInput = form.querySelector('[name="name"]');
                const descInput = form.querySelector('[name="description"]');
                const colorInput = form.querySelector('[name="color"]:checked');
                const schoolIdInput = document.getElementById('addFacilitySchoolId');
                
                const name = nameInput ? nameInput.value.trim() : "";
                const description = descInput ? descInput.value.trim() : "";
                const color = colorInput ? colorInput.value : "#28a745";
                const sId = schoolIdInput ? schoolIdInput.value : currentSchoolId;

                if (!name) {
                    Swal.fire('Required', 'Please enter a facility name.', 'warning');
                    return;
                }

                if (!sId) {
                    console.error('School ID missing', { formSId: schoolIdInput ? schoolIdInput.value : null, globalSId: currentSchoolId });
                    Swal.fire('Error', 'Unable to determine target school. Please close and reopen the modal.', 'error');
                    return;
                }

                // 3. Create facility object
                const facilityId = 'facility_' + Date.now();
                const facility = {
                    id: facilityId,
                    name: name,
                    description: description,
                    color: color,
                    x: 800, // Central-ish on the 2400 width
                    y: 600
                };
                
                // 4. Update data structure
                if (typeof mapDataArr === 'undefined') window.mapDataArr = {};
                if (!mapDataArr[sId]) mapDataArr[sId] = { buildings: [], facilities: [] };
                if (!mapDataArr[sId].facilities) mapDataArr[sId].facilities = [];
                mapDataArr[sId].facilities.push(facility);
                
                // 5. Render it
                renderFacility(facility, sId, {});
                
                // 6. UI Updates
                const modalEl = document.getElementById('addFacilityModal');
                if (window.bootstrap && bootstrap.Modal) {
                    const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    if (modal && typeof modal.hide === 'function') modal.hide();
                } else {
                    // Fallback hide
                    $(modalEl).modal('hide'); 
                }
                
                // Enable save button
                const saveBtn = document.getElementById(`save-placement-btn-${sId}`);
                if (saveBtn) {
                    saveBtn.disabled = false;
                    saveBtn.classList.remove('btn-secondary');
                    saveBtn.classList.add('btn-primary');
                }
                
                // Unlock map for positioning
                if (!isMapEditable[sId]) toggleMapEdit(sId);
                
                form.reset();
                Swal.fire({
                    icon: 'success',
                    title: 'Facility Saved to Map',
                    text: 'Now drag the rectangle to its correct location and click "Save Layout".',
                    timer: 3000
                });
            } catch (err) {
                console.error('Internal Error in createNewFacility:', err);
                alert('An error occurred. Check browser console or contact support: ' + err.message);
            }
        }

        function openEditFacilityModal(facility, schoolId) {
            document.getElementById('editFacilityId').value = facility.id;
            document.getElementById('editFacilityName').value = facility.name;
            document.getElementById('editFacilityDesc').value = facility.description || '';
            
            const colorOption = document.querySelector(`#editFacilityColorOptions input[value="${facility.color}"]`);
            if (colorOption) colorOption.checked = true;

            const modal = new bootstrap.Modal(document.getElementById('editFacilityModal'));
            modal.show();
        }

        function updateFacilityAction() {
            const id = document.getElementById('editFacilityId').value;
            const el = document.getElementById(id);
            if (!el) return;

            const name = document.getElementById('editFacilityName').value;
            const desc = document.getElementById('editFacilityDesc').value;
            const color = document.querySelector('#editFacilityColorOptions input:checked').value;

            el.dataset.name = name;
            el.dataset.description = desc;
            el.dataset.color = color;
            el.style.backgroundColor = color;
            el.textContent = name;

            bootstrap.Modal.getInstance(document.getElementById('editFacilityModal')).hide();
            const saveBtn = document.getElementById(`save-placement-btn-${currentSchoolId}`);
            if (saveBtn) saveBtn.disabled = false;
        }

        function togglePlanView(mode, schoolId) {
            const schoolView = document.getElementById(`school-plan-view-${schoolId}`);
            const buildingView = document.getElementById(`building-plans-view-${schoolId}`);
            
            if (mode === 'school') {
                schoolView.style.display = 'block';
                buildingView.style.display = 'none';
            } else {
                schoolView.style.display = 'none';
                buildingView.style.display = 'block';
            }
        }

        function deleteFacility() {
            const id = document.getElementById('editFacilityId').value;
            const el = document.getElementById(id);
            if (el) el.remove();
            
            bootstrap.Modal.getInstance(document.getElementById('editFacilityModal')).hide();
            const saveBtn = document.getElementById(`save-placement-btn-${currentSchoolId}`);
            if (saveBtn) saveBtn.disabled = false;
        }
    </script>
@endsection
