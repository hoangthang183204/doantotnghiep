<!DOCTYPE html>
<html lang="vi" class="{{ session('theme', 'light') === 'dark' ? 'dark' : '' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1d4ed8">
    <link rel="manifest" href="/manifest.webmanifest">
    <title>@yield('title', 'HR Flow')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

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

        body {
            background-color: #f9fafb;
        }
        .dark body {
            background-color: #111827;
        }

        /* Sidebar */
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
        }
        .dark .sidebar {
            background-color: #1f2937;
        }
        .sidebar.collapsed {
            width: 64px;
        }

        .sidebar.collapsed .logo-container,
        .sidebar.collapsed .menu-text,
        .sidebar.collapsed .user-text,
        .sidebar.collapsed .logo-text {
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

        .submenu {
            max-height: 300px;
            overflow-y: auto;
        }
        .submenu.hidden {
            display: none;
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

        header {
            position: sticky;
            top: 0;
            z-index: 40;
            background-color: white;
        }
        .dark header {
            background-color: #1f2937;
        }

        /* Mobile */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
                width: 280px;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0 !important;
            }
            .main-wrapper.collapsed {
                margin-left: 0 !important;
            }
            main {
                padding-bottom: 5rem;
            }
        }
    </style>

    @include('layouts.partials.hr-page-style')
    @stack('styles')
</head>

<body class="bg-gray-50 dark:bg-gray-900">
    {{-- Sidebar chung --}}
    @include('layouts.partials.sidebar')

    <div class="main-wrapper" id="mainWrapper">
        {{-- Header chung --}}
        @include('layouts.partials.header')

        <main class="p-4 md:p-6">
            {{-- Alert --}}
            @include('layouts.partials.alerts')

            @yield('content')
        </main>

        {{-- Footer chung --}}
        @include('layouts.partials.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <nav class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 flex justify-around py-2 text-[11px] text-gray-600">
        @if(Route::has('employee.cham-cong-face.index'))
            <a href="{{ route('employee.cham-cong-face.index') }}" class="flex flex-col items-center">Chấm công</a>
        @endif
        @if(Route::has('employee.bang-luong.index'))
            <a href="{{ route('employee.bang-luong.index') }}" class="flex flex-col items-center">Phiếu lương</a>
        @endif
        @if(Route::has('employee.don-nghi.index'))
            <a href="{{ route('employee.don-nghi.index') }}" class="flex flex-col items-center">Đơn từ</a>
        @endif
        @if(Route::has('employee.hop-dong.index'))
            <a href="{{ route('employee.hop-dong.index') }}" class="flex flex-col items-center">Hợp đồng</a>
        @endif
        <button type="button" id="batThongBao" class="flex flex-col items-center">Thông báo</button>
    </nav>
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

        console.log('✅ Master layout loaded');

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').catch(() => {});
        }
        const nutThongBao = document.getElementById('batThongBao');
        if (nutThongBao) {
            nutThongBao.addEventListener('click', async () => {
                const quyen = await Notification.requestPermission();
                if (quyen !== 'granted') return;
                const reg = await navigator.serviceWorker.ready;
                const keyRes = await fetch('{{ route('employee.push.vapid') }}');
                const key = await keyRes.json();
                if (!key.publicKey) {
                    alert('Đã bật thông báo trên thiết bị này.');
                    return;
                }
                const sub = await reg.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: (function (base64String) {
                        const padding = '='.repeat((4 - base64String.length % 4) % 4);
                        const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
                        const raw = atob(base64);
                        const output = new Uint8Array(raw.length);
                        for (let i = 0; i < raw.length; ++i) output[i] = raw.charCodeAt(i);
                        return output;
                    })(key.publicKey)
                });
                await fetch('{{ route('employee.push.subscribe') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(sub)
                });
                alert('Đã bật thông báo đẩy.');
            });
        }
    </script>
    @stack('scripts')
</body>
</html>