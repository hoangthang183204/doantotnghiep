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
        .head-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .head-table td { vertical-align: top; }
        .right { text-align: right; }
        .center { text-align: center; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 6px; }
        table.data th, table.data td { border: 1px solid #d1d5db; padding: 5px 6px; }
        table.data th { background: #1e40af; color: #fff; font-size: 10px; }
        table.data tfoot td { font-weight: bold; background: #eef2ff; }
        .deduct { color: #b91c1c; }
        .net { color: #1e40af; font-weight: bold; }
        .sign { margin-top: 30px; width: 100%; border-collapse: collapse; }
        .sign td { text-align: center; vertical-align: top; padding-top: 4px; }
        .footer { margin-top: 14px; font-size: 9px; color: #888; text-align: center; }
    </style>
</head>
<body>
    <table class="head-table">
        <tr>
            <td>
                <div class="company">🏢 CÔNG TY ABC</div>
                <div class="muted">Hệ thống quản lý nhân sự HRM</div>
            </td>
            <td class="right">
                <h1>Bảng lương tháng {{ $bangLuong->thang }}/{{ $bangLuong->nam }}</h1>
                <div class="muted">Mã: {{ $bangLuong->ma_bang_luong }} — Trạng thái: {{ $bangLuong->trang_thai_text }}</div>
                <div class="muted">Ngày xuất: {{ $ngayXuat }}</div>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th class="center">STT</th>
                <th>Nhân viên</th>
                <th>Chức vụ</th>
                <th class="center">Công</th>
                <th class="right">Lương theo công</th>
                <th class="right">Phụ cấp</th>
                <th class="right">Tăng ca</th>
                <th class="right">Tổng lương</th>
                <th class="right">Khấu trừ</th>
                <th class="right">Thực nhận</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bangLuong->luongNhanViens as $i => $lnv)
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td>{{ trim(($lnv->nguoiDung->ho_so->ho ?? '') . ' ' . ($lnv->nguoiDung->ho_so->ten ?? '')) ?: ($lnv->nguoiDung->ten_dang_nhap ?? '') }}</td>
                <td>{{ $lnv->nguoiDung->chuc_vu->ten ?? '' }}</td>
                <td class="center">{{ rtrim(rtrim(number_format($lnv->so_ngay_cong, 1), '0'), '.') }}/{{ (int) $lnv->so_ngay_cong_chuan }}</td>
                <td class="right">{{ number_format($lnv->luong_theo_cong) }}</td>
                <td class="right">{{ number_format($lnv->tong_phu_cap) }}</td>
                <td class="right">{{ number_format($lnv->tien_tang_ca) }}</td>
                <td class="right">{{ number_format($lnv->tong_luong) }}</td>
                <td class="right deduct">-{{ number_format($lnv->tong_khau_tru) }}</td>
                <td class="right net">{{ number_format($lnv->luong_thuc_nhan) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td class="center" colspan="4">TỔNG CỘNG ({{ $bangLuong->luongNhanViens->count() }} NV)</td>
                <td class="right">{{ number_format($bangLuong->luongNhanViens->sum('luong_theo_cong')) }}</td>
                <td class="right">{{ number_format($bangLuong->luongNhanViens->sum('tong_phu_cap')) }}</td>
                <td class="right">{{ number_format($bangLuong->luongNhanViens->sum('tien_tang_ca')) }}</td>
                <td class="right">{{ number_format($bangLuong->luongNhanViens->sum('tong_luong')) }}</td>
                <td class="right deduct">-{{ number_format($bangLuong->luongNhanViens->sum('tong_khau_tru')) }}</td>
                <td class="right net">{{ number_format($bangLuong->luongNhanViens->sum('luong_thuc_nhan')) }}</td>
            </tr>
        </tfoot>
    </table>

    <table class="sign">
        <tr>
            <td>
                <b>NGƯỜI LẬP BẢNG</b><br>
                <span class="muted">(Ký, ghi rõ họ tên)</span>
            </td>
            <td>
                <b>KẾ TOÁN</b><br>
                <span class="muted">(Ký, ghi rõ họ tên)</span>
            </td>
            <td>
                <b>GIÁM ĐỐC</b><br>
                <span class="muted">(Ký, ghi rõ họ tên)</span>
            </td>
        </tr>
    </table>

    <div class="footer">Báo cáo được tạo tự động bởi hệ thống HRM • {{ $ngayXuat }}</div>
</body>
</html>
