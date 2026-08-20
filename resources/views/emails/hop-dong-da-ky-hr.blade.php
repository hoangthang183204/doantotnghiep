<!-- hop-dong-da-ky-hr.blade.php - Gửi cho HR (không cần file đính kèm) -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhân viên đã ký hợp đồng</title>
</head>
<body style="margin:0;padding:0;background-color:#f5f7fa;font-family:'Segoe UI',Arial,Helvetica,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f5f7fa;padding:30px 0;">
    <tr>
        <td align="center">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:560px;width:100%;">
                <tr>
                    <td style="padding:0 20px;">
                        <!-- Header -->
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff;border-radius:8px 8px 0 0;border-bottom:2px solid #16a34a;">
                            <tr>
                                <td style="padding:28px 30px 20px;text-align:center;">
                                    <h1 style="margin:0;font-size:20px;font-weight:600;color:#1e293b;letter-spacing:-0.5px;">
                                        <span style="color:#16a34a;">✓</span> Nhân viên đã ký hợp đồng
                                    </h1>
                                    <p style="margin:4px 0 0;font-size:14px;color:#64748b;">Hợp đồng đã được ký thành công</p>
                                </td>
                            </tr>
                        </table>

                        <!-- Content -->
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff;border-radius:0 0 8px 8px;">
                            <tr>
                                <td style="padding:30px 30px 20px;">
                                    <p style="margin:0 0 16px;font-size:15px;color:#334155;line-height:1.7;">
                                        Xin chào <strong>Bộ phận Nhân sự</strong>,
                                    </p>
                                    <p style="margin:0 0 20px;font-size:15px;color:#334155;line-height:1.7;">
                                        Nhân viên <strong>{{ $hoSo->ho ?? '' }} {{ $hoSo->ten ?? $nhanVien->ten_dang_nhap }}</strong> đã <strong>ký hợp đồng lao động</strong> thành công.
                                    </p>

                                    <!-- Info Box -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f8fafc;border-radius:6px;border-left:3px solid #16a34a;margin-bottom:20px;">
                                        <tr><td style="padding:16px 20px;">
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                <tr><td style="padding:4px 0;font-size:14px;color:#475569;border-bottom:1px solid #e9edf2;"><span style="font-weight:600;color:#64748b;display:inline-block;width:120px;">📌 Số hợp đồng:</span> <strong style="color:#1e293b;">{{ $hopDong->so_hop_dong }}</strong></td></tr>
                                                <tr><td style="padding:4px 0;font-size:14px;color:#475569;border-bottom:1px solid #e9edf2;"><span style="font-weight:600;color:#64748b;display:inline-block;width:120px;">👤 Nhân viên:</span> <span style="color:#1e293b;">{{ $hoSo->ho ?? '' }} {{ $hoSo->ten ?? $nhanVien->ten_dang_nhap }}</span></td></tr>
                                                <tr><td style="padding:4px 0;font-size:14px;color:#475569;border-bottom:1px solid #e9edf2;"><span style="font-weight:600;color:#64748b;display:inline-block;width:120px;">📅 Ngày ký:</span> <span style="color:#1e293b;">{{ $hopDong->thoi_gian_ky ? \Carbon\Carbon::parse($hopDong->thoi_gian_ky)->format('d/m/Y H:i') : '---' }}</span></td></tr>
                                                <tr><td style="padding:4px 0;font-size:14px;color:#475569;"><span style="font-weight:600;color:#64748b;display:inline-block;width:120px;">💰 Lương:</span> <span style="color:#16a34a;font-weight:700;">{{ number_format($hopDong->luong_co_ban, 0, ',', '.') }} VNĐ</span></td></tr>
                                            </table>
                                        </td></tr>
                                    </table>

                                    <!-- HR xem file scan ở đây (xem online) -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f8fafc;border-radius:6px;border:1px solid #e2e8f0;margin-bottom:20px;">
                                        <tr><td style="padding:12px 18px;">
                                            <p style="margin:0;font-size:13px;color:#475569;">
                                                📎 <strong>File scan hợp đồng đã ký:</strong>
                                                @if(isset($hopDong->file_scan) && $hopDong->file_scan)
                                                    <a href="{{ route('admin.hop-dong.download', $hopDong->id) }}" style="color:#1e293b;text-decoration:underline;">{{ $hopDong->file_scan }}</a>
                                                @else
                                                    <span style="color:#94a3b8;">Chưa có file</span>
                                                @endif
                                            </p>
                                        </td></tr>
                                    </table>

                                    <!-- Button -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr><td align="center" style="padding:6px 0 8px;">
                                            <a href="{{ route('admin.hop-dong.show', $hopDong->id) }}" style="display:inline-block;padding:12px 32px;background-color:#1e293b;color:#ffffff;text-decoration:none;font-size:14px;font-weight:600;border-radius:6px;letter-spacing:0.2px;">📄 Xem chi tiết hợp đồng</a>
                                        </td></tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        <!-- Footer -->
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr><td style="padding:20px 20px 10px;text-align:center;">
                                <p style="margin:0 0 4px;font-size:11px;color:#94a3b8;">📧 Email này được gửi tự động từ hệ thống HRFlow.</p>
                                <p style="margin:0;font-size:11px;color:#94a3b8;">&copy; {{ date('Y') }} HRFlow - Hệ thống quản lý nhân sự</p>
                            </td></tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>