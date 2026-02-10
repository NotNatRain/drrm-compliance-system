<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incidents Compliance Dashboard - DRRM</title>
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
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
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
            transition: all 0.2s;
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
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
            border-radius: 12px;
        }

        .custom-table td {
            padding: 15px;
            vertical-align: middle;
        }

        .custom-table td:first-child { border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
        .custom-table td:last-child { border-top-right-radius: 12px; border-bottom-right-radius: 12px; }

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
            transition: all 0.3s;
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
    </style>
</head>
<body>

    <div class="header-section">
        <div class="header-content container-fluid">
            <div class="header-title">
                <a href="{{ route('dashboard') }}" class="back-btn mb-3 d-inline-block">
                    <i class="fas fa-arrow-left me-2"></i> Back to Main
                </a>
                <h1>Incidents Dashboard</h1>
                <p>Real-time incident tracking and compliance monitoring system</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-light btn-lg rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#logIncidentModal" style="color: var(--incident-orange); font-weight: 600;">
                    <i class="fas fa-plus-circle me-2"></i> Log New Incident
                </button>
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
        <div class="row">
            <!-- Left Panel: Calendar -->
            <div class="col-lg-7 mb-4">
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
                                    $dayHoverItems->push(['type' => 'Event', 'name' => $ce->incidentStatus->name ?? 'Event', 'school' => $ce->school_name ?? '']);
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
                                            <span class="badge {{ $ce->incidentStatus->color_class ?? 'status-no-suspension' }}" title="{{ $ce->incidentStatus->name ?? 'Event' }}">E</span>
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
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                        <div class="d-flex align-items-center gap-2">
                            <button id="exportIncidentsBtn" type="button" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-file-export me-1"></i> Export Backup
                            </button>
                            <label class="btn btn-sm btn-outline-secondary mb-0">
                                <i class="fas fa-file-import me-1"></i> Import Backup
                                <input type="file" id="importIncidentsInput" accept=".json" hidden>
                            </label>
                            <small class="text-muted ms-2" style="font-size: 0.8rem;">Backup & restore data</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Incident Specifics -->
            <div class="col-lg-5 mb-4">
                <div class="glass-card p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-info-circle me-2 text-warning"></i>
                        Monthly Incident Specifics
                    </h5>
                    
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="custom-table">
                            <tbody>
                                @forelse($incidents ?? [] as $incident)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $incident->incident_date->format('M j') }}</div>
                                        <small class="text-muted">{{ $incident->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <div class="school-name">{{ $incident->school_name }}</div>
                                        @if($incident->entry_type === 'incident' && $incident->incidentType)
                                            <span class="badge {{ $incident->incidentType->color_class }} small">{{ $incident->incidentType->name }}</span>
                                        @elseif($incident->entry_type === 'compliance' && $incident->incidentStatus)
                                            <span class="badge {{ $incident->incidentStatus->color_class }} small">{{ $incident->incidentStatus->name }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-truncate d-block" style="max-width: 150px;">{{ Str::limit($incident->remarks, 50) }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <div class="mb-3">
                                            <i class="fas fa-folder-open fa-3x text-muted"></i>
                                        </div>
                                        <p class="text-muted">No incidents recorded for this month</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
                                <div class="stat-label">Total Logs</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-value text-danger">{{ $stats['incidents'] ?? 0 }}</div>
                                <div class="stat-label">Incident</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-value text-success">{{ $stats['compliance'] ?? 0 }}</div>
                                <div class="stat-label">Events</div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="row">
                                <!-- Left Chart: Incident Type Distribution -->
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted text-uppercase small mb-2">
                                        <i class="fas fa-chart-pie me-2 text-warning"></i> Incident Type Distribution
                                    </h6>
                                    <canvas id="incidentTypeChart" height="100"></canvas>
                                </div>
                                
                                <!-- Right Chart: Incident Trend (Daily) -->
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted text-uppercase small mb-2">
                                        <i class="fas fa-chart-line me-2 text-warning"></i> Incident Trend (Daily)
                                    </h6>
                                    <canvas id="incidentTrendChart" height="100"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Legend and Stats Section -->
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="glass-card p-4">
                    <h5 class="fw-bold mb-4">Legend & Filters</h5>
                    <div class="row">
                        <div class="col-md-6 border-end">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-muted small text-uppercase mb-0">Incident Types</h6>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="addIncidentTypeBtn" title="Add incident type">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($incidentTypes as $type)
                                    <div class="legend-tag {{ $type->color_class ?? 'type-others' }}">
                                        <div class="tag-dot" style="background: {{ in_array($type->color_class, ['type-flooding','type-others']) ? '#333' : '#fff' }};"></div>
                                        <span>{{ $type->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6 ps-md-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-muted small text-uppercase mb-0">Compliance Status / Events</h6>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="addIncidentStatusBtn" title="Add status/event">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($incidentStatuses as $status)
                                    <div class="legend-tag {{ $status->color_class ?? 'status-no-suspension' }}">
                                        <span>{{ $status->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="glass-card p-4 overflow-hidden position-relative">
                    <div style="position: absolute; top: -20px; right: -20px; font-size: 8rem; color: rgba(242, 201, 76, 0.05);">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Quick Compliance Checklist</h5>
                    <p class="text-muted small mb-3">For {{ \Carbon\Carbon::parse($checklistDate)->format('F j, Y') }} (resets daily)</p>
                    <div id="checklistContainer" class="list-group list-group-flush bg-transparent">
                        @foreach($checklistItems as $item)
                        <div class="list-group-item bg-transparent border-0 px-0 py-2 d-flex align-items-center justify-content-between checklist-item-row" data-id="{{ $item->id }}">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input checklist-toggle" type="checkbox" id="checklist_{{ $item->id }}" {{ $item->is_completed ? 'checked' : '' }}>
                                <label class="form-check-label fw-600 ms-1" for="checklist_{{ $item->id }}">{{ $item->label }}</label>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger ms-2 checklist-delete" title="Remove item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-3">
                        <div class="input-group input-group-sm">
                            <input type="text" id="newChecklistLabel" class="form-control" placeholder="Add checklist item...">
                            <button class="btn btn-warning" type="button" id="addChecklistItemBtn">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Log Incident Modal -->
    <div class="modal fade" id="logIncidentModal" tabindex="-1" aria-labelledby="logIncidentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content incident-modal">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="logIncidentModalLabel">
                        <i class="fas fa-plus-circle me-2"></i> Log New Incident/Event
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="incidentTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="incident-tab" data-bs-toggle="tab" data-bs-target="#incident-form" type="button" role="tab">
                                <i class="fas fa-exclamation-triangle me-2"></i> Incident
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="compliance-tab" data-bs-toggle="tab" data-bs-target="#compliance-form" type="button" role="tab">
                                <i class="fas fa-calendar-check me-2"></i> Compliance Status/Event
                            </button>
                        </li>
                    </ul>
                    
                    <div class="tab-content mt-4">
                        <!-- Incident Form -->
                        <div class="tab-pane fade show active" id="incident-form" role="tabpanel">
                            <form id="incidentForm">
                                @csrf
                                <input type="hidden" name="entry_type" value="incident">
                                <input type="hidden" name="incident_date" id="incident_date" value="{{ date('Y-m-d') }}">
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="incident_type_id" class="form-label">Incident Type *</label>
                                        <select class="form-select" id="incident_type_id" name="incident_type_id" required>
                                            <option value="">Select Incident Type</option>
                                            @foreach($incidentTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="incident_date_input" class="form-label">Date *</label>
                                        <input type="date" class="form-control" id="incident_date_input" name="incident_date_input" required 
                                               value="{{ date('Y-m-d') }}">
                                    </div>
                                    
                                    <div class="col-md-12 mb-3">
                                        <label for="school_name" class="form-label">School Name *</label>
                                        <input type="text" class="form-control" id="school_name" name="school_name" 
                                               placeholder="Start typing school name..." required>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="affected_personnel" class="form-label">Affected Personnel <small class="text-muted">(optional, leave empty or 0)</small></label>
                                        <input type="number" class="form-control" id="affected_personnel" name="affected_personnel" min="0" placeholder="0" value="">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="affected_students" class="form-label">Affected Students <small class="text-muted">(optional, leave empty or 0)</small></label>
                                        <input type="number" class="form-control" id="affected_students" name="affected_students" min="0" placeholder="0" value="">
                                    </div>
                                    
                                    <div class="col-12 mb-3">
                                        <label for="remarks" class="form-label">Remarks/Description *</label>
                                        <textarea class="form-control" id="remarks" name="remarks" rows="3" 
                                                  placeholder="Provide detailed description of the incident..." required></textarea>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save me-2"></i> Save Incident
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Compliance Form -->
                        <div class="tab-pane fade" id="compliance-form" role="tabpanel">
                            <form id="complianceForm">
                                @csrf
                                <input type="hidden" name="entry_type" value="compliance">
                                <input type="hidden" name="incident_date" id="compliance_incident_date" value="{{ date('Y-m-d') }}">
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="incident_status_id" class="form-label">Compliance Status/Event *</label>
                                        <select class="form-select" id="incident_status_id" name="incident_status_id" required>
                                            <option value="">Select Status/Event</option>
                                            @foreach($incidentStatuses as $status)
                                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="compliance_date_input" class="form-label">Date *</label>
                                        <input type="date" class="form-control" id="compliance_date_input" name="incident_date_input" required 
                                               value="{{ date('Y-m-d') }}">
                                    </div>
                                    
                                    <div class="col-md-12 mb-3">
                                        <label for="compliance_school_name" class="form-label">School Name *</label>
                                        <input type="text" class="form-control" id="compliance_school_name" name="school_name" 
                                               placeholder="Start typing school name..." required>
                                    </div>
                                    
                                    <div class="col-12 mb-3">
                                        <label for="compliance_remarks" class="form-label">Remarks/Description *</label>
                                        <textarea class="form-control" id="compliance_remarks" name="remarks" rows="3" 
                                                  placeholder="Provide details about the compliance status/event..." required></textarea>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save me-2"></i> Save Compliance Event
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const incidentsDateUrl = '{{ url("/incidents/date") }}';
        const incidentsStoreUrl = '{{ route("incidents.store") }}';
        const incidentTypeStoreUrl = '{{ route("incidents.types.store") }}';
        const incidentStatusStoreUrl = '{{ route("incidents.statuses.store") }}';
        const incidentsExportUrl = '{{ url("/incidents/export") }}';
        const incidentsImportUrl = '{{ url("/incidents/import") }}';
        const checklistIndexUrl = '{{ route("incidents.checklist.index") }}';
        const checklistStoreUrl = '{{ route("incidents.checklist.store") }}';
        const checklistUpdateBaseUrl = '{{ url("/incidents/checklist") }}';
        const checklistDate = '{{ $checklistDate }}';

        const typeChartData = {
            labels: @json($stats['type_distribution']['labels'] ?? []),
            values: @json($stats['type_distribution']['values'] ?? []),
        };
        const trendChartData = {
            labels: @json($stats['trend']['labels'] ?? []),
            values: @json($stats['trend']['values'] ?? []),
        };

        // Day hover: custom info box (Incident/Event Type + School name)
        let tooltipEl = null;
        document.querySelectorAll('.calendar-day').forEach(dayEl => {
            dayEl.addEventListener('mouseenter', function(e) {
                const raw = this.getAttribute('data-day-hover');
                if (!raw || raw === '[]') return;
                let items;
                try {
                    items = JSON.parse(raw);
                    if (!items || items.length === 0) return;
                } catch (err) { return; }
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
        function escapeHtml(s) {
            const d = document.createElement('div');
            d.textContent = s;
            return d.innerHTML;
        }

        // Calendar day click: fetch and show day details
        document.querySelectorAll('.calendar-day').forEach(dayEl => {
            dayEl.addEventListener('click', function() {
                if (this.classList.contains('other-month')) return;
                const date = this.getAttribute('data-date');
                if (!date) return;
                const d = new Date(date + 'T12:00:00');
                document.getElementById('modalDayDate').textContent = d.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                document.getElementById('dayIncidentsList').innerHTML = '<div class="text-center text-muted py-3"><small>Loading...</small></div>';
                document.getElementById('dayComplianceList').innerHTML = '<div class="text-center text-muted py-3"><small>Loading...</small></div>';
                const modal = new bootstrap.Modal(document.getElementById('dayDetailsModal'));
                modal.show();
                window._selectedDateForLog = date;

                fetch(incidentsDateUrl + '/' + date, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                    .then(r => r.json())
                    .then(data => {
                        const incList = document.getElementById('dayIncidentsList');
                        const compList = document.getElementById('dayComplianceList');
                        const toArray = (x) => Array.isArray(x) ? x : (x && typeof x === 'object' ? Object.values(x) : []);
                        const incidents = toArray(data.incidents);
                        const compliance = toArray(data.compliance);
                        if (incidents.length) {
                            incList.innerHTML = incidents.map(i => {
                                const typeName = (i.incident_type && i.incident_type.name) ? i.incident_type.name : 'Incident';
                                const school = i.school_name || '';
                                const remarks = (i.remarks || '').substring(0, 200);
                                return '<div class="incident-list-item border-left mb-2 p-2" style="border-color:#667eea"><strong>' + escapeHtml(typeName) + '</strong><br><small>' + escapeHtml(school) + '</small><br><span class="text-muted small">' + escapeHtml(remarks) + '</span></div>';
                            }).join('');
                        } else {
                            incList.innerHTML = '<div class="text-center text-muted py-5"><i class="fas fa-folder-open fa-3x mb-3"></i><p>No incidents for this day</p></div>';
                        }
                        if (compliance.length) {
                            compList.innerHTML = compliance.map(c => {
                                const statusName = (c.incident_status && c.incident_status.name) ? c.incident_status.name : 'Event';
                                const school = c.school_name || '';
                                const remarks = (c.remarks || '').substring(0, 200);
                                return '<div class="incident-list-item border-left mb-2 p-2" style="border-color:#1ed760"><strong>' + escapeHtml(statusName) + '</strong><br><small>' + escapeHtml(school) + '</small><br><span class="text-muted small">' + escapeHtml(remarks) + '</span></div>';
                            }).join('');
                        } else {
                            compList.innerHTML = '<div class="text-center text-muted py-5"><i class="fas fa-calendar fa-3x mb-3"></i><p>No compliance events for this day</p></div>';
                        }
                    })
                    .catch(() => {
                        document.getElementById('dayIncidentsList').innerHTML = '<div class="text-center text-muted py-5"><p>Failed to load</p></div>';
                        document.getElementById('dayComplianceList').innerHTML = '<div class="text-center text-muted py-5"><p>Failed to load</p></div>';
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

        function submitIncidentForm(form, isCompliance) {
            const data = buildFormData(form);
            if (!data.incident_date) data.incident_date = (form.querySelector('input[type="date"]') || {}).value || new Date().toISOString().slice(0, 10);
            data._token = csrfToken;
            const btn = form.querySelector('button[type="submit"]');
            const origText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
            fetch(incidentsStoreUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify(data)
            })
            .then(r => r.json())
            .then(resp => {
                if (resp.success) {
                    bootstrap.Modal.getInstance(document.getElementById('logIncidentModal')).hide();
                    window.location.reload();
                } else {
                    alert(resp.message || 'Failed to save');
                    btn.disabled = false;
                    btn.innerHTML = origText;
                }
            })
            .catch(err => {
                alert('Failed to save. Check your connection.');
                btn.disabled = false;
                btn.innerHTML = origText;
            });
        }

        document.getElementById('incidentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            submitIncidentForm(this, false);
        });

        document.getElementById('complianceForm').addEventListener('submit', function(e) {
            e.preventDefault();
            submitIncidentForm(this, true);
        });

        document.getElementById('incident_date_input').addEventListener('change', function() {
            document.getElementById('incident_date').value = this.value;
        });

        document.getElementById('compliance_date_input').addEventListener('change', function() {
            document.getElementById('compliance_incident_date').value = this.value;
        });

        function logForSelectedDate() {
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
            }, 300);
        }

        // School autocomplete
        const schoolInput = document.getElementById('school_name');
        const complianceSchoolInput = document.getElementById('compliance_school_name');
        const searchSchoolsUrl = '{{ route("incidents.search-schools") }}';
        function showSchoolSuggestions(input, listId) {
            const q = input.value.trim();
            if (q.length < 2) { document.getElementById(listId).innerHTML = ''; return; }
            fetch(searchSchoolsUrl + '?q=' + encodeURIComponent(q))
                .then(r => r.json())
                .then(schools => {
                    const list = document.getElementById(listId);
                    if (!schools.length) { list.innerHTML = ''; return; }
                    list.innerHTML = schools.map(s => '<div class="autocomplete-item p-2 border-bottom" style="cursor:pointer">' + (s.name || s) + '</div>').join('');
                    list.style.display = 'block';
                    list.querySelectorAll('.autocomplete-item').forEach(el => {
                        el.addEventListener('click', () => { input.value = el.textContent.trim(); list.innerHTML = ''; list.style.display = 'none'; });
                    });
                });
        }
        if (schoolInput) {
            let acDiv = document.getElementById('school_autocomplete');
            if (!acDiv) {
                acDiv = document.createElement('div');
                acDiv.id = 'school_autocomplete';
                acDiv.className = 'autocomplete-items position-absolute';
                acDiv.style.display = 'none';
                schoolInput.parentNode.style.position = 'relative';
                schoolInput.parentNode.appendChild(acDiv);
            }
            schoolInput.addEventListener('input', () => showSchoolSuggestions(schoolInput, 'school_autocomplete'));
        }
        if (complianceSchoolInput) {
            let acDiv2 = document.getElementById('compliance_school_autocomplete');
            if (!acDiv2) {
                acDiv2 = document.createElement('div');
                acDiv2.id = 'compliance_school_autocomplete';
                acDiv2.className = 'autocomplete-items position-absolute';
                acDiv2.style.display = 'none';
                complianceSchoolInput.parentNode.style.position = 'relative';
                complianceSchoolInput.parentNode.appendChild(acDiv2);
            }
            complianceSchoolInput.addEventListener('input', () => showSchoolSuggestions(complianceSchoolInput, 'compliance_school_autocomplete'));
        }

        // Charts
        document.addEventListener('DOMContentLoaded', function () {
        const typeCtx = document.getElementById('incidentTypeChart');
        if (typeCtx && typeChartData.labels.length) {
            new Chart(typeCtx, {
                type: 'pie',
                data: {
                    labels: typeChartData.labels,
                    datasets: [{
                        data: typeChartData.values,
                        backgroundColor: [
                            '#4facfe', '#00f2fe', '#667eea', '#a18cd1', '#38f9d7',
                            '#ff0844', '#f093fb', '#84fab0', '#f2c94c'
                        ],
                    }]
                },
                options: {
                    plugins: {
                        legend: { 
                            position: 'bottom',
                            labels: {
                                font: { size: 10 }, // Smaller font for compact size
                                boxWidth: 12, // Smaller legend boxes
                                padding: 8 // Less padding
                            }
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: true
                }
            });
        }

        const trendCtx = document.getElementById('incidentTrendChart');
        if (trendCtx && trendChartData.labels.length) {
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: trendChartData.labels,
                    datasets: [{
                        label: 'Incidents',
                        data: trendChartData.values,
                        borderColor: '#F2994A',
                        backgroundColor: 'rgba(242,153,74,0.15)',
                        tension: 0.25,
                        fill: true,
                        pointRadius: 3, // Smaller points
                        pointHoverRadius: 5,
                        borderWidth: 2
                    }]
                },
                options: {
                    scales: {
                        x: { 
                            ticks: { 
                                autoSkip: true, 
                                maxTicksLimit: 8,
                                font: { size: 9 }
                            }
                        },
                        y: { 
                            beginAtZero: true, 
                            precision: 0,
                            ticks: { font: { size: 9 } }
                        }
                    },
                    plugins: {
                        legend: { 
                            display: false 
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: true
                }
            });
        }
        });

        // Dynamic incident type/status add buttons with small modals
        const addIncidentTypeBtn = document.getElementById('addIncidentTypeBtn');
        const addIncidentStatusBtn = document.getElementById('addIncidentStatusBtn');
        const addIncidentTypeModalEl = document.getElementById('addIncidentTypeModal');
        const addIncidentStatusModalEl = document.getElementById('addIncidentStatusModal');

        if (addIncidentTypeBtn && addIncidentTypeModalEl) {
            const addIncidentTypeModal = new bootstrap.Modal(addIncidentTypeModalEl);
            addIncidentTypeBtn.addEventListener('click', function () {
                document.getElementById('newIncidentTypeName').value = '';
                addIncidentTypeModal.show();
            });
            document.getElementById('saveIncidentTypeBtn').addEventListener('click', function () {
                const nameInput = document.getElementById('newIncidentTypeName');
                const name = nameInput.value.trim();
                if (!name) {
                    nameInput.focus();
                    return;
                }
                fetch(incidentTypeStoreUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ name })
                }).then(r => r.json()).then(resp => {
                    if (!resp.success) {
                        alert('Failed to add incident type.');
                        return;
                    }
                    addIncidentTypeModal.hide();
                    alert('Incident type added. The page will reload to show it.');
                    window.location.reload();
                }).catch(() => alert('Failed to add incident type.'));
            });
        }

        if (addIncidentStatusBtn && addIncidentStatusModalEl) {
            const addIncidentStatusModal = new bootstrap.Modal(addIncidentStatusModalEl);
            addIncidentStatusBtn.addEventListener('click', function () {
                document.getElementById('newIncidentStatusName').value = '';
                addIncidentStatusModal.show();
            });
            document.getElementById('saveIncidentStatusBtn').addEventListener('click', function () {
                const nameInput = document.getElementById('newIncidentStatusName');
                const name = nameInput.value.trim();
                if (!name) {
                    nameInput.focus();
                    return;
                }
                fetch(incidentStatusStoreUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ name })
                }).then(r => r.json()).then(resp => {
                    if (!resp.success) {
                        alert('Failed to add status/event.');
                        return;
                    }
                    addIncidentStatusModal.hide();
                    alert('Status/event added. The page will reload to show it.');
                    window.location.reload();
                }).catch(() => alert('Failed to add status/event.'));
            });
        }

        // Quick Compliance Checklist JS
        function updateChecklistItem(id, payload) {
            fetch(checklistUpdateBaseUrl + '/' + id, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            }).then(r => r.json()).then(resp => {
                if (!resp.success) {
                    alert('Failed to update checklist item.');
                }
            }).catch(() => {
                alert('Failed to update checklist item.');
            });
        }

        document.querySelectorAll('#checklistContainer .checklist-toggle').forEach(cb => {
            cb.addEventListener('change', function () {
                const row = this.closest('.checklist-item-row');
                const id = row.getAttribute('data-id');
                updateChecklistItem(id, { is_completed: this.checked ? 1 : 0 });
            });
        });

        document.querySelectorAll('#checklistContainer .checklist-delete').forEach(btn => {
            btn.addEventListener('click', function () {
                const row = this.closest('.checklist-item-row');
                const id = row.getAttribute('data-id');
                if (!confirm('Remove this checklist item?')) return;
                fetch(checklistUpdateBaseUrl + '/' + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(r => r.json()).then(resp => {
                    if (resp.success) {
                        row.remove();
                    } else {
                        alert('Failed to delete checklist item.');
                    }
                }).catch(() => {
                    alert('Failed to delete checklist item.');
                });
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
            importInput.addEventListener('change', function () {
                if (!this.files.length) return;
                if (!confirm('Importing a backup will overwrite existing incident data. Continue?')) {
                    this.value = '';
                    return;
                }
                const file = this.files[0];
                const fd = new FormData();
                fd.append('file', file);
                fd.append('_token', csrfToken);
                fetch(incidentsImportUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: fd
                }).then(r => r.json()).then(resp => {
                    if (!resp.success) {
                        alert(resp.message || 'Failed to import backup.');
                        return;
                    }
                    alert('Backup imported successfully.');
                    window.location.reload();
                }).catch(() => alert('Failed to import backup.'));
            });
        }

        document.getElementById('addChecklistItemBtn').addEventListener('click', function () {
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
            }).then(r => r.json()).then(resp => {
                if (!resp.success) {
                    alert('Failed to add checklist item.');
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
                    '  <label class="form-check-label fw-600 ms-1" for="checklist_' + item.id + '">' + escapeHtml(item.label) + '</label>' +
                    '</div>' +
                    '<button type="button" class="btn btn-sm btn-outline-danger ms-2 checklist-delete" title="Remove item">' +
                    '  <i class="fas fa-trash"></i>' +
                    '</button>';
                container.appendChild(div);
                input.value = '';

                div.querySelector('.checklist-toggle').addEventListener('change', function () {
                    const row = this.closest('.checklist-item-row');
                    const id = row.getAttribute('data-id');
                    updateChecklistItem(id, { is_completed: this.checked ? 1 : 0 });
                });
                div.querySelector('.checklist-delete').addEventListener('click', function () {
                    const row = this.closest('.checklist-item-row');
                    const id = row.getAttribute('data-id');
                    if (!confirm('Remove this checklist item?')) return;
                    fetch(checklistUpdateBaseUrl + '/' + id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }).then(r => r.json()).then(resp => {
                        if (resp.success) {
                            row.remove();
                        } else {
                            alert('Failed to delete checklist item.');
                        }
                    }).catch(() => {
                        alert('Failed to delete checklist item.');
                    });
                });
            }).catch(() => {
                alert('Failed to add checklist item.');
            });
        });
    </script>
</body>
</html>