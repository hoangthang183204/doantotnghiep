@extends('layouts.admin') 

@section('content')
<div class="p-4 sm:p-6 lg:p-8 w-full min-h-[80vh] flex flex-col text-gray-900 dark:text-gray-100 transition-colors duration-200">
    
    {{-- HEADER --}}
    <div class="sm:flex sm:items-center mb-6">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">📋 Quản lý đơn xin nghỉ phép</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Danh sách kiểm duyệt đơn xin nghỉ phép của nhân viên.</p>
        </div>
    </div>

    {{-- THỐNG KÊ --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 border-l-[6px] border-l-blue-500 p-4 flex flex-col items-center justify-center">
            <span class="text-2xl font-bold text-gray-800 dark:text-white">{{ $danhSachDon->total() }}</span>
            <span class="text-gray-500 dark:text-gray-300 text-sm font-medium">Tổng số</span>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 border-l-[6px] border-l-yellow-500 p-4 flex flex-col items-center justify-center">
            <span class="text-2xl font-bold text-gray-800 dark:text-white">{{ $countChoDuyet }}</span>
            <span class="text-gray-500 dark:text-gray-300 text-sm font-medium">⏳ Chờ duyệt</span>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 border-l-[6px] border-l-green-500 p-4 flex flex-col items-center justify-center">
            <span class="text-2xl font-bold text-gray-800 dark:text-white">{{ $countDaDuyet }}</span>
            <span class="text-gray-500 dark:text-gray-300 text-sm font-medium">✅ Đã duyệt</span>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 border-l-[6px] border-l-red-500 p-4 flex flex-col items-center justify-center">
            <span class="text-2xl font-bold text-gray-800 dark:text-white">{{ $countTuChoi }}</span>
            <span class="text-gray-500 dark:text-gray-300 text-sm font-medium">❌ Từ chối</span>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 border-l-[6px] border-l-purple-500 p-4 flex flex-col items-center justify-center">
            <span class="text-2xl font-bold text-gray-800 dark:text-white">{{ $countHomNay }}</span>
            <span class="text-gray-500 dark:text-gray-300 text-sm font-medium">📅 Hôm nay</span>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 mb-6">
        <form method="GET" action="{{ route('admin.don_nghi.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Từ khóa --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">🔍 Tìm kiếm</label>
                    <input type="text" name="keyword" value="{{ request('keyword') }}" 
                        placeholder="Mã đơn, tên NV, mã NV..."
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Trạng thái --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">📊 Trạng thái</label>
                    <select name="trang_thai" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Tất cả --</option>
                        <option value="cho_duyet" {{ request('trang_thai') == 'cho_duyet' ? 'selected' : '' }}>⏳ Chờ duyệt</option>
                        <option value="da_duyet" {{ request('trang_thai') == 'da_duyet' ? 'selected' : '' }}>✅ Đã duyệt</option>
                        <option value="tu_choi" {{ request('trang_thai') == 'tu_choi' ? 'selected' : '' }}>❌ Từ chối</option>
                    </select>
                </div>

                {{-- Loại nghỉ phép --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">📌 Loại nghỉ</label>
                    <select name="loai_nghi_phep_id" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Tất cả --</option>
                        @foreach($loaiNghiPheps ?? [] as $loai)
                            <option value="{{ $loai->id }}" {{ request('loai_nghi_phep_id') == $loai->id ? 'selected' : '' }}>
                                {{ $loai->ten }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Ngày tạo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">📅 Từ ngày tạo</label>
                    <input type="date" name="tu_ngay" value="{{ request('tu_ngay') }}" 
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">📅 Đến ngày tạo</label>
                    <input type="date" name="den_ngay" value="{{ request('den_ngay') }}" 
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">📅 Từ ngày nghỉ</label>
                    <input type="date" name="tu_ngay_nghi" value="{{ request('tu_ngay_nghi') }}" 
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">📅 Đến ngày nghỉ</label>
                    <input type="date" name="den_ngay_nghi" value="{{ request('den_ngay_nghi') }}" 
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
                        🔍 Lọc
                    </button>
                    <a href="{{ route('admin.don_nghi.index') }}" class="px-5 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2">
                        ↻ Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="mb-4 p-4 text-sm text-green-800 dark:text-green-100 rounded-lg bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-800/50 flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-green-800 dark:text-green-100 hover:opacity-70">&times;</button>
        </div>
    @endif

    {{-- BẢNG --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 sm:rounded-lg overflow-hidden flex-1">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Mã đơn</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Nhân viên</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Ngày tạo</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Từ ngày</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Đến ngày</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Số ngày</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Trạng thái</th>
                        <th class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900 dark:text-white">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @forelse($danhSachDon as $don)
                    @php
                        $hoTen = optional($don->nguoi_dung->hoSo)->ho . ' ' . optional($don->nguoi_dung->hoSo)->ten;
                        $avatar = optional($don->nguoi_dung->hoSo)->anh_dai_dien 
                            ? asset('storage/' . optional($don->nguoi_dung->hoSo)->anh_dai_dien)
                            : 'https://ui-avatars.com/api/?background=3b82f6&color=fff&name=' . urlencode($hoTen);
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white">
                            <a href="{{ route('admin.don_nghi.show', $don->id) }}" class="hover:text-blue-600 hover:underline">
                                {{ $don->ma_don_nghi }}
                            </a>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                            <div class="flex items-center gap-2">
                                <img src="{{ $avatar }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover">
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $hoTen ?: 'N/A' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        Mã NV: {{ optional($don->nguoi_dung->hoSo)->ma_nhan_vien ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-100">
                            {{ $don->created_at->format('d/m/Y') }}
                            <br><span class="text-xs text-gray-400">{{ $don->created_at->format('H:i') }}</span>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-100">
                            {{ $don->ngay_bat_dau->format('d/m/Y') }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-100">
                            {{ $don->ngay_ket_thuc->format('d/m/Y') }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm font-semibold text-blue-600 dark:text-blue-400">
                            {{ number_format($don->so_ngay_nghi, 0) }} ngày
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                            @if($don->trang_thai == 'cho_duyet')
                                <span class="inline-flex items-center rounded-md bg-yellow-50 dark:bg-yellow-900/30 px-2.5 py-1 text-xs font-medium text-yellow-800 dark:text-yellow-400">⏳ Chờ duyệt</span>
                            @elseif($don->trang_thai == 'da_duyet')
                                <span class="inline-flex items-center rounded-md bg-green-50 dark:bg-green-900/30 px-2.5 py-1 text-xs font-medium text-green-700 dark:text-green-400">✅ Đã duyệt</span>
                            @else
                                <span class="inline-flex items-center rounded-md bg-red-50 dark:bg-red-900/30 px-2.5 py-1 text-xs font-medium text-red-700 dark:text-red-400">❌ Từ chối</span>
                            @endif
                            @if($don->trang_thai == 'tu_choi' && $don->ghi_chu)
                                <div class="text-xs text-red-400 mt-0.5" title="{{ $don->ghi_chu }}">📌 {{ Str::limit($don->ghi_chu, 30) }}</div>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-center text-sm font-medium">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('admin.don_nghi.show', $don->id) }}" 
                                    class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Xem chi tiết">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>

                                @if($don->trang_thai == 'cho_duyet')
                                    <form action="{{ route('admin.don_nghi.duyet', $don->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="trang_thai" value="da_duyet">
                                        <button type="submit" class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg transition" title="Duyệt" onclick="return confirm('Duyệt đơn này?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    <button onclick="showTuChoiModal({{ $don->id }})" 
                                        class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Từ chối">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                @else
                                    <form action="{{ route('admin.don_nghi.duyet', $don->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="trang_thai" value="cho_duyet"> 
                                        <button type="submit" class="p-1.5 text-yellow-600 hover:bg-yellow-50 rounded-lg transition" title="Hoàn tác" onclick="return confirm('Hoàn tác đơn này?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-10 text-center text-sm text-gray-500 dark:text-gray-100">
                            <div class="text-4xl mb-2">📭</div>
                            Không có đơn xin nghỉ nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if($danhSachDon->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                    {{ $danhSachDon->links() }}
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
        <form id="tuChoiForm" method="POST">
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
    let currentDonId = null;

    function showTuChoiModal(id) {
        currentDonId = id;
        const modal = document.getElementById('tuChoiModal');
        const form = document.getElementById('tuChoiForm');
        form.action = `/admin/don-nghi/${id}/duyet`;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.getElementById('ly_do_tu_choi').value = '';
    }

    function closeTuChoiModal() {
        const modal = document.getElementById('tuChoiModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        currentDonId = null;
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