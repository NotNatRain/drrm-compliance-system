<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archives Report</title>
    <style>
        @page { size: portrait; margin: 0; }
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 0; padding: 0; }
        .no-print { background: #f8f9fa; border-bottom: 1px solid #ddd; padding: 10px; text-align: center; }
        .btn-print { background: #A8191F; color: #fff; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; margin: 5px; font-weight: bold; }
        .page { padding: 1cm; }
        .header-container { position: relative; height: 80px; display: flex; align-items: center; margin-bottom: 10px; }
        .logo-left { position: absolute; left: 0; top: 0; display: flex; align-items: center; }
        .logo-left img { height: 60px; margin-right: 10px; }
        .title-center { width: 100%; text-align: center; padding-left: 430px; padding-right: 30px; box-sizing: border-box; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #000; padding: 7px; text-align: left; vertical-align: top; }
        th { background: #f2f2f2; text-transform: uppercase; font-size: 10px; }
        @media print { .no-print { display:none; } body { -webkit-print-color-adjust: exact; margin: 0; } }
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
                <div style="text-align:left;"><h2 style="margin:0; font-size:16px; font-weight:bold; text-transform:uppercase;">DepEd DRRM</h2></div>
            </div>
            <div class="title-center"><h1 style="margin:0; font-size:14px; font-weight:normal; text-transform:uppercase;">School Safety Archives Report</h1></div>
        </div>

        <table>
            <tr><th style="width:25%;">School</th><td>{{ $school->school_name }}</td><th style="width:25%;">School ID</th><td>{{ $school->school_id }}</td></tr>
            <tr><th>Division</th><td>{{ $school->division ?? 'N/A' }}</td><th>Region</th><td>{{ $school->region ?? 'N/A' }}</td></tr>
        </table>

        <table>
            <thead>
                <tr>
                    <th style="width:15%;">Date</th>
                    <th style="width:18%;">Event Type</th>
                    <th style="width:27%;">Title</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse($timelineEvents as $event)
                    <tr>
                        <td>{{ $event['event_date'] ? \Carbon\Carbon::parse($event['event_date'])->format('M d, Y') : 'N/A' }}</td>
                        <td>{{ $event['event_type'] }}</td>
                        <td>{{ $event['title'] }}</td>
                        <td>{{ $event['details'] }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align:center; padding:20px;">No archive entries found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
