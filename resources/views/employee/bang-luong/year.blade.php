@extends('layouts.employee')

@section('title', 'Chi tiết lương năm ' . $year)

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

        <div class="flex items-center justify-between">

            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                    Bảng lương của tôi / Năm {{ $year }}
                </p>

                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Chi tiết lương năm {{ $year }}
                </h1>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Danh sách phiếu lương theo từng tháng
                </p>
            </div>

            <a href="{{ route('employee.bang-luong.index') }}"
               class="px-4 py-2 border border-gray-300 dark:border-gray-600
                      rounded-lg text-gray-700 dark:text-gray-200
                      hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                ← Quay lại
            </a>

        </div>
    </div>

    {{-- THỐNG KÊ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Tổng lương năm {{ $year }}
            </p>

            <p class="mt-2 text-2xl font-bold text-gray-800 dark:text-white">
                {{ number_format($payrolls->sum('tong_luong')) }} đ
            </p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Tổng khấu trừ
            </p>

            <p class="mt-2 text-2xl font-bold text-red-600">
                {{ number_format($payrolls->sum('tong_khau_tru')) }} đ
            </p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Tổng thực nhận
            </p>

            <p class="mt-2 text-2xl font-bold text-green-600">
                {{ number_format($payrolls->sum('luong_thuc_nhan')) }} đ
            </p>
        </div>

    </div>

    {{-- DANH SÁCH THÁNG --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">
                            Mã BL
                        </th>

                        <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">
                            Tháng
                        </th>

                        <th class="px-4 py-3 text-right text-gray-700 dark:text-gray-200">
                            Tổng lương
                        </th>

                        <th class="px-4 py-3 text-right text-gray-700 dark:text-gray-200">
                            Khấu trừ
                        </th>

                        <th class="px-4 py-3 text-right text-gray-700 dark:text-gray-200">
                            Thực nhận
                        </th>

                        <th class="px-4 py-3 text-center text-gray-700 dark:text-gray-200">
                            Thao tác
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($payrolls as $p)

                        <tr class="border-t border-gray-200 dark:border-gray-700
                                   hover:bg-gray-50 dark:hover:bg-gray-700/50">

                            <td class="px-4 py-4 font-medium text-blue-600">
                                {{ $p->bangLuong->ma_bang_luong ?? '-' }}
                            </td>

                            <td class="px-4 py-4 font-semibold text-gray-800 dark:text-white">
                                Tháng {{ $p->luong_thang }}
                            </td>

                            <td class="px-4 py-4 text-right text-gray-800 dark:text-gray-200">
                                {{ number_format($p->tong_luong) }} đ
                            </td>

                            <td class="px-4 py-4 text-right text-red-600">
                                {{ number_format($p->tong_khau_tru) }} đ
                            </td>

                            <td class="px-4 py-4 text-right font-bold text-green-600">
                                {{ number_format($p->luong_thuc_nhan) }} đ
                            </td>

                            <td class="px-4 py-4 text-center">

                                <a href="{{ route('employee.bang-luong.show', $p->id) }}"
                                   title="Xem chi tiết"
                                   class="inline-flex items-center justify-center
                                          w-9 h-9 text-blue-600
                                          hover:bg-blue-50
                                          dark:hover:bg-gray-700
                                          rounded-lg transition">

                                    <svg class="w-5 h-5"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                        </path>

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0
                                                 8.268 2.943 9.542 7-1.274 4.057-5.064
                                                 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>

                                    </svg>

                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection