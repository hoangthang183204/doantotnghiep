{{-- resources/views/employee/tang-ca/show.blade.php --}}
@extends('layouts.employee')

@section('title', 'Chi tiết đơn tăng ca')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto px-4 sm:px-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg shadow-blue-500/20">
                    <i class="fas fa-file-alt text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Chi tiết đơn tăng ca
                    </h1>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Mã đơn:</span>
                        <span class="text-sm font-semibold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-3 py-0.5 rounded-full">
                            #{{ $donTangCa->id }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ route('employee.tang-ca.index') }}" 
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl transition-all duration-200 hover:shadow-md group">
            <i class="fas fa-arrow-left text-sm group-hover:-translate-x-0.5 transition-transform"></i>
            <span>Quay lại</span>
        </a>
    </div>

    {{-- THÔNG BÁO --}}
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border-l-4 border-emerald-500 rounded-xl p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-1.5 bg-emerald-500 rounded-lg">
                    <i class="fas fa-check-circle text-white text-sm"></i>
                </div>
                <p class="text-emerald-700 dark:text-emerald-300 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-xl p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-1.5 bg-red-500 rounded-lg">
                    <i class="fas fa-exclamation-circle text-white text-sm"></i>
                </div>
                <p class="text-red-700 dark:text-red-300 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- MAIN CARD --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
        
        {{-- STATUS BAR --}}
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800/50 dark:to-gray-800">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-4">
                    @php
                        $statusConfig = [
                            'cho_duyet' => ['icon' => 'fa-clock', 'color' => 'yellow', 'label' => '⏳ Chờ duyệt'],
                            'da_duyet' => ['icon' => 'fa-check-circle', 'color' => 'green', 'label' => '✅ Đã duyệt'],
                            'tu_choi' => ['icon' => 'fa-times-circle', 'color' => 'red', 'label' => '❌ Từ chối'],
                            'huy' => ['icon' => 'fa-trash-alt', 'color' => 'gray', 'label' => '🗑️ Đã hủy'],
                        ];
                        $status = $statusConfig[$donTangCa->trang_thai] ?? ['icon' => 'fa-circle', 'color' => 'gray', 'label' => $donTangCa->trang_thai];
                    @endphp
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-sm font-semibold 
                            @if($status['color'] == 'yellow') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300
                            @elseif($status['color'] == 'green') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300
                            @elseif($status['color'] == 'red') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300
                            @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 @endif">
                            <i class="fas {{ $status['icon'] }}"></i>
                            {{ $status['label'] }}
                        </span>
                        @if($donTangCa->trang_thai == 'da_duyet' && $donTangCa->da_hoan_thanh)
                            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-sm font-semibold bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300">
                                <i class="fas fa-check-double"></i>
                                Hoàn thành
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <i class="far fa-calendar-alt"></i>
                    <span>Ngày tạo: <strong class="text-gray-700 dark:text-gray-300">{{ $donTangCa->created_at ? Carbon\Carbon::parse($donTangCa->created_at)->format('d/m/Y H:i') : '---' }}</strong></span>
                </div>
            </div>
        </div>

        {{-- BODY --}}
        <div class="p-6 space-y-8">

            {{-- ⭐ THÔNG TIN CƠ BẢN --}}
            <div>
                <h3 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-500"></i>
                    Thông tin đơn
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                        <p class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1">
                            <i class="far fa-calendar text-blue-400"></i>
                            Ngày
                        </p>
                        <p class="mt-1.5 font-semibold text-gray-900 dark:text-white">
                            {{ $donTangCa->ngay_tang_ca ? Carbon\Carbon::parse($donTangCa->ngay_tang_ca)->format('d/m/Y') : '---' }}
                        </p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                        <p class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1">
                            <i class="far fa-clock text-blue-400"></i>
                            Giờ
                        </p>
                        <p class="mt-1.5 font-semibold text-gray-900 dark:text-white">
                            {{ $donTangCa->gio_bat_dau ? Carbon\Carbon::parse($donTangCa->gio_bat_dau)->format('H:i') : '---' }} - 
                            {{ $donTangCa->gio_ket_thuc ? Carbon\Carbon::parse($donTangCa->gio_ket_thuc)->format('H:i') : '---' }}
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 dark:from-blue-900/20 dark:to-blue-900/10 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                        <p class="text-xs text-blue-600 dark:text-blue-400 flex items-center gap-1">
                            <i class="fas fa-hourglass-half"></i>
                            Số giờ
                        </p>
                        <p class="mt-1.5 font-bold text-blue-600 dark:text-blue-400 text-lg">
                            {{ number_format($donTangCa->so_gio_tang_ca ?? 0, 2, ',', '') }} <span class="text-xs font-normal">giờ</span>
                        </p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                        <p class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1">
                            <i class="fas fa-tag text-blue-400"></i>
                            Loại
                        </p>
                        <p class="mt-1.5 font-semibold text-gray-900 dark:text-white">
                            @php
                                $loaiDisplay = [
                                    'ngay_thuong' => 'Ngày thường (150%)',
                                    'ngay_nghi' => 'Ngày cuối tuần (200%)',
                                    'le_tet' => 'Lễ, Tết (400%)',
                                ];
                            @endphp
                            {{ $loaiDisplay[$donTangCa->loai_tang_ca] ?? $donTangCa->loai_tang_ca }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- ⭐ LÝ DO --}}
            <div>
                <h3 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i class="fas fa-pen text-blue-500"></i>
                    Lý do
                </h3>
                <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 dark:from-gray-700/30 dark:to-gray-700/20 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                        {{ $donTangCa->ly_do_tang_ca ?? 'Không có lý do' }}
                    </p>
                </div>
            </div>

            {{-- ⭐ NGƯỜI DUYỆT --}}
            <div>
                <h3 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i class="fas fa-user-check text-green-500"></i>
                    Người duyệt
                </h3>
                @if($donTangCa->nguoi_duyet)
                    <div class="flex items-center gap-4 bg-gradient-to-br from-green-50 to-emerald-50/50 dark:from-green-900/20 dark:to-emerald-900/10 rounded-xl p-4 border border-green-200 dark:border-green-800">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center text-white text-lg font-bold shadow-lg shadow-green-500/25">
                            {{ strtoupper(substr(optional($donTangCa->nguoi_duyet->hoSo)->ten ?? 'N', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ optional($donTangCa->nguoi_duyet->hoSo)->ho }} {{ optional($donTangCa->nguoi_duyet->hoSo)->ten }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <i class="far fa-clock"></i>
                                {{ $donTangCa->thoi_gian_duyet ? Carbon\Carbon::parse($donTangCa->thoi_gian_duyet)->format('d/m/Y H:i') : '---' }}
                            </p>
                        </div>
                    </div>
                @else
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-200 dark:border-gray-700 text-center">
                        <p class="text-gray-400 dark:text-gray-500">
                            <i class="fas fa-clock mr-2"></i>
                            Chưa có người duyệt
                        </p>
                    </div>
                @endif
            </div>

            {{-- ⭐ LÝ DO TỪ CHỐI --}}
            @if($donTangCa->trang_thai == 'tu_choi' && $donTangCa->ly_do_tu_choi)
            <div>
                <h3 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i class="fas fa-times-circle text-red-500"></i>
                    Lý do từ chối
                </h3>
                <div class="bg-gradient-to-br from-red-50 to-rose-50/50 dark:from-red-900/20 dark:to-rose-900/10 rounded-xl p-4 border border-red-200 dark:border-red-800">
                    <p class="text-red-700 dark:text-red-300 leading-relaxed">
                        {{ $donTangCa->ly_do_tu_choi }}
                    </p>
                </div>
            </div>
            @endif

            {{-- ⭐ THỰC HIỆN TĂNG CA --}}
            @php
                $thucHien = $donTangCa->thuc_hien;
                $daCheckout = $thucHien && $thucHien->thoi_gian_ket_thuc;
                $gioBatDauThucTe = $thucHien && $thucHien->thoi_gian_bat_dau ? Carbon\Carbon::parse($thucHien->thoi_gian_bat_dau)->format('H:i') : null;
                $gioKetThucThucTe = $daCheckout ? Carbon\Carbon::parse($thucHien->thoi_gian_ket_thuc)->format('H:i') : null;
                $soGioThucTe = $thucHien && $thucHien->so_gio_tang_ca_thuc_te ? $thucHien->so_gio_tang_ca_thuc_te : 0;
                $trangThaiThucHien = $thucHien ? $thucHien->trang_thai : 'chua_xac_nhan';
                $daHoanThanh = $donTangCa->da_hoan_thanh;
            @endphp

            <div>
                <h3 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="fas fa-play-circle text-purple-500"></i>
                    Thực hiện tăng ca
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50/50 dark:from-green-900/20 dark:to-emerald-900/10 rounded-xl p-4 border border-green-200 dark:border-green-800">
                        <p class="text-xs text-green-600 dark:text-green-400 flex items-center gap-1">
                            <i class="fas fa-play-circle"></i>
                            Bắt đầu thực tế
                        </p>
                        <p class="mt-1.5 font-semibold text-gray-900 dark:text-white text-lg">
                            @if($gioBatDauThucTe)
                                {{ $gioBatDauThucTe }}
                                <span class="text-xs font-normal text-green-600 dark:text-green-400 ml-1">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                            @else
                                <span class="text-gray-400">{{ $donTangCa->gio_bat_dau ? Carbon\Carbon::parse($donTangCa->gio_bat_dau)->format('H:i') : '---' }}</span>
                                <span class="text-xs text-gray-400 ml-1">(đăng ký)</span>
                            @endif
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-violet-50/50 dark:from-purple-900/20 dark:to-violet-900/10 rounded-xl p-4 border border-purple-200 dark:border-purple-800">
                        <p class="text-xs text-purple-600 dark:text-purple-400 flex items-center gap-1">
                            <i class="fas fa-stop-circle"></i>
                            Kết thúc thực tế
                        </p>
                        <p class="mt-1.5 font-semibold text-gray-900 dark:text-white text-lg">
                            @if($gioKetThucThucTe)
                                {{ $gioKetThucThucTe }}
                                <span class="text-xs font-normal text-purple-600 dark:text-purple-400 ml-1">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                            @else
                                <span class="text-gray-400">---</span>
                                <span class="text-xs text-gray-400 ml-1">(chưa check-out)</span>
                            @endif
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50/50 dark:from-blue-900/20 dark:to-indigo-900/10 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                        <p class="text-xs text-blue-600 dark:text-blue-400 flex items-center gap-1">
                            <i class="fas fa-hourglass-half"></i>
                            Số giờ thực tế
                        </p>
                        <p class="mt-1.5 font-bold text-blue-600 dark:text-blue-400 text-lg">
                            @if($soGioThucTe > 0 || $daHoanThanh)
                                @php
                                    $hoursDisplay = $soGioThucTe > 0 ? $soGioThucTe : $donTangCa->so_gio_tang_ca;
                                @endphp
                                {{ number_format($hoursDisplay, 2, ',', '') }}
                                <span class="text-xs font-normal">giờ</span>
                                @if($daCheckout && $hoursDisplay < $donTangCa->so_gio_tang_ca)
                                    <span class="text-xs text-yellow-600 dark:text-yellow-400 block mt-0.5">
                                        (sớm {{ number_format($donTangCa->so_gio_tang_ca - $hoursDisplay, 2, ',', '') }}h)
                                    </span>
                                @endif
                            @else
                                <span class="text-gray-400">---</span>
                            @endif
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-orange-50 to-amber-50/50 dark:from-orange-900/20 dark:to-amber-900/10 rounded-xl p-4 border border-orange-200 dark:border-orange-800">
                        <p class="text-xs text-orange-600 dark:text-orange-400 flex items-center gap-1">
                            <i class="fas fa-info-circle"></i>
                            Trạng thái
                        </p>
                        @php
                            $statusThucHienConfig = [
                                'chua_xac_nhan' => ['color' => 'gray', 'label' => '⏸️ Chưa bắt đầu'],
                                'chua_lam' => ['color' => 'gray', 'label' => '⏸️ Chưa bắt đầu'],
                                'dang_lam' => ['color' => 'green', 'label' => '🔄 Đang thực hiện'],
                                'dang_thuc_hien' => ['color' => 'green', 'label' => '🔄 Đang thực hiện'],
                                'nhan_vien_xac_nhan' => ['color' => 'yellow', 'label' => '⏳ Chờ xác nhận'],
                                'quan_ly_xac_nhan' => ['color' => 'purple', 'label' => '✅ Hoàn thành'],
                                'hoan_thanh' => ['color' => 'purple', 'label' => '✅ Hoàn thành'],
                                'khong_hoan_thanh' => ['color' => 'red', 'label' => '❌ Không hoàn thành'],
                            ];
                            $thStatus = $statusThucHienConfig[$trangThaiThucHien] ?? ['color' => 'gray', 'label' => $trangThaiThucHien];
                        @endphp
                        <p class="mt-1.5 font-semibold text-gray-900 dark:text-white text-lg flex items-center gap-2">
                            <span class="text-base 
                                @if($thStatus['color'] == 'green') text-green-600 dark:text-green-400
                                @elseif($thStatus['color'] == 'yellow') text-yellow-600 dark:text-yellow-400
                                @elseif($thStatus['color'] == 'purple') text-purple-600 dark:text-purple-400
                                @elseif($thStatus['color'] == 'red') text-red-600 dark:text-red-400
                                @else text-gray-400 @endif">
                                {{ $thStatus['label'] }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- ⭐ LƯƠNG TĂNG CA --}}
            @php
                $showLuong = false;
                $tienTangCa = 0;
                $luongMotGio = 0;
                $heSoTangCa = 0;
                $hours = 0;
                $luongGross = 0;
                $soNgayCongChuan = 0;
                $tongGioLam = 0;
                $chiTiet = '';
                
                if (($thucHien && $thucHien->thoi_gian_ket_thuc) || $daHoanThanh) {
                    $showLuong = true;
                    $userId = $donTangCa->nguoi_dung_id;
                    
                    if ($thucHien && $thucHien->so_gio_tang_ca_thuc_te > 0) {
                        $hours = $thucHien->so_gio_tang_ca_thuc_te;
                    } else {
                        $hours = $donTangCa->so_gio_tang_ca ?? 0;
                    }
                    
                    $type = $donTangCa->loai_tang_ca;
                    $thang = Carbon\Carbon::parse($donTangCa->ngay_tang_ca)->month;
                    $nam = Carbon\Carbon::parse($donTangCa->ngay_tang_ca)->year;
                    
                    $result = \App\Helpers\OvertimeHelper::tinhLuongTangCaChiTiet(
                        $userId, 
                        $hours, 
                        $type, 
                        $thang, 
                        $nam
                    );
                    
                    $luongMotGio = $result['hourly_rate'];
                    $heSoTangCa = $result['he_so_tang_ca'];
                    $tienTangCa = $result['tien_tang_ca'];
                    $luongGross = $result['luong_gross'];
                    $soNgayCongChuan = $result['so_ngay_cong_chuan'];
                    $tongGioLam = $result['tong_gio_lam_trong_thang'];
                    $chiTiet = $result['chi_tiet'];
                }
            @endphp

            @if($showLuong)
            <div>
                <h3 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="fas fa-coins text-yellow-500"></i>
                    Lương tăng ca
                    @if($donTangCa->trang_thai == 'tu_choi')
                        <span class="ml-2 text-xs text-red-500 font-normal">(Đã bị từ chối)</span>
                    @elseif($daHoanThanh)
                        <span class="ml-2 text-xs text-green-500 font-normal">(Đã hoàn thành)</span>
                    @else
                        <span class="ml-2 text-xs text-blue-500 font-normal">(Đã check-out)</span>
                    @endif
                </h3>
                
                <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 dark:from-gray-700/30 dark:to-gray-700/20 rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm">
                    
                    {{-- Bước 1: Lương 1 giờ --}}
                    <div class="p-5 bg-gradient-to-r from-blue-50/50 to-indigo-50/50 dark:from-blue-900/10 dark:to-indigo-900/10 border-b border-gray-200 dark:border-gray-700">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-500 text-white text-xs font-bold">1</span>
                            Lương 1 giờ làm việc bình thường
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="bg-white/70 dark:bg-gray-800/50 rounded-xl p-3 text-center backdrop-blur-sm">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Lương tháng (Gross)</p>
                                <p class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($luongGross) }}đ</p>
                            </div>
                            <div class="bg-white/70 dark:bg-gray-800/50 rounded-xl p-3 text-center backdrop-blur-sm">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Số ngày công chuẩn</p>
                                <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400">{{ $soNgayCongChuan }} ngày</p>
                            </div>
                            <div class="bg-white/70 dark:bg-gray-800/50 rounded-xl p-3 text-center backdrop-blur-sm border-2 border-blue-200 dark:border-blue-800">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Lương 1 giờ</p>
                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($luongMotGio) }}đ</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">
                                    = {{ number_format($luongGross) }}đ ÷ ({{ $soNgayCongChuan }} × 8)
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Bước 2: Tính lương tăng ca --}}
                    <div class="p-5">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-purple-500 text-white text-xs font-bold">2</span>
                            Tính tiền lương tăng ca
                        </p>
                        
                        <div class="space-y-4">
                            {{-- 3 thông số tính lương --}}
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center border-2 border-blue-200 dark:border-blue-800 shadow-sm">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">① Lương 1 giờ</p>
                                    <p class="font-bold text-blue-600 dark:text-blue-400 text-xl">{{ number_format($luongMotGio) }}đ</p>
                                </div>
                                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center border-2 border-green-200 dark:border-green-800 shadow-sm">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">② Số giờ thực tế</p>
                                    <p class="font-bold text-green-600 dark:text-green-400 text-xl">{{ number_format($hours, 2, ',', '') }} giờ</p>
                                </div>
                                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center border-2 border-orange-200 dark:border-orange-800 shadow-sm">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">③ Hệ số tăng ca</p>
                                    <p class="font-bold text-orange-600 dark:text-orange-400 text-xl">{{ $heSoTangCa }}</p>
                                </div>
                            </div>

                            {{-- Kết quả --}}
                            <div class="p-5 rounded-xl border-2 
                                @if($donTangCa->trang_thai == 'tu_choi') 
                                    bg-red-50 border-red-300 dark:bg-red-900/20 dark:border-red-700
                                @elseif($daHoanThanh)
                                    bg-emerald-50 border-emerald-300 dark:bg-emerald-900/20 dark:border-emerald-700
                                @else
                                    bg-yellow-50 border-yellow-300 dark:bg-yellow-900/20 dark:border-yellow-700
                                @endif
                            ">
                                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-medium 
                                            @if($donTangCa->trang_thai == 'tu_choi') text-red-700 dark:text-red-300
                                            @elseif($daHoanThanh) text-emerald-700 dark:text-emerald-300
                                            @else text-yellow-700 dark:text-yellow-300 @endif">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            @if($donTangCa->trang_thai == 'tu_choi')
                                                ⚠️ Lương tăng ca đã bị từ chối
                                            @elseif($daHoanThanh)
                                                ✅ Lương tăng ca đã được xác nhận
                                            @else
                                                📝 Lương tăng ca tạm tính (chờ xác nhận)
                                            @endif
                                        </p>
                                        @if($donTangCa->trang_thai == 'tu_choi')
                                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                Đơn đã bị từ chối nên lương này sẽ không được thanh toán
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Tổng tiền</p>
                                        <p class="text-3xl font-bold 
                                            @if($donTangCa->trang_thai == 'tu_choi') text-red-600 dark:text-red-400
                                            @elseif($daHoanThanh) text-emerald-600 dark:text-emerald-400
                                            @else text-yellow-600 dark:text-yellow-400 @endif">
                                            {{ number_format($tienTangCa) }}đ
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="px-5 py-3 bg-gray-100/50 dark:bg-gray-700/20 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                            <i class="fas fa-calculator mr-1"></i>
                            {{ number_format($luongMotGio) }}đ × {{ number_format($hours, 1, ',', '') }}h × {{ $heSoTangCa }} = {{ number_format($tienTangCa) }}đ &middot; 
                            Lương tháng: {{ number_format($luongGross) }}đ &middot; 
                            Ngày công: {{ $soNgayCongChuan }} &middot; 
                            Tổng giờ: {{ $tongGioLam }}h
                        </p>
                    </div>
                </div>
            </div>

            @elseif($donTangCa->trang_thai == 'da_duyet' && !$donTangCa->da_hoan_thanh)
                @if($daCheckout)
                    <div>
                        <h3 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <i class="fas fa-coins text-yellow-500"></i>
                            Lương tăng ca
                        </h3>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-6 text-center border border-yellow-200 dark:border-yellow-800">
                            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-yellow-100 dark:bg-yellow-900/30 mb-3">
                                <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-2xl"></i>
                            </div>
                            <p class="text-yellow-700 dark:text-yellow-300 font-medium">
                                Đang chờ trưởng phòng xác nhận hoàn thành
                            </p>
                            <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-1">
                                Lương sẽ được tính sau khi xác nhận
                            </p>
                        </div>
                    </div>
                @else
                    <div>
                        <h3 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <i class="fas fa-coins text-yellow-500"></i>
                            Lương tăng ca
                        </h3>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-6 text-center border border-yellow-200 dark:border-yellow-800">
                            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-yellow-100 dark:bg-yellow-900/30 mb-3">
                                <i class="fas fa-hourglass-half text-yellow-600 dark:text-yellow-400 text-2xl"></i>
                            </div>
                            <p class="text-yellow-700 dark:text-yellow-300 font-medium">
                                Lương sẽ được tính sau khi check-out
                            </p>
                            <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-1">
                                Vui lòng check-out khi kết thúc tăng ca
                            </p>
                        </div>
                    </div>
                @endif

            @elseif($donTangCa->trang_thai == 'cho_duyet')
            <div>
                <h3 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i class="fas fa-coins text-yellow-500"></i>
                    Lương tăng ca
                </h3>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-6 text-center border border-yellow-200 dark:border-yellow-800">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-yellow-100 dark:bg-yellow-900/30 mb-3">
                        <i class="fas fa-file-signature text-yellow-600 dark:text-yellow-400 text-2xl"></i>
                    </div>
                    <p class="text-yellow-700 dark:text-yellow-300 font-medium">
                        Đơn đang chờ duyệt
                    </p>
                    <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-1">
                        Lương sẽ được tính sau khi đơn được duyệt
                    </p>
                </div>
            </div>
            @endif

            {{-- ⭐ NÚT CHECK-OUT & XIN VỀ SỚM --}}
            @php
                $canCheckout = false;
                $checkoutMessage = '';
                $checkoutDisabled = false;
                $checkoutDisabledMessage = '';
                $canXinVeSom = false;
                
                if ($donTangCa->trang_thai == 'da_duyet' && !$donTangCa->da_hoan_thanh) {
                    $now = Carbon\Carbon::now('Asia/Ho_Chi_Minh');
                    $ngayTangCa = Carbon\Carbon::parse($donTangCa->ngay_tang_ca)->startOfDay();
                    $gioBatDau = Carbon\Carbon::parse($donTangCa->gio_bat_dau);
                    $thoiGianBatDau = Carbon\Carbon::parse(
                        $ngayTangCa->format('Y-m-d') . ' ' . $gioBatDau->format('H:i:s')
                    );
                    $daDenGioTangCa = $now->gte($thoiGianBatDau->copy()->subMinutes(30));
                    
                    if ($daCheckout) {
                        $checkoutDisabled = true;
                        $checkoutDisabledMessage = 'Đã check-out lúc ' . Carbon\Carbon::parse($thucHien->thoi_gian_ket_thuc)->format('H:i');
                    } else {
                        if ($daDenGioTangCa) {
                            $canCheckoutResult = \App\Models\DangKyTangCa::canCheckout($donTangCa->id);
                            if ($canCheckoutResult['valid']) {
                                $canCheckout = true;
                                if ($canCheckoutResult['is_early'] ?? false) {
                                    $checkoutMessage = "Sớm " . ($canCheckoutResult['early_minutes'] ?? 0) . " phút";
                                }
                            } else {
                                $checkoutDisabled = true;
                                $checkoutDisabledMessage = $canCheckoutResult['message'];
                            }
                        } else {
                            $checkoutDisabled = true;
                            $diffMinutes = $now->diffInMinutes($thoiGianBatDau);
                            $hours = floor($diffMinutes / 60);
                            $minutes = $diffMinutes % 60;
                            if ($hours > 0) {
                                $checkoutDisabledMessage = "⏳ Chưa đến giờ tăng ca. Còn {$hours} giờ {$minutes} phút nữa.";
                            } else {
                                $checkoutDisabledMessage = "⏳ Chưa đến giờ tăng ca. Còn {$minutes} phút nữa.";
                            }
                        }
                    }
                    
                    if (!$daCheckout) {
                        $gioKetThuc = Carbon\Carbon::parse($donTangCa->gio_ket_thuc);
                        $thoiGianKetThuc = Carbon\Carbon::parse(
                            $ngayTangCa->format('Y-m-d') . ' ' . $gioKetThuc->format('H:i:s')
                        );
                        
                        $sau30pBatDau = $now->gte($thoiGianBatDau->copy()->addMinutes(30));
                        $truoc30pKetThuc = $now->lte($thoiGianKetThuc->copy()->subMinutes(30));
                        
                        if ($sau30pBatDau && $truoc30pKetThuc) {
                            $xinVeSom = $donTangCa->xin_ve_som;
                            if (!$xinVeSom || $xinVeSom->trang_thai == 'tu_choi' || $xinVeSom->trang_thai == 'huy') {
                                $canXinVeSom = true;
                            }
                        }
                    }
                } else {
                    $checkoutDisabled = true;
                    $checkoutDisabledMessage = $donTangCa->da_hoan_thanh ? 'Đã hoàn thành' : 'Đơn chưa được duyệt';
                }
            @endphp

            @if($canCheckout || $canXinVeSom || $checkoutDisabled)
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                @if($canCheckout || $canXinVeSom)
                    <div class="flex flex-wrap items-center gap-4">
                        @if($canCheckout)
                            <form action="{{ route('employee.tang-ca.confirm-thuc-hien', $donTangCa->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-600 hover:to-amber-600 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                                    onclick="return confirm('Xác nhận check-out tăng ca?')">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Check-out
                                    @if($checkoutMessage)
                                        <span class="text-xs opacity-80 bg-white/20 px-2 py-0.5 rounded-full">
                                            {{ $checkoutMessage }}
                                        </span>
                                    @endif
                                </button>
                            </form>
                        @endif

                        @if($canXinVeSom)
                            <a href="{{ route('employee.tang-ca.xin-ve-som', $donTangCa->id) }}"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-clock"></i>
                                Xin về sớm
                            </a>
                            <span class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i>
                                Xin về sớm trước giờ kết thúc, chờ trưởng phòng duyệt
                            </span>
                        @endif
                    </div>
                    
                    @if($canCheckout)
                        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                            <i class="fas fa-info-circle text-blue-400"></i>
                            Check-out trong 10 phút cuối, tính lương đến thời điểm check-out
                        </p>
                    @endif
                @elseif($checkoutDisabled)
                    <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-700">
                        <div class="p-2 bg-gray-200 dark:bg-gray-600 rounded-lg">
                            <i class="fas fa-clock text-gray-500 dark:text-gray-400"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                {{ $checkoutDisabledMessage }}
                            </p>
                            @if($thucHien && $thucHien->thoi_gian_bat_dau)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    <i class="fas fa-play mr-1"></i>
                                    Bắt đầu: {{ Carbon\Carbon::parse($thucHien->thoi_gian_bat_dau)->format('H:i') }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            @endif

        </div>
    </div>
</div>
@endsection