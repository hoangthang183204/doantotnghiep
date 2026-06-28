@extends('layouts.employee')

@section('title', 'Quy định công ty')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        /* 1. HỆ THỐNG GRID TỰ BUILT (Thay thế Bootstrap) */
        .hr-wrapper {
            width: 100%;
            color: #334155;
        }

        .hr-stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .hr-main-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }

        @media (max-width: 992px) {
            .hr-stats-row {
                grid-template-columns: repeat(2, 1fr);
            }

            .hr-main-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .hr-stats-row {
                grid-template-columns: 1fr;
            }
        }

        /* 2. CÁC CLASS TIỆN ÍCH (Utilities) */
        .d-flex {
            display: flex;
        }

        .align-items-center {
            align-items: center;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }

        .mb-1 {
            margin-bottom: 4px !important;
        }

        .mb-2 {
            margin-bottom: 8px !important;
        }

        .mb-3 {
            margin-bottom: 16px !important;
        }

        .mb-4 {
            margin-bottom: 24px !important;
        }

        .me-2 {
            margin-right: 8px !important;
        }

        .me-3 {
            margin-right: 16px !important;
        }

        .ms-3 {
            margin-left: 16px !important;
        }

        .fw-medium {
            font-weight: 500;
        }

        .fw-bold {
            font-weight: bold;
        }

        .small {
            font-size: 13px;
        }

        .text-center {
            text-align: center;
        }

        /* Màu chữ */
        .text-white {
            color: #ffffff;
        }

        .text-muted {
            color: #64748b;
        }

        .text-primary {
            color: #3b59d6;
        }

        .text-success {
            color: #22c55e;
        }

        .text-warning {
            color: #f59e0b;
        }

        .text-info {
            color: #14b8a6;
        }

        .text-danger {
            color: #ef4444;
        }

        .text-dark {
            color: #1e293b;
        }

        /* 3. TUỲ CHỈNH COMPONENT (Card, Banner, Accordion) */
        .banner-quy-dinh {
            /* Đổi sang nền trắng, bo góc, và bóng đổ nhẹ để tạo khối */
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            color: #1e293b;
            /* Đổi màu chữ chính sang màu tối */
            margin-bottom: 24px;
            box-shadow: 0 4px 15px rgba(15, 23, 42, 0.05);
            /* Bóng đổ nhẹ hơn */
            border: 1px solid #e2e8f0;
            /* Thêm viền xám nhẹ cho tinh tế */
        }

        .banner-quy-dinh h4 {
            margin: 0 0 4px 0;
            font-size: 20px;
            color: #3b59d6;
            /* Đổi màu tiêu đề chính sang màu xanh */
        }

        .banner-quy-dinh .hr-btn-light {
            border-color: #3b59d6;
            /* Đổi viền xanh */
            color: #3b59d6;
            /* Đổi chữ xanh */
            background: transparent;
        }

        .banner-quy-dinh .hr-btn-light:hover {
            background: #3b59d6;
            /* Khi hover, nền xanh */
            color: white;
            /* Và chữ trắng */
        }

        .card-custom {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.05);
            border: 1px solid #f1f5f9;
        }

        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px 15px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.05);
            border: 1px solid #f1f5f9;
            transition: transform 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-card h5 {
            margin: 0;
            font-size: 18px;
        }

        .stat-icon {
            font-size: 28px;
            margin-bottom: 12px;
        }

        .card-header-custom {
            padding: 16px 20px;
            font-weight: 600;
            border-bottom: 1px solid #f1f5f9;
            background: #fff;
        }

        .card-body-custom {
            padding: 20px;
        }

        /* Danh sách quy định (Accordion) */
        .accordion-item {
            border-bottom: 1px solid #f1f5f9;
        }

        .accordion-item:last-child {
            border-bottom: none;
        }

        .accordion-button {
            width: 100%;
            text-align: left;
            background: transparent;
            border: none;
            padding: 16px 20px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            color: #334155;
            font-size: 15px;
            transition: background-color 0.2s, color 0.2s;
        }

        .accordion-button.active {
            color: #3b59d6;
            background-color: #f8fafc;
        }

        /* MŨI TÊN (MỚI THÊM) */
        .toggle-icon {
            margin-left: auto;
            /* Đẩy icon sang kịch mép phải */
            transition: transform 0.3s ease;
            /* Hiệu ứng xoay mượt */
            color: #94a3b8;
            font-size: 14px;
        }

        .accordion-button.active .toggle-icon {
            transform: rotate(180deg);
            /* Xoay ngược lên khi đang mở */
            color: #3b59d6;
            /* Đổi màu xanh cho tone-sur-tone */
        }

        .accordion-collapse {
            display: none;
            padding: 0 20px 16px 20px;
        }

        .accordion-collapse.show {
            display: block;
        }

        .stt-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            color: white;
            font-size: 12px;
            font-weight: bold;
            margin-right: 12px;
            flex-shrink: 0;
        }

        /* Màu nền STT */
        .bg-stt-1 {
            background-color: #3b59d6;
        }

        .bg-stt-2 {
            background-color: #22c55e;
        }

        .bg-stt-3 {
            background-color: #14b8a6;
        }

        .bg-stt-4 {
            background-color: #f59e0b;
        }

        /* Thẻ Sidebar & Nút bấm */
        .sidebar-card {
            border-top: 4px solid;
        }

        .border-teal {
            border-top-color: #14b8a6;
        }

        .border-green {
            border-top-color: #22c55e;
        }

        ul.list-unstyled {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .hr-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            transition: 0.2s;
            border: 1px solid transparent;
            background: transparent;
        }

        .hr-btn-light {
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
        }

        .hr-btn-light:hover {
            background: white;
            color: #253fa8;
        }

        .hr-btn-primary {
            border-color: #3b59d6;
            color: #3b59d6;
            width: 100%;
            margin-bottom: 12px;
        }

        .hr-btn-primary:hover {
            background: #3b59d6;
            color: white;
        }

        .hr-btn-success {
            border-color: #22c55e;
            color: #22c55e;
            width: 100%;
            margin-bottom: 12px;
        }

        .hr-btn-success:hover {
            background: #22c55e;
            color: white;
        }

        .hr-btn-secondary {
            border-color: #64748b;
            color: #64748b;
            width: 100%;
        }

        .hr-btn-secondary:hover {
            background: #64748b;
            color: white;
        }

        /* Modal Thuần (Không dùng BS) */
        .hr-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .hr-modal.show {
            display: flex;
        }

        .hr-modal-content {
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hr-modal-header {
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
        }

        .hr-modal-header h5 {
            margin: 0;
            font-size: 18px;
        }

        .hr-close-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #64748b;
            line-height: 1;
        }

        .hr-timeline-item {
            padding-bottom: 16px;
            margin-bottom: 16px;
            border-bottom: 1px dashed #e2e8f0;
        }

        .hr-timeline-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .hr-timeline-item h6 {
            margin: 0 0 4px 0;
            font-size: 15px;
        }

        /* ===== DARK MODE CHO TRANG QUY ĐỊNH ===== */

        html.dark .banner-quy-dinh {
            background: linear-gradient(135deg, #1f2440, #2f3f8f);
        }

        html.dark .card-custom,
        html.dark .stat-card {
            background: #2c2d48 !important;
            border: 1px solid #404463 !important;
            box-shadow: none !important;
        }

        html.dark .card-header-custom {
            background: #2c2d48 !important;
            color: #fff !important;
            border-bottom: 1px solid #404463 !important;
        }

        html.dark .accordion-button {
            background: #2c2d48 !important;
            color: #fff !important;
        }

        html.dark .accordion-button.active {
            background: #34375a !important;
            color: #fff !important;
        }

        html.dark .accordion-collapse {
            background: #2c2d48 !important;
            color: #d1d5db !important;
        }

        html.dark .accordion-item {
            border-color: #404463 !important;
        }

        html.dark .text-dark {
            color: #fff !important;
        }

        html.dark .text-muted {
            color: #a1a1aa !important;
        }

        html.dark .sidebar-card {
            background: #2c2d48 !important;
        }

        html.dark .hr-modal-content {
            background: #2c2d48 !important;
            color: #fff !important;
        }

        html.dark .hr-modal-header {
            border-color: #404463 !important;
        }

        html.dark .hr-timeline-item {
            border-color: #404463 !important;
        }

        html.dark ul li,
        html.dark .small,
        html.dark p,
        html.dark div {
            color: #d1d5db;
        }
    </style>


    <div class="hr-wrapper">
        <div class="banner-quy-dinh d-flex justify-content-between align-items-center">
            <div>
                <!-- Màu icon và tiêu đề được điều chỉnh trong CSS -->
                <h4 class="fw-bold"><i class="fas fa-clipboard-list me-2"></i> Quy Định Công Ty</h4>
                <!-- Màu chữ phụ, giữ nguyên size và giảm độ mờ một chút -->
                <div class="text-muted small">Nội quy và quy định làm việc</div>
            </div>
            <!-- Nút Xuất PDF với viền và chữ xanh, nền trong suốt -->
            <button onclick="exportPDF()" class="hr-btn hr-btn-light">
                <i class="fas fa-download me-2"></i> Xuất PDF
            </button>
        </div>

        <div class="hr-stats-row">
            <div class="stat-card">
                <i class="fas fa-clock stat-icon text-primary"></i>
                <div class="text-muted small fw-medium mb-1">Giờ Làm Việc</div>
                <h5 class="text-primary fw-bold">
                    {{ \Illuminate\Support\Facades\Storage::json('company_setting.json')['work_start'] ?? '08:30' }} -
                    {{ \Illuminate\Support\Facades\Storage::json('company_setting.json')['work_end'] ?? '17:30' }}
                </h5>
            </div>
            <div class="stat-card">
                <i class="far fa-calendar-alt stat-icon text-success"></i>
                <div class="text-muted small fw-medium mb-1">Ngày Làm Việc</div>
                <h5 class="text-success fw-bold">T2 - T6</h5>
            </div>
            <div class="stat-card">
                <i class="fas fa-coffee stat-icon text-warning"></i>
                <div class="text-muted small fw-medium mb-1">Giờ Nghỉ Trưa</div>
                <h5 class="text-warning fw-bold">12:00 - 13:00</h5>
            </div>
            <div class="stat-card">
                <i class="fas fa-users stat-icon text-info"></i>
                <div class="text-muted small fw-medium mb-1">Tổng Nhân Viên</div>
                <h5 class="text-info fw-bold">5</h5>
            </div>
        </div>

        <div class="hr-main-row">

            <div class="hr-left-col">
                <div class="card-custom" id="export-pdf-content">
                    <div class="card-header-custom d-flex align-items-center">
                        <i class="fas fa-list-ul me-2 text-muted"></i> Danh Sách Quy Định
                    </div>
                    <div>
                        <div class="accordion-item">
                            <button class="accordion-button active" type="button"
                                onclick="toggleQuyDinh('collapseOne', this)">
                                <span class="stt-circle bg-stt-1">01</span> Quy định về giờ làm việc
                                <i class="fas fa-chevron-down toggle-icon"></i>
                            </button>
                            <div id="collapseOne" class="accordion-collapse show">
                                <div class="mb-2 text-primary fw-medium">Thời gian làm việc:</div>
                                <ul class="list-unstyled ms-3 mb-3 text-muted small">
                                    <li class="mb-1"><i class="far fa-clock text-success me-2"></i> Giờ vào:
                                        {{ \Illuminate\Support\Facades\Storage::json('company_setting.json')['work_start'] ?? '08:30' }}
                                        h</li>
                                    <li class="mb-1"><i class="far fa-clock text-danger me-2"></i> Giờ ra:
                                        {{ \Illuminate\Support\Facades\Storage::json('company_setting.json')['work_end'] ?? '17:30' }}
                                        h</li>
                                    <li><i class="fas fa-coffee text-warning me-2"></i> Nghỉ trưa: 12:00 - 13:00</li>
                                </ul>
                                <div class="mb-2 text-primary fw-medium">Quy định chấm công:</div>
                                <div class="ms-3 mb-3 text-muted small">Đi muộn quá 15 phút hoặc về sớm trước 15 phút sẽ cần
                                    gửi lý do và chờ cấp trên duyệt mới được tính công.</div>
                                <div class="mb-2 text-primary fw-medium">Quy định tính công:</div>
                                <ul class="list-unstyled ms-3 mb-0 text-muted small">
                                    <li class="mb-1">- Làm đủ 8 giờ &rarr; <b>1 công</b></li>
                                    <li class="mb-1">- Làm trên 4 giờ &rarr; <b>0.5 công</b></li>
                                    <li>- Dưới 4 giờ &rarr; <b>0 công</b></li>
                                </ul>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <button class="accordion-button" type="button" onclick="toggleQuyDinh('collapseTwo', this)">
                                <span class="stt-circle bg-stt-2">02</span> Quy định về tăng ca
                                <i class="fas fa-chevron-down toggle-icon"></i>
                            </button>
                            <div id="collapseTwo" class="accordion-collapse">
                                <div class="mb-2 text-success fw-medium">Nguyên tắc đăng ký và duyệt:</div>
                                <ul class="list-unstyled ms-3 mb-3 text-muted small">
                                    <li class="mb-1"><i class="fas fa-check text-success me-2"></i> Phải gửi đơn đăng ký
                                        tăng ca trước khi làm thêm.</li>
                                    <li class="mb-1"><i class="fas fa-check text-success me-2"></i> Chỉ được tăng ca sau
                                        khi <b>quản lý phê duyệt</b>.</li>
                                    <li class="mb-1"><i class="fas fa-check text-success me-2"></i> Ca tăng ca được công
                                        nhận khi <b>làm đủ/hơn giờ đăng ký</b>.</li>
                                    <li><i class="fas fa-times text-danger me-2"></i> Nếu làm <b>ít hơn số giờ đăng ký</b>
                                        &rarr; chờ duyệt lại.</li>
                                </ul>
                                <div class="mb-2 text-success fw-medium">Cách tính số công:</div>
                                <ul class="list-unstyled ms-3 mb-0 text-muted small">
                                    <li class="mb-1"><i class="fas fa-calculator text-primary me-2"></i> Số công = (Giờ ra
                                        - Giờ vào) : 8 * Hệ số tăng ca</li>
                                </ul>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <button class="accordion-button" type="button" onclick="toggleQuyDinh('collapseThree', this)">
                                <span class="stt-circle bg-stt-3">03</span> Quy định về trang phục
                                <i class="fas fa-chevron-down toggle-icon"></i>
                            </button>
                            <div id="collapseThree" class="accordion-collapse">
                                <div class="mb-2 text-info fw-medium">Trang phục công sở:</div>
                                <ul class="list-unstyled ms-3 mb-0 text-muted small">
                                    <li class="mb-1"><i class="fas fa-check text-success me-2"></i> Áo sơ mi, quần âu lịch
                                        sự</li>
                                    <li class="mb-1"><i class="fas fa-check text-success me-2"></i> Giày dép phù hợp</li>
                                    <li><i class="fas fa-times text-danger me-2"></i> Không mặc quần short, dép tông</li>
                                </ul>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <button class="accordion-button" type="button" onclick="toggleQuyDinh('collapseFour', this)">
                                <span class="stt-circle bg-stt-4">04</span> Quy định về nghỉ phép
                                <i class="fas fa-chevron-down toggle-icon"></i>
                            </button>
                            <div id="collapseFour" class="accordion-collapse">
                                <div class="mb-2 text-warning fw-medium">Nghỉ phép năm:</div>
                                <ul class="list-unstyled ms-3 mb-0 text-muted small">
                                    <li class="mb-1"><i class="fas fa-calendar-alt text-info me-2"></i> 12 ngày phép/năm
                                    </li>
                                    <li class="mb-1"><i class="fas fa-bell text-warning me-2"></i> Báo trước ít nhất 3
                                        ngày</li>
                                    <li><i class="fas fa-envelope-open-text text-primary me-2"></i> Gửi đơn xin phép và chờ
                                        duyệt</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hr-right-col">
                <div class="card-custom sidebar-card border-teal mb-4">
                    <div class="card-header-custom">
                        <i class="fas fa-headset me-2 text-info"></i> Liên Hệ HR
                    </div>
                    <div class="card-body-custom">
                        <div class="d-flex align-items-center mb-4">
                            <img src="{{ asset('images/avatar-default.png') }}" alt="HR" width="48"
                                height="48"
                                style="border-radius: 50%; object-fit: cover; background: #eee; margin-right: 12px;">
                            <div>
                                <div class="text-muted small">Phòng Kế Toán / Nhân Sự</div>
                                <div class="fw-bold text-dark">Hoàng Thị Hoa</div>
                            </div>
                        </div>
                        <ul class="list-unstyled small">
                            <li class="mb-2"><i class="fas fa-phone-alt text-success me-2"></i> 0901234568</li>
                            <li class="mb-2"><i class="fas fa-envelope text-primary me-2"></i> ke.toan@hrflow.com</li>
                            <li><i class="fas fa-map-marker-alt text-danger me-2"></i> TP.HCM</li>
                        </ul>
                    </div>
                </div>

                <div class="card-custom sidebar-card border-green">
                    <div class="card-header-custom">
                        <i class="fas fa-bolt me-2 text-success"></i> Thao Tác Nhanh
                    </div>
                    <div class="card-body-custom text-center">
                        <button onclick="exportPDF()" class="hr-btn hr-btn-primary">
                            <i class="fas fa-file-pdf me-2"></i> Tải quy định (PDF)
                        </button>
                        <a href="mailto:ke.toan@hrflow.com?subject=Hỏi đáp về Quy định công ty"
                            class="hr-btn hr-btn-success">
                            <i class="fas fa-question-circle me-2"></i> Hỏi đáp quy định
                        </a>
                        <button onclick="openModal()" class="hr-btn hr-btn-secondary">
                            <i class="fas fa-history me-2"></i> Lịch sử cập nhật
                        </button>
                        @if (auth()->check() && auth()->user()->vai_tro_id == 1)
                            <button type="button" onclick="openModalSua()" class="hr-btn"
                                style="border-color: #ef4444; color: #ef4444; width: 100%; margin-top: 12px; font-weight: 500;">
                                <i class="fas fa-edit me-2"></i> Sửa quy định (Admin)
                            </button>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    <form action="{{ route('admin.quy-dinh.update') }}" method="POST">
        @csrf
        <div id="modalLichSu" class="hr-modal">
            <div class="hr-modal-content">
                <div class="hr-modal-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark"><i class="fas fa-edit text-danger me-2"></i> Chỉnh sửa giờ làm việc</h5>
                    <button type="button" onclick="closeModal()" class="hr-close-btn">&times;</button>
                </div>
                <div class="card-body-custom">
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500; text-align: left;">Giờ vào
                            làm:</label>
                        <input type="text" name="work_start"
                            value="{{ \Illuminate\Support\Facades\Storage::json('company_setting.json')['work_start'] ?? '08:30' }}"
                            style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; color: #000;">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500; text-align: left;">Giờ tan
                            làm:</label>
                        <input type="text" name="work_end"
                            value="{{ \Illuminate\Support\Facades\Storage::json('company_setting.json')['work_end'] ?? '17:30' }}"
                            style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; color: #000;">
                    </div>

                    <div style="display: flex; gap: 10px; justify-content: flex-end;">
                        <button type="button" onclick="closeModal()"
                            style="background: #64748b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Hủy</button>
                        <button type="submit"
                            style="background: #22c55e; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-weight: 500;">Lưu
                            thay đổi</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        // JS 1: Đóng Mở Các Dòng Quy Định
        function toggleQuyDinh(targetId, btnElement) {
            const targetContent = document.getElementById(targetId);
            const isOpen = targetContent.classList.contains('show');

            document.querySelectorAll('.accordion-collapse').forEach(content => content.classList.remove('show'));
            document.querySelectorAll('.accordion-button').forEach(btn => btn.classList.remove('active'));

            if (!isOpen) {
                targetContent.classList.add('show');
                btnElement.classList.add('active');
            }
        }

        // JS 2: Đóng Mở Modal Bằng JS Thuần
        function openModal() {
            document.getElementById('modalLichSu').classList.add('show');
        }

        function closeModal() {
            document.getElementById('modalLichSu').classList.remove('show');
        }

        // Nhấn ESC hoặc click ra ngoài để tắt Modal
        window.onclick = function(event) {
            let modal = document.getElementById('modalLichSu');
            if (event.target === modal) closeModal();
        }
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") closeModal();
        });

        // JS 3: Xuất PDF
        function exportPDF() {
            document.querySelectorAll('.accordion-collapse').forEach(content => content.classList.add('show'));
            document.querySelectorAll('.accordion-button').forEach(btn => btn.classList.add('active'));

            setTimeout(function() {
                const element = document.getElementById('export-pdf-content');
                const opt = {
                    margin: 10,
                    filename: 'Quy_Dinh_Cong_Ty.pdf',
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2,
                        useCORS: true
                    },
                    jsPDF: {
                        unit: 'mm',
                        format: 'a4',
                        orientation: 'portrait'
                    }
                };
                html2pdf().set(opt).from(element).save();
            }, 500);
        }

        function openModalSua() {
            document.getElementById('modalLichSu').classList.add('show');
        }
    </script>
@endsection
