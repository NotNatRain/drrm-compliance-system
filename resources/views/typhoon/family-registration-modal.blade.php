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
                
                <div class="modal-body">

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
                                @foreach(($existingFamilies ?? $families ?? []) as $family)
                                    <option value="{{ $family->id ?? $family['id'] ?? '' }}">
                                        {{ $family->head_family_name ?? $family->family_name ?? $family->head_name ?? ($family['head_family_name'] ?? $family['family_name'] ?? $family['head_name'] ?? 'Family') }}
                                        @if(!empty($family->evacuation_center_id ?? $family['evacuation_center_id'] ?? null))
                                            — Center #{{ $family->evacuation_center_id ?? $family['evacuation_center_id'] }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Families registered in any evacuation center are available.</small>
                        </div>
                    </div>

                    {{-- Family-level fields --}}
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h6 class="fw-bold text-primary"><i class="fas fa-user-tie"></i> Head of Family Details</h6>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-bold">Full Name (Head) <span class="text-danger">*</span></label>
                            <input type="text" name="head_family_name" id="input_head_name" class="form-control" placeholder="Full name of head" required
                                oninput="document.getElementById('hidden_head_name').value = this.value">
                            <input type="hidden" name="members[0][full_name]" id="hidden_head_name">
                            <input type="hidden" name="members[0][is_head]" value="1">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label small fw-bold">Age <span class="text-danger">*</span></label>
                            <input type="number" name="members[0][age]" class="form-control" placeholder="Age" required min="0" max="120">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label small fw-bold">Gender <span class="text-danger">*</span></label>
                            <select name="members[0][gender]" class="form-select" required>
                                <option value="">Select...</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label small fw-bold">Contact Number</label>
                            <input type="text" name="contact_number" class="form-control" placeholder="e.g. 09XXXXXXXXX">
                        </div>
                        <div class="col-md-8 mb-2">
                            <label class="form-label small fw-bold">Street / Purok</label>
                            <input type="text" name="street" class="form-control" placeholder="Street or Purok name">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label small fw-bold">Barangay</label>
                            <select name="barangay" id="familyBarangay" class="form-select">
                                <option value="">-- Select Barangay --</option>
                                <option value="Asinan">Asinan</option>
                                <option value="Banicain">Banicain</option>
                                <option value="Barretto">Barretto</option>
                                <option value="East Bajac-Bajac">East Bajac-Bajac</option>
                                <option value="East Tapinac">East Tapinac</option>
                                <option value="Gordon Heights">Gordon Heights</option>
                                <option value="Kalaklan">Kalaklan</option>
                                <option value="Mabayuan">Mabayuan</option>
                                <option value="New Cabalan">New Cabalan</option>
                                <option value="New Ilalim">New Ilalim</option>
                                <option value="New Kababae">New Kababae</option>
                                <option value="New Kalalake">New Kalalake</option>
                                <option value="Old Cabalan">Old Cabalan</option>
                                <option value="Pag-asa">Pag-asa</option>
                                <option value="Santa Rita">Santa Rita</option>
                                <option value="West Bajac-Bajac">West Bajac-Bajac</option>
                                <option value="West Tapinac">West Tapinac</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label small fw-bold">Family Serial Number</label>
                            <input type="text" name="serial_number" id="familySerialNumber" class="form-control" placeholder="Generated from barangay" readonly>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label small fw-bold">City / Municipality</label>
                            <input type="text" name="city" class="form-control" placeholder="City or Municipality">
                        </div>
                        <!-- Head of Family Vulnerability Tags -->
                        <div class="col-12 vulnerability-wrapper mt-2">
                            <div class="p-2 bg-light rounded border">
                                <label class="form-label fw-bold small mb-1">Head Vulnerabilities / Special Concerns</label>
                                <select class="form-select form-select-sm mb-2 vulnerability-selector">
                                    <option value="">-- Add Concern --</option>
                                    <option value="flagPregnant">Pregnant</option>
                                    <option value="flagPwd">PWD</option>
                                    <option value="flagSenior">Senior Citizen</option>
                                    <option value="flagLactating">Lactating</option>
                                    <option value="flagChild">Child Under 5</option>
                                    <option value="flagOtherNeeds">Other / Special Needs</option>
                                </select>
                                <div class="vulnerability-tags-container d-flex flex-wrap gap-2"></div>
                                <div class="d-none">
                                    <input class="form-check-input vulnerability-checkbox flagPregnant" type="checkbox" name="has_pregnant" value="1" id="flagPregnant">
                                    <input class="form-check-input vulnerability-checkbox flagPwd" type="checkbox" name="has_pwd" value="1" id="flagPwd">
                                    <input class="form-check-input vulnerability-checkbox flagSenior" type="checkbox" name="has_senior" value="1" id="flagSenior">
                                    <input class="form-check-input vulnerability-checkbox flagLactating" type="checkbox" name="has_lactating" value="1" id="flagLactating">
                                    <input class="form-check-input vulnerability-checkbox flagChild" type="checkbox" name="has_child_under5" value="1" id="flagChild">
                                    <input class="form-check-input vulnerability-checkbox flagOtherNeeds" type="checkbox" name="has_other_needs" value="1" id="flagOtherNeeds">
                                </div>
                                <div id="otherNeedsDetailsWrap" class="d-none mt-2">
                                    <input type="text" name="other_needs_details" class="form-control form-control-sm" placeholder="Describe the special need..." maxlength="500">
                                </div>
                            </div>
                        </div>
 


                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-bold mb-0">Other Family Members</label>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-member-btn">
                            <i class="fas fa-plus"></i> Add Member
                        </button>
                    </div>
                    <div id="family-members-container"></div>
                    <div id="no-members-hint" class="text-muted small text-center py-2">
                        <i class="fas fa-info-circle me-1"></i> No additional members added.
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-bold mb-0">Collective Family Needs</label>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="add-collective-need-btn"><i class="fas fa-plus me-1"></i>Add Need</button>
                    </div>
                    <div id="collective-needs-container"></div>
                    <div id="no-collective-needs-hint" class="text-muted small text-center py-2">No collective family needs added yet.</div>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-bold mb-0">Personal Belongings</label>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="add-personal-item-btn"><i class="fas fa-plus me-1"></i>Add Item</button>
                    </div>
                    <div id="personal-items-container"></div>
                    <div id="no-personal-items-hint" class="text-muted small text-center py-2">No personal belongings added yet.</div>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-bold mb-0">Pets</label>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="add-pet-btn"><i class="fas fa-plus me-1"></i>Add Pet</button>
                    </div>
                    <div id="pets-container"></div>
                    <div id="no-pets-hint" class="text-muted small text-center py-2">No pets added yet.</div>

                    <hr>

                    {{-- Evacuation Center, Building & Room Location --}}
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-header fw-bold small text-uppercase" style="background: #eaf0fb; color: #1B4C6D;">
                            <i class="fas fa-building me-2"></i> Evacuation Center & Accommodation
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- Evacuation Center Dropdown -->
                                <div class="col-md-4">
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

                                <!-- Fire Safety Building Dropdown -->
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-uppercase text-muted">Building <span class="text-danger">*</span></label>
                                    <select name="building_id" id="firesafety_buildings" class="form-select" required>
                                        <option value="">-- Select Building --</option>
                                        @foreach(($firesafetyBuildings ?? $fireSafetyBuildings ?? $buildings ?? []) as $building)
                                            @php
                                                $buildingId = data_get($building, 'id');
                                                $buildingCenterId = data_get($building, 'unified_school_id', data_get($building, 'school_id', data_get($building, 'evacuation_center_id', data_get($building, 'center_id'))));
                                                $buildingName = data_get($building, 'name', data_get($building, 'building_name', data_get($building, 'identification', 'Building')));
                                            @endphp
                                            <option value="{{ $buildingId }}" data-center-id="{{ $buildingCenterId }}">
                                                {{ $buildingName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Room Dropdown -->
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-uppercase text-muted">Room <span class="text-danger">*</span></label>
                                    <select name="room_id" id="fire_safety_rooms" class="form-select" required>
                                        <option value="">-- Select Room --</option>
                                    </select>
                                    <small class="text-muted d-block mt-1">Room labels show evacuation classification (Main / Buffer).</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Registrant and check-in information --}}
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-header fw-bold small text-uppercase" style="background: #eaf0fb; color: #1B4C6D;">
                            <i class="fas fa-clipboard-check me-2"></i> Registration Info
                        </div>
                        <div class="card-body">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Registered By</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly style="background: #f1f3f5; cursor: not-allowed;">
                                    <small class="text-muted">Automatically assigned to the currently logged-in user.</small>
                                </div>
                                <div class="col-md-6">
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
                </div>

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

