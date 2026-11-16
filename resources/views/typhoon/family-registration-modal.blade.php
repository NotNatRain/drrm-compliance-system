{{-- Family Registration Modal --}}
<div class="modal fade" id="familyRegistrationModal" tabindex="-1" aria-labelledby="familyRegistrationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form method="POST" action="{{ route('typhoon.families.store') }}" id="familyRegistrationForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--bg-dark); color: var(--accent-blue); border-bottom: 1px solid var(--glass-border);">
                    <h5 class="modal-title" id="familyRegistrationModalLabel">
                        <i class="fas fa-people-arrows me-2"></i> Register Family Evacuee
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">

                    {{-- Registration Mode --}}
                    @if(isset($hidden_ec_id))
                        <input type="hidden" name="evacuation_center_id" value="{{ $hidden_ec_id }}">
                    @endif
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Family Encoding Mode <span class="text-danger">*</span></label>
                            <select name="registration_mode" id="familyRegistrationMode" class="form-select" required>
                                <option value="new" selected>Encode new family</option>
                                <option value="existing">Register existing</option>
                            </select>
                            <input type="hidden" name="existing_family_id" id="existingFamilyId" value="">
                        </div>
                        <div class="col-md-6 d-none" id="existingFamilySelectorWrap">
                            <label class="form-label small fw-bold">Registered Family in This Center</label>
                            <select id="existingFamilySelect" class="form-select">
                                <option value="">-- Select existing family --</option>
                            </select>
                            <small class="text-muted">Only families previously registered in the selected evacuation center are listed.</small>
                        </div>
                    </div>

                    {{-- ── SECTION 1: HEAD OF FAMILY ── --}}
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-header fw-bold small text-uppercase" style="background: #eaf0fb; color: #1B4C6D;">
                            <i class="fas fa-user-tie me-2"></i> Head of Family Details
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                {{-- Name --}}
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small fw-bold">Full Name (Head) <span class="text-danger">*</span></label>
                                    <input type="text" name="head_family_name" id="input_head_name" class="form-control" placeholder="Full name of head" required
                                        oninput="document.getElementById('hidden_head_name').value = this.value">
                                    <input type="hidden" name="members[0][full_name]" id="hidden_head_name">
                                    <input type="hidden" name="members[0][is_head]" value="1">
                                </div>
                                {{-- Age --}}
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small fw-bold">Age <span class="text-danger">*</span></label>
                                    <input type="number" name="members[0][age]" class="form-control" placeholder="Age" required min="0" max="150">
                                </div>
                                {{-- Gender --}}
                                <div class="col-md-3 mb-2">
                                    <label class="form-label small fw-bold">Gender <span class="text-danger">*</span></label>
                                    <select name="members[0][gender]" class="form-select" required>
                                        <option value="">Select...</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                                {{-- Contact Number --}}
                                <div class="col-md-4 mb-2">
                                    <label class="form-label small fw-bold">Contact Number</label>
                                    <input type="text" name="contact_number" class="form-control" placeholder="e.g. 09XXXXXXXXX">
                                </div>
                                {{-- Street --}}
                                <div class="col-md-8 mb-2">
                                    <label class="form-label small fw-bold">Street / Purok</label>
                                    <input type="text" name="street" class="form-control" placeholder="Street or Purok name">
                                </div>
                                {{-- Barangay --}}
                                <div class="col-md-5 mb-2">
                                    <label class="form-label small fw-bold">Barangay</label>
                                    <input type="text" name="barangay" class="form-control" placeholder="Barangay">
                                </div>
                                {{-- City --}}
                                <div class="col-md-7 mb-2">
                                    <label class="form-label small fw-bold">City / Municipality</label>
                                    <input type="text" name="city" class="form-control" placeholder="City or Municipality">
                                </div>
                            </div>

                            {{-- Vulnerabilities --}}
                            <div class="vulnerability-wrapper mt-3">
                                <div class="p-3 rounded border" style="background: #f8f9fa;">
                                    <label class="form-label fw-bold small mb-2">Vulnerabilities / Special Concerns</label>
                                    <select class="form-select form-select-sm mb-2 vulnerability-selector" id="headVulnerabilitySelector">
                                        <option value="">-- Add Concern --</option>
                                        <option value="flagPregnant">Pregnant</option>
                                        <option value="flagPwd">PWD</option>
                                        <option value="flagSenior">Senior Citizen</option>
                                        <option value="flagLactating">Lactating</option>
                                        <option value="flagChild">Child Under 5</option>
                                        <option value="flagOtherNeeds">Other / Special Needs</option>
                                    </select>
                                    <div class="vulnerability-tags-container d-flex flex-wrap gap-2 mb-2"></div>
                                    <div id="otherNeedsDetailsWrap" class="d-none">
                                        <input type="text" name="other_needs_details" class="form-control form-control-sm" placeholder="Describe the special need..." maxlength="500">
                                    </div>
                                    <div class="d-none">
                                        <input class="vulnerability-checkbox flagPregnant" type="checkbox" name="has_pregnant" value="1" id="flagPregnant">
                                        <input class="vulnerability-checkbox flagPwd" type="checkbox" name="has_pwd" value="1" id="flagPwd">
                                        <input class="vulnerability-checkbox flagSenior" type="checkbox" name="has_senior" value="1" id="flagSenior">
                                        <input class="vulnerability-checkbox flagLactating" type="checkbox" name="has_lactating" value="1" id="flagLactating">
                                        <input class="vulnerability-checkbox flagChild" type="checkbox" name="has_child_under5" value="1" id="flagChild">
                                        <input class="vulnerability-checkbox flagOtherNeeds" type="checkbox" name="has_other_needs" value="1" id="flagOtherNeeds">
                                    </div>
                                </div>
                            </div>

                            {{-- Collective Needs --}}
                            <div class="mt-3">
                                <label class="form-label small fw-bold">Collective Family Needs <span class="text-danger">*</span></label>
                                <div class="family-needs-builder" data-family-needs-builder="create" data-need-options='@json($familyNeedOptions ?? [])' data-existing-needs='[]'></div>
                                <small class="text-muted d-block mt-1">Choose a need and quantity. Selecting <strong>Others Please Specify</strong> will reveal a custom need field.</small>
                            </div>
                        </div>
                    </div>

                    {{-- ── SECTION 2: OTHER FAMILY MEMBERS ── --}}
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center fw-bold small text-uppercase" style="background: #eaf0fb; color: #1B4C6D;">
                            <span><i class="fas fa-users me-2"></i> Other Family Members</span>
                            <button type="button" class="btn btn-sm btn-primary" id="add-member-btn" style="font-size:0.75rem;">
                                <i class="fas fa-plus me-1"></i> Add Member
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="family-members-container"></div>
                            <div id="no-members-hint" class="text-muted small text-center py-2">
                                <i class="fas fa-info-circle me-1"></i> No additional members added. Click "Add Member" to include family members.
                            </div>
                        </div>
                    </div>

                    {{-- ── SECTION 3: PERSONAL BELONGINGS ── --}}
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center fw-bold small text-uppercase" style="background: #eaf0fb; color: #1B4C6D;">
                            <span><i class="fas fa-briefcase me-2"></i> Personal Belongings</span>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="add-belonging-btn" style="font-size:0.75rem;">
                                <i class="fas fa-plus me-1"></i> Add Item
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="belongings-container"></div>
                            <div id="no-belongings-hint" class="text-muted small text-center py-2">
                                <i class="fas fa-box-open me-1"></i> No items added yet. Click "Add Item" to list belongings.
                            </div>
                        </div>
                    </div>

                    {{-- ── SECTION 4: PERSONAL PETS ── --}}
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center fw-bold small text-uppercase" style="background: #eaf0fb; color: #1B4C6D;">
                            <span><i class="fas fa-paw me-2"></i> Personal Pets</span>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="add-pet-btn" style="font-size:0.75rem;">
                                <i class="fas fa-plus me-1"></i> Add Pet
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="pets-container"></div>
                            <div id="no-pets-hint" class="text-muted small text-center py-2">
                                <i class="fas fa-paw me-1"></i> No pets added yet. Click "Add Pet" to list pets.
                            </div>
                        </div>
                    </div>

                    {{-- ── SECTION 5: REGISTRANT & CHECK-IN ── --}}
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-header fw-bold small text-uppercase" style="background: #eaf0fb; color: #1B4C6D;">
                            <i class="fas fa-clipboard-check me-2"></i> Registration Info
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                {{-- Registrant (read-only) --}}
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Registered By</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly style="background: #f1f3f5; cursor: not-allowed;">
                                    <small class="text-muted">Automatically assigned to the currently logged-in user.</small>
                                </div>
                                {{-- Check-in --}}
                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="form-check mt-3">
                                        <input class="form-check-input" type="checkbox" name="confirm_check_in" id="confirmCheckIn" checked>
                                        <label class="form-check-label" for="confirmCheckIn">
                                            <strong>Check-in this family now</strong><br>
                                            <small class="text-muted">Sets current date/time as check-in.</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Evacuation Center Dropdown (hidden data) --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Evacuation Center / School <span class="text-danger">*</span></label>
                        <div id="lockedCenterHint" class="small text-primary mb-1 d-none">
                            <i class="fas fa-lock me-1"></i> Locked to selected evacuation center.
                        </div>
                        <select name="evacuation_center_id" id="modal_evacuation_center_id" class="form-select" required>
                            <option value="">-- Select Evacuation Center --</option>
                            @foreach($evacuationCenters ?? [] as $ec)
                                <option value="{{ $ec->id }}">
                                    {{ $ec->school_name ?? $ec->identification ?? ('Evacuation Center #' . $ec->id) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>{{-- /modal-body --}}

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background-color: #1B4C6D; color: white;">
                        <i class="fas fa-save me-1"></i> Register Family
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ── Common Belongings & Pets Data (for JS) ── --}}
<script>
    const COMMON_BELONGINGS = [
        'Clothing / Clothes', 'Blanket', 'Pillow', 'Towel', 'Toiletries',
        'Food / Groceries', 'Water Containers', 'Medicine / First Aid Kit',
        'Mobile Phone / Charger', 'Flashlight / Batteries', 'Important Documents',
        'Cash / Valuables', 'Baby Items / Diapers', 'Cooking Utensils',
        'Mattress / Sleeping Mat', 'Backpack / Bag'
    ];

    const COMMON_PETS = [
        'Dog', 'Cat', 'Chicken / Poultry', 'Pig', 'Goat', 'Rabbit',
        'Bird / Pigeon', 'Fish (in container)', 'Cow / Carabao', 'Horse'
    ];

    const FAMILY_ROLES = [
        'Father', 'Mother', 'Son', 'Daughter', 'Grandfather', 'Grandmother',
        'Uncle', 'Aunt', 'Nephew', 'Niece', 'Cousin', 'Relative', 'Guardian',
        'Family Friend'
    ];

    // ── Belongings Builder ──
    let belongingIndex = 0;

    function buildSelectOptions(optionsList, selectedVal = '') {
        let html = '<option value="">-- Select --</option>';
        optionsList.forEach(opt => {
            html += `<option value="${opt}" ${opt === selectedVal ? 'selected' : ''}>${opt}</option>`;
        });
        html += `<option value="Others" ${selectedVal === 'Others' ? 'selected' : ''}>Others (specify)</option>`;
        return html;
    }

    function addBelongingRow(item = {}) {
        const container = document.getElementById('belongings-container');
        const hint = document.getElementById('no-belongings-hint');
        if (hint) hint.classList.add('d-none');

        const idx = belongingIndex++;
        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 align-items-end belonging-row';

        const selectedName = item.name || '';
        const isOther = selectedName && !COMMON_BELONGINGS.includes(selectedName);
        const qty = item.qty || 1;

        row.innerHTML = `
            <div class="col-md-5">
                <label class="form-label small fw-bold">Item</label>
                <select class="form-select belonging-select" name="belongings[${idx}][name]">
                    ${buildSelectOptions(COMMON_BELONGINGS, isOther ? 'Others' : selectedName)}
                </select>
            </div>
            <div class="col-md-4 belonging-other-wrap ${isOther ? '' : 'd-none'}">
                <label class="form-label small fw-bold">Specify</label>
                <input type="text" class="form-control belonging-other-input" placeholder="Specify item..." value="${isOther ? selectedName : ''}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Qty</label>
                <input type="number" class="form-control" name="belongings[${idx}][qty]" value="${qty}" min="1" max="999">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-outline-danger btn-sm remove-belonging w-100"><i class="fas fa-trash"></i></button>
            </div>
        `;

        const select = row.querySelector('.belonging-select');
        const otherWrap = row.querySelector('.belonging-other-wrap');
        const otherInput = row.querySelector('.belonging-other-input');

        select.addEventListener('change', function () {
            const isOtherVal = this.value === 'Others';
            otherWrap.classList.toggle('d-none', !isOtherVal);
            // Sync name field
            if (!isOtherVal) {
                otherInput.value = '';
                this.name = `belongings[${idx}][name]`;
            } else {
                this.name = ''; // let other input carry the value
                otherInput.name = `belongings[${idx}][name]`;
            }
        });

        // Init: if it's Others, wire up properly
        if (isOther) {
            select.name = '';
            otherInput.name = `belongings[${idx}][name]`;
        }

        row.querySelector('.remove-belonging').addEventListener('click', function () {
            row.remove();
            checkBelongingsHint();
        });

        container.appendChild(row);
    }

    function checkBelongingsHint() {
        const container = document.getElementById('belongings-container');
        const hint = document.getElementById('no-belongings-hint');
        if (hint) hint.classList.toggle('d-none', container.children.length > 0);
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('add-belonging-btn')?.addEventListener('click', function () {
            addBelongingRow();
        });
    });

    // ── Pets Builder ──
    let petIndex = 0;

    function addPetRow(item = {}) {
        const container = document.getElementById('pets-container');
        const hint = document.getElementById('no-pets-hint');
        if (hint) hint.classList.add('d-none');

        const idx = petIndex++;
        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 align-items-end pet-row';

        const selectedName = item.name || '';
        const isOther = selectedName && !COMMON_PETS.includes(selectedName);
        const qty = item.qty || 1;

        row.innerHTML = `
            <div class="col-md-5">
                <label class="form-label small fw-bold">Pet Type</label>
                <select class="form-select pet-select" name="pets[${idx}][name]">
                    ${buildSelectOptions(COMMON_PETS, isOther ? 'Others' : selectedName)}
                </select>
            </div>
            <div class="col-md-4 pet-other-wrap ${isOther ? '' : 'd-none'}">
                <label class="form-label small fw-bold">Specify</label>
                <input type="text" class="form-control pet-other-input" placeholder="Specify pet type..." value="${isOther ? selectedName : ''}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Qty</label>
                <input type="number" class="form-control" name="pets[${idx}][qty]" value="${qty}" min="1" max="999">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-outline-danger btn-sm remove-pet w-100"><i class="fas fa-trash"></i></button>
            </div>
        `;

        const select = row.querySelector('.pet-select');
        const otherWrap = row.querySelector('.pet-other-wrap');
        const otherInput = row.querySelector('.pet-other-input');

        select.addEventListener('change', function () {
            const isOtherVal = this.value === 'Others';
            otherWrap.classList.toggle('d-none', !isOtherVal);
            if (!isOtherVal) {
                otherInput.value = '';
                this.name = `pets[${idx}][name]`;
            } else {
                this.name = '';
                otherInput.name = `pets[${idx}][name]`;
            }
        });

        if (isOther) {
            select.name = '';
            otherInput.name = `pets[${idx}][name]`;
        }

        row.querySelector('.remove-pet').addEventListener('click', function () {
            row.remove();
            checkPetsHint();
        });

        container.appendChild(row);
    }

    function checkPetsHint() {
        const container = document.getElementById('pets-container');
        const hint = document.getElementById('no-pets-hint');
        if (hint) hint.classList.toggle('d-none', container.children.length > 0);
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('add-pet-btn')?.addEventListener('click', function () {
            addPetRow();
        });
    });

    // ── Other Needs Vulnerability Toggle ──
    document.addEventListener('change', function (e) {
        if (e.target && e.target.classList.contains('vulnerability-selector')) {
            if (e.target.value === 'flagOtherNeeds') {
                const wrap = document.getElementById('otherNeedsDetailsWrap');
                if (wrap) wrap.classList.remove('d-none');
            }
        }
    });

    // Reset belongings/pets when modal is reset
    function resetBelongingsAndPets() {
        const bc = document.getElementById('belongings-container');
        const pc = document.getElementById('pets-container');
        if (bc) bc.innerHTML = '';
        if (pc) pc.innerHTML = '';
        checkBelongingsHint();
        checkPetsHint();
        belongingIndex = 0;
        petIndex = 0;
        const otherWrap = document.getElementById('otherNeedsDetailsWrap');
        if (otherWrap) otherWrap.classList.add('d-none');
    }
</script>