@extends('layouts.admin')

@section('title', 'Chi tiết hồ sơ nhân viên')

@section('content')

<div class="space-y-6">

    <div class="bg-white rounded-xl shadow-sm p-6">

        {{-- HEADER --}}
        <div class="flex items-center justify-between mb-6">

            <div>
                <h1 class="text-xl font-bold text-gray-800">
                    Chi tiết hồ sơ nhân viên
                </h1>
                <p class="text-sm text-gray-500">
                    Thông tin chi tiết của nhân sự trong hệ thống
                </p>
            </div>

            <a href="{{ route('admin.ho-so.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                ← Quay lại
            </a>

        </div>

        <div class="flex gap-8">

            {{-- AVATAR --}}
            <div class="w-1/4 flex flex-col items-center">

                <div class="w-40 h-40 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 text-6xl shadow">
                    👤
                </div>

                <div class="mt-4 text-center">
                    <div class="font-semibold text-lg text-gray-800">
                        {{ $hoSo->ho }} {{ $hoSo->ten }}
                    </div>

                    <div class="text-sm text-gray-500">
                        {{ $hoSo->ma_nhan_vien }}
                    </div>
                </div>

            </div>

            {{-- INFO --}}
            <div class="w-3/4 grid grid-cols-2 gap-5">

                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm text-gray-500">Email công ty</div>
                    <div class="font-medium text-gray-800">
                        {{ $hoSo->email_cong_ty ?? '---' }}
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm text-gray-500">Số điện thoại</div>
                    <div class="font-medium text-gray-800">
                        {{ $hoSo->so_dien_thoai ?? '---' }}
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm text-gray-500">Ngày sinh</div>
                    <div class="font-medium text-gray-800">
                        {{ $hoSo->ngay_sinh ?? '---' }}
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm text-gray-500">Giới tính</div>
                    <div class="font-medium text-gray-800">
                        {{ ucfirst($hoSo->gioi_tinh ?? '---') }}
                    </div>
                </div>

                {{-- FIXED FIELD --}}
                <div class="col-span-2 bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm text-gray-500">Địa chỉ</div>
                    <div class="font-medium text-gray-800">
                        {{ $hoSo->dia_chi_hien_tai ?? '---' }}
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection