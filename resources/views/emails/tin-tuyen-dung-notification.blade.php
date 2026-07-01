<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo từ HRFlow</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }
        .header {
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #1e293b;
            font-size: 24px;
            margin: 0;
        }
        .header p {
            color: #64748b;
            margin: 5px 0 0 0;
        }
        .content {
            color: #334155;
            line-height: 1.6;
        }
        .content p {
            margin-bottom: 15px;
        }
        .info-box {
            background-color: #f8fafc;
            border-left: 4px solid #2563eb;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box strong {
            color: #1e293b;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #94a3b8;
            font-size: 14px;
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-blue {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .button {
            display: inline-block;
            padding: 10px 24px;
            background-color: #2563eb;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 10px;
        }
        .button:hover {
            background-color: #1d4ed8;
        }
        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-moi_nop { background-color: #dbeafe; color: #1e40af; }
        .status-cho_duyet { background-color: #fef3c7; color: #92400e; }
        .status-da_duyet { background-color: #d1fae5; color: #065f46; }
        .status-dat { background-color: #d1fae5; color: #065f46; }
        .status-khong_dat { background-color: #fee2e2; color: #991b1b; }
        .status-da_huy { background-color: #e5e7eb; color: #374151; }
        .status-tam_dung { background-color: #fed7aa; color: #9a3412; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📢 Thông báo từ HRFlow</h1>
            <p>Hệ thống quản lý nhân sự</p>
        </div>

        <div class="content">
            <p>Xin chào <strong>{{ $ungVien->ho }} {{ $ungVien->ten }}</strong>,</p>
            
            {!! nl2br(e($noiDung)) !!}

            <div class="info-box">
                <p><strong>📋 Thông tin chi tiết:</strong></p>
                <p><strong>Vị trí:</strong> {{ $tinTuyenDung->vi_tri ?? 'Chưa xác định' }}</p>
                <p><strong>Phòng ban:</strong> {{ $tinTuyenDung->phongBan?->ten_phong_ban ?? 'Chưa xác định' }}</p>
                <p><strong>Hạn nộp hồ sơ:</strong> {{ $tinTuyenDung->han_nop_ho_so ? $tinTuyenDung->han_nop_ho_so->format('d/m/Y') : 'Chưa xác định' }}</p>
                <p><strong>Mã hồ sơ của bạn:</strong> 
                    <span class="badge badge-blue">{{ $ungVien->ma_ho_so }}</span>
                </p>
                <p><strong>Trạng thái hiện tại:</strong>
                    <span class="status status-{{ $ungVien->trang_thai }}">
                        @switch($ungVien->trang_thai)
                            @case('moi_nop') Mới nộp @break
                            @case('cho_duyet') Chờ duyệt @break
                            @case('da_duyet') Đã duyệt @break
                            @case('dat') Trúng tuyển @break
                            @case('khong_dat') Không đạt @break
                            @case('da_huy') Đã hủy @break
                            @case('tam_dung') Tạm dừng @break
                            @default {{ $ungVien->trang_thai }}
                        @endswitch
                    </span>
                </p>
            </div>

            <p>Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với phòng Nhân sự để được hỗ trợ.</p>

            <p>Trân trọng,<br>
            <strong>Phòng Nhân sự HRFlow</strong></p>
        </div>

        <div class="footer">
            <p>Email này được gửi tự động từ hệ thống HRFlow. Vui lòng không trả lời email này.</p>
            <p>&copy; {{ date('Y') }} HRFlow. Tất cả quyền được bảo lưu.</p>
        </div>
    </div>
</body>
</html>