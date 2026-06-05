@extends('layouts.admin')

@section('title', 'Chỉnh sửa hồ sơ nhân viên')

@section('content')

    <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">

        <div class="flex justify-between items-center">
            <h1 class="text-xl font-bold">Chỉnh sửa hồ sơ</h1>

            <a href="{{ route('admin.ho-so.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg">
                ← Quay lại
            </a>
        </div>

        <form method="POST" action="{{ route('admin.ho-so.update', $hoSo->id) }}" enctype="multipart/form-data"
            class="grid grid-cols-2 gap-6">

            @csrf
            @method('PUT')

            {{-- HO --}}
            <div>
                <label class="text-sm">Họ</label>
                <input type="text" name="ho" value="{{ $hoSo->ho }}" class="w-full border rounded-lg px-3 py-2">
            </div>

            {{-- TEN --}}
            <div>
                <label class="text-sm">Tên</label>
                <input type="text" name="ten" value="{{ $hoSo->ten }}" class="w-full border rounded-lg px-3 py-2">
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="text-sm">Email</label>
                <input type="email" name="email_cong_ty" value="{{ $hoSo->email_cong_ty }}"
                    class="w-full border rounded-lg px-3 py-2">
            </div>

            {{-- SDT --}}
            <div>
                <label class="text-sm">Số điện thoại</label>
                <input type="text" name="so_dien_thoai" value="{{ $hoSo->so_dien_thoai }}"
                    class="w-full border rounded-lg px-3 py-2">
            </div>

            {{-- NGAY SINH --}}
            <div>
                <label class="text-sm">Ngày sinh</label>
                <input type="date" name="ngay_sinh" value="{{ $hoSo->ngay_sinh }}"
                    class="w-full border rounded-lg px-3 py-2">
            </div>

            {{-- GIOI TINH --}}
            <div>
                <label class="text-sm">Giới tính</label>
                <select name="gioi_tinh" class="w-full border rounded-lg px-3 py-2">

                    <option value="nam" {{ $hoSo->gioi_tinh == 'nam' ? 'selected' : '' }}>
                        Nam
                    </option>

                    <option value="nu" {{ $hoSo->gioi_tinh == 'nu' ? 'selected' : '' }}>
                        Nữ
                    </option>

                </select>
            </div>

            {{-- DIA CHI --}}
            <div class="col-span-2">
                <label class="text-sm font-medium">
                    Địa chỉ
                </label>

                <input type="text" name="dia_chi_hien_tai" value="{{ old('dia_chi_hien_tai', $hoSo->dia_chi_hien_tai) }}"
                    class="w-full border rounded-lg px-3 py-2">
            </div>

            {{-- AVATAR --}}
            <div class="col-span-2">

                <label class="text-sm font-medium block mb-2">
                    Ảnh đại diện
                </label>

                @if ($hoSo->anh_dai_dien)
                    <div class="mb-4">

                        <img src="{{ asset('storage/' . $hoSo->anh_dai_dien) }}" alt="Avatar"
                            class="w-32 h-32 rounded-xl object-cover border shadow-sm">

                    </div>
                @endif

                <input type="file" name="anh_dai_dien" accept="image/*" class="w-full border rounded-lg px-3 py-2">

                <p class="text-xs text-gray-500 mt-1">
                    JPG, PNG, WEBP tối đa 2MB
                </p>

            </div>

            {{-- BUTTON --}}
            <div class="col-span-2 flex justify-end gap-3">
                <button type="reset" class="bg-gray-400 text-white px-4 py-2 rounded-lg">
                    Reset
                </button>

                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg">
                    Lưu thay đổi
                </button>
            </div>

        </form>

    </div>

@endsection
