@extends('layouts.admin')

@section('title', 'Chi tiết chấm công')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">📋 Chi tiết chấm công</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Thông tin chi tiết bản ghi chấm công</p>
            </div>
            <a href="{{ route('admin.cham-cong.index') }}" 
               class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    {{-- THÔNG TIN NHÂN VIÊN --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
            <h2 class="font-medium text-gray-700 dark:text-gray-300">👤 Thông tin nhân viên</h2>
        </div>
        <div class="p-6">
            @php
                $nguoiDung = $chamCong->nguoiDung ?? null;
                $hoSo = $nguoiDung ? $nguoiDung->hoSo ?? null : null;
                $hoTen = '';
                if ($hoSo) {
                    $hoTen = trim(($hoSo->ho ?? '') . ' ' . ($hoSo->ten ?? ''));
                }
                if (empty($hoTen) && $nguoiDung) {
                    $hoTen = $nguoiDung->ten_dang_nhap ?? 'N/A';
                }
                if (empty($hoTen)) {
                    $hoTen = 'NV#' . ($chamCong->nguoi_dung_id ?? '?');
                }
                $hasAvatar = $hoSo && $hoSo->anh_dai_dien && file_exists(public_path('storage/' . $hoSo->anh_dai_dien));
                $avatar = $hasAvatar ? asset('storage/' . $hoSo->anh_dai_dien) : null;
            @endphp
            <div class="flex items-center gap-4">
                @if($avatar)
                    <img src="{{ $avatar }}" alt="{{ $hoTen }}" class="w-14 h-14 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">
                @else
                    <div class="w-14 h-14 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-xl">
                        {{ strtoupper(substr($hoTen, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ $hoTen }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Mã: {{ $hoSo ? $hoSo->ma_nhan_vien ?? 'N/A' : 'N/A' }}
                        @if($nguoiDung && $nguoiDung->phongBan)
                            | Phòng: {{ $nguoiDung->phongBan->ten_phong_ban }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- THÔNG TIN CHẤM CÔNG --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
            <h2 class="font-medium text-gray-700 dark:text-gray-300">⏰ Thông tin chấm công</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                {{-- Ngày --}}
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Ngày</p>
                    <p class="font-semibold text-gray-800 dark:text-white">
                        {{ \Carbon\Carbon::parse($chamCong->ngay_cham_cong)->format('d/m/Y') }}
                    </p>
                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($chamCong->ngay_cham_cong)->locale('vi')->dayName }}</p>
                </div>

                {{-- Giờ vào --}}
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Giờ vào</p>
                    <p class="font-semibold text-gray-800 dark:text-white text-lg">
                        {{ $chamCong->gio_vao ? \Carbon\Carbon::parse($chamCong->gio_vao)->format('H:i') : '--:--' }}
                    </p>
                    @if(($chamCong->phut_di_muon ?? 0) > 0)
                        <p class="text-xs text-yellow-600">+{{ $chamCong->phut_di_muon }}p</p>
                    @endif
                </div>

                {{-- Giờ ra --}}
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Giờ ra</p>
                    <p class="font-semibold text-gray-800 dark:text-white text-lg">
                        {{ $chamCong->gio_ra ? \Carbon\Carbon::parse($chamCong->gio_ra)->format('H:i') : '--:--' }}
                    </p>
                    @if(($chamCong->phut_ve_som ?? 0) > 0)
                        <p class="text-xs text-yellow-600">-{{ $chamCong->phut_ve_som }}p</p>
                    @endif
                </div>

                {{-- Số giờ --}}
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Số giờ</p>
                    <p class="font-semibold text-blue-600 dark:text-blue-400 text-lg">
                        {{ number_format($chamCong->so_gio_lam ?? 0, 1) }}h
                    </p>
                </div>

                {{-- Số công --}}
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Số công</p>
                    <p class="font-semibold text-gray-800 dark:text-white">
                        {{ number_format($chamCong->so_cong ?? 0, 2) }}
                    </p>
                </div>

                {{-- Tăng ca --}}
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Tăng ca</p>
                    <p class="font-semibold text-purple-600 dark:text-purple-400">
                        {{ number_format($chamCong->gio_tang_ca ?? 0, 1) }}h
                    </p>
                </div>

                {{-- Trạng thái --}}
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Trạng thái</p>
                    @php
                        $statusMap = [
                            'dung_gio' => ['bg-green-100 text-green-700', '✅ Đúng giờ'],
                            'di_muon' => ['bg-yellow-100 text-yellow-700', '⚠️ Đi muộn'],
                            've_som' => ['bg-orange-100 text-orange-700', '🔻 Về sớm'],
                            'den_som' => ['bg-blue-100 text-blue-700', '📈 Đến sớm'],
                            'vang_mat' => ['bg-red-100 text-red-700', '❌ Vắng mặt'],
                            'khong_cham_cong' => ['bg-gray-100 text-gray-700', '⏸️ Không chấm công'],
                            'nghi_phep' => ['bg-indigo-100 text-indigo-700', '📅 Nghỉ phép'],
                            'tang_ca' => ['bg-purple-100 text-purple-700', '🔄 Tăng ca'],
                        ];
                        $stt = $statusMap[$chamCong->trang_thai] ?? ['bg-gray-100 text-gray-700', $chamCong->trang_thai];
                    @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $stt[0] }}">
                        {{ $stt[1] }}
                    </span>
                </div>

                {{-- Phương thức --}}
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Phương thức</p>
                    @php
                        $methodMap = [
                            'ip' => [
                                'icon' => '📡',
                                'label' => 'IP',
                                'color' => 'text-blue-600 dark:text-blue-400'
                            ],
                            'wifi' => [
                                'icon' => '📶',
                                'label' => 'WiFi',
                                'color' => 'text-green-600 dark:text-green-400'
                            ],
                            'mac' => [
                                'icon' => '💻',
                                'label' => 'MAC Address',
                                'color' => 'text-purple-600 dark:text-purple-400'
                            ],
                            'manual' => [
                                'icon' => '✍️',
                                'label' => 'Nhập tay',
                                'color' => 'text-gray-600 dark:text-gray-400'
                            ],
                            'face' => [
                                'icon' => '😊',
                                'label' => 'Nhận diện khuôn mặt',
                                'color' => 'text-indigo-600 dark:text-indigo-400'
                            ],
                        ];
                        $method = $methodMap[$chamCong->phuong_thuc_cham_cong] ?? [
                            'icon' => '❓',
                            'label' => 'Chưa xác định',
                            'color' => 'text-gray-400'
                        ];
                    @endphp
                    <div class="flex items-center gap-2">
                        <span class="text-lg">{{ $method['icon'] }}</span>
                        <span class="font-semibold {{ $method['color'] }}">
                            {{ $method['label'] }}
                        </span>
                    </div>
                    
                    {{-- Hiển thị thông tin chi tiết tùy theo phương thức --}}
                    @if($chamCong->phuong_thuc_cham_cong == 'ip' && $chamCong->dia_chi_ip)
                        <p class="text-xs text-gray-400 mt-1">IP: {{ $chamCong->dia_chi_ip }}</p>
                    @elseif($chamCong->phuong_thuc_cham_cong == 'wifi' && $chamCong->ten_wifi)
                        <p class="text-xs text-gray-400 mt-1">WiFi: {{ $chamCong->ten_wifi }}</p>
                    @elseif($chamCong->phuong_thuc_cham_cong == 'mac' && $chamCong->dia_chi_mac)
                        <p class="text-xs text-gray-400 mt-1">MAC: {{ $chamCong->dia_chi_mac }}</p>
                    @elseif($chamCong->phuong_thuc_cham_cong == 'face')
                        @php
                            $faceRecord = $chamCong->faceRecords()->first();
                        @endphp
                        @if($faceRecord)
                            <p class="text-xs text-gray-400 mt-1">
                                Độ tin cậy: {{ number_format($faceRecord->confidence * 100, 1) }}%
                            </p>
                            @if($faceRecord->image_path)
                                <p class="text-xs text-gray-400">Đã lưu ảnh</p>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- THÔNG TIN THIẾT BỊ --}}
    @if($chamCong->ten_thiet_bi || $chamCong->dia_chi_ip || $chamCong->ten_wifi || $chamCong->dia_chi_mac)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
            <h2 class="font-medium text-gray-700 dark:text-gray-300">🖥️ Thông tin thiết bị</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($chamCong->ten_thiet_bi)
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Tên thiết bị</p>
                        <p class="text-gray-800 dark:text-white font-medium">{{ $chamCong->ten_thiet_bi }}</p>
                    </div>
                @endif
                @if($chamCong->dia_chi_ip)
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Địa chỉ IP</p>
                        <p class="text-gray-800 dark:text-white font-mono">{{ $chamCong->dia_chi_ip }}</p>
                    </div>
                @endif
                @if($chamCong->ten_wifi)
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Tên WiFi</p>
                        <p class="text-gray-800 dark:text-white font-medium">{{ $chamCong->ten_wifi }}</p>
                    </div>
                @endif
                @if($chamCong->dia_chi_mac)
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Địa chỉ MAC</p>
                        <p class="text-gray-800 dark:text-white font-mono">{{ $chamCong->dia_chi_mac }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- GHI CHÚ --}}
    @if($chamCong->ghi_chu)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
            <h2 class="font-medium text-gray-700 dark:text-gray-300">📝 Ghi chú</h2>
        </div>
        <div class="p-6">
            <p class="text-gray-700 dark:text-gray-300 bg-yellow-50 dark:bg-yellow-900/10 p-3 rounded-lg border border-yellow-200 dark:border-yellow-800 text-sm">
                {{ $chamCong->ghi_chu }}
            </p>
        </div>
    </div>
    @endif

</div>
@endsection