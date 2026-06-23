{{-- resources/views/admin/don_nghi/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Chi tiết đơn xin nghỉ - ' . $donNghi->ma_don_nghi)

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
   {{-- Phần Header --}}
<div class="flex gap-2">
    <a href="{{ route('admin.don_nghi.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Quay lại
    </a>
    
    {{-- CHỈ HIỂN THỊ NÚT DUYỆT/TỪ CHỐI KHI ĐƠN Ở TRẠNG THÁI CHỜ DUYỆT --}}
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
    @elseif($donNghi->trang_thai == 'huy_bo')
        <span class="px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed">
            🚫 Đã hủy
        </span>
    @endif
</div>
    {{-- THÔNG TIN NHÂN VIÊN --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <h2 class="font-semibold text-gray-800 dark:text-white">👤 Thông tin nhân viên</h2>
        </div>
        <div class="p-6">
            <div class="flex items-center gap-4 mb-4">
                @php
                    $avatar = optional($donNghi->nguoiDung->hoSo)->anh_dai_dien 
                        ? asset('storage/' . optional($donNghi->nguoiDung->hoSo)->anh_dai_dien)
                        : 'https://ui-avatars.com/api/?background=3b82f6&color=fff&name=' . urlencode(optional($donNghi->nguoiDung->hoSo)->ho . ' ' . optional($donNghi->nguoiDung->hoSo)->ten);
                @endphp
                <img src="{{ $avatar }}" alt="Avatar" class="w-16 h-16 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">
                <div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                        {{ optional($donNghi->nguoiDung->hoSo)->ho ?? '' }} {{ optional($donNghi->nguoiDung->hoSo)->ten ?? '' }}
                    </h3>
                    <div class="flex flex-wrap gap-3 text-sm text-gray-500 dark:text-gray-400">
                        <span>Mã NV: {{ optional($donNghi->nguoiDung->hoSo)->ma_nhan_vien ?? 'N/A' }}</span>
                        <span>•</span>
                        <span>Phòng: {{ optional($donNghi->nguoiDung->phongBan)->ten_phong_ban ?? 'N/A' }}</span>
                        <span>•</span>
                        <span>Chức vụ: {{ optional($donNghi->nguoiDung->chucVu)->ten ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- THÔNG TIN ĐƠN NGHỈ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Cột trái: Thông tin đơn nghỉ --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h2 class="font-semibold text-gray-800 dark:text-white">📋 Thông tin đơn nghỉ</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Loại nghỉ phép</span>
                                <p class="font-semibold text-gray-800 dark:text-white">{{ optional($donNghi->loaiNghiPhep)->ten ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Số ngày nghỉ</span>
                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($donNghi->so_ngay_nghi, 1) }} ngày</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Từ ngày</span>
                                <p class="font-semibold text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($donNghi->ngay_bat_dau)->format('d/m/Y') }}</p>
                                <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($donNghi->ngay_bat_dau)->locale('vi')->dayName }}</span>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Đến ngày</span>
                                <p class="font-semibold text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($donNghi->ngay_ket_thuc)->format('d/m/Y') }}</p>
                                <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($donNghi->ngay_ket_thuc)->locale('vi')->dayName }}</span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Ngày tạo</span>
                                <p class="font-semibold text-gray-800 dark:text-white">{{ $donNghi->created_at->format('d/m/Y H:i') }}</p>
                                <span class="text-xs text-gray-400">{{ $donNghi->created_at->locale('vi')->diffForHumans() }}</span>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Trạng thái</span>
                                @if($donNghi->trang_thai == 'cho_duyet')
                                    <span class="inline-flex items-center rounded-md bg-yellow-50 dark:bg-yellow-900/30 px-2.5 py-1 text-xs font-medium text-yellow-800 dark:text-yellow-400">⏳ Chờ duyệt</span>
                                @elseif($donNghi->trang_thai == 'da_duyet')
                                    <span class="inline-flex items-center rounded-md bg-green-50 dark:bg-green-900/30 px-2.5 py-1 text-xs font-medium text-green-700 dark:text-green-400">✅ Đã duyệt</span>
                                @elseif($donNghi->trang_thai == 'tu_choi')
                                    <span class="inline-flex items-center rounded-md bg-red-50 dark:bg-red-900/30 px-2.5 py-1 text-xs font-medium text-red-700 dark:text-red-400">❌ Từ chối</span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-gray-50 dark:bg-gray-900/30 px-2.5 py-1 text-xs font-medium text-gray-700 dark:text-gray-400">🚫 Đã hủy</span>
                                @endif
                            </div>
                            @if($donNghi->banGiaoCho)
                                <div>
                                    <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Người bàn giao</span>
                                    <p class="font-semibold text-gray-800 dark:text-white">
                                        {{ optional($donNghi->banGiaoCho->hoSo)->ho ?? '' }} {{ optional($donNghi->banGiaoCho->hoSo)->ten ?? '' }}
                                    </p>
                                </div>
                            @endif
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

                    {{-- Ghi chú --}}
                    @if($donNghi->ghi_chu)
                        <div class="mt-4">
                            <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block mb-2">📌 Ghi chú</span>
                            <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $donNghi->ghi_chu }}</p>
                            </div>
                        </div>
                    @endif

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

        {{-- Cột phải: Thông tin duyệt và số dư --}}
        <div class="lg:col-span-1 space-y-4">
            {{-- Thông tin duyệt --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h2 class="font-semibold text-gray-800 dark:text-white">✅ Thông tin duyệt</h2>
                </div>
                <div class="p-4 space-y-4">
                    @if($donNghi->trang_thai == 'da_duyet')
                        <div>
                            <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Người duyệt</span>
                            <p class="font-semibold text-gray-800 dark:text-white">
                                @if($donNghi->nguoiDuyet)
                                    {{ optional($donNghi->nguoiDuyet->hoSo)->ho ?? '' }} {{ optional($donNghi->nguoiDuyet->hoSo)->ten ?? $donNghi->nguoiDuyet->ten_dang_nhap }}
                                @else
                                    <span class="text-gray-400">Chưa có</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Thời gian duyệt</span>
                            <p class="font-semibold text-gray-800 dark:text-white">
                                @if($donNghi->thoi_gian_duyet)
                                    {{ \Carbon\Carbon::parse($donNghi->thoi_gian_duyet)->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-gray-400">Chưa có</span>
                                @endif
                            </p>
                        </div>
                    @elseif($donNghi->trang_thai == 'tu_choi')
                        <div>
                            <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Người từ chối</span>
                            <p class="font-semibold text-gray-800 dark:text-white">
                                @if($donNghi->nguoiDuyet)
                                    {{ optional($donNghi->nguoiDuyet->hoSo)->ho ?? '' }} {{ optional($donNghi->nguoiDuyet->hoSo)->ten ?? $donNghi->nguoiDuyet->ten_dang_nhap }}
                                @else
                                    <span class="text-gray-400">Chưa có</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Thời gian từ chối</span>
                            <p class="font-semibold text-gray-800 dark:text-white">
                                @if($donNghi->thoi_gian_duyet)
                                    {{ \Carbon\Carbon::parse($donNghi->thoi_gian_duyet)->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-gray-400">Chưa có</span>
                                @endif
                            </p>
                        </div>
                        @if($donNghi->ghi_chu)
                            <div>
                                <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Lý do từ chối</span>
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $donNghi->ghi_chu }}</p>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-6">
                            <div class="w-16 h-16 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">⏳ Đang chờ duyệt</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Vui lòng chờ phê duyệt</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Số dư nghỉ phép --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h2 class="font-semibold text-gray-800 dark:text-white">📊 Số dư nghỉ phép</h2>
                </div>
                <div class="p-4">
                    @php
                        $soNgayPhepNam = 12;
                        $soNgayDaNghi = App\Models\DonXinNghi::where('nguoi_dung_id', $donNghi->nguoi_dung_id)
                            ->where('trang_thai', 'da_duyet')
                            ->whereYear('ngay_bat_dau', now()->year)
                            ->sum('so_ngay_nghi');
                        $soDuConLai = max(0, $soNgayPhepNam - $soNgayDaNghi);
                    @endphp
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Còn lại</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ number_format($soDuConLai, 1) }}</p>
                            <p class="text-xs text-gray-400">/ {{ $soNgayPhepNam }} ngày</p>
                        </div>
                        <div class="w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    {{-- Thanh tiến trình --}}
                    <div class="mt-3">
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            @php
                                $phanTram = $soNgayPhepNam > 0 ? (($soDuConLai) / $soNgayPhepNam) * 100 : 0;
                            @endphp
                            <div class="bg-green-500 h-2 rounded-full transition-all duration-500" 
                                 style="width: {{ min(100, $phanTram) }}%"></div>
                        </div>
                        <div class="flex justify-between mt-1">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Đã nghỉ {{ number_format($soNgayDaNghi, 1) }} ngày</span>
                            <span class="text-xs text-green-600 dark:text-green-400 font-medium">{{ number_format($soDuConLai, 1) }} ngày</span>
                        </div>
                    </div>
                </div>
            </div>
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