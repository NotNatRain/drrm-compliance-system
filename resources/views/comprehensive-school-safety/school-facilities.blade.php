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
    <a href="{{ route('hazard-mapping.dashboard') }}" class="btn w-100 text-white fw-bold py-3 js-module-link" data-module="hazard_mapping" data-school-id="{{ $school->id }}" style="background: linear-gradient(135deg, #0D7377 0%, #14a3a8 100%); border: none;">
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

                <div class="modal-body">
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

                        <div class="col-12">
                            <label class="form-label fw-bold">Description *</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Describe the finding in detail" required></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Remarks</label>
                            <textarea class="form-control" name="remarks" rows="2" placeholder="Additional info / remarks"></textarea>
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
                    <button type="submit" class="btn btn-primary">Save Finding</button>
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
    $findingsByBuildingPayload = ($findingsByBuilding ?? collect())->map(function ($rows) use ($school) {
        return $rows->map(function ($f) use ($school) {
            return [
                'id' => $f->id,
                'concern_category' => $f->concern_category,
                'concern_type' => $f->concern_type,
                'priority' => strtoupper((string) $f->priority),
                'observation_date' => optional($f->observation_date)->format('M d, Y') ?? $f->observation_date,
                'description' => $f->description,
                'remarks' => $f->remarks,
                'delete_url' => route('comprehensive-school-safety.school.summary-findings.destroy', [$school->id, $f->id]),
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
    const otherConcernCategoryInput = document.getElementById('otherConcernCategoryInput');
    const otherConcernTypeInput = document.getElementById('otherConcernTypeInput');

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
            return `
                <div class="border rounded bg-white p-3 mb-2">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <div class="fw-bold">${row.concern_category} - ${row.concern_type}</div>
                            <div class="small text-muted">Observed: ${row.observation_date}</div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-${badge}">${row.priority}</span>
                            <form method="POST" action="${row.delete_url}" onsubmit="return confirm('Delete this finding?')">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    <div class="small mt-2">${row.description}</div>
                    ${remarks}
                </div>
            `;
        }).join('');
    }

    document.querySelectorAll('.open-summary-findings-modal').forEach((button) => {
        button.addEventListener('click', function () {
            const buildingId = this.dataset.buildingId;
            const buildingTitle = this.dataset.buildingTitle || 'Building';

            if (titleTarget) {
                titleTarget.textContent = buildingTitle;
            }

            if (buildingIdField) {
                buildingIdField.value = buildingId;
            }

            if (concernCategoryInput) {
                concernCategoryInput.value = '';
            }
            if (concernTypeInput) {
                concernTypeInput.value = '';
            }
            toggleOtherField(concernCategoryInput, otherConcernCategoryInput);
            toggleOtherField(concernTypeInput, otherConcernTypeInput);

            renderExistingFindings(buildingId);

            if (modalInstance) {
                modalInstance.show();
            }
        });
    });
});
</script>
@endpush
