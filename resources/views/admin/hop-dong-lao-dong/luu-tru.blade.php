@extends('layouts.admin')

@section('title', 'Lưu trữ hợp đồng')

@section('content')
    <div class="space-y-6 bg-gray-50 dark:bg-[#0f172a] min-h-screen p-6">

        {{-- HEADER --}}
        <div
            class="bg-white dark:bg-gray-800/80 backdrop-blur border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">📦 Lưu trữ hợp đồng</h1>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Danh sách hợp đồng đã hủy bỏ, từ chối hoặc đã tái ký</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.hop-dong.export', array_merge(request()->all(), ['luu_tru' => 'true'])) }}"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl transition flex items-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Xuất Excel
                    </a>
                    <a href="{{ route('admin.hop-dong.index') }}"
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-xl transition flex items-center gap-2 shadow-sm">
                        ← Danh sách hợp đồng
                    </a>
                </div>
            </div>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div
                class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-xl font-bold hover:opacity-70">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div
                class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-xl font-bold hover:opacity-70">&times;</button>
            </div>
        @endif

        {{-- BỘ LỌC TÌM KIẾM --}}
        <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-5">
            <form method="GET" action="{{ route('admin.hop-dong.luu-tru') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">🔍 Từ khóa</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Số HĐ, tên NV, mã NV..."
                            class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📑 Loại hợp
                            đồng</label>
                        <select name="loai_hop_dong"
                            class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 transition text-sm">
                            <option value="">-- Tất cả --</option>
                            <option value="xac_dinh_thoi_han"
                                {{ request('loai_hop_dong') == 'xac_dinh_thoi_han' ? 'selected' : '' }}>Xác định thời hạn
                            </option>
                            <option value="khong_xac_dinh_thoi_han"
                                {{ request('loai_hop_dong') == 'khong_xac_dinh_thoi_han' ? 'selected' : '' }}>Không xác định
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📊 Trạng thái</label>
                        <select name="trang_thai_ky"
                            class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 transition text-sm">
                            <option value="">-- Tất cả --</option>
                            <option value="tu_choi_ky" {{ request('trang_thai_ky') == 'tu_choi_ky' ? 'selected' : '' }}>❌
                                Từ chối ký</option>
                            <option value="da_ky" {{ request('trang_thai_ky') == 'da_ky' ? 'selected' : '' }}>✅ Đã ký
                            </option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition flex items-center gap-1.5 shadow-sm text-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Tìm kiếm
                        </button>
                        <a href="{{ route('admin.hop-dong.luu-tru') }}"
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-xl transition flex items-center gap-1.5 shadow-sm text-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Làm mới
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- THỐNG KÊ NHANH --}}
        @php
            $tongHopDong = $hopDongsArchive->total();
            $tuChoiKy = $hopDongsArchive->where('trang_thai_ky', 'tu_choi_ky')->count();
            $huyBo = $hopDongsArchive->where('trang_thai_hop_dong', 'huy_bo')->count();
            $tuChoiDuyet = $hopDongsArchive->where('trang_thai_duyet', 'tu_choi')->count();
            $daTaiKy = $hopDongsArchive->where('trang_thai_tai_ky', 'da_tai_ky')->count();
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div
                class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm p-4 text-center border border-gray-200 dark:border-gray-700">
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($tongHopDong) }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">📦 Tổng lưu trữ</div>
            </div>
            <div
                class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm p-4 text-center border border-gray-200 dark:border-gray-700">
                <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($tuChoiKy) }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">❌ Từ chối ký</div>
            </div>
            <div
                class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm p-4 text-center border border-gray-200 dark:border-gray-700">
                <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($huyBo) }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">🚫 Hủy bỏ</div>
            </div>
            <div
                class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm p-4 text-center border border-gray-200 dark:border-gray-700">
                <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($tuChoiDuyet) }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">⛔ Từ chối duyệt</div>
            </div>
            <div
                class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm p-4 text-center border border-gray-200 dark:border-gray-700">
                <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($daTaiKy) }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">🔄 Đã tái ký</div>
            </div>
        </div>

        {{-- TABLE --}}
        <div
            class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr
                            class="text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <th class="px-4 py-3 text-center w-12">STT</th>
                            <th class="px-4 py-3">Số hợp đồng</th>
                            <th class="px-4 py-3">Nhân viên</th>
                            <th class="px-4 py-3">Loại HĐ</th>
                            <th class="px-4 py-3">Ngày bắt đầu</th>
                            <th class="px-4 py-3">Ngày kết thúc</th>
                            <th class="px-4 py-3">Trạng thái</th>
                            <th class="px-4 py-3">Lý do</th>
                            <th class="px-4 py-3 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($hopDongsArchive as $index => $hd)
                            @php
                                $hoTen = $hd->hoSoNguoiDung
                                    ? $hd->hoSoNguoiDung->ho . ' ' . $hd->hoSoNguoiDung->ten
                                    : 'N/A';
                                $maNV = $hd->hoSoNguoiDung->ma_nhan_vien ?? 'N/A';
                                $avatar =
                                    $hd->hoSoNguoiDung && $hd->hoSoNguoiDung->anh_dai_dien
                                        ? asset('storage/' . $hd->hoSoNguoiDung->anh_dai_dien)
                                        : 'https://ui-avatars.com/api/?background=3b82f6&color=fff&name=' .
                                            urlencode($hoTen);

                                // Xác định trạng thái và lý do
                                $statusText = '';
                                $statusColor = '';
                                $statusIcon = '';
                                $lyDo = '';

                                if ($hd->trang_thai_ky == 'tu_choi_ky') {
                                    $statusText = 'Từ chối ký';
                                    $statusColor = 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300';
                                    $statusIcon = '❌';
                                    $lyDo = str_replace('Từ chối ký: ', '', $hd->ghi_chu ?? 'Không có lý do');
                                } elseif ($hd->trang_thai_duyet == 'tu_choi') {
                                    $statusText = 'Từ chối duyệt';
                                    $statusColor = 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300';
                                    $statusIcon = '⛔';
                                    $lyDo = $hd->ly_do_tu_choi ?? 'Không có lý do';
                                } elseif ($hd->trang_thai_hop_dong == 'huy_bo') {
                                    $statusText = 'Hủy bỏ';
                                    $statusColor = 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300';
                                    $statusIcon = '🚫';
                                    $lyDo = $hd->ly_do_huy ?? 'Không có lý do';
                                } elseif ($hd->trang_thai_tai_ky == 'da_tai_ky') {
                                    $statusText = 'Đã tái ký';
                                    $statusColor =
                                        'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300';
                                    $statusIcon = '🔄';
                                    $lyDo = 'Đã tái ký sang hợp đồng mới';
                                } else {
                                    $statusText = 'Lưu trữ';
                                    $statusColor = 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300';
                                    $statusIcon = '📦';
                                    $lyDo = '---';
                                }
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                                <td class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">{{ $index + 1 }}
                                </td>
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $hd->so_hop_dong }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $avatar }}" alt="Avatar"
                                            class="w-8 h-8 rounded-full object-cover ring-2 ring-gray-200 dark:ring-gray-600">
                                        <div>
                                            <div class="font-medium text-gray-800 dark:text-white">{{ $hoTen }}
                                            </div>
                                            <div class="text-xs text-gray-500">Mã: {{ $maNV }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-semibold {{ $hd->loai_hop_dong == 'khong_xac_dinh_thoi_han' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                        {{ $hd->loai_hop_dong == 'khong_xac_dinh_thoi_han' ? 'Không xác định' : 'Xác định' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($hd->ngay_bat_dau)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                    {{ $hd->ngay_ket_thuc ? \Carbon\Carbon::parse($hd->ngay_ket_thuc)->format('d/m/Y') : '♾️' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                        {{ $statusIcon }} {{ $statusText }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2"
                                        title="{{ $lyDo }}">
                                        {{ $lyDo }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-center gap-1.5 flex-wrap">
                                        {{-- Nút Xem --}}
                                        <a href="{{ route('admin.hop-dong.show', $hd->id) }}"
                                            class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                            title="Xem chi tiết">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        {{-- Nếu là từ chối ký, cho phép tạo lại (dù đã tạo lại hay chưa) --}}
                                        @if ($hd->trang_thai_ky == 'tu_choi_ky')
                                            <form action="{{ route('admin.hop-dong.tao-lai', $hd->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="p-1.5 text-purple-600 hover:bg-purple-50 rounded-lg transition"
                                                    title="📝 Tạo lại hợp đồng"
                                                    onclick="return confirm('📝 Tạo lại hợp đồng mới từ hợp đồng này?\n\nHợp đồng mới sẽ được tạo với thông tin tương tự và gửi lên duyệt.')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Nút xóa (chỉ Admin) --}}
                                        @if (auth()->user()->vaiTros->first()->name == 'admin')
                                            <form action="{{ route('admin.hop-dong.destroy', $hd->id) }}" method="POST"
                                                onsubmit="return confirm('🗑️ Bạn có chắc muốn xóa hợp đồng {{ $hd->so_hop_dong }}?')"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition"
                                                    title="Xóa">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-12 text-gray-500 dark:text-gray-400">
                                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                        </path>
                                    </svg>
                                    <p class="text-lg font-medium">Không có hợp đồng nào trong lưu trữ</p>
                                    <p class="text-sm mt-1 text-gray-400">Hợp đồng sẽ tự động được đưa vào lưu trữ khi bị
                                        hủy, từ chối hoặc đã tái ký.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($hopDongsArchive->hasPages())
                <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $hopDongsArchive->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- CSS cho line-clamp --}}
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            max-width: 200px;
        }
    </style>
@endsection