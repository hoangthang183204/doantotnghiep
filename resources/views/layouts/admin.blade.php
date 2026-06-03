<!DOCTYPE html>
<html lang="vi" class="{{ session('theme', 'light') === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HRM System') - Admin</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        * { font-family: 'Inter', sans-serif; }
        
        body { background-color: #f9fafb; }
        .dark body { background-color: #111827; }
        
        .sidebar {
            position: fixed; left: 0; top: 0; z-index: 40;
            width: 16rem; height: 100vh;
            background-color: white;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
            transition: transform 0.3s ease-in-out;
            transform: translateX(-100%);
        }
        .dark .sidebar { background-color: #1f2937; }
        @media (min-width: 1024px) { .sidebar { transform: translateX(0); } }
        
        .main-wrapper { transition: margin-left 0.3s ease-in-out; }
        @media (min-width: 1024px) { .main-wrapper { margin-left: 16rem; } }
        
        .card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        }
        .dark .card {
            background-color: #1f2937;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.3);
        }
        
        .dark .text-gray-900 { color: #ffffff; }
        .dark .text-gray-600 { color: #9ca3af; }
        .dark .text-gray-500 { color: #6b7280; }
        .dark .border-gray-200 { border-color: #374151; }
        .dark .bg-gray-50 { background-color: #111827; }
        .dark .bg-white { background-color: #1f2937; }
        .dark .hover\:bg-gray-50:hover { background-color: #374151; }
        
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background-color: rgb(0 0 0 / 0.5);
            z-index: 30;
        }
        @media (min-width: 1024px) { .sidebar-overlay { display: none; } }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    @include('layouts.partials.sidebar')
    
    <div class="main-wrapper">
        @include('layouts.partials.header')
        
        <main class="min-h-screen p-4 md:p-6">
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
                const html = document.documentElement;
                const isDark = html.classList.contains('dark');
                html.classList.toggle('dark');
                localStorage.setItem('theme', isDark ? 'light' : 'dark');
            });
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') document.documentElement.classList.add('dark');
        }
        
        // Sidebar Toggle
        const showSidebar = document.getElementById('showSidebar');
        const closeSidebar = document.getElementById('closeSidebar');
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        
        if (showSidebar && sidebar) {
            showSidebar.addEventListener('click', () => {
                sidebar.style.transform = 'translateX(0)';
                if (overlay) overlay.classList.remove('hidden');
            });
        }
        if (closeSidebar && sidebar) {
            closeSidebar.addEventListener('click', () => {
                sidebar.style.transform = 'translateX(-100%)';
                if (overlay) overlay.classList.add('hidden');
            });
        }
        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.style.transform = 'translateX(-100%)';
                overlay.classList.add('hidden');
            });
        }
        
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
        
        // Submenu Toggle
        document.querySelectorAll('.menu-toggle').forEach(button => {
            button.addEventListener('click', () => {
                const targetId = button.dataset.target;
                const submenu = document.getElementById(targetId);
                if (submenu) submenu.classList.toggle('hidden');
            });
        });
    </script>
    @stack('scripts')
</body>
</html>