@extends('layouts.admin')

@section('title', 'Chỉnh sửa hợp đồng')

@section('content')
<div class="space-y-6 bg-gray-50 dark:bg-[#0f172a] min-h-screen p-6">

    <div class="bg-white dark:bg-gray-800/80 backdrop-blur border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">✏️ Chỉnh sửa hợp đồng</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Cập nhật thông tin hợp đồng lao động</p>
            </div>
            <a href="{{ route('admin.hop-dong.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-xl transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Quay lại
            </a>
        </div>
    </div>

    @if(($hopDong->trang_thai_ky === 'cho_ky' && $hopDong->trang_thai_hop_dong === 'chua_hieu_luc') || $hopDong->trang_thai_ky === 'tu_choi_ky')
        <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-xl">
            ⚠️ Hợp đồng này không thể sửa đổi (đã gửi cho nhân viên hoặc đã từ chối ký).
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 text-red-700 px-4 py-3 rounded-xl">
            <div class="font-semibold mb-2">Có lỗi xảy ra:</div>
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $canEdit = in_array($hopDong->trang_thai_hop_dong, ['tao_moi', 'chua_hieu_luc']) && $hopDong->trang_thai_ky !== 'tu_choi_ky';
    @endphp

    <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
        <form action="{{ route('admin.hop-dong.update', $hopDong->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Nhân viên --}}
                    <div>
                        <label class="block text-sm font-medium mb-2">Nhân viên</label>
                        <input type="text" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" 
                               value="{{ $hopDong->hoSoNguoiDung ? $hopDong->hoSoNguoiDung->ho . ' ' . $hopDong->hoSoNguoiDung->ten : 'N/A' }}" readonly>
                        <input type="hidden" name="nguoi_dung_id" value="{{ $hopDong->nguoi_dung_id }}">
                    </div>

                    {{-- Chức vụ --}}
                    <div>
                        <label class="block text-sm font-medium mb-2">Chức vụ <span class="text-red-500">*</span></label>
                        <select name="chuc_vu_id" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white" {{ !$canEdit ? 'disabled' : '' }}>
                            @foreach($chucVus as $cv)
                                <option value="{{ $cv->id }}" {{ $hopDong->chuc_vu_id == $cv->id ? 'selected' : '' }}>{{ $cv->ten }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Số hợp đồng --}}
                    <div>
                        <label class="block text-sm font-medium mb-2">Số hợp đồng</label>
                        <input type="text" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" 
                               value="{{ $hopDong->so_hop_dong }}" readonly>
                        <input type="hidden" name="so_hop_dong" value="{{ $hopDong->so_hop_dong }}">
                    </div>

                    {{-- Loại hợp đồng --}}
                    <div>
                        <label class="block text-sm font-medium mb-2">Loại hợp đồng <span class="text-red-500">*</span></label>
                        <select name="loai_hop_dong" id="loai_hop_dong" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white" {{ !$canEdit ? 'disabled' : '' }}>
                            <option value="xac_dinh_thoi_han" {{ $hopDong->loai_hop_dong == 'xac_dinh_thoi_han' ? 'selected' : '' }}>Xác định thời hạn</option>
                            <option value="khong_xac_dinh_thoi_han" {{ $hopDong->loai_hop_dong == 'khong_xac_dinh_thoi_han' ? 'selected' : '' }}>Không xác định</option>
                        </select>
                    </div>

                    {{-- Ngày bắt đầu --}}
                    <div>
                        <label class="block text-sm font-medium mb-2">Ngày bắt đầu <span class="text-red-500">*</span></label>
                        <input type="date" name="ngay_bat_dau" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white" 
                               value="{{ $hopDong->ngay_bat_dau ? (is_string($hopDong->ngay_bat_dau) ? date('Y-m-d', strtotime($hopDong->ngay_bat_dau)) : $hopDong->ngay_bat_dau->format('Y-m-d')) : '' }}" 
                               {{ !$canEdit ? 'readonly' : '' }} required>
                    </div>

                    {{-- Ngày kết thúc --}}
                    <div>
                        <label class="block text-sm font-medium mb-2">Ngày kết thúc</label>
                        <input type="date" name="ngay_ket_thuc" id="ngay_ket_thuc" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white" 
                               value="{{ $hopDong->ngay_ket_thuc ? (is_string($hopDong->ngay_ket_thuc) ? date('Y-m-d', strtotime($hopDong->ngay_ket_thuc)) : $hopDong->ngay_ket_thuc->format('Y-m-d')) : '' }}" 
                               {{ !$canEdit ? 'readonly' : '' }}>
                    </div>

                    {{-- Lương cơ bản --}}
                    <div>
                        <label class="block text-sm font-medium mb-2">Lương cơ bản <span class="text-red-500">*</span></label>
                        <input type="text" name="luong_co_ban" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white" 
                               value="{{ (int) $hopDong->luong_co_ban }}" {{ !$canEdit ? 'readonly' : '' }} required>
                    </div>

                    {{-- ===== PHỤ CẤP TỪ BẢNG PHU_CAP ===== --}}
                    <div>
                        <label class="block text-sm font-medium mb-2">Phụ cấp</label>
                        <select name="phu_cap_ids[]" id="phu_cap_ids" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white" 
                                multiple {{ !$canEdit ? 'disabled' : '' }}>
                            @foreach($phuCaps as $pc)
                                <option value="{{ $pc->id }}" {{ in_array($pc->id, $selectedPhuCapIds ?? []) ? 'selected' : '' }}>
                                    {{ $pc->ten }} - {{ number_format($pc->so_tien_mac_dinh, 0, ',', '.') }} đ
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
                                @php
                                    $selectedPhuCapNames = [];
                                    if(isset($selectedPhuCapIds) && count($selectedPhuCapIds) > 0) {
                                        foreach($phuCaps as $pc) {
                                            if(in_array($pc->id, $selectedPhuCapIds)) {
                                                $selectedPhuCapNames[] = $pc->ten . ': ' . number_format($pc->so_tien_mac_dinh, 0, ',', '.') . ' đ';
                                            }
                                        }
                                    }
                                @endphp
                                @if(count($selectedPhuCapNames) > 0)
                                    @foreach($selectedPhuCapNames as $name)
                                        <span class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full text-sm">
                                            {{ $name }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Chưa có phụ cấp nào được chọn</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Địa điểm làm việc --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">Địa điểm làm việc <span class="text-red-500">*</span></label>
                        <input type="text" name="dia_diem_lam_viec" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white" 
                               value="{{ $hopDong->dia_diem_lam_viec }}" {{ !$canEdit ? 'readonly' : '' }} required>
                    </div>

                    {{-- Điều khoản --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">Điều khoản hợp đồng <span class="text-red-500">*</span></label>
                        <textarea name="dieu_khoan" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white" 
                                  rows="5" {{ !$canEdit ? 'readonly' : '' }} required>{{ $hopDong->dieu_khoan }}</textarea>
                    </div>

                    {{-- Ghi chú --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">Ghi chú</label>
                        <textarea name="ghi_chu" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white" 
                                  rows="3" {{ !$canEdit ? 'readonly' : '' }}>{{ $hopDong->ghi_chu }}</textarea>
                    </div>

                    {{-- File hợp đồng hiện tại --}}
                    @if($hopDong->duong_dan_file)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">File hợp đồng hiện tại</label>
                        @foreach(array_filter(explode(';', $hopDong->duong_dan_file)) as $file)
                            <a href="{{ asset('storage/' . trim($file)) }}" target="_blank" class="inline-flex items-center gap-2 p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg mr-2 text-blue-700 dark:text-blue-300">
                                📄 {{ basename(trim($file)) }}
                            </a>
                        @endforeach
                    </div>
                    @endif

                    {{-- Upload file hợp đồng mới --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">File hợp đồng mới (tùy chọn, upload để thay thế file cũ)</label>
                        <input type="file" name="file_hop_dong[]" multiple class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white" {{ !$canEdit ? 'disabled' : '' }}>
                        <p class="text-xs text-gray-500 mt-1">Chấp nhận PDF, DOC, DOCX. Tối đa 5MB mỗi file.</p>
                    </div>

                    {{-- File đính kèm hiện tại --}}
                    @if($hopDong->file_dinh_kem)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">File đính kèm hiện tại</label>
                        <a href="{{ asset('storage/' . $hopDong->file_dinh_kem) }}" target="_blank" class="inline-flex items-center gap-2 p-2 bg-green-50 dark:bg-green-900/30 rounded-lg text-green-700 dark:text-green-300">
                            📎 {{ basename($hopDong->file_dinh_kem) }}
                        </a>
                    </div>
                    @endif

                    {{-- Upload file đính kèm mới --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">File đính kèm mới (tùy chọn, upload để thay thế file cũ)</label>
                        <input type="file" name="file_dinh_kem" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white" {{ !$canEdit ? 'disabled' : '' }}>
                        <p class="text-xs text-gray-500 mt-1">Chấp nhận PDF, DOC, DOCX, JPG, JPEG, PNG. Tối đa 5MB.</p>
                    </div>

                    {{-- Trạng thái ký --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">Trạng thái ký</label>
                        <select name="trang_thai_ky" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white" {{ !$canEdit ? 'disabled' : '' }}>
                            <option value="cho_ky" {{ $hopDong->trang_thai_ky == 'cho_ky' ? 'selected' : '' }}>Chờ ký</option>
                            <option value="da_ky" {{ $hopDong->trang_thai_ky == 'da_ky' ? 'selected' : '' }}>Đã ký</option>
                        </select>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    @if($canEdit)
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md hover:shadow-lg transition">💾 Lưu thay đổi</button>
                    @endif
                    <a href="{{ route('admin.hop-dong.index') }}" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-xl transition">← Quay lại</a>
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
        
        if (loaiHopDong && ngayKetThuc) {
            const toggleNgayKetThuc = () => {
                if (loaiHopDong.value === 'khong_xac_dinh_thoi_han') {
                    ngayKetThuc.disabled = true;
                    ngayKetThuc.value = '';
                } else {
                    ngayKetThuc.disabled = false;
                }
            };
            loaiHopDong.addEventListener('change', toggleNgayKetThuc);
            toggleNgayKetThuc();
        }

        // ===== XỬ LÝ PHỤ CẤP =====
        const phuCapSelect = document.getElementById('phu_cap_ids');
        const phuCapList = document.getElementById('phu_cap_list');

        if (phuCapSelect && phuCapList) {
            function updatePhuCapList() {
                const selectedOptions = phuCapSelect.selectedOptions;
                const phuCapItems = [];

                for (let option of selectedOptions) {
                    phuCapItems.push(option.text);
                }

                if (phuCapItems.length === 0) {
                    phuCapList.innerHTML = '<span class="text-sm text-gray-500 dark:text-gray-400">Chưa có phụ cấp nào được chọn</span>';
                } else {
                    phuCapList.innerHTML = phuCapItems.map(item => 
                        `<span class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full text-sm">
                            ${item}
                        </span>`
                    ).join(' ');
                }
            }

            phuCapSelect.addEventListener('change', updatePhuCapList);
            // Cập nhật khi trang load
            updatePhuCapList();
        }
    });
</script>
@endsection