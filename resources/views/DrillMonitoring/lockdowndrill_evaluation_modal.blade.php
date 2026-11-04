{{-- 
    Lockdown Drill Evaluation Modal
    Uses Bootstrap 5 classes like the supplied drill-monitoring example.
    Update the form action route and any database field names to match your Laravel application.
--}}

<form id="lockdownEvaluationForm" action="{{ route('drill-monitoring.store') }}" method="POST">
    @csrf
    <input type="hidden" name="drill_type" value="Lockdown">
    <input type="hidden" name="_method" id="lockdownFormMethod" value="POST">
    {{-- Keep this hidden field if your page has an active school --}}
    @if(isset($activeSchool))
        <input type="hidden" name="unified_school_id" value="{{ $activeSchool->id }}">
    @endif

    <div class="modal fade" id="lockdownEvaluationModal" tabindex="-1" aria-labelledby="lockdownEvaluationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <div>
                        <h5 class="modal-title mb-1" id="lockdownEvaluationModalLabel">School Lockdown Drill Evaluation</h5>
                        <small class="text-white-50">Active Threat / Active Attacker Scenario</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-info py-2 small mb-4">
                        <strong>Rating guide:</strong>
                        <span class="ms-2">2 = Satisfactory</span>
                        <span class="ms-2">1 = Needs Improvement</span>
                        <span class="ms-2">0 = Unsatisfactory</span>
                        <span class="ms-2">N/O = Not Observed / Not Applicable</span>
                        <div class="mt-1">Select one rating for each indicator. N/O items are excluded from the adjusted maximum score.</div>
                    </div>

                    {{-- I. General Information --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light fw-bold">I. General Information</div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Name of School</label>
                                    <input type="text" class="form-control border-primary" name="school_name" value="{{ $activeSchool->school_name ?? '' }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">School ID</label>
                                    <input type="text" class="form-control border-primary" name="school_id" value="{{ $activeSchool->school_id ?? '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Date</label>
                                    <input type="date" class="form-control border-primary" name="inspection_date" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Time</label>
                                    <input type="time" class="form-control border-primary" name="inspection_time" value="{{ date('H:i') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">School Head</label>
                                    <input type="text" class="form-control" name="school_head">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">School DRRM Coordinator</label>
                                    <input type="text" class="form-control" name="coordinator_name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Evaluator / Observer</label>
                                    <input type="text" class="form-control" name="monitored_by">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold d-block">Type of Drill</label>
                                    <div class="d-flex flex-wrap gap-3 pt-2">
                                        <div class="form-check"><input class="form-check-input" type="radio" name="drill_schedule_type" value="Announced" id="drillAnnounced" required><label class="form-check-label" for="drillAnnounced">Announced</label></div>
                                        <div class="form-check"><input class="form-check-input" type="radio" name="drill_schedule_type" value="Unannounced (authorized)" id="drillUnannounced"><label class="form-check-label" for="drillUnannounced">Unannounced (authorized)</label></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Scenario</label>
                                    <select class="form-select border-primary" name="scenario" required>
                                        <option value="">Select scenario</option>
                                        <option value="Active Threat">Active Threat</option>
                                        <option value="Active Attacker">Active Attacker</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <input type="text" class="form-control mt-2" name="scenario_other" placeholder="Specify other scenario (if applicable)">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold d-block">Participants</label>
                                    <div class="d-flex flex-wrap gap-3">
                                        <div class="form-check"><input class="form-check-input" type="checkbox" name="participants[]" value="Learners" id="partLearners"><label class="form-check-label" for="partLearners">Learners</label></div>
                                        <div class="form-check"><input class="form-check-input" type="checkbox" name="participants[]" value="Teaching Personnel" id="partTeaching"><label class="form-check-label" for="partTeaching">Teaching Personnel</label></div>
                                        <div class="form-check"><input class="form-check-input" type="checkbox" name="participants[]" value="Non-Teaching Personnel" id="partNonTeaching"><label class="form-check-label" for="partNonTeaching">Non-Teaching Personnel</label></div>
                                        <div class="form-check"><input class="form-check-input" type="checkbox" name="participants[]" value="Security Personnel" id="partSecurity"><label class="form-check-label" for="partSecurity">Security Personnel</label></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Number of Students / Learners</label>
                                    <input type="number" class="form-control" name="no_of_students" min="0" value="0" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Number of Personnel</label>
                                    <input type="number" class="form-control" name="no_of_personnel" min="0" value="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- II. Evaluation Checklist --}}
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <h6 class="fw-bold mb-0">II. Evaluation Checklist</h6>
                        <span class="small text-muted">Choose one rating per row</span>
                    </div>
                    <div class="accordion" id="lockdownChecklistAccordion">

                        <div class="accordion-item mb-3 border rounded overflow-hidden">
                            <h2 class="accordion-header" id="headingA">
                                <button class="accordion-button  fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#lockdownSectionA" aria-expanded="true" aria-controls="lockdownSectionA">
                                    A. Pre-Drill Preparation
                                    <span class="ms-auto me-3 small text-muted section-score-label" id="labelScoreA">0 / 20 points</span>
                                </button>
                            </h2>
                            <div id="lockdownSectionA" class="accordion-collapse collapse show" aria-labelledby="headingA" data-bs-parent="#lockdownChecklistAccordion">
                                <div class="accordion-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover align-middle mb-0 lockdown-score-table" data-section="A">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center" style="width: 48px;">No.</th>
                                                    <th>Evaluation Indicator</th>
                                                    <th class="text-center" style="width: 60px;">2</th>
                                                    <th class="text-center" style="width: 60px;">1</th>
                                                    <th class="text-center" style="width: 60px;">0</th>
                                                    <th class="text-center" style="width: 70px;">N/O</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center text-muted">1</td>
                                                    <td class="small">An approved School Contingency Plan for Active Threat/Active Shooter Incidents is available.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_01]" value="2" id="rating_a_01_2" aria-label="Satisfactory: An approved School Contingency Plan for Active Threat/Active Shooter Incidents is available." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_01]" value="1" id="rating_a_01_1" aria-label="Needs Improvement: An approved School Contingency Plan for Active Threat/Active Shooter Incidents is available." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_01]" value="0" id="rating_a_01_0" aria-label="Unsatisfactory: An approved School Contingency Plan for Active Threat/Active Shooter Incidents is available." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_01]" value="NO" id="rating_a_01_NO" aria-label="Not Observed / Not Applicable: An approved School Contingency Plan for Active Threat/Active Shooter Incidents is available." data-section="A">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">2</td>
                                                    <td class="small">The lockdown procedure and responsibilities of personnel are clearly established.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_02]" value="2" id="rating_a_02_2" aria-label="Satisfactory: The lockdown procedure and responsibilities of personnel are clearly established." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_02]" value="1" id="rating_a_02_1" aria-label="Needs Improvement: The lockdown procedure and responsibilities of personnel are clearly established." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_02]" value="0" id="rating_a_02_0" aria-label="Unsatisfactory: The lockdown procedure and responsibilities of personnel are clearly established." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_02]" value="NO" id="rating_a_02_NO" aria-label="Not Observed / Not Applicable: The lockdown procedure and responsibilities of personnel are clearly established." data-section="A">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">3</td>
                                                    <td class="small">Teachers, learners, and non-teaching personnel received appropriate orientation before the drill.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_03]" value="2" id="rating_a_03_2" aria-label="Satisfactory: Teachers, learners, and non-teaching personnel received appropriate orientation before the drill." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_03]" value="1" id="rating_a_03_1" aria-label="Needs Improvement: Teachers, learners, and non-teaching personnel received appropriate orientation before the drill." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_03]" value="0" id="rating_a_03_0" aria-label="Unsatisfactory: Teachers, learners, and non-teaching personnel received appropriate orientation before the drill." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_03]" value="NO" id="rating_a_03_NO" aria-label="Not Observed / Not Applicable: Teachers, learners, and non-teaching personnel received appropriate orientation before the drill." data-section="A">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">4</td>
                                                    <td class="small">A designated lockdown announcement or alarm is established and distinguishable from fire and earthquake drill signals.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_04]" value="2" id="rating_a_04_2" aria-label="Satisfactory: A designated lockdown announcement or alarm is established and distinguishable from fire and earthquake drill signals." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_04]" value="1" id="rating_a_04_1" aria-label="Needs Improvement: A designated lockdown announcement or alarm is established and distinguishable from fire and earthquake drill signals." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_04]" value="0" id="rating_a_04_0" aria-label="Unsatisfactory: A designated lockdown announcement or alarm is established and distinguishable from fire and earthquake drill signals." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_04]" value="NO" id="rating_a_04_NO" aria-label="Not Observed / Not Applicable: A designated lockdown announcement or alarm is established and distinguishable from fire and earthquake drill signals." data-section="A">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">5</td>
                                                    <td class="small">Evaluators, drill controllers, and relevant emergency contacts are identified.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_05]" value="2" id="rating_a_05_2" aria-label="Satisfactory: Evaluators, drill controllers, and relevant emergency contacts are identified." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_05]" value="1" id="rating_a_05_1" aria-label="Needs Improvement: Evaluators, drill controllers, and relevant emergency contacts are identified." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_05]" value="0" id="rating_a_05_0" aria-label="Unsatisfactory: Evaluators, drill controllers, and relevant emergency contacts are identified." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_05]" value="NO" id="rating_a_05_NO" aria-label="Not Observed / Not Applicable: Evaluators, drill controllers, and relevant emergency contacts are identified." data-section="A">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">6</td>
                                                    <td class="small">Measures are in place to prevent panic and distinguish the exercise from an actual emergency.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_06]" value="2" id="rating_a_06_2" aria-label="Satisfactory: Measures are in place to prevent panic and distinguish the exercise from an actual emergency." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_06]" value="1" id="rating_a_06_1" aria-label="Needs Improvement: Measures are in place to prevent panic and distinguish the exercise from an actual emergency." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_06]" value="0" id="rating_a_06_0" aria-label="Unsatisfactory: Measures are in place to prevent panic and distinguish the exercise from an actual emergency." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_06]" value="NO" id="rating_a_06_NO" aria-label="Not Observed / Not Applicable: Measures are in place to prevent panic and distinguish the exercise from an actual emergency." data-section="A">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">7</td>
                                                    <td class="small">Procedures account for learners and personnel with disabilities or mobility limitations.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_07]" value="2" id="rating_a_07_2" aria-label="Satisfactory: Procedures account for learners and personnel with disabilities or mobility limitations." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_07]" value="1" id="rating_a_07_1" aria-label="Needs Improvement: Procedures account for learners and personnel with disabilities or mobility limitations." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_07]" value="0" id="rating_a_07_0" aria-label="Unsatisfactory: Procedures account for learners and personnel with disabilities or mobility limitations." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_07]" value="NO" id="rating_a_07_NO" aria-label="Not Observed / Not Applicable: Procedures account for learners and personnel with disabilities or mobility limitations." data-section="A">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">8</td>
                                                    <td class="small">Visitors, guards, canteen personnel, and other non-classroom occupants are included in the procedure.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_08]" value="2" id="rating_a_08_2" aria-label="Satisfactory: Visitors, guards, canteen personnel, and other non-classroom occupants are included in the procedure." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_08]" value="1" id="rating_a_08_1" aria-label="Needs Improvement: Visitors, guards, canteen personnel, and other non-classroom occupants are included in the procedure." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_08]" value="0" id="rating_a_08_0" aria-label="Unsatisfactory: Visitors, guards, canteen personnel, and other non-classroom occupants are included in the procedure." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_08]" value="NO" id="rating_a_08_NO" aria-label="Not Observed / Not Applicable: Visitors, guards, canteen personnel, and other non-classroom occupants are included in the procedure." data-section="A">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">9</td>
                                                    <td class="small">Classrooms and designated safe areas have functioning doors, locks, or other appropriate protective measures.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_09]" value="2" id="rating_a_09_2" aria-label="Satisfactory: Classrooms and designated safe areas have functioning doors, locks, or other appropriate protective measures." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_09]" value="1" id="rating_a_09_1" aria-label="Needs Improvement: Classrooms and designated safe areas have functioning doors, locks, or other appropriate protective measures." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_09]" value="0" id="rating_a_09_0" aria-label="Unsatisfactory: Classrooms and designated safe areas have functioning doors, locks, or other appropriate protective measures." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_09]" value="NO" id="rating_a_09_NO" aria-label="Not Observed / Not Applicable: Classrooms and designated safe areas have functioning doors, locks, or other appropriate protective measures." data-section="A">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">10</td>
                                                    <td class="small">Emergency communication and drill termination procedures have been tested or verified.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_10]" value="2" id="rating_a_10_2" aria-label="Satisfactory: Emergency communication and drill termination procedures have been tested or verified." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_10]" value="1" id="rating_a_10_1" aria-label="Needs Improvement: Emergency communication and drill termination procedures have been tested or verified." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_10]" value="0" id="rating_a_10_0" aria-label="Unsatisfactory: Emergency communication and drill termination procedures have been tested or verified." data-section="A">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[a_10]" value="NO" id="rating_a_10_NO" aria-label="Not Observed / Not Applicable: Emergency communication and drill termination procedures have been tested or verified." data-section="A">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center gap-2 p-3 bg-light border-top">
                                        <span class="small text-muted">Section score:</span>
                                        <strong id="sectionScoreA">0 / 20</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3 border rounded overflow-hidden">
                            <h2 class="accordion-header" id="headingB">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#lockdownSectionB" aria-expanded="false" aria-controls="lockdownSectionB">
                                    B. Activation of the Lockdown
                                    <span class="ms-auto me-3 small text-muted section-score-label" id="labelScoreB">0 / 20 points</span>
                                </button>
                            </h2>
                            <div id="lockdownSectionB" class="accordion-collapse collapse " aria-labelledby="headingB" data-bs-parent="#lockdownChecklistAccordion">
                                <div class="accordion-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover align-middle mb-0 lockdown-score-table" data-section="B">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center" style="width: 48px;">No.</th>
                                                    <th>Evaluation Indicator</th>
                                                    <th class="text-center" style="width: 60px;">2</th>
                                                    <th class="text-center" style="width: 60px;">1</th>
                                                    <th class="text-center" style="width: 60px;">0</th>
                                                    <th class="text-center" style="width: 70px;">N/O</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center text-muted">1</td>
                                                    <td class="small">The designated announcement or signal is clearly communicated.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_01]" value="2" id="rating_b_01_2" aria-label="Satisfactory: The designated announcement or signal is clearly communicated." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_01]" value="1" id="rating_b_01_1" aria-label="Needs Improvement: The designated announcement or signal is clearly communicated." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_01]" value="0" id="rating_b_01_0" aria-label="Unsatisfactory: The designated announcement or signal is clearly communicated." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_01]" value="NO" id="rating_b_01_NO" aria-label="Not Observed / Not Applicable: The designated announcement or signal is clearly communicated." data-section="B">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">2</td>
                                                    <td class="small">Teachers and personnel recognize the signal and initiate the appropriate response.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_02]" value="2" id="rating_b_02_2" aria-label="Satisfactory: Teachers and personnel recognize the signal and initiate the appropriate response." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_02]" value="1" id="rating_b_02_1" aria-label="Needs Improvement: Teachers and personnel recognize the signal and initiate the appropriate response." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_02]" value="0" id="rating_b_02_0" aria-label="Unsatisfactory: Teachers and personnel recognize the signal and initiate the appropriate response." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_02]" value="NO" id="rating_b_02_NO" aria-label="Not Observed / Not Applicable: Teachers and personnel recognize the signal and initiate the appropriate response." data-section="B">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">3</td>
                                                    <td class="small">Learners respond promptly and calmly to the instructions.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_03]" value="2" id="rating_b_03_2" aria-label="Satisfactory: Learners respond promptly and calmly to the instructions." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_03]" value="1" id="rating_b_03_1" aria-label="Needs Improvement: Learners respond promptly and calmly to the instructions." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_03]" value="0" id="rating_b_03_0" aria-label="Unsatisfactory: Learners respond promptly and calmly to the instructions." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_03]" value="NO" id="rating_b_03_NO" aria-label="Not Observed / Not Applicable: Learners respond promptly and calmly to the instructions." data-section="B">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">4</td>
                                                    <td class="small">Personnel provide clear, concise, and appropriate instructions.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_04]" value="2" id="rating_b_04_2" aria-label="Satisfactory: Personnel provide clear, concise, and appropriate instructions." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_04]" value="1" id="rating_b_04_1" aria-label="Needs Improvement: Personnel provide clear, concise, and appropriate instructions." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_04]" value="0" id="rating_b_04_0" aria-label="Unsatisfactory: Personnel provide clear, concise, and appropriate instructions." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_04]" value="NO" id="rating_b_04_NO" aria-label="Not Observed / Not Applicable: Personnel provide clear, concise, and appropriate instructions." data-section="B">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">5</td>
                                                    <td class="small">The lockdown response is initiated without unnecessary delay or confusion.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_05]" value="2" id="rating_b_05_2" aria-label="Satisfactory: The lockdown response is initiated without unnecessary delay or confusion." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_05]" value="1" id="rating_b_05_1" aria-label="Needs Improvement: The lockdown response is initiated without unnecessary delay or confusion." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_05]" value="0" id="rating_b_05_0" aria-label="Unsatisfactory: The lockdown response is initiated without unnecessary delay or confusion." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_05]" value="NO" id="rating_b_05_NO" aria-label="Not Observed / Not Applicable: The lockdown response is initiated without unnecessary delay or confusion." data-section="B">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">6</td>
                                                    <td class="small">Personnel avoid actions that could expose participants to simulated danger.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_06]" value="2" id="rating_b_06_2" aria-label="Satisfactory: Personnel avoid actions that could expose participants to simulated danger." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_06]" value="1" id="rating_b_06_1" aria-label="Needs Improvement: Personnel avoid actions that could expose participants to simulated danger." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_06]" value="0" id="rating_b_06_0" aria-label="Unsatisfactory: Personnel avoid actions that could expose participants to simulated danger." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_06]" value="NO" id="rating_b_06_NO" aria-label="Not Observed / Not Applicable: Personnel avoid actions that could expose participants to simulated danger." data-section="B">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">7</td>
                                                    <td class="small">The school follows its established procedure for communicating the lockdown to affected areas.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_07]" value="2" id="rating_b_07_2" aria-label="Satisfactory: The school follows its established procedure for communicating the lockdown to affected areas." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_07]" value="1" id="rating_b_07_1" aria-label="Needs Improvement: The school follows its established procedure for communicating the lockdown to affected areas." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_07]" value="0" id="rating_b_07_0" aria-label="Unsatisfactory: The school follows its established procedure for communicating the lockdown to affected areas." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_07]" value="NO" id="rating_b_07_NO" aria-label="Not Observed / Not Applicable: The school follows its established procedure for communicating the lockdown to affected areas." data-section="B">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">8</td>
                                                    <td class="small">No unauthorized alarm or conflicting announcement disrupts the exercise.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_08]" value="2" id="rating_b_08_2" aria-label="Satisfactory: No unauthorized alarm or conflicting announcement disrupts the exercise." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_08]" value="1" id="rating_b_08_1" aria-label="Needs Improvement: No unauthorized alarm or conflicting announcement disrupts the exercise." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_08]" value="0" id="rating_b_08_0" aria-label="Unsatisfactory: No unauthorized alarm or conflicting announcement disrupts the exercise." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_08]" value="NO" id="rating_b_08_NO" aria-label="Not Observed / Not Applicable: No unauthorized alarm or conflicting announcement disrupts the exercise." data-section="B">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">9</td>
                                                    <td class="small">The school maintains control of routine movement and prevents unnecessary exposure in corridors or open areas.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_09]" value="2" id="rating_b_09_2" aria-label="Satisfactory: The school maintains control of routine movement and prevents unnecessary exposure in corridors or open areas." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_09]" value="1" id="rating_b_09_1" aria-label="Needs Improvement: The school maintains control of routine movement and prevents unnecessary exposure in corridors or open areas." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_09]" value="0" id="rating_b_09_0" aria-label="Unsatisfactory: The school maintains control of routine movement and prevents unnecessary exposure in corridors or open areas." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_09]" value="NO" id="rating_b_09_NO" aria-label="Not Observed / Not Applicable: The school maintains control of routine movement and prevents unnecessary exposure in corridors or open areas." data-section="B">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">10</td>
                                                    <td class="small">The drill controller confirms that the exercise is proceeding within the approved scenario.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_10]" value="2" id="rating_b_10_2" aria-label="Satisfactory: The drill controller confirms that the exercise is proceeding within the approved scenario." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_10]" value="1" id="rating_b_10_1" aria-label="Needs Improvement: The drill controller confirms that the exercise is proceeding within the approved scenario." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_10]" value="0" id="rating_b_10_0" aria-label="Unsatisfactory: The drill controller confirms that the exercise is proceeding within the approved scenario." data-section="B">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[b_10]" value="NO" id="rating_b_10_NO" aria-label="Not Observed / Not Applicable: The drill controller confirms that the exercise is proceeding within the approved scenario." data-section="B">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center gap-2 p-3 bg-light border-top">
                                        <span class="small text-muted">Section score:</span>
                                        <strong id="sectionScoreB">0 / 20</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3 border rounded overflow-hidden">
                            <h2 class="accordion-header" id="headingC">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#lockdownSectionC" aria-expanded="false" aria-controls="lockdownSectionC">
                                    C. Classroom and Room Security Procedures
                                    <span class="ms-auto me-3 small text-muted section-score-label" id="labelScoreC">0 / 20 points</span>
                                </button>
                            </h2>
                            <div id="lockdownSectionC" class="accordion-collapse collapse " aria-labelledby="headingC" data-bs-parent="#lockdownChecklistAccordion">
                                <div class="accordion-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover align-middle mb-0 lockdown-score-table" data-section="C">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center" style="width: 48px;">No.</th>
                                                    <th>Evaluation Indicator</th>
                                                    <th class="text-center" style="width: 60px;">2</th>
                                                    <th class="text-center" style="width: 60px;">1</th>
                                                    <th class="text-center" style="width: 60px;">0</th>
                                                    <th class="text-center" style="width: 70px;">N/O</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center text-muted">1</td>
                                                    <td class="small">Classroom doors are secured according to the approved lockdown procedure.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_01]" value="2" id="rating_c_01_2" aria-label="Satisfactory: Classroom doors are secured according to the approved lockdown procedure." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_01]" value="1" id="rating_c_01_1" aria-label="Needs Improvement: Classroom doors are secured according to the approved lockdown procedure." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_01]" value="0" id="rating_c_01_0" aria-label="Unsatisfactory: Classroom doors are secured according to the approved lockdown procedure." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_01]" value="NO" id="rating_c_01_NO" aria-label="Not Observed / Not Applicable: Classroom doors are secured according to the approved lockdown procedure." data-section="C">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">2</td>
                                                    <td class="small">Windows, curtains, or other visual barriers are managed when appropriate and safe.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_02]" value="2" id="rating_c_02_2" aria-label="Satisfactory: Windows, curtains, or other visual barriers are managed when appropriate and safe." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_02]" value="1" id="rating_c_02_1" aria-label="Needs Improvement: Windows, curtains, or other visual barriers are managed when appropriate and safe." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_02]" value="0" id="rating_c_02_0" aria-label="Unsatisfactory: Windows, curtains, or other visual barriers are managed when appropriate and safe." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_02]" value="NO" id="rating_c_02_NO" aria-label="Not Observed / Not Applicable: Windows, curtains, or other visual barriers are managed when appropriate and safe." data-section="C">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">3</td>
                                                    <td class="small">Learners and personnel move to designated protected areas away from public view, where appropriate.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_03]" value="2" id="rating_c_03_2" aria-label="Satisfactory: Learners and personnel move to designated protected areas away from public view, where appropriate." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_03]" value="1" id="rating_c_03_1" aria-label="Needs Improvement: Learners and personnel move to designated protected areas away from public view, where appropriate." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_03]" value="0" id="rating_c_03_0" aria-label="Unsatisfactory: Learners and personnel move to designated protected areas away from public view, where appropriate." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_03]" value="NO" id="rating_c_03_NO" aria-label="Not Observed / Not Applicable: Learners and personnel move to designated protected areas away from public view, where appropriate." data-section="C">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">4</td>
                                                    <td class="small">Participants remain quiet and follow the established communication procedure.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_04]" value="2" id="rating_c_04_2" aria-label="Satisfactory: Participants remain quiet and follow the established communication procedure." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_04]" value="1" id="rating_c_04_1" aria-label="Needs Improvement: Participants remain quiet and follow the established communication procedure." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_04]" value="0" id="rating_c_04_0" aria-label="Unsatisfactory: Participants remain quiet and follow the established communication procedure." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_04]" value="NO" id="rating_c_04_NO" aria-label="Not Observed / Not Applicable: Participants remain quiet and follow the established communication procedure." data-section="C">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">5</td>
                                                    <td class="small">Mobile phones and communication devices are used in accordance with school instructions.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_05]" value="2" id="rating_c_05_2" aria-label="Satisfactory: Mobile phones and communication devices are used in accordance with school instructions." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_05]" value="1" id="rating_c_05_1" aria-label="Needs Improvement: Mobile phones and communication devices are used in accordance with school instructions." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_05]" value="0" id="rating_c_05_0" aria-label="Unsatisfactory: Mobile phones and communication devices are used in accordance with school instructions." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_05]" value="NO" id="rating_c_05_NO" aria-label="Not Observed / Not Applicable: Mobile phones and communication devices are used in accordance with school instructions." data-section="C">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">6</td>
                                                    <td class="small">Teachers or designated personnel account for learners and other occupants without compromising safety.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_06]" value="2" id="rating_c_06_2" aria-label="Satisfactory: Teachers or designated personnel account for learners and other occupants without compromising safety." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_06]" value="1" id="rating_c_06_1" aria-label="Needs Improvement: Teachers or designated personnel account for learners and other occupants without compromising safety." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_06]" value="0" id="rating_c_06_0" aria-label="Unsatisfactory: Teachers or designated personnel account for learners and other occupants without compromising safety." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_06]" value="NO" id="rating_c_06_NO" aria-label="Not Observed / Not Applicable: Teachers or designated personnel account for learners and other occupants without compromising safety." data-section="C">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">7</td>
                                                    <td class="small">Personnel do not open secured doors or leave protected areas without proper authorization or a genuine safety necessity.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_07]" value="2" id="rating_c_07_2" aria-label="Satisfactory: Personnel do not open secured doors or leave protected areas without proper authorization or a genuine safety necessity." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_07]" value="1" id="rating_c_07_1" aria-label="Needs Improvement: Personnel do not open secured doors or leave protected areas without proper authorization or a genuine safety necessity." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_07]" value="0" id="rating_c_07_0" aria-label="Unsatisfactory: Personnel do not open secured doors or leave protected areas without proper authorization or a genuine safety necessity." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_07]" value="NO" id="rating_c_07_NO" aria-label="Not Observed / Not Applicable: Personnel do not open secured doors or leave protected areas without proper authorization or a genuine safety necessity." data-section="C">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">8</td>
                                                    <td class="small">Learners requiring additional assistance are supported appropriately.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_08]" value="2" id="rating_c_08_2" aria-label="Satisfactory: Learners requiring additional assistance are supported appropriately." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_08]" value="1" id="rating_c_08_1" aria-label="Needs Improvement: Learners requiring additional assistance are supported appropriately." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_08]" value="0" id="rating_c_08_0" aria-label="Unsatisfactory: Learners requiring additional assistance are supported appropriately." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_08]" value="NO" id="rating_c_08_NO" aria-label="Not Observed / Not Applicable: Learners requiring additional assistance are supported appropriately." data-section="C">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">9</td>
                                                    <td class="small">No participant is instructed to confront, pursue, or physically engage a simulated attacker.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_09]" value="2" id="rating_c_09_2" aria-label="Satisfactory: No participant is instructed to confront, pursue, or physically engage a simulated attacker." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_09]" value="1" id="rating_c_09_1" aria-label="Needs Improvement: No participant is instructed to confront, pursue, or physically engage a simulated attacker." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_09]" value="0" id="rating_c_09_0" aria-label="Unsatisfactory: No participant is instructed to confront, pursue, or physically engage a simulated attacker." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_09]" value="NO" id="rating_c_09_NO" aria-label="Not Observed / Not Applicable: No participant is instructed to confront, pursue, or physically engage a simulated attacker." data-section="C">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">10</td>
                                                    <td class="small">Classroom occupants maintain the protective measures until an authorized all-clear or other valid emergency instruction is received.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_10]" value="2" id="rating_c_10_2" aria-label="Satisfactory: Classroom occupants maintain the protective measures until an authorized all-clear or other valid emergency instruction is received." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_10]" value="1" id="rating_c_10_1" aria-label="Needs Improvement: Classroom occupants maintain the protective measures until an authorized all-clear or other valid emergency instruction is received." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_10]" value="0" id="rating_c_10_0" aria-label="Unsatisfactory: Classroom occupants maintain the protective measures until an authorized all-clear or other valid emergency instruction is received." data-section="C">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[c_10]" value="NO" id="rating_c_10_NO" aria-label="Not Observed / Not Applicable: Classroom occupants maintain the protective measures until an authorized all-clear or other valid emergency instruction is received." data-section="C">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center gap-2 p-3 bg-light border-top">
                                        <span class="small text-muted">Section score:</span>
                                        <strong id="sectionScoreC">0 / 20</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3 border rounded overflow-hidden">
                            <h2 class="accordion-header" id="headingD">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#lockdownSectionD" aria-expanded="false" aria-controls="lockdownSectionD">
                                    D. School-Wide Coordination and Communication
                                    <span class="ms-auto me-3 small text-muted section-score-label" id="labelScoreD">0 / 20 points</span>
                                </button>
                            </h2>
                            <div id="lockdownSectionD" class="accordion-collapse collapse " aria-labelledby="headingD" data-bs-parent="#lockdownChecklistAccordion">
                                <div class="accordion-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover align-middle mb-0 lockdown-score-table" data-section="D">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center" style="width: 48px;">No.</th>
                                                    <th>Evaluation Indicator</th>
                                                    <th class="text-center" style="width: 60px;">2</th>
                                                    <th class="text-center" style="width: 60px;">1</th>
                                                    <th class="text-center" style="width: 60px;">0</th>
                                                    <th class="text-center" style="width: 70px;">N/O</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center text-muted">1</td>
                                                    <td class="small">The School Head or designated Incident Commander assumes the assigned coordination role.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_01]" value="2" id="rating_d_01_2" aria-label="Satisfactory: The School Head or designated Incident Commander assumes the assigned coordination role." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_01]" value="1" id="rating_d_01_1" aria-label="Needs Improvement: The School Head or designated Incident Commander assumes the assigned coordination role." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_01]" value="0" id="rating_d_01_0" aria-label="Unsatisfactory: The School Head or designated Incident Commander assumes the assigned coordination role." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_01]" value="NO" id="rating_d_01_NO" aria-label="Not Observed / Not Applicable: The School Head or designated Incident Commander assumes the assigned coordination role." data-section="D">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">2</td>
                                                    <td class="small">The School DRRM Coordinator or designated team monitors drill implementation.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_02]" value="2" id="rating_d_02_2" aria-label="Satisfactory: The School DRRM Coordinator or designated team monitors drill implementation." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_02]" value="1" id="rating_d_02_1" aria-label="Needs Improvement: The School DRRM Coordinator or designated team monitors drill implementation." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_02]" value="0" id="rating_d_02_0" aria-label="Unsatisfactory: The School DRRM Coordinator or designated team monitors drill implementation." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_02]" value="NO" id="rating_d_02_NO" aria-label="Not Observed / Not Applicable: The School DRRM Coordinator or designated team monitors drill implementation." data-section="D">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">3</td>
                                                    <td class="small">The school has a functioning method for communicating with relevant personnel.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_03]" value="2" id="rating_d_03_2" aria-label="Satisfactory: The school has a functioning method for communicating with relevant personnel." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_03]" value="1" id="rating_d_03_1" aria-label="Needs Improvement: The school has a functioning method for communicating with relevant personnel." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_03]" value="0" id="rating_d_03_0" aria-label="Unsatisfactory: The school has a functioning method for communicating with relevant personnel." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_03]" value="NO" id="rating_d_03_NO" aria-label="Not Observed / Not Applicable: The school has a functioning method for communicating with relevant personnel." data-section="D">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">4</td>
                                                    <td class="small">Security guards follow established access-control and reporting procedures.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_04]" value="2" id="rating_d_04_2" aria-label="Satisfactory: Security guards follow established access-control and reporting procedures." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_04]" value="1" id="rating_d_04_1" aria-label="Needs Improvement: Security guards follow established access-control and reporting procedures." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_04]" value="0" id="rating_d_04_0" aria-label="Unsatisfactory: Security guards follow established access-control and reporting procedures." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_04]" value="NO" id="rating_d_04_NO" aria-label="Not Observed / Not Applicable: Security guards follow established access-control and reporting procedures." data-section="D">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">5</td>
                                                    <td class="small">The school follows its approved process for contacting police or other emergency responders during a real incident.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_05]" value="2" id="rating_d_05_2" aria-label="Satisfactory: The school follows its approved process for contacting police or other emergency responders during a real incident." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_05]" value="1" id="rating_d_05_1" aria-label="Needs Improvement: The school follows its approved process for contacting police or other emergency responders during a real incident." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_05]" value="0" id="rating_d_05_0" aria-label="Unsatisfactory: The school follows its approved process for contacting police or other emergency responders during a real incident." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_05]" value="NO" id="rating_d_05_NO" aria-label="Not Observed / Not Applicable: The school follows its approved process for contacting police or other emergency responders during a real incident." data-section="D">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">6</td>
                                                    <td class="small">Designated personnel account for learners and staff using available class lists or attendance records.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_06]" value="2" id="rating_d_06_2" aria-label="Satisfactory: Designated personnel account for learners and staff using available class lists or attendance records." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_06]" value="1" id="rating_d_06_1" aria-label="Needs Improvement: Designated personnel account for learners and staff using available class lists or attendance records." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_06]" value="0" id="rating_d_06_0" aria-label="Unsatisfactory: Designated personnel account for learners and staff using available class lists or attendance records." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_06]" value="NO" id="rating_d_06_NO" aria-label="Not Observed / Not Applicable: Designated personnel account for learners and staff using available class lists or attendance records." data-section="D">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">7</td>
                                                    <td class="small">Communication procedures consider learners or personnel who are outside classrooms.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_07]" value="2" id="rating_d_07_2" aria-label="Satisfactory: Communication procedures consider learners or personnel who are outside classrooms." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_07]" value="1" id="rating_d_07_1" aria-label="Needs Improvement: Communication procedures consider learners or personnel who are outside classrooms." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_07]" value="0" id="rating_d_07_0" aria-label="Unsatisfactory: Communication procedures consider learners or personnel who are outside classrooms." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_07]" value="NO" id="rating_d_07_NO" aria-label="Not Observed / Not Applicable: Communication procedures consider learners or personnel who are outside classrooms." data-section="D">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">8</td>
                                                    <td class="small">The school avoids releasing sensitive information about the simulated threat through unauthorized channels.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_08]" value="2" id="rating_d_08_2" aria-label="Satisfactory: The school avoids releasing sensitive information about the simulated threat through unauthorized channels." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_08]" value="1" id="rating_d_08_1" aria-label="Needs Improvement: The school avoids releasing sensitive information about the simulated threat through unauthorized channels." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_08]" value="0" id="rating_d_08_0" aria-label="Unsatisfactory: The school avoids releasing sensitive information about the simulated threat through unauthorized channels." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_08]" value="NO" id="rating_d_08_NO" aria-label="Not Observed / Not Applicable: The school avoids releasing sensitive information about the simulated threat through unauthorized channels." data-section="D">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">9</td>
                                                    <td class="small">The drill is coordinated without disrupting neighboring schools or causing unnecessary public alarm.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_09]" value="2" id="rating_d_09_2" aria-label="Satisfactory: The drill is coordinated without disrupting neighboring schools or causing unnecessary public alarm." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_09]" value="1" id="rating_d_09_1" aria-label="Needs Improvement: The drill is coordinated without disrupting neighboring schools or causing unnecessary public alarm." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_09]" value="0" id="rating_d_09_0" aria-label="Unsatisfactory: The drill is coordinated without disrupting neighboring schools or causing unnecessary public alarm." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_09]" value="NO" id="rating_d_09_NO" aria-label="Not Observed / Not Applicable: The drill is coordinated without disrupting neighboring schools or causing unnecessary public alarm." data-section="D">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">10</td>
                                                    <td class="small">The school maintains an accurate record of significant events and observations during the exercise.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_10]" value="2" id="rating_d_10_2" aria-label="Satisfactory: The school maintains an accurate record of significant events and observations during the exercise." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_10]" value="1" id="rating_d_10_1" aria-label="Needs Improvement: The school maintains an accurate record of significant events and observations during the exercise." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_10]" value="0" id="rating_d_10_0" aria-label="Unsatisfactory: The school maintains an accurate record of significant events and observations during the exercise." data-section="D">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[d_10]" value="NO" id="rating_d_10_NO" aria-label="Not Observed / Not Applicable: The school maintains an accurate record of significant events and observations during the exercise." data-section="D">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center gap-2 p-3 bg-light border-top">
                                        <span class="small text-muted">Section score:</span>
                                        <strong id="sectionScoreD">0 / 20</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3 border rounded overflow-hidden">
                            <h2 class="accordion-header" id="headingE">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#lockdownSectionE" aria-expanded="false" aria-controls="lockdownSectionE">
                                    E. Drill Termination, Accountability, and Recovery
                                    <span class="ms-auto me-3 small text-muted section-score-label" id="labelScoreE">0 / 20 points</span>
                                </button>
                            </h2>
                            <div id="lockdownSectionE" class="accordion-collapse collapse " aria-labelledby="headingE" data-bs-parent="#lockdownChecklistAccordion">
                                <div class="accordion-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover align-middle mb-0 lockdown-score-table" data-section="E">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center" style="width: 48px;">No.</th>
                                                    <th>Evaluation Indicator</th>
                                                    <th class="text-center" style="width: 60px;">2</th>
                                                    <th class="text-center" style="width: 60px;">1</th>
                                                    <th class="text-center" style="width: 60px;">0</th>
                                                    <th class="text-center" style="width: 70px;">N/O</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center text-muted">1</td>
                                                    <td class="small">The authorized drill controller clearly announces the termination of the exercise.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_01]" value="2" id="rating_e_01_2" aria-label="Satisfactory: The authorized drill controller clearly announces the termination of the exercise." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_01]" value="1" id="rating_e_01_1" aria-label="Needs Improvement: The authorized drill controller clearly announces the termination of the exercise." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_01]" value="0" id="rating_e_01_0" aria-label="Unsatisfactory: The authorized drill controller clearly announces the termination of the exercise." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_01]" value="NO" id="rating_e_01_NO" aria-label="Not Observed / Not Applicable: The authorized drill controller clearly announces the termination of the exercise." data-section="E">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">2</td>
                                                    <td class="small">Participants distinguish the drill termination signal from the lockdown activation signal.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_02]" value="2" id="rating_e_02_2" aria-label="Satisfactory: Participants distinguish the drill termination signal from the lockdown activation signal." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_02]" value="1" id="rating_e_02_1" aria-label="Needs Improvement: Participants distinguish the drill termination signal from the lockdown activation signal." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_02]" value="0" id="rating_e_02_0" aria-label="Unsatisfactory: Participants distinguish the drill termination signal from the lockdown activation signal." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_02]" value="NO" id="rating_e_02_NO" aria-label="Not Observed / Not Applicable: Participants distinguish the drill termination signal from the lockdown activation signal." data-section="E">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">3</td>
                                                    <td class="small">Teachers and designated personnel conduct an appropriate headcount or accountability check.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_03]" value="2" id="rating_e_03_2" aria-label="Satisfactory: Teachers and designated personnel conduct an appropriate headcount or accountability check." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_03]" value="1" id="rating_e_03_1" aria-label="Needs Improvement: Teachers and designated personnel conduct an appropriate headcount or accountability check." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_03]" value="0" id="rating_e_03_0" aria-label="Unsatisfactory: Teachers and designated personnel conduct an appropriate headcount or accountability check." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_03]" value="NO" id="rating_e_03_NO" aria-label="Not Observed / Not Applicable: Teachers and designated personnel conduct an appropriate headcount or accountability check." data-section="E">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">4</td>
                                                    <td class="small">Missing, unaccounted-for, or injured participants are promptly reported through the established process.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_04]" value="2" id="rating_e_04_2" aria-label="Satisfactory: Missing, unaccounted-for, or injured participants are promptly reported through the established process." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_04]" value="1" id="rating_e_04_1" aria-label="Needs Improvement: Missing, unaccounted-for, or injured participants are promptly reported through the established process." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_04]" value="0" id="rating_e_04_0" aria-label="Unsatisfactory: Missing, unaccounted-for, or injured participants are promptly reported through the established process." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_04]" value="NO" id="rating_e_04_NO" aria-label="Not Observed / Not Applicable: Missing, unaccounted-for, or injured participants are promptly reported through the established process." data-section="E">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">5</td>
                                                    <td class="small">Personnel verify that it is appropriate to resume normal activities.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_05]" value="2" id="rating_e_05_2" aria-label="Satisfactory: Personnel verify that it is appropriate to resume normal activities." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_05]" value="1" id="rating_e_05_1" aria-label="Needs Improvement: Personnel verify that it is appropriate to resume normal activities." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_05]" value="0" id="rating_e_05_0" aria-label="Unsatisfactory: Personnel verify that it is appropriate to resume normal activities." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_05]" value="NO" id="rating_e_05_NO" aria-label="Not Observed / Not Applicable: Personnel verify that it is appropriate to resume normal activities." data-section="E">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">6</td>
                                                    <td class="small">Learners are guided back to normal activities in an orderly manner.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_06]" value="2" id="rating_e_06_2" aria-label="Satisfactory: Learners are guided back to normal activities in an orderly manner." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_06]" value="1" id="rating_e_06_1" aria-label="Needs Improvement: Learners are guided back to normal activities in an orderly manner." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_06]" value="0" id="rating_e_06_0" aria-label="Unsatisfactory: Learners are guided back to normal activities in an orderly manner." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_06]" value="NO" id="rating_e_06_NO" aria-label="Not Observed / Not Applicable: Learners are guided back to normal activities in an orderly manner." data-section="E">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">7</td>
                                                    <td class="small">Personnel check for distress, confusion, or other welfare concerns among participants.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_07]" value="2" id="rating_e_07_2" aria-label="Satisfactory: Personnel check for distress, confusion, or other welfare concerns among participants." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_07]" value="1" id="rating_e_07_1" aria-label="Needs Improvement: Personnel check for distress, confusion, or other welfare concerns among participants." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_07]" value="0" id="rating_e_07_0" aria-label="Unsatisfactory: Personnel check for distress, confusion, or other welfare concerns among participants." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_07]" value="NO" id="rating_e_07_NO" aria-label="Not Observed / Not Applicable: Personnel check for distress, confusion, or other welfare concerns among participants." data-section="E">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">8</td>
                                                    <td class="small">The School Head and DRRM team conduct a post-drill debriefing.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_08]" value="2" id="rating_e_08_2" aria-label="Satisfactory: The School Head and DRRM team conduct a post-drill debriefing." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_08]" value="1" id="rating_e_08_1" aria-label="Needs Improvement: The School Head and DRRM team conduct a post-drill debriefing." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_08]" value="0" id="rating_e_08_0" aria-label="Unsatisfactory: The School Head and DRRM team conduct a post-drill debriefing." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_08]" value="NO" id="rating_e_08_NO" aria-label="Not Observed / Not Applicable: The School Head and DRRM team conduct a post-drill debriefing." data-section="E">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">9</td>
                                                    <td class="small">Evaluators document strengths, deficiencies, and recommended corrective actions.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_09]" value="2" id="rating_e_09_2" aria-label="Satisfactory: Evaluators document strengths, deficiencies, and recommended corrective actions." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_09]" value="1" id="rating_e_09_1" aria-label="Needs Improvement: Evaluators document strengths, deficiencies, and recommended corrective actions." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_09]" value="0" id="rating_e_09_0" aria-label="Unsatisfactory: Evaluators document strengths, deficiencies, and recommended corrective actions." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_09]" value="NO" id="rating_e_09_NO" aria-label="Not Observed / Not Applicable: Evaluators document strengths, deficiencies, and recommended corrective actions." data-section="E">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center text-muted">10</td>
                                                    <td class="small">The school establishes a process for completing and monitoring its post-drill evaluation report.</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_10]" value="2" id="rating_e_10_2" aria-label="Satisfactory: The school establishes a process for completing and monitoring its post-drill evaluation report." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_10]" value="1" id="rating_e_10_1" aria-label="Needs Improvement: The school establishes a process for completing and monitoring its post-drill evaluation report." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_10]" value="0" id="rating_e_10_0" aria-label="Unsatisfactory: The school establishes a process for completing and monitoring its post-drill evaluation report." data-section="E">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input lockdown-rating" type="radio" name="checklist_data[e_10]" value="NO" id="rating_e_10_NO" aria-label="Not Observed / Not Applicable: The school establishes a process for completing and monitoring its post-drill evaluation report." data-section="E">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center gap-2 p-3 bg-light border-top">
                                        <span class="small text-muted">Section score:</span>
                                        <strong id="sectionScoreE">0 / 20</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- III. Summary --}}
                    <div class="card border-0 shadow-sm my-4">
                        <div class="card-header bg-light fw-bold">III. Summary of Evaluation Scores</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-3">
                                    <thead class="table-light">
                                        <tr><th>Evaluation Area</th><th class="text-center">Score</th><th class="text-center">Adjusted Maximum</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>A. Pre-Drill Preparation</td>
                                            <td class="text-center"><span id="summaryScoreA">0</span></td>
                                            <td class="text-center"><span id="summaryMaxA">20</span></td>
                                        </tr>
                                        <tr>
                                            <td>B. Activation of the Lockdown</td>
                                            <td class="text-center"><span id="summaryScoreB">0</span></td>
                                            <td class="text-center"><span id="summaryMaxB">20</span></td>
                                        </tr>
                                        <tr>
                                            <td>C. Classroom and Room Security Procedures</td>
                                            <td class="text-center"><span id="summaryScoreC">0</span></td>
                                            <td class="text-center"><span id="summaryMaxC">20</span></td>
                                        </tr>
                                        <tr>
                                            <td>D. School-Wide Coordination and Communication</td>
                                            <td class="text-center"><span id="summaryScoreD">0</span></td>
                                            <td class="text-center"><span id="summaryMaxD">20</span></td>
                                        </tr>
                                        <tr>
                                            <td>E. Drill Termination, Accountability, and Recovery</td>
                                            <td class="text-center"><span id="summaryScoreE">0</span></td>
                                            <td class="text-center"><span id="summaryMaxE">20</span></td>
                                        </tr>
                                        <tr class="table-primary fw-bold">
                                            <td>TOTAL</td>
                                            <td class="text-center"><span id="totalScore">0</span></td>
                                            <td class="text-center"><span id="totalMax">100</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row g-3 align-items-center">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Overall Result</label>
                                    <input type="text" class="form-control" id="overallResult" name="overall_result" value="Not yet rated" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Percentage</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="overallPercentage" name="overall_percentage" value="0%" readonly>
                                        <span class="input-group-text">of adjusted maximum</span>
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-2">Suggested internal interpretation only: 90–100% Highly satisfactory; 75–89% Satisfactory, with improvements needed; 50–74% Needs significant improvement; below 50% Requires priority corrective action. Replace these bands if an official Division rubric is issued.</small>
                        </div>
                    </div>

                    {{-- IV. Observations --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light fw-bold">IV. Evaluator's Observations and Recommendations</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">1. Observed Strengths</label>
                                <textarea class="form-control" name="observed_strengths" rows="3" placeholder="Record strengths observed during the drill..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">2. Gaps and Areas for Improvement</label>
                                <textarea class="form-control" name="improvement_gaps" rows="3" placeholder="Describe gaps, issues, or inconsistencies observed..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">3. Recommended Corrective Actions</label>
                                <textarea class="form-control" name="corrective_actions" rows="3" placeholder="Specify the action, responsible person/team, and target date where applicable..."></textarea>
                            </div>
                            <div>
                                <label class="form-label fw-bold">4. Evaluation Summary / Additional Notes</label>
                                <textarea class="form-control" name="remarks" rows="3" placeholder="Additional observations or summary..."></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- V. Certification --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light fw-bold">V. Certification</div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Evaluator / Observer</label>
                                    <input type="text" class="form-control" name="cert_evaluator">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">School Head</label>
                                    <input type="text" class="form-control" name="cert_school_head">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">School DRRM Coordinator</label>
                                    <input type="text" class="form-control" name="cert_drrm_coordinator">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Evaluator Signature / Reference</label>
                                    <input type="text" class="form-control" name="signature_evaluator" placeholder="Signature reference">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">School Head Signature / Reference</label>
                                    <input type="text" class="form-control" name="signature_school_head" placeholder="Signature reference">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Coordinator Signature / Reference</label>
                                    <input type="text" class="form-control" name="signature_coordinator" placeholder="Signature reference">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- VI. Safety Reminders --}}
                    <div class="alert alert-warning mb-0">
                        <h6 class="fw-bold">VI. Important Safety Reminders for Evaluators</h6>
                        <ul class="mb-0 small">
                            <li>No real weapons, realistic weapon replicas, physical confrontations, or unauthorized simulated attacks should be introduced during the drill.</li>
                            <li>Coordinate with appropriate school authorities and relevant responders in advance, and establish an immediate stop-drill procedure.</li>
                            <li>If a genuine threat or emergency occurs, stop the exercise and activate the actual emergency response protocol.</li>
                            <li>Evaluate the effectiveness of protective procedures, not merely the speed of the lockdown.</li>
                            <li>Do not require a universal evacuation or a single response to every scenario. Actions must follow the approved contingency plan and actual circumstances.</li>
                        </ul>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="reset" class="btn btn-light border" id="resetLockdownEvaluation">Reset Form</button>
                    <button type="submit" class="btn btn-primary" id="saveLockdownEvaluationBtn"><i class="fas fa-save me-1"></i> Save Evaluation</button>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    #lockdownEvaluationModal .modal-dialog { max-width: 1200px; }
    #lockdownEvaluationModal .modal-header { background: #0d6efd; }
    #lockdownEvaluationModal .accordion-button:not(.collapsed) { background: #eaf2ff; color: #123c74; box-shadow: none; }
    #lockdownEvaluationModal .lockdown-score-table th { white-space: nowrap; font-size: .8rem; }
    #lockdownEvaluationModal .lockdown-score-table td { padding: .55rem .5rem; }
    #lockdownEvaluationModal .lockdown-rating { cursor: pointer; width: 1.05rem; height: 1.05rem; }
    #lockdownEvaluationModal .lockdown-rating:checked { outline: 2px solid rgba(13,110,253,.2); }
    #lockdownEvaluationModal .form-label { margin-bottom: .35rem; }
    @media (max-width: 767.98px) {
        #lockdownEvaluationModal .modal-dialog { margin: .5rem; }
        #lockdownEvaluationModal .modal-body { padding: .85rem; }
        #lockdownEvaluationModal .lockdown-score-table { min-width: 650px; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('lockdownEvaluationModal');
    if (!modal) return;

    const sectionLetters = ['A', 'B', 'C', 'D', 'E'];

    function updateLockdownScores() {
        let grandScore = 0;
        let grandMax = 0;

        sectionLetters.forEach(function (section) {
            const table = modal.querySelector('.lockdown-score-table[data-section="' + section + '"]');
            if (!table) return;

            let score = 0;
            let ratedCount = 0;

            table.querySelectorAll('tbody tr').forEach(function (row) {
                const selected = row.querySelector('input[type="radio"]:checked');
                if (!selected) return;

                if (selected.value !== 'NO') {
                    score += Number(selected.value);
                    ratedCount++;
                }
            });

            const adjustedMax = ratedCount * 2;
            grandScore += score;
            grandMax += adjustedMax;

            const sectionScore = modal.querySelector('#sectionScore' + section);
            const labelScore = modal.querySelector('#labelScore' + section);
            const summaryScore = modal.querySelector('#summaryScore' + section);
            const summaryMax = modal.querySelector('#summaryMax' + section);

            if (sectionScore) sectionScore.textContent = score + ' / ' + adjustedMax;
            if (labelScore) labelScore.textContent = score + ' / ' + adjustedMax + ' points';
            if (summaryScore) summaryScore.textContent = score;
            if (summaryMax) summaryMax.textContent = adjustedMax;
        });

        const totalScore = modal.querySelector('#totalScore');
        const totalMax = modal.querySelector('#totalMax');
        const percentageInput = modal.querySelector('#overallPercentage');
        const resultInput = modal.querySelector('#overallResult');

        const percentage = grandMax > 0 ? (grandScore / grandMax) * 100 : 0;
        if (totalScore) totalScore.textContent = grandScore;
        if (totalMax) totalMax.textContent = grandMax;
        if (percentageInput) percentageInput.value = percentage.toFixed(1) + '%';

        let result = 'Not yet rated';
        if (grandMax > 0) {
            if (percentage >= 90) result = 'Highly satisfactory';
            else if (percentage >= 75) result = 'Satisfactory, with improvements needed';
            else if (percentage >= 50) result = 'Needs significant improvement';
            else result = 'Requires priority corrective action';
        }
        if (resultInput) resultInput.value = result;
    }

    modal.addEventListener('change', function (event) {
        if (event.target.matches('.lockdown-rating')) updateLockdownScores();
    });

    const form = document.getElementById('lockdownEvaluationForm');
    if (form) {
        form.addEventListener('reset', function () {
            window.setTimeout(function () {
                if (form.action.includes('/update/')) {
                    document.getElementById('lockdownFormMethod').value = 'PUT';
                }
                updateLockdownScores();
            }, 0);
        });

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            const submitButton = form.querySelector('button[type="submit"]');
            const originalButtonText = submitButton ? submitButton.innerHTML : '';
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
            }

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                if (!response.ok || data.success === false) {
                    throw new Error(data.message || 'The lockdown evaluation could not be saved.');
                }
                if (window.Swal) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Evaluation Saved',
                        text: data.message || 'The lockdown evaluation was saved.',
                        timer: 1800,
                        showConfirmButton: false
                    });
                }
                window.location.reload();
            } catch (error) {
                if (window.Swal) {
                    Swal.fire('Save Failed', error.message, 'error');
                } else {
                    window.alert(error.message);
                }
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                }
            }
        });
    }

    updateLockdownScores();
});
</script>
