<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drill Monitoring Report</title>
    <style>
        body { font-family: 'Arial', sans-serif; line-height: 1.4; color: #333; margin: 0; padding: 20px; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h3 { margin: 5px 0; text-transform: uppercase; }
        .header p { margin: 2px 0; }

        .monitoring-tool-title { text-align: center; font-weight: bold; font-size: 14px; margin: 20px 0; border: 2px solid #000; padding: 10px; }

        .data-grid { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-grid td { border: 1px solid #000; padding: 8px; }
        .label { font-weight: bold; background-color: #f2f2f2; width: 20%; }

        .results-section { margin-top: 20px; }
        .results-section h4 { border-bottom: 1px solid #000; padding-bottom: 5px; margin-bottom: 10px; }

        .checklist-table { width: 100%; border-collapse: collapse; }
        .checklist-table td { border: 1px solid #000; padding: 6px; vertical-align: middle; }
        .check-box { width: 18px; height: 18px; border: 1px solid #000; display: inline-block; text-align: center; font-weight: bold; line-height: 18px; margin-right: 5px; }

        .signatures { width: 100%; margin-top: 50px; border-collapse: collapse; }
        .signatures td { text-align: center; width: 33%; padding-top: 50px; }
        .sig-line { border-top: 1px solid #000; margin: 0 10px; padding-top: 5px; }

        @media print {
            @page { margin: 1cm; }
            body { padding: 0; }
            .no-print { display: none; }
        }

        .no-print { margin-bottom: 20px; text-align: center; }
        .print-btn { padding: 10px 20px; background: #2c3e50; color: white; border: none; cursor: pointer; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="print-btn">Print Document</button>
    </div>

    <div class="header-container" style="position: relative; height: 80px; display: flex; align-items: center; margin-bottom: 20px;">
        <!-- Logo and Agency Name (absolute left) -->
        <div style="position: absolute; left: 0; top: 0; display: flex; align-items: center;">
            <img src="{{ asset('images/Layer-0-1.png') }}" alt="Logo 1" style="height: 60px; margin-right: 10px;">
            <img src="{{ asset('images/What-Is-the-Difference-Between-DepEd-Seal-and-DepEd-Logo.png') }}" alt="Logo 2" style="height: 60px; margin-right: 10px;">
            <img src="{{ asset('images/drrmis-logo-2.png') }}" alt="Logo 3" style="height: 60px; margin-right: 15px;">
            <div style="text-align: left;">
                <h2 style="margin: 0; font-size: 16px; font-weight: bold; text-transform: uppercase;">DepEd DRRM</h2>
            </div>
        </div>
        
        <!-- Main Title (centered) -->
        <div style="width: 100%; text-align: center;">
            <h3 style="margin: 0; font-size: 14px; font-weight: bold; text-transform: uppercase;">MONITORING TOOL FOR EARTHQUAKE AND FIRE DRILLS IN SCHOOLS</h3>
        </div>
    </div>

    <!-- 5-Column Table: School Info + Drill Data -->
    <table class="data-grid">
        <tr>
            <td class="label">School Name</td>
            <td colspan="2"><strong>{{ $inspection->school->school_name ?? 'N/A' }}</strong></td>
            <td class="label">School ID</td>
            <td>{{ $inspection->school->school_id ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Drill Type</td>
            <td>{{ $inspection->drill_type }}</td>
            <td class="label">Date</td>
            <td>{{ date('F d, Y', strtotime($inspection->inspection_date)) }}</td>
            <td class="label">Elapsed Time</td>
            <td>{{ $inspection->elapsed_time ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Time Started</td>
            <td>{{ $inspection->time_started ?? 'N/A' }}</td>
            <td class="label">Time Finished</td>
            <td>{{ $inspection->time_finished ?? 'N/A' }}</td>
            <td class="label">No. of Exits</td>
            <td>{{ $inspection->no_of_exits ?? 0 }}</td>
        </tr>
        <tr>
            <td class="label">No. of Buildings</td>
            <td>{{ $inspection->no_of_buildings ?? 0 }}</td>
            <td class="label">No. of Students</td>
            <td>{{ number_format($inspection->no_of_students ?? 0) }}</td>
            <td class="label">No. of Personnel</td>
            <td>{{ number_format($inspection->no_of_personnel ?? 0) }}</td>
        </tr>
    </table>

    <!-- CHECKLIST ITEMS - 3 COLUMNS -->
    <div class="results-section">
        <h4>CHECKLIST ITEMS</h4>
        <table class="checklist-table">
            @php
                $checklistData = $inspection->checklist_data ?? [];
                $chunks = array_chunk($checklistData, 3); // 3 columns
            @endphp

            @forelse($chunks as $chunk)
            <tr>
                @foreach($chunk as $item)
                <td style="width: 33%;">
                    <span class="check-box">
                        @if(is_string($item) || (is_bool($item) && $item) || (isset($item->checked) && $item->checked))
                            ✓
                        @endif
                    </span>
                    {{ is_string($item) ? $item : ($item->label ?? $item) }}
                </td>
                @endforeach

                @if(count($chunk) < 3)
                    @for($i = count($chunk); $i < 3; $i++)
                        <td style="width: 33%;">&nbsp;</td>
                    @endfor
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align: center; color: #888; padding: 15px;">
                    No checklist items recorded.
                </td>
            </tr>
            @endforelse
        </table>
    </div>

    <!-- OTHER OBSERVERS PRESENT - 3 COLUMNS -->
    @if(!empty($inspection->observers_data))
    <div class="results-section">
        <h4>OTHER OBSERVERS PRESENT</h4>
        <table class="checklist-table">
            @php
                $observers = $inspection->observers_data ?? [];
                $obsChunks = array_chunk($observers, 3);
            @endphp

            @foreach($obsChunks as $chunk)
            <tr>
                @foreach($chunk as $observer)
                <td style="width: 33%;">
                    <span class="check-box">✓</span>
                    {{ $observer }}
                </td>
                @endforeach

                @if(count($chunk) < 3)
                    @for($i = count($chunk); $i < 3; $i++)
                        <td style="width: 33%;">&nbsp;</td>
                    @endfor
                @endif
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    <!-- REMARKS / OBSERVATIONS - MOVED BELOW OBSERVERS -->
    @if($inspection->remarks)
    <div class="results-section">
        <h4>REMARKS / OBSERVATIONS</h4>
        <div style="border: 1px solid #000; padding: 10px; min-height: 80px;">
            {{ $inspection->remarks }}
        </div>
    </div>
    @endif

    <!-- SIGNATURES -->
    <table class="signatures">
        <tr>
            <td>
                <div class="sig-line"><strong>{{ $inspection->monitored_by ?? '_____________________' }}</strong></div>
                <div style="margin-top: 5px;">Monitored By / Representative Name</div>
            </td>
            <td>
                <div class="sig-line"><strong>{{ $inspection->coordinator_name ?? '_____________________' }}</strong></div>
                <div style="margin-top: 5px;">School DRRM Coordinator</div>
            </td>
            <td>
                <div class="sig-line"><strong>{{ $inspection->school_head_name ?? '_____________________' }}</strong></div>
                <div style="margin-top: 5px;">School Head / Principal</div>
            </td>
        </tr>
    </table>
</body>
</html>
