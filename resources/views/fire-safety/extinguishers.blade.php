<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fire Extinguishers - Fire Safety</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --fire-red: #A8191F;
            --fire-dark-red: #8A1217;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        .top-nav {
            background-color: var(--fire-red);
            height: 60px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .sidebar {
            background-color: var(--fire-red);
            width: 250px;
            position: fixed;
            top: 60px;
            left: 0;
            bottom: 0;
            z-index: 1020;
            overflow-y: auto;
        }

        .main-content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 20px;
            min-height: calc(100vh - 60px);
            background-color: #f8f9fa;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9);
            padding: 12px 20px;
            display: flex;
            align-items: center;
        }

        .nav-link:hover, .nav-link.active {
            background-color: var(--fire-dark-red);
            color: white;
            text-decoration: none;
        }

        .nav-link.active {
            border-left: 4px solid white;
        }

        .nav-icon {
            width: 24px;
            margin-right: 10px;
            text-align: center;
        }

        .dashboard-card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .school-tabs {
            border-bottom: 2px solid #dee2e6;
        }

        .school-tab-btn {
            color: #495057;
            background-color: transparent;
            border: 1px solid transparent;
            border-top-left-radius: 0.25rem;
            border-top-right-radius: 0.25rem;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s;
            position: relative;
            margin-bottom: -2px;
        }

        .school-tab-btn:hover {
            color: white;
            background-color: #8A1217;
            border-color: #8A1217 #8A1217 #dee2e6;
        }

        .school-tab-btn.active {
            color: white !important;
            background-color: #8A1217 !important;
            border-color: #8A1217 #8A1217 #8A1217 !important;
            position: relative;
            z-index: 1;
        }

        .school-tab-btn:not(.active):not(:hover) {
            color: #495057;
            background-color: #f8f9fa;
            border-color: #dee2e6 #dee2e6 #dee2e6;
        }

        .no-data {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }

        .no-data i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #adb5bd;
        }
    </style>
    <style>
        .health-bar {
            height: 25px; /* Fatten/Large height */
            width: 100%;
            background-color: #e9ecef;
            border-radius: 12px;
            margin-top: 5px;
            overflow: hidden;
            position: relative;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.2);
        }
        .health-bar-fill {
            height: 100%;
            transition: width 0.3s ease;
        }
        .health-bar-text {
            position: absolute;
            width: 100%;
            text-align: center;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 11px;
            font-weight: bold;
            color: #000;
            text-shadow: 0 0 2px rgba(255,255,255,0.8);
        }
        .health-good { background-color: #28a745; } /* OK */
        .health-warning { background-color: #ffc107; } /* For Refill */
        .health-danger { background-color: #dc3545; } /* Empty/Missing */

        /* SweetAlert2 Custom Styling */
        .swal2-popup {
            border-radius: 15px !important;
        }
        .swal2-styled.swal2-confirm {
            background-color: var(--fire-red) !important;
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="top-nav">
        <div class="container-fluid h-100">
            <div class="row h-100 align-items-center">
                <div class="col-auto">
                    <a href="{{ route('fire-safety.dashboard') }}" class="text-white text-decoration-none">
                        <i class="fas fa-arrow-left me-2"></i>
                        <i class="fas fa-fire me-2"></i>
                        <span class="fw-bold">Fire Safety Checklist System</span>
                    </a>
                </div>

                <div class="col text-center">
                    <h4 class="text-white mb-0">Fire Extinguishers (Room-Based)</h4>
                </div>

                <div class="col-auto">
                    <div class="d-flex align-items-center">
                        <!-- Notifications -->
                        <div class="dropdown me-3">
                            <a href="#" class="text-white position-relative" data-bs-toggle="dropdown">
                                <i class="fas fa-bell fa-lg"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    0
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <h6 class="dropdown-header">Notifications</h6>
                                <div class="dropdown-item text-muted">No new notifications</div>
                            </div>
                        </div>

                        <div class="dropdown">
                            <a href="#" class="text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle fa-lg me-2"></i>
                                <span>{{ Auth::user()->name ?? 'User' }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('fire-safety.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                   <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="sidebar">
        <div class="p-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('fire-safety.dashboard') }}">
                        <span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('fire-safety.buildings') }}">
                        <span class="nav-icon"><i class="fas fa-building"></i></span>
                        <span>Buildings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('fire-safety.alarm-systems') }}">
                        <span class="nav-icon"><i class="fas fa-bell"></i></span>
                        <span>Alarm Systems</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('fire-safety.extinguishers') }}">
                        <span class="nav-icon"><i class="fas fa-fire-extinguisher"></i></span>
                        <span>Fire Extinguishers & Rooms</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('fire-safety.evacuation-plans') }}">
                        <span class="nav-icon"><i class="fas fa-map-signs"></i></span>
                        <span>Evacuation Plans</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('fire-safety.customization') }}">
                        <span class="nav-icon"><i class="fas fa-cog"></i></span>
                        <span>Customization</span>
                    </a>
                </li>
            </ul>
            <hr class="bg-white my-4">
        </div>
    </div>

    <!-- Modals (Moved to top for better visibility and to avoid stacking context issues) -->
    
    <!-- Update Extinguisher Status Modal -->
    <div class="modal fade" id="updateExtModal" tabindex="-1" style="z-index: 1060;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Update Extinguisher</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="updateExtForm">
                        @csrf
                        <input type="hidden" id="updateExtId" name="extinguisher_id">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Extinguisher Code</label>
                            <input type="text" class="form-control bg-light" id="updateExtCode" readonly>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Status *</label>
                                <select class="form-control" name="status" id="updateExtStatus" required onchange="handleUpdateStatusChange()">
                                    <option value="active">OK (Active)</option>
                                    <option value="maintenance">For Refill</option>
                                    <option value="expired">Empty</option>
                                    <option value="missing">Missing</option>
                                    <option value="purchase">For Purchase</option>
                                    <option value="decommissioned">Decommissioned</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Pressure (0-100%) *</label>
                                <input type="number" class="form-control" name="pressure_level" id="updateExtPressure" min="0" max="100" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Notes / Remarks *</label>
                            <textarea class="form-control" name="notes" id="updateExtNotes" rows="3" placeholder="Reason for update..." required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <!-- Remove button (initially hidden, shown via JS if status is deccomissioned) -->
                    <button type="button" class="btn btn-outline-danger" id="removeExtBtn" style="display: none;" onclick="showExtRemovalReason()">
                        <i class="fas fa-trash-alt me-2"></i>Remove
                    </button>
                    <button type="button" class="btn btn-primary" onclick="saveExtinguisherStatus()">
                        <i class="fas fa-save me-2"></i>Update Status
                    </button>
                </div>
                <!-- Reason for Removal section (initially hidden) -->
                <div class="card-footer bg-light border-top-0 d-none" id="extRemovalReasonSection">
                    <div class="p-3">
                        <label class="form-label fw-bold text-danger">Reason for Removal *</label>
                        <textarea class="form-control border-danger" id="extRemovalReason" rows="2" placeholder="State reason for decommissioning and removal..."></textarea>
                        <div class="mt-2 text-end">
                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmRemoveExtinguisher()">
                                <i class="fas fa-check me-2"></i>Yes, Remove It!
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inspect & Update Room Modal -->
    <div class="modal fade" id="updateRoomModal" tabindex="-1" style="z-index: 1060;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title"><i class="fas fa-search-plus me-2"></i>Inspect & Update Room</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="updateRoomForm">
                        @csrf
                        <input type="hidden" id="updateRoomId" name="room_id">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Room Code</label>
                                <input type="text" class="form-control" name="room_code" id="updateRoomCode" placeholder="e.g., Rm-101">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Floor Level</label>
                                <input type="text" class="form-control bg-light" id="updateRoomFloor" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">Update Room Name (Optional)</label>
                            <input type="text" class="form-control" name="room_name" id="updateRoomName" placeholder="Leave blank to keep current name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nearest Extinguisher Room</label>
                            <select class="form-control" name="nearest_extinguisher_room_id" id="updateRoomNearest">
                                <option value="">None / Self-Covered</option>
                            </select>
                            <div class="form-text small">Select the room that houses the extinguisher covering this room. Only rooms on the same floor with coverage capacity are shown.</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveRoomUpdate()">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Room Modal -->
    <div class="modal fade" id="addRoomModal" tabindex="-1" style="z-index: 1060;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title"><i class="fas fa-door-open me-2"></i>Add Room</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addRoomForm">
                        @csrf
                        <input type="hidden" name="school_id" id="roomSchoolId">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Building *</label>
                            <select class="form-control" name="building_id" id="roomBuildingSelect" required>
                                <option value="">Select Building</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Room Code</label>
                                <input type="text" class="form-control" name="room_code" placeholder="e.g., Rm-101">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Floor No.</label>
                                <select class="form-control" name="floor_no" id="roomFloorSelect" required disabled>
                                    <option value="">Select Building First</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Room Name *</label>
                            <input type="text" class="form-control" name="room_name" placeholder="e.g., Room 101, Science Lab" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Room Type *</label>
                            <select class="form-control" name="room_type_config_id" id="room_type_select" required onchange="updateRoomPriority()">
                                <option value="">Select room type</option>
                                @foreach(($roomTypes ?? collect()) as $rt)
                                    @php
                                        $p = ($calculatedPriorities ?? collect())->firstWhere('id', $rt->parent_id);
                                    @endphp
                                    <option value="{{ $rt->id }}"
                                            data-priority-label="{{ $p->name ?? '' }}"
                                            data-max-rooms="{{ $p->max_rooms_covered ?? '' }}">
                                        {{ $rt->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Calculated Priority</label>
                            <input type="text" class="form-control bg-light" id="room_priority" readonly value="">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveRoom()">
                        <i class="fas fa-save me-2"></i>Save Room
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Extinguisher Modal -->
    <div class="modal fade" id="addExtModal" tabindex="-1" style="z-index: 1060;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add Extinguisher (Room-Based)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addExtForm">
                        @csrf
                        <input type="hidden" name="school_id" id="extSchoolId">

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Code *</label>
                                <input type="text" class="form-control" name="code" placeholder="e.g., EXT-001" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Type *</label>
                                <select class="form-control" name="type" id="ext_type_select" required onchange="handleExtTypeChange()">
                                    <option value="ABC">ABC (Dry Chemical)</option>
                                    <option value="CO2">CO2</option>
                                    <option value="Water">Water</option>
                                    <option value="Foam">Foam</option>
                                    <option value="Other">Other, Please Specify...</option>
                                </select>
                            </div>
                            <!-- Added Specify Other Type Field -->
                            <div class="col-md-3 mb-3 d-none" id="otherTypeContainer">
                                <label class="form-label fw-bold text-danger">Specify Type *</label>
                                <input type="text" class="form-control border-danger" id="other_type_input" placeholder="Enter type...">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Status *</label>
                                <select class="form-control" name="status" id="addExtStatus" required onchange="handleAddStatusChange()">
                                    <option value="active">Active</option>
                                    <option value="maintenance">For Refill</option>
                                    <option value="expired">Empty</option>
                                    <option value="missing">Missing</option>
                                    <option value="purchase">For Purchase</option>
                                    <option value="decommissioned">Decommissioned</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Pressure (0-100%)</label>
                                <input type="number" class="form-control" name="pressure_level" id="addExtPressure" min="0" max="100" value="100" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Building *</label>
                                <select class="form-control" name="building_id" id="extBuildingSelect" required onchange="handleExtBuildingChange()">
                                    <option value="">Select Building</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Floor</label>
                                <select class="form-control" id="extFloorSelect" disabled onchange="handleExtFloorChange()">
                                    <option value="">Select Floor</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Center Room *</label>
                                <select class="form-control" name="room_id" id="centerRoomSelect" required onchange="handleCenterRoomChange()">
                                    <option value="">Select Center Room</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Covered Rooms (Select up to 3) *</label>
                            <select class="form-control" id="coveredRoomsSelect" name="covered_room_ids[]" multiple size="5" required>
                            </select>
                            <div class="form-text small">Use Ctrl/Cmd + Click to select multiple. Laboratory rule applies.</div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Date Checked *</label>
                                <input type="date" class="form-control" name="date_checked" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Evaluation Result *</label>
                                <select class="form-control" name="evaluation_result" required>
                                    <option value="Passed">Passed</option>
                                    <option value="Failed">Failed</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Remarks</label>
                                <input type="text" class="form-control" name="remarks" placeholder="Optional remarks...">
                            </div>
                        </div>

                        <div class="alert alert-info mb-0 py-2 small">
                            <i class="fas fa-info-circle me-2"></i><strong>Note:</strong> Laboratories can cover themselves + 1 clinic/auxiliary room max.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveExtinguisher()">
                        <i class="fas fa-save me-2"></i>Save Extinguisher
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Fire Extinguisher's History Modal -->
    <div class="modal fade" id="extHistoryModal" tabindex="-1" style="z-index: 1060;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #6c757d; color: white;">
                    <h5 class="modal-title"><i class="fas fa-history me-2"></i>Fire Extinguisher's History</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm" id="extHistoryTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Date Removed</th>
                                    <th>Code</th>
                                    <th>Type</th>
                                    <th>Last Location</th>
                                    <th>Reason to be removed</th>
                                    <th>Last Recorded Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data populated via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="main-content">
        @if($schools->isEmpty())
            <div class="row">
                <div class="col-12">
                    <div class="card dashboard-card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-school fa-4x text-muted mb-4"></i>
                            <h4 class="text-muted mb-3">No Schools Found</h4>
                            <p class="text-muted mb-4">You need to add a school under inspection first.</p>
                            <a href="{{ route('fire-safety.dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i> Go to Dashboard to Add School
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- School Tabs -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card dashboard-card">
                        <div class="card-body p-0">
                            <div class="school-tabs">
                                <nav>
                                    <div class="nav nav-tabs border-0" id="schoolTab" role="tablist">
                                        @foreach($schools as $school)
                                            <button class="nav-link school-tab-btn {{ $loop->first ? 'active' : '' }}"
                                                    id="school-tab-{{ $school->id }}"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#school-{{ $school->id }}"
                                                    type="button"
                                                    role="tab"
                                                    aria-controls="school-{{ $school->id }}"
                                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                                    data-school-id="{{ $school->id }}">
                                                {{ $school->school_name }}
                                            </button>
                                        @endforeach
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="tab-content" id="schoolTabContent">
                @foreach($schools as $school)
                    @php
                        $actualTotalRooms = $school->buildings->sum('rooms');
                        $requiredExtinguishers = $school->buildings->sum('required_extinguishers_count');
                        $allRoomsCollection = $school->buildings->flatMap(fn ($b) => $b->actualRooms);
                        $allExts = $school->buildings->flatMap(fn ($b) => $b->fireExtinguishers);
                        $coveredRoomIds = $allExts->flatMap(fn ($e) => $e->coveredRooms->pluck('id'))->unique();
                        $uncoveredRoomsCount = max(0, $allRoomsCollection->count() - $coveredRoomIds->count()); // Based on created rooms
                        $labRooms = $allRoomsCollection->where('room_type', 'laboratory');
                        $labsCovered = $labRooms->filter(fn ($r) => $coveredRoomIds->contains($r->id))->count();

                        // New Metrics
                        $evaluationCount = $allExts->where('status', 'active')->count();
                        $evaluationPassed = $requiredExtinguishers > 0 && $evaluationCount >= $requiredExtinguishers;
                        $compliancePercent = $actualTotalRooms > 0 ? round(($coveredRoomIds->count() / $actualTotalRooms) * 100, 1) : 0;
                    @endphp

                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="school-{{ $school->id }}">
                        <!-- Summary -->
                        <div class="row mb-4">
                            <!-- Total Rooms -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card dashboard-card h-100 border-left-primary">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Rooms</div>
                                                <div class="h2 mb-0 fw-bold text-gray-800">{{ $allRoomsCollection->count() }}</div>
                                            </div>
                                            <i class="fas fa-door-closed fa-2x text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Room Coverage (Combined) -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card dashboard-card h-100 border-left-success">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="text-xs fw-bold text-dark text-uppercase mb-1">Room Coverage</div>
                                                <div class="mb-0 fw-bold">
                                                    <span class="text-success">{{ $coveredRoomIds->count() }} Covered<i class="bi bi-check-circle-fill ms-1"></i></span>
                                                    <span class="text-muted mx-1">|</span>
                                                    <span class="text-danger">{{ $uncoveredRoomsCount }} Uncovered X</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Coverage Compliance (%) -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card dashboard-card h-100 border-left-info">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="text-xs fw-bold text-info text-uppercase mb-1">School Coverage Compliance</div>
                                                <div class="h2 mb-0 fw-bold text-gray-800">
                                                    {{ $compliancePercent }}%
                                                </div>
                                            </div>
                                            <i class="fas fa-percent fa-2x text-info"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Extinguisher Status Ratio -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card dashboard-card h-100 border-left-warning">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="text-xs fw-bold text-warning text-uppercase mb-1">School's Fire Extinguisher Evaluation Result</div>
                                                <div class="h2 mb-0 fw-bold text-gray-800">
                                                    {{ $evaluationCount }} / {{ $requiredExtinguishers }}
                                                    <span class="text-xs text-muted fw-normal">{{ $evaluationPassed ? 'Passed' : 'Failed' }}</span>
                                                </div>
                                            </div>
                                            <i class="fas fa-clipboard-check fa-2x text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($labRooms->count() > 0 && $labsCovered < $labRooms->count())
                            <div class="alert alert-warning">
                                <strong>Laboratory coverage:</strong>
                                {{ $labsCovered }}/{{ $labRooms->count() }} Dedicated rooms currently have an assigned extinguisher coverage.
                            </div>
                        @endif

                        <!-- Buildings -->
                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="card dashboard-card">
                                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                        <h6 class="m-0 fw-bold text-primary">
                                            <i class="fas fa-list me-2"></i> Room-Based Extinguishers - {{ $school->school_name }}
                                        </h6>
                                        <div>
                                            @if(auth()->user()->role === 'admin')
                                            <button class="btn btn-outline-primary btn-sm me-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#addRoomModal"
                                                    data-school-id="{{ $school->id }}">
                                                <i class="fas fa-door-open me-2"></i> Add Room
                                            </button>
                                            <button class="btn btn-primary btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#addExtModal"
                                                    data-school-id="{{ $school->id }}">
                                                <i class="fas fa-plus me-2"></i> Add Extinguisher
                                            </button>
                                            <button class="btn btn-sm ms-2" 
                                                    style="background-color: #e9ecef; color: #495057; border: 1px solid #ced4da;"
                                                    onclick="openExtHistoryModal({{ $school->id }})">
                                                <i class="fas fa-history me-1"></i> Removed Fire Extinguisher
                                            </button>
                                            <a href="{{ route('fire-safety.report.extinguisher-details', $school->id) }}" target="_blank"
                                                    class="btn btn-sm ms-2" 
                                                    style="background-color: #e9ecef; color: #495057; border: 1px solid #ced4da;">
                                                <i class="fas fa-print me-1"></i> Print Fire Extinguisher Details
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @if($school->buildings->isEmpty())
                                            <div class="no-data">
                                                <i class="fas fa-building"></i>
                                                <h5>No Buildings Found</h5>
                                                <p class="text-muted mb-0">Add buildings first in the Buildings page.</p>
                                            </div>
                                        @else
                                            <div class="accordion" id="buildingAccordion-{{ $school->id }}">
                                                @foreach($school->buildings as $building)
                                                    @php
                                                        $coverageMap = [];
                                                        foreach ($building->fireExtinguishers as $ext) {
                                                            foreach ($ext->coveredRooms as $r) {
                                                                $coverageMap[$r->id] = $ext;
                                                            }
                                                        }
                                                    @endphp

                                                    <div class="accordion-item mb-2">
                                                        <h2 class="accordion-header" id="heading-{{ $school->id }}-{{ $building->id }}">
                                                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#collapse-{{ $school->id }}-{{ $building->id }}"
                                                                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                                                    aria-controls="collapse-{{ $school->id }}-{{ $building->id }}">
                                                                <strong class="me-2">{{ $building->building_no }}</strong>
                                                                <span class="text-muted">{{ $building->building_name }}</span>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse-{{ $school->id }}-{{ $building->id }}"
                                                             class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                                             aria-labelledby="heading-{{ $school->id }}-{{ $building->id }}"
                                                             data-bs-parent="#buildingAccordion-{{ $school->id }}">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-lg-7 mb-4">
                                                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                                                            <h6 class="fw-bold mb-0"><i class="fas fa-door-closed me-2"></i>Rooms</h6>
                                                                            <div class="bg-light border rounded px-3 py-1 shadow-sm d-flex align-items-center">
                                                                                <span class="text-muted small fw-bold me-2 text-uppercase" style="font-size: 0.7rem;">Current / Total Rooms:</span>
                                                                                <span class="fw-bold text-primary">{{ $building->actualRooms->count() }} / {{ $building->rooms }}</span>
                                                                            </div>
                                                                        </div>
                                                                        @if($building->actualRooms->isEmpty())
                                                                            <div class="alert alert-secondary mb-0">
                                                                                No rooms defined yet for this building.
                                                                            </div>
                                                                        @else
                                                                            <div class="table-responsive">
                                                                                <table class="table table-sm table-hover align-middle">
                                                                                    <thead class="table-light">
                                                                                        <tr>
                                                                                            <th>Room</th>
                                                                                            <th>Type</th>
                                                                                            <th>Floor</th>
                                                                                            <th>Covered By</th>
                                                                                            <th class="text-end">Action</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @foreach($building->actualRooms as $room)
                                                                                            @php $ext = $coverageMap[$room->id] ?? null; @endphp
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <div class="fw-semibold">{{ $room->room_name }}</div>
                                                                                                    <div class="text-muted small">{{ $room->room_code }}</div>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <span class="badge bg-{{ $room->room_type === 'laboratory' ? 'danger' : ($room->room_type === 'auxiliary' ? 'info' : 'secondary') }}">
                                                                                                        {{ ucfirst($room->room_type) }}
                                                                                                    </span>
                                                                                                </td>
                                                                                                <td>{{ $room->floor_no ?? 'â€”' }}</td>
                                                                                                <td>
                                                                                                    @if($ext)
                                                                                                        <span class="badge bg-success">{{ $ext->code }}</span>
                                                                                                        @if($ext->room_id === $room->id)
                                                                                                            <span class="badge bg-primary">Center</span>
                                                                                                        @endif
                                                                                                    @else
                                                                                                        <span class="badge bg-warning text-dark">Uncovered</span>
                                                                                                    @endif
                                                                                                </td>
                                                                                                <td class="text-end">
                                                                                                    <button class="btn btn-sm btn-outline-primary" onclick="openUpdateRoomModal({{ $room->id }})">
                                                                                                        <i class="fas fa-search-plus me-1"></i> Inspect & Update
                                                                                                    </button>
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        @endif
                                                                    </div>

                                                                    <div class="col-lg-5 mb-4">
                                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                                            <h6 class="fw-bold mb-0"><i class="fas fa-fire-extinguisher me-2"></i>Extinguishers & Details</h6>
                                                                            @php
                                                                                $bldgExts = $building->fireExtinguishers;
                                                                                $bldgActive = $bldgExts->where('status', 'active')->count();
                                                                                $required = $building->required_extinguishers_count;
                                                                                $evalText = $bldgActive >= $required ? 'Passed' : 'Failed';
                                                                                $evalColor = $evalText === 'Passed' ? 'success' : 'danger';
                                                                            @endphp
                                                                            <span class="badge bg-{{ $evalColor }}">Evaluation Result: {{ $evalText }} ({{ $bldgActive }}/{{ $required }})</span>
                                                                        </div>
                                                                        @if($building->fireExtinguishers->isEmpty())
                                                                            <div class="alert alert-secondary mb-0">
                                                                                No extinguishers recorded yet for this building.
                                                                            </div>
                                                                        @else
                                            <div class="table-responsive">
                                                @foreach($building->fireExtinguishers as $ext)
                                                    @php
                                                        $pressure = $ext->pressure_level ?? 100;
                                                        $statusLabel = 'OK';
                                                        $healthClass = 'health-good';
                                                        $badgeClass = 'success';

                                                            if ($ext->status === 'maintenance') {
                                                                $statusLabel = 'For Refill';
                                                                $healthClass = 'health-warning';
                                                                $badgeClass = 'warning';
                                                            } elseif ($ext->status === 'expired' || $ext->status === 'missing') {
                                                                $statusLabel = $ext->status === 'expired' ? 'Empty' : 'Missing';
                                                                $healthClass = 'health-danger';
                                                                $badgeClass = 'danger';
                                                            } elseif ($ext->status === 'purchase') {
                                                                $statusLabel = 'For Purchase';
                                                                $healthClass = 'health-secondary';
                                                                $badgeClass = 'dark';
                                                            } elseif ($ext->status === 'decommissioned') {
                                                                $statusLabel = 'Decommissioned';
                                                                $healthClass = 'health-danger';
                                                                $badgeClass = 'danger';
                                                            }
                                                    @endphp
                                                    <div class="mb-4 border rounded shadow-sm overflow-hidden bg-white">
                                                        <table class="table table-bordered table-sm mb-0">
                                                            <!-- Row 1 -->
                                                            <tr>
                                                                <td colspan="3" class="align-middle ps-3 py-2">
                                                                    <strong>Status & Pressure:</strong>
                                                                    <span class="badge bg-{{ $badgeClass }} ms-1">{{ $statusLabel }}</span>
                                                                </td>
                                                                <td colspan="2" class="align-middle ps-3 py-2">
                                                                    <strong>Type:</strong>
                                                                    <span class="badge bg-secondary ms-1">{{ $ext->type }}</span>
                                                                </td>
                                                            </tr>
                                                            <!-- Row 2 -->
                                                            <tr>
                                                                <td colspan="5" class="p-3">
                                                                    <div class="health-bar" style="height: 30px; background-color: #e9ecef; border-radius: 4px;" title="Pressure: {{ $pressure }}%">
                                                                        <div class="health-bar-fill {{ $healthClass }}" style="width: {{ $pressure }}%; border-radius: 4px;"></div>
                                                                        <div class="health-bar-text" style="line-height: 30px; font-size: 14px; font-weight: bold; color: #333;">{{ $pressure }}%</div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <!-- Row 4 -->
                                                            <tr class="table-light text-center small">
                                                                <th>Code</th>
                                                                <th>As Of</th>
                                                                <th>Location</th>
                                                                <th>Covering</th>
                                                                <th>Action</th>
                                                            </tr>
                                                            <!-- Row 5 -->
                                                            <tr class="text-center align-middle">
                                                                <td class="fw-bold">{{ $ext->code }}</td>
                                                                <td>{{ $ext->date_checked ? \Carbon\Carbon::parse($ext->date_checked)->format('m-d-Y') : 'N/A' }}</td>
                                                                <td>{{ $ext->centerRoom->room_name ?? 'N/A' }}</td>
                                                                <td>{{ $ext->coveredRooms->count() }} Rooms</td>
                                                                <td class="p-2">
                                                                    <button class="btn btn-sm btn-primary w-100"
                                                                            onclick="openUpdateModal({{ $ext->id }}, '{{ $ext->code }}', '{{ $ext->status }}', {{ $pressure }})">
                                                                        <i class="fas fa-edit me-1"></i> Update
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                @endforeach
                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Inspections -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card dashboard-card">
                                    <div class="card-header py-3 d-flex justify-content-between align-items-center bg-light">
                                        <h6 class="m-0 fw-bold text-dark">
                                            <i class="fas fa-history me-2"></i> Recent Inspections - {{ $school->school_name }}
                                        </h6>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="loadRecentInspections({{ $school->id }})">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped" id="inspectionsTable-{{ $school->id }}">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Extinguisher Code</th>
                                                        <th>Location</th>
                                                        <th>Inspector</th>
                                                        <th>Status</th>
                                                        <th>Pressure</th>
                                                        <th>Notes / Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr><td colspan="6" class="text-center text-muted">Loading...</td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Obsolete nested modals section removed -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const schools = @json($schools);
        let currentBuildingRooms = [];

        function csrfToken() {
            return document.querySelector('meta[name="csrf-token"]').content;
        }

        // Handle status change in Add Extinguisher modal - enforce pressure ranges
        function handleAddStatusChange() {
            const status = document.getElementById('addExtStatus').value;
            const pressureInput = document.getElementById('addExtPressure');
            updatePressureConstraints(status, pressureInput);
        }

        // Handle status change in Update Extinguisher modal - enforce pressure ranges
        function handleUpdateStatusChange() {
            const status = document.getElementById('updateExtStatus').value;
            const pressureInput = document.getElementById('updateExtPressure');
            updatePressureConstraints(status, pressureInput);
        }

        function updatePressureConstraints(status, pressureInput) {
            switch(status) {
                case 'active':
                    pressureInput.min = 70;
                    pressureInput.max = 100;
                    // Reset if out of range, or clamp? User said "range of percentage are only 70-100%"
                    if (pressureInput.value < 70) pressureInput.value = 70;
                    if (pressureInput.value > 100) pressureInput.value = 100;
                    break;
                case 'maintenance': // For Refill
                    pressureInput.min = 20;
                    pressureInput.max = 69;
                    if (pressureInput.value < 20) pressureInput.value = 20;
                    if (pressureInput.value > 69) pressureInput.value = 69;
                    break;
                case 'expired': // Empty
                    pressureInput.min = 0;
                    pressureInput.max = 19;
                    if (pressureInput.value > 19) pressureInput.value = 19;
                    break;
                case 'missing':
                case 'purchase':
                case 'decommissioned':
                    pressureInput.min = 0;
                    pressureInput.max = 100;
                    break;
            }
        }

        function setTodayIfEmpty(dateInput) {
            if (!dateInput.value) {
                dateInput.value = new Date().toISOString().split('T')[0];
            }
        }

        // Populate building selects for a school
        function populateBuildingsForSchool(schoolId) {
            const school = schools.find(s => String(s.id) === String(schoolId));
            const buildings = (school && school.buildings) ? school.buildings : [];

            const roomBuildingSelect = document.getElementById('roomBuildingSelect');
            const extBuildingSelect = document.getElementById('extBuildingSelect');

            roomBuildingSelect.innerHTML = '<option value="">Select Building</option>';
            extBuildingSelect.innerHTML = '<option value="">Select Building</option>';

            buildings.forEach(b => {
                const opt1 = document.createElement('option');
                opt1.value = b.id;
                opt1.textContent = b.building_no + (b.building_name ? ` (${b.building_name})` : '');
                // Store floors and type for logic
                opt1.dataset.floors = b.floors || 0;
                opt1.dataset.type = b.building_type || '';
                opt1.dataset.rooms_limit = b.rooms || 0;
                roomBuildingSelect.appendChild(opt1);

                const opt2 = document.createElement('option');
                opt2.value = b.id;
                opt2.textContent = b.building_no + (b.building_name ? ` (${b.building_name})` : '');
                opt2.dataset.floors = b.floors || 0;
                extBuildingSelect.appendChild(opt2);
            });
        }

        // Handle Building Selection in Add Room (Populate Floors & Check Type & Check Rules)
        document.getElementById('roomBuildingSelect').addEventListener('change', async function() {
            const select = this;
            const floorSelect = document.getElementById('roomFloorSelect');
            floorSelect.innerHTML = '<option value="">Select Floor</option>';
            floorSelect.disabled = true;

            const option = select.options[select.selectedIndex];
            if (!option || !option.value) return;

            const buildingId = option.value;
            const type = option.dataset.type;
            const totalRequiredRooms = parseInt(option.dataset.rooms_limit) || parseInt(schools.flatMap(s => s.buildings).find(b => b.id == buildingId)?.rooms) || 0;
            const totalFloors = parseInt(option.dataset.floors) || 1;

            // Restriction for Gymnasium and Cafeteria
            if (type.toLowerCase() === 'gymnasium' || type.toLowerCase() === 'cafeteria or canteens') {
                Swal.fire({
                    title: 'Building Restriction',
                    text: 'Gymnasium & Cafeteria buildings have only 1 room. You cannot add more rooms to them.',
                    icon: 'warning'
                });
                select.value = ""; // Reset
                return;
            }

            try {
                // Fetch current rooms to calculate distribution
                const resp = await fetch(`/fire-safety/rooms/${buildingId}`);
                const existingRooms = await resp.json();

                const currentTotalCount = existingRooms.length;
                const roomsByFloor = {};
                for (let i = 1; i <= totalFloors; i++) {
                    roomsByFloor[i] = existingRooms.filter(r => r.floor_no == i).length;
                }

                const emptyFloorsCount = Object.values(roomsByFloor).filter(c => c === 0).length;
                const remainingSlots = totalRequiredRooms - currentTotalCount;

                floorSelect.disabled = false;
                const getOrdinal = (n) => {
                    const s = ["th", "st", "nd", "rd"];
                    const v = n % 100;
                    return n + (s[(v - 20) % 10] || s[v] || s[0]);
                };

                for (let i = 1; i <= totalFloors; i++) {
                    const floorIsEmpty = (roomsByFloor[i] === 0 || !roomsByFloor[i]);
                    const emptyOtherFloors = floorIsEmpty ? (emptyFloorsCount - 1) : emptyFloorsCount;

                    // Logic: If we add to this floor, will we have enough slots left for all other empty floors?
                    // Needed: emptyOtherFloors slots.
                    // Available if we take one slot now: remainingSlots - 1. (Wait, remainingSlots is totalRequiredRooms - currentTotalCount)
                    if ((remainingSlots - 1) >= emptyOtherFloors) {
                        const opt = document.createElement('option');
                        opt.value = i;
                        opt.textContent = getOrdinal(i) + " Floor" + (floorIsEmpty ? " (Empty)" : "");
                        floorSelect.appendChild(opt);
                    }
                }

                if (floorSelect.options.length <= 1) {
                    Swal.fire('Limit Reached', 'No more rooms can be added without violating the minimum floor requirement or building room limit.', 'warning');
                    floorSelect.disabled = true;
                }

            } catch (e) {
                console.error(e);
                floorSelect.disabled = false; // Fallback
            }
        });

        // Hook modal open events to set school_id and populate buildings
        document.getElementById('addRoomModal').addEventListener('show.bs.modal', function (event) {
            const btn = event.relatedTarget;
            const schoolId = btn?.getAttribute('data-school-id');
            document.getElementById('roomSchoolId').value = schoolId || '';
            populateBuildingsForSchool(schoolId);
        });

        document.getElementById('addExtModal').addEventListener('show.bs.modal', function (event) {
            const btn = event.relatedTarget;
            const schoolId = btn?.getAttribute('data-school-id');
            document.getElementById('extSchoolId').value = schoolId || '';
            populateBuildingsForSchool(schoolId);

            // reset room selects
            document.getElementById('centerRoomSelect').innerHTML = '<option value="">Select Center Room</option>';
            document.getElementById('coveredRoomsSelect').innerHTML = '';

            setTodayIfEmpty(document.querySelector('#addExtForm input[name="date_checked"]'));
        });

        async function saveRoom() {
            const form = document.getElementById('addRoomForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);

            try {
                const resp = await fetch(`{{ route('fire-safety.room.store') }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken(),
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await resp.json();
                if (!resp.ok || !data.success) {
                    Swal.fire('Error', data.message || 'Failed to add room', 'error');
                    return;
                }

                Swal.fire('Success', 'Room added successfully!', 'success').then(() => {
                    location.reload();
                });
            } catch (e) {
                console.error(e);
                Swal.fire('Error', 'Failed to add room. Please try again.', 'error');
            }
        }

        // inspectRoom function replaced by openUpdateRoomModal below

        // Room Inspection logic moved to the end of script block for cleaner organization


        function updateRoomPriority() {
            const typeSelect = document.getElementById('room_type_select');
            const priorityInput = document.getElementById('room_priority');
            const opt = typeSelect?.selectedOptions?.[0];
            const label = opt?.dataset?.priorityLabel || '';
            const maxRooms = opt?.dataset?.maxRooms || '';
            if (label) {
                priorityInput.value = maxRooms ? `${label} (Up to ${maxRooms} rooms)` : label;
            } else {
                priorityInput.value = '';
            }
        }

        async function handleExtBuildingChange() {
            const buildingSelect = document.getElementById('extBuildingSelect');
            const floorSelect = document.getElementById('extFloorSelect');
            const centerSelect = document.getElementById('centerRoomSelect');
            const coveredSelect = document.getElementById('coveredRoomsSelect');

            const buildingId = buildingSelect.value;

            // Reset selects
            floorSelect.innerHTML = '<option value="">Select Floor</option>';
            centerSelect.innerHTML = '<option value="">Select Center Room</option>';
            coveredSelect.innerHTML = '';

            if (!buildingId) {
                floorSelect.disabled = true;
                return;
            }

            // Populate Floors
            const selectedOption = buildingSelect.options[buildingSelect.selectedIndex];
            const floors = parseInt(selectedOption.dataset.floors) || 1;

            for (let i = 1; i <= floors; i++) {
                const opt = document.createElement('option');
                opt.value = i;
                opt.textContent = `Floor ${i}`;
                floorSelect.appendChild(opt);
            }
            floorSelect.disabled = false;

            // Fetch and store rooms
            try {
                const resp = await fetch(`/fire-safety/rooms/${buildingId}`, {
                    headers: { 'Accept': 'application/json' }
                });
                currentBuildingRooms = await resp.json();

                // If user hasn't selected a floor yet, we don't show rooms.
                // Or should we? User request said "depending on what floor was chosen... show the rooms".
            } catch (e) {
                console.error(e);
                Swal.fire('Error', 'Failed to load rooms.', 'error');
            }
        }

        function handleExtFloorChange() {
            const floorSelect = document.getElementById('extFloorSelect');
            const centerSelect = document.getElementById('centerRoomSelect');
            const coveredSelect = document.getElementById('coveredRoomsSelect');

            const selectedFloor = floorSelect.value;

            centerSelect.innerHTML = '<option value="">Select Center Room</option>';
            coveredSelect.innerHTML = '';

            if (!selectedFloor) return;

            // Filter rooms by floor
            const filteredRooms = currentBuildingRooms.filter(r => String(r.floor_no) === String(selectedFloor));

            filteredRooms.forEach(r => {
                const label = `${r.room_name}${r.room_code ? ' (' + r.room_code + ')' : ''} - ${r.room_type}`;

                const optCenter = document.createElement('option');
                optCenter.value = r.id;
                optCenter.textContent = label;
                optCenter.dataset.roomType = r.room_type;
                centerSelect.appendChild(optCenter);

                const optCovered = document.createElement('option');
                optCovered.value = r.id;
                optCovered.textContent = label;
                optCovered.dataset.roomType = r.room_type;
                coveredSelect.appendChild(optCovered);
            });
        }

        async function handleExtTypeChange() {
            const select = document.getElementById('ext_type_select');
            const otherContainer = document.getElementById('otherTypeContainer');
            const otherInput = document.getElementById('other_type_input');

            if (select.value === 'Other') {
                otherContainer.classList.remove('d-none');
                otherInput.required = true;
                otherInput.focus();
            } else {
                otherContainer.classList.add('d-none');
                otherInput.required = false;
                otherInput.value = '';
            }
        }

        function handleCenterRoomChange() {
            const centerSelect = document.getElementById('centerRoomSelect');
            const coveredSelect = document.getElementById('coveredRoomsSelect');
            const centerId = centerSelect.value;
            const centerType = centerSelect.selectedOptions[0]?.dataset?.roomType;

            // auto-select center room in covered rooms
            Array.from(coveredSelect.options).forEach(o => {
                if (String(o.value) === String(centerId)) {
                    o.selected = true;
                }
            });

            // If center is laboratory: allow only 2 rooms total and only auxiliary can be the other
            if (centerType === 'laboratory') {
                Array.from(coveredSelect.options).forEach(o => {
                    const t = o.dataset.roomType;
                    if (String(o.value) === String(centerId)) {
                        o.disabled = false;
                        return;
                    }
                    o.disabled = (t !== 'auxiliary');
                    if (o.disabled) o.selected = false;
                });
            } else {
                // enable all options
                Array.from(coveredSelect.options).forEach(o => { o.disabled = false; });
            }
        }

        async function saveExtinguisher() {
            const form = document.getElementById('addExtForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const typeSelect = document.getElementById('ext_type_select');
            const otherInput = document.getElementById('other_type_input');
            
            // Create a temporary cloned FormData to manipulate values if needed
            // Actually, we can just append or change values in the original FormData object before sending
            const formData = new FormData(form);

            if (typeSelect.value === 'Other') {
                if (!otherInput.value.trim()) {
                    Swal.fire('Required', 'Please specify the extinguisher type.', 'warning');
                    otherInput.focus();
                    return;
                }
                // Override 'type' with the custom value
                formData.set('type', otherInput.value.trim());
            }

            const centerId = document.getElementById('centerRoomSelect').value;
            const covered = Array.from(document.getElementById('coveredRoomsSelect').selectedOptions).map(o => o.value);

            if (!centerId) {
                Swal.fire('Selection Required', 'Please select a center room.', 'warning');
                return;
            }
            if (covered.length < 1 || covered.length > 3) {
                Swal.fire('Invalid Selection', 'Please select 1 to 3 covered rooms.', 'warning');
                return;
            }
            if (!covered.includes(centerId)) {
                Swal.fire('Inconsistent Selection', 'Covered rooms must include the center room.', 'warning');
                return;
            }

            const centerType = document.getElementById('centerRoomSelect').selectedOptions[0]?.dataset?.roomType;
            if (centerType === 'laboratory' && covered.length > 2) {
                Swal.fire('Constraint Error', 'Laboratory can only cover itself, or itself + 1 clinic/auxiliary room.', 'warning');
                return;
            }

            // Update pressure based on status if needed, or enforce validation later
            // For now, simple validation

            try {
                const resp = await fetch(`{{ route('fire-safety.extinguisher.store') }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken(),
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await resp.json();
                if (!resp.ok || !data.success) {
                    Swal.fire('Error', data.message || 'Failed to add extinguisher', 'error');
                    return;
                }

                Swal.fire('Success', 'Extinguisher added successfully!', 'success').then(() => {
                    location.reload();
                });
            } catch (e) {
                console.error(e);
                Swal.fire('Error', 'Failed to add extinguisher. Please try again.', 'error');
            }
        }

        // Enforce max 3 selections for covered rooms
        document.addEventListener('change', function (e) {
            if (e.target && e.target.id === 'coveredRoomsSelect') {
                const selected = Array.from(e.target.selectedOptions);
                if (selected.length > 3) {
                    // keep first 3
                    selected.slice(3).forEach(o => o.selected = false);
                    Swal.fire('Limit Reached', 'Max of 3 rooms can be covered by one extinguisher.', 'info');
                }
            }
        });

        function openUpdateModal(id, code, status, pressure) {
            document.getElementById('updateExtId').value = id;
            document.getElementById('updateExtCode').value = code;
            document.getElementById('updateExtStatus').value = status;
            document.getElementById('updateExtPressure').value = pressure;
            document.getElementById('updateExtNotes').value = '';
            
            // Reset removal logic
            if (document.getElementById('removeExtBtn')) document.getElementById('removeExtBtn').style.display = 'none';
            if (document.getElementById('extRemovalReasonSection')) document.getElementById('extRemovalReasonSection').classList.add('d-none');
            if (document.getElementById('extRemovalReason')) document.getElementById('extRemovalReason').value = '';

            const modalEl = document.getElementById('updateExtModal');
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();

            // Check initial status for remove button
            handleUpdateStatusChange();
        }

        function handleUpdateStatusChange() {
            const statusSelect = document.getElementById('updateExtStatus');
            if(!statusSelect) return;
            const status = statusSelect.value;
            const pressureInput = document.getElementById('updateExtPressure');
            
            // Show/Hide Remove button if status is Decommissioned
            const removeBtn = document.getElementById('removeExtBtn');
            if (removeBtn) {
                if (status === 'decommissioned') {
                    removeBtn.style.display = 'inline-block';
                } else {
                    removeBtn.style.display = 'none';
                    if (document.getElementById('extRemovalReasonSection')) {
                        document.getElementById('extRemovalReasonSection').classList.add('d-none');
                    }
                }
            }

            if (status === 'active') {
                pressureInput.min = 70;
                pressureInput.max = 100;
                if (pressureInput.value < 70) pressureInput.value = 70;
            } else if (status === 'maintenance') {
                pressureInput.min = 0;
                pressureInput.max = 69;
                if (pressureInput.value >= 70) pressureInput.value = 69;
            } else if (status === 'expired') {
                pressureInput.min = 0;
                pressureInput.max = 19;
                if (pressureInput.value > 19) pressureInput.value = 19;
            } else {
                pressureInput.min = 0;
                pressureInput.max = 100;
            }
        }

        function showExtRemovalReason() {
            const section = document.getElementById('extRemovalReasonSection');
            if(!section) return;
            section.classList.toggle('d-none');
            if (!section.classList.contains('d-none')) {
                const input = document.getElementById('extRemovalReason');
                if(input) input.focus();
            }
        }

        async function confirmRemoveExtinguisher() {
            const id = document.getElementById('updateExtId').value;
            const code = document.getElementById('updateExtCode').value;
            const reasonInput = document.getElementById('extRemovalReason');
            const reason = reasonInput ? reasonInput.value : '';

            if (!reason.trim()) {
                Swal.fire('Reason Required', 'Please provide a reason for removal.', 'warning');
                return;
            }

            const result = await Swal.fire({
                title: 'Are you sure?',
                text: `You are about to remove Fire Extinguisher ${code}. This will move it to archive.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Remove It!'
            });

            if (result.isConfirmed) {
                try {
                    const resp = await fetch(`/fire-safety/extinguisher/${id}/remove`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken(),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ reason: reason })
                    });

                    const data = await resp.json();
                    if (data.success) {
                        Swal.fire('Removed!', 'Fire extinguisher has been archived.', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', data.message || 'Failed to remove extinguisher', 'error');
                    }
                } catch (e) {
                    console.error(e);
                    Swal.fire('Error', 'Network error during removal.', 'error');
                }
            }
        }

        async function openExtHistoryModal(schoolId) {
            const modalEl = document.getElementById('extHistoryModal');
            if(!modalEl) return;
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            const tableBody = document.querySelector('#extHistoryTable tbody');
            if(!tableBody) return;
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Loading...</td></tr>';
            modal.show();

            try {
                const resp = await fetch(`/fire-safety/extinguisher/history/${schoolId}`);
                const data = await resp.json();

                if (data.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No removed extinguishers found.</td></tr>';
                    return;
                }

                tableBody.innerHTML = '';
                data.forEach(item => {
                    const removedAt = new Date(item.removed_at).toLocaleString();
                    const row = `
                        <tr>
                            <td>${removedAt}</td>
                            <td class="fw-bold text-danger">${item.item_code || 'N/A'}</td>
                            <td>${item.item_data.type || 'N/A'}</td>
                            <td>${item.item_data.building_name || 'N/A'}, Floor ${item.item_data.floor_no || '?'}</td>
                            <td>${item.reason || 'No reason provided'}</td>
                            <td>
                                <small>
                                    Status: ${item.item_data.status}<br>
                                    Pressure: ${item.item_data.pressure_level}%
                                </small>
                            </td>
                        </tr>
                    `;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
            } catch (e) {
                console.error(e);
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Failed to load history.</td></tr>';
            }
        }

        async function saveExtinguisherStatus() {
            const id = document.getElementById('updateExtId').value;
            const form = document.getElementById('updateExtForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);

            try {
                const resp = await fetch(`/fire-safety/extinguisher/${id}/update`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken(),
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await resp.json();
                if(data.success) {
                    Swal.fire('Updated', 'Extinguisher status updated successfully!', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'Error updating extinguisher', 'error');
                }
            } catch(e) {
                console.error(e);
                Swal.fire('Network Error', 'Failed to update extinguisher status.', 'error');
            }
        }

        async function loadRecentInspections(schoolId) {
            const tableBody = document.querySelector(`#inspectionsTable-${schoolId} tbody`);
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Loading...</td></tr>';

            try {
                const resp = await fetch(`/fire-safety/extinguisher/inspections/${schoolId}`);
                const data = await resp.json();

                if (data.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No recent inspections found.</td></tr>';
                    return;
                }

                tableBody.innerHTML = '';
                data.forEach(item => {
                    let badgeClass = 'secondary';
                    let statusLabel = item.status;
                    if (item.status === 'active') { badgeClass = 'success'; statusLabel = 'OK'; }
                    else if (item.status === 'maintenance') { badgeClass = 'warning'; statusLabel = 'For Refill'; }
                    else if (item.status === 'expired') { badgeClass = 'danger'; statusLabel = 'Empty'; }
                    else if (item.status === 'missing') { badgeClass = 'danger'; statusLabel = 'Missing'; }
                    else if (item.status === 'purchase') { badgeClass = 'dark'; statusLabel = 'For Purchase'; }
                    else if (item.status === 'decommissioned') { badgeClass = 'danger'; statusLabel = 'Decommissioned'; }

                    const row = `
                        <tr>
                            <td>${item.date}</td>
                            <td class="fw-bold">${item.code}</td>
                            <td>${item.location}</td>
                            <td>${item.inspector}</td>
                            <td><span class="badge bg-${badgeClass}">${statusLabel}</span></td>
                            <td>${item.pressure_level}%</td>
                            <td>${item.notes || '-'}</td>
                        </tr>
                    `;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
            } catch(e) {
                console.error(e);
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Failed to load data.</td></tr>';
            }
        }

        // Load inspections on page load for the active tab
        document.addEventListener('DOMContentLoaded', () => {
           const activeTabPane = document.querySelector('.tab-pane.show.active');
           if(activeTabPane) {
               const sId = activeTabPane.id.replace('school-', '');
               loadRecentInspections(sId);
           }

           // Listener for tab changes
           const tabEls = document.querySelectorAll('button[data-bs-toggle="tab"]');
           tabEls.forEach(tabEl => {
               tabEl.addEventListener('shown.bs.tab', function (event) {
                   const targetId = event.target.getAttribute('data-bs-target');
                   const sId = targetId.replace('#school-', '');
                   loadRecentInspections(sId);
               });
           });
        });


        async function openUpdateRoomModal(roomId) {
            try {
                const resp = await fetch(`/fire-safety/room/${roomId}`);
                if (!resp.ok) throw new Error('Room not found');
                const data = await resp.json();

                document.getElementById('updateRoomId').value = data.id;
                document.getElementById('updateRoomCode').value = data.room_code || '';
                document.getElementById('updateRoomName').value = data.room_name;
                document.getElementById('updateRoomFloor').value = data.floor_no + " Floor";

                // Populate candidates for nearest extinguisher
                const candidatesResp = await fetch(`/fire-safety/room/${roomId}/candidates`);
                const candidates = await candidatesResp.json();

                const select = document.getElementById('updateRoomNearest');
                select.innerHTML = '<option value="">None / Self-Covered</option>';

                if (data.is_center_room) {
                    select.disabled = true;
                    const opt = document.createElement('option');
                    opt.value = "";
                    opt.textContent = "HOST ROOM (Hosts own extinguisher)";
                    opt.selected = true;
                    select.appendChild(opt);
                    select.classList.add('bg-light');
                } else {
                    select.disabled = false;
                    select.classList.remove('bg-light');
                    candidates.forEach(c => {
                        const opt = document.createElement('option');
                        opt.value = c.id;
                        opt.textContent = `${c.room_name} (${c.room_code || 'No Code'})`;
                        if (data.nearest_extinguisher_room_id == c.id) opt.selected = true;
                        select.appendChild(opt);
                    });
                }

                const modalEl = document.getElementById('updateRoomModal');
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();

            } catch (e) {
                console.error(e);
                Swal.fire('Error', 'Failed to load room details.', 'error');
            }
        }

        async function saveRoomUpdate() {
            const id = document.getElementById('updateRoomId').value;
            const form = document.getElementById('updateRoomForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);
            // Removed _method PUT as suggested - using POST directly


            try {
                const resp = await fetch(`/fire-safety/room/${id}/update`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken(),
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await resp.json();
                if (data.success) {
                    Swal.fire('Updated', 'Room details updated successfully!', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to update room', 'error');
                }
            } catch (e) {
                console.error(e);
                Swal.fire('Error', 'Network error during room update.', 'error');
            }
        }

        function inspectRoom(roomId) {
            openUpdateRoomModal(roomId);
        }
    </script>
</body>
</html>
