<!DOCTYPE html>
<html lang="vi" class="{{ session('theme', 'light') === 'dark' ? 'dark' : '' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HRM System') - Nhân viên</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .sidebar::-webkit-scrollbar {
            display: none;
        }

        .sidebar nav {
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .sidebar nav::-webkit-scrollbar {
            display: none;
        }

        .sidebar {
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .sidebar::-webkit-scrollbar {
            display: none;
        }

        body {
            background-color: #f9fafb;
        }

        .dark body {
            background-color: #111827;
        }

        /* Sidebar text hidden khi thu gọn */
        .sidebar.collapsed .logo-container,
        .sidebar.collapsed .menu-text,
        .sidebar.collapsed .user-text {
            display: none !important;
        }

        .sidebar.collapsed .menu-toggle {
            justify-content: center;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .sidebar.collapsed .menu-toggle span:first-child {
            margin-right: 0 !important;
        }

        .sidebar.collapsed .submenu {
            display: none !important;
        }

        .sidebar.collapsed .toggle-btn {
            display: flex !important;
        }

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
            overflow-y: hidden;
        }

        .submenu {
            max-height: 300px;
            overflow-y: auto;
        }

        .dark .sidebar {
            background-color: #1f2937;
        }

        .sidebar.collapsed {
            width: 64px;
        }

        .main-wrapper {
            margin-left: 260px;
            transition: margin-left 0.3s ease-in-out;
            min-height: 100vh;
        }

        .card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        }

        .dark .card {
            background-color: #1f2937;
        }

        .dark .text-gray-900 {
            color: #ffffff;
        }

        .dark .text-gray-600 {
            color: #9ca3af;
        }

        .dark .text-gray-500 {
            color: #6b7280;
        }

        .dark .border-gray-200 {
            border-color: #374151;
        }

        .dark .bg-white {
            background-color: #1f2937;
        }

        .submenu.hidden {
            display: none;
        }

        .sidebar.collapsed .menu-text,
        .sidebar.collapsed .logo-text,
        .sidebar.collapsed .user-text {
            display: none !important;
        }

        .sidebar.collapsed .menu-toggle {
            justify-content: center;
        }

        .sidebar.collapsed .menu-toggle span:first-child {
            margin-right: 0 !important;
        }

        .sidebar.collapsed .submenu {
            display: none !important;
        }

        /* Tooltip */
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
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        header {
            position: sticky;
            top: 0;
            z-index: 40;
            background-color: white;
        }

        .dark header {
            background-color: #1f2937;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 dark:bg-gray-900">
    @include('employee.partials.sidebar')

    <div class="main-wrapper" id="mainWrapper">
        @include('employee.partials.header')

        <main class="p-4 md:p-6">
            @if(session('success'))
                <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 px-4 py-3 rounded-lg mb-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2 text-green-500 dark:text-green-400"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="text-green-500 dark:text-green-400 hover:text-green-700" onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 px-4 py-3 rounded-lg mb-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2 text-red-500 dark:text-red-400"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" class="text-red-500 dark:text-red-400 hover:text-red-700" onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>

        @include('employee.partials.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                const html = document.documentElement;
                const isDark = html.classList.contains('dark');
                html.classList.toggle('dark');
                localStorage.setItem('theme', isDark ? 'light' : 'dark');
            });
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            }
        }

        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebarBtn');
        const mainWrapper = document.getElementById('mainWrapper');

        function updateMainMargin() {
            if (sidebar && mainWrapper) {
                if (sidebar.classList.contains('collapsed')) {
                    mainWrapper.style.marginLeft = '64px';
                } else {
                    mainWrapper.style.marginLeft = '260px';
                }
            }
        }

        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                updateMainMargin();
                localStorage.setItem('sidebar_collapsed', sidebar.classList.contains('collapsed'));
            });

            const savedState = localStorage.getItem('sidebar_collapsed');
            if (savedState === 'true') {
                sidebar.classList.add('collapsed');
                updateMainMargin();
            }
        }

        // Submenu Toggle
        document.querySelectorAll('.menu-toggle').forEach(button => {
            button.addEventListener('click', function(e) {
                const sidebar = document.getElementById('sidebar');
                if (sidebar.classList.contains('collapsed')) return;

                const targetId = this.getAttribute('data-target');
                const submenu = document.getElementById(targetId);
                const arrowIcon = this.querySelector('.arrow-icon');

                if (submenu) {
                    if (submenu.classList.contains('hidden')) {
                        submenu.classList.remove('hidden');
                        if (arrowIcon) arrowIcon.style.transform = 'rotate(180deg)';
                    } else {
                        submenu.classList.add('hidden');
                        if (arrowIcon) arrowIcon.style.transform = 'rotate(0deg)';
                    }
                }
            });
        });

        // Dropdowns
        const notificationToggle = document.getElementById('notificationToggle');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const userMenuToggle = document.getElementById('userMenuToggle');
        const userDropdown = document.getElementById('userDropdown');

        if (notificationToggle && notificationDropdown) {
            notificationToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                notificationDropdown.classList.toggle('hidden');
            });
        }

        if (userMenuToggle && userDropdown) {
            userMenuToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });
        }

        document.addEventListener('click', () => {
            if (notificationDropdown) notificationDropdown.classList.add('hidden');
            if (userDropdown) userDropdown.classList.add('hidden');
        });

        // Mobile sidebar toggle
        const mobileToggle = document.getElementById('mobileSidebarToggle');
        if (mobileToggle && sidebar) {
            mobileToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
        }

        // Click outside để đóng sidebar trên mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 992) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggle = mobileToggle && mobileToggle.contains(event.target);
                if (!isClickInsideSidebar && !isClickOnToggle) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>
    @stack('scripts')
</body>
</html>