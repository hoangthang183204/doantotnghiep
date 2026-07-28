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
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm">

    {{-- Header --}}
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-200">

        <div>
            <h2 class="text-xl font-bold text-gray-800">
                Bộ lọc tìm kiếm
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Tìm kiếm lịch sử xử lý yêu cầu lương theo nhân viên, thời gian và hành động.
            </p>
        </div>

        <div class="hidden lg:flex gap-3">

            <div class="px-4 py-2 rounded-full bg-blue-50 text-blue-600 text-sm font-semibold">
                {{ $lichSus->total() }} kết quả
            </div>

        </div>

    </div>

    {{-- Body --}}
    <div class="p-6">

        <form method="GET">

            <div class="grid grid-cols-12 gap-4">

                {{-- Nhân viên --}}
                <div class="col-span-12 lg:col-span-4">

                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        Nhân viên
                    </label>

                    <select
                        name="nguoi_dung_id"
                        class="w-full h-11 rounded-xl border border-gray-300 bg-white px-4 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">

                        <option value="">Tất cả nhân viên</option>

                        @foreach($nhanViens as $nv)

                            <option
                                value="{{ $nv->id }}"
                                {{ request('nguoi_dung_id') == $nv->id ? 'selected' : '' }}>

                                {{ $nv->ho_so->ho }} {{ $nv->ho_so->ten }}

                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Từ ngày --}}
                <div class="col-span-12 md:col-span-6 lg:col-span-2">

                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        Từ ngày
                    </label>

                    <input
                        type="date"
                        name="tu_ngay"
                        value="{{ request('tu_ngay') }}"
                        class="w-full h-11 rounded-xl border border-gray-300 px-4 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">

                </div>

                {{-- Đến ngày --}}
                <div class="col-span-12 md:col-span-6 lg:col-span-2">

                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        Đến ngày
                    </label>

                    <input
                        type="date"
                        name="den_ngay"
                        value="{{ request('den_ngay') }}"
                        class="w-full h-11 rounded-xl border border-gray-300 px-4 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">

                </div>

                {{-- Hành động --}}
                <div class="col-span-12 lg:col-span-2">

                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        Hành động
                    </label>

                    <select
                        name="hanh_dong"
                        class="w-full h-11 rounded-xl border border-gray-300 px-4 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">

                        <option value="">Tất cả</option>

                        <option value="tao"
                            {{ request('hanh_dong')=='tao' ? 'selected' : '' }}>
                            Tạo
                        </option>

                        <option value="duyet"
                            {{ request('hanh_dong')=='duyet' ? 'selected' : '' }}>
                            Duyệt
                        </option>

                        <option value="cap_nhat"
                            {{ request('hanh_dong')=='cap_nhat' ? 'selected' : '' }}>
                            Cập nhật
                        </option>

                        <option value="tu_choi"
                            {{ request('hanh_dong')=='tu_choi' ? 'selected' : '' }}>
                            Từ chối
                        </option>

                    </select>

                </div>

                {{-- Buttons --}}
                <div class="col-span-12 lg:col-span-2 flex items-end gap-2">

                    <a href="{{ url()->current() }}"
                       class="flex-1 h-11 flex items-center justify-center rounded-xl border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 font-medium transition">

                        Reset

                    </a>

                    <button
                        type="submit"
                        class="flex-1 h-11 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-sm transition">

                        Lọc

                    </button>

                </div>

            </div>

        </form>

    </div>

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