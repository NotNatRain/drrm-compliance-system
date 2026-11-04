{{-- resources/views/heat-index-reports/heat-index_dashboard.blade.php --}}
@php
    $currentUser = auth()->user();
    $isAdmin = $currentUser && $currentUser->role === 'admin';

    // Resolve assigned school for non-admin user
    $assignedSchool = $contributorAssignedSchool 
        ?? ($currentUser?->school_id ? \App\Models\School::find($currentUser->school_id) : null)
        ?? ($currentUser?->typhoon_school_id ? \App\Models\School::find($currentUser->typhoon_school_id) : null)
        ?? ($currentUser?->incident_school_id ? \App\Models\School::find($currentUser->incident_school_id) : null);

    $assignedSchoolId = $assignedSchool?->id;
    $assignedSchoolName = $assignedSchool?->school_name;
    $assignedSchoolHead = $assignedSchool?->school_head ?: ($currentUser?->name ?? 'School Head');

    // Pre-load submissions from DB if available
    $initialSubmissions = \Illuminate\Support\Facades\Schema::hasTable('heat_index_reports')
        ? \App\Models\HeatIndexReport::with('school', 'user')
            ->when(!$isAdmin && $assignedSchoolId, function($q) use ($assignedSchoolId) {
                return $q->where('school_id', $assignedSchoolId);
            })
            ->latest()
            ->take(50)
            ->get()
        : collect();
@endphp

