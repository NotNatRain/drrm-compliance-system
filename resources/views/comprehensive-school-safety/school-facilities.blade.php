@extends('comprehensive-school-safety.layouts.app')
@section('activeMenu', 'facilities')
@section('headerLabel', $school->name ?? 'Facilities')

@section('content')
<div class="d-flex justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h2 class="csss-section-title mb-1">Safety Facilities Dashboard</h2>
        <p class="csss-muted mb-0">Facilities and Fire Safety references for this school are shown below.</p>
        <p class="csss-muted small mb-0 mt-1">Buildings are referenced from the Fire Safety module, not duplicated here. Risk is derived from Fire Safety scores, plans, alarms, and extinguisher coverage.</p>
    </div>
    <div class="text-end">
        <div class="badge bg-dark text-white px-3 py-2 mb-2">{{ $assessmentSummary['building_count'] ?? 0 }} Fire Safety references</div>
        <p class="csss-muted small mb-0">Average score: <strong>{{ $assessmentSummary['average_score'] ?? 0 }}</strong></p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="csss-card p-4 h-100">
            <p class="csss-muted small mb-2">Average safety score</p>
            <h3 class="fw-bold mb-0">{{ $assessmentSummary['average_score'] ?? 0 }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="csss-card p-4 h-100">
            <p class="csss-muted small mb-2">Good</p>
            <h3 class="fw-bold mb-0 text-success">{{ $assessmentSummary['good_count'] ?? 0 }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="csss-card p-4 h-100">
            <p class="csss-muted small mb-2">Fair</p>
            <h3 class="fw-bold mb-0 text-warning">{{ $assessmentSummary['fair_count'] ?? 0 }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="csss-card p-4 h-100">
            <p class="csss-muted small mb-2">Needs attention</p>
            <h3 class="fw-bold mb-0 text-danger">{{ $assessmentSummary['poor_count'] ?? 0 }}</h3>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="csss-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Risk Register</h5>
                    <p class="csss-muted small mb-0">One card per building with core safety indicators.</p>
                </div>
                <div class="text-end">
                    <div class="small text-muted fw-bold">Engineer Last Inspection Date</div>
                    <div class="badge bg-light text-dark border px-3 py-2">
                        {{ $school->engineer_last_inspection_date ? \Carbon\Carbon::parse($school->engineer_last_inspection_date)->format('M d, Y') : 'N/A' }}
                    </div>
                </div>
            </div>

            <div class="row g-3">
                @forelse($riskRegister as $item)
                    <div class="col-lg-6">
                        <div class="border rounded-3 p-3 h-100 {{ $item['needs_attention'] ? 'bg-light' : '' }}">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $item['title'] }}</h6>
                                    <div class="csss-muted small mb-0">Rooms: <strong>{{ $item['rooms'] }}</strong> | Floors: <strong>{{ $item['floors'] }}</strong></div>
                                </div>
                                <span class="badge bg-{{ $item['color'] ?? 'secondary' }}">{{ $item['status'] }}</span>
                            </div>

                            <ul class="list-unstyled small mb-3">
                                <li class="mb-1"><strong>Exits:</strong> {{ $item['exits'] }}</li>
                                <li class="mb-1"><strong>Alarms:</strong> {{ $item['alarms'] }}</li>
                                <li class="mb-1"><strong>Extinguishers:</strong> {{ $item['extinguishers'] }}</li>
                            </ul>

                            @if(!empty($item['description']))
                                <div class="border-top pt-3 mb-3">
                                    <div class="small text-muted fw-bold mb-1">Building Description</div>
                                    <div class="small">{{ $item['description'] }}</div>
                                </div>
                            @endif

                            <div class="d-flex justify-content-end align-items-center gap-1" style="flex-wrap: nowrap;">
                                <button
                                    type="button"
                                    class="btn btn-outline-secondary open-summary-findings-modal text-nowrap csss-card-action-btn"
                                    data-building-id="{{ $item['building_id'] }}"
                                    data-building-title="{{ $item['title'] }}"
                                >
                                    <i class="fas fa-clipboard-list me-1"></i> Summary of Findings
                                </button>

                                <a href="{{ $item['manage_url'] }}" class="btn btn-outline-primary js-module-link text-nowrap csss-card-action-btn" data-module="fire_safety" data-school-id="{{ $school->id }}">
                                    <i class="fas fa-fire-extinguisher me-1"></i> Manage at Fire Safety
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 border rounded-3 bg-light">
                        <i class="fas fa-shield-halved text-muted" style="font-size: 2rem;"></i>
                        <p class="csss-muted mt-3 mb-0">No risk records available yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="csss-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Facilities</h5>
                    <p class="csss-muted small mb-0">Main reference list for school facilities and assembly areas.</p>
                </div>
                @if(auth()->user()->role !== 'viewer')
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addFacilityModal">
                        <i class="fas fa-plus me-1"></i> Add Facility
                    </button>
                @endif
            </div>

            <div class="d-grid gap-3">
                @forelse($facilities as $facility)
                    @php
                        $isAssembly = ($facility->type ?? '') === 'assembly_area';
                    @endphp
                    <div class="border rounded-3 p-3 bg-white facility-card {{ $isAssembly ? 'facility-assembly' : '' }}"
                         data-id="{{ $facility->id }}"
                         data-name="{{ $facility->name }}"
                         data-type="{{ $facility->type }}"
                         data-description="{{ $facility->description }}"
                         data-condition="{{ $facility->condition }}"
                         data-remarks="{{ $facility->remarks }}"
                         style="cursor: {{ auth()->user()->role !== 'viewer' ? 'pointer' : 'default' }};"
                         {{ auth()->user()->role !== 'viewer' ? 'data-bs-toggle=modal data-bs-target=#editFacilityModal' : '' }}>
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $facility->name }}</h6>
                                @if(!empty($facility->description))
                                    <div class="small mb-2">{{ $facility->description }}</div>
                                @endif
                                <div class="small"><strong>Remarks:</strong> {{ $facility->remarks ?: 'N/A' }}</div>
                            </div>
                            <span class="badge {{ $isAssembly ? 'bg-light text-dark border' : 'bg-secondary-subtle text-dark' }}">
                                {{ ucfirst($facility->condition ?? 'good') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 border rounded-3 bg-light">
                        <i class="fas fa-warehouse text-muted" style="font-size: 2rem;"></i>
                        <p class="csss-muted mt-3 mb-0">No facilities recorded yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="csss-card p-4 mb-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h5 class="fw-bold mb-1">Action Tracker</h5>
            <p class="csss-muted small mb-0">This section displays the full evacuation plan pulled from Fire Safety.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-danger">Evacuation plan</span>
            @if(!$fireSafetyPlan && auth()->user()->role !== 'viewer')
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createSchoolEvacPlanModal">
                    <i class="fas fa-plus me-1"></i> Create School Plan
                </button>
            @endif
        </div>
    </div>

    @if(!$fireSafetyPlan)
        <div class="text-center py-4 border rounded-3 bg-light">
            <i class="fas fa-route text-muted" style="font-size: 2rem;"></i>
            <p class="csss-muted mt-3 mb-0">No building evacuation plan linked.</p>
            @if(auth()->user()->role !== 'viewer')
                <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#createSchoolEvacPlanModal">
                    <i class="fas fa-plus me-1"></i> Create Evacuation Plan (Entire School)
                </button>
            @endif
        </div>
    @else
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="border rounded-3 p-3 h-100 bg-white">
                    <h6 class="fw-bold mb-3">Evacuation Plan Details</h6>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2"><strong>Plan No:</strong> {{ $fireSafetyPlan->plan_no ?? 'N/A' }}</li>
                        <li class="mb-2"><strong>Exits:</strong> {{ $fireSafetyPlan->exits ?? 0 }}</li>
                        <li class="mb-2"><strong>Routes:</strong> {{ $fireSafetyPlan->routes ?? 0 }}</li>
                        <li class="mb-2"><strong>Areas:</strong> {{ $fireSafetyPlan->areas ?? 0 }}</li>
                        <li class="mb-2"><strong>Primary Route:</strong> {{ $fireSafetyPlan->primary_route ?? 'N/A' }}</li>
                        <li class="mb-2"><strong>Secondary Route:</strong> {{ $fireSafetyPlan->secondary_route ?? 'N/A' }}</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="border rounded-3 p-3 h-100 bg-white">
                    <h6 class="fw-bold mb-3">Assembly & Safety Details</h6>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2"><strong>Primary Assembly Area:</strong> {{ $fireSafetyPlan->primary_assembly_area ?? 'N/A' }}</li>
                        <li class="mb-2"><strong>Secondary Assembly Area:</strong> {{ $fireSafetyPlan->secondary_assembly_area ?? 'N/A' }}</li>
                        <li class="mb-2"><strong>Assembly Capacity:</strong> {{ $fireSafetyPlan->assembly_capacity ?? 0 }}</li>
                        <li class="mb-2"><strong>Emergency Contacts:</strong> {{ $fireSafetyPlan->emergency_contacts ?? 'N/A' }}</li>
                        <li class="mb-2"><strong>Special Instructions:</strong> {{ $fireSafetyPlan->special_instructions ?? 'N/A' }}</li>
                        <li class="mb-2"><strong>Safety Features Installed:</strong> {{ $fireSafetyPlan->safety_features_installed ?? 'N/A' }}</li>
                    </ul>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="mb-3">
    <a href="{{ route('hazard-mapping.dashboard') }}" class="btn w-100 text-white fw-bold py-3" style="background: linear-gradient(135deg, #0D7377 0%, #14a3a8 100%); border: none;">
        <i class="fas fa-map-marked-alt me-2"></i> To see passageway and detailed safety hazards go to Hazard Mapping Compliance System
    </a>
</div>

<div class="modal fade" id="moduleRegistrationWarningModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #0D7377 0%, #14a3a8 100%); border-bottom: none;">
                <h5 class="modal-title"><i class="fas fa-triangle-exclamation me-2"></i>Module Registration Required</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0" id="moduleRegistrationWarningMessage">This module still has not registered this school yet. Contact administrator to register it.</p>
            </div>
            <div class="modal-footer" style="background: #e6f8f8;">
                <button type="button" class="btn text-white" data-bs-dismiss="modal" style="background: #0D7377; border: none;">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="summaryFindingsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background: linear-gradient(135deg, #5c4033 0%, #8b6f47 100%); color: #fff;">
                <h5 class="modal-title">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Summary of Findings - <span id="summaryFindingsBuildingTitle">Building</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('comprehensive-school-safety.school.summary-findings.store', $school->id) }}">
                @csrf
                <input type="hidden" name="building_id" id="summaryFindingsBuildingId">
                <input type="hidden" name="_method" id="summaryFindingsMethod" value="POST">

                <div class="modal-body">
                    <div class="alert alert-secondary d-flex align-items-center justify-content-between" id="summaryFindingsLockBanner" role="alert">
                        <span><i class="fas fa-lock me-2"></i>This modal opens in locked mode. Click Update to enable editing.</span>
                        <button type="button" class="btn btn-sm btn-outline-dark" id="summaryFindingsUnlockBtn">
                            <i class="fas fa-pen me-1"></i> Update
                        </button>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Concerns *</label>
                            <select class="form-select" name="concern_category" id="concernCategoryInput" required>
                                <option value="">-- Select Concern Category --</option>
                                @foreach($concernCategoryOptions as $categoryOption)
                                    <option value="{{ $categoryOption }}">{{ $categoryOption }}</option>
                                @endforeach
                                <option value="__other__">Others please specify...</option>
                            </select>
                            <input type="text" class="form-control form-control-sm mt-2 d-none" name="other_concern_category" id="otherConcernCategoryInput" placeholder="Type other concern category">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Concern Type *</label>
                            <select class="form-select" name="concern_type" id="concernTypeInput" required>
                                <option value="">-- Select Concern Type --</option>
                                @foreach($concernTypeOptions as $typeOption)
                                    <option value="{{ $typeOption }}">{{ $typeOption }}</option>
                                @endforeach
                                <option value="__other__">Others please specify...</option>
                            </select>
                            <input type="text" class="form-control form-control-sm mt-2 d-none" name="other_concern_type" id="otherConcernTypeInput" placeholder="Type other concern type">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Priority *</label>
                            <select class="form-select" name="priority" required>
                                <option value="high">High</option>
                                <option value="medium" selected>Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Observation Date *</label>
                            <input type="date" class="form-control" name="observation_date" value="{{ now()->toDateString() }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Floor Number</label>
                            <select class="form-select" name="floor_number" id="summaryFindingFloorNumber">
                                <option value="">-- Select floor --</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Room</label>
                            <select class="form-select" name="room_code" id="summaryFindingRoomCode">
                                <option value="">-- Select room --</option>
                            </select>
                            <div class="form-text">Use room list from Fire Safety records for this building.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Description *</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Describe the finding in detail" required></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Remarks</label>
                            <textarea class="form-control" name="remarks" rows="2" placeholder="Additional info / remarks"></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold mb-2">Room Inside Information (for Hazard Mapping)</label>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <input type="number" min="0" class="form-control" name="chairs_count" placeholder="No. of Chairs">
                                </div>
                                <div class="col-md-4">
                                    <input type="number" min="0" class="form-control" name="tables_count" placeholder="No. of Tables">
                                </div>
                                <div class="col-md-4">
                                    <input type="number" min="0" class="form-control" name="tv_count" placeholder="No. of TVs">
                                </div>
                                <div class="col-md-4">
                                    <input type="number" min="0" class="form-control" name="electric_fan_count" placeholder="No. of Electric Fans">
                                </div>
                                <div class="col-md-4">
                                    <input type="number" min="0" class="form-control" name="ceiling_fan_count" placeholder="No. of Ceiling Fans">
                                </div>
                                <div class="col-md-4">
                                    <input type="number" min="0" class="form-control" name="water_dispenser_count" placeholder="No. of Water Dispensers">
                                </div>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="window_type" placeholder="Window Type (e.g., Jalousie, Sliding, Awning)">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="fw-bold mb-2">Existing Findings for this Building</h6>
                    <div id="existingFindingsContainer" class="border rounded p-3 bg-light">
                        <p class="text-muted small mb-0">No findings yet for this building.</p>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-outline-dark d-none" id="summaryFindingsCancelEditBtn">Cancel Update</button>
                    <button type="submit" class="btn btn-primary d-none" id="summaryFindingsSubmitBtn">Save Finding</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(auth()->user()->role !== 'viewer')
    <div class="modal fade" id="createSchoolEvacPlanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i> Create Evacuation Plan (Entire School)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="createSchoolEvacPlanForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="unified_school_id" value="{{ $school->id }}">
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
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Save Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addFacilityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add Facility</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('comprehensive-school-safety.school.facilities.store', $school->id) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name *</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea class="form-control" name="description" rows="2"></textarea>
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
                            <textarea class="form-control" name="remarks" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Facility</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editFacilityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Update Facility</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="editFacilityForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name *</label>
                            <input type="text" class="form-control" name="name" id="editName" required>
                        </div>
                        <div class="mb-3" id="editDescriptionGroup">
                            <label class="form-label fw-bold">Description</label>
                            <textarea class="form-control" name="description" id="editDescription" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Condition *</label>
                            <select class="form-select" name="condition" id="editCondition" required>
                                <option value="excellent">Excellent</option>
                                <option value="good">Good</option>
                                <option value="fair">Fair</option>
                                <option value="poor">Poor</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Remarks</label>
                            <textarea class="form-control" name="remarks" id="editRemarks" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info text-white">Update Facility</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<style>
    .facility-assembly {
        background: #5C4033 !important;
        color: #fff;
        border-color: #4a3329 !important;
    }

    .facility-assembly .text-muted,
    .facility-assembly .small {
        color: #f3e5da !important;
    }

    .facility-assembly .badge {
        background: #f3e5da !important;
        color: #5C4033 !important;
    }

    .csss-card-action-btn {
        font-size: 0.68rem;
        padding: 0.2rem 0.42rem;
        line-height: 1.1;
        border-radius: 0.35rem;
    }

    .csss-card-action-btn i {
        margin-right: 0.2rem !important;
    }

    #summaryFindingsModal .modal-body {
        max-height: calc(100vh - 240px);
        overflow-y: auto;
    }

    #summaryFindingsModal .modal-footer {
        position: sticky;
        bottom: 0;
        z-index: 2;
        border-top: 1px solid #dee2e6;
    }
