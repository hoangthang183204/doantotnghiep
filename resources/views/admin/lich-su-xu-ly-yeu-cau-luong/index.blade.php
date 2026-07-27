@extends('layouts.admin')


@section('title','Lịch sử xử lý yêu cầu lương')


@section('content')


<div class="space-y-6">


<div class="bg-white rounded-xl shadow p-6">


<h1 class="text-2xl font-bold">

Lịch sử xử lý yêu cầu lương

</h1>


<p class="text-gray-500 mt-1">

Theo dõi toàn bộ thay đổi yêu cầu và phiếu lương

</p>


</div>



{{-- FILTER --}}

<div class="bg-white rounded-xl shadow p-6">


<form method="GET">


<div class="grid grid-cols-4 gap-4">



<div>

<label class="text-sm">
Nhân viên
</label>


<select 
name="nguoi_dung_id"
class="w-full border rounded-lg mt-1">


<option value="">
-- Tất cả --
</option>


@foreach($nhanViens as $nv)

<option 
value="{{ $nv->id }}"
@if(request('nguoi_dung_id')==$nv->id)
selected
@endif
>


{{ $nv->ho_so->ho }}
{{ $nv->ho_so->ten }}


</option>

@endforeach


</select>

</div>




<div>

<label>
Từ ngày
</label>

<input
type="date"
name="tu_ngay"
value="{{request('tu_ngay')}}"
class="w-full border rounded-lg"
/>

</div>




<div>

<label>
Đến ngày
</label>

<input
type="date"
name="den_ngay"
value="{{request('den_ngay')}}"
class="w-full border rounded-lg"
/>

</div>



<div>

<label>
Hành động
</label>


<select
name="hanh_dong"
class="w-full border rounded-lg">


<option value="">
Tất cả
</option>


<option value="tao">
Tạo
</option>


<option value="duyet">
Duyệt
</option>


<option value="cap_nhat">
Cập nhật
</option>


<option value="tu_choi">
Từ chối
</option>


</select>


</div>


</div>


<div class="mt-5">

<button
class="px-5 py-2 bg-blue-600 text-white rounded-lg">

Lọc dữ liệu

</button>

</div>


</form>


</div>





{{-- TABLE --}}

<div class="bg-white rounded-xl shadow overflow-hidden">


<table class="w-full">


<thead class="bg-gray-100">


<tr>

<th class="p-4 text-left">
Nhân viên
</th>


<th>
Hành động
</th>


<th>
Người xử lý
</th>


<th>
Thời gian
</th>


<th>
Ghi chú
</th>


</tr>


</thead>



<tbody>


@foreach($lichSus as $item)


<tr class="border-t">


<td class="p-4">


{{ 
$item->yeuCau->nguoiDung->ho_so->ho 
?? ''
}}

{{ 
$item->yeuCau->nguoiDung->ho_so->ten 
?? ''
}}


</td>


<td>


{{ strtoupper($item->hanh_dong) }}


</td>


<td>


{{ 
$item->nguoiThucHien->ho_so->ho 
?? ''
}}

{{ 
$item->nguoiThucHien->ho_so->ten 
?? ''
}}


</td>



<td>


{{ 
$item->thoi_gian
->format('d/m/Y H:i')
}}


</td>



<td>

{{ $item->ghi_chu }}


</td>


</tr>


@endforeach


</tbody>


</table>


</div>


<div>

{{ $lichSus->links() }}

</div>


</div>


@endsection