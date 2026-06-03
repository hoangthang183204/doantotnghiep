@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tổng quan hệ thống</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Xem nhanh các chỉ số chính của HR Flow</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Employees -->
        <div class="card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Tổng nhân viên</dt>
                    <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalNhanVien ?? 5 }}</dd>
                </div>
            </div>
        </div>

        <!-- Present Today -->
        <div class="card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Có mặt hôm nay</dt>
                    <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $homNayCoMat ?? 0 }}</dd>
                </div>
            </div>
        </div>

        <!-- On Leave -->
        <div class="card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Đang nghỉ phép</dt>
                    <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $dangNghiPhep ?? 0 }}</dd>
                </div>
            </div>
        </div>

        <!-- Late Today -->
        <div class="card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-full">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Đi muộn hôm nay</dt>
                    <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $diMuonHomNay ?? 0 }}</dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Attendance Chart -->
        <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Thống kê chấm công tháng {{ date('m/Y') }}</h3>
            <canvas id="attendanceChart" height="300"></canvas>
        </div>

        <!-- Recent Leave Requests -->
        <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Đơn xin nghỉ gần đây</h3>
            <div class="space-y-4">
                @forelse($donXinNghis ?? [] as $don)
                <div class="border-b dark:border-gray-700 pb-3 last:border-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $don->nguoi_dung->ho_so->ten ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($don->ngay_bat_dau)->format('d/m/Y') }} → {{ \Carbon\Carbon::parse($don->ngay_ket_thuc)->format('d/m/Y') }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($don->trang_thai == 'cho_duyet') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                            @elseif($don->trang_thai == 'da_duyet') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                            @elseif($don->trang_thai == 'tu_choi') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                            @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400
                            @endif">
                            @if($don->trang_thai == 'cho_duyet')
                                Chờ duyệt
                            @elseif($don->trang_thai == 'da_duyet')
                                Đã duyệt
                            @elseif($don->trang_thai == 'tu_choi')
                                Từ chối
                            @else
                                {{ $don->trang_thai }}
                            @endif
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400 text-center py-4">Không có đơn xin nghỉ nào</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('attendanceChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Tuần 1', 'Tuần 2', 'Tuần 3', 'Tuần 4'],
                datasets: [{
                    label: 'Số ngày công',
                    data: [22, 21, 23, 20],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.3
                }, {
                    label: 'Số giờ tăng ca',
                    data: [5, 8, 6, 10],
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#fff' : '#000'
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280' }
                    },
                    x: {
                        ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280' }
                    }
                }
            }
        });
    }
</script>
@endpush