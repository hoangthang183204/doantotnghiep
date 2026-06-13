@extends('layouts.admin')

@section('title', 'Chi tiết hồ sơ nhân viên')

@section('content')

    <div class="space-y-6">

        {{-- HEADER & BACK BUTTON --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

            <div class="flex items-center justify-between mb-6">

                <div>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                        Chi tiết hồ sơ nhân viên
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Thông tin chi tiết của nhân sự trong hệ thống
                    </p>
                </div>

                <a href="{{ route('admin.ho-so.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                    ← Quay lại
                </a>

            </div>

            @php
                $nguoiDung = $hoSo->nguoi_dung;
                $trangThai = $hoSo->trang_thai ?? 1;
            @endphp

            <div class="flex flex-col md:flex-row gap-8">

                {{-- AVATAR --}}
                <div class="md:w-1/3 lg:w-1/4 flex flex-col items-center">

                    @if ($hoSo->anh_dai_dien)
                        <img src="{{ asset('storage/' . $hoSo->anh_dai_dien) }}" alt="Ảnh đại diện"
                            class="w-40 h-40 rounded-full object-cover shadow border-4 border-white dark:border-gray-700">
                    @else
                        <div
                            class="w-40 h-40 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-400 text-6xl shadow">
                            👤
                        </div>
                    @endif

                    <div class="mt-4 text-center">
                        <div class="font-semibold text-lg text-gray-800 dark:text-white">
                            {{ $hoSo->ho }} {{ $hoSo->ten }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $hoSo->ma_nhan_vien }}
                        </div>

                        {{-- STATUS BADGE --}}
                        <div class="mt-2">
                            @if ($trangThai === 0)
                                <span class="text-xs px-3 py-1 bg-red-100 text-red-600 rounded-full">
                                    ⛔ Đã nghỉ việc
                                </span>
                            @else
                                <span class="text-xs px-3 py-1 bg-green-100 text-green-600 rounded-full">
                                    ✅ Đang làm việc
                                </span>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- INFO --}}
                <div class="md:w-2/3 lg:w-3/4">

                    {{-- THÔNG TIN CƠ BẢN --}}
                    <div class="mb-6">
                        <h3
                            class="text-md font-semibold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
                            📋 Thông tin cơ bản
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Email công ty</div>
                                <div class="font-medium text-gray-800 dark:text-white">
                                    {{ $nguoiDung->email ?? '---' }}
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Số điện thoại</div>
                                <div class="font-medium text-gray-800 dark:text-white">
                                    {{ $hoSo->so_dien_thoai ?? '---' }}
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Ngày sinh</div>
                                <div class="font-medium text-gray-800 dark:text-white">
                                    {{ \Carbon\Carbon::parse($hoSo->ngay_sinh)->format('d/m/Y') ?? '---' }}
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Giới tính</div>
                                <div class="font-medium text-gray-800 dark:text-white">
                                    @if ($hoSo->gioi_tinh == 'nam')
                                        Nam
                                    @elseif($hoSo->gioi_tinh == 'nu')
                                        Nữ
                                    @else
                                        Khác
                                    @endif
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Chức vụ</div>
                                <div class="font-medium text-gray-800 dark:text-white">
                                    {{ $nguoiDung->chuc_vu->ten ?? '---' }}
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Phòng ban</div>
                                <div class="font-medium text-gray-800 dark:text-white">
                                    {{ $nguoiDung->phong_ban->ten_phong_ban ?? '---' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ĐỊA CHỈ --}}
                    <div class="mb-6">
                        <h3
                            class="text-md font-semibold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
                            🏠 Địa chỉ
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Địa chỉ hiện tại</div>
                                <div class="font-medium text-gray-800 dark:text-white">
                                    {{ $hoSo->dia_chi_hien_tai ?? '---' }}
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Địa chỉ thường trú</div>
                                <div class="font-medium text-gray-800 dark:text-white">
                                    {{ $hoSo->dia_chi_thuong_tru ?? '---' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- GIẤY TỜ TÙY THÂN --}}
                    <div class="mb-6">
                        <h3
                            class="text-md font-semibold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
                            🪪 Giấy tờ tùy thân
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 dark:text-gray-400">CMND/CCCD</div>
                                <div class="font-medium text-gray-800 dark:text-white">
                                    {{ $hoSo->cmnd_cccd ?? '---' }}
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Số hộ chiếu</div>
                                <div class="font-medium text-gray-800 dark:text-white">
                                    {{ $hoSo->so_ho_chieu ?? '---' }}
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Tình trạng hôn nhân</div>
                                <div class="font-medium text-gray-800 dark:text-white">
                                    @php
                                        $tinhTrang = [
                                            'doc_than' => 'Độc thân',
                                            'da_ket_hon' => 'Đã kết hôn',
                                            'ly_hon' => 'Ly hôn',
                                            'goa' => 'Góa',
                                        ];
                                    @endphp
                                    {{ $tinhTrang[$hoSo->tinh_trang_hon_nhan] ?? '---' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- LIÊN HỆ KHẨN CẤP --}}
                    <div class="mb-6">
                        <h3
                            class="text-md font-semibold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
                            📞 Liên hệ khẩn cấp
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Họ tên</div>
                                <div class="font-medium text-gray-800 dark:text-white">
                                    {{ $hoSo->lien_he_khan_cap ?? '---' }}
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Số điện thoại</div>
                                <div class="font-medium text-gray-800 dark:text-white">
                                    {{ $hoSo->sdt_khan_cap ?? '---' }}
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Mối quan hệ</div>
                                <div class="font-medium text-gray-800 dark:text-white">
                                    {{ $hoSo->quan_he_khan_cap ?? '---' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ẢNH CCCD --}}
                    @if ($hoSo->anh_cccd_truoc || $hoSo->anh_cccd_sau)
                        <div>
                            <h3
                                class="text-md font-semibold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
                                🖼️ Ảnh CCCD
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if ($hoSo->anh_cccd_truoc)
                                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg text-center">
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Mặt trước</div>
                                        <img src="{{ asset('storage/' . $hoSo->anh_cccd_truoc) }}" alt="CCCD mặt trước"
                                            class="max-w-full h-auto rounded-lg shadow">
                                    </div>
                                @endif

                                @if ($hoSo->anh_cccd_sau)
                                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg text-center">
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Mặt sau</div>
                                        <img src="{{ asset('storage/' . $hoSo->anh_cccd_sau) }}" alt="CCCD mặt sau"
                                            class="max-w-full h-auto rounded-lg shadow">
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if (isset($cv) && $cv)
                        <div class="mt-6">
                            <h3
                                class="text-md font-semibold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
                                📄 Hồ sơ CV
                            </h3>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">

                                <div class="mb-3">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        Tên tài liệu
                                    </div>

                                    <div class="font-medium text-gray-800 dark:text-white">
                                        {{ $cv->tieu_de }}
                                    </div>
                                </div>

                                <div class="flex gap-3">

                                    <a href="{{ Storage::url($cv->duong_dan_file) }}" target="_blank"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                        👁 Xem CV
                                    </a>

                                    <a href="{{ Storage::url($cv->duong_dan_file) }}" download
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                                        ⬇ Tải xuống
                                    </a>

                                </div>

                            </div>
                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>

@endsection
