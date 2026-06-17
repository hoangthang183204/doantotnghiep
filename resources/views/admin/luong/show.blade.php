@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto p-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Chi tiết lương nhân viên</h2>
            <p class="text-gray-500">Thông tin bảng lương cá nhân</p>
        </div>

        <a href="{{ route('admin.luong.index') }}"
           class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
            ← Quay lại
        </a>
    </div>

    {{-- CARD --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- THÔNG TIN CƠ BẢN --}}
        <div class="bg-white shadow rounded-xl p-5">
            <h3 class="text-lg font-semibold mb-4">Thông tin lương</h3>

            <div class="space-y-3 text-gray-700">

                <p><b>Lương cơ bản:</b>
                    <span class="text-blue-600">
                        {{ number_format($luong->luong_co_ban) }} VNĐ
                    </span>
                </p>

                <p><b>Phụ cấp:</b>
                    <span class="text-green-600">
                        {{ number_format($luong->phu_cap) }} VNĐ
                    </span>
                </p>

                <p><b>Thưởng:</b>
                    <span class="text-yellow-600">
                        {{ number_format($luong->tien_thuong) }} VNĐ
                    </span>
                </p>

                <p><b>Phạt:</b>
                    <span class="text-red-600">
                        {{ number_format($luong->tien_phat) }} VNĐ
                    </span>
                </p>
            </div>
        </div>

        {{-- TỔNG LƯƠNG --}}
        <div class="bg-gradient-to-r from-green-500 to-green-700 text-white shadow rounded-xl p-5">
            <h3 class="text-lg font-semibold mb-4">Thực nhận</h3>

            @php
                $thuNhap =
                    $luong->luong_co_ban +
                    $luong->phu_cap +
                    $luong->tien_thuong -
                    $luong->tien_phat;
            @endphp

            <div class="text-3xl font-bold">
                {{ number_format($thuNhap) }} VNĐ
            </div>

            <p class="mt-2 text-sm opacity-90">
                Đã bao gồm phụ cấp + thưởng - phạt
            </p>
        </div>

    </div>

    {{-- THÔNG TIN PHỤ --}}
    <div class="mt-6 bg-white shadow rounded-xl p-5">

        <h3 class="text-lg font-semibold mb-4">Thông tin liên quan</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">

            <p>
                <b>Nhân viên:</b>
                {{ $luong->nguoiDung->name ?? '---' }}
            </p>

            <p>
                <b>Hợp đồng:</b>
                {{ $luong->hopDong->ma_hop_dong ?? '---' }}
            </p>

            <p>
                <b>Ngày bắt đầu:</b>
                {{ $luong->hopDong->ngay_bat_dau ?? '---' }}
            </p>

        </div>

    </div>

</div>
@endsection