@extends('layouts.admin')

@section('title', 'Báo cáo yêu cầu điều chỉnh công')

@section('content')

<div class="space-y-6 bg-gray-50 dark:bg-[#0f172a] min-h-screen p-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800/80 backdrop-blur border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    Báo cáo yêu cầu điều chỉnh công
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    Thống kê và phân tích yêu cầu điều chỉnh công
                </p>
            </div>
            <div>
                <a href="{{ route('admin.yeu-cau-dieu-chinh-cong.index') }}" 
                    class="px-5 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    {{-- BỘ LỌC --}}
    <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-5">
        <form method="GET" action="{{ route('admin.yeu-cau-dieu-chinh-cong.bao-cao') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Từ ngày</label>
                    <input type="date" name="tu_ngay" value="{{ $tuNgay }}" 
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Đến ngày</label>
                    <input type="date" name="den_ngay" value="{{ $denNgay }}" 
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Lọc dữ liệu
                    </button>
                    <a href="{{ route('admin.yeu-cau-dieu-chinh-cong.export-bao-cao', ['tu_ngay' => $tuNgay, 'den_ngay' => $denNgay]) }}" 
                        class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export Excel
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- THỐNG KÊ TỔNG QUAN --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tổng yêu cầu</p>
                    <h3 class="text-3xl font-bold text-blue-600 mt-2">{{ array_sum(array_values($thongKeTheoTrangThai ?? [])) }}</h3>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Chờ duyệt</p>
                    <h3 class="text-3xl font-bold text-yellow-500 mt-2">{{ $thongKeTheoTrangThai['cho_duyet'] ?? 0 }}</h3>
                </div>
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Đã duyệt</p>
                    <h3 class="text-3xl font-bold text-green-600 mt-2">{{ $thongKeTheoTrangThai['da_duyet'] ?? 0 }}</h3>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Từ chối</p>
                    <h3 class="text-3xl font-bold text-red-600 mt-2">{{ $thongKeTheoTrangThai['tu_choi'] ?? 0 }}</h3>
                </div>
                <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- BIỂU ĐỒ VÀ THỐNG KÊ PHÒNG BAN --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Biểu đồ theo tháng --}}
        <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-5">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Thống kê theo tháng</h3>
            <canvas id="monthlyChart" height="250"></canvas>
        </div>

        {{-- Thống kê theo phòng ban --}}
        <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-5">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Thống kê theo phòng ban</h3>
            @if(($thongKeTheoPhongBan ?? collect())->isEmpty())
                <p class="text-gray-400 text-center py-10">Không có dữ liệu</p>
            @else
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @foreach($thongKeTheoPhongBan as $phongBan => $thongKe)
                        @php
                            $total = $thongKe['tong_so'];
                            $daDuyetPercent = $total > 0 ? ($thongKe['da_duyet'] / $total) * 100 : 0;
                            $choDuyetPercent = $total > 0 ? ($thongKe['cho_duyet'] / $total) * 100 : 0;
                            $tuChoiPercent = $total > 0 ? ($thongKe['tu_choi'] / $total) * 100 : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $phongBan ?: 'Chưa phân loại' }}</span>
                                <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 rounded-full text-sm">{{ $total }}</span>
                            </div>
                            <div class="flex h-2 rounded-full overflow-hidden">
                                @if($daDuyetPercent > 0)
                                    <div class="bg-green-500" style="width: {{ $daDuyetPercent }}%"></div>
                                @endif
                                @if($choDuyetPercent > 0)
                                    <div class="bg-yellow-500" style="width: {{ $choDuyetPercent }}%"></div>
                                @endif
                                @if($tuChoiPercent > 0)
                                    <div class="bg-red-500" style="width: {{ $tuChoiPercent }}%"></div>
                                @endif
                            </div>
                            <div class="flex gap-3 mt-1 text-xs">
                                <span class="text-green-600">✓ {{ $thongKe['da_duyet'] }}</span>
                                <span class="text-yellow-500">⏳ {{ $thongKe['cho_duyet'] }}</span>
                                <span class="text-red-600">✗ {{ $thongKe['tu_choi'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- TOP NHÂN VIÊN --}}
    <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">🏆 Top 10 nhân viên có nhiều yêu cầu nhất</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr class="text-left text-sm text-gray-700 dark:text-gray-300">
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">Họ tên</th>
                        <th class="px-6 py-3">Mã NV</th>
                        <th class="px-6 py-3">Phòng ban</th>
                        <th class="px-6 py-3">Số yêu cầu</th>
                        <th class="px-6 py-3">Xếp hạng</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topNhanVien ?? [] as $index => $nhanVien)
                        <tr class="border-t border-gray-200 dark:border-gray-700">
                            <td class="px-6 py-3">{{ $index + 1 }}</td>
                            <td class="px-6 py-3 font-medium">{{ $nhanVien['ho_ten'] }}</td>
                            <td class="px-6 py-3">{{ $nhanVien['ma_nhan_vien'] }}</td>
                            <td class="px-6 py-3">{{ $nhanVien['phong_ban'] }}</td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-sm">{{ $nhanVien['so_luong'] }}</span>
                            </td>
                            <td class="px-6 py-3">
                                @if($index == 0) 🥇
                                @elseif($index == 1) 🥈
                                @elseif($index == 2) 🥉
                                @else {{ $index + 1 }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400">Không có dữ liệu</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- BIỂU ĐỒ TRÒN VÀ CHI TIẾT TRẠNG THÁI --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Biểu đồ tròn --}}
        <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-5">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Phân bố theo trạng thái</h3>
            <canvas id="statusChart" height="250"></canvas>
        </div>

        {{-- Chi tiết trạng thái --}}
        <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-5">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Chi tiết theo trạng thái</h3>
            @php
                $tongSo = array_sum(array_values($thongKeTheoTrangThai ?? []));
            @endphp
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                        <span class="font-medium">Chờ duyệt</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xl font-bold text-yellow-600">{{ $thongKeTheoTrangThai['cho_duyet'] ?? 0 }}</span>
                        <span class="text-sm text-gray-500 ml-2">
                            ({{ $tongSo > 0 ? number_format(($thongKeTheoTrangThai['cho_duyet'] ?? 0) / $tongSo * 100, 1) : 0 }}%)
                        </span>
                    </div>
                </div>
                <div class="flex justify-between items-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                        <span class="font-medium">Đã duyệt</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xl font-bold text-green-600">{{ $thongKeTheoTrangThai['da_duyet'] ?? 0 }}</span>
                        <span class="text-sm text-gray-500 ml-2">
                            ({{ $tongSo > 0 ? number_format(($thongKeTheoTrangThai['da_duyet'] ?? 0) / $tongSo * 100, 1) : 0 }}%)
                        </span>
                    </div>
                </div>
                <div class="flex justify-between items-center p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                        <span class="font-medium">Từ chối</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xl font-bold text-red-600">{{ $thongKeTheoTrangThai['tu_choi'] ?? 0 }}</span>
                        <span class="text-sm text-gray-500 ml-2">
                            ({{ $tongSo > 0 ? number_format(($thongKeTheoTrangThai['tu_choi'] ?? 0) / $tongSo * 100, 1) : 0 }}%)
                        </span>
                    </div>
                </div>
                <div class="flex justify-between items-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg mt-4 border-t-2 border-gray-200 dark:border-gray-700 pt-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                        <span class="font-bold">Tổng cộng</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xl font-bold text-blue-600">{{ $tongSo }}</span>
                        <span class="text-sm text-gray-500 ml-2">(100%)</span>
                    </div>
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
    // Biểu đồ theo tháng
    const thongKeTheoThang = @json($thongKeTheoThang ?? []);
    const labels = Object.keys(thongKeTheoThang).sort();
    const formattedLabels = labels.map(label => {
        const [year, month] = label.split('-');
        return `${month}/${year}`;
    });

    if (labels.length > 0) {
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: formattedLabels,
                datasets: [
                    { label: 'Đã duyệt', data: labels.map(l => thongKeTheoThang[l]['da_duyet'] || 0), backgroundColor: 'rgba(34, 197, 94, 0.7)', borderColor: 'rgb(34, 197, 94)', borderWidth: 1 },
                    { label: 'Chờ duyệt', data: labels.map(l => thongKeTheoThang[l]['cho_duyet'] || 0), backgroundColor: 'rgba(234, 179, 8, 0.7)', borderColor: 'rgb(234, 179, 8)', borderWidth: 1 },
                    { label: 'Từ chối', data: labels.map(l => thongKeTheoThang[l]['tu_choi'] || 0), backgroundColor: 'rgba(239, 68, 68, 0.7)', borderColor: 'rgb(239, 68, 68)', borderWidth: 1 }
                ]
            },
            options: { responsive: true, maintainAspectRatio: true, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        });
    }

    // Biểu đồ tròn
    const statusData = @json($thongKeTheoTrangThai ?? []);
    if (Object.values(statusData).some(v => v > 0)) {
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Chờ duyệt', 'Đã duyệt', 'Từ chối'],
                datasets: [{
                    data: [statusData['cho_duyet'] || 0, statusData['da_duyet'] || 0, statusData['tu_choi'] || 0],
                    backgroundColor: ['rgb(234, 179, 8)', 'rgb(34, 197, 94)', 'rgb(239, 68, 68)'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '60%',
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return `${context.label}: ${context.parsed} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush