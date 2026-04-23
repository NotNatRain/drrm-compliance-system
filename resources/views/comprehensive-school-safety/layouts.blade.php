<!--app.blade.php-->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>School Safety Management System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-[Inter] text-slate-800 bg-slate-100 min-h-screen py-0 md:py-8 transition-all overflow-x-hidden">
    <div class="max-w-[1440px] mx-auto h-screen md:h-[calc(100vh-64px)] bg-white md:rounded-[2.5rem] shadow-2xl shadow-slate-300/50 border border-slate-200/60 overflow-hidden relative flex">
        <!-- Mobile Overlay -->
        <div id="mobile-overlay" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden md:hidden transition-opacity duration-300"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed md:relative inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white flex flex-col transition-transform duration-300 transform -translate-x-full md:translate-x-0 md:inset-auto shadow-2xl h-full overflow-hidden">
            <div class="p-6 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-500 flex items-center justify-center">
                        <i data-lucide="shield" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg tracking-tight">Comprehensive School Safety</h1>
                        <p class="text-xs text-slate-400">Compliance System</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 p-4 space-y-1">
                {{-- Back Button (Visible only when inside a school context) --}}
                @if(isset($school))
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 mb-4 text-xs font-semibold text-blue-300 hover:text-white uppercase tracking-wider transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Back to Division
                </a>

                <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">School Menu</p>
                <a href="{{ route('schools.dashboard', $school) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('schools.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                @else
                <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-2">Main</p>

                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                @endif

                @if(isset($school))
                    <a href="{{ route('schools.assessments.index', $school) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('schools.assessments.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                        <span class="font-medium">Assessments</span>
                    </a>

{{--
                    <a href="{{ route('schools.students.index', $school) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('schools.students.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i data-lucide="users" class="w-5 h-5"></i>
                        <span class="font-medium">Students</span>
                    </a>
--}}

                    <a href="{{ route('schools.facilities.index', $school) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('schools.facilities.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i data-lucide="building-2" class="w-5 h-5"></i>
                        <span class="font-medium">Facilities</span>
                    </a>
                @else
                    <a href="{{ route('assessments.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('assessments.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                        <span class="font-medium">Assessments</span>
                    </a>

{{--
                    <a href="{{ route('students.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('students.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i data-lucide="users" class="w-5 h-5"></i>
                        <span class="font-medium">Students</span>
                    </a>
--}}

                    <a href="{{ route('facilities.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('facilities.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i data-lucide="building-2" class="w-5 h-5"></i>
                        <span class="font-medium">Facilities</span>
                    </a>
                @endif

                @if(Auth::user()->role === 'admin')
                <a href="{{ route('accounts.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('accounts.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i data-lucide="users-2" class="w-5 h-5"></i>
                    <span class="font-medium">Accounts</span>
                </a>
                @endif

                <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">Reports</p>

                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                    <span class="font-medium">Analytics</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    <span class="font-medium">Reports</span>
                </a>

            </nav>

            <div class="p-4 border-t border-slate-800">
                <div class="flex items-center gap-3 p-2 rounded-xl mb-2">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-purple-500 to-blue-500 flex items-center justify-center text-white font-bold text-xs uppercase">
                        {{ substr(Auth::user()->name ?? 'GU', 0, 2) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'Guest' }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email ?? '' }}</p>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-400 hover:text-white hover:bg-red-500/20 rounded-lg transition-colors">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50/50 backdrop-blur-sm">
            <!-- Topbar -->
            <header class="h-16 flex items-center justify-between px-8 bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-20">
                <div class="flex items-center gap-4">
                    <button id="mobile-menu-btn" class="md:hidden p-2 text-slate-500 hover:bg-slate-100 rounded-lg transition-colors">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    <h2 class="text-xl font-bold text-slate-800">@yield('title', 'Dashboard')</h2>
                </div>

                <div class="flex items-center gap-4">
                    <div class="relative">
                        <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" placeholder="Search school..." class="pl-10 pr-4 py-2 rounded-full bg-slate-100 border-none text-sm focus:ring-2 focus:ring-blue-500 w-64 transition-all">
                    </div>
                    <button class="p-2 text-slate-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors relative">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-8 scroll-smooth">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();

        // Mobile Menu Logic
        const sidebar = document.getElementById('sidebar');
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const overlay = document.getElementById('mobile-overlay');

        function toggleMenu() {
            const isHidden = sidebar.classList.contains('-translate-x-full');
            if (isHidden) {
                // Open menu
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                overlay.classList.add('opacity-100');
            } else {
                // Close menu
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                overlay.classList.remove('opacity-100');
            }
        }

        if (mobileBtn) {
            mobileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleMenu();
            });
        }

        if (overlay) {
            overlay.addEventListener('click', () => {
                toggleMenu();
            });
        }

        // Close on window resize if moving to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                // Reset states for desktop
                if (!sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.add('-translate-x-full'); // Actually on desktop md:translate-x-0 overrides this, but good to keep state clean
                    overlay.classList.add('hidden');
                }
            }
        });
    </script>
</body>
</html>

<!--guest.blade.php-->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - School Safety Management System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-[Inter] bg-slate-100 text-slate-800 antialiased min-h-screen flex items-center justify-center px-4 py-8 relative overflow-hidden">

    <!-- Background glow -->
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_-20%,#3b82f615,transparent)] pointer-events-none"></div>

    <!-- Centered Container -->
    <div class="relative z-10 w-full max-w-md mx-auto">
        @yield('content')
    </div>

</body>
</html>

<!--landing.blade.php-->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                        <i data-lucide="shield" class="w-5 h-5 text-white"></i>
                    </div>
                    <span class="font-bold text-xl tracking-tight text-slate-800">Comprehensive School Safety <span class="text-slate-400 font-normal text-sm ml-1">Portal</span></span>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden md:block">
                            <p class="text-sm font-medium text-slate-700">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500">Division Office</p>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                            <i data-lucide="log-out" class="w-5 h-5"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Wrapped in a Boxed Container -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-200/60 overflow-hidden p-8 md:p-12 min-h-[80vh]">
            @yield('content')
        </div>
    </div>

    <footer class="bg-white border-t border-slate-200 py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center text-slate-400 text-sm">
            &copy; {{ date('Y') }} School Safety Management System - Olongapo City Division. All rights reserved.
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
