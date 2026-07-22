<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận hợp đồng đã ký</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background: #f8fafc; }
        .header { background: linear-gradient(135deg, #16a34a, #15803d); color: white; padding: 25px 30px; border-radius: 12px 12px 0 0; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { background: white; padding: 30px; border-radius: 0 0 12px 12px; border: 1px solid #e2e8f0; border-top: none; }
        .info-box { background: #f1f5f9; padding: 20px; border-radius: 10px; margin: 20px 0; border-left: 4px solid #16a34a; }
        .info-box .row { display: flex; padding: 6px 0; border-bottom: 1px solid #e8edf4; }
        .info-box .row:last-child { border-bottom: none; }
        .info-box .label { font-weight: 600; color: #64748b; width: 130px; flex-shrink: 0; }
        .info-box .value { color: #1e293b; }
        .footer { text-align: center; font-size: 12px; color: #94a3b8; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0; }
        .status-badge { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #dcfce7; color: #16a34a; }
        .btn { display: inline-block; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; padding: 14px 35px; text-decoration: none; border-radius: 10px; margin-top: 20px; font-weight: 600; }
        .hr-contact { background: #f1f5f9; padding: 15px; border-radius: 8px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>✅ Xác nhận hợp đồng đã ký</h1>
        <p style="margin: 5px 0 0; opacity: 0.9;">Hợp đồng của bạn đã được ký thành công</p>
    </div>
    <div class="content">
        <p>Xin chào <strong>{{ $hoSo->ho ?? '' }} {{ $hoSo->ten ?? $nhanVien->ten_dang_nhap }}</strong>,</p>

        <p>Hệ thống HRFlow xác nhận bạn đã <strong>ký hợp đồng lao động</strong> thành công. Hợp đồng chính thức có hiệu lực.</p>

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
                <span class="label">📅 Ngày ký:</span>
                <span class="value">{{ $hopDong->thoi_gian_ky ? \Carbon\Carbon::parse($hopDong->thoi_gian_ky)->format('d/m/Y H:i') : '---' }}</span>
            </div>
            <div class="row">
                <span class="label">📊 Trạng thái:</span>
                <span class="value"><span class="status-badge">✅ Đã ký</span></span>
            </div>
        </div>

        <p style="font-size: 14px; color: #64748b;">
            📎 Bản scan hợp đồng đã ký được đính kèm trong email này.
        </p>

        <div style="text-align: center;">
            <a href="{{ route('employee.hop-dong.index') }}" class="btn">📄 Xem lại hợp đồng</a>
        </div>

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