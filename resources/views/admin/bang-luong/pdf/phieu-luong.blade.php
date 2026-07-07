<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: "DejaVu Sans", sans-serif; }
        body { font-size: 12px; color: #111; margin: 0; }
        .company { font-size: 14px; font-weight: bold; color: #1e40af; }
        h1 { font-size: 18px; margin: 0; }
        .muted { color: #666; }
        .head { width: 100%; border-collapse: collapse; border-bottom: 2px solid #1e40af; padding-bottom: 8px; margin-bottom: 14px; }
        .head td { vertical-align: top; }
        .right { text-align: right; }
        table.info { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        table.info td { padding: 5px 8px; background: #f9fafb; border: 1px solid #eee; width: 50%; }
        table.info .label { color: #6b7280; font-size: 10px; display: block; }
        table.info .value { font-weight: bold; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th, table.data td { border: 1px solid #e5e7eb; padding: 7px 9px; }
        table.data th { background: #1e40af; color: #fff; text-align: left; }
        .income { color: #059669; font-weight: bold; }
        .deduct { color: #dc2626; font-weight: bold; }
        .sub { padding-left: 18px; color: #555; font-size: 11px; }
        .total { margin-top: 16px; width: 100%; border-collapse: collapse; }
        .total td { padding: 12px; background: #ecfeff; border-left: 4px solid #0891b2; font-size: 16px; font-weight: bold; }
        .footer { margin-top: 20px; font-size: 10px; color: #888; text-align: center; }
    </style>
</head>
<body>
    <table class="head">
        <tr>
            <td><div class="company">🏢 CÔNG TY ABC</div></td>
            <td class="right">
                <h1>PHIẾU LƯƠNG</h1>
                <div class="muted">Tháng {{ $luong->luong_thang }}/{{ $luong->luong_nam }}</div>
            </td>
        </tr>
    </table>

    <table class="info">
        <tr>
            <td><span class="label">Họ tên</span><span class="value">{{ $hoTen }}</span></td>
            <td><span class="label">Chức vụ</span><span class="value">{{ $luong->nguoiDung->chuc_vu->ten ?? '—' }}</span></td>
        </tr>
        <tr>
            <td><span class="label">Số tài khoản</span><span class="value">{{ $luong->nguoiDung->ho_so->so_tai_khoan ?? 'Chưa cập nhật' }}</span></td>
            <td><span class="label">Ngày công</span><span class="value">{{ rtrim(rtrim(number_format($luong->so_ngay_cong, 1), '0'), '.') }} / {{ (int) $luong->so_ngay_cong_chuan }}</span></td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr><th>Khoản mục</th><th class="right">Số tiền (VNĐ)</th></tr>
        </thead>
        <tbody>
            <tr><td>Lương theo công</td><td class="right income">{{ number_format($luong->luong_theo_cong) }}</td></tr>
            <tr><td>Phụ cấp</td><td class="right income">{{ number_format($luong->tong_phu_cap) }}</td></tr>
            @foreach($luong->phuCapLuongs as $pc)
                <tr><td class="sub">• {{ $pc->phuCap->ten ?? $pc->ghi_chu ?? 'Phụ cấp' }}</td><td class="right sub">{{ number_format($pc->so_tien) }}</td></tr>
            @endforeach
            <tr><td>Tăng ca ({{ rtrim(rtrim(number_format($luong->gio_tang_ca, 1), '0'), '.') }} giờ)</td><td class="right income">{{ number_format($luong->tien_tang_ca) }}</td></tr>
            <tr><td><b>Các khoản khấu trừ</b></td><td class="right deduct">-{{ number_format($luong->tong_khau_tru) }}</td></tr>
            @foreach($luong->khauTruLuongs as $kt)
                <tr><td class="sub">• {{ $kt->ghi_chu ?: $kt->ten_loai }}</td><td class="right sub deduct">-{{ number_format($kt->so_tien) }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <table class="total">
        <tr>
            <td>THỰC NHẬN</td>
            <td class="right">{{ number_format($luong->luong_thuc_nhan) }} VNĐ</td>
        </tr>
    </table>

    <div class="footer">Phiếu lương được tạo tự động bởi hệ thống HRM • Ngày xuất: {{ $ngayXuat }} • Không cần chữ ký</div>
</body>
</html>
