@extends('layouts.admin')

@section('title', 'Sửa vai trò')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            ✏️ Sửa vai trò
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Cập nhật thông tin vai trò: <strong>{{ $vaiTro->ten_hien_thi }}</strong>
        </p>
    </div>

    {{-- ALERT nếu là vai trò hệ thống --}}
    @if ($vaiTro->la_vai_tro_he_thong)
        <div class="bg-yellow-100 dark:bg-yellow-900/30 border border-yellow-300 dark:border-yellow-700 text-yellow-700 dark:text-yellow-300 px-4 py-4 rounded-xl shadow-sm">
            ⚠️ Đây là vai trò hệ thống. Bạn chỉ có thể thay đổi trạng thái, không thể sửa mã hoặc tên hiển thị.
        </div>
    @endif

    {{-- FORM CARD --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.vai-tro.update', $vaiTro->id) }}">
            @csrf
            @method('PUT')

            {{-- GRID 2 CỘT --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- MÃ VAI TRÒ --}}
                <div>
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Mã vai trò <span class="text-red-500">*</span>
                    </label>

                    <input type="text" 
                           name="name" 
                           value="{{ old('name', $vaiTro->name) }}"
                           placeholder="VD: truong_phong_du_an..."
                           class="w-full rounded-lg border @error('name') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror {{ $vaiTro->la_vai_tro_he_thong ? 'bg-gray-100 dark:bg-gray-700/50 text-gray-500 cursor-not-allowed' : 'bg-white dark:bg-gray-700 text-gray-800 dark:text-white' }} px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                           {{ $vaiTro->la_vai_tro_he_thong ? 'disabled' : 'required' }}>
                    
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
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

                {{-- TÊN HIỂN THỊ --}}
                <div>
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Tên hiển thị <span class="text-red-500">*</span>
                    </label>

                    <input type="text" 
                           name="ten_hien_thi" 
                           value="{{ old('ten_hien_thi', $vaiTro->ten_hien_thi) }}"
                           class="w-full rounded-lg border @error('ten_hien_thi') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror {{ $vaiTro->la_vai_tro_he_thong ? 'bg-gray-100 dark:bg-gray-700/50 text-gray-500 cursor-not-allowed' : 'bg-white dark:bg-gray-700 text-gray-800 dark:text-white' }} px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                           {{ $vaiTro->la_vai_tro_he_thong ? 'disabled' : 'required' }}>
                    
                    @if ($vaiTro->la_vai_tro_he_thong)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            🔒 Tên hiển thị của vai trò hệ thống không thể sửa
                        </p>
                    @endif

                    @error('ten_hien_thi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- TRẠNG THÁI --}}
                <div>
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Trạng thái
                    </label>

                    <select name="trang_thai"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                        <option value="1" {{ old('trang_thai', $vaiTro->trang_thai) == 1 ? 'selected' : '' }}>
                            ✅ Hoạt động
                        </option>
                        <option value="0" {{ old('trang_thai', $vaiTro->trang_thai) == 0 ? 'selected' : '' }}>
                            ⛔ Khóa
                        </option>
                    </select>
                </div>

                {{-- MÔ TẢ (Cho chiếm 2 cột) --}}
                <div class="md:col-span-2">
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Mô tả
                    </label>

                    <textarea name="mo_ta" 
                              rows="4"
                              placeholder="Mô tả chi tiết về vai trò này..."
                              class="w-full rounded-lg border @error('mo_ta') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">{{ old('mo_ta', $vaiTro->mo_ta) }}</textarea>
                    
                    @error('mo_ta')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- BUTTON --}}
            <div class="flex gap-3 mt-8">
                <button type="submit"
                        class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shadow-sm">
                    💾 Cập nhật vai trò
                </button>

                <a href="{{ route('admin.vai-tro.index') }}"
                   class="px-5 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-white rounded-lg transition">
                    Hủy bỏ
                </a>
            </div>

        </form>
    </div>

    {{-- THÔNG TIN NGƯỜI DÙNG THUỘC VAI TRÒ NÀY --}}
    @if ($vaiTro->nguoi_dungs_count > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">
                👥 Người dùng thuộc vai trò này ({{ $vaiTro->nguoi_dungs_count }})
            </h3>
            
            <div class="flex flex-wrap gap-2">
                @foreach ($vaiTro->nguoi_dungs as $nguoiDung)
                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-sm font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                        {{ $nguoiDung->ho_ten ?? $nguoiDung->ten_dang_nhap }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif

</div>

@endsection