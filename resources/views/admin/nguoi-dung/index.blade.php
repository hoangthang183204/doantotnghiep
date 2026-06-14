@extends('layouts.admin')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

            <div class="flex justify-between items-start">

                <div>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                        Quản lý tài khoản người dùng
                    </h1>

                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Danh sách tài khoản hệ thống, có thể tìm kiếm, lọc và quản lý trạng thái.
                    </p>
                </div>

                {{-- CREATE BUTTON --}}
                <a href="{{ route('admin.nguoi-dung.create') }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                    + Tạo tài khoản
                </a>

            </div>


            {{-- FILTER --}}
            <div class="mt-5 border-t border-gray-200 dark:border-gray-700 pt-4">

                <form method="GET" action="{{ route('admin.nguoi-dung.index') }}">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        {{-- SEARCH --}}
                        <input type="text" name="keyword" value="{{ request('keyword') }}"
                            placeholder="Tìm username, email..."
                            class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2">

                        {{-- PHÒNG BAN FILTER --}}
                        <select name="phong_ban_id"
                            class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2">

                            <option value="">-- Tất cả phòng ban --</option>

                            @foreach ($phongBans as $pb)
                                <option value="{{ $pb->id }}"
                                    {{ request('phong_ban_id') == $pb->id ? 'selected' : '' }}>
                                    {{ $pb->ten_phong_ban }}
                                </option>
                            @endforeach

                        </select>

                        {{-- TRẠNG THÁI --}}
                        <select name="trang_thai"
                            class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2">

                            <option value="">-- Tất cả trạng thái --</option>
                            <option value="1" {{ request('trang_thai') === '1' ? 'selected' : '' }}>
                                Hoạt động
                            </option>
                            <option value="0" {{ request('trang_thai') === '0' ? 'selected' : '' }}>
                                Khóa
                            </option>

                        </select>

                    </div>

                    <div class="flex gap-3 mt-4">

                        <button type="submit"
                            class="bg-blue-700 hover:bg-blue-800 text-white px-5 py-2 rounded-lg transition">
                            🔍 Lọc
                        </button>

                        <a href="{{ route('admin.nguoi-dung.index') }}"
                            class="bg-cyan-500 hover:bg-cyan-600 text-white px-5 py-2 rounded-lg transition">
                            ↻ Reset
                        </a>

                    </div>

                </form>

            </div>

        </div>

        {{-- ALERT SUCCESS --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        {{-- TABLE --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

            <div class="overflow-x-auto">

                <table class="min-w-full text-gray-700 dark:text-gray-200">

                    <thead>
                        <tr
                            class="text-left text-sm text-gray-600 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">

                            <th class="p-3">NGƯỜI DÙNG</th>
                            <th class="p-3">EMAIL</th>
                            <th class="p-3">VAI TRÒ</th>
                            <th class="p-3">PHÒNG BAN</th>
                            <th class="p-3">TRẠNG THÁI</th>
                            <th class="p-3 text-center">THAO TÁC</th>

                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($users as $user)
                            <tr
                                class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                                {{-- USER --}}
                                <td class="p-3">
                                    <div class="flex items-start gap-3">

                                        <div
                                            class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                            👤
                                        </div>

                                        <div>
                                            <div class="font-semibold text-gray-800 dark:text-white">
                                                {{ $user->ho_ten }}
                                            </div>

                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                🆔 {{ $user->id }}
                                            </div>
                                        </div>

                                    </div>
                                </td>

                                {{-- EMAIL --}}
                                <td class="p-3 text-sm">
                                    {{ $user->email }}
                                </td>

                                {{-- ROLE --}}
                                <td class="p-3 text-sm">
                                    {{ $user->vai_tro->ten_hien_thi ?? '---' }}
                                </td>

                                {{-- PHÒNG BAN --}}
                                <td class="p-3 text-sm">
                                    {{ $user->phong_ban->ten_phong_ban ?? '---' }}
                                </td>

                                {{-- STATUS --}}
                                <td class="p-3">

                                    @if ($user->trang_thai == 1)
                                        <span class="text-xs px-2 py-1 bg-green-100 text-green-600 rounded-full">
                                            ✅ Hoạt động
                                        </span>
                                    @else
                                        <span class="text-xs px-2 py-1 bg-red-100 text-red-600 rounded-full">
                                            ⛔ Khóa
                                        </span>
                                    @endif

                                </td>

                                {{-- ACTION --}}
                                <td class="px-4 py-3">

                                    <div class="flex justify-center gap-1.5">

                                        {{-- Chỉnh sửa --}}
                                        <a href="{{ route('admin.nguoi-dung.edit', $user->id) }}"
                                            class="p-1.5 text-yellow-600 hover:bg-yellow-50 rounded-lg transition"
                                            title="Chỉnh sửa">

                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>

                                            </svg>

                                        </a>

                                        {{-- Xóa --}}
                                        <form action="{{ route('admin.nguoi-dung.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Xóa người dùng này?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition"
                                                title="Xóa">

                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7L5 7M10 11V17M14 11V17M6 7L7 19C7.1 20.1 7.9 21 9 21H15C16.1 21 16.9 20.1 17 19L18 7M9 7V5C9 3.9 9.9 3 11 3H13C14.1 3 15 3.9 15 5V7">
                                                    </path>

                                                </svg>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-gray-500 dark:text-gray-400">
                                    Không có dữ liệu người dùng
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- PAGINATION --}}
            <div class="mt-5">
                {{ $users->appends(request()->query())->links() }}
            </div>

        </div>

    </div>
@endsection
