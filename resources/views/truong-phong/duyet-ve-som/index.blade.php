{{-- resources/views/truong-phong/duyet-ve-som/index.blade.php --}}

@extends('layouts.admin')

@section('title', 'Duyệt đơn về sớm')

@section('content')
<div class="space-y-6">
    
    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-running mr-3 text-blue-600"></i>
                Duyệt đơn xin về sớm
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Quản lý đơn xin về sớm của nhân viên trong phòng
                @if(isset($phongBan) && $phongBan)
                    <span class="text-blue-600 font-medium">- {{ $phongBan->ten_phong_ban }}</span>
                @endif
            </p>
        </div>
    </div>

    {{-- Bộ lọc --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div>
                <select name="trang_thai" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Tất cả trạng thái</option>
                    <option value="cho_duyet" {{ request('trang_thai') == 'cho_duyet' ? 'selected' : '' }}>Chờ duyệt</option>
                    <option value="da_duyet" {{ request('trang_thai') == 'da_duyet' ? 'selected' : '' }}>Đã duyệt</option>
                    <option value="tu_choi" {{ request('trang_thai') == 'tu_choi' ? 'selected' : '' }}>Từ chối</option>
                </select>
            </div>
            <div>
                <input type="date" name="tu_ngay" value="{{ request('tu_ngay') }}" 
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <input type="date" name="den_ngay" value="{{ request('den_ngay') }}" 
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                <i class="fas fa-search mr-1"></i> Lọc
            </button>
            <a href="{{ route('truong-phong.duyet-ve-som.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition">
                <i class="fas fa-redo mr-1"></i> Reset
            </a>
        </form>
    </div>

    {{-- Danh sách đơn --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nhân viên</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Ngày xin về</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Giờ ra dự kiến</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Số phút về sớm</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Lý do</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Trạng thái</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @forelse($donVeSoms as $don)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 text-xs font-bold">
                                    {{ substr($don->nguoiDung->hoSo->ten ?? $don->nguoiDung->ten_dang_nhap ?? 'N', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-white">
                                        {{ $don->nguoiDung->hoSo->ho ?? '' }} {{ $don->nguoiDung->hoSo->ten ?? '' }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $don->nguoiDung->hoSo->ma_nhan_vien ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                            {{ \Carbon\Carbon::parse($don->ngay)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($don->gio_ra_du_kien)->format('H:i') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-1 bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 rounded-md text-xs font-semibold">
                                {{ $don->so_phut_ve_som }} phút
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300 max-w-xs truncate" title="{{ $don->ly_do }}">
                            {{ $don->ly_do }}
                        </td>
                        <td class="px-4 py-3">
                            @if($don->trang_thai == 'cho_duyet')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                                    <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5"></span>
                                    Chờ duyệt
                                </span>
                            @elseif($don->trang_thai == 'da_duyet')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                    Đã duyệt
                                </span>
                            @elseif($don->trang_thai == 'tu_choi')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                    Từ chối
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                {{-- Nút Xem Chi Tiết -> Dẫn tới file show.blade.php --}}
                                <a href="{{ route('truong-phong.duyet-ve-som.show', $don->id) }}" 
                                   class="inline-flex items-center px-2.5 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 rounded-lg text-xs font-medium transition" 
                                   title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($don->trang_thai == 'cho_duyet')
                                    <button onclick="duyetDon({{ $don->id }})" 
                                        class="inline-flex items-center px-2.5 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-medium transition" 
                                        title="Duyệt đơn">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button onclick="tuChoiDon({{ $don->id }})" 
                                        class="inline-flex items-center px-2.5 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-medium transition" 
                                        title="Từ chối">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-inbox text-4xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                <p class="font-medium">Không có đơn xin về sớm nào</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($donVeSoms->hasPages())
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
            {{ $donVeSoms->links() }}
        </div>
        @endif
    </div>
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