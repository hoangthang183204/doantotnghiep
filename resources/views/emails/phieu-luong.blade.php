<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip</title>

    <style>
        body {
            margin: 0;
            padding: 32px 16px;
            background: linear-gradient(180deg, #eef4ff 0%, #f8fafc 100%);
            font-family: Arial, Helvetica, sans-serif;
            color: #111827;
        }

        .a4 {
            max-width: 760px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%);
            padding: 26px 30px 22px;
        }

        .company {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.4px;
            color: #ffffff;
        }

        .title {
            text-align: right;
        }

        .title h2 {
            margin: 0;
            font-size: 28px;
            line-height: 1.2;
            color: #ffffff;
            letter-spacing: 1px;
        }

        .title small {
            display: inline-block;
            margin-top: 6px;
            color: rgba(255, 255, 255, 0.85);
            font-size: 12px;
            letter-spacing: 0.4px;
        }

        .content {
            padding: 24px 30px 10px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px 18px;
            margin-bottom: 22px;
        }

        .box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #3b82f6;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 14px;
        }

        .label {
            display: block;
            color: #64748b;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 6px;
        }

        .value {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 10px;
            background: #ffffff;
        }

        th {
            background: #1d4ed8;
            color: #ffffff;
            padding: 14px 16px;
            font-size: 13px;
            text-align: left;
            letter-spacing: 0.4px;
        }

        td {
            padding: 14px 16px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            color: #1f2937;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .right {
            text-align: right;
        }

        .income {
            color: #059669;
            font-weight: 700;
        }

        .deduct {
            color: #dc2626;
            font-weight: 700;
        }

        .total {
            margin: 22px 0 18px;
            padding: 16px 18px;
            background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
            border: 1px solid #a7f3d0;
            border-left: 6px solid #10b981;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 18px;
            font-weight: 800;
            color: #065f46;
        }

        .footer {
            padding: 0 30px 24px;
            font-size: 12px;
            color: #64748b;
            text-align: center;
            line-height: 1.6;
        }

        @media only screen and (max-width: 640px) {
            body {
                padding: 20px 12px;
            }

            .header,
            .content,
            .footer {
                padding-left: 16px;
                padding-right: 16px;
            }

            .header {
                display: block;
                text-align: left;
            }

            .title {
                text-align: left;
                margin-top: 12px;
            }

            .title h2 {
                font-size: 24px;
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .total {
                display: block;
                text-align: center;
            }

            .total span + span {
                display: block;
                margin-top: 6px;
            }
        }
    </style>
</head>

<body>

<div class="a4">

    <!-- HEADER -->
    <div class="header">
        <div class="company">
            🏢 CÔNG TY HRFLOW
        </div>

        <div class="title">
            <h2>PHIẾU LƯƠNG</h2>
            <small>Tháng {{ $luong->luong_thang }}/{{ $luong->luong_nam }}</small>
        </div>
    </div>

    <div class="content">
    <!-- EMPLOYEE INFO -->
    <div class="grid">

        <div class="box">
            <div class="label">Họ tên</div>
            <div class="value">
                {{ $luong->nguoiDung->hoTen ?? $luong->nguoiDung->ten_dang_nhap }}
            </div>
        </div>

        <div class="box">
            <div class="label">Số tài khoản</div>
            <div class="value">
                {{ $luong->nguoiDung->hoSo->so_tai_khoan ?? 'Chưa cập nhật' }}
            </div>
        </div>

        <div class="box">
            <div class="label">Ngày công chuẩn</div>
            <div class="value">{{ $luong->so_ngay_cong_chuan }}</div>
        </div>

        <div class="box">
            <div class="label">Ngày công thực tế</div>
            <div class="value">{{ $luong->so_ngay_cong }}</div>
        </div>

    </div>

    <!-- TABLE -->
    <table>
        <thead>
            <tr>
                <th>Khoản mục</th>
                <th class="right">Số tiền (VNĐ)</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>Lương theo công</td>
                <td class="right income">{{ number_format($luong->luong_theo_cong) }}</td>
            </tr>

            <tr>
                <td>Phụ cấp</td>
                <td class="right income">{{ number_format($luong->tong_phu_cap) }}</td>
            </tr>

            <tr>
                <td>Tăng ca</td>
                <td class="right income">{{ number_format($luong->tien_tang_ca) }}</td>
            </tr>

            @foreach($luong->chiTietTangCa() as $tc)
            <tr>
                <td style="padding-left:18px;color:#6b7280;font-size:12px;">
                    • {{ $tc['nhan'] }} — {{ rtrim(rtrim(number_format($tc['gio'], 1), '0'), '.') }} giờ
                </td>
                <td class="right" style="color:#6b7280;font-size:12px;">{{ number_format($tc['tien']) }}</td>
            </tr>
            @endforeach

            <tr>
                <td>Thưởng</td>
                <td class="right income">{{ number_format($luong->tong_thuong) }}</td>
            </tr>

            @foreach($luong->thuongLuongs as $tt)
            <tr>
                <td style="padding-left:18px;color:#6b7280;font-size:12px;">• {{ $tt->ten }}</td>
                <td class="right" style="color:#6b7280;font-size:12px;">{{ number_format($tt->so_tien) }}</td>
            </tr>
            @endforeach

            <tr>
                <td>Khấu trừ</td>
                <td class="right deduct">-{{ number_format($luong->tong_khau_tru) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- TOTAL -->
    <div class="total">
        <span>THỰC NHẬN</span>
        <span>{{ number_format($luong->luong_thuc_nhan) }} VNĐ</span>
    </div>

    </div>

    <div class="footer">
        Phiếu lương được tạo tự động bởi hệ thống HRM • Không cần chữ ký
    </div>

</div>

</body>
</html>