{{-- resources/views/employee/tang-ca/index.blade.php --}}
@extends('layouts.employee')

@section('title', 'Tăng ca')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-clock mr-3 text-blue-600 dark:text-blue-400"></i>
                    Tăng ca
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Quản lý kiến nghị và đơn tăng ca của bạn</p>
            </div>
            <a href="{{ route('employee.tang-ca.create') }}"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2 whitespace-nowrap">
                <i class="fas fa-plus-circle"></i>
                Gửi kiến nghị
            </a>
        </div>

        {{-- ⭐ THÔNG BÁO TĂNG CA HÔM NAY --}}
        @php
            $today = Carbon\Carbon::now('Asia/Ho_Chi_Minh');
            $overtimeToday = \App\Models\DangKyTangCa::where('nguoi_dung_id', auth()->id())
                ->whereDate('ngay_tang_ca', $today)
                ->where('trang_thai', 'da_duyet')
                ->where('da_hoan_thanh', false)
                ->first();
                
            $canCheckout = false;
            $checkoutMessage = '';
            $showOvertimeAlert = false;
            $thucHien = null;
            $daCheckout = false;
            $canXinVeSom = false;
            $xinVeSom = null;
            $daDenGioTangCa = false;
            $trangThaiHienThi = '';
            $mauTrangThai = '';
            
            if ($overtimeToday) {
                $showOvertimeAlert = true;
                $thucHien = $overtimeToday->thuc_hien;
                $daCheckout = $thucHien && $thucHien->thoi_gian_ket_thuc;
                
                $checkoutStatus = $overtimeToday->getCheckoutStatus();
                $trangThaiHienThi = $checkoutStatus['label'] ?? '';
                $mauTrangThai = '';
                switch ($checkoutStatus['color'] ?? 'gray') {
                    case 'green': $mauTrangThai = 'text-green-600 dark:text-green-400'; break;
                    case 'yellow': $mauTrangThai = 'text-yellow-600 dark:text-yellow-400'; break;
                    case 'orange': $mauTrangThai = 'text-orange-600 dark:text-orange-400'; break;
                    case 'red': $mauTrangThai = 'text-red-600 dark:text-red-400'; break;
                    case 'purple': $mauTrangThai = 'text-purple-600 dark:text-purple-400'; break;
                    case 'blue': $mauTrangThai = 'text-blue-600 dark:text-blue-400'; break;
                    default: $mauTrangThai = 'text-gray-600 dark:text-gray-400';
                }
                
                if (!$daCheckout && !$overtimeToday->thieu_cham_cong_ra) {
                    $canCheckoutResult = \App\Models\DangKyTangCa::canCheckout($overtimeToday->id);
                    if ($canCheckoutResult['valid']) {
                        $canCheckout = true;
                        if ($canCheckoutResult['is_early'] ?? false) {
                            $checkoutMessage = "Sớm " . ($canCheckoutResult['early_minutes'] ?? 0) . " phút";
                        }
                    }
                }
                
                if (!$daCheckout && !$overtimeToday->thieu_cham_cong_ra) {
                    $now = Carbon\Carbon::now('Asia/Ho_Chi_Minh');
                    $ngayTangCa = Carbon\Carbon::parse($overtimeToday->ngay_tang_ca)->startOfDay();
                    $gioBatDau = Carbon\Carbon::parse($overtimeToday->gio_bat_dau);
                    $gioKetThuc = Carbon\Carbon::parse($overtimeToday->gio_ket_thuc);
                    $thoiGianBatDau = Carbon\Carbon::parse($ngayTangCa->format('Y-m-d') . ' ' . $gioBatDau->format('H:i:s'));
                    $thoiGianKetThuc = Carbon\Carbon::parse($ngayTangCa->format('Y-m-d') . ' ' . $gioKetThuc->format('H:i:s'));
                    
                    $sau30pBatDau = $now->gte($thoiGianBatDau->copy()->addMinutes(30));
                    $truoc30pKetThuc = $now->lte($thoiGianKetThuc->copy()->subMinutes(30));
                    
                    if ($sau30pBatDau && $truoc30pKetThuc) {
                        $xinVeSom = $overtimeToday->xin_ve_som;
                        if (!$xinVeSom || $xinVeSom->trang_thai == 'tu_choi' || $xinVeSom->trang_thai == 'huy') {
                            $canXinVeSom = true;
                        }
                    }
                }
            }
        @endphp

        @if($showOvertimeAlert)
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
            <div class="flex flex-col sm:flex-row items-start gap-3">
                <i class="fas fa-clock text-blue-500 text-xl mt-0.5 flex-shrink-0"></i>
                <div class="flex-1 w-full min-w-0">
                    <p class="text-sm font-medium text-blue-700 dark:text-blue-300">
                        📋 Đơn tăng ca hôm nay: {{ $today->format('d/m/Y') }}
                    </p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mt-2 text-sm">
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Giờ bắt đầu</span>
                            <p class="font-semibold text-blue-600 dark:text-blue-400">{{ Carbon\Carbon::parse($overtimeToday->gio_bat_dau)->format('H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Giờ kết thúc</span>
                            <p class="font-semibold text-blue-600 dark:text-blue-400">{{ Carbon\Carbon::parse($overtimeToday->gio_ket_thuc)->format('H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Số giờ</span>
                            <p class="font-semibold text-blue-600 dark:text-blue-400">{{ number_format($overtimeToday->so_gio_tang_ca, 2, ',', '') }} giờ</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Trạng thái</span>
                            <p class="font-semibold {{ $mauTrangThai }}">{{ $trangThaiHienThi }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-3 flex flex-wrap gap-2">
                        @if($overtimeToday->thieu_cham_cong_ra)
                            <a href="{{ route('employee.tang-ca.sua-chua-cong', $overtimeToday->id) }}"
                                class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition text-sm font-medium whitespace-nowrap">
                                <i class="fas fa-edit mr-1"></i> Yêu cầu sửa chữa công
                            </a>
                            <span class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm whitespace-nowrap">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Thiếu chấm công ra
                            </span>
                        @elseif($canCheckout)
                            <form action="{{ route('employee.tang-ca.confirm-thuc-hien', $overtimeToday->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition text-sm font-medium whitespace-nowrap"
                                    onclick="return confirm('Xác nhận check-out tăng ca?')">
                                    <i class="fas fa-sign-out-alt mr-1"></i> Check-out
                                    @if($checkoutMessage)
                                        <span class="text-xs opacity-80">({{ $checkoutMessage }})</span>
                                    @endif
                                </button>
                            </form>
                            <span class="text-xs text-gray-500 dark:text-gray-400 self-center whitespace-nowrap">
                                ⏰ Check-out trong 10 phút cuối
                            </span>
                        @elseif($daCheckout)
                            <span class="inline-flex items-center px-3 py-1.5 bg-purple-100 text-purple-700 rounded-lg text-sm whitespace-nowrap">
                                <i class="fas fa-check-circle mr-1"></i> Đã hoàn thành
                            </span>
                        @endif

                        @if($canXinVeSom)
                            <a href="{{ route('employee.tang-ca.xin-ve-som', $overtimeToday->id) }}"
                                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition text-sm font-medium whitespace-nowrap">
                                <i class="fas fa-clock mr-1"></i> Xin về sớm
                            </a>
                        @endif

                        @if($xinVeSom && !$daCheckout)
                            <span class="inline-flex items-center px-3 py-1.5 
                                @if($xinVeSom->trang_thai == 'cho_duyet') bg-yellow-100 text-yellow-700
                                @elseif($xinVeSom->trang_thai == 'da_duyet') bg-green-100 text-green-700
                                @elseif($xinVeSom->trang_thai == 'tu_choi') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-700 @endif
                                rounded-lg text-sm whitespace-nowrap">
                                <i class="fas 
                                    @if($xinVeSom->trang_thai == 'cho_duyet') fa-clock
                                    @elseif($xinVeSom->trang_thai == 'da_duyet') fa-check-circle
                                    @elseif($xinVeSom->trang_thai == 'tu_choi') fa-times-circle
                                    @else fa-info-circle @endif
                                    mr-1"></i>
                                {{ \App\Models\XinVeSomTangCa::$trangThaiLabels[$xinVeSom->trang_thai] ?? $xinVeSom->trang_thai }}
                                @if($xinVeSom->trang_thai == 'da_duyet')
                                    (về lúc {{ $xinVeSom->gio_ve_som_du_kien }})
                                @endif
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- THÔNG BÁO --}}
        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    <p class="text-green-700 dark:text-green-300">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 text-xl"></i>
                    <p class="text-red-700 dark:text-red-300">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- THỐNG KÊ --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-3 sm:p-4 text-center border border-gray-100 dark:border-gray-700">
                <p class="text-xl sm:text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $thongKe['tong'] ?? 0 }}</p>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Tổng yêu cầu</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-3 sm:p-4 text-center border border-gray-100 dark:border-gray-700">
                <p class="text-xl sm:text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $thongKe['cho_duyet'] ?? 0 }}</p>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">⏳ Chờ duyệt</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-3 sm:p-4 text-center border border-gray-100 dark:border-gray-700">
                <p class="text-xl sm:text-2xl font-bold text-green-600 dark:text-green-400">{{ $thongKe['da_duyet'] ?? 0 }}</p>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">✅ Đã duyệt</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-3 sm:p-4 text-center border border-gray-100 dark:border-gray-700">
                <p class="text-xl sm:text-2xl font-bold text-purple-600 dark:text-purple-400">
                    {{ $donTangCa->filter(function ($item) {
                            return $item->trang_thai == 'da_duyet' &&
                                $item->thuc_hien &&
                                $item->thuc_hien->trang_thai == 'quan_ly_xac_nhan';
                        })->count() }}
                </p>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">✅ Hoàn thành</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-3 sm:p-4 text-center border border-gray-100 dark:border-gray-700 col-span-2 sm:col-span-1">
                <p class="text-xl sm:text-2xl font-bold text-red-600 dark:text-red-400">{{ $thongKe['tu_choi'] ?? 0 }}</p>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">❌ Từ chối</p>
            </div>
        </div>

        {{-- DANH SÁCH --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                <h3 class="font-semibold text-gray-900 dark:text-white">📋 Danh sách</h3>
                <span class="text-sm text-gray-500">Tổng: {{ $donTangCa->total() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px]">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">Loại</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">Ngày</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">Giờ</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">Số giờ</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">Loại</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">Trạng thái</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-center text-[10px] sm:text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($donTangCa as $don)
                            @php
                                $isKienNghi = is_null($don->ngay_tang_ca);
                                $thucHien = $don->thuc_hien;
                                $daXacNhan = $thucHien && $thucHien->trang_thai === 'quan_ly_xac_nhan';
                                $daCheckout = $thucHien && $thucHien->thoi_gian_ket_thuc;
                                $daDenGioTangCaItem = false;
                                $canXinVeSomItem = false;
                                $xinVeSomItem = null;
                                $trangThaiPhu = '';
                                $mauTrangThaiPhu = '';
                                $thoiGianConLaiText = '';
                                $thongBaoThoiGian = '';
                                
                                // Chỉ lấy trạng thái phụ nếu đã duyệt và chưa hoàn thành
                                if ($don->trang_thai == 'da_duyet' && !$don->da_hoan_thanh && !$isKienNghi) {
                                    $checkoutStatus = $don->getCheckoutStatus();
                                    $trangThaiPhu = $checkoutStatus['label'] ?? '';
                                    switch ($checkoutStatus['color'] ?? 'gray') {
                                        case 'green': $mauTrangThaiPhu = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'; break;
                                        case 'yellow': $mauTrangThaiPhu = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300'; break;
                                        case 'orange': $mauTrangThaiPhu = 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300'; break;
                                        case 'red': $mauTrangThaiPhu = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300'; break;
                                        case 'purple': $mauTrangThaiPhu = 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300'; break;
                                        case 'blue': $mauTrangThaiPhu = 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300'; break;
                                        default: $mauTrangThaiPhu = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                    }
                                }
                                
                                $loaiLabels = [
                                    'ngay_thuong' => 'Ngày thường',
                                    'ngay_nghi' => 'Ngày cuối tuần',
                                    'le_tet' => 'Lễ, Tết',
                                ];
                                $badgeClasses = [
                                    'cho_duyet' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                    'da_duyet' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                    'tu_choi' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                    'huy' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                ];
                                $trangThaiLabels = [
                                    'cho_duyet' => '⏳ Chờ duyệt',
                                    'da_duyet' => '✅ Đã duyệt',
                                    'tu_choi' => '❌ Từ chối',
                                    'huy' => '🗑️ Đã hủy',
                                ];
                                
                                $coTheCheckout = false;
                                $checkoutMessage = '';
                                
                                if (!$isKienNghi && $don->trang_thai == 'da_duyet' && !$don->da_hoan_thanh) {
                                    if ($don->thieu_cham_cong_ra) {
                                        $thongBaoThoiGian = '⚠️ Thiếu chấm công ra';
                                    } elseif (!$daCheckout) {
                                        $now = Carbon\Carbon::now('Asia/Ho_Chi_Minh');
                                        $ngayTangCa = Carbon\Carbon::parse($don->ngay_tang_ca)->startOfDay();
                                        $gioBatDau = Carbon\Carbon::parse($don->gio_bat_dau);
                                        $gioKetThuc = Carbon\Carbon::parse($don->gio_ket_thuc);
                                        $thoiGianBatDau = Carbon\Carbon::parse(
                                            $ngayTangCa->format('Y-m-d') . ' ' . $gioBatDau->format('H:i:s')
                                        );
                                        $thoiGianKetThuc = Carbon\Carbon::parse(
                                            $ngayTangCa->format('Y-m-d') . ' ' . $gioKetThuc->format('H:i:s')
                                        );
                                        
                                        if ($now->lt($thoiGianBatDau)) {
                                            $diffInMinutes = $now->diffInMinutes($thoiGianBatDau);
                                            $diffInMinutes = (int) ceil($diffInMinutes);
                                            
                                            if ($diffInMinutes < 60) {
                                                $thoiGianConLaiText = '⏳ ' . $diffInMinutes . ' phút nữa';
                                            } else {
                                                $hours = floor($diffInMinutes / 60);
                                                $minutes = $diffInMinutes % 60;
                                                if ($minutes > 0) {
                                                    $thoiGianConLaiText = '⏳ Còn ' . $hours . 'h' . $minutes . 'p';
                                                } else {
                                                    $thoiGianConLaiText = '⏳ Còn ' . $hours . ' giờ';
                                                }
                                            }
                                        }
                                        
                                        $daDenGioTangCaItem = $now->gte($thoiGianBatDau->copy()->subMinutes(30));
                                        
                                        if ($daDenGioTangCaItem) {
                                            $canCheckoutResult = \App\Models\DangKyTangCa::canCheckout($don->id);
                                            if ($canCheckoutResult['valid']) {
                                                $coTheCheckout = true;
                                                if ($canCheckoutResult['is_early'] ?? false) {
                                                    $earlyMinutes = (int) ($canCheckoutResult['early_minutes'] ?? 0);
                                                    $checkoutMessage = "Sớm " . $earlyMinutes . " phút";
                                                }
                                            } else {
                                                $thongBaoThoiGian = $canCheckoutResult['message'];
                                            }
                                        } else {
                                            $thongBaoThoiGian = $thoiGianConLaiText ?: '⏳ Chưa đến giờ tăng ca';
                                        }
                                    } else {
                                        $thongBaoThoiGian = '⏳ Chờ xác nhận';
                                    }
                                }
                                
                                if (!$isKienNghi && $don->trang_thai == 'da_duyet' && !$don->da_hoan_thanh && !$don->thieu_cham_cong_ra && !$daCheckout) {
                                    $now = Carbon\Carbon::now('Asia/Ho_Chi_Minh');
                                    $ngayTangCa = Carbon\Carbon::parse($don->ngay_tang_ca)->startOfDay();
                                    $gioBatDau = Carbon\Carbon::parse($don->gio_bat_dau);
                                    $gioKetThuc = Carbon\Carbon::parse($don->gio_ket_thuc);
                                    $thoiGianBatDau = Carbon\Carbon::parse($ngayTangCa->format('Y-m-d') . ' ' . $gioBatDau->format('H:i:s'));
                                    $thoiGianKetThuc = Carbon\Carbon::parse($ngayTangCa->format('Y-m-d') . ' ' . $gioKetThuc->format('H:i:s'));
                                    
                                    $sau30pBatDau = $now->gte($thoiGianBatDau->copy()->addMinutes(30));
                                    $truoc30pKetThuc = $now->lte($thoiGianKetThuc->copy()->subMinutes(30));
                                    
                                    if ($sau30pBatDau && $truoc30pKetThuc) {
                                        $xinVeSomItem = $don->xin_ve_som;
                                        if (!$xinVeSomItem || $xinVeSomItem->trang_thai == 'tu_choi' || $xinVeSomItem->trang_thai == 'huy') {
                                            $canXinVeSomItem = true;
                                        }
                                    }
                                }
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors" id="row-{{ $don->id }}">
                                <td class="px-2 sm:px-4 py-2 sm:py-3">
                                    @if($isKienNghi)
                                        <span class="px-2 py-1 rounded-full text-[10px] sm:text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 whitespace-nowrap">📝 Kiến nghị</span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-[10px] sm:text-xs font-medium bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300 whitespace-nowrap">📄 Đơn tăng ca</span>
                                    @endif
                                </td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900 dark:text-white whitespace-nowrap">
                                    @if($isKienNghi)
                                        {{ Carbon\Carbon::parse($don->created_at)->format('d/m/Y') }}
                                    @else
                                        {{ Carbon\Carbon::parse($don->ngay_tang_ca)->format('d/m/Y') }}
                                    @endif
                                </td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                    @if($isKienNghi)
                                        <span class="text-gray-400">---</span>
                                    @else
                                        {{ Carbon\Carbon::parse($don->gio_bat_dau)->format('H:i') }} - {{ Carbon\Carbon::parse($don->gio_ket_thuc)->format('H:i') }}
                                    @endif
                                </td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm font-medium {{ $isKienNghi ? 'text-gray-400' : 'text-blue-600 dark:text-blue-400' }} whitespace-nowrap">
                                    {{ number_format($don->so_gio_tang_ca ?? 0, 2, ',', '') }} giờ
                                </td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                    {{ $loaiLabels[$don->loai_tang_ca] ?? $don->loai_tang_ca }}
                                </td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3">
                                    <div class="flex flex-wrap items-center gap-1">
                                        <span class="px-2 py-1 rounded-full text-[10px] sm:text-xs font-medium {{ $badgeClasses[$don->trang_thai] ?? 'bg-gray-100 text-gray-800' }} whitespace-nowrap">
                                            {{ $trangThaiLabels[$don->trang_thai] ?? $don->trang_thai }}
                                        </span>
                                        {{-- Chỉ hiển thị trạng thái phụ nếu đã duyệt và chưa hoàn thành --}}
                                        @if ($don->trang_thai == 'da_duyet' && !$don->da_hoan_thanh && !$isKienNghi)
                                            @if ($don->thieu_cham_cong_ra)
                                                <span class="px-2 py-1 rounded-full text-[10px] sm:text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 whitespace-nowrap">⚠️ Thiếu chấm công ra</span>
                                            @elseif($trangThaiPhu)
                                                <span class="px-2 py-1 rounded-full text-[10px] sm:text-xs font-medium {{ $mauTrangThaiPhu }} whitespace-nowrap">{{ $trangThaiPhu }}</span>
                                            @endif
                                        @endif
                                        @if($xinVeSomItem && !$don->da_hoan_thanh && !$isKienNghi)
                                            <span class="px-2 py-1 rounded-full text-[10px] sm:text-xs font-medium 
                                                @if($xinVeSomItem->trang_thai == 'cho_duyet') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                                @elseif($xinVeSomItem->trang_thai == 'da_duyet') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                                @elseif($xinVeSomItem->trang_thai == 'tu_choi') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif
                                                whitespace-nowrap">
                                                {{ \App\Models\XinVeSomTangCa::$trangThaiLabels[$xinVeSomItem->trang_thai] ?? $xinVeSomItem->trang_thai }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3">
                                    <div class="flex items-center justify-center gap-1 flex-nowrap">
                                        <a href="{{ route('employee.tang-ca.show', $don->id) }}"
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 rounded-lg transition flex-shrink-0"
                                            title="Xem chi tiết">
                                            <i class="fas fa-eye text-xs sm:text-sm"></i>
                                        </a>

                                        @if (!$isKienNghi && $don->trang_thai == 'da_duyet' && $don->thieu_cham_cong_ra && !$don->da_hoan_thanh)
                                            <a href="{{ route('employee.tang-ca.sua-chua-cong', $don->id) }}"
                                                class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-1.5 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition text-[10px] sm:text-sm font-medium whitespace-nowrap flex-shrink-0">
                                                <i class="fas fa-edit mr-1"></i> Sửa công
                                            </a>
                                        @endif

                                        @if (!$isKienNghi && $don->trang_thai == 'da_duyet' && !$don->da_hoan_thanh && !$don->thieu_cham_cong_ra)
                                            @if ($coTheCheckout)
                                                <form action="{{ route('employee.tang-ca.confirm-thuc-hien', $don->id) }}" method="POST" class="inline flex-shrink-0">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition text-[10px] sm:text-sm font-medium whitespace-nowrap"
                                                        onclick="return confirm('Check-out tăng ca?')">
                                                        <i class="fas fa-sign-out-alt mr-1"></i> Check-out
                                                        @if($checkoutMessage)
                                                            <span class="text-[8px] sm:text-xs opacity-80">({{ $checkoutMessage }})</span>
                                                        @endif
                                                    </button>
                                                </form>
                                            @else
                                                <span class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-1.5 bg-gray-200 text-gray-500 rounded-lg text-[10px] sm:text-sm cursor-not-allowed whitespace-nowrap flex-shrink-0"
                                                      title="{{ $thongBaoThoiGian ?: 'Chưa đến giờ check-out' }}">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ $thongBaoThoiGian ?: 'Chờ check-out' }}
                                                </span>
                                            @endif
                                        @endif

                                        @if($canXinVeSomItem && !$don->da_hoan_thanh && !$isKienNghi && !$don->thieu_cham_cong_ra)
                                            <a href="{{ route('employee.tang-ca.xin-ve-som', $don->id) }}"
                                                class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-1.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition text-[10px] sm:text-sm font-medium whitespace-nowrap flex-shrink-0">
                                                <i class="fas fa-clock mr-1"></i> Xin về sớm
                                            </a>
                                        @endif

                                        @if ($isKienNghi && $don->trang_thai == 'cho_duyet')
                                            <a href="{{ route('employee.tang-ca.edit', $don->id) }}"
                                                class="inline-flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 bg-yellow-50 text-yellow-600 hover:bg-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-400 dark:hover:bg-yellow-900/50 rounded-lg transition flex-shrink-0">
                                                <i class="fas fa-edit text-xs sm:text-sm"></i>
                                            </a>
                                            <form action="{{ route('employee.tang-ca.huy', $don->id) }}" method="POST"
                                                onsubmit="return confirm('Hủy kiến nghị này?')" class="inline flex-shrink-0">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 rounded-lg transition flex-shrink-0">
                                                    <i class="fas fa-times text-xs sm:text-sm"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if (!$isKienNghi && $don->loai_tao == 'truong_phong' && $don->trang_thai == 'da_duyet' && !$don->da_hoan_thanh && !$don->thieu_cham_cong_ra)
                                            <button onclick="showTuChoiModalIndex({{ $don->id }})"
                                                class="inline-flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 rounded-lg transition flex-shrink-0"
                                                title="Từ chối đơn tăng ca">
                                                <i class="fas fa-times text-xs sm:text-sm"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-inbox text-2xl block mb-2 text-gray-300 dark:text-gray-600"></i>
                                    Chưa có dữ liệu tăng ca
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($donTangCa->hasPages())
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $donTangCa->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- ⭐ MODAL TỪ CHỐI ĐƠN TĂNG CA --}}
    <div id="tuChoiModalIndex" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md p-6 mx-4 animate-scale-up">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-times-circle text-red-500 text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Từ chối đơn tăng ca</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nhập lý do từ chối</p>
                </div>
            </div>
            
            <form action="" method="POST" id="tuChoiFormIndex">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Lý do từ chối <span class="text-red-500">*</span>
                    </label>
                    <textarea name="ly_do_tu_choi" id="lyDoTuChoiIndex" rows="4"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none transition"
                        placeholder="Nhập lý do từ chối..." required></textarea>
                    <div class="flex justify-between mt-2">
                        <span class="text-xs text-gray-400">Tối thiểu 10 ký tự</span>
                        <span id="lyDoTuChoiCountIndex" class="text-xs text-gray-400">0/500</span>
                    </div>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closeTuChoiModalIndex()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">Hủy</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-check"></i> Xác nhận từ chối
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .animate-scale-up {
            animation: scaleUp 0.25s ease-out;
        }
        @keyframes scaleUp {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
    </style>
@endsection

@push('scripts')
<script>
    let currentDonId = null;

    function showTuChoiModalIndex(id) {
        currentDonId = id;
        const modal = document.getElementById('tuChoiModalIndex');
        const form = document.getElementById('tuChoiFormIndex');
        form.action = `/employee/tang-ca/${id}/tu-choi-don`;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.getElementById('lyDoTuChoiIndex').value = '';
        document.getElementById('lyDoTuChoiCountIndex').textContent = '0/500';
    }

    function closeTuChoiModalIndex() {
        document.getElementById('tuChoiModalIndex').classList.add('hidden');
        document.getElementById('tuChoiModalIndex').classList.remove('flex');
        currentDonId = null;
    }

    document.getElementById('lyDoTuChoiIndex').addEventListener('input', function() {
        document.getElementById('lyDoTuChoiCountIndex').textContent = this.value.length + '/500';
    });

    document.getElementById('tuChoiFormIndex').addEventListener('submit', function(e) {
        const lyDo = document.getElementById('lyDoTuChoiIndex').value.trim();
        if (lyDo.length < 10) {
            e.preventDefault();
            alert('⚠️ Lý do từ chối phải có ít nhất 10 ký tự!');
            return false;
        }
        if (!confirm('Bạn có chắc muốn từ chối đơn tăng ca này?')) {
            e.preventDefault();
            return false;
        }
    });

    document.getElementById('tuChoiModalIndex').addEventListener('click', function(e) {
        if (e.target === this) closeTuChoiModalIndex();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeTuChoiModalIndex();
    });
</script>
@endpush