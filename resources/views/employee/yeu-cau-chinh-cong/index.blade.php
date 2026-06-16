{{-- resources/views/employee/yeu-cau-chinh-cong/index.blade.php --}}
@extends('layouts.employee')

@section('title', 'Yêu cầu chỉnh công')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-edit mr-3 text-blue-600 dark:text-blue-400"></i>
                Yêu cầu chỉnh công
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Quản lý yêu cầu chỉnh sửa công</p>
        </div>
        <a href="{{ route('employee.yeu-cau-chinh-cong.create') }}" 
           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus-circle"></i>
            Tạo yêu cầu mới
        </a>
    </div>

    {{-- THỐNG KÊ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $thongKe['tong'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">Tổng yêu cầu</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $thongKe['cho_duyet'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">⏳ Chờ duyệt</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $thongKe['da_duyet'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">✅ Đã duyệt</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $thongKe['tu_choi'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">❌ Từ chối</p>
        </div>
    </div>

    {{-- DANH SÁCH --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="font-semibold text-gray-900 dark:text-white">📋 Danh sách yêu cầu chỉnh công</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Giờ vào</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Giờ ra</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lý do</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($yeuCaus as $yc)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                {{ Carbon\Carbon::parse($yc->ngay)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                {{ $yc->gio_vao ? date('H:i', strtotime($yc->gio_vao)) : '--:--' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                {{ $yc->gio_ra ? date('H:i', strtotime($yc->gio_ra)) : '--:--' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 max-w-[150px] truncate">
                                {{ $yc->ly_do }}
                            </td>
                            <td class="px-4 py-3">
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
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badgeClasses[$yc->trang_thai] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $trangThaiLabels[$yc->trang_thai] ?? $yc->trang_thai }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('employee.yeu-cau-chinh-cong.show', $yc->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($yc->tep_dinh_kem)
                                        <a href="{{ route('employee.yeu-cau-chinh-cong.download', $yc->id) }}" 
                                           class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 text-sm" title="Tải file">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                    @if($yc->trang_thai == 'cho_duyet')
                                        <form action="{{ route('employee.yeu-cau-chinh-cong.huy', $yc->id) }}" method="POST" 
                                              onsubmit="return confirm('Bạn có chắc muốn hủy yêu cầu này?')">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm" title="Hủy yêu cầu">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                <i class="fas fa-inbox text-2xl block mb-2 text-gray-300 dark:text-gray-600"></i>
                                Chưa có yêu cầu chỉnh công nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($yeuCaus->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                {{ $yeuCaus->links() }}
            </div>
        @endif
    </div>
</div>
@endsection