<div class="modal fade" id="logIncidentModal" tabindex="-1" aria-labelledby="logIncidentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content incident-modal">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="logIncidentModalLabel">
                    <i class="fas fa-plus-circle me-2"></i> Log New Incident/Event
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="incidentTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="incident-tab" data-bs-toggle="tab" data-bs-target="#incident-form" type="button" role="tab">
                            <i class="fas fa-exclamation-triangle me-2"></i> Incident
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="compliance-tab" data-bs-toggle="tab" data-bs-target="#compliance-form" type="button" role="tab">
                            <i class="fas fa-calendar-check me-2"></i> Compliance Status/Event
                        </button>
                    </li>
                </ul>

                <div class="tab-content mt-4">
                    <!-- Incident Form -->
                    <div class="tab-pane fade show active" id="incident-form" role="tabpanel">
                        <form id="incidentForm">
                            @csrf
                            <input type="hidden" name="entry_type" value="incident">
                            <input type="hidden" name="incident_id" id="incident_update_id" value="">
                            <input type="hidden" name="incident_date" id="incident_date" value="{{ date('Y-m-d') }}">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="incident_type_id" class="form-label">Incident Type *</label>
                                    <select class="form-select" id="incident_type_id" name="incident_type_id" required>
                                        <option value="">Select Incident Type</option>
                                        @foreach($incidentTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                        <option value="others">Others</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3" id="incident_other_type_group" style="display: none;">
                                    <label for="incident_other_type" class="form-label">Please Specify:</label>
                                    <input type="text" class="form-control" id="incident_other_type" name="incident_other_type" placeholder="Specify incident type...">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="incident_date_input" class="form-label">Date *</label>
                                    <input type="date" class="form-control" id="incident_date_input" name="incident_date_input" required
                                           value="{{ date('Y-m-d') }}" @if(auth()->user()->role !== 'admin') max="{{ date('Y-m-d') }}" @endif>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">School Name *</label>
                                    @if(auth()->user()->role !== 'admin' && !empty($assignedIncidentSchoolName ?? null))
                                        <input type="text" class="form-control" value="{{ $assignedIncidentSchoolName }}" readonly>
                                        <input type="hidden" id="incident_assigned_school_name" value="{{ $assignedIncidentSchoolName }}">
                                        <small class="text-muted small">This module is assigned to one school only for your account.</small>
                                    @else
                                        <div class="d-flex flex-wrap gap-3 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input incident-school-source" type="radio" name="incident_source_type" id="incident_source_existing" value="existing" checked>
                                                <label class="form-check-label small fw-600" for="incident_source_existing">Use Existing Registered School</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input incident-school-source" type="radio" name="incident_source_type" id="incident_source_new" value="new">
                                                <label class="form-check-label small fw-600" for="incident_source_new">Input New School Name</label>
                                            </div>
                                            @if(auth()->user()->role === 'admin')
                                            <div class="form-check">
                                                <input class="form-check-input incident-school-source" type="radio" name="incident_source_type" id="incident_source_all" value="all">
                                                <label class="form-check-label small fw-600" for="incident_source_all">All Schools</label>
                                            </div>
                                            @endif
                                        </div>

                                        <div id="incident_existing_school_container">
                                            <select class="form-select" id="incident_school_existing_select" name="school_name_existing">
                                                <option value="">-- Select Registered School --</option>
                                                @foreach($fireSafetySchools as $fsSchool)
                                                    <option value="{{ $fsSchool->school_name }}">{{ $fsSchool->school_name }}</option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted small">Choose a school from the registered list.</small>
                                        </div>

                                        <div id="incident_new_school_container" style="display: none;">
                                            <input type="text" class="form-control" id="incident_school_name_manual" name="school_name_manual"
                                                   placeholder="Enter new school name...">
                                            <small class="text-muted small">Manually type the school name if not registered.</small>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="affected_personnel" class="form-label">Affected Personnel <small class="text-muted">(optional, leave empty or 0)</small></label>
                                    <input type="number" class="form-control" id="affected_personnel" name="affected_personnel" min="0" placeholder="0" value="">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="affected_students" class="form-label">Affected Students <small class="text-muted">(optional, leave empty or 0)</small></label>
                                    <input type="number" class="form-control" id="affected_students" name="affected_students" min="0" placeholder="0" value="">
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="remarks" class="form-label">Remarks/Description *</label>
                                    <textarea class="form-control" id="remarks" name="remarks" rows="3"
                                              placeholder="Provide detailed description of the incident..." required></textarea>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="incident_attachment" class="form-label">
                                    Attachment/Evidence <span class="text-danger" id="incident_attachment_required_asterisk">*</span>
                                    <small class="text-muted">(PDF, JPG, PNG - Max 10MB)</small>
                                </label>
                                <div id="current_incident_attachment" class="mb-2 p-2 border rounded bg-light" style="display: none;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span><i class="fas fa-paperclip me-2 text-warning"></i> <span id="incident_attachment_name" class="small fw-bold text-truncate" style="max-width: 200px;"></span></span>
                                        <a id="incident_attachment_view" href="#" target="_blank" class="btn btn-sm btn-link text-warning p-0">View current</a>
                                    </div>
                                </div>
                                <input type="file" class="form-control" id="incident_attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted fst-italic" id="incident_attachment_hint">You can attach evidence now or later during update.</small>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-2"></i> Save Incident
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Compliance Form -->
                    <div class="tab-pane fade" id="compliance-form" role="tabpanel">
                        <form id="complianceForm">
                            @csrf
                            <input type="hidden" name="entry_type" value="compliance">
                            <input type="hidden" name="compliance_id" id="compliance_update_id" value="">
                            <input type="hidden" name="incident_date" id="compliance_incident_date" value="{{ date('Y-m-d') }}">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="incident_status_id" class="form-label">Compliance Status/Event *</label>
                                    <select class="form-select" id="incident_status_id" name="incident_status_id" required>
                                        <option value="">Select Status/Event</option>
                                        @foreach($incidentStatuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                        <option value="others">Others</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3" id="compliance_other_status_group" style="display: none;">
                                    <label for="compliance_other_status" class="form-label">Please Specify:</label>
                                    <input type="text" class="form-control" id="compliance_other_status" name="compliance_other_status" placeholder="Specify compliance status/event...">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="compliance_date_input" class="form-label">Date *</label>
                                    <input type="date" class="form-control" id="compliance_date_input" name="incident_date_input" required
                                           value="{{ date('Y-m-d') }}" @if(auth()->user()->role !== 'admin') max="{{ date('Y-m-d') }}" @endif>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">School Name *</label>
                                    @if(auth()->user()->role !== 'admin' && !empty($assignedIncidentSchoolName ?? null))
                                        <input type="text" class="form-control" value="{{ $assignedIncidentSchoolName }}" readonly>
                                        <input type="hidden" id="compliance_assigned_school_name" value="{{ $assignedIncidentSchoolName }}">
                                        <small class="text-muted small">This module is assigned to one school only for your account.</small>
                                    @else
                                        <div class="d-flex flex-wrap gap-3 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input compliance-school-source" type="radio" name="compliance_source_type" id="compliance_source_existing" value="existing" checked>
                                                <label class="form-check-label small fw-600" for="compliance_source_existing">Use Existing Registered School</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input compliance-school-source" type="radio" name="compliance_source_type" id="compliance_source_new" value="new">
                                                <label class="form-check-label small fw-600" for="compliance_source_new">Input New School Name</label>
                                            </div>
                                            @if(auth()->user()->role === 'admin')
                                            <div class="form-check">
                                                <input class="form-check-input compliance-school-source" type="radio" name="compliance_source_type" id="compliance_source_all" value="all">
                                                <label class="form-check-label small fw-600" for="compliance_source_all">All Schools</label>
                                            </div>
                                            @endif
                                        </div>

                                        <div id="compliance_existing_school_container">
                                            <select class="form-select" id="compliance_school_existing_select" name="compliance_school_name_existing">
                                                <option value="">-- Select Registered School --</option>
                                                @foreach($fireSafetySchools as $fsSchool)
                                                    <option value="{{ $fsSchool->school_name }}">{{ $fsSchool->school_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div id="compliance_new_school_container" style="display: none;">
                                            <input type="text" class="form-control" id="compliance_school_name_manual" name="compliance_school_name_manual"
                                                   placeholder="Enter new school name...">
                                        </div>
                                    @endif
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="compliance_remarks" class="form-label">Remarks/Description *</label>
                                    <textarea class="form-control" id="compliance_remarks" name="remarks" rows="3"
                                              placeholder="Provide details about the compliance status/event..." required></textarea>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="compliance_attachment" class="form-label">
                                    Attachment/Evidence
                                    <small class="text-muted">(Optional - PDF, JPG, PNG, Max 10MB)</small>
                                </label>
                                <div id="current_compliance_attachment" class="mb-2 p-2 border rounded bg-light" style="display: none;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span><i class="fas fa-paperclip me-2 text-success"></i> <span id="compliance_attachment_name" class="small fw-bold text-truncate" style="max-width: 200px;"></span></span>
                                        <a id="compliance_attachment_view" href="#" target="_blank" class="btn btn-sm btn-link text-success p-0">View current</a>
                                    </div>
                                </div>
                                <input type="file" class="form-control" id="compliance_attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-2"></i> Save Compliance Event
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const incidentTypeSelect = document.getElementById('incident_type_id');
        const incidentOtherGroup = document.getElementById('incident_other_type_group');
        const incidentOtherInput = document.getElementById('incident_other_type');

        const complianceStatusSelect = document.getElementById('incident_status_id');
        const complianceOtherGroup = document.getElementById('compliance_other_status_group');
        const complianceOtherInput = document.getElementById('compliance_other_status');

        function toggleIncidentOther() {
            if (!incidentTypeSelect || !incidentOtherGroup || !incidentOtherInput) return;
            const isOthers = incidentTypeSelect.value === 'others';
            incidentOtherGroup.style.display = isOthers ? 'block' : 'none';
            incidentOtherInput.required = isOthers;
            if (!isOthers) incidentOtherInput.value = '';
        }

        function toggleComplianceOther() {
            if (!complianceStatusSelect || !complianceOtherGroup || !complianceOtherInput) return;
            const isOthers = complianceStatusSelect.value === 'others';
            complianceOtherGroup.style.display = isOthers ? 'block' : 'none';
            complianceOtherInput.required = isOthers;
            if (!isOthers) complianceOtherInput.value = '';
        }

        if (incidentTypeSelect) {
            incidentTypeSelect.addEventListener('change', toggleIncidentOther);
            toggleIncidentOther();
        }

        if (complianceStatusSelect) {
            complianceStatusSelect.addEventListener('change', toggleComplianceOther);
            toggleComplianceOther();
        }
    });
</script>
