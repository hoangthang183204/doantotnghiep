{{-- resources/views/employee/yeu-cau-chinh-cong/create.blade.php --}}
@extends('layouts.employee')

@section('title', 'Tạo yêu cầu chỉnh công')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-plus-circle mr-3 text-blue-600"></i>
                Tạo yêu cầu chỉnh công
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Yêu cầu điều chỉnh giờ công</p>
        </div>
        <a href="{{ route('employee.yeu-cau-chinh-cong.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
            ← Quay lại
        </a>
    </div>

    {{-- FORM --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <form action="{{ route('employee.yeu-cau-chinh-cong.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-6 space-y-4">
                
                {{-- Ngày --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Ngày cần chỉnh <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="ngay" value="{{ old('ngay', date('Y-m-d')) }}"
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500" required>
                    @error('ngay')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Giờ vào --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Giờ vào
                    </label>
                    <input type="time" name="gio_vao" value="{{ old('gio_vao') }}"
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('gio_vao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Giờ ra --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Giờ ra
                    </label>
                    <input type="time" name="gio_ra" value="{{ old('gio_ra') }}"
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('gio_ra')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Lý do --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Lý do chỉnh công <span class="text-red-500">*</span>
                    </label>
                    <textarea name="ly_do" rows="4" 
                              class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500" 
                              placeholder="Nhập lý do yêu cầu chỉnh công..." required>{{ old('ly_do') }}</textarea>
                    @error('ly_do')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- File đính kèm --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        File đính kèm
                    </label>
                    <input type="file" name="tep_dinh_kem" 
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500"
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <p class="text-xs text-gray-500 mt-1">Chấp nhận: PDF, DOC, DOCX, JPG, PNG. Tối đa 5MB</p>
                    @error('tep_dinh_kem')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nút --}}
                <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-paper-plane"></i>
                        Gửi yêu cầu
                    </button>
                    <a href="{{ route('employee.yeu-cau-chinh-cong.index') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                        Hủy
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection