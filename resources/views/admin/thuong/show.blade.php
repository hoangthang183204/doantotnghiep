@extends('layouts.admin')

@section('title', 'Chi tiết khoản thưởng')

@php
    $nv = $thuong->nguoiDung;
    $hoTen = trim(($nv->ho_so->ho ?? '') . ' ' . ($nv->ho_so->ten ?? '')) ?: ($nv->ten_dang_nhap ?? 'N/A');
@endphp

@section('content')
<div class="min-h-screen p-6 bg-gray-50 dark:bg-slate-900">
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex flex-wrap justify-between items-start gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Chi tiết khoản thưởng</h1>
            <p class="text-gray-500 dark:text-slate-400 mt-1">{{ $hoTen }} — {{ $thuong->loaiThuong->ten ?? 'Thưởng' }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.thuong.edit', $thuong->id) }}"
               class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg shadow-sm">
                <i class="fa-solid fa-pen-to-square mr-1"></i> Sửa
            </a>
            <a href="{{ route('admin.thuong.index') }}"
               class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-300 rounded-lg hover:opacity-80">
                <i class="fa-solid fa-arrow-left mr-1"></i> Quay lại
            </a>
        </div>
    </div>

    @include('layouts.partials.alerts')

    {{-- Số tiền --}}
    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-8 py-5 flex justify-between items-center shadow-sm">
        <div>
            <p class="text-gray-500 dark:text-slate-400 text-sm">Thành tiền áp dụng vào lương</p>
            <p class="text-2xl font-extrabold text-green-600 dark:text-green-400 mt-2">+{{ number_format($soTien) }} đ</p>
        </div>
        <div class="text-right">
            <span class="px-3 py-1.5 text-sm rounded-full font-medium {{ $thuong->trang_thai_badge }}">{{ $thuong->trang_thai_text }}</span>
        </div>
    </div>

    {{-- Thông tin --}}
    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-700">
            <h2 class="font-semibold text-gray-900 dark:text-white">
                <i class="fa-solid fa-circle-info mr-2 text-blue-500"></i>Thông tin khoản thưởng
            </h2>
        </div>
        <dl class="divide-y divide-gray-100 dark:divide-slate-700 text-sm">
            <div class="px-5 py-3 flex justify-between gap-4">
                <dt class="text-gray-500 dark:text-slate-400">Nhân viên</dt>
                <dd class="font-medium text-gray-900 dark:text-white">{{ $hoTen }}</dd>
            </div>
            <div class="px-5 py-3 flex justify-between gap-4">
                <dt class="text-gray-500 dark:text-slate-400">Loại thưởng</dt>
                <dd class="font-medium text-gray-900 dark:text-white">{{ $thuong->loaiThuong->ten ?? '—' }}</dd>
            </div>
            <div class="px-5 py-3 flex justify-between gap-4">
                <dt class="text-gray-500 dark:text-slate-400">Hình thức</dt>
                <dd><span class="px-2.5 py-1 text-xs rounded-full font-medium {{ $thuong->hinh_thuc_badge }}">{{ $thuong->hinh_thuc_text }}</span></dd>
            </div>
            <div class="px-5 py-3 flex justify-between gap-4">
                <dt class="text-gray-500 dark:text-slate-400">Phạm vi áp dụng</dt>
                <dd class="font-medium text-gray-900 dark:text-white">{{ $thuong->pham_vi_text }}</dd>
            </div>
            <div class="px-5 py-3 flex justify-between gap-4">
                <dt class="text-gray-500 dark:text-slate-400">Cách tính</dt>
                <dd class="font-medium text-gray-900 dark:text-white">
                    {{ \App\Models\LoaiThuong::$cachTinhLabels[$thuong->cach_tinh] ?? $thuong->cach_tinh }} — {{ $thuong->gia_tri_text }}
                    @if($thuong->cach_tinh === 'phan_tram_luong_cb')
                        <span class="block text-xs text-gray-400 mt-0.5">
                            = {{ number_format((float) ($nv->luong_co_ban ?? 0)) }} đ × {{ rtrim(rtrim(number_format((float) $thuong->gia_tri, 2), '0'), '.') }}%
                        </span>
                    @endif
                </dd>
            </div>
            <div class="px-5 py-3 flex justify-between gap-4">
                <dt class="text-gray-500 dark:text-slate-400">Thuế TNCN</dt>
                <dd class="font-medium text-gray-900 dark:text-white">
                    {{ $thuong->chiuThueThucTe() ? 'Tính vào thu nhập chịu thuế' : 'Không chịu thuế' }}
                    @if($thuong->chiu_thue === null)
                        <span class="text-xs text-gray-400">(theo loại thưởng)</span>
                    @endif
                </dd>
            </div>
            <div class="px-5 py-3 flex justify-between gap-4">
                <dt class="text-gray-500 dark:text-slate-400">Lý do</dt>
                <dd class="font-medium text-gray-900 dark:text-white">{{ $thuong->ly_do ?: '—' }}</dd>
            </div>
            <div class="px-5 py-3 flex justify-between gap-4">
                <dt class="text-gray-500 dark:text-slate-400">Người tạo</dt>
                <dd class="font-medium text-gray-900 dark:text-white">
                    {{ $thuong->nguoiTao->ten_dang_nhap ?? '—' }}
                    <span class="text-xs text-gray-400">{{ $thuong->created_at?->format('d/m/Y H:i') }}</span>
                </dd>
            </div>
        </dl>
    </div>

    <p class="text-xs text-gray-400">
        <i class="fa-solid fa-circle-info mr-1"></i>
        Số tiền trên được quy đổi theo lương cơ bản hiện tại của nhân viên. Khi tính lương, hệ thống lấy lương cơ bản
        của đúng kỳ lương đó nên con số có thể khác nếu nhân viên được điều chỉnh lương.
    </p>

</div>
</div>
@endsection
