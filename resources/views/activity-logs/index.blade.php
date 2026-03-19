@extends('layouts.app')

@section('title', 'Activity Log - DRRM Compliance')

@push('styles')
<style>
    :root {
        --fire-red: #A8191F;
        --fire-dark-red: #8A1217;
    }
    /* Custom Pagination Styling */
    .pagination {
        gap: 5px;
    }
    .pagination .page-link {
        color: var(--fire-red) !important;
        border-radius: 6px !important;
        border: 1px solid #dee2e6 !important;
        padding: 0.25rem 0.6rem !important;
        font-size: 0.8rem !important;
        transition: all 0.2s !important;
    }
    .pagination .page-item.active .page-link {
        background-color: var(--fire-red) !important;
        border-color: var(--fire-red) !important;
        color: white !important;
    }
    /* Small arrows */
    .pagination .page-link i, 
    .pagination .page-link svg {
        width: 8px !important;
        height: 8px !important;
    }
    .pagination .page-item:hover:not(.active):not(.disabled) .page-link {
        background-color: var(--fire-dark-red) !important;
        border-color: var(--fire-dark-red) !important;
        color: white !important;
    }
    .pagination .page-item.disabled .page-link {
        color: #adb5bd !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-clipboard-list text-primary"></i> Activity Log
            </h1>
            <p class="text-muted small mb-0 mt-1">Track user actions across DRRM compliance modules.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-light">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="get" action="{{ route('activity-logs.index') }}" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Date From</label>
                    <input type="date" class="form-control form-control-sm" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Date To</label>
                    <input type="date" class="form-control form-control-sm" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">User</label>
                    <select class="form-select form-select-sm" name="user_id">
                        <option value="">All Users</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Role</label>
                    <select class="form-select form-select-sm" name="role">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="contributor" {{ request('role') === 'contributor' ? 'selected' : '' }}>Contributor</option>
                        <option value="viewer" {{ request('role') === 'viewer' ? 'selected' : '' }}>Viewer</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Module</label>
                    <select class="form-select form-select-sm" name="module">
                        <option value="">All Modules</option>
                        @foreach($modules as $key => $label)
                            <option value="{{ $key }}" {{ request('module') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">School</label>
                    <input type="text" class="form-control form-control-sm" name="school" value="{{ request('school') }}" placeholder="Name or ID">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Activity</label>
                    <input type="text" class="form-control form-control-sm" name="activity" value="{{ request('activity') }}" placeholder="Search activity">
                </div>
                <div class="col-md-2 d-flex align-items-end gap-1">
                    <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                    <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Log Table -->
    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Date</th>
                            <th>User</th>
                            <th>Role</th>
                            <th>Activity</th>
                            <th>School</th>
                            <th>Module</th>
                            <th class="pe-4">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td class="ps-4 text-nowrap">{{ $log->created_at->format('M d, Y H:i') }}</td>
                            <td>{{ $log->user?->name ?? '—' }}</td>
                            <td>
                                <span class="badge {{ $log->role === 'admin' ? 'bg-danger' : ($log->role === 'contributor' ? 'bg-success' : 'bg-info text-dark') }}">
                                    {{ ucfirst($log->role ?? '—') }}
                                </span>
                            </td>
                            <td>{{ $log->activity }}</td>
                            <td>{{ $log->school_name ?? ($log->school_id ? 'School #' . $log->school_id : '—') }}</td>
                            <td>{{ $log->module_label }}</td>
                            <td class="pe-4 text-muted small" style="max-width: 280px;">{{ \Illuminate\Support\Str::limit($log->notes, 80) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No activity logs found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($logs->hasPages())
            <div class="card-footer py-2">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
