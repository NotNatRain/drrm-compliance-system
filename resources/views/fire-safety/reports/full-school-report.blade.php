<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Full Fire Safety Report - {{ $school->school_name ?? 'School' }}</title>
    <style>
        @page {
            size: landscape;
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        .no-print {
            background: #f8f9fa;
            border-bottom: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .btn-print {
            background: #A8191F;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
            font-weight: bold;
        }

        .page {
            padding: 1cm;
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .header-container {
            position: relative;
            height: 80px;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .logo-left {
            position: absolute;
            left: 0;
            top: 0;
            display: flex;
            align-items: center;
        }

        .logo-left img {
            height: 60px;
            margin-right: 10px;
        }

        .title-center {
            width: 100%;
            text-align: center;
            padding-left: 430px;
            padding-right: 30px;
            box-sizing: border-box;
        }

        .info-grid {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            text-transform: uppercase;
            font-size: 10px;
        }

        thead {
            display: table-header-group;
        }

        tr {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .print-page-break-row td {
            border: 0 !important;
            padding: 0 !important;
            height: 0 !important;
            font-size: 0 !important;
            line-height: 0 !important;
        }

        .print-page-break-row {
            page-break-after: always;
            break-after: page;
        }

        .vertical-th {
            height: 140px;
            white-space: nowrap;
            vertical-align: bottom;
            padding-bottom: 10px;
            text-align: center;
        }

        .vertical-th div {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            display: inline-block;
            word-break: break-word;
            white-space: pre-line;
            max-width: 60px;
            line-height: 1.2;
            text-align: center;
            padding: 5px 0;
        }

        .center-text {
            text-align: center;
        }

        .purpose {
            margin-top: 20px;
            font-style: italic;
            color: #555;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 2px solid #000;
            padding-bottom: 4px;
            margin-top: 28px;
            margin-bottom: 8px;
        }

        .signatures {
            margin-top: 35px;
            display: flex;
            justify-content: space-between;
            padding: 0 50px;
        }

        .sig-col {
            text-align: center;
        }

        .uncovered {
            color: #c0392b;
            font-weight: bold;
        }

        .no-data-box {
            text-align: center;
            padding: 30px;
            border: 1px dashed #ccc;
            color: #999;
            font-style: italic;
        }

        .plan-card {
            border: 1px solid #ccc;
            margin-bottom: 15px;
            padding: 15px;
            page-break-inside: avoid;
        }

        .plan-header {
            background-color: #f2f2f2;
            padding: 8px 12px;
            margin: -15px -15px 15px -15px;
            border-bottom: 1px solid #ccc;
            font-weight: bold;
            font-size: 13px;
        }

        .school-plan-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .school-plan-table th,
        .school-plan-table td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }

        .school-plan-table th {
            background: #f5f5f5;
            font-size: 11px;
            text-transform: uppercase;
            color: #555;
            width: 35%;
        }

        .map-header {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .map-header-left,
        .map-header-center,
        .map-header-right {
            display: table-cell;
            vertical-align: middle;
        }

        .map-header-left { width: 30%; }
        .map-header-center { width: 40%; text-align: center; }
        .map-header-right { width: 30%; text-align: right; }

        .map-logos {
            display: flex;
            align-items: center;
        }

        .map-logos img {
            height: 35px;
            margin-right: 4px;
        }

        .map-canvas {
            position: relative;
            width: 100%;
            height: 800px;
            background: #e9ecef;
            border: 2px solid #333;
            overflow: hidden;
            border-radius: 4px;
            box-shadow: inset 0 0 20px rgba(0,0,0,0.1);
        }

        .map-box-item {
            position: absolute;
            border: 2px solid rgba(0, 0, 0, 0.25);
            border-radius: 4px;
            color: #111;
            font-size: 10px;
            font-weight: bold;
            overflow: hidden;
            padding: 4px;
            box-sizing: border-box;
            background: rgba(255,255,255,0.9);
        }

        .map-facility {
            color: #fff;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.6);
            border-color: rgba(0,0,0,0.2);
        }

        .map-specific {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            background: #fff;
        }

        .map-legend {
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            background: #fff;
            font-size: 10px;
        }

        .map-legend-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 8px;
        }

        .map-legend-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            color: #555;
            font-size: 11px;
            margin-top: 8px;
        }

        .map-legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .map-source-label {
            margin-top: 8px;
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #444;
        }

        .attached-map-frame {
            width: 100%;
            height: 800px;
            border: 2px solid #333;
            border-radius: 4px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .attached-map-frame img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">Print Report</button>
        <button class="btn-print" style="background: #6c757d;" onclick="window.close()">Close</button>
        <p class="small text-muted mb-0 mt-2">This merged report combines Buildings, Alarm Details, Extinguisher Details, Evacuation Plans, and Evacuation Map in one printable file.</p>
    </div>

    @php $printRowLimit = 18; @endphp

    {{-- SECTION 1: BUILDING SUMMARY --}}
    <div class="page">
        <div class="header-container">
            <div class="logo-left">
                <img src="{{ asset('images/Layer-0-1.png') }}" alt="Logo 1">
                <img src="{{ asset('images/What-Is-the-Difference-Between-DepEd-Seal-and-DepEd-Logo.png') }}" alt="Logo 2">
                <img src="{{ asset('images/drrmis-logo-2.png') }}" alt="Logo 3">
                <div style="text-align: left;">
                    <h2 style="margin: 0; font-size: 16px; font-weight: bold; text-transform: uppercase;">DepEd DRRM</h2>
                </div>
            </div>
            <div class="title-center">
                <h1 style="margin: 0; font-size: 14px; font-weight: normal; text-transform: uppercase;">School's Summarization of Fire Safety - Buildings</h1>
            </div>
        </div>

        <div class="info-grid" style="display: grid; grid-template-columns: 1fr 1fr;">
            <div><strong>Name of School:</strong> {{ $school->school_name }}</div>
            <div><strong>Name of School Head:</strong> {{ $school->school_head }}</div>
            <div><strong>School ID:</strong> {{ $school->school_id }}</div>
            <div><strong>Name of School DRRM Coordinator:</strong> {{ $school->school_drrm_coordinator }}</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">BUILDING NUMBER</th>
                    <th style="width: 10%; text-align: center;">BUILDING NAME</th>
                    <th class="vertical-th"><div>NUMBER OF FLOOR</div></th>
                    <th class="vertical-th"><div>IS THE BUILDING WITH SECONDARY EXIT FOR (2-4 STOREY BUILDING)</div></th>
                    <th class="vertical-th"><div>NUMBER OF CLASSROOMS</div></th>
                    <th class="vertical-th"><div>NUMBER OF ROOMS WITHOUT SECONDARY EXIT</div></th>
                    <th class="vertical-th"><div>NUMBER OF LABORATORIES</div></th>
                    <th class="vertical-th"><div>NUMBER OF ADMINISTRATIVE OFFICE</div></th>
                    <th class="vertical-th"><div>NUMBER OF REQUIRED FIRE EXTINGUISHER</div></th>
                    <th class="vertical-th"><div>NUMBER OF ACTIVE FIRE EXTINGUISHER</div></th>
                    <th class="vertical-th"><div>SMOKE DETECTOR</div></th>
                    <th class="vertical-th"><div>ALARMS</div></th>
                    <th style="width: 10%; text-align: center;">REMARKS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($school->buildings as $building)
                    @php
                        $roomsForBuilding = $building->actualRooms;
                        $classrooms = $roomsForBuilding->filter(function($room) {
                            $configName = isset($room->roomTypeConfig->name) ? strtolower($room->roomTypeConfig->name) : '';
                            $typeName = strtolower($room->room_type ?? '');
                            return stripos($configName, 'classroom') !== false || stripos($typeName, 'classroom') !== false;
                        })->count();

                        $laboratories = $roomsForBuilding->filter(function($room) {
                            return strtolower($room->room_type ?? '') === 'laboratory' || (isset($room->roomTypeConfig->name) && stripos($room->roomTypeConfig->name, 'lab') !== false);
                        })->count();

                        $adminRooms = $roomsForBuilding->filter(function($room) {
                            $typeName = strtolower($room->room_type ?? '');
                            $configName = isset($room->roomTypeConfig->name) ? strtolower($room->roomTypeConfig->name) : '';
                            return strpos($typeName, 'administration') !== false || strpos($typeName, 'office') !== false || strpos($typeName, 'administrative') !== false || strpos($configName, 'administration') !== false || strpos($configName, 'office') !== false || strpos($configName, 'admin') !== false;
                        });
                        $offices = $adminRooms->count();

                        $roomsWithoutSecondaryExit = $roomsForBuilding->filter(function($room) {
                            return !$room->has_secondary_exit || $room->has_secondary_exit == '0';
                        })->count();

                        $requiredExtinguishers = $building->required_extinguishers ?? $building->requiredExtinguishersCount;

                        $allExtinguishers = $building->fireExtinguishers;
                        $activeCount = 0;
                        $extinguisherIssues = [];
                        foreach($allExtinguishers as $ext) {
                            $status = strtolower($ext->status ?? '');
                            if (in_array($status, ['active', 'ok', 'operational', 'okay'])) {
                                $activeCount++;
                            } else {
                                $code = match($status) {
                                    'purchase', 'for_purchase' => 'FP',
                                    'maintenance', 'refill' => 'FPM',
                                    'decommissioned', 'broken' => 'DC',
                                    'missing' => 'MS',
                                    'expired' => 'FPM',
                                    default => 'FPM'
                                };
                                $extinguisherIssues[] = $ext->code . ' ' . $code;
                            }
                        }
                        $extinguisherBg = ($activeCount >= $requiredExtinguishers) ? '#90EE90' : '#e20707';
                        $extinguisherContent = empty($extinguisherIssues) ? $activeCount : implode(', ', $extinguisherIssues);

                        $secExitBg = '';
                        $secExitContent = 'N/A';
                        if ($building->floors >= 2 && $building->floors <= 4) {
                            if (($building->emergency_exits ?? 0) >= 2) {
                                $secExitContent = 'Yes';
                                $secExitBg = '#90EE90';
                            } else {
                                $secExitContent = 'No';
                                $secExitBg = '#e20707';
                            }
                        }

                        $buildingAlarms = $building->alarmSystemsMany;
                        if ($buildingAlarms->isEmpty()) {
                            $buildingAlarms = $building->alarmSystems;
                        }
                        $alarmBg = '#e20707';
                        $alarmContentParts = [];
                        $hasBroken = false;
                        $hasCovering = false;
                        $hasAlarm = false;
                        if ($buildingAlarms->count() > 0) {
                            $hasAlarm = true;
                            foreach($buildingAlarms as $alarmRow) {
                                $status = strtolower($alarmRow->status ?? '');
                                $isFunctional = in_array($status, ['active', 'functional', 'ok', 'online']);
                                $inPivot = $building->alarmSystemsMany->pluck('id')->contains($alarmRow->id);
                                $inPrimary = (int) ($alarmRow->building_id ?? 0) === (int) $building->id;
                                if (!$isFunctional) {
                                    $hasBroken = true;
                                }
                                if ($inPivot && !$inPrimary) {
                                    $hasCovering = true;
                                }
                                $typeChar = 'O';
                                if (stripos($alarmRow->alarm_type ?? '', 'Bell') !== false) $typeChar = 'B';
                                elseif (stripos($alarmRow->alarm_type ?? '', 'Mechanical') !== false) $typeChar = 'M';
                                elseif (stripos($alarmRow->alarm_type ?? '', 'Digital') !== false) $typeChar = 'D';
                                else $typeChar = strtoupper(substr((string)($alarmRow->alarm_type ?? 'AL'), 0, 2));
                                $alarmContentParts[] = ($alarmRow->code ?? 'N/A') . ' ' . $typeChar . (($inPivot && !$inPrimary) ? ' (Covering)' : '');
                            }

                            if ($hasBroken) {
                                $alarmBg = '#add8e6';
                            } elseif ($hasCovering) {
                                $alarmBg = '#FFFF00';
                            } else {
                                $alarmBg = '#90EE90';
                            }
                        }
                        $alarmContent = $hasAlarm ? implode(', ', $alarmContentParts) : '';

                        $adminRoomsRequiring = $adminRooms->where('smoke_detector_required', true);
                        $adminRoomsRequiringCount = $adminRoomsRequiring->count();
                        $smokeDetectorCount = $adminRoomsRequiring->where('has_smoke_detector', true)->count();

                        if ($adminRoomsRequiringCount === 0) {
                            $smokeDetectorContent = 'N/A';
                            $smokeDetectorBg = '';
                        } elseif ($smokeDetectorCount === $adminRoomsRequiringCount) {
                            $smokeDetectorContent = $smokeDetectorCount;
                            $smokeDetectorBg = '#90EE90';
                        } else {
                            $smokeDetectorContent = $smokeDetectorCount;
                            $smokeDetectorBg = '#e20707';
                        }
                    @endphp
                    <tr>
                        <td class="center-text">{{ $building->building_no }}</td>
                        <td class="center-text">{{ $building->building_name }}</td>
                        <td class="center-text">{{ $building->floors }}</td>
                        <td class="center-text" style="background-color: {{ $secExitBg }};">{{ $secExitContent }}</td>
                        <td class="center-text">{{ $classrooms }}</td>
                        <td class="center-text">{{ $roomsWithoutSecondaryExit }}</td>
                        <td class="center-text">{{ $laboratories }}</td>
                        <td class="center-text">{{ $offices }}</td>
                        <td class="center-text">{{ $requiredExtinguishers }}</td>
                        <td class="center-text" style="background-color: {{ $extinguisherBg }};">{{ $extinguisherContent }}</td>
                        <td class="center-text" style="background-color: {{ $smokeDetectorBg }};">{{ $smokeDetectorContent }}</td>
                        <td class="center-text" style="background-color: {{ $alarmBg }};">{{ $alarmContent }}</td>
                        <td class="center-text">{{ $building->description }}</td>
                    </tr>
                    @if(($loop->iteration % max(1, (int)$printRowLimit)) === 0 && !$loop->last)
                        <tr class="print-page-break-row"><td colspan="13"></td></tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <div class="signatures">
            <div class="sig-col">
                <p>Prepared by:</p>
                <br><br>
                <p><strong>{{ $school->school_drrm_coordinator }}</strong></p>
                <p>School DRRM Coordinator</p>
            </div>
            <div class="sig-col">
                <p>Certified Correct:</p>
                <br><br>
                <p><strong>{{ $school->school_head }}</strong></p>
                <p>School Head / Principal</p>
            </div>
        </div>
    </div>

    {{-- SECTION 2: ALARM DETAILS --}}
    <div class="page">
        <div class="header-container">
            <div class="logo-left">
                <img src="{{ asset('images/Layer-0-1.png') }}" alt="Logo 1">
                <img src="{{ asset('images/What-Is-the-Difference-Between-DepEd-Seal-and-DepEd-Logo.png') }}" alt="Logo 2">
                <img src="{{ asset('images/drrmis-logo-2.png') }}" alt="Logo 3">
                <div style="text-align: left;">
                    <h2 style="margin: 0; font-size: 16px; font-weight: bold; text-transform: uppercase;">DepEd DRRM</h2>
                </div>
            </div>
            <div class="title-center">
                <h1 style="margin: 0; font-size: 14px; font-weight: normal; text-transform: uppercase;">Fire Alarm System Inspection and Coverage Details</h1>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-row">
                <div><strong>Name of School:</strong> {{ $school->school_name }}</div>
                <div><strong>Name of School Head:</strong> {{ $school->school_head }}</div>
            </div>
            <div class="info-row">
                <div><strong>School ID:</strong> {{ $school->school_id }}</div>
                <div><strong>DRRM Coordinator:</strong> {{ $school->school_drrm_coordinator }}</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Alarm Code</th>
                    <th>Assigned Building</th>
                    <th>Alarm Type</th>
                    <th>Status</th>
                    <th>Last Date Checked</th>
                    <th>Next Test Due</th>
                    <th style="width: 20%;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alarms as $alarm)
                    <tr>
                        <td><strong>{{ $alarm->code }}</strong></td>
                        <td>
                            @php
                                $primaryB = $alarm->building;
                                $primaryLabel = $primaryB
                                    ? trim($primaryB->building_no . ($primaryB->building_name ? ' (' . $primaryB->building_name . ')' : ''))
                                    : 'N/A';
                                $others = $alarm->buildings ? $alarm->buildings->filter(fn ($b) => (int) $b->id !== (int) $alarm->building_id) : collect();
                                $otherLabels = $others->map(function ($b) {
                                    return trim($b->building_no . ($b->building_name ? ' (' . $b->building_name . ')' : ''));
                                })->filter()->values();
                            @endphp
                            Installed: {{ $primaryLabel }}@if($otherLabels->isNotEmpty())<br><small>Covering: {{ $otherLabels->implode(', ') }}</small>@endif
                        </td>
                        <td>{{ $alarm->alarm_type }}</td>
                        <td class="center-text"><strong>{{ strtoupper(str_ireplace('broken', 'defective', $alarm->status)) }}</strong></td>
                        <td>{{ $alarm->last_test ? \Carbon\Carbon::parse($alarm->last_test)->format('M d, Y') : 'N/A' }}</td>
                        <td>{{ $alarm->next_test_due ? \Carbon\Carbon::parse($alarm->next_test_due)->format('M d, Y') : 'N/A' }}</td>
                        <td>{{ $alarm->notes }}</td>
                    </tr>
                    @if(($loop->iteration % max(1, (int)$printRowLimit)) === 0 && !$loop->last)
                        <tr class="print-page-break-row"><td colspan="7"></td></tr>
                    @endif
                @endforeach
                @if($alarms->isEmpty())
                    <tr>
                        <td colspan="7" class="center-text" style="padding: 20px;">No alarm systems recorded for this school.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="purpose">
            <p><strong>Overall Purpose:</strong> This sheet is designed for monitoring the alarm's overall coverage of the school, its status, and the type of alarm system installed.</p>
        </div>

        <div class="signatures">
            <div class="sig-col">
                <p>Prepared by:</p>
                <br><br>
                <p>_______________________</p>
                <p>School DRRM Coordinator</p>
            </div>
            <div class="sig-col">
                <p>Noted by:</p>
                <br><br>
                <p>_______________________</p>
                <p>School Head / Principal</p>
            </div>
        </div>
    </div>

    {{-- SECTION 3: EXTINGUISHER + ROOMS --}}
    <div class="page">
        <div class="header-container">
            <div class="logo-left">
                <img src="{{ asset('images/Layer-0-1.png') }}" alt="Logo 1">
                <img src="{{ asset('images/What-Is-the-Difference-Between-DepEd-Seal-and-DepEd-Logo.png') }}" alt="Logo 2">
                <img src="{{ asset('images/drrmis-logo-2.png') }}" alt="Logo 3">
                <div style="text-align: left;">
                    <h2 style="margin: 0; font-size: 16px; font-weight: bold; text-transform: uppercase;">DepEd DRRM</h2>
                </div>
            </div>
            <div class="title-center">
                <h1 style="margin: 0; font-size: 14px; font-weight: normal; text-transform: uppercase;">Fire Extinguisher Inspection and Coverage Details</h1>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-row">
                <div><strong>Name of School:</strong> {{ $school->school_name }}</div>
                <div><strong>Name of School Head:</strong> {{ $school->school_head }}</div>
            </div>
            <div class="info-row">
                <div><strong>School ID:</strong> {{ $school->school_id }}</div>
                <div><strong>DRRM Coordinator:</strong> {{ $school->school_drrm_coordinator }}</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Extinguisher Code</th>
                    <th>Building</th>
                    <th>Room Covered / Location</th>
                    <th>Status</th>
                    <th>Date Last Checked</th>
                    <th>Type</th>
                    <th style="width: 25%;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($extinguishers as $ext)
                    <tr>
                        <td><strong>{{ $ext->code }}</strong></td>
                        <td>
                            {{ $ext->building ? trim(($ext->building->building_no ?: 'N/A') . ($ext->building->building_name ? ' (' . $ext->building->building_name . ')' : '')) : 'N/A' }}
                        </td>
                        <td>
                            {{ $ext->centerRoom ? $ext->centerRoom->room_name : 'N/A' }}
                            @php
                                $otherRooms = $ext->coveredRooms ? $ext->coveredRooms->filter(fn($r) => $ext->centerRoom && $r->id !== $ext->centerRoom->id) : collect();
                            @endphp
                            @if($otherRooms->count() > 0)
                                <small class="text-muted d-block">({{ $otherRooms->map(fn($r) => 'Room ' . ($r->room_code ?: $r->room_name))->implode(' & ') }} taking cover)</small>
                            @endif
                        </td>
                        @php
                            $statusValue = strtolower(trim((string) ($ext->status ?? '')));
                            if (in_array($statusValue, ['maintenance', 'preventive_maintenance', 'for_preventive_maintenance'])) {
                                $statusLabel = 'FOR PREVENTIVE<br>MAINTENANCE';
                            } elseif (in_array($statusValue, ['purchase', 'for_purchase'])) {
                                $statusLabel = 'FOR<br>PURCHASE';
                            } else {
                                $statusLabel = strtoupper(str_replace('_', ' ', (string) ($ext->status ?? 'N/A')));
                            }
                        @endphp
                        <td class="center-text"><strong style="display:inline-block; line-height:1.1;">{!! $statusLabel !!}</strong></td>
                        <td>{{ $ext->date_checked ? \Carbon\Carbon::parse($ext->date_checked)->format('M d, Y') : 'N/A' }}</td>
                        <td>{{ $ext->type }}</td>
                        <td>{{ $ext->remarks }} {{ $ext->notes }}</td>
                    </tr>
                    @if(($loop->iteration % max(1, (int)$printRowLimit)) === 0 && !$loop->last)
                        <tr class="print-page-break-row"><td colspan="7"></td></tr>
                    @endif
                @endforeach

                @php
                    $needed = (int) ($school->buildings->sum(function ($building) {
                        return (int) ($building->requiredExtinguishersCount ?? 0);
                    }));
                    $existing = (int) $extinguishers->count();
                    $passed = (int) $extinguishers->filter(function ($ext) {
                        return strtolower((string) ($ext->evaluation_result ?? '')) === 'passed';
                    })->count();
                    $summaryRemarks = ($needed > 0 && $existing >= $needed && $passed >= $needed) ? 'Passed' : 'Needs Attention';

                    $activeCount = $extinguishers->where('status', 'active')->count();
                    $maintenanceCount = $extinguishers->where('status', 'maintenance')->count();
                    $usedCount = $extinguishers->where('status', 'expired')->count();
                    $missingCount = $extinguishers->where('status', 'missing')->count();
                    $purchaseCount = $extinguishers->filter(function($ext) {
                        $status = strtolower((string) ($ext->status ?? ''));
                        return in_array($status, ['purchase', 'for_purchase']);
                    })->count();
                @endphp
                <tr style="background-color: #f7f7f7;">
                    <td><strong>Summary</strong></td>
                    <td><strong>Existing / Needed:</strong> {{ $existing }} / {{ $needed }}</td>
                    <td><strong>Passed / Needed:</strong> {{ $passed }} / {{ $needed }}</td>
                    <td class="center-text"><strong>{{ $summaryRemarks }}</strong></td>
                    <td colspan="3">—</td>
                </tr>
                <tr style="background-color: #f0f4f7;">
                    <td><strong>Status Totals</strong></td>
                    <td><strong>Active:</strong> {{ $activeCount }}</td>
                    <td><strong>For Preventive Maintenance:</strong> {{ $maintenanceCount }}</td>
                    <td><strong>Used:</strong> {{ $usedCount }}</td>
                    <td><strong>Missing:</strong> {{ $missingCount }}</td>
                    <td><strong>For Purchase:</strong> {{ $purchaseCount }}</td>
                    <td>—</td>
                </tr>

                @if($extinguishers->isEmpty())
                    <tr>
                        <td colspan="7" class="center-text" style="padding: 20px;">No fire extinguishers recorded for this school.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="purpose">
            <p><strong>Overall Purpose:</strong> This sheet is designed for equipment-level tracking, allowing monitoring of maintenance schedules, operational readiness, placement coverage, and compliance documentation.</p>
        </div>

        <div class="signatures">
            <div class="sig-col">
                <p>Prepared by:</p>
                <br><br>
                <p>_______________________</p>
                <p>School DRRM Coordinator</p>
            </div>
            <div class="sig-col">
                <p>Approved by:</p>
                <br><br>
                <p>_______________________</p>
                <p>School Head / Principal</p>
            </div>
        </div>
    </div>

    <div class="page">
        <div class="header-container">
            <div class="logo-left">
                <img src="{{ asset('images/Layer-0-1.png') }}" alt="Logo 1">
                <img src="{{ asset('images/What-Is-the-Difference-Between-DepEd-Seal-and-DepEd-Logo.png') }}" alt="Logo 2">
                <img src="{{ asset('images/drrmis-logo-2.png') }}" alt="Logo 3">
                <div style="text-align: left;">
                    <h2 style="margin: 0; font-size: 16px; font-weight: bold; text-transform: uppercase;">DepEd DRRM</h2>
                </div>
            </div>
            <div class="title-center">
                <h1 style="margin: 0; font-size: 14px; font-weight: normal; text-transform: uppercase;">Building Room Details & Coverage</h1>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-row">
                <div><strong>Name of School:</strong> {{ $school->school_name }}</div>
                <div><strong>School ID:</strong> {{ $school->school_id }}</div>
            </div>
        </div>

        <div class="section-title">Room Information</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 3%; text-align: center;">#</th>
                    <th style="width: 7%;">Room Code</th>
                    <th style="width: 13%;">Room Name</th>
                    <th style="width: 12%;">Building</th>
                    <th style="width: 4%; text-align: center;">Floor</th>
                    <th style="width: 13%;">Room Type</th>
                    <th style="width: 6%; text-align: center;">2nd Exit</th>
                    <th style="width: 8%; text-align: center;">Smoke Detector</th>
                    <th style="width: 14%;">Covered By</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rooms as $index => $room)
                    @php
                        $coveringExts = $room->extinguishersCoveringThisRoom ?? collect();
                        $hostedExt = $room->hostedExtinguisher ?? null;
                        $isUncovered = $coveringExts->isEmpty();
                    @endphp
                    <tr>
                        <td class="center-text">{{ $index + 1 }}</td>
                        <td>{{ $room->room_code ?: '—' }}</td>
                        <td>{{ $room->room_name }}</td>
                        <td>{{ $room->building ? ($room->building->building_no . ($room->building->building_name ? ' - ' . $room->building->building_name : '')) : 'N/A' }}</td>
                        <td class="center-text">{{ $room->floor_no ?? '—' }}</td>
                        <td>{{ $room->roomTypeConfig?->name ?? $room->room_type }}</td>
                        <td class="center-text">{{ $room->has_secondary_exit ? 'Yes' : 'No' }}</td>
                        <td class="center-text">
                            @if($room->smoke_detector_required)
                                {{ $room->has_smoke_detector ? 'Yes' : 'No' }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if($isUncovered)
                                <span class="uncovered">Uncovered</span>
                            @else
                                @foreach($coveringExts as $covExt)
                                    <strong>{{ $covExt->code }}</strong>@if($hostedExt && $hostedExt->id === $covExt->id) <em>(Center)</em>@endif{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            @endif
                        </td>
                        <td>{{ $room->remarks ?: '—' }}</td>
                    </tr>
                    @if((($index + 1) % max(1, (int)$printRowLimit)) === 0 && !$loop->last)
                        <tr class="print-page-break-row"><td colspan="10"></td></tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="10" class="center-text" style="padding: 20px;">No rooms recorded for this school.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="signatures">
            <div class="sig-col">
                <p>Prepared by:</p>
                <br><br>
                <p>_______________________</p>
                <p>School DRRM Coordinator</p>
            </div>
            <div class="sig-col">
                <p>Approved by:</p>
                <br><br>
                <p>_______________________</p>
                <p>School Head / Principal</p>
            </div>
        </div>
    </div>

    {{-- SECTION 4: EVACUATION PLANS --}}
    <div class="page">
        <div class="map-header">
            <div class="map-header-left">
                <div class="map-logos">
                    <img src="{{ asset('images/Layer-0-1.png') }}" alt="Logo 1">
                    <img src="{{ asset('images/What-Is-the-Difference-Between-DepEd-Seal-and-DepEd-Logo.png') }}" alt="Logo 2">
                    <img src="{{ asset('images/drrmis-logo-2.png') }}" alt="Logo 3">
                    <div style="font-size: 11px; font-weight: bold; text-transform: uppercase; margin-left: 3px;">DepEd DRRM</div>
                </div>
            </div>
            <div class="map-header-center">
                <h3 style="margin: 0; font-size: 13px; font-weight: bold; text-transform: uppercase; line-height: 1.2;">Evacuation Plans Report</h3>
            </div>
            <div class="map-header-right"></div>
        </div>

        <div class="info-grid" style="background: #fafafa;">
            <div class="info-row">
                <div><strong>Name of School:</strong> {{ $school->school_name }}</div>
                <div><strong>Name of School Head:</strong> {{ $school->school_head }}</div>
            </div>
            <div class="info-row">
                <div><strong>School ID:</strong> {{ $school->school_id }}</div>
                <div><strong>DRRM Coordinator:</strong> {{ $school->school_drrm_coordinator }}</div>
            </div>
        </div>

        <div class="section-title" style="font-size: 13px; border-bottom: 2px solid #333; text-transform: none;">School-Wide Evacuation Plan (Entire School)</div>
        @if($schoolPlan ?? null)
            <table class="school-plan-table">
                <tr><th>Plan Name</th><td>{{ $schoolPlan->plan_no }}</td></tr>
                <tr><th>Status</th><td>{{ strtoupper($schoolPlan->status) }}</td></tr>
                <tr><th>Number of Assembly Areas</th><td>{{ $schoolPlan->areas ?? 'N/A' }}</td></tr>
                <tr><th>Primary Assembly Area</th><td>{{ $schoolPlan->primary_assembly_area ?: 'Not Specified' }}</td></tr>
                <tr><th>Secondary Assembly Area</th><td>{{ $schoolPlan->secondary_assembly_area ?: 'Not Specified' }}</td></tr>
                <tr><th>Assembly Area Capacity</th><td>{{ $schoolPlan->assembly_capacity ? $schoolPlan->assembly_capacity . ' Persons' : 'N/A' }}</td></tr>
                <tr><th>Special Instructions</th><td>{{ $schoolPlan->special_instructions ?: 'None' }}</td></tr>
                <tr><th>Emergency Contacts</th><td>{{ $schoolPlan->emergency_contacts ?: 'None' }}</td></tr>
            </table>
        @else
            <div class="no-data-box">No school-wide evacuation plan has been created yet.</div>
        @endif

        <div class="section-title" style="font-size: 13px; border-bottom: 2px solid #333; text-transform: none;">Individual Building Evacuation Plans</div>
        @if(($buildingPlans ?? collect())->isEmpty())
            <div class="no-data-box">No individual building evacuation plans have been created yet.</div>
        @else
            @foreach($buildingPlans as $plan)
                <div class="plan-card">
                    <div class="plan-header">
                        {{ $plan->building ? $plan->building->building_no . ($plan->building->building_name ? ' - ' . $plan->building->building_name : '') : 'Unknown Building' }}
                    </div>
                    <div style="display: flex; gap: 20px;">
                        <div style="flex: 1;">
                            <div style="margin-bottom: 8px;"><strong style="display: inline-block; min-width: 180px; color: #555;">Plan Name:</strong> <span>{{ $plan->plan_no }}</span></div>
                            <div style="margin-bottom: 8px;"><strong style="display: inline-block; min-width: 180px; color: #555;">Status:</strong> <span>{{ strtoupper($plan->status) }}</span></div>
                            <div style="margin-bottom: 8px;"><strong style="display: inline-block; min-width: 180px; color: #555;">Number of Routes:</strong> <span>{{ $plan->routes ?? 'N/A' }}</span></div>
                            <div style="margin-bottom: 8px;"><strong style="display: inline-block; min-width: 180px; color: #555;">Safety Features Installed:</strong> <span>{{ $plan->safety_features_installed ?: 'Not Specified' }}</span></div>
                        </div>
                        <div style="flex: 1;">
                            <div style="margin-bottom: 8px;"><strong style="display: inline-block; min-width: 180px; color: #555;">Primary Evacuation Route:</strong> <span>{{ $plan->primary_route ?: 'Not Specified' }}</span></div>
                            <div style="margin-bottom: 8px;"><strong style="display: inline-block; min-width: 180px; color: #555;">Secondary Evacuation Route:</strong> <span>{{ $plan->secondary_route ?: 'Not Specified' }}</span></div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        <div class="purpose">
            <p><strong>Overall Purpose:</strong> This document serves as the official compilation of evacuation plans and assigned assembly parameters, providing critical action directives during emergencies.</p>
        </div>

        <div class="signatures">
            <div class="sig-col">
                <p>Prepared by:</p>
                <br><br>
                <p>_______________________</p>
                <p>School DRRM Coordinator</p>
            </div>
            <div class="sig-col">
                <p>Noted by:</p>
                <br><br>
                <p>_______________________</p>
                <p>School Head / Principal</p>
            </div>
        </div>
    </div>

    {{-- SECTION 5: EVACUATION MAP --}}
    <div class="page" style="page-break-after: auto;">
        @php
            $layoutItems = is_array($mapLayout ?? null) ? $mapLayout : [];
        @endphp
        @if(!empty($layoutItems))
            <iframe
                id="full-report-map-frame"
                src="{{ route('fire-safety.schools.evacuation-plans', $school->id) }}?print_map=1"
                title="Evacuation Map"
                style="width: 100%; height: 980px; border: 0;"
            ></iframe>
        @elseif(!empty($school->attached_evacuation_map))
            <div class="attached-map-frame">
                <img src="{{ asset('storage/' . ltrim($school->attached_evacuation_map, '/')) }}" alt="Attached evacuation map">
            </div>
            <div class="map-source-label">Attached Image</div>
        @else
            <div class="no-data-box">No generated evacuation map layout found for this school.</div>
        @endif
    </div>

    <script>
        (function () {
            let hasPrinted = false;
            const printNow = function () {
                if (hasPrinted) return;
                hasPrinted = true;
                window.print();
            };

            const mapFrame = document.getElementById('full-report-map-frame');
            if (mapFrame) {
                mapFrame.addEventListener('load', function () {
                    setTimeout(printNow, 1200);
                }, { once: true });

                // Fallback print in case iframe load event is delayed.
                setTimeout(printNow, 5000);
            } else {
                setTimeout(printNow, 300);
            }
        })();
    </script>
</body>
</html>
