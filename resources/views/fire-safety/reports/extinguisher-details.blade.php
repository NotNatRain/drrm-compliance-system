<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fire Extinguisher Inspection tracking</title>
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
        .section-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 2px solid #000;
            padding-bottom: 4px;
            margin-top: 28px;
            margin-bottom: 8px;
        }
        .uncovered {
            color: #c0392b;
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
            font-size: 10px;
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
            <h1 style="margin: 0; font-size: 14px; font-weight: normal; text-transform: uppercase;">Fire Extinguisher Inspection and Coverage Details</h1>
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

    <div class="page-1">
        <table>
            <thead>
                <tr>
                    <th>Extinguisher Code</th>
                    <th>Room Covered / Location</th>
                    <th>Status</th>
                    <th>Date Last Checked</th>
                    <th>Type</th>
                    <th style="width: 25%;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($extinguishers as $ext)
                    <tr>
                        <td><strong>{{ $ext->code }}</strong></td>
                        <td>
                            {{ $ext->centerRoom ? $ext->centerRoom->room_name : 'N/A' }}
                            @php
                                $otherRooms = $ext->coveredRooms->filter(fn($r) => $ext->centerRoom && $r->id !== $ext->centerRoom->id);
                            @endphp
                            @if($otherRooms->count() > 0)
                                <small class="text-muted d-block">({{ $otherRooms->map(fn($r) => 'Room ' . ($r->room_code ?: $r->room_name))->implode(' & ') }} taking cover)</small>
                            @endif
                        </td>
                        <td class="status-cell">{{ strtoupper($ext->status) }}</td>
                        <td>{{ $ext->date_checked ? \Carbon\Carbon::parse($ext->date_checked)->format('M d, Y') : 'N/A' }}</td>
                        <td>{{ $ext->type }}</td>
                        <td>{{ $ext->remarks }} {{ $ext->notes }}</td>
                    </tr>
                @endforeach
                @if($extinguishers->isEmpty())
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px;">No fire extinguishers recorded for this school.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="purpose">
            <p><strong>Overall Purpose:</strong> This sheet is designed for equipment-level tracking, allowing monitoring of maintenance schedules, operational readiness, placement coverage, and compliance documentation.</p>
        </div>

        <div style="margin-top: 40px; display: flex; justify-content: space-between; padding: 0 50px;">
            <div style="text-align: center;">
                <p>Prepared by:</p>
                <br><br>
                <p>_______________________</p>
                <p>School DRRM Coordinator</p>
            </div>
            <div style="text-align: center;">
                <p>Approved by:</p>
                <br><br>
                <p>_______________________</p>
                <p>School Head / Principal</p>
            </div>
        </div>
    </div>

    {{-- PAGE BREAK --}}
    <div style="page-break-after: always; height: 1px;"></div>

    <div class="page-2">
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
            <h1 style="margin: 0; font-size: 14px; font-weight: normal; text-transform: uppercase;">Building Room Details & Coverage</h1>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-row">
            <div><strong>Name of School:</strong> {{ $school->school_name }}</div>
            <div><strong>School ID:</strong> {{ $school->school_id }}</div>
        </div>
    </div>

    {{-- ===== ROOM INFORMATION SECTION ===== --}}
    <div class="section-title">Room Information</div>
    <table>
        <thead>
            <tr>
                <th style="width: 3%; text-align: center;">#</th>
                <th style="width: 7%;">Room Code</th>
                <th style="width: 13%;">Room Name</th>
                <th style="width: 12%;">Building</th>
                <th style="width: 4%; text-align: center;">Floor</th>
                <th style="width: 13%;">Room Type</th>
                <th style="width: 6%; text-align: center;">2nd Exit</th>
                <th style="width: 8%; text-align: center;">Smoke Detector</th>
                <th style="width: 14%;">Covered By</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rooms as $index => $room)
                @php
                    $coveringExts  = $room->extinguishersCoveringThisRoom;
                    $hostedExt     = $room->hostedExtinguisher;
                    $isUncovered   = $coveringExts->isEmpty();
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $room->room_code ?: '—' }}</td>
                    <td>{{ $room->room_name }}</td>
                    <td>{{ $room->building ? ($room->building->building_no . ($room->building->building_name ? ' - ' . $room->building->building_name : '')) : 'N/A' }}</td>
                    <td style="text-align: center;">{{ $room->floor_no ?? '—' }}</td>
                    <td>{{ $room->roomTypeConfig?->name ?? $room->room_type }}</td>
                    <td style="text-align: center;">{{ $room->has_secondary_exit ? 'Yes' : 'No' }}</td>
                    <td style="text-align: center;">
                        @if($room->smoke_detector_required)
                            {{ $room->has_smoke_detector ? 'Yes' : 'No' }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($isUncovered)
                            <span class="uncovered">Uncovered</span>
                        @else
                            @foreach($coveringExts as $covExt)
                                <strong>{{ $covExt->code }}</strong>@if($hostedExt && $hostedExt->id === $covExt->id) <em>(Center)</em>@endif{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        @endif
                    </td>
                    <td>{{ $room->remarks ?: '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center; padding: 20px;">No rooms recorded for this school.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 40px; display: flex; justify-content: space-between; padding: 0 50px;">
        <div style="text-align: center;">
            <p>Prepared by:</p>
            <br><br>
            <p>_______________________</p>
            <p>School DRRM Coordinator</p>
        </div>
        <div style="text-align: center;">
            <p>Approved by:</p>
            <br><br>
            <p>_______________________</p>
            <p>School Head / Principal</p>
        </div>
    </div>
</body>
</html>
