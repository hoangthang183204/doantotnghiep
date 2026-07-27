@extends('layouts.admin')

@section('title', 'Yêu cầu xem xét lương')

@section('content')

<div class="space-y-6">


{{-- HEADER --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

    <div class="flex items-center justify-between">

        <div>
            <div class="flex items-center gap-3">

                <div class="p-3 rounded-xl bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z"/>

                    </svg>
                </div>


                <div>

                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                        Yêu cầu xem xét lương
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Quản lý các yêu cầu khiếu nại và điều chỉnh phiếu lương
                    </p>

                </div>


            </div>

        </div>


        <div class="text-right">

            <p class="text-sm text-gray-500">
                Tổng yêu cầu
            </p>

            <p class="text-2xl font-bold text-blue-600">
                {{ $yeuCaus->total() }}
            </p>

        </div>


    </div>

</div>




{{-- TABLE --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">


<div class="overflow-x-auto">

<table class="min-w-full">


<thead>

<tr class="bg-gray-50 dark:bg-gray-700 border-b
           border-gray-200 dark:border-gray-600">


<th class="px-5 py-4 text-left text-xs uppercase">
STT
</th>

<th class="px-5 py-4 text-left text-xs uppercase">
Nhân viên
</th>


<th class="px-5 py-4 text-left text-xs uppercase">
Kỳ lương
</th>


<th class="px-5 py-4 text-left text-xs uppercase">
Sai sót
</th>


<th class="px-5 py-4 text-left text-xs uppercase">
Lý do
</th>


<th class="px-5 py-4 text-left text-xs uppercase">
Ngày gửi
</th>


<th class="px-5 py-4 text-center text-xs uppercase">
Trạng thái
</th>


<th class="px-5 py-4 text-center text-xs uppercase">
Thao tác
</th>


</tr>

</thead>



<tbody>


@forelse($yeuCaus as $index=>$yc)


<tr class="border-b dark:border-gray-700
           hover:bg-gray-50 dark:hover:bg-gray-700 transition">


<td class="px-5 py-4 font-semibold">

{{ $yeuCaus->firstItem()+$index }}

</td>



<td class="px-5 py-4">

<div class="flex items-center gap-3">


<div class="w-10 h-10 rounded-full 
            bg-blue-100 text-blue-700
            flex items-center justify-center
            font-bold">

{{ strtoupper(substr($yc->nguoiDung->ten_dang_nhap,0,1)) }}

</div>


<div>

<p class="font-semibold">
{{ $yc->nguoiDung->ho_ten 
?? $yc->nguoiDung->ten_dang_nhap }}
</p>

<p class="text-xs text-gray-500">
Nhân viên
</p>

</div>


</div>


</td>




<td class="px-5 py-4">

<span class="font-medium">

{{ $yc->luongNhanVien->luong_thang }}/{{ $yc->luongNhanVien->luong_nam }}

</span>


<p class="text-xs text-gray-500">
Kỳ lương
</p>

</td>





<td class="px-5 py-4">

<div class="flex flex-wrap gap-1">


@if(str_contains($yc->loai_sai_sot ?? '','cham_cong'))
<span class="px-3 py-1 rounded-full text-xs
bg-blue-100 text-blue-700">
Chấm công
</span>
@endif



@if(str_contains($yc->loai_sai_sot ?? '','tang_ca'))
<span class="px-3 py-1 rounded-full text-xs
bg-purple-100 text-purple-700">
Tăng ca
</span>
@endif



@if(str_contains($yc->loai_sai_sot ?? '','phu_cap'))
<span class="px-3 py-1 rounded-full text-xs
bg-green-100 text-green-700">
Phụ cấp
</span>
@endif



@if(str_contains($yc->loai_sai_sot ?? '','khau_tru'))
<span class="px-3 py-1 rounded-full text-xs
bg-red-100 text-red-700">
Khấu trừ
</span>
@endif



@if($yc->loai_sai_sot=='tat_ca')

<span class="px-3 py-1 rounded-full text-xs
bg-gray-100 text-gray-700">
Tất cả
</span>

@endif


</div>


</td>





<td class="px-5 py-4 max-w-xs">

<p class="truncate">
{{ $yc->ly_do }}
</p>

</td>





<td class="px-5 py-4">

<p class="font-medium">

{{ $yc->created_at->format('d/m/Y') }}

</p>

<p class="text-xs text-gray-500">

{{ $yc->created_at->format('H:i') }}

</p>


</td>





<td class="px-5 py-4 text-center">


@if($yc->trang_thai=='cho_duyet')

<span class="px-3 py-1 rounded-full text-xs
bg-yellow-100 text-yellow-700">
Chờ duyệt
</span>


@elseif($yc->trang_thai=='da_duyet')

<span class="px-3 py-1 rounded-full text-xs
bg-blue-100 text-blue-700">
Đã duyệt
</span>


@elseif($yc->trang_thai=='dang_sua')

<span class="px-3 py-1 rounded-full text-xs
bg-orange-100 text-orange-700">
Đang sửa
</span>


@elseif($yc->trang_thai=='da_cap_nhat')

<span class="px-3 py-1 rounded-full text-xs
bg-green-100 text-green-700">
Đã cập nhật
</span>


@elseif($yc->trang_thai=='tu_choi')

<span class="px-3 py-1 rounded-full text-xs
bg-red-100 text-red-700">
Từ chối
</span>

@endif


</td>




<td class="px-5 py-4 text-center">


<a href="{{ route('admin.yeu-cau-luong.show',$yc->id) }}"
class="inline-flex p-2 rounded-lg
text-blue-600 hover:bg-blue-100 transition">


<svg class="w-5 h-5"
fill="none"
stroke="currentColor"
viewBox="0 0 24 24">

<path stroke-width="2"
stroke-linecap="round"
stroke-linejoin="round"
d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>

<path stroke-width="2"
stroke-linecap="round"
stroke-linejoin="round"
d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>

</svg>


</a>


</td>


</tr>



@empty


<tr>

<td colspan="8"
class="text-center py-12 text-gray-500">

Chưa có yêu cầu nào

</td>

</tr>


@endforelse


</tbody>


</table>


</div>


<div class="px-6 py-4 border-t">

{{ $yeuCaus->links() }}

</div>


</div>


</div>


@endsection