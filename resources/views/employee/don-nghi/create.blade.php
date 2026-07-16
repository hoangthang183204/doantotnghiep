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
                        <div>
                            <label for="loai_nghi_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Loại nghỉ <span class="text-red-500">*</span>
                            </label>
                            <select name="loai_nghi_id" id="loai_nghi_id"
                                class="w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('loai_nghi_id') border-red-500 dark:border-red-500 @enderror"
                                style="padding: 10px 14px;">
                                <option value="">Chọn loại nghỉ</option>
                                @foreach ($loaiNghiPheps as $loai)
                                    <option value="{{ $loai->id }}"
                                        {{ old('loai_nghi_id') == $loai->id ? 'selected' : '' }}>
                                        {{ $loai->ten }} {{ $loai->co_luong ? '(Có lương)' : '(Không lương)' }}
                                    </option>
                                @endforeach
                            </select>
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
                            <input type="number" name="so_ngay_nghi" id="so_ngay_nghi" value="{{ old('so_ngay_nghi') }}"
                                class="w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('so_ngay_nghi') border-red-500 dark:border-red-500 @enderror"
                                style="padding: 10px 14px;" placeholder="Nhập số ngày nghỉ" min="0.5" step="0.5">
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

                    {{-- Số dư nghỉ phép & Cảnh báo giới hạn --}}
                    <div
                        class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-4 border-2 border-blue-200 dark:border-blue-700">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            {{-- Cột 1: Số dư theo năm (Giữ nguyên gốc của bạn) --}}
                            <div
                                class="border-b md:border-b-0 md:border-r border-blue-200 dark:border-blue-700 pb-4 md:pb-0 md:pr-4">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 bg-blue-100 dark:bg-blue-800/50 rounded-lg flex items-center justify-center flex-shrink-0 border-2 border-blue-200 dark:border-blue-600">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p
                                            class="text-xs font-semibold uppercase tracking-wider text-blue-800 dark:text-blue-300">
                                            Quỹ phép năm {{ date('Y') }}</p>
                                        <div class="flex items-baseline gap-2">
                                            <p class="text-xl font-bold text-blue-600 dark:text-blue-400">
                                                {{ $soDu['so_du_con_lai'] ?? 12 }}
                                                <span class="text-sm font-normal text-blue-500 dark:text-blue-400">/
                                                    {{ $soDu['so_ngay_phep_nam'] ?? 12 }} ngày</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Thanh tiến trình năm --}}
                                <div class="mt-3">
                                    <div class="w-full bg-blue-200 dark:bg-blue-800/50 rounded-full h-2">
                                        @php
                                            $soDuConLai = $soDu['so_du_con_lai'] ?? 0;
                                            $soNgayPhepNam = $soDu['so_ngay_phep_nam'] ?? 12;
                                            $phanTram = $soNgayPhepNam > 0 ? ($soDuConLai / $soNgayPhepNam) * 100 : 0;
                                        @endphp
                                        <div class="bg-blue-600 dark:bg-blue-400 h-2 rounded-full transition-all duration-500"
                                            style="width: {{ min(100, $phanTram) }}%"></div>
                                    </div>
                                    <div class="flex justify-between mt-1">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Khả dụng năm nay</span>
                                        <span
                                            class="text-xs text-blue-600 dark:text-blue-400 font-medium">{{ $soDuConLai }}
                                            ngày</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Cột 2: Cảnh báo giới hạn theo tháng (Phần thêm mới theo ý thầy) --}}
                            <div class="flex flex-col justify-between md:pl-4">
                                <div>
                                    <div class="flex items-center space-x-2 mb-1">
                                        <span class="flex h-2 w-2 relative">
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                        </span>
                                        <p
                                            class="text-xs font-semibold uppercase tracking-wider text-amber-800 dark:text-amber-400">
                                            Quy định nghỉ phép tháng {{ date('m') }}
                                        </p>
                                    </div>

                                    {{-- Giả sử giới hạn tháng là 3 ngày (bạn có thể truyền từ Controller qua biến $soDu) --}}
                                    @php
                                        $daNghiTrongThang = $soDu['da_nghi_trong_thang'] ?? 1.5; // Số ngày đã nghỉ tháng này
                                        $gioiHanThang = $soDu['gioi_han_thang'] ?? 3; // Giới hạn tối đa 1 tháng
                                    @endphp

                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                        Tháng này bạn đã nghỉ <strong
                                            class="text-gray-900 dark:text-white">{{ $daNghiTrongThang }}</strong> /
                                        <strong>{{ $gioiHanThang }} ngày</strong> cho phép.
                                    </p>
                                </div>

                                <div
                                    class="mt-3 p-2 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800 rounded text-xs text-amber-800 dark:text-amber-300">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    <strong>Lưu ý:</strong> Đơn xin nghỉ phép vượt quá <strong>{{ $gioiHanThang }}
                                        ngày/tháng</strong> sẽ cần sự phê duyệt đặc biệt từ Giám đốc bộ phận.
                                </div>
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

        // Thiết lập thuộc tính ngày tối thiểu để chọn là hôm nay
        const today = new Date().toISOString().split('T')[0];
        ngayBatDau.setAttribute('min', today);
        ngayKetThuc.setAttribute('min', today);

        // Hàm kiểm tra giới hạn và khóa nút submit nếu vượt quá ngày cho phép
        function kiemTraVaChan(days) {
            const btnSubmit = document.querySelector('button[type="submit"]');
            const GIOI_HAN_THANG = Number("{{ $soDu['gioi_han_thang'] ?? 3 }}");
            const daNghiThangNay = Number("{{ $soDu['da_nghi_trong_thang'] ?? 0 }}");
            const tongNgayDuKien = days + daNghiThangNay;

            // Xóa cảnh báo cũ
            const oldWarning = document.getElementById('monthly-limit-warning');
            if (oldWarning) {
                oldWarning.remove();
            }
            
            // Phục hồi trạng thái nút submit ban đầu
            if (btnSubmit) {
                btnSubmit.removeAttribute('disabled');
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
            }

            // Nếu vượt quá giới hạn tháng -> TIẾN HÀNH CHẶN NGAY
            if (tongNgayDuKien > GIOI_HAN_THANG) {
                const warningDiv = document.createElement('div');
                warningDiv.id = 'monthly-limit-warning';
                warningDiv.className = 'mt-2 p-3 bg-red-100 border-2 border-red-300 text-red-800 rounded-lg text-xs font-semibold dark:bg-red-900/40 dark:border-red-800 dark:text-red-300';
                warningDiv.innerHTML = `❌ <strong>Bị từ chối:</strong> Tổng số ngày nghỉ trong tháng này của bạn (${tongNgayDuKien} ngày) vượt quá hạn mức tối đa cho phép (${GIOI_HAN_THANG} ngày/tháng). Bạn không thể tạo đơn này. Vui lòng điều chỉnh lại khoảng thời gian nghỉ!`;
                
                soNgayNghi.parentNode.appendChild(warningDiv);

                if (btnSubmit) {
                    btnSubmit.setAttribute('disabled', 'true');
                    btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }
        }

        function tinhSoNgay() {
            if (ngayBatDau.value && ngayKetThuc.value) {
                const start = new Date(ngayBatDau.value);
                const end = new Date(ngayKetThuc.value);
                
                if (start <= end) {
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    soNgayNghi.value = diffDays;

                    // Chạy hàm kiểm tra chặn đơn
                    kiemTraVaChan(diffDays);
                } else {
                    soNgayNghi.value = '';
                    const oldWarning = document.getElementById('monthly-limit-warning');
                    if (oldWarning) oldWarning.remove();
                }
            }
        }

        // Lắng nghe thay đổi của ngày bắt đầu
        ngayBatDau.addEventListener('change', function() {
            if (this.value) {
                ngayKetThuc.setAttribute('min', this.value);
            }
            tinhSoNgay();
        });

        // Lắng nghe thay đổi của ngày kết thúc
        ngayKetThuc.addEventListener('change', tinhSoNgay);

        // Lắng nghe nếu nhân viên tự tay điều chỉnh số trong ô Số ngày nghỉ
        soNgayNghi.addEventListener('input', function() {
            const targetDays = Number(this.value) || 0;
            kiemTraVaChan(targetDays);
        });
    });
</script>
@endpush
