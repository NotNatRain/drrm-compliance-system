<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Assistance Record</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap');

        :root {
            --sidebar-bg: #0f172a;
            --accent-blue: #00d2ff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f1f5f9;
            color: #1e293b;
        }

        .dashboard-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        .app-sidebar {
            width: 280px;
            min-height: 100vh;
            background-color: var(--sidebar-bg);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .sidebar-header {
            padding: 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-brand {
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            font-size: 1.25rem;
            color: #ffffff;
            line-height: 1.2;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sidebar-brand span {
            color: var(--accent-blue);
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            letter-spacing: 1px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 12px;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            font-size: 1.05rem;
            letter-spacing: 0.5px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .sidebar-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: rgba(0, 210, 255, 0.1);
            color: var(--accent-blue);
        }

        .sidebar-link-toggle {
            width: 100%;
            border: 0;
            background: transparent;
            cursor: pointer;
            text-align: left;
        }

        .sidebar-link-toggle .sidebar-chevron {
            margin-left: auto;
            font-size: 0.75rem;
            transition: transform 0.2s ease;
        }

        .sidebar-link-toggle[aria-expanded="true"] .sidebar-chevron {
            transform: rotate(180deg);
        }

        .sidebar-submenu {
            list-style: none;
            padding: 4px 0 0 34px;
            margin: 0;
        }

        .sidebar-submenu a,
        .sidebar-submenu button {
            display: block;
            width: 100%;
            padding: 8px 12px;
            border: 0;
            border-radius: 6px;
            background: transparent;
            color: rgba(255, 255, 255, 0.55);
            text-align: left;
            text-decoration: none;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
        }

        .sidebar-submenu a:hover,
        .sidebar-submenu button:hover {
            background: rgba(255, 255, 255, 0.05);
            color: var(--accent-blue);
        }

        .sidebar-submenu a.active {
            color: var(--accent-blue);
        }

        .content-panel {
            flex-grow: 1;
            min-width: 0;
            min-height: 100vh;
            overflow-y: auto;
            padding: 40px 20px;
        }

        .dashboard-card {
            background: #ffffff;
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .form-header {
            background: #0f172a;
            color: #ffffff;
            padding: 32px;
            border-bottom: 4px solid #ef4444;
        }

        .form-header h1 {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .form-header p {
            color: #94a3b8;
            font-size: 14px;
        }

        .page-content {
            padding: 32px;
        }

        .back-button,
        .record-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 18px;
            border: none;
            border-radius: 6px;
            background-color: #2563eb;
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .back-button:hover,
        .record-button:hover {
            background-color: #1d4ed8;
            transform: translateY(-1px);
        }

        .record-button {
            margin-top: 16px;
        }

        .page-content h2 {
            margin: 28px 0 8px;
            color: #1e293b;
        }

        .page-content p {
            color: #64748b;
        }

        .table-heading {
            margin-top: 30px;
            color: #334155;
            font-size: 16px;
        }

        .table-wrapper {
            margin-top: 24px;
            overflow-x: auto;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }

        .display-table-wrapper {
            margin-top: 14px;
            overflow: visible !important;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }

        .display-table-wrapper .table-scroll {
            overflow-x: auto;
        }

        .display-table {
            min-width: 0 !important;
            width: 100%;
        }

        .family-search {
            display: block;
            width: 100%;
            max-width: 420px;
            margin-top: 14px;
        }

        .family-search input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            color: #1e293b;
            font-size: 14px;
            outline: none;
        }

        .family-search input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .table-heading-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-top: 30px;
        }

        .table-heading-row .table-heading {
            margin-top: 0;
        }

        .print-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 14px;
            border: none;
            border-radius: 6px;
            background-color: #475569;
            color: #ffffff;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }

        .print-button:hover {
            background-color: #334155;
        }

        .record-actions {
            display: flex;
            gap: 8px;
        }

        .record-action {
            border: none;
            border-radius: 5px;
            padding: 7px 10px;
            color: #ffffff;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .view-record-button {
            background: #2563eb;
        }

        .print-record-button {
            background: #475569;
        }

        .view-record-button:hover {
            background: #1d4ed8;
        }

        .print-record-button:hover {
            background: #334155;
        }

        .record-modal {
            position: fixed;
            inset: 0;
            z-index: 10;
            display: grid;
            place-items: center;
            padding: 20px;
            background: rgba(15, 23, 42, 0.55);
        }

        .record-modal[hidden],
        .individual-print-area {
            display: none;
        }

        .record-modal-content {
            width: min(900px, 100%);
            max-height: 90vh;
            overflow-y: auto;
            padding: 24px;
            border-radius: 10px;
            background: #ffffff;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        }

        .record-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .record-modal-header h2 {
            color: #1e293b;
            font-size: 20px;
        }

        .close-modal-button {
            border: none;
            background: transparent;
            color: #64748b;
            font-size: 22px;
            cursor: pointer;
        }

        .record-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .record-detail {
            padding: 10px;
            border-radius: 6px;
            background: #f8fafc;
        }

        .record-detail strong {
            display: block;
            margin-bottom: 4px;
            color: #64748b;
            font-size: 11px;
            text-transform: uppercase;
        }

        .record-detail span {
            color: #1e293b;
            font-size: 13px;
        }

        .assistance-table { 
            width: 100%;
            min-width: 1050px;
            border-collapse: collapse;
            background: #ffffff;
        }

        .assistance-table th {
            padding: 13px 10px;
            background: #0f172a;
            color: #ffffff;
            font-size: 12px;
            font-weight: 600;
            text-align: left;
            white-space: nowrap;
        }

        .assistance-table td {
            padding: 8px;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            white-space: nowrap;
        }

        .assistance-table input {
            width: 100%;
            min-width: 90px;
            padding: 9px 8px;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            color: #1e293b;
            font-size: 12px;
            outline: none;
        }

        .assistance-table input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .assistance-table .date-input {
            min-width: 125px;
        }

        .assistance-table .signature-input {
            min-width: 130px;
        }

        .display-table td {
            background-color: #f8fafc;
            min-width: 100px;
        }

        .display-table .empty-message {
            text-align: center;
            color: #64748b;
            padding: 18px;
        }

        .assistance-column-heading {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .assistance-menu-wrap {
            position: relative;
        }

        .assistance-menu-toggle {
            display: inline-grid;
            place-items: center;
            width: 24px;
            height: 24px;
            padding: 0;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 4px;
            background: transparent;
            color: #ffffff;
            cursor: pointer;
        }

        .assistance-menu {
            position: absolute;
            top: calc(100% + 5px);
            right: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            min-width: 185px;
            padding: 4px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            background: #ffffff;
            box-shadow: 0 8px 16px rgba(15, 23, 42, 0.15);
        }

        .assistance-menu[hidden] {
            display: none;
        }

        .assistance-menu button {
            width: 100%;
            padding: 8px 10px;
            border: 0;
            border-radius: 4px;
            background: transparent;
            color: #334155;
            font-size: 12px;
            text-align: left;
            white-space: nowrap;
            cursor: pointer;
        }

        .assistance-menu button:hover {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .family-assistance-selection {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .assistance-table .family-assistance-checkbox {
            width: 16px;
            min-width: 16px;
            height: 16px;
            flex: 0 0 16px;
            accent-color: #2563eb;
            cursor: pointer;
        }

        .family-assistance-description {
            min-width: 180px !important;
        }

        @media (max-width: 900px) {
            .app-sidebar {
                width: 220px;
            }
        }

        @media print {
            @page {
                size: landscape;
                margin: 12mm;
            }

            body {
                background: #ffffff;
            }

            .app-sidebar,
            .form-header,
            .back-button,
            .page-content > h2,
            .page-content > p,
            .page-content > h3.table-heading:not(.table-heading-row .table-heading),
            .table-wrapper:not(.display-table-wrapper),
            #recordAssistanceButton,
            .print-button,
            .record-actions,
            .record-modal,
            .assistance-modal-backdrop,
            .preview-message {
                display: none !important;
            }

            .dashboard-wrapper,
            .content-panel,
            .dashboard-card,
            .page-content {
                display: block;
                width: 100%;
                max-width: none;
                min-height: 0;
                padding: 0;
                margin: 0;
                border: none;
                box-shadow: none;
                overflow: visible;
            }

            .table-heading-row {
                display: block;
                margin: 0 0 12px;
            }

            .table-heading-row .table-heading {
                display: block;
            }

            .display-table-wrapper {
                margin: 0;
                overflow: visible;
                border: 1px solid #cbd5e1;
            }

            .display-table {
                min-width: 0;
            }

            .display-table th,
            .display-table td {
                font-size: 9px;
                padding: 6px;
            }

            .individual-print-area {
                display: none;
            }

            body.individual-printing .individual-print-area {
                display: block;
            }

            body.individual-printing .display-table-wrapper,
            body.individual-printing .table-heading-row {
                display: none !important;
            }
        }

        @media (max-width: 768px) {
            .dashboard-wrapper {
                display: block;
            }

            .app-sidebar {
                width: 100%;
                min-height: auto;
            }

            .sidebar-header {
                padding: 18px;
            }

            .sidebar-menu {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                padding: 12px;
            }

            .content-panel {
                padding: 20px 12px;
            }

            .form-header,
            .page-content {
                padding: 22px;
            }
        }
    </style>
</head>
 <body>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <div class="dashboard-wrapper">
        <aside class="app-sidebar">
            <div class="sidebar-header">
                <div class="sidebar-brand">
                    DSWD FACED
                    <span>Disaster Response Portal</span>
                </div>
            </div>

            <nav>
                <ul class="sidebar-menu">
                    <li>
                        <a href="{{ route('typhoon.dashboard') }}" class="sidebar-link">
                            <i class="fas fa-th-large"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link">
                            <i class="fas fa-school"></i>
                            <span>Evacuation Centers</span>
                        </a>
                    </li>
                    <li>
                        <button type="button" class="sidebar-link sidebar-link-toggle" data-bs-toggle="collapse" data-bs-target="#familiesSidebarMenu" aria-expanded="true" aria-controls="familiesSidebarMenu">
                            <i class="fas fa-users"></i>
                            <span>Families &amp; Individuals</span>
                            <i class="fas fa-chevron-down sidebar-chevron"></i>
                        </button>
                        <ul id="familiesSidebarMenu" class="sidebar-submenu collapse show">
                            <li>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#familyRegistrationModal">
                                    <i class="fas fa-user-plus me-2"></i> Register Family
                                </button>
                            </li>
                            <li>
                                <a href="{{ route('typhoon.dswd-form') }}">
                                    <i class="fas fa-file-alt me-2"></i> DSWD Form
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('typhoon.family-assistance') }}" class="active" aria-current="page">
                                    <i class="fas fa-hand-holding-heart me-2"></i> Family Assistance
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link">
                            <i class="fas fa-box-open"></i>
                            <span>Resource Inventory</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link">
                            <i class="fas fa-file-alt"></i>
                            <span>Incident Reports</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="content-panel">
            <div class="dashboard-card">
                <header class="form-header">
                    <h1>Family Assistance Card in Emergencies and Disasters (FACED)</h1>
                    <p>DSWD Disaster Response Management System Digitization Portal</p>
                </header>

                <section class="page-content">
                    <a href="{{ route('typhoon.dashboard') }}" class="back-button">
                        <i class="fas fa-arrow-left"></i>
                        Back to Dashboard
                    </a>

                    <h2>Family Assistance Record</h2>
                    <p>Record the assistance provided to the affected family.</p>

                    <h3 class="table-heading">Registered Families</h3>
                    <label class="family-search">
                        <input type="search" id="familySearch" placeholder="Search by family head, serial number, or barangay" aria-label="Search registered families">
                    </label>
                    <div class="table-wrapper display-table-wrapper">
                        <div class="table-scroll">
                        <table class="assistance-table display-table">
                            <thead>
                                <tr>
                                    <th>Family Head Name</th>
                                    <th>Serial Number</th>
                                    <th>Barangay</th>
                                    <th>
                                        <div class="assistance-column-heading">
                                            <span>Assistance</span>
                                            <div class="assistance-menu-wrap">
                                                <button type="button" class="assistance-menu-toggle" id="assistanceMenuToggle" aria-label="Assistance options" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-caret-down" aria-hidden="true"></i>
                                                </button>
                                                <div class="assistance-menu" id="assistanceMenu" hidden>
                                                    <button type="button" id="selectAllAssistance">Select all</button>
                                                    <button type="button" id="unselectAllAssistance">Unselect all</button>
                                                    <button type="button" id="selectAssistanceMode">Give to selected families</button>
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($families as $family)
                                    <tr class="registered-family-row">
                                        <td>{{ $family->head_family_name ?: '—' }}</td>
                                        <td>
                                            @if(!empty($family->serial_number))
                                                <a href="{{ route('typhoon.dswd-form', ['serial_number' => $family->serial_number]) }}" class="badge bg-primary text-decoration-none" title="Open in DSWD Form" style="font-size:12px;padding:4px 8px;">
                                                    {{ $family->serial_number }} <i class="fas fa-external-link-alt ms-1" style="font-size:10px;"></i>
                                                </a>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>{{ $family->barangay ?: '—' }}</td>
                                        <td>
                                            <div class="family-assistance-selection">
                                                <input type="checkbox" class="family-assistance-checkbox" aria-label="Select {{ $family->head_family_name ?: 'family' }} for assistance">
                                                <input type="text" class="family-assistance-description" placeholder="Description" aria-label="Assistance description for {{ $family->head_family_name ?: 'family' }}">
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="empty-message">No registered families found.</td>
                                    </tr>
                                @endforelse
                                <tr id="familySearchNoResults" hidden>
                                    <td colspan="4" class="empty-message">No matching families found.</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>{{-- .table-scroll --}}
                    </div>

                    <h3 class="table-heading">Enter Assistance Details</h3>
                    <div class="table-wrapper">
                        <table class="assistance-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Name of Receiving Family Member</th>
                                    <th>Emergency / Disaster</th>
                                    <th>Assistance</th>
                                    <th>Unit</th>
                                    <th>Quantity</th>
                                    <th>Cost</th>
                                    <th>Provider</th>
                                    <th>Signature</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input class="date-input" type="date" name="assistance_date"></td>
                                    <td><input type="text" name="receiving_family_member"></td>
                                    <td><input type="text" name="emergency_disaster"></td>
                                    <td><input type="text" name="assistance"></td>
                                    <td><input type="text" name="unit"></td>
                                    <td><input type="number" name="quantity" min="0"></td>
                                    <td><input type="number" name="cost" min="0" step="0.01" placeholder="₱"></td>
                                    <td><input type="text" name="provider"></td>
                                    <td><input class="signature-input" type="text" name="signature"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="record-button" id="recordAssistanceButton">
                        <i class="fas fa-save"></i>
                        Record Assistance
                    </button>

                    <div class="table-heading-row">
                        <h3 class="table-heading">Recorded Assistance</h3>
                    </div>
                    <div class="table-wrapper display-table-wrapper">
                        <table class="assistance-table display-table">
                            <thead>
                                <tr>
                                    <th>Name of Receiving Family Member</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="recordedAssistanceBody">
                                <tr id="emptyRow">
                                    <td colspan="3" class="empty-message">
                                        No assistance records yet.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <div class="record-modal" id="recordModal" hidden>
        <div class="record-modal-content" role="dialog" aria-modal="true" aria-labelledby="recordModalTitle">
            <div class="record-modal-header">
                <h2 id="recordModalTitle">Assistance Record</h2>
                <button type="button" class="close-modal-button" id="closeRecordModal" aria-label="Close">&times;</button>
            </div>
            <div class="record-details" id="recordDetails"></div>
        </div>
    </div>

    <div class="individual-print-area" id="individualPrintArea"></div>

    @include('typhoon.assistance-modal')

    <script>
        const recordModal = document.getElementById('recordModal');
        const recordDetails = document.getElementById('recordDetails');
        const individualPrintArea = document.getElementById('individualPrintArea');
        const closeRecordModal = document.getElementById('closeRecordModal');
        const printAssistanceButton = document.getElementById('printAssistanceButton');
        const familySearch = document.getElementById('familySearch');
        const familyRows = document.querySelectorAll('.registered-family-row');
        const familySearchNoResults = document.getElementById('familySearchNoResults');
        const assistanceMenuToggle = document.getElementById('assistanceMenuToggle');
        const assistanceMenu = document.getElementById('assistanceMenu');
        const selectAllAssistanceBtn = document.getElementById('selectAllAssistance');
        const unselectAllAssistanceBtn = document.getElementById('unselectAllAssistance');
        const selectAssistanceModeBtn = document.getElementById('selectAssistanceMode');

        // Assistance Modal Elements
        const assistanceModalBackdrop = document.getElementById('assistanceModalBackdrop');
        const giveModalBackdrop = document.getElementById('giveModalBackdrop');
        const selectAllFamiliesBtn = document.getElementById('selectAllFamilies');
        const unselectAllFamiliesBtn = document.getElementById('unselectAllFamilies');
        const selectionStatus = document.getElementById('selectionStatus');
        const openGiveModal = document.getElementById('openGiveModal');
        const assistanceForm = document.getElementById('assistanceForm');
        const allAssistanceDescription = document.getElementById('allAssistanceDescription');
        const giveAssistanceForm = document.getElementById('giveAssistanceForm');
        const giveAssistanceDescription = document.getElementById('giveAssistanceDescription');
        const previewMessage = document.getElementById('previewMessage');

        let previewToastTimer = null;
        function showToast(message, type = 'success') {
            if (!previewMessage) return;
            previewMessage.textContent = message;
            previewMessage.className = 'preview-message preview-' + type + ' show';
            clearTimeout(previewToastTimer);
            previewToastTimer = setTimeout(() => {
                previewMessage.classList.remove('show');
            }, 3500);
        }

        function getFamilyCheckboxes() {
            return document.querySelectorAll('.family-assistance-checkbox');
        }

        function getSelectedCheckboxes() {
            return document.querySelectorAll('.family-assistance-checkbox:checked');
        }

        function updateSelectionUI() {
            const checkboxes = getFamilyCheckboxes();
            const total = checkboxes.length;
            const selectedCount = getSelectedCheckboxes().length;
            const allSelected = total > 0 && selectedCount === total;

            if (selectAllFamiliesBtn) {
                selectAllFamiliesBtn.classList.toggle('selected', allSelected);
                const btnSpan = selectAllFamiliesBtn.querySelector('span');
                if (btnSpan) {
                    btnSpan.textContent = allSelected ? 'All Families Selected' : 'Select All Families';
                }
            }

            if (selectionStatus) {
                if (allSelected) {
                    selectionStatus.textContent = 'All families are selected for this assistance distribution.';
                } else if (selectedCount > 0) {
                    selectionStatus.textContent = `${selectedCount} of ${total} families selected for this assistance distribution.`;
                } else {
                    selectionStatus.textContent = 'Choose families for this assistance distribution.';
                }
            }
        }

        function selectAllFamilies(select = true) {
            const checkboxes = getFamilyCheckboxes();
            checkboxes.forEach(cb => {
                cb.checked = select;
            });
            updateSelectionUI();
        }

        function openSelectAllModal() {
            // Select all families on table
            selectAllFamilies(true);
            if (assistanceModalBackdrop) {
                assistanceModalBackdrop.classList.add('is-open');
                assistanceModalBackdrop.setAttribute('aria-hidden', 'false');
                if (allAssistanceDescription) {
                    allAssistanceDescription.value = '';
                    setTimeout(() => allAssistanceDescription.focus(), 60);
                }
            }
        }

        function closeSelectAllModal() {
            if (assistanceModalBackdrop) {
                assistanceModalBackdrop.classList.remove('is-open');
                assistanceModalBackdrop.setAttribute('aria-hidden', 'true');
            }
        }

        function openGiveFamiliesModal() {
            const selectedCount = getSelectedCheckboxes().length;
            if (selectedCount === 0) {
                alert('Please select at least one family before giving assistance.');
                showToast('Please select at least one family before giving assistance.', 'error');
                return;
            }

            // Close select all modal if open
            closeSelectAllModal();

            // Open Give to Selected Families modal
            if (giveModalBackdrop) {
                giveModalBackdrop.classList.add('is-open');
                giveModalBackdrop.setAttribute('aria-hidden', 'false');
                if (giveAssistanceDescription) {
                    const draftDesc = (allAssistanceDescription ? allAssistanceDescription.value : '').trim();
                    giveAssistanceDescription.value = draftDesc || '';
                    setTimeout(() => giveAssistanceDescription.focus(), 60);
                }
            }
        }

        function closeGiveFamiliesModal() {
            if (giveModalBackdrop) {
                giveModalBackdrop.classList.remove('is-open');
                giveModalBackdrop.setAttribute('aria-hidden', 'true');
            }
        }

        // Assistance menu toggle in first table header
        if (assistanceMenuToggle && assistanceMenu) {
            assistanceMenuToggle.addEventListener('click', function (e) {
                e.stopPropagation();
                const isHidden = assistanceMenu.hidden;
                assistanceMenu.hidden = !isHidden;
                assistanceMenuToggle.setAttribute('aria-expanded', String(isHidden));
            });

            document.addEventListener('click', function (e) {
                if (!assistanceMenu.contains(e.target) && e.target !== assistanceMenuToggle) {
                    assistanceMenu.hidden = true;
                    assistanceMenuToggle.setAttribute('aria-expanded', 'false');
                }
            });
        }

        // Dropdown: "Select all" -> open select-all modal
        if (selectAllAssistanceBtn) {
            selectAllAssistanceBtn.addEventListener('click', function () {
                if (assistanceMenu) {
                    assistanceMenu.hidden = true;
                    assistanceMenuToggle.setAttribute('aria-expanded', 'false');
                }
                openSelectAllModal();
            });
        }

        // Dropdown: "Unselect all"
        if (unselectAllAssistanceBtn) {
            unselectAllAssistanceBtn.addEventListener('click', function () {
                if (assistanceMenu) {
                    assistanceMenu.hidden = true;
                    assistanceMenuToggle.setAttribute('aria-expanded', 'false');
                }
                selectAllFamilies(false);
                showToast('All families unselected.', 'warning');
            });
        }

        // Dropdown: "Give to selected families" -> validate selection then open give modal
        if (selectAssistanceModeBtn) {
            selectAssistanceModeBtn.addEventListener('click', function () {
                if (assistanceMenu) {
                    assistanceMenu.hidden = true;
                    assistanceMenuToggle.setAttribute('aria-expanded', 'false');
                }
                openGiveFamiliesModal();
            });
        }

        // Modal "Select All Families" button inside Select All modal
        if (selectAllFamiliesBtn) {
            selectAllFamiliesBtn.addEventListener('click', function () {
                selectAllFamilies(true);
            });
        }

        // Modal "Unselect All" button inside Select All modal
        if (unselectAllFamiliesBtn) {
            unselectAllFamiliesBtn.addEventListener('click', function () {
                selectAllFamilies(false);
            });
        }

        // "Give to Selected Families" button inside Select All modal (if present)
        if (openGiveModal) {
            openGiveModal.addEventListener('click', function () {
                openGiveFamiliesModal();
            });
        }

        // Close buttons for modals
        document.querySelectorAll('[data-close-modal]').forEach(btn => {
            btn.addEventListener('click', closeSelectAllModal);
        });

        document.querySelectorAll('[data-close-give-modal]').forEach(btn => {
            btn.addEventListener('click', closeGiveFamiliesModal);
        });

        // Close on backdrop click
        if (assistanceModalBackdrop) {
            assistanceModalBackdrop.addEventListener('click', function (e) {
                if (e.target === assistanceModalBackdrop) {
                    closeSelectAllModal();
                }
            });
        }

        if (giveModalBackdrop) {
            giveModalBackdrop.addEventListener('click', function (e) {
                if (e.target === giveModalBackdrop) {
                    closeGiveFamiliesModal();
                }
            });
        }

        // Close modals on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeGiveFamiliesModal();
                closeSelectAllModal();
            }
        });

        // Listen for individual family checkbox changes
        document.addEventListener('change', function (e) {
            if (e.target && e.target.classList.contains('family-assistance-checkbox')) {
                updateSelectionUI();
            }
        });

        // Form submission for Select All Families modal
        if (assistanceForm) {
            assistanceForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const description = (allAssistanceDescription ? allAssistanceDescription.value : '').trim();
                if (!description) {
                    alert('Please enter an assistance description.');
                    return;
                }

                const selectedCheckboxes = getSelectedCheckboxes();
                if (selectedCheckboxes.length === 0) {
                    alert('Please select at least one family before submitting.');
                    showToast('Please select at least one family before submitting.', 'error');
                    return;
                }

                let updatedCount = 0;
                document.querySelectorAll('.registered-family-row').forEach(row => {
                    const cb = row.querySelector('.family-assistance-checkbox');
                    if (cb && cb.checked) {
                        const descInput = row.querySelector('.family-assistance-description');
                        if (descInput) {
                            descInput.value = description;
                            updatedCount++;
                        }
                    }
                });

                // Also sync to main assistance input if blank
                const mainAssistanceInput = document.querySelector('input[name="assistance"]');
                if (mainAssistanceInput && !mainAssistanceInput.value) {
                    mainAssistanceInput.value = description;
                }

                closeSelectAllModal();
                if (allAssistanceDescription) {
                    allAssistanceDescription.value = '';
                }
                showToast(`Assistance "${description}" assigned to ${updatedCount} selected family/families.`, 'success');
            });
        }

        // Form submission for Give to Selected Families modal
        if (giveAssistanceForm) {
            giveAssistanceForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const description = (giveAssistanceDescription ? giveAssistanceDescription.value : '').trim();
                if (!description) {
                    alert('Please enter an assistance description.');
                    return;
                }

                let updatedCount = 0;
                document.querySelectorAll('.registered-family-row').forEach(row => {
                    const cb = row.querySelector('.family-assistance-checkbox');
                    if (cb && cb.checked) {
                        const descInput = row.querySelector('.family-assistance-description');
                        if (descInput) {
                            descInput.value = description;
                            updatedCount++;
                        }
                    }
                });

                // Also sync to main assistance input if blank
                const mainAssistanceInput = document.querySelector('input[name="assistance"]');
                if (mainAssistanceInput && !mainAssistanceInput.value) {
                    mainAssistanceInput.value = description;
                }

                closeGiveFamiliesModal();
                if (giveAssistanceDescription) {
                    giveAssistanceDescription.value = '';
                }
                showToast(`Assistance "${description}" assigned to ${updatedCount} selected family/families.`, 'success');
            });
        }

        familySearch.addEventListener('input', function () {
            const searchTerm = familySearch.value.trim().toLowerCase();
            let visibleCount = 0;

            familyRows.forEach(row => {
                const matches = row.textContent.toLowerCase().includes(searchTerm);
                row.hidden = !matches;
                if (matches) visibleCount++;
            });

            familySearchNoResults.hidden = familyRows.length === 0 || visibleCount > 0;
        });

        closeRecordModal.addEventListener('click', function () {
            recordModal.hidden = true;
        });

        window.addEventListener('afterprint', function () {
            document.body.classList.remove('individual-printing');
            individualPrintArea.innerHTML = '';
        });

        recordModal.addEventListener('click', function (event) {
            if (event.target === recordModal) {
                recordModal.hidden = true;
            }
        });

        const inputNames = [
            'assistance_date',
            'receiving_family_member',
            'emergency_disaster',
            'assistance',
            'unit',
            'quantity',
            'cost',
            'provider',
            'signature'
        ];

        document.getElementById('recordAssistanceButton').addEventListener('click', function () {
            const inputs = inputNames.map(name =>
                document.querySelector(`[name="${name}"]`).value.trim()
            );

            if (inputs.every(value => value === '')) {
                return;
            }

            const recordedBody = document.getElementById('recordedAssistanceBody');
            const emptyRow = document.getElementById('emptyRow');

            if (emptyRow) {
                emptyRow.remove();
            }

            const row = document.createElement('tr');

            [inputs[1], inputs[0]].forEach(value => {
                const cell = document.createElement('td');
                cell.textContent = value || '—';
                row.appendChild(cell);
            });

            const actionsCell = document.createElement('td');
            actionsCell.innerHTML = `
                <div class="record-actions">
                    <button type="button" class="record-action view-record-button">
                        <i class="fas fa-eye"></i> View
                    </button>
                    <button type="button" class="record-action print-record-button">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>`;

            const record = Object.fromEntries(inputNames.map((name, index) => [name, inputs[index] || '—']));

            actionsCell.querySelector('.view-record-button').addEventListener('click', function () {
                recordDetails.innerHTML = inputNames.map(name => `
                    <div class="record-detail">
                        <strong>${name.replaceAll('_', ' ')}</strong>
                        <span>${record[name]}</span>
                    </div>
                `).join('');
                recordModal.hidden = false;
            });

            actionsCell.querySelector('.print-record-button').addEventListener('click', function () {
                individualPrintArea.innerHTML = `
                    <h1>Family Assistance Record</h1>
                    ${inputNames.map(name => `<p><strong>${name.replaceAll('_', ' ')}:</strong> ${record[name]}</p>`).join('')}`;
                document.body.classList.add('individual-printing');
                window.print();
            });

            row.appendChild(actionsCell);
            recordedBody.appendChild(row);

            inputNames.forEach(name => {
                document.querySelector(`[name="${name}"]`).value = '';
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>