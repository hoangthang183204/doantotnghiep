@extends('layouts.employee')
@section('title', 'Tài sản của tôi')
@section('content')
@php
    $tenLoai = ['laptop' => 'Laptop', 'dien_thoai' => 'Điện thoại', 'the' => 'Thẻ từ', 'dong_phuc' => 'Đồng phục', 'khac' => 'Khác'];
@endphp
<div class="hr-page space-y-6 text-gray-900 dark:text-gray-100">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tài sản của tôi</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Những món HR đang cấp cho bạn. Khi nghỉ việc cần trả lại đủ các món còn giữ.</p>
    </div>
    <div class="grid gap-3 md:grid-cols-2">
        @forelse($dangGiu as $cap)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex justify-between gap-3">
                    <div>
                        <p class="font-semibold">{{ $cap->taiSan->ten ?? 'Tài sản' }}</p>
                        <p class="text-sm text-gray-500">{{ $tenLoai[$cap->taiSan->loai ?? ''] ?? 'Tài sản' }} · Mã {{ $cap->taiSan->ma ?? '—' }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700 h-fit">Đang giữ</span>
                </div>
                <p class="mt-3 text-sm text-gray-600">Nhận ngày {{ $cap->ngay_cap?->format('d/m/Y') }}@if($cap->taiSan?->serial) · Serial {{ $cap->taiSan->serial }}@endif</p>
                @if($cap->ghi_chu)<p class="mt-1 text-sm text-gray-500">{{ $cap->ghi_chu }}</p>@endif
            </div>
        @empty
            <p class="text-sm text-gray-500">Bạn chưa được cấp tài sản nào.</p>
        @endforelse
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <h2 class="font-semibold mb-3">Lịch sử cấp phát và thu hồi</h2>
        <div class="divide-y text-sm">
            @forelse($lichSu as $cap)
                <div class="py-2 flex flex-wrap justify-between gap-2">
                    <span>{{ $cap->taiSan->ten ?? 'Tài sản' }} · nhận {{ $cap->ngay_cap?->format('d/m/Y') }}</span>
                    <span class="{{ $cap->trang_thai === 'dang_cap' ? 'text-blue-700' : 'text-gray-500' }}">
                        {{ $cap->trang_thai === 'dang_cap' ? 'Đang giữ' : 'Đã trả '.($cap->ngay_thu?->format('d/m/Y') ?? '') }}
                    </span>
                </div>
            @empty
                <p class="text-gray-500">Chưa có lịch sử.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
