    <!-- View Inspection Modal -->
    <div class="modal fade" id="viewInspectionModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-eye me-2"></i> Inspection #<span id="viewInspectionIdSpan"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="viewInspectionBody" style="font-size: 0.95rem;">
                    <!-- Will be populated via JS -->
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    @if(auth()->user()->role !== 'viewer')
                    <button type="button" class="btn btn-warning px-4" onclick="openUpdateInspectionModal()">
                        <i class="fas fa-edit me-2"></i> Update
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Update Inspection Modal -->
    <div class="modal fade" id="updateInspectionModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i> Update Drill & Inspection Monitoring
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="updateInspectionForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="school_id" id="updateInspSchoolId">
                        <input type="hidden" id="updateInspId">

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Drill Type *</label>
                                <select class="form-select border-primary" name="drill_type" id="upd_drill_type" required>
                                    <option value="">Select Type</option>
                                    <option value="Earthquake">Earthquake</option>
                                    <option value="Fire">Fire</option>
                                    <option value="Both">Both Earthquake & Fire</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Date *</label>
                                <input type="date" class="form-control border-primary" name="inspection_date" id="upd_inspection_date" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Time *</label>
                                <input type="time" class="form-control border-primary" name="inspection_time" id="upd_inspection_time" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Time Started</label>
                                <input type="time" class="form-control" name="time_started" id="upd_time_started">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Time Finished</label>
                                <input type="time" class="form-control" name="time_finished" id="upd_time_finished">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Elapsed Time (mm:ss)</label>
                                <input type="text" class="form-control" name="elapsed_time" id="upd_elapsed_time" placeholder="e.g. 05:30">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">No. of Exits</label>
                                <input type="number" class="form-control" name="no_of_exits" id="upd_no_of_exits" value="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">No. of Buildings</label>
                                <input type="number" class="form-control" name="no_of_buildings" id="upd_no_of_buildings" value="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">No. of Students</label>
                                <input type="number" class="form-control" name="no_of_students" id="upd_no_of_students" value="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">No. of Personnel</label>
                                <input type="number" class="form-control" name="no_of_personnel" id="upd_no_of_personnel" value="0">
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <h6 class="fw-bold text-success border-bottom pb-2 mb-3">
                                    <i class="fas fa-list-check me-2"></i> Safety Checklist
                                </h6>
                                <div class="checklist-items scroll-y" style="max-height: 250px; overflow-y: auto;">
                                    @if(isset($checklists) && count($checklists) > 0)
                                        @foreach($checklists as $item)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input check-upd-list" type="checkbox" name="checklist_data[]" value="{{ $item->name }}" id="upd_check_{{ $item->id }}">
                                                <label class="form-check-label small" for="upd_check_{{ $item->id }}">
                                                    {{ $item->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted small">No checklist items configured in customization.</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-users-viewfinder me-2"></i> Other Observers
                                </h6>
                                <div class="observer-items scroll-y" style="max-height: 250px; overflow-y: auto;">
                                    @if(isset($observers) && count($observers) > 0)
                                        @foreach($observers as $obs)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input obs-upd-list" type="checkbox" name="observers_data[]" value="{{ $obs->name }}" id="upd_obs_{{ $obs->id }}">
                                                <label class="form-check-label small" for="upd_obs_{{ $obs->id }}">
                                                    {{ $obs->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @endif

                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="upd_obs_others_chk" onchange="toggleOthersInput(this, 'upd_obs_others_text_container')">
                                        <label class="form-check-label small" for="upd_obs_others_chk">OTHERS: (Please specify)</label>
                                    </div>
                                    <div id="upd_obs_others_text_container" style="display: none;" class="ms-4 mb-2">
                                        <input type="text" class="form-control form-control-sm border-primary" id="upd_obs_others_text" placeholder="Specify other observers...">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Remarks / Findings</label>
                            <textarea class="form-control border-primary" name="remarks" id="upd_remarks" rows="3"></textarea>
                        </div>

                        <div class="row border-top pt-3 bg-light rounded p-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Monitored By:</label>
                                <input type="text" class="form-control mb-2" name="monitored_by" id="upd_monitored_by" placeholder="Name" required>
                                <input type="text" class="form-control" name="monitored_by_position" id="upd_monitored_by_position" placeholder="Position">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">School DRRM Coordinator</label>
                                <input type="text" class="form-control" name="coordinator_name" id="upd_coordinator_name">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small fw-bold">School Head / Principal</label>
                                <input type="text" class="form-control" name="school_head_name" id="upd_school_head_name">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    @if(auth()->user()->role !== 'viewer')
                    <button type="button" class="btn btn-warning px-4" onclick="updateDrillInspection()">
                        <i class="fas fa-save me-2"></i> Update & Record Inspection
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
