@extends('layouts.admin')

@section('title', 'Phiếu lương nhân viên')

@php
    $nv = $luong->nguoiDung;
    $hoTen = trim(($nv->ho_so->ho ?? '') . ' ' . ($nv->ho_so->ten ?? '')) ?: $nv->ten_dang_nhap;
    $ngayHuongLuong = (float) $luong->so_ngay_cong + (float) $luong->ngay_nghi_phep;
    $heSoTC = \App\Services\TinhLuongService::HE_SO_TANG_CA;
    // hiển thị số gọn (bỏ .00 thừa)
    $fmtNgay = fn($v) => rtrim(rtrim(number_format((float)$v, 2), '0'), '.');
@endphp

@section('content')
<div class="min-h-screen p-6 bg-gray-50 dark:bg-slate-900">
<div class="max-w-6xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-wrap justify-between items-start gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Phiếu lương — {{ $hoTen }}</h1>
            <p class="text-gray-500 dark:text-slate-400 mt-1">
                {{ $nv->chuc_vu->ten ?? '' }} {{ $nv->phong_ban->ten ?? '' ? '• ' . $nv->phong_ban->ten : '' }}
                — Kỳ lương tháng {{ $luong->luong_thang }}/{{ $luong->luong_nam }}
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.bang-luong.phieu-luong-pdf', [$bangLuong->id, $luong->id]) }}"
               class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow-sm">
                <i class="fa-solid fa-file-pdf mr-1"></i> Xuất PDF
            </a>
            <a href="{{ route('admin.bang-luong.show', $bangLuong->id) }}"
               class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-300 rounded-lg hover:opacity-80">
                <i class="fa-solid fa-arrow-left mr-1"></i> Quay lại bảng lương
            </a>
        </div>
    </div>

    {{-- NET SALARY BANNER --}}

<div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-8 py-5 flex justify-between items-center shadow-sm">

    <div>
        <p class="text-gray-500 dark:text-slate-400 text-sm">
            Lương thực nhận tháng {{ $luong->luong_thang }}/{{ $luong->luong_nam }}
        </p>

        <p class="text-2xl font-bold font-extrabold text-gray-900 dark:text-white mt-2">
            {{ number_format($luong->luong_thuc_nhan) }} đ
        </p>
    </div>

    <div class="text-right space-y-2">
        <p class="text-gray-500 dark:text-slate-300 text-lg">
            Tổng lương:
            <span class="font-bold text-gray-900 dark:text-white">
                {{ number_format($luong->tong_luong) }} đ
            </span>
        </p>

        <p class="text-gray-500 dark:text-slate-300 text-lg">
            Tổng khấu trừ:
            <span class="font-bold text-red-600 dark:text-red-400">
                -{{ number_format($luong->tong_khau_tru) }} đ
            </span>
        </p>
    </div>

