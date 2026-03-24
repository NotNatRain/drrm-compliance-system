@extends('layouts.app')

@section('title', 'Reporting Dashboard - ' . ($assignedIncidentSchoolName ?? 'Incident Checklist'))
@section('hide_main_nav', '1')

@push('styles')
<style>
    :root {
        --incident-yellow: #F2C94C;
        --incident-orange: #F2994A;
        --incident-dark: #333333;
        --incident-light: #fdfcf0;
    }

    body {
        background-color: #ffffff !important;
        font-family: 'Outfit', sans-serif;
    }

    main.py-4 {
        padding-top: 0 !important;
    }

    .report-header {
        background: linear-gradient(135deg, var(--incident-yellow) 0%, var(--incident-orange) 100%);
        padding: 30px 40px;
        border-bottom-left-radius: 40px;
        border-bottom-right-radius: 40px;
        box-shadow: 0 10px 30px rgba(242, 201, 76, 0.3);
        margin-bottom: 40px;
        color: #fff;
    }

    .back-btn {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #fff;
        padding: 10px 20px;
        border-radius: 15px;
        text-decoration: none !important;
        transition: all 0.2s;
    }

    .back-btn:hover {
        background: rgba(255, 255, 255, 0.4);
        color: #fff;
    }

    .report-card {
        border: 1px solid #eee;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 25px;
        transition: transform 0.2s, box-shadow 0.2s;
        background: #fff;
    }

    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .report-date {
        font-weight: 700;
        color: var(--incident-orange);
        font-size: 1.2rem;
        border-bottom: 2px solid var(--incident-yellow);
        display: inline-block;
        margin-bottom: 20px;
    }

    .entry-item {
        padding: 15px 0;
        border-bottom: 1px dashed #eee;
    }

    .entry-item:last-child {
        border-bottom: none;
    }

    .badge-incident { background-color: #ff0844; color: #fff; }
    .badge-compliance { background-color: #1ed760; color: #fff; }

    .status-badge {
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 50px;
        font-weight: 600;
    }

    .status-pending { background-color: #ffeeba; color: #856404; }
    .status-accepted { background-color: #d4edda; color: #155724; }
    .status-rejected { background-color: #f8d7da; color: #721c24; }

    .empty-state {
        text-align: center;
        padding: 60px;
        color: #adb5bd;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.3;
    }

    .btn-log {
        background: var(--incident-orange);
        border: none;
        color: #fff;
        font-weight: 600;
        padding: 12px 30px;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(242, 153, 74, 0.3);
    }

    .btn-log:hover {
        background: #e68a30;
        color: #fff;
        transform: scale(1.05);
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 25px;
        border: none;
        overflow: hidden;
    }

    .modal-header {
        background: linear-gradient(135deg, var(--incident-yellow) 0%, var(--incident-orange) 100%);
        color: #fff;
        padding: 25px;
    }

    .nav-tabs .nav-link {
        border-radius: 12px 12px 0 0;
        color: #666;
        font-weight: 600;
    }

    .nav-tabs .nav-link.active {
        color: var(--incident-orange);
        border-color: #dee2e6 #dee2e6 #fff;
    }
</style>
@endpush

@section('content')
<div class="report-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-7">
                <a href="{{ route('dashboard') }}" class="back-btn mb-3 d-inline-block">
                    <i class="fas fa-arrow-left me-2"></i> Back to Main
                </a>
                <div class="d-flex align-items-center mb-2">
                    <img src="{{ asset('images/incident-checklist-logo.png') }}" alt="Incident Checklist" style="height: 45px; margin-right: 15px;">
                    <h1 class="fw-bold mb-0">Reporting Dashboard - {{ $assignedIncidentSchoolName ?? 'Incident Checklist' }}</h1>
                </div>
                <p class="opacity-90">Monitor daily incident reports and compliance updates</p>
            </div>
            <div class="col-md-5 text-md-end mt-3 mt-md-0">
                <button class="btn btn-outline-light btn-lg rounded-pill shadow-sm position-relative me-3" data-bs-toggle="modal" data-bs-target="#allNotificationsModal" title="Notifications">
                    <i class="fas fa-bell"></i>
                    @php
                        $rejectedCount = $myReports->where('status', 'rejected')->count();
                    @endphp
                    @if($rejectedCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $rejectedCount }}
                        </span>
                    @endif
                </button>
                <button class="btn btn-log shadow-lg" data-bs-toggle="modal" data-bs-target="#logIncidentModal">
                    <i class="fas fa-plus-circle me-2"></i> Log New Incident/Event
                </button>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <h4 class="fw-bold mb-0">Recent Weekly Reports</h4>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('incidents.dashboard', ['week_offset' => ($weekOffset ?? 0) - 1]) }}" class="btn btn-outline-secondary btn-sm rounded-pill" title="Previous Week">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <span class="small fw-bold text-muted px-2">
                        {{ ($weekStart ?? now()->startOfWeek())->format('M d, Y') }} - {{ ($weekEnd ?? now()->endOfWeek())->format('M d, Y') }}
                    </span>
                    <a href="{{ route('incidents.dashboard', ['week_offset' => ($weekOffset ?? 0) + 1]) }}" class="btn btn-outline-secondary btn-sm rounded-pill" title="Next Week">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <a href="{{ route('incidents.reporting.print', ['period' => 'daily', 'date' => now()->toDateString()]) }}" class="btn btn-outline-secondary btn-sm rounded-pill" title="Print Daily Report">
                        <i class="fas fa-print me-1"></i> Daily
                    </a>
                    <a href="{{ route('incidents.reporting.print', ['period' => 'weekly', 'date' => ($weekStart ?? now())->format('Y-m-d')]) }}" class="btn btn-outline-secondary btn-sm rounded-pill" title="Print Weekly Report">
                        <i class="fas fa-print me-1"></i> Weekly
                    </a>
                    <a href="{{ route('incidents.reporting.print', ['period' => 'monthly', 'date' => ($weekStart ?? now())->format('Y-m-d')]) }}" class="btn btn-outline-secondary btn-sm rounded-pill" title="Print Monthly Report">
                        <i class="fas fa-print me-1"></i> Monthly
                    </a>
                </div>
            </div>

            @php
                $groupedReports = $myReports->groupBy(function($item) {
                    return $item->incident_date->format('Y-m-d');
                });
            @endphp

            @forelse($groupedReports as $date => $reports)
                <div class="report-card">
                    <div class="report-date">
                        {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}
                        <span class="ms-2 small text-muted">({{ \Carbon\Carbon::parse($date)->format('l') }})</span>
                    </div>

                    @foreach($reports as $report)
                        <div class="entry-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="d-flex align-items-center mb-2">
                                        @if($report->entry_type === 'incident')
                                            <span class="badge badge-incident me-2">INCIDENT</span>
                                            <h6 class="fw-bold mb-0">{{ $report->incidentType->name ?? 'Unspecified' }}</h6>
                                        @else
                                            <span class="badge badge-compliance me-2">COMPLIANCE</span>
                                            <h6 class="fw-bold mb-0">{{ $report->incidentStatus->name ?? 'Unspecified' }}</h6>
                                        @endif
                                        <span class="status-badge ms-3 status-{{ $report->status }}">
                                            @if($report->status === 'pending') <i class="fas fa-clock me-1"></i> Pending Review
                                            @elseif($report->status === 'accepted') <i class="fas fa-check-circle me-1"></i> Registered
                                            @else <i class="fas fa-times-circle me-1"></i> Rejected
                                            @endif
                                        </span>
                                    </div>
                                    <div class="text-muted small mb-2">
                                        <i class="fas fa-school me-1"></i> {{ $report->school_name }}
                                        <span class="mx-2">|</span>
                                        <i class="fas fa-clock me-1"></i> Submitted at {{ $report->created_at->format('h:i A') }}
                                    </div>
                                    <p class="mb-2 text-dark">{{ $report->remarks }}</p>

                                    @if($report->status === 'rejected' && $report->rejection_reason)
                                        <div class="alert alert-danger py-2 px-3 small mt-2">
                                            <strong>Rejection Note:</strong> {{ $report->rejection_reason }}
                                        </div>
                                    @endif

                                    @if($report->attachment_path)
                                        <div class="mt-2">
                                            <a href="{{ asset('storage/' . $report->attachment_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill">
                                                <i class="fas fa-paperclip me-1"></i> View Attachment
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-end">
                                    @if($report->affected_personnel > 0 || $report->affected_students > 0)
                                        <div class="small text-muted mb-1">Impact:</div>
                                        <div class="d-flex gap-2 justify-content-end">
                                            @if($report->affected_personnel > 0)
                                                <span class="badge bg-light text-dark border">
                                                    <i class="fas fa-users text-primary me-1"></i> {{ $report->affected_personnel }}
                                                </span>
                                            @endif
                                            @if($report->affected_students > 0)
                                                <span class="badge bg-light text-dark border">
                                                    <i class="fas fa-child text-info me-1"></i> {{ $report->affected_students }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-clipboard-list"></i>
                    <h3>No Reports This Week</h3>
                    <p>No incident or compliance reports found for the selected week.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Simple Modal for Notifications See More -->
<div class="modal fade" id="allNotificationsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-bell me-2"></i> Notifications</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="list-group list-group-flush" id="notifListContainer">
                    @forelse($myReports->where('status', 'rejected') as $rejected)
                        <div class="list-group-item p-3 border-start border-danger border-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-danger"><i class="fas fa-times-circle me-1"></i> Report Rejected</span>
                                <small class="text-muted">{{ $rejected->updated_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-2 small">Your report for <strong>{{ $rejected->school_name }}</strong> on {{ $rejected->incident_date->format('M d, Y') }} was rejected.</p>
                            @if($rejected->rejection_reason)
                                <div class="bg-light p-2 rounded small border">
                                    <strong>Reason:</strong> {{ $rejected->rejection_reason }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="p-5 text-center text-muted">
                            <i class="fas fa-bell-slash fa-2x mb-2 opacity-50"></i>
                            <p class="mb-0">No new notifications.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal logic similar to admin dashboard but specialized for contributor -->
@include('incidents.partials.log-modal')

@endsection

@push('scripts')
<script>
    const csrfToken = '{{ csrf_token() }}';
    const incidentsStoreUrl = '{{ route("incidents.store") }}';

    document.addEventListener('DOMContentLoaded', function() {
        // School source toggles
        document.querySelectorAll('.incident-school-source').forEach(radio => {
            radio.addEventListener('change', function() {
                const existingContainer = document.getElementById('incident_existing_school_container');
                const newContainer = document.getElementById('incident_new_school_container');
                if (this.value === 'existing') {
                    existingContainer.style.display = 'block';
                    newContainer.style.display = 'none';
                } else if (this.value === 'new') {
                    existingContainer.style.display = 'none';
                    newContainer.style.display = 'block';
                } else {
                    existingContainer.style.display = 'none';
                    newContainer.style.display = 'none';
                }
            });
        });

        document.querySelectorAll('.compliance-school-source').forEach(radio => {
            radio.addEventListener('change', function() {
                const existingContainer = document.getElementById('compliance_existing_school_container');
                const newContainer = document.getElementById('compliance_new_school_container');
                if (this.value === 'existing') {
                    existingContainer.style.display = 'block';
                    newContainer.style.display = 'none';
                } else if (this.value === 'new') {
                    existingContainer.style.display = 'none';
                    newContainer.style.display = 'block';
                } else {
                    existingContainer.style.display = 'none';
                    newContainer.style.display = 'none';
                }
            });
        });

        // Form submissions
        document.getElementById('incidentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            submitIncidentForm(this, false);
        });

        document.getElementById('complianceForm').addEventListener('submit', function(e) {
            e.preventDefault();
            submitIncidentForm(this, true);
        });

        async function submitIncidentForm(form, isCompliance) {
            const formData = new FormData(form);
            const btn = form.querySelector('button[type="submit"]');
            const origText = btn.innerHTML;

            const selectedDate = isCompliance
                ? (form.querySelector('#compliance_date_input')?.value || '')
                : (form.querySelector('#incident_date_input')?.value || '');
            if (selectedDate) {
                formData.set('incident_date', selectedDate);
            }

            const assignedIncidentSchool = document.getElementById('incident_assigned_school_name')?.value || '';
            const assignedComplianceSchool = document.getElementById('compliance_assigned_school_name')?.value || '';

            if (isCompliance && assignedComplianceSchool) {
                formData.set('school_name', assignedComplianceSchool);
            } else if (!isCompliance && assignedIncidentSchool) {
                formData.set('school_name', assignedIncidentSchool);
            } else if (!isCompliance) {
                const source = form.querySelector('input[name="incident_source_type"]:checked')?.value;
                if (source === 'all') {
                    formData.set('school_name', 'All Schools');
                } else if (source === 'existing') {
                    formData.set('school_name', form.querySelector('#incident_school_existing_select')?.value || '');
                } else {
                    formData.set('school_name', form.querySelector('#incident_school_name_manual')?.value || '');
                }
            } else {
                const source = form.querySelector('input[name="compliance_source_type"]:checked')?.value;
                if (source === 'all') {
                    formData.set('school_name', 'All Schools');
                } else if (source === 'existing') {
                    formData.set('school_name', form.querySelector('#compliance_school_existing_select')?.value || '');
                } else {
                    formData.set('school_name', form.querySelector('#compliance_school_name_manual')?.value || '');
                }
            }

            if (!formData.get('school_name')) {
                alert('Please select a school first.');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

            try {
                const r = await fetch(incidentsStoreUrl, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: formData
                });
                const resp = await r.json();
                if (resp.success) {
                    bootstrap.Modal.getInstance(document.getElementById('logIncidentModal')).hide();
                    if (window.showNotify) {
                        await showNotify('Report submitted and synced to dashboard calendar.', 'Success', 'fa-check-circle');
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        window.location.reload();
                    }
                } else {
                    alert(resp.message || 'Failed to save');
                    btn.disabled = false;
                    btn.innerHTML = origText;
                }
            } catch (err) {
                alert('Connection error');
                btn.disabled = false;
                btn.innerHTML = origText;
            }
        }
    });
</script>
@endpush
