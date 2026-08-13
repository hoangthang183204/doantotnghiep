{{-- resources/views/truong-phong/tang-ca/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tạo đơn tăng ca cho nhân viên')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-user-plus mr-3 text-blue-600"></i>
                Tạo đơn tăng ca cho nhân viên
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Phòng: <span class="font-medium text-blue-600">{{ $phongBan->ten_phong_ban ?? 'N/A' }}</span>
            </p>
        </div>
        <a href="{{ route('truong-phong.tang-ca.index') }}" 
            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    {{-- THÔNG BÁO TỪ KIẾN NGHỊ --}}
    @if(isset($kienNghi) && $kienNghi)
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <i class="fas fa-check-circle text-green-600 dark:text-green-400 mt-0.5"></i>
                <div>
                    <p class="text-sm font-medium text-green-700 dark:text-green-400">
                        ✅ Tạo đơn từ kiến nghị của nhân viên
                    </p>
                    <div class="grid grid-cols-2 gap-2 mt-2 text-sm text-green-600 dark:text-green-300">
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Nhân viên:</span>
                            <span class="font-medium">
                                {{ optional($kienNghi->nguoi_dung->hoSo)->ho }} {{ optional($kienNghi->nguoi_dung->hoSo)->ten }}
                            </span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Mã NV:</span>
                            <span class="font-medium">{{ optional($kienNghi->nguoi_dung->hoSo)->ma_nhan_vien ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Ngày đề nghị:</span>
                            <span class="font-medium">{{ $kienNghi->created_at ? $kienNghi->created_at->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Lý do:</span>
                            <span class="font-medium">{{ $kienNghi->ly_do_tang_ca }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- FORM --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <form action="{{ route('truong-phong.tang-ca.store') }}" method="POST" id="tangCaForm">
            @csrf
            @if(isset($kienNghi) && $kienNghi)
                <input type="hidden" name="kien_nghi_id" value="{{ $kienNghi->id }}">
                <input type="hidden" name="from_kien_nghi" value="1">
            @endif
            
            <div class="p-6 space-y-5">

                {{-- Bước 1: Chọn nhân viên --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-user text-blue-500 mr-1"></i>
                        Chọn nhân viên <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="nguoi_dung_id" id="nhanVienSelect"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none cursor-pointer"
                            required>
                            <option value="">-- Chọn nhân viên --</option>
                            @foreach($nhanViens as $nv)
                                <option value="{{ $nv->id }}" 
                                    {{ old('nguoi_dung_id', isset($kienNghi) ? $kienNghi->nguoi_dung_id : '') == $nv->id ? 'selected' : '' }}>
                                    {{ optional($nv->hoSo)->ma_nhan_vien ?? 'N/A' }} - 
                                    {{ optional($nv->hoSo)->ho }} {{ optional($nv->hoSo)->ten }}
                                    @if($nv->chucVu)
                                        ({{ $nv->chucVu->ten ?? 'N/A' }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                    @error('nguoi_dung_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    
                    {{-- Hiển thị thông tin nhân viên được chọn --}}
                    <div id="nhanVienInfo" class="{{ isset($kienNghi) && $kienNghi ? '' : 'hidden' }} mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                <span id="nhanVienInitial">
                                    @if(isset($kienNghi) && $kienNghi)
                                        {{ strtoupper(substr(optional($kienNghi->nguoi_dung->hoSo)->ten ?? 'N', 0, 1)) }}
                                    @else
                                        ?
                                    @endif
                                </span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 dark:text-white" id="nhanVienName">
                                    @if(isset($kienNghi) && $kienNghi)
                                        {{ optional($kienNghi->nguoi_dung->hoSo)->ho }} {{ optional($kienNghi->nguoi_dung->hoSo)->ten }}
                                    @else
                                        ---
                                    @endif
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Mã: <span id="nhanVienMa">
                                        @if(isset($kienNghi) && $kienNghi)
                                            {{ optional($kienNghi->nguoi_dung->hoSo)->ma_nhan_vien ?? 'N/A' }}
                                        @else
                                            ---
                                        @endif
                                    </span> | 
                                    Chức vụ: <span id="nhanVienChucVu">
                                        @if(isset($kienNghi) && $kienNghi)
                                            {{ optional($kienNghi->nguoi_dung->chucVu)->ten ?? 'N/A' }}
                                        @else
                                            ---
                                        @endif
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 my-4"></div>

                {{-- Bước 2: Thông tin tăng ca --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    {{-- Ngày tăng ca --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-calendar-day text-blue-500 mr-1"></i>
                            Ngày tăng ca <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="ngay_tang_ca" id="ngayTangCa" 
                            value="{{ old('ngay_tang_ca', isset($kienNghi) && $kienNghi ? $kienNghi->ngay_tang_ca : date('Y-m-d')) }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            min="{{ date('Y-m-d') }}" required>
                        @error('ngay_tang_ca')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ⭐ THÔNG BÁO LOẠI NGÀY --}}
                    <div id="thongBaoNgay" class="hidden col-span-1 md:col-span-2 p-3 rounded-lg border mb-2">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            <span id="thongBaoNgayText" class="text-sm"></span>
                        </div>
                    </div>

                    {{-- Loại tăng ca --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-tag text-blue-500 mr-1"></i>
                            Loại tăng ca <span class="text-red-500">*</span>
                        </label>
                        <select name="loai_tang_ca" id="loaiTangCa"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="ngay_thuong" {{ old('loai_tang_ca', isset($kienNghi) ? $kienNghi->loai_tang_ca : '') == 'ngay_thuong' ? 'selected' : '' }}>
                                📅 Ngày thường (150%)
                            </option>
                            <option value="ngay_nghi" {{ old('loai_tang_ca') == 'ngay_nghi' ? 'selected' : '' }}>
                                🎉 Ngày nghỉ hằng tuần (200%)
                            </option>
                            <option value="le_tet" {{ old('loai_tang_ca') == 'le_tet' ? 'selected' : '' }}>
                                🎊 Lễ, Tết (400%)
                            </option>
                        </select>
                        <div id="loaiTangCaNote" class="text-xs mt-1"></div>
                        @error('loai_tang_ca')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Giờ bắt đầu --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-play text-green-500 mr-1"></i>
                            Giờ bắt đầu <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="gio_bat_dau" id="gioBatDau" 
                            value="{{ old('gio_bat_dau', isset($kienNghi) && $kienNghi ? $kienNghi->gio_bat_dau : '17:00') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <p class="text-xs text-red-500 dark:text-red-400 mt-1" id="gioBatDauNote">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            ⚠️ Giờ tăng ca phải trong tương lai (sau thời điểm hiện tại)
                        </p>
                        @error('gio_bat_dau')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Giờ kết thúc --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-stop text-red-500 mr-1"></i>
                            Giờ kết thúc <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="gio_ket_thuc" id="gioKetThuc" 
                            value="{{ old('gio_ket_thuc', isset($kienNghi) && $kienNghi ? $kienNghi->gio_ket_thuc : '20:00') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        @error('gio_ket_thuc')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Hiển thị số giờ tăng ca tự động --}}
                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3 flex items-center justify-between">
                    <div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-clock mr-1 text-blue-500"></i>
                            Số giờ tăng ca:
                        </span>
                        <span id="soGioHienThi" class="font-bold text-blue-600 dark:text-blue-400 text-lg ml-2">
                            @if(isset($kienNghi) && $kienNghi && $kienNghi->so_gio_tang_ca)
                                {{ $kienNghi->so_gio_tang_ca }}
                            @else
                                0
                            @endif
                        </span>
                        <span class="text-sm text-gray-500">giờ</span>
                    </div>
                    <span class="text-xs text-gray-400">⚠️ Tối đa 8 giờ/ngày</span>
                </div>

                {{-- Lý do --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-pen text-blue-500 mr-1"></i>
                        Lý do tăng ca <span class="text-red-500">*</span>
                    </label>
                    <textarea name="ly_do_tang_ca" id="lyDo" rows="4" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                        placeholder="Nhập lý do tăng ca cho nhân viên..." required>{{ old('ly_do_tang_ca', isset($kienNghi) && $kienNghi ? $kienNghi->ly_do_tang_ca : '') }}</textarea>
                    <div class="flex justify-between mt-1">
                        <span class="text-xs text-gray-400">Tối thiểu 10 ký tự</span>
                        <span id="lyDoCount" class="text-xs text-gray-400">{{ strlen(old('ly_do_tang_ca', isset($kienNghi) && $kienNghi ? $kienNghi->ly_do_tang_ca : '')) }}/500</span>
                    </div>
                    @error('ly_do_tang_ca')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Lưu ý --}}
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-yellow-600 dark:text-yellow-400 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-medium text-yellow-700 dark:text-yellow-400">
                                📌 Lưu ý khi tạo đơn tăng ca
                            </p>
                            <ul class="text-xs text-yellow-600 dark:text-yellow-300 mt-1 space-y-1 list-disc list-inside">
                                <li>⚠️ <strong>Ngày thường:</strong> Tăng ca chỉ áp dụng sau giờ hành chính (17:30)</li>
                                <li>🎉 <strong>Ngày cuối tuần:</strong> Tất cả giờ làm đều được tính là tăng ca (200%)</li>
                                <li>Đơn sẽ được <strong>tự động duyệt</strong> vì trưởng phòng tạo</li>
                                <li>Tối đa <strong>8 giờ</strong> tăng ca/ngày</li>
                                <li>Giới hạn <strong>40 giờ/tháng</strong> và <strong>200 giờ/năm</strong></li>
                                <li>Nhân viên sẽ nhận thông báo ngay sau khi tạo</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Nút --}}
                <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" 
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2 shadow-sm hover:shadow-md">
                        <i class="fas fa-paper-plane"></i>
                        Tạo đơn tăng ca
                    </button>
                    <a href="{{ route('truong-phong.tang-ca.index') }}" 
                        class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition">
                        Hủy
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Danh sách nhân viên gợi ý --}}
    @if($nhanViens->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
            <h3 class="font-medium text-gray-700 dark:text-gray-300">
                <i class="fas fa-users mr-2 text-blue-500"></i>
                Danh sách nhân viên trong phòng ({{ $nhanViens->count() }} người)
            </h3>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 max-h-60 overflow-y-auto">
                @foreach($nhanViens as $nv)
                    <button type="button" 
                        onclick="chonNhanVien({{ $nv->id }}, '{{ addslashes(optional($nv->hoSo)->ho) }} {{ addslashes(optional($nv->hoSo)->ten) }}', '{{ optional($nv->hoSo)->ma_nhan_vien ?? 'N/A' }}', '{{ $nv->chucVu->ten ?? 'N/A' }}')"
                        class="flex items-center gap-2 p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition border border-transparent hover:border-blue-200 dark:hover:border-blue-800 text-sm text-left">
                        <div class="w-7 h-7 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-600 dark:text-gray-300 text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr(optional($nv->hoSo)->ten ?? 'N', 0, 1)) }}
                        </div>
                        <div class="truncate">
                            <p class="font-medium text-gray-700 dark:text-gray-300 truncate">
                                {{ optional($nv->hoSo)->ho }} {{ optional($nv->hoSo)->ten }}
                            </p>
                            <p class="text-xs text-gray-400 truncate">{{ optional($nv->hoSo)->ma_nhan_vien ?? 'N/A' }}</p>
                        </div>
                    </button>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 text-center">
        <p class="text-yellow-700 dark:text-yellow-400">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Hiện tại phòng ban chưa có nhân viên nào để tạo đơn tăng ca.
        </p>
    </div>
    @endif
</div>

<style>
#nhanVienSelect {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    padding-right: 2.5rem;
}
.dark #nhanVienSelect {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
}
#thongBaoNgay.hidden {
    display: none !important;
}
</style>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ⭐ CẤU HÌNH GIỜ HÀNH CHÍNH
    const GIO_HANH_CHINH = 17;
    const PHUT_HANH_CHINH = 30;

    // Chọn nhân viên từ danh sách
    window.chonNhanVien = function(id, ten, ma, chucVu) {
        const select = document.getElementById('nhanVienSelect');
        select.value = id;
        select.dispatchEvent(new Event('change'));
        document.querySelector('form').scrollIntoView({ behavior: 'smooth', block: 'center' });
    };

    // Hiển thị thông tin nhân viên khi chọn
    document.getElementById('nhanVienSelect').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const infoDiv = document.getElementById('nhanVienInfo');
        
        if (this.value) {
            const text = selectedOption.text;
            const parts = text.split(' - ');
            const ma = parts[0] || 'N/A';
            const nameParts = parts[1] ? parts[1].split(' (') : ['N/A'];
            const name = nameParts[0] || 'N/A';
            const chucVu = nameParts[1] ? nameParts[1].replace(')', '') : 'N/A';
            
            document.getElementById('nhanVienInitial').textContent = name.charAt(0).toUpperCase();
            document.getElementById('nhanVienName').textContent = name;
            document.getElementById('nhanVienMa').textContent = ma;
            document.getElementById('nhanVienChucVu').textContent = chucVu;
            infoDiv.classList.remove('hidden');
        } else {
            infoDiv.classList.add('hidden');
        }
    });

    // Trigger change nếu đã chọn
    const select = document.getElementById('nhanVienSelect');
    if (select.value) {
        select.dispatchEvent(new Event('change'));
    }

    // ⭐ KIỂM TRA NGÀY CUỐI TUẦN VÀ GỢI Ý LOẠI TĂNG CA
    function kiemTraNgay() {
        const ngayInput = document.getElementById('ngayTangCa');
        const loaiSelect = document.getElementById('loaiTangCa');
        const thongBaoDiv = document.getElementById('thongBaoNgay');
        const thongBaoText = document.getElementById('thongBaoNgayText');
        const loaiNote = document.getElementById('loaiTangCaNote');
        
        if (!ngayInput || !ngayInput.value) {
            if (thongBaoDiv) thongBaoDiv.classList.add('hidden');
            return;
        }
        
        const ngay = new Date(ngayInput.value + 'T00:00:00');
        const thu = ngay.getDay(); // 0 = Chủ Nhật, 6 = Thứ 7
        const isWeekend = thu === 0 || thu === 6;
        
        const thuNames = ['Chủ Nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'];
        const tenThu = thuNames[thu];
        
        if (!thongBaoDiv) return;
        
        thongBaoDiv.classList.remove('hidden');
        
        if (isWeekend) {
            // 🎉 NGÀY CUỐI TUẦN
            thongBaoDiv.className = 'col-span-1 md:col-span-2 p-3 rounded-lg border border-orange-200 bg-orange-50 dark:bg-orange-900/20 dark:border-orange-800 mb-2';
            thongBaoText.innerHTML = `
                <span class="font-medium text-orange-700 dark:text-orange-400">
                    🎉 Ngày ${tenThu} (${ngayInput.value.split('-').reverse().join('/')}) là ngày cuối tuần
                </span>
                <span class="text-sm text-orange-600 dark:text-orange-300 ml-2">
                    → Tất cả giờ làm đều được tính là tăng ca (200%)
                </span>
            `;
            
            // Tự động chọn ngày cuối tuần nếu đang chọn ngày thường
            if (loaiSelect && loaiSelect.value === 'ngay_thuong') {
                loaiSelect.value = 'ngay_nghi';
            }
            
            // Cập nhật ghi chú
            if (loaiNote) {
                loaiNote.innerHTML = '<span class="text-orange-600 dark:text-orange-400">✅ Ngày cuối tuần - Tất cả giờ làm đều là tăng ca (200%)</span>';
            }
            
            // Cập nhật ghi chú giờ bắt đầu
            const gioNote = document.getElementById('gioBatDauNote');
            if (gioNote) {
                gioNote.innerHTML = '<i class="fas fa-info-circle mr-1 text-green-500"></i> ✅ Ngày cuối tuần - Không giới hạn giờ hành chính';
                gioNote.className = 'text-xs text-green-600 dark:text-green-400 mt-1';
            }
            
        } else {
            // 📅 NGÀY THƯỜNG
            thongBaoDiv.className = 'col-span-1 md:col-span-2 p-3 rounded-lg border border-blue-200 bg-blue-50 dark:bg-blue-900/20 dark:border-blue-800 mb-2';
            thongBaoText.innerHTML = `
                <span class="font-medium text-blue-700 dark:text-blue-400">
                    📅 Ngày ${tenThu} (${ngayInput.value.split('-').reverse().join('/')}) là ngày thường
                </span>
                <span class="text-sm text-blue-600 dark:text-blue-300 ml-2">
                    → Tăng ca chỉ áp dụng sau giờ hành chính (${GIO_HANH_CHINH}:${String(PHUT_HANH_CHINH).padStart(2, '0')})
                </span>
            `;
            
            // Tự động chọn ngày thường nếu đang chọn ngày cuối tuần
            if (loaiSelect && loaiSelect.value === 'ngay_nghi') {
                loaiSelect.value = 'ngay_thuong';
            }
            
            // Cập nhật ghi chú
            if (loaiNote) {
                loaiNote.innerHTML = `<span class="text-blue-600 dark:text-blue-400">⚠️ Ngày thường - Tăng ca chỉ áp dụng sau ${GIO_HANH_CHINH}:${String(PHUT_HANH_CHINH).padStart(2, '0')}</span>`;
            }
            
            // Cập nhật ghi chú giờ bắt đầu
            const gioNote = document.getElementById('gioBatDauNote');
            if (gioNote) {
                gioNote.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i> ⚠️ Giờ tăng ca phải sau ${GIO_HANH_CHINH}:${String(PHUT_HANH_CHINH).padStart(2, '0')} (giờ hành chính) và trong tương lai`;
                gioNote.className = 'text-xs text-red-500 dark:text-red-400 mt-1';
            }
        }
    }

    // ⭐ TÍNH SỐ GIỜ TĂNG CA TỰ ĐỘNG
    function tinhSoGio() {
        const start = document.getElementById('gioBatDau').value;
        const end = document.getElementById('gioKetThuc').value;
        const display = document.getElementById('soGioHienThi');

        if (start && end) {
            const startTime = new Date('2000-01-01T' + start + ':00');
            const endTime = new Date('2000-01-01T' + end + ':00');
            let diff = (endTime - startTime) / (1000 * 60 * 60);
            if (diff < 0) diff += 24;
            display.textContent = diff.toFixed(1);
        } else {
            display.textContent = '0';
        }
    }

    // ⭐ GẮN SỰ KIỆN KHI THAY ĐỔI GIỜ
    document.getElementById('gioBatDau').addEventListener('change', tinhSoGio);
    document.getElementById('gioKetThuc').addEventListener('change', tinhSoGio);
    document.getElementById('gioBatDau').addEventListener('input', tinhSoGio);
    document.getElementById('gioKetThuc').addEventListener('input', tinhSoGio);
    
    // ⭐ TÍNH NGAY KHI LOAD TRANG
    tinhSoGio();

    // Đếm số ký tự lý do
    document.getElementById('lyDo').addEventListener('input', function() {
        document.getElementById('lyDoCount').textContent = this.value.length + '/500';
    });

    // ⭐ KIỂM TRA TRƯỚC KHI SUBMIT
    document.getElementById('tangCaForm').addEventListener('submit', function(e) {
        const soGio = parseFloat(document.getElementById('soGioHienThi').textContent);
        const ngayTangCa = document.getElementById('ngayTangCa').value;
        const gioBatDau = document.getElementById('gioBatDau').value;
        const gioKetThuc = document.getElementById('gioKetThuc').value;
        const loaiTangCa = document.getElementById('loaiTangCa').value;
        
        // 1️⃣ Kiểm tra số giờ
        if (soGio > 8) {
            e.preventDefault();
            alert('⚠️ Số giờ tăng ca không được vượt quá 8 giờ/ngày!');
            return false;
        }
        if (soGio < 0.5) {
            e.preventDefault();
            alert('⚠️ Số giờ tăng ca tối thiểu là 0.5 giờ!');
            return false;
        }

        // 2️⃣ ⭐ KIỂM TRA LOẠI TĂNG CA PHÙ HỢP VỚI NGÀY
        if (ngayTangCa) {
            const ngay = new Date(ngayTangCa + 'T00:00:00');
            const thu = ngay.getDay();
            const isWeekend = thu === 0 || thu === 6;
            
            if (isWeekend && loaiTangCa === 'ngay_thuong') {
                e.preventDefault();
                alert('⚠️ Ngày ' + ngayTangCa.split('-').reverse().join('/') + ' là ngày cuối tuần! Vui lòng chọn loại "Ngày cuối tuần (200%)"');
                document.getElementById('loaiTangCa').focus();
                return false;
            }
            
            if (!isWeekend && loaiTangCa === 'ngay_nghi') {
                e.preventDefault();
                alert('⚠️ Ngày ' + ngayTangCa.split('-').reverse().join('/') + ' không phải ngày cuối tuần! Vui lòng chọn loại "Ngày thường (150%)"');
                document.getElementById('loaiTangCa').focus();
                return false;
            }
        }

        // 3️⃣ ⭐ KIỂM TRA GIỜ HÀNH CHÍNH CHO NGÀY THƯỜNG
        if (ngayTangCa && loaiTangCa !== 'le_tet') {
            const ngay = new Date(ngayTangCa + 'T00:00:00');
            const thu = ngay.getDay();
            const isWeekend = thu === 0 || thu === 6;
            
            // Chỉ kiểm tra giờ hành chính cho ngày thường
            if (!isWeekend) {
                if (gioBatDau) {
                    const gio = parseInt(gioBatDau.split(':')[0]);
                    const phut = parseInt(gioBatDau.split(':')[1]);
                    
                    if (gio < GIO_HANH_CHINH || (gio === GIO_HANH_CHINH && phut < PHUT_HANH_CHINH)) {
                        e.preventDefault();
                        alert('⚠️ Giờ tăng ca phải bắt đầu sau giờ hành chính (' + GIO_HANH_CHINH + ':' + String(PHUT_HANH_CHINH).padStart(2, '0') + ')!');
                        document.getElementById('gioBatDau').focus();
                        return false;
                    }
                }
            }
        }

        // 4️⃣ ⭐ KIỂM TRA THỜI GIAN BẮT ĐẦU TRONG TƯƠNG LAI
        if (ngayTangCa && gioBatDau) {
            const now = new Date();
            const dateParts = ngayTangCa.split('-');
            const timeParts = gioBatDau.split(':');
            
            const startTime = new Date(
                parseInt(dateParts[0]),
                parseInt(dateParts[1]) - 1,
                parseInt(dateParts[2]),
                parseInt(timeParts[0]),
                parseInt(timeParts[1])
            );
            
            if (startTime < now) {
                e.preventDefault();
                alert('⛔ Không thể tạo đơn tăng ca cho thời gian đã qua! Vui lòng chọn giờ bắt đầu trong tương lai.');
                document.getElementById('gioBatDau').focus();
                return false;
            }
        }

        // 5️⃣ Kiểm tra giờ kết thúc sau giờ bắt đầu
        if (gioBatDau && gioKetThuc) {
            const start = new Date('2000-01-01T' + gioBatDau + ':00');
            const end = new Date('2000-01-01T' + gioKetThuc + ':00');
            if (end <= start) {
                e.preventDefault();
                alert('⛔ Giờ kết thúc phải sau giờ bắt đầu!');
                document.getElementById('gioKetThuc').focus();
                return false;
            }
        }

        if (!confirm('Xác nhận tạo đơn tăng ca cho nhân viên này?')) {
            e.preventDefault();
            return false;
        }
    });

    // ⭐ KIỂM TRA REAL-TIME KHI CHỌN GIỜ
    document.getElementById('gioBatDau').addEventListener('change', function() {
        const ngayTangCa = document.getElementById('ngayTangCa').value;
        const gioBatDau = this.value;
        
        if (ngayTangCa && gioBatDau) {
            const now = new Date();
            const dateParts = ngayTangCa.split('-');
            const timeParts = gioBatDau.split(':');
            
            const startTime = new Date(
                parseInt(dateParts[0]),
                parseInt(dateParts[1]) - 1,
                parseInt(dateParts[2]),
                parseInt(timeParts[0]),
                parseInt(timeParts[1])
            );
            
            if (startTime < now) {
                this.style.borderColor = '#ef4444';
                this.style.backgroundColor = '#fef2f2';
            } else {
                this.style.borderColor = '';
                this.style.backgroundColor = '';
            }
        }
    });

    // ⭐ GẮN SỰ KIỆN CHO NGÀY
    const ngayInput = document.getElementById('ngayTangCa');
    if (ngayInput) {
        ngayInput.addEventListener('change', kiemTraNgay);
        ngayInput.addEventListener('input', kiemTraNgay);
        setTimeout(kiemTraNgay, 200);
    }

    // ⭐ GẮN SỰ KIỆN CHO LOẠI TĂNG CA
    document.getElementById('loaiTangCa').addEventListener('change', function() {
        // Kiểm tra lại khi người dùng thay đổi loại tăng ca
        kiemTraNgay();
    });
});
</script>
@endpush