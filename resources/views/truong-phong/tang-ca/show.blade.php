{{-- resources/views/truong-phong/tang-ca/show.blade.php --}}

@extends('layouts.admin')

@section('title', 'Chi tiết đơn tăng ca')

@section('content')
<div class="max-w-4xl mx-auto">
    
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-clock mr-3 text-blue-600"></i>
                Chi tiết đơn tăng ca
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Mã đơn: <span class="font-medium">#{{ $tangCa->id }}</span></p>
        </div>
        <a href="{{ route('duyet-tang-ca.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition">
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
                    <p class="font-medium">{{ $tangCa->nguoi_dung->hoSo->ho ?? '' }} {{ $tangCa->nguoi_dung->hoSo->ten ?? '' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Mã nhân viên</p>
                    <p class="font-medium">{{ $tangCa->nguoi_dung->hoSo->ma_nhan_vien ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Phòng ban</p>
                    <p class="font-medium">{{ $tangCa->nguoi_dung->phongBan->ten_phong_ban ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Chức vụ</p>
                    <p class="font-medium">{{ $tangCa->nguoi_dung->chucVu->ten ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        {{-- Thông tin tăng ca --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i> Thông tin tăng ca
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ngày tăng ca</p>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($tangCa->ngay_tang_ca)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Giờ bắt đầu</p>
                    <p class="font-medium">{{ $tangCa->gio_bat_dau }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Giờ kết thúc</p>
                    <p class="font-medium">{{ $tangCa->gio_ket_thuc }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Số giờ</p>
                    <p class="font-medium text-blue-600">{{ $tangCa->so_gio_tang_ca }} giờ</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Loại tăng ca</p>
                    <p class="font-medium">
                        @if($tangCa->loai_tang_ca == 'ngay_thuong')
                            Ngày thường
                        @elseif($tangCa->loai_tang_ca == 'ngay_nghi')
                            Ngày nghỉ
                        @else
                            Lễ tết
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Lý do --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">
                <i class="fas fa-pen mr-2 text-blue-600"></i> Lý do tăng ca
            </h3>
            <p class="text-gray-700 dark:text-gray-300">{{ $tangCa->ly_do_tang_ca }}</p>
        </div>

        {{-- Kết quả thực hiện --}}
        @if($tangCa->thuc_hien)
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-tasks mr-2 text-blue-600"></i> Kết quả thực hiện
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Giờ bắt đầu thực tế</p>
                    <p class="font-medium">{{ $tangCa->thuc_hien->gio_bat_dau_thuc_te ?? 'Chưa cập nhật' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Giờ kết thúc thực tế</p>
                    <p class="font-medium">{{ $tangCa->thuc_hien->gio_ket_thuc_thuc_te ?? 'Chưa cập nhật' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Số giờ thực tế</p>
                    <p class="font-medium">{{ $tangCa->thuc_hien->so_gio_tang_ca_thuc_te ?? 0 }} giờ</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Trạng thái</p>
                    <p class="font-medium">
                        @if($tangCa->thuc_hien->trang_thai == 'hoan_thanh')
                            <span class="text-green-600">✅ Hoàn thành</span>
                        @elseif($tangCa->thuc_hien->trang_thai == 'dang_lam')
                            <span class="text-yellow-600">⏳ Đang làm</span>
                        @elseif($tangCa->thuc_hien->trang_thai == 'khong_hoan_thanh')
                            <span class="text-red-600">❌ Không hoàn thành</span>
                        @else
                            <span class="text-gray-600">⏸ Chưa làm</span>
                        @endif
                    </p>
                </div>
            </div>
            @if($tangCa->thuc_hien->cong_viec_da_thuc_hien)
            <div class="mt-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">Công việc đã thực hiện</p>
                <p class="text-gray-700 dark:text-gray-300">{{ $tangCa->thuc_hien->cong_viec_da_thuc_hien }}</p>
            </div>
            @endif
        </div>
        @endif

        {{-- Trạng thái --}}
        <div class="p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">
                <i class="fas fa-flag mr-2 text-blue-600"></i> Trạng thái
            </h3>
            <div class="flex items-center gap-3">
                @if($tangCa->trang_thai == 'cho_duyet')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                        <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2 animate-pulse"></span>
                        Chờ duyệt
                    </span>
                    <div class="flex gap-2">
                        <button onclick="duyetTangCa({{ $tangCa->id }})" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition">
                            <i class="fas fa-check mr-1"></i> Duyệt
                        </button>
                        <button onclick="tuChoiTangCa({{ $tangCa->id }})" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">
                            <i class="fas fa-times mr-1"></i> Từ chối
                        </button>
                    </div>
                @elseif($tangCa->trang_thai == 'da_duyet')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                        Đã duyệt
                    </span>
                    @if($tangCa->nguoi_duyet)
                        <span class="text-sm text-gray-500">
                            bởi {{ $tangCa->nguoi_duyet->hoSo->ho ?? '' }} {{ $tangCa->nguoi_duyet->hoSo->ten ?? '' }}
                            lúc {{ \Carbon\Carbon::parse($tangCa->thoi_gian_duyet)->format('d/m/Y H:i') }}
                        </span>
                    @endif
                @elseif($tangCa->trang_thai == 'tu_choi')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                        <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                        Từ chối
                    </span>
                    @if($tangCa->ly_do_tu_choi)
                        <span class="text-sm text-red-600 dark:text-red-400">Lý do: {{ $tangCa->ly_do_tu_choi }}</span>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function duyetTangCa(id) {
    if (!confirm('Bạn có chắc muốn duyệt đơn tăng ca này?')) return;
    
    fetch(`/duyet-tang-ca/${id}/duyet`, {
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

function tuChoiTangCa(id) {
    const lyDo = prompt('Nhập lý do từ chối:');
    if (lyDo === null) return;
    if (lyDo.trim() === '') {
        showToast('⚠️ Vui lòng nhập lý do từ chối', 'warning');
        return;
    }
    
    fetch(`/duyet-tang-ca/${id}/tu-choi`, {
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