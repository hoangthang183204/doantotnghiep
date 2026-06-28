@extends('layouts.admin')

@section('title', 'Cập nhật người dùng')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                        Chỉnh sửa tài khoản
                    </h1>

                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Cập nhật thông tin người dùng trong hệ thống
                    </p>
                </div>

                <a href="{{ route('admin.nguoi-dung.index') }}"
                    class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700
                      text-gray-700 dark:text-gray-200 hover:bg-gray-300
                      dark:hover:bg-gray-600 transition">
                    ← Quay lại
                </a>

            </div>
        </div>

        {{-- SUCCESS --}}
        @if (session('success'))
            <div
                class="bg-green-100 dark:bg-green-900/30
                    border border-green-300 dark:border-green-700
                    text-green-700 dark:text-green-300
                    px-4 py-3 rounded-xl">

                {{ session('success') }}
            </div>
        @endif

        {{-- ERROR --}}
        @if ($errors->any())
            <div
                class="bg-red-100 dark:bg-red-900/30
                    border border-red-300 dark:border-red-700
                    text-red-700 dark:text-red-300
                    px-4 py-3 rounded-xl">

                <div class="font-semibold mb-2">
                    Có lỗi xảy ra:
                </div>

                <ul class="list-disc ml-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- THÔNG TIN NGƯỜI DÙNG --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">

                <div class="bg-blue-600 px-4 py-3 text-white">
                    <h2 class="font-semibold flex items-center gap-2">
                        <i class="fas fa-user"></i>
                        Thông tin người dùng
                    </h2>
                </div>

                <div class="p-5 text-center">

                    {{-- Avatar --}}
                    <div class="flex justify-center">
                        <div
                            class="w-28 h-28 rounded-full border-4 border-blue-600
               flex items-center justify-center
               bg-white dark:bg-gray-700 shadow-md">

                            <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-slate-900 dark:text-gray-200"
                                fill="currentColor" viewBox="0 0 24 24">

                                <path
                                    d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm0 2c-3.33 0-10 1.67-10 5v3h20v-3c0-3.33-6.67-5-10-5z" />

                            </svg>

                        </div>
                    </div>

                    {{-- Họ tên --}}
                    <h3 class="mt-4 text-xl font-semibold text-gray-800 dark:text-white">
                        {{ trim(($user->hoSo->ho ?? '') . ' ' . ($user->hoSo->ten ?? '')) ?: $user->ten_dang_nhap }}
                    </h3>

                    {{-- Mã NV + Phòng ban --}}
                    <div class="grid grid-cols-2 gap-4 mt-4">

                        <div class="border-r dark:border-gray-700">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Mã nhân viên
                            </div>

                            <div class="text-lg font-bold text-gray-800 dark:text-white mt-1">
                                {{ $user->hoSo->ma_nhan_vien ?? '--' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Phòng ban
                            </div>

                            <div class="text-lg font-bold text-gray-800 dark:text-white mt-1">
                                {{ $user->phong_ban->ten_phong_ban ?? '--' }}
                            </div>
                        </div>

                    </div>

                    {{-- Divider --}}
                    <div class="border-t dark:border-gray-700 my-4"></div>

                    {{-- Thông tin liên hệ --}}
                    <div class="space-y-2 text-sm">

                        <div class="flex items-center justify-center gap-2 text-gray-700 dark:text-gray-300">
                            <i class="fas fa-phone"></i>
                            <span>{{ $user->hoSo->so_dien_thoai ?? 'Chưa cập nhật' }}</span>
                        </div>

                        <div class="flex items-center justify-center gap-2 text-gray-700 dark:text-gray-300">
                            <i class="fas fa-envelope"></i>
                            <span>{{ $user->email }}</span>
                        </div>

                    </div>

                </div>

            </div>
            {{-- FORM --}}
            <div class="lg:col-span-2">

                <form method="POST" action="{{ route('admin.nguoi-dung.update', $user->id) }}">

                    @csrf
                    @method('PUT')

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">

                        <div class="bg-amber-500 px-5 py-3 text-white">
                            <h2 class="font-semibold">
                                Thông tin tài khoản
                            </h2>
                        </div>

                        <div class="p-6">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                {{-- TÊN ĐĂNG NHẬP --}}
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Tên đăng nhập
                                    </label>

                                    <input type="text" name="ten_dang_nhap"
                                        value="{{ old('ten_dang_nhap', $user->ten_dang_nhap) }}"
                                        class="w-full px-3 py-2 rounded-lg
                               border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700
                               text-gray-900 dark:text-white">
                                </div>

                                {{-- EMAIL --}}
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Email
                                    </label>

                                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                        class="w-full px-3 py-2 rounded-lg
                               border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700
                               text-gray-900 dark:text-white">
                                </div>

                                {{-- TRẠNG THÁI --}}
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Trạng thái
                                    </label>

                                    <select name="trang_thai"
                                        class="w-full px-3 py-2 rounded-lg
                               border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700
                               text-gray-900 dark:text-white">

                                        <option value="1"
                                            {{ old('trang_thai', $user->trang_thai) == 1 ? 'selected' : '' }}>
                                            Hoạt động
                                        </option>

                                        <option value="0"
                                            {{ old('trang_thai', $user->trang_thai) == 0 ? 'selected' : '' }}>
                                            Ngưng hoạt động
                                        </option>

                                    </select>
                                </div>


                                {{-- QUYỀN NGƯỜI DÙNG --}}
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        <i class="fas fa-user-tag mr-1 text-gray-400"></i> Quyền người dùng
                                    </label>

                                    <select name="vai_tro_id"
                                        class="w-full px-3 py-2 rounded-lg
               border border-gray-300 dark:border-gray-600
               bg-white dark:bg-gray-700
               text-gray-900 dark:text-white">

                                        <option value="">-- Chọn quyền --</option>

                                        @foreach ($vaiTros as $vt)
                                            <option value="{{ $vt->id }}"
                                                {{ old('vai_tro_id', $currentRoleId ?? ($user->vai_tro_id ?? '')) == $vt->id ? 'selected' : '' }}>
                                                {{ $vt->ten_hien_thi }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                {{-- PHÒNG BAN --}}
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Phòng ban
                                    </label>

                                    <select name="phong_ban_id" id="phong_ban_id"
                                        class="w-full px-3 py-2 rounded-lg
                               border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700
                               text-gray-900 dark:text-white">

                                        <option value="">
                                            -- Chọn phòng ban --
                                        </option>

                                        @foreach ($phongBans as $pb)
                                            <option value="{{ $pb->id }}"
                                                {{ old('phong_ban_id', $user->phong_ban_id) == $pb->id ? 'selected' : '' }}>
                                                {{ $pb->ten_phong_ban }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                {{-- CHỨC VỤ --}}
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Chức vụ
                                    </label>

                                    <select name="chuc_vu_id" id="chuc_vu_id"
                                        class="w-full px-3 py-2 rounded-lg
                               border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700
                               text-gray-900 dark:text-white">

                                        <option value="">
                                            -- Chọn chức vụ --
                                        </option>

                                        @foreach ($chucVus as $cv)
                                            <option value="{{ $cv->id }}"
                                                {{ old('chuc_vu_id', $user->chuc_vu_id) == $cv->id ? 'selected' : '' }}>
                                                {{ $cv->ten }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">

                        <a href="{{ route('admin.nguoi-dung.index') }}"
                            class="px-5 py-2 rounded-lg
                  bg-gray-200 dark:bg-gray-700
                  text-gray-700 dark:text-gray-200
                  hover:bg-gray-300 dark:hover:bg-gray-600">
                            Hủy
                        </a>

                        <button type="submit"
                            class="px-5 py-2 rounded-lg
                       bg-blue-600 hover:bg-blue-700
                       text-white">
                            Cập nhật
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
@endsection
