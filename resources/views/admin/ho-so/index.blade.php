@extends('layouts.admin')

@section('title', 'Hồ sơ nhân viên')

@section('content')

    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

            <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                Quản lý danh sách nhân sự
            </h1>

            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Thông tin nhân sự được hiển thị bên dưới. Có thể tìm kiếm, xem hoặc chỉnh sửa.
            </p>

            {{-- SEARCH & FILTER --}}
            <div class="mt-5 border-t border-gray-200 dark:border-gray-700 pt-4">

                <form method="GET" action="{{ route('admin.ho-so.index') }}">

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                        <input type="text" name="keyword" value="{{ request('keyword') }}"
                            placeholder="Tìm họ, tên, mã NV, SĐT, CCCD..."
                            class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2 outline-none">

                        <select name="trang_thai"
                            class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2 outline-none">
                            <option value="">Tất cả trạng thái</option>
                            <option value="1" {{ request('trang_thai') == '1' ? 'selected' : '' }}>Đang làm việc
                            </option>
                            <option value="0" {{ request('trang_thai') == '0' ? 'selected' : '' }}>Đã nghỉ việc
                            </option>
                        </select>

                        <select name="phong_ban_id"
                            class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2 outline-none">
                            <option value="">Tất cả phòng ban</option>
                            @foreach ($phongBans ?? [] as $phongBan)
                                <option value="{{ $phongBan->id }}"
                                    {{ request('phong_ban_id') == $phongBan->id ? 'selected' : '' }}>
                                    {{ $phongBan->ten_phong_ban }}
                                </option>
                            @endforeach
                        </select>

                        <div class="flex gap-3">
                            <button type="submit"
                                class="bg-blue-700 hover:bg-blue-800 text-white px-5 py-2 rounded-lg transition w-full">
                                🔍 Tìm kiếm
                            </button>
                            <a href="{{ route('admin.ho-so.index') }}"
                                class="bg-cyan-500 hover:bg-cyan-600 text-white px-5 py-2 rounded-lg transition text-center w-full">
                                ↻ Làm mới
                            </a>
                        </div>

                    </div>

                </form>

            </div>

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

                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

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

                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

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

                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
