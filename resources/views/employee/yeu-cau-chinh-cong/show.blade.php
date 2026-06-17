{{-- resources/views/employee/yeu-cau-chinh-cong/show.blade.php --}}
@extends('layouts.employee')

@section('title', 'Chi tiết yêu cầu chỉnh công')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-edit mr-3 text-blue-600"></i>
                Chi tiết yêu cầu chỉnh công
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Thông tin chi tiết yêu cầu</p>
        </div>
        <a href="{{ route('employee.yeu-cau-chinh-cong.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
            ← Quay lại
        </a>
    </div>

    {{-- THÔNG TIN --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6 space-y-4">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ngày</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ Carbon\Carbon::parse($yeuCau->ngay)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Trạng thái</p>
                    @php
                        $badgeClasses = [
                            'cho_duyet' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                            'da_duyet' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                            'tu_choi' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                        ];
                        $trangThaiLabels = [
                            'cho_duyet' => '⏳ Chờ duyệt',
                            'da_duyet' => '✅ Đã duyệt',
                            'tu_choi' => '❌ Từ chối',
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $badgeClasses[$yeuCau->trang_thai] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $trangThaiLabels[$yeuCau->trang_thai] ?? $yeuCau->trang_thai }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Giờ vào</p>
                    <p class="font-semibold text-gray-900 dark:text-white">
                        {{ $yeuCau->gio_vao ? date('H:i', strtotime($yeuCau->gio_vao)) : '--:--' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Giờ ra</p>
                    <p class="font-semibold text-gray-900 dark:text-white">
                        {{ $yeuCau->gio_ra ? date('H:i', strtotime($yeuCau->gio_ra)) : '--:--' }}
                    </p>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">Lý do</p>
                <p class="mt-1 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-gray-700 dark:text-gray-300">
                    {{ $yeuCau->ly_do }}
                </p>
            </div>

            @if($yeuCau->tep_dinh_kem)
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">File đính kèm</p>
                <a href="{{ route('employee.yeu-cau-chinh-cong.download', $yeuCau->id) }}" 
                   class="inline-flex items-center gap-2 mt-1 px-4 py-2 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/50 transition">
                    <i class="fas fa-download"></i>
                    {{ basename($yeuCau->tep_dinh_kem) }}
                </a>
            </div>
            @endif

            @if($yeuCau->ghi_chu_duyet)
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">Ghi chú duyệt</p>
                <p class="mt-1 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-blue-700 dark:text-blue-300">
                    {{ $yeuCau->ghi_chu_duyet }}
                </p>
            </div>
            @endif

            @if($yeuCau->duyet_vao)
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    <i class="fas fa-user-check mr-1"></i>
                    Người duyệt: 
                    @php
                        $nguoiDuyet = $yeuCau->nguoi_duyet;
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
                    Thời gian duyệt: {{ Carbon\Carbon::parse($yeuCau->duyet_vao)->format('d/m/Y H:i') }}
                </p>
            </div>
            @endif

            @if($yeuCau->trang_thai == 'cho_duyet')
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <form action="{{ route('employee.yeu-cau-chinh-cong.huy', $yeuCau->id) }}" method="POST" 
                      onsubmit="return confirm('Bạn có chắc muốn hủy yêu cầu này?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition">
                        <i class="fas fa-times mr-2"></i>
                        Hủy yêu cầu
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection