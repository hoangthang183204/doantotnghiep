@extends('layouts.admin')

@section('title','Cập nhật khóa đào tạo')

@section('content')

<div class="max-w-5xl mx-auto">

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm">

        <div class="px-6 py-5 border-b dark:border-gray-700">

            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                Cập nhật khóa đào tạo
            </h1>

            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Chỉnh sửa thông tin khóa đào tạo của nhân viên
            </p>

        </div>

        <form action="{{ route('admin.dao-tao.update',$daoTao->id) }}"
              method="POST"
              class="p-6">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Nhân viên --}}
                <div>
                    <label class="font-medium">
                        Nhân viên
                    </label>

                    <select
                        name="ho_so_id"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">

                        @foreach($hoSos as $hs)

                            <option
                                value="{{ $hs->id }}"
                                {{ old('ho_so_id',$daoTao->ho_so_id)==$hs->id ? 'selected':'' }}>

                                {{ $hs->ma_nhan_vien }}
                                -
                                {{ $hs->ho_ten }}

                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Tên khóa học --}}
                <div>

                    <label class="font-medium">
                        Tên khóa học
                    </label>

                    <input
                        type="text"
                        name="ten_khoa_hoc"
                        value="{{ old('ten_khoa_hoc',$daoTao->ten_khoa_hoc) }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">

                </div>

                {{-- Đơn vị đào tạo --}}
                <div>

                    <label class="font-medium">
                        Đơn vị đào tạo
                    </label>

                    <input
                        type="text"
                        name="to_chuc"
                        value="{{ old('to_chuc',$daoTao->to_chuc) }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">

                </div>

                {{-- Chi phí --}}
                <div>

                    <label class="font-medium">
                        Chi phí
                    </label>

                    <input
                        type="number"
                        name="chi_phi"
                        value="{{ old('chi_phi',$daoTao->chi_phi) }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">

                </div>

                {{-- Ngày bắt đầu --}}
                <div>

                    <label class="font-medium">
                        Ngày bắt đầu
                    </label>

                    <input
                        type="date"
                        name="ngay_bat_dau"
                        value="{{ old('ngay_bat_dau',optional($daoTao->ngay_bat_dau)->format('Y-m-d')) }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">

                </div>

                {{-- Ngày kết thúc --}}
                <div>

                    <label class="font-medium">
                        Ngày kết thúc
                    </label>

                    <input
                        type="date"
                        name="ngay_ket_thuc"
                        value="{{ old('ngay_ket_thuc',optional($daoTao->ngay_ket_thuc)->format('Y-m-d')) }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">

                </div>

                {{-- Kết quả --}}
                <div>

                    <label class="font-medium">
                        Kết quả
                    </label>

                    <input
                        type="text"
                        name="ket_qua"
                        value="{{ old('ket_qua',$daoTao->ket_qua) }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">

                </div>

                {{-- Có chứng chỉ --}}
                <div class="flex items-center mt-8">

                    <input
                        id="co_chung_chi"
                        type="checkbox"
                        name="co_chung_chi"
                        value="1"
                        {{ old('co_chung_chi',$daoTao->co_chung_chi) ? 'checked' : '' }}
                        class="mr-3">

                    <label for="co_chung_chi">

                        Đã có chứng chỉ

                    </label>

                </div>

            </div>

            {{-- Ghi chú --}}
            <div class="mt-6">

                <label class="font-medium">

                    Ghi chú

                </label>

                <textarea
                    name="ghi_chu"
                    rows="5"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">{{ old('ghi_chu',$daoTao->ghi_chu) }}</textarea>

            </div>

            <div class="flex justify-end gap-3 mt-8">

                <a href="{{ route('admin.dao-tao.index') }}"
                   class="px-5 py-2 rounded-lg bg-gray-500 hover:bg-gray-600 text-white">

                    Hủy

                </a>

                <button
                    class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">

                    Cập nhật

                </button>

            </div>

        </form>

    </div>

</div>

@endsection