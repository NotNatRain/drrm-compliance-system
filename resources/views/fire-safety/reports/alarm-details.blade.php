<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fire Alarm System Inspection tracking</title>
    <style>
        @page {
            size: portrait;
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
            font-size: 16px;
            text-transform: uppercase;
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
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            text-transform: uppercase;
            font-size: 10px;
        }
        .status-cell {
            text-align: center;
            font-weight: bold;
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
        .purpose {
            margin-top: 20px;
            font-style: italic;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="no-print" style="background: #f8f9fa; border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">
        <button class="btn-print" onclick="window.print()">Print Report</button>
        <button class="btn-print" style="background: #6c757d;" onclick="window.close()">Close</button>
    </div>

    <div class="header-container" style="position: relative; height: 80px; display: flex; align-items: center; margin-bottom: 10px;">
        <div style="position: absolute; left: 0; top: 0; display: flex; align-items: center;">
            <img src="{{ asset('images/Layer-0-1.png') }}" alt="Logo 1" style="height: 60px; margin-right: 10px;">
            <img src="{{ asset('images/What-Is-the-Difference-Between-DepEd-Seal-and-DepEd-Logo.png') }}" alt="Logo 2" style="height: 60px; margin-right: 10px;">
            <img src="{{ asset('images/drrmis-logo-2.png') }}" alt="Logo 3" style="height: 60px; margin-right: 15px;">
            <div style="text-align: left;">
                <h2 style="margin: 0; font-size: 16px; font-weight: bold; text-transform: uppercase;">DepEd DRRM</h2>
            </div>
        </div>

        <div style="width: 100%; text-align: center; padding-left: 430px; padding-right: 30px; box-sizing: border-box;">
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
                            $others = $alarm->buildings->filter(fn ($b) => (int) $b->id !== (int) $alarm->building_id);
                            $otherLabels = $others->map(function ($b) {
                                return trim($b->building_no . ($b->building_name ? ' (' . $b->building_name . ')' : ''));
                            })->filter()->values();
                        @endphp
                        {{ $primaryLabel }}@if($otherLabels->isNotEmpty()) ({{ $otherLabels->implode(', ') }})@endif
                    </td>
                    <td>{{ $alarm->alarm_type }}</td>
                    <td class="status-cell">{{ strtoupper(str_ireplace('broken', 'defective', $alarm->status)) }}</td>
                    <td>{{ $alarm->last_test ? \Carbon\Carbon::parse($alarm->last_test)->format('M d, Y') : 'N/A' }}</td>
                    <td>{{ $alarm->next_test_due ? \Carbon\Carbon::parse($alarm->next_test_due)->format('M d, Y') : 'N/A' }}</td>
                    <td>{{ $alarm->notes }}</td>
                </tr>
            @endforeach
            @if($alarms->isEmpty())
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">No alarm systems recorded for this school.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="purpose">
        <p><strong>Overall Purpose:</strong> This sheet is designed for monitoring the alarm's overall coverage of the school, its status, and the type of alarm system installed.</p>
    </div>

    <div style="margin-top: 40px; display: flex; justify-content: space-between; padding: 0 50px;">
        <div style="text-align: center;">
            <p>Prepared by:</p>
            <br><br>
            <p>_______________________</p>
            <p>School DRRM Coordinator</p>
        </div>
        <div style="text-align: center;">
            <p>Noted by:</p>
            <br><br>
            <p>_______________________</p>
            <p>School Head / Principal</p>
        </div>
    </div>
</body>
</html>