</style>

@if(auth()->user()->role !== 'viewer')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editModal = document.getElementById('editFacilityModal');
    const editForm = document.getElementById('editFacilityForm');
    const createPlanForm = document.getElementById('createSchoolEvacPlanForm');

    document.querySelectorAll('.facility-card').forEach((card) => {
        card.addEventListener('click', function () {
            const id = this.dataset.id;
            const type = this.dataset.type || '';
            const isAssembly = type === 'assembly_area';

            editForm.action = "{{ route('comprehensive-school-safety.school.facilities.update', [$school->id, '__ID__']) }}".replace('__ID__', id);
            document.getElementById('editName').value = this.dataset.name || '';
            document.getElementById('editDescription').value = this.dataset.description || '';
            document.getElementById('editCondition').value = this.dataset.condition || 'good';
            document.getElementById('editRemarks').value = this.dataset.remarks || '';

            document.getElementById('editDescriptionGroup').style.display = isAssembly ? 'none' : '';
            document.getElementById('editDescription').disabled = isAssembly;
        });
    });

    if (createPlanForm) {
        createPlanForm.addEventListener('submit', async function (event) {
            event.preventDefault();

            const submitButton = createPlanForm.querySelector('button[type="submit"]');
            const originalHtml = submitButton ? submitButton.innerHTML : '';
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
            }

            try {
                const response = await fetch("{{ route('fire-safety.evacuation-plan.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                    },
                    body: new FormData(createPlanForm),
                });

                const data = await response.json();
                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Failed to create evacuation plan.');
                }

                if (typeof Swal !== 'undefined') {
                    await Swal.fire('Success', data.message || 'Evacuation plan created successfully.', 'success');
                }

                const modalEl = document.getElementById('createSchoolEvacPlanModal');
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) modalInstance.hide();
                window.location.reload();
            } catch (error) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Error', error.message || 'Unable to create evacuation plan.', 'error');
                } else {
                    alert(error.message || 'Unable to create evacuation plan.');
                }
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalHtml;
                }
            }
        });
    }
});
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    const warningModalEl = document.getElementById('moduleRegistrationWarningModal');
    const warningMessageEl = document.getElementById('moduleRegistrationWarningMessage');
    const warningModal = warningModalEl ? bootstrap.Modal.getOrCreateInstance(warningModalEl) : null;

    async function verifyModuleRegistration(moduleName, schoolId) {
        const endpoint = `{{ route('module-registration.status') }}?module=${encodeURIComponent(moduleName)}&school_id=${encodeURIComponent(schoolId)}`;
        const response = await fetch(endpoint, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (!response.ok) {
            throw new Error('Unable to validate module registration right now.');
        }

        return response.json();
    }

    document.querySelectorAll('.js-module-link').forEach((link) => {
        link.addEventListener('click', async function (event) {
            const moduleName = this.dataset.module;
            const schoolId = this.dataset.schoolId;
            const href = this.getAttribute('href');

            if (!moduleName || !schoolId || !href) {
                return;
            }

            event.preventDefault();

            try {
                const result = await verifyModuleRegistration(moduleName, schoolId);
                if (result && result.registered) {
                    window.location.href = href;
                    return;
                }

                if (warningMessageEl) {
                    warningMessageEl.textContent = (result && result.message)
                        ? result.message
                        : 'This module still has not registered this school yet. Contact administrator to register it.';
                }

                if (warningModal) {
                    warningModal.show();
                }
            } catch (error) {
                if (warningMessageEl) {
                    warningMessageEl.textContent = error.message || 'Unable to validate module registration right now.';
                }
                if (warningModal) {
                    warningModal.show();
                }
            }
        });
    });
});
</script>

