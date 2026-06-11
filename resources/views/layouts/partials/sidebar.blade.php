@php
    $currentRoute = request()->route()->getName();
    $userRole = session('user.vai_tro', 'Super Admin');
    $isAdmin = true;
    $isTruongPhong = true;
    $isKeToan = true;
    $isNhanVien = true;

    $menuItems = [
        [
            'title' => 'Tổng quan',
            'icon' =>
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
            'route' => '/admin',
            'active' => $currentRoute == 'admin',
            'visible' => true,
        ],
        [
            'title' => 'Nhân sự',
            'icon' =>
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>',
            'visible' => true,
            'submenu' => [
                [
                    'title' => 'Hồ sơ nhân viên',
                    'route' => 'admin.ho-so.index',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>',
                ],
                [
                    'title' => 'Tài khoản',
                    'route' => 'admin.nguoi-dung.index',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>',
                ],
                [
                    'title' => 'Chức vụ',
                    'route' => 'admin.chuc-vu.index',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
                ],
                [
                    'title' => 'Phòng ban',
                    'route' => 'admin.phong-ban.index',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
                ],
                [
                    'title' => 'Vai trò',
                    'route' => 'admin.vai-tro.index',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
                ],
            ],
        ],
        [
            'title' => 'Chấm công',
            'icon' =>
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'visible' => true,
            'submenu' => [
                [
                    'title' => 'Danh sách chấm công',
                    'route' => 'admin.cham-cong.index',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>',
                ],
                [
                    'title' => 'Phê duyệt tăng ca',
                    'route' => 'admin.tang-ca.index',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                ],
                [
                    'title' => 'Danh sách tăng ca',
                    'route' => 'admin.thuc-hien-tang-ca.index',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                ],
                [
                    'title' => 'Import chấm công',
                    'route' => '#',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>',
                ],
                [
                    'title' => 'Quản lý vị trí',
                    'route' => '#',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>',
                ],
                [
                    'title' => 'Quản lý thời gian',
                    'route' => '#',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                ],
                [
                    'title' => 'Yêu cầu chỉnh công',
                    'route' => '#',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>',
                ],
            ],
        ],
        [
            'title' => 'Lương',
            'icon' =>
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'visible' => true,
            'submenu' => [
                [
                    'title' => 'Danh sách lương',
                    'route' => 'admin.bang-luong.index',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>',
                ],
                [
                    'title' => 'Tính lương',
                    'route' => '#',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>',
                ],
                [
                    'title' => 'Bảng lương',
                    'route' => '#',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
                ],
                [
                    'title' => 'Phụ cấp',
                    'route' => 'admin.phu-cap.index',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                ],
            ],
        ],
        [
            'title' => 'Ứng viên',
            'icon' =>
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>',
            'visible' => true,
            'submenu' => [
                [
                    'title' => 'Danh sách ứng viên',
                    'route' => 'admin.ung_vien.index',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>',
                ],
                [
                    'title' => 'Gửi email phỏng vấn',
                    'route' => 'admin.ung_vien.email.create',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
                ],
                [
                    'title' => 'Danh sách email đã gửi',
                    'route' => 'admin.ung_vien.email.index',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
                ],
                [
                    'title' => 'Trúng tuyển',
                    'route' => '#',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                ],
                [
                    'title' => 'Lưu trữ',
                    'route' => '#',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>',
                ],
            ],
        ],
        [
            'title' => 'Quản lý hợp đồng',
            'icon' =>
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
            'visible' => true,
            'submenu' => [
                [
                    'title' => 'Danh sách hợp đồng',
                    'route' => 'admin.hop-dong.index',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>',
                ],
                [
                    'title' => 'Thêm hợp đồng',
                    'route' => 'admin.hop-dong.create',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>',
                ],
                [
                    'title' => 'Sắp hết hạn',
                    'route' => 'admin.hop-dong.sap-het-han',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                ],
                [
                    'title' => 'Xuất Excel',
                    'route' => 'admin.hop-dong.export.excel',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
                ],
                [
                    'title' => 'Xuất PDF',
                    'route' => 'admin.hop-dong.export.pdf',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>',
                ],
            ],
        ],
        [
            'title' => 'Duyệt đơn',
            'icon' =>
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'visible' => true,
            'submenu' => [
                [
                    'title' => 'Tuyển dụng',
                    'route' => 'admin.duyetdon.tuyendung.index',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/>',
                ],
            ],
        ],
        [
            'title' => 'Loại nghỉ phép',
            'icon' =>
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>',
            'route' => route('admin.loai-nghi-phep.index'),
            'active' => $currentRoute == 'admin.loai-nghi-phep.index', // ← Thêm dòng này để tự sáng menu khi click vào
            'visible' => true,
        ],
        [
            'title' => 'Quy định',
            'route' => route('admin.quy-dinh.index'), // Dùng hàm route() để tạo link
            'icon' =>
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>',
            'active' => $currentRoute == 'admin.quy-dinh.index', // Thêm dòng này để menu tự sáng lên khi đang ở trang này
            'visible' => true,
        ],
        [
            'title' => 'Nghỉ phép',
            'icon' =>
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
            'visible' => true,
            'submenu' => [
                [
                    'title' => 'Đơn xin nghỉ',
                    'route' => 'admin.don_nghi.index',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
                ],
            ],
        ],
        [
            'title' => 'Cài đặt hệ thống',
            'icon' =>
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>',
            'visible' => true,
            'submenu' => [
                [
                    'title' => 'Cấu hình chung',
                    'route' => '#',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>',
                ],
                [
                    'title' => 'Phân quyền',
                    'route' => '#',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>',
                ],
                [
                    'title' => 'Nhật ký hệ thống',
                    'route' => '#',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>',
                ],
            ],
        ],
    ];
