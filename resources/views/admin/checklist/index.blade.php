@extends('layouts.admin')
@section('title', 'Onboarding / Offboarding')
@section('content')
@php
    $ten = fn ($user) => trim(($user->hoSo->ho ?? '').' '.($user->hoSo->ten ?? '')) ?: ($user->ten_dang_nhap ?? '');
@endphp
<div class="hr-page space-y-6 text-gray-900 dark:text-gray-100">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Checklist nhận việc / nghỉ việc</h1>
    @if(session('success'))<div class="p-3 rounded-lg bg-green-50 text-green-700">{{ session('success') }}</div>@endif
    <form method="POST" action="{{ route('admin.checklist.store') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 flex flex-wrap gap-3 items-end">
        @csrf
        <label class="text-sm">Nhân viên
            <select name="nguoi_dung_id" class="block rounded-lg border-gray-300" required>
                @foreach($nhanViens as $nv)<option value="{{ $nv->id }}">{{ $ten($nv) }}</option>@endforeach
            </select>
        </label>
        <label class="text-sm">Loại
            <select name="loai" class="block rounded-lg border-gray-300">
                <option value="onboarding">Nhận việc</option>
                <option value="offboarding">Nghỉ việc</option>
            </select>
        </label>
        <label class="text-sm">Ngày bắt đầu<input type="date" name="ngay_bat_dau" value="{{ now()->toDateString() }}" class="block rounded-lg border-gray-300"></label>
        <button class="px-4 py-2 rounded-lg bg-blue-600 text-white">Mở checklist</button>
    </form>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 divide-y">
        @forelse($phieus as $phieu)
            <a href="{{ route('admin.checklist.show', $phieu->id) }}" class="flex justify-between px-4 py-3 text-sm hover:bg-gray-50 dark:bg-gray-700/60">
                <span>{{ $ten($phieu->nguoiDung) }} — {{ $phieu->loai === 'onboarding' ? 'Nhận việc' : 'Nghỉ việc' }}</span>
                <span>{{ $phieu->viecs->where('trang_thai','xong')->count() }}/{{ $phieu->viecs->count() }} · {{ $phieu->trang_thai }}</span>
            </a>
        @empty
            <p class="p-4 text-gray-500">Chưa có phiếu.</p>
        @endforelse
    </div>
</div>
@endsection
