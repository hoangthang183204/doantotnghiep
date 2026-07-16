{{-- resources/views/employee/cham-cong/history.blade.php --}}

@extends('layouts.admin')

@section('title', 'Lịch sử chấm công')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-history mr-3 text-blue-600"></i>
                    Lịch sử chấm công
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Xem lịch sử chấm công của bạn
                </p>
            </div>
            <a href="{{ route('employee.cham-cong.index') }}"
                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition">
                <i class="fas fa-arrow-left mr-1"></i> Quay lại
            </a>
        </div>

        {{-- Bộ lọc tháng/năm --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
            <form method="GET" class="flex flex-wrap items-center gap-3">
                <div>
                    <select name="thang"
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Chọn tháng</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}"
                                {{ request('thang', $thangLoc ?? date('m')) == $i ? 'selected' : '' }}>
                                Tháng {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <select name="nam"
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Chọn năm</option>
                        @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                            <option value="{{ $i }}"
                                {{ request('nam', $namLoc ?? date('Y')) == $i ? 'selected' : '' }}>
                                Năm {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                    <i class="fas fa-search mr-1"></i> Lọc
                </button>
                <a href="{{ route('employee.cham-cong.history') }}"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-redo mr-1"></i> Reset
                </a>
                <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">
                    Hiển thị: Tháng {{ $thangLoc }}/{{ $namLoc }}
                </span>
            </form>
        </div>

        {{-- Thống kê --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                <p class="text-sm text-gray-500 dark:text-gray-400">Tổng ngày</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $thongKe['tong_ngay'] ?? 0 }}</p>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl border border-green-200 dark:border-green-700/50 p-4 shadow-sm">
                <p class="text-sm text-green-600 dark:text-green-400">Đúng giờ</p>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $thongKe['dung_gio'] ?? 0 }}</p>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl border border-yellow-200 dark:border-yellow-700/50 p-4 shadow-sm">
                <p class="text-sm text-yellow-600 dark:text-yellow-400">Đi muộn</p>
                <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $thongKe['di_muon'] ?? 0 }}</p>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl border border-orange-200 dark:border-orange-700/50 p-4 shadow-sm">
                <p class="text-sm text-orange-600 dark:text-orange-400">Về sớm</p>
                <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $thongKe['ve_som'] ?? 0 }}</p>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl border border-blue-200 dark:border-blue-700/50 p-4 shadow-sm col-span-2">
                <p class="text-sm text-blue-600 dark:text-blue-400">Tổng giờ làm</p>
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                    {{ number_format($thongKe['tong_gio_lam'] ?? 0, 1) }}h</p>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl border border-purple-200 dark:border-purple-700/50 p-4 shadow-sm col-span-2">
                <p class="text-sm text-purple-600 dark:text-purple-400">Tổng tăng ca</p>
                <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                    {{ number_format($thongKe['tong_tang_ca'] ?? 0, 1) }}h</p>
            </div>
        </div>

        {{-- Danh sách chi tiết --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">#
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Ngày</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Giờ vào</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Giờ ra</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Số giờ</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Tăng ca</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Trạng thái</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @forelse($lichSu as $index => $item)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $lichSu->firstItem() + $index }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($item->ngay_cham_cong)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                    {{ $item->gio_vao ? \Carbon\Carbon::parse($item->gio_vao)->format('H:i') : '--:--' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                    {{ $item->gio_ra ? \Carbon\Carbon::parse($item->gio_ra)->format('H:i') : '--:--' }}
                                </td>
                                <td class="px-4 py-3 text-center font-medium">
                                    {{ number_format($item->so_gio_lam ?? 0, 1) }}h
                                </td>
                                <td class="px-4 py-3 text-center font-medium text-purple-600">
                                    {{ number_format($item->gio_tang_ca ?? 0, 1) }}h
                                </td>
                                <td class="px-4 py-3">
                                    @if (in_array($item->trang_thai, ['dung_gio', 'den_som']))
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                            {{ $item->trang_thai == 'dung_gio' ? 'Đúng giờ' : 'Đến sớm' }}
                                        </span>
                                    @elseif($item->trang_thai == 'di_muon')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                                            <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5"></span>
                                            Đi muộn
                                        </span>
                                    @elseif($item->trang_thai == 've_som')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                                            <span class="w-1.5 h-1.5 bg-orange-500 rounded-full mr-1.5"></span>
                                            Về sớm
                                        </span>
                                    @elseif($item->trang_thai == 'tang_ca')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                                            <span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-1.5"></span>
                                            Tăng ca
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                            {{ $item->trang_thai ?? 'N/A' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400 max-w-[150px] truncate">
                                    {{ $item->ghi_chu ?? '--' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-calendar-times text-4xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                        <p class="font-medium">Không có dữ liệu chấm công</p>
                                        <p class="text-sm">Bạn chưa có bản ghi chấm công nào trong tháng này</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($lichSu->hasPages())
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    {{ $lichSu->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
