<div class="modal fade" id="createEvacCenterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('typhoon.evacuation-center.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header" style="background-color: #0a192f; color: #00d2ff; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i> Create Evacuation Center / School
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Use Existing Registered School</label>
                        <select name="existing_school_id" id="cec_existing_school_id" class="form-select">
                            <option value="">-- None / Create New --</option>
                            @foreach($unregisteredSchools ?? [] as $school)
                                <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Choose a school from the Fire Safety module, or leave blank to create a new one.</small>
                    </div>
                    
                    <div id="cec_new_school_fields">
                        <hr>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Identification (School ID / Code)</label>
                            <input type="text" name="identification" id="cec_identification" class="form-control" placeholder="e.g. ES-001" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">School Name (for new school)</label>
                            <input type="text" name="school_name" id="cec_school_name" class="form-control" placeholder="e.g. San Isidro Elementary School" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Location</label>
                            <textarea name="location" id="cec_location" rows="2" class="form-control" placeholder="Barangay, Municipality, Province" required></textarea>
                        </div>
                    </div>

                    <hr>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Capacity (Max Individuals)</label>
                        <input type="number" name="capacity" class="form-control" placeholder="e.g. 500" min="0" value="0" required>
                        <small class="text-muted">Set to 0 for unlimited or unknown.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Initial Usage Status</label>
                        <select name="usage_status" class="form-select" required>
                            <option value="cleared">Cleared</option>
                            <option value="occupied">Occupied</option>
                            <option value="full">Full</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Emergency Resources (optional)</label>
                        <textarea name="emergency_resources" rows="2" class="form-control" placeholder="e.g. 100 cots, 50 hygiene kits, generator, etc."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background-color: #0a192f; color: #00d2ff; border: 1px solid #00d2ff;">Create Center</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('cec_existing_school_id');
        const fields = document.getElementById('cec_new_school_fields');
        const identification = document.getElementById('cec_identification');
        const schoolName = document.getElementById('cec_school_name');
        const location = document.getElementById('cec_location');
        if (select && fields) {
            function toggleFields() {
                if (select.value) {
                    fields.style.display = 'none';
                    if (identification) identification.required = false;
                    if (schoolName) schoolName.required = false;
                    if (location) location.required = false;
                } else {
                    fields.style.display = 'block';
                    if (identification) identification.required = true;
                    if (schoolName) schoolName.required = true;
                    if (location) location.required = true;
                }
            }
            select.addEventListener('change', toggleFields);
            toggleFields(); // init
        }
    });
</script>

