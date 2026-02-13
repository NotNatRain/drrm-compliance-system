<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Volunteer Participation Certificate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow mx-auto" style="max-width:720px;">
        <div class="card-body text-center p-5">
            <h5 class="text-muted">Republic of the Philippines</h5>
            <h4 class="mb-4" style="color:#1B4C6D;">Department of Education</h4>
            <h2 class="mb-3">Certificate of Volunteer Participation</h2>
            <p class="mb-4">This certifies that</p>
            <h2 class="mb-3">{{ $assignment->volunteer->name }}</h2>
            <p class="mb-3">
                has rendered volunteer service as
                <strong>{{ $assignment->role ?? 'Community Volunteer' }}</strong>
                at <strong>{{ $assignment->school->school_name ?? 'Designated School' }}</strong>
            </p>
            <p class="mb-4">
                under the PIE-PRA (Pre-Disaster Intelligent Evacuation Predictor &amp; Resource Allocator) program
                for the scenario: <strong>{{ $assignment->scenario->name }}</strong>.
            </p>
            <p class="mb-4">
                Period of service:
                <strong>{{ optional($assignment->check_in_at)->format('M d, Y H:i') ?? 'N/A' }}</strong>
                to
                <strong>{{ optional($assignment->check_out_at)->format('M d, Y H:i') ?? 'N/A' }}</strong>
            </p>
            <p class="text-muted mb-2">
                Certificate No.: {{ $assignment->certificate_number ?? 'TBD' }}
            </p>
            <p class="text-muted mb-4">
                Issued on: {{ optional($assignment->certificate_issued_at ?? now())->format('M d, Y') }}
            </p>
            <div class="mt-5">
                <p class="mb-0"><strong>DRRM Coordinator</strong></p>
                <p class="text-muted">School / Division DRRM Office</p>
            </div>
        </div>
    </div>
</div>
</body>
</html>

