{{-- resources/views/employee/don-nghi/show.blade.php --}}
@extends('layouts.employee')

@section('title', 'Chi tiết đơn nghỉ phép')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Chi tiết đơn nghỉ phép</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Mã đơn: #{{ $donNghi->id }}</p>
        </div>
        <div class="mt-4 md:mt-0 flex items-center space-x-3">
            @if($donNghi->trang_thai == 'cho_duyet')
                <a href="{{ route('employee.don-nghi.edit', $donNghi->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Sửa
                </a>
                <form method="POST" action="{{ route('employee.don-nghi.huy', $donNghi->id) }}" class="inline" onsubmit="return confirm('Bạn có chắc muốn hủy đơn này?')">
                    @csrf
                    @method('POST')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hủy đơn
                    </button>
                </form>
            @endif
            <a href="{{ route('employee.don-nghi.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    {{-- Chi tiết --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Cột trái: Thông tin đơn nghỉ --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Thông tin đơn nghỉ</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Loại nghỉ</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $donNghi->loaiNghiPhep->ten ?? 'Không xác định' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Trạng thái</p>
                            @php
                                $statusColors = [
                                    'cho_duyet' => 'yellow', 
                                    'da_duyet' => 'green', 
                                    'tu_choi' => 'red', 
                                    'huy_bo' => 'gray'
                                ];
                                $statusLabels = [
                                    'cho_duyet' => '⏳ Chờ duyệt', 
                                    'da_duyet' => '✅ Đã duyệt', 
                                    'tu_choi' => '❌ Từ chối', 
                                    'huy_bo' => '🚫 Đã hủy'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $statusColors[$donNghi->trang_thai] ?? 'gray' }}-100 text-{{ $statusColors[$donNghi->trang_thai] ?? 'gray' }}-800 dark:bg-{{ $statusColors[$donNghi->trang_thai] ?? 'gray' }}-900/30 dark:text-{{ $statusColors[$donNghi->trang_thai] ?? 'gray' }}-400">
                                {{ $statusLabels[$donNghi->trang_thai] ?? $donNghi->trang_thai }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Từ ngày</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($donNghi->ngay_bat_dau)->format('d/m/Y') }}</p>
                            <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($donNghi->ngay_bat_dau)->locale('vi')->dayName }}</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Đến ngày</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($donNghi->ngay_ket_thuc)->format('d/m/Y') }}</p>
                            <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($donNghi->ngay_ket_thuc)->locale('vi')->dayName }}</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Số ngày nghỉ</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $donNghi->so_ngay_nghi }} ngày</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Ngày tạo</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $donNghi->created_at->format('d/m/Y H:i') }}</p>
                            <span class="text-xs text-gray-400">{{ $donNghi->created_at->locale('vi')->diffForHumans() }}</span>
                        </div>
                    </div>

                    {{-- Lý do --}}
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Lý do</p>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $donNghi->ly_do ?: 'Không có lý do' }}</p>
                    </div>
                    
                    {{-- Ghi chú --}}
                    @if($donNghi->ghi_chu)
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Ghi chú</p>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $donNghi->ghi_chu }}</p>
                        </div>
                    @endif

                    {{-- Lý do từ chối (nếu có) --}}
                    @if($donNghi->trang_thai == 'tu_choi' && $donNghi->ghi_chu)
                        <div class="mt-2 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                            <p class="text-sm text-red-600 dark:text-red-400 font-medium">📌 Lý do từ chối:</p>
                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $donNghi->ghi_chu }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Cột phải: Thông tin duyệt và số dư --}}
        <div class="space-y-4">
            {{-- Thông tin duyệt --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Thông tin duyệt</h3>
                </div>
                <div class="p-4 space-y-3">
                    @if($donNghi->trang_thai == 'da_duyet')
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Người duyệt</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                @if($donNghi->nguoiDuyet)
                                    {{ optional($donNghi->nguoiDuyet->hoSo)->ho ?? '' }} 
                                    {{ optional($donNghi->nguoiDuyet->hoSo)->ten ?? $donNghi->nguoiDuyet->ten_dang_nhap }}
                                @else
                                    <span class="text-gray-400">Chưa có</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Thời gian duyệt</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                @if($donNghi->thoi_gian_duyet)
                                    {{ \Carbon\Carbon::parse($donNghi->thoi_gian_duyet)->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-gray-400">Chưa có</span>
                                @endif
                            </p>
                        </div>
                    @elseif($donNghi->trang_thai == 'tu_choi')
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Người từ chối</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                @if($donNghi->nguoiDuyet)
                                    {{ optional($donNghi->nguoiDuyet->hoSo)->ho ?? '' }} 
                                    {{ optional($donNghi->nguoiDuyet->hoSo)->ten ?? $donNghi->nguoiDuyet->ten_dang_nhap }}
                                @else
                                    <span class="text-gray-400">Chưa có</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Thời gian từ chối</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                @if($donNghi->thoi_gian_duyet)
                                    {{ \Carbon\Carbon::parse($donNghi->thoi_gian_duyet)->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-gray-400">Chưa có</span>
                                @endif
                            </p>
                        </div>
                    @elseif($donNghi->trang_thai == 'huy_bo')
                        <div class="text-center py-4">
                            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">🚫 Đã hủy</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Đơn đã bị hủy bởi nhân viên</p>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">⏳ Đang chờ duyệt</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Vui lòng chờ phê duyệt từ quản lý</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Số dư nghỉ phép --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Số dư nghỉ phép</h3>
                </div>
                <div class="p-4">
                    @php
                        $soNgayPhepNam = 12;
                        $soNgayDaNghi = App\Models\DonXinNghi::where('nguoi_dung_id', $donNghi->nguoi_dung_id)
                            ->where('trang_thai', 'da_duyet')
                            ->whereYear('ngay_bat_dau', now()->year)
                            ->sum('so_ngay_nghi');
                        $soDuConLai = max(0, $soNgayPhepNam - $soNgayDaNghi);
                    @endphp
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Còn lại</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($soDuConLai, 1) }}</p>
                            <p class="text-xs text-gray-400">/ {{ $soNgayPhepNam }} ngày</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    {{-- Thanh tiến trình --}}
                    <div class="mt-3">
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            @php
                                $phanTram = $soNgayPhepNam > 0 ? (($soDuConLai) / $soNgayPhepNam) * 100 : 0;
                            @endphp
                            <div class="bg-green-500 h-2 rounded-full transition-all duration-500" 
                                 style="width: {{ min(100, $phanTram) }}%"></div>
                        </div>
                        <div class="flex justify-between mt-1">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Đã nghỉ {{ number_format($soNgayDaNghi, 1) }} ngày</span>
                            <span class="text-xs text-green-600 dark:text-green-400 font-medium">{{ number_format($soDuConLai, 1) }} ngày</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection