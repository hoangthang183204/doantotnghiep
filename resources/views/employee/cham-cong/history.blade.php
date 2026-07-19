{{-- resources/views/employee/cham-cong/history.blade.php --}}
@extends('layouts.admin')

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
                            <option value="{{ $i }}" {{ request('thang', $thangLoc ?? date('m')) == $i ? 'selected' : '' }}>
                                Tháng {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <select name="nam" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm">
                        <option value="">Chọn năm</option>
                        @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                            <option value="{{ $i }}" {{ request('nam', $namLoc ?? date('Y')) == $i ? 'selected' : '' }}>
                                Năm {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
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

        <!-- ===== LỊCH THÁNG (THU NHỎ) ===== -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900 dark:text-white text-sm">
                    <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                    Lịch chấm công tháng {{ $thangLoc }}/{{ $namLoc }}
                </h3>
                <div class="flex items-center gap-2 text-[10px]">
                    <span class="flex items-center gap-0.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span> Đã chấm
                    </span>
                    <span class="flex items-center gap-0.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span> Chưa
                    </span>
                    <span class="flex items-center gap-0.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span> Muộn
                    </span>
                    <span class="flex items-center gap-0.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span> Sớm
                    </span>
                </div>
            </div>
            <div class="p-3">
                @php
                    $ngayDauThang = \Carbon\Carbon::create($namLoc, $thangLoc, 1);
                    $soNgayTrongThang = $ngayDauThang->daysInMonth;
                    $thuBatDau = $ngayDauThang->dayOfWeek;
                    $thuTrongTuan = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
                    
                    $chamCongTrongThang = \App\Models\ChamCong::where('nguoi_dung_id', auth()->id())
                        ->whereMonth('ngay_cham_cong', $thangLoc)
                        ->whereYear('ngay_cham_cong', $namLoc)
                        ->where('loai_cham_cong', 'check_in')
                        ->get()
                        ->keyBy(function ($item) {
                            return $item->ngay_cham_cong->format('d');
                        });
                @endphp

                <div class="grid grid-cols-7 gap-1 max-w-md mx-auto">
                    <!-- Tiêu đề các thứ -->
                    @foreach($thuTrongTuan as $thu)
                        <div class="text-center text-[10px] font-semibold text-gray-500 dark:text-gray-400 py-1">
                            {{ $thu }}
                        </div>
                    @endforeach

                    <!-- Các ngày trong tháng -->
                    @for ($i = 0; $i < $soNgayTrongThang + $thuBatDau; $i++)
                        @php
                            $ngay = $i - $thuBatDau + 1;
                            $isValid = $ngay >= 1 && $ngay <= $soNgayTrongThang;
                            $ngayHienTai = $isValid ? \Carbon\Carbon::create($namLoc, $thangLoc, $ngay) : null;
                            $isToday = $ngayHienTai && $ngayHienTai->isToday();
                            $daChamCong = $isValid && isset($chamCongTrongThang[$ngay]);
                            $trangThai = $daChamCong ? $chamCongTrongThang[$ngay]->trang_thai : null;
                            $soCong = $daChamCong ? $chamCongTrongThang[$ngay]->so_cong : 0;
                            
                            // Xác định màu sắc
                            $bgColor = 'bg-gray-100 dark:bg-gray-700';
                            $textColor = 'text-gray-400 dark:text-gray-500';
                            $borderColor = 'border-gray-200 dark:border-gray-600';
                            
                            if ($isValid && $daChamCong) {
                                if ($trangThai == 'di_muon') {
                                    $bgColor = 'bg-yellow-100 dark:bg-yellow-900/30';
                                    $textColor = 'text-yellow-700 dark:text-yellow-300';
                                    $borderColor = 'border-yellow-300 dark:border-yellow-700';
                                } elseif ($trangThai == 've_som') {
                                    $bgColor = 'bg-orange-100 dark:bg-orange-900/30';
                                    $textColor = 'text-orange-700 dark:text-orange-300';
                                    $borderColor = 'border-orange-300 dark:border-orange-700';
                                } elseif ($trangThai == 'den_som') {
                                    $bgColor = 'bg-blue-100 dark:bg-blue-900/30';
                                    $textColor = 'text-blue-700 dark:text-blue-300';
                                    $borderColor = 'border-blue-300 dark:border-blue-700';
                                } elseif ($trangThai == 'tang_ca') {
                                    $bgColor = 'bg-purple-100 dark:bg-purple-900/30';
                                    $textColor = 'text-purple-700 dark:text-purple-300';
                                    $borderColor = 'border-purple-300 dark:border-purple-700';
                                } else {
                                    $bgColor = 'bg-green-100 dark:bg-green-900/30';
                                    $textColor = 'text-green-700 dark:text-green-300';
                                    $borderColor = 'border-green-300 dark:border-green-700';
                                }
                            } elseif ($isValid && !$daChamCong) {
                                $bgColor = 'bg-red-50 dark:bg-red-900/10';
                                $textColor = 'text-red-400 dark:text-red-500';
                                $borderColor = 'border-red-200 dark:border-red-800';
                            }
                            
                            if ($isToday) {
                                $borderColor = 'border-blue-500 ring-1 ring-blue-500 ring-opacity-50';
                            }
                        @endphp

                        @if ($i < $thuBatDau)
                            <div class="aspect-square rounded border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50"></div>
                        @elseif ($isValid)
                            <div class="aspect-square rounded border {{ $borderColor }} {{ $bgColor }} flex items-center justify-center relative transition-all hover:shadow-md cursor-default group">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="text-xs font-medium {{ $textColor }} {{ $isToday ? 'font-bold' : '' }}">
                                        {{ $ngay }}
                                    </span>
                                    @if ($daChamCong && $soCong > 0)
                                        <span class="text-[8px] {{ $textColor }} opacity-75 leading-none mt-0.5">
                                            {{ number_format($soCong, 1) }}
                                        </span>
                                    @endif
                                </div>
                                @if ($daChamCong)
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 dark:bg-gray-900 text-white text-[9px] rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10 pointer-events-none">
                                        @if ($trangThai == 'dung_gio') ✅ Đúng giờ
                                        @elseif ($trangThai == 'di_muon') ⚠️ Đi muộn
                                        @elseif ($trangThai == 've_som') 🔻 Về sớm
                                        @elseif ($trangThai == 'den_som') 📈 Đến sớm
                                        @elseif ($trangThai == 'tang_ca') 🕐 Tăng ca
                                        @else {{ $trangThai }}
                                        @endif
                                        - {{ number_format($soCong, 2) }} công
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endfor
                </div>
            </div>
        </div>

        <!-- Thống kê -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl border p-4 shadow-sm">
                <p class="text-sm text-gray-500 dark:text-gray-400">Tổng ngày</p>
                <p class="text-2xl font-bold">{{ $thongKe['tong_ngay'] ?? 0 }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-green-200 p-4 shadow-sm">
                <p class="text-sm text-green-600">Đúng giờ</p>
                <p class="text-2xl font-bold text-green-600">{{ $thongKe['dung_gio'] ?? 0 }}</p>
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
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tăng ca</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lý do</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @forelse($lichSu as $index => $item)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-4 py-3 text-gray-500">{{ $lichSu->firstItem() + $index }}</td>
                                <td class="px-4 py-3 font-medium">{{ $item->ngay_cham_cong_format }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded text-xs {{ $item->caLamViec && $item->caLamViec->ten == 'Sáng' ? 'bg-yellow-100 text-yellow-700' : 'bg-indigo-100 text-indigo-700' }}">
                                        {{ $item->ten_ca }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ $item->gio_vao_format }}</td>
                                <td class="px-4 py-3">{{ $item->gio_ra_format }}</td>
                                <td class="px-4 py-3 text-center font-medium text-blue-600">{{ number_format($item->so_cong ?? 0, 2) }}</td>
                                <td class="px-4 py-3 text-center font-medium text-purple-600">{{ number_format($item->gio_tang_ca ?? 0, 1) }}h</td>
                                <td class="px-4 py-3">
                                    @include('employee.cham-cong.partials.status-badge', ['status' => $item->trang_thai])
                                </td>
                                <td class="px-4 py-3 text-gray-500 max-w-[150px] truncate">
                                    {{ $item->ly_do_ve_som ?? ($item->ghi_chu ?? '--') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-12 text-center text-gray-500">
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