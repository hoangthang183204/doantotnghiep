{{-- resources/views/employee/tang-ca/show.blade.php --}}
@extends('layouts.employee')

@section('title', 'Chi tiết đơn tăng ca')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-clock mr-3 text-blue-600"></i>
                    Chi tiết đơn tăng ca
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Thông tin chi tiết đơn xin tăng ca</p>
            </div>
            <a href="{{ route('employee.tang-ca.index') }}"
                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                ← Quay lại
            </a>
        </div>

        {{-- THÔNG BÁO --}}
        @if (session('success'))
            <div
                class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg shadow-sm flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-700 dark:text-green-400">×</button>
            </div>
        @endif
        @if (session('error'))
            <div
                class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- THÔNG TIN --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 space-y-4">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Ngày tăng ca</p>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            {{ Carbon\Carbon::parse($donTangCa->ngay_tang_ca)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Trạng thái</p>
                        @php
                            $badgeClasses = [
                                'cho_duyet' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                'da_duyet' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                'tu_choi' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                'huy' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                            ];
                            $trangThaiLabels = [
                                'cho_duyet' => 'Chờ duyệt',
                                'da_duyet' => 'Đã duyệt',
                                'tu_choi' => 'Từ chối',
                                'huy' => 'Đã hủy',
                            ];
                        @endphp
                        <span
                            class="px-3 py-1 rounded-full text-sm font-medium {{ $badgeClasses[$donTangCa->trang_thai] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $trangThaiLabels[$donTangCa->trang_thai] ?? $donTangCa->trang_thai }}
                        </span>
                        @if ($donTangCa->trang_thai == 'da_duyet' && $donTangCa->da_hoan_thanh)
                            <span
                                class="ml-2 px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                ✅ Hoàn thành
                            </span>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Giờ bắt đầu</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $donTangCa->gio_bat_dau }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Giờ kết thúc</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $donTangCa->gio_ket_thuc }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Số giờ tăng ca</p>
                        <p class="font-semibold text-blue-600 dark:text-blue-400">{{ $donTangCa->so_gio_tang_ca }} giờ</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Loại tăng ca</p>
                        @php
                            $loaiLabels = [
                                'ngay_thuong' => 'Ngày thường',
                                'ngay_nghi' => 'Ngày nghỉ',
                            ];
                        @endphp
                        <p class="font-semibold text-gray-900 dark:text-white">
                            {{ $loaiLabels[$donTangCa->loai_tang_ca] ?? $donTangCa->loai_tang_ca }}
                        </p>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Lý do tăng ca</p>
                    <p class="mt-1 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-gray-700 dark:text-gray-300">
                        {{ $donTangCa->ly_do_tang_ca }}
                    </p>
                </div>

                {{-- THÔNG TIN THỰC HIỆN TĂNG CA --}}
                @if ($donTangCa->thuc_hien)
                    @php $thucHien = $donTangCa->thuc_hien; @endphp
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-3">📋 Thực hiện tăng ca</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Giờ bắt đầu thực tế</p>
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    {{ Carbon\Carbon::parse($thucHien->gio_bat_dau_thuc_te)->format('H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Giờ kết thúc thực tế</p>
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    {{ Carbon\Carbon::parse($thucHien->gio_ket_thuc_thuc_te)->format('H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Số giờ thực tế</p>
                                <p class="font-semibold text-blue-600 dark:text-blue-400">
                                    {{ number_format($thucHien->so_gio_tang_ca_thuc_te, 1) }} giờ</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Trạng thái thực hiện</p>
                                @php
                                    $ttThucHienLabels = [
                                        'chua_lam' => '⏳ Chưa làm',
                                        'dang_lam' => '🔄 Đang làm',
                                        'hoan_thanh' => '✅ Hoàn thành',
                                        'khong_hoan_thanh' => '❌ Không hoàn thành',
                                        'nhan_vien_xac_nhan' => '👤 Nhân viên đã xác nhận',
                                        'quan_ly_xac_nhan' => '✅ Quản lý đã xác nhận hoàn thành',
                                    ];
                                    $ttThucHienClasses = [
                                        'chua_lam' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                        'dang_lam' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                        'hoan_thanh' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                        'khong_hoan_thanh' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                        'nhan_vien_xac_nhan' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        'quan_ly_xac_nhan' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                    ];
                                @endphp
                                <span
                                    class="px-3 py-1 rounded-full text-sm font-medium {{ $ttThucHienClasses[$thucHien->trang_thai] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $ttThucHienLabels[$thucHien->trang_thai] ?? $thucHien->trang_thai }}
                                </span>
                            </div>
                        </div>
                        @if ($thucHien->cong_viec_da_thuc_hien)
                            <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Công việc đã thực hiện</p>
                                <p class="text-gray-700 dark:text-gray-300">{{ $thucHien->cong_viec_da_thuc_hien }}</p>
                            </div>
                        @endif
                        @if ($thucHien->ghi_chu)
                            <div class="mt-2 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Ghi chú</p>
                                <p class="text-gray-700 dark:text-gray-300">{{ $thucHien->ghi_chu }}</p>
                            </div>
                        @endif
                        @if ($donTangCa->luong_tang_ca)
                            <div
                                class="mt-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                                <p class="text-sm text-green-600 dark:text-green-400 font-medium">💰 Lương tăng ca</p>
                                <p class="text-lg font-bold text-green-700 dark:text-green-300">
                                    {{ number_format($donTangCa->luong_tang_ca, 0) }}đ</p>
                            </div>
                        @endif
                    </div>
                @endif

                @if ($donTangCa->ly_do_tu_choi)
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-sm text-red-500">Lý do từ chối</p>
                        <p class="mt-1 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg text-red-700 dark:text-red-300">
                            {{ $donTangCa->ly_do_tu_choi }}
                        </p>
                    </div>
                @endif

                @if ($donTangCa->thoi_gian_duyet)
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-user-check mr-1"></i>
                            Người duyệt:
                            @php
                                $nguoiDuyet = $donTangCa->nguoi_duyet;
                                $ten = 'Chưa có';
                                if ($nguoiDuyet) {
                                    $hoSo = $nguoiDuyet->hoSo;
                                    $ten = $hoSo ? $hoSo->ho . ' ' . $hoSo->ten : $nguoiDuyet->ten_dang_nhap;
                                }
                            @endphp
                            {{ $ten }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            <i class="fas fa-clock mr-1"></i>
                            Thời gian duyệt: {{ Carbon\Carbon::parse($donTangCa->thoi_gian_duyet)->format('d/m/Y H:i') }}
                        </p>
                    </div>
                @endif

                {{-- ACTION BUTTONS --}}
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-wrap gap-3">
                    @if ($donTangCa->trang_thai == 'da_duyet' && !$donTangCa->thuc_hien)
                        @php
                            $now = Carbon\Carbon::now();
                            $ngayTangCa = Carbon\Carbon::parse($donTangCa->ngay_tang_ca);
                            $gioBatDau = Carbon\Carbon::parse($donTangCa->gio_bat_dau);
                            $thoiGianBatDau = Carbon\Carbon::parse($ngayTangCa->format('Y-m-d') . ' ' . $gioBatDau->format('H:i:s'));
                            $thoiGianChoPhepSom = $thoiGianBatDau->copy()->subMinutes(30);
                            $coTheXacNhan = $now->gte($thoiGianChoPhepSom);
                            
                            if (!$coTheXacNhan) {
                                $thoiGianConLai = $now->diffInMinutes($thoiGianChoPhepSom);
                                $gioConLai = floor($thoiGianConLai / 60);
                                $phutConLai = $thoiGianConLai % 60;
                                $thongBao = "Còn {$gioConLai} giờ {$phutConLai} phút nữa mới được xác nhận";
                            }
                        @endphp
                        
                        @if($coTheXacNhan)
                            <form action="{{ route('employee.tang-ca.confirm-thuc-hien', $donTangCa->id) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Bạn đã hoàn thành giờ tăng ca này?')"
                                    class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition flex items-center gap-2">
                                    <i class="fas fa-check-circle"></i>
                                    Xác nhận đã làm tăng ca
                                </button>
                            </form>
                        @else
                            <button disabled
                                class="px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed flex items-center gap-2">
                                <i class="fas fa-clock"></i>
                                {{ $thongBao ?? 'Chưa đến giờ tăng ca' }}
                            </button>
                        @endif
                    @endif

                    @if ($donTangCa->trang_thai == 'cho_duyet')
                        <a href="{{ route('employee.tang-ca.edit', $donTangCa->id) }}"
                            class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition flex items-center gap-2">
                            <i class="fas fa-edit"></i>
                            Chỉnh sửa
                        </a>
                    @endif

                    @if ($donTangCa->trang_thai == 'cho_duyet')
                        <form action="{{ route('employee.tang-ca.huy', $donTangCa->id) }}" method="POST"
                            onsubmit="return confirm('Bạn có chắc muốn hủy đơn này?')">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition flex items-center gap-2">
                                <i class="fas fa-times"></i>
                                Hủy đơn
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('employee.tang-ca.index') }}"
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection