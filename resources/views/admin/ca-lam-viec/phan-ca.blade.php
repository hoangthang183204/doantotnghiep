@extends('layouts.admin')
@section('title', 'Xếp ca')
@section('content')
@php
    $tenNv = fn ($nv) => trim(($nv->hoSo->ho ?? '').' '.($nv->hoSo->ten ?? '')) ?: $nv->ten_dang_nhap;
    $thu = ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'];
@endphp
<div class="hr-page space-y-6 text-gray-900 dark:text-gray-100">
    <a href="{{ route('admin.ca-lam-viec.index') }}" class="text-sm text-blue-600">← Danh sách ca</a>
    <div>
        <h1 class="text-2xl font-bold">Xếp ca {{ \Carbon\Carbon::parse($tuNgay)->format('d/m') }} – {{ \Carbon\Carbon::parse($denNgay)->format('d/m/Y') }}</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 max-w-3xl">
            Mỗi ô là một ngày của một nhân viên. Chọn ca rồi trang tự lưu. Chọn “Bỏ ca” nếu ngày đó chưa cần xếp.
            Nhân viên thấy lịch này ở mục Ca của tôi và có thể gửi yêu cầu đổi; HR duyệt ở trang Ca làm việc.
        </p>
    </div>
    @if(session('success'))<div class="p-3 rounded-lg bg-green-50 text-green-700">{{ session('success') }}</div>@endif
    <form method="GET" class="flex flex-wrap gap-2 items-end">
        <label class="text-sm">Từ ngày
            <input type="date" name="tu_ngay" value="{{ $tuNgay }}" class="block rounded-lg border-gray-300">
        </label>
        <label class="text-sm">Đến ngày
            <input type="date" name="den_ngay" value="{{ $denNgay }}" class="block rounded-lg border-gray-300">
        </label>
        <button class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">Xem</button>
    </form>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700/60">
                <tr>
                    <th class="px-3 py-2 text-left sticky left-0 bg-gray-50 dark:bg-gray-700">Nhân viên</th>
                    @foreach($ngayList as $ngay)
                        <th class="px-2 py-2 text-left whitespace-nowrap">
                            <div>{{ $thu[$ngay->dayOfWeek] }}</div>
                            <div class="font-normal text-gray-500">{{ $ngay->format('d/m') }}</div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($nhanViens as $nv)
                <tr class="border-t">
                    <td class="px-3 py-2 whitespace-nowrap sticky left-0 bg-white dark:bg-gray-800">{{ $tenNv($nv) }}</td>
                    @foreach($ngayList as $ngay)
                        @php $pc = ($phanCongs[$nv->id.'|'.$ngay->toDateString()] ?? collect())->first(); @endphp
                        <td class="px-2 py-2">
                            <form method="POST" action="{{ route('admin.ca-lam-viec.store-phan-ca') }}">
                                @csrf
                                <input type="hidden" name="nguoi_dung_id" value="{{ $nv->id }}">
                                <input type="hidden" name="ngay" value="{{ $ngay->toDateString() }}">
                                <select name="ca_lam_viec_id" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-xs max-w-[9rem]">
                                    <option value="">Bỏ ca</option>
                                    @foreach($cas as $ca)
                                        <option value="{{ $ca->id }}" @selected($pc && (int) $pc->ca_lam_viec_id === (int) $ca->id)>
                                            {{ $ca->ten }} {{ substr($ca->gio_bat_dau,0,5) }}–{{ substr($ca->gio_ket_thuc,0,5) }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
