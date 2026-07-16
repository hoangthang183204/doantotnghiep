@extends('layouts.admin')

@section('title', 'Tổng lương theo năm')

@section('content')
<div class="min-h-screen p-6 bg-gray-50 dark:bg-slate-900">
<div class="max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tổng lương theo năm</h1>
            <p class="text-gray-500 dark:text-slate-400 mt-1">
                Tổng hợp tổng lương, thuế TNCN, bảo hiểm và thực nhận của toàn công ty theo từng năm.
                Bấm vào một năm để xem chi tiết 12 tháng.
            </p>
        </div>
        <a href="{{ route('admin.thong-ke-luong.index') }}"
           class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-200 rounded-lg text-sm shadow-sm hover:bg-gray-50">
            <i class="fa-solid fa-chart-column mr-1"></i> Thống kê theo phòng ban
        </a>
    </div>

    @include('layouts.partials.alerts')

    {{-- BỘ LỌC THEO NĂM --}}
    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl p-4">
        <form method="GET" action="{{ route('admin.tong-luong.index') }}" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-sm text-gray-500 dark:text-slate-400 mb-1">
                    <i class="fa-solid fa-filter mr-1"></i> Lọc theo năm
                </label>
                <select name="nam" onchange="this.form.submit()"
                        class="px-3 py-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-200 rounded-lg text-sm min-w-[180px]">
                    <option value="">Tất cả các năm</option>
                    @foreach($namList as $n)
                        <option value="{{ $n }}" {{ (string) $namChon === (string) $n ? 'selected' : '' }}>Năm {{ $n }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm shadow-sm">
                <i class="fa-solid fa-magnifying-glass mr-1"></i> Lọc
            </button>
            @if($namChon)
                <a href="{{ route('admin.tong-luong.index') }}"
                   class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-600 dark:text-slate-300 rounded-lg text-sm hover:bg-gray-50">
                    <i class="fa-solid fa-xmark mr-1"></i> Xóa lọc
                </a>
                <span class="ml-auto self-center text-sm text-gray-500 dark:text-slate-400">
                    Đang xem: <b class="text-gray-800 dark:text-white">Năm {{ $namChon }}</b>
                </span>
            @endif
        </form>
    </div>

    {{-- SUMMARY CARDS (TỔNG TẤT CẢ CÁC NĂM) --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400">Tổng lương (gross)</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($tongLuong) }} đ</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400">Tổng bảo hiểm (BHXH+BHYT+BHTN)</p>
            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ number_format($tongBaoHiem) }} đ</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400">Tổng thuế TNCN đã đóng</p>
            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($tongThue) }} đ</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400">Tổng thực nhận (net)</p>
            <p class="text-2xl font-bold text-blue-600 dark:text-sky-400">{{ number_format($tongThucNhan) }} đ</p>
        </div>
    </div>

    @if($rows->isEmpty())
        <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl p-10 text-center text-gray-500 dark:text-slate-400">
            <i class="fa-regular fa-calendar text-3xl mb-2"></i><br>
            Chưa có dữ liệu lương. Hãy tính (chốt) bảng lương trước ở mục <b>Bảng lương</b>.
        </div>
    @else
        {{-- TABLE --}}
        <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm whitespace-nowrap">
                    <thead class="bg-gray-50 dark:bg-slate-900 text-gray-500 dark:text-slate-400 text-left">
                        <tr>
                            <th class="p-3">Năm</th>
                            <th class="p-3 text-center">Số NV</th>
                            <th class="p-3 text-right">Tổng lương</th>
                            <th class="p-3 text-right">Bảo hiểm</th>
                            <th class="p-3 text-right">Thuế TNCN</th>
                            <th class="p-3 text-right">Khấu trừ</th>
                            <th class="p-3 text-right">Thực nhận</th>
                            <th class="p-3 text-center">Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @foreach($rows as $r)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/40 transition cursor-pointer"
                            onclick="window.location='{{ route('admin.tong-luong.chi-tiet', $r->nam) }}'">
                            <td class="p-3 font-bold text-gray-900 dark:text-white">
                                <i class="fa-regular fa-calendar-days text-blue-500 mr-1"></i> {{ $r->nam }}
                            </td>
                            <td class="p-3 text-center text-gray-600 dark:text-slate-300">{{ $r->so_nhan_vien }}</td>
                            <td class="p-3 text-right font-semibold text-gray-900 dark:text-white">{{ number_format($r->tong_luong) }}</td>
                            <td class="p-3 text-right text-amber-600 dark:text-amber-400">{{ number_format($r->tong_bao_hiem) }}</td>
                            <td class="p-3 text-right text-red-500">{{ number_format($r->tong_thue) }}</td>
                            <td class="p-3 text-right text-gray-500 dark:text-slate-400">-{{ number_format($r->tong_khau_tru) }}</td>
                            <td class="p-3 text-right font-bold text-blue-600 dark:text-sky-400">{{ number_format($r->tong_thuc_nhan) }}</td>
                            <td class="p-3 text-center">
                                <a href="{{ route('admin.tong-luong.chi-tiet', $r->nam) }}"
                                   onclick="event.stopPropagation()"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 dark:bg-sky-900/30 text-blue-600 dark:text-sky-400 rounded-lg text-xs font-medium hover:bg-blue-100">
                                    Xem chi tiết <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    {{-- <tfoot class="bg-gray-50 dark:bg-slate-900 border-t border-gray-200 dark:border-slate-700 font-semibold">
                        <tr>
                            <td class="p-3 text-gray-700 dark:text-slate-300">TỔNG CỘNG</td>
                            <td class="p-3 text-center text-gray-700 dark:text-slate-300">—</td>
                            <td class="p-3 text-right text-gray-900 dark:text-white">{{ number_format($tongLuong) }}</td>
                            <td class="p-3 text-right text-amber-600 dark:text-amber-400">{{ number_format($tongBaoHiem) }}</td>
                            <td class="p-3 text-right text-red-500">{{ number_format($tongThue) }}</td>
                            <td class="p-3 text-right text-gray-500 dark:text-slate-400">-{{ number_format($tongKhauTru) }}</td>
                            <td class="p-3 text-right text-blue-600 dark:text-sky-400">{{ number_format($tongThucNhan) }}</td>
                            <td class="p-3"></td>
                        </tr>
                    </tfoot> --}}
                </table>
            </div>
        </div>
    @endif

</div>
</div>
@endsection
