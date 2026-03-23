@extends('layouts.app')

@section('title', 'User Accounts - DRRM Compliance')

@section('content')
@php
    $isAdminView = $isAdmin ?? (auth()->check() && auth()->user()->role === 'admin');
@endphp
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-users-cog text-primary"></i> User Management
            </h1>
            @if($isAdminView)
                <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fas fa-user-plus fa-sm text-white-50"></i> Add New User
                </button>
            @endif
        </div>
    </div>

    <!-- Filters -->
    @if($isAdminView)
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-light">
                <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
            </div>
            <div class="card-body">
                <form id="filterForm" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Role</label>
                        <select class="form-select form-select-sm" name="role" id="filterRole">
                            <option value="">All Roles</option>
                            <option value="admin">Admin</option>
                            <option value="contributor">Contributor</option>
                            <option value="viewer">Viewer</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold">Sort By</label>
                        <select class="form-select form-select-sm" name="sort" id="filterSort">
                            <option value="name">Name</option>
                            <option value="created_at">Date Created</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold">Order</label>
                        <select class="form-select form-select-sm" name="order" id="filterOrder">
                            <option value="asc">Ascending</option>
                            <option value="desc">Descending</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-sm btn-primary me-2">Apply Filters</button>
                        <button type="reset" class="btn btn-sm btn-outline-secondary">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-info shadow-sm">
            <div class="fw-bold mb-1">Manage your account</div>
            You can update your name, email address, and password here. Role and permissions are managed by the administrator.
        </div>
    @endif

    <!-- User List Table -->
    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Username</th>
                            <th>Email Address</th>
                            <th>Role</th>
                            <th>Module Access</th>
                            <th>Assigned School</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody">
                        @foreach($users as $user)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @php
                                    $roleLabel = ucfirst($user->role ?? 'user');
                                @endphp
                                <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : ($user->role === 'contributor' ? 'bg-success' : 'bg-info text-dark') }}">
                                    {{ $roleLabel }}
                                </span>
                            </td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="text-muted small">All Modules</span>
                                @else
                                    @php
                                        $modules = $user->module_access ?? [];
                                        $allAvailableModules = [
                                            'fire_safety',
                                            'typhoon_flood',
                                            'incident_checklist',
                                            'comprehensive_school_safety',
                                            'hazard_mapping',
                                        ];
                                        $hasAllModules = $user->role === 'contributor'
                                            && empty(array_diff($allAvailableModules, $modules));
                                    @endphp

                                    @if($hasAllModules)
                                        <span class="text-muted small">All Modules</span>
                                    @else
                                        @foreach($modules as $mod)
                                            <span class="badge bg-secondary small mb-1">{{ str_replace('_', ' ', $mod) }}</span>
                                        @endforeach
                                    @endif

                                    @if(empty($modules))
                                        <span class="text-muted italic small">No access</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-light text-dark border"><i class="fas fa-globe me-1"></i> Full Access</span>
                                @else
                                    @php
                                        $assignedModules = $user->module_access ?? [];
                                    @endphp

                                    @if(in_array('fire_safety', $assignedModules, true))
                                        <div class="mb-2">
                                            <div class="small fw-bold text-muted mb-1 text-uppercase" style="font-size: 0.65rem;">Fire Safety:</div>
                                            @if($user->school)
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-school text-primary me-2 shadow-sm p-1 rounded bg-light" style="font-size: 0.8rem;"></i>
                                                    <div>
                                                        <div class="fw-bold small" style="font-size: 0.75rem;">{{ $user->school->school_name }}</div>
                                                        <div class="text-muted" style="font-size: 0.65rem;">ID: {{ $user->school->school_id }}</div>
                                                    </div>
                                                </div>
                                            @elseif($user->needs_fs_registration)
                                                <span class="badge bg-warning text-dark" style="font-size: 0.65rem;"><i class="fas fa-keyboard me-1"></i> To be encoded</span>
                                            @endif
                                        </div>
                                    @endif

                                    @if(in_array('typhoon_flood', $assignedModules, true))
                                        <div class="mb-2">
                                            <div class="small fw-bold text-muted mb-1 text-uppercase" style="font-size: 0.65rem;">Typhoon/Flood:</div>
                                            @if($user->typhoonSchool)
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-cloud-showers-heavy text-info me-2 shadow-sm p-1 rounded bg-light" style="font-size: 0.8rem;"></i>
                                                    <div>
                                                        <div class="fw-bold small" style="font-size: 0.75rem;">{{ $user->typhoonSchool->school->school_name }}</div>
                                                        <div class="text-muted" style="font-size: 0.65rem;">{{ $user->typhoonSchool->identification ?: 'Evac Center' }}</div>
                                                    </div>
                                                </div>
                                            @elseif($user->needs_tf_registration)
                                                <span class="badge bg-warning text-dark" style="font-size: 0.65rem;"><i class="fas fa-keyboard me-1"></i> To be encoded</span>
                                            @endif
                                        </div>
                                    @endif

                                    @if(in_array('incident_checklist', $assignedModules, true))
                                        <div>
                                            <div class="small fw-bold text-muted mb-1 text-uppercase" style="font-size: 0.65rem;">Incident Checklist:</div>
                                            @if($user->incidentSchool)
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-clipboard-list text-warning me-2 shadow-sm p-1 rounded bg-light" style="font-size: 0.8rem;"></i>
                                                    <div>
                                                        <div class="fw-bold small" style="font-size: 0.75rem;">{{ $user->incidentSchool->name }}</div>
                                                        <div class="text-muted" style="font-size: 0.65rem;">Assigned Incident School</div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    @if(empty($assignedModules))
                                        <span class="text-muted small italic">No module school assignments</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success shadow-sm"><i class="fas fa-check-circle me-1"></i> Active</span>
                                @else
                                    <span class="badge bg-secondary shadow-sm"><i class="fas fa-times-circle me-1"></i> Deactivated</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="editUser({{ $user->id }})">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                @if($isAdminView)
                                    <button class="btn btn-sm btn-outline-success me-1" onclick="assignAccess({{ $user->id }})" title="Assign Access">
                                        <i class="fas fa-tasks"></i>
                                    </button>
                                    @if($user->id !== auth()->id())
                                    <button class="btn btn-sm {{ $user->is_active ? 'btn-outline-warning' : 'btn-outline-info' }}" 
                                            onclick="toggleUserStatus({{ $user->id }}, {{ $user->is_active ? 'true' : 'false' }})" 
                                            title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                    </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if($isAdminView)
