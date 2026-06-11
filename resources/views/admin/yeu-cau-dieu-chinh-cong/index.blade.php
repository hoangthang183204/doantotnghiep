@extends('layouts.admin')

@section('title', 'Quản lý yêu cầu điều chỉnh công')

@section('content')

<div class="space-y-6 bg-gray-50 dark:bg-[#0f172a] min-h-screen p-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800/80 backdrop-blur border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                Quản lý yêu cầu điều chỉnh công
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                Quản lý và xét duyệt yêu cầu điều chỉnh giờ công của nhân viên
            </p>
        </div>
    </div>

    {{-- THỐNG KÊ --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tổng số yêu cầu</p>
                    <h3 class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-2">{{ number_format($thongKe['tong_so'] ?? 0) }}</h3>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Chờ duyệt</p>
                    <h3 class="text-3xl font-bold text-yellow-500 mt-2">{{ number_format($thongKe['cho_duyet'] ?? 0) }}</h3>
                </div>
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Đã duyệt</p>
                    <h3 class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">{{ number_format($thongKe['da_duyet'] ?? 0) }}</h3>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Từ chối</p>
                    <h3 class="text-3xl font-bold text-red-600 dark:text-red-400 mt-2">{{ number_format($thongKe['tu_choi'] ?? 0) }}</h3>
                </div>
                <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-full">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="font-bold text-xl">&times;</button>
        </div>
    @endif

    @if($errors->has('error'))
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg flex justify-between items-center">
            <span>{{ $errors->first('error') }}</span>
            <button onclick="this.parentElement.remove()" class="font-bold text-xl">&times;</button>
        </div>
    @endif

    {{-- BỘ LỌC --}}
    <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-5">
        <form method="GET" action="{{ route('admin.yeu-cau-dieu-chinh-cong.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Trạng thái</label>
                    <select name="trang_thai" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Tất cả --</option>
                        <option value="cho_duyet" {{ request('trang_thai') == 'cho_duyet' ? 'selected' : '' }}>🟡 Chờ duyệt</option>
                        <option value="da_duyet" {{ request('trang_thai') == 'da_duyet' ? 'selected' : '' }}>🟢 Đã duyệt</option>
                        <option value="tu_choi" {{ request('trang_thai') == 'tu_choi' ? 'selected' : '' }}>🔴 Từ chối</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phòng ban</label>
                    <select name="phong_ban_id" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Tất cả --</option>
                        @foreach($phongBanList ?? [] as $pb)
                            <option value="{{ $pb->id }}" {{ request('phong_ban_id') == $pb->id ? 'selected' : '' }}>
                                {{ $pb->ten_phong_ban }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Từ ngày</label>
                    <input type="date" name="tu_ngay" value="{{ request('tu_ngay') }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Đến ngày</label>
                    <input type="date" name="den_ngay" value="{{ request('den_ngay') }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tìm kiếm</label>
                    <input type="text" name="tim_kiem" value="{{ request('tim_kiem') }}" placeholder="Tên, mã NV..." class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="mt-5 flex gap-3 flex-wrap">
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Lọc dữ liệu
                </button>
                <a href="{{ route('admin.yeu-cau-dieu-chinh-cong.index') }}" class="px-5 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reset
                </a>
                <a href="{{ route('admin.yeu-cau-dieu-chinh-cong.bao-cao') }}" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Báo cáo
                </a>
            </div>
        </form>
    </div>

    {{-- BULK ACTIONS TOOLBAR --}}
    <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-4">
        <div class="flex items-center gap-4">
            <input type="checkbox" id="check-all" class="w-4 h-4 rounded border-gray-300 cursor-pointer">
            <label for="check-all" class="text-sm text-gray-600 dark:text-gray-300 cursor-pointer">Chọn tất cả</label>
            <span class="text-sm text-gray-500">(<span id="selectedCount">0</span> mục được chọn)</span>
            <div id="bulkActions" class="flex gap-2 ml-auto" style="display: none;">
                <button type="button" onclick="bulkApprove()" 
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition">
                    ✓ Duyệt hàng loạt
                </button>
                <button type="button" onclick="bulkReject()" 
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition">
                    ✗ Từ chối hàng loạt
                </button>
            </div>
        </div>
    </div>

    {{-- BẢNG DANH SÁCH --}}
    <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100 dark:bg-gray-800/60">
                    <tr class="text-left text-sm text-gray-700 dark:text-gray-300">
                        <th class="px-4 py-3 w-10"></th>
                        <th class="px-4 py-3">Nhân viên</th>
                        <th class="px-4 py-3">Ngày điều chỉnh</th>
                        <th class="px-4 py-3">Giờ vào/ra</th>
                        <th class="px-4 py-3">Lý do</th>
                        <th class="px-4 py-3">Trạng thái</th>
                        <th class="px-4 py-3">Người duyệt</th>
                        <th class="px-4 py-3">Ngày tạo</th>
                        <th class="px-4 py-3 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($yeuCauList ?? [] as $yeuCau)
                        @php
                            $hoTen = optional($yeuCau->nguoiDung->hoSo)
                                ? $yeuCau->nguoiDung->hoSo->ho . ' ' . $yeuCau->nguoiDung->hoSo->ten
                                : $yeuCau->nguoiDung->ten_dang_nhap ?? 'N/A';
                            $avatar = optional($yeuCau->nguoiDung->hoSo)->anh_dai_dien 
                                ? asset($yeuCau->nguoiDung->hoSo->anh_dai_dien) 
                                : asset('assets/images/default.png');
                            
                            $statusClass = match($yeuCau->trang_thai) {
                                'cho_duyet' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300',
                                'da_duyet' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
                                'tu_choi' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                                default => 'bg-gray-100 text-gray-700',
                            };
                            $statusText = match($yeuCau->trang_thai) {
                                'cho_duyet' => 'Chờ duyệt',
                                'da_duyet' => 'Đã duyệt',
                                'tu_choi' => 'Từ chối',
                                default => $yeuCau->trang_thai,
                            };
                        @endphp
                        <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-4 py-3">
                                @if($yeuCau->trang_thai == 'cho_duyet')
                                    <input type="checkbox" class="row-check w-4 h-4 rounded border-gray-300" value="{{ $yeuCau->id }}">
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $avatar }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover" onerror="this.src='{{ asset('assets/images/default.png') }}'">
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $hoTen }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Mã NV: {{ optional($yeuCau->nguoiDung->hoSo)->ma_nhan_vien ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Phòng: {{ optional($yeuCau->nguoiDung->phongBan)->ten_phong_ban ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-medium">{{ \Carbon\Carbon::parse($yeuCau->ngay)->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($yeuCau->ngay)->locale('vi')->dayName }}</div>
                            </td>

                            <td class="px-4 py-3">
                                @if($yeuCau->gio_vao)
                                    <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-700">Vào: {{ \Carbon\Carbon::parse($yeuCau->gio_vao)->format('H:i') }}</span><br>
                                @endif
                                @if($yeuCau->gio_ra)
                                    <span class="px-2 py-1 rounded text-xs bg-orange-100 text-orange-700 mt-1 inline-block">Ra: {{ \Carbon\Carbon::parse($yeuCau->gio_ra)->format('H:i') }}</span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                <div class="max-w-xs">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate" title="{{ $yeuCau->ly_do }}">
                                        {{ \Illuminate\Support\Str::limit($yeuCau->ly_do, 50) }}
                                    </p>
                                    @if($yeuCau->tep_dinh_kem)
                                        <span class="text-xs text-blue-500 inline-flex items-center gap-1 mt-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                            </svg>
                                            Có file đính kèm
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                @if($yeuCau->nguoiDuyet)
                                    <div class="font-medium text-sm">{{ optional($yeuCau->nguoiDuyet->hoSo)->ho ?? '' }} {{ optional($yeuCau->nguoiDuyet->hoSo)->ten ?? '' }}</div>
                                    <div class="text-xs text-gray-400">{{ $yeuCau->duyet_vao ? \Carbon\Carbon::parse($yeuCau->duyet_vao)->format('d/m/Y H:i') : '' }}</div>
                                @else
                                    <span class="text-gray-400 text-sm">--</span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $yeuCau->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $yeuCau->created_at->format('H:i') }}</div>
                            </td>

                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('admin.yeu-cau-dieu-chinh-cong.show', $yeuCau->id) }}" 
                                        class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition" title="Xem chi tiết">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>

                                    @if($yeuCau->trang_thai == 'cho_duyet')
                                        <button onclick="showDuyetModal({{ $yeuCau->id }}, 'duyet')" 
                                            class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition" title="Duyệt">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                        <button onclick="showDuyetModal({{ $yeuCau->id }}, 'tu_choi')" 
                                            class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition" title="Từ chối">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    @endif

                                    @if($yeuCau->tep_dinh_kem)
                                        <a href="{{ route('admin.yeu-cau-dieu-chinh-cong.download', $yeuCau->id) }}" 
                                            class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition" title="Tải file">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-10 text-center text-gray-400 dark:text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Không có dữ liệu yêu cầu điều chỉnh công
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PHÂN TRANG --}}
        @if(isset($yeuCauList) && $yeuCauList->hasPages())
        <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $yeuCauList->links() }}
        </div>
        @endif
    </div>
