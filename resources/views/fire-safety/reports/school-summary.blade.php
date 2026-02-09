<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School's Summarization of Fire Safety - Schools</title>
    <style>
        @page {
            size: landscape;
            margin: 1cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .vertical-th {
            height: 150px;
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
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="no-print" style="background: #f8f9fa; border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">
        <button class="btn-print" onclick="window.print()">Print Report</button>
        <button class="btn-print" style="background: #6c757d;" onclick="window.close()">Close</button>
    </div>

    <div class="header">
        <h1>School's Summarization of Fire Safety – Schools</h1>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 20%;">School Name</th>
                <th style="width: 10%;">School ID</th>
                <th style="width: 20%;">School Head Name</th>
                <th class="vertical-th"><div>Number of Buildings</div></th>
                <th class="vertical-th"><div>Number of Fire Extinguishers</div></th>
                <th class="vertical-th"><div>Number of Classrooms</div></th>
                <th class="vertical-th"><div>Number of Laboratories</div></th>
                <th class="vertical-th"><div>Number of Administrative Office</div></th>
                <th class="vertical-th"><div>Number of Alarms</div></th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schools as $school)
                @php
                    $buildings = $school->buildings;
                    $rooms = $buildings->flatMap->actualRooms;
                    
                    $classrooms = $rooms->where('room_type', 'classroom')->count();
                    $laboratories = $rooms->where('room_type', 'laboratory')->count();
                    $offices = $rooms->whereIn('room_type', ['office', 'department'])->count();
                    $extinguishers = $buildings->flatMap(fn($b) => $b->fireExtinguishers)->count();
                    $alarms = $buildings->flatMap(fn($b) => $b->alarmSystems)->count();
                @endphp
                <tr>
                    <td>{{ $school->school_name }}</td>
                    <td>{{ $school->school_id }}</td>
                    <td>{{ $school->school_head }}</td>
                    <td class="center-text">{{ $buildings->count() }}</td>
                    <td class="center-text">{{ $extinguishers }}</td>
                    <td class="center-text">{{ $classrooms }}</td>
                    <td class="center-text">{{ $laboratories }}</td>
                    <td class="center-text">{{ $offices }}</td>
                    <td class="center-text">{{ $alarms }}</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; display: flex; justify-content: space-between; padding: 0 50px;">
        <div style="text-align: center;">
            <p style="margin-bottom: 50px;">Prepared by:</p>
            <p><strong>{{ auth()->user()->name }}</strong></p>
            <p>DRRM Coordinator</p>
        </div>
        <div style="text-align: center;">
            <p style="margin-bottom: 50px;">Approved by:</p>
            <p>_______________________</p>
            <p>School Head / Principal</p>
        </div>
    </div>
</body>
</html>
