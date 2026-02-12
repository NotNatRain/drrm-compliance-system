<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Tool - {{ $inspection->drill_type }} Drill</title>
    <style>
        body { font-family: 'Arial', sans-serif; line-height: 1.4; color: #333; margin: 0; padding: 20px; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h3 { margin: 5px 0; text-transform: uppercase; }
        .header p { margin: 2px 0; }
        
        .annex { text-align: right; font-style: italic; margin-bottom: 5px; }
        
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 5px; vertical-align: top; }
        .underline { border-bottom: 1px solid #000; display: inline-block; min-width: 150px; padding: 0 10px; }
        
        .monitoring-tool-title { text-align: center; font-weight: bold; font-size: 14px; margin: 20px 0; border: 2px solid #000; padding: 10px; }
        
        .data-grid { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-grid td { border: 1px solid #000; padding: 8px; }
        .label { font-weight: bold; background-color: #f2f2f2; width: 30%; }
        
        .results-section { margin-top: 20px; }
        .results-section h4 { border-bottom: 1px solid #000; padding-bottom: 5px; margin-bottom: 10px; }
        
        .checklist-table { width: 100%; border-collapse: collapse; }
        .checklist-table td { border: 1px solid #000; padding: 5px; }
        .check-box { width: 20px; height: 20px; border: 1px solid #000; display: inline-block; vertical-align: middle; text-align: center; font-weight: bold; line-height: 20px; }
        
        .signatures { width: 100%; margin-top: 50px; }
        .signatures td { text-align: center; width: 33%; padding-top: 50px; }
        .sig-line { border-top: 1px solid #000; margin: 0 10px; padding-top: 5px; }

        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2c3e50; color: white; border: none; cursor: pointer; border-radius: 4px;">Print Document</button>
    </div>

    <div class="annex">Annex B of DepEd Order No. 48, s. 2012</div>
    
    <div class="header">
        <p>Republic of the Philippines</p>
        <p>Department of Education</p>
        <h3>MONITORING TOOL FOR EARTHQUAKE AND FIRE DRILLS IN SCHOOLS</h3>
    </div>

    <table class="info-table">
        <tr>
            <td>Region: <span class="underline">{{ $inspection->school->region ?? 'N/A' }}</span></td>
            <td>School ID: <span class="underline">{{ $inspection->school->school_id }}</span></td>
        </tr>
        <tr>
            <td>District: <span class="underline">{{ $inspection->school->district ?? 'N/A' }}</span></td>
            <td>Division: <span class="underline">{{ $inspection->school->division ?? 'N/A' }}</span></td>
        </tr>
        <tr>
            <td colspan="2">School Name: <span class="underline" style="width: 80%;">{{ $inspection->school->school_name }}</span></td>
        </tr>
    </table>

    <table class="data-grid">
        <tr>
            <td class="label">Drill Type</td>
            <td>{{ $inspection->drill_type }}</td>
            <td class="label">Date</td>
            <td>{{ date('F d, Y', strtotime($inspection->inspection_date)) }}</td>
        </tr>
        <tr>
            <td class="label">Time Started</td>
            <td>{{ $inspection->time_started ?? 'N/A' }}</td>
            <td class="label">Time Finished</td>
            <td>{{ $inspection->time_finished ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Elapsed Time</td>
            <td>{{ $inspection->elapsed_time ?? 'N/A' }}</td>
            <td class="label">No. of Exits</td>
            <td>{{ $inspection->no_of_exits ?? 0 }}</td>
        </tr>
        <tr>
            <td class="label">No. of Buildings</td>
            <td>{{ $inspection->no_of_buildings ?? 0 }}</td>
            <td class="label">No. of Students</td>
            <td>{{ number_format($inspection->no_of_students ?? 0) }}</td>
        </tr>
        <tr>
            <td class="label">No. of Personnel</td>
            <td colspan="3">{{ number_format($inspection->no_of_personnel ?? 0) }}</td>
        </tr>
    </table>

    <div class="results-section">
        <h4>CHECKLIST ITEMS</h4>
        <table class="checklist-table">
            @php 
                $checklistData = $inspection->checklist_data ?? []; 
                // We show all configured checklists if possible, or just the ones checked
            @endphp
            @foreach($checklistData as $item)
            <tr>
                <td style="width: 30px; text-align: center;"><span class="check-box">/</span></td>
                <td>{{ $item }}</td>
            </tr>
            @endforeach
            @if(empty($checklistData))
            <tr>
                <td colspan="2" style="text-align: center; color: #888;">No checklist items recorded.</td>
            </tr>
            @endif
        </table>
    </div>

    @if($inspection->remarks)
    <div class="results-section">
        <h4>REMARKS / OBSERVATIONS</h4>
        <div style="border: 1px solid #000; padding: 10px; min-height: 80px;">
            {{ $inspection->remarks }}
        </div>
    </div>
    @endif

    @if(!empty($inspection->observers_data))
    <div class="results-section">
        <h4>OTHER OBSERVERS PRESENT</h4>
        <table class="checklist-table">
            @foreach($inspection->observers_data as $obs)
            <tr>
                <td style="width: 30px; text-align: center;"><span class="check-box">/</span></td>
                <td>{{ $obs }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    <table class="signatures">
        <tr>
            <td>
                <div class="sig-line"><strong>{{ $inspection->monitored_by }}</strong></div>
                <div>Monitored By / Representative Name</div>
            </td>
            <td>
                <div class="sig-line"><strong>{{ $inspection->coordinator_name }}</strong></div>
                <div>School DRRM Coordinator</div>
            </td>
            <td>
                <div class="sig-line"><strong>{{ $inspection->school_head_name }}</strong></div>
                <div>School Head / Principal</div>
            </td>
        </tr>
    </table>

    <div style="margin-top: 30px; font-size: 10px; color: #666; text-align: center;">
        Generated by DRRM Compliance System on {{ date('Y-m-d H:i:s') }}
    </div>
</body>
</html>
