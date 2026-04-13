<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprehensive Assessment Report</title>
    <style>
        @page { size: landscape; margin: 0; }
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 0; padding: 0; }
        .no-print { background: #f8f9fa; border-bottom: 1px solid #ddd; padding: 10px; text-align: center; }
        .btn-print { background: #A8191F; color: #fff; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; margin: 5px; font-weight: bold; }
        .page { padding: 1cm; }
        .header-container { position: relative; height: 80px; display: flex; align-items: center; margin-bottom: 10px; }
        .logo-left { position: absolute; left: 0; top: 0; display: flex; align-items: center; }
        .logo-left img { height: 60px; margin-right: 10px; }
        .title-center { width: 100%; text-align: center; padding-left: 430px; padding-right: 30px; box-sizing: border-box; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; vertical-align: top; }
        th { background: #f2f2f2; text-transform: uppercase; font-size: 10px; }
        .small { font-size: 10px; }
        .check-cell { white-space: nowrap; }
        .check-box { display: inline-block; width: 12px; height: 12px; border: 1px solid #000; margin-right: 4px; vertical-align: middle; }
        .center { text-align: center; }
        @media print { .no-print { display: none; } body { -webkit-print-color-adjust: exact; margin: 0; } }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">Print Report</button>
        <button class="btn-print" style="background:#6c757d;" onclick="window.close()">Close</button>
    </div>

    <div class="page">
        <div class="header-container">
            <div class="logo-left">
                <img src="{{ asset('images/Layer-0-1.png') }}" alt="Logo 1">
                <img src="{{ asset('images/What-Is-the-Difference-Between-DepEd-Seal-and-DepEd-Logo.png') }}" alt="Logo 2">
                <img src="{{ asset('images/drrmis-logo-2.png') }}" alt="Logo 3">
                <div style="text-align:left;">
                    <h2 style="margin:0; font-size:16px; font-weight:bold; text-transform:uppercase;">DepEd DRRM</h2>
                </div>
            </div>
            <div class="title-center">
                <h1 style="margin:0; font-size:14px; font-weight:normal; text-transform:uppercase;">Comprehensive School Safety Assessment Report</h1>
            </div>
        </div>

        <table class="small" style="margin-bottom: 12px;">
            <tr>
                <th style="width:22%;">School Category, Level & Classifications</th>
                <td style="width:28%;">
                    <div class="check-cell"><span class="check-box"></span>Public School</div>
                    <div class="check-cell"><span class="check-box"></span>Urban</div>
                    <div class="check-cell"><span class="check-box"></span>Private School</div>
                    <div class="check-cell"><span class="check-box"></span>Rural</div>
                </td>
                <th style="width:20%;">Name of School</th>
                <td style="width:30%;">{{ $school->school_name }}</td>
            </tr>
            <tr>
                <th>School ID</th>
                <td>{{ $school->school_id }}</td>
                <th>Division</th>
                <td>{{ $school->division ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Region</th>
                <td>{{ $school->region ?? 'N/A' }}</td>
                <th>School Address</th>
                <td>{{ $school->address ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Legislative District</th>
                <td>{{ $school->district ?? 'N/A' }}</td>
                <th>Province</th>
                <td>N/A</td>
            </tr>
            <tr>
                <th>School District</th>
                <td>{{ $school->district ?? 'N/A' }}</td>
                <th>Date Visited</th>
                <td>{{ $assessment->date_visited ? \Carbon\Carbon::parse($assessment->date_visited)->format('M d, Y') : 'N/A' }}</td>
            </tr>
        </table>

        <table>
            <thead>
                <tr>
                    <th style="width:18%;">Criteria Group</th>
                    <th>Criteria / Questionnaire</th>
                    <th style="width:8%;" class="center">Compliant</th>
                    <th style="width:7%;" class="center">Points</th>
                    <th style="width:20%;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($criteriaRows as $row)
                    <tr>
                        <td>{{ $row['category'] }}</td>
                        <td>{{ $row['criteria'] }}</td>
                        <td class="center">
                            @if($row['is_compliant'] === true)
                                Yes
                            @elseif($row['is_compliant'] === false)
                                No
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="center">{{ $row['points'] }}</td>
                        <td>{{ $row['remarks'] ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="center" style="padding: 20px;">No questionnaire rows found for this assessment.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
