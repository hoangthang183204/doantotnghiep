{{-- resources/views/truong-phong/bao-cao/overview.blade.php --}}

@extends('layouts.admin')

@section('title', 'Báo cáo tổng quan')

@section('content')
<div class="space-y-6">
    
    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-chart-pie mr-3 text-blue-600"></i>
                Báo cáo tổng quan
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Phòng <span class="font-medium text-blue-600">{{ $phongBan->ten_phong_ban }}</span>
                - Tháng {{ $thang }}/{{ $nam }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-sm bg-gray-100 dark:bg-gray-700 px-3 py-1.5 rounded-lg text-gray-600 dark:text-gray-300">
                <i class="fas fa-users mr-1"></i> {{ $tongNhanVien }} nhân viên
            </span>
        </div>
    </div>

    {{-- Bộ lọc --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div>
                <select name="thang" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $thang == $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <select name="nam" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    @for($i = date('Y') - 3; $i <= date('Y'); $i++)
                        <option value="{{ $i }}" {{ $nam == $i ? 'selected' : '' }}>Năm {{ $i }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                <i class="fas fa-search mr-1"></i> Xem báo cáo
            </button>
            <a href="{{ route('truong-phong.bao-cao.overview') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition">
                <i class="fas fa-redo mr-1"></i> Reset
            </a>
        </form>
    </div>

    {{-- Thống kê nhân sự --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Tổng nhân viên</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $tongNhanVien }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-blue-200 dark:border-blue-700/50 p-4 shadow-sm">
            <p class="text-sm text-blue-600 dark:text-blue-400">Nam</p>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $nhanVienNam }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-pink-200 dark:border-pink-700/50 p-4 shadow-sm">
            <p class="text-sm text-pink-600 dark:text-pink-400">Nữ</p>
            <p class="text-2xl font-bold text-pink-600 dark:text-pink-400">{{ $nhanVienNu }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-green-200 dark:border-green-700/50 p-4 shadow-sm">
            <p class="text-sm text-green-600 dark:text-green-400">Tỷ lệ chấm công</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $tyLeChamCong }}%</p>
        </div>
    </div>

    {{-- Biểu đồ chấm công --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-gray-900 dark:text-white">
                <i class="fas fa-chart-bar mr-2 text-blue-600"></i>
                Thống kê chấm công theo tháng
            </h3>
        </div>
        <div class="p-6">
            <canvas id="attendanceChart" height="250"></canvas>
        </div>
    </div>

    {{-- Thống kê chi tiết --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-yellow-200 dark:border-yellow-700/50 p-4 shadow-sm">
            <p class="text-sm text-yellow-600 dark:text-yellow-400">Đi muộn</p>
            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $soNgayDiMuon }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-orange-200 dark:border-orange-700/50 p-4 shadow-sm">
            <p class="text-sm text-orange-600 dark:text-orange-400">Về sớm</p>
            <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $soNgayVeSom }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-blue-200 dark:border-blue-700/50 p-4 shadow-sm">
            <p class="text-sm text-blue-600 dark:text-blue-400">Tăng ca</p>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $soNgayTangCa }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Tổng ngày công</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $soNgayLam }}</p>
        </div>
    </div>

    {{-- Biểu đồ đơn nghỉ phép --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-gray-900 dark:text-white">
                <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                Thống kê đơn nghỉ phép theo tháng
            </h3>
        </div>
        <div class="p-6">
            <canvas id="leaveChart" height="200"></canvas>
        </div>
    </div>

    {{-- Thống kê đơn từ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Đơn nghỉ phép</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $tongDonNghi }}</p>
            <p class="text-xs text-gray-400">Trong tháng</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-yellow-200 dark:border-yellow-700/50 p-4 shadow-sm">
            <p class="text-sm text-yellow-600 dark:text-yellow-400">Chờ duyệt</p>
            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $donNghiChoDuyet }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-green-200 dark:border-green-700/50 p-4 shadow-sm">
            <p class="text-sm text-green-600 dark:text-green-400">Đã duyệt</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $donNghiDaDuyet }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-red-200 dark:border-red-700/50 p-4 shadow-sm">
            <p class="text-sm text-red-600 dark:text-red-400">Từ chối</p>
            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $donNghiTuChoi }}</p>
        </div>
    </div>

    {{-- Thống kê tăng ca và chỉnh công --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-3">
                <i class="fas fa-clock mr-2 text-blue-600"></i> Tăng ca
            </h4>
            <div class="grid grid-cols-3 gap-2">
                <div>
                    <p class="text-sm text-gray-500">Tổng</p>
                    <p class="text-xl font-bold">{{ $tongTangCa }}</p>
                </div>
                <div>
                    <p class="text-sm text-yellow-600">Chờ duyệt</p>
                    <p class="text-xl font-bold text-yellow-600">{{ $tangCaChoDuyet }}</p>
                </div>
                <div>
                    <p class="text-sm text-green-600">Đã duyệt</p>
                    <p class="text-xl font-bold text-green-600">{{ $tangCaDaDuyet }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-3">
                <i class="fas fa-edit mr-2 text-blue-600"></i> Chỉnh công
            </h4>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <p class="text-sm text-gray-500">Tổng</p>
                    <p class="text-xl font-bold">{{ $tongChinhCong }}</p>
                </div>
                <div>
                    <p class="text-sm text-yellow-600">Chờ duyệt</p>
                    <p class="text-xl font-bold text-yellow-600">{{ $chinhCongChoDuyet }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Biểu đồ chấm công
    const ctx1 = document.getElementById('attendanceChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Số ngày chấm công',
                data: @json($chartData),
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2,
                borderRadius: 6,
                barPercentage: 0.6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: '#fff',
                    bodyColor: '#e5e7eb',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' ngày';
                        }
                    }
                }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            }
        }
    });

    // Biểu đồ đơn nghỉ phép
    const ctx2 = document.getElementById('leaveChart').getContext('2d');
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: @json($leaveChartLabels),
            datasets: [{
                label: 'Số đơn nghỉ phép',
                data: @json($leaveChartData),
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                borderColor: 'rgba(239, 68, 68, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(239, 68, 68, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                fill: true,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: '#fff',
                    bodyColor: '#e5e7eb',
                    padding: 10,
                    cornerRadius: 8
                }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>
@endpush
@endsection