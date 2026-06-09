@extends('layouts.admin')

@section('content')
    <div class="container p-6">
        <h1 class="text-2xl mb-4">Chi tiết đơn tuyển dụng</h1>
        <div class="bg-white rounded shadow p-4">
            <div class="mb-2"><strong>Mã yêu cầu:</strong> {{ $item->ma_yeu_cau ?? $item->id }}</div>
            <div class="mb-2"><strong>Phòng ban:</strong> {{ optional($item->phongBan)->ten ?? '-' }}</div>
            <div class="mb-2"><strong>Chức vụ:</strong> {{ optional($item->chucVu)->ten ?? $item->chuc_vu }}</div>
            <div class="mb-2"><strong>Số lượng:</strong> {{ $item->so_luong ?? '-' }}</div>
            <div class="mb-2"><strong>Mô tả:</strong> {!! nl2br(e($item->mo_ta ?? '-')) !!}</div>
            <div class="mt-4">
                <a href="{{ route('admin.duyetdon.tuyendung.index') }}" class="px-3 py-1 border rounded">Quay lại</a>
            </div>
        </div>
    </div>
@endsection
