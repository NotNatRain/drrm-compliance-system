<div class="modal fade" id="createEvacCenterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('typhoon.evacuation-center.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header" style="background-color:#1B4C6D;color:white;">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle"></i> Create Evacuation Center / School
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Use Existing Registered School</label>
                        <select name="existing_school_id" id="cec_existing_school_id" class="form-select">
                            <option value="">-- None / Create New --</option>
                            @foreach(\App\Models\FireSafetySchool::orderBy('school_name')->get() as $school)
                                <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Choose a school from the Fire Safety module, or leave blank to create a new one.</small>
                    </div>
                    
                    <div id="cec_new_school_fields">
                        <hr>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Identification (School ID / Code)</label>
                            <input type="text" name="identification" class="form-control" placeholder="e.g. ES-001">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">School Name (for new school)</label>
                            <input type="text" name="school_name" class="form-control" placeholder="e.g. San Isidro Elementary School">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Location</label>
                            <textarea name="location" rows="2" class="form-control" placeholder="Barangay, Municipality, Province"></textarea>
                        </div>
                    </div>

                    <hr>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Capacity (Max Individuals)</label>
                        <input type="number" name="capacity" class="form-control" placeholder="e.g. 500" min="0" value="0">
                        <small class="text-muted">Set to 0 for unlimited or unknown.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Initial Usage Status</label>
                        <select name="usage_status" class="form-select">
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
                    <button type="submit" class="btn" style="background-color:#1B4C6D;color:white;">Create</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('cec_existing_school_id');
        const fields = document.getElementById('cec_new_school_fields');
        if (select && fields) {
            function toggleFields() {
                if (select.value) {
                    fields.style.display = 'none';
                    // Optional: clear fields or remove required
                } else {
                    fields.style.display = 'block';
                }
            }
            select.addEventListener('change', toggleFields);
            toggleFields(); // init
        }
    });
</script>

