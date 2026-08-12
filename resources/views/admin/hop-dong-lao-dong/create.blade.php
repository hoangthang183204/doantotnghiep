{{-- resources/views/admin/hop-dong-lao-dong/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Thêm hợp đồng lao động')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">📄 Thêm hợp đồng lao động</h1>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Nhập thông tin hợp đồng lao động mới</p>
                </div>
                <a href="{{ route('admin.hop-dong.index') }}"
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                    ← Quay lại
                </a>
            </div>
        </div>

        {{-- ALERT ERRORS --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <div class="font-medium mb-2">❌ Có lỗi xảy ra:</div>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <form action="{{ route('admin.hop-dong.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Nhân viên --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nhân viên <span class="text-red-500">*</span>
                            </label>
                            <select name="nguoi_dung_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500"
                                required>
                                <option value="">-- Chọn nhân viên --</option>
                                @foreach ($nhanViens as $nv)
                                    <option value="{{ $nv->id }}"
                                        {{ old('nguoi_dung_id') == $nv->id ? 'selected' : '' }}>
                                        {{ optional($nv->hoSo)->ho ?? '' }} {{ optional($nv->hoSo)->ten ?? '' }}
                                        ({{ optional($nv->hoSo)->ma_nhan_vien ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Chức vụ --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Chức vụ <span class="text-red-500">*</span>
                            </label>
                            <select name="chuc_vu_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500"
                                required>
                                <option value="">-- Chọn chức vụ --</option>
                                @foreach ($chucVus as $cv)
                                    <option value="{{ $cv->id }}"
                                        {{ old('chuc_vu_id') == $cv->id ? 'selected' : '' }}>
                                        {{ $cv->ten }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Số hợp đồng --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Số hợp đồng <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="so_hop_dong"
                                value="{{ old('so_hop_dong', $soHopDongTuDong ?? '') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>

                        {{-- Loại hợp đồng --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Loại hợp đồng <span class="text-red-500">*</span>
                            </label>
                            <select name="loai_hop_dong" id="loai_hop_dong"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500"
                                required>
                                <option value="xac_dinh_thoi_han"
                                    {{ old('loai_hop_dong') == 'xac_dinh_thoi_han' ? 'selected' : '' }}>Xác định thời hạn
                                </option>
                                <option value="khong_xac_dinh_thoi_han"
                                    {{ old('loai_hop_dong') == 'khong_xac_dinh_thoi_han' ? 'selected' : '' }}>Không xác
                                    định thời hạn</option>
                            </select>
                        </div>

                        {{-- Ngày bắt đầu --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ngày bắt đầu <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="ngay_bat_dau" value="{{ old('ngay_bat_dau') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>

                        {{-- Ngày kết thúc --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ngày kết thúc <span class="text-red-500" id="ngay_ket_thuc_required"
                                    style="display: none;">*</span>
                            </label>
                            <input type="date" name="ngay_ket_thuc" id="ngay_ket_thuc"
                                value="{{ old('ngay_ket_thuc') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        {{-- Lương cơ bản --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Lương cơ bản <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="1000" name="luong_co_ban" value="{{ old('luong_co_ban') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>

                        {{-- Phụ cấp --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Phụ cấp
                            </label>
                            <select name="phu_cap_ids[]" id="phu_cap_ids"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500"
                                multiple>
                                @foreach ($phuCaps as $pc)
                                    <option value="{{ $pc->id }}"
                                        {{ old('phu_cap_ids') && in_array($pc->id, old('phu_cap_ids')) ? 'selected' : '' }}>
                                        {{ $pc->ten }} - {{ number_format($pc->so_tien_mac_dinh) }} VND
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Giữ Ctrl để chọn nhiều phụ cấp</p>
                        </div>

                        {{-- Danh sách phụ cấp đã chọn --}}
                        <div id="selected_phu_caps" class="col-span-2">
                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📋 Phụ cấp đã chọn:</p>
                                <div id="phu_cap_list" class="flex flex-wrap gap-2">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Chưa có phụ cấp nào được
                                        chọn</span>
                                </div>
                            </div>
                        </div>

                        {{-- Địa điểm làm việc --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Địa điểm làm việc <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="dia_diem_lam_viec" value="{{ old('dia_diem_lam_viec') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>

                        {{-- Điều khoản --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Điều khoản hợp đồng <span class="text-red-500">*</span>
                            </label>
                            <textarea name="dieu_khoan" rows="5"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500"
                                required>{{ old('dieu_khoan', 'Bảo mật') }}</textarea>
                        </div>

                        {{-- 🔥 THÔNG BÁO TỰ ĐỘNG TẠO PDF --}}
                        <div class="md:col-span-2">
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 flex items-start gap-3">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-blue-700 dark:text-blue-300">📄 File PDF hợp đồng sẽ được tạo tự động</p>
                                    <p class="text-sm text-blue-600 dark:text-blue-400 mt-1">
                                        Sau khi lưu hợp đồng, hệ thống sẽ tự động sinh file PDF từ dữ liệu bạn nhập.
                                        Bạn không cần upload file hợp đồng thủ công.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Ghi chú --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ghi chú
                            </label>
                            <textarea name="ghi_chu" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('ghi_chu') }}</textarea>
                        </div>

                    </div>

                    {{-- BUTTONS --}}
                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.hop-dong.index') }}"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            Hủy
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                            💾 Lưu hợp đồng
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ===== XỬ LÝ LOẠI HỢP ĐỒNG =====
            const loaiHopDong = document.getElementById('loai_hop_dong');
            const ngayKetThuc = document.getElementById('ngay_ket_thuc');
            const ngayKetThucRequired = document.getElementById('ngay_ket_thuc_required');

            function toggleNgayKetThuc() {
                if (loaiHopDong.value === 'khong_xac_dinh_thoi_han') {
                    ngayKetThuc.disabled = true;
                    ngayKetThuc.value = '';
                    ngayKetThuc.required = false;
                    if (ngayKetThucRequired) ngayKetThucRequired.style.display = 'none';
                } else {
                    ngayKetThuc.disabled = false;
                    ngayKetThuc.required = true;
                    if (ngayKetThucRequired) ngayKetThucRequired.style.display = 'inline';
                }
            }

            loaiHopDong?.addEventListener('change', toggleNgayKetThuc);
            toggleNgayKetThuc();

            // ===== XỬ LÝ PHỤ CẤP =====
            const phuCapSelect = document.getElementById('phu_cap_ids');
            const phuCapList = document.getElementById('phu_cap_list');

            function updatePhuCapList() {
                const selectedOptions = phuCapSelect.selectedOptions;
                const phuCapItems = [];

                for (let option of selectedOptions) {
                    phuCapItems.push(option.text);
                }

                if (phuCapItems.length === 0) {
                    phuCapList.innerHTML =
                        '<span class="text-sm text-gray-500 dark:text-gray-400">Chưa có phụ cấp nào được chọn</span>';
                } else {
                    phuCapList.innerHTML = phuCapItems.map(item =>
                        `<span class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full text-sm">
                            ${item}
                        </span>`
                    ).join(' ');
                }
            }

            phuCapSelect?.addEventListener('change', updatePhuCapList);
            updatePhuCapList();

            // ===== XỬ LÝ CHỌN NHÂN VIÊN =====
            const nhanVienSelect = document.querySelector('select[name="nguoi_dung_id"]');
            const luongCoBanInput = document.querySelector('input[name="luong_co_ban"]');

            nhanVienSelect?.addEventListener('change', function() {
                const userId = this.value;
                if (userId) {
                    fetch(`/admin/get-nhan-vien-info/${userId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (data.luong_co_ban) {
                                    luongCoBanInput.value = data.luong_co_ban;
                                }

                                const options = phuCapSelect.options;
                                for (let i = 0; i < options.length; i++) {
                                    options[i].selected = false;
                                }
                                updatePhuCapList();
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });
        });
    </script>
@endsection