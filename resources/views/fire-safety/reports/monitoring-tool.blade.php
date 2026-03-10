<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drill Monitoring Report</title>
    <style>
        body { font-family: 'Arial', sans-serif; line-height: 1.2; color: #333; margin: 0; padding: 10px; font-size: 11px; }
        .header { text-align: center; margin-bottom: 10px; }
        .header h3 { margin: 2px 0; text-transform: uppercase; }
        .header p { margin: 1px 0; }

        .monitoring-tool-title { text-align: center; font-weight: bold; font-size: 12px; margin: 10px 0; border: 1px solid #000; padding: 5px; }

        .data-grid { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .data-grid td { border: 1px solid #000; padding: 4px 6px; }
        .label { font-weight: bold; background-color: #f2f2f2; }

        .results-section { margin-top: 10px; }
        .results-section h4 { padding-bottom: 2px; margin-bottom: 5px; margin-top: 5px; font-size: 12px; }

        .checklist-table { width: 100%; border-collapse: collapse; }
        .checklist-table td { border: 1px solid #000; padding: 3px 5px; vertical-align: middle; }
        .check-box { width: 15px; height: 15px; border: 1px solid #000; display: inline-block; text-align: center; font-weight: bold; line-height: 15px; margin-right: 5px; }

        .signatures { width: 35%; margin-top: 15px; border-collapse: collapse; margin-left: 0; }
        .signatures td { text-align: center; padding-top: 15px; }
        .sig-line { border-bottom: 0.5pt solid #000; margin-bottom: 2px; min-width: 180px; padding-bottom: 1px; display: inline-block; width: 100%; }
        .sig-title { margin-top: 2px; font-weight: bold; }

        @media print {
            @page { 
                size: letter portrait;
                margin: 0; 
            }
            body { 
                padding: 0;
                margin: 1.5cm; 
            }
            .no-print { display: none; }
        }

        .no-print { margin-bottom: 20px; text-align: center; }
        .print-btn { padding: 10px 20px; background: #2c3e50; color: white; border: none; cursor: pointer; border-radius: 4px; }
        
        /* Fixed Header Layout */
        .print-header {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .header-left {
            display: table-cell;
            vertical-align: middle;
            width: 30%;
            text-align: left;
        }
        .header-center {
            display: table-cell;
            vertical-align: middle;
            width: 40%;
            text-align: center;
        }
        .header-right {
            display: table-cell;
            vertical-align: middle;
            width: 30%;
            text-align: right;
        }
        .header-logos {
            display: flex;
            align-items: center;
        }
        .header-logos img {
            height: 35px;
            margin-right: 4px;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="print-btn">Print Document</button>
    </div>

    <!-- Improved Header using Table-like behavior for Print Stability -->
    <div class="print-header">
        <div class="header-left">
            <div class="header-logos">
                <img src="{{ asset('images/Layer-0-1.png') }}" alt="Logo 1">
                <img src="{{ asset('images/What-Is-the-Difference-Between-DepEd-Seal-and-DepEd-Logo.png') }}" alt="Logo 2">
                <img src="{{ asset('images/drrmis-logo-2.png') }}" alt="Logo 3">
                <div style="font-size: 11px; font-weight: bold; text-transform: uppercase; margin-left: 3px;">DepEd DRRM</div>
            </div>
        </div>
        <div class="header-center">
            <h3 style="margin: 0; font-size: 11px; font-weight: bold; text-transform: uppercase; line-height: 1.1;">
                MONITORING TOOL FOR EARTHQUAKE AND FIRE DRILLS IN SCHOOLS
            </h3>
        </div>
        <div class="header-right">
            <!-- Reserved for potential barcodes or document codes -->
        </div>
    </div>

    <!-- Custom Data Grid Layout -->
    <table class="data-grid">
        <colgroup>
            <col style="width: 8%;">
            <col style="width: 25.33%;">
            <col style="width: 8%;">
            <col style="width: 25.33%;">
            <col style="width: 8%;">
            <col style="width: 25.33%;">
        </colgroup>
        <tr>
            <td class="label" colspan="2">NAME OF SCHOOL</td>
            <td colspan="2"><strong>{{ $inspection->school->school_name ?? 'N/A' }}</strong></td>
            <td class="label" colspan="2" style="text-align: center;">TYPE OF DRILL</td>
        </tr>
        <tr>
            <td class="label" colspan="2">SCHOOL ID</td>
            <td colspan="2">{{ $inspection->school->school_id ?? 'N/A' }}</td>
            <td style="text-align: center; border-right: none;">
                <span class="check-box" style="margin: 0;">
                    {{ (in_array($inspection->drill_type, ['Earthquake', 'Both'])) ? '✓' : '' }}
                </span>
            </td>
            <td style="border-left: none; font-weight: bold;">EARTHQUAKE</td>
        </tr>
        <tr>
            <td class="label" colspan="2">DATE AND TIME OF DRILL</td>
            <td colspan="2">
                {{ date('F d, Y', strtotime($inspection->inspection_date)) }} 
                {{ $inspection->inspection_time ?? '' }}
            </td>
            <td style="text-align: center; border-right: none;">
                <span class="check-box" style="margin: 0;">
                    {{ (in_array($inspection->drill_type, ['Fire', 'Both'])) ? '✓' : '' }}
                </span>
            </td>
            <td style="border-left: none; font-weight: bold;">FIRE</td>
        </tr>
        <tr>
            <td style="text-align: center; font-weight: bold;">{{ $inspection->time_started ?? 'N/A' }}</td>
            <td class="label">TIME STARTED</td>
            <td style="text-align: center; font-weight: bold;">{{ number_format($inspection->no_of_students ?? 0) }}</td>
            <td class="label">NO. OF STUDENTS</td>
            <td style="text-align: center; font-weight: bold;">{{ $inspection->no_of_exits ?? 0 }}</td>
            <td class="label">NO. OF EXIT/S</td>
        </tr>
        <tr>
            <td style="text-align: center; font-weight: bold;">{{ $inspection->time_finished ?? 'N/A' }}</td>
            <td class="label">TIME FINISHED</td>
            <td style="text-align: center; font-weight: bold;">{{ number_format($inspection->no_of_personnel ?? 0) }}</td>
            <td class="label">NO. OF PERSONNEL</td>
            <td style="text-align: center; font-weight: bold;">{{ $inspection->no_of_buildings ?? 0 }}</td>
            <td class="label">NO. OF BLDG/S</td>
        </tr>
        <tr>
            <td style="text-align: center; font-weight: bold; background-color: #f9f9f9;">{{ $inspection->elapsed_time ?? 'N/A' }}</td>
            <td class="label">ELAPSED TIME</td>
            <td style="text-align: center; font-weight: bold; background-color: #f9f9f9;">{{ number_format(($inspection->no_of_students ?? 0) + ($inspection->no_of_personnel ?? 0)) }}</td>
            <td class="label">TOTAL COUNT</td>
            <td colspan="2" style="background-color: #f2f2f2;"></td>
        </tr>
    </table>

    <!-- CHECKLIST ITEMS - 3 COLUMNS -->
    <div class="results-section">
        <h4>CHECKLIST ITEMS</h4>
        <table class="checklist-table">
            @php
                $checklistData = $inspection->checklist_data ?? [];
                // Use master list if available, otherwise fallback to saved data
                if (isset($checklists) && count($checklists) > 0) {
                    $allItems = $checklists->pluck('name')->toArray();
                } else {
                    $allItems = $checklistData;
                }
                $chunks = array_chunk($allItems, 3);
            @endphp

            @forelse($chunks as $chunk)
            <tr>
                @foreach($chunk as $itemLabel)
                <td style="width: 33%;">
                    <span class="check-box">
                        @if(in_array($itemLabel, $checklistData))
                            ✓
                        @else
                            &nbsp;
                        @endif
                    </span>
                    {{ $itemLabel }}
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
                    No checklist items configured or recorded.
                </td>
            </tr>
            @endforelse
        </table>
    </div>

    <!-- OTHER OBSERVERS PRESENT - 3 COLUMNS -->
    @if((isset($observers) && count($observers) > 0) || !empty($inspection->observers_data))
    <div class="results-section" style="margin-top: 5px;">
        <h4 style="margin-bottom: 3px;">OTHER OBSERVERS PRESENT</h4>
        <table class="checklist-table">
            @php
                $obsData = $inspection->observers_data ?? [];
                if (isset($observers) && count($observers) > 0) {
                    $allObs = $observers->pluck('name')->toArray();
                    
                    // Add the explicit "Others" label to master list
                    $allObs[] = 'OTHERS: (Please specify)';
                    
                    // Include any custom "Others: [text]" observers in the list
                    foreach($obsData as $item) {
                        if(strpos($item, 'Others: ') === 0 && !in_array($item, $allObs)) {
                            $allObs[] = $item;
                        }
                    }
                } else {
                    $allObs = $obsData;
                }
                $obsChunks = array_chunk($allObs, 3);
            @endphp

            @forelse($obsChunks as $chunk)
            <tr>
                @foreach($chunk as $observerLabel)
                <td style="width: 33%;">
                    <span class="check-box">
                        @php
                            $isChecked = in_array($observerLabel, $obsData);
                            // If this is the generic label, check if any "Others: " items exist
                            if (!$isChecked && $observerLabel === 'OTHERS: (Please specify)') {
                                foreach($obsData as $o) {
                                    if(strpos($o, 'Others: ') === 0) { $isChecked = true; break; }
                                }
                            }
                        @endphp
                        @if($isChecked)
                            ✓
                        @else
                            &nbsp;
                        @endif
                    </span>
                    {{ $observerLabel }}
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
                    No observers recorded.
                </td>
            </tr>
            @endforelse
        </table>
    </div>
    @endif

    <!-- REMARKS / OBSERVATIONS - MOVED BELOW OBSERVERS -->
    @if($inspection->remarks)
    <div class="results-section" style="margin-top: 5px;">
        <h4 style="margin-bottom: 3px;">REMARKS / OBSERVATIONS</h4>
        <div style="border: 1px solid #000; padding: 5px; min-height: 40px;">
            {{ $inspection->remarks }}
        </div>
    </div>
    @endif

    <!-- SIGNATURES - STACKED ON LEFT -->
    <table class="signatures">
        <tr>
            <td>
                <div style="text-align: left; font-weight: bold; margin-bottom: 5px;">Monitored By:</div>
                <div class="sig-line"><strong>{{ $inspection->monitored_by ?? '_____________________' }}</strong></div>
                <div class="sig-title">{{ $inspection->monitored_by_position ?? '_____________________' }}</div>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 20px;">
                <div style="text-align: left; font-weight: bold; margin-bottom: 5px;">Contents Noted:</div>
                <div class="sig-line"><strong>{{ $inspection->coordinator_name ?? '_____________________' }}</strong></div>
                <div class="sig-title">School DRRM Coordinator</div>
                
                <div style="margin-top: 15px;"></div>
                <div class="sig-line"><strong>{{ $inspection->school_head_name ?? '_____________________' }}</strong></div>
                <div class="sig-title">School Head / Principal</div>
            </td>
        </tr>
    </table>
</body>
</html>
