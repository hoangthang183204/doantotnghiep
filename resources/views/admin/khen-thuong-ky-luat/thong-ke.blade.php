@extends('layouts.admin')

@section('title', 'Thống kê khen thưởng / kỷ luật')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                    Thống kê khen thưởng / kỷ luật
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    Thống kê theo tháng / năm / phòng ban
                </p>
            </div>
        </div>

        <div class="mt-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-6 gap-4">
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1">Tháng</label>
                    <select name="thang" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tất cả</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" @selected(request('thang') == $i)>Tháng {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1">Năm</label>
                    <select name="nam" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tất cả</option>
                        @for ($i = 2020; $i <= date('Y'); $i++)
                            <option value="{{ $i }}" @selected(request('nam') == $i)>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1">Phòng ban</label>
                    <select name="phong_ban" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tất cả</option>
                        @foreach ($phongBans as $pb)
                            <option value="{{ $pb->id }}" @selected(request('phong_ban') == $pb->id)>{{ $pb->ten_phong_ban }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="xl:col-span-3 flex items-end gap-3">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition shadow-sm">
                        <i class="fa-solid fa-filter mr-1"></i> Lọc dữ liệu
                    </button>
                    <a href="{{ url()->current() }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition">
                        <i class="fa-solid fa-rotate mr-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- KPI --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="text-xs text-gray-500 dark:text-gray-400">Tổng quyết định</div>
            <div class="text-2xl font-bold text-blue-600">{{ $tongQuyetDinh }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="text-xs text-gray-500 dark:text-gray-400">Khen thưởng</div>
            <div class="text-2xl font-bold text-green-600">{{ $tongKhenThuong }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="text-xs text-gray-500 dark:text-gray-400">Kỷ luật</div>
            <div class="text-2xl font-bold text-red-600">{{ $tongKyLuat }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="text-xs text-gray-500 dark:text-gray-400">Tổng tiền</div>
            <div class="text-xl font-bold text-yellow-600">{{ number_format($tongTienThuong) }} đ</div>
        </div>
    </div>

    {{-- CHART THÁNG --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <h2 class="font-medium text-gray-700 dark:text-white mb-3">Thống kê theo tháng</h2>
        <div class="h-[260px]"><canvas id="chartThang"></canvas></div>
    </div>

    {{-- CHART PHÒNG BAN --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <h2 class="font-medium text-gray-700 dark:text-white mb-3">Thống kê theo phòng ban</h2>
        <div class="h-[260px]"><canvas id="chartPhongBan"></canvas></div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function getIsDark() { return document.documentElement.classList.contains('dark'); }
    function getChartOptions() {
        const isDark = getIsDark();
        const gridColor = isDark ? '#334155' : '#e5e7eb';
        const textColor = isDark ? '#cbd5e1' : '#111827';
        return {
            responsive: true, maintainAspectRatio: false,
            scales: {
                x: { ticks: { color: textColor }, grid: { color: gridColor } },
                y: { ticks: { color: textColor }, grid: { color: gridColor }, beginAtZero: true }
            },
            plugins: { legend: { labels: { color: textColor } } }
        };
    }

    const chartThang = new Chart(document.getElementById('chartThang'), {
        type: 'bar',
        data: {
            labels: @json($chartTheoThang->pluck('thang')),
            datasets: [{
                label: 'Số lượng',
                data: @json($chartTheoThang->pluck('tong')),
                borderWidth: 1
            }]
        },
        options: getChartOptions()
    });

    const chartPhongBan = new Chart(document.getElementById('chartPhongBan'), {
        type: 'doughnut',
        data: {
            labels: @json($chartPhongBan->pluck('ten')),
            datasets: [{ data: @json($chartPhongBan->pluck('tong')) }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { labels: { color: () => getIsDark() ? '#cbd5e1' : '#111827' } } }
        }
    });

    const observer = new MutationObserver(() => {
        chartThang.options = getChartOptions(); chartThang.update(); chartPhongBan.update();
    });
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
</script>
@endsection