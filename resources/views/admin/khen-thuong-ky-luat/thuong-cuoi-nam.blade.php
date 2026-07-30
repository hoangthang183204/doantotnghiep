@extends('layouts.admin')

@section('title', 'Thưởng cuối năm')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                    Thưởng cuối năm
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    Tự động tính thưởng dựa trên thành tích và kỷ luật trong năm.
                </p>
            </div>
            <a href="{{ route('admin.khen-thuong-ky-luat.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm transition gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Quay lại
            </a>
        </div>

        <div class="mt-6">
            <form method="GET" action="{{ route('admin.khen-thuong-ky-luat.thuong-cuoi-nam') }}" class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Chọn năm
                    </label>
                    <select name="nam" class="h-11 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @for ($y = now()->year; $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ $nam == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="h-11 px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition shadow-sm">
                    <i class="fa-solid fa-filter mr-1"></i>
                    Xem kết quả
                </button>
            </form>
        </div>
    </div>

    {{-- KPI --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="text-xs text-gray-500 dark:text-gray-400">Tổng nhân viên</div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($ketQua) }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="text-xs text-gray-500 dark:text-gray-400">Tổng thưởng</div>
            <div class="text-2xl font-bold text-emerald-600">{{ number_format(collect($ketQua)->sum('thuong_cuoi_nam')) }} đ</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="text-xs text-gray-500 dark:text-gray-400">Điểm cao nhất</div>
            <div class="text-2xl font-bold text-blue-600">{{ collect($ketQua)->max('tong_diem') }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="text-xs text-gray-500 dark:text-gray-400">Điểm thấp nhất</div>
            <div class="text-2xl font-bold text-red-600">{{ collect($ketQua)->min('tong_diem') }}</div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/30 border-b border-gray-200 dark:border-gray-700">
                    <tr class="text-left text-gray-600 dark:text-gray-400">
                        <th class="px-6 py-4 font-medium">Nhân viên</th>
                        <th class="px-6 py-4 text-center font-medium">Phòng ban</th>
                        <th class="px-6 py-4 text-center font-medium">Điểm</th>
                        <th class="px-6 py-4 text-center font-medium">Khen</th>
                        <th class="px-6 py-4 text-center font-medium">Kỷ luật</th>
                        <th class="px-6 py-4 text-right font-medium">Tổng lương</th>
                        <th class="px-6 py-4 text-right font-medium text-emerald-600 dark:text-emerald-400">Thưởng cuối năm</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($ketQua as $row)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center font-bold text-blue-700 dark:text-blue-300 text-sm">
                                    {{ mb_substr($row['hoSo']->ho_ten, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $row['hoSo']->ho_ten }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $row['hoSo']->ma_nhan_vien }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-700 dark:text-gray-300">
                            {{ $row['hoSo']->nguoi_dung?->phongBan?->ten_phong_ban ?? '---' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if ($row['tong_diem'] > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300">
                                    <i class="fa-solid fa-arrow-trend-up mr-1"></i> +{{ $row['tong_diem'] }}
                                </span>
                            @elseif($row['tong_diem'] < 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300">
                                    <i class="fa-solid fa-arrow-trend-down mr-1"></i> {{ $row['tong_diem'] }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-white">0</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-300 text-sm font-medium">
                                {{ $row['tong_khen_thuong'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-300 text-sm font-medium">
                                {{ $row['tong_ky_luat'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-medium text-gray-800 dark:text-gray-200">
                            {{ number_format($row['thuong_co_ban']) }} đ
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                                {{ number_format($row['thuong_cuoi_nam']) }} đ
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                            <i class="fa-regular fa-folder-open text-4xl block mb-3"></i>
                            Không có dữ liệu.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection