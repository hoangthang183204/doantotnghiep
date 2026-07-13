{{-- resources/views/truong-phong/nhan-vien/index.blade.php --}}

@extends('layouts.admin')

@section('title', 'Danh sách nhân viên')

@section('content')
<div class="space-y-6">
    
    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-users mr-3 text-blue-600"></i>
                Danh sách nhân viên
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Phòng <span class="font-medium text-blue-600">{{ $phongBan->ten_phong_ban }}</span>
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-sm bg-gray-100 dark:bg-gray-700 px-3 py-1.5 rounded-lg text-gray-600 dark:text-gray-300">
                <i class="fas fa-users mr-1"></i> {{ $nhanViens->total() }} nhân viên
            </span>
        </div>
    </div>

    {{-- Bộ lọc --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="keyword" value="{{ request('keyword') }}" 
                    placeholder="Tìm kiếm tên, mã nhân viên..." 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                <i class="fas fa-search mr-1"></i> Tìm kiếm
            </button>
            <a href="{{ route('truong-phong.nhan-vien.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition">
                <i class="fas fa-redo mr-1"></i> Reset
            </a>
        </form>
    </div>

    {{-- Danh sách nhân viên --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">STT</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Mã NV</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Họ tên</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Chức vụ</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden md:table-cell">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Trạng thái</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @forelse($nhanViens as $index => $nv)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $nhanViens->firstItem() + $index }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                            {{ $nv->hoSo->ma_nhan_vien ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                @if($nv->hoSo && $nv->hoSo->anh_dai_dien)
                                    <img src="{{ asset('storage/' . $nv->hoSo->anh_dai_dien) }}" 
                                         class="w-8 h-8 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                        {{ substr($nv->hoSo->ten ?? $nv->ten_dang_nhap, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-white">
                                        {{ $nv->hoSo->ho ?? '' }} {{ $nv->hoSo->ten ?? $nv->ten_dang_nhap }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $nv->ten_dang_nhap }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                            {{ $nv->chucVu->ten ?? 'Chưa có' }}
                        </td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ $nv->email }}</td>
                        <td class="px-4 py-3">
                            @if($nv->trang_thai == 1)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                    Đang làm
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                    <span class="w-1.5 h-1.5 bg-gray-500 rounded-full mr-1.5"></span>
                                    Đã nghỉ
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('truong-phong.nhan-vien.show', $nv->id) }}" 
                                class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-medium transition" 
                                title="Xem chi tiết">
                                <i class="fas fa-eye mr-1"></i> Xem
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-users-slash text-4xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                <p class="font-medium">Không có nhân viên nào</p>
                                <p class="text-sm">Phòng ban chưa có nhân viên nào</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($nhanViens->hasPages())
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
            {{ $nhanViens->links() }}
        </div>
        @endif
    </div>
</div>
@endsection