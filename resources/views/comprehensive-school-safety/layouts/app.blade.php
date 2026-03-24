<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Comprehensive School Safety')</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --csss-primary: #5c4033;
            --csss-primary-dark: #3f2a21;
            --csss-primary-soft: #8b6f47;
            --csss-surface: #f4efe8;
            --csss-card: #ffffff;
            --csss-border: #e4d8c9;
            --csss-text: #2b221d;
            --csss-muted: #7a6a5f;
            --csss-sidebar: #1f1a17;
            --csss-sidebar-muted: #b9aba0;
        }

        body {
            margin: 0;
            background: radial-gradient(circle at top left, #f9f5ee, #f3ece2 40%, #efe7dc);
            color: var(--csss-text);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .csss-shell {
            min-height: 100vh;
            display: flex;
        }

        .csss-sidebar {
            width: 280px;
            background: linear-gradient(180deg, #2a211c 0%, var(--csss-sidebar) 100%);
            color: #fff;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.2);
        }

        .csss-brand {
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            padding-bottom: 1rem;
        }

        .csss-brand h5 {
            margin: 0;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .csss-brand small {
            color: var(--csss-sidebar-muted);
        }

        .csss-menu-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #cdbfb2;
            margin-top: 0.5rem;
            margin-bottom: 0.35rem;
            letter-spacing: 0.08em;
        }

        .csss-menu-link {
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.03);
            color: #f2e7da;
            border-radius: 12px;
            padding: 0.65rem 0.75rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.92rem;
            transition: all 0.2s ease;
        }

        .csss-menu-link:hover {
            background: rgba(255, 255, 255, 0.11);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.2);
        }

        .csss-menu-link.active {
            background: linear-gradient(135deg, var(--csss-primary) 0%, var(--csss-primary-soft) 100%);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 8px 20px rgba(92, 64, 51, 0.35);
        }

        .csss-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .csss-topbar {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--csss-border);
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(8px);
            position: sticky;
            top: 0;
            z-index: 5;
        }

        .csss-search {
            max-width: 580px;
        }

        .csss-search .input-group-text,
        .csss-search .form-control {
            border-color: var(--csss-border);
            background: #fff;
        }

        .csss-notification {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            border: 1px solid var(--csss-border);
            background: #fff;
            color: var(--csss-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .csss-content {
            padding: 1.5rem;
        }

        .csss-card {
            border: 1px solid var(--csss-border);
            background: rgba(255, 255, 255, 0.85);
            border-radius: 16px;
            box-shadow: 0 8px 22px rgba(92, 64, 51, 0.08);
        }

        .csss-section-title {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--csss-primary-dark);
        }

        .csss-muted {
            color: var(--csss-muted);
        }

        @media (max-width: 991.98px) {
            .csss-shell {
                display: block;
            }

            .csss-sidebar {
                width: 100%;
                border-radius: 0 0 14px 14px;
            }

            .csss-topbar {
                position: static;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
<div class="csss-shell">
    @php
        $selectedSchool = $school ?? null;
        $activeMenu = trim($__env->yieldContent('activeMenu'));
    @endphp

    <aside class="csss-sidebar">
        <div class="csss-brand">
            <h5>Comprehensive School Safety</h5>
            <small>
                @if($selectedSchool)
                    {{ $selectedSchool->name }}
                @else
                    Schools Directory
                @endif
            </small>
        </div>

        <div class="csss-menu-label">School Menu</div>

        <a class="csss-menu-link {{ $activeMenu === 'dashboard' ? 'active' : '' }}"
           href="{{ $selectedSchool ? route('comprehensive-school-safety.school.dashboard', $selectedSchool->id) : route('comprehensive-school-safety.dashboard') }}">
            <i class="fas fa-chart-line"></i> Dashboard
        </a>

        <a class="csss-menu-link {{ $activeMenu === 'assessments' ? 'active' : '' }}"
           href="{{ $selectedSchool ? route('comprehensive-school-safety.school.assessments', $selectedSchool->id) : route('comprehensive-school-safety.dashboard') }}">
            <i class="fas fa-clipboard-check"></i> Assessments
        </a>

        <a class="csss-menu-link {{ $activeMenu === 'students' ? 'active' : '' }}"
           href="{{ $selectedSchool ? route('comprehensive-school-safety.school.students', $selectedSchool->id) : route('comprehensive-school-safety.dashboard') }}">
            <i class="fas fa-users"></i> Students
        </a>

        <a class="csss-menu-link {{ $activeMenu === 'facilities' ? 'active' : '' }}"
           href="{{ $selectedSchool ? route('comprehensive-school-safety.school.facilities', $selectedSchool->id) : route('comprehensive-school-safety.dashboard') }}">
            <i class="fas fa-building"></i> Facilities
        </a>

        <div class="csss-menu-label">Reports Menu</div>
        <a class="csss-menu-link {{ $activeMenu === 'reports' ? 'active' : '' }}"
           href="{{ $selectedSchool ? route('comprehensive-school-safety.school.reports', $selectedSchool->id) : route('comprehensive-school-safety.dashboard') }}">
            <i class="fas fa-chart-pie"></i> Analytics &amp; Reports
        </a>

        <div class="mt-auto pt-3 border-top border-secondary-subtle">
            <a class="csss-menu-link" href="{{ route('comprehensive-school-safety.dashboard') }}">
                <i class="fas fa-school"></i> Switch School
            </a>
            <a class="csss-menu-link mt-2" href="{{ route('dashboard') }}">
                <i class="fas fa-arrow-left"></i> Main System
            </a>
        </div>
    </aside>

    <div class="csss-main">
        <div class="csss-topbar d-flex align-items-center justify-content-between gap-3">
            <div class="csss-search w-100">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search schools, assessments, students, facilities...">
                </div>
            </div>
            <button type="button" class="csss-notification" aria-label="Notifications">
                <i class="fas fa-bell"></i>
            </button>
        </div>

        <main class="csss-content">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
