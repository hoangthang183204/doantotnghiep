@extends('layouts.admin')

@section('title', 'Hồ sơ nhân viên')

@section('content')

    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                        Quản lý danh sách nhân sự
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Thông tin nhân sự được hiển thị bên dưới. Có thể tìm kiếm, xem hoặc chỉnh sửa.
                    </p>
                </div>

                {{-- Thống kê nhanh --}}
                <div class="flex items-center gap-3 text-sm">
                    <span class="flex items-center gap-1.5 px-3 py-1.5 bg-green-50 dark:bg-green-900/20 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        <span
                            class="text-green-700 dark:text-green-300 font-medium">{{ $hoSos->where('nguoi_dung.trang_thai', 1)->count() ?? 0 }}</span>
                        <span class="text-gray-500 dark:text-gray-400">đang làm</span>
                    </span>
                    <span class="flex items-center gap-1.5 px-3 py-1.5 bg-red-50 dark:bg-red-900/20 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                        <span
                            class="text-red-700 dark:text-red-300 font-medium">{{ $hoSos->where('nguoi_dung.trang_thai', 0)->count() ?? 0 }}</span>
                        <span class="text-gray-500 dark:text-gray-400">đã nghỉ</span>
                    </span>
                    <span class="flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span class="text-blue-700 dark:text-blue-300 font-medium">{{ $hoSos->total() ?? 0 }}</span>
                        <span class="text-gray-500 dark:text-gray-400">tổng</span>
                    </span>
                </div>
            </div>

            {{-- FILTER --}}
            <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">

                <form method="GET" action="{{ route('admin.ho-so.index') }}">

                    <div class="flex flex-wrap items-center gap-2">

                        {{-- Tìm kiếm --}}
                        <div class="relative flex-1 min-w-[180px]">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="keyword" value="{{ request('keyword') }}"
                                placeholder="Tìm kiếm nhân viên..."
                                class="w-full border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg pl-9 pr-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:bg-white dark:focus:bg-gray-600 outline-none transition">
                        </div>

                        {{-- Email --}}
                        <div class="relative flex-1 min-w-[160px]">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <input type="text" name="email" value="{{ request('email') }}" placeholder="Email..."
                                class="w-full border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg pl-9 pr-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:bg-white dark:focus:bg-gray-600 outline-none transition">
                        </div>

                        {{-- Phòng ban --}}
                        <div class="relative flex-1 min-w-[140px]">
                            <select name="phong_ban_id"
                                class="w-full border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:bg-white dark:focus:bg-gray-600 outline-none appearance-none transition">
                                <option value="">Tất cả phòng ban</option>
                                @foreach ($phongBans ?? [] as $phongBan)
                                    <option value="{{ $phongBan->id }}"
                                        {{ request('phong_ban_id') == $phongBan->id ? 'selected' : '' }}>
                                        {{ $phongBan->ten_phong_ban }}
                                    </option>
                                @endforeach
                            </select>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        {{-- Trạng thái --}}
                        <div class="relative flex-1 min-w-[130px]">
                            <select name="trang_thai"
                                class="w-full border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:bg-white dark:focus:bg-gray-600 outline-none appearance-none transition">
                                <option value="">Tất cả trạng thái</option>
                                <option value="1" {{ request('trang_thai') === '1' ? 'selected' : '' }}>✅ Đang làm việc
                                </option>
                                <option value="0" {{ request('trang_thai') === '0' ? 'selected' : '' }}>⛔ Đã nghỉ việc
                                </option>
                            </select>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        {{-- Nút Lọc --}}
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg transition flex items-center gap-1.5 text-sm font-medium flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Lọc
                        </button>

                        {{-- Nút Reset --}}
                        <a href="{{ route('admin.ho-so.index') }}"
                            class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 px-4 py-1.5 rounded-lg transition flex items-center gap-1.5 text-sm font-medium flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset
                        </a>

                    </div>

                </form>

            </div>

        </div>

        <div class="flex gap-3 mt-4 flex-wrap">
            {{-- Các nút hiện có: Lọc, Reset --}}

            {{-- NÚT EXPORT --}}
            <a href="{{ route('admin.ho-so.export', request()->query()) }}"
                class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg transition inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                📤 Xuất Excel
            </a>
        </div>

        {{-- TABLE --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

            <div class="overflow-x-auto">

                <table class="min-w-full text-gray-700 dark:text-gray-200">

                    <thead>
                        <tr
                            class="text-left text-sm text-gray-600 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">
                            <th class="p-3">NHÂN VIÊN</th>
                            <th class="p-3">MÃ NV</th>
                            <th class="p-3">CHỨC VỤ</th>
                            <th class="p-3">PHÒNG BAN</th>
                            <th class="p-3">SĐT</th>
                            <th class="p-3 text-center">THAO TÁC</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($hoSos as $hoSo)
                            @php
                                $trangThai = $hoSo->trang_thai ?? 1;
                                $nguoiDung = $hoSo->nguoi_dung;
                            @endphp

                            <tr
                                class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition {{ $trangThai === 0 ? 'opacity-60' : '' }}">

                                {{-- NHÂN VIÊN --}}
                                <td class="p-3">

                                    <div class="flex items-start gap-3">

                                        @if ($hoSo->anh_dai_dien)
                                            <img src="{{ asset('storage/' . $hoSo->anh_dai_dien) }}" alt="Avatar"
                                                class="w-10 h-10 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                                        @else
                                            <div
                                                class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-lg">
                                                👤
                                            </div>
                                        @endif

                                        <div>

                                            <div class="font-semibold text-gray-800 dark:text-white">
                                                {{ $hoSo->ho }} {{ $hoSo->ten }}
                                            </div>

                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                📧 {{ $nguoiDung->email ?? '---' }}
                                            </div>

                                            {{-- STATUS --}}
                                            <div class="mt-1">
                                                @if ($trangThai === 0)
                                                    <span class="text-xs px-2 py-1 bg-red-100 text-red-600 rounded-full">
                                                        ⛔ Đã nghỉ việc
                                                    </span>
                                                @else
                                                    <span
                                                        class="text-xs px-2 py-1 bg-green-100 text-green-600 rounded-full">
                                                        ✅ Đang làm việc
                                                    </span>
                                                @endif
                                            </div>

                                        </div>

                                    </div>

                                </td>

                                {{-- MÃ NV --}}
                                <td class="p-3 text-sm">
                                    {{ $hoSo->ma_nhan_vien ?? '---' }}
                                </td>

                                {{-- CHỨC VỤ --}}
                                <td class="p-3 text-sm">
                                    {{ $nguoiDung->chuc_vu->ten ?? '---' }}
                                </td>

                                {{-- PHÒNG BAN --}}
                                <td class="p-3 text-sm">
                                    {{ $nguoiDung->phong_ban->ten_phong_ban ?? '---' }}
                                </td>

                                {{-- SĐT --}}
                                <td class="p-3 text-sm">
                                    {{ $hoSo->so_dien_thoai ?? '---' }}
                                </td>

                                {{-- ACTION --}}
                                <td class="px-4 py-3">
                                    <div class="flex justify-center gap-1.5">

                                        {{-- Xem chi tiết --}}
                                        <a href="{{ route('admin.ho-so.show', $hoSo->id) }}"
                                            class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                            title="Xem chi tiết">

                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                </path>

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>

                                            </svg>

                                        </a>

                                        {{-- Chỉnh sửa --}}
                                        <a href="{{ route('admin.ho-so.edit', $hoSo->id) }}"
                                            class="p-1.5 text-yellow-600 hover:bg-yellow-50 rounded-lg transition"
                                            title="Chỉnh sửa">

                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>

                                            </svg>

                                        </a>

                                        {{-- Nghỉ việc --}}
                                        @if ($trangThai === 1)
                                            <form method="POST" action="{{ route('admin.ho-so.resign', $hoSo->id) }}"
                                                onsubmit="return confirm('Xác nhận cho nhân viên {{ $hoSo->ho }} {{ $hoSo->ten }} nghỉ việc?')">

                                                @csrf

                                                <button type="submit"
                                                    class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition"
                                                    title="Cho nghỉ việc">

                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">

                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7L5 7M10 11V17M14 11V17M6 7L7 19C7.1 20.1 7.9 21 9 21H15C16.1 21 16.9 20.1 17 19L18 7M9 7V5C9 3.9 9.9 3 11 3H13C14.1 3 15 3.9 15 5V7">
                                                        </path>

                                                    </svg>

                                                </button>

                                            </form>
                                        @else
                                            {{-- Kích hoạt --}}
                                            <form method="POST" action="{{ route('admin.ho-so.activate', $hoSo->id) }}"
                                                onsubmit="return confirm('Kích hoạt lại nhân viên {{ $hoSo->ho }} {{ $hoSo->ten }}?')">

                                                @csrf

                                                <button type="submit"
                                                    class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg transition"
                                                    title="Kích hoạt lại">

                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">

                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7">
                                                        </path>

                                                    </svg>

                                                </button>

                                            </form>
                                        @endif

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center py-10 text-gray-500 dark:text-gray-400">
                                    Không có dữ liệu nhân viên
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- PAGINATION --}}
            <div class="mt-5">
                {{ $hoSos->appends(request()->query())->links() }}
            </div>

        </div>

    </div>
@endsection
