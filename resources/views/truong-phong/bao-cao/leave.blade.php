{{-- resources/views/truong-phong/bao-cao/leave.blade.php --}}

@extends('layouts.admin')

@section('title', 'Báo cáo nghỉ phép')

@section('content')
<div class="space-y-6">
    
    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-calendar-alt mr-3 text-blue-600"></i>
                Báo cáo nghỉ phép
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Phòng <span class="font-medium text-blue-600">{{ $phongBan->ten_phong_ban }}</span>
                - Tháng {{ $thang }}/{{ $nam }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-sm bg-gray-100 dark:bg-gray-700 px-3 py-1.5 rounded-lg text-gray-600 dark:text-gray-300">
                <i class="fas fa-calendar-check mr-1"></i> {{ $tongDon }} đơn
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
            <a href="{{ route('truong-phong.bao-cao.leave') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition">
                <i class="fas fa-redo mr-1"></i> Reset
            </a>
        </form>
    </div>

    {{-- Thống kê --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Tổng đơn</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $tongDon }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-yellow-200 dark:border-yellow-700/50 p-4 shadow-sm">
            <p class="text-sm text-yellow-600 dark:text-yellow-400">Chờ duyệt</p>
            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $choDuyet }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-green-200 dark:border-green-700/50 p-4 shadow-sm">
            <p class="text-sm text-green-600 dark:text-green-400">Đã duyệt</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $daDuyet }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-red-200 dark:border-red-700/50 p-4 shadow-sm">
            <p class="text-sm text-red-600 dark:text-red-400">Từ chối</p>
            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $tuChoi }}</p>
        </div>
    </div>

    {{-- Thống kê theo loại nghỉ --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-gray-900 dark:text-white">
                <i class="fas fa-tags mr-2 text-blue-600"></i>
                Thống kê theo loại nghỉ
            </h3>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($thongKeLoaiNghi as $item)
                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item->loaiNghiPhep->ten ?? 'Khác' }}</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $item->so_luong }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Danh sách đơn --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900 dark:text-white">
                <i class="fas fa-list mr-2 text-blue-600"></i>
                Danh sách đơn nghỉ phép
            </h3>
            <span class="text-sm bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full text-gray-600 dark:text-gray-300">
                Tổng {{ $tongSoNgayNghi }} ngày
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Mã đơn</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nhân viên</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Loại nghỉ</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Thời gian</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Số ngày</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @forelse($donNghis as $don)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $don->ma_don_nghi }}</td>
                        <td class="px-4 py-3">
                            {{ $don->nguoiDung->hoSo->ho ?? '' }} {{ $don->nguoiDung->hoSo->ten ?? '' }}
                        </td>
                        <td class="px-4 py-3">{{ $don->loaiNghiPhep->ten ?? 'N/A' }}</td>
                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($don->ngay_bat_dau)->format('d/m/Y') }} 
                            <span class="text-gray-400">→</span> 
                            {{ \Carbon\Carbon::parse($don->ngay_ket_thuc)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 text-center font-medium">{{ $don->so_ngay_nghi }}</td>
                        <td class="px-4 py-3">
                            @if($don->trang_thai == 'cho_duyet')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                                    Chờ duyệt
                                </span>
                            @elseif($don->trang_thai == 'da_duyet')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                    Đã duyệt
                                </span>
                            @elseif($don->trang_thai == 'tu_choi')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                    Từ chối
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                    {{ $don->trang_thai }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-inbox text-4xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                <p class="font-medium">Không có đơn nghỉ phép</p>
                                <p class="text-sm">Chưa có đơn nghỉ phép trong tháng này</p>
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