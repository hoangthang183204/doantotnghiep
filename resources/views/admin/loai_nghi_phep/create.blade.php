@extends('layouts.admin')

@section('content')
<div class="p-6 w-full mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Tạo loại nghỉ phép mới</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Thêm mới danh mục loại nghỉ phép vào hệ thống cấu hình HR Flow.</p>
        </div>
        <a href="{{ route('admin.loai-nghi-phep.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl text-sm font-semibold transition-all shadow-sm">
            ← Quay lại danh sách
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
            <h3 class="font-bold text-gray-900 dark:text-white text-base">Thông tin danh mục</h3>
        </div>

        <form action="{{ route('admin.loai-nghi-phep.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            {{-- Thiết kế 2 hàng rộng rãi --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Mã loại nghỉ <span class="text-red-500">*</span></label>
                    <input type="text" name="ma" value="{{ old('ma') }}" placeholder="Ví dụ: PHEP_NAM" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 outline-none transition @error('ma') border-red-500 @enderror">
                    @error('ma') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Tên loại nghỉ phép <span class="text-red-500">*</span></label>
                    <input type="text" name="ten" value="{{ old('ten') }}" placeholder="Ví dụ: Nghỉ phép năm" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 outline-none transition @error('ten') border-red-500 @enderror">
                    @error('ten') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Chế độ lương</label>
                    <select name="co_luong" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 outline-none transition font-medium">
                        <option value="1" {{ old('co_luong') == '1' ? 'selected' : '' }}>✓ Có lương</option>
                        <option value="0" {{ old('co_luong') == '0' ? 'selected' : '' }}>✕ Không lương</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Trạng thái hệ thống</label>
                    <select name="trang_thai" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 outline-none transition font-medium">
                        <option value="1" {{ old('trang_thai') == '1' ? 'selected' : '' }}>● Hoạt động</option>
                        <option value="0" {{ old('trang_thai') == '0' ? 'selected' : '' }}>● Tạm khóa</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                <button type="reset" class="px-5 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-200 rounded-xl text-sm font-semibold transition">
                    Nhập lại từ đầu
                </button>
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition shadow-md shadow-blue-200 dark:shadow-none transform hover:-translate-y-0.5">
                    Lưu dữ liệu hệ thống
                </button>
            </div>
        </form>
    </div>
</div>
@endsection