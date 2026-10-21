@extends('layouts.admin')
@section('title', 'Quyết toán thuế TNCN')
@section('content')
<div class="hr-page space-y-6 text-gray-900 dark:text-gray-100">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Thuế TNCN</h1>
            <p class="text-sm text-gray-500 mt-1">Tờ khai khấu trừ tháng (05/KK-TNCN) và quyết toán năm (05/QTT-TNCN). Quyết toán năm tính lại thuế trên thu nhập cả năm rồi so với số đã khấu trừ từng tháng.</p>
        </div>
        <form method="GET" class="flex gap-2 items-end">
            <label class="text-sm">Tháng<input type="number" name="thang" min="1" max="12" value="{{ $thang }}" class="block rounded-lg border-gray-300 w-20"></label>
            <label class="text-sm">Năm<input type="number" name="nam" value="{{ $nam }}" class="block rounded-lg border-gray-300 w-24"></label>
            <button class="px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm">Xem</button>
        </form>
    </div>
    <div class="flex gap-3 text-sm">
        <a class="text-blue-600 underline" target="_blank" href="{{ route('admin.quyet-toan-thue.kk', ['nam' => $nam, 'thang' => $thang]) }}">In mẫu 05/KK-TNCN tháng {{ $thang }}/{{ $nam }}</a>
        <a class="text-blue-600 underline" target="_blank" href="{{ route('admin.quyet-toan-thue.qtt', ['nam' => $nam]) }}">In mẫu 05/QTT-TNCN năm {{ $nam }}</a>
    </div>
    @include('admin.quyet-toan-thue._bang', ['tieuDe' => 'Khấu trừ tháng '.$thang.'/'.$nam, 'dong' => $dongThang, 'coChenhLech' => false])
    @include('admin.quyet-toan-thue._bang', ['tieuDe' => 'Quyết toán năm '.$nam, 'dong' => $dongNam, 'coChenhLech' => true])
</div>
@endsection
