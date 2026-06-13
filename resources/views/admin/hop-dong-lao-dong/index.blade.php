@extends('layouts.admin')

@section('title', 'Quản lý hợp đồng lao động')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800/80 backdrop-blur border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    📄 Quản lý hợp đồng lao động
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">
                    Danh sách hợp đồng lao động trong hệ thống
                </p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('admin.hop-dong.create') }}"
                   class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Thêm hợp đồng
                </a>
            </div>
        </div>
    </div>

    {{-- ALERT MESSAGES --}}
    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-xl flex justify-between items-center">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-xl font-bold hover:opacity-70">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-xl flex justify-between items-center">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-xl font-bold hover:opacity-70">&times;</button>
        </div>
    @endif

    {{-- BỘ LỌC TÌM KIẾM --}}
    <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-5">
        <form method="GET" action="{{ route('admin.hop-dong.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">🔍 Từ khóa</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Số HĐ, tên NV, mã NV..."
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📑 Loại hợp đồng</label>
                    <select name="loai_hop_dong" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 transition">
                        <option value="">-- Tất cả --</option>
                        <option value="thu_viec" {{ request('loai_hop_dong') == 'thu_viec' ? 'selected' : '' }}>Thử việc</option>
                        <option value="xac_dinh_thoi_han" {{ request('loai_hop_dong') == 'xac_dinh_thoi_han' ? 'selected' : '' }}>Xác định thời hạn</option>
                        <option value="khong_xac_dinh_thoi_han" {{ request('loai_hop_dong') == 'khong_xac_dinh_thoi_han' ? 'selected' : '' }}>Không xác định</option>
                        <option value="mua_vu" {{ request('loai_hop_dong') == 'mua_vu' ? 'selected' : '' }}>Mùa vụ</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📊 Trạng thái hợp đồng</label>
                    <select name="trang_thai_hop_dong" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 transition">
                        <option value="">-- Tất cả --</option>
                        <option value="tao_moi" {{ request('trang_thai_hop_dong') == 'tao_moi' ? 'selected' : '' }}>🆕 Tạo mới</option>
                        <option value="hieu_luc" {{ request('trang_thai_hop_dong') == 'hieu_luc' ? 'selected' : '' }}>✅ Hiệu lực</option>
                        <option value="chua_hieu_luc" {{ request('trang_thai_hop_dong') == 'chua_hieu_luc' ? 'selected' : '' }}>⏳ Chưa hiệu lực</option>
                        <option value="het_han" {{ request('trang_thai_hop_dong') == 'het_han' ? 'selected' : '' }}>⏰ Hết hạn</option>
                        <option value="huy_bo" {{ request('trang_thai_hop_dong') == 'huy_bo' ? 'selected' : '' }}>❌ Hủy bỏ</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">✍️ Trạng thái ký</label>
                    <select name="trang_thai_ky" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 transition">
                        <option value="">-- Tất cả --</option>
                        <option value="cho_ky" {{ request('trang_thai_ky') == 'cho_ky' ? 'selected' : '' }}>⏳ Chờ ký</option>
                        <option value="da_ky" {{ request('trang_thai_ky') == 'da_ky' ? 'selected' : '' }}>✅ Đã ký</option>
                        <option value="tu_choi_ky" {{ request('trang_thai_ky') == 'tu_choi_ky' ? 'selected' : '' }}>❌ Từ chối ký</option>
                    </select>
                </div>
            </div>
            <div class="mt-5 flex gap-3">
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition flex items-center gap-2 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Tìm kiếm
                </button>
                <a href="{{ route('admin.hop-dong.index') }}" class="px-5 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-xl transition flex items-center gap-2 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Làm mới
                </a>
            </div>
        </form>
    </div>

    {{-- THỐNG KÊ NHANH --}}
    @php
        $hopDongTaoMoi = $hopDongs->where('trang_thai_hop_dong', 'tao_moi')->count();
        $hopDongSapHetHan = $hopDongs->where('trang_thai_hop_dong', 'hieu_luc')
            ->where('ngay_ket_thuc', '<=', now()->addDays(30))
            ->where('ngay_ket_thuc', '>=', now())
            ->count();
    @endphp
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @if($hopDongTaoMoi > 0)
            <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-700 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <span class="font-semibold">{{ $hopDongTaoMoi }}</span> hợp đồng ở trạng thái <strong>"Tạo mới"</strong> cần gửi cho nhân viên.
                </div>
            </div>
        @endif
        
        @if($hopDongSapHetHan > 0)
            <div class="p-4 rounded-xl bg-orange-50 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 border border-orange-200 dark:border-orange-700 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-800 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <span class="font-semibold">{{ $hopDongSapHetHan }}</span> hợp đồng sắp hết hạn trong 30 ngày tới.
                </div>
            </div>
        @endif
    </div>

    {{-- TABLE CARD --}}
    <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr class="text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-4 py-3">Số hợp đồng</th>
                        <th class="px-4 py-3">Nhân viên</th>
                        <th class="px-4 py-3">Loại HĐ</th>
                        <th class="px-4 py-3">Ngày bắt đầu</th>
                        <th class="px-4 py-3">Ngày kết thúc</th>
                        <th class="px-4 py-3">Lương</th>
                        <th class="px-4 py-3">Trạng thái ký</th>
                        <th class="px-4 py-3">Trạng thái HĐ</th>
                        <th class="px-4 py-3 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($hopDongs as $hd)
                    @php
                        $hoTen = optional($hd->hoSoNguoiDung)->ho . ' ' . optional($hd->hoSoNguoiDung)->ten;
                        $avatar = optional($hd->hoSoNguoiDung)->anh_dai_dien 
                            ? asset($hd->hoSoNguoiDung->anh_dai_dien) 
                            : 'https://ui-avatars.com/api/?background=3b82f6&color=fff&name=' . urlencode($hoTen);
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                        <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $hd->so_hop_dong }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $avatar }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover ring-2 ring-gray-200 dark:ring-gray-600">
                                <div>
                                    <div class="font-medium text-gray-800 dark:text-white">{{ $hoTen ?: 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">Mã: {{ optional($hd->hoSoNguoiDung)->ma_nhan_vien ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ match($hd->loai_hop_dong) {
                                'thu_viec' => 'bg-red-100 text-red-700',
                                'xac_dinh_thoi_han' => 'bg-blue-100 text-blue-700',
                                'khong_xac_dinh_thoi_han' => 'bg-green-100 text-green-700',
                                'mua_vu' => 'bg-yellow-100 text-yellow-700',
                                default => 'bg-gray-100 text-gray-700',
                            } }}">
                                {{ match($hd->loai_hop_dong) {
                                    'thu_viec' => 'Thử việc',
                                    'xac_dinh_thoi_han' => 'Xác định thời hạn',
                                    'khong_xac_dinh_thoi_han' => 'Không xác định',
                                    'mua_vu' => 'Mùa vụ',
                                    default => $hd->loai_hop_dong,
                                } }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($hd->ngay_bat_dau)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $hd->ngay_ket_thuc ? \Carbon\Carbon::parse($hd->ngay_ket_thuc)->format('d/m/Y') : '---' }}</td>
                        <td class="px-4 py-3 font-semibold text-green-600">{{ number_format($hd->luong_co_ban, 0, ',', '.') }}đ</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ match($hd->trang_thai_ky) {
                                'cho_ky' => 'bg-yellow-100 text-yellow-700',
                                'da_ky' => 'bg-green-100 text-green-700',
                                'tu_choi_ky' => 'bg-red-100 text-red-700',
                                default => 'bg-gray-100 text-gray-700',
                            } }}">
                                {{ match($hd->trang_thai_ky) {
                                    'cho_ky' => 'Chờ ký',
                                    'da_ky' => 'Đã ký',
                                    'tu_choi_ky' => 'Từ chối',
                                    default => $hd->trang_thai_ky,
                                } }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ match($hd->trang_thai_hop_dong) {
                                'tao_moi' => 'bg-gray-100 text-gray-700',
                                'chua_hieu_luc' => 'bg-yellow-100 text-yellow-700',
                                'hieu_luc' => 'bg-green-100 text-green-700',
                                'het_han' => 'bg-orange-100 text-orange-700',
                                'huy_bo' => 'bg-red-100 text-red-700',
                                default => 'bg-gray-100 text-gray-700',
                            } }}">
                                {{ match($hd->trang_thai_hop_dong) {
                                    'tao_moi' => 'Tạo mới',
                                    'chua_hieu_luc' => 'Chưa hiệu lực',
                                    'hieu_luc' => 'Hiệu lực',
                                    'het_han' => 'Hết hạn',
                                    'huy_bo' => 'Hủy bỏ',
                                    default => $hd->trang_thai_hop_dong,
                                } }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-1.5">
                                <a href="{{ route('admin.hop-dong.show', $hd->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Xem">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                @if(in_array($hd->trang_thai_hop_dong, ['tao_moi', 'chua_hieu_luc']) && $hd->trang_thai_ky != 'da_ky')
                                <a href="{{ route('admin.hop-dong.edit', $hd->id) }}" class="p-1.5 text-yellow-600 hover:bg-yellow-50 rounded-lg transition" title="Sửa">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                @endif
                                <form action="{{ route('admin.hop-dong.destroy', $hd->id) }}" method="POST" onsubmit="return confirm('Xóa hợp đồng này?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Xóa">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-12 text-gray-500 dark:text-gray-400">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-lg">Chưa có hợp đồng nào</p>
                            <p class="text-sm mt-1">Nhấn "Thêm hợp đồng" để tạo mới</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($hopDongs->hasPages())
            <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $hopDongs->links() }}
            </div>
        @endif
    </div>
</div>

@endsection