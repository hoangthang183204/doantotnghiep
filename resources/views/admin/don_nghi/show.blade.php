@extends('layouts.admin')

@section('title', 'Chi tiết đơn xin nghỉ - ' . $donNghi->ma_don_nghi)

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">📋 Chi tiết đơn xin nghỉ</h1>
                <div class="flex items-center gap-3 mt-1">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Mã đơn:</span>
                    <span class="font-semibold text-gray-800 dark:text-white">{{ $donNghi->ma_don_nghi }}</span>
                    @if($donNghi->trang_thai == 'cho_duyet')
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">⏳ Chờ duyệt</span>
                    @elseif($donNghi->trang_thai == 'da_duyet')
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs">✅ Đã duyệt</span>
                    @else
                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs">❌ Từ chối</span>
                    @endif
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.don_nghi.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Quay lại
                </a>
                @if($donNghi->trang_thai == 'cho_duyet')
                    <form action="{{ route('admin.don_nghi.duyet', $donNghi->id) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="trang_thai" value="da_duyet">
                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition flex items-center gap-2">
                            ✅ Duyệt
                        </button>
                    </form>
                    <button onclick="showTuChoiModal()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition flex items-center gap-2">
                        ❌ Từ chối
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- THÔNG TIN NHÂN VIÊN --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <h2 class="font-semibold text-gray-800 dark:text-white">👤 Thông tin nhân viên</h2>
        </div>
        <div class="p-6">
            <div class="flex items-center gap-4 mb-4">
                @php
                    $avatar = optional($donNghi->nguoi_dung->hoSo)->anh_dai_dien 
                        ? asset('storage/' . optional($donNghi->nguoi_dung->hoSo)->anh_dai_dien)
                        : 'https://ui-avatars.com/api/?background=3b82f6&color=fff&name=' . urlencode(optional($donNghi->nguoi_dung->hoSo)->ho . ' ' . optional($donNghi->nguoi_dung->hoSo)->ten);
                @endphp
                <img src="{{ $avatar }}" alt="Avatar" class="w-16 h-16 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">
                <div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                        {{ optional($donNghi->nguoi_dung->hoSo)->ho ?? '' }} {{ optional($donNghi->nguoi_dung->hoSo)->ten ?? '' }}
                    </h3>
                    <div class="flex flex-wrap gap-3 text-sm text-gray-500 dark:text-gray-400">
                        <span>Mã NV: {{ optional($donNghi->nguoi_dung->hoSo)->ma_nhan_vien ?? 'N/A' }}</span>
                        <span>•</span>
                        <span>Phòng: {{ optional($donNghi->nguoi_dung->phongBan)->ten_phong_ban ?? 'N/A' }}</span>
                        <span>•</span>
                        <span>Chức vụ: {{ optional($donNghi->nguoi_dung->chucVu)->ten ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- THÔNG TIN ĐƠN NGHỈ --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <h2 class="font-semibold text-gray-800 dark:text-white">📋 Thông tin đơn nghỉ</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Loại nghỉ phép</span>
                        <p class="font-semibold text-gray-800 dark:text-white">{{ optional($donNghi->loai_nghi_phep)->ten ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Số ngày nghỉ</span>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($donNghi->so_ngay_nghi, 0) }} ngày</p>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Từ ngày</span>
                        <p class="font-semibold text-gray-800 dark:text-white">{{ $donNghi->ngay_bat_dau->format('d/m/Y') }}</p>
                        <span class="text-xs text-gray-400">{{ $donNghi->ngay_bat_dau->locale('vi')->dayName }}</span>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Đến ngày</span>
                        <p class="font-semibold text-gray-800 dark:text-white">{{ $donNghi->ngay_ket_thuc->format('d/m/Y') }}</p>
                        <span class="text-xs text-gray-400">{{ $donNghi->ngay_ket_thuc->locale('vi')->dayName }}</span>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Ngày tạo</span>
                        <p class="font-semibold text-gray-800 dark:text-white">{{ $donNghi->created_at->format('d/m/Y H:i') }}</p>
                        <span class="text-xs text-gray-400">{{ $donNghi->created_at->locale('vi')->diffForHumans() }}</span>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Người bàn giao</span>
                        <p class="font-semibold text-gray-800 dark:text-white">
                            {{ optional($donNghi->ban_giao_cho->hoSo)->ho ?? '' }} {{ optional($donNghi->ban_giao_cho->hoSo)->ten ?? 'Không có' }}
                        </p>
                    </div>
                    @if($donNghi->ghi_chu_ban_giao)
                        <div>
                            <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Ghi chú bàn giao</span>
                            <p class="font-semibold text-gray-800 dark:text-white">{{ $donNghi->ghi_chu_ban_giao }}</p>
                        </div>
                    @endif
                    @if($donNghi->lien_he_khan_cap)
                        <div>
                            <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Liên hệ khẩn cấp</span>
                            <p class="font-semibold text-gray-800 dark:text-white">{{ $donNghi->lien_he_khan_cap }} - {{ $donNghi->sdt_khan_cap }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Lý do --}}
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block mb-2">📝 Lý do nghỉ</span>
                <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $donNghi->ly_do }}</p>
                </div>
            </div>

            {{-- Lý do từ chối --}}
            @if($donNghi->trang_thai == 'tu_choi' && $donNghi->ghi_chu)
                <div class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                    <span class="text-xs font-medium text-red-700 dark:text-red-300 uppercase tracking-wider block">📌 Lý do từ chối</span>
                    <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $donNghi->ghi_chu }}</p>
                </div>
            @endif

            {{-- File đính kèm --}}
            @if($donNghi->tai_lieu_ho_tro)
                <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <span class="text-xs font-medium text-blue-700 dark:text-blue-300 uppercase tracking-wider block">📎 File đính kèm</span>
                    @php
                        $files = is_array($donNghi->tai_lieu_ho_tro) ? $donNghi->tai_lieu_ho_tro : json_decode($donNghi->tai_lieu_ho_tro, true);
                    @endphp
                    @if($files && count($files) > 0)
                        <div class="flex flex-wrap gap-2 mt-1">
                            @foreach($files as $file)
                                <a href="{{ asset('storage/' . $file) }}" target="_blank" 
                                    class="text-sm text-blue-600 hover:underline flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                    </svg>
                                    {{ basename($file) }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL TỪ CHỐI --}}
<div id="tuChoiModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md p-6 animate-fadeIn">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">❌ Từ chối đơn xin nghỉ</h3>
            <button onclick="closeTuChoiModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.don_nghi.duyet', $donNghi->id) }}">
            @csrf
            <input type="hidden" name="trang_thai" value="tu_choi">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lý do từ chối <span class="text-red-500">*</span></label>
                <textarea name="ly_do_tu_choi" id="ly_do_tu_choi" rows="4" 
                    placeholder="Nhập lý do từ chối..."
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-400" required></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeTuChoiModal()" 
                    class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                    Hủy
                </button>
                <button type="submit" 
                    class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                    Xác nhận từ chối
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function showTuChoiModal() {
        const modal = document.getElementById('tuChoiModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.getElementById('ly_do_tu_choi').value = '';
    }

    function closeTuChoiModal() {
        const modal = document.getElementById('tuChoiModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('tuChoiModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeTuChoiModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeTuChoiModal();
    });
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.2s ease-out;
    }
</style>
@endsection