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
        <a href="{{ route('employee.tang-ca.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
            ← Quay lại
        </a>
    </div>

    {{-- THÔNG TIN --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6 space-y-4">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ngày tăng ca</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ Carbon\Carbon::parse($donTangCa->ngay_tang_ca)->format('d/m/Y') }}</p>
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
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $badgeClasses[$donTangCa->trang_thai] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $trangThaiLabels[$donTangCa->trang_thai] ?? $donTangCa->trang_thai }}
                    </span>
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
                            'le_tet' => 'Lễ / Tết',
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

            @if($donTangCa->ly_do_tu_choi)
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-red-500">Lý do từ chối</p>
                <p class="mt-1 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg text-red-700 dark:text-red-300">
                    {{ $donTangCa->ly_do_tu_choi }}
                </p>
            </div>
            @endif

            @if($donTangCa->thoi_gian_duyet)
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    <i class="fas fa-user-check mr-1"></i>
                    Người duyệt: 
                    @php
                        $nguoiDuyet = $donTangCa->nguoi_duyet;
                        $ten = 'Chưa có';
                        if ($nguoiDuyet) {
                            $hoSo = $nguoiDuyet->hoSo;
                            $ten = $hoSo ? ($hoSo->ho . ' ' . $hoSo->ten) : $nguoiDuyet->ten_dang_nhap;
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

            @if($donTangCa->trang_thai == 'cho_duyet')
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <form action="{{ route('employee.tang-ca.huy', $donTangCa->id) }}" method="POST" 
                      onsubmit="return confirm('Bạn có chắc muốn hủy đơn này?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition">
                        <i class="fas fa-times mr-2"></i>
                        Hủy đơn
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection