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
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
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
            transition: all 0.2s;
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
            transform: scale(1.05);
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
                <button class="btn btn-light btn-lg rounded-pill shadow-sm" style="color: var(--incident-orange); font-weight: 600;">
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
                            January 2026
                        </div>
                        <div class="calendar-nav">
                            <button class="btn btn-sm btn-outline-secondary rounded-circle"><i class="fas fa-chevron-left"></i></button>
                            <button class="btn btn-sm btn-warning rounded-pill mx-2 px-3">Today</button>
                            <button class="btn btn-sm btn-outline-secondary rounded-circle"><i class="fas fa-chevron-right"></i></button>
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

                        <!-- Previous Month Days -->
                        <div class="calendar-day other-month"><span class="day-num">28</span></div>
                        <div class="calendar-day other-month"><span class="day-num">29</span></div>
                        <div class="calendar-day other-month"><span class="day-num">30</span></div>
                        <div class="calendar-day other-month"><span class="day-num">31</span></div>

                        <!-- Current Month Days -->
                        @for($i = 1; $i <= 31; $i++)
                        <div class="calendar-day {{ $i == 30 ? 'bg-light border-warning' : '' }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="day-num">{{ $i }}</span>
                                @if($i == 15)
                                    <span class="badge status-holiday" title="Holiday">H</span>
                                @endif
                                @if($i == 22)
                                    <span class="badge status-suspended" title="Suspended">S</span>
                                @endif
                            </div>
                            <div class="mt-2">
                                @if($i == 10 || $i == 25)
                                    <span class="incident-dot type-cyclone" title="Tropical Cyclone"></span>
                                @endif
                                @if($i == 5)
                                    <span class="incident-dot type-fire" title="Fire"></span>
                                @endif
                                @if($i == 20)
                                    <span class="incident-dot type-accident" title="Accident"></span>
                                    <span class="incident-dot type-rainfall" title="Heavy Rainfall"></span>
                                @endif
                            </div>
                        </div>
                        @endfor
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
                                @forelse([] as $incident)
                                <!-- This will be populated dynamically -->
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

                                <!-- Sample Data for UI Demonstration -->
                                <tr>
                                    <td>
                                        <div class="fw-bold">Jan 10</div>
                                        <small class="text-muted">08:30 AM</small>
                                    </td>
                                    <td>
                                        <div class="school-name">North Central High</div>
                                        <span class="badge type-cyclone small">Tropical Cyclone</span>
                                    </td>
                                    <td>
                                        <small class="text-truncate d-block" style="max-width: 150px;">Minor roof damage reported.</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="fw-bold">Jan 22</div>
                                        <small class="text-muted">10:15 AM</small>
                                    </td>
                                    <td>
                                        <div class="school-name">South Elementary</div>
                                        <span class="badge status-suspended small">Classes Suspended</span>
                                    </td>
                                    <td>
                                        <small class="text-truncate d-block" style="max-width: 150px;">Heavy rains leading to flooding.</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="stat-value">0</div>
                                <div class="stat-label">Total Logs</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-value text-danger">0</div>
                                <div class="stat-label">Critical</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-value text-success">0</div>
                                <div class="stat-label">Resolved</div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
