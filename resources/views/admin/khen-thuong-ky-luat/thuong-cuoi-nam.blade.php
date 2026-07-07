@extends('layouts.admin')

@section('title', 'Thưởng cuối năm')

@section('content')

    <div class="min-h-screen bg-gray-50 dark:bg-slate-900 p-6">

        <div class="max-w-7xl mx-auto space-y-6">

            {{-- HEADER --}}
            <div
                class="rounded-2xl bg-gradient-to-r from-emerald-600 via-green-600 to-teal-600
    text-white p-6 shadow-lg">

                <div class="flex flex-col lg:flex-row justify-between lg:items-center gap-5">

                    <div>

                        <div class="flex items-center gap-3">

                            <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center">

                                <i class="fa-solid fa-gift text-3xl"></i>

                            </div>

                            <div>

                                <h1 class="text-3xl font-bold">
                                    Thưởng cuối năm
                                </h1>

                                <p class="text-green-100 mt-1">
                                    Tự động tính thưởng dựa trên thành tích và kỷ luật trong năm.
                                </p>

                            </div>

                        </div>

                    </div>

                    <a href="{{ route('admin.khen-thuong-ky-luat.index') }}"
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-xl
            bg-white/20 hover:bg-white/30 transition">

                        <i class="fa-solid fa-arrow-left"></i>

                        Quay lại

                    </a>

                </div>

            </div>

            {{-- FILTER --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm p-6">

                <form method="GET" action="{{ route('admin.khen-thuong-ky-luat.thuong-cuoi-nam') }}"
                    class="flex flex-wrap items-end gap-4">

                    <div>

                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                            Chọn năm
                        </label>

                        <select name="nam"
                            class="h-11 rounded-xl border border-gray-300
                dark:border-slate-700
                bg-white dark:bg-slate-900
                text-gray-900 dark:text-white
                px-4">

                            @for ($y = now()->year; $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $nam == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor

                        </select>

                    </div>

                    <button class="h-11 px-6 rounded-xl
            bg-blue-600 hover:bg-blue-700
            text-white">

                        <i class="fa-solid fa-filter mr-2"></i>

                        Xem kết quả

                    </button>

                </form>

            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">

                <div class="bg-white dark:bg-slate-800 rounded-xl border dark:border-slate-700 p-5">

                    <p class="text-gray-500 dark:text-slate-400 text-sm">
                        Tổng nhân viên
                    </p>

                    <h2 class="text-3xl font-bold dark:text-white">
                        {{ count($ketQua) }}
                    </h2>

                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl border dark:border-slate-700 p-5">

                    <p class="text-gray-500 dark:text-slate-400 text-sm">
                        Tổng thưởng
                    </p>

                    <h2 class="text-emerald-600 text-3xl font-bold">

                        {{ number_format(collect($ketQua)->sum('thuong_cuoi_nam')) }}

                    </h2>

                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl border dark:border-slate-700 p-5">

                    <p class="text-gray-500 dark:text-slate-400 text-sm">
                        Điểm cao nhất
                    </p>

                    <h2 class="text-blue-600 text-3xl font-bold">

                        {{ collect($ketQua)->max('tong_diem') }}

                    </h2>

                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl border dark:border-slate-700 p-5">

                    <p class="text-gray-500 dark:text-slate-400 text-sm">
                        Điểm thấp nhất
                    </p>

                    <h2 class="text-red-600 text-3xl font-bold">

                        {{ collect($ketQua)->min('tong_diem') }}

                    </h2>

                </div>

            </div>

            {{-- TABLE --}}
            <div
                class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="min-w-full text-sm">

                        <thead
                            class="sticky top-0 bg-gray-100 dark:bg-slate-900 border-b border-gray-200 dark:border-slate-700">

                            <tr class="text-gray-700 dark:text-slate-300">

                                <th class="px-6 py-4 text-left font-semibold">Nhân viên</th>

                                <th class="px-6 py-4 text-center font-semibold">Phòng ban</th>

                                <th class="px-6 py-4 text-center font-semibold">Điểm</th>

                                <th class="px-6 py-4 text-center font-semibold">Khen</th>

                                <th class="px-6 py-4 text-center font-semibold">Kỷ luật</th>

                                <th class="px-6 py-4 text-right font-semibold">
                                    Tổng lương
                                </th>

                                <th class="px-6 py-4 text-right font-semibold text-emerald-600 dark:text-emerald-400">
                                    Thưởng cuối năm
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-slate-700">

                            @forelse($ketQua as $row)
                                @php
                                    $diem = $row['tong_diem'];
                                @endphp

                                <tr class="transition duration-200 hover:bg-emerald-50 dark:hover:bg-slate-700/50">

                                    {{-- Nhân viên --}}
                                    <td class="px-6 py-4">

                                        <div class="flex items-center gap-3">

                                            <div
                                                class="w-11 h-11 rounded-full bg-blue-100 dark:bg-blue-900/30
                                           flex items-center justify-center
                                           font-bold text-blue-700 dark:text-blue-300">

                                                {{ mb_substr($row['hoSo']->ho_ten, 0, 1) }}

                                            </div>

                                            <div>

                                                <div class="font-semibold text-gray-900 dark:text-white">

                                                    {{ $row['hoSo']->ho_ten }}

                                                </div>

                                                <div class="text-xs text-gray-500 dark:text-slate-400">

                                                    {{ $row['hoSo']->ma_nhan_vien }}

                                                </div>

                                            </div>

                                        </div>

                                    </td>

                                    {{-- Phòng ban --}}
                                    <td class="px-6 py-4 text-center text-gray-700 dark:text-slate-300">

                                        {{ $row['hoSo']->nguoi_dung?->phongBan?->ten_phong_ban ?? '---' }}

                                    </td>

                                    {{-- Điểm --}}
                                    <td class="px-6 py-4 text-center">

                                        @if ($diem > 0)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full
                                           bg-green-100 text-green-700
                                           dark:bg-green-900/30 dark:text-green-300">

                                                <i class="fa-solid fa-arrow-trend-up mr-1"></i>

                                                +{{ $diem }}

                                            </span>
                                        @elseif($diem < 0)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full
                                           bg-red-100 text-red-700
                                           dark:bg-red-900/30 dark:text-red-300">

                                                <i class="fa-solid fa-arrow-trend-down mr-1"></i>

                                                {{ $diem }}

                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full
                                           bg-gray-100 text-gray-700
                                           dark:bg-slate-700 dark:text-white">

                                                0

                                            </span>
                                        @endif

                                    </td>

                                    {{-- Khen --}}
                                    <td class="px-6 py-4 text-center">

                                        <span
                                            class="inline-flex items-center justify-center
                                       w-9 h-9 rounded-full
                                       bg-green-100 text-green-700
                                       dark:bg-green-900/20 dark:text-green-300">

                                            {{ $row['tong_khen_thuong'] }}

                                        </span>

                                    </td>

                                    {{-- Kỷ luật --}}
                                    <td class="px-6 py-4 text-center">

                                        <span
                                            class="inline-flex items-center justify-center
                                       w-9 h-9 rounded-full
                                       bg-red-100 text-red-700
                                       dark:bg-red-900/20 dark:text-red-300">

                                            {{ $row['tong_ky_luat'] }}

                                        </span>

                                    </td>

                                    {{-- Lương --}}
                                    <td class="px-6 py-4 text-right font-semibold text-gray-800 dark:text-slate-200">

                                        {{ number_format($row['thuong_co_ban']) }} đ

                                    </td>

                                    {{-- Thưởng --}}
                                    <td class="px-6 py-4 text-right">

                                        <div class="font-bold text-lg text-emerald-600 dark:text-emerald-400">

                                            {{ number_format($row['thuong_cuoi_nam']) }} đ

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="py-12 text-center text-gray-500 dark:text-slate-400">

                                        <i class="fa-regular fa-folder-open text-4xl mb-3 block"></i>

                                        Không có dữ liệu.

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

@endsection
