@extends('layouts.app')

@section('title', 'User Accounts - DRRM Compliance')

@section('content')
@php
    $isAdminView = $isAdmin ?? (auth()->check() && auth()->user()->role === 'admin');
@endphp
<style>
    /* User Management Redesign */
    :root {
        --um-navy: #0D1B36;
        --um-blue: #1E3A5F;
        --um-accent: #2563EB;
        --um-bg: #F8FAFC;
        --um-border: #E2E8F0;
        --um-text: #374151;
        --um-text-light: #6B7280;
        --um-cyan: #06b6d4;
        --um-cyan-hover: #0891b2;
        --um-green: #10B981;
        --um-green-hover: #059669;
    }
    
    .um-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
        animation: fadeInDown 0.4s ease-out;
    }
    .um-title-section {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }
    .um-title-icon {
        width: 50px;
        height: 50px;
        background: #EFF6FF;
        color: var(--um-accent);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    .um-title h1 {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--um-navy);
        margin: 0;
        line-height: 1.2;
    }
    .um-title p {
        margin: 0;
        font-size: 0.9rem;
        color: var(--um-text-light);
    }
    .btn-add-user {
        background: var(--um-navy);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }
    .btn-add-user:hover {
        background: var(--um-blue);
        color: white;
        transform: translateY(-1px);
    }

    /* Filters Card */
    .um-filters-card {
        background: white;
        border: 1px solid var(--um-border);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        animation: fadeInUp 0.4s ease-out 0.1s both;
    }
    .um-filters-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--um-navy);
        font-weight: 800;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }
    .um-filters-header i {
        color: #EA580C;
    }
    .um-filter-label {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--um-text);
        margin-bottom: 0.5rem;
    }
    .um-form-select {
        border-radius: 8px;
        border: 1px solid var(--um-border);
        padding: 0.6rem 2.5rem 0.6rem 1rem;
        font-size: 0.9rem;
        color: var(--um-text);
        box-shadow: none;
    }
    .um-form-select:focus {
        border-color: var(--um-accent);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    }
    .btn-apply-filter {
        background: var(--um-navy);
        color: white;
        border: none;
        padding: 0.6rem 1.5rem;
        border-radius: 8px;
        font-weight: 700;
        transition: all 0.2s;
        height: 42px;
    }
    .btn-apply-filter:hover {
        background: var(--um-blue);
    }
    .btn-reset-filter {
        background: white;
        color: var(--um-text);
        border: 1px solid var(--um-border);
        padding: 0.6rem 1.5rem;
        border-radius: 8px;
        font-weight: 700;
        transition: all 0.2s;
        height: 42px;
    }
    .btn-reset-filter:hover {
        background: var(--um-bg);
    }

    /* Table */
    .um-table-card {
        background: white;
        border: 1px solid var(--um-border);
        border-radius: 12px;
        overflow: hidden;
        animation: fadeInUp 0.4s ease-out 0.2s both;
    }
    .um-table {
        margin: 0;
        width: 100%;
        border-collapse: collapse;
    }
    .um-table th {
        background: transparent;
        color: var(--um-text-light);
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1.25rem 1rem;
        border-bottom: 1px solid var(--um-border);
    }
    .um-table td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--um-border);
        color: var(--um-text);
        font-size: 0.9rem;
        transition: background 0.2s;
    }
    .um-table tbody tr:hover td {
        background: #F8FAFC;
    }
    .um-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* User Avatar/Info */
    .um-user-cell {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .um-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--um-navy);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.85rem;
        flex-shrink: 0;
    }
    .um-username {
        font-weight: 800;
        color: var(--um-navy);
    }

    /* Pills */
    .um-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    .um-pill-admin { background: #FEE2E2; color: #DC2626; }
    .um-pill-contrib { background: #DCFCE7; color: #16A34A; }
    .um-pill-viewer { background: #E0F2FE; color: #0284C7; }

    .um-pill-school { background: #F1F5F9; color: var(--um-navy); border: 1px solid var(--um-border); }
    .um-pill-school i { color: #64748B; }

    .um-pill-status-active { background: #DCFCE7; color: #16A34A; }
    .um-pill-status-inactive { background: #F1F5F9; color: #64748B; }
    .um-status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    /* Action Buttons */
    .um-actions {
        display: flex;
        gap: 0.5rem;
    }
    .um-btn-action {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        border: 1px solid var(--um-border);
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--um-text-light);
        transition: all 0.2s;
        cursor: pointer;
    }
    .um-btn-action:hover {
        background: var(--um-bg);
        transform: translateY(-2px);
    }
    .um-btn-edit:hover { color: var(--um-accent); border-color: var(--um-accent); }
    .um-btn-assign:hover { color: #10B981; border-color: #10B981; }
    .um-btn-toggle:hover { color: #F59E0B; border-color: #F59E0B; }

    /* Modals Redesign */
    .modal-cyan-header {
        background: var(--um-cyan);
        color: white;
        border-bottom: none;
        border-radius: 12px 12px 0 0;
    }
    .modal-green-header {
        background: var(--um-green);
        color: white;
        border-bottom: none;
        border-radius: 12px 12px 0 0;
    }
    .modal-header .btn-close-white {
        filter: invert(1) grayscale(100%) brightness(200%);
        opacity: 0.8;
    }
    .modal-header .btn-close-white:hover {
        opacity: 1;
    }
    .modal-content.border-0 {
        border-radius: 12px;
    }
    .btn-cyan {
        background: var(--um-cyan);
        color: white;
        border: none;
    }
    .btn-cyan:hover {
        background: var(--um-cyan-hover);
        color: white;
    }
    .btn-green {
        background: var(--um-green);
        color: white;
        border: none;
    }
    .btn-green:hover {
        background: var(--um-green-hover);
        color: white;
    }

    /* Animations */
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="container-fluid px-4 px-lg-5 py-4">
    <!-- Header -->
    <div class="um-page-header">
        <div class="um-title-section">
            <div class="um-title-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="um-title">
                <h1>User Management</h1>
                <p>Manage accounts, roles, and module access across the DRRM system.</p>
            </div>
        </div>
        @if($isAdminView)
            <button class="btn-add-user" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-user-plus"></i> Add New User
            </button>
        @endif
    </div>

    <!-- Filters -->
    @if($isAdminView)
        <div class="um-filters-card">
            <div class="um-filters-header">
                <i class="fas fa-filter"></i> Filters
            </div>
            <form id="filterForm">
                <div class="row g-4 align-items-end">
                    <div class="col-md-3">
                        <div class="um-filter-label">Role</div>
                        <select class="form-select um-form-select" name="role" id="filterRole">
                            <option value="">All Roles</option>
                            <option value="admin">Admin</option>
                            <option value="contributor">Contributor</option>
                            <option value="viewer">Viewer</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="um-filter-label">Sort By</div>
                        <select class="form-select um-form-select" name="sort" id="filterSort">
                            <option value="name">Name</option>
                            <option value="created_at">Date Created</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="um-filter-label">Order</div>
                        <select class="form-select um-form-select" name="order" id="filterOrder">
                            <option value="asc">Ascending</option>
                            <option value="desc">Descending</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn-apply-filter flex-grow-1"><i class="fas fa-check me-2"></i> Apply Filters</button>
                        <button type="reset" class="btn-reset-filter">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    @else
        <div class="alert alert-info shadow-sm rounded-3 mb-4">
            <div class="fw-bold mb-1"><i class="fas fa-info-circle me-2"></i> Manage your account</div>
            You can update your name, email address, and password here. Role and permissions are managed by the administrator.
        </div>
    @endif

    <!-- User List Table -->
    <div class="um-table-card">
        <div class="table-responsive">
            <table class="um-table">
                <thead>
                    <tr>
                        <th class="ps-4">Username</th>
                        <th>Email Address</th>
                        <th>Role</th>
                        <th>Module Access</th>
                        <th>Assigned School</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    @foreach($users as $user)
                    <tr>
                        <td class="ps-4">
                            <div class="um-user-cell">
                                @php
                                    $initials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $user->name), 0, 2));
                                    if (empty($initials)) $initials = 'U';
                                @endphp
                                <div class="um-avatar">{{ $initials }}</div>
                                <div class="um-username">{{ $user->name }}</div>
                            </div>
                        </td>
                        <td class="text-muted">{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="um-pill um-pill-admin">Admin</span>
                            @elseif($user->role === 'contributor')
                                <span class="um-pill um-pill-contrib">Contributor</span>
                            @else
                                <span class="um-pill um-pill-viewer">Viewer</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted" style="font-style: italic;">
                                @if($user->role === 'admin')
                                    All Modules
                                @elseif($user->role === 'contributor')
                                    All Modules (Exc. CSS)
                                @else
                                    @php
                                        $modules = $user->module_access ?? [];
                                        $coreComplianceModules = ['fire_safety','typhoon_flood','incident_checklist','comprehensive_school_safety','hazard_mapping'];
                                        $hasAllModules = empty(array_diff($coreComplianceModules, $modules));
                                    @endphp
                                    @if($hasAllModules)
                                        All Modules
                                    @elseif(empty($modules))
                                        No access
                                    @else
                                        Partial Access
                                    @endif
                                @endif
                            </span>
                        </td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="um-pill um-pill-school"><i class="fas fa-globe"></i> Full Access</span>
                            @else
                                @if($user->school)
                                    <span class="fw-semibold text-dark">{{ $user->school->school_name }}</span>
                                @elseif($user->typhoonSchool)
                                    <span class="fw-semibold text-dark">{{ $user->typhoonSchool->school_name }}</span>
                                @elseif($user->incidentSchool)
                                    <span class="fw-semibold text-dark">{{ $user->incidentSchool->school_name ?? $user->incidentSchool->name }}</span>
                                @elseif($user->needs_fs_registration || $user->needs_tf_registration)
                                    <span class="text-warning fw-bold small"><i class="fas fa-exclamation-circle me-1"></i> To be encoded</span>
                                @else
                                    <span class="text-muted small" style="font-style: italic;">Unassigned</span>
                                @endif
                            @endif
                        </td>
                        <td>
                            @if($user->is_active)
                                <span class="um-pill um-pill-status-active"><div class="um-status-dot"></div> Active</span>
                            @else
                                <span class="um-pill um-pill-status-inactive"><div class="um-status-dot"></div> Deactivated</span>
                            @endif
                        </td>
                        <td>
                            <div class="um-actions">
                                <button class="um-btn-action um-btn-edit" onclick="editUser({{ $user->id }})" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($isAdminView)
                                    @if($user->role !== 'admin')
                                        <button class="um-btn-action um-btn-assign" onclick="assignAccess({{ $user->id }})" title="Assign Access">
                                            <i class="fas fa-user-tag"></i>
                                        </button>
                                    @endif
                                    @if($user->id !== auth()->id())
                                        <button class="um-btn-action um-btn-toggle" onclick="toggleUserStatus({{ $user->id }}, {{ $user->is_active ? 'true' : 'false' }})" title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header modal-cyan-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-edit me-2"></i>Edit User Info</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editUserId">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Username</label>
                        <input type="text" name="name" id="editUserName" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Email Address</label>
                        <input type="email" name="email" id="editUserEmail" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3 border-top pt-3">
                        <label class="form-label fw-bold small text-dark">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control rounded-3">
                    </div>
                    @if($isAdminView)
                        <div class="mb-2">
                            <label class="form-label fw-bold small text-dark">Role</label>
                            <select name="role" id="editUserRole" class="form-select rounded-3" required>
                                <option value="contributor">Contributor</option>
                                <option value="viewer">Viewer</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    @endif
                </div>
                <div class="modal-footer bg-white border-top-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-secondary rounded-3 px-4 fw-bold" style="background: #6B7280; border: none;" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-cyan rounded-3 px-4 fw-bold">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($isAdminView)
<!-- Assign Access Modal -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header modal-green-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-shield-alt me-2"></i>Assign Module Access & Schools</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="assignForm">
                @csrf
                <input type="hidden" name="user_id" id="assignUserId">
                <div class="modal-body p-4">
                    <p class="mb-3 text-dark">Select WHICH compliance systems <strong id="assignUserName"></strong> can see and assign a school if applicable.</p>
                    
                    <div id="contributorModuleNotice" class="alert alert-info small d-none border-0" style="background-color: #E0F2FE; color: #0369A1; border-radius: 8px;">
                        Contributor accounts automatically include Fire Safety, Typhoon/Flood, Incident Checklist, and Hazard Mapping. You can only toggle Comprehensive School Safety.
                    </div>

                    <div class="border rounded-3 p-3 mb-4 bg-white shadow-sm">
                        <label class="small fw-bold mb-2 d-block text-dark">Select School</label>
                        <select name="universal_school_id" id="universalSchoolSelect" class="form-select form-select-sm rounded-2">
                            <option value="">-- Select School --</option>
                            @if($schools->isEmpty())
                                <option value="" disabled>-- No existing schools --</option>
                            @else
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <small class="text-muted d-block mt-2">This sets one school assignment across contributor/viewer access.</small>
                    </div>
                    
                    <div class="list-group border-0 shadow-sm rounded-3">
                        <!-- Fire Safety -->
                        <div class="list-group-item border-start-0 border-end-0 border-top-0 px-4 py-3 module-row" data-module-row="fire_safety">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check m-0">
                                    <input class="form-check-input module-check mt-1" type="checkbox" name="modules[]" value="fire_safety" id="checkFS">
                                    <label class="form-check-label fw-bold text-dark ms-2" for="checkFS">Fire Safety Compliance</label>
                                </div>
                            </div>
                        </div>

                        <!-- Typhoon/Flooding -->
                        <div class="list-group-item border-start-0 border-end-0 px-4 py-3 module-row" data-module-row="typhoon_flood">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check m-0">
                                    <input class="form-check-input module-check mt-1" type="checkbox" name="modules[]" value="typhoon_flood" id="checkTF">
                                    <label class="form-check-label fw-bold text-dark ms-2" for="checkTF">Evacuation Monitoring</label>
                                </div>
                            </div>
                        </div>

                        <!-- Incident Checklist -->
                        <div class="list-group-item border-start-0 border-end-0 px-4 py-3 module-row" data-module-row="incident_checklist">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check m-0">
                                    <input class="form-check-input module-check mt-1" type="checkbox" name="modules[]" value="incident_checklist" id="checkIC">
                                    <label class="form-check-label fw-bold text-dark ms-2" for="checkIC">Incident Checklist</label>
                                </div>
                            </div>
                        </div>

                        <!-- School Safety -->
                        <div class="list-group-item border-start-0 border-end-0 px-4 py-3 module-row" data-module-row="comprehensive_school_safety">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check m-0">
                                    <input class="form-check-input module-check mt-1" type="checkbox" name="modules[]" value="comprehensive_school_safety" id="checkSS">
                                    <label class="form-check-label fw-bold text-dark ms-2" for="checkSS">Comprehensive School Safety</label>
                                </div>
                                <span class="badge rounded-pill px-3 py-2" style="background: #06b6d4; color: white;">In Development</span>
                            </div>
                        </div>

                        <!-- Hazard Mapping -->
                        <div class="list-group-item border-start-0 border-end-0 border-bottom-0 px-4 py-3 module-row" data-module-row="hazard_mapping">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check m-0">
                                    <input class="form-check-input module-check mt-1" type="checkbox" name="modules[]" value="hazard_mapping" id="checkHM">
                                    <label class="form-check-label fw-bold text-dark ms-2" for="checkHM">Hazard Mapping</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white border-top-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-secondary rounded-3 px-4 fw-bold" style="background: #6B7280; border: none;" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-green rounded-3 px-4 fw-bold">Save Assignments</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Confirm Deactivate/Activate Modal -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white border-bottom-0" style="border-radius: 12px 12px 0 0;">
                <h5 class="modal-title fw-bold" id="toggleStatusTitle">Confirm Action</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="fas fa-exclamation-triangle text-warning mb-3" style="font-size: 3rem;"></i>
                <h5 class="fw-bold mb-3" id="toggleStatusMessage">Are you sure you want to toggle this user's account?</h5>
                <p class="text-muted mb-0">This action will immediately change their access to the system.</p>
            </div>
            <div class="modal-footer bg-white border-top-0 px-4 pb-4 pt-0 justify-content-center">
                <button type="button" class="btn btn-secondary rounded-3 px-4 fw-bold" style="background: #6B7280; border: none;" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-dark rounded-3 px-4 fw-bold" id="confirmToggleStatusBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>

@if($isAdminView)
<div class="modal fade" id="csssSecurityRestrictionModal" tabindex="-1" aria-labelledby="csssSecurityRestrictionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header csss-security-header text-white">
                <h5 class="modal-title" id="csssSecurityRestrictionLabel">Security Restriction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="fw-bold mb-2">Are you sure you want to assign a contributor to take an assessment?</p>
                <p class="mb-0">Only administrators should perform assessments. Assigning contributors may bypass required compliance protocols.</p>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" id="csssCancelAssignBtn" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn csss-security-btn text-white" id="csssAssignAnywayBtn">Assign Anyway</button>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('styles')
<style>
    .csss-security-header {
        background: linear-gradient(135deg, #8b5a2b 0%, #5e3b1f 100%);
    }

    .csss-security-btn {
        background: #8b5a2b;
        border-color: #8b5a2b;
    }

    .csss-security-btn:hover {
        background: #744921;
        border-color: #744921;
    }
</style>
@endpush

@push('scripts')
<script>
let csssRestrictionConfirmed = false;
let csssRestrictionModalInstance = null;

document.addEventListener('DOMContentLoaded', function() {
    const isAdminView = {{ $isAdminView ? 'true' : 'false' }};
    const assignModalEl = document.getElementById('assignModal');
    const universalSchoolSelect = document.getElementById('universalSchoolSelect');
    const csssCheckbox = document.getElementById('checkSS');
    const csssAssignAnywayBtn = document.getElementById('csssAssignAnywayBtn');
    const csssCancelAssignBtn = document.getElementById('csssCancelAssignBtn');
    csssRestrictionModalInstance = document.getElementById('csssSecurityRestrictionModal')
        ? bootstrap.Modal.getOrCreateInstance(document.getElementById('csssSecurityRestrictionModal'))
        : null;

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
            if (this.id === 'checkSS' && this.checked) {
                csssRestrictionConfirmed = false;
                if (csssRestrictionModalInstance) {
                    csssRestrictionModalInstance.show();
                }
            }
        });
    });

    if (csssAssignAnywayBtn) {
        csssAssignAnywayBtn.addEventListener('click', function () {
            csssRestrictionConfirmed = true;
            if (csssCheckbox) {
                csssCheckbox.checked = true;
            }
            if (csssRestrictionModalInstance) {
                csssRestrictionModalInstance.hide();
            }
        });
    }

    if (csssCancelAssignBtn) {
        csssCancelAssignBtn.addEventListener('click', function () {
            csssRestrictionConfirmed = false;
            if (csssCheckbox) {
                csssCheckbox.checked = false;
            }
        });
    }

    if (assignModalEl) {
        assignModalEl.addEventListener('hidden.bs.modal', function () {
            csssRestrictionConfirmed = false;
        });
    }

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
            assignForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                if (csssCheckbox && csssCheckbox.checked && !csssRestrictionConfirmed) {
                    if (csssRestrictionModalInstance) {
                        csssRestrictionModalInstance.show();
                    }
                    return;
                }

                const formData = new FormData(this);
                const userId = document.getElementById('assignUserId').value;
                const userRole = assignForm.dataset.userRole || '';
                const modules = [];
                this.querySelectorAll('input[name="modules[]"]:checked').forEach(c => modules.push(c.value));
                const universalSchoolId = formData.get('universal_school_id') || '';

                if (userRole === 'contributor') {
                    ['fire_safety', 'typhoon_flood', 'incident_checklist', 'hazard_mapping'].forEach(module => {
                        if (!modules.includes(module)) {
                            modules.push(module);
                        }
                    });
                }

                if (modules.length > 0 && !universalSchoolId) {
                    await Swal.fire({
                        title: 'School required',
                        text: 'This user needs to have a school first before module access can be assigned.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                const initialModules = JSON.parse(assignForm.dataset.initialModules || '[]');
                const removedModules = initialModules.filter(module => !modules.includes(module));

                if (removedModules.length) {
                    const moduleLabels = {
                        fire_safety: 'Fire Safety Compliance',
                        typhoon_flood: 'Evacuation Monitoring',
                        incident_checklist: 'Incident Checklist',
                        comprehensive_school_safety: 'Comprehensive School Safety',
                        hazard_mapping: 'Hazard Mapping',
                    };
                    const removedLabelList = removedModules.map(module => moduleLabels[module] || module).join(', ');
                    const confirmResult = await Swal.fire({
                        title: 'Confirm unassignment',
                        text: `This will remove access to: ${removedLabelList}. Do you want to continue?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, save changes',
                        cancelButtonText: 'Cancel'
                    });

                    if (!confirmResult.isConfirmed) {
                        return;
                    }
                }
                
                const data = {
                    modules: modules,
                    universal_school_id: universalSchoolId || null
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
            const universalSchoolSelect = document.getElementById('universalSchoolSelect');
            const csssCheckbox = document.getElementById('checkSS');
            const contributorNotice = document.getElementById('contributorModuleNotice');
            const defaultContributorModules = ['fire_safety', 'typhoon_flood', 'incident_checklist', 'hazard_mapping'];

            if (userIdInput) userIdInput.value = user.id;
            if (userNameSpan) userNameSpan.textContent = user.name;
            if (csssCheckbox) csssCheckbox.checked = false;
            csssRestrictionConfirmed = false;
            
            const isAdmin = user.role === 'admin';
            const isContributor = user.role === 'contributor';
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

                const moduleRow = c.closest('.list-group-item');
                if (moduleRow) {
                    moduleRow.classList.remove('d-none');
                }
            });

            // Set checks for non-admin users.
            if (!isAdmin) {
                const access = user.module_access || [];
                access.forEach(mod => {
                    const check = modalEl.querySelector(`.module-check[value="${mod}"]`);
                    if (check) {
                        check.checked = true;
                    }
                });
                if (assignForm) {
                    assignForm.dataset.initialModules = JSON.stringify(access);
                    assignForm.dataset.userRole = user.role || '';
                }
            } else if (assignForm) {
                assignForm.dataset.initialModules = JSON.stringify(['fire_safety', 'typhoon_flood', 'incident_checklist', 'comprehensive_school_safety', 'hazard_mapping']);
                assignForm.dataset.userRole = 'admin';
            }

            if (isContributor) {
                defaultContributorModules.forEach(module => {
                    const check = modalEl.querySelector(`.module-check[value="${module}"]`);
                    if (check) {
                        check.checked = true;
                        check.disabled = true;
                        const moduleRow = check.closest('.module-row');
                        if (moduleRow) moduleRow.classList.add('d-none');
                    }
                });

                ['checkSS'].forEach(id => {
                    const check = document.getElementById(id);
                    const moduleRow = check ? check.closest('.module-row') : null;
                    if (check) {
                        check.disabled = false;
                    }
                    if (moduleRow) {
                        moduleRow.classList.remove('d-none');
                    }
                });

                if (contributorNotice) {
                    contributorNotice.classList.remove('d-none');
                }
            } else if (contributorNotice) {
                contributorNotice.classList.add('d-none');
            }

            // If admin, hide save button or show "Admin has full access" message
            if (isAdmin) {
                if (saveBtn) saveBtn.classList.add('d-none');
            } else {
                if (saveBtn) saveBtn.classList.remove('d-none');
            }

            if (universalSchoolSelect) {
                const fallbackSchoolId = user.school_id || user.typhoon_school_id || user.incident_school_id || user.school_safety_id || "";
                universalSchoolSelect.value = fallbackSchoolId;
            }

            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        })
        .catch(err => {
            console.error('Error fetching user data:', err);
            alert('Error fetching user details: ' + err.message);
        });
}

let currentToggleUserId = null;

function toggleUserStatus(userId, currentState) {
    const isAdminView = {{ $isAdminView ? 'true' : 'false' }};
    if (!isAdminView) return;
    
    currentToggleUserId = userId;
    const action = currentState ? 'deactivate' : 'activate';
    
    const titleEl = document.getElementById('toggleStatusTitle');
    const msgEl = document.getElementById('toggleStatusMessage');
    
    if (titleEl) titleEl.innerText = action === 'deactivate' ? 'Deactivate User' : 'Activate User';
    if (msgEl) msgEl.innerText = `Are you sure you want to ${action} this user account?`;

    const toggleModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('toggleStatusModal'));
    toggleModal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    const confirmToggleStatusBtn = document.getElementById('confirmToggleStatusBtn');
    if (confirmToggleStatusBtn) {
        confirmToggleStatusBtn.addEventListener('click', function() {
            if (!currentToggleUserId) return;
            
            // disable button to prevent double submit
            confirmToggleStatusBtn.disabled = true;
            confirmToggleStatusBtn.innerText = 'Processing...';

            fetch("{{ route('users.index') }}/" + currentToggleUserId + "/toggle-status", {
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
                    confirmToggleStatusBtn.disabled = false;
                    confirmToggleStatusBtn.innerText = 'Confirm';
                }
            }).catch(err => {
                console.error('Error:', err);
                alert('Error toggling user status: ' + err.message);
                confirmToggleStatusBtn.disabled = false;
                confirmToggleStatusBtn.innerText = 'Confirm';
            });
        });
    }
});
</script>
@endpush
