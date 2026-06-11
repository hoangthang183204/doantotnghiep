@extends('layouts.admin')

@section('content')
    <div class="p-6 max-w-7xl mx-auto space-y-6">

        {{-- Thông báo thành công từ Controller chuyển về --}}
        @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-2xl bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-100 dark:border-green-900"
                role="alert">
                <span class="font-semibold">Thành công!</span> {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Loại nghỉ phép
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">
                    Quản lý danh mục loại nghỉ phép trong hệ thống.
                </p>
            </div>

            {{-- ĐÃ SỬA: Chuyển từ Button Modal sang Thẻ A điều hướng --}}
            <a href="{{ route('admin.loai-nghi-phep.create') }}"
                class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition-all duration-200 transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tạo loại nghỉ phép
            </a>
        </div>

        {{-- Thống kê --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Tổng loại nghỉ</p>
                <h3 class="text-3xl font-bold mt-2 text-gray-900 dark:text-white">
                    {{ $dsLoaiNghi->count() }}
                </h3>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Đang hoạt động</p>
                <h3 class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mt-2">
                    {{ $dsLoaiNghi->where('trang_thai', 1)->count() }}
                </h3>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Có lương</p>
                <h3 class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-2">
                    {{ $dsLoaiNghi->where('co_luong', 1)->count() }}
                </h3>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Không lương</p>
                <h3 class="text-3xl font-bold text-red-600 dark:text-red-400 mt-2">
                    {{ $dsLoaiNghi->where('co_luong', 0)->count() }}
                </h3>
            </div>
        </div>

        {{-- Search --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-4">
            <form action="{{ route('admin.loai-nghi-phep.index') }}" method="GET" class="max-w-2xl flex gap-3">

                {{-- Khung chứa ô nhập liệu --}}
                <div class="relative flex-1">
                    <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>

                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Tìm kiếm theo mã hoặc tên loại nghỉ phép..."
                        class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 outline-none transition">
                </div>

                {{-- Nút Tìm kiếm xịn sò thêm mới --}}
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-sm transition-all duration-200 active:scale-95 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Tìm kiếm
                </button>

                {{-- Nút Xóa bộ lọc (Chỉ hiển thị khi đang có từ khóa tìm kiếm để reset nhanh về danh sách tổng) --}}
                @if (request('search'))
                    <a href="{{ route('admin.loai-nghi-phep.index') }}"
                        class="inline-flex items-center px-4 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-200 font-medium transition whitespace-nowrap"
                        title="Xóa bộ lọc">
                        Xóa lọc
                    </a>
                @endif

            </form>
        </div>

        {{-- Table --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white">
                    Danh sách loại nghỉ phép
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr
                            class="bg-gradient-to-r from-slate-50 to-gray-50 dark:from-gray-700 dark:to-gray-800 text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider">Mã</th>
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider">Tên loại nghỉ phép
                            </th>
                            <th class="px-5 py-4 text-center text-xs font-bold uppercase tracking-wider">Có lương</th>
                            <th class="px-5 py-4 text-center text-xs font-bold uppercase tracking-wider">Trạng thái</th>
                            <th class="px-5 py-4 text-center text-xs font-bold uppercase tracking-wider">Ngày tạo</th>
                            <th class="px-5 py-4 text-center text-xs font-bold uppercase tracking-wider w-24">Hành động</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($dsLoaiNghi as $item)
                            <tr class="hover:bg-blue-50/40 dark:hover:bg-gray-700/40 transition-all duration-200">
                                {{-- Mã --}}
                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 font-semibold text-xs border border-slate-200 dark:border-gray-600 shadow-sm">
                                        {{ $item->ma }}
                                    </span>
                                </td>

                                {{-- Tên (ĐÃ SỬA: từ $item->ten thành $item->ten_loai-nghi-phep cho đồng bộ cột DB) --}}
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-gray-900 dark:text-white">
                                        {{ $item->ten_loai_nghi_phep ?? $item->ten }}
                                    </div>
                                </td>

                                {{-- Có lương --}}
                                <td class="px-5 py-4 text-center">
                                    @if ($item->co_luong)
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-semibold">
                                            ✓ Có lương
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-semibold">
                                            ✕ Không lương
                                        </span>
                                    @endif
                                </td>

                                {{-- Trạng thái --}}
                                <td class="px-5 py-4 text-center">
                                    @if ($item->trang_thai)
                                        <span
                                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-medium">
                                            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                            Hoạt động
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-medium">
                                            <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                            Tạm khóa
                                        </span>
                                    @endif
                                </td>

                                {{-- Ngày tạo --}}
                                <td
                                    class="px-5 py-4 text-center text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') : 'N/A' }}
                                </td>

                                {{-- Hành động --}}
                                <td class="px-5 py-4">
                                    <div class="flex justify-center gap-2">
                                        {{-- ĐÃ SỬA: Nút xem chi tiết --}}
                                        <a href="{{ route('admin.loai-nghi-phep.show', $item->id) }}"
                                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-all shadow-sm"
                                            title="Xem chi tiết">
                                            👁
                                        </a>

                                        {{-- ĐÃ SỬA: Nút chỉnh sửa --}}
                                        <a href="{{ route('admin.loai-nghi-phep.edit', $item->id) }}"
                                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-all shadow-sm"
                                            title="Chỉnh sửa">
                                            ✏
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-500 dark:text-gray-400">
                                    Chưa có dữ liệu loại nghỉ phép nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
