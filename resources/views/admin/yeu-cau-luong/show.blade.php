@extends('layouts.admin')

@section('title', 'Chi tiết yêu cầu xem xét lương')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

        <div class="flex items-center justify-between">

            <div>

                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Chi tiết yêu cầu xem xét lương
                </h1>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Xem thông tin yêu cầu của nhân viên
                </p>

            </div>

            <a href="{{ route('admin.yeu-cau-luong.index') }}"
               class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">

                ← Quay lại

            </a>

        </div>

    </div>


    {{-- Nội dung --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm">

        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">

            <h2 class="font-semibold text-lg">
                Thông tin yêu cầu
            </h2>

        </div>

        <div class="grid grid-cols-2 gap-6 p-6">

            {{-- Nhân viên --}}
            <div>

                <label class="text-sm text-gray-500">
                    Nhân viên
                </label>

                <div class="mt-1 font-semibold text-lg">
                    {{ $yeuCau->nguoiDung->ho_ten ?? $yeuCau->nguoiDung->ten_dang_nhap }}
                </div>

            </div>

            {{-- Trạng thái --}}
            <div>

                <label class="text-sm text-gray-500">
                    Trạng thái
                </label>

                <div class="mt-2">

                    @if($yeuCau->trang_thai=='cho_duyet')

                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm font-semibold">
                            Chờ duyệt
                        </span>

                    @elseif($yeuCau->trang_thai=='da_duyet')

                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
                            Đã duyệt
                        </span>

                    @else

                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-semibold">
                            Từ chối
                        </span>

                    @endif

                </div>

            </div>


            {{-- Kỳ lương --}}
            <div>

                <label class="text-sm text-gray-500">
                    Kỳ lương
                </label>

                <div class="mt-1 font-medium">

                    Tháng
                    {{ $yeuCau->luongNhanVien->luong_thang }}
                    /
                    {{ $yeuCau->luongNhanVien->luong_nam }}

                </div>

            </div>


            {{-- Ngày gửi --}}
            <div>

                <label class="text-sm text-gray-500">
                    Ngày gửi
                </label>

                <div class="mt-1 font-medium">

                    {{ $yeuCau->created_at->format('d/m/Y H:i') }}

                </div>

            </div>

        </div>

    </div>


    {{-- Lý do --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm">

        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">

            <h2 class="font-semibold text-lg">
                Lý do yêu cầu
            </h2>

        </div>

        <div class="p-6">

            <div class="rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-5 leading-8">

                {{ $yeuCau->ly_do }}

            </div>

        </div>

    </div>


    {{-- Nút xử lý --}}
    @if($yeuCau->trang_thai=='cho_duyet')

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

        <div class="flex justify-end gap-3">

            {{-- Từ chối --}}
            <form action="{{ route('admin.yeu-cau-luong.tu-choi',$yeuCau->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <button
                    onclick="return confirm('Từ chối yêu cầu này?')"
                    class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">

                    Từ chối

                </button>

            </form>


            {{-- Duyệt --}}
            <form action="{{ route('admin.yeu-cau-luong.duyet',$yeuCau->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <button
                    onclick="return confirm('Xác nhận duyệt yêu cầu?')"
                    class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">

                    Duyệt yêu cầu

                </button>

            </form>

        </div>

    </div>

    @endif

</div>

@endsection