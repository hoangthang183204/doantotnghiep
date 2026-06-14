@extends('layouts.admin')

@section('title', 'Tổng quan hệ thống')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tổng quan hệ thống</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Tổng quan về hoạt động nhân sự và chấm công</p>
        </div>

        <!-- Stats Cards Row 1 -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-5">
            <!-- Total Employees -->
            <div class="card p-6">
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
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Tổng nhân viên</dt>
                        <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $tongNguoiDung ?? 0 }}</dd>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                            <i class="mdi mdi-account-check"></i> Đang đi làm
                        </p>
                    </div>
                </div>
            </div>

            <!-- New Employees -->
            <div class="card p-6">
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
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Nhân viên mới</dt>
                        <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $nhanVienMoi ?? 0 }}</dd>
                        <p
                            class="text-xs {{ ($tyLeNhanVienMoiThayDoi ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                            <i
                                class="mdi {{ ($tyLeNhanVienMoiThayDoi ?? 0) >= 0 ? 'mdi-trending-up' : 'mdi-trending-down' }}"></i>
                            <span>{{ number_format($tyLeNhanVienMoiThayDoi ?? 0, 1) }}%</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Attendance Today -->
            <div class="card p-6">
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
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Chấm công hôm nay</dt>
                        <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $nhanVienChamCongHomNay ?? 0 }}
                        </dd>
                        <p class="text-xs {{ ($tyLeChamCongThayDoi ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                            <i
                                class="mdi {{ ($tyLeChamCongThayDoi ?? 0) >= 0 ? 'mdi-trending-up' : 'mdi-trending-down' }}"></i>
                            <span>{{ number_format($tyLeChamCongThayDoi ?? 0, 1) }}%</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- On Leave Today -->
            <div class="card p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-full">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Nghỉ phép hôm nay</dt>
                        <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $nhanVienNghiPhepHomNay ?? 0 }}
                        </dd>
                        <p class="text-xs {{ ($tyLeNghiPhepThayDoi ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                            <i
                                class="mdi {{ ($tyLeNghiPhepThayDoi ?? 0) >= 0 ? 'mdi-trending-up' : 'mdi-trending-down' }}"></i>
                            <span>{{ number_format($tyLeNghiPhepThayDoi ?? 0, 1) }}%</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Candidates -->
            <div class="card p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-full">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Ứng viên mới</dt>
                        <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $tongUngVien ?? 0 }}</dd>
                        <p class="text-xs {{ ($tyLeUngVienThayDoi ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                            <i
                                class="mdi {{ ($tyLeUngVienThayDoi ?? 0) >= 0 ? 'mdi-trending-up' : 'mdi-trending-down' }}"></i>
                            <span>{{ number_format($tyLeUngVienThayDoi ?? 0, 1) }}%</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Attendance Chart -->
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

            <!-- Employee by Department Chart -->
            <div class="card p-6">
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Số lượng nhân viên theo phòng ban</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Phân bố nhân sự các phòng ban</p>
                </div>
                <canvas id="employeeChart" height="280"></canvas>
            </div>
        </div>

        <!-- Second Row -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- New Members List -->
            <div class="card p-6 lg:col-span-1">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Thành viên mới</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Nhân viên mới nhất</p>
                    </div>
                    <span
                        class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">{{ count($employees ?? []) }}
                        người</span>
                </div>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($employees ?? [] as $employee)
                        @php
                            $hoSo = $employee->hoSo ?? null;
                            $hoTen = '';
                            if ($hoSo) {
                                $hoTen = trim(($hoSo->ho ?? '') . ' ' . ($hoSo->ten ?? ''));
                            }
                            if (empty($hoTen)) {
                                $hoTen = $employee->nguoiDung->ten_dang_nhap ?? 'Nhân viên';
                            }
                            $avatar =
                                $hoSo && $hoSo->anh_dai_dien
                                    ? asset($hoSo->anh_dai_dien)
                                    : asset('assets/images/default.png');
                            $initial = strtoupper(substr($hoTen, 0, 1));
                        @endphp
                        <div
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <div class="flex-shrink-0">
                                @if ($hoSo && $hoSo->anh_dai_dien)
                                    <img src="{{ $avatar }}" alt="{{ $hoTen }}"
                                        class="w-10 h-10 rounded-full object-cover"
                                        onerror="this.src='{{ asset('assets/images/default.png') }}'">
                                @else
                                    <div
                                        class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white font-bold text-sm">
                                        {{ $initial }}
                                    </div>
                                @endif
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
                                    {{ $hoSo && $hoSo->created_at ? Carbon\Carbon::parse($hoSo->created_at)->diffForHumans() : 'Mới' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">Không có dữ liệu</p>
                    @endforelse
                </div>
            </div>

            <!-- Gender Chart -->
            <div class="card p-6 lg:col-span-1">
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Thống kê giới tính</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tỷ lệ nam/nữ trong công ty</p>
                </div>
                <canvas id="genderChart" height="200"></canvas>
                <div id="genderLegend" class="flex justify-center gap-4 mt-4"></div>
            </div>

            <!-- Leave Report -->
            <div class="card p-6 lg:col-span-1">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Thống kê nghỉ phép</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Phân tích theo tháng</p>
                    </div>
                    <select id="monthSelect" onchange="loadLeaveChart(this.value)"
                        class="px-3 py-1 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800">
                        <option value="1" {{ date('m') == '01' ? 'selected' : '' }}>Tháng 1</option>
                        <option value="2" {{ date('m') == '02' ? 'selected' : '' }}>Tháng 2</option>
                        <option value="3" {{ date('m') == '03' ? 'selected' : '' }}>Tháng 3</option>
                        <option value="4" {{ date('m') == '04' ? 'selected' : '' }}>Tháng 4</option>
                        <option value="5" {{ date('m') == '05' ? 'selected' : '' }}>Tháng 5</option>
                        <option value="6" {{ date('m') == '06' ? 'selected' : '' }}>Tháng 6</option>
                        <option value="7" {{ date('m') == '07' ? 'selected' : '' }}>Tháng 7</option>
                        <option value="8" {{ date('m') == '08' ? 'selected' : '' }}>Tháng 8</option>
                        <option value="9" {{ date('m') == '09' ? 'selected' : '' }}>Tháng 9</option>
                        <option value="10" {{ date('m') == '10' ? 'selected' : '' }}>Tháng 10</option>
                        <option value="11" {{ date('m') == '11' ? 'selected' : '' }}>Tháng 11</option>
                        <option value="12" {{ date('m') == '12' ? 'selected' : '' }}>Tháng 12</option>
                    </select>
                </div>
                <canvas id="leaveChart" height="200"></canvas>
            </div>
        </div>
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

        // ==================== BIỂU ĐỒ CHẤM CÔNG (HỖ TRỢ TRÒN/DONUT/CỘT) ====================
        function createAttendanceChart(type = 'bar') {
            const ctx = document.getElementById('attendanceChart');
            if (!ctx) return;

            // Hủy biểu đồ cũ nếu có
            if (attendanceChart) {
                attendanceChart.destroy();
            }

            // Kiểm tra có dữ liệu không
            const hasData = attendanceData.some(v => v > 0);
            if (!hasData) {
                ctx.getContext('2d').clearRect(0, 0, ctx.width, ctx.height);
                return;
            }

            // Cấu hình chung
            let config = {
                type: type,
                data: {
                    labels: months,
                    datasets: [{
                        data: attendanceData,
                        backgroundColor: '#3b82f6',
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

            // Cấu hình riêng cho từng loại biểu đồ
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
                // Cho pie và doughnut
                config.options.plugins.legend.position = 'right';
                config.options.cutout = type === 'doughnut' ? '50%' : 0;
            }

            attendanceChart = new Chart(ctx, config);
            updateStatsDisplay();
        }

        // Cập nhật thống kê phía dưới biểu đồ
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

        // Chuyển đổi loại biểu đồ
        function toggleChartType(type) {
            currentChartType = type;
            createAttendanceChart(type);

            // Cập nhật style cho các nút
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

            // Legend cho biểu đồ giới tính
            const legendContainer = document.getElementById('genderLegend');
            if (legendContainer && labelsGender.length > 0) {
                legendContainer.innerHTML = '';
                labelsGender.forEach((label, index) => {
                    const div = document.createElement('div');
                    div.className = 'flex items-center gap-2';
                    div.innerHTML = `
                    <div class="w-3 h-3 rounded-full" style="background: ${['#3b82f6', '#ec4899', '#10b981'][index]}"></div>
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

            // Lấy dữ liệu từ database
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
            // Khởi tạo biểu đồ chấm công
            createAttendanceChart('bar');
            createLeaveChart();

            // Set active button mặc định
            const btnBar = document.getElementById('btnBar');
            if (btnBar) {
                btnBar.classList.remove('bg-gray-100', 'dark:bg-gray-700');
                btnBar.classList.add('bg-blue-500', 'text-white');
            }

            // Cập nhật thống kê lần đầu
            updateStatsDisplay();
        });
    </script>
@endpush
