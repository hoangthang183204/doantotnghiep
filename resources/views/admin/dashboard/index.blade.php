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
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
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
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Nhân viên mới</dt>
                    <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $nhanVienMoi ?? 0 }}</dd>
                    <p class="text-xs {{ ($tyLeNhanVienMoiThayDoi ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                        <i class="mdi {{ ($tyLeNhanVienMoiThayDoi ?? 0) >= 0 ? 'mdi-trending-up' : 'mdi-trending-down' }}"></i>
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
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Chấm công hôm nay</dt>
                    <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $nhanVienChamCongHomNay ?? 0 }}</dd>
                    <p class="text-xs {{ ($tyLeChamCongThayDoi ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                        <i class="mdi {{ ($tyLeChamCongThayDoi ?? 0) >= 0 ? 'mdi-trending-up' : 'mdi-trending-down' }}"></i>
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
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Nghỉ phép hôm nay</dt>
                    <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $nhanVienNghiPhepHomNay ?? 0 }}</dd>
                    <p class="text-xs {{ ($tyLeNghiPhepThayDoi ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                        <i class="mdi {{ ($tyLeNghiPhepThayDoi ?? 0) >= 0 ? 'mdi-trending-up' : 'mdi-trending-down' }}"></i>
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
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Ứng viên mới</dt>
                    <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $tongUngVien ?? 0 }}</dd>
                    <p class="text-xs {{ ($tyLeUngVienThayDoi ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                        <i class="mdi {{ ($tyLeUngVienThayDoi ?? 0) >= 0 ? 'mdi-trending-up' : 'mdi-trending-down' }}"></i>
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
                    <button onclick="toggleChartType('pie')" class="px-3 py-1 text-sm rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition" id="btnPie">
                        <i class="mdi mdi-chart-pie"></i> Tròn
                    </button>
                    <button onclick="toggleChartType('doughnut')" class="px-3 py-1 text-sm rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition" id="btnDoughnut">
                        <i class="mdi mdi-chart-donut"></i> Donut
                    </button>
                    <button onclick="toggleChartType('bar')" class="px-3 py-1 text-sm rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition" id="btnBar">
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
                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">{{ count($employees ?? []) }} người</span>
            </div>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($employees ?? [] as $employee)
                <div class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    <div class="flex-shrink-0">
                        <img src="{{ asset($employee->hoSo->anh_dai_dien ?? 'assets/images/default.png') }}" 
                             alt="{{ $employee->hoSo->ho . ' ' . $employee->hoSo->ten }}"
                             class="w-10 h-10 rounded-full object-cover"
                             onerror="this.src='{{ asset('assets/images/default.png') }}'">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                            {{ $employee->hoSo->ho . ' ' . $employee->hoSo->ten }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            <i class="mdi mdi-clock-outline"></i> {{ Carbon\Carbon::parse($employee->hoSo->created_at)->diffForHumans() }}
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
                <select id="monthSelect" onchange="loadLeaveChart(this.value)" class="px-3 py-1 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800">
                    <option value="1">Tháng 1</option>
                    <option value="2">Tháng 2</option>
                    <option value="3">Tháng 3</option>
                    <option value="4">Tháng 4</option>
                    <option value="5">Tháng 5</option>
                    <option value="6">Tháng 6</option>
                    <option value="7">Tháng 7</option>
                    <option value="8">Tháng 8</option>
                    <option value="9">Tháng 9</option>
                    <option value="10">Tháng 10</option>
                    <option value="11">Tháng 11</option>
                    <option value="12">Tháng 12</option>
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
// Data from PHP
const chartConfig = {
    attendanceData: {!! json_encode($dataAverageAttendanceRate ?? []) !!},
    designationNames: <?php echo $DesignationName ?? '[]'; ?>,
    designationSeries: <?php echo $designationSeries ?? '[]'; ?>,
    labelsGender: @json($labelsGender ?? []),
    dataGender: @json($dataGender ?? []),
    sickLeaveData: @json(array_values($sickLeaveData ?? [])),
    casualLeaveData: @json(array_values($casualLeaveData ?? [])),
    months: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
    colors: ['#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6', '#06b6d4', '#ec4899', '#14b8a6', '#f97316', '#6366f1', '#d946ef', '#84cc16']
};

let attendanceChart = null;
let employeeChart = null;
let genderChart = null;
let leaveChart = null;
let currentChartType = 'pie';
let hiddenMonths = new Set();

// Get active data (filter out zero and hidden)
function getActiveData() {
    const activeData = [];
    const activeLabels = [];
    const activeColors = [];
    
    chartConfig.attendanceData.forEach((value, index) => {
        if (value > 0 && !hiddenMonths.has(index)) {
            activeData.push(value);
            activeLabels.push(chartConfig.months[index]);
            activeColors.push(chartConfig.colors[index]);
        }
    });
    
    return { data: activeData, labels: activeLabels, colors: activeColors };
}

// Create attendance chart
function createAttendanceChart(type = 'pie') {
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    const activeData = getActiveData();
    
    if (attendanceChart) {
        attendanceChart.destroy();
    }
    
    if (activeData.data.length === 0) {
        return;
    }
    
    const config = {
        type: type,
        data: {
            labels: activeData.labels,
            datasets: [{
                data: activeData.data,
                backgroundColor: activeData.colors,
                borderColor: 'white',
                borderWidth: 2,
                borderRadius: type === 'bar' ? 6 : 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (type === 'bar') {
                                return `${context.label}: ${context.parsed.y}%`;
                            }
                            const total = activeData.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return `${context.label}: ${context.parsed}% (${percentage}% tổng)`;
                        }
                    }
                }
            }
        }
    };
    
    if (type === 'bar') {
        config.options.scales = {
            y: { beginAtZero: true, max: 100, ticks: { callback: (v) => v + '%' } }
        };
    }
    
    attendanceChart = new Chart(ctx, config);
    updateStatsDisplay(activeData);
}

