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
            --charcoal: #36454F;
            --dark-charcoal: #2C3E50;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        .top-nav {
            background: linear-gradient(135deg, var(--fire-red) 0%, var(--charcoal) 100%);
            height: 60px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .sidebar-toggle {
            display: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            margin-right: 15px;
            padding: 5px;
        }

        .sidebar {
            background: linear-gradient(180deg, var(--fire-red) 0%, var(--dark-charcoal) 100%);
            width: 250px;
            position: fixed;
            top: 60px;
            left: 0;
            bottom: 0;
            z-index: 1025;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .main-content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 20px;
            min-height: calc(100vh - 60px);
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1020;
            backdrop-filter: blur(2px);
        }

        @media (max-width: 991.98px) {
            .sidebar-toggle {
                display: block;
            }
            .sidebar {
                left: -250px;
            }
            .main-content {
                margin-left: 0;
            }
            body.show-sidebar .sidebar {
                left: 0;
            }
            body.show-sidebar .sidebar-overlay {
                display: block;
            }
            .top-nav .col text-center h4 {
                font-size: 1.1rem;
            }
            .top-nav .fw-bold {
                display: none;
            }
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

        /* Ensure dropdowns in top-nav appear above everything */
        .top-nav .dropdown-menu {
            z-index: 1050 !important;
        }


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


        /* Global Responsive Utilities */
        @media (max-width: 767.98px) {
            body { font-size: 0.85rem; }

            /* Force stat cards to 2 columns on mobile (col-md-6 override) */
            .stat-card .col-auto { display: none; } /* hide large icon on mobile */
            .stat-card .card-body .row { display: block; }
            .stat-card .card-body { padding: 8px !important; text-align: center; }
            .stat-card .text-xs { font-size: 0.6rem !important; line-height: 1; margin-bottom: 2px !important; }
            .stat-card .h2 { font-size: 1.1rem !important; margin-bottom: 0 !important; }

            .main-content { padding: 10px; }

            .card-header.d-flex, .card-header .d-flex {
                flex-direction: column;
                align-items: stretch !important;
                gap: 8px;
                padding: 10px !important;
            }
            .card-header h6 { font-size: 0.9rem !important; }

            .card-header .btn-group, .card-header .d-flex {
                width: 100%;
                display: flex;
                flex-direction: row !important;
                flex-wrap: wrap;
                gap: 5px;
            }
            .card-header .btn {
                flex-grow: 1;
                font-size: 0.75rem !important;
                padding: 4px 8px !important;
                margin-right: 0 !important;
            }

            /* Table Scaling */
            .table { font-size: 0.75rem !important; }
            .table th, .table td { padding: 6px 4px !important; }
            .badge { font-size: 0.65rem !important; padding: 3px 6px !important; }

            .h2 { font-size: 1.25rem !important; }
            .h3 { font-size: 1.15rem !important; }
            .h4 { font-size: 1rem !important; }
            .h5 { font-size: 0.9rem !important; }
            .h6 { font-size: 0.85rem !important; }

            /* Compact Cards for Mobile */
            .dashboard-card {
                margin-bottom: 10px !important;
            }
            .dashboard-card .card-body { padding: 8px !important; }


            .mobile-text-truncate {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 100%;
            }

            .mobile-stack {
                flex-direction: column !important;
                gap: 5px !important;
            }

            /* High Density Utilities - Mobile Only */
            .mobile-card-body { padding: 8px !important; }
            .mobile-compact-text { font-size: 0.75rem !important; }
            .mobile-tiny-text { font-size: 0.65rem !important; }
            .mobile-badge { font-size: 0.6rem !important; padding: 2px 4px !important; }
            .mobile-h-80 { height: 80px !important; }
            .mobile-mb-2 { margin-bottom: 0.5rem !important; }

            /* Responsive Tabs */
            .nav-tabs {
                flex-wrap: nowrap !important;
                overflow-x: auto !important;
                overflow-y: hidden !important;
                -webkit-overflow-scrolling: touch !important;
                font-size: 0.75rem !important;
            }
            .nav-tabs .nav-link {
                white-space: nowrap !important;
                padding: 6px 10px !important;
            }

            /* Table Fitting Force */
            .compact-mobile-table {
                font-size: 0.65rem !important;
                table-layout: fixed;
                width: 100% !important;
            }
            .compact-mobile-table th, .compact-mobile-table td {
                padding: 3px 2px !important;
                word-wrap: break-word;
                overflow-wrap: break-word;
            }
            .compact-mobile-table .badge {
                font-size: 0.55rem !important;
                padding: 1px 3px !important;
            }

            /* Admin Header Fix */
            .settings-tabs .nav-link {
                padding: 10px !important;
            }
        }

        @yield('styles')
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="top-nav">
        <div class="container-fluid h-100">
            <div class="row h-100 align-items-center">
                <div class="col-auto d-flex align-items-center">
                    <div class="sidebar-toggle" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </div>
                    <a href="{{ route('dashboard') }}" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-arrow-left me-2"></i>
                        <i class="fas fa-fire me-2"></i>
                        <span class="fw-bold">Fire Safety Checklist System</span>
                    </a>
                </div>

                <div class="col text-center">
                    <h4 class="text-white mb-0">
                        @yield('page_title', 'Fire Safety Module')
                        @if(auth()->user()->role !== 'admin' && isset($activeSchool))
                            <span class="d-inline-block ms-2 opacity-75 fw-normal">— {{ $activeSchool->school_name }}</span>
                        @endif
                    </h4>
                </div>

                <div class="col-auto">
                    <div class="d-flex align-items-center">
                        <div class="dropdown me-3">
                            <a href="#" class="text-white position-relative dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false" id="notificationDropdown" data-bs-auto-close="outside">
                                <i class="fas fa-bell fa-lg"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none" id="notifBadge">
                                    0
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow" style="width: 320px; max-width: 90vw; max-height: 480px; overflow-y: auto;">
                                <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                    Notifications
                                    <span class="badge bg-primary unread-label d-none">New</span>
                                </h6>
                                <div id="notificationList">
                                    <div class="dropdown-item text-center py-3">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                        <div class="text-muted small mt-1">Loading...</div>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-center small text-primary" href="{{ route('fire-safety.notifications.page') }}">See All</a>
                            </div>
                        </div>

                        <div class="dropdown">
                            <a href="#" class="text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                <i class="fas fa-user-circle fa-lg me-2"></i>
                                <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'User' }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('users.index') }}">
                                    <i class="fas fa-users-cog me-2"></i> {{ Auth::user()->role === 'admin' ? 'User Accounts' : 'User Account' }}
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

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

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
                        <span class="nav-icon">
                            <i class="fas fa-building"></i>
                            <i class="fas fa-bell ms-1" style="font-size: 0.7em;"></i>
                        </span>
                        <span>Buildings & Alarm System</span>
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
                @if(auth()->user()->role !== 'viewer')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('fire-safety.customization') ? 'active' : '' }}" href="{{ route('fire-safety.customization') }}">
                        <span class="nav-icon"><i class="fas fa-cog"></i></span>
                        <span>{{ auth()->user()->role === 'admin' ? 'Customization' : 'Update School Info' }}</span>
                    </a>
                </li>
                @endif
            </ul>
            <hr class="bg-white my-4">
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @if(isset($schools) && $schools->isNotEmpty())
            @if(auth()->user()->role === 'admin' && !request()->routeIs(['fire-safety.dashboard', 'fire-safety.customization', 'fire-safety.notifications.page']))
            <!-- School Selection Tabs (Admin only) -->
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
            @endif
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

    <!-- Global Notification Reply Modal -->
    <div class="modal fade" id="notifReplyModal" tabindex="-1" style="z-index: 1060;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reply to Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="notifReplyForm">
                    <div class="modal-body">
                        <input type="hidden" name="item_id" id="replyItemId">
                        <input type="hidden" name="school_id" id="replySchoolId">
                        <div class="mb-3">
                            <label class="form-label">Your Reply</label>
                            <textarea class="form-control" name="message" rows="4" placeholder="Type your reply here..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Send Reply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notifList = document.getElementById('notificationList');
            const notifBadge = document.getElementById('notifBadge');
            const notifDropdown = document.getElementById('notificationDropdown');
            const currentUserRole = "{{ auth()->user()->role }}";
            const currentUserSchoolId = "{{ auth()->user()->school_id }}";

            // Explicitly initialize all dropdowns in the top-nav to ensure they work on every page
            document.querySelectorAll('.top-nav [data-bs-toggle="dropdown"]').forEach(function(el) {
                new bootstrap.Dropdown(el);
            });

            function fetchNotifications() {
                fetch('{{ route("fire-safety.notifications") }}')
                    .then(response => response.json())
                    .then(data => {
                        renderNotifications(data);
                    })
                    .catch(err => console.error('Error fetching notifications:', err));
            }

            function renderNotifications(data) {
                const notifications = data.notifications || [];
                const unreadCount = data.unread_count || 0;

                if (notifications.length === 0) {
                    notifList.innerHTML = '<div class="dropdown-item text-center py-3 text-muted">No notifications</div>';
                    notifBadge.classList.add('d-none');
                    return;
                }

                if (unreadCount > 0) {
                    notifBadge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                    notifBadge.classList.remove('d-none');
                    document.querySelector('.unread-label').classList.remove('d-none');
                } else {
                    notifBadge.classList.add('d-none');
                    document.querySelector('.unread-label').classList.add('d-none');
                }

                notifList.innerHTML = '';
                // Show only the latest 8 in dropdown
                notifications.slice(0, 8).forEach(item => {
                    const iconMap = {
                        'inspection': 'fa-clipboard-check',
                        'alarm_due': 'fa-bell',
                        'alarm_update': 'fa-bell',
                        'room_approval': 'fa-door-open',
                        'room_update': 'fa-door-open',
                        'extinguisher_inspection': 'fa-fire-extinguisher',
                        'building_update': 'fa-building',
                        'general': 'fa-info-circle',
                        'alert': 'fa-exclamation-triangle',
                        'event': 'fa-calendar-alt'
                    };
                    const colorMap = {
                        'inspection': 'primary',
                        'alarm_due': 'warning',
                        'alarm_update': 'warning',
                        'room_approval': 'info',
                        'room_update': 'info',
                        'extinguisher_inspection': 'danger',
                        'building_update': 'dark',
                        'general': 'secondary',
                        'alert': 'danger',
                        'event': 'success'
                    };
                    const icon = iconMap[item.type] || 'fa-bell';
                    const color = colorMap[item.type] || 'primary';
                    const isUnread = !item.is_read;

                    const div = document.createElement('div');
                    div.className = `dropdown-item p-2 border-bottom ${isUnread ? 'bg-light' : ''}`;
                    div.style.cursor = 'pointer';
                    div.innerHTML = `
                        <div class="d-flex w-100 align-items-start">
                            <div class="flex-shrink-0 me-2">
                                <span class="badge bg-${color}-subtle text-${color} p-2 rounded">
                                    <i class="fas ${icon}"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1" style="min-width: 0;">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold small text-truncate" style="max-width: 180px;">${item.title}</h6>
                                    <small class="text-muted ms-1" style="font-size: 0.65rem; white-space: nowrap;">${item.time_ago}</small>
                                </div>
                                <p class="mb-1 small text-truncate text-muted" style="max-width: 230px; font-size: 0.75rem;">${item.message}</p>
                                <div class="d-flex align-items-center gap-1">
                                    ${item.school_name ? `<span class="badge bg-primary-subtle text-primary" style="font-size: 0.55rem;"><i class="fas fa-school me-1"></i>${item.school_name}</span>` : ''}
                                    ${isUnread ? '<span class="badge bg-primary" style="font-size: 0.55rem;">New</span>' : ''}
                                </div>
                            </div>
                        </div>
                    `;
                    notifList.appendChild(div);
                });
            }

            // Initial fetch
            fetchNotifications();
            // Auto-refresh every 30 seconds
            setInterval(fetchNotifications, 30000);

            // Auto-select school in Add Alert/Event modals for contributors
            const alertModalEl = document.getElementById('addAlertModal');
            const eventModalEl = document.getElementById('addEventModal');
            if (alertModalEl) {
                alertModalEl.addEventListener('show.bs.modal', () => {
                    const sel = document.getElementById('alertSchoolSelect');
                    if (sel) {
                        if (currentUserRole !== 'admin') {
                            sel.value = currentUserSchoolId || '';
                            sel.disabled = true;
                        }
                    }
                });
            }
            if (eventModalEl) {
                eventModalEl.addEventListener('show.bs.modal', () => {
                    const sel = document.getElementById('eventSchoolSelect');
                    if (sel) {
                        if (currentUserRole !== 'admin') {
                            sel.value = currentUserSchoolId || '';
                            sel.disabled = true;
                        }
                    }
                });
            }

            // Handle Reply Submission
            const replyForm = document.getElementById('notifReplyForm');
            replyForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const data = Object.fromEntries(formData.entries());

                fetch('{{ route("fire-safety.notification.reply") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => {
                    if (!response.ok) {
                        // attempt JSON error otherwise throw text
                        return response.text().then(txt => {
                            try { return Promise.reject(JSON.parse(txt)); }
                            catch(_){ return Promise.reject({ message: txt }); }
                        });
                    }
                    return response.json();
                })
                .then(res => {
                    if (res.success) {
                        bootstrap.Modal.getInstance(document.getElementById('notifReplyModal')).hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'Sent!',
                            text: 'Your reply has been registered.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        fetchNotifications();
                        replyForm.reset();
                    } else {
                        Swal.fire('Error', res.message || 'Failed to send reply.', 'error');
                    }
                })
                .catch(err => {
                    console.error('Error sending reply:', err);
                    const errorMsg = err.message || (err.errors ? Object.values(err.errors).flat().join(', ') : 'Failed to send reply.');
                    Swal.fire('Error', errorMsg, 'error');
                });
            });
        });
    </script>

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

        function switchSchoolAndRedirect(schoolId, redirectUrl) {
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
                    window.location.href = redirectUrl;
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

            // Sidebar Toggle Logic
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    document.body.classList.toggle('show-sidebar');
                });
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    document.body.classList.remove('show-sidebar');
                });
            }

            // Close sidebar on link click (mobile)
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 992) {
                        document.body.classList.remove('show-sidebar');
                    }
                });
            });

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
