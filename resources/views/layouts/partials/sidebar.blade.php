{{-- resources/views/layouts/partials/sidebar.blade.php --}}

@php
    $currentRoute = request()->route()->getName();
    $user = Auth::user();

    // ============================================================
    // ⭐ KIỂM TRA VAI TRÒ
    // ============================================================
    $isSuperAdmin = $user
        ->vaiTros()
        ->whereIn('name', ['admin', 'Super Admin'])
        ->exists();
    $isAdmin = $user
        ->vaiTros()
        ->whereIn('name', ['admin', 'Super Admin'])
        ->exists();
    $isHR = $user->vaiTros()->where('name', 'hr')->exists();
    $isKeToan = $user->vaiTros()->where('name', 'ke_toan')->exists();

    // ============================================================
    // ⭐ KIỂM TRA TRƯỞNG PHÒNG - CHỈ TRUE KHI THỰC SỰ LÀ TRƯỞNG PHÒNG
    // ============================================================
    $isTruongPhong = false;
    $phongBanInfo = null;

    // CÁCH 1: Kiểm tra từ vai trò
    $isTruongPhong = $user
        ->vaiTros()
        ->whereIn('name', ['truong_phong', 'quan_ly'])
        ->exists();

    if ($isTruongPhong) {
        $phongBanInfo = $user->phongBan;
        if (!$phongBanInfo) {
            $phongBanInfo = \App\Models\PhongBan::where('truong_phong_id', $user->id)->first();
        }
    }

    // CÁCH 2: Kiểm tra từ bảng phong_ban (trường truong_phong_id)
    if (!$isTruongPhong) {
        $phongBan = \App\Models\PhongBan::where('truong_phong_id', $user->id)->first();
        if ($phongBan) {
            $isTruongPhong = true;
            $phongBanInfo = $phongBan;
        }
    }

    // CÁCH 3: Kiểm tra từ chức vụ
    if (!$isTruongPhong && $user->chucVu) {
        $chucVuTen = $user->chucVu->ten;
        $keywords = ['Trưởng Phòng', 'Trưởng phòng', 'Quản lý', 'Manager'];

        foreach ($keywords as $keyword) {
            if (str_contains($chucVuTen, $keyword)) {
                $isTruongPhong = true;
                $phongBanInfo = $user->phongBan;
                break;
            }
        }
    }

    // ⛔ NẾU LÀ KẾ TOÁN -> KHÔNG BAO GIỜ LÀ TRƯỞNG PHÒNG
    if ($isKeToan) {
        $isTruongPhong = false;
        $phongBanInfo = null;
    }

    // ⛔ NẾU LÀ ADMIN HOẶC HR -> KHÔNG HIỂN THỊ MENU QUẢN LÝ PHÒNG (VÌ HỌ ĐÃ CÓ MENU RIÊNG)
    if ($isAdmin || $isHR) {
        $isTruongPhong = false;
        $phongBanInfo = null;
    }

    // ⛔ NẾU KHÔNG CÓ PHÒNG BAN -> KHÔNG HIỂN THỊ
    if ($isTruongPhong && !$phongBanInfo) {
        $isTruongPhong = false;
    }

    $isAdminOrHR = $isAdmin || $isHR;

    // ============================================================
    // ⭐ KIỂM TRA PERMISSION
    // ============================================================
    $canViewDashboardAdmin = $user->hasPermission('dashboard.admin');
    $canViewDashboardEmployee = $user->hasPermission('dashboard.employee');
    $canViewProfile = $user->hasPermission('profile.view') || $user->hasPermission('hoso.personal') || $isHR;
    $canCheckin = $user->hasPermission('attendance.checkin');
    $canCheckout = $user->hasPermission('attendance.checkout');
    $canViewAttendanceHistory = $user->hasPermission('attendance.history');
    $canCreateOvertime = $user->hasPermission('overtime.create');
    $canViewOvertime = $user->hasPermission('overtime.index');
    $canCreateAdjustment = $user->hasPermission('adjustment.create');
    $canViewAdjustment = $user->hasPermission('adjustment.index');
    $canViewPayroll = $user->hasPermission('payroll.index') || $isHR || $isKeToan;
    $canViewContractPersonal = $user->hasPermission('contract.personal');
    $canViewLeaveHistory = $user->hasPermission('leave.history');
    $canRequestLeave = $user->hasPermission('leave.request');
    $canViewRegulationEmployee = $user->hasPermission('regulation.employee');
    $canViewNotifications = $user->hasPermission('notification.view');
    $canViewEmployee = $user->hasPermission('hoso.index');
    $canViewUser = $user->hasPermission('user.view');
    $canViewDepartment = $user->hasPermission('department.view');
    $canViewChucVu = $user->hasPermission('chucvu.view');
    $canViewRole = $user->hasPermission('role.view');
    $canViewAttendance = $user->hasPermission('attendance.index');
    $canManageOvertime = $user->hasPermission('attendance.overtime_approve');
    $canApproveAdjustment = $user->hasPermission('attendance.adjustment_approve');
    $canViewSalary = $user->hasPermission('salary.index');
    $canViewAllowance = $user->hasPermission('allowance.index');
    $canManageBonus = $user->hasPermission('salary.bonus');
    $canViewReward = $user->hasPermission('khen_thuong.view');
    $canViewRecruitment = $user->hasPermission('recruitment.index');
    $canViewCandidate = $user->hasPermission('recruitment.candidate');
    $canViewPassed = $user->hasPermission('recruitment.passed');
    $canViewContract = $user->hasPermission('contract.index');
    $canEditContract = $user->hasPermission('contract.edit');
    $canApproveLeave = $user->hasPermission('leave.approve');
    $canViewLeaveType = $user->hasPermission('leave_type.index');
    $canViewRegulation = $user->hasPermission('regulation.view');
    $canViewTime = $user->hasPermission('time.index');
    $canManagePermission = $user->hasPermission('setting.permission');
    $canViewDaoTao = $user->hasPermission('dao-tao.index');

    if ($isAdmin) {
        $canViewProfile = false;
        $canCheckin = false;
        $canCheckout = false;
        $canViewAttendanceHistory = false;
        $canCreateOvertime = false;
        $canViewOvertime = false;
        $canCreateAdjustment = false;
        $canViewAdjustment = false;
        $canViewPayroll = false;
        $canViewContractPersonal = false;
        $canViewLeaveHistory = false;
        $canRequestLeave = false;
        $canViewRegulationEmployee = false;
        $canViewNotifications = false;
    }

    $hasAdminPermission =
        $canViewEmployee ||
        $canViewUser ||
        $canViewDepartment ||
        $canViewChucVu ||
        $canViewRole ||
        $canViewAttendance ||
        $canManageOvertime ||
        $canApproveAdjustment ||
        $canViewSalary ||
        $canViewAllowance ||
        $canManageBonus ||
        $canViewReward ||
        $canViewRecruitment ||
        $canViewCandidate ||
        $canViewPassed ||
        $canViewContract ||
        $canApproveLeave ||
        $canViewLeaveType ||
        $canViewRegulation ||
        $canViewTime ||
        $canManagePermission;

    $showAdminMenus = $hasAdminPermission;
    $showEmployeeMenus =
        $canViewDashboardEmployee ||
        $canViewProfile ||
        $canCheckin ||
        $canCheckout ||
        $canViewAttendanceHistory ||
        $canCreateOvertime ||
        $canViewOvertime ||
        $canCreateAdjustment ||
        $canViewAdjustment ||
        $canViewPayroll ||
        $canViewContractPersonal ||
        $canViewLeaveHistory ||
        $canRequestLeave ||
        $canViewRegulationEmployee ||
        $canViewNotifications;

    $menuCong = [];
    if (!$isAdmin && ($canCheckin || $canCheckout || $canViewAttendanceHistory) && Route::has('employee.cham-cong.index')) {
        $menuCong[] = ['title' => 'Chấm công', 'route' => 'employee.cham-cong.index', 'active' => ['employee.cham-cong.index', 'employee.cham-cong.history']];
    }
    if (!$isAdmin && Route::has('employee.ca.index')) {
        $menuCong[] = ['title' => 'Ca của tôi', 'route' => 'employee.ca.index', 'active' => ['employee.ca.index']];
    }
    if (!$isAdmin && ($canCreateOvertime || $canViewOvertime) && Route::has('employee.tang-ca.index')) {
        $menuCong[] = ['title' => 'Đơn xin tăng ca', 'route' => 'employee.tang-ca.index', 'active' => ['employee.tang-ca.index']];
    }
    if (!$isAdmin && ($canCreateAdjustment || $canViewAdjustment) && Route::has('employee.yeu-cau-chinh-cong.index')) {
        $menuCong[] = ['title' => 'Yêu cầu chỉnh công', 'route' => 'employee.yeu-cau-chinh-cong.index', 'active' => ['employee.yeu-cau-chinh-cong.index']];
    }
    if (!$isAdmin && ($canViewLeaveHistory || $canRequestLeave) && Route::has('employee.don-nghi.index')) {
        $menuCong[] = ['title' => 'Đơn nghỉ phép', 'route' => 'employee.don-nghi.index', 'prefix' => 'employee.don-nghi'];
    }

    $menuCaNhan = [];
    if (!$isAdmin && ($canViewDashboardEmployee || $canViewDashboardAdmin) && Route::has('employee.dashboard')) {
        $menuCaNhan[] = ['title' => 'Thống kê cá nhân', 'route' => 'employee.dashboard', 'active' => ['employee.dashboard']];
    }
    if ($canViewProfile && Route::has('employee.ho-so.show')) {
        $menuCaNhan[] = ['title' => 'Hồ sơ cá nhân', 'route' => 'employee.ho-so.show', 'active' => ['employee.ho-so.show']];
    }
    if (!$isAdmin && Route::has('employee.tai-san.index')) {
        $menuCaNhan[] = ['title' => 'Tài sản của tôi', 'route' => 'employee.tai-san.index', 'active' => ['employee.tai-san.index']];
    }
    if (!$isAdmin && Route::has('employee.danh-gia.index')) {
        $menuCaNhan[] = ['title' => 'Đánh giá của tôi', 'route' => 'employee.danh-gia.index', 'prefix' => 'employee.danh-gia'];
    }
    if ($canViewPayroll && Route::has('employee.bang-luong.index')) {
        $menuCaNhan[] = ['title' => 'Lương của tôi', 'route' => 'employee.bang-luong.index', 'prefix' => 'employee.bang-luong'];
    }
    if ($canViewPayroll && Route::has('employee.ung-luong.index')) {
        $menuCaNhan[] = ['title' => 'Ứng lương', 'route' => 'employee.ung-luong.index', 'prefix' => 'employee.ung-luong'];
    }
    if ($canViewContractPersonal && Route::has('employee.hop-dong.index')) {
        $menuCaNhan[] = ['title' => 'Hợp đồng của tôi', 'route' => 'employee.hop-dong.index', 'prefix' => 'employee.hop-dong'];
    }
    if ($canViewRegulationEmployee && !$canViewRegulation && Route::has('employee.quydinh.index')) {
        $menuCaNhan[] = ['title' => 'Quy định', 'route' => 'employee.quydinh.index', 'active' => ['employee.quydinh.index']];
    }
    if ($canViewNotifications && Route::has('employee.notifications.index')) {
        $soThongBao = $user->unreadNotifications->count();
        $menuCaNhan[] = ['title' => 'Thông báo', 'route' => 'employee.notifications.index', 'active' => ['employee.notifications.index'], 'badge' => $soThongBao];
    }

    $menuNghiPhep = [];
    if ($canApproveLeave && Route::has('admin.don_nghi.index')) {
        $menuNghiPhep[] = ['title' => 'Duyệt đơn', 'route' => 'admin.don_nghi.index', 'prefix' => 'admin.don_nghi'];
    }
    if ($canViewLeaveType && Route::has('admin.loai-nghi-phep.index')) {
        $menuNghiPhep[] = ['title' => 'Loại nghỉ phép', 'route' => 'admin.loai-nghi-phep.index', 'prefix' => 'admin.loai-nghi-phep'];
    }
