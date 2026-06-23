@extends('layouts.admin')

@section('title', 'Thêm tài khoản mới')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 flex items-center justify-center text-white text-lg">
                        <i class="fas fa-user-plus"></i>
                    </span>
                    Thêm tài khoản mới
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Tạo tài khoản mới và tự động tạo hồ sơ nhân viên
                </p>
            </div>
            <a href="{{ route('admin.nguoi-dung.index') }}" 
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl text-sm font-medium transition flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Quay lại
            </a>
        </div>
    </div>

    {{-- FORM --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <form method="POST" action="{{ route('admin.nguoi-dung.store') }}" class="p-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Cột trái --}}
                <div class="space-y-5">
                    {{-- Tên đăng nhập --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            <i class="fas fa-user mr-1 text-gray-400"></i> Tên đăng nhập <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="ten_dang_nhap" value="{{ old('ten_dang_nhap') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="VD: nguyenvana">
                        @error('ten_dang_nhap')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            <i class="fas fa-envelope mr-1 text-gray-400"></i> Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="VD: nguyenvana@company.com">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Mật khẩu --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            <i class="fas fa-lock mr-1 text-gray-400"></i> Mật khẩu <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" 
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="Tối thiểu 6 ký tự">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Cột phải --}}
                <div class="space-y-5">
                    {{-- Vai trò --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            <i class="fas fa-user-tag mr-1 text-gray-400"></i> Vai trò
                        </label>
                        <select name="vai_tro_id" 
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">-- Chọn vai trò --</option>
                            @foreach($vaiTros as $vt)
                                <option value="{{ $vt->id }}" {{ old('vai_tro_id') == $vt->id ? 'selected' : '' }}>
                                    {{ $vt->ten_hien_thi }}
                                </option>
                            @endforeach
                        </select>
                        @error('vai_tro_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phòng ban --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            <i class="fas fa-building mr-1 text-gray-400"></i> Phòng ban
                        </label>
                        <select name="phong_ban_id" 
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">-- Chọn phòng ban --</option>
                            @foreach($phongBans as $pb)
                                <option value="{{ $pb->id }}" {{ old('phong_ban_id') == $pb->id ? 'selected' : '' }}>
                                    {{ $pb->ten_phong_ban }}
                                </option>
                            @endforeach
                        </select>
                        @error('phong_ban_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Chức vụ --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            <i class="fas fa-briefcase mr-1 text-gray-400"></i> Chức vụ
                        </label>
                        <select name="chuc_vu_id" 
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">-- Chọn chức vụ --</option>
                            @foreach($chucVus as $cv)
                                <option value="{{ $cv->id }}" {{ old('chuc_vu_id') == $cv->id ? 'selected' : '' }}>
                                    {{ $cv->ten }}
                                </option>
                            @endforeach
                        </select>
                        @error('chuc_vu_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>

            {{-- Thông báo tự động tạo hồ sơ --}}
            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                <div class="flex items-start gap-3">
                    <i class="fas fa-info-circle text-blue-500 dark:text-blue-400 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Hồ sơ sẽ được tự động tạo</p>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-0.5">
                            Mã nhân viên sẽ được tạo tự động theo định dạng NVxxx
                        </p>
                    </div>
                </div>
            </div>

            {{-- BUTTON --}}
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end gap-3">
                <a href="{{ route('admin.nguoi-dung.index') }}" 
                    class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl text-sm font-medium transition">
                    Hủy
                </a>
                <button type="submit" 
                    class="px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl text-sm font-medium transition shadow-md hover:shadow-lg flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Tạo tài khoản
                </button>
            </div>

        </form>
    </div>
</div>
@endsection