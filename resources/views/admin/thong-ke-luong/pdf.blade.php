<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: "DejaVu Sans", sans-serif; }
        body { font-size: 11px; color: #111; margin: 0; }
        h1 { font-size: 16px; margin: 0 0 2px; text-transform: uppercase; }
        .muted { color: #666; }
        .company { font-size: 13px; font-weight: bold; color: #1e40af; }
        .head { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .head td { vertical-align: top; }
        .right { text-align: right; }
        .center { text-align: center; }
        .cards { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .cards td { border: 1px solid #e5e7eb; padding: 8px; width: 25%; }
        .cards .label { color: #6b7280; font-size: 9px; display: block; }
        .cards .value { font-weight: bold; font-size: 13px; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th, table.data td { border: 1px solid #d1d5db; padding: 5px 6px; }
        table.data th { background: #1e40af; color: #fff; font-size: 10px; }
        table.data tfoot td { font-weight: bold; background: #eef2ff; }
        .deduct { color: #b91c1c; }
        .net { color: #1e40af; font-weight: bold; }
        .footer { margin-top: 14px; font-size: 9px; color: #888; text-align: center; }
    </style>
</head>
<body>
    <table class="head">
        <tr>
            <td>
                <div class="company">🏢 CÔNG TY ABC</div>
                <div class="muted">Hệ thống quản lý nhân sự HRM</div>
            </td>
            <td class="right">
                <h1>Thống kê quỹ lương theo phòng ban</h1>
                <div class="muted">Kỳ lương tháng {{ $thang }}/{{ $nam }} — Ngày xuất: {{ $ngayXuat }}</div>
            </td>
        </tr>
    </table>

    <table class="cards">
        <tr>
            <td><span class="label">Tổng quỹ lương (gross)</span><span class="value">{{ number_format($tongQuyLuong) }} đ</span></td>
            <td><span class="label">Tổng thực chi (net)</span><span class="value net">{{ number_format($tongThucNhan) }} đ</span></td>
            <td><span class="label">Số NV / phòng ban</span><span class="value">{{ $tongNhanVien }} / {{ $soPhongBan }}</span></td>
            <td><span class="label">Lương TB / người</span><span class="value">{{ number_format($luongTbNv) }} đ</span></td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>Phòng ban</th>
                <th class="center">Số NV</th>
                <th class="right">Phụ cấp</th>
                <th class="right">Tăng ca</th>
                <th class="right">Tổng lương</th>
                <th class="right">Khấu trừ</th>
                <th class="right">Thực chi</th>
                <th class="right">TB/người</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $r)
            <tr>
                <td>{{ $r->ten_phong_ban }}</td>
                <td class="center">{{ $r->so_nhan_vien }}</td>
                <td class="right">{{ number_format($r->tong_phu_cap) }}</td>
                <td class="right">{{ number_format($r->tong_tang_ca) }}</td>
                <td class="right">{{ number_format($r->tong_luong) }}</td>
                <td class="right deduct">-{{ number_format($r->tong_khau_tru) }}</td>
                <td class="right net">{{ number_format($r->tong_thuc_nhan) }}</td>
                <td class="right">{{ number_format($r->so_nhan_vien > 0 ? $r->tong_thuc_nhan / $r->so_nhan_vien : 0) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>TỔNG CỘNG</td>
                <td class="center">{{ $tongNhanVien }}</td>
                <td class="right">{{ number_format($rows->sum('tong_phu_cap')) }}</td>
                <td class="right">{{ number_format($rows->sum('tong_tang_ca')) }}</td>
                <td class="right">{{ number_format($tongQuyLuong) }}</td>
                <td class="right deduct">-{{ number_format($tongKhauTru) }}</td>
                <td class="right net">{{ number_format($tongThucNhan) }}</td>
                <td class="right">{{ number_format($luongTbNv) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">Báo cáo được tạo tự động bởi hệ thống HRM • {{ $ngayXuat }}</div>
</body>
</html>
