@extends('layouts.admin')

@section('title', 'Chi tiết chấm công')

@section('content')

<div class="space-y-6">

    {{-- HEADER WITH BREADCRUMB --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-sm opacity-90 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <a href="{{ route('admin.cham-cong.index') }}" class="hover:underline">Chấm công</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-white/80">Chi tiết</span>
                </div>
                <h1 class="text-3xl font-bold">📋 Chi tiết chấm công</h1>
                <p class="text-blue-100 mt-1">Thông tin chi tiết bản ghi chấm công nhân viên</p>
            </div>
            <a href="{{ route('admin.cham-cong.index') }}" 
               class="px-5 py-2.5 bg-white/20 hover:bg-white/30 backdrop-blur rounded-xl transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    {{-- THÔNG TIN NHÂN VIÊN - PROFILE CARD --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Thông tin nhân viên
            </h2>
        </div>
        <div class="p-6">
            <div class="flex flex-col md:flex-row gap-6 items-start">
                <div class="flex-shrink-0">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                        {{ strtoupper(substr($chamCong->nguoi_dung->hoSo->ten ?? $chamCong->nguoi_dung->ten_dang_nhap ?? 'NV', 0, 1)) }}
                    </div>
                </div>
                <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Họ và tên</label>
                        <p class="mt-1 font-semibold text-gray-800 dark:text-white text-lg">
                            {{ $chamCong->nguoi_dung->hoSo->ho ?? '' }} {{ $chamCong->nguoi_dung->hoSo->ten ?? $chamCong->nguoi_dung->ten_dang_nhap ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Mã nhân viên</label>
                        <p class="mt-1 font-semibold text-gray-800 dark:text-white">
                            {{ $chamCong->nguoi_dung->hoSo->ma_nhan_vien ?? $chamCong->nguoi_dung->id ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Phòng ban</label>
                        <p class="mt-1 font-semibold text-gray-800 dark:text-white">
                            {{ $chamCong->nguoi_dung->phongBan->ten_phong_ban ?? 'Chưa có' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- THÔNG TIN CHẤM CÔNG - GRID HIỆN ĐẠI --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Thông tin chấm công
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Ngày chấm công -->
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-blue-600 dark:text-blue-400 uppercase font-semibold">Ngày chấm công</p>
                            <p class="font-bold text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($chamCong->ngay_cham_cong)->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($chamCong->ngay_cham_cong)->locale('vi')->dayName }}</p>
                        </div>
                    </div>
                </div>

                <!-- Giờ vào -->
                <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-800 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-green-600 dark:text-green-400 uppercase font-semibold">Giờ vào</p>
                            <p class="font-bold text-gray-800 dark:text-white text-xl">{{ $chamCong->gio_vao ? date('H:i', strtotime($chamCong->gio_vao)) : '--:--' }}</p>
                            @if(($chamCong->phut_di_muon ?? 0) > 0)
                                <p class="text-xs text-yellow-600">(+{{ $chamCong->phut_di_muon }} phút)</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Giờ ra -->
                <div class="bg-orange-50 dark:bg-orange-900/20 rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-800 flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-orange-600 dark:text-orange-400 uppercase font-semibold">Giờ ra</p>
                            <p class="font-bold text-gray-800 dark:text-white text-xl">{{ $chamCong->gio_ra ? date('H:i', strtotime($chamCong->gio_ra)) : '--:--' }}</p>
                            @if(($chamCong->phut_ve_som ?? 0) > 0)
                                <p class="text-xs text-yellow-600">(-{{ $chamCong->phut_ve_som }} phút)</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Số giờ làm -->
                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-800 flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-purple-600 dark:text-purple-400 uppercase font-semibold">Số giờ làm</p>
                            <p class="font-bold text-gray-800 dark:text-white text-xl">{{ number_format($chamCong->so_gio_lam ?? 0, 1) }} giờ</p>
                        </div>
                    </div>
                </div>

                <!-- Số công -->
                <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-800 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-indigo-600 dark:text-indigo-400 uppercase font-semibold">Số công</p>
                            <p class="font-bold text-gray-800 dark:text-white text-xl">{{ number_format($chamCong->so_cong ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tăng ca -->
                <div class="bg-pink-50 dark:bg-pink-900/20 rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-pink-100 dark:bg-pink-800 flex items-center justify-center">
                            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-pink-600 dark:text-pink-400 uppercase font-semibold">Giờ tăng ca</p>
                            <p class="font-bold text-gray-800 dark:text-white text-xl">{{ number_format($chamCong->gio_tang_ca ?? 0, 1) }} giờ</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- TRẠNG THÁI & PHƯƠNG THỨC --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Trạng thái -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Trạng thái
                </h2>
            </div>
            <div class="p-6">
                @php
                    $statusConfig = [
                        'dung_gio' => ['bg-green-100 dark:bg-green-900/30', 'text-green-700 dark:text-green-300', '✅ Đúng giờ'],
                        'di_muon' => ['bg-yellow-100 dark:bg-yellow-900/30', 'text-yellow-700 dark:text-yellow-300', '⚠️ Đi muộn'],
                        've_som' => ['bg-orange-100 dark:bg-orange-900/30', 'text-orange-700 dark:text-orange-300', '🔻 Về sớm'],
                        'khong_cham_cong' => ['bg-red-100 dark:bg-red-900/30', 'text-red-700 dark:text-red-300', '❌ Không chấm công'],
                    ];
                    $config = $statusConfig[$chamCong->trang_thai] ?? $statusConfig['khong_cham_cong'];
                @endphp
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full {{ str_replace('bg-', 'bg-', $config[0]) }}"></div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $config[0] }} {{ $config[1] }}">
                        {{ $config[2] }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Phương thức chấm công -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Phương thức chấm công
                </h2>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-3">
                    @if($chamCong->phuong_thuc_cham_cong == 'may_quet')
                        <span class="text-2xl">🖥️</span>
                        <span class="font-semibold text-gray-800 dark:text-white">Máy quét vân tay</span>
                    @elseif($chamCong->phuong_thuc_cham_cong == 'app_dien_thoai')
                        <span class="text-2xl">📱</span>
                        <span class="font-semibold text-gray-800 dark:text-white">App điện thoại</span>
                    @elseif($chamCong->phuong_thuc_cham_cong == 'web')
                        <span class="text-2xl">💻</span>
                        <span class="font-semibold text-gray-800 dark:text-white">Web</span>
                    @else
                        <span class="text-2xl">❓</span>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ $chamCong->phuong_thuc_cham_cong ?? 'Chưa xác định' }}</span>
                    @endif
                </div>
                @if($chamCong->dia_chi_ip)
                    <p class="text-xs text-gray-500 mt-3">IP: {{ $chamCong->dia_chi_ip }}</p>
                @endif
            </div>
        </div>

    </div>

    {{-- PHÊ DUYỆT --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Thông tin phê duyệt
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm text-gray-500 dark:text-gray-400">👤 Người phê duyệt</label>
                    <p class="mt-1 font-semibold text-gray-800 dark:text-white">
                        @php
                            $nguoiDuyet = $chamCong->nguoi_phe_duyet;
                            if ($nguoiDuyet) {
                                $ho = $nguoiDuyet->hoSo->ho ?? '';
                                $ten = $nguoiDuyet->hoSo->ten ?? $nguoiDuyet->ten_dang_nhap ?? '';
                                echo trim($ho . ' ' . $ten);
                            } else {
                                echo 'Chưa có';
                            }
                        @endphp
                    </p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 dark:text-gray-400">📅 Thời gian phê duyệt</label>
                    <p class="mt-1 font-semibold text-gray-800 dark:text-white">
                        {{ $chamCong->thoi_gian_phe_duyet ? \Carbon\Carbon::parse($chamCong->thoi_gian_phe_duyet)->format('d/m/Y H:i') : 'Chưa có' }}
                    </p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 dark:text-gray-400">🔖 Trạng thái duyệt</label>
                    <div class="mt-2">
                        @if($chamCong->trang_thai_duyet == 1)
                            <span class="px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300">
                                ✅ Đã duyệt
                            </span>
                        @elseif($chamCong->trang_thai_duyet == 2)
                            <span class="px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300">
                                ❌ Từ chối
                            </span>
                        @else
                            <span class="px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300">
                                ⏳ Chờ duyệt
                            </span>
                        @endif
                    </div>
                </div>
                @if($chamCong->ghi_chu_duyet)
                <div class="md:col-span-2">
                    <label class="text-sm text-gray-500 dark:text-gray-400">📝 Ghi chú duyệt</label>
                    <p class="mt-1 text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                        {{ $chamCong->ghi_chu_duyet }}
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- GHI CHÚ --}}
    @if($chamCong->ghi_chu)
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Ghi chú
            </h2>
        </div>
        <div class="p-6">
            <p class="text-gray-700 dark:text-gray-300 bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg border-l-4 border-yellow-500">
                {{ $chamCong->ghi_chu }}
            </p>
        </div>
    </div>
    @endif

</div>

@endsection