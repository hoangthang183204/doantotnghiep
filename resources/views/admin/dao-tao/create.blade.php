@extends('layouts.admin')

@section('title', 'Đăng ký khóa đào tạo')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            Đăng ký khóa đào tạo
        </h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Thêm khóa đào tạo cho nhân viên
        </p>
    </div>

    <form action="{{ route('admin.dao-tao.store') }}" method="POST">
        @csrf

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Nhân viên --}}
                <div>
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Nhân viên <span class="text-red-500">*</span>
                    </label>

                    <select name="ho_so_id"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500focus:border-blue-500">

                        <option value="">-- Chọn nhân viên --</option>

                        @foreach($hoSos as $hs)
                            <option value="{{ $hs->id }}" {{ old('ho_so_id')==$hs->id?'selected':'' }}>
                                {{ $hs->ma_nhan_vien }} - {{ $hs->ho_ten }}
                            </option>
                        @endforeach

                    </select>

                    @error('ho_so_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                </div>

                {{-- Khóa học --}}
                <div>

                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Tên khóa học <span class="text-red-500">*</span>
                    </label>

                    <input type="text"
                           name="ten_khoa_hoc"
                           value="{{ old('ten_khoa_hoc') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">

                    @error('ten_khoa_hoc')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                </div>

                {{-- Đơn vị --}}
                <div>

                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Đơn vị đào tạo
                    </label>

                    <input type="text"
                           name="to_chuc"
                           value="{{ old('to_chuc') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">

                </div>

                {{-- Chi phí --}}
                <div>

                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Chi phí
                    </label>

                    <input type="number"
                           min="0"
                           step="1000"
                           name="chi_phi"
                           value="{{ old('chi_phi') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">

                </div>

                {{-- Ngày bắt đầu --}}
                <div>

                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Ngày bắt đầu <span class="text-red-500">*</span>
                    </label>

                    <input type="date"
                           name="ngay_bat_dau"
                           value="{{ old('ngay_bat_dau') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">

                </div>

                {{-- Ngày kết thúc --}}
                <div>

                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Ngày kết thúc
                    </label>

                    <input type="date"
                           name="ngay_ket_thuc"
                           value="{{ old('ngay_ket_thuc') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">

                </div>

            </div>

            {{-- Ghi chú --}}
            <div class="mt-6">

                <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                    Ghi chú
                </label>

                <textarea
                    name="ghi_chu"
                    rows="4"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">{{ old('ghi_chu') }}</textarea>

            </div>

        </div>

        {{-- Button --}}
        <div class="flex justify-end gap-3">

            <a href="{{ route('admin.dao-tao.index') }}"
               class="px-5 py-2.5 rounded-lg bg-gray-500 hover:bg-gray-600 text-white">

                Quay lại

            </a>

            <button type="submit"
                    class="px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">

                Lưu đăng ký

            </button>

        </div>

    </form>

</div>

@endsection