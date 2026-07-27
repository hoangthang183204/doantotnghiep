@extends('layouts.employee')

@section('title', 'Yêu cầu xem xét lương')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            Yêu cầu xem xét phiếu lương
        </h1>

        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            Gửi yêu cầu tới phòng nhân sự khi phát hiện sai sót trong phiếu lương.
        </p>

    </div>

    {{-- Form --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm">

        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">

            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                Thông tin phiếu lương
            </h2>

        </div>

        <div class="p-6">

    @include('layouts.partials.alerts')

    <div class="grid md:grid-cols-2 gap-6 mb-8">

        <div>
            <label class="block text-sm text-gray-500 dark:text-gray-400">
                Kỳ lương
            </label>

            <p class="mt-2 text-lg font-semibold text-gray-800 dark:text-white">
                Tháng {{ $luong->luong_thang }}/{{ $luong->luong_nam }}
            </p>
        </div>

        <div>
            <label class="block text-sm text-gray-500 dark:text-gray-400">
                Lương thực nhận
            </label>

            <p class="mt-2 text-lg font-bold text-green-600">
                {{ number_format($luong->luong_thuc_nhan) }} đ
            </p>
        </div>

    </div>

    {{-- Thông tin để nhân viên đối chiếu --}}
    <div class="grid md:grid-cols-2 gap-6 mb-8">

        <div>
            <label class="text-sm text-gray-500">Số ngày công</label>
            <p class="font-semibold">{{ $luong->so_ngay_cong }} ngày</p>
        </div>

        <div>
            <label class="text-sm text-gray-500">Giờ tăng ca</label>
            <p class="font-semibold">{{ $luong->gio_tang_ca }} giờ</p>
        </div>

        <div>
            <label class="text-sm text-gray-500">Tổng phụ cấp</label>
            <p class="font-semibold">{{ number_format($luong->tong_phu_cap) }} đ</p>
        </div>

        <div>
            <label class="text-sm text-gray-500">Tổng khấu trừ</label>
            <p class="font-semibold text-red-600">
                {{ number_format($luong->tong_khau_tru) }} đ
            </p>
        </div>

    </div>

    <form action="{{ route('employee.yeu-cau-luong.store',$luong->id) }}"
          method="POST">

        @csrf

        {{-- Loại sai sót --}}
        <div class="mb-6">

            <label class="block font-semibold text-gray-800 dark:text-white mb-2">
                Loại sai sót <span class="text-red-500">*</span>
            </label>

            <p class="text-sm text-gray-500 mb-4">
                Có thể chọn một hoặc nhiều loại sai sót nếu phiếu lương có nhiều vấn đề.
            </p>

            <div class="grid grid-cols-2 gap-4">

                <label class="flex items-center gap-3 border rounded-lg p-3 hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox"
                           name="loai_sai_sot[]"
                           value="cham_cong"
                           {{ in_array('cham_cong', old('loai_sai_sot', [])) ? 'checked' : '' }}>
                    <span>Sai chấm công</span>
                </label>

                <label class="flex items-center gap-3 border rounded-lg p-3 hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox"
                           name="loai_sai_sot[]"
                           value="tang_ca"
                           {{ in_array('tang_ca', old('loai_sai_sot', [])) ? 'checked' : '' }}>
                    <span>Sai giờ tăng ca</span>
                </label>

                <label class="flex items-center gap-3 border rounded-lg p-3 hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox"
                           name="loai_sai_sot[]"
                           value="phu_cap"
                           {{ in_array('phu_cap', old('loai_sai_sot', [])) ? 'checked' : '' }}>
                    <span>Sai phụ cấp</span>
                </label>

                <label class="flex items-center gap-3 border rounded-lg p-3 hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox"
                           name="loai_sai_sot[]"
                           value="khau_tru"
                           {{ in_array('khau_tru', old('loai_sai_sot', [])) ? 'checked' : '' }}>
                    <span>Sai khấu trừ</span>
                </label>

            </div>

            @error('loai_sai_sot')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror

        </div>

        {{-- Lý do --}}
        <div>

            <label class="block font-semibold text-gray-800 dark:text-white mb-2">
                Lý do xem xét <span class="text-red-500">*</span>
            </label>

            <textarea
                name="ly_do"
                rows="7"
                class="w-full rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white p-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Ví dụ: Tôi làm đủ 26 ngày nhưng hệ thống chỉ tính 25 ngày công...">{{ old('ly_do') }}</textarea>

            @error('ly_do')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror

        </div>

        <div class="flex justify-end gap-3 mt-8">

            <a href="{{ route('employee.bang-luong.show',$luong->id) }}"
               class="px-6 py-2.5 rounded-lg bg-gray-500 hover:bg-gray-600 text-white transition">
                Quay lại
            </a>

            <button
                type="submit"
                class="px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition">
                Gửi yêu cầu
            </button>

        </div>

    </form>
    </div> {{-- p-6 --}}

</div> {{-- card trắng --}}

</div> {{-- space-y-6 --}}


@endsection