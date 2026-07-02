@extends('layouts.admin')

@section('title', 'Thống kê khen thưởng / kỷ luật')

@section('content')

    <div class="min-h-screen p-6 bg-gray-50 dark:bg-slate-900">

        <div class="max-w-7xl mx-auto space-y-6">

            {{-- HEADER --}}
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Thống kê khen thưởng / kỷ luật
                </h1>
                <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">
                    Thống kê theo tháng / năm / phòng ban
                </p>
            </div>

            {{-- FILTER --}}
            <form method="GET"
                class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl p-4 shadow-sm">

                <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-6 gap-4">

                    {{-- Tháng --}}
                    <div>
                        <label class="text-xs text-gray-500 dark:text-slate-400">Tháng</label>
                        <select name="thang"
                            class="w-full mt-1 text-base py-2.5 px-3
                       rounded-lg border border-gray-300 dark:border-slate-700
                       bg-white dark:bg-slate-900 dark:text-white
                       focus:ring-2 focus:ring-blue-500
                       min-h-[44px]">

                            <option value="">Tất cả</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" @selected(request('thang') == $i)>
                                    Tháng {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- Năm --}}
                    <div>
                        <label class="text-xs text-gray-500 dark:text-slate-400">Năm</label>
                        <select name="nam"
                            class="w-full mt-1 text-base py-2.5 px-3
                       rounded-lg border border-gray-300 dark:border-slate-700
                       bg-white dark:bg-slate-900 dark:text-white
                       focus:ring-2 focus:ring-blue-500
                       min-h-[44px]">

                            <option value="">Tất cả</option>
                            @for ($i = 2020; $i <= date('Y'); $i++)
                                <option value="{{ $i }}" @selected(request('nam') == $i)>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- Phòng ban --}}
                    <div>
                        <label class="text-xs text-gray-500 dark:text-slate-400">Phòng ban</label>
                        <select name="phong_ban"
                            class="w-full mt-1 text-base py-2.5 px-3
                       rounded-lg border border-gray-300 dark:border-slate-700
                       bg-white dark:bg-slate-900 dark:text-white
                       focus:ring-2 focus:ring-blue-500
                       min-h-[44px]">

                            <option value="">Tất cả</option>
                            @foreach ($phongBans as $pb)
                                <option value="{{ $pb->id }}" @selected(request('phong_ban') == $pb->id)>
                                    {{ $pb->ten_phong_ban }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="xl:col-span-3 flex items-end gap-3">

                        {{-- Lọc --}}
                        <button type="submit"
                            class="flex items-center gap-2 px-4 py-2 rounded-lg
                       bg-blue-600 hover:bg-blue-700 text-white
                       transition shadow-sm">

                            <i class="fa-solid fa-filter"></i>
                            Lọc dữ liệu
                        </button>


                        <a href="{{ url()->current() }}"
                            class="flex items-center gap-2 px-4 py-2 rounded-lg
                            bg-gray-100 hover:bg-gray-200
                            dark:bg-slate-700 dark:hover:bg-slate-600
                            text-gray-800 dark:text-white
                            transition shadow-sm">

                            <i class="fa-solid fa-rotate"></i>
                            Reset bộ lọc
                        </a>

                    </div>

                </div>

            </form>

            {{-- KPI --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div class="p-5 bg-white dark:bg-slate-800 rounded-xl border">
                    <div class="text-gray-500">Tổng quyết định</div>
                    <div class="text-3xl font-bold text-blue-600">{{ $tongQuyetDinh }}</div>
                </div>

                <div class="p-5 bg-white dark:bg-slate-800 rounded-xl border">
                    <div class="text-gray-500">Khen thưởng</div>
                    <div class="text-3xl font-bold text-green-600">{{ $tongKhenThuong }}</div>
                </div>

                <div class="p-5 bg-white dark:bg-slate-800 rounded-xl border">
                    <div class="text-gray-500">Kỷ luật</div>
                    <div class="text-3xl font-bold text-red-600">{{ $tongKyLuat }}</div>
                </div>

                <div class="p-5 bg-white dark:bg-slate-800 rounded-xl border">
                    <div class="text-gray-500">Tổng tiền</div>
                    <div class="text-2xl font-bold text-yellow-500">
                        {{ number_format($tongTienThuong) }} đ
                    </div>
                </div>

            </div>

            {{-- CHART THÁNG --}}
            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border">
                <h2 class="font-semibold mb-3 text-gray-700 dark:text-white">
                    Thống kê theo tháng
                </h2>

                <div class="h-[260px]">
                    <canvas id="chartThang"></canvas>
                </div>
            </div>

            {{-- CHART PHÒNG BAN --}}
            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border">
                <h2 class="font-semibold mb-3 text-gray-700 dark:text-white">
                    Thống kê theo phòng ban
                </h2>

                <div class="h-[260px]">
                    <canvas id="chartPhongBan"></canvas>
                </div>
            </div>

        </div>

    </div>

    {{-- CHART JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        function getIsDark() {
            return document.documentElement.classList.contains('dark');
        }

        function getChartOptions() {
            const isDark = getIsDark();

            const gridColor = isDark ? '#334155' : '#e5e7eb';
            const textColor = isDark ? '#cbd5e1' : '#111827';

            return {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: {
                            color: textColor
                        },
                        grid: {
                            color: gridColor
                        }
                    },
                    y: {
                        ticks: {
                            color: textColor
                        },
                        grid: {
                            color: gridColor
                        },
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: textColor
                        }
                    }
                }
            };
        }

        // ===== CHART THEO THÁNG =====
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

        // ===== CHART PHÒNG BAN =====
        const chartPhongBan = new Chart(document.getElementById('chartPhongBan'), {
            type: 'doughnut',
            data: {
                labels: @json($chartPhongBan->pluck('ten')),
                datasets: [{
                    data: @json($chartPhongBan->pluck('tong'))
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: () => getIsDark() ? '#cbd5e1' : '#111827'
                        }
                    }
                }
            }
        });

        // ===== OPTIONAL: auto update khi toggle dark mode =====
        const observer = new MutationObserver(() => {
            chartThang.options = getChartOptions();
            chartThang.update();

            chartPhongBan.update();
        });

        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
        });
    </script>

@endsection
