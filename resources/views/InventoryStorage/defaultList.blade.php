<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Inventory Compliance Report</title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1a2e2e; background: #fff; }

    /* Header */
    .report-header { text-align: center; padding: 18px 0 14px; border-bottom: 2px solid #0D7377; margin-bottom: 18px; }
    .report-header .agency { font-size: 8px; text-transform: uppercase; letter-spacing: .08em; color: #6b8080; }
    .report-header h1 { font-size: 15px; font-weight: 700; color: #0D7377; margin: 4px 0 2px; }
    .report-header .school-name { font-size: 11px; font-weight: 700; color: #1a2e2e; }
    .report-header .meta { font-size: 8px; color: #9bb2b2; margin-top: 4px; }

    /* Section header */
    .section-header { background: #0D7377; color: #fff; padding: 7px 12px; border-radius: 4px 4px 0 0; margin-top: 18px; }
    .section-header h2 { font-size: 10px; font-weight: 700; letter-spacing: .04em; }
    .section-header .score { float: right; font-size: 9px; font-weight: 600; }

    /* Table */
    table { width: 100%; border-collapse: collapse; font-size: 9px; }
    thead th { background: #eef5f5; color: #4a6060; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; padding: 6px 8px; border-bottom: 1px solid #cdd8d8; text-align: left; }
    tbody td { padding: 6px 8px; border-bottom: 1px solid #f0f5f5; vertical-align: top; }
    tbody tr:nth-child(even) td { background: #f8fbfb; }
    .badge-yes { color: #065f46; font-weight: 700; }
    .badge-no  { color: #9ca3af; }
    .badge-source { background: #e0f2fe; color: #0369a1; padding: 1px 5px; border-radius: 3px; }

    /* Inventory table */
    .inv-section-header { background: #1a2e2e; color: #fff; padding: 7px 12px; border-radius: 4px 4px 0 0; margin-top: 18px; }
    .inv-section-header h2 { font-size: 10px; font-weight: 700; }
    .status-missing   { color: #c0293a; font-weight: 700; }
    .status-working   { color: #065f46; font-weight: 700; }
    .status-attention { color: #b45309; font-weight: 700; }
    .status-repair    { color: #3730a3; font-weight: 700; }
    .status-defective { color: #6b21a8; font-weight: 700; }

    /* Footer */
    .report-footer { margin-top: 24px; padding-top: 10px; border-top: 1px solid #e0eaea; text-align: center; font-size: 8px; color: #9bb2b2; }
    .compliance-summary { display: flex; gap: 12px; margin-top: 14px; }
    .summary-box { flex: 1; border: 1px solid #dde8e8; border-radius: 4px; padding: 8px 10px; text-align: center; }
    .summary-box .val { font-size: 18px; font-weight: 700; }
    .summary-box .lbl { font-size: 7.5px; text-transform: uppercase; letter-spacing: .06em; color: #9bb2b2; }
    .val-good { color: #065f46; }
    .val-fair { color: #b45309; }
    .val-poor { color: #c0293a; }
</style>
</head>
<body>

<!-- Header -->
<div class="report-header">
    <div class="agency">Division Disaster Risk Reduction &amp; Management Office</div>
    <h1>Inventory &amp; Resource Compliance Report</h1>
    <div class="school-name">{{ $school->school_name }}</div>
    <div class="meta">Generated: {{ $generatedAt }} &nbsp;|&nbsp; School ID: {{ $school->school_id ?? 'N/A' }}</div>
</div>

@php
    $savedA  = $saved['A'] ?? collect();
    $savedB  = $saved['B'] ?? collect();
    $totalA  = count($itemsA);
    $totalB  = count($itemsB);
    $haveA   = $savedA->where('has_item', true)->count();
    $haveB   = $savedB->where('has_item', true)->count();
    $pctA    = $totalA > 0 ? round(($haveA / $totalA) * 100) : 0;
    $pctB    = $totalB > 0 ? round(($haveB / $totalB) * 100) : 0;
    $totalAll = $totalA + $totalB;
    $haveAll  = $haveA + $haveB;
    $pctAll   = $totalAll > 0 ? round(($haveAll / $totalAll) * 100) : 0;
    $colorClass = fn($p) => $p >= 80 ? 'val-good' : ($p >= 50 ? 'val-fair' : 'val-poor');
@endphp

<!-- Compliance summary boxes -->
<table style="width:100%; margin-top:0;" cellspacing="0" cellpadding="0">
    <tr>
        <td style="width:33%; padding:6px; text-align:center; border:1px solid #dde8e8; border-radius:4px;">
            <div style="font-size:18px; font-weight:700;" class="{{ $colorClass($pctA) }}">{{ $pctA }}%</div>
            <div style="font-size:7.5px; text-transform:uppercase; color:#9bb2b2;">Section A Compliance</div>
            <div style="font-size:8px; color:#4a6060;">{{ $haveA }} / {{ $totalA }} items</div>
        </td>
        <td style="width:4%;"></td>
        <td style="width:33%; padding:6px; text-align:center; border:1px solid #dde8e8; border-radius:4px;">
            <div style="font-size:18px; font-weight:700;" class="{{ $colorClass($pctB) }}">{{ $pctB }}%</div>
            <div style="font-size:7.5px; text-transform:uppercase; color:#9bb2b2;">Section B Compliance</div>
            <div style="font-size:8px; color:#4a6060;">{{ $haveB }} / {{ $totalB }} items</div>
        </td>
        <td style="width:4%;"></td>
        <td style="width:33%; padding:6px; text-align:center; border:1px solid #dde8e8; border-radius:4px;">
            <div style="font-size:18px; font-weight:700;" class="{{ $colorClass($pctAll) }}">{{ $pctAll }}%</div>
            <div style="font-size:7.5px; text-transform:uppercase; color:#9bb2b2;">Overall Compliance</div>
            <div style="font-size:8px; color:#4a6060;">{{ $haveAll }} / {{ $totalAll }} items</div>
        </td>
    </tr>
</table>

<!-- Section A -->
<div class="section-header">
    <h2>Section A — Emergency Supplies and Equipment <span class="score">{{ $haveA }} / {{ $totalA }} sourced ({{ $pctA }}%)</span></h2>
</div>
<table>
    <thead>
        <tr>
            <th style="width:32%">Item</th>
            <th style="width:8%">Have</th>
            <th style="width:8%">Qty</th>
            <th style="width:18%">Source</th>
            <th style="width:15%">Date Checked</th>
            <th style="width:19%">Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach($itemsA as $key => $name)
            @php $row = $savedA->get($key); @endphp
            <tr>
                <td>{{ $name }}</td>
                <td class="{{ $row && $row->has_item ? 'badge-yes' : 'badge-no' }}">
                    {{ $row && $row->has_item ? '✓ Yes' : '✗ No' }}
                </td>
                <td>{{ $row ? $row->quantity_owned : '–' }}</td>
                <td>
                    @if($row && $row->source)
                        <span class="badge-source">{{ ucfirst($row->source) }}</span>
                        @if($row->source_detail) <br><small>{{ $row->source_detail }}</small> @endif
                    @else –
                    @endif
                </td>
                <td>{{ $row && $row->date_checked ? $row->date_checked->format('M d, Y') : '–' }}</td>
                <td>{{ $row ? ($row->remarks ?: '–') : '–' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Section B -->
<div class="section-header" style="background:#1a7f5a;">
    <h2>Section B — Response and Rescue Supplies <span class="score">{{ $haveB }} / {{ $totalB }} sourced ({{ $pctB }}%)</span></h2>
</div>
<table>
    <thead>
        <tr>
            <th style="width:32%">Item</th>
            <th style="width:8%">Have</th>
            <th style="width:8%">Qty</th>
            <th style="width:18%">Source</th>
            <th style="width:15%">Date Checked</th>
            <th style="width:19%">Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach($itemsB as $key => $name)
            @php $row = $savedB->get($key); @endphp
            <tr>
                <td>{{ $name }}</td>
                <td class="{{ $row && $row->has_item ? 'badge-yes' : 'badge-no' }}">
                    {{ $row && $row->has_item ? '✓ Yes' : '✗ No' }}
                </td>
                <td>{{ $row ? $row->quantity_owned : '–' }}</td>
                <td>
                    @if($row && $row->source)
                        <span class="badge-source">{{ ucfirst($row->source) }}</span>
                        @if($row->source_detail) <br><small>{{ $row->source_detail }}</small> @endif
                    @else –
                    @endif
                </td>
                <td>{{ $row && $row->date_checked ? $row->date_checked->format('M d, Y') : '–' }}</td>
                <td>{{ $row ? ($row->remarks ?: '–') : '–' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Inventory Ledger -->
@if($inventoryItems->count() > 0)
<div class="inv-section-header">
    <h2>Inventory Ledger ({{ $inventoryItems->count() }} items)</h2>
</div>
<table>
    <thead>
        <tr>
            <th style="width:28%">Item Name</th>
            <th style="width:8%">Unit</th>
            <th style="width:6%">Qty</th>
            <th style="width:13%">Status</th>
            <th style="width:13%">Location</th>
            <th style="width:17%">Fund Source</th>
            <th style="width:15%">Date Checked</th>
        </tr>
    </thead>
    <tbody>
        @foreach($inventoryItems as $item)
        @php
            $sc = match($item->status) {
                'missing'         => 'status-missing',
                'needs_attention' => 'status-attention',
                'working'         => 'status-working',
                'for_repair'      => 'status-repair',
                'defective'       => 'status-defective',
                default           => '',
            };
            $sl = match($item->status) {
                'missing'         => 'Missing',
                'needs_attention' => 'Needs Attention',
                'working'         => 'Working',
                'for_repair'      => 'For Repair',
                'defective'       => 'Defective',
                default           => ucfirst($item->status),
            };
        @endphp
        <tr>
            <td>{{ $item->item_name }}</td>
            <td>{{ $item->unit ?: '–' }}</td>
            <td>{{ $item->quantity }}</td>
            <td class="{{ $sc }}">{{ $sl }}</td>
            <td>{{ $item->location ?: '–' }}</td>
            <td>{{ $item->fund_source ?: '–' }}</td>
            <td>{{ $item->date_checked ? \Carbon\Carbon::parse($item->date_checked)->format('M d, Y') : '–' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<div class="report-footer">
    This report was generated automatically by the DRRM Compliance System &nbsp;|&nbsp; {{ $generatedAt }}
</div>

</body>
</html>
