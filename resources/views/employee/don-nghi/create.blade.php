{{-- resources/views/employee/don-nghi/create.blade.php --}}
@extends('layouts.employee')

@section('title', 'Tạo đơn xin nghỉ phép')

@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tạo đơn xin nghỉ phép</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Điền thông tin để tạo đơn xin nghỉ phép mới</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('employee.don-nghi.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition-colors border-2 border-gray-300 dark:border-gray-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Quay lại
                </a>
            </div>
        </div>

        {{-- Thông báo lỗi chung --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/30 border-2 border-red-200 dark:border-red-800 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-red-800 dark:text-red-300">Vui lòng kiểm tra lại thông tin:</p>
                        <ul class="mt-1 text-sm text-red-700 dark:text-red-400 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Form --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border-2 border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                <form method="POST" action="{{ route('employee.don-nghi.store') }}" class="space-y-6">
                    @csrf

                    {{-- 2 cột: Loại nghỉ và Số ngày nghỉ --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Loại nghỉ --}}
                        {{-- Loại nghỉ --}}
<div>
    <label for="loai_nghi_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
        Loại nghỉ <span class="text-red-500">*</span>
    </label>
    <select name="loai_nghi_id" id="loai_nghi_id" 
            class="w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('loai_nghi_id') border-red-500 dark:border-red-500 @enderror"
            style="padding: 10px 14px;">
        <option value="" data-gioi-han="">Chọn loại nghỉ</option>
        @foreach($loaiNghiPheps as $loai)
            @php
                $soDuConLai = $soDu['so_du_con_lai'] ?? 12; 
                $tenLoai = mb_strtolower($loai->ten, 'UTF-8');

                if (str_contains($tenLoai, 'phép năm') || $loai->id == 1) {
                    $textGioiHan = "Số dư khả dụng: " . $soDuConLai . " ngày (Trừ quỹ phép)";
                } elseif (str_contains($tenLoai, 'ốm') || $loai->id == 2) {
                    $textGioiHan = "Tối đa: 3 ngày/đơn (Trừ quỹ phép - Yêu cầu báo cáo)";
                } elseif (str_contains($tenLoai, 'lễ')) {
                    $textGioiHan = "Tối đa: 1 ngày/đơn (Trừ quỹ phép)";
                } elseif (str_contains($tenLoai, 'thai sản')) {
                    $textGioiHan = "Tối đa: 5 ngày/đơn (Không trừ quỹ phép - Khám thai / Vợ sinh)";
                } elseif (str_contains($tenLoai, 'không lương')) {
                    $textGioiHan = "Tối đa: 5 ngày/đơn (Không trừ vào quỹ phép năm)";
                } else {
                    $textGioiHan = "Tối đa: " . $soDuConLai . " ngày/đơn";
                }
            @endphp
            <option value="{{ $loai->id }}" 
                    data-gioi-han="{{ $textGioiHan }}"
                    {{ old('loai_nghi_id') == $loai->id ? 'selected' : '' }}>
                {{ $loai->ten }} {{ $loai->co_luong ? '(Có lương)' : '(Không lương)' }}
            </option>
        @endforeach
    </select>
    
    {{-- Thẻ hiển thị thông báo giới hạn động dưới select --}}
    <p id="hien_thi_gioi_han" class="mt-1.5 text-xs font-semibold text-blue-600 dark:text-blue-400 min-h-[16px]"></p>

    @error('loai_nghi_id')
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>

                        {{-- Số ngày nghỉ --}}
                        <div>
                            <label for="so_ngay_nghi"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Số ngày nghỉ <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="so_ngay_nghi" id="so_ngay_nghi" value="{{ old('so_ngay_nghi', 0) }}"
                                class="w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-600 dark:text-white text-sm focus:outline-none transition-all duration-200 @error('so_ngay_nghi') border-red-500 dark:border-red-500 @enderror"
                                style="padding: 10px 14px;" readonly>
                            @error('so_ngay_nghi')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- 2 cột: Từ ngày và Đến ngày --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Từ ngày --}}
                        <div>
                            <label for="ngay_bat_dau"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Từ ngày <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="ngay_bat_dau" id="ngay_bat_dau" value="{{ old('ngay_bat_dau') }}"
                                min="{{ date('Y-m-d') }}"
                                class="w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('ngay_bat_dau') border-red-500 dark:border-red-500 @enderror"
                                style="padding: 10px 14px;">
                            @error('ngay_bat_dau')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Đến ngày --}}
                        <div>
                            <label for="ngay_ket_thuc"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Đến ngày <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="ngay_ket_thuc" id="ngay_ket_thuc"
                                value="{{ old('ngay_ket_thuc') }}" min="{{ date('Y-m-d') }}"
                                class="w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('ngay_ket_thuc') border-red-500 dark:border-red-500 @enderror"
                                style="padding: 10px 14px;">
                            @error('ngay_ket_thuc')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Lý do --}}
                    <div>
                        <label for="ly_do" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Lý do <span class="text-red-500">*</span>
                        </label>
                        <textarea name="ly_do" id="ly_do" rows="4"
                            class="w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('ly_do') border-red-500 dark:border-red-500 @enderror"
                            style="padding: 10px 14px;" placeholder="Nhập lý do xin nghỉ...">{{ old('ly_do') }}</textarea>
                        @error('ly_do')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Ghi chú --}}
                    <div>
                        <label for="ghi_chu" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Ghi chú
                        </label>
                        <textarea name="ghi_chu" id="ghi_chu" rows="2"
                            class="w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            style="padding: 10px 14px;" placeholder="Ghi chú thêm (nếu có)...">{{ old('ghi_chu') }}</textarea>
                        @error('ghi_chu')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ⭐ CẢNH BÁO SẮP HẾT PHÉP (ĐỒNG BỘ GIỐNG TRANG INDEX) ⭐ --}}
                    @if ($soDu['canh_bao_sap_het'] ?? false)
                        <div
                            class="p-4 bg-amber-50 dark:bg-amber-950/20 border-2 border-amber-200 dark:border-amber-800 rounded-lg text-amber-800 dark:text-amber-300 flex items-center space-x-2">
                            <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            <span class="text-sm font-medium">
                                Cảnh báo: Số dư nghỉ phép của bạn sắp hết (Còn lại {{ $soDu['so_du_con_lai'] ?? 0 }} ngày).
                                Vui lòng cân nhắc kế hoạch nghỉ phép hợp lý.
                            </span>
                        </div>
                    @endif

                    {{-- Số dư nghỉ phép năm --}}
                    <div
                        class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-4 border-2 border-blue-200 dark:border-blue-700">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-10 h-10 bg-blue-100 dark:bg-blue-800/50 rounded-lg flex items-center justify-center flex-shrink-0 border-2 border-blue-200 dark:border-blue-600">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-blue-800 dark:text-blue-300">Số dư nghỉ phép hiện
                                        tại</p>
                                    <div class="flex items-baseline gap-2">
                                        <p class="text-xl font-bold text-blue-600 dark:text-blue-400">
                                            {{ $soDu['so_du_con_lai'] ?? 12 }}
                                            <span class="text-sm font-normal text-blue-500 dark:text-blue-400">/
                                                {{ $soDu['so_ngay_phep_nam'] ?? 12 }} ngày</span>
                                        </p>
                                        @if (($soDu['so_ngay_da_nghi'] ?? 0) > 0)
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                (Đã nghỉ {{ $soDu['so_ngay_da_nghi'] ?? 0 }} ngày)
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div>
                                <span
                                    class="text-xs text-blue-600 dark:text-blue-400 bg-white dark:bg-gray-800 px-3 py-1.5 rounded-lg border-2 border-blue-200 dark:border-blue-600 font-medium inline-block">
                                    <i class="fas fa-sync-alt mr-1.5"></i> Cập nhật tự động
                                </span>
                            </div>
                        </div>

                        {{-- Thanh tiến trình số dư --}}
                        <div class="mt-3">
                            <div class="w-full bg-blue-200 dark:bg-blue-800/50 rounded-full h-2.5">
                                @php
                                    $soDuConLai = $soDu['so_du_con_lai'] ?? 0;
                                    $soNgayPhepNam = $soDu['so_ngay_phep_nam'] ?? 12;
                                    $phanTram = $soNgayPhepNam > 0 ? ($soDuConLai / $soNgayPhepNam) * 100 : 0;
                                @endphp
                                <div class="bg-blue-600 dark:bg-blue-400 h-2.5 rounded-full transition-all duration-500"
                                    style="width: {{ min(100, $phanTram) }}%"></div>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Còn lại</span>
                                <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">{{ $soDuConLai }}
                                    ngày</span>
                            </div>
                        </div>
                    </div>

                    {{-- Nút submit --}}
                    <div
                        class="flex items-center justify-end space-x-3 pt-4 border-t-2 border-gray-200 dark:border-gray-700">
                        <a href="{{ route('employee.don-nghi.index') }}"
                            class="px-4 py-2 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition-colors border-2 border-gray-300 dark:border-gray-600">
                            Hủy
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors border-2 border-blue-700 hover:border-blue-800 shadow-sm">
                            <svg class="inline w-4 h-4 mr-2 -mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Gửi đơn xin nghỉ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ngayBatDau = document.getElementById('ngay_bat_dau');
            const ngayKetThuc = document.getElementById('ngay_ket_thuc');
            const soNgayNghi = document.getElementById('so_ngay_nghi');
            const loaiNghiSelect = document.getElementById('loai_nghi_id');
            const hienThiGioiHan = document.getElementById('hien_thi_gioi_han');

            // Thiết lập min date từ hôm nay
            const today = new Date().toISOString().split('T')[0];
            ngayBatDau.setAttribute('min', today);
            ngayKetThuc.setAttribute('min', today);

            // Hàm 1: Tính toán số ngày nghỉ thực tế (loại trừ T7, CN)
            function tinhSoNgay() {
                if (ngayBatDau.value && ngayKetThuc.value) {
                    const start = new Date(ngayBatDau.value);
                    const end = new Date(ngayKetThuc.value);

                    if (start <= end) {
                        let count = 0;
                        let current = new Date(start);
                        while (current <= end) {
                            const dayOfWeek = current.getDay();
                            // 0: Chủ Nhật, 6: Thứ Bảy
                            if (dayOfWeek !== 0 && dayOfWeek !== 6) {
                                count++;
                            }
                            current.setDate(current.getDate() + 1);
                        }
                        soNgayNghi.value = count;
                    } else {
                        soNgayNghi.value = '';
                    }
                }
            }

            // ⭐ Hàm 2: Xử lý hiển thị chuỗi giới hạn tương ứng của từng loại nghỉ ⭐
            function capNhatGioiHanHienThi() {
                if (!loaiNghiSelect || !hienThiGioiHan) return;
                
                const selectedOption = loaiNghiSelect.options[loaiNghiSelect.selectedIndex];
                const gioiHanText = selectedOption.getAttribute('data-gioi-han');
                
                if (gioiHanText) {
                    hienThiGioiHan.textContent = `📌 ${gioiHanText}`;
                } else {
                    hienThiGioiHan.textContent = '';
                }
            }

            // Lắng nghe sự kiện thay đổi ngày
            ngayBatDau.addEventListener('change', function() {
                if (this.value) {
                    ngayKetThuc.setAttribute('min', this.value);
                }
                tinhSoNgay();
            });
            ngayKetThuc.addEventListener('change', tinhSoNgay);

            // Lắng nghe sự kiện thay đổi loại nghỉ phép
            loaiNghiSelect.addEventListener('change', capNhatGioiHanHienThi);

            // Chạy kích hoạt lần đầu khi vừa load trang (đề phòng trường hợp dính old input khi validate lỗi)
            capNhatGioiHanHienThi();
        });
    </script>
@endpush
