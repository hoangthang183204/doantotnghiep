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
        .section { margin-top: 18px; margin-bottom: 6px; font-size: 13px; font-weight: bold; color: #1e40af; border-bottom: 1px solid #1e40af; padding-bottom: 3px; }
        table.data .rowsum td { background: #f3f4f6; }
        .note { margin-top: 8px; font-size: 11px; color: #059669; }
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

    @php
        $dg = $luong->dienGiai();
        $npt = $luong->nguoiPhuThuocTrongKy();
        $pct = fn($v) => rtrim(rtrim(number_format($v * 100, 1), '0'), '.') . '%';
    @endphp

    <div class="section">DIỄN GIẢI CÁC KHOẢN KHẤU TRỪ</div>

    <table class="data">
        <thead>
            <tr>
                <th>A. Bảo hiểm bắt buộc — trừ trên lương cơ bản {{ number_format($dg['luong_co_ban']) }} đ</th>
                <th class="right" style="width:22%">Tỷ lệ</th>
                <th class="right" style="width:26%">Số tiền</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Bảo hiểm xã hội (BHXH)</td><td class="right">{{ $pct($dg['ty_le_bhxh']) }}</td><td class="right deduct">-{{ number_format($dg['bhxh']) }}</td></tr>
            <tr><td>Bảo hiểm y tế (BHYT)</td><td class="right">{{ $pct($dg['ty_le_bhyt']) }}</td><td class="right deduct">-{{ number_format($dg['bhyt']) }}</td></tr>
            <tr><td>Bảo hiểm thất nghiệp (BHTN)</td><td class="right">{{ $pct($dg['ty_le_bhtn']) }}</td><td class="right deduct">-{{ number_format($dg['bhtn']) }}</td></tr>
            <tr class="rowsum"><td><b>Cộng bảo hiểm bắt buộc</b></td><td class="right"><b>{{ $pct($dg['ty_le_bhxh'] + $dg['ty_le_bhyt'] + $dg['ty_le_bhtn']) }}</b></td><td class="right deduct">-{{ number_format($dg['tong_bao_hiem']) }}</td></tr>
        </tbody>
    </table>

    <table class="data" style="margin-top:10px">
        <thead>
            <tr><th colspan="2">B. Giảm trừ gia cảnh (không trừ vào lương — chỉ trừ khi tính thuế TNCN)</th></tr>
        </thead>
        <tbody>
            <tr>
                <td>Bản thân người nộp thuế (1 người × {{ number_format($dg['giam_tru_ban_than']) }} đ)</td>
                <td class="right" style="width:26%">{{ number_format($dg['giam_tru_ban_than']) }}</td>
            </tr>
            <tr>
                <td>Người phụ thuộc ({{ $dg['so_nguoi_phu_thuoc'] }} người × {{ number_format($dg['muc_giam_tru_moi_npt']) }} đ)</td>
                <td class="right">{{ number_format($dg['giam_tru_nguoi_phu_thuoc']) }}</td>
            </tr>
            @foreach($npt as $p)
                <tr>
                    <td class="sub">• {{ $p->ho_ten }} ({{ $p->quan_he_text }}@if($p->ma_so_thue) — MST {{ $p->ma_so_thue }}@endif)</td>
                    <td class="right sub">{{ number_format($dg['muc_giam_tru_moi_npt']) }}</td>
                </tr>
            @endforeach
            <tr class="rowsum"><td><b>Tổng giảm trừ gia cảnh</b></td><td class="right"><b>{{ number_format($dg['giam_tru_gia_canh']) }}</b></td></tr>
        </tbody>
    </table>

    <table class="data" style="margin-top:10px">
        <thead>
            <tr><th colspan="2">C. Thuế thu nhập cá nhân</th></tr>
        </thead>
        <tbody>
            <tr><td>① Thu nhập chịu thuế (lương theo công + phụ cấp chịu thuế + tăng ca)</td><td class="right" style="width:26%">{{ number_format($dg['thu_nhap_chiu_thue']) }}</td></tr>
            <tr><td>② Trừ bảo hiểm bắt buộc (A)</td><td class="right deduct">-{{ number_format($dg['tong_bao_hiem']) }}</td></tr>
            <tr><td>③ Trừ giảm trừ gia cảnh (B)</td><td class="right deduct">-{{ number_format($dg['giam_tru_gia_canh']) }}</td></tr>
            <tr class="rowsum"><td><b>④ Thu nhập tính thuế = ① − ② − ③</b></td><td class="right"><b>{{ number_format($dg['thu_nhap_tinh_thue']) }}</b></td></tr>
        </tbody>
    </table>

    @if(empty($dg['chi_tiet_bac_thue']))
        <div class="note">Thu nhập tính thuế bằng 0 sau khi giảm trừ → không phải nộp thuế TNCN.</div>
    @else
    <table class="data" style="margin-top:10px">
        <thead>
            <tr>
                <th>Bậc</th>
                <th>Khoảng thu nhập tính thuế/tháng</th>
                <th class="right">Phần rơi vào bậc</th>
                <th class="right" style="width:14%">Thuế suất</th>
                <th class="right">Thuế</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dg['chi_tiet_bac_thue'] as $b)
            <tr>
                <td>Bậc {{ $b['bac'] }}</td>
                <td>@if($b['den'] === null) Trên {{ number_format($b['tu']) }} @else {{ number_format($b['tu']) }} – {{ number_format($b['den']) }} @endif</td>
                <td class="right">{{ number_format($b['phan_thu_nhap']) }}</td>
                <td class="right">{{ $pct($b['thue_suat']) }}</td>
                <td class="right deduct">-{{ number_format($b['thue']) }}</td>
            </tr>
            @endforeach
            <tr class="rowsum"><td colspan="4"><b>Cộng thuế TNCN phải nộp</b></td><td class="right deduct">-{{ number_format($dg['thue_tncn']) }}</td></tr>
        </tbody>
    </table>
    @endif

    <table class="data" style="margin-top:10px">
        <thead>
            <tr><th colspan="2">D. Khấu trừ khác</th></tr>
        </thead>
        <tbody>
            @forelse($dg['khau_tru_khac'] as $kt)
                <tr><td>{{ $kt->ghi_chu ?: $kt->ten_loai }}</td><td class="right deduct" style="width:26%">-{{ number_format($kt->so_tien) }}</td></tr>
            @empty
                <tr><td colspan="2" class="sub">Không có khoản tạm ứng / phạt / bồi thường nào trong kỳ.</td></tr>
            @endforelse
            <tr class="rowsum"><td><b>Cộng khấu trừ khác</b></td><td class="right deduct">-{{ number_format($dg['tong_khau_tru_khac']) }}</td></tr>
        </tbody>
    </table>

    <table class="data" style="margin-top:10px">
        <tbody>
            <tr class="rowsum">
                <td><b>TỔNG KHẤU TRỪ</b> = A {{ number_format($dg['tong_bao_hiem']) }} + C {{ number_format($dg['thue_tncn']) }} + D {{ number_format($dg['tong_khau_tru_khac']) }}</td>
                <td class="right deduct" style="width:26%"><b>-{{ number_format($dg['tong_khau_tru']) }}</b></td>
            </tr>
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
