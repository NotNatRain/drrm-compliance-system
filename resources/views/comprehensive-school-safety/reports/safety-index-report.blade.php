<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safety Index Report</title>
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
        th, td { border: 1px solid #000; padding: 6px; text-align: left; vertical-align: top; }
        th { background: #f2f2f2; text-transform: uppercase; font-size: 10px; }
        .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-top: 10px; margin-bottom: 10px; }
        .card { border: 1px solid #000; padding: 8px; }
        .card h4 { margin: 0 0 4px; font-size: 18px; }
        .card p { margin: 0; font-size: 10px; text-transform: uppercase; }
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
            <div class="title-center"><h1 style="margin:0; font-size:14px; font-weight:normal; text-transform:uppercase;">Safety Index Report</h1></div>
        </div>

        <table>
            <tr><th style="width:25%;">School</th><td>{{ $school->school_name }}</td><th style="width:25%;">School ID</th><td>{{ $school->school_id }}</td></tr>
            <tr><th>Division</th><td>{{ $school->division ?? 'N/A' }}</td><th>Region</th><td>{{ $school->region ?? 'N/A' }}</td></tr>
        </table>

        <div class="grid">
            <div class="card"><h4>{{ $summaryFindings->count() }}</h4><p>Total Summary Findings</p></div>
            <div class="card"><h4>{{ $indexStats['high_findings'] }}</h4><p>High Priority Findings</p></div>
            <div class="card"><h4>{{ $indexStats['medium_findings'] }}</h4><p>Medium Priority Findings</p></div>
            <div class="card"><h4>{{ $indexStats['low_findings'] }}</h4><p>Low Priority Findings</p></div>
            <div class="card"><h4>{{ $buildings->sum('floors') }}</h4><p>Total Floors</p></div>
            <div class="card"><h4>{{ $buildings->sum('rooms') }}</h4><p>Total Rooms</p></div>
        </div>

        @php
            $buildingsById = $buildings->keyBy('id');
        @endphp

        <table>
            <thead>
                <tr>
                    <th style="width:14%;">Observation Date</th>
                    <th style="width:14%;">Building</th>
                    <th style="width:7%;">Floors</th>
                    <th style="width:7%;">Rooms</th>
                    <th style="width:14%;">Concern Category</th>
                    <th style="width:12%;">Concern Type</th>
                    <th style="width:8%;">Priority</th>
                    <th>Description</th>
                    <th style="width:16%;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($summaryFindings as $finding)
                    @php
                        $building = $buildingsById->get($finding->building_id);
                    @endphp
                    <tr>
                        <td>{{ $finding->observation_date ? \Carbon\Carbon::parse($finding->observation_date)->format('M d, Y') : 'N/A' }}</td>
                        <td>{{ $building?->building_name ?? ($building ? ('Building ' . $building->building_no) : 'N/A') }}</td>
                        <td>{{ $building->floors ?? 0 }}</td>
                        <td>{{ $building->rooms ?? 0 }}</td>
                        <td>{{ $finding->concern_category }}</td>
                        <td>{{ $finding->concern_type }}</td>
                        <td>{{ strtoupper((string) $finding->priority) }}</td>
                        <td>{{ $finding->description }}</td>
                        <td>{{ $finding->remarks ?: ($building->compliance_reason ?? ($building->description ?? '-')) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="9" style="text-align:center; padding:20px;">No summary findings recorded.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
