{{-- resources/views/employee/don-nghi/create.blade.php --}}
@extends('layouts.employee')

@section('title', 'Tạo đơn xin nghỉ phép')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tạo đơn xin nghỉ phép</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Điền thông tin để tạo đơn xin nghỉ phép mới</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('employee.don-nghi.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    @include('layouts.partials.alerts')

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form method="POST" action="{{ route('employee.don-nghi.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="loai_nghi_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Loại nghỉ <span class="text-red-500">*</span>
                    </label>
                    <select name="loai_nghi_id" id="loai_nghi_id" 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500 @error('loai_nghi_id') border-red-500 @enderror">
                        <option value="">Chọn loại nghỉ</option>
                        @foreach($loaiNghiPheps as $loai)
                            <option value="{{ $loai->id }}" {{ old('loai_nghi_id') == $loai->id ? 'selected' : '' }}>
                                {{ $loai->ten }} {{ $loai->co_luong ? '(Có lương)' : '(Không lương)' }}
                            </option>
                        @endforeach
                    </select>
                    @error('loai_nghi_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="so_ngay_nghi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Số ngày nghỉ <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="so_ngay_nghi" id="so_ngay_nghi" 
                           value="{{ old('so_ngay_nghi') }}" 
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500 @error('so_ngay_nghi') border-red-500 @enderror"
                           placeholder="Nhập số ngày nghỉ" min="0.5" step="0.5">
                    @error('so_ngay_nghi')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="ngay_bat_dau" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Từ ngày <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="ngay_bat_dau" id="ngay_bat_dau" 
                           value="{{ old('ngay_bat_dau') }}" 
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500 @error('ngay_bat_dau') border-red-500 @enderror">
                    @error('ngay_bat_dau')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="ngay_ket_thuc" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Đến ngày <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="ngay_ket_thuc" id="ngay_ket_thuc" 
                           value="{{ old('ngay_ket_thuc') }}" 
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500 @error('ngay_ket_thuc') border-red-500 @enderror">
                    @error('ngay_ket_thuc')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="ly_do" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Lý do <span class="text-red-500">*</span>
                </label>
                <textarea name="ly_do" id="ly_do" rows="4" 
                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500 @error('ly_do') border-red-500 @enderror"
                          placeholder="Nhập lý do xin nghỉ...">{{ old('ly_do') }}</textarea>
                @error('ly_do')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="ghi_chu" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Ghi chú
                </label>
                <textarea name="ghi_chu" id="ghi_chu" rows="2" 
                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Ghi chú thêm (nếu có)...">{{ old('ghi_chu') }}</textarea>
                @error('ghi_chu')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-800 dark:text-blue-300">Số dư nghỉ phép hiện tại</p>
                            <p class="text-sm text-blue-600 dark:text-blue-400">{{ $soDuNghiPhep ?? 12 }} ngày còn lại</p>
                        </div>
                    </div>
                    <span class="text-xs text-blue-600 dark:text-blue-400">Cập nhật tự động</span>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('employee.don-nghi.index') }}" 
                   class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition-colors">
                    Hủy
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                    <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Gửi đơn xin nghỉ
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ngayBatDau = document.getElementById('ngay_bat_dau');
        const ngayKetThuc = document.getElementById('ngay_ket_thuc');
        const soNgayNghi = document.getElementById('so_ngay_nghi');

        function tinhSoNgay() {
            if (ngayBatDau.value && ngayKetThuc.value) {
                const start = new Date(ngayBatDau.value);
                const end = new Date(ngayKetThuc.value);
                if (start <= end) {
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    soNgayNghi.value = diffDays;
                }
            }
        }

        ngayBatDau.addEventListener('change', tinhSoNgay);
        ngayKetThuc.addEventListener('change', tinhSoNgay);
    });
</script>
@endpush