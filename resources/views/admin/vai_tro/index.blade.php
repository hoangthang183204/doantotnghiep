@extends('layouts.admin')

@section('title', 'Quản lý Vai trò')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
        <div class="flex justify-between items-start flex-wrap gap-3">
            <div>
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                    📋 Quản lý vai trò
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Phân quyền và quản lý các vai trò trong hệ thống
                </p>
            </div>
            <a href="{{ route('admin.vai_tro.create') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
               Thêm vai trò
            </a>
        </div>

        {{-- SEARCH --}}
        <div class="mt-4">
            <form method="GET" action="{{ route('admin.vai_tro.index') }}">
                <div class="flex gap-3">
                    <input type="text" name="keyword" value="{{ request('keyword') }}" 
                        placeholder="Tìm kiếm theo tên, mã vai trò..."
                        class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 dark:bg-gray-700 focus:ring-2 focus:ring-blue-500 outline-none">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                        🔍 Tìm kiếm
                    </button>
                    <a href="{{ route('admin.vai_tro.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                        ↻ Làm mới
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-gray-700 dark:text-gray-200">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <th class="px-4 py-3 text-center w-12">STT</th>
                        <th class="px-4 py-3 text-left">Vai trò</th>
                        <th class="px-4 py-3 text-left">Mô tả</th>
                        <th class="px-4 py-3 text-center">Phân loại</th>
                        <th class="px-4 py-3 text-center">Số người dùng</th>
                        <th class="px-4 py-3 text-center">Trạng thái</th>
                        <th class="px-4 py-3 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vaiTros as $index => $vt)
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-4 py-3 text-center font-medium">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-800 dark:text-white">
                                    {{ $vt->ten_hien_thi }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 font-mono mt-0.5">
                                    {{ $vt->name }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                {{ $vt->mo_ta ?: '—' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($vt->la_vai_tro_he_thong)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                                        🛡️ Hệ thống
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                        ⚙️ Tùy chỉnh
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center justify-center min-w-[32px] px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                    {{ $vt->nguoi_dungs_count ?? 0 }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($vt->trang_thai == 1)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                        ● Hoạt động
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                        ● Khóa
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if(!$vt->la_vai_tro_he_thong)
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.vai_tro.edit', $vt->id) }}" 
                                           class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300 transition"
                                           title="Sửa">
                                            ✏️
                                        </a>
                                        <form action="{{ route('admin.vai_tro.destroy', $vt->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Xóa vai trò {{ $vt->ten_hien_thi }}?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition"
                                                    title="Xóa">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 text-xs">🔒 Hệ thống</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-gray-400 dark:text-gray-500">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="text-4xl">📭</span>
                                    <span>Không có dữ liệu vai trò</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if(method_exists($vaiTros, 'links') && $vaiTros->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $vaiTros->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>

@endsection