@extends('layouts.admin')

@section('title', 'Quản lý khuôn mặt')

@section('content')
<div class="space-y-6">
    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800/80 backdrop-blur border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">📸 Quản lý khuôn mặt nhân viên</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Đăng ký và quản lý khuôn mặt cho nhân viên</p>
            </div>
            <a href="{{ route('admin.cham-cong-face.create') }}" 
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Đăng ký khuôn mặt
            </a>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-xl flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-xl font-bold hover:opacity-70">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-xl flex items-center justify-between">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-xl font-bold hover:opacity-70">&times;</button>
        </div>
    @endif

    {{-- TABLE --}}
    <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr class="text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-4 py-3 text-center w-12">STT</th>
                        <th class="px-4 py-3">Nhân viên</th>
                        <th class="px-4 py-3">Mã NV</th>
                        <th class="px-4 py-3">Ảnh</th>
                        <th class="px-4 py-3">Trạng thái</th>
                        <th class="px-4 py-3">Ngày đăng ký</th>
                        <th class="px-4 py-3 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($faceData as $index => $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                        <td class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @php
                                    $hoTen = optional($item->nguoiDung->hoSo)->ho . ' ' . optional($item->nguoiDung->hoSo)->ten;
                                    $avatar = optional($item->nguoiDung->hoSo)->anh_dai_dien 
                                        ? asset('storage/' . optional($item->nguoiDung->hoSo)->anh_dai_dien)
                                        : 'https://ui-avatars.com/api/?background=3b82f6&color=fff&name=' . urlencode($hoTen);
                                @endphp
                                <img src="{{ $avatar }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover ring-2 ring-gray-200 dark:ring-gray-600">
                                <div>
                                    <div class="font-medium text-gray-800 dark:text-white">{{ $hoTen ?: 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ optional($item->nguoiDung)->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ optional($item->nguoiDung->hoSo)->ma_nhan_vien ?? '---' }}</td>
                        <td class="px-4 py-3">
                            @if($item->image_path)
                                <img src="{{ asset('storage/' . $item->image_path) }}" 
                                     alt="Face" class="w-12 h-12 rounded-lg object-cover border border-gray-200 dark:border-gray-600">
                            @else
                                <span class="text-gray-400">---</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $item->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300' }}">
                                {{ $item->is_active ? '✅ Hoạt động' : '❌ Không hoạt động' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-1.5">
                                {{-- Nút xóa --}}
                                <form action="{{ route('admin.cham-cong-face.destroy', $item->id) }}" method="POST" 
                                      onsubmit="return confirm('🗑️ Bạn có chắc muốn xóa khuôn mặt này?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Xóa">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-500 dark:text-gray-400">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <p class="text-lg font-medium">Chưa có dữ liệu khuôn mặt</p>
                            <p class="text-sm mt-1 text-gray-400">Nhấn "Đăng ký khuôn mặt" để thêm mới</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($faceData->hasPages())
        <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $faceData->links() }}
        </div>
        @endif
    </div>
</div>
@endsection