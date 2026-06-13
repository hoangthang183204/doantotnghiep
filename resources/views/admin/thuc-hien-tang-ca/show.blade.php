@extends('layouts.admin')

@section('title', 'Chi tiết thực hiện tăng ca')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('admin.thuc-hien-tang-ca.index') }}" class="hover:text-gray-700">Thực hiện tăng ca</a>
                    <span>/</span>
                    <span class="text-gray-700 dark:text-gray-300">Chi tiết</span>
                </div>
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">Chi tiết thực hiện tăng ca</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Xem thông tin đăng ký và kết quả thực hiện tăng ca</p>
            </div>
            <a href="{{ route('admin.thuc-hien-tang-ca.index') }}" 
               class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    @php
        $dangKy = $thucHien->dang_ky;
        $nguoiDung = $dangKy->nguoi_dung ?? null;
        $hoSo = $nguoiDung ? ($nguoiDung->hoSo ?? null) : null;
        
        $hoTen = '';
        if ($hoSo) {
            $hoTen = trim(($hoSo->ho ?? '') . ' ' . ($hoSo->ten ?? ''));
        }
        if (empty($hoTen) && $nguoiDung) {
            $hoTen = $nguoiDung->ten_dang_nhap ?? 'N/A';
        }
        $initial = strtoupper(substr($hoTen, 0, 1));
    @endphp

    {{-- THÔNG TIN NHÂN VIÊN --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h2 class="font-semibold text-gray-800 dark:text-white">Thông tin nhân viên</h2>
        </div>
        <div class="p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-600 dark:text-gray-300 font-bold text-lg">
                    {{ $initial }}
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 dark:text-white">{{ $hoTen }}</h3>
                    <p class="text-sm text-gray-500">Mã NV: {{ $hoSo->ma_nhan_vien ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-500">Phòng ban: {{ $nguoiDung->phongBan->ten_phong_ban ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- THÔNG TIN ĐĂNG KÝ --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <h2 class="font-semibold text-gray-800 dark:text-white">Thông tin đăng ký</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-xs text-gray-500">Ngày tăng ca</p>
                        <p class="font-medium text-gray-800 dark:text-white">{{ $dangKy->ngay_tang_ca->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-400">{{ ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'][$dangKy->ngay_tang_ca->dayOfWeek] }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Loại tăng ca</p>
                        @php
                            $loaiLabels = ['ngay_thuong' => 'Ngày thường', 'ngay_nghi' => 'Ngày nghỉ', 'le_tet' => 'Lễ / Tết'];
                            $loaiColors = ['ngay_thuong' => 'bg-blue-100 text-blue-700', 'ngay_nghi' => 'bg-purple-100 text-purple-700', 'le_tet' => 'bg-red-100 text-red-700'];
                        @endphp
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium mt-1 {{ $loaiColors[$dangKy->loai_tang_ca] ?? 'bg-gray-100' }}">
                            {{ $loaiLabels[$dangKy->loai_tang_ca] ?? $dangKy->loai_tang_ca }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Giờ đăng ký</p>
                        <p class="font-medium text-gray-800 dark:text-white">{{ substr($dangKy->gio_bat_dau, 0, 5) }} - {{ substr($dangKy->gio_ket_thuc, 0, 5) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Số giờ đăng ký</p>
                        <p class="font-semibold text-blue-600 dark:text-blue-400">{{ $dangKy->so_gio_tang_ca }}<span class="text-sm"> giờ</span></p>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">📝 Lý do tăng ca</p>
                    <p class="text-gray-700 dark:text-gray-300">{{ $dangKy->ly_do_tang_ca }}</p>
                </div>

                <div class="mt-4 text-xs text-gray-400">
                    Ngày tạo: {{ $dangKy->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>

        {{-- KẾT QUẢ THỰC HIỆN --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <h2 class="font-semibold text-gray-800 dark:text-white">Kết quả thực hiện</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-xs text-gray-500">Giờ bắt đầu</p>
                        <p class="font-medium text-gray-800 dark:text-white">{{ $thucHien->gio_bat_dau_thuc_te ? substr($thucHien->gio_bat_dau_thuc_te, 0, 5) : '--:--' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Giờ kết thúc</p>
                        <p class="font-medium text-gray-800 dark:text-white">{{ $thucHien->gio_ket_thuc_thuc_te ? substr($thucHien->gio_ket_thuc_thuc_te, 0, 5) : '--:--' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Số giờ thực tế</p>
                        <p class="font-semibold text-blue-600 dark:text-blue-400">{{ number_format($thucHien->so_gio_tang_ca_thuc_te ?? 0, 1) }}<span class="text-sm"> giờ</span></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Công tăng ca</p>
                        <p class="font-semibold text-green-600 dark:text-green-400">{{ number_format($thucHien->so_cong_tang_ca ?? 0, 2) }}</p>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="text-xs text-gray-500 mb-1">Trạng thái</p>
                    @php
                        $statusClass = match($thucHien->trang_thai) {
                            'hoan_thanh' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                            'dang_lam' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                            'khong_hoan_thanh' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                            default => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                        };
                        $statusText = match($thucHien->trang_thai) {
                            'hoan_thanh' => '✅ Hoàn thành',
                            'dang_lam' => '🔄 Đang làm',
                            'khong_hoan_thanh' => '❌ Không hoàn thành',
                            default => '⏳ Chưa làm',
                        };
                    @endphp
                    <span class="inline-block px-2 py-1 rounded-lg text-xs font-medium {{ $statusClass }}">
                        {{ $statusText }}
                    </span>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 mb-4">
                    <p class="text-sm text-gray-500 mb-1">📋 Công việc đã thực hiện</p>
                    <p class="text-gray-700 dark:text-gray-300">{{ $thucHien->cong_viec_da_thuc_hien ?: 'Chưa cập nhật' }}</p>
                </div>

                @if($thucHien->ghi_chu)
                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">💬 Ghi chú</p>
                    <p class="text-gray-700 dark:text-gray-300">{{ $thucHien->ghi_chu }}</p>
                </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ACTION BUTTONS --}}
    <div class="flex gap-3">
        <a href="{{ route('admin.thuc-hien-tang-ca.edit', $thucHien->id) }}"
            class="px-5 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition text-sm">
            ✏️ Cập nhật
        </a>
        <a href="{{ route('admin.thuc-hien-tang-ca.index') }}"
            class="px-5 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition text-sm">
            ← Quay lại
        </a>
    </div>

</div>
@endsection