@extends('layouts.admin')
@section('title', 'Tài sản')
@section('content')
@php $ten = fn ($user) => $user ? (trim(($user->hoSo->ho ?? '').' '.($user->hoSo->ten ?? '')) ?: $user->ten_dang_nhap) : '—'; @endphp
<div class="hr-page space-y-6 text-gray-900 dark:text-gray-100">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tài sản cấp phát</h1>
    @if(session('success'))<div class="p-3 rounded-lg bg-green-50 text-green-700">{{ session('success') }}</div>@endif
    <form method="POST" action="{{ route('admin.tai-san.store') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 grid md:grid-cols-5 gap-3">
        @csrf
        <input name="ma" placeholder="Mã" class="rounded-lg border-gray-300" required>
        <input name="ten" placeholder="Tên" class="rounded-lg border-gray-300" required>
        <select name="loai" class="rounded-lg border-gray-300">
            <option value="laptop">Laptop</option><option value="dien_thoai">Điện thoại</option>
            <option value="the">Thẻ</option><option value="dong_phuc">Đồng phục</option><option value="khac">Khác</option>
        </select>
        <input name="serial" placeholder="Serial" class="rounded-lg border-gray-300">
        <button class="rounded-lg bg-blue-600 text-white">Thêm</button>
    </form>
    <div class="space-y-3">
        @foreach($taiSans as $ts)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-sm">
            <div class="flex justify-between gap-3">
                <div><b>{{ $ts->ma }}</b> — {{ $ts->ten }} <span class="text-gray-500">({{ $ts->loai }})</span></div>
                <span>{{ $ts->tinh_trang }}</span>
            </div>
            @if($ts->capPhatHienTai)
                <div class="mt-2 text-gray-600">Đang cấp cho {{ $ten($ts->capPhatHienTai->nguoiDung) }} từ {{ $ts->capPhatHienTai->ngay_cap->format('d/m/Y') }}</div>
                <form method="POST" action="{{ route('admin.tai-san.thu-hoi', $ts->id) }}" class="mt-2">@csrf<button class="text-red-600">Thu hồi</button></form>
            @else
                <form method="POST" action="{{ route('admin.tai-san.cap-phat', $ts->id) }}" class="mt-2 flex flex-wrap gap-2">
                    @csrf
                    <select name="nguoi_dung_id" class="rounded-lg border-gray-300" required>
                        @foreach($nhanViens as $nv)<option value="{{ $nv->id }}">{{ $ten($nv) }}</option>@endforeach
                    </select>
                    <input type="date" name="ngay_cap" value="{{ now()->toDateString() }}" class="rounded-lg border-gray-300" required>
                    <button class="px-3 py-1 rounded bg-emerald-600 text-white">Cấp phát</button>
                </form>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection
