@extends('layouts.admin')

@section('title', 'Chi tiết phụ cấp')

@section('content')

<style>
body {
    background: #f6f7fb;
    font-family: "Inter", sans-serif;
}

.page-card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.06);
}

.info-row {
    display: flex;
    padding: 14px 0;
    border-bottom: 1px solid #f1f1f1;
}

.info-label {
    width: 200px;
    color: #6b7280;
    font-weight: 500;
}

.info-value {
    font-weight: 600;
    color: #111827;
}

.badge {
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}

.badge-green {
    background: #dcfce7;
    color: #16a34a;
}

.badge-red {
    background: #fee2e2;
    color: #dc2626;
}

.badge-yellow {
    background: #fef9c3;
    color: #ca8a04;
}
</style>

<div class="container-fluid">

    <!-- HEADER -->
    <div class="page-card p-4 mb-4">
        <h2 class="text-xl font-bold">Chi tiết phụ cấp</h2>
        <p class="text-gray-500">Thông tin đầy đủ của phụ cấp</p>
    </div>

    <!-- CONTENT -->
    <div class="page-card p-4">

        <div class="info-row">
            <div class="info-label">Mã phụ cấp</div>
            <div class="info-value">{{ $phuCap->ma }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Tên phụ cấp</div>
            <div class="info-value">{{ $phuCap->ten }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Loại phụ cấp</div>
            <div class="info-value">{{ $phuCap->loai_phu_cap }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Số tiền mặc định</div>
            <div class="info-value text-green-600">
                {{ number_format($phuCap->so_tien_mac_dinh, 0, ',', '.') }} đ
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Chịu thuế</div>
            <div class="info-value">
                @if($phuCap->chiu_thue)
                    <span class="badge badge-yellow">Có</span>
                @else
                    <span class="badge badge-green">Không</span>
                @endif
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Trạng thái</div>
            <div class="info-value">
                @if($phuCap->trang_thai)
                    <span class="badge badge-green">Hoạt động</span>
                @else
                    <span class="badge badge-red">Ngừng</span>
                @endif
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('admin.phu-cap.index') }}"
               class="px-4 py-2 bg-gray-200 rounded-lg">
                ← Quay lại
            </a>
        </div>

    </div>
</div>

@endsection