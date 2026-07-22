<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhân viên đã ký hợp đồng</title>
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
        .btn { display: inline-block; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; padding: 12px 30px; text-decoration: none; border-radius: 10px; margin-top: 20px; font-weight: 600; }
    </style>
</head>
<body>
    <div class="header">
        <h1>✅ Nhân viên đã ký hợp đồng</h1>
        <p style="margin: 5px 0 0; opacity: 0.9;">Hợp đồng đã được ký thành công</p>
    </div>
    <div class="content">
        <p>Xin chào <strong>Bộ phận Nhân sự</strong>,</p>

        <p>Nhân viên <strong>{{ $hoSo->ho ?? '' }} {{ $hoSo->ten ?? $nhanVien->ten_dang_nhap }}</strong> đã <strong>ký hợp đồng lao động</strong> thành công.</p>

        <div class="info-box">
            <div class="row">
                <span class="label">📌 Số hợp đồng:</span>
                <span class="value"><strong>{{ $hopDong->so_hop_dong }}</strong></span>
            </div>
            <div class="row">
                <span class="label">👤 Nhân viên:</span>
                <span class="value">{{ $hoSo->ho ?? '' }} {{ $hoSo->ten ?? $nhanVien->ten_dang_nhap }}</span>
            </div>
            <div class="row">
                <span class="label">📅 Ngày ký:</span>
                <span class="value">{{ $hopDong->thoi_gian_ky ? \Carbon\Carbon::parse($hopDong->thoi_gian_ky)->format('d/m/Y H:i') : '---' }}</span>
            </div>
            <div class="row">
                <span class="label">💰 Lương:</span>
                <span class="value" style="color: #16a34a; font-weight: 700;">{{ number_format($hopDong->luong_co_ban, 0, ',', '.') }} VNĐ</span>
            </div>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('admin.hop-dong.show', $hopDong->id) }}" class="btn">📄 Xem chi tiết hợp đồng</a>
        </div>
    </div>
    <div class="footer">
        <p>📧 Email này được gửi tự động từ hệ thống HRFlow.</p>
        <p>&copy; {{ date('Y') }} HRFlow - Hệ thống quản lý nhân sự</p>
    </div>
</body>
</html>