<div class="modal fade" id="heatIndexReportModal" tabindex="-1" aria-labelledby="heatIndexReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="max-width: 1180px;">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            
            <!-- Modal Header -->
            <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #FF6B35 0%, #EA580C 50%, #C2410C 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3 bg-white text-danger shadow-sm" style="width: 46px; height: 46px; font-size: 1.4rem;">
                        <i class="fas fa-temperature-high" style="color: #EA580C;"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="modal-title fw-bold mb-0" id="heatIndexReportModalLabel" style="font-family: var(--font-display, 'Sora', sans-serif); letter-spacing: -0.02em;">
                                Heat Index Report & Monitoring System
                            </h5>
                            @if($isAdmin)
                                <span class="badge bg-dark text-white fw-bold px-2 py-1" style="font-size: 0.72rem;">
                                    <i class="fas fa-shield-alt me-1"></i> Admin Portal
                                </span>
                            @else
                                <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size: 0.72rem;">
                                    <i class="fas fa-school me-1"></i> School Head Portal
                                </span>
                            @endif
                        </div>
                        <div class="small text-white-50 d-flex align-items-center gap-2 mt-1">
                            <span><i class="fas fa-cloud-sun me-1"></i> PAGASA & DepEd Weather Safety Compliance</span>
                            <span>•</span>
                            <span class="badge bg-white text-dark fw-semibold" style="font-size: 0.72rem;">DepEd DO 037, s. 2022</span>
                            @if(!$isAdmin && $assignedSchool)
                                <span>•</span>
                                <span class="text-white fw-semibold">
                                    <i class="fas fa-map-marker-alt me-1"></i> {{ $assignedSchoolName }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-0 bg-light">
                
                <!-- Navigation Tabs -->
                <div class="bg-white border-bottom px-4 pt-3">
                    <ul class="nav nav-tabs border-0" id="heatIndexTabs" role="tablist">
                        @if($isAdmin)
                            <!-- ADMIN TAB 1: Calculator & Daily Log -->
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-bold text-dark border-0 border-bottom border-3 border-warning pb-3 px-3 d-flex align-items-center gap-2" 
                                        id="hi-calculator-tab" data-bs-toggle="tab" data-bs-target="#hi-calculator-pane" type="button" role="tab">
                                    <i class="fas fa-calculator text-warning"></i>
                                    <span>Calculator & Log Tool</span>
                                </button>
                            </li>
                            <!-- ADMIN TAB 2: All School Head Submissions (NEW SECTION) -->
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-danger border-0 pb-3 px-3 d-flex align-items-center gap-2" 
                                        id="hi-submissions-tab" data-bs-toggle="tab" data-bs-target="#hi-submissions-pane" type="button" role="tab">
                                    <i class="fas fa-inbox text-danger"></i>
                                    <span>School Head Submissions</span>
                                    <span class="badge bg-danger rounded-pill ms-1" id="hiAdminSubmissionsCountBadge">{{ count($initialSubmissions) }}</span>
                                </button>
                            </li>
                            <!-- ADMIN TAB 3: PAGASA Scale & Protocols -->
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-semibold text-muted border-0 pb-3 px-3 d-flex align-items-center gap-2" 
                                        id="hi-standards-tab" data-bs-toggle="tab" data-bs-target="#hi-standards-pane" type="button" role="tab">
                                    <i class="fas fa-shield-alt text-danger"></i>
                                    <span>PAGASA Scale & Protocols</span>
                                </button>
                            </li>
                            <!-- ADMIN TAB 4: General Logs History -->
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-semibold text-muted border-0 pb-3 px-3 d-flex align-items-center gap-2" 
                                        id="hi-history-tab" data-bs-toggle="tab" data-bs-target="#hi-history-pane" type="button" role="tab">
                                    <i class="fas fa-history text-primary"></i>
                                    <span>All Recorded Logs</span>
                                </button>
                            </li>
                        @else
                            <!-- NON-ADMIN / SCHOOL HEAD TABS -->
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-bold text-dark border-0 border-bottom border-3 border-warning pb-3 px-3 d-flex align-items-center gap-2" 
                                        id="hi-calculator-tab" data-bs-toggle="tab" data-bs-target="#hi-calculator-pane" type="button" role="tab">
                                    <i class="fas fa-paper-plane text-warning"></i>
                                    <span>Submit Heat Index Report</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-semibold text-muted border-0 pb-3 px-3 d-flex align-items-center gap-2" 
                                        id="hi-standards-tab" data-bs-toggle="tab" data-bs-target="#hi-standards-pane" type="button" role="tab">
                                    <i class="fas fa-shield-alt text-danger"></i>
                                    <span>PAGASA Scale & Suspension Guidelines</span>
                                </button>
                            </li>
                        @endif
                    </ul>
                </div>

                <!-- Tab Panes Content -->
                <div class="tab-content p-4" id="heatIndexTabContent">
                    
                    <!-- TAB: Calculator & Submission Form (Both Admin & School Head) -->
                    <div class="tab-pane fade show active" id="hi-calculator-pane" role="tabpanel">
                        
                        @if(!$isAdmin && !$assignedSchool)
                            <!-- Alert if user has no assigned school -->
                            <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center gap-3 p-4 mb-4 rounded-3">
                                <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">No School Assigned to Your Account</h6>
                                    <p class="mb-0 small text-muted">
                                        You are currently not assigned to any school. Submissions are strictly locked to each school's assigned personnel. Please contact your system administrator to assign your campus.
                                    </p>
                                </div>
                            </div>
                        @endif

                        <div class="row g-4">
                            <!-- Left: Input Form -->
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                                        <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                            <i class="fas fa-edit text-warning"></i> 
                                            @if($isAdmin)
                                                Log School Heat Index Reading
                                            @else
                                                Submit School Heat Index Report
                                            @endif
                                        </div>
                                        @if(!$isAdmin && $assignedSchool)
                                            <span class="badge bg-light text-success border border-success fw-semibold">
                                                <i class="fas fa-lock me-1"></i> School Locked
                                            </span>
                                        @endif
                                    </div>
                                    <div class="card-body p-4">
                                        <form id="heatIndexEntryForm" onsubmit="handleHeatIndexSubmit(event)">
                                            @csrf
                                            
                                            <!-- School Selection: LOCKED for Non-Admin, Dropdown for Admin -->
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted d-flex justify-content-between align-items-center">
                                                    <span>School Campus <span class="text-danger">*</span></span>
                                                    @if(!$isAdmin)
                                                        <span class="text-muted fw-normal" style="font-size: 0.75rem;">
                                                            <i class="fas fa-lock text-warning me-1"></i> Locked to your assigned school
                                                        </span>
                                                    @endif
                                                </label>

                                                @if($isAdmin)
                                                    <!-- Admin: can select any school -->
                                                    <select id="hi_school_select" class="form-select border-1" required onchange="onAdminSchoolSelectChange(this)">
                                                        <option value="">-- Choose a school --</option>
                                                        @if(isset($schools) && count($schools) > 0)
                                                            @foreach($schools as $sch)
                                                                <option value="{{ $sch->id }}" data-name="{{ $sch->school_name }}" data-head="{{ $sch->school_head ?: 'School Head' }}" data-school-id="{{ $sch->school_id ?: ($sch->school_id_number ?: '') }}">
                                                                    {{ $sch->school_name }} {{ $sch->school_id ? '(' . $sch->school_id . ')' : '' }}
                                                                </option>
                                                            @endforeach
                                                        @elseif(isset($allSchools) && count($allSchools) > 0)
                                                            @foreach($allSchools as $sch)
                                                                <option value="{{ $sch->id }}" data-name="{{ $sch->school_name }}" data-head="{{ $sch->school_head ?: 'School Head' }}" data-school-id="{{ $sch->school_id ?: ($sch->school_id_number ?: '') }}">
                                                                    {{ $sch->school_name }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                @else
                                                    <!-- Non-Admin: LOCKED to assigned school -->
                                                    @if($assignedSchool)
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light border-end-0 text-muted">
                                                                <i class="fas fa-lock text-warning"></i>
                                                            </span>
                                                            <input type="text" class="form-control bg-light border-start-0 fw-bold text-dark" 
                                                                   value="{{ $assignedSchoolName }} {{ $assignedSchool->school_id ? '(' . $assignedSchool->school_id . ')' : '' }}" 
                                                                   readonly disabled>
                                                        </div>
                                                        <input type="hidden" id="hi_school_select" value="{{ $assignedSchoolId }}" data-name="{{ $assignedSchoolName }}" data-head="{{ $assignedSchoolHead }}">
                                                    @else
                                                        <input type="text" class="form-control bg-light text-danger" value="No Assigned School" readonly disabled>
                                                        <input type="hidden" id="hi_school_select" value="">
                                                    @endif
                                                @endif
                                            </div>

                                            <!-- School Head / Submitter Info -->
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted">School Head / Submitter</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light text-muted"><i class="fas fa-user-tie"></i></span>
                                                    <input type="text" id="hi_school_head" class="form-control bg-light" 
                                                           value="{{ $isAdmin ? '' : ($assignedSchoolHead ?: $currentUser->name) }}" 
                                                           placeholder="School Head Name" 
                                                           {{ !$isAdmin ? 'readonly' : '' }}>
                                                </div>
                                            </div>

                                            <!-- Date and Time -->
                                            <div class="row g-2 mb-3">
                                                <div class="col-sm-6">
                                                    <label class="form-label fw-bold small text-muted">Date <span class="text-danger">*</span></label>
                                                    <input type="date" id="hi_log_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="form-label fw-bold small text-muted">Time of Reading <span class="text-danger">*</span></label>
                                                    <input type="time" id="hi_log_time" class="form-control" value="{{ date('H:i') }}" required>
                                                </div>
                                            </div>

                                            <!-- Ambient Temperature Input -->
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <label class="form-label fw-bold small text-muted mb-0">Ambient Air Temperature (°C) <span class="text-danger">*</span></label>
                                                    <span class="badge bg-light text-dark border fw-bold fs-6" id="hiTempDisplay">33.0 °C</span>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="range" class="form-range flex-grow-1" id="hiTempRange" min="20" max="50" step="0.5" value="33" oninput="syncTemp(this.value)">
                                                    <input type="number" class="form-control form-control-sm text-center" id="hiTempNumber" min="20" max="50" step="0.5" value="33.0" style="width: 75px;" oninput="syncTemp(this.value)">
                                                </div>
                                                <div class="d-flex justify-content-between text-muted" style="font-size: 0.72rem;">
                                                    <span>20°C (Cool)</span>
                                                    <span>35°C (Warm)</span>
                                                    <span>50°C (Extreme)</span>
                                                </div>
                                            </div>

                                            <!-- Relative Humidity Input -->
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <label class="form-label fw-bold small text-muted mb-0">Relative Humidity (%) <span class="text-danger">*</span></label>
                                                    <span class="badge bg-light text-dark border fw-bold fs-6" id="hiHumidityDisplay">65 %</span>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="range" class="form-range flex-grow-1" id="hiHumidityRange" min="10" max="100" step="1" value="65" oninput="syncHumidity(this.value)">
                                                    <input type="number" class="form-control form-control-sm text-center" id="hiHumidityNumber" min="10" max="100" step="1" value="65" style="width: 75px;" oninput="syncHumidity(this.value)">
                                                </div>
                                                <div class="d-flex justify-content-between text-muted" style="font-size: 0.72rem;">
                                                    <span>10% (Dry)</span>
                                                    <span>60% (Moderate)</span>
                                                    <span>100% (Saturated)</span>
                                                </div>
                                            </div>

                                            <!-- Recommended Action Taken -->
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted">School Protocol / Mitigation Taken</label>
                                                <select id="hi_action_taken" class="form-select">
                                                    <option value="Normal Classes with Frequent Hydration">Normal In-Person Classes with Frequent Hydration</option>
                                                    <option value="Suspended Outdoor P.E. and Playground Activities">Suspended Outdoor P.E. & Playground Activities</option>
                                                    <option value="Shortened Class Hours / Early Dismissal">Shortened Class Hours / Early Dismissal</option>
                                                    <option value="Shifted to Modular Distance Learning (MDL) / ADM">Shifted to Modular Distance Learning (MDL) / ADM</option>
                                                    <option value="Full In-Person Suspension per DepEd Advisory">Full In-Person Suspension per DepEd Advisory</option>
                                                </select>
                                            </div>

                                            <!-- Remarks -->
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted">Classroom Environment & Ventilation Remarks</label>
                                                <textarea id="hi_remarks" class="form-control" rows="2" placeholder="e.g. Electric fans operating, hydration points inspected, afternoon classrooms monitored."></textarea>
                                            </div>

                                            <div class="d-grid mt-4">
                                                <button type="submit" id="hiSubmitBtn" class="btn text-white fw-bold py-2 shadow-sm" 
                                                        style="background: linear-gradient(135deg, #EA580C, #C2410C);"
                                                        {{ (!$isAdmin && !$assignedSchool) ? 'disabled' : '' }}>
                                                    <i class="fas fa-paper-plane me-2"></i> 
                                                    @if($isAdmin)
                                                        Save Reading to School Log
                                                    @else
                                                        Submit Heat Index Report to Division DRRM
                                                    @endif
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Live Heat Index Gauge & Advisory Card -->
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                                        <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                            <i class="fas fa-tachometer-alt text-danger"></i> Calculated Heat Index & Advisory
                                        </div>
                                        <span class="badge rounded-pill text-uppercase" id="hiCategoryBadge" style="background-color: #EF4444; color: white;">
                                            Danger
                                        </span>
                                    </div>
                                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                                        
                                        <!-- Main Heat Index Meter -->
                                        <div class="text-center py-3 rounded-4 mb-3" id="hiMeterContainer" style="background: #FFF7ED; border: 2px dashed #FDBA74;">
                                            <div class="text-muted small text-uppercase fw-bold letter-spacing-1 mb-1">Estimated Heat Index (Apparent Temp)</div>
                                            <div class="display-3 fw-bolder mb-0" id="hiCalculatedValue" style="color: #EA580C; font-family: var(--font-display, 'Sora', sans-serif);">
                                                44°C
                                            </div>
                                            <div class="h5 fw-bold mt-1 mb-0" id="hiCategoryTitle" style="color: #C2410C;">
                                                Danger (42°C - 51°C)
                                            </div>
                                        </div>

                                        <!-- Multi-level scale visual -->
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-muted d-block">PAGASA Danger Scale Meter</label>
                                            <div class="progress" style="height: 12px; border-radius: 6px;">
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: 25%;" title="Caution (27-32°C)"></div>
                                                <div class="progress-bar" role="progressbar" style="width: 25%; background-color: #F97316;" title="Extreme Caution (33-41°C)"></div>
                                                <div class="progress-bar" role="progressbar" style="width: 25%; background-color: #EF4444;" title="Danger (42-51°C)"></div>
                                                <div class="progress-bar" role="progressbar" style="width: 25%; background-color: #7F1D1D;" title="Extreme Danger (52°C+)"></div>
                                            </div>
                                            <div class="d-flex justify-content-between text-muted mt-1" style="font-size: 0.7rem;">
                                                <span>27°C (Caution)</span>
                                                <span>33°C</span>
                                                <span>42°C (Danger)</span>
                                                <span>52°C+ (Extreme)</span>
                                            </div>
                                        </div>

                                        <!-- Health Effects Box -->
                                        <div class="alert alert-light border rounded-3 p-3 mb-3" id="hiHealthAlert">
                                            <div class="d-flex align-items-start gap-2">
                                                <i class="fas fa-heartbeat text-danger mt-1 fs-5"></i>
                                                <div>
                                                    <div class="fw-bold small text-dark mb-1">Health Risks & Physical Effects:</div>
                                                    <div class="small text-muted" id="hiHealthText">
                                                        Heat cramps and heat exhaustion are likely. Continued physical activity could lead to heat stroke.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- DepEd Official Order Box -->
                                        <div class="p-3 rounded-3 mb-3" id="hiDepedPolicyBox" style="background-color: #FEF2F2; border-left: 4px solid #DC2626;">
                                            <div class="d-flex align-items-start gap-2">
                                                <i class="fas fa-exclamation-triangle text-danger mt-1"></i>
                                                <div>
                                                    <div class="fw-bold small text-danger">DepEd Policy Recommendation:</div>
                                                    <div class="small text-dark mt-1" id="hiDepedText">
                                                        School Heads have the authority and responsibility to cancel or suspend in-person classes and implement Modular Distance Learning (MDL) when heat index reaches <strong>42°C (Danger)</strong> or higher.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Safety Reminders List -->
                                        <div class="row g-2 text-center text-muted" style="font-size: 0.78rem;">
                                            <div class="col-4">
                                                <div class="p-2 border rounded-2 bg-light">
                                                    <i class="fas fa-tint text-primary mb-1 d-block fs-6"></i>
                                                    Hydrate Regularly
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="p-2 border rounded-2 bg-light">
                                                    <i class="fas fa-umbrella text-warning mb-1 d-block fs-6"></i>
                                                    Stay in the Shade
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="p-2 border rounded-2 bg-light">
                                                    <i class="fas fa-fan text-info mb-1 d-block fs-6"></i>
                                                    Ensure Airflow
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Non-Admin: My School Recent Submissions Card -->
                        @if(!$isAdmin && $assignedSchool)
                            <div class="card border-0 shadow-sm rounded-3 bg-white mt-4">
                                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                                    <div class="fw-bold text-dark">
                                        <i class="fas fa-history text-primary me-2"></i> My School's Submitted Reports ({{ $assignedSchoolName }})
                                    </div>
                                    <span class="badge bg-light text-dark border">
                                        {{ count($initialSubmissions) }} Submission(s)
                                    </span>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light small text-uppercase text-muted">
                                            <tr>
                                                <th>Date & Time</th>
                                                <th>Temp / Humidity</th>
                                                <th>Heat Index</th>
                                                <th>Status</th>
                                                <th>Mitigation Taken</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="hiSchoolUserSubmissionsTbody" class="small">
                                            @forelse($initialSubmissions as $sub)
                                                <tr>
                                                    <td>{{ $sub->report_date->format('M d, Y') }} {{ $sub->report_time }}</td>
                                                    <td>{{ $sub->temperature }}°C / {{ $sub->humidity }}%</td>
                                                    <td class="fw-bold" style="color: {{ $sub->badge_color }};">{{ $sub->heat_index }}°C</td>
                                                    <td>
                                                        <span class="badge text-white" style="background-color: {{ $sub->badge_color }};">
                                                            {{ $sub->category }}
                                                        </span>
                                                    </td>
                                                    <td><small class="text-secondary">{{ $sub->action_taken }}</small></td>
                                                    <td><small class="text-muted">{{ $sub->remarks ?: '-' }}</small></td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4 text-muted">
                                                        No reports submitted yet for this school. Fill out the form above to submit your first reading.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                    </div>

                    @if($isAdmin)
                    <!-- ADMIN TAB 2: All School Head Submissions (NEW SECTION) -->
                    <div class="tab-pane fade" id="hi-submissions-pane" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-3 bg-white">
                            
                            <!-- Submissions Header & Summary Stats -->
                            <div class="card-header bg-white border-bottom p-4">
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                                    <div>
                                        <h5 class="fw-bold text-dark mb-1">
                                            <i class="fas fa-inbox text-danger me-2"></i> All School Head Submissions
                                        </h5>
                                        <p class="text-muted small mb-0">
                                            Real-time submissions and heat index monitoring reports filed by school heads across the division.
                                        </p>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" onclick="loadSubmissionsFromBackend()">
                                            <i class="fas fa-sync-alt me-1"></i> Refresh
                                        </button>
                                        <button type="button" class="btn btn-dark btn-sm px-3" onclick="printSchoolHeadSubmissionsCompilation()">
                                            <i class="fas fa-print me-1"></i> Print Division Summary
                                        </button>
                                    </div>
                                </div>

                                <!-- Summary Stats Counter Cards -->
                                <div class="row g-3">
                                    <div class="col-sm-6 col-xl-3">
                                        <div class="p-3 rounded-3 bg-light border">
                                            <div class="text-muted small text-uppercase fw-bold">Total Submissions</div>
                                            <div class="h4 fw-bold text-dark mb-0 mt-1" id="stat_total_submissions">
                                                {{ count($initialSubmissions) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xl-3">
                                        <div class="p-3 rounded-3 border" style="background-color: #FEF2F2; border-color: #FCA5A5 !important;">
                                            <div class="text-danger small text-uppercase fw-bold">High Danger (≥42°C)</div>
                                            <div class="h4 fw-bold text-danger mb-0 mt-1" id="stat_danger_submissions">
                                                {{ $initialSubmissions->where('heat_index', '>=', 42)->count() }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xl-3">
                                        <div class="p-3 rounded-3 border" style="background-color: #FFF7ED; border-color: #FDBA74 !important;">
                                            <div class="text-warning small text-uppercase fw-bold" style="color: #EA580C !important;">Modular Classes (MDL)</div>
                                            <div class="h4 fw-bold mb-0 mt-1" id="stat_mdl_submissions" style="color: #EA580C;">
                                                {{ $initialSubmissions->filter(fn($s) => str_contains(strtolower($s->action_taken ?? ''), 'modular') || str_contains(strtolower($s->action_taken ?? ''), 'suspension'))->count() }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xl-3">
                                        <div class="p-3 rounded-3 bg-light border">
                                            <div class="text-muted small text-uppercase fw-bold">Reporting Campuses</div>
                                            <div class="h4 fw-bold text-primary mb-0 mt-1" id="stat_schools_reported">
                                                {{ $initialSubmissions->pluck('school_id')->filter()->unique()->count() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Filters -->
                                <div class="row g-2 mt-2 pt-2 border-top">
                                    <div class="col-md-5">
                                        <input type="search" id="hiAdminSearchInput" class="form-control form-control-sm" 
                                               placeholder="Search by school head, school name, or remarks..." 
                                               oninput="filterAdminSubmissions(this.value)">
                                    </div>
                                    <div class="col-md-4">
                                        <select id="hiAdminCategoryFilter" class="form-select form-select-sm" onchange="filterAdminSubmissions()">
                                            <option value="">All Danger Categories</option>
                                            <option value="Danger">Danger (42°C – 51°C)</option>
                                            <option value="Extreme Caution">Extreme Caution (33°C – 41°C)</option>
                                            <option value="Caution">Caution (27°C – 32°C)</option>
                                            <option value="Extreme Danger">Extreme Danger (≥52°C)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <span class="text-muted small" id="hiAdminFilteredCountText">
                                            Showing {{ count($initialSubmissions) }} submissions
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Submissions Table -->
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="hiAdminSubmissionsTable">
                                    <thead class="table-light small text-uppercase text-muted">
                                        <tr>
                                            <th>School Campus</th>
                                            <th>School Head / Submitter</th>
                                            <th>Date & Time</th>
                                            <th>Temp / Humidity</th>
                                            <th>Heat Index</th>
                                            <th>Classification</th>
                                            <th>Action Taken</th>
                                            <th>Remarks</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="hiAdminSubmissionsTbody" class="small">
                                        @forelse($initialSubmissions as $sub)
                                            <tr data-sub-id="{{ $sub->id }}" data-school="{{ strtolower($sub->school_name) }}" data-head="{{ strtolower($sub->school_head ?? '') }}" data-cat="{{ $sub->category }}">
                                                <td>
                                                    <div class="fw-bold text-dark">{{ $sub->school_name }}</div>
                                                    <div class="text-muted" style="font-size: 0.75rem;">
                                                        ID: {{ $sub->school?->school_id ?: ($sub->school?->school_id_number ?: 'N/A') }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold text-dark">
                                                        <i class="fas fa-user-circle text-secondary me-1"></i>
                                                        {{ $sub->school_head ?: 'School Head' }}
                                                    </div>
                                                    @if($sub->submitted_by && $sub->submitted_by !== $sub->school_head)
                                                        <div class="text-muted" style="font-size: 0.72rem;">By: {{ $sub->submitted_by }}</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div><i class="far fa-calendar-alt text-muted me-1"></i> {{ $sub->report_date->format('M d, Y') }}</div>
                                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $sub->report_time }}</div>
                                                </td>
                                                <td>
                                                    <div>{{ $sub->temperature }}°C</div>
                                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $sub->humidity }}% Hum.</div>
                                                </td>
                                                <td class="fw-bold" style="color: {{ $sub->badge_color }};">
                                                    {{ $sub->heat_index }}°C
                                                </td>
                                                <td>
                                                    <span class="badge text-white" style="background-color: {{ $sub->badge_color }};">
                                                        {{ $sub->category }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <small class="fw-semibold {{ str_contains(strtolower($sub->action_taken ?? ''), 'modular') ? 'text-danger' : 'text-dark' }}">
                                                        {{ $sub->action_taken }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <small class="text-secondary" style="max-width: 180px; display: inline-block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $sub->remarks }}">
                                                        {{ $sub->remarks ?: '-' }}
                                                    </small>
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-flex justify-content-end gap-1">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="printSingleSubmissionAdvisory('{{ $sub->id }}')" title="Print School Advisory">
                                                            <i class="fas fa-print"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteSubmissionFromBackend('{{ $sub->id }}')" title="Delete entry">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr id="hiAdminNoSubmissionsRow">
                                                <td colspan="9" class="text-center py-5 text-muted">
                                                    <i class="fas fa-inbox fa-3x text-secondary mb-3 d-block opacity-50"></i>
                                                    No submissions from school heads recorded yet. Reports submitted by school heads will automatically populate here.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- TAB: PAGASA Scale & DepEd Protocols (Both Admin & School Head) -->
                    <div class="tab-pane fade" id="hi-standards-pane" role="tabpanel">
                        <div class="row g-3 mb-4">
                            <!-- Card 1: Caution -->
                            <div class="col-md-6 col-xl-3">
                                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                                    <div class="p-3 text-dark fw-bold" style="background: #FEF08A; border-bottom: 3px solid #EAB308;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Caution</span>
                                            <span class="badge bg-dark">27°C – 32°C</span>
                                        </div>
                                    </div>
                                    <div class="card-body p-3 small text-muted">
                                        <p class="mb-2"><strong>Physical Effects:</strong> Fatigue is possible with prolonged exposure and activity. Continuing activity could lead to heat cramps.</p>
                                        <hr class="my-2">
                                        <p class="mb-0 text-dark"><strong>DepEd Action:</strong> Normal classes. Ensure availability of safe drinking water and well-ventilated classrooms.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2: Extreme Caution -->
                            <div class="col-md-6 col-xl-3">
                                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                                    <div class="p-3 text-white fw-bold" style="background: #F97316; border-bottom: 3px solid #C2410C;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Extreme Caution</span>
                                            <span class="badge bg-white text-dark">33°C – 41°C</span>
                                        </div>
                                    </div>
                                    <div class="card-body p-3 small text-muted">
                                        <p class="mb-2"><strong>Physical Effects:</strong> Heat cramps and heat exhaustion are possible. Continued activity could lead to heat stroke.</p>
                                        <hr class="my-2">
                                        <p class="mb-0 text-dark"><strong>DepEd Action:</strong> Curtail strenuous physical education exercises and outdoor playground games between 10 AM and 3 PM.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 3: Danger -->
                            <div class="col-md-6 col-xl-3">
                                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                                    <div class="p-3 text-white fw-bold" style="background: #EF4444; border-bottom: 3px solid #991B1B;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Danger</span>
                                            <span class="badge bg-white text-danger">42°C – 51°C</span>
                                        </div>
                                    </div>
                                    <div class="card-body p-3 small text-muted">
                                        <p class="mb-2"><strong>Physical Effects:</strong> Heat cramps and heat exhaustion are likely; heat stroke is probable with continued exposure.</p>
                                        <hr class="my-2">
                                        <p class="mb-0 text-danger fw-bold"><strong>DepEd Action:</strong> School Head is authorized to suspend in-person classes and shift to Modular Distance Learning (MDL).</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 4: Extreme Danger -->
                            <div class="col-md-6 col-xl-3">
                                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                                    <div class="p-3 text-white fw-bold" style="background: #7F1D1D; border-bottom: 3px solid #450A0A;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Extreme Danger</span>
                                            <span class="badge bg-danger">≥ 52°C</span>
                                        </div>
                                    </div>
                                    <div class="card-body p-3 small text-muted">
                                        <p class="mb-2"><strong>Physical Effects:</strong> Heat stroke is imminent. High mortality risk upon sustained direct or indirect heat exposure.</p>
                                        <hr class="my-2">
                                        <p class="mb-0 text-danger fw-bold"><strong>DepEd Action:</strong> Mandatory cancellation of all school operations. Automatic shift to emergency asynchronous modalities.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Policy Framework Card -->
                        <div class="card border-0 shadow-sm rounded-3 bg-white p-4">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="fas fa-file-contract text-primary me-2"></i> DepEd Memorandum & Legal Framework
                            </h6>
                            <div class="row g-3 small text-secondary">
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-3 bg-light">
                                        <div class="fw-bold text-dark mb-1">DepEd Order No. 037, Series of 2022</div>
                                        <p class="mb-0">
                                            "Guidelines on the Cancellation or Suspension of Classes and Work in Schools in the Event of Natural Disasters, Power Outages/Power Interruptions, and Other Calamities." Explicitly authorizes school heads to suspend classes during extreme heat conditions to safeguard learner safety.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-3 bg-light">
                                        <div class="fw-bold text-dark mb-1">Alternative Delivery Mode (ADM) Implementation</div>
                                        <p class="mb-0">
                                            Whenever face-to-face classes are suspended due to hazardous heat levels, schools are directed to promptly implement modular distance learning, online classes, or home-based instructional tasks so learning competencies continue safely.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($isAdmin)
                    <!-- ADMIN TAB 4: General Logs History -->
                    <div class="tab-pane fade" id="hi-history-pane" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-3 bg-white">
                            <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                                <div class="fw-bold text-dark">
                                    <i class="fas fa-clipboard-list text-primary me-2"></i> School Heat Index Monitoring History
                                </div>
                                <div class="d-flex gap-2">
                                    <input type="search" id="hiSearchInput" class="form-control form-control-sm" placeholder="Filter by school or status..." oninput="filterHeatIndexLogs(this.value)" style="width: 220px;">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearHeatIndexLogs()" title="Clear local logs">
                                        <i class="fas fa-trash-alt"></i> Reset Local
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="hiLogsTable">
                                    <thead class="table-light small text-uppercase text-muted">
                                        <tr>
                                            <th>School Name</th>
                                            <th>Date & Time</th>
                                            <th>Temp (°C)</th>
                                            <th>Humidity (%)</th>
                                            <th>Heat Index</th>
                                            <th>Status</th>
                                            <th>Action Taken</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="hiLogsTableBody" class="small">
                                        <!-- Dynamic rows inserted by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                            <div id="hiNoLogsMessage" class="text-center py-5 text-muted d-none">
                                <i class="fas fa-temperature-empty fs-2 text-secondary mb-2 d-block"></i>
                                No entries recorded yet.
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer bg-white border-top px-4 py-3 d-flex justify-content-between">
                <div class="text-muted small">
                    <i class="fas fa-info-circle me-1 text-primary"></i> 
                    @if($isAdmin)
                        Admin mode: Viewing division-wide heat index reports and school head submissions.
                    @else
                        School Head mode: Submitting heat index report locked to <strong>{{ $assignedSchoolName ?? 'Assigned School' }}</strong>.
                    @endif
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary px-3" onclick="printHeatIndexReport()">
                        <i class="fas fa-print me-1"></i> Print Advisory
                    </button>
                    <button type="button" class="btn btn-dark px-4" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const IS_ADMIN = @json($isAdmin);
    const ASSIGNED_SCHOOL_ID = @json($assignedSchoolId);
    const ASSIGNED_SCHOOL_NAME = @json($assignedSchoolName);
    const ASSIGNED_SCHOOL_HEAD = @json($assignedSchoolHead);

    // Formula to compute Heat Index in Celsius
    function calculateHeatIndexC(T, R) {
        if (T < 26.7) {
            return Math.round(T * 10) / 10;
        }
        const c1 = -8.78469475556;
        const c2 = 1.61139411;
        const c3 = 2.33854883889;
        const c4 = -0.14611605;
        const c5 = -0.012308094;
        const c6 = -0.0164248277778;
        const c7 = 0.002211732;
        const c8 = 0.00072546;
        const c9 = -0.000003582;

        let hi = c1 + (c2 * T) + (c3 * R) + (c4 * T * R) + (c5 * T * T) + 
                 (c6 * R * R) + (c7 * T * T * R) + (c8 * T * R * R) + (c9 * T * T * R * R);

        return Math.round(hi * 10) / 10;
    }

    function syncTemp(val) {
        const floatVal = parseFloat(val) || 20;
        document.getElementById('hiTempRange').value = floatVal;
        document.getElementById('hiTempNumber').value = floatVal.toFixed(1);
        document.getElementById('hiTempDisplay').innerText = floatVal.toFixed(1) + ' °C';
        updateHeatIndexDisplay();
    }

    function syncHumidity(val) {
        const intVal = parseInt(val) || 10;
        document.getElementById('hiHumidityRange').value = intVal;
        document.getElementById('hiHumidityNumber').value = intVal;
        document.getElementById('hiHumidityDisplay').innerText = intVal + ' %';
        updateHeatIndexDisplay();
    }

    function updateHeatIndexDisplay() {
        const T = parseFloat(document.getElementById('hiTempNumber').value) || 33;
        const R = parseFloat(document.getElementById('hiHumidityNumber').value) || 65;
        const hi = calculateHeatIndexC(T, R);

        const valEl = document.getElementById('hiCalculatedValue');
        const catBadge = document.getElementById('hiCategoryBadge');
        const catTitle = document.getElementById('hiCategoryTitle');
        const healthText = document.getElementById('hiHealthText');
        const depedText = document.getElementById('hiDepedText');
        const actionSelect = document.getElementById('hi_action_taken');
        const container = document.getElementById('hiMeterContainer');

        if (!valEl) return;
        valEl.innerText = Math.round(hi) + '°C';

        if (hi < 33) {
            catBadge.style.backgroundColor = '#EAB308';
            catBadge.innerText = 'Caution';
            catTitle.style.color = '#CA8A04';
            catTitle.innerText = 'Caution (27°C - 32°C)';
            valEl.style.color = '#CA8A04';
            container.style.background = '#FEFCE8';
            container.style.borderColor = '#FEF08A';
            healthText.innerText = 'Fatigue is possible with prolonged exposure and activity. Continuing activity could lead to heat cramps.';
            depedText.innerHTML = 'Normal classes proceed. Ensure functional electric fans and regular hydration breaks for students.';
            if (actionSelect) actionSelect.value = 'Normal Classes with Frequent Hydration';
        } else if (hi < 42) {
            catBadge.style.backgroundColor = '#F97316';
            catBadge.innerText = 'Extreme Caution';
            catTitle.style.color = '#EA580C';
            catTitle.innerText = 'Extreme Caution (33°C - 41°C)';
            valEl.style.color = '#EA580C';
            container.style.background = '#FFF7ED';
            container.style.borderColor = '#FDBA74';
            healthText.innerText = 'Heat cramps and heat exhaustion are possible. Continued activity could lead to heat stroke.';
            depedText.innerHTML = 'Limit strenuous physical education (P.E.) exercises and outdoor activities between 10:00 AM and 3:00 PM.';
            if (actionSelect) actionSelect.value = 'Suspended Outdoor P.E. and Playground Activities';
        } else if (hi < 52) {
            catBadge.style.backgroundColor = '#EF4444';
            catBadge.innerText = 'Danger';
            catTitle.style.color = '#DC2626';
            catTitle.innerText = 'Danger (42°C - 51°C)';
            valEl.style.color = '#DC2626';
            container.style.background = '#FEF2F2';
            container.style.borderColor = '#FCA5A5';
            healthText.innerText = 'Heat cramps and heat exhaustion are likely; heat stroke is probable with continued exposure.';
            depedText.innerHTML = '<strong>DepEd Policy:</strong> School Heads are authorized to suspend in-person classes and implement Modular Distance Learning (MDL) per DO 037, s. 2022.';
            if (actionSelect) actionSelect.value = 'Shifted to Modular Distance Learning (MDL) / ADM';
        } else {
            catBadge.style.backgroundColor = '#7F1D1D';
            catBadge.innerText = 'Extreme Danger';
            catTitle.style.color = '#7F1D1D';
            catTitle.innerText = 'Extreme Danger (≥ 52°C)';
            valEl.style.color = '#7F1D1D';
            container.style.background = '#450A0A';
            container.style.borderColor = '#991B1B';
            valEl.style.color = '#FECACA';
            catTitle.style.color = '#FCA5A5';
            healthText.innerText = 'Heat stroke is imminent! Sustained exposure is critical and hazardous to life.';
            depedText.innerHTML = '<strong>CRITICAL ALERT:</strong> Immediate suspension of all in-person school operations. Implement remote emergency learning.';
            if (actionSelect) actionSelect.value = 'Full In-Person Suspension per DepEd Advisory';
        }
    }

    function onAdminSchoolSelectChange(select) {
        const headInput = document.getElementById('hi_school_head');
        if (!headInput) return;
        const opt = select.options[select.selectedIndex];
        if (opt && opt.getAttribute('data-head')) {
            headInput.value = opt.getAttribute('data-head');
        }
    }

    // Handle Heat Index Submission
    async function handleHeatIndexSubmit(e) {
        e.preventDefault();
        
        let schoolId = null;
        let schoolName = '';
        let schoolHead = '';

        if (IS_ADMIN) {
            const select = document.getElementById('hi_school_select');
            if (!select || !select.value) {
                Swal.fire('School Required', 'Please select a school to record this reading.', 'warning');
                return;
            }
            const selectedOpt = select.options[select.selectedIndex];
            schoolId = select.value;
            schoolName = selectedOpt.getAttribute('data-name') || selectedOpt.text;
            schoolHead = document.getElementById('hi_school_head').value || selectedOpt.getAttribute('data-head') || 'School Head';
        } else {
            // For non-admin: locked to assigned school
            if (!ASSIGNED_SCHOOL_ID) {
                Swal.fire('No Assigned School', 'Your account does not have an assigned school. Submissions are restricted to assigned school heads.', 'error');
                return;
            }
            schoolId = ASSIGNED_SCHOOL_ID;
            schoolName = ASSIGNED_SCHOOL_NAME;
            schoolHead = document.getElementById('hi_school_head').value || ASSIGNED_SCHOOL_HEAD;
        }

        const date = document.getElementById('hi_log_date').value;
        const time = document.getElementById('hi_log_time').value;
        const temp = parseFloat(document.getElementById('hiTempNumber').value) || 33;
        const humidity = parseFloat(document.getElementById('hiHumidityNumber').value) || 65;
        const hi = calculateHeatIndexC(temp, humidity);

        let category = 'Caution';
        let badgeColor = '#EAB308';
        if (hi >= 52) {
            category = 'Extreme Danger';
            badgeColor = '#7F1D1D';
        } else if (hi >= 42) {
            category = 'Danger';
            badgeColor = '#EF4444';
        } else if (hi >= 33) {
            category = 'Extreme Caution';
            badgeColor = '#F97316';
        }

        const action = document.getElementById('hi_action_taken').value;
        const remarks = document.getElementById('hi_remarks').value;

        const submitBtn = document.getElementById('hiSubmitBtn');
        const origBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Submitting...';

        try {
            // Send to Backend
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            const resp = await fetch('/heat-index/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    school_id: schoolId,
                    school_head: schoolHead,
                    report_date: date,
                    report_time: time,
                    temperature: temp,
                    humidity: humidity,
                    heat_index: Math.round(hi),
                    category: category,
                    badge_color: badgeColor,
                    action_taken: action,
                    remarks: remarks
                })
            });

            const resData = await resp.json();

            if (resp.ok && resData.success) {
                // Also update local storage for redundancy
                saveToLocalStorageLog({
                    id: resData.data?.id || ('log-' + Date.now()),
                    school_name: schoolName,
                    school_head: schoolHead,
                    date: date,
                    time: time,
                    temp: temp,
                    humidity: humidity,
                    heat_index: Math.round(hi),
                    category: category,
                    badge_color: badgeColor,
                    action: action,
                    remarks: remarks
                });

                Swal.fire({
                    title: IS_ADMIN ? 'Reading Recorded!' : 'Report Submitted to Division DRRM!',
                    html: `Recorded <strong>${Math.round(hi)}°C (${category})</strong> for <strong>${escapeHtml(schoolName)}</strong>.`,
                    icon: hi >= 42 ? 'warning' : 'success',
                    confirmButtonColor: '#EA580C'
                });

                // Reset remarks
                document.getElementById('hi_remarks').value = '';

                // Reload admin view if admin
                if (IS_ADMIN) {
                    loadSubmissionsFromBackend();
                } else {
                    // Update user's table
                    prependSchoolUserTableRow(resData.data);
                }
            } else {
                Swal.fire('Submission Failed', resData.message || 'Please check your inputs and try again.', 'error');
            }
        } catch(err) {
            console.error('Heat index submit error:', err);
            // Fallback to local storage if offline
            saveToLocalStorageLog({
                id: 'local-' + Date.now(),
                school_name: schoolName,
                school_head: schoolHead,
                date: date,
                time: time,
                temp: temp,
                humidity: humidity,
                heat_index: Math.round(hi),
                category: category,
                badge_color: badgeColor,
                action: action,
                remarks: remarks
            });
            Swal.fire('Saved Locally', 'Saved report locally. Server may be unreachable.', 'info');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = origBtnText;
        }
    }

    function prependSchoolUserTableRow(data) {
        const tbody = document.getElementById('hiSchoolUserSubmissionsTbody');
        if (!tbody || !data) return;
        const row = `
            <tr class="table-warning">
                <td>${escapeHtml(data.report_date)} ${escapeHtml(data.report_time)}</td>
                <td>${data.temperature}°C / ${data.humidity}%</td>
                <td class="fw-bold" style="color: ${data.badge_color};">${data.heat_index}°C</td>
                <td><span class="badge text-white" style="background-color: ${data.badge_color};">${escapeHtml(data.category)}</span></td>
                <td><small class="text-secondary">${escapeHtml(data.action_taken)}</small></td>
                <td><small class="text-muted">${escapeHtml(data.remarks || '-')}</small></td>
            </tr>
        `;
        tbody.insertAdjacentHTML('afterbegin', row);
    }

    // Backend Submissions Loader (Admin)
    async function loadSubmissionsFromBackend() {
        if (!IS_ADMIN) return;
        try {
            const resp = await fetch('/heat-index/submissions', {
                headers: { 'Accept': 'application/json' }
            });
            const data = await resp.json();
            if (data.success && Array.isArray(data.data)) {
                renderAdminSubmissionsTable(data.data);
            }
        } catch (e) {
            console.warn('Failed to fetch submissions:', e);
        }
    }

    function renderAdminSubmissionsTable(items) {
        const tbody = document.getElementById('hiAdminSubmissionsTbody');
        const badge = document.getElementById('hiAdminSubmissionsCountBadge');
        const statTotal = document.getElementById('stat_total_submissions');
        const statDanger = document.getElementById('stat_danger_submissions');
        const statMdl = document.getElementById('stat_mdl_submissions');
        const statSchools = document.getElementById('stat_schools_reported');

        if (!tbody) return;

        if (badge) badge.innerText = items.length;
        if (statTotal) statTotal.innerText = items.length;
        
        const dangerCount = items.filter(s => (s.heat_index >= 42)).length;
        if (statDanger) statDanger.innerText = dangerCount;

        const mdlCount = items.filter(s => {
            const act = (s.action_taken || '').toLowerCase();
            return act.includes('modular') || act.includes('suspension');
        }).length;
        if (statMdl) statMdl.innerText = mdlCount;

        const uniqueSchools = new Set(items.map(s => s.school_id).filter(Boolean));
        if (statSchools) statSchools.innerText = uniqueSchools.size;

        if (items.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x text-secondary mb-3 d-block opacity-50"></i>
                        No submissions from school heads recorded yet.
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = items.map(sub => {
            const dateStr = sub.report_date ? new Date(sub.report_date).toLocaleDateString('en-PH', { month: 'short', day: 'numeric', year: 'numeric' }) : '';
            return `
                <tr data-sub-id="${sub.id}" data-school="${(sub.school_name || '').toLowerCase()}" data-head="${(sub.school_head || '').toLowerCase()}" data-cat="${sub.category}">
                    <td>
                        <div class="fw-bold text-dark">${escapeHtml(sub.school_name)}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">ID: ${escapeHtml(sub.school?.school_id || 'N/A')}</div>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">
                            <i class="fas fa-user-circle text-secondary me-1"></i>
                            ${escapeHtml(sub.school_head || 'School Head')}
                        </div>
                        ${sub.submitted_by && sub.submitted_by !== sub.school_head ? `<div class="text-muted" style="font-size: 0.72rem;">By: ${escapeHtml(sub.submitted_by)}</div>` : ''}
                    </td>
                    <td>
                        <div><i class="far fa-calendar-alt text-muted me-1"></i> ${escapeHtml(dateStr)}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">${escapeHtml(sub.report_time || '')}</div>
                    </td>
                    <td>
                        <div>${sub.temperature}°C</div>
                        <div class="text-muted" style="font-size: 0.75rem;">${sub.humidity}% Hum.</div>
                    </td>
                    <td class="fw-bold" style="color: ${sub.badge_color};">${sub.heat_index}°C</td>
                    <td>
                        <span class="badge text-white" style="background-color: ${sub.badge_color};">
                            ${escapeHtml(sub.category)}
                        </span>
                    </td>
                    <td>
                        <small class="fw-semibold ${(sub.action_taken || '').toLowerCase().includes('modular') ? 'text-danger' : 'text-dark'}">
                            ${escapeHtml(sub.action_taken || '')}
                        </small>
                    </td>
                    <td>
                        <small class="text-secondary" style="max-width: 180px; display: inline-block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${escapeHtml(sub.remarks || '')}">
                            ${escapeHtml(sub.remarks || '-')}
                        </small>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="printSingleSubmissionAdvisory('${sub.id}')" title="Print School Advisory">
                                <i class="fas fa-print"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteSubmissionFromBackend('${sub.id}')" title="Delete entry">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function filterAdminSubmissions(query = '') {
        const searchVal = (query || document.getElementById('hiAdminSearchInput')?.value || '').toLowerCase();
        const catVal = document.getElementById('hiAdminCategoryFilter')?.value || '';
        const rows = document.querySelectorAll('#hiAdminSubmissionsTbody tr[data-sub-id]');

        let visibleCount = 0;
        rows.forEach(r => {
            const school = r.getAttribute('data-school') || '';
            const head = r.getAttribute('data-head') || '';
            const cat = r.getAttribute('data-cat') || '';
            const textContent = r.innerText.toLowerCase();

            const matchesSearch = !searchVal || school.includes(searchVal) || head.includes(searchVal) || textContent.includes(searchVal);
            const matchesCat = !catVal || cat === catVal;

            if (matchesSearch && matchesCat) {
                r.style.display = '';
                visibleCount++;
            } else {
                r.style.display = 'none';
            }
        });

        const countText = document.getElementById('hiAdminFilteredCountText');
        if (countText) {
            countText.innerText = `Showing ${visibleCount} submission(s)`;
        }
    }

    async function deleteSubmissionFromBackend(id) {
        const confirm = await Swal.fire({
            title: 'Delete this report?',
            text: 'This will remove the heat index report submission from the database.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it'
        });

        if (!confirm.isConfirmed) return;

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            const resp = await fetch(`/heat-index/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            const res = await resp.json();
            if (resp.ok && res.success) {
                Swal.fire('Deleted', res.message, 'success');
                loadSubmissionsFromBackend();
            } else {
                Swal.fire('Error', res.message || 'Failed to delete report.', 'error');
            }
        } catch(e) {
            console.error(e);
            Swal.fire('Error', 'Failed to communicate with server.', 'error');
        }
    }

    // Local Storage helpers
    const HI_STORAGE_KEY = 'drrm_heat_index_logs_v1';

    function getLocalStorageLogs() {
        try {
            const raw = localStorage.getItem(HI_STORAGE_KEY);
            if (raw) return JSON.parse(raw);
        } catch(e) {}
        return [];
    }

    function saveToLocalStorageLog(logItem) {
        const logs = getLocalStorageLogs();
        logs.unshift(logItem);
        try {
            localStorage.setItem(HI_STORAGE_KEY, JSON.stringify(logs));
        } catch(e) {}
        renderHeatIndexLogs();
    }

    function renderHeatIndexLogs(filterText = '') {
        if (!IS_ADMIN) return;
        const logs = getLocalStorageLogs();
        const tbody = document.getElementById('hiLogsTableBody');
        const emptyMsg = document.getElementById('hiNoLogsMessage');
        if (!tbody) return;

        const filtered = logs.filter(item => {
            if (!filterText) return true;
            const query = filterText.toLowerCase();
            return (item.school_name || '').toLowerCase().includes(query) ||
                   (item.category || '').toLowerCase().includes(query) ||
                   (item.action || '').toLowerCase().includes(query);
        });

        if (filtered.length === 0) {
            tbody.innerHTML = '';
            if (emptyMsg) emptyMsg.classList.remove('d-none');
            return;
        }

        if (emptyMsg) emptyMsg.classList.add('d-none');

        tbody.innerHTML = filtered.map(item => `
            <tr>
                <td class="fw-bold text-dark">${escapeHtml(item.school_name)}</td>
                <td><i class="far fa-calendar-alt text-muted me-1"></i> ${escapeHtml(item.date)} <span class="text-muted">${escapeHtml(item.time)}</span></td>
                <td>${item.temp} °C</td>
                <td>${item.humidity} %</td>
                <td class="fw-bold" style="color: ${item.badge_color};">${item.heat_index} °C</td>
                <td>
                    <span class="badge text-white" style="background-color: ${item.badge_color};">
                        ${escapeHtml(item.category)}
                    </span>
                </td>
                <td><small class="text-secondary">${escapeHtml(item.action)}</small></td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="deleteHeatIndexLocalLog('${item.id}')" title="Delete entry">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    function deleteHeatIndexLocalLog(id) {
        let logs = getLocalStorageLogs();
        logs = logs.filter(l => l.id !== id);
        try {
            localStorage.setItem(HI_STORAGE_KEY, JSON.stringify(logs));
        } catch(e) {}
        renderHeatIndexLogs();
    }

    function clearHeatIndexLogs() {
        localStorage.removeItem(HI_STORAGE_KEY);
        renderHeatIndexLogs();
    }

    function filterHeatIndexLogs(q) {
        renderHeatIndexLogs(q);
    }

    // Printable functions
    function printHeatIndexReport() {
        const dateStr = new Date().toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' });
        const schoolName = IS_ADMIN ? 'All Division Schools' : (ASSIGNED_SCHOOL_NAME || 'School Campus');
        const temp = document.getElementById('hiTempNumber').value || '33';
        const humidity = document.getElementById('hiHumidityNumber').value || '65';
        const hi = document.getElementById('hiCalculatedValue').innerText || '44°C';
        const cat = document.getElementById('hiCategoryBadge').innerText || 'Danger';
        const action = document.getElementById('hi_action_taken').value;
        const remarks = document.getElementById('hi_remarks').value || 'Standard hydration and ventilation measures observing DepEd DO 037, s. 2022.';

        let printWindow = window.open('', '_blank');
        if (!printWindow) return;

        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>School Heat Index Advisory - ${dateStr}</title>
                <style>
                    body { font-family: 'Segoe UI', Arial, sans-serif; padding: 40px; color: #1e293b; line-height: 1.5; }
                    .header { text-align: center; border-bottom: 2px solid #EA580C; padding-bottom: 15px; margin-bottom: 25px; }
                    .header h2 { margin: 0; color: #0D1B36; }
                    .header p { margin: 4px 0 0; color: #64748B; font-size: 13px; }
                    .card { border: 1px solid #cbd5e1; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
                    .grid { display: flex; gap: 20px; }
                    .grid > div { flex: 1; }
                    .metric { text-align: center; background: #fff7ed; padding: 15px; border-radius: 8px; border: 1px solid #fdba74; }
                    .metric-val { font-size: 38px; font-weight: bold; color: #ea580c; }
                    .footer { margin-top: 50px; display: flex; justify-content: space-between; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>DEPARTMENT OF EDUCATION • DIVISION DRRM OFFICE</h2>
                    <p>School Heat Index Monitoring & Health Safety Advisory • DepEd Order No. 037, s. 2022</p>
                    <p>Date Generated: <strong>${dateStr}</strong></p>
                </div>
                <div class="card">
                    <h3 style="margin-top:0; color:#0D1B36;">School Campus: ${escapeHtml(schoolName)}</h3>
                    <div class="grid">
                        <div class="metric">
                            <div>Apparent Heat Index</div>
                            <div class="metric-val">${escapeHtml(hi)}</div>
                            <div style="font-weight:bold; color:#dc2626;">Classification: ${escapeHtml(cat)}</div>
                        </div>
                        <div>
                            <p><strong>Ambient Air Temp:</strong> ${escapeHtml(temp)} °C</p>
                            <p><strong>Relative Humidity:</strong> ${escapeHtml(humidity)} %</p>
                            <p><strong>Mitigation Action Taken:</strong> ${escapeHtml(action)}</p>
                            <p><strong>Observations / Remarks:</strong> ${escapeHtml(remarks)}</p>
                        </div>
                    </div>
                </div>
                <div class="footer">
                    <div>
                        <p>Reported by:<br><br>____________________________________<br><strong>${escapeHtml(ASSIGNED_SCHOOL_HEAD || 'School Head')}</strong><br>School Head / DRRM Focal</p>
                    </div>
                    <div>
                        <p>Noted by:<br><br>____________________________________<br><strong>Division DRRM Coordinator</strong><br>Schools Division Office</p>
                    </div>
                </div>
                <script>window.onload = function() { window.print(); }<\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }

    function printSchoolHeadSubmissionsCompilation() {
        const rows = document.querySelectorAll('#hiAdminSubmissionsTbody tr[data-sub-id]');
        const dateStr = new Date().toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' });

        let printWindow = window.open('', '_blank');
        if (!printWindow) return;

        let rowsHtml = '';
        rows.forEach(r => {
            if (r.style.display !== 'none') {
                rowsHtml += `<tr>${r.innerHTML}</tr>`;
            }
        });

        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Division Summary - School Head Heat Index Submissions - ${dateStr}</title>
                <style>
                    body { font-family: 'Segoe UI', Arial, sans-serif; padding: 30px; font-size: 12px; }
                    .header { text-align: center; border-bottom: 2px solid #EA580C; padding-bottom: 12px; margin-bottom: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                    th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
                    th { background: #f8fafc; font-weight: bold; }
                    th:last-child, td:last-child { display: none; } /* Hide action buttons */
                </style>
            </head>
            <body>
                <div class="header">
                    <h2 style="margin:0;">DIVISION OF SCHOOLS — HEAT INDEX SUBMISSIONS COMPILATION</h2>
                    <p style="margin:5px 0 0;">School Head Submissions & Heat Advisory Logs • DepEd Order No. 037, s. 2022</p>
                    <p style="margin:2px 0 0; color:#666;">Generated on: ${dateStr}</p>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>School Campus</th>
                            <th>School Head / Submitter</th>
                            <th>Date & Time</th>
                            <th>Temp / Humidity</th>
                            <th>Heat Index</th>
                            <th>Classification</th>
                            <th>Action Taken</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>${rowsHtml}</tbody>
                </table>
                <script>window.onload = function() { window.print(); }<\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }

    function printSingleSubmissionAdvisory(id) {
        const row = document.querySelector(`tr[data-sub-id="${id}"]`);
        if (!row) return;
        const school = row.querySelector('td:nth-child(1) .fw-bold')?.innerText || 'School';
        const head = row.querySelector('td:nth-child(2) .fw-semibold')?.innerText || 'School Head';
        const date = row.querySelector('td:nth-child(3)')?.innerText || '';
        const temp = row.querySelector('td:nth-child(4)')?.innerText || '';
        const hi = row.querySelector('td:nth-child(5)')?.innerText || '';
        const cat = row.querySelector('td:nth-child(6)')?.innerText || '';
        const action = row.querySelector('td:nth-child(7)')?.innerText || '';
        const remarks = row.querySelector('td:nth-child(8)')?.innerText || '';

        let printWindow = window.open('', '_blank');
        if (!printWindow) return;

        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Official Heat Index Report - ${school}</title>
                <style>
                    body { font-family: 'Segoe UI', Arial, sans-serif; padding: 40px; color: #1e293b; line-height: 1.6; }
                    .header { text-align: center; border-bottom: 2px solid #EA580C; padding-bottom: 15px; margin-bottom: 25px; }
                    .header h2 { margin: 0; color: #0D1B36; }
                    .box { border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; background: #fafafa; margin-bottom: 20px; }
                    .hi-badge { font-size: 28px; font-weight: bold; color: #ea580c; }
                    .footer { margin-top: 50px; display: flex; justify-content: space-between; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>DEPARTMENT OF EDUCATION • DIVISION DRRM OFFICE</h2>
                    <p>Official School Head Heat Index Advisory & Incident Record</p>
                </div>
                <div class="box">
                    <h3 style="margin-top:0;">${escapeHtml(school)}</h3>
                    <p><strong>Submitted by:</strong> ${escapeHtml(head)}</p>
                    <p><strong>Date & Time of Reading:</strong> ${escapeHtml(date)}</p>
                    <p><strong>Ambient Conditions:</strong> ${escapeHtml(temp)}</p>
                    <p><strong>Calculated Apparent Heat Index:</strong> <span class="hi-badge">${escapeHtml(hi)}</span> (${escapeHtml(cat)})</p>
                    <p><strong>School Action / Mitigation Taken:</strong> ${escapeHtml(action)}</p>
                    <p><strong>Observations / Remarks:</strong> ${escapeHtml(remarks)}</p>
                </div>
                <div class="footer">
                    <div><p>Reported by:<br><br>___________________________<br><strong>${escapeHtml(head)}</strong><br>School Head</p></div>
                    <div><p>Verified by:<br><br>___________________________<br><strong>DRRM Officer</strong><br>Division DRRM Office</p></div>
                </div>
                <script>window.onload = function() { window.print(); }<\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }

    // Helper for opening modal pre-selecting a specific school (Admin use)
    function openHeatIndexModalForSchool(school) {
        if (IS_ADMIN) {
            const select = document.getElementById('hi_school_select');
            if (select && school) {
                select.value = school.id;
                onAdminSchoolSelectChange(select);
            }
        }
        const modalEl = document.getElementById('heatIndexReportModal');
        if (modalEl && window.bootstrap) {
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
    }

    // Initialize on load
    document.addEventListener('DOMContentLoaded', function() {
        updateHeatIndexDisplay();
        renderHeatIndexLogs();
        if (IS_ADMIN) {
            loadSubmissionsFromBackend();
        }
    });
</script>
