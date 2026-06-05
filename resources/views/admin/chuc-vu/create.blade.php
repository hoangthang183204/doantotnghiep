@extends('layouts.admin')

@section('title', 'Thêm chức vụ')

@section('content')

<div class="max-w-5xl mx-auto">

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                Thêm chức vụ
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Tạo mới chức vụ trong hệ thống
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-5 rounded-lg bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 p-4">

                <ul class="list-disc list-inside text-red-700 dark:text-red-300">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>
        @endif

        <form action="{{ route('admin.chuc-vu.store') }}"
              method="POST"
              class="space-y-5">

            @csrf

            <div>
                <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                    Tên chức vụ
                </label>

                <input
                    type="text"
                    name="ten"
                    value="{{ old('ten') }}"
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                    Mã chức vụ
                </label>

                <input
                    type="text"
                    name="ma"
                    value="{{ old('ma') }}"
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                    Phòng ban
                </label>

                <select
                    name="phong_ban_id"
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3">

                    <option value="">
                        -- Chọn phòng ban --
                    </option>

                    @foreach($phongBans as $pb)
                        <option value="{{ $pb->id }}">
                            {{ $pb->ten_phong_ban }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="grid md:grid-cols-2 gap-5">

                <div>
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Lương cơ bản
                    </label>

                    <input
                        type="number"
                        name="luong_co_ban"
                        value="{{ old('luong_co_ban') }}"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3">
                </div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Hệ số lương
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        name="he_so_luong"
                        value="{{ old('he_so_luong') }}"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3">
                </div>

            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                    Mô tả
                </label>

                <textarea
                    name="mo_ta"
                    rows="4"
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3">{{ old('mo_ta') }}</textarea>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                    Trạng thái
                </label>

                <select
                    name="trang_thai"
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3">

                    <option value="1">Hoạt động</option>
                    <option value="0">Ngừng hoạt động</option>

                </select>
            </div>

            <div class="flex gap-3 pt-3">

                <button
                    type="submit"
                    class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                    Lưu chức vụ
                </button>

                <a href="{{ route('admin.chuc-vu.index') }}"
                   class="px-5 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                    Quay lại
                </a>

            </div>

        </form>

    </div>

</div>

@endsection