<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Add New User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addUserForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <input type="text" name="name" class="form-control" required placeholder="Enter username" autocomplete="off" value="">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control" required placeholder="example@email.com" autocomplete="off" value="">
                        <div class="form-text text-primary"><i class="fas fa-info-circle me-1"></i> This must be a working Google account for password recovery/verification.</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" required autocomplete="new-password">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="contributor">Contributor</option>
                            <option value="viewer">Viewer</option>
                            @if($adminCount < 2)
                                <option value="admin">Admin</option>
                            @endif
                        </select>
                        @if($adminCount >= 2)
                            <div class="form-text text-danger">Maximum of 2 Administrators reached (~ Admin role disabled).</div>
                        @endif
                    </div>
                    <div id="adminConfirmation" class="mb-3 d-none">
                        <label class="form-label text-danger fw-bold">Admin Password Confirmation</label>
                        <input type="password" name="admin_confirmation" class="form-control" placeholder="Your password to confirm admin creation">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Edit User Info</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editUserId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <input type="text" name="name" id="editUserName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Address</label>
                        <input type="email" name="email" id="editUserEmail" class="form-control" required>
                    </div>
                    <div class="mb-3 border-top pt-3">
                        <label class="form-label fw-bold">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    @if($isAdminView)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Role</label>
                            <select name="role" id="editUserRole" class="form-select" required>
                                <option value="contributor">Contributor</option>
                                <option value="viewer">Viewer</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    @endif
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info text-white">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($isAdminView)
<!-- Assign Access Modal -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-shield-alt me-2"></i>Assign Module Access & Schools</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="assignForm">
                @csrf
                <input type="hidden" name="user_id" id="assignUserId">
                <div class="modal-body">
                    <p class="mb-3">Select WHICH compliance systems <strong id="assignUserName"></strong> can see and assign a school if applicable.</p>
                    
                    <div class="list-group">
                        <!-- Fire Safety -->
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="form-check">
                                    <input class="form-check-input module-check" type="checkbox" name="modules[]" value="fire_safety" id="checkFS">
                                    <label class="form-check-label fw-bold" for="checkFS">Fire Safety Compliance</label>
                                </div>
                                <span class="badge bg-secondary">Active</span>
                            </div>
                            <div id="schoolFS" class="ms-4 mt-2 d-none">
                                <label class="small fw-bold">Assign School:</label>
                                <select name="school_id" class="form-select form-select-sm school-select">
                                    <option value="encode">Encode their own school first</option>
                                    @if($schools->isEmpty())
                                        <option value="" disabled>-- No existing schools --</option>
                                    @else
                                        <option value="">-- Select Existing School --</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <!-- Typhoon/Flooding -->
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="form-check">
                                    <input class="form-check-input module-check" type="checkbox" name="modules[]" value="typhoon_flood" id="checkTF">
                                    <label class="form-check-label fw-bold" for="checkTF">Typhoon/Flooding Compliance</label>
                                </div>
                                <span class="badge bg-secondary">Active</span>
                            </div>
                            <!-- Note: Request says use respective tables, but they don't exist yet. Using FireSafety schools for now as placeholder as per instructions -->
                            <div id="schoolTF" class="ms-4 mt-2 d-none">
                                <label class="small fw-bold">Assign School (Typhoon):</label>
                                <select name="typhoon_school_id" class="form-select form-select-sm school-select">
                                    <option value="encode">Encode their own school first</option>
                                    @if($typhoonSchools->isEmpty())
                                        <option value="" disabled>-- No existing evacuation centers --</option>
                                    @else
                                        <option value="">-- Select Existing Evacuation Center --</option>
                                        @foreach($typhoonSchools as $tSchool)
                                            <option value="{{ $tSchool->id }}">{{ $tSchool->school ? $tSchool->school->school_name : ($tSchool->identification ?: 'ID: ' . $tSchool->id) }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <!-- Incident Checklist -->
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="form-check">
                                    <input class="form-check-input module-check" type="checkbox" name="modules[]" value="incident_checklist" id="checkIC">
                                    <label class="form-check-label fw-bold" for="checkIC">Incident Checklist</label>
                                </div>
                                <span class="badge bg-secondary">Active</span>
                            </div>
                            <div id="schoolIC" class="ms-4 mt-2 d-none">
                                <label class="small fw-bold">Assign School (Incident):</label>
                                <select name="incident_school_id" class="form-select form-select-sm school-select">
                                    <option value="">-- Select Incident School --</option>
                                    @foreach($incidentSchools as $incidentSchool)
                                        <option value="{{ $incidentSchool->id }}">{{ $incidentSchool->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- School Safety -->
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="form-check">
                                    <input class="form-check-input module-check" type="checkbox" name="modules[]" value="comprehensive_school_safety" id="checkSS">
                                    <label class="form-check-label fw-bold" for="checkSS">Comprehensive School Safety</label>
                                </div>
                                <span class="badge bg-info text-dark">Development</span>
                            </div>
                            <div id="schoolSS" class="ms-4 mt-2 d-none">
                                <label class="small fw-bold">Assign School:</label>
                                <select name="school_safety_id" class="form-select form-select-sm" disabled>
                                    <option value="">-- Development Only --</option>
                                </select>
                            </div>
                        </div>

                        <!-- Hazard Mapping -->
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input module-check" type="checkbox" name="modules[]" value="hazard_mapping" id="checkHM">
                                    <label class="form-check-label fw-bold" for="checkHM">Hazard Mapping</label>
                                </div>
                                <span class="badge bg-info text-dark">Development</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Assignments</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isAdminView = {{ $isAdminView ? 'true' : 'false' }};

    // Role monitoring for Admin password
    if (isAdminView) {
        const roleSelect = document.querySelector('#addUserForm select[name="role"]');
        const adminConfirm = document.getElementById('adminConfirmation');
        if (roleSelect && adminConfirm) {
            roleSelect.addEventListener('change', function() {
                if (this.value === 'admin') {
                    adminConfirm.classList.remove('d-none');
                    adminConfirm.querySelector('input').setAttribute('required', 'required');
                } else {
                    adminConfirm.classList.add('d-none');
                    adminConfirm.querySelector('input').removeAttribute('required');
                }
            });
        }
    }

    // Reset Add User Form when modal is shown
    if (isAdminView) {
        const addUserModalEl = document.getElementById('addUserModal');
        if (addUserModalEl) {
            addUserModalEl.addEventListener('show.bs.modal', function() {
                const form = document.getElementById('addUserForm');
                if (form) form.reset();
                const adminConfirm = document.getElementById('adminConfirmation');
                if (adminConfirm) {
                    adminConfirm.classList.add('d-none');
                    adminConfirm.querySelector('input').removeAttribute('required');
                }
            });
        }
    }

    // Module checkbox monitoring
    document.querySelectorAll('.module-check').forEach(check => {
        check.addEventListener('change', function() {
            const schoolDiv = document.getElementById('school' + this.id.replace('check', ''));
            if (schoolDiv) {
                if (this.checked) {
                    schoolDiv.classList.remove('d-none');
                } else {
                    schoolDiv.classList.add('d-none');
                }
            }
        });
    });

    // Form Submissions
    if (isAdminView) {
        const addUserForm = document.getElementById('addUserForm');
        if (addUserForm) {
            addUserForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const data = Object.fromEntries(formData.entries());
                data.modules = []; // Initial empty modules

                fetch("{{ route('users.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                }).then(res => res.json()).then(res => {
                    if (res.success) {
                        location.reload();
                    } else {
                        alert(res.message || 'Error occurred');
                    }
                });
            });
        }
    }

    const editUserForm = document.getElementById('editUserForm');
    if (editUserForm) {
        editUserForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const userId = document.getElementById('editUserId').value;
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            fetch(`/users/${userId}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            }).then(res => res.json()).then(res => {
                if (res.success) {
                    location.reload();
                } else {
                    alert(res.message || 'Error occurred');
                }
            });
        });
    }

    if (isAdminView) {
        const assignForm = document.getElementById('assignForm');
        if (assignForm) {
            assignForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const userId = document.getElementById('assignUserId').value;
                const modules = [];
                this.querySelectorAll('input[name="modules[]"]:checked').forEach(c => modules.push(c.value));
                
                const data = {
                    modules: modules,
                    school_id: formData.get('school_id'),
                    typhoon_school_id: formData.get('typhoon_school_id'),
                    incident_school_id: formData.get('incident_school_id')
                };

                fetch("{{ route('users.index') }}/" + userId + "/assign", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                }).then(res => res.json()).then(res => {
                    if (res.success) {
                        location.reload();
                    } else {
                        alert(res.message);
                    }
                });
            });
        }
    }
});

function editUser(userId) {
    const modalEl = document.getElementById('editUserModal');
    if (!modalEl) return;

    fetch("{{ route('users.show', ':id') }}".replace(':id', userId), {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(res => {
            if (!res.ok) throw new Error('Response code: ' + res.status);
            return res.json();
        })
        .then(user => {
            const idInput = document.getElementById('editUserId');
            const nameInput = document.getElementById('editUserName');
            const emailInput = document.getElementById('editUserEmail');
            const roleSelect = document.getElementById('editUserRole');

            if (idInput) idInput.value = user.id;
            if (nameInput) nameInput.value = user.name;
            if (emailInput) emailInput.value = user.email;
            if (roleSelect) roleSelect.value = user.role;
            
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Error fetching user data: ' + err.message);
        });
}

function assignAccess(userId) {
    const isAdminView = {{ $isAdminView ? 'true' : 'false' }};
    if (!isAdminView) return;
    const modalEl = document.getElementById('assignModal');
    if (!modalEl) {
        console.error('Assign modal not found');
        return;
    }

    fetch("{{ route('users.show', ':id') }}".replace(':id', userId), {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(res => {
            if (!res.ok) throw new Error('Response code: ' + res.status);
            return res.json();
        })
        .then(user => {
            const userIdInput = document.getElementById('assignUserId');
            const userNameSpan = document.getElementById('assignUserName');
            const assignForm = document.getElementById('assignForm');

            if (userIdInput) userIdInput.value = user.id;
            if (userNameSpan) userNameSpan.textContent = user.name;
            
            const isAdmin = user.role === 'admin';
            const saveBtn = modalEl.querySelector('button[type="submit"]');

            // Clear checks and hide all school divs first
            modalEl.querySelectorAll('.module-check').forEach(c => {
                if (isAdmin) {
                    c.checked = true;
                    c.disabled = true;
                } else {
                    c.checked = false;
                    c.disabled = false;
                }
                
                const schoolDivId = 'school' + c.id.replace('check', '');
                const schoolDiv = document.getElementById(schoolDivId);
                if (schoolDiv) {
                    if (isAdmin || c.checked) {
                        schoolDiv.classList.remove('d-none');
                    } else {
                        schoolDiv.classList.add('d-none');
                    }
                }
            });

            // FS School selection
            const schoolSelect = assignForm.querySelector('select[name="school_id"]');
            if (schoolSelect) {
                if (isAdmin) {
                    schoolSelect.value = "";
                    schoolSelect.disabled = true;
                } else {
                    schoolSelect.disabled = false;
                    if (user.needs_fs_registration) {
                        schoolSelect.value = "encode";
                    } else {
                        schoolSelect.value = user.school_id || "";
                    }
                }
            }

            // Typhoon School selection
            const typhoonSelect = assignForm.querySelector('select[name="typhoon_school_id"]');
            if (typhoonSelect) {
                if (isAdmin) {
                    typhoonSelect.value = "";
                    typhoonSelect.disabled = true;
                } else {
                    typhoonSelect.disabled = false;
                    if (user.needs_tf_registration) {
                        typhoonSelect.value = "encode";
                    } else {
                        typhoonSelect.value = user.typhoon_school_id || "";
                    }
                }
            }

            // Incident School selection
            const incidentSelect = assignForm.querySelector('select[name="incident_school_id"]');
            if (incidentSelect) {
                if (isAdmin) {
                    incidentSelect.value = "";
                    incidentSelect.disabled = true;
                } else {
                    incidentSelect.disabled = false;
                    incidentSelect.value = user.incident_school_id || "";
                }
            }

            // Set checks and show relevant school divs (for non-admins)
            if (!isAdmin) {
                const access = user.module_access || [];
                access.forEach(mod => {
                    const check = modalEl.querySelector(`.module-check[value="${mod}"]`);
                    if (check) {
                        check.checked = true;
                        const schoolDivId = 'school' + check.id.replace('check', '');
                        const schoolDiv = document.getElementById(schoolDivId);
                        if (schoolDiv) schoolDiv.classList.remove('d-none');
                    }
                });
            }

            // If admin, hide save button or show "Admin has full access" message
            if (isAdmin) {
                if (saveBtn) saveBtn.classList.add('d-none');
            } else {
                if (saveBtn) saveBtn.classList.remove('d-none');
            }

            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        })
        .catch(err => {
            console.error('Error fetching user data:', err);
            alert('Error fetching user details: ' + err.message);
        });
}

function toggleUserStatus(userId, currentState) {
    const isAdminView = {{ $isAdminView ? 'true' : 'false' }};
    if (!isAdminView) return;
    const action = currentState ? 'deactivate' : 'activate';
    if (confirm(`Are you sure you want to ${action} this user account?`)) {
        fetch("{{ route('users.index') }}/" + userId + "/toggle-status", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(res => res.json()).then(res => {
            if (res.success) {
                location.reload();
            } else {
                alert(res.message);
            }
        }).catch(err => {
            console.error('Error:', err);
            alert('Error toggling user status: ' + err.message);
        });
    }
}
</script>
@endpush
