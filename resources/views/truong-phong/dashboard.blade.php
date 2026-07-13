{{-- resources/views/truong-phong/dashboard.blade.php --}}

@extends('layouts.admin')

@section('title', 'Dashboard Trưởng phòng')

@section('content')
    <div class="space-y-6">

        {{-- ============================================= --}}
        {{-- 1️⃣ HEADER: THÔNG TIN PHÒNG BAN --}}
        {{-- ============================================= --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 md:p-6 shadow-sm">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-building text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ $phongBan->ten_phong_ban ?? 'Phòng ban của bạn' }}</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Mã: {{ $phongBan->ma_phong_ban ?? 'N/A' }}
                            <span class="mx-2">|</span>
                            Trưởng phòng: {{ Auth::user()->hoSo->ho ?? '' }}
                            {{ Auth::user()->hoSo->ten ?? Auth::user()->ten_dang_nhap }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $soNhanVien }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Nhân viên</p>
                    </div>
                    <div class="text-center border-l border-gray-200 dark:border-gray-700 pl-6">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ date('d/m/Y') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Hôm nay</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================= --}}
        {{-- 2️⃣ THỐNG KÊ NHANH - 4 CARDS ĐƠN GIẢN --}}
        {{-- ============================================= --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Đã chấm công</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $daChamCong }}</p>
                        <p class="text-xs text-gray-400">Hôm nay</p>
                    </div>
                    <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-gray-600 dark:text-gray-400"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                        <div class="bg-blue-600 dark:bg-blue-500 h-1.5 rounded-full"
                            style="width: {{ $soNhanVien > 0 ? round(($daChamCong / $soNhanVien) * 100) : 0 }}%"></div>
                    </div>
                    <div class="flex justify-between mt-0.5">
                        <span
                            class="text-xs text-gray-500 dark:text-gray-400">{{ $daChamCong }}/{{ $soNhanVien }}</span>
                        <span
                            class="text-xs text-gray-600 dark:text-gray-400">{{ $soNhanVien > 0 ? round(($daChamCong / $soNhanVien) * 100) : 0 }}%</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Chưa chấm công</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $chuaChamCong }}</p>
                        <p class="text-xs text-gray-400">Hôm nay</p>
                    </div>
                    <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-gray-600 dark:text-gray-400"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                        <div class="bg-gray-600 dark:bg-gray-500 h-1.5 rounded-full"
                            style="width: {{ $soNhanVien > 0 ? round(($chuaChamCong / $soNhanVien) * 100) : 0 }}%"></div>
                    </div>
                    <div class="flex justify-between mt-0.5">
                        <span
                            class="text-xs text-gray-500 dark:text-gray-400">{{ $chuaChamCong }}/{{ $soNhanVien }}</span>
                        <span
                            class="text-xs text-gray-600 dark:text-gray-400">{{ $soNhanVien > 0 ? round(($chuaChamCong / $soNhanVien) * 100) : 0 }}%</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Đơn nghỉ phép</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $donChoDuyet['nghi_phep'] }}
                        </p>
                        <p class="text-xs text-gray-400">Chờ duyệt</p>
                    </div>
                    <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-gray-600 dark:text-gray-400"></i>
                    </div>
                </div>
                <div class="mt-2">
                    @if ($donChoDuyet['nghi_phep'] > 0)
                        <p class="text-sm text-amber-600 dark:text-amber-400">⚠️ {{ $donChoDuyet['nghi_phep'] }} đơn chờ
                        </p>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">✅ Không có đơn</p>
                    @endif
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tăng ca</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $donChoDuyet['tang_ca'] }}</p>
                        <p class="text-xs text-gray-400">Chờ duyệt</p>
                    </div>
                    <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <i class="fas fa-hourglass-half text-gray-600 dark:text-gray-400"></i>
                    </div>
                </div>
                <div class="mt-2">
                    @if ($donChoDuyet['tang_ca'] > 0)
                        <p class="text-sm text-amber-600 dark:text-amber-400">⚠️ {{ $donChoDuyet['tang_ca'] }} đơn chờ</p>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">✅ Không có đơn</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- ============================================= --}}
        {{-- 3️⃣ BIỂU ĐỒ + TOP NHÂN VIÊN --}}
        {{-- ============================================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            {{-- Biểu đồ --}}
            <div
                class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                    <h5 class="font-medium text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-chart-bar text-blue-600 dark:text-blue-400"></i>
                        Thống kê chấm công theo tháng
                    </h5>
                </div>
                <div class="p-4">
                    <canvas id="attendanceChart" height="250"></canvas>
                </div>
            </div>

            {{-- Top nhân viên --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                    <h5 class="font-medium text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-trophy text-amber-600 dark:text-amber-400"></i>
                        Top chấm công
                    </h5>
                </div>
                <div class="p-3">
                    @forelse($topNhanVien as $index => $nv)
                        <div
                            class="flex items-center gap-3 py-2 {{ !$loop->last ? 'border-b border-gray-100 dark:border-gray-700/50' : '' }}">
                            <div class="flex-shrink-0 w-6 text-center">
                                @if ($index == 0)
                                    <span class="text-lg">🥇</span>
                                @elseif($index == 1)
                                    <span class="text-lg">🥈</span>
                                @elseif($index == 2)
                                    <span class="text-lg">🥉</span>
                                @else
                                    <span class="text-xs text-gray-400">{{ $index + 1 }}</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 dark:text-white truncate">{{ $nv['ho_ten'] }}
                                </p>
                                <p class="text-xs text-gray-400">Mã: {{ $nv['ma_nhan_vien'] ?? 'N/A' }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span
                                    class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full text-gray-600 dark:text-gray-400">
                                    {{ $nv['tong_cham_cong'] }} ngày
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-gray-400">
                            <i class="fas fa-inbox text-2xl mb-2 block"></i>
                            <p class="text-sm">Chưa có dữ liệu</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ============================================= --}}
        {{-- 4️⃣ DANH SÁCH NHÂN VIÊN TRONG PHÒNG --}}
        {{-- ============================================= --}}
        {{-- ============================================= --}}
        {{-- 4️⃣ DANH SÁCH NHÂN VIÊN TRONG PHÒNG --}}
        {{-- ============================================= --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div
                class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between flex-wrap gap-2">
                <h5 class="font-medium text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fas fa-users text-blue-600 dark:text-blue-400"></i>
                    Danh sách nhân viên
                </h5>
                <span class="text-xs bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full text-gray-600 dark:text-gray-400">
                    {{ $nhanViens->total() }} nhân viên
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                STT</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Mã NV</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Họ tên</th>
                            <th
                                class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden sm:table-cell">
                                Chức vụ</th>
                            <th
                                class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden md:table-cell">
                                Email</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Trạng thái</th>
                            <th
                                class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @forelse($nhanViens as $index => $nv)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30">
                                <td class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $nhanViens->firstItem() + $index }}</td>
                                <td class="px-3 py-2 text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $nv->hoSo->ma_nhan_vien ?? 'N/A' }}
                                </td>
                                <td class="px-3 py-2">
                                    <div class="flex items-center gap-2">
                                        @if ($nv->hoSo && $nv->hoSo->anh_dai_dien)
                                            <img src="{{ asset('storage/' . $nv->hoSo->anh_dai_dien) }}"
                                                class="w-7 h-7 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                                        @else
                                            <div
                                                class="w-7 h-7 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-gray-700 dark:text-gray-300 text-xs font-bold flex-shrink-0">
                                                {{ substr($nv->hoSo->ten ?? $nv->ten_dang_nhap, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-gray-800 dark:text-white truncate">
                                                {{ $nv->hoSo->ho ?? '' }} {{ $nv->hoSo->ten ?? $nv->ten_dang_nhap }}
                                            </p>
                                            <p class="text-xs text-gray-400 truncate">{{ $nv->ten_dang_nhap }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hidden sm:table-cell">
                                    {{ $nv->chucVu->ten ?? 'Chưa có' }}
                                </td>
                                <td class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell">
                                    {{ $nv->email }}
                                </td>
                                <td class="px-3 py-2">
                                    @if ($nv->trang_thai == 1)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>
                                            Đang làm
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                            <span class="w-1.5 h-1.5 bg-gray-500 rounded-full mr-1"></span>
                                            Đã nghỉ
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 text-right">
                                    {{-- ⭐ SỬA: Thêm link xem chi tiết --}}
                                    <a href="{{ route('truong-phong.nhan-vien.show', $nv->id) }}"
                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors p-1"
                                        title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-users-slash text-2xl mb-2 block"></i>
                                    <p>Chưa có nhân viên nào trong phòng</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($nhanViens->hasPages())
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    {{ $nhanViens->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- 🟢 SCRIPT CHO BIỂU ĐỒ --}}
    {{-- ============================================= --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('attendanceChart').getContext('2d');

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [{
                            label: 'Số ngày chấm công',
                            data: @json($chartAttendance),
                            backgroundColor: 'rgba(59, 130, 246, 0.6)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1.5,
                            borderRadius: 4,
                            barPercentage: 0.6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false,
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0,0,0,0.8)',
                                titleColor: '#fff',
                                bodyColor: '#e5e7eb',
                                padding: 8,
                                cornerRadius: 6,
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
                                grid: {
                                    color: 'rgba(0,0,0,0.06)',
                                    drawBorder: false,
                                },
                                ticks: {
                                    stepSize: 1,
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
