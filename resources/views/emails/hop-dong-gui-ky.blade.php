<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hợp đồng cần ký</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background: #f8fafc; }
        .header { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; padding: 25px 30px; border-radius: 12px 12px 0 0; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0 0; opacity: 0.9; }
        .content { background: white; padding: 30px; border-radius: 0 0 12px 12px; border: 1px solid #e2e8f0; border-top: none; }
        .info-box { background: #f1f5f9; padding: 20px; border-radius: 10px; margin: 20px 0; border-left: 4px solid #2563eb; }
        .info-box .row { display: flex; padding: 6px 0; border-bottom: 1px solid #e8edf4; }
        .info-box .row:last-child { border-bottom: none; }
        .info-box .label { font-weight: 600; color: #64748b; width: 130px; flex-shrink: 0; }
        .info-box .value { color: #1e293b; }
        .btn-wrapper { text-align: center; margin: 25px 0; }
        .btn { display: inline-block; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; padding: 14px 40px; text-decoration: none; border-radius: 10px; font-weight: 600; font-size: 16px; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4); }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37, 99, 235, 0.5); background: #1d4ed8; }
        .btn-secondary { display: inline-block; background: white; color: #2563eb; padding: 12px 35px; text-decoration: none; border-radius: 10px; font-weight: 600; font-size: 14px; border: 2px solid #2563eb; transition: all 0.3s ease; }
        .btn-secondary:hover { background: #eff6ff; }
        .footer { text-align: center; font-size: 12px; color: #94a3b8; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0; }
        .status-badge { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #fef3c7; color: #d97706; }
        .note { background: #fef2f2; padding: 15px; border-radius: 8px; margin: 15px 0; border: 1px solid #fecaca; }
        .note strong { color: #dc2626; }
        .hr-contact { background: #f1f5f9; padding: 15px; border-radius: 8px; margin-top: 20px; }
        .text-center { text-align: center; }
        .text-muted { color: #64748b; font-size: 14px; }
        .mt-10 { margin-top: 10px; }
        .mt-20 { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📄 Hợp đồng lao động cần ký</h1>
        <p>Vui lòng đăng nhập để xem và ký hợp đồng</p>
    </div>
    <div class="content">
        <p>Xin chào <strong>{{ $hoSo->ho ?? '' }} {{ $hoSo->ten ?? $nhanVien->ten_dang_nhap }}</strong>,</p>

        <p>Bộ phận Nhân sự đã gửi cho bạn <strong>hợp đồng lao động</strong> để ký. Vui lòng đăng nhập vào hệ thống để xem và ký hợp đồng.</p>

        <div class="info-box">
            <div class="row">
                <span class="label">📌 Số hợp đồng:</span>
                <span class="value"><strong>{{ $hopDong->so_hop_dong }}</strong></span>
            </div>
            <div class="row">
                <span class="label">📋 Loại hợp đồng:</span>
                <span class="value">
                    @switch($hopDong->loai_hop_dong)
                        @case('thu_viec') Thử việc @break
                        @case('xac_dinh_thoi_han') Xác định thời hạn @break
                        @case('khong_xac_dinh_thoi_han') Không xác định thời hạn @break
                        @default {{ $hopDong->loai_hop_dong }}
                    @endswitch
                </span>
            </div>
            <div class="row">
                <span class="label">💰 Lương cơ bản:</span>
                <span class="value" style="color: #16a34a; font-weight: 700;">{{ number_format($hopDong->luong_co_ban, 0, ',', '.') }} VNĐ</span>
            </div>
            <div class="row">
                <span class="label">📅 Ngày bắt đầu:</span>
                <span class="value">{{ \Carbon\Carbon::parse($hopDong->ngay_bat_dau)->format('d/m/Y') }}</span>
            </div>
            <div class="row">
                <span class="label">📅 Ngày kết thúc:</span>
                <span class="value">{{ $hopDong->ngay_ket_thuc ? \Carbon\Carbon::parse($hopDong->ngay_ket_thuc)->format('d/m/Y') : '♾️ Vô thời hạn' }}</span>
            </div>
            <div class="row">
                <span class="label">📊 Trạng thái:</span>
                <span class="value"><span class="status-badge">⏳ Chờ ký</span></span>
            </div>
        </div>

        <div class="note">
            <p style="margin: 0;"><strong>⚠️ Lưu ý:</strong> Vui lòng ký hợp đồng trước ngày <strong>{{ \Carbon\Carbon::parse($hopDong->ngay_bat_dau)->format('d/m/Y') }}</strong> để hợp đồng có hiệu lực đúng hạn.</p>
        </div>

        {{-- 🔥 NÚT KÝ HỢP ĐỒNG NGAY (ĐÃ CHỈNH) --}}
        <div class="btn-wrapper">
            <a href="{{ route('login') }}?redirect={{ route('employee.hop-dong.index') }}" class="btn">
                ✍️ Ký hợp đồng ngay
            </a>
            <p class="text-muted mt-10">Bạn sẽ được chuyển đến trang đăng nhập</p>
        </div>

        {{-- NÚT PHỤ: XEM CHI TIẾT (KHÔNG CẦN ĐĂNG NHẬP) --}}
        <div class="text-center mt-20">
            <a href="{{ route('employee.hop-dong.index') }}" class="btn-secondary">
                📄 Xem chi tiết hợp đồng
            </a>
        </div>

        <div style="margin-top: 25px; padding: 15px; background: #f1f5f9; border-radius: 8px; font-size: 14px;">
            <p style="margin: 0 0 5px;"><strong>📎 Hướng dẫn:</strong></p>
            <ol style="margin: 0; padding-left: 20px; color: #475569;">
                <li>Nhấn nút "<strong>Ký hợp đồng ngay</strong>"</li>
                <li>Đăng nhập vào hệ thống (nếu chưa đăng nhập)</li>
                <li>Vào mục "<strong>Hợp đồng của tôi</strong>"</li>
                <li>Xem nội dung và ký hợp đồng</li>
                <li>Tải lên file scan hợp đồng đã ký</li>
            </ol>
        </div>

        <p class="text-muted">Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ bộ phận Nhân sự.</p>

        <div class="hr-contact">
            <p style="margin: 0;"><strong>📞 Liên hệ HR:</strong></p>
            <p style="margin: 5px 0 0;">📧 Email: hr@hrflow.com | 📞 Điện thoại: 024.1234.5678</p>
        </div>
    </div>
    <div class="footer">
        <p>📧 Email này được gửi tự động từ hệ thống HRFlow. Vui lòng không trả lời email này.</p>
        <p>&copy; {{ date('Y') }} HRFlow - Hệ thống quản lý nhân sự</p>
    </div>
</body>
</html>