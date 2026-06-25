{{-- resources/views/employee/partials/header.blade.php --}}
<header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    <div class="px-4 py-3 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <button id="mobileSidebarToggle"
                class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <h1 class="text-lg font-semibold text-gray-900 dark:text-white">
                @yield('title', 'Dashboard')
            </h1>
        </div>

        <div class="flex items-center space-x-4">
            <!-- Theme Toggle -->
            <button id="themeToggle"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 relative w-8 h-8 flex items-center justify-center">
                <i
                    class="fas fa-sun text-yellow-500 text-lg absolute transition-opacity duration-300 dark:opacity-0 dark:scale-50"></i>
                <i
                    class="fas fa-moon text-gray-600 dark:text-yellow-400 text-lg absolute transition-opacity duration-300 opacity-0 scale-50 dark:opacity-100 dark:scale-100"></i>
            </button>

            <!-- ========================================== -->
            <!-- NOTIFICATIONS - INLINE ALPINE              -->
            <!-- ========================================== -->
            <div class="relative" x-data="{
                isOpen: false,
                notifications: [],
                unreadCount: 0,
                loading: false,
                timer: null,

                init() {
                    console.log('✅ Employee Notification ready');
                    this.fetchNotifications();
                    this.startPolling();
                },

                toggleDropdown() {
                    this.isOpen = !this.isOpen;
                    if (this.isOpen) {
                        this.fetchNotifications();
                    }
                },

                async fetchNotifications() {
                    this.loading = true;
                    try {
                        console.log('📤 Fetching employee notifications...');
                        const response = await fetch('/api/notifications', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            credentials: 'same-origin'
                        });
                        
                        console.log('📥 Response status:', response.status);
                        
                        if (!response.ok) {
                            console.error('❌ HTTP Error:', response.status);
                            throw new Error('Network error');
                        }
                        
                        const data = await response.json();
                        console.log('📦 Data received:', data);
                        
                        this.notifications = data.data || [];
                        this.unreadCount = data.unread_count || 0;
                        console.log('📨 Fetched:', this.notifications.length, 'notifications');
                    } catch (error) {
                        console.error('❌ Fetch error:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                async markAsRead(notification) {
                    if (notification.read_at) return;
                    try {
                        await fetch(`/api/notifications/${notification.id}/mark-as-read`, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'application/json',
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify({})
                        });
                        notification.read_at = new Date().toISOString();
                        this.unreadCount = Math.max(0, this.unreadCount - 1);
                    } catch (error) {
                        console.error('Mark read error:', error);
                    }
                },

                async markAllAsRead() {
                    try {
                        await fetch('/api/notifications/mark-all-as-read', {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'application/json',
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify({})
                        });
                        this.notifications.forEach(n => n.read_at = new Date().toISOString());
                        this.unreadCount = 0;
                    } catch (error) {
                        console.error('Mark all read error:', error);
                    }
                },

                startPolling() {
                    this.timer = setInterval(() => {
                        this.fetchUnreadCount();
                    }, 30000);
                },

                async fetchUnreadCount() {
                    try {
                        const response = await fetch('/api/notifications/unread-count', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            credentials: 'same-origin'
                        });
                        if (!response.ok) throw new Error('Network error');
                        const data = await response.json();
                        this.unreadCount = data.count || 0;
                    } catch (error) {
                        console.error('Unread count error:', error);
                    }
                },

                timeAgo(time) {
                    if (!time) return '';
                    const diff = Math.floor((new Date() - new Date(time)) / 1000);
                    if (diff < 60) return 'Vừa xong';
                    if (diff < 3600) return Math.floor(diff / 60) + ' phút trước';
                    if (diff < 86400) return Math.floor(diff / 3600) + ' giờ trước';
                    if (diff < 2592000) return Math.floor(diff / 86400) + ' ngày trước';
                    if (diff < 31536000) return Math.floor(diff / 2592000) + ' tháng trước';
                    return Math.floor(diff / 31536000) + ' năm trước';
                },

                destroy() {
                    if (this.timer) clearInterval(this.timer);
                }
            }">
                <!-- Nút chuông -->
                <button type="button" 
                    @click="toggleDropdown()"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 relative">
                    <i class="fas fa-bell text-lg"></i>
                    <span x-show="unreadCount > 0"
                        x-text="unreadCount > 99 ? '99+' : unreadCount"
                        class="absolute -top-1 -right-1 min-w-[16px] h-4 bg-red-500 text-white text-[10px] rounded-full flex items-center justify-center px-1">
                    </span>
                </button>

                <!-- Dropdown -->
                <div x-show="isOpen" 
                    @click.away="isOpen = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50"
                    style="display: none;">
                    
                    <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Thông báo</span>
                        <button x-show="unreadCount > 0" 
                            @click="markAllAsRead()"
                            class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400">
                            Đánh dấu đã đọc
                        </button>
                    </div>

                    <!-- Loading -->
                    <div x-show="loading" class="p-4 text-center">
                        <div class="inline-block animate-spin rounded-full h-6 w-6 border-2 border-blue-500 border-t-transparent"></div>
                    </div>

                    <!-- Empty -->
                    <div x-show="!loading && notifications.length === 0" class="p-8 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Không có thông báo nào</p>
                    </div>

                    <!-- List -->
                    <div x-show="!loading && notifications.length > 0" class="max-h-64 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
                        <template x-for="notification in notifications" :key="notification.id">
                            <a :href="notification.data.url || '#'" 
                                @click="markAsRead(notification)"
                                class="flex items-start px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer"
                                :class="{ 'bg-blue-50 dark:bg-blue-900/20': !notification.read_at }">
                                
                                <!-- Icon theo loại thông báo -->
                                <div class="flex-shrink-0 mt-0.5">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                                        :class="{
                                            'bg-blue-100 dark:bg-blue-900': notification.data.color === 'info' || !notification.data.color,
                                            'bg-green-100 dark:bg-green-900': notification.data.color === 'success',
                                            'bg-red-100 dark:bg-red-900': notification.data.color === 'danger',
                                            'bg-yellow-100 dark:bg-yellow-900': notification.data.color === 'warning'
                                        }">
                                        <i :class="'fas fa-' + (notification.data.icon || 'bell')"
                                            :class="{
                                                'text-blue-600 dark:text-blue-400': notification.data.color === 'info' || !notification.data.color,
                                                'text-green-600 dark:text-green-400': notification.data.color === 'success',
                                                'text-red-600 dark:text-red-400': notification.data.color === 'danger',
                                                'text-yellow-600 dark:text-yellow-400': notification.data.color === 'warning'
                                            }">
                                        </i>
                                    </div>
                                </div>

                                <div class="ml-3 flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate" 
                                            x-text="notification.data.title">
                                        </p>
                                        <span class="text-[10px] text-gray-400 whitespace-nowrap ml-2" 
                                            x-text="timeAgo(notification.created_at)">
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate" 
                                        x-text="notification.data.message">
                                    </p>
                                    <div x-show="!notification.read_at" class="mt-1">
                                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                    </div>
                                </div>
                            </a>
                        </template>
                    </div>

                    <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700 text-center">
                        <a href="{{ route('employee.notifications.index') }}" 
                            class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400">
                            Xem tất cả
                        </a>
                    </div>
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="relative">
                <button id="userMenuToggle" class="flex items-center space-x-3 text-sm focus:outline-none">
                    @php
                        $user = auth()->user();
                        $hoSo = $user->hoSo;
                        $avatarPath = $hoSo && $hoSo->anh_dai_dien ? $hoSo->anh_dai_dien : null;
                        $hasAvatar = $avatarPath && file_exists(storage_path('app/public/' . $avatarPath));
                        $initial = $hoSo
                            ? strtoupper(substr($hoSo->ho ?? ($hoSo->ten ?? $user->ten_dang_nhap), 0, 1))
                            : strtoupper(substr($user->ten_dang_nhap, 0, 1));
                    @endphp

                    @if ($hasAvatar)
                        <img src="{{ asset('storage/' . $avatarPath) }}" alt="Avatar"
                            class="w-8 h-8 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold border-2 border-gray-200 dark:border-gray-600"
                            style="display: none;">
                            {{ $initial }}
                        </div>
                    @else
                        <div
                            class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold border-2 border-gray-200 dark:border-gray-600">
                            {{ $initial }}
                        </div>
                    @endif

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

                <div id="userDropdown"
                    class="hidden absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
                    <a href="{{ route('employee.ho-so.index') }}"
                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-user-circle w-5 text-gray-400 dark:text-gray-500"></i>
                        <span class="ml-3">Hồ sơ của tôi</span>
                    </a>
                    <a href="#"
                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-cog w-5 text-gray-400 dark:text-gray-500"></i>
                        <span class="ml-3">Cài đặt</span>
                    </a>
                    <hr class="my-1 border-gray-200 dark:border-gray-700">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="flex items-center w-full px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                            <i class="fas fa-sign-out-alt w-5 text-red-400 dark:text-red-500"></i>
                            <span class="ml-3">Đăng xuất</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>