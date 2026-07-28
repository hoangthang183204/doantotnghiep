@extends('layouts.employee')

@section('title', 'Lịch sử xử lý yêu cầu lương')

@section('content')

<div class="space-y-8">


{{-- HEADER --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

    <div class="flex justify-between items-center">

    <div>

        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
            Bảng lương của tôi / Lịch sử xử lý
        </p>

        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            Lịch sử xử lý yêu cầu lương
        </h1>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Theo dõi toàn bộ quá trình xử lý các yêu cầu xem xét lương
        </p>

    </div>

    <a href="{{ route('employee.bang-luong.index') }}"
       class="px-4 py-2 border border-gray-300 dark:border-gray-600
              rounded-lg text-gray-700 dark:text-gray-200
              hover:bg-gray-100 dark:hover:bg-gray-700 transition">

        ← Quay lại

    </a>

</div>


</div>




{{-- LIST --}}

@forelse($lichSu as $yeuCau)


<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700">


{{-- CARD HEADER --}}

<div class="p-6 border-b dark:border-gray-700 flex justify-between items-start">


<div>

<h2 class="text-xl font-bold text-gray-800 dark:text-white">

    Yêu cầu #{{ $yeuCau->id }}

</h2>


<div class="flex items-center gap-2 mt-2 text-sm text-gray-500">

<svg class="w-4 h-4"
fill="none"
stroke="currentColor"
viewBox="0 0 24 24">

<path stroke-linecap="round"
stroke-linejoin="round"
stroke-width="2"
d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>

</svg>


{{ $yeuCau->created_at->format('d/m/Y H:i') }}

</div>


</div>



{{-- STATUS --}}

@if($yeuCau->trang_thai == 'da_duyet')

<span class="px-4 py-2 rounded-full bg-green-100 text-green-700 font-semibold text-sm">

✓ Đã duyệt

</span>


@elseif($yeuCau->trang_thai == 'da_cap_nhat')

<span class="px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-semibold text-sm">

✓ Đã cập nhật lương

</span>


@elseif($yeuCau->trang_thai == 'tu_choi')

<span class="px-4 py-2 rounded-full bg-red-100 text-red-700 font-semibold text-sm">

✕ Từ chối

</span>


@else

<span class="px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 font-semibold text-sm">

⏳ Chờ xử lý

</span>


@endif


</div>





{{-- INFO --}}

<div class="p-6 grid md:grid-cols-3 gap-5">


<div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4">

<p class="text-sm text-gray-500">

Loại sai sót

</p>


<p class="mt-2 font-bold text-gray-800 dark:text-white">

{{ str_replace('_',' ', $yeuCau->loai_sai_sot) }}

</p>

</div>




<div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4">

<p class="text-sm text-gray-500">

Thời gian xử lý

</p>


<p class="mt-2 font-bold text-gray-800 dark:text-white">

{{ optional($yeuCau->thoi_gian_duyet)->format('d/m/Y H:i') ?? 'Chưa xử lý' }}

</p>

</div>





<div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4">

<p class="text-sm text-gray-500">

Người xử lý

</p>


<p class="mt-2 font-bold text-gray-800 dark:text-white">


@if($yeuCau->nguoiDuyet)

{{ $yeuCau->nguoiDuyet->ho_so->ho ?? '' }}

{{ $yeuCau->nguoiDuyet->ho_so->ten ?? '' }}


@else

Chưa có


@endif


</p>


</div>


</div>





{{-- CONTENT --}}

<div class="px-6 pb-6 space-y-5">


<div>

<p class="font-semibold text-gray-700 dark:text-white mb-2">

Nội dung yêu cầu

</p>


<div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-5 text-gray-700 dark:text-gray-200">

{{ $yeuCau->ly_do }}

</div>


</div>





@if($yeuCau->phan_hoi)


<div>

<p class="font-semibold text-gray-700 dark:text-white mb-2">

Phản hồi xử lý

</p>


<div class="bg-blue-50 dark:bg-blue-900/30 rounded-xl p-5 text-blue-700 dark:text-blue-300">

{{ $yeuCau->phan_hoi }}

</div>


</div>


@endif


</div>






{{-- TIMELINE --}}

<div class="bg-gray-50 dark:bg-gray-900/30 p-6">


<h3 class="font-bold text-lg text-gray-800 dark:text-white mb-6">

Lịch sử thao tác

</h3>



<div class="space-y-6">


@foreach($yeuCau->lichSuXuLy as $item)


<div class="flex gap-4">


<div class="flex flex-col items-center">

<div class="w-4 h-4 rounded-full bg-blue-600 ring-4 ring-blue-100"></div>

<div class="w-px flex-1 bg-gray-300 mt-2"></div>

</div>



<div class="flex-1 bg-white dark:bg-gray-800 rounded-xl p-5 shadow">


<div class="flex justify-between">


<h4 class="font-bold text-gray-800 dark:text-white">

{{ ucfirst($item->hanh_dong) }}

</h4>


<span class="text-sm text-gray-500">

{{ $item->thoi_gian->format('d/m/Y H:i') }}

</span>


</div>




<p class="mt-3 text-gray-600 dark:text-gray-300">

{{ $item->ghi_chu ?? 'Không có ghi chú' }}

</p>




<div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4">

    <p class="text-sm text-gray-500">
        Người thực hiện
    </p>


    <p class="mt-2 font-bold text-gray-800 dark:text-white">

        @if($item->nguoiThucHien && $item->nguoiThucHien->ho_so)

            {{ $item->nguoiThucHien->ho_so->ho }}
            {{ $item->nguoiThucHien->ho_so->ten }}

        @else

            Không xác định

        @endif

    </p>

</div>



</div>


</div>


@endforeach


</div>


</div>



</div>



@empty


<div class="bg-white dark:bg-gray-800 rounded-xl shadow p-10 text-center">


<div class="text-gray-400 text-lg">

Chưa có yêu cầu xem xét lương nào

</div>


</div>



@endforelse
@if($lichSu->hasPages())

<div class="mt-8 bg-white dark:bg-gray-800 rounded-xl shadow p-4">

    {{ $lichSu->links() }}

</div>

@endif


</div>


@endsection