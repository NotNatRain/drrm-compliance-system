<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <!-- 70/30 Hybrid: Default to desktop view; prevent full mobile collapse -->
    <meta name="viewport" content="width=1024">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'DRRM Compliance Dashboard')</title>
    @if(Route::is('typhoon.*'))
        <link rel="icon" type="image/png" href="{{ asset('images/typhoon-flood-logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/typhoon-flood-logo.png') }}">
    @elseif(Route::is('incidents.*'))
        <link rel="icon" type="image/png" href="{{ asset('images/incident-checklist-logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/incident-checklist-logo.png') }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('images/drrmis-logo-2.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/drrmis-logo-2.png') }}">
    @endif

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('styles')
    <style>
        /* ====================================================
         * 70/30 HYBRID MOBILE APPROACH — app.blade.php scope
         * Desktop structure stays intact. Only minimal tweaks
         * for mobile usability without layout collapse.
         * ==================================================== */

        /* Make all Bootstrap tables horizontally scrollable on mobile (triggered by 1024px viewport lock) */
        @media (max-width: 1024.1px) {
            .table-responsive,
            .table-responsive-md,
            .table-responsive-sm {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch;
            }

            /* Auto-wrap non-.table-responsive tables */
            .card-body table:not(.calendar-grid),
            .card table {
                display: block;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                max-width: 100%;
            }

            /* Increase button tap targets slightly for mobile */
            .btn:not(.btn-sm):not(.btn-xs) {
                min-height: 40px;
                padding-top: 0.45rem !important;
                padding-bottom: 0.45rem !important;
            }

            /* Stack form rows on mobile (col-md-* inside forms only) */
            form .row > [class*="col-md-"],
            form .row > [class*="col-sm-"] {
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }

            /* Dashboard module cards: stack to 2 columns not 1 */
            .col-md-4.mb-4 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media (max-width: 575.98px) {
            /* Full stack only on very small screens */
            .col-md-4.mb-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        @unless(View::hasSection('hide_main_nav'))
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand fw-bold text-primary" href="{{ route('dashboard') }}">
                        @if(Route::is('typhoon.*'))
                            <img src="{{ asset('images/typhoon-flood-logo.png') }}" alt="Typhoon/Flood" style="height: 28px; width: auto; margin-right: 8px;">
                            Typhoon/Flood Monitoring
                        @elseif(Route::is('incidents.*'))
                            <img src="{{ asset('images/incident-checklist-logo.png') }}" alt="Incident Checklist" style="height: 28px; width: auto; margin-right: 8px;">
                            Incident Checklist
                        @else
                            <img src="{{ asset('images/drrmis-logo-2.png') }}" alt="DRRM" style="height: 28px; width: auto; margin-right: 8px;">
                            DRRM Compliance Dashboard
                        @endif
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto">
                            <!-- Left side navigation reserved for future links -->
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-auto">
                            <!-- Authentication Links -->
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                @endif

                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <div class="text-end me-2 d-none d-sm-block">
                                            <div class="fw-bold lh-1" style="font-size: 0.9rem;">{{ Auth::user()->name }}</div>
                                            @if(Auth::user()->school)
                                                <div class="text-muted small" style="font-size: 0.7rem;">
                                                    <i class="fas fa-school me-1"></i>{{ Auth::user()->school->school_name }}
                                                </div>
                                            @else
                                                <div class="text-muted small" style="font-size: 0.7rem;">
                                                    Global Access
                                                </div>
                                            @endif
                                        </div>
                                        <span class="badge {{ Auth::user()->role === 'admin' ? 'bg-danger' : 'bg-info text-dark' }}" style="font-size: 0.7rem;">
                                            {{ ucfirst(Auth::user()->role ?? 'User') }}
                                        </span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item py-2" href="{{ route('users.index') }}">
                                            <i class="fas fa-user-circle text-primary me-2"></i>
                                            {{ Auth::user()->role === 'admin' ? 'User Accounts' : 'User Account' }}
                                        </a>
                                        @if(Auth::user()->role === 'admin')
                                            <a class="dropdown-item py-2" href="{{ route('activity-logs.index') }}">
                                                <i class="fas fa-clipboard-list text-info me-2"></i> Logs
                                            </a>
                                        @endif
                                        <div class="dropdown-divider"></div>
                                        @if(Auth::user()->role === 'admin')
                                            <a class="dropdown-item py-2" href="#" data-bs-toggle="modal" data-bs-target="#announceModal">
                                                <i class="fas fa-bullhorn text-warning me-2"></i> Announce
                                            </a>
                                            <div class="dropdown-divider"></div>
                                        @endif
                                        <a class="dropdown-item py-2" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt text-danger me-2"></i> {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
        @endunless

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
