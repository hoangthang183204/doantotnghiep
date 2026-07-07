@extends('layouts.admin')

@section('title', 'Theo dõi kết quả đào tạo')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-6">

        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Theo dõi kết quả đào tạo
        </h1>

        <p class="text-gray-500 dark:text-gray-400 mt-2">
            Cập nhật kết quả sau khi nhân viên hoàn thành khóa đào tạo.
        </p>

    </div>

    <form action="{{ route('admin.dao-tao.update',$daoTao->id) }}"
          method="POST">

        @csrf
        @method('PUT')

        {{-- THÔNG TIN KHÓA ĐÀO TẠO --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-6">

            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                Thông tin khóa đào tạo
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="text-sm text-gray-500 dark:text-gray-400">
                        Mã nhân viên
                    </label>

                    <div class="mt-1 font-semibold text-gray-900 dark:text-white">
                        {{ $daoTao->hoSo->ma_nhan_vien }}
                    </div>
                </div>

                <div>
                    <label class="text-sm text-gray-500 dark:text-gray-400">
                        Nhân viên
                    </label>

                    <div class="mt-1 font-semibold text-gray-900 dark:text-white">
                        {{ $daoTao->hoSo->ho_ten }}
                    </div>
                </div>

                <div>
                    <label class="text-sm text-gray-500 dark:text-gray-400">
                        Khóa học
                    </label>

                    <div class="mt-1 font-semibold text-gray-900 dark:text-white">
                        {{ $daoTao->ten_khoa_hoc }}
                    </div>
                </div>

                <div>
                    <label class="text-sm text-gray-500 dark:text-gray-400">
                        Đơn vị đào tạo
                    </label>

                    <div class="mt-1 text-gray-900 dark:text-white">
                        {{ $daoTao->to_chuc ?: '-' }}
                    </div>
                </div>

                <div>
                    <label class="text-sm text-gray-500 dark:text-gray-400">
                        Chi phí
                    </label>

                    <div class="mt-1 font-semibold text-blue-600">
                        {{ number_format($daoTao->chi_phi) }} VNĐ
                    </div>
                </div>

                <div>
                    <label class="text-sm text-gray-500 dark:text-gray-400">
                        Ngày bắt đầu
                    </label>

                    <div class="mt-1 text-gray-900 dark:text-white">
                        {{ optional($daoTao->ngay_bat_dau)->format('d/m/Y') }}
                    </div>
                </div>

            </div>

        </div>

        {{-- KẾT QUẢ ĐÀO TẠO --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-6 mt-6">

            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                Cập nhật kết quả đào tạo
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Ngày kết thúc --}}
                <div>

                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Ngày kết thúc
                    </label>

                    <input
                        type="date"
                        name="ngay_ket_thuc"
                        value="{{ old('ngay_ket_thuc',optional($daoTao->ngay_ket_thuc)->format('Y-m-d')) }}"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-700
                        text-gray-900 dark:text-white
                        px-4 py-2
                        focus:ring-2 focus:ring-blue-500">

                </div>

                {{-- Kết quả --}}
                <div>

                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Kết quả đào tạo
                    </label>

                    <select
                        name="ket_qua"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-700
                        text-gray-900 dark:text-white
                        px-4 py-2
                        focus:ring-2 focus:ring-blue-500">

                        <option value="">-- Chọn kết quả --</option>

                        <option value="Xuất sắc"
                            {{ old('ket_qua',$daoTao->ket_qua)=='Xuất sắc'?'selected':'' }}>
                            Xuất sắc
                        </option>

                        <option value="Đạt"
                            {{ old('ket_qua',$daoTao->ket_qua)=='Đạt'?'selected':'' }}>
                            Đạt
                        </option>

                        <option value="Không đạt"
                            {{ old('ket_qua',$daoTao->ket_qua)=='Không đạt'?'selected':'' }}>
                            Không đạt
                        </option>

                        <option value="Đang học"
                            {{ old('ket_qua',$daoTao->ket_qua)=='Đang học'?'selected':'' }}>
                            Đang học
                        </option>

                    </select>

                </div>

                {{-- Chứng chỉ --}}
                <div>

                    <label class="block mb-3 font-medium text-gray-700 dark:text-gray-300">
                        Chứng chỉ
                    </label>

                    <div class="flex gap-8">

                        <label class="flex items-center gap-2">

                            <input
                                type="radio"
                                name="co_chung_chi"
                                value="1"
                                {{ old('co_chung_chi',$daoTao->co_chung_chi)==1?'checked':'' }}>

                            <span class="text-gray-700 dark:text-gray-300">
                                Đã cấp
                            </span>

                        </label>

                        <label class="flex items-center gap-2">

                            <input
                                type="radio"
                                name="co_chung_chi"
                                value="0"
                                {{ old('co_chung_chi',$daoTao->co_chung_chi)==0?'checked':'' }}>

                            <span class="text-gray-700 dark:text-gray-300">
                                Chưa cấp
                            </span>

                        </label>

                    </div>

                </div>

            </div>

            {{-- Đánh giá --}}
            <div class="mt-6">

                <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                    Đánh giá sau đào tạo
                </label>

                <textarea
                    name="ghi_chu"
                    rows="6"
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                    bg-white dark:bg-gray-700
                    text-gray-900 dark:text-white
                    px-4 py-3
                    focus:ring-2 focus:ring-blue-500">{{ old('ghi_chu',$daoTao->ghi_chu) }}</textarea>

            </div>

        </div>

        {{-- BUTTON --}}
        <div class="flex justify-end gap-3 mt-6">

            <a href="{{ route('admin.dao-tao.index') }}"
               class="px-5 py-2.5 rounded-lg bg-gray-500 hover:bg-gray-600 text-white">

                Quay lại

            </a>

            <button
                type="submit"
                class="px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">

                Lưu kết quả đào tạo

            </button>

        </div>

    </form>

</div>

@endsection