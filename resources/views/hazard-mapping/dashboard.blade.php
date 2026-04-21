@extends('layouts.app')

@section('title', 'Hazard Mapping - ' . ($currentSchool->school_name ?? 'Dashboard'))
@section('hide_main_nav', '1')

@push('styles')
<style>
    :root {
        --hazard-bg-light: #e8f5e9;
        --hazard-header: #0D7377;
        --hazard-header-alt: #13a3a8;
        --hazard-primary: #4caf50;
        --hazard-accent: #7cb342;
        --hazard-danger: #d32f2f;
        --hazard-warning: #f57c00;
        --hazard-safe: #388e3c;
    }

    body {
        background-color: var(--hazard-bg-light) !important;
    }

    main.py-4 {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

    .hazard-header {
        background: linear-gradient(135deg, var(--hazard-header) 0%, var(--hazard-header-alt) 100%);
        color: #ffffff;
        padding: 1.25rem 2rem;
        display: grid;
        grid-template-columns: auto 1fr auto;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .hazard-header-left {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .hazard-header-center {
        text-align: center;
    }

    .hazard-back-btn {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: #ffffff;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1.1rem;
    }

    .hazard-back-btn:hover {
        background: rgba(255,255,255,0.3);
        transform: translateX(-2px);
    }

    .hazard-school-info {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .hazard-school-name {
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        line-height: 1.1;
    }

    .hazard-header-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.45rem;
    }

    .hazard-system-title {
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: 0.7px;
        text-transform: uppercase;
        color: #ffffff;
        line-height: 1;
    }

    .hazard-school-selector {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.3);
        color: #ffffff;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .hazard-school-selector:hover {
        background: rgba(255,255,255,0.2);
    }

    @media (max-width: 992px) {
        .hazard-header {
            grid-template-columns: auto 1fr;
            grid-template-areas:
                'left center'
                'right right';
        }

        .hazard-header-left { grid-area: left; }
        .hazard-header-center { grid-area: center; }
        .hazard-header-right {
            grid-area: right;
            align-items: center;
        }

        .hazard-school-name {
            font-size: 1.5rem;
        }
    }

    .hazard-main-container {
        display: flex;
        height: calc(100vh - 100px);
    }

    .hazard-sidebar {
        width: 280px;
        background: #ffffff;
        border-right: 1px solid #e0e0e0;
        overflow-y: auto;
        padding: 1.5rem 0;
    }

    .hazard-sidebar-title {
        padding: 1rem 1.5rem;
        font-weight: 700;
        color: var(--hazard-header);
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .hazard-building-group {
        border-top: 1px solid #eef2f3;
    }

    .hazard-building-summary {
        list-style: none;
        cursor: pointer;
        padding: 0.85rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #1f2937;
        font-weight: 700;
        font-size: 0.92rem;
    }

    .hazard-building-summary::-webkit-details-marker {
        display: none;
    }

    .hazard-building-summary:hover {
        background: rgba(13, 115, 119, 0.06);
    }

    .hazard-building-group[open] .hazard-building-summary {
        background: rgba(13, 115, 119, 0.1);
        color: var(--hazard-header);
    }

    .hazard-floor-list {
        padding: 0.35rem 0 0.75rem;
    }

    .hazard-floor-link {
        display: block;
        margin: 0.2rem 0.85rem 0.2rem 2rem;
        padding: 0.48rem 0.8rem;
        border-radius: 6px;
        color: #4b5563;
        text-decoration: none;
        font-size: 0.86rem;
        transition: all 0.2s ease;
    }

    .hazard-floor-link:hover {
        background: rgba(13, 115, 119, 0.08);
        color: var(--hazard-header);
    }

    .hazard-floor-link.active {
        background: rgba(13, 115, 119, 0.14);
        color: var(--hazard-header);
        font-weight: 700;
    }

    .hazard-floor-item {
        padding: 1rem 1.5rem;
        border-left: 3px solid transparent;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #424242;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .hazard-floor-item:hover {
        background: rgba(76, 175, 80, 0.05);
        border-left-color: var(--hazard-accent);
    }

    .hazard-floor-item.active {
        background: rgba(76, 175, 80, 0.1);
        border-left-color: var(--hazard-primary);
        color: var(--hazard-header);
        font-weight: 600;
    }

    .floor-icon {
        font-size: 1rem;
        width: 24px;
        text-align: center;
    }

    .hazard-content {
        flex: 1;
        display: flex;
        overflow: hidden;
    }

    .hazard-map-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 2rem;
        overflow-y: auto;
    }

    .hazard-legend {
        width: 320px;
        background: #ffffff;
        border-left: 1px solid #e0e0e0;
        padding: 2rem 1.5rem;
        overflow-y: auto;
    }

    .hazard-legend-title {
        font-weight: 700;
        color: var(--hazard-header);
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
        padding: 0.75rem;
        border-radius: 6px;
        background: #f5f5f5;
    }

    .legend-color {
        width: 24px;
        height: 24px;
        border-radius: 4px;
        flex-shrink: 0;
        border: 1px solid rgba(0,0,0,0.1);
    }

    .legend-text {
        font-size: 0.9rem;
        color: #424242;
        font-weight: 500;
    }

    .floor-map {
        background: #ffffff;
        border: 2px solid #bdbdbd;
        border-radius: 8px;
        padding: 2rem;
        min-height: 500px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .floor-map-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--hazard-header);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .floor-map-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    .map-section {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .map-section h4 {
        font-weight: 600;
        color: #424242;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .map-items-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .map-item {
        padding: 0.75rem 1rem;
        background: #f5f5f5;
        border-left: 3px solid var(--hazard-accent);
        border-radius: 4px;
        font-size: 0.9rem;
        color: #424242;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #999;
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #ddd;
    }

    .hazard-context-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.35rem 0.8rem;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #0b5a5d;
        background: rgba(19, 163, 168, 0.14);
    }

    .school-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 0.75rem;
        margin-top: 1rem;
        margin-bottom: 1.25rem;
    }

    .stat-tile {
        border-radius: 10px;
        border: 1px solid #d6eced;
        background: #f8fdfd;
        padding: 0.8rem;
    }

    .stat-tile-value {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--hazard-header);
        line-height: 1;
        margin-bottom: 0.2rem;
    }

    .stat-tile-label {
        font-size: 0.78rem;
        color: #607d8b;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-weight: 700;
    }

    .building-card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .building-card {
        border: 1px solid #d5e6e6;
        border-radius: 12px;
        background: #ffffff;
        padding: 1rem;
    }

    .building-card h5 {
        margin: 0;
        font-size: 1.02rem;
        font-weight: 800;
        color: #1f2937;
    }

    .building-stack {
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
    }

    .floor-plate {
        border: 1px solid #dce8ea;
        border-radius: 10px;
        background: linear-gradient(135deg, #f8fbfb 0%, #f0f6f7 100%);
        padding: 0.9rem;
        box-shadow: 0 4px 8px rgba(15, 70, 80, 0.07);
    }

    .room-topdown-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 0.9rem;
        margin-top: 1rem;
    }

    .room-tile {
        border: 1px solid #d9e6ea;
        border-radius: 10px;
        background: #fff;
        padding: 0.85rem;
    }

    .room-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 0.6rem;
    }

    .room-code {
        font-weight: 800;
        color: #0b5a5d;
        font-size: 0.8rem;
    }

    .room-name {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1f2937;
        line-height: 1.15;
    }

    .room-meta {
        margin-top: 0.55rem;
        font-size: 0.8rem;
        color: #64748b;
    }

    .inside-grid {
        margin-top: 0.7rem;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.45rem;
    }

    .inside-chip {
        border: 1px solid #e4eef0;
        background: #f9fcfc;
        border-radius: 8px;
        padding: 0.4rem 0.5rem;
        font-size: 0.76rem;
        color: #37474f;
    }

    .inside-source {
        margin-top: 0.45rem;
        font-size: 0.72rem;
        color: #78909c;
    }

