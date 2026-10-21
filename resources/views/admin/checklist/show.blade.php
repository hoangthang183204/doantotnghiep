@extends('layouts.admin')
@section('title', 'Checklist')
@section('content')
@php $ten = fn ($user) => $user ? (trim(($user->hoSo->ho ?? '').' '.($user->hoSo->ten ?? '')) ?: $user->ten_dang_nhap) : '—'; @endphp
<div class="hr-page space-y-6 text-gray-900 dark:text-gray-100">
    <a href="{{ route('admin.checklist.index') }}" class="text-sm text-blue-600">← Danh sách</a>
    <h1 class="text-2xl font-bold">{{ $phieu->loai === 'onboarding' ? 'Nhận việc' : 'Nghỉ việc' }} — {{ $ten($phieu->nguoiDung) }}</h1>
    @if(session('success'))<div class="p-3 rounded-lg bg-green-50 text-green-700">{{ session('success') }}</div>@endif
    @foreach($phieu->viecs as $viec)
        <form method="POST" action="{{ route('admin.checklist.viec', $viec->id) }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 grid md:grid-cols-2 gap-3 text-sm">
            @csrf @method('PUT')
            <div class="md:col-span-2 font-medium">{{ $viec->ten }}</div>
            <select name="trang_thai" class="rounded-lg border-gray-300">
                <option value="chua_xong" @selected($viec->trang_thai==='chua_xong')>Chưa xong</option>
                <option value="xong" @selected($viec->trang_thai==='xong')>Xong</option>
            </select>
            <select name="nguoi_phu_trach_id" class="rounded-lg border-gray-300">
                <option value="">Người phụ trách</option>
                @foreach($nguoiPhuTrachs as $nv)
                    <option value="{{ $nv->id }}" @selected($viec->nguoi_phu_trach_id==$nv->id)>{{ $ten($nv) }}</option>
                @endforeach
            </select>
            <input type="date" name="han_hoan_thanh" value="{{ optional($viec->han_hoan_thanh)->toDateString() }}" class="rounded-lg border-gray-300">
            <input name="ghi_chu" value="{{ $viec->ghi_chu }}" placeholder="Ghi chú" class="rounded-lg border-gray-300">
            <button class="md:col-span-2 w-fit px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">Lưu</button>
        </form>
    @endforeach
    <form method="POST" action="{{ route('admin.checklist.viec.store', $phieu->id) }}" class="bg-white rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 flex flex-wrap gap-2">
        @csrf
        <input name="ten" placeholder="Việc mới" class="rounded-lg border-gray-300 flex-1" required>
        <button class="px-3 py-2 rounded-lg bg-blue-600 text-white">Thêm việc</button>
    </form>
</div>
@endsection
