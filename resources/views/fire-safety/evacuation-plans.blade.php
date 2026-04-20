@extends('layouts.fire-safety')

@section('title', 'Evacuation Plans - Fire Safety')
@section('page_title', 'Evacuation Plans')
@section('content')
    <div class="container-fluid {{ request()->boolean('print_map') ? 'evac-map-print-mode' : '' }}">
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
                /* Force browsers to print background colors & images */
                * {
                    -webkit-print-color-adjust: exact !important;
                    print-color-adjust: exact !important;
                    color-adjust: exact !important;
                }
                .building-element, .facility-element, .map-element {
                    -webkit-print-color-adjust: exact !important;
                    print-color-adjust: exact !important;
                    color-adjust: exact !important;
                }
            }
            .facility-element { border-radius: 4px; display: flex; align-items: center; justify-content: center; text-align: center; color: white; font-weight: bold; font-size: 14px; text-shadow: 1px 1px 2px rgba(0,0,0,0.5); border: 2px solid rgba(0,0,0,0.2); }

            /* Toggle Division Styles */
            .toggle-icon {
                cursor: pointer;
                transition: transform 0.3s ease;
                margin-right: 8px;
            }
            .card-collapsed .card-body {
                display: none;
            }
            .card-collapsed .toggle-icon {
                transform: rotate(-90deg);
            }

            .evac-map-print-mode .row.mb-4,
            .evac-map-print-mode .card-header,
            .evac-map-print-mode [id^="plans-content-"] {
                display: none !important;
            }

            .evac-map-print-mode [id^="map-content-"] > .d-flex.justify-content-between.align-items-center,
            .evac-map-print-mode .mt-3.p-3.bg-white {
                display: none !important;
            }

            .evac-map-print-mode .card,
            .evac-map-print-mode .card-body,
            .evac-map-print-mode .tab-content,
            .evac-map-print-mode .tab-pane,
            .evac-map-print-mode [id^="generated-map-view-"] {
                border: none !important;
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .evac-map-print-mode .school-map-canvas-container {
                width: 100% !important;
                height: 980px !important;
                border-radius: 0 !important;
            }
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
                        <div class="card dashboard-card" id="recent-inspections-card-{{ $school->id }}">
                            <div class="card-header py-2 px-3 d-flex align-items-center bg-light">
                                <i class="fas fa-chevron-down toggle-icon me-3" onclick="toggleDivision(this, 'recent-inspections-card-{{ $school->id }}')"></i>
                                <div class="school-tabs overflow-hidden">
                                    <nav>
                                        <div class="nav nav-tabs border-0" id="evacuationTabs-{{ $school->id }}" role="tablist">
                                            <button class="nav-link school-tab-btn active" id="plans-tab-{{ $school->id }}" data-bs-toggle="tab" data-bs-target="#plans-content-{{ $school->id }}" type="button" role="tab" aria-controls="plans-content-{{ $school->id }}" aria-selected="true" data-tab-key="plans">
                                                <i class="fas fa-list me-2"></i> Building Evacuation Plans
                                            </button>
                                            <button class="nav-link school-tab-btn" id="map-tab-{{ $school->id }}" data-bs-toggle="tab" data-bs-target="#map-content-{{ $school->id }}" type="button" role="tab" aria-controls="map-content-{{ $school->id }}" aria-selected="false" data-tab-key="map" onclick="initEvacuationMap({{ $school->id }})">
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
                                    <!-- Consolidated Map Header -->
                                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                        <div>
                                            <h6 class="fw-bold text-primary mb-1">
                                                Visual Evacuation Map
                                            </h6>
                                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Switch between Generated Map layout or Attached JPEG/PNG.</small>
                                        </div>
                                         <div class="d-flex align-items-center gap-2">
                                             @if(auth()->user()->role !== 'viewer')
                                                 <!-- Generated Map Actions (Hidden in Attached mode) -->
                                                 <div id="generated-actions-{{ $school->id }}" class="d-flex align-items-center gap-2">
                                                    <button class="btn btn-outline-success btn-sm no-print" onclick="openAddFacilityModal({{ $school->id }})">
                                                        <i class="fas fa-plus me-1"></i> Add Facility
                                                    </button>
                                                    <div class="dropdown no-print">
                                                        <button class="btn btn-outline-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="btn-specifics-{{ $school->id }}" style="display: none;">
                                                            <i class="fas fa-th me-1"></i> Specifics
                                                        </button>
                                                        <div class="dropdown-menu p-3 shadow-lg" style="min-width: 250px;">
                                                            <h6 class="dropdown-header px-0 mb-2 border-bottom">Drag items to Map</h6>
                                                            <div class="row g-2 text-center" id="specifics-list-{{ $school->id }}">
                                                                <div class="col-4">
                                                                    <div class="specific-item p-2 border rounded" style="cursor: pointer;" onclick="addSpecificToMap('door', {{ $school->id }})">
                                                                        <i class="fas fa-door-open fa-lg mb-1"></i><br><small>Door</small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="specific-item p-2 border rounded" style="cursor: pointer;" onclick="addSpecificToMap('stairs', {{ $school->id }})">
                                                                        <i class="fas fa-stairs fa-lg mb-1"></i><br><small>Stairs</small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="specific-item p-2 border rounded" style="cursor: pointer;" onclick="addSpecificToMap('table', {{ $school->id }})">
                                                                        <i class="fas fa-table-list fa-lg mb-1"></i><br><small>Table</small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="specific-item p-2 border rounded" style="cursor: pointer;" onclick="addSpecificToMap('bell', {{ $school->id }})">
                                                                        <i class="fas fa-bell fa-lg mb-1"></i><br><small>Bell</small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="specific-item p-2 border rounded" style="cursor: pointer;" onclick="addSpecificToMap('medical', {{ $school->id }})">
                                                                        <i class="fas fa-kit-medical fa-lg mb-1 text-danger"></i><br><small>First Aid</small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="specific-item p-2 border rounded" style="cursor: pointer;" onclick="addSpecificToMap('arrow', {{ $school->id }})">
                                                                        <i class="fas fa-arrow-right fa-lg mb-1"></i><br><small>Arrow</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-outline-primary btn-sm no-print" id="edit-placement-btn-{{ $school->id }}" onclick="toggleMapEdit({{ $school->id }})">
                                                        <i class="fas fa-arrows-alt me-1"></i> Edit Place
                                                    </button>
                                                 </div>
                                                 <!-- Shared Actions -->
                                                 <button class="btn btn-outline-secondary btn-sm no-print" onclick="printEvacuationMap({{ $school->id }})">
                                                     <i class="fas fa-print me-1"></i> Print Map
                                                 </button>
                                                 <button class="btn btn-outline-warning btn-sm no-print" onclick="openNotifyAdminModal({{ $school->id }})">
                                                     <i class="fas fa-bullhorn me-1"></i> Notify Admin
                                                 </button>
                                                 <!-- Moding Toggle -->
                                                 <div class="btn-group btn-group-sm no-print" role="group">
                                                     <input type="radio" class="btn-check" name="mapViewMode-{{ $school->id }}" id="generatedMapView-{{ $school->id }}" autocomplete="off" checked onclick="toggleMapView('generated', {{ $school->id }})">
                                                     <label class="btn btn-outline-primary" for="generatedMapView-{{ $school->id }}">Generated Layout</label>
                                                     <input type="radio" class="btn-check" name="mapViewMode-{{ $school->id }}" id="attachedMapView-{{ $school->id }}" autocomplete="off" onclick="toggleMapView('attached', {{ $school->id }})">
                                                     <label class="btn btn-outline-primary" for="attachedMapView-{{ $school->id }}">Attached Image</label>
                                                 </div>
                                             @endif
                                         </div>
                                    </div>

                                    <!-- GENERATED VIEW -->
                                    <div id="generated-map-view-{{ $school->id }}">

                                        <div class="school-map-canvas-container" style="position: relative; width: 100%; height: 800px; background: #e9ecef; border: 2px solid #333; overflow: hidden; border-radius: 4px; box-shadow: inset 0 0 20px rgba(0,0,0,0.1);">
                                            <div id="school-map-canvas-{{ $school->id }}" class="school-map-canvas" style="width: 100%; height: 100%; position: relative;">
                                                <div class="text-center pt-5 text-muted">
                                                    <i class="fas fa-spinner fa-spin fa-3x mb-3"></i><br>Loading Map Data...
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3 p-3 bg-white border rounded shadow-sm">
                                            <h6 class="fw-bold fs-sm mb-2 text-dark border-bottom pb-2">Map Legend:</h6>
                                            <div class="d-flex flex-wrap gap-4 text-secondary small mb-3">
                                                <div class="d-flex align-items-center"><span style="width: 30px; height: 30px; background: white; border: 2px solid black; margin-right: 8px; display:inline-block;"></span> <strong>Building Structure</strong></div>
                                                <div class="d-flex align-items-center"><span style="width: 24px; height: 1px; background: black; margin-right: 8px; display:inline-block;"></span> <strong>Floor Divider</strong></div>
                                                <div class="d-flex align-items-center"><span style="width: 15px; height: 15px; background: #f0f0f0; border: 1px solid #333; margin-right: 8px; display:inline-block;"></span> <strong>Room</strong></div>
                                                <div class="d-flex align-items-center"><span style="font-size: 16px; margin-right: 8px;">🧯</span> <strong>Fire Extinguisher</strong></div>
                                                <div class="d-flex align-items-center"><span style="font-size: 16px; margin-right: 8px;">🔔</span> <strong>Alarm System</strong></div>
                                                <div class="d-flex align-items-center"><span style="font-size: 14px; margin-right: 8px;">⚪</span> <strong>Smoke Detector</strong></div>
                                                <div class="d-flex align-items-center"><span style="background: green; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px; margin-right: 8px;">Plan OK</span> <strong>Evacuation Plan</strong></div>
                                                <div class="d-flex align-items-center"><span style="width: 20px; height: 10px; background: linear-gradient(90deg, #3b82f6 0%, #10b981 50%, #f59e0b 100%); border: 1px solid rgba(0,0,0,0.2); margin-right: 8px; display:inline-block;"></span> <strong>Campus Facility (Custom Color)</strong></div>
                                            </div>
                                            <h6 class="fw-bold fs-sm mb-2 text-dark border-bottom pb-2">Building Types:</h6>
                                            <div class="d-flex flex-wrap gap-4 text-secondary small">
                                                <div class="d-flex align-items-center"><span style="width: 20px; height: 10px; background: #007bff; border: 1px solid rgba(0,0,0,0.2); margin-right: 8px; display:inline-block;"></span> <strong>Classroom/School</strong></div>
                                                <div class="d-flex align-items-center"><span style="width: 20px; height: 10px; background: #ffc107; border: 1px solid rgba(0,0,0,0.2); margin-right: 8px; display:inline-block;"></span> <strong>Laboratory</strong></div>
                                                <div class="d-flex align-items-center"><span style="width: 20px; height: 10px; background: #8D6E63; border: 1px solid rgba(0,0,0,0.2); margin-right: 8px; display:inline-block;"></span> <strong>Administration</strong></div>
                                                <div class="d-flex align-items-center"><span style="width: 20px; height: 10px; background: #28a745; border: 1px solid rgba(0,0,0,0.2); margin-right: 8px; display:inline-block;"></span> <strong>Canteen</strong></div>
                                                <div class="d-flex align-items-center"><span style="width: 20px; height: 10px; background: #6f42c1; border: 1px solid rgba(0,0,0,0.2); margin-right: 8px; display:inline-block;"></span> <strong>Gymnasium/Court</strong></div>
                                                <div class="d-flex align-items-center"><span style="width: 20px; height: 10px; background: #6c757d; border: 1px solid rgba(0,0,0,0.2); margin-right: 8px; display:inline-block;"></span> <strong>Other</strong></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ATTACHED VIEW -->
                                    <div id="attached-map-view-{{ $school->id }}" style="display: none;">

                                        @if($school->attached_evacuation_map)
                                            <div class="border rounded p-3 text-center bg-light">
                                                <img src="{{ asset('storage/' . $school->attached_evacuation_map) }}" class="img-fluid" alt="Attached Evacuation Map" style="max-height: 800px; width: auto;">
                                                <div class="mt-3">
                                                    @if(auth()->user()->role !== 'viewer')
                                                        <form action="{{ route('fire-safety.school.upload-map', $school->id) }}" method="POST" enctype="multipart/form-data" class="d-inline-block">
                                                            @csrf
                                                            <input type="file" name="evacuation_map" id="mapUploadInput-{{ $school->id }}" accept=".jpg,.jpeg,.png" style="display: none;" onchange="this.form.submit()">
                                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('mapUploadInput-{{ $school->id }}').click()">
                                                                <i class="fas fa-upload me-1"></i> Update Attached Map
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-center py-5 border rounded bg-light border-dashed" style="border-width: 2px !important; border-color: #dee2e6 !important;">
                                                <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                                                <h5>No attached map yet</h5>
                                                @if(auth()->user()->role !== 'viewer')
                                                    <p class="text-muted small mb-3">Upload your custom visual evacuation map here (JPEG/PNG).</p>
                                                    <form action="{{ route('fire-safety.school.upload-map', $school->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="file" name="evacuation_map" id="mapUploadInput-{{ $school->id }}" accept=".jpg,.jpeg,.png" style="display: none;" onchange="this.form.submit()">
                                                        <button type="button" class="btn btn-primary btn-sm mt-1" onclick="document.getElementById('mapUploadInput-{{ $school->id }}').click()">
                                                            <i class="fas fa-upload me-1"></i> Attached Now
                                                        </button>
                                                    </form>
                                                @else
                                                    <p class="text-muted small mb-0">No custom visual evacuation map has been uploaded.</p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                </div> <!-- End Tab Content -->
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
                        <input type="hidden" name="unified_school_id" class="school-id-field">
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
                        <input type="hidden" name="unified_school_id" class="school-id-field">
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
                            <label class="form-label fw-bold">Name *</label>
                            <input type="text" class="form-control" name="name" placeholder="e.g., Covered Court, Parking" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea class="form-control" name="description" rows="2" placeholder="Small description..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Condition *</label>
                            <select class="form-select" name="condition" required>
                                <option value="excellent">Excellent</option>
                                <option value="good" selected>Good</option>
                                <option value="fair">Fair</option>
                                <option value="poor">Poor</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Remarks</label>
                            <textarea class="form-control" name="remarks" rows="2" placeholder="Optional remarks"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Facility Color</label>
                            <input type="color" class="form-control form-control-color" name="color" id="addFacilityColor" value="#198754" title="Choose facility color">
                            <small class="text-muted">This color will be used for this facility on the map.</small>
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
                        <input type="hidden" id="editFacilityDbId">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name *</label>
                            <input type="text" class="form-control" name="name" id="editFacilityName" required>
                        </div>
                        <div class="mb-3" id="editFacilityDescWrap">
                            <label class="form-label fw-bold">Description</label>
                            <textarea class="form-control" name="description" id="editFacilityDesc" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Condition *</label>
                            <select class="form-select" id="editFacilityCondition" name="condition" required>
                                <option value="excellent">Excellent</option>
                                <option value="good">Good</option>
                                <option value="fair">Fair</option>
                                <option value="poor">Poor</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Remarks</label>
                            <textarea class="form-control" name="remarks" id="editFacilityRemarks" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Facility Color</label>
                            <input type="color" class="form-control form-control-color" name="color" id="editFacilityColor" value="#198754" title="Choose facility color">
                            <small class="text-muted">This color will be used for this facility on the map.</small>
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

    <!-- Notify Administrator Modal -->
    <div class="modal fade" id="notifyAdminModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="fas fa-bullhorn me-2"></i> Notify Administrator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="notifyAdminForm">
                    @csrf
                    <input type="hidden" name="school_id" id="notifyAdminSchoolId">
                    <div class="modal-body">
                        <div class="alert alert-info small mb-3">
                            <i class="fas fa-info-circle me-1"></i> Use this to notify the administrator about recent changes you made to the evacuation map layout.
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">What did you update? *</label>
                            <textarea class="form-control" name="description" rows="4" placeholder="e.g., Repositioned Building A to reflect actual campus layout, added new facility marker for the guard house..." required maxlength="1000"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning"><i class="fas fa-paper-plane me-1"></i> Send Notification</button>
                    </div>
                </form>
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
        const isMapPrintMode = {{ request()->boolean('print_map') ? 'true' : 'false' }};

        // Notify Administrator modal
        function openNotifyAdminModal(schoolId) {
            document.getElementById('notifyAdminSchoolId').value = schoolId;
            document.querySelector('#notifyAdminForm textarea[name="description"]').value = '';
            new bootstrap.Modal(document.getElementById('notifyAdminModal')).show();
        }

        document.getElementById('notifyAdminForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const schoolId = document.getElementById('notifyAdminSchoolId').value;
            const description = this.querySelector('textarea[name="description"]').value;
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;

            fetch(`/fire-safety/school/${schoolId}/map-notify`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ description })
            })
            .then(response => response.json().then(data => ({ ok: response.ok, data })))
            .then(({ ok, data }) => {
                submitBtn.disabled = false;
                bootstrap.Modal.getInstance(document.getElementById('notifyAdminModal')).hide();
                if (ok && data.success) {
                    Swal.fire('Sent!', data.message, 'success');
                } else {
                    Swal.fire('Error', data.message || 'Failed to send notification.', 'error');
                }
            })
            .catch(() => {
                submitBtn.disabled = false;
                Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
            });
        });

        // --- Tab Persistence via localStorage ---
        document.addEventListener('DOMContentLoaded', function() {
            const schoolId = {{ $activeSchool->id ?? 0 }};
            if (!schoolId) return;

            if (isMapPrintMode) {
                const mapTabBtn = document.getElementById(`map-tab-${schoolId}`);
                if (mapTabBtn) {
                    const tab = new bootstrap.Tab(mapTabBtn);
                    tab.show();
                }
                initEvacuationMap(schoolId);
                return;
            }

            // Save tab choice when any tab is clicked
            document.querySelectorAll(`#evacuationTabs-${schoolId} .nav-link`).forEach(function(tabBtn) {
                tabBtn.addEventListener('shown.bs.tab', function() {
                    const key = this.dataset.tabKey; // 'plans' or 'map'
                    if (key) localStorage.setItem('evacuation_last_tab', key);
                });
            });

            // Restore last active tab
            const lastTab = localStorage.getItem('evacuation_last_tab');
            if (lastTab === 'map') {
                const mapTabBtn = document.getElementById(`map-tab-${schoolId}`);
                if (mapTabBtn) {
                    const tab = new bootstrap.Tab(mapTabBtn);
                    tab.show();
                    initEvacuationMap(schoolId);
                }
            }
        });

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

            // Modal Trigger Listeners
            initModalListeners();
        });

        // Load all data for a school
        async function loadSchoolData(schoolId) {
            if (!schoolId) return;
            currentSchoolId = schoolId;

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


        function initModalListeners() {
            // Add Building Plan
            const addBldgModal = document.getElementById('addBuildingPlanModal');
            if (addBldgModal) {
                addBldgModal.addEventListener('show.bs.modal', function(event) {
                    const btn = event.relatedTarget;
                    const bId = btn ? btn.getAttribute('data-building-id') : null;
                    const bCode = btn ? btn.getAttribute('data-building-code') : '';
                    const sId = (btn && btn.getAttribute('data-school-id')) || currentSchoolId;

                    const form = document.getElementById('addBuildingPlanForm');
                    form.reset();
                    form.querySelector('.school-id-field').value = sId || '';
                    form.querySelector('.building-id-field').value = bId || '';
                    document.getElementById('addBuildBldgCode').textContent = bCode || '—';

                    if (bId && sId) {
                        loadBuildingDetailsForPlan(bId, sId, form);
                    }
                    checkViewerAccess('addBuildingPlanForm');
                });
            }

            // Add School Plan
            const addSchoolModal = document.getElementById('addSchoolPlanModal');
            if (addSchoolModal) {
                addSchoolModal.addEventListener('show.bs.modal', function(event) {
                    const btn = event.relatedTarget;
                    const sId = (btn && btn.getAttribute('data-school-id')) || currentSchoolId;
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
        function successSwal(text, callback) { Swal.fire({ icon: 'success', title: 'Success', text: text, confirmButtonText: 'OK' }).then(callback); }
        function errorSwal(text) { Swal.fire({ icon: 'error', title: 'Error', text: text }); }


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
        let mapBuildingLookup = {};

        function floorOrdinalLabel(floorNo) {
            const n = Number(floorNo);
            if (n % 100 >= 11 && n % 100 <= 13) return `${n}th`;
            if (n % 10 === 1) return `${n}st`;
            if (n % 10 === 2) return `${n}nd`;
            if (n % 10 === 3) return `${n}rd`;
            return `${n}th`;
        }

        function normalizeMapPlacement(value) {
            const normalized = String(value || '').trim().toLowerCase();
            if (normalized.includes('left')) return 'left';
            if (normalized.includes('right')) return 'right';
            return 'center';
        }

        function appendPlacementBell(container, alarm, placement, offsetIndex = 0) {
            if (!container) return;

            const bell = document.createElement('span');
            bell.style.position = 'absolute';
            bell.style.top = `${2 + (offsetIndex * 10)}px`;
            bell.style.fontSize = '12px';
            bell.style.lineHeight = '1';
            bell.style.color = '#111';
            bell.style.background = 'rgba(255,255,255,0.92)';
            bell.style.border = '1px solid rgba(0,0,0,0.18)';
            bell.style.borderRadius = '999px';
            bell.style.padding = '0 3px';
            bell.style.pointerEvents = 'none';
            bell.title = `Alarm: ${alarm.code || ''}${alarm.location ? ` (${alarm.location})` : ''}`;
            bell.textContent = '🔔';

            if (placement === 'left') {
                bell.style.left = '10%';
                bell.style.transform = 'translateX(-50%)';
            } else if (placement === 'right') {
                bell.style.left = '90%';
                bell.style.transform = 'translateX(-50%)';
            } else {
                bell.style.left = '50%';
                bell.style.transform = 'translateX(-50%)';
            }

            container.appendChild(bell);
        }

        function facilityColorByType(type) {
            const t = String(type || '').toLowerCase();
            if (t === 'assembly_area') return '#5C4033'; // royal brown
            if (t === 'commercial') return '#0d6efd';
            if (t === 'industrial') return '#fd7e14';
            if (t === 'residential') return '#20c997';
            if (t === 'educational') return '#6f42c1';
            if (t === 'public/institutional') return '#198754';
            return '#6c757d';
        }

        function normalizeFacilityColor(value, fallback = '#198754') {
            const color = String(value || '').trim();
            return /^#([0-9a-fA-F]{6})$/.test(color) ? color : fallback;
        }

        function appendRoomExitMarkers(roomDiv, hasSecondaryExit) {
            const exitSides = hasSecondaryExit ? ['left', 'right'] : ['right'];

            exitSides.forEach(side => {
                const exitWrap = document.createElement('div');
                exitWrap.style.position = 'absolute';
                exitWrap.style.bottom = '1px';
                exitWrap.style[side] = '5px';
                exitWrap.style.width = '24px';
                exitWrap.style.height = '20px';
                exitWrap.style.display = 'flex';
                exitWrap.style.flexDirection = 'column';
                exitWrap.style.alignItems = 'center';
                exitWrap.style.justifyContent = 'flex-end';
                exitWrap.style.pointerEvents = 'none';

                const exitLine = document.createElement('div');
                exitLine.style.width = '2px';
                exitLine.style.height = '7px';
                exitLine.style.background = '#198754';
                exitLine.style.marginBottom = '1px';

                const exitSign = document.createElement('div');
                exitSign.style.fontSize = '9px';
                exitSign.style.fontWeight = '700';
                exitSign.style.color = '#198754';
                exitSign.style.background = 'rgba(255,255,255,0.95)';
                exitSign.style.padding = '0 2px';
                exitSign.style.border = '1px solid #198754';
                exitSign.style.borderRadius = '2px';
                exitSign.style.lineHeight = '1';
                exitSign.textContent = 'E';

                exitWrap.appendChild(exitLine);
                exitWrap.appendChild(exitSign);
                roomDiv.appendChild(exitWrap);
            });
        }

        function appendStairwayMarker(container, side, top, buildingWidth) {
            if (!container) return;

            const stairWrap = document.createElement('div');
            stairWrap.style.position = 'absolute';
            stairWrap.style.top = `${top}px`;
            stairWrap.style.width = '34px';
            stairWrap.style.height = '34px';
            stairWrap.style.display = 'flex';
            stairWrap.style.flexDirection = 'column';
            stairWrap.style.alignItems = 'center';
            stairWrap.style.justifyContent = 'center';
            stairWrap.style.background = 'rgba(255,255,255,0.95)';
            stairWrap.style.border = '1px solid #495057';
            stairWrap.style.borderRadius = '4px';
            stairWrap.style.boxShadow = '0 1px 2px rgba(0,0,0,0.15)';
            stairWrap.style.pointerEvents = 'none';
            stairWrap.style.zIndex = '2';

            if (String(side).toLowerCase() === 'left') {
                stairWrap.style.left = '6px';
            } else {
                stairWrap.style.left = `${Math.max(0, Number(buildingWidth || 0) - 40)}px`;
            }

            const stairIcon = document.createElement('i');
            stairIcon.className = 'fas fa-stairs';
            stairIcon.style.fontSize = '12px';
            stairIcon.style.color = '#495057';

            const stairLabel = document.createElement('div');
            stairLabel.style.fontSize = '7px';
            stairLabel.style.fontWeight = '700';
            stairLabel.style.lineHeight = '1';
            stairLabel.style.marginTop = '2px';
            stairLabel.style.color = '#495057';
            stairLabel.textContent = 'STAIR';

            stairWrap.appendChild(stairIcon);
            stairWrap.appendChild(stairLabel);
            container.appendChild(stairWrap);
        }

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
                const mapDataEndpoints = [
                    `/fire-safety/school/${schoolId}/map-data`,
                    `/fire-safety/schools/${schoolId}/map-data`
                ];

                let school = null;
                let lastError = null;

                for (const endpoint of mapDataEndpoints) {
                    try {
                        const response = await fetch(endpoint, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) {
                            lastError = new Error(`Map data request failed (${response.status})`);
                            continue;
                        }

                        const contentType = String(response.headers.get('content-type') || '').toLowerCase();
                        if (!contentType.includes('application/json')) {
                            lastError = new Error('Map data endpoint returned a non-JSON response.');
                            continue;
                        }

                        school = await response.json();
                        break;
                    } catch (fetchError) {
                        lastError = fetchError;
                    }
                }

                if (!school || typeof school !== 'object') {
                    throw lastError || new Error('Unable to load map data.');
                }

                mapDataArr[schoolId] = school;

                // Sync facilities from shared facilities table + saved layout coordinates
                const savedLayout = (school.evacuation_map_layout && typeof school.evacuation_map_layout === 'object')
                    ? school.evacuation_map_layout
                    : {};
                const dbFacilities = Array.isArray(school.facilities) ? school.facilities : [];
                school.facilities = dbFacilities.map((facility) => {
                    const layoutKey = `facility_${facility.id}`;
                    const layoutItem = savedLayout[layoutKey] || {};
                    const isSecondaryAssembly = String(facility.description || '').toLowerCase().includes('secondary assembly area');
                    const defaultColor = facilityColorByType(facility.type);
                    return {
                        id: layoutKey,
                        db_id: facility.id,
                        type: facility.type,
                        name: facility.name,
                        description: facility.description || '',
                        condition: facility.condition || 'good',
                        remarks: facility.remarks || '',
                        color: normalizeFacilityColor(layoutItem.color || facility.color || defaultColor, defaultColor),
                        x: Number(layoutItem.x || (facility.type === 'assembly_area' ? (isSecondaryAssembly ? 760 : 420) : 300)),
                        y: Number(layoutItem.y || (facility.type === 'assembly_area' ? 300 : 300)),
                        width: Number(layoutItem.width || 200),
                        height: Number(layoutItem.height || 100),
                    };
                });

                // Keep legacy ad-hoc facility markers for backward compatibility.
                Object.keys(savedLayout).forEach(id => {
                    if (id && String(id).startsWith('facility_') && !school.facilities.find(f => f.id === id)) {
                        school.facilities.push({
                            id: id,
                            db_id: null,
                            type: 'public/institutional',
                            condition: 'good',
                            remarks: '',
                            ...savedLayout[id]
                        });
                    } else if (id && String(id).startsWith('specific_')) {
                        if (!school.specifics) school.specifics = [];
                        school.specifics.push({
                            id: id,
                            ...savedLayout[id]
                        });
                    }
                });

                // Dynamic gate markers based on the school gate count from the main dashboard.
                const gateCount = Math.max(0, Number(school.number_gates || 0));
                school.gates = [];
                for (let i = 1; i <= gateCount; i++) {
                    const gateId = `gate_${i}`;
                    const gateLayout = savedLayout[gateId] || {};
                    school.gates.push({
                        id: gateId,
                        subType: 'gate',
                        x: Number(gateLayout.x || (120 + (i - 1) * 90)),
                        y: Number(gateLayout.y || 1240),
                        width: Number(gateLayout.width || 72),
                        height: Number(gateLayout.height || 72),
                        rotation: Number(gateLayout.rotation || 0),
                    });
                }

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
            mapBuildingLookup[schoolId] = {};

            canvas.style.width = '2400px';
            canvas.style.height = '1400px';

            let x = 100, y = 100;
            const savedLayout = school.evacuation_map_layout || school.map_layout || {};

            buildings.forEach((building) => {
                mapBuildingLookup[schoolId][building.id] = building;

                const allRooms = Array.isArray(building.actual_rooms) ? building.actual_rooms : [];
                const maxFloorFromRooms = allRooms.reduce((mx, room) => {
                    const f = Number(room.floor_no || room.floor || 1);
                    return Number.isFinite(f) ? Math.max(mx, f) : mx;
                }, 1);

                const floors = Math.max(Number(building.floors || 1), maxFloorFromRooms, 1);
                const roomsByFloor = {};
                for (let i = 1; i <= floors; i++) roomsByFloor[i] = [];

                allRooms.forEach(room => {
                    let floorNo = Number(room.floor_no || room.floor || 1);
                    if (!Number.isFinite(floorNo) || floorNo < 1) floorNo = 1;
                    if (floorNo > floors) floorNo = floors;
                    roomsByFloor[floorNo].push(room);
                });

                const maxRoomsOnFloor = Math.max(1, ...Object.values(roomsByFloor).map(rooms => rooms.length || 1));
                const roomGap = 12;
                const floorGap = 54;
                const headerHeight = 35;
                const minRoomSize = 64;

                let buildingWidth = Math.max(300, maxRoomsOnFloor * minRoomSize + roomGap * (maxRoomsOnFloor + 1));
                const maxRoomWidth = (buildingWidth - roomGap * (maxRoomsOnFloor + 1)) / maxRoomsOnFloor;
                const floorHeight = Math.max(60, maxRoomWidth);
                let buildingHeight = headerHeight + floorGap * (floors + 1) + floors * floorHeight;

                const layoutKey = `building_${building.id}`;
                const savedItem = savedLayout[layoutKey] || null;
                if (savedItem && Number(savedItem.width) > 0) buildingWidth = Number(savedItem.width);
                if (savedItem && Number(savedItem.height) > 0) buildingHeight = Number(savedItem.height);

                const buildingContainerDiv = document.createElement('div');
                buildingContainerDiv.className = 'map-element building-element';
                buildingContainerDiv.id = `map-bldg-${building.id}`;
                buildingContainerDiv.dataset.id = layoutKey;
                buildingContainerDiv.dataset.schoolId = schoolId;
                buildingContainerDiv.dataset.buildingId = building.id;
                buildingContainerDiv.dataset.buildingNo = building.building_no;
                buildingContainerDiv.style.position = 'absolute';

                if (savedItem) {
                    buildingContainerDiv.style.left = Number(savedItem.x || 0) + 'px';
                    buildingContainerDiv.style.top = Number(savedItem.y || 0) + 'px';
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

                const savedRotation = savedItem && savedItem.rotation ? Number(savedItem.rotation) : 0;
                buildingContainerDiv.dataset.rotation = String(savedRotation);
                buildingContainerDiv.dataset.baseWidth = String(buildingWidth);
                buildingContainerDiv.dataset.baseHeight = String(buildingHeight);
                if (savedRotation) {
                    buildingContainerDiv.style.transformOrigin = 'top left';
                    buildingContainerDiv.style.transform = `rotate(${savedRotation}deg)`;
                }

                let headerColor = '#007bff'; // default blue (classroom/school building)
                let bType = (building.building_type || '').toLowerCase();
                if (bType.includes('classroom') || bType.includes('school')) {
                    headerColor = '#007bff';
                } else if (bType.includes('laboratory')) {
                    headerColor = '#ffc107'; // yellow
                } else if (bType.includes('admin')) {
                    headerColor = '#8D6E63'; // brown
                } else if (bType.includes('canteen')) {
                    headerColor = '#28a745'; // green
                } else if (bType.includes('gymnasium') || bType.includes('court')) {
                    headerColor = '#6f42c1'; // purple
                } else {
                    // Default to gray for "Other" or unknown types as per legend
                    headerColor = '#6c757d';
                }

                const headerDiv = document.createElement('div');
                headerDiv.style.padding = '5px 8px';
                headerDiv.style.backgroundColor = headerColor;
                headerDiv.style.color = (headerColor === '#ffc107') ? 'black' : 'white';
                headerDiv.style.fontSize = '12px';
                headerDiv.style.fontWeight = 'bold';
                headerDiv.style.borderBottom = '2px solid black';
                headerDiv.style.display = 'flex';
                headerDiv.style.justifyContent = 'space-between';
                headerDiv.style.alignItems = 'center';
                headerDiv.style.position = 'relative';

                const hasPlan = building.evacuation_plan || building.evacuationPlan || school.evacuation_plan || school.evacuationPlan;

                // Collect alarms
                const directAlarms = building.alarm_systems || building.alarmSystems || [];
                const coveredAlarms = building.alarm_systems_many || building.alarmSystemsMany || [];
                const alarms = [...directAlarms, ...coveredAlarms].filter((a, idx, arr) => {
                    const id = a && a.id ? a.id : null;
                    if (id === null) return true;
                    return arr.findIndex(x => x && x.id === id) === idx;
                });
                const buildingAlarms = alarms.filter(a => {
                    const v = (a && a.floor_id != null) ? String(a.floor_id).toLowerCase() : '';
                    return !v || v === 'all' || v.includes('all');
                });

                headerDiv.innerHTML = `
                    <span>${building.building_no}</span>
                    <span style="font-size: 10px;">${building.building_name || ''}</span>
                    <span style="display:flex; align-items:center; gap:6px;">
                        <span style="background: ${hasPlan ? 'green' : 'red'}; padding: 2px 6px; border-radius: 3px; font-size: 9px; color: white;">
                            ${hasPlan ? 'Plan OK' : 'No Plan'}
                        </span>
                        <button type="button" class="btn btn-sm btn-light p-0 px-1" title="Rotate building" style="line-height:1;"
                            onmousedown="event.stopPropagation();"
                            onclick="rotateMapBuilding('${buildingContainerDiv.id}', ${schoolId}); event.stopPropagation();">
                            <i class="fas fa-rotate-right"></i>
                        </button>
                    </span>
                `;

                const headerAlarmLayer = document.createElement('div');
                headerAlarmLayer.style.position = 'absolute';
                headerAlarmLayer.style.left = '0';
                headerAlarmLayer.style.top = '2px';
                headerAlarmLayer.style.width = '100%';
                headerAlarmLayer.style.height = '14px';
                headerAlarmLayer.style.pointerEvents = 'none';
                headerAlarmLayer.style.zIndex = '2';
                headerDiv.appendChild(headerAlarmLayer);

                const headerPlacementCounts = { left: 0, center: 0, right: 0 };
                buildingAlarms.forEach(a => {
                    const placement = normalizeMapPlacement(a.location);
                    appendPlacementBell(headerAlarmLayer, a, placement, headerPlacementCounts[placement]++);
                });
                buildingContainerDiv.appendChild(headerDiv);

                // --- Build interior using HTML divs for rooms ---
                const interiorDiv = document.createElement('div');
                interiorDiv.style.width = '100%';
                interiorDiv.style.height = `calc(100% - ${headerHeight}px)`;
                interiorDiv.style.position = 'relative';
                interiorDiv.style.overflow = 'hidden';
                interiorDiv.style.border = '2px solid black';
                interiorDiv.style.boxSizing = 'border-box';

                const computedFloorHeight = Math.max(72, (buildingHeight - headerHeight - floorGap * (floors + 1)) / floors);
                const hasSecondaryBuildingExit = floors >= 2 || allRooms.some(room => room.has_secondary_exit === true || String(room.has_secondary_exit || '') === '1');

                // Render floors top-down (highest floor at top)
                for (let floorIdx = 0; floorIdx < floors; floorIdx++) {
                    const floorNo = floors - floorIdx; // top = highest floor
                    const floorY = floorGap + floorIdx * (computedFloorHeight + floorGap);

                    const floorRooms = roomsByFloor[floorNo] || [];
                    const roomCount = Math.max(1, floorRooms.length);
                    const roomWidth = Math.max(30, (buildingWidth - roomGap * (roomCount + 1)) / roomCount);

                    // Room row
                    for (let roomIdx = 0; roomIdx < roomCount; roomIdx++) {
                        const room = floorRooms[roomIdx] || null;
                        const roomX = roomGap + roomIdx * (roomWidth + roomGap);

                        const roomDiv = document.createElement('div');
                        roomDiv.style.position = 'absolute';
                        roomDiv.style.left = roomX + 'px';
                        roomDiv.style.top = floorY + 'px';
                        roomDiv.style.width = Math.max(1, roomWidth - 2) + 'px';
                        roomDiv.style.height = Math.max(1, computedFloorHeight - 2) + 'px';
                        roomDiv.style.border = room ? '1px solid black' : '1px solid #ccc';
                        roomDiv.style.backgroundColor = room ? '#f8f8f8' : '#ffffff';
                        roomDiv.style.display = 'flex';
                        roomDiv.style.flexDirection = 'column';
                        roomDiv.style.alignItems = 'center';
                        roomDiv.style.justifyContent = 'center';
                        roomDiv.style.overflow = 'hidden';

                        if (room) {
                            roomDiv.style.cursor = 'pointer';
                            roomDiv.addEventListener('click', ((bId, rId, sId) => (e) => {
                                e.stopPropagation();
                                if (isMapEditable[sId]) return;
                                openRoomExtinguishers(bId, rId, sId);
                            })(building.id, room.id, schoolId));

                            // Room number (top center)
                            const roomLabel = document.createElement('div');
                            roomLabel.style.fontSize = '11px';
                            roomLabel.style.fontWeight = 'bold';
                            roomLabel.style.color = '#333';
                            roomLabel.style.textAlign = 'center';
                            roomLabel.style.lineHeight = '1.1';
                            roomLabel.style.marginBottom = '2px';
                            roomLabel.style.overflow = 'hidden';
                            roomLabel.style.textOverflow = 'ellipsis';
                            roomLabel.style.whiteSpace = 'nowrap';
                            roomLabel.style.maxWidth = '100%';
                            roomLabel.style.padding = '0 2px';
                            roomLabel.textContent = room.room_name || `R${room.id}`;
                            roomDiv.appendChild(roomLabel);

                            const hasSecondaryExit = room.has_secondary_exit === true
                                || String(room.has_secondary_exit || '') === '1';
                            appendRoomExitMarkers(roomDiv, hasSecondaryExit);

                            // Fire extinguisher icon (center)
                            const extinguishers = building.fire_extinguishers
                                ? building.fire_extinguishers.filter(e => Number(e.room_id) === Number(room.id))
                                : [];
                            if (extinguishers.length > 0) {
                                const extIcon = document.createElement('div');
                                extIcon.style.fontSize = '16px';
                                extIcon.style.lineHeight = '1';
                                extIcon.title = `${extinguishers.length} extinguisher(s)`;
                                extIcon.textContent = '🧯';
                                roomDiv.appendChild(extIcon);
                            }
                        }

                        interiorDiv.appendChild(roomDiv);
                    }

                    const stairTop = floorY + Math.max(6, Math.round((computedFloorHeight - 34) / 2));
                    appendStairwayMarker(interiorDiv, 'right', stairTop, buildingWidth);
                    if (hasSecondaryBuildingExit) {
                        appendStairwayMarker(interiorDiv, 'left', stairTop, buildingWidth);
                    }

                    // Floor divider line (below this floor's rooms, except for the last/bottom floor)
                    if (floorIdx < floors - 1) {
                        const dividerY = floorY + computedFloorHeight;

                        const divider = document.createElement('div');
                        divider.style.position = 'absolute';
                        divider.style.left = '0';
                        divider.style.top = dividerY + 'px';
                        divider.style.width = '100%';
                        divider.style.height = floorGap + 'px';
                        divider.style.display = 'flex';
                        divider.style.alignItems = 'center';
                        divider.style.justifyContent = 'center';
                        divider.style.borderTop = '2px solid black';
                        divider.style.position = 'absolute';

                        // Floor alarms on the divider
                        const floorBelowNo = floorNo; // bell belongs to the floor above the divider
                        const floorAlarms = alarms.filter(a => a.floor_id && String(a.floor_id).toLowerCase() !== 'all' && Number(a.floor_id) === floorBelowNo);
                        const floorPlacementCounts = { left: 0, center: 0, right: 0 };
                        floorAlarms.forEach(a => {
                            const placement = normalizeMapPlacement(a.location);
                            appendPlacementBell(divider, a, placement, floorPlacementCounts[placement]++);
                        });

                        interiorDiv.appendChild(divider);
                    }

                    // For the bottom floor, show its alarms at the very bottom
                    if (floorIdx === floors - 1 && floorNo >= 1) {
                        const bottomFloorAlarms = alarms.filter(a => a.floor_id && String(a.floor_id).toLowerCase() !== 'all' && Number(a.floor_id) === floorNo);
                        if (bottomFloorAlarms.length > 0) {
                            const bottomDivider = document.createElement('div');
                            bottomDivider.style.position = 'absolute';
                            bottomDivider.style.left = '0';
                            bottomDivider.style.top = (floorY + computedFloorHeight) + 'px';
                            bottomDivider.style.width = '100%';
                            bottomDivider.style.display = 'flex';
                            bottomDivider.style.alignItems = 'center';
                            bottomDivider.style.justifyContent = 'center';
                            bottomDivider.style.position = 'absolute';
                            bottomDivider.style.height = '18px';
                            bottomDivider.style.pointerEvents = 'none';
                            const bottomPlacementCounts = { left: 0, center: 0, right: 0 };
                            bottomFloorAlarms.forEach(a => {
                                const placement = normalizeMapPlacement(a.location);
                                appendPlacementBell(bottomDivider, a, placement, bottomPlacementCounts[placement]++);
                            });
                            interiorDiv.appendChild(bottomDivider);
                        }
                    }
                }

                buildingContainerDiv.appendChild(interiorDiv);

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

                buildingContainerDiv.addEventListener('click', function(e) {
                    if (!isMapEditable[schoolId] && e.target !== infoBtn) {
                        e.preventDefault();
                        showBuildingOptions(building.id, building.building_no, schoolId);
                    }
                });

                makeDraggable(buildingContainerDiv, schoolId);
                makeResizable(buildingContainerDiv, schoolId);
            });

            facilities.forEach(facility => {
                renderFacility(facility, schoolId, savedLayout);
            });

            // Render specifics
            const specifics = school.specifics || [];
            specifics.forEach(spec => {
                renderSpecific(spec, schoolId);
            });

            // Render dynamic gate markers.
            const gates = school.gates || [];
            gates.forEach(gate => {
                renderSpecific(gate, schoolId, true);
            });

            applyMapFit(schoolId);
        }

        async function deleteGateMarker(specDiv, schoolId) {
            const confirmDelete = await Swal.fire({
                title: 'Delete gate marker?',
                text: 'Are you sure you want to delete this gate? This will also reduce the school gate count by one.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete gate',
            });

            if (!confirmDelete.isConfirmed) return;

            try {
                const response = await fetch(`/fire-safety/school/${schoolId}/decrement-gates`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ count: 1 })
                });

                const data = await response.json().catch(() => ({}));
                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Failed to delete gate marker.');
                }

                specDiv.remove();
                if (mapDataArr[schoolId]) {
                    mapDataArr[schoolId].number_gates = Number(data.number_gates ?? 0);
                }

                Swal.fire('Gate deleted', 'The gate marker was removed and the gate count was reduced.', 'success');
            } catch (error) {
                console.error(error);
                Swal.fire('Error', error.message || 'Failed to delete gate marker.', 'error');
            }
        }

        function renderSpecific(spec, schoolId, isGateMarker = false) {
            const canvas = document.getElementById(`school-map-canvas-${schoolId}`);
            if (!canvas) return;

            const isGate = isGateMarker || String(spec.subType || '').toLowerCase() === 'gate';
            const baseWidth = spec.width || (isGate ? 72 : 40);
            const baseHeight = spec.height || (isGate ? 72 : 40);

            const specDiv = document.createElement('div');
            specDiv.className = 'map-element specific-element';
            specDiv.id = spec.id;
            specDiv.dataset.id = spec.id;
            specDiv.dataset.type = 'specific';
            specDiv.dataset.subType = spec.subType;
            specDiv.dataset.schoolId = schoolId;
            specDiv.dataset.isGate = isGate ? '1' : '0';
            specDiv.dataset.rotation = spec.rotation || 0;
            specDiv.dataset.baseWidth = baseWidth;
            specDiv.dataset.baseHeight = baseHeight;

            specDiv.style.position = 'absolute';
            specDiv.style.width = `${specDiv.dataset.baseWidth}px`;
            specDiv.style.height = `${specDiv.dataset.baseHeight}px`;
            specDiv.style.left = (spec.x || 300) + 'px';
            specDiv.style.top = (spec.y || 300) + 'px';
            specDiv.style.display = 'flex';
            specDiv.style.alignItems = 'center';
            specDiv.style.justifyContent = 'center';
            specDiv.style.background = 'white';
            specDiv.style.border = '1px solid #333';
            specDiv.style.borderRadius = '4px';
            specDiv.style.zIndex = '100';
            specDiv.style.fontSize = isGate ? '30px' : '20px';

            if (spec.rotation) {
                specDiv.style.transform = `rotate(${spec.rotation}deg)`;
            }

            const iconMap = {
                door: 'fa-door-open',
                stairs: 'fa-stairs',
                table: 'fa-table-list',
                bell: 'fa-bell',
                window: 'fa-window-maximize',
                toilet: 'fa-toilet',
                medical: 'fa-kit-medical',
                arrow: 'fa-arrow-right',
                gate: 'fa-door-open'
            };

            const iconClass = iconMap[spec.subType] || 'fa-question';
            const iconColor = spec.subType === 'medical' ? 'color:red;' : (isGate ? 'color:#198754;' : '');
            if (spec.subType === 'stairs') {
                specDiv.style.fontSize = '12px';
                specDiv.innerHTML = `<div style="display:flex; flex-direction:column; align-items:center; justify-content:center; line-height:1; width:100%; height:100%;"><i class="fas ${iconClass}" style="${iconColor}"></i><span style="font-size:8px; font-weight:700; margin-top:2px;">STAIR</span></div>`;
            } else {
                specDiv.innerHTML = `<i class="fas ${iconClass}" style="${iconColor}"></i>`;
            }

            // Delete button on specifics
            const delBtn = document.createElement('div');
            delBtn.className = 'delete-specific no-print';
            delBtn.innerHTML = '×';
            delBtn.style.cssText = 'position:absolute; top:-10px; right:-10px; width:18px; height:18px; background:red; color:white; border-radius:50%; font-size:12px; line-height:16px; text-align:center; cursor:pointer; display:' + (isMapEditable[schoolId] ? 'block' : 'none') + ';';
            delBtn.onclick = async (e) => {
                e.stopPropagation();
                if (!isMapEditable[schoolId]) return;
                if (specDiv.dataset.isGate === '1') {
                    await deleteGateMarker(specDiv, schoolId);
                    return;
                }
                specDiv.remove();
            };
            specDiv.appendChild(delBtn);

            canvas.appendChild(specDiv);
            makeDraggable(specDiv, schoolId);
            makeResizable(specDiv, schoolId);

            // Double click to rotate specifics
            specDiv.ondblclick = (e) => {
                if (!isMapEditable[schoolId]) return;
                const rot = (parseInt(specDiv.dataset.rotation || 0) + 90) % 360;
                specDiv.dataset.rotation = rot;
                specDiv.style.transform = `rotate(${rot}deg)`;
            };
        }

        function addSpecificToMap(subType, schoolId) {
            if (!isMapEditable[schoolId]) {
                Swal.fire('Edit Mode Required', 'Please click "Edit Place" first before adding map specifics.', 'info');
                return;
            }
            const id = 'specific_' + Date.now();
            const spec = {
                id: id,
                subType: subType,
                x: 150,
                y: 150,
                width: 40,
                height: 40,
                rotation: 0
            };
            renderSpecific(spec, schoolId);
        }

        function renderFacility(facility, schoolId, savedLayout) {
            const canvas = document.getElementById(`school-map-canvas-${schoolId}`);
            if (!canvas) {
                console.error('Canvas not found for school:', schoolId);
                return;
            }

            const layoutItem = (savedLayout && facility.id) ? savedLayout[facility.id] : null;
            const facilityWidth = Number((layoutItem && layoutItem.width) || facility.width || 200);
            const facilityHeight = Number((layoutItem && layoutItem.height) || facility.height || 100);
            const resolvedColor = normalizeFacilityColor(
                (layoutItem && layoutItem.color) || facility.color,
                facilityColorByType(facility.type)
            );

            const facilityDiv = document.createElement('div');
            facilityDiv.className = 'map-element facility-element';
            facilityDiv.id = facility.id;
            facilityDiv.dataset.id = facility.id;
            facilityDiv.dataset.dbId = facility.db_id || '';
            facilityDiv.dataset.type = 'facility';
            facilityDiv.dataset.schoolId = schoolId;
            facilityDiv.dataset.name = facility.name;
            facilityDiv.dataset.facilityType = facility.type || 'public/institutional';
            facilityDiv.dataset.condition = facility.condition || 'good';
            facilityDiv.dataset.remarks = facility.remarks || '';
            facilityDiv.dataset.description = facility.description || '';
            facilityDiv.dataset.color = resolvedColor;
            facilityDiv.dataset.baseWidth = String(facilityWidth);
            facilityDiv.dataset.baseHeight = String(facilityHeight);

            facilityDiv.style.position = 'absolute';
            facilityDiv.style.width = `${facilityWidth}px`;
            facilityDiv.style.height = `${facilityHeight}px`;
            facilityDiv.style.backgroundColor = resolvedColor;
            facilityDiv.style.left = (facility.x || 300) + 'px';
            facilityDiv.style.top = (facility.y || 300) + 'px';
            facilityDiv.textContent = facility.name;

            facilityDiv.onclick = (e) => {
                if (isMapEditable[schoolId]) return;
                openEditFacilityModal({
                    id: facilityDiv.dataset.id,
                    db_id: facilityDiv.dataset.dbId || null,
                    type: facilityDiv.dataset.facilityType || 'public/institutional',
                    name: facilityDiv.dataset.name || '',
                    description: facilityDiv.dataset.description || '',
                    condition: facilityDiv.dataset.condition || 'good',
                    remarks: facilityDiv.dataset.remarks || '',
                    color: facilityDiv.dataset.color || '',
                }, schoolId);
            };

            canvas.appendChild(facilityDiv);
            makeDraggable(facilityDiv, schoolId);
            makeResizable(facilityDiv, schoolId);
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
                const isSpecific = el.classList.contains('specific-element');

                let bw, bh;
                if (isFacility) {
                    bw = parseFloat(el.dataset.baseWidth || el.style.width || 200);
                    bh = parseFloat(el.dataset.baseHeight || el.style.height || 100);
                } else if (isSpecific) {
                    bw = parseFloat(el.dataset.baseWidth || 40);
                    bh = parseFloat(el.dataset.baseHeight || 40);
                } else {
                    bw = parseFloat(el.dataset.baseWidth || 300);
                    bh = parseFloat(el.dataset.baseHeight || 150);
                }

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

        function getRotatedBoundsOffsets(width, height, rotation) {
            const w = Number(width) || 0;
            const h = Number(height) || 0;
            const normalized = ((Number(rotation) % 360) + 360) % 360;

            if (normalized === 90) {
                return { minX: -h, maxX: 0, minY: 0, maxY: w };
            }
            if (normalized === 180) {
                return { minX: -w, maxX: 0, minY: -h, maxY: 0 };
            }
            if (normalized === 270) {
                return { minX: 0, maxX: h, minY: -w, maxY: 0 };
            }

            return { minX: 0, maxX: w, minY: 0, maxY: h };
        }

        function clampMapElementToCanvas(element, schoolId) {
            const canvas = document.getElementById(`school-map-canvas-${schoolId}`);
            if (!canvas) return;

            const isFacility = element.classList.contains('facility-element');
            const isSpecific = element.classList.contains('specific-element');
            const bw = isSpecific
                ? parseFloat(element.dataset.baseWidth || element.style.width || 40)
                : (isFacility ? parseFloat(element.dataset.baseWidth || element.style.width || 200) : parseFloat(element.dataset.baseWidth || 300));
            const bh = isSpecific
                ? parseFloat(element.dataset.baseHeight || element.style.height || 40)
                : (isFacility ? parseFloat(element.dataset.baseHeight || element.style.height || 100) : parseFloat(element.dataset.baseHeight || 150));
            const rotation = parseInt(element.dataset.rotation || 0);
            const offsets = getRotatedBoundsOffsets(bw, bh, rotation);

            const virtualW = parseFloat(canvas.style.width) || 2400;
            const virtualH = parseFloat(canvas.style.height) || 1400;

            const minLeft = -offsets.minX;
            const maxLeft = virtualW - offsets.maxX;
            const minTop = -offsets.minY;
            const maxTop = virtualH - offsets.maxY;

            const currentLeft = parseFloat(element.style.left || element.offsetLeft || 0);
            const currentTop = parseFloat(element.style.top || element.offsetTop || 0);

            const left = Math.min(Math.max(minLeft, currentLeft), Math.max(minLeft, maxLeft));
            const top = Math.min(Math.max(minTop, currentTop), Math.max(minTop, maxTop));

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
            window.location.href = `/fire-safety/extinguishers?tab=tab-extinguishers&building_id=${buildingId}&room=${roomId}`;
        }

        function toggleMapEdit(schoolId) {
            const canvas = document.getElementById(`school-map-canvas-${schoolId}`);
            const editBtn = document.getElementById(`edit-placement-btn-${schoolId}`);
            const saveBtn = document.getElementById(`save-placement-btn-${schoolId}`);
            const specificsPanel = document.getElementById(`specifics-panel-${schoolId}`);

            if (!isMapEditable[schoolId]) { // Entering edit mode
                isMapEditable[schoolId] = true;
                editBtn.innerHTML = '<i class="fas fa-save me-1"></i> Save Layout';
                editBtn.classList.remove('btn-outline-primary');
                editBtn.classList.add('btn-primary');
                if (saveBtn) saveBtn.disabled = false;

                // Show Specifics button and panel
                const specificsBtn = document.getElementById(`btn-specifics-${schoolId}`);
                if (specificsBtn) specificsBtn.style.display = 'block';
                if (specificsPanel) specificsPanel.style.display = 'block';

                // Enable dragging/resizing
                const els = canvas.querySelectorAll('.map-element');
                els.forEach(el => {
                    el.classList.add('map-edit-mode');
                    el.style.cursor = 'move';
                    el.style.borderColor = '#ffc107';
                    const delBtn = el.querySelector('.delete-specific');
                    if (delBtn) delBtn.style.display = 'block';
                });
                // remove fit while editing
                applyMapFit(schoolId);
            } else {
                saveMapLayout(schoolId); // Save layout when exiting edit mode
                isMapEditable[schoolId] = false;
                editBtn.innerHTML = '<i class="fas fa-arrows-alt me-2"></i> Edit Placement';
                editBtn.classList.remove('btn-primary');
                editBtn.classList.add('btn-outline-primary');
                if (saveBtn) saveBtn.disabled = true;

                // Hide Specifics button and panel
                const specificsBtn = document.getElementById(`btn-specifics-${schoolId}`);
                if (specificsBtn) specificsBtn.style.display = 'none';
                if (specificsPanel) specificsPanel.style.display = 'none';

                document.querySelectorAll(`#school-map-canvas-${schoolId} .map-element`).forEach(el => {
                    el.classList.remove('map-edit-mode');
                    el.style.cursor = 'default';
                    el.style.borderColor = 'black';
                    const delBtn = el.querySelector('.delete-specific');
                    if (delBtn) delBtn.style.display = 'none';
                });
                // re-fit when locked
                applyMapFit(schoolId);
            }
        }

        function makeDraggable(element, schoolId) {
            let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;

            element.addEventListener('mousedown', dragMouseDown);

            function dragMouseDown(e) {
                if (!isMapEditable[schoolId]) return;
                if (element.dataset.isResizing === '1') return;
                if (e.button !== 0) return;
                if (e.target.classList.contains('delete-specific')) return;

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

        function makeResizable(element, schoolId) {
            const isBuilding = element.classList.contains('building-element');
            const isFacility = element.classList.contains('facility-element');
            const isSpecific = element.classList.contains('specific-element');
            if (!isBuilding && !isFacility && !isSpecific) return;

            const edge = 10;
            const minW = isSpecific ? 20 : (isFacility ? 120 : 220);
            const minH = isSpecific ? 20 : (isFacility ? 60 : 140);
            let resizeDir = null;

            function getResizeDirection(e) {
                const rect = element.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const nearLeft = x <= edge;
                const nearRight = x >= rect.width - edge;
                const nearTop = y <= edge;
                const nearBottom = y >= rect.height - edge;

                if (nearLeft && nearTop) return 'nw';
                if (nearRight && nearTop) return 'ne';
                if (nearLeft && nearBottom) return 'sw';
                if (nearRight && nearBottom) return 'se';
                if (nearLeft || nearRight) return nearLeft ? 'w' : 'e';
                if (nearTop || nearBottom) return nearTop ? 'n' : 's';
                return null;
            }

            function getCursor(dir) {
                if (dir === 'e' || dir === 'w') return 'ew-resize';
                if (dir === 'n' || dir === 's') return 'ns-resize';
                if (dir === 'nw' || dir === 'se') return 'nwse-resize';
                if (dir === 'ne' || dir === 'sw') return 'nesw-resize';
                return 'move';
            }

            element.addEventListener('mousemove', (e) => {
                if (!isMapEditable[schoolId] || element.dataset.isResizing === '1') return;
                const dir = getResizeDirection(e);
                element.style.cursor = dir ? getCursor(dir) : 'move';
            });

            element.addEventListener('mouseleave', () => {
                if (!isMapEditable[schoolId] || element.dataset.isResizing === '1') return;
                element.style.cursor = 'move';
            });

            element.addEventListener('mousedown', (e) => {
                if (!isMapEditable[schoolId] || e.button !== 0) return;

                resizeDir = getResizeDirection(e);
                if (!resizeDir) return;

                e.preventDefault();
                e.stopImmediatePropagation();
                element.dataset.isResizing = '1';

                const canvas = document.getElementById(`school-map-canvas-${schoolId}`);
                const transform = canvas.style.transform;
                const scaleMatch = transform.match(/scale\(([^)]+)\)/);
                const scale = scaleMatch ? parseFloat(scaleMatch[1]) : 1;

                const startX = e.clientX;
                const startY = e.clientY;
                const startW = parseFloat(element.style.width);
                const startH = parseFloat(element.style.height);
                const startL = parseFloat(element.style.left);
                const startT = parseFloat(element.style.top);

                function onMove(ev) {
                    ev.preventDefault();

                    const dx = (ev.clientX - startX) / scale;
                    const dy = (ev.clientY - startY) / scale;

                    let newW = startW;
                    let newH = startH;
                    let newL = startL;
                    let newT = startT;

                    const rightResize = resizeDir === 'e' || resizeDir === 'se' || resizeDir === 'ne';
                    const leftResize = resizeDir === 'w' || resizeDir === 'sw' || resizeDir === 'nw';
                    const bottomResize = resizeDir === 's' || resizeDir === 'se' || resizeDir === 'sw';
                    const topResize = resizeDir === 'n' || resizeDir === 'ne' || resizeDir === 'nw';

                    if (rightResize) newW = startW + dx;
                    if (leftResize) {
                        newW = startW - dx;
                        newL = startL + dx;
                    }
                    if (bottomResize) newH = startH + dy;
                    if (topResize) {
                        newH = startH - dy;
                        newT = startT + dy;
                    }

                    newW = Math.max(minW, newW);
                    newH = Math.max(minH, newH);

                    element.style.width = `${newW}px`;
                    element.style.height = `${newH}px`;
                    element.style.left = `${newL}px`;
                    element.style.top = `${newT}px`;
                    element.dataset.baseWidth = String(newW);
                    element.dataset.baseHeight = String(newH);
                    clampMapElementToCanvas(element, schoolId);
                }

                function onUp() {
                    document.removeEventListener('mousemove', onMove);
                    document.removeEventListener('mouseup', onUp);
                    element.dataset.isResizing = '0';
                    element.style.cursor = 'move';
                }

                document.addEventListener('mousemove', onMove);
                document.addEventListener('mouseup', onUp);
            });
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
                        y: parseFloat(el.style.top),
                        width: Number(el.dataset.baseWidth || parseFloat(el.style.width) || 200),
                        height: Number(el.dataset.baseHeight || parseFloat(el.style.height) || 100)
                    };
                } else if (el.classList.contains('specific-element')) {
                    layout[id] = {
                        type: 'specific',
                        subType: el.dataset.subType,
                        x: parseFloat(el.style.left),
                        y: parseFloat(el.style.top),
                        rotation: Number(el.dataset.rotation || 0),
                        width: Number(el.dataset.baseWidth || parseFloat(el.style.width) || 40),
                        height: Number(el.dataset.baseHeight || parseFloat(el.style.height) || 40)
                    };
                } else {
                    layout[id] = {
                        x: parseFloat(el.style.left),
                        y: parseFloat(el.style.top),
                        rotation: Number(el.dataset.rotation || 0),
                        width: Number(el.dataset.baseWidth || parseFloat(el.style.width) || 300),
                        height: Number(el.dataset.baseHeight || parseFloat(el.style.height) || 150)
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
                const form = document.getElementById('addFacilityForm');
                if (!form) {
                    Swal.fire('Error', 'Add Facility form was not found.', 'error');
                    return;
                }

                const nameInput = form.querySelector('[name="name"]');
                const descInput = form.querySelector('[name="description"]');
                const condInput = form.querySelector('[name="condition"]');
                const remarksInput = form.querySelector('[name="remarks"]');
                const colorInput = form.querySelector('[name="color"]');
                const schoolIdInput = document.getElementById('addFacilitySchoolId');

                const type = 'public/institutional';
                const name = nameInput ? nameInput.value.trim() : "";
                const description = descInput ? descInput.value.trim() : "";
                const condition = condInput ? condInput.value : 'good';
                const remarks = remarksInput ? remarksInput.value.trim() : '';
                const color = normalizeFacilityColor(colorInput ? colorInput.value : '', facilityColorByType(type));
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

                fetch('/fire-safety/facilities', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        school_id: Number(sId),
                        type,
                        name,
                        description,
                        condition,
                        remarks,
                        color,
                    })
                })
                .then(async (response) => {
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'Failed to create facility.');
                    }

                    const created = data.facility;
                    const facility = {
                        id: `facility_${created.id}`,
                        db_id: created.id,
                        type: created.type,
                        name: created.name,
                        description: created.description || '',
                        condition: created.condition || 'good',
                        remarks: created.remarks || '',
                        color,
                        x: 800,
                        y: 600,
                    };

                    if (typeof mapDataArr === 'undefined') window.mapDataArr = {};
                    if (!mapDataArr[sId]) mapDataArr[sId] = { buildings: [], facilities: [] };
                    if (!mapDataArr[sId].facilities) mapDataArr[sId].facilities = [];
                    mapDataArr[sId].facilities.push(facility);

                    renderFacility(facility, sId, {});

                    const modalEl = document.getElementById('addFacilityModal');
                    const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    if (modal && typeof modal.hide === 'function') modal.hide();

                    const saveBtn = document.getElementById(`save-placement-btn-${sId}`);
                    if (saveBtn) {
                        saveBtn.disabled = false;
                        saveBtn.classList.remove('btn-secondary');
                        saveBtn.classList.add('btn-primary');
                    }

                    if (!isMapEditable[sId]) toggleMapEdit(sId);

                    form.reset();
                    Swal.fire({
                        icon: 'success',
                        title: 'Facility Saved to Map',
                        text: 'Now drag the rectangle to its correct location and click "Save Layout".',
                        confirmButtonText: 'OK'
                    });
                })
                .catch((error) => {
                    console.error('Facility create error:', error);
                    Swal.fire('Error', error.message || 'Failed to create facility.', 'error');
                });
            } catch (err) {
                console.error('Internal Error in createNewFacility:', err);
                Swal.fire('Error', err.message || 'Unexpected error while creating facility.', 'error');
            }
        }

        function openEditFacilityModal(facility, schoolId) {
            currentSchoolId = schoolId;
            document.getElementById('editFacilityId').value = facility.id;
            document.getElementById('editFacilityDbId').value = facility.db_id || '';
            document.getElementById('editFacilityName').value = facility.name;
            document.getElementById('editFacilityDesc').value = facility.description || '';
            document.getElementById('editFacilityCondition').value = facility.condition || 'good';
            document.getElementById('editFacilityRemarks').value = facility.remarks || '';
            document.getElementById('editFacilityColor').value = normalizeFacilityColor(facility.color || '', facilityColorByType(facility.type));

            const isAssemblyArea = String(facility.type || '').toLowerCase() === 'assembly_area';
            document.getElementById('editFacilityDescWrap').style.display = isAssemblyArea ? 'none' : '';

            const modal = new bootstrap.Modal(document.getElementById('editFacilityModal'));
            modal.show();
        }

        function updateFacilityAction() {
            const id = document.getElementById('editFacilityId').value;
            const el = document.getElementById(id);
            if (!el) return;

            const name = document.getElementById('editFacilityName').value;
            const desc = document.getElementById('editFacilityDesc').value;
            const dbId = document.getElementById('editFacilityDbId').value;
            const type = el.dataset.facilityType || 'public/institutional';
            const condition = document.getElementById('editFacilityCondition').value || 'good';
            const remarks = document.getElementById('editFacilityRemarks').value || '';
            const color = normalizeFacilityColor(document.getElementById('editFacilityColor').value || '', facilityColorByType(type));

            if (!name.trim()) {
                Swal.fire('Required', 'Facility name is required.', 'warning');
                return;
            }

            if (!dbId) {
                Swal.fire('Not Supported', 'This is a legacy map marker and cannot be edited from shared facilities.', 'info');
                return;
            }

            fetch(`/fire-safety/facilities/${dbId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name,
                    description: desc,
                    condition,
                    remarks,
                    color,
                })
            })
            .then(async (response) => {
                const data = await response.json().catch(() => ({}));
                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Failed to update facility.');
                }

                el.dataset.name = name;
                el.dataset.description = desc;
                el.dataset.facilityType = type;
                el.dataset.condition = condition;
                el.dataset.remarks = remarks;
                el.dataset.color = color;
                el.style.backgroundColor = color;
                el.textContent = name;

                bootstrap.Modal.getInstance(document.getElementById('editFacilityModal')).hide();
                const saveBtn = document.getElementById(`save-placement-btn-${currentSchoolId}`);
                if (saveBtn) saveBtn.disabled = false;
            })
            .catch((error) => {
                console.error('Facility update error:', error);
                Swal.fire('Error', error.message || 'Failed to update facility.', 'error');
            });
        }
        function toggleMapView(mode, id) {
            const generatedView = document.getElementById('generated-map-view-' + id);
            const attachedView = document.getElementById('attached-map-view-' + id);
            const generatedActions = document.getElementById('generated-actions-' + id);

            if (mode === 'generated') {
                generatedView.style.display = 'block';
                attachedView.style.display = 'none';
                if (generatedActions) {
                    generatedActions.style.display = 'flex';
                    generatedActions.classList.add('d-flex');
                }
            } else {
                generatedView.style.display = 'none';
                attachedView.style.display = 'block';
                if (generatedActions) {
                    generatedActions.style.display = 'none';
                    generatedActions.classList.remove('d-flex');
                }
            }
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
            const dbId = document.getElementById('editFacilityDbId').value;
            const el = document.getElementById(id);

            if (!dbId) {
                if (el) el.remove();
                bootstrap.Modal.getInstance(document.getElementById('editFacilityModal')).hide();
                const saveBtn = document.getElementById(`save-placement-btn-${currentSchoolId}`);
                if (saveBtn) saveBtn.disabled = false;
                return;
            }

            fetch(`/fire-safety/facilities/${dbId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(async (response) => {
                const data = await response.json().catch(() => ({}));
                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Failed to delete facility.');
                }

                if (el) el.remove();
                bootstrap.Modal.getInstance(document.getElementById('editFacilityModal')).hide();
                const saveBtn = document.getElementById(`save-placement-btn-${currentSchoolId}`);
                if (saveBtn) saveBtn.disabled = false;
            })
            .catch((error) => {
                console.error('Facility delete error:', error);
                Swal.fire('Error', error.message || 'Failed to delete facility.', 'error');
            });
        }
        // Apply card states on load
        document.addEventListener('DOMContentLoaded', function() {
            const cardStates = JSON.parse(localStorage.getItem('fireSafetyExtCardStates') || '{}');
            const cardId = 'recent-inspections-card-{{ $school->id }}';
            const card = document.getElementById(cardId);
            if (card && cardStates[cardId] === 'collapsed') {
                card.classList.add('card-collapsed');
            }
        });
    </script>
@endsection
