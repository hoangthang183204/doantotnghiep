@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

        <h1 class="text-xl font-bold text-gray-800 dark:text-white">
            Cập nhật người dùng
        </h1>

        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Chỉnh sửa thông tin tài khoản hệ thống
        </p>

    </div>

    {{-- FORM --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

        <form method="POST" action="{{ route('admin.nguoi-dung.update', $user->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- USERNAME --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Tên đăng nhập</label>

                <input name="ten_dang_nhap"
                       value="{{ $user->ten_dang_nhap }}"
                       class="mt-1 w-full border rounded-lg px-3 py-2"
                       placeholder="VD: admin01">
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>

                <input name="email"
                       value="{{ $user->email }}"
                       class="mt-1 w-full border rounded-lg px-3 py-2">
            </div>

            {{-- PASSWORD --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Mật khẩu (để trống nếu không đổi)
                </label>

                <input name="password" type="password"
                       class="mt-1 w-full border rounded-lg px-3 py-2"
                       placeholder="••••••••">
            </div>

            {{-- VAI TRÒ --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Vai trò</label>

                <select name="vai_tro_id"
                        class="mt-1 w-full border rounded-lg px-3 py-2">

                    <option value="">-- Chọn vai trò --</option>

                    @foreach($vaiTros as $vt)
                        <option value="{{ $vt->id }}"
                            {{ $user->vai_tro_id == $vt->id ? 'selected' : '' }}>
                            {{ $vt->ten_hien_thi }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- PHÒNG BAN --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Phòng ban</label>

                <select name="phong_ban_id"
                        class="mt-1 w-full border rounded-lg px-3 py-2">

                    <option value="">-- Chọn phòng ban --</option>

                    @foreach($phongBans as $pb)
                        <option value="{{ $pb->id }}"
                            {{ $user->phong_ban_id == $pb->id ? 'selected' : '' }}>
                            {{ $pb->ten_phong_ban }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- CHỨC VỤ --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Chức vụ</label>

                <select name="chuc_vu_id"
                        class="mt-1 w-full border rounded-lg px-3 py-2">

                    <option value="">-- Chọn chức vụ --</option>

                    @foreach($chucVus as $cv)
                        <option value="{{ $cv->id }}"
                            {{ $user->chuc_vu_id == $cv->id ? 'selected' : '' }}>
                            {{ $cv->ten }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- BUTTON --}}
            <div class="flex justify-end gap-3 pt-2">

                <a href="{{ route('admin.nguoi-dung.index') }}"
                   class="px-4 py-2 bg-gray-200 rounded-lg">
                    Hủy
                </a>

                <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg">
                    Cập nhật
                </button>

            </div>

        </form>

    </div>

</div>
@endsection