{{-- resources/views/employee/tang-ca/show.blade.php --}}
@extends('layouts.employee')

@section('title', 'Chi tiết đơn tăng ca')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-file-alt mr-3 text-blue-600"></i>
                Chi tiết đơn tăng ca
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Mã đơn: <span class="font-medium text-blue-600">#{{ $donTangCa->id }}</span>
            </p>
        </div>
        <a href="{{ route('employee.tang-ca.index') }}" 
            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

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

    {{-- THÔNG TIN ĐƠN --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6 space-y-6">
            
            {{-- Thông tin cơ bản --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs text-gray-400">Ngày</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $donTangCa->ngay_tang_ca ? Carbon\Carbon::parse($donTangCa->ngay_tang_ca)->format('d/m/Y') : '---' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Giờ</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $donTangCa->gio_bat_dau ?? '---' }} - {{ $donTangCa->gio_ket_thuc ?? '---' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Số giờ</p>
                    <p class="font-medium text-blue-600 dark:text-blue-400">
                        {{ number_format($donTangCa->so_gio_tang_ca ?? 0, 1, ',', '') }} giờ
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Loại</p>
                    <p class="font-medium text-gray-900 dark:text-white">
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

            <div class="border-t border-gray-200 dark:border-gray-700"></div>

            {{-- Thông tin tạo --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-400">Ngày tạo</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $donTangCa->created_at ? Carbon\Carbon::parse($donTangCa->created_at)->format('d/m/Y H:i') : '---' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Người tạo</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        @if($donTangCa->loai_tao == 'truong_phong')
                            <span class="text-blue-600">Trưởng phòng</span>
                        @else
                            <span class="text-green-600">Nhân viên</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700"></div>

            {{-- Lý do --}}
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                    <i class="fas fa-pen mr-2 text-blue-500"></i>
                    Lý do
                </h3>
                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4">
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
                        {{ $donTangCa->ly_do_tang_ca ?? 'Không có lý do' }}
                    </p>
                </div>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700"></div>

            {{-- Người duyệt --}}
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                    <i class="fas fa-check-circle mr-2 text-green-500"></i>
                    Người duyệt
                </h3>
                @if($donTangCa->nguoi_duyet)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white text-sm font-bold">
                            {{ strtoupper(substr(optional($donTangCa->nguoi_duyet->hoSo)->ten ?? 'N', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">
                                {{ optional($donTangCa->nguoi_duyet->hoSo)->ho }} {{ optional($donTangCa->nguoi_duyet->hoSo)->ten }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $donTangCa->thoi_gian_duyet ? Carbon\Carbon::parse($donTangCa->thoi_gian_duyet)->format('d/m/Y H:i') : '---' }}
                            </p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-400">Chưa duyệt</p>
                @endif
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700"></div>

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
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                    <i class="fas fa-play-circle mr-2 text-blue-500"></i>
                    Thực hiện tăng ca
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-xs text-gray-400">Giờ bắt đầu thực tế</p>
                        <p class="font-medium text-gray-900 dark:text-white">
                            @if($gioBatDauThucTe)
                                <span class="text-green-600 dark:text-green-400">{{ $gioBatDauThucTe }}</span>
                                <span class="text-xs text-gray-400 ml-1">(đã check-in)</span>
                            @else
                                <span class="text-gray-400">{{ $donTangCa->gio_bat_dau ? Carbon\Carbon::parse($donTangCa->gio_bat_dau)->format('H:i') : '---' }}</span>
                                <span class="text-xs text-gray-400 ml-1">(giờ đăng ký)</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Giờ kết thúc thực tế</p>
                        <p class="font-medium text-gray-900 dark:text-white">
                            @if($gioKetThucThucTe)
                                <span class="text-purple-600 dark:text-purple-400">{{ $gioKetThucThucTe }}</span>
                                <span class="text-xs text-gray-400 ml-1">(đã check-out)</span>
                            @else
                                <span class="text-gray-400">---</span>
                                <span class="text-xs text-gray-400 ml-1">(chưa check-out)</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Số giờ thực tế</p>
                        <p class="font-medium text-blue-600 dark:text-blue-400">
                            @if($soGioThucTe > 0 || $daHoanThanh)
                                @php
                                    $hoursDisplay = $soGioThucTe > 0 ? $soGioThucTe : $donTangCa->so_gio_tang_ca;
                                @endphp
                                {{ number_format($hoursDisplay, 1, ',', '') }} giờ
                                @if($daCheckout && $hoursDisplay < $donTangCa->so_gio_tang_ca)
                                    <span class="text-xs text-yellow-600 dark:text-yellow-400 ml-1">
                                        (sớm hơn {{ number_format($donTangCa->so_gio_tang_ca - $hoursDisplay, 1, ',', '') }}h)
                                    </span>
                                @endif
                            @else
                                <span class="text-gray-400">---</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Trạng thái thực hiện</p>
                        @php
                            $trangThaiThucHienLabels = [
                                'chua_xac_nhan' => 'Chưa bắt đầu',
                                'chua_lam' => 'Chưa bắt đầu',
                                'dang_lam' => '🔄 Đang thực hiện',
                                'dang_thuc_hien' => '🔄 Đang thực hiện',
                                'nhan_vien_xac_nhan' => '⏳ Chờ xác nhận',
                                'quan_ly_xac_nhan' => '✅ Hoàn thành',
                                'hoan_thanh' => '✅ Hoàn thành',
                                'khong_hoan_thanh' => '❌ Không hoàn thành',
                            ];
                            $trangThaiThucHienColors = [
                                'chua_xac_nhan' => 'text-gray-400',
                                'chua_lam' => 'text-gray-400',
                                'dang_lam' => 'text-green-600 dark:text-green-400',
                                'dang_thuc_hien' => 'text-green-600 dark:text-green-400',
                                'nhan_vien_xac_nhan' => 'text-yellow-600 dark:text-yellow-400',
                                'quan_ly_xac_nhan' => 'text-purple-600 dark:text-purple-400',
                                'hoan_thanh' => 'text-purple-600 dark:text-purple-400',
                                'khong_hoan_thanh' => 'text-red-600 dark:text-red-400',
                            ];
                        @endphp
                        <p class="font-medium {{ $trangThaiThucHienColors[$trangThaiThucHien] ?? 'text-gray-400' }}">
                            {{ $trangThaiThucHienLabels[$trangThaiThucHien] ?? $trangThaiThucHien }}
                        </p>
                        @if($gioBatDauThucTe && !$gioKetThucThucTe)
                            <p class="text-xs text-green-600 dark:text-green-400">
                                <i class="fas fa-clock mr-1"></i>
                                Đã bắt đầu lúc {{ $gioBatDauThucTe }}
                            </p>
                        @endif
                        @if($gioKetThucThucTe)
                            <p class="text-xs text-purple-600 dark:text-purple-400">
                                <i class="fas fa-check-circle mr-1"></i>
                                Kết thúc lúc {{ $gioKetThucThucTe }}
                            </p>
                        @endif
                        @if($daHoanThanh && !$daCheckout)
                            <p class="text-xs text-green-600 dark:text-green-400">
                                <i class="fas fa-check-circle mr-1"></i>
                                Đã hoàn thành
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700"></div>

            {{-- ⭐ LÝ DO TỪ CHỐI --}}
            @if($donTangCa->trang_thai == 'tu_choi' && $donTangCa->ly_do_tu_choi)
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                    <i class="fas fa-times-circle mr-2 text-red-500"></i>
                    Lý do từ chối
                </h3>
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <p class="text-red-700 dark:text-red-300 whitespace-pre-wrap">
                        {{ $donTangCa->ly_do_tu_choi }}
                    </p>
                </div>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700"></div>
            @endif

            {{-- ⭐ LƯƠNG TĂNG CA - CHỈ HIỂN THỊ KHI ĐÃ CHECK-OUT HOẶC ĐÃ HOÀN THÀNH --}}
            @php
                $showLuong = false;
                $tienTangCa = 0;
                $luongTheoGio = 0;
                $heSoTangCa = 0;
                $hours = 0;
                $luongThucTe = 0;
                $soNgayCong = 0;
                $tongGioLam = 0;
                $chiTiet = '';
                
                // CHỈ TÍNH LƯƠNG KHI ĐÃ CHECK-OUT HOẶC ĐÃ HOÀN THÀNH
                if (($thucHien && $thucHien->thoi_gian_ket_thuc) || $daHoanThanh) {
                    $showLuong = true;
                    
                    $userId = $donTangCa->nguoi_dung_id;
                    
                    // Lấy số giờ thực tế
                    if ($thucHien && $thucHien->so_gio_tang_ca_thuc_te > 0) {
                        $hours = $thucHien->so_gio_tang_ca_thuc_te;
                    } else {
                        $hours = $donTangCa->so_gio_tang_ca ?? 0;
                    }
                    
                    $type = $donTangCa->loai_tang_ca;
                    
                    $thang = Carbon\Carbon::parse($donTangCa->ngay_tang_ca)->month;
                    $nam = Carbon\Carbon::parse($donTangCa->ngay_tang_ca)->year;
                    
                    // Lấy lương từ bảng lương
                    $luongNhanVien = \App\Models\LuongNhanVien::where('nguoi_dung_id', $userId)
                        ->where('luong_thang', $thang)
                        ->where('luong_nam', $nam)
                        ->first();
                    
                    if (!$luongNhanVien) {
                        $luongNhanVien = \App\Models\LuongNhanVien::where('nguoi_dung_id', $userId)
                            ->orderBy('luong_nam', 'desc')
                            ->orderBy('luong_thang', 'desc')
                            ->first();
                    }
                    
                    // Lấy dữ liệu
                    $luongThucTe = 0;
                    $soNgayCong = 0;
                    
                    if ($luongNhanVien) {
                        $luongThucTe = $luongNhanVien->luong_thuc_nhan > 0 ? $luongNhanVien->luong_thuc_nhan : 
                                    ($luongNhanVien->luong_theo_cong > 0 ? $luongNhanVien->luong_theo_cong : 
                                    ($luongNhanVien->tong_luong > 0 ? $luongNhanVien->tong_luong : 0));
                        $soNgayCong = $luongNhanVien->so_ngay_cong > 0 ? $luongNhanVien->so_ngay_cong : 0;
                    }
                    
                    if ($luongThucTe <= 0) {
                        $user = \App\Models\NguoiDung::with('hoSo')->find($userId);
                        if ($user && $user->hoSo && $user->hoSo->luong_co_ban > 0) {
                            $luongThucTe = $user->hoSo->luong_co_ban;
                            $soNgayCong = 26;
                        }
                    }
                    
                    if ($luongThucTe <= 0) {
                        $luongThucTe = 5000000;
                        $soNgayCong = 26;
                    }
                    
                    $tongGioLam = $soNgayCong * 8;
                    $luongTheoGio = $tongGioLam > 0 ? round($luongThucTe / $tongGioLam, 0) : 0;
                    $heSoTangCa = \App\Helpers\OvertimeHelper::getHeSo($type);
                    $tienTangCa = round($hours * $luongTheoGio * $heSoTangCa, 0);
                    
                    $chiTiet = number_format($hours, 1, ',', '') . ' giờ × ' . number_format($luongTheoGio) . 'đ × ' . $heSoTangCa . ' = ' . number_format($tienTangCa) . 'đ';
                }
            @endphp

            @if($showLuong)
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                    <i class="fas fa-coins mr-2 text-yellow-500"></i>
                    Lương tăng ca
                    @if($donTangCa->trang_thai == 'tu_choi')
                        <span class="ml-2 text-xs text-red-500 font-normal">(Đã bị từ chối)</span>
                    @elseif($daHoanThanh)
                        <span class="ml-2 text-xs text-green-500 font-normal">(Đã hoàn thành)</span>
                    @else
                        <span class="ml-2 text-xs text-blue-500 font-normal">(Đã check-out)</span>
                    @endif
                </h3>
                
                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-600">
                    
                    <div class="p-4 border-b border-gray-200 dark:border-gray-600">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Lương theo giờ</p>
                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($luongTheoGio) }}đ/giờ</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Hệ số tăng ca</p>
                                <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $heSoTangCa }} ({{ $heSoTangCa * 100 }}%)</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Số giờ thực tế</p>
                                <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($hours, 1, ',', '') }} giờ</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            📊 Cách tính lương tăng ca
                        </p>
                        
                        <div class="space-y-3 text-sm">
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
                                <p class="text-gray-700 dark:text-gray-300">
                                    <span class="font-medium">Công thức:</span>
                                    {{ number_format($hours, 1, ',', '') }} giờ × {{ number_format($luongTheoGio) }}đ × {{ $heSoTangCa }} = 
                                    <span class="font-bold text-blue-600 dark:text-blue-400">{{ number_format($tienTangCa) }}đ</span>
                                </p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div class="bg-white dark:bg-gray-800 p-3 rounded-lg border border-gray-200 dark:border-gray-600 text-center">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">① Số giờ thực tế</p>
                                    <p class="font-bold text-gray-900 dark:text-white text-lg">{{ number_format($hours, 1, ',', '') }} giờ</p>
                                </div>
                                <div class="bg-white dark:bg-gray-800 p-3 rounded-lg border border-gray-200 dark:border-gray-600 text-center">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">② Lương theo giờ</p>
                                    <p class="font-bold text-blue-600 dark:text-blue-400 text-lg">{{ number_format($luongTheoGio) }}đ</p>
                                </div>
                                <div class="bg-white dark:bg-gray-800 p-3 rounded-lg border border-gray-200 dark:border-gray-600 text-center">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">③ Hệ số tăng ca</p>
                                    <p class="font-bold text-orange-600 dark:text-orange-400 text-lg">{{ $heSoTangCa }}</p>
                                </div>
                            </div>

                            <div class="mt-3 p-4 
                                @if($donTangCa->trang_thai == 'tu_choi') 
                                    bg-red-50 border-red-200 dark:bg-red-900/20 dark:border-red-800
                                @elseif($daHoanThanh)
                                    bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800
                                @else
                                    bg-yellow-50 border-yellow-200 dark:bg-yellow-900/20 dark:border-yellow-800
                                @endif
                                border rounded-lg">
                                <p class="text-sm 
                                    @if($donTangCa->trang_thai == 'tu_choi') 
                                        text-red-700 dark:text-red-300
                                    @elseif($daHoanThanh)
                                        text-green-700 dark:text-green-300
                                    @else
                                        text-yellow-700 dark:text-yellow-300
                                    @endif
                                ">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <span class="font-medium">
                                        @if($donTangCa->trang_thai == 'tu_choi')
                                            ⚠️ Lương tăng ca đã bị từ chối:
                                        @elseif($daHoanThanh)
                                            ✅ Lương tăng ca đã được xác nhận:
                                        @else
                                            📝 Lương tăng ca tạm tính (chờ xác nhận):
                                        @endif
                                    </span>
                                    {{ number_format($tienTangCa) }}đ
                                </p>
                                @if($donTangCa->trang_thai == 'tu_choi')
                                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        Đơn đã bị từ chối nên lương này sẽ không được thanh toán
                                    </p>
                                @elseif(!$daHoanThanh && $donTangCa->trang_thai != 'tu_choi')
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        <i class="fas fa-clock mr-1"></i>
                                        Đang chờ trưởng phòng xác nhận hoàn thành
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border-t border-gray-200 dark:border-gray-600 
                        @if($donTangCa->trang_thai == 'tu_choi') 
                            bg-red-50 dark:bg-red-900/20
                        @elseif($daHoanThanh)
                            bg-green-50 dark:bg-green-900/20
                        @else
                            bg-blue-50 dark:bg-blue-900/20
                        @endif
                    ">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-calculator mr-1"></i>
                                    Tính theo: {{ $chiTiet }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Lương tháng: {{ number_format($luongThucTe) }}đ | 
                                    Ngày công: {{ $soNgayCong }} ngày | 
                                    Tổng giờ: {{ $tongGioLam }} giờ
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    @if($donTangCa->trang_thai == 'tu_choi')
                                        Lương bị từ chối
                                    @elseif($daHoanThanh)
                                        Lương đã xác nhận
                                    @else
                                        Lương tạm tính
                                    @endif
                                </p>
                                <p class="text-2xl font-bold 
                                    @if($donTangCa->trang_thai == 'tu_choi') 
                                        text-red-600 dark:text-red-400
                                    @elseif($daHoanThanh)
                                        text-green-600 dark:text-green-400
                                    @else
                                        text-blue-600 dark:text-blue-400
                                    @endif
                                ">
                                    {{ number_format($tienTangCa) }}đ
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700"></div>

            {{-- ⭐ HIỂN THỊ THÔNG BÁO KHI CHƯA CHECK-OUT --}}
            @elseif($donTangCa->trang_thai == 'da_duyet' && !$donTangCa->da_hoan_thanh)
                @if($daCheckout)
                    {{-- Đã check-out nhưng chưa hoàn thành --}}
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                            <i class="fas fa-coins mr-2 text-yellow-500"></i>
                            Lương tăng ca
                        </h3>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 text-center">
                            <p class="text-yellow-600 dark:text-yellow-400">
                                <i class="fas fa-clock mr-2"></i>
                                Đang chờ trưởng phòng xác nhận hoàn thành
                            </p>
                        </div>
                    </div>
                @else
                    {{-- Chưa check-out --}}
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                            <i class="fas fa-coins mr-2 text-yellow-500"></i>
                            Lương tăng ca
                        </h3>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 text-center">
                            <p class="text-yellow-600 dark:text-yellow-400">
                                <i class="fas fa-clock mr-2"></i>
                                Lương sẽ được tính sau khi check-out
                            </p>
                        </div>
                    </div>
                @endif

            <div class="border-t border-gray-200 dark:border-gray-700"></div>

            @elseif($donTangCa->trang_thai == 'cho_duyet')
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                    <i class="fas fa-coins mr-2 text-yellow-500"></i>
                    Lương tăng ca
                </h3>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 text-center">
                    <p class="text-yellow-600 dark:text-yellow-400">
                        <i class="fas fa-clock mr-2"></i>
                        Đơn đang chờ duyệt
                    </p>
                </div>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700"></div>
            @endif

            {{-- Trạng thái --}}
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                    Trạng thái
                </h3>
                <div>
                    @php
                        $statusColors = [
                            'cho_duyet' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                            'da_duyet' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                            'tu_choi' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                            'huy' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                        ];
                        $statusLabels = [
                            'cho_duyet' => '⏳ Chờ duyệt',
                            'da_duyet' => '✅ Đã duyệt',
                            'tu_choi' => '❌ Từ chối',
                            'huy' => '🗑️ Đã hủy',
                        ];
                    @endphp
                    <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $statusColors[$donTangCa->trang_thai] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabels[$donTangCa->trang_thai] ?? $donTangCa->trang_thai }}
                    </span>
                    
                    @if($donTangCa->trang_thai == 'da_duyet' && $donTangCa->da_hoan_thanh)
                        <span class="ml-2 px-3 py-1.5 rounded-full text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                            ✅ Hoàn thành
                        </span>
                    @endif
                </div>
            </div>

            {{-- ⭐ NÚT CHECK-OUT --}}
            @php
                $canCheckout = false;
                $checkoutMessage = '';
                $checkoutDisabled = false;
                $checkoutDisabledMessage = '';
                $canXinVeSom = false;
                
                if ($donTangCa->trang_thai == 'da_duyet' && !$donTangCa->da_hoan_thanh) {
                    // Kiểm tra đã đến giờ tăng ca chưa (cho phép trước 30 phút)
                    $now = Carbon\Carbon::now('Asia/Ho_Chi_Minh');
                    $ngayTangCa = Carbon\Carbon::parse($donTangCa->ngay_tang_ca)->startOfDay();
                    $gioBatDau = Carbon\Carbon::parse($donTangCa->gio_bat_dau);
                    $thoiGianBatDau = Carbon\Carbon::parse(
                        $ngayTangCa->format('Y-m-d') . ' ' . $gioBatDau->format('H:i:s')
                    );
                    $daDenGioTangCa = $now->gte($thoiGianBatDau->copy()->subMinutes(30));
                    
                    // Nếu đã có bản ghi thực hiện và đã check-out
                    if ($daCheckout) {
                        $checkoutDisabled = true;
                        $checkoutDisabledMessage = 'Đã check-out lúc ' . Carbon\Carbon::parse($thucHien->thoi_gian_ket_thuc)->format('H:i');
                    } else {
                        // Kiểm tra có thể check-out không (chỉ khi đã đến giờ tăng ca)
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
                    
                    // Kiểm tra có thể xin về sớm không (chỉ khi đã đến giờ tăng ca và chưa check-out)
                    if (!$daCheckout && $daDenGioTangCa) {
                        $xinVeSom = $donTangCa->xin_ve_som;
                        if (!$xinVeSom || $xinVeSom->trang_thai == 'tu_choi' || $xinVeSom->trang_thai == 'huy') {
                            $canXinVeSom = true;
                        }
                    }
                } else {
                    $checkoutDisabled = true;
                    $checkoutDisabledMessage = $donTangCa->da_hoan_thanh ? 'Đã hoàn thành' : 'Đơn chưa được duyệt';
                }
            @endphp

            @if($canCheckout || $canXinVeSom)
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <div class="flex flex-wrap gap-3">
                    {{-- Nút Check-out --}}
                    @if($canCheckout)
                        <form action="{{ route('employee.tang-ca.confirm-thuc-hien', $donTangCa->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="px-6 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition shadow-sm hover:shadow-md flex items-center gap-2"
                                onclick="return confirm('Xác nhận check-out tăng ca?')">
                                <i class="fas fa-sign-out-alt"></i>
                                Check-out
                                @if($checkoutMessage)
                                    <span class="text-xs opacity-80">({{ $checkoutMessage }})</span>
                                @endif
                            </button>
                        </form>
                    @endif

                    {{-- Nút Xin về sớm --}}
                    @if($canXinVeSom)
                        <a href="{{ route('employee.tang-ca.xin-ve-som', $donTangCa->id) }}"
                            class="px-6 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition shadow-sm hover:shadow-md flex items-center gap-2">
                            <i class="fas fa-clock"></i>
                            Xin về sớm
                        </a>
                        <span class="text-xs text-gray-500 dark:text-gray-400 self-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Xin về sớm trước giờ kết thúc, chờ trưởng phòng duyệt
                        </span>
                    @endif

                    {{-- Hiển thị thông tin check-out --}}
                    @if($daCheckout)
                        <div class="flex items-center gap-2 px-4 py-2.5 bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-300 rounded-lg border border-purple-200 dark:border-purple-800">
                            <i class="fas fa-check-double text-purple-500"></i>
                            <span>Đã check-out lúc {{ Carbon\Carbon::parse($thucHien->thoi_gian_ket_thuc)->format('H:i') }}</span>
                            <span class="text-xs text-purple-500">({{ number_format($thucHien->so_gio_tang_ca_thuc_te ?? 0, 1, ',', '') }} giờ)</span>
                        </div>
                    @endif
                </div>
                
                @if($canCheckout)
                <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-info-circle mr-1"></i>
                    Check-out sớm tối đa 1 tiếng, tính lương đến thời điểm check-out
                </div>
                @endif
            </div>
            @elseif($checkoutDisabled)
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/30 border border-gray-200 dark:border-gray-600 rounded-lg">
                    <i class="fas fa-clock text-gray-400"></i>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $checkoutDisabledMessage }}
                        </p>
                        @if($thucHien && $thucHien->thoi_gian_bat_dau)
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Giờ bắt đầu: {{ Carbon\Carbon::parse($thucHien->thoi_gian_bat_dau)->format('H:i') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection