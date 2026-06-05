@extends('layouts.admin')

@section('title', 'Quản lý Vai trò')

@section('content')

<style>
    .role-card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 2px 18px rgba(15,23,42,.05);
    }
    
    .role-table {
        width: 100%;
        margin-bottom: 0;
    }
    
    .role-table th,
    .role-table td {
        vertical-align: middle !important;
    }
    
    .role-table thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        border-bottom: 1px solid #e2e8f0;
        padding: 15px;
        white-space: nowrap;
    }
    
    .role-table td {
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .role-table tbody tr {
        transition: .2s;
    }
    
    .role-table tbody tr:hover {
        background: #f8fbff;
    }
    
    .role-table tbody tr:nth-child(even) {
        background: #fcfcfd;
    }
    
    .role-name {
        font-weight: 600;
        font-size: 15px;
        color: #1e293b;
    }
    
    .role-code {
        display: inline-block;
        margin-top: 4px;
        padding: 4px 10px;
        border-radius: 8px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 12px;
        font-family: monospace;
        font-weight: 600;
        border: 1px solid #dbeafe;
    }
    
    .role-desc {
        color: #475569;
        line-height: 1.6;
        white-space: normal;
    }
    
    .custom-badge {
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* Thêm style cho phần header */
    .page-heading {
        font-weight: 700;
        font-size: 1.25rem;
        color: #0f172a;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
</style>
    
<div class="container-fluid px-4 py-4" style="max-width: 1200px; margin: 0 auto;">
    
    <div class="page-heading">
        📋 Danh Sách Vai Trò
    </div>
    
    <div class="card role-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table role-table">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 8%;">STT</th>
                            <th class="text-start" style="width: 27%;">Vai trò hệ thống</th>
                            <th class="text-start" style="width: 35%;">Mô tả chi tiết</th>
                            <th class="text-center" style="width: 15%;">Phân loại</th>
                            <th class="text-center" style="width: 15%;">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vaiTros as $key => $vt)
                            @php
                                $trangThai = (int)($vt->trang_thai ?? 1);
                            @endphp
                            <tr style="{{ $trangThai === 0 ? 'opacity:.65' : '' }}">
                                <td class="text-center" style="font-weight:600;">
                                    {{ sprintf('%02d', $key + 1) }}
                                </td>
                                <td class="text-start">
                                    <div class="role-name">
                                        {{ $vt->ten_hien_thi }}
                                    </div>
                                    <div class="role-code">
                                        {{ $vt->name }}
                                    </div>
                                </td>
                                <td class="text-start">
                                    <div class="role-desc">
                                        {{ $vt->mo_ta ?: 'Chưa cập nhật nội dung mô tả cấu hình...' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($vt->la_vai_tro_he_thong)
                                        <span class="custom-badge" style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca;">
                                            🛡️ Hệ thống
                                        </span>
                                    @else
                                        <span class="custom-badge" style="background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0;">
                                            ⚙️ Tùy biến
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($trangThai === 1)
                                        <span class="custom-badge" style="background:#ecfdf5; color:#10b981; border:1px solid #d1fae5;">
                                            ● Hoạt động
                                        </span>
                                    @else
                                        <span class="custom-badge" style="background:#fff7ed; color:#ea580c; border:1px solid #fed7aa;">
                                            ● Tạm khóa
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div style="color:#94a3b8;">
                                        📭 Không tìm thấy dữ liệu vai trò phù hợp
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    
        @if(method_exists($vaiTros, 'links'))
            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-end">
                    {{ $vaiTros->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>

</div>

@endsection