@extends('layouts.admin')

@section('title', 'Chi tiết hợp đồng')

@section('content')
    <div class="space-y-6 bg-gray-50 dark:bg-[#0f172a] min-h-screen p-6">

        {{-- HEADER --}}
        <div
            class="bg-white dark:bg-gray-800/80 backdrop-blur border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">📄 Chi tiết hợp đồng lao động</h1>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Thông tin chi tiết của hợp đồng</p>
                </div>
                <a href="{{ route('admin.hop-dong.index') }}"
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-xl transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Quay lại
                </a>
            </div>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 text-green-700 px-4 py-3 rounded-xl">✅
                {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 text-red-700 px-4 py-3 rounded-xl">❌
                {{ session('error') }}</div>
        @endif

        {{-- THÔNG TIN CHÍNH --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Thông tin nhân viên --}}
            <div
                class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
                <div class="border-b px-6 py-4 bg-gray-50 dark:bg-gray-700/30">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">👤 Thông tin nhân viên</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex">
                        <div class="w-32 text-gray-500">Họ tên:</div>
                        <div>
                            @if ($hopDong->nguoiDung && $hopDong->nguoiDung->hoSo)
                                {{ $hopDong->nguoiDung->hoSo->ho ?? '' }}
                                {{ $hopDong->nguoiDung->hoSo->ten ?? '' }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-32 text-gray-500">Mã NV:</div>
                        <div>{{ optional(optional($hopDong->nguoiDung)->hoSo)->ma_nhan_vien ?? 'N/A' }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-32 text-gray-500">Chức vụ:</div>
                        <div>{{ $hopDong->chucVu->ten ?? ($hopDong->chuc_vu ?? 'N/A') }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-32 text-gray-500">Phòng ban:</div>
                        <div>{{ optional($hopDong->nguoiDung->phongBan)->ten_phong_ban ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>

            {{-- Thông tin hợp đồng --}}
            <div
                class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
                <div class="border-b px-6 py-4 bg-gray-50 dark:bg-gray-700/30">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">📋 Thông tin hợp đồng</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex">
                        <div class="w-32 text-gray-500">Số hợp đồng:</div>
                        <div class="font-semibold">{{ $hopDong->so_hop_dong }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-32 text-gray-500">Loại hợp đồng:</div>
                        <div><span
                                class="px-2 py-1 rounded-full text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">{{ $hopDong->ten_loai_hop_dong }}</span>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-32 text-gray-500">Ngày bắt đầu:</div>
                        <div>{{ $hopDong->ngay_bat_dau ? $hopDong->ngay_bat_dau->format('d/m/Y') : '---' }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-32 text-gray-500">Ngày kết thúc:</div>
                        <div>
                            {{ $hopDong->ngay_ket_thuc ? $hopDong->ngay_ket_thuc->format('d/m/Y') : '♾️ Vô thời hạn' }}
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-32 text-gray-500">Lương cơ bản:</div>
                        <div class="font-semibold text-green-600 dark:text-green-400">
                            {{ number_format($hopDong->luong_co_ban, 0, ',', '.') }} đ</div>
                    </div>

                    {{-- Phụ cấp --}}
                    <div class="flex">
                        <div class="w-32 text-gray-500">Phụ cấp:</div>
                        <div>
                            @php
                                $phuCapNhanViens = $hopDong->nguoiDung->phuCapNhanViens ?? collect();
                                $totalPhuCap = $phuCapNhanViens->sum('so_tien');
                            @endphp
                            @if ($phuCapNhanViens->count() > 0)
                                <span class="font-semibold text-blue-600 dark:text-blue-400">
                                    {{ number_format($totalPhuCap, 0, ',', '.') }} đ
                                </span>
                                <div class="mt-2 space-y-1">
                                    @foreach ($phuCapNhanViens as $pc)
                                        <span
                                            class="inline-block px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg text-xs mr-1">
                                            {{ $pc->phuCap->ten ?? 'Phụ cấp' }}:
                                            {{ number_format($pc->so_tien, 0, ',', '.') }} đ
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                @php
                                    $phuCapValue = $hopDong->phu_cap ?? 0;
                                    $phuCapDisplay = 0;
                                    $hasPhuCap = false;

                                    if (is_array($phuCapValue) && count($phuCapValue) > 0) {
                                        $phuCapIds = $phuCapValue;
                                        $phuCaps = \App\Models\PhuCap::whereIn('id', $phuCapIds)->get();
                                        foreach ($phuCaps as $pc) {
                                            $phuCapDisplay += $pc->so_tien_mac_dinh ?? 0;
                                        }
                                        $hasPhuCap = $phuCapDisplay > 0;
                                    } elseif (is_numeric($phuCapValue) && $phuCapValue > 0) {
                                        $phuCapDisplay = $phuCapValue;
                                        $hasPhuCap = true;
                                    } elseif (!empty($hopDong->phu_cap_id)) {
                                        $phuCap = \App\Models\PhuCap::find($hopDong->phu_cap_id);
                                        if ($phuCap) {
                                            $phuCapDisplay = $phuCap->so_tien_mac_dinh ?? 0;
                                            $hasPhuCap = $phuCapDisplay > 0;
                                        }
                                    }
                                @endphp
                                @if ($hasPhuCap)
                                    <span class="text-gray-400">{{ number_format($phuCapDisplay, 0, ',', '.') }} đ</span>
                                @else
                                    <span class="text-gray-400">Không có phụ cấp</span>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="flex">
                        <div class="w-32 text-gray-500">Địa điểm:</div>
                        <div>{{ $hopDong->dia_diem_lam_viec ?? '---' }}</div>
                    </div>

                    {{-- TRẠNG THÁI DUYỆT --}}
                    <div class="flex">
                        <div class="w-32 text-gray-500">Trạng thái duyệt:</div>
                        <div>
                            @switch($hopDong->trang_thai_duyet)
                                @case('cho_duyet')
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300">⏳
                                        Chờ duyệt</span>
                                @break

                                @case('da_duyet')
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300">✅
                                        Đã duyệt</span>
                                @break

                                @case('tu_choi')
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300">❌
                                        Từ chối</span>
                                @break

                                @default
                                    <span class="px-2 py-1 rounded-full text-xs bg-gray-100 dark:bg-gray-700">---</span>
                            @endswitch
                        </div>
                    </div>

                    {{-- Người duyệt --}}
                    @if ($hopDong->nguoi_duyet_id)
                        <div class="flex">
                            <div class="w-32 text-gray-500">Người duyệt:</div>
                            <div>
                                @if ($hopDong->nguoiDuyet && $hopDong->nguoiDuyet->hoSo)
                                    {{ $hopDong->nguoiDuyet->hoSo->ho ?? '' }}
                                    {{ $hopDong->nguoiDuyet->hoSo->ten ?? ($hopDong->nguoiDuyet->ten_dang_nhap ?? 'N/A') }}
                                @elseif($hopDong->nguoiDuyet)
                                    {{ $hopDong->nguoiDuyet->ten_dang_nhap ?? 'N/A' }}
                                @else
                                    <span class="text-gray-400">---</span>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Thời gian duyệt --}}
                    @if ($hopDong->thoi_gian_duyet)
                        <div class="flex">
                            <div class="w-32 text-gray-500">Thời gian duyệt:</div>
                            <div>
                                {{ $hopDong->thoi_gian_duyet ? \Carbon\Carbon::parse($hopDong->thoi_gian_duyet)->format('d/m/Y H:i') : '---' }}
                            </div>
                        </div>
                    @endif

                    {{-- Lý do từ chối duyệt --}}
                    @if ($hopDong->trang_thai_duyet === 'tu_choi' && $hopDong->ly_do_tu_choi)
                        <div class="flex">
                            <div class="w-32 text-gray-500">Lý do từ chối duyệt:</div>
                            <div class="text-red-600">{{ $hopDong->ly_do_tu_choi }}</div>
                        </div>
                    @endif

                    {{-- Trạng thái hợp đồng --}}
                    <div class="flex">
                        <div class="w-32 text-gray-500">Trạng thái HĐ:</div>
                        <div>
                            @switch($hopDong->trang_thai_hop_dong)
                                @case('tao_moi')
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">🆕
                                        Tạo mới</span>
                                @break

                                @case('chua_hieu_luc')
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300">⏳
                                        Chưa hiệu lực</span>
                                @break

                                @case('hieu_luc')
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300">✅
                                        Hiệu lực</span>
                                @break

                                @case('het_han')
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300">❌
                                        Hết hạn</span>
                                @break

                                @case('huy_bo')
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">🚫
                                        Hủy bỏ</span>
                                @break

                                @default
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-gray-100 dark:bg-gray-700">{{ ucfirst(str_replace('_', ' ', $hopDong->trang_thai_hop_dong)) }}</span>
                            @endswitch
                        </div>
                    </div>

                    {{-- Trạng thái ký --}}
                    <div class="flex">
                        <div class="w-32 text-gray-500">Trạng thái ký:</div>
                        <div>
                            @switch($hopDong->trang_thai_ky)
                                @case('cho_ky')
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300">⏳
                                        Chờ ký</span>
                                @break

                                @case('da_ky')
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300">✅
                                        Đã ký</span>
                                @break

                                @case('tu_choi_ky')
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300">❌
                                        Từ chối ký</span>
                                @break

                                @default
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-gray-100 dark:bg-gray-700">{{ ucfirst(str_replace('_', ' ', $hopDong->trang_thai_ky)) }}</span>
                            @endswitch
                        </div>
                    </div>

                    {{-- Lý do từ chối ký (từ nhân viên) --}}
                    @if ($hopDong->trang_thai_ky === 'tu_choi_ky' && $hopDong->ghi_chu)
                        <div class="flex">
                            <div class="w-32 text-gray-500">Lý do từ chối ký:</div>
                            <div class="text-red-600">{{ str_replace('Từ chối ký: ', '', $hopDong->ghi_chu) }}</div>
                        </div>
                    @endif

                    {{-- Thời gian gửi cho nhân viên --}}
                    @if ($hopDong->thoi_gian_gui)
                        <div class="flex">
                            <div class="w-32 text-gray-500">Thời gian gửi:</div>
                            <div>{{ \Carbon\Carbon::parse($hopDong->thoi_gian_gui)->format('d/m/Y H:i') }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Bản Scan Hợp Đồng Ký Tay do nhân viên nộp --}}
        @if ($hopDong->file_scan_ky)
            <div
                class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
                <div class="border-b px-6 py-4 bg-teal-50 dark:bg-teal-900/20">
                    <h3 class="text-lg font-semibold text-teal-600 dark:text-teal-400 flex items-center gap-2">
                        📸 Bản scan hợp đồng ký tay từ nhân viên
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap items-center gap-4">
                        @php
                            $extension = strtolower(pathinfo($hopDong->file_scan_ky, PATHINFO_EXTENSION));
                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                        @endphp

                        <a href="{{ asset('storage/' . $hopDong->file_scan_ky) }}" target="_blank"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl transition font-medium text-sm shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Xem chi tiết bản ký tay (.{{ $extension }})
                        </a>

                        @if ($hopDong->thoi_gian_ky)
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                🕐 Thời gian nộp: {{ \Carbon\Carbon::parse($hopDong->thoi_gian_ky)->format('d/m/Y H:i') }}
                            </span>
                        @endif
                    </div>

                    @if ($isImage)
                        <div
                            class="mt-4 border border-gray-200 dark:border-gray-700 rounded-lg p-2 bg-gray-50 dark:bg-gray-900/50 max-w-md">
                            <p class="text-xs text-gray-400 mb-2 font-medium">📷 Xem trước ảnh bản ký:</p>
                            <img src="{{ asset('storage/' . $hopDong->file_scan_ky) }}" alt="Bản scan ký tay"
                                class="rounded shadow-sm max-h-60 object-contain cursor-pointer"
                                onclick="window.open(this.src)">
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- 🔥 ACTION BUTTONS THEO LUỒNG DUYỆT --}}
        @php
            $user = auth()->user();
            $roleName = '';
            if ($user && $user->vaiTros) {
                $roleName = $user->vaiTros->first()->name ?? '';
            }
            $isAdmin = $roleName === 'admin';
            $isHr = $roleName === 'hr';
            $isAdminOrHr = in_array($roleName, ['admin', 'hr']);
        @endphp

        <div class="flex flex-wrap gap-3">

            {{-- 🔥 Nút DUYỆT - Chỉ hiện cho Admin khi đang chờ duyệt --}}
            @if ($hopDong->trang_thai_duyet === 'cho_duyet' && $isAdmin)
                <div
                    class="w-full p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl border border-yellow-200 dark:border-yellow-800 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="font-medium text-yellow-700 dark:text-yellow-300">⏳ Hợp đồng đang chờ duyệt</p>
                        <p class="text-sm text-yellow-600 dark:text-yellow-400">Vui lòng xem xét và duyệt hợp đồng này.</p>
                    </div>
                    <div class="flex gap-3">
                        <form action="{{ route('admin.hop-dong.duyet', $hopDong->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl transition shadow-md hover:shadow-lg"
                                onclick="return confirm('✅ Xác nhận duyệt hợp đồng {{ $hopDong->so_hop_dong }}?')">
                                ✅ Duyệt hợp đồng
                            </button>
                        </form>
                        <button onclick="showTuChoiDuyetForm()"
                            class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl transition">
                            ❌ Từ chối
                        </button>
                    </div>
                </div>

                {{-- Form từ chối duyệt (ẩn) --}}
                <div id="tuChoiDuyetForm" style="display:none;"
                    class="w-full bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4">❌ Từ chối duyệt hợp đồng</h3>
                    <form action="{{ route('admin.hop-dong.tu-choi-duyet', $hopDong->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lý do từ chối
                                <span class="text-red-500">*</span></label>
                            <textarea name="ly_do_tu_choi"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500"
                                rows="3" placeholder="Nhập lý do từ chối..." required></textarea>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">Xác nhận từ
                                chối</button>
                            <button type="button" onclick="hideTuChoiDuyetForm()"
                                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">Hủy
                                bỏ</button>
                        </div>
                    </form>
                </div>
            @endif

            {{-- 🔥 Nút GỬI CHO NHÂN VIÊN - Chỉ hiện khi chưa gửi --}}
            @if ($hopDong->trang_thai_duyet === 'da_duyet' && $hopDong->trang_thai_ky === 'cho_ky')
                @if (($isAdmin || $isHr) && !$hopDong->thoi_gian_gui)
                    <form action="{{ route('admin.hop-dong.gui-ky', $hopDong->id) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                            class="w-full px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition shadow-md hover:shadow-lg flex items-center justify-center gap-2"
                            onclick="return confirm('📨 Gửi hợp đồng {{ $hopDong->so_hop_dong }} cho nhân viên ký?')">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            📨 Gửi cho nhân viên ký
                        </button>
                    </form>
                @else
                    <div
                        class="w-full px-6 py-2.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-xl flex items-center justify-center gap-2 border border-green-200 dark:border-green-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        ✅ Đã gửi cho nhân viên ký
                        @if ($hopDong->thoi_gian_gui)
                            <span
                                class="text-xs text-gray-500">({{ \Carbon\Carbon::parse($hopDong->thoi_gian_gui)->format('d/m/Y H:i') }})</span>
                        @endif
                    </div>
                @endif
            @endif

            {{-- 🔥 KHI NHÂN VIÊN TỪ CHỐI KÝ --}}
            @if ($hopDong->trang_thai_ky === 'tu_choi_ky')
                <div class="w-full p-4 bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-200 dark:border-red-800">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <p class="font-medium text-red-700 dark:text-red-300">❌ Nhân viên từ chối ký hợp đồng</p>
                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">
                                <span class="font-medium">Lý do:</span>
                                {{ str_replace('Từ chối ký: ', '', $hopDong->ghi_chu ?? 'Không có lý do') }}
                            </p>
                            @php
                                $isSalaryIssue =
                                    str_contains(strtolower($hopDong->ghi_chu ?? ''), 'lương') ||
                                    str_contains(strtolower($hopDong->ghi_chu ?? ''), 'luong') ||
                                    str_contains(strtolower($hopDong->ghi_chu ?? ''), 'thấp') ||
                                    str_contains(strtolower($hopDong->ghi_chu ?? ''), 'cao');
                            @endphp
                            @if ($isSalaryIssue)
                                <p class="text-sm text-blue-600 dark:text-blue-400 mt-1">
                                    💡 Gợi ý: Nhân viên không hài lòng với mức lương. Hãy tăng lương và tạo lại hợp đồng.
                                </p>
                            @endif
                            @if ($hopDong->trang_thai_tai_ky == 'da_tai_ky')
                                <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-1">
                                    ⚠️ Đã tạo lại hợp đồng này trước đó. Bạn có thể tạo lại lần nữa nếu cần.
                                </p>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-2">
                            {{-- Nút Tạo lại hợp đồng --}}
                            <form action="{{ route('admin.hop-dong.tao-lai', $hopDong->id) }}" method="POST"
                                class="inline">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-lg transition"
                                    onclick="return confirm('📝 Tạo lại hợp đồng mới dựa trên hợp đồng này?\n\nHợp đồng mới sẽ được tạo với thông tin tương tự và gửi lên duyệt.')">
                                    📝 Tạo lại hợp đồng
                                </button>
                            </form>
                            {{-- Nút Đề xuất tăng lương (nếu lý do liên quan đến lương) --}}
                            @if ($isSalaryIssue)
                                <a href="{{ route('admin.tang-luong.create', $hopDong->id) }}"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition">
                                    💰 Đề xuất tăng lương
                                </a>
                            @endif
                            {{-- Nút Sửa (chỉ khi chưa tạo lại) --}}
                            @if ($hopDong->trang_thai_tai_ky != 'da_tai_ky' && $isAdminOrHr)
                                <a href="{{ route('admin.hop-dong.edit', $hopDong->id) }}"
                                    class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm rounded-lg transition">
                                    ✏️ Sửa hợp đồng
                                </a>
                            @endif
                            {{-- Nút Xóa --}}
                            @if ($isAdmin)
                                <form action="{{ route('admin.hop-dong.destroy', $hopDong->id) }}" method="POST"
                                    onsubmit="return confirm('🗑️ Xóa hợp đồng này?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg transition">
                                        🗑️ Xóa
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Nút Tăng lương --}}
            @if ($hopDong->trang_thai_hop_dong == 'hieu_luc' && $hopDong->trang_thai_ky == 'da_ky')
                <a href="{{ route('admin.tang-luong.create', $hopDong->id) }}"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    💰 Tăng lương
                </a>
            @endif

            {{-- Nút Hủy hợp đồng --}}
            @if (in_array($hopDong->trang_thai_hop_dong, ['hieu_luc', 'chua_hieu_luc', 'het_han']) &&
                    $hopDong->trang_thai_ky != 'da_ky' &&
                    !$hopDong->thoi_gian_gui && 
                    $isAdminOrHr)
                <button onclick="showHuyForm()"
                    class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition">
                    ❌ Hủy hợp đồng
                </button>
            @endif

            {{-- Nút Sửa - Chỉ hiện khi chưa duyệt --}}
            @if ($hopDong->trang_thai_duyet === 'cho_duyet' && $hopDong->trang_thai_hop_dong !== 'huy_bo' && $isAdminOrHr)
                <a href="{{ route('admin.hop-dong.edit', $hopDong->id) }}"
                    class="px-6 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-xl transition">
                    ✏️ Sửa hợp đồng
                </a>
            @endif

            {{-- Nút Xóa - Chỉ hiện cho Admin khi chưa gửi cho nhân viên --}}
            @if (in_array($hopDong->trang_thai_hop_dong, ['tao_moi', 'het_han', 'huy_bo']) && $isAdmin && !$hopDong->thoi_gian_gui)
                {{-- 🔥 THÊM ĐIỀU KIỆN: CHƯA GỬI --}}
                <form action="{{ route('admin.hop-dong.destroy', $hopDong->id) }}" method="POST"
                    onsubmit="return confirm('🗑️ Bạn có chắc muốn xóa hợp đồng {{ $hopDong->so_hop_dong }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-xl transition">
                        🗑️ Xóa hợp đồng
                    </button>
                </form>
            @endif
        </div>

        {{-- Form hủy hợp đồng (ẩn) --}}
        <div id="huyForm" style="display:none;"
            class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4">❌ Xác nhận hủy hợp đồng</h3>
            <form action="{{ route('admin.hop-dong.huy', $hopDong->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lý do hủy <span
                            class="text-red-500">*</span></label>
                    <textarea name="ly_do_huy"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500"
                        rows="3" placeholder="Nhập lý do hủy hợp đồng..." required></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">Xác nhận
                        hủy</button>
                    <button type="button" onclick="hideHuyForm()"
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">Hủy bỏ</button>
                </div>
            </form>
        </div>

        {{-- Điều khoản hợp đồng --}}
        @if ($hopDong->dieu_khoan)
            <div
                class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
                <div class="border-b px-6 py-4 bg-gray-50 dark:bg-gray-700/30">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">📜 Điều khoản hợp đồng</h3>
                </div>
                <div class="p-6">
                    <div class="prose dark:prose-invert max-w-none">
                        {!! nl2br(e($hopDong->dieu_khoan)) !!}
                    </div>
                </div>
            </div>
        @endif

        {{-- Ghi chú --}}
        @if ($hopDong->ghi_chu)
            <div
                class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
                <div class="border-b px-6 py-4 bg-gray-50 dark:bg-gray-700/30">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">📝 Ghi chú</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 dark:text-gray-300">{{ $hopDong->ghi_chu }}</p>
                </div>
            </div>
        @endif

        {{-- ============================================================ --}}
        {{-- 🔥 FILE HỢP ĐỒNG - GỐC VÀ ĐÃ KÝ (CÙNG 1 HÀNG) 🔥 --}}
        {{-- ============================================================ --}}
        @if ($hopDong->duong_dan_file || $hopDong->file_hop_dong_da_ky)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- File hợp đồng gốc --}}
                <div
                    class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
                    <div class="border-b px-6 py-4 bg-blue-50 dark:bg-blue-900/20">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <h3 class="text-lg font-semibold text-blue-600 dark:text-blue-400 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                📎 File hợp đồng gốc
                            </h3>
                            @if ($hopDong->created_by)
                                <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                    <span>👤
                                        @if ($hopDong->nguoiGuiHopDong && $hopDong->nguoiGuiHopDong->hoSo)
                                            {{ $hopDong->nguoiGuiHopDong->hoSo->ho ?? '' }}
                                            {{ $hopDong->nguoiGuiHopDong->hoSo->ten ?? $hopDong->nguoiGuiHopDong->ten_dang_nhap }}
                                        @elseif($hopDong->nguoiGuiHopDong)
                                            {{ $hopDong->nguoiGuiHopDong->ten_dang_nhap ?? 'N/A' }}
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                    @if ($hopDong->created_at)
                                        <span class="text-gray-400">•</span>
                                        <span>🕐 {{ $hopDong->created_at->format('d/m/Y H:i') }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-wrap gap-2">
                            @foreach (array_filter(explode(';', $hopDong->duong_dan_file)) as $file)
                                @php
                                    $fileName = basename(trim($file));
                                    $filePath = asset('storage/' . trim($file));
                                    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                @endphp
                                <a href="{{ $filePath }}" target="_blank"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-lg transition text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                    @if ($isImage)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    @endif
                                    {{ $fileName }}
                                    @if ($isImage)
                                        <span class="text-xs text-blue-400">(🖼️ Ảnh)</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- File hợp đồng đã ký --}}
                @if ($hopDong->file_hop_dong_da_ky)
                    <div
                        class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
                        <div class="border-b px-6 py-4 bg-green-50 dark:bg-green-900/20">
                            <h3 class="text-lg font-semibold text-green-600 dark:text-green-400 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                ✍️ File hợp đồng đã ký
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="flex flex-wrap gap-2">
                                @foreach (array_filter(explode(';', $hopDong->file_hop_dong_da_ky)) as $file)
                                    @php
                                        $fileName = basename(trim($file));
                                        $filePath = asset('storage/' . trim($file));
                                        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    @endphp
                                    <a href="{{ $filePath }}" target="_blank"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 dark:bg-green-900/30 hover:bg-green-100 dark:hover:bg-green-900/50 rounded-lg transition text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800">
                                        @if ($isImage)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        @endif
                                        ✅ {{ $fileName }}
                                        @if ($isImage)
                                            <span class="text-xs text-green-400">(🖼️ Ảnh)</span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                            @if ($hopDong->thoi_gian_ky)
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-3 flex items-center gap-2">
                                    <span>🕐 Ký lúc:
                                        {{ \Carbon\Carbon::parse($hopDong->thoi_gian_ky)->format('d/m/Y H:i') }}</span>
                                </p>
                            @endif
                            @if ($hopDong->nguoi_ky_id)
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-2">
                                    <span>👤 Người ký:
                                        @if ($hopDong->nguoiKy && $hopDong->nguoiKy->hoSo)
                                            {{ $hopDong->nguoiKy->hoSo->ho ?? '' }}
                                            {{ $hopDong->nguoiKy->hoSo->ten ?? ($hopDong->nguoiKy->ten_dang_nhap ?? 'N/A') }}
                                        @elseif($hopDong->nguoiKy)
                                            {{ $hopDong->nguoiKy->ten_dang_nhap ?? 'N/A' }}
                                        @else
                                            <span class="text-gray-400">---</span>
                                        @endif
                                    </span>
                                </p>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        @endif

        {{-- 🔥 NÚT TÁI KÝ (GIA HẠN) - CHỈ HIỆN KHI HẾT HẠN --}}
        @if ($hopDong->trang_thai_hop_dong == 'het_han' && $hopDong->trang_thai_tai_ky != 'da_tai_ky')
            <div
                class="mt-4 p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl border border-purple-200 dark:border-purple-800">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h4 class="font-semibold text-purple-700 dark:text-purple-300 flex items-center gap-2">
                            <span class="text-xl">🔄</span> Tái ký hợp đồng (Gia hạn)
                        </h4>
                        <p class="text-sm text-purple-600 dark:text-purple-400 mt-1">
                            Hợp đồng đã hết hạn. Tạo hợp đồng gia hạn mới.
                        </p>
                    </div>
                    <form action="{{ route('admin.hop-dong.tai-ky', $hopDong->id) }}" method="POST"
                        onsubmit="return confirm('🔄 Bạn có chắc muốn tái ký (gia hạn) hợp đồng này?\n\nHợp đồng mới sẽ được tạo với ngày tháng mới.')">
                        @csrf
                        <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl transition shadow-md hover:shadow-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            🔄 Tái ký (Gia hạn)
                        </button>
                    </form>
                </div>
            </div>
        @endif

        {{-- Hiển thị thông báo nếu hợp đồng đã được tái ký --}}
        @if ($hopDong->trang_thai_tai_ky == 'da_tai_ky')
            <div
                class="mt-4 p-4 bg-green-50 dark:bg-green-900/20 rounded-xl border border-green-200 dark:border-green-800">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">✅</span>
                    <div>
                        <h4 class="font-semibold text-green-700 dark:text-green-300">Hợp đồng đã được tái ký</h4>
                        <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                            Hợp đồng này đã được tái ký sang hợp đồng mới.
                            @if (class_exists(\App\Models\LichSuTaiKy::class))
                                @php
                                    $lichSuTaiKy = \App\Models\LichSuTaiKy::where(
                                        'hop_dong_cu_id',
                                        $hopDong->id,
                                    )->first();
                                @endphp
                                @if ($lichSuTaiKy)
                                    <a href="{{ route('admin.hop-dong.show', $lichSuTaiKy->hop_dong_moi_id) }}"
                                        class="text-blue-600 hover:underline font-medium">
                                        Xem hợp đồng mới
                                    </a>
                                @endif
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- File đính kèm --}}
        @if ($hopDong->file_dinh_kem)
            <div
                class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
                <div class="border-b px-6 py-4 bg-gray-50 dark:bg-gray-700/30">
                    <h3 class="text-lg font-semibold text-purple-600 dark:text-purple-400">📎 File đính kèm</h3>
                </div>
                <div class="p-6">
                    <a href="{{ asset('storage/' . $hopDong->file_dinh_kem) }}" target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-purple-50 dark:bg-purple-900/30 hover:bg-purple-100 dark:hover:bg-purple-900/50 rounded-lg transition text-purple-700 dark:text-purple-300">
                        📎 {{ basename($hopDong->file_dinh_kem) }}
                    </a>
                </div>
            </div>
        @endif

        {{-- Thông tin người hủy --}}
        @if ($hopDong->trang_thai_hop_dong == 'huy_bo' || $hopDong->trang_thai_ky == 'tu_choi_ky')
            <div
                class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl shadow-sm overflow-hidden">
                <div class="border-b px-6 py-4 bg-red-100 dark:bg-red-900/30">
                    <h3 class="text-lg font-semibold text-red-700 dark:text-red-300">🚫 Thông tin hủy hợp đồng</h3>
                </div>
                <div class="p-6 space-y-2">
                    <div class="flex">
                        <div class="w-32 text-red-600 dark:text-red-400">Lý do hủy:</div>
                        <div class="text-red-700 dark:text-red-300">
                            @if ($hopDong->trang_thai_ky == 'tu_choi_ky')
                                {{ str_replace('Từ chối ký: ', '', $hopDong->ghi_chu ?? 'Không có lý do') }}
                            @else
                                {{ $hopDong->ly_do_huy ?? 'Không có lý do' }}
                            @endif
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-32 text-red-600 dark:text-red-400">Người hủy:</div>
                        <div class="text-red-700 dark:text-red-300">
                            @if ($hopDong->nguoiHuy && $hopDong->nguoiHuy->hoSo)
                                {{ $hopDong->nguoiHuy->hoSo->ho ?? '' }}
                                {{ $hopDong->nguoiHuy->hoSo->ten ?? ($hopDong->nguoiHuy->ten_dang_nhap ?? 'N/A') }}
                            @elseif($hopDong->nguoiHuy)
                                {{ $hopDong->nguoiHuy->ten_dang_nhap ?? 'N/A' }}
                            @else
                                <span class="text-gray-400">---</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-32 text-red-600 dark:text-red-400">Thời gian hủy:</div>
                        <div class="text-red-700 dark:text-red-300">
                            {{ $hopDong->thoi_gian_huy ? \Carbon\Carbon::parse($hopDong->thoi_gian_huy)->format('d/m/Y H:i') : 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- LỊCH SỬ TÁI KÝ --}}
        @if (class_exists(\App\Models\LichSuTaiKy::class))
            @php
                $lichSuTaiKy = \App\Models\LichSuTaiKy::where('hop_dong_cu_id', $hopDong->id)
                    ->orWhere('hop_dong_moi_id', $hopDong->id)
                    ->with(['hopDongCu', 'hopDongMoi', 'nguoiThucHien'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            @endphp

            @if ($lichSuTaiKy->count() > 0)
                <div
                    class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden mt-6">
                    <div class="border-b px-6 py-4 bg-purple-50 dark:bg-purple-900/20">
                        <h3 class="text-lg font-semibold text-purple-600 dark:text-purple-400 flex items-center gap-2">
                            <span class="text-xl">🔄</span> Lịch sử tái ký / Tạo lại
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="relative border-l-2 border-purple-300 dark:border-purple-700 ml-4 pl-6 space-y-6">
                            @foreach ($lichSuTaiKy as $item)
                                <div class="relative">
                                    <div
                                        class="absolute -left-8 top-1 w-4 h-4 bg-purple-500 rounded-full border-2 border-white dark:border-gray-800">
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                        <div class="flex flex-wrap justify-between items-start gap-2">
                                            <div>
                                                @if (isset($item->loai) && $item->loai == 'tao_lai')
                                                    <p class="font-medium text-gray-800 dark:text-white">
                                                        <span class="text-purple-600 dark:text-purple-400">📝 Tạo
                                                            lại</span>
                                                        từ <span
                                                            class="font-mono">{{ $item->hopDongCu->so_hop_dong ?? 'N/A' }}</span>
                                                        → <span
                                                            class="font-mono">{{ $item->hopDongMoi->so_hop_dong ?? 'N/A' }}</span>
                                                    </p>
                                                @else
                                                    <p class="font-medium text-gray-800 dark:text-white">
                                                        <span class="text-purple-600 dark:text-purple-400">🔄 Tái ký (Gia
                                                            hạn)</span>
                                                        từ <span
                                                            class="font-mono">{{ $item->hopDongCu->so_hop_dong ?? 'N/A' }}</span>
                                                        → <span
                                                            class="font-mono">{{ $item->hopDongMoi->so_hop_dong ?? 'N/A' }}</span>
                                                    </p>
                                                @endif
                                                @if ($item->ly_do_tai_ky)
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                        📌 {{ $item->ly_do_tai_ky }}
                                                    </p>
                                                @endif
                                                @if (isset($item->loai) && $item->loai == 'tao_lai' && $item->hopDongCu->ghi_chu)
                                                    <p class="text-sm text-red-500 dark:text-red-400 mt-1">
                                                        ⚠️ Lý do từ chối cũ:
                                                        {{ str_replace('Từ chối ký: ', '', $item->hopDongCu->ghi_chu) }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="text-right text-sm text-gray-500 dark:text-gray-400">
                                                <p>🕐 {{ $item->created_at->format('d/m/Y H:i') }}</p>
                                                <p>👤
                                                    @if ($item->nguoiThucHien && $item->nguoiThucHien->hoSo)
                                                        {{ $item->nguoiThucHien->hoSo->ho ?? '' }}
                                                        {{ $item->nguoiThucHien->hoSo->ten ?? ($item->nguoiThucHien->ten_dang_nhap ?? 'N/A') }}
                                                    @elseif($item->nguoiThucHien)
                                                        {{ $item->nguoiThucHien->ten_dang_nhap ?? 'N/A' }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex gap-2 mt-2 flex-wrap">
                                            <a href="{{ route('admin.hop-dong.show', $item->hopDongCu->id ?? 0) }}"
                                                class="text-xs text-blue-600 hover:underline">Xem hợp đồng cũ</a>
                                            <span class="text-gray-300">|</span>
                                            <a href="{{ route('admin.hop-dong.show', $item->hopDongMoi->id ?? 0) }}"
                                                class="text-xs text-green-600 hover:underline">Xem hợp đồng mới</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endif

    </div>

    <script>
        function showTuChoiDuyetForm() {
            document.getElementById('tuChoiDuyetForm').style.display = 'block';
            document.getElementById('tuChoiDuyetForm').scrollIntoView({
                behavior: 'smooth'
            });
        }

        function hideTuChoiDuyetForm() {
            document.getElementById('tuChoiDuyetForm').style.display = 'none';
        }

        function showHuyForm() {
            document.getElementById('huyForm').style.display = 'block';
            document.getElementById('huyForm').scrollIntoView({
                behavior: 'smooth'
            });
        }

        function hideHuyForm() {
            document.getElementById('huyForm').style.display = 'none';
        }
    </script>
@endsection
