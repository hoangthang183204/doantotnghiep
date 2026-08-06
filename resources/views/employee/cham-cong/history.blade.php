@extends('layouts.employee')

@section('title', 'Lịch sử chấm công')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-history mr-3 text-blue-600"></i>
                    Lịch sử chấm công
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Xem lịch sử chấm công theo ca sáng/chiều
                </p>
            </div>
            <a href="{{ route('employee.cham-cong.index') }}"
                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition">
                <i class="fas fa-arrow-left mr-1"></i> Quay lại
            </a>
        </div>

        <!-- Bộ lọc -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
            <form method="GET" class="flex flex-wrap items-center gap-3">
                <div>
                    <select name="thang" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm">
                        <option value="">Chọn tháng</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}"
                                {{ request('thang', $thangLoc ?? date('m')) == $i ? 'selected' : '' }}>
                                Tháng {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <select name="nam" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm">
                        <option value="">Chọn năm</option>
                        @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                            <option value="{{ $i }}"
                                {{ request('nam', $namLoc ?? date('Y')) == $i ? 'selected' : '' }}>
                                Năm {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                    <i class="fas fa-search mr-1"></i> Lọc
                </button>
                <a href="{{ route('employee.cham-cong.history') }}"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-redo mr-1"></i> Reset
                </a>
                <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">
                    Tháng {{ $thangLoc }}/{{ $namLoc }}
                </span>
            </form>
        </div>

        <!-- ===== LỊCH THÁNG ===== -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900 dark:text-white text-sm">
                    <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                    Lịch chấm công tháng {{ $thangLoc }}/{{ $namLoc }}
                </h3>
                <div class="flex items-center gap-2 text-[10px]">
                    <span class="flex items-center gap-0.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span> Full
                    </span>
                    <span class="flex items-center gap-0.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span> Nửa
                    </span>
                    <span class="flex items-center gap-0.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span> Muộn
                    </span>
                    <span class="flex items-center gap-0.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span> Sớm
                    </span>
                    <span class="flex items-center gap-0.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-300 dark:bg-gray-600"></span> Chưa
                    </span>
                </div>
            </div>
            <div class="p-3">
                @php
                    $ngayDauThang = \Carbon\Carbon::create($namLoc, $thangLoc, 1, 0, 0, 0, 'Asia/Ho_Chi_Minh');
                    $soNgayTrongThang = $ngayDauThang->daysInMonth;
                    $thuBatDau = $ngayDauThang->dayOfWeek;
                    $thuTrongTuan = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];

                    $chamCongTrongThang = [];
                    if (!empty($allRecords)) {
                        foreach ($allRecords as $record) {
                            $ngay = \Carbon\Carbon::parse($record->ngay_cham_cong)
                                ->setTimezone('Asia/Ho_Chi_Minh')
                                ->format('j'); 
                            $chamCongTrongThang[(string) $ngay] = $record;
                        }
                    }

                    $today = \Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format('j');
                    $todayMonth = \Carbon\Carbon::now('Asia/Ho_Chi_Minh')->month;
                    $todayYear = \Carbon\Carbon::now('Asia/Ho_Chi_Minh')->year;
                @endphp

                <div class="grid grid-cols-7 gap-1.5 max-w-[400px] mx-auto">
                    @foreach ($thuTrongTuan as $thu)
                        <div class="text-center text-[11px] font-semibold text-gray-500 dark:text-gray-400 py-2 border-b dark:border-gray-700">
                            {{ $thu }}
                        </div>
                    @endforeach

                    @for ($i = 0; $i < $soNgayTrongThang + $thuBatDau; $i++)
                        @php
                            $ngay = $i - $thuBatDau + 1;
                            $isValid = $ngay >= 1 && $ngay <= $soNgayTrongThang;

                            $ngayHienTai = $isValid
                                ? \Carbon\Carbon::create($namLoc, $thangLoc, $ngay, 0, 0, 0, 'Asia/Ho_Chi_Minh')
                                : null;

                            $isToday =
                                $ngayHienTai &&
                                $ngayHienTai->format('j') == $today &&
                                $ngayHienTai->month == $todayMonth &&
                                $ngayHienTai->year == $todayYear;

                            $daChamCong = $isValid && isset($chamCongTrongThang[(string) $ngay]);

                            $trangThai = null;
                            $soCong = 0;
                            if ($daChamCong) {
                                $chamCongItem = $chamCongTrongThang[(string) $ngay];
                                $trangThai = $chamCongItem->trang_thai;
                                $soCong = floatval($chamCongItem->so_cong ?? 0);
                            }

                            // ========================================
                            // 🎨 XÁC ĐỊNH MÀU SẮC
                            // ========================================
                            $bgColor = 'bg-gray-100 dark:bg-gray-700/50';
                            $textColor = 'text-gray-400 dark:text-gray-500';
                            $borderColor = 'border-gray-200 dark:border-gray-600';

                            if ($isValid && $daChamCong) {
                                // 1. Xác định màu nền dựa trên số Công TRƯỚC
                                if ($soCong >= 1) {
                                    $bgColor = 'bg-green-200 dark:bg-green-900/50';
                                    $textColor = 'text-green-800 dark:text-green-200';
                                } elseif ($soCong > 0) {
                                    $bgColor = 'bg-teal-200 dark:bg-teal-900/50';
                                    $textColor = 'text-teal-800 dark:text-teal-200';
                                } else {
                                    $bgColor = 'bg-red-200 dark:bg-red-900/50';
                                    $textColor = 'text-red-800 dark:text-red-200';
                                }
                                $borderColor = 'border-gray-200 dark:border-gray-600';

                                // 2. Ghi đè (Override) màu nếu có Trạng thái đặc biệt (Muộn, Sớm)
                                if ($trangThai == 'di_muon') {
                                    $bgColor = 'bg-yellow-200 dark:bg-yellow-900/50';
                                    $textColor = 'text-yellow-800 dark:text-yellow-200';
                                    $borderColor = 'border-yellow-500 dark:border-yellow-600';
                                } elseif ($trangThai == 've_som') {
                                    $bgColor = 'bg-orange-200 dark:bg-orange-900/50';
                                    $textColor = 'text-orange-800 dark:text-orange-200';
                                    $borderColor = 'border-orange-500 dark:border-orange-600';
                                } elseif ($trangThai == 'den_som') {
                                    $bgColor = 'bg-blue-200 dark:bg-blue-900/50';
                                    $textColor = 'text-blue-800 dark:text-blue-200';
                                    $borderColor = 'border-blue-500 dark:border-blue-600';
                                }
                            }

                            // VIỀN XANH CHO NGÀY HÔM NAY
                            if ($isToday) {
                                $borderColor = 'border-blue-500 ring-2 ring-blue-400 ring-opacity-60';
                            }
                        @endphp

                        @if ($i < $thuBatDau)
                            <div class="aspect-square rounded bg-gray-50 dark:bg-gray-800/50 border border-transparent">
                            </div>
                        @elseif ($isValid)
                            <div class="aspect-square rounded border {{ $borderColor }} {{ $bgColor }} flex items-center justify-center relative transition-all hover:shadow-md cursor-default group">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <span class="text-xs font-medium {{ $textColor }} {{ $isToday ? 'font-bold' : '' }}">
                                        {{ $ngay }}
                                    </span>
                                    
                                    @if ($daChamCong)
                                        <span class="text-[10px] {{ $textColor }} opacity-80 leading-tight mt-0.5 font-medium">
                                            {{ number_format($soCong, 1) }}
                                        </span>
                                    @else
                                        <span class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight mt-0.5">
                                            --
                                        </span>
                                    @endif
                                </div>

                                {{-- Tooltip hiện chi tiết khi di chuột --}}
                                @if ($daChamCong)
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-1 bg-gray-800 dark:bg-gray-900 text-white text-[9px] rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10 pointer-events-none shadow-lg">
                                        {{ number_format($soCong, 2) }} công
                                        @if ($trangThai)
                                            @php
                                                $textTrangThai = '';
                                                if ($trangThai == 'di_muon') {
                                                    $textTrangThai = 'Đi muộn';
                                                } elseif ($trangThai == 've_som') {
                                                    $textTrangThai = 'Về sớm';
                                                } elseif ($trangThai == 'den_som') {
                                                    $textTrangThai = 'Đến sớm';
                                                } else {
                                                    if ($soCong >= 1) {
                                                        $textTrangThai = 'Đúng giờ';
                                                    } elseif ($soCong > 0) {
                                                        $textTrangThai = 'Nửa công';
                                                    } else {
                                                        $textTrangThai = 'Đã check-in';
                                                    }
                                                }
                                            @endphp
                                            - {{ $textTrangThai }}
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endfor
                </div>
            </div>
        </div>

        <!-- Thống kê -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl border p-4 shadow-sm">
                <p class="text-sm text-gray-500 dark:text-gray-400">Tổng ngày</p>
                <p class="text-2xl font-bold">{{ $thongKe['tong_ngay'] ?? 0 }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-green-200 p-4 shadow-sm">
                <p class="text-sm text-green-600">Full công</p>
                <p class="text-2xl font-bold text-green-600">{{ $thongKe['full_cong'] ?? 0 }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-teal-200 p-4 shadow-sm">
                <p class="text-sm text-teal-600">Nửa công</p>
                <p class="text-2xl font-bold text-teal-600">{{ $thongKe['nua_cong'] ?? 0 }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-yellow-200 p-4 shadow-sm">
                <p class="text-sm text-yellow-600">Đi muộn</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $thongKe['di_muon'] ?? 0 }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-orange-200 p-4 shadow-sm">
                <p class="text-sm text-orange-600">Về sớm</p>
                <p class="text-2xl font-bold text-orange-600">{{ $thongKe['ve_som'] ?? 0 }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-blue-200 p-4 shadow-sm col-span-2">
                <p class="text-sm text-blue-600">Tổng giờ làm</p>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($thongKe['tong_gio_lam'] ?? 0, 1) }}h</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-purple-200 p-4 shadow-sm col-span-2">
                <p class="text-sm text-purple-600">Tổng tăng ca</p>
                <p class="text-2xl font-bold text-purple-600">{{ number_format($thongKe['tong_tang_ca'] ?? 0, 1) }}h</p>
            </div>
        </div>

        <!-- Danh sách chi tiết -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ngày</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ca</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-in</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-out</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Công</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loại</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tăng ca</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lý do</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @forelse($lichSu as $index => $item)
                            @php
                                $soCong = floatval($item->so_cong ?? 0);
                                if ($soCong >= 1) {
                                    $loaiCong = 'Full công';
                                    $loaiColor = 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300';
                                } elseif ($soCong > 0) {
                                    $loaiCong = 'Nửa công';
                                    $loaiColor = 'bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-300';
                                } else {
                                    $loaiCong = '0 công';
                                    $loaiColor = 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300';
                                }
                            @endphp
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-4 py-3 text-gray-500">{{ $lichSu->firstItem() + $index }}</td>
                                <td class="px-4 py-3 font-medium">{{ $item->ngay_cham_cong_format }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="px-2 py-1 rounded text-xs {{ $item->caLamViec && $item->caLamViec->ten == 'Sáng' ? 'bg-yellow-100 text-yellow-700' : 'bg-indigo-100 text-indigo-700' }}">
                                        {{ $item->ten_ca }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ $item->gio_vao_format }}</td>
                                <td class="px-4 py-3">{{ $item->gio_ra_format }}</td>
                                <td
                                    class="px-4 py-3 text-center font-bold {{ $soCong >= 1 ? 'text-green-600' : ($soCong > 0 ? 'text-teal-600' : 'text-red-500') }}">
                                    {{ number_format($soCong, 2) }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-[10px] font-medium {{ $loaiColor }}">
                                        {{ $loaiCong }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center font-medium text-purple-600">
                                    {{ number_format($item->gio_tang_ca ?? 0, 1) }}h</td>
                                <td class="px-4 py-3">
                                    @php
                                        // 🟢 LOGIC MỚI: Cứ có giờ vào là Đúng giờ (kể cả sau khi check-out ra 0 công)
                                        if ($item->gio_vao) {
                                            $mau = 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300';
                                            $icon = 'fas fa-check-circle';
                                            $text = '✅ Đúng giờ';

                                            if ($item->gio_ra) {
                                                // Chỉ đổi thành Muộn hoặc Sớm nếu database ghi hẳn 2 trạng thái này
                                                if ($item->trang_thai == 'di_muon') {
                                                    $mau = 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300';
                                                    $icon = 'fas fa-exclamation-triangle';
                                                    $text = '⏰ Đi muộn';
                                                } elseif ($item->trang_thai == 've_som') {
                                                    $mau = 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300';
                                                    $icon = 'fas fa-exclamation-triangle';
                                                    $text = '🏠 Về sớm';
                                                }
                                            } else {
                                                // Chưa check-out -> Đang làm
                                                $mau = 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400';
                                                $icon = 'fas fa-clock';
                                                $text = '⏳ Đang làm';
                                            }
                                        } else {
                                            // Chưa check-in
                                            $mau = 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400';
                                            $icon = 'fas fa-minus-circle';
                                            $text = '⏸️ Chưa chấm công';
                                            
                                            if ($item->trang_thai == 'nghi_phep') {
                                                $mau = 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300';
                                                $icon = 'fas fa-calendar-check';
                                                $text = '📋 Nghỉ phép';
                                            } elseif ($item->trang_thai == 'vang_mat') {
                                                $mau = 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300';
                                                $icon = 'fas fa-times-circle';
                                                $text = '❌ Vắng mặt';
                                            }
                                        }
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $mau }}">
                                        <i class="{{ $icon }} mr-1"></i> {{ $text }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-500 max-w-[150px] truncate">
                                    {{ $item->ly_do_ve_som ?? ($item->ghi_chu ?? '--') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-12 text-center text-gray-500">
                                    <i class="fas fa-calendar-times text-4xl mb-3 text-gray-300"></i>
                                    <p class="font-medium">Không có dữ liệu chấm công</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($lichSu->hasPages())
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    {{ $lichSu->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection