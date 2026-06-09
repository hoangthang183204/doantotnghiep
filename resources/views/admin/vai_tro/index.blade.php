@extends('layouts.admin')

@section('title', 'Quản lý Vai trò')

@section('content')

<style>
    .role-card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 2px 18px rgba(15,23,42,.05);
        background-color: #ffffff;
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
        color: #334155;
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

    /* Đưa inline style thành class để dễ custom Dark Mode */
    .badge-system { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .badge-custom { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
    .badge-active { background: #ecfdf5; color: #10b981; border: 1px solid #d1fae5; }
    .badge-locked { background: #fff7ed; color: #ea580c; border: 1px solid #fed7aa; }
    .empty-text { color: #94a3b8; }

    .page-heading {
        font-weight: 700;
        font-size: 1.25rem;
        color: #0f172a;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* =========================================
       DARK MODE OVERRIDES
       ========================================= */
    .dark .role-card {
        background-color: #1f2937;
        box-shadow: none;
    }

    .dark .page-heading {
        color: #f9fafb;
    }

    .dark .role-table thead th {
        background: rgba(17, 24, 39, 0.5);
        color: #e5e7eb;
        border-bottom-color: #374151;
    }

    .dark .role-table td {
        border-bottom-color: #374151;
        color: #e5e7eb;
    }

    .dark .role-table tbody tr:hover {
        background: rgba(55, 65, 81, 0.5);
    }

    .dark .role-table tbody tr:nth-child(even) {
        background: rgba(31, 41, 55, 0.5);
    }

    .dark .role-name {
        color: #ffffff;
    }

    .dark .role-desc {
        color: #9ca3af;
    }

    .dark .role-code {
        background: rgba(37, 99, 235, 0.2);
        color: #93c5fd;
        border-color: rgba(37, 99, 235, 0.3);
    }

    .dark .card-footer {
        background-color: #1f2937 !important;
        border-top: 1px solid #374151 !important;
    }

    /* Dark Mode cho các Badge */
    .dark .badge-system { background: rgba(220, 38, 38, 0.15); color: #f87171; border-color: rgba(220, 38, 38, 0.3); }
    .dark .badge-custom { background: rgba(22, 163, 74, 0.15); color: #4ade80; border-color: rgba(22, 163, 74, 0.3); }
    .dark .badge-active { background: rgba(16, 185, 129, 0.15); color: #34d399; border-color: rgba(16, 185, 129, 0.3); }
    .dark .badge-locked { background: rgba(234, 88, 12, 0.15); color: #fb923c; border-color: rgba(234, 88, 12, 0.3); }
    
    .dark .empty-text { color: #cbd5e1; }
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
                                        <span class="custom-badge badge-system">
                                            🛡️ Hệ thống
                                        </span>
                                    @else
                                        <span class="custom-badge badge-custom">
                                            ⚙️ Tùy biến
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($trangThai === 1)
                                        <span class="custom-badge badge-active">
                                            ● Hoạt động
                                        </span>
                                    @else
                                        <span class="custom-badge badge-locked">
                                            ● Tạm khóa
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="empty-text">
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