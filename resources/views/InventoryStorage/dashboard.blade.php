<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory &amp; Resource Management | DRRM Compliance</title>
    <meta name="description"
        content="Division Disaster Risk Reduction &amp; Management Office – Inventory &amp; Resource Management">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --teal: #158f8f;
            --teal-dark: #0a5a5d;
            --teal-light: rgba(13, 115, 119, .12);
            --sidebar-bg: #112626;
            --sidebar-w: 260px;
            --body-bg: #f0f4f4;
            --card-radius: 12px;
            --missing: #dc3545;
            --attention: #f59e0b;
            --working: #10b981;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--body-bg);
            color: #1a2e2e;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            color: #fff;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 100;
            overflow-y: auto;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 32px 24px 20px;
        }

        .sidebar-brand .brand-icon {
            width: 40px;
            height: 40px;
            background: var(--teal);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .sidebar-brand .brand-text {
            font-size: 1.05rem;
            font-weight: 700;
            line-height: 1.25;
        }

        .sidebar-back-link {
            padding: 0 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            margin-bottom: 10px;
        }

        .sidebar-back-link a {
            color: rgba(255, 255, 255, .7);
            text-decoration: none;
            font-size: .88rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: color .18s;
        }

        .sidebar-back-link a:hover {
            color: #fff;
        }

        .sidebar-section-label {
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: rgba(255, 255, 255, .5);
            padding: 24px 24px 10px;
        }

        .sidebar-nav {
            list-style: none;
            margin: 0;
            padding: 0 12px;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            color: rgba(255, 255, 255, .8);
            font-size: .9rem;
            font-weight: 600;
            text-decoration: none;
            transition: background .18s, color .18s;
            cursor: pointer;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            font-family: inherit;
            border-radius: 10px;
        }

        .sidebar-nav .nav-link:hover {
            background: rgba(255, 255, 255, .08);
            color: #fff;
        }

        .sidebar-nav .nav-link.active {
            background: var(--teal);
            color: #fff;
        }

        .sidebar-nav .nav-link .nav-icon {
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }

        /* ── SCHOOL SWITCHER ── */
        .school-switcher {
            padding: 0 16px 16px;
        }

        .school-switcher select {
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .15);
            color: #fff;
            border-radius: 8px;
            padding: 6px 10px;
            font-size: .78rem;
            width: 100%;
            font-family: inherit;
        }

        .school-switcher select option {
            background: #1a3535;
            color: #fff;
        }

        .school-switcher label {
            font-size: .65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: rgba(255, 255, 255, .45);
            margin-bottom: 5px;
            display: block;
        }

        /* ── MAIN ── */
        .main-content {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e5eaea;
            padding: 18px 32px 14px;
        }

        .topbar h1 {
            font-size: 1.45rem;
            font-weight: 700;
            color: #0f1f1f;
            margin: 0 0 2px;
        }

        .topbar .subtitle {
            font-size: .8rem;
            color: #6b8080;
            margin: 0;
        }

        .topbar-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-outline-muted {
            border: 1.5px solid #d4dede;
            color: #4a6060;
            background: #fff;
            font-size: .8rem;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: background .15s;
            text-decoration: none;
        }

        .btn-outline-muted:hover {
            background: #f0f4f4;
            border-color: #afc4c4;
            color: #2a4040;
        }

        .btn-primary-teal {
            background: var(--teal);
            color: #fff;
            border: none;
            font-size: .82rem;
            font-weight: 600;
            padding: 7px 16px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: background .18s;
            font-family: inherit;
        }

        .btn-primary-teal:hover {
            background: var(--teal-dark);
            color: #fff;
        }

        .btn-danger-sm {
            background: #dc3545;
            color: #fff;
            border: none;
            font-size: .75rem;
            padding: 4px 10px;
            border-radius: 6px;
            cursor: pointer;
            font-family: inherit;
        }

        .btn-edit-sm {
            background: #e9f5f5;
            color: var(--teal);
            border: 1.5px solid #c5dede;
            font-size: .75rem;
            padding: 4px 10px;
            border-radius: 6px;
            cursor: pointer;
            font-family: inherit;
        }

        /* ── PAGE BODY ── */
        .page-body {
            padding: 28px 32px;
            flex: 1;
        }

        .panel {
            display: none;
        }

        .panel.active {
            display: block;
        }

        /* ── STAT CARDS ── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: #fff;
            border-radius: var(--card-radius);
            padding: 20px 24px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
        }

        .stat-card .stat-label {
            font-size: .7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: #8aa0a0;
            margin-bottom: 6px;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
            color: #0f1f1f;
        }

        .stat-card.stat-missing .stat-value {
            color: var(--missing);
        }

        .stat-card.stat-attention .stat-value {
            color: var(--attention);
        }

        .stat-card.stat-working .stat-value {
            color: var(--working);
        }

        /* ── INV CARD ── */
        .inv-card {
            background: #fff;
            border-radius: var(--card-radius);
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
            overflow: hidden;
        }

        .inv-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 22px;
            border-bottom: 1px solid #edf2f2;
            flex-wrap: wrap;
            gap: 10px;
        }

        .inv-card-header .title {
            font-size: .92rem;
            font-weight: 600;
            color: #1a2e2e;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .inv-card-header .title i {
            color: var(--teal);
        }

        .search-box {
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #9bb2b2;
            font-size: .8rem;
        }

        .search-box input {
            border: 1.5px solid #dde8e8;
            border-radius: 8px;
            padding: 6px 12px 6px 30px;
            font-size: .8rem;
            color: #2a4040;
            width: 210px;
            outline: none;
            transition: border-color .18s;
            background: #f8fbfb;
        }

        .search-box input:focus {
            border-color: var(--teal);
            background: #fff;
        }

        /* ── TABLE ── */
        .inv-table {
            width: 100%;
            border-collapse: collapse;
        }

        .inv-table thead th {
            font-size: .68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: #8aa0a0;
            padding: 10px 16px;
            background: #f8fbfb;
            border-bottom: 1px solid #edf2f2;
            white-space: nowrap;
        }

        .inv-table tbody tr {
            border-bottom: 1px solid #f0f5f5;
            transition: background .12s;
        }

        .inv-table tbody tr:last-child {
            border-bottom: none;
        }

        .inv-table tbody tr:hover {
            background: #f8fbfb;
        }

        .inv-table tbody td {
            padding: 10px 16px;
            font-size: .83rem;
            color: #2a4040;
            vertical-align: middle;
        }

        .inv-table .item-name-cell {
            font-weight: 600;
            color: #0f1f1f;
        }

        .inv-table .muted-val {
            color: #9bb2b2;
        }

        /* ── STATUS BADGES ── */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: .72rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .status-badge.missing {
            background: #fde8ea;
            color: #c0293a;
        }

        .status-badge.attention {
            background: #fef3e2;
            color: #b45309;
        }

        .status-badge.working {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge.repair {
            background: #e8e8ff;
            color: #3730a3;
        }

        .status-badge.defective {
            background: #f3e8ff;
            color: #6b21a8;
        }

        /* ── COMPLIANCE SCORE CARD ── */
        .compliance-bar-wrap {
            padding: 18px 22px 16px;
            background: #f8fbfb;
            border-bottom: 1px solid #edf2f2;
        }

        .compliance-scores {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .compliance-score-box {
            flex: 1;
            min-width: 160px;
            background: #fff;
            border-radius: 10px;
            border: 1.5px solid #edf2f2;
            padding: 14px 18px;
        }

        .compliance-score-box .cs-label {
            font-size: .65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: #8aa0a0;
            margin-bottom: 4px;
        }

        .compliance-score-box .cs-value {
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1;
        }

        .compliance-score-box .cs-sub {
            font-size: .72rem;
            color: #9bb2b2;
            margin-top: 2px;
        }

        .cs-good {
            color: #065f46;
        }

        .cs-fair {
            color: #b45309;
        }

        .cs-poor {
            color: #c0293a;
        }

        .progress-thin {
            height: 5px;
            border-radius: 4px;
            background: #edf2f2;
            margin-top: 8px;
            overflow: hidden;
        }

        .progress-thin-bar {
            height: 100%;
            border-radius: 4px;
            transition: width .4s ease;
        }

        .bar-good {
            background: #10b981;
        }

        .bar-fair {
            background: #f59e0b;
        }

        .bar-poor {
            background: #dc3545;
        }

        /* ── DEFAULT LIST TABS ── */
        .section-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .section-tab {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 24px;
            border: 1.5px solid #dde8e8;
            background: #fff;
            font-size: .8rem;
            font-weight: 600;
            color: #4a6060;
            cursor: pointer;
            font-family: inherit;
            transition: background .15s, border-color .15s, color .15s;
        }

        .section-tab:hover {
            border-color: var(--teal);
            color: var(--teal);
        }

        .section-tab.active {
            border-color: var(--teal);
            background: var(--teal);
            color: #fff;
        }

        .section-tab .tab-badge {
            background: rgba(255, 255, 255, .25);
            font-size: .65rem;
            padding: 1px 7px;
            border-radius: 20px;
        }

        .section-tab:not(.active) .tab-badge {
            background: #eef3f3;
            color: #6b8080;
        }

        /* ── SECTION CARDS ── */
        .section-card {
            background: #fff;
            border-radius: var(--card-radius);
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
            margin-bottom: 28px;
            overflow: hidden;
        }

        .section-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 22px;
            border-bottom: 1px solid #edf2f2;
            flex-wrap: wrap;
            gap: 10px;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-label-badge {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--teal);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .9rem;
            flex-shrink: 0;
        }

        .section-title-text {
            font-size: .95rem;
            font-weight: 700;
            color: #0f1f1f;
        }

        .sourced-counter {
            font-size: .75rem;
            color: #9bb2b2;
            font-weight: 500;
        }

        .section-search-box {
            position: relative;
            flex-shrink: 0;
        }

        .section-search-box i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #9bb2b2;
            font-size: .78rem;
            pointer-events: none;
        }

        .section-search-box input {
            border: 1.5px solid #dde8e8;
            border-radius: 8px;
            padding: 6px 12px 6px 30px;
            font-size: .8rem;
            color: #2a4040;
            width: 200px;
            outline: none;
            transition: border-color .18s;
            background: #f8fbfb;
        }

        .section-search-box input:focus {
            border-color: var(--teal);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(13, 115, 119, .1);
        }

        /* ── COMPLIANCE ITEM TABLE ── */
        .item-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .item-img {
            width: 42px;
            height: 42px;
            object-fit: cover;
            border-radius: 8px;
            flex-shrink: 0;
            background: #eef3f3;
            border: 1px solid #e0eaea;
        }

        .item-img-placeholder {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            background: #eef3f3;
            border: 1px solid #e0eaea;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9bb2b2;
            font-size: .75rem;
        }

        .item-name {
            font-weight: 600;
            color: #0f1f1f;
            font-size: .83rem;
            line-height: 1.3;
        }

        /* Have-it toggle */
        .have-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .have-toggle input[type=checkbox] {
            width: 18px;
            height: 18px;
            accent-color: var(--teal);
            cursor: pointer;
        }

        .have-toggle label {
            font-size: .78rem;
            font-weight: 600;
            cursor: pointer;
            color: #4a6060;
        }

        /* Qty */
        .qty-input {
            border: 1.5px solid #dde8e8;
            border-radius: 7px;
            font-size: .78rem;
            color: #2a4040;
            padding: 5px 8px;
            width: 70px;
            text-align: center;
        }

        .qty-input:focus {
            border-color: var(--teal);
            outline: none;
            box-shadow: 0 0 0 3px rgba(13, 115, 119, .1);
        }

        /* Source selects */
        .source-select-wrap {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .form-select-sm {
            border: 1.5px solid #dde8e8;
            border-radius: 7px;
            font-size: .77rem;
            color: #2a4040;
            padding: 5px 8px;
        }

        .form-select-sm:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(13, 115, 119, .1);
            outline: none;
        }

        .form-control-date {
            border: 1.5px solid #dde8e8;
            border-radius: 7px;
            font-size: .77rem;
            color: #2a4040;
            padding: 5px 8px;
            width: 100%;
        }

        .form-control-date:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(13, 115, 119, .1);
            outline: none;
        }

        .remarks-input {
            border: 1.5px solid #dde8e8;
            border-radius: 7px;
            font-size: .77rem;
            color: #2a4040;
            padding: 5px 8px;
            width: 100%;
            resize: none;
        }

        .remarks-input:focus {
            border-color: var(--teal);
            outline: none;
            box-shadow: 0 0 0 3px rgba(13, 115, 119, .1);
        }

        /* Save footer */
        .section-save-bar {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            padding: 14px 22px;
            background: #f8fbfb;
            border-top: 1px solid #edf2f2;
        }

        .save-status {
            font-size: .78rem;
            color: #9bb2b2;
            font-style: italic;
        }

        /* ── MODAL ── */
        .modal-content {
            border: none;
            border-radius: 16px;
            overflow: hidden;
        }

        .modal-header {
            background: #1a2e2e;
            color: #fff;
            padding: 18px 24px;
            border-bottom: none;
        }

        .modal-header .modal-title {
            font-size: .95rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-header .modal-title i {
            color: var(--teal);
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            background: #f8fbfb;
            border-top: 1px solid #edf2f2;
            padding: 14px 24px;
        }

        .form-label {
            font-size: .78rem;
            font-weight: 600;
            color: #2a4040;
            margin-bottom: 5px;
        }

        .form-label small {
            font-weight: 400;
            color: #9bb2b2;
        }

        .form-control,
        .form-select {
            border: 1.5px solid #dde8e8;
            border-radius: 8px;
            font-size: .83rem;
            color: #1a2e2e;
            padding: 8px 12px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(13, 115, 119, .12);
        }

        .btn-cancel {
            background: transparent;
            border: 1.5px solid #d4dede;
            color: #4a6060;
            font-size: .82rem;
            font-weight: 500;
            padding: 7px 18px;
            border-radius: 8px;
            cursor: pointer;
            transition: background .15s;
        }

        .btn-cancel:hover {
            background: #f0f4f4;
        }

        /* ── TOAST ── */
        .toast-wrap {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .toast-msg {
            background: #1a2e2e;
            color: #fff;
            padding: 12px 18px;
            border-radius: 10px;
            font-size: .82rem;
            font-weight: 500;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .2);
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeUp .25s ease;
        }

        .toast-msg.success i {
            color: #10b981;
        }

        .toast-msg.error i {
            color: #dc3545;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── RESPONSIVE ── */
        @media(max-width:992px) {
            :root {
                --sidebar-w: 70px;
            }

            .sidebar-brand {
                padding: 22px 0 18px;
                justify-content: center;
            }

            .brand-text,
            .sidebar-section-label,
            .nav-text,
            .school-switcher {
                display: none !important;
            }

            .sidebar-back-link {
                padding: 0;
                border-bottom: none;
            }
            
            .sidebar-back-link a {
                justify-content: center;
                padding: 12px 0;
            }

            .sidebar-nav .nav-link {
                padding: 12px 0;
                justify-content: center;
            }

            .sidebar-nav .nav-link .nav-icon,
            .sidebar-back-link a .nav-icon {
                font-size: 1.1rem;
                margin: 0;
            }
        }

        @media(max-width:768px) {
            .stat-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .topbar,
            .page-body {
                padding: 16px;
            }

            .compliance-scores {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="layout">

        <!-- ── SIDEBAR ── -->
        <nav class="sidebar">
            <div class="sidebar-brand">
                <div class="brand-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <div class="brand-text">Inventory<br>Manager</div>
            </div>
            <div class="sidebar-back-link">
                <a href="{{ url('/dashboard') }}" title="Back to Dashboard">
                    <span class="nav-icon"><i class="fa-solid fa-arrow-left"></i></span>
                    <span class="nav-text">Back to Dashboard</span>
                </a>
            </div>

            {{-- School switcher (admin only) --}}
            @if(auth()->user()->role === 'admin' && $schools->count() > 1)
                <div class="school-switcher">
                    <label>Active School</label>
                    <select id="schoolSwitcher">
                        @foreach($schools as $s)
                            <option value="{{ $s->id }}" {{ $school && $school->id == $s->id ? 'selected' : '' }}>
                                {{ $s->school_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="sidebar-section-label">Workspace</div>
            <ul class="sidebar-nav">
                <li>
                    <button class="nav-link active" data-panel="panel-dashboard" id="nav-dashboard">
                        <span class="nav-icon"><i class="fa-solid fa-border-all"></i></span>
                        <span class="nav-text">Dashboard</span>
                    </button>
                </li>
                <li>
                    <button class="nav-link" data-panel="panel-default-list" id="nav-default-list">
                        <span class="nav-icon"><i class="fa-solid fa-bars"></i></span>
                        <span class="nav-text">Default List</span>
                    </button>
                </li>
            </ul>

            <div class="sidebar-section-label">Reports</div>
            <ul class="sidebar-nav">
                <li>
                    <button class="nav-link" data-panel="panel-monthly-summary" id="nav-monthly-summary">
                        <span class="nav-icon"><i class="fa-solid fa-chart-column"></i></span>
                        <span class="nav-text">Monthly Summary</span>
                    </button>
                </li>
            </ul>
        </nav>

        <!-- ── MAIN ── -->
        <div class="main-content">
            <div class="topbar d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h1 id="topbar-title">Inventory &amp; Resource Management</h1>
                    <p class="subtitle" id="topbar-subtitle">
                        {{ $school ? $school->school_name : 'No school selected' }}
                        &nbsp;&middot;&nbsp; Division Disaster Risk Reduction &amp; Management Office
                    </p>
                </div>
                <div class="topbar-actions">
                    <a href="{{ route('inventory-storage.export-pdf') }}" class="btn-outline-muted" id="btn-export" style="display:none;">
                        <i class="fa-solid fa-file-pdf"></i> Export PDF
                    </a>
                    <button class="btn-primary-teal" data-bs-toggle="modal" data-bs-target="#addItemModal"
                        id="btn-add-item">
                        <i class="fa-solid fa-plus"></i> Add New Item
                    </button>
                </div>
            </div>

            <div class="page-body">

                <!-- ═══════════════════════════════════════
                 PANEL: DASHBOARD
            ════════════════════════════════════════ -->
                <div class="panel active" id="panel-dashboard">

                    <div class="stat-grid">
                        <div class="stat-card">
                            <div class="stat-label">Total Items</div>
                            <div class="stat-value" id="stat-total">{{ $items->count() }}</div>
                        </div>
                        <div class="stat-card stat-missing">
                            <div class="stat-label">Missing</div>
                            <div class="stat-value" id="stat-missing">{{ $items->where('status', 'missing')->count() }}</div>
                        </div>
                        <div class="stat-card stat-attention">
                            <div class="stat-label">Needs Attention</div>
                            <div class="stat-value" id="stat-needs_attention">{{ $items->where('status', 'needs_attention')->count() }}</div>
                        </div>
                        <div class="stat-card stat-working">
                            <div class="stat-label">Working</div>
                            <div class="stat-value" id="stat-working">{{ $items->where('status', 'working')->count() }}</div>
                        </div>
                    </div>

                    <div class="inv-card">
                        <div class="inv-card-header">
                            <div class="title"><i class="fa-solid fa-bars-staggered"></i> Inventory</div>
                            <div class="d-flex gap-2 align-items-center">
                                <div class="search-box">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    <input type="text" id="inventorySearch" placeholder="Search items..."
                                        aria-label="Search inventory">
                                </div>
                            </div>
                        </div>
                        <table class="inv-table" id="inventoryTable">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Unit</th>
                                    <th>Qty</th>
                                    <th>Status</th>
                                    <th>Location</th>
                                    <th>Fund Source</th>
                                    <th>Received</th>
                                    <th>Checked</th>
                                    <th style="width:90px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    @php
                                        $sm = ['missing' => ['Missing', 'missing', 'fa-circle-exclamation'], 'needs_attention' => ['Needs Attention', 'attention', 'fa-triangle-exclamation'], 'working' => ['Working', 'working', 'fa-circle-check'], 'for_repair' => ['For Repair', 'repair', 'fa-wrench'], 'defective' => ['Defective', 'defective', 'fa-ban']];
                                        $s = $sm[$item->status] ?? [ucfirst($item->status), 'working', 'fa-circle'];
                                    @endphp
                                    <tr class="item-row" data-id="{{ $item->id }}" data-status="{{ $item->status }}">
                                        <td class="item-name-cell">{{ $item->item_name }}</td>
                                        <td class="muted-val">{{ $item->unit ?: '–' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td><span class="status-badge {{ $s[1] }}"><i class="fa-solid {{ $s[2] }}"></i>
                                                {{ $s[0] }}</span></td>
                                        <td>{{ $item->location ?: '–' }}</td>
                                        <td>{{ $item->fund_source ?: '–' }}</td>
                                        <td class="muted-val">
                                            {{ $item->date_received ? \Carbon\Carbon::parse($item->date_received)->format('M d, Y') : '–' }}
                                        </td>
                                        <td class="muted-val">
                                            {{ $item->date_checked ? \Carbon\Carbon::parse($item->date_checked)->format('M d, Y') : '–' }}
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button class="btn-edit-sm btn-edit-item" data-id="{{ $item->id }}"
                                                    title="Edit"><i class="fa-solid fa-pen"></i></button>
                                                <button class="btn-danger-sm btn-delete-item" data-id="{{ $item->id }}"
                                                    data-name="{{ $item->item_name }}" title="Delete"><i
                                                        class="fa-solid fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr id="emptyInventoryRow" style="display: {{ $items->count() > 0 ? 'none' : 'table-row' }};">
                                    <td colspan="9"
                                        style="text-align:center;padding:40px;color:#9bb2b2;font-size:.85rem;">
                                        <i class="fa-solid fa-inbox"
                                            style="font-size:1.8rem;display:block;margin-bottom:10px;opacity:.4;"></i>
                                        No inventory items yet. Click <strong>Add New Item</strong> to get started.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div><!-- /panel-dashboard -->


                <!-- ═══════════════════════════════════════
                 PANEL: DEFAULT LIST
            ════════════════════════════════════════ -->
                <div class="panel" id="panel-default-list">

                    @php
                        // Load saved compliance data for this school
                        $savedRows = $school
                            ? \App\Models\DefaultListItem::where('school_id', $school->id)->get()->groupBy('section')->map(fn($g) => $g->keyBy('item_key'))
                            : collect();
                        $savedA = $savedRows->get('A', collect());
                        $savedB = $savedRows->get('B', collect());

                        $itemsA = \App\Http\Controllers\InventoryStorageController::getItemsCatalogue('A');
                        $itemsB = \App\Http\Controllers\InventoryStorageController::getItemsCatalogue('B');

                        $haveA = $savedA->where('has_item', true)->count();
                        $haveB = $savedB->where('has_item', true)->count();
                        $pctA = count($itemsA) > 0 ? round(($haveA / count($itemsA)) * 100) : 0;
                        $pctB = count($itemsB) > 0 ? round(($haveB / count($itemsB)) * 100) : 0;
                        $pctAll = (count($itemsA) + count($itemsB)) > 0 ? round((($haveA + $haveB) / (count($itemsA) + count($itemsB))) * 100) : 0;
                        $colorClass = fn($p) => $p >= 80 ? 'cs-good' : ($p >= 50 ? 'cs-fair' : 'cs-poor');
                        $barClass = fn($p) => $p >= 80 ? 'bar-good' : ($p >= 50 ? 'bar-fair' : 'bar-poor');
                    @endphp

                    <!-- Compliance overview -->
                    <div style="margin-bottom:24px;">
                        <div class="compliance-scores">
                            <div class="compliance-score-box">
                                <div class="cs-label">Overall Compliance</div>
                                <div class="cs-value {{ $colorClass($pctAll) }}" id="score-val-all">{{ $pctAll }}%</div>
                                <div class="cs-sub" id="score-sub-all">{{ $haveA + $haveB }} / {{ count($itemsA) + count($itemsB) }} items
                                    sourced</div>
                                <div class="progress-thin">
                                    <div class="progress-thin-bar {{ $barClass($pctAll) }}" id="score-bar-all"
                                        style="width:{{ $pctAll }}%"></div>
                                </div>
                            </div>
                            <div class="compliance-score-box">
                                <div class="cs-label">Section A — Emergency Supplies</div>
                                <div class="cs-value {{ $colorClass($pctA) }}" id="score-val-a">{{ $pctA }}%</div>
                                <div class="cs-sub" id="score-sub-a">{{ $haveA }} / {{ count($itemsA) }} sourced</div>
                                <div class="progress-thin">
                                    <div class="progress-thin-bar {{ $barClass($pctA) }}" id="score-bar-a"
                                        style="width:{{ $pctA }}%"></div>
                                </div>
                            </div>
                            <div class="compliance-score-box">
                                <div class="cs-label">Section B — Rescue Supplies</div>
                                <div class="cs-value {{ $colorClass($pctB) }}" id="score-val-b">{{ $pctB }}%</div>
                                <div class="cs-sub" id="score-sub-b">{{ $haveB }} / {{ count($itemsB) }} sourced</div>
                                <div class="progress-thin">
                                    <div class="progress-thin-bar {{ $barClass($pctB) }}" id="score-bar-b"
                                        style="width:{{ $pctB }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category tabs -->
                    <div class="section-tabs">
                        <button class="section-tab active" data-filter="all" id="tab-all">
                            <i class="fa-solid fa-check" style="font-size:.7rem;"></i> All Categories
                        </button>
                        <button class="section-tab" data-filter="a" id="tab-a">
                            <span style="font-size:.75rem;font-weight:700;">A</span>
                            Emergency Supplies &amp; Equipment
                            <span class="tab-badge">{{ count($itemsA) }} items</span>
                        </button>
                        <button class="section-tab" data-filter="b" id="tab-b">
                            <span style="font-size:.75rem;font-weight:700;">B</span>
                            Response &amp; Rescue Supplies
                            <span class="tab-badge">{{ count($itemsB) }} items</span>
                        </button>
                    </div>

                    <!-- ── SECTION A ── -->
                    <div class="section-card" id="section-a" data-section="a">
                        <div class="section-card-header">
                            <div class="section-title">
                                <div class="section-label-badge">A</div>
                                <div>
                                    <div class="section-title-text">Emergency Supplies and Equipment</div>
                                    <div class="sourced-counter">Provided by DepEd and/or Partners</div>
                                </div>
                            </div>
                            <div class="section-search-box">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" id="search-a" placeholder="Search item..."
                                    aria-label="Search section A">
                            </div>
                        </div>
                        <table class="inv-table" id="table-a">
                            <thead>
                                <tr>
                                    <th style="width:30%">Item Description</th>
                                    <th style="width:9%">Have It?</th>
                                    <th style="width:7%">Qty</th>
                                    <th style="width:22%">Source</th>
                                    <th style="width:12%">Date Checked</th>
                                    <th style="width:20%">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $imgBase = 'images/InventoryManager/A_emergency_supplies_and_equipment/';
                                    $imgMapA = ['2fold_aluminum_stretcher' => '2-Fold Aluminum Stretcher.jpg', 'cadaver_bag' => 'Cadaver bag.jpg', 'c_collars' => 'C-Collar.jpg', 'cot_battlefield_bed' => 'Cot (battlefield bed).jpg', 'cpr_board' => 'CPR Board.jpg', 'emergency_head_lamp' => 'Emergency Head Lamp.jpg', 'emergency_whistle' => 'Emergency whistle.jpg', 'fire_extinguisher' => 'Fire extinguisher.jpg', 'go_bag_learner' => 'Go bag with Multi-tool for each learner.png', 'go_bag_personnel' => 'Go bag with Multi-tool for each personnel.png', 'handheld_base_radios' => 'Handheld base radios.png', 'led_search_light' => 'Led search light 850 lumens.jpg', 'life_vest' => 'Life vest life jacket.jpg', 'medical_cushion' => 'medical cushion.jpg', 'plastic_spine_board' => 'plastic spine board with safety belts.jpg', 'portable_pa_system' => 'portable PA system.png', 'safety_coat' => 'safety coat.jpg', 'safety_helmet' => 'safety helmet.jpg', 'safety_shoes' => 'safety shoes.jpg', 'splinter' => 'splinter1.jpg', 'steel_boxes' => 'steel boxes.jpg', 'steel_cabinets' => 'steel cabinets.jpg', 'traffic_vest' => 'traffic vest.jpg', 'transport_bags_45l' => 'transport bag, 45l.jpg', 'trauma_bag' => 'trauma bag with contents for 20 25 persons.jpg', 'universal_head_immobilizer' => 'universal head immobilizer.png'];
                                @endphp
                                @foreach($itemsA as $key => $name)
                                    @php $row = $savedA->get($key);
                                    $img = $imgMapA[$key] ?? null; @endphp
                                    <tr class="compliance-row" data-key="{{ $key }}" data-section="A">
                                        <td>
                                            <div class="item-cell">
                                                @if($img)
                                                    <img src="{{ asset($imgBase . $img) }}" alt="{{ $name }}" class="item-img"
                                                        onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                                    <div class="item-img-placeholder" style="display:none;"><i
                                                            class="fa-solid fa-image"></i></div>
                                                @else
                                                    <div class="item-img-placeholder"><i class="fa-solid fa-image"></i></div>
                                                @endif
                                                <span class="item-name">{{ $name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="have-toggle">
                                                <input type="checkbox" class="have-checkbox" id="have_a_{{ $key }}" {{ $row && $row->has_item ? 'checked' : '' }}>
                                                <label for="have_a_{{ $key }}">Yes</label>
                                            </div>
                                        </td>
                                        <td><input type="number" class="qty-input"
                                                value="{{ $row ? $row->quantity_owned : 0 }}" min="0"></td>
                                        <td>
                                            <div class="source-select-wrap">
                                                <select class="form-select-sm source-select">
                                                    <option value="">Select Source</option>
                                                    <option value="deped" {{ $row && $row->source == 'deped' ? 'selected' : '' }}>DepEd</option>
                                                    <option value="partner" {{ $row && $row->source == 'partner' ? 'selected' : '' }}>Partner</option>
                                                </select>
                                                <select
                                                    class="form-select-sm deped-options {{ ($row && $row->source == 'deped') ? '' : 'd-none' }}">
                                                    <option value="">Select DepEd Source</option>
                                                    <option value="GAA" {{ $row && $row->source_detail == 'GAA' ? 'selected' : '' }}>GAA</option>
                                                    <option value="Special Purpose Fund" {{ $row && $row->source_detail == 'Special Purpose Fund' ? 'selected' : '' }}>
                                                        Special Purpose Fund</option>
                                                    <option value="Other DepEd Sources" {{ $row && $row->source_detail == 'Other DepEd Sources' ? 'selected' : '' }}>
                                                        Other DepEd Sources</option>
                                                    <option value="Others" {{ $row && $row->source_detail == 'Others' ? 'selected' : '' }}>Others</option>
                                                </select>
                                                <select
                                                    class="form-select-sm partner-options {{ ($row && $row->source == 'partner') ? '' : 'd-none' }}">
                                                    <option value="">Select Partner Source</option>
                                                    <option value="NGO" {{ $row && $row->source_detail == 'NGO' ? 'selected' : '' }}>NGO</option>
                                                    <option value="LGU" {{ $row && $row->source_detail == 'LGU' ? 'selected' : '' }}>LGU</option>
                                                    <option value="Private Sector" {{ $row && $row->source_detail == 'Private Sector' ? 'selected' : '' }}>Private Sector</option>
                                                    <option value="Others" {{ $row && $row->source_detail == 'Others' ? 'selected' : '' }}>Others</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td><input type="date" class="form-control-date"
                                                value="{{ $row && $row->date_checked ? $row->date_checked->format('Y-m-d') : date('Y-m-d') }}">
                                        </td>
                                        <td><textarea class="remarks-input"
                                                rows="2">{{ $row ? $row->remarks : '' }}</textarea></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="section-save-bar">
                            <span class="save-status" id="save-status-a"></span>
                            <button class="btn-primary-teal btn-save-section" data-section="A" id="btn-save-a">
                                <i class="fa-solid fa-floppy-disk"></i> Save Section A
                            </button>
                        </div>
                    </div><!-- /section-a -->

                    <!-- ── SECTION B ── -->
                    <div class="section-card" id="section-b" data-section="b">
                        <div class="section-card-header">
                            <div class="section-title">
                                <div class="section-label-badge" style="background:#1a7f5a;">B</div>
                                <div>
                                    <div class="section-title-text">Response and Rescue Supplies</div>
                                    <div class="sourced-counter">Provided by DepEd and/or Partners</div>
                                </div>
                            </div>
                            <div class="section-search-box">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" id="search-b" placeholder="Search item..."
                                    aria-label="Search section B">
                            </div>
                        </div>
                        <table class="inv-table" id="table-b">
                            <thead>
                                <tr>
                                    <th style="width:30%">Item Description</th>
                                    <th style="width:9%">Have It?</th>
                                    <th style="width:7%">Qty</th>
                                    <th style="width:22%">Source</th>
                                    <th style="width:12%">Date Checked</th>
                                    <th style="width:20%">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $imgBaseB = 'images/InventoryManager/B_response_and_rescue_supplies_and_equipment/';
                                    $imgMapB = ['bicycle' => 'bicycle.jpg', 'fire_hose' => 'firehose.jpg', 'motor_banca' => 'motor banca.jpg', 'power_sprayer' => 'powersprayer.jpg'];
                                @endphp
                                @foreach($itemsB as $key => $name)
                                    @php $row = $savedB->get($key);
                                    $img = $imgMapB[$key] ?? null; @endphp
                                    <tr class="compliance-row" data-key="{{ $key }}" data-section="B">
                                        <td>
                                            <div class="item-cell">
                                                @if($img)
                                                    <img src="{{ asset($imgBaseB . $img) }}" alt="{{ $name }}" class="item-img"
                                                        onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                                    <div class="item-img-placeholder" style="display:none;"><i
                                                            class="fa-solid fa-image"></i></div>
                                                @else
                                                    <div class="item-img-placeholder"><i class="fa-solid fa-image"></i></div>
                                                @endif
                                                <span class="item-name">{{ $name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="have-toggle">
                                                <input type="checkbox" class="have-checkbox" id="have_b_{{ $key }}" {{ $row && $row->has_item ? 'checked' : '' }}>
                                                <label for="have_b_{{ $key }}">Yes</label>
                                            </div>
                                        </td>
                                        <td><input type="number" class="qty-input"
                                                value="{{ $row ? $row->quantity_owned : 0 }}" min="0"></td>
                                        <td>
                                            <div class="source-select-wrap">
                                                <select class="form-select-sm source-select">
                                                    <option value="">Select Source</option>
                                                    <option value="deped" {{ $row && $row->source == 'deped' ? 'selected' : '' }}>DepEd</option>
                                                    <option value="partner" {{ $row && $row->source == 'partner' ? 'selected' : '' }}>Partner</option>
                                                </select>
                                                <select
                                                    class="form-select-sm deped-options {{ ($row && $row->source == 'deped') ? '' : 'd-none' }}">
                                                    <option value="">Select DepEd Source</option>
                                                    <option value="GAA" {{ $row && $row->source_detail == 'GAA' ? 'selected' : '' }}>GAA</option>
                                                    <option value="Special Purpose Fund" {{ $row && $row->source_detail == 'Special Purpose Fund' ? 'selected' : '' }}>
                                                        Special Purpose Fund</option>
                                                    <option value="Other DepEd Sources">Other DepEd Sources</option>
                                                    <option value="Others" {{ $row && $row->source_detail == 'Others' ? 'selected' : '' }}>Others</option>
                                                </select>
                                                <select
                                                    class="form-select-sm partner-options {{ ($row && $row->source == 'partner') ? '' : 'd-none' }}">
                                                    <option value="">Select Partner Source</option>
                                                    <option value="NGO" {{ $row && $row->source_detail == 'NGO' ? 'selected' : '' }}>NGO</option>
                                                    <option value="LGU" {{ $row && $row->source_detail == 'LGU' ? 'selected' : '' }}>LGU</option>
                                                    <option value="Private Sector" {{ $row && $row->source_detail == 'Private Sector' ? 'selected' : '' }}>Private Sector</option>
                                                    <option value="Others" {{ $row && $row->source_detail == 'Others' ? 'selected' : '' }}>Others</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td><input type="date" class="form-control-date"
                                                value="{{ $row && $row->date_checked ? $row->date_checked->format('Y-m-d') : date('Y-m-d') }}">
                                        </td>
                                        <td><textarea class="remarks-input"
                                                rows="2">{{ $row ? $row->remarks : '' }}</textarea></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="section-save-bar">
                            <span class="save-status" id="save-status-b"></span>
                            <button class="btn-primary-teal btn-save-section" data-section="B" id="btn-save-b">
                                <i class="fa-solid fa-floppy-disk"></i> Save Section B
                            </button>
                        </div>
                    </div><!-- /section-b -->

                </div><!-- /panel-default-list -->


                <!-- ═══════════════════════════════════════
                 PANEL: MONTHLY SUMMARY
            ════════════════════════════════════════ -->
                <div class="panel" id="panel-monthly-summary">
                    @php
                        $now        = \Carbon\Carbon::now();
                        $monthStart = $now->copy()->startOfMonth();
                        $monthEnd   = $now->copy()->endOfMonth();

                        $missingItems   = $items->where('status', 'missing');
                        $attentionItems = $items->where('status', 'needs_attention');
                        $newlyAdded     = $items->filter(fn($i) => $i->created_at && \Carbon\Carbon::parse($i->created_at)->between($monthStart, $monthEnd));

                        $totalA   = count($itemsA);
                        $totalB   = count($itemsB);
                        $haveAc   = $savedA->where('has_item', true)->count();
                        $haveBc   = $savedB->where('has_item', true)->count();
                        $pctAc    = $totalA > 0 ? round(($haveAc / $totalA) * 100) : 0;
                        $pctBc    = $totalB > 0 ? round(($haveBc / $totalB) * 100) : 0;
                        $pctCus   = 100;

                        $byFund = $items->groupBy(fn($i) => $i->fund_source ?: 'Unspecified')
                                        ->map(fn($g) => $g->count())
                                        ->sortDesc();
                    @endphp

                    <!-- Month label -->
                    <div style="margin-bottom:20px;">
                        <div style="font-size:.72rem;font-weight:700;letter-spacing:.1em;color:#9bb2b2;text-transform:uppercase;">
                            Monthly Summary Report &middot; {{ $now->format('F Y') }}
                        </div>
                        <div style="font-size:.75rem;color:#6a9191;margin-top:3px;">
                            {{ $school ? $school->school_name : 'No school selected' }}
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="stat-grid" style="margin-bottom:24px;">
                        <div class="stat-card">
                            <div class="stat-label">Total Items Tracked</div>
                            <div class="stat-value">{{ $items->count() }}</div>
                        </div>
                        <div class="stat-card stat-missing">
                            <div class="stat-label">Missing</div>
                            <div class="stat-value">{{ $missingItems->count() }}</div>
                        </div>
                        <div class="stat-card stat-attention">
                            <div class="stat-label">Needs Attention</div>
                            <div class="stat-value">{{ $attentionItems->count() }}</div>
                        </div>
                        <div class="stat-card stat-working">
                            <div class="stat-label">Working</div>
                            <div class="stat-value">{{ $items->where('status', 'working')->count() }}</div>
                        </div>
                    </div>

                    <!-- Category completion + Fund source -->
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">

                        <div class="inv-card" style="padding:22px 24px;">
                            <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;color:#9bb2b2;text-transform:uppercase;margin-bottom:16px;">
                                <i class="fa-solid fa-layer-group" style="margin-right:6px;"></i>Category Completion
                            </div>
                            <div style="display:flex;flex-direction:column;gap:14px;">
                                @php
                                    $catRows = [
                                        ['A · Emergency Supplies', $haveAc, $totalA, $pctAc, '#158f8f'],
                                        ['B · Response & Rescue',  $haveBc, $totalB, $pctBc, '#1a7f5a'],
                                        ['Custom / Ad-hoc Items',  $items->count(), $items->count(), $pctCus, '#2a6e8a'],
                                    ];
                                @endphp
                                @foreach($catRows as [$label, $have, $total, $pct, $color])
                                    <div>
                                        <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:6px;">
                                            <span style="color:#c8dada;">{{ $label }}</span>
                                            <span style="color:#9bb2b2;font-size:.75rem;">{{ $have }}/{{ $total }}</span>
                                        </div>
                                        <div style="background:#1e3535;border-radius:4px;height:7px;overflow:hidden;">
                                            <div style="height:100%;width:{{ $pct }}%;background:{{ $color }};border-radius:4px;"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="inv-card" style="padding:22px 24px;">
                            <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;color:#9bb2b2;text-transform:uppercase;margin-bottom:16px;">
                                <i class="fa-solid fa-coins" style="margin-right:6px;"></i>Items by Fund Source
                            </div>
                            <div style="display:flex;flex-direction:column;gap:9px;">
                                @forelse($byFund as $source => $cnt)
                                    <div style="display:flex;justify-content:space-between;align-items:center;padding:9px 14px;background:#1e3535;border-radius:8px;">
                                        <span style="font-size:.83rem;color:#c8dada;">{{ $source }}</span>
                                        <span style="font-size:.78rem;color:#9bb2b2;font-weight:600;">{{ $cnt }} {{ $cnt === 1 ? 'item' : 'items' }}</span>
                                    </div>
                                @empty
                                    <p style="color:#9bb2b2;font-size:.82rem;">No items recorded.</p>
                                @endforelse
                            </div>
                        </div>

                    </div>

                    <!-- Missing items -->
                    @if($missingItems->count() > 0)
                    <div class="inv-card" style="margin-bottom:20px;">
                        <div class="inv-card-header">
                            <div class="title" style="color:var(--missing);"><i class="fa-solid fa-circle-exclamation"></i> Missing Items &mdash; Requires Immediate Action</div>
                        </div>
                        <table class="inv-table">
                            <thead><tr><th>Item Name</th><th>Location</th><th>Fund Source</th><th>Last Checked</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach($missingItems as $item)
                                <tr>
                                    <td class="item-name-cell">{{ $item->item_name }}</td>
                                    <td>{{ $item->location ?: '&ndash;' }}</td>
                                    <td>{{ $item->fund_source ?: '&ndash;' }}</td>
                                    <td class="muted-val">{{ $item->date_checked ? \Carbon\Carbon::parse($item->date_checked)->format('M d, Y') : '&ndash;' }}</td>
                                    <td><span class="status-badge missing"><i class="fa-solid fa-circle-exclamation"></i> Missing</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    <!-- Needs attention -->
                    @if($attentionItems->count() > 0)
                    <div class="inv-card" style="margin-bottom:20px;">
                        <div class="inv-card-header">
                            <div class="title" style="color:var(--attention);"><i class="fa-solid fa-triangle-exclamation"></i> Needs Attention &mdash; Follow-up Recommended</div>
                        </div>
                        <table class="inv-table">
                            <thead><tr><th>Item Name</th><th>Qty</th><th>Location</th><th>Fund Source</th><th>Last Checked</th></tr></thead>
                            <tbody>
                                @foreach($attentionItems as $item)
                                <tr>
                                    <td class="item-name-cell">{{ $item->item_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->location ?: '&ndash;' }}</td>
                                    <td>{{ $item->fund_source ?: '&ndash;' }}</td>
                                    <td class="muted-val">{{ $item->date_checked ? \Carbon\Carbon::parse($item->date_checked)->format('M d, Y') : '&ndash;' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    <!-- Newly added this month -->
                    <div class="inv-card">
                        <div class="inv-card-header">
                            <div class="title">
                                <i class="fa-solid fa-plus"></i> Newly Added This Month
                                <span style="font-size:.72rem;color:#9bb2b2;font-weight:400;margin-left:8px;">{{ $now->format('F Y') }}</span>
                            </div>
                        </div>
                        @if($newlyAdded->count() > 0)
                        <table class="inv-table">
                            <thead><tr><th>Item Name</th><th>Qty</th><th>Location</th><th>Fund Source</th><th>Date Received</th></tr></thead>
                            <tbody>
                                @foreach($newlyAdded as $item)
                                <tr>
                                    <td class="item-name-cell">{{ $item->item_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->location ?: '&ndash;' }}</td>
                                    <td>{{ $item->fund_source ?: '&ndash;' }}</td>
                                    <td class="muted-val">{{ $item->date_received ? \Carbon\Carbon::parse($item->date_received)->format('M d, Y') : '&ndash;' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div style="text-align:center;padding:32px;color:#9bb2b2;font-size:.85rem;">
                            <i class="fa-solid fa-inbox" style="font-size:1.6rem;display:block;margin-bottom:10px;opacity:.4;"></i>
                            No new items added in {{ $now->format('F Y') }}.
                        </div>
                        @endif
                    </div>

                </div><!-- /panel-monthly-summary -->

            </div><!-- /page-body -->
        </div><!-- /main-content -->
    </div><!-- /layout -->


    <!-- ── ADD ITEM MODAL ── -->
    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemModalLabel"><i class="fa-solid fa-plus"></i> Add New Inventory
                        Item</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('inventory-storage.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label" for="add-item-name">Item Name</label>
                                <input type="text" class="form-control" id="add-item-name" name="item_name"
                                    placeholder="Enter item name..." required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="add-unit">Unit</label>
                                <select class="form-select" name="unit" id="add-unit">
                                    <option value="" disabled selected hidden>Select unit type</option>
                                    <option value="Boxes">Boxes</option>
                                    <option value="Sets">Sets</option>
                                    <option value="Pieces">Pieces</option>
                                    <option value="Others">Others (Specify below)</option>
                                </select>
                                <div class="mt-2 d-none" id="addUnitOtherWrapper">
                                    <input type="text" class="form-control" name="unit_other" id="addUnitOtherInput"
                                        placeholder="crates, sets, pallets…">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="add-quantity">Quantity</label>
                                <input type="number" class="form-control" id="add-quantity" name="quantity" min="0"
                                    value="0" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="add-status">Status</label>
                                <select class="form-select" id="add-status" name="status" required>
                                    <option value="working">Working</option>
                                    <option value="needs_attention">Needs Attention</option>
                                    <option value="for_repair">For Repair</option>
                                    <option value="defective">Defective</option>
                                    <option value="missing">Missing</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="add-location">Location</label>
                                <input type="text" class="form-control" id="add-location" name="location"
                                    placeholder="Storage area">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="add-fund-source">Fund Source</label>
                                <select class="form-select" id="add-fund-source" name="fund_source">
                                    <option value="" disabled selected hidden>Select funding source</option>
                                    <option value="School MOOE">School MOOE</option>
                                    <option value="SEF">SEF</option>
                                    <option value="Division Office">Division Office</option>
                                    <option value="Regional Office">Regional Office</option>
                                    <option value="Central Office">Central Office</option>
                                    <option value="FTPA/PTA/GPTA">FTPA / PTA / GPTA</option>
                                    <option value="Others">Others (Specify below)</option>
                                </select>
                                <div class="mt-2 d-none" id="addFundOtherWrapper">
                                    <input type="text" class="form-control" name="fund_source_other"
                                        id="addFundOtherInput" placeholder="e.g., donation, specific project">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="add-date-received">Date Received <small>(per custodian
                                        slip)</small></label>
                                <input type="date" class="form-control" id="add-date-received" name="date_received"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="add-date-checked">Date Checked</label>
                                <input type="date" class="form-control" id="add-date-checked" name="date_checked"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-teal"><i class="fa-solid fa-floppy-disk"></i> Save
                            Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ── EDIT ITEM MODAL ── -->
    <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editItemModalLabel"><i class="fa-solid fa-pen"></i> Edit Inventory Item
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label" for="edit-item-name">Item Name</label>
                            <input type="text" class="form-control" id="edit-item-name" placeholder="Enter item name..."
                                required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="edit-unit">Unit</label>
                            <select class="form-select" id="edit-unit">
                                <option value="">— none —</option>
                                <option value="Boxes">Boxes</option>
                                <option value="Sets">Sets</option>
                                <option value="Pieces">Pieces</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="edit-quantity">Quantity</label>
                            <input type="number" class="form-control" id="edit-quantity" min="0" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="edit-status">Status</label>
                            <select class="form-select" id="edit-status">
                                <option value="working">Working</option>
                                <option value="needs_attention">Needs Attention</option>
                                <option value="for_repair">For Repair</option>
                                <option value="defective">Defective</option>
                                <option value="missing">Missing</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="edit-location">Location</label>
                            <input type="text" class="form-control" id="edit-location" placeholder="Storage area">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="edit-fund-source">Fund Source</label>
                            <select class="form-select" id="edit-fund-source">
                                <option value="">— none —</option>
                                <option value="School MOOE">School MOOE</option>
                                <option value="SEF">SEF</option>
                                <option value="Division Office">Division Office</option>
                                <option value="Regional Office">Regional Office</option>
                                <option value="Central Office">Central Office</option>
                                <option value="FTPA/PTA/GPTA">FTPA / PTA / GPTA</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="edit-date-received">Date Received</label>
                            <input type="date" class="form-control" id="edit-date-received">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="edit-date-checked">Date Checked</label>
                            <input type="date" class="form-control" id="edit-date-checked">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-primary-teal" id="btn-save-edit"><i
                            class="fa-solid fa-floppy-disk"></i> Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ── DELETE CONFIRMATION MODAL ── -->
    <div class="modal fade" id="deleteItemModal" tabindex="-1" aria-labelledby="deleteItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteItemModalLabel"><i class="fa-solid fa-triangle-exclamation" style="color: var(--missing);"></i> Delete Item</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to delete "<strong id="deleteItemName"></strong>"?<br><br>This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-danger-sm py-2 px-3" id="btn-confirm-delete"><i class="fa-solid fa-trash"></i> Delete Item</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ── TOAST CONTAINER ── -->
    <div class="toast-wrap" id="toastWrap"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;

        // ── helpers ──────────────────────────────────────────────────
        function toast(msg, type = 'success') {
            var wrap = document.getElementById('toastWrap');
            var el = document.createElement('div');
            el.className = 'toast-msg ' + type;
            el.innerHTML = '<i class="fa-solid ' + (type === 'success' ? 'fa-circle-check' : 'fa-circle-xmark') + '"></i> ' + msg;
            wrap.appendChild(el);
            setTimeout(function () { el.remove(); }, 3500);
        }

        function apiFetch(url, method, body) {
            return fetch(url, {
                method: method,
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: body ? JSON.stringify(body) : undefined,
            }).then(function (r) { return r.json(); });
        }

        // ── Panel switcher ────────────────────────────────────────────
        var panelTitles = { 'panel-dashboard': 'Inventory & Resource Management', 'panel-default-list': 'Default Inventory List', 'panel-monthly-summary': 'Monthly Summary' };
        var panelSubs = { 'panel-dashboard': '{{ $school ? $school->school_name : "No school selected" }} · Storage overview', 'panel-default-list': '{{ $school ? $school->school_name : "No school selected" }} · Compliance checklist', 'panel-monthly-summary': '{{ $school ? $school->school_name : "No school selected" }} · {{ now()->format("F Y") }}' };

        function switchPanel(panelId) {
            document.querySelectorAll('.panel').forEach(function (p) { p.classList.remove('active'); });
            document.getElementById(panelId).classList.add('active');
            document.querySelectorAll('.sidebar-nav .nav-link[data-panel]').forEach(function (b) {
                b.classList.toggle('active', b.dataset.panel === panelId);
            });
            document.getElementById('topbar-title').textContent = panelTitles[panelId] || '';

            // Toggle topbar action buttons
            var btnExport  = document.getElementById('btn-export');
            var btnAddItem = document.getElementById('btn-add-item');
            var isMonthly  = panelId === 'panel-monthly-summary';
            if (btnExport)  btnExport.style.display  = isMonthly ? '' : 'none';
            if (btnAddItem) btnAddItem.style.display  = isMonthly ? 'none' : '';
        }

        document.querySelectorAll('[data-panel]').forEach(function (btn) {
            btn.addEventListener('click', function () { switchPanel(this.dataset.panel); });
        });

        // ── Default List filter ───────────────────────────────────────
        function applyFilter(filter) {
            document.querySelectorAll('.section-tab').forEach(function (t) { t.classList.toggle('active', t.dataset.filter === filter); });
            document.querySelectorAll('.section-card[data-section]').forEach(function (card) {
                card.style.display = (filter === 'all' || card.dataset.section.toLowerCase() === filter) ? '' : 'none';
            });
        }
        document.querySelectorAll('.section-tab').forEach(function (tab) {
            tab.addEventListener('click', function () { applyFilter(this.dataset.filter); });
        });
        applyFilter('all');

        // ── Per-section search ────────────────────────────────────────
        ['a', 'b'].forEach(function (s) {
            var input = document.getElementById('search-' + s);
            if (!input) return;
            input.addEventListener('input', function () {
                var q = this.value.toLowerCase();
                document.querySelectorAll('#table-' + s + ' tbody tr').forEach(function (row) {
                    row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
                });
            });
        });

        // ── Dashboard search ──────────────────────────────────────────
        document.getElementById('inventorySearch').addEventListener('input', function () {
            var q = this.value.toLowerCase();
            document.querySelectorAll('#inventoryTable tbody tr').forEach(function (row) {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });

        // ── Source cascade ────────────────────────────────────────────
        document.querySelectorAll('.source-select').forEach(function (sel) {
            sel.addEventListener('change', function () {
                var wrap = this.closest('.source-select-wrap');
                var deped = wrap.querySelector('.deped-options');
                var partner = wrap.querySelector('.partner-options');
                deped.classList.add('d-none'); partner.classList.add('d-none');
                if (this.value === 'deped') deped.classList.remove('d-none');
                if (this.value === 'partner') partner.classList.remove('d-none');
            });
        });

        // ── Compliance score updater ──────────────────────────────────
        function updateScore(section) {
            var s = section.toLowerCase();
            var rows = document.querySelectorAll('#table-' + s + ' tbody tr.compliance-row');
            var total = rows.length;
            var have = 0;
            rows.forEach(function (r) { if (r.querySelector('.have-checkbox').checked) have++; });
            var pct = total > 0 ? Math.round((have / total) * 100) : 0;
            var el = document.getElementById('score-val-' + s);
            var sub = document.getElementById('score-sub-' + s);
            var bar = document.getElementById('score-bar-' + s);
            if (el) { el.textContent = pct + '%'; el.className = 'cs-value ' + (pct >= 80 ? 'cs-good' : pct >= 50 ? 'cs-fair' : 'cs-poor'); }
            if (sub) sub.textContent = have + ' / ' + total + ' sourced';
            if (bar) { bar.style.width = pct + '%'; bar.className = 'progress-thin-bar ' + (pct >= 80 ? 'bar-good' : pct >= 50 ? 'bar-fair' : 'bar-poor'); }

            // Update overall score
            var allRows = document.querySelectorAll('.compliance-row');
            var allTotal = allRows.length;
            var allHave = 0;
            allRows.forEach(function (r) { if (r.querySelector('.have-checkbox').checked) allHave++; });
            var allPct = allTotal > 0 ? Math.round((allHave / allTotal) * 100) : 0;
            
            var allEl = document.getElementById('score-val-all');
            var allSub = document.getElementById('score-sub-all');
            var allBar = document.getElementById('score-bar-all');
            if (allEl) { allEl.textContent = allPct + '%'; allEl.className = 'cs-value ' + (allPct >= 80 ? 'cs-good' : allPct >= 50 ? 'cs-fair' : 'cs-poor'); }
            if (allSub) allSub.textContent = allHave + ' / ' + allTotal + ' items sourced';
            if (allBar) { allBar.style.width = allPct + '%'; allBar.className = 'progress-thin-bar ' + (allPct >= 80 ? 'bar-good' : allPct >= 50 ? 'bar-fair' : 'bar-poor'); }
        }

        document.querySelectorAll('.have-checkbox').forEach(function (cb) {
            cb.addEventListener('change', function () {
                var section = this.closest('tr').dataset.section;
                updateScore(section);
            });
        });

        // ── Save section ──────────────────────────────────────────────
        document.querySelectorAll('.btn-save-section').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var section = this.dataset.section;
                var s = section.toLowerCase();
                var rows = document.querySelectorAll('#table-' + s + ' tbody tr.compliance-row');
                var items = [];
                rows.forEach(function (row) {
                    var wrap = row.querySelector('.source-select-wrap');
                    var srcSel = wrap.querySelector('.source-select');
                    var src = srcSel.value;
                    var detail = '';
                    if (src === 'deped') detail = wrap.querySelector('.deped-options').value;
                    if (src === 'partner') detail = wrap.querySelector('.partner-options').value;
                    items.push({
                        item_key: row.dataset.key,
                        has_item: row.querySelector('.have-checkbox').checked,
                        quantity_owned: parseInt(row.querySelector('.qty-input').value) || 0,
                        source: src || null,
                        source_detail: detail || null,
                        date_checked: row.querySelector('.form-control-date').value || null,
                        remarks: row.querySelector('.remarks-input').value || null,
                    });
                });

                var statusEl = document.getElementById('save-status-' + s);
                if (statusEl) statusEl.textContent = 'Saving…';

                apiFetch('{{ route("inventory-storage.default-list.save") }}', 'POST', { section: section, items: items })
                    .then(function (res) {
                        if (res.success) {
                            toast(res.message || 'Saved!', 'success');
                            if (statusEl) statusEl.textContent = 'Saved · ' + new Date().toLocaleTimeString();
                        } else {
                            toast(res.message || 'Error saving.', 'error');
                            if (statusEl) statusEl.textContent = '';
                        }
                    }).catch(function () {
                        toast('Network error. Please try again.', 'error');
                        if (statusEl) statusEl.textContent = '';
                    });
            });
        });

        // ── Add modal: "Others" toggles ───────────────────────────────
        document.getElementById('add-fund-source').addEventListener('change', function () {
            var w = document.getElementById('addFundOtherWrapper'), i = document.getElementById('addFundOtherInput');
            if (this.value === 'Others') { w.classList.remove('d-none'); i.setAttribute('required', 'required'); }
            else { w.classList.add('d-none'); i.removeAttribute('required'); i.value = ''; }
        });
        document.getElementById('add-unit').addEventListener('change', function () {
            var w = document.getElementById('addUnitOtherWrapper'), i = document.getElementById('addUnitOtherInput');
            if (this.value === 'Others') { w.classList.remove('d-none'); i.setAttribute('required', 'required'); }
            else { w.classList.add('d-none'); i.removeAttribute('required'); i.value = ''; }
        });

        // ── Edit modal ────────────────────────────────────────────────
        var currentEditId = null;
        var editModal = new bootstrap.Modal(document.getElementById('editItemModal'));

        document.querySelectorAll('.btn-edit-item').forEach(function (btn) {
            btn.addEventListener('click', function () {
                currentEditId = this.dataset.id;
                apiFetch('{{ url("inventory-storage/item") }}/' + currentEditId, 'GET')
                    .then(function (item) {
                        document.getElementById('edit-item-name').value = item.item_name || '';
                        document.getElementById('edit-unit').value = item.unit || '';
                        document.getElementById('edit-quantity').value = item.quantity || 0;
                        document.getElementById('edit-status').value = item.status || 'working';
                        document.getElementById('edit-location').value = item.location || '';
                        document.getElementById('edit-fund-source').value = item.fund_source || '';
                        document.getElementById('edit-date-received').value = item.date_received || '';
                        document.getElementById('edit-date-checked').value = item.date_checked || '';
                        editModal.show();
                    }).catch(function () { toast('Could not load item data.', 'error'); });
            });
        });

        document.getElementById('btn-save-edit').addEventListener('click', function () {
            if (!currentEditId) return;
            var body = {
                item_name: document.getElementById('edit-item-name').value,
                unit: document.getElementById('edit-unit').value,
                quantity: parseInt(document.getElementById('edit-quantity').value) || 0,
                status: document.getElementById('edit-status').value,
                location: document.getElementById('edit-location').value,
                fund_source: document.getElementById('edit-fund-source').value,
                date_received: document.getElementById('edit-date-received').value,
                date_checked: document.getElementById('edit-date-checked').value,
                _method: 'PUT',
            };
            fetch('{{ url("inventory-storage/update") }}/' + currentEditId, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(body),
            }).then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.success) {
                        toast('Item updated successfully.', 'success');
                        editModal.hide();
                        setTimeout(function () { location.reload(); }, 800);
                    } else { toast(res.message || 'Update failed.', 'error'); }
                }).catch(function () { toast('Network error.', 'error'); });
        });

        // ── Delete ────────────────────────────────────────────────────
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteItemModal'));
        var currentDeleteId = null;

        document.querySelectorAll('.btn-delete-item').forEach(function (btn) {
            btn.addEventListener('click', function () {
                currentDeleteId = this.dataset.id;
                document.getElementById('deleteItemName').textContent = this.dataset.name;
                deleteModal.show();
            });
        });

        document.getElementById('btn-confirm-delete').addEventListener('click', function () {
            if (!currentDeleteId) return;
            apiFetch('{{ url("inventory-storage/destroy") }}/' + currentDeleteId, 'DELETE')
                .then(function (res) {
                    if (res.success) {
                        toast('Item deleted.', 'success');
                        var row = document.querySelector('#inventoryTable tr[data-id="' + currentDeleteId + '"]');
                        if (row) {
                            var status = row.dataset.status;
                            row.remove();

                            // Update stats
                            var totalEl = document.getElementById('stat-total');
                            if (totalEl) totalEl.textContent = Math.max(0, parseInt(totalEl.textContent) - 1);
                            var statEl = document.getElementById('stat-' + status);
                            if (statEl) statEl.textContent = Math.max(0, parseInt(statEl.textContent) - 1);

                            // Check if empty
                            var remaining = document.querySelectorAll('#inventoryTable tbody tr.item-row');
                            if (remaining.length === 0) {
                                var emptyRow = document.getElementById('emptyInventoryRow');
                                if (emptyRow) emptyRow.style.display = 'table-row';
                            }
                        }
                        deleteModal.hide();
                    } else { toast(res.message || 'Delete failed.', 'error'); }
                }).catch(function () { toast('Network error.', 'error'); });
        });

        // ── School switcher (admin) ───────────────────────────────────
        var schoolSwitcher = document.getElementById('schoolSwitcher');
        if (schoolSwitcher) {
            schoolSwitcher.addEventListener('change', function () {
                apiFetch('{{ route("inventory-storage.set-school") }}', 'POST', { school_id: this.value })
                    .then(function (res) { if (res.success) location.reload(); })
                    .catch(function () { toast('Could not switch school.', 'error'); });
            });
        }

        // ── Session-based panel open ──────────────────────────────────
        @if(session('open_panel') === 'default-list')
            switchPanel('panel-default-list');
        @endif
if (window.location.hash === '#default-list') switchPanel('panel-default-list');
    </script>
</body>

</html>