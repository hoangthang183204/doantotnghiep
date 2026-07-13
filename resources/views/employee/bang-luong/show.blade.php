
@extends('layouts.employee')

@section('title', 'Chi tiết phiếu lương')

@section('content')

<div class="mx-auto max-w-7xl space-y-6 pb-10">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 shadow-xl">

        {{-- DECORATION --}}
        <div class="absolute -right-20 -top-20 h-72 w-72 rounded-full bg-blue-500/20 blur-3xl"></div>
        <div class="absolute -bottom-24 left-1/3 h-64 w-64 rounded-full bg-indigo-500/20 blur-3xl"></div>

        <div class="relative px-6 py-7 sm:px-8 lg:px-10 lg:py-9">

            {{-- NÚT TRỞ VỀ --}}
            <div class="mb-7">
                <a href="{{ url()->previous() }}"
                   class="group inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/10 px-4 py-2.5 text-sm font-medium text-slate-200 backdrop-blur-sm transition-all duration-200 hover:border-white/20 hover:bg-white/20 hover:text-white">

                    <svg class="h-4 w-4 transition-transform duration-200 group-hover:-translate-x-1"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M15 19l-7-7 7-7"/>
                    </svg>

                    <span>Trở về</span>
                </a>
            </div>

            <div class="flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">

                {{-- LEFT --}}
                <div>
                    <div class="mb-4 flex flex-wrap items-center gap-3">
                        <span class="inline-flex items-center rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-blue-200">
                            Phiếu lương nhân viên
                        </span>

                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-400/10 px-3 py-1 text-xs font-medium text-emerald-300">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                            Đã hoàn tất
                        </span>
                    </div>

                    <h1 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">
                        Phiếu lương tháng
                        {{ str_pad($payroll->luong_thang, 2, '0', STR_PAD_LEFT) }}/{{ $payroll->luong_nam }}
                    </h1>

                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-400">
                        Báo cáo chi tiết thu nhập, dữ liệu chấm công và các khoản
                        khấu trừ trong kỳ lương.
                    </p>
                </div>

                {{-- RIGHT --}}
                <div class="min-w-[280px] rounded-2xl border border-white/10 bg-white/10 p-5 backdrop-blur-sm">

                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">
                        Lương thực nhận
                    </p>

                    <div class="mt-2 flex items-end gap-2">
                        <span class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                            {{ number_format($payroll->luong_thuc_nhan) }}
                        </span>

                        <span class="pb-1 text-sm font-semibold text-blue-300">
                            VNĐ
                        </span>
                    </div>

                    <div class="mt-4 border-t border-white/10 pt-4">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-400">Kỳ lương</span>

                            <span class="font-medium text-white">
                                {{ str_pad($payroll->luong_thang, 2, '0', STR_PAD_LEFT) }}/{{ $payroll->luong_nam }}
                            </span>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>


    {{-- =========================================================
        TỔNG QUAN TÀI CHÍNH
    ========================================================== --}}
    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

        {{-- LƯƠNG CƠ BẢN --}}
        <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg dark:border-slate-700 dark:bg-slate-800">

            <div class="absolute right-0 top-0 h-24 w-24 rounded-bl-full bg-blue-50 dark:bg-blue-900/10"></div>

            <div class="relative">
                <div class="mb-6 flex items-start justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 10v2m9-6a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>

                    <span class="text-xs font-medium text-slate-400">Cơ sở</span>
                </div>

                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                    Lương cơ bản
                </p>

                <p class="mt-2 text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                    {{ number_format($payroll->luong_co_ban) }}
                    <span class="text-sm font-semibold text-slate-400">VNĐ</span>
                </p>
            </div>
        </div>


        {{-- TỔNG LƯƠNG --}}
        <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg dark:border-slate-700 dark:bg-slate-800">

            <div class="absolute right-0 top-0 h-24 w-24 rounded-bl-full bg-indigo-50 dark:bg-indigo-900/10"></div>

            <div class="relative">
                <div class="mb-6 flex items-start justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>

                    <span class="text-xs font-medium text-indigo-500">Thu nhập</span>
                </div>

                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                    Tổng lương
                </p>

                <p class="mt-2 text-2xl font-bold tracking-tight text-indigo-600 dark:text-indigo-400">
                    {{ number_format($payroll->tong_luong) }}
                    <span class="text-sm font-semibold text-slate-400">VNĐ</span>
                </p>
            </div>
        </div>


        {{-- KHẤU TRỪ --}}
        <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg dark:border-slate-700 dark:bg-slate-800">

            <div class="absolute right-0 top-0 h-24 w-24 rounded-bl-full bg-red-50 dark:bg-red-900/10"></div>

            <div class="relative">
                <div class="mb-6 flex items-start justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                        </svg>
                    </div>

                    <span class="text-xs font-medium text-red-500">Khấu trừ</span>
                </div>

                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                    Tổng khấu trừ
                </p>

                <p class="mt-2 text-2xl font-bold tracking-tight text-red-600 dark:text-red-400">
                    -{{ number_format($payroll->tong_khau_tru) }}
                    <span class="text-sm font-semibold text-slate-400">VNĐ</span>
                </p>
            </div>
        </div>

    </div>


    {{-- =========================================================
        CƠ CẤU LƯƠNG
    ========================================================== --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">

        <div class="mb-6">
            <p class="text-xs font-semibold uppercase tracking-wider text-blue-600 dark:text-blue-400">
                Tổng kết kỳ lương
            </p>

            <h2 class="mt-1 text-lg font-bold text-slate-900 dark:text-white">
                Cơ cấu lương thực nhận
            </h2>
        </div>

        <div class="flex flex-col items-stretch gap-3 lg:flex-row lg:items-center">

            <div class="flex-1 rounded-xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-900/30">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Tổng lương
                </p>

                <p class="mt-2 text-xl font-bold text-slate-900 dark:text-white">
                    {{ number_format($payroll->tong_luong) }}
                    <span class="text-xs text-slate-400">VNĐ</span>
                </p>
            </div>

            <div class="flex h-10 w-10 shrink-0 items-center justify-center self-center rounded-full bg-slate-100 text-xl font-medium text-slate-400 dark:bg-slate-700">
                −
            </div>

            <div class="flex-1 rounded-xl border border-red-100 bg-red-50/50 p-5 dark:border-red-900/30 dark:bg-red-900/10">
                <p class="text-xs font-medium uppercase tracking-wide text-red-400">
                    Tổng khấu trừ
                </p>

                <p class="mt-2 text-xl font-bold text-red-600 dark:text-red-400">
                    {{ number_format($payroll->tong_khau_tru) }}
                    <span class="text-xs text-red-400">VNĐ</span>
                </p>
            </div>

            <div class="flex h-10 w-10 shrink-0 items-center justify-center self-center rounded-full bg-slate-100 text-xl font-medium text-slate-400 dark:bg-slate-700">
                =
            </div>

            <div class="flex-1 rounded-xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-900/40 dark:bg-emerald-900/20">
                <p class="text-xs font-medium uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                    Thực nhận
                </p>

                <p class="mt-2 text-xl font-bold text-emerald-700 dark:text-emerald-400">
                    {{ number_format($payroll->luong_thuc_nhan) }}
                    <span class="text-xs text-emerald-500">VNĐ</span>
                </p>
            </div>

        </div>
    </div>


    {{-- =========================================================
        CHẤM CÔNG
    ========================================================== --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">

        <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between dark:border-slate-700">

            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-blue-600 dark:text-blue-400">
                    Attendance
                </p>

                <h2 class="mt-1 text-lg font-bold text-slate-900 dark:text-white">
                    Thông tin chấm công
                </h2>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Tổng hợp dữ liệu làm việc trong kỳ lương.
                </p>
            </div>

            <div class="rounded-xl bg-blue-50 px-4 py-2 text-sm dark:bg-blue-900/20">
                <span class="text-slate-500 dark:text-slate-400">Tỷ lệ công:</span>

                <span class="ml-1 font-bold text-blue-600 dark:text-blue-400">
                    {{ $payroll->so_ngay_cong_chuan > 0
                        ? number_format(($payroll->so_ngay_cong / $payroll->so_ngay_cong_chuan) * 100, 1)
                        : 0 }}%
                </span>
            </div>

        </div>

        <div class="p-6">

            <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">

                <div class="relative overflow-hidden rounded-2xl border border-blue-100 bg-blue-50/50 p-5 dark:border-blue-900/30 dark:bg-blue-900/10">
                    <div class="absolute right-4 top-4 text-4xl font-bold text-blue-100 dark:text-blue-900/30">01</div>

                    <p class="relative text-sm font-medium text-slate-500 dark:text-slate-400">
                        Ngày công thực tế
                    </p>

                    <div class="relative mt-4 flex items-end gap-2">
                        <span class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                            {{ number_format($payroll->so_ngay_cong) }}
                        </span>
                        <span class="pb-1 text-sm text-slate-400">công</span>
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-900/30">
                    <div class="absolute right-4 top-4 text-4xl font-bold text-slate-200 dark:text-slate-700">02</div>

                    <p class="relative text-sm font-medium text-slate-500 dark:text-slate-400">
                        Ngày công chuẩn
                    </p>

                    <div class="relative mt-4 flex items-end gap-2">
                        <span class="text-3xl font-bold text-slate-800 dark:text-white">
                            {{ number_format($payroll->so_ngay_cong_chuan) }}
                        </span>
                        <span class="pb-1 text-sm text-slate-400">công</span>
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-2xl border border-amber-100 bg-amber-50/50 p-5 dark:border-amber-900/30 dark:bg-amber-900/10">
                    <div class="absolute right-4 top-4 text-4xl font-bold text-amber-100 dark:text-amber-900/30">03</div>

                    <p class="relative text-sm font-medium text-slate-500 dark:text-slate-400">
                        Nghỉ phép
                    </p>

                    <div class="relative mt-4 flex items-end gap-2">
                        <span class="text-3xl font-bold text-amber-600">
                            {{ number_format($payroll->ngay_nghi_phep) }}
                        </span>
                        <span class="pb-1 text-sm text-slate-400">ngày</span>
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-2xl border border-red-100 bg-red-50/50 p-5 dark:border-red-900/30 dark:bg-red-900/10">
                    <div class="absolute right-4 top-4 text-4xl font-bold text-red-100 dark:text-red-900/30">04</div>

                    <p class="relative text-sm font-medium text-slate-500 dark:text-slate-400">
                        Nghỉ không phép
                    </p>

                    <div class="relative mt-4 flex items-end gap-2">
                        <span class="text-3xl font-bold text-red-600 dark:text-red-400">
                            {{ number_format($payroll->ngay_nghi_khong_phep) }}
                        </span>
                        <span class="pb-1 text-sm text-slate-400">ngày</span>
                    </div>
                </div>

            </div>


            {{-- TĂNG CA --}}
            @if($payroll->gio_tang_ca > 0 || $payroll->cong_tang_ca > 0)

                <div class="mt-5 rounded-2xl border border-violet-100 bg-violet-50/50 p-5 dark:border-violet-900/30 dark:bg-violet-900/10">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                        <div>
                            <p class="font-semibold text-slate-900 dark:text-white">
                                Thông tin tăng ca
                            </p>

                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                Tổng hợp thời gian làm việc ngoài giờ.
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-3">

                            @if($payroll->gio_tang_ca > 0)
                                <div class="rounded-xl bg-white px-5 py-3 shadow-sm dark:bg-slate-800">
                                    <p class="text-xs text-slate-400">Số giờ tăng ca</p>

                                    <p class="mt-1 font-bold text-violet-600 dark:text-violet-400">
                                        {{ number_format($payroll->gio_tang_ca) }} giờ
                                    </p>
                                </div>
                            @endif

                            @if($payroll->cong_tang_ca > 0)
                                <div class="rounded-xl bg-white px-5 py-3 shadow-sm dark:bg-slate-800">
                                    <p class="text-xs text-slate-400">Công quy đổi</p>

                                    <p class="mt-1 font-bold text-violet-600 dark:text-violet-400">
                                        {{ number_format($payroll->cong_tang_ca, 2) }} công
                                    </p>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

            @endif

        </div>
    </div>


    {{-- =========================================================
        CHI TIẾT KHẤU TRỪ
    ========================================================== --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">

        <div class="flex flex-col gap-4 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between dark:border-slate-700">

            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-red-500">
                    Deductions
                </p>

                <h2 class="mt-1 text-lg font-bold text-slate-900 dark:text-white">
                    Chi tiết các khoản khấu trừ
                </h2>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Danh sách các khoản được khấu trừ trong kỳ lương.
                </p>
            </div>

            <div class="rounded-xl bg-red-50 px-4 py-3 text-right dark:bg-red-900/20">
                <p class="text-xs text-red-500">Tổng khấu trừ</p>

                <p class="mt-1 font-bold text-red-600 dark:text-red-400">
                    {{ number_format($payroll->tong_khau_tru) }} VNĐ
                </p>
            </div>

        </div>


        @if($payroll->khauTrus->isEmpty())

            <div class="px-6 py-16 text-center">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <h3 class="mt-5 font-semibold text-slate-900 dark:text-white">
                    Không có khoản khấu trừ
                </h3>

                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500 dark:text-slate-400">
                    Kỳ lương này không phát sinh bất kỳ khoản khấu trừ nào.
                </p>

            </div>

        @else

            <div class="overflow-x-auto">
                <table class="w-full text-sm">

                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-900/30">

                            <th class="w-16 px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                STT
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Loại khấu trừ
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Ghi chú
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Số tiền
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">

                        @foreach($payroll->khauTrus as $index => $kt)

                            <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-700/30">

                                <td class="px-6 py-5 text-center">
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-xs font-semibold text-slate-500 dark:bg-slate-700 dark:text-slate-300">
                                        {{ $index + 1 }}
                                    </span>
                                </td>

                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">

                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500 dark:bg-red-900/20">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M20 12H4"/>
                                            </svg>
                                        </div>

                                        <span class="font-semibold text-slate-900 dark:text-white">
                                            {{ $kt->getLoaiKhauTruVietNamAttribute() }}
                                        </span>

                                    </div>
                                </td>

                                <td class="px-6 py-5 text-slate-500 dark:text-slate-400">
                                    {{ $kt->ghi_chu ?? 'Không có ghi chú' }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-5 text-right">
                                    <span class="font-bold text-red-600 dark:text-red-400">
                                        -{{ number_format($kt->so_tien) }} VNĐ
                                    </span>
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                    <tfoot>
                        <tr class="border-t-2 border-slate-200 bg-slate-50 dark:border-slate-600 dark:bg-slate-900/30">

                            <td colspan="3" class="px-6 py-5">
                                <p class="font-bold text-slate-900 dark:text-white">
                                    Tổng cộng
                                </p>

                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                    Tổng tất cả các khoản khấu trừ
                                </p>
                            </td>

                            <td class="whitespace-nowrap px-6 py-5 text-right">
                                <p class="text-lg font-bold text-red-600 dark:text-red-400">
                                    -{{ number_format($payroll->khauTrus->sum('so_tien')) }} VNĐ
                                </p>
                            </td>

                        </tr>
                    </tfoot>

                </table>
            </div>

        @endif

    </div>


    {{-- =========================================================
        FINAL SUMMARY
    ========================================================== --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 p-6 shadow-lg sm:p-8">

        <div class="absolute -right-12 -top-12 h-40 w-40 rounded-full border-[30px] border-white/5"></div>

        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

            <div>
                <p class="text-sm font-medium text-emerald-100">
                    Tổng số tiền được nhận trong kỳ
                </p>

                <p class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                    {{ number_format($payroll->luong_thuc_nhan) }}

                    <span class="text-lg font-semibold text-emerald-100">
                        VNĐ
                    </span>
                </p>

                <p class="mt-3 text-sm text-emerald-100/80">
                    Sau khi đã áp dụng toàn bộ các khoản khấu trừ.
                </p>
            </div>

            <div class="rounded-2xl border border-white/20 bg-white/10 px-6 py-4 backdrop-blur-sm">

                <div class="flex items-center gap-4">

                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white/20">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs text-emerald-100">
                            Trạng thái phiếu lương
                        </p>

                        <p class="mt-1 font-semibold text-white">
                            Đã hoàn tất tính toán
                        </p>
                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection

