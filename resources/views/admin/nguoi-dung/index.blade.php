@extends('layouts.admin')

@section('title', 'Quản lý tài khoản')

@section('content')

<div class="space-y-6">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

        <div class="flex justify-between items-start">

            <div>
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                    Quản lý tài khoản
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Quản lý tài khoản và phân quyền người dùng trong hệ thống
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.nguoi-dung.sync') }}"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition text-sm flex items-center gap-2">
                    <i class="fas fa-sync-alt"></i>
                    Đồng bộ hồ sơ
                </a>
                <a href="{{ route('admin.nguoi-dung.create') }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition text-sm flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i>
                    Thêm tài khoản
                </a>
            </div>

        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- THỐNG KÊ --}}
    {{-- ========================================================= --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tổng tài khoản</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $users->total() }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <i class="fas fa-users text-xl text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Đang hoạt động</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $users->where('trang_thai', 1)->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <i class="fas fa-check-circle text-xl text-green-600 dark:text-green-400"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Đã khóa</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $users->where('trang_thai', 0)->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <i class="fas fa-lock text-xl text-red-600 dark:text-red-400"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Chưa có hồ sơ</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $users->filter(function($u) { return !$u->hoSo; })->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-xl text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- FILTER --}}
    {{-- ========================================================= --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

        <form method="GET" action="{{ route('admin.nguoi-dung.index') }}">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                {{-- SEARCH --}}
                <div class="md:col-span-2">
                    <input type="text" name="keyword" value="{{ request('keyword') }}"
                        placeholder="Tìm kiếm theo tên, email, mã nhân viên..."
                        class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                {{-- VAI TRÒ --}}
                <select name="vai_tro_id"
                    class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">-- Tất cả vai trò --</option>
                    @foreach ($vaiTros as $vt)
                        <option value="{{ $vt->id }}" {{ request('vai_tro_id') == $vt->id ? 'selected' : '' }}>
                            {{ $vt->ten_hien_thi }}
                        </option>
                    @endforeach
                </select>

                {{-- PHÒNG BAN --}}
                <select name="phong_ban_id"
                    class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">-- Tất cả phòng ban --</option>
                    @foreach ($phongBans as $pb)
                        <option value="{{ $pb->id }}" {{ request('phong_ban_id') == $pb->id ? 'selected' : '' }}>
                            {{ $pb->ten_phong_ban }}
                        </option>
                    @endforeach
                </select>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                {{-- TRẠNG THÁI --}}
                <select name="trang_thai"
                    class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="1" {{ request('trang_thai') === '1' ? 'selected' : '' }}>✅ Đang hoạt động</option>
                    <option value="0" {{ request('trang_thai') === '0' ? 'selected' : '' }}>⛔ Đã khóa</option>
                </select>

                <div class="flex gap-3">
                    <button type="submit"
                        class="bg-blue-700 hover:bg-blue-800 text-white px-5 py-2 rounded-lg transition">
                        🔍 Lọc
                    </button>
                    <a href="{{ route('admin.nguoi-dung.index') }}"
                        class="bg-cyan-500 hover:bg-cyan-600 text-white px-5 py-2 rounded-lg transition">
                        ↻ Reset
                    </a>
                    <span class="text-sm text-gray-500 dark:text-gray-400 ml-auto flex items-center">
                        <i class="fas fa-database mr-1"></i> {{ $users->total() }} tài khoản
                    </span>
                </div>
            </div>

        </form>

    </div>

    {{-- ========================================================= --}}
    {{-- ALERT --}}
    {{-- ========================================================= --}}
    @if (session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle text-green-500"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-xl font-bold hover:opacity-70">&times;</button>
        </div>
    @endif

    {{-- ========================================================= --}}
    {{-- TABLE --}}
    {{-- ========================================================= --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">

        <div class="overflow-x-auto">
            <table class="min-w-full text-gray-700 dark:text-gray-200">

                <thead>
                    <tr class="text-left text-sm text-gray-600 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <th class="p-3 w-10">
                            <input type="checkbox" id="check-all" class="rounded border-gray-300 dark:border-gray-600">
                        </th>
                        <th class="p-3">NGƯỜI DÙNG</th>
                        <th class="p-3 hidden md:table-cell">MÃ NV</th>
                        <th class="p-3 hidden lg:table-cell">EMAIL</th>
                        <th class="p-3">VAI TRÒ</th>
                        <th class="p-3 hidden md:table-cell">PHÒNG BAN</th>
                        <th class="p-3 text-center">TRẠNG THÁI</th>
                        <th class="p-3 text-center">THAO TÁC</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($users as $user)
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                            {{-- CHECKBOX --}}
                            <td class="p-3">
                                <input type="checkbox" class="row-check rounded border-gray-300 dark:border-gray-600" value="{{ $user->id }}">
                            </td>

                            {{-- NGƯỜI DÙNG --}}
                            <td class="p-3">
                                <div class="flex items-start gap-3">

                                    {{-- Avatar --}}
                                    @if ($user->hoSo && $user->hoSo->anh_dai_dien)
                                        <img src="{{ asset('storage/' . $user->hoSo->anh_dai_dien) }}" alt="Avatar"
                                            class="w-10 h-10 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($user->ho_ten, 0, 1)) }}
                                        </div>
                                    @endif

                                    <div>
                                        <div class="font-semibold text-gray-800 dark:text-white">
                                            {{ $user->ho_ten }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            @if ($user->hoSo && $user->hoSo->ma_nhan_vien)
                                                <span class="font-mono">📋 {{ $user->hoSo->ma_nhan_vien }}</span>
                                            @else
                                                <span class="text-yellow-500">⚠️ Chưa có mã</span>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </td>

                            {{-- MÃ NV --}}
                            <td class="p-3 text-sm hidden md:table-cell font-mono">
                                {{ $user->hoSo->ma_nhan_vien ?? '---' }}
                            </td>

                            {{-- EMAIL --}}
                            <td class="p-3 text-sm hidden lg:table-cell text-gray-500 dark:text-gray-400">
                                {{ $user->email }}
                            </td>

                            {{-- VAI TRÒ --}}
                            <td class="p-3 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                    {{ $user->vai_tro->ten_hien_thi ?? 'Chưa phân' }}
                                </span>
                            </td>

                            {{-- PHÒNG BAN --}}
                            <td class="p-3 text-sm hidden md:table-cell">
                                {{ $user->phong_ban->ten_phong_ban ?? '---' }}
                            </td>

                            {{-- TRẠNG THÁI --}}
                            <td class="p-3 text-center">
                                @if ($user->trang_thai == 1)
                                    <span class="text-xs px-2 py-1 bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400 rounded-full">
                                        ✅ Đang hoạt động
                                    </span>
                                @else
                                    <span class="text-xs px-2 py-1 bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 rounded-full">
                                        ⛔ Đã khóa
                                    </span>
                                @endif
                            </td>

                            {{-- THAO TÁC --}}
                            <td class="px-3 py-3">
                                <div class="flex justify-center gap-1.5">

                                    {{-- Xem chi tiết --}}
                                    <a href="{{ route('admin.nguoi-dung.show', $user->id) }}"
                                        class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                        title="Xem chi tiết">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>

                                    {{-- Sửa --}}
                                    <a href="{{ route('admin.nguoi-dung.edit', $user->id) }}"
                                        class="p-1.5 text-yellow-600 hover:bg-yellow-50 rounded-lg transition"
                                        title="Chỉnh sửa">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>

                                    {{-- Xóa --}}
                                    <form action="{{ route('admin.nguoi-dung.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Xóa tài khoản {{ $user->ho_ten }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition"
                                            title="Xóa">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7L5 7M10 11V17M14 11V17M6 7L7 19C7.1 20.1 7.9 21 9 21H15C16.1 21 16.9 20.1 17 19L18 7M9 7V5C9 3.9 9.9 3 11 3H13C14.1 3 15 3.9 15 5V7"></path>
                                            </svg>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-users text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                    <p class="text-lg font-medium">Chưa có tài khoản nào</p>
                                    <p class="text-sm">Nhấn "Thêm tài khoản" để tạo mới</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($users->hasPages())
            <div class="mt-5 px-5 pb-5">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif

    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkAll = document.getElementById('check-all');
        const rowChecks = document.querySelectorAll('.row-check');

        checkAll?.addEventListener('change', function() {
            rowChecks.forEach(cb => cb.checked = this.checked);
        });

        rowChecks.forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = Array.from(rowChecks).every(c => c.checked);
                checkAll.checked = allChecked;
            });
        });
    });
</script>
@endpush

@endsection