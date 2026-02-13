<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'DRRM Compliance Dashboard')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('styles')
</head>
<body>
    <div id="app">
        @unless(View::hasSection('hide_main_nav'))
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand fw-bold text-primary" href="{{ route('dashboard') }}">
                        <i class="fas fa-shield-alt"></i> DRRM Compliance Dashboard
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
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <span class="me-1 fw-bold">{{ Auth::user()->name }}</span>
                                        <span class="badge bg-info text-dark" style="font-size: 0.75rem;">
                                            {{ ucfirst(Auth::user()->role ?? 'User') }}
                                        </span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="navbarDropdown">
                                        @if(Auth::user()->role === 'admin')
                                            <a class="dropdown-item py-2" href="{{ route('users.index') }}">
                                                <i class="fas fa-users-cog text-primary me-2"></i> User Accounts
                                            </a>
                                            <div class="dropdown-divider"></div>
                                        @endif
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
