@extends('layouts.admin')

@section('content')

<style>
/* Đẩy dòng chảy layout và căn giữa khối bảng một cách tự nhiên */
.role-container {
    padding: 2rem 1.5rem;
    min-height: calc(100vh - 160px);
    display: flex;
    flex-direction: column;
    align-items: center; 
}

.role-card-wrapper {
    background: #ffffff;
    border: none;
    border-radius: 20px;
    box-shadow: 0 10px 35px rgba(149, 157, 165, 0.08);
    overflow: hidden;
    transition: box-shadow 0.3s ease;
    margin-bottom: 2rem;
    width: 100%;
    max-width: 1000px; /* Bóp gọn lại kích thước khung để các cột tự xích lại gần nhau */
}

.role-card-wrapper:hover {
    box-shadow: 0 15px 45px rgba(149, 157, 165, 0.12);
}

/* Header Table Component */
.role-header-clean {
    padding: 24px 30px;
    background: #ffffff;
    border-bottom: 1px solid #f1f5f9;
}

.role-header-clean h3 {
    font-size: 20px;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: -0.3px;
    margin-bottom: 0;
}

/* Custom Minimalist Table */
.custom-role-table {
    margin-bottom: 0;
    width: 100%;
    /* Bỏ table-layout: fixed để trình duyệt tự dãn cột thông minh theo độ dài chữ */
}

.custom-role-table thead th {
    background: #f8fafc;
    border: none;
    padding: 18px 24px;
    font-size: 13px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.8px;
}

.custom-role-table tbody tr {
    transition: background-color 0.2s ease;
}

.custom-role-table tbody tr:hover {
    background-color: #f8fafc;
}

.custom-role-table tbody td {
    padding: 18px 24px;
    vertical-align: middle;
    border-top: 1px solid #f1f5f9;
    color: #334155;
    font-size: 14.5px;
}

/* Các class căn lề trục đứng dọc */
.align-cell-center {
    text-align: center !important;
}

.align-cell-left {
    text-align: left !important;
}

/* Components inside table */
.stt-indicator {
    font-size: 13px;
    font-weight: 700;
    color: #94a3b8;
}

.role-pill-code {
    display: inline-block;
    background: #eff6ff;
    color: #2563eb;
    padding: 5px 12px;
    border-radius: 8px;
    font-family: 'JetBrains Mono', 'Fira Code', monospace;
    font-size: 13px;
    font-weight: 600;
    border: 1px solid #dbeafe;
}

.role-display-name {
    font-weight: 600;
    color: #1e293b;
}

.role-text-desc {
    color: #64748b;
}

/* Badges Overwrite */
.badge-clean {
    padding: 6px 14px;
    border-radius: 100px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    border-width: 1px;
    border-style: solid;
    min-width: 110px;
}

.badge-clean-system {
    background-color: #fef2f2;
    color: #ef4444;
    border-color: #fee2e2;
}

.badge-clean-custom {
    background-color: #f0fdf4;
    color: #22c55e;
    border-color: #dcfce7;
}

.badge-clean-active {
    background-color: #ecfdf5;
    color: #10b981;
    border-color: #d1fae5;
}

.badge-clean-locked {
    background-color: #fff7ed;
    color: #f97316;
    border-color: #ffedd5;
}
</style>

<div class="container-fluid role-container">

    <div class="card role-card-wrapper">
        
        <div class="role-header-clean">
            <h3>Danh sách vai trò hệ thống</h3>
        </div>

        <div class="table-responsive">
            <table class="table custom-role-table">
                <thead>
                    <tr>
                        <th class="align-cell-center">STT</th>
                        <th class="align-cell-left">Mã định danh</th>
                        <th class="align-cell-left">Tên hiển thị</th>
                        <th class="align-cell-left">Mô tả chi tiết</th>
                        <th class="align-cell-center">Phân loại</th>
                        <th class="align-cell-center">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($vaiTros as $key => $vt)
                    <tr>
                        <td class="align-cell-center">
                            <span class="stt-indicator">
                                {{ sprintf("%02d", $key + 1) }}
                            </span>
                        </td>

                        <td class="align-cell-left">
                            <span class="role-pill-code">
                                {{ $vt->name }}
                            </span>
                        </td>

                        <td class="align-cell-left">
                            <span class="role-display-name">
                                {{ $vt->ten_hien_thi }}
                            </span>
                        </td>

                        <td class="align-cell-left">
                            <div class="role-text-desc" title="{{ $vt->mo_ta }}">
                                {{ $vt->mo_ta ?: 'Chưa cập nhật mô tả...' }}
                            </div>
                        </td>

                        <td class="align-cell-center">
                            @if($vt->la_vai_tro_he_thong)
                                <span class="badge-clean badge-clean-system">
                                    <i class="fas fa-shield-alt" style="font-size: 10px;"></i> Hệ thống
                                </span>
                            @else
                                <span class="badge-clean badge-clean-custom">
                                    <i class="fas fa-user-cog" style="font-size: 10px;"></i> Tùy biến
                                </span>
                            @endif
                        </td>

                        <td class="align-cell-center">
                            @if($vt->trang_thai)
                                <span class="badge-clean badge-clean-active">
                                    <span style="width:6px; height:6px; background:#10b981; border-radius:50%; display:inline-block;"></span> Hoạt động
                                </span>
                            @else
                                <span class="badge-clean badge-clean-locked">
                                    <span style="width:6px; height:6px; background:#f97316; border-radius:50%; display:inline-block;"></span> Tạm khóa
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center p-5 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <h5>Hệ thống hiện tại chưa có dữ liệu cấu hình vai trò</h5>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        
    </div>

</div>

@endsection