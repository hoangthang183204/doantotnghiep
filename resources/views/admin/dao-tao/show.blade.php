@extends('layouts.admin')

@section('title','Chi tiết khóa đào tạo')

@section('content')

<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                Chi tiết khóa đào tạo
            </h1>

            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Thông tin chi tiết khóa đào tạo của nhân viên
            </p>
        </div>

        <a href="{{ route('admin.dao-tao.index') }}"
            class="px-4 py-2 rounded-lg bg-gray-600 hover:bg-gray-700 text-white">

            Quay lại

        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="text-sm text-gray-500">
                    Mã nhân viên
                </label>

                <div class="mt-1 font-semibold text-gray-800 dark:text-white">
                    {{ $daoTao->hoSo->ma_nhan_vien }}
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-500">
                    Nhân viên
                </label>

                <div class="mt-1 font-semibold text-gray-800 dark:text-white">
                    {{ $daoTao->hoSo->ho_ten }}
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-500">
                    Tên khóa học
                </label>

                <div class="mt-1 font-semibold">
                    {{ $daoTao->ten_khoa_hoc }}
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-500">
                    Đơn vị đào tạo
                </label>

                <div class="mt-1">
                    {{ $daoTao->to_chuc ?: '-' }}
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-500">
                    Ngày bắt đầu
                </label>

                <div class="mt-1">
                    {{ $daoTao->ngay_bat_dau->format('d/m/Y') }}
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-500">
                    Ngày kết thúc
                </label>

                <div class="mt-1">
                    {{ optional($daoTao->ngay_ket_thuc)->format('d/m/Y') ?? '-' }}
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-500">
                    Kết quả
                </label>

                <div class="mt-1">
                    {{ $daoTao->ket_qua ?: '-' }}
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-500">
                    Có chứng chỉ
                </label>

                <div class="mt-1">

                    @if($daoTao->co_chung_chi)

                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">
                            Có
                        </span>

                    @else

                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm">
                            Không
                        </span>

                    @endif

                </div>
            </div>

            <div>
                <label class="text-sm text-gray-500">
                    Chi phí
                </label>

                <div class="mt-1 font-semibold text-blue-600">
                    {{ number_format($daoTao->chi_phi) }} VNĐ
                </div>
            </div>

        </div>

        <hr class="my-6 dark:border-gray-700">

        <div>

            <label class="text-sm text-gray-500">
                Ghi chú
            </label>

            <div class="mt-2 p-4 rounded-lg bg-gray-50 dark:bg-gray-900">

                {{ $daoTao->ghi_chu ?: 'Không có ghi chú.' }}

            </div>

        </div>

    </div>

</div>

@endsection