@extends('layouts.admin')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                        Quản lý phòng ban
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Danh sách phòng ban trong hệ thống nhân sự
                    </p>
                </div>
                <a href="{{ route('admin.phong-ban.create') }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                    + Thêm phòng ban
                </a>
            </div>

            {{-- SEARCH --}}
            <div class="mt-4">
                <form method="GET" action="{{ route('admin.phong-ban.index') }}">
                    <div class="flex gap-3">
                        <input type="text" name="keyword" value="{{ request('keyword') }}"
                            placeholder="Tìm theo mã, tên phòng ban..."
                            class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 dark:bg-gray-700">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                            🔍 Tìm
                        </button>
                        <a href="{{ route('admin.phong-ban.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                            ↻ Reset
                        </a>
                    </div>
                </form>
            </div>

        </div>

        {{-- SUCCESS --}}
        @if (session('success'))
            <div class="px-4 py-3 rounded-lg bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="px-4 py-3 rounded-lg bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200">
                {{ session('error') }}
            </div>
        @endif

        {{-- TABLE CARD --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">

            <div class="overflow-x-auto">

                <table class="min-w-full text-sm text-gray-700 dark:text-gray-200">

                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                            <th class="p-3">MÃ PHÒNG</th>
                            <th class="p-3">TÊN PHÒNG BAN</th>
                            <th class="p-3">NGÂN SÁCH</th>
                            <th class="p-3">TRƯỞNG PHÒNG</th>
                            <th class="p-3">TRẠNG THÁI</th>
                            <th class="p-3 text-center">THAO TÁC</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($phongBans as $pb)
                            <tr
                                class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">

                                <td class="p-3 font-medium">
                                    {{ $pb->ma_phong_ban }}
                                </td>

                                <td class="p-3">
                                    {{ $pb->ten_phong_ban }}
                                </td>

                                <td class="p-3">
                                    {{ number_format($pb->ngan_sach, 0, ',', '.') }} đ
                                </td>

                                <td class="p-3">
                                    @if ($pb->truong_phong_id)
                                        @if ($pb->truong_phong && $pb->truong_phong->hoSo)
                                            {{ $pb->truong_phong->hoSo->ho }} {{ $pb->truong_phong->hoSo->ten }}
                                        @else
                                            {{ $pb->truong_phong->ten_dang_nhap ?? '---' }}
                                        @endif
                                    @else
                                        <span class="text-gray-400 italic">Chưa cập nhật</span>
                                    @endif
                                </td>

                                <td class="p-3">
                                    @if ($pb->trang_thai == 1)
                                        <span class="text-xs px-2 py-1 bg-green-100 text-green-600 rounded-full">
                                            ✅ Hoạt động
                                        </span>
                                    @else
                                        <span class="text-xs px-2 py-1 bg-red-100 text-red-600 rounded-full">
                                            ⛔ Tạm dừng
                                        </span>
                                    @endif
                                </td>

                                <td class="p-3 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.phong-ban.edit', $pb->id) }}"
                                            class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-lg text-xs transition">
                                            ✏️ Sửa
                                        </a>

                                        <form method="POST" action="{{ route('admin.phong-ban.destroy', $pb->id) }}"
                                            onsubmit="return confirm('Xóa phòng ban {{ $pb->ten_phong_ban }}? Dữ liệu liên quan cũng sẽ bị ảnh hưởng.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs transition">
                                                🗑️ Xóa
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center py-10 text-gray-500">
                                    Không có phòng ban nào
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- PAGINATION --}}
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $phongBans->appends(request()->query())->links() }}
            </div>

        </div>

    </div>
@endsection
