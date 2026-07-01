<!-- resources/views/admin/tin-tuyen-dung/index.blade.php -->
@extends('layouts.admin')

@section('content')
    <div class="p-6 max-w-7xl mx-auto space-y-6">
        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Quản lý tin tuyển dụng
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">
                    Quản lý các tin tuyển dụng và theo dõi số lượng ứng viên
                </p>
            </div>
            <a href="{{ route('admin.tin-tuyen-dung.create') }}"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Thêm mới
            </a>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div
                class="p-4 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-100 dark:border-green-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div
                class="p-4 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border border-red-100 dark:border-red-800 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if (session('warning'))
            <div
                class="p-4 rounded-xl bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400 border border-yellow-100 dark:border-yellow-800 text-sm">
                {{ session('warning') }}
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-4">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <input type="text" name="keyword" value="{{ request('keyword') }}"
                        placeholder="Tìm kiếm tin tuyển dụng..."
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 outline-none text-sm">
                </div>
                <div>
                    <select name="status"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 outline-none text-sm">
                        <option value="">Tất cả trạng thái</option>
                        <option value="dang_dang" {{ request('status') == 'dang_dang' ? 'selected' : '' }}>Đang đăng
                        </option>
                        <option value="da_dung" {{ request('status') == 'da_dung' ? 'selected' : '' }}>Đã dừng</option>
                    </select>
                </div>
                <div>
                    <select name="phong_ban_id"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 outline-none text-sm">
                        <option value="">Tất cả phòng ban</option>
                        @foreach ($phongBans as $phongBan)
                            <option value="{{ $phongBan->id }}"
                                {{ request('phong_ban_id') == $phongBan->id ? 'selected' : '' }}>
                                {{ $phongBan->ten_phong_ban }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button
                        class="w-full px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold transition text-sm">
                        Tìm kiếm
                    </button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 text-xs uppercase">
                            <th class="px-5 py-4 text-left">Tiêu đề</th>
                            <th class="px-5 py-4 text-left">Vị trí</th>
                            <th class="px-5 py-4 text-left">Phòng ban</th>
                            <th class="px-5 py-4 text-center">Số lượng</th>
                            <th class="px-5 py-4 text-center">Ứng viên</th>
                            <th class="px-5 py-4 text-center">Trạng thái</th>
                            <th class="px-5 py-4 text-center">Hạn nộp</th>
                            <th class="px-5 py-4 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($tinTuyenDungs as $item)
                            <tr class="hover:bg-blue-50/40 dark:hover:bg-gray-700/40 transition">
                                <td class="px-5 py-4">
                                    <a href="{{ route('admin.tin-tuyen-dung.show', $item->id) }}"
                                        class="font-semibold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                                        {{ $item->tieu_de }}
                                    </a>
                                </td>
                                <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $item->vi_tri }}</td>
                                <td class="px-5 py-4 text-gray-600 dark:text-gray-300">
                                    {{ $item->phongBan?->ten_phong_ban ?? 'Chưa xác định' }}
                                </td>
                                <td class="px-5 py-4 text-center font-semibold text-gray-900 dark:text-white">
                                    {{ $item->so_luong }}
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span
                                        class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 text-xs font-semibold">
                                        {{ $item->ungViens->count() }}
                                    </span>
                                </td>
                                <!-- Tìm dòng có thẻ td chứa trạng thái và thay bằng -->
                                <td class="px-5 py-4 text-center">
                                    @switch($item->trang_thai)
                                        @case('nhap')
                                            <span
                                                class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400 text-xs font-semibold">
                                                Nháp
                                            </span>
                                        @break

                                        @case('dang_tuyen')
                                            <span
                                                class="px-3 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs font-semibold">
                                                Đang tuyển
                                            </span>
                                        @break

                                        @case('tam_dung')
                                            <span
                                                class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 text-xs font-semibold">
                                                Tạm dừng
                                            </span>
                                        @break

                                        @case('ket_thuc')
                                            <span
                                                class="px-3 py-1 rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 text-xs font-semibold">
                                                Kết thúc
                                            </span>
                                        @break

                                        @default
                                            <span
                                                class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400 text-xs font-semibold">
                                                {{ $item->trang_thai }}
                                            </span>
                                    @endswitch
                                </td>
                                <td class="px-5 py-4 text-center text-sm text-gray-600 dark:text-gray-300">
                                    {{ $item->han_nop_ho_so ? $item->han_nop_ho_so->format('d/m/Y') : '---' }}
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.tin-tuyen-dung.show', $item->id) }}"
                                            class="px-3 py-1.5 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-medium hover:bg-blue-100 dark:hover:bg-blue-900/50 transition">
                                            Xem
                                        </a>
                                        <a href="{{ route('admin.tin-tuyen-dung.edit', $item->id) }}"
                                            class="px-3 py-1.5 rounded-lg bg-yellow-50 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 text-xs font-medium hover:bg-yellow-100 dark:hover:bg-yellow-900/50 transition">
                                            Sửa
                                        </a>
                                        <form action="{{ route('admin.tin-tuyen-dung.destroy', $item->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa tin tuyển dụng này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1.5 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs font-medium hover:bg-red-100 dark:hover:bg-red-900/50 transition">
                                                Xóa
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-10 text-gray-500 dark:text-gray-400">
                                        Không tìm thấy tin tuyển dụng nào
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $tinTuyenDungs->links() }}
                </div>
            </div>
        </div>
    @endsection
