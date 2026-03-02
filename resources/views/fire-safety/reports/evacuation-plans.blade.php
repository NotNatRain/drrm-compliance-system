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
        .header-title {
            text-align: center;
            margin-bottom: 15px;
        }
        .header-title h1 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
            color: #A8191F;
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
        .plan-card {
            border: 1px solid #ccc;
            margin-bottom: 20px;
            padding: 15px;
            page-break-inside: avoid;
        }
        .plan-header {
            background-color: #f2f2f2;
            padding: 8px;
            margin: -15px -15px 15px -15px;
            border-bottom: 1px solid #ccc;
            font-weight: bold;
            font-size: 14px;
        }
        .field-group {
            margin-bottom: 10px;
        }
        .field-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
            color: #555;
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
        }
    </style>
</head>
<body>
    <div class="no-print" style="background: #f8f9fa; border-bottom: 1px solid #ddd; padding: 10px; text-align: center;">
        <button class="btn-print" onclick="window.print()">Print Report</button>
        <button class="btn-print" style="background: #6c757d;" onclick="window.close()">Close</button>
    </div>

    <div class="header-container">
        <div class="header-title">
            <h1>Evacuation Plans Master List</h1>
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
    </div>

    @if($plans->isEmpty())
        <div style="text-align: center; padding: 40px; border: 1px dashed #ccc; margin-bottom: 20px;">
            <h3>No evacuation plans currently registered for this school.</h3>
        </div>
    @else
        @foreach($plans as $plan)
            <div class="plan-card">
                <div class="plan-header">
                    Plan #: {{ $plan->plan_no }} — Building: {{ $plan->building ? $plan->building->building_no . ($plan->building->building_name ? ' (' . $plan->building->building_name . ')' : '') : 'N/A' }}
                </div>
                
                <div style="display: flex; justify-content: space-between;">
                    <div style="width: 48%;">
                        <div class="field-group">
                            <span class="field-label">Status:</span> 
                            <span>{{ strtoupper($plan->status) }}</span>
                        </div>
                        <div class="field-group">
                            <span class="field-label">Number of Exits:</span> 
                            <span>{{ $plan->exits }}</span>
                        </div>
                        <div class="field-group">
                            <span class="field-label">Evacuation Routes:</span> 
                            <span>{{ $plan->routes }}</span>
                        </div>
                        <div class="field-group">
                            <span class="field-label">Assembly Areas:</span> 
                            <span>{{ $plan->areas }}</span>
                        </div>
                        <div class="field-group">
                            <span class="field-label">Capacity:</span> 
                            <span>{{ $plan->assembly_capacity }}</span>
                        </div>
                    </div>
                    <div style="width: 48%;">
                        <div class="field-group">
                            <span class="field-label">Primary Route:</span> 
                            <span>{{ $plan->primary_route ?: 'Not Specified' }}</span>
                        </div>
                        <div class="field-group">
                            <span class="field-label">Secondary Route:</span> 
                            <span>{{ $plan->secondary_route ?: 'Not Specified' }}</span>
                        </div>
                        <div class="field-group">
                            <span class="field-label">Primary Assembly Area:</span> 
                            <span>{{ $plan->primary_assembly_area ?: 'Not Specified' }}</span>
                        </div>
                        <div class="field-group">
                            <span class="field-label">Secondary Assembly Area:</span> 
                            <span>{{ $plan->secondary_assembly_area ?: 'Not Specified' }}</span>
                        </div>
                        <div class="field-group">
                            <span class="field-label">Approval Date:</span> 
                            <span>{{ $plan->approved_at ? $plan->approved_at->format('F d, Y') : 'Pending' }}</span>
                        </div>
                    </div>
                </div>

                @if($plan->emergency_contacts || $plan->special_instructions)
                    <hr style="border: none; border-top: 1px dashed #ccc; margin: 10px 0;">
                    @if($plan->emergency_contacts)
                        <div class="field-group">
                            <span class="field-label">Emergency Contacts:</span> 
                            <span>{{ $plan->emergency_contacts }}</span>
                        </div>
                    @endif
                    @if($plan->special_instructions)
                        <div class="field-group">
                            <span class="field-label">Special Instructions:</span> 
                            <span>{{ $plan->special_instructions }}</span>
                        </div>
                    @endif
                @endif
            </div>
        @endforeach
    @endif

    <div class="purpose">
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
