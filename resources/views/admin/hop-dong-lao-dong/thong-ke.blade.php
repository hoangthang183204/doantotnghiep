@extends('layouts.admin')

@section('title', 'Thống kê hợp đồng')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
        <div class="px-6 py-5">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-blue-50 dark:bg-blue-900/20
                                flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>

                    <div>
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                            Thống kê hợp đồng
                        </h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Theo dõi tình trạng và thời hạn hợp đồng lao động
                        </p>
                    </div>
                </div>

                <a href="{{ route('admin.hop-dong.index') }}"
                   class="inline-flex items-center justify-center gap-2
                          px-4 py-2.5 rounded-xl
                          bg-gray-100 hover:bg-gray-200
                          dark:bg-gray-700 dark:hover:bg-gray-600
                          text-sm font-medium text-gray-700 dark:text-gray-200
                          transition">

                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>

                    Quay lại danh sách
                </a>
            </div>
        </div>
    </div>


    {{-- BỘ LỌC --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">

        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707L15 12v5l-6 3v-8L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>

                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">
                    Bộ lọc thống kê
                </h2>
            </div>
        </div>

        <div class="p-6">
            <form method="GET"
                  action="{{ route('admin.hop-dong.thong-ke') }}"
                  class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 items-end">

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-2">
                        Từ ngày
                    </label>
                    <input type="date"
                           name="tu_ngay"
                           value="{{ $tuNgay ?? now()->startOfMonth()->format('Y-m-d') }}"
                           class="w-full px-3.5 py-2.5 text-sm rounded-xl
                                  border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700
                                  text-gray-900 dark:text-gray-100
                                  focus:ring-2 focus:ring-blue-500
                                  focus:border-blue-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-2">
                        Đến ngày
                    </label>
                    <input type="date"
                           name="den_ngay"
                           value="{{ $denNgay ?? now()->format('Y-m-d') }}"
                           class="w-full px-3.5 py-2.5 text-sm rounded-xl
                                  border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700
                                  text-gray-900 dark:text-gray-100
                                  focus:ring-2 focus:ring-blue-500
                                  focus:border-blue-500 transition">
                </div>

                <button type="submit"
                        class="w-full px-4 py-2.5
                               bg-blue-600 hover:bg-blue-700
                               text-white text-sm font-medium
                               rounded-xl transition
                               inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Tìm kiếm
                </button>

                <a href="{{ route('admin.hop-dong.thong-ke') }}"
                   class="w-full px-4 py-2.5
                          bg-gray-100 hover:bg-gray-200
                          dark:bg-gray-700 dark:hover:bg-gray-600
                          text-gray-700 dark:text-gray-200
                          text-sm font-medium rounded-xl transition
                          inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Đặt lại
                </a>

            </form>
        </div>
    </div>


    {{-- THỐNG KÊ --}}
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700
                    shadow-sm p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Tổng hợp đồng</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ number_format($tongHopDong) }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700
                    shadow-sm p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Đang hiệu lực</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-2">
                        {{ number_format($hopDongHieuLuc) }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-green-50 dark:bg-green-900/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700
                    shadow-sm p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Chưa hiệu lực</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mt-2">
                        {{ number_format($hopDongChuaHieuLuc) }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-yellow-50 dark:bg-yellow-900/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700
                    shadow-sm p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Tạo mới</p>
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400 mt-2">
                        {{ number_format($hopDongTaoMoi) }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700
                    shadow-sm p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Hết hạn</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-2">
                        {{ number_format($hopDongHetHan) }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-900/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M10.29 3.86l-7.82 13.5A2 2 0 004.2 20h15.6a2 2 0 001.73-2.64l-7.82-13.5a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700
                    shadow-sm p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Đã hủy</p>
                    <p class="text-2xl font-bold text-gray-600 dark:text-gray-400 mt-2">
                        {{ number_format($hopDongHuyBo) }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>


    {{-- HỢP ĐỒNG SẮP HẾT HẠN --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">

        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-900/20
                                flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-orange-600 dark:text-orange-400"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">
                            Hợp đồng sắp hết hạn
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Các hợp đồng sẽ hết hạn trong 30 ngày tới
                        </p>
                    </div>
                </div>
                <span class="inline-flex items-center px-3 py-1.5 rounded-full
                             bg-orange-50 dark:bg-orange-900/20
                             text-orange-700 dark:text-orange-300
                             text-xs font-semibold">
                    {{ $hopDongSapHetHan30Ngay->count() }} hợp đồng
                </span>
            </div>
        </div>

        @if($hopDongSapHetHan30Ngay->count() > 0)

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/40
                                   text-left text-xs font-semibold
                                   text-gray-500 dark:text-gray-400">
                            <th class="px-5 py-3.5 text-center w-14">#</th>
                            <th class="px-5 py-3.5">Số hợp đồng</th>
                            <th class="px-5 py-3.5">Nhân viên</th>
                            <th class="px-5 py-3.5">Chức vụ</th>
                            <th class="px-5 py-3.5">Ngày kết thúc</th>
                            <th class="px-5 py-3.5 text-center">Thời gian còn lại</th>
                            <th class="px-5 py-3.5 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">

                        @foreach($hopDongSapHetHan30Ngay as $index => $hd)

                            @php
                                $ngayHetHan = \Carbon\Carbon::parse($hd->ngay_ket_thuc);
                                
                                // 🔥 SỬA: Tính số ngày còn lại (làm tròn lên)
                                $soNgayConLai = (int) ceil(now()->diffInDays($ngayHetHan, false));
                                
                                // Nếu đã quá hạn thì hiển thị 0 ngày
                                if ($soNgayConLai < 0) {
                                    $soNgayConLai = 0;
                                }

                                $tenNhanVien = ($hd->hoSoNguoiDung
                                    ? trim($hd->hoSoNguoiDung->ho . ' ' . $hd->hoSoNguoiDung->ten)
                                    : '') ?: 'N/A';

                                if ($soNgayConLai <= 3) {
                                    $badgeColor = 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300';
                                    $badgeText = $soNgayConLai . ' ngày ⚠️';
                                    $rowBg = 'bg-red-50/40 dark:bg-red-900/5';
                                } elseif ($soNgayConLai <= 10) {
                                    $badgeColor = 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300';
                                    $badgeText = $soNgayConLai . ' ngày';
                                    $rowBg = 'bg-yellow-50/30 dark:bg-yellow-900/5';
                                } else {
                                    $badgeColor = 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300';
                                    $badgeText = $soNgayConLai . ' ngày';
                                    $rowBg = '';
                                }
                            @endphp

                            <tr class="{{ $rowBg }} hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td class="px-5 py-4 text-center text-gray-400">{{ $index + 1 }}</td>
                                <td class="px-5 py-4">
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $hd->so_hop_dong }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full
                                                    bg-blue-100 dark:bg-blue-900/30
                                                    flex items-center justify-center
                                                    text-xs font-semibold
                                                    text-blue-600 dark:text-blue-400">
                                            {{ mb_strtoupper(mb_substr($tenNhanVien, 0, 1)) }}
                                        </div>
                                        <span class="text-gray-700 dark:text-gray-300">{{ $tenNhanVien }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-gray-500 dark:text-gray-400">
                                    {{ $hd->chucVu->ten ?? 'N/A' }}
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-gray-700 dark:text-gray-300">{{ $ngayHetHan->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center justify-center
                                                 min-w-[72px] px-3 py-1.5 rounded-lg
                                                 text-xs font-semibold
                                                 {{ $badgeColor }}">
                                        {{ $badgeText }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.hop-dong.show', $hd->id) }}"
                                           class="inline-flex items-center gap-1.5
                                                  px-3 py-1.5 text-xs font-medium
                                                  bg-blue-50 hover:bg-blue-100
                                                  dark:bg-blue-900/20 dark:hover:bg-blue-900/30
                                                  text-blue-600 dark:text-blue-400
                                                  rounded-lg transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Xem
                                        </a>

                                        @if($soNgayConLai <= 3)
                                            <a href="{{ route('admin.hop-dong.tai-ky', $hd->id) }}"
                                               onclick="return confirm('Tái ký hợp đồng {{ $hd->so_hop_dong }}?')"
                                               class="inline-flex items-center gap-1.5
                                                      px-3 py-1.5 text-xs font-medium
                                                      bg-orange-500 hover:bg-orange-600
                                                      text-white rounded-lg transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                                Tái ký
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                        @endforeach

                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-700">
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-xs text-gray-500 dark:text-gray-400">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                        <span>≤ 3 ngày - Cần tái ký ngay</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span>
                        <span>4 - 10 ngày - Sắp đến hạn</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        <span>> 10 ngày - Còn thời gian</span>
                    </div>
                </div>
            </div>

        @else

            <div class="py-16 px-6 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-green-50 dark:bg-green-900/20 flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Không có hợp đồng sắp hết hạn</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Hiện không có hợp đồng nào hết hạn trong 30 ngày tới.
                </p>
            </div>

        @endif

    </div>

</div>
@endsection