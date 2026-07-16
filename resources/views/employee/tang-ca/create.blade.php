{{-- resources/views/employee/tang-ca/create.blade.php --}}
@extends('layouts.employee')

@section('title', 'Tạo đơn xin tăng ca')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-plus-circle mr-3 text-blue-600"></i>
                Tạo đơn xin tăng ca
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Nhập thông tin đăng ký tăng ca</p>
        </div>
        <a href="{{ route('employee.tang-ca.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
            ← Quay lại
        </a>
    </div>

    {{-- FORM --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <form action="{{ route('employee.tang-ca.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                
                {{-- Ngày tăng ca --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Ngày tăng ca <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="ngay_tang_ca" value="{{ old('ngay_tang_ca', date('Y-m-d')) }}"
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    @error('ngay_tang_ca')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Giờ bắt đầu --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Giờ bắt đầu <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="gio_bat_dau" value="{{ old('gio_bat_dau', '18:00') }}"
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    @error('gio_bat_dau')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Giờ kết thúc --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Giờ kết thúc <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="gio_ket_thuc" value="{{ old('gio_ket_thuc', '22:00') }}"
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">⚠️ Số giờ tăng ca sẽ được tính tự động</p>
                    @error('gio_ket_thuc')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Loại tăng ca --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Loại tăng ca <span class="text-red-500">*</span>
                    </label>
                    <select name="loai_tang_ca" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500" required>
                        <option value="ngay_thuong" {{ old('loai_tang_ca') == 'ngay_thuong' ? 'selected' : '' }}>Ngày thường (150%)</option>
                        <option value="ngay_nghi" {{ old('loai_tang_ca') == 'ngay_nghi' ? 'selected' : '' }}>Ngày nghỉ (200%)</option>
                    </select>
                    @error('loai_tang_ca')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Lý do --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Lý do tăng ca <span class="text-red-500">*</span>
                    </label>
                    <textarea name="ly_do_tang_ca" rows="4" 
                              class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500" 
                              placeholder="Nhập lý do tăng ca..." required>{{ old('ly_do_tang_ca') }}</textarea>
                    @error('ly_do_tang_ca')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nút --}}
                <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-paper-plane"></i>
                        Gửi đơn
                    </button>
                    <a href="{{ route('employee.tang-ca.index') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                        Hủy
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection