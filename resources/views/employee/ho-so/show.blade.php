@extends('layouts.employee')

@section('title', 'Hồ sơ cá nhân - ' . $user->ho_ten)

@section('content')

    <div class="space-y-6 max-w-6xl mx-auto text-gray-900 dark:text-gray-100">

        {{-- ================= HEADER ================= --}}
        <div
            class="rounded-2xl p-6
        bg-white dark:bg-slate-800
        border border-gray-200 dark:border-slate-700
        shadow-sm
        text-gray-800 dark:text-white">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">

                <div class="flex items-center gap-4">

                    @if ($hoSo?->anh_dai_dien)
                        <img src="{{ asset('storage/' . $hoSo->anh_dai_dien) }}"
                            class="w-20 h-20 rounded-2xl object-cover border-4 border-gray-400 dark:border-slate-500">
                    @else
                        <div
                            class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-3xl text-white font-bold">
                            {{ strtoupper(substr($user->ho_ten, 0, 1)) }}
                        </div>
                    @endif

                    <div>
                        <h1 class="text-2xl font-bold">
                            {{ $user->ho_ten }}
                        </h1>

                        <p class="text-gray-500 dark:text-gray-400">
                            {{ $user->email }}
                        </p>

                        <div class="flex flex-wrap gap-2 mt-2">
                            <span class="px-3 py-1 rounded-full bg-gray-100 dark:bg-slate-700 text-sm">
                                {{ $user->vai_tro?->ten_hien_thi }}
                            </span>
                            <span class="px-3 py-1 rounded-full bg-gray-100 dark:bg-slate-700 text-sm">
                                {{ $user->phong_ban?->ten_phong_ban }}
                            </span>
                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">
                                🟢 Đang làm việc
                            </span>
                        </div>
                    </div>

                </div>

                {{-- Nút Điều chỉnh hồ sơ --}}
                <a href="{{ route('employee.ho-so.index') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl
    bg-blue-600 text-white hover:bg-blue-700
    transition shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Điều chỉnh hồ sơ
                </a>

            </div>

        </div>

        {{-- ================= TAB NAVIGATION ================= --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-2">
            <nav class="flex flex-wrap gap-1" id="tabNav">
                <button class="tab-btn active px-5 py-2.5 rounded-lg text-sm font-medium transition" data-tab="tab1">
                    📋 Thông tin
                </button>
                <button class="tab-btn px-5 py-2.5 rounded-lg text-sm font-medium transition" data-tab="tab2">
                    💼 Công việc & HĐ
                </button>
                <button class="tab-btn px-5 py-2.5 rounded-lg text-sm font-medium transition" data-tab="tab3">
                    📄 Năng lực & CV
                </button>
                <button class="tab-btn px-5 py-2.5 rounded-lg text-sm font-medium transition" data-tab="tab4">
                    💰 Lương thưởng
                </button>
                <button class="tab-btn px-5 py-2.5 rounded-lg text-sm font-medium transition" data-tab="tab5">
                    🛡️ Bảo hiểm & Thuế
                </button>
                <button class="tab-btn px-5 py-2.5 rounded-lg text-sm font-medium transition" data-tab="tab6">
                    🏆 Đào tạo & Kỷ luật
                </button>
            </nav>
        </div>

        {{-- ================= TAB CONTENT ================= --}}
        <div class="space-y-6">

            {{-- ========================================================== --}}
            {{-- TAB 1: THÔNG TIN CÁ NHÂN --}}
            {{-- ========================================================== --}}
            <div id="tab1" class="tab-content">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- Cột trái: Thông tin cá nhân --}}
                    <div
                        class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-slate-700">
                        <h3
                            class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-slate-700 pb-3 mb-4">
                            🧑‍💼 Thông tin cá nhân
                        </h3>

                        <div class="space-y-3">
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-slate-700">
                                <span class="text-gray-500 dark:text-gray-400">Họ và tên</span>
                                <span class="font-medium">{{ $hoSo?->ho }} {{ $hoSo?->ten }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-slate-700">
                                <span class="text-gray-500 dark:text-gray-400">Mã nhân viên</span>
                                <span class="font-mono font-medium">{{ $hoSo?->ma_nhan_vien ?? '---' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-slate-700">
                                <span class="text-gray-500 dark:text-gray-400">Email công ty</span>
                                <span class="text-blue-600 dark:text-blue-400">{{ $user->email }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-slate-700">
                                <span class="text-gray-500 dark:text-gray-400">Số điện thoại</span>
                                <span class="font-medium">{{ $hoSo?->so_dien_thoai ?? '---' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-slate-700">
                                <span class="text-gray-500 dark:text-gray-400">Ngày sinh</span>
                                <span class="font-medium">{{ $hoSo?->ngay_sinh?->format('d/m/Y') ?? '---' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-slate-700">
                                <span class="text-gray-500 dark:text-gray-400">Giới tính</span>
                                <span class="font-medium">{{ $hoSo?->gioi_tinh_text ?? '---' }}</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-gray-500 dark:text-gray-400">Tình trạng hôn nhân</span>
                                <span class="font-medium">{{ $hoSo?->tinh_trang_hon_nhan_text ?? '---' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Cột phải: Địa chỉ & Giấy tờ --}}
                    <div
                        class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-slate-700">
                        <h3
                            class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-slate-700 pb-3 mb-4">
                            🏠 Địa chỉ & Giấy tờ
                        </h3>

                        <div class="space-y-3">
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-slate-700">
                                <span class="text-gray-500 dark:text-gray-400">Địa chỉ hiện tại</span>
                                <span class="font-medium text-right">{{ $hoSo?->dia_chi_hien_tai ?? '---' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-slate-700">
                                <span class="text-gray-500 dark:text-gray-400">Địa chỉ thường trú</span>
                                <span class="font-medium text-right">{{ $hoSo?->dia_chi_thuong_tru ?? '---' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-slate-700">
                                <span class="text-gray-500 dark:text-gray-400">CMND/CCCD</span>
                                <span class="font-mono font-medium">{{ $hoSo?->cmnd_cccd ?? '---' }}</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-gray-500 dark:text-gray-400">Số hộ chiếu</span>
                                <span class="font-medium">{{ $hoSo?->so_ho_chieu ?? '---' }}</span>
                            </div>
                        </div>

                        {{-- Ảnh CCCD --}}
                        @if ($hoSo?->anh_cccd_truoc || $hoSo?->anh_cccd_sau)
                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-slate-700">
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">🪪 Ảnh CCCD</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        @if ($hoSo->anh_cccd_truoc)
                                            <img src="{{ asset('storage/' . $hoSo->anh_cccd_truoc) }}" alt="CCCD mặt trước"
                                                class="w-full rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm cursor-pointer hover:opacity-90 transition"
                                                onclick="openFilePreview('{{ asset('storage/' . $hoSo->anh_cccd_truoc) }}', 'CCCD mặt trước')">
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-center">Mặt trước
                                            </p>
                                        @else
                                            <div
                                                class="w-full h-32 bg-gray-100 dark:bg-slate-700 rounded-lg border-2 border-dashed border-gray-300 dark:border-slate-600 flex items-center justify-center">
                                                <span class="text-gray-400 text-sm">Chưa có ảnh</span>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-center">Mặt trước
                                            </p>
                                        @endif
                                    </div>
                                    <div>
                                        @if ($hoSo->anh_cccd_sau)
                                            <img src="{{ asset('storage/' . $hoSo->anh_cccd_sau) }}" alt="CCCD mặt sau"
                                                class="w-full rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm cursor-pointer hover:opacity-90 transition"
                                                onclick="openFilePreview('{{ asset('storage/' . $hoSo->anh_cccd_sau) }}', 'CCCD mặt sau')">
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-center">Mặt sau</p>
                                        @else
                                            <div
                                                class="w-full h-32 bg-gray-100 dark:bg-slate-700 rounded-lg border-2 border-dashed border-gray-300 dark:border-slate-600 flex items-center justify-center">
                                                <span class="text-gray-400 text-sm">Chưa có ảnh</span>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-center">Mặt sau</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>

                {{-- Liên hệ khẩn cấp --}}
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 mt-6 border border-gray-200 dark:border-slate-700">
                    <h3
                        class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-slate-700 pb-3 mb-4">
                        📞 Liên hệ khẩn cấp
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 dark:bg-slate-700 rounded-lg p-3">
                            <span class="text-gray-500 dark:text-gray-400 text-sm block">Họ tên</span>
                            <p class="font-medium text-lg">{{ $hoSo?->lien_he_khan_cap ?? '---' }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-700 rounded-lg p-3">
                            <span class="text-gray-500 dark:text-gray-400 text-sm block">Số điện thoại</span>
                            <p class="font-medium text-lg">{{ $hoSo?->sdt_khan_cap ?? '---' }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-700 rounded-lg p-3">
                            <span class="text-gray-500 dark:text-gray-400 text-sm block">Mối quan hệ</span>
                            <p class="font-medium text-lg">{{ $hoSo?->quan_he_khan_cap ?? '---' }}</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ========================================================== --}}
            {{-- TAB 2: CÔNG VIỆC & HỢP ĐỒNG --}}
            {{-- ========================================================== --}}
            <div id="tab2" class="tab-content hidden">

                <div
                    class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-slate-700">

                    <h3
                        class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-slate-700 pb-3 mb-4">
                        💼 Thông tin công việc
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="space-y-3">
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-slate-700">
                                <span class="text-gray-500 dark:text-gray-400">Chức vụ</span>
                                <span class="font-medium">{{ $user->chuc_vu?->ten ?? '---' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-slate-700">
                                <span class="text-gray-500 dark:text-gray-400">Phòng ban</span>
                                <span class="font-medium">{{ $user->phong_ban?->ten_phong_ban ?? '---' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-slate-700">
                                <span class="text-gray-500 dark:text-gray-400">Ngày vào làm</span>
                                <span class="font-medium">{{ $user->created_at?->format('d/m/Y') ?? '---' }}</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-gray-500 dark:text-gray-400">Thâm niên</span>
                                <span class="font-medium text-green-600">{{ $user->hoSo?->tham_nien ?? '---' }}</span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-slate-700">
                                <span class="text-gray-500 dark:text-gray-400">Loại hợp đồng</span>
                                <span class="font-medium">{{ $hopDongHieuLuc?->ten_loai_hop_dong ?? '---' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100 dark:border-slate-700">
                                <span class="text-gray-500 dark:text-gray-400">Ngày ký HĐ</span>
                                <span
                                    class="font-medium">{{ $hopDongHieuLuc?->ngay_bat_dau?->format('d/m/Y') ?? '---' }}</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-gray-500 dark:text-gray-400">Ngày hết hạn HĐ</span>
                                <span
                                    class="font-medium">{{ $hopDongHieuLuc?->ngay_ket_thuc?->format('d/m/Y') ?? 'Không áp dụng' }}</span>
                            </div>
                        </div>

                    </div>

                    {{-- Lịch sử hợp đồng --}}
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-slate-700">

                        <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">📄 Lịch sử hợp đồng lao động</h4>

                        @if ($hoSo?->hop_dong && $hoSo->hop_dong->count() > 0)
                            <div class="space-y-3">
                                @foreach ($hoSo->hop_dong as $item)
                                    <div
                                        class="bg-gray-50 dark:bg-slate-700 rounded-lg p-4 border-l-4
                                        {{ $item->trang_thai_hop_dong == 'hieu_luc'
                                            ? 'border-green-500'
                                            : ($item->trang_thai_hop_dong == 'het_han'
                                                ? 'border-gray-400'
                                                : ($item->trang_thai_hop_dong == 'chua_hieu_luc'
                                                    ? 'border-yellow-500'
                                                    : 'border-red-500')) }}">

                                        <div class="flex justify-between items-start">
                                            <div>
                                                <span
                                                    class="font-medium">{{ $item->ten_loai_hop_dong ?? $item->loai_hop_dong }}</span>
                                                <span
                                                    class="text-sm text-gray-500 dark:text-gray-400 ml-2">({{ $item->so_hop_dong }})</span>
                                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $item->ngay_bat_dau?->format('d/m/Y') ?? '---' }}
                                                    →
                                                    {{ $item->ngay_ket_thuc?->format('d/m/Y') ?? 'Không xác định' }}
                                                </div>

                                                @php
                                                    $filePath = $item->file_hop_dong_da_ky
                                                        ? storage_path('app/public/' . $item->file_hop_dong_da_ky)
                                                        : null;
                                                    $fileExists = $filePath && file_exists($filePath);
                                                @endphp

                                                @if ($item->file_hop_dong_da_ky && $fileExists)
                                                    <div class="mt-3 flex flex-wrap gap-2">
                                                        <button
                                                            onclick="openFilePreview{{ route('employee.ho-so.view-contract', $item->id) }}', 'Hợp đồng {{ $item->so_hop_dong }}')"
                                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition flex items-center gap-1">
                                                            <i class="fa-regular fa-eye"></i> Xem
                                                        </button>
                                                        <a href="{{ asset('storage/' . $item->file_hop_dong_da_ky) }}"
                                                            download
                                                            class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition flex items-center gap-1">
                                                            <i class="fa-solid fa-download"></i> Tải
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="mt-3">
                                                        <span class="text-sm text-gray-400 flex items-center gap-2">
                                                            📎 Chưa có file hợp đồng
                                                        </span>
                                                    </div>
                                                @endif

                                                @if ($item->nguoi_ky_id)
                                                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                        ✍️ Ký bởi: {{ $item->nguoiKy?->ho ?? '' }}
                                                        {{ $item->nguoiKy?->ten ?? '' }}
                                                        @if ($item->thoi_gian_ky)
                                                            •
                                                            {{ \Carbon\Carbon::parse($item->thoi_gian_ky)->format('d/m/Y H:i') }}
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>

                                            <span
                                                class="text-xs px-2 py-1 {{ $item->mau_trang_thai }} rounded-full whitespace-nowrap ml-2">
                                                {{ $item->ten_trang_thai }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có hợp đồng lao động</p>
                        @endif

                    </div>

                </div>

            </div>

            {{-- ========================================================== --}}
            {{-- TAB 3: NĂNG LỰC & CV --}}
            {{-- ========================================================== --}}
            <div id="tab3" class="tab-content hidden">

                <div
                    class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-slate-700">

                    <h3
                        class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-slate-700 pb-3 mb-4">
                        📄 Hồ sơ năng lực & CV
                    </h3>

                    {{-- CV --}}
                    <div
                        class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-lg p-4 mb-6 border border-blue-200 dark:border-blue-800">

                        <div class="flex justify-between items-center">
                            <div>
                                <span class="font-medium">📎 CV đính kèm</span>
                                @php $cv = $hoSo?->cv; @endphp
                                @if ($cv)
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $cv->ten_file_goc }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $cv->kich_thuoc }} •
                                        {{ $cv->loai_mime }}</p>
                                @else
                                    <p class="text-sm text-gray-400">Chưa có CV</p>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                @if ($cv)
                                    <button
                                        onclick="openFilePreview('{{ route('employee.ho-so.view-cv', $cv->id) }}', 'CV - {{ $user->ho_ten }}')"
                                        class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                    <a href="{{ asset('storage/' . $cv->duong_dan_file) }}" download
                                        class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg transition">
                                        <i class="fa-solid fa-download"></i>
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">Chưa có CV</span>
                                @endif
                            </div>
                        </div>

                    </div>

                    {{-- Kỹ năng --}}
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">🛠️ Kỹ năng chuyên môn</h4>

                        @if ($hoSo?->ky_nang && $hoSo->ky_nang->count() > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach ($hoSo->ky_nang as $item)
                                    <span
                                        class="px-3 py-1.5 {{ $item->mau_cap_do }} rounded-full text-sm font-medium shadow-sm">
                                        {{ $item->ten_ky_nang }}
                                        <span class="text-xs opacity-70">({{ $item->cap_do }})</span>
                                    </span>
                                @endforeach
                            </div>
                            <div class="mt-2 text-xs text-gray-400">📌 Tổng: {{ $hoSo->ky_nang->count() }} kỹ năng</div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có kỹ năng</p>
                        @endif
                    </div>

                    {{-- Chứng chỉ --}}
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">🏅 Chứng chỉ</h4>

                        @if ($hoSo?->chung_chi && $hoSo->chung_chi->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach ($hoSo->chung_chi as $item)
                                    <div
                                        class="bg-gray-50 dark:bg-slate-700 rounded-lg p-3 border border-gray-200 dark:border-slate-600 hover:shadow-md transition">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-medium text-gray-800 dark:text-white">
                                                    {{ $item->ten_chung_chi }}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">🏛️
                                                    {{ $item->to_chuc_cap }}</p>
                                            </div>
                                            <span class="text-xs px-2 py-1 {{ $item->mau_trang_thai }} rounded-full">
                                                {{ $item->trang_thai_hien_thi }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between mt-2 text-xs text-gray-500 dark:text-gray-400">
                                            <span>📅 {{ $item->nam_cap }}</span>
                                            @if ($item->ngay_het_han)
                                                <span>⏳ Hết hạn: {{ $item->ngay_het_han->format('d/m/Y') }}</span>
                                            @else
                                                <span>♾️ Không hết hạn</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có chứng chỉ</p>
                        @endif
                    </div>

                    {{-- Dự án --}}
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-slate-700">

                        <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">🚀 Dự án đã tham gia</h4>

                        @if ($hoSo?->du_an && $hoSo->du_an->count() > 0)
                            <div class="space-y-3">
                                @foreach ($hoSo->du_an as $item)
                                    <div
                                        class="bg-gray-50 dark:bg-slate-700 rounded-lg p-4 border-l-4 {{ $item->mau_border }} hover:shadow-md transition">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <span
                                                    class="font-medium text-gray-800 dark:text-white">{{ $item->ten_du_an }}</span>
                                                <span
                                                    class="text-sm text-gray-500 dark:text-gray-400 ml-2">({{ $item->vai_tro }})</span>
                                            </div>
                                            <span class="text-xs px-2 py-1 {{ $item->mau_trang_thai }} rounded-full">
                                                {{ $item->icon_trang_thai }} {{ $item->trang_thai }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            📅 {{ $item->ngay_bat_dau->format('d/m/Y') }} →
                                            {{ $item->ngay_ket_thuc ? $item->ngay_ket_thuc->format('d/m/Y') : 'Đang thực hiện' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có dự án</p>
                        @endif

                    </div>

                </div>

            </div>

            {{-- ========================================================== --}}
            {{-- TAB 4: LƯƠNG THƯỞNG --}}
            {{-- ========================================================== --}}
            <div id="tab4" class="tab-content hidden">

                <div
                    class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-slate-700">

                    <h3
                        class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-slate-700 pb-3 mb-4">
                        💰 Thông tin lương thưởng
                    </h3>

                    {{-- Thông tin ngân hàng --}}
                    <div
                        class="bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 border-2 border-green-200 dark:border-green-800 rounded-xl p-5 mb-6 shadow-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-2xl">🏦</span>
                            <h4 class="text-lg font-bold text-gray-800 dark:text-white">Thông tin nhận lương</h4>
                            <span
                                class="ml-auto text-xs px-3 py-1 bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200 rounded-full font-medium">Chi
                                trả hàng tháng</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div
                                class="bg-white dark:bg-slate-800 rounded-lg p-3 shadow-sm border border-gray-200 dark:border-slate-700">
                                <span class="text-xs text-gray-500 dark:text-gray-400 uppercase block font-medium">Chủ tài
                                    khoản</span>
                                <p class="font-semibold text-gray-800 dark:text-white text-lg">
                                    {{ $hoSo?->chu_tai_khoan ?? 'Chưa cập nhật' }}</p>
                            </div>
                            <div
                                class="bg-white dark:bg-slate-800 rounded-lg p-3 shadow-sm border border-gray-200 dark:border-slate-700">
                                <span class="text-xs text-gray-500 dark:text-gray-400 uppercase block font-medium">Số tài
                                    khoản</span>
                                <p class="font-mono font-bold text-gray-800 dark:text-white text-lg">
                                    {{ $hoSo?->so_tai_khoan ?? 'Chưa cập nhật' }}</p>
                            </div>
                            <div
                                class="bg-white dark:bg-slate-800 rounded-lg p-3 shadow-sm border border-gray-200 dark:border-slate-700">
                                <span class="text-xs text-gray-500 dark:text-gray-400 uppercase block font-medium">Ngân
                                    hàng</span>
                                <p class="font-semibold text-gray-800 dark:text-white text-lg">
                                    {{ $hoSo?->ten_ngan_hang ?? 'Chưa cập nhật' }}</p>
                            </div>
                            <div
                                class="bg-white dark:bg-slate-800 rounded-lg p-3 shadow-sm border border-gray-200 dark:border-slate-700">
                                <span class="text-xs text-gray-500 dark:text-gray-400 uppercase block font-medium">Chi
                                    nhánh / PGD</span>
                                <p class="font-semibold text-gray-800 dark:text-white text-lg">
                                    {{ $hoSo?->chi_nhanh_ngan_hang ?? 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- 3 thẻ lương --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div
                            class="bg-gray-50 dark:bg-slate-700 rounded-lg p-4 text-center border border-gray-200 dark:border-slate-600">
                            <p class="text-sm text-gray-500 dark:text-gray-400">📋 Lương cơ bản</p>
                            <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                {{ number_format($luongCoBanHienTai, 0, ',', '.') }} ₫
                            </p>
                            @if ($hopDongHieuLuc)
                                <p class="text-xs text-gray-400">📄 {{ $hopDongHieuLuc->so_hop_dong }}</p>
                            @endif
                        </div>
                        <div
                            class="bg-gray-50 dark:bg-slate-700 rounded-lg p-4 text-center border border-gray-200 dark:border-slate-600">
                            <p class="text-sm text-gray-500 dark:text-gray-400">📊 Tổng thu nhập</p>
                            <p class="text-lg font-bold text-green-600 dark:text-green-400">
                                {{ number_format($tongThuNhap, 0, ',', '.') }} ₫
                            </p>
                            <p class="text-xs text-gray-400">
                                = {{ number_format($luongCoBanHienTai, 0, ',', '.') }}
                                @if ($tongPhuCap > 0)
                                    + {{ number_format($tongPhuCap, 0, ',', '.') }}
                                @endif
                                @if ($coTangCa)
                                    + {{ number_format($tienTangCa, 0, ',', '.') }}
                                @endif
                            </p>
                        </div>
                        <div
                            class="bg-gray-50 dark:bg-slate-700 rounded-lg p-4 text-center border border-gray-200 dark:border-slate-600">
                            <p class="text-sm text-gray-500 dark:text-gray-400">💰 Thực nhận</p>
                            <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                {{ number_format($thucNhan, 0, ',', '.') }} ₫
                            </p>
                            <p class="text-xs text-gray-400">
                                = {{ number_format($tongThuNhap, 0, ',', '.') }}
                                - {{ number_format($tongBaoHiem, 0, ',', '.') }}
                                - {{ number_format($thueTncn, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    {{-- Phụ cấp --}}
                    @if ($phuCapChiTiets->count() > 0)
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">📌 Phụ cấp</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($phuCapChiTiets as $pc)
                                    <span
                                        class="px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 rounded-full text-sm border border-blue-200 dark:border-blue-800">
                                        {{ $pc->ten }}:
                                        <strong>{{ number_format($pc->so_tien_mac_dinh, 0, ',', '.') }} ₫</strong>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">📌 Không có phụ cấp trong hợp đồng hiện tại
                            </p>
                        </div>
                    @endif

                    {{-- Tăng ca --}}
                    @if ($coTangCa)
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">⏰ Tăng ca</h4>
                            <span
                                class="px-3 py-1.5 bg-yellow-50 dark:bg-yellow-900/20 rounded-full text-sm border border-yellow-200 dark:border-yellow-800">
                                Tiền tăng ca: <strong
                                    class="text-yellow-600 dark:text-yellow-400">{{ number_format($tienTangCa, 0, ',', '.') }}
                                    ₫</strong>
                            </span>
                        </div>
                    @endif

                    {{-- Lịch sử lương --}}
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-slate-700">
                        <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">📈 Lịch sử lương</h4>
                        @if ($hoSo?->lich_su_luong && $hoSo->lich_su_luong->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr
                                            class="border-b border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-700">
                                            <th class="text-left p-2 font-semibold">Kỳ lương</th>
                                            <th class="text-left p-2 font-semibold">Ngày công</th>
                                            <th class="text-left p-2 font-semibold">Lương CB</th>
                                            <th class="text-left p-2 font-semibold">Thực nhận</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($hoSo->lich_su_luong as $item)
                                            <tr
                                                class="border-b border-gray-100 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                                                <td class="p-2 font-medium">Tháng
                                                    {{ $item->luong_thang }}/{{ $item->luong_nam }}</td>
                                                <td class="p-2">{{ $item->so_ngay_cong ?? 0 }}</td>
                                                <td class="p-2">
                                                    {{ number_format($item->luong_co_ban ?? 0, 0, ',', '.') }}</td>
                                                <td class="p-2 font-bold text-green-600">
                                                    {{ number_format($item->luong_thuc_nhan ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có lịch sử lương</p>
                        @endif
                    </div>

                </div>

            </div>

            {{-- ========================================================== --}}
            {{-- TAB 5: BẢO HIỂM & THUẾ --}}
            {{-- ========================================================== --}}
            <div id="tab5" class="tab-content hidden">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- Bảo hiểm --}}
                    <div
                        class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-slate-700">
                        <h3
                            class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-slate-700 pb-3 mb-4">
                            🛡️ Bảo hiểm xã hội
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-slate-700">
                                <span class="text-gray-500 dark:text-gray-400">Số sổ BHXH</span>
                                <span
                                    class="font-mono font-medium text-gray-800 dark:text-white">{{ $hoSo?->so_bhxh ?? 'Chưa cập nhật' }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-slate-700">
                                <span class="text-gray-500 dark:text-gray-400">Mã số thuế TNCN</span>
                                <span
                                    class="font-mono font-medium text-gray-800 dark:text-white">{{ $hoSo?->ma_so_thue ?? 'Chưa cập nhật' }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-slate-700">
                                <span class="text-gray-500 dark:text-gray-400">Nơi đăng ký KCB</span>
                                <span
                                    class="font-medium text-gray-800 dark:text-white">{{ $hoSo?->noi_dang_ky_kcb ?? 'Chưa cập nhật' }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-slate-700">
                                <span class="text-gray-500 dark:text-gray-400">Mức lương đóng BHXH</span>
                                <span
                                    class="font-medium text-green-600 dark:text-green-400">{{ number_format($luongDongBhxh, 0, ',', '.') }}
                                    VNĐ</span>
                            </div>

                            {{-- Chi tiết đóng BH --}}
                            <div class="bg-gray-50 dark:bg-slate-700 rounded-lg p-3 mt-2">
                                <div class="flex justify-between py-1">
                                    <span class="text-gray-500 dark:text-gray-400">BHXH (8%)</span>
                                    <span class="font-medium text-blue-600">{{ number_format($bhxh, 0, ',', '.') }}
                                        VNĐ</span>
                                </div>
                                <div class="flex justify-between py-1 border-t border-gray-200 dark:border-slate-600">
                                    <span class="text-gray-500 dark:text-gray-400">BHYT (1.5%)</span>
                                    <span class="font-medium text-blue-600">{{ number_format($bhyt, 0, ',', '.') }}
                                        VNĐ</span>
                                </div>
                                <div class="flex justify-between py-1 border-t border-gray-200 dark:border-slate-600">
                                    <span class="text-gray-500 dark:text-gray-400">BHTN (1%)</span>
                                    <span class="font-medium text-blue-600">{{ number_format($bhtn, 0, ',', '.') }}
                                        VNĐ</span>
                                </div>
                                <div
                                    class="flex justify-between py-2 border-t-2 border-gray-300 dark:border-slate-500 font-bold mt-1">
                                    <span class="text-gray-700 dark:text-gray-300">Tổng đóng (10.5%)</span>
                                    <span class="text-red-600">{{ number_format($tongBaoHiem, 0, ',', '.') }} VNĐ</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Thuế TNCN --}}
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 rounded-xl shadow-sm p-6 border border-blue-200 dark:border-blue-800">
                        <h3
                            class="text-lg font-semibold text-gray-800 dark:text-white border-b border-blue-200 dark:border-blue-700 pb-3 mb-4">
                            🏛️ Thuế TNCN
                        </h3>

                        {{-- Tổng thu nhập --}}
                        <div class="flex justify-between py-1 text-sm border-t border-gray-200 dark:border-blue-700">
                            <span class="text-gray-500 dark:text-gray-400">📊 Tổng thu nhập</span>
                            <span
                                class="font-medium text-gray-700 dark:text-gray-300">{{ number_format($tongThuNhap, 0, ',', '.') }}
                                ₫</span>
                        </div>
                        <div class="pl-4 text-xs text-gray-400">
                            = {{ number_format($luongCoBanHienTai, 0, ',', '.') }}
                            @if ($tongPhuCap > 0)
                                + {{ number_format($tongPhuCap, 0, ',', '.') }}
                            @endif
                            @if ($coTangCa)
                                + {{ number_format($tienTangCa, 0, ',', '.') }}
                            @endif
                        </div>

                        {{-- Bảo hiểm --}}
                        <div class="flex justify-between py-1 text-sm border-t border-gray-200 dark:border-blue-700">
                            <span class="text-gray-500 dark:text-gray-400">🔻 Bảo hiểm (10.5%)</span>
                            <span class="font-medium text-red-600">-{{ number_format($tongBaoHiem, 0, ',', '.') }}
                                ₫</span>
                        </div>

                        {{-- Thu nhập chịu thuế --}}
                        <div
                            class="flex justify-between py-1 text-sm border-t border-gray-200 dark:border-blue-700 font-medium">
                            <span class="text-gray-600 dark:text-gray-300">📝 Thu nhập chịu thuế</span>
                            <span class="font-bold {{ $thuNhapChiuThue > 0 ? 'text-orange-600' : 'text-green-600' }}">
                                {{ number_format($thuNhapChiuThue, 0, ',', '.') }} ₫
                            </span>
                        </div>
                        <div class="text-xs text-gray-400 pl-4">
                            = {{ number_format($tongThuNhap, 0, ',', '.') }} -
                            {{ number_format($tongBaoHiem, 0, ',', '.') }}
                        </div>

                        {{-- Thuế TNCN --}}
                        <div class="flex justify-between py-2 mt-1 border-t-2 border-blue-300 dark:border-blue-700">
                            <span class="font-semibold text-gray-700 dark:text-gray-300">🏛️ Thuế TNCN</span>
                            <span class="font-bold {{ $thueTncn > 0 ? 'text-red-600' : 'text-green-600' }} text-lg">
                                {{ number_format($thueTncn, 0, ',', '.') }} ₫
                            </span>
                        </div>

                        @if ($thueTncn > 0)
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">ℹ️ Áp dụng biểu thuế lũy tiến từng
                                phần</div>
                        @else
                            <div class="text-xs text-green-600 dark:text-green-400 mt-1">✅ Không phải nộp thuế</div>
                        @endif

                        {{-- Thực nhận --}}
                        <div
                            class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border-2 border-green-300 dark:border-green-700 mt-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700 dark:text-gray-300 font-bold text-lg">💰 THỰC NHẬN</span>
                                <span class="text-2xl font-bold text-green-600 dark:text-green-400">
                                    {{ number_format($thucNhan, 0, ',', '.') }} ₫
                                </span>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                = {{ number_format($tongThuNhap, 0, ',', '.') }}
                                - {{ number_format($tongBaoHiem, 0, ',', '.') }}
                                - {{ number_format($thueTncn, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            {{-- ========================================================== --}}
            {{-- TAB 6: ĐÀO TẠO & KỶ LUẬT --}}
            {{-- ========================================================== --}}
            <div id="tab6" class="tab-content hidden">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- Đào tạo --}}
                    <div
                        class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-slate-700">
                        <h3
                            class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-slate-700 pb-3 mb-4">
                            🎓 Đào tạo đã tham gia
                        </h3>

                        @if ($hoSo?->dao_tao && $hoSo->dao_tao->count() > 0)
                            <div class="space-y-3">
                                @foreach ($hoSo->dao_tao as $item)
                                    <div class="bg-gray-50 dark:bg-slate-700 rounded-lg p-3 border-l-4 border-blue-500">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <span
                                                    class="font-medium text-gray-800 dark:text-white">{{ $item->ten_khoa_hoc }}</span>
                                                <span
                                                    class="text-sm text-gray-500 dark:text-gray-400 ml-2">({{ $item->to_chuc ?? 'N/A' }})</span>
                                            </div>
                                            @if ($item->co_chung_chi)
                                                <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-full">📜
                                                    Có chứng chỉ</span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            📅 {{ $item->ngay_bat_dau->format('d/m/Y') }} →
                                            {{ $item->ngay_ket_thuc ? $item->ngay_ket_thuc->format('d/m/Y') : 'Đang học' }}
                                        </div>
                                        @if ($item->ket_qua)
                                            <div class="text-sm text-green-600 dark:text-green-400 mt-1">✅ Kết quả:
                                                {{ $item->ket_qua }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 dark:bg-slate-700/50 rounded-lg">
                                <div class="text-4xl mb-2">📚</div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có khóa đào tạo nào</p>
                            </div>
                        @endif
                    </div>

                    {{-- Khen thưởng & Kỷ luật --}}
                    <div
                        class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-slate-700">
                        <h3
                            class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-slate-700 pb-3 mb-4">
                            ⚖️ Khen thưởng & Kỷ luật
                        </h3>

                        @if ($hoSo?->khen_thuong_ky_luat && $hoSo->khen_thuong_ky_luat->count() > 0)
                            <div class="space-y-3">
                                @foreach ($hoSo->khen_thuong_ky_luat as $item)
                                    <div class="rounded-lg p-3 {{ $item->mau_loai }}">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <span class="font-medium text-gray-800 dark:text-white">
                                                    {{ $item->loai_text }}: {{ $item->ten }}
                                                </span>
                                                @if ($item->so_tien)
                                                    <span
                                                        class="text-sm {{ $item->loai == 'khen_thuong' ? 'text-green-600' : 'text-red-600' }} ml-2">
                                                        ({{ $item->loai == 'khen_thuong' ? '+' : '-' }}{{ number_format($item->so_tien, 0, ',', '.') }}
                                                        VNĐ)
                                                    </span>
                                                @endif
                                            </div>
                                            <span
                                                class="text-sm text-gray-500 dark:text-gray-400">{{ $item->ngay->format('d/m/Y') }}</span>
                                        </div>
                                        @if ($item->noi_dung)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                {{ $item->noi_dung }}</p>
                                        @endif
                                        @if ($item->hinh_thuc)
                                            <span class="text-xs text-gray-500">📌 Hình thức:
                                                {{ $item->hinh_thuc }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 dark:bg-slate-700/50 rounded-lg">
                                <div class="text-4xl mb-2">⚖️</div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có khen thưởng hoặc kỷ luật</p>
                            </div>
                        @endif
                    </div>

                </div>

                {{-- Thống kê tổng hợp --}}
                <div
                    class="mt-6 bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-slate-700">
                    <h3
                        class="text-lg font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-slate-700 pb-3 mb-4">
                        📊 Tổng hợp
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div
                            class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 text-center border border-blue-200 dark:border-blue-800">
                            <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                                {{ $hoSo?->dao_tao?->count() ?? 0 }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Khóa đào tạo</div>
                        </div>
                        <div
                            class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 text-center border border-green-200 dark:border-green-800">
                            <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                                {{ $hoSo?->khen_thuong_ky_luat?->where('loai', 'khen_thuong')->count() ?? 0 }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Khen thưởng</div>
                        </div>
                        <div
                            class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 text-center border border-red-200 dark:border-red-800">
                            <div class="text-3xl font-bold text-red-600 dark:text-red-400">
                                {{ $hoSo?->khen_thuong_ky_luat?->where('loai', 'ky_luat')->count() ?? 0 }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Kỷ luật</div>
                        </div>
                        <div
                            class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 text-center border border-purple-200 dark:border-purple-800">
                            <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                                {{ $hoSo?->nguoiPhuThuoc?->count() ?? 0 }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Người phụ thuộc</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    {{-- ============================================================ --}}
    {{-- MODAL XEM TRƯỚC FILE --}}
    {{-- ============================================================ --}}
    <div id="filePreviewModal"
        class="fixed inset-0 z-50 hidden bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-5xl max-h-[95vh] flex flex-col">

            <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-slate-700">
                <h3 id="filePreviewTitle" class="text-lg font-semibold text-gray-800 dark:text-white">📄 Xem trước tài
                    liệu</h3>
                <button onclick="closeFilePreview()"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition">
                    <i class="fa-solid fa-xmark text-2xl"></i>
                </button>
            </div>

            <div class="flex-1 p-4 overflow-auto bg-gray-100 dark:bg-gray-900 min-h-[500px]">
                <div id="filePreviewContent" class="w-full h-full flex items-center justify-center">
                    <div class="text-center text-gray-500 dark:text-gray-400">
                        <div class="text-6xl mb-4 animate-pulse">📄</div>
                        <p>Đang tải tài liệu...</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2 p-4 border-t border-gray-200 dark:border-slate-700">
                <a id="fileDownloadLink" href="#" download
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    ⬇️ Tải xuống
                </a>
                <button onclick="closeFilePreview()"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                    Đóng
                </button>
            </div>

        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- JAVASCRIPT --}}
    {{-- ============================================================ --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const tabs = document.querySelectorAll('.tab-btn');
            const contents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {

                    tabs.forEach(t => {
                        t.classList.remove('active', 'bg-blue-700', 'text-white');
                        t.classList.add('text-gray-600', 'hover:bg-gray-100');
                    });

                    this.classList.add('active', 'bg-blue-700', 'text-white');
                    this.classList.remove('text-gray-600', 'hover:bg-gray-100');

                    contents.forEach(c => c.classList.add('hidden'));

                    const target = document.getElementById(this.dataset.tab);
                    if (target) {
                        target.classList.remove('hidden');
                    }

                });
            });

            const firstTab = document.querySelector('.tab-btn.active');
            if (firstTab) {
                const target = document.getElementById(firstTab.dataset.tab);
                if (target) {
                    target.classList.remove('hidden');
                }
            }

        });

        function openFilePreview(url, title) {
            const modal = document.getElementById('filePreviewModal');
            const content = document.getElementById('filePreviewContent');
            const titleEl = document.getElementById('filePreviewTitle');
            const downloadLink = document.getElementById('fileDownloadLink');

            // Cập nhật tiêu đề
            const isCV = title.toLowerCase().includes('cv');
            titleEl.textContent = '📄 ' + title;
            downloadLink.href = url;

            // Hiển thị loading với animation đẹp hơn
            content.innerHTML = `
        <div class="flex items-center justify-center h-full min-h-[400px]">
            <div class="text-center">
                <div class="relative inline-block">
                    <div class="w-20 h-20 border-4 border-blue-200 dark:border-blue-800 border-t-blue-600 dark:border-t-blue-400 rounded-full animate-spin"></div>
                    <div class="absolute inset-0 flex items-center justify-center text-blue-600 dark:text-blue-400 text-2xl">
                        <i class="fa-regular fa-file-pdf"></i>
                    </div>
                </div>
                <p class="text-gray-500 dark:text-gray-400 mt-4 font-medium">Đang tải tài liệu...</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Vui lòng đợi trong giây lát</p>
            </div>
        </div>
    `;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Hiển thị file với iframe và styling tốt hơn
            setTimeout(() => {
                // Phân loại file để hiển thị phù hợp
                const fileExt = url.split('.').pop().toLowerCase();
                const isPDF = fileExt === 'pdf';
                const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(fileExt);
                const isDoc = ['doc', 'docx'].includes(fileExt);

                let displayContent = '';

                if (isImage) {
                    // Hiển thị ảnh to hơn
                    displayContent = `
                <div class="flex items-center justify-center h-full bg-white dark:bg-gray-900 rounded-lg p-4">
                    <img src="${url}" alt="Preview" class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-lg">
                </div>
            `;
                } else {
                    // Hiển thị PDF hoặc các file khác bằng iframe
                    displayContent = `
                <div class="w-full h-full bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-inner">
                    <div class="h-full min-h-[600px]">
                        <iframe 
                            src="${url}" 
                            class="w-full h-full min-h-[600px] border-0"
                            style="min-height: 600px; width: 100%;"
                            onload="this.style.opacity='1'"
                            onerror="handleIframeError(this)"
                        ></iframe>
                    </div>
                </div>
            `;
                }

                content.innerHTML = displayContent;
            }, 600);
        }

        // Cập nhật hàm handleIframeError để hiển thị đẹp hơn
        function handleIframeError(iframe) {
            const content = document.getElementById('filePreviewContent');
            const url = iframe.src;
            const fileExt = url.split('.').pop().toLowerCase();
            const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(fileExt);
            const isPDF = fileExt === 'pdf';

            // Nếu là ảnh, thử hiển thị trực tiếp
            if (isImage) {
                content.innerHTML = `
            <div class="flex items-center justify-center h-full bg-white dark:bg-gray-900 rounded-lg p-4">
                <img src="${url}" alt="Preview" class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-lg">
            </div>
        `;
                return;
            }

            // Hiển thị thông báo lỗi đẹp
            content.innerHTML = `
        <div class="flex flex-col items-center justify-center h-full min-h-[400px] text-center bg-white dark:bg-gray-900 rounded-lg p-8">
            <div class="w-24 h-24 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center mb-4">
                <i class="fa-regular fa-file-pdf text-4xl text-yellow-600 dark:text-yellow-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Không thể hiển thị trực tiếp</h3>
            <p class="text-gray-500 dark:text-gray-400 max-w-md">
                ${isPDF ? 'PDF này không thể hiển thị trực tiếp trong trình duyệt.' : 'File này không hỗ trợ xem trực tiếp.'}
                Vui lòng tải xuống để xem.
            </p>
            <div class="flex flex-wrap gap-3 mt-6">
                <a href="${url}" download 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shadow-sm hover:shadow-md font-medium">
                    <i class="fa-solid fa-download"></i>
                    Tải xuống ngay
                </a>
                <button onclick="closeFilePreview()" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg transition font-medium">
                    <i class="fa-regular fa-xmark"></i>
                    Đóng
                </button>
            </div>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-4">
                <i class="fa-regular fa-circle-info mr-1"></i>
                Tên file: ${url.split('/').pop()}
            </p>
        </div>
    `;
        }

        function closeFilePreview() {
            const modal = document.getElementById('filePreviewModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('click', function(event) {
            const modal = document.getElementById('filePreviewModal');
            if (event.target === modal) {
                closeFilePreview();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeFilePreview();
            }
        });
    </script>

    <style>
        .tab-btn {
            transition: all 0.3s ease;
        }

        .tab-btn.active {
            background-color: #1d4ed8;
            color: white;
        }

        .tab-btn:not(.active):hover {
            background-color: #f3f4f6;
        }

        .dark .tab-btn:not(.active):hover {
            background-color: #1f2937;
        }

        .tab-content {
            transition: opacity 0.3s ease;
            animation: fadeIn 0.3s ease;
        }

        .tab-content.hidden {
            display: none;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

@endsection
