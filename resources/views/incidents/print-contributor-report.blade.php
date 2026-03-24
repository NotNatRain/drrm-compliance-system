<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $periodLabel }} Contributor Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            size: landscape;
            margin: 1cm;
        }

        body {
            padding: 20px;
            font-family: Arial, sans-serif;
            color: #212529;
        }

        .report-header {
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 18px;
            padding-bottom: 10px;
        }

        .meta {
            font-size: 0.92rem;
            color: #495057;
        }

        .summary-grid {
            margin: 15px 0 20px;
        }

        .summary-item {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 8px 10px;
            background: #f8f9fa;
            font-size: 0.88rem;
        }

        .small-cell {
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="report-header">
        <h3 class="mb-1">INCIDENT CHECKLIST CONTRIBUTOR REPORT</h3>
        <h5 class="mb-2">{{ strtoupper($periodLabel) }} REPORT</h5>
        <div class="meta">
            Contributor: {{ $user->name }}<br>
            Date Range: {{ $rangeLabel }}
        </div>
    </div>

    <div class="row summary-grid g-2">
        <div class="col"><div class="summary-item"><strong>Total:</strong> {{ $summary['total'] }}</div></div>
        <div class="col"><div class="summary-item"><strong>Incidents:</strong> {{ $summary['incidents'] }}</div></div>
        <div class="col"><div class="summary-item"><strong>Compliance:</strong> {{ $summary['compliance'] }}</div></div>
        <div class="col"><div class="summary-item"><strong>Accepted:</strong> {{ $summary['accepted'] }}</div></div>
        <div class="col"><div class="summary-item"><strong>Pending:</strong> {{ $summary['pending'] }}</div></div>
        <div class="col"><div class="summary-item"><strong>Rejected:</strong> {{ $summary['rejected'] }}</div></div>
    </div>

    <table class="table table-bordered table-sm align-middle">
        <thead class="table-light">
            <tr>
                <th style="width: 100px;">Date</th>
                <th style="width: 170px;">School</th>
                <th style="width: 110px;">Category</th>
                <th style="width: 200px;">Classification</th>
                <th>Remarks</th>
                <th style="width: 110px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                <tr>
                    <td class="small-cell">{{ optional($record->incident_date)->format('Y-m-d') }}</td>
                    <td class="small-cell">{{ $record->school_name }}</td>
                    <td class="small-cell">{{ $record->entry_type === 'incident' ? 'Incident' : 'Compliance' }}</td>
                    <td class="small-cell">
                        @if($record->entry_type === 'incident')
                            {{ optional($record->incidentType)->name ?? 'Unspecified' }}
                        @else
                            {{ optional($record->incidentStatus)->name ?? 'Unspecified' }}
                        @endif
                    </td>
                    <td class="small-cell">{{ $record->remarks }}</td>
                    <td class="small-cell text-capitalize">{{ $record->status ?? 'accepted' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No contributor records found for this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        <p class="mb-5"><strong>Prepared by:</strong> {{ $user->name }}</p>
        <p class="mb-0"><strong>Noted by:</strong> ____________________________</p>
    </div>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</body>
</html>
