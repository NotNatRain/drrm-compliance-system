<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DSWD FACED Dashboard Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap');

        :root {
            --sidebar-bg: #0f172a;
            --accent-blue: #00d2ff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }

        body { background-color: #f1f5f9; color: #1e293b; }

        .dashboard-wrapper { display: flex; width: 100%; min-height: 100vh; }

        .app-sidebar {
            width: 280px; min-height: 100vh;
            background-color: var(--sidebar-bg);
            border-right: 1px solid rgba(255,255,255,0.05);
            display: flex; flex-direction: column; flex-shrink: 0;
        }
        .sidebar-header { padding: 24px; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .sidebar-brand {
            font-family: 'Rajdhani', sans-serif; font-weight: 700;
            font-size: 1.25rem; color: #fff; line-height: 1.2;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .sidebar-brand span { color: var(--accent-blue); display: block; font-size: 0.85rem; font-weight: 500; letter-spacing: 1px; }
        .sidebar-menu { list-style: none; padding: 20px 12px; margin: 0; display: flex; flex-direction: column; gap: 8px; }
        .sidebar-link {
            display: flex; align-items: center; gap: 14px; padding: 12px 16px;
            color: rgba(255,255,255,0.6); text-decoration: none;
            font-family: 'Rajdhani', sans-serif; font-weight: 600;
            font-size: 1.05rem; letter-spacing: 0.5px;
            border-radius: 8px; transition: all 0.2s ease;
        }
        .sidebar-link i { font-size: 1.1rem; width: 20px; text-align: center; }
        .sidebar-link:hover { background: rgba(255,255,255,0.03); color: #fff; }
        .sidebar-link.active { background: rgba(0,210,255,0.1); color: var(--accent-blue); text-shadow: 0 0 10px rgba(0,210,255,0.3); }

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

        .sidebar-submenu { list-style: none; padding: 4px 0 0 34px; margin: 0; }
        .sidebar-submenu a,
        .sidebar-submenu button {
            display: block;
            width: 100%;
            padding: 8px 12px;
            border: 0;
            border-radius: 6px;
            background: transparent;
            color: rgba(255,255,255,0.55);
            text-align: left;
            text-decoration: none;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
        }
        .sidebar-submenu a:hover, .sidebar-submenu button:hover { background: rgba(255,255,255,0.05); color: var(--accent-blue); }
        .sidebar-submenu a.active { color: var(--accent-blue); }

        .content-panel { flex-grow: 1; min-width: 0; min-height: 100vh; overflow-y: auto; padding: 40px 20px; }

        .dashboard-card {
            background: #fff; width: 100%; max-width: 960px; margin: 0 auto;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -4px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0; overflow: hidden;
        }

        .form-header { background: #0f172a; color: #fff; padding: 32px; border-bottom: 4px solid #ef4444; }
        .form-header h1 { font-size: 22px; font-weight: 700; letter-spacing: -0.5px; margin-bottom: 6px; text-transform: uppercase; }
        .form-header p { color: #94a3b8; font-size: 14px; }

        .record-button-container { padding: 20px 32px 0; }
        .record-button {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 11px 18px; border: none; border-radius: 6px;
            background-color: #2563eb; color: #fff; font-size: 14px;
            font-weight: 600; cursor: pointer; text-decoration: none;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }
        .record-button:hover { background-color: #1d4ed8; transform: translateY(-1px); }

        .form-body { padding: 32px; }

        .section-title {
            font-size: 13px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.5px; color: #fff;
            background: #1e293b; padding: 8px 12px;
            margin: 28px 0 16px; border-radius: 4px;
        }
        .section-title:first-of-type { margin-top: 0; }

        .grid-2, .grid-3, .grid-4 { display: grid; gap: 16px; }
        .grid-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-4 { grid-template-columns: repeat(4, 1fr); }
        .grid-6 { display: grid; grid-template-columns: repeat(6, 1fr); gap: 12px; }

        .form-group { display: flex; flex-direction: column; gap: 5px; }
        label { font-size: 12px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.3px; }

        input[type="text"], input[type="date"], input[type="number"], input[type="email"], select, textarea {
            width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1;
            border-radius: 5px; font-size: 13px; outline: none;
            background-color: #f8fafc; transition: all 0.2s ease;
        }
        input:focus, select:focus, textarea:focus {
            background-color: #fff; border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
        }

        .checkbox-row { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; margin-top: 4px; }
        .checkbox-item { display: flex; align-items: center; gap: 6px; font-size: 13px; color: #334155; cursor: pointer; }
        .checkbox-item input[type="checkbox"], .checkbox-item input[type="radio"] { width: auto; }

        /* Family members table in form */
        .family-table-wrapper { overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 6px; margin-top: 4px; }
        .family-table { width: 100%; min-width: 780px; border-collapse: collapse; background: #fff; }
        .family-table th {
            padding: 9px 8px; background: #334155; color: #fff;
            font-size: 11px; font-weight: 600; text-align: center;
            white-space: nowrap; border: 1px solid #475569;
        }
        .family-table td { padding: 4px 4px; border: 1px solid #e2e8f0; }
        .family-table td input, .family-table td select {
            padding: 7px 6px; font-size: 12px; border: none; background: transparent; border-radius: 0;
        }
        .family-table td input:focus, .family-table td select:focus {
            background: #eff6ff; border: none; box-shadow: none;
        }
        .add-row-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 7px 14px; border: 1px dashed #94a3b8;
            border-radius: 5px; background: transparent; color: #64748b;
            font-size: 12px; cursor: pointer; margin-top: 8px;
            transition: all 0.2s;
        }
        .add-row-btn:hover { background: #f1f5f9; border-color: #475569; color: #334155; }

        .privacy-box {
            background-color: #f8fafc; border: 1px dashed #cbd5e1;
            padding: 16px; border-radius: 8px; margin-top: 28px;
        }
        .privacy-box p { font-size: 12px; color: #64748b; line-height: 1.6; margin-bottom: 10px; }

        .form-actions { margin-top: 28px; display: flex; justify-content: flex-end; gap: 14px; }

        .btn { padding: 11px 26px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; border: none; }
        .btn-secondary { background-color: #e2e8f0; color: #475569; }
        .btn-secondary:hover { background-color: #cbd5e1; }
        .btn-primary { background-color: #2563eb; color: #fff; }
        .btn-primary:hover { background-color: #1d4ed8; }

        .records-section { margin-top: 40px; padding-top: 24px; border-top: 1px solid #e2e8f0; }
        .records-header { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 14px; }
        .records-header h2 { color: #334155; font-size: 17px; }

        .print-button {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 14px; border: none; border-radius: 6px;
            background-color: #475569; color: #fff;
            font-size: 13px; font-weight: 600; cursor: pointer;
        }
        .print-button:hover { background-color: #334155; }

        .records-table-wrapper { overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 8px; }
        .records-table { width: 100%; min-width: 700px; border-collapse: collapse; background: #fff; }
        .records-table th {
            padding: 11px 10px; background: #0f172a; color: #fff;
            font-size: 12px; text-align: left; white-space: nowrap;
        }
        .records-table td { padding: 10px; border-top: 1px solid #e2e8f0; color: #475569; font-size: 12px; white-space: nowrap; }
        .records-table .empty-record { color: #64748b; text-align: center; padding: 18px; }

        .record-actions { display: flex; gap: 6px; flex-wrap: wrap; }
        .record-action {
            border: none; border-radius: 5px; padding: 6px 10px;
            color: #fff; font-size: 12px; font-weight: 600; cursor: pointer;
            display: inline-flex; align-items: center; gap: 5px;
            transition: all 0.2s ease;
        }
        .record-action:hover { transform: translateY(-1px); filter: brightness(1.05); }
        .view-record-button { background: #2563eb; }
        .view-record-button:hover { background: #1d4ed8; }
        .edit-record-button { background: #0284c7; }
        .edit-record-button:hover { background: #0369a1; }
        .print-record-button { background: #475569; }
        .print-record-button:hover { background: #334155; }
        .delete-record-button { background: #ef4444; }
        .delete-record-button:hover { background: #dc2626; }

        .modal-section-title {
            grid-column: span 2; font-size: 12px; font-weight: 700;
            text-transform: uppercase; color: #1e293b; letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0; padding-bottom: 4px; margin-top: 14px;
        }
        .modal-section-title:first-child { margin-top: 0; }
        .modal-full-width { grid-column: span 2; }
        .modal-fam-table { width: 100%; border-collapse: collapse; margin-top: 6px; font-size: 12px; }
        .modal-fam-table th { background: #334155; color: #fff; padding: 6px 8px; font-size: 11px; text-align: left; }
        .modal-fam-table td { padding: 6px 8px; border-bottom: 1px solid #e2e8f0; color: #334155; }

        /* Toast notification */
        .dswd-toast {
            position: fixed; top: 24px; right: 24px; z-index: 99999;
            padding: 14px 20px; border-radius: 8px; font-size: 13px; font-weight: 600;
            color: #fff; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2);
            display: flex; align-items: center; gap: 10px; max-width: 420px;
            transition: all 0.3s ease; opacity: 0; transform: translateY(-10px);
            pointer-events: none;
        }
        .dswd-toast.show { opacity: 1; transform: translateY(0); pointer-events: auto; }
        .dswd-toast.toast-success { background-color: #059669; }
        .dswd-toast.toast-error { background-color: #dc2626; }

        .record-modal {
            position: fixed; inset: 0; z-index: 10;
            display: grid; place-items: center;
            padding: 20px; background: rgba(15,23,42,0.55);
        }
        .record-modal[hidden], .individual-print-area { display: none; }
        .record-modal-content {
            width: min(700px, 100%); max-height: 90vh; overflow-y: auto;
            padding: 24px; border-radius: 10px; background: #fff;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2);
        }
        .record-modal-header { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 16px; }
        .record-modal-header h2 { color: #1e293b; font-size: 18px; }
        .close-modal-button { border: none; background: transparent; color: #64748b; font-size: 22px; cursor: pointer; }
        .record-details { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .record-detail { padding: 10px; border-radius: 6px; background: #f8fafc; }
        .record-detail strong { display: block; margin-bottom: 4px; color: #64748b; font-size: 11px; text-transform: uppercase; }
        .record-detail span { color: #1e293b; font-size: 13px; }

        @media (max-width: 900px) {
            .app-sidebar { width: 220px; }
            .grid-3, .grid-4, .grid-6 { grid-template-columns: repeat(2, 1fr); }
        }

        /* ============================================================
           PRINT STYLES — Official DSWD FACED Card Layout
        ============================================================ */
        @media print {
            @page {
                size: landscape;
                margin: 6mm;
            }

            body { background: #fff; }

            .app-sidebar, .form-header, .record-button-container,
            .form-body, .records-section, .records-header,
            .record-actions, .record-modal { display: none !important; }

            .dashboard-wrapper, .content-panel, .dashboard-card {
                display: block; width: 100%; max-width: none;
                min-height: 0; padding: 0; margin: 0;
                border: none; box-shadow: none; overflow: visible;
            }

            .individual-print-area {
                display: block !important;
                width: 100%;
            }

            /* ---- FACED Card Styles ---- */
            .faced-sheet {
                display: flex;
                gap: 6mm;
                width: 100%;
                font-family: Arial, sans-serif;
                font-size: 7.5pt;
                color: #000;
            }

            .faced-copy {
                flex: 1;
                border: 1.5pt solid #000;
                padding: 0;
                page-break-inside: avoid;
            }

            /* Card Header */
            .faced-header {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                padding: 3mm 3mm 2mm;
                border-bottom: 1.5pt solid #000;
                gap: 2mm;
            }
            .faced-header-center { text-align: center; flex: 1; }
            .faced-header-center .republic { font-size: 7pt; }
            .faced-header-center .dept { font-size: 7pt; margin-bottom: 1mm; }
            .faced-header-center .title { font-size: 9.5pt; font-weight: bold; text-transform: uppercase; line-height: 1.2; }
            .faced-logo { font-size: 18pt; font-weight: 900; color: #003087; white-space: nowrap; line-height: 1; }
            .faced-logo small { font-size: 5pt; display: block; color: #000; font-weight: normal; }

            .faced-official-box {
                border: 1pt solid #000;
                font-size: 6.5pt;
                min-width: 30mm;
                text-align: center;
            }
            .faced-official-box .not-for-sale { font-size: 5.5pt; padding: 1mm 2mm; border-bottom: 1pt solid #000; font-style: italic; }
            .faced-official-box .official-use { background: #000; color: #fff; padding: 1.5mm 2mm; font-weight: bold; font-size: 6.5pt; }
            .faced-official-box .copy-type { background: #000; color: #fff; padding: 1mm 2mm; font-size: 6pt; }
            .faced-official-box .serial-row { padding: 1.5mm 2mm; font-size: 6.5pt; text-align: left; }

            /* Section headers */
            .faced-section-head {
                background: #000;
                color: #fff;
                font-weight: bold;
                font-size: 7pt;
                text-align: center;
                padding: 1.5mm;
                text-transform: uppercase;
                letter-spacing: 0.3pt;
            }

            /* Field rows */
            .faced-fields { padding: 1.5mm 2mm; }
            .faced-row { display: flex; gap: 1mm; margin-bottom: 2mm; align-items: flex-end; flex-wrap: wrap; }
            .faced-field { display: flex; flex-direction: column; flex: 1; min-width: 0; }
            .faced-field.w-half { flex: 0.5; }
            .faced-field.w-2 { flex: 2; }
            .faced-field.w-3 { flex: 3; }
            .faced-label { font-size: 5.5pt; color: #000; white-space: nowrap; }
            .faced-value { border-bottom: 0.75pt solid #000; min-height: 4mm; font-size: 7pt; padding-bottom: 0.5mm; word-break: break-word; }

            /* Address sub-labels */
            .faced-addr { display: flex; gap: 1mm; border-top: 0.5pt solid #000; padding-top: 1mm; }
            .faced-addr-field { flex: 1; display: flex; flex-direction: column; }
            .faced-addr-sub { font-size: 4.5pt; border-top: 0.75pt solid #000; text-align: center; }

            /* Sex checkboxes row */
            .faced-sex-row { display: flex; align-items: center; gap: 4mm; flex-wrap: wrap; }
            .faced-check { display: inline-flex; align-items: center; gap: 1mm; font-size: 6.5pt; }
            .faced-box { display: inline-block; width: 2.5mm; height: 2.5mm; border: 0.75pt solid #000; vertical-align: middle; }
            .faced-box.checked { background: #000; }

            /* Others row */
            .faced-others-row { display: flex; align-items: center; gap: 3mm; flex-wrap: wrap; padding: 1mm 2mm; }

            /* Family info table */
            .faced-fam-table { width: 100%; border-collapse: collapse; font-size: 6pt; }
            .faced-fam-table th {
                background: #333; color: #fff;
                border: 0.5pt solid #000;
                padding: 1mm; text-align: center;
                font-size: 5.5pt; line-height: 1.2;
                vertical-align: middle;
            }
            .faced-fam-table td {
                border: 0.5pt solid #000;
                padding: 0.5mm 1mm;
                height: 4.5mm;
                vertical-align: bottom;
                font-size: 6.5pt;
            }

            /* Account, ownership, damage */
            .faced-acct { padding: 1.5mm 2mm; }
            .faced-note { font-size: 5pt; font-style: italic; margin-bottom: 1.5mm; line-height: 1.3; }
            .faced-acct-row { display: flex; gap: 2mm; margin-bottom: 1.5mm; }
            .faced-acct-field { flex: 1; display: flex; align-items: flex-end; gap: 1.5mm; }
            .faced-acct-label { font-size: 5.5pt; white-space: nowrap; }
            .faced-acct-line { flex: 1; border-bottom: 0.75pt solid #000; font-size: 7pt; min-height: 3.5mm; }

            .faced-ownership-damage { display: flex; gap: 2mm; padding: 1.5mm 2mm; border-top: 0.75pt solid #000; }
            .faced-ownership { flex: 1; }
            .faced-damage { flex: 1.5; }
            .faced-sub-head { font-weight: bold; font-size: 6pt; text-decoration: underline; margin-bottom: 1mm; text-transform: uppercase; }
            .faced-checks-row { display: flex; gap: 3mm; flex-wrap: wrap; }

            /* Signature area */
            .faced-sig { display: flex; gap: 2mm; padding: 2mm; border-top: 0.75pt solid #000; }
            .faced-sig-block { flex: 1; border: 0.75pt solid #000; min-height: 12mm; display: flex; flex-direction: column; justify-content: flex-end; padding: 1mm; }
            .faced-sig-label { font-size: 5pt; border-top: 0.5pt solid #000; padding-top: 1mm; text-align: center; margin-top: 1mm; }
            .faced-sig-value { font-size: 7pt; font-style: italic; color: #333; padding: 1mm; }
            .faced-thumb { width: 15mm; border: 0.75pt solid #000; display: flex; flex-direction: column; justify-content: space-between; padding: 1mm; }
            .faced-thumb-label { font-size: 5pt; text-align: center; }

            .faced-sig-bottom { display: flex; gap: 2mm; padding: 0 2mm 1.5mm; }
            .faced-sig-date { flex: 1; display: flex; flex-direction: column; }
            .faced-sig-lswdo { flex: 1; display: flex; flex-direction: column; }
            .faced-sig-line { border-bottom: 0.75pt solid #000; min-height: 5mm; font-size: 7pt; font-style: italic; color: #333; }
            .faced-sig-sub { font-size: 5pt; text-align: center; }

            /* Privacy declaration */
            .faced-privacy { border-top: 0.75pt solid #000; padding: 1.5mm 2mm; }
            .faced-privacy-head { font-size: 6.5pt; font-weight: bold; text-align: center; margin-bottom: 1mm; text-transform: uppercase; }
            .faced-privacy-text { font-size: 5pt; text-align: center; line-height: 1.4; }
        }

        @media (max-width: 768px) {
            .dashboard-wrapper { display: block; }
            .app-sidebar { width: 100%; min-height: auto; }
            .sidebar-header { padding: 18px; }
            .sidebar-menu { display: grid; grid-template-columns: repeat(2, 1fr); padding: 12px; }
            .content-panel { padding: 20px 12px; }
            .record-button-container { padding: 20px 22px 0; }
            .grid-2, .grid-3, .grid-4, .grid-6 { grid-template-columns: 1fr; }
            .form-body, .form-header { padding: 22px; }
        }
    </style>
</head>
<body>
<div class="dashboard-wrapper">
    <aside class="app-sidebar">
        <div class="sidebar-header">
            <div class="sidebar-brand">DSWD FACED <span>Disaster Response Portal</span></div>
        </div>
        <nav>
            <ul class="sidebar-menu">
                <li><a href="{{ route('typhoon.dashboard') }}" class="sidebar-link"><i class="fas fa-th-large"></i><span>Dashboard</span></a></li>
                <li><a href="#" class="sidebar-link"><i class="fas fa-school"></i><span>Evacuation Centers</span></a></li>
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
                        <li><a href="{{ route('typhoon.dswd-form') }}" class="active" aria-current="page"><i class="fas fa-file-alt me-2"></i> DSWD Form</a></li>
                        <li><a href="{{ route('typhoon.family-assistance') }}"><i class="fas fa-hand-holding-heart me-2"></i> Family Assistance</a></li>
                    </ul>
                </li>
                <li><a href="#" class="sidebar-link"><i class="fas fa-box-open"></i><span>Resource Inventory</span></a></li>
                <li><a href="#" class="sidebar-link"><i class="fas fa-file-alt"></i><span>Incident Reports</span></a></li>
            </ul>
        </nav>
    </aside>

    <main class="content-panel">
        <div class="dashboard-card">
            <div class="form-header">
                <h1>Family Assistance Card in Emergencies and Disasters (FACED)</h1>
                <p>DSWD Disaster Response Management System Digitization Portal</p>
            </div>

            <div id="dswdToast" class="dswd-toast"><i class="fas fa-info-circle"></i> <span id="dswdToastMsg"></span></div>

            <form class="form-body" id="facedForm" action="{{ route('typhoon.dswd-form.submit') }}" method="POST">
                @csrf
                <input type="hidden" id="dswd_id" name="dswd_id" value="">

                <!-- Serial / Date -->
                <div class="grid-2" style="background:#f8fafc;padding:14px;border-radius:8px;border:1px solid #e2e8f0;margin-bottom:20px;">
                    <div class="form-group">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2px;">
                            <label for="serial_number" style="margin-bottom:0;">Serial Number</label>
                            <span id="serialStatusBadge" style="font-size:11px;font-weight:600;color:#0284c7;display:none;align-items:center;gap:4px;">
                                <i class="fas fa-check-circle"></i> <span>Family Linked</span>
                            </span>
                        </div>
                        <select id="serial_number" name="serial_number" style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:5px;font-size:13px;background-color:#fff;">
                            <option value="">-- Select Family Serial Number --</option>
                            @if(isset($families))
                                @foreach($families as $fam)
                                    @if(!empty($fam->serial_number))
                                        <option value="{{ $fam->serial_number }}" {{ request('serial_number') == $fam->serial_number ? 'selected' : '' }}>
                                            {{ $fam->serial_number }} &mdash; {{ $fam->head_family_name }} @if(!empty($fam->barangay))({{ $fam->barangay }})@endif
                                        </option>
                                    @endif
                                @endforeach
                            @endif
                            <option value="__custom__">+ Enter Custom / New Serial Number...</option>
                        </select>
                        <input type="text" id="custom_serial_number" placeholder="Enter custom serial number" style="display:none;margin-top:6px;">
                        <small style="color:#64748b;font-size:11px;margin-top:2px;">Select an existing family serial number to auto-fill the form, or select "+ Enter Custom" to input manually.</small>
                    </div>
                    <div class="form-group">
                        <label for="date_registered">Date Registered</label>
                        <input type="date" id="date_registered" name="date_registered">
                    </div>
                </div>

                <!-- 1. Location -->
                <div class="section-title">1. Location of the Affected Family</div>
                <div class="grid-3">
                    <div class="form-group">
                        <label for="region">1. Region</label>
                        <input type="text" id="region" name="region">
                    </div>
                    <div class="form-group">
                        <label for="province">2. Province</label>
                        <input type="text" id="province" name="province">
                    </div>
                    <div class="form-group">
                        <label for="city_municipality">3. City / Municipality</label>
                        <input type="text" id="city_municipality" name="city_municipality">
                    </div>
                    <div class="form-group">
                        <label for="district">4. District</label>
                        <input type="text" id="district" name="district">
                    </div>
                    <div class="form-group">
                        <label for="barangay">5. Barangay</label>
                        <input type="text" id="barangay" name="barangay">
                    </div>
                    <div class="form-group">
                        <label for="evacuation_center">6. Evacuation Center / Site</label>
                        <input type="text" id="evacuation_center" name="evacuation_center" placeholder="If applicable">
                    </div>
                </div>

                <!-- 2. Head of the Family -->
                <div class="section-title">Head of the Family</div>
                <div class="grid-4" style="margin-bottom:14px;">
                    <div class="form-group">
                        <label for="last_name">7. Last Name</label>
                        <input type="text" id="last_name" name="last_name">
                    </div>
                    <div class="form-group">
                        <label for="first_name">8. First Name</label>
                        <input type="text" id="first_name" name="first_name">
                    </div>
                    <div class="form-group">
                        <label for="middle_name">9. Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name">
                    </div>
                    <div class="form-group">
                        <label for="name_ext">10. Name Ext. (Jr., Sr., II)</label>
                        <input type="text" id="name_ext" name="name_ext" placeholder="e.g. Jr.">
                    </div>
                </div>

                <div class="grid-3" style="margin-bottom:14px;">
                    <div class="form-group">
                        <label for="birthdate">11. Birthdate (DD-Month-YYYY)</label>
                        <input type="date" id="birthdate" name="birthdate">
                    </div>
                    <div class="form-group">
                        <label for="age">12. Age</label>
                        <input type="number" id="age" name="age" min="0" max="150">
                    </div>
                    <div class="form-group">
                        <label for="birthplace">13. Birthplace</label>
                        <input type="text" id="birthplace" name="birthplace">
                    </div>
                </div>

                <div class="grid-3" style="margin-bottom:14px;">
                    <div class="form-group">
                        <label>14. Sex</label>
                        <div class="checkbox-row" style="padding:9px 0;">
                            <label class="checkbox-item"><input type="radio" name="gender" value="Male"> Male</label>
                            <label class="checkbox-item"><input type="radio" name="gender" value="Female"> Female</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="civil_status">15. Civil Status</label>
                        <select id="civil_status" name="civil_status">
                            <option value="">Select</option>
                            <option>Single</option><option>Married</option>
                            <option>Widowed</option><option>Separated</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mothers_maiden_name">16. Mother's Maiden Name</label>
                        <input type="text" id="mothers_maiden_name" name="mothers_maiden_name">
                    </div>
                    <div class="form-group">
                        <label for="religion">17. Religion</label>
                        <input type="text" id="religion" name="religion">
                    </div>
                    <div class="form-group">
                        <label for="occupation">18. Occupation</label>
                        <input type="text" id="occupation" name="occupation">
                    </div>
                    <div class="form-group">
                        <label for="household_income">19. Monthly Family Net Income (PHP)</label>
                        <input type="number" id="household_income" name="household_income" min="0">
                    </div>
                    <div class="form-group">
                        <label for="id_card_presented">20. ID Card Presented</label>
                        <input type="text" id="id_card_presented" name="id_card_presented">
                    </div>
                    <div class="form-group">
                        <label for="id_card_number">21. ID Card Number</label>
                        <input type="text" id="id_card_number" name="id_card_number">
                    </div>
                    <div class="form-group">
                        <label for="contact_primary">22. Contact Number – Primary</label>
                        <input type="text" id="contact_primary" name="contact_primary">
                    </div>
                    <div class="form-group">
                        <label for="contact_alternate">22. Contact Number – Alternate</label>
                        <input type="text" id="contact_alternate" name="contact_alternate">
                    </div>
                </div>

                <!-- 23. Permanent Address -->
                <div class="section-title" style="margin-top:14px;">23. Permanent Address</div>
                <div class="grid-6" style="margin-bottom:10px;">
                    <div class="form-group" style="grid-column: span 2;">
                        <label for="addr_house">House/Block/Lot No.</label>
                        <input type="text" id="addr_house" name="addr_house">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label for="addr_street">Street</label>
                        <input type="text" id="addr_street" name="addr_street">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label for="addr_subd">Subd./Village</label>
                        <input type="text" id="addr_subd" name="addr_subd">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label for="addr_barangay">Barangay</label>
                        <input type="text" id="addr_barangay" name="addr_barangay">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label for="addr_city">City/Municipality</label>
                        <input type="text" id="addr_city" name="addr_city">
                    </div>
                    <div class="form-group">
                        <label for="addr_province">Province</label>
                        <input type="text" id="addr_province" name="addr_province">
                    </div>
                    <div class="form-group">
                        <label for="addr_zip">Zip Code</label>
                        <input type="text" id="addr_zip" name="addr_zip">
                    </div>
                </div>

                <!-- 24. Others -->
                <div class="section-title" style="margin-top:14px;">24. Others</div>
                <div class="checkbox-row" style="padding: 8px 0 14px;">
                    <label class="checkbox-item"><input type="checkbox" name="is_4ps" id="is_4ps"> 4Ps Beneficiary</label>
                    <label class="checkbox-item"><input type="checkbox" name="is_ip" id="is_ip"> IP</label>
                    <div class="form-group" style="min-width:220px;">
                        <label for="ip_ethnicity">Type of Ethnicity (if IP)</label>
                        <input type="text" id="ip_ethnicity" name="ip_ethnicity">
                    </div>
                </div>

                <!-- 25. Family Information -->
                <div class="section-title">25. Family Information</div>
                <div class="family-table-wrapper">
                    <table class="family-table" id="familyTable">
                        <thead>
                            <tr>
                                <th style="min-width:110px;">Family Members</th>
                                <th>Relation to Family Head</th>
                                <th>Birthdate</th>
                                <th>Age</th>
                                <th>Sex</th>
                                <th>Highest Educational Attainment</th>
                                <th>Occupation</th>
                                <th>Type of Vulnerability</th>
                            </tr>
                        </thead>
                        <tbody id="familyTableBody">
                            <!-- rows added by JS -->
                        </tbody>
                    </table>
                </div>
                <button type="button" class="add-row-btn" id="addFamilyRow">
                    <i class="fas fa-plus"></i> Add Family Member
                </button>

                <!-- Account Information -->
                <div class="section-title" style="margin-top:20px;">Account Information</div>
                <div class="grid-2" style="margin-bottom:14px;">
                    <div class="form-group">
                        <label for="bank_ewallet">26. Bank/E-Wallet</label>
                        <input type="text" id="bank_ewallet" name="bank_ewallet">
                    </div>
                    <div class="form-group">
                        <label for="account_name">27. Account Name</label>
                        <input type="text" id="account_name" name="account_name">
                    </div>
                    <div class="form-group">
                        <label for="account_type">28. Account Type</label>
                        <input type="text" id="account_type" name="account_type">
                    </div>
                    <div class="form-group">
                        <label for="account_number">29. Account Number</label>
                        <input type="text" id="account_number" name="account_number">
                    </div>
                </div>

                <!-- 30/31. House Ownership / Shelter Damage -->
                <div class="grid-2" style="margin-bottom:14px;">
                    <div class="form-group">
                        <label>30. House Ownership</label>
                        <div class="checkbox-row" style="padding:9px 0;">
                            <label class="checkbox-item"><input type="radio" name="house_ownership" value="Owner"> Owner</label>
                            <label class="checkbox-item"><input type="radio" name="house_ownership" value="Renter"> Renter</label>
                            <label class="checkbox-item"><input type="radio" name="house_ownership" value="Sharer"> Sharer</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>31. Shelter Damage Classification</label>
                        <div class="checkbox-row" style="padding:9px 0;">
                            <label class="checkbox-item"><input type="radio" name="shelter_damage" value="Partially Damaged"> Partially Damaged</label>
                            <label class="checkbox-item"><input type="radio" name="shelter_damage" value="Totally Damaged"> Totally Damaged</label>
                        </div>
                    </div>
                </div>

                <!-- Signatures -->
                <div class="section-title" style="margin-top:14px;">Signatures</div>
                <div class="grid-2" style="margin-bottom:14px;">
                    <div class="form-group">
                        <label for="sig_barangay_captain">Name/Signature of Barangay Captain</label>
                        <input type="text" id="sig_barangay_captain" name="sig_barangay_captain">
                    </div>
                    <div class="form-group">
                        <label for="sig_lswdo">Name/Signature of LSWDO</label>
                        <input type="text" id="sig_lswdo" name="sig_lswdo">
                    </div>
                </div>

                <div class="privacy-box">
                    <p>All data and information indicated herein shall be used for identification purposes for the implementation of disaster risk reduction and management (DRRM) programs, projects, and activities and its disclosure shall be in compliance to Republic Act 10173 (Data Privacy Act of 2012).</p>
                    <label class="checkbox-item">
                        <input type="checkbox" required>
                        I confirm that the information provided is accurate and I consent to data processing.
                    </label>
                </div>

                <div class="form-actions">
                    <button type="reset" class="btn btn-secondary">Clear Form</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save" style="margin-right:6px;"></i>Submit Registration</button>
                </div>
            </form>

            <!-- Records Table -->
            <section class="records-section" style="padding: 0 32px 32px;" aria-labelledby="records-heading">
                <div class="records-header">
                    <h2 id="records-heading">Submitted Family Records</h2>
                </div>
                <div class="records-table-wrapper">
                    <table class="records-table">
                        <thead>
                            <tr>
                                <th>Serial Number</th>
                                <th>Family Head</th>
                                <th>Date Registered</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="recordsBody">
                            @forelse($dswdRecords as $rec)
                                @php
                                    $fullName = implode(' ', array_filter([$rec->first_name, $rec->middle_name, $rec->last_name, $rec->name_ext])) ?: '—';
                                    $location = implode(', ', array_filter([$rec->barangay, $rec->city_municipality, $rec->province, $rec->region])) ?: '—';
                                @endphp
                                <tr id="record-row-{{ $rec->id }}" data-id="{{ $rec->id }}">
                                    <td><strong>{{ $rec->serial_number ?: '—' }}</strong></td>
                                    <td>{{ $fullName }}</td>
                                    <td>{{ $rec->date_registered ? \Carbon\Carbon::parse($rec->date_registered)->format('M d, Y') : '—' }}</td>
                                    <td>{{ $location }}</td>
                                    <td>
                                        <div class="record-actions">
                                            <button type="button" class="record-action view-record-button" data-id="{{ $rec->id }}"><i class="fas fa-eye"></i> View</button>
                                            <button type="button" class="record-action edit-record-button" data-id="{{ $rec->id }}"><i class="fas fa-edit"></i> Edit</button>
                                            <button type="button" class="record-action print-record-button" data-id="{{ $rec->id }}"><i class="fas fa-print"></i> Print</button>
                                            <button type="button" class="record-action delete-record-button" data-id="{{ $rec->id }}" title="Permanently delete this record"><i class="fas fa-trash"></i> Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptyRecordRow">
                                    <td colspan="5" class="empty-record">No submitted family records yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>
</div>

<!-- View Modal -->
<div class="record-modal" id="recordModal" hidden>
    <div class="record-modal-content" role="dialog" aria-modal="true" aria-labelledby="recordModalTitle">
        <div class="record-modal-header">
            <h2 id="recordModalTitle">Family Record</h2>
            <button type="button" class="close-modal-button" id="closeRecordModal" aria-label="Close">&times;</button>
        </div>
        <div class="record-details" id="recordDetails"></div>
    </div>
</div>

<!-- Print Area -->
<div class="individual-print-area" id="individualPrintArea"></div>

<script>
    /* ---- Family Member Rows ---- */
    const famBody = document.getElementById('familyTableBody');
    function createFamilyRow(data = {}) {
        const tr = document.createElement('tr');
        const fields = [
            { name: 'fam_name[]',        type: 'text',   placeholder: 'Full Name' },
            { name: 'fam_relation[]',     type: 'text',   placeholder: 'e.g. Spouse' },
            { name: 'fam_birthdate[]',    type: 'date' },
            { name: 'fam_age[]',          type: 'number', placeholder: '' },
            { name: 'fam_sex[]',          type: 'select', options: ['', 'Male', 'Female'] },
            { name: 'fam_education[]',    type: 'text',   placeholder: 'e.g. College' },
            { name: 'fam_occupation[]',   type: 'text',   placeholder: '' },
            { name: 'fam_vulnerability[]',type: 'text',   placeholder: 'e.g. PWD, Senior' },
        ];
        fields.forEach((f, i) => {
            const td = document.createElement('td');
            let el;
            if (f.type === 'select') {
                el = document.createElement('select');
                f.options.forEach(o => { const op = document.createElement('option'); op.value = o; op.textContent = o || 'Select'; el.appendChild(op); });
            } else {
                el = document.createElement('input');
                el.type = f.type;
                if (f.placeholder) el.placeholder = f.placeholder;
            }
            el.name = f.name;
            el.style.width = '100%';
            if (data[i] !== undefined) el.value = data[i];
            td.appendChild(el);
            tr.appendChild(td);
        });
        // Delete button
        const tdDel = document.createElement('td');
        const delBtn = document.createElement('button');
        delBtn.type = 'button';
        delBtn.innerHTML = '<i class="fas fa-times"></i>';
        delBtn.style.cssText = 'background:#ef4444;color:#fff;border:none;border-radius:3px;padding:4px 7px;cursor:pointer;font-size:11px;';
        delBtn.addEventListener('click', () => tr.remove());
        tdDel.appendChild(delBtn);
        tr.appendChild(tdDel);
        famBody.appendChild(tr);
        return tr;
    }
    // Start with 5 blank rows
    for (let i = 0; i < 5; i++) createFamilyRow();
    document.getElementById('addFamilyRow').addEventListener('click', () => createFamilyRow());

    /* ---- Collect family members data ---- */
    function getFamilyMembers() {
        const rows = famBody.querySelectorAll('tr');
        return Array.from(rows).map(tr => {
            const inputs = tr.querySelectorAll('input, select');
            return {
                name:          inputs[0]?.value.trim() || '',
                relation:      inputs[1]?.value.trim() || '',
                birthdate:     inputs[2]?.value || '',
                age:           inputs[3]?.value || '',
                sex:           inputs[4]?.value || '',
                education:     inputs[5]?.value.trim() || '',
                occupation:    inputs[6]?.value.trim() || '',
                vulnerability: inputs[7]?.value.trim() || '',
            };
        }).filter(r => r.name || r.relation);
    }

    /* ---- Modal ---- */
    const recordModal    = document.getElementById('recordModal');
    const recordDetails  = document.getElementById('recordDetails');
    const closeRecordModal = document.getElementById('closeRecordModal');
    closeRecordModal.addEventListener('click', () => recordModal.hidden = true);
    recordModal.addEventListener('click', e => { if (e.target === recordModal) recordModal.hidden = true; });

    /* ---- Print area ---- */
    const individualPrintArea = document.getElementById('individualPrintArea');
    window.addEventListener('afterprint', () => {
        document.body.classList.remove('individual-printing');
        individualPrintArea.innerHTML = '';
    });

    /* ---- Helper ---- */
    const v = val => (val !== undefined && val !== null) ? val : '';
    const chk = (condition) => `<span class="faced-box ${condition ? 'checked' : ''}"></span>`;

    function getRecordMembers(record) {
        if (!record) return [];
        if (Array.isArray(record.family_members)) return record.family_members;
        if (typeof record.family_members === 'string' && record.family_members.trim() !== '') {
            try {
                const parsed = JSON.parse(record.family_members);
                if (Array.isArray(parsed)) return parsed;
            } catch (e) {}
        }
        if (Array.isArray(record.familyMembers)) return record.familyMembers;
        return [];
    }

    /* ---- Build ONE copy of the FACED card ---- */
    function buildFacedCard(record, copyLabel) {
        const fam = getRecordMembers(record);
        const famRows = fam.length
            ? fam.map(m => `
                <tr>
                    <td>${v(m.name)}</td>
                    <td style="text-align:center;">${v(m.relation)}</td>
                    <td style="text-align:center;">${v(m.birthdate)}</td>
                    <td style="text-align:center;">${v(m.age)}</td>
                    <td style="text-align:center;">${v(m.sex)}</td>
                    <td>${v(m.education)}</td>
                    <td>${v(m.occupation)}</td>
                    <td>${v(m.vulnerability)}</td>
                </tr>`).join('')
            : Array(5).fill('<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>').join('');

        return `
        <div class="faced-copy">
            <!-- Header -->
            <div class="faced-header">
                <div class="faced-logo">DSWD<small>Department of Social Welfare<br>and Development</small></div>
                <div class="faced-header-center">
                    <div class="republic">Republic of the Philippines</div>
                    <div class="dept">Department of Social Welfare and Development</div>
                    <div class="title">Family Assistance Card in<br>Emergencies and Disasters (FACED)</div>
                </div>
                <div class="faced-official-box">
                    <div class="not-for-sale">THIS CARD IS NOT FOR SALE</div>
                    <div class="copy-type">${copyLabel}</div>
                    <div class="official-use">OFFICIAL USE ONLY</div>
                    <div class="serial-row">SERIAL NUMBER: <strong>${v(record.serial_number)}</strong></div>
                </div>
            </div>

            <!-- Location -->
            <div class="faced-section-head">Location of the Affected Family</div>
            <div class="faced-fields">
                <div class="faced-row">
                    <div class="faced-field w-2">
                        <span class="faced-label">1. REGION</span>
                        <div class="faced-value">${v(record.region)}</div>
                    </div>
                    <div class="faced-field w-2">
                        <span class="faced-label">4. DISTRICT</span>
                        <div class="faced-value">${v(record.district)}</div>
                    </div>
                </div>
                <div class="faced-row">
                    <div class="faced-field w-2">
                        <span class="faced-label">2. PROVINCE</span>
                        <div class="faced-value">${v(record.province)}</div>
                    </div>
                    <div class="faced-field w-2">
                        <span class="faced-label">5. BARANGAY</span>
                        <div class="faced-value">${v(record.barangay)}</div>
                    </div>
                </div>
                <div class="faced-row">
                    <div class="faced-field w-2">
                        <span class="faced-label">3. CITY/MUNICIPALITY</span>
                        <div class="faced-value">${v(record.city_municipality)}</div>
                    </div>
                    <div class="faced-field w-2">
                        <span class="faced-label">6. EVACUATION CENTER/SITE</span>
                        <div class="faced-value">${v(record.evacuation_center)}</div>
                    </div>
                </div>
            </div>

            <!-- Head of Family -->
            <div class="faced-section-head">Head of the Family</div>
            <div class="faced-fields">
                <div class="faced-row">
                    <div class="faced-field w-3">
                        <span class="faced-label">7. LAST NAME</span>
                        <div class="faced-value">${v(record.last_name)}</div>
                    </div>
                    <div class="faced-field w-2">
                        <span class="faced-label">15. CIVIL STATUS</span>
                        <div class="faced-value">${v(record.civil_status)}</div>
                    </div>
                </div>
                <div class="faced-row">
                    <div class="faced-field w-3">
                        <span class="faced-label">8. FIRST NAME</span>
                        <div class="faced-value">${v(record.first_name)}</div>
                    </div>
                    <div class="faced-field w-2">
                        <span class="faced-label">16. MOTHER'S MAIDEN NAME</span>
                        <div class="faced-value">${v(record.mothers_maiden_name)}</div>
                    </div>
                </div>
                <div class="faced-row">
                    <div class="faced-field w-3">
                        <span class="faced-label">9. MIDDLE NAME</span>
                        <div class="faced-value">${v(record.middle_name)}</div>
                    </div>
                    <div class="faced-field w-2">
                        <span class="faced-label">17. RELIGION</span>
                        <div class="faced-value">${v(record.religion)}</div>
                    </div>
                </div>
                <div class="faced-row">
                    <div class="faced-field w-half">
                        <span class="faced-label">10. NAME EXT. (Jr., Sr., II)</span>
                        <div class="faced-value">${v(record.name_ext)}</div>
                    </div>
                    <div class="faced-field w-2">
                        <span class="faced-label">18. OCCUPATION</span>
                        <div class="faced-value">${v(record.occupation)}</div>
                    </div>
                </div>
                <div class="faced-row">
                    <div class="faced-field">
                        <span class="faced-label">11. BIRTHDATE (DD-Month-YYYY)</span>
                        <div class="faced-value">${v(record.birthdate)}</div>
                    </div>
                    <div class="faced-field">
                        <span class="faced-label">19. MONTHLY FAMILY NET INCOME</span>
                        <div class="faced-value">${record.household_income ? '₱ ' + Number(record.household_income).toLocaleString() : ''}</div>
                    </div>
                </div>
                <div class="faced-row">
                    <div class="faced-field w-half">
                        <span class="faced-label">12. AGE</span>
                        <div class="faced-value">${v(record.age)}</div>
                    </div>
                    <div class="faced-field w-2">
                        <span class="faced-label">20. ID CARD PRESENTED</span>
                        <div class="faced-value">${v(record.id_card_presented)}</div>
                    </div>
                </div>
                <div class="faced-row">
                    <div class="faced-field">
                        <span class="faced-label">13. BIRTHPLACE</span>
                        <div class="faced-value">${v(record.birthplace)}</div>
                    </div>
                    <div class="faced-field">
                        <span class="faced-label">21. ID CARD NUMBER</span>
                        <div class="faced-value">${v(record.id_card_number)}</div>
                    </div>
                </div>
                <div class="faced-row">
                    <div class="faced-field">
                        <span class="faced-label">14. SEX</span>
                        <div class="faced-sex-row" style="padding:1mm 0;">
                            ${chk(record.gender === 'Male')} <span style="font-size:6.5pt;">MALE</span>
                            &nbsp;&nbsp;
                            ${chk(record.gender === 'Female')} <span style="font-size:6.5pt;">FEMALE</span>
                        </div>
                    </div>
                    <div class="faced-field">
                        <span class="faced-label">22. CONTACT NUMBER</span>
                        <div style="display:flex;gap:3mm;">
                            <div style="flex:1;"><div style="font-size:5pt;">PRIMARY</div><div class="faced-value">${v(record.contact_primary || record.contact_number)}</div></div>
                            <div style="flex:1;"><div style="font-size:5pt;">ALTERNATE</div><div class="faced-value">${v(record.contact_alternate || record.phone_number)}</div></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permanent Address -->
            <div class="faced-fields" style="padding-top:0;">
                <div class="faced-label" style="font-weight:bold;">23. PERMANENT ADDRESS</div>
                <div class="faced-addr" style="margin-top:1mm;">
                    <div class="faced-addr-field">
                        <div class="faced-value">${v(record.addr_house)}</div>
                        <div class="faced-addr-sub">House/Block/Lot No.</div>
                    </div>
                    <div class="faced-addr-field">
                        <div class="faced-value">${v(record.addr_street)}</div>
                        <div class="faced-addr-sub">Street</div>
                    </div>
                    <div class="faced-addr-field">
                        <div class="faced-value">${v(record.addr_subd)}</div>
                        <div class="faced-addr-sub">Subd./Village</div>
                    </div>
                    <div class="faced-addr-field">
                        <div class="faced-value">${v(record.addr_barangay)}</div>
                        <div class="faced-addr-sub">Barangay</div>
                    </div>
                    <div class="faced-addr-field">
                        <div class="faced-value">${v(record.addr_city)}</div>
                        <div class="faced-addr-sub">City/Municipality</div>
                    </div>
                    <div class="faced-addr-field" style="flex:0.6;">
                        <div class="faced-value">${v(record.addr_province)}</div>
                        <div class="faced-addr-sub">Province</div>
                    </div>
                    <div class="faced-addr-field" style="flex:0.4;">
                        <div class="faced-value">${v(record.addr_zip)}</div>
                        <div class="faced-addr-sub">Zip Code</div>
                    </div>
                </div>
            </div>

            <!-- Others -->
            <div class="faced-others-row">
                <span style="font-size:6.5pt;font-weight:bold;">24. OTHERS</span>
                ${chk(record.is_4ps)} <span style="font-size:6.5pt;">4Ps Beneficiary</span>
                &nbsp;&nbsp;
                ${chk(record.is_ip)} <span style="font-size:6.5pt;">IP (Type of Ethnicity: ${v(record.ip_ethnicity)})</span>
            </div>

            <!-- 25. Family Information -->
            <div class="faced-section-head">25. Family Information</div>
            <table class="faced-fam-table">
                <thead>
                    <tr>
                        <th style="width:18%;">FAMILY MEMBERS</th>
                        <th>RELATION TO FAMILY HEAD</th>
                        <th>BIRTHDATE</th>
                        <th>AGE</th>
                        <th>SEX</th>
                        <th>HIGHEST EDUCATIONAL ATTAINMENT</th>
                        <th>OCCUPATION</th>
                        <th>TYPE OF VULNERABILITY</th>
                    </tr>
                </thead>
                <tbody>${famRows}</tbody>
            </table>

            <!-- Account Information -->
            <div class="faced-section-head">Account Information</div>
            <div class="faced-acct">
                <div class="faced-note">Note: In case the family head does not have a bank or an e-wallet account, any of the family members with a validated account can be indicated.</div>
                <div class="faced-acct-row">
                    <div class="faced-acct-field">
                        <span class="faced-acct-label">26. BANK/E-WALLET</span>
                        <div class="faced-acct-line">${v(record.bank_ewallet)}</div>
                    </div>
                    <div class="faced-acct-field">
                        <span class="faced-acct-label">28. ACCOUNT TYPE</span>
                        <div class="faced-acct-line">${v(record.account_type)}</div>
                    </div>
                </div>
                <div class="faced-acct-row">
                    <div class="faced-acct-field">
                        <span class="faced-acct-label">27. ACCOUNT NAME</span>
                        <div class="faced-acct-line">${v(record.account_name)}</div>
                    </div>
                    <div class="faced-acct-field">
                        <span class="faced-acct-label">29. ACCOUNT NUMBER</span>
                        <div class="faced-acct-line">${v(record.account_number)}</div>
                    </div>
                </div>
            </div>

            <!-- House Ownership / Shelter Damage -->
            <div class="faced-ownership-damage">
                <div class="faced-ownership">
                    <div class="faced-sub-head">30. House Ownership</div>
                    <div class="faced-checks-row">
                        ${chk(record.house_ownership === 'Owner')} <span style="font-size:6.5pt;">OWNER</span>
                        ${chk(record.house_ownership === 'Renter')} <span style="font-size:6.5pt;">RENTER</span>
                        ${chk(record.house_ownership === 'Sharer')} <span style="font-size:6.5pt;">SHARER</span>
                    </div>
                </div>
                <div class="faced-damage">
                    <div class="faced-sub-head">31. Shelter Damage Classification</div>
                    <div class="faced-checks-row">
                        ${chk(record.shelter_damage === 'Partially Damaged')} <span style="font-size:6.5pt;">PARTIALLY DAMAGED</span>
                        ${chk(record.shelter_damage === 'Totally Damaged')} <span style="font-size:6.5pt;">TOTALLY DAMAGED</span>
                    </div>
                </div>
            </div>

            <!-- Signatures -->
            <div class="faced-sig">
                <div class="faced-thumb">
                    <div style="flex:1;"></div>
                    <div class="faced-thumb-label">Right Thumbmark</div>
                </div>
                <div class="faced-sig-block">
                    <div class="faced-sig-value"></div>
                    <div class="faced-sig-label">Signature/Thumbmark of Family Head</div>
                </div>
                <div class="faced-sig-block">
                    <div class="faced-sig-value">${v(record.sig_barangay_captain)}</div>
                    <div class="faced-sig-label">Name/Signature of Barangay Captain</div>
                </div>
            </div>
            <div class="faced-sig-bottom" style="padding: 1.5mm 2mm;">
                <div style="flex:0.5;"></div>
                <div class="faced-sig-date" style="flex:1;">
                    <div class="faced-sig-line">${v(record.date_registered)}</div>
                    <div class="faced-sig-sub">Date Registered</div>
                </div>
                <div class="faced-sig-lswdo" style="flex:1;">
                    <div class="faced-sig-line">${v(record.sig_lswdo)}</div>
                    <div class="faced-sig-sub">Name/Signature of LSWDO</div>
                </div>
            </div>

            <!-- Data Privacy Declaration -->
            <div class="faced-privacy">
                <div class="faced-privacy-head">32. Data Privacy Declaration</div>
                <div class="faced-privacy-text">All data and information indicated herein shall be used for identification purposes for the implementation of disaster risk reduction and management (DRRM) programs, projects, and activities and its disclosure shall be in compliance to Republic Act 10173 (Data Privacy Act of 2012).</div>
            </div>
        </div>`;
    }

    /* ---- Permanent Records & State ---- */
    const initialDswdRecords = @json($dswdRecords);
    const familiesData = @json($familiesData ?? []);
    const dswdRecordsMap = new Map();

    initialDswdRecords.forEach(r => {
        if (typeof r.family_members === 'string') {
            try { r.family_members = JSON.parse(r.family_members); } catch (e) { r.family_members = []; }
        }
        dswdRecordsMap.set(Number(r.id), r);
    });

    const registrationForm   = document.getElementById('facedForm');
    const recordsBody        = document.getElementById('recordsBody');
    const serialSelect       = document.getElementById('serial_number');
    const customSerialInput  = document.getElementById('custom_serial_number');
    const serialStatusBadge  = document.getElementById('serialStatusBadge');
    const dswdIdInput        = document.getElementById('dswd_id');

    /* ---- Toast Notification ---- */
    let toastTimeout;
    function showToast(msg, type = 'success') {
        const toast = document.getElementById('dswdToast');
        const toastMsg = document.getElementById('dswdToastMsg');
        if (!toast || !toastMsg) return;
        clearTimeout(toastTimeout);
        toast.className = 'dswd-toast show ' + (type === 'success' ? 'toast-success' : 'toast-error');
        const icon = toast.querySelector('i');
        if (icon) icon.className = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
        toastMsg.innerHTML = msg;
        toastTimeout = setTimeout(() => {
            toast.classList.remove('show');
        }, 4500);
    }

    /* ---- Populate Form with Record or Family Data ---- */
    function populateForm(data) {
        if (!data) return;

        // Hidden ID
        dswdIdInput.value = data.id && dswdRecordsMap.has(Number(data.id)) ? data.id : (data.dswd_id || '');

        // Serial Number
        const serial = data.serial_number || '';
        let optionExists = false;
        if (serialSelect) {
            for (let i = 0; i < serialSelect.options.length; i++) {
                if (serialSelect.options[i].value === serial) {
                    serialSelect.selectedIndex = i;
                    optionExists = true;
                    break;
                }
            }
            if (!optionExists && serial) {
                serialSelect.value = '__custom__';
                if (customSerialInput) {
                    customSerialInput.style.display = 'block';
                    customSerialInput.value = serial;
                }
            } else if (optionExists && customSerialInput) {
                customSerialInput.style.display = 'none';
                customSerialInput.value = '';
            }
        }

        // Location & Registration
        if (data.date_registered) document.getElementById('date_registered').value = String(data.date_registered).substring(0, 10);
        document.getElementById('region').value = data.region || '';
        document.getElementById('province').value = data.province || '';
        document.getElementById('city_municipality').value = data.city_municipality || '';
        document.getElementById('district').value = data.district || '';
        document.getElementById('barangay').value = data.barangay || '';
        document.getElementById('evacuation_center').value = data.evacuation_center || '';

        // Head of Family
        document.getElementById('last_name').value = data.last_name || '';
        document.getElementById('first_name').value = data.first_name || '';
        document.getElementById('middle_name').value = data.middle_name || '';
        document.getElementById('name_ext').value = data.name_ext || '';
        if (data.birthdate) document.getElementById('birthdate').value = String(data.birthdate).substring(0, 10);
        document.getElementById('age').value = (data.age !== undefined && data.age !== null) ? data.age : '';
        document.getElementById('birthplace').value = data.birthplace || '';

        // Sex radio
        const genderVal = (data.gender || '').toLowerCase();
        document.querySelectorAll('input[name="gender"]').forEach(r => {
            r.checked = (r.value.toLowerCase() === genderVal);
        });

        // Civil Status, Maiden Name, Religion, Occupation, Income, ID
        document.getElementById('civil_status').value = data.civil_status || '';
        document.getElementById('mothers_maiden_name').value = data.mothers_maiden_name || '';
        document.getElementById('religion').value = data.religion || '';
        document.getElementById('occupation').value = data.occupation || '';
        document.getElementById('household_income').value = data.household_income || '';
        document.getElementById('id_card_presented').value = data.id_card_presented || '';
        document.getElementById('id_card_number').value = data.id_card_number || '';
        document.getElementById('contact_primary').value = data.contact_primary || data.contact_number || '';
        document.getElementById('contact_alternate').value = data.contact_alternate || data.phone_number || '';

        // Permanent Address
        document.getElementById('addr_house').value = data.addr_house || '';
        document.getElementById('addr_street').value = data.addr_street || '';
        document.getElementById('addr_subd').value = data.addr_subd || '';
        document.getElementById('addr_barangay').value = data.addr_barangay || '';
        document.getElementById('addr_city').value = data.addr_city || '';
        document.getElementById('addr_province').value = data.addr_province || '';
        document.getElementById('addr_zip').value = data.addr_zip || '';

        // Others
        document.getElementById('is_4ps').checked = Boolean(data.is_4ps == 1 || data.is_4ps === true);
        document.getElementById('is_ip').checked = Boolean(data.is_ip == 1 || data.is_ip === true);
        document.getElementById('ip_ethnicity').value = data.ip_ethnicity || '';

        // Family Members Table
        famBody.innerHTML = '';
        const members = getRecordMembers(data);
        if (members && members.length > 0) {
            members.forEach(m => {
                createFamilyRow([
                    m.name || '',
                    m.relation || '',
                    m.birthdate ? String(m.birthdate).substring(0, 10) : '',
                    m.age !== undefined ? m.age : '',
                    m.sex || '',
                    m.education || '',
                    m.occupation || '',
                    m.vulnerability || ''
                ]);
            });
            const remaining = 5 - members.length;
            for (let i = 0; i < remaining; i++) createFamilyRow();
        } else {
            for (let i = 0; i < 5; i++) createFamilyRow();
        }

        // Account Information
        document.getElementById('bank_ewallet').value = data.bank_ewallet || '';
        document.getElementById('account_name').value = data.account_name || '';
        document.getElementById('account_type').value = data.account_type || '';
        document.getElementById('account_number').value = data.account_number || '';

        // House Ownership
        const houseVal = (data.house_ownership || '').toLowerCase();
        document.querySelectorAll('input[name="house_ownership"]').forEach(r => {
            r.checked = (r.value.toLowerCase() === houseVal);
        });

        // Shelter Damage
        const dmgVal = (data.shelter_damage || '').toLowerCase();
        document.querySelectorAll('input[name="shelter_damage"]').forEach(r => {
            r.checked = (r.value.toLowerCase() === dmgVal);
        });

        // Signatures
        document.getElementById('sig_barangay_captain').value = data.sig_barangay_captain || '';
        document.getElementById('sig_lswdo').value = data.sig_lswdo || '';

        // Badge
        if (serialStatusBadge && serial) {
            serialStatusBadge.style.display = 'inline-flex';
            serialStatusBadge.querySelector('span').textContent = dswdIdInput.value ? 'Saved DSWD Linked' : 'Family Linked';
        }
    }

    /* ---- Reset Form ---- */
    function resetForm() {
        registrationForm.reset();
        dswdIdInput.value = '';
        if (customSerialInput) {
            customSerialInput.style.display = 'none';
            customSerialInput.value = '';
        }
        if (serialStatusBadge) serialStatusBadge.style.display = 'none';
        famBody.innerHTML = '';
        for (let i = 0; i < 5; i++) createFamilyRow();
    }

    registrationForm.addEventListener('reset', function () {
        setTimeout(resetForm, 10);
    });

    /* ---- Serial Number Change Event ---- */
    if (serialSelect) {
        serialSelect.addEventListener('change', function () {
            const val = this.value;
            if (val === '__custom__') {
                if (customSerialInput) {
                    customSerialInput.style.display = 'block';
                    customSerialInput.focus();
                }
                if (serialStatusBadge) serialStatusBadge.style.display = 'none';
                dswdIdInput.value = '';
                return;
            }
            if (customSerialInput) customSerialInput.style.display = 'none';
            if (!val) {
                resetForm();
                return;
            }

            // Look in saved DSWD records map first
            let foundDswd = null;
            for (const r of dswdRecordsMap.values()) {
                if (r.serial_number === val) {
                    foundDswd = r;
                    break;
                }
            }

            if (foundDswd) {
                populateForm(foundDswd);
                showToast(`Loaded saved DSWD record for ${val}.`, 'success');
            } else if (familiesData && familiesData[val]) {
                populateForm(familiesData[val]);
                showToast(`Loaded family details for ${val}.`, 'success');
            } else {
                fetch(`{{ url('typhoon/dswd-form/family') }}/${encodeURIComponent(val)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (!data.error) {
                            populateForm(data);
                            showToast(`Loaded family details for ${val}.`, 'success');
                        }
                    })
                    .catch(err => console.error(err));
            }
        });
    }

    /* ---- Delete DSWD Record ---- */
    async function deleteRecord(id) {
        const record = dswdRecordsMap.get(Number(id));
        const headName = record ? [record.first_name, record.last_name].filter(Boolean).join(' ') : 'this record';
        const serial = record?.serial_number || '—';

        if (!confirm(`Are you sure you want to permanently delete the DSWD record for "${headName}" (Serial: ${serial})?\n\nThis will permanently remove it from the system.`)) {
            return;
        }

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const res = await fetch(`{{ url('typhoon/dswd-form') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const data = await res.json();
            if (res.ok && data.success) {
                // Remove row from table
                const row = document.getElementById(`record-row-${id}`);
                if (row) row.remove();
                dswdRecordsMap.delete(Number(id));

                // If table is now empty, add empty row
                const rows = recordsBody.querySelectorAll('tr[id^="record-row-"]');
                if (rows.length === 0) {
                    recordsBody.innerHTML = `
                        <tr id="emptyRecordRow">
                            <td colspan="5" class="empty-record">No submitted family records yet.</td>
                        </tr>`;
                }

                // If currently editing this record, reset form
                if (dswdIdInput.value == id) {
                    resetForm();
                }

                showToast('DSWD record permanently deleted.', 'success');
            } else {
                showToast(data.message || 'Failed to delete DSWD record.', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('An error occurred while deleting the record.', 'error');
        }
    }

    /* ---- Render or Update Table Row ---- */
    function renderOrUpdateTableRow(record) {
        const emptyRow = document.getElementById('emptyRecordRow');
        if (emptyRow) emptyRow.remove();

        const fullName = [record.first_name, record.middle_name, record.last_name, record.name_ext]
            .filter(Boolean).join(' ') || '—';
        const location = [record.barangay, record.city_municipality, record.province, record.region]
            .filter(Boolean).join(', ') || '—';
        const dateStr = record.date_registered ? String(record.date_registered).substring(0, 10) : '—';

        let row = document.getElementById(`record-row-${record.id}`);
        const isNew = !row;
        if (isNew) {
            row = document.createElement('tr');
            row.id = `record-row-${record.id}`;
            row.setAttribute('data-id', record.id);
        }

        row.innerHTML = `
            <td><strong>${record.serial_number || '—'}</strong></td>
            <td>${fullName}</td>
            <td>${dateStr}</td>
            <td>${location}</td>
            <td>
                <div class="record-actions">
                    <button type="button" class="record-action view-record-button" data-id="${record.id}"><i class="fas fa-eye"></i> View</button>
                    <button type="button" class="record-action edit-record-button" data-id="${record.id}"><i class="fas fa-edit"></i> Edit</button>
                    <button type="button" class="record-action print-record-button" data-id="${record.id}"><i class="fas fa-print"></i> Print</button>
                    <button type="button" class="record-action delete-record-button" data-id="${record.id}" title="Permanently delete this record"><i class="fas fa-trash"></i> Delete</button>
                </div>
            </td>`;

        attachRowEvents(row, record.id);

        if (isNew) {
            recordsBody.insertBefore(row, recordsBody.firstChild);
        }
    }

    /* ---- Attach Events to a Table Row ---- */
    function attachRowEvents(row, id) {
        // View Button
        row.querySelector('.view-record-button')?.addEventListener('click', () => {
            const record = dswdRecordsMap.get(Number(id));
            if (!record) return;

            const fullName = [record.first_name, record.middle_name, record.last_name, record.name_ext].filter(Boolean).join(' ') || '—';
            const location = [record.barangay, record.city_municipality, record.province, record.region].filter(Boolean).join(', ') || '—';
            const members = getRecordMembers(record);

            let membersHtml = '';
            if (members.length > 0) {
                membersHtml = `
                    <div class="modal-full-width">
                        <table class="modal-fam-table">
                            <thead>
                                <tr>
                                    <th>Member Name</th>
                                    <th>Relation</th>
                                    <th>Birthdate</th>
                                    <th>Age</th>
                                    <th>Sex</th>
                                    <th>Education</th>
                                    <th>Occupation</th>
                                    <th>Vulnerability</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${members.map(m => `
                                    <tr>
                                        <td><strong>${v(m.name)}</strong></td>
                                        <td>${v(m.relation)}</td>
                                        <td>${v(m.birthdate)}</td>
                                        <td>${v(m.age)}</td>
                                        <td>${v(m.sex)}</td>
                                        <td>${v(m.education)}</td>
                                        <td>${v(m.occupation)}</td>
                                        <td>${v(m.vulnerability)}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>`;
            } else {
                membersHtml = `<div class="modal-full-width" style="color:#64748b;font-size:12px;padding:6px 0;">No family members recorded.</div>`;
            }

            const permanentAddr = [record.addr_house, record.addr_street, record.addr_subd, record.addr_barangay, record.addr_city, record.addr_province, record.addr_zip].filter(Boolean).join(', ') || '—';

            recordDetails.innerHTML = `
                <div class="modal-section-title">1. Location &amp; Registration</div>
                <div class="record-detail"><strong>Serial Number</strong><span>${v(record.serial_number) || '—'}</span></div>
                <div class="record-detail"><strong>Date Registered</strong><span>${v(record.date_registered) || '—'}</span></div>
                <div class="record-detail modal-full-width"><strong>Evacuation Center</strong><span>${v(record.evacuation_center) || '—'}</span></div>
                <div class="record-detail modal-full-width"><strong>Location</strong><span>${location}</span></div>

                <div class="modal-section-title">2. Head of Family</div>
                <div class="record-detail"><strong>Family Head</strong><span>${fullName}</span></div>
                <div class="record-detail"><strong>Sex &amp; Civil Status</strong><span>${[record.gender, record.civil_status].filter(Boolean).join(' / ') || '—'}</span></div>
                <div class="record-detail"><strong>Birthdate &amp; Age</strong><span>${[record.birthdate, record.age ? record.age + ' yrs old' : null].filter(Boolean).join(' — ') || '—'}</span></div>
                <div class="record-detail"><strong>Birthplace</strong><span>${v(record.birthplace) || '—'}</span></div>
                <div class="record-detail"><strong>Mother's Maiden Name</strong><span>${v(record.mothers_maiden_name) || '—'}</span></div>
                <div class="record-detail"><strong>Religion</strong><span>${v(record.religion) || '—'}</span></div>
                <div class="record-detail"><strong>Occupation</strong><span>${v(record.occupation) || '—'}</span></div>
                <div class="record-detail"><strong>Monthly Income</strong><span>${record.household_income ? '₱ ' + Number(record.household_income).toLocaleString() : '—'}</span></div>
                <div class="record-detail"><strong>ID Presented / No.</strong><span>${[record.id_card_presented, record.id_card_number].filter(Boolean).join(' - ') || '—'}</span></div>
                <div class="record-detail"><strong>Contacts</strong><span>${[record.contact_primary || record.contact_number, record.contact_alternate || record.phone_number].filter(Boolean).join(' / ') || '—'}</span></div>

                <div class="modal-section-title">3. Permanent Address</div>
                <div class="record-detail modal-full-width"><strong>Permanent Address</strong><span>${permanentAddr}</span></div>

                <div class="modal-section-title">4. Others (Special Status)</div>
                <div class="record-detail"><strong>4Ps Beneficiary</strong><span>${record.is_4ps ? 'Yes' : 'No'}</span></div>
                <div class="record-detail"><strong>Indigenous People (IP)</strong><span>${record.is_ip ? ('Yes (' + (record.ip_ethnicity || 'Not specified') + ')') : 'No'}</span></div>

                <div class="modal-section-title">5. Family Members (${members.length})</div>
                ${membersHtml}

                <div class="modal-section-title">6. Financial &amp; Housing</div>
                <div class="record-detail"><strong>Bank / E-Wallet</strong><span>${[record.bank_ewallet, record.account_type].filter(Boolean).join(' - ') || '—'}</span></div>
                <div class="record-detail"><strong>Account Name / No.</strong><span>${[record.account_name, record.account_number].filter(Boolean).join(' - ') || '—'}</span></div>
                <div class="record-detail"><strong>House Ownership</strong><span>${v(record.house_ownership) || '—'}</span></div>
                <div class="record-detail"><strong>Shelter Damage</strong><span>${v(record.shelter_damage) || '—'}</span></div>

                <div class="modal-section-title">7. Signatures</div>
                <div class="record-detail"><strong>Barangay Captain</strong><span>${v(record.sig_barangay_captain) || '—'}</span></div>
                <div class="record-detail"><strong>LSWDO</strong><span>${v(record.sig_lswdo) || '—'}</span></div>
            `;
            recordModal.hidden = false;
        });

        // Edit Button
        row.querySelector('.edit-record-button')?.addEventListener('click', () => {
            const record = dswdRecordsMap.get(Number(id));
            if (!record) return;
            populateForm(record);
            document.querySelector('.dashboard-card').scrollIntoView({ behavior: 'smooth' });
            showToast(`Loaded record for ${[record.first_name, record.last_name].filter(Boolean).join(' ')}. You can edit and save changes.`, 'success');
        });

        // Print Button
        row.querySelector('.print-record-button')?.addEventListener('click', () => {
            const record = dswdRecordsMap.get(Number(id));
            if (!record) return;
            individualPrintArea.innerHTML = `
                <div class="faced-sheet">
                    ${buildFacedCard(record, "BENEFICIARY'S COPY")}
                    ${buildFacedCard(record, "SOCIAL WORKER'S COPY")}
                </div>`;
            document.body.classList.add('individual-printing');
            window.print();
        });

        // Delete Button
        row.querySelector('.delete-record-button')?.addEventListener('click', () => {
            deleteRecord(id);
        });
    }

    // Attach row events to all server-rendered rows
    document.querySelectorAll('#recordsBody tr[data-id]').forEach(tr => {
        const id = tr.getAttribute('data-id');
        if (id) attachRowEvents(tr, id);
    });

    /* ---- Submit Form Handler ---- */
    registrationForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        const submitBtn = registrationForm.querySelector('button[type="submit"]');
        const originalBtnHtml = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right:6px;"></i>Saving...';

        try {
            let serialVal = document.getElementById('serial_number').value;
            if (serialVal === '__custom__' && customSerialInput) {
                serialVal = customSerialInput.value.trim();
            }

            const fd = new FormData(registrationForm);
            fd.set('serial_number', serialVal);

            // Add family members as JSON string
            const members = getFamilyMembers();
            fd.set('family_members', JSON.stringify(members));

            // Ensure checkboxes send boolean-compatible values
            fd.set('is_4ps', document.getElementById('is_4ps').checked ? '1' : '0');
            fd.set('is_ip', document.getElementById('is_ip').checked ? '1' : '0');

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const res = await fetch('{{ route('typhoon.dswd-form.submit') }}', {
                method: 'POST',
                body: fd,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                }
            });

            const data = await res.json();
            if (res.ok && data.success) {
                const savedRec = data.record;
                if (typeof savedRec.family_members === 'string') {
                    try { savedRec.family_members = JSON.parse(savedRec.family_members); } catch (e) { savedRec.family_members = []; }
                }

                // Update Map
                dswdRecordsMap.set(Number(savedRec.id), savedRec);

                // Update or Insert Table Row
                renderOrUpdateTableRow(savedRec);

                // Update hidden ID
                dswdIdInput.value = savedRec.id;

                if (serialStatusBadge) {
                    serialStatusBadge.style.display = 'inline-flex';
                    serialStatusBadge.querySelector('span').textContent = 'Saved DSWD Linked';
                }

                showToast('DSWD information saved permanently to the system.', 'success');
            } else if (res.status === 422) {
                const errors = data.errors || {};
                const errMsgs = Object.values(errors).flat().join('<br>');
                showToast('Validation Error:<br>' + (errMsgs || 'Please check the required fields.'), 'error');
            } else {
                showToast(data.message || 'Failed to save DSWD record.', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('An unexpected error occurred while saving.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
        }
    });
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>