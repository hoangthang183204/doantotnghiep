{{-- resources/views/employee/dashboard.blade.php --}}
@extends('layouts.employee')

@section('title', 'Thống kê cá nhân')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-chart-pie mr-3 text-blue-600 dark:text-blue-400"></i>
                    Thống kê cá nhân
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tổng quan về hoạt động nhân sự và chấm công</p>
            </div>
            <span class="text-sm text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-3 py-1.5 rounded-lg">
                <i class="far fa-calendar-alt mr-1.5"></i> {{ Carbon\Carbon::now()->format('d/m/Y') }}
            </span>
        </div>

        <!-- ===== THÔNG TIN CƠ BẢN ===== -->
        <div class="grid grid-cols-1 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                    <!-- Avatar -->
                    <div class="flex-shrink-0">
                        @php
                            $user = auth()->user();
                            $hoSo = $user->hoSo;
                            $avatarPath = $hoSo && $hoSo->anh_dai_dien ? 'storage/' . $hoSo->anh_dai_dien : null;
                            $defaultAvatar = 'avatars/default.png';
                            $avatarUrl =
                                $avatarPath && file_exists(public_path($avatarPath))
                                    ? asset($avatarPath)
                                    : asset('storage/' . $defaultAvatar);
                            $initial = $hoSo
                                ? strtoupper(substr($hoSo->ho ?? ($hoSo->ten ?? $user->ten_dang_nhap), 0, 1))
                                : strtoupper(substr($user->ten_dang_nhap, 0, 1));
                        @endphp

                        @if ($avatarPath && file_exists(public_path($avatarPath)))
                            <img src="{{ $avatarUrl }}" alt="Avatar"
                                class="w-24 h-24 rounded-full object-cover border-4 border-blue-100 dark:border-blue-900/30 shadow-md"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                            <div class="w-24 h-24 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white text-3xl font-bold shadow-md"
                                style="display: none;">
                                {{ $initial }}
                            </div>
                        @else
                            <div
                                class="w-24 h-24 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white text-3xl font-bold shadow-md">
                                {{ $initial }}
                            </div>
                        @endif
                    </div>

                    <!-- Thông tin -->
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $hoTen }}</h2>
                            <span
                                class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ session('user.vai_tro') ?? 'Nhân viên' }}
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-3">
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-envelope w-5 text-gray-400 dark:text-gray-500"></i>
                                <span class="ml-2">{{ $email }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-building w-5 text-gray-400 dark:text-gray-500"></i>
                                <span class="ml-2">{{ $phongBan }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-calendar-check w-5 text-gray-400 dark:text-gray-500"></i>
                                <span class="ml-2"><strong
                                        class="text-gray-900 dark:text-white">{{ number_format($soNgayLamViec) }}</strong>
                                    ngày làm việc</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-id-badge w-5 text-gray-400 dark:text-gray-500"></i>
                                <span class="ml-2">Mã NV: <strong
                                        class="text-gray-900 dark:text-white">{{ $hoSo->ma_nhan_vien ?? 'N/A' }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================= -->
        <!-- 4 THẺ THỐNG KÊ -->
        <!-- ============================================= -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Tỷ lệ chấm công -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tỷ lệ chấm công</p>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $tyLeChamCong }}%</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ $ngayChamCong }}/{{ $tongNgayTrongThang }} ngày
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center">
                        <i class="fas fa-clock text-xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $tyLeChamCong }}%"></div>
                    </div>
                    <div class="flex justify-between mt-1">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Còn {{ $soNgayThieu }} ngày</span>
                        <span class="text-xs text-red-500 dark:text-red-400">
                            <i class="fas fa-arrow-down mr-1"></i>
                            {{ $soNgayThieu > 0 ? round(($soNgayThieu / $tongNgayTrongThang) * 100) : 0 }}%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Số ngày đi trễ -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Đi trễ</p>
                        <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">{{ $soNgayDiTre }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tháng này</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-yellow-50 dark:bg-yellow-900/20 flex items-center justify-center">
                        <i class="fas fa-running text-xl text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-center text-sm">
                        <span class="text-gray-600 dark:text-gray-400">So với tháng trước</span>
                        <span class="ml-2 text-green-600 dark:text-green-400">
                            <i class="fas fa-arrow-down mr-1"></i> 0%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Số ngày về sớm -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Về sớm</p>
                        <p class="text-3xl font-bold text-cyan-600 dark:text-cyan-400 mt-1">{{ $soNgayVeSom }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tháng này</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-cyan-50 dark:bg-cyan-900/20 flex items-center justify-center">
                        <i class="fas fa-home text-xl text-cyan-600 dark:text-cyan-400"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-center text-sm">
                        <span class="text-gray-600 dark:text-gray-400">So với tháng trước</span>
                        <span class="ml-2 text-green-600 dark:text-green-400">
                            <i class="fas fa-arrow-down mr-1"></i> 0%
                        </span>
                    </div>
                </div>
            </div>

            
            <!-- Số ngày nghỉ phép -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nghỉ phép</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $soNgayNghiPhep }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tháng này</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-900/20 flex items-center justify-center">
                        <i class="fas fa-umbrella-beach text-xl text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-center text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Số dư khả dụng</span>
                        {{-- Hiển thị số ngày phép động từ Database --}}
                        <span class="ml-2 font-bold text-green-600 dark:text-green-400">
                            {{ $soDuConLai }} ngày
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================= -->
        <!-- BIỂU ĐỒ VÀ RANKING -->
        <!-- ============================================= -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Biểu đồ -->
            <div class="lg:col-span-2">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-chart-bar mr-2 text-blue-600 dark:text-blue-400"></i>
                            Thống kê chấm công 6 tháng
                        </h3>
                        <span
                            class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2.5 py-1 rounded-full">
                            {{ Carbon\Carbon::now()->year }}
                        </span>
                    </div>
                    <div class="p-6">
                        <canvas id="attendanceChart" height="250"></canvas>
                    </div>
                </div>
            </div>

            <!-- Ranking -->
            <div class="lg:col-span-1">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-trophy mr-2 text-yellow-500"></i>
                            Ranking phòng ban
                        </h3>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Năm {{ Carbon\Carbon::now()->year }}</span>
                    </div>
                    <div class="p-6">
                        <div class="text-center mb-4">
                            <div class="text-5xl font-bold text-yellow-500">#{{ $viTri }}</div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Vị trí của bạn</p>
                            <div class="mt-3">
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                    <div class="bg-green-500 h-2.5 rounded-full transition-all"
                                        style="width: {{ $tyLeTrenPhong }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5">
                                    Top {{ $tyLeTrenPhong }}% trong {{ $tongNhanVien }} nhân viên
                                </p>
                            </div>
                        </div>

                        <div class="space-y-2 mt-4">
                            @foreach ($topEmployees as $index => $emp)
                                <div
                                    class="flex items-center justify-between p-2 rounded-lg {{ $index == 0 ? 'bg-yellow-50 dark:bg-yellow-900/10' : ($index == 1 ? 'bg-gray-50 dark:bg-gray-700/30' : ($index == 2 ? 'bg-orange-50 dark:bg-orange-900/10' : '')) }}">
                                    <div class="flex items-center space-x-3">
                                        <span
                                            class="w-6 h-6 flex items-center justify-center rounded-full text-xs font-bold {{ $index == 0 ? 'bg-yellow-500 text-white' : ($index == 1 ? 'bg-gray-400 text-white' : ($index == 2 ? 'bg-orange-400 text-white' : 'bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300')) }}">
                                            {{ $index + 1 }}
                                        </span>
                                        <span
                                            class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $emp['ho_ten'] }}</span>
                                    </div>
                                    <span
                                        class="text-xs font-semibold text-gray-600 dark:text-gray-400">{{ $emp['tong_cham_cong'] }}
                                        ngày</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================= -->
        <!-- THỐNG KÊ CẢ NĂM -->
        <!-- ============================================= -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900 dark:text-white">
                    <i class="fas fa-calendar-alt mr-2 text-blue-600 dark:text-blue-400"></i>
                    Thống kê cả năm {{ Carbon\Carbon::now()->year }}
                </h3>
                <span
                    class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2.5 py-1 rounded-full">
                    Cập nhật mới nhất
                </span>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 rounded-xl bg-blue-50 dark:bg-blue-900/10">
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                            {{ number_format($tongNgayChamCongNam) }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Tổng ngày chấm công</p>
                    </div>
                    <div class="text-center p-4 rounded-xl bg-yellow-50 dark:bg-yellow-900/10">
                        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                            {{ number_format($tongDiTreNam) }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Đi trễ</p>
                    </div>
                    <div class="text-center p-4 rounded-xl bg-cyan-50 dark:bg-cyan-900/10">
                        <p class="text-2xl font-bold text-cyan-600 dark:text-cyan-400">{{ number_format($tongVeSomNam) }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Về sớm</p>
                    </div>
                    <div class="text-center p-4 rounded-xl bg-green-50 dark:bg-green-900/10">
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                            {{ number_format($tongNghiPhepNam) }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Nghỉ phép</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('attendanceChart').getContext('2d');

            // Gradient
            const gradient = ctx.createLinearGradient(0, 0, 0, 250);
            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.8)');
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0.1)');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Số ngày chấm công',
                        data: @json($chartData),
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(59, 130, 246, 0.6)',
                            'rgba(59, 130, 246, 0.5)',
                            'rgba(59, 130, 246, 0.6)',
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(59, 130, 246, 0.8)'
                        ],
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleColor: '#fff',
                            bodyColor: '#e5e7eb',
                            cornerRadius: 8,
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' ngày';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.05)',
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
