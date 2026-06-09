@extends('layouts.admin')

@section('title', 'Chỉnh sửa chức vụ')

@section('content')

<div class="max-w-5xl mx-auto">

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                Chỉnh sửa chức vụ
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Cập nhật thông tin chức vụ trong hệ thống
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

        <form action="{{ route('admin.chuc-vu.update', $chucVu->id) }}"
              method="POST"
              class="space-y-5">

            @csrf
            @method('PUT')

            {{-- TÊN CHỨC VỤ --}}
            <div>
                <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                    Tên chức vụ
                </label>

                <input
                    type="text"
                    name="ten"
                    value="{{ old('ten', $chucVu->ten) }}"
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            {{-- MÃ CHỨC VỤ --}}
            <div>
                <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                    Mã chức vụ
                </label>

                <input
                    type="text"
                    name="ma"
                    value="{{ old('ma', $chucVu->ma) }}"
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            {{-- PHÒNG BAN --}}
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
                        <option
                            value="{{ $pb->id }}"
                            {{ old('phong_ban_id', $chucVu->phong_ban_id) == $pb->id ? 'selected' : '' }}>
                            {{ $pb->ten_phong_ban }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- LƯƠNG + HỆ SỐ --}}
            <div class="grid md:grid-cols-2 gap-5">

                <div>
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Lương cơ bản
                    </label>

                    <input
                        type="number"
                        name="luong_co_ban"
                        value="{{ old('luong_co_ban', $chucVu->luong_co_ban) }}"
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
                        value="{{ old('he_so_luong', $chucVu->he_so_luong) }}"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3">
                </div>

            </div>

            {{-- MÔ TẢ --}}
            <div>
                <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                    Mô tả
                </label>

                <textarea
                    name="mo_ta"
                    rows="4"
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3">{{ old('mo_ta', $chucVu->mo_ta) }}</textarea>
            </div>

            {{-- TRẠNG THÁI --}}
            <div>
                <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                    Trạng thái
                </label>

                <select
                    name="trang_thai"
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3">

                    <option value="1"
                        {{ old('trang_thai', $chucVu->trang_thai) == 1 ? 'selected' : '' }}>
                        Hoạt động
                    </option>

                    <option value="0"
                        {{ old('trang_thai', $chucVu->trang_thai) == 0 ? 'selected' : '' }}>
                        Ngừng hoạt động
                    </option>

                </select>
            </div>

            {{-- BUTTON --}}
            <div class="flex gap-3 pt-3">

                <button
                    type="submit"
                    class="px-5 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                    Cập nhật chức vụ
                </button>

                <a href="{{ route('admin.chuc-vu.index') }}"
                   class="px-5 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                    Quay lại
                </a>

            </div>

        </form>

    </div>

</div>

@endsection