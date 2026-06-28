{{-- resources/views/layouts/master.blade.php --}}
<!DOCTYPE html>
<html lang="vi" class="{{ session('theme', 'light') === 'dark' ? 'dark' : '' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HRFlow')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <script>
        tailwind.config = { darkMode: 'class' }
    </script>

    <style>
        * { font-family: 'Inter', sans-serif; }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 50;
            width: 260px;
            background-color: white;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
            transition: width 0.3s ease-in-out;
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .sidebar::-webkit-scrollbar { display: none; }
        .dark .sidebar { background-color: #1f2937; }
        .sidebar.collapsed { width: 64px; }

        .sidebar.collapsed .logo-text,
        .sidebar.collapsed .menu-text,
        .sidebar.collapsed .user-text,
        .sidebar.collapsed .arrow-icon {
            display: none !important;
        }
        .sidebar.collapsed .menu-toggle,
        .sidebar.collapsed > ul > li > a {
            justify-content: center !important;
            padding: 10px 0 !important;
        }
        .sidebar.collapsed .menu-toggle .w-5,
        .sidebar.collapsed > ul > li > a .w-5 {
            margin-right: 0 !important;
        }
        .sidebar.collapsed .submenu { display: none !important; }

        .main-wrapper {
            margin-left: 260px;
            transition: margin-left 0.3s ease-in-out;
            min-height: 100vh;
        }
        .main-wrapper.collapsed { margin-left: 64px; }

        .dark body { background-color: #111827; }
        .dark .card { background-color: #1f2937; }
        .dark .border-gray-200 { border-color: #374151; }
        .dark .bg-white { background-color: #1f2937; }
        .dark .text-gray-600 { color: #9ca3af; }
        .dark .text-gray-900 { color: #ffffff; }

        .submenu.hidden { display: none; }

        header {
            position: sticky;
            top: 0;
            z-index: 40;
            background-color: white;
        }
        .dark header { background-color: #1f2937; }

        /* Tooltip cho sidebar thu gọn */
        .sidebar.collapsed .menu-toggle:hover::after,
        .sidebar.collapsed a:hover::after {
            content: attr(data-title);
            position: absolute;
            left: 70px;
            background: #1f2937;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 60;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 dark:bg-gray-900">
    @include('layouts.partials.sidebar-dynamic')

    <div class="main-wrapper" id="mainWrapper">
        @include('layouts.partials.header')

        @include('layouts.partials.alerts')

        <main class="p-4 md:p-6">
            @yield('content')
        </main>

        @include('layouts.partials.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                document.documentElement.classList.toggle('dark');
                localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
            });
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        }

        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebarBtn');
        const mainWrapper = document.getElementById('mainWrapper');

        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                mainWrapper.classList.toggle('collapsed');
                localStorage.setItem('sidebar_collapsed', sidebar.classList.contains('collapsed'));
            });

            if (localStorage.getItem('sidebar_collapsed') === 'true') {
                sidebar.classList.add('collapsed');
                mainWrapper.classList.add('collapsed');
            }
        }

        // Submenu Toggle
        document.querySelectorAll('.menu-toggle').forEach(button => {
            button.addEventListener('click', function(e) {
                if (document.getElementById('sidebar').classList.contains('collapsed')) return;

                const targetId = this.getAttribute('data-target');
                const submenu = document.getElementById(targetId);
                const arrowIcon = this.querySelector('.arrow-icon');

                if (submenu) {
                    submenu.classList.toggle('hidden');
                    if (arrowIcon) {
                        arrowIcon.style.transform = submenu.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
                    }
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>