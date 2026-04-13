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

                            <div class="d-flex justify-content-end">
                                <a href="{{ $item['manage_url'] }}" class="btn btn-sm btn-outline-primary js-module-link" data-module="fire_safety" data-school-id="{{ $school->id }}">
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
                                <div class="small text-muted mb-2">
                                    Type: {{ $isAssembly ? 'Assembly Area' : ucwords(str_replace(['_', '/'], [' ', ' / '], $facility->type ?? '')) }}
                                </div>
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
        <span class="badge bg-danger">Evacuation plan</span>
    </div>

    @if(!$fireSafetyPlan)
        <div class="text-center py-4 border rounded-3 bg-light">
            <i class="fas fa-route text-muted" style="font-size: 2rem;"></i>
            <p class="csss-muted mt-3 mb-0">No building evacuation plan linked.</p>
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
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="fas fa-triangle-exclamation me-2"></i>Module Registration Required</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0" id="moduleRegistrationWarningMessage">This module still has not registered this school yet. Contact administrator to register it.</p>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->role !== 'viewer')
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
                            <label class="form-label fw-bold">Type *</label>
                            <select class="form-select" name="type" required>
                                <option value="">-- Select Type --</option>
                                <option value="commercial">Commercial</option>
                                <option value="industrial">Industrial</option>
                                <option value="residential">Residential</option>
                                <option value="educational">Educational</option>
                                <option value="public/institutional">Public/Institutional</option>
                            </select>
                        </div>
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
                        <div class="mb-3" id="editTypeGroup">
                            <label class="form-label fw-bold">Type *</label>
                            <select class="form-select" name="type" id="editType">
                                <option value="commercial">Commercial</option>
                                <option value="industrial">Industrial</option>
                                <option value="residential">Residential</option>
                                <option value="educational">Educational</option>
                                <option value="public/institutional">Public/Institutional</option>
                            </select>
                        </div>
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
</style>

@if(auth()->user()->role !== 'viewer')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editModal = document.getElementById('editFacilityModal');
    const editForm = document.getElementById('editFacilityForm');

    document.querySelectorAll('.facility-card').forEach((card) => {
        card.addEventListener('click', function () {
            const id = this.dataset.id;
            const type = this.dataset.type || '';
            const isAssembly = type === 'assembly_area';

            editForm.action = "{{ route('comprehensive-school-safety.school.facilities.update', [$school->id, '__ID__']) }}".replace('__ID__', id);
            document.getElementById('editName').value = this.dataset.name || '';
            document.getElementById('editType').value = type || 'public/institutional';
            document.getElementById('editDescription').value = this.dataset.description || '';
            document.getElementById('editCondition').value = this.dataset.condition || 'good';
            document.getElementById('editRemarks').value = this.dataset.remarks || '';

            document.getElementById('editTypeGroup').style.display = isAssembly ? 'none' : '';
            document.getElementById('editDescriptionGroup').style.display = isAssembly ? 'none' : '';
            document.getElementById('editType').disabled = isAssembly;
            document.getElementById('editDescription').disabled = isAssembly;
        });
    });
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
@endpush
