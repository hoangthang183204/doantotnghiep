@extends('layouts.admin')

@section('title', 'Chi tiết hợp đồng')

@section('content')
    <div class="space-y-6 bg-gray-50 dark:bg-[#0f172a] min-h-screen p-6">

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

        @if (session('success'))
            <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 text-green-700 px-4 py-3 rounded-xl">✅
                {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 text-red-700 px-4 py-3 rounded-xl">❌
                {{ session('error') }}</div>
        @endif

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
                        <div>{{ optional(optional($hopDong->nguoiDung)->hoSo)->ho ?? '' }}
                            {{ optional(optional($hopDong->nguoiDung)->hoSo)->ten ?? '' }}</div>
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
                                class="px-2 py-1 rounded-full text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">{{ $hopDong->loai_hop_dong == 'xac_dinh_thoi_han' ? 'Xác định thời hạn' : 'Không xác định' }}</span>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-32 text-gray-500">Ngày bắt đầu:</div>
                        <div>{{ \Carbon\Carbon::parse($hopDong->ngay_bat_dau)->format('d/m/Y') }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-32 text-gray-500">Ngày kết thúc:</div>
                        <div>
                            {{ $hopDong->ngay_ket_thuc ? \Carbon\Carbon::parse($hopDong->ngay_ket_thuc)->format('d/m/Y') : '---' }}
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-32 text-gray-500">Lương cơ bản:</div>
                        <div class="font-semibold text-green-600 dark:text-green-400">
                            {{ number_format($hopDong->luong_co_ban, 0, ',', '.') }} đ</div>
                    </div>

                    {{-- ===== PHỤ CẤP TỪ BẢNG PHU_CAP_NHAN_VIEN ===== --}}
                    <div class="flex">
                        <div class="w-32 text-gray-500">Phụ cấp:</div>
                        <div>
                            @php
                                $phuCapNhanViens = $hopDong->nguoiDung->phuCapNhanViens ?? collect();
                                $totalPhuCap = $phuCapNhanViens->sum('so_tien');
                            @endphp
                            @if ($phuCapNhanViens->count() > 0)
                                <span
                                    class="font-semibold text-blue-600 dark:text-blue-400">{{ number_format($totalPhuCap, 0, ',', '.') }}
                                    đ</span>
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
                                <span class="text-gray-400">{{ number_format($hopDong->phu_cap ?? 0, 0, ',', '.') }}
                                    đ</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex">
                        <div class="w-32 text-gray-500">Địa điểm:</div>
                        <div>{{ $hopDong->dia_diem_lam_viec }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-32 text-gray-500">Trạng thái HĐ:</div>
                        <div>
                            @switch($hopDong->trang_thai_hop_dong)
                                @case('hieu_luc')
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300">✅
                                        Hiệu lực</span>
                                @break

                                @case('chua_hieu_luc')
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300">⏳
                                        Chưa hiệu lực</span>
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
                    <div class="flex">
                        <div class="w-32 text-gray-500">Trạng thái ký:</div>
                        <div>
                            @switch($hopDong->trang_thai_ky)
                                @case('da_ky')
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300">✅
                                        Đã ký</span>
                                @break

                                @case('cho_ky')
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300">⏳
                                        Chờ ký</span>
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
                </div>
            </div>
        </div>
        {{-- Khối code hiển thị Bản Scan Hợp Đồng Ký Tay do nhân viên nộp --}}
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

        {{-- Thêm vào phần actions (khoảng dòng 200-220) --}}
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

        {{-- File hợp đồng gốc --}}
        @if ($hopDong->duong_dan_file)
            <div
                class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
                <div class="border-b px-6 py-4 bg-gray-50 dark:bg-gray-700/30">
                    <h3 class="text-lg font-semibold text-blue-600 dark:text-blue-400">📎 File hợp đồng gốc</h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-2">
                        @foreach (array_filter(explode(';', $hopDong->duong_dan_file)) as $file)
                            <a href="{{ asset('storage/' . trim($file)) }}" target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-lg transition text-blue-700 dark:text-blue-300">
                                📄 {{ basename(trim($file)) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- ============================================================ --}}
        {{-- NÚT TÁI KÝ HỢP ĐỒNG - CHỈ HIỆN KHI HẾT HẠN --}}
        {{-- ============================================================ --}}
        @if ($hopDong->trang_thai_hop_dong == 'het_han' && $hopDong->trang_thai_tai_ky != 'da_tai_ky')
            <div
                class="mt-4 p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl border border-purple-200 dark:border-purple-800">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h4 class="font-semibold text-purple-700 dark:text-purple-300 flex items-center gap-2">
                            <span class="text-xl">🔄</span> Tái ký hợp đồng
                        </h4>
                        <p class="text-sm text-purple-600 dark:text-purple-400 mt-1">
                            Hợp đồng đã hết hạn. Tạo hợp đồng mới để gia hạn.
                        </p>
                    </div>
                    <form action="{{ route('admin.hop-dong.tai-ky', $hopDong->id) }}" method="POST"
                        onsubmit="return confirm('🔄 Bạn có chắc muốn tái ký hợp đồng này?\n\nHợp đồng mới sẽ được tạo dựa trên thông tin hiện tại.\nHợp đồng cũ sẽ được đánh dấu là đã tái ký.')">
                        @csrf
                        <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl transition shadow-md hover:shadow-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            🔄 Tái ký hợp đồng
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
                            {{-- Nếu có lịch sử tái ký, hiển thị thêm --}}
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

        {{-- File hợp đồng đã ký --}}
        @if ($hopDong->file_hop_dong_da_ky)
            <div
                class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
                <div class="border-b px-6 py-4 bg-gray-50 dark:bg-gray-700/30">
                    <h3 class="text-lg font-semibold text-green-600 dark:text-green-400">✍️ File hợp đồng đã ký</h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-2">
                        @foreach (array_filter(explode(';', $hopDong->file_hop_dong_da_ky)) as $file)
                            <a href="{{ asset('storage/' . trim($file)) }}" target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 dark:bg-green-900/30 hover:bg-green-100 dark:hover:bg-green-900/50 rounded-lg transition text-green-700 dark:text-green-300">
                                ✅ {{ basename(trim($file)) }}
                            </a>
                        @endforeach
                    </div>
                    @if ($hopDong->thoi_gian_ky)
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-3">🕐 Ký lúc:
                            {{ \Carbon\Carbon::parse($hopDong->thoi_gian_ky)->format('d/m/Y H:i') }}</p>
                    @endif
                    @if ($hopDong->nguoi_ky_id)
                        <p class="text-sm text-gray-500 dark:text-gray-400">👤 Người ký:
                            {{ optional($hopDong->nguoiKy->hoSo)->ho ?? '' }}
                            {{ optional($hopDong->nguoiKy->hoSo)->ten ?? ($hopDong->nguoiKy->ten_dang_nhap ?? 'N/A') }}</p>
                    @endif
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
        @if ($hopDong->trang_thai_hop_dong == 'huy_bo')
            <div
                class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl shadow-sm overflow-hidden">
                <div class="border-b px-6 py-4 bg-red-100 dark:bg-red-900/30">
                    <h3 class="text-lg font-semibold text-red-700 dark:text-red-300">🚫 Thông tin hủy hợp đồng</h3>
                </div>
                <div class="p-6 space-y-2">
                    <div class="flex">
                        <div class="w-32 text-red-600 dark:text-red-400">Lý do hủy:</div>
                        <div class="text-red-700 dark:text-red-300">{{ $hopDong->ly_do_huy ?? 'Không có lý do' }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-32 text-red-600 dark:text-red-400">Người hủy:</div>
                        <div class="text-red-700 dark:text-red-300">{{ optional($hopDong->nguoiHuy->hoSo)->ho ?? '' }}
                            {{ optional($hopDong->nguoiHuy->hoSo)->ten ?? ($hopDong->nguoiHuy->ten_dang_nhap ?? 'N/A') }}
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

        {{-- Actions --}}
        <div class="flex flex-wrap gap-3">
            @php
                $user = auth()->user();
                $roleName = '';
                if ($user && $user->vaiTros) {
                    $roleName = $user->vaiTros->first()->name ?? '';
                }
                $isAdminOrHr = in_array($roleName, ['admin', 'hr']);
            @endphp

            @if ($user && $isAdminOrHr && $hopDong->trang_thai_hop_dong == 'tao_moi')
                <form action="{{ route('admin.hop-dong.gui-ky', $hopDong->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition">
                        📨 Gửi cho nhân viên ký
                    </button>
                </form>
            @endif

            @if (
                $user &&
                    $isAdminOrHr &&
                    in_array($hopDong->trang_thai_hop_dong, ['hieu_luc', 'chua_hieu_luc', 'het_han']) &&
                    $hopDong->trang_thai_ky != 'da_ky')
                <button onclick="showHuyForm()"
                    class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition">
                    ❌ Hủy hợp đồng
                </button>
            @endif

            @if ($user && $isAdminOrHr)
                <a href="{{ route('admin.hop-dong.edit', $hopDong->id) }}"
                    class="px-6 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-xl transition">
                    ✏️ Sửa hợp đồng
                </a>
            @endif

            @if ($user && $roleName == 'admin')
                <form action="{{ route('admin.hop-dong.destroy', $hopDong->id) }}" method="POST"
                    onsubmit="return confirm('Bạn có chắc muốn xóa hợp đồng này?')">
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

        {{-- ============================================================ --}}
        {{-- LỊCH SỬ TÁI KÝ --}}
        {{-- ============================================================ --}}
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
                            <span class="text-xl">🔄</span> Lịch sử tái ký
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
                                                <p class="font-medium text-gray-800 dark:text-white">
                                                    <span class="text-purple-600 dark:text-purple-400">Tái ký</span>
                                                    từ <span
                                                        class="font-mono">{{ $item->hopDongCu->so_hop_dong ?? 'N/A' }}</span>
                                                    → <span
                                                        class="font-mono">{{ $item->hopDongMoi->so_hop_dong ?? 'N/A' }}</span>
                                                </p>
                                                @if ($item->ly_do_tai_ky)
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                        📌 {{ $item->ly_do_tai_ky }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="text-right text-sm text-gray-500 dark:text-gray-400">
                                                <p>🕐 {{ $item->created_at->format('d/m/Y H:i') }}</p>
                                                <p>👤 {{ optional($item->nguoiThucHien->hoSo)->ho ?? '' }}
                                                    {{ optional($item->nguoiThucHien->hoSo)->ten ?? ($item->nguoiThucHien->ten_dang_nhap ?? 'N/A') }}
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
