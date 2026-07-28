@extends('layouts.admin')

@section('title', 'Đơn xin về sớm')

@section('content')
<div class="space-y-6">
    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    <i class="fas fa-file-signature mr-3 text-yellow-500"></i>
                    Đơn xin về sớm
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">
                    Quản lý và duyệt đơn xin về sớm của nhân viên
                </p>
            </div>
            <a href="{{ route('admin.cham-cong.index') }}" 
               class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Quay lại chấm công
            </a>
        </div>
    </div>

    {{-- THỐNG KÊ --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl shadow-sm p-5 border border-yellow-200 dark:border-yellow-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-yellow-600 dark:text-yellow-400">
                        <i class="fas fa-clock mr-1"></i> Chờ duyệt
                    </p>
                    <h3 class="text-3xl font-bold text-yellow-600 mt-2">{{ $soChoDuyet ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-yellow-200 dark:bg-yellow-800/50 rounded-full flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-green-50 dark:bg-green-900/20 rounded-xl shadow-sm p-5 border border-green-200 dark:border-green-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-600 dark:text-green-400">
                        <i class="fas fa-check-circle mr-1"></i> Đã duyệt
                    </p>
                    <h3 class="text-3xl font-bold text-green-600 mt-2">{{ $soDaDuyet ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-green-200 dark:bg-green-800/50 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-double text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-red-50 dark:bg-red-900/20 rounded-xl shadow-sm p-5 border border-red-200 dark:border-red-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-red-600 dark:text-red-400">
                        <i class="fas fa-times-circle mr-1"></i> Từ chối
                    </p>
                    <h3 class="text-3xl font-bold text-red-600 mt-2">{{ $soTuChoi ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-red-200 dark:bg-red-800/50 rounded-full flex items-center justify-center">
                    <i class="fas fa-ban text-red-600 dark:text-red-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl shadow-sm p-5 border border-blue-200 dark:border-blue-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-600 dark:text-blue-400">
                        <i class="fas fa-list mr-1"></i> Tổng cộng
                    </p>
                    <h3 class="text-3xl font-bold text-blue-600 mt-2">{{ ($soChoDuyet ?? 0) + ($soDaDuyet ?? 0) + ($soTuChoi ?? 0) }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-200 dark:bg-blue-800/50 rounded-full flex items-center justify-center">
                    <i class="fas fa-file-alt text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- BỘ LỌC --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700">
        <form method="GET" action="{{ route('admin.cham-cong.don-ve-som') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="fas fa-user mr-1"></i> Tìm theo tên
                </label>
                <input type="text" name="ten_nhan_vien" value="{{ request('ten_nhan_vien') }}"
                    placeholder="Nhập tên nhân viên..."
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="fas fa-filter mr-1"></i> Trạng thái
                </label>
                <select name="trang_thai" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">-- Tất cả --</option>
                    <option value="cho_duyet" {{ request('trang_thai') == 'cho_duyet' ? 'selected' : '' }}>⏳ Chờ duyệt</option>
                    <option value="da_duyet" {{ request('trang_thai') == 'da_duyet' ? 'selected' : '' }}>✅ Đã duyệt</option>
                    <option value="tu_choi" {{ request('trang_thai') == 'tu_choi' ? 'selected' : '' }}>❌ Từ chối</option>
                </select>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="fas fa-calendar-alt mr-1"></i> Từ ngày
                </label>
                <input type="date" name="tu_ngay" value="{{ request('tu_ngay') }}"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="fas fa-calendar-alt mr-1"></i> Đến ngày
                </label>
                <input type="date" name="den_ngay" value="{{ request('den_ngay') }}"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            <div class="md:col-span-4 flex flex-wrap gap-3">
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
                <a href="{{ route('admin.cham-cong.don-ve-som') }}"
                    class="px-5 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-redo"></i> Làm mới
                </a>
            </div>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nhân viên</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Giờ ra dự kiến</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Số phút</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($donVeSoms ?? [] as $don)
                        @php
                            $nguoiDung = $don->nguoiDung;
                            $hoSo = $nguoiDung ? $nguoiDung->hoSo : null;
                            $hoTen = '';
                            if ($hoSo && ($hoSo->ho || $hoSo->ten)) {
                                $hoTen = trim(($hoSo->ho ?? '') . ' ' . ($hoSo->ten ?? ''));
                            }
                            if (empty($hoTen) && $nguoiDung) {
                                $hoTen = $nguoiDung->ten_dang_nhap ?? 'N/A';
                            }
                            if (empty($hoTen)) {
                                $hoTen = 'NV#' . ($don->nguoi_dung_id ?? '?');
                            }
                            
                            $hasAvatar = $hoSo && $hoSo->anh_dai_dien && file_exists(public_path('storage/' . $hoSo->anh_dai_dien));
                            $avatar = $hasAvatar ? asset('storage/' . $hoSo->anh_dai_dien) : null;
                            $initial = strtoupper(substr($hoTen, 0, 1));
                            
                            $statusMap = [
                                'cho_duyet' => ['bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300', '⏳ Chờ duyệt'],
                                'da_duyet' => ['bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300', '✅ Đã duyệt'],
                                'tu_choi' => ['bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300', '❌ Từ chối'],
                            ];
                            $stt = $statusMap[$don->trang_thai] ?? ['bg-gray-100 text-gray-700', $don->trang_thai];
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    @if($avatar)
                                        <img src="{{ $avatar }}" alt="{{ $hoTen }}"
                                            class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600 shadow-sm">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white font-bold shadow-md">
                                            {{ $initial }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-gray-800 dark:text-white">{{ $hoTen }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Mã: {{ $hoSo ? $hoSo->ma_nhan_vien ?? 'N/A' : 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">
                                {{ \Carbon\Carbon::parse($don->ngay)->format('d/m/Y') }}
                                <br>
                                <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($don->ngay)->locale('vi')->dayName }}</span>
                            </td>
                            <td class="px-4 py-4 text-sm font-semibold text-blue-600 dark:text-blue-400">
                                {{ \Carbon\Carbon::parse($don->gio_ra_du_kien)->format('H:i') }}
                            </td>
                            <td class="px-4 py-4 text-sm">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300">
                                    {{ $don->so_phut_ve_som }} phút
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $stt[0] }}">
                                    {{ $stt[1] }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Nút xem chi tiết --}}
                                    <button onclick="xemChiTiet({{ $don->id }})"
                                        class="w-8 h-8 rounded-full bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-800/50 text-blue-600 dark:text-blue-400 transition flex items-center justify-center"
                                        title="Xem chi tiết">
                                        <i class="fas fa-eye text-sm"></i>
                                    </button>

                                    @if($don->trang_thai == 'cho_duyet')
                                        <button onclick="duyetDon({{ $don->id }})"
                                            class="w-8 h-8 rounded-full bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-800/50 text-green-600 dark:text-green-400 transition flex items-center justify-center"
                                            title="Duyệt đơn">
                                            <i class="fas fa-check text-sm"></i>
                                        </button>
                                        <button onclick="tuChoiDon({{ $don->id }})"
                                            class="w-8 h-8 rounded-full bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-800/50 text-red-600 dark:text-red-400 transition flex items-center justify-center"
                                            title="Từ chối đơn">
                                            <i class="fas fa-times text-sm"></i>
                                        </button>
                                    @else
                                        <span class="text-sm text-gray-400 dark:text-gray-500">Đã xử lý</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-gray-500 dark:text-gray-400">
                                <i class="fas fa-inbox text-4xl block mb-3 text-gray-300 dark:text-gray-600"></i>
                                <p class="font-medium">Không có đơn xin về sớm nào</p>
                                <p class="text-sm">Hiện tại chưa có đơn xin về sớm nào được gửi lên</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($donVeSoms) && $donVeSoms->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $donVeSoms->links() }}
            </div>
        @endif
    </div>
</div>

{{-- MODAL CHI TIẾT ĐƠN XIN VỀ SỚM --}}
<div id="modalChiTiet" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto mx-4 animate-fadeIn">
        <div class="sticky top-0 bg-white dark:bg-gray-800 rounded-t-xl border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex items-center justify-between z-10">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-file-signature text-yellow-500"></i>
                Chi tiết đơn xin về sớm
            </h3>
            <button onclick="closeModalChiTiet()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <div class="p-6 space-y-4">
            {{-- Thông tin nhân viên --}}
            <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                <div id="chiTietAvatar" class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white text-2xl font-bold shadow-md flex-shrink-0">
                    ?
                </div>
                <div>
                    <p id="chiTietHoTen" class="text-lg font-bold text-gray-800 dark:text-white">---</p>
                    <p id="chiTietMaNV" class="text-sm text-gray-500 dark:text-gray-400">Mã NV: ---</p>
                    <p id="chiTietPhongBan" class="text-sm text-gray-500 dark:text-gray-400">Phòng ban: ---</p>
                </div>
            </div>

            {{-- Thông tin đơn --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                    <p class="text-xs text-yellow-600 dark:text-yellow-400 font-medium">NGÀY XIN</p>
                    <p id="chiTietNgay" class="text-lg font-bold text-gray-800 dark:text-white">---</p>
                </div>
                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">GIỜ RA DỰ KIẾN</p>
                    <p id="chiTietGioRa" class="text-lg font-bold text-blue-600 dark:text-blue-400">---</p>
                </div>
                <div class="p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg border border-orange-200 dark:border-orange-800">
                    <p class="text-xs text-orange-600 dark:text-orange-400 font-medium">SỐ PHÚT VỀ SỚM</p>
                    <p id="chiTietSoPhut" class="text-lg font-bold text-orange-600 dark:text-orange-400">---</p>
                </div>
                <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800">
                    <p class="text-xs text-purple-600 dark:text-purple-400 font-medium">TRẠNG THÁI</p>
                    <p id="chiTietTrangThai" class="text-lg font-bold">---</p>
                </div>
            </div>

            {{-- Lý do --}}
            <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-200 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-2">📝 LÝ DO XIN VỀ SỚM</p>
                <p id="chiTietLyDo" class="text-gray-700 dark:text-gray-300 text-base leading-relaxed">---</p>
            </div>

            {{-- Lý do từ chối (nếu có) --}}
            <div id="chiTietLyDoTuChoiContainer" class="hidden p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                <p class="text-xs text-red-600 dark:text-red-400 font-medium mb-2">❌ LÝ DO TỪ CHỐI</p>
                <p id="chiTietLyDoTuChoi" class="text-red-700 dark:text-red-300 text-base leading-relaxed">---</p>
            </div>

            {{-- Thông tin duyệt --}}
            <div id="chiTietThongTinDuyet" class="hidden p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                <p class="text-xs text-green-600 dark:text-green-400 font-medium mb-2">✅ THÔNG TIN DUYỆT</p>
                <p id="chiTietNguoiDuyet" class="text-gray-700 dark:text-gray-300 text-sm">Người duyệt: ---</p>
                <p id="chiTietThoiGianDuyet" class="text-gray-700 dark:text-gray-300 text-sm">Thời gian: ---</p>
            </div>
        </div>

        <div class="sticky bottom-0 bg-white dark:bg-gray-800 rounded-b-xl border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-end gap-3">
            <button onclick="closeModalChiTiet()" 
                class="px-5 py-2 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-700 dark:text-white rounded-lg transition">
                Đóng
            </button>
        </div>
    </div>
</div>

{{-- MODAL TỪ CHỐI --}}
<div id="modalTuChoi" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md mx-4 animate-fadeIn">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-times-circle text-red-500"></i>
                Từ chối đơn xin về sớm
            </h3>
            <button onclick="closeModalTuChoi()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="p-6 space-y-4">
            <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                <p class="text-sm text-yellow-700 dark:text-yellow-300">
                    <i class="fas fa-info-circle mr-1"></i>
                    Vui lòng nhập lý do từ chối để nhân viên biết lý do.
                </p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Lý do từ chối <span class="text-red-500">*</span>
                </label>
                <textarea id="lyDoTuChoi" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white"
                    placeholder="Nhập lý do từ chối..."></textarea>
                <p class="text-xs text-gray-400 mt-1">Tối thiểu 10 ký tự</p>
            </div>
        </div>
        
        <input type="hidden" id="donIdTuChoi" value="">
        
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex gap-3 justify-end">
            <button onclick="closeModalTuChoi()" 
                class="px-4 py-2 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-700 dark:text-white rounded-lg transition">
                Hủy
            </button>
            <button onclick="xacNhanTuChoi()" 
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition flex items-center gap-2">
                <i class="fas fa-times"></i> Xác nhận từ chối
            </button>
        </div>
    </div>
</div>

{{-- MODAL THÔNG BÁO THÀNH CÔNG --}}
<div id="modalSuccess" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-sm mx-4 text-center p-6">
        <div class="mb-4">
            <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto">
                <i class="fas fa-check-circle text-4xl text-green-500"></i>
            </div>
        </div>
        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2" id="modalSuccessTitle">Thành công!</h3>
        <p class="text-gray-600 dark:text-gray-400 mb-4" id="modalSuccessMessage">Đã duyệt đơn xin về sớm.</p>
        <button onclick="closeModalSuccess()" 
            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
            Đóng
        </button>
    </div>
</div>

{{-- MODAL THÔNG BÁO LỖI --}}
<div id="modalError" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-sm mx-4 text-center p-6">
        <div class="mb-4">
            <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto">
                <i class="fas fa-times-circle text-4xl text-red-500"></i>
            </div>
        </div>
        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Lỗi!</h3>
        <p class="text-gray-600 dark:text-gray-400 mb-4" id="modalErrorMessage">Có lỗi xảy ra.</p>
        <button onclick="closeModalError()" 
            class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
            Đóng
        </button>
    </div>
</div>

@endsection

@push('styles')
<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(10px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    .animate-fadeIn {
        animation: fadeIn 0.25s ease-out;
    }
</style>
@endpush

@push('scripts')
<script>
    // =============================================
    // XEM CHI TIẾT ĐƠN
    // =============================================
    function xemChiTiet(id) {
        fetch(`/admin/cham-cong/don-ve-som/${id}/detail`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const don = data.data;
                
                // Avatar
                const avatarEl = document.getElementById('chiTietAvatar');
                if (don.avatar) {
                    avatarEl.innerHTML = `<img src="${don.avatar}" class="w-16 h-16 rounded-full object-cover">`;
                } else {
                    avatarEl.textContent = don.initial || '?';
                    avatarEl.className = 'w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white text-2xl font-bold shadow-md flex-shrink-0';
                }
                
                document.getElementById('chiTietHoTen').textContent = don.ho_ten || '---';
                document.getElementById('chiTietMaNV').textContent = 'Mã NV: ' + (don.ma_nhan_vien || '---');
                document.getElementById('chiTietPhongBan').textContent = 'Phòng ban: ' + (don.phong_ban || '---');
                document.getElementById('chiTietNgay').textContent = don.ngay || '---';
                document.getElementById('chiTietGioRa').textContent = don.gio_ra_du_kien || '---';
                document.getElementById('chiTietSoPhut').textContent = don.so_phut_ve_som ? don.so_phut_ve_som + ' phút' : '---';
                document.getElementById('chiTietLyDo').textContent = don.ly_do || '---';
                
                // Trạng thái
                const statusMap = {
                    'cho_duyet': { text: '⏳ Chờ duyệt', class: 'text-yellow-600' },
                    'da_duyet': { text: '✅ Đã duyệt', class: 'text-green-600' },
                    'tu_choi': { text: '❌ Từ chối', class: 'text-red-600' }
                };
                const stt = statusMap[don.trang_thai] || { text: don.trang_thai, class: 'text-gray-600' };
                document.getElementById('chiTietTrangThai').textContent = stt.text;
                document.getElementById('chiTietTrangThai').className = 'text-lg font-bold ' + stt.class;
                
                // Lý do từ chối
                const lyDoTuChoiContainer = document.getElementById('chiTietLyDoTuChoiContainer');
                const lyDoTuChoiEl = document.getElementById('chiTietLyDoTuChoi');
                if (don.ly_do_tu_choi) {
                    lyDoTuChoiContainer.classList.remove('hidden');
                    lyDoTuChoiEl.textContent = don.ly_do_tu_choi;
                } else {
                    lyDoTuChoiContainer.classList.add('hidden');
                }
                
                // Thông tin duyệt
                const thongTinDuyet = document.getElementById('chiTietThongTinDuyet');
                if (don.trang_thai === 'da_duyet' || don.trang_thai === 'tu_choi') {
                    thongTinDuyet.classList.remove('hidden');
                    document.getElementById('chiTietNguoiDuyet').textContent = 'Người duyệt: ' + (don.nguoi_duyet || '---');
                    document.getElementById('chiTietThoiGianDuyet').textContent = 'Thời gian: ' + (don.thoi_gian_duyet || '---');
                } else {
                    thongTinDuyet.classList.add('hidden');
                }
                
                document.getElementById('modalChiTiet').classList.remove('hidden');
                document.getElementById('modalChiTiet').classList.add('flex');
            } else {
                showError('Lỗi!', data.message || 'Không thể tải thông tin chi tiết.');
            }
        })
        .catch(error => {
            showError('Lỗi!', 'Có lỗi xảy ra: ' + error.message);
        });
    }

    function closeModalChiTiet() {
        document.getElementById('modalChiTiet').classList.add('hidden');
        document.getElementById('modalChiTiet').classList.remove('flex');
    }

    // =============================================
    // DUYỆT ĐƠN
    // =============================================
    function duyetDon(id) {
        if (!confirm('Bạn có chắc chắn muốn duyệt đơn xin về sớm này?')) return;

        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch(`/admin/cham-cong/don-ve-som/${id}/duyet`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = originalText;
            
            if (data.success) {
                showSuccess('✅ Duyệt thành công!', data.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showError('Lỗi!', data.message || 'Không thể duyệt đơn này.');
            }
        })
        .catch(error => {
            btn.disabled = false;
            btn.innerHTML = originalText;
            showError('Lỗi!', 'Có lỗi xảy ra: ' + error.message);
        });
    }

    // =============================================
    // TỪ CHỐI ĐƠN
    // =============================================
    function tuChoiDon(id) {
        document.getElementById('donIdTuChoi').value = id;
        document.getElementById('lyDoTuChoi').value = '';
        document.getElementById('modalTuChoi').classList.remove('hidden');
        document.getElementById('modalTuChoi').classList.add('flex');
        setTimeout(() => document.getElementById('lyDoTuChoi').focus(), 100);
    }

    function closeModalTuChoi() {
        document.getElementById('modalTuChoi').classList.add('hidden');
        document.getElementById('modalTuChoi').classList.remove('flex');
        document.getElementById('donIdTuChoi').value = '';
        document.getElementById('lyDoTuChoi').value = '';
    }

    function xacNhanTuChoi() {
        const id = document.getElementById('donIdTuChoi').value;
        const lyDo = document.getElementById('lyDoTuChoi').value.trim();

        if (!lyDo) {
            showError('Lỗi!', 'Vui lòng nhập lý do từ chối!');
            document.getElementById('lyDoTuChoi').focus();
            return;
        }

        if (lyDo.length < 10) {
            showError('Lỗi!', 'Lý do từ chối phải có ít nhất 10 ký tự!');
            document.getElementById('lyDoTuChoi').focus();
            return;
        }

        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch(`/admin/cham-cong/don-ve-som/${id}/tu-choi`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ ly_do_tu_choi: lyDo })
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = originalText;
            
            if (data.success) {
                closeModalTuChoi();
                showSuccess('❌ Từ chối thành công!', data.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showError('Lỗi!', data.message || 'Không thể từ chối đơn này.');
            }
        })
        .catch(error => {
            btn.disabled = false;
            btn.innerHTML = originalText;
            showError('Lỗi!', 'Có lỗi xảy ra: ' + error.message);
        });
    }

    // =============================================
    // MODAL THÔNG BÁO
    // =============================================
    function showSuccess(title, message) {
        document.getElementById('modalSuccessTitle').textContent = title;
        document.getElementById('modalSuccessMessage').textContent = message;
        document.getElementById('modalSuccess').classList.remove('hidden');
        document.getElementById('modalSuccess').classList.add('flex');
    }

    function closeModalSuccess() {
        document.getElementById('modalSuccess').classList.add('hidden');
        document.getElementById('modalSuccess').classList.remove('flex');
    }

    function showError(title, message) {
        document.getElementById('modalErrorMessage').textContent = message;
        document.getElementById('modalError').classList.remove('hidden');
        document.getElementById('modalError').classList.add('flex');
    }

    function closeModalError() {
        document.getElementById('modalError').classList.add('hidden');
        document.getElementById('modalError').classList.remove('flex');
    }

    // =============================================
    // KEYBOARD SHORTCUTS
    // =============================================
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && document.getElementById('modalTuChoi').classList.contains('flex')) {
            xacNhanTuChoi();
        }
        if (e.key === 'Escape') {
            closeModalChiTiet();
            closeModalTuChoi();
            closeModalSuccess();
            closeModalError();
        }
    });

    // =============================================
    // CLICK OUTSIDE TO CLOSE
    // =============================================
    document.addEventListener('click', function(e) {
        const modalChiTiet = document.getElementById('modalChiTiet');
        if (e.target === modalChiTiet) {
            closeModalChiTiet();
        }
        const modalTuChoi = document.getElementById('modalTuChoi');
        if (e.target === modalTuChoi) {
            closeModalTuChoi();
        }
        const modalSuccess = document.getElementById('modalSuccess');
        if (e.target === modalSuccess) {
            closeModalSuccess();
        }
        const modalError = document.getElementById('modalError');
        if (e.target === modalError) {
            closeModalError();
        }
    });
</script>
@endpush