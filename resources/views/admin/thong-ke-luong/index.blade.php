@extends('layouts.admin')

@section('title', 'Thống kê quỹ lương')

@section('content')
<div class="min-h-screen p-6 bg-gray-50 dark:bg-slate-900">
<div class="max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Thống kê quỹ lương theo phòng ban</h1>
            <p class="text-gray-500 dark:text-slate-400 mt-1">Kỳ lương tháng {{ $thang }}/{{ $nam }}</p>
        </div>
        <div class="flex items-center gap-2">
            <form method="GET" class="flex items-end gap-2">
                <div>
                    <label class="block text-xs text-gray-500 dark:text-slate-400 mb-1">Tháng</label>
                    <select name="thang" class="rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white text-sm">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" @selected($m == $thang)>Tháng {{ $m }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 dark:text-slate-400 mb-1">Năm</label>
                    <input type="number" name="nam" value="{{ $nam }}" min="2000" max="2100"
                           class="w-24 rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white text-sm">
                </div>
                <button class="px-4 py-2 bg-gray-800 dark:bg-slate-700 text-white rounded-lg text-sm">
                    <i class="fa-solid fa-magnifying-glass mr-1"></i> Xem
                </button>
            </form>
            <a href="{{ route('admin.thong-ke-luong.pdf', ['thang' => $thang, 'nam' => $nam]) }}"
               class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm shadow-sm">
                <i class="fa-solid fa-file-pdf mr-1"></i> Xuất PDF
            </a>
        </div>
    </div>

    @include('layouts.partials.alerts')

    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400">Tổng quỹ lương (gross)</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($tongQuyLuong) }} đ</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400">Tổng thực chi (net)</p>
            <p class="text-2xl font-bold text-blue-600 dark:text-sky-400">{{ number_format($tongThucNhan) }} đ</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400">Số nhân viên / phòng ban</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $tongNhanVien }} / {{ $soPhongBan }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400">Lương TB / người</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($luongTbNv) }} đ</p>
        </div>
    </div>

    @if($rows->isEmpty())
        <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl p-10 text-center text-gray-500 dark:text-slate-400">
            <i class="fa-regular fa-chart-bar text-3xl mb-2"></i><br>
            Chưa có dữ liệu lương cho tháng {{ $thang }}/{{ $nam }}. Hãy tính (chốt) lương trước.
        </div>
    @else
        {{-- BAR CHART (CSS) --}}
        <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm p-6">
            <h2 class="font-semibold text-gray-900 dark:text-white mb-4">
                <i class="fa-solid fa-chart-simple text-blue-500 mr-2"></i>Tỷ trọng quỹ lương thực chi theo phòng ban
            </h2>
            <div class="space-y-3">
                @foreach($rows as $r)
                    @php $pct = $tongThucNhan > 0 ? round($r->tong_thuc_nhan / $tongThucNhan * 100, 1) : 0; @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-700 dark:text-slate-300 font-medium">{{ $r->ten_phong_ban }}
                                <span class="text-gray-400">({{ $r->so_nhan_vien }} NV)</span>
                            </span>
                            <span class="text-gray-900 dark:text-white font-semibold">{{ number_format($r->tong_thuc_nhan) }} đ • {{ $pct }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-3">
                            <div class="bg-blue-600 dark:bg-sky-500 h-3 rounded-full" style="width: {{ max($pct, 1) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- TABLE --}}
        <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm whitespace-nowrap">
                    <thead class="bg-gray-50 dark:bg-slate-900 text-gray-500 dark:text-slate-400 text-left">
                        <tr>
                            <th class="p-3">Phòng ban</th>
                            <th class="p-3 text-center">Số NV</th>
                            <th class="p-3 text-right">Phụ cấp</th>
                            <th class="p-3 text-right">Tăng ca</th>
                            <th class="p-3 text-right">Tổng lương</th>
                            <th class="p-3 text-right">Khấu trừ</th>
                            <th class="p-3 text-right">Thực chi</th>
                            <th class="p-3 text-right">TB/người</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @foreach($rows as $r)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/40 transition">
                            <td class="p-3 font-medium text-gray-900 dark:text-white">{{ $r->ten_phong_ban }}</td>
                            <td class="p-3 text-center text-gray-600 dark:text-slate-300">{{ $r->so_nhan_vien }}</td>
                            <td class="p-3 text-right text-green-600 dark:text-green-400">{{ number_format($r->tong_phu_cap) }}</td>
                            <td class="p-3 text-right text-indigo-600 dark:text-indigo-400">{{ number_format($r->tong_tang_ca) }}</td>
                            <td class="p-3 text-right font-semibold text-gray-900 dark:text-white">{{ number_format($r->tong_luong) }}</td>
                            <td class="p-3 text-right text-red-500">-{{ number_format($r->tong_khau_tru) }}</td>
                            <td class="p-3 text-right font-bold text-blue-600 dark:text-sky-400">{{ number_format($r->tong_thuc_nhan) }}</td>
                            <td class="p-3 text-right text-gray-600 dark:text-slate-300">
                                {{ number_format($r->so_nhan_vien > 0 ? $r->tong_thuc_nhan / $r->so_nhan_vien : 0) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-slate-900 border-t border-gray-200 dark:border-slate-700 font-semibold">
                        <tr>
                            <td class="p-3 text-gray-700 dark:text-slate-300">TỔNG CỘNG</td>
                            <td class="p-3 text-center text-gray-700 dark:text-slate-300">{{ $tongNhanVien }}</td>
                            <td class="p-3 text-right text-green-600 dark:text-green-400">{{ number_format($rows->sum('tong_phu_cap')) }}</td>
                            <td class="p-3 text-right text-indigo-600 dark:text-indigo-400">{{ number_format($rows->sum('tong_tang_ca')) }}</td>
                            <td class="p-3 text-right text-gray-900 dark:text-white">{{ number_format($tongQuyLuong) }}</td>
                            <td class="p-3 text-right text-red-500">-{{ number_format($tongKhauTru) }}</td>
                            <td class="p-3 text-right text-blue-600 dark:text-sky-400">{{ number_format($tongThucNhan) }}</td>
                            <td class="p-3 text-right text-gray-600 dark:text-slate-300">{{ number_format($luongTbNv) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endif

</div>
</div>
@endsection
