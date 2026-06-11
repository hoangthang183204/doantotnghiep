@extends('layouts.admin')

@section('title', 'Sửa vai trò')

@section('content')

    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                        ✏️ Sửa vai trò
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Cập nhật thông tin vai trò: <strong>{{ $vaiTro->ten_hien_thi }}</strong>
                    </p>
                </div>
                <a href="{{ route('admin.vai-tro.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                    ← Quay lại
                </a>
            </div>
        </div>

        {{-- ALERT nếu là vai trò hệ thống --}}
        @if ($vaiTro->la_vai_tro_he_thong)
            <div
                class="bg-yellow-100 dark:bg-yellow-900/30 border border-yellow-300 dark:border-yellow-700 text-yellow-700 dark:text-yellow-300 px-4 py-3 rounded-lg">
                ⚠️ Đây là vai trò hệ thống. Bạn chỉ có thể thay đổi trạng thái, không thể sửa mã hoặc tên hiển thị.
            </div>
        @endif

        {{-- FORM --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <form method="POST" action="{{ route('admin.vai-tro.update', $vaiTro->id) }}" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Mã vai trò (name) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Mã vai trò <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $vaiTro->name) }}"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed"
                        placeholder="VD: truong_phong_du_an" {{ $vaiTro->la_vai_tro_he_thong ? 'disabled' : '' }}
                        {{ $vaiTro->la_vai_tro_he_thong ? '' : 'required' }}>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        @if ($vaiTro->la_vai_tro_he_thong)
                            🔒 Mã vai trò hệ thống không thể sửa
                        @else
                            Mã duy nhất, chỉ chứa chữ thường, số và dấu gạch dưới
                        @endif
                    </p>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tên hiển thị --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Tên hiển thị <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="ten_hien_thi" value="{{ old('ten_hien_thi', $vaiTro->ten_hien_thi) }}"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"
                        {{ $vaiTro->la_vai_tro_he_thong ? 'disabled' : '' }}
                        {{ $vaiTro->la_vai_tro_he_thong ? '' : 'required' }}>
                    @if ($vaiTro->la_vai_tro_he_thong)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">🔒 Tên hiển thị của vai trò hệ thống không
                            thể sửa</p>
                    @endif
                    @error('ten_hien_thi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mô tả --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Mô tả
                    </label>
                    <textarea name="mo_ta" rows="4"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Mô tả chi tiết về vai trò này...">{{ old('mo_ta', $vaiTro->mo_ta) }}</textarea>
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
                        <option value="1" {{ old('trang_thai', $vaiTro->trang_thai) == 1 ? 'selected' : '' }}>✅ Hoạt
                            động</option>
                        <option value="0" {{ old('trang_thai', $vaiTro->trang_thai) == 0 ? 'selected' : '' }}>⛔ Khóa
                        </option>
                    </select>
                </div>

                {{-- Button --}}
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.vai-tro.index') }}"
                        class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-white transition">
                        Hủy bỏ
                    </a>
                    <button type="submit"
                        class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow transition">
                        💾 Cập nhật vai trò
                    </button>
                </div>

            </form>
        </div>

        {{-- THÔNG TIN NGƯỜI DÙNG THUỘC VAI TRÒ NÀY --}}
        @if ($vaiTro->nguoi_dungs_count > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <h3 class="text-md font-semibold text-gray-800 dark:text-white mb-3">
                    👥 Người dùng thuộc vai trò này ({{ $vaiTro->nguoi_dungs_count }})
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach ($vaiTro->nguoi_dungs as $nguoiDung)
                        <span
                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                            {{ $nguoiDung->ho_ten ?? $nguoiDung->ten_dang_nhap }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

@endsection
