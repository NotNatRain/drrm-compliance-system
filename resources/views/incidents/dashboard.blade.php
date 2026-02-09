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
            padding: 20px;
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
                            <h6 class="text-muted small mb-3 text-uppercase">Incident Types</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <div class="legend-tag type-cyclone"><div class="tag-dot" style="background: #fff;"></div> Tropical Cyclone</div>
                                <div class="legend-tag type-rainfall"><div class="tag-dot" style="background: #fff;"></div> Heavy Rainfall</div>
                                <div class="legend-tag type-earthquake"><div class="tag-dot" style="background: #fff;"></div> Earthquake</div>
                                <div class="legend-tag type-landslide"><div class="tag-dot" style="background: #fff;"></div> Landslide</div>
                                <div class="legend-tag type-flooding"><div class="tag-dot" style="background: #333;"></div> Flooding</div>
                                <div class="legend-tag type-fire"><div class="tag-dot" style="background: #fff;"></div> Fire</div>
                                <div class="legend-tag type-accident"><div class="tag-dot" style="background: #fff;"></div> Accidents</div>
                                <div class="legend-tag type-violence"><div class="tag-dot" style="background: #fff;"></div> Violence/Violence</div>
                                <div class="legend-tag type-others"><div class="tag-dot" style="background: #333;"></div> Others</div>
                            </div>
                        </div>
                        <div class="col-md-6 ps-md-4">
                            <h6 class="text-muted small mb-3 text-uppercase">Compliance Status / Events</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <div class="legend-tag status-holiday">Holiday</div>
                                <div class="legend-tag status-incident">Incident In School</div>
                                <div class="legend-tag status-suspended">Classes/Work Suspended</div>
                                <div class="legend-tag status-no-suspension">No Class Suspension</div>
                                <div class="legend-tag status-f2f-suspended">Suspended F2F Classes</div>
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
                    <h5 class="fw-bold mb-4">Quick Compliance Checklist</h5>
                    <div class="list-group list-group-flush bg-transparent">
                        <div class="list-group-item bg-transparent border-0 px-0 py-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="c1">
                                <label class="form-check-label fw-600" for="c1">Daily Monitoring Report Submitted</label>
                            </div>
                        </div>
                        <div class="list-group-item bg-transparent border-0 px-0 py-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="c2">
                                <label class="form-check-label fw-600" for="c2">Incident Verification Completed</label>
                            </div>
                        </div>
                        <div class="list-group-item bg-transparent border-0 px-0 py-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="c3">
                                <label class="form-check-label fw-600" for="c3">Victim Assistance Log Updated</label>
                            </div>
                        </div>
                        <div class="list-group-item bg-transparent border-0 px-0 py-3">
                            <div class="form-check text-muted">
                                <input class="form-check-input" type="checkbox" id="c4">
                                <label class="form-check-label" for="c4">School Head Confirmation Received</label>
                            </div>
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
                                            <option value="1">Tropical Cyclone</option>
                                            <option value="2">Heavy Rainfall</option>
                                            <option value="3">Earthquake</option>
                                            <option value="4">Landslide</option>
                                            <option value="5">Flooding</option>
                                            <option value="6">Fire</option>
                                            <option value="7">Accidents</option>
                                            <option value="8">Violence/Conflict</option>
                                            <option value="9">Others</option>
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
                                            <option value="1">Holiday</option>
                                            <option value="2">Incident In School</option>
                                            <option value="3">Classes/Work Suspended</option>
                                            <option value="4">No Class Suspension</option>
                                            <option value="5">Suspended F2F Classes</option>
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
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const incidentsDateUrl = '{{ url("/incidents/date") }}';
        const incidentsStoreUrl = '{{ route("incidents.store") }}';

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
    </script>
</body>
</html>