// Update stats
function updateStatsDisplay(activeData) {
    const total = activeData.data.reduce((a, b) => a + b, 0);
    const average = activeData.data.length > 0 ? (total / activeData.data.length).toFixed(1) : 0;
    const maxValue = Math.max(...activeData.data);
    const maxIndex = activeData.data.indexOf(maxValue);
    const maxMonth = activeData.data.length > 0 ? activeData.labels[maxIndex] : '--';
    
    document.getElementById('activeMonths').textContent = activeData.data.length;
    document.getElementById('totalRate').textContent = total.toFixed(1) + '%';
    document.getElementById('avgActive').textContent = average + '%';
    document.getElementById('maxMonth').textContent = maxMonth;
}

// Toggle chart type
function toggleChartType(type) {
    currentChartType = type;
    createAttendanceChart(type);
    
    document.querySelectorAll('[onclick^="toggleChartType"]').forEach(btn => {
        btn.classList.remove('bg-blue-500', 'text-white');
        btn.classList.add('bg-gray-100', 'dark:bg-gray-700');
    });
    const btnMap = { pie: 'btnPie', doughnut: 'btnDoughnut', bar: 'btnBar' };
    const activeBtn = document.getElementById(btnMap[type]);
    if (activeBtn) {
        activeBtn.classList.remove('bg-gray-100', 'dark:bg-gray-700');
        activeBtn.classList.add('bg-blue-500', 'text-white');
    }
}

// Create employee chart
function createEmployeeChart() {
    const ctx = document.getElementById('employeeChart').getContext('2d');
    employeeChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartConfig.designationNames,
            datasets: [{
                label: 'Số lượng nhân viên',
                data: chartConfig.designationSeries,
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
}

// Create gender chart
function createGenderChart() {
    const ctx = document.getElementById('genderChart').getContext('2d');
    genderChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: chartConfig.labelsGender,
            datasets: [{
                data: chartConfig.dataGender,
                backgroundColor: ['#3b82f6', '#ec4899', '#10b981'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '60%',
            plugins: { legend: { display: false } }
        }
    });
    
    // Generate legend
    const legendContainer = document.getElementById('genderLegend');
    if (legendContainer) {
        legendContainer.innerHTML = '';
        chartConfig.labelsGender.forEach((label, index) => {
            const div = document.createElement('div');
            div.className = 'flex items-center gap-2';
            div.innerHTML = `
                <div class="w-3 h-3 rounded-full" style="background: ${['#3b82f6', '#ec4899', '#10b981'][index]}"></div>
                <span class="text-sm">${label}: ${chartConfig.dataGender[index]}%</span>
            `;
            legendContainer.appendChild(div);
        });
    }
}

// Create leave chart
function createLeaveChart(month = null) {
    const ctx = document.getElementById('leaveChart').getContext('2d');
    const currentMonth = month !== null ? month : new Date().getMonth() + 1;
    
    if (leaveChart) leaveChart.destroy();
    
    const sickData = chartConfig.sickLeaveData[currentMonth - 1] || [0,0,0,0,0];
    const casualData = chartConfig.casualLeaveData[currentMonth - 1] || [0,0,0,0,0];
    
    leaveChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Tuần 1', 'Tuần 2', 'Tuần 3', 'Tuần 4', 'Tuần 5'],
            datasets: [
                { label: 'Nghỉ ốm', data: sickData, borderColor: '#ef4444', backgroundColor: 'rgba(239, 68, 68, 0.1)', tension: 0.3, fill: true },
                { label: 'Nghỉ phép thường', data: casualData, borderColor: '#3b82f6', backgroundColor: 'rgba(59, 130, 246, 0.1)', tension: 0.3, fill: true }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { position: 'top' } }
        }
    });
}

function loadLeaveChart(month) {
    createLeaveChart(parseInt(month));
}

// Initialize all charts
document.addEventListener('DOMContentLoaded', function() {
    createAttendanceChart('pie');
    createEmployeeChart();
    createGenderChart();
    createLeaveChart();
    
    // Set active button
    document.getElementById('btnPie')?.classList.add('bg-blue-500', 'text-white');
});
</script>
@endpush