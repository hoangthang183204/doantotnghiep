@extends('layouts.admin')
@section('title', 'Đánh giá KPI')
@section('content')
<div class="hr-page space-y-6 text-gray-900 dark:text-gray-100">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Đánh giá hiệu suất</h1>
    <p class="text-sm text-gray-500">Điểm quý/năm được nhân vào thưởng cuối năm. Chưa có điểm thì thưởng giữ nguyên cách tính cũ.</p>
    <form method="POST" action="{{ route('admin.danh-gia.store') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 flex flex-wrap gap-3 items-end">
        @csrf
        <input name="ten" placeholder="Tên kỳ, ví dụ Quý 4/2026" class="rounded-lg border-gray-300" required>
        <select name="loai" class="rounded-lg border-gray-300"><option value="quy">Quý</option><option value="nam">Năm</option></select>
        <input type="date" name="tu_ngay" class="rounded-lg border-gray-300" required>
        <input type="date" name="den_ngay" class="rounded-lg border-gray-300" required>
        <button class="px-4 py-2 rounded-lg bg-blue-600 text-white">Tạo kỳ</button>
    </form>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 divide-y">
        @forelse($kys as $ky)
            <a class="flex justify-between px-4 py-3 text-sm" href="{{ route('admin.danh-gia.show', $ky->id) }}">
                <span>{{ $ky->ten }} · {{ $ky->loai === 'nam' ? 'Năm' : 'Quý' }}</span>
                <span>{{ $ky->ket_quas_count }} người · {{ $ky->tu_ngay->format('d/m') }}–{{ $ky->den_ngay->format('d/m/Y') }}</span>
            </a>
        @empty
            <p class="p-4 text-gray-500">Chưa có kỳ đánh giá.</p>
        @endforelse
    </div>
</div>
@endsection
