<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=1024">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'DRRM Compliance Dashboard')</title>
    @if(Request::is('typhoon*') || Route::is('typhoon.*'))
        <link rel="icon" type="image/png" href="{{ asset('images/typhoon-flood-logo.png') }}?v=2">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/typhoon-flood-logo.png') }}?v=2">
        <link rel="apple-touch-icon" href="{{ asset('images/typhoon-flood-logo.png') }}?v=2">
    @elseif(Request::is('incidents*') || Route::is('incidents.*'))
        <link rel="icon" type="image/png" href="{{ asset('images/incident-checklist-logo.png') }}?v=2">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/incident-checklist-logo.png') }}?v=2">
        <link rel="apple-touch-icon" href="{{ asset('images/incident-checklist-logo.png') }}?v=2">
    @elseif(Request::is('fire-safety*') || Route::is('fire-safety.*'))
        <link rel="icon" type="image/png" href="{{ asset('images/fire-safety-logo.png') }}?v=2">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/fire-safety-logo.png') }}?v=2">
        <link rel="apple-touch-icon" href="{{ asset('images/fire-safety-logo.png') }}?v=2">
    @else
        <link rel="icon" type="image/png" href="{{ asset('images/drrmis-logo-2.png') }}?v=2">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/drrmis-logo-2.png') }}?v=2">
        <link rel="apple-touch-icon" href="{{ asset('images/drrmis-logo-2.png') }}?v=2">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('styles')
    
    <style>
        /* Base resets matching wireframe */
        :root {
            --app-bg: #F8FAFC;
            --navy: #0D1B36;
            --orange: #E05C2E;
            --font-display: 'Sora', sans-serif;
            --font-body: 'Inter', sans-serif;
        }

        body {
            background-color: var(--app-bg);
            font-family: var(--font-body);
        }

        /* Navbar Styling */
        .app-navbar {
            background-color: #FFFFFF;
            border-bottom: 1px solid #E2E8F0;
            padding: 12px 0;
            box-shadow: none;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-brand img {
            height: 32px;
            width: auto;
            margin-right: 12px;
        }

        .navbar-brand-text {
            font-weight: 800;
            color: var(--navy);
            font-size: 1.1rem;
            font-family: var(--font-display);
        }

        /* User Profile Toggle */
        .user-toggle {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .user-toggle:hover, .show > .user-toggle {
            background: #F1F5F9;
        }

        .user-info {
            text-align: right;
            display: none;
        }
        @media (min-width: 768px) { .user-info { display: block; } }

        .user-name {
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--navy);
            line-height: 1.2;
        }

        .user-role-badge {
            font-size: 0.7rem;
            color: #64748B;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
        }

        .role-text-admin { color: #EF4444; font-weight: 700; }
        .role-text-user { color: #3B82F6; font-weight: 700; }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--navy);
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            flex-shrink: 0;
        }

        /* Custom Dropdown Menu */
        .custom-dropdown-menu {
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(13,27,54,0.08);
            padding: 16px;
            min-width: 260px;
            margin-top: 12px;
            animation: dropdownFade 0.2s ease;
        }

        @keyframes dropdownFade {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-section-title {
            font-size: 0.7rem;
            font-weight: 800;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 8px;
            padding: 0 12px;
        }

        .dropdown-item-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 8px;
            color: var(--navy);
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
        }

        .dropdown-item-custom:hover {
            background-color: #F1F5F9;
            color: var(--navy);
        }

        .dropdown-item-custom .icon-wrapper {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #E2E8F0;
            color: #64748B;
            flex-shrink: 0;
        }

        /* Specific Icon Colors based on wireframe */
        .item-users .icon-wrapper { background: #E0E7FF; color: #4F46E5; }
        .item-logs .icon-wrapper { background: #E0F2FE; color: #0284C7; }
        .item-announce .icon-wrapper { background: #FEF3C7; color: #D97706; }
        .item-download .icon-wrapper { background: #DCFCE7; color: #16A34A; }
        
        .item-logout { color: #EF4444; }
        .item-logout .icon-wrapper { background: #FEE2E2; color: #EF4444; }
        .item-logout:hover { background-color: #FEF2F2; color: #DC2626; }

        .dropdown-divider-custom {
            height: 1px;
            background-color: #E2E8F0;
            margin: 12px 0;
        }

        /* Hide default caret */
        .dropdown-toggle::after { display: none; }
        .caret-icon { color: #94A3B8; font-size: 0.8rem; margin-left: 4px; transition: transform 0.2s; }
        .show > .user-toggle .caret-icon { transform: rotate(180deg); }

        /* Mobile tweaks */
        @media (max-width: 1024.1px) {
            .table-responsive { overflow-x: auto !important; -webkit-overflow-scrolling: touch; }
            .card table { display: block; overflow-x: auto; max-width: 100%; }
        }
    </style>
</head>
<body>
    <div id="app">
        @unless(View::hasSection('hide_main_nav'))
            <nav class="navbar navbar-expand-md app-navbar">
                <div class="container-fluid px-4 px-lg-5 position-relative">
                    <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                        @if(Route::is('typhoon.*'))
                            <img src="{{ asset('images/typhoon-flood-logo.png') }}" alt="Typhoon/Flood">
                            <span class="navbar-brand-text d-none d-sm-inline-block">Evacuation Monitoring</span>
                        @elseif(Route::is('incidents.*'))
                            <img src="{{ asset('images/incident-checklist-logo.png') }}" alt="Incident Checklist">
                            <span class="navbar-brand-text d-none d-sm-inline-block">Incident Checklist</span>
                        @else
                            <img src="{{ asset('images/drrmis-logo-2.png') }}" alt="DRRM">
                            <span class="navbar-brand-text d-none d-sm-inline-block">DRRM Compliance Dashboard</span>
                        @endif
                    </a>

                    <!-- Live Time & Date -->
                    <div class="d-none d-lg-flex flex-column align-items-center position-absolute top-50 start-50 translate-middle text-center" style="pointer-events: none;">
                        <span id="nav-time" class="fw-bold" style="font-family: var(--font-display, 'Sora', sans-serif); color: #1D232A; font-size: 0.75rem; line-height: 1; letter-spacing: 0.5px;">--:--:--</span>
                        <span id="nav-date" class="fw-semibold text-uppercase" style="font-family: var(--font-body, 'Inter', sans-serif); color: #414F62; font-size: 0.65rem; letter-spacing: 0.5px; margin-top: 2px;">---</span>
                    </div>

                    <ul class="navbar-nav ms-auto flex-row">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item me-3"><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
                            @endif
                        @else
                            @php
                                $names = explode(' ', Auth::user()->name);
                                $initials = substr($names[0], 0, 1) . (isset($names[1]) ? substr($names[1], 0, 1) : '');
                            @endphp
                            <li class="nav-item d-flex align-items-center me-3">
                                @isset($announcements)
                                    <div class="position-relative announcement-bell-btn" data-bs-toggle="offcanvas" data-bs-target="#announcementsOffcanvas" role="button" title="View Announcements">
                                        <i class="fas fa-bell fs-5" style="{{ $announcements->count() > 0 ? 'animation: swing 2s ease-in-out infinite; transform-origin: top center;' : '' }}"></i>
                                        @if($announcements->count() > 0)
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; padding: 0.25em 0.5em; font-family: var(--font-body, 'Inter', sans-serif);">
                                                {{$announcements->count()}}
                                            </span>
                                        @endif
                                    </div>
                                    <style>
                                        .announcement-bell-btn {
                                            color: var(--navy, #0D1B36);
                                            cursor: pointer;
                                            transition: all 0.2s ease-in-out;
                                            padding: 8px;
                                            border-radius: 50%;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                        }
                                        .announcement-bell-btn:hover {
                                            color: var(--orange, #E05C2E);
                                            background-color: rgba(224, 92, 46, 0.1);
                                            transform: scale(1.1);
                                        }
                                        @keyframes swing {
                                            0% { transform: rotate(0deg); }
                                            10% { transform: rotate(15deg); }
                                            20% { transform: rotate(-10deg); }
                                            30% { transform: rotate(5deg); }
                                            40% { transform: rotate(-5deg); }
                                            50%, 100% { transform: rotate(0deg); }
                                        }
                                    </style>
                                @endisset
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link p-0 text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <div class="user-toggle">
                                        <div class="user-info">
                                            <div class="user-name">{{ Auth::user()->name }}</div>
                                            <div class="user-role-badge">
                                                @if(Auth::user()->school)
                                                    <span>{{ Str::limit(Auth::user()->school->school_name, 20) }}</span>
                                                @else
                                                    <span>Global Access</span>
                                                @endif
                                                <span class="role-text-{{ Auth::user()->role === 'admin' ? 'admin' : 'user' }}">{{ ucfirst(Auth::user()->role ?? 'User') }}</span>
                                            </div>
                                        </div>
                                        <div class="user-avatar">{{ strtoupper($initials) }}</div>
                                        <i class="fas fa-chevron-down caret-icon"></i>
                                    </div>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end custom-dropdown-menu" aria-labelledby="navbarDropdown">
                                    <div class="dropdown-section-title">Administration</div>
                                    <a class="dropdown-item-custom item-users" href="{{ route('users.index') }}">
                                        <div class="icon-wrapper"><i class="fas fa-user-friends"></i></div>
                                        {{ Auth::user()->role === 'admin' ? 'User Accounts' : 'User Account' }}
                                    </a>
                                    
                                    @if(Auth::user()->role === 'admin')
                                        <a class="dropdown-item-custom item-logs" href="{{ route('activity-logs.index') }}">
                                            <div class="icon-wrapper"><i class="fas fa-file-alt"></i></div>
                                            Logs
                                        </a>
                                        
                                        <div class="dropdown-divider-custom"></div>
                                        
                                        <div class="dropdown-section-title">Actions</div>
                                        <a class="dropdown-item-custom item-announce" href="#" data-bs-toggle="modal" data-bs-target="#announceModal">
                                            <div class="icon-wrapper"><i class="fas fa-bullhorn"></i></div>
                                            Announce
                                        </a>
                                        <a class="dropdown-item-custom item-download" href="{{ route('admin.database.download') }}">
                                            <div class="icon-wrapper"><i class="fas fa-download"></i></div>
                                            Download Database
                                        </a>
                                    @endif

                                    <div class="dropdown-divider-custom"></div>
                                    
                                    <a class="dropdown-item-custom item-logout" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <div class="icon-wrapper"><i class="fas fa-sign-out-alt"></i></div>
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </nav>
        @endunless

        <main class="{{ View::hasSection('hide_main_nav') ? 'p-0' : 'py-4' }}">
            @yield('content')
        </main>
    </div>
    @stack('scripts')
    <script>
        function updateNavDateTime() {
            const timeEl = document.getElementById('nav-time');
            const dateEl = document.getElementById('nav-date');
            if(!timeEl || !dateEl) return;
            
            const now = new Date();
            timeEl.textContent = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', second: '2-digit', hour12: true });
            dateEl.textContent = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' });
        }
        setInterval(updateNavDateTime, 1000);
        updateNavDateTime();
    </script>
</body>
</html>
