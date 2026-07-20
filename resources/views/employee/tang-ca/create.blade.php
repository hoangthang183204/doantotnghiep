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

    {{-- THÔNG TIN GIỚI HẠN --}}
    @php
        $thongKeGio = App\Helpers\OvertimeHelper::thongKeGioTangCa(Auth::id());
    @endphp
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg border border-blue-200 dark:border-blue-800">
            <div class="flex items-center justify-between">
                <p class="text-xs text-blue-600 dark:text-blue-400">Đã dùng tháng</p>
                <span class="text-xs font-bold text-blue-700 dark:text-blue-300">
                    {{ $thongKeGio['trong_thang_text'] }}
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                <div class="bg-blue-600 h-1.5 rounded-full" 
                     style="width: {{ min(100, ($thongKeGio['trong_thang'] / $thongKeGio['limit_month']) * 100) }}%"></div>
            </div>
            <p class="text-[10px] text-gray-500 mt-0.5">Giới hạn: {{ $thongKeGio['limit_month_text'] }}</p>
        </div>
        
        <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg border border-green-200 dark:border-green-800">
            <div class="flex items-center justify-between">
                <p class="text-xs text-green-600 dark:text-green-400">Đã dùng năm</p>
                <span class="text-xs font-bold text-green-700 dark:text-green-300">
                    {{ $thongKeGio['trong_nam_text'] }}
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                <div class="bg-green-600 h-1.5 rounded-full" 
                     style="width: {{ min(100, ($thongKeGio['trong_nam'] / $thongKeGio['limit_year']) * 100) }}%"></div>
            </div>
            <p class="text-[10px] text-gray-500 mt-0.5">Giới hạn: {{ $thongKeGio['limit_year_text'] }}</p>
        </div>
        
        <div class="bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg border border-yellow-200 dark:border-yellow-800">
            <div class="flex items-center justify-between">
                <p class="text-xs text-yellow-600 dark:text-yellow-400">Còn lại tháng</p>
                <span class="text-xs font-bold text-yellow-700 dark:text-yellow-300">
                    {{ $thongKeGio['remaining_month_text'] }}
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                <div class="bg-yellow-600 h-1.5 rounded-full" 
                     style="width: {{ min(100, ($thongKeGio['remaining_month'] / $thongKeGio['limit_month']) * 100) }}%"></div>
            </div>
            <p class="text-[10px] text-gray-500 mt-0.5">Còn {{ $thongKeGio['remaining_month_text'] }}</p>
        </div>
        
        <div class="bg-purple-50 dark:bg-purple-900/20 p-3 rounded-lg border border-purple-200 dark:border-purple-800">
            <div class="flex items-center justify-between">
                <p class="text-xs text-purple-600 dark:text-purple-400">Còn lại năm</p>
                <span class="text-xs font-bold text-purple-700 dark:text-purple-300">
                    {{ $thongKeGio['remaining_year_text'] }}
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                <div class="bg-purple-600 h-1.5 rounded-full" 
                     style="width: {{ min(100, ($thongKeGio['remaining_year'] / $thongKeGio['limit_year']) * 100) }}%"></div>
            </div>
            <p class="text-[10px] text-gray-500 mt-0.5">Còn {{ $thongKeGio['remaining_year_text'] }}</p>
        </div>
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

                {{-- Lưu ý giới hạn --}}
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3">
                    <p class="text-xs text-yellow-700 dark:text-yellow-400">
                        <i class="fas fa-info-circle mr-1"></i>
                        <strong>Quy định tăng ca:</strong> Tối đa 4 giờ/ngày, 40 giờ/tháng, 200 giờ/năm. 
                        Tổng giờ làm việc không quá 12 giờ/ngày.
                    </p>
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