@endphp

<aside id="sidebar" class="sidebar">
    <!-- Logo và nút 3 gạch -->
    <div class="flex items-center justify-between px-4 py-4 border-b dark:border-gray-700">
        <!-- Logo - ẩn khi sidebar thu gọn -->
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 logo-container">
            <div
                class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <span class="text-white font-bold text-xl">H</span>
            </div>
            <span class="text-xl font-bold text-blue-600 dark:text-blue-400 logo-text">HR Flow</span>
        </a>

        <!-- Nút thu gọn sidebar - luôn hiển thị -->
        <button type="button" id="toggleSidebarBtn"
            class="toggle-btn text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 focus:outline-none p-1 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
        </button>
    </div>

    <!-- Menu Navigation -->
    <nav class="h-full overflow-y-auto px-3 py-4 pb-24">
        <ul class="space-y-1">
            @foreach ($menuItems as $item)
                @if (isset($item['visible']) && !$item['visible'])
                    @continue
                @endif

                @if (isset($item['submenu']))
                    @php
                        $menuId = 'submenu-' . Str::slug($item['title']);
                    @endphp
                    <li>
                        <button type="button"
                            class="menu-toggle flex items-center w-full px-3 py-2.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                            data-target="{{ $menuId }}" data-title="{{ $item['title'] }}">
                            <span class="w-5 h-5 mr-3 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.5">
                                    {!! $item['icon'] !!}
                                </svg>
                            </span>
                            <span class="flex-1 text-left font-medium menu-text">{{ $item['title'] }}</span>
                            <svg class="w-4 h-4 transition-transform duration-200 arrow-icon flex-shrink-0"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <ul class="submenu pl-10 mt-1 space-y-1 hidden" id="{{ $menuId }}">
                            @foreach ($item['submenu'] as $subitem)
                                <li>
                                    <a href="{{ $subitem['route'] !== '#' && Route::has($subitem['route']) ? route($subitem['route']) : '#' }}"
                                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">

                                        <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            {!! $subitem['icon'] !!}
                                        </svg>

                                        <span class="menu-text">{{ $subitem['title'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li>
                        <a href="{{ $item['route'] }}"
                            class="flex items-center px-3 py-2.5 rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                            data-title="{{ $item['title'] }}">
                            <span class="w-5 h-5 mr-3 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.5">
                                    {!! $item['icon'] !!}
                                </svg>
                            </span>
                            <span class="font-medium menu-text">{{ $item['title'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>
</aside>
