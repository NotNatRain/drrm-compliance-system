{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard - DRRM Compliance')

@push('styles')
<style>
    /* Announcement Ribbon & Offcanvas Styles */
    .announcement-ribbon {
        position: absolute;
        top: 0;
        left: 30px;
        background: linear-gradient(135deg, var(--orange, #E05C2E), #FF7A45);
        color: white;
        padding: 12px 15px 15px;
        border-radius: 0 0 12px 12px;
        cursor: pointer;
        z-index: 1040;
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 4px 10px rgba(224, 92, 46, 0.3);
    }
    .announcement-ribbon:hover {
        padding-top: 20px;
        background: linear-gradient(135deg, #FF7A45, var(--orange, #E05C2E));
    }
    .announcement-ribbon .ringing-bell {
        font-size: 1.5rem;
        margin-bottom: 5px;
        transform-origin: top center;
        animation: swing 2s ease-in-out infinite;
    }
    .announcement-ribbon .ribbon-text {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    @keyframes swing {
        0% { transform: rotate(0deg); }
        10% { transform: rotate(15deg); }
        20% { transform: rotate(-10deg); }
        30% { transform: rotate(5deg); }
        40% { transform: rotate(-5deg); }
        50%, 100% { transform: rotate(0deg); }
    }
    .delete-announcement-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
        background: rgba(220, 53, 69, 0.9);
        border: none;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .delete-announcement-btn:hover {
        background: #dc3545;
        transform: scale(1.1);
    }
    /* Dashboard Wireframe Styles */
    .dashboard-welcome {
        font-size: 1rem;
        color: #64748B;
        margin-bottom: 1.5rem;
    }
    .dashboard-welcome strong { color: var(--navy); }

    /* System Overview Card */
    .overview-card {
        background: #FFFFFF;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        border: 1px solid #E2E8F0;
        margin-bottom: 2rem;
        overflow: hidden;
    }
    .overview-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #E2E8F0;
        font-weight: 700;
        color: var(--navy);
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .overview-stats-grid {
        display: flex;
        flex-wrap: wrap;
    }
    .stat-item {
        flex: 1 1 25%;
        padding: 2rem 1rem;
        text-align: center;
        border-right: 1px solid #E2E8F0;
    }
    .stat-item:last-child { border-right: none; }
    @media (max-width: 768px) {
        .stat-item { flex: 1 1 50%; border-bottom: 1px solid #E2E8F0; }
        .stat-item:nth-child(2n) { border-right: none; }
    }
    .stat-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.25rem;
    }
    /* Stat Colors */
    .stat-schools { background: #E0E7FF; color: #4F46E5; }
    .stat-users { background: #DCFCE7; color: #16A34A; }
    .stat-compliance { background: #FEF3C7; color: #D97706; }
    .stat-population { background: #E0F2FE; color: #0284C7; }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: var(--navy);
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    .stat-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Tabs (Pill style) */
    .dashboard-tabs-wrapper {
        margin-bottom: 2rem;
    }
    .custom-tabs-container {
        display: inline-flex;
        background: #FFFFFF;
        border-radius: 12px;
        padding: 6px;
        border: 1px solid #E2E8F0;
        gap: 8px;
    }
    .nav-tab-item {
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .nav-tab-item.active {
        background: var(--navy);
        color: #FFFFFF;
    }
    .nav-tab-item:not(.active) {
        color: #64748B;
    }
    .nav-tab-item:hover:not(.active) {
        background: #F1F5F9;
        color: var(--navy);
    }

    /* Module Cards */
    .modules-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }
    .module-card-link {
        text-decoration: none;
        display: block;
    }
    .module-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 2.5rem 2rem;
        text-align: center;
        border: 1px solid #E2E8F0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        height: 100%;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }
    .module-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .module-icon-wrap {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 1.75rem;
    }
    .module-title {
        font-size: 1.25rem;
        font-weight: 800;
        margin-bottom: 1rem;
    }
    .module-desc {
        font-size: 0.9rem;
        color: #64748B;
        line-height: 1.6;
        margin-bottom: 2rem;
        flex-grow: 1;
    }
    .module-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        color: #FFFFFF;
        text-decoration: none;
        margin: 0 auto;
        transition: filter 0.2s;
    }
    .module-card:hover .module-btn { filter: brightness(1.1); }

    /* Module specific colors */
    .mod-fire { --mc: #D12428; --mbg: #FEE2E2; }
    .mod-evac { --mc: #1B4C6D; --mbg: #E0F2FE; }
    .mod-inc { --mc: #D97706; --mbg: #FEF3C7; } /* Wireframe is gold */
    .mod-css { --mc: #5C4033; --mbg: #F5EAE6; } /* Brown */
    .mod-drill { --mc: #EA580C; --mbg: #FFEDD5; } /* Orange */
    .mod-haz { --mc: #0D7377; --mbg: #CCFBF1; } /* Teal */
    .mod-dam { --mc: #8B5CF6; --mbg: #EDE9FE; } /* Purple */
    .mod-inv { --mc: #0891B2; --mbg: #CFFAFE; } /* Cyan */

    .mod-fire .module-icon-wrap { background: var(--mbg); color: var(--mc); }
    .mod-fire .module-title { color: var(--mc); }
    .mod-fire .module-btn { background: var(--mc); }

    .mod-evac .module-icon-wrap { background: var(--mbg); color: var(--mc); }
    .mod-evac .module-title { color: var(--mc); }
    .mod-evac .module-btn { background: var(--mc); }

    .mod-inc .module-icon-wrap { background: var(--mbg); color: var(--mc); }
    .mod-inc .module-title { color: var(--mc); }
    .mod-inc .module-btn { background: var(--mc); }

    .mod-css .module-icon-wrap { background: var(--mbg); color: var(--mc); }
    .mod-css .module-title { color: var(--mc); }
    .mod-css .module-btn { background: var(--mc); }

    .mod-drill .module-icon-wrap { background: var(--mbg); color: var(--mc); }
    .mod-drill .module-title { color: var(--mc); }
    .mod-drill .module-btn { background: var(--mc); }

    .mod-haz .module-icon-wrap { background: var(--mbg); color: var(--mc); }
    .mod-haz .module-title { color: var(--mc); }
    .mod-haz .module-btn { background: var(--mc); }

    .mod-dam .module-icon-wrap { background: var(--mbg); color: var(--mc); }
    .mod-dam .module-title { color: var(--mc); }
    .mod-dam .module-btn { background: var(--mc); }

    .mod-inv .module-icon-wrap { background: var(--mbg); color: var(--mc); }
    .mod-inv .module-title { color: var(--mc); }
    .mod-inv .module-btn { background: var(--mc); }

    .btn-disabled { opacity: 0.6; cursor: not-allowed; }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

    @section('content')
<div class="container-fluid px-4 px-lg-5 position-relative">
    @php
        $user = auth()->user();
        $modules = $user?->module_access ?? [];
        $isAdmin = $user && $user->role === 'admin';
        $isContributor = $user && $user->role === 'contributor';
        $canShowSchoolTab = $isAdmin || $isContributor;
    @endphp

        <!-- Announcements Offcanvas -->
        <div class="offcanvas offcanvas-start border-0 shadow" tabindex="-1" id="announcementsOffcanvas" aria-labelledby="announcementsOffcanvasLabel" style="width: 400px; max-width: 90vw;">
            <div class="offcanvas-header bg-white border-bottom py-3 px-4">
                <h5 class="offcanvas-title fw-bolder mb-0" id="announcementsOffcanvasLabel" style="font-family: var(--font-display, 'Sora', sans-serif); color: var(--navy, #0D1B36);">
                    <i class="fas fa-bullhorn me-2 text-primary"></i> System Announcements
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0 bg-light position-relative">
                @if($announcements->count() > 0)
                    <div id="announcementCarousel" class="carousel slide h-100" data-bs-ride="carousel" data-bs-interval="5000">
                        <div class="carousel-inner h-100">
                            @foreach($announcements as $index => $announcement)
                                <div class="carousel-item h-100 {{ $index === 0 ? 'active' : '' }}">
                                    @if(Auth::user()->role === 'admin')
                                        <button class="delete-announcement-btn shadow-sm" onclick="deleteAnnouncement({{ $announcement->id }})" title="Remove Announcement">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endif
                                    <div class="announcement-card-wrap p-4 h-100 d-flex flex-column">
                                        <div class="announcement-poster-wrap rounded-3 shadow-sm overflow-hidden mb-4 position-relative" style="padding-top: 100%; background: #fff;">
                                            <img src="{{ asset('storage/' . $announcement->image_path) }}" class="position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover;" alt="Announcement Poster">
                                        </div>
                                        <div class="announcement-content-text px-2 flex-grow-1">
                                            <p class="text-muted small mb-1 fw-bold">
                                                <i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($announcement->when)->format('M j, Y h:i A') }}
                                            </p>
                                            <p class="text-muted small mb-3 fw-bold">
                                                <i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $announcement->where }}
                                            </p>
                                            <h4 class="fw-bolder text-dark mb-3" style="font-family: var(--font-display, 'Sora', sans-serif);">{{ $announcement->what }}</h4>
                                            <p class="text-secondary" style="line-height: 1.6;">{{ $announcement->why }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($announcements->count() > 1)
                            <div class="carousel-indicators mb-2" style="position: absolute; bottom: 10px;">
                                @foreach($announcements as $index => $announcement)
                                    <button type="button" data-bs-target="#announcementCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }} bg-primary" aria-label="Slide {{ $index + 1 }}"></button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="d-flex flex-column align-items-center justify-content-center h-100 p-5 text-center" style="color: #94A3B8;">
                        <i class="fas fa-bell-slash mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                        <h5 class="fw-bold mb-2" style="color: var(--navy, #0D1B36); font-family: var(--font-display, 'Sora', sans-serif);">All Caught Up!</h5>
                        <p class="small mb-0" style="font-family: var(--font-body, 'Inter', sans-serif);">There are no active announcements at the moment. Check back later.</p>
                    </div>
                @endif
            </div>
        </div>

    <div class="dashboard-welcome mt-4">
        Welcome back, <strong>{{ Auth::user()->name }}</strong>! Select a compliance system to manage.
    </div>

    <!-- Toast Container for Notifications -->
    <div class="toast-container position-fixed bottom-0 end-0 p-4" style="z-index: 1080;">
        @if(session('success'))
            <div id="successToast" class="toast align-items-center bg-white border-0 shadow-lg" style="border-radius: 12px;" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body fw-semibold py-3 px-4" style="color: var(--navy, #0D1B36); font-family: var(--font-body, 'Inter', sans-serif);">
                        <i class="fas fa-check-circle me-2 text-success"></i> {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close me-3 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div id="errorToast" class="toast align-items-center bg-white border-0 shadow-lg" style="border-radius: 12px;" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body fw-semibold py-3 px-4" style="color: var(--navy, #0D1B36); font-family: var(--font-body, 'Inter', sans-serif);">
                        <i class="fas fa-exclamation-triangle me-2 text-danger"></i> {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close me-3 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    <!-- System Overview -->
    <div class="overview-card">
        <div class="overview-header">
            <i class="fas fa-chart-line"></i> System Overview
        </div>
        <div class="overview-stats-grid">
            <div class="stat-item">
                <div class="stat-icon-wrapper stat-schools">
                    <i class="fas fa-school"></i>
                </div>
                <div class="stat-value">{{ $totalSchoolsCount }}</div>
                <div class="stat-label">Total Schools</div>
            </div>
            <div class="stat-item">
                <div class="stat-icon-wrapper stat-users">
                    <i class="fas fa-user-friends"></i>
                </div>
                <div class="stat-value">{{ $totalUsersCount }}</div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-item">
                <div class="stat-icon-wrapper stat-compliance">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div class="stat-value">{{ $overallComplianceRate }}%</div>
                <div class="stat-label">Compliance Rate</div>
            </div>
            <div class="stat-item">
                <div class="stat-icon-wrapper stat-population">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value">{{ number_format($totalPopulationCount) }}</div>
                <div class="stat-label">Population</div>
            </div>
        </div>
    </div>

    @if($canShowSchoolTab)
        <div class="dashboard-tabs-wrapper">
            <div class="custom-tabs-container">
                <div id="complianceTabBtn" class="nav-tab-item active" onclick="switchDashboardTab('compliance')">
                    <i class="fas fa-th-large"></i> Compliance
                </div>
                <div id="schoolsTabBtn" class="nav-tab-item" onclick="switchDashboardTab('schools')">
                    <i class="fas fa-university"></i> {{ $isAdmin ? 'Schools' : 'School' }}
                </div>
            </div>
        </div>
    @endif

    <!-- Compliance Content Division -->
    <div id="complianceTabContent" class="tab-content-active">
        <div class="modules-grid">
            
            <!-- Fire Safety -->
            @php $canAccessFire = $isAdmin || in_array('fire_safety', $modules); @endphp
            <a href="{{ route('fire-safety.dashboard') }}" class="module-card-link"
               data-module="fire_safety" data-can-access="{{ $canAccessFire ? '1' : '0' }}">
                <div class="module-card mod-fire">
                    <div class="module-icon-wrap"><i class="fas fa-fire"></i></div>
                    <div class="module-title">Fire Safety</div>
                    <div class="module-desc">Fire extinguishers, building inspections, and evacuation plans management</div>
                    <div class="module-btn {{ !$canAccessFire ? 'btn-disabled' : '' }}">
                        <i class="fas fa-arrow-right"></i> {{ $canAccessFire ? 'Enter' : 'Admin Only' }}
                    </div>
                </div>
            </a>

            <!-- Evacuation Monitoring -->
            @php $canAccessTyphoon = $isAdmin || in_array('typhoon_flood', $modules); @endphp
            <a href="{{ route('typhoon.dashboard') }}" class="module-card-link"
               data-module="typhoon_flood" data-can-access="{{ $canAccessTyphoon ? '1' : '0' }}">
                <div class="module-card mod-evac">
                    <div class="module-icon-wrap"><i class="fas fa-route"></i></div>
                    <div class="module-title">Evacuation Monitoring</div>
                    <div class="module-desc">Evacuation centers, evacuee management, and monitoring Evacuation centers.</div>
                    <div class="module-btn {{ !$canAccessTyphoon ? 'btn-disabled' : '' }}">
                        <i class="fas fa-arrow-right"></i> {{ $canAccessTyphoon ? 'Enter' : 'Admin Only' }}
                    </div>
                </div>
            </a>

            <!-- Incident Checklist -->
            @php $canAccessIncidents = $isAdmin || in_array('incident_checklist', $modules); @endphp
            <a href="{{ route('incidents.dashboard') }}" class="module-card-link"
               data-module="incident_checklist" data-can-access="{{ $canAccessIncidents ? '1' : '0' }}">
                <div class="module-card mod-inc">
                    <div class="module-icon-wrap"><i class="fas fa-clipboard-check"></i></div>
                    <div class="module-title">Incident Checklist</div>
                    <div class="module-desc">Incident recording, victim management, compliance checklists, and remarks tracking</div>
                    <div class="module-btn {{ !$canAccessIncidents ? 'btn-disabled' : '' }}">
                        <i class="fas fa-arrow-right"></i> {{ $canAccessIncidents ? 'Enter' : 'Admin Only' }}
                    </div>
                </div>
            </a>

            <!-- Comprehensive School Safety -->
            @php $canAccessSchoolSafety = $isAdmin || in_array('comprehensive_school_safety', $modules); @endphp
            <a href="{{ route('comprehensive-school-safety.dashboard') }}" class="module-card-link"
               data-module="comprehensive_school_safety" data-can-access="{{ $canAccessSchoolSafety ? '1' : '0' }}">
                <div class="module-card mod-css">
                    <div class="module-icon-wrap"><i class="fas fa-school"></i></div>
                    <div class="module-title">Comprehensive School Safety</div>
                    <div class="module-desc">Centralized overview of every safety category, helping schools stay compliant and maintain safe learning facilities.</div>
                    <div class="module-btn {{ !$canAccessSchoolSafety ? 'btn-disabled' : '' }}">
                        <i class="fas fa-arrow-right"></i> {{ $canAccessSchoolSafety ? 'Enter' : 'Admin Only' }}
                    </div>
                </div>
            </a>

            <!-- Drill Monitoring -->
            @php $canAccessDrillMonitoring = $isAdmin || in_array('drill_monitoring', $modules); @endphp
            <a href="{{ route('drill-monitoring.dashboard') }}" class="module-card-link"
               data-module="drill_monitoring" data-can-access="{{ $canAccessDrillMonitoring ? '1' : '0' }}">
                <div class="module-card mod-drill">
                    <div class="module-icon-wrap"><i class="fas fa-bell"></i></div>
                    <div class="module-title">Drill Monitoring</div>
                    <div class="module-desc">Track, log, and evaluate emergency evacuation drills, response times, and compliance metrics.</div>
                    <div class="module-btn {{ !$canAccessDrillMonitoring ? 'btn-disabled' : '' }}">
                        <i class="fas fa-arrow-right"></i> {{ $canAccessDrillMonitoring ? 'Enter' : 'Admin Only' }}
                    </div>
                </div>
            </a>

            <!-- Hazard Mapping -->
            @php $canAccessHazard = $isAdmin || in_array('hazard_mapping', $modules); @endphp
            <a href="{{ route('hazard-mapping.dashboard') }}" class="module-card-link"
                data-module="hazard_mapping" data-can-access="{{ $canAccessHazard ? '1' : '0' }}">
                <div class="module-card mod-haz">
                    <div class="module-icon-wrap"><i class="fas fa-map"></i></div>
                    <div class="module-title">Hazard Mapping</div>
                    <div class="module-desc">Identify, assess, and map hazards affecting school sites and areas for risk reduction and preparedness.</div>
                    <div class="module-btn {{ !$canAccessHazard ? 'btn-disabled' : '' }}">
                        <i class="fas fa-arrow-right"></i> {{ $canAccessHazard ? 'Enter' : 'Admin Only' }}
                    </div>
                </div>
            </a>

            <!-- Damage Reports -->
            @php $canAccessDamageReport = $isAdmin || in_array('damage_reports', $modules); @endphp
            <a href="#" class="module-card-link" 
               data-module="damage_reports" data-can-access="{{ $canAccessDamageReport ? '1' : '0' }}">
                <div class="module-card mod-dam">
                    <div class="module-icon-wrap"><i class="fas fa-user-injured"></i></div>
                    <div class="module-title">Damage Reports</div>
                    <div class="module-desc">Track damages of facilities and resources. Assess the severity of damage and make decisions for recovery and rehabilitation.</div>
                    <div class="module-btn {{ !$canAccessDamageReport ? 'btn-disabled' : '' }}">
                        <i class="fas fa-arrow-right"></i> {{ $canAccessDamageReport ? 'Enter' : 'Admin Only' }}
                    </div>
                </div>
            </a>

            <!-- Inventory -->
            @php $canAccessInventory = $isAdmin || in_array('inventory_storage', $modules); @endphp
            <a href="{{ route('inventory-storage.dashboard') }}" class="module-card-link" 
               data-module="inventory_storage" data-can-access="{{ $canAccessInventory ? '1' : '0' }}">
                <div class="module-card mod-inv">
                    <div class="module-icon-wrap"><i class="fas fa-boxes"></i></div>
                    <div class="module-title">Inventory</div>
                    <div class="module-desc">Manage and track sources, gears and equipment inventory for disaster preparedness, response, and recovery operations.</div>
                    <div class="module-btn {{ !$canAccessInventory ? 'btn-disabled' : '' }}">
                        <i class="fas fa-arrow-right"></i> {{ $canAccessInventory ? 'Enter' : 'Admin Only' }}
                    </div>
                </div>
            </a>

        </div>
    </div>

    @if($canShowSchoolTab)
        <!-- Schools Content Division -->
        <div id="schoolsTabContent" style="display:none;">
            @if($isAdmin)
                @include('schools-tab')
            @else
                <div class="schools-tab-container mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 px-lg-5">
                        <h2 class="fw-bold mb-0"><i class="fas fa-school me-2"></i> My Assigned School</h2>
                    </div>

                    <div class="px-lg-5">
                        @if(isset($contributorAssignedSchool) && $contributorAssignedSchool)
                            <div class="card border-0 shadow-lg rounded-4">
                                <div class="card-header bg-dark text-white rounded-top-4">
                                            <div class="d-flex justify-content-between align-items-center gap-3">
                                                <h5 class="mb-0 fw-bold">{{ $contributorAssignedSchool->school_name ?? 'Assigned School' }}</h5>
                                                <a href="{{ route('fire-safety.report.full-school', $contributorAssignedSchool->id) }}" target="_blank" class="btn btn-sm btn-outline-light">
                                                    <i class="fas fa-print me-1"></i> Print Full Report
                                                </a>
                                            </div>
                                </div>
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="fw-bold mb-0">Core Information</h5>
                                        @if(Auth::user()->role !== 'viewer')
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-dark btn-sm" id="contributorUnlockSchoolBtn">
                                                    <i class="fas fa-pen me-1"></i> Update Details
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm d-none" id="contributorCancelSchoolBtn">Cancel</button>
                                            </div>
                                        @endif
                                    </div>

                                    <form id="contributorSchoolForm">
                                        @csrf
                                        @method('PUT')
                                        <div class="row g-3">
                                            <div class="col-md-9">
                                                <label class="form-label fw-bold small">School Name</label>
                                                <input type="text" class="form-control contributor-school-input" name="school_name" value="{{ $contributorAssignedSchool->school_name }}" required disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold small">School ID</label>
                                                <input type="text" class="form-control contributor-school-input" name="school_id" value="{{ $contributorAssignedSchool->school_id ?: ($contributorAssignedSchool->school_id_number ?: '') }}" disabled>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label fw-bold small">Address</label>
                                                <textarea class="form-control contributor-school-input" name="address" rows="2" required disabled>{{ $contributorAssignedSchool->address }}</textarea>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small">District</label>
                                                <input type="text" class="form-control contributor-school-input" name="district" value="{{ $contributorAssignedSchool->district }}" disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small">Division</label>
                                                <input type="text" class="form-control contributor-school-input" name="division" value="{{ $contributorAssignedSchool->division }}" disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small">Region</label>
                                                <input type="text" class="form-control contributor-school-input" name="region" value="{{ $contributorAssignedSchool->region }}" disabled>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small">School Head</label>
                                                <input type="text" class="form-control contributor-school-input" name="school_head" value="{{ $contributorAssignedSchool->school_head }}" disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small">DRRM Coordinator</label>
                                                <input type="text" class="form-control contributor-school-input" name="drrm_coordinator" value="{{ $contributorAssignedSchool->drrm_coordinator }}" disabled>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small">Head Contact</label>
                                                <input type="text" class="form-control contributor-school-input" name="contact_number" value="{{ $contributorAssignedSchool->contact_number }}" disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small">Coordinator Contact</label>
                                                <input type="text" class="form-control contributor-school-input" name="contact_number_2" value="{{ $contributorAssignedSchool->contact_number_2 }}" disabled>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small">No. of Students</label>
                                                <input type="number" class="form-control contributor-school-input" name="number_students" min="0" value="{{ $contributorAssignedSchool->number_students ?? 0 }}" disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small">No. of Personnel</label>
                                                <input type="number" class="form-control contributor-school-input" name="number_personnel" min="0" value="{{ $contributorAssignedSchool->number_personnel ?? 0 }}" disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small">No. of Gates</label>
                                                <input type="number" class="form-control contributor-school-input" name="number_gates" min="0" value="{{ $contributorAssignedSchool->number_gates ?? 0 }}" disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small">Engineer Last Inspection Date</label>
                                                <input type="date" class="form-control contributor-school-input" name="engineer_last_inspection_date" value="{{ optional($contributorAssignedSchool->engineer_last_inspection_date)->format('Y-m-d') }}" disabled>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label fw-bold small">Emergency Resources</label>
                                                <textarea class="form-control contributor-school-input" name="emergency_resources" rows="2" disabled>{{ $contributorAssignedSchool->emergency_resources }}</textarea>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end mt-3">
                                            <button type="submit" class="btn btn-success btn-sm d-none" id="contributorSaveSchoolBtn">
                                                <i class="fas fa-save me-1"></i> Save Changes
                                            </button>
                                        </div>
                                    </form>

                                    <div class="mt-4 pt-3 border-top">
                                        <h6 class="fw-bold mb-2">School Account Users</h6>
                                        <div class="small text-muted" id="contributorSchoolAccountUsersList">
                                            @if(isset($contributorSchoolAccountUsers) && $contributorSchoolAccountUsers->count() > 0)
                                                @foreach($contributorSchoolAccountUsers as $acctUser)
                                                    <div class="mb-1">{{ $acctUser->name }} ({{ $acctUser->email }})</div>
                                                @endforeach
                                            @else
                                                No assigned school account users.
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="card border-0 shadow rounded-4">
                                <div class="card-body p-4 text-center">
                                    <i class="fas fa-school text-muted fa-2x mb-3"></i>
                                    <h5 class="fw-bold">No School Assignment Yet</h5>
                                    <p class="text-muted mb-0">Your account has no assigned school. Please contact your administrator.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    @endif

    @if($isAdmin)

        <!-- View School Details Modal -->
        <div class="modal fade modal-add-school" id="viewSchoolModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 900px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <span class="title-icon"><i class="fas fa-university"></i></span>
                            <span id="schoolDetailName">School Details</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-4">
                            <!-- Core Info -->
                            <div class="col-md-6 pe-md-4 border-md-end">
                                <div class="mb-4">
                                    <div class="form-section-label">Core Information</div>
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="school-detail-label text-uppercase fw-bold text-muted small" style="font-size: 0.7rem;">School ID</div>
                                            <div class="school-detail-value text-break fw-semibold" id="detail_id">-</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="school-detail-label text-uppercase fw-bold text-muted small" style="font-size: 0.7rem;">Name</div>
                                            <div class="school-detail-value fw-semibold" id="detail_name">-</div>
                                        </div>
                                        <div class="col-12">
                                            <div class="school-detail-label text-uppercase fw-bold text-muted small" style="font-size: 0.7rem;">School Address</div>
                                            <div class="school-detail-value fw-semibold" id="detail_address">-</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="school-detail-label text-uppercase fw-bold text-muted small" style="font-size: 0.7rem;">School Head</div>
                                            <div class="school-detail-value text-muted" id="detail_head" style="font-style: italic;">-</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="school-detail-label text-uppercase fw-bold text-muted small" style="font-size: 0.7rem;">DRRM Coordinator</div>
                                            <div class="school-detail-value text-muted" id="detail_coordinator" style="font-style: italic;">-</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-section-label">Additional & Module Info</div>
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="school-detail-label text-uppercase fw-bold text-muted small" style="font-size: 0.7rem;">Head Contact</div>
                                            <div class="school-detail-value text-muted" id="detail_head_contact" style="font-style: italic;">-</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="school-detail-label text-uppercase fw-bold text-muted small" style="font-size: 0.7rem;">Coord. Contact</div>
                                            <div class="school-detail-value text-muted" id="detail_coord_contact" style="font-style: italic;">-</div>
                                        </div>
                                        <div class="col-4">
                                            <div class="school-detail-label text-uppercase fw-bold text-muted small" style="font-size: 0.7rem;">District</div>
                                            <div class="school-detail-value text-muted" id="detail_district" style="font-style: italic;">-</div>
                                        </div>
                                        <div class="col-4">
                                            <div class="school-detail-label text-uppercase fw-bold text-muted small" style="font-size: 0.7rem;">Division</div>
                                            <div class="school-detail-value text-muted" id="detail_division" style="font-style: italic;">-</div>
                                        </div>
                                        <div class="col-4">
                                            <div class="school-detail-label text-uppercase fw-bold text-muted small" style="font-size: 0.7rem;">Region</div>
                                            <div class="school-detail-value text-muted" id="detail_region" style="font-style: italic;">-</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="school-detail-label text-uppercase fw-bold text-muted small mb-1" style="font-size: 0.7rem;">Number of Students</div>
                                            <div class="school-detail-value badge bg-light text-dark border px-3 py-2 rounded-pill fw-semibold" id="detail_students" style="font-size: 0.9rem;">0</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="school-detail-label text-uppercase fw-bold text-muted small mb-1" style="font-size: 0.7rem;">Number of Personnel</div>
                                            <div class="school-detail-value badge bg-light text-dark border px-3 py-2 rounded-pill fw-semibold" id="detail_personnel" style="font-size: 0.9rem;">0</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="school-detail-label text-uppercase fw-bold text-muted small mb-1" style="font-size: 0.7rem;">Number of Gates</div>
                                            <div class="school-detail-value badge bg-light text-dark border px-3 py-2 rounded-pill fw-semibold" id="detail_gates" style="font-size: 0.9rem;">0</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="school-detail-label text-uppercase fw-bold text-muted small mb-1" style="font-size: 0.7rem;">Engineer Last Inspection Date</div>
                                            <div class="school-detail-value badge bg-light text-dark border px-3 py-2 rounded-pill fw-semibold" id="detail_engineer_last_inspection_date" style="font-size: 0.9rem;">N/A</div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="form-section-label">Emergency Resources</div>
                                    <div class="school-detail-value text-muted" id="detail_resources" style="font-style: italic;">None specified</div>
                                </div>
                            </div>

                            <!-- Module Shortcuts & Users -->
                            <div class="col-md-6 ps-md-4">
                                <div class="form-section-label mb-1">Module Connections</div>
                                <p class="small text-muted mb-3">Redirection depends on registration status.</p>

                                <div class="d-grid gap-2 mb-4">
                                    <button id="btn_fire_safety" class="btn bg-white border d-flex align-items-center justify-content-between p-3 rounded-3 text-start w-100 module-btn-link" style="transition: all 0.2s;">
                                        <div class="d-flex align-items-center fw-semibold text-dark">
                                            <div class="d-flex align-items-center justify-content-center rounded-3 me-3" style="width: 32px; height: 32px; background: #FEE2E2; color: #D12428;">
                                                <i class="fas fa-fire"></i>
                                            </div>
                                            Fire Safety
                                        </div>
                                        <i class="fas fa-chevron-right text-muted small"></i>
                                    </button>
                                    <button id="btn_typhoon" class="btn bg-white border d-flex align-items-center justify-content-between p-3 rounded-3 text-start w-100 module-btn-link" style="transition: all 0.2s;">
                                        <div class="d-flex align-items-center fw-semibold text-dark">
                                            <div class="d-flex align-items-center justify-content-center rounded-3 me-3" style="width: 32px; height: 32px; background: #E0F2FE; color: #1B4C6D;">
                                                <i class="fas fa-cloud-showers-heavy"></i>
                                            </div>
                                            Typhoon/Flood
                                        </div>
                                        <i class="fas fa-chevron-right text-muted small"></i>
                                    </button>
                                    <button id="btn_incident" class="btn bg-white border d-flex align-items-center justify-content-between p-3 rounded-3 text-start w-100 module-btn-link" style="transition: all 0.2s;">
                                        <div class="d-flex align-items-center fw-semibold text-dark">
                                            <div class="d-flex align-items-center justify-content-center rounded-3 me-3" style="width: 32px; height: 32px; background: #FEF3C7; color: #D97706;">
                                                <i class="fas fa-clipboard-list"></i>
                                            </div>
                                            Incidents
                                        </div>
                                        <i class="fas fa-chevron-right text-muted small"></i>
                                    </button>
                                    <button id="btn_comprehensive" class="btn bg-white border d-flex align-items-center justify-content-between p-3 rounded-3 text-start w-100 module-btn-link" style="transition: all 0.2s;">
                                        <div class="d-flex align-items-center fw-semibold text-dark">
                                            <div class="d-flex align-items-center justify-content-center rounded-3 me-3" style="width: 32px; height: 32px; background: #F5EAE6; color: #5C4033;">
                                                <i class="fas fa-school"></i>
                                            </div>
                                            CSS Assessment
                                        </div>
                                        <i class="fas fa-chevron-right text-muted small"></i>
                                    </button>
                                    <button id="btn_damage_assessment" class="btn bg-white border d-flex align-items-center justify-content-between p-3 rounded-3 text-start w-100 module-btn-link opacity-50 disabled" style="transition: all 0.2s;">
                                        <div class="d-flex align-items-center fw-semibold text-muted">
                                            <div class="d-flex align-items-center justify-content-center rounded-3 me-3" style="width: 32px; height: 32px; background: #EDE9FE; color: #8B5CF6;">
                                                <i class="fas fa-user-injured"></i>
                                            </div>
                                            Damage Assessment
                                        </div>
                                    </button>
                                    <button id="btn_hazard" class="btn bg-white border d-flex align-items-center justify-content-between p-3 rounded-3 text-start w-100 module-btn-link opacity-50 disabled" style="transition: all 0.2s;">
                                        <div class="d-flex align-items-center fw-semibold text-muted">
                                            <div class="d-flex align-items-center justify-content-center rounded-3 me-3" style="width: 32px; height: 32px; background: #CCFBF1; color: #0D7377;">
                                                <i class="fas fa-map-marked-alt"></i>
                                            </div>
                                            Hazard (Dev)
                                        </div>
                                    </button>
                                </div>

                                <div class="mb-4">
                                    <div class="form-section-label">Unassigned Contributors/Viewers</div>
                                    <div id="availableSchoolUsersMenu" class="d-grid gap-2 mb-2">
                                        <div class="text-muted small" style="font-style: italic;">No available users.</div>
                                    </div>
                                    <div class="small text-muted" id="schoolAssignmentHint" style="font-size: 0.75rem;">Only unassigned contributors/viewers are listed here.</div>
                                </div>

                                <div>
                                    <div class="form-section-label">School Account Users</div>
                                    <div id="schoolAccountUsersList" class="small text-muted" style="font-style: italic;">No assigned school account users.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-end align-items-center p-3 border-top bg-white">
                        <button type="button" class="btn-cancel-school me-2" data-bs-dismiss="modal">Close</button>
                        @if(Auth::user()->role !== 'viewer')
                        <button type="button" class="btn-save-school" id="editSchoolBtn">
                            <i class="fas fa-edit me-2"></i> Update Details
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete School Password Modal -->
        <div class="modal fade" id="deleteSchoolPasswordModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header bg-dark text-white p-4">
                        <h5 class="modal-title fw-bold">
                            <i class="fas fa-trash me-2"></i> Delete School Confirmation
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="mb-3 text-muted">
                            You are about to delete: <strong id="deleteSchoolName">-</strong>
                        </p>

                        <div class="mb-2">
                            <label class="form-label fw-bold small">Admin password required</label>
                            <input
                                type="password"
                                id="deleteSchoolPasswordInput"
                                class="form-control"
                                placeholder="Enter your account password"
                                autocomplete="current-password"
                                style="pointer-events:auto;"
                            >
                        </div>

                        <div id="deleteSchoolPasswordError" class="alert alert-danger py-2 px-3" style="display:none;">
                            Password is required.
                        </div>

                        <input type="hidden" id="deleteSchoolIdInput" value="">
                    </div>

                    <div class="modal-footer bg-light border-0 p-4">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-danger px-5 fw-bold" id="deleteSchoolConfirmBtn">
                            Delete now
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add School Modal (Wireframe Redesign) -->
        <div class="modal fade modal-add-school" id="addSchoolModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <span class="title-icon"><i class="fas fa-plus"></i></span>
                            Add New School
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="addSchoolForm">
                        @csrf
                        <div class="modal-body">

                            {{-- School Identification --}}
                            <div class="form-section-label">School Identification</div>
                            <div class="mb-3">
                                <label class="form-label">Official School Name</label>
                                <input type="text" name="school_name" class="form-control" required placeholder="e.g. Abayuan Senior High School">
                            </div>
                            <div class="mb-4">
                                <label class="form-label">DepEd School ID (Optional)</label>
                                <input type="text" name="school_id" class="form-control" placeholder="e.g. 107119">
                            </div>

                            {{-- Address --}}
                            <div class="form-section-label">School's Address</div>
                            <div class="mb-4">
                                <textarea name="address" class="form-control" rows="3" placeholder="Complete school address"></textarea>
                            </div>

                            {{-- Personnel --}}
                            <div class="form-section-label">Personnel</div>
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <label class="form-label">School Head</label>
                                    <input type="text" name="school_head" class="form-control" placeholder="Enter school head name">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">DRRM Coordinator</label>
                                    <input type="text" name="drrm_coordinator" class="form-control" placeholder="Enter DRRM coordinator name">
                                </div>
                            </div>

                            {{-- Location --}}
                            <div class="form-section-label">Location Details</div>
                            <div class="row g-3 mb-4">
                                <div class="col-4">
                                    <label class="form-label">District</label>
                                    <input type="text" name="district" class="form-control" placeholder="Dist.">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Division</label>
                                    <input type="text" name="division" class="form-control" placeholder="Div.">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Region</label>
                                    <input type="text" name="region" class="form-control" placeholder="Reg.">
                                </div>
                            </div>

                            {{-- Site Details --}}
                            <div class="form-section-label">Site Details</div>
                            <div class="row g-3">
                                <div class="col-3">
                                    <label class="form-label">No. of Students</label>
                                    <input type="number" name="number_students" class="form-control" min="0" value="0">
                                </div>
                                <div class="col-3">
                                    <label class="form-label">No. of Personnel</label>
                                    <input type="number" name="number_personnel" class="form-control" min="0" value="0">
                                </div>
                                <div class="col-3">
                                    <label class="form-label">No. of Gates</label>
                                    <input type="number" name="number_gates" class="form-control" min="0" value="0">
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Last Inspection</label>
                                    <input type="date" name="engineer_last_inspection_date" class="form-control">
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-cancel-school" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-save-school">
                                <i class="fas fa-save"></i> Save School
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit School Modal (Redesigned to match Add School) -->
        <div class="modal fade modal-add-school" id="editSchoolModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width:540px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <span class="title-icon"><i class="fas fa-pen"></i></span>
                            Update School Information
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editSchoolForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">

                            {{-- School Identification --}}
                            <div class="form-section-label">School Identification</div>
                            <div class="row g-3 mb-4">
                                <div class="col-8">
                                    <label class="form-label">Official School Name</label>
                                    <input type="text" name="school_name" class="form-control" required placeholder="e.g. Abayuan Senior High School">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">School ID</label>
                                    <input type="text" name="school_id" class="form-control" placeholder="e.g. 107119">
                                </div>
                            </div>

                            {{-- Address --}}
                            <div class="form-section-label">School's Address</div>
                            <div class="mb-4">
                                <textarea name="address" class="form-control" rows="3" placeholder="Complete school address" required></textarea>
                            </div>

                            {{-- Personnel --}}
                            <div class="form-section-label">Personnel</div>
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <label class="form-label">School Head</label>
                                    <input type="text" name="school_head" class="form-control" placeholder="Enter school head name">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Head Contact No.</label>
                                    <input type="text" name="contact_number" class="form-control" placeholder="e.g. 09xxxxxxxxx">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">DRRM Coordinator</label>
                                    <input type="text" name="drrm_coordinator" class="form-control" placeholder="Enter DRRM coordinator name">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Coord. Contact No.</label>
                                    <input type="text" name="contact_number_2" class="form-control" placeholder="e.g. 09xxxxxxxxx">
                                </div>
                            </div>

                            {{-- Location --}}
                            <div class="form-section-label">Location Details</div>
                            <div class="row g-3 mb-4">
                                <div class="col-4">
                                    <label class="form-label">District</label>
                                    <input type="text" name="district" class="form-control" placeholder="Dist.">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Division</label>
                                    <input type="text" name="division" class="form-control" placeholder="Div.">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Region</label>
                                    <input type="text" name="region" class="form-control" placeholder="Reg.">
                                </div>
                            </div>

                            {{-- Site Details --}}
                            <div class="form-section-label">Site Details</div>
                            <div class="row g-3 mb-4">
                                <div class="col-3">
                                    <label class="form-label">No. of Students</label>
                                    <input type="number" name="number_students" class="form-control" min="0">
                                </div>
                                <div class="col-3">
                                    <label class="form-label">No. of Personnel</label>
                                    <input type="number" name="number_personnel" class="form-control" min="0">
                                </div>
                                <div class="col-3">
                                    <label class="form-label">No. of Gates</label>
                                    <input type="number" name="number_gates" class="form-control" min="0">
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Last Inspection</label>
                                    <input type="date" name="engineer_last_inspection_date" class="form-control">
                                </div>
                            </div>

                            {{-- Emergency Resources --}}
                            <div class="form-section-label">Emergency Resources</div>
                            <div class="mb-2">
                                <textarea name="emergency_resources" class="form-control" rows="2" placeholder="List available emergency resources..."></textarea>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-cancel-school" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-save-school">
                                <i class="fas fa-save"></i> Update School
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if($user && ($user->needs_fs_registration || $user->needs_tf_registration))
        <!-- Contributor School Registration Modal -->
        <div class="modal fade" id="registrationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="registrationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header bg-dark text-white border-0">
                        <h5 class="modal-title" id="registrationModalLabel">
                            <i class="fas fa-school-flag me-2 text-warning"></i> Register Your School
                        </h5>
                    </div>
                    <form id="registrationForm">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> Before you can access the compliance modules, you need to provide your school's official information. This will automatically link your account to your newly created school.
                            </div>

                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">Official School Name <span class="text-danger">*</span></label>
                                    <input type="text" name="school_name" class="form-control" placeholder="e.g. San Isidro Central School" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">School ID / Code <span class="text-danger">*</span></label>
                                    <input type="text" name="school_id_number" class="form-control" placeholder="e.g. 106883" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">School Address <span class="text-danger">*</span></label>
                                    <textarea name="address" class="form-control" rows="3" placeholder="Enter full address..." required></textarea>
                                </div>
                            </div>

                            <div class="mt-4 p-3 rounded bg-light border">
                                <h6 class="fw-bold mb-2"><i class="fas fa-check-double me-1 text-success"></i> Modules to activate:</h6>
                                <div class="d-flex gap-3">
                                    @if($user->needs_fs_registration)
                                        <span class="badge bg-white text-dark border p-2"><i class="fas fa-fire me-1 text-danger"></i> Fire Safety Compliance</span>
                                    @endif
                                    @if($user->needs_tf_registration)
                                        <span class="badge bg-white text-dark border p-2"><i class="fas fa-cloud-showers-heavy me-1 text-info"></i> Typhoon/Flooding</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4">
                            <a href="{{ route('logout') }}" class="btn btn-link text-muted" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout and Finish Later
                            </a>
                            <button type="submit" class="btn btn-dark px-4 py-2" id="submitReg">
                                <i class="fas fa-save me-2"></i> Register and Continue
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const regModal = new bootstrap.Modal(document.getElementById('registrationModal'));
                regModal.show();

                document.getElementById('registrationForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const btn = document.getElementById('submitReg');
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Registering...';

                    fetch("{{ route('register-school') }}", {
                        method: 'POST',
                        body: new FormData(this),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message,
                                confirmButtonColor: '#212529'
                            }).then(() => location.reload());
                        } else {
                            Swal.fire('Error', data.message, 'error');
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fas fa-save me-2"></i> Register and Continue';
                        }
                    })
                    .catch(e => {
                        console.error(e);
                        Swal.fire('Error', 'An unexpected error occurred.', 'error');
                        btn.disabled = false;
                    });
                });
            });
        </script>
    @endif

<!-- Incident Module Choice Modal -->
<div class="modal fade" id="incidentModuleChoiceModal" tabindex="-1" aria-labelledby="incidentModuleChoiceLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #F2C94C 0%, #F2994A 100%);">
                <h5 class="modal-title" id="incidentModuleChoiceLabel">
                    <i class="fas fa-clipboard-list me-2"></i>Choose Incident Entry Type
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-3 text-muted">Open <strong id="incidentModuleChoiceSchoolName">Selected school</strong> in the Incident Checklist module, then choose the log type.</p>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-warning py-3 fw-semibold" id="incidentModuleChoiceIncidentBtn">
                        <i class="fas fa-exclamation-triangle me-2"></i> Log Incident
                    </button>
                    <button type="button" class="btn btn-outline-warning py-3 fw-semibold" id="incidentModuleChoiceComplianceBtn">
                        <i class="fas fa-calendar-check me-2"></i> Log Compliance Status / Event
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hazard Mapping Coming Soon Modal -->
<div class="modal fade" id="hazardModuleComingSoonModal" tabindex="-1" aria-labelledby="hazardModuleComingSoonLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: #0D7377;">
                <h5 class="modal-title" id="hazardModuleComingSoonLabel">
                    <i class="fas fa-tools me-2"></i>Hazard Mapping: Coming Soon
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">
                    The Hazard Mapping system is currently under development and will be available in the upcoming days.
                </p>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn text-white" data-bs-dismiss="modal" style="background-color: #0D7377; border-color: #0D7377;">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- No Access Modal -->
<div class="modal fade" id="noModuleAccessModal" tabindex="-1" aria-labelledby="noModuleAccessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" id="noModuleAccessModalHeader" style="background-color: #dc3545;">
                <h5 class="modal-title" id="noModuleAccessModalLabel">
                    <i class="fas fa-lock me-2"></i>Access denied
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">
                    You don't currently have access to this module. Please contact your administrator if you need access for your role.
                </p>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-primary" id="noModuleAccessCloseBtn" data-bs-dismiss="modal" style="border-color: transparent;">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Announce Modal -->
<div class="modal fade" id="announceModal" tabindex="-1" aria-labelledby="announceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
            <div class="modal-header border-bottom bg-white py-3 px-4">
                <h5 class="modal-title" id="announceModalLabel" style="font-family: var(--font-display, 'Sora', sans-serif); font-weight: 800; color: var(--navy, #0D1B36);">
                    <i class="fas fa-bullhorn me-2 text-primary"></i> Create System Announcement
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="announceForm" method="POST" action="{{ route('announcements.store', [], false) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4 text-dark">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">What is the event? (Title)</label>
                            <input type="text" name="what" class="form-control" placeholder="e.g. Annual Fire Safety Drill 2026" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">When? (Date & Time)</label>
                            <input type="datetime-local" name="when" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Where will it take place?</label>
                            <input type="text" name="where" class="form-control" placeholder="e.g. Main School Quadrangle" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Why? (Small Description)</label>
                            <textarea name="why" class="form-control" rows="3" placeholder="Explain the purpose of this announcement..." required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Upload Poster/Flyer (Panoramic/Widescreen 16:9 recommended)</label>
                            <p class="text-info small mb-2"><i class="fas fa-info-circle me-1"></i> Note: Upload a landscape image for best results (maximum file size: 2MB).</p>
                            <input type="file" name="image" class="form-control" accept="image/*" required onchange="previewImage(this)">
                            <div id="imagePreview" class="mt-3 d-none">
                                <p class="small text-muted mb-2">Image Preview:</p>
                                <div style="width: 100%; padding-top: 25%; position: relative; overflow: hidden; border-radius: 8px; border: 1px solid #ddd; background: #fff;">
                                    <img id="previewImg" src="#" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0 px-4 py-3">
                    <button type="button" class="btn btn-light border shadow-sm fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn border-0 shadow-sm fw-semibold text-white px-4" id="submitAnnounce" style="background: var(--navy, #0D1B36);">
                        <i class="fas fa-paper-plane me-2"></i> Post Announcement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentSchoolDetail = null;

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function renderSchoolAssignmentPanels(data) {
        const availableMenu = document.getElementById('availableSchoolUsersMenu');
        const schoolAccountWrap = document.getElementById('schoolAccountUsersList');
        if (!availableMenu || !schoolAccountWrap) return;

        const availableUsers = Array.isArray(data.available_users) ? data.available_users : [];
        const schoolAccountUsers = Array.isArray(data.school_account_users) ? data.school_account_users : [];

        if (!availableUsers.length) {
            availableMenu.innerHTML = '<div class="text-muted small" style="font-style: italic;">No available users.</div>';
        } else {
            availableMenu.innerHTML = availableUsers.map((u) => {
                const roleLabel = u.role === 'viewer' ? 'Viewer' : 'Contributor';
                const nameStr = u.name || '';
                const initials = nameStr.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                return `
                    <div class="d-flex align-items-center justify-content-between p-3 border rounded-3 mb-2 bg-white shadow-sm">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 40px; height: 40px; font-size: 0.9rem;">
                                ${escapeHtml(initials)}
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 0.9rem; line-height: 1.2;">
                                    ${escapeHtml(u.name)} <span class="fw-normal text-muted" style="font-size: 0.85rem;">(${escapeHtml(roleLabel)})</span>
                                </div>
                                <div class="text-muted small mt-1" style="line-height: 1.2;">${escapeHtml(u.email)}</div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-dark btn-sm px-4 fw-semibold rounded-2 assign-school-user-btn" style="padding-top: 6px; padding-bottom: 6px;" data-user-id="${u.id}">Assign</button>
                    </div>
                `
            }).join('');
        }

        if (!schoolAccountUsers.length) {
            schoolAccountWrap.textContent = 'No assigned school account users.';
        } else {
            schoolAccountWrap.innerHTML = schoolAccountUsers.map((u) => `${escapeHtml(u.name)} (${escapeHtml(u.email)})`).join('<br>');
        }

        availableMenu.querySelectorAll('.assign-school-user-btn').forEach((btn) => {
            btn.addEventListener('click', async () => {
                if (!currentSchoolDetail) return;
                const userId = btn.getAttribute('data-user-id');
                if (!userId) return;

                await assignSchoolUser(currentSchoolDetail.id, userId);
            });
        });
    }

    async function assignSchoolUser(schoolId, userId) {
        try {
            const resp = await fetch(`/schools/${schoolId}/users/${userId}/assign`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            });
            const data = await resp.json();
            if (!resp.ok || !data.success) {
                Swal.fire('Error', data.message || 'Failed to assign user.', 'error');
                return;
            }
            await viewSchoolDetails(schoolId, true);
        } catch (err) {
            console.error(err);
            Swal.fire('Error', 'Failed to assign user.', 'error');
        }
    }

    async function removeSchoolAssignment(schoolId, userId) {
        try {
            const resp = await fetch(`/schools/${schoolId}/users/${userId}/assign`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            const data = await resp.json();
            if (!resp.ok || !data.success) {
                Swal.fire('Error', data.message || 'Failed to remove assignment.', 'error');
                return;
            }
            await viewSchoolDetails(schoolId, true);
        } catch (err) {
            console.error(err);
            Swal.fire('Error', 'Failed to remove assignment.', 'error');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const links = document.querySelectorAll('a.module-card-link[data-module]');
        const modalEl = document.getElementById('noModuleAccessModal');
        const modal = modalEl ? bootstrap.Modal.getOrCreateInstance(modalEl) : null;
        const headerEl = document.getElementById('noModuleAccessModalHeader');
        const closeBtn = document.getElementById('noModuleAccessCloseBtn');

        function applyThemeColor(color) {
            const safeColor = (typeof color === 'string' && color.trim()) ? color.trim() : '#dc3545';
            if (headerEl) headerEl.style.backgroundColor = safeColor;
            if (closeBtn) closeBtn.style.backgroundColor = safeColor;
        }

        links.forEach(a => {
            a.addEventListener('click', function (e) {
                const canAccess = this.getAttribute('data-can-access') === '1';
                if (canAccess) return;
                e.preventDefault();
                applyThemeColor(this.getAttribute('data-theme-color'));
                if (modal) modal.show();
            });
        });
    });

    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const maxBytes = 2 * 1024 * 1024; // 2MB

        if (input.files && input.files[0]) {
            if (input.files[0].size > maxBytes) {
                input.value = '';
                preview.classList.add('d-none');
                Swal.fire('File Too Large', 'Please upload an image that is 2MB or smaller.', 'warning');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const announceForm = document.getElementById('announceForm');
        if (!announceForm) {
            return;
        }

        announceForm.addEventListener('submit', function (event) {
            const fileInput = announceForm.querySelector('input[name="image"]');
            const submitBtn = document.getElementById('submitAnnounce');
            const maxBytes = 2 * 1024 * 1024; // 2MB

            if (fileInput && fileInput.files && fileInput.files[0] && fileInput.files[0].size > maxBytes) {
                event.preventDefault();
                Swal.fire('File Too Large', 'Please upload an image that is 2MB or smaller.', 'warning');
                return;
            }

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Posting...';
            }
        });
    });

    function deleteAnnouncement(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This announcement will be removed from the dashboard.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/announcements/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Removed!', data.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                });
            }
        });
    }

    /* Tab Switching Logic — with smooth fade+slide animation */
    function switchDashboardTab(tabName) {
        const complianceBtn = document.getElementById('complianceTabBtn');
        const schoolsBtn    = document.getElementById('schoolsTabBtn');
        const complianceContent = document.getElementById('complianceTabContent');
        const schoolsContent    = document.getElementById('schoolsTabContent');
        if (!complianceContent || !schoolsContent) return;

        const DURATION = 220; // ms for fade-out before swap

        function fadeOut(el, cb) {
            el.style.transition = 'opacity ' + DURATION + 'ms ease, transform ' + DURATION + 'ms ease';
            el.style.opacity = '0';
            el.style.transform = 'translateY(12px)';
            setTimeout(() => { el.style.display = 'none'; el.style.transition = ''; el.style.opacity = ''; el.style.transform = ''; cb(); }, DURATION);
        }

        function fadeIn(el) {
            el.style.display = 'block';
            el.style.opacity = '0';
            el.style.transform = 'translateY(12px)';
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    el.style.transition = 'opacity ' + DURATION + 'ms ease, transform ' + DURATION + 'ms ease';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                    setTimeout(() => { el.style.transition = ''; el.style.opacity = ''; el.style.transform = ''; }, DURATION);
                });
            });
        }

        if (tabName === 'schools') {
            complianceBtn.classList.remove('active');
            schoolsBtn.classList.add('active');
            fadeOut(complianceContent, () => {
                fadeIn(schoolsContent);
                const si = document.getElementById('schoolSearchInput');
                if (si) { si.value = ''; si.dispatchEvent(new Event('input', { bubbles: true })); }
            });
            localStorage.setItem('activeDashboardTab', 'schools');
        } else {
            schoolsBtn.classList.remove('active');
            complianceBtn.classList.add('active');
            fadeOut(schoolsContent, () => fadeIn(complianceContent));
            localStorage.setItem('activeDashboardTab', 'compliance');
        }
    }

    function focusSchoolCardInSchoolsTab(schoolId, shouldScroll, shouldHighlight) {
        const selector = `.school-item-col[data-school-id="${schoolId}"] .school-card`;
        const schoolCard = document.querySelector(selector);
        if (!schoolCard) return false;

        if (shouldScroll) {
            schoolCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        if (shouldHighlight) {
            schoolCard.classList.add('school-card-focus-highlight');
            setTimeout(() => schoolCard.classList.remove('school-card-focus-highlight'), 4800);
        }

        return true;
    }

    // Persist active tab on reload
    document.addEventListener('DOMContentLoaded', function() {
        const savedTab = localStorage.getItem('activeDashboardTab');
        if (savedTab === 'schools' && @json($canShowSchoolTab)) {
            switchDashboardTab('schools');
        }

        const params = new URLSearchParams(window.location.search);
        const requestedTab = params.get('tab');
        const targetSchoolId = params.get('school_id');
        const shouldHighlight = params.get('highlight') === '1';
        const shouldScroll = params.get('scroll') === '1';

        if (requestedTab === 'schools' && @json($canShowSchoolTab)) {
            switchDashboardTab('schools');
        }

        if (requestedTab === 'schools' && targetSchoolId) {
            [0, 160, 420, 860].forEach((delay) => {
                setTimeout(() => {
                    focusSchoolCardInSchoolsTab(targetSchoolId, shouldScroll, shouldHighlight);
                }, delay);
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('contributorSchoolForm');
        if (!form) return;

        const unlockBtn = document.getElementById('contributorUnlockSchoolBtn');
        const cancelBtn = document.getElementById('contributorCancelSchoolBtn');
        const saveBtn = document.getElementById('contributorSaveSchoolBtn');
        const inputs = Array.from(form.querySelectorAll('.contributor-school-input'));
        const initialData = {};

        inputs.forEach((input) => {
            initialData[input.name] = input.value;
        });

        const setEditable = (editable) => {
            inputs.forEach((input) => {
                input.disabled = !editable;
            });

            if (unlockBtn) unlockBtn.classList.toggle('d-none', editable);
            if (cancelBtn) cancelBtn.classList.toggle('d-none', !editable);
            if (saveBtn) saveBtn.classList.toggle('d-none', !editable);
        };

        if (unlockBtn) {
            unlockBtn.addEventListener('click', function () {
                setEditable(true);
            });
        }

        if (cancelBtn) {
            cancelBtn.addEventListener('click', function () {
                inputs.forEach((input) => {
                    input.value = initialData[input.name] ?? '';
                });
                setEditable(false);
            });
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const payload = Object.fromEntries(new FormData(form).entries());
            const submitButton = document.getElementById('contributorSaveSchoolBtn');
            if (submitButton) submitButton.disabled = true;

            fetch("{{ route('schools.assigned.update') }}", {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            })
            .then(async (response) => {
                const data = await response.json().catch(() => ({}));
                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Failed to update school information.');
                }

                inputs.forEach((input) => {
                    initialData[input.name] = input.value;
                });

                setEditable(false);
                Swal.fire('Updated', data.message || 'School information updated.', 'success');
            })
            .catch((error) => {
                Swal.fire('Error', error.message || 'Failed to update school information.', 'error');
            })
            .finally(() => {
                if (submitButton) submitButton.disabled = false;
            });
        });
    });

    /* Unified Schools Functionality (Phase 2) */
    function openAddSchoolModal() {
        const addModal = new bootstrap.Modal(document.getElementById('addSchoolModal'));
        addModal.show();
    }

    // Handle Add School Submission
    document.getElementById('addSchoolForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Adding...';

        fetch("{{ route('schools.unified-store') }}", {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                Swal.fire('Success!', data.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Error', data.message, 'error');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        })
        .catch(err => {
            console.error(err);
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    });

    // View Details Logic
    async function viewSchoolDetails(id, keepOpen = false) {
        const viewModalEl = document.getElementById('viewSchoolModal');
        const viewModal = new bootstrap.Modal(viewModalEl);

        // Show loading state or reset content
        document.getElementById('schoolDetailName').innerText = 'Loading...';

        try {
            const res = await fetch(`/schools/details/${id}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();

            const s = data.school;
            const m = data.modules;
            currentSchoolDetail = {
                ...s,
                school_account_user: data.school_account_user || null,
            };

            // Populate Basic Info
            document.getElementById('schoolDetailName').innerText = s.school_name;
            document.getElementById('detail_id').innerText = s.school_id || s.school_id_number || 'N/A';
            document.getElementById('detail_name').innerText = s.school_name;
            document.getElementById('detail_address').innerText = s.address;
            document.getElementById('detail_head').innerText = data.school_head_user?.name || s.school_head || '-';
            document.getElementById('detail_coordinator').innerText = data.school_drrm_user?.name || s.drrm_coordinator || s.school_drrm_coordinator || '-';

            // Additional Info
            document.getElementById('detail_head_contact').innerText = s.contact_number || 'N/A';
            document.getElementById('detail_coord_contact').innerText = s.contact_number_2 || 'N/A';
            document.getElementById('detail_district').innerText = s.district || 'N/A';
            document.getElementById('detail_division').innerText = s.division || 'N/A';
            document.getElementById('detail_region').innerText = s.region || 'N/A';
            document.getElementById('detail_students').innerText = (s.number_students ?? 0);
            document.getElementById('detail_personnel').innerText = (s.number_personnel ?? 0);
            document.getElementById('detail_gates').innerText = (s.number_gates ?? 0);
            document.getElementById('detail_engineer_last_inspection_date').innerText = formatSchoolDate(s.engineer_last_inspection_date);
            document.getElementById('detail_resources').innerText = s.emergency_resources || 'None specified';
            renderSchoolAssignmentPanels(data);

            // Configure Module Buttons
            configureModuleButton('btn_fire_safety', m.fire_safety, `/fire-safety/buildings?school_id=${s.id}`);
            configureModuleButton('btn_typhoon', m.typhoon_flood, `/typhoon/evacuation-center/${s.id}`);
            configureModuleButton('btn_incident', m.incident_checklist, null, () => showIncidentModuleChoice(s));
            configureModuleButton('btn_comprehensive', m.comprehensive_school_safety, `/comprehensive-school-safety/schools/${s.id}/assessments`);
            configureModuleButton('btn_damage_assessment', m.damage_assessment, `/damage-assessment/dashboard?school_id=${s.id}`);
            configureModuleButton('btn_hazard', m.hazard_mapping, `#`);

            // Setup Edit Button
            const editBtn = document.getElementById('editSchoolBtn');
            if (editBtn) {
                editBtn.onclick = () => openEditModal(s);
            }
            const delBtn = document.getElementById('deleteSchoolBtn');
            if (delBtn) {
                delBtn.style.display = @json($isAdmin) ? '' : 'none';
                delBtn.onclick = () => confirmDeleteSchool(s);
            }

            if (keepOpen) {
                const existing = bootstrap.Modal.getInstance(viewModalEl);
                if (!existing) viewModal.show();
            } else {
                viewModal.show();
            }
        } catch (err) {
            console.error(err);
            Swal.fire('Error', 'Failed to load school details.', 'error');
        }
    }

    function formatSchoolDate(value) {
        if (!value) {
            return 'N/A';
        }

        const date = new Date(value);
        if (Number.isNaN(date.getTime())) {
            return value;
        }

        return new Intl.DateTimeFormat('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }).format(date);
    }

    function configureModuleButton(id, isActive, url, action = null) {
        const btn = document.getElementById(id);
        if (isActive) {
            btn.classList.remove('disabled');
            btn.classList.add('btn-outline-dark');
            btn.classList.remove('btn-light', 'text-muted');
            if (typeof action === 'function') {
                btn.onclick = action;
            } else {
                btn.onclick = () => window.location.href = url;
            }
        } else {
            btn.classList.add('disabled');
            btn.classList.add('btn-light', 'text-muted');
            btn.classList.remove('btn-outline-dark');
            btn.onclick = (e) => e.preventDefault();
        }
    }

    function showIncidentModuleChoice(school) {
        const modalEl = document.getElementById('incidentModuleChoiceModal');
        if (!modalEl || typeof bootstrap === 'undefined') return;
        const parentModalEl = document.getElementById('viewSchoolModal');

        const schoolNameEl = document.getElementById('incidentModuleChoiceSchoolName');
        const incidentBtn = document.getElementById('incidentModuleChoiceIncidentBtn');
        const complianceBtn = document.getElementById('incidentModuleChoiceComplianceBtn');
        const baseUrl = '{{ route('incidents.dashboard') }}';

        if (schoolNameEl) {
            schoolNameEl.textContent = school?.school_name || 'Selected school';
        }

        const openWithTab = (tabName) => {
            const params = new URLSearchParams({
                school_id: String(school.id),
                open_log: '1',
                log_tab: tabName,
            });
            window.location.href = `${baseUrl}?${params.toString()}`;
        };

        if (incidentBtn) {
            incidentBtn.onclick = () => openWithTab('incident');
        }

        if (complianceBtn) {
            complianceBtn.onclick = () => openWithTab('compliance');
        }

        const showChoiceModal = () => bootstrap.Modal.getOrCreateInstance(modalEl).show();

        if (parentModalEl) {
            const parentModal = bootstrap.Modal.getInstance(parentModalEl);
            if (parentModal) {
                parentModalEl.addEventListener('hidden.bs.modal', showChoiceModal, { once: true });
                parentModal.hide();
                return;
            }
        }

        showChoiceModal();
    }

    function openEditModal(school) {
        // Close view modal first
        bootstrap.Modal.getInstance(document.getElementById('viewSchoolModal')).hide();

        const form = document.getElementById('editSchoolForm');
        form.action = `/schools/update/${school.id}`;

        // Populate form
        form.querySelector('[name="school_name"]').value = school.school_name;
        form.querySelector('[name="school_id"]').value = school.school_id || '';
        form.querySelector('[name="address"]').value = school.address;
        const headInput = form.querySelector('[name="school_head"]');
        const drrmInput = form.querySelector('[name="drrm_coordinator"]');
        if (headInput) headInput.value = school.school_head || '';
        if (drrmInput) drrmInput.value = school.drrm_coordinator || '';
        form.querySelector('[name="district"]').value = school.district || '';
        form.querySelector('[name="division"]').value = school.division || '';
        form.querySelector('[name="region"]').value = school.region || '';
        form.querySelector('[name="contact_number"]').value = school.contact_number || '';
        form.querySelector('[name="contact_number_2"]').value = school.contact_number_2 || '';
        form.querySelector('[name="number_students"]').value = school.number_students || 0;
        form.querySelector('[name="number_personnel"]').value = school.number_personnel || 0;
        form.querySelector('[name="number_gates"]').value = school.number_gates || 0;
        form.querySelector('[name="engineer_last_inspection_date"]').value = school.engineer_last_inspection_date || '';
        form.querySelector('[name="emergency_resources"]').value = school.emergency_resources || '';

        new bootstrap.Modal(document.getElementById('editSchoolModal')).show();
    }

    async function confirmDeleteSchool(school) {
        const doOpenPasswordModal = () => {
            // Hide view modal first to avoid focus/overlay conflicts.
            const viewEl = document.getElementById('viewSchoolModal');
            if (viewEl && bootstrap?.Modal) {
                const viewInst = bootstrap.Modal.getInstance(viewEl);
                viewInst?.hide();
            }

            const modalEl = document.getElementById('deleteSchoolPasswordModal');
            const nameEl = document.getElementById('deleteSchoolName');
            const idEl = document.getElementById('deleteSchoolIdInput');
            const pwdEl = document.getElementById('deleteSchoolPasswordInput');
            const errEl = document.getElementById('deleteSchoolPasswordError');
            const confirmBtn = document.getElementById('deleteSchoolConfirmBtn');

            if (nameEl) nameEl.textContent = school.school_name || 'â€”';
            if (idEl) idEl.value = school.id;
            if (pwdEl) pwdEl.value = '';
            if (errEl) errEl.style.display = 'none';
            if (confirmBtn) confirmBtn.disabled = false;

            if (modalEl && bootstrap?.Modal) {
                new bootstrap.Modal(modalEl).show();
                setTimeout(() => pwdEl?.focus(), 100);
            }
        };

        // Step 1: simple confirmation first (no typing), then open password modal.
        if (typeof Swal !== 'undefined') {
            const first = await Swal.fire({
                title: 'Delete this school?',
                text: `This will delete: ${school.school_name}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Continue'
            });
            if (!first.isConfirmed) return;
        }

        doOpenPasswordModal();
    }

    // One-time handler for modal confirm button
    (function () {
        const confirmBtn = document.getElementById('deleteSchoolConfirmBtn');
        const pwdEl = document.getElementById('deleteSchoolPasswordInput');
        const idEl = document.getElementById('deleteSchoolIdInput');
        const errEl = document.getElementById('deleteSchoolPasswordError');
        const modalEl = document.getElementById('deleteSchoolPasswordModal');

        if (!confirmBtn || !pwdEl || !idEl || !errEl || !modalEl) return;

        confirmBtn.addEventListener('click', async () => {
            const schoolId = idEl.value;
            const pwd = (pwdEl.value || '').trim();

            if (!pwd) {
                errEl.style.display = 'block';
                return;
            }

            confirmBtn.disabled = true;

            try {
                const resp = await fetch(`/schools/delete/${schoolId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ password: pwd })
                });

                const data = await resp.json();
                if (!resp.ok || !data.success) {
                    errEl.textContent = data.message || 'Failed to delete school.';
                    errEl.style.display = 'block';
                    confirmBtn.disabled = false;
                    return;
                }

                // Hide modal and refresh after success.
                const modalInst = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                modalInst.hide();
                location.reload();
            } catch (e) {
                console.error(e);
                errEl.textContent = 'Failed to delete school.';
                errEl.style.display = 'block';
                confirmBtn.disabled = false;
            }
        });
    })();

    document.getElementById('editSchoolForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;

        fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                Swal.fire('Updated!', data.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Error', data.message, 'error');
                btn.disabled = false;
            }
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        var toastElList = [].slice.call(document.querySelectorAll('.toast'));
        var toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl, { autohide: true, delay: 5000 });
        });
        toastList.forEach(toast => toast.show());
    });
</script>
@endpush
@endsection
