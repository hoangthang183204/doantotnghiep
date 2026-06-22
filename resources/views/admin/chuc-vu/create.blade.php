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

        <form action="{{ route('admin.chuc-vu.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- TÊN CHỨC VỤ --}}
                <div>
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Tên chức vụ <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="ten"
                        value="{{ old('ten') }}"
                        placeholder="VD: Trưởng phòng Nhân sự..."
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                {{-- MÃ CHỨC VỤ --}}
                <div>
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Mã chức vụ <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="ma"
                        value="{{ old('ma') }}"
                        placeholder="VD: TP_NS..."
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                {{-- PHÒNG BAN --}}
                <div>
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Phòng ban <span class="text-red-500">*</span>
                    </label>
                    <select
                        name="phong_ban_id"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">-- Chọn phòng ban --</option>
                        @foreach($phongBans as $pb)
                            <option value="{{ $pb->id }}" {{ old('phong_ban_id') == $pb->id ? 'selected' : '' }}>
                                {{ $pb->ten_phong_ban }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- TRẠNG THÁI --}}
                <div>
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Trạng thái
                    </label>
                    <select
                        name="trang_thai"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="1" {{ old('trang_thai', '1') == '1' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ old('trang_thai') == '0' ? 'selected' : '' }}>Ngừng hoạt động</option>
                    </select>
                </div>

                {{-- ✅ THÊM: LƯƠNG CƠ BẢN --}}
                <div>
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Lương cơ bản
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-500 dark:text-gray-400">₫</span>
                        <input
                            type="number"
                            name="luong_co_ban"
                            step="1000"
                            min="0"
                            value="{{ old('luong_co_ban') }}"
                            placeholder="VD: 10000000"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white pl-8 pr-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Để trống nếu chưa xác định</p>
                </div>

                {{-- ✅ THÊM: HỆ SỐ LƯƠNG --}}
                <div>
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Hệ số lương
                    </label>
                    <input
                        type="number"
                        name="he_so_luong"
                        step="0.01"
                        min="0"
                        max="10"
                        value="{{ old('he_so_luong') }}"
                        placeholder="VD: 2.5"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Từ 0 đến 10</p>
                </div>

                {{-- MÔ TẢ --}}
                <div class="md:col-span-2">
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Mô tả
                    </label>
                    <textarea
                        name="mo_ta"
                        rows="4"
                        placeholder="Nhập mô tả chi tiết về chức vụ này..."
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('mo_ta') }}</textarea>
                </div>

            </div>

            {{-- BUTTON --}}
            <div class="flex gap-3 mt-8">
                <button
                    type="submit"
                    class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Lưu chức vụ
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