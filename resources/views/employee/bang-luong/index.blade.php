@extends('layouts.employee')

@section('title', 'Bảng lương của tôi')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            Bảng lương của tôi
        </h1>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Tổng hợp bảng lương theo từng năm
        </p>
    </div>

    {{-- TABLE --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">

        <div class="overflow-x-auto">
            <table class="w-full">

                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">
                            Năm
                        </th>

                        <th class="px-4 py-3 text-center text-gray-700 dark:text-gray-200">
                            Số tháng
                        </th>

                        <th class="px-4 py-3 text-right text-gray-700 dark:text-gray-200">
                            Tổng lương
                        </th>

                        <th class="px-4 py-3 text-right text-gray-700 dark:text-gray-200">
                            Tổng khấu trừ
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
                    @forelse($years as $year)

                        <tr class="border-t border-gray-200 dark:border-gray-700
                                   hover:bg-gray-50 dark:hover:bg-gray-700/50">

                            <td class="px-4 py-4 font-bold text-blue-600">
                                {{ $year->luong_nam }}
                            </td>

                            <td class="px-4 py-4 text-center text-gray-800 dark:text-gray-200">
                                {{ $year->so_thang }} tháng
                            </td>

                            <td class="px-4 py-4 text-right font-semibold text-gray-800 dark:text-white">
                                {{ number_format($year->tong_luong) }} đ
                            </td>

                            <td class="px-4 py-4 text-right font-semibold text-red-600">
                                {{ number_format($year->tong_khau_tru) }} đ
                            </td>

                            <td class="px-4 py-4 text-right font-bold text-green-600">
                                {{ number_format($year->tong_thuc_nhan) }} đ
                            </td>

                            <td class="px-4 py-4 text-center">
                                <a
                                    href="{{ route('employee.bang-luong.year', $year->luong_nam) }}"
                                    class="inline-flex items-center gap-2 px-4 py-2
                                           text-sm font-medium text-blue-600
                                           hover:bg-blue-50
                                           dark:hover:bg-gray-700
                                           rounded-lg transition"
                                >
                                    Xem chi tiết
                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6"
                                class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">

                                Chưa có dữ liệu bảng lương

                            </td>
                        </tr>

                    @endforelse
                </tbody>

            </table>
        </div>

    </div>

</div>

@endsection