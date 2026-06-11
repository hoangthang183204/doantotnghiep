@extends('layouts.admin')

@section('title', 'Chi tiết thực hiện tăng ca')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Chi tiết thực hiện tăng ca
        </h1>

        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            Xem thông tin đăng ký tăng ca và kết quả thực hiện của nhân viên
        </p>
    </div>

    @php
        $dangKy = $thucHien->dang_ky;

        $hoTen = trim(
            (optional($dangKy->nguoi_dung->hoSo)->ho ?? '') . ' ' .
            (optional($dangKy->nguoi_dung->hoSo)->ten ?? '')
        );
    @endphp

    {{-- THÔNG TIN NHÂN VIÊN --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">

        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Thông tin nhân viên
            </h2>
        </div>

        <div class="p-6">

            <div class="grid md:grid-cols-2 gap-5">

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Họ và tên
                    </p>

                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ $hoTen }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Tên đăng nhập
                    </p>

                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ $dangKy->nguoi_dung->ten_dang_nhap }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Ngày tăng ca
                    </p>

                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ $dangKy->ngay_tang_ca->format('d/m/Y') }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Loại tăng ca
                    </p>

                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ \App\Models\DangKyTangCa::$loaiLabels[$dangKy->loai_tang_ca] ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Giờ đăng ký
                    </p>

                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ $dangKy->gio_bat_dau }} - {{ $dangKy->gio_ket_thuc }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Số giờ đăng ký
                    </p>

                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ $dangKy->so_gio_tang_ca }} giờ
                    </p>
                </div>

            </div>

            <div class="mt-6">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                    Lý do tăng ca
                </p>

                <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-gray-100">
                    {{ $dangKy->ly_do_tang_ca }}
                </div>
            </div>

        </div>

    </div>

    {{-- KẾT QUẢ THỰC HIỆN --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">

        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Kết quả thực hiện
            </h2>
        </div>

        <div class="p-6">

            <div class="grid md:grid-cols-2 gap-5">

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Bắt đầu thực tế
                    </p>

                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ $thucHien->gio_bat_dau_thuc_te ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Kết thúc thực tế
                    </p>

                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ $thucHien->gio_ket_thuc_thuc_te ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Số giờ thực tế
                    </p>

                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ $thucHien->so_gio_tang_ca_thuc_te }} giờ
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Số công tăng ca
                    </p>

                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ $thucHien->so_cong_tang_ca }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Trạng thái
                    </p>

                    @php
                        $statusClass = match($thucHien->trang_thai) {
                            'hoan_thanh' =>
                                'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',

                            'dang_lam' =>
                                'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',

                            'khong_hoan_thanh' =>
                                'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',

                            default =>
                                'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                        };
                    @endphp

                    <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                        {{ \App\Models\ThucHienTangCa::$trangThaiLabels[$thucHien->trang_thai] }}
                    </span>
                </div>

            </div>

            <div class="mt-6">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                    Công việc đã thực hiện
                </p>

                <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-gray-100">
                    {{ $thucHien->cong_viec_da_thuc_hien ?: 'Chưa cập nhật' }}
                </div>
            </div>

            <div class="mt-6">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                    Ghi chú
                </p>

                <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-gray-100">
                    {{ $thucHien->ghi_chu ?: 'Không có ghi chú' }}
                </div>
            </div>

        </div>

    </div>

    {{-- ACTION --}}
    <div class="flex flex-wrap gap-3">

        <a href="{{ route('admin.thuc-hien-tang-ca.edit', $thucHien->id) }}"
            class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl transition">
            Cập nhật
        </a>

        <a href="{{ route('admin.thuc-hien-tang-ca.index') }}"
            class="px-5 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-xl transition">
            Quay lại
        </a>

    </div>

</div>
@endsection