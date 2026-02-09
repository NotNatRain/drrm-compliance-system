{{-- resources/views/fire-safety/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fire Safety Dashboard - DRRM Compliance</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --fire-red: #A8191F;
            --fire-dark-red: #8A1217;
            --fire-light-red: #F8D7DA;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* Top Navigation */
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

        /* Sidebar */
        .sidebar {
            background-color: var(--fire-red);
            width: 250px;
            position: fixed;
            top: 60px; /* Below top nav */
            left: 0;
            bottom: 0;
            z-index: 1020;
            overflow-y: auto;
            transition: all 0.3s;
        }

        /* Main Content Area */
        .main-content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 20px;
            min-height: calc(100vh - 60px);
            background-color: #f8f9fa;
        }

        /* Sidebar Navigation Items */
        .sidebar-nav {
            padding: 20px 0;
        }

        .nav-item {
            margin-bottom: 2px;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            text-decoration: none;
        }

        .nav-link.active {
            background-color: var(--fire-dark-red);
            color: white;
            border-left: 4px solid white;
        }

        .nav-icon {
            width: 24px;
            margin-right: 10px;
            text-align: center;
        }

        /* Quick Actions in Sidebar */
        .quick-actions {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            padding: 0 20px;
        }

        /* Cards */
        .dashboard-card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .dashboard-card:hover {
            transform: translateY(-2px);
        }

        /* Status Badges */
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar.active {
                margin-left: 0;
            }
        }

        /* Custom Scrollbar for Sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="top-nav">
        <div class="container-fluid h-100">
            <div class="row h-100 align-items-center">
                <!-- Left: Logo and Back Button -->
                <div class="col-auto">
                    <a href="{{ route('dashboard') }}" class="text-white text-decoration-none">
                        <i class="fas fa-arrow-left me-2"></i>
                        <i class="fas fa-fire me-2"></i>
                        <span class="fw-bold">Fire Safety Checklist System</span>
                    </a>
                </div>

                <!-- Center: Title -->
                <div class="col text-center">
                    <h4 class="text-white mb-0">Dashboard</h4>
                </div>

                <!-- Right: User Menu and Notifications -->
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

                        <!-- User Profile -->
                        <div class="dropdown">
                            <a href="#" class="text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle fa-lg me-2"></i>
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('fire-safety.customization') }}">
                                    <i class="fas fa-cogs me-2"></i> Customization
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

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Navigation -->
        <div class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('fire-safety.dashboard') }}">
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
                    <a class="nav-link" href="{{ route('fire-safety.extinguishers') }}">
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
        </div>

        <!-- Quick Actions at Bottom of Sidebar -->
        <div class="quick-actions">
            <h6 class="text-white mb-3">Quick Actions</h6>
            <div class="d-grid gap-2">
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addInspectionModal">
                    <i class="fas fa-plus me-2"></i> Add Inspection
                </button>
                <div class="row g-2">
                    <div class="col-6">
                        <button class="btn btn-danger btn-sm w-100" data-bs-toggle="modal" data-bs-target="#addAlertModal">
                            <i class="fas fa-exclamation-triangle me-1"></i> Add Alert
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#addEventModal">
                            <i class="fas fa-calendar-plus me-1"></i> Add Event
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card border-left-success h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                    Passed Inspections
                                </div>
                                <div class="h2 mb-0 fw-bold text-gray-800">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card border-left-danger h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-danger text-uppercase mb-1">
                                    Ongoing Improvement
                                </div>
                                <div class="h2 mb-0 fw-bold text-gray-800">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times-circle fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card border-left-warning h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                    Extinguishers Needing Action
                                </div>
                                <div class="h2 mb-0 fw-bold text-gray-800">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-fire-extinguisher fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card border-left-info h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                    Alarm System Status
                                </div>
                                <div class="h2 mb-0 fw-bold text-gray-800">0 Offline</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-bell fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- School Safety Status Section -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card dashboard-card mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary">School Safety Status</h6>
                        <div class="d-flex gap-2">
                            <select id="statusFilter" class="form-select form-select-sm" style="width: auto;">
                                <option value="all">All Status</option>
                                <option value="passed">Passed</option>
                                <option value="failed">Ongoing</option>
                                <option value="unconfigured">Unconfigured</option>
                            </select>
                            <select id="sortFilter" class="form-select form-select-sm" style="width: auto;">
                                <option value="name_asc">School Name (A-Z)</option>
                                <option value="name_desc">School Name (Z-A)</option>
                                <option value="inspection_asc">Last Inspection (Oldest)</option>
                                <option value="inspection_desc">Last Inspection (Newest)</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>School</th>
                                                <th>Status</th>
                                                <th>Issues</th>
                                                <th>Last Inspection</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="schoolsTableBody">
                                            @forelse($schools as $school)
                                                <tr data-status="{{ $school->status }}" 
                                                    data-school-name="{{ $school->school_name }}"
                                                    data-inspection-date="{{ $school->last_inspection_date ?? '1900-01-01' }}">
                                                    <td>{{ $school->school_name }}</td>
                                                    <td>
                                                        @if($school->status === 'passed')
                                                            <span class="status-badge bg-success">PASSED</span>
                                                        @elseif($school->status === 'failed')
                                                            <span class="status-badge bg-danger">FAILED</span>
                                                        @elseif($school->status === 'unconfigured')
                                                            <span class="status-badge bg-warning">UNCONFIGURED</span>
                                                        @else
                                                            <span class="status-badge bg-warning">WARNING</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($school->status === 'unconfigured')
                                                            Setup Needed
                                                        @elseif ($school->issues_count > 0)
                                                            {{$school->issues_count}} issues found
                                                        @else
                                                            None
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($school->last_inspection_date && $school->last_inspection_date !== 'Never')
                                                            {{ \Carbon\Carbon::parse($school->last_inspection_date)->format('Y-m-d') }}
                                                        @else
                                                            Never
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($school->status === 'passed')
                                                            <button class="btn btn-sm btn-outline-primary view-school-btn"
                                                                    data-school-id="{{ $school->id }}"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#viewSchoolModal">
                                                                <i class="fas fa-eye"></i> View
                                                            </button>
                                                        @else
                                                            <button class="btn btn-sm btn-outline-warning details-btn"
                                                                    data-school-id="{{ $school->id }}"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#issuesModal">
                                                                <i class="fas fa-info-circle"></i> Details
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted py-4">
                                                        No schools found. Click "Add Inspection" to add a school.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Bottom Action Bar -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="card dashboard-card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-center gap-3">
                                                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addInspectionModal">
                                                        <i class="fas fa-plus-circle me-2"></i> Add Inspection
                                                    </button>
                                                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addAlertModal">
                                                        <i class="fas fa-exclamation-triangle me-2"></i> Add Alert
                                                    </button>
                                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
                                                        <i class="fas fa-calendar-plus me-2"></i> Add Event
                                                    </button>
                                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#evacInfoModal">
                                                        <i class="fas fa-map-signs me-2"></i> View Evacuation Plans
                                                    </button>
                                                    <a href="{{ route('fire-safety.report.school-summary') }}" target="_blank" class="btn btn-success">
                                                        <i class="fas fa-file-pdf me-2"></i> Generate Report
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <!-- Alerts for All Schools -->
                                <div class="card dashboard-card mb-4">
                                    <div class="card-header py-3 bg-danger text-white">
                                        <h6 class="m-0 fw-bold">
                                            <i class="fas fa-exclamation-circle me-2"></i> Alerts
                                        </h6>
                                    </div>
                                    <div class="card-body" id="allAlerts">
                                        @forelse($allAlerts as $alert)
                                            <div class="card mb-2 border-start border-4 {{ $alert['type'] == 'danger' ? 'border-danger' : ($alert['type'] == 'warning' ? 'border-warning' : 'border-info') }}">
                                                <div class="card-body p-2">
                                                    <div class="d-flex justify-content-between">
                                                        <h6 class="mb-1 fw-bold text-{{ $alert['type'] }}">{{ $alert['school_name'] }}: {{ $alert['title'] }}</h6>
                                                        <small class="text-muted" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($alert['created_at'])->diffForHumans() }}</small>
                                                    </div>
                                                    <p class="mb-0 small text-dark">{{ $alert['description'] }}</p>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center text-muted py-3">
                                                <i class="fas fa-check-circle me-2"></i> No active alerts
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- Events for All Schools -->
                                <div class="card dashboard-card">
                                    <div class="card-header py-3 bg-primary text-white">
                                        <h6 class="m-0 fw-bold">
                                            <i class="fas fa-calendar-alt me-2"></i> Events
                                        </h6>
                                    </div>
                                    <div class="card-body" id="allEvents">
                                        @forelse($allEvents as $event)
                                            <div class="card mb-2 border-start border-4 border-primary">
                                                <div class="card-body p-2">
                                                    <div class="d-flex justify-content-between">
                                                        <h6 class="mb-1 fw-bold text-primary">{{ $event['school_name'] }}: {{ $event['title'] }}</h6>
                                                        <small class="text-muted" style="font-size: 0.7rem;">{{ $event['date'] }} {{ $event['time'] }}</small>
                                                    </div>
                                                    <p class="mb-0 small text-dark">{{ $event['description'] }}</p>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center text-muted py-3">
                                                <i class="fas fa-calendar me-2"></i> No upcoming events
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- <- School Safety Status Section -->



        <!-- View School Modal (for PASSED status) -->
        <div class="modal fade" id="viewSchoolModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="schoolNameTitle">School Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- School Information -->
                        <div class="school-info mb-4">
                            <h6 class="border-bottom pb-2">School Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>School Name:</strong> <span id="modalSchoolName"></span></p>
                                    <p><strong>School ID:</strong> <span id="modalSchoolId"></span></p>
                                    <p><strong>Address:</strong> <span id="modalSchoolAddress"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>School Head:</strong> <span id="modalSchoolHead"></span></p>
                                    <p><strong>DRRM Coordinator:</strong> <span id="modalDrrmCoordinator"></span></p>
                                </div>
                            </div>
                        </div>

                        <!-- Equipment Summary -->
                        <div class="equipment-summary">
                            <h6 class="border-bottom pb-2">Equipment Summary</h6>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h5 id="fireExtinguishersCount">0</h5>
                                            <p class="mb-0">Fire Extinguishers</p>
                                            <button class="btn btn-sm btn-link view-equipment"
                                                    data-type="extinguishers">
                                                View Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h5 id="alarmSystemsCount">0</h5>
                                            <p class="mb-0">Alarm Systems</p>
                                            <button class="btn btn-sm btn-link view-equipment"
                                                    data-type="alarm-systems">
                                                View Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h5 id="evacuationPlansCount">0</h5>
                                            <p class="mb-0">Evacuation Plans</p>
                                            <button class="btn btn-sm btn-link view-equipment"
                                                    data-type="evacuation-plans">
                                                View Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h5 id="buildingsCount">0</h5>
                                            <p class="mb-0">Buildings</p>
                                            <button class="btn btn-sm btn-link view-equipment"
                                                    data-type="buildings">
                                                View Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Issues Modal (for FAILED/WARNING status) -->
        <div class="modal fade" id="issuesModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title">Issues Found</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <h6 id="issuesSchoolName" class="mb-3"></h6>
                        <div id="issuesList">
                            <!-- Issues will be populated here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add Inspection Modal -->
        <div class="modal fade" id="addInspectionModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New School Inspection</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="addSchoolForm" action="{{ route('fire-safety.school.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>School Name *</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label>School ID *</label>
                                <input type="text" class="form-control" name="school_id" required>
                            </div>
                            <div class="mb-3">
                                <label>Address *</label>
                                <textarea class="form-control" name="address" rows="2" required></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>School Head *</label>
                                    <input type="text" class="form-control" name="school_head" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>DRRM Coordinator *</label>
                                    <input type="text" class="form-control" name="drrm_coordinator" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Add School</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Add Alert Modal -->
        <div class="modal fade" id="addAlertModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i> Add Safety Alert</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="addAlertForm">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">School *</label>
                                <select class="form-select" name="school_id" required>
                                    <option value="">Select School</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alert Title *</label>
                                <input type="text" class="form-control" name="title" placeholder="e.g., Blockage in Fire Exit" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alert Type *</label>
                                <select class="form-select" name="type" required>
                                    <option value="danger">Critical (Red)</option>
                                    <option value="warning">Warning (Yellow)</option>
                                    <option value="info">Information (Blue)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description *</label>
                                <textarea class="form-control" name="description" rows="3" placeholder="Provide more details about the alert..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Post Alert</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Event Modal -->
        <div class="modal fade" id="addEventModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="fas fa-calendar-plus me-2"></i> Add Safety Event</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="addEventForm">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">School *</label>
                                <select class="form-select" name="school_id" required>
                                    <option value="">Select School</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Event Title *</label>
                                <input type="text" class="form-control" name="title" placeholder="e.g., Annual Fire Drill" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date *</label>
                                    <input type="date" class="form-control" name="date" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Time</label>
                                    <input type="time" class="form-control" name="time">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description *</label>
                                <textarea class="form-control" name="description" rows="3" placeholder="Provide more details about the event..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Schedule Event</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Evacuation Plans Info Modal -->
        <div class="modal fade" id="evacInfoModal">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <i class="fas fa-map-signs fa-3x text-info mb-3"></i>
                        <h5>Evacuation Plans</h5>
                        <p>View evacuation routes, assembly points, and emergency procedures.</p>
                        <a href="{{ route('fire-safety.evacuation-plans') }}" class="btn btn-info">View All Plans</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching - load school-specific alerts/events
    const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-bs-target');
            const schoolSlug = target.replace('#', '');

            // Remove active from all
            tabButtons.forEach(btn => btn.classList.remove('active'));
            // Add active to clicked
            this.classList.add('active');

            // Load school data if needed
            if (schoolSlug !== 'all') {
                // You can load specific school data here
                console.log(`Loading data for ${schoolSlug}`);
            }
        });
    });

    // View School Modal Handler
    document.querySelectorAll('.view-school-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const schoolId = this.getAttribute('data-school-id');
            loadSchoolDetails(schoolId);
        });
    });

    // Issues Modal Handler
    document.querySelectorAll('.details-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const schoolId = this.getAttribute('data-school-id');
            loadSchoolIssues(schoolId);
        });
    });

    // Equipment View Buttons (in modal)
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('view-equipment')) {
            const type = e.target.getAttribute('data-type');
            window.location.href = `/fire-safety/${type}`;
        }
    });

    function loadSchoolDetails(schoolId) {
        fetch(`/fire-safety/school/${schoolId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modalSchoolName').textContent = data.name;
                document.getElementById('modalSchoolId').textContent = data.school_id;
                document.getElementById('modalSchoolAddress').textContent = data.address || 'N/A';
                document.getElementById('modalSchoolHead').textContent = data.school_head;
                document.getElementById('modalDrrmCoordinator').textContent = data.drrm_coordinator;
                document.getElementById('fireExtinguishersCount').textContent = data.fire_extinguishers_count;
                document.getElementById('alarmSystemsCount').textContent = data.alarm_systems_count;
                document.getElementById('evacuationPlansCount').textContent = data.evacuation_plans_count;
                document.getElementById('buildingsCount').textContent = data.buildings_count;

                // Update modal title
                document.getElementById('schoolNameTitle').textContent = `${data.name} Details`;

                // Render Alerts and Events
                renderAlerts(data.alerts, data.name);
                renderEvents(data.events, data.name);
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    text: 'Failed to load school details. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#A8191F'
                });
            });
    }

function loadSchoolIssues(schoolId) {
    fetch(`/fire-safety/school/${schoolId}/issues`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('issuesSchoolName').textContent = data.school_name;

            let issuesHtml = '';
            if(data.issues.length === 0) {
                issuesHtml = '<div class="alert alert-success">No issues found!</div>';
            } else {
                data.issues.forEach(issue => {
                    const alertClass = issue.type === 'danger' ? 'alert-danger' : 'alert-warning';

                    if (issue.link) {
                        // Clickable issue with link
                        issuesHtml += `
                            <a href="${issue.link}" class="alert ${alertClass} d-block text-decoration-none" onclick="event.preventDefault(); window.location.href='${issue.link}'">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>${issue.title}</strong><br>
                                <small>${issue.description}</small>
                                <div class="text-end mt-2">
                                    <span class="badge bg-dark"><i class="fas fa-external-link-alt me-1"></i> Configure</span>
                                </div>
                            </a>`;
                    } else {
                        // Non-clickable issue
                        issuesHtml += `
                            <div class="alert ${alertClass}">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>${issue.title}</strong><br>
                                <small>${issue.description}</small>
                            </div>`;
                    }
                });
            }
            document.getElementById('issuesList').innerHTML = issuesHtml;
        })
        .catch(error => {
            console.error('Error loading school issues:', error);
            // Show a more user-friendly message
            document.getElementById('issuesList').innerHTML = `
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    School configuration setup needed. Please visit each section to configure:
                    <div class="mt-2">
                        <a href="/fire-safety/alarm-systems" class="btn btn-sm btn-warning me-2">Alarm Systems</a>
                        <a href="/fire-safety/extinguishers" class="btn btn-sm btn-danger me-2">Fire Extinguishers</a>
                        <a href="/fire-safety/buildings" class="btn btn-sm btn-primary me-2">Buildings</a>
                        <a href="/fire-safety/evacuation-plans" class="btn btn-sm btn-success">Evacuation Plans</a>
                    </div>
                </div>`;
        });
}

    // Initialize with some data
    const firstTab = document.querySelector('[data-bs-target="#all"]');
    if (firstTab) {
        firstTab.click();
    }

    // Filter and Sort functionality
    const statusFilter = document.getElementById('statusFilter');
    const sortFilter = document.getElementById('sortFilter');
    const schoolsTableBody = document.getElementById('schoolsTableBody');

    function filterAndSortSchools() {
        const statusValue = statusFilter.value;
        const sortValue = sortFilter.value;
        const rows = Array.from(schoolsTableBody.querySelectorAll('tr'));

        // Filter rows
        rows.forEach(row => {
            if (row.querySelector('td[colspan]')) return; // Skip empty state row
            
            const rowStatus = row.dataset.status;
            if (statusValue === 'all' || rowStatus === statusValue) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        // Get visible rows
        const visibleRows = rows.filter(row => row.style.display !== 'none' && !row.querySelector('td[colspan]'));

        // Sort visible rows
        visibleRows.sort((a, b) => {
            if (sortValue.startsWith('name_')) {
                const nameA = a.dataset.schoolName.toLowerCase();
                const nameB = b.dataset.schoolName.toLowerCase();
                return sortValue === 'name_asc' 
                    ? nameA.localeCompare(nameB) 
                    : nameB.localeCompare(nameA);
            } else if (sortValue.startsWith('inspection_')) {
                const dateA = new Date(a.dataset.inspectionDate);
                const dateB = new Date(b.dataset.inspectionDate);
                return sortValue === 'inspection_asc' 
                    ? dateA - dateB 
                    : dateB - dateA;
            }
            return 0;
        });

        // Reorder rows in the table
        visibleRows.forEach(row => {
            schoolsTableBody.appendChild(row);
        });
    }

    if (statusFilter && sortFilter) {
        statusFilter.addEventListener('change', filterAndSortSchools);
        sortFilter.addEventListener('change', filterAndSortSchools);
    }

    // Add School Form Submission
    const addSchoolForm = document.getElementById('addSchoolForm');
    if (addSchoolForm) {
        addSchoolForm.addEventListener('submit', function(e) {
            e.preventDefault();

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    name: this.name.value,
                    school_id: this.school_id.value,
                    address: this.address.value,
                    school_head: this.school_head.value,
                    drrm_coordinator: this.drrm_coordinator.value
                })
            })
            .then(async response => {
                const data = await response.json();
                if (response.ok && data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'School added successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    // Specific handling for validation errors if data.message is present
                    let errorMsg = data.message || 'Failed to add school. Check your input.';
                    if (data.errors) {
                        const firstError = Object.values(data.errors)[0][0];
                        errorMsg = firstError;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Notice',
                        text: errorMsg
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'An unexpected error occurred. Please try again later.'
                });
            });
        });
    }

    // Add Alert Form Submission
    const addAlertForm = document.getElementById('addAlertForm');
    if (addAlertForm) {
        addAlertForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            fetch('{{ route("fire-safety.school.alert.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    Swal.fire('Success', 'Alert posted successfully!', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('addAlertModal')).hide();
                    this.reset();
                    // If the school being edited is the one currently viewed, refresh display
                    // For now, simpler to just reload or re-fetch details if we have an active school ID
                    location.reload(); 
                }
            });
        });
    }

    // Add Event Form Submission
    const addEventForm = document.getElementById('addEventForm');
    if (addEventForm) {
        addEventForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            fetch('{{ route("fire-safety.school.event.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    Swal.fire('Success', 'Event scheduled successfully!', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('addEventModal')).hide();
                    this.reset();
                    location.reload();
                }
            });
        });
    }

    function renderAlerts(alerts, schoolName) {
        const container = document.getElementById('allAlerts');
        if (!alerts || alerts.length === 0) {
            container.innerHTML = `<div class="text-center text-muted py-3">No active alerts for ${schoolName}</div>`;
            return;
        }

        let html = '';
        alerts.sort((a, b) => new Date(b.created_at) - new Date(a.created_at)).forEach(alert => {
            const icon = alert.type === 'danger' ? 'fa-exclamation-triangle' : 'fa-info-circle';
            const border = alert.type === 'danger' ? 'border-danger' : (alert.type === 'warning' ? 'border-warning' : 'border-info');
            const bg = alert.type === 'danger' ? 'bg-light-danger' : (alert.type === 'warning' ? 'bg-light-warning' : 'bg-light-info');

            html += `
                <div class="card mb-2 border-start border-4 ${border}">
                    <div class="card-body p-2">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-1 fw-bold text-${alert.type}">${alert.title}</h6>
                            <small class="text-muted" style="font-size: 0.7rem;">${alert.created_at}</small>
                        </div>
                        <p class="mb-0 small text-dark">${alert.description}</p>
                    </div>
                </div>`;
        });
        container.innerHTML = html;
    }

    function renderEvents(events, schoolName) {
        const container = document.getElementById('allEvents');
        if (!events || events.length === 0) {
            container.innerHTML = `<div class="text-center text-muted py-3">No upcoming events for ${schoolName}</div>`;
            return;
        }

        let html = '';
        events.sort((a, b) => new Date(a.date) - new Date(b.date)).forEach(event => {
            html += `
                <div class="card mb-2 border-start border-4 border-primary">
                    <div class="card-body p-2">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-1 fw-bold text-primary">${event.title}</h6>
                            <small class="text-muted" style="font-size: 0.7rem;">${event.date} ${event.time || ''}</small>
                        </div>
                        <p class="mb-0 small text-dark">${event.description}</p>
                    </div>
                </div>`;
        });
        container.innerHTML = html;
    }
});

</script>
</body>
</html>
