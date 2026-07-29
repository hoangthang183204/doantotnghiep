{{-- resources/views/truong-phong/bao-cao/attendance.blade.php --}}

@extends('layouts.admin')

@section('title', 'Báo cáo chấm công')

@php
    use Carbon\Carbon;
@endphp

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                        <i class="fas fa-chart-bar mr-3 text-blue-600"></i>
                        Báo cáo chấm công
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">
                        Phòng <span class="font-medium text-blue-600">{{ $phongBan->ten_phong_ban }}</span>
                        - Tháng {{ $thang }}/{{ $nam }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="window.print()"
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2 shadow-sm">
                        <i class="fas fa-print"></i>
                        In
                    </button>
                    <a href="{{ route('truong-phong.bao-cao.export') }}?thang={{ $thang }}&nam={{ $nam }}"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition flex items-center gap-2 shadow-sm">
                        <i class="fas fa-file-excel"></i>
                        Xuất Excel
                    </a>
                </div>
            </div>
        </div>

        {{-- BỘ LỌC --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700">
            <form method="GET" action="{{ route('truong-phong.bao-cao.attendance') }}"
                class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        <i class="fas fa-user mr-1"></i> Nhân viên
                    </label>
                    <select name="nhan_vien_id"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Tất cả</option>
                        @foreach ($nhanViens as $nv)
                            <option value="{{ $nv->id }}" {{ request('nhan_vien_id') == $nv->id ? 'selected' : '' }}>
                                {{ ($nv->hoSo->ho ?? '') . ' ' . ($nv->hoSo->ten ?? $nv->ten_dang_nhap) }}
                                ({{ $nv->hoSo->ma_nhan_vien ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        <i class="fas fa-calendar-alt mr-1"></i> Tháng
                    </label>
                    <select name="thang"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $thang == $i ? 'selected' : '' }}>Tháng
                                {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        <i class="fas fa-calendar-alt mr-1"></i> Năm
                    </label>
                    <select name="nam"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        @for ($i = date('Y') - 3; $i <= date('Y'); $i++)
                            <option value="{{ $i }}" {{ $nam == $i ? 'selected' : '' }}>Năm
                                {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="flex items-end gap-3 md:col-span-2">
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-search"></i> Xem báo cáo
                    </button>
                    <a href="{{ route('truong-phong.bao-cao.attendance') }}"
                        class="px-5 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-redo"></i> Làm mới
                    </a>
                </div>
            </form>
        </div>

        {{-- THỐNG KÊ --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-users mr-1"></i> Tổng nhân viên
                        </p>
                        <h3 class="text-3xl font-bold text-blue-600 mt-2">{{ $tongNhanVien }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-green-200 dark:border-green-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-600 dark:text-green-400">
                            <i class="fas fa-check-circle mr-1"></i> Tổng ngày công
                        </p>
                        <h3 class="text-3xl font-bold text-green-600 mt-2">{{ $tongNgayChamCong }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-double text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-yellow-200 dark:border-yellow-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-yellow-600 dark:text-yellow-400">
                            <i class="fas fa-clock mr-1"></i> Đi muộn
                        </p>
                        <h3 class="text-3xl font-bold text-yellow-600 mt-2">{{ $tongNgayDiMuon }}</h3>
                    </div>
                    <div
                        class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                        <i class="fas fa-hourglass-start text-yellow-600 dark:text-yellow-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-orange-200 dark:border-orange-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-orange-600 dark:text-orange-400">
                            <i class="fas fa-clock mr-1"></i> Về sớm
                        </p>
                        <h3 class="text-3xl font-bold text-orange-600 mt-2">{{ $tongNgayVeSom }}</h3>
                    </div>
                    <div
                        class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center">
                        <i class="fas fa-hourglass-end text-orange-600 dark:text-orange-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- ⭐ LỊCH THÁNG THU NHỎ (GIỐNG EMPLOYEE) ⭐ --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900 dark:text-white text-sm">
                    <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                    Lịch chấm công tháng {{ $thang }}/{{ $nam }}
                </h3>
                <div class="flex items-center gap-2 text-[10px]">
                    <span class="flex items-center gap-0.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-400"></span> Full công
                    </span>
                    <span class="flex items-center gap-0.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-teal-400"></span> Nửa công
                    </span>
                    <span class="flex items-center gap-0.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-300"></span> Chưa
                    </span>
                    <span class="flex items-center gap-0.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-400"></span> Muộn
                    </span>
                    <span class="flex items-center gap-0.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-orange-400"></span> Sớm
                    </span>
                </div>
            </div>
            <div class="p-3">
                <div class="grid grid-cols-7 gap-1 max-w-md mx-auto">
                    <!-- Tiêu đề các thứ -->
                    @foreach ($thuTrongTuan as $thu)
                        <div class="text-center text-[10px] font-semibold text-gray-500 dark:text-gray-400 py-1">
                            {{ $thu }}
                        </div>
                    @endforeach

                    <!-- Các ngày trong tháng -->
                    @for ($i = 0; $i < $soNgayTrongThang + $thuBatDau; $i++)
                        @php
                            $ngay = $i - $thuBatDau + 1;
                            $isValid = $ngay >= 1 && $ngay <= $soNgayTrongThang;
                            $ngayHienTai = $isValid ? Carbon::create($nam, $thang, $ngay) : null;
                            $isToday = $ngayHienTai && $ngayHienTai->isToday();

                            // Kiểm tra xem có nhân viên nào chấm công ngày này không
                            $daCoChamCong = false;
                            $soCongTrungBinh = 0;
                            $trangThai = null;
                            $dem = 0;

                            if ($isValid) {
                                foreach ($nhanViens as $nv) {
                                    $chamCongNgay = $chamCongTrongThang[$nv->id] ?? null;
                                    if ($chamCongNgay && isset($chamCongNgay[$ngay])) {
                                        $daCoChamCong = true;
                                        $dem++;
                                        $soCongTrungBinh += floatval($chamCongNgay[$ngay]->so_cong ?? 0);
                                        if ($trangThai === null) {
                                            $trangThai = $chamCongNgay[$ngay]->trang_thai;
                                        }
                                    }
                                }
                                if ($dem > 0) {
                                    $soCongTrungBinh = round($soCongTrungBinh / $dem, 2);
                                }
                            }

                            // Xác định màu sắc
                            $bgColor = 'bg-gray-100 dark:bg-gray-700';
                            $textColor = 'text-gray-500 dark:text-gray-400';
                            $borderColor = 'border-gray-200 dark:border-gray-600';

                            if ($isValid && $daCoChamCong) {
                                if ($soCongTrungBinh >= 1) {
                                    if ($trangThai == 'di_muon') {
                                        $bgColor = 'bg-yellow-100 dark:bg-yellow-900/20';
                                        $textColor = 'text-yellow-700 dark:text-yellow-300';
                                        $borderColor = 'border-yellow-200 dark:border-yellow-700';
                                    } elseif ($trangThai == 've_som') {
                                        $bgColor = 'bg-orange-100 dark:bg-orange-900/20';
                                        $textColor = 'text-orange-700 dark:text-orange-300';
                                        $borderColor = 'border-orange-200 dark:border-orange-700';
                                    } elseif ($trangThai == 'den_som') {
                                        $bgColor = 'bg-blue-100 dark:bg-blue-900/20';
                                        $textColor = 'text-blue-700 dark:text-blue-300';
                                        $borderColor = 'border-blue-200 dark:border-blue-700';
                                    } elseif ($trangThai == 'tang_ca') {
                                        $bgColor = 'bg-purple-100 dark:bg-purple-900/20';
                                        $textColor = 'text-purple-700 dark:text-purple-300';
                                        $borderColor = 'border-purple-200 dark:border-purple-700';
                                    } else {
                                        $bgColor = 'bg-green-100 dark:bg-green-900/20';
                                        $textColor = 'text-green-700 dark:text-green-300';
                                        $borderColor = 'border-green-200 dark:border-green-700';
                                    }
                                } elseif ($soCongTrungBinh >= 0.5) {
                                    if ($trangThai == 'di_muon') {
                                        $bgColor = 'bg-yellow-100 dark:bg-yellow-900/20';
                                        $textColor = 'text-yellow-700 dark:text-yellow-300';
                                        $borderColor = 'border-yellow-200 dark:border-yellow-700';
                                    } elseif ($trangThai == 've_som') {
                                        $bgColor = 'bg-orange-100 dark:bg-orange-900/20';
                                        $textColor = 'text-orange-700 dark:text-orange-300';
                                        $borderColor = 'border-orange-200 dark:border-orange-700';
                                    } else {
                                        $bgColor = 'bg-teal-100 dark:bg-teal-900/20';
                                        $textColor = 'text-teal-700 dark:text-teal-300';
                                        $borderColor = 'border-teal-200 dark:border-teal-700';
                                    }
                                } elseif ($soCongTrungBinh > 0) {
                                    $bgColor = 'bg-pink-100 dark:bg-pink-900/20';
                                    $textColor = 'text-pink-700 dark:text-pink-300';
                                    $borderColor = 'border-pink-200 dark:border-pink-700';
                                } else {
                                    $bgColor = 'bg-red-100 dark:bg-red-900/20';
                                    $textColor = 'text-red-700 dark:text-red-300';
                                    $borderColor = 'border-red-200 dark:border-red-700';
                                }
                            } elseif ($isValid && !$daCoChamCong) {
                                $bgColor = 'bg-gray-50 dark:bg-gray-800';
                                $textColor = 'text-gray-400 dark:text-gray-500';
                                $borderColor = 'border-gray-100 dark:border-gray-700';
                            }

                            if ($isToday) {
                                $borderColor = 'border-blue-400 ring-2 ring-blue-400 ring-opacity-40';
                            }
                        @endphp

                        @if ($i < $thuBatDau)
                            <div
                                class="aspect-square rounded border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                            </div>
                        @elseif ($isValid)
                            <div
                                class="aspect-square rounded border {{ $borderColor }} {{ $bgColor }} flex items-center justify-center relative transition-all hover:shadow-md cursor-default group">
                                <div class="flex flex-col items-center justify-center">
                                    <span
                                        class="text-xs font-medium {{ $textColor }} {{ $isToday ? 'font-bold' : '' }}">
                                        {{ $ngay }}
                                    </span>
                                    @if ($daCoChamCong && $soCongTrungBinh > 0)
                                        <span
                                            class="text-[8px] {{ $textColor }} opacity-80 leading-none mt-0.5 font-medium">
                                            {{ number_format($soCongTrungBinh, 1) }}
                                        </span>
                                    @endif
                                </div>
                                @if ($daCoChamCong)
                                    <div
                                        class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-1 bg-gray-800 dark:bg-gray-900 text-white text-[9px] rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10 pointer-events-none shadow-lg">
                                        {{ $dem }} NV - TB {{ number_format($soCongTrungBinh, 2) }} công
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endfor
                </div>
            </div>
        </div>

        {{-- ⭐ BẢNG CHI TIẾT NHÂN VIÊN ⭐ --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800 dark:text-white">
                    <i class="fas fa-table mr-2 text-blue-600"></i>
                    Chi tiết chấm công nhân viên
                </h3>
                <span class="text-sm bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full text-gray-600 dark:text-gray-300">
                    {{ count($nhanViens ?? []) }} nhân viên
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                STT</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Mã NV</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Họ tên</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Ngày công</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Đi muộn</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Về sớm</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tổng giờ</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Số công</th>
                            
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($nhanViens ?? [] as $index => $nv)
                            @php
                                $chiTiet = $chiTietNhanViens[$nv->id] ?? [
                                    'id' => $nv->id,
                                    'ma_nhan_vien' => 'N/A',
                                    'ho_ten' => 'N/A',
                                    'so_ngay_cham_cong' => 0,
                                    'so_ngay_di_muon' => 0,
                                    'so_ngay_ve_som' => 0,
                                    'tong_gio_lam' => 0,
                                ];
                                $soCong = ($chiTiet['tong_gio_lam'] ?? 0) / 8;
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $chiTiet['ma_nhan_vien'] ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $chiTiet['ho_ten'] ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-green-600 dark:text-green-400 text-center">
                                    {{ $chiTiet['so_ngay_cham_cong'] ?? 0 }}
                                </td>
                                <td
                                    class="px-4 py-3 text-sm font-semibold text-yellow-600 dark:text-yellow-400 text-center">
                                    {{ $chiTiet['so_ngay_di_muon'] ?? 0 }}
                                </td>
                                <td
                                    class="px-4 py-3 text-sm font-semibold text-orange-600 dark:text-orange-400 text-center">
                                    {{ $chiTiet['so_ngay_ve_som'] ?? 0 }}
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-blue-600 dark:text-blue-400 text-center">
                                    {{ $chiTiet['tong_gio_lam'] ?? 0 }}h
                                </td>
                                <td
                                    class="px-4 py-3 text-sm font-semibold text-purple-600 dark:text-purple-400 text-center">
                                    {{ number_format($soCong, 2) }}
                                </td>
                                
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-12 text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-inbox text-4xl block mb-3 text-gray-300 dark:text-gray-600"></i>
                                    <p class="font-medium">Không có dữ liệu chấm công</p>
                                    <p class="text-sm">Chưa có dữ liệu chấm công trong tháng này</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Footer tổng hợp --}}
            <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700">
                <div class="flex flex-wrap items-center gap-4 text-sm">
                    <span class="text-gray-600 dark:text-gray-400">
                        <i class="fas fa-user mr-1"></i> <strong
                            class="text-gray-900 dark:text-white">{{ count($nhanViens ?? []) }}</strong> nhân viên
                    </span>
                    <span class="text-gray-600 dark:text-gray-400">
                        <i class="fas fa-check-circle text-green-500 mr-1"></i> <strong
                            class="text-green-600 dark:text-green-400">{{ $tongNgayChamCong }}</strong> công
                    </span>
                    <span class="text-gray-600 dark:text-gray-400">
                        <i class="fas fa-clock text-yellow-500 mr-1"></i> <strong
                            class="text-yellow-600">{{ $tongNgayDiMuon }}</strong> muộn
                    </span>
                    <span class="text-gray-600 dark:text-gray-400">
                        <i class="fas fa-clock text-orange-500 mr-1"></i> <strong
                            class="text-orange-600">{{ $tongNgayVeSom }}</strong> sớm
                    </span>
                    <span class="text-gray-600 dark:text-gray-400 ml-auto">
                        Tỷ lệ chấm công: <strong class="text-blue-600">{{ $tyLeChamCong }}%</strong>
                    </span>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('styles')
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            .bg-white {
                background: white !important;
            }

            .border {
                border-color: #ddd !important;
            }

            .shadow-sm {
                box-shadow: none !important;
            }
        }
    </style>
@endpush
