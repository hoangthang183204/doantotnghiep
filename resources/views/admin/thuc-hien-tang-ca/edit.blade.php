@extends('layouts.admin')

@section('title', 'Cập nhật thực hiện tăng ca')

@section('content')

@php
    $daHoanThanh = $thucHien->trang_thai === 'hoan_thanh';

    $dangKy = $thucHien->dang_ky;

    $hoTen =
        optional($dangKy->nguoi_dung->hoSo)->ho . ' ' .
        optional($dangKy->nguoi_dung->hoSo)->ten;
@endphp

<div class="space-y-6 bg-gray-50 dark:bg-[#0f172a] min-h-screen p-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
            Cập nhật thực hiện tăng ca
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
            Ghi nhận kết quả thực hiện tăng ca của nhân viên
        </p>
    </div>

    {{-- ERROR --}}
    @if($errors->any())
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-300 p-4 rounded-lg">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- THÔNG TIN ĐƠN --}}
    <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">

        <h2 class="text-lg font-semibold text-blue-600 dark:text-blue-300 mb-4">
            Thông tin đăng ký tăng ca
        </h2>

        <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-700 dark:text-gray-300">

            <div><strong>Nhân viên:</strong> {{ $hoTen }}</div>

            <div><strong>Tài khoản:</strong> {{ $dangKy->nguoi_dung->ten_dang_nhap }}</div>

            <div><strong>Ngày tăng ca:</strong> {{ $dangKy->ngay_tang_ca->format('d/m/Y') }}</div>

            <div><strong>Loại tăng ca:</strong> {{ \App\Models\DangKyTangCa::$loaiLabels[$dangKy->loai_tang_ca] }}</div>

            <div>
                <strong>Giờ đăng ký:</strong>
                {{ $dangKy->gio_bat_dau }} - {{ $dangKy->gio_ket_thuc }}
            </div>

            <div>
                <strong>Số giờ đăng ký:</strong> {{ $dangKy->so_gio_tang_ca }} giờ
            </div>

        </div>

        <div class="mt-4">
            <strong class="text-gray-800 dark:text-gray-200">Lý do tăng ca:</strong>

            <div class="mt-2 bg-gray-50 dark:bg-gray-900/40 border border-gray-200 dark:border-gray-700 rounded-lg p-3 text-gray-700 dark:text-gray-300">
                {{ $dangKy->ly_do_tang_ca }}
            </div>
        </div>

    </div>

    {{-- FORM --}}
    <form action="{{ route('admin.thuc-hien-tang-ca.update',$thucHien->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">

            <div class="grid md:grid-cols-2 gap-6">

                {{-- GIỜ BẮT ĐẦU --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Giờ bắt đầu thực tế
                    </label>

                    <input
                        id="gio_bat_dau"
                        type="time"
                        name="gio_bat_dau_thuc_te"
                        value="{{ old('gio_bat_dau_thuc_te',$thucHien->gio_bat_dau_thuc_te) }}"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500"
                        {{ $daHoanThanh ? 'readonly' : '' }}>
                </div>

                {{-- GIỜ KẾT THÚC --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Giờ kết thúc thực tế
                    </label>

                    <input
                        id="gio_ket_thuc"
                        type="time"
                        name="gio_ket_thuc_thuc_te"
                        value="{{ old('gio_ket_thuc_thuc_te',$thucHien->gio_ket_thuc_thuc_te) }}"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500"
                        {{ $daHoanThanh ? 'readonly' : '' }}>
                </div>

                {{-- SỐ GIỜ --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Số giờ thực tế
                    </label>

                    <input
                        id="so_gio_preview"
                        type="text"
                        readonly
                        value="{{ $thucHien->so_gio_tang_ca_thuc_te }}"
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700
                        bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                </div>

                {{-- CÔNG --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Số công tăng ca
                    </label>

                    <input
                        type="text"
                        readonly
                        value="{{ $thucHien->so_cong_tang_ca }}"
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700
                        bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                </div>

                {{-- TRẠNG THÁI --}}
                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Trạng thái
                    </label>

                    <select
                        name="trang_thai"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                        {{ $daHoanThanh ? 'disabled' : '' }}>

                        @foreach(\App\Models\ThucHienTangCa::$trangThaiLabels as $key => $value)
                            <option value="{{ $key }}" @selected($thucHien->trang_thai == $key)>
                                {{ $value }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- CÔNG VIỆC --}}
                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Công việc đã thực hiện
                    </label>

                    <textarea
                        name="cong_viec_da_thuc_hien"
                        rows="5"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                        {{ $daHoanThanh ? 'readonly' : '' }}>{{ old('cong_viec_da_thuc_hien',$thucHien->cong_viec_da_thuc_hien) }}</textarea>
                </div>

                {{-- GHI CHÚ --}}
                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Ghi chú
                    </label>

                    <textarea
                        name="ghi_chu"
                        rows="3"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                        {{ $daHoanThanh ? 'readonly' : '' }}>{{ old('ghi_chu',$thucHien->ghi_chu) }}</textarea>
                </div>

            </div>
        </div>

        {{-- ACTION --}}
        <div class="flex gap-3 mt-6">

            @unless($daHoanThanh)
                <button type="submit"
                    class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                    Lưu cập nhật
                </button>
            @endunless

            <a href="{{ route('admin.thuc-hien-tang-ca.index') }}"
                class="px-5 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white rounded-lg">
                Quay lại
            </a>

        </div>

    </form>

</div>

{{-- SCRIPT --}}
@push('scripts')
<script>
function tinhSoGio() {
    let start = document.getElementById('gio_bat_dau').value;
    let end = document.getElementById('gio_ket_thuc').value;

    if (!start || !end) return;

    let s = start.split(':');
    let e = end.split(':');

    let startMinutes = parseInt(s[0]) * 60 + parseInt(s[1]);
    let endMinutes = parseInt(e[0]) * 60 + parseInt(e[1]);

    let total = (endMinutes - startMinutes) / 60;
    if (total < 0) total = 0;

    document.getElementById('so_gio_preview').value = total.toFixed(2);
}

document.getElementById('gio_bat_dau')?.addEventListener('change', tinhSoGio);
document.getElementById('gio_ket_thuc')?.addEventListener('change', tinhSoGio);
</script>
@endpush

@endsection