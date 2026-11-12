{{-- resources/views/typhoon/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Evacuation Monitoring')
@section('hide_main_nav', '1')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --bg-dark: #0a1128;
        --card-bg: #ffffff;
        --card-header-bg: #0f2154ff;
        --accent-blue: #00d2ff;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --glass-border: rgba(0, 0, 0, 0.05);
        --font-display: 'Sora', sans-serif;
        --font-body: 'Inter', sans-serif;
    }

    .search-bar-container input::placeholder {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    .search-bar-container input:focus {
        background-color: rgba(255, 255, 255, 0.15) !important;
        border-color: rgba(255, 255, 255, 0.5) !important;
        color: white !important;
        box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.1) !important;
        outline: none;
    }
    .search-bar-container i {
        color: rgba(255, 255, 255, 0.7) !important;
    }

    body {
        background-color: var(--bg-dark) !important;
        background-image: radial-gradient(circle at 50% 50%, #112240 0%, #0a1128 100%);
        color: var(--text-dark);
        font-family: var(--font-body);
    }

    h1, h2, h3, h4, h5, .card-header-custom, .stat-value, .fw-bold {
        font-family: var(--font-display);
        letter-spacing: 0.5px;
    }

    /* ── Full-screen context for dashboard ── */
    html {
        font-size: 80%;
    }
    html, body {
        height: 100%;
    }
    body > div,
    body > div > main {
        height: 100%;
    }
    /* Override app layout's py-4 padding for this page */
    main.py-4 {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

    .container-fluid {
        padding: 2rem;
    }

    .dashboard-card {
        background: var(--card-bg);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        transition: transform 0.2s ease;
        height: 100%;
        color: var(--text-dark);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .card-header-custom {
        background: var(--card-header-bg);
        color: #ffffff;
        padding: 1rem 1.5rem;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        border-bottom: 2px solid rgba(0,0,0,0.1);
    }

    .card-header-custom i {
        color: var(--accent-blue);
        margin-right: 10px;
        font-size: 1.1rem;
    }

    .occupancy-filter-btn {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.35);
        color: #ffffff;
        font-size: 0.75rem;
        letter-spacing: 0.4px;
        text-transform: uppercase;
    }

    .occupancy-filter-btn:hover,
    .occupancy-filter-btn:focus {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    .occupancy-filter-menu {
        min-width: 230px;
        border: 1px solid #dbe7f5;
        box-shadow: 0 12px 24px rgba(15, 33, 84, 0.15);
        padding: 0.75rem;
    }

    .occupancy-chart-panel {
        flex: 1 1 auto;
        min-height: 0;
    }

    .occupancy-chart-scroll {
        min-width: 100%;
        height: 100%;
        position: relative;
    }

    .occupancy-summary {
        margin-top: 0.35rem !important;
    }

    .stat-value {
        font-size: 2.25rem;
        font-weight: 800;
        color: var(--text-dark);
    }

    .stat-label {
        color: var(--text-muted);
        font-size: 0.9rem;
        font-weight: 500;
    }

    .badge-custom {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
    }

    .table-custom {
        color: var(--text-dark);
    }

    .table-custom thead tr {
        background: #f8fafc;
    }

    .table-custom th {
        color: var(--text-muted);
        border-bottom: 1px solid #e2e8f0;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        font-weight: 600;
        padding: 1rem;
    }

    .table-custom td {
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        padding: 1rem;
    }

    .btn-action {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: var(--text-dark);
        transition: all 0.2s ease;
    }

    .btn-action:hover {
        background: var(--accent-blue);
        color: white;
        border-color: var(--accent-blue);
    }

    h1, h2, h3, h5 {
        color: #ffffff !important;
    }

    .dashboard-card h1, .dashboard-card h2, .dashboard-card h3, .dashboard-card h5, .dashboard-card .h3 {
        color: var(--text-dark) !important;
    }

    .text-muted {
        color: var(--text-muted) !important;
    }

    @keyframes typhoonSpin {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }

    /* ====================================================
     * 70/30 HYBRID MOBILE APPROACH â€” Typhoon Dashboard
     * Desktop layout preserved. Minimal tweaks only:
     *  1. Scrollable tables
     *  2. Slightly larger button tap targets
     *  3. Stack form columns in modals/forms
     * ==================================================== */
    /* Triggered by 1024px viewport lock â€” desktop layout preserved but mobile enhancements active */
    @media (max-width: 1024.1px) {
        .table-responsive {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
        }
        .table-custom {
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .btn:not(.btn-sm):not(.btn-xs):not(.btn-action) {
            min-height: 40px;
            padding-top: 0.45rem !important;
            padding-bottom: 0.45rem !important;
        }
        form .row > [class*="col-md-"],
        form .row > [class*="col-sm-"],
        .modal-body .row > [class*="col-md-"],
        .modal-body .row > [class*="col-sm-"] {
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }
    }

    /* Evacuation Centers table UI */
    .evac-panel-schools {
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.95);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(15, 33, 84, 0.12);
        color: var(--text-dark);
    }

    .evac-panel-schools .evac-panel-header {
        min-height: 62px;
        display: flex;
        align-items: center;
        gap: 0.7rem;
        padding: 1rem 1.25rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        color: #0f2154;
        font-family: var(--font-display);
        font-size: 0.95rem;
        font-weight: 800;
        letter-spacing: 0.35px;
    }

    .evac-panel-schools .evac-panel-header > i {
        color: #0284c7;
        font-size: 1.05rem;
    }

    .evac-schools-table-wrap {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .evac-schools-table {
        width: 100%;
        min-width: 680px;
        border-collapse: separate;
        border-spacing: 0;
        color: #1e293b;
        font-family: var(--font-body);
    }

    .evac-schools-table th {
        padding: 0.8rem 1.25rem;
        background: #f8fafc;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.75px;
        text-align: left;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .evac-schools-table th:not(.th-name),
    .evac-schools-table td:not(.td-name) {
        text-align: center;
    }

    .evac-schools-table td {
        height: 64px;
        padding: 0.85rem 1.25rem;
        background: #ffffff;
        border-bottom: 1px solid #eef2f7;
        font-size: 0.86rem;
        vertical-align: middle;
        transition: background-color 0.18s ease;
    }

    .evac-schools-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .evac-school-row {
        cursor: pointer;
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .evac-school-row:hover td {
        background: #f0f9ff;
    }

    .evac-school-row:focus-within td,
    .evac-cluster-row td {
        background: #fffbeb;
    }

    .evac-school-name {
        display: inline-block;
        max-width: 360px;
        overflow: hidden;
        color: #0f172a;
        font-weight: 700;
        text-overflow: ellipsis;
        vertical-align: middle;
        white-space: nowrap;
    }

    .evac-cluster-pin {
        display: inline-flex;
        width: 24px;
        height: 24px;
        align-items: center;
        justify-content: center;
        margin-right: 0.45rem;
        border-radius: 50%;
        background: #fef3c7;
        color: #d97706;
        font-size: 0.7rem;
        vertical-align: middle;
    }

    .td-num {
        color: #0f2154;
        font-family: var(--font-display);
        font-size: 1rem !important;
        font-weight: 800;
    }

    .evac-badge {
        display: inline-flex;
        min-width: 84px;
        align-items: center;
        justify-content: center;
        padding: 0.35rem 0.7rem;
        border: 1px solid transparent;
        border-radius: 999px;
        font-size: 0.63rem;
        font-weight: 800;
        letter-spacing: 0.55px;
        line-height: 1;
        white-space: nowrap;
    }

    .evac-badge-cleared { background: #ecfdf5; border-color: #a7f3d0; color: #047857; }
    .evac-badge-occupied { background: #eff6ff; border-color: #bfdbfe; color: #1d4ed8; }
    .evac-badge-full { background: #fef2f2; border-color: #fecaca; color: #b91c1c; }
    .evac-badge-decamp { background: #f5f3ff; border-color: #ddd6fe; color: #6d28d9; }

    .evac-empty-row {
        padding: 2.5rem 1rem !important;
        color: #64748b;
        text-align: center !important;
    }

    .evac-pagination-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.85rem 1.25rem;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
    }

    .evac-page-btn {
        display: inline-flex;
        min-width: 86px;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        padding: 0.45rem 0.8rem;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        background: #ffffff;
        color: #334155;
        font-size: 0.75rem;
        font-weight: 700;
        transition: all 0.18s ease;
    }

    .evac-page-btn:hover:not(:disabled) {
        border-color: #0284c7;
        background: #0284c7;
        color: #ffffff;
    }

    .evac-page-btn:disabled {
        cursor: not-allowed;
        opacity: 0.45;
    }

    .evac-page-info {
        color: #64748b;
        font-size: 0.76rem;
        font-weight: 700;
    }

    /* Reference-style evacuation centers table */
    .evac-panel-schools {
        border-radius: 10px;
        border-color: #b8c4d4;
        background: #f8fafc;
        box-shadow: 0 4px 14px rgba(15, 33, 84, 0.16);
    }

    .evac-panel-schools .evac-panel-header {
        min-height: 46px;
        padding: 0.65rem 1.25rem;
        gap: 0.5rem;
        background: #12265f;
        border-bottom: 0;
        color: #ffffff;
        font-size: 0.72rem;
        letter-spacing: 0.65px;
        text-transform: uppercase;
    }

    .evac-panel-schools .evac-panel-header > i {
        color: #00d2ff;
        font-size: 0.95rem;
    }

    .evac-table-toolbar-search {
        width: 220px;
        margin-left: auto;
        margin-right: 0.75rem;
    }

    .evac-table-toolbar-search input {
        height: 28px;
        padding: 0.25rem 0.65rem;
        border: 1px solid rgba(255,255,255,0.16);
        border-radius: 4px;
        background: rgba(255,255,255,0.1);
        color: #ffffff;
        font-size: 0.7rem;
    }

    .evac-table-toolbar-search input::placeholder {
        color: rgba(255,255,255,0.35);
    }

    .evac-table-status-chips {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .evac-table-status-chips .evac-badge {
        min-width: auto;
        padding: 0.25rem 0.45rem;
        font-size: 0.48rem;
        letter-spacing: 0.2px;
        border: 0;
    }

    .evac-table-status-chips .evac-badge:hover {
        filter: brightness(1.08);
    }

    .evac-schools-table-wrap {
        background: #f8fafc;
    }

    .evac-schools-table {
        min-width: 920px;
        table-layout: fixed;
    }

    .evac-schools-table th {
        height: 44px;
        padding: 0.55rem 1.25rem;
        background: #f8fafc;
        color: #64748b;
        border-bottom: 1px solid #cbd5e1;
        font-size: 0.58rem;
        letter-spacing: 0.8px;
        line-height: 1.2;
    }

    .evac-schools-table th:nth-child(1) { width: 25%; }
    .evac-schools-table th:nth-child(2) { width: 24%; }
    .evac-schools-table th:nth-child(3) { width: 11%; }
    .evac-schools-table th:nth-child(4) { width: 11%; }
    .evac-schools-table th:nth-child(5) { width: 14%; }
    .evac-schools-table th:nth-child(6) { width: 9%; }
    .evac-schools-table th:nth-child(7) { width: 13%; }

    .evac-schools-table td {
        height: 62px;
        padding: 0.7rem 1.25rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.72rem;
    }

    .evac-school-row:hover td {
        background: #eef6ff;
    }

    .evac-school-name {
        display: block;
        max-width: 150px;
        font-size: 0.72rem;
        line-height: 1.15;
        white-space: normal;
    }

    .evac-school-uid {
        display: block;
        margin-top: 0.2rem;
        color: #64748b;
        font-size: 0.62rem;
    }

    .evac-location {
        display: block;
        max-width: 190px;
        line-height: 1.25;
        text-align: left;
    }

    .evac-location i {
        color: #ef4444;
        margin-right: 0.3rem;
    }

    .evac-capacity,
    .evac-load {
        font-family: var(--font-display);
        font-weight: 800;
    }

    .evac-load {
        color: #00b8e6;
    }

    .evac-resource {
        color: #334155;
        font-size: 0.68rem;
    }

    .evac-management {
        display: inline-flex;
        align-items: stretch;
        border: 1px solid #dbe4ef;
        border-radius: 6px;
        overflow: hidden;
        background: #f1f5f9;
    }

    .evac-management button,
    .evac-management a {
        display: inline-flex;
        min-width: 38px;
        height: 30px;
        align-items: center;
        justify-content: center;
        border: 0;
        border-right: 1px solid #dbe4ef;
        background: transparent;
        color: #334155;
        font-size: 0.65rem;
        text-decoration: none;
    }

    .evac-management button:last-child,
    .evac-management a:last-child { border-right: 0; }
    .evac-management button:hover,
    .evac-management a:hover { background: #e0f2fe; color: #0369a1; }

    .evac-pagination-bar {
        padding: 0.65rem 1.25rem;
        background: #f8fafc;
        border-top: 0;
    }

    @media (max-width: 768px) {
        .evac-panel-schools .evac-panel-header {
            align-items: center;
            flex-wrap: wrap;
        }

        .evac-table-toolbar-search {
            order: 3;
            width: 100%;
            margin: 0.35rem 0 0;
        }

        .evac-table-status-chips {
            margin-left: auto;
        }

        .evac-schools-table th,
        .evac-schools-table td {
            padding-right: 0.85rem;
            padding-left: 0.85rem;
        }

        .evac-pagination-bar { padding: 0.75rem 0.85rem; }
    }

    /* System Status Pulse */
    .status-pulse {
        width: 12px;
        height: 12px;
        background: #22c55e;
        border-radius: 50%;
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }
    
    .btn-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    /* Centered Navigation */
    .header-nav-center {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        background: rgba(255, 255, 255, 0.05);
        padding: 0.5rem 2rem;
        border-radius: 50px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
    }

    .nav-link-custom {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        font-family: var(--font-display);
        font-weight: 700;
        font-size: 1.1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        background: none;
        border: none;
        padding: 0.5rem 0.25rem;
    }

    .nav-link-custom:hover {
        color: var(--accent-blue);
    }

    .nav-link-custom.active {
        color: var(--accent-blue);
        text-shadow: 0 0 15px rgba(0, 210, 255, 0.5);
    }

    .notif-btn-custom {
        position: relative;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem;
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.25rem;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .notif-btn-custom .badge {
        pointer-events: none;
    }

    .notif-btn-custom:hover, .notif-btn-custom.active {
        color: var(--accent-blue);
    }

    .school-btn-custom {
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.25rem;
        transition: all 0.3s ease;
        background: none;
        border: none;
    }

    .school-btn-custom:hover, .school-btn-custom.active {
        color: var(--accent-blue);
    }
    
    /* Sidebar Core Styles */
    .dashboard-wrapper {
        display: flex;
        width: 100%;
        height: 100vh;
    }

    .app-sidebar {
        width: 280px;
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

    .sidebar-link:hover {
        background: rgba(255, 255, 255, 0.03);
        color: #ffffff;
    }

    .sidebar-link.active {
        background: rgba(0, 210, 255, 0.1);
        color: var(--accent-blue);
        text-shadow: 0 0 10px rgba(0, 210, 255, 0.3);
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

    /* Right Main Content Panel */
    .content-panel {
        flex-grow: 1;
        min-width: 0;
        height: 100vh;
        overflow-y: auto;
    }

    /* Dashboard table refinements */
    .evac-panel-alert {
        display: none !important;
    }

    .evac-panel-schools {
        width: 100%;
        min-height: 520px;
    }

    .evac-panel-schools .evac-panel-header {
        position: relative;
        min-height: 58px;
        padding: 0.8rem 1.5rem;
    }

    .evac-table-toolbar-search {
        position: absolute;
        left: 54%;
        top: 50%;
        width: min(300px, 32%);
        margin: 0;
        transform: translate(-50%, -50%);
    }

    .evac-table-toolbar-search input {
        height: 36px;
        padding: 0.4rem 0.9rem;
        font-size: 0.84rem;
        text-align: center;
    }

    .evac-schools-table {
        min-width: 1040px;
    }

    .evac-schools-table th {
        height: 54px;
        padding: 0.75rem 1.5rem;
        font-size: 0.72rem;
    }

    .evac-schools-table td {
        height: 80px;
        padding: 1rem 1.5rem;
        font-size: 0.86rem;
    }

    .evac-school-name {
        max-width: 220px;
        font-size: 0.86rem;
    }

    .evac-school-uid {
        font-size: 0.68rem;
    }

    .evac-location {
        max-width: 240px;
        font-size: 0.84rem;
    }

    .evac-resource {
        font-size: 0.74rem;
    }

    .evac-badge {
        font-size: 0.68rem;
    }

    @media (max-width: 768px) {
        .evac-table-toolbar-search {
            position: static;
            order: 3;
            width: 100%;
            margin: 0.45rem 0 0;
            transform: none;
        }

        .evac-table-toolbar-search input {
            text-align: left;
            font-size: 0.86rem;
        }
    }

</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        {{-- Left Side --}}
        <div class="d-flex align-items-center" style="width: 30%;">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-info border-0 me-3" title="Back">
                <i class="fas fa-chevron-left fa-lg"></i>
            </a>
            <div>
                <h1 class="h3 mb-0 fw-bold text-white">
                    <i class="fas fa-satellite-dish me-2" style="color: var(--accent-blue);"></i>
                    Evacuation Management System
                </h1>
            </div>
        </div>


        
        {{-- Centered Navigation --}}
        <div class="header-nav-center">
            <a href="{{ route('typhoon.dashboard') }}" class="nav-link-custom active">
                Dashboard
            </a>
            <a href="{{ route('typhoon.notifications') }}" class="notif-btn-custom" title="Notifications">
                <i class="fas fa-bell"></i>
                @php
                    $unreadCount = $unreadNotificationsCount ?? 0;
                @endphp
                @if($unreadCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.6rem; padding: 0.35em 0.65em;">
                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                    </span>
                @endif
            </a>
            <button type="button" class="school-btn-custom" data-bs-toggle="modal" data-bs-target="#chooseSchoolModal" title="Choose Evacuation Center">
                <i class="fas fa-school"></i>
            </button>
        </div>

        {{-- Right Side --}}
        <div class="d-flex align-items-center gap-3 justify-content-end" style="width: 30%;">
            <div class="text-white-50 small text-end">
                <div class="fw-bold text-white">{{ now()->format('M d, Y') }}</div>
                <div>{{ now()->format('h:i A') }}</div>
            </div>
            <div class="btn-group shadow-lg">
                <button class="btn btn-success px-3 fw-bold" onclick="document.getElementById('socialPrintModal').style.display='flex'" title="Send to Social">
                    <i class="fas fa-share-alt"></i>
                </button>
                @if(auth()->user()->role === 'admin')
                <button type="button" class="btn btn-warning text-white px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#announceSomethingModal">
                    <i class="fas fa-bullhorn"></i>
                </button>
                @endif
            </div>
        </div>
    </div>

{{-- Main Row spanning 100% width --}}
<div class="d-flex w-100 min-vh-100" style="overflow-x: hidden;">

    
    <!-- Left Navigation Sidebar -->
    <div class="app-sidebar">
        <div class="sidebar-header">
        <ul class="sidebar-menu">
            <li>
                <a href="#" class="sidebar-link active">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="#" class="sidebar-link">
                    <i class="fas fa-school"></i> Evacuation Centers
                </a>
            </li>
            <li>
                <button type="button" class="sidebar-link sidebar-link-toggle" data-bs-toggle="collapse" data-bs-target="#familiesSidebarMenu" aria-expanded="false" aria-controls="familiesSidebarMenu">
                    <i class="fas fa-users"></i>
                    <span>Families &amp; Individuals</span>
                    <i class="fas fa-chevron-down sidebar-chevron"></i>
                </button>
                <ul id="familiesSidebarMenu" class="sidebar-submenu collapse">
                    <li>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#familyRegistrationModal">
                            <i class="fas fa-user-plus me-2"></i> Register Family
                        </button>
                        <a href="{{ route('typhoon.dswd-form') }}">
                            <i class="fas fa-file-alt me-2"></i> DSWD Form
                        </a>
                            <a href="{{ route('typhoon.family-assistance') }}">
                            <i class="fas fa-file-alt me-2"></i> Family Assistance
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#" class="sidebar-link">
                    <i class="fas fa-box-open"></i> Resource Inventory
                </a>
            </li>
            <li>
                <a href="#" class="sidebar-link">
                    <i class="fas fa-file-alt"></i> Incident Reports
                </a>
            </li>
        </ul>
    </div>

</div>

 <div class="flex-grow-1 p-4 d-flex flex-column gap-4" style="background-color: #0a1128; min-width: 0;">
            <div class="row g-3">
                  {{-- Left Card: Estimated Affected Population --}} 
    <div class="col-md-6"> 
        <div class="dashboard-card h-100"> 
            <div class="card-header-custom"><i class="fas fa-users"></i>Estimated Affected Population</div> 
            <div class="p-4"> 
                <div class="row g-0 align-items-center"> 
                    <div class="col-6 mb-4"> 
                        <div class="stat-label">Total Families</div> 
                        <div class="stat-value text-primary">{{ $totalFamilies ?? 0 }}</div> 
                    </div> 
                    <div class="col-6 mb-4"> 
                        <div class="stat-label">Total Individuals</div> 
                        <div class="stat-value">{{ $totalEvacuees ?? 0 }}</div> 
                    </div> 
                    <div class="col-12"> 
                        <div class="d-flex align-items-center justify-content-between p-3 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0;"> 
                            <div class="stat-label mb-0">Active Evacuation Centers</div> 
                            <div class="h4 mb-0 fw-bold text-primary">{{ $openEvacuationCentersCount ?? 0 }}</div> 
                        </div> 
                    </div> 
                </div> 
            </div> 
        </div> 
    </div> 

    {{-- Right Card: Occupancy Overview --}} 
    <div class="col-md-6"> 
        <div class="dashboard-card d-flex flex-column h-100"> 
            <div class="card-header-custom justify-content-between"> 
                <div class="d-flex align-items-center"> 
                    <i class="fas fa-chart-pie"></i>Occupancy Overview 
                </div> 
                <div class="dropdown"> 
                    <button class="btn btn-sm occupancy-filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"> 
                        <i class="fas fa-filter"></i> Filter 
                    </button> 
                    <div class="dropdown-menu occupancy-filter-menu"> 
                        <div class="mb-2"> 
                            <label for="occupancySortOrder" class="form-label mb-1 small text-uppercase text-muted fw-semibold">Sort By</label> 
                            <select id="occupancySortOrder" class="form-select form-select-sm"> 
                                <option value="alphabetical">Alphabetical</option> 
                                <option value="highest">Highest to Lowest</option> 
                                <option value="newest">Newest to Lowest</option> 
                            </select> 
                        </div> 
                        <div> 
                            <div class="small text-uppercase text-muted fw-semibold mb-1">Direction</div> 
                            <div class="form-check"> 
                                <input class="form-check-input" type="radio" name="occupancyDirection" id="occupancyDirectionLtr" value="ltr" checked> 
                                <label class="form-check-label small" for="occupancyDirectionLtr">Left to Right</label> 
                            </div> 
                            <div class="form-check"> 
                                <input class="form-check-input" type="radio" name="occupancyDirection" id="occupancyDirectionRtl" value="rtl"> 
                                <label class="form-check-label small" for="occupancyDirectionRtl">Right to Left</label> 
                            </div> 
                        </div> 
                    </div> 
                </div> 
            </div> 
            <div class="p-2 flex-grow-1 d-flex flex-column justify-content-between"> 
                <div class="occupancy-chart-panel" style="position: relative; overflow-x: auto; width: 100%;"> 
                    <div id="occupancyChartScroll" class="occupancy-chart-scroll" style="width: 100%;"> 
                        <canvas id="occupancyChart" style="width: 100%; min-height: 200px;"></canvas> 
                    </div> 
                </div> 
                <div class="occupancy-summary"> 
                    <div class="d-flex justify-content-between small mb-1"> 
                        <span class="text-muted">Total System Capacity</span> 
                        <span class="fw-bold">{{ $totalSystemCapacity ?? 0 }}</span> 
                    </div> 
                    <div class="progress" style="height: 8px; background: #f1f5f9;"> 
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 100%;"></div> 
                    </div> 
                </div> 
            </div> 
        </div> 
    </div> 
  </div>

    {{-- ═══ Active Typhoon Alert Banner (only shown when a TC is near Philippines) ═══ --}}
    @if(!empty($activeTyphoon))
    @php
        $signalColors = [
            1 => ['bg' => '#f59e0b', 'border' => '#d97706', 'text' => '#78350f', 'badge' => '#fde68a'],
            2 => ['bg' => '#f97316', 'border' => '#ea580c', 'text' => '#431407', 'badge' => '#fed7aa'],
            3 => ['bg' => '#ef4444', 'border' => '#dc2626', 'text' => '#450a0a', 'badge' => '#fecaca'],
            4 => ['bg' => '#9333ea', 'border' => '#7c3aed', 'text' => '#2e1065', 'badge' => '#e9d5ff'],
            5 => ['bg' => '#0f172a', 'border' => '#00d2ff', 'text' => '#e2e8f0', 'badge' => '#00d2ff'],
        ];
        $sc = $signalColors[$activeTyphoon['signal']] ?? $signalColors[1];
    @endphp
    <div class="mb-4" style="border-radius: 14px; overflow: hidden; box-shadow: 0 0 40px {{ $sc['border'] }}55;">
        <div style="background: linear-gradient(135deg, {{ $sc['bg'] }} 0%, {{ $sc['border'] }} 100%); padding: 0; position: relative; overflow: hidden;">
            
            {{-- Animated rotating storm background --}}
            <div style="position:absolute; top:-60px; right:-60px; width:220px; height:220px; border-radius:50%;
                        border: 3px solid rgba(255,255,255,0.1);
                        animation: typhoonSpin 8s linear infinite; pointer-events:none; opacity:0.3;">
            </div>
            <div style="position:absolute; top:-30px; right:-30px; width:160px; height:160px; border-radius:50%;
                        border: 3px solid rgba(255,255,255,0.15);
                        animation: typhoonSpin 5s linear infinite reverse; pointer-events:none; opacity:0.4;">
            </div>

            <div style="position:relative; z-index:2; display:flex; align-items:center; justify-content:space-between; padding: 1.25rem 1.75rem; gap: 1.5rem; flex-wrap:wrap;">
                
                {{-- Left: Storm icon + label --}}
                <div style="display:flex; align-items:center; gap:1rem;">
                    <div style="font-size:3rem; animation: typhoonSpin 4s linear infinite; line-height:1;">
                        🌀
                    </div>
                    <div>
                        <div style="font-size:0.65rem; color:rgba(255,255,255,0.75); text-transform:uppercase; letter-spacing:2px; font-weight:700; margin-bottom:0.2rem;">
                            ⚠ PAGASA Active Weather Alert — Directly Affecting Olongapo City Area
                        </div>
                        <div style="font-family:'Rajdhani',sans-serif; font-size:1.65rem; font-weight:800; color:#fff; letter-spacing:1px; line-height:1.1;">
                            Effects of <span style="color:rgba(255,255,255,0.75); font-weight:500;">{{ $activeTyphoon['category'] }}</span>
                            "<span style="color:#fff; font-weight:900;">{{ $activeTyphoon['name'] }}"</span>
                        </div>
                        <div style="font-size:0.78rem; color:rgba(255,255,255,0.8); margin-top:0.3rem;">
                            <i class="fas fa-map-marker-alt me-1"></i> Near <strong>Olongapo City · Zambales</strong>&nbsp;|&nbsp;
                            <i class="fas fa-ruler-combined me-1"></i> Distance: <strong>~{{ $activeTyphoon['distance_km'] ?? '?' }} km from Olongapo</strong>&nbsp;|&nbsp;
                            <i class="fas fa-wind me-1"></i> Max Wind: <strong>{{ $activeTyphoon['wind_kph'] > 0 ? $activeTyphoon['wind_kph'].' km/h' : '--' }}</strong>
                        </div>
                    </div>
                </div>

                {{-- Right: PAGASA Signal + source note --}}
                <div style="text-align:center; flex-shrink:0;">
                    <div style="font-size:0.6rem; color:rgba(255,255,255,0.7); text-transform:uppercase; letter-spacing:1px; margin-bottom:0.3rem;">TCWS Level</div>
                    <div style="background:rgba(255,255,255,0.15); border:2px solid rgba(255,255,255,0.4); border-radius:12px; padding:0.5rem 1.5rem; backdrop-filter:blur(6px);">
                        <div style="font-family:'Rajdhani',sans-serif; font-size:2.5rem; font-weight:900; color:#fff; line-height:1;">
                            #{{ $activeTyphoon['signal'] }}
                        </div>
                        <div style="font-size:0.65rem; color:rgba(255,255,255,0.7); letter-spacing:1px; text-transform:uppercase;">Signal No.</div>
                    </div>
                    <div style="font-size:0.55rem; color:rgba(255,255,255,0.5); margin-top:0.4rem;">
                        Source: GDACS · Auto-refreshes every 30 min
                    </div>
                </div>
            </div>

            {{-- Bottom stripe --}}
            <div style="background:rgba(0,0,0,0.25); padding:0.4rem 1.75rem; font-size:0.7rem; color:rgba(255,255,255,0.65); display:flex; align-items:center; gap:0.75rem;">
                <i class="fas fa-exclamation-circle me-1" style="color:rgba(255,255,255,0.9);"></i>
                <strong style="color:#fff;">DepEd Reminder:</strong>
                Classes in affected areas are automatically suspended under Tropical Cyclone Wind Signal {{ $activeTyphoon['signal'] >= 1 ? '#'.$activeTyphoon['signal'] : '' }}.
                All DepEd-Zambales schools must activate their DRRM protocols immediately.
            </div>
        </div>
    </div>
    @endif

{{-- Family Registration Modal moved to a reusable partial. --}}
@if(false)
<div class="modal fade" id="familyRegistrationModal" tabindex="-1" aria-labelledby="familyRegistrationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('typhoon.families.store') }}" id="familyRegistrationForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--bg-dark); color: var(--accent-blue); border-bottom: 1px solid var(--glass-border);">
                    <h5 class="modal-title" id="familyRegistrationModalLabel">
                        <i class="fas fa-people-arrows me-2"></i> Register Family Evacuee
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">

                  <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Family Encoding Mode <span class="text-danger">*</span></label>
                            <select name="registration_mode" id="familyRegistrationMode" class="form-select" required>
                                <option value="new" selected>Encode new family</option>
                                <option value="existing">Register existing</option>
                            </select>
                            <input type="hidden" name="existing_family_id" id="existingFamilyId" value="">
                        </div>
                        <div class="col-md-6 d-none" id="existingFamilySelectorWrap">
                            <label class="form-label small fw-bold">Registered Family in This Center</label>
                            <select id="existingFamilySelect" class="form-select">
                                <option value="">-- Select existing family --</option>
                            </select>
                            <small class="text-muted">Families registered in any evacuation center are available for selection.</small>
                        </div>
                    </div>

                    {{-- Family-level fields --}}
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h6 class="fw-bold text-primary"><i class="fas fa-user-tie"></i> Head of Family Details</h6>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-bold">Full Name (Head) <span class="text-danger">*</span></label>
                            <input type="text" name="head_family_name" id="input_head_name" class="form-control" placeholder="Full name of head" required
                                oninput="document.getElementById('hidden_head_name').value = this.value">
                            <input type="hidden" name="members[0][full_name]" id="hidden_head_name">
                            <input type="hidden" name="members[0][is_head]" value="1">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label small fw-bold">Age <span class="text-danger">*</span></label>
                            <input type="number" name="members[0][age]" class="form-control" placeholder="Age" required min="0" max="150">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label small fw-bold">Gender <span class="text-danger">*</span></label>
                            <select name="members[0][gender]" class="form-select" required>
                                <option value="">Select...</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <!-- Head of Family Vulnerability Tags -->
                        <div class="col-12 vulnerability-wrapper mt-2">
                            <div class="p-2 bg-light rounded border">
                                <label class="form-label fw-bold small mb-1">Head Vulnerabilities / Special Concerns</label>
                                <select class="form-select form-select-sm mb-2 vulnerability-selector">
                                    <option value="">-- Add Concern --</option>
                                    <option value="flagPregnant">Pregnant</option>
                                    <option value="flagPwd">PWD</option>
                                    <option value="flagSenior">Senior Citizen</option>
                                    <option value="flagLactating">Lactating</option>
                                    <option value="flagChild">Child Under 5</option>
                                </select>
                                <div class="vulnerability-tags-container d-flex flex-wrap gap-2"></div>
                                <div class="d-none">
                                    <input class="form-check-input vulnerability-checkbox flagPregnant" type="checkbox" name="has_pregnant" value="1" id="flagPregnant">
                                    <input class="form-check-input vulnerability-checkbox flagPwd" type="checkbox" name="has_pwd" value="1" id="flagPwd">
                                    <input class="form-check-input vulnerability-checkbox flagSenior" type="checkbox" name="has_senior" value="1" id="flagSenior">
                                    <input class="form-check-input vulnerability-checkbox flagLactating" type="checkbox" name="has_lactating" value="1" id="flagLactating">
                                    <input class="form-check-input vulnerability-checkbox flagChild" type="checkbox" name="has_child_under5" value="1" id="flagChild">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <label class="form-label small fw-bold">Collective Family Needs <span class="text-danger">*</span></label>
                            <div class="family-needs-builder" data-family-needs-builder="create" data-need-options='@json($familyNeedOptions ?? [])' data-existing-needs='[]'></div>
                            <small class="text-muted d-block mt-2">Choose a need and quantity. Selecting <strong>Others Please Specify</strong> will reveal a custom need field.</small>
                        </div>
                    </div>

    {{-- â”€â”€ MAIN BODY: Sidebar + Center + Right â”€â”€ --}}
    <div class="evac-body">

        {{-- â”€â”€ LEFT SIDEBAR â”€â”€ --}}
        <aside class="evac-sidebar">
            <nav class="evac-nav">

                <a href="#" class="evac-nav-item" id="evac-nav-incident">
                    <div class="evac-nav-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <span class="evac-nav-label">Incident</span>
                </a>

                {{-- Register with dropdown --}}
                <div class="evac-nav-item evac-nav-has-sub" id="evac-nav-register-wrap">
                    <div class="evac-nav-item-inner" id="evac-nav-register-toggle">
                        <div class="evac-nav-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <span class="evac-nav-label">Register</span>
                        <i class="fas fa-chevron-down evac-nav-caret" id="evac-register-caret"></i>
                    </div>
                    <div class="evac-nav-sub" id="evac-nav-register-sub">
                        <a href="#" class="evac-nav-sub-item" id="registerNavNew">
                            <i class="fas fa-arrow-right evac-sub-arrow"></i> New
                        </a>
                        <a href="#" class="evac-nav-sub-item" id="registerNavExisting">
                            <i class="fas fa-arrow-right evac-sub-arrow"></i> Existing
                        </a>
                    </div>
                </div>

                <a href="#" class="evac-nav-item" id="evac-nav-school" data-bs-toggle="modal" data-bs-target="#chooseSchoolModal">
                    <div class="evac-nav-icon">
                        <i class="fas fa-school"></i>
                    </div>
                    <span class="evac-nav-label">School</span>
                </a>

                <a href="#" class="evac-nav-item" id="evac-nav-remarks">
                    <div class="evac-nav-icon">
                        <i class="fas fa-comment-dots"></i>
                    </div>
                </div>
<!--work in progress-->

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background-color: #1B4C6D; color: white;">
                        <i class="fas fa-save"></i> Register Family
                    </button>
                </div>
            </div>
</form>
    </div>
</div>
@endif

            </nav>
        </aside>

        {{-- â”€â”€ CENTER COLUMN â”€â”€ --}}
        <main class="evac-main">

            {{-- â”€â”€ SCHOOL STATUS / UPDATES (Typhoon Alert Banner) â”€â”€ --}}
            <section class="evac-panel evac-panel-alert">
                <div class="evac-panel-header">
                    <i class="fas fa-broadcast-tower"></i>
                    <span>School Status / Updates</span>
                </div>
                <div class="evac-panel-body">
                    @if(!empty($activeTyphoon))
                        @php
                            $signalColors = [
                                1 => ['bg' => '#f59e0b', 'border' => '#d97706', 'glow' => 'rgba(245,158,11,0.35)'],
                                2 => ['bg' => '#f97316', 'border' => '#ea580c', 'glow' => 'rgba(249,115,22,0.35)'],
                                3 => ['bg' => '#ef4444', 'border' => '#dc2626', 'glow' => 'rgba(239,68,68,0.35)'],
                                4 => ['bg' => '#9333ea', 'border' => '#7c3aed', 'glow' => 'rgba(147,51,234,0.35)'],
                                5 => ['bg' => '#0f172a',  'border' => '#00d2ff', 'glow' => 'rgba(0,210,255,0.35)'],
                            ];
                            $sc = $signalColors[$activeTyphoon['signal']] ?? $signalColors[1];
                        @endphp
                        <div class="evac-typhoon-alert" style="background: linear-gradient(135deg, {{ $sc['bg'] }} 0%, {{ $sc['border'] }} 100%); box-shadow: 0 0 30px {{ $sc['glow'] }};">
                            {{-- Animated storm rings --}}
                            <div class="evac-storm-ring evac-storm-ring-1"></div>
                            <div class="evac-storm-ring evac-storm-ring-2"></div>

                            <div class="evac-alert-content">
                                <div class="evac-alert-left">
                                    <div class="evac-storm-emoji">ðŸŒ€</div>
                                    <div class="evac-alert-text">
                                        <div class="evac-alert-sub">âš  PAGASA Active Weather Alert â€” Directly Affecting Olongapo City Area</div>
                                        <div class="evac-alert-title">
                                            Effects of <span class="evac-alert-cat">{{ $activeTyphoon['category'] }}</span>
                                            "<span class="evac-alert-name">{{ $activeTyphoon['name'] }}</span>"
                                        </div>
                                        <div class="evac-alert-meta">
                                            <span><i class="fas fa-map-marker-alt me-1"></i> Near <strong>Olongapo City Â· Zambales</strong></span>
                                            <span>|</span>
                                            <span><i class="fas fa-ruler-combined me-1"></i> ~{{ $activeTyphoon['distance_km'] ?? '?' }} km from Olongapo</span>
                                            <span>|</span>
                                            <span><i class="fas fa-wind me-1"></i> {{ $activeTyphoon['wind_kph'] > 0 ? $activeTyphoon['wind_kph'].' km/h' : '--' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="evac-alert-right">
                                    <div class="evac-signal-label">TCWS Level</div>
                                    <div class="evac-signal-badge">#{{ $activeTyphoon['signal'] }}</div>
                                    <div class="evac-signal-sub">Signal No.</div>
                                    <div class="evac-signal-source">Source: GDACS Â· Auto-refreshes every 30 min</div>
                                </div>
                            </div>
                            <div class="evac-alert-stripe">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                <strong>DepEd Reminder:</strong>
                                Classes in affected areas are automatically suspended under Tropical Cyclone Wind Signal {{ $activeTyphoon['signal'] >= 1 ? '#'.$activeTyphoon['signal'] : '' }}.
                                All DepEd-Zambales schools must activate their DRRM protocols immediately.
                            </div>
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div class="evac-empty-state">
                            <div class="evac-empty-icon">
                                <i class="fas fa-sun"></i>
                                <i class="fas fa-cloud evac-empty-cloud"></i>
                            </div>
                            <div class="evac-empty-title">No Active Typhoon Alerts</div>
                            <div class="evac-empty-sub">Weather conditions are currently normal. No tropical cyclone threats detected in the area.</div>
                        </div>
                    @endif
                </div>
            </section>

            {{-- â”€â”€ CURRENT STATUS OF SCHOOL â”€â”€ --}}
            <section class="evac-panel evac-panel-schools">
                <div class="evac-panel-header">
                    <i class="fas fa-chart-bar"></i>
                    <span>Evacuation Centers Status Monitoring</span>
                    <div class="evac-table-toolbar-search">
                        <input type="search" id="evacSchoolSearch" class="form-control" placeholder="Search school name..." aria-label="Search school name">
                    </div>
                    <div class="evac-table-status-chips" aria-label="Filter by status">
                        <button type="button" class="evac-badge evac-badge-cleared filter-status-btn" data-status="cleared">CLEARED</button>
                        <button type="button" class="evac-badge evac-badge-occupied filter-status-btn" data-status="occupied">OCCUPIED</button>
                        <button type="button" class="evac-badge evac-badge-full filter-status-btn" data-status="full">FULL</button>
                        <button type="button" class="evac-badge evac-badge-decamp filter-status-btn" data-status="decamp">DECAMP</button>
                    </div>
                </div>
                <div class="evac-schools-table-wrap">
                    <table class="evac-schools-table" id="evacSchoolsTable">
                        <thead>
                            <tr>
                                <th class="th-name">School / Center<br>Name</th>
                                <th class="th-location">Location &amp; Area</th>
                                <th class="th-capacity">Max<br>Capacity</th>
                                <th class="th-load">Current<br>Load</th>
                                <th class="th-status">Operational<br>Status</th>
                                <th class="th-resource">Resource<br>Inventory</th>
                                <th class="th-management">Management</th>
                            </tr>
                        </thead>
                        <tbody id="evacSchoolsTbody">
                            @forelse($evacuationCenters ?? [] as $ec)
                            <tr class="evac-school-row {{ $ec->is_cluster_priority ? 'evac-cluster-row' : '' }}"
                                data-href="{{ route('typhoon.evacuation-center.show', $ec->id) }}"
                                data-priority="{{ $ec->is_cluster_priority ? '1' : '0' }}"
                                onclick="window.location=this.dataset.href">
                                <td class="td-name">
                                    @if($ec->is_cluster_priority)
                                        <span class="evac-cluster-pin" title="Clustered / Priority School"><i class="fas fa-star"></i></span>
                                    @endif
                                    <span class="evac-school-name">{{ $ec->school_name ?? $ec->identification ?? ('Center #' . $ec->id) }}</span>
                                    <span class="evac-school-uid">UID: {{ $ec->school_id ?? 'N/A' }}</span>
                                </td>
                                <td class="td-location">
                                    <span class="evac-location"><i class="fas fa-map-marker-alt"></i>{{ $ec->location ?? 'Location not provided' }}</span>
                                </td>
                                <td class="td-capacity"><span class="evac-capacity">{{ ($ec->capacity ?? 0) > 0 ? $ec->capacity : 'Unlimited' }}</span></td>
                                <td class="td-load"><span class="evac-load">{{ $ec->current_occupancy ?? 0 }}</span></td>
                                <td class="td-status">
                                    @php
                                        $st = $ec->usage_status ?? 'cleared';
                                        $statusMap = [
                                            'cleared'  => ['cls' => 'evac-badge-cleared',  'label' => 'CLEARED'],
                                            'occupied' => ['cls' => 'evac-badge-occupied', 'label' => 'OCCUPIED'],
                                            'full'     => ['cls' => 'evac-badge-full',     'label' => 'FULL'],
                                            'decamp'   => ['cls' => 'evac-badge-decamp',   'label' => 'DECAMP'],
                                        ];
                                        $sm = $statusMap[$st] ?? $statusMap['cleared'];
                                    @endphp
                                    <span class="evac-badge {{ $sm['cls'] }}">{{ $sm['label'] }}</span>
                                </td>
                                <td class="td-resource"><span class="evac-resource">Standby</span></td>
                                <td class="td-management">
                                    <span class="evac-management">
                                        <a href="{{ route('typhoon.evacuation-center.show', $ec->id) }}" title="Register / View center"><i class="fas fa-user-plus"></i><span class="visually-hidden">Register</span></a>
                                        <a href="{{ route('typhoon.evacuation-center.show', $ec->id) }}" title="Open center"><i class="fas fa-expand-alt"></i><span class="visually-hidden">Open</span></a>
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="evac-empty-row">
                                    <i class="fas fa-school me-2 opacity-50"></i> No evacuation centers registered.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Footer --}}
                <div class="evac-pagination-bar">
                    <button class="evac-page-btn" id="evacPrevPage" disabled>
                        <i class="fas fa-chevron-left"></i> Prev
                    </button>
                    <span class="evac-page-info" id="evacPageInfo">Page 1 of â€”</span>
                    <button class="evac-page-btn" id="evacNextPage">
                        Next <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </section>

        </main>



    </div>{{-- /.evac-body --}}
</div>{{-- /.evac-dashboard-root --}}

{{-- Family registration modal is kept outside the dashboard layout. --}}
@include('typhoon.family-registration-modal')

{{-- â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ SHARED MODALS & PARTIALS â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
@include('typhoon.partials.choose-school-modal')
@include('typhoon.partials.create-evac-center-modal')






{{-- ===================== SOCIAL / PRINT OVERLAY ===================== --}}
<div id="socialPrintModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.92); z-index:9999; flex-direction:column; align-items:center; justify-content:flex-start; overflow-y:auto; padding: 1.5rem 0;">

    {{-- ACTION BUTTONS â€” outside the printable card --}}
    <div id="printActionBar" style="display:flex; align-items:center; gap:1rem; margin-bottom:1.25rem; width:1100px; max-width:96vw; justify-content:flex-end;">
        <span style="color:#8892b0; font-size:0.82rem; margin-right:auto; font-family:'Space Grotesk',sans-serif;">
            <i class="fas fa-info-circle me-1"></i> Preview your social media report. Click <strong style="color:#00d2ff;">Save as PDF</strong> to download.
        </span>
        <button onclick="printSocialCard()" style="background:#00d2ff; border:none; color:#0a1128; border-radius:8px; padding:0.6rem 1.5rem; font-weight:800; font-size:0.95rem; cursor:pointer; letter-spacing:0.5px; display:flex; align-items:center; gap:0.5rem; box-shadow:0 0 20px rgba(0,210,255,0.4);">
            <i class="fas fa-file-pdf"></i> Save as PDF
        </button>
        <button onclick="document.getElementById('socialPrintModal').style.display='none'" style="background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.25); color:#fff; border-radius:8px; padding:0.6rem 1.25rem; font-weight:700; font-size:0.95rem; cursor:pointer; display:flex; align-items:center; gap:0.5rem;">
            <i class="fas fa-times"></i> Close
        </button>
    </div>

    {{-- PRINT CARD (landscape, 1100px wide) --}}
    <div id="printCard" style="background: linear-gradient(135deg, #0a1128 0%, #112240 55%, #0d2137 100%); color: #e2e8f0; width: 1100px; max-width: 96vw; border-radius: 16px; box-shadow: 0 0 80px rgba(0,210,255,0.15); border: 1px solid rgba(0,210,255,0.2); font-family: 'Sora', 'Inter', sans-serif; padding: 2.25rem; margin-bottom: 2rem;">

        {{-- Header with Logos --}}
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; padding-bottom:1.25rem; border-bottom:1px solid rgba(0,210,255,0.2);">
            <div style="display:flex; align-items:center; gap:1.25rem;">
                <img src="{{ asset('images/drrmis-logo-2.png') }}" alt="DRRMIS" style="height:65px; object-fit:contain;">
                <img src="{{ asset('images/What-Is-the-Difference-Between-DepEd-Seal-and-DepEd-Logo.png') }}" alt="DepEd" style="height:65px; object-fit:contain;">
                <img src="{{ asset('images/Layer-0-1.png') }}" alt="Logo" style="height:65px; object-fit:contain;">
                <div style="margin-left:0.5rem; padding-left:1.25rem; border-left:2px solid rgba(0,210,255,0.3);">
                    <div style="font-size:0.65rem; color:#8892b0; text-transform:uppercase; letter-spacing:2px;">Department of Education</div>
                    <div style="font-size:0.65rem; color:#8892b0; text-transform:uppercase; letter-spacing:1px;">Disaster Risk Reduction & Management</div>
                </div>
            </div>
            <div style="text-align:right;">
                <div style="font-size:1.75rem; font-weight:800; color:#00d2ff; letter-spacing:3px; line-height:1;">TYPHOON & FLOOD</div>
                <div style="font-size:1rem; font-weight:700; color:#ffffff; letter-spacing:2px;">REPORTING SYSTEM</div>
                <div style="font-size:0.72rem; color:#8892b0; margin-top:0.35rem; letter-spacing:0.5px;">ðŸ“… {{ now()->format('F d, Y  â€”  h:i A') }}</div>
            </div>
        </div>

        {{-- Stats Row --}}
        <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:1rem; margin-bottom:1.25rem;">
            <div style="background:rgba(0,210,255,0.08); border:1px solid rgba(0,210,255,0.2); border-radius:10px; padding:1.1rem; text-align:center;">
                <div style="font-size:0.65rem; color:#8892b0; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:0.35rem;">Total Families</div>
                <div style="font-size:2.5rem; font-weight:800; color:#00d2ff; line-height:1;">{{ $totalFamilies ?? 0 }}</div>
            </div>
            <div style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:10px; padding:1.1rem; text-align:center;">
                <div style="font-size:0.65rem; color:#8892b0; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:0.35rem;">Total Individuals</div>
                <div style="font-size:2.5rem; font-weight:800; color:#ffffff; line-height:1;">{{ $totalEvacuees ?? 0 }}</div>
            </div>
            <div style="background:rgba(220,53,69,0.1); border:1px solid rgba(220,53,69,0.3); border-radius:10px; padding:1.1rem; text-align:center;">
                <div style="font-size:0.65rem; color:#8892b0; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:0.35rem;">Major Incidents</div>
                <div style="font-size:2.5rem; font-weight:800; color:#ff6b6b; line-height:1;">{{ $incidentMonitoring['major'] ?? 0 }}</div>
            </div>
            <div style="background:rgba(25,135,84,0.1); border:1px solid rgba(46,204,113,0.3); border-radius:10px; padding:1.1rem; text-align:center;">
                <div style="font-size:0.65rem; color:#8892b0; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:0.35rem;">Minor Incidents</div>
                <div style="font-size:2.5rem; font-weight:800; color:#2ecc71; line-height:1;">{{ $incidentMonitoring['minor'] ?? 0 }}</div>
            </div>
        </div>

        {{-- Rainfall & Weather + Active Centers --}}
        <div style="display:grid; grid-template-columns: 1fr 1fr 0.6fr; gap:1rem; margin-bottom:1.25rem;">
            <div style="background:rgba(0,210,255,0.06); border:1px solid rgba(0,210,255,0.15); border-radius:10px; padding:1rem;">
                <div style="font-size:0.65rem; color:#00d2ff; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:0.75rem; font-weight:700;">ðŸ“¡ Daily Rainfall</div>
                <div style="display:flex; justify-content:space-around;">
                    <div style="text-align:center;">
                        <div style="font-size:0.65rem; color:#8892b0; margin-bottom:0.25rem;">Bangal Station</div>
                        <div style="font-size:2rem; font-weight:800; color:#00d2ff; line-height:1;">{{ $rainfall['bangal'] ?? '0.0' }}<span style="font-size:0.8rem; color:#8892b0;"> mm</span></div>
                    </div>
                    <div style="width:1px; background:rgba(0,210,255,0.15);"></div>
                    <div style="text-align:center;">
                        <div style="font-size:0.65rem; color:#8892b0; margin-bottom:0.25rem;">Kalaklan Station</div>
                        <div style="font-size:2rem; font-weight:800; color:#00d2ff; line-height:1;">{{ $rainfall['kalaklan'] ?? '0.0' }}<span style="font-size:0.8rem; color:#8892b0;"> mm</span></div>
                    </div>
                </div>
            </div>
            <div style="background:rgba(52,152,219,0.08); border:1px solid rgba(52,152,219,0.2); border-radius:10px; padding:1rem;">
                <div style="font-size:0.65rem; color:#00d2ff; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:0.75rem; font-weight:700;">ðŸŒ© Weather Forecast</div>
                <div style="font-size:1.6rem; font-weight:800; color:#ffffff; line-height:1; margin-bottom:0.25rem;">{{ $typhoonData->name ?? 'Moderate Rain' }}</div>
                <div style="font-size:0.82rem; color:#8892b0; margin-bottom:0.5rem;">{{ $typhoonData->temp ?? '28' }}Â°C &nbsp;|&nbsp; {{ $typhoonData->wind ?? '15' }} km/h Wind</div>
                <div style="font-size:0.72rem; background:rgba(255,183,3,0.12); border-radius:6px; padding:0.35rem 0.75rem; color:#f0b429; display:inline-block;">âš  Storm Signal #1 Active</div>
            </div>
            <div style="background:rgba(0,210,255,0.06); border:1px solid rgba(0,210,255,0.15); border-radius:10px; padding:1rem; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                <div style="font-size:0.65rem; color:#8892b0; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:0.5rem; text-align:center;">Active Centers</div>
                <div style="font-size:3rem; font-weight:800; color:#00d2ff; line-height:1;">{{ $openEvacuationCentersCount ?? 0 }}</div>
            </div>
        </div>

        {{-- Evacuation Centers Table --}}
        <div style="margin-bottom:1rem;">
            <div style="font-size:0.7rem; color:#00d2ff; text-transform:uppercase; letter-spacing:2px; font-weight:700; margin-bottom:0.75rem; padding-bottom:0.5rem; border-bottom:1px solid rgba(0,210,255,0.2);">ðŸ« Evacuation Centers Status Monitoring</div>
            <table style="width:100%; border-collapse:collapse; font-size:0.82rem;">
                <thead>
                    <tr style="background:rgba(0,210,255,0.12);">
                        <th style="padding:0.6rem 0.75rem; text-align:left; color:#00d2ff; text-transform:uppercase; font-size:0.65rem; letter-spacing:1px;">Center / School</th>
                        <th style="padding:0.6rem 0.75rem; text-align:left; color:#00d2ff; text-transform:uppercase; font-size:0.65rem; letter-spacing:1px;">Location</th>
                        <th style="padding:0.6rem 0.75rem; text-align:center; color:#00d2ff; text-transform:uppercase; font-size:0.65rem; letter-spacing:1px;">Capacity</th>
                        <th style="padding:0.6rem 0.75rem; text-align:center; color:#00d2ff; text-transform:uppercase; font-size:0.65rem; letter-spacing:1px;">Occupancy</th>
                        <th style="padding:0.6rem 0.75rem; text-align:center; color:#00d2ff; text-transform:uppercase; font-size:0.65rem; letter-spacing:1px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($evacuationCenters ?? [] as $ec)
                    <tr style="border-bottom:1px solid rgba(255,255,255,0.05);">
                        <td style="padding:0.6rem 0.75rem; font-weight:700; color:#e2e8f0;">{{ $ec->school_name ?? $ec->identification ?? ('Center #' . $ec->id) }}</td>
                        <td style="padding:0.6rem 0.75rem; color:#8892b0; font-size:0.78rem;">{{ Str::limit($ec->location, 40) }}</td>
                        <td style="padding:0.6rem 0.75rem; text-align:center; color:#8892b0;">{{ $ec->capacity > 0 ? $ec->capacity : 'âˆž' }}</td>
                        <td style="padding:0.6rem 0.75rem; text-align:center; font-weight:800; color:#00d2ff; font-size:1.05rem;">{{ $ec->current_occupancy }}</td>
                        <td style="padding:0.6rem 0.75rem; text-align:center;">
                            @php
                                $bc = $ec->usage_status === 'full' ? '#dc3545' : ($ec->usage_status === 'occupied' ? '#3498db' : '#28a745');
                                $bt = $ec->usage_status === 'full' ? 'FULL' : ($ec->usage_status === 'occupied' ? 'OCCUPIED' : 'CLEARED');
                            @endphp
                            <span style="background:{{ $bc }}22; color:{{ $bc }}; border:1px solid {{ $bc }}55; border-radius:50px; padding:0.2rem 0.8rem; font-size:0.7rem; font-weight:700; letter-spacing:0.5px;">{{ $bt }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center; color:#8892b0; padding:1.5rem;">No evacuation centers registered yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div style="margin-top:1.25rem; padding-top:0.9rem; border-top:1px solid rgba(0,210,255,0.12); display:flex; justify-content:space-between; align-items:center; font-size:0.68rem; color:#4a5568;">
            <span>Generated by DRRM Typhoon & Flood Monitoring System</span>
            <span>{{ now()->format('Y') }} Â· DepEd DRRM Monitoring Â· Printed: {{ now()->format('M d, Y h:i A') }}</span>
        </div>
    </div>
</div>



@php
    $chartData = $evacuationCenters->map(function($ec) {
        $fullName = optional($ec->school)->school_name ?? $ec->identification ?? 'Center #'.$ec->id;
        return [
            'full_name' => $fullName,
            'display_name' => \Illuminate\Support\Str::limit($fullName, 12),
            'occupancy' => $ec->current_occupancy,
            'capacity' => $ec->capacity > 0 ? $ec->capacity : 0,
            'created_at' => optional($ec->created_at)->toDateTimeString(),
        ];
    })->values();
    $totalSystemCapacity = $evacuationCenters->sum(fn ($ec) => (int) ($ec->capacity ?? 0));
@endphp

{{-- ===================== SCRIPTS ===================== --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    /* ── Sidebar Dropdown Toggles ── */
    document.addEventListener('DOMContentLoaded', function () {
        function setupDropdown(toggleId, subId, caretId) {
            const toggleBtn = document.getElementById(toggleId);
            const subMenu   = document.getElementById(subId);
            const caret     = document.getElementById(caretId);
            if (toggleBtn && subMenu) {
                toggleBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    subMenu.classList.toggle('open');
                    if (caret) caret.classList.toggle('open');
                });
            }
        }
        
        setupDropdown('evac-nav-register-toggle', 'evac-nav-register-sub', 'evac-register-caret');
        setupDropdown('evac-nav-reports-toggle', 'evac-nav-reports-sub', 'evac-reports-caret');

        // Register sub-nav: programmatically open family modal to avoid e.preventDefault() conflict
        function openFamilyModalWithMode(mode) {
            const el = document.getElementById('familyRegistrationModal');
            if (!el) return;
            
            // Move modal directly to body to avoid z-index or stacking context issues
            if (el.parentElement !== document.body) {
                document.body.appendChild(el);
            }
            
            const modal = bootstrap.Modal.getOrCreateInstance(el);
            // Attach the mode as a temporary property so show.bs.modal can read it
            el.dataset.pendingMode = mode;
            modal.show();
        }
        const registerNavNew = document.getElementById('registerNavNew');
        const registerNavExisting = document.getElementById('registerNavExisting');
        if (registerNavNew) {
            registerNavNew.addEventListener('click', function(e) {
                e.preventDefault();
                openFamilyModalWithMode('new');
            });
        }
        if (registerNavExisting) {
            registerNavExisting.addEventListener('click', function(e) {
                e.preventDefault();
                openFamilyModalWithMode('existing');
            });
        }

        /* ── School Table Search, Status Filters, and Pagination ── */
        const ROWS_PER_PAGE = 10;
        const tbody = document.getElementById('evacSchoolsTbody');
        const searchInput = document.getElementById('evacSchoolSearch');
        const statusButtons = document.querySelectorAll('.filter-status-btn');
        const prevBtn = document.getElementById('evacPrevPage');
        const nextBtn = document.getElementById('evacNextPage');
        const pageInfo = document.getElementById('evacPageInfo');

        if (!tbody || !prevBtn || !nextBtn || !pageInfo) return;

        const allRows = Array.from(tbody.querySelectorAll('tr.evac-school-row'));
        const priorityRows = allRows.filter(row => row.dataset.priority === '1');
        const regularRows = allRows.filter(row => row.dataset.priority !== '1');
        const orderedRows = [...priorityRows, ...regularRows];
        orderedRows.forEach(row => tbody.appendChild(row));

        let currentPage = 1;
        let activeStatus = 'all';

        function getVisibleRows() {
            const query = (searchInput?.value || '').trim().toLowerCase();

            return orderedRows.filter(row => {
                const rowText = row.textContent.toLowerCase();
                const status = row.querySelector('.td-status .evac-badge')?.textContent.trim().toLowerCase() || '';
                const matchesSearch = !query || rowText.includes(query);
                const matchesStatus = activeStatus === 'all' || status === activeStatus;
                return matchesSearch && matchesStatus;
            });
        }

        function renderPage(page = 1) {
            const visibleRows = getVisibleRows();
            const totalPages = Math.max(1, Math.ceil(visibleRows.length / ROWS_PER_PAGE));
            currentPage = Math.min(Math.max(page, 1), totalPages);
            const start = (currentPage - 1) * ROWS_PER_PAGE;
            const end = start + ROWS_PER_PAGE;

            orderedRows.forEach(row => {
                row.style.display = 'none';
            });
            visibleRows.forEach((row, index) => {
                row.style.display = index >= start && index < end ? '' : 'none';
            });

            pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
            prevBtn.disabled = currentPage <= 1;
            nextBtn.disabled = currentPage >= totalPages;
        }

        searchInput?.addEventListener('input', () => renderPage(1));

        statusButtons.forEach(button => {
            button.addEventListener('click', () => {
                activeStatus = button.dataset.status || 'all';
                statusButtons.forEach(item => item.classList.toggle('active', item === button));
                renderPage(1);
            });
        });

        prevBtn.addEventListener('click', () => renderPage(currentPage - 1));
        nextBtn.addEventListener('click', () => renderPage(currentPage + 1));
        renderPage();
    });
</script>

<script>
    // 1. Setup Chart
    document.addEventListener('DOMContentLoaded', function() {
        const chartCanvas = document.getElementById('occupancyChart');
        if (!chartCanvas) {
            return;
        }

        const ctx = chartCanvas.getContext('2d');
        const scrollContainer = document.getElementById('occupancyChartScroll');
        const sortSelect = document.getElementById('occupancySortOrder');
        const directionRadios = document.querySelectorAll('input[name="occupancyDirection"]');

        const rawData = @json($chartData);
        let occupancyChart = null;

        const normalizeDate = (value) => {
            const timestamp = value ? new Date(value).getTime() : 0;
            return Number.isNaN(timestamp) ? 0 : timestamp;
        };

        const getFilteredData = () => {
            const sortMode = sortSelect ? sortSelect.value : 'alphabetical';
            const selectedDirection = document.querySelector('input[name="occupancyDirection"]:checked')?.value ?? 'ltr';
            const dataset = [...rawData];

            if (sortMode === 'highest') {
                dataset.sort((a, b) => (b.occupancy ?? 0) - (a.occupancy ?? 0));
            } else if (sortMode === 'newest') {
                dataset.sort((a, b) => normalizeDate(b.created_at) - normalizeDate(a.created_at));
            } else {
                dataset.sort((a, b) => (a.full_name ?? '').localeCompare(b.full_name ?? ''));
            }

            if (selectedDirection === 'rtl') {
                dataset.reverse();
            }

            return dataset;
        };

        const renderChart = () => {
            const filteredData = getFilteredData();
            const labels = filteredData.map((item) => item.display_name ?? item.full_name ?? '');
            const dataOccupancy = filteredData.map((item) => item.occupancy ?? 0);
            const dataCapacity = filteredData.map((item) => item.capacity ?? 0);
            const minWidth = Math.max((labels.length * 92), 420);

            if (scrollContainer) {
                scrollContainer.style.minWidth = `${minWidth}px`;
                scrollContainer.style.height = '100%';
            }
            chartCanvas.width = minWidth;
            chartCanvas.height = 220;

            if (occupancyChart) {
                occupancyChart.destroy();
            }

            occupancyChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Current Occupancy',
                        data: dataOccupancy,
                        backgroundColor: 'rgba(0, 210, 255, 0.5)',
                        borderColor: 'rgba(0, 210, 255, 1)',
                        borderWidth: 2,
                        borderRadius: 5,
                        barPercentage: 0.72,
                        categoryPercentage: 0.7,
                        maxBarThickness: 28,
                    }, {
                        label: 'Capacity',
                        data: dataCapacity,
                        backgroundColor: 'rgba(255, 193, 7, 0.45)',
                        borderColor: 'rgba(255, 193, 7, 1)',
                        borderWidth: 2,
                        borderRadius: 5,
                        barPercentage: 0.72,
                        categoryPercentage: 0.7,
                        maxBarThickness: 28,
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                title: function(context) {
                                    const dataIndex = context?.[0]?.dataIndex ?? 0;
                                    return filteredData[dataIndex]?.full_name ?? context?.[0]?.label ?? '';
                                },
                                label: function(context) {
                                    const label = context.dataset.label || 'Value';
                                    return `${label}: ${context.parsed.y}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grace: '10%',
                            grid: {
                                color: '#f1f5f9'
                            },
                            ticks: {
                                color: '#64748b'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#64748b',
                                maxRotation: 0,
                                minRotation: 0,
                                autoSkip: false
                            }
                        }
                    }
                }
            });
        };

        renderChart();

        if (sortSelect) {
            sortSelect.addEventListener('change', renderChart);
        }
        directionRadios.forEach((radio) => {
            radio.addEventListener('change', renderChart);
        });
    });


    // 3. Social Print Function
    function printSocialCard() {
        const card = document.getElementById('printCard');
        if (!card) return;

        const html = `<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Typhoon & Flood Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        @page { margin: 0; size: A4 landscape; }
        html, body {
            width: 100%;
            height: 100%;
            background: #0a1128 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            font-family: 'Sora', 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #printCard {
            width: 100%;
            max-width: 100%;
            border-radius: 0 !important;
            box-shadow: none !important;
            border: none !important;
            margin: 0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        @media print {
            @page { margin: 0; size: A4 landscape; }
        }
    </style>
</head>
<body>
    ${card.outerHTML}
    <script>
        window.onload = function() {
            window.print();
            setTimeout(function() { window.close(); }, 500);
        };
    <\/script>
</body>
</html>`;

        const printWin = window.open('', '_blank', 'width=1100,height=780');
        if (!printWin) {
            alert('Pop-up blocked! Please allow pop-ups for this page and try again.');
            return;
        }
        printWin.document.open();
        printWin.document.write(html);
        printWin.document.close();
    }

    // Search function for the Evacuation Centers Table
    const schoolSearchInput = document.getElementById('schoolSearchInput');
    const tableBody = document.getElementById('evacuationCentersTableBody');
    if (schoolSearchInput && tableBody) {
        schoolSearchInput.addEventListener('keyup', function() {
            const query = this.value.toLowerCase();
            const rows = tableBody.querySelectorAll('tr.school-row');
            rows.forEach(row => {
                const text = row.querySelector('.school-name-text')?.textContent.toLowerCase() || '';
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }

    // Table search and status filtering are handled by the pagination controller above.
</script>
@endsection
@include('typhoon.partials.choose-school-modal')

{{-- MODAL: QUICK ANNOUNCEMENT (Global for Admin) --}}
<div class="modal fade" id="announceSomethingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('typhoon.announcements.store') }}">
            @csrf
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header" style="background-color: var(--card-header-bg); color: white;">
                    <h5 class="modal-title fw-bold"><i class="fas fa-bullhorn me-2 text-info"></i>PUBLIC ANNOUNCEMENT</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-dark">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">TITLE / SUBJECT</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. System-wide Relief Distribution Notice" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">URGENCY LEVEL</label>
                        <select name="urgency" class="form-select">
                            <option value="info">INFO - Standard Update</option>
                            <option value="warning">WARNING - Important Notice</option>
                            <option value="danger">URGENT - Critical Requirement</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold text-muted small">MESSAGE CONTENT</label>
                        <textarea name="message" rows="4" class="form-control" placeholder="Type your announcement details here..." required></textarea>
                    </div>
                    <div class="mt-3 small text-muted italic">
                        <i class="fas fa-info-circle me-1"></i> This is a global announcement. It will be visible to ALL users across ALL evacuation centers.
                    </div>
                </div>
                <div class="modal-footer bg-light shadow-sm">
                    <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn btn-info text-white px-5 fw-bold shadow-sm">POST ANNOUNCEMENT</button>
                </div>
            </div>
        </form>
    </div>
</div>
