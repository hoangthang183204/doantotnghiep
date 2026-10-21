@extends('layouts.admin')
@section('title', $ky->ten)
@section('content')
<div class="hr-page space-y-6 text-gray-900 dark:text-gray-100">
    <a href="{{ route('admin.danh-gia.index') }}" class="text-sm text-blue-600">← Các kỳ</a>
    <h1 class="text-2xl font-bold">{{ $ky->ten }}</h1>
    @if(session('success'))<div class="p-3 rounded-lg bg-green-50 text-green-700">{{ session('success') }}</div>@endif
    @foreach($ketQuas as $kq)
        <div class="text-sm bg-white border rounded-xl p-3">
            {{ trim(($kq->nguoiDung->hoSo->ho ?? '').' '.($kq->nguoiDung->hoSo->ten ?? '')) }} —
            <b>{{ $kq->diem }}</b> ({{ $kq->xep_loai }})
            @if($kq->diem !== null)
                · đề xuất tăng lương {{ max(0, round(($kq->diem - 80) * 0.5, 1)) }}% nếu từ 80 điểm trở lên
            @endif
        </div>
    @endforeach
    <form method="POST" action="{{ route('admin.danh-gia.ket-qua', $ky->id) }}" class="bg-white border rounded-xl p-4 space-y-3">
        @csrf
        <select name="nguoi_dung_id" class="w-full rounded-lg border-gray-300" required>
            @foreach($nhanViens as $nv)
                <option value="{{ $nv->id }}">{{ trim(($nv->hoSo->ho ?? '').' '.($nv->hoSo->ten ?? '')) ?: $nv->ten_dang_nhap }}</option>
            @endforeach
        </select>
        @for($i = 0; $i < 3; $i++)
            <div class="grid md:grid-cols-4 gap-2">
                <input name="muc_tieu[{{ $i }}][ten]" placeholder="Mục tiêu {{ $i + 1 }}" class="rounded-lg border-gray-300 md:col-span-2" {{ $i === 0 ? 'required' : '' }}>
                <input name="muc_tieu[{{ $i }}][trong_so]" type="number" placeholder="Trọng số %" value="{{ $i === 0 ? 100 : 0 }}" class="rounded-lg border-gray-300">
                <input name="muc_tieu[{{ $i }}][diem]" type="number" step="0.1" placeholder="Điểm" class="rounded-lg border-gray-300">
                <input name="muc_tieu[{{ $i }}][muc_tieu]" placeholder="Chỉ tiêu" class="rounded-lg border-gray-300 md:col-span-2">
                <input name="muc_tieu[{{ $i }}][thuc_dat]" placeholder="Thực đạt" class="rounded-lg border-gray-300 md:col-span-2">
            </div>
        @endfor
        <textarea name="nhan_xet" class="w-full rounded-lg border-gray-300" placeholder="Nhận xét"></textarea>
        <button class="px-4 py-2 rounded-lg bg-blue-600 text-white">Lưu đánh giá</button>
    </form>
</div>
@endsection