</div>

    {{-- BƯỚC 1-2: NGÀY CÔNG & LƯƠNG CƠ BẢN --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400"><i class="fa-solid fa-calendar-check text-blue-500 mr-1"></i> Ngày công</p>
            <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $fmtNgay($luong->so_ngay_cong) }} / {{ (int)$luong->so_ngay_cong_chuan }}</p>
            <p class="text-xs text-gray-400 mt-1">Nghỉ phép: {{ $fmtNgay($luong->ngay_nghi_phep) }} • Nghỉ KP: {{ $fmtNgay($luong->ngay_nghi_khong_phep) }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400"><i class="fa-solid fa-sack-dollar text-green-500 mr-1"></i> Lương cơ bản (HĐ)</p>
            <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($luong->luong_co_ban) }} đ</p>
            <p class="text-xs text-gray-400 mt-1">Đơn giá ngày: {{ number_format($luong->luong_mot_ngay) }} đ • giờ: {{ number_format($luong->luong_mot_gio) }} đ</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400"><i class="fa-solid fa-business-time text-indigo-500 mr-1"></i> Tăng ca</p>
            <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $fmtNgay($luong->gio_tang_ca) }} giờ</p>
            <p class="text-xs text-gray-400 mt-1">Hệ số x{{ $heSoTC }}</p>
        </div>
    </div>

    {{-- BẢNG CÔNG THỨC CHI TIẾT --}}
    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-700">
            <h2 class="font-semibold text-gray-900 dark:text-white"><i class="fa-solid fa-calculator mr-2 text-blue-500"></i>Diễn giải công thức tính lương</h2>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-slate-700">

            {{-- B3: Lương theo công --}}
            <div class="px-5 py-4">
                <div class="flex justify-between items-start gap-4">
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">① Lương theo ngày công</p>
                        <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">
                            = (Lương cơ bản ÷ Ngày công chuẩn) × (Ngày công + Ngày phép)
                        </p>
                        <p class="text-xs text-gray-400 mt-1 font-mono">
                            = ({{ number_format($luong->luong_co_ban) }} ÷ {{ (int)$luong->so_ngay_cong_chuan }}) × {{ $fmtNgay($ngayHuongLuong) }}
                            = {{ number_format($luong->luong_mot_ngay) }} × {{ $fmtNgay($ngayHuongLuong) }}
                        </p>
                    </div>
                    <p class="text-lg font-bold text-gray-900 dark:text-white whitespace-nowrap">{{ number_format($luong->luong_theo_cong) }} đ</p>
                </div>
            </div>

            {{-- B4: Phụ cấp --}}
            <div class="px-5 py-4">
                <div class="flex justify-between items-start gap-4">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900 dark:text-white">② Phụ cấp</p>
                        @if($luong->phuCapLuongs->isEmpty())
                            <p class="text-sm text-gray-400 mt-1">Không có phụ cấp</p>
                        @else
                            <ul class="mt-2 space-y-1">
                                @foreach($luong->phuCapLuongs as $pc)
                                <li class="text-sm text-gray-600 dark:text-slate-300 flex justify-between max-w-md">
                                    <span>• {{ $pc->phuCap->ten ?? $pc->ghi_chu ?? 'Phụ cấp' }}</span>
                                    <span class="font-medium">{{ number_format($pc->so_tien) }} đ</span>
                                </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <p class="text-lg font-bold text-green-600 dark:text-green-400 whitespace-nowrap">+{{ number_format($luong->tong_phu_cap) }} đ</p>
                </div>
            </div>

            {{-- B5: Tăng ca --}}
            <div class="px-5 py-4">
                <div class="flex justify-between items-start gap-4">
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">③ Tiền tăng ca</p>
                        <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">
                            = Số giờ TC × Đơn giá giờ × Hệ số
                        </p>
                        <p class="text-xs text-gray-400 mt-1 font-mono">
                            = {{ $fmtNgay($luong->gio_tang_ca) }} × {{ number_format($luong->luong_mot_gio) }} × {{ $heSoTC }}
                        </p>
                    </div>
                    <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">+{{ number_format($luong->tien_tang_ca) }} đ</p>
                </div>
            </div>

            {{-- B6: Tổng lương --}}
            <div class="px-5 py-4 bg-gray-50 dark:bg-slate-900/50">
                <div class="flex justify-between items-center gap-4">
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-white">④ TỔNG LƯƠNG (gross)</p>
                        <p class="text-xs text-gray-400 mt-1 font-mono">= ① + ② + ③</p>
                    </div>
                    <p class="text-xl font-bold text-gray-900 dark:text-white whitespace-nowrap">{{ number_format($luong->tong_luong) }} đ</p>
                </div>
            </div>

            {{-- B7: Khấu trừ --}}
            <div class="px-5 py-4">
                <div class="flex justify-between items-start gap-4">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900 dark:text-white">⑤ Các khoản khấu trừ</p>
                        @if($luong->khauTruLuongs->isEmpty())
                            <p class="text-sm text-gray-400 mt-1">Không có khấu trừ</p>
                        @else
                            <ul class="mt-2 space-y-1">
                                @foreach($luong->khauTruLuongs as $kt)
                                <li class="text-sm text-gray-600 dark:text-slate-300 flex justify-between max-w-md">
                                    <span>• {{ $kt->ten_loai }}</span>
                                    <span class="font-medium text-red-500">-{{ number_format($kt->so_tien) }} đ</span>
                                </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <p class="text-lg font-bold text-red-500 whitespace-nowrap">-{{ number_format($luong->tong_khau_tru) }} đ</p>
                </div>
            </div>

            {{-- B8: Thực nhận --}}
            <div class="px-5 py-5 bg-blue-50 dark:bg-blue-950/30">
                <div class="flex justify-between items-center gap-4">
                    <div>
                        <p class="font-bold text-gray-900 dark:text-white">⑥ LƯƠNG THỰC NHẬN (net)</p>
                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-1 font-mono">= ④ Tổng lương − ⑤ Khấu trừ</p>
                    </div>
                    <p class="text-2xl font-extrabold text-blue-600 dark:text-sky-400 whitespace-nowrap">{{ number_format($luong->luong_thuc_nhan) }} đ</p>
                </div>
            </div>

        </div>
    </div>

    <p class="text-xs text-gray-400">
        * BHXH 8% + BHYT 1.5% + BHTN 1% tính trên lương cơ bản. Thuế TNCN tính lũy tiến với giảm trừ gia cảnh
        {{ number_format(\App\Services\TinhLuongService::GIAM_TRU_BAN_THAN) }} đ/tháng.
        Trạng thái bảng lương: <span class="font-medium">{{ $bangLuong->trang_thai_text }}</span>.
    </p>

</div>
</div>
@endsection
