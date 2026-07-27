@extends('layouts.admin')

@section('title','Cập nhật phiếu lương')

@section('content')

<div class="space-y-6">

    <div class="bg-white rounded-xl shadow p-6">

        <div class="flex justify-between">

            <div>

                <h1 class="text-2xl font-bold">
                    Cập nhật phiếu lương
                </h1>

                <p class="text-gray-500">
                    Chỉnh sửa lại thông tin lương theo yêu cầu của nhân viên.
                </p>

            </div>

            <a href="{{ route('admin.yeu-cau-luong.show',$yeuCau->id) }}"
               class="px-4 py-2 bg-gray-600 text-white rounded-lg">

                Quay lại

            </a>

        </div>

    </div>
    <div class="bg-white rounded-xl shadow p-6">

    <h2 class="text-lg font-semibold border-b pb-3 mb-5">
        Thông tin yêu cầu
    </h2>

    <div class="grid grid-cols-2 gap-6">

        <div>
            <label class="text-sm text-gray-500">Nhân viên</label>

            <div class="mt-1 font-semibold">
                {{ $yeuCau->nguoiDung->ho_ten ?? $yeuCau->nguoiDung->ten_dang_nhap }}
            </div>
        </div>

        <div>
            <label class="text-sm text-gray-500">Kỳ lương</label>

            <div class="mt-1">
                Tháng {{ $luong->luong_thang }}/{{ $luong->luong_nam }}
            </div>
        </div>

        <div>
            <label class="text-sm text-gray-500">Loại sai sót</label>

            <div class="mt-1">

                @switch($yeuCau->loai_sai_sot)

                    @case('cham_cong')
                        Sai chấm công
                        @break

                    @case('tang_ca')
                        Sai tăng ca
                        @break

                    @case('phu_cap')
                        Sai phụ cấp
                        @break

                    @case('khau_tru')
                        Sai khấu trừ
                        @break

                    @default
                        Khác

                @endswitch

            </div>
        </div>

        <div>
            <label class="text-sm text-gray-500">Ngày gửi</label>

            <div class="mt-1">
                {{ $yeuCau->created_at->format('d/m/Y H:i') }}
            </div>
        </div>

    </div>

    <div class="mt-6">

        <label class="text-sm text-gray-500">
            Lý do nhân viên
        </label>

        <div class="mt-2 p-4 rounded-lg bg-gray-100 border leading-7">
            {{ $yeuCau->ly_do }}
        </div>

    </div>

</div>

<form method="POST"
      action="{{ route('admin.yeu-cau-luong.update',$yeuCau->id) }}">

@csrf
@method('PUT')

@php

switch($yeuCau->loai_sai_sot){

    case 'cham_cong':
        $fields=['cham_cong'];
        break;

    case 'tang_ca':
        $fields=['tang_ca'];
        break;

    case 'phu_cap':
        $fields=['phu_cap'];
        break;

    case 'khau_tru':
        $fields=['khau_tru'];
        break;

    case 'cham_cong_tang_ca':
        $fields=['cham_cong','tang_ca'];
        break;

    case 'cham_cong_phu_cap':
        $fields=['cham_cong','phu_cap'];
        break;

    case 'cham_cong_khau_tru':
        $fields=['cham_cong','khau_tru'];
        break;

    case 'tang_ca_phu_cap':
        $fields=['tang_ca','phu_cap'];
        break;

    case 'tang_ca_khau_tru':
        $fields=['tang_ca','khau_tru'];
        break;

    case 'phu_cap_khau_tru':
        $fields=['phu_cap','khau_tru'];
        break;

    case 'cham_cong_tang_ca_phu_cap':
        $fields=['cham_cong','tang_ca','phu_cap'];
        break;

    case 'cham_cong_tang_ca_khau_tru':
        $fields=['cham_cong','tang_ca','khau_tru'];
        break;

    case 'cham_cong_phu_cap_khau_tru':
        $fields=['cham_cong','phu_cap','khau_tru'];
        break;

    case 'tang_ca_phu_cap_khau_tru':
        $fields=['tang_ca','phu_cap','khau_tru'];
        break;

    case 'tat_ca':
        $fields=['cham_cong','tang_ca','phu_cap','khau_tru'];
        break;

    default:
        $fields=[];
}

@endphp
<div class="bg-white rounded-xl shadow p-6">

<div class="grid grid-cols-2 gap-6">

@if(in_array('cham_cong', $fields))

<div>
    <label>Số ngày công</label>
    <input
    type="number"
    step="0.5"
    min="0"
    max="22"
    name="so_ngay_cong"
    class="w-full border rounded-lg p-3"
    value="{{ old('so_ngay_cong',$luong->so_ngay_cong) }}">
</div>

@endif


@if(in_array('tang_ca', $fields))

<div>
    <label class="block mb-2 font-medium">
        Số giờ tăng ca
    </label>

    <input
        type="number"
        step="0.5"
        name="gio_tang_ca"
        class="w-full border rounded-lg p-3"
        value="{{ old('gio_tang_ca',$luong->gio_tang_ca) }}">

</div>

@endif


@if(in_array('phu_cap', $fields))

<div>
    <label>Tổng phụ cấp</label>
    <input
        type="number"
        name="tong_phu_cap"
        class="w-full border rounded-lg p-3"
        value="{{ old('tong_phu_cap',$luong->tong_phu_cap) }}">
</div>

@endif

</div>
    {{-- phản hồi HR --}}
           <div class="mt-6">

    <label class="block mb-2 font-medium">
        Phản hồi
    </label>

    <textarea
        name="phan_hoi"
        rows="6"
        class="w-full border rounded-lg p-3">{{ old('phan_hoi', $yeuCau->phan_hoi) }}</textarea>

</div>

<div class="flex justify-end mt-8">

<button
    type="submit"
    class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">

Lưu cập nhật

</button>

</div>

</div>

</form>

</div>

@endsection