@extends('layouts.admin')

@section('title', 'Chi tiết bảng lương')

@section('content')

<div class="min-h-screen p-6 bg-gray-50 dark:bg-slate-900">

<div class="max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-start">

        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Chi tiết bảng lương
            </h1>

            <p class="text-gray-500 dark:text-slate-400 mt-1">
                Mã: <span class="font-semibold">{{ $bangLuong->ma_bang_luong }}</span>
                - Tháng {{ $bangLuong->thang }}/{{ $bangLuong->nam }}
            </p>
        </div>

        <div class="flex gap-2">

            @if($bangLuong->trang_thai == 'dang_tao')
            <form action="{{ route('admin.bang-luong.duyet', $bangLuong->id) }}" method="POST">
                @csrf
                @method('PUT')

                <button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-sm">
                    <i class="fa-solid fa-check mr-1"></i> Duyệt
                </button>
            </form>
            @endif

            <a href="{{ route('admin.bang-luong.index') }}"
               class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700
                      text-gray-700 dark:text-slate-300 rounded-lg hover:opacity-80">
                Quay lại
            </a>

        </div>

    </div>

    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400">Tổng nhân viên</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ $bangLuong->luongNhanViens->count() }}
            </p>
        </div>

        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400">Tổng thực lĩnh</p>
            <p class="text-2xl font-bold text-blue-600 dark:text-sky-400">
                {{ number_format($bangLuong->luongNhanViens->sum('luong_thuc_nhan')) }} đ
            </p>
        </div>

        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400">Trạng thái</p>

            <span class="inline-block mt-1 px-3 py-1 text-xs rounded-full
                @if($bangLuong->trang_thai == 'dang_tao') bg-yellow-100 text-yellow-800
                @elseif($bangLuong->trang_thai == 'cho_duyet') bg-blue-100 text-blue-800
                @elseif($bangLuong->trang_thai == 'da_duyet') bg-green-100 text-green-800
                @else bg-gray-100 text-gray-800
                @endif">

                {{ $bangLuong->trang_thai }}
            </span>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 dark:bg-slate-900 text-gray-500 dark:text-slate-400">
                    <tr>
                        <th class="p-4 text-left">STT</th>
                        <th class="p-4 text-left">Họ tên</th>
                        <th class="p-4 text-left">Lương cơ bản</th>
                        <th class="p-4 text-left">Phụ cấp</th>
                        <th class="p-4 text-left">Khấu trừ</th>
                        <th class="p-4 text-left">Thực lĩnh</th>
                        <th class="p-4 text-left">Ngày công</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">

                    @foreach($bangLuong->luongNhanViens as $index => $lnv)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/40 transition">

                        <td class="p-4 text-gray-600 dark:text-slate-300">
                            {{ $index + 1 }}
                        </td>

                        <td class="p-4 font-medium text-gray-900 dark:text-white">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-950
                                            flex items-center justify-center text-blue-600 dark:text-sky-400 text-xs">
                                    <i class="fa-solid fa-user"></i>
                                </div>

                                {{ $lnv->nguoiDung->ho_so->ho ?? '' }}
                                {{ $lnv->nguoiDung->ho_so->ten ?? '' }}
                            </div>
                        </td>

                        <td class="p-4 text-gray-700 dark:text-slate-300">
                            {{ number_format($lnv->luong_co_ban) }} đ
                        </td>

                        <td class="p-4 text-green-600 dark:text-green-400">
                            {{ number_format($lnv->tong_phu_cap) }} đ
                        </td>

                        <td class="p-4 text-red-500">
                            {{ number_format($lnv->tong_khau_tru) }} đ
                        </td>

                        <td class="p-4 font-bold text-blue-600 dark:text-sky-400">
                            {{ number_format($lnv->luong_thuc_nhan) }} đ
                        </td>

                        <td class="p-4 text-gray-600 dark:text-slate-300">
                            {{ $lnv->so_ngay_cong }} / 26
                        </td>

                    </tr>
                    @endforeach

                </tbody>

                <tfoot class="bg-gray-50 dark:bg-slate-900 border-t border-gray-200 dark:border-slate-700">
                    <tr>
                        <td colspan="5" class="p-4 text-right font-semibold text-gray-700 dark:text-slate-300">
                            Tổng cộng
                        </td>

                        <td class="p-4 font-bold text-blue-600 dark:text-sky-400">
                            {{ number_format($bangLuong->luongNhanViens->sum('luong_thuc_nhan')) }} đ
                        </td>

                        <td></td>
                    </tr>
                </tfoot>

            </table>

        </div>

    </div>

</div>

</div>

@endsection