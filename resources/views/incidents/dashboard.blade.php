<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- 70/30 Hybrid: Default to desktop view; prevent full mobile collapse -->
    <meta name="viewport" content="width=1024">
    <title>Incidents Compliance Dashboard - DRRM</title>
    <link rel="icon" type="image/png" href="{{ asset('images/incident-checklist-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/incident-checklist-logo.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --incident-yellow: #F2C94C;
            --incident-orange: #F2994A;
            --incident-dark: #333333;
            --incident-light: #fdfcf0;
            --glass-bg: rgba(255, 255, 255, 0.9);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8f9fa;
            color: var(--incident-dark);
            overflow-x: hidden;
        }

        /* Premium Header */
        .header-section {
            background: linear-gradient(135deg, var(--incident-yellow) 0%, var(--incident-orange) 100%);
            padding: 30px 40px;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
            box-shadow: 0 10px 30px rgba(242, 201, 76, 0.3);
            margin-bottom: 40px;
            position: relative;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
        }

        .header-title h1 {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 5px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .header-title p {
            opacity: 0.9;
            font-size: 1.1rem;
            margin-bottom: 0;
        }

        /* Glass Cards */
        .glass-card {
            background: #fff;
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        /* Calendar Styling */
        .calendar-container {
            padding: 20px 20px 15px 20px;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }


        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .calendar-title {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--incident-dark);
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
        }

        .calendar-day-head {
            text-align: center;
            font-weight: 600;
            color: #adb5bd;
            padding-bottom: 10px;
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        .calendar-day {
            aspect-ratio: 1;
            background: #fff;
            border-radius: 12px;
            padding: 10px;
            position: relative;
            border: 1px solid #f1f3f5;
            cursor: pointer;
            contain: layout;
            transition: background-color 0.15s ease-out, border-color 0.15s ease-out;
        }

        .calendar-day:hover {
            background: var(--incident-light);
            border-color: var(--incident-yellow);
        }

        .calendar-day.other-month {
            opacity: 0.3;
            cursor: default;
        }

        .calendar-day .day-num {
            font-weight: 600;
            font-size: 1rem;
        }

        .incident-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin: 1px;
        }

        /* Legend / Filters */
        .legend-tag {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            cursor: pointer;
            border: 1px solid transparent;
        }

        .legend-tag:hover {
            opacity: 0.8;
        }

        .tag-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 8px;
        }

        /* Incident Types Colors */
        .type-cyclone { background-color: #4facfe; color: #fff; }
        .type-rainfall { background-color: #00f2fe; color: #fff; }
        .type-earthquake { background-color: #667eea; color: #fff; }
        .type-landslide { background-color: #a18cd1; color: #fff; }
        .type-flooding { background-color: #38f9d7; color: #333; }
        .type-fire { background-color: #ff0844; color: #fff; }
        .type-accident { background-color: #f093fb; color: #fff; }
        .type-violence { background-color: #434343; color: #fff; }
        .type-others { background-color: #84fab0; color: #333; }

        /* Status Colors */
        .status-holiday { background-color: #f76b1c; border-color: #f76b1c; color: #fff; }
        .status-incident { background-color: #ff0844; border-color: #ff0844; color: #fff; }
        .status-suspended { background-color: #f9d423; border-color: #f9d423; color: #333; }
        .status-no-suspension { background-color: #1ed760; border-color: #1ed760; color: #fff; }
        .status-f2f-suspended { background-color: #30cfd0; border-color: #30cfd0; color: #fff; }

        /* Tables */
        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .custom-table tr {
            background: #fff;
            border-bottom: 1px solid #eee;
        }

        .custom-table td {
            padding: 10px 15px;
            vertical-align: middle;
        }

        .custom-table td:first-child { border-top-left-radius: 0; border-bottom-left-radius: 0; }
        .custom-table td:last-child { border-top-right-radius: 0; border-bottom-right-radius: 0; }

        .school-name {
            font-weight: 600;
            color: var(--incident-dark);
        }

        .stat-card {
            padding: 20px;
            text-align: center;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--incident-orange);
        }

        .stat-label {
            color: #adb5bd;
            font-weight: 500;
            font-size: 0.9rem;
        }

        /* Navigation */
        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            padding: 10px 20px;
            border-radius: 15px;
            text-decoration: none !important;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.4);
            color: #fff;
        }

        /* Modal Styles */
        .incident-modal .tab-content {
            padding: 20px 0;
        }

        .incident-list-item {
            border-left: 4px solid;
            padding-left: 15px;
            margin-bottom: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 12px;
        }

        .incident-actions {
            opacity: 0;
        }

        .incident-list-item:hover .incident-actions {
            opacity: 1;
        }

        .autocomplete-items {
            position: absolute;
            border: 1px solid #ddd;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            top: 100%;
            left: 0;
            right: 0;
            max-height: 200px;
            overflow-y: auto;
        }

        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #ddd;
        }

        .autocomplete-items div:hover {
            background-color: #e9e9e9;
        }

        .date-highlight {
            background-color: var(--incident-light) !important;
            border: 2px solid var(--incident-yellow) !important;
        }

        .date-highlight-focus {
            box-shadow: 0 0 0 3px rgba(242, 201, 76, 0.55), 0 0 20px rgba(242, 153, 74, 0.45);
            animation: focusPulse 1.6s ease-in-out 3;
        }

        @keyframes focusPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.03); }
            100% { transform: scale(1); }
        }

        .calendar-day.has-events {
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
        }

        .day-tooltip {
            position: absolute;
            z-index: 100;
            background: #333;
            color: #fff;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 0.8rem;
            max-width: 320px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.25);
            pointer-events: none;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .day-tooltip .hover-item {
            padding: 4px 0;
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }
        .day-tooltip .hover-item:last-child { border-bottom: none; }
        .day-tooltip .hover-type { font-weight: 600; color: #F2C94C; }
        .day-tooltip .hover-school { color: #ddd; font-size: 0.85em; }

        /* Right Sidebar */
        .right-sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: 380px;
            height: 100vh;
            background: #fff;
            box-shadow: -10px 0 30px rgba(0,0,0,0.1);
            z-index: 1040;
            transform: translateX(100%);
            transition: transform 0.3s ease-out;
            display: flex;
            flex-direction: column;
            will-change: transform;
        }

        .right-sidebar.open {
            transform: translateX(0);
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0.3);
            z-index: 1035;
            display: none;
            opacity: 0;
            transition: opacity 0.2s ease-out;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        .sidebar-content {
            padding: 30px;
            overflow-y: auto;
            flex-grow: 1;
        }

        .sidebar-header {
            padding: 20px 30px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--incident-light);
        }

        .sidebar-close {
            cursor: pointer;
            font-size: 1.5rem;
            color: #adb5bd;
            transition: color 0.2s;
        }

        .sidebar-close:hover {
            color: var(--incident-dark);
        }

        /* Yesterday Display */
        .yesterday-section {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px dashed #dee2e6;
        }

        .yesterday-item {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .yesterday-item i {
            color: #1ed760;
            margin-right: 10px;
        }

        .history-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        @media (max-width: 992px) {
            .history-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .history-grid {
                grid-template-columns: 1fr;
            }
        }

        .history-day-card {
            background: #fff;
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 16px;
            padding: 16px;
            height: 100%;
            transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
            will-change: transform, box-shadow;
            contain: layout;
        }

        .history-day-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
        }

        .history-day-title {
            font-weight: 700;
            font-size: 0.75rem;
            color: var(--incident-dark);
            margin-bottom: 8px;
            border-bottom: 1px solid #f1f3f5;
            padding-bottom: 5px;
        }

        .history-item {
            font-size: 0.7rem;
            margin-bottom: 4px;
            display: flex;
            align-items: flex-start;
        }

        .history-item i {
            font-size: 0.6rem;
            margin-top: 3px;
            margin-right: 5px;
        }

        /* Custom Notification Modal */
        .custom-notify-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.2s ease-out;
        }
        .custom-notify-overlay.show { opacity: 1; }
        .custom-notify-box {
            background: linear-gradient(135deg, var(--incident-yellow) 0%, var(--incident-orange) 100%);
            border-radius: 20px;
            padding: 30px 35px 25px;
            max-width: 420px;
            width: 90%;
            color: #fff;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            transform: scale(0.9) translateY(20px);
            transition: transform 0.25s ease-out;
            text-align: center;
        }
        .custom-notify-overlay.show .custom-notify-box {
            transform: scale(1) translateY(0);
        }
        .custom-notify-icon {
            font-size: 2.5rem;
            margin-bottom: 12px;
            opacity: 0.9;
        }
        .custom-notify-title {
            font-weight: 700;
            font-size: 1.15rem;
            margin-bottom: 8px;
        }
        .custom-notify-message {
            font-size: 0.92rem;
            opacity: 0.95;
            margin-bottom: 22px;
            line-height: 1.5;
            white-space: pre-line;
        }
        .custom-notify-btn {
            background: rgba(255,255,255,0.25);
            border: 2px solid rgba(255,255,255,0.5);
            color: #fff;
            font-weight: 700;
            padding: 8px 32px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background 0.15s ease-out;
            margin: 0 5px;
        }
        .custom-notify-btn:hover {
            background: rgba(255,255,255,0.4);
        }
        .custom-notify-btn.cancel-btn {
            background: rgba(0,0,0,0.15);
            border-color: rgba(255,255,255,0.3);
        }
        .custom-notify-btn.cancel-btn:hover {
            background: rgba(0,0,0,0.25);
        }

        .incident-theme-header {
            background: linear-gradient(135deg, var(--incident-yellow) 0%, var(--incident-orange) 100%);
            color: #fff;
        }
        .incident-theme-header .btn-close {
            filter: brightness(0) invert(1);
        }

        /* ====================================================
         * 70/30 HYBRID MOBILE APPROACH — Incidents Dashboard
         * Desktop structure is preserved. Minimal tweaks only:
         *  1. Scrollable tables
         *  2. Slightly larger button tap targets
         *  3. Stack form columns only
         * ==================================================== */
        /* Triggered by 1024px viewport lock — desktop structure preserved but mobile enhancements active */
        @media (max-width: 1024.1px) {
            /* Scrollable Tables */
            .table-responsive {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch;
            }
            table:not(.custom-table) {
                display: block;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                max-width: 100%;
            }

            /* Slightly Larger Button Tap Targets */
            .btn:not(.btn-sm):not(.btn-xs) {
                min-height: 40px;
                padding-top: 0.45rem !important;
                padding-bottom: 0.45rem !important;
            }

            /* Stack Form Columns Only */
            form .row > [class*="col-md-"],
            form .row > [class*="col-sm-"],
            .modal-body .row > [class*="col-md-"],
            .modal-body .row > [class*="col-sm-"] {
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body>

    <div class="header-section">
        <div class="header-content container-fluid">
            <div class="header-title">
                <a href="{{ route('dashboard') }}" class="back-btn mb-3 d-inline-block">
                    <i class="fas fa-arrow-left me-2"></i> Back to Main
                </a>
                <div class="d-flex align-items-center mb-2">
                    <img src="{{ asset('images/incident-checklist-logo.png') }}" alt="Incident Checklist" style="height: 40px; width: auto; margin-right: 15px;">
                    <h1>Incidents Checklist Dashboard</h1>
                </div>
                <p>Real-time incident tracking and compliance monitoring system</p>
            </div>
            <div class="header-actions d-flex align-items-center gap-2">
                @if(auth()->user()->role === 'admin')
                <button class="btn btn-outline-light btn-lg rounded-pill shadow-sm position-relative me-2" id="adminNotifBtn" title="Pending Reports">
                    <i class="fas fa-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none" id="pendingNotifCount">
                        0
                    </span>
                </button>
                @endif
                <button class="btn btn-light btn-lg rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#logIncidentModal" style="color: var(--incident-orange); font-weight: 600;">
                    <i class="fas fa-plus-circle me-2"></i> Log New Incident
                </button>
                <button class="btn btn-warning btn-lg rounded-pill shadow-sm text-white" id="toggleChecklistSidebar" style="font-weight: 600;">
                    <i class="fas fa-tasks me-2"></i> Quick Checklist
                </button>
            </div>
        </div>
    </div>

    <!-- Right Sidebar for Quick Checklist -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="right-sidebar" id="checklistSidebar">
        <div class="sidebar-header">
            <h5 class="fw-bold mb-0">
                <i class="fas fa-shield-alt text-warning me-2"></i> Quick Checklist
            </h5>
            <div class="sidebar-close" id="closeSidebar">
                <i class="fas fa-times"></i>
            </div>
        </div>
        <div class="sidebar-content">
            <p class="text-muted small mb-4">Today: {{ \Carbon\Carbon::parse($checklistDate)->format('M j, Y') }}</p>
            <div id="checklistContainer" class="list-group list-group-flush bg-transparent mb-4">
                @foreach($checklistItems as $item)
                <div class="list-group-item bg-transparent border-0 px-0 py-2 d-flex align-items-center justify-content-between checklist-item-row" data-id="{{ $item->id }}">
                    <div class="form-check flex-grow-1">
                        <input class="form-check-input checklist-toggle" type="checkbox" id="checklist_{{ $item->id }}" {{ $item->is_completed ? 'checked' : '' }}>
                        <label class="form-check-label fw-600 ms-1 small checklist-label" for="checklist_{{ $item->id }}">{{ $item->label }}</label>
                    </div>
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-sm text-muted ms-1 checklist-edit" title="Edit task" style="padding: 0 5px;">
                            <i class="fas fa-pen x-small" style="font-size: 0.7rem;"></i>
                        </button>
                        <button type="button" class="btn btn-sm text-danger ms-1 checklist-delete" title="Remove item" style="padding: 0 5px;">
                            <i class="fas fa-trash small"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- What you did yesterday -->
            <div class="yesterday-section">
                <h6 class="fw-bold mb-3 small text-uppercase text-muted">What you did yesterday</h6>
                @php $checkedYesterday = ($yesterdayItems ?? collect())->where('is_completed', true); @endphp
                @if($checkedYesterday->count() > 0)
                    @foreach($checkedYesterday as $yItem)
                        <div class="yesterday-item">
                            <i class="fas fa-check-circle"></i>
                            <span>{{ $yItem->label }}</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted small italic mb-0">You didn’t check some tasks yesterday.</p>
                @endif
                <button class="btn btn-outline-secondary btn-sm w-100 mt-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#activityHistoryModal">
                    <i class="fas fa-history me-1"></i> Activity History
                </button>
            </div>

            <div class="mt-auto">
                <label class="form-label small fw-bold text-muted text-uppercase">Add New Task</label>
                <div class="input-group">
                    <input type="text" id="newChecklistLabel" class="form-control" placeholder="Type task here...">
                    <button class="btn btn-warning text-white" type="button" id="addChecklistItemBtn">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="mt-4 p-3 rounded bg-light border">
                    <small class="text-muted d-block mb-2"><i class="fas fa-info-circle me-1"></i> <strong>Note:</strong></small>
                    <p class="mb-0 x-small text-muted" style="font-size: 0.75rem;">This checklist is reset daily. Ensure all critical monitoring tasks are completed before the end of the day.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Incident Type Modal -->
    <div class="modal fade" id="addIncidentTypeModal" tabindex="-1" aria-labelledby="addIncidentTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title" id="addIncidentTypeModalLabel">
                        <i class="fas fa-plus-circle me-1 text-warning"></i> New Incident Type
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <form id="addIncidentTypeForm">
                        @csrf
                        <div class="mb-2">
                            <label for="newIncidentTypeName" class="form-label small">Incident Type Name</label>
                            <input type="text" id="newIncidentTypeName" class="form-control form-control-sm" required placeholder="e.g., Storm Surge">
                        </div>
                    </form>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sm btn-warning" id="saveIncidentTypeBtn">
                        <i class="fas fa-save me-1"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Incident Status/Event Modal -->
    <div class="modal fade" id="addIncidentStatusModal" tabindex="-1" aria-labelledby="addIncidentStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title" id="addIncidentStatusModalLabel">
                        <i class="fas fa-plus-circle me-1 text-warning"></i> New Status / Event
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <form id="addIncidentStatusForm">
                        @csrf
                        <div class="mb-2">
                            <label for="newIncidentStatusName" class="form-label small">Status / Event Name</label>
                            <input type="text" id="newIncidentStatusName" class="form-control form-control-sm" required placeholder="e.g., Division Memo Suspension">
                        </div>
                    </form>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sm btn-warning" id="saveIncidentStatusBtn">
                        <i class="fas fa-save me-1"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4">
        <div class="row g-4">
            <!-- Left Panel: Calendar (70%) -->
            <div class="col-lg-8 mb-4" style="flex: 0 0 70%; max-width: 70%;">
                <div class="glass-card calendar-container">
                    <div class="calendar-header">
                        <div class="calendar-title">
                            <i class="fas fa-calendar-alt me-2 text-warning"></i>
                            @php
                                $calYear = $year ?? (int) date('Y');
                                $calMonth = $month ?? (int) date('n');
                                $prevMonth = $calMonth == 1 ? 12 : $calMonth - 1;
                                $prevYear = $calMonth == 1 ? $calYear - 1 : $calYear;
                                $nextMonth = $calMonth == 12 ? 1 : $calMonth + 1;
                                $nextYear = $calMonth == 12 ? $calYear + 1 : $calYear;
                            @endphp
                            {{ \Carbon\Carbon::create($calYear, $calMonth, 1)->format('F Y') }}
                        </div>
                        <div class="calendar-nav">
                            <a href="{{ route('incidents.dashboard', ['year' => $prevYear, 'month' => $prevMonth]) }}" class="btn btn-sm btn-outline-secondary rounded-circle" title="Previous month">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            <a href="{{ route('incidents.dashboard', ['year' => date('Y'), 'month' => date('n')]) }}" class="btn btn-sm btn-warning rounded-pill mx-2 px-3">Today</a>
                            <a href="{{ route('incidents.dashboard', ['year' => $nextYear, 'month' => $nextMonth]) }}" class="btn btn-sm btn-outline-secondary rounded-circle" title="Next month">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                            <a href="{{ route('incidents.print', ['year' => $calYear, 'month' => $calMonth]) }}" class="btn btn-sm btn-outline-secondary ms-2" title="Print calendar">
                                <i class="fas fa-print me-1"></i> Print Calendar
                            </a>
                        </div>
                    </div>

                    <div class="calendar-grid">
                        <div class="calendar-day-head">Sun</div>
                        <div class="calendar-day-head">Mon</div>
                        <div class="calendar-day-head">Tue</div>
                        <div class="calendar-day-head">Wed</div>
                        <div class="calendar-day-head">Thu</div>
                        <div class="calendar-day-head">Fri</div>
                        <div class="calendar-day-head">Sat</div>

                        @php $calendarData = $calendarData ?? []; @endphp
                        @foreach($calendarData as $week)
                            @foreach($week as $day)
                            @php
                                $dayHoverItems = collect();
                                foreach ($day['incidents'] as $inc) {
                                    $dayHoverItems->push(['type' => 'Incident', 'name' => $inc->incidentType->name ?? 'Incident', 'school' => $inc->school_name ?? '']);
                                }
                                foreach ($day['compliance'] as $ce) {
                                    $isHoliday = ($ce->school_name === 'All Schools') || (($ce->additional_data['source'] ?? null) === 'holiday_api') || (($ce->incidentStatus->name ?? '') === 'Holiday');
                                    $dayHoverItems->push(['type' => $isHoliday ? 'Holiday' : 'Event', 'name' => $isHoliday ? 'Holiday' : ($ce->incidentStatus->name ?? 'Event'), 'school' => $ce->school_name ?? '']);
                                }
                                $dayHoverJson = $dayHoverItems->isEmpty() ? '' : $dayHoverItems->take(8)->toJson();
                            @endphp
                            <div class="calendar-day {{ $day['is_current_month'] ? '' : 'other-month' }} {{ (count($day['incidents']) + count($day['compliance'])) > 0 ? 'has-events' : '' }}"
                                 data-date="{{ $day['date'] }}"
                                 data-day-num="{{ $day['day'] }}"
                                 data-day-hover="{{ e($dayHoverJson) }}"
                                 title="{{ $day['date'] }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span class="day-num">{{ $day['day'] }}</span>
                                    @if(count($day['compliance']) > 0)
                                        @foreach($day['compliance']->take(1) as $ce)
                                            @php
                                                $isHoliday = ($ce->school_name === 'All Schools') || (($ce->additional_data['source'] ?? null) === 'holiday_api') || (($ce->incidentStatus->name ?? '') === 'Holiday');
                                            @endphp
                                            <span class="badge {{ $isHoliday ? 'bg-warning text-dark' : ($ce->incidentStatus->color_class ?? 'status-no-suspension') }}" title="{{ $isHoliday ? 'Holiday' : ($ce->incidentStatus->name ?? 'Event') }}">{{ $isHoliday ? 'H' : 'E' }}</span>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="mt-2">
                                    @foreach($day['incidents'] as $inc)
                                        <span class="incident-dot {{ $inc->incidentType->color_class ?? 'type-others' }}" title="{{ $inc->incidentType->name ?? 'Incident' }}"></span>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        @endforeach
                    </div>


                    <!-- Legend & Filters Inside Calendar -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="fw-bold mb-3">Legend & Filters</h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="text-muted small text-uppercase mb-0">Incident Types</h6>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="addIncidentTypeBtn" title="Add incident type" style="padding: 1px 6px; font-size: 10px;">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($incidentTypes as $type)
                                        <div class="legend-tag {{ $type->color_class ?? 'type-others' }} edit-type-btn" data-id="{{ $type->id }}" data-name="{{ $type->name }}" style="padding: 2px 8px; font-size: 0.7rem;">
                                            <div class="tag-dot" style="width: 6px; height: 6px; background: {{ in_array($type->color_class, ['type-flooding','type-others']) ? '#333' : '#fff' }};"></div>
                                            <span>{{ $type->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="text-muted small text-uppercase mb-0">Compliance Status / Events</h6>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="addIncidentStatusBtn" title="Add status/event" style="padding: 1px 6px; font-size: 10px;">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($incidentStatuses as $status)
                                        <div class="legend-tag {{ $status->color_class ?? 'status-no-suspension' }} edit-status-btn" data-id="{{ $status->id }}" data-name="{{ $status->name }}" style="padding: 2px 8px; font-size: 0.7rem;">
                                            <span>{{ $status->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Container (30%) -->
            <div class="col-lg-4" style="flex: 0 0 30%; max-width: 30%;">
                <div class="row">
                    <!-- Panel: Incident Specifics (Full width of the 30% container) -->
                    <div class="col-lg-12 mb-4">
                        <div class="glass-card p-4">
                            <h5 class="fw-bold mb-4">
                                <i class="fas fa-info-circle me-2 text-warning"></i>
                                Monthly Incident Specifics
                            </h5>
                            <!-- ... existing table structure ... -->
                            <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
                                <table class="custom-table">
                                    <tbody>
                                        @forelse($incidents ?? [] as $incident)
                                        <tr>
                                            <td>
                                                <div class="fw-bold" style="font-size: 0.85rem;">{{ $incident->incident_date->format('M j') }}</div>
                                                <small class="text-muted" style="font-size: 0.7rem;">{{ $incident->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <div class="school-name" style="font-size: 0.85rem;">{{ ($incident->entry_type === 'compliance' && (($incident->school_name === 'All Schools') || (($incident->additional_data['source'] ?? null) === 'holiday_api') || (($incident->incidentStatus->name ?? '') === 'Holiday'))) ? 'All Schools' : $incident->school_name }}</div>
                                                @if($incident->entry_type === 'incident' && $incident->incidentType)
                                                    <span class="badge {{ $incident->incidentType->color_class }}" style="font-size: 10px; padding: 2px 5px;">{{ $incident->incidentType->name }}</span>
                                                @elseif($incident->entry_type === 'compliance' && $incident->incidentStatus)
                                                    @php
                                                        $isHoliday = ($incident->school_name === 'All Schools') || (($incident->additional_data['source'] ?? null) === 'holiday_api') || (($incident->incidentStatus->name ?? '') === 'Holiday');
                                                    @endphp
                                                    <span class="badge {{ $isHoliday ? 'bg-warning text-dark' : $incident->incidentStatus->color_class }}" style="font-size: 10px; padding: 2px 5px;">{{ $isHoliday ? 'Holiday' : $incident->incidentStatus->name }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-truncate d-block" style="max-width: 150px; font-size: 0.75rem;">{{ Str::limit($incident->remarks, 50) }}</small>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-outline-secondary rounded-circle" onclick="viewIncidentDetails({{ $incident->id }})" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <div class="mb-3">
                                                    <i class="fas fa-folder-open fa-2x text-muted"></i>
                                                </div>
                                                <p class="text-muted small">No incidents recorded for this month</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 pt-3 border-top">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="stat-value" style="font-size: 1.2rem;">{{ $stats['total'] ?? 0 }}</div>
                                        <div class="stat-label">Total Logs</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-value text-danger" style="font-size: 1.2rem;">{{ $stats['incidents'] ?? 0 }}</div>
                                        <div class="stat-label">Incidents</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-value text-success" style="font-size: 1.2rem;">{{ $stats['compliance'] ?? 0 }}</div>
                                        <div class="stat-label">Compliance</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Separate Charts Division (Covers specifics and checklist below them) -->
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="glass-card p-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                <h5 class="fw-bold mb-0">
                                    <i class="fas fa-chart-bar me-2 text-warning"></i>
                                    Analytics Distribution & Trend
                                </h5>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('incidents.analytics.print', ['type' => 'incident_type', 'year' => $calYear, 'month' => $calMonth]) }}" class="btn btn-sm btn-outline-secondary analytics-print-btn" data-chart-target="incidentTypeChart" title="Print Incident Type Distribution">
                                        <i class="fas fa-print me-1"></i> Print Incident Type
                                    </a>
                                    <a href="{{ route('incidents.analytics.print', ['type' => 'compliance_status', 'year' => $calYear, 'month' => $calMonth]) }}" class="btn btn-sm btn-outline-secondary analytics-print-btn" data-chart-target="complianceDistributionChart" title="Print Compliance Status / Events">
                                        <i class="fas fa-print me-1"></i> Print Compliance Status
                                    </a>
                                </div>
                            </div>
                            <div class="row g-3">
                                <!-- Left Chart: Incident Type Distribution -->
                                <div class="col-lg-6 border-end">
                                    <h6 class="text-muted text-uppercase mb-3" style="font-size: 10px; font-weight: 700;">
                                        <i class="fas fa-chart-pie me-1 text-warning"></i> Incident Type Distribution
                                    </h6>
                                    <div style="height: 180px; position: relative;">
                                        <canvas id="incidentTypeChart"></canvas>
                                    </div>
                                </div>

                                <!-- Right Chart: Compliance Status Distribution -->
                                <div class="col-lg-6 ps-lg-4">
                                    <h6 class="text-muted text-uppercase mb-3" style="font-size: 10px; font-weight: 700;">
                                        <i class="fas fa-chart-bar me-1 text-warning"></i> Compliance Status / Events
                                    </h6>
                                    <div style="height: 180px; position: relative;">
                                        <canvas id="complianceDistributionChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('incidents.partials.log-modal')

    <!-- Pending Reports Modal for Admin -->
    <div class="modal fade" id="pendingReportsModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header incident-theme-header">
                    <h5 class="modal-title"><i class="fas fa-clipboard-check me-2"></i> Pending Contributor Reports</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Date</th>
                                    <th>Contributor</th>
                                    <th>School</th>
                                    <th>Type</th>
                                    <th>Remarks</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="pendingReportsTable">
                                <!-- Loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                    <div id="pendingEmptyState" class="text-center py-5 d-none">
                        <i class="fas fa-check-circle fa-3x text-success mb-3 opacity-25"></i>
                        <p class="text-muted">No pending reports to review.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Reason Modal -->
    <div class="modal fade" id="rejectionReasonModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white py-2">
                    <h6 class="modal-title">Reason for Rejection</h6>
                </div>
                <div class="modal-body">
                    <textarea id="rejectionReasonText" class="form-control" rows="3" placeholder="Explain why this was rejected..."></textarea>
                </div>
                <div class="modal-footer py-2">
                    <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-sm btn-danger" id="confirmRejectBtn">Reject Report</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Day Details Modal -->
    <div class="modal fade" id="dayDetailsModal" tabindex="-1" aria-labelledby="dayDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="dayDetailsModalLabel">
                        <i class="fas fa-calendar-day me-2"></i> Day Details - <span id="modalDayDate"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Incidents Section -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i> Incidents</h6>
                                </div>
                                <div class="card-body" style="max-height: 400px; overflow-y: auto;" id="dayIncidentsList">
                                    <div class="text-center text-muted py-5">
                                        <i class="fas fa-folder-open fa-3x mb-3"></i>
                                        <p>No incidents for this day</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Compliance Events Section -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-calendar-check me-2"></i> Compliance Events</h6>
                                </div>
                                <div class="card-body" style="max-height: 400px; overflow-y: auto;" id="dayComplianceList">
                                    <div class="text-center text-muted py-5">
                                        <i class="fas fa-calendar fa-3x mb-3"></i>
                                        <p>No compliance events for this day</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" onclick="logForSelectedDate()">
                        <i class="fas fa-plus-circle me-2"></i> Log New Entry
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Incident Detail Modal (Specific Item) -->
    <div class="modal fade" id="incidentDetailInfoModal" tabindex="-1" aria-labelledby="incidentDetailInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white p-4">
                    <h5 class="modal-title fw-bold" id="incidentDetailInfoModalLabel">
                        <i class="fas fa-info-circle text-warning me-2"></i> Log Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-white" id="incidentDetailBody">
                    <!-- Content populated by JS -->
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity History Modal -->
    <div class="modal fade" id="activityHistoryModal" tabindex="-1" aria-labelledby="activityHistoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center w-100 me-4">
                        <h5 class="modal-title fw-bold mb-2 mb-md-0" id="activityHistoryModalLabel">
                            <i class="fas fa-history text-warning me-2"></i> Activity History
                        </h5>
                        <div class="d-flex align-items-center gap-3 bg-secondary bg-opacity-25 rounded-pill px-3 py-1">
                            <button type="button" class="btn btn-sm text-white p-0" id="prevHistoryMonth" title="Previous Month">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <span id="currentHistoryMonthLabel" class="fw-600 small" style="min-width: 120px; text-align: center;">
                                {{ \Carbon\Carbon::today()->format('F Y') }}
                            </span>
                            <button type="button" class="btn btn-sm text-white p-0" id="nextHistoryMonth" title="Next Month">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light" style="max-height: 70vh; overflow-y: auto;" id="activityHistoryBody">
                    <div class="history-grid" id="historyGridContainer">
                        @foreach($historyData as $date => $items)
                        <div class="history-day-card shadow-sm">
                            <div class="history-day-title d-flex justify-content-between">
                                <span>{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</span>
                                <span class="text-muted" style="font-size: 0.65rem;">{{ \Carbon\Carbon::parse($date)->format('l') }}</span>
                            </div>
                            @foreach($items as $item)
                            <div class="history-item">
                                @if($item->is_completed)
                                    <i class="fas fa-check-circle text-success"></i>
                                @else
                                    <i class="fas fa-times-circle text-muted"></i>
                                @endif
                                <span class="{{ $item->is_completed ? 'text-dark fw-bold' : 'text-muted' }} text-truncate" title="{{ $item->label }}">{{ $item->label }}</span>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                        @if($historyData->isEmpty())
                        <div class="col-12 py-5 text-center w-100">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3 opacity-25"></i>
                            <p class="text-muted">No activities recorded for this period.</p>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer bg-white border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close History</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const incidentsDateUrl = '{{ url("/incidents/date") }}';
        const incidentsStoreUrl = '{{ route("incidents.store") }}';
        const incidentTypeStoreUrl = '{{ route("incidents.types.store") }}';
        const incidentTypeUpdateBaseUrl = '{{ url("/incidents/types") }}';
        const incidentStatusStoreUrl = '{{ route("incidents.statuses.store") }}';
        const incidentStatusUpdateBaseUrl = '{{ url("/incidents/statuses") }}';
        const incidentsExportUrl = '{{ url("/incidents/export") }}';
        const incidentsImportUrl = '{{ url("/incidents/import") }}';
        const checklistIndexUrl = '{{ route("incidents.checklist.index") }}';
        const checklistStoreUrl = '{{ route("incidents.checklist.store") }}';
        const checklistUpdateBaseUrl = '{{ url("/incidents/checklist") }}';
        const checklistHistoryUrl = '{{ route("incidents.checklist.history") }}';
        const checklistDate = '{{ $checklistDate }}';

        const typeChartData = {
            labels: @json($stats['type_distribution']['labels'] ?? []),
            values: @json($stats['type_distribution']['values'] ?? []),
        };
        const complianceChartData = {
            labels: @json($stats['compliance_distribution']['labels'] ?? []),
            values: @json($stats['compliance_distribution']['values'] ?? []),
        };

        const focusDate = @json($focusDate ?? null);
        const focusReportId = @json($focusReportId ?? null);

        const allIncidents = @json($incidents);
        let incidentDetailModal = null;
        let currentViewingIncidentId = null;

        /**
         * Custom Notification Modal (replaces alert)
         * @param {string} message - The message to show
         * @param {string} title - Optional title
         * @param {string} icon - FontAwesome icon class
         */
        window.showNotify = function(message, title = 'Notification', icon = 'fa-info-circle') {
            return new Promise((resolve) => {
                const overlay = document.createElement('div');
                overlay.className = 'custom-notify-overlay';
                overlay.innerHTML = `
                    <div class="custom-notify-box">
                        <div class="custom-notify-icon"><i class="fas ${icon}"></i></div>
                        <div class="custom-notify-title">${title}</div>
                        <div class="custom-notify-message">${message}</div>
                        <button class="custom-notify-btn">OK</button>
                    </div>
                `;
                document.body.appendChild(overlay);

                // Trigger animation
                setTimeout(() => overlay.classList.add('show'), 10);

                const close = () => {
                    overlay.classList.remove('show');
                    setTimeout(() => {
                        overlay.remove();
                        resolve();
                    }, 250);
                };

                overlay.querySelector('.custom-notify-btn').onclick = close;
                overlay.onclick = (e) => { if (e.target === overlay) close(); };
            });
        };

        /**
         * Custom Confirmation Modal (replaces confirm)
         * @param {string} message - The question to ask
         * @param {string} title - Optional title
         * @returns {Promise<boolean>}
         */
        window.showConfirm = function(message, title = 'Are you sure?') {
            return new Promise((resolve) => {
                const overlay = document.createElement('div');
                overlay.className = 'custom-notify-overlay';
                overlay.innerHTML = `
                    <div class="custom-notify-box">
                        <div class="custom-notify-icon"><i class="fas fa-question-circle"></i></div>
                        <div class="custom-notify-title">${title}</div>
                        <div class="custom-notify-message">${message}</div>
                        <div class="d-flex justify-content-center">
                            <button class="custom-notify-btn cancel-btn">Cancel</button>
                            <button class="custom-notify-btn confirm-btn">Yes, Proceed</button>
                        </div>
                    </div>
                `;
                document.body.appendChild(overlay);

                setTimeout(() => overlay.classList.add('show'), 10);

                const handle = (result) => {
                    overlay.classList.remove('show');
                    setTimeout(() => {
                        overlay.remove();
                        resolve(result);
                    }, 250);
                };

                overlay.querySelector('.cancel-btn').onclick = () => handle(false);
                overlay.querySelector('.confirm-btn').onclick = () => handle(true);
                overlay.onclick = (e) => { if (e.target === overlay) handle(false); };
            });
        };

        window.viewIncidentDetails = function(id) {
            if (!incidentDetailModal) {
                const modalEl = document.getElementById('incidentDetailInfoModal');
                if (modalEl) incidentDetailModal = new bootstrap.Modal(modalEl);
            }
            const incident = allIncidents.find(i => i.id === id);
            if (!incident) return;

            currentViewingIncidentId = id;
            const body = document.getElementById('incidentDetailBody');
            const date = new Date(incident.incident_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
            const time = new Date(incident.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

            let typeBadge = '';
            const type = incident.incident_type || incident.incidentType;
            const status = incident.incident_status || incident.incidentStatus;
            const isHoliday = incident.entry_type === 'compliance' && (
                incident.school_name === 'All Schools' ||
                incident.additional_data?.source === 'holiday_api' ||
                status?.name === 'Holiday'
            );

            if (incident.entry_type === 'incident' && type) {
                typeBadge = `<span class="badge ${type.color_class}">${type.name}</span>`;
            } else if (incident.entry_type === 'compliance' && status) {
                typeBadge = `<span class="badge ${isHoliday ? 'bg-warning text-dark' : status.color_class}">${isHoliday ? 'Holiday' : status.name}</span>`;
            }

            let attachmentHtml = '<p class="text-muted small italic">No attachment</p>';
            if (incident.attachment_path) {
                const url = `/storage/${incident.attachment_path}`;
                attachmentHtml = `
                    <div class="mt-2">
                        <a href="${url}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                            <i class="fas fa-paperclip me-1"></i> View Attachment
                        </a>
                    </div>
                `;
            }

            body.innerHTML = `
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold mb-0 text-dark">${isHoliday ? 'All Schools' : incident.school_name}</h4>
                        ${typeBadge}
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="text-muted small text-uppercase fw-bold d-block">Report Date</label>
                            <span><i class="fas fa-calendar-day me-1 text-primary"></i> ${date}</span>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small text-uppercase fw-bold d-block">Report Time</label>
                            <span><i class="fas fa-clock me-1 text-primary"></i> ${time}</span>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small text-uppercase fw-bold d-block">Category</label>
                            <span class="text-capitalize">${incident.entry_type}</span>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small text-uppercase fw-bold d-block">Reported By</label>
                            <span>${incident.reported_by || 'Anonymous'}</span>
                        </div>
                    </div>

                    <div class="mb-4 p-3 bg-light rounded shadow-sm border-start border-4 border-warning">
                        <label class="text-muted small text-uppercase fw-bold d-block mb-2">Remarks / Details</label>
                        <p class="mb-0" style="white-space: pre-wrap;">${incident.remarks}</p>
                    </div>

                    <div class="mb-0">
                        <label class="text-muted small text-uppercase fw-bold d-block mb-1">Supporting Document</label>
                        ${attachmentHtml}
                    </div>
                </div>
            `;

            incidentDetailModal.show();
        };



        function escapeHtml(s) {
            const d = document.createElement('div');
            d.textContent = s;
            return d.innerHTML;
        }

        function formatFileSize(bytes) {
            if (!bytes) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        }

        // Calendar day click: fetch and show day details
        document.addEventListener('DOMContentLoaded', function() {
            // Day hover: custom info box
            let tooltipEl = null;
            document.querySelectorAll('.calendar-day').forEach(dayEl => {
                dayEl.addEventListener('mouseenter', function(e) {
                    const raw = this.getAttribute('data-day-hover');
                    if (!raw || raw === '[]') return;
                    let items;
                    try { items = JSON.parse(raw); if (!items || items.length === 0) return; } catch (err) { return; }
                    if (tooltipEl) tooltipEl.remove();
                    tooltipEl = document.createElement('div');
                    tooltipEl.className = 'day-tooltip';
                    tooltipEl.innerHTML = items.map(it => {
                        const typeLabel = (it.type === 'Incident' ? 'Incident' : 'Event') + ': ' + (it.name || it.type);
                        const school = (it.school || '').trim() || '—';
                        return '<div class="hover-item"><span class="hover-type">' + escapeHtml(typeLabel) + '</span><br><span class="hover-school">' + escapeHtml(school) + '</span></div>';
                    }).join('');
                    document.body.appendChild(tooltipEl);
                    const rect = this.getBoundingClientRect();
                    tooltipEl.style.left = (rect.left + rect.width / 2 - tooltipEl.offsetWidth / 2) + 'px';
                    tooltipEl.style.top = (rect.top - tooltipEl.offsetHeight - 8) + 'px';
                    if (rect.top - tooltipEl.offsetHeight < 8) tooltipEl.style.top = (rect.bottom + 8) + 'px';
                });
                dayEl.addEventListener('mouseleave', function() {
                    if (tooltipEl) { tooltipEl.remove(); tooltipEl = null; }
                });
            });

            document.querySelectorAll('.calendar-day').forEach(dayEl => {
                dayEl.addEventListener('click', function() {
                    if (this.classList.contains('other-month')) return;
                    const date = this.getAttribute('data-date');
                    if (!date) return;
                    const d = new Date(date + 'T12:00:00');
                    const modalDayDate = document.getElementById('modalDayDate');
                    const incList = document.getElementById('dayIncidentsList');
                    const compList = document.getElementById('dayComplianceList');

                    if (modalDayDate) modalDayDate.textContent = d.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                    if (incList) incList.innerHTML = '<div class="text-center text-muted py-3"><small>Loading...</small></div>';
                    if (compList) compList.innerHTML = '<div class="text-center text-muted py-3"><small>Loading...</small></div>';

                    const modalEl = document.getElementById('dayDetailsModal');
                    if (!modalEl) return;
                    let modal = bootstrap.Modal.getInstance(modalEl);
                    if (!modal) modal = new bootstrap.Modal(modalEl);
                    modal.show();
                    window._selectedDateForLog = date;

                    fetch(incidentsDateUrl + '/' + date, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                        .then(r => r.json())
                        .then(data => {
                            window._currentDayData = data; 
                            const toArray = (x) => Array.isArray(x) ? x : (x && typeof x === 'object' ? Object.values(x) : []);
                            const incidents = toArray(data.incidents);
                            const compliance = toArray(data.compliance);
                            
                            if (incList) {
                                if (incidents.length) {
                                    incList.innerHTML = incidents.map(i => `
                                        <div class="incident-list-item border-left mb-3 p-3" style="border-color:#667eea; border-width:4px;">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <span class="badge bg-danger mb-2">INCIDENT</span>
                                                    <h6 class="fw-bold mb-2">${escapeHtml(i.incident_type?.name || 'Incident')}</h6>
                                                </div>
                                                <small class="text-muted">Reported by: ${escapeHtml(i.contributor?.name || i.reported_by || 'Unknown')}</small>
                                            </div>
                                            <div class="mb-2"><i class="fas fa-school text-muted me-2"></i> ${escapeHtml(i.school_name || 'N/A')}</div>
                                            <div class="row mb-2">
                                                <div class="col-6"><small class="text-muted d-block"><i class="fas fa-users me-1"></i> Personnel: ${i.affected_personnel || 0}</small></div>
                                                <div class="col-6"><small class="text-muted d-block"><i class="fas fa-child me-1"></i> Students: ${i.affected_students || 0}</small></div>
                                            </div>
                                            <div class="bg-light p-2 rounded mb-2"><i class="fas fa-comment-alt text-muted me-2"></i> ${escapeHtml(i.remarks || 'No remarks')}</div>
                                            ${i.attachment_path ? `<div class="mt-2"><a href="${i.attachment_url}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-paperclip me-1"></i> ${escapeHtml(i.attachment_name || 'Attachment')} <span class="badge bg-secondary ms-1">${formatFileSize(i.attachment_size)}</span></a></div>` : ''}
                                            <div class="mt-2 d-flex justify-content-between align-items-center">
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-outline-warning" onclick="editIncidentEntry(${i.id}, 'incident')"><i class="fas fa-edit me-1"></i> Edit</button>
                                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteIncidentEntry(${i.id})"><i class="fas fa-trash me-1"></i></button>
                                                </div>
                                                <small class="text-muted"><i class="fas fa-clock me-1"></i> ${new Date(i.created_at).toLocaleString()}</small>
                                            </div>
                                        </div>`).join('');
                                } else {
                                    incList.innerHTML = '<div class="text-center text-muted py-5"><i class="fas fa-folder-open fa-3x mb-3"></i><p>No incidents for this day</p><a href="#" class="text-warning fw-bold" onclick="logForSelectedDateWithTab(event, \'incident\');">Log new entry?</a></div>';
                                }
                            }
                            if (compList) {
                                if (compliance.length) {
                                    compList.innerHTML = compliance.map(c => `
                                        <div class="incident-list-item border-left mb-3 p-3" style="border-color:#1ed760; border-width:4px;">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <span class="badge bg-success mb-2">COMPLIANCE EVENT</span>
                                                    <h6 class="fw-bold mb-2">${escapeHtml(c.incident_status?.name || 'Event')}</h6>
                                                </div>
                                                <small class="text-muted">Reported by: ${escapeHtml(c.contributor?.name || c.reported_by || 'Unknown')}</small>
                                            </div>
                                            <div class="mb-2"><i class="fas fa-school text-muted me-2"></i> ${escapeHtml(c.school_name || 'N/A')}</div>
                                            <div class="bg-light p-2 rounded mb-2"><i class="fas fa-comment-alt text-muted me-2"></i> ${escapeHtml(c.remarks || 'No remarks')}</div>
                                            ${c.attachment_path ? `<div class="mt-2"><a href="${c.attachment_url}" target="_blank" class="btn btn-sm btn-outline-success"><i class="fas fa-paperclip me-1"></i> ${escapeHtml(c.attachment_name || 'Attachment')} <span class="badge bg-secondary ms-1">${formatFileSize(c.attachment_size)}</span></a></div>` : ''}
                                            <div class="mt-2 d-flex justify-content-between align-items-center">
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-outline-warning" onclick="editIncidentEntry(${c.id}, 'compliance')"><i class="fas fa-edit me-1"></i> Edit</button>
                                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteIncidentEntry(${c.id})"><i class="fas fa-trash me-1"></i></button>
                                                </div>
                                                <small class="text-muted"><i class="fas fa-clock me-1"></i> ${new Date(c.created_at).toLocaleString()}</small>
                                            </div>
                                        </div>`).join('');
                                } else {
                                    compList.innerHTML = '<div class="text-center text-muted py-5"><i class="fas fa-calendar fa-3x mb-3"></i><p>No compliance events for this day</p><a href="#" class="text-warning fw-bold" onclick="logForSelectedDateWithTab(event, \'compliance\');">Log new entry?</a></div>';
                                }
                            }
                        })
                        .catch(() => {
                            if (incList) incList.innerHTML = '<div class="text-center text-muted py-5"><p>Failed to load</p></div>';
                            if (compList) compList.innerHTML = '<div class="text-center text-muted py-5"><p>Failed to load</p></div>';
                        });
                });
            });
        });

        function buildFormData(form) {
            const fd = new FormData(form);
            const data = {};
            fd.forEach((v, k) => data[k] = v);
            if (form.id === 'complianceForm') {
                data.incident_date = document.getElementById('compliance_incident_date').value;
            }
            return data;
        }

        async function submitIncidentForm(form, isCompliance) {
            const formData = new FormData(form);
            formData.append('_token', csrfToken);

            // Handle ID for update
            let updateId = isCompliance ? document.getElementById('compliance_update_id').value : document.getElementById('incident_update_id').value;
            let method = 'POST';
            let url = incidentsStoreUrl;

            if (updateId) {
                method = 'POST';
                formData.append('_method', 'PUT');
                url = incidentsStoreUrl.split('/store')[0] + '/' + updateId;
            }

            // Handle school name based on source
            if (!isCompliance) {
                const sourceInput = form.querySelector('input[name="incident_source_type"]:checked');
                const source = sourceInput ? sourceInput.value : 'existing';
                if (source === 'all') {
                    formData.set('school_name', 'All Schools');
                } else if (source === 'existing') {
                    formData.set('school_name', form.querySelector('#incident_school_existing_select').value);
                }
            } else {
                const sourceInput = form.querySelector('input[name="compliance_source_type"]:checked');
                const source = sourceInput ? sourceInput.value : 'existing';
                if (source === 'all') {
                    formData.set('school_name', 'All Schools');
                } else if (source === 'existing') {
                    formData.set('school_name', form.querySelector('#compliance_school_existing_select').value);
                }
            }

            // Validation
            if (!formData.get('school_name')) {
                await showNotify('Please select or enter a school name.', 'Missing Information', 'fa-school');
                return;
            }

            // Attachment logic for incidents
            if (!isCompliance) {
                const fileInput = form.querySelector('#incident_attachment');
                const hasNewFile = fileInput && fileInput.files && fileInput.files.length > 0;
                const hasExistingAttachment = document.getElementById('current_incident_attachment').style.display !== 'none';
                const isUpdate = !!updateId;

                if (isUpdate && !hasExistingAttachment && !hasNewFile) {
                    // First update with NO existing attachment: attachment is REQUIRED
                    await showNotify('This incident has no evidence attached yet. Please attach a file before updating.', 'Evidence Required', 'fa-file-upload');
                    fileInput.focus();
                    return;
                }

                if (!isUpdate && !hasNewFile) {
                    // New incident with no file: ask user to confirm
                    if (!(await showConfirm('No evidence attached.\n\nLog incident now, attach evidence later?', 'Confirmation Required'))) {
                        return;
                    }
                }
                // If isUpdate && hasExistingAttachment && !hasNewFile -> optional, keep existing
            }

            const btn = form.querySelector('button[type="submit"]');
            const origText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(r => r.json())
            .then(async resp => {
                if (resp.success) {
                    bootstrap.Modal.getInstance(document.getElementById('logIncidentModal')).hide();
                    window.location.reload();
                } else {
                    await showNotify(resp.message || 'Failed to save', 'Error', 'fa-exclamation-triangle');
                    btn.disabled = false;
                    btn.innerHTML = origText;
                }
            })
            .catch(async err => {
                await showNotify('Failed to save. Check your connection.', 'Connection Error', 'fa-wifi');
                btn.disabled = false;
                btn.innerHTML = origText;
            });
        }

        window.editIncidentEntry = async function(id, type) {
            const data = window._currentDayData;
            if (!data) return;

            const list = type === 'incident' ? data.incidents : data.compliance;
            const item = Object.values(list).find(i => i.id === id);
            if (!item) return;

            // Hide the details modal
            const dayModal = bootstrap.Modal.getInstance(document.getElementById('dayDetailsModal'));
            if (dayModal) dayModal.hide();

            setTimeout(async () => {
                const logModalEl = document.getElementById('logIncidentModal');
                let logModal = bootstrap.Modal.getInstance(logModalEl);
                if (!logModal) logModal = new bootstrap.Modal(logModalEl);

                const modalTitle = document.getElementById('logIncidentModalLabel');
                const tabs = document.getElementById('incidentTab');

                // Reset form first
                document.getElementById('incidentForm').reset();
                document.getElementById('complianceForm').reset();

                modalTitle.innerHTML = `<i class="fas fa-edit me-2"></i> Update ${type === 'incident' ? 'Incident' : 'Compliance Event'}`;
                tabs.style.display = 'none'; // Hide tabs as requested

                if (type === 'incident') {
                    const form = document.getElementById('incidentForm');
                    document.getElementById('incident-form').classList.add('show', 'active');
                    document.getElementById('compliance-form').classList.remove('show', 'active');

                    document.getElementById('incident_update_id').value = item.id;
                    document.getElementById('incident_type_id').value = item.incident_type_id;
                    document.getElementById('incident_date_input').value = item.incident_date.split('T')[0];
                    document.getElementById('incident_date').value = item.incident_date.split('T')[0];

                    // School handling
                    const existingSelect = document.getElementById('incident_school_existing_select');
                    const options = Array.from(existingSelect.options);
                    const match = options.find(o => o.value === item.school_name);

                    const isHolidayRecord = item.school_name === 'All Schools' || item.additional_data?.source === 'holiday_api' || item.incidentStatus?.name === 'Holiday';

                    if (isHolidayRecord) {
                        document.getElementById('incident_source_all').checked = true;
                        document.getElementById('incident_existing_school_container').style.display = 'none';
                        existingSelect.value = '';
                    } else if (match) {
                        document.getElementById('incident_source_existing').checked = true;
                        existingSelect.value = item.school_name;
                        document.getElementById('incident_existing_school_container').style.display = 'block';
                    } else {
                        document.getElementById('incident_existing_school_container').style.display = 'none';
                        await showNotify('This log uses an unregistered school name. Please pick a registered school or set "All Schools".', 'School Name Not Found', 'fa-school');
                        return;
                    }

                    document.getElementById('affected_personnel').value = item.affected_personnel;
                    document.getElementById('affected_students').value = item.affected_students;
                    document.getElementById('remarks').value = item.remarks;

                    // Show current attachment if exists
                    const currentAttach = document.getElementById('current_incident_attachment');
                    const attachHint = document.getElementById('incident_attachment_hint');
                    if (item.attachment_path) {
                        document.getElementById('incident_attachment_name').textContent = item.attachment_name;
                        document.getElementById('incident_attachment_view').href = item.attachment_url;
                        currentAttach.style.display = 'block';
                        document.getElementById('incident_attachment_required_asterisk').style.display = 'none';
                        attachHint.textContent = 'Optional — a file is already attached. Upload to replace it.';
                    } else {
                        currentAttach.style.display = 'none';
                        document.getElementById('incident_attachment_required_asterisk').style.display = 'inline';
                        attachHint.textContent = 'Required — no evidence attached yet.';
                        attachHint.classList.add('text-danger');
                        attachHint.classList.remove('text-muted');
                    }

                    // File input is never HTML-required; JS handles the logic
                    document.getElementById('incident_attachment').required = false;

                } else {
                    const form = document.getElementById('complianceForm');
                    document.getElementById('compliance-form').classList.add('show', 'active');
                    document.getElementById('incident-form').classList.remove('show', 'active');

                    document.getElementById('compliance_update_id').value = item.id;
                    document.getElementById('incident_status_id').value = item.incident_status_id;
                    document.getElementById('compliance_date_input').value = item.incident_date.split('T')[0];
                    document.getElementById('compliance_incident_date').value = item.incident_date.split('T')[0];

                    // School handling
                    const existingSelect = document.getElementById('compliance_school_existing_select');
                    const options = Array.from(existingSelect.options);
                    const match = options.find(o => o.value === item.school_name);

                    const isHolidayRecord = item.school_name === 'All Schools' || item.additional_data?.source === 'holiday_api' || item.incidentStatus?.name === 'Holiday';

                    if (isHolidayRecord) {
                        document.getElementById('compliance_source_all').checked = true;
                        document.getElementById('compliance_existing_school_container').style.display = 'none';
                        existingSelect.value = '';
                    } else if (match) {
                        document.getElementById('compliance_source_existing').checked = true;
                        existingSelect.value = item.school_name;
                        document.getElementById('compliance_existing_school_container').style.display = 'block';
                    } else {
                        document.getElementById('compliance_existing_school_container').style.display = 'none';
                        await showNotify('This log uses an unregistered school name. Please pick a registered school or set "All Schools".', 'School Name Not Found', 'fa-school');
                        return;
                    }

                    document.getElementById('compliance_remarks').value = item.remarks;

                    // Show current attachment if exists
                    const currentAttach = document.getElementById('current_compliance_attachment');
                    if (item.attachment_path) {
                        document.getElementById('compliance_attachment_name').textContent = item.attachment_name;
                        document.getElementById('compliance_attachment_view').href = item.attachment_url;
                        currentAttach.style.display = 'block';
                    } else {
                        currentAttach.style.display = 'none';
                    }
                }

                logModal.show();
            }, 300);
        };

        window.deleteIncidentEntry = async function(id) {
            if (!(await showConfirm('Are you sure you want to delete this log?', 'Confirm Deletion'))) return;

            fetch(`${incidentsStoreUrl.replace('/store', '')}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => r.json())
            .then(resp => {
                window.location.reload();
            })
            .catch(async err => await showNotify('Failed to delete.', 'Deletion Failed', 'fa-times-circle'));
        };

        // Reset modal on close
        document.getElementById('logIncidentModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('logIncidentModalLabel').innerHTML = '<i class="fas fa-plus-circle me-2"></i> Log New Incident/Event';
            document.getElementById('incidentTab').style.display = 'flex';
            document.getElementById('incident_update_id').value = '';
            document.getElementById('compliance_update_id').value = '';
            document.getElementById('incident_attachment').required = false;
            document.getElementById('current_incident_attachment').style.display = 'none';
            document.getElementById('current_compliance_attachment').style.display = 'none';
            document.getElementById('incident_attachment_required_asterisk').style.display = 'inline';
            const attachHint = document.getElementById('incident_attachment_hint');
            attachHint.textContent = 'You can attach evidence now or later during update.';
            attachHint.classList.remove('text-danger');
            attachHint.classList.add('text-muted');
        });

        // Global Listeners
        document.addEventListener('DOMContentLoaded', function() {
            const incForm = document.getElementById('incidentForm');
            const compForm = document.getElementById('complianceForm');
            const incDateInput = document.getElementById('incident_date_input');
            const compDateInput = document.getElementById('compliance_date_input');

            if (incForm) {
                incForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitIncidentForm(this, false);
                });
            }

            if (compForm) {
                compForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitIncidentForm(this, true);
                });
            }

            if (incDateInput) {
                incDateInput.addEventListener('change', function() {
                    document.getElementById('incident_date').value = this.value;
                });
            }

            if (compDateInput) {
                compDateInput.addEventListener('change', function() {
                    document.getElementById('compliance_incident_date').value = this.value;
                });
            }
        });

        function logForSelectedDate() {
            logForSelectedDateWithTab(null, 'incident');
        }

        function logForSelectedDateWithTab(e, tab) {
            if (e) e.preventDefault();
            const date = window._selectedDateForLog;
            bootstrap.Modal.getInstance(document.getElementById('dayDetailsModal')).hide();
            setTimeout(() => {
                const logModal = new bootstrap.Modal(document.getElementById('logIncidentModal'));
                logModal.show();
                if (date) {
                    document.getElementById('incident_date_input').value = date;
                    document.getElementById('incident_date').value = date;
                    document.getElementById('compliance_date_input').value = date;
                    document.getElementById('compliance_incident_date').value = date;
                }
                if (tab === 'compliance') {
                    const compTab = document.getElementById('compliance-tab');
                    if (compTab) new bootstrap.Tab(compTab).show();
                } else {
                    const incTab = document.getElementById('incident-tab');
                    if (incTab) new bootstrap.Tab(incTab).show();
                }
            }, 300);
        }



        // --- Consolidated DOMContentLoaded for Chart & UI Logic ---
        document.addEventListener('DOMContentLoaded', function () {
            // Charts
            const typeCtx = document.getElementById('incidentTypeChart');
            const complianceCtx = document.getElementById('complianceDistributionChart');

            if (typeCtx && typeChartData.labels.length) {
                new Chart(typeCtx, {
                    type: 'pie', data: { labels: typeChartData.labels, datasets: [{ data: typeChartData.values, backgroundColor: ['#4facfe', '#00f2fe', '#667eea', '#a18cd1', '#38f9d7', '#ff0844', '#f093fb', '#84fab0', '#f2c94c'] }] },
                    options: { plugins: { legend: { position: 'bottom', labels: { font: { size: 9 }, boxWidth: 10, padding: 8 } } }, responsive: true, maintainAspectRatio: false }
                });
            }
            if (complianceCtx && complianceChartData.labels.length) {
                new Chart(complianceCtx, {
                    type: 'bar', data: { labels: complianceChartData.labels, datasets: [{ label: 'Events', data: complianceChartData.values, backgroundColor: '#1ed760', borderRadius: 6, barThickness: 15 }] },
                    options: { indexAxis: 'y', scales: { x: { beginAtZero: true, ticks: { font: { size: 8 } }, grid: { display: false } }, y: { ticks: { font: { size: 9, weight: '600' } }, grid: { display: false } } }, plugins: { legend: { display: false } }, responsive: true, maintainAspectRatio: false }
                });
            }

            // Sidebar Toggle
            const toggleBtn = document.getElementById('toggleChecklistSidebar');
            const closeBtn = document.getElementById('closeSidebar');
            const sidebar = document.getElementById('checklistSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (toggleBtn && sidebar && overlay) {
                const closeSidebar = () => { sidebar.classList.remove('open'); overlay.classList.remove('active'); };
                toggleBtn.addEventListener('click', () => { sidebar.classList.add('open'); overlay.classList.add('active'); });
                if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
                overlay.addEventListener('click', closeSidebar);
            }

            // School selection toggles
            document.querySelectorAll('.incident-school-source').forEach(radio => { radio.addEventListener('change', function() { const c = document.getElementById('incident_existing_school_container'); if (c) c.style.display = this.value === 'existing' ? 'block' : 'none'; }); });
            document.querySelectorAll('.compliance-school-source').forEach(radio => { radio.addEventListener('change', function() { const c = document.getElementById('compliance_existing_school_container'); if (c) c.style.display = this.value === 'existing' ? 'block' : 'none'; }); });

            // Notification print buttons
            document.querySelectorAll('.analytics-print-btn').forEach((btn) => {
                btn.addEventListener('click', function (event) {
                    const targetCanvasId = this.getAttribute('data-chart-target');
                    const canvas = targetCanvasId ? document.getElementById(targetCanvasId) : null;
                    if (!canvas) return;
                    event.preventDefault();
                    let chartImageData = '';
                    try { chartImageData = canvas.toDataURL('image/png'); } catch (e) { chartImageData = ''; }
                    const printUrl = new URL(this.href, window.location.origin);
                    if (chartImageData) {
                        const chartKey = `incident_chart_print_${Date.now()}_${Math.random().toString(36).slice(2)}`;
                        localStorage.setItem(chartKey, chartImageData);
                        printUrl.searchParams.set('chart_key', chartKey);
                    }
                    window.open(printUrl.toString(), '_blank', 'noopener');
                });
            });

            // Add Incident Type & Status listeners
            const addTypeBtn = document.getElementById('addIncidentTypeBtn');
            const addStatusBtn = document.getElementById('addIncidentStatusBtn');
            const typeModalEl = document.getElementById('addIncidentTypeModal');
            const statusModalEl = document.getElementById('addIncidentStatusModal');

            let isTypeEdit = false; let activeTypeId = null;
            let isStatusEdit = false; let activeStatusId = null;

            if (addTypeBtn && typeModalEl) {
                const typeModal = new bootstrap.Modal(typeModalEl);
                addTypeBtn.addEventListener('click', () => {
                    isTypeEdit = false; activeTypeId = null;
                    document.getElementById('addIncidentTypeModalLabel').innerHTML = '<i class="fas fa-plus-circle me-1 text-warning"></i> New Incident Type';
                    document.getElementById('saveIncidentTypeBtn').innerHTML = '<i class="fas fa-save me-1"></i> Save';
                    document.getElementById('newIncidentTypeName').value = '';
                    typeModal.show();
                });
                document.querySelectorAll('.edit-type-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        isTypeEdit = true; activeTypeId = this.getAttribute('data-id');
                        document.getElementById('addIncidentTypeModalLabel').innerHTML = '<i class="fas fa-edit me-1 text-warning"></i> Edit Incident Type';
                        document.getElementById('saveIncidentTypeBtn').innerHTML = '<i class="fas fa-save me-1"></i> Update';
                        document.getElementById('newIncidentTypeName').value = this.getAttribute('data-name');
                        typeModal.show();
                    });
                });
                document.getElementById('saveIncidentTypeBtn').addEventListener('click', async () => {
                    const name = document.getElementById('newIncidentTypeName').value.trim();
                    if (!name) return;
                    const url = isTypeEdit ? (incidentTypeUpdateBaseUrl + '/' + activeTypeId) : incidentTypeStoreUrl;
                    fetch(url, { method: isTypeEdit ? 'PUT' : 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: JSON.stringify({ name }) })
                    .then(r => r.json()).then(resp => { if (resp.success) location.reload(); else showNotify('Failed to save.'); }).catch(() => showNotify('Connection Error'));
                });
            }

            if (addStatusBtn && statusModalEl) {
                const statusModal = new bootstrap.Modal(statusModalEl);
                addStatusBtn.addEventListener('click', () => {
                    isStatusEdit = false; activeStatusId = null;
                    document.getElementById('addIncidentStatusModalLabel').innerHTML = '<i class="fas fa-plus-circle me-1 text-warning"></i> New Status / Event';
                    document.getElementById('saveIncidentStatusBtn').innerHTML = '<i class="fas fa-save me-1"></i> Save';
                    document.getElementById('newIncidentStatusName').value = '';
                    statusModal.show();
                });
                document.querySelectorAll('.edit-status-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        isStatusEdit = true; activeStatusId = this.getAttribute('data-id');
                        document.getElementById('addIncidentStatusModalLabel').innerHTML = '<i class="fas fa-edit me-1 text-warning"></i> Edit Status / Event';
                        document.getElementById('saveIncidentStatusBtn').innerHTML = '<i class="fas fa-save me-1"></i> Update';
                        document.getElementById('newIncidentStatusName').value = this.getAttribute('data-name');
                        statusModal.show();
                    });
                });
                document.getElementById('saveIncidentStatusBtn').addEventListener('click', async () => {
                    const name = document.getElementById('newIncidentStatusName').value.trim();
                    if (!name) return;
                    const url = isStatusEdit ? (incidentStatusUpdateBaseUrl + '/' + activeStatusId) : incidentStatusStoreUrl;
                    fetch(url, { method: isStatusEdit ? 'PUT' : 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: JSON.stringify({ name }) })
                    .then(r => r.json()).then(resp => { if (resp.success) location.reload(); else showNotify('Failed to save.'); }).catch(() => showNotify('Connection Error'));
                });
            }

            // Initial calls
            @if(auth()->user()->role === 'admin')
                if (typeof refreshPendingCount === 'function') refreshPendingCount();
            @endif

            if (focusDate) {
                const targetDay = document.querySelector(`.calendar-day[data-date="${focusDate}"]`);
                if (targetDay) {
                    targetDay.classList.add('date-highlight', 'date-highlight-focus');
                    targetDay.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center' });
                }
            }
        });

        // Quick Compliance Checklist JS
        async function updateChecklistItem(id, payload) {
            fetch(checklistUpdateBaseUrl + '/' + id, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            }).then(r => r.json()).then(async resp => {
                if (!resp.success) {
                    await showNotify('Failed to update checklist item.', 'Update Failed', 'fa-times');
                }
            }).catch(async () => await showNotify('Failed to update checklist item.', 'Connection Error', 'fa-wifi'));
        }

        document.querySelectorAll('#checklistContainer .checklist-toggle').forEach(cb => {
            cb.addEventListener('change', function () {
                const row = this.closest('.checklist-item-row');
                const id = row.getAttribute('data-id');
                updateChecklistItem(id, { is_completed: this.checked ? 1 : 0 });
            });
        });

        document.querySelectorAll('#checklistContainer .checklist-delete').forEach(btn => {
            btn.addEventListener('click', async function () {
                if (!(await showConfirm('Remove this checklist item?', 'Delete Task'))) return;
                const row = this.closest('.checklist-item-row');
                const id = row.getAttribute('data-id');

                fetch(checklistUpdateBaseUrl + '/' + id, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                }).then(r => r.json()).then(async resp => {
                    if (resp.success) {
                        row.remove();
                    } else {
                        await showNotify('Failed to delete checklist item.', 'Error', 'fa-exclamation-triangle');
                    }
                }).catch(async () => await showNotify('Failed to delete checklist item.', 'Connection Error', 'fa-wifi'));
            });
        });

        document.querySelectorAll('#checklistContainer .checklist-edit').forEach(btn => {
            btn.addEventListener('click', function () {
                const row = this.closest('.checklist-item-row');
                const id = row.getAttribute('data-id');
                const labelEl = row.querySelector('.checklist-label');
                const oldLabel = labelEl.textContent;
                const newLabel = prompt('Edit checklist task:', oldLabel);

                if (newLabel && newLabel.trim() !== '' && newLabel.trim() !== oldLabel) {
                    updateChecklistItem(id, { label: newLabel.trim() });
                    labelEl.textContent = newLabel.trim();
                }
            });
        });

        // Export / Import backup
        const exportBtn = document.getElementById('exportIncidentsBtn');
        const importInput = document.getElementById('importIncidentsInput');

        if (exportBtn) {
            exportBtn.addEventListener('click', function () {
                window.location.href = incidentsExportUrl;
            });
        }

        if (importInput) {
            importInput.addEventListener('change', async function() {
                if (this.files.length === 0) return;

                if (!(await showConfirm('Importing a backup will overwrite existing incident data. Continue?', 'Warning: Critical Action'))) {
                    this.value = '';
                    return;
                }

                const formData = new FormData();
                formData.append('file', this.files[0]);
                formData.append('_token', csrfToken);

                // Find the label or link that triggered this to show loading state if possible
                const triggerLabel = document.querySelector('label[for="importIncidentsInput"]') || document.createElement('div');
                const origHtml = triggerLabel.innerHTML;
                if (triggerLabel.innerHTML) {
                    triggerLabel.style.pointerEvents = 'none';
                    triggerLabel.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Importing...';
                }

                fetch(incidentsImportUrl, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                })
                .then(r => r.json())
                .then(async resp => {
                    if (resp.success) {
                        await showNotify('Backup imported successfully.', 'Success', 'fa-check-circle');
                        window.location.reload();
                    } else {
                        await showNotify(resp.message || 'Failed to import backup.', 'Import Failed', 'fa-exclamation-triangle');
                        if (triggerLabel.innerHTML) {
                            triggerLabel.style.pointerEvents = 'auto';
                            triggerLabel.innerHTML = origHtml;
                        }
                    }
                })
                .catch(async () => {
                    await showNotify('Failed to import backup.', 'Connection Error', 'fa-wifi');
                    if (triggerLabel.innerHTML) {
                        triggerLabel.style.pointerEvents = 'auto';
                        triggerLabel.innerHTML = origHtml;
                    }
                });
            });
        }

        document.getElementById('addChecklistItemBtn').addEventListener('click', async function () {
            const input = document.getElementById('newChecklistLabel');
            const label = input.value.trim();
            if (!label) return;
            fetch(checklistStoreUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    checklist_date: checklistDate,
                    label: label
                })
            }).then(r => r.json()).then(async resp => {
                if (!resp.success) {
                    await showNotify('Failed to add checklist item.', 'Error', 'fa-times');
                    return;
                }
                const item = resp.item;
                const container = document.getElementById('checklistContainer');
                const div = document.createElement('div');
                div.className = 'list-group-item bg-transparent border-0 px-0 py-2 d-flex align-items-center justify-content-between checklist-item-row';
                div.setAttribute('data-id', item.id);
                div.innerHTML = '' +
                    '<div class="form-check flex-grow-1">' +
                    '  <input class="form-check-input checklist-toggle" type="checkbox" id="checklist_' + item.id + '">' +
                    '  <label class="form-check-label fw-600 ms-1 small checklist-label" for="checklist_' + item.id + '">' + escapeHtml(item.label) + '</label>' +
                    '</div>' +
                    '<div class="d-flex align-items-center">' +
                    '  <button type="button" class="btn btn-sm text-muted ms-1 checklist-edit" title="Edit task" style="padding: 0 5px;">' +
                    '    <i class="fas fa-pen x-small" style="font-size: 0.7rem;"></i>' +
                    '  </button>' +
                    '  <button type="button" class="btn btn-sm text-danger ms-1 checklist-delete" title="Remove item" style="padding: 0 5px;">' +
                    '    <i class="fas fa-trash small"></i>' +
                    '  </button>' +
                    '</div>';
                container.appendChild(div);
                input.value = '';

                div.querySelector('.checklist-toggle').addEventListener('change', function () {
                    const row = this.closest('.checklist-item-row');
                    const id = row.getAttribute('data-id');
                    updateChecklistItem(id, { is_completed: this.checked ? 1 : 0 });
                });
                div.querySelector('.checklist-delete').addEventListener('click', async function () {
                    if (!(await showConfirm('Remove this checklist item?', 'Delete Task'))) return;
                    const row = this.closest('.checklist-item-row');
                    const id = row.getAttribute('data-id');

                    fetch(checklistUpdateBaseUrl + '/' + id, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    }).then(r => r.json()).then(async resp => {
                        if (resp.success) {
                            row.remove();
                        } else {
                            await showNotify('Failed to delete checklist item.', 'Error', 'fa-exclamation-triangle');
                        }
                    }).catch(async () => await showNotify('Failed to delete checklist item.', 'Connection Error', 'fa-wifi'));
                });
                div.querySelector('.checklist-edit').addEventListener('click', function () {
                    const row = this.closest('.checklist-item-row');
                    const id = row.getAttribute('data-id');
                    const labelEl = row.querySelector('.checklist-label');
                    const oldLabel = labelEl.textContent;
                    const newLabel = prompt('Edit checklist task:', oldLabel);

                    if (newLabel && newLabel.trim() !== '' && newLabel.trim() !== oldLabel) {
                        updateChecklistItem(id, { label: newLabel.trim() });
                        labelEl.textContent = newLabel.trim();
                    }
                });
            }).catch(async () => {
                await showNotify('Failed to add checklist item.', 'Error', 'fa-times');
            });
        });

        // Activity History Modal Navigation
        let currentHistoryDate = new Date();
        currentHistoryDate.setDate(1); // Set to first of month

        document.getElementById('activityHistoryModal').addEventListener('show.bs.modal', function () {
            currentHistoryDate = new Date();
            currentHistoryDate.setDate(1);
            fetchHistory(currentHistoryDate.getFullYear(), currentHistoryDate.getMonth() + 1);
        });

        function fetchHistory(year, month) {
            const container = document.getElementById('historyGridContainer');
            container.innerHTML = '<div class="col-12 py-5 text-center w-100"><span class="spinner-border text-warning border-4"></span><p class="mt-3 fs-5 text-muted">Loading history...</p></div>';

            fetch(`${checklistHistoryUrl}?year=${year}&month=${month}`)
                .then(r => r.json())
                .then(resp => {
                    if (resp.success) {
                        document.getElementById('currentHistoryMonthLabel').textContent = resp.month_name;

                        let html = '';
                        const history = resp.history;
                        const datesArray = Object.keys(history);

                        if (datesArray.length === 0) {
                            html = '<div class="col-12 py-5 text-center w-100">' +
                                   '  <i class="fas fa-clipboard-list fa-3x text-muted mb-3 opacity-25"></i>' +
                                   '  <p class="text-muted">No activities recorded for this period.</p>' +
                                   '</div>';
                        } else {
                            datesArray.forEach(date => {
                                const items = history[date];
                                const d = new Date(date);
                                const dateStr = d.toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
                                const dayName = d.toLocaleDateString('en-US', { weekday: 'long' });

                                html += `<div class="history-day-card shadow-sm">
                                            <div class="history-day-title d-flex justify-content-between">
                                                <span>${dateStr}</span>
                                                <span class="text-muted" style="font-size: 0.65rem;">${dayName}</span>
                                            </div>`;

                                items.forEach(item => {
                                    html += `<div class="history-item">
                                                <i class="fas ${item.is_completed ? 'fa-check-circle text-success' : 'fa-times-circle text-muted'}"></i>
                                                <span class="${item.is_completed ? 'text-dark fw-bold' : 'text-muted'} text-truncate" title="${item.label}">${item.label}</span>
                                            </div>`;
                                });

                                html += `</div>`;
                            });
                        }
                        container.innerHTML = html;
                    }
                })
                .catch(err => {
                    container.innerHTML = '<div class="text-danger text-center w-100 py-4"><i class="fas fa-exclamation-triangle mb-2"></i><br>Failed to load history items.</div>';
                });
        }

        const prevBtn = document.getElementById('prevHistoryMonth');
        const nextBtn = document.getElementById('nextHistoryMonth');

        if (prevBtn) {
            // Remove previous event listeners by cloning
            const newPrevBtn = prevBtn.cloneNode(true);
            prevBtn.parentNode.replaceChild(newPrevBtn, prevBtn);
            newPrevBtn.addEventListener('click', () => {
                currentHistoryDate.setMonth(currentHistoryDate.getMonth() - 1);
                fetchHistory(currentHistoryDate.getFullYear(), currentHistoryDate.getMonth() + 1);
            });
        }

        if (nextBtn) {
            const newNextBtn = nextBtn.cloneNode(true);
            nextBtn.parentNode.replaceChild(newNextBtn, nextBtn);
            newNextBtn.addEventListener('click', () => {
                currentHistoryDate.setMonth(currentHistoryDate.getMonth() + 1);
                fetchHistory(currentHistoryDate.getFullYear(), currentHistoryDate.getMonth() + 1);
            });
        }

        // Print analytics chart image (actual rendered chart canvas)
        document.querySelectorAll('.analytics-print-btn').forEach((btn) => {
            btn.addEventListener('click', function (event) {
                const targetCanvasId = this.getAttribute('data-chart-target');
                const canvas = targetCanvasId ? document.getElementById(targetCanvasId) : null;

                if (!canvas) {
                    return;
                }

                event.preventDefault();

                let chartImageData = '';
                try {
                    chartImageData = canvas.toDataURL('image/png');
                } catch (error) {
                    chartImageData = '';
                }

                const printUrl = new URL(this.href, window.location.origin);
                if (chartImageData) {
                    const chartKey = `incident_chart_print_${Date.now()}_${Math.random().toString(36).slice(2)}`;
                    localStorage.setItem(chartKey, chartImageData);
                    printUrl.searchParams.set('chart_key', chartKey);
                }

                window.open(printUrl.toString(), '_blank', 'noopener');
            });
        });

        function formatFileSize(bytes) {
            if (!bytes) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        }

        // --- Admin Report Approval Logic ---
        @if(auth()->user()->role === 'admin')
        window.refreshPendingCount = async function() {
            const pNotifCount = document.getElementById('pendingNotifCount');
            try {
                const r = await fetch('{{ route("incidents.pending") }}');
                const resp = await r.json();
                if (resp.success) {
                    if (resp.count > 0) {
                        if (pNotifCount) {
                            pNotifCount.textContent = resp.count;
                            pNotifCount.classList.remove('d-none');
                        }
                    } else {
                        if (pNotifCount) pNotifCount.classList.add('d-none');
                    }
                }
            } catch (e) {}
        };

        window.loadPendingReports = async function() {
            const pReportsTable = document.getElementById('pendingReportsTable');
            const pEmptyState = document.getElementById('pendingEmptyState');
            if (pReportsTable) pReportsTable.innerHTML = '<tr><td colspan="6" class="text-center py-4"><span class="spinner-border spinner-border-sm me-2"></span>Loading...</td></tr>';
            if (pEmptyState) pEmptyState.classList.add('d-none');

            try {
                const r = await fetch('{{ route("incidents.pending") }}');
                const resp = await r.json();
                if (resp.success && resp.reports.length > 0) {
                    let html = '';
                    resp.reports.forEach(report => {
                        const date = new Date(report.incident_date).toLocaleDateString('en-PH', { month: 'short', day: '2-digit', year: 'numeric' });
                        const typeBadge = report.entry_type === 'incident' ?
                            `<span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i> ${escapeHtml(report.incident_type?.name || 'Incident')}</span>` :
                            `<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> ${escapeHtml(report.incident_status?.name || 'Compliance')}</span>`;

                        html += `
                            <tr>
                                <td class="ps-4 fw-bold text-muted small">${date}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center p-2 me-2" style="width:30px;height:30px;">
                                            <i class="fas fa-user-circle text-secondary"></i>
                                        </div>
                                        <span class="small fw-600">${escapeHtml(report.contributor?.name || 'Unknown')}</span>
                                    </div>
                                </td>
                                <td><span class="small">${escapeHtml(report.school_name)}</span></td>
                                <td>${typeBadge}</td>
                                <td><p class="mb-0 small text-truncate" style="max-width: 250px;" title="${escapeHtml(report.remarks)}">${escapeHtml(report.remarks)}</p></td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-success" onclick="approveReport(${report.id})" title="Accept">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="initiateRejection(${report.id})" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    if (pReportsTable) pReportsTable.innerHTML = html;
                    if (pEmptyState) pEmptyState.classList.add('d-none');
                } else {
                    if (pReportsTable) pReportsTable.innerHTML = '';
                    if (pEmptyState) pEmptyState.classList.remove('d-none');
                }
            } catch (e) {
                if (pReportsTable) pReportsTable.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-danger">Failed to load reports.</td></tr>';
            }
        };

        window.approveReport = async (id) => {
            if (!(await showConfirm('Accept this report and log it to the calendar?', 'Approve Report'))) return;

            try {
                const r = await fetch(`{{ url("incidents") }}/${id}/accept`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                });
                const resp = await r.json();
                if (resp.success) {
                    await showNotify(resp.message, 'Success', 'fa-check-circle');
                    await loadPendingReports();
                    await refreshPendingCount();
                    if (typeof window.location.reload === 'function') setTimeout(() => window.location.reload(), 1000);
                } else {
                    await showNotify(resp.message || 'Action failed', 'Error', 'fa-times-circle');
                }
            } catch (e) {
                await showNotify('Connection failed', 'Error', 'fa-wifi');
            }
        };

        let activeRejectionId = null;
        let rejectModal = null;

        window.initiateRejection = (id) => {
            activeRejectionId = id;
            document.getElementById('rejectionReasonText').value = '';
            const modalEl = document.getElementById('rejectionReasonModal');
            if (modalEl) {
                if (!rejectModal) rejectModal = new bootstrap.Modal(modalEl);
                rejectModal.show();
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            const adminNotifyBtn = document.getElementById('adminNotifBtn');
            const pReportsModalEl = document.getElementById('pendingReportsModal');
            let pReportsModal = null;
            if (pReportsModalEl) pReportsModal = new bootstrap.Modal(pReportsModalEl);

            if (adminNotifyBtn && pReportsModal) {
                adminNotifyBtn.addEventListener('click', async () => {
                    await loadPendingReports();
                    pReportsModal.show();
                });
            }

            const confirmBtn = document.getElementById('confirmRejectBtn');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', async () => {
                    const reason = document.getElementById('rejectionReasonText').value.trim();
                    if (!reason) {
                        await showNotify('Please provide a reason for rejection.', 'Reason Required', 'fa-info-circle');
                        return;
                    }
                    try {
                        const r = await fetch(`{{ url("incidents") }}/${activeRejectionId}/reject`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ reason })
                        });
                        const resp = await r.json();
                        if (resp.success) {
                            if (rejectModal) rejectModal.hide();
                            await showNotify(resp.message, 'Rejected', 'fa-times-circle');
                            await loadPendingReports();
                            await refreshPendingCount();
                        } else {
                            await showNotify(resp.message || 'Action failed', 'Error', 'fa-times-circle');
                        }
                    } catch (e) {
                        await showNotify('Connection failed', 'Error', 'fa-wifi');
                    }
                });
            }

            setInterval(refreshPendingCount, 60000); // Every minute
        });
        @endif
    </script>
</body>
</html>
