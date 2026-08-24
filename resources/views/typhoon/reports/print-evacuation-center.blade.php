<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evacuation Center Report - {{ $ec->school_name ?? $ec->identification }}</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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

        .signatures {
            margin-top: 35px;
            display: flex;
            justify-content: space-between;
            padding: 0 50px;
        }

        .sig-col {
            text-align: center;
        }

        @media print {

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print()" onafterprint="window.history.back()">

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
                <h1 style="margin: 0; font-size: 14px; font-weight: normal; text-transform: uppercase;">Evacuation Center Intelligence Report</h1>
            </div>
        </div>

        <div class="info-grid" style="display: grid; grid-template-columns: 1fr 1fr;">
            <div><strong>Name of Evacuation Center:</strong> {{ $ec->school_name ?? $ec->identification }}</div>
            <div><strong>Date & Time:</strong> {{ now()->format('F d, Y | h:i A') }}</div>
            <div><strong>Location / Address:</strong> {{ $ec->location ?? $ec->school->address ?? 'N/A' }}</div>
            <div><strong>Operational Status:</strong> <span style="text-transform: uppercase;">{{ $ec->usage_status ?? 'CLEARED' }}</span></div>
            <div><strong>Max Capacity:</strong> {{ $ec->capacity ?? 0 }} Individuals</div>
            <div><strong>Current Load:</strong> {{ $currentOccupancy }} Individuals</div>
            <div style="grid-column: span 2; margin-top: 5px;"><strong>Latest Situation Narrative:</strong> {{ $ec->reports_status ?: 'No situation report provided.' }}</div>
            <div style="grid-column: span 2; margin-top: 5px;"><strong>Resource Shortages / Needs:</strong> {{ $ec->emergency_resources ?: 'No resource shortages reported.' }}</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th colspan="5" style="font-size: 12px; background: #ddd; text-align: center;">MASTER EVACUATION REGISTRY</th>
                </tr>
                <tr>
                    <th>Head of Family</th>
                    <th style="text-align: center;">Members</th>
                    <th>Vulnerabilities</th>
                    <th>Entry Date</th>
                    <th>Needs</th>
                </tr>
            </thead>
            <tbody>
                @forelse($families as $family)
                <tr>
                    <td>{{ $family->head_family_name }}</td>
                    <td style="text-align: center;">{{ $family->members_count }}</td>
                    <td>
                        @php 
                            $v = []; 
                            if($family->has_pregnant) $v[]='Pregnant'; 
                            if($family->has_pwd) $v[]='PWD'; 
                            if($family->has_senior) $v[]='Senior'; 
                            if($family->has_lactating) $v[]='Lactating';
                            if($family->has_child_under5) $v[]='Child < 5'; 
                        @endphp
                        {{ implode(', ', $v) ?: 'None' }}
                    </td>
                    <td>{{ $family->created_at->format('M d, Y') }}</td>
                    <td>{{ $family->needs_summary ?: 'None' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #555; padding: 20px;">No families currently registered in this center.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="signatures">
            <div class="sig-col">
                <p>Prepared by:</p>
                <br><br>
                <p>_____________________________________</p>
                <p>DRRM Coordinator / Center Manager</p>
            </div>
            <div class="sig-col">
                <p>Certified Correct:</p>
                <br><br>
                <p>_____________________________________</p>
                <p>School Head / Principal</p>
            </div>
        </div>
    </div>
</body>
</html>
