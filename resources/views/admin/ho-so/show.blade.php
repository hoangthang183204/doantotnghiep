@extends('layouts.admin')

@section('title', 'Chi tiết hồ sơ - ' . $hoSo->ho . ' ' . $hoSo->ten)

@section('content')

    {{-- ============================================================ --}}
    {{-- STYLE --}}
    {{-- ============================================================ --}}
    <style>
        /* ========== FONT CUSTOMIZATION ========== */
        body,
        .font-body {
            font-family: 'Segoe UI', 'Inter', system-ui, -apple-system, sans-serif;
        }

        /* Font cho tiêu đề */
        .font-heading {
            font-family: 'Segoe UI', 'Inter', 'Helvetica Neue', sans-serif;
            font-weight: 600;
            letter-spacing: -0.02em;
        }

        /* Font cho số liệu, dữ liệu quan trọng */
        .font-number {
            font-family: 'Inter', 'Segoe UI', 'Roboto', monospace;
            font-weight: 500;
            letter-spacing: 0.01em;
        }

        /* Font cho thông tin nhạy cảm (mã, số tài khoản, CCCD) */
        .font-sensitive {
            font-family: 'JetBrains Mono', 'Fira Code', 'Cascadia Code', 'Consolas', monospace;
            letter-spacing: 0.5px;
        }

        /* ========== TOGGLE HIDE/SHOW ========== */
        .toggle-content {
            transition: all 0.3s ease;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            letter-spacing: 0.3px;
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
            font-family: 'Inter', 'Segoe UI', sans-serif;
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

        /* Font cho nội dung nhạy cảm khi hiển thị */
        .toggle-content.visible-content.font-sensitive {
            font-family: 'JetBrains Mono', 'Fira Code', monospace !important;
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
            font-family: 'Segoe UI', 'Inter', sans-serif;
            font-weight: 500;
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

        /* ========== CARD & TABLE FONTS ========== */
        .card-title {
            font-family: 'Segoe UI', 'Inter', sans-serif;
            font-weight: 600;
            letter-spacing: -0.01em;
        }

        .card-value {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            font-weight: 500;
        }

        .table-header {
            font-family: 'Segoe UI', 'Inter', sans-serif;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table-cell {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            font-size: 0.875rem;
        }

        /* ========== SALARY SPECIFIC ========== */
        .salary-amount {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            font-weight: 700;
            letter-spacing: 0.01em;
        }

        .salary-label {
            font-family: 'Segoe UI', 'Inter', sans-serif;
            font-weight: 500;
            font-size: 0.875rem;
        }

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

        /* ========== BADGE FONTS ========== */
        .badge-text {
            font-family: 'Segoe UI', 'Inter', sans-serif;
            font-weight: 500;
            font-size: 0.75rem;
            letter-spacing: 0.02em;
        }

        /* ========== RESPONSIVE FONT SIZE ========== */
        @media (max-width: 640px) {
            .salary-amount {
                font-size: 1.25rem !important;
            }

            .card-value {
                font-size: 0.875rem !important;
            }
        }
    </style>

    <div class="space-y-6 font-body">

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
                            class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-3xl text-white font-bold font-heading">
                            {{ substr($hoSo->ten ?? 'N', 0, 1) }}{{ substr($hoSo->ho ?? 'N', 0, 1) }}
                        </div>
                    @endif

                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-white font-heading">
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
                                <span
                                    class="text-xs px-3 py-1 bg-green-100 text-green-700 rounded-full font-medium badge-text">
                                    ✅ Đang làm việc
                                </span>
                            @else
                                <span class="text-xs px-3 py-1 bg-red-100 text-red-700 rounded-full font-medium badge-text">
                                    ⛔ Đã nghỉ việc
                                </span>
                            @endif
                            <span class="text-xs px-3 py-1 bg-blue-100 text-blue-700 rounded-full font-medium badge-text">
                                🎯 {{ $hoSo->tham_nien }}
                            </span>
                        </div>
                    </div>

                </div>

                <div class="flex gap-2 flex-wrap">
                    @can('update', $hoSo)
                        <a href="{{ route('admin.ho-so.edit', $hoSo->id) }}"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition font-medium">
                            ✏️ Sửa hồ sơ
                        </a>
                    @endcan
                    <a href="{{ route('admin.ho-so.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition font-medium">
                        ← Quay lại
                    </a>
                </div>

            </div>

        </div>

        {{-- ============================================================ --}}
        {{-- TAB NAVIGATION (7 TABS) --}}
        {{-- ============================================================ --}}
        @php
            $user = auth()->user();
            $userRole = $user->vaiTro->name ?? '';
            $isAdmin = $userRole === 'admin';
            $isHR = $userRole === 'hr';
            $isTruongPhong = $userRole === 'truong_phong';
            $isSelf = $user->id === $hoSo->nguoi_dung_id;

            $canViewSensitive = $isAdmin || $isHR;
            $canViewTab1 = true;
            $canViewTab2 = $isAdmin || $isHR || $isTruongPhong || $isSelf;
            $canViewTab3 = $isAdmin || $isHR || $isTruongPhong || $isSelf;
            $canViewTab4 = $canViewSensitive;
            $canViewTab5 = $canViewSensitive;
            $canViewTab6 = $isAdmin || $isHR || $isTruongPhong || $isSelf;
            $canViewTab7 = $isAdmin || $isHR || $isTruongPhong || $isSelf;
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
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white font-heading">
                                    🧑‍💼 Thông tin cá nhân
                                </h3>
                                @if ($canViewSensitive)
                                    <button onclick="toggleAllSensitive()"
                                        class="text-xs px-3 py-1.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition flex items-center gap-1.5 font-medium">
                                        <i class="fas fa-eye" id="toggleAllIcon"></i>
                                        <span id="toggleAllText">Hiện tất cả</span>
                                    </button>
                                @endif
                            </div>

                            <div class="space-y-3">
                                {{-- Họ và tên --}}
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Họ và tên</span>
                                    <span class="font-medium font-body">{{ $hoSo->ho }} {{ $hoSo->ten }}</span>
                                </div>

                                {{-- Mã nhân viên --}}
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Mã nhân viên</span>
                                    <span class="font-sensitive font-medium">{{ $hoSo->ma_nhan_vien ?? '---' }}</span>
                                </div>

                                {{-- Email --}}
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Email công ty</span>
                                    <span
                                        class="text-blue-600 dark:text-blue-400 font-medium">{{ $hoSo->nguoi_dung->email ?? '---' }}</span>
                                </div>

                                {{-- Số điện thoại (NHẠY CẢM) --}}
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Số điện thoại</span>
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content font-sensitive" data-sensitive="phone">
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
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Ngày sinh</span>
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

                                {{-- Tuổi --}}
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Tuổi</span>
                                    <span class="font-medium">{{ $hoSo->tuoi ?? '---' }} tuổi</span>
                                </div>

                                {{-- Giới tính --}}
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Giới tính</span>
                                    <span class="font-medium">{{ $hoSo->gioi_tinh_text }}</span>
                                </div>

                                {{-- Tình trạng hôn nhân --}}
                                <div class="flex justify-between py-1">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Tình trạng hôn nhân</span>
                                    <span class="font-medium">{{ $hoSo->tinh_trang_hon_nhan_text }}</span>
                                </div>
                            </div>

                        </div>

                        {{-- Cột phải: Địa chỉ & Giấy tờ --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

                            <h3
                                class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4 font-heading">
                                🏠 Địa chỉ & Giấy tờ
                            </h3>

                            <div class="space-y-3">
                                {{-- Địa chỉ hiện tại --}}
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Địa chỉ hiện tại</span>
                                    <span class="font-medium text-right">{{ $hoSo->dia_chi_hien_tai ?? '---' }}</span>
                                </div>

                                {{-- Địa chỉ thường trú --}}
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Địa chỉ thường trú</span>
                                    <span class="font-medium text-right">{{ $hoSo->dia_chi_thuong_tru ?? '---' }}</span>
                                </div>

                                {{-- CMND/CCCD (NHẠY CẢM) --}}
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">CMND/CCCD</span>
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content font-sensitive" data-sensitive="cccd">
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
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Số hộ chiếu</span>
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content font-sensitive"
                                            data-sensitive="passport">
                                            {{ $hoSo->so_ho_chieu ?? '---' }}
                                        </span>
                                        <button onclick="toggleSensitive(this, 'passport')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>

                            {{-- ẢNH CCCD --}}
                            @if ($hoSo->anh_cccd_truoc || $hoSo->anh_cccd_sau)
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 font-heading">🪪
                                            Ảnh CCCD</h4>
                                        <button onclick="toggleCccdImages()"
                                            class="text-xs px-3 py-1.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition flex items-center gap-1.5 font-medium">
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
                                                            class="text-white text-sm font-medium bg-black/50 px-3 py-1 rounded font-body">🔍
                                                            Xem</span>
                                                    </div>
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
                                                            class="text-white text-sm font-medium bg-black/50 px-3 py-1 rounded font-body">🔍
                                                            Xem</span>
                                                    </div>
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
                                            class="text-blue-600 hover:underline font-medium">Chỉnh sửa hồ sơ</a></p>
                                </div>
                            @endif

                        </div>

                    </div>

                    {{-- LIÊN HỆ KHẨN CẤP --}}
                    @if ($canViewSensitive || $isSelf)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mt-6">

                            <h3
                                class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4 font-heading">
                                📞 Liên hệ khẩn cấp
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                {{-- Họ tên LHKC (NHẠY CẢM) --}}
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm font-medium block">Họ tên</span>
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content font-medium text-lg font-body"
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
                                    <span class="text-gray-500 dark:text-gray-400 text-sm font-medium block">Số điện
                                        thoại</span>
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content font-medium text-lg font-body"
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
                                    <span class="text-gray-500 dark:text-gray-400 text-sm font-medium block">Mối quan
                                        hệ</span>
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content font-medium text-lg font-body"
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
                            class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4 font-heading">
                            💼 Thông tin công việc
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div class="space-y-3">
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Chức vụ</span>
                                    <span class="font-medium">{{ $hoSo->nguoi_dung->chuc_vu->ten ?? '---' }}</span>
                                </div>
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Phòng ban</span>
                                    <span
                                        class="font-medium">{{ $hoSo->nguoi_dung->phong_ban->ten_phong_ban ?? '---' }}</span>
                                </div>
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Ngày vào làm</span>
                                    <span class="font-medium">
                                        {{ $hoSo->nguoi_dung->created_at ? $hoSo->nguoi_dung->created_at->format('d/m/Y') : '---' }}
                                    </span>
                                </div>
                                <div class="flex justify-between py-1">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Thâm niên</span>
                                    <span class="font-medium text-green-600">{{ $hoSo->tham_nien }}</span>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Loại hợp đồng</span>
                                    <span class="font-medium">{{ $hopDongHieuLuc->ten_loai_hop_dong ?? '---' }}</span>
                                </div>
                                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Ngày ký HĐ</span>
                                    <span class="font-medium">
                                        {{ isset($hopDongHieuLuc) && $hopDongHieuLuc->ngay_bat_dau
                                            ? \Carbon\Carbon::parse($hopDongHieuLuc->ngay_bat_dau)->format('d/m/Y')
                                            : '---' }}
                                    </span>
                                </div>
                                <div class="flex justify-between py-1">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Ngày hết hạn HĐ</span>
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

                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3 font-heading">📄 Lịch sử hợp
                                đồng lao động
                            </h4>

                            @if ($hoSo->hop_dong && $hoSo->hop_dong->count() > 0)
                                <div class="space-y-3">
                                    @foreach ($hoSo->hop_dong as $item)
                                        @php
                                            $borderColor = 'border-gray-400';
                                            $statusText = 'Không xác định';
                                            $statusColor =
                                                'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300';

                                            if ($item->trang_thai_hop_dong == 'hieu_luc') {
                                                $borderColor = 'border-green-500';
                                                $statusText = '✅ Hiệu lực';
                                                $statusColor =
                                                    'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400';
                                            } elseif ($item->trang_thai_hop_dong == 'chua_hieu_luc') {
                                                $borderColor = 'border-yellow-500';
                                                $statusText = '⏳ Chưa hiệu lực';
                                                $statusColor =
                                                    'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400';
                                            } elseif ($item->trang_thai_hop_dong == 'het_han') {
                                                $borderColor = 'border-red-500';
                                                $statusText = '⏰ Hết hạn';
                                                $statusColor =
                                                    'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';
                                            } elseif ($item->trang_thai_hop_dong == 'huy_bo') {
                                                $borderColor = 'border-red-600';
                                                $statusText = '🚫 Hủy bỏ';
                                                $statusColor =
                                                    'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';
                                            } elseif ($item->trang_thai_hop_dong == 'tao_moi') {
                                                $borderColor = 'border-blue-400';
                                                $statusText = '📝 Tạo mới';
                                                $statusColor =
                                                    'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400';
                                            }

                                            $kyStatus = '';
                                            $kyStatusColor = '';
                                            if ($item->trang_thai_ky == 'da_ky') {
                                                $kyStatus = '✅ Đã ký';
                                                $kyStatusColor = 'text-green-600 dark:text-green-400';
                                            } elseif ($item->trang_thai_ky == 'cho_ky') {
                                                $kyStatus = '⏳ Chờ ký';
                                                $kyStatusColor = 'text-yellow-600 dark:text-yellow-400';
                                            } elseif ($item->trang_thai_ky == 'tu_choi_ky') {
                                                $kyStatus = '❌ Từ chối ký';
                                                $kyStatusColor = 'text-red-600 dark:text-red-400';
                                            }

                                            $filePath = $item->file_hop_dong_da_ky
                                                ? storage_path('app/public/' . $item->file_hop_dong_da_ky)
                                                : null;
                                            $fileExists = $filePath && file_exists($filePath);
                                        @endphp

                                        <div
                                            class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border-l-4 {{ $borderColor }}">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <span
                                                        class="font-medium font-heading">{{ $item->ten_loai_hop_dong ?? $item->loai_hop_dong }}</span>
                                                    <span
                                                        class="text-sm text-gray-500 dark:text-gray-400 ml-2">({{ $item->so_hop_dong }})</span>

                                                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                        📅
                                                        {{ $item->ngay_bat_dau ? $item->ngay_bat_dau->format('d/m/Y') : '---' }}
                                                        →
                                                        {{ $item->ngay_ket_thuc ? $item->ngay_ket_thuc->format('d/m/Y') : '♾️ Không xác định' }}
                                                    </div>

                                                    @if ($kyStatus)
                                                        <div class="text-sm mt-1">
                                                            <span class="font-medium">✍️ Trạng thái ký:</span>
                                                            <span class="{{ $kyStatusColor }}">{{ $kyStatus }}</span>
                                                        </div>
                                                    @endif

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

                                                    @if ($item->thoi_gian_gui)
                                                        <div class="mt-1 text-xs text-gray-400">
                                                            📨 Gửi lúc:
                                                            {{ \Carbon\Carbon::parse($item->thoi_gian_gui)->format('d/m/Y H:i') }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="flex flex-col items-end gap-1">
                                                    <span
                                                        class="text-xs px-2 py-1 {{ $statusColor }} rounded-full whitespace-nowrap ml-2 badge-text">
                                                        {{ $statusText }}
                                                    </span>
                                                </div>
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
                                <p class="text-gray-500 dark:text-gray-400 text-sm font-body">Chưa có hợp đồng lao động</p>
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
                            class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4 font-heading">
                            📄 Hồ sơ năng lực & CV
                        </h3>

                        {{-- FILE CV --}}
                        <div
                            class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-lg p-4 mb-6 border border-blue-200 dark:border-blue-800">

                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="font-medium font-heading">📎 CV đính kèm</span>
                                    @if ($hoSo->cv)
                                        <p class="text-sm text-gray-500 dark:text-gray-400 font-body">
                                            {{ $hoSo->cv->ten_file_goc }}
                                        </p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $hoSo->cv->kich_thuoc }} •
                                            {{ $hoSo->cv->loai_mime }}</p>
                                    @else
                                        <p class="text-sm text-gray-400 font-body">Chưa có CV</p>
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
                                        <span class="text-gray-400 text-sm font-body">Chưa có CV</span>
                                    @endif
                                </div>
                            </div>

                        </div>

                        {{-- KỸ NĂNG CHUYÊN MÔN --}}
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3 font-heading">🛠️ Kỹ năng chuyên
                                môn</h4>

                            @if ($hoSo->ky_nang && $hoSo->ky_nang->count() > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($hoSo->ky_nang as $item)
                                        <span
                                            class="px-3 py-1.5 {{ $item->mau_cap_do }} rounded-full text-sm font-medium shadow-sm font-body">
                                            {{ $item->ten_ky_nang }}
                                            <span class="text-xs opacity-70">({{ $item->cap_do }})</span>
                                        </span>
                                    @endforeach
                                </div>
                                <div class="mt-2 text-xs text-gray-400 font-body">
                                    📌 Tổng: {{ $hoSo->ky_nang->count() }} kỹ năng
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-sm font-body">Chưa có kỹ năng</p>
                            @endif
                        </div>

                        {{-- CHỨNG CHỈ --}}
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3 font-heading">🏅 Chứng chỉ</h4>

                            @if ($hoSo->chung_chi && $hoSo->chung_chi->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($hoSo->chung_chi as $item)
                                        <div
                                            class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 border border-gray-200 dark:border-gray-600 hover:shadow-md transition group">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="font-medium text-gray-800 dark:text-white font-heading">
                                                        {{ $item->ten_chung_chi }}</p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 font-body">🏛️
                                                        {{ $item->to_chuc_cap }}</p>
                                                </div>
                                                <span
                                                    class="text-xs px-2 py-1 {{ $item->mau_trang_thai }} rounded-full badge-text">
                                                    {{ $item->trang_thai_hien_thi }}
                                                </span>
                                            </div>
                                            <div
                                                class="flex justify-between mt-2 text-xs text-gray-500 dark:text-gray-400 font-body">
                                                <span>📅 {{ $item->nam_cap }}</span>
                                                @if ($item->ngay_het_han)
                                                    <span>⏳ Hết hạn: {{ $item->ngay_het_han->format('d/m/Y') }}</span>
                                                @else
                                                    <span>♾️ Không hết hạn</span>
                                                @endif
                                            </div>

                                            {{-- ⭐ FILE ĐÍNH KÈM CHỨNG CHỈ - HIỂN THỊ ICON MẮT VÀ TẢI XUỐNG --}}
                                            @if ($item->file_dinh_kem)
                                                <div class="mt-3 pt-2 border-t border-gray-200 dark:border-gray-600">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center gap-2">
                                                            <span
                                                                class="text-xs text-gray-500 dark:text-gray-400 font-body">📎
                                                                File:</span>
                                                            <span
                                                                class="text-xs text-gray-600 dark:text-gray-300 truncate max-w-[100px] font-body"
                                                                title="{{ basename($item->file_dinh_kem) }}">
                                                                {{ Str::limit(basename($item->file_dinh_kem), 15) }}
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center gap-1">
                                                            {{-- Nút Xem (Con mắt) --}}
                                                            <button
                                                                onclick="openFilePreview('{{ asset('storage/' . $item->file_dinh_kem) }}', 'Chứng chỉ - {{ $item->ten_chung_chi }}')"
                                                                class="p-1.5 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-800 rounded-lg transition hover:scale-110"
                                                                title="Xem chứng chỉ">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
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
                                                            <a href="{{ asset('storage/' . $item->file_dinh_kem) }}"
                                                                download
                                                                class="p-1.5 text-green-600 hover:bg-green-100 dark:hover:bg-green-800 rounded-lg transition hover:scale-110"
                                                                title="Tải xuống chứng chỉ">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-sm font-body">Chưa có chứng chỉ</p>
                            @endif
                        </div>

                        {{-- DỰ ÁN ĐÃ THAM GIA --}}
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">

                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3 font-heading">🚀 Dự án đã tham
                                gia</h4>

                            @php
                                // ⭐ Lấy dữ liệu dự án và phân trang bằng LengthAwarePaginator
                                $duAn = $hoSo->du_an ?? collect();
                                $duAnPaginated = $duAn->sortByDesc('ngay_bat_dau');

                                // Số lượng dự án mỗi trang
                                $perPage = 3;

                                // Lấy trang hiện tại từ request
                                $currentPage = request()->get('du_an_page', 1);

                                // Tạo paginator
                                $duAnPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                                    $duAnPaginated->forPage($currentPage, $perPage),
                                    $duAnPaginated->count(),
                                    $perPage,
                                    $currentPage,
                                    ['path' => request()->url(), 'query' => request()->query()],
                                );

                                // Lấy items cho trang hiện tại
                                $duAnItems = $duAnPaginator->items();
                                $totalDuAn = $duAnPaginator->total();
                                $totalPages = $duAnPaginator->lastPage();
                            @endphp

                            @if ($duAn && $duAn->count() > 0)
                                <div class="space-y-3" id="duAnContainer">
                                    @foreach ($duAnItems as $item)
                                        <div
                                            class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border-l-4 {{ $item->mau_border }} hover:shadow-md transition">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <span
                                                        class="font-medium text-gray-800 dark:text-white font-heading">{{ $item->ten_du_an }}</span>
                                                    <span
                                                        class="text-sm text-gray-500 dark:text-gray-400 ml-2 font-body">({{ $item->vai_tro }})</span>
                                                </div>
                                                <span
                                                    class="text-xs px-2 py-1 {{ $item->mau_trang_thai }} rounded-full badge-text">
                                                    {{ $item->icon_trang_thai }} {{ $item->trang_thai }}
                                                </span>
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-body">
                                                📅 {{ $item->ngay_bat_dau->format('d/m/Y') }} →
                                                {{ $item->ngay_ket_thuc ? $item->ngay_ket_thuc->format('d/m/Y') : 'Đang thực hiện' }}
                                            </div>
                                            @if ($item->mo_ta)
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 italic font-body">
                                                    "{{ $item->mo_ta }}"
                                                </p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                {{-- ⭐ PHÂN TRANG --}}
                                @if ($totalPages > 1)
                                    <div
                                        class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <span class="text-sm text-gray-500 dark:text-gray-400 font-body">
                                            Hiển thị {{ $duAnPaginator->firstItem() }} - {{ $duAnPaginator->lastItem() }}
                                            / {{ $totalDuAn }} dự án
                                        </span>
                                        <div class="flex gap-1">
                                            {{-- Nút Previous --}}
                                            @if ($duAnPaginator->onFirstPage())
                                                <button disabled
                                                    class="px-3 py-1.5 text-sm bg-gray-100 dark:bg-gray-800 text-gray-400 rounded-lg cursor-not-allowed font-body">
                                                    ←
                                                </button>
                                            @else
                                                <button onclick="changeDuAnPage({{ $currentPage - 1 }})"
                                                    class="px-3 py-1.5 text-sm bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-lg transition font-body">
                                                    ←
                                                </button>
                                            @endif

                                            {{-- Các số trang --}}
                                            @php
                                                $start = max(1, $currentPage - 2);
                                                $end = min($totalPages, $currentPage + 2);
                                            @endphp

                                            @if ($start > 1)
                                                <button onclick="changeDuAnPage(1)"
                                                    class="px-3 py-1.5 text-sm rounded-lg transition font-body bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                                                    1
                                                </button>
                                                @if ($start > 2)
                                                    <span class="px-2 py-1.5 text-sm text-gray-400 font-body">...</span>
                                                @endif
                                            @endif

                                            @for ($i = $start; $i <= $end; $i++)
                                                <button onclick="changeDuAnPage({{ $i }})"
                                                    class="px-3 py-1.5 text-sm rounded-lg transition font-body
                            {{ $i == $currentPage ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
                                                    {{ $i }}
                                                </button>
                                            @endfor

                                            @if ($end < $totalPages)
                                                @if ($end < $totalPages - 1)
                                                    <span class="px-2 py-1.5 text-sm text-gray-400 font-body">...</span>
                                                @endif
                                                <button onclick="changeDuAnPage({{ $totalPages }})"
                                                    class="px-3 py-1.5 text-sm rounded-lg transition font-body bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                                                    {{ $totalPages }}
                                                </button>
                                            @endif

                                            {{-- Nút Next --}}
                                            @if ($duAnPaginator->hasMorePages())
                                                <button onclick="changeDuAnPage({{ $currentPage + 1 }})"
                                                    class="px-3 py-1.5 text-sm bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-lg transition font-body">
                                                    →
                                                </button>
                                            @else
                                                <button disabled
                                                    class="px-3 py-1.5 text-sm bg-gray-100 dark:bg-gray-800 text-gray-400 rounded-lg cursor-not-allowed font-body">
                                                    →
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <div class="mt-2 text-xs text-gray-400 font-body">
                                    📌 Tổng: {{ $totalDuAn }} dự án đã tham gia
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-sm font-body">Chưa có dự án</p>
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
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white font-heading">
                                💰 Thông tin lương thưởng
                            </h3>
                            <button onclick="toggleAllSensitive()"
                                class="text-xs px-3 py-1.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition flex items-center gap-1.5 font-medium">
                                <i class="fas fa-eye" id="toggleAllIconTab4"></i>
                                <span id="toggleAllTextTab4">Hiện tất cả</span>
                            </button>
                        </div>

                        {{-- 🏦 THÔNG TIN NGÂN HÀNG --}}
                        <div
                            class="bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 border-2 border-green-200 dark:border-green-800 rounded-xl p-5 mb-6 shadow-sm">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-2xl">🏦</span>
                                <h4 class="text-lg font-bold text-gray-800 dark:text-white font-heading">Thông tin nhận
                                    lương</h4>
                                <span
                                    class="ml-auto text-xs px-3 py-1 bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200 rounded-full font-medium badge-text">Chi
                                    trả hàng tháng</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm border border-gray-200 dark:border-gray-700">
                                    <span
                                        class="text-xs text-gray-500 dark:text-gray-400 uppercase block font-medium font-heading">Chủ
                                        tài khoản</span>
                                    <span class="sensitive-label">
                                        <span
                                            class="toggle-content hidden-content font-semibold text-gray-800 dark:text-white text-lg font-body"
                                            data-sensitive="bank_owner">
                                            {{ $hoSo->chu_tai_khoan ?? 'Chưa cập nhật' }}
                                        </span>
                                        <button onclick="toggleSensitive(this, 'bank_owner')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>

                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm border border-gray-200 dark:border-gray-700">
                                    <span
                                        class="text-xs text-gray-500 dark:text-gray-400 uppercase block font-medium font-heading">Số
                                        tài khoản</span>
                                    <span class="sensitive-label">
                                        <span
                                            class="toggle-content hidden-content font-sensitive font-bold text-gray-800 dark:text-white text-lg"
                                            data-sensitive="bank_account">
                                            {{ $hoSo->so_tai_khoan ?? 'Chưa cập nhật' }}
                                        </span>
                                        <button onclick="toggleSensitive(this, 'bank_account')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>

                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm border border-gray-200 dark:border-gray-700">
                                    <span
                                        class="text-xs text-gray-500 dark:text-gray-400 uppercase block font-medium font-heading">Ngân
                                        hàng</span>
                                    <p class="font-semibold text-gray-800 dark:text-white text-lg font-body">
                                        {{ $hoSo->ten_ngan_hang ?? 'Chưa cập nhật' }}
                                    </p>
                                </div>

                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm border border-gray-200 dark:border-gray-700">
                                    <span
                                        class="text-xs text-gray-500 dark:text-gray-400 uppercase block font-medium font-heading">Chi
                                        nhánh / PGD</span>
                                    <p class="font-semibold text-gray-800 dark:text-white text-lg font-body">
                                        {{ $hoSo->chi_nhanh_ngan_hang ?? 'Chưa cập nhật' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- ⭐ LƯƠNG THÁNG HIỆN TẠI --}}
                        @php
                            // Sử dụng dữ liệu đã được truyền từ Controller
                            $luongCoBanHienTai = $luongGanNhat->luong_co_ban ?? ($hopDongHieuLuc->luong_co_ban ?? 0);
                            $tongPhuCap = $luongGanNhat->tong_phu_cap ?? 0;
                            $tienTangCa = $luongGanNhat->tien_tang_ca ?? 0;
                            $tongBaoHiem = $luongGanNhat->tong_bao_hiem ?? 0;
                            $thueTncn = $luongGanNhat->thue_thu_nhap_ca_nhan ?? 0;
                            $tongThuNhap = $luongGanNhat->tong_luong ?? $luongCoBanHienTai + $tongPhuCap;
                            $thucNhan = $luongGanNhat->luong_thuc_nhan ?? $tongThuNhap;
                            $soNgayCong = $luongGanNhat->so_ngay_cong ?? 0;
                            $soNgayCongChuan = $luongGanNhat->so_ngay_cong_chuan ?? 26;
                            $soNguoiPhuThuoc = $luongGanNhat->so_nguoi_phu_thuoc ?? 0;
                            $bhxh = $luongGanNhat->bhxh ?? 0;
                            $bhyt = $luongGanNhat->bhyt ?? 0;
                            $bhtn = $luongGanNhat->bhtn ?? 0;

                            $coPhuCap = $tongPhuCap > 0;
                            $coTangCa = $tienTangCa > 0;
                            $kyLuu = $bangLuongGanNhat
                                ? 'Tháng ' . $bangLuongGanNhat->thang . '/' . $bangLuongGanNhat->nam
                                : 'Chưa có bảng lương';

                            // ⭐ Lấy lịch sử lương
                            $lichSuLuong = \App\Models\LuongNhanVien::where('nguoi_dung_id', $hoSo->nguoi_dung_id)
                                ->join('bang_luong', 'luong_nhan_vien.bang_luong_id', '=', 'bang_luong.id')
                                ->where('bang_luong.trang_thai', 'da_chot')
                                ->orderBy('bang_luong.nam', 'desc')
                                ->orderBy('bang_luong.thang', 'desc')
                                ->limit(12)
                                ->select('luong_nhan_vien.*')
                                ->get();
                        @endphp

                        {{-- 3 THẺ LƯƠNG --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div
                                class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center border border-gray-200 dark:border-gray-600">
                                <p class="text-sm text-gray-500 dark:text-gray-400 salary-label">📋 Lương cơ bản</p>
                                <span class="sensitive-label">
                                    <span
                                        class="toggle-content hidden-content text-lg font-bold text-blue-600 dark:text-blue-400 salary-amount"
                                        data-sensitive="salary_basic">
                                        {{ number_format($luongCoBanHienTai, 0, ',', '.') }} ₫
                                    </span>
                                    <button onclick="toggleSensitive(this, 'salary_basic')" class="toggle-btn"
                                        title="Nhấn để xem">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </span>
                                @if ($hopDongHieuLuc)
                                    <p class="text-xs text-gray-400 font-body">📄 {{ $hopDongHieuLuc->so_hop_dong }}</p>
                                @endif
                                @if ($soNgayCong > 0)
                                    <p class="text-xs text-gray-400 font-body">📅 Công:
                                        {{ $soNgayCong }}/{{ $soNgayCongChuan }}</p>
                                @endif
                            </div>

                            <div
                                class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center border border-gray-200 dark:border-gray-600">
                                <p class="text-sm text-gray-500 dark:text-gray-400 salary-label">📊 Tổng thu nhập</p>
                                <span class="sensitive-label">
                                    <span
                                        class="toggle-content hidden-content text-lg font-bold text-green-600 dark:text-green-400 salary-amount"
                                        data-sensitive="salary_total">
                                        {{ number_format($tongThuNhap, 0, ',', '.') }} ₫
                                    </span>
                                    <button onclick="toggleSensitive(this, 'salary_total')" class="toggle-btn"
                                        title="Nhấn để xem">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </span>
                                <p class="text-xs text-gray-400 font-body">
                                    {{ $kyLuu }}
                                </p>
                            </div>

                            <div
                                class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center border border-gray-200 dark:border-gray-600">
                                <p class="text-sm text-gray-500 dark:text-gray-400 salary-label">💰 Thực nhận</p>
                                <span class="sensitive-label">
                                    <span
                                        class="toggle-content hidden-content text-lg font-bold text-indigo-600 dark:text-indigo-400 salary-amount"
                                        data-sensitive="salary_net">
                                        {{ number_format($thucNhan, 0, ',', '.') }} ₫
                                    </span>
                                    <button onclick="toggleSensitive(this, 'salary_net')" class="toggle-btn"
                                        title="Nhấn để xem">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </span>
                                <p class="text-xs text-gray-400 font-body">
                                    = {{ number_format($tongThuNhap, 0, ',', '.') }}
                                    @if ($tongBaoHiem > 0)
                                        - {{ number_format($tongBaoHiem, 0, ',', '.') }}
                                    @endif
                                    @if ($thueTncn > 0)
                                        - {{ number_format($thueTncn, 0, ',', '.') }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- CHI TIẾT BẢO HIỂM --}}
                        <div
                            class="mb-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3 font-heading">🛡️ Bảo hiểm
                                (10.5%)</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-2 text-center border border-gray-200 dark:border-gray-600">
                                    <p class="text-xs text-gray-500 font-medium">BHXH (8%)</p>
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content font-bold text-blue-600 salary-amount"
                                            data-sensitive="bhxh">
                                            {{ number_format($bhxh, 0, ',', '.') }} ₫
                                        </span>
                                        <button onclick="toggleSensitive(this, 'bhxh')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>

                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-2 text-center border border-gray-200 dark:border-gray-600">
                                    <p class="text-xs text-gray-500 font-medium">BHYT (1.5%)</p>
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content font-bold text-blue-600 salary-amount"
                                            data-sensitive="bhyt">
                                            {{ number_format($bhyt, 0, ',', '.') }} ₫
                                        </span>
                                        <button onclick="toggleSensitive(this, 'bhyt')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>

                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-2 text-center border border-gray-200 dark:border-gray-600">
                                    <p class="text-xs text-gray-500 font-medium">BHTN (1%)</p>
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content font-bold text-blue-600 salary-amount"
                                            data-sensitive="bhtn">
                                            {{ number_format($bhtn, 0, ',', '.') }} ₫
                                        </span>
                                        <button onclick="toggleSensitive(this, 'bhtn')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>

                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-2 text-center border-2 border-red-200 dark:border-red-700">
                                    <p class="text-xs text-gray-500 font-medium">Tổng</p>
                                    <span class="sensitive-label">
                                        <span class="toggle-content hidden-content font-bold text-red-600 salary-amount"
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
                            <p class="text-xs text-gray-400 mt-2 font-body">📌 Tính trên lương cơ bản:
                                {{ number_format($luongCoBanHienTai, 0, ',', '.') }} ₫</p>
                        </div>

                        {{-- PHỤ CẤP --}}
                        @if ($coPhuCap)
                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2 font-heading">📌 Phụ cấp
                                </h4>
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
                                            class="px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 rounded-full text-sm border border-blue-200 dark:border-blue-800 font-body">
                                            {{ $pc->ten }}:
                                            <span class="sensitive-label">
                                                <span class="toggle-content hidden-content salary-amount"
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
                                    <span
                                        class="px-3 py-1.5 bg-green-50 dark:bg-green-900/20 rounded-full text-sm border border-green-200 dark:border-green-800 font-body">
                                        <strong>Tổng phụ cấp:</strong>
                                        <span class="sensitive-label">
                                            <span class="toggle-content hidden-content text-green-600 salary-amount"
                                                data-sensitive="tong_phu_cap">
                                                <strong>{{ number_format($tongPhuCap, 0, ',', '.') }} ₫</strong>
                                            </span>
                                            <button onclick="toggleSensitive(this, 'tong_phu_cap')" class="toggle-btn"
                                                title="Nhấn để xem">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @endif

                        {{-- TĂNG CA --}}
                        @if ($coTangCa)
                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2 font-heading">⏰ Tăng ca</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="px-3 py-1.5 bg-yellow-50 dark:bg-yellow-900/20 rounded-full text-sm border border-yellow-200 dark:border-yellow-800 font-body">
                                        Tiền tăng ca:
                                        <span class="sensitive-label">
                                            <span
                                                class="toggle-content hidden-content text-yellow-600 dark:text-yellow-400 salary-amount"
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
                        @if ($tongBaoHiem > 0 || $thueTncn > 0)
                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2 font-heading">📌 Khấu trừ
                                </h4>
                                <div class="flex flex-wrap gap-2">
                                    @if ($bhxh > 0)
                                        <span
                                            class="px-3 py-1.5 bg-red-50 dark:bg-red-900/20 rounded-full text-sm border border-red-200 dark:border-red-800 font-body">
                                            BHXH:
                                            <span class="sensitive-label">
                                                <span class="toggle-content hidden-content text-red-600 salary-amount"
                                                    data-sensitive="khau_tru_bhxh">
                                                    <strong>-{{ number_format($bhxh, 0, ',', '.') }} ₫</strong>
                                                </span>
                                                <button onclick="toggleSensitive(this, 'khau_tru_bhxh')"
                                                    class="toggle-btn" title="Nhấn để xem">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            </span>
                                        </span>
                                    @endif
                                    @if ($bhyt > 0)
                                        <span
                                            class="px-3 py-1.5 bg-red-50 dark:bg-red-900/20 rounded-full text-sm border border-red-200 dark:border-red-800 font-body">
                                            BHYT:
                                            <span class="sensitive-label">
                                                <span class="toggle-content hidden-content text-red-600 salary-amount"
                                                    data-sensitive="khau_tru_bhyt">
                                                    <strong>-{{ number_format($bhyt, 0, ',', '.') }} ₫</strong>
                                                </span>
                                                <button onclick="toggleSensitive(this, 'khau_tru_bhyt')"
                                                    class="toggle-btn" title="Nhấn để xem">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            </span>
                                        </span>
                                    @endif
                                    @if ($bhtn > 0)
                                        <span
                                            class="px-3 py-1.5 bg-red-50 dark:bg-red-900/20 rounded-full text-sm border border-red-200 dark:border-red-800 font-body">
                                            BHTN:
                                            <span class="sensitive-label">
                                                <span class="toggle-content hidden-content text-red-600 salary-amount"
                                                    data-sensitive="khau_tru_bhtn">
                                                    <strong>-{{ number_format($bhtn, 0, ',', '.') }} ₫</strong>
                                                </span>
                                                <button onclick="toggleSensitive(this, 'khau_tru_bhtn')"
                                                    class="toggle-btn" title="Nhấn để xem">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            </span>
                                        </span>
                                    @endif
                                    @if ($thueTncn > 0)
                                        <span
                                            class="px-3 py-1.5 bg-red-50 dark:bg-red-900/20 rounded-full text-sm border border-red-200 dark:border-red-800 font-body">
                                            Thuế TNCN:
                                            <span class="sensitive-label">
                                                <span class="toggle-content hidden-content text-red-600 salary-amount"
                                                    data-sensitive="khau_tru_thue">
                                                    <strong>-{{ number_format($thueTncn, 0, ',', '.') }} ₫</strong>
                                                </span>
                                                <button onclick="toggleSensitive(this, 'khau_tru_thue')"
                                                    class="toggle-btn" title="Nhấn để xem">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            </span>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- LỊCH SỬ LƯƠNG --}}
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3 font-heading">📈 Lịch sử lương
                            </h4>

                            @php
                                // ⭐ Lấy lịch sử lương - KHÔNG LỌC TRẠNG THÁI (lấy tất cả)
                                $lichSuLuong = \App\Models\LuongNhanVien::where('nguoi_dung_id', $hoSo->nguoi_dung_id)
                                    ->join('bang_luong', 'luong_nhan_vien.bang_luong_id', '=', 'bang_luong.id')
                                    ->orderBy('bang_luong.nam', 'desc')
                                    ->orderBy('bang_luong.thang', 'desc')
                                    ->limit(12)
                                    ->select('luong_nhan_vien.*')
                                    ->get();

                                // Debug: In ra số lượng
                                // \Log::info('Lịch sử lương count:', ['count' => $lichSuLuong->count()]);

                            @endphp

                            @if ($lichSuLuong && $lichSuLuong->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-sm">
                                        <thead>
                                            <tr
                                                class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                                <th class="text-left p-2 font-semibold table-header">Kỳ lương</th>
                                                <th class="text-left p-2 font-semibold table-header">Ngày công</th>
                                                <th class="text-left p-2 font-semibold table-header">Lương CB</th>
                                                <th class="text-left p-2 font-semibold table-header">Phụ cấp</th>
                                                <th class="text-left p-2 font-semibold table-header">Thực nhận</th>
                                                <th class="text-left p-2 font-semibold table-header">Trạng thái</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lichSuLuong as $item)
                                                @php
                                                    $statusMap = [
                                                        'da_chot' => '✅ Đã chốt',
                                                        'da_duyet' => '✅ Đã duyệt',
                                                        'cho_duyet' => '⏳ Chờ duyệt',
                                                        'dang_xu_ly' => '🔄 Đang xử lý',
                                                        'da_tra' => '💰 Đã trả',
                                                    ];
                                                    $statusClass = [
                                                        'da_chot' =>
                                                            'text-green-600 bg-green-100 dark:bg-green-900/30 dark:text-green-400',
                                                        'da_duyet' =>
                                                            'text-green-600 bg-green-100 dark:bg-green-900/30 dark:text-green-400',
                                                        'cho_duyet' =>
                                                            'text-yellow-600 bg-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-400',
                                                        'dang_xu_ly' =>
                                                            'text-blue-600 bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400',
                                                        'da_tra' =>
                                                            'text-purple-600 bg-purple-100 dark:bg-purple-900/30 dark:text-purple-400',
                                                    ];
                                                    $bangLuong = $item->bangLuong;
                                                    $trangThai = $bangLuong->trang_thai ?? '';
                                                @endphp
                                                <tr
                                                    class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                                    <td class="p-2 font-medium table-cell">
                                                        @if ($bangLuong)
                                                            Tháng {{ $bangLuong->thang }}/{{ $bangLuong->nam }}
                                                            <br>
                                                            <span
                                                                class="text-xs text-gray-400 font-sensitive">{{ $bangLuong->ma_bang_luong }}</span>
                                                        @else
                                                            <span class="text-gray-400">Không xác định</span>
                                                        @endif
                                                    </td>
                                                    <td class="p-2 table-cell">
                                                        {{ number_format($item->so_ngay_cong ?? 0, 2) }}</td>
                                                    <td class="p-2 table-cell">
                                                        <span class="sensitive-label">
                                                            <span class="toggle-content hidden-content salary-amount"
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
                                                    <td class="p-2 table-cell">
                                                        <span class="sensitive-label">
                                                            <span class="toggle-content hidden-content salary-amount"
                                                                data-sensitive="ls_phu_cap_{{ $item->id }}">
                                                                {{ number_format($item->tong_phu_cap ?? 0, 0, ',', '.') }}
                                                            </span>
                                                            <button
                                                                onclick="toggleSensitive(this, 'ls_phu_cap_{{ $item->id }}')"
                                                                class="toggle-btn" title="Nhấn để xem">
                                                                <i class="fas fa-eye-slash"></i>
                                                            </button>
                                                        </span>
                                                    </td>
                                                    <td class="p-2 font-bold text-green-600 table-cell">
                                                        <span class="sensitive-label">
                                                            <span class="toggle-content hidden-content salary-amount"
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
                                                    <td class="p-2 table-cell">
                                                        <span
                                                            class="px-2 py-0.5 rounded-full text-xs font-medium badge-text {{ $statusClass[$trangThai] ?? 'bg-gray-100 text-gray-600' }}">
                                                            {{ $statusMap[$trangThai] ?? ($trangThai ?? 'Không xác định') }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3 text-xs text-gray-400 font-body">
                                    📌 Hiển thị {{ $lichSuLuong->count() }} bản ghi lương gần nhất
                                </div>
                            @else
                                {{-- Hiển thị thông báo khi chưa có lịch sử lương --}}
                                <div class="text-center py-8 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="text-4xl mb-2">📭</div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm font-body">Chưa có lịch sử lương</p>
                                    <p class="text-xs text-gray-400 mt-1 font-body">Dữ liệu lương sẽ hiển thị sau khi chốt
                                        lương</p>
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            @endif

            {{-- ========================================================== --}}
            {{-- TAB 5: BẢO HIỂM & THUẾ (CHỈ ADMIN & HR) --}}
            {{-- ========================================================== --}}
            {{-- ========================================================== --}}
            {{-- TAB 5: BẢO HIỂM & THUẾ (CHỈ ADMIN & HR) --}}
            {{-- ========================================================== --}}
            @if ($canViewTab5)
                <div id="tab5" class="tab-content hidden">

                    @php
                        // Sử dụng dữ liệu đã được truyền từ Controller
                        $luongCoBanHienTai = $luongGanNhat->luong_co_ban ?? ($hopDongHieuLuc->luong_co_ban ?? 0);
                        $tongPhuCap = $luongGanNhat->tong_phu_cap ?? 0;
                        $tienTangCa = $luongGanNhat->tien_tang_ca ?? 0;
                        $tongBaoHiem = $luongGanNhat->tong_bao_hiem ?? 0;
                        $thueTncn = $luongGanNhat->thue_thu_nhap_ca_nhan ?? 0;
                        $tongThuNhap = $luongGanNhat->tong_luong ?? $luongCoBanHienTai + $tongPhuCap;
                        $thucNhan = $luongGanNhat->luong_thuc_nhan ?? $tongThuNhap;
                        $bhxh = $luongGanNhat->bhxh ?? 0;
                        $bhyt = $luongGanNhat->bhyt ?? 0;
                        $bhtn = $luongGanNhat->bhtn ?? 0;
                        $soNguoiPhuThuoc = $luongGanNhat->so_nguoi_phu_thuoc ?? ($hoSo->nguoiPhuThuoc?->count() ?? 0);

                        $giamTruBanThan = 15500000;
                        $giamTruGiaCanh = $giamTruBanThan + 6200000 * $soNguoiPhuThuoc;
                        $thuNhapChiuThue = max(0, $tongThuNhap - $tongBaoHiem);
                        $thuNhapTinhThue = max(0, $thuNhapChiuThue - $giamTruGiaCanh);

                        $coPhuCap = $tongPhuCap > 0;
                        $coTangCa = $tienTangCa > 0;
                        $nguoiPhuThuocs = $hoSo->nguoiPhuThuoc ?? collect();
                        $kyLuu = $bangLuongGanNhat
                            ? 'Tháng ' . $bangLuongGanNhat->thang . '/' . $bangLuongGanNhat->nam
                            : 'Chưa có bảng lương';
                    @endphp

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                        {{-- BẢO HIỂM XÃ HỘI --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                            <div
                                class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white font-heading">🛡️ Bảo hiểm
                                    xã hội</h3>
                                <button onclick="toggleAllSensitive()"
                                    class="text-xs px-3 py-1.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition flex items-center gap-1.5 font-medium">
                                    <i class="fas fa-eye"></i>
                                    <span>Hiện tất cả</span>
                                </button>
                            </div>
                            <div class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Số sổ BHXH</span>
                                    <span class="sensitive-label">
                                        <span
                                            class="toggle-content hidden-content font-sensitive font-medium text-gray-800 dark:text-white"
                                            data-sensitive="bhxh_number">
                                            {{ $hoSo->so_bhxh ?? 'Chưa cập nhật' }}
                                        </span>
                                        <button onclick="toggleSensitive(this, 'bhxh_number')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>

                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Mã số thuế TNCN</span>
                                    <span class="sensitive-label">
                                        <span
                                            class="toggle-content hidden-content font-sensitive font-medium text-gray-800 dark:text-white"
                                            data-sensitive="tax_code">
                                            {{ $hoSo->ma_so_thue ?? 'Chưa cập nhật' }}
                                        </span>
                                        <button onclick="toggleSensitive(this, 'tax_code')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>

                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Nơi đăng ký KCB</span>
                                    <span
                                        class="font-medium font-body">{{ $hoSo->noi_dang_ky_kcb ?? 'Chưa cập nhật' }}</span>
                                </div>

                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Mức lương đóng BHXH</span>
                                    <span class="sensitive-label">
                                        <span
                                            class="toggle-content hidden-content font-medium text-green-600 dark:text-green-400 salary-amount"
                                            data-sensitive="bhxh_salary">
                                            {{ number_format($luongCoBanHienTai, 0, ',', '.') }} ₫
                                        </span>
                                        <button onclick="toggleSensitive(this, 'bhxh_salary')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>

                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 mt-2">
                                    <div class="flex justify-between py-1">
                                        <span class="text-gray-500 dark:text-gray-400 font-medium">BHXH (8%)</span>
                                        <span class="sensitive-label">
                                            <span
                                                class="toggle-content hidden-content font-medium text-blue-600 salary-amount"
                                                data-sensitive="bhxh_detail">
                                                {{ number_format($bhxh, 0, ',', '.') }} ₫
                                            </span>
                                            <button onclick="toggleSensitive(this, 'bhxh_detail')" class="toggle-btn"
                                                title="Nhấn để xem">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <div class="flex justify-between py-1 border-t border-gray-200 dark:border-gray-600">
                                        <span class="text-gray-500 dark:text-gray-400 font-medium">BHYT (1.5%)</span>
                                        <span class="sensitive-label">
                                            <span
                                                class="toggle-content hidden-content font-medium text-blue-600 salary-amount"
                                                data-sensitive="bhyt_detail">
                                                {{ number_format($bhyt, 0, ',', '.') }} ₫
                                            </span>
                                            <button onclick="toggleSensitive(this, 'bhyt_detail')" class="toggle-btn"
                                                title="Nhấn để xem">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <div class="flex justify-between py-1 border-t border-gray-200 dark:border-gray-600">
                                        <span class="text-gray-500 dark:text-gray-400 font-medium">BHTN (1%)</span>
                                        <span class="sensitive-label">
                                            <span
                                                class="toggle-content hidden-content font-medium text-blue-600 salary-amount"
                                                data-sensitive="bhtn_detail">
                                                {{ number_format($bhtn, 0, ',', '.') }} ₫
                                            </span>
                                            <button onclick="toggleSensitive(this, 'bhtn_detail')" class="toggle-btn"
                                                title="Nhấn để xem">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <div
                                        class="flex justify-between py-2 border-t-2 border-gray-300 dark:border-gray-500 font-bold mt-1">
                                        <span class="text-gray-700 dark:text-gray-300 font-heading">Tổng đóng
                                            (10.5%)</span>
                                        <span class="sensitive-label">
                                            <span class="toggle-content hidden-content text-red-600 salary-amount"
                                                data-sensitive="tong_bh_detail">
                                                {{ number_format($tongBaoHiem, 0, ',', '.') }} ₫
                                            </span>
                                            <button onclick="toggleSensitive(this, 'tong_bh_detail')" class="toggle-btn"
                                                title="Nhấn để xem">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 font-heading">
                                            👨‍👩‍👧‍👦 Người phụ thuộc
                                            <span
                                                class="text-xs font-normal text-gray-500 font-body">({{ $soNguoiPhuThuoc }}
                                                người)</span>
                                        </h4>
                                        @if ($soNguoiPhuThuoc > 0)
                                            <span
                                                class="text-xs px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full badge-text">
                                                Giảm trừ: {{ number_format(6200000 * $soNguoiPhuThuoc, 0, ',', '.') }}
                                                ₫/tháng
                                            </span>
                                        @endif
                                    </div>

                                    @if ($soNguoiPhuThuoc > 0)
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full text-sm">
                                                <thead>
                                                    <tr
                                                        class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                                        <th class="text-left p-1.5 font-semibold text-xs table-header">Họ
                                                            tên</th>
                                                        <th class="text-left p-1.5 font-semibold text-xs table-header">Ngày
                                                            sinh</th>
                                                        <th class="text-left p-1.5 font-semibold text-xs table-header">Quan
                                                            hệ</th>
                                                        <th class="text-left p-1.5 font-semibold text-xs table-header">Mã
                                                            số thuế</th>
                                                        <th class="text-left p-1.5 font-semibold text-xs table-header">Ngày
                                                            bắt đầu</th>
                                                        <th class="text-left p-1.5 font-semibold text-xs table-header">
                                                            Trạng thái</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($nguoiPhuThuocs as $npt)
                                                        @php
                                                            $isActive =
                                                                is_null($npt->ngay_ket_thuc) ||
                                                                $npt->ngay_ket_thuc >= now();
                                                            $statusColor = $isActive
                                                                ? 'text-green-600 bg-green-100'
                                                                : 'text-red-600 bg-red-100';
                                                            $statusText = $isActive
                                                                ? '✅ Đang áp dụng'
                                                                : '⛔ Đã kết thúc';
                                                        @endphp
                                                        <tr
                                                            class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                                            <td class="p-1.5 text-xs font-medium table-cell">
                                                                {{ $npt->ho_ten }}
                                                            </td>
                                                            <td class="p-1.5 text-xs table-cell">
                                                                {{ $npt->ngay_sinh ? $npt->ngay_sinh->format('d/m/Y') : '---' }}
                                                            </td>
                                                            <td class="p-1.5 text-xs table-cell">
                                                                <span
                                                                    class="px-1.5 py-0.5 rounded text-xs bg-gray-100 text-gray-700 badge-text">
                                                                    {{ $npt->quan_he == 'con' ? '👶 Con' : ($npt->quan_he == 'vo' ? '👩 Vợ' : ($npt->quan_he == 'chong' ? '👨 Chồng' : ($npt->quan_he == 'cha' ? '👨 Cha' : ($npt->quan_he == 'me' ? '👩 Mẹ' : '👤 Khác')))) }}
                                                                </span>
                                                            </td>
                                                            <td class="p-1.5 text-xs font-sensitive">
                                                                {{ $npt->ma_so_thue ?? '---' }}</td>
                                                            <td class="p-1.5 text-xs table-cell">
                                                                {{ $npt->ngay_bat_dau ? $npt->ngay_bat_dau->format('d/m/Y') : '---' }}
                                                            </td>
                                                            <td class="p-1.5 text-xs table-cell">
                                                                <span
                                                                    class="px-2 py-0.5 rounded-full text-xs font-medium badge-text {{ $statusColor }}">
                                                                    {{ $statusText }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mt-2 text-xs text-gray-400 font-body">
                                            📌 Tổng giảm trừ gia cảnh: {{ number_format($giamTruGiaCanh, 0, ',', '.') }}
                                            ₫/tháng
                                            (Bản thân: {{ number_format($giamTruBanThan, 0, ',', '.') }} ₫ +
                                            {{ $soNguoiPhuThuoc }} người phụ thuộc × 6.200.000 ₫)
                                        </div>
                                    @else
                                        <div class="text-center py-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                            <p class="text-gray-500 dark:text-gray-400 text-sm font-body">📭 Chưa có người
                                                phụ thuộc
                                                đăng ký</p>
                                            <p class="text-xs text-gray-400 mt-1 font-body">Thêm người phụ thuộc để được
                                                giảm trừ gia
                                                cảnh</p>
                                        </div>
                                    @endif
                                </div>

                                @if (!$hoSo->so_bhxh && !$hoSo->ma_so_thue)
                                    <div
                                        class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                                        <p
                                            class="text-sm text-yellow-700 dark:text-yellow-300 flex items-center gap-2 font-body">
                                            <span>⚠️</span> Thông tin bảo hiểm chưa được cập nhật.
                                            <a href="{{ route('admin.ho-so.edit', $hoSo->id) }}"
                                                class="text-blue-600 hover:underline font-medium">Cập nhật ngay</a>
                                        </p>
                                    </div>
                                @endif

                                @if ($bangLuongGanNhat)
                                    <div class="text-xs text-gray-400 mt-2 font-body">
                                        📋 Kỳ lương: {{ $kyLuu }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- THUẾ TNCN --}}
                        <div
                            class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-semibold text-gray-700 dark:text-gray-300 font-heading">🏛️ Thuế
                                    TNCN</span>
                                <span class="sensitive-label">
                                    <span
                                        class="toggle-content hidden-content font-bold {{ $thueTncn > 0 ? 'text-red-600' : 'text-green-600' }} text-lg salary-amount"
                                        data-sensitive="thue_tncn_tab5">
                                        {{ number_format($thueTncn, 0, ',', '.') }} ₫
                                    </span>
                                    <button onclick="toggleSensitive(this, 'thue_tncn_tab5')" class="toggle-btn"
                                        title="Nhấn để xem">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </span>
                            </div>

                            <div class="flex justify-between py-1 text-sm border-t border-gray-200 dark:border-gray-600">
                                <span class="text-gray-500 dark:text-gray-400 font-medium">📊 Tổng thu nhập</span>
                                <span class="sensitive-label">
                                    <span
                                        class="toggle-content hidden-content font-medium text-gray-700 dark:text-gray-300 salary-amount"
                                        data-sensitive="tong_thu_nhap_tab5">
                                        {{ number_format($tongThuNhap, 0, ',', '.') }} ₫
                                    </span>
                                    <button onclick="toggleSensitive(this, 'tong_thu_nhap_tab5')" class="toggle-btn"
                                        title="Nhấn để xem">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </span>
                            </div>

                            <div class="pl-4 text-xs text-gray-400 font-body">
                                = {{ number_format($luongCoBanHienTai, 0, ',', '.') }}
                                @if ($coPhuCap)
                                    + {{ number_format($tongPhuCap, 0, ',', '.') }}
                                @endif
                                @if ($coTangCa)
                                    + {{ number_format($tienTangCa, 0, ',', '.') }}
                                @endif
                            </div>

                            <div class="flex justify-between py-1 text-sm border-t border-gray-200 dark:border-gray-600">
                                <span class="text-gray-500 dark:text-gray-400 font-medium">🔻 Bảo hiểm (10.5%)</span>
                                <span class="sensitive-label">
                                    <span class="toggle-content hidden-content font-medium text-red-600 salary-amount"
                                        data-sensitive="bao_hiem_tab5">
                                        -{{ number_format($tongBaoHiem, 0, ',', '.') }} ₫
                                    </span>
                                    <button onclick="toggleSensitive(this, 'bao_hiem_tab5')" class="toggle-btn"
                                        title="Nhấn để xem">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </span>
                            </div>

                            <div class="flex justify-between py-1 text-sm border-t border-gray-200 dark:border-gray-600">
                                <span class="text-gray-500 dark:text-gray-400 font-medium">👨‍👩‍👧‍👦 Giảm trừ gia
                                    cảnh</span>
                                <span class="sensitive-label">
                                    <span class="toggle-content hidden-content font-medium text-blue-600 salary-amount"
                                        data-sensitive="giam_tru_gia_canh">
                                        -{{ number_format($giamTruGiaCanh, 0, ',', '.') }} ₫
                                    </span>
                                    <button onclick="toggleSensitive(this, 'giam_tru_gia_canh')" class="toggle-btn"
                                        title="Nhấn để xem">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </span>
                            </div>
                            <div class="text-xs text-gray-400 pl-4 font-body">
                                Bản thân: {{ number_format($giamTruBanThan, 0, ',', '.') }} ₫
                                @if ($soNguoiPhuThuoc > 0)
                                    + {{ $soNguoiPhuThuoc }} người PT × 6.200.000 ₫
                                @endif
                            </div>

                            <div
                                class="flex justify-between py-1 text-sm border-t border-gray-200 dark:border-gray-600 font-medium">
                                <span class="text-gray-600 dark:text-gray-300 font-medium">📝 Thu nhập chịu thuế</span>
                                <span class="sensitive-label">
                                    <span
                                        class="toggle-content hidden-content font-bold {{ $thuNhapChiuThue > 0 ? 'text-orange-600' : 'text-green-600' }} salary-amount"
                                        data-sensitive="thu_nhap_chiu_thue">
                                        {{ number_format($thuNhapChiuThue, 0, ',', '.') }} ₫
                                    </span>
                                    <button onclick="toggleSensitive(this, 'thu_nhap_chiu_thue')" class="toggle-btn"
                                        title="Nhấn để xem">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </span>
                            </div>
                            <div class="text-xs text-gray-400 pl-4 font-body">
                                = {{ number_format($tongThuNhap, 0, ',', '.') }} -
                                {{ number_format($tongBaoHiem, 0, ',', '.') }}
                            </div>

                            <div class="flex justify-between py-1 text-sm border-t border-gray-200 dark:border-gray-600">
                                <span class="text-gray-500 dark:text-gray-400 font-medium">📊 Thu nhập tính thuế</span>
                                <span class="sensitive-label">
                                    <span
                                        class="toggle-content hidden-content font-medium {{ $thuNhapTinhThue > 0 ? 'text-orange-600' : 'text-green-600' }} salary-amount"
                                        data-sensitive="thu_nhap_tinh_thue">
                                        {{ number_format(max(0, $thuNhapTinhThue), 0, ',', '.') }} ₫
                                    </span>
                                    <button onclick="toggleSensitive(this, 'thu_nhap_tinh_thue')" class="toggle-btn"
                                        title="Nhấn để xem">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </span>
                            </div>
                            <div class="text-xs text-gray-400 pl-4 font-body">
                                = {{ number_format($thuNhapChiuThue, 0, ',', '.') }} -
                                {{ number_format($giamTruGiaCanh, 0, ',', '.') }}
                            </div>

                            <div class="flex justify-between py-2 mt-1 border-t-2 border-blue-300 dark:border-blue-700">
                                <span class="font-semibold text-gray-700 dark:text-gray-300 font-heading">🏛️ Thuế
                                    TNCN</span>
                                <span class="sensitive-label">
                                    <span
                                        class="toggle-content hidden-content font-bold {{ $thueTncn > 0 ? 'text-red-600' : 'text-green-600' }} text-lg salary-amount"
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
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-body">ℹ️ Áp dụng biểu thuế
                                    lũy tiến
                                    từng phần</div>
                            @else
                                <div class="text-xs text-green-600 dark:text-green-400 mt-1 font-body">✅ Không phải nộp
                                    thuế</div>
                            @endif

                            <div
                                class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border-2 border-green-300 dark:border-green-700 mt-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700 dark:text-gray-300 font-bold text-lg font-heading">💰 THỰC
                                        NHẬN</span>
                                    <span class="sensitive-label">
                                        <span
                                            class="toggle-content hidden-content text-2xl font-bold text-green-600 dark:text-green-400 salary-amount"
                                            data-sensitive="thuc_nhan_final">
                                            {{ number_format($thucNhan, 0, ',', '.') }} ₫
                                        </span>
                                        <button onclick="toggleSensitive(this, 'thuc_nhan_final')" class="toggle-btn"
                                            title="Nhấn để xem">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-body">
                                    = {{ number_format($tongThuNhap, 0, ',', '.') }}
                                    - {{ number_format($tongBaoHiem, 0, ',', '.') }}
                                    - {{ number_format($thueTncn, 0, ',', '.') }}
                                </div>
                                @if ($bangLuongGanNhat)
                                    <div class="text-xs text-gray-400 mt-1 font-body">
                                        📋 Kỳ lương: {{ $kyLuu }}
                                    </div>
                                @endif
                            </div>

                            <div
                                class="mt-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 font-heading">📋 Tóm
                                    tắt giảm trừ
                                </p>
                                <div class="grid grid-cols-2 gap-1 text-xs">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500 font-body">Giảm trừ bản thân:</span>
                                        <span
                                            class="font-medium font-body">{{ number_format($giamTruBanThan, 0, ',', '.') }}
                                            ₫</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500 font-body">Người phụ thuộc:</span>
                                        <span class="font-medium font-body">{{ $soNguoiPhuThuoc }} người</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500 font-body">Giảm trừ NPT:</span>
                                        <span
                                            class="font-medium font-body">{{ number_format(6200000 * $soNguoiPhuThuoc, 0, ',', '.') }}
                                            ₫</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500 font-body">Tổng giảm trừ:</span>
                                        <span
                                            class="font-medium text-blue-600 font-body">{{ number_format($giamTruGiaCanh, 0, ',', '.') }}
                                            ₫</span>
                                    </div>
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
                                class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4 font-heading">
                                🎓 Đào tạo đã tham gia
                            </h3>

                            @if ($hoSo->dao_tao && $hoSo->dao_tao->count() > 0)
                                <div class="space-y-3">
                                    @foreach ($hoSo->dao_tao as $item)
                                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 border-l-4 border-blue-500">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <span
                                                        class="font-medium text-gray-800 dark:text-white font-heading">{{ $item->ten_khoa_hoc }}</span>
                                                    <span
                                                        class="text-sm text-gray-500 dark:text-gray-400 ml-2 font-body">({{ $item->to_chuc ?? 'N/A' }})</span>
                                                </div>
                                                @if ($item->co_chung_chi)
                                                    <span
                                                        class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-full badge-text">📜
                                                        Có chứng chỉ</span>
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-body">
                                                📅 {{ $item->ngay_bat_dau->format('d/m/Y') }} →
                                                {{ $item->ngay_ket_thuc ? $item->ngay_ket_thuc->format('d/m/Y') : 'Đang học' }}
                                            </div>
                                            @if ($item->ket_qua)
                                                <div class="text-sm text-green-600 dark:text-green-400 mt-1 font-body">✅
                                                    Kết quả:
                                                    {{ $item->ket_qua }}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="text-4xl mb-2">📚</div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm font-body">Chưa có khóa đào tạo nào
                                    </p>
                                </div>
                            @endif
                        </div>

                        {{-- KHEN THƯỞNG & KỶ LUẬT --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                            <h3
                                class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4 font-heading">
                                ⚖️ Khen thưởng & Kỷ luật
                            </h3>

                            @if ($hoSo->khen_thuong_ky_luat && $hoSo->khen_thuong_ky_luat->count() > 0)
                                <div class="space-y-3">
                                    @foreach ($hoSo->khen_thuong_ky_luat as $item)
                                        <div class="rounded-lg p-3 {{ $item->mau_loai }}">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <span class="font-medium text-gray-800 dark:text-white font-heading">
                                                        {{ $item->loai_text }}: {{ $item->ten }}
                                                    </span>
                                                    @if ($item->so_tien)
                                                        <span
                                                            class="text-sm {{ $item->loai == 'khen_thuong' ? 'text-green-600' : 'text-red-600' }} ml-2 font-body">
                                                            ({{ $item->loai == 'khen_thuong' ? '+' : '-' }}{{ number_format($item->so_tien, 0, ',', '.') }}
                                                            ₫)
                                                        </span>
                                                    @endif
                                                </div>
                                                <span
                                                    class="text-sm text-gray-500 dark:text-gray-400 font-body">{{ $item->ngay->format('d/m/Y') }}</span>
                                            </div>
                                            @if ($item->noi_dung)
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 font-body">
                                                    {{ $item->noi_dung }}</p>
                                            @endif
                                            @if ($item->hinh_thuc)
                                                <span class="text-xs text-gray-500 font-body">📌 Hình thức:
                                                    {{ $item->hinh_thuc }}</span>
                                            @endif
                                            @if ($item->quyet_dinh_so)
                                                <span class="text-xs text-gray-500 ml-2 font-body">• QĐ:
                                                    {{ $item->quyet_dinh_so }}</span>
                                            @endif
                                            @if ($item->nguoiKy)
                                                <div class="text-xs text-gray-400 mt-1 font-body">
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
                                    <p class="text-gray-500 dark:text-gray-400 text-sm font-body">Chưa có khen thưởng hoặc
                                        kỷ luật
                                    </p>
                                </div>
                            @endif
                        </div>

                    </div>

                    {{-- THỐNG KÊ TỔNG HỢP --}}
                    <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h3
                            class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4 font-heading">
                            📊 Tổng hợp
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div
                                class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 text-center border border-blue-200 dark:border-blue-800">
                                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 font-number">
                                    {{ $hoSo->dao_tao?->count() ?? 0 }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 font-body">Khóa đào tạo</div>
                            </div>
                            <div
                                class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 text-center border border-green-200 dark:border-green-800">
                                <div class="text-3xl font-bold text-green-600 dark:text-green-400 font-number">
                                    {{ $hoSo->khen_thuong_ky_luat?->where('loai', 'khen_thuong')->count() ?? 0 }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 font-body">Khen thưởng</div>
                            </div>
                            <div
                                class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 text-center border border-red-200 dark:border-red-800">
                                <div class="text-3xl font-bold text-red-600 dark:text-red-400 font-number">
                                    {{ $hoSo->khen_thuong_ky_luat?->where('loai', 'ky_luat')->count() ?? 0 }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 font-body">Kỷ luật</div>
                            </div>
                            <div
                                class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 text-center border border-purple-200 dark:border-purple-800">
                                <div class="text-3xl font-bold text-purple-600 dark:text-purple-400 font-number">
                                    {{ $hoSo->nguoiPhuThuoc?->count() ?? 0 }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 font-body">Người phụ thuộc</div>
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
                            <p class="text-2xl font-bold text-blue-600 font-number">
                                {{ $thongKeDonTu['tong_don_nghi'] ?? 0 }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-body">📋 Tổng đơn nghỉ</p>
                        </div>
                        <div
                            class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-4 border border-yellow-200 dark:border-yellow-800 text-center">
                            <p class="text-2xl font-bold text-yellow-600 font-number">
                                {{ $thongKeDonTu['don_nghi_cho_duyet'] ?? 0 }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-body">⏳ Chờ duyệt</p>
                        </div>
                        <div
                            class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 border border-green-200 dark:border-green-800 text-center">
                            <p class="text-2xl font-bold text-green-600 font-number">
                                {{ $thongKeDonTu['don_nghi_da_duyet'] ?? 0 }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-body">✅ Đã duyệt</p>
                        </div>
                        <div
                            class="bg-red-50 dark:bg-red-900/20 rounded-xl p-4 border border-red-200 dark:border-red-800 text-center">
                            <p class="text-2xl font-bold text-red-600 font-number">
                                {{ $thongKeDonTu['don_nghi_tu_choi'] ?? 0 }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-body">❌ Từ chối</p>
                        </div>
                        <div
                            class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4 border border-purple-200 dark:border-purple-800 text-center">
                            <p class="text-2xl font-bold text-purple-600 font-number">
                                {{ $thongKeDonTu['tong_tang_ca'] ?? 0 }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-body">⏰ Tổng tăng ca</p>
                        </div>
                        <div
                            class="bg-orange-50 dark:bg-orange-900/20 rounded-xl p-4 border border-orange-200 dark:border-orange-800 text-center">
                            <p class="text-2xl font-bold text-orange-600 font-number">
                                {{ $thongKeDonTu['tong_ve_som'] ?? 0 }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-body">🏠 Tổng về sớm</p>
                        </div>
                    </div>

                    {{-- LỊCH SỬ NGHỈ PHÉP --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 mb-6">
                        <div
                            class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white font-heading">
                                📋 Lịch sử nghỉ phép
                            </h3>
                            <span class="text-xs text-gray-400 font-body">Tổng: {{ $lichSuNghiPhep->total() }} đơn</span>
                        </div>

                        @if ($lichSuNghiPhep && $lichSuNghiPhep->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr
                                            class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                            <th class="text-left p-2 font-semibold text-xs table-header">Ngày tạo</th>
                                            <th class="text-left p-2 font-semibold text-xs table-header">Loại</th>
                                            <th class="text-left p-2 font-semibold text-xs table-header">Từ ngày</th>
                                            <th class="text-left p-2 font-semibold text-xs table-header">Đến ngày</th>
                                            <th class="text-left p-2 font-semibold text-xs table-header">Số ngày</th>
                                            <th class="text-left p-2 font-semibold text-xs table-header">Đã dùng</th>
                                            <th class="text-left p-2 font-semibold text-xs table-header">Còn lại</th>
                                            <th class="text-left p-2 font-semibold text-xs table-header">Lý do</th>
                                            <th class="text-left p-2 font-semibold text-xs table-header">Trạng thái</th>
                                            <th class="text-left p-2 font-semibold text-xs table-header">Người duyệt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $tongPhepNam = $soDuPhep->phep_nam_moi ?? 12;
                                            $tongDaDung = 0;
                                        @endphp
                                        @foreach ($lichSuNghiPhep as $item)
                                            @php
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
                                                <td class="p-2 text-xs table-cell">
                                                    {{ $item->created_at ? $item->created_at->format('d/m/Y') : '---' }}
                                                    <br><span
                                                        class="text-gray-400 text-[10px]">{{ $item->created_at ? $item->created_at->format('H:i') : '' }}</span>
                                                </td>
                                                <td class="p-2 text-xs table-cell">
                                                    <span
                                                        class="px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 badge-text">
                                                        {{ $item->loaiNghiPhep->ten ?? $item->loai_nghi_phep_id }}
                                                    </span>
                                                </td>
                                                <td class="p-2 text-xs table-cell">
                                                    {{ $item->ngay_bat_dau ? $item->ngay_bat_dau->format('d/m/Y') : '---' }}
                                                </td>
                                                <td class="p-2 text-xs table-cell">
                                                    {{ $item->ngay_ket_thuc ? $item->ngay_ket_thuc->format('d/m/Y') : '---' }}
                                                </td>
                                                <td class="p-2 text-xs text-center font-medium table-cell">
                                                    {{ $item->so_ngay_nghi }}
                                                </td>
                                                <td class="p-2 text-xs text-center font-medium text-orange-600 table-cell">
                                                    {{ number_format($soNgayDaDung, 1) }}
                                                </td>
                                                <td
                                                    class="p-2 text-xs text-center font-bold {{ $conLai <= 0 ? 'text-red-600' : 'text-green-600' }} table-cell">
                                                    {{ number_format($conLai, 1) }}
                                                </td>
                                                <td class="p-2 text-xs max-w-[150px] truncate table-cell"
                                                    title="{{ $item->ly_do }}">{{ $item->ly_do }}</td>
                                                <td class="p-2 text-xs table-cell">
                                                    <span
                                                        class="px-2 py-0.5 rounded-full text-xs font-medium badge-text {{ $statusColors[$item->trang_thai] ?? 'bg-gray-100 text-gray-700' }}">
                                                        {{ $statusTexts[$item->trang_thai] ?? $item->trang_thai }}
                                                    </span>
                                                </td>
                                                <td class="p-2 text-xs table-cell">
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

                            @if ($lichSuNghiPhep->hasPages())
                                <div class="mt-4 flex justify-between items-center">
                                    <div class="text-xs text-gray-500 font-body">
                                        Hiển thị {{ $lichSuNghiPhep->firstItem() }} - {{ $lichSuNghiPhep->lastItem() }}
                                        / {{ $lichSuNghiPhep->total() }} đơn
                                    </div>
                                    <div class="flex gap-1">
                                        {{ $lichSuNghiPhep->appends(['nghi_phep_page' => $lichSuNghiPhep->currentPage()])->links('pagination::tailwind') }}
                                    </div>
                                </div>
                            @endif
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-body">📭 Chưa có lịch sử nghỉ phép</p>
                        @endif
                    </div>

                    {{-- LỊCH SỬ TĂNG CA --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 mb-6">
                        <div
                            class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white font-heading">
                                ⏰ Lịch sử tăng ca
                            </h3>
                            <span class="text-xs text-gray-400 font-body">Tổng: {{ $lichSuTangCa->total() }} đơn</span>
                        </div>

                        @if ($lichSuTangCa && $lichSuTangCa->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr
                                            class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                            <th class="text-left p-2 font-semibold text-xs table-header">Ngày tạo</th>
                                            <th class="text-left p-2 font-semibold text-xs table-header">Ngày TC</th>
                                            <th class="text-left p-2 font-semibold text-xs table-header">Giờ</th>
                                            <th class="text-left p-2 font-semibold text-xs table-header">Số giờ</th>
                                            <th class="text-left p-2 font-semibold text-xs table-header">Lý do</th>
                                            <th class="text-left p-2 font-semibold text-xs table-header">Trạng thái</th>
                                            <th class="text-left p-2 font-semibold text-xs table-header">Người duyệt</th>
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
                                                <td class="p-2 text-xs table-cell">
                                                    {{ $item->created_at ? $item->created_at->format('d/m/Y') : '---' }}
                                                    <br><span
                                                        class="text-gray-400 text-[10px]">{{ $item->created_at ? $item->created_at->format('H:i') : '' }}</span>
                                                </td>
                                                <td class="p-2 text-xs table-cell">
                                                    {{ $item->ngay_tang_ca ? $item->ngay_tang_ca->format('d/m/Y') : '---' }}
                                                </td>
                                                <td class="p-2 text-xs table-cell">{{ $item->gio_bat_dau }} -
                                                    {{ $item->gio_ket_thuc }}</td>
                                                <td class="p-2 text-xs text-center font-medium table-cell">
                                                    {{ $item->so_gio_tang_ca }}h</td>
                                                <td class="p-2 text-xs max-w-[150px] truncate table-cell"
                                                    title="{{ $item->ly_do_tang_ca }}">{{ $item->ly_do_tang_ca }}</td>
                                                <td class="p-2 text-xs table-cell">
                                                    <span
                                                        class="px-2 py-0.5 rounded-full text-xs font-medium badge-text {{ $statusColors[$item->trang_thai] ?? 'bg-gray-100 text-gray-700' }}">
                                                        {{ $statusTexts[$item->trang_thai] ?? $item->trang_thai }}
                                                    </span>
                                                </td>
                                                <td class="p-2 text-xs table-cell">
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

                            @if ($lichSuTangCa->hasPages())
                                <div class="mt-4 flex justify-between items-center">
                                    <div class="text-xs text-gray-500 font-body">
                                        Hiển thị {{ $lichSuTangCa->firstItem() }} - {{ $lichSuTangCa->lastItem() }} /
                                        {{ $lichSuTangCa->total() }} đơn
                                    </div>
                                    <div class="flex gap-1">
                                        {{ $lichSuTangCa->appends(['tang_ca_page' => $lichSuTangCa->currentPage()])->links('pagination::tailwind') }}
                                    </div>
                                </div>
                            @endif
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-body">📭 Chưa có lịch sử tăng ca</p>
                        @endif
                    </div>

                    {{-- LỊCH SỬ ĐƠN XIN VỀ SỚM --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                        <div
                            class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white font-heading">
                                🏠 Lịch sử đơn xin về sớm
                            </h3>
                            <span class="text-xs text-gray-400 font-body">Tổng: {{ $lichSuVeSom->total() }} đơn</span>
                        </div>

                        @if ($lichSuVeSom && $lichSuVeSom->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr
                                            class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                            <th class="text-left p-2 font-semibold text-xs table-header">Ngày tạo</th>
                                            <th class="text-left p-2 font-semibold text-xs table-header">Ngày về</th>
                                            <th class="text-left p-2 font-semibold text-xs table-header">Giờ ra dự kiến
                                            </th>
                                            <th class="text-left p-2 font-semibold text-xs table-header">Số phút</th>
                                            <th class="text-left p-2 font-semibold text-xs table-header">Lý do</th>
                                            <th class="text-left p-2 font-semibold text-xs table-header">Trạng thái</th>
                                            <th class="text-left p-2 font-semibold text-xs table-header">Người duyệt</th>
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
                                                <td class="p-2 text-xs table-cell">
                                                    {{ $item->created_at ? $item->created_at->format('d/m/Y') : '---' }}
                                                    <br><span
                                                        class="text-gray-400 text-[10px]">{{ $item->created_at ? $item->created_at->format('H:i') : '' }}</span>
                                                </td>
                                                <td class="p-2 text-xs table-cell">
                                                    {{ $item->ngay ? $item->ngay->format('d/m/Y') : '---' }}
                                                </td>
                                                <td class="p-2 text-xs table-cell">{{ $item->gio_ra_du_kien }}</td>
                                                <td class="p-2 text-xs text-center font-medium text-orange-600 table-cell">
                                                    {{ $item->so_phut_ve_som }}p</td>
                                                <td class="p-2 text-xs max-w-[150px] truncate table-cell"
                                                    title="{{ $item->ly_do }}">{{ $item->ly_do }}</td>
                                                <td class="p-2 text-xs table-cell">
                                                    <span
                                                        class="px-2 py-0.5 rounded-full text-xs font-medium badge-text {{ $statusColors[$item->trang_thai] ?? 'bg-gray-100 text-gray-700' }}">
                                                        {{ $statusTexts[$item->trang_thai] ?? $item->trang_thai }}
                                                    </span>
                                                </td>
                                                <td class="p-2 text-xs table-cell">
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

                            @if ($lichSuVeSom->hasPages())
                                <div class="mt-4 flex justify-between items-center">
                                    <div class="text-xs text-gray-500 font-body">
                                        Hiển thị {{ $lichSuVeSom->firstItem() }} - {{ $lichSuVeSom->lastItem() }} /
                                        {{ $lichSuVeSom->total() }} đơn
                                    </div>
                                    <div class="flex gap-1">
                                        {{ $lichSuVeSom->appends(['ve_som_page' => $lichSuVeSom->currentPage()])->links('pagination::tailwind') }}
                                    </div>
                                </div>
                            @endif
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-body">📭 Chưa có lịch sử đơn xin về sớm
                            </p>
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
                <h3 id="filePreviewTitle" class="text-lg font-semibold text-gray-800 dark:text-white font-heading">📄 Xem
                    trước tài
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
                    <div class="text-center text-gray-500 dark:text-gray-400 font-body">
                        <div class="text-6xl mb-4 animate-pulse">📄</div>
                        <p>Đang tải tài liệu...</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2 p-4 border-t border-gray-200 dark:border-gray-700">
                <a id="fileDownloadLink" href="#" download
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition font-medium">⬇️ Tải
                    xuống</a>
                <button onclick="closeFilePreview()"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition font-medium">Đóng</button>
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
                <div class="text-center text-gray-500 dark:text-gray-400 font-body">
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
                                <p class="text-xs text-gray-400 text-center mt-2 font-body">⚡ Đang sử dụng Google Docs Viewer</p>
                            `;
                        } else {
                            content.innerHTML = `
                                <div class="text-center text-gray-500 dark:text-gray-400 py-12 font-body">
                                    <div class="text-6xl mb-4">📄</div>
                                    <p class="text-lg font-medium">Không thể xem trước file này</p>
                                    <p class="text-sm mt-2">Định dạng: ${contentType}</p>
                                    <p class="text-sm">Vui lòng <a href="${url}" download class="text-blue-600 hover:underline font-medium">tải xuống</a> để xem</p>
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi tải file:', error);
                        content.innerHTML = `
                            <div class="text-center text-gray-500 dark:text-gray-400 py-12 font-body">
                                <div class="text-6xl mb-4">❌</div>
                                <p class="text-lg font-medium text-red-600 dark:text-red-400">Không thể tải file</p>
                                <p class="text-sm mt-2">${error.message}</p>
                                <p class="text-xs text-gray-400 mt-1">Vui lòng kiểm tra file đã được tải lên chưa</p>
                                <div class="mt-4 flex justify-center gap-3 flex-wrap">
                                    <a href="${url}" download class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition font-medium">⬇️ Tải xuống</a>
                                    <button onclick="closeFilePreview()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition font-medium">Đóng</button>
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
                    <p class="text-xs text-gray-400 text-center mt-2 font-body">⚡ Đang sử dụng Google Docs Viewer</p>
                `;
            } else {
                content.innerHTML = `
                    <div class="text-center text-gray-500 dark:text-gray-400 py-12 font-body">
                        <div class="text-6xl mb-4">📄</div>
                        <p class="text-lg font-medium">Không thể xem trước file này</p>
                        <p class="text-sm mt-2">Định dạng: .${ext}</p>
                        <p class="text-sm">Vui lòng <a href="${url}" download class="text-blue-600 hover:underline font-medium">tải xuống</a> để xem</p>
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

        // Ghi đè hàm toggleAllSensitive để bao gồm cả ảnh CCCD
        const originalToggleAll = window.toggleAllSensitive;
        window.toggleAllSensitive = function() {
            if (originalToggleAll) {
                originalToggleAll();
            }

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
