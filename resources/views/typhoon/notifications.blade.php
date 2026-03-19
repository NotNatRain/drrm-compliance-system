@extends('layouts.app')

@section('title', 'Typhoon/Flood Notifications')
@section('hide_main_nav', '1')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --bg-dark: #0a1128;
        --card-bg: #ffffff;
        --card-header-bg: #0f2154ff;
        --accent-blue: #00d2ff;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --glass-border: rgba(0, 0, 0, 0.05);
    }

    body {
        background-color: var(--bg-dark) !important;
        background-image: radial-gradient(circle at 50% 50%, #112240 0%, #0a1128 100%);
        color: var(--text-dark);
        font-family: 'Space Grotesk', 'Inter', sans-serif;
    }

    h1, h2, h3, h4, h5, .card-header-custom, .stat-value, .fw-bold {
        font-family: 'Rajdhani', sans-serif;
        letter-spacing: 0.5px;
    }

    .container-fluid {
        padding: 0;
    }

    .notification-page-wrapper {
        padding: 2rem 2rem 0 2rem;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .dashboard-card {
        background: var(--card-bg);
        border: 1px solid var(--glass-border);
        border-radius: 20px 20px 0 0; /* More rounded top, flat bottom to touch edge */
        transition: transform 0.2s ease;
        flex-grow: 1;
        color: var(--text-dark);
        box-shadow: 0 -4px 30px rgba(0,0,0,0.15);
        overflow: hidden;
        margin-bottom: 0 !important;
        display: flex;
        flex-direction: column;
    }

    .card-header-custom {
        background: var(--card-header-bg);
        color: #ffffff;
        padding: 1rem 1.5rem;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        border-bottom: 2px solid rgba(0,0,0,0.1);
    }

    .notification-item {
        border-bottom: 1px solid #f1f5f9;
        padding: 1.5rem;
        transition: all 0.2s ease;
        position: relative;
    }

    .notification-item:hover {
        background-color: #f8fafc;
    }

    .notification-item.unread {
        background-color: #f0f9ff;
        border-left: 4px solid var(--accent-blue);
    }

    .notification-icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .icon-announcement { background-color: #e0f2fe; color: #0ea5e9; }
    .icon-update { background-color: #f0fdf4; color: #22c55e; }
    .icon-alert { background-color: #fef2f2; color: #ef4444; }

    .btn-mark-read {
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 50px;
    }
    
    h1, h2, h3, h5 {
        color: #ffffff !important;
    }

    .dashboard-card h1, .dashboard-card h2, .dashboard-card h3, .dashboard-card h5, .dashboard-card .h3 {
        color: var(--text-dark) !important;
    }

    /* Centered Navigation */
    .header-nav-center {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        background: rgba(255, 255, 255, 0.05);
        padding: 0.5rem 2rem;
        border-radius: 50px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
    }

    .nav-link-custom {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        background: none;
        border: none;
        padding: 0.5rem 0.25rem;
    }

    .nav-link-custom:hover {
        color: var(--accent-blue);
    }

    .nav-link-custom.active {
        color: var(--accent-blue);
        text-shadow: 0 0 15px rgba(0, 210, 255, 0.5);
    }

    .notif-btn-custom {
        position: relative;
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.25rem;
        transition: all 0.3s ease;
    }

    .notif-btn-custom:hover, .notif-btn-custom.active {
        color: var(--accent-blue);
    }

    .school-btn-custom {
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.25rem;
        transition: all 0.3s ease;
        background: none;
        border: none;
    }

    .school-btn-custom:hover, .school-btn-custom.active {
        color: var(--accent-blue);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="notification-page-wrapper">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-5">
            {{-- Left Side --}}
            <div class="d-flex align-items-center" style="width: 30%;">
                <a href="{{ route('typhoon.dashboard') }}" class="btn btn-outline-info border-0 me-3 shadow-sm" style="background: rgba(255,255,255,0.05);" title="Back">
                    <i class="fas fa-chevron-left fa-lg text-white"></i>
                </a>
                <div>
                    <h1 class="h3 mb-0 fw-bold text-white">
                        Typhoon/Flood Notifications hub
                    </h1>
                    <div class="small text-white-50 mt-1">Live dispatch & system intelligence</div>
                </div>
            </div>

            {{-- Centered Navigation --}}
            <div class="header-nav-center">
                <a href="{{ route('typhoon.dashboard') }}" class="nav-link-custom">
                    Dashboard
                </a>
                <a href="{{ route('typhoon.notifications') }}" class="notif-btn-custom active" title="Notifications">
                    <i class="fas fa-bell"></i>
                    @php
                        $unreadCount = \App\Models\FireSafetyNotification::forCompliance('typhoon_flood')->unread()->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.6rem; padding: 0.35em 0.65em;">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    @endif
                </a>
                <button type="button" class="school-btn-custom" data-bs-toggle="modal" data-bs-target="#chooseSchoolModal" title="Choose Evacuation Center">
                    <i class="fas fa-school"></i>
                </button>
            </div>

            {{-- Right Side --}}
            <div class="d-flex align-items-center gap-3 justify-content-end" style="width: 30%;">
                 <form action="{{ route('typhoon.notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-lg" style="border-radius: 8px;">
                        <i class="fas fa-check-double me-2"></i> MARK ALL AS READ
                    </button>
                </form>
            </div>
        </div>

        <div class="row flex-grow-1">
            <div class="col-12 h-100 d-flex">
                <div class="dashboard-card shadow-lg border-0 d-flex flex-column w-100">
                    <div class="card-header-custom py-3">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <span><i class="fas fa-list-ul me-2"></i> RECENT UPDATES & ANNOUNCEMENTS</span>
                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2" style="font-size: 0.7rem;">LIVE FEED</span>
                        </div>
                    </div>
                    
                    <div class="notifications-list flex-grow-1 overflow-auto" style="height: calc(100% - 130px); max-height: 75vh;">
                        @forelse($notifications as $notif)
                            <div class="notification-item {{ $notif->is_read ? '' : 'unread' }} border-0 border-bottom" id="notif-{{ $notif->id }}">
                                <div class="d-flex gap-4">
                                    @php
                                        $iconClass = 'icon-update';
                                        $icon = 'fas fa-sync-alt';
                                        if($notif->type === 'announcement') {
                                            $iconClass = 'icon-announcement';
                                            $icon = 'fas fa-bullhorn';
                                        } elseif($notif->type === 'alert' || (isset($notif->action_data['urgency']) && $notif->action_data['urgency'] === 'danger')) {
                                            $iconClass = 'icon-alert';
                                            $icon = 'fas fa-exclamation-triangle';
                                        }
                                    @endphp
                                    <div class="notification-icon {{ $iconClass }} shadow-sm">
                                        <i class="{{ $icon }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="fw-bold mb-0 {{ $notif->is_read ? 'text-dark' : 'text-primary' }}" style="font-family: 'Rajdhani', sans-serif; font-size: 1.25rem; letter-spacing: 0.5px;">
                                                {{ strtoupper($notif->title) }}
                                            </h6>
                                            <div class="text-end">
                                                <div class="small text-muted fw-bold"><i class="far fa-clock me-1"></i> {{ $notif->created_at->format('M d, Y') }}</div>
                                                <div class="small text-muted opacity-75" style="font-size: 0.65rem;">{{ $notif->created_at->format('h:i A') }} ({{ $notif->created_at->diffForHumans() }})</div>
                                            </div>
                                        </div>
                                        <p class="mb-3 text-muted" style="font-size: 1.05rem; line-height: 1.6;">{{ $notif->message }}</p>
                                        
                                        <div class="d-flex align-items-center justify-content-between pt-2">
                                            <div class="d-flex align-items-center gap-3">
                                                @if($notif->school)
                                                    <span class="badge bg-light text-dark border px-3 py-2"><i class="fas fa-school me-2 text-info"></i>{{ $notif->school->school_name }}</span>
                                                @else
                                                    <span class="badge bg-light text-dark border px-3 py-2"><i class="fas fa-satellite me-2 text-primary"></i>SYSTEM DISPATCH</span>
                                                @endif
                                                
                                                @if($notif->user)
                                                    <span class="text-muted small fw-bold"><i class="fas fa-id-badge me-1"></i> LOGGED BY: {{ strtoupper($notif->user->name) }}</span>
                                                @endif
                                            </div>
                                            
                                            <div class="d-flex gap-2">
                                                @if($notif->action_url)
                                                    <a href="{{ $notif->action_url }}" class="btn btn-sm btn-info text-white rounded-pill px-4 fw-bold shadow-sm">
                                                        ACCESS INTELLIGENCE
                                                    </a>
                                                @endif
                                                
                                                @if(!$notif->is_read)
                                                    <button onclick="markAsRead({{ $notif->id }})" class="btn btn-sm btn-outline-secondary rounded-pill px-4 fw-bold">
                                                        SET AS READ
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-5 text-center opacity-50 d-flex flex-column align-items-center justify-content-center h-100">
                                <div class="bg-light p-4 rounded-circle mb-4">
                                    <i class="fas fa-bell-slash fa-5x text-muted"></i>
                                </div>
                                <h3 class="fw-bold">CLEAR FEED</h3>
                                <p class="text-muted fs-5">There are no recent alerts or announcements in the system.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($notifications->hasPages())
                        <div class="p-4 border-top bg-light">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function markAsRead(id) {
        fetch(`/typhoon/notifications/${id}/mark-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const item = document.getElementById(`notif-${id}`);
                item.classList.remove('unread');
                item.querySelector('button[onclick^="markAsRead"]').remove();
            }
        });
    }
</script>
@endpush
