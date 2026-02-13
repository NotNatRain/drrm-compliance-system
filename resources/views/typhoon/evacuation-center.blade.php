@extends('layouts.app')

@section('title', 'Evacuation Center Details')
@section('hide_main_nav', '1')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('typhoon.dashboard') }}" class="btn btn-outline-secondary border-0 p-2">
            <i class="fas fa-arrow-left fa-lg"></i>
        </a>
        <h1 class="h5 mb-0" style="color:#1B4C6D;">
            <i class="fas fa-school"></i>
            {{ $ec->school->school_name ?? $ec->identification ?? ('Evacuation Center #' . $ec->id) }}
        </h1>
    </div>

    <div class="row mb-3">
        <div class="col-md-8">
            <div class="card shadow mb-3">
                <div class="card-header" style="background-color:#1B4C6D;color:white;">
                    <h5 class="mb-0">Evacuation Center Profile</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Identification</strong></div>
                        <div class="col-sm-8">{{ $ec->identification ?? '—' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Location</strong></div>
                        <div class="col-sm-8">{{ $ec->location ?? $ec->school->address ?? '—' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Capacity</strong></div>
                        <div class="col-sm-8">{{ $ec->capacity ?? 0 }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Occupancy</strong></div>
                        <div class="col-sm-8">{{ $currentOccupancy }} individuals</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Emergency Resources</strong></div>
                        <div class="col-sm-8">{{ $ec->emergency_resources ?? 'To be encoded' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm_4"><strong>Usage Status</strong></div>
                        <div class="col-sm-8">
                            @if($ec->usage_status === 'full')
                                <span class="badge bg-danger">Full</span>
                            @elseif($ec->usage_status === 'occupied')
                                <span class="badge bg-warning text-dark">Occupied</span>
                            @else
                                <span class="badge bg-success">Cleared</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Monitoring &amp; Reports</strong></div>
                        <div class="col-sm-8">{{ $ec->reports_status ?? 'No reports yet' }}</div>
                    </div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updateCenterStatusModal">
                            <i class="fas fa-edit me-1"></i> Update Status / Reports
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-3">
                <div class="card-header" style="background-color:#1B4C6D;color:white;">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Last Usage</h5>
                </div>
                <div class="card-body">
                    @if($lastUsedAt)
                        <p class="mb-1"><strong>Last used as evacuation:</strong></p>
                        <p class="mb-2">{{ $lastUsedAt->format('M d, Y H:i') }}</p>
                        <p class="mb-0">
                            <strong>Current Monitoring &amp; Reports:</strong><br>
                            <small>{{ $ec->reports_status ?? 'No reports yet' }}</small>
                        </p>
                    @else
                        <p class="text-muted mb-0">This center has not yet been used as an evacuation site.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- History footer --}}
    <div class="card shadow">
        <div class="card-header" style="background-color:#1B4C6D;color:white;">
            <h5 class="mb-0"><i class="fas fa-people-arrows"></i> Evacuation History (Families)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Family Head</th>
                            <th>Members</th>
                            <th>Special Concerns</th>
                            <th>Collective Needs</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($families as $family)
                            <tr>
                                <td>{{ $family->created_at->format('M d, Y H:i') }}</td>
                                <td>{{ $family->head_family_name }}</td>
                                <td>{{ $family->members_count }}</td>
                                <td>
                                    @php $flags = []; @endphp
                                    @if($family->has_pregnant) @php $flags[] = 'Pregnant'; @endphp @endif
                                    @if($family->has_pwd) @php $flags[] = 'PWD'; @endphp @endif
                                    @if($family->has_senior) @php $flags[] = 'Senior'; @endphp @endif
                                    @if($family->has_lactating) @php $flags[] = 'Lactating'; @endphp @endif
                                    @if($family->has_child_under5) @php $flags[] = 'Child &lt;5'; @endphp @endif
                                    <small>{{ implode(', ', $flags) ?: 'None' }}</small>
                                </td>
                                <td><small>{{ $family->collective_needs ?? '—' }}</small></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">
                                    No families have been registered for this center yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Update status / reports modal --}}
<div class="modal fade" id="updateCenterStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('typhoon.evacuation-center.update', $ec->id) }}">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header" style="background-color:#1B4C6D;color:white;">
                    <h5 class="modal-title">Update Status &amp; Reports</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Usage Status</label>
                        <select name="usage_status" class="form-select">
                            <option value="cleared" @selected($ec->usage_status === 'cleared')>Cleared</option>
                            <option value="occupied" @selected($ec->usage_status === 'occupied')>Occupied</option>
                            <option value="full" @selected($ec->usage_status === 'full')>Full</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Emergency Resources (summary)</label>
                        <textarea name="emergency_resources" rows="2" class="form-control">{{ old('emergency_resources', $ec->emergency_resources) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Monitoring &amp; Reports</label>
                        <textarea name="reports_status" rows="3" class="form-control">{{ old('reports_status', $ec->reports_status) }}</textarea>
                        <small class="text-muted">Short narrative on current situation (e.g., issues, status of classrooms, damages).</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background-color:#1B4C6D;color:white;">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

