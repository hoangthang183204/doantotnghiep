@extends('layouts.admin')

@section('title','Cập nhật chứng chỉ')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">

        <div class="px-6 py-5">

            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                Cập nhật chứng chỉ
            </h1>

            <p class="mt-1 text-gray-500 dark:text-gray-400">
                Chỉnh sửa thông tin chứng chỉ của nhân viên.
            </p>

        </div>

    </div>

    <form action="{{ route('admin.chung-chi.update',$chungChi->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        {{-- Thông tin nhân viên --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-6">

            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-5">

                Thông tin nhân viên

            </h2>

            <div class="grid md:grid-cols-2 gap-6">

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

                        Nhân viên

                    </label>

                    <div class="mt-1 font-semibold text-gray-800 dark:text-white">

                        {{ $chungChi->hoSo->ho_ten }}

                    </div>

                </div>

            </div>

        </div>

        {{-- Thông tin chứng chỉ --}}
        <div class="mt-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-6">

            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">

                Thông tin chứng chỉ

            </h2>

            <div class="grid md:grid-cols-2 gap-6">

                <div>

                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">

                        Tên chứng chỉ

                    </label>

                    <input
                        type="text"
                        name="ten_chung_chi"
                        value="{{ old('ten_chung_chi',$chungChi->ten_chung_chi) }}"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-900 dark:text-white">

                </div>

                <div>

                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">

                        Tổ chức cấp

                    </label>

                    <input
                        type="text"
                        name="to_chuc_cap"
                        value="{{ old('to_chuc_cap',$chungChi->to_chuc_cap) }}"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-900 dark:text-white">

                </div>

                <div>

                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">

                        Năm cấp

                    </label>

                    <input
                        type="number"
                        name="nam_cap"
                        value="{{ old('nam_cap',$chungChi->nam_cap) }}"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-900 dark:text-white">

                </div>

                <div>

                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">

                        Ngày hết hạn

                    </label>

                    <input
                        type="date"
                        name="ngay_het_han"
                        value="{{ old('ngay_het_han',optional($chungChi->ngay_het_han)->format('Y-m-d')) }}"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-900 dark:text-white">

                </div>

            </div>

        </div>

        {{-- File --}}
        <div class="mt-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-6">

            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">

                File chứng chỉ

            </h2>

            @if($chungChi->file_dinh_kem)

                <div class="mb-5">

                    <a href="{{ asset('storage/'.$chungChi->file_dinh_kem) }}"
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">

                        <i class="fas fa-file-alt mr-2"></i>

                        Xem file hiện tại

                    </a>

                </div>

            @endif

            <input
                type="file"
                name="file_dinh_kem"
                accept=".pdf,.jpg,.jpeg,.png"
                class="block w-full text-sm text-gray-700 dark:text-gray-300
                file:mr-4 file:py-2 file:px-4
                file:rounded-lg file:border-0
                file:bg-blue-600 file:text-white
                hover:file:bg-blue-700">

            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">

                Hỗ trợ PDF, JPG, PNG (tối đa 4MB)

            </p>

        </div>

        {{-- Button --}}
        <div class="flex justify-end gap-3 mt-8">

            <a href="{{ route('admin.chung-chi.index') }}"
               class="px-5 py-2.5 rounded-lg bg-gray-500 hover:bg-gray-600 text-white">

                Quay lại

            </a>

            <button
                type="submit"
                class="px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">

                Lưu thay đổi

            </button>

        </div>

    </form>

</div>

@endsection