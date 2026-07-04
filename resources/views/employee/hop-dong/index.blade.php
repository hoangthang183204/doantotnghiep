@extends('layouts.employee')

@section('content')
    <div class="p-4 max-w-7xl mx-auto space-y-4">

        {{-- ALERT --}}
        @if (session('success'))
            <div
                class="p-3 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800 flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-lg mr-2"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()"
                    class="text-green-800 dark:text-green-400 hover:opacity-70">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div
                class="p-3 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800 flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-lg mr-2"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()"
                    class="text-red-800 dark:text-red-400 hover:opacity-70">&times;</button>
            </div>
        @endif

        {{-- HEADER --}}
        <div class="flex flex-wrap justify-between items-center gap-3 pb-3 border-b border-gray-200 dark:border-gray-700">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">📄 Hợp Đồng Của Tôi</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Xem và quản lý hợp đồng lao động của bạn</p>
            </div>
            <button onclick="window.history.back();"
                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-1"></i> Quay lại
            </button>
        </div>

        @if (!$hopDong)
            <div
                class="flex flex-col items-center justify-center p-10 text-center bg-yellow-50 dark:bg-gray-800/50 rounded-xl border-2 border-dashed border-yellow-200 dark:border-yellow-700">
                <i class="fas fa-file-contract text-4xl text-yellow-400 mb-3"></i>
                <h4 class="text-lg font-bold text-gray-900 dark:text-white">Chưa có hợp đồng</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md">Vui lòng liên hệ bộ phận nhân sự để được hỗ
                    trợ.</p>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                {{-- LEFT COLUMN --}}
                <div class="lg:col-span-2 space-y-4">

                    {{-- CARD: THÔNG TIN HỢP ĐỒNG --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div
                            class="px-4 py-3 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-b border-gray-200 dark:border-gray-700 flex items-center">
                            <i class="fas fa-file-contract text-blue-600 dark:text-blue-400 mr-2"></i>
                            <span class="font-bold text-gray-800 dark:text-gray-200 text-sm">Thông tin hợp đồng</span>
                            <span
                                class="ml-auto text-xs px-2 py-0.5 rounded-full {{ ($hopDong->trang_thai_hop_dong ?? '') == 'hieu_luc' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' }}">
                                {{ ($hopDong->trang_thai_hop_dong ?? '') == 'hieu_luc' ? '✅ Hiệu lực' : '⏳ Chưa hiệu lực' }}
                            </span>
                        </div>
                        <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                            <div>
                                <span class="text-xs text-gray-400 uppercase block">Số HĐ</span>
                                <span
                                    class="font-semibold text-gray-900 dark:text-white">{{ $hopDong->so_hop_dong ?? '---' }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 uppercase block">Loại HĐ</span>
                                <span
                                    class="text-xs px-2 py-0.5 rounded bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">{{ ($hopDong->loai_hop_dong ?? '') == 'khong_xac_dinh_thoi_han' ? 'Không xác định' : 'Xác định' }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 uppercase block">Ngày bắt đầu</span>
                                <span
                                    class="font-medium">{{ isset($hopDong->ngay_bat_dau) ? date('d/m/Y', strtotime($hopDong->ngay_bat_dau)) : '---' }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 uppercase block">Ngày kết thúc</span>
                                <span
                                    class="font-medium">{{ isset($hopDong->ngay_ket_thuc) ? date('d/m/Y', strtotime($hopDong->ngay_ket_thuc)) : '♾️ Vô thời hạn' }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 uppercase block">Trạng thái ký</span>
                                <span
                                    class="text-xs px-2 py-0.5 rounded {{ ($hopDong->trang_thai_ky ?? '') == 'da_ky' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                    {{ ($hopDong->trang_thai_ky ?? '') == 'da_ky' ? '✅ Đã ký' : '⏳ Chờ ký' }}
                                </span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 uppercase block">Địa điểm</span>
                                <span class="font-medium">{{ $hopDong->dia_diem_lam_viec ?? '---' }}</span>
                            </div>
                            <div>
                                <span
                                    class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Người
                                    ký</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    @if (!empty($hopDong->nguoi_ky_id))
                                        {{ $hopDong->nguoi_ky_ho_ten ?? ($hopDong->nguoi_ky_username ?? 'Đã ký') }}
                                    @elseif(($hopDong->trang_thai_ky ?? '') == 'da_ky')
                                        {{ $hopDong->nhan_vien_ho_ten ?? 'Nhân viên' }}
                                    @else
                                        <span class="text-gray-400">---</span>
                                    @endif
                                </span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 uppercase block">Ngày ký</span>
                                <span
                                    class="font-medium">{{ $hopDong->thoi_gian_ky ? date('d/m/Y', strtotime($hopDong->thoi_gian_ky)) : '---' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- CARD: LƯƠNG & PHỤ CẤP --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div
                            class="px-4 py-3 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-b border-gray-200 dark:border-gray-700 flex items-center">
                            <i class="fas fa-money-bill-wave text-green-600 dark:text-green-400 mr-2"></i>
                            <span class="font-bold text-gray-800 dark:text-gray-200 text-sm">💰 Lương & Phụ cấp</span>
                        </div>
                        <div class="p-4">
                            <div class="flex items-end gap-2 mb-3">
                                <span class="text-xs text-gray-400 uppercase">Lương cơ bản</span>
                                <span class="text-2xl font-bold text-green-600 dark:text-green-400">
                                    {{ number_format($hopDong->luong_co_ban ?? 0, 0, ',', '.') }}
                                </span>
                                <span class="text-xs text-gray-500 mb-1">VNĐ/tháng</span>
                            </div>

                            @if (isset($dsPhuCap) && count($dsPhuCap) > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($dsPhuCap as $phuCap)
                                        <div
                                            class="flex items-center gap-2 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                            <i class="fas fa-gift text-blue-500 text-xs"></i>
                                            <span
                                                class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $phuCap->ten }}</span>
                                            <span
                                                class="text-xs font-bold text-blue-600 dark:text-blue-400">{{ number_format($phuCap->so_tien_mac_dinh ?? 0, 0, ',', '.') }}đ</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-400 dark:text-gray-500"><i class="fas fa-info-circle mr-1"></i>
                                    Không có phụ cấp</p>
                            @endif
                        </div>
                    </div>

                    {{-- CARD: FILE HỢP ĐỒNG --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div
                            class="px-4 py-3 bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20 border-b border-gray-200 dark:border-gray-700 flex items-center">
                            <i class="fas fa-paperclip text-red-500 mr-2"></i>
                            <span class="font-bold text-gray-800 dark:text-gray-200 text-sm">📎 File hợp đồng</span>
                        </div>
                        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                            {{-- File gốc --}}
                            <div
                                class="p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center gap-2 mb-1">
                                    <i class="fas fa-file text-blue-500 text-sm"></i>
                                    <span class="font-semibold text-gray-700 dark:text-gray-300 text-xs">Hợp đồng gốc</span>
                                </div>
                                @if (!empty($hopDong->duong_dan_file))
                                    @php
                                        $danhSachFile = explode(';', $hopDong->duong_dan_file);
                                        $fileDauTien = trim($danhSachFile[0]);
                                    @endphp
                                    <div class="flex flex-wrap gap-1">
                                        <a href="{{ asset('storage/' . $fileDauTien) }}" target="_blank"
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-lg transition-colors">
                                            <i class="fas fa-eye mr-1"></i> Xem
                                        </a>
                                        <a href="{{ asset('storage/' . $fileDauTien) }}" download
                                            class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs rounded-lg transition-colors">
                                            <i class="fas fa-download mr-1"></i> Tải
                                        </a>
                                    </div>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 truncate">
                                        {{ basename($fileDauTien) }}</p>
                                @else
                                    <p class="text-xs text-gray-400">Chưa có file</p>
                                @endif
                            </div>

                            {{-- File đã ký --}}
                            <div
                                class="p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center gap-2 mb-1">
                                    <i class="fas fa-file-signature text-green-500 text-sm"></i>
                                    <span class="font-semibold text-gray-700 dark:text-gray-300 text-xs">Hợp đồng đã
                                        ký</span>
                                    @if (!empty($hopDong->file_hop_dong_da_ky))
                                        <span
                                            class="ml-auto text-xs px-2 py-0.5 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 rounded-full">✅</span>
                                    @endif
                                </div>
                                @if (!empty($hopDong->file_hop_dong_da_ky))
                                    <div class="flex flex-wrap gap-1">
                                        <a href="{{ asset('storage/' . $hopDong->file_hop_dong_da_ky) }}" target="_blank"
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-lg transition-colors">
                                            <i class="fas fa-eye mr-1"></i> Xem
                                        </a>
                                        <a href="{{ asset('storage/' . $hopDong->file_hop_dong_da_ky) }}" download
                                            class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs rounded-lg transition-colors">
                                            <i class="fas fa-download mr-1"></i> Tải
                                        </a>
                                    </div>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 truncate">
                                        {{ basename($hopDong->file_hop_dong_da_ky) }}</p>
                                @else
                                    <p class="text-xs text-gray-400">Chưa có file</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- GHI CHÚ --}}
                    @if (!empty($hopDong->ghi_chu))
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div
                                class="px-4 py-3 bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-amber-900/20 dark:to-yellow-900/20 border-b border-gray-200 dark:border-gray-700 flex items-center">
                                <i class="fas fa-sticky-note text-amber-500 mr-2"></i>
                                <span class="font-bold text-gray-800 dark:text-gray-200 text-sm">📝 Ghi chú</span>
                            </div>
                            <div class="p-4">
                                <p
                                    class="text-sm text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-800/50 p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                                    {{ $hopDong->ghi_chu }}
                                </p>
                            </div>
                        </div>
                    @endif

                </div>

                {{-- RIGHT COLUMN --}}
                <div class="lg:col-span-1 space-y-4">

                    {{-- CARD: THÔNG TIN NHÂN VIÊN --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div
                            class="px-4 py-3 bg-gradient-to-r from-teal-50 to-cyan-50 dark:from-teal-900/20 dark:to-cyan-900/20 border-b border-gray-200 dark:border-gray-700 flex items-center">
                            <i class="fas fa-user-circle text-teal-600 dark:text-teal-400 mr-2"></i>
                            <span class="font-bold text-gray-800 dark:text-gray-200 text-sm">👤 Thông tin nhân viên</span>
                        </div>
                        <div class="p-4 text-center">
                            <div
                                class="w-16 h-16 mx-auto bg-gradient-to-tr from-blue-500 to-teal-400 rounded-full flex items-center justify-center text-white text-xl font-bold shadow-lg ring-2 ring-white dark:ring-gray-800">
                                @if (!empty($hopDong->nhan_vien_ho_ten))
                                    {{ mb_substr(strrchr($hopDong->nhan_vien_ho_ten, ' '), 1) ?: mb_substr($hopDong->nhan_vien_ho_ten, 0, 2) }}
                                @else
                                    NV
                                @endif
                            </div>
                            <h5 class="text-base font-bold text-gray-900 dark:text-white mt-2">
                                {{ $hopDong->nhan_vien_ho_ten ?? $hopDong->ten_dang_nhap }}</h5>
                            <span
                                class="inline-block text-xs font-semibold px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 rounded-full mt-1">
                                Mã NV: {{ $hopDong->nhan_vien_ma_nv ?? '---' }}
                            </span>
                            <div class="mt-3 space-y-2 text-sm">
                                <div
                                    class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-1">
                                    <span class="text-gray-400 dark:text-gray-500">Chức vụ</span>
                                    <span
                                        class="font-semibold text-gray-800 dark:text-gray-200">{{ $hopDong->ten_chuc_vu ?? '---' }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400 dark:text-gray-500">Phòng ban</span>
                                    <span
                                        class="font-semibold text-gray-800 dark:text-gray-200">{{ $hopDong->ten_phong_ban ?? '---' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CARD: GỬI FILE SCAN --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden sticky top-4">
                        <div
                            class="px-4 py-3 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 border-b border-gray-200 dark:border-gray-700 flex items-center">
                            <i class="fas fa-cloud-upload-alt text-indigo-600 dark:text-indigo-400 mr-2"></i>
                            <span class="font-bold text-gray-800 dark:text-gray-200 text-sm">📤 Gửi file ký tay</span>
                        </div>
                        <div class="p-4">
                            @php
                                $canSign =
                                    ($hopDong->trang_thai_ky ?? '') == 'cho_ky' &&
                                    in_array($hopDong->trang_thai_hop_dong ?? '', [
                                        'chua_hieu_luc',
                                        'hieu_luc',
                                        'het_han',
                                    ]) &&
                                    ($hopDong->trang_thai_hop_dong ?? '') != 'huy_bo';
                            @endphp

                            @if (($hopDong->trang_thai_ky ?? '') == 'da_ky')
                                <div
                                    class="p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg border border-emerald-200 dark:border-emerald-800 text-center">
                                    <i
                                        class="fas fa-check-circle text-2xl text-emerald-500 dark:text-emerald-400 block mb-1"></i>
                                    <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-300">✅ Đã gửi file
                                        đã ký</p>
                                    <p class="text-xs text-emerald-600 dark:text-emerald-400">Hợp đồng đã có hiệu lực</p>
                                    @if (!empty($hopDong->file_hop_dong_da_ky))
                                        <a href="{{ asset('storage/' . $hopDong->file_hop_dong_da_ky) }}" target="_blank"
                                            class="inline-flex items-center mt-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-lg transition-colors">
                                            <i class="fas fa-eye mr-1"></i> Xem file đã ký
                                        </a>
                                    @endif
                                </div>
                            @elseif (($hopDong->trang_thai_hop_dong ?? '') == 'huy_bo' || ($hopDong->trang_thai_ky ?? '') == 'tu_choi_ky')
                                <div
                                    class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800 text-center">
                                    <i class="fas fa-times-circle text-2xl text-red-500 dark:text-red-400 block mb-1"></i>
                                    <p class="text-sm font-semibold text-red-700 dark:text-red-300">❌ Hợp đồng đã bị hủy
                                        hoặc từ chối</p>
                                    <p class="text-xs text-red-600 dark:text-red-400">Vui lòng liên hệ HR để được hỗ
                                        trợ</p>
                                </div>
                            @elseif ($canSign)
                                <div class="space-y-3">
                                    <div
                                        class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-xs text-blue-700 dark:text-blue-300">
                                        <p class="font-semibold">📌 Hướng dẫn:</p>
                                        <ol class="list-decimal list-inside text-blue-600 dark:text-blue-400">
                                            <li>Tải file hợp đồng gốc về</li>
                                            <li>In và ký tay</li>
                                            <li>Scan và tải lên file đã ký</li>
                                        </ol>
                                    </div>

                                    <form action="{{ route('employee.hop-dong.update-status', $hopDong->id) }}"
                                        method="POST" enctype="multipart/form-data" id="kyForm">
                                        @csrf
                                        @method('PATCH')

                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                📎 Chọn file đã ký:
                                                <span class="text-red-500">*</span>
                                            </label>
                                            <input type="file" name="file_hop_dong_da_ky" required
                                                accept="image/*,.pdf,.doc,.docx"
                                                class="block w-full text-xs text-gray-500 dark:text-gray-400
                                                file:mr-2 file:py-1.5 file:px-3
                                                file:rounded-lg file:border-0
                                                file:text-xs file:font-semibold
                                                file:bg-indigo-50 file:text-indigo-700
                                                hover:file:bg-indigo-100
                                                dark:file:bg-gray-700 dark:file:text-gray-300
                                                border border-gray-300 dark:border-gray-600 rounded-lg p-1 bg-gray-50 dark:bg-gray-900">
                                            <p class="text-xs text-gray-400 mt-1">Chấp nhận: JPG, PNG, PDF, DOC, DOCX (tối
                                                đa 5MB)</p>
                                        </div>

                                        <div class="mt-3 flex flex-col gap-2">
                                            <button type="submit" name="action" value="gui_file_scan"
                                                class="w-full inline-flex items-center justify-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold text-sm transition-colors">
                                                <i class="fas fa-paper-plane mr-1"></i> Gửi file đã ký
                                            </button>
                                            <button type="button" onclick="showTuChoiForm()"
                                                class="w-full inline-flex items-center justify-center px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg font-semibold text-sm transition-colors">
                                                <i class="fas fa-times mr-1"></i> Từ chối ký
                                            </button>
                                        </div>
                                    </form>

                                    {{-- Form từ chối ký (ẩn) --}}
                                    <div id="tuChoiForm" style="display:none;"
                                        class="mt-3 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                                        <form action="{{ route('employee.hop-dong.tu-choi-ky', $hopDong->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="mb-2">
                                                <label
                                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Lý
                                                    do từ chối:</label>
                                                <textarea name="ly_do_tu_choi" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm"
                                                    rows="3" placeholder="Nhập lý do từ chối ký..." required></textarea>
                                            </div>
                                            <div class="flex gap-2">
                                                <button type="submit"
                                                    class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition-colors">Xác
                                                    nhận từ chối</button>
                                                <button type="button" onclick="hideTuChoiForm()"
                                                    class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-lg transition-colors">Hủy</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @elseif (($hopDong->trang_thai_hop_dong ?? '') == 'tao_moi')
                                <div
                                    class="p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-200 dark:border-gray-600 text-center">
                                    <i class="fas fa-clock text-2xl text-gray-400 block mb-1"></i>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">⏳ Hợp đồng đang chờ
                                        HR gửi</p>
                                    <p class="text-xs text-gray-400">Vui lòng đợi HR gửi hợp đồng để ký</p>
                                </div>
                            @else
                                <div
                                    class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800 text-center">
                                    <i class="fas fa-info-circle text-2xl text-yellow-500 block mb-1"></i>
                                    <p class="text-sm font-medium text-yellow-700 dark:text-yellow-300">⚠️ Hợp đồng không
                                        thể ký</p>
                                    <p class="text-xs text-yellow-600 dark:text-yellow-400">Trạng thái:
                                        {{ $hopDong->trang_thai_hop_dong ?? 'Không xác định' }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Script --}}
    <script>
        function showTuChoiForm() {
            document.getElementById('tuChoiForm').style.display = 'block';
            document.getElementById('tuChoiForm').scrollIntoView({
                behavior: 'smooth'
            });
        }

        function hideTuChoiForm() {
            document.getElementById('tuChoiForm').style.display = 'none';
        }

        // Validation form
        document.getElementById('kyForm')?.addEventListener('submit', function(e) {
            const fileInput = this.querySelector('input[type="file"]');
            const file = fileInput?.files[0];

            if (!file) {
                e.preventDefault();
                alert('⚠️ Vui lòng chọn file hợp đồng đã ký!');
                return false;
            }

            // Check file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                e.preventDefault();
                alert('⚠️ File quá lớn! Vui lòng chọn file dưới 5MB.');
                return false;
            }

            // Check file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf', 'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];
            if (!allowedTypes.includes(file.type)) {
                e.preventDefault();
                alert('⚠️ Định dạng file không hợp lệ! Chấp nhận: JPG, PNG, PDF, DOC, DOCX.');
                return false;
            }

            if (!confirm('✅ Bạn có chắc chắn muốn gửi file hợp đồng đã ký?')) {
                e.preventDefault();
                return false;
            }
        });
    </script>

    <style>
        #tuChoiForm {
            transition: all 0.3s ease;
        }
    </style>
@endsection