</style>
@endpush

@section('content')
<div class="d-flex flex-column" style="height: 100vh;">
    {{-- Hazard Mapping Header --}}
    <div class="hazard-header">
        <div class="hazard-header-left">
            <a href="{{ route('dashboard') }}" class="hazard-back-btn" title="Back to Main Dashboard">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="hazard-system-title"><i class="fas fa-map-marked-alt me-1"></i>Hazard Mapping System</div>
        </div>
        <div class="hazard-header-center">
            <div class="hazard-school-info">
                <div class="hazard-school-name">{{ $currentSchool->school_name ?? 'Unknown School' }}</div>
            </div>
        </div>
        <div class="hazard-header-right">
            <div class="dropdown">
                <button class="hazard-school-selector dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-exchange-alt me-2"></i> Choose Another School
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    @foreach($schools as $school)
                        <li>
                            <a class="dropdown-item" href="{{ route('hazard-mapping.dashboard', ['school_id' => $school->id]) }}">
                                {{ $school->school_name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="hazard-main-container">
        {{-- Sidebar with Entire School / Buildings / Floors --}}
        <div class="hazard-sidebar">
            <div class="hazard-sidebar-title">
                <i class="fas fa-building"></i> Buildings
            </div>

            <a class="hazard-floor-link {{ ($viewMode ?? 'floor') === 'school' ? 'active' : '' }}"
               href="{{ route('hazard-mapping.dashboard', ['school_id' => $currentSchool->id, 'view' => 'school']) }}"
               style="margin-left: 1rem; margin-right: 1rem;">
                <i class="fas fa-city me-2"></i>Entire School
            </a>

            @forelse(($buildingStructures ?? []) as $building)
                @php
                    $isOpen = (($viewMode ?? 'floor') !== 'school' && $currentBuilding && (int) $currentBuilding->id === (int) $building['id']);
                @endphp
                <details class="hazard-building-group" {{ $isOpen ? 'open' : '' }}>
                    <summary class="hazard-building-summary">
                        <span><i class="fas fa-school me-2"></i>{{ $building['name'] }}</span>
                        <i class="fas fa-chevron-down small"></i>
                    </summary>
                    <div class="hazard-floor-list">
                        <a class="hazard-floor-link {{ ($viewMode ?? 'floor') === 'building' && $currentBuilding && (int) $currentBuilding->id === (int) $building['id'] ? 'active' : '' }}"
                           href="{{ route('hazard-mapping.dashboard', ['school_id' => $currentSchool->id, 'view' => 'building', 'building_id' => $building['id']]) }}">
                            <i class="fas fa-cube me-2"></i>Building Model
                        </a>

                        @foreach(($building['floors'] ?? []) as $floor)
                            <a class="hazard-floor-link {{ ($viewMode ?? 'floor') === 'floor' && $currentBuilding && (int) $currentBuilding->id === (int) $building['id'] && (int) ($selectedFloorNumber ?? 1) === (int) $floor['number'] ? 'active' : '' }}"
                               href="{{ route('hazard-mapping.dashboard', ['school_id' => $currentSchool->id, 'view' => 'floor', 'building_id' => $building['id'], 'floor_number' => $floor['number']]) }}">
                                <i class="fas fa-layer-group me-2"></i>{{ $floor['label'] }}
                            </a>
                        @endforeach
                    </div>
                </details>
            @empty
                <div style="padding: 1rem; text-align: center; color: #999;">
                    <i class="fas fa-inbox me-2"></i> No buildings yet
                </div>
            @endforelse
        </div>

        {{-- Map and Legend Container --}}
        <div class="hazard-content">
            <div class="hazard-map-container">
                <div class="floor-map">
                    @if(($viewMode ?? 'floor') === 'school')
                        <div class="hazard-context-chip mb-2"><i class="fas fa-city"></i> Entire School View</div>
                        <div class="floor-map-title">
                            <i class="fas fa-school"></i>
                            {{ $currentSchool->school_name }} - Hazard Mapping Overview
                        </div>

                        <div class="school-grid">
                            <div class="stat-tile">
                                <div class="stat-tile-value">{{ $schoolHazardSummary['total_buildings'] ?? 0 }}</div>
                                <div class="stat-tile-label">Buildings</div>
                            </div>
                            <div class="stat-tile">
                                <div class="stat-tile-value">{{ $schoolHazardSummary['total_floors'] ?? 0 }}</div>
                                <div class="stat-tile-label">Floors</div>
                            </div>
                            <div class="stat-tile">
                                <div class="stat-tile-value">{{ $schoolHazardSummary['total_rooms'] ?? 0 }}</div>
                                <div class="stat-tile-label">Rooms</div>
                            </div>
                            <div class="stat-tile">
                                <div class="stat-tile-value">{{ $schoolHazardSummary['total_findings'] ?? 0 }}</div>
                                <div class="stat-tile-label">Summary Findings</div>
                            </div>
                        </div>

                        <div class="building-card-grid">
                            @forelse(($buildingStructures ?? []) as $building)
                                <div class="building-card">
                                    <h5>{{ $building['name'] }}</h5>
                                    <div class="small text-muted mt-1">{{ $building['floors_count'] }} floor(s), {{ $building['rooms_count'] }} room(s)</div>
                                    <div class="small mt-2">
                                        <strong>Hallways:</strong> {{ $building['hallways_count'] }}
                                        <span class="mx-2">|</span>
                                        <strong>Stairs:</strong> {{ $building['stairs_count'] }}
                                    </div>
                                    <div class="small mt-1">
                                        <strong>Functional Alarms:</strong> {{ $building['alarms_count'] }}
                                        <span class="mx-2">|</span>
                                        <strong>Active Extinguishers:</strong> {{ $building['extinguishers_count'] }}
                                    </div>
                                    <div class="small mt-1"><strong>Findings:</strong> {{ $building['findings_count'] }}</div>
                                </div>
                            @empty
                                <div class="empty-state w-100">
                                    <div class="empty-state-icon"><i class="fas fa-map-marker"></i></div>
                                    <h5>No building data yet</h5>
                                </div>
                            @endforelse
                        </div>
                    @elseif(($viewMode ?? 'floor') === 'building')
                        <div class="hazard-context-chip mb-2"><i class="fas fa-cube"></i> Building View (3D style)</div>
                        <div class="floor-map-title">
                            <i class="fas fa-building"></i>
                            {{ $currentBuildingStructure['name'] ?? ($currentBuilding->building_name ?? 'Building') }}
                        </div>

                        @if(!empty($currentBuildingStructure))
                            <div class="map-section mb-3">
                                <h4><i class="fas fa-ruler-combined" style="color: var(--hazard-primary);"></i> Structural Summary</h4>
                                <div class="map-items-list">
                                    <div class="map-item">Floors: {{ $currentBuildingStructure['floors_count'] }}</div>
                                    <div class="map-item">Rooms: {{ $currentBuildingStructure['rooms_count'] }}</div>
                                    <div class="map-item">Hallways/Pathways: {{ $currentBuildingStructure['hallways_count'] }}</div>
                                    <div class="map-item">Stairs: {{ $currentBuildingStructure['stairs_count'] }}</div>
                                </div>
                            </div>

                            <div class="building-stack">
                                @foreach(collect($currentBuildingStructure['floors'] ?? [])->sortByDesc('number') as $floor)
                                    <div class="floor-plate">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong>{{ $floor['label'] }}</strong>
                                            <span class="badge bg-secondary">{{ $floor['rooms_count'] }} room(s)</span>
                                        </div>
                                        <div class="small text-muted mt-2">Hazard findings for this floor: {{ $floor['finding_count'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="fas fa-building"></i></div>
                                <h5>No building selected</h5>
                            </div>
                        @endif
                    @else
                        <div class="hazard-context-chip mb-2"><i class="fas fa-layer-group"></i> Floor View (Topdown)</div>
                        <div class="floor-map-title">
                            <i class="fas fa-map"></i>
                            {{ $currentBuildingStructure['name'] ?? ($currentBuilding->building_name ?? 'Building') }} -
                            {{ $currentFloorStructure['label'] ?? ($currentFloor->floor_name ?? ('Floor ' . ($selectedFloorNumber ?? 1))) }}
                        </div>

                        <div class="floor-map-content">
                            <div class="map-section">
                                <h4><i class="fas fa-exclamation-triangle" style="color: var(--hazard-danger);"></i> Identified Hazards</h4>
                                <div class="map-items-list">
                                    @forelse((array) ($currentFloor->hazards ?? []) as $hazard)
                                        <div class="map-item">{{ $hazard }}</div>
                                    @empty
                                        <div style="color: #ccc; text-align: center; padding: 1rem;">No hazards recorded</div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="map-section">
                                <h4><i class="fas fa-shield-alt" style="color: var(--hazard-warning);"></i> Vulnerabilities</h4>
                                <div class="map-items-list">
                                    @forelse((array) ($currentFloor->vulnerabilities ?? []) as $vulnerability)
                                        <div class="map-item">{{ $vulnerability }}</div>
                                    @empty
                                        <div style="color: #ccc; text-align: center; padding: 1rem;">No vulnerabilities recorded</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <h4 class="mt-4 mb-2" style="font-weight: 700; color: #0f6467;">
                            <i class="fas fa-th-large me-2"></i>Topdown Room Allocation
                        </h4>

                        <div class="room-topdown-grid">
                            @forelse(($currentFloorStructure['rooms'] ?? []) as $room)
                                @php
                                    $inside = $room['inside_info'] ?? null;
                                @endphp
                                <div class="room-tile">
                                    <div class="room-heading">
                                        <div>
                                            <div class="room-code">{{ $room['room_code'] }}</div>
                                            <div class="room-name">{{ $room['room_name'] }}</div>
                                        </div>
                                        <span class="badge bg-light text-dark border">{{ ucfirst($room['room_type'] ?? 'room') }}</span>
                                    </div>

                                    <div class="small">
                                        <span class="me-2"><strong>Smoke Detector:</strong> {{ !empty($room['has_smoke_detector']) ? 'Yes' : 'No' }}</span>
                                        <span><strong>Secondary Exit:</strong> {{ !empty($room['has_secondary_exit']) ? 'Yes' : 'No' }}</span>
                                    </div>

                                    <div class="inside-grid">
                                        <div class="inside-chip"><strong>Chairs:</strong> {{ $inside['chairs_count'] ?? 0 }}</div>
                                        <div class="inside-chip"><strong>Tables:</strong> {{ $inside['tables_count'] ?? 0 }}</div>
                                        <div class="inside-chip"><strong>TV:</strong> {{ $inside['tv_count'] ?? 0 }}</div>
                                        <div class="inside-chip"><strong>Electric Fans:</strong> {{ $inside['electric_fan_count'] ?? 0 }}</div>
                                        <div class="inside-chip"><strong>Ceiling Fans:</strong> {{ $inside['ceiling_fan_count'] ?? 0 }}</div>
                                        <div class="inside-chip"><strong>Water Dispensers:</strong> {{ $inside['water_dispenser_count'] ?? 0 }}</div>
                                        <div class="inside-chip" style="grid-column: 1 / -1;"><strong>Window Type:</strong> {{ $inside['window_type'] ?? 'N/A' }}</div>
                                    </div>

                                    <div class="inside-source">{{ $inside['source_note'] ?? 'No inside inventory yet. Add via Comprehensive Summary of Findings.' }}</div>
                                    <div class="room-meta">{{ !empty($room['remarks']) ? $room['remarks'] : 'No room remarks.' }}</div>
                                </div>
                            @empty
                                <div class="empty-state" style="grid-column: 1 / -1;">
                                    <div class="empty-state-icon"><i class="fas fa-door-open"></i></div>
                                    <h5>No room structure found for this floor</h5>
                                    <p>Encode rooms in Fire Safety to generate topdown floor mapping here.</p>
                                </div>
                            @endforelse
                        </div>
                    @endif
                </div>
            </div>

            <div class="hazard-legend">
                <div class="hazard-legend-title">
                    <i class="fas fa-list me-2"></i>Legend
                </div>

                <div class="legend-item">
                    <div class="legend-color" style="background: rgba(211, 47, 47, 0.2); border-color: #d32f2f;"></div>
                    <div class="legend-text">Hazard Areas / High-Risk Findings</div>
                </div>

                <div class="legend-item">
                    <div class="legend-color" style="background: rgba(76, 175, 80, 0.2); border-color: #4caf50;"></div>
                    <div class="legend-text">Safe Zones / Prepared Spaces</div>
                </div>

                <div class="legend-item">
                    <div class="legend-color" style="background: rgba(245, 124, 0, 0.2); border-color: #f57c00;"></div>
                    <div class="legend-text">Evacuation Routes</div>
                </div>

                <div class="legend-item">
                    <div class="legend-color" style="background: rgba(33, 150, 243, 0.2); border-color: #2196f3;"></div>
                    <div class="legend-text">Room Inside Information (from Comprehensive)</div>
                </div>

                <hr style="margin: 1.5rem 0; border: none; border-top: 1px solid #e0e0e0;">

                <div style="font-size: 0.85rem; color: #607d8b;">
                    <p><strong>View Behavior:</strong></p>
                    <ul style="padding-left: 1rem; margin-top: 0.75rem;">
                        <li><strong>Entire School:</strong> cross-building summary layout</li>
                        <li><strong>Building:</strong> vertical structural model per floor</li>
                        <li><strong>Floor:</strong> topdown room allocation with furniture/equipment counts</li>
                    </ul>
                    <p class="mb-0">Data source: Fire Safety structure + Comprehensive Summary of Findings.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
