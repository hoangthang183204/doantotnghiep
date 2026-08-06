@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">

    {{-- Header Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Thêm loại nghỉ phép mới</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Thêm mới danh mục loại nghỉ phép vào hệ thống.</p>
            </div>
        </div>
        <a href="{{ route('admin.loai-nghi-phep.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Quay lại
        </a>
    </div>

    {{-- Form --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
        <form action="{{ route('admin.loai-nghi-phep.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Mã --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Mã loại nghỉ <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="ma" value="{{ old('ma') }}" placeholder="VD: PHEP_NAM" 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-green-500 focus:ring-2 focus:ring-green-200 dark:focus:ring-green-800 outline-none transition @error('ma') border-red-500 @enderror">
                    @error('ma') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                {{-- Tên --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Tên loại nghỉ phép <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="ten" value="{{ old('ten') }}" placeholder="VD: Nghỉ phép năm" 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-green-500 focus:ring-2 focus:ring-green-200 dark:focus:ring-green-800 outline-none transition @error('ten') border-red-500 @enderror">
                    @error('ten') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                {{-- Mô tả --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Mô tả <span class="text-xs font-normal text-gray-400">(Tùy chọn)</span>
                    </label>
                    <textarea name="mo_ta" rows="2" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-green-500 focus:ring-2 focus:ring-green-200 dark:focus:ring-green-800 outline-none transition" placeholder="Mô tả ngắn về loại nghỉ phép này...">{{ old('mo_ta') }}</textarea>
                </div>

                {{-- Có lương --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Chế độ lương
                    </label>
                    <select name="co_luong" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-green-500 focus:ring-2 focus:ring-green-200 dark:focus:ring-green-800 outline-none transition font-medium">
                        <option value="1" {{ old('co_luong') == '1' ? 'selected' : '' }}>Có lương</option>
                        <option value="0" {{ old('co_luong') == '0' ? 'selected' : '' }}>Không lương</option>
                    </select>
                </div>

                {{-- Trạng thái --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Trạng thái
                    </label>
                    <select name="trang_thai" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-green-500 focus:ring-2 focus:ring-green-200 dark:focus:ring-green-800 outline-none transition font-medium">
                        <option value="1" {{ old('trang_thai') == '1' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ old('trang_thai') == '0' ? 'selected' : '' }}>Tạm khóa</option>
                    </select>
                </div>

                {{-- Quy tắc áp dụng --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Quy tắc áp dụng <span class="text-xs font-normal text-gray-400">(Tùy chọn)</span>
                    </label>
                    <textarea name="quy_tac" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-green-500 focus:ring-2 focus:ring-green-200 dark:focus:ring-green-800 outline-none transition" placeholder="Ví dụ: Được nghỉ tối đa 12 ngày/năm, cần có giấy xác nhận của bác sĩ nếu nghỉ >= 3 ngày liên tiếp...">{{ old('quy_tac') }}</textarea>
                </div>
            </div>

            {{-- Alert Info --}}
            <div class="mt-4 p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 flex items-start gap-3">
                <div class="mt-0.5 text-blue-600 dark:text-blue-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-blue-800 dark:text-blue-300">Lưu ý quan trọng</p>
                    <p class="text-sm text-blue-700 dark:text-blue-400">Mã loại nghỉ phép không thể thay đổi sau khi tạo. Vui lòng kiểm tra kỹ trước khi lưu.</p>
                </div>
            </div>

            {{-- Footer Buttons --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('admin.loai-nghi-phep.index') }}" class="px-5 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium transition">
                    Hủy
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-green-600 hover:bg-green-700 text-white text-sm font-medium transition shadow-md shadow-green-200 dark:shadow-none flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    Lưu dữ liệu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection