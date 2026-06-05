@extends('layouts.admin')

@section('content')
    <div class="space-y-6">

        {{-- HEADER (GIỐNG HỒ SƠ) --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

            <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                Thêm phòng ban
            </h1>

            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Nhập thông tin phòng ban mới để thêm vào hệ thống
            </p>

        </div>

        {{-- FORM CARD --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

            <form method="POST" action="{{ route('admin.phong-ban.store') }}" class="space-y-4">

                @csrf

                {{-- MÃ PHÒNG BAN --}}
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Mã phòng ban
                    </label>
                    <input name="ma_phong_ban"
                        class="mt-1 w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="VD: PB01">
                </div>

                {{-- TÊN PHÒNG BAN --}}
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Tên phòng ban
                    </label>
                    <input name="ten_phong_ban"
                        class="mt-1 w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="VD: Phòng Kế toán">
                </div>

                {{-- MÔ TẢ --}}
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Mô tả
                    </label>
                    <textarea name="mo_ta" rows="4"
                        class="mt-1 w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Mô tả phòng ban..."></textarea>
                </div>

                {{-- NGÂN SÁCH --}}
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Ngân sách
                    </label>

                    <input name="ngan_sach" type="number"
                        class="mt-1 w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="VD: 1000000000">
                </div>

                {{-- BUTTON --}}
                <div class="flex justify-end gap-3 pt-2">

                    <a href="{{ route('admin.phong-ban.index') }}"
                        class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-white transition">
                        Hủy
                    </a>

                    <button type="submit"
                        class="px-5 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white shadow transition">
                        Lưu phòng ban
                    </button>

                </div>

            </form>

        </div>

    </div>
@endsection
