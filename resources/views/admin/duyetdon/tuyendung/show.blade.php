@extends('layouts.admin')

@section('content')
    <div class="container p-6 text-gray-900 dark:text-gray-100 transition-colors duration-200">
        <h1 class="mb-4 text-2xl font-semibold text-gray-900 dark:text-gray-100">
            Chi tiết đơn tuyển dụng
        </h1>

        <div class="rounded border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-gray-800">
            <div class="mb-2 text-gray-700 dark:text-gray-300">
                <strong class="text-gray-900 dark:text-gray-100">Mã yêu cầu:</strong>
                {{ $item->ma_yeu_cau ?? $item->id }}
            </div>

            <div class="mb-2 text-gray-700 dark:text-gray-300">
                <strong class="text-gray-900 dark:text-gray-100">Phòng ban:</strong>
                {{ optional($item->phongBan)->ten ?? '-' }}
            </div>

            <div class="mb-2 text-gray-700 dark:text-gray-300">
                <strong class="text-gray-900 dark:text-gray-100">Chức vụ:</strong>
                {{ optional($item->chucVu)->ten ?? $item->chuc_vu }}
            </div>

            <div class="mb-2 text-gray-700 dark:text-gray-300">
                <strong class="text-gray-900 dark:text-gray-100">Số lượng:</strong>
                {{ $item->so_luong ?? '-' }}
            </div>

            <div class="mb-2 text-gray-700 dark:text-gray-300">
                <strong class="text-gray-900 dark:text-gray-100">Mô tả:</strong>
                {!! nl2br(e($item->mo_ta ?? '-')) !!}
            </div>

            <div class="mt-4">
                <a href="{{ route('admin.duyetdon.tuyendung.index') }}"
                    class="rounded border border-gray-300 px-3 py-1 text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                    Quay lại
                </a>
            </div>
        </div>
    </div>
@endsection