<script>
(() => {
    const $ = id => document.getElementById(id);
    const members = $('family-members-container');
    let memberIndex = 1, petIndex = 0;
    const pets = ['Dog','Cat','Chicken / Poultry','Pig','Goat','Rabbit','Bird / Pigeon','Fish (in container)','Cow / Carabao','Horse'];

    const existingFamilies = @json($existingFamilies ?? $families ?? []);
    const existingFamiliesByCenter = @json($existingFamiliesByCenter ?? []);
    const familyNeedOptions = @json($familyNeedOptions ?? []);
    // Records come from the firesafety_buildings and fire_safety_rooms tables.
    const registeredRooms = @json($fireSafetyRooms ?? $rooms ?? []);
    // Use the same registered buildings shown by the Fire Safety page/modal.
    const registeredBuildings = @json(
        $firesafetyBuildings
            ?? $fireSafetyBuildings
            ?? $buildings
            ?? (isset($activeSchool) ? $activeSchool->buildings : [])
    );

    function asArray(value) {
        if (Array.isArray(value)) return value;
        if (value && typeof value === 'object') return Object.values(value);
        return [];
    }

    function valueOf(object, keys, fallback = '') {
        for (const key of keys) {
            if (object && object[key] !== undefined && object[key] !== null) return object[key];
        }
        return fallback;
    }

    function relatedId(object, keys) {
        const value = valueOf(object, keys, '');
        if (value && typeof value === 'object') return valueOf(value, ['id', 'value'], '');
        return value;
    }

    function normalizedId(value) {
        return String(value ?? '').trim();
    }

    function matchesId(value, id) {
        return normalizedId(relatedId({ value }, ['value'])) === normalizedId(id);
    }

    function options(list, selected = '') {
        return '<option value="">-- Select --</option>' + list.map(x => `<option value="${x}" ${x === selected ? 'selected' : ''}>${x}</option>`).join('') + '<option value="Others" ' + (selected === 'Others' ? 'selected' : '') + '>Others (specify)</option>';
    }

    let personalItemIndex = 0;
    function addPersonalItem(item = {}) {
        const container = $('personal-items-container');
        if (!container) return;
        const index = personalItemIndex++;
        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 align-items-end personal-item-row';
        row.innerHTML = `<div class="col-md-7"><label class="form-label small fw-bold">Item</label><input type="text" class="form-control" name="belongings[${index}][name]" value="${valueOf(item, ['name', 'item'])}" placeholder="Enter item"></div>
            <div class="col-md-3"><label class="form-label small fw-bold">Qty</label><input type="number" class="form-control" name="belongings[${index}][qty]" value="${valueOf(item, ['qty', 'quantity'], 1)}" min="1" max="999"></div>
            <div class="col-md-2"><button type="button" class="btn btn-outline-danger btn-sm remove-personal-item w-100"><i class="fas fa-trash"></i></button></div>`;
        row.querySelector('.remove-personal-item').onclick = () => {
            row.remove();
            $('no-personal-items-hint')?.classList.toggle('d-none', container.children.length > 0);
        };
        container.appendChild(row);
        $('no-personal-items-hint')?.classList.add('d-none');
    }

    function allExistingFamilies() {
        const grouped = Object.values(existingFamiliesByCenter || {}).flat();
        const source = grouped.length ? grouped : existingFamilies;
        const unique = new Map();
        (Array.isArray(source) ? source : []).forEach(family => {
            if (family?.id != null) unique.set(String(family.id), family);
        });
        return [...unique.values()];
    }

    function setField(name, value) {
        const field = document.querySelector(`[name="${name}"]`);
        if (field) field.value = value ?? '';
    }

    function updateFamilySerialNumber() {
        const barangay = $('familyBarangay')?.value.trim() || '';
        const serialField = $('familySerialNumber');
        if (!serialField) return;
        if (!barangay) {
            serialField.value = '';
            return;
        }

        const words = barangay.split(/\s+/).map(word => word.replace(/[^A-Za-z0-9]/g, '')).filter(Boolean);
        const code = (words.map(word => word[0]).join('') || barangay.replace(/[^A-Za-z0-9]/g, '').slice(0, 4) || 'BRGY').toUpperCase().slice(0, 5);
        const date = new Date().toISOString().slice(0, 10).replace(/-/g, '');
        const suffix = Math.random().toString(36).slice(2, 6).toUpperCase();
        serialField.value = `${code}-${date}-${suffix}`;
    }

    let         collectiveNeedIndex = 0;

    function addCollectiveNeed(data = {}) {
        const container = $('collective-needs-container');
        if (!container) return;
        const index = collectiveNeedIndex++;
        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 align-items-end collective-need-row';
        const needOptions = asArray(familyNeedOptions).map(need => {
            const value = valueOf(need, ['id', 'name', 'label', 'value'], need);
            const label = valueOf(need, ['name', 'label', 'value'], value);
            return `<option value="${value}">${label}</option>`;
        }).join('');
        row.innerHTML = `<div class="col-md-5"><label class="form-label small fw-bold">Need</label><select class="form-select need-select" name="needs[${index}][need_name]" required><option value="">-- Select need --</option>${needOptions}</select></div>
            <div class="col-md-2"><label class="form-label small fw-bold">Qty</label><input type="number" class="form-control" name="needs[${index}][quantity]" value="${valueOf(data, ['quantity', 'qty'], 1)}" min="1" max="999" required></div>
            <div class="col-md-3 need-custom-wrap d-none"><label class="form-label small fw-bold">Specify</label><input type="text" class="form-control" name="needs[${index}][custom_need]" value="${valueOf(data, ['custom_need'])}" maxlength="255"></div>
            <div class="col-md-2"><button type="button" class="btn btn-outline-danger btn-sm remove-collective-need w-100"><i class="fas fa-trash"></i></button></div>`;
        const select = row.querySelector('.need-select');
        const customWrap = row.querySelector('.need-custom-wrap');
        const customInput = row.querySelector(`[name="needs[${index}][custom_need]"]`);
        const savedName = valueOf(data, ['need_name', 'name']);
        select.value = savedName === 'Others Please Specify' || data.is_custom ? 'Others Please Specify' : savedName;
        const syncCustomNeed = () => {
            const isCustom = select.value === 'Others Please Specify';
            customWrap.classList.toggle('d-none', !isCustom);
            customInput.required = isCustom;
        };
        select.addEventListener('change', syncCustomNeed);
        syncCustomNeed();
        row.querySelector('.remove-collective-need').onclick = () => {
            row.remove();
            $('no-collective-needs-hint')?.classList.toggle('d-none', container.children.length > 0);
        };
        container.appendChild(row);
        $('no-collective-needs-hint')?.classList.add('d-none');
    }

    function clearDynamicRows() {
        members.innerHTML = '';
        $('collective-needs-container').innerHTML = '';
        $('personal-items-container').innerHTML = '';
        $('pets-container').innerHTML = '';
        memberIndex = 1;
        personalItemIndex = 0;
        petIndex = 0;
        collectiveNeedIndex = 0;
        $('no-members-hint').classList.remove('d-none');
        $('no-collective-needs-hint').classList.remove('d-none');
        $('no-personal-items-hint').classList.remove('d-none');
        $('no-pets-hint').classList.remove('d-none');
    }

    function fillExistingFamily(family) {
        if (!family) return;

        // Prefer the latest saved registration when the controller provides history.
        const latest = valueOf(family, ['latest_registration', 'latestRegistration', 'latest'], family);
        const head = valueOf(latest, ['head', 'head_member', 'headMember'], {});
        const membersData = valueOf(latest, ['members', 'family_members'], valueOf(family, ['members', 'family_members'], []));
        const headData = typeof head === 'object' ? head : {};
        const headName = valueOf(latest, ['head_family_name', 'family_name', 'head_name'], valueOf(headData, ['full_name', 'name'], valueOf(family, ['head_family_name', 'family_name', 'head_name'])));

        setField('head_family_name', headName);
        setField('members[0][full_name]', headName);
        setField('members[0][age]', valueOf(headData, ['age'], valueOf(latest, ['head_age'], valueOf(family, ['head_age']))));
        setField('members[0][gender]', valueOf(headData, ['gender'], valueOf(latest, ['head_gender'], valueOf(family, ['head_gender']))));
        ['contact_number', 'street', 'barangay', 'city', 'other_needs_details'].forEach(field => {
            setField(field, valueOf(latest, [field], valueOf(family, [field])));
        });
        updateFamilySerialNumber();

        clearDynamicRows();
        (Array.isArray(membersData) ? membersData : [])
            .filter(member => !Boolean(Number(valueOf(member, ['is_head'], 0))))
            .forEach(addMember);
        (valueOf(latest, ['belongings', 'family_belongings'], valueOf(family, ['belongings', 'family_belongings'], [])) || [])
            .forEach(item => addPersonalItem(item));
        (valueOf(latest, ['pets', 'family_pets'], valueOf(family, ['pets', 'family_pets'], [])) || [])
            .forEach(item => addAsset('pets', item));

        ['pregnant', 'pwd', 'senior', 'lactating', 'child_under5', 'other_needs'].forEach(flag => {
            const checkbox = document.querySelector(`[name="has_${flag}"]`);
            if (checkbox) checkbox.checked = Boolean(valueOf(latest, [`has_${flag}`, flag], valueOf(family, [`has_${flag}`, flag], false)));
        });
        const otherWrap = $('otherNeedsDetailsWrap');
        if (otherWrap) otherWrap.classList.toggle('d-none', !$('[name="has_other_needs"]')?.checked);
    }

    const memberVulnerabilities = [
        ['flagPregnant', 'Pregnant', 'has_pregnant'],
        ['flagPwd', 'PWD', 'has_pwd'],
        ['flagSenior', 'Senior Citizen', 'has_senior'],
        ['flagLactating', 'Lactating', 'has_lactating'],
        ['flagChild', 'Child Under 5', 'has_child_under5'],
        ['flagOtherNeeds', 'Other / Special Needs', 'has_other_needs']
    ];

    function addMemberVulnerability(row, index, flagClass, label) {
        const checkbox = row.querySelector(`.member-${flagClass}`);
        if (!checkbox || checkbox.checked) return;
        checkbox.checked = true;

        const tag = document.createElement('span');
        tag.className = 'badge bg-primary d-inline-flex align-items-center gap-1';
        tag.textContent = label;

        const remove = document.createElement('button');
        remove.type = 'button';
        remove.className = 'btn-close btn-close-white p-0 border-0';
        remove.style.fontSize = '0.55rem';
        remove.setAttribute('aria-label', `Remove ${label}`);
        remove.onclick = () => {
            checkbox.checked = false;
            tag.remove();
            if (flagClass === 'flagOtherNeeds') {
                row.querySelector('.member-other-needs-wrap')?.classList.add('d-none');
                const details = row.querySelector(`[name="members[${index}][other_needs_details]"]`);
                if (details) details.value = '';
            }
        };
        tag.appendChild(remove);
        row.querySelector('.member-vulnerability-tags')?.appendChild(tag);
        if (flagClass === 'flagOtherNeeds') row.querySelector('.member-other-needs-wrap')?.classList.remove('d-none');
    }

    function addMember(data = {}) {
        const i = memberIndex++;
        const row = document.createElement('div');
        row.className = 'row g-2 mb-3 align-items-end member-row border-bottom pb-3';
        const vulnerabilityOptions = memberVulnerabilities.map(([flag, label]) => `<option value="${flag}">${label}</option>`).join('');
        const vulnerabilityInputs = memberVulnerabilities.map(([flag, , name]) => `<input class="d-none member-${flag}" type="checkbox" name="members[${i}][${name}]" value="1">`).join('');
        row.innerHTML = `<div class="col-md-4"><label class="form-label small fw-bold">Full Name</label><input class="form-control" name="members[${i}][full_name]" value="${valueOf(data, ['full_name', 'name'])}" required></div>
            <div class="col-md-2"><label class="form-label small fw-bold">Age</label><input type="number" class="form-control" name="members[${i}][age]" value="${valueOf(data, ['age'])}" min="0" max="120" required></div>
            <div class="col-md-2"><label class="form-label small fw-bold">Gender</label><select class="form-select" name="members[${i}][gender]" required><option value="">Select...</option><option value="male" ${valueOf(data, ['gender']) === 'male' ? 'selected' : ''}>Male</option><option value="female" ${valueOf(data, ['gender']) === 'female' ? 'selected' : ''}>Female</option></select></div>
            <div class="col-md-3"><label class="form-label small fw-bold">Family Role</label><input class="form-control" name="members[${i}][family_role]" value="${valueOf(data, ['family_role', 'role'])}" placeholder="e.g. Daughter"></div>
            <div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="fas fa-trash"></i></button></div>
            <div class="col-12 mt-2"><label class="form-label small fw-bold mb-1">Vulnerabilities / Special Concerns</label><select class="form-select form-select-sm member-vulnerability-selector"><option value="">-- Add Concern --</option>${vulnerabilityOptions}</select><div class="member-vulnerability-tags d-flex flex-wrap gap-2 mt-2"></div><div class="member-other-needs-wrap d-none mt-2"><input type="text" class="form-control form-control-sm" name="members[${i}][other_needs_details]" value="${valueOf(data, ['other_needs_details'])}" placeholder="Describe the special need..." maxlength="500"></div><div>${vulnerabilityInputs}</div></div>
            <input type="hidden" name="members[${i}][is_head]" value="0">`;

        const selector = row.querySelector('.member-vulnerability-selector');
        selector.onchange = () => {
            const selected = memberVulnerabilities.find(([flag]) => flag === selector.value);
            if (selected) addMemberVulnerability(row, i, selected[0], selected[1]);
            selector.value = '';
        };
        memberVulnerabilities.forEach(([flag, label, name]) => {
            if (Boolean(valueOf(data, [`has_${name.replace('has_', '')}`, name, flag], false))) {
                addMemberVulnerability(row, i, flag, label);
            }
        });
        row.querySelector('.remove-row').onclick = () => { row.remove(); $('no-members-hint').classList.toggle('d-none', members.children.length > 0); };
        members.appendChild(row); $('no-members-hint').classList.add('d-none');
    }
    function addAsset(type, data = {}) {
        const container = $(type + '-container'), hint = $('no-' + type + '-hint'), i = petIndex++;
        const list = pets, field = 'pets';
        const row = document.createElement('div'); row.className = `row g-2 mb-2 ${type.slice(0,-1)}-row align-items-end`;
        const selectedName = valueOf(data, ['name', 'item', 'pet_type']);
        const selectedQty = valueOf(data, ['qty', 'quantity'], 1);
        const isOther = selectedName && !list.includes(selectedName);
        row.innerHTML = `<div class="col-md-5"><label class="form-label small fw-bold">${type === 'pets' ? 'Pet Type' : 'Item'}</label><select class="form-select asset-select" name="${field}[${i}][name]">${options(list, isOther ? 'Others' : selectedName)}</select></div>
            <div class="col-md-4 ${isOther ? '' : 'd-none'} other-wrap"><label class="form-label small fw-bold">Specify</label><input class="form-control other-input" value="${isOther ? selectedName : ''}"></div>
            <div class="col-md-2"><label class="form-label small fw-bold">Qty</label><input type="number" class="form-control" name="${field}[${i}][qty]" value="${selectedQty}" min="1" max="999"></div>
            <div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="fas fa-trash"></i></button></div>`;
        const select = row.querySelector('.asset-select'), wrap = row.querySelector('.other-wrap'), input = row.querySelector('.other-input');
        const syncAssetName = () => { const other = select.value === 'Others'; wrap.classList.toggle('d-none', !other); input.name = other ? `${field}[${i}][name]` : ''; if (other) select.removeAttribute('name'); else select.name = `${field}[${i}][name]`; };
        select.onchange = syncAssetName;
        syncAssetName();
        row.querySelector('.remove-row').onclick = () => { row.remove(); hint.classList.toggle('d-none', container.children.length > 0); };
        container.appendChild(row); hint.classList.add('d-none');
    }
    function initFamilyModal() {
        const modal = $('familyRegistrationModal');
        if (!modal || modal.dataset.familyModalInitialized === 'true') return;
        modal.dataset.familyModalInitialized = 'true';

        const bindSingleAddClick = (id, handler) => {
            const originalButton = $(id);
            if (!originalButton || originalButton.dataset.singleAddBound === 'true') return;

            // Replace the button to remove any listener left by an older modal script.
            const button = originalButton.cloneNode(true);
            button.dataset.singleAddBound = 'true';
            originalButton.replaceWith(button);

            button.addEventListener('click', event => {
                event.preventDefault();
                event.stopImmediatePropagation();
                handler();
            });
        };

        bindSingleAddClick('add-member-btn', addMember);
        bindSingleAddClick('add-collective-need-btn', addCollectiveNeed);
        bindSingleAddClick('add-personal-item-btn', addPersonalItem);
        bindSingleAddClick('add-pet-btn', () => addAsset('pets'));
        $('input_head_name')?.addEventListener('input', e => $('hidden_head_name').value = e.target.value);
        $('familyBarangay')?.addEventListener('change', updateFamilySerialNumber);
        document.querySelector('.vulnerability-selector')?.addEventListener('change', e => {
            if (!e.target.value) return;

            const flagClass = e.target.value;
            const checkbox = document.querySelector('.' + flagClass);
            if (checkbox && !checkbox.checked) {
                checkbox.checked = true;

                const tag = document.createElement('span');
                tag.className = 'badge bg-primary d-inline-flex align-items-center gap-1';
                tag.dataset.flagClass = flagClass;
                tag.append(document.createTextNode(e.target.options[e.target.selectedIndex].text));

                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'btn-close btn-close-white p-0 border-0';
                removeButton.style.fontSize = '0.55rem';
                removeButton.setAttribute('aria-label', 'Remove vulnerability');
                removeButton.title = 'Remove vulnerability';
                removeButton.addEventListener('click', () => {
                    checkbox.checked = false;
                    tag.remove();

                    if (flagClass === 'flagOtherNeeds') {
                        $('otherNeedsDetailsWrap')?.classList.add('d-none');
                        const details = document.querySelector('[name="other_needs_details"]');
                        if (details) details.value = '';
                    }
                });

                tag.appendChild(removeButton);
                $('otherNeedsDetailsWrap')?.classList.toggle('d-none', flagClass !== 'flagOtherNeeds');
                $('otherNeedsDetailsWrap')?.parentElement.querySelector('.vulnerability-tags-container').appendChild(tag);
            }
            e.target.value = '';
        });
        $('familyRegistrationMode')?.addEventListener('change', e => {
            const existing = e.target.value === 'existing';
            $('existingFamilySelectorWrap').classList.toggle('d-none', !existing);
            if (!existing) {
                $('existingFamilyId').value = '';
                clearDynamicRows();
            }
        });
        function refreshExistingChoices() {
            const select = $('existingFamilySelect');
            if (!select) return;
            const selected = select.value;
            select.innerHTML = '<option value="">-- Select existing family --</option>';
            allExistingFamilies().forEach(family => {
                const name = valueOf(family, ['head_family_name', 'family_name', 'head_name'], 'Family');
                const center = valueOf(family, ['evacuation_center_id', 'center_id'], '');
                select.add(new Option(`#${family.id} - ${name}${center ? ` — Center #${center}` : ''}`, family.id));
            });
            if (selected) select.value = selected;
        }

        $('existingFamilySelect')?.addEventListener('change', e => {
            $('existingFamilyId').value = e.target.value;
            const family = allExistingFamilies().find(item => String(valueOf(item, ['id'])) === String(e.target.value));
            fillExistingFamily(family);
        });

        function resetRoomOptions(message = '-- Select Room --') {
            const roomSelect = $('fire_safety_rooms');
            if (roomSelect) roomSelect.innerHTML = `<option value="">${message}</option>`;
        }

        function resetBuildingOptions(message = '-- Select Building --') {
            const buildingSelect = $('firesafety_buildings');
            if (buildingSelect) buildingSelect.innerHTML = `<option value="">${message}</option>`;
        }

        function isTruthy(value) {
            return value === true || value === 1 || value === '1' || String(value).toLowerCase() === 'true';
        }

        async function filterBuildingsByCenter() {
            const centerId = $('modal_evacuation_center_id')?.value || '';
            const buildingSelect = $('firesafety_buildings');
            if (!buildingSelect) return;

            resetBuildingOptions();
            resetRoomOptions();
            if (!centerId) return;

            let buildings = asArray(registeredBuildings)
                .filter(building => {
                    const buildingCenter = relatedId(building, [
                        'unified_school_id', 'school_id', 'school',
                        'evacuation_center_id', 'center_id', 'evac_center_id',
                        'evacuation_center_id_fk', 'evacuation_center'
                    ]);
                    return normalizedId(buildingCenter) === normalizedId(centerId);
                });

            if (!buildings.length) {
                try {
                    const res = await fetch(`/fire-safety/buildings/${centerId}`);
                    if (res.ok) {
                        const fetched = await res.json();
                        if (Array.isArray(fetched) && fetched.length > 0) {
                            buildings = fetched;
                            fetched.forEach(b => registeredBuildings.push(b));
                        }
                    }
                } catch (e) {
                    console.warn('Buildings fetch error:', e);
                }
            }

            buildings.forEach(building => {
                const id = valueOf(building, ['id']);
                if (!id) return;
                const number = valueOf(building, ['building_no', 'building_number', 'code'], '');
                const name = valueOf(building, ['building_name', 'name', 'identification'], 'Building');
                buildingSelect.add(new Option(`${number ? number + ' - ' : ''}${name}`, id));
            });

            if (!buildings.length) {
                buildingSelect.innerHTML = '<option value="">No registered buildings in this center</option>';
            } else if (buildings.length === 1) {
                buildingSelect.selectedIndex = 1;
                populateRooms(buildingSelect.value);
            }
        }

        async function populateRooms(buildingId) {
            const roomSelect = $('fire_safety_rooms');
            if (!roomSelect) return;
            resetRoomOptions();
            if (!buildingId) return;

            let matchingRooms = asArray(registeredRooms).filter(room => {
                const roomBuilding = relatedId(room, [
                    'building_id', 'firesafety_building_id', 'fire_safety_building_id',
                    'building', 'firesafety_building', 'fire_safety_building'
                ]);
                return normalizedId(roomBuilding) === normalizedId(buildingId);
            });

            if (!matchingRooms.length) {
                try {
                    const res = await fetch(`/fire-safety/rooms/${buildingId}`);
                    if (res.ok) {
                        const fetched = await res.json();
                        if (Array.isArray(fetched) && fetched.length > 0) {
                            matchingRooms = fetched;
                            fetched.forEach(r => registeredRooms.push(r));
                        }
                    }
                } catch (e) {
                    console.warn('Rooms fetch error:', e);
                }
            }

            matchingRooms.forEach(room => {
                const roomType = String(valueOf(room, [
                    'room_type', 'type', 'classification', 'room_class', 'evacuation_type'
                ], '')).toLowerCase();
                const isBuffer = isTruthy(valueOf(room, ['Buffer_evac', 'buffer_evac', 'is_buffer', 'buffer'])) || roomType.includes('buffer');
                const isMain = isTruthy(valueOf(room, ['Main_evac', 'main_evac', 'is_main', 'main'])) || roomType.includes('main');
                const label = isBuffer && isMain ? 'Main / Buffer' : isBuffer ? 'Buffer' : isMain ? 'Main' : 'Room';
                const roomName = valueOf(room, [
                    'room_code', 'room_name', 'name', 'identification', 'room_number'
                ], 'Room');
                const roomId = valueOf(room, ['id'], '');
                if (roomId !== '') roomSelect.add(new Option(`${roomName} (${label})`, roomId));
            });

            if (!matchingRooms.length) {
                roomSelect.innerHTML = '<option value="">No registered rooms in this building</option>';
            }
        }

        $('modal_evacuation_center_id')?.addEventListener('change', filterBuildingsByCenter);
        $('firesafety_buildings')?.addEventListener('change', e => populateRooms(e.target.value));
        $('familyRegistrationModal')?.addEventListener('shown.bs.modal', filterBuildingsByCenter);
        filterBuildingsByCenter();
        refreshExistingChoices();

        $('familyRegistrationMode')?.addEventListener('change', e => {
            const isExisting = e.target.value === 'existing';
            $('existingFamilySelectorWrap')?.classList.toggle('d-none', !isExisting);
            if (isExisting) refreshExistingChoices();
            else {
                $('existingFamilyId').value = '';
                clearDynamicRows();
            }
        });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initFamilyModal);
    else initFamilyModal();
})();
</script>