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

                   @if($yeuCau->trang_thai == 'cho_duyet')

<span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm font-semibold">
    Chờ duyệt
</span>

@elseif($yeuCau->trang_thai == 'da_duyet')

<span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold">
    Đã duyệt
</span>

@elseif($yeuCau->trang_thai == 'dang_sua')

<span class="px-3 py-1 rounded-full bg-orange-100 text-orange-700 text-sm font-semibold">
    Đang cập nhật lương
</span>

@elseif($yeuCau->trang_thai == 'da_cap_nhat')

<span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
    Đã cập nhật
</span>

@elseif($yeuCau->trang_thai == 'tu_choi')

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
{{-- Loại sai sót --}}
<div>

    <label class="text-sm text-gray-500">
        Loại sai sót
    </label>


<div class="flex flex-wrap gap-2">

@if(str_contains($yeuCau->loai_sai_sot ?? '', 'cham_cong'))
<span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700">
Sai chấm công
</span>
@endif


@if(str_contains($yeuCau->loai_sai_sot ?? '','tang_ca'))
<span class="px-3 py-1 rounded-full bg-purple-100 text-purple-700">
Sai tăng ca
</span>
@endif


@if(str_contains($yeuCau->loai_sai_sot ?? '','phu_cap'))
<span class="px-3 py-1 rounded-full bg-green-100 text-green-700">
Sai phụ cấp
</span>
@endif


@if(str_contains($yeuCau->loai_sai_sot ?? '','khau_tru'))
<span class="px-3 py-1 rounded-full bg-red-100 text-red-700">
Sai khấu trừ
</span>
@endif
@if($yeuCau->loai_sai_sot == 'tat_ca')

<span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700">
    Sai tất cả
</span>

@endif
</div>

</div>
<div>

    <label class="text-sm text-gray-500">
        Người xử lý
    </label>

    <div class="mt-1 font-medium">
        {{ optional($yeuCau->nguoiDuyet)->ho_ten ?? '-' }}
    </div>

</div>
<div>

    <label class="text-sm text-gray-500">
        Thời gian xử lý
    </label>

    <div class="mt-1 font-medium">
        {{ $yeuCau->thoi_gian_duyet?->format('d/m/Y H:i') ?? '-' }}
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
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm">

    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">

        <h2 class="font-semibold text-lg">
            Thông tin phiếu lương
        </h2>

    </div>

    <div class="grid grid-cols-2 gap-6 p-6">

        <div>

            <label class="text-sm text-gray-500">
                Lương thực nhận
            </label>

            <div class="mt-1 font-bold text-green-600 text-lg">
                {{ number_format($yeuCau->luongNhanVien->luong_thuc_nhan) }} đ
            </div>

        </div>

        <div>

            <label class="text-sm text-gray-500">
                Tổng lương
            </label>

            <div class="mt-1">
                {{ number_format($yeuCau->luongNhanVien->tong_luong) }} đ
            </div>

        </div>

        <div>

            <label class="text-sm text-gray-500">
                Tổng khấu trừ
            </label>

            <div class="mt-1 text-red-600">
                {{ number_format($yeuCau->luongNhanVien->tong_khau_tru) }} đ
            </div>

        </div>

        <div>

            <label class="text-sm text-gray-500">
                Ngày công
            </label>

            <div class="mt-1">
                {{ $yeuCau->luongNhanVien->so_ngay_cong }}/{{ $yeuCau->luongNhanVien->so_ngay_cong_chuan }} công
            </div>

        </div>
        <div>

    <label class="text-sm text-gray-500">
        Giờ tăng ca
    </label>

    <div class="mt-1">
        {{ $yeuCau->luongNhanVien->gio_tang_ca }} giờ
    </div>

</div>

<div>

    <label class="text-sm text-gray-500">
        Nghỉ phép
    </label>

    <div class="mt-1">
        {{ $yeuCau->luongNhanVien->ngay_nghi_phep }} ngày
    </div>

</div>

<div>

    <label class="text-sm text-gray-500">
        Nghỉ không phép
    </label>

    <div class="mt-1">
        {{ $yeuCau->luongNhanVien->ngay_nghi_khong_phep }} ngày
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

    @if($yeuCau->phan_hoi)

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm">

    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
        <h2 class="font-semibold text-lg">
            Phản hồi của phòng nhân sự
        </h2>
    </div>

    <div class="p-6">

        <div class="rounded-lg border border-blue-200 bg-blue-50 p-5 leading-8">
            {{ $yeuCau->phan_hoi }}
        </div>

    </div>

</div>

@endif
{{-- Lịch sử xử lý --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm">

    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">

        <h2 class="font-semibold text-lg text-gray-800 dark:text-white">
            Lịch sử xử lý yêu cầu
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Theo dõi toàn bộ thao tác xử lý của yêu cầu lương
        </p>

    </div>


    <div class="p-6">

        @forelse($yeuCau->lichSuXuLy as $lichSu)

            <div class="relative pl-8 pb-8">


                {{-- đường timeline --}}
                @if(!$loop->last)
                    <div class="absolute left-3 top-6 bottom-0 w-0.5 bg-gray-200"></div>
                @endif


                {{-- icon --}}
                <div class="absolute left-0 top-1">

                    @if($lichSu->hanh_dong == 'duyet')

                        <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center text-white text-xs">
                            ✓
                        </div>


                    @elseif($lichSu->hanh_dong == 'tu_choi')

                        <div class="w-6 h-6 rounded-full bg-red-500 flex items-center justify-center text-white text-xs">
                            ×
                        </div>


                    @elseif($lichSu->hanh_dong == 'cap_nhat')

                        <div class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs">
                            ↻
                        </div>


                    @else

                        <div class="w-6 h-6 rounded-full bg-gray-500 flex items-center justify-center text-white text-xs">
                            +
                        </div>

                    @endif

                </div>



                {{-- nội dung --}}
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">


                    <div class="flex justify-between">


                        <div>


                            <h3 class="font-semibold text-gray-800 dark:text-white">


                                @if($lichSu->hanh_dong == 'duyet')

                                    Đã duyệt yêu cầu

                                @elseif($lichSu->hanh_dong == 'tu_choi')

                                    Đã từ chối yêu cầu

                                @elseif($lichSu->hanh_dong == 'cap_nhat')

                                    Đã cập nhật phiếu lương

                                @else

                                    Tạo yêu cầu

                                @endif


                            </h3>



                            <p class="text-sm text-gray-500 mt-1">

                                Người thực hiện:

                                <span class="font-medium text-gray-700">

                                    {{ 
                                        $lichSu->nguoiThucHien->ho_ten 
                                        ?? $lichSu->nguoiThucHien->ten_dang_nhap
                                    }}

                                </span>

                            </p>


                        </div>


                        <div class="text-sm text-gray-500">

                            {{ $lichSu->thoi_gian->format('d/m/Y H:i') }}

                        </div>


                    </div>



                    @if($lichSu->ghi_chu)

                        <div class="mt-3 p-3 rounded-lg bg-white border">

                            <span class="font-medium">
                                Ghi chú:
                            </span>

                            {{ $lichSu->ghi_chu }}

                        </div>

                    @endif



                </div>


            </div>


        @empty


            <div class="text-center text-gray-500 py-5">

                Chưa có lịch sử xử lý

            </div>


        @endforelse


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
            <form action="{{ route('admin.yeu-cau-luong.edit', $yeuCau->id) }}"
      method="GET">

    <button
        onclick="return confirm('Xác nhận xử lý yêu cầu này?')"
        class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">

        Duyệt yêu cầu

    </button>

</form>

        </div>

    </div>

    @endif

</div>

@endsection