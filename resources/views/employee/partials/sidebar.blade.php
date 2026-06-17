{{-- resources/views/employee/partials/sidebar.blade.php --}}
<aside class="sidebar" id="sidebar">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-between px-4 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center space-x-3 logo-container">
                <div
                    class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-sm">
                    H
                </div>
                <span class="font-bold text-lg text-gray-800 dark:text-white logo-text">HRFlow</span>
            </div>
            <button id="toggleSidebarBtn"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 toggle-btn">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>

        <!-- Menu -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <!-- Thống kê -->
            <a href="{{ route('employee.dashboard') }}"
                class="menu-toggle flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('employee.dashboard') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}"
                data-title="Thống kê cá nhân">
                <i class="fas fa-chart-pie w-5 text-center"></i>
                <span class="ml-3 menu-text">Thống kê cá nhân</span>
            </a>

            <!-- Nhân sự -->
            <div class="pt-4">
                <a href="{{ route('employee.ho-so.index') }}"
                    class="menu-toggle flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('employee.ho-so*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}"
                    data-title="Hồ sơ cá nhân">
                    <i class="fas fa-user w-5 text-center"></i>
                    <span class="ml-3 menu-text">Hồ sơ cá nhân</span>
                </a>
            </div>

            <!-- Chấm công -->
            <div class="pt-4">

                <button onclick="toggleSubmenu('attendanceSubMenu')"
                    class="menu-toggle w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('employee.cham-cong*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}"
                    data-target="attendanceSubMenu" data-title="Chấm công">
                    <i class="fas fa-clock w-5 text-center"></i>
                    <span class="ml-3 menu-text">Chấm công</span>
                    <i
                        class="fas fa-chevron-down ml-auto text-xs arrow-icon transition-transform duration-200 {{ request()->routeIs('employee.cham-cong*') ? 'rotate-180' : '' }}"></i>
                </button>
                <div id="attendanceSubMenu"
                    class="submenu space-y-1 pl-8 {{ request()->routeIs('employee.cham-cong*') ? '' : 'hidden' }}">
                    <a href="{{ route('employee.cham-cong.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors duration-200 {{ request()->routeIs('employee.cham-cong.index') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                        <i class="fas fa-fingerprint w-4 text-center"></i>
                        <span class="ml-3">Chấm công</span>
                    </a>
                    <a href="{{ route('employee.tang-ca.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors duration-200 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
                        <i class="fas fa-plus-circle w-4 text-center"></i>
                        <span class="ml-3">Đơn xin tăng ca</span>
                    </a>

                    <a href="{{ route('employee.yeu-cau-chinh-cong.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors duration-200 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
                        <i class="fas fa-edit w-4 text-center"></i>
                        <span class="ml-3">Yêu cầu chỉnh công</span>
                    </a>
                </div>
            </div>

            <!-- Lương -->
            <div class="pt-4">
           
                <a href="#"
                    class="menu-toggle flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                    <i class="fas fa-money-bill-wave w-5 text-center"></i>
                    <span class="ml-3 menu-text">Bảng lương của tôi</span>
                </a>
            </div>

            <!-- Hợp đồng -->
            <div class="pt-4">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Hợp đồng</p>
                <a href="{{ route('employee.hop-dong.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 
                          {{ request()->routeIs('employee.hop-dong.index') ? 'bg-blue-50 text-blue-600 dark:bg-gray-700 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                <a href="#"
                    class="menu-toggle flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                    <i class="fas fa-file-contract w-5 text-center"></i>
                    <span class="ml-3 menu-text">Hợp đồng của tôi</span>
                </a>
            </div>

            <!-- Nghỉ phép -->
            <div class="pt-4">
               

                <button onclick="toggleSubmenu('leaveSubMenu')"
                    class="menu-toggle w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('employee.don-nghi*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}"
                    data-target="leaveSubMenu" data-title="Xin nghỉ phép">
                    <i class="fas fa-calendar-alt w-5 text-center"></i>
                    <span class="ml-3 menu-text">Xin nghỉ phép</span>
                    <i
                        class="fas fa-chevron-down ml-auto text-xs arrow-icon transition-transform duration-200 {{ request()->routeIs('employee.don-nghi*') ? 'rotate-180' : '' }}"></i>
                </button>
                <div id="leaveSubMenu"
                    class="submenu space-y-1 pl-8 {{ request()->routeIs('employee.don-nghi*') ? '' : 'hidden' }}">
                    <a href="{{ route('employee.don-nghi.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors duration-200 {{ request()->routeIs('employee.don-nghi.index') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                        <i class="fas fa-list w-4 text-center"></i>
                        <span class="ml-3">Đơn nghỉ phép</span>
                    </a>
                    <a href="{{ route('employee.don-nghi.create') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors duration-200 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
                        <i class="fas fa-plus-circle w-4 text-center"></i>
                        <span class="ml-3">Tạo đơn mới</span>
                    </a>
                </div>
            </div>

            <!-- Quy định -->
            <div class="pt-4">

                <a href="#"
                    class="menu-toggle flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                    <i class="fas fa-gavel w-5 text-center"></i>
                    <span class="ml-3 menu-text">Quy định</span>
                </a>
            </div>
        </nav>

    </div>
</aside>
