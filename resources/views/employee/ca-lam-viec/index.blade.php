@extends('layouts.employee')
@section('title', 'Ca của tôi')
@section('content')
@php
    $tenLoai = ['sang' => 'Sáng', 'chieu' => 'Chiều', 'toi' => 'Tối', 'gay' => 'Ca gãy', 'hanh_chinh' => 'Hành chính'];
    $tenTt = ['cho_duyet' => 'Chờ HR duyệt', 'da_duyet' => 'Đã duyệt', 'tu_choi' => 'Từ chối'];
    $thu = ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'];
@endphp
<div class="hr-page space-y-6 text-gray-900 dark:text-gray-100">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Ca của tôi</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 max-w-2xl">
            HR xếp ca cho từng ngày. Dưới đây là lịch tuần này. Muốn đổi, chọn ngày và ca mới rồi gửi. Khi HR duyệt, lịch ngày đó đổi sang ca mới.
        </p>
    </div>
    @if(session('success'))<div class="p-3 rounded-lg bg-green-50 text-green-700">{{ session('success') }}</div>@endif
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 divide-y">
        @forelse($lich as $dong)
            <div class="px-4 py-3 flex justify-between gap-3 text-sm">
                <span>{{ $thu[$dong->ngay->dayOfWeek] }}, {{ $dong->ngay->format('d/m/Y') }}</span>
                <b>{{ $dong->ca->ten ?? 'Chưa rõ ca' }} {{ $dong->ca ? substr($dong->ca->gio_bat_dau,0,5).'–'.substr($dong->ca->gio_ket_thuc,0,5) : '' }}</b>
            </div>
        @empty
            <p class="p-4 text-gray-500 text-sm">Tuần này HR chưa xếp ca cho bạn.</p>
        @endforelse
    </div>
    <form method="POST" action="{{ route('employee.ca.doi') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 space-y-3">
        @csrf
        <h2 class="font-semibold">Đăng ký đổi ca</h2>
        <p class="text-sm text-gray-500">Yêu cầu chưa có hiệu lực cho đến khi HR bấm duyệt.</p>
        <label class="block text-sm">Ngày muốn đổi
            <input type="date" name="ngay" class="mt-1 w-full rounded-lg border-gray-300" required>
        </label>
        <label class="block text-sm">Ca muốn chuyển sang
            <select name="ca_moi_id" class="mt-1 w-full rounded-lg border-gray-300" required>
                @foreach($cas as $ca)
                    <option value="{{ $ca->id }}">{{ $ca->ten }} · {{ $tenLoai[$ca->loai] ?? 'Hành chính' }} · {{ substr($ca->gio_bat_dau,0,5) }}–{{ substr($ca->gio_ket_thuc,0,5) }}</option>
                @endforeach
            </select>
        </label>
        <label class="block text-sm">Lý do
            <textarea name="ly_do" class="mt-1 w-full rounded-lg border-gray-300" placeholder="Ví dụ: xin làm full time ngày này" required></textarea>
        </label>
        <button class="px-4 py-2 rounded-lg bg-blue-600 text-white">Gửi yêu cầu</button>
    </form>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <h2 class="font-semibold mb-2">Yêu cầu đã gửi</h2>
        @forelse($yeuCaus as $yc)
            <div class="py-2 border-t text-sm flex flex-wrap justify-between gap-2">
                <span>{{ $yc->ngay->format('d/m/Y') }} → {{ $yc->caMoi->ten ?? 'Ca mới' }}</span>
                <span class="px-2 py-0.5 rounded-full text-xs {{ $yc->trang_thai === 'da_duyet' ? 'bg-green-100 text-green-700' : ($yc->trang_thai === 'tu_choi' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-800') }}">{{ $tenTt[$yc->trang_thai] ?? $yc->trang_thai }}</span>
            </div>
        @empty
            <p class="text-sm text-gray-500">Chưa gửi yêu cầu đổi ca.</p>
        @endforelse
    </div>
</div>
@endsection