</div>

{{-- MODAL DUYỆT / TỪ CHỐI --}}
<div id="duyetModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6">
        <h3 id="modalTitle" class="text-lg font-bold text-gray-800 dark:text-white mb-4">Duyệt yêu cầu</h3>
        <form id="duyetForm" method="POST">
            @csrf
            <input type="hidden" name="hanh_dong" id="hanh_dong">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ghi chú</label>
                <textarea name="ghi_chu_duyet" id="ghi_chu_duyet" rows="3" 
                    placeholder="Nhập ghi chú (tùy chọn)..."
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeDuyetModal()" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                    Hủy
                </button>
                <button type="submit" id="submitBtn" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    Xác nhận
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL XÁC NHẬN HÀNG LOẠT --}}
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Xác nhận</h3>
        <p id="confirmMessage" class="text-gray-600 dark:text-gray-300 mb-6"></p>
        <div class="flex justify-end gap-3">
            <button onclick="closeConfirmModal()" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                Hủy
            </button>
            <button id="confirmBtn" class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                Xác nhận
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let confirmCallback = null;
let duyetModal = null;
let currentAction = null;
let currentIds = [];

// DOM Elements
const modalDuyet = document.getElementById('duyetModal');
const modalConfirm = document.getElementById('confirmModal');

