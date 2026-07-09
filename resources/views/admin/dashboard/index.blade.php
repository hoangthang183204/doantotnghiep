@extends('layouts.admin')

@section('title', 'Tổng quan hệ thống')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tổng quan hệ thống</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Tổng quan về hoạt động nhân sự và chấm công</p>

            {{-- Hiển thị vai trò hiện tại --}}
            <div class="mt-2 flex items-center gap-2">
                <span class="text-xs text-gray-400">Vai trò:</span>
                @php
                    $user = Auth::user();
                    $roleNames = $user->vaiTros->pluck('ten_hien_thi')->implode(', ');
                @endphp
                <span
                    class="px-2 py-1 text-xs font-medium rounded-full 
                    @if ($user->isAdmin()) bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                    @elseif($user->isHR()) bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                    @elseif($user->isTruongPhong()) bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                    @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400 @endif">
                    {{ $roleNames ?: 'Nhân viên' }}
                </span>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- PHẦN 1: STATS CARDS - Hiển thị theo vai trò                   -->
        <!-- ============================================================ -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">

            <!-- Card 1: Tổng nhân viên - Ai cũng thấy -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Tổng nhân viên</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $tongNguoiDung ?? 0 }}</p>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                            <i class="mdi mdi-account-check"></i> Đang đi làm
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 2: Nhân viên mới - Hiển thị cho HR và Admin -->
            @if (isHR() || isAdmin())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Nhân viên mới</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $nhanVienMoi ?? 0 }}</p>
                            <p
                                class="text-xs {{ ($tyLeNhanVienMoiThayDoi ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                                <i
                                    class="mdi {{ ($tyLeNhanVienMoiThayDoi ?? 0) >= 0 ? 'mdi-trending-up' : 'mdi-trending-down' }}"></i>
                                <span>{{ number_format($tyLeNhanVienMoiThayDoi ?? 0, 1) }}%</span>
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Card 3: Chấm công hôm nay - Ai cũng thấy -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-full">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Chấm công hôm nay</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                            @if (isNhanVien())
                                @php
                                    $userAttendance = \App\Models\ChamCong::where('nguoi_dung_id', auth()->id())
                                        ->whereDate('ngay_cham_cong', \Carbon\Carbon::today())
                                        ->first();
                                @endphp
                                @if ($userAttendance && $userAttendance->gio_vao)
                                    ✅ Đã chấm công
                                @else
                                    ⏳ Chưa chấm công
                                @endif
                            @else
                                {{ $nhanVienChamCongHomNay ?? 0 }}
                            @endif
                        </p>
                        <p class="text-xs {{ ($tyLeChamCongThayDoi ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                            <i
                                class="mdi {{ ($tyLeChamCongThayDoi ?? 0) >= 0 ? 'mdi-trending-up' : 'mdi-trending-down' }}"></i>
                            <span>{{ number_format($tyLeChamCongThayDoi ?? 0, 1) }}%</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 4: Nghỉ phép hôm nay - Hiển thị cho HR, Trưởng phòng, Admin -->
            @if (isHR() || isTruongPhong() || isAdmin())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-full">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Nghỉ phép hôm nay</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ $nhanVienNghiPhepHomNay ?? 0 }}</p>
                            <p
                                class="text-xs {{ ($tyLeNghiPhepThayDoi ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                                <i
                                    class="mdi {{ ($tyLeNghiPhepThayDoi ?? 0) >= 0 ? 'mdi-trending-up' : 'mdi-trending-down' }}"></i>
                                <span>{{ number_format($tyLeNghiPhepThayDoi ?? 0, 1) }}%</span>
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ❌ ĐÃ XÓA CARD 5: ỨNG VIÊN MỚI --}}

        </div>

        <!-- ============================================================ -->
        <!-- PHẦN 2: BIỂU ĐỒ - Hiển thị cho HR, Trưởng phòng, Admin      -->
        <!-- ============================================================ -->

        {{-- Kiểm tra nếu user có ít nhất 1 trong các quyền để xem biểu đồ --}}
        @php
            $canViewCharts = canAny(['attendance.index', 'hoso.index', 'attendance.view']);
        @endphp

        @if ($canViewCharts || isAdmin() || isHR() || isTruongPhong())
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                <!-- Biểu đồ 1: Tỷ lệ chấm công -->
                <div class="card p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Tỷ lệ chấm công theo tháng</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Phân tích tỷ lệ chấm công trong năm</p>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="toggleChartType('pie')"
                                class="px-3 py-1 text-sm rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                                id="btnPie">
                                <i class="mdi mdi-chart-pie"></i> Tròn
                            </button>
                            <button onclick="toggleChartType('doughnut')"
                                class="px-3 py-1 text-sm rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                                id="btnDoughnut">
                                <i class="mdi mdi-chart-donut"></i> Donut
                            </button>
                            <button onclick="toggleChartType('bar')"
                                class="px-3 py-1 text-sm rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                                id="btnBar">
                                <i class="mdi mdi-chart-bar"></i> Cột
                            </button>
                        </div>
                    </div>
                    <canvas id="attendanceChart" height="280"></canvas>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-4 gap-4 mt-4 pt-4 border-t dark:border-gray-700">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600" id="activeMonths">0</p>
                            <p class="text-xs text-gray-500">Tháng có dữ liệu</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-green-600" id="totalRate">0%</p>
                            <p class="text-xs text-gray-500">Tổng tỷ lệ</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-purple-600" id="avgActive">0%</p>
                            <p class="text-xs text-gray-500">Trung bình tháng</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-orange-600" id="maxMonth">--</p>
                            <p class="text-xs text-gray-500">Tháng cao nhất</p>
                        </div>
                    </div>
                </div>

                <!-- Biểu đồ 2: Nhân viên theo phòng ban -->
                <div class="card p-6">
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Số lượng nhân viên theo phòng ban
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Phân bố nhân sự các phòng ban</p>
                    </div>
                    <canvas id="employeeChart" height="280"></canvas>
                </div>
            </div>
        @endif

        <!-- ============================================================ -->
        <!-- PHẦN 3: HÀNG THỨ 2 - Thành viên mới, Giới tính, Nghỉ phép   -->
        <!-- ============================================================ -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            <!-- Thành viên mới - Hiển thị cho HR và Admin -->
            @if (isHR() || isAdmin())
                <div class="card p-6 lg:col-span-1">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Thành viên mới</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nhân viên mới nhất</p>
                        </div>
                        <span
                            class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                            {{ count($employees ?? []) }} người
                        </span>
                    </div>
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @forelse($employees ?? [] as $employee)
                            @php
                                $hoSo = $employee->nguoiDung->hoSo ?? null;
                                $hoTen = '';
                                if ($hoSo) {
                                    $hoTen = trim(($hoSo->ho ?? '') . ' ' . ($hoSo->ten ?? ''));
                                }
                                if (empty($hoTen)) {
                                    $hoTen = $employee->nguoiDung->ten_dang_nhap ?? 'Nhân viên';
                                }
                                $initial = strtoupper(substr($hoTen, 0, 1));
                            @endphp
                            <div
                                class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white font-bold text-sm">
                                        {{ $initial }}
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        {{ $hoTen }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $hoSo && $hoSo->created_at ? \Carbon\Carbon::parse($hoSo->created_at)->diffForHumans() : 'Mới' }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400 text-center py-4">Không có dữ liệu</p>
                        @endforelse
                    </div>
                </div>
            @endif

            <!-- Biểu đồ giới tính - Hiển thị cho HR và Admin -->
            @if (isHR() || isAdmin())
                <div class="card p-6 lg:col-span-1">
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Thống kê giới tính</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tỷ lệ nam/nữ trong công ty</p>
                    </div>
                    <canvas id="genderChart" height="200"></canvas>
                    <div id="genderLegend" class="flex justify-center gap-4 mt-4"></div>
                </div>
            @endif

            <!-- Thống kê nghỉ phép - Hiển thị cho HR, Trưởng phòng, Admin -->
            @if (isHR() || isTruongPhong() || isAdmin())
                <div class="card p-6 lg:col-span-1">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Thống kê nghỉ phép</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Phân tích theo tháng</p>
                        </div>
                        <select id="monthSelect" onchange="loadLeaveChart(this.value)"
                            class="px-3 py-1 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ date('m') == sprintf('%02d', $i) ? 'selected' : '' }}>
                                    Tháng {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <canvas id="leaveChart" height="200"></canvas>
                </div>
            @endif

            {{-- Nếu không có quyền gì, hiển thị thông báo --}}
            @if (!isHR() && !isAdmin() && !isTruongPhong())
                <div class="card p-6 lg:col-span-3">
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Chào mừng bạn!</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Bạn có thể sử dụng các chức năng cơ bản như chấm công và tạo đơn nghỉ phép.
                        </p>
                        <div class="mt-4 flex justify-center gap-4">
                            @can('attendance.checkin')
                                <a href="{{ route('admin.attendance.checkin-view') }}"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition">
                                    🕐 Chấm công
                                </a>
                            @endcan
                            @can('leave.request')
                                <a href="{{ route('admin.leave.create') }}"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm transition">
                                    📝 Tạo đơn nghỉ
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            @endif
        </div>


        <!-- PHẦN QUẢN TRỊ NHANH - CHỈ ADMIN -->
        @isAdmin
            <div class="card p-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">⚡ Quản trị nhanh</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Các chức năng quản trị hệ thống</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                        🔐 Admin
                    </span>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">

                    {{-- PHÂN QUYỀN --}}
                    @if (Route::has('admin.phan-quyen.index'))
                        <a href="{{ route('admin.phan-quyen.index') }}"
                            class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl text-center hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors group">
                            <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">🔐</div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Phân quyền</p>
                        </a>
                    @endif

                    {{-- NGƯỜI DÙNG --}}
                    @if (Route::has('admin.user.index'))
                        <a href="{{ route('admin.user.index') }}"
                            class="p-4 bg-green-50 dark:bg-green-900/20 rounded-xl text-center hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors group">
                            <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">👥</div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Người dùng</p>
                        </a>
                    @endif

                    {{-- CÀI ĐẶT --}}
                    @if (Route::has('admin.setting.index'))
                        <a href="{{ route('admin.setting.index') }}"
                            class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl text-center hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors group">
                            <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">⚙️</div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Cài đặt</p>
                        </a>
                    @endif

                    {{-- PHÒNG BAN --}}
                    @if (Route::has('admin.phong-ban.index'))
                        <a href="{{ route('admin.phong-ban.index') }}"
                            class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl text-center hover:bg-yellow-100 dark:hover:bg-yellow-900/30 transition-colors group">
                            <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">🏢</div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Phòng ban</p>
                        </a>
                    @endif

                    {{-- CHỨC VỤ --}}
                    @if (Route::has('admin.chuc-vu.index'))
                        <a href="{{ route('admin.chuc-vu.index') }}"
                            class="p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl text-center hover:bg-indigo-100 dark:hover:bg-indigo-900/30 transition-colors group">
                            <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">💼</div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Chức vụ</p>
                        </a>
                    @endif

                    {{-- BÁO CÁO --}}
                    <a href="#"
                        class="p-4 bg-red-50 dark:bg-red-900/20 rounded-xl text-center hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors group">
                        <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">📊</div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Báo cáo</p>
                    </a>
                </div>
            </div>
        @endisAdmin

        <!-- ============================================================ -->
        <!-- PHẦN 5: ĐƠN NGHỈ CHỜ DUYỆT - Chỉ HR và Trưởng phòng         -->
        <!-- ============================================================ -->
        @can('leave.approve')
            @if (isset($donChoDuyetList) && count($donChoDuyetList) > 0)
                <div class="card p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">📋 Đơn nghỉ chờ duyệt</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Các đơn nghỉ phép đang chờ phê duyệt</p>
                        </div>
                        <a href="{{ route('admin.leave.approve') }}"
                            class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                            Xem tất cả →
                        </a>
                    </div>
                    <div class="space-y-3">
                        @foreach ($donChoDuyetList as $don)
                            <div
                                class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center text-yellow-600 dark:text-yellow-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $don->nguoiDung->hoSo->ho ?? '' }}
                                            {{ $don->nguoiDung->hoSo->ten ?? $don->nguoiDung->ten_dang_nhap }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $don->loaiNghiPhep->ten ?? 'Nghỉ phép' }} •
                                            {{ \Carbon\Carbon::parse($don->ngay_bat_dau)->format('d/m') }} -
                                            {{ \Carbon\Carbon::parse($don->ngay_ket_thuc)->format('d/m/Y') }}
                                            ({{ $don->so_ngay_nghi }} ngày)
                                        </p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <form action="{{ route('admin.leave.approve.action', $don->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded-lg transition">
                                            ✅ Duyệt
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.leave.reject', $don->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-lg transition"
                                            onclick="return confirm('Từ chối đơn nghỉ này?')">
                                            ❌ Từ chối
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endcan
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ==================== DỮ LIỆU TỪ PHP ====================
        const attendanceData = @json($dataAverageAttendanceRate ?? []);
        const designationNames = @json($DesignationName ?? []);
        const designationSeries = @json($designationSeries ?? []);
        const labelsGender = @json($labelsGender ?? []);
        const dataGender = @json($dataGender ?? []);
        const months = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9',
            'Tháng 10', 'Tháng 11', 'Tháng 12'
        ];

        // ==================== BIẾN TOÀN CỤC ====================
        let attendanceChart = null;
        let currentChartType = 'bar';
        let leaveChart = null;

        // ==================== BIỂU ĐỒ CHẤM CÔNG ====================
        function createAttendanceChart(type = 'bar') {
            const ctx = document.getElementById('attendanceChart');
            if (!ctx) return;

            if (attendanceChart) {
                attendanceChart.destroy();
            }

            const hasData = attendanceData.some(v => v > 0);
            if (!hasData) {
                ctx.getContext('2d').clearRect(0, 0, ctx.width, ctx.height);
                return;
            }

            let config = {
                type: type,
                data: {
                    labels: months,
                    datasets: [{
                        data: attendanceData,
                        backgroundColor: type === 'bar' ? '#3b82f6' : ['#3b82f6', '#10b981', '#f59e0b',
                            '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#f97316', '#6366f1', '#84cc16',
                            '#06b6d4', '#d946ef'
                        ],
                        borderColor: 'white',
                        borderWidth: 2,
                        borderRadius: type === 'bar' ? 6 : 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: type === 'bar' ? 'top' : 'right',
                            labels: {
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    if (type === 'bar') {
                                        return `${context.label}: ${context.parsed.y}%`;
                                    }
                                    const total = attendanceData.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                    return `${context.label}: ${context.parsed}% (${percentage}% tổng)`;
                                }
                            }
                        }
                    }
                }
            };

            if (type === 'bar') {
                config.data.datasets[0].label = 'Tỷ lệ chấm công (%)';
                config.options.scales = {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: (v) => v + '%'
                        }
                    }
                };
            } else {
                config.options.plugins.legend.position = 'right';
                config.options.cutout = type === 'doughnut' ? '50%' : 0;
            }

            attendanceChart = new Chart(ctx, config);
            updateStatsDisplay();
        }

        function updateStatsDisplay() {
            const total = attendanceData.reduce((a, b) => a + b, 0);
            const activeMonths = attendanceData.filter(v => v > 0).length;
            const average = activeMonths > 0 ? (total / activeMonths).toFixed(1) : 0;
            const maxValue = Math.max(...attendanceData);
            const maxIndex = attendanceData.indexOf(maxValue);
            const maxMonth = maxValue > 0 ? months[maxIndex] : '--';

            const activeMonthsEl = document.getElementById('activeMonths');
            const totalRateEl = document.getElementById('totalRate');
            const avgActiveEl = document.getElementById('avgActive');
            const maxMonthEl = document.getElementById('maxMonth');

            if (activeMonthsEl) activeMonthsEl.textContent = activeMonths;
            if (totalRateEl) totalRateEl.textContent = total.toFixed(1) + '%';
            if (avgActiveEl) avgActiveEl.textContent = average + '%';
            if (maxMonthEl) maxMonthEl.textContent = maxMonth;
        }

        function toggleChartType(type) {
            currentChartType = type;
            createAttendanceChart(type);

            const buttons = ['btnPie', 'btnDoughnut', 'btnBar'];
            buttons.forEach(btnId => {
                const btn = document.getElementById(btnId);
                if (btn) {
                    btn.classList.remove('bg-blue-500', 'text-white');
                    btn.classList.add('bg-gray-100', 'dark:bg-gray-700');
                }
            });

            const activeBtn = document.getElementById(`btn${type.charAt(0).toUpperCase() + type.slice(1)}`);
            if (activeBtn) {
                activeBtn.classList.remove('bg-gray-100', 'dark:bg-gray-700');
                activeBtn.classList.add('bg-blue-500', 'text-white');
            }
        }

        // ==================== BIỂU ĐỒ NHÂN VIÊN THEO PHÒNG BAN ====================
        const ctx2 = document.getElementById('employeeChart');
        if (ctx2 && designationNames.length > 0) {
            new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: designationNames,
                    datasets: [{
                        label: 'Số lượng nhân viên',
                        data: designationSeries,
                        backgroundColor: '#10b981',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => `${ctx.raw} nhân viên`
                            }
                        }
                    }
                }
            });
        }

        // ==================== BIỂU ĐỒ GIỚI TÍNH ====================
        const ctx3 = document.getElementById('genderChart');
        if (ctx3) {
            new Chart(ctx3, {
                type: 'doughnut',
                data: {
                    labels: labelsGender,
                    datasets: [{
                        data: dataGender,
                        backgroundColor: ['#3b82f6', '#ec4899', '#10b981'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '60%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            const legendContainer = document.getElementById('genderLegend');
            if (legendContainer && labelsGender.length > 0) {
                legendContainer.innerHTML = '';
                const colors = ['#3b82f6', '#ec4899', '#10b981'];
                labelsGender.forEach((label, index) => {
                    const div = document.createElement('div');
                    div.className = 'flex items-center gap-2';
                    div.innerHTML = `
                        <div class="w-3 h-3 rounded-full" style="background: ${colors[index]}"></div>
                        <span class="text-sm">${label}: ${dataGender[index]}%</span>
                    `;
                    legendContainer.appendChild(div);
                });
            }
        }

        // ==================== BIỂU ĐỒ NGHỈ PHÉP ====================
        const sickLeaveData = @json($sickLeaveData ?? []);
        const casualLeaveData = @json($casualLeaveData ?? []);

        function createLeaveChart(month = null) {
            const ctx = document.getElementById('leaveChart');
            if (!ctx) return;

            if (leaveChart) leaveChart.destroy();

            const currentMonth = month !== null ? month - 1 : new Date().getMonth();
            const sickData = (sickLeaveData[currentMonth] && sickLeaveData[currentMonth].length > 0) ?
                sickLeaveData[currentMonth] : [0, 0, 0, 0, 0];

            const weekLabels = ['Tuần 1', 'Tuần 2', 'Tuần 3', 'Tuần 4', 'Tuần 5'];

            leaveChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: weekLabels,
                    datasets: [{
                        label: 'Số đơn nghỉ phép',
                        data: sickData,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#3b82f6',
                        pointBorderColor: '#fff',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => `${ctx.dataset.label}: ${ctx.raw} đơn`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            },
                            title: {
                                display: true,
                                text: 'Số đơn nghỉ'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Tuần'
                            }
                        }
                    }
                }
            });
        }

        function loadLeaveChart(month) {
            createLeaveChart(parseInt(month));
        }

        // ==================== KHỞI TẠO ====================
        document.addEventListener('DOMContentLoaded', function() {
            // Chỉ khởi tạo biểu đồ nếu phần tử tồn tại
            if (document.getElementById('attendanceChart')) {
                createAttendanceChart('bar');
                const btnBar = document.getElementById('btnBar');
                if (btnBar) {
                    btnBar.classList.remove('bg-gray-100', 'dark:bg-gray-700');
                    btnBar.classList.add('bg-blue-500', 'text-white');
                }
                updateStatsDisplay();
            }

            if (document.getElementById('leaveChart')) {
                createLeaveChart();
            }
        });
    </script>
@endpush