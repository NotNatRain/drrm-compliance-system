@extends('layouts.app')

@section('title', 'Activity Log - DRRM Compliance')

@push('styles')
<style>
    /* Activity Log Redesign */
    :root {
        --um-navy: #0D1B36;
        --um-blue: #1E3A5F;
        --um-accent: #2563EB;
        --um-bg: #F8FAFC;
        --um-border: #E2E8F0;
        --um-text: #374151;
        --um-text-light: #6B7280;
    }
    
    /* Filters Card */
    .um-filters-card {
        background: white;
        border: 1px solid var(--um-border);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        animation: fadeInUp 0.4s ease-out 0.1s both;
    }
    .um-filters-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--um-navy);
        font-weight: 800;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }
    .um-filters-header i {
        color: #EA580C;
    }
    .um-filter-label {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--um-text);
        margin-bottom: 0.5rem;
    }
    .um-form-control, .um-form-select {
        border-radius: 8px;
        border: 1px solid var(--um-border);
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        color: var(--um-text);
        box-shadow: none;
        width: 100%;
        background-color: white;
    }
    .um-form-select {
        padding-right: 2.5rem;
    }
    .um-form-control:focus, .um-form-select:focus {
        border-color: var(--um-accent);
        outline: none;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    }
    .input-icon-wrap {
        position: relative;
    }
    .input-icon-wrap i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9CA3AF;
        pointer-events: none;
    }
    .input-icon-wrap .um-form-control {
        padding-left: 2.5rem;
    }
    
    .btn-apply-filter {
        background: var(--um-navy);
        color: white;
        border: none;
        padding: 0.6rem 2rem;
        border-radius: 8px;
        font-weight: 700;
        transition: all 0.2s;
        height: 42px;
    }
    .btn-apply-filter:hover {
        background: var(--um-blue);
        color: white;
    }
    .btn-reset-filter {
        background: white;
        color: var(--um-text);
        border: 1px solid var(--um-border);
        padding: 0.6rem 2rem;
        border-radius: 8px;
        font-weight: 700;
        transition: all 0.2s;
        height: 42px;
    }
    .btn-reset-filter:hover {
        background: var(--um-bg);
    }

    /* Table */
    .um-table-card {
        background: white;
        border: 1px solid var(--um-border);
        border-radius: 12px;
        overflow: hidden;
        animation: fadeInUp 0.4s ease-out 0.2s both;
    }
    .um-table {
        margin: 0;
        width: 100%;
        border-collapse: collapse;
    }
    .um-table th {
        background: transparent;
        color: var(--um-text-light);
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.9rem 1rem;
        border-bottom: 1px solid var(--um-border);
    }
    .um-table td {
        padding: 0.8rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--um-border);
        color: var(--um-text);
        font-size: 0.8rem;
        transition: background 0.2s;
    }
    .um-table tbody tr:hover td {
        background: #F8FAFC;
    }
    .um-table tbody tr:last-child td {
        border-bottom: none;
    }

    .um-date-main {
        font-weight: 700;
        font-size: 0.8rem;
        color: var(--um-text);
        line-height: 1.2;
    }
    .um-date-time {
        font-size: 0.72rem;
        color: var(--um-text-light);
        margin-top: 1px;
    }

    /* User Avatar/Info */
    .um-user-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .um-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--um-navy);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.8rem;
        flex-shrink: 0;
    }
    .um-username {
        font-weight: 600;
        color: var(--um-text);
    }

    /* Activity Icon */
    .activity-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .activity-icon {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        flex-shrink: 0;
    }
    .activity-icon.yellow { background: #FEF3C7; color: #D97706; }
    .activity-icon.red { background: #FEE2E2; color: #DC2626; }
    .activity-icon.blue { background: #E0F2FE; color: #0284C7; }
    .activity-icon.green { background: #DCFCE7; color: #16A34A; }
    .activity-icon.gray { background: #F1F5F9; color: #475569; }

    /* Pills */
    .um-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 0.2rem 0.65rem;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
    }
    .um-pill-admin { background: #FEE2E2; color: #DC2626; }
    .um-pill-contrib { background: #DCFCE7; color: #16A34A; }
    .um-pill-viewer { background: #E0F2FE; color: #0284C7; }
    .um-pill-module {
        display: inline-block;
        background: #EFF4FB;
        color: #374151;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.2rem 0.6rem;
        border-radius: 5px;
    }
    .um-pill-notes { background: #DCFCE7; color: #16A34A; font-size: 0.72rem; font-weight: 700; padding: 0.2rem 0.65rem; border-radius: 20px; display: inline-block; }

    /* Pagination Redesign */
    .um-pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.85rem 1.25rem;
        background: white;
        border-top: 1px solid var(--um-border);
        border-radius: 0 0 12px 12px;
    }
    .um-pagination-info {
        color: var(--um-text-light);
        font-size: 0.8rem;
    }
    .um-pager {
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .um-pager a, .um-pager span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 8px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--um-navy);
        text-decoration: none;
        border: 1px solid var(--um-border);
        background: white;
        transition: all 0.15s;
    }
    .um-pager a:hover {
        background: #F1F5F9;
    }
    .um-pager .um-pager-active {
        background: var(--um-navy);
        color: white;
        border-color: var(--um-navy);
    }
    .um-pager .um-pager-disabled {
        color: #CBD5E1;
        pointer-events: none;
        border-color: #E2E8F0;
    }
    .um-pager-prev, .um-pager-next {
        font-weight: 700 !important;
        letter-spacing: 0.01em;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 px-lg-5 py-4">

    <!-- Filters -->
    <div class="um-filters-card mt-3">
        <div class="um-filters-header">
            <i class="fas fa-filter"></i> Filters
        </div>
        <form method="get" action="{{ route('activity-logs.index') }}">
            <div class="row g-3 mb-3">
                <div class="col-md-2">
                    <div class="um-filter-label">Date From</div>
                    <input type="date" class="um-form-control" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <div class="um-filter-label">Date To</div>
                    <input type="date" class="um-form-control" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <div class="um-filter-label">User</div>
                    <select class="um-form-select" name="user_id">
                        <option value="">All Users</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="um-filter-label">Role</div>
                    <select class="um-form-select" name="role">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="contributor" {{ request('role') === 'contributor' ? 'selected' : '' }}>Contributor</option>
                        <option value="viewer" {{ request('role') === 'viewer' ? 'selected' : '' }}>Viewer</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="um-filter-label">Module</div>
                    <select class="um-form-select" name="module">
                        <option value="">All Modules</option>
                        @foreach($modules as $key => $label)
                            <option value="{{ $key }}" {{ request('module') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="um-filter-label">School</div>
                    <div class="input-icon-wrap">
                        <i class="fas fa-school"></i>
                        <input type="text" class="um-form-control" name="school" value="{{ request('school') }}" placeholder="Name or ID">
                    </div>
                </div>
            </div>
            <div class="row g-3 align-items-end">
                <div class="col-md-9">
                    <div class="um-filter-label">Activity</div>
                    <div class="input-icon-wrap">
                        <i class="fas fa-search"></i>
                        <input type="text" class="um-form-control" name="activity" value="{{ request('activity') }}" placeholder="Search activity">
                    </div>
                </div>
                <div class="col-md-3 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn-apply-filter"><i class="fas fa-check me-2"></i> Apply</button>
                    <a href="{{ route('activity-logs.index') }}" class="btn btn-reset-filter text-decoration-none text-center">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Log Table -->
    <div class="um-table-card">
        <div class="table-responsive">
            <table class="um-table">
                <thead>
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
                        <td class="ps-4" style="white-space: nowrap;">
                            <div class="um-date-main">{{ $log->created_at->format('M d, Y') }}</div>
                            <div class="um-date-time">{{ $log->created_at->format('H:i') }}</div>
                        </td>
                        <td>
                            <div class="um-user-cell">
                                @php
                                    $name = $log->user?->name ?? 'Unknown';
                                    $initials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $name), 0, 2));
                                    if (empty($initials)) $initials = 'U';
                                @endphp
                                <div class="um-avatar">{{ $initials }}</div>
                                <div class="um-username">{{ $name }}</div>
                            </div>
                        </td>
                        <td>
                            @if($log->role === 'admin')
                                <span class="um-pill um-pill-admin">Admin</span>
                            @elseif($log->role === 'contributor')
                                <span class="um-pill um-pill-contrib">Contributor</span>
                            @else
                                <span class="um-pill um-pill-viewer">Viewer</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $actText = strtolower($log->activity);
                                $iconClass = 'gray';
                                $iconFa = 'fa-file-alt';
                                
                                if (str_contains($actText, 'drill') && str_contains($actText, 'earthquake')) {
                                    $iconClass = 'yellow'; $iconFa = 'fa-exclamation-triangle';
                                } elseif (str_contains($actText, 'drill') && str_contains($actText, 'fire')) {
                                    $iconClass = 'red'; $iconFa = 'fa-fire';
                                } elseif (str_contains($actText, 'evacuation center') || str_contains($actText, 'typhoon')) {
                                    $iconClass = 'blue'; $iconFa = 'fa-cloud-showers-water';
                                } elseif (str_contains($actText, 'decamped') || str_contains($actText, 'family')) {
                                    $iconClass = 'green'; $iconFa = 'fa-users';
                                } elseif (str_contains($actText, 'login') || str_contains($actText, 'logout')) {
                                    $iconClass = 'gray'; $iconFa = 'fa-sign-in-alt';
                                }
                            @endphp
                            <div class="activity-cell">
                                <div class="activity-icon {{ $iconClass }}">
                                    <i class="fas {{ $iconFa }}"></i>
                                </div>
                                <span class="fw-semibold text-dark">{{ $log->activity }}</span>
                            </div>
                        </td>
                        <td class="text-dark" style="font-size: 0.8rem;">{{ $log->school_display }}</td>
                        <td>
                            @if($log->module_label !== '—')
                                <span class="um-pill-module">{{ $log->module_label }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="pe-4">
                            @if($log->notes)
                                @if(str_contains(strtolower($log->notes), 'status: cleared'))
                                    <span class="um-pill um-pill-notes">Status: cleared</span>
                                @else
                                    <span class="text-muted small text-truncate-custom" style="max-width: 150px;" title="{{ $log->notes }}">{{ $log->notes }}</span>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">No activity logs found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->total() > 0)
        <div class="um-pagination-wrapper">
            <div class="um-pagination-info">
                Showing {{ $logs->count() }} of {{ $logs->total() }} entries
            </div>
            <div class="um-pager">
                {{-- Prev --}}
                @if($logs->onFirstPage())
                    <span class="um-pager-prev um-pager-disabled">Prev</span>
                @else
                    <a class="um-pager-prev" href="{{ $logs->previousPageUrl() }}">Prev</a>
                @endif

                {{-- Page numbers --}}
                @php
                    $currentPage = $logs->currentPage();
                    $lastPage = $logs->lastPage();
                    $start = max(1, $currentPage - 1);
                    $end = min($lastPage, $start + 2);
                    if ($end - $start < 2) $start = max(1, $end - 2);
                @endphp

                @for($p = $start; $p <= $end; $p++)
                    @if($p === $currentPage)
                        <span class="um-pager-active">{{ $p }}</span>
                    @else
                        <a href="{{ $logs->url($p) }}">{{ $p }}</a>
                    @endif
                @endfor

                {{-- Next --}}
                @if($logs->hasMorePages())
                    <a class="um-pager-next" href="{{ $logs->nextPageUrl() }}">Next</a>
                @else
                    <span class="um-pager-next um-pager-disabled">Next</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
