<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Incidents & Events Calendar Report - {{ $monthName }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            size: landscape;
            margin: 1cm;
        }
        body {
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        .attested {
            margin-top: 50px;
            width: 100%;
        }
        .attested-line {
            display: inline-block;
            border-bottom: 1px solid black;
            min-width: 250px;
            margin-left: 10px;
        }
        .report-header {
            margin-bottom: 30px;
        }
        .no-print {
            display: none;
        }
        @media print {
        @page {
            margin: 1cm;
            size: landscape;
        }
        
        /* Remove browser print header/footer */
        @page :first {
            margin-top: 0;
        }
        
        /* Hide default browser print elements */
        body > * {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
    </style>
</head>
<body class="p-4">
    <div class="report-header">
        <h3 class="text-center mb-4">INCIDENTS & EVENTS CALENDAR REPORT</h3>
        <h5 class="text-center mb-4">{{ strtoupper($monthName) }}</h5>
    </div>

    <table class="table table-sm table-bordered table-striped align-middle">
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

    <div class="attested">
        <div class="row">
            <div class="col-12">
                <p><strong>Attested by:</strong></p>
                <p style="margin-top: 40px;">
                    <span style="font-weight: bold;">{{ $attestedBy ?? '_____________________' }}</span>
                </p>
                <div style="border-bottom: 1px solid black; width: 250px;"></div>
                <p class="text-muted small mt-1">Signature over Printed Name</p>
            </div>
        </div>
    </div>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</body>
</html>