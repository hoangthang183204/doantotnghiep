@extends('layouts.employee')
@section('title', 'Ca của tôi')
@section('content')
<div class="hr-page space-y-6 text-gray-900 dark:text-gray-100">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Ca của tôi</h1>
    @if(session('success'))<div class="p-3 rounded-lg bg-green-50 text-green-700">{{ session('success') }}</div>@endif
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 divide-y">
        @forelse($lich as $dong)
            <div class="px-4 py-3 flex justify-between text-sm">
                <span>{{ $dong->ngay->format('d/m/Y') }}</span>
                <b>{{ $dong->ca->ten ?? '' }} {{ $dong->ca ? substr($dong->ca->gio_bat_dau,0,5).'–'.substr($dong->ca->gio_ket_thuc,0,5) : '' }}</b>
            </div>
        @empty
            <p class="p-4 text-gray-500 text-sm">Tuần này chưa được xếp ca.</p>
        @endforelse
    </div>
    <form method="POST" action="{{ route('employee.ca.doi') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 space-y-3">
        @csrf
        <h2 class="font-semibold">Đăng ký đổi ca</h2>
        <input type="date" name="ngay" class="w-full rounded-lg border-gray-300" required>
        <select name="ca_moi_id" class="w-full rounded-lg border-gray-300" required>
            @foreach($cas as $ca)
                <option value="{{ $ca->id }}">{{ $ca->ten }} ({{ $ca->loai ?? 'hanh_chinh' }}) {{ substr($ca->gio_bat_dau,0,5) }}–{{ substr($ca->gio_ket_thuc,0,5) }}</option>
            @endforeach
        </select>
        <textarea name="ly_do" class="w-full rounded-lg border-gray-300" placeholder="Lý do" required></textarea>
        <button class="px-4 py-2 rounded-lg bg-blue-600 text-white">Gửi yêu cầu</button>
    </form>
    <div class="text-sm text-gray-600">
        @foreach($yeuCaus as $yc)
            <div class="py-1">{{ $yc->ngay->format('d/m') }} → {{ $yc->caMoi->ten ?? '' }}: {{ $yc->trang_thai }}</div>
        @endforeach
    </div>
</div>
@endsection
