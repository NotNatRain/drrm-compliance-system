<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School's Summarization of Fire Safety - Buildings</title>
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
        .header-container {
            margin-bottom: 20px;
        }
        .header-title {
            text-align: center;
            margin-bottom: 15px;
        }
        .header-title h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .info-item {
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid black;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
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
        @media print {
            .no-print {
                display: none;
            }
            body {
                -webkit-print-color-adjust: exact;
                margin: 1cm;
            }
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
    </style>
</head>
<body>
    <div class="no-print" style="background: #f8f9fa; border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">
        <button class="btn-print" onclick="window.print()">Print Report</button>
        <button class="btn-print" style="background: #6c757d;" onclick="window.close()">Close</button>
        <p class="small text-muted mb-0 mt-2">For a clean print without URL, date, or page numbers, turn off &quot;Headers and footers&quot; in the print dialog.</p>
    </div>

    <div class="header-container">
        <div class="header-title">
            <h1>School's Summarization of Fire Safety – Buildings</h1>
        </div>
        
        <div class="info-grid">
            <div class="info-item"><strong>Name of School:</strong> {{ $school->school_name }}</div>
            <div class="info-item"><strong>Name of School Head:</strong> {{ $school->school_head }}</div>
            <div class="info-item"><strong>School ID:</strong> {{ $school->school_id }}</div>
            <div class="info-item"><strong>Name of School DRRM Coordinator:</strong> {{ $school->school_drrm_coordinator }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">BUILDING NUMBER</th>
                <th style="width: 20%;">BUILDING NAME</th>
                <th class="vertical-th"><div>NUMBER OF FLOOR</div></th>
                <th class="vertical-th"><div>IS THE BUILDING<br> WITH SECONDARY<br> EXIT FOR (2-4 STOREY<br> BUILDING)</div></th>
                <th class="vertical-th"><div>NUMBER OF CLASSROOMS</div></th>
                <th class="vertical-th"><div>NUMBER OF <br>ROOMS WITHOUT <br>SECONDARY EXIT</div></th>
                <th class="vertical-th"><div>NUMBER OF LABORATORIES</div></th>
                <th class="vertical-th"><div>NUMBER OF ADMINISTRATIVE<br> OFFICE</div></th>
                <th class="vertical-th"><div>NUMBER OF REQUIRED<br> FIRE EXTINGUISHER</div></th>
                <th class="vertical-th"><div>NUMBER OF ACTIVE FIRE EXTINGUISHER</div></th>
                <th class="vertical-th"><div>ALARMS</div></th>
                <th style="width: 15%;">REMARKS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($school->buildings as $building)
                @php
                    $rooms = $building->actualRooms;
                    
                    // Count classrooms - looking for room_type = 'classroom' or room types with 'classroom' in name
                    $classrooms = $rooms->filter(function($room) {
                        return strtolower($room->room_type) === 'classroom' || 
                               (isset($room->roomTypeConfig->name) && stripos($room->roomTypeConfig->name, 'classroom') !== false);
                    })->count();
                    
                    // Count laboratories - looking for room_type = 'laboratory' or room types with 'lab' in name
                    $laboratories = $rooms->filter(function($room) {
                        return strtolower($room->room_type) === 'laboratory' || 
                               (isset($room->roomTypeConfig->name) && stripos($room->roomTypeConfig->name, 'lab') !== false);
                    })->count();
                    
                    // Count administrative offices - looking for room_type = 'office' or 'department' or room types with 'office' in name
                    $offices = $rooms->filter(function($room) {
                        return in_array(strtolower($room->room_type), ['office', 'department', 'administrative']) || 
                               (isset($room->roomTypeConfig->name) && (stripos($room->roomTypeConfig->name, 'office') !== false || stripos($room->roomTypeConfig->name, 'admin') !== false));
                    })->count();
                    
                    // Count rooms without secondary exit (rooms on floors >= 2 in buildings without adequate emergency exits)
                    $roomsWithoutSecondaryExit = 0;
                    if ($building->floors >= 2 && $building->emergency_exits < 2) {
                        // Count rooms on floor 2 and above
                        $roomsWithoutSecondaryExit = $rooms->filter(function($room) {
                            return ($room->floor_no ?? 1) >= 2;
                        })->count();
                    }
                    
                    // Get required fire extinguishers from building's required_extinguishers field or calculate
                    $requiredExtinguishers = $building->required_extinguishers ?? $building->requiredExtinguishersCount;
                    
                    // Count active fire extinguishers (status = 'active' or 'OK' or 'operational')
                    $activeExtinguishers = $building->fireExtinguishers->filter(function($ext) {
                        $status = strtolower($ext->status ?? '');
                        return in_array($status, ['active', 'ok', 'operational', 'okay']);
                    })->count();
                    
                    // Count total alarms for this building (using alarmSystemsMany relationship for many-to-many)
                    $totalAlarms = $building->alarmSystemsMany->count();
                    
                    // Secondary Exit check: Building has 2-4 floors AND has at least 2 emergency exits
                    $hasSecondaryExit = ($building->floors >= 2 && $building->floors <= 4 && $building->emergency_exits >= 2) ? 'Yes' : 'No';
                @endphp
                <tr>
                    <td class="center-text">{{ $building->building_no }}</td>
                    <td class="center-text">{{ $building->building_name }}</td>
                    <td class="center-text">{{ $building->floors }}</td>
                    <td class="center-text">{{ $hasSecondaryExit }}</td>
                    <td class="center-text">{{ $classrooms }}</td>
                    <td class="center-text">{{ $roomsWithoutSecondaryExit }}</td>
                    <td class="center-text">{{ $laboratories }}</td>
                    <td class="center-text">{{ $offices }}</td>
                    <td class="center-text">{{ $requiredExtinguishers }}</td>
                    <td class="center-text">{{ $activeExtinguishers }}</td>
                    <td class="center-text">{{ $totalAlarms }}</td>
                    <td class="center-text">{{ $building->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 40px; display: flex; justify-content: space-between; padding: 0 50px;">
        <div style="text-align: center;">
            <p>Prepared by:</p>
            <br><br>
            <p><strong>{{ $school->school_drrm_coordinator }}</strong></p>
            <p>School DRRM Coordinator</p>
        </div>
        <div style="text-align: center;">
            <p>Certified Correct:</p>
            <br><br>
            <p><strong>{{ $school->school_head }}</strong></p>
            <p>School Head / Principal</p>
        </div>
    </div>
</body>
</html>
