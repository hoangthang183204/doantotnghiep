@extends('layouts.admin')

@section('title', 'Chi tiết phụ cấp')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* Reset & Base Fonts */
body {
    background-color: #f6f7fb !important;
    font-family: "Inter", sans-serif;
}

/* Toàn bộ vùng chứa */
.detail-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1.5rem;
}

/* Card thiết kế bo tròn mượt */
.page-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #f1f3f7;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
}

/* Layout phần tiêu đề */
.header-wrapper {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.icon-box-gift {
    width: 48px;
    height: 48px;
    background-color: #eff6ff;
    color: #3b82f6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

/* Hàng thông tin chuẩn tỉ lệ */
.info-row {
    display: flex;
    align-items: center;
    padding: 18px 24px;
    border-bottom: 1px solid #f3f4f6;
}

.info-row:last-child {
    border-bottom: none;
}

/* Cột nhãn kèm icon định danh */
.info-label {
    width: 260px;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #4b5563;
    font-weight: 500;
    font-size: 14px;
}

.icon-prefix {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

/* Màu sắc background riêng biệt cho từng icon theo ảnh */
.bg-blue-light { background-color: #eff6ff; color: #3b82f6; }
.bg-indigo-light { background-color: #e0e7ff; color: #6366f1; }
.bg-purple-light { background-color: #f3e8ff; color: #a855f7; }
.bg-green-light { background-color: #dcfce7; color: #22c55e; }

/* Giá trị text hiển thị */
.info-value {
    font-weight: 500;
    color: #1f2937;
    font-size: 14px;
}

.info-value.amount {
    color: #16a34a;
    font-weight: 600;
}

/* Badge trạng thái "Hoạt động" dạng pill */
.badge-status {
    display: inline-flex;
    align-items: center;
    padding: 4px 16px;
    border-radius: 9999px;
    font-size: 13px;
    font-weight: 500;
}

.badge-active {
    background-color: #dcfce7;
    color: #16a34a;
}

.badge-inactive {
    background-color: #fee2e2;
    color: #dc2626;
}

/* Nút quay lại danh sách */
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 8px 16px;
    background-color: #ffffff;
    border: 1px solid #e5e7eb;
    color: #374151;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-back:hover {
    background-color: #f9fafb;
    color: #111827;
    text-decoration: none;
}
</style>

<div class="detail-container">

    <div class="mb-3 text-sm text-gray-500" style="font-size: 13px;">
        <span class="text-blue-500 font-medium">Phụ cấp</span> 
        <span class="mx-2 text-gray-400">&gt;</span> 
        <span class="text-gray-400">Chi tiết phụ cấp</span>
    </div>

    <div class="page-card p-4 mb-4 header-wrapper">
        <div class="icon-box-gift">
            <i class="fa-solid fa-gift"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold m-0" style="color: #111827; font-size: 20px;">Chi tiết phụ cấp</h2>
            <p class="text-gray-500 m-0 mt-1" style="font-size: 13px; color: #6b7280;">Thông tin đầy đủ của phụ cấp trong hệ thống</p>
        </div>
    </div>

    <div class="page-card p-2 mb-4">

        <div class="info-row">
            <div class="info-label">
                <span class="icon-prefix bg-blue-light"><i class="fa-solid fa-hashtag"></i></span>
                Mã phụ cấp
            </div>
            <div class="info-value font-mono">{{ $phuCap->ma ?? 'XANG_XEEE3333' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">
                <span class="icon-prefix bg-indigo-light"><i class="fa-regular fa-file-text"></i></span>
                Tên phụ cấp
            </div>
            <div class="info-value">{{ $phuCap->ten ?? 'Phụ cấp xăng djsabdfjkb' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">
                <span class="icon-prefix bg-blue-light"><i class="fa-solid fa-layer-group"></i></span>
                Loại phụ cấp
            </div>
            <div class="info-value">{{ $phuCap->loai_phu_cap ?? 'theo_cap_bac' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">
                <span class="icon-prefix bg-green-light"><i class="fa-solid fa-money-bill-wave"></i></span>
                Số tiền mặc định
            </div>
            <div class="info-value amount">
                {{ isset($phuCap->so_tien_mac_dinh) ? number_format($phuCap->so_tien_mac_dinh, 0, ',', '.') : '1.011.232' }} đ
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">
                <span class="icon-prefix bg-green-light"><i class="fa-regular fa-circle-check"></i></span>
                Trạng thái
            </div>
            <div class="info-value">
                @if($phuCap->trang_thai ?? true)
                    <span class="badge-status badge-active">Hoạt động</span>
                @else
                    <span class="badge-status badge-collapse badge-inactive">Ngừng</span>
                @endif
            </div>
        </div>

    </div>

    <div class="mt-3">
        <a href="{{ route('admin.phu-cap.index') }}" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>
</div>

@endsection