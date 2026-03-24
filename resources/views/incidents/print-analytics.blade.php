<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $reportTitle }} - {{ $monthName }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 1cm;
        }

        body {
            padding: 20px;
            font-family: Arial, sans-serif;
            color: #212529;
        }

        .report-header {
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .meta {
            font-size: 0.92rem;
            color: #495057;
        }

        .chart-print-wrap {
            margin: 18px 0 20px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 12px;
            background: #ffffff;
        }

        .chart-print-wrap img {
            width: 100%;
            max-height: 440px;
            object-fit: contain;
            display: block;
        }

        .chart-note {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <div class="report-header">
        <h3 class="mb-1">INCIDENT CHECKLIST ANALYTICS REPORT</h3>
        <h5 class="mb-2">{{ strtoupper($reportTitle) }}</h5>
        <div class="meta">
            Reporting Period: {{ $monthName }}<br>
            Total Count: {{ $total }}
        </div>
    </div>

    <div class="chart-print-wrap" id="chartPrintWrap" style="display:none;">
        <img id="chartPrintImage" alt="Printed Analytics Chart">
        <div class="chart-note">Chart Snapshot from Dashboard Analytics</div>
    </div>

    <table class="table table-bordered table-sm align-middle">
        <thead class="table-light">
            <tr>
                <th style="width: 70px;">#</th>
                <th>Classification</th>
                <th style="width: 120px;" class="text-end">Count</th>
                <th style="width: 140px;" class="text-end">Percentage</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row['label'] }}</td>
                    <td class="text-end">{{ $row['count'] }}</td>
                    <td class="text-end">{{ number_format($row['percent'], 2) }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">No records available for this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-5">
        <p class="mb-5"><strong>Prepared by:</strong> ____________________________</p>
        <p class="mb-0"><strong>Validated by:</strong> ____________________________</p>
    </div>

    <script>
        window.onload = function () {
            const chartKey = @json($chartKey ?? null);
            const chartImage = document.getElementById('chartPrintImage');
            const chartWrap = document.getElementById('chartPrintWrap');

            if (chartKey) {
                const dataUrl = localStorage.getItem(chartKey);
                if (dataUrl) {
                    chartImage.src = dataUrl;
                    chartWrap.style.display = 'block';
                    localStorage.removeItem(chartKey);
                }
            }

            window.print();
        };
    </script>
</body>
</html>
