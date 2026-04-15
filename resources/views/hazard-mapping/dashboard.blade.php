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
        {{-- Sidebar with Buildings/Floors --}}
        <div class="hazard-sidebar">
            <div class="hazard-sidebar-title">
                <i class="fas fa-building"></i> Buildings
            </div>

            @forelse($buildings ?? collect() as $building)
                @php
                    $buildingFloors = $buildingFloorMap[$building->id] ?? [1];
                    $isOpen = ($currentBuilding && (int) $currentBuilding->id === (int) $building->id) || (!$currentBuilding && $loop->first);
                @endphp
                <details class="hazard-building-group" {{ $isOpen ? 'open' : '' }}>
                    <summary class="hazard-building-summary">
                        <span><i class="fas fa-school me-2"></i>{{ $building->building_name ?: ('Building ' . $building->building_no) }}</span>
                        <i class="fas fa-chevron-down small"></i>
                    </summary>
                    <div class="hazard-floor-list">
                        @foreach($buildingFloors as $floorNo)
                            @php
                                $isActiveFloor = $currentFloor && (int) $currentFloor->floor_number === (int) $floorNo && $currentBuilding && (int) $currentBuilding->id === (int) $building->id;
                            @endphp
                            <a class="hazard-floor-link {{ $isActiveFloor ? 'active' : '' }}"
                               href="{{ route('hazard-mapping.dashboard', ['school_id' => $currentSchool->id, 'building_id' => $building->id, 'floor_number' => $floorNo]) }}">
                                <i class="fas fa-layer-group me-2"></i>Floor {{ $floorNo }}
                            </a>
                        @endforeach
                    </div>
                </details>
            @empty
                <div style="padding: 1rem; text-align: center; color: #999;">
                    <i class="fas fa-layer-group me-2"></i> Floors
                </div>
                @forelse($floors as $floor)
                    <div class="hazard-floor-item {{ $floor->id == $currentFloor->id ? 'active' : '' }}"
                         onclick="window.location.href='{{ route('hazard-mapping.dashboard', ['school_id' => $currentSchool->id, 'floor_number' => $floor->floor_number]) }}'">
                        <div class="floor-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600;">{{ $floor->floor_name }}</div>
                            <div style="font-size: 0.8rem; color: #999;">Floor {{ $floor->floor_number }}</div>
                        </div>
                    </div>
                @empty
                    <div style="padding: 1rem; text-align: center; color: #999;">
                        <i class="fas fa-inbox me-2"></i> No floors added yet
                    </div>
                @endforelse
            @endforelse
        </div>

        {{-- Map and Legend Container --}}
        <div class="hazard-content">
            {{-- Main Map Area --}}
            <div class="hazard-map-container">
                <div class="floor-map">
                    <div class="floor-map-title">
                        <i class="fas fa-map"></i>
                        @if($currentBuilding)
                            {{ $currentBuilding->building_name ?: ('Building ' . $currentBuilding->building_no) }} -
                        @endif
                        {{ $currentFloor->floor_name ?? ('Floor ' . ($selectedFloorNumber ?? 1)) }} - Hazard Mapping
                    </div>

                    @if($currentFloor->hazards || $currentFloor->vulnerabilities || $currentFloor->evacuation_routes)
                        <div class="floor-map-content">
                            {{-- Hazards Section --}}
                            <div class="map-section">
                                <h4>
                                    <i class="fas fa-exclamation-triangle" style="color: var(--hazard-danger);"></i>
                                    Identified Hazards
                                </h4>
                                <div class="map-items-list">
                                    @if($currentFloor->hazards && count($currentFloor->hazards) > 0)
                                        @foreach($currentFloor->hazards as $hazard)
                                            <div class="map-item">{{ $hazard }}</div>
                                        @endforeach
                                    @else
                                        <div style="color: #ccc; text-align: center; padding: 2rem;">No hazards recorded</div>
                                    @endif
                                </div>
                            </div>

                            {{-- Vulnerabilities Section --}}
                            <div class="map-section">
                                <h4>
                                    <i class="fas fa-shield-alt" style="color: var(--hazard-warning);"></i>
                                    Vulnerabilities
                                </h4>
                                <div class="map-items-list">
                                    @if($currentFloor->vulnerabilities && count($currentFloor->vulnerabilities) > 0)
                                        @foreach($currentFloor->vulnerabilities as $vulnerability)
                                            <div class="map-item">{{ $vulnerability }}</div>
                                        @endforeach
                                    @else
                                        <div style="color: #ccc; text-align: center; padding: 2rem;">No vulnerabilities recorded</div>
                                    @endif
                                </div>
                            </div>

                            {{-- Evacuation Routes --}}
                            <div class="map-section">
                                <h4>
                                    <i class="fas fa-directions" style="color: var(--hazard-primary);"></i>
                                    Evacuation Routes
                                </h4>
                                <div class="map-items-list">
                                    @if($currentFloor->evacuation_routes && count($currentFloor->evacuation_routes) > 0)
                                        @foreach($currentFloor->evacuation_routes as $route)
                                            <div class="map-item">{{ $route }}</div>
                                        @endforeach
                                    @else
                                        <div style="color: #ccc; text-align: center; padding: 2rem;">No evacuation routes defined</div>
                                    @endif
                                </div>
                            </div>

                            {{-- Safe Zones --}}
                            <div class="map-section">
                                <h4>
                                    <i class="fas fa-check-circle" style="color: var(--hazard-safe);"></i>
                                    Safe Zones
                                </h4>
                                <div class="map-items-list">
                                    @if($currentFloor->safe_zones && count($currentFloor->safe_zones) > 0)
                                        @foreach($currentFloor->safe_zones as $zone)
                                            <div class="map-item">{{ $zone }}</div>
                                        @endforeach
                                    @else
                                        <div style="color: #ccc; text-align: center; padding: 2rem;">No safe zones defined</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-map-marker"></i>
                            </div>
                            <h5>No hazard mapping data available</h5>
                            <p>Start by adding hazards, vulnerabilities, and evacuation information for this floor.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Legend Sidebar --}}
            <div class="hazard-legend">
                <div class="hazard-legend-title">
                    <i class="fas fa-list me-2"></i>Legend
                </div>

                <div class="legend-item">
                    <div class="legend-color" style="background: rgba(211, 47, 47, 0.2); border-color: #d32f2f;"></div>
                    <div class="legend-text">Hazard Areas</div>
                </div>

                <div class="legend-item">
                    <div class="legend-color" style="background: rgba(76, 175, 80, 0.2); border-color: #4caf50;"></div>
                    <div class="legend-text">Safe Zones</div>
                </div>

                <div class="legend-item">
                    <div class="legend-color" style="background: rgba(245, 124, 0, 0.2); border-color: #f57c00;"></div>
                    <div class="legend-text">Evacuation Routes</div>
                </div>

                <div class="legend-item">
                    <div class="legend-color" style="background: rgba(244, 67, 54, 0.2); border-color: #f44336;"></div>
                    <div class="legend-text">Vulnerabilities</div>
                </div>

                <div class="legend-item">
                    <div class="legend-color" style="background: rgba(33, 150, 243, 0.2); border-color: #2196f3;"></div>
                    <div class="legend-text">Assembly Points</div>
                </div>

                <hr style="margin: 1.5rem 0; border: none; border-top: 1px solid #e0e0e0;">

                <div style="font-size: 0.85rem; color: #999;">
                    <p><strong>Hazard Mapping Overview:</strong></p>
                    <ul style="padding-left: 1rem; margin-top: 0.75rem;">
                        <li>Identify all potential hazards on each floor</li>
                        <li>Mark safe zones and assembly points</li>
                        <li>Clearly identify evacuation routes</li>
                        <li>Document vulnerabilities and risks</li>
                        <li>Update regularly based on changes</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
