@extends('layouts.employee')

@section('content')

<div class="container mx-auto px-6 py-6">

    @if(session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700 dark:border-green-700 dark:bg-green-900/30 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700 dark:border-red-700 dark:bg-red-900/30 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">

        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            Yêu cầu ứng lương
        </h2>

        <a href="{{ route('employee.ung-luong.create') }}"
            class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 transition">

            + Gửi yêu cầu

        </a>

    </div>

    <div class="overflow-hidden rounded-xl bg-white shadow dark:bg-gray-800">

        <table class="w-full">

            <thead class="bg-gray-100 dark:bg-gray-700">

                <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Ngày gửi</th>
                    <th class="px-4 py-3 text-right text-gray-700 dark:text-gray-200">Số tiền</th>
                    <th class="px-4 py-3 text-center text-gray-700 dark:text-gray-200">Trạng thái</th>
                </tr>
                </thead>

            </thead>

            <tbody>

                @forelse($danhSach as $item)

                    <tr class="border-t border-gray-200 dark:border-gray-700">

                        <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                            {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}
                        </td>

                        <td class="px-4 py-3 text-right font-semibold text-gray-700 dark:text-gray-200">
                            {{ number_format($item->so_tien) }} đ
                        </td>

                        <td class="px-4 py-3 text-center">

                            @if($item->trang_thai == 'huy')

                                <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-sm font-medium text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300">
                                    Chờ duyệt
                                </span>

                            @elseif($item->trang_thai == 'hieu_luc')

                                <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700 dark:bg-green-900/40 dark:text-green-300">
                                    Đã duyệt
                                </span>

                            @else

                                <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-700 dark:bg-red-900/40 dark:text-red-300">
                                    Đã hủy
                                </span>

                            @endif

                        </td>
                        

                    </tr>

                @empty

                    <tr>

                        <td colspan="3"
                            class="py-10 text-center text-gray-500 dark:text-gray-400">

                            Chưa có yêu cầu ứng lương

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection