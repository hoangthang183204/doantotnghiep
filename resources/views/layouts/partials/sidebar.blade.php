@php
    $currentRoute = request()->route()->getName();
    
    $menuItems = [
        [
            'title' => 'Dashboard',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
            'route' => 'admin.dashboard',
            'active' => $currentRoute == 'admin.dashboard'
        ],
        [
            'title' => 'Quản lý nhân sự',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>',
            'submenu' => [
                ['title' => 'Danh sách nhân viên', 'route' => 'admin.nguoi-dung.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>', 'active' => str_contains($currentRoute, 'nguoi-dung')],
                ['title' => 'Phòng ban', 'route' => 'admin.phong-ban.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>', 'active' => str_contains($currentRoute, 'phong-ban')],
                ['title' => 'Chức vụ', 'route' => 'admin.chuc-vu.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>', 'active' => str_contains($currentRoute, 'chuc-vu')],
            ]
        ],
        [
            'title' => 'Chấm công',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'submenu' => [
                ['title' => 'Chấm công hàng ngày', 'route' => 'admin.cham-cong.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>', 'active' => str_contains($currentRoute, 'cham-cong')],
                ['title' => 'Đăng ký tăng ca', 'route' => 'admin.tang-ca.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>', 'active' => str_contains($currentRoute, 'tang-ca')],
            ]
        ],
        [
            'title' => 'Nghỉ phép',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
            'submenu' => [
                ['title' => 'Đơn xin nghỉ', 'route' => 'admin.don-nghi.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>', 'active' => str_contains($currentRoute, 'don-nghi')],
                ['title' => 'Loại nghỉ phép', 'route' => 'admin.loai-nghi.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>', 'active' => str_contains($currentRoute, 'loai-nghi')],
            ]
        ],
        [
            'title' => 'Lương thưởng',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'submenu' => [
                ['title' => 'Bảng lương', 'route' => 'admin.bang-luong.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>', 'active' => str_contains($currentRoute, 'bang-luong')],
                ['title' => 'Phụ cấp', 'route' => 'admin.phu-cap.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>', 'active' => str_contains($currentRoute, 'phu-cap')],
            ]
        ],
        [
            'title' => 'Tuyển dụng',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
            'submenu' => [
                ['title' => 'Tin tuyển dụng', 'route' => 'admin.tin-tuyen-dung.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>', 'active' => str_contains($currentRoute, 'tin-tuyen-dung')],
                ['title' => 'Ứng viên', 'route' => 'admin.ung-vien.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>', 'active' => str_contains($currentRoute, 'ung-vien')],
            ]
        ],
    ];
@endphp

<div class="fixed inset-0 bg-gray-600 bg-opacity-50 z-30 hidden sidebar-overlay"></div>

<aside class="sidebar">
    <div class="flex items-center justify-between px-4 py-4 border-b dark:border-gray-700">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                <span class="text-white font-bold text-xl">H</span>
            </div>
            <span class="text-xl font-bold text-gray-800 dark:text-white">HR Flow</span>
        </a>
        <button type="button" class="lg:hidden text-gray-500 dark:text-gray-400" id="closeSidebar">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    
    <nav class="flex-1 overflow-y-auto px-3 py-4 h-[calc(100vh-5rem)]">
        <ul class="space-y-1">
            @foreach($menuItems as $item)
                @if(isset($item['submenu']))
                    <li>
                        <button type="button" class="menu-toggle flex items-center w-full px-3 py-2.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                data-target="submenu-{{ Str::slug($item['title']) }}">
                            <span class="w-5 h-5 mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    {!! $item['icon'] !!}
                                </svg>
                            </span>
                            <span class="flex-1 text-left">{{ $item['title'] }}</span>
                            <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <ul class="submenu pl-10 mt-1 space-y-1 hidden" id="submenu-{{ Str::slug($item['title']) }}">
                            @foreach($item['submenu'] as $subitem)
                                <li>
                                    <a href="{{ route($subitem['route']) }}" 
                                       class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ $subitem['active'] ? 'bg-gray-100 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            {!! $subitem['icon'] !!}
                                        </svg>
                                        <span>{{ $subitem['title'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li>
                        <a href="{{ route($item['route']) }}" 
                           class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ $item['active'] ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <span class="w-5 h-5 mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    {!! $item['icon'] !!}
                                </svg>
                            </span>
                            <span>{{ $item['title'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>
    
    <div class="border-t dark:border-gray-700 p-4 absolute bottom-0 w-full bg-white dark:bg-gray-800">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                AD
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">Admin</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Quản trị viên</p>
            </div>
        </div>
    </div>
</aside>