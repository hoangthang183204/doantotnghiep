@extends('layouts.admin')

@section('title', 'Chi tiết hồ sơ - ' . $hoSo->ho . ' ' . $hoSo->ten)

@section('content')

    {{-- ============================================================ --}}
    {{-- STYLE --}}
    {{-- ============================================================ --}}
    <style>
        /* ========== TOGGLE HIDE/SHOW ========== */
        .toggle-content {
            transition: all 0.3s ease;
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
            display: inline-block;
        }

        .toggle-content.hidden-content {
            filter: blur(6px);
            -webkit-filter: blur(6px);
            user-select: none;
            color: #9ca3af;
            background: rgba(156, 163, 175, 0.1);
            padding: 2px 8px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            min-width: 60px;
        }

        .toggle-content.visible-content {
            filter: blur(0px);
            -webkit-filter: blur(0px);
            user-select: text;
            color: inherit;
            background: transparent;
            padding: 2px 8px;
            border-radius: 6px;
        }

        .toggle-btn {
            cursor: pointer;
            transition: all 0.2s ease;
            padding: 2px 6px;
            border-radius: 4px;
            border: none;
            background: transparent;
            font-size: 14px;
            margin-left: 4px;
            color: #9ca3af;
        }

        .toggle-btn:hover {
            background: rgba(0, 0, 0, 0.05);
            transform: scale(1.1);
        }

        .toggle-btn.active {
            color: #2563eb;
        }

        .toggle-btn.inactive {
            color: #9ca3af;
        }

        .sensitive-label {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .pagination-btn {
            transition: all 0.2s ease;
        }

        .pagination-btn:hover:not(:disabled) {
            transform: scale(1.05);
        }

        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

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
                    @can('update', $hoSo)
                        <a href="{{ route('admin.ho-so.edit', $hoSo->id) }}"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition">
                            ✏️ Sửa hồ sơ
                        </a>
                    @endcan
                    <a href="{{ route('admin.ho-so.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                        ← Quay lại
                    </a>
                </div>

            </div>

        </div>

        {{-- ============================================================ --}}
        {{-- TAB NAVIGATION (7 TABS) - ẨN/HIỆN THEO QUYỀN --}}
        {{-- ============================================================ --}}
        @php
            $user = auth()->user();
            $userRole = $user->vaiTro->name ?? '';
            $isAdmin = $userRole === 'admin';
            $isHR = $userRole === 'hr';
            $isTruongPhong = $userRole === 'truong_phong';
            $isSelf = $user->id === $hoSo->nguoi_dung_id;

            // ⭐ Xác định quyền xem thông tin nhạy cảm (Lương, Bảo hiểm)
            $canViewSensitive = $isAdmin || $isHR;

            // ⭐ Xác định quyền xem tab
            $canViewTab1 = true; // Thông tin cơ bản - ai cũng xem được
            $canViewTab2 = $isAdmin || $isHR || $isTruongPhong || $isSelf; // Công việc & HĐ
            $canViewTab3 = $isAdmin || $isHR || $isTruongPhong || $isSelf; // Năng lực & CV
            $canViewTab4 = $canViewSensitive; // Lương thưởng - Chỉ Admin & HR
            $canViewTab5 = $canViewSensitive; // Bảo hiểm & Thuế - Chỉ Admin & HR
            $canViewTab6 = $isAdmin || $isHR || $isTruongPhong || $isSelf; // Đào tạo & Kỷ luật
            $canViewTab7 = $isAdmin || $isHR || $isTruongPhong || $isSelf; // ⭐ Lịch sử đơn từ
        @endphp

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-2">
            <nav class="flex flex-wrap gap-1" id="tabNav">
                @if ($canViewTab1)
                    <button class="tab-btn active px-5 py-2.5 rounded-lg text-sm font-medium transition" data-tab="tab1">
                        📋 Thông tin
                    </button>
                @endif
                @if ($canViewTab2)
                    <button class="tab-btn px-5 py-2.5 rounded-lg text-sm font-medium transition" data-tab="tab2">
                        💼 Công việc & HĐ
                    </button>
                @endif
                @if ($canViewTab3)
                    <button class="tab-btn px-5 py-2.5 rounded-lg text-sm font-medium transition" data-tab="tab3">
                        📄 Năng lực & CV
                    </button>
                @endif
                @if ($canViewTab4)
                    <button class="tab-btn px-5 py-2.5 rounded-lg text-sm font-medium transition" data-tab="tab4">
                        💰 Lương thưởng
                    </button>
                @endif
                @if ($canViewTab5)
                    <button class="tab-btn px-5 py-2.5 rounded-lg text-sm font-medium transition" data-tab="tab5">
                        🛡️ Bảo hiểm & Thuế
                    </button>
                @endif
                @if ($canViewTab6)
                    <button class="tab-btn px-5 py-2.5 rounded-lg text-sm font-medium transition" data-tab="tab6">
                        🏆 Đào tạo & Kỷ luật
                    </button>
                @endif
                @if ($canViewTab7)
                    <button class="tab-btn px-5 py-2.5 rounded-lg text-sm font-medium transition" data-tab="tab7">
                        📝 Lịch sử đơn từ
                    </button>
                @endif
            </nav>
        </div>

        {{-- ============================================================ --}}
        {{-- TAB CONTENT --}}
        {{-- ============================================================ --}}
        <div class="space-y-6">

            {{-- ========================================================== --}}
            {{-- TAB 1: THÔNG TIN CƠ BẢN --}}
            {{-- ========================================================== --}}
            @if ($canViewTab1)
                <div id="tab1" class="tab-content">

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                        {{-- Cột trái: Thông tin cá nhân --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

                            <div
                                class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                    🧑‍💼 Thông tin cá nhân
                                </h3>
                                @if ($canViewSensitive)
                                    <button onclick="toggleAllSensitive()"
                                        class="text-xs px-3 py-1.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition flex items-center gap-1.5">
                                        <i class="fas fa-eye" id="toggleAllIcon"></i>
                                        <span id="toggleAllText">Hiện tất cả</span>
                                    </button>
                                @endif
                            </div>

                            <div class="space-y-3">
                                {{-- Họ và tên (không nhạy cảm) --}}
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400">Họ và tên</span>
                                    <span class="font-medium">{{ $hoSo->ho }} {{ $hoSo->ten }}</span>
                                </div>

                                {{-- Mã nhân viên (không nhạy cảm) --}}
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400">Mã nhân viên</span>
                                    <span class="font-mono font-medium">{{ $hoSo->ma_nhan_vien ?? '---' }}</span>
                                </div>

                                {{-- Email (không nhạy cảm) --}}
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400">Email công ty</span>
                                    <span
                                        class="text-blue-600 dark:text-blue-400">{{ $hoSo->nguoi_dung->email ?? '---' }}</span>
                                </div>

                                {{-- Số điện thoại (NHẠY CẢM) --}}
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400">Số điện thoại</span>
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content" data-sensitive="phone">
                                            {{ $hoSo->so_dien_thoai ?? '---' }}
                                        </span>
                                        <button onclick="toggleSensitive(this, 'phone')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>

                                {{-- Ngày sinh (NHẠY CẢM) --}}
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400">Ngày sinh</span>
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content" data-sensitive="birthday">
                                            {{ $hoSo->ngay_sinh ? $hoSo->ngay_sinh->format('d/m/Y') : '---' }}
                                        </span>
                                        <button onclick="toggleSensitive(this, 'birthday')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>

                                {{-- Tuổi (không nhạy cảm) --}}
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400">Tuổi</span>
                                    <span class="font-medium">{{ $hoSo->tuoi ?? '---' }} tuổi</span>
                                </div>

                                {{-- Giới tính (không nhạy cảm) --}}
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400">Giới tính</span>
                                    <span class="font-medium">{{ $hoSo->gioi_tinh_text }}</span>
                                </div>

                                {{-- Tình trạng hôn nhân (không nhạy cảm) --}}
                                <div class="flex justify-between py-1">
                                    <span class="text-gray-500 dark:text-gray-400">Tình trạng hôn nhân</span>
                                    <span class="font-medium">{{ $hoSo->tinh_trang_hon_nhan_text }}</span>
                                </div>
                            </div>

                        </div>

                        {{-- Cột phải: Địa chỉ & Giấy tờ --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

                            <h3
                                class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                                🏠 Địa chỉ & Giấy tờ
                            </h3>

                            <div class="space-y-3">
                                {{-- Địa chỉ hiện tại (không nhạy cảm) --}}
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400">Địa chỉ hiện tại</span>
                                    <span class="font-medium text-right">{{ $hoSo->dia_chi_hien_tai ?? '---' }}</span>
                                </div>

                                {{-- Địa chỉ thường trú (không nhạy cảm) --}}
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400">Địa chỉ thường trú</span>
                                    <span class="font-medium text-right">{{ $hoSo->dia_chi_thuong_tru ?? '---' }}</span>
                                </div>

                                {{-- CMND/CCCD (NHẠY CẢM) --}}
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400">CMND/CCCD</span>
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content" data-sensitive="cccd">
                                            {{ $hoSo->cmnd_cccd ?? '---' }}
                                        </span>
                                        <button onclick="toggleSensitive(this, 'cccd')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>

                                {{-- Số hộ chiếu (NHẠY CẢM) --}}
                                <div class="flex justify-between py-1">
                                    <span class="text-gray-500 dark:text-gray-400">Số hộ chiếu</span>
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content" data-sensitive="passport">
                                            {{ $hoSo->so_ho_chieu ?? '---' }}
                                        </span>
                                        <button onclick="toggleSensitive(this, 'passport')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>

                            {{-- ẢNH CCCD (CÓ CHE) --}}
                            @if ($hoSo->anh_cccd_truoc || $hoSo->anh_cccd_sau)
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">🪪 Ảnh CCCD</h4>
                                        <button onclick="toggleCccdImages()"
                                            class="text-xs px-3 py-1.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition flex items-center gap-1.5">
                                            <i class="fas fa-eye" id="cccdToggleIcon"></i>
                                            <span id="cccdToggleText">Hiện ảnh</span>
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        {{-- Mặt trước --}}
                                        <div>
                                            @if ($hoSo->anh_cccd_truoc)
                                                <div class="relative group">
                                                    <img src="{{ asset('storage/' . $hoSo->anh_cccd_truoc) }}"
                                                        alt="CCCD mặt trước" id="cccdTruocImg"
                                                        class="w-full rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm cursor-pointer hover:opacity-90 transition cccd-image blurred"
                                                        onclick="openFilePreview('{{ asset('storage/' . $hoSo->anh_cccd_truoc) }}', 'CCCD mặt trước - {{ $hoSo->ho }} {{ $hoSo->ten }}')">
                                                    <div
                                                        class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition bg-black/30 rounded-lg">
                                                        <span
                                                            class="text-white text-sm font-medium bg-black/50 px-3 py-1 rounded">🔍
                                                            Xem</span>
                                                    </div>
                                                    {{-- ⭐ ICON KHÓA TRÊN ẢNH --}}
                                                    <div
                                                        class="absolute top-2 right-2 bg-black/60 text-white rounded-full p-1.5 cccd-lock-icon">
                                                        <i class="fas fa-lock text-xs"></i>
                                                    </div>
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-center">Mặt
                                                    trước</p>
                                            @else
                                                <div
                                                    class="w-full h-32 bg-gray-100 dark:bg-gray-700 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center">
                                                    <span class="text-gray-400 text-sm">Chưa có ảnh</span>
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-center">Mặt
                                                    trước</p>
                                            @endif
                                        </div>

                                        {{-- Mặt sau --}}
                                        <div>
                                            @if ($hoSo->anh_cccd_sau)
                                                <div class="relative group">
                                                    <img src="{{ asset('storage/' . $hoSo->anh_cccd_sau) }}"
                                                        alt="CCCD mặt sau" id="cccdSauImg"
                                                        class="w-full rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm cursor-pointer hover:opacity-90 transition cccd-image blurred"
                                                        onclick="openFilePreview('{{ asset('storage/' . $hoSo->anh_cccd_sau) }}', 'CCCD mặt sau - {{ $hoSo->ho }} {{ $hoSo->ten }}')">
                                                    <div
                                                        class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition bg-black/30 rounded-lg">
                                                        <span
                                                            class="text-white text-sm font-medium bg-black/50 px-3 py-1 rounded">🔍
                                                            Xem</span>
                                                    </div>
                                                    {{-- ⭐ ICON KHÓA TRÊN ẢNH --}}
                                                    <div
                                                        class="absolute top-2 right-2 bg-black/60 text-white rounded-full p-1.5 cccd-lock-icon">
                                                        <i class="fas fa-lock text-xs"></i>
                                                    </div>
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-center">Mặt
                                                    sau</p>
                                            @else
                                                <div
                                                    class="w-full h-32 bg-gray-100 dark:bg-gray-700 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center">
                                                    <span class="text-gray-400 text-sm">Chưa có ảnh</span>
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-center">Mặt
                                                    sau</p>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-2">💡 Click vào ảnh để xem phóng to (sau khi bỏ che)
                                    </p>
                                </div>
                            @else
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

                    {{-- LIÊN HỆ KHẨN CẤP (NHẠY CẢM - Chỉ Admin, HR và chính nhân viên đó mới xem được) --}}
                    @if ($canViewSensitive || $isSelf)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mt-6">

                            <h3
                                class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                                📞 Liên hệ khẩn cấp
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                {{-- Họ tên LHKC (NHẠY CẢM) --}}
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm block">Họ tên</span>
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content font-medium text-lg"
                                            data-sensitive="emergency_name">
                                            {{ $hoSo->lien_he_khan_cap ?? '---' }}
                                        </span>
                                        <button onclick="toggleSensitive(this, 'emergency_name')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>

                                {{-- SĐT LHKC (NHẠY CẢM) --}}
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm block">Số điện thoại</span>
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content font-medium text-lg"
                                            data-sensitive="emergency_phone">
                                            {{ $hoSo->sdt_khan_cap ?? '---' }}
                                        </span>
                                        <button onclick="toggleSensitive(this, 'emergency_phone')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>

                                {{-- Quan hệ LHKC (NHẠY CẢM) --}}
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm block">Mối quan hệ</span>
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content font-medium text-lg"
                                            data-sensitive="emergency_relation">
                                            {{ $hoSo->quan_he_khan_cap ?? '---' }}
                                        </span>
                                        <button onclick="toggleSensitive(this, 'emergency_relation')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>

                            </div>

                        </div>
                    @endif

                </div>
            @endif

            {{-- ========================================================== --}}
            {{-- TAB 2: CÔNG VIỆC & HỢP ĐỒNG --}}
            {{-- ========================================================== --}}
            @if ($canViewTab2)
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

                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">📄 Lịch sử hợp đồng lao động
                            </h4>

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

                                                    @php
                                                        $filePath = $item->file_hop_dong_da_ky
                                                            ? storage_path('app/public/' . $item->file_hop_dong_da_ky)
                                                            : null;
                                                        $fileExists = $filePath && file_exists($filePath);
                                                    @endphp

                                                    @if ($item->file_hop_dong_da_ky && $fileExists)
                                                        <div class="mt-3 flex flex-wrap gap-2">
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
            @endif

            {{-- ========================================================== --}}
            {{-- TAB 3: NĂNG LỰC & CV --}}
            {{-- ========================================================== --}}
            @if ($canViewTab3)
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
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $hoSo->cv->ten_file_goc }}
                                        </p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $hoSo->cv->kich_thuoc }} •
                                            {{ $hoSo->cv->loai_mime }}</p>
                                    @else
                                        <p class="text-sm text-gray-400">Chưa có CV</p>
                                    @endif
                                </div>
                                <div class="flex gap-2">
                                    @if ($hoSo->cv)
                                        <div class="flex gap-1.5">
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

                        {{-- KỸ NĂNG CHUYÊN MÔN --}}
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

                        {{-- CHỨNG CHỈ --}}
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
                                            <div
                                                class="flex justify-between mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                <span>📅 {{ $item->nam_cap }}</span>
                                                @if ($item->ngay_het_han)
                                                    <span>⏳ Hết hạn: {{ $item->ngay_het_han->format('d/m/Y') }}</span>
                                                @else
                                                    <span>♾️ Không hết hạn</span>
                                                @endif
                                            </div>
                                            @if ($item->file_dinh_kem)
                                                <div class="mt-2">
                                                    <a href="{{ asset('storage/' . $item->file_dinh_kem) }}"
                                                        target="_blank"
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

                        {{-- DỰ ÁN ĐÃ THAM GIA --}}
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">

                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">🚀 Dự án đã tham gia</h4>

                            @php
                                $duAn = $hoSo->du_an ?? collect();
                                $duAnPaginated = $duAn->sortByDesc('ngay_bat_dau');
                                $perPage = 3;
                                $currentPage = request()->get('du_an_page', 1);
                                $offset = ($currentPage - 1) * $perPage;
                                $duAnItems = $duAnPaginated->slice($offset, $perPage);
                                $totalDuAn = $duAnPaginated->count();
                                $totalPages = ceil($totalDuAn / $perPage);
                            @endphp

                            @if ($duAn && $duAn->count() > 0)
                                <div class="space-y-3" id="duAnContainer">
                                    @foreach ($duAnItems as $item)
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
                                                    "{{ $item->mo_ta }}"
                                                </p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                @if ($totalPages > 1)
                                    <div
                                        class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            Hiển thị {{ $duAnItems->count() }} / {{ $totalDuAn }} dự án
                                        </span>
                                        <div class="flex gap-1">
                                            @if ($currentPage > 1)
                                                <button onclick="changeDuAnPage({{ $currentPage - 1 }})"
                                                    class="px-3 py-1.5 text-sm bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-lg transition">
                                                    ←
                                                </button>
                                            @else
                                                <button disabled
                                                    class="px-3 py-1.5 text-sm bg-gray-100 dark:bg-gray-800 text-gray-400 rounded-lg cursor-not-allowed">
                                                    ←
                                                </button>
                                            @endif

                                            @for ($i = 1; $i <= $totalPages; $i++)
                                                <button onclick="changeDuAnPage({{ $i }})"
                                                    class="px-3 py-1.5 text-sm rounded-lg transition
                                                    {{ $i == $currentPage ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
                                                    {{ $i }}
                                                </button>
                                            @endfor

                                            @if ($currentPage < $totalPages)
                                                <button onclick="changeDuAnPage({{ $currentPage + 1 }})"
                                                    class="px-3 py-1.5 text-sm bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-lg transition">
                                                    →
                                                </button>
                                            @else
                                                <button disabled
                                                    class="px-3 py-1.5 text-sm bg-gray-100 dark:bg-gray-800 text-gray-400 rounded-lg cursor-not-allowed">
                                                    →
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <div class="mt-2 text-xs text-gray-400">
                                    📌 Tổng: {{ $totalDuAn }} dự án đã tham gia
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có dự án</p>
                            @endif

                        </div>

                    </div>

                </div>
            @endif

            {{-- ========================================================== --}}
            {{-- TAB 4: LƯƠNG THƯỞNG (CHỈ ADMIN & HR) --}}
            {{-- ========================================================== --}}
            @if ($canViewTab4)
                <div id="tab4" class="tab-content hidden">

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

                        <div
                            class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                💰 Thông tin lương thưởng
                            </h3>
                            <button onclick="toggleAllSensitive()"
                                class="text-xs px-3 py-1.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition flex items-center gap-1.5">
                                <i class="fas fa-eye" id="toggleAllIconTab4"></i>
                                <span id="toggleAllTextTab4">Hiện tất cả</span>
                            </button>
                        </div>

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
                                {{-- Chủ tài khoản (NHẠY CẢM) --}}
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm border border-gray-200 dark:border-gray-700">
                                    <span class="text-xs text-gray-500 dark:text-gray-400 uppercase block font-medium">Chủ
                                        tài khoản</span>
                                    <span class="sensitive-label">
                                        <span
                                            class="toggle-content hidden-content font-semibold text-gray-800 dark:text-white text-lg"
                                            data-sensitive="bank_owner">
                                            {{ $hoSo->chu_tai_khoan ?? 'Chưa cập nhật' }}
                                        </span>
                                        <button onclick="toggleSensitive(this, 'bank_owner')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>

                                {{-- Số tài khoản (NHẠY CẢM) --}}
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm border border-gray-200 dark:border-gray-700">
                                    <span class="text-xs text-gray-500 dark:text-gray-400 uppercase block font-medium">Số
                                        tài khoản</span>
                                    <span class="sensitive-label">
                                        <span
                                            class="toggle-content hidden-content font-mono font-bold text-gray-800 dark:text-white text-lg"
                                            data-sensitive="bank_account">
                                            {{ $hoSo->so_tai_khoan ?? 'Chưa cập nhật' }}
                                        </span>
                                        <button onclick="toggleSensitive(this, 'bank_account')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>

                                {{-- Ngân hàng (không nhạy cảm) --}}
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm border border-gray-200 dark:border-gray-700">
                                    <span class="text-xs text-gray-500 dark:text-gray-400 uppercase block font-medium">Ngân
                                        hàng</span>
                                    <p class="font-semibold text-gray-800 dark:text-white text-lg">
                                        {{ $hoSo->ten_ngan_hang ?? 'Chưa cập nhật' }}
                                    </p>
                                </div>

                                {{-- Chi nhánh (không nhạy cảm) --}}
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

                        {{-- ⭐ LƯƠNG HIỆN TẠI --}}
                        @php
                            $luongCoBanHienTai = $hopDongHieuLuc->luong_co_ban ?? 0;

                            $tongPhuCap = 0;
                            if ($hopDongHieuLuc) {
                                if (!empty($hopDongHieuLuc->phu_cap)) {
                                    $phuCapIds = is_string($hopDongHieuLuc->phu_cap)
                                        ? json_decode($hopDongHieuLuc->phu_cap, true)
                                        : $hopDongHieuLuc->phu_cap;

                                    if (is_array($phuCapIds) && count($phuCapIds) > 0) {
                                        $tongPhuCap = \App\Models\PhuCap::whereIn('id', $phuCapIds)->sum(
                                            'so_tien_mac_dinh',
                                        );
                                    }
                                }
                            }

                            $tienTangCa = $luongGanNhat->tien_tang_ca ?? 0;
                            $coTangCa = $tienTangCa > 0;

                            $tongThuNhap = $luongCoBanHienTai + $tongPhuCap + $tienTangCa;

                            $luongDongBhxh = $hopDongHieuLuc->luong_co_ban ?? 0;

                            $bhxh = round($luongDongBhxh * 0.08, 0);
                            $bhyt = round($luongDongBhxh * 0.015, 0);
                            $bhtn = round($luongDongBhxh * 0.01, 0);
                            $tongBaoHiem = $bhxh + $bhyt + $bhtn;

                            $soNguoiPhuThuoc = $hoSo->nguoiPhuThuoc?->count() ?? 0;
                            $giamTruBanThan = 15500000;
                            $giamTruGiaCanh = $giamTruBanThan + 6200000 * $soNguoiPhuThuoc;

                            $thuNhapChiuThue = max(0, $tongThuNhap - $tongBaoHiem);
                            $thuNhapTinhThue = max(0, $thuNhapChiuThue - $giamTruGiaCanh);

                            $thueTncn = 0;
                            $remaining = $thuNhapTinhThue;
                            $bac = [
                                ['tu' => 0, 'den' => 10000000, 'thue_suat' => 0.05],
                                ['tu' => 10000000, 'den' => 30000000, 'thue_suat' => 0.1],
                                ['tu' => 30000000, 'den' => 60000000, 'thue_suat' => 0.2],
                                ['tu' => 60000000, 'den' => 100000000, 'thue_suat' => 0.3],
                                ['tu' => 100000000, 'den' => PHP_INT_MAX, 'thue_suat' => 0.35],
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
                            $coPhuCap = $tongPhuCap > 0;
                        @endphp

                        {{-- 3 THẺ LƯƠNG --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            {{-- Lương cơ bản (NHẠY CẢM) --}}
                            <div
                                class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center border border-gray-200 dark:border-gray-600">
                                <p class="text-sm text-gray-500 dark:text-gray-400">📋 Lương cơ bản</p>
                                <span class="sensitive-label">
                                    <span
                                        class="toggle-content hidden-content text-lg font-bold text-blue-600 dark:text-blue-400"
                                        data-sensitive="salary_basic">
                                        {{ number_format($luongCoBanHienTai, 0, ',', '.') }} ₫
                                    </span>
                                    <button onclick="toggleSensitive(this, 'salary_basic')" class="toggle-btn"
                                        title="Nhấn để xem">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </span>
                                @if ($hopDongHieuLuc)
                                    <p class="text-xs text-gray-400">📄 {{ $hopDongHieuLuc->so_hop_dong }}</p>
                                @endif
                            </div>

                            {{-- Tổng thu nhập (NHẠY CẢM) --}}
                            <div
                                class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center border border-gray-200 dark:border-gray-600">
                                <p class="text-sm text-gray-500 dark:text-gray-400">📊 Tổng thu nhập</p>
                                <span class="sensitive-label">
                                    <span
                                        class="toggle-content hidden-content text-lg font-bold text-green-600 dark:text-green-400"
                                        data-sensitive="salary_total">
                                        {{ number_format($tongThuNhap, 0, ',', '.') }} ₫
                                    </span>
                                    <button onclick="toggleSensitive(this, 'salary_total')" class="toggle-btn"
                                        title="Nhấn để xem">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </span>
                                <p class="text-xs text-gray-400">
                                    = {{ number_format($luongCoBanHienTai, 0, ',', '.') }}
                                    @if ($coPhuCap)
                                        + {{ number_format($tongPhuCap, 0, ',', '.') }}
                                    @endif
                                    @if ($coTangCa)
                                        + {{ number_format($tienTangCa, 0, ',', '.') }}
                                    @endif
                                </p>
                            </div>

                            {{-- Thực nhận (NHẠY CẢM) --}}
                            <div
                                class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center border border-gray-200 dark:border-gray-600">
                                <p class="text-sm text-gray-500 dark:text-gray-400">💰 Thực nhận</p>
                                <span class="sensitive-label">
                                    <span
                                        class="toggle-content hidden-content text-lg font-bold text-indigo-600 dark:text-indigo-400"
                                        data-sensitive="salary_net">
                                        {{ number_format($thucNhan, 0, ',', '.') }} ₫
                                    </span>
                                    <button onclick="toggleSensitive(this, 'salary_net')" class="toggle-btn"
                                        title="Nhấn để xem">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </span>
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
                                {{-- BHXH (NHẠY CẢM) --}}
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-2 text-center border border-gray-200 dark:border-gray-600">
                                    <p class="text-xs text-gray-500">BHXH (8%)</p>
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content font-bold text-blue-600"
                                            data-sensitive="bhxh">
                                            {{ number_format($bhxh, 0, ',', '.') }} ₫
                                        </span>
                                        <button onclick="toggleSensitive(this, 'bhxh')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>

                                {{-- BHYT (NHẠY CẢM) --}}
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-2 text-center border border-gray-200 dark:border-gray-600">
                                    <p class="text-xs text-gray-500">BHYT (1.5%)</p>
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content font-bold text-blue-600"
                                            data-sensitive="bhyt">
                                            {{ number_format($bhyt, 0, ',', '.') }} ₫
                                        </span>
                                        <button onclick="toggleSensitive(this, 'bhyt')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>

                                {{-- BHTN (NHẠY CẢM) --}}
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-2 text-center border border-gray-200 dark:border-gray-600">
                                    <p class="text-xs text-gray-500">BHTN (1%)</p>
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content font-bold text-blue-600"
                                            data-sensitive="bhtn">
                                            {{ number_format($bhtn, 0, ',', '.') }} ₫
                                        </span>
                                        <button onclick="toggleSensitive(this, 'bhtn')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>

                                {{-- Tổng BH (NHẠY CẢM) --}}
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-2 text-center border-2 border-red-200 dark:border-red-700">
                                    <p class="text-xs text-gray-500">Tổng</p>
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content font-bold text-red-600"
                                            data-sensitive="tong_bh">
                                            {{ number_format($tongBaoHiem, 0, ',', '.') }} ₫
                                        </span>
                                        <button onclick="toggleSensitive(this, 'tong_bh')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mt-2">📌 Tính trên lương cơ bản:
                                {{ number_format($luongDongBhxh, 0, ',', '.') }} ₫</p>
                        </div>

                        {{-- PHỤ CẤP --}}
                        @if ($coPhuCap)
                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">📌 Phụ cấp</h4>
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        $phuCapIds = [];
                                        if ($hopDongHieuLuc && !empty($hopDongHieuLuc->phu_cap)) {
                                            $phuCapIds = is_string($hopDongHieuLuc->phu_cap)
                                                ? json_decode($hopDongHieuLuc->phu_cap, true)
                                                : $hopDongHieuLuc->phu_cap;
                                        }
                                        $phuCapChiTiets = \App\Models\PhuCap::whereIn('id', $phuCapIds)->get();
                                    @endphp
                                    @foreach ($phuCapChiTiets as $pc)
                                        <span
                                            class="px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 rounded-full text-sm border border-blue-200 dark:border-blue-800">
                                            {{ $pc->ten }}:
                                            <span class="sensitive-label">
                                                <span class="toggle-content hidden-content"
                                                    data-sensitive="phu_cap_{{ $pc->id }}">
                                                    <strong>{{ number_format($pc->so_tien_mac_dinh, 0, ',', '.') }}
                                                        ₫</strong>
                                                </span>
                                                <button onclick="toggleSensitive(this, 'phu_cap_{{ $pc->id }}')"
                                                    class="toggle-btn" title="Nhấn để xem">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            </span>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- TĂNG CA --}}
                        @if ($coTangCa)
                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">⏰ Tăng ca</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="px-3 py-1.5 bg-yellow-50 dark:bg-yellow-900/20 rounded-full text-sm border border-yellow-200 dark:border-yellow-800">
                                        Tiền tăng ca:
                                        <span class="sensitive-label">
                                            <span
                                                class="toggle-content hidden-content text-yellow-600 dark:text-yellow-400"
                                                data-sensitive="tang_ca">
                                                <strong>{{ number_format($tienTangCa, 0, ',', '.') }} ₫</strong>
                                            </span>
                                            <button onclick="toggleSensitive(this, 'tang_ca')" class="toggle-btn"
                                                title="Nhấn để xem">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @endif

                        {{-- KHẤU TRỪ --}}
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">📌 Khấu trừ</h4>
                            <div class="flex flex-wrap gap-2">
                                <span
                                    class="px-3 py-1.5 bg-red-50 dark:bg-red-900/20 rounded-full text-sm border border-red-200 dark:border-red-800">
                                    BHXH:
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content text-red-600"
                                            data-sensitive="khau_tru_bhxh">
                                            <strong>-{{ number_format($bhxh, 0, ',', '.') }} ₫</strong>
                                        </span>
                                        <button onclick="toggleSensitive(this, 'khau_tru_bhxh')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </span>
                                <span
                                    class="px-3 py-1.5 bg-red-50 dark:bg-red-900/20 rounded-full text-sm border border-red-200 dark:border-red-800">
                                    BHYT:
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content text-red-600"
                                            data-sensitive="khau_tru_bhyt">
                                            <strong>-{{ number_format($bhyt, 0, ',', '.') }} ₫</strong>
                                        </span>
                                        <button onclick="toggleSensitive(this, 'khau_tru_bhyt')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </span>
                                <span
                                    class="px-3 py-1.5 bg-red-50 dark:bg-red-900/20 rounded-full text-sm border border-red-200 dark:border-red-800">
                                    BHTN:
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content text-red-600"
                                            data-sensitive="khau_tru_bhtn">
                                            <strong>-{{ number_format($bhtn, 0, ',', '.') }} ₫</strong>
                                        </span>
                                        <button onclick="toggleSensitive(this, 'khau_tru_bhtn')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </span>
                                @if ($thueTncn > 0)
                                    <span
                                        class="px-3 py-1.5 bg-red-50 dark:bg-red-900/20 rounded-full text-sm border border-red-200 dark:border-red-800">
                                        Thuế TNCN:
                                        <span class="sensitive-label">
                                            <span class="toggle-content hidden-content text-red-600"
                                                data-sensitive="khau_tru_thue">
                                                <strong>-{{ number_format($thueTncn, 0, ',', '.') }} ₫</strong>
                                            </span>
                                            <button onclick="toggleSensitive(this, 'khau_tru_thue')" class="toggle-btn"
                                                title="Nhấn để xem">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                        </span>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- THUẾ TNCN --}}
                        <div
                            class="mb-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-700 dark:text-gray-300">🏛️ Thuế TNCN</span>
                                <span class="sensitive-label">
                                    <span
                                        class="toggle-content hidden-content font-bold {{ $thueTncn > 0 ? 'text-red-600' : 'text-green-600' }} text-lg"
                                        data-sensitive="thue_tncn">
                                        {{ number_format($thueTncn, 0, ',', '.') }} ₫
                                    </span>
                                    <button onclick="toggleSensitive(this, 'thue_tncn')" class="toggle-btn"
                                        title="Nhấn để xem">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </span>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Thu nhập chịu thuế: {{ number_format($thuNhapChiuThue, 0, ',', '.') }} ₫
                                @if ($thueTncn > 0)
                                    <span class="text-gray-400">| Áp dụng biểu thuế lũy tiến</span>
                                @endif
                            </div>
                            @if ($thuNhapTinhThue == 0)
                                <p class="text-xs text-green-600 dark:text-green-400 mt-1">✅ Không phải nộp thuế</p>
                            @endif
                        </div>

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
                                                        <span class="sensitive-label">
                                                            <span class="toggle-content hidden-content"
                                                                data-sensitive="ls_luong_cb_{{ $item->id }}">
                                                                {{ number_format($item->luong_co_ban ?? 0, 0, ',', '.') }}
                                                            </span>
                                                            <button
                                                                onclick="toggleSensitive(this, 'ls_luong_cb_{{ $item->id }}')"
                                                                class="toggle-btn" title="Nhấn để xem">
                                                                <i class="fas fa-eye-slash"></i>
                                                            </button>
                                                        </span>
                                                    </td>
                                                    <td class="p-2 font-bold text-green-600">
                                                        <span class="sensitive-label">
                                                            <span class="toggle-content hidden-content"
                                                                data-sensitive="ls_thuc_nhan_{{ $item->id }}">
                                                                {{ number_format($item->luong_thuc_nhan ?? 0, 0, ',', '.') }}
                                                            </span>
                                                            <button
                                                                onclick="toggleSensitive(this, 'ls_thuc_nhan_{{ $item->id }}')"
                                                                class="toggle-btn" title="Nhấn để xem">
                                                                <i class="fas fa-eye-slash"></i>
                                                            </button>
                                                        </span>
                                                    </td>
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
            @endif

            {{-- ========================================================== --}}
            {{-- TAB 5: BẢO HIỂM & THUẾ (CHỈ ADMIN & HR) --}}
            {{-- ========================================================== --}}
            @if ($canViewTab5)
                <div id="tab5" class="tab-content hidden">

                    @php
                        $luongCoBanHienTai = $hopDongHieuLuc->luong_co_ban ?? 0;

                        $tongPhuCap = 0;
                        if ($hopDongHieuLuc) {
                            if (!empty($hopDongHieuLuc->phu_cap)) {
                                $phuCapIds = is_string($hopDongHieuLuc->phu_cap)
                                    ? json_decode($hopDongHieuLuc->phu_cap, true)
                                    : $hopDongHieuLuc->phu_cap;

                                if (is_array($phuCapIds) && count($phuCapIds) > 0) {
                                    $tongPhuCap = \App\Models\PhuCap::whereIn('id', $phuCapIds)->sum(
                                        'so_tien_mac_dinh',
                                    );
                                }
                            }

                            if ($tongPhuCap == 0) {
                                $phuCapNhanVien = \App\Models\PhuCapNhanVien::where(
                                    'nguoi_dung_id',
                                    $hoSo->nguoi_dung_id,
                                )
                                    ->where('trang_thai', 'hieu_luc')
                                    ->where('ngay_hieu_luc', '<=', now())
                                    ->where(function ($q) {
                                        $q->whereNull('ngay_ket_thuc')->orWhere('ngay_ket_thuc', '>=', now());
                                    })
                                    ->sum('so_tien');
                                $tongPhuCap = $phuCapNhanVien > 0 ? $phuCapNhanVien : 0;
                            }
                        }

                        $tienTangCa = $luongGanNhat->tien_tang_ca ?? 0;
                        $tongThuNhap = $luongCoBanHienTai + $tongPhuCap + $tienTangCa;

                        $luongDongBhxh = $hopDongHieuLuc->luong_co_ban ?? 0;

                        $bhxh = round($luongDongBhxh * 0.08, 0);
                        $bhyt = round($luongDongBhxh * 0.015, 0);
                        $bhtn = round($luongDongBhxh * 0.01, 0);
                        $tongBaoHiem = $bhxh + $bhyt + $bhtn;

                        $soNguoiPhuThuoc = $hoSo->nguoiPhuThuoc?->count() ?? 0;
                        $giamTruBanThan = 15500000;
                        $giamTruGiaCanh = $giamTruBanThan + 6200000 * $soNguoiPhuThuoc;

                        $thuNhapChiuThue = max(0, $tongThuNhap - $tongBaoHiem);
                        $thuNhapTinhThue = max(0, $thuNhapChiuThue - $giamTruGiaCanh);

                        $thueTncn = 0;
                        $remaining = $thuNhapTinhThue;
                        $bac = [
                            ['tu' => 0, 'den' => 10000000, 'thue_suat' => 0.05],
                            ['tu' => 10000000, 'den' => 30000000, 'thue_suat' => 0.1],
                            ['tu' => 30000000, 'den' => 60000000, 'thue_suat' => 0.2],
                            ['tu' => 60000000, 'den' => 100000000, 'thue_suat' => 0.3],
                            ['tu' => 100000000, 'den' => PHP_INT_MAX, 'thue_suat' => 0.35],
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
                        $coPhuCap = $tongPhuCap > 0;
                    @endphp

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                        {{-- BẢO HIỂM XÃ HỘI --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                            <div
                                class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">🛡️ Bảo hiểm xã hội</h3>
                                <button onclick="toggleAllSensitive()"
                                    class="text-xs px-3 py-1.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition flex items-center gap-1.5">
                                    <i class="fas fa-eye"></i>
                                    <span>Hiện tất cả</span>
                                </button>
                            </div>
                            <div class="space-y-3">
                                {{-- Số sổ BHXH (NHẠY CẢM) --}}
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400">Số sổ BHXH</span>
                                    <span class="sensitive-label">
                                        <span
                                            class="toggle-content hidden-content font-mono font-medium text-gray-800 dark:text-white"
                                            data-sensitive="bhxh_number">
                                            {{ $hoSo->so_bhxh ?? 'Chưa cập nhật' }}
                                        </span>
                                        <button onclick="toggleSensitive(this, 'bhxh_number')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>

                                {{-- Mã số thuế TNCN (NHẠY CẢM) --}}
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400">Mã số thuế TNCN</span>
                                    <span class="sensitive-label">
                                        <span
                                            class="toggle-content hidden-content font-mono font-medium text-gray-800 dark:text-white"
                                            data-sensitive="tax_code">
                                            {{ $hoSo->ma_so_thue ?? 'Chưa cập nhật' }}
                                        </span>
                                        <button onclick="toggleSensitive(this, 'tax_code')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>

                                {{-- Nơi đăng ký KCB (không nhạy cảm) --}}
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400">Nơi đăng ký KCB</span>
                                    <span
                                        class="font-medium text-gray-800 dark:text-white">{{ $hoSo->noi_dang_ky_kcb ?? 'Chưa cập nhật' }}</span>
                                </div>

                                {{-- Mức lương đóng BHXH (NHẠY CẢM) --}}
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400">Mức lương đóng BHXH</span>
                                    <span class="sensitive-label">
                                        <span
                                            class="toggle-content hidden-content font-medium text-green-600 dark:text-green-400"
                                            data-sensitive="bhxh_salary">
                                            {{ number_format($luongDongBhxh, 0, ',', '.') }} VNĐ
                                        </span>
                                        <button onclick="toggleSensitive(this, 'bhxh_salary')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>

                                {{-- CHI TIẾT ĐÓNG BH (NHẠY CẢM) --}}
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 mt-2">
                                    <div class="flex justify-between py-1">
                                        <span class="text-gray-500 dark:text-gray-400">BHXH (8%)</span>
                                        <span class="sensitive-label">
                                            <span class="toggle-content hidden-content font-medium text-blue-600"
                                                data-sensitive="bhxh_detail">
                                                {{ number_format($bhxh, 0, ',', '.') }} VNĐ
                                            </span>
                                            <button onclick="toggleSensitive(this, 'bhxh_detail')" class="toggle-btn"
                                                title="Nhấn để xem">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <div class="flex justify-between py-1 border-t border-gray-200 dark:border-gray-600">
                                        <span class="text-gray-500 dark:text-gray-400">BHYT (1.5%)</span>
                                        <span class="sensitive-label">
                                            <span class="toggle-content hidden-content font-medium text-blue-600"
                                                data-sensitive="bhyt_detail">
                                                {{ number_format($bhyt, 0, ',', '.') }} VNĐ
                                            </span>
                                            <button onclick="toggleSensitive(this, 'bhyt_detail')" class="toggle-btn"
                                                title="Nhấn để xem">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <div class="flex justify-between py-1 border-t border-gray-200 dark:border-gray-600">
                                        <span class="text-gray-500 dark:text-gray-400">BHTN (1%)</span>
                                        <span class="sensitive-label">
                                            <span class="toggle-content hidden-content font-medium text-blue-600"
                                                data-sensitive="bhtn_detail">
                                                {{ number_format($bhtn, 0, ',', '.') }} VNĐ
                                            </span>
                                            <button onclick="toggleSensitive(this, 'bhtn_detail')" class="toggle-btn"
                                                title="Nhấn để xem">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <div
                                        class="flex justify-between py-2 border-t-2 border-gray-300 dark:border-gray-500 font-bold mt-1">
                                        <span class="text-gray-700 dark:text-gray-300">Tổng đóng (10.5%)</span>
                                        <span class="sensitive-label">
                                            <span class="toggle-content hidden-content text-red-600"
                                                data-sensitive="tong_bh_detail">
                                                {{ number_format($tongBaoHiem, 0, ',', '.') }} VNĐ
                                            </span>
                                            <button onclick="toggleSensitive(this, 'tong_bh_detail')" class="toggle-btn"
                                                title="Nhấn để xem">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                        </span>
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
                        <div
                            class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-semibold text-gray-700 dark:text-gray-300">🏛️ Thuế TNCN</span>
                                <span class="sensitive-label">
                                    <span
                                        class="toggle-content hidden-content font-bold {{ $thueTncn > 0 ? 'text-red-600' : 'text-green-600' }} text-lg"
                                        data-sensitive="thue_tncn_tab5">
                                        {{ number_format($thueTncn, 0, ',', '.') }} ₫
                                    </span>
                                    <button onclick="toggleSensitive(this, 'thue_tncn_tab5')" class="toggle-btn"
                                        title="Nhấn để xem">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </span>
                            </div>

                            {{-- Tổng thu nhập (NHẠY CẢM) --}}
                            <div class="flex justify-between py-1 text-sm border-t border-gray-200 dark:border-gray-600">
                                <span class="text-gray-500 dark:text-gray-400">📊 Tổng thu nhập</span>
                                <span class="sensitive-label">
                                    <span
                                        class="toggle-content hidden-content font-medium text-gray-700 dark:text-gray-300"
                                        data-sensitive="tong_thu_nhap_tab5">
                                        {{ number_format($tongThuNhap, 0, ',', '.') }} ₫
                                    </span>
                                    <button onclick="toggleSensitive(this, 'tong_thu_nhap_tab5')" class="toggle-btn"
                                        title="Nhấn để xem">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </span>
                            </div>

                            <div class="pl-4 text-xs text-gray-400">
                                = {{ number_format($luongCoBanHienTai, 0, ',', '.') }}
                                @if ($coPhuCap)
                                    + {{ number_format($tongPhuCap, 0, ',', '.') }}
                                @endif
                                @if ($coTangCa)
                                    + {{ number_format($tienTangCa, 0, ',', '.') }}
                                @endif
                            </div>

                            {{-- Bảo hiểm (NHẠY CẢM) --}}
                            <div class="flex justify-between py-1 text-sm border-t border-gray-200 dark:border-gray-600">
                                <span class="text-gray-500 dark:text-gray-400">🔻 Bảo hiểm (10.5%)</span>
                                <span class="sensitive-label">
                                    <span class="toggle-content hidden-content font-medium text-red-600"
                                        data-sensitive="bao_hiem_tab5">
                                        -{{ number_format($tongBaoHiem, 0, ',', '.') }} ₫
                                    </span>
                                    <button onclick="toggleSensitive(this, 'bao_hiem_tab5')" class="toggle-btn"
                                        title="Nhấn để xem">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </span>
                            </div>

                            {{-- Thu nhập chịu thuế (NHẠY CẢM) --}}
                            <div
                                class="flex justify-between py-1 text-sm border-t border-gray-200 dark:border-gray-600 font-medium">
                                <span class="text-gray-600 dark:text-gray-300">📝 Thu nhập chịu thuế</span>
                                <span class="sensitive-label">
                                    <span
                                        class="toggle-content hidden-content font-bold {{ $thuNhapChiuThue > 0 ? 'text-orange-600' : 'text-green-600' }}"
                                        data-sensitive="thu_nhap_chiu_thue">
                                        {{ number_format($thuNhapChiuThue, 0, ',', '.') }} ₫
                                    </span>
                                    <button onclick="toggleSensitive(this, 'thu_nhap_chiu_thue')" class="toggle-btn"
                                        title="Nhấn để xem">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </span>
                            </div>
                            <div class="text-xs text-gray-400 pl-4">
                                = {{ number_format($tongThuNhap, 0, ',', '.') }} -
                                {{ number_format($tongBaoHiem, 0, ',', '.') }}
                            </div>

                            {{-- Thuế TNCN (NHẠY CẢM) --}}
                            <div class="flex justify-between py-2 mt-1 border-t-2 border-blue-300 dark:border-blue-700">
                                <span class="font-semibold text-gray-700 dark:text-gray-300">🏛️ Thuế TNCN</span>
                                <span class="sensitive-label">
                                    <span
                                        class="toggle-content hidden-content font-bold {{ $thueTncn > 0 ? 'text-red-600' : 'text-green-600' }} text-lg"
                                        data-sensitive="thue_tncn_final">
                                        {{ number_format($thueTncn, 0, ',', '.') }} ₫
                                    </span>
                                    <button onclick="toggleSensitive(this, 'thue_tncn_final')" class="toggle-btn"
                                        title="Nhấn để xem">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </span>
                            </div>

                            @if ($thueTncn > 0)
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">ℹ️ Áp dụng biểu thuế lũy tiến
                                    từng phần</div>
                            @else
                                <div class="text-xs text-green-600 dark:text-green-400 mt-1">✅ Không phải nộp thuế</div>
                            @endif

                            {{-- THỰC NHẬN (NHẠY CẢM) --}}
                            <div
                                class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border-2 border-green-300 dark:border-green-700 mt-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700 dark:text-gray-300 font-bold text-lg">💰 THỰC NHẬN</span>
                                    <span class="sensitive-label">
                                        <span
                                            class="toggle-content hidden-content text-2xl font-bold text-green-600 dark:text-green-400"
                                            data-sensitive="thuc_nhan_final">
                                            {{ number_format($thucNhan, 0, ',', '.') }} ₫
                                        </span>
                                        <button onclick="toggleSensitive(this, 'thuc_nhan_final')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    = {{ number_format($tongThuNhap, 0, ',', '.') }}
                                    - {{ number_format($tongBaoHiem, 0, ',', '.') }}
                                    - {{ number_format($thueTncn, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            @endif

            {{-- ========================================================== --}}
            {{-- TAB 6: ĐÀO TẠO & KỶ LUẬT --}}
            {{-- ========================================================== --}}
            @if ($canViewTab6)
                <div id="tab6" class="tab-content hidden">

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                        {{-- ĐÀO TẠO --}}
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
                                                    <span
                                                        class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-full">📜
                                                        Có chứng chỉ</span>
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                📅 {{ $item->ngay_bat_dau->format('d/m/Y') }} →
                                                {{ $item->ngay_ket_thuc ? $item->ngay_ket_thuc->format('d/m/Y') : 'Đang học' }}
                                            </div>
                                            @if ($item->ket_qua)
                                                <div class="text-sm text-green-600 dark:text-green-400 mt-1">✅ Kết quả:
                                                    {{ $item->ket_qua }}</div>
                                            @endif
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

                        {{-- KHEN THƯỞNG & KỶ LUẬT --}}
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
                                                <span
                                                    class="text-sm text-gray-500 dark:text-gray-400">{{ $item->ngay->format('d/m/Y') }}</span>
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
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có khen thưởng hoặc kỷ luật
                                    </p>
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
                                    {{ $hoSo->khen_thuong_ky_luat?->where('loai', 'khen_thuong')->count() ?? 0 }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Khen thưởng</div>
                            </div>
                            <div
                                class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 text-center border border-red-200 dark:border-red-800">
                                <div class="text-3xl font-bold text-red-600 dark:text-red-400">
                                    {{ $hoSo->khen_thuong_ky_luat?->where('loai', 'ky_luat')->count() ?? 0 }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Kỷ luật</div>
                            </div>
                            <div
                                class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 text-center border border-purple-200 dark:border-purple-800">
                                <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                                    {{ $hoSo->nguoiPhuThuoc?->count() ?? 0 }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Người phụ thuộc</div>
                            </div>
                        </div>
                    </div>

                </div>
            @endif

            {{-- ========================================================== --}}
            {{-- ⭐ TAB 7: LỊCH SỬ ĐƠN TỪ --}}
            {{-- ========================================================== --}}
            @if ($canViewTab7)
                <div id="tab7" class="tab-content hidden">

                    {{-- THỐNG KÊ ĐƠN TỪ --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
                        <div
                            class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800 text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ $thongKeDonTu['tong_don_nghi'] ?? 0 }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">📋 Tổng đơn nghỉ</p>
                        </div>
                        <div
                            class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-4 border border-yellow-200 dark:border-yellow-800 text-center">
                            <p class="text-2xl font-bold text-yellow-600">{{ $thongKeDonTu['don_nghi_cho_duyet'] ?? 0 }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">⏳ Chờ duyệt</p>
                        </div>
                        <div
                            class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 border border-green-200 dark:border-green-800 text-center">
                            <p class="text-2xl font-bold text-green-600">{{ $thongKeDonTu['don_nghi_da_duyet'] ?? 0 }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">✅ Đã duyệt</p>
                        </div>
                        <div
                            class="bg-red-50 dark:bg-red-900/20 rounded-xl p-4 border border-red-200 dark:border-red-800 text-center">
                            <p class="text-2xl font-bold text-red-600">{{ $thongKeDonTu['don_nghi_tu_choi'] ?? 0 }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">❌ Từ chối</p>
                        </div>
                        <div
                            class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4 border border-purple-200 dark:border-purple-800 text-center">
                            <p class="text-2xl font-bold text-purple-600">{{ $thongKeDonTu['tong_tang_ca'] ?? 0 }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">⏰ Tổng tăng ca</p>
                        </div>
                        <div
                            class="bg-orange-50 dark:bg-orange-900/20 rounded-xl p-4 border border-orange-200 dark:border-orange-800 text-center">
                            <p class="text-2xl font-bold text-orange-600">{{ $thongKeDonTu['tong_ve_som'] ?? 0 }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">🏠 Tổng về sớm</p>
                        </div>
                    </div>

                    {{-- LỊCH SỬ NGHỈ PHÉP --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 mb-6">
                        <div
                            class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                📋 Lịch sử nghỉ phép
                            </h3>
                            <span class="text-xs text-gray-400">Tổng: {{ $lichSuNghiPhep->total() }} đơn</span>
                        </div>

                        @if ($lichSuNghiPhep && $lichSuNghiPhep->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr
                                            class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                            <th class="text-left p-2 font-semibold text-xs">Ngày tạo</th>
                                            <th class="text-left p-2 font-semibold text-xs">Loại</th>
                                            <th class="text-left p-2 font-semibold text-xs">Từ ngày</th>
                                            <th class="text-left p-2 font-semibold text-xs">Đến ngày</th>
                                            <th class="text-left p-2 font-semibold text-xs">Số ngày</th>
                                            <th class="text-left p-2 font-semibold text-xs">Đã dùng</th>
                                            <th class="text-left p-2 font-semibold text-xs">Còn lại</th>
                                            <th class="text-left p-2 font-semibold text-xs">Lý do</th>
                                            <th class="text-left p-2 font-semibold text-xs">Trạng thái</th>
                                            <th class="text-left p-2 font-semibold text-xs">Người duyệt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // Lấy tổng phép năm
                                            $tongPhepNam = $soDuPhep->phep_nam_moi ?? 12;
                                            $tongDaDung = 0;
                                        @endphp
                                        @foreach ($lichSuNghiPhep as $item)
                                            @php
                                                // Chỉ tính số ngày đã dùng nếu đơn được duyệt
                                                $soNgayDaDung =
                                                    $item->trang_thai == 'da_duyet' ? $item->so_ngay_nghi : 0;
                                                $tongDaDung += $soNgayDaDung;
                                                $conLai = max(0, $tongPhepNam - $tongDaDung);

                                                $statusColors = [
                                                    'cho_duyet' =>
                                                        'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300',
                                                    'da_duyet' =>
                                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
                                                    'tu_choi' =>
                                                        'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
                                                    'huy_bo' =>
                                                        'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400',
                                                ];
                                                $statusTexts = [
                                                    'cho_duyet' => '⏳ Chờ duyệt',
                                                    'da_duyet' => '✅ Đã duyệt',
                                                    'tu_choi' => '❌ Từ chối',
                                                    'huy_bo' => '🗑️ Đã hủy',
                                                ];
                                            @endphp
                                            <tr
                                                class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                                <td class="p-2 text-xs">
                                                    {{ $item->created_at ? $item->created_at->format('d/m/Y') : '---' }}
                                                    <br><span
                                                        class="text-gray-400 text-[10px]">{{ $item->created_at ? $item->created_at->format('H:i') : '' }}</span>
                                                </td>
                                                <td class="p-2 text-xs">
                                                    <span
                                                        class="px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                                        {{ $item->loaiNghiPhep->ten ?? $item->loai_nghi_phep_id }}
                                                    </span>
                                                </td>
                                                <td class="p-2 text-xs">
                                                    {{ $item->ngay_bat_dau ? $item->ngay_bat_dau->format('d/m/Y') : '---' }}
                                                </td>
                                                <td class="p-2 text-xs">
                                                    {{ $item->ngay_ket_thuc ? $item->ngay_ket_thuc->format('d/m/Y') : '---' }}
                                                </td>
                                                <td class="p-2 text-xs text-center font-medium">
                                                    {{ $item->so_ngay_nghi }}
                                                </td>
                                                <td class="p-2 text-xs text-center font-medium text-orange-600">
                                                    {{ number_format($soNgayDaDung, 1) }}
                                                </td>
                                                <td
                                                    class="p-2 text-xs text-center font-bold {{ $conLai <= 0 ? 'text-red-600' : 'text-green-600' }}">
                                                    {{ number_format($conLai, 1) }}
                                                </td>
                                                <td class="p-2 text-xs max-w-[150px] truncate"
                                                    title="{{ $item->ly_do }}">{{ $item->ly_do }}</td>
                                                <td class="p-2 text-xs">
                                                    <span
                                                        class="px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$item->trang_thai] ?? 'bg-gray-100 text-gray-700' }}">
                                                        {{ $statusTexts[$item->trang_thai] ?? $item->trang_thai }}
                                                    </span>
                                                </td>
                                                <td class="p-2 text-xs">
                                                    @if ($item->nguoiDuyet)
                                                        {{ $item->nguoiDuyet->hoSo->ho ?? '' }}
                                                        {{ $item->nguoiDuyet->hoSo->ten ?? '' }}
                                                        <br><span
                                                            class="text-gray-400 text-[10px]">{{ $item->thoi_gian_duyet ? $item->thoi_gian_duyet->format('d/m/Y H:i') : '' }}</span>
                                                    @else
                                                        <span class="text-gray-400">---</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- ⭐ PHÂN TRANG CHO LỊCH SỬ NGHỈ PHÉP --}}
                            @if ($lichSuNghiPhep->hasPages())
                                <div class="mt-4 flex justify-between items-center">
                                    <div class="text-xs text-gray-500">
                                        Hiển thị {{ $lichSuNghiPhep->firstItem() }} - {{ $lichSuNghiPhep->lastItem() }}
                                        / {{ $lichSuNghiPhep->total() }} đơn
                                    </div>
                                    <div class="flex gap-1">
                                        {{ $lichSuNghiPhep->appends(['nghi_phep_page' => $lichSuNghiPhep->currentPage()])->links('pagination::tailwind') }}
                                    </div>
                                </div>
                            @endif
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">📭 Chưa có lịch sử nghỉ phép</p>
                        @endif
                    </div>

                    {{-- LỊCH SỬ TĂNG CA --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 mb-6">
                        <div
                            class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                ⏰ Lịch sử tăng ca
                            </h3>
                            <span class="text-xs text-gray-400">Tổng: {{ $lichSuTangCa->total() }} đơn</span>
                        </div>

                        @if ($lichSuTangCa && $lichSuTangCa->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr
                                            class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                            <th class="text-left p-2 font-semibold text-xs">Ngày tạo</th>
                                            <th class="text-left p-2 font-semibold text-xs">Ngày TC</th>
                                            <th class="text-left p-2 font-semibold text-xs">Giờ</th>
                                            <th class="text-left p-2 font-semibold text-xs">Số giờ</th>
                                            <th class="text-left p-2 font-semibold text-xs">Lý do</th>
                                            <th class="text-left p-2 font-semibold text-xs">Trạng thái</th>
                                            <th class="text-left p-2 font-semibold text-xs">Người duyệt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lichSuTangCa as $item)
                                            @php
                                                $statusColors = [
                                                    'cho_duyet' =>
                                                        'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300',
                                                    'da_duyet' =>
                                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
                                                    'tu_choi' =>
                                                        'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
                                                    'huy' =>
                                                        'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400',
                                                ];
                                                $statusTexts = [
                                                    'cho_duyet' => '⏳ Chờ duyệt',
                                                    'da_duyet' => '✅ Đã duyệt',
                                                    'tu_choi' => '❌ Từ chối',
                                                    'huy' => '🗑️ Đã hủy',
                                                ];
                                            @endphp
                                            <tr
                                                class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                                <td class="p-2 text-xs">
                                                    {{ $item->created_at ? $item->created_at->format('d/m/Y') : '---' }}
                                                    <br><span
                                                        class="text-gray-400 text-[10px]">{{ $item->created_at ? $item->created_at->format('H:i') : '' }}</span>
                                                </td>
                                                <td class="p-2 text-xs">
                                                    {{ $item->ngay_tang_ca ? $item->ngay_tang_ca->format('d/m/Y') : '---' }}
                                                </td>
                                                <td class="p-2 text-xs">{{ $item->gio_bat_dau }} -
                                                    {{ $item->gio_ket_thuc }}</td>
                                                <td class="p-2 text-xs text-center font-medium">
                                                    {{ $item->so_gio_tang_ca }}h</td>
                                                <td class="p-2 text-xs max-w-[150px] truncate"
                                                    title="{{ $item->ly_do_tang_ca }}">{{ $item->ly_do_tang_ca }}</td>
                                                <td class="p-2 text-xs">
                                                    <span
                                                        class="px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$item->trang_thai] ?? 'bg-gray-100 text-gray-700' }}">
                                                        {{ $statusTexts[$item->trang_thai] ?? $item->trang_thai }}
                                                    </span>
                                                </td>
                                                <td class="p-2 text-xs">
                                                    @if ($item->nguoiDuyet)
                                                        {{ $item->nguoiDuyet->hoSo->ho ?? '' }}
                                                        {{ $item->nguoiDuyet->hoSo->ten ?? '' }}
                                                        <br><span
                                                            class="text-gray-400 text-[10px]">{{ $item->thoi_gian_duyet ? $item->thoi_gian_duyet->format('d/m/Y H:i') : '' }}</span>
                                                    @else
                                                        <span class="text-gray-400">---</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- ⭐ PHÂN TRANG CHO LỊCH SỬ TĂNG CA --}}
                            @if ($lichSuTangCa->hasPages())
                                <div class="mt-4 flex justify-between items-center">
                                    <div class="text-xs text-gray-500">
                                        Hiển thị {{ $lichSuTangCa->firstItem() }} - {{ $lichSuTangCa->lastItem() }} /
                                        {{ $lichSuTangCa->total() }} đơn
                                    </div>
                                    <div class="flex gap-1">
                                        {{ $lichSuTangCa->appends(['tang_ca_page' => $lichSuTangCa->currentPage()])->links('pagination::tailwind') }}
                                    </div>
                                </div>
                            @endif
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">📭 Chưa có lịch sử tăng ca</p>
                        @endif
                    </div>

                    {{-- LỊCH SỬ ĐƠN XIN VỀ SỚM --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                        <div
                            class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                🏠 Lịch sử đơn xin về sớm
                            </h3>
                            <span class="text-xs text-gray-400">Tổng: {{ $lichSuVeSom->total() }} đơn</span>
                        </div>

                        @if ($lichSuVeSom && $lichSuVeSom->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr
                                            class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                            <th class="text-left p-2 font-semibold text-xs">Ngày tạo</th>
                                            <th class="text-left p-2 font-semibold text-xs">Ngày về</th>
                                            <th class="text-left p-2 font-semibold text-xs">Giờ ra dự kiến</th>
                                            <th class="text-left p-2 font-semibold text-xs">Số phút</th>
                                            <th class="text-left p-2 font-semibold text-xs">Lý do</th>
                                            <th class="text-left p-2 font-semibold text-xs">Trạng thái</th>
                                            <th class="text-left p-2 font-semibold text-xs">Người duyệt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lichSuVeSom as $item)
                                            @php
                                                $statusColors = [
                                                    'cho_duyet' =>
                                                        'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300',
                                                    'da_duyet' =>
                                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
                                                    'tu_choi' =>
                                                        'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
                                                ];
                                                $statusTexts = [
                                                    'cho_duyet' => '⏳ Chờ duyệt',
                                                    'da_duyet' => '✅ Đã duyệt',
                                                    'tu_choi' => '❌ Từ chối',
                                                ];
                                            @endphp
                                            <tr
                                                class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                                <td class="p-2 text-xs">
                                                    {{ $item->created_at ? $item->created_at->format('d/m/Y') : '---' }}
                                                    <br><span
                                                        class="text-gray-400 text-[10px]">{{ $item->created_at ? $item->created_at->format('H:i') : '' }}</span>
                                                </td>
                                                <td class="p-2 text-xs">
                                                    {{ $item->ngay ? $item->ngay->format('d/m/Y') : '---' }}
                                                </td>
                                                <td class="p-2 text-xs">{{ $item->gio_ra_du_kien }}</td>
                                                <td class="p-2 text-xs text-center font-medium text-orange-600">
                                                    {{ $item->so_phut_ve_som }}p</td>
                                                <td class="p-2 text-xs max-w-[150px] truncate"
                                                    title="{{ $item->ly_do }}">{{ $item->ly_do }}</td>
                                                <td class="p-2 text-xs">
                                                    <span
                                                        class="px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$item->trang_thai] ?? 'bg-gray-100 text-gray-700' }}">
                                                        {{ $statusTexts[$item->trang_thai] ?? $item->trang_thai }}
                                                    </span>
                                                </td>
                                                <td class="p-2 text-xs">
                                                    @if ($item->nguoiDuyet)
                                                        {{ $item->nguoiDuyet->hoSo->ho ?? '' }}
                                                        {{ $item->nguoiDuyet->hoSo->ten ?? '' }}
                                                        <br><span
                                                            class="text-gray-400 text-[10px]">{{ $item->thoi_gian_duyet ? $item->thoi_gian_duyet->format('d/m/Y H:i') : '' }}</span>
                                                    @else
                                                        <span class="text-gray-400">---</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- ⭐ PHÂN TRANG CHO LỊCH SỬ VỀ SỚM --}}
                            @if ($lichSuVeSom->hasPages())
                                <div class="mt-4 flex justify-between items-center">
                                    <div class="text-xs text-gray-500">
                                        Hiển thị {{ $lichSuVeSom->firstItem() }} - {{ $lichSuVeSom->lastItem() }} /
                                        {{ $lichSuVeSom->total() }} đơn
                                    </div>
                                    <div class="flex gap-1">
                                        {{ $lichSuVeSom->appends(['ve_som_page' => $lichSuVeSom->currentPage()])->links('pagination::tailwind') }}
                                    </div>
                                </div>
                            @endif
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">📭 Chưa có lịch sử đơn xin về sớm</p>
                        @endif
                    </div>

                </div>
            @endif

        </div>

    </div>

    {{-- ============================================================ --}}
    {{-- MODAL XEM TRƯỚC FILE --}}
    {{-- ============================================================ --}}
    <div id="filePreviewModal"
        class="fixed inset-0 z-50 hidden bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-5xl max-h-[95vh] flex flex-col">

            <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 id="filePreviewTitle" class="text-lg font-semibold text-gray-800 dark:text-white">📄 Xem trước tài
                    liệu</h3>
                <button onclick="closeFilePreview()"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 p-4 overflow-auto bg-gray-100 dark:bg-gray-900 min-h-[500px]">
                <div id="filePreviewContent" class="w-full h-full flex items-center justify-center">
                    <div class="text-center text-gray-500 dark:text-gray-400">
                        <div class="text-6xl mb-4 animate-pulse">📄</div>
                        <p>Đang tải tài liệu...</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2 p-4 border-t border-gray-200 dark:border-gray-700">
                <a id="fileDownloadLink" href="#" download
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">⬇️ Tải xuống</a>
                <button onclick="closeFilePreview()"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">Đóng</button>
            </div>

        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- JAVASCRIPT --}}
    {{-- ============================================================ --}}
    <script>
        // =============================================
        // TOGGLE HIỂN THỊ THÔNG TIN NHẠY CẢM
        // =============================================

        function toggleSensitive(btn, type) {
            const elements = document.querySelectorAll(`.toggle-content[data-sensitive="${type}"]`);
            const icon = btn.querySelector('i');

            elements.forEach(el => {
                if (el.classList.contains('hidden-content')) {
                    el.classList.remove('hidden-content');
                    el.classList.add('visible-content');
                    if (icon) {
                        icon.className = 'fas fa-eye';
                        btn.classList.add('active');
                    }
                } else {
                    el.classList.remove('visible-content');
                    el.classList.add('hidden-content');
                    if (icon) {
                        icon.className = 'fas fa-eye-slash';
                        btn.classList.remove('active');
                    }
                }
            });
        }

        // =============================================
        // HIỂN THỊ TẤT CẢ THÔNG TIN NHẠY CẢM
        // =============================================

        let allVisible = false;

        function toggleAllSensitive() {
            allVisible = !allVisible;

            const allSensitive = document.querySelectorAll('.toggle-content');
            const allButtons = document.querySelectorAll('.toggle-btn');
            const icon = document.getElementById('toggleAllIcon');
            const text = document.getElementById('toggleAllText');

            allSensitive.forEach(el => {
                if (allVisible) {
                    el.classList.remove('hidden-content');
                    el.classList.add('visible-content');
                } else {
                    el.classList.remove('visible-content');
                    el.classList.add('hidden-content');
                }
            });

            allButtons.forEach(btn => {
                const btnIcon = btn.querySelector('i');
                if (allVisible) {
                    btn.classList.add('active');
                    if (btnIcon) btnIcon.className = 'fas fa-eye';
                } else {
                    btn.classList.remove('active');
                    if (btnIcon) btnIcon.className = 'fas fa-eye-slash';
                }
            });

            if (icon) {
                icon.className = allVisible ? 'fas fa-eye-slash' : 'fas fa-eye';
            }
            if (text) {
                text.textContent = allVisible ? 'Ẩn tất cả' : 'Hiện tất cả';
            }
        }

        // =============================================
        // TABS
        // =============================================

        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab-btn');
            const contents = document.querySelectorAll('.tab-content');
            const scrollPositions = {};

            function getActiveTabFromUrl() {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get('tab') || sessionStorage.getItem('active_tab') || 'tab1';
            }

            function setActiveTab(tabId) {
                sessionStorage.setItem('active_tab', tabId);
                const url = new URL(window.location.href);
                url.searchParams.set('tab', tabId);
                window.history.replaceState({}, '', url.toString());
            }

            function activateTab(tabId) {
                tabs.forEach(t => {
                    t.classList.remove('active', 'bg-blue-700', 'text-white');
                    t.classList.add('text-gray-600', 'hover:bg-gray-100');
                });

                const activeTab = document.querySelector(`.tab-btn[data-tab="${tabId}"]`);
                if (activeTab) {
                    activeTab.classList.add('active', 'bg-blue-700', 'text-white');
                    activeTab.classList.remove('text-gray-600', 'hover:bg-gray-100');
                }

                contents.forEach(c => c.classList.add('hidden'));

                const target = document.getElementById(tabId);
                if (target) {
                    target.classList.remove('hidden');
                }

                setActiveTab(tabId);
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.dataset.tab;

                    const currentTab = document.querySelector('.tab-btn.active');
                    if (currentTab) {
                        scrollPositions[currentTab.dataset.tab] = window.scrollY;
                    }

                    activateTab(tabId);

                    setTimeout(() => {
                        if (scrollPositions[tabId] !== undefined) {
                            window.scrollTo({
                                top: scrollPositions[tabId],
                                behavior: 'smooth'
                            });
                        } else {
                            const tabNav = document.querySelector('.tab-nav');
                            if (tabNav) {
                                const rect = tabNav.getBoundingClientRect();
                                window.scrollTo({
                                    top: rect.top + window.scrollY - 20,
                                    behavior: 'smooth'
                                });
                            }
                        }
                    }, 100);
                });
            });

            const activeTabId = getActiveTabFromUrl();
            activateTab(activeTabId);

            const savedScroll = sessionStorage.getItem('du_an_scroll_position');
            if (savedScroll !== null) {
                setTimeout(() => {
                    window.scrollTo({
                        top: parseInt(savedScroll),
                        behavior: 'smooth'
                    });
                    sessionStorage.removeItem('du_an_scroll_position');
                }, 300);
            }

            window.addEventListener('beforeunload', function() {
                const activeTab = document.querySelector('.tab-btn.active');
                if (activeTab) {
                    sessionStorage.setItem('active_tab', activeTab.dataset.tab);
                }
            });
        });

        // =============================================
        // MỞ XEM TRƯỚC FILE
        // =============================================

        function openFilePreview(url, title) {
            const modal = document.getElementById('filePreviewModal');
            const content = document.getElementById('filePreviewContent');
            const titleEl = document.getElementById('filePreviewTitle');
            const downloadLink = document.getElementById('fileDownloadLink');

            titleEl.textContent = '📄 ' + title;
            downloadLink.href = url;

            content.innerHTML = `
                <div class="text-center text-gray-500 dark:text-gray-400">
                    <div class="text-6xl mb-4 animate-pulse">📄</div>
                    <p>Đang tải tài liệu...</p>
                </div>
            `;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            if (url.includes('/view-cv') || url.includes('/view-contract')) {
                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        const contentType = response.headers.get('content-type') || '';
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
                        } else if (contentType.includes('image/')) {
                            return response.blob().then(blob => {
                                const blobUrl = URL.createObjectURL(blob);
                                content.innerHTML = `
                                    <div class="flex items-center justify-center w-full h-full">
                                        <img src="${blobUrl}" alt="Xem trước" 
                                            class="max-w-full max-h-[70vh] object-contain rounded-lg shadow-lg">
                                    </div>
                                `;
                            });
                        } else if (contentType.includes('application/vnd.openxmlformats-officedocument') ||
                            contentType.includes('application/msword') ||
                            contentType.includes('application/vnd.ms-excel')) {
                            const viewerUrl =
                                `https://docs.google.com/viewer?embedded=true&url=${encodeURIComponent(url)}`;
                            content.innerHTML = `
                                <iframe src="${viewerUrl}" 
                                    class="w-full h-[600px] border-0 rounded-lg bg-white" 
                                    style="min-height: 600px;">
                                </iframe>
                                <p class="text-xs text-gray-400 text-center mt-2">⚡ Đang sử dụng Google Docs Viewer</p>
                            `;
                        } else {
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
                                    <a href="${url}" download class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">⬇️ Tải xuống</a>
                                    <button onclick="closeFilePreview()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">Đóng</button>
                                </div>
                            </div>
                        `;
                    });
                return;
            }

            const ext = url.split('.').pop().toLowerCase();

            if (ext === 'pdf') {
                content.innerHTML = `
                    <iframe src="${url}#toolbar=0&navpanes=0&scrollbar=0" 
                        class="w-full h-[600px] border-0 rounded-lg bg-white" 
                        style="min-height: 600px;">
                    </iframe>
                `;
            } else if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(ext)) {
                content.innerHTML = `
                    <div class="flex items-center justify-center w-full h-full">
                        <img src="${url}" alt="Xem trước" class="max-w-full max-h-[70vh] object-contain rounded-lg shadow-lg">
                    </div>
                `;
            } else if (['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'].includes(ext)) {
                const viewerUrl = `https://docs.google.com/viewer?embedded=true&url=${encodeURIComponent(url)}`;
                content.innerHTML = `
                    <iframe src="${viewerUrl}" class="w-full h-[600px] border-0 rounded-lg bg-white" style="min-height: 600px;">
                    </iframe>
                    <p class="text-xs text-gray-400 text-center mt-2">⚡ Đang sử dụng Google Docs Viewer</p>
                `;
            } else {
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

        document.addEventListener('click', function(event) {
            const modal = document.getElementById('filePreviewModal');
            if (event.target === modal) {
                closeFilePreview();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeFilePreview();
            }
        });

        function changeDuAnPage(page) {
            const url = new URL(window.location.href);
            url.searchParams.set('du_an_page', page);

            const activeTab = document.querySelector('.tab-btn.active');
            if (activeTab) {
                url.searchParams.set('tab', activeTab.dataset.tab);
                sessionStorage.setItem('active_tab', activeTab.dataset.tab);
            }

            const currentScroll = window.scrollY;
            sessionStorage.setItem('du_an_scroll_position', currentScroll);

            window.location.href = url.toString();
        }

        let cccdVisible = false;

        function toggleCccdImages() {
            cccdVisible = !cccdVisible;

            const images = document.querySelectorAll('.cccd-image');
            const lockIcons = document.querySelectorAll('.cccd-lock-icon');
            const icon = document.getElementById('cccdToggleIcon');
            const text = document.getElementById('cccdToggleText');

            images.forEach(img => {
                if (cccdVisible) {
                    img.classList.remove('blurred');
                    img.classList.add('visible');
                } else {
                    img.classList.remove('visible');
                    img.classList.add('blurred');
                }
            });

            lockIcons.forEach(icon => {
                if (cccdVisible) {
                    icon.style.opacity = '0';
                } else {
                    icon.style.opacity = '1';
                }
            });

            if (icon) {
                icon.className = cccdVisible ? 'fas fa-eye-slash' : 'fas fa-eye';
            }
            if (text) {
                text.textContent = cccdVisible ? 'Ẩn ảnh' : 'Hiện ảnh';
            }
        }

        // =============================================
        // KẾT HỢP VỚI TOGGLE ALL SENSITIVE
        // =============================================

        // Ghi đè hàm toggleAllSensitive để bao gồm cả ảnh CCCD
        const originalToggleAll = window.toggleAllSensitive;
        window.toggleAllSensitive = function() {
            // Gọi hàm gốc
            if (originalToggleAll) {
                originalToggleAll();
            }

            // Đồng bộ trạng thái ảnh CCCD
            const allVisible = document.querySelectorAll('.toggle-content.visible-content').length > 0;
            const images = document.querySelectorAll('.cccd-image');
            const lockIcons = document.querySelectorAll('.cccd-lock-icon');
            const icon = document.getElementById('cccdToggleIcon');
            const text = document.getElementById('cccdToggleText');

            if (allVisible) {
                images.forEach(img => {
                    img.classList.remove('blurred');
                    img.classList.add('visible');
                });
                lockIcons.forEach(icon => icon.style.opacity = '0');
                if (icon) icon.className = 'fas fa-eye-slash';
                if (text) text.textContent = 'Ẩn ảnh';
                cccdVisible = true;
            } else {
                images.forEach(img => {
                    img.classList.remove('visible');
                    img.classList.add('blurred');
                });
                lockIcons.forEach(icon => icon.style.opacity = '1');
                if (icon) icon.className = 'fas fa-eye';
                if (text) text.textContent = 'Hiện ảnh';
                cccdVisible = false;
            }
        };
    </script>

    <style>
        /* ========== CCCD IMAGE BLUR ========== */
        .cccd-image {
            transition: all 0.4s ease;
        }

        .cccd-image.blurred {
            filter: blur(12px);
            -webkit-filter: blur(12px);
            user-select: none;
        }

        .cccd-image.visible {
            filter: blur(0px);
            -webkit-filter: blur(0px);
            user-select: auto;
        }

        .cccd-lock-icon {
            transition: all 0.3s ease;
            opacity: 1;
        }

        .cccd-image.visible+.cccd-lock-icon {
            opacity: 0;
        }

        .cccd-image.blurred:hover+.cccd-lock-icon {
            opacity: 0.8;
        }

        .cccd-image.blurred:hover~.cccd-lock-icon {
            opacity: 0.8;
        }

        .cccd-image.blurred:hover {
            filter: blur(8px);
        }
    </style>

@endsection
