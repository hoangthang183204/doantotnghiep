@extends('layouts.admin')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

            <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                Thêm người dùng
            </h1>

            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Nhập thông tin tài khoản mới để thêm vào hệ thống
            </p>

        </div>

        {{-- FORM --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

            <form method="POST" action="{{ route('admin.nguoi-dung.store') }}" class="space-y-4">

                @csrf

                {{-- USERNAME --}}
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Tên đăng nhập
                    </label>

                    <input name="ten_dang_nhap"
                        class="mt-1 w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2"
                        placeholder="VD: admin01">
                </div>

                {{-- EMAIL --}}
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Email
                    </label>

                    <input name="email" type="email"
                        class="mt-1 w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2"
                        placeholder="VD: admin@company.com">
                </div>

                {{-- PASSWORD --}}
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Mật khẩu
                    </label>

                    <input name="password" type="password"
                        class="mt-1 w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2"
                        placeholder="Nhập mật khẩu">
                </div>

                {{-- VAI TRÒ --}}
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Vai trò
                    </label>

                    <select name="vai_tro_id"
                        class="mt-1 w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2">

                        <option value="">-- Chọn vai trò --</option>

                        @foreach ($vaiTros as $vt)
                            <option value="{{ $vt->id }}">
                                {{ $vt->ten_hien_thi }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- PHÒNG BAN --}}
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Phòng ban
                    </label>

                    <select name="phong_ban_id"
                        class="mt-1 w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2">

                        <option value="">-- Chọn phòng ban --</option>

                        @foreach ($phongBans as $pb)
                            <option value="{{ $pb->id }}">
                                {{ $pb->ten_phong_ban }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- CHỨC VỤ --}}
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Chức vụ
                    </label>

                    <select name="chuc_vu_id"
                        class="mt-1 w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2">

                        <option value="">-- Chọn chức vụ --</option>

                        @foreach ($chucVus as $cv)
                            <option value="{{ $cv->id }}">
                                {{ $cv->ten }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- BUTTON --}}
                <div class="flex justify-end gap-3 pt-2">

                    <a href="{{ route('admin.nguoi-dung.index') }}"
                        class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-600 text-white">
                        Hủy
                    </a>

                    <button type="submit" class="px-5 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white">
                        Lưu người dùng
                    </button>

                </div>

            </form>

        </div>

    </div>
@endsection