// Khởi tạo
document.addEventListener('DOMContentLoaded', function() {
    // Check all
    const selectAll = document.getElementById('check-all');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
            updateBulkActions();
        });
    }

    // Individual checkbox
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('row-check')) {
            updateBulkActions();
            if (selectAll) {
                const allChecked = document.querySelectorAll('.row-check:checked').length === document.querySelectorAll('.row-check').length;
                selectAll.checked = allChecked;
            }
        }
    });
});

function updateBulkActions() {
    const checked = document.querySelectorAll('.row-check:checked');
    const count = checked.length;
    const selectedCount = document.getElementById('selectedCount');
    const bulkActions = document.getElementById('bulkActions');
    
    if (selectedCount) selectedCount.textContent = count;
    if (bulkActions) bulkActions.style.display = count > 0 ? 'flex' : 'none';
}

function getSelectedIds() {
    return Array.from(document.querySelectorAll('.row-check:checked')).map(cb => cb.value);
}

// Hiển thị modal duyệt đơn lẻ
function showDuyetModal(id, action) {
    const form = document.getElementById('duyetForm');
    const title = document.getElementById('modalTitle');
    const submitBtn = document.getElementById('submitBtn');
    const hanhDong = document.getElementById('hanh_dong');
    
    form.action = `/admin/yeu-cau-dieu-chinh-cong/${id}/duyet`;
    hanhDong.value = action;
    
    if (action === 'duyet') {
        title.textContent = 'Duyệt yêu cầu điều chỉnh công';
        submitBtn.textContent = 'Duyệt';
        submitBtn.classList.remove('bg-red-600', 'hover:bg-red-700');
        submitBtn.classList.add('bg-green-600', 'hover:bg-green-700');
    } else {
        title.textContent = 'Từ chối yêu cầu điều chỉnh công';
        submitBtn.textContent = 'Từ chối';
        submitBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
        submitBtn.classList.add('bg-red-600', 'hover:bg-red-700');
    }
    
    if (modalDuyet) {
        modalDuyet.classList.remove('hidden');
        modalDuyet.classList.add('flex');
    }
}

function closeDuyetModal() {
    if (modalDuyet) {
        modalDuyet.classList.add('hidden');
        modalDuyet.classList.remove('flex');
    }
    document.getElementById('duyetForm').reset();
}

// Bulk actions
function bulkApprove() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        alert('Vui lòng chọn ít nhất một yêu cầu!');
        return;
    }
    showConfirm(`Xác nhận duyệt ${ids.length} yêu cầu điều chỉnh công?`, () => {
        bulkAction(ids, 'duyet', 'Duyệt hàng loạt thành công!');
    });
}

function bulkReject() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        alert('Vui lòng chọn ít nhất một yêu cầu!');
        return;
    }
    showConfirm(`Xác nhận từ chối ${ids.length} yêu cầu điều chỉnh công?`, () => {
        const reason = prompt('Nhập lý do từ chối (tùy chọn):');
        bulkAction(ids, 'tu_choi', 'Từ chối hàng loạt thành công!', reason);
    });
}

function bulkAction(ids, action, successMessage, reason = null) {
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('yeu_cau_ids', JSON.stringify(ids));
    formData.append('hanh_dong', action);
    if (reason) formData.append('ghi_chu_duyet', reason);
    
    fetch('{{ route("admin.yeu-cau-dieu-chinh-cong.duyet-hang-loat") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(successMessage);
            location.reload();
        } else {
            alert('Có lỗi xảy ra: ' + (data.message || 'Vui lòng thử lại'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi thực hiện thao tác!');
    });
}

// Confirm modal
function showConfirm(message, callback) {
    const confirmMessage = document.getElementById('confirmMessage');
    const confirmBtn = document.getElementById('confirmBtn');
    
    if (confirmMessage) confirmMessage.textContent = message;
    confirmCallback = callback;
    
    if (modalConfirm) {
        modalConfirm.classList.remove('hidden');
        modalConfirm.classList.add('flex');
    }
    
    if (confirmBtn) {
        confirmBtn.onclick = function() {
            if (confirmCallback) confirmCallback();
            closeConfirmModal();
        };
    }
}

function closeConfirmModal() {
    if (modalConfirm) {
        modalConfirm.classList.add('hidden');
        modalConfirm.classList.remove('flex');
    }
    confirmCallback = null;
}

// Đóng modal khi click ra ngoài
if (modalDuyet) {
    modalDuyet.addEventListener('click', function(e) {
        if (e.target === this) closeDuyetModal();
    });
}
if (modalConfirm) {
    modalConfirm.addEventListener('click', function(e) {
        if (e.target === this) closeConfirmModal();
    });
}
</script>
@endpush