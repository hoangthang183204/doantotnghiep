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

            {{-- SEARCH --}}
            <div class="mt-5 border-t border-gray-200 dark:border-gray-700 pt-4">

                <form method="GET" action="{{ route('admin.ho-so.index') }}">

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <input type="text" name="keyword" value="{{ request('keyword') }}"
                            placeholder="Tìm họ, tên, email..."
                            class="w-full md:w-1/2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2 outline-none">

                        <div class="flex gap-3">

                            <button type="submit"
                                class="bg-blue-700 hover:bg-blue-800 text-white px-5 py-2 rounded-lg transition">
                                🔍 Tìm kiếm
                            </button>

                            <a href="{{ route('admin.ho-so.index') }}"
                                class="bg-cyan-500 hover:bg-cyan-600 text-white px-5 py-2 rounded-lg transition">
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
                            <th class="p-3">NGÀY SINH</th>
                            <th class="p-3">GIỚI TÍNH</th>
                            <th class="p-3">ĐỊA CHỈ</th>
                            <th class="p-3 text-center">THAO TÁC</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($hoSos as $hoSo)
                            @php
                                $trangThai = (int) ($hoSo->trang_thai ?? 1);
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
                                                class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                                👤
                                            </div>
                                        @endif

                                        <div>

                                            <div class="font-semibold text-gray-800 dark:text-white">
                                                {{ $hoSo->ho }} {{ $hoSo->ten }}
                                            </div>

                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                📧 {{ $hoSo->email_cong_ty ?? '---' }}
                                            </div>

                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                🆔 {{ $hoSo->ma_nhan_vien ?? '---' }}
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
                                <td class="p-3 text-sm text-gray-700 dark:text-gray-200">
                                    {{ $hoSo->ma_nhan_vien ?? '---' }}
                                </td>

                                {{-- NGÀY SINH --}}
                                <td class="p-3 text-sm text-gray-700 dark:text-gray-200">
                                    {{ $hoSo->ngay_sinh ?? '---' }}
                                </td>

                                {{-- GIỚI TÍNH --}}
                                <td class="p-3 text-sm text-gray-700 dark:text-gray-200">
                                    {{ $hoSo->gioi_tinh == 'nam' ? 'Nam' : 'Nữ' }}
                                </td>

                                {{-- ĐỊA CHỈ --}}
                                <td class="p-3 text-sm text-gray-700 dark:text-gray-200">
                                    {{ $hoSo->dia_chi_hien_tai ?? '---' }}
                                </td>

                                {{-- ACTION --}}
                                <td class="p-3">

                                    <div class="flex justify-center gap-2">

                                        <a href="{{ route('admin.ho-so.show', $hoSo->id) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition">
                                            👁
                                        </a>

                                        <a href="{{ route('admin.ho-so.edit', $hoSo->id) }}"
                                            class="bg-yellow-400 hover:bg-yellow-500 text-white p-2 rounded-lg transition">
                                            ✏️
                                        </a>

                                        @if ($trangThai === 1)
                                            <form method="POST" action="{{ route('admin.ho-so.resign', $hoSo->id) }}">
                                                @csrf

                                                <button type="submit"
                                                    class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition"
                                                    onclick="return confirm('Xác nhận cho nhân viên nghỉ việc?')">
                                                    ⛔
                                                </button>

                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.ho-so.activate', $hoSo->id) }}">
                                                @csrf

                                                <button type="submit"
                                                    class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-lg transition"
                                                    onclick="return confirm('Kích hoạt lại nhân viên?')">
                                                    ✅
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
