@extends('layouts.employee')
@section('title', 'Đánh giá của tôi')
@section('content')
<div class="hr-page space-y-6 text-gray-900 dark:text-gray-100">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Đánh giá của tôi</h1>
    @forelse($ketQuas as $kq)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex justify-between"><b>{{ $kq->ky->ten ?? 'Kỳ đánh giá' }}</b><span>{{ $kq->diem }} điểm · {{ $kq->xep_loai }}</span></div>
            <p class="text-sm text-gray-500 mt-1">{{ $kq->nhan_xet }}</p>
            <ul class="mt-2 text-sm space-y-1">
                @foreach($kq->mucTieus as $muc)
                    <li>{{ $muc->ten }} — trọng số {{ $muc->trong_so }}% — điểm {{ $muc->diem ?? '—' }}</li>
                @endforeach
            </ul>
        </div>
    @empty
        <p class="text-gray-500">Chưa có kỳ đánh giá nào.</p>
    @endforelse
</div>
@endsection
