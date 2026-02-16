@extends('layouts.fire-safety')

@section('title', 'Alarm Systems - Fire Safety')

@section('styles')
    <style>
        .test-overdue {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
        }

        .status-functional, .status-active, .status-online { color: #28a745 !important; font-weight: bold; } /* Green */
        .status-missing, .status-broken, .status-maintenance, .status-decommissioned, .status-system-error { color: #dc3545 !important; font-weight: bold; } /* Red */
        .status-offline, .status-not-yet-installed, .status-issues, .status-jammed, .status-under-repair { color: #ffc107 !important; font-weight: bold; } /* Yellow */
        .status-not-installed { color: #6c757d !important; font-weight: bold; } /* Gray */

        .test-overdue {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
        }
     :root {
            --fire-red: #A8191F;
            --fire-dark-red: #8A1217;
            --fire-light-red: #F8D7DA;
            --charcoal: #36454F;       /* ← ADD THIS */
            --dark-charcoal: #2C3E50;  /* ← ADD THIS */
        }
    .top-nav {
        background: linear-gradient(135deg, var(--fire-red) 0%, var(--charcoal) 100%);
    }
    .sidebar {
        background: linear-gradient(180deg, var(--fire-red) 0%, var(--dark-charcoal) 100%);
    }
    </style>
@endsection

@section('modals')

    <!-- Alarm Details & Update Modal -->
    <div class="modal fade" id="updateAlarmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i> Alarm System Details & Update
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="updateAlarmForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="alarm_id" id="updateAlarmId">
                        <input type="hidden" id="updateSchoolId">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">School</label>
                                <p class="form-control-plaintext border-bottom" id="updateDisplaySchool">Loading...</p>
                            </div>
                            <!-- Building Selection Logic -->
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="updateCoversMultiple" name="covers_multiple">
                                    <label class="form-check-label fw-bold" for="updateCoversMultiple">
                                        Yes, it covers multiple buildings
                                    </label>
                                </div>
                                <label class="form-label fw-bold">Building(s) *</label>
                                <select class="form-control" name="building_ids[]" id="updateBuildingSelect" required>
                                    <!-- Populated via JS -->
                                </select>
                                <small class="text-muted" id="updateMultiSelectHelp" style="display:none;">Hold Ctrl/Cmd to select multiple buildings</small>
                            </div>
                        </div>

                        <!-- Floor Selection - Only for Single Building -->
                        <div id="updateFloorsContainer" style="display:none;" class="mb-3 p-3 bg-light rounded border">
                            <label class="form-label fw-bold mb-2">Select Floor Location *</label>
                            <select class="form-control" name="floor_id" id="updateFloorSelect">
                                <option value="">Select Building First</option>
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Alarm Code *</label>
                                <input type="text" class="form-control" name="code" id="updateAlarmCode" required>
                                <input type="hidden" id="originalAlarmCode">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Alarm Type</label>
                                <input type="text" class="form-control bg-light" id="updateAlarmTypeDisplay" readonly disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Status *</label>
                                <select class="form-control" name="status" id="updateStatusSelect" required>
                                    <!-- Options populated by JS -->
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Location Details *</label>
                                <input type="text" class="form-control" name="location" id="updateAlarmSpecificLocation" placeholder="e.g. Main Lobby, Hallway" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Next Test Due *</label>
                                <input type="date" class="form-control" name="next_test_due" id="updateNextTestDue" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Last Test Date</label>
                                <input type="date" class="form-control" name="last_test" id="updateLastTestDate" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Manufacturer</label>
                                <input type="text" class="form-control" name="manufacturer" id="updateManufacturer">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Installation Date</label>
                                <input type="date" class="form-control" name="installation_date" id="updateInstallationDateInput">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Notes/Remarks</label>
                            <textarea class="form-control" name="notes" id="updateNotes" rows="3"></textarea>
                        </div>

                        <div class="alert alert-info py-2">
                            <i class="fas fa-info-circle me-1"></i> <strong>Note:</strong> Updating this information will set the "As Of" (Last Test) date to today.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    @if(Auth::user()->role !== 'viewer')
                    <button type="button" class="btn btn-danger me-auto" onclick="removeAlarmSystem()">
                        <i class="fas fa-trash me-2"></i> Remove
                    </button>
                    @endif
                    @if(Auth::user()->role !== 'viewer')
                    <button type="button" class="btn btn-primary" onclick="updateAlarmSystem()">
                        <i class="fas fa-save me-2"></i> Save Changes
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add New Alarm System Modal -->
    <div class="modal fade" id="addAlarmModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--fire-red); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i> Add New Alarm System
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addAlarmForm">
                        @csrf
                        <input type="hidden" name="school_id" id="modalSchoolId">

                        <!-- Building Selection Logic -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="coversMultiple" name="covers_multiple">
                                    <label class="form-check-label fw-bold" for="coversMultiple">
                                        Yes, it covers multiple buildings
                                    </label>
                                </div>
                                <label class="form-label fw-bold">Building(s) *</label>
                                <select class="form-control" name="building_ids[]" id="addBuildingSelect" required>
                                    <!-- Populated via JS -->
                                </select>
                                <small class="text-muted" id="multiSelectHelp" style="display:none;">Hold Ctrl/Cmd to select multiple buildings</small>
                            </div>
                        </div>

                        <!-- Floor Selection - Only for Single Building -->
                        <div id="floorsContainer" style="display:none;" class="mb-3 p-3 bg-light rounded border">
                            <label class="form-label fw-bold mb-2">Select Floor Location *</label>
                            <select class="form-control" name="floor_id" id="addFloorSelect">
                                <option value="">Select Building First</option>
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Alarm Code *</label>
                                <input type="text" class="form-control" name="code" id="alarmCode" placeholder="e.g. ALARM-001" required onblur="checkAlarmCode(this.value)">
                                <div class="invalid-feedback" id="codeError">Alarm code already exists</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Alarm Type *</label>
                                <select class="form-control" name="alarm_type" id="addAlarmType" required>
                                    <option value="" disabled selected>Select Type</option>
                                    @foreach($alarmTypes as $type)
                                        <option value="{{ $type->name }}" data-type-id="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Status *</label>
                                <select class="form-control" name="status" id="addStatusSelect" required>
                                    <option value="functional">Functional (Active)</option>
                                    @foreach($alarmStatusesByType as $parentId => $statuses)
                                        <optgroup label="{{ \App\Models\SystemConfiguration::find($parentId)->name }}" data-parent-id="{{ $parentId }}">
                                            @foreach($statuses as $status)
                                                <option value="{{ $status->name }}">{{ $status->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Location Details *</label>
                                <input type="text" class="form-control" name="location" id="alarmSpecificLocation" placeholder="e.g. Main Lobby, Hallway" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Manufacturer</label>
                                <input type="text" class="form-control" name="manufacturer">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Installation Date</label>
                                <input type="date" class="form-control" name="installation_date" id="installationDate">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Last Test Date</label>
                                <input type="date" class="form-control" name="last_test" id="lastTestDate" max="{{ date('Y-m-d') }}">
                                <div class="form-text">Cannot be in the future.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Next Test Due *</label>
                                <input type="date" class="form-control" name="next_test_due" id="nextTestDue" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Notes/Remarks</label>
                            <textarea class="form-control" name="notes" rows="2" placeholder="Additional information..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    @if(Auth::user()->role !== 'viewer')
                    <button type="button" class="btn btn-primary" onclick="saveAlarmSystem()">
                        <i class="fas fa-save me-2"></i> Save Alarm System
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Alarm System Removal Modal -->
    <div class="modal fade" id="alarmRemovalModal" tabindex="-1" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Remove Alarm System</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="fw-bold">Are you sure you want to remove this alarm system?</p>
                    <p class="text-muted small">This action cannot be undone. All historical data for this alarm will be moved to the archives.</p>

                    <div class="mt-4">
                        <label class="form-label fw-bold">Reason to be removed *</label>
                        <textarea class="form-control" id="alarmRemovalReason" rows="3" placeholder="Enter reason for removal..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="confirmRemoveAlarm()">
                        <i class="fas fa-trash-alt me-2"></i>Yes, Remove It!
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alarm System History Modal -->
    <div class="modal fade" id="alarmHistoryModal" tabindex="-1" aria-modal="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #6c757d; color: white;">
                    <h5 class="modal-title"><i class="fas fa-history me-2"></i>Alarm System's History</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm" id="alarmHistoryTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Date Removed</th>
                                    <th>Code</th>
                                    <th>Type</th>
                                    <th>Last Location</th>
                                    <th>Reason to be removed</th>
                                    <th>Last Status</th>
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
@endsection

@section('content')
    @if($schools->isEmpty())
        <div class="row">
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-school fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted mb-3">No Schools Found</h4>
                        <p class="text-muted mb-4">You need to add a school that will be under inspection first.</p>
                        <a href="{{ route('fire-safety.dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Go to Dashboard to Add School
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else

        @php $school = $activeSchool; @endphp

        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">

                        <div class="card dashboard-card border-left-success h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                            Functional
                                        </div>
                                        <div class="h2 mb-0 fw-bold text-gray-800">
                                            {{ $school->alarmSystems()->whereIn('status', ['functional', 'online', 'active'])->count() }}
                                        </div>
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
                                            Issues
                                        </div>
                                        <div class="h2 mb-0 fw-bold text-gray-800">
                                            {{ $school->alarmSystems()->whereNotIn('status', ['functional', 'online', 'active'])->count() }}
                                        </div>
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
                                            Needs Testing
                                        </div>
                                        <div class="h2 mb-0 fw-bold text-gray-800">
                                            {{ $school->alarmSystems()->where('next_test_due', '<', now())->count() }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-warning"></i>
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
                                            Last Inspected
                                        </div>
                                        <div class="small fw-bold text-gray-800">
                                            @php
                                                $latestTested = $school->alarmSystems()
                                                    ->whereNotNull('last_test')
                                                    ->orderBy('last_test', 'desc')
                                                    ->take(2)
                                                    ->get();
                                            @endphp
                                            @if($latestTested->count() > 0)
                                                @foreach($latestTested as $tested)
                                                    <div class="mb-1">
                                                        <strong>{{ $tested->code }}</strong><br>
                                                        <small class="text-muted">{{ \Carbon\Carbon::parse($tested->last_test)->format('Y-m-d') }}</small>
                                                    </div>
                                                @endforeach
                                            @else
                                                <span class="text-muted">No tests recorded</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-alt fa-2x text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alarm Systems Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card dashboard-card">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-list me-2"></i> Alarm Systems List - {{ $school->school_name }}
                                </h6>
                                <div class="d-flex">
                                    @if(Auth::user()->role !== 'viewer')
                                    <button class="btn btn-primary btn-sm add-alarm-btn ms-2"
                                            data-school-id="{{ $school->id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#addAlarmModal">
                                        <i class="fas fa-plus me-2"></i> Add New Alarm
                                    </button>
                                    <button class="btn btn-sm ms-2"
                                            style="background-color: #e9ecef; color: #495057; border: 1px solid #ced4da;"
                                            onclick="openAlarmHistoryModal({{ $school->id }})">
                                        <i class="fas fa-history me-1"></i> Removed Alarm System
                                    </button>
                                    @endif
                                    <a href="{{ route('fire-safety.report.alarm-details', $school->id) }}" target="_blank"
                                            class="btn btn-sm ms-2"
                                            style="background-color: #e9ecef; color: #495057; border: 1px solid #ced4da;">
                                        <i class="fas fa-print me-1"></i> Print Alarm Details
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Building</th>
                                                <th>Code</th>
                                                <th>Type</th>
                                                <th>Status</th>
                                                <th>AS OF</th>
                                                <th>Next Test Due</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($school->alarmSystems as $alarm)
                                                <tr>
                                                    <td>
                                                        @if($alarm->buildings->count() > 0)
                                                            @foreach($alarm->buildings as $building)
                                                                <span class="badge bg-secondary">{{ $building->building_no }}</span>
                                                                <small class="text-muted d-block">{{ $building->building_name }}</small>
                                                            @endforeach
                                                        @else
                                                            <span class="text-muted">No buildings assigned</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $alarm->code }}</td>
                                                    <td>{{ $alarm->alarm_type }}</td>
                                                    <td>
                                                        @php
                                                            $statusClass = 'status-' . str_replace([' ', '_'], '-', strtolower($alarm->status));
                                                            $displayStatus = ucwords(str_replace('_', ' ', $alarm->status));
                                                        @endphp
                                                        <span class="alarm-status {{ $statusClass }}">
                                                            <i class="fas fa-circle"></i> {{ $displayStatus }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $alarm->last_test ? \Carbon\Carbon::parse($alarm->last_test)->format('Y-m-d') : 'Never' }}</td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($alarm->next_test_due)->format('Y-m-d') }}
                                                        @if($alarm->next_test_due < now())
                                                            <span class="badge bg-danger ms-2">Overdue</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            @if(Auth::user()->role !== 'viewer')
                                                            <button class="btn btn-sm btn-outline-primary test-now-btn"
                                                                    data-alarm-id="{{ $alarm->id }}"
                                                                    data-alarm-code="{{ $alarm->code }}">
                                                                <i class="fas fa-play"></i> Test Now
                                                            </button>
                                                            @endif
                                                            <button class="btn btn-sm btn-outline-info update-alarm-btn"
                                                                    data-alarm-id="{{ $alarm->id }}">
                                                                <i class="fas fa-edit"></i> Details
                                                            </button>
                                                            @if(Auth::user()->role !== 'viewer')
                                                            <button class="btn btn-sm btn-outline-danger remove-alarm-btn-table"
                                                                    onclick="currentAlarmId = '{{ $alarm->id }}'; removeAlarmSystem();">
                                                                <i class="fas fa-trash"></i> Remove
                                                            </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4">
                                                        <div class="text-muted mb-2">No alarm systems found for this school.</div>
                                                        @if(Auth::user()->role !== 'viewer')
                                                        <button class="btn btn-sm btn-primary add-alarm-btn ms-2"
                                                                data-school-id="{{ $school->id }}"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#addAlarmModal">
                                                            <i class="fas fa-plus me-2"></i> Add New Alarm
                                                        </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testing Schedule -->
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <div class="card dashboard-card">
                            <div class="card-header py-3">
                                <h6 class="m-0 fw-bold text-primary">
                                    <i class="fas fa-calendar-check me-2"></i> Upcoming Tests - {{ $school->school_name }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                @foreach($school->alarmSystems->where('next_test_due', '>=', now())->sortBy('next_test_due')->take(5) as $alarm)
                                @php
                                    $nextTest = \Carbon\Carbon::parse($alarm->next_test_due);
                                    $borderClass = $nextTest->diffInDays(now()) <= 7 ? 'border-warning' : 'border-info';
                                @endphp
                                <div class="col-md-4 mb-3">
                                    <div class="card {{ $borderClass }}">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $alarm->code }}</h6>

                                            <p class="card-text mb-1">
                                                <small class="text-muted">Building:
                                                    @if($alarm->buildings->count() > 0)
                                                        {{ $alarm->buildings->pluck('building_no')->implode(', ') }}
                                                    @else
                                                        {{ $alarm->building->building_no ?? 'N/A' }}
                                                    @endif
                                                </small>
                                            </p>
                                            </p>
                                            <p class="card-text mb-1">
                                                <small class="text-muted">Type: {{ $alarm->alarm_type }}</small>
                                            </p>
                                            <p class="card-text">
                                                <strong>Due: {{ \Carbon\Carbon::parse($alarm->next_test_due)->format('Y-m-d') }}</strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

    @endif
@endsection

@section('scripts')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>


                // Multi-Building Selection Logic
        document.addEventListener('DOMContentLoaded', function() {
            const addModalEl = document.getElementById('addAlarmModal');
            if (addModalEl) {
                const coversMultipleCheckbox = document.getElementById('coversMultiple');
                const buildingSelect = document.getElementById('addBuildingSelect');
                const multiSelectHelp = document.getElementById('multiSelectHelp');
                const floorsContainer = document.getElementById('floorsContainer');
                const floorSelect = document.getElementById('addFloorSelect');
                const typeSelect = document.getElementById('addAlarmType');
                const statusSelect = document.getElementById('addStatusSelect');

                // Map Alarm Type Name -> ID for filtering
                const alarmTypeIds = {};
                @foreach($alarmTypes as $type)
                    alarmTypeIds['{{ $type->name }}'] = {{ $type->id }};
                @endforeach

                // Filter status options based on alarm type
                function filterStatusOptions(selectElement, typeName) {
                    const typeId = alarmTypeIds[typeName];
                    const optgroups = selectElement.querySelectorAll('optgroup');

                    optgroups.forEach(group => {
                        const parentId = group.getAttribute('data-parent-id');
                        if (parentId == typeId) {
                            group.style.display = '';
                            group.disabled = false;
                        } else {
                            group.style.display = 'none';
                            group.disabled = true;
                        }
                    });

                    // Reset value if currently selected option is now hidden
                    const selectedOpt = selectElement.options[selectElement.selectedIndex];
                    if (selectedOpt && selectedOpt.parentElement.tagName === 'OPTGROUP' && selectedOpt.parentElement.disabled) {
                        selectElement.value = 'functional';
                    }
                }

                // Toggle Multiple/Single Building
                if (coversMultipleCheckbox) {
                    coversMultipleCheckbox.addEventListener('change', function() {
                        if (this.checked) {
                            buildingSelect.setAttribute('multiple', 'multiple');
                            buildingSelect.size = 4;
                            multiSelectHelp.style.display = 'block';
                            floorsContainer.style.display = 'none';
                            floorSelect.required = false;
                            floorSelect.value = "";
                            document.getElementById('alarmSpecificLocation').value = "Multiple Buildings - Shared System";
                        } else {
                            buildingSelect.removeAttribute('multiple');
                            buildingSelect.removeAttribute('size');
                            // If multiple selected, keep only first
                            if (buildingSelect.selectedOptions.length > 1) {
                                for (let i = 0; i < buildingSelect.options.length; i++) {
                                    buildingSelect.options[i].selected = (i === buildingSelect.selectedOptions[0].index);
                                }
                            }
                            multiSelectHelp.style.display = 'none';
                            handleBuildingChange();
                        }
                    });
                }

                // Handle Building Selection Change
                if (buildingSelect) {
                    buildingSelect.addEventListener('change', handleBuildingChange);
                }
                
                // --- UPDATE MODAL LOGIC ---
                const updateCoversMultiple = document.getElementById('updateCoversMultiple');
                const updateBuildingSelect = document.getElementById('updateBuildingSelect');
                
                if (updateCoversMultiple) {
                    updateCoversMultiple.addEventListener('change', function() {
                         const help = document.getElementById('updateMultiSelectHelp');
                         const floorsCont = document.getElementById('updateFloorsContainer');
                         const floorSel = document.getElementById('updateFloorSelect');
                         const locInput = document.getElementById('updateAlarmSpecificLocation');
                         
                         if (this.checked) {
                            updateBuildingSelect.setAttribute('multiple', 'multiple');
                            updateBuildingSelect.size = 4;
                            help.style.display = 'block';
                            floorsCont.style.display = 'none';
                            floorSel.required = false;
                            floorSel.value = "";
                            locInput.value = "Multiple Buildings - Shared System";
                         } else {
                            updateBuildingSelect.removeAttribute('multiple');
                            updateBuildingSelect.removeAttribute('size');
                            if (updateBuildingSelect.selectedOptions.length > 1) {
                                for (let i = 0; i < updateBuildingSelect.options.length; i++) {
                                    updateBuildingSelect.options[i].selected = (i === updateBuildingSelect.selectedOptions[0].index);
                                }
                            }
                            help.style.display = 'none';
                            handleUpdateBuildingChange();
                            // Clear location if it was the generic one
                            if (locInput.value === "Multiple Buildings - Shared System") {
                                locInput.value = "";
                            }
                         }
                    });
                }
                
                if (updateBuildingSelect) {
                    updateBuildingSelect.addEventListener('change', handleUpdateBuildingChange);
                }

                function handleUpdateBuildingChange() {
                    const isMultiple = document.getElementById('updateCoversMultiple').checked;
                    const bSelect = document.getElementById('updateBuildingSelect');
                    const floorsCont = document.getElementById('updateFloorsContainer');
                    const floorSel = document.getElementById('updateFloorSelect');
                    
                    if (!isMultiple && bSelect.value) {
                        const buildingId = bSelect.value;
                        floorsCont.style.display = 'block';
                        floorSel.required = true;
                        
                        // We need the floors count. We can store it in dataset when populating options.
                        const selectedOpt = bSelect.options[bSelect.selectedIndex];
                        const floors = selectedOpt ? parseInt(selectedOpt.dataset.floors || 1) : 1;
                        
                        floorSel.innerHTML = '<option value="">Select Floor</option><option value="All Floors">All Floors</option>';
                        
                        const getOrdinal = (n) => {
                            const s = ["th", "st", "nd", "rd"];
                            const v = n % 100;
                            return n + (s[(v - 20) % 10] || s[v] || s[0]);
                        };

                        for(let i = 1; i <= floors; i++) {
                            const opt = document.createElement('option');
                            opt.value = getOrdinal(i) + " Floor";
                            opt.textContent = getOrdinal(i) + " Floor";
                            floorSel.appendChild(opt);
                        }
                    } else {
                        floorsCont.style.display = 'none';
                        floorSel.required = false;
                    }
                }

                // Handle Alarm Type Change
                if (typeSelect) {
                    typeSelect.addEventListener('change', function() {
                        filterStatusOptions(statusSelect, this.value);
                    });
                }

                function handleBuildingChange() {
                    const isMultiple = coversMultipleCheckbox ? coversMultipleCheckbox.checked : false;

                    if (!isMultiple && buildingSelect.value) {
                        const buildingId = buildingSelect.value;
                        floorsContainer.style.display = 'block';
                        floorSelect.required = true;
                        floorSelect.innerHTML = '<option value="">Loading...</option>';

                        fetch(`/fire-safety/building/${buildingId}`)
                            .then(r => r.json())
                            .then(data => {
                                floorSelect.innerHTML = '<option value="">Select Floor</option><option value="All Floors">All Floors</option>';
                                if (data.floors) {
                                    // Helper for ordinal floors
                                    const getOrdinal = (n) => {
                                        const s = ["th", "st", "nd", "rd"];
                                        const v = n % 100;
                                        return n + (s[(v - 20) % 10] || s[v] || s[0]);
                                    };

                                    for(let i = 1; i <= data.floors; i++) {
                                        const opt = document.createElement('option');
                                        opt.value = getOrdinal(i) + " Floor";
                                        opt.textContent = getOrdinal(i) + " Floor";
                                        floorSelect.appendChild(opt);
                                    }
                                }
                            })
                            .catch(e => {
                                console.error(e);
                                floorSelect.innerHTML = '<option value="">Error loading floors</option>';
                            });
                    } else {
                        floorsContainer.style.display = 'none';
                        floorSelect.required = false;
                    }
                }

                // Modal Show Event
                addModalEl.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const schoolId = button.getAttribute('data-school-id');
                    const buildingId = button.getAttribute('data-building-id');

                    document.getElementById('modalSchoolId').value = schoolId;

                    // Reset form
                    document.getElementById('addAlarmForm').reset();
                    if(coversMultipleCheckbox) {
                        coversMultipleCheckbox.checked = false;
                        coversMultipleCheckbox.dispatchEvent(new Event('change'));
                    }

                    // Trigger type change to filter statuses initially
                    if(typeSelect) typeSelect.dispatchEvent(new Event('change'));

                    // Fetch buildings for this school
                    fetch(`/fire-safety/buildings/${schoolId}`)
                        .then(r => r.json())
                        .then(buildings => {
                            buildingSelect.innerHTML = '<option value="">Select Building</option>';
                            buildings.forEach(b => {
                                const option = document.createElement('option');
                                option.value = b.id;
                                option.text = `${b.building_no} - ${b.building_name}`;
                                option.dataset.floors = b.floors;
                                if (buildingId && b.id == buildingId) {
                                    option.selected = true;
                                }
                                buildingSelect.appendChild(option);
                            });
                            // Trigger change to setup floors
                            handleBuildingChange();
                        });
                });
            }
        });

        // Status options based on alarm type (Dynamic from DB)
        const statusOptions = {
            @foreach($alarmTypes as $type)
                '{{ $type->name }}': [
                    @php $typeStatuses = $alarmStatusesByType->get($type->id, collect()); @endphp
                    @foreach($typeStatuses as $s)
                        '{{ $s->name }}',
                    @endforeach
                ],
            @endforeach
        };

        // Store current school and alarm data
        let currentSchoolId = "{{ $activeSchool->id ?? '' }}";
        let currentAlarmId = null;
        let currentAlarmType = null;
        const userRole = "{{ auth()->user()->role }}";

        function checkViewerAccess(formId, buttonsId = null) {
            if (userRole === 'viewer') {
                const form = document.getElementById(formId);
                if (form) {
                    const elements = form.querySelectorAll('input, select, textarea, button:not([data-bs-dismiss="modal"])');
                    elements.forEach(el => el.disabled = true);
                }
                if (buttonsId) {
                    const buttons = document.getElementById(buttonsId);
                    if (buttons) buttons.style.display = 'none';
                }
            }
        }

        // Update Alarm button click (using delegation)
        document.body.addEventListener('click', async function(e) {
            const button = e.target.closest('.update-alarm-btn');
            if (!button) return;

            console.log('Update button clicked for alarm:', button.getAttribute('data-alarm-id'));

            const alarmId = button.getAttribute('data-alarm-id');
            currentAlarmId = alarmId;

            try {
                // Fetch alarm details first
                const response = await fetch(`/fire-safety/alarm/${alarmId}`);
                const alarm = await response.json();

                currentAlarmType = alarm.alarm_type;

                // Populate form basics
                document.getElementById('updateAlarmId').value = alarmId;
                document.getElementById('updateAlarmCode').value = alarm.code;
                document.getElementById('originalAlarmCode').value = alarm.code;
                document.getElementById('updateAlarmTypeDisplay').value = alarm.alarm_type;
                document.getElementById('updateDisplaySchool').textContent = alarm.school ? alarm.school.school_name : 'N/A';
                document.getElementById('updateSchoolId').value = alarm.school_id;

                // Fetch Buildings List for this school
                const buildingsResp = await fetch(`/fire-safety/buildings/${alarm.school_id}`);
                const buildings = await buildingsResp.json();
                
                const bSelect = document.getElementById('updateBuildingSelect');
                bSelect.innerHTML = '<option value="">Select Building</option>';
                buildings.forEach(b => {
                    const option = document.createElement('option');
                    option.value = b.id;
                    option.text = `${b.building_no} - ${b.building_name}`;
                    option.dataset.floors = b.floors;
                    bSelect.appendChild(option);
                });

                // Handle Coverage Logic
                const isMulti = (alarm.buildings && alarm.buildings.length > 1);
                const updateCoversMultiple = document.getElementById('updateCoversMultiple');
                updateCoversMultiple.checked = isMulti;
                
                // Trigger UI update manually based on state
                const help = document.getElementById('updateMultiSelectHelp');
                const floorsCont = document.getElementById('updateFloorsContainer');
                const floorSel = document.getElementById('updateFloorSelect');
                const locInput = document.getElementById('updateAlarmSpecificLocation');

                if (isMulti) {
                    bSelect.setAttribute('multiple', 'multiple');
                    bSelect.size = 4;
                    help.style.display = 'block';
                    floorsCont.style.display = 'none';
                    floorSel.required = false;
                    locInput.value = "Multiple Buildings - Shared System";
                    
                    // Select buildings
                    const assignedIds = alarm.buildings.map(b => b.id);
                    Array.from(bSelect.options).forEach(opt => {
                        if (assignedIds.includes(parseInt(opt.value))) opt.selected = true;
                    });
                } else {
                    bSelect.removeAttribute('multiple');
                    bSelect.removeAttribute('size');
                    help.style.display = 'none';
                    floorsCont.style.display = 'block';
                    floorSel.required = true;
                    
                    // Select single building (either from list or direct ID)
                    const bId = (alarm.buildings && alarm.buildings.length > 0) ? alarm.buildings[0].id : (alarm.building_id || '');
                    bSelect.value = bId;
                    
                    // Populate Floors for selected building
                    if (bId) {
                        const selectedOpt = bSelect.options[bSelect.selectedIndex];
                        const floors = selectedOpt ? parseInt(selectedOpt.dataset.floors || 1) : 1;
                        
                        floorSel.innerHTML = '<option value="">Select Floor</option><option value="All Floors">All Floors</option>';
                        
                        const getOrdinal = (n) => {
                            const s = ["th", "st", "nd", "rd"];
                            const v = n % 100;
                            return n + (s[(v - 20) % 10] || s[v] || s[0]);
                        };

                        for(let i = 1; i <= floors; i++) {
                            const opt = document.createElement('option');
                            opt.value = getOrdinal(i) + " Floor";
                            opt.textContent = getOrdinal(i) + " Floor";
                            floorSel.appendChild(opt);
                        }
                        
                        // Parse Location
                        let location = alarm.location || '';
                        let specificLoc = location;
                        let matchedFloor = '';
                        
                        // Try to match start of string with floor options
                        // Location format usually: "1st Floor - Lobby"
                        for (let opt of floorSel.options) {
                            if (opt.value && location.startsWith(opt.value + " - ")) {
                                matchedFloor = opt.value;
                                specificLoc = location.substring(opt.value.length + 3); // Remove "Floor - "
                                break;
                            } else if (opt.value && location === opt.value) {
                                matchedFloor = opt.value;
                                specificLoc = "";
                                break;
                            }
                        }
                        
                        floorSel.value = matchedFloor;
                        locInput.value = specificLoc;
                    } else {
                        // No building selected ??
                         floorsCont.style.display = 'none';
                    }
                }

                document.getElementById('updateManufacturer').value = alarm.manufacturer || '';
                document.getElementById('updateInstallationDateInput').value = alarm.installation_date || '';
                document.getElementById('updateNextTestDue').value = alarm.next_test_due || '';
                document.getElementById('updateNotes').value = alarm.notes || '';
                document.getElementById('updateLastTestDate').value = alarm.last_test || '';

                // Store installation date for validation
                document.getElementById('updateAlarmId').dataset.installationDate = alarm.installation_date || '';

                // Populate status options
                const statusSelect = document.getElementById('updateStatusSelect');
                statusSelect.innerHTML = '<option value="">Select Status</option>';

                let options = [...(statusOptions[alarm.alarm_type] || [])];
                if (!options.includes('Decommissioned')) options.push('Decommissioned');

                options.forEach(status => {
                    const option = document.createElement('option');
                    const statusValue = status.toLowerCase().replace(' ', '_');
                    option.value = statusValue;
                    option.textContent = status;
                    if (alarm.status === statusValue) {
                        option.selected = true;
                    }
                    statusSelect.appendChild(option);
                });

                // Show modal
                const modalEl = document.getElementById('updateAlarmModal');
                const modal = new bootstrap.Modal(modalEl);
                modal.show();

                // Enforce viewer role restrictions
                checkViewerAccess('updateAlarmForm');

            } catch (error) {
                console.error('Error loading alarm data:', error);
                Swal.fire('Error', 'Failed to load alarm details.', 'error');
            }
        });

        // Test Now button click (using event delegation)
        document.body.addEventListener('click', function(e) {
            const button = e.target.closest('.test-now-btn');
            if (!button) return;

            const alarmId = button.getAttribute('data-alarm-id');
            const alarmCode = button.getAttribute('data-alarm-code');

            Swal.fire({
                title: 'Test Alarm?',
                text: `Test alarm ${alarmCode} now? This will update the last test date to today.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Test Now'
            }).then((result) => {
                if (result.isConfirmed) {
                    testAlarmSystem(alarmId);
                }
            });
        });

        const simulateBtn = document.getElementById('simulateAlarmBtn');
        if (simulateBtn) {
            simulateBtn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Simulate Alarm Test?',
                    text: 'Are you sure you want to simulate an alarm test? This will trigger test alerts.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Simulate'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire('Simulating...', 'Alarm test simulation started!', 'info');
                    }
                });
            });
        }

        // Check if alarm code already exists
        async function checkAlarmCode(code) {
            if (!code || !currentSchoolId) return true;

            try {
                const response = await fetch(`/fire-safety/check-alarm-code/${currentSchoolId}/${encodeURIComponent(code)}`);
                const data = await response.json();

                const codeInput = document.getElementById('alarmCode');
                const errorDiv = document.getElementById('codeError');

                if (data.exists) {
                    codeInput.classList.add('is-invalid');
                    errorDiv.textContent = 'Alarm code already exists. Please use a different code.';
                    return false;
                } else {
                    codeInput.classList.remove('is-invalid');
                    return true;
                }
            } catch (error) {
                console.error('Error checking alarm code:', error);
                return true;
            }
        }

        // Date validation
        function validateDates() {
            const installationDate = document.getElementById('installationDate').value;
            const lastTestDate = document.getElementById('lastTestDate').value;
            const nextTestDue = document.getElementById('nextTestDue').value;
            const today = new Date().toISOString().split('T')[0];

            let isValid = true;

            if (installationDate && installationDate > today) {
                Swal.fire('Validation Error', 'Installation date cannot be in the future.', 'warning');
                return false;
            }
            if (lastTestDate && lastTestDate > today) {
                Swal.fire('Validation Error', 'Last test date cannot be in the future.', 'warning');
                return false;
            }
            if (installationDate && lastTestDate && lastTestDate < installationDate) {
                Swal.fire('Validation Error', 'Last test date cannot be before installation date.', 'warning');
                return false;
            }
            if (installationDate && nextTestDue && nextTestDue < installationDate) {
                Swal.fire('Validation Error', 'Next test due date cannot be before installation date.', 'warning');
                return false;
            }
            if (lastTestDate && nextTestDue && nextTestDue < lastTestDate) {
                Swal.fire('Validation Error', 'Next test due date cannot be before last test date.', 'warning');
                return false;
            }
            return true;
        }

        // Save Alarm System
        async function saveAlarmSystem() {
            const form = document.getElementById('addAlarmForm');

            // Validate dates
            if (!validateDates()) {
                return;
            }

            // Validate alarm code
            const code = document.getElementById('alarmCode').value;
            const codeValid = await checkAlarmCode(code);
            if (!codeValid) {
                Swal.fire('Error', 'Please fix the alarm code error.', 'error');
                return;
            }

            // Check building selection
            const buildingSelect = document.getElementById('addBuildingSelect');
            const isMultiple = document.getElementById('coversMultiple').checked;

            if (isMultiple) {
                // Check if at least one building is selected for multi-building
                const selectedBuildings = Array.from(buildingSelect.selectedOptions).map(opt => opt.value);
                if (selectedBuildings.length === 0 || (selectedBuildings.length === 1 && selectedBuildings[0] === '')) {
                    Swal.fire('Missing Information', 'Please select at least one building.', 'warning');
                    return;
                }
                // For multi-building, set location as "Multiple Buildings"
                document.getElementById('alarmSpecificLocation').value = "Multiple Buildings - Shared System";
            } else {
                // Single building validation
                if (!buildingSelect.value) {
                    Swal.fire('Missing Information', 'Please select a building.', 'warning');
                    return;
                }

                // Check floor selection for single building
                const floor = document.getElementById('addFloorSelect').value;
                const specific = document.getElementById('alarmSpecificLocation').value.trim();

                if (!floor) {
                    Swal.fire('Missing Information', 'Please select a floor.', 'warning');
                    return;
                }

                if (!specific) {
                    Swal.fire('Missing Information', 'Please enter a specific location.', 'warning');
                    return;
                }
            }

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Get CSRF token
            let csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!csrfToken) {
                csrfToken = form.querySelector('input[name="_token"]')?.value;
                if (!csrfToken) {
                    csrfToken = document.querySelector('input[name="csrf_token"]')?.value;
                    if (!csrfToken) {
                        console.error('CSRF token not found anywhere');
                        Swal.fire('Error', 'Security token missing. Please refresh the page and try again.', 'error');
                        return;
                    }
                }
            }

            console.log('CSRF Token found:', csrfToken ? 'Yes' : 'No');

            // Combine Location for single building
            if (!isMultiple) {
                const floor = document.getElementById('addFloorSelect').value;
                const specific = document.getElementById('alarmSpecificLocation').value.trim();
                document.getElementById('alarmSpecificLocation').value = `${floor} - ${specific}`;
            }

            const formData = new FormData(form);

            // Log what we're sending for debugging
            console.log('Form action:', '{{ route("fire-safety.alarm.store") }}');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }

            try {
                const response = await fetch('{{ route("fire-safety.alarm.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(formData)
                });

                console.log('Response status:', response.status);

                const data = await response.json();
                console.log('Response data:', data);

                if (data.success) {
                    // Hide modal first so user can focus on message
                    const modalEl = document.getElementById('addAlarmModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();

                    Swal.fire('Success', 'Alarm system added successfully!', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    // Hide modal on error too so message is visible
                    const modalEl = document.getElementById('addAlarmModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();

                    let errorMessage = data.message || 'Failed to add alarm system';
                    if (data.errors) {
                        const errorList = Object.values(data.errors).flat().join('\n');
                        errorMessage += '\n' + errorList;
                    }
                    Swal.fire('Error', errorMessage, 'error');
                }

            } catch (error) {
                console.error('Error details:', error);
                Swal.fire('Error', 'Failed to add alarm system. Check console (F12) for details.', 'error');
            }
        }

        // Update Alarm System
        async function updateAlarmSystem() {
            const form = document.getElementById('updateAlarmForm');
            const alarmId = document.getElementById('updateAlarmId').value;
            const newCode = document.getElementById('updateAlarmCode').value;
            const oldCode = document.getElementById('originalAlarmCode').value;
            
            // Handle Location Combination Logic
            const isMultiple = document.getElementById('updateCoversMultiple').checked;
            const bSelect = document.getElementById('updateBuildingSelect');
            
            if (isMultiple) {
                // Check if at least one building selected
                if (bSelect.selectedOptions.length === 0) {
                     Swal.fire('Missing Information', 'Please select at least one building.', 'warning');
                     return;
                }
                document.getElementById('updateAlarmSpecificLocation').value = "Multiple Buildings - Shared System";
            } else {
                // Single Building Valdation
                if (!bSelect.value) {
                     Swal.fire('Missing Information', 'Please select a building.', 'warning');
                     return;
                }
                
                const floor = document.getElementById('updateFloorSelect').value;
                const specific = document.getElementById('updateAlarmSpecificLocation').value.trim();
                
                if (!floor) {
                     Swal.fire('Missing Information', 'Please select a floor.', 'warning');
                     return;
                }
                
                if (!specific) {
                     Swal.fire('Missing Information', 'Please enter a specific location.', 'warning');
                     return;
                }
                
                // Combine value for submission
                // We modify the input value just before FormData creation
                // Note: We might want to revert this if submission fails, but for now it's fine as page reloads on success
                // Actually, let's keep the specific value clean in the input and append to FormData manually if needed, 
                // BUT FormData reads from the input value.
                // To avoid visual glitch if we cancel/fail, we can just handle the string in FormData.
            }

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Code update confirmation
            if (newCode !== oldCode) {
                const confirmCode = await Swal.fire({
                    title: 'Update Alarm Code?',
                    text: `Are you sure you want to update the alarm code from "${oldCode}" to "${newCode}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#A8191F',
                    confirmButtonText: 'Yes, update it!',
                    cancelButtonText: 'No, keep original'
                });

                if (!confirmCode.isConfirmed) {
                    document.getElementById('updateAlarmCode').value = oldCode;
                    return;
                }
            }

            // Get dates
            const nextTestDue = document.getElementById('updateNextTestDue').value;
            const installationDate = document.getElementById('updateAlarmId').dataset.installationDate;
            const today = new Date().toISOString().split('T')[0];

            if (installationDate) {
                // Check next test not before installation
                if (nextTestDue && nextTestDue < installationDate) {
                    Swal.fire('Invalid Date', 'Next test due date cannot be before installation date.', 'warning');
                    return;
                }
            }

            const formData = new FormData(form);
            formData.append('_method', 'PUT');
            // Auto-update Last Test to Today
            formData.append('last_test', today);
            
            // Handle Location Override in FormData for Single Building
            if (!isMultiple) {
                const floor = document.getElementById('updateFloorSelect').value;
                const specific = document.getElementById('updateAlarmSpecificLocation').value.trim();
                let combinedLocation = specific;
                if (floor && floor !== 'All Floors') {
                    combinedLocation = `${floor} - ${specific}`;
                }
                formData.set('location', combinedLocation);
            } else {
                 formData.set('location', "Multiple Buildings - Shared System");
            }

            // Show loading
            Swal.fire({
                title: 'Updating...',
                text: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const response = await fetch(`/fire-safety/alarm/${alarmId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // Hide modal first so user can focus on message
                    const modalEl = document.getElementById('updateAlarmModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();

                    Swal.fire({
                        title: 'Updated',
                        text: 'Alarm system details updated successfully!',
                        icon: 'success',
                        confirmButtonColor: '#A8191F'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    // Hide modal on error too so message is visible
                    const modalEl = document.getElementById('updateAlarmModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();

                    let errorMessage = data.message || 'Failed to update alarm system';
                    if (data.errors) {
                        const errorList = Object.values(data.errors).flat().join('\n');
                        errorMessage += '\n' + errorList;
                    }
                    Swal.fire('Error', errorMessage, 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Failed to update alarm system', 'error');
            }
        }

        // Test Alarm System
        async function testAlarmSystem(alarmId) {
            try {
                const response = await fetch(`/fire-safety/alarm/${alarmId}/test`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire('Success', 'Alarm test completed successfully! Last test date updated.', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to test alarm system', 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Failed to test alarm system', 'error');
            }
        }

        // Open Removal Modal
        function removeAlarmSystem() {
            // currentAlarmId is already set by the button onclick which is set in update-alarm-btn click handler or table button
            // If called from the Update Modal, we need to ensure currentAlarmId works
            if (!window.currentAlarmId) {
                 window.currentAlarmId = document.getElementById('updateAlarmId').value;
            }

            // Close update modal if open
            const updateModal = bootstrap.Modal.getInstance(document.getElementById('updateAlarmModal'));
            if (updateModal) {
                updateModal.hide();
            }

            document.getElementById('alarmRemovalReason').value = '';
            const modalEl = document.getElementById('alarmRemovalModal');
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }

        async function confirmRemoveAlarm() {
            const reason = document.getElementById('alarmRemovalReason').value;
            if (!reason.trim()) {
                Swal.fire('Reason Required', 'Please provide a reason for removal.', 'warning');
                return;
            }

            try {
                const response = await fetch(`/fire-safety/alarm/${currentAlarmId}/remove`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ reason: reason })
                });

                const data = await response.json().catch(() => ({}));

                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('alarmRemovalModal'));
                    if(modal) modal.hide();

                    Swal.fire('Removed', 'Alarm system has been archived successfully!', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to remove alarm system', 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Failed to remove alarm system. Check console for details.', 'error');
            }
        }

        async function openAlarmHistoryModal(schoolId) {
            const modalEl = document.getElementById('alarmHistoryModal');
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            const tableBody = document.querySelector('#alarmHistoryTable tbody');
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Loading...</td></tr>';
            modal.show();

            try {
                const resp = await fetch(`/fire-safety/alarm/history/${schoolId}`);
                const data = await resp.json();

                if (data.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No removed alarm systems found.</td></tr>';
                    return;
                }

                tableBody.innerHTML = '';
                data.forEach(item => {
                    const removedAt = new Date(item.removed_at).toLocaleString();
                    const row = `
                        <tr>
                            <td>${removedAt}</td>
                            <td class="fw-bold text-danger">${item.item_code || 'N/A'}</td>
                            <td>${item.item_data.alarm_type || 'N/A'}</td>
                            <td>${item.item_data.building_name || 'N/A'}</td>
                            <td>${item.reason || 'No reason provided'}</td>
                            <td><span class="badge bg-secondary">${item.item_data.status}</span></td>
                        </tr>
                    `;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
            } catch (e) {
                console.error(e);
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Failed to load history.</td></tr>';
            }
        }

    </script>
@endsection