@php
    $buildingFloorPayload = ($fireSafetyBuildings ?? collect())->mapWithKeys(function ($building) {
        $maxFloor = max(1, (int) ($building->floors ?? 1));
        return [(string) $building->id => range(1, $maxFloor)];
    });

    $roomLookupPayload = ($roomsByBuilding ?? collect())->map(function ($rows) {
        return $rows->map(function ($room) {
            return [
                'code' => (string) ($room->room_code ?? ''),
                'name' => (string) ($room->room_name ?? ''),
                'floor_no' => (int) ($room->floor_no ?? 1),
            ];
        })->values();
    });

    $findingsByBuildingPayload = ($findingsByBuilding ?? collect())->map(function ($rows) use ($school) {
        return $rows->map(function ($f) use ($school) {
            return [
                'id' => $f->id,
                'concern_category' => $f->concern_category,
                'concern_type' => $f->concern_type,
                'priority' => strtoupper((string) $f->priority),
                'observation_date' => optional($f->observation_date)->format('M d, Y') ?? $f->observation_date,
                'observation_date_raw' => optional($f->observation_date)->format('Y-m-d') ?? null,
                'floor_number' => $f->floor_number,
                'room_code' => $f->room_code,
                'chairs_count' => (int) ($f->chairs_count ?? 0),
                'tables_count' => (int) ($f->tables_count ?? 0),
                'tv_count' => (int) ($f->tv_count ?? 0),
                'electric_fan_count' => (int) ($f->electric_fan_count ?? 0),
                'ceiling_fan_count' => (int) ($f->ceiling_fan_count ?? 0),
                'water_dispenser_count' => (int) ($f->water_dispenser_count ?? 0),
                'window_type' => $f->window_type,
                'description' => $f->description,
                'remarks' => $f->remarks,
                'delete_url' => route('comprehensive-school-safety.school.summary-findings.destroy', [$school->id, $f->id]),
                'update_url' => route('comprehensive-school-safety.school.summary-findings.update', [$school->id, $f->id]),
            ];
        })->values();
    });
