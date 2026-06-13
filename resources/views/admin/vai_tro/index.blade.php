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
            <a href="{{ route('admin.vai-tro.create') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
               Thêm vai trò
            </a>
        </div>

        {{-- SEARCH --}}
        <div class="mt-4">
            <form method="GET" action="{{ route('admin.vai-tro.index') }}">
                <div class="flex gap-3">
                    <input type="text" name="keyword" value="{{ request('keyword') }}" 
                        placeholder="Tìm kiếm theo tên, mã vai trò..."
                        class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 dark:bg-gray-700 focus:ring-2 focus:ring-blue-500 outline-none">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                        🔍 Tìm kiếm
                    </button>
                    <a href="{{ route('admin.vai-tro.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
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
                                        
                                        {{-- Nút Sửa (Icon Bút chì) --}}
                                        <a href="{{ route('admin.vai-tro.edit', $vt->id) }}" 
                                            class="inline-flex items-center justify-center p-2 text-yellow-500 hover:text-yellow-600 hover:bg-yellow-50 rounded-full transition-all duration-200"
                                            title="Sửa">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </a>
                            
                                        {{-- Nút Xóa (Icon Thùng rác) --}}
                                        <form action="{{ route('admin.vai-tro.destroy', $vt->id) }}" 
                                                method="POST" 
                                                onsubmit="return confirm('Xóa vai trò {{ $vt->ten_hien_thi }}?')"
                                                class="m-0 p-0 flex items-center">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-full transition-all duration-200"
                                                    title="Xóa">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
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