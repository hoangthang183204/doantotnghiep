@extends('layouts.admin')

@section('title', 'Thêm phòng ban')

@section('content')

    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

            <div class="flex justify-between items-start">

                <div>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                        Thêm mới phòng ban
                    </h1>

                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Thêm mới thông tin phòng ban trong hệ thống
                    </p>
                </div>

                <a href="{{ route('admin.phong-ban.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                    ← Quay lại
                </a>

            </div>

        </div>

        {{-- SUCCESS --}}
        @if (session('success'))
            <div class="px-4 py-3 rounded-lg bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200">
                {{ session('success') }}
            </div>
        @endif

        {{-- ERROR --}}
        @if (session('error'))
            <div class="px-4 py-3 rounded-lg bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200">
                {{ session('error') }}
            </div>
        @endif

        {{-- VALIDATION --}}
        @if ($errors->any())
            <div class="px-4 py-3 rounded-lg bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200">
                <strong>Có lỗi xảy ra:</strong>

                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.phong-ban.store') }}">
            @csrf

            <div class="p-6 space-y-5">

                {{-- HÀNG 1 --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    {{-- MÃ PHÒNG BAN --}}
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">

                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                            </svg>

                            Mã phòng ban
                            <span class="text-red-500">*</span>
                        </label>

                        <input type="text" name="ma_phong_ban" value="{{ old('ma_phong_ban') }}" placeholder="VD: PB001"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">

                        @error('ma_phong_ban')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- TRẠNG THÁI --}}
                    <div>
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">

                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                            </svg>

                            Trạng thái
                        </label>

                        <select name="trang_thai"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">

                            <option value="1" {{ old('trang_thai', 1) == 1 ? 'selected' : '' }}>
                                Hoạt động
                            </option>

                            <option value="0" {{ old('trang_thai') == 0 ? 'selected' : '' }}>
                                Tạm dừng
                            </option>

                        </select>

                        @error('trang_thai')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- TÊN PHÒNG BAN --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7h18M3 12h18M3 17h18" />
                        </svg>

                        Tên phòng ban
                        <span class="text-red-500">*</span>
                    </label>

                    <input type="text" name="ten_phong_ban" value="{{ old('ten_phong_ban') }}"
                        placeholder="Nhập tên phòng ban"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">

                    @error('ten_phong_ban')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- HÀNG 3 --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- TRƯỞNG PHÒNG --}}
                    <div>
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">

                            👤 Trưởng phòng

                        </label>

                        <select name="truong_phong_id"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">

                            <option value="">
                                -- Chọn trưởng phòng --
                            </option>

                            @foreach ($nguoiDungs as $nguoiDung)
                                <option value="{{ $nguoiDung->id }}"
                                    {{ old('truong_phong_id') == $nguoiDung->id ? 'selected' : '' }}>

                                    {{ $nguoiDung->ten_dang_nhap }}

                                </option>
                            @endforeach

                        </select>

                        @error('truong_phong_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NGÂN SÁCH --}}
                    <div>
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">

                            💰 Ngân sách

                        </label>

                        <input type="number" min="0" name="ngan_sach" value="{{ old('ngan_sach') }}"
                            placeholder="Nhập ngân sách"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">

                        @error('ngan_sach')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- GHI CHÚ --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">

                        📝 Ghi chú

                    </label>

                    <textarea name="mo_ta" rows="4" placeholder="Nhập ghi chú hoặc mô tả phòng ban..."
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">{{ old('mo_ta') }}</textarea>

                    @error('mo_ta')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-end gap-3">

                <a href="{{ route('admin.phong-ban.index') }}"
                    class="px-4 py-2 rounded-lg bg-gray-500 hover:bg-gray-600 text-white transition">
                    Hủy
                </a>

                <button type="submit" class="px-5 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white transition">
                    Lưu phòng ban
                </button>

            </div>

        </form>

    </div>

@endsection
