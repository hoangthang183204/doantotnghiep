<header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-end px-4 py-3">
        <div class="flex items-center space-x-4">
            <!-- Theme Toggle -->
            <button type="button" id="themeToggle"
                class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
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
                    console.log('✅ Notification ready');
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
                        const response = await fetch('/api/notifications', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            credentials: 'same-origin'
                        });
                        
                        if (!response.ok) {
                            console.error('❌ HTTP Error:', response.status);
                            throw new Error('Network error');
                        }
                        
                        const data = await response.json();
                        
                        // Lọc chỉ hiển thị thông báo chưa đọc
                        this.notifications = data.data || [];
                        this.unreadCount = data.unread_count || 0;
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
                        
                        // ⭐ XÓA THÔNG BÁO KHỎI DANH SÁCH
                        this.notifications = this.notifications.filter(n => n.id !== notification.id);
                        this.unreadCount = Math.max(0, this.unreadCount - 1);
                        
                        console.log('✅ Đã xóa thông báo:', notification.id);
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
                        
                        // ⭐ XÓA TẤT CẢ THÔNG BÁO
                        this.notifications = [];
                        this.unreadCount = 0;
                        
                        console.log('✅ Đã xóa tất cả thông báo');
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
                    class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span x-show="unreadCount > 0"
                        x-text="unreadCount > 99 ? '99+' : unreadCount"
                        class="absolute -top-1 -right-1 min-w-[16px] h-4 bg-red-500 rounded-full text-white text-[10px] flex items-center justify-center px-1">
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
                    
                    <div class="p-3 border-b dark:border-gray-700 flex justify-between items-center">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Thông báo</h3>
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

                    <!-- List - Chỉ hiển thị thông báo chưa đọc -->
                    <div x-show="!loading && notifications.length > 0" class="max-h-96 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
                        <template x-for="notification in notifications" :key="notification.id">
                            <a :href="notification.data.url || '#'" 
                                @click="markAsRead(notification)"
                                class="flex items-start p-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer bg-blue-50 dark:bg-blue-900/20">
                                
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
                                    <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2" 
                                        x-text="notification.data.message">
                                    </p>
                                    <div class="mt-1">
                                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                    </div>
                                </div>
                            </a>
                        </template>
                    </div>

                    <div class="p-3 border-t dark:border-gray-700 text-center">
                        <a href="{{ route('admin.notifications.index') }}" 
                            class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400">
                            Xem tất cả
                        </a>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- USER DROPDOWN - INLINE ALPINE              -->
            <!-- ========================================== -->
            <div class="relative" x-data="{ isOpen: false, toggle() { this.isOpen = !this.isOpen; } }">
                <button type="button" 
                    @click="toggle()"
                    class="flex items-center space-x-2 focus:outline-none group">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-semibold">
                        @php
                            $avatar = session('user.ho_ten', session('user.ten_dang_nhap', 'AD'));
                            $words = explode(' ', trim($avatar));
                            $initials = '';
                            if (count($words) >= 2) {
                                $initials = substr($words[0], 0, 1) . substr(end($words), 0, 1);
                            } else {
                                $initials = substr($avatar, 0, 2);
                            }
                            echo strtoupper($initials);
                        @endphp
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ session('user.ho_ten', session('user.ten_dang_nhap', 'Admin')) }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ session('user.vai_tro', 'Quản trị viên') }}
                        </p>
                    </div>
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 transition-transform duration-200"
                        :class="{ 'rotate-180': isOpen }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="isOpen" 
                    @click.away="isOpen = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50 overflow-hidden"
                    style="display: none;">
                    
                    <!-- ... giữ nguyên user dropdown ... -->
                    <div class="p-4 border-b dark:border-gray-700 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-700 dark:to-gray-800">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-lg">
                                @php
                                    $avatar2 = session('user.ho_ten', session('user.ten_dang_nhap', 'AD'));
                                    $words2 = explode(' ', trim($avatar2));
                                    $initials2 = '';
                                    if (count($words2) >= 2) {
                                        $initials2 = substr($words2[0], 0, 1) . substr(end($words2), 0, 1);
                                    } else {
                                        $initials2 = substr($avatar2, 0, 2);
                                    }
                                    echo strtoupper($initials2);
                                @endphp
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                    {{ session('user.ho_ten', session('user.ten_dang_nhap', 'Admin')) }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                    {{ session('user.email', 'admin@hrflow.com') }}
                                </p>
                                <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                    {{ session('user.vai_tro', 'Quản trị viên') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t dark:border-gray-700"></div>
                    <div class="py-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <svg class="w-5 h-5 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>