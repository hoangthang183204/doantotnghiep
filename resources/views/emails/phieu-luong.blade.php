<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            background:#eef2f7;
            padding:30px;
        }

        .a4{
            width:800px;
            margin:auto;
            background:white;
            padding:30px;
            border-radius:10px;
            box-shadow:0 10px 25px rgba(0,0,0,0.08);
        }

        /* HEADER */
        .header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            border-bottom:2px solid #1e40af;
            padding-bottom:15px;
            margin-bottom:20px;
        }

        .company{
            font-size:18px;
            font-weight:700;
            color:#1e40af;
        }

        .title{
            text-align:right;
        }

        .title h2{
            margin:0;
            font-size:20px;
            color:#111827;
        }

        .title small{
            color:#6b7280;
        }

        /* INFO GRID */
        .grid{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:10px 30px;
            margin-bottom:20px;
        }

        .box{
            background:#f9fafb;
            padding:10px 12px;
            border-radius:6px;
            font-size:14px;
        }

        .label{
            color:#6b7280;
            font-size:12px;
        }

        .value{
            font-weight:600;
            margin-top:3px;
        }

        /* TABLE */
        table{
            width:100%;
            border-collapse:collapse;
            margin-top:10px;
        }

        th{
            background:#1e40af;
            color:white;
            padding:10px;
            font-size:13px;
            text-align:left;
        }

        td{
            padding:10px;
            border-bottom:1px solid #e5e7eb;
            font-size:14px;
        }

        .right{
            text-align:right;
        }

        .income{
            color:#059669;
            font-weight:600;
        }

        .deduct{
            color:#dc2626;
            font-weight:600;
        }

        /* TOTAL */
        .total{
            margin-top:20px;
            padding:15px;
            background:#ecfeff;
            border-left:5px solid #0891b2;
            display:flex;
            justify-content:space-between;
            font-size:18px;
            font-weight:700;
        }

        .footer{
            margin-top:20px;
            font-size:12px;
            color:#6b7280;
            text-align:center;
        }
    </style>
</head>

<body>

<div class="a4">

    <!-- HEADER -->
    <div class="header">
        <div class="company">
            🏢 CÔNG TY ABC
        </div>

        <div class="title">
            <h2>PHIẾU LƯƠNG</h2>
            <small>Tháng {{ $luong->luong_thang }}/{{ $luong->luong_nam }}</small>
        </div>
    </div>

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

    <div class="footer">
        Phiếu lương được tạo tự động bởi hệ thống HRM • Không cần chữ ký
    </div>

</div>

</body>
</html>