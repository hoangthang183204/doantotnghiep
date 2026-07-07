{{-- resources/views/layouts/partials/sidebar.blade.php --}}

@php
    $currentRoute = request()->route()->getName();
    $user = Auth::user();

    // ============================================================
    // ⭐ KIỂM TRA VAI TRÒ
    // ============================================================
    $isAdmin = $user->vaiTros()->whereIn('name', ['admin', 'Super Admin'])->exists();
    $isHR = $user->vaiTros()->where('name', 'hr')->exists();
    $isTruongPhong = $user->vaiTros()->where('name', 'truong_phong')->exists();
    $isAdminOrHR = $isAdmin || $isHR || $isTruongPhong;

    // ============================================================
    // ⭐ KIỂM TRA PERMISSION
    // ============================================================
    $canViewDashboardAdmin = $user->hasPermission('dashboard.admin');
    $canViewDashboardEmployee = $user->hasPermission('dashboard.employee');
    $canViewProfile = $user->hasPermission('profile.view') || $user->hasPermission('hoso.personal');
    $canCheckin = $user->hasPermission('attendance.checkin');
    $canCheckout = $user->hasPermission('attendance.checkout');
    $canViewAttendanceHistory = $user->hasPermission('attendance.history');
    $canCreateOvertime = $user->hasPermission('overtime.create');
    $canViewOvertime = $user->hasPermission('overtime.index');
    $canCreateAdjustment = $user->hasPermission('adjustment.create');
    $canViewAdjustment = $user->hasPermission('adjustment.index');
    $canViewPayroll = $user->hasPermission('payroll.index');
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
    $canViewReward = $user->hasPermission('khen_thuong.view');
    $canViewRecruitment = $user->hasPermission('recruitment.index');
    $canViewCandidate = $user->hasPermission('recruitment.candidate');
    $canViewPassed = $user->hasPermission('recruitment.passed');
    $canViewContract = $user->hasPermission('contract.index');
    $canApproveLeave = $user->hasPermission('leave.approve');
    $canViewLeaveType = $user->hasPermission('leave_type.index');
    $canViewRegulation = $user->hasPermission('regulation.view');
    $canViewTime = $user->hasPermission('time.index');
    $canManagePermission = $user->hasPermission('setting.permission');
    $canViewDaoTao = $user->hasPermission('dao-tao.index');

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
@endphp

