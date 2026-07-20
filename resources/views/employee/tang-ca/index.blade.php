{{-- resources/views/employee/tang-ca/index.blade.php --}}
@extends('layouts.employee')

@section('title', 'Đơn xin tăng ca')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-clock mr-3 text-blue-600 dark:text-blue-400"></i>
                    Đơn xin tăng ca
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Quản lý đơn xin tăng ca của bạn</p>
            </div>
            <a href="{{ route('employee.tang-ca.create') }}"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus-circle"></i>
                Tạo đơn mới
            </a>
        </div>

        {{-- THỐNG KÊ ĐƠN --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $thongKe['tong'] ?? 0 }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tổng đơn</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
                <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $thongKe['cho_duyet'] ?? 0 }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">⏳ Chờ duyệt</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $thongKe['da_duyet'] ?? 0 }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">✅ Đã duyệt</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
                <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                    {{ $donTangCa->filter(function ($item) {
                            return $item->trang_thai == 'da_duyet' &&
                                $item->thuc_hien &&
                                $item->thuc_hien->trang_thai == 'quan_ly_xac_nhan';
                        })->count() }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">✅ Hoàn thành</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
                <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $thongKe['tu_choi'] ?? 0 }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">❌ Từ chối</p>
            </div>
        </div>

        {{-- ⭐ THỐNG KÊ GIỚI HẠN GIỜ TĂNG CA - CHỈ 1 HÀNG --}}
        @php
            $thongKeGio = App\Helpers\OvertimeHelper::thongKeGioTangCa(Auth::id());
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas fa-calendar-alt text-blue-500 text-xs"></i>
                    <p class="text-xs font-medium text-blue-600 dark:text-blue-400">Đã dùng tháng</p>
                </div>
                <p class="text-xl font-bold text-blue-700 dark:text-blue-300">
                    {{ $thongKeGio['trong_thang_text'] }}
                </p>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-500"
                        style="width: {{ min(100, ($thongKeGio['trong_thang'] / $thongKeGio['limit_month']) * 100) }}%">
                    </div>
                </div>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1.5">
                    Giới hạn: <span class="font-medium">{{ $thongKeGio['limit_month_text'] }}</span>
                </p>
            </div>

            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-200 dark:border-green-800">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas fa-calendar-check text-green-500 text-xs"></i>
                    <p class="text-xs font-medium text-green-600 dark:text-green-400">Đã dùng năm</p>
                </div>
                <p class="text-xl font-bold text-green-700 dark:text-green-300">
                    {{ $thongKeGio['trong_nam_text'] }}
                </p>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
                    <div class="bg-green-600 h-2 rounded-full transition-all duration-500"
                        style="width: {{ min(100, ($thongKeGio['trong_nam'] / $thongKeGio['limit_year']) * 100) }}%">
                    </div>
                </div>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1.5">
                    Giới hạn: <span class="font-medium">{{ $thongKeGio['limit_year_text'] }}</span>
                </p>
            </div>

            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg border border-yellow-200 dark:border-yellow-800">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas fa-clock text-yellow-500 text-xs"></i>
                    <p class="text-xs font-medium text-yellow-600 dark:text-yellow-400">Còn lại tháng</p>
                </div>
                <p class="text-xl font-bold text-yellow-700 dark:text-yellow-300">
                    {{ $thongKeGio['remaining_month_text'] }}
                </p>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
                    <div class="bg-yellow-600 h-2 rounded-full transition-all duration-500"
                        style="width: {{ min(100, ($thongKeGio['remaining_month'] / $thongKeGio['limit_month']) * 100) }}%">
                    </div>
                </div>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1.5">
                    Còn <span class="font-medium">{{ $thongKeGio['remaining_month_text'] }}</span>
                </p>
            </div>

            <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg border border-purple-200 dark:border-purple-800">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas fa-infinity text-purple-500 text-xs"></i>
                    <p class="text-xs font-medium text-purple-600 dark:text-purple-400">Còn lại năm</p>
                </div>
                <p class="text-xl font-bold text-purple-700 dark:text-purple-300">
                    {{ $thongKeGio['remaining_year_text'] }}
                </p>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
                    <div class="bg-purple-600 h-2 rounded-full transition-all duration-500"
                        style="width: {{ min(100, ($thongKeGio['remaining_year'] / $thongKeGio['limit_year']) * 100) }}%">
                    </div>
                </div>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1.5">
                    Còn <span class="font-medium">{{ $thongKeGio['remaining_year_text'] }}</span>
                </p>
            </div>
        </div>

        {{-- DANH SÁCH --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white">📋 Danh sách đơn tăng ca</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Giờ</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Số giờ</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($donTangCa as $don)
                            @php
                                $thucHien = $don->thuc_hien;
                                $daXacNhan = $thucHien && $thucHien->trang_thai === 'quan_ly_xac_nhan';
                                $daNhanVienXacNhan = $thucHien && $thucHien->trang_thai === 'nhan_vien_xac_nhan';
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                    {{ Carbon\Carbon::parse($don->ngay_tang_ca)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $don->gio_bat_dau }} - {{ $don->gio_ket_thuc }}
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-blue-600 dark:text-blue-400">
                                    {{ $don->so_gio_tang_ca }} giờ
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    @php
                                        $loaiLabels = [
                                            'ngay_thuong' => 'Ngày thường',
                                            'ngay_nghi' => 'Ngày nghỉ',
                                        ];
                                    @endphp
                                    {{ $loaiLabels[$don->loai_tang_ca] ?? $don->loai_tang_ca }}
                                </td>
                                <td class="px-4 py-3">
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
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badgeClasses[$don->trang_thai] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $trangThaiLabels[$don->trang_thai] ?? $don->trang_thai }}
                                    </span>

                                    @if ($don->trang_thai == 'da_duyet')
                                        @if ($daXacNhan)
                                            <span class="ml-1 px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                                ✅ Hoàn thành
                                            </span>
                                        @elseif($daNhanVienXacNhan)
                                            <span class="ml-1 px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                ⏳ Chờ xác nhận
                                            </span>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('employee.tang-ca.show', $don->id) }}"
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm"
                                            title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if ($don->trang_thai == 'da_duyet' && !$thucHien)
                                            @php
                                                $now = Carbon\Carbon::now();
                                                $ngayTangCa = Carbon\Carbon::parse($don->ngay_tang_ca);
                                                $gioBatDau = Carbon\Carbon::parse($don->gio_bat_dau);
                                                $thoiGianBatDau = Carbon\Carbon::parse(
                                                    $ngayTangCa->format('Y-m-d') . ' ' . $gioBatDau->format('H:i:s'),
                                                );
                                                $thoiGianChoPhepSom = $thoiGianBatDau->copy()->subMinutes(30);
                                                $coTheXacNhan = $now->gte($thoiGianChoPhepSom);
                                            @endphp

                                            @if ($coTheXacNhan)
                                                <form action="{{ route('employee.tang-ca.confirm-thuc-hien', $don->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Bạn đã hoàn thành giờ tăng ca này?')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 text-sm"
                                                        title="Xác nhận đã làm tăng ca">
                                                        <i class="fas fa-check-circle"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 text-sm" title="Chưa đến giờ tăng ca">
                                                    <i class="fas fa-clock"></i>
                                                </span>
                                            @endif
                                        @endif

                                        @if ($don->trang_thai == 'cho_duyet')
                                            <a href="{{ route('employee.tang-ca.edit', $don->id) }}"
                                                class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300 text-sm"
                                                title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif

                                        @if ($don->trang_thai == 'cho_duyet')
                                            <form action="{{ route('employee.tang-ca.huy', $don->id) }}" method="POST"
                                                onsubmit="return confirm('Bạn có chắc muốn hủy đơn này?')">
                                                @csrf
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm"
                                                    title="Hủy đơn">
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
                                    Chưa có đơn xin tăng ca nào
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($donTangCa->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $donTangCa->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection