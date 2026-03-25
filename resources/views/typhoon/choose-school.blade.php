{{-- resources/views/typhoon/choose-school.blade.php --}}
@extends('layouts.app')

@section('title', 'Choose School - Typhoon/Flooding Monitoring')
@section('hide_main_nav', '1')

@push('styles')
<style>
    .tf-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 16px;
    }
    .tf-title {
        color: #1B4C6D;
        margin: 0;
    }
    .tf-nav .nav-link.active {
        background-color: #1B4C6D !important;
        color: white !important;
    }
    .tf-badge {
        background: #e8f4ff;
        color: #1B4C6D;
        border: 1px solid #cfe8ff;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="tf-header">
        <a href="{{ route('typhoon.dashboard') }}" class="btn btn-outline-secondary border-0 p-2" title="Back">
            <i class="fas fa-arrow-left fa-lg"></i>
        </a>

        <div class="d-flex align-items-center gap-3">
            <nav class="nav nav-pills tf-nav">
                <a class="nav-link text-dark" href="{{ route('typhoon.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                </a>
                <a class="nav-link active" href="{{ route('typhoon.choose-school') }}">
                    <i class="fas fa-school me-1"></i> Choose School
                </a>
            </nav>
            <span class="badge tf-badge">
                <i class="fas fa-user-shield me-1"></i>
                {{ ucfirst(Auth::user()->role ?? 'user') }}
            </span>
        </div>
    </div>

    <div class="mb-3">
        <h1 class="h3 tf-title">
            <i class="fas fa-umbrella"></i> Typhoon/Flooding Monitoring
        </h1>
        <p class="text-muted mb-0">Choose the evacuation center (school) to manage and monitor.</p>
    </div>

    <div class="card shadow">
        <div class="card-header" style="background-color: #1B4C6D; color: white;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-school"></i> Evacuation Centers / Schools</h5>
                <small class="opacity-75">Admin can view all, contributors see only their own</small>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>School</th>
                            <th>Identification</th>
                            <th>Location</th>
                            <th>Capacity</th>
                            <th>Occupancy</th>
                            <th>Occupancy Safety</th>
                            <th>Emergency Resources Usage</th>
                            <th>Status & Monitoring</th>
                            <th>Reports</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schools as $school)
                            @php
                                $ec = $school->typ_ec;
                                $cap = (int) ($ec->capacity ?? 0);
                                $occ = (int) ($school->typ_ec_current_occupancy ?? 0);
                                $safety = $ec->occupancy_safety ?? 'safe';
                                if ($cap > 0) {
                                    $ratio = $occ / max($cap, 1);
                                    if ($ratio >= 0.9) $safety = 'critical';
                                    elseif ($ratio >= 0.7) $safety = 'warning';
                                    else $safety = 'safe';
                                }
                            @endphp
                            <tr>
                                <td class="fw-bold">{{ $school->school_name }}</td>
                                <td>{{ $ec->identification ?? $school->school_id ?? '-' }}</td>
                                <td style="min-width: 220px;">{{ $ec->location ?? $school->address ?? '-' }}</td>
                                <td>{{ $cap }}</td>
                                <td>{{ $occ }}</td>
                                <td>
                                    @if($safety === 'critical')
                                        <span class="badge bg-danger">Critical</span>
                                    @elseif($safety === 'warning')
                                        <span class="badge bg-warning text-dark">Warning</span>
                                    @else
                                        <span class="badge bg-success">Safe</span>
                                    @endif
                                </td>
                                <td>
                                    @if($ec->emergency_resources_usage_status)
                                        <span class="badge bg-info">{{ $ec->emergency_resources_usage_status }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $ec->operational_status ?? 'operational' }}</span>
                                    <span class="badge bg-primary">{{ $ec->monitoring_status ?? 'Active' }}</span>
                                </td>
                                <td>
                                    @if($ec->reports_status)
                                        <span class="badge bg-secondary">{{ $ec->reports_status }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm {{ ($activeSchoolId ?? null) == $school->id ? 'btn-success' : 'btn-outline-primary' }}"
                                            onclick="setActiveTyphoonSchool({{ $school->id }})">
                                        <i class="fas fa-check"></i>
                                        {{ ($activeSchoolId ?? null) == $school->id ? 'Active' : 'Select' }}
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    <i class="fas fa-school fa-2x mb-2"></i><br>
                                    No schools found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    async function setActiveTyphoonSchool(id) {
        try {
            const res = await fetch(`{{ url('/typhoon/set-school') }}/${id}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            if (!res.ok) {
                const j = await res.json().catch(() => ({}));
                alert(j.message || 'Failed to select school.');
                return;
            }
            window.location.href = "{{ route('typhoon.dashboard') }}";
        } catch (e) {
            alert('Failed to select school.');
        }
    }
</script>
@endpush
@endsection