@endphp

<aside id="sidebar" class="sidebar">
    <!-- Logo -->
    <div class="flex items-center justify-between px-4 py-4 border-b dark:border-gray-700">
        <a href="{{ $isSuperAdmin ? route('admin.dashboard') : ($isHR ? route('employee.dashboard') : ($showAdminMenus ? route('admin.dashboard') : route('employee.dashboard'))) }}"
            class="flex items-center space-x-2 logo-container">
            <div
                class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <span class="text-white font-bold text-xl">H</span>
            </div>
            <span class="text-xl font-bold text-blue-600 dark:text-blue-400 logo-text">HR Flow</span>
        </a>
        <button type="button" id="toggleSidebarBtn"
            class="toggle-btn text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 focus:outline-none p-1 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
        </button>
    </div>

    <nav class="h-full overflow-y-auto px-3 py-4 pb-24">
        <ul class="space-y-1">

            @if ($isAdmin && ($canViewDashboardAdmin || $canViewDashboardEmployee))
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ $currentRoute == 'admin.dashboard' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        @include('layouts.partials.sidebar-icon', ['icon' => 'home'])
                        <span class="font-medium menu-text">Tổng quan</span>
                    </a>
                </li>
            @endif

            @include('layouts.partials.sidebar-nhom', ['items' => $menuCong, 'nhomTitle' => 'Công của tôi', 'icon' => 'clock'])
            @include('layouts.partials.sidebar-nhom', ['items' => $menuCaNhan, 'nhomTitle' => 'Cá nhân', 'icon' => 'user'])

            {{-- ========================================================== --}}
            {{-- ⭐ 4. QUẢN LÝ PHÒNG BAN (CHỈ TRƯỞNG PHÒNG - KHÔNG BAO GỒM KẾ TOÁN) --}}
            {{-- ========================================================== --}}
            @if ($isTruongPhong)
                <li>
                    <details class="menu-details"
                        {{ str_starts_with($currentRoute, 'truong-phong.') ||
                        str_starts_with($currentRoute, 'duyet-don.') ||
                        str_starts_with($currentRoute, 'duyet-tang-ca.') ||
                        str_starts_with($currentRoute, 'duyet-chinh-cong.') ||
                        str_starts_with($currentRoute, 'truong-phong.duyet-ve-som.')
                            ? 'open'
                            : '' }}>
                        <summary
                            class="flex items-center w-full px-3 py-2.5 rounded-lg transition-colors cursor-pointer 
                            {{ str_starts_with($currentRoute, 'truong-phong.') ||
                            str_starts_with($currentRoute, 'duyet-don.') ||
                            str_starts_with($currentRoute, 'duyet-tang-ca.') ||
                            str_starts_with($currentRoute, 'duyet-chinh-cong.')
                                ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <span class="w-5 h-5 mr-3 flex-shrink-0 text-gray-700 dark:text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </span>
                            <span class="flex-1 text-left font-medium menu-text">
                                Quản lý phòng
                                @if ($phongBanInfo)
                                    <span class="text-xs text-blue-500 dark:text-blue-400 block font-normal">
                                        {{ $phongBanInfo->ten_phong_ban }}
                                        <span
                                            class="ml-1 text-xs bg-gray-200 dark:bg-gray-700 px-1.5 py-0.5 rounded-full">
                                            {{ \App\Models\NguoiDung::where('phong_ban_id', $phongBanInfo->id)->where('trang_thai', 1)->count() }}
                                        </span>
                                    </span>
                                @endif
                            </span>
                            <svg class="w-4 h-4 transition-transform duration-200 arrow-icon flex-shrink-0 
                                {{ str_starts_with($currentRoute, 'truong-phong.') ||
                                str_starts_with($currentRoute, 'duyet-don.') ||
                                str_starts_with($currentRoute, 'duyet-tang-ca.') ||
                                str_starts_with($currentRoute, 'duyet-chinh-cong.')
                                    ? 'rotate-180'
                                    : '' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <ul class="pl-10 mt-1 space-y-1">

                            {{-- Dashboard trưởng phòng --}}
                            @if (Route::has('truong-phong.dashboard'))
                                <li>
                                    <a href="{{ route('truong-phong.dashboard') }}"
                                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors 
                                        {{ $currentRoute == 'truong-phong.dashboard'
                                            ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        <span class="menu-text">Tổng quan phòng ban</span>
                                    </a>
                                </li>
                            @endif

                            {{-- Nhân viên trong phòng --}}
                            @if (Route::has('truong-phong.nhan-vien.index'))
                                <li>
                                    <a href="{{ route('truong-phong.nhan-vien.index') }}"
                                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors 
                                        {{ str_starts_with($currentRoute, 'truong-phong.nhan-vien.')
                                            ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <span class="menu-text">Nhân viên</span>
                                    </a>
                                </li>
                            @endif


                            {{-- DUYỆT ĐƠN NGHỈ PHÉP --}}
                            @if (Route::has('duyet-don.index'))
                                <li>
                                    <a href="{{ route('duyet-don.index') }}"
                                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors 
                                        {{ str_starts_with($currentRoute, 'duyet-don.')
                                            ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="menu-text">Duyệt đơn nghỉ phép</span>
                                        @php
                                            $donChoDuyetCount = \App\Models\DonXinNghi::whereIn(
                                                'nguoi_dung_id',
                                                function ($q) use ($phongBanInfo) {
                                                    $q->select('id')
                                                        ->from('nguoi_dung')
                                                        ->where('phong_ban_id', $phongBanInfo->id);
                                                },
                                            )
                                                ->where('trang_thai', 'cho_duyet')
                                                ->where('nguoi_dung_id', '!=', $user->id)
                                                ->count();
                                        @endphp
                                        @if ($donChoDuyetCount > 0)
                                            <span
                                                class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                                                {{ $donChoDuyetCount }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                            @endif

                            
                            {{-- DUYỆT ĐƠN VỀ SỚM (Dành cho Trưởng phòng) --}}
                            @if (Route::has('truong-phong.duyet-ve-som.index'))
                                <li>
                                    <a href="{{ route('truong-phong.duyet-ve-som.index') }}"
                                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors 
        {{ str_starts_with($currentRoute, 'truong-phong.duyet-ve-som.')
            ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                        </svg>
                                        <span class="menu-text">Duyệt đơn về sớm</span>
                                        @php
                                            $veSomChoDuyetCount = \App\Models\DonXinVeSom::whereIn(
                                                'nguoi_dung_id',
                                                function ($q) use ($phongBanInfo) {
                                                    $q->select('id')
                                                        ->from('nguoi_dung')
                                                        ->where('phong_ban_id', $phongBanInfo->id);
                                                },
                                            )
                                                ->where('trang_thai', 'cho_duyet')
                                                ->where('nguoi_dung_id', '!=', $user->id)
                                                ->count();
                                        @endphp
                                        @if ($veSomChoDuyetCount > 0)
                                            <span
                                                class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                                                {{ $veSomChoDuyetCount }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                            @endif
                            {{-- DUYỆT CHỈNH CÔNG --}}
                            @if (Route::has('duyet-chinh-cong.index'))
                                <li>
                                    <a href="{{ route('duyet-chinh-cong.index') }}"
                                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors 
                                        {{ str_starts_with($currentRoute, 'duyet-chinh-cong.')
                                            ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <span class="menu-text">Duyệt chỉnh công</span>
                                        @php
                                            $chinhCongChoDuyet = \App\Models\YeuCauDieuChinhCong::whereIn(
                                                'nguoi_dung_id',
                                                function ($q) use ($phongBanInfo) {
                                                    $q->select('id')
                                                        ->from('nguoi_dung')
                                                        ->where('phong_ban_id', $phongBanInfo->id);
                                                },
                                            )
                                                ->where('trang_thai', 'cho_duyet')
                                                ->count();
                                        @endphp
                                        @if ($chinhCongChoDuyet > 0)
                                            <span
                                                class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                                                {{ $chinhCongChoDuyet }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                            @endif

                            {{-- Tăng ca --}}
                            @if (Route::has('truong-phong.tang-ca.index'))
                                <li>
                                    <a href="{{ route('truong-phong.tang-ca.index') }}"
                                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors 
                                        {{ str_starts_with($currentRoute, 'truong-phong.tang-ca.')
                                            ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="menu-text"> Duyệt tăng ca</span>
                                    </a>
                                </li>
                            @endif

                            {{-- Yêu cầu chỉnh công --}}
                            @if (Route::has('truong-phong.yeu-cau-chinh-cong.index'))
                                <li>
                                    <a href="{{ route('truong-phong.yeu-cau-chinh-cong.index') }}"
                                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors 
                                        {{ str_starts_with($currentRoute, 'truong-phong.yeu-cau-chinh-cong.')
                                            ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <span class="menu-text">Chỉnh công</span>
                                    </a>
                                </li>
                            @endif

                            {{-- BÁO CÁO --}}
                            <li>
                                <details class="menu-details"
                                    {{ str_starts_with($currentRoute, 'truong-phong.bao-cao.') ? 'open' : '' }}>
                                    <summary
                                        class="flex items-center w-full px-3 py-2 rounded-lg text-sm transition-colors cursor-pointer 
                                        {{ str_starts_with($currentRoute, 'truong-phong.bao-cao.')
                                            ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        <span class="flex-1 text-left font-medium menu-text">Báo cáo</span>
                                        <svg class="w-4 h-4 transition-transform duration-200 arrow-icon flex-shrink-0 
                                            {{ str_starts_with($currentRoute, 'truong-phong.bao-cao.') ? 'rotate-180' : '' }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </summary>
                                    <ul class="pl-8 mt-1 space-y-1">

                                        {{-- Báo cáo tổng quan --}}
                                        @if (Route::has('truong-phong.bao-cao.overview'))
                                            <li>
                                                <a href="{{ route('truong-phong.bao-cao.overview') }}"
                                                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors 
                                                    {{ $currentRoute == 'truong-phong.bao-cao.overview'
                                                        ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                                        : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                                    <svg class="w-4 h-4 flex-shrink-0"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                                    </svg>
                                                    <span class="menu-text">Tổng quan</span>
                                                </a>
                                            </li>
                                        @endif

                                        {{-- Báo cáo chấm công --}}
                                        @if (Route::has('truong-phong.bao-cao.attendance'))
                                            <li>
                                                <a href="{{ route('truong-phong.bao-cao.attendance') }}"
                                                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors 
                                                    {{ $currentRoute == 'truong-phong.bao-cao.attendance'
                                                        ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                                        : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                                    <svg class="w-4 h-4 flex-shrink-0"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="menu-text">Chấm công</span>
                                                </a>
                                            </li>
                                        @endif

                                        {{-- Báo cáo nghỉ phép --}}
                                        @if (Route::has('truong-phong.bao-cao.leave'))
                                            <li>
                                                <a href="{{ route('truong-phong.bao-cao.leave') }}"
                                                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors 
                                                    {{ $currentRoute == 'truong-phong.bao-cao.leave'
                                                        ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                                        : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                                    <svg class="w-4 h-4 flex-shrink-0"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span class="menu-text">Nghỉ phép</span>
                                                </a>
                                            </li>
                                        @endif

                                        {{-- Xuất báo cáo --}}
                                        @if (Route::has('truong-phong.bao-cao.export'))
                                            <li>
                                                <a href="{{ route('truong-phong.bao-cao.export') }}"
                                                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors 
                                                    {{ $currentRoute == 'truong-phong.bao-cao.export'
                                                        ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                                        : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                                    <svg class="w-4 h-4 flex-shrink-0"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                    </svg>
                                                    <span class="menu-text">Xuất Excel</span>
                                                </a>
                                            </li>
                                        @endif

                                    </ul>
                                </details>
                            </li>

                        </ul>
                    </details>
                </li>
            @endif

            {{-- ========================================================== --}}
            {{-- ⭐ 5. CÁC MENU KHÁC --}}
            {{-- ========================================================== --}}

            {{-- NHÂN SỰ --}}
            @php
                $submenuNhanSu = [];
                if ($canViewEmployee && Route::has('admin.ho-so.index')) {
                    $submenuNhanSu[] = ['title' => 'Hồ sơ nhân viên', 'route' => 'admin.ho-so.index'];
                }
                if ($canViewUser && Route::has('admin.nguoi-dung.index')) {
                    $submenuNhanSu[] = ['title' => 'Tài khoản', 'route' => 'admin.nguoi-dung.index'];
                }
                if ($canViewDepartment && Route::has('admin.phong-ban.index')) {
                    $submenuNhanSu[] = ['title' => 'Phòng ban', 'route' => 'admin.phong-ban.index'];
                }
                if ($canViewChucVu && Route::has('admin.chuc-vu.index')) {
                    $submenuNhanSu[] = ['title' => 'Chức vụ', 'route' => 'admin.chuc-vu.index'];
                }
                if ($canViewRole && Route::has('admin.vai-tro.index')) {
                    $submenuNhanSu[] = ['title' => 'Vai trò', 'route' => 'admin.vai-tro.index'];
                }
            @endphp
            @if (!empty($submenuNhanSu))
                <li>
                    <details class="menu-details"
                        {{ in_array($currentRoute, array_column($submenuNhanSu, 'route')) ? 'open' : '' }}>
                        <summary
                            class="flex items-center w-full px-3 py-2.5 rounded-lg transition-colors cursor-pointer {{ in_array($currentRoute, array_column($submenuNhanSu, 'route')) ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <span class="w-5 h-5 mr-3 flex-shrink-0 text-gray-700 dark:text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </span>
                            <span class="flex-1 text-left font-medium menu-text">Nhân sự</span>
                            <svg class="w-4 h-4 transition-transform duration-200 arrow-icon flex-shrink-0 {{ in_array($currentRoute, array_column($submenuNhanSu, 'route')) ? 'rotate-180' : '' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <ul class="pl-10 mt-1 space-y-1">
                            @foreach ($submenuNhanSu as $sub)
                                <li>
                                    <a href="{{ route($sub['route']) }}"
                                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute == $sub['route'] ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <span class="menu-text">{{ $sub['title'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </details>
                </li>
            @endif

            {{-- QUẢN LÝ CHẤM CÔNG --}}
            @php
                $submenuChamCongAdmin = [];
                if ($canViewAttendance && Route::has('admin.cham-cong.index')) {
                    $submenuChamCongAdmin[] = ['title' => 'Danh sách chấm công', 'route' => 'admin.cham-cong.index'];
                }
                if ($canViewAttendance && Route::has('admin.cham-cong-face.index') && !$isKeToan) {
                    $submenuChamCongAdmin[] = ['title' => 'Quản lý khuôn mặt', 'route' => 'admin.cham-cong-face.index'];
                }
                if (($isHR || $isAdmin) && Route::has('admin.chot-du-lieu.index')) {
                    $submenuChamCongAdmin[] = ['title' => 'Chốt dữ liệu tháng', 'route' => 'admin.chot-du-lieu.index'];
                }
                if ($canManageOvertime && Route::has('admin.tang-ca.index')) {
                    $submenuChamCongAdmin[] = ['title' => 'Phê duyệt tăng ca', 'route' => 'admin.tang-ca.index'];
                }
                if ($canApproveAdjustment && Route::has('admin.yeu-cau-dieu-chinh-cong.index')) {
                    $submenuChamCongAdmin[] = [
                        'title' => 'Yêu cầu chỉnh công',
                        'route' => 'admin.yeu-cau-dieu-chinh-cong.index',
                    ];
                }
            @endphp
            @if (!empty($submenuChamCongAdmin))
                <li>
                    <details class="menu-details"
                        {{ in_array($currentRoute, array_column($submenuChamCongAdmin, 'route')) ? 'open' : '' }}>
                        <summary
                            class="flex items-center w-full px-3 py-2.5 rounded-lg transition-colors cursor-pointer {{ in_array($currentRoute, array_column($submenuChamCongAdmin, 'route')) ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <span class="w-5 h-5 mr-3 flex-shrink-0 text-gray-700 dark:text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <span class="flex-1 text-left font-medium menu-text">Quản lý chấm công</span>
                            <svg class="w-4 h-4 transition-transform duration-200 arrow-icon flex-shrink-0 {{ in_array($currentRoute, array_column($submenuChamCongAdmin, 'route')) ? 'rotate-180' : '' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <ul class="pl-10 mt-1 space-y-1">
                            @foreach ($submenuChamCongAdmin as $sub)
                                <li>
                                    <a href="{{ route($sub['route']) }}"
                                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute == $sub['route'] ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <span class="menu-text">{{ $sub['title'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </details>
                </li>
            @endif

            {{-- LƯƠNG --}}
            @php
                $submenuLuong = [];
                if ($canViewSalary && Route::has('admin.bang-luong.index')) {
                    $submenuLuong[] = ['title' => 'Bảng lương', 'route' => 'admin.bang-luong.index'];
                }
                if ($canViewSalary && Route::has('admin.khau-tru-khac.index')) {
                    $submenuLuong[] = ['title' => 'Khấu trừ', 'route' => 'admin.khau-tru-khac.index'];
                }
                if ($canViewAllowance && Route::has('admin.phu-cap.index')) {
                    $submenuLuong[] = ['title' => 'Phụ cấp', 'route' => 'admin.phu-cap.index'];
                }
                if ($canManageBonus && Route::has('admin.thuong.index')) {
                    $submenuLuong[] = ['title' => 'Thưởng nhân viên', 'route' => 'admin.thuong.index'];
                }
                if ($canManageBonus && Route::has('admin.loai-thuong.index')) {
                    $submenuLuong[] = ['title' => 'Loại thưởng', 'route' => 'admin.loai-thuong.index'];
                }
                if ($canViewSalary && Route::has('admin.tong-luong.index')) {
                    $submenuLuong[] = ['title' => 'Tổng lương theo năm', 'route' => 'admin.tong-luong.index'];
                }
                if ($canEditContract && Route::has('admin.tang-luong.index')) {
                    $submenuLuong[] = ['title' => 'Lịch sử tăng lương', 'route' => 'admin.tang-luong.index'];
                }
                if ($canViewSalary && Route::has('admin.yeu-cau-luong.index')) {
                    $submenuLuong[] = ['title' => 'Yêu cầu xem xét lương', 'route' => 'admin.yeu-cau-luong.index'];
                }
                if ($canViewSalary && Route::has('admin.lich-su-xu-ly-yeu-cau-luong.index')) {
                    $submenuLuong[] = [
                        'title' => 'Lịch sử xử lý yêu cầu lương',
                        'route' => 'admin.lich-su-xu-ly-yeu-cau-luong.index',
                    ];
                }
            @endphp
            @if (!empty($submenuLuong))
                <li>
                    <details class="menu-details"
                        {{ in_array($currentRoute, array_column($submenuLuong, 'route')) ? 'open' : '' }}>
                        <summary
                            class="flex items-center w-full px-3 py-2.5 rounded-lg transition-colors cursor-pointer {{ in_array($currentRoute, array_column($submenuLuong, 'route')) ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <span class="w-5 h-5 mr-3 flex-shrink-0 text-gray-700 dark:text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <span class="flex-1 text-left font-medium menu-text">Lương</span>
                            <svg class="w-4 h-4 transition-transform duration-200 arrow-icon flex-shrink-0 {{ in_array($currentRoute, array_column($submenuLuong, 'route')) ? 'rotate-180' : '' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <ul class="pl-10 mt-1 space-y-1">
                            @foreach ($submenuLuong as $sub)
                                <li>
                                    <a href="{{ route($sub['route']) }}"
                                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute == $sub['route'] ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <span class="menu-text">{{ $sub['title'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </details>
                </li>
            @endif

            {{-- HỢP ĐỒNG --}}
            @if ($canViewContract)
                <li>
                    <a href="{{ route('admin.hop-dong.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ $currentRoute == 'admin.hop-dong.index' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <span class="w-5 h-5 mr-3 flex-shrink-0 text-gray-700 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </span>
                        <span class="font-medium menu-text">Quản lý hợp đồng</span>
                    </a>
                </li>
            @endif

            @include('layouts.partials.sidebar-nhom', ['items' => $menuNghiPhep, 'nhomTitle' => 'Nghỉ phép', 'icon' => 'calendar'])

            {{-- ĐÀO TẠO --}}
            @if ($canViewDaoTao)
                <li x-data="{ open: {{ str_starts_with($currentRoute, 'admin.dao-tao') || str_starts_with($currentRoute, 'admin.chung-chi') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors
                        {{ str_starts_with($currentRoute, 'admin.dao-tao') || str_starts_with($currentRoute, 'admin.chung-chi')
                            ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                            : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <div class="flex items-center">
                            <span class="w-5 h-5 mr-3 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 14 3 9l9-5 9 5-9 5Zm0 0v6m6-8v4c0 1.5-2.7 3-6 3s-6-1.5-6-3v-4" />
                                </svg>
                            </span>
                            <span class="font-medium menu-text">Đào tạo</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200"
                            :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul x-show="open" x-transition x-cloak class="mt-2 ml-8 space-y-1">
                        @if (auth()->user()->hasPermission('dao-tao.index'))
                            <li>
                                <a href="{{ route('admin.dao-tao.index') }}"
                                    class="block px-3 py-2 rounded-lg text-sm transition
                                    {{ str_starts_with($currentRoute, 'admin.dao-tao')
                                        ? 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400'
                                        : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    Quản lý đào tạo nhân viên
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->hasPermission('chung-chi.index'))
                            <li>
                                <a href="{{ route('admin.chung-chi.index') }}"
                                    class="block px-3 py-2 rounded-lg text-sm transition
                                    {{ str_starts_with($currentRoute, 'admin.chung-chi')
                                        ? 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400'
                                        : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    Quản lý chứng chỉ nhân viên
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            {{-- KHEN THƯỞNG / KỶ LUẬT --}}
            @if ($canViewReward)
                @php
                    $submenuReward = [];
                    if (Route::has('admin.khen-thuong-ky-luat.index')) {
                        $submenuReward[] = ['title' => 'Danh sách', 'route' => 'admin.khen-thuong-ky-luat.index'];
                    }
                    if (Route::has('admin.khen-thuong-ky-luat.thong-ke')) {
                        $submenuReward[] = ['title' => 'Thống kê', 'route' => 'admin.khen-thuong-ky-luat.thong-ke'];
                    }
                @endphp
                @if (!empty($submenuReward))
                    <li>
                        <details class="menu-details"
                            {{ str_starts_with($currentRoute, 'admin.khen-thuong-ky-luat.') ? 'open' : '' }}>
                            <summary
                                class="flex items-center w-full px-3 py-2.5 rounded-lg transition-colors cursor-pointer 
                    {{ str_starts_with($currentRoute, 'admin.khen-thuong-ky-luat.') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                <span class="w-5 h-5 mr-3 flex-shrink-0 text-gray-700 dark:text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.5 18.75h-9A2.25 2.25 0 015.25 16.5v-9A2.25 2.25 0 017.5 5.25h4.5l2.25 2.25h2.25A2.25 2.25 0 0118.75 9.75v6.75A2.25 2.25 0 0116.5 18.75z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9.75l.9 1.83 2.02.29-1.46 1.42.34 2.01L12 14.36l-1.8.94.34-2.01-1.46-1.42 2.02-.29L12 9.75z" />
                                    </svg>
                                </span>
                                <span class="flex-1 text-left font-medium menu-text">Khen thưởng kỷ luật</span>
                                {{-- CHỈ CẦN THÊM SVG NÀY LÀ MŨI TÊN SẼ TỰ XOAY KHI MỞ/ĐÓNG BẰNG CSS --}}
                                <svg class="w-4 h-4 transition-transform duration-200 arrow-icon flex-shrink-0"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </summary>
                            <ul class="pl-10 mt-1 space-y-1">
                                @foreach ($submenuReward as $sub)
                                    <li>
                                        <a href="{{ route($sub['route']) }}"
                                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute == $sub['route'] ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                            <span class="menu-text">{{ $sub['title'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </details>
                    </li>
                @endif
            @endif

            @if ($isAdminOrHR || $canViewSalary || $canViewEmployee)
                <li>
                    <details class="menu-details" {{ str_starts_with($currentRoute ?? '', 'admin.bhxh-thue') || str_starts_with($currentRoute ?? '', 'admin.quyet-toan') || str_starts_with($currentRoute ?? '', 'admin.lich-lam') || str_starts_with($currentRoute ?? '', 'admin.ca-lam') || str_starts_with($currentRoute ?? '', 'admin.checklist') || str_starts_with($currentRoute ?? '', 'admin.tai-san') || str_starts_with($currentRoute ?? '', 'admin.danh-gia') || str_starts_with($currentRoute ?? '', 'admin.thong-bao-kenh') || str_starts_with($currentRoute ?? '', 'admin.nhat-ky') ? 'open' : '' }}>
                        <summary class="flex items-center w-full px-3 py-2.5 rounded-lg cursor-pointer text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <span class="w-5 h-5 mr-3 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </span>
                            <span class="flex-1 text-left font-medium menu-text">Vận hành</span>
                        </summary>
                        <ul class="pl-10 mt-1 space-y-1">
                            @foreach ([
                                ['Cấu hình BHXH / thuế', 'admin.bhxh-thue.index', 'fa-solid fa-shield-halved', 'ke_toan'],
                                ['Quyết toán TNCN', 'admin.quyet-toan-thue.index', 'fa-solid fa-file-invoice-dollar', 'ke_toan'],
                                ['Lịch làm việc và ngày lễ', 'admin.lich-lam-viec.index', 'fa-solid fa-calendar-days', 'hr'],
                                ['Ca làm việc', 'admin.ca-lam-viec.index', 'fa-solid fa-clock', 'hr'],
                                ['Nhận việc / nghỉ việc', 'admin.checklist.index', 'fa-solid fa-list-check', 'hr'],
                                ['Tài sản', 'admin.tai-san.index', 'fa-solid fa-laptop', 'hr'],
                                ['Đánh giá KPI', 'admin.danh-gia.index', 'fa-solid fa-star', 'hr'],
                                ['Thông báo đa kênh', 'admin.thong-bao-kenh.index', 'fa-solid fa-bell', 'hr'],
                                ['Nhật ký thao tác', 'admin.nhat-ky.index', 'fa-solid fa-clipboard-list', 'hr'],
                            ] as [$menuTitle, $routeName, $menuIcon, $nhomMenu])
                                @if (Route::has($routeName) && (($nhomMenu === 'ke_toan' && ($isKeToan || $isAdmin)) || ($nhomMenu === 'hr' && ($isHR || $isAdmin))))
                                    <li>
                                        <a href="{{ route($routeName) }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ $currentRoute == $routeName ? 'bg-blue-50 text-blue-700' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100' }}">
                                            <i class="{{ $menuIcon }} w-4 text-center"></i>
                                            <span class="menu-text">{{ $menuTitle }}</span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </details>
                </li>
            @endif

            {{-- QUY ĐỊNH --}}
            @if ($canViewRegulation && Route::has('admin.quy-dinh.index'))
                <li>
                    <a href="{{ route('admin.quy-dinh.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ $currentRoute == 'admin.quy-dinh.index' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <span class="w-5 h-5 mr-3 flex-shrink-0 text-gray-700 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </span>
                        <span class="font-medium menu-text">Quy định</span>
                    </a>
                </li>
            @endif

            {{-- CÀI ĐẶT --}}
            @php
                $submenuCaiDat = [];
                if ($canManagePermission && Route::has('admin.phan-quyen.index')) {
                    $submenuCaiDat[] = ['title' => 'Phân quyền', 'route' => 'admin.phan-quyen.index'];
                }
                if ($canViewTime && Route::has('admin.quan-ly-thoi-gian.index')) {
                    $submenuCaiDat[] = ['title' => 'Quản lý thời gian', 'route' => 'admin.quan-ly-thoi-gian.index'];
                }
            @endphp
            @if (!empty($submenuCaiDat))
                <li>
                    <details class="menu-details"
                        {{ in_array($currentRoute, array_column($submenuCaiDat, 'route')) ? 'open' : '' }}>
                        <summary
                            class="flex items-center w-full px-3 py-2.5 rounded-lg transition-colors cursor-pointer {{ in_array($currentRoute, array_column($submenuCaiDat, 'route')) ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <span class="w-5 h-5 mr-3 flex-shrink-0 text-gray-700 dark:text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                </svg>
                            </span>
                            <span class="flex-1 text-left font-medium menu-text">Cài đặt</span>
                            <svg class="w-4 h-4 transition-transform duration-200 arrow-icon flex-shrink-0 {{ in_array($currentRoute, array_column($submenuCaiDat, 'route')) ? 'rotate-180' : '' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <ul class="pl-10 mt-1 space-y-1">
                            @foreach ($submenuCaiDat as $sub)
                                <li>
                                    <a href="{{ route($sub['route']) }}"
                                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute == $sub['route'] ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        <span class="menu-text">{{ $sub['title'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </details>
                </li>
            @endif

        </ul>
    </nav>
</aside>

<style>
    .sidebar .menu-toggle,
    .sidebar>ul>li>a {
        display: flex !important;
        align-items: center !important;
    }

    .sidebar .menu-toggle .w-5,
    .sidebar>ul>li>a .w-5 {
        width: 24px !important;
        min-width: 24px !important;
        height: 24px !important;
        margin-right: 12px !important;
    }

    .sidebar.collapsed {
        width: 70px !important;
    }

    .sidebar.collapsed .menu-toggle,
    .sidebar.collapsed>ul>li>a {
        justify-content: center !important;
        padding: 10px 0 !important;
    }

    .sidebar.collapsed .menu-toggle .w-5,
    .sidebar.collapsed>ul>li>a .w-5 {
        margin-right: 0 !important;
    }

    .sidebar.collapsed .menu-text,
    .sidebar.collapsed .logo-text,
    .sidebar.collapsed .arrow-icon {
        display: none !important;
    }

    .sidebar.collapsed .submenu {
        display: none !important;
    }

    .menu-details summary::-webkit-details-marker {
        display: none;
    }

    .menu-details summary {
        list-style: none;
        cursor: pointer;
    }

    .menu-details[open] .arrow-icon {
        transform: rotate(180deg);
    }

    .menu-details {
        display: block;
    }
</style>
