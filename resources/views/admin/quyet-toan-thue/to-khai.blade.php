<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>{{ $mau }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 16px; text-align: center; margin-bottom: 0; }
        p { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #333; padding: 4px 6px; }
        th { background: #f3f4f6; }
        .right { text-align: right; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <button class="no-print" onclick="window.print()">In tờ khai</button>
    <h1>{{ $mau === '05/KK-TNCN' ? 'TỜ KHAI KHẤU TRỪ THUẾ THU NHẬP CÁ NHÂN' : 'TỜ KHAI QUYẾT TOÁN THUẾ THU NHẬP CÁ NHÂN' }}</h1>
    <p>Mẫu số {{ $mau }} — Kỳ: {{ $thang ? 'Tháng '.$thang.'/' : 'Năm ' }}{{ $nam }}</p>
    <table>
        <thead>
            <tr>
                <th>STT</th><th>Họ và tên</th><th>MST</th>
                <th>Thu nhập chịu thuế</th><th>Bảo hiểm được trừ</th><th>Giảm trừ gia cảnh</th>
                <th>Thu nhập tính thuế</th><th>Thuế đã khấu trừ</th>
                @if(!$thang)<th>Thuế phải nộp cả năm</th><th>Chênh lệch</th>@endif
            </tr>
        </thead>
        <tbody>
            @foreach($dong as $i => $row)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $row['ho_ten'] }}</td>
                <td>{{ $row['mst'] }}</td>
                <td class="right">{{ number_format($row['thu_nhap_chiu_thue']) }}</td>
                <td class="right">{{ number_format($row['bao_hiem']) }}</td>
                <td class="right">{{ number_format($row['giam_tru']) }}</td>
                <td class="right">{{ number_format($row['thu_nhap_tinh_thue']) }}</td>
                <td class="right">{{ number_format($row['thue_da_khau_tru']) }}</td>
                @if(!$thang)
                    <td class="right">{{ number_format($row['thue_phai_nop']) }}</td>
                    <td class="right">{{ number_format($row['chenh_lech']) }}</td>
                @endif
            </tr>
            @endforeach
            <tr>
                <td colspan="3"><b>Tổng</b></td>
                <td class="right"><b>{{ number_format(collect($dong)->sum('thu_nhap_chiu_thue')) }}</b></td>
                <td class="right"><b>{{ number_format(collect($dong)->sum('bao_hiem')) }}</b></td>
                <td class="right"><b>{{ number_format(collect($dong)->sum('giam_tru')) }}</b></td>
                <td class="right"><b>{{ number_format(collect($dong)->sum('thu_nhap_tinh_thue')) }}</b></td>
                <td class="right"><b>{{ number_format(collect($dong)->sum('thue_da_khau_tru')) }}</b></td>
                @if(!$thang)
                    <td class="right"><b>{{ number_format(collect($dong)->sum('thue_phai_nop')) }}</b></td>
                    <td class="right"><b>{{ number_format(collect($dong)->sum('chenh_lech')) }}</b></td>
                @endif
            </tr>
        </tbody>
    </table>
    <p style="text-align:left;margin-top:18px">Số dương ở cột chênh lệch là thuế còn phải nộp. Số âm là thuế nộp thừa.</p>
</body>
</html>
