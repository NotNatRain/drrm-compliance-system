<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evacuation Plans Tracking</title>
    <style>
        @page {
            size: portrait;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .header-container {
            margin-bottom: 20px;
        }
        .info-grid {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 20px;
            background: #fafafa;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .division {
            margin-bottom: 25px;
        }
        .section-title {
            font-weight: bold;
            font-size: 13px;
            padding: 6px 0;
            margin-bottom: 10px;
            border-bottom: 2px solid #333;
        }
        .division-body {
            padding: 10px 0;
        }
        /* Header Layout with Logos */
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
        .plan-card {
            border: 1px solid #ccc;
            margin-bottom: 15px;
            padding: 15px;
            page-break-inside: avoid;
        }
        .plan-header {
            background-color: #f2f2f2;
            padding: 8px 12px;
            margin: -15px -15px 15px -15px;
            border-bottom: 1px solid #ccc;
            font-weight: bold;
            font-size: 13px;
        }
        .field-group {
            margin-bottom: 8px;
        }
        .field-label {
            font-weight: bold;
            display: inline-block;
            min-width: 180px;
            color: #555;
        }
        .school-plan-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .school-plan-table th,
        .school-plan-table td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }
        .school-plan-table th {
            background: #f5f5f5;
            font-size: 11px;
            text-transform: uppercase;
            color: #555;
            width: 35%;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                color-adjust: exact;
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
        .no-data-box {
            text-align: center;
            padding: 30px;
            border: 1px dashed #ccc;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="no-print" style="background: #f8f9fa; border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">
        <button class="btn-print" onclick="window.print()">Print Report</button>
        <button class="btn-print" style="background: #6c757d;" onclick="window.close()">Close</button>
    </div>

    <!-- Header with 3 Logos -->
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
            <h3 style="margin: 0; font-size: 13px; font-weight: bold; text-transform: uppercase; line-height: 1.2;">
                Evacuation Plans Report
            </h3>
        </div>
        <div class="header-right"></div>
    </div>

    <div class="header-container">
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
    </div>

    {{-- ========== SCHOOL-WIDE EVACUATION PLAN ========== --}}
    <div class="division">
        <div class="section-title">
            School-Wide Evacuation Plan (Entire School)
        </div>
        <div class="division-body">
            @if($schoolPlan ?? null)
                <table class="school-plan-table">
                    <tr>
                        <th>Plan Name</th>
                        <td>{{ $schoolPlan->plan_no }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ strtoupper($schoolPlan->status) }}</td>
                    </tr>
                    <tr>
                        <th>Number of Assembly Areas</th>
                        <td>{{ $schoolPlan->areas ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Primary Assembly Area</th>
                        <td>{{ $schoolPlan->primary_assembly_area ?: 'Not Specified' }}</td>
                    </tr>
                    <tr>
                        <th>Secondary Assembly Area</th>
                        <td>{{ $schoolPlan->secondary_assembly_area ?: 'Not Specified' }}</td>
                    </tr>
                    <tr>
                        <th>Assembly Area Capacity</th>
                        <td>{{ $schoolPlan->assembly_capacity ? $schoolPlan->assembly_capacity . ' Persons' : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Special Instructions</th>
                        <td>{{ $schoolPlan->special_instructions ?: 'None' }}</td>
                    </tr>
                    <tr>
                        <th>Emergency Contacts</th>
                        <td>{{ $schoolPlan->emergency_contacts ?: 'None' }}</td>
                    </tr>
                </table>
            @else
                <div class="no-data-box">No school-wide evacuation plan has been created yet.</div>
            @endif
        </div>
    </div>

    {{-- ========== INDIVIDUAL BUILDING EVACUATION PLANS ========== --}}
    <div class="division">
        <div class="section-title">
            Individual Building Evacuation Plans
        </div>
        <div class="division-body">
            @if(($buildingPlans ?? collect())->isEmpty())
                <div class="no-data-box">No individual building evacuation plans have been created yet.</div>
            @else
                @foreach($buildingPlans as $plan)
                    <div class="plan-card">
                        <div class="plan-header">
                            {{ $plan->building ? $plan->building->building_no . ($plan->building->building_name ? ' — ' . $plan->building->building_name : '') : 'Unknown Building' }}
                        </div>

                        <div style="display: flex; gap: 20px;">
                            <div style="flex: 1;">
                                <div class="field-group">
                                    <span class="field-label">Plan Name:</span>
                                    <span>{{ $plan->plan_no }}</span>
                                </div>
                                <div class="field-group">
                                    <span class="field-label">Status:</span>
                                    <span>{{ strtoupper($plan->status) }}</span>
                                </div>
                                <div class="field-group">
                                    <span class="field-label">Number of Routes:</span>
                                    <span>{{ $plan->routes ?? 'N/A' }}</span>
                                </div>
                                <div class="field-group">
                                    <span class="field-label">Safety Features Installed:</span>
                                    <span>{{ $plan->safety_features_installed ?: 'Not Specified' }}</span>
                                </div>
                            </div>
                            <div style="flex: 1;">
                                <div class="field-group">
                                    <span class="field-label">Primary Evacuation Route:</span>
                                    <span>{{ $plan->primary_route ?: 'Not Specified' }}</span>
                                </div>
                                <div class="field-group">
                                    <span class="field-label">Secondary Evacuation Route:</span>
                                    <span>{{ $plan->secondary_route ?: 'Not Specified' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div style="margin-top: 30px; font-style: italic; color: #555;">
        <p><strong>Overall Purpose:</strong> This document serves as the official compilation of evacuation plans and assigned assembly parameters, providing critical action directives during emergencies.</p>
    </div>

    <div style="margin-top: 40px; display: flex; justify-content: space-between; padding: 0 50px;">
        <div style="text-align: center;">
            <p>Prepared by:</p>
            <br><br>
            <p>_______________________</p>
            <p>School DRRM Coordinator</p>
        </div>
        <div style="text-align: center;">
            <p>Noted by:</p>
            <br><br>
            <p>_______________________</p>
            <p>School Head / Principal</p>
        </div>
    </div>
</body>
</html>
