@extends('layouts.admin')

@section('title', 'Chi tiết chấm công')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

        <div class="flex items-center justify-between">

            <div>

                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Chi tiết chấm công
                </h1>

                <p class="text-gray-500 dark:text-gray-400 mt-1">
                    Thông tin chi tiết bản ghi chấm công
                </p>

            </div>

            <a href="{{ route('admin.cham-cong.index') }}"
               class="px-5 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">

                Quay lại

            </a>

        </div>

    </div>

    {{-- THÔNG TIN --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

        <div class="grid md:grid-cols-2 gap-6">

            <div>
                <label class="text-sm text-gray-500 dark:text-gray-400">
                    Nhân viên
                </label>

                <p class="mt-1 font-semibold text-gray-800 dark:text-white">
                    {{ $chamCong->nguoi_dung->ho_ten ?? 'N/A' }}
                </p>
            </div>

            <div>
                <label class="text-sm text-gray-500 dark:text-gray-400">
                    Ngày chấm công
                </label>

                <p class="mt-1 font-semibold text-gray-800 dark:text-white">
                    {{ \Carbon\Carbon::parse($chamCong->ngay_cham_cong)->format('d/m/Y') }}
                </p>
            </div>

            <div>
                <label class="text-sm text-gray-500 dark:text-gray-400">
                    Giờ vào
                </label>

                <p class="mt-1 font-semibold text-gray-800 dark:text-white">
                    {{ $chamCong->gio_vao ?? '--' }}
                </p>
            </div>

            <div>
                <label class="text-sm text-gray-500 dark:text-gray-400">
                    Giờ ra
                </label>

                <p class="mt-1 font-semibold text-gray-800 dark:text-white">
                    {{ $chamCong->gio_ra ?? '--' }}
                </p>
            </div>

            <div>
                <label class="text-sm text-gray-500 dark:text-gray-400">
                    Số giờ làm
                </label>

                <p class="mt-1 font-semibold text-gray-800 dark:text-white">
                    {{ $chamCong->so_gio_lam }}
                </p>
            </div>

            <div>
                <label class="text-sm text-gray-500 dark:text-gray-400">
                    Số công
                </label>

                <p class="mt-1 font-semibold text-gray-800 dark:text-white">
                    {{ $chamCong->so_cong }}
                </p>
            </div>

            <div>
                <label class="text-sm text-gray-500 dark:text-gray-400">
                    Giờ tăng ca
                </label>

                <p class="mt-1 font-semibold text-gray-800 dark:text-white">
                    {{ $chamCong->gio_tang_ca }}
                </p>
            </div>

            <div>
                <label class="text-sm text-gray-500 dark:text-gray-400">
                    Đi muộn
                </label>

                <p class="mt-1 font-semibold text-gray-800 dark:text-white">
                    {{ $chamCong->phut_di_muon }} phút
                </p>
            </div>

            <div>
                <label class="text-sm text-gray-500 dark:text-gray-400">
                    Về sớm
                </label>

                <p class="mt-1 font-semibold text-gray-800 dark:text-white">
                    {{ $chamCong->phut_ve_som }} phút
                </p>
            </div>

            <div>
                <label class="text-sm text-gray-500 dark:text-gray-400">
                    Trạng thái
                </label>

                <div class="mt-2">

                    @if($chamCong->trang_thai == 'dung_gio')

                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300">
                            Đúng giờ
                        </span>

                    @elseif($chamCong->trang_thai == 'di_muon')

                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300">
                            Đi muộn
                        </span>

                    @elseif($chamCong->trang_thai == 've_som')

                        <span class="px-3 py-1 rounded-full bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300">
                            Về sớm
                        </span>

                    @else

                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300">
                            Không chấm công
                        </span>

                    @endif

                </div>

            </div>

            <div>
                <label class="text-sm text-gray-500 dark:text-gray-400">
                    Phương thức chấm công
                </label>

                <p class="mt-1 font-semibold text-gray-800 dark:text-white">
                    {{ $chamCong->phuong_thuc_cham_cong ?? '--' }}
                </p>
            </div>

        </div>

    </div>

    {{-- PHÊ DUYỆT --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

        <h2 class="font-bold text-lg text-gray-800 dark:text-white mb-4">
            Thông tin phê duyệt
        </h2>

        <div class="grid md:grid-cols-2 gap-6">

            <div>
                <label class="text-sm text-gray-500 dark:text-gray-400">
                    Người phê duyệt
                </label>

                <p class="mt-1 font-semibold text-gray-800 dark:text-white">
                    {{ $chamCong->nguoi_phe_duyet->ho_ten ?? 'Chưa có' }}
                </p>
            </div>

            <div>
                <label class="text-sm text-gray-500 dark:text-gray-400">
                    Trạng thái duyệt
                </label>

                <div class="mt-2">

                    @if($chamCong->trang_thai_duyet == 1)

                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">
                            Đã duyệt
                        </span>

                    @elseif($chamCong->trang_thai_duyet == 2)

                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700">
                            Từ chối
                        </span>

                    @else

                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700">
                            Chờ duyệt
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>

    {{-- GHI CHÚ --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

        <h2 class="font-bold text-lg text-gray-800 dark:text-white mb-4">
            Ghi chú
        </h2>

        <p class="text-gray-700 dark:text-gray-300">
            {{ $chamCong->ghi_chu ?: 'Không có ghi chú' }}
        </p>

    </div>

</div>

@endsection