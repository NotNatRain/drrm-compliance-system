<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School's Summarization of Fire Safety - Buildings</title>
    <style>
        @page {
            size: landscape;
            margin: 1cm;
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
    </div>

    <div class="header-container">
        <div class="header-title">
            <h1>School's Summarization of Fire Safety – Buildings</h1>
        </div>
        
        <div class="info-grid">
            <div class="info-item"><strong>Name of School:</strong> {{ $school->school_name }}</div>
            <div class="info-item"><strong>Name of School Head:</strong> {{ $school->school_head }}</div>
            <div class="info-item"><strong>School ID:</strong> {{ $school->school_id }}</div>
            <div class="info-item"><strong>Name of School DRRM Coordinator:</strong> {{ $school->drrm_coordinator }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Building Number</th>
                <th style="width: 25%;">Building Name</th>
                <th class="vertical-th"><div>Secondary Exit (2–4 storey building)</div></th>
                <th class="vertical-th"><div>Number of Fire Extinguishers</div></th>
                <th class="vertical-th"><div>Number of Floors</div></th>
                <th class="vertical-th"><div>Number of Classrooms</div></th>
                <th class="vertical-th"><div>Number of Laboratories</div></th>
                <th class="vertical-th"><div>Number of Administrative Office</div></th>
                <th class="vertical-th"><div>Alarm Coverage</div></th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($school->buildings as $building)
                @php
                    $rooms = $building->actualRooms;
                    $classrooms = $rooms->where('room_type', 'classroom')->count();
                    $laboratories = $rooms->where('room_type', 'laboratory')->count();
                    $offices = $rooms->whereIn('room_type', ['office', 'department'])->count();
                    $extinguishers = $building->fireExtinguishers->count();
                    $alarms = $building->alarmSystems->count();
                    
                    // Secondary Exit check: Floors >= 2 and emergency_exits >= 2 (as specified in logic previously)
                    $secondaryExit = ($building->floors >= 2 && $building->emergency_exits >= 2) ? 'Yes' : 'No';
                @endphp
                <tr>
                    <td class="center-text">{{ $building->building_no }}</td>
                    <td>{{ $building->building_name }}</td>
                    <td class="center-text">{{ $secondaryExit }}</td>
                    <td class="center-text">{{ $extinguishers }}</td>
                    <td class="center-text">{{ $building->floors }}</td>
                    <td class="center-text">{{ $classrooms }}</td>
                    <td class="center-text">{{ $laboratories }}</td>
                    <td class="center-text">{{ $offices }}</td>
                    <td class="center-text">{{ $alarms > 0 ? 'Covered' : 'Not Covered' }}</td>
                    <td>{{ $building->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 40px; display: flex; justify-content: space-between; padding: 0 50px;">
        <div style="text-align: center;">
            <p>Prepared by:</p>
            <br><br>
            <p><strong>{{ $school->drrm_coordinator }}</strong></p>
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
