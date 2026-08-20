<!-- hop-dong-gui-ky.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hợp đồng cần ký</title>
</head>
<body style="margin:0;padding:0;background-color:#f5f7fa;font-family:'Segoe UI',Arial,Helvetica,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f5f7fa;padding:30px 0;">
    <tr>
        <td align="center">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:560px;width:100%;">
                <tr>
                    <td style="padding:0 20px;">
                        <!-- Header -->
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff;border-radius:8px 8px 0 0;border-bottom:2px solid #1e293b;">
                            <tr>
                                <td style="padding:28px 30px 20px;text-align:center;">
                                    <h1 style="margin:0;font-size:20px;font-weight:600;color:#1e293b;letter-spacing:-0.5px;">
                                        📄 Hợp đồng lao động cần ký
                                    </h1>
                                    <p style="margin:4px 0 0;font-size:14px;color:#64748b;">Vui lòng đăng nhập để xem và ký hợp đồng</p>
                                </td>
                            </tr>
                        </table>

                        <!-- Content -->
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff;border-radius:0 0 8px 8px;">
                            <tr>
                                <td style="padding:30px 30px 20px;">
                                    <p style="margin:0 0 16px;font-size:15px;color:#334155;line-height:1.7;">
                                        Xin chào <strong>{{ $hoSo->ho ?? '' }} {{ $hoSo->ten ?? $nhanVien->ten_dang_nhap }}</strong>,
                                    </p>
                                    <p style="margin:0 0 20px;font-size:15px;color:#334155;line-height:1.7;">
                                        Bộ phận Nhân sự đã gửi cho bạn <strong>hợp đồng lao động</strong> để ký. Vui lòng đăng nhập vào hệ thống để xem và ký hợp đồng.
                                    </p>

                                    <!-- Info Box -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f8fafc;border-radius:6px;border-left:3px solid #1e293b;margin-bottom:20px;">
                                        <tr><td style="padding:16px 20px;">
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                <tr><td style="padding:4px 0;font-size:14px;color:#475569;border-bottom:1px solid #e9edf2;"><span style="font-weight:600;color:#64748b;display:inline-block;width:120px;">📌 Số hợp đồng:</span> <strong style="color:#1e293b;">{{ $hopDong->so_hop_dong }}</strong></td></tr>
                                                <tr><td style="padding:4px 0;font-size:14px;color:#475569;border-bottom:1px solid #e9edf2;"><span style="font-weight:600;color:#64748b;display:inline-block;width:120px;">📋 Loại hợp đồng:</span> <span style="color:#1e293b;">
                                                    @switch($hopDong->loai_hop_dong)
                                                        @case('thu_viec') Thử việc @break
                                                        @case('xac_dinh_thoi_han') Xác định thời hạn @break
                                                        @case('khong_xac_dinh_thoi_han') Không xác định thời hạn @break
                                                        @default {{ $hopDong->loai_hop_dong }}
                                                    @endswitch
                                                </span></td></tr>
                                                <tr><td style="padding:4px 0;font-size:14px;color:#475569;border-bottom:1px solid #e9edf2;"><span style="font-weight:600;color:#64748b;display:inline-block;width:120px;">💰 Lương cơ bản:</span> <span style="color:#16a34a;font-weight:700;">{{ number_format($hopDong->luong_co_ban, 0, ',', '.') }} VNĐ</span></td></tr>
                                                <tr><td style="padding:4px 0;font-size:14px;color:#475569;border-bottom:1px solid #e9edf2;"><span style="font-weight:600;color:#64748b;display:inline-block;width:120px;">📅 Ngày bắt đầu:</span> <span style="color:#1e293b;">{{ \Carbon\Carbon::parse($hopDong->ngay_bat_dau)->format('d/m/Y') }}</span></td></tr>
                                                <tr><td style="padding:4px 0;font-size:14px;color:#475569;border-bottom:1px solid #e9edf2;"><span style="font-weight:600;color:#64748b;display:inline-block;width:120px;">📅 Ngày kết thúc:</span> <span style="color:#1e293b;">{{ $hopDong->ngay_ket_thuc ? \Carbon\Carbon::parse($hopDong->ngay_ket_thuc)->format('d/m/Y') : 'Vô thời hạn' }}</span></td></tr>
                                                <tr><td style="padding:4px 0;font-size:14px;color:#475569;"><span style="font-weight:600;color:#64748b;display:inline-block;width:120px;">📊 Trạng thái:</span> <span style="display:inline-block;padding:2px 12px;border-radius:20px;font-size:12px;font-weight:600;background-color:#fef3c7;color:#b45309;">Chờ ký</span></td></tr>
                                            </table>
                                        </td></tr>
                                    </table>

                                    <!-- Note -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#fef2f2;border-radius:6px;border:1px solid #fecaca;margin-bottom:20px;">
                                        <tr><td style="padding:12px 16px;">
                                            <p style="margin:0;font-size:13px;color:#991b1b;"><strong>⚠️ Lưu ý:</strong> Vui lòng ký hợp đồng trước ngày <strong>{{ \Carbon\Carbon::parse($hopDong->ngay_bat_dau)->format('d/m/Y') }}</strong> để hợp đồng có hiệu lực đúng hạn.</p>
                                        </td></tr>
                                    </table>

                                    <!-- Main Button -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr><td align="center" style="padding:4px 0 2px;">
                                            <a href="{{ route('login') }}?redirect={{ route('employee.hop-dong.index') }}" style="display:inline-block;padding:13px 36px;background-color:#1e293b;color:#ffffff;text-decoration:none;font-size:15px;font-weight:600;border-radius:6px;letter-spacing:0.3px;">✍️ Ký hợp đồng ngay</a>
                                        </td></tr>
                                        <tr><td align="center" style="padding:4px 0 8px;">
                                            <p style="margin:0;font-size:12px;color:#94a3b8;">Bạn sẽ được chuyển đến trang đăng nhập</p>
                                        </td></tr>
                                    </table>

                                    <!-- Secondary link -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr><td align="center" style="padding:2px 0 14px;">
                                            <a href="{{ route('employee.hop-dong.index') }}" style="display:inline-block;padding:10px 30px;background-color:transparent;color:#1e293b;text-decoration:none;font-size:13px;font-weight:500;border:1px solid #d1d5db;border-radius:6px;">📄 Xem chi tiết hợp đồng</a>
                                        </td></tr>
                                    </table>

                                    <!-- Instructions -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f8fafc;border-radius:6px;margin-bottom:16px;">
                                        <tr><td style="padding:14px 18px;">
                                            <p style="margin:0 0 4px;font-size:13px;font-weight:600;color:#334155;">📎 Hướng dẫn:</p>
                                            <ol style="margin:0;padding-left:20px;font-size:13px;color:#475569;line-height:1.8;">
                                                <li>Nhấn nút "<strong>Ký hợp đồng ngay</strong>"</li>
                                                <li>Đăng nhập vào hệ thống (nếu chưa đăng nhập)</li>
                                                <li>Vào mục "<strong>Hợp đồng của tôi</strong>"</li>
                                                <li>Xem nội dung và ký hợp đồng</li>
                                                <li>Tải lên file scan hợp đồng đã ký</li>
                                            </ol>
                                        </td></tr>
                                    </table>

                                    <p style="margin:0 0 16px;font-size:13px;color:#64748b;">Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ bộ phận Nhân sự.</p>

                                    <!-- Contact -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f8fafc;border-radius:6px;">
                                        <tr><td style="padding:14px 20px;">
                                            <p style="margin:0 0 2px;font-size:13px;font-weight:600;color:#334155;">📞 Liên hệ HR:</p>
                                            <p style="margin:0;font-size:13px;color:#64748b;">📧 hr@hrflow.com &nbsp;|&nbsp; 📞 024.1234.5678</p>
                                        </td></tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        <!-- Footer -->
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr><td style="padding:20px 20px 10px;text-align:center;">
                                <p style="margin:0 0 4px;font-size:11px;color:#94a3b8;">📧 Email này được gửi tự động từ hệ thống HRFlow. Vui lòng không trả lời email này.</p>
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