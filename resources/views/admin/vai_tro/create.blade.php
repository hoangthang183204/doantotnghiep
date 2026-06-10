@extends('layouts.admin')

@section('title', 'Thêm vai trò mới')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                    ➕ Thêm vai trò mới
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Tạo vai trò mới và phân quyền cho người dùng
                </p>
            </div>
            <a href="{{ route('admin.vai_tro.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                ← Quay lại
            </a>
        </div>
    </div>

    {{-- FORM --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.vai_tro.store') }}" class="space-y-5">
            @csrf

            {{-- Mã vai trò (name) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Mã vai trò <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       value="{{ old('name') }}"
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"
                       placeholder="VD: truong_phong_du_an"
                       required>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Mã duy nhất, chỉ chứa chữ thường, số và dấu gạch dưới</p>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tên hiển thị --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Tên hiển thị <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="ten_hien_thi" 
                       value="{{ old('ten_hien_thi') }}"
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"
                       placeholder="VD: Trưởng phòng dự án"
                       required>
                @error('ten_hien_thi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Mô tả --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Mô tả
                </label>
                <textarea name="mo_ta" 
                          rows="4"
                          class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"
                          placeholder="Mô tả chi tiết về vai trò này...">{{ old('mo_ta') }}</textarea>
                @error('mo_ta')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Trạng thái --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Trạng thái
                </label>
                <select name="trang_thai" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="1" {{ old('trang_thai') == 1 ? 'selected' : '' }}>✅ Hoạt động</option>
                    <option value="0" {{ old('trang_thai') == 0 ? 'selected' : '' }}>⛔ Khóa</option>
                </select>
            </div>

            {{-- Button --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.vai_tro.index') }}" 
                   class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-white transition">
                    Hủy bỏ
                </a>
                <button type="submit" 
                        class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow transition">
                    💾 Lưu vai trò
                </button>
            </div>

        </form>
    </div>

</div>

@endsection