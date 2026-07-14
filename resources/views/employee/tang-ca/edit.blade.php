{{-- resources/views/employee/tang-ca/edit.blade.php --}}
@extends('layouts.employee')

@section('title', 'Chỉnh sửa đơn xin tăng ca')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-edit mr-3 text-yellow-600"></i>
                Chỉnh sửa đơn xin tăng ca
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Cập nhật thông tin đăng ký tăng ca</p>
        </div>
        <a href="{{ route('employee.tang-ca.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
            ← Quay lại
        </a>
    </div>

    {{-- THÔNG BÁO --}}
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg shadow-sm flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 dark:text-green-400">×</button>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg shadow-sm">
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg shadow-sm">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- FORM --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <form action="{{ route('employee.tang-ca.update', $tangCa->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">
                
                {{-- Ngày tăng ca --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Ngày tăng ca <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="ngay_tang_ca" value="{{ old('ngay_tang_ca', $tangCa->ngay_tang_ca->format('Y-m-d')) }}"
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
                    <input type="time" name="gio_bat_dau" value="{{ old('gio_bat_dau', $tangCa->gio_bat_dau) }}"
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
                    <input type="time" name="gio_ket_thuc" value="{{ old('gio_ket_thuc', $tangCa->gio_ket_thuc) }}"
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
                        <option value="ngay_thuong" {{ old('loai_tang_ca', $tangCa->loai_tang_ca) == 'ngay_thuong' ? 'selected' : '' }}>Ngày thường (x1)</option>
                        <option value="ngay_nghi" {{ old('loai_tang_ca', $tangCa->loai_tang_ca) == 'ngay_nghi' ? 'selected' : '' }}>Ngày nghỉ (x1.5)</option>
                        <option value="le_tet" {{ old('loai_tang_ca', $tangCa->loai_tang_ca) == 'le_tet' ? 'selected' : '' }}>Lễ Tết (x2)</option>
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
                              placeholder="Nhập lý do tăng ca..." required>{{ old('ly_do_tang_ca', $tangCa->ly_do_tang_ca) }}</textarea>
                    @error('ly_do_tang_ca')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Thông tin trạng thái --}}
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                    <p class="text-sm text-yellow-700 dark:text-yellow-400">
                        <i class="fas fa-info-circle mr-2"></i>
                        Đơn đang ở trạng thái: <strong>Chờ duyệt</strong>. Sau khi chỉnh sửa, đơn sẽ được gửi lại để duyệt.
                    </p>
                </div>

                {{-- Nút --}}
                <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Cập nhật đơn
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