@endphp

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalElement = document.getElementById('summaryFindingsModal');
    const modalInstance = modalElement ? bootstrap.Modal.getOrCreateInstance(modalElement) : null;
    const titleTarget = document.getElementById('summaryFindingsBuildingTitle');
    const buildingIdField = document.getElementById('summaryFindingsBuildingId');
    const findingsContainer = document.getElementById('existingFindingsContainer');
    const concernCategoryInput = document.getElementById('concernCategoryInput');
    const concernTypeInput = document.getElementById('concernTypeInput');
    const floorNumberInput = document.getElementById('summaryFindingFloorNumber');
    const roomCodeInput = document.getElementById('summaryFindingRoomCode');
    const lockBanner = document.getElementById('summaryFindingsLockBanner');
    const unlockBtn = document.getElementById('summaryFindingsUnlockBtn');
    const cancelEditBtn = document.getElementById('summaryFindingsCancelEditBtn');
    const submitBtn = document.getElementById('summaryFindingsSubmitBtn');
    const methodInput = document.getElementById('summaryFindingsMethod');
    const summaryForm = modalElement ? modalElement.querySelector('form') : null;
    const otherConcernCategoryInput = document.getElementById('otherConcernCategoryInput');
    const otherConcernTypeInput = document.getElementById('otherConcernTypeInput');
    const roomLookup = @json($roomLookupPayload);
    const buildingFloors = @json($buildingFloorPayload);
    const defaultStoreUrl = "{{ route('comprehensive-school-safety.school.summary-findings.store', $school->id) }}";
    let isSummaryEditEnabled = false;

    function toggleOtherField(selectElement, inputElement) {
        if (!selectElement || !inputElement) {
            return;
        }

        const show = selectElement.value === '__other__';
        inputElement.classList.toggle('d-none', !show);
        inputElement.required = show;

        if (!show) {
            inputElement.value = '';
        }
    }

    const findingsByBuilding = @json($findingsByBuildingPayload);

    function populateFloorOptions(buildingId, selectedFloor = '') {
        if (!floorNumberInput) {
            return;
        }

        const floors = buildingFloors[String(buildingId)] || [1];
        floorNumberInput.innerHTML = '<option value="">-- Select floor --</option>';
        floors.forEach((floorNo) => {
            const option = document.createElement('option');
            option.value = String(floorNo);
            option.textContent = floorNo === 1 ? 'Ground Floor' : `Floor ${floorNo}`;
            if (String(selectedFloor) === String(floorNo)) {
                option.selected = true;
            }
            floorNumberInput.appendChild(option);
        });
    }

    function populateRoomOptions(buildingId, selectedFloor = '', selectedRoomCode = '') {
        if (!roomCodeInput) {
            return;
        }

        const floor = selectedFloor ? parseInt(selectedFloor, 10) : null;
        const rows = (roomLookup[String(buildingId)] || []).filter((room) => {
            if (!floor) {
                return true;
            }
            return parseInt(room.floor_no || 1, 10) === floor;
        });

        roomCodeInput.innerHTML = '<option value="">-- Select room --</option>';
        rows.forEach((room) => {
            const option = document.createElement('option');
            option.value = room.code || '';
            option.textContent = room.code ? `${room.code} - ${room.name || 'Room'}` : (room.name || 'Room');
            if (String(selectedRoomCode || '').toUpperCase() === String(room.code || '').toUpperCase()) {
                option.selected = true;
            }
            roomCodeInput.appendChild(option);
        });
    }

    if (floorNumberInput) {
        floorNumberInput.addEventListener('change', function () {
            const buildingId = buildingIdField ? buildingIdField.value : '';
            populateRoomOptions(buildingId, this.value, roomCodeInput ? roomCodeInput.value : '');
        });
    }

    if (concernCategoryInput) {
        concernCategoryInput.addEventListener('change', function () {
            toggleOtherField(concernCategoryInput, otherConcernCategoryInput);
        });
    }

    if (concernTypeInput) {
        concernTypeInput.addEventListener('change', function () {
            toggleOtherField(concernTypeInput, otherConcernTypeInput);
        });
    }

    function findingPriorityBadge(priority) {
        const p = String(priority || '').toUpperCase();
        if (p === 'HIGH') return 'danger';
        if (p === 'LOW') return 'success';
        return 'warning text-dark';
    }

    function setSummaryEditMode(enabled) {
        isSummaryEditEnabled = !!enabled;

        if (unlockBtn) {
            unlockBtn.classList.toggle('d-none', isSummaryEditEnabled);
        }
        if (cancelEditBtn) {
            cancelEditBtn.classList.toggle('d-none', !isSummaryEditEnabled);
        }
        if (submitBtn) {
            submitBtn.classList.toggle('d-none', !isSummaryEditEnabled);
        }
        if (lockBanner) {
            lockBanner.classList.toggle('alert-secondary', !isSummaryEditEnabled);
            lockBanner.classList.toggle('alert-warning', isSummaryEditEnabled);
            const bannerText = lockBanner.querySelector('span');
            if (bannerText) {
                bannerText.innerHTML = isSummaryEditEnabled
                    ? '<i class="fas fa-lock-open me-2"></i>Update mode enabled. You can edit details and save changes.'
                    : '<i class="fas fa-lock me-2"></i>This modal opens in locked mode. Click Update to enable editing.';
            }
        }

        const editableElements = modalElement.querySelectorAll('input, select, textarea');
        editableElements.forEach((element) => {
            if (element.id === 'summaryFindingsBuildingId' || element.id === 'summaryFindingsMethod') {
                return;
            }
            element.disabled = !isSummaryEditEnabled;
        });

        const deleteButtons = modalElement.querySelectorAll('.js-finding-delete-btn');
        deleteButtons.forEach((button) => {
            button.disabled = !isSummaryEditEnabled;
        });

        const editButtons = modalElement.querySelectorAll('.js-finding-edit-btn');
        editButtons.forEach((button) => {
            button.disabled = !isSummaryEditEnabled;
        });
    }

    function resetSummaryFormToCreate() {
        if (!summaryForm) {
            return;
        }

        summaryForm.action = defaultStoreUrl;
        if (methodInput) {
            methodInput.value = 'POST';
        }
        if (submitBtn) {
            submitBtn.textContent = 'Save Finding';
        }
    }

    function renderExistingFindings(buildingId) {
        if (!findingsContainer) {
            return;
        }

        const rows = findingsByBuilding[String(buildingId)] || [];
        if (!rows.length) {
            findingsContainer.innerHTML = '<p class="text-muted small mb-0">No findings yet for this building.</p>';
            return;
        }

        findingsContainer.innerHTML = rows.map((row) => {
            const badge = findingPriorityBadge(row.priority);
            const remarks = row.remarks ? `<div class="small mt-1"><strong>Remarks:</strong> ${row.remarks}</div>` : '';
            const placement = [
                row.floor_number ? `Floor ${row.floor_number}` : '',
                row.room_code ? `Room ${row.room_code}` : ''
            ].filter(Boolean).join(' | ');
            const insideInfo = `
                <div class="small mt-2 text-muted">
                    Chairs: ${row.chairs_count || 0}, Tables: ${row.tables_count || 0}, TV: ${row.tv_count || 0},
                    Electric Fans: ${row.electric_fan_count || 0}, Ceiling Fans: ${row.ceiling_fan_count || 0},
                    Water Dispensers: ${row.water_dispenser_count || 0}${row.window_type ? ', Window: ' + row.window_type : ''}
                </div>
            `;
            return `
                <div class="border rounded bg-white p-3 mb-2">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <div class="fw-bold">${row.concern_category} - ${row.concern_type}</div>
                            <div class="small text-muted">Observed: ${row.observation_date}</div>
                            ${placement ? `<div class="small text-muted">${placement}</div>` : ''}
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-${badge}">${row.priority}</span>
                            <button type="button" class="btn btn-sm btn-outline-primary js-finding-edit-btn" data-finding-id="${row.id}">
                                <i class="fas fa-pen"></i>
                            </button>
                            <form method="POST" action="${row.delete_url}" onsubmit="return confirm('Delete this finding?')">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-outline-danger js-finding-delete-btn"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    <div class="small mt-2">${row.description}</div>
                    ${insideInfo}
                    ${remarks}
                </div>
            `;
        }).join('');
    }

    document.querySelectorAll('.open-summary-findings-modal').forEach((button) => {
        button.addEventListener('click', function () {
            const buildingId = this.dataset.buildingId;
            const buildingTitle = this.dataset.buildingTitle || 'Building';

            if (summaryForm) {
                summaryForm.reset();
            }
            resetSummaryFormToCreate();

            if (titleTarget) {
                titleTarget.textContent = buildingTitle;
            }

            if (buildingIdField) {
                buildingIdField.value = buildingId;
            }

            populateFloorOptions(buildingId);
            populateRoomOptions(buildingId);

            if (concernCategoryInput) {
                concernCategoryInput.value = '';
            }
            if (concernTypeInput) {
                concernTypeInput.value = '';
            }
            toggleOtherField(concernCategoryInput, otherConcernCategoryInput);
            toggleOtherField(concernTypeInput, otherConcernTypeInput);

            renderExistingFindings(buildingId);
            setSummaryEditMode(false);

            if (modalInstance) {
                modalInstance.show();
            }
        });
    });

    if (unlockBtn) {
        unlockBtn.addEventListener('click', function () {
            setSummaryEditMode(true);
        });
    }

    if (cancelEditBtn) {
        cancelEditBtn.addEventListener('click', function () {
            resetSummaryFormToCreate();
            setSummaryEditMode(false);
        });
    }

    if (findingsContainer) {
        findingsContainer.addEventListener('click', function (event) {
            const editButton = event.target.closest('.js-finding-edit-btn');
            if (!editButton || !isSummaryEditEnabled) {
                return;
            }

            const findingId = String(editButton.dataset.findingId || '');
            const buildingId = buildingIdField ? String(buildingIdField.value || '') : '';
            const rows = findingsByBuilding[buildingId] || [];
            const row = rows.find((item) => String(item.id) === findingId);
            if (!row || !summaryForm) {
                return;
            }

            summaryForm.action = row.update_url;
            if (methodInput) {
                methodInput.value = 'PUT';
            }
            if (submitBtn) {
                submitBtn.textContent = 'Update Finding';
            }

            if (concernCategoryInput) {
                concernCategoryInput.value = row.concern_category || '';
            }
            if (concernTypeInput) {
                concernTypeInput.value = row.concern_type || '';
            }
            if (floorNumberInput) {
                populateFloorOptions(buildingId, row.floor_number || '');
                floorNumberInput.value = row.floor_number || '';
            }
            populateRoomOptions(buildingId, row.floor_number || '', row.room_code || '');

            const fields = {
                observation_date: row.observation_date_raw || '',
                description: row.description || '',
                remarks: row.remarks || '',
                chairs_count: row.chairs_count ?? '',
                tables_count: row.tables_count ?? '',
                tv_count: row.tv_count ?? '',
                electric_fan_count: row.electric_fan_count ?? '',
                ceiling_fan_count: row.ceiling_fan_count ?? '',
                water_dispenser_count: row.water_dispenser_count ?? '',
                window_type: row.window_type || '',
            };

            Object.keys(fields).forEach((name) => {
                const input = summaryForm.querySelector(`[name="${name}"]`);
                if (input) {
                    input.value = fields[name];
                }
            });

            const priorityInput = summaryForm.querySelector('[name="priority"]');
            if (priorityInput) {
                priorityInput.value = String(row.priority || '').toLowerCase();
            }

            toggleOtherField(concernCategoryInput, otherConcernCategoryInput);
            toggleOtherField(concernTypeInput, otherConcernTypeInput);
        });
    }
});
</script>
@endpush
