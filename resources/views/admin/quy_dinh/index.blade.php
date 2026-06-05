@extends('layouts.admin')

@section('title', 'Quy định công ty')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<style>
    /* Tuỳ chỉnh CSS cho trang Quy định */
    .banner-quy-dinh {
        background: linear-gradient(135deg, #253fa8 0%, #3b59d6 100%);
        border-radius: 12px;
        padding: 24px;
        color: white;
        margin-bottom: 24px;
        box-shadow: 0 4px 15px rgba(37, 63, 168, 0.2);
    }
    .stat-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.05);
        padding: 20px 15px;
        text-align: center;
        height: 100%;
        transition: transform 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-3px);
    }
    .stat-icon {
        font-size: 28px;
        margin-bottom: 12px;
    }
    .icon-blue { color: #3b59d6; }
    .icon-green { color: #22c55e; }
    .icon-orange { color: #f59e0b; }
    .icon-teal { color: #14b8a6; }
    
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }
    .card-header-custom {
        background-color: transparent;
        border-bottom: 1px solid #f1f5f9;
        padding: 16px 20px;
        font-weight: 600;
    }
    
    /* Tuỳ chỉnh Accordion Danh sách quy định */
    .accordion-item {
        border: none;
        border-bottom: 1px solid #f1f5f9;
    }
    .accordion-item:last-child {
        border-bottom: none;
    }
    .accordion-button {
        padding: 16px 20px;
        font-weight: 500;
        color: #334155;
        background-color: transparent;
        box-shadow: none !important;
        width: 100%;
        text-align: left;
        border: none;
        display: flex;
        align-items: center;
    }
    .accordion-button:not(.collapsed) {
        color: #3b59d6;
        background-color: #f8fafc;
    }
    .accordion-button:focus { outline: none; }
    
    .accordion-collapse {
        display: none; /* Mặc định ẩn */
    }
    .accordion-collapse.show {
        display: block; /* Hiện khi có class show */
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
    }
    .bg-stt-1 { background-color: #3b59d6; }
    .bg-stt-2 { background-color: #22c55e; }
    .bg-stt-3 { background-color: #14b8a6; }
    .bg-stt-4 { background-color: #f59e0b; }

    /* Thẻ Sidebar */
    .sidebar-card { border-top: 4px solid; }
    .border-teal { border-top-color: #14b8a6; }
    .border-green { border-top-color: #22c55e; }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="banner-quy-dinh d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1 text-white"><i class="fas fa-clipboard-list me-2"></i> Quy Định Công Ty</h4>
                    <p class="mb-0" style="opacity: 0.8; font-size: 14px;">Nội quy và quy định làm việc</p>
                </div>
                <button onclick="exportPDF()" class="btn btn-outline-light btn-sm px-3 rounded-pill">
                    <i class="fas fa-download me-1"></i> Xuất PDF
                </button>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card bg-white">
                <i class="fas fa-clock stat-icon icon-blue"></i>
                <div class="text-muted small fw-medium mb-1">Giờ Làm Việc</div>
                <h5 class="mb-0 text-primary fw-bold">08:30 - 17:30</h5>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card bg-white">
                <i class="far fa-calendar-alt stat-icon icon-green"></i>
                <div class="text-muted small fw-medium mb-1">Ngày Làm Việc</div>
                <h5 class="mb-0 text-success fw-bold">T2 - T6</h5>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card bg-white">
                <i class="fas fa-coffee stat-icon icon-orange"></i>
                <div class="text-muted small fw-medium mb-1">Giờ Nghỉ Trưa</div>
                <h5 class="mb-0 text-warning fw-bold">12:00 - 13:00</h5>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card bg-white">
                <i class="fas fa-users stat-icon icon-teal"></i>
                <div class="text-muted small fw-medium mb-1">Tổng Nhân Viên</div>
                <h5 class="mb-0 text-info fw-bold">5</h5>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card card-custom" id="export-pdf-content">
                <div class="card-header card-header-custom d-flex align-items-center">
                    <i class="fas fa-list-ul me-2 text-muted"></i> Danh Sách Quy Định
                </div>
                <div class="card-body p-0">
                    <div class="accordion" id="accordionQuyDinh">
                        
                        <div class="accordion-item">
                            <button class="accordion-button" type="button" onclick="toggleQuyDinh('collapseOne', this)">
                                <span class="stt-circle bg-stt-1">01</span> Quy định về giờ làm việc
                            </button>
                            <div id="collapseOne" class="accordion-collapse show">
                                <div class="p-3 text-dark" style="font-size: 14px;">
                                    <div class="mb-2 text-primary fw-medium">Thời gian làm việc:</div>
                                    <ul class="list-unstyled ms-3 mb-3 text-muted">
                                        <li class="mb-1"><i class="far fa-clock text-success me-2"></i> Giờ vào: 08:30 h</li>
                                        <li class="mb-1"><i class="far fa-clock text-danger me-2"></i> Giờ ra: 17:30 h</li>
                                        <li><i class="fas fa-coffee text-warning me-2"></i> Nghỉ trưa: 12:00 - 13:00</li>
                                    </ul>
                                    
                                    <div class="mb-2 text-primary fw-medium">Quy định chấm công:</div>
                                    <p class="ms-3 mb-3 text-muted">
                                        Đi muộn quá 15 phút hoặc về sớm trước 15 phút sẽ cần gửi lý do và chờ cấp trên duyệt mới được tính công.
                                    </p>

                                    <div class="mb-2 text-primary fw-medium">Quy định tính công:</div>
                                    <ul class="list-unstyled ms-3 mb-0 text-muted">
                                        <li class="mb-1">- Làm đủ 8 giờ &rarr; <b>1 công</b></li>
                                        <li class="mb-1">- Làm trên 4 giờ &rarr; <b>0.5 công</b></li>
                                        <li>- Dưới 4 giờ &rarr; <b>0 công</b></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <button class="accordion-button collapsed" type="button" onclick="toggleQuyDinh('collapseTwo', this)">
                                <span class="stt-circle bg-stt-2">02</span> Quy định về tăng ca
                            </button>
                            <div id="collapseTwo" class="accordion-collapse">
                                <div class="p-3 text-dark" style="font-size: 14px;">
                                    <div class="mb-2 text-success fw-medium">Nguyên tắc đăng ký và duyệt:</div>
                                    <ul class="list-unstyled ms-3 mb-3 text-muted">
                                        <li class="mb-1"><i class="fas fa-check text-success me-2"></i> Phải gửi đơn đăng ký tăng ca trước khi làm thêm.</li>
                                        <li class="mb-1"><i class="fas fa-check text-success me-2"></i> Chỉ được tăng ca sau khi <b>quản lý phê duyệt</b>.</li>
                                        <li class="mb-1"><i class="fas fa-check text-success me-2"></i> Ca tăng ca được công nhận khi <b>làm đủ hoặc hơn số giờ đăng ký</b>.</li>
                                        <li><i class="fas fa-times text-danger me-2"></i> Nếu làm <b>ít hơn số giờ đăng ký</b> &rarr; không hoàn thành và chờ duyệt.</li>
                                    </ul>

                                    <div class="mb-2 text-success fw-medium">Cách tính số công:</div>
                                    <ul class="list-unstyled ms-3 mb-3 text-muted">
                                        <li class="mb-1"><i class="fas fa-calculator text-primary me-2"></i> Số công = (Giờ ra - Giờ vào) : 8 * Hệ số tăng ca</li>
                                        <li class="mb-1"><i class="fas fa-check text-success me-2"></i> Nếu <b>không hoàn thành</b>: tính theo giờ thực tế làm được.</li>
                                        <li><i class="fas fa-check text-success me-2"></i> Nếu <b>hoàn thành</b>: tính theo giờ đăng ký * hệ số tăng ca.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <button class="accordion-button collapsed" type="button" onclick="toggleQuyDinh('collapseThree', this)">
                                <span class="stt-circle bg-stt-3">03</span> Quy định về trang phục
                            </button>
                            <div id="collapseThree" class="accordion-collapse">
                                <div class="p-3 text-dark" style="font-size: 14px;">
                                    <div class="mb-2 text-info fw-medium">Trang phục công sở:</div>
                                    <ul class="list-unstyled ms-3 mb-0 text-muted">
                                        <li class="mb-1"><i class="fas fa-check text-success me-2"></i> Áo sơ mi, quần âu lịch sự</li>
                                        <li class="mb-1"><i class="fas fa-check text-success me-2"></i> Giày dép phù hợp</li>
                                        <li><i class="fas fa-times text-danger me-2"></i> Không mặc quần short, dép tông</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <button class="accordion-button collapsed" type="button" onclick="toggleQuyDinh('collapseFour', this)">
                                <span class="stt-circle bg-stt-4">04</span> Quy định về nghỉ phép
                            </button>
                            <div id="collapseFour" class="accordion-collapse">
                                <div class="p-3 text-dark" style="font-size: 14px;">
                                    <div class="mb-2 text-warning fw-medium">Nghỉ phép năm:</div>
                                    <ul class="list-unstyled ms-3 mb-0 text-muted">
                                        <li class="mb-1"><i class="fas fa-calendar-alt text-info me-2"></i> 12 ngày phép/năm</li>
                                        <li class="mb-1"><i class="fas fa-bell text-warning me-2"></i> Báo trước ít nhất 3 ngày</li>
                                        <li><i class="fas fa-envelope-open-text text-primary me-2"></i> Gửi đơn xin phép và chờ duyệt</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-custom sidebar-card border-teal mb-4">
                <div class="card-header card-header-custom text-dark">
                    <i class="fas fa-headset me-2 text-teal"></i> Liên Hệ HR
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ asset('images/avatar-default.png') }}" alt="HR" class="rounded-circle me-3" width="48" height="48" style="object-fit: cover; background: #eee;">
                        <div>
                            <div class="text-muted small">Phòng Kế Toán / Nhân Sự</div>
                            <h6 class="mb-0 fw-bold">Hoàng Thị Hoa</h6>
                        </div>
                    </div>
                    <ul class="list-unstyled mb-0" style="font-size: 14px;">
                        <li class="mb-3"><i class="fas fa-phone-alt text-success me-3"></i> 0901234568</li>
                        <li class="mb-3"><i class="fas fa-envelope text-primary me-3"></i> ke.toan@hrflow.com</li>
                        <li><i class="fas fa-map-marker-alt text-danger me-3"></i> TP.HCM</li>
                    </ul>
                </div>
            </div>

            <div class="card card-custom sidebar-card border-green">
                <div class="card-header card-header-custom text-dark">
                    <i class="fas fa-bolt me-2 text-success"></i> Thao Tác Nhanh
                </div>
                <div class="card-body">
                    <button onclick="exportPDF()" class="btn btn-outline-primary w-100 mb-3 rounded-pill" style="font-size: 14px;">
                        <i class="fas fa-file-pdf me-2"></i> Tải quy định công ty (PDF)
                    </button>
                    
                    <a href="mailto:ke.toan@hrflow.com?subject=Hỏi đáp về Quy định công ty" class="btn btn-outline-success w-100 mb-3 rounded-pill" style="font-size: 14px;">
                        <i class="fas fa-question-circle me-2"></i> Hỏi đáp về quy định
                    </a>
                    
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modalLichSu" class="btn btn-outline-secondary w-100 rounded-pill" style="font-size: 14px;">
                        <i class="fas fa-history me-2"></i> Lịch sử cập nhật
                    </button>
                </div>
            </div>

<div class="modal fade" id="modalLichSu" tabindex="-1" aria-labelledby="modalLichSuLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold text-dark" id="modalLichSuLabel"><i class="fas fa-history text-secondary me-2"></i> Lịch sử cập nhật</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="timeline p-3">
            <div class="mb-3 pb-3 border-bottom">
                <h6 class="fw-bold text-primary mb-1">Phiên bản 1.1 (Hiện tại)</h6>
                <div class="text-muted small"><i class="far fa-calendar-alt me-1"></i> Cập nhật ngày 01/06/2026</div>
                <div class="mt-2 text-dark" style="font-size: 14px;">- Cập nhật số lượng nhân viên.<br>- Bổ sung quy định giờ làm việc Thứ 7.</div>
            </div>
            <div>
                <h6 class="fw-bold text-secondary mb-1">Phiên bản 1.0</h6>
                <div class="text-muted small"><i class="far fa-calendar-alt me-1"></i> Ban hành ngày 01/01/2026</div>
                <div class="mt-2 text-dark" style="font-size: 14px;">- Ban hành lần đầu quy chế nội bộ.</div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
        </div>
    </div>
</div>

<script>
    // Hàm đóng/mở từng quy định (Giữ nguyên của bạn)
    function toggleQuyDinh(targetId, btnElement) {
        const targetContent = document.getElementById(targetId);
        const isOpen = targetContent.classList.contains('show');

        document.querySelectorAll('.accordion-collapse').forEach(function(content) {
            content.classList.remove('show');
        });
        document.querySelectorAll('.accordion-button').forEach(function(btn) {
            btn.classList.add('collapsed');
        });

        if (!isOpen) {
            targetContent.classList.add('show');
            btnElement.classList.remove('collapsed');
        }
    }

    // Hàm Xuất PDF đã được tối ưu
    function exportPDF() {
        // 1. Mở toàn bộ các danh sách ra
        document.querySelectorAll('.accordion-collapse').forEach(function(content) {
            content.classList.add('show');
        });
        document.querySelectorAll('.accordion-button').forEach(function(btn) {
            btn.classList.remove('collapsed');
        });

        // 2. Tạm dừng 0.5 giây để HTML kịp hiển thị hết chữ rồi mới chụp PDF
        setTimeout(function() {
            const element = document.getElementById('export-pdf-content');

            const opt = {
                margin:       10,
                filename:     'Quy_Dinh_Cong_Ty.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            // 3. Thực thi tải file
            html2pdf().set(opt).from(element).save();
        }, 500); // 500 mili-giây = 0.5s
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection