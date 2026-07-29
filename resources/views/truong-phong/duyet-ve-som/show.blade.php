{{-- resources/views/truong-phong/duyet-ve-som/show.blade.php --}}

@php
    $item = $donVeSom ?? $don ?? null;
@endphp

@extends('layouts.admin')

@section('title', 'Chi tiết đơn xin về sớm')

@section('content')
<div class="max-w-4xl mx-auto">

    @if(!$item)
        <div class="p-6 bg-red-50 text-red-600 rounded-xl border border-red-200 text-center font-medium">
            Không tìm thấy thông tin đơn xin về sớm này!
        </div>
    @else

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="fas fa-file-alt text-blue-600"></i> Chi tiết đơn xin về sớm
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Mã đơn: <span class="font-medium text-gray-700 dark:text-gray-300">DVS{{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}</span></p>
        </div>
        <a href="{{ route('truong-phong.duyet-ve-som.index') }}" 
           class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition inline-flex items-center">
            <i class="fas fa-arrow-left mr-1.5"></i> Quay lại
        </a>
    </div>

    {{-- Main Form Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">

        {{-- 1. Thông tin nhân viên --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="fas fa-user text-blue-600"></i> Thông tin nhân viên
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Họ tên</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $item->nguoiDung->hoSo->ho ?? '' }} {{ $item->nguoiDung->hoSo->ten ?? $item->nguoiDung->ten_dang_nhap ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Mã nhân viên</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $item->nguoiDung->hoSo->ma_nhan_vien ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Phòng ban</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $item->nguoiDung->phongBan->ten_phong_ban ?? $item->nguoiDung->hoSo->phongBan->ten_phong_ban ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Chức vụ</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $item->nguoiDung->chucVu->ten ?? $item->nguoiDung->hoSo->chucVu->ten_chuc_vu ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- 2. Thông tin đơn --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-blue-600"></i> Thông tin đơn
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ngày làm việc</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $item->ngay ? \Carbon\Carbon::parse($item->ngay)->format('d/m/Y') : 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Giờ ra dự kiến</p>
                    <p class="font-medium text-blue-600 dark:text-blue-400">
                        {{ $item->gio_ra_du_kien ? \Carbon\Carbon::parse($item->gio_ra_du_kien)->format('H:i') : 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Số phút về sớm</p>
                    <p class="font-medium text-amber-600 dark:text-amber-400">
                        {{ $item->so_phut_ve_som ?? 0 }} phút
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Thời gian tạo đơn</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('H:i d/m/Y') : 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- 3. Lý do về sớm --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                <i class="fas fa-pen text-blue-600"></i> Lý do về sớm
            </h3>
            <p class="text-gray-700 dark:text-gray-300">
                {{ $item->ly_do ?? 'Không có lý do chi tiết' }}
            </p>
        </div>

        {{-- 4. Trạng thái & Thao tác duyệt --}}
        <div class="p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                <i class="fas fa-flag text-blue-600"></i> Trạng thái
            </h3>

            @if(($item->trang_thai ?? '') == 'cho_duyet')
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                        <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2 animate-pulse"></span>
                        Chờ duyệt
                    </span>
                    <div class="flex gap-2">
                        <button onclick="duyetDon({{ $item->id }})" 
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition flex items-center gap-1">
                            <i class="fas fa-check mr-1"></i> Duyệt
                        </button>
                        <button onclick="tuChoiDon({{ $item->id }})" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition flex items-center gap-1">
                            <i class="fas fa-times mr-1"></i> Từ chối
                        </button>
                    </div>
                </div>
            @elseif(($item->trang_thai ?? '') == 'da_duyet')
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                        Đã duyệt
                    </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        bởi {{ $item->nguoiDuyet->hoSo->ho ?? '' }} {{ $item->nguoiDuyet->hoSo->ten ?? $item->nguoiDuyet->ten_dang_nhap ?? 'Hệ thống' }}
                        lúc {{ $item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') : '' }}
                    </span>
                </div>
            @elseif(($item->trang_thai ?? '') == 'tu_choi')
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                        <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                        Từ chối
                    </span>
                    @if(!empty($item->ly_do_tu_choi))
                        <span class="text-sm text-red-600 dark:text-red-400">Lý do: {{ $item->ly_do_tu_choi }}</span>
                    @endif
                </div>
            @endif
        </div>

    </div>
    @endif
</div>

@push('scripts')
<script>
function duyetDon(id) {
    if (!confirm('Bạn có chắc muốn duyệt đơn xin về sớm này?')) return;
    
    fetch(`/truong-phong/duyet-ve-som/${id}/duyet`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('✅ ' + data.message, 'success');
            setTimeout(() => location.reload(), 800);
        } else {
            showToast('❌ ' + data.message, 'error');
        }
    })
    .catch(() => showToast('❌ Có lỗi xảy ra', 'error'));
}

function tuChoiDon(id) {
    const lyDo = prompt('Nhập lý do từ chối:');
    if (lyDo === null) return;
    if (lyDo.trim() === '') {
        showToast('⚠️ Vui lòng nhập lý do từ chối', 'warning');
        return;
    }
    
    fetch(`/truong-phong/duyet-ve-som/${id}/tu-choi`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ ly_do_tu_choi: lyDo })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('✅ ' + data.message, 'success');
            setTimeout(() => location.reload(), 800);
        } else {
            showToast('❌ ' + data.message, 'error');
        }
    })
    .catch(() => showToast('❌ Có lỗi xảy ra', 'error'));
}

function showToast(message, type = 'success') {
    const colors = { success: 'bg-green-500', error: 'bg-red-500', warning: 'bg-yellow-500' };
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 ${colors[type] || 'bg-blue-500'} text-white px-6 py-3 rounded-xl shadow-lg z-50 transition-all duration-300 text-sm font-medium`;
    toast.innerHTML = message;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>
@endpush
@endsection