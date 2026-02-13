@extends('layouts.app')

@section('title', 'User Accounts - DRRM Compliance')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-users-cog text-primary"></i> User Management
            </h1>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-user-plus fa-sm text-white-50"></i> Add New User
            </button>
        </div>
    </div>

    <!-- Filters -->
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
                                    @php $modules = $user->module_access ?? []; @endphp
                                    @foreach($modules as $mod)
                                        <span class="badge bg-secondary small mb-1">{{ str_replace('_', ' ', $mod) }}</span>
                                    @endforeach
                                    @if(empty($modules))
                                        <span class="text-muted italic small">No access</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($user->school)
                                    <span class="small">{{ $user->school->name }}</span>
                                @else
                                    <span class="text-muted small">None</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="editUser({{ $user->id }})">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-outline-success me-1" onclick="assignAccess({{ $user->id }})">
                                    <i class="fas fa-tasks"></i> Assign
                                </button>
                                @if($user->id !== auth()->id())
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteUser({{ $user->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
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
                        <input type="text" name="name" class="form-control" required placeholder="Enter username">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control" required placeholder="example@email.com">
                        <div class="form-text">Needs to be legit and true for future use.</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="contributor">Contributor</option>
                            <option value="viewer">Viewer</option>
                            <option value="admin">Admin</option>
                        </select>
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
                    <div class="mb-3">
                        <label class="form-label fw-bold">Role</label>
                        <select name="role" id="editUserRole" class="form-select" required>
                            <option value="contributor">Contributor</option>
                            <option value="viewer">Viewer</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info text-white">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
                                    <option value="">-- Select School --</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                                    @endforeach
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
                                <select name="typhoon_school_id" class="form-select form-select-sm school-select" disabled>
                                    <option value="">-- Resource Not Ready Yet --</option>
                                </select>
                            </div>
                        </div>

                        <!-- Incident Checklist -->
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input module-check incident-check" type="checkbox" name="modules[]" value="incident_checklist" id="checkIC">
                                    <label class="form-check-label fw-bold" for="checkIC">Incident Checklist</label>
                                </div>
                                <span class="badge bg-warning text-dark">Confirmation Required</span>
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

<!-- Incident Confirmation Modal -->
<div class="modal fade" id="incidentConfirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle"></i> Confirmation Required</h5>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to grant <strong>Incident Checklist</strong> access? Any user (including Contributors) assigned to this will only have <strong>VIEWING</strong> capabilities.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelIC">Actually, no</button>
                <button type="button" class="btn btn-warning fw-bold text-dark" id="confirmIC">Yes, Grant Access</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Role monitoring for Admin password
    const roleSelect = document.querySelector('#addUserForm select[name="role"]');
    const adminConfirm = document.getElementById('adminConfirmation');
    roleSelect.addEventListener('change', function() {
        if (this.value === 'admin') {
            adminConfirm.classList.remove('d-none');
            adminConfirm.querySelector('input').setAttribute('required', 'required');
        } else {
            adminConfirm.classList.add('d-none');
            adminConfirm.querySelector('input').removeAttribute('required');
        }
    });

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

    // Incident Checklist Confirmation
    const icCheck = document.querySelector('.incident-check');
    const confirmModalEl = document.getElementById('incidentConfirmModal');
    if (icCheck && confirmModalEl) {
        const confirmModal = new bootstrap.Modal(confirmModalEl);

        icCheck.addEventListener('click', function(e) {
            if (!this.checked) {
                return;
            }
            e.preventDefault();
            confirmModal.show();
        });

        const confirmBtn = document.getElementById('confirmIC');
        const cancelBtn = document.getElementById('cancelIC');

        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                icCheck.checked = true;
                confirmModal.hide();
            });
        }

        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                icCheck.checked = false;
                confirmModal.hide();
            });
        }
    }

    // Form Submissions
    document.getElementById('addUserForm').addEventListener('submit', function(e) {
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

    document.getElementById('assignForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const userId = document.getElementById('assignUserId').value;
        const modules = [];
        this.querySelectorAll('input[name="modules[]"]:checked').forEach(c => modules.push(c.value));
        
        const data = {
            modules: modules,
            school_id: formData.get('school_id')
        };

        fetch(`/users/${userId}/assign`, {
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
});

function editUser(userId) {
    fetch(`/users/${userId}`)
        .then(res => res.json())
        .then(user => {
            document.getElementById('editUserId').value = user.id;
            document.getElementById('editUserName').value = user.name;
            document.getElementById('editUserEmail').value = user.email;
            document.getElementById('editUserRole').value = user.role;
            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        });
}

function assignAccess(userId) {
    fetch(`/users/${userId}`)
        .then(res => res.json())
        .then(user => {
            document.getElementById('assignUserId').value = user.id;
            document.getElementById('assignUserName').textContent = user.name;
            
            // Clear checks
            document.querySelectorAll('.module-check').forEach(c => {
                c.checked = false;
                const div = document.getElementById('school' + c.id.replace('check', ''));
                if(div) div.classList.add('d-none');
            });

            // Set checks
            const access = user.module_access || [];
            access.forEach(mod => {
                const check = document.querySelector(`.module-check[value="${mod}"]`);
                if (check) {
                    check.checked = true;
                    const div = document.getElementById('school' + check.id.replace('check', ''));
                    if(div) div.classList.remove('d-none');
                }
            });

            // Set school
            if (user.school_id) {
                document.querySelector('select[name="school_id"]').value = user.school_id;
            } else {
                document.querySelector('select[name="school_id"]').value = "";
            }

            new bootstrap.Modal(document.getElementById('assignModal')).show();
        });
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        fetch(`/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(res => res.json()).then(res => {
            if (res.success) {
                location.reload();
            } else {
                alert(res.message);
            }
        });
    }
}
</script>
@endsection
