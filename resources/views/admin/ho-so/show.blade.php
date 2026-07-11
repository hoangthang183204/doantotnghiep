@extends('layouts.admin')

@section('title', 'Chi tiết hồ sơ - ' . $hoSo->ho . ' ' . $hoSo->ten)

@section('content')

    <div class="space-y-6">

        {{-- ============================================================ --}}
        {{-- HEADER --}}
        {{-- ============================================================ --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

            <div class="flex justify-between items-start">

                <div class="flex items-center gap-4">

                    @if ($hoSo->anh_dai_dien)
                        <img src="{{ asset('storage/' . $hoSo->anh_dai_dien) }}" alt="Avatar"
                            class="w-16 h-16 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">
                    @else
                        <div
                            class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-3xl text-white font-bold">
                            {{ substr($hoSo->ten ?? 'N', 0, 1) }}{{ substr($hoSo->ho ?? 'N', 0, 1) }}
                        </div>
                    @endif

                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                            {{ $hoSo->ho }} {{ $hoSo->ten }}
                        </h1>
                        <div class="flex items-center gap-3 mt-1 flex-wrap">
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                📋 {{ $hoSo->ma_nhan_vien ?? 'Chưa có mã' }}
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                📧 {{ $hoSo->nguoi_dung->email ?? '---' }}
                            </span>
                            @if (($hoSo->trang_thai ?? 1) == 1)
                                <span class="text-xs px-3 py-1 bg-green-100 text-green-700 rounded-full font-medium">
                                    ✅ Đang làm việc
                                </span>
                            @else
                                <span class="text-xs px-3 py-1 bg-red-100 text-red-700 rounded-full font-medium">
                                    ⛔ Đã nghỉ việc
                                </span>
                            @endif
                            <span class="text-xs px-3 py-1 bg-blue-100 text-blue-700 rounded-full font-medium">
                                🎯 {{ $hoSo->tham_nien }}
                            </span>
                        </div>
                    </div>

                </div>

                <div class="flex gap-2 flex-wrap">
                    <a href="{{ route('admin.ho-so.edit', $hoSo->id) }}"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition">
                        ✏️ Sửa hồ sơ
                    </a>
                    <a href="{{ route('admin.ho-so.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                        ← Quay lại
                    </a>
                </div>

            </div>

        </div>

        {{-- ============================================================ --}}
        {{-- TAB NAVIGATION (6 TABS) --}}
        {{-- ============================================================ --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-2">
            <nav class="flex flex-wrap gap-1" id="tabNav">
                <button class="tab-btn active px-5 py-2.5 rounded-lg text-sm font-medium transition" data-tab="tab1">
                    📋 Thông tin
                </button>
                <button class="tab-btn px-5 py-2.5 rounded-lg text-sm font-medium transition" data-tab="tab2">
                    💼 Công việc & HĐ
                </button>
                <button class="tab-btn px-5 py-2.5 rounded-lg text-sm font-medium transition" data-tab="tab3">
                    📄 Năng lực & CV
                </button>
                <button class="tab-btn px-5 py-2.5 rounded-lg text-sm font-medium transition" data-tab="tab4">
                    💰 Lương thưởng
                </button>
                <button class="tab-btn px-5 py-2.5 rounded-lg text-sm font-medium transition" data-tab="tab5">
                    🛡️ Bảo hiểm & Thuế
                </button>
                <button class="tab-btn px-5 py-2.5 rounded-lg text-sm font-medium transition" data-tab="tab6">
                    🏆 Đào tạo & Kỷ luật
                </button>
            </nav>
        </div>

        {{-- ============================================================ --}}
        {{-- TAB CONTENT --}}
        {{-- ============================================================ --}}
        <div class="space-y-6">

            {{-- ========================================================== --}}
            {{-- TAB 1: THÔNG TIN CƠ BẢN (DỮ LIỆU THẬT) --}}
            {{-- ========================================================== --}}
            <div id="tab1" class="tab-content">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- Cột trái: Thông tin cá nhân --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

                        <h3
                            class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                            🧑‍💼 Thông tin cá nhân
                        </h3>

                        <div class="space-y-3">
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400">Họ và tên</span>
                                <span class="font-medium">{{ $hoSo->ho }} {{ $hoSo->ten }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400">Mã nhân viên</span>
                                <span class="font-mono font-medium">{{ $hoSo->ma_nhan_vien ?? '---' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400">Email công ty</span>
                                <span
                                    class="text-blue-600 dark:text-blue-400">{{ $hoSo->nguoi_dung->email ?? '---' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400">Số điện thoại</span>
                                <span class="font-medium">{{ $hoSo->so_dien_thoai ?? '---' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400">Ngày sinh</span>
                                <span
                                    class="font-medium">{{ $hoSo->ngay_sinh ? $hoSo->ngay_sinh->format('d/m/Y') : '---' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400">Tuổi</span>
                                <span class="font-medium">{{ $hoSo->tuoi ?? '---' }} tuổi</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400">Giới tính</span>
                                <span class="font-medium">{{ $hoSo->gioi_tinh_text }}</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-gray-500 dark:text-gray-400">Tình trạng hôn nhân</span>
                                <span class="font-medium">{{ $hoSo->tinh_trang_hon_nhan_text }}</span>
                            </div>
                        </div>

                    </div>

                    {{-- Cột phải: Địa chỉ & Giấy tờ --}}
                    {{-- Cột phải: Địa chỉ & Giấy tờ --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

                        <h3
                            class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                            🏠 Địa chỉ & Giấy tờ
                        </h3>

                        <div class="space-y-3">
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400">Địa chỉ hiện tại</span>
                                <span class="font-medium text-right">{{ $hoSo->dia_chi_hien_tai ?? '---' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400">Địa chỉ thường trú</span>
                                <span class="font-medium text-right">{{ $hoSo->dia_chi_thuong_tru ?? '---' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400">CMND/CCCD</span>
                                <span class="font-mono font-medium">{{ $hoSo->cmnd_cccd ?? '---' }}</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-gray-500 dark:text-gray-400">Số hộ chiếu</span>
                                <span class="font-medium">{{ $hoSo->so_ho_chieu ?? '---' }}</span>
                            </div>
                        </div>

                        {{-- ⭐ THÊM PHẦN HIỂN THỊ ẢNH CCCD --}}
                        @if ($hoSo->anh_cccd_truoc || $hoSo->anh_cccd_sau)
                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">🪪 Ảnh CCCD</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    {{-- Mặt trước --}}
                                    <div>
                                        @if ($hoSo->anh_cccd_truoc)
                                            <div class="relative group">
                                                <img src="{{ asset('storage/' . $hoSo->anh_cccd_truoc) }}"
                                                    alt="CCCD mặt trước"
                                                    class="w-full rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm cursor-pointer hover:opacity-90 transition"
                                                    onclick="openFilePreview('{{ asset('storage/' . $hoSo->anh_cccd_truoc) }}', 'CCCD mặt trước - {{ $hoSo->ho }} {{ $hoSo->ten }}')">
                                                <div
                                                    class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition bg-black/30 rounded-lg">
                                                    <span
                                                        class="text-white text-sm font-medium bg-black/50 px-3 py-1 rounded">🔍
                                                        Xem</span>
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-center">Mặt trước
                                            </p>
                                        @else
                                            <div
                                                class="w-full h-32 bg-gray-100 dark:bg-gray-700 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center">
                                                <span class="text-gray-400 text-sm">Chưa có ảnh</span>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-center">Mặt trước
                                            </p>
                                        @endif
                                    </div>

                                    {{-- Mặt sau --}}
                                    <div>
                                        @if ($hoSo->anh_cccd_sau)
                                            <div class="relative group">
                                                <img src="{{ asset('storage/' . $hoSo->anh_cccd_sau) }}" alt="CCCD mặt sau"
                                                    class="w-full rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm cursor-pointer hover:opacity-90 transition"
                                                    onclick="openFilePreview('{{ asset('storage/' . $hoSo->anh_cccd_sau) }}', 'CCCD mặt sau - {{ $hoSo->ho }} {{ $hoSo->ten }}')">
                                                <div
                                                    class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition bg-black/30 rounded-lg">
                                                    <span
                                                        class="text-white text-sm font-medium bg-black/50 px-3 py-1 rounded">🔍
                                                        Xem</span>
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-center">Mặt sau
                                            </p>
                                        @else
                                            <div
                                                class="w-full h-32 bg-gray-100 dark:bg-gray-700 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center">
                                                <span class="text-gray-400 text-sm">Chưa có ảnh</span>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-center">Mặt sau
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400 mt-2">💡 Click vào ảnh để xem phóng to</p>
                            </div>
                        @else
                            {{-- Hiển thị khi chưa có ảnh --}}
                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="grid grid-cols-2 gap-4">
                                    <div
                                        class="w-full h-32 bg-gray-100 dark:bg-gray-700 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center">
                                        <span class="text-gray-400 text-sm">Mặt trước<br><span class="text-xs">Chưa có
                                                ảnh</span></span>
                                    </div>
                                    <div
                                        class="w-full h-32 bg-gray-100 dark:bg-gray-700 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center">
                                        <span class="text-gray-400 text-sm">Mặt sau<br><span class="text-xs">Chưa có
                                                ảnh</span></span>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400 mt-2">💡 Cập nhật ảnh CCCD trong phần <a
                                        href="{{ route('admin.ho-so.edit', $hoSo->id) }}"
                                        class="text-blue-600 hover:underline">Chỉnh sửa hồ sơ</a></p>
                            </div>
                        @endif

                    </div>

                </div>

                {{-- LIÊN HỆ KHẨN CẤP (DỮ LIỆU THẬT) --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mt-6">

                    <h3
                        class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                        📞 Liên hệ khẩn cấp
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                            <span class="text-gray-500 dark:text-gray-400 text-sm block">Họ tên</span>
                            <p class="font-medium text-lg">{{ $hoSo->lien_he_khan_cap ?? '---' }}</p>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                            <span class="text-gray-500 dark:text-gray-400 text-sm block">Số điện thoại</span>
                            <p class="font-medium text-lg">{{ $hoSo->sdt_khan_cap ?? '---' }}</p>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                            <span class="text-gray-500 dark:text-gray-400 text-sm block">Mối quan hệ</span>
                            <p class="font-medium text-lg">{{ $hoSo->quan_he_khan_cap ?? '---' }}</p>
                        </div>

                    </div>

                </div>

            </div>

            {{-- ========================================================== --}}
            {{-- TAB 2: CÔNG VIỆC & HỢP ĐỒNG (DỮ LIỆU THẬT) --}}
            {{-- ========================================================== --}}
            <div id="tab2" class="tab-content hidden">

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

                    <h3
                        class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                        💼 Thông tin công việc
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="space-y-3">
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400">Chức vụ</span>
                                <span class="font-medium">{{ $hoSo->nguoi_dung->chuc_vu->ten ?? '---' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400">Phòng ban</span>
                                <span
                                    class="font-medium">{{ $hoSo->nguoi_dung->phong_ban->ten_phong_ban ?? '---' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400">Ngày vào làm</span>
                                <span class="font-medium">
                                    {{ $hoSo->nguoi_dung->created_at ? $hoSo->nguoi_dung->created_at->format('d/m/Y') : '---' }}
                                </span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-gray-500 dark:text-gray-400">Thâm niên</span>
                                <span class="font-medium text-green-600">{{ $hoSo->tham_nien }}</span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400">Loại hợp đồng</span>
                                {{-- ⭐ SỬA: Dùng accessor có dấu --}}
                                <span class="font-medium">{{ $hopDongHieuLuc->ten_loai_hop_dong ?? '---' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400">Ngày ký HĐ</span>
                                <span class="font-medium">
                                    {{ isset($hopDongHieuLuc) && $hopDongHieuLuc->ngay_bat_dau
                                        ? \Carbon\Carbon::parse($hopDongHieuLuc->ngay_bat_dau)->format('d/m/Y')
                                        : '---' }}
                                </span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-gray-500 dark:text-gray-400">Ngày hết hạn HĐ</span>
                                <span class="font-medium">
                                    {{ isset($hopDongHieuLuc) && $hopDongHieuLuc->ngay_ket_thuc
                                        ? \Carbon\Carbon::parse($hopDongHieuLuc->ngay_ket_thuc)->format('d/m/Y')
                                        : 'Không áp dụng' }}
                                </span>
                            </div>
                        </div>

                    </div>

                    {{-- LỊCH SỬ HỢP ĐỒNG --}}
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">

                        <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">📄 Lịch sử hợp đồng lao động</h4>

                        @if ($hoSo->hop_dong && $hoSo->hop_dong->count() > 0)
                            <div class="space-y-3">
                                @foreach ($hoSo->hop_dong as $item)
                                    <div
                                        class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border-l-4 
                    {{ $item->trang_thai_hop_dong == 'hieu_luc'
                        ? 'border-green-500'
                        : ($item->trang_thai_hop_dong == 'het_han'
                            ? 'border-gray-400'
                            : ($item->trang_thai_hop_dong == 'chua_hieu_luc'
                                ? 'border-yellow-500'
                                : 'border-red-500')) }}">

                                        <div class="flex justify-between items-start">
                                            <div>
                                                <span
                                                    class="font-medium">{{ $item->ten_loai_hop_dong ?? $item->loai_hop_dong }}</span>
                                                <span
                                                    class="text-sm text-gray-500 dark:text-gray-400 ml-2">({{ $item->so_hop_dong }})</span>
                                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $item->ngay_bat_dau ? $item->ngay_bat_dau->format('d/m/Y') : '---' }}
                                                    →
                                                    {{ $item->ngay_ket_thuc ? $item->ngay_ket_thuc->format('d/m/Y') : 'Không xác định' }}
                                                </div>

                                                {{-- ⭐ KIỂM TRA FILE CÓ TỒN TẠI KHÔNG --}}
                                                @php
                                                    $filePath = $item->file_hop_dong_da_ky
                                                        ? storage_path('app/public/' . $item->file_hop_dong_da_ky)
                                                        : null;
                                                    $fileExists = $filePath && file_exists($filePath);
                                                @endphp

                                                {{-- ⭐ NÚT XEM FILE HỢP ĐỒNG --}}
                                                @if ($item->file_hop_dong_da_ky && $fileExists)
                                                    <div class="mt-3 flex flex-wrap gap-2">
                                                        <div class="flex flex-wrap gap-2">
                                                            {{-- Nút Xem hợp đồng --}}
                                                            <button
                                                                onclick="openFilePreview('{{ route('admin.ho-so.view-contract', $item->id) }}', 'Hợp đồng {{ $item->so_hop_dong }}')"
                                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition flex items-center gap-1"
                                                                title="Xem hợp đồng">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                </svg>
                                                            </button>

                                                            {{-- Nút Tải xuống --}}
                                                            <a href="{{ asset('storage/' . $item->file_hop_dong_da_ky) }}"
                                                                download
                                                                class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition flex items-center gap-1"
                                                                title="Tải xuống">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @elseif ($item->file_hop_dong_da_ky && !$fileExists)
                                                    <div class="mt-3">
                                                        <span class="text-sm text-red-500 flex items-center gap-2">
                                                            ⚠️ File hợp đồng không tồn tại trên server
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="mt-3">
                                                        <span class="text-sm text-gray-400 flex items-center gap-2">
                                                            📎 Chưa có file hợp đồng
                                                        </span>
                                                    </div>
                                                @endif

                                                {{-- ⭐ HIỂN THỊ THÔNG TIN KÝ --}}
                                                @if ($item->nguoi_ky_id)
                                                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                        ✍️ Ký bởi: {{ $item->nguoiKy->ho ?? '' }}
                                                        {{ $item->nguoiKy->ten ?? '' }}
                                                        @if ($item->thoi_gian_ky)
                                                            •
                                                            {{ \Carbon\Carbon::parse($item->thoi_gian_ky)->format('d/m/Y H:i') }}
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>

                                            <span
                                                class="text-xs px-2 py-1 {{ $item->mau_trang_thai }} rounded-full whitespace-nowrap ml-2">
                                                {{ $item->ten_trang_thai }}
                                            </span>
                                        </div>

                                        {{-- GHI CHÚ --}}
                                        @if ($item->ghi_chu)
                                            <div
                                                class="mt-2 p-2 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                                                <p class="text-sm text-yellow-700 dark:text-yellow-300">📌
                                                    {{ $item->ghi_chu }}</p>
                                            </div>
                                        @endif

                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có hợp đồng lao động</p>
                        @endif

                    </div>

                </div>

            </div>

            {{-- ========================================================== --}}
            {{-- TAB 3: NĂNG LỰC & CV --}}
            {{-- ========================================================== --}}
            <div id="tab3" class="tab-content hidden">

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

                    <h3
                        class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                        📄 Hồ sơ năng lực & CV
                    </h3>

                    {{-- FILE CV --}}
                    <div
                        class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-lg p-4 mb-6 border border-blue-200 dark:border-blue-800">

                        <div class="flex justify-between items-center">
                            <div>
                                <span class="font-medium">📎 CV đính kèm</span>
                                @if ($hoSo->cv)
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $hoSo->cv->ten_file_goc }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $hoSo->cv->kich_thuoc }} •
                                        {{ $hoSo->cv->loai_mime }}</p>
                                @else
                                    <p class="text-sm text-gray-400">Chưa có CV</p>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                @if ($hoSo->cv)
                                    <div class="flex gap-1.5">
                                        {{-- Nút Xem trước CV --}}
                                        <button
                                            onclick="openFilePreview('{{ route('admin.ho-so.view-cv', $hoSo->cv->id) }}', 'CV - {{ $hoSo->ho }} {{ $hoSo->ten }}')"
                                            class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                            title="Xem trước CV">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>

                                        {{-- Nút Tải xuống CV --}}
                                        <a href="{{ asset('storage/' . $hoSo->cv->duong_dan_file) }}" download
                                            class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg transition"
                                            title="Tải xuống CV">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">Chưa có CV</span>
                                @endif
                            </div>
                        </div>

                    </div>

                    {{-- ========================================== --}}
                    {{-- 🛠️ KỸ NĂNG CHUYÊN MÔN (DỮ LIỆU THẬT) --}}
                    {{-- ========================================== --}}
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">🛠️ Kỹ năng chuyên môn</h4>

                        @if ($hoSo->ky_nang && $hoSo->ky_nang->count() > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach ($hoSo->ky_nang as $item)
                                    <span
                                        class="px-3 py-1.5 {{ $item->mau_cap_do }} rounded-full text-sm font-medium shadow-sm">
                                        {{ $item->ten_ky_nang }}
                                        <span class="text-xs opacity-70">({{ $item->cap_do }})</span>
                                    </span>
                                @endforeach
                            </div>
                            <div class="mt-2 text-xs text-gray-400">
                                📌 Tổng: {{ $hoSo->ky_nang->count() }} kỹ năng
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có kỹ năng</p>
                        @endif
                    </div>

                    {{-- ========================================== --}}
                    {{-- 🏅 CHỨNG CHỈ (DỮ LIỆU THẬT) --}}
                    {{-- ========================================== --}}
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">🏅 Chứng chỉ</h4>

                        @if ($hoSo->chung_chi && $hoSo->chung_chi->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach ($hoSo->chung_chi as $item)
                                    <div
                                        class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 border border-gray-200 dark:border-gray-600 hover:shadow-md transition">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-medium text-gray-800 dark:text-white">
                                                    {{ $item->ten_chung_chi }}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">🏛️
                                                    {{ $item->to_chuc_cap }}</p>
                                            </div>
                                            <span class="text-xs px-2 py-1 {{ $item->mau_trang_thai }} rounded-full">
                                                {{ $item->trang_thai_hien_thi }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between mt-2 text-xs text-gray-500 dark:text-gray-400">
                                            <span>📅 {{ $item->nam_cap }}</span>
                                            @if ($item->ngay_het_han)
                                                <span>⏳ Hết hạn: {{ $item->ngay_het_han->format('d/m/Y') }}</span>
                                            @else
                                                <span>♾️ Không hết hạn</span>
                                            @endif
                                        </div>
                                        @if ($item->file_dinh_kem)
                                            <div class="mt-2">
                                                <a href="{{ asset('storage/' . $item->file_dinh_kem) }}" target="_blank"
                                                    class="text-blue-600 hover:text-blue-800 text-xs">📎 Xem file đính
                                                    kèm</a>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có chứng chỉ</p>
                        @endif
                    </div>

                    {{-- ========================================== --}}
                    {{-- 🚀 DỰ ÁN ĐÃ THAM GIA (DỮ LIỆU THẬT) --}}
                    {{-- ========================================== --}}
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">

                        <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">🚀 Dự án đã tham gia</h4>

                        @if ($hoSo->du_an && $hoSo->du_an->count() > 0)
                            <div class="space-y-3">
                                @foreach ($hoSo->du_an as $item)
                                    <div
                                        class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border-l-4 {{ $item->mau_border }} hover:shadow-md transition">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <span
                                                    class="font-medium text-gray-800 dark:text-white">{{ $item->ten_du_an }}</span>
                                                <span
                                                    class="text-sm text-gray-500 dark:text-gray-400 ml-2">({{ $item->vai_tro }})</span>
                                            </div>
                                            <span class="text-xs px-2 py-1 {{ $item->mau_trang_thai }} rounded-full">
                                                {{ $item->icon_trang_thai }} {{ $item->trang_thai }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            📅 {{ $item->ngay_bat_dau->format('d/m/Y') }} →
                                            {{ $item->ngay_ket_thuc ? $item->ngay_ket_thuc->format('d/m/Y') : 'Đang thực hiện' }}
                                        </div>
                                        @if ($item->mo_ta)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 italic">
                                                "{{ $item->mo_ta }}"</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-2 text-xs text-gray-400">
                                📌 Tổng: {{ $hoSo->du_an->count() }} dự án đã tham gia
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có dự án</p>
                        @endif

                    </div>

                </div>

            </div>

            {{-- ========================================================== --}}
            {{-- TAB 4: LƯƠNG THƯỞNG --}}
            {{-- ========================================================== --}}
            <div id="tab4" class="tab-content hidden">

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

                    <h3
                        class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                        💰 Thông tin lương thưởng
                    </h3>

                    {{-- 🏦 THÔNG TIN NGÂN HÀNG --}}
                    <div
                        class="bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 border-2 border-green-200 dark:border-green-800 rounded-xl p-5 mb-6 shadow-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-2xl">🏦</span>
                            <h4 class="text-lg font-bold text-gray-800 dark:text-white">Thông tin nhận lương</h4>
                            <span
                                class="ml-auto text-xs px-3 py-1 bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200 rounded-full font-medium">Chi
                                trả hàng tháng</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm border border-gray-200 dark:border-gray-700">
                                <span class="text-xs text-gray-500 dark:text-gray-400 uppercase block font-medium">Chủ tài
                                    khoản</span>
                                <p class="font-semibold text-gray-800 dark:text-white text-lg">
                                    {{ $hoSo->chu_tai_khoan ?? 'Chưa cập nhật' }}
                                </p>
                            </div>
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm border border-gray-200 dark:border-gray-700">
                                <span class="text-xs text-gray-500 dark:text-gray-400 uppercase block font-medium">Số tài
                                    khoản</span>
                                <p class="font-mono font-bold text-gray-800 dark:text-white text-lg">
                                    {{ $hoSo->so_tai_khoan ?? 'Chưa cập nhật' }}
                                </p>
                            </div>
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm border border-gray-200 dark:border-gray-700">
                                <span class="text-xs text-gray-500 dark:text-gray-400 uppercase block font-medium">Ngân
                                    hàng</span>
                                <p class="font-semibold text-gray-800 dark:text-white text-lg">
                                    {{ $hoSo->ten_ngan_hang ?? 'Chưa cập nhật' }}
                                </p>
                            </div>
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm border border-gray-200 dark:border-gray-700">
                                <span class="text-xs text-gray-500 dark:text-gray-400 uppercase block font-medium">Chi
                                    nhánh / PGD</span>
                                <p class="font-semibold text-gray-800 dark:text-white text-lg">
                                    {{ $hoSo->chi_nhanh_ngan_hang ?? 'Chưa cập nhật' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- ⭐ LƯƠNG HIỆN TẠI - LẤY 100% TỪ HỢP ĐỒNG HIỆU LỰC --}}
                    @php
                        // ⭐ LẤY LƯƠNG TỪ HỢP ĐỒNG HIỆU LỰC (KHÔNG DÙNG BẢNG LƯƠNG)
                        $luongCoBanHienTai = $hopDongHieuLuc->luong_co_ban ?? 0;

                        // Lấy phụ cấp từ bảng lương (nếu có) hoặc từ hợp đồng
                        $tongPhuCap = $luongGanNhat->tong_phu_cap ?? 0;

                        // Nếu phụ cấp = 0 nhưng có hợp đồng, thử lấy từ phụ cấp nhân viên
                        if ($tongPhuCap == 0 && $hopDongHieuLuc) {
                            $phuCapNhanVien = \App\Models\PhuCapNhanVien::where('nguoi_dung_id', $hoSo->nguoi_dung_id)
                                ->where('trang_thai', 'hieu_luc')
                                ->sum('so_tien');
                            $tongPhuCap = $phuCapNhanVien > 0 ? $phuCapNhanVien : 0;
                        }

                        // Tăng ca (lấy từ bảng lương)
                        $tienTangCa = $luongGanNhat->tien_tang_ca ?? 0;

                        // TỔNG THU NHẬP = Lương hợp đồng + Phụ cấp + Tăng ca
                        $tongThuNhap = $luongCoBanHienTai + $tongPhuCap + $tienTangCa;

                        // ⭐ LƯƠNG ĐÓNG BẢO HIỂM = Lương từ hợp đồng
                        $luongDongBhxh = $hopDongHieuLuc->luong_co_ban ?? 0;

                        // Tính bảo hiểm (10.5%)
                        $bhxh = round($luongDongBhxh * 0.08, 0);
                        $bhyt = round($luongDongBhxh * 0.015, 0);
                        $bhtn = round($luongDongBhxh * 0.01, 0);
                        $tongBaoHiem = $bhxh + $bhyt + $bhtn;

                        // Thu nhập chịu thuế
                        $thuNhapChiuThue = max(0, $tongThuNhap - $tongBaoHiem);
                        $giamTruBanThan = 11000000;
                        $thuNhapTinhThue = max(0, $thuNhapChiuThue - $giamTruBanThan);

                        // Tính thuế theo biểu lũy tiến
                        $thueTncn = 0;
                        $remaining = $thuNhapTinhThue;
                        $bac = [
                            ['tu' => 0, 'den' => 5000000, 'thue_suat' => 0.05],
                            ['tu' => 5000000, 'den' => 10000000, 'thue_suat' => 0.1],
                            ['tu' => 10000000, 'den' => 18000000, 'thue_suat' => 0.15],
                            ['tu' => 18000000, 'den' => 32000000, 'thue_suat' => 0.2],
                            ['tu' => 32000000, 'den' => 52000000, 'thue_suat' => 0.25],
                            ['tu' => 52000000, 'den' => 80000000, 'thue_suat' => 0.3],
                            ['tu' => 80000000, 'den' => PHP_INT_MAX, 'thue_suat' => 0.35],
                        ];
                        foreach ($bac as $b) {
                            if ($remaining <= 0) {
                                break;
                            }
                            $khoang = min($remaining, $b['den'] - $b['tu']);
                            $thueTncn += $khoang * $b['thue_suat'];
                            $remaining -= $khoang;
                        }
                        $thueTncn = round($thueTncn, 0);

                        $thucNhan = $tongThuNhap - $tongBaoHiem - $thueTncn;
                    @endphp

                    {{-- 3 THẺ LƯƠNG --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div
                            class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center border border-gray-200 dark:border-gray-600">
                            <p class="text-sm text-gray-500 dark:text-gray-400">📋 Lương cơ bản</p>
                            <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                {{ number_format($luongCoBanHienTai, 0, ',', '.') }} ₫
                            </p>
                            @if ($hopDongHieuLuc)
                                <p class="text-xs text-gray-400">📄 {{ $hopDongHieuLuc->so_hop_dong }}</p>
                            @endif
                        </div>
                        <div
                            class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center border border-gray-200 dark:border-gray-600">
                            <p class="text-sm text-gray-500 dark:text-gray-400">📊 Tổng thu nhập</p>
                            <p class="text-lg font-bold text-green-600 dark:text-green-400">
                                {{ number_format($tongThuNhap, 0, ',', '.') }} ₫
                            </p>
                            <p class="text-xs text-gray-400">
                                = {{ number_format($luongCoBanHienTai, 0, ',', '.') }}
                                + {{ number_format($tongPhuCap, 0, ',', '.') }}
                                + {{ number_format($tienTangCa, 0, ',', '.') }}
                            </p>
                        </div>
                        <div
                            class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center border border-gray-200 dark:border-gray-600">
                            <p class="text-sm text-gray-500 dark:text-gray-400">💰 Thực nhận</p>
                            <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                {{ number_format($thucNhan, 0, ',', '.') }} ₫
                            </p>
                            <p class="text-xs text-gray-400">
                                = {{ number_format($tongThuNhap, 0, ',', '.') }}
                                - {{ number_format($tongBaoHiem, 0, ',', '.') }}
                                - {{ number_format($thueTncn, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    {{-- CHI TIẾT BẢO HIỂM --}}
                    <div
                        class="mb-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">🛡️ Bảo hiểm (10.5%)</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg p-2 text-center border border-gray-200 dark:border-gray-600">
                                <p class="text-xs text-gray-500">BHXH (8%)</p>
                                <p class="font-bold text-blue-600">{{ number_format($bhxh, 0, ',', '.') }} ₫</p>
                            </div>
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg p-2 text-center border border-gray-200 dark:border-gray-600">
                                <p class="text-xs text-gray-500">BHYT (1.5%)</p>
                                <p class="font-bold text-blue-600">{{ number_format($bhyt, 0, ',', '.') }} ₫</p>
                            </div>
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg p-2 text-center border border-gray-200 dark:border-gray-600">
                                <p class="text-xs text-gray-500">BHTN (1%)</p>
                                <p class="font-bold text-blue-600">{{ number_format($bhtn, 0, ',', '.') }} ₫</p>
                            </div>
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg p-2 text-center border-2 border-red-200 dark:border-red-700">
                                <p class="text-xs text-gray-500">Tổng</p>
                                <p class="font-bold text-red-600">{{ number_format($tongBaoHiem, 0, ',', '.') }} ₫</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">📌 Tính trên lương cơ bản:
                            {{ number_format($luongDongBhxh, 0, ',', '.') }} ₫</p>
                    </div>

                    {{-- PHỤ CẤP --}}
                    @if ($tongPhuCap > 0)
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">📌 Phụ cấp</h4>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    // Lấy chi tiết phụ cấp từ bảng phu_cap_nhan_vien
                                    $phuCapChiTiets = \App\Models\PhuCapNhanVien::with('phuCap')
                                        ->where('nguoi_dung_id', $hoSo->nguoi_dung_id)
                                        ->where('trang_thai', 'hieu_luc')
                                        ->get();
                                @endphp
                                @foreach ($phuCapChiTiets as $pc)
                                    <span
                                        class="px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 rounded-full text-sm border border-blue-200 dark:border-blue-800">
                                        {{ $pc->phuCap->ten ?? 'Phụ cấp' }}:
                                        <strong>{{ number_format($pc->so_tien, 0, ',', '.') }} ₫</strong>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- KHẤU TRỪ --}}
                    @if ($tongBaoHiem > 0 || ($luongGanNhat->tong_khau_tru ?? 0) > 0)
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">📌 Khấu trừ</h4>
                            <div class="flex flex-wrap gap-2">
                                <span
                                    class="px-3 py-1.5 bg-red-50 dark:bg-red-900/20 rounded-full text-sm border border-red-200 dark:border-red-800">
                                    BHXH: <strong class="text-red-600">-{{ number_format($bhxh, 0, ',', '.') }}
                                        ₫</strong>
                                </span>
                                <span
                                    class="px-3 py-1.5 bg-red-50 dark:bg-red-900/20 rounded-full text-sm border border-red-200 dark:border-red-800">
                                    BHYT: <strong class="text-red-600">-{{ number_format($bhyt, 0, ',', '.') }}
                                        ₫</strong>
                                </span>
                                <span
                                    class="px-3 py-1.5 bg-red-50 dark:bg-red-900/20 rounded-full text-sm border border-red-200 dark:border-red-800">
                                    BHTN: <strong class="text-red-600">-{{ number_format($bhtn, 0, ',', '.') }}
                                        ₫</strong>
                                </span>
                                @if ($thueTncn > 0)
                                    <span
                                        class="px-3 py-1.5 bg-red-50 dark:bg-red-900/20 rounded-full text-sm border border-red-200 dark:border-red-800">
                                        Thuế TNCN: <strong
                                            class="text-red-600">-{{ number_format($thueTncn, 0, ',', '.') }} ₫</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- THUẾ TNCN --}}
                    <div
                        class="mb-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-700 dark:text-gray-300">🏛️ Thuế TNCN</span>
                            <span class="font-bold {{ $thueTncn > 0 ? 'text-red-600' : 'text-green-600' }} text-lg">
                                {{ number_format($thueTncn, 0, ',', '.') }} ₫
                            </span>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Thu nhập chịu thuế: {{ number_format($thuNhapChiuThue, 0, ',', '.') }} ₫
                            - Giảm trừ: {{ number_format($giamTruBanThan, 0, ',', '.') }} ₫
                            @if ($thuNhapTinhThue > 0)
                                = {{ number_format($thuNhapTinhThue, 0, ',', '.') }} ₫
                            @endif
                        </div>
                        @if ($thuNhapTinhThue == 0)
                            <p class="text-xs text-green-600 dark:text-green-400 mt-1">✅ Không phải nộp thuế</p>
                        @endif
                    </div>

                    {{-- KỲ LƯƠNG (nếu có bảng lương) --}}
                    @if ($luongGanNhat && $luongGanNhat->id)
                        <div
                            class="mt-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                📅 Kỳ lương gần nhất: Tháng
                                {{ $luongGanNhat->luong_thang }}/{{ $luongGanNhat->luong_nam }}
                                • Ngày công:
                                {{ $luongGanNhat->so_ngay_cong ?? 0 }}/{{ $luongGanNhat->so_ngay_cong_chuan ?? 26 }}
                            </p>
                        </div>
                    @endif

                    {{-- LỊCH SỬ LƯƠNG --}}
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">📈 Lịch sử lương</h4>
                        @if ($hoSo->lich_su_luong && $hoSo->lich_su_luong->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr
                                            class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                            <th class="text-left p-2 font-semibold">Kỳ lương</th>
                                            <th class="text-left p-2 font-semibold">Ngày công</th>
                                            <th class="text-left p-2 font-semibold">Lương CB</th>
                                            <th class="text-left p-2 font-semibold">Thực nhận</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($hoSo->lich_su_luong as $item)
                                            <tr
                                                class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                                <td class="p-2 font-medium">Tháng
                                                    {{ $item->luong_thang }}/{{ $item->luong_nam }}</td>
                                                <td class="p-2">{{ $item->so_ngay_cong ?? 0 }}</td>
                                                <td class="p-2">
                                                    {{ number_format($item->luong_co_ban ?? 0, 0, ',', '.') }}</td>
                                                <td class="p-2 font-bold text-green-600">
                                                    {{ number_format($item->luong_thuc_nhan ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có lịch sử lương</p>
                        @endif
                    </div>

                </div>

            </div>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- TAB 5: BẢO HIỂM & THUẾ --}}
    {{-- ========================================================== --}}
    {{-- ========================================================== --}}
    {{-- TAB 5: BẢO HIỂM & THUẾ --}}
    {{-- ========================================================== --}}
    <div id="tab5" class="tab-content hidden">

        @php
            // ⭐ LẤY LƯƠNG TỪ HỢP ĐỒNG HIỆU LỰC (KHÔNG DÙNG BẢNG LƯƠNG)
            $luongCoBanHienTai = $hopDongHieuLuc->luong_co_ban ?? 0;

            // Lấy phụ cấp từ bảng lương hoặc từ phụ cấp nhân viên
            $tongPhuCap = $luongGanNhat->tong_phu_cap ?? 0;
            if ($tongPhuCap == 0 && $hopDongHieuLuc) {
                $phuCapNhanVien = \App\Models\PhuCapNhanVien::where('nguoi_dung_id', $hoSo->nguoi_dung_id)
                    ->where('trang_thai', 'hieu_luc')
                    ->sum('so_tien');
                $tongPhuCap = $phuCapNhanVien > 0 ? $phuCapNhanVien : 0;
            }

            // Tăng ca (lấy từ bảng lương)
            $tienTangCa = $luongGanNhat->tien_tang_ca ?? 0;

            // TỔNG THU NHẬP = Lương hợp đồng + Phụ cấp + Tăng ca
            $tongThuNhap = $luongCoBanHienTai + $tongPhuCap + $tienTangCa;

            // ⭐ LƯƠNG ĐÓNG BẢO HIỂM = Lương từ hợp đồng
            $luongDongBhxh = $hopDongHieuLuc->luong_co_ban ?? 0;

            // Tính bảo hiểm (10.5%)
            $bhxh = round($luongDongBhxh * 0.08, 0);
            $bhyt = round($luongDongBhxh * 0.015, 0);
            $bhtn = round($luongDongBhxh * 0.01, 0);
            $tongBaoHiem = $bhxh + $bhyt + $bhtn;

            // Thu nhập chịu thuế = Tổng thu nhập - Bảo hiểm
            $thuNhapChiuThue = max(0, $tongThuNhap - $tongBaoHiem);

            // Giảm trừ bản thân 11,000,000
            $giamTruBanThan = 11000000;
            $thuNhapTinhThue = max(0, $thuNhapChiuThue - $giamTruBanThan);

            // Tính thuế theo biểu lũy tiến
            $thueTncn = 0;
            $remaining = $thuNhapTinhThue;
            $bac = [
                ['tu' => 0, 'den' => 5000000, 'thue_suat' => 0.05],
                ['tu' => 5000000, 'den' => 10000000, 'thue_suat' => 0.1],
                ['tu' => 10000000, 'den' => 18000000, 'thue_suat' => 0.15],
                ['tu' => 18000000, 'den' => 32000000, 'thue_suat' => 0.2],
                ['tu' => 32000000, 'den' => 52000000, 'thue_suat' => 0.25],
                ['tu' => 52000000, 'den' => 80000000, 'thue_suat' => 0.3],
                ['tu' => 80000000, 'den' => PHP_INT_MAX, 'thue_suat' => 0.35],
            ];
            foreach ($bac as $b) {
                if ($remaining <= 0) {
                    break;
                }
                $khoang = min($remaining, $b['den'] - $b['tu']);
                $thueTncn += $khoang * $b['thue_suat'];
                $remaining -= $khoang;
            }
            $thueTncn = round($thueTncn, 0);

            // Thực nhận
            $thucNhan = $tongThuNhap - $tongBaoHiem - $thueTncn;
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- BẢO HIỂM XÃ HỘI --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <h3
                    class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                    🛡️ Bảo hiểm xã hội
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-500 dark:text-gray-400">Số sổ BHXH</span>
                        <span
                            class="font-mono font-medium text-gray-800 dark:text-white">{{ $hoSo->so_bhxh ?? 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-500 dark:text-gray-400">Mã số thuế TNCN</span>
                        <span
                            class="font-mono font-medium text-gray-800 dark:text-white">{{ $hoSo->ma_so_thue ?? 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-500 dark:text-gray-400">Nơi đăng ký KCB</span>
                        <span
                            class="font-medium text-gray-800 dark:text-white">{{ $hoSo->noi_dang_ky_kcb ?? 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-500 dark:text-gray-400">Mức lương đóng BHXH</span>
                        <span class="font-medium text-green-600 dark:text-green-400">
                            {{ number_format($luongDongBhxh, 0, ',', '.') }} VNĐ
                        </span>
                    </div>

                    {{-- CHI TIẾT ĐÓNG BH --}}
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 mt-2">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-500 dark:text-gray-400">BHXH (8%)</span>
                            <span class="font-medium text-blue-600">{{ number_format($bhxh, 0, ',', '.') }} VNĐ</span>
                        </div>
                        <div class="flex justify-between py-1 border-t border-gray-200 dark:border-gray-600">
                            <span class="text-gray-500 dark:text-gray-400">BHYT (1.5%)</span>
                            <span class="font-medium text-blue-600">{{ number_format($bhyt, 0, ',', '.') }} VNĐ</span>
                        </div>
                        <div class="flex justify-between py-1 border-t border-gray-200 dark:border-gray-600">
                            <span class="text-gray-500 dark:text-gray-400">BHTN (1%)</span>
                            <span class="font-medium text-blue-600">{{ number_format($bhtn, 0, ',', '.') }} VNĐ</span>
                        </div>
                        <div
                            class="flex justify-between py-2 border-t-2 border-gray-300 dark:border-gray-500 font-bold mt-1">
                            <span class="text-gray-700 dark:text-gray-300">Tổng đóng (10.5%)</span>
                            <span class="text-red-600">{{ number_format($tongBaoHiem, 0, ',', '.') }} VNĐ</span>
                        </div>
                    </div>

                    @if (!$hoSo->so_bhxh && !$hoSo->ma_so_thue)
                        <div
                            class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                            <p class="text-sm text-yellow-700 dark:text-yellow-300 flex items-center gap-2">
                                <span>⚠️</span> Thông tin bảo hiểm chưa được cập nhật.
                                <a href="{{ route('admin.ho-so.edit', $hoSo->id) }}"
                                    class="text-blue-600 hover:underline font-medium">Cập nhật ngay</a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- THUẾ TNCN --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <h3
                    class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                    💰 Thuế TNCN
                </h3>

                <div class="space-y-3">
                    {{-- TỔNG THU NHẬP --}}
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-500 dark:text-gray-400">📊 Tổng thu nhập</span>
                        <span class="font-bold text-blue-600 dark:text-blue-400">
                            {{ number_format($tongThuNhap, 0, ',', '.') }} VNĐ
                        </span>
                    </div>

                    {{-- CHI TIẾT TỔNG THU NHẬP --}}
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 ml-4">
                        <div class="flex justify-between py-1 text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Lương cơ bản</span>
                            <span class="font-medium">{{ number_format($luongCoBanHienTai, 0, ',', '.') }} VNĐ</span>
                        </div>
                        @if ($tongPhuCap > 0)
                            <div class="flex justify-between py-1 text-sm border-t border-gray-200 dark:border-gray-600">
                                <span class="text-gray-500 dark:text-gray-400">Phụ cấp</span>
                                <span>{{ number_format($tongPhuCap, 0, ',', '.') }} VNĐ</span>
                            </div>
                        @endif
                        @if ($tienTangCa > 0)
                            <div class="flex justify-between py-1 text-sm border-t border-gray-200 dark:border-gray-600">
                                <span class="text-gray-500 dark:text-gray-400">Tăng ca</span>
                                <span>{{ number_format($tienTangCa, 0, ',', '.') }} VNĐ</span>
                            </div>
                        @endif
                        @if ($hopDongHieuLuc)
                            <div class="flex justify-between py-1 text-sm border-t border-gray-200 dark:border-gray-600">
                                <span class="text-gray-500 dark:text-gray-400">Hợp đồng</span>
                                <span class="text-xs text-gray-400">{{ $hopDongHieuLuc->so_hop_dong }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- BẢO HIỂM --}}
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-500 dark:text-gray-400">🔻 Bảo hiểm (10.5%)</span>
                        <span class="font-medium text-red-600">
                            -{{ number_format($tongBaoHiem, 0, ',', '.') }} VNĐ
                        </span>
                    </div>

                    {{-- THU NHẬP CHỊU THUẾ --}}
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700 font-medium">
                        <span class="text-gray-600 dark:text-gray-300">📝 Thu nhập chịu thuế</span>
                        <span class="font-bold {{ $thuNhapChiuThue > 0 ? 'text-orange-600' : 'text-green-600' }}">
                            {{ number_format($thuNhapChiuThue, 0, ',', '.') }} VNĐ
                        </span>
                    </div>
                    <div class="text-xs text-gray-400 ml-4">
                        = {{ number_format($tongThuNhap, 0, ',', '.') }} -
                        {{ number_format($tongBaoHiem, 0, ',', '.') }}
                    </div>

                    {{-- GIẢM TRỪ BẢN THÂN --}}
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-500 dark:text-gray-400">👤 Giảm trừ bản thân</span>
                        <span class="font-medium text-green-600">
                            -{{ number_format($giamTruBanThan, 0, ',', '.') }} VNĐ
                        </span>
                    </div>

                    {{-- THU NHẬP TÍNH THUẾ --}}
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700 font-medium">
                        <span class="text-gray-600 dark:text-gray-300">📌 Thu nhập tính thuế</span>
                        <span class="font-bold {{ $thuNhapTinhThue > 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ number_format($thuNhapTinhThue, 0, ',', '.') }} VNĐ
                        </span>
                    </div>

                    {{-- THUẾ TNCN --}}
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border border-blue-200 dark:border-blue-800">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-600 dark:text-gray-300 font-medium">🏛️ Thuế TNCN phải nộp</span>
                            <span class="font-bold {{ $thueTncn > 0 ? 'text-red-600' : 'text-green-600' }} text-lg">
                                {{ number_format($thueTncn, 0, ',', '.') }} VNĐ
                            </span>
                        </div>
                        @if ($thuNhapTinhThue == 0)
                            <p class="text-xs text-green-600 dark:text-green-400 mt-1">✅ Không phải nộp thuế</p>
                        @else
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                ℹ️ Áp dụng biểu thuế lũy tiến từng phần
                            </p>
                        @endif
                    </div>

                    {{-- THỰC NHẬN --}}
                    <div
                        class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border-2 border-green-300 dark:border-green-700 mt-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 dark:text-gray-300 font-bold text-lg">💰 THỰC NHẬN</span>
                            <span class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ number_format($thucNhan, 0, ',', '.') }} VNĐ
                            </span>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            = {{ number_format($tongThuNhap, 0, ',', '.') }} -
                            {{ number_format($tongBaoHiem, 0, ',', '.') }} - {{ number_format($thueTncn, 0, ',', '.') }}
                        </div>
                    </div>

                    {{-- GHI CHÚ --}}
                    <div
                        class="mt-2 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            📌 * Bảo hiểm tính trên <strong>lương cơ bản</strong>:
                            {{ number_format($luongDongBhxh, 0, ',', '.') }} VNĐ × 10.5%
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            📌 * Thuế TNCN tính trên <strong>tổng thu nhập - bảo hiểm - giảm trừ</strong>
                        </p>
                        @if ($hopDongHieuLuc)
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                📄 * Theo hợp đồng: <strong>{{ $hopDongHieuLuc->so_hop_dong }}</strong>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>




    {{-- TAB 6: ĐÀO TẠO & KỶ LUẬT --}}
    <div id="tab6" class="tab-content hidden">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- ⭐ ĐÀO TẠO ĐÃ THAM GIA --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <h3
                    class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                    🎓 Đào tạo đã tham gia
                </h3>

                @if ($hoSo->dao_tao && $hoSo->dao_tao->count() > 0)
                    <div class="space-y-3">
                        @foreach ($hoSo->dao_tao as $item)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 border-l-4 border-blue-500">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span
                                            class="font-medium text-gray-800 dark:text-white">{{ $item->ten_khoa_hoc }}</span>
                                        <span
                                            class="text-sm text-gray-500 dark:text-gray-400 ml-2">({{ $item->to_chuc ?? 'N/A' }})</span>
                                    </div>
                                    @if ($item->co_chung_chi)
                                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-full">📜
                                            Có chứng chỉ</span>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    📅 {{ $item->ngay_bat_dau->format('d/m/Y') }} →
                                    {{ $item->ngay_ket_thuc ? $item->ngay_ket_thuc->format('d/m/Y') : 'Đang học' }}
                                </div>
                                @if ($item->ket_qua)
                                    <div class="text-sm text-green-600 dark:text-green-400 mt-1">
                                        ✅ Kết quả: {{ $item->ket_qua }}
                                    </div>
                                @endif
                                {{-- ⭐ BỎ CHI PHÍ VÀ GHI CHÚ --}}
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="text-4xl mb-2">📚</div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có khóa đào tạo nào</p>
                    </div>
                @endif
            </div>

            {{-- ⭐ KHEN THƯỞNG & KỶ LUẬT --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <h3
                    class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                    ⚖️ Khen thưởng & Kỷ luật
                </h3>

                @if ($hoSo->khen_thuong_ky_luat && $hoSo->khen_thuong_ky_luat->count() > 0)
                    <div class="space-y-3">
                        @foreach ($hoSo->khen_thuong_ky_luat as $item)
                            <div class="rounded-lg p-3 {{ $item->mau_loai }}">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="font-medium text-gray-800 dark:text-white">
                                            {{ $item->loai_text }}: {{ $item->ten }}
                                        </span>
                                        @if ($item->so_tien)
                                            <span
                                                class="text-sm {{ $item->loai == 'khen_thuong' ? 'text-green-600' : 'text-red-600' }} ml-2">
                                                ({{ $item->loai == 'khen_thuong' ? '+' : '-' }}{{ number_format($item->so_tien, 0, ',', '.') }}
                                                VNĐ)
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $item->ngay->format('d/m/Y') }}
                                    </span>
                                </div>
                                @if ($item->noi_dung)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        {{ $item->noi_dung }}</p>
                                @endif
                                @if ($item->hinh_thuc)
                                    <span class="text-xs text-gray-500">📌 Hình thức:
                                        {{ $item->hinh_thuc }}</span>
                                @endif
                                @if ($item->quyet_dinh_so)
                                    <span class="text-xs text-gray-500 ml-2">• QĐ:
                                        {{ $item->quyet_dinh_so }}</span>
                                @endif
                                @if ($item->nguoiKy)
                                    <div class="text-xs text-gray-400 mt-1">
                                        ✍️ Ký bởi: {{ $item->nguoiKy->ho ?? '' }}
                                        {{ $item->nguoiKy->ten ?? '' }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="text-4xl mb-2">⚖️</div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có khen thưởng hoặc kỷ luật</p>
                    </div>
                @endif
            </div>

        </div>

        {{-- THỐNG KÊ TỔNG HỢP --}}
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h3
                class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                📊 Tổng hợp
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div
                    class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 text-center border border-blue-200 dark:border-blue-800">
                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                        {{ $hoSo->dao_tao?->count() ?? 0 }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Khóa đào tạo</div>
                </div>
                <div
                    class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 text-center border border-green-200 dark:border-green-800">
                    <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                        {{ $hoSo->khen_thuong_ky_luat?->where('loai', 'khen_thuong')->count() ?? 0 }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Khen thưởng</div>
                </div>
                <div
                    class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 text-center border border-red-200 dark:border-red-800">
                    <div class="text-3xl font-bold text-red-600 dark:text-red-400">
                        {{ $hoSo->khen_thuong_ky_luat?->where('loai', 'ky_luat')->count() ?? 0 }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Kỷ luật</div>
                </div>
                <div
                    class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 text-center border border-purple-200 dark:border-purple-800">
                    <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                        {{ $hoSo->nguoiPhuThuoc?->count() ?? 0 }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Người phụ thuộc</div>
                </div>
            </div>
        </div>

    </div>

    </div>

    </div>

    {{-- ============================================================ --}}
    {{-- MODAL XEM TRƯỚC FILE (CV & HỢP ĐỒNG) --}}
    {{-- ============================================================ --}}
    <div id="filePreviewModal"
        class="fixed inset-0 z-50 hidden bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-5xl max-h-[95vh] flex flex-col">

            {{-- Header --}}
            <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 id="filePreviewTitle" class="text-lg font-semibold text-gray-800 dark:text-white">
                    📄 Xem trước tài liệu
                </h3>
                <button onclick="closeFilePreview()"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Nội dung file --}}
            <div class="flex-1 p-4 overflow-auto bg-gray-100 dark:bg-gray-900 min-h-[500px]">
                <div id="filePreviewContent" class="w-full h-full flex items-center justify-center">
                    <div class="text-center text-gray-500 dark:text-gray-400">
                        <div class="text-6xl mb-4 animate-pulse">📄</div>
                        <p>Đang tải tài liệu...</p>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex justify-end gap-2 p-4 border-t border-gray-200 dark:border-gray-700">
                <a id="fileDownloadLink" href="#" download
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    ⬇️ Tải xuống
                </a>
                <button onclick="closeFilePreview()"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                    Đóng
                </button>
            </div>

        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- JAVASCRIPT --}}
    {{-- ============================================================ --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const tabs = document.querySelectorAll('.tab-btn');
            const contents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {

                    tabs.forEach(t => {
                        t.classList.remove('active', 'bg-blue-700', 'text-white');
                        t.classList.add('text-gray-600', 'hover:bg-gray-100');
                    });

                    this.classList.add('active', 'bg-blue-700', 'text-white');
                    this.classList.remove('text-gray-600', 'hover:bg-gray-100');

                    contents.forEach(c => c.classList.add('hidden'));

                    const target = document.getElementById(this.dataset.tab);
                    if (target) {
                        target.classList.remove('hidden');
                    }

                });
            });

            const firstTab = document.querySelector('.tab-btn.active');
            if (firstTab) {
                const target = document.getElementById(firstTab.dataset.tab);
                if (target) {
                    target.classList.remove('hidden');
                }
            }

        });

        function openFilePreview(url, title) {
            const modal = document.getElementById('filePreviewModal');
            const content = document.getElementById('filePreviewContent');
            const titleEl = document.getElementById('filePreviewTitle');
            const downloadLink = document.getElementById('fileDownloadLink');

            titleEl.textContent = '📄 ' + title;
            downloadLink.href = url;

            // Hiển thị loading
            content.innerHTML = `
        <div class="text-center text-gray-500 dark:text-gray-400">
            <div class="text-6xl mb-4 animate-pulse">📄</div>
            <p>Đang tải tài liệu...</p>
        </div>
    `;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // ⭐ KIỂM TRA URL CÓ PHẢI LÀ ROUTE KHÔNG
            // Nếu URL chứa /view-cv hoặc /view-contract -> là route cần fetch
            if (url.includes('/view-cv') || url.includes('/view-contract')) {
                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }

                        const contentType = response.headers.get('content-type') || '';
                        console.log('Content-Type:', contentType);

                        // Nếu là PDF
                        if (contentType.includes('application/pdf')) {
                            return response.blob().then(blob => {
                                const blobUrl = URL.createObjectURL(blob);
                                content.innerHTML = `
                            <iframe src="${blobUrl}#toolbar=0&navpanes=0&scrollbar=0" 
                                class="w-full h-[600px] border-0 rounded-lg bg-white" 
                                style="min-height: 600px;">
                            </iframe>
                        `;
                            });
                        }
                        // Nếu là ảnh
                        else if (contentType.includes('image/')) {
                            return response.blob().then(blob => {
                                const blobUrl = URL.createObjectURL(blob);
                                content.innerHTML = `
                            <div class="flex items-center justify-center w-full h-full">
                                <img src="${blobUrl}" alt="Xem trước" 
                                    class="max-w-full max-h-[70vh] object-contain rounded-lg shadow-lg">
                            </div>
                        `;
                            });
                        }
                        // Nếu là file Office
                        else if (contentType.includes('application/vnd.openxmlformats-officedocument') ||
                            contentType.includes('application/msword') ||
                            contentType.includes('application/vnd.ms-excel')) {
                            // Sử dụng Google Docs Viewer
                            const viewerUrl =
                                `https://docs.google.com/viewer?embedded=true&url=${encodeURIComponent(url)}`;
                            content.innerHTML = `
                        <iframe src="${viewerUrl}" 
                            class="w-full h-[600px] border-0 rounded-lg bg-white" 
                            style="min-height: 600px;">
                        </iframe>
                        <p class="text-xs text-gray-400 text-center mt-2">
                            ⚡ Đang sử dụng Google Docs Viewer
                        </p>
                    `;
                        }
                        // Không xác định được
                        else {
                            content.innerHTML = `
                        <div class="text-center text-gray-500 dark:text-gray-400 py-12">
                            <div class="text-6xl mb-4">📄</div>
                            <p class="text-lg font-medium">Không thể xem trước file này</p>
                            <p class="text-sm mt-2">Định dạng: ${contentType}</p>
                            <p class="text-sm">Vui lòng <a href="${url}" download class="text-blue-600 hover:underline">tải xuống</a> để xem</p>
                        </div>
                    `;
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi tải file:', error);
                        content.innerHTML = `
                    <div class="text-center text-gray-500 dark:text-gray-400 py-12">
                        <div class="text-6xl mb-4">❌</div>
                        <p class="text-lg font-medium text-red-600 dark:text-red-400">Không thể tải file</p>
                        <p class="text-sm mt-2">${error.message}</p>
                        <p class="text-xs text-gray-400 mt-1">Vui lòng kiểm tra file đã được tải lên chưa</p>
                        <div class="mt-4 flex justify-center gap-3 flex-wrap">
                            <a href="${url}" download class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                                ⬇️ Tải xuống
                            </a>
                            <button onclick="closeFilePreview()" 
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                                Đóng
                            </button>
                        </div>
                    </div>
                `;
                    });
                return;
            }

            // ⭐ XỬ LÝ URL TRỰC TIẾP (asset)
            const ext = url.split('.').pop().toLowerCase();

            // Nếu là PDF
            if (ext === 'pdf') {
                content.innerHTML = `
            <iframe src="${url}#toolbar=0&navpanes=0&scrollbar=0" 
                class="w-full h-[600px] border-0 rounded-lg bg-white" 
                style="min-height: 600px;">
            </iframe>
        `;
            }
            // Nếu là ảnh
            else if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(ext)) {
                content.innerHTML = `
            <div class="flex items-center justify-center w-full h-full">
                <img src="${url}" alt="Xem trước" 
                    class="max-w-full max-h-[70vh] object-contain rounded-lg shadow-lg">
            </div>
        `;
            }
            // Nếu là file Office
            else if (['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'].includes(ext)) {
                const viewerUrl = `https://docs.google.com/viewer?embedded=true&url=${encodeURIComponent(url)}`;
                content.innerHTML = `
            <iframe src="${viewerUrl}" 
                class="w-full h-[600px] border-0 rounded-lg bg-white" 
                style="min-height: 600px;">
            </iframe>
            <p class="text-xs text-gray-400 text-center mt-2">
                ⚡ Đang sử dụng Google Docs Viewer
            </p>
        `;
            }
            // Không xác định
            else {
                content.innerHTML = `
            <div class="text-center text-gray-500 dark:text-gray-400 py-12">
                <div class="text-6xl mb-4">📄</div>
                <p class="text-lg font-medium">Không thể xem trước file này</p>
                <p class="text-sm mt-2">Định dạng: .${ext}</p>
                <p class="text-sm">Vui lòng <a href="${url}" download class="text-blue-600 hover:underline">tải xuống</a> để xem</p>
            </div>
        `;
            }
        }

        function closeFilePreview() {
            const modal = document.getElementById('filePreviewModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Đóng modal khi click bên ngoài
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('filePreviewModal');
            if (event.target === modal) {
                closeFilePreview();
            }
        });

        // Đóng modal khi nhấn ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeFilePreview();
            }
        });
    </script>

    <style>
        .tab-btn {
            transition: all 0.3s ease;
        }

        .tab-btn.active {
            background-color: #1d4ed8;
            color: white;
        }

        .tab-btn:not(.active):hover {
            background-color: #f3f4f6;
        }

        .tab-content {
            transition: opacity 0.3s ease;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .tab-content.hidden {
            display: none;
        }
    </style>

@endsection
