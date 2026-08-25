{{-- resources/views/employee/tang-ca/xin-ve-som.blade.php --}}
@extends('layouts.employee')

@section('title', 'Xin về sớm tăng ca')

@section('content')
<div class="space-y-6 max-w-full px-4 sm:px-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-clock mr-3 text-yellow-600"></i>
                Xin về sớm tăng ca
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Đơn #{{ $donTangCa->id }} - {{ Carbon\Carbon::parse($donTangCa->ngay_tang_ca)->format('d/m/Y') }}
            </p>
        </div>
        <a href="{{ route('employee.tang-ca.show', $donTangCa->id) }}" 
            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2 whitespace-nowrap">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    {{-- THÔNG TIN TĂNG CA --}}
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Giờ bắt đầu</p>
                <p class="font-medium text-blue-700 dark:text-blue-300">{{ \Carbon\Carbon::parse($donTangCa->gio_bat_dau)->format('H:i') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Giờ kết thúc</p>
                <p class="font-medium text-blue-700 dark:text-blue-300">{{ \Carbon\Carbon::parse($donTangCa->gio_ket_thuc)->format('H:i') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Số giờ đăng ký</p>
                <p class="font-medium text-blue-700 dark:text-blue-300">{{ number_format($donTangCa->so_gio_tang_ca, 1, ',', '') }} giờ</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Loại</p>
                <p class="font-medium text-blue-700 dark:text-blue-300">
                    {{ \App\Helpers\OvertimeHelper::getLoaiLabel($donTangCa->loai_tang_ca) }}
                </p>
            </div>
        </div>
    </div>

    {{-- FORM XIN VỀ SỚM --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <form action="{{ route('employee.tang-ca.xin-ve-som.store', $donTangCa->id) }}" method="POST" id="formXinVeSom">
            @csrf
            <div class="p-6 space-y-5">

                {{-- Giờ về sớm dự kiến --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-hourglass-end mr-1 text-yellow-500"></i>
                        Giờ về sớm dự kiến <span class="text-red-500">*</span>
                    </label>
                    
                    @php
                        $gioBatDau = \Carbon\Carbon::parse($donTangCa->gio_bat_dau)->format('H:i');
                        $gioKetThuc = \Carbon\Carbon::parse($donTangCa->gio_ket_thuc)->format('H:i');
                    @endphp
                    
                    <input type="time" 
                        name="gio_ve_som" 
                        id="gioVeSom"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                        min="{{ $gioBatDau }}"
                        max="{{ $gioKetThuc }}"
                        value="{{ old('gio_ve_som') }}"
                        required>
                        
                    <div class="flex justify-between mt-2">
                        <span class="text-xs text-gray-400">
                            Giờ bắt đầu: <span class="font-medium">{{ $gioBatDau }}</span>
                        </span>
                        <span class="text-xs text-gray-400">
                            Giờ kết thúc: <span class="font-medium">{{ $gioKetThuc }}</span>
                        </span>
                    </div>
                    <div id="thongBaoSoPhut" class="mt-2 text-sm font-medium"></div>
                    @error('gio_ve_som')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Lý do --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-pen mr-1 text-yellow-500"></i>
                        Lý do xin về sớm
                    </label>
                    <textarea name="ly_do" id="lyDo" rows="4"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 resize-none"
                        placeholder="Nhập lý do xin về sớm...">{{ old('ly_do') }}</textarea>
                    <div class="flex justify-between mt-1">
                        <span class="text-xs text-gray-400">Không bắt buộc</span>
                        <span id="lyDoCount" class="text-xs text-gray-400">0/500</span>
                    </div>
                </div>

                {{-- Lưu ý --}}
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-yellow-600 dark:text-yellow-400 mt-0.5"></i>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-yellow-700 dark:text-yellow-400">
                                📌 Lưu ý khi xin về sớm
                            </p>
                            <ul class="text-xs text-yellow-600 dark:text-yellow-300 space-y-0.5 list-disc list-inside">
                                <li>Chỉ được xin về sớm tối đa <strong>3 tiếng</strong></li>
                                <li>Chờ <strong>trưởng phòng duyệt</strong> mới được về sớm</li>
                                <li>Nếu về sớm trong <strong>1 tiếng cuối</strong> không cần xin</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Nút --}}
                <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit"
                        class="px-6 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition flex items-center gap-2 shadow-sm hover:shadow-md"
                        onclick="return confirm('Xác nhận gửi đơn xin về sớm?')">
                        <i class="fas fa-paper-plane"></i>
                        Gửi đơn xin về sớm
                    </button>
                    <a href="{{ route('employee.tang-ca.show', $donTangCa->id) }}"
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
    // ⭐ LẤY GIỜ ĐÃ ĐƯỢC FORMAT CHUẨN HH:MM
    const gioBatDau = '{{ \Carbon\Carbon::parse($donTangCa->gio_bat_dau)->format('H:i') }}';
    const gioKetThuc = '{{ \Carbon\Carbon::parse($donTangCa->gio_ket_thuc)->format('H:i') }}';
    
    const inputGioVeSom = document.getElementById('gioVeSom');
    const thongBao = document.getElementById('thongBaoSoPhut');

    function tinhSoPhutVeSom() {
        const gioVe = inputGioVeSom.value;
        if (!gioVe) {
            thongBao.innerHTML = '';
            thongBao.className = 'mt-2 text-sm font-medium';
            return;
        }

        // Tách giờ và phút
        const batDauParts = gioBatDau.split(':');
        const ketThucParts = gioKetThuc.split(':');
        const veParts = gioVe.split(':');
        
        const batDauPhut = parseInt(batDauParts[0]) * 60 + parseInt(batDauParts[1]);
        const ketThucPhut = parseInt(ketThucParts[0]) * 60 + parseInt(ketThucParts[1]);
        const vePhut = parseInt(veParts[0]) * 60 + parseInt(veParts[1]);
        
        const soPhutVeSom = ketThucPhut - vePhut;
        
        if (soPhutVeSom <= 0) {
            thongBao.innerHTML = `
                <span class="text-red-600 dark:text-red-400">⚠️ Giờ về sớm phải trước giờ kết thúc (${gioKetThuc})</span>
            `;
            thongBao.className = 'mt-2 text-sm font-medium';
            return;
        }
        
        if (soPhutVeSom > 180) {
            thongBao.innerHTML = `
                <span class="text-red-600 dark:text-red-400">⚠️ Chỉ được xin về sớm tối đa 3 tiếng (hiện tại ${soPhutVeSom} phút)</span>
            `;
            thongBao.className = 'mt-2 text-sm font-medium';
            return;
        }
        
        const gio = Math.floor(soPhutVeSom / 60);
        const phut = soPhutVeSom % 60;
        let text = '';
        if (gio > 0) {
            text += `${gio} giờ `;
        }
        if (phut > 0) {
            text += `${phut} phút`;
        }
        if (!text) text = '0 phút';
        
        thongBao.innerHTML = `
            <span class="text-green-600 dark:text-green-400">✅ Sẽ về sớm <strong>${text}</strong> (${soPhutVeSom} phút)</span>
        `;
        thongBao.className = 'mt-2 text-sm font-medium';
    }

    // Gọi hàm khi load trang (nếu có giá trị)
    if (inputGioVeSom.value) {
        tinhSoPhutVeSom();
    }

    inputGioVeSom.addEventListener('change', tinhSoPhutVeSom);
    inputGioVeSom.addEventListener('input', tinhSoPhutVeSom);

    // ⭐ KIỂM TRA TRƯỚC KHI SUBMIT
    document.getElementById('formXinVeSom').addEventListener('submit', function(e) {
        const gioVe = inputGioVeSom.value;
        if (!gioVe) {
            e.preventDefault();
            alert('⚠️ Vui lòng chọn giờ về sớm!');
            return false;
        }

        const ketThucParts = gioKetThuc.split(':');
        const veParts = gioVe.split(':');
        
        const ketThucPhut = parseInt(ketThucParts[0]) * 60 + parseInt(ketThucParts[1]);
        const vePhut = parseInt(veParts[0]) * 60 + parseInt(veParts[1]);
        
        const soPhutVeSom = ketThucPhut - vePhut;
        
        if (soPhutVeSom <= 0) {
            e.preventDefault();
            alert('⚠️ Giờ về sớm phải trước giờ kết thúc!');
            return false;
        }
        
        if (soPhutVeSom > 180) {
            e.preventDefault();
            alert('⚠️ Chỉ được xin về sớm tối đa 3 tiếng!');
            return false;
        }
        
        if (!confirm('Xác nhận gửi đơn xin về sớm?')) {
            e.preventDefault();
            return false;
        }
    });

    // Đếm ký tự lý do
    document.getElementById('lyDo').addEventListener('input', function() {
        document.getElementById('lyDoCount').textContent = this.value.length + '/500';
    });
});
</script>
@endpush
@endsection