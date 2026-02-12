<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Fire Safety Checklist System')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        
        /* Shared Styles for Status/Modals */
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        
        /* Modals Z-Index Fix */
        .modal { z-index: 1065 !important; }
        .modal-backdrop { z-index: 1060 !important; }
        .swal2-container { z-index: 2060 !important; }


        /* School Tabs Styles */
        .nav-tabs { border-bottom: 2px solid #dee2e6; }
        .nav-tabs .nav-link {
            color: #495057;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-bottom: none;
            border-top-left-radius: 0.25rem;
            border-top-right-radius: 0.25rem;
            margin-bottom: -1px;
            font-weight: 500;
            transition: all 0.3s;
            cursor: pointer;
        }
        .nav-tabs .nav-link:hover {
            color: white;
            background-color: #8A1217; 
            border-color: #8A1217 #8A1217 #dee2e6;
        }
        .nav-tabs .nav-link.active {
            color: white !important;
            background-color: #8A1217 !important;
            border-color: #8A1217 !important;
            position: relative;
            z-index: 1;
        }
        
        /* School Selection Card */
        .school-select-card {
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            border: 2px solid transparent;
        }
        .school-select-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            border-color: var(--fire-red);
        }
        .school-select-card.active {
            border-color: var(--fire-red);
            background-color: rgba(168, 25, 31, 0.05);
        }

        @yield('styles')
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
                    <h4 class="text-white mb-0">@yield('page_title', 'Fire Safety Module')</h4>
                </div>

                <div class="col-auto">
                    <div class="d-flex align-items-center">
                        <div class="dropdown me-3">
                            <a href="#" class="text-white position-relative" data-bs-toggle="dropdown">
                                <i class="fas fa-bell fa-lg"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
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

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="p-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('fire-safety.dashboard') ? 'active' : '' }}" href="{{ route('fire-safety.dashboard') }}">
                        <span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('fire-safety.buildings') ? 'active' : '' }}" href="{{ route('fire-safety.buildings') }}">
                        <span class="nav-icon"><i class="fas fa-building"></i></span>
                        <span>Buildings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('fire-safety.alarm-systems') ? 'active' : '' }}" href="{{ route('fire-safety.alarm-systems') }}">
                        <span class="nav-icon"><i class="fas fa-bell"></i></span>
                        <span>Alarm Systems</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('fire-safety.extinguishers') ? 'active' : '' }}" href="{{ route('fire-safety.extinguishers') }}">
                        <span class="nav-icon"><i class="fas fa-fire-extinguisher"></i></span>
                        <span>Fire Extinguishers & Rooms</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('fire-safety.evacuation-plans') ? 'active' : '' }}" href="{{ route('fire-safety.evacuation-plans') }}">
                        <span class="nav-icon"><i class="fas fa-map-signs"></i></span>
                        <span>Evacuation Plans</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('fire-safety.customization') ? 'active' : '' }}" href="{{ route('fire-safety.customization') }}">
                        <span class="nav-icon"><i class="fas fa-cog"></i></span>
                        <span>{{ auth()->user()->role === 'viewer' ? 'School Info' : 'Customization' }}</span>
                    </a>
                </li>
            </ul>
            <hr class="bg-white my-4">
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @if(isset($schools) && $schools->isNotEmpty())
            <!-- School Selection Tabs -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card dashboard-card">
                        <div class="card-body p-0">
                            <ul class="nav nav-tabs border-0" id="schoolTab" role="tablist">
                                @if($schools->count() > 4)
                                    <!-- > 4 Schools: Show Active + Choose Another -->
                                    <li class="nav-item">
                                        <button class="nav-link active" type="button">
                                            {{ $activeSchool->school_name ?? 'Select School' }}
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" type="button" data-bs-toggle="modal" data-bs-target="#selectSchoolModal">
                                            <i class="fas fa-exchange-alt me-2"></i> Choose Another School...
                                        </button>
                                    </li>
                                @else
                                    <!-- <= 4 Schools: Show All Tabs -->
                                    @foreach($schools as $school)
                                    <li class="nav-item">
                                        <button class="nav-link {{ (isset($activeSchool) && $activeSchool->id == $school->id) ? 'active' : '' }}" 
                                                onclick="switchSchool({{ $school->id }})">
                                            {{ $school->school_name }}
                                        </button>
                                    </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(isset($schools) && $schools->isEmpty() && !Route::is('fire-safety.dashboard'))
             <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> No schools found. Please add a school in the Dashboard first.
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Select School Modal (Card Layout) -->
    <div class="modal fade" id="selectSchoolModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title"><i class="fas fa-school me-2"></i> Select School</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="row g-3">
                        @if(isset($schools))
                            @foreach($schools as $school)
                            <div class="col-md-6 mb-3">
                                <div class="card school-select-card h-100 {{ (isset($activeSchool) && $activeSchool->id == $school->id) ? 'active' : '' }}" 
                                     onclick="switchSchool({{ $school->id }})">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white me-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                                            <i class="fas fa-university fa-lg"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">{{ $school->school_name }}</h6>
                                            <small class="text-muted">{{ $school->school_id }}</small>
                                            @if(isset($activeSchool) && $activeSchool->id == $school->id)
                                                <span class="badge bg-success ms-2">Active</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function switchSchool(schoolId) {
            fetch(`/fire-safety/set-school/${schoolId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    Swal.fire('Error', 'Failed to switch school', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'An error occurred', 'error');
            });

        }

        // Move modals to body to escape stacking contexts and ensure they are clickable
        function moveModalsToBody() {
            document.querySelectorAll('.modal').forEach(function(modal) {
                if (modal.parentNode !== document.body) {
                    document.body.appendChild(modal);
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            moveModalsToBody();
            
            // Also watch for dynamically added modals if any
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1 && node.classList.contains('modal')) {
                            if (node.parentNode !== document.body) {
                                document.body.appendChild(node);
                            }
                        }
                    });
                });
            });
            
            observer.observe(document.body, { childList: true, subtree: true });
        });
    </script>


    @yield('modals')

    @yield('scripts')
</body>
</html>
