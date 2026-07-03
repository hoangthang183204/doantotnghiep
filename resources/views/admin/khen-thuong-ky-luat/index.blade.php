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

                    {{-- EXPORT --}}
                    <a href="{{ route('admin.khen-thuong-ky-luat.export') }}"
                        class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white transition">
                        <i class="fa-solid fa-file-excel mr-2"></i>
                        Xuất Excel
                    </a>

                    {{-- THÊM KHEN THƯỞNG --}}
                    <a href="{{ route('admin.khen-thuong-ky-luat.khen-thuong.create') }}"
                        class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition">
                        <i class="fa-solid fa-trophy mr-2"></i>
                        Thêm khen thưởng
                    </a>

                    {{-- THÊM KỶ LUẬT --}}
                    <a href="{{ route('admin.khen-thuong-ky-luat.ky-luat.create') }}"
                        class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white transition">
                        <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                        Thêm kỷ luật
                    </a>

                </div>
            </div>

            @include('layouts.partials.alerts')


            {{-- STATS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">

                <div class="bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-xl p-5">
                    <p class="text-sm text-gray-500 dark:text-slate-400">Tổng quyết định</p>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $tongQuyetDinh }}
                    </h2>
                </div>

                <div class="bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-xl p-5">
                    <p class="text-sm text-gray-500 dark:text-slate-400">Khen thưởng</p>
                    <h2 class="text-3xl font-bold text-green-600 mt-2">
                        {{ $tongKhenThuong }}
                    </h2>
                </div>

                <div class="bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-xl p-5">
                    <p class="text-sm text-gray-500 dark:text-slate-400">Kỷ luật</p>
                    <h2 class="text-3xl font-bold text-red-600 mt-2">
                        {{ $tongKyLuat }}
                    </h2>
                </div>

                <div class="bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-xl p-5">
                    <p class="text-sm text-gray-500 dark:text-slate-400">Tổng tiền thưởng</p>
                    <h2 class="text-2xl font-bold text-emerald-600 mt-2">
                        {{ number_format($tongTienThuong) }} đ
                    </h2>
                </div>

            </div>

            {{-- FILTER --}}
            <div class="bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-xl p-5">

                <form method="GET" action="{{ route('admin.khen-thuong-ky-luat.index') }}"
                    class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-4">

                    {{-- Tìm kiếm --}}
                    <div class="xl:col-span-2">
                        <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-slate-300">
                            Tìm kiếm
                        </label>

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Tên hoặc mã nhân viên..."
                            class="w-full h-11 rounded-lg border border-gray-300 dark:border-slate-700
                       bg-white dark:bg-slate-900
                       text-gray-900 dark:text-white
                       placeholder-gray-400 dark:placeholder-slate-500
                       px-4 focus:ring-2 focus:ring-blue-500">
                    </div>

                    {{-- Loại --}}
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-slate-300">
                            Loại
                        </label>

                        <select name="loai"
                            class="w-full h-11 rounded-lg border border-gray-300 dark:border-slate-700
                       bg-white dark:bg-slate-900
                       text-gray-900 dark:text-white px-3">

                            <option value="">Tất cả</option>
                            <option value="khen_thuong" {{ request('loai') == 'khen_thuong' ? 'selected' : '' }}>
                                Khen thưởng
                            </option>
                            <option value="ky_luat" {{ request('loai') == 'ky_luat' ? 'selected' : '' }}>
                                Kỷ luật
                            </option>
                        </select>
                    </div>

                    {{-- Phòng ban --}}
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-slate-300">
                            Phòng ban
                        </label>

                        <select name="phong_ban"
                            class="w-full h-11 rounded-lg border border-gray-300 dark:border-slate-700
                       bg-white dark:bg-slate-900
                       text-gray-900 dark:text-white px-3">

                            <option value="">Tất cả</option>

                            @foreach ($phongBans as $pb)
                                <option value="{{ $pb->id }}"
                                    {{ request('phong_ban') == $pb->id ? 'selected' : '' }}>
                                    {{ $pb->ten_phong_ban }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Tháng --}}
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-slate-300">
                            Tháng
                        </label>

                        <select name="thang"
                            class="w-full h-11 rounded-lg border border-gray-300 dark:border-slate-700
                       bg-white dark:bg-slate-900
                       text-gray-900 dark:text-white px-3">

                            <option value="">Tất cả</option>

                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('thang') == $i ? 'selected' : '' }}>
                                    Tháng {{ $i }}
                                </option>
                            @endfor

                        </select>
                    </div>

                    {{-- Năm --}}
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-slate-300">
                            Năm
                        </label>

                        <select name="nam"
                            class="w-full h-11 rounded-lg border border-gray-300 dark:border-slate-700
                       bg-white dark:bg-slate-900
                       text-gray-900 dark:text-white px-3">

                            <option value="">Tất cả</option>

                            @for ($y = now()->year; $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ request('nam') == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor

                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="xl:col-span-6 flex justify-end gap-3 mt-2">

                        <a href="{{ route('admin.khen-thuong-ky-luat.index') }}"
                            class="px-5 py-2.5 rounded-lg border border-gray-300 dark:border-slate-700
                       bg-white dark:bg-slate-900
                       text-gray-700 dark:text-white
                       hover:bg-gray-100 dark:hover:bg-slate-700 transition">
                            <i class="fa-solid fa-rotate-left mr-2"></i>
                            Đặt lại
                        </a>

                        <button type="submit"
                            class="px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700
                       text-white transition shadow-sm">
                            <i class="fa-solid fa-filter mr-2"></i>
                            Lọc dữ liệu
                        </button>

                    </div>

                </form>

            </div>

            {{-- TABLE --}}
            <div class="bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-xl overflow-hidden">

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">

                        <thead class="bg-gray-50 dark:bg-slate-900 border-b dark:border-slate-700">
                            <tr class="text-left text-gray-500 dark:text-slate-400">
                                <th class="p-4">Nhân viên</th>
                                <th class="p-4">Loại</th>
                                <th class="p-4">Ngày</th>
                                <th class="p-4 text-right">Số tiền</th>
                                <th class="p-4">Người ký</th>
                                <th class="p-4 text-right">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y dark:divide-slate-700">

                            @forelse($ds as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/40 transition">

                                    {{-- NHÂN VIÊN --}}
                                    <td class="p-4">
                                        <div class="font-semibold">
                                            <!-- Khi click vào tên sẽ tự động lọc ra toàn bộ lịch sử khen thưởng / kỷ luật của nhân viên đó -->
                                            <a href="{{ route('admin.khen-thuong-ky-luat.index', ['search' => $item->hoSo?->ma_nhan_vien]) }}"
                                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:underline transition">
                                                {{ $item->hoSo?->ho_ten }}
                                            </a>
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-slate-400">
                                            {{ $item->hoSo?->ma_nhan_vien }}
                                        </div>
                                    </td>

                                    {{-- LOẠI --}}
                                    <td class="p-4">
                                        @if ($item->loai === 'khen_thuong')
                                            <span
                                                class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-300">
                                                🏆 Khen thưởng
                                            </span>
                                        @else
                                            <span
                                                class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-300">
                                                ⚠️ Kỷ luật
                                            </span>
                                        @endif
                                    </td>

                                    {{-- NGÀY --}}
                                    <td class="p-4 text-gray-600 dark:text-slate-300">
                                        {{ optional($item->ngay)->format('d/m/Y') }}
                                    </td>

                                    {{-- SỐ TIỀN --}}
                                    <td class="p-4 text-right font-semibold">
                                        @if ($item->so_tien)
                                            <span
                                                class="{{ $item->loai === 'khen_thuong' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $item->loai === 'khen_thuong' ? '+' : '-' }}
                                                {{ number_format($item->so_tien) }} đ
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>

                                    {{-- NGƯỜI KÝ --}}
                                    <td class="p-4 text-gray-600 dark:text-slate-300">
                                        {{ $item->nguoiKy?->ten_dang_nhap ?? '---' }}
                                    </td>

                                    {{-- ACTION --}}
                                    <td class="p-4">
                                        <div class="flex justify-end gap-2">

                                            <a href="{{ route('admin.khen-thuong-ky-luat.show', $item->id) }}"
                                                class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg">
                                                <i class="fa-regular fa-eye"></i>
                                            </a>

                                            <a href="{{ route('admin.khen-thuong-ky-luat.edit', $item->id) }}"
                                                class="p-2 text-amber-600 hover:bg-amber-50 dark:hover:bg-slate-700 rounded-lg">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>

                                            <form action="{{ route('admin.khen-thuong-ky-luat.destroy', $item->id) }}"
                                                method="POST" onsubmit="return confirm('Xóa bản ghi này?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-slate-700 rounded-lg">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-10 text-center text-gray-500 dark:text-slate-400">
                                        Không có dữ liệu
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="p-4 border-t dark:border-slate-700">
                    {{ $ds->links() }}
                </div>

            </div>

        </div>
    </div>

@endsection
