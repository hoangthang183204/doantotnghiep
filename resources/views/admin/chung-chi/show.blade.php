@extends('layouts.admin')

@section('title','Chi tiết chứng chỉ')

@section('content')

<div class="space-y-6">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                Chi tiết chứng chỉ
            </h1>

            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Thông tin chứng chỉ của nhân viên
            </p>

        </div>

        <div class="flex gap-2">

            <a href="{{ route('admin.chung-chi.edit',$chungChi->id) }}"
               class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg">

                <i class="fas fa-edit mr-1"></i>
                Chỉnh sửa

            </a>

            <a href="{{ route('admin.chung-chi.index') }}"
               class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">

                Quay lại

            </a>

        </div>

    </div>

    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-6">

        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-5">

            Thông tin nhân viên

        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>

                <label class="text-sm text-gray-500 dark:text-gray-400">

                    Mã nhân viên

                </label>

                <div class="mt-1 font-semibold text-gray-800 dark:text-white">

                    {{ $chungChi->hoSo->ma_nhan_vien }}

                </div>

            </div>

            <div>

                <label class="text-sm text-gray-500 dark:text-gray-400">

                    Họ và tên

                </label>

                <div class="mt-1 font-semibold text-gray-800 dark:text-white">

                    {{ $chungChi->hoSo->ho_ten }}

                </div>

            </div>

        </div>

    </div>

    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-6">

        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-5">

            Thông tin chứng chỉ

        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>

                <label class="text-sm text-gray-500 dark:text-gray-400">

                    Tên chứng chỉ

                </label>

                <div class="mt-1 font-semibold text-gray-800 dark:text-white">

                    {{ $chungChi->ten_chung_chi }}

                </div>

            </div>

            <div>

                <label class="text-sm text-gray-500 dark:text-gray-400">

                    Tổ chức cấp

                </label>

                <div class="mt-1 text-gray-800 dark:text-white">

                    {{ $chungChi->to_chuc_cap }}

                </div>

            </div>

            <div>

                <label class="text-sm text-gray-500 dark:text-gray-400">

                    Năm cấp

                </label>

                <div class="mt-1 text-gray-800 dark:text-white">

                    {{ $chungChi->nam_cap }}

                </div>

            </div>

            <div>

                <label class="text-sm text-gray-500 dark:text-gray-400">

                    Ngày hết hạn

                </label>

                <div class="mt-1 text-gray-800 dark:text-white">

                    {{ optional($chungChi->ngay_het_han)->format('d/m/Y') ?? 'Không có' }}

                </div>

            </div>

            <div>

                <label class="text-sm text-gray-500 dark:text-gray-400">

                    Trạng thái

                </label>

                <div class="mt-2">

                    <span class="px-3 py-1 rounded-full text-sm {{ $chungChi->mau_trang_thai }}">

                        {{ $chungChi->trang_thai_hien_thi }}

                    </span>

                </div>

            </div>

        </div>

    </div>

    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-6">

        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-5">

            File đính kèm

        </h2>

        @if($chungChi->file_dinh_kem)

            <a href="{{ asset('storage/'.$chungChi->file_dinh_kem) }}"
               target="_blank"
               class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">

                <i class="fas fa-file-pdf mr-2"></i>

                Xem chứng chỉ

            </a>

        @else

            <div class="text-gray-500 dark:text-gray-400">

                Chưa có file chứng chỉ.

            </div>

        @endif

    </div>

</div>

@endsection