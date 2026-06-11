@extends('layouts.admin')

@section('content')
<div class="p-6 w-full mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Chi tiết: {{ $loaiNghi->ten_loai_nghi_phep }}</h2>
                <span class="inline-flex items-center px-3 py-1 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-mono text-xs font-bold border border-blue-100 dark:border-blue-800">
                    {{ $loaiNghi->ma }}
                </span>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Xem chi tiết toàn bộ cấu hình thuộc tính của danh mục.</p>
        </div>
        <a href="{{ route('admin.loai-nghi-phep.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl text-sm font-semibold transition">
            ← Danh sách loại nghỉ
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
            <h3 class="font-bold text-gray-900 dark:text-white text-base">Thông tin cấu hình chi tiết</h3>
        </div>
        
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-4 p-5 items-center gap-2">
                <div class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mã cấu hình</div>
                <div class="text-base text-gray-900 dark:text-white font-mono bg-slate-50 dark:bg-gray-900 px-3 py-1.5 rounded-lg border w-fit">{{ $loaiNghi->ma }}</div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 p-5 items-center gap-2">
                <div class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tên loại nghỉ phép</div>
{{-- Thay thế bằng trường 'ten' hoặc dùng toán tử ?? để check phòng hờ --}}
<div class="text-lg text-gray-900 dark:text-white font-bold md:col-span-3">{{ $loaiNghi->ten ?? $loaiNghi->ten_loai_nghi_phep }}</div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 p-5 items-center gap-2">
                <div class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Chế độ tính lương</div>
                <div class="md:col-span-3">
                    @if($loaiNghi->co_luong)
                        <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800">✓ Có hưởng lương</span>
                    @else
                        <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800">✕ Không hưởng lương</span>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 p-5 items-center gap-2">
                <div class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái hoạt động</div>
                <div class="md:col-span-3">
                    @if($loaiNghi->trang_thai)
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Đang hoạt động áp dụng
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                            <span class="w-2 h-2 rounded-full bg-gray-400"></span> Đang tạm khóa hệ thống
                        </span>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 p-5 items-center gap-2">
                <div class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày khởi tạo danh mục</div>
                <div class="text-sm text-gray-700 dark:text-gray-300 md:col-span-3 font-medium">{{ $loaiNghi->created_at ? $loaiNghi->created_at->format('d/m/Y \l\ú\c H:i') : 'N/A' }}</div>
            </div>
        </div>

        <div class="p-5 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-2 border-t border-gray-100 dark:border-gray-700">
            <a href="{{ route('admin.loai-nghi-phep.edit', $loaiNghi->id) }}" class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition shadow-md shadow-amber-100 dark:shadow-none transform hover:-translate-y-0.5">
                Chỉnh sửa thông tin ngay
            </a>
        </div>
    </div>
</div>
@endsection