<aside id="sidebar" class="sidebar">
    <!-- Logo -->
    <div class="flex items-center justify-between px-4 py-4 border-b dark:border-gray-700">
        <a href="{{ $isSuperAdmin ? route('admin.dashboard') : ($showAdminMenus ? route('admin.dashboard') : route('employee.dashboard')) }}"
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

            {{-- ========================================================== --}}
            {{-- ⭐ 1. CHẤM CÔNG - ĐẶT LÊN ĐẦU TIÊN (QUAN TRỌNG NHẤT) --}}
            {{-- ========================================================== --}}
            @if ($canCheckin || $canCheckout || $canViewAttendanceHistory)
                <li>
                    <a href="{{ route('employee.cham-cong.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ $currentRoute == 'employee.cham-cong.index' || $currentRoute == 'employee.cham-cong.history' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <span class="w-5 h-5 mr-3 flex-shrink-0 text-gray-700 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        <span class="font-medium menu-text">Chấm công</span>
                    </a>
                </li>
            @endif

            {{-- ========================================================== --}}
            {{-- ⭐ 2. THỐNG KÊ CÁ NHÂN / TỔNG QUAN --}}
            {{-- ========================================================== --}}
            @if ($canViewDashboardAdmin || $canViewDashboardEmployee)
                <li>
                    <a href="{{ $isAdminOrHR ? route('admin.dashboard') : route('employee.dashboard') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ $currentRoute == 'admin.dashboard' || $currentRoute == 'employee.dashboard' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <span class="w-5 h-5 mr-3 flex-shrink-0 text-gray-700 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </span>
                        <span class="font-medium menu-text">{{ $isAdminOrHR ? 'Tổng quan' : 'Thống kê cá nhân' }}</span>
                    </a>
                </li>
            @endif

            {{-- ========================================================== --}}
            {{-- ⭐ 3. HỒ SƠ CÁ NHÂN --}}
            {{-- ========================================================== --}}
            @if ($canViewProfile)
                <li>
                    <a href="{{ route('employee.ho-so.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ $currentRoute == 'employee.ho-so.index' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <span class="w-5 h-5 mr-3 flex-shrink-0 text-gray-700 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </span>
                        <span class="font-medium menu-text">Hồ sơ cá nhân</span>
                    </a>
                </li>
            @endif

            {{-- ========================================================== --}}
            {{-- ⭐ 4. CÁC MENU KHÁC --}}
            {{-- ========================================================== --}}

            {{-- 🔹 NHÂN SỰ (ADMIN) --}}
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
            @endif

            {{-- 🔹 QUẢN LÝ CHẤM CÔNG (ADMIN) --}}
            @php
                $submenuChamCongAdmin = [];
                if ($canViewAttendance && Route::has('admin.cham-cong.index')) {
                    $submenuChamCongAdmin[] = ['title' => 'Danh sách chấm công', 'route' => 'admin.cham-cong.index'];
                }
                if ($canManageOvertime && Route::has('admin.tang-ca.index')) {
                    $submenuChamCongAdmin[] = ['title' => 'Phê duyệt tăng ca', 'route' => 'admin.tang-ca.index'];
                }
                if ($canApproveAdjustment && Route::has('admin.yeu-cau-dieu-chinh-cong.index')) {
                    $submenuChamCongAdmin[] = ['title' => 'Yêu cầu chỉnh công', 'route' => 'admin.yeu-cau-dieu-chinh-cong.index'];
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
            @endif

            {{-- 🔹 ĐƠN XIN TĂNG CA (EMPLOYEE) --}}
            @if ($canCreateOvertime || $canViewOvertime)
                <li>
                    <a href="{{ route('employee.tang-ca.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ $currentRoute == 'employee.tang-ca.index' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <span class="w-5 h-5 mr-3 flex-shrink-0 text-gray-700 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        <span class="font-medium menu-text">Đơn xin tăng ca</span>
                    </a>
                </li>
            @endif

            {{-- 🔹 YÊU CẦU CHỈNH CÔNG (EMPLOYEE) --}}
            @if ($canCreateAdjustment || $canViewAdjustment)
                <li>
                    <a href="{{ route('employee.yeu-cau-chinh-cong.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ $currentRoute == 'employee.yeu-cau-chinh-cong.index' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <span class="w-5 h-5 mr-3 flex-shrink-0 text-gray-700 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </span>
                        <span class="font-medium menu-text">Yêu cầu chỉnh công</span>
                    </a>
                </li>
            @endif

            {{-- 🔹 LƯƠNG (ADMIN) --}}
            @php
                $submenuLuong = [];
                if ($canViewSalary && Route::has('admin.bang-luong.index')) {
                    $submenuLuong[] = ['title' => 'Bảng lương', 'route' => 'admin.bang-luong.index'];
                }
                if ($canViewAllowance && Route::has('admin.phu-cap.index')) {
                    $submenuLuong[] = ['title' => 'Phụ cấp', 'route' => 'admin.phu-cap.index'];
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
            @endif

            {{-- 🔹 BẢNG LƯƠNG CỦA TÔI (EMPLOYEE) --}}
            @if ($canViewPayroll)
                <li>
                    <a href="{{ route('employee.bang-luong.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ $currentRoute == 'employee.bang-luong.index' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <span class="w-5 h-5 mr-3 flex-shrink-0 text-gray-700 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        <span class="font-medium menu-text">Bảng lương của tôi</span>
                    </a>
                </li>
            @endif
            

            {{-- 🔹 HỢP ĐỒNG (ADMIN) --}}
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

            {{-- 🔹 HỢP ĐỒNG CỦA TÔI (EMPLOYEE) --}}
            @if ($canViewContractPersonal)
                <li>
                    <a href="{{ route('employee.hop-dong.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ $currentRoute == 'employee.hop-dong.index' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <span class="w-5 h-5 mr-3 flex-shrink-0 text-gray-700 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </span>
                        <span class="font-medium menu-text">Hợp đồng của tôi</span>
                    </a>
                </li>
            @endif

            {{-- 🔹 DUYỆT ĐƠN (ADMIN) --}}
            @if ($canApproveLeave)
                <li>
                    <a href="{{ route('admin.don_nghi.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ $currentRoute == 'admin.don_nghi.index' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <span class="w-5 h-5 mr-3 flex-shrink-0 text-gray-700 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        <span class="font-medium menu-text">Duyệt đơn</span>
                    </a>
                </li>
            @endif

            {{-- 🔹 ĐƠN NGHỈ PHÉP (EMPLOYEE) --}}
            @if ($canViewLeaveHistory || $canRequestLeave)
                <li>
                    <a href="{{ route('employee.don-nghi.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ $currentRoute == 'employee.don-nghi.index' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <span class="w-5 h-5 mr-3 flex-shrink-0 text-gray-700 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <span class="font-medium menu-text">Đơn nghỉ phép</span>
                    </a>
                </li>
            @endif

            {{-- 🔹 LOẠI NGHỈ PHÉP (ADMIN) --}}
            @if ($canViewLeaveType)
                <li>
                    <a href="{{ route('admin.loai-nghi-phep.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ $currentRoute == 'admin.loai-nghi-phep.index' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <span class="w-5 h-5 mr-3 flex-shrink-0 text-gray-700 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </span>
                        <span class="font-medium menu-text">Loại nghỉ phép</span>
                    </a>
                </li>
            @endif

            {{-- 🔹 ĐÀO TẠO (ADMIN) --}}
            @if ($canViewDaoTao)
                <li>
                    <a href="{{ route('admin.dao-tao.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ $currentRoute == 'admin.dao-tao.index' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <span class="w-5 h-5 mr-3 flex-shrink-0 text-gray-700 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </span>
                        <span class="font-medium menu-text">Đào tạo</span>
                    </a>
                </li>
            @endif

            {{-- 🔹 KHEN THƯỞNG / KỶ LUẬT (ADMIN) --}}
            @if ($canViewReward)
                @php
                    $submenuReward = [];

                    if (Route::has('admin.khen-thuong-ky-luat.index')) {
                        $submenuReward[] = [
                            'title' => 'Danh sách',
                            'route' => 'admin.khen-thuong-ky-luat.index',
                        ];
                    }

                    if (Route::has('admin.khen-thuong-ky-luat.thong-ke')) {
                        $submenuReward[] = [
                            'title' => 'Thống kê',
                            'route' => 'admin.khen-thuong-ky-luat.thong-ke',
                        ];
                    }
                @endphp

                @if (!empty($submenuReward))
                    <li>
                        <details class="menu-details"
                            {{ str_starts_with($currentRoute, 'admin.khen-thuong-ky-luat.') ? 'open' : '' }}>

                            <summary
                                class="flex items-center w-full px-3 py-2.5 rounded-lg transition-colors cursor-pointer {{ str_starts_with($currentRoute, 'admin.khen-thuong-ky-luat.') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                <span class="w-5 h-5 mr-3 flex-shrink-0 text-gray-700 dark:text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="1.5">

                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.5 18.75h-9A2.25 2.25 0 015.25 16.5v-9A2.25 2.25 0 017.5 5.25h4.5l2.25 2.25h2.25A2.25 2.25 0 0118.75 9.75v6.75A2.25 2.25 0 0116.5 18.75z" />

                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9.75l.9 1.83 2.02.29-1.46 1.42.34 2.01L12 14.36l-1.8.94.34-2.01-1.46-1.42 2.02-.29L12 9.75z" />
                                    </svg>
                                </span>

                                <span class="flex-1 text-left font-medium menu-text">
                                    Khen thưởng / Kỷ luật
                                </span>

                                <svg class="w-4 h-4 transition-transform duration-200 arrow-icon flex-shrink-0
                        {{ str_starts_with($currentRoute, 'admin.khen-thuong-ky-luat.') ? 'rotate-180' : '' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>

                            </summary>

                            <ul class="pl-10 mt-1 space-y-1">

                                @foreach ($submenuReward as $sub)
                                    <li>
                                        <a href="{{ route($sub['route']) }}"
                                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors
                                {{ $currentRoute == $sub['route']
                                    ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                    : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

                                            <span class="menu-text">
                                                {{ $sub['title'] }}
                                            </span>

                                        </a>
                                    </li>
                                @endforeach

                            </ul>

                        </details>
                    </li>
                @endif
            @endif

            {{-- 🔹 QUY ĐỊNH (ADMIN) --}}
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

            {{-- 🔹 QUY ĐỊNH (EMPLOYEE) --}}
            @if ($canViewRegulationEmployee)
                <li>
                    <a href="{{ route('employee.quydinh.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ $currentRoute == 'employee.quydinh.index' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
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

            {{-- 🔹 CÀI ĐẶT (ADMIN) --}}
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
                                        d="M19 9l-7 7-7-7"></path>
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

            {{-- 🔹 THÔNG BÁO --}}
            @if ($canViewNotifications)
                <li>
                    <a href="{{ route('employee.notifications.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ $currentRoute == 'employee.notifications.index' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <span class="w-5 h-5 mr-3 flex-shrink-0 text-gray-700 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </span>
                        <span class="font-medium menu-text">Thông báo</span>
                        @php
                            $unreadCount = $user->unreadNotifications->count();
                        @endphp
                        @if ($unreadCount > 0)
                            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>
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
