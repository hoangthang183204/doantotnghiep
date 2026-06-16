{{-- resources/views/employee/partials/header.blade.php --}}
<header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    <div class="px-4 py-3 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <button id="mobileSidebarToggle" class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <h1 class="text-lg font-semibold text-gray-900 dark:text-white">
                @yield('title', 'Dashboard')
            </h1>
        </div>

        <div class="flex items-center space-x-4">
            <!-- Theme Toggle -->
            <button id="themeToggle" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <i class="fas fa-moon dark:hidden"></i>
                <i class="fas fa-sun hidden dark:inline"></i>
            </button>

            <!-- Notification -->
            <div class="relative">
                <button id="notificationToggle" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 relative">
                    <i class="fas fa-bell text-lg"></i>
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[10px] rounded-full flex items-center justify-center">3</span>
                </button>
                <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
                    <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Thông báo</span>
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        <a href="#" class="flex items-start px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <div class="w-2 h-2 mt-2 rounded-full bg-blue-500 flex-shrink-0"></div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-700 dark:text-gray-200">Đơn nghỉ phép đã được duyệt</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">5 phút trước</p>
                            </div>
                        </a>
                        <a href="#" class="flex items-start px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <div class="w-2 h-2 mt-2 rounded-full bg-green-500 flex-shrink-0"></div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-700 dark:text-gray-200">Chấm công thành công</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">1 giờ trước</p>
                            </div>
                        </a>
                        <a href="#" class="flex items-start px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <div class="w-2 h-2 mt-2 rounded-full bg-yellow-500 flex-shrink-0"></div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-700 dark:text-gray-200">Nhắc nhở: Cập nhật hồ sơ</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">2 giờ trước</p>
                            </div>
                        </a>
                    </div>
                    <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700 text-center">
                        <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Xem tất cả</a>
                    </div>
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="relative">
                <button id="userMenuToggle" class="flex items-center space-x-3 text-sm focus:outline-none">
                    <img src="{{ asset('storage/' . (auth()->user()->hoSo->anh_dai_dien ?? 'avatars/default.png')) }}" 
                         alt="Avatar" 
                         class="w-8 h-8 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ session('user.ho_ten') ?? auth()->user()->ten_dang_nhap }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ session('user.vai_tro') ?? 'Nhân viên' }}
                        </p>
                    </div>
                    <i class="fas fa-chevron-down text-xs text-gray-400 dark:text-gray-500"></i>
                </button>

                <div id="userDropdown" class="hidden absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
                    <a href="{{ route('employee.ho-so.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-user-circle w-5 text-gray-400 dark:text-gray-500"></i>
                        <span class="ml-3">Hồ sơ của tôi</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-cog w-5 text-gray-400 dark:text-gray-500"></i>
                        <span class="ml-3">Cài đặt</span>
                    </a>
                    <hr class="my-1 border-gray-200 dark:border-gray-700">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                            <i class="fas fa-sign-out-alt w-5 text-red-400 dark:text-red-500"></i>
                            <span class="ml-3">Đăng xuất</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>