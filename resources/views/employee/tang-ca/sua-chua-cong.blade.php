{{-- resources/views/employee/tang-ca/sua-chua-cong.blade.php --}}
@extends('layouts.employee')

@section('title', 'Yêu cầu sửa chữa công')

@section('content')
<div class="space-y-6 max-w-full px-4 sm:px-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-edit mr-3 text-orange-600"></i>
                Yêu cầu sửa chữa công
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Đơn tăng ca #{{ $donTangCa->id }} - Ngày {{ Carbon\Carbon::parse($donTangCa->ngay_tang_ca)->format('d/m/Y') }}
            </p>
        </div>
        <a href="{{ route('employee.tang-ca.index') }}" 
            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition whitespace-nowrap">
            ← Quay lại
        </a>
    </div>

    {{-- THÔNG BÁO --}}
    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 mt-0.5 text-xl"></i>
            <div>
                <p class="text-sm font-medium text-red-700 dark:text-red-400">
                    ⚠️ Bạn đã quên check-out cho đơn tăng ca này
                </p>
                <p class="text-sm text-red-600 dark:text-red-300 mt-1">
                    Vui lòng nhập giờ checkout thực tế để gửi yêu cầu sửa chữa công đến trưởng phòng.
                </p>
                <p class="text-xs text-red-500 dark:text-red-400 mt-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    Thông tin: Giờ bắt đầu {{ Carbon\Carbon::parse($donTangCa->gio_bat_dau)->format('H:i') }} - 
                    Giờ kết thúc dự kiến {{ Carbon\Carbon::parse($donTangCa->gio_ket_thuc)->format('H:i') }}
                </p>
            </div>
        </div>
    </div>

    {{-- FORM --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <form action="{{ route('employee.tang-ca.store-sua-chua-cong', $donTangCa->id) }}" method="POST" enctype="multipart/form-data" id="suaChuaForm">
            @csrf
            <div class="p-6 space-y-5">

                {{-- Giờ checkout thực tế --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-clock mr-1 text-orange-500"></i>
                        Giờ checkout thực tế <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="gio_checkout_thuc_te" id="gioCheckout"
                        value="{{ old('gio_checkout_thuc_te', date('H:i')) }}"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                        required>
                    <p class="text-xs text-gray-400 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>
                        Nhập giờ bạn thực tế đã kết thúc tăng ca
                    </p>
                    @error('gio_checkout_thuc_te')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Số giờ tính toán --}}
                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3 flex items-center justify-between">
                    <div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-calculator mr-1 text-orange-500"></i>
                            Số giờ thực tế:
                        </span>
                        <span id="soGioThucTe" class="font-bold text-orange-600 dark:text-orange-400 text-lg ml-2">0</span>
                        <span class="text-sm text-gray-500">giờ</span>
                    </div>
                    <span class="text-xs text-gray-400">
                        Tối đa {{ number_format($donTangCa->so_gio_tang_ca, 1, ',', '') }} giờ
                    </span>
                </div>

                {{-- Ghi chú --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-pen mr-1 text-orange-500"></i>
                        Ghi chú (nếu có)
                    </label>
                    <textarea name="ghi_chu" rows="3" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 resize-none"
                        placeholder="Nhập ghi chú về lý do quên check-out...">{{ old('ghi_chu') }}</textarea>
                </div>

                {{-- File đính kèm --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-paperclip mr-1 text-orange-500"></i>
                        File đính kèm (nếu có)
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="file" name="file_dinh_kem" id="fileDinhKem"
                            class="block w-full text-sm text-gray-500 dark:text-gray-400
                                file:mr-4 file:py-2.5 file:px-4
                                file:rounded-lg file:border-0
                                file:text-sm file:font-semibold
                                file:bg-orange-50 file:text-orange-700
                                hover:file:bg-orange-100
                                dark:file:bg-orange-900/30 dark:file:text-orange-400
                                cursor-pointer">
                    </div>
                    <p class="text-xs text-gray-400 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>
                        Hỗ trợ: JPG, PNG, PDF, DOC, DOCX (tối đa 5MB)
                    </p>
                    @error('file_dinh_kem')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nút --}}
                <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" 
                        class="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition flex items-center gap-2 shadow-sm hover:shadow-md">
                        <i class="fas fa-paper-plane"></i>
                        Gửi yêu cầu sửa chữa công
                    </button>
                    <a href="{{ route('employee.tang-ca.index') }}" 
                        class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition">
                        Hủy
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const gioBatDau = '{{ $donTangCa->gio_bat_dau }}';
    const soGioDangKy = {{ $donTangCa->so_gio_tang_ca }};

    function tinhSoGio() {
        const gioCheckout = document.getElementById('gioCheckout').value;
        const display = document.getElementById('soGioThucTe');
        
        if (gioCheckout) {
            const start = new Date('2000-01-01T' + gioBatDau + ':00');
            const end = new Date('2000-01-01T' + gioCheckout + ':00');
            let diff = (end - start) / (1000 * 60 * 60);
            if (diff < 0) diff += 24;
            diff = Math.min(diff, soGioDangKy);
            display.textContent = diff.toFixed(1);
        } else {
            display.textContent = '0';
        }
    }

    document.getElementById('gioCheckout').addEventListener('change', tinhSoGio);
    document.getElementById('gioCheckout').addEventListener('input', tinhSoGio);
    tinhSoGio();

    document.getElementById('suaChuaForm').addEventListener('submit', function(e) {
        const gioCheckout = document.getElementById('gioCheckout').value;
        if (!gioCheckout) {
            e.preventDefault();
            alert('⚠️ Vui lòng nhập giờ checkout thực tế!');
            return false;
        }

        const soGio = parseFloat(document.getElementById('soGioThucTe').textContent);
        if (soGio < 0.5) {
            e.preventDefault();
            alert('⚠️ Số giờ thực tế tối thiểu là 0.5 giờ!');
            return false;
        }

        if (!confirm('Xác nhận gửi yêu cầu sửa chữa công?')) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endpush
@endsection