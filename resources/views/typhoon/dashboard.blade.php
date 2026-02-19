{{-- resources/views/typhoon/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Typhoon/Flooding Monitoring')
@section('hide_main_nav', '1')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary border-0 me-3" title="Back">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="h3 mb-0" style="color: #1B4C6D;">
                <i class="fas fa-desktop me-2"></i> Typhoon & Flood Monitoring Dashboard
            </h1>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-dark border p-2">
                <i class="fas fa-clock me-1"></i> AS OF {{ now()->format('F d, Y h:i A') }}
            </span>
            <button class="btn btn-sm btn-outline-primary" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i>
            </button>
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createEvacCenterModal">
                <i class="fas fa-plus-circle me-1"></i> Add Center
            </button>
            <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#chooseSchoolModal">
                <i class="fas fa-school"></i>
            </button>
        </div>
    </div>

    {{-- Monitoring Grid --}}
    <div class="row g-3 mb-4">
        
        {{-- A. Estimated Affected Population --}}
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm h-100 border-start border-4 border-primary">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted fw-bold small mb-3">Estimated Affected Population</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Families</span>
                        <span class="fw-bold fs-5">{{ $totalFamilies ?? 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Individuals</span>
                        <span class="fw-bold fs-5">{{ $totalEvacuees ?? 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Open Evac Centers</span>
                        <span class="fw-bold fs-5 text-primary">{{ $openEvacuationCentersCount ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- B. Incident Monitoring --}}
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm h-100 border-start border-4 border-danger">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted fw-bold small mb-3">Incident Monitoring</h6>
                    <div class="d-flex justify-content-between mb-3 align-items-center">
                        <span>Major Incidents</span>
                        <span class="badge bg-danger rounded-pill fs-6">{{ $incidentMonitoring['major'] ?? 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Minor Incidents</span>
                        <span class="badge bg-warning text-dark rounded-pill fs-6">{{ $incidentMonitoring['minor'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- C. Rainfall Accumulation --}}
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm h-100 border-start border-4 border-info">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted fw-bold small mb-3">Daily Rainfall (mm)</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Bangal Station</span>
                        <span class="fw-bold text-info">{{ $rainfall['bangal'] ?? '0.0' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Kalaklan Station</span>
                        <span class="fw-bold text-info">{{ $rainfall['kalaklan'] ?? '0.0' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- D. Weather Forecast --}}
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm h-100 border-start border-4 border-success">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted fw-bold small mb-3">Weather Forecast</h6>
                    <div class="mb-2">
                        <span class="d-block text-muted small">System Name</span>
                        <span class="fw-bold fs-5 text-success">{{ $typhoonData->name ?? 'None' }}</span>
                    </div>
                    <div>
                        <span class="d-block text-muted small">As Of</span>
                        <span class="fw-bold">{{ now()->format('h:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Evacuation Centers List --}}
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-dark">Evacuation Centers Status</h5>
                        <div class="d-flex gap-2">
                            <span class="badge bg-success">Cleared</span>
                            <span class="badge bg-primary">Occupied</span>
                            <span class="badge bg-danger">Full</span>
                            <span style="background-color: #6f42c1;" class="badge text-white">Decamp</span>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>School / Center</th>
                                <th>Location</th>
                                <th class="text-center">Capacity</th>
                                <th class="text-center">Occupancy</th>
                                <th class="text-center">Usage Status</th>
                                <th>Resources</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($evacuationCenters ?? [] as $ec)
                            <tr>
                                <td class="fw-bold">
                                    {{ $ec->school->school_name ?? $ec->identification ?? ('Evacuation Center #' . $ec->id) }}
                                </td>
                                <td class="small text-muted">{{ Str::limit($ec->location, 30) }}</td>
                                <td class="text-center">{{ $ec->capacity > 0 ? $ec->capacity : 'N/A' }}</td>
                                <td class="text-center fw-bold">{{ $ec->current_occupancy }}</td>
                                <td class="text-center">
                                    @php
                                        $statusColor = 'success'; // default cleared
                                        $statusLabel = 'Cleared';
                                        if($ec->usage_status === 'occupied') { $statusColor = 'primary'; $statusLabel = 'Occupied'; }
                                        elseif($ec->usage_status === 'full') { $statusColor = 'danger'; $statusLabel = 'Full'; }
                                        elseif($ec->usage_status === 'decamp') { $statusColor = 'custom-purple'; $statusLabel = 'Decamp'; }
                                        else { $statusLabel = 'Cleared'; }
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }} {{ $statusColor === 'custom-purple' ? 'text-white' : '' }}" 
                                          style="{{ $ec->usage_status === 'decamp' ? 'background-color: #6f42c1;' : '' }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td>
                                    <small class="d-block text-truncate" style="max-width: 150px;" title="{{ $ec->emergency_resources }}">
                                        {{ $ec->emergency_resources ?? '-' }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#familyRegistrationModal"
                                                data-ec-id="{{ $ec->id }}"
                                                data-ec-name="{{ $ec->school->school_name ?? $ec->identification ?? ('Evacuation Center #' . $ec->id) }}"
                                                title="Register Family">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
                                        <a href="{{ route('typhoon.evacuation-center.show', $ec->id) }}" class="btn btn-outline-secondary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-school fa-3x mb-3 text-secondary"></i>
                                    <p>No evacuation centers registered.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Chart Section --}}
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Occupancy Overview</h5>
                </div>
                <div class="card-body">
                    <canvas id="occupancyChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Shared modals --}}
    @include('typhoon.partials.choose-school-modal')
    @include('typhoon.partials.create-evac-center-modal')
</div>

{{-- Family Registration Modal (Already Updated) --}}
<div class="modal fade" id="familyRegistrationModal" tabindex="-1" aria-labelledby="familyRegistrationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('typhoon.families.store') }}" id="familyRegistrationForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header" style="background-color: #1B4C6D; color: white;">
                    <h5 class="modal-title" id="familyRegistrationModalLabel">
                        <i class="fas fa-people-arrows"></i> Register Family Evacuee
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Evacuation Center / School <span class="text-danger">*</span></label>
                        <select name="evacuation_center_id" id="modal_evacuation_center_id" class="form-select" required>
                            <option value="">-- Select Evacuation Center --</option>
                            @foreach($evacuationCenters ?? [] as $ec)
                                <option value="{{ $ec->id }}">
                                    {{ $ec->school->school_name ?? $ec->identification ?? ('Evacuation Center #' . $ec->id) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Family-level fields --}}
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h6 class="fw-bold text-primary"><i class="fas fa-user-tie"></i> Head of Family Details</h6>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-bold">Full Name (Head) <span class="text-danger">*</span></label>
                            <input type="text" name="head_family_name" id="input_head_name" class="form-control" placeholder="Full name of head" required
                                oninput="document.getElementById('hidden_head_name').value = this.value">
                            <input type="hidden" name="members[0][full_name]" id="hidden_head_name">
                            <input type="hidden" name="members[0][is_head]" value="1">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label small fw-bold">Age <span class="text-danger">*</span></label>
                            <input type="number" name="members[0][age]" class="form-control" placeholder="Age" required min="0" max="150">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label small fw-bold">Gender <span class="text-danger">*</span></label>
                            <select name="members[0][gender]" class="form-select" required>
                                <option value="">Select...</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label small fw-bold">Individual Needs (Optional)</label>
                            <input type="text" name="members[0][needs]" class="form-control" placeholder="Specific needs for the head (e.g. medication)">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Collective Family Needs (Optional)</label>
                            <textarea name="collective_needs" class="form-control" rows="1" placeholder="e.g. Rice, medicine, blankets (shared items)"></textarea>
                        </div>
                    </div>

                    <div class="mb-3 p-2 bg-light rounded">
                        <label class="form-label fw-bold small">Family Vulnerabilities / Special Concerns</label>
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="has_pregnant" value="1" id="flagPregnant">
                                <label class="form-check-label" for="flagPregnant">Pregnant</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="has_pwd" value="1" id="flagPwd">
                                <label class="form-check-label" for="flagPwd">PWD</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="has_senior" value="1" id="flagSenior">
                                <label class="form-check-label" for="flagSenior">Senior Citizen</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="has_lactating" value="1" id="flagLactating">
                                <label class="form-check-label" for="flagLactating">Lactating</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="has_child_under5" value="1" id="flagChild">
                                <label class="form-check-label" for="flagChild">Child under 5</label>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-bold mb-0">Other Family Members</label>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-member-btn">
                            <i class="fas fa-plus"></i> Add Member
                        </button>
                    </div>
                    
                    <div id="family-members-container">
                        {{-- Dynamic members start here --}}
                    </div>

                    <hr>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="confirm_check_in" id="confirmCheckIn" checked>
                        <label class="form-check-label" for="confirmCheckIn">
                            Check-in this family now (sets current date/time)
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background-color: #1B4C6D; color: white;">
                        <i class="fas fa-save"></i> Register Family
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@php
    $chartData = $evacuationCenters->map(function($ec) {
        return [
            'name' => \Illuminate\Support\Str::limit($ec->school->school_name ?? $ec->identification ?? 'Center #'.$ec->id, 15),
            'occupancy' => $ec->current_occupancy,
            'capacity' => $ec->capacity,
        ];
    })->values();
@endphp

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Setup Chart
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('occupancyChart').getContext('2d');
        
        // Prepare Data using robust JSON serialization
        const rawData = @json($chartData);

        const labels = rawData.map(d => d.name);
        const dataOccupancy = rawData.map(d => d.occupancy);
        const dataCapacity = rawData.map(d => d.capacity);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Current Occupancy',
                    data: dataOccupancy,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Capacity',
                    data: dataCapacity,
                    backgroundColor: 'rgba(201, 203, 207, 0.3)',
                    borderColor: 'rgba(201, 203, 207, 1)',
                    borderWidth: 1,
                    type: 'bar' 
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        grace: '5%'
                    }
                }
            }
        });
    });

    // 2. Family Modal Logic
    const familyModalEl = document.getElementById('familyRegistrationModal');
    if (familyModalEl) {
        familyModalEl.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const select = familyModalEl.querySelector('#modal_evacuation_center_id');
            if (button && select) {
                const ecId = button.getAttribute('data-ec-id');
                if (ecId) {
                    select.value = ecId;
                }
            }
        });
    }

    // Dynamic family members
    let memberIndex = 1;
    const addMemberBtn = document.getElementById('add-member-btn');
    if(addMemberBtn) {
        addMemberBtn.addEventListener('click', function() {
            const container = document.getElementById('family-members-container');
            const newRow = document.createElement('div');
            newRow.className = 'row g-2 mb-2 member-row border-bottom pb-2';
            newRow.innerHTML = `
                <div class="col-md-4">
                    <input type="text" name="members[${memberIndex}][full_name]" class="form-control" placeholder="Full name" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="members[${memberIndex}][age]" class="form-control" placeholder="Age" required min="0">
                </div>
                <div class="col-md-2">
                    <select name="members[${memberIndex}][gender]" class="form-select" required>
                        <option value="">Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="members[${memberIndex}][needs]" class="form-control" placeholder="Individual needs">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-member">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <input type="hidden" name="members[${memberIndex}][is_head]" value="0">
            `;
            container.appendChild(newRow);
            memberIndex++;

            newRow.querySelector('.remove-member').addEventListener('click', function() {
                newRow.remove();
            });
        });
    }
</script>
@endpush

@endsection