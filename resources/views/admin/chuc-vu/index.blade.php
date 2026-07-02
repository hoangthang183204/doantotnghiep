@extends('layouts.admin')

@section('title', 'Quản lý chức vụ')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Quản lý chức vụ
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Danh sách chức vụ trong hệ thống
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.chuc-vu.org-chart') }}"
                   class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition">
                    <i class="fas fa-sitemap mr-1"></i> Sơ đồ
                </a>
                <a href="{{ route('admin.chuc-vu.statistics') }}"
                   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                    <i class="fas fa-chart-bar mr-1"></i> Thống kê
                </a>
                <a href="{{ route('admin.chuc-vu.create') }}"
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    <i class="fas fa-plus mr-1"></i> Thêm chức vụ
                </a>
            </div>
        </div>
    </div>

    {{-- THÔNG BÁO --}}
    @if (session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-800 dark:text-gray-100">ID</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-800 dark:text-gray-100">Tên chức vụ</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-800 dark:text-gray-100">Mã</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-800 dark:text-gray-100">Phòng ban</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-800 dark:text-gray-100">Số NV</th> <!-- ✅ Thêm -->
                        <th class="px-4 py-3 text-left font-semibold text-gray-800 dark:text-gray-100">Trạng thái</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-800 dark:text-gray-100">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($chucVus as $chucVu)
                        <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $chucVu->id }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-white">
                                <a href="{{ route('admin.chuc-vu.show', $chucVu->id) }}" 
                                   class="hover:text-blue-600 dark:hover:text-blue-400 transition">
                                    {{ $chucVu->ten }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-xs font-mono">
                                    {{ $chucVu->ma }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                {{ $chucVu->phongBan->ten_phong_ban ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">
                                <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-full text-xs font-semibold">
                                    {{ $chucVu->nguoi_dungs_count }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if ($chucVu->trang_thai)
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300">
                                        <i class="fas fa-circle text-[6px] mr-1"></i> Hoạt động
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300">
                                        <i class="fas fa-circle text-[6px] mr-1"></i> Ngừng hoạt động
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center gap-1.5">
                                    <a href="{{ route('admin.chuc-vu.show', $chucVu->id) }}"
                                       class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" 
                                       title="Xem chi tiết">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.chuc-vu.edit', $chucVu->id) }}"
                                       class="p-1.5 text-yellow-600 hover:bg-yellow-50 rounded-lg transition" 
                                       title="Sửa">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    
                                    <form action="{{ route('admin.chuc-vu.destroy', $chucVu->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        @if($chucVu->trang_thai == 1)
                                            <button type="submit"
                                                    onclick="return confirm('Bạn có chắc muốn ẩn chức vụ này?')"
                                                    class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition" 
                                                    title="Ẩn chức vụ">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                                </svg>
                                            </button>
                                        @else
                                            <button type="submit"
                                                    onclick="return confirm('Bạn có chắc muốn hiển thị lại chức vụ này?')"
                                                    class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" 
                                                    title="Hiển thị lại">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <span>Chưa có dữ liệu chức vụ</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4">
        {{ $chucVus->links() }}
    </div>

</div>
@endsection