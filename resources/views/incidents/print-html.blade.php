<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Incidents & Events Calendar Report - {{ $monthName }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h3>Incidents & Events Calendar Report</h3>
    <p><strong>Month:</strong> {{ $monthName }}</p>

    <table class="table table-sm table-bordered table-striped align-middle mt-3">
        <thead class="table-light">
            <tr>
                <th style="width: 110px;">Date</th>
                <th style="width: 220px;">School Name</th>
                <th style="width: 140px;">Category</th>
                <th style="width: 220px;">Event Classification</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $rec)
                <tr>
                    <td>{{ $rec->incident_date ? $rec->incident_date->format('Y-m-d') : '' }}</td>
                    <td>{{ $rec->school_name }}</td>
                    <td>{{ $rec->entry_type === 'incident' ? 'Incident' : 'Compliance Status/Events' }}</td>
                    <td>
                        @if($rec->entry_type === 'incident')
                            {{ optional($rec->incidentType)->name }}
                        @else
                            {{ optional($rec->incidentStatus)->name }}
                        @endif
                    </td>
                    <td>{{ $rec->remarks }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        No incidents or events recorded for this month.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <script>
        // Automatically open print dialog for convenience
        window.onload = function () {
            window.print();
        };
    </script>
</body>
</html>

