{{-- resources/views/truong-phong/duyet-don/show.blade.php --}}

@extends('layouts.admin')

@section('title', 'Chi tiết đơn nghỉ phép')

@section('content')
<div class="max-w-4xl mx-auto">
    
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-file-alt mr-3 text-blue-600"></i>
                Chi tiết đơn nghỉ phép
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Mã đơn: <span class="font-medium">{{ $donNghi->ma_don_nghi }}</span></p>
        </div>
        <a href="{{ route('duyet-don.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition">
            <i class="fas fa-arrow-left mr-1"></i> Quay lại
        </a>
    </div>

    {{-- Nội dung --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        {{-- Thông tin nhân viên --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-user mr-2 text-blue-600"></i> Thông tin nhân viên
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Họ tên</p>
                    <p class="font-medium">{{ $donNghi->nguoiDung->hoSo->ho ?? '' }} {{ $donNghi->nguoiDung->hoSo->ten ?? '' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Mã nhân viên</p>
                    <p class="font-medium">{{ $donNghi->nguoiDung->hoSo->ma_nhan_vien ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Phòng ban</p>
                    <p class="font-medium">{{ $donNghi->nguoiDung->phongBan->ten_phong_ban ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Chức vụ</p>
                    <p class="font-medium">{{ $donNghi->nguoiDung->chucVu->ten ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        {{-- Thông tin đơn --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i> Thông tin đơn
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Loại nghỉ</p>
                    <p class="font-medium">{{ $donNghi->loaiNghiPhep->ten ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ngày bắt đầu</p>
                    <p class="font-medium">{{ Carbon\Carbon::parse($donNghi->ngay_bat_dau)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ngày kết thúc</p>
                    <p class="font-medium">{{ Carbon\Carbon::parse($donNghi->ngay_ket_thuc)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Số ngày</p>
                    <p class="font-medium text-blue-600">{{ $donNghi->so_ngay_nghi }} ngày</p>
                </div>
            </div>
        </div>

        {{-- Lý do --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">
                <i class="fas fa-pen mr-2 text-blue-600"></i> Lý do nghỉ
            </h3>
            <p class="text-gray-700 dark:text-gray-300">{{ $donNghi->ly_do }}</p>
            @if($donNghi->ghi_chu)
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    <span class="font-medium">Ghi chú:</span> {{ $donNghi->ghi_chu }}
                </p>
            @endif
        </div>

        {{-- Trạng thái --}}
        <div class="p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">
                <i class="fas fa-flag mr-2 text-blue-600"></i> Trạng thái
            </h3>
            <div class="flex items-center gap-3">
                @if($donNghi->trang_thai == 'cho_duyet')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                        <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2 animate-pulse"></span>
                        Chờ duyệt
                    </span>
                    <div class="flex gap-2">
                        <button onclick="duyetDon({{ $donNghi->id }})" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition">
                            <i class="fas fa-check mr-1"></i> Duyệt
                        </button>
                        <button onclick="tuChoiDon({{ $donNghi->id }})" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">
                            <i class="fas fa-times mr-1"></i> Từ chối
                        </button>
                    </div>
                @elseif($donNghi->trang_thai == 'da_duyet')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                        Đã duyệt
                    </span>
                    @if($donNghi->nguoiDuyet)
                        <span class="text-sm text-gray-500">
                            bởi {{ $donNghi->nguoiDuyet->hoSo->ho ?? '' }} {{ $donNghi->nguoiDuyet->hoSo->ten ?? '' }}
                            lúc {{ Carbon\Carbon::parse($donNghi->thoi_gian_duyet)->format('d/m/Y H:i') }}
                        </span>
                    @endif
                @elseif($donNghi->trang_thai == 'tu_choi')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                        <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                        Từ chối
                    </span>
                    @if($donNghi->ghi_chu)
                        <span class="text-sm text-red-600 dark:text-red-400">Lý do: {{ $donNghi->ghi_chu }}</span>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function duyetDon(id) {
    if (!confirm('Bạn có chắc muốn duyệt đơn này?')) return;
    
    fetch(`/duyet-don/${id}/duyet`, {
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
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('❌ ' + data.message, 'error');
        }
    })
    .catch(error => {
        showToast('❌ Có lỗi xảy ra', 'error');
    });
}

function tuChoiDon(id) {
    const lyDo = prompt('Nhập lý do từ chối:');
    if (lyDo === null) return;
    if (lyDo.trim() === '') {
        showToast('⚠️ Vui lòng nhập lý do từ chối', 'warning');
        return;
    }
    
    fetch(`/duyet-don/${id}/tu-choi`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ ly_do: lyDo })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('✅ ' + data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('❌ ' + data.message, 'error');
        }
    })
    .catch(error => {
        showToast('❌ Có lỗi xảy ra', 'error');
    });
}

function showToast(message, type = 'success') {
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500'
    };
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 ${colors[type] || 'bg-blue-500'} text-white px-6 py-3 rounded-xl shadow-lg z-50 transition-all duration-300`;
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