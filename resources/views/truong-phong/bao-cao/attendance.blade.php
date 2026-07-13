{{-- resources/views/truong-phong/bao-cao/attendance.blade.php --}}

@extends('layouts.admin')

@section('title', 'Báo cáo chấm công')

@section('content')
<div class="space-y-6">
    
    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-clock mr-3 text-blue-600"></i>
                Báo cáo chấm công
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Phòng <span class="font-medium text-blue-600">{{ $phongBan->ten_phong_ban }}</span>
                - Tháng {{ $thang }}/{{ $nam }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition">
                <i class="fas fa-print mr-1"></i> In
            </button>
            <a href="{{ route('truong-phong.bao-cao.export') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                <i class="fas fa-file-excel mr-1"></i> Xuất Excel
            </a>
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
            <a href="{{ route('truong-phong.bao-cao.attendance') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition">
                <i class="fas fa-redo mr-1"></i> Reset
            </a>
        </form>
    </div>

    {{-- Thống kê --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Tổng nhân viên</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $tongNhanVien }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-green-200 dark:border-green-700/50 p-4 shadow-sm">
            <p class="text-sm text-green-600 dark:text-green-400">Tổng ngày công</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $tongNgayChamCong }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-yellow-200 dark:border-yellow-700/50 p-4 shadow-sm">
            <p class="text-sm text-yellow-600 dark:text-yellow-400">Đi muộn</p>
            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $tongNgayDiMuon }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-orange-200 dark:border-orange-700/50 p-4 shadow-sm">
            <p class="text-sm text-orange-600 dark:text-orange-400">Về sớm</p>
            <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $tongNgayVeSom }}</p>
        </div>
    </div>

    {{-- Bảng chi tiết --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900 dark:text-white">
                <i class="fas fa-table mr-2 text-blue-600"></i>
                Chi tiết chấm công
            </h3>
            <span class="text-sm bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full text-gray-600 dark:text-gray-300">
                Tỷ lệ: {{ $tyLeChamCong }}%
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">STT</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Mã NV</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Họ tên</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Ngày công</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Đi muộn</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Về sớm</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tổng giờ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @forelse($nhanViens as $index => $nv)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $nv['ma_nhan_vien'] }}</td>
                        <td class="px-4 py-3">{{ $nv['ho_ten'] }}</td>
                        <td class="px-4 py-3 font-medium text-green-600 dark:text-green-400">{{ $nv['so_ngay_cham_cong'] }}</td>
                        <td class="px-4 py-3 text-yellow-600 dark:text-yellow-400">{{ $nv['so_ngay_di_muon'] }}</td>
                        <td class="px-4 py-3 text-orange-600 dark:text-orange-400">{{ $nv['so_ngay_ve_som'] }}</td>
                        <td class="px-4 py-3">{{ $nv['tong_gio_lam'] }}h</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-inbox text-4xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                <p class="font-medium">Không có dữ liệu chấm công</p>
                                <p class="text-sm">Chưa có dữ liệu chấm công trong tháng này</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection