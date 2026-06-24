@extends('layouts.employee')

@section('title', 'Chi tiết phiếu lương')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            Phiếu lương tháng {{ $payroll->luong_thang }}/{{ $payroll->luong_nam }}
        </h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Chi tiết các khoản thu nhập và khấu trừ
        </p>
    </div>

    {{-- THÔNG TIN LƯƠNG --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
        <p>Lương cơ bản: <strong>{{ number_format($payroll->luong_co_ban) }}</strong></p>
        <p>Tổng lương: <strong>{{ number_format($payroll->tong_luong) }}</strong></p>
        <p>Tổng khấu trừ: <strong class="text-red-600">{{ number_format($payroll->tong_khau_tru) }}</strong></p>
        <p>Lương thực nhận: <strong class="text-green-600 text-lg">{{ number_format($payroll->luong_thuc_nhan) }}</strong></p>
    </div>

    {{-- LƯƠNG THƯỞNG --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">
        Thông tin chấm công
    </h3>

    <div class="space-y-3">

        <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700">
            <span class="text-gray-700 dark:text-gray-300">
                Ngày công thực tế
            </span>
            <span class="font-semibold text-blue-600">
                {{ $payroll->so_ngay_cong }} công
            </span>
        </div>

        <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700">
            <span class="text-gray-700 dark:text-gray-300">
                Ngày công chuẩn
            </span>
            <span class="font-medium">
                {{ $payroll->so_ngay_cong_chuan }} công
            </span>
        </div>

        <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700">
            <span class="text-gray-700 dark:text-gray-300">
                Ngày nghỉ phép
            </span>
            <span class="font-medium">
                {{ $payroll->ngay_nghi_phep }} ngày
            </span>
        </div>

        <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700">
            <span class="text-gray-700 dark:text-gray-300">
                Nghỉ không phép
            </span>
            <span class="font-medium text-red-600">
                {{ $payroll->ngay_nghi_khong_phep }} ngày
            </span>
        </div>

        @if($payroll->gio_tang_ca > 0)
        <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700">
            <span class="text-gray-700 dark:text-gray-300">
                Giờ tăng ca
            </span>
            <span class="font-medium">
                {{ $payroll->gio_tang_ca }} giờ
            </span>
        </div>
        @endif

        @if($payroll->cong_tang_ca > 0)
        <div class="flex justify-between items-center">
            <span class="text-gray-700 dark:text-gray-300">
                Công quy đổi từ tăng ca
            </span>
            <span class="font-medium">
                {{ number_format($payroll->cong_tang_ca, 2) }} công
            </span>
        </div>
        @endif

    </div>
</div>

    {{-- CHI TIẾT KHẤU TRỪ --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Chi tiết khấu trừ</h3>
        @if($payroll->khauTrus->isEmpty())
            <p class="text-gray-500 dark:text-gray-400">Không có khấu trừ</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">Loại khấu trừ</th>
                            <th class="px-4 py-2 text-right text-gray-700 dark:text-gray-300">Số tiền</th>
                            <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payroll->khauTrus as $kt)
                            <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-4 py-3">{{ $kt->getLoaiKhauTruVietNamAttribute() }}</td>
                                <td class="px-4 py-3 text-right font-medium">{{ number_format($kt->so_tien) }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $kt->ghi_chu ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-700 font-semibold">
                        <tr>
                            <td class="px-4 py-3">Tổng cộng</td>
                            <td class="px-4 py-3 text-right">{{ number_format($payroll->khauTrus->sum('so_tien')) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>



</div>

@endsection
