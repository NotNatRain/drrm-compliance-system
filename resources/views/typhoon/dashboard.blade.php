{{-- resources/views/typhoon/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Typhoon/Flooding Dashboard')
@section('hide_main_nav', '1')

@section('content')
<div class="container-fluid">
    {{-- Header with back button only --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary border-0 p-2" title="Back to Main Dashboard">
            <i class="fas fa-arrow-left fa-lg"></i>
        </a>
        <div class="d-flex align-items-center gap-3">
            {{-- Navigation tabs --}}
            <nav class="nav nav-pills">
                <a class="nav-link active" href="{{ route('typhoon.dashboard') }}" style="background-color: #1B4C6D; color: white;">
                    <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                </a>
                <a class="nav-link text-dark" href="#" data-bs-toggle="modal" data-bs-target="#chooseSchoolModal">
                    <i class="fas fa-school me-1"></i> Choose School
                </a>
            </nav>
            <span class="badge bg-info">
                <i class="fas fa-clock"></i> Real-time Monitoring Active
            </span>
        </div>
    </div>

    {{-- Main header --}}
    <div class="mb-4">
        <h1 class="h3 mb-0" style="color: #1B4C6D;">
            <i class="fas fa-umbrella"></i> Typhoon/Flooding Compliance
        </h1>
        <p class="text-muted">Casualty tracking, evacuation center management, and real-time monitoring</p>
    </div>

    {{-- Main Dashboard Cards --}}
    <div class="row">
        {{-- Missing --}}
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow h-100" style="border-top: 4px solid #dc3545;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Missing</h6>
                            <h2 class="mb-0">{{ $missingCount ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-search fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Injured --}}
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow h-100" style="border-top: 4px solid #ffc107;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Injured</h6>
                            <h2 class="mb-0">{{ $injuredCount ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-user-injured fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Deceased --}}
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow h-100" style="border-top: 4px solid #343a40;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Deceased</h6>
                            <h2 class="mb-0">{{ $deceasedCount ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-cross fa-2x text-dark"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Evacuees (total individuals) --}}
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow h-100" style="border-top: 4px solid #28a745;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Evacuees</h6>
                            <h2 class="mb-0">{{ $totalEvacuees ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-users fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Evacuation Centers and Family Registration --}}
    <div class="row">
        {{-- Evacuation Centers Table --}}
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #1B4C6D; color: white;">
                    <h5 class="mb-0">
                        <i class="fas fa-school"></i> Evacuation Centers Status
                    </h5>
                    <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#createEvacCenterModal">
                        <i class="fas fa-plus-circle"></i> Create Evacuation Center/School
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Evacuation Center</th>
                                    <th>Status</th>
                                    <th>Capacity</th>
                                    <th>Occupancy</th>
                                    <th>Available</th>
                                    <th>Usage Status</th>
                                    <th>Monitoring &amp; Reports</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($evacuationCenters ?? [] as $ec)
                                <tr>
                                    <td>{{ $ec->school->school_name ?? $ec->identification ?? ('Evacuation Center #' . $ec->id) }}</td>
                                    <td>
                                        @if($ec->operational_status === 'operational')
                                            <span class="badge bg-success">Operational</span>
                                        @elseif($ec->operational_status === 'partial')
                                            <span class="badge bg-warning">Partial</span>
                                        @else
                                            <span class="badge bg-danger">Closed</span>
                                        @endif
                                    </td>
                                    @php
                                        $capacity = $ec->capacity ?? 0;
                                        $currentOcc = $ec->current_occupancy ?? 0;
                                        $available = max($capacity - $currentOcc, 0);
                                    @endphp
                                    <td>{{ $capacity }}</td>
                                    <td>{{ $currentOcc }}</td>
                                    <td>{{ $available }}</td>
                                    <td>
                                        @if($ec->usage_status === 'full')
                                            <span class="badge bg-danger">Full</span>
                                        @elseif($ec->usage_status === 'occupied')
                                            <span class="badge bg-warning text-dark">Occupied</span>
                                        @else
                                            <span class="badge bg-success">Cleared</span>
                                        @endif
                                    </td>
                                    <td style="max-width: 220px;">
                                        <small>{{ $ec->reports_status ?? 'No reports yet' }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#familyRegistrationModal"
                                                    data-ec-id="{{ $ec->id }}"
                                                    data-ec-name="{{ $ec->school->school_name ?? $ec->identification ?? ('Evacuation Center #' . $ec->id) }}">
                                                <i class="fas fa-user-plus"></i>
                                            </button>
                                            <a href="{{ route('typhoon.evacuation-center.show', $ec->id) }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-building fa-2x mb-2"></i><br>
                                        No evacuation centers found. Use "Create Evacuation Center/School" to add one.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Evacuee Summary & Actions --}}
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header" style="background-color: #1B4C6D; color: white;">
                    <h5 class="mb-0">
                        <i class="fas fa-users"></i> Evacuee Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Total Families Evacuated: <strong>{{ $totalFamilies ?? 0 }}</strong></h6>
                        <h6>Total Individuals: <strong>{{ $totalEvacuees ?? 0 }}</strong></h6>
                    </div>

                    @if($recentEvacuees ?? false)
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>{{ $recentlyRegistered ?? 0 }} new families registered today</strong>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>No active evacuation</strong><br>
                            <small>All evacuation centers are currently empty</small>
                        </div>
                    @endif

                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#familyRegistrationModal">
                            <i class="fas fa-plus"></i> Register Evacuee (Family)
                        </button>
                        <button class="btn btn-outline-success" onclick="window.print()">
                            <i class="fas fa-print"></i> Print Evacuation List
                        </button>
                    </div>
                </div>
            </div>

            {{-- Quick status flags summary --}}
            <div class="card shadow">
                <div class="card-body">
                    <h6 class="fw-bold">Vulnerable Groups</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-danger">Pregnant: {{ $vulnerableCounts['pregnant'] ?? 0 }}</span>
                        <span class="badge bg-warning text-dark">PWD: {{ $vulnerableCounts['pwd'] ?? 0 }}</span>
                        <span class="badge bg-secondary">Senior: {{ $vulnerableCounts['senior'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Real-time Monitoring Section --}}
    <div class="row mt-2">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header" style="background-color: #1B4C6D; color: white;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-map-marked-alt"></i> Real-time Monitoring
                        </h5>
                        <span class="badge bg-light text-dark" id="rt_last_updated_badge">
                            <i class="fas fa-sync-alt me-1"></i> Updated: {{ now()->format('h:i A') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- Flood Monitoring --}}
                        <div class="col-md-4 mb-3">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-water fa-2x text-primary me-2"></i>
                                        <h6 class="fw-bold mb-0">Flood Level</h6>
                                    </div>
                                    <p class="display-6 mb-1" id="rt_flood_level">{{ $floodMonitoring->level ?? 'Normal' }}</p>
                                    <small class="text-muted" id="rt_flood_station">Station: {{ $floodMonitoring->station ?? 'San Isidro' }}</small>
                                </div>
                            </div>
                        </div>

                        {{-- Typhoon Tracking --}}
                        <div class="col-md-4 mb-3">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-hurricane fa-2x text-info me-2"></i>
                                        <h6 class="fw-bold mb-0">Typhoon Track</h6>
                                    </div>
                                    <p class="fw-bold mb-1" id="rt_typhoon_name">{{ $typhoonData->name ?? 'None' }}</p>
                                    <small id="rt_typhoon_wind">Wind: {{ $typhoonData->wind_speed ?? '--' }} km/h</small>
                                </div>
                            </div>
                        </div>

                        {{-- Evacuation Routes --}}
                        <div class="col-md-4 mb-3">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-route fa-2x text-success me-2"></i>
                                        <h6 class="fw-bold mb-0">Evacuation Routes</h6>
                                    </div>
                                    <p class="text-success mb-1" id="rt_routes_status"><i class="fas fa-check-circle"></i> All routes clear</p>
                                    <small id="rt_routes_blocked">3 blocked roads reported</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Live feed placeholder --}}
                    <div class="alert alert-warning mt-2 mb-0" id="rt_refresh_note">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Real-time monitoring is active. Data refreshes every 30 seconds.
                        <a href="#" class="alert-link">View full monitoring</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Shared modals --}}
    @include('typhoon.partials.choose-school-modal')
    @include('typhoon.partials.create-evac-center-modal')
</div>

{{-- Family Registration Modal --}}
<div class="modal fade" id="familyRegistrationModal" tabindex="-1" aria-labelledby="familyRegistrationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('typhoon.families.store') }}" id="familyRegistrationForm">
            @csrf
            {{-- Selected Evacuation Center --}}
            
            <div class="modal-content">
                <div class="modal-header" style="background-color: #1B4C6D; color: white;">
                    <h5 class="modal-title" id="familyRegistrationModalLabel">
                        <i class="fas fa-people-arrows"></i> Register Family Evacuee
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    {{-- Evacuation Center Info --}}
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
                        <small class="text-muted">You can also click "Register" in the table to pre-select a center.</small>
                    </div>

                    {{-- Family-level fields --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Family Head <span class="text-danger">*</span></label>
                            <input type="text" name="head_family_name" class="form-control" placeholder="Full name of head" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Collective Needs (optional)</label>
                            <textarea name="collective_needs" class="form-control" rows="1" placeholder="e.g. Rice, medicine, blankets"></textarea>
                            <small class="text-muted">Items the whole family shares</small>
                        </div>
                    </div>

                    {{-- Family status flags --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Family Special Concerns</label>
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

                    {{-- Family members (dynamic rows) --}}
                    <label class="form-label fw-bold">Family Members <span class="text-danger">*</span></label>
                    <small class="text-muted d-block mb-2">First member is automatically the family head.</small>
                    
                    <div id="family-members-container">
                        {{-- First member row (head) --}}
                        <div class="row g-2 mb-2 member-row">
                            <div class="col-md-4">
                                <input type="text" name="members[0][full_name]" class="form-control" placeholder="Full name" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="members[0][age]" class="form-control" placeholder="Age" required>
                            </div>
                            <div class="col-md-2">
                                <select name="members[0][gender]" class="form-select" required>
                                    <option value="">Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="members[0][needs]" class="form-control" placeholder="Individual needs (optional)">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-member" disabled>
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-member-btn">
                        <i class="fas fa-plus"></i> Add Family Member
                    </button>

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

@push('scripts')
<script>
    // Pre-select evacuation center when opened from table button
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
    document.getElementById('add-member-btn').addEventListener('click', function() {
        const container = document.getElementById('family-members-container');
        const newRow = document.createElement('div');
        newRow.className = 'row g-2 mb-2 member-row';
        newRow.innerHTML = `
            <div class="col-md-4">
                <input type="text" name="members[${memberIndex}][full_name]" class="form-control" placeholder="Full name" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="members[${memberIndex}][age]" class="form-control" placeholder="Age" required>
            </div>
            <div class="col-md-2">
                <select name="members[${memberIndex}][gender]" class="form-select" required>
                    <option value="">Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="members[${memberIndex}][needs]" class="form-control" placeholder="Individual needs (optional)">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger btn-sm remove-member">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(newRow);
        memberIndex++;

        // Attach remove event
        newRow.querySelector('.remove-member').addEventListener('click', function() {
            newRow.remove();
        });
    });

    // Remove event for initial remove buttons (disabled on first)
    document.querySelectorAll('.remove-member:not(:disabled)').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.member-row').remove();
        });
    });

    // Real-time polling (simple AJAX refresh every 30s)
    async function refreshRealtime() {
        try {
            const res = await fetch("{{ route('typhoon.realtime') }}", {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            });
            if (!res.ok) return;
            const data = await res.json();

            if (data?.last_updated_label) {
                const badge = document.getElementById('rt_last_updated_badge');
                if (badge) badge.innerHTML = `<i class="fas fa-sync-alt me-1"></i> Updated: ${data.last_updated_label}`;
            }
            if (data?.flood_level !== undefined) {
                const el = document.getElementById('rt_flood_level');
                if (el) el.textContent = data.flood_level;
            }
            if (data?.flood_station !== undefined) {
                const el = document.getElementById('rt_flood_station');
                if (el) el.textContent = `Station: ${data.flood_station}`;
            }
            if (data?.typhoon_name !== undefined) {
                const el = document.getElementById('rt_typhoon_name');
                if (el) el.textContent = data.typhoon_name;
            }
            if (data?.typhoon_wind_speed !== undefined) {
                const el = document.getElementById('rt_typhoon_wind');
                if (el) el.textContent = `Wind: ${data.typhoon_wind_speed} km/h`;
            }
            if (data?.routes_status_html !== undefined) {
                const el = document.getElementById('rt_routes_status');
                if (el) el.innerHTML = data.routes_status_html;
            }
            if (data?.blocked_roads_label !== undefined) {
                const el = document.getElementById('rt_routes_blocked');
                if (el) el.textContent = data.blocked_roads_label;
            }
        } catch (e) {
            // silent fail
        }
    }

    refreshRealtime();
    setInterval(refreshRealtime, 30000);
</script>
@endpush

@endsection