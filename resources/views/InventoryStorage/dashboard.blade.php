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
            --body-bg: #eef2f5;
            --card-radius: 14px;
            --missing: #dc3545;
            --attention: #f59e0b;
            --working: #10b981;
            --card-shadow: 0 2px 8px rgba(0, 0, 0, .06), 0 0 1px rgba(0, 0, 0, .08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--body-bg);
            color: #1a2e2e;
            background-image: radial-gradient(circle at 20% 50%, rgba(21, 143, 143, .03) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(21, 143, 143, .04) 0%, transparent 40%);
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w);
            background: linear-gradient(180deg, #158f8f 0%, #0d7377 100%);
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
            border-right: none;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            padding: 32px 24px 20px;
        }

        .sidebar-brand .brand-icon {
            width: 42px;
            height: 42px;
            background: #ffffff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--teal);
            font-size: 1.15rem;
            flex-shrink: 0;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .1);
        }

        .sidebar-brand .brand-text {
            font-size: 1.1rem;
            font-weight: 700;
            line-height: 1.25;
            letter-spacing: .02em;
            color: #ffffff;
        }

        .sidebar-back-link {
            padding: 0 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, .15);
            margin-bottom: 10px;
        }

        .sidebar-back-link a {
            color: rgba(255, 255, 255, .85);
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
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: rgba(255, 255, 255, .65);
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
            padding: 11px 14px;
            color: rgba(255, 255, 255, .85);
            font-size: .88rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s ease;
            cursor: pointer;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            font-family: inherit;
            border-radius: 10px;
            position: relative;
        }

        .sidebar-nav .nav-link:hover {
            background: rgba(255, 255, 255, .15);
            color: #fff;
        }

        .sidebar-nav .nav-link.active {
            background: #ffffff;
            color: var(--teal-dark);
            box-shadow: 0 3px 12px rgba(0, 0, 0, .15);
        }

        .sidebar-nav .nav-link .nav-icon {
            width: 22px;
            text-align: center;
            font-size: 1rem;
            opacity: .85;
        }

        .sidebar-nav .nav-link.active .nav-icon {
            opacity: 1;
        }

        /* ── SCHOOL SWITCHER ── */
        .school-switcher {
            padding: 0 16px 16px;
            position: relative;
        }

        .school-switcher-label {
            font-size: .65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: rgba(255, 255, 255, .65);
            margin-bottom: 6px;
            display: block;
        }

        .school-picker-btn {
            width: 100%;
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 10px;
            padding: 9px 12px;
            color: #fff;
            font-size: .82rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all .2s;
            text-align: left;
        }

        .school-picker-btn:hover {
            background: rgba(255, 255, 255, .22);
            border-color: rgba(255, 255, 255, .4);
        }

        .school-picker-btn .school-picker-name {
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .school-picker-btn .chevron {
            font-size: .65rem;
            opacity: .7;
            transition: transform .2s;
            flex-shrink: 0;
        }

        .school-picker-btn.open .chevron {
            transform: rotate(180deg);
        }

        .school-picker-dropdown {
            display: none;
            position: absolute;
            top: calc(100% - 4px);
            left: 16px;
            right: 16px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .18), 0 2px 8px rgba(0, 0, 0, .1);
            z-index: 200;
            overflow: hidden;
        }

        .school-picker-dropdown.open {
            display: block;
        }

        .school-picker-search-wrap {
            padding: 10px 10px 8px;
            border-bottom: 1px solid #edf2f2;
        }

        .school-picker-search {
            width: 100%;
            border: 1.5px solid #dde8e8;
            border-radius: 8px;
            padding: 7px 10px 7px 32px;
            font-size: .8rem;
            font-family: inherit;
            color: #1a2e2e;
            outline: none;
            background: #f8fbfb url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%238aa0a0' stroke-width='2.5'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E") no-repeat 10px center;
            transition: border-color .2s;
        }

        .school-picker-search:focus {
            border-color: var(--teal);
        }

        .school-picker-list {
            max-height: 220px;
            overflow-y: auto;
            padding: 6px;
        }

        .school-picker-list::-webkit-scrollbar {
            width: 4px;
        }

        .school-picker-list::-webkit-scrollbar-thumb {
            background: #c5dede;
            border-radius: 4px;
        }

        .school-picker-item {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 10px;
            border-radius: 8px;
            cursor: pointer;
            font-size: .82rem;
            color: #2a4040;
            font-weight: 500;
            transition: background .15s;
        }

        .school-picker-item:hover {
            background: #f0f7f7;
        }

        .school-picker-item.active {
            background: rgba(21, 143, 143, .1);
            color: var(--teal-dark);
            font-weight: 700;
        }

        .school-picker-item .school-icon {
            width: 26px;
            height: 26px;
            border-radius: 6px;
            background: rgba(21, 143, 143, .1);
            color: var(--teal);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .72rem;
            flex-shrink: 0;
        }

        .school-picker-item.active .school-icon {
            background: var(--teal);
            color: #fff;
        }

        .school-picker-item .check {
            margin-left: auto;
            color: var(--teal);
            font-size: .75rem;
            display: none;
        }

        .school-picker-item.active .check {
            display: block;
        }

        .school-picker-empty {
            padding: 20px;
            text-align: center;
            color: #8aa0a0;
            font-size: .8rem;
        }

        .school-switcher-collapsed {
            display: none;
        }

        /* ── MAIN ── */
        .main-content {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: linear-gradient(135deg, #ffffff 0%, #f7fafa 100%);
            border-bottom: 1px solid #e0eaea;
            padding: 20px 32px 16px;
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
            transition: background .15s, transform .15s;
        }

        .btn-danger-sm:hover {
            background: #c82333;
            transform: translateY(-1px);
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
            transition: background .15s, border-color .15s, transform .15s;
        }

        .btn-edit-sm:hover {
            background: #d4eded;
            border-color: var(--teal);
            transform: translateY(-1px);
        }

        /* ── PAGE BODY ── */
        .page-body {
            padding: 28px 32px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .panel {
            display: none;
            flex: 1;
        }

        .panel.active {
            display: flex;
            flex-direction: column;
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
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease;
            display: flex;
            align-items: center;
            gap: 16px;
            border-left: 4px solid var(--teal);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, .08);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
            background: rgba(21, 143, 143, .1);
            color: var(--teal);
        }

        .stat-card .stat-info {
            flex: 1;
            min-width: 0;
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

        .stat-card.stat-missing {
            border-left-color: var(--missing);
        }

        .stat-card.stat-missing .stat-icon {
            background: rgba(220, 53, 69, .1);
            color: var(--missing);
        }

        .stat-card.stat-missing .stat-value {
            color: var(--missing);
        }

        .stat-card.stat-attention {
            border-left-color: var(--attention);
        }

        .stat-card.stat-attention .stat-icon {
            background: rgba(245, 158, 11, .1);
            color: var(--attention);
        }

        .stat-card.stat-attention .stat-value {
            color: var(--attention);
        }

        .stat-card.stat-working {
            border-left-color: var(--working);
        }

        .stat-card.stat-working .stat-icon {
            background: rgba(16, 185, 129, .1);
            color: var(--working);
        }

        .stat-card.stat-working .stat-value {
            color: var(--working);
        }

        /* ── INV CARD ── */
        .inv-card {
            background: #fff;
            border-radius: var(--card-radius);
            box-shadow: var(--card-shadow);
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
            background: linear-gradient(135deg, #f8fbfb 0%, #f0f7f7 100%);
        }

        .inv-card-header .title {
            font-size: .95rem;
            font-weight: 700;
            color: #1a2e2e;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .inv-card-header .title i {
            color: #fff;
            background: var(--teal);
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
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
            color: var(--teal-dark);
            padding: 12px 16px;
            background: linear-gradient(135deg, #f0f8f8 0%, #f8fbfb 100%);
            border-bottom: 2px solid #dbeaea;
            white-space: nowrap;
        }

        .inv-table thead th:first-child {
            border-left: 3px solid var(--teal);
        }

        .inv-table tbody tr {
            border-bottom: 1px solid #f0f5f5;
            transition: background .15s, transform .1s;
        }

        .inv-table tbody tr:last-child {
            border-bottom: none;
        }

        .inv-table tbody tr:hover {
            background: rgba(21, 143, 143, .03);
        }

        .inv-table tbody td {
            padding: 12px 16px;
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
            padding: 4px 12px;
            border-radius: 20px;
            transition: transform .15s;
        }

        .status-badge:hover {
            transform: scale(1.05);
        }

        .status-badge.missing {
            background: linear-gradient(135deg, #fde8ea, #fff0f1);
            color: #c0293a;
            border: 1px solid rgba(192, 41, 58, .15);
        }

        .status-badge.attention {
            background: linear-gradient(135deg, #fef3e2, #fffbf0);
            color: #b45309;
            border: 1px solid rgba(180, 83, 9, .15);
        }

        .status-badge.working {
            background: linear-gradient(135deg, #d1fae5, #ecfdf5);
            color: #065f46;
            border: 1px solid rgba(6, 95, 70, .15);
        }

        .status-badge.repair {
            background: linear-gradient(135deg, #e8e8ff, #f0f0ff);
            color: #3730a3;
            border: 1px solid rgba(55, 48, 163, .15);
        }

        .status-badge.defective {
            background: linear-gradient(135deg, #f3e8ff, #faf5ff);
            color: #6b21a8;
            border: 1px solid rgba(107, 33, 168, .15);
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

        /* ── IMAGE HOVER PREVIEW ── */
        #img-preview-overlay {
            position: fixed;
            z-index: 9999;
            pointer-events: none;
            display: none;
            background: #fff;
            padding: 4px;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            border: 1px solid #d4dede;
        }

        #img-preview-overlay img {
            max-width: 300px;
            max-height: 300px;
            border-radius: 4px;
            display: block;
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
            box-shadow: var(--card-shadow);
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
            background: linear-gradient(135deg, #f8fbfb 0%, #f0f7f7 100%);
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
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .2), 0 0 1px rgba(0, 0, 0, .1);
        }

        .modal-header {
            background: linear-gradient(180deg, #158f8f 0%, #0d7377 100%);
            color: #fff;
            padding: 20px 28px;
            border-bottom: none;
            position: relative;
        }

        .modal-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--teal), #1aadad, transparent);
        }

        .modal-header .modal-title {
            font-size: 1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-header .modal-title i {
            color: #fff;
            background: var(--teal);
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .75rem;
            box-shadow: 0 2px 8px rgba(21, 143, 143, .3);
        }

        .modal-body {
            padding: 28px;
            background: #fafcfc;
        }

        .modal-footer {
            background: linear-gradient(135deg, #f4f8f8 0%, #f8fbfb 100%);
            border-top: 1px solid #e5edef;
            padding: 16px 28px;
        }

        .modal-body .form-section-label {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--teal);
            margin-bottom: 14px;
            padding-bottom: 8px;
            border-bottom: 2px solid rgba(21, 143, 143, .1);
            display: flex;
            align-items: center;
            gap: 8px;
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
            border-radius: 10px;
            font-size: .83rem;
            color: #1a2e2e;
            padding: 9px 14px;
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(13, 115, 119, .1);
            background: #fff;
        }

        .btn-cancel {
            background: #fff;
            border: 1.5px solid #d4dede;
            color: #4a6060;
            font-size: .82rem;
            font-weight: 600;
            padding: 8px 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: all .18s;
        }

        .btn-cancel:hover {
            background: #f0f4f4;
            border-color: #afc4c4;
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
                padding: 24px 0 16px;
                justify-content: center;
            }

            .sidebar-brand .brand-text {
                display: none;
            }

            .sidebar-section-label,
            .nav-text,
            .school-switcher {
                display: none !important;
            }

            .school-switcher-collapsed {
                display: block !important;
                margin-bottom: 10px;
            }

            .sidebar-back-link {
                padding: 0;
                border-bottom: none;
            }

            .sidebar-back-link a {
                justify-content: center;
                padding: 12px 0;
            }

            .sidebar-nav {
                padding: 0 8px;
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
                <div class="brand-icon"><i class="fas fa-boxes"></i></div>
                <div class="brand-text">Inventory</div>
            </div>
            <div class="sidebar-back-link">
                <a href="{{ url('/dashboard') }}" title="Back to Dashboard">
                    <span class="nav-icon"><i class="fa-solid fa-arrow-left"></i></span>
                    <span class="nav-text">Back to Dashboard</span>
                </a>
            </div>

            {{-- School switcher (admin only) --}}
            @if(auth()->user()->role === 'admin' && $schools->count() > 1)
                <div class="school-switcher" id="schoolSwitcherWrap">
                    <span class="school-switcher-label">Active School</span>
                    <button class="school-picker-btn" id="schoolPickerBtn" type="button" aria-expanded="false">
                        <i class="fa-solid fa-school" style="font-size:.8rem;opacity:.8;flex-shrink:0;"></i>
                        <span class="school-picker-name"
                            id="schoolPickerName">{{ $school ? $school->school_name : 'Select school' }}</span>
                        <i class="fa-solid fa-chevron-down chevron"></i>
                    </button>
                    <div class="school-picker-dropdown" id="schoolPickerDropdown">
                        <div class="school-picker-search-wrap">
                            <input type="text" class="school-picker-search" id="schoolPickerSearch"
                                placeholder="Search schools..." autocomplete="off">
                        </div>
                        <div class="school-picker-list" id="schoolPickerList">
                            @foreach($schools as $s)
                                <div class="school-picker-item {{ $school && $school->id == $s->id ? 'active' : '' }}"
                                    data-id="{{ $s->id }}" data-name="{{ $s->school_name }}">
                                    <div class="school-icon"><i class="fa-solid fa-school"></i></div>
                                    <span>{{ $s->school_name }}</span>
                                    <i class="fa-solid fa-check check"></i>
                                </div>
                            @endforeach
                            <div class="school-picker-empty" id="schoolPickerEmpty" style="display:none;">No schools found
                            </div>
                        </div>
                    </div>
                </div>

                <ul class="sidebar-nav school-switcher-collapsed">
                    <li>
                        <button class="nav-link" data-bs-toggle="modal" data-bs-target="#schoolSwitcherModal"
                            title="Switch School">
                            <span class="nav-icon"><i class="fa-solid fa-school"></i></span>
                        </button>
                    </li>
                </ul>
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
                    <a href="{{ route('inventory-storage.export-pdf') }}" class="btn-outline-muted" id="btn-export"
                        style="display:none;">
                        <i class="fa-solid fa-file-pdf"></i> Export PDF
                    </a>
                    <button class="btn-primary-teal" data-bs-toggle="modal" data-bs-target="#addItemModal"
                        id="btn-add-item">
                        <i class="fa-solid fa-plus"></i> Add New Item
                    </button>
                </div>
            </div>

            <div class="page-body">
                <div class="panel active" id="panel-dashboard">

                    <div class="stat-grid">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fa-solid fa-cubes"></i></div>
                            <div class="stat-info">
                                <div class="stat-label">Total Items</div>
                                <div class="stat-value" id="stat-total">{{ $items->count() }}</div>
                            </div>
                        </div>
                        <div class="stat-card stat-missing">
                            <div class="stat-icon"><i class="fa-solid fa-circle-exclamation"></i></div>
                            <div class="stat-info">
                                <div class="stat-label">Missing</div>
                                <div class="stat-value" id="stat-missing">
                                    {{ $items->where('status', 'missing')->count() }}
                                </div>
                            </div>
                        </div>
                        <div class="stat-card stat-attention">
                            <div class="stat-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                            <div class="stat-info">
                                <div class="stat-label">Needs Attention</div>
                                <div class="stat-value" id="stat-needs_attention">
                                    {{ $items->where('status', 'needs_attention')->count() }}
                                </div>
                            </div>
                        </div>
                        <div class="stat-card stat-working">
                            <div class="stat-icon"><i class="fa-solid fa-circle-check"></i></div>
                            <div class="stat-info">
                                <div class="stat-label">Working</div>
                                <div class="stat-value" id="stat-working">
                                    {{ $items->where('status', 'working')->count() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="inv-card d-flex flex-column" style="flex: 1;">
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
                            </tbody>
                        </table>

                        <div id="emptyInventoryRow"
                            style="display: {{ $items->count() > 0 ? 'none' : 'flex' }}; flex: 1; flex-direction: column; align-items: center; justify-content: center; padding: 60px 40px; text-align: center;">
                            <div
                                style="width: 72px; height: 72px; border-radius: 50%; background: linear-gradient(135deg, rgba(21, 143, 143, .08), rgba(21, 143, 143, .15)); display: flex; align-items: center; justify-content: center; margin-bottom: 18px;">
                                <i class="fa-solid fa-boxes-stacked"
                                    style="font-size: 1.6rem; color: var(--teal); opacity: .7;"></i>
                            </div>
                            <div style="font-size: .95rem; font-weight: 600; color: #3a5050; margin-bottom: 6px;">No
                                inventory items yet</div>
                            <div style="font-size: .82rem; color: #8aa0a0;">Click <strong style="color: var(--teal);">+
                                    Add New Item</strong> to start tracking your supplies.</div>
                        </div>
                    </div>

                </div>

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
                            <div class="compliance-score-box" style="border-left: 4px solid var(--teal);">
                                <div class="cs-label"><i class="fa-solid fa-chart-pie"
                                        style="margin-right:6px;opacity:.6;"></i>Overall Compliance</div>
                                <div class="cs-value {{ $colorClass($pctAll) }}" id="score-val-all">{{ $pctAll }}%</div>
                                <div class="cs-sub" id="score-sub-all">{{ $haveA + $haveB }} /
                                    {{ count($itemsA) + count($itemsB) }} items
                                    sourced
                                </div>
                                <div class="progress-thin">
                                    <div class="progress-thin-bar {{ $barClass($pctAll) }}" id="score-bar-all"
                                        style="width:{{ $pctAll }}%"></div>
                                </div>
                            </div>
                            <div class="compliance-score-box" style="border-left: 4px solid #f59e0b;">
                                <div class="cs-label"><i class="fa-solid fa-kit-medical"
                                        style="margin-right:6px;opacity:.6;"></i>Emergency Supplies</div>
                                <div class="cs-value {{ $colorClass($pctA) }}" id="score-val-a">{{ $pctA }}%</div>
                                <div class="cs-sub" id="score-sub-a">{{ $haveA }} / {{ count($itemsA) }} sourced</div>
                                <div class="progress-thin">
                                    <div class="progress-thin-bar {{ $barClass($pctA) }}" id="score-bar-a"
                                        style="width:{{ $pctA }}%"></div>
                                </div>
                            </div>
                            <div class="compliance-score-box" style="border-left: 4px solid #10b981;">
                                <div class="cs-label"><i class="fa-solid fa-life-ring"
                                        style="margin-right:6px;opacity:.6;"></i>Rescue Supplies</div>
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
                            Emergency Supplies &amp; Equipment
                            <span class="tab-badge">{{ count($itemsA) }} items</span>
                        </button>
                        <button class="section-tab" data-filter="b" id="tab-b">
                            Response &amp; Rescue Supplies
                            <span class="tab-badge">{{ count($itemsB) }} items</span>
                        </button>
                    </div>

                    <!-- ── SECTION A ── -->
                    <div class="section-card" id="section-a" data-section="a">
                        <div class="section-card-header">
                            <div class="section-title">
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
                                                @php
                                                    $depedVals = ['Division Office', 'Regional Office', 'Central Office', 'School MOOE'];
                                                    $partnerVals = ['PTA', 'FPTA', 'City Government (SEF)'];
                                                    
                                                    $isDepedOther = $row && $row->source == 'deped' && $row->source_detail && !in_array($row->source_detail, $depedVals);
                                                    $isPartnerOther = $row && $row->source == 'partner' && $row->source_detail && !in_array($row->source_detail, $partnerVals);
                                                    
                                                    $otherVal = '';
                                                    if ($isDepedOther || $isPartnerOther) $otherVal = $row->source_detail;
                                                @endphp
                                                <select
                                                    class="form-select-sm deped-options {{ ($row && $row->source == 'deped') ? '' : 'd-none' }}">
                                                    <option value="">Select DepEd Source</option>
                                                    <option value="Division Office" {{ $row && $row->source_detail == 'Division Office' ? 'selected' : '' }}>Division Office</option>
                                                    <option value="Regional Office" {{ $row && $row->source_detail == 'Regional Office' ? 'selected' : '' }}>Regional Office</option>
                                                    <option value="Central Office" {{ $row && $row->source_detail == 'Central Office' ? 'selected' : '' }}>Central Office</option>
                                                    <option value="School MOOE" {{ $row && $row->source_detail == 'School MOOE' ? 'selected' : '' }}>School MOOE</option>
                                                    <option value="Others (Please Specify)" {{ $isDepedOther ? 'selected' : '' }}>Others (Please Specify)</option>
                                                </select>
                                                <select
                                                    class="form-select-sm partner-options {{ ($row && $row->source == 'partner') ? '' : 'd-none' }}">
                                                    <option value="">Select Partner Source</option>
                                                    <option value="PTA" {{ $row && $row->source_detail == 'PTA' ? 'selected' : '' }}>PTA</option>
                                                    <option value="FPTA" {{ $row && $row->source_detail == 'FPTA' ? 'selected' : '' }}>FPTA</option>
                                                    <option value="City Government (SEF)" {{ $row && $row->source_detail == 'City Government (SEF)' ? 'selected' : '' }}>City Government (SEF)</option>
                                                    <option value="Others (Please Specify)" {{ $isPartnerOther ? 'selected' : '' }}>Others (Please Specify)</option>
                                                </select>
                                                <input type="text" class="form-control form-control-sm other-source-input mt-1 {{ ($isDepedOther || $isPartnerOther) ? '' : 'd-none' }}" placeholder="Please specify..." value="{{ $otherVal }}">
                                            </div>
                                        </td>
                                        <td><input type="date" class="form-control-date"
                                                value="{{ $row && $row->date_checked ? $row->date_checked->format('Y-m-d') : date('Y-m-d') }}">
                                        </td>
                                        <td><textarea class="remarks-input"
                                                rows="2">{{ $row ? $row->remarks : '' }}</textarea></td>
                                    </tr>
                                @endforeach
                                <tr id="no-search-a" style="display:none;">
                                    <td colspan="6"
                                        style="text-align:center;padding:40px;color:#718096;font-size:.85rem;">
                                        <i class="fa-solid fa-magnifying-glass"
                                            style="font-size:1.6rem;display:block;margin-bottom:10px;opacity:.4;"></i>
                                        No items found matching your search.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="section-save-bar">
                            <span class="save-status" id="save-status-a"></span>
                            <button class="btn-primary-teal btn-save-section" data-section="A" id="btn-save-a">
                                <i class="fa-solid fa-floppy-disk"></i> Save Section
                            </button>
                        </div>
                    </div><!-- /section-a -->

                    <!-- ── SECTION B ── -->
                    <div class="section-card" id="section-b" data-section="b">
                        <div class="section-card-header">
                            <div class="section-title">
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
                                                @php
                                                    $depedVals = ['Division Office', 'Regional Office', 'Central Office', 'School MOOE'];
                                                    $partnerVals = ['PTA', 'FPTA', 'City Government (SEF)'];
                                                    
                                                    $isDepedOther = $row && $row->source == 'deped' && $row->source_detail && !in_array($row->source_detail, $depedVals);
                                                    $isPartnerOther = $row && $row->source == 'partner' && $row->source_detail && !in_array($row->source_detail, $partnerVals);
                                                    
                                                    $otherVal = '';
                                                    if ($isDepedOther || $isPartnerOther) $otherVal = $row->source_detail;
                                                @endphp
                                                <select
                                                    class="form-select-sm deped-options {{ ($row && $row->source == 'deped') ? '' : 'd-none' }}">
                                                    <option value="">Select DepEd Source</option>
                                                    <option value="Division Office" {{ $row && $row->source_detail == 'Division Office' ? 'selected' : '' }}>Division Office</option>
                                                    <option value="Regional Office" {{ $row && $row->source_detail == 'Regional Office' ? 'selected' : '' }}>Regional Office</option>
                                                    <option value="Central Office" {{ $row && $row->source_detail == 'Central Office' ? 'selected' : '' }}>Central Office</option>
                                                    <option value="School MOOE" {{ $row && $row->source_detail == 'School MOOE' ? 'selected' : '' }}>School MOOE</option>
                                                    <option value="Others (Please Specify)" {{ $isDepedOther ? 'selected' : '' }}>Others (Please Specify)</option>
                                                </select>
                                                <select
                                                    class="form-select-sm partner-options {{ ($row && $row->source == 'partner') ? '' : 'd-none' }}">
                                                    <option value="">Select Partner Source</option>
                                                    <option value="PTA" {{ $row && $row->source_detail == 'PTA' ? 'selected' : '' }}>PTA</option>
                                                    <option value="FPTA" {{ $row && $row->source_detail == 'FPTA' ? 'selected' : '' }}>FPTA</option>
                                                    <option value="City Government (SEF)" {{ $row && $row->source_detail == 'City Government (SEF)' ? 'selected' : '' }}>City Government (SEF)</option>
                                                    <option value="Others (Please Specify)" {{ $isPartnerOther ? 'selected' : '' }}>Others (Please Specify)</option>
                                                </select>
                                                <input type="text" class="form-control form-control-sm other-source-input mt-1 {{ ($isDepedOther || $isPartnerOther) ? '' : 'd-none' }}" placeholder="Please specify..." value="{{ $otherVal }}">
                                            </div>
                                        </td>
                                        <td><input type="date" class="form-control-date"
                                                value="{{ $row && $row->date_checked ? $row->date_checked->format('Y-m-d') : date('Y-m-d') }}">
                                        </td>
                                        <td><textarea class="remarks-input"
                                                rows="2">{{ $row ? $row->remarks : '' }}</textarea></td>
                                    </tr>
                                @endforeach
                                <tr id="no-search-b" style="display:none;">
                                    <td colspan="6"
                                        style="text-align:center;padding:40px;color:#718096;font-size:.85rem;">
                                        <i class="fa-solid fa-magnifying-glass"
                                            style="font-size:1.6rem;display:block;margin-bottom:10px;opacity:.4;"></i>
                                        No items found matching your search.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="section-save-bar">
                            <span class="save-status" id="save-status-b"></span>
                            <button class="btn-primary-teal btn-save-section" data-section="B" id="btn-save-b">
                                <i class="fa-solid fa-floppy-disk"></i> Save Section
                            </button>
                        </div>
                    </div><!-- /section-b -->

                </div><!-- /panel-default-list -->


                <!-- ═══════════════════════════════════════
                 PANEL: MONTHLY SUMMARY
            ════════════════════════════════════════ -->
                <div class="panel" id="panel-monthly-summary">
                    @php
                        $now = \Carbon\Carbon::now();
                        $monthStart = $now->copy()->startOfMonth();
                        $monthEnd = $now->copy()->endOfMonth();

                        $missingItems = $items->where('status', 'missing');
                        $attentionItems = $items->where('status', 'needs_attention');
                        $newlyAdded = $items->filter(fn($i) => $i->created_at && \Carbon\Carbon::parse($i->created_at)->between($monthStart, $monthEnd));

                        $totalA = count($itemsA);
                        $totalB = count($itemsB);
                        $haveAc = $savedA->where('has_item', true)->count();
                        $haveBc = $savedB->where('has_item', true)->count();
                        $pctAc = $totalA > 0 ? round(($haveAc / $totalA) * 100) : 0;
                        $pctBc = $totalB > 0 ? round(($haveBc / $totalB) * 100) : 0;
                        $pctCus = 100;

                        $byFund = $items->groupBy(fn($i) => $i->fund_source ?: 'Unspecified')
                            ->map(fn($g) => $g->count())
                            ->sortDesc();
                    @endphp

                    <!-- Month label -->
                    <div style="margin-bottom:20px;">
                        <div
                            style="font-size:.72rem;font-weight:700;letter-spacing:.1em;color:#4a6060;text-transform:uppercase;">
                            Monthly Summary Report &middot; {{ $now->format('F Y') }}
                        </div>
                        <div style="font-size:.75rem;color:#2c3e50;margin-top:3px;font-weight:600;">
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
                            <div
                                style="font-size:.7rem;font-weight:700;letter-spacing:.08em;color:#2c3e50;text-transform:uppercase;margin-bottom:16px;">
                                <i class="fa-solid fa-layer-group" style="margin-right:6px;"></i>Category Completion
                            </div>
                            <div style="display:flex;flex-direction:column;gap:14px;">
                                @php
                                    $catRows = [
                                        ['Emergency Supplies', $haveAc, $totalA, $pctAc, '#158f8f'],
                                        ['Rescue Supplies', $haveBc, $totalB, $pctBc, '#1a7f5a'],
                                        ['Custom / Ad-hoc Items', $items->count(), $items->count(), $pctCus, '#2a6e8a'],
                                    ];
                                @endphp
                                @foreach($catRows as [$label, $have, $total, $pct, $color])
                                    <div>
                                        <div
                                            style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:6px;">
                                            <span style="color:#2c3e50;font-weight:600;">{{ $label }}</span>
                                            <span
                                                style="color:#5a738e;font-size:.75rem;font-weight:600;">{{ $have }}/{{ $total }}</span>
                                        </div>
                                        <div style="background:#e8ecec;border-radius:4px;height:7px;overflow:hidden;">
                                            <div
                                                style="height:100%;width:{{ $pct }}%;background:{{ $color }};border-radius:4px;">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="inv-card" style="padding:22px 24px;">
                            <div
                                style="font-size:.7rem;font-weight:700;letter-spacing:.08em;color:#2c3e50;text-transform:uppercase;margin-bottom:16px;">
                                <i class="fa-solid fa-coins" style="margin-right:6px;"></i>Items by Fund Source
                            </div>
                            <div style="display:flex;flex-direction:column;gap:9px;">
                                @forelse($byFund as $source => $cnt)
                                    <div
                                        style="display:flex;justify-content:space-between;align-items:center;padding:9px 14px;background:#f5f7f8;border:1px solid #e2e8f0;border-radius:8px;">
                                        <span style="font-size:.83rem;color:#2c3e50;font-weight:600;">{{ $source }}</span>
                                        <span style="font-size:.78rem;color:#5a738e;font-weight:700;">{{ $cnt }}
                                            {{ $cnt === 1 ? 'item' : 'items' }}</span>
                                    </div>
                                @empty
                                    <p style="color:#718096;font-size:.82rem;">No items recorded.</p>
                                @endforelse
                            </div>
                        </div>

                    </div>

                    <!-- Missing items -->
                    @if($missingItems->count() > 0)
                        <div class="inv-card" style="margin-bottom:20px;">
                            <div class="inv-card-header">
                                <div class="title" style="color:var(--missing);"><i
                                        class="fa-solid fa-circle-exclamation"></i> Missing Items &mdash; Requires Immediate
                                    Action</div>
                            </div>
                            <table class="inv-table">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Location</th>
                                        <th>Fund Source</th>
                                        <th>Last Checked</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($missingItems as $item)
                                        <tr>
                                            <td class="item-name-cell">{{ $item->item_name }}</td>
                                            <td>{{ $item->location ?: '&ndash;' }}</td>
                                            <td>{{ $item->fund_source ?: '&ndash;' }}</td>
                                            <td class="muted-val">
                                                {{ $item->date_checked ? \Carbon\Carbon::parse($item->date_checked)->format('M d, Y') : '&ndash;' }}
                                            </td>
                                            <td><span class="status-badge missing"><i
                                                        class="fa-solid fa-circle-exclamation"></i> Missing</span></td>
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
                                <div class="title" style="color:var(--attention);"><i
                                        class="fa-solid fa-triangle-exclamation"></i> Needs Attention &mdash; Follow-up
                                    Recommended</div>
                            </div>
                            <table class="inv-table">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Qty</th>
                                        <th>Location</th>
                                        <th>Fund Source</th>
                                        <th>Last Checked</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attentionItems as $item)
                                        <tr>
                                            <td class="item-name-cell">{{ $item->item_name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ $item->location ?: '&ndash;' }}</td>
                                            <td>{{ $item->fund_source ?: '&ndash;' }}</td>
                                            <td class="muted-val">
                                                {{ $item->date_checked ? \Carbon\Carbon::parse($item->date_checked)->format('M d, Y') : '&ndash;' }}
                                            </td>
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
                                <span
                                    style="font-size:.72rem;color:#5a738e;font-weight:500;margin-left:8px;">{{ $now->format('F Y') }}</span>
                            </div>
                        </div>
                        @if($newlyAdded->count() > 0)
                            <table class="inv-table">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Qty</th>
                                        <th>Location</th>
                                        <th>Fund Source</th>
                                        <th>Date Received</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($newlyAdded as $item)
                                        <tr>
                                            <td class="item-name-cell">{{ $item->item_name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ $item->location ?: '&ndash;' }}</td>
                                            <td>{{ $item->fund_source ?: '&ndash;' }}</td>
                                            <td class="muted-val">
                                                {{ $item->date_received ? \Carbon\Carbon::parse($item->date_received)->format('M d, Y') : '&ndash;' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div style="text-align:center;padding:32px;color:#718096;font-size:.85rem;">
                                <i class="fa-solid fa-inbox"
                                    style="font-size:1.6rem;display:block;margin-bottom:10px;opacity:.4;"></i>
                                No new items added in {{ $now->format('F Y') }}.
                            </div>
                        @endif
                    </div>

                </div><!-- /panel-monthly-summary -->

            </div><!-- /page-body -->
        </div><!-- /main-content -->
    </div><!-- /layout -->


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
                        <div class="form-section-label"><i class="fa-solid fa-cube"></i> Item Details</div>
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
                        </div>
                        <div class="form-section-label" style="margin-top:24px;"><i
                                class="fa-solid fa-money-bill-wave"></i> Funding & Dates</div>
                        <div class="row g-3">
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
                    <div class="form-section-label"><i class="fa-solid fa-cube"></i> Item Details</div>
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
                    </div>
                    <div class="form-section-label" style="margin-top:24px;"><i class="fa-solid fa-money-bill-wave"></i>
                        Funding & Dates</div>
                    <div class="row g-3">
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
    <div class="modal fade" id="deleteItemModal" tabindex="-1" aria-labelledby="deleteItemModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteItemModalLabel"><i class="fa-solid fa-triangle-exclamation"
                            style="color: var(--missing);"></i> Delete Item</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to delete "<strong id="deleteItemName"></strong>"?<br><br>This
                        action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-danger-sm py-2 px-3" id="btn-confirm-delete"><i
                            class="fa-solid fa-trash"></i> Delete Item</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ── SCHOOL SWITCHER MODAL (collapsed sidebar) ── -->
    @if(auth()->user()->role === 'admin' && $schools->count() > 1)
        <div class="modal fade" id="schoolSwitcherModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa-solid fa-school"></i> Select Active School</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="padding:16px;">
                        <div style="position:relative;margin-bottom:10px;">
                            <i class="fa-solid fa-magnifying-glass"
                                style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#8aa0a0;font-size:.8rem;"></i>
                            <input type="text" id="modalSchoolSearch" placeholder="Search schools..."
                                style="width:100%;border:1.5px solid #dde8e8;border-radius:10px;padding:8px 12px 8px 34px;font-size:.83rem;font-family:inherit;outline:none;color:#1a2e2e;transition:border-color .2s;"
                                autocomplete="off">
                        </div>
                        <div id="modalSchoolList"
                            style="max-height:280px;overflow-y:auto;display:flex;flex-direction:column;gap:4px;">
                            @foreach($schools as $s)
                                <div class="modal-school-item {{ $school && $school->id == $s->id ? 'modal-school-active' : '' }}"
                                    data-id="{{ $s->id }}" data-name="{{ $s->school_name }}"
                                    style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;cursor:pointer;transition:background .15s;border:1.5px solid transparent;">
                                    <div
                                        style="width:32px;height:32px;border-radius:8px;background:rgba(21,143,143,.1);color:var(--teal);display:flex;align-items:center;justify-content:center;font-size:.8rem;flex-shrink:0;">
                                        <i class="fa-solid fa-school"></i>
                                    </div>
                                    <span
                                        style="font-size:.85rem;font-weight:500;color:#2a4040;flex:1;">{{ $s->school_name }}</span>
                                    <i class="fa-solid fa-check"
                                        style="color:var(--teal);font-size:.75rem;{{ $school && $school->id == $s->id ? '' : 'display:none;' }}"></i>
                                </div>
                            @endforeach
                            <div id="modalSchoolEmpty"
                                style="display:none;padding:24px;text-align:center;color:#8aa0a0;font-size:.83rem;">No
                                schools found</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- ── TOAST CONTAINER ── -->
    <div class="toast-wrap" id="toastWrap"></div>

    <!-- ── IMAGE HOVER PREVIEW ── -->
    <div id="img-preview-overlay">
        <img id="img-preview-img" src="" alt="Preview">
    </div>

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
            var btnExport = document.getElementById('btn-export');
            var btnAddItem = document.getElementById('btn-add-item');
            var isMonthly = panelId === 'panel-monthly-summary';
            if (btnExport) btnExport.style.display = isMonthly ? '' : 'none';
            if (btnAddItem) btnAddItem.style.display = isMonthly ? 'none' : '';
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
                var visibleCount = 0;
                document.querySelectorAll('#table-' + s + ' tbody tr.compliance-row').forEach(function (row) {
                    var match = row.textContent.toLowerCase().includes(q);
                    row.style.display = match ? '' : 'none';
                    if (match) visibleCount++;
                });
                var noDataRow = document.getElementById('no-search-' + s);
                if (noDataRow) noDataRow.style.display = (visibleCount === 0) ? 'table-row' : 'none';
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
        document.querySelectorAll('.source-select-wrap').forEach(function (wrap) {
            var srcSel = wrap.querySelector('.source-select');
            var deped = wrap.querySelector('.deped-options');
            var partner = wrap.querySelector('.partner-options');
            var otherInput = wrap.querySelector('.other-source-input');

            function toggleOtherInput() {
                if (srcSel.value === 'deped' && deped.value === 'Others (Please Specify)') {
                    otherInput.classList.remove('d-none');
                } else if (srcSel.value === 'partner' && partner.value === 'Others (Please Specify)') {
                    otherInput.classList.remove('d-none');
                } else {
                    otherInput.classList.add('d-none');
                }
            }

            srcSel.addEventListener('change', function () {
                deped.classList.add('d-none'); 
                partner.classList.add('d-none');
                if (this.value === 'deped') deped.classList.remove('d-none');
                if (this.value === 'partner') partner.classList.remove('d-none');
                toggleOtherInput();
            });

            if (deped) deped.addEventListener('change', toggleOtherInput);
            if (partner) partner.addEventListener('change', toggleOtherInput);
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
                    
                    if (detail === 'Others (Please Specify)') {
                        var otherInput = wrap.querySelector('.other-source-input');
                        if (otherInput && otherInput.value.trim() !== '') {
                            detail = otherInput.value.trim();
                        }
                    }
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
                                if (emptyRow) emptyRow.style.display = 'flex';
                            }
                        }
                        deleteModal.hide();
                    } else { toast(res.message || 'Delete failed.', 'error'); }
                }).catch(function () { toast('Network error.', 'error'); });
        });

        // ── School switcher (admin) ───────────────────────────────────
        function switchSchool(schoolId) {
            apiFetch('{{ route("inventory-storage.set-school") }}', 'POST', { school_id: schoolId })
                .then(function (res) { if (res.success) location.reload(); })
                .catch(function () { toast('Could not switch school.', 'error'); });
        }

        // Sidebar custom picker
        var pickerBtn = document.getElementById('schoolPickerBtn');
        var pickerDropdown = document.getElementById('schoolPickerDropdown');
        var pickerSearch = document.getElementById('schoolPickerSearch');
        var pickerList = document.getElementById('schoolPickerList');
        var pickerEmpty = document.getElementById('schoolPickerEmpty');

        if (pickerBtn) {
            // Toggle dropdown
            pickerBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                var isOpen = pickerDropdown.classList.toggle('open');
                pickerBtn.classList.toggle('open', isOpen);
                pickerBtn.setAttribute('aria-expanded', isOpen);
                if (isOpen) {
                    pickerSearch.value = '';
                    filterPicker('');
                    setTimeout(function () { pickerSearch.focus(); }, 50);
                }
            });

            // Search
            pickerSearch.addEventListener('input', function () {
                filterPicker(this.value.toLowerCase());
            });

            function filterPicker(q) {
                var items = pickerList.querySelectorAll('.school-picker-item');
                var visible = 0;
                items.forEach(function (item) {
                    var match = item.dataset.name.toLowerCase().includes(q);
                    item.style.display = match ? '' : 'none';
                    if (match) visible++;
                });
                pickerEmpty.style.display = visible === 0 ? '' : 'none';
            }

            // Select school
            pickerList.addEventListener('click', function (e) {
                var item = e.target.closest('.school-picker-item');
                if (!item) return;
                if (item.classList.contains('active')) {
                    pickerDropdown.classList.remove('open');
                    pickerBtn.classList.remove('open');
                    return;
                }
                switchSchool(item.dataset.id);
            });

            // Close on outside click
            document.addEventListener('click', function (e) {
                if (!document.getElementById('schoolSwitcherWrap').contains(e.target)) {
                    pickerDropdown.classList.remove('open');
                    pickerBtn.classList.remove('open');
                }
            });
        }

        // Modal picker (collapsed sidebar)
        var modalSchoolSearch = document.getElementById('modalSchoolSearch');
        var modalSchoolList = document.getElementById('modalSchoolList');
        var modalSchoolEmpty = document.getElementById('modalSchoolEmpty');

        if (modalSchoolSearch) {
            modalSchoolSearch.addEventListener('input', function () {
                var q = this.value.toLowerCase();
                var items = modalSchoolList.querySelectorAll('.modal-school-item');
                var visible = 0;
                items.forEach(function (item) {
                    var match = item.dataset.name.toLowerCase().includes(q);
                    item.style.display = match ? '' : 'none';
                    if (match) visible++;
                });
                modalSchoolEmpty.style.display = visible === 0 ? '' : 'none';
            });

            // Hover styles
            modalSchoolList.addEventListener('mouseover', function (e) {
                var item = e.target.closest('.modal-school-item');
                if (item && !item.classList.contains('modal-school-active')) item.style.background = '#f0f7f7';
            });
            modalSchoolList.addEventListener('mouseout', function (e) {
                var item = e.target.closest('.modal-school-item');
                if (item && !item.classList.contains('modal-school-active')) item.style.background = '';
            });

            // Click to switch
            modalSchoolList.addEventListener('click', function (e) {
                var item = e.target.closest('.modal-school-item');
                if (!item) return;
                switchSchool(item.dataset.id);
            });

            // Style active items
            document.querySelectorAll('.modal-school-active').forEach(function (item) {
                item.style.background = 'rgba(21,143,143,.08)';
                item.style.borderColor = 'rgba(21,143,143,.2)';
            });

            // Clear search when modal opens
            var schoolModal = document.getElementById('schoolSwitcherModal');
            if (schoolModal) {
                schoolModal.addEventListener('show.bs.modal', function () {
                    modalSchoolSearch.value = '';
                    modalSchoolList.querySelectorAll('.modal-school-item').forEach(function (i) { i.style.display = ''; });
                    modalSchoolEmpty.style.display = 'none';
                });
            }
        }

        // ── Session-based panel open ──────────────────────────────────
        @if(session('open_panel') === 'default-list')
            switchPanel('panel-default-list');
        @endif
        if (window.location.hash === '#default-list') switchPanel('panel-default-list');

        // ── Image Hover Preview ───────────────────────────────────────
        var previewOverlay = document.getElementById('img-preview-overlay');
        var previewImg = document.getElementById('img-preview-img');

        document.querySelectorAll('.item-img').forEach(function (img) {
            img.addEventListener('mouseenter', function (e) {
                previewImg.src = this.src;
                previewOverlay.style.display = 'block';
            });
            img.addEventListener('mousemove', function (e) {
                var offsetX = 15;
                var offsetY = 15;
                var x = e.clientX + offsetX;
                var y = e.clientY + offsetY;

                // Prevent going off screen (assuming max width/height ~320px)
                if (x + 320 > window.innerWidth) x = e.clientX - 320 - offsetX;
                if (y + 320 > window.innerHeight) y = e.clientY - 320 - offsetY;

                previewOverlay.style.left = x + 'px';
                previewOverlay.style.top = y + 'px';
            });
            img.addEventListener('mouseleave', function () {
                previewOverlay.style.display = 'none';
            });
        });
    </script>
</body>

</html>