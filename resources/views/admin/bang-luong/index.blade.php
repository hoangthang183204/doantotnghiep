@extends('layouts.admin')

@section('title', 'Danh sách bảng lương')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Danh sách bảng lương</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Quản lý bảng lương theo tháng</p>
        </div>
        <a href="{{ route('admin.bang-luong.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tính lương mới
        </a>
    </div>
    
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr class="border-b dark:border-gray-700">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-500 dark:text-gray-400">Mã bảng lương</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-500 dark:text-gray-400">Tháng/Năm</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-500 dark:text-gray-400">Số nhân viên</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-500 dark:text-gray-400">Tổng lương</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-500 dark:text-gray-400">Trạng thái</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-500 dark:text-gray-400">Người xử lý</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-500 dark:text-gray-400">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bangLuongs as $bl)
                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <td class="py-3 px-4 text-gray-900 dark:text-white">{{ $bl->ma_bang_luong }}</td>
                        <td class="py-3 px-4 text-gray-600 dark:text-gray-400">Tháng {{ $bl->thang }}/{{ $bl->nam }}</td>
                        <td class="py-3 px-4 text-gray-600 dark:text-gray-400">{{ $bl->luongNhanViens->count() }}</td>
                        <td class="py-3 px-4 text-gray-600 dark:text-gray-400">{{ number_format($bl->luongNhanViens->sum('tong_luong')) }} đ</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($bl->trang_thai == 'dang_tao') bg-yellow-100 text-yellow-800
                                @elseif($bl->trang_thai == 'cho_duyet') bg-blue-100 text-blue-800
                                @elseif($bl->trang_thai == 'da_duyet') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $bl->trang_thai_text }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-gray-600 dark:text-gray-400">{{ $bl->nguoiXuLy->ten_dang_nhap ?? 'N/A' }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.bang-luong.show', $bl->id) }}" 
                                   class="text-blue-600 hover:text-blue-800" title="Xem chi tiết">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                @if($bl->trang_thai == 'dang_tao')
                                <form action="{{ route('admin.bang-luong.duyet', $bl->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="text-green-600 hover:text-green-800" title="Duyệt">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                </form>
                                <form action="{{ route('admin.bang-luong.destroy', $bl->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-500 dark:text-gray-400">
                            Chưa có bảng lương nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $bangLuongs->links() }}
        </div>
    </div>
</div>
@endsection