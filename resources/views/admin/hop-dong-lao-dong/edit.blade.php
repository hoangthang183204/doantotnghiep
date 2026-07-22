@extends('layouts.admin')

@section('title', 'Chỉnh sửa hợp đồng')

@section('content')
    <div class="space-y-6 bg-gray-50 dark:bg-[#0f172a] min-h-screen p-6">

        {{-- HEADER --}}
        <div
            class="bg-white dark:bg-gray-800/80 backdrop-blur border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">✏️ Chỉnh sửa hợp đồng</h1>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Cập nhật thông tin hợp đồng lao động</p>
                </div>
                <a href="{{ route('admin.hop-dong.index') }}"
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-xl transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Quay lại
                </a>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- 🔥 KIỂM TRA HỢP ĐỒNG ĐÃ BỊ TỪ CHỐI HOẶC HỦY 🔥 --}}
        {{-- ============================================================ --}}
        @if ($hopDong->trang_thai_ky === 'tu_choi_ky' || $hopDong->trang_thai_hop_dong === 'huy_bo')
            <div
                class="bg-red-50 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-6 py-4 rounded-xl">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <h4 class="font-semibold text-base">❌ Không thể sửa hợp đồng này</h4>
                        <p class="text-sm mt-1">
                            @if ($hopDong->trang_thai_ky === 'tu_choi_ky')
                                Hợp đồng đã bị <strong>nhân viên từ chối ký</strong>.
                                @if ($hopDong->ghi_chu)
                                    <br>📝 <span class="font-medium">Lý do:</span>
                                    <span class="italic">{{ str_replace('Từ chối ký: ', '', $hopDong->ghi_chu) }}</span>
                                @endif
                            @elseif ($hopDong->trang_thai_hop_dong === 'huy_bo')
                                Hợp đồng đã bị <strong>hủy bỏ</strong>.
                                @if ($hopDong->ly_do_huy)
                                    <br>📝 <span class="font-medium">Lý do:</span>
                                    <span class="italic">{{ $hopDong->ly_do_huy }}</span>
                                @endif
                            @else
                                Hợp đồng không ở trạng thái có thể chỉnh sửa.
                            @endif
                        </p>
                        <div class="flex flex-wrap gap-3 mt-4">
                            <a href="{{ route('admin.hop-dong.show', $hopDong->id) }}"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition shadow-sm">
                                📄 Xem chi tiết
                            </a>
                            <form action="{{ route('admin.hop-dong.tai-ky', $hopDong->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-lg transition shadow-sm"
                                    onclick="return confirm('🔄 Tạo lại hợp đồng mới dựa trên hợp đồng này?\n\nHợp đồng mới sẽ được tạo với thông tin tương tự và gửi lên duyệt.')">
                                    🔄 Tạo lại hợp đồng
                                </button>
                            </form>
                            @if (in_array($hopDong->trang_thai_hop_dong, ['tao_moi', 'het_han', 'huy_bo']) &&
                                    auth()->user()->vaiTros->first()->name === 'admin')
                                <form action="{{ route('admin.hop-dong.destroy', $hopDong->id) }}" method="POST"
                                    onsubmit="return confirm('🗑️ Bạn có chắc muốn xóa hợp đồng {{ $hopDong->so_hop_dong }}?')"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg transition shadow-sm">
                                        🗑️ Xóa hợp đồng
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('admin.hop-dong.index') }}"
                                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-lg transition shadow-sm">
                                ← Quay lại danh sách
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ẨN FORM SỬA --}}
            @php $canEdit = false; @endphp
        @elseif ($hopDong->trang_thai_ky === 'cho_ky' && $hopDong->trang_thai_hop_dong === 'chua_hieu_luc')
            {{-- Đã gửi cho nhân viên nhưng chưa ký --}}
            <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-xl">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-yellow-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <span class="font-medium">⏳ Hợp đồng đã gửi cho nhân viên và đang chờ ký.</span>
                        <span class="text-sm block mt-0.5">Không thể sửa đổi hợp đồng này. Vui lòng đợi nhân viên ký hoặc
                            hủy hợp đồng.</span>
                    </div>
                </div>
            </div>
            @php $canEdit = false; @endphp
        @else
            {{-- Có thể sửa --}}
            @php
                $canEdit =
                    in_array($hopDong->trang_thai_hop_dong, ['tao_moi', 'chua_hieu_luc']) &&
                    $hopDong->trang_thai_ky !== 'tu_choi_ky' &&
                    $hopDong->trang_thai_ky !== 'da_ky' &&
                    $hopDong->trang_thai_hop_dong !== 'huy_bo';
            @endphp
        @endif

        {{-- ============================================================ --}}
        {{-- HIỂN THỊ LỖI VALIDATION --}}
        {{-- ============================================================ --}}
        @if ($errors->any())
            <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 text-red-700 px-4 py-3 rounded-xl">
                <div class="font-semibold mb-2">❌ Có lỗi xảy ra:</div>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ============================================================ --}}
        {{-- FORM SỬA - CHỈ HIỂN THỊ KHI CÓ THỂ SỬA --}}
        {{-- ============================================================ --}}
        @if ($canEdit)
            <div
                class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
                <form action="{{ route('admin.hop-dong.update', $hopDong->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Nhân viên --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nhân
                                    viên</label>
                                <input type="text"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 cursor-not-allowed"
                                    value="{{ $hopDong->hoSoNguoiDung ? $hopDong->hoSoNguoiDung->ho . ' ' . $hopDong->hoSoNguoiDung->ten : 'N/A' }}"
                                    readonly disabled>
                                <input type="hidden" name="nguoi_dung_id" value="{{ $hopDong->nguoi_dung_id }}">
                            </div>

                            {{-- Chức vụ --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Chức vụ <span
                                        class="text-red-500">*</span></label>
                                <select name="chuc_vu_id"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 transition"
                                    required>
                                    @foreach ($chucVus as $cv)
                                        <option value="{{ $cv->id }}"
                                            {{ $hopDong->chuc_vu_id == $cv->id ? 'selected' : '' }}>{{ $cv->ten }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Số hợp đồng --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Số hợp
                                    đồng</label>
                                <input type="text"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 cursor-not-allowed"
                                    value="{{ $hopDong->so_hop_dong }}" readonly disabled>
                                <input type="hidden" name="so_hop_dong" value="{{ $hopDong->so_hop_dong }}">
                            </div>

                            {{-- Loại hợp đồng --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Loại hợp đồng
                                    <span class="text-red-500">*</span></label>
                                <select name="loai_hop_dong" id="loai_hop_dong"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 transition"
                                    required>
                                    <option value="xac_dinh_thoi_han"
                                        {{ $hopDong->loai_hop_dong == 'xac_dinh_thoi_han' ? 'selected' : '' }}>Xác định
                                        thời hạn</option>
                                    <option value="khong_xac_dinh_thoi_han"
                                        {{ $hopDong->loai_hop_dong == 'khong_xac_dinh_thoi_han' ? 'selected' : '' }}>Không
                                        xác định</option>
                                </select>
                            </div>

                            {{-- Ngày bắt đầu --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ngày bắt đầu
                                    <span class="text-red-500">*</span></label>
                                <input type="date" name="ngay_bat_dau"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 transition"
                                    value="{{ $hopDong->ngay_bat_dau ? (is_string($hopDong->ngay_bat_dau) ? date('Y-m-d', strtotime($hopDong->ngay_bat_dau)) : $hopDong->ngay_bat_dau->format('Y-m-d')) : '' }}"
                                    required>
                            </div>

                            {{-- Ngày kết thúc --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ngày kết
                                    thúc</label>
                                <input type="date" name="ngay_ket_thuc" id="ngay_ket_thuc"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 transition"
                                    value="{{ $hopDong->ngay_ket_thuc ? (is_string($hopDong->ngay_ket_thuc) ? date('Y-m-d', strtotime($hopDong->ngay_ket_thuc)) : $hopDong->ngay_ket_thuc->format('Y-m-d')) : '' }}">
                            </div>

                            {{-- Lương cơ bản --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lương cơ bản
                                    <span class="text-red-500">*</span></label>
                                <input type="number" step="1000" name="luong_co_ban"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 transition"
                                    value="{{ (int) $hopDong->luong_co_ban }}" required>
                            </div>

                            {{-- Phụ cấp --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phụ
                                    cấp</label>
                                <select name="phu_cap_ids[]" id="phu_cap_ids"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 transition"
                                    multiple>
                                    @foreach ($phuCaps as $pc)
                                        <option value="{{ $pc->id }}"
                                            {{ in_array($pc->id, $selectedPhuCapIds ?? []) ? 'selected' : '' }}>
                                            {{ $pc->ten }} - {{ number_format($pc->so_tien_mac_dinh, 0, ',', '.') }}
                                            đ
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">💡 Giữ Ctrl để chọn nhiều phụ cấp</p>
                            </div>

                            {{-- Danh sách phụ cấp đã chọn --}}
                            <div id="selected_phu_caps" class="col-span-2">
                                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📋 Phụ cấp đã
                                        chọn:</p>
                                    <div id="phu_cap_list" class="flex flex-wrap gap-2">
                                        @php
                                            $selectedPhuCapNames = [];
                                            if (isset($selectedPhuCapIds) && count($selectedPhuCapIds) > 0) {
                                                foreach ($phuCaps as $pc) {
                                                    if (in_array($pc->id, $selectedPhuCapIds)) {
                                                        $selectedPhuCapNames[] =
                                                            $pc->ten .
                                                            ': ' .
                                                            number_format($pc->so_tien_mac_dinh, 0, ',', '.') .
                                                            ' đ';
                                                    }
                                                }
                                            }
                                        @endphp
                                        @if (count($selectedPhuCapNames) > 0)
                                            @foreach ($selectedPhuCapNames as $name)
                                                <span
                                                    class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full text-sm">
                                                    {{ $name }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">Chưa có phụ cấp nào được
                                                chọn</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Địa điểm làm việc --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Địa điểm làm
                                    việc <span class="text-red-500">*</span></label>
                                <input type="text" name="dia_diem_lam_viec"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 transition"
                                    value="{{ $hopDong->dia_diem_lam_viec }}" required>
                            </div>

                            {{-- Điều khoản --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Điều khoản
                                    hợp đồng <span class="text-red-500">*</span></label>
                                <textarea name="dieu_khoan"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 transition"
                                    rows="5" required>{{ $hopDong->dieu_khoan }}</textarea>
                            </div>

                            {{-- Ghi chú --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ghi
                                    chú</label>
                                <textarea name="ghi_chu"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 transition"
                                    rows="3">{{ $hopDong->ghi_chu }}</textarea>
                            </div>

                            {{-- File hợp đồng hiện tại --}}
                            @if ($hopDong->duong_dan_file)
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📎 File
                                        hợp đồng hiện tại</label>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach (array_filter(explode(';', $hopDong->duong_dan_file)) as $file)
                                            <a href="{{ asset('storage/' . trim($file)) }}" target="_blank"
                                                class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-lg transition text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800 text-sm">
                                                📄 {{ basename(trim($file)) }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Upload file hợp đồng mới --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📤 File hợp
                                    đồng mới (tùy chọn, upload để thay thế file cũ)</label>
                                <input type="file" name="file_hop_dong[]" multiple
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition"
                                    accept=".pdf,.doc,.docx">
                                <p class="text-xs text-gray-500 mt-1">📌 Chấp nhận PDF, DOC, DOCX. Tối đa 5MB mỗi file.</p>
                            </div>

                            {{-- File đính kèm hiện tại --}}
                            @if ($hopDong->file_dinh_kem)
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📎 File
                                        đính kèm hiện tại</label>
                                    <a href="{{ asset('storage/' . $hopDong->file_dinh_kem) }}" target="_blank"
                                        class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-50 dark:bg-green-900/30 hover:bg-green-100 dark:hover:bg-green-900/50 rounded-lg transition text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800 text-sm">
                                        📎 {{ basename($hopDong->file_dinh_kem) }}
                                    </a>
                                </div>
                            @endif

                            {{-- Upload file đính kèm mới --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📤 File đính
                                    kèm mới (tùy chọn, upload để thay thế file cũ)</label>
                                <input type="file" name="file_dinh_kem"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition"
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <p class="text-xs text-gray-500 mt-1">📌 Chấp nhận PDF, DOC, DOCX, JPG, JPEG, PNG. Tối đa
                                    5MB.</p>
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex flex-wrap gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit"
                                class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md hover:shadow-lg transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                💾 Lưu thay đổi
                            </button>
                            <a href="{{ route('admin.hop-dong.show', $hopDong->id) }}"
                                class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-xl transition flex items-center gap-2">
                                📄 Xem chi tiết
                            </a>
                            <a href="{{ route('admin.hop-dong.index') }}"
                                class="px-6 py-2.5 bg-gray-400 hover:bg-gray-500 text-white rounded-xl transition flex items-center gap-2">
                                ← Quay lại danh sách
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        @elseif($hopDong->trang_thai_ky !== 'tu_choi_ky' && $hopDong->trang_thai_hop_dong !== 'huy_bo')
            {{-- HIỂN THỊ THÔNG BÁO KHÔNG THỂ SỬA (đã gửi hoặc đã ký) --}}
            <div
                class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-400 text-yellow-700 px-6 py-4 rounded-xl text-center">
                <svg class="w-12 h-12 mx-auto text-yellow-500 mb-2" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-lg font-semibold">⏳ Hợp đồng không thể sửa đổi</p>
                <p class="text-sm mt-1">Hợp đồng đã được gửi cho nhân viên hoặc đã được ký.</p>
                <div class="flex flex-wrap justify-center gap-3 mt-4">
                    <a href="{{ route('admin.hop-dong.show', $hopDong->id) }}"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition">
                        📄 Xem chi tiết
                    </a>
                    <a href="{{ route('admin.hop-dong.index') }}"
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-lg transition">
                        ← Quay lại danh sách
                    </a>
                </div>
            </div>
        @endif

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
                        ngayKetThuc.required = false;
                    } else {
                        ngayKetThuc.disabled = false;
                        ngayKetThuc.required = true;
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

                phuCapSelect.addEventListener('change', updatePhuCapList);
                // Cập nhật khi trang load
                updatePhuCapList();
            }
        });
    </script>

    <style>
        /* Tùy chỉnh style cho file input */
        input[type="file"]::file-selector-button {
            cursor: pointer;
        }

        input[type="file"]:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
@endsection
