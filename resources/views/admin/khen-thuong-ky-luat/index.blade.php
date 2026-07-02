@extends('layouts.admin')

@section('title', 'Khen thưởng / Kỷ luật')

@section('content')

    <div class="min-h-screen p-6 bg-gray-50 dark:bg-slate-900">

        <div class="max-w-7xl mx-auto space-y-6">

            {{-- HEADER --}}
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Khen thưởng / Kỷ luật
                    </h1>

                    <p class="text-gray-500 dark:text-slate-400 mt-1">
                        Quản lý toàn bộ quyết định khen thưởng và kỷ luật nhân viên.
                    </p>
                </div>

                <div class="flex items-center gap-2">

                    <a href="{{ route('admin.khen-thuong-ky-luat.export') }}"
                        class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white transition">

                        <i class="fa-solid fa-file-excel mr-2"></i>

                        Xuất Excel

                    </a>

                    <a href="{{ route('admin.khen-thuong-ky-luat.create') }}"
                        class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition">

                        <i class="fa-solid fa-plus mr-2"></i>

                        Thêm mới

                    </a>

                </div>

            </div>

            @include('layouts.partials.alerts')

            {{-- THỐNG KÊ --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">

                <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl p-5">

                    <div class="flex justify-between items-center">

                        <div>

                            <p class="text-sm text-gray-500 dark:text-slate-400">
                                Tổng quyết định
                            </p>

                            <h2 class="text-3xl font-bold mt-2 text-gray-900 dark:text-white">

                                {{ $tongQuyetDinh }}

                            </h2>

                        </div>

                        <div class="w-14 h-14 rounded-xl bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">

                            <i class="fa-solid fa-folder text-blue-600 text-xl"></i>

                        </div>

                    </div>

                </div>

                <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl p-5">

                    <div class="flex justify-between items-center">

                        <div>

                            <p class="text-sm text-gray-500 dark:text-slate-400">
                                Khen thưởng
                            </p>

                            <h2 class="text-3xl font-bold mt-2 text-green-600">

                                {{ $tongKhenThuong }}

                            </h2>

                        </div>

                        <div
                            class="w-14 h-14 rounded-xl bg-green-100 dark:bg-green-900/20 flex items-center justify-center">

                            <i class="fa-solid fa-trophy text-green-600 text-xl"></i>

                        </div>

                    </div>

                </div>

                <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl p-5">

                    <div class="flex justify-between items-center">

                        <div>

                            <p class="text-sm text-gray-500 dark:text-slate-400">
                                Kỷ luật
                            </p>

                            <h2 class="text-3xl font-bold mt-2 text-red-600">

                                {{ $tongKyLuat }}

                            </h2>

                        </div>

                        <div class="w-14 h-14 rounded-xl bg-red-100 dark:bg-red-900/20 flex items-center justify-center">

                            <i class="fa-solid fa-triangle-exclamation text-red-600 text-xl"></i>

                        </div>

                    </div>

                </div>

                <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl p-5">

                    <div class="flex justify-between items-center">

                        <div>

                            <p class="text-sm text-gray-500 dark:text-slate-400">
                                Tổng tiền thưởng
                            </p>

                            <h2 class="text-2xl font-bold mt-2 text-emerald-600">

                                {{ number_format($tongTienThuong) }} đ

                            </h2>

                        </div>

                        <div
                            class="w-14 h-14 rounded-xl bg-emerald-100 dark:bg-emerald-900/20 flex items-center justify-center">

                            <i class="fa-solid fa-money-bill-wave text-emerald-600 text-xl"></i>

                        </div>

                    </div>

                </div>

            </div>

            {{-- FILTER --}}

            <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-4">

                <form method="GET" class="space-y-3">

                    <!-- GRID FILTER -->
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-3">

                        <!-- SEARCH -->
                        <div class="relative">
                            <i
                                class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>

                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Tên / mã nhân viên..."
                                class="w-full h-10 pl-9 pr-3 rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>

                        <!-- LOẠI -->
                        <select name="loai"
                            class="h-10 px-3 rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">

                            <option value="">Tất cả loại</option>
                            <option value="khen_thuong" @selected(request('loai') == 'khen_thuong')>🏆 Khen thưởng</option>
                            <option value="ky_luat" @selected(request('loai') == 'ky_luat')>⚠️ Kỷ luật</option>

                        </select>

                        <!-- PHÒNG BAN -->
                        <select name="phong_ban"
                            class="h-10 px-3 rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">

                            <option value="">Phòng ban</option>

                            @foreach ($phongBans as $pb)
                                <option value="{{ $pb->id }}" @selected(request('phong_ban') == $pb->id)>
                                    {{ $pb->ten_phong_ban }}
                                </option>
                            @endforeach

                        </select>

                        <!-- THÁNG -->
                        <input type="number" name="thang" min="1" max="12" value="{{ request('thang') }}"
                            placeholder="Tháng"
                            class="h-10 px-3 rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">

                        <!-- NĂM -->
                        <input type="number" name="nam" value="{{ request('nam') }}" placeholder="Năm"
                            class="h-10 px-3 rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">

                    </div>

                    <!-- ACTIONS -->
                    <div class="flex justify-end gap-2">

                        <a href="{{ route('admin.khen-thuong-ky-luat.index') }}"
                            class="h-10 px-4 flex items-center gap-2 rounded-lg border border-gray-300 dark:border-slate-600
                            bg-white dark:bg-slate-900
                            text-gray-700 dark:text-gray-200
                            hover:bg-gray-100 dark:hover:bg-slate-700
                            transition">

                            <i class="fa-solid fa-rotate-left text-gray-500 dark:text-gray-300"></i>
                            Reset
                        </a>

                        <button type="submit"
                            class="h-10 px-5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white flex items-center gap-2 transition">

                            <i class="fa-solid fa-filter"></i>
                            Lọc
                        </button>

                    </div>

                </form>

            </div>
            {{-- DANH SÁCH --}}
            <div
                class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-gray-50 dark:bg-slate-900 border-b border-gray-200 dark:border-slate-700">

                            <tr class="text-left text-gray-500 dark:text-slate-400">

                                <th class="p-4 font-medium">Nhân viên</th>

                                <th class="p-4 font-medium">Loại</th>

                                <th class="p-4 font-medium">Ngày</th>

                                <th class="p-4 font-medium text-right">Số tiền</th>

                                <th class="p-4 font-medium">Người ký</th>

                                <th class="p-4 font-medium text-right">Thao tác</th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-100 dark:divide-slate-700">

                            @forelse($ds as $item)

                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/40 transition">

                                    {{-- Nhân viên --}}
                                    <td class="p-4">

                                        <div>

                                            <div class="font-semibold text-gray-900 dark:text-white">

                                                {{ $item->hoSo?->ho_ten }}

                                            </div>

                                            <div class="text-xs text-gray-500 dark:text-slate-400 mt-1">

                                                {{ $item->hoSo?->ma_nhan_vien }}

                                                •

                                                {{ $item->hoSo?->nguoi_dung?->phongBan?->ten_phong_ban ?? '---' }}

                                            </div>

                                        </div>

                                    </td>

                                    {{-- Loại --}}
                                    <td class="p-4">

                                        @if ($item->loai == 'khen_thuong')
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-300">

                                                🏆 Khen thưởng

                                            </span>
                                        @else
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-300">

                                                ⚠️ Kỷ luật

                                            </span>
                                        @endif

                                    </td>

                                    {{-- Ngày --}}
                                    <td class="p-4 text-gray-600 dark:text-slate-300">

                                        {{ $item->ngay->format('d/m/Y') }}

                                    </td>

                                    {{-- Tiền --}}
                                    <td class="p-4 text-right font-semibold">

                                        @if ($item->so_tien)
                                            @if ($item->loai == 'khen_thuong')
                                                <span class="text-green-600 dark:text-green-400">

                                                    +{{ number_format($item->so_tien) }} đ

                                                </span>
                                            @else
                                                <span class="text-red-600 dark:text-red-400">

                                                    -{{ number_format($item->so_tien) }} đ

                                                </span>
                                            @endif
                                        @else
                                            <span class="text-gray-400">

                                                —

                                            </span>
                                        @endif

                                    </td>

                                    {{-- Người ký --}}
                                    <td class="p-4 text-gray-600 dark:text-slate-300">

                                        {{ $item->nguoiKy?->ten_dang_nhap ?? '---' }}

                                    </td>

                                    {{-- Action --}}
                                    <td class="p-4">

                                        <div class="flex justify-end items-center gap-1">

                                            <a href="{{ route('admin.khen-thuong-ky-luat.show', $item->id) }}"
                                                class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-700">

                                                <i class="fa-regular fa-eye"></i>

                                            </a>

                                            <a href="{{ route('admin.khen-thuong-ky-luat.edit', $item->id) }}"
                                                class="p-2 rounded-lg text-amber-600 hover:bg-amber-50 dark:hover:bg-slate-700">

                                                <i class="fa-solid fa-pen"></i>

                                            </a>

                                            <form action="{{ route('admin.khen-thuong-ky-luat.destroy', $item->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Bạn có chắc muốn xóa quyết định này?')">

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    class="p-2 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-slate-700">

                                                    <i class="fa-solid fa-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="py-16 text-center">

                                        <div class="flex flex-col items-center">

                                            <div
                                                class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center">

                                                <i class="fa-solid fa-award text-3xl text-slate-400"></i>

                                            </div>

                                            <h3 class="mt-5 text-lg font-semibold text-gray-700 dark:text-slate-300">

                                                Chưa có dữ liệu

                                            </h3>

                                            <p class="text-gray-500 dark:text-slate-500 mt-1">

                                                Hiện chưa có quyết định khen thưởng hoặc kỷ luật nào.

                                            </p>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="p-4 border-t border-gray-200 dark:border-slate-700">

                    {{ $ds->links() }}

                </div>

            </div>

        </div>

    </div>

@endsection
