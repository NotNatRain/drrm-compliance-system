<div class="modal fade" id="createEvacCenterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered mx-auto" style="margin-left:auto; margin-right:auto;">
        <form method="POST" action="{{ route('typhoon.evacuation-center.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header" style="background-color: #0a192f; color: #00d2ff; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i> Register Evacuation Center (from main school directory)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">
                        Add schools from <strong>DRRM Main Dashboard → Schools</strong> first. Only schools not yet registered for Typhoon/Flood appear below.
                    </p>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Select school to register *</label>
                        <select name="existing_school_id" id="cec_existing_school_id" class="form-select form-select-lg" required>
                            <option value="">— Choose a school —</option>
                            @forelse($unregisteredSchools ?? [] as $school)
                                @php
                                    $code = $school->school_id_number ?: $school->school_id ?: '';
                                    $loc = $school->evacuation_location ?: $school->address ?: '';
                                @endphp
                                <option
                                    value="{{ $school->id }}"
                                    data-identification="{{ e($code) }}"
                                    data-school-name="{{ e($school->school_name) }}"
                                    data-location="{{ e($loc) }}"
                                >
                                    {{ $school->school_name }} @if($code)({{ $code }})@endif
                                </option>
                            @empty
                                <option value="" disabled>No schools available — add schools on the main dashboard first, or all are already registered.</option>
                            @endforelse
                        </select>
                    </div>

                    <div id="cec_locked_panel" class="border rounded-3 p-4 bg-light" style="display: none;">
                        <h6 class="fw-bold text-secondary text-uppercase small mb-3">School information (read-only)</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted">Identification (School ID / Code)</label>
                                <input type="text" id="cec_display_identification" class="form-control" readonly tabindex="-1">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-bold small text-muted">School Name</label>
                                <input type="text" id="cec_display_school_name" class="form-control" readonly tabindex="-1">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted">Location</label>
                                <textarea id="cec_display_location" class="form-control" rows="2" readonly tabindex="-1"></textarea>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Capacity (Max Individuals) *</label>
                            <input type="number" name="capacity" class="form-control" placeholder="e.g. 500" min="0" value="0" required>
                            <small class="text-muted">0 = unlimited / unknown.</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Initial Usage Status *</label>
                            <select name="usage_status" class="form-select" required>
                                <option value="cleared">Cleared</option>
                                <option value="occupied">Occupied</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Emergency Resources (optional)</label>
                            <textarea name="emergency_resources" rows="2" class="form-control" placeholder="e.g. cots, hygiene kits, generator…"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background-color: #0a192f; color: #00d2ff; border: 1px solid #00d2ff;" @if(($unregisteredSchools ?? collect())->isEmpty()) disabled @endif>
                        Register center
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('cec_existing_school_id');
        const panel = document.getElementById('cec_locked_panel');
        const ident = document.getElementById('cec_display_identification');
        const nameEl = document.getElementById('cec_display_school_name');
        const loc = document.getElementById('cec_display_location');
        if (!select || !panel) return;

        function refreshLocked() {
            const opt = select.options[select.selectedIndex];
            if (!opt || !opt.value) {
                panel.style.display = 'none';
                return;
            }
            panel.style.display = 'block';
            ident.value = opt.dataset.identification || '';
            nameEl.value = opt.dataset.schoolName || '';
            loc.value = opt.dataset.location || '';
        }
        select.addEventListener('change', refreshLocked);
        refreshLocked();
    });
</script>
