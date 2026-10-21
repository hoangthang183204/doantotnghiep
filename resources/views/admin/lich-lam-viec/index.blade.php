@extends('layouts.admin')
@section('title', 'Lịch làm việc')
@section('content')
@php
    $thu = [1 => 'Thứ 2', 2 => 'Thứ 3', 3 => 'Thứ 4', 4 => 'Thứ 5', 5 => 'Thứ 6', 6 => 'Thứ 7', 7 => 'Chủ nhật'];
@endphp
<div class="hr-page space-y-6 text-gray-900 dark:text-gray-100">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Ngày lễ và lịch làm việc</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Giờ chuẩn, nghỉ trưa, ngày công và ngày lễ dùng để tính công một lần check-in / check-out.</p>
    </div>
    @if(session('success'))<div class="p-3 rounded-lg bg-green-50 text-green-700">{{ session('success') }}</div>@endif
    <form method="POST" action="{{ route('admin.lich-lam-viec.update') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 grid md:grid-cols-3 gap-4">
        @csrf
        <h2 class="md:col-span-3 font-semibold">Giờ chuẩn và ngày công</h2>
        <label class="text-sm">Giờ vào<input type="time" name="gio_vao" value="{{ substr($cauHinh->gio_vao, 0, 5) }}" class="mt-1 w-full rounded-lg border-gray-300" required></label>
        <label class="text-sm">Giờ ra<input type="time" name="gio_ra" value="{{ substr($cauHinh->gio_ra, 0, 5) }}" class="mt-1 w-full rounded-lg border-gray-300" required></label>
        <label class="text-sm">Nghỉ trưa từ<input type="time" name="gio_nghi_trua_bat_dau" value="{{ substr($cauHinh->gio_nghi_trua_bat_dau, 0, 5) }}" class="mt-1 w-full rounded-lg border-gray-300" required></label>
        <label class="text-sm">Nghỉ trưa đến<input type="time" name="gio_nghi_trua_ket_thuc" value="{{ substr($cauHinh->gio_nghi_trua_ket_thuc, 0, 5) }}" class="mt-1 w-full rounded-lg border-gray-300" required></label>
        <label class="text-sm">Số giờ chuẩn/ngày<input type="number" step="0.5" name="so_gio_chuan" value="{{ $cauHinh->so_gio_chuan }}" class="mt-1 w-full rounded-lg border-gray-300" required></label>
        <label class="text-sm">Ngày công chuẩn/tháng<input type="number" name="ngay_cong_chuan" value="{{ $cauHinh->ngay_cong_chuan }}" class="mt-1 w-full rounded-lg border-gray-300" required></label>
        <div class="md:col-span-3 flex flex-wrap gap-3 text-sm">
            @foreach($thu as $so => $ten)
                <label class="inline-flex items-center gap-2"><input type="checkbox" name="ngay_lam[]" value="{{ $so }}" @checked(in_array($so, $ngayLam))> {{ $ten }}</label>
            @endforeach
        </div>
        <div><button class="px-4 py-2 rounded-lg bg-blue-600 text-white">Lưu lịch</button></div>
    </form>

    <div class="grid lg:grid-cols-3 gap-4">
        <form method="POST" action="{{ route('admin.lich-lam-viec.ngay-le.store') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 space-y-3">
            @csrf
            <h2 class="font-semibold">Thêm ngày lễ</h2>
            <input name="ten" placeholder="Tên ngày lễ" class="w-full rounded-lg border-gray-300" required>
            <input type="date" name="ngay" class="w-full rounded-lg border-gray-300" required>
            <select name="loai" class="w-full rounded-lg border-gray-300">
                <option value="quoc_gia">Lễ quốc gia</option>
                <option value="cong_ty">Lễ riêng công ty</option>
            </select>
            <label class="text-sm flex gap-2"><input type="checkbox" name="co_huong_luong" value="1" checked> Hưởng lương</label>
            <label class="text-sm flex gap-2"><input type="checkbox" name="lap_lai_hang_nam" value="1"> Lặp lại mỗi năm (theo ngày dương)</label>
            <button class="px-4 py-2 rounded-lg bg-blue-600 text-white">Thêm</button>
        </form>
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/60"><tr><th class="px-3 py-2 text-left">Ngày</th><th class="px-3 py-2 text-left">Tên</th><th class="px-3 py-2">Loại</th><th class="px-3 py-2">Lương</th><th></th></tr></thead>
                <tbody>
                    @foreach($ngayLes as $le)
                    <tr class="border-t">
                        <td class="px-3 py-2">{{ $le->ngay->format('d/m/Y') }}</td>
                        <td class="px-3 py-2">{{ $le->ten }}</td>
                        <td class="px-3 py-2 text-center">{{ $le->loai === 'cong_ty' ? 'Công ty' : 'Quốc gia' }}</td>
                        <td class="px-3 py-2 text-center">{{ $le->co_huong_luong ? 'Có' : 'Không' }}</td>
                        <td class="px-3 py-2 text-right">
                            <form method="POST" action="{{ route('admin.lich-lam-viec.ngay-le.destroy', $le->id) }}">@csrf @method('DELETE')<button class="text-red-600">Xóa</button></form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 space-y-3">
        <h2 class="font-semibold">Vị trí GPS được phép chấm công</h2>
        <p class="text-sm text-gray-500">Khi có ít nhất một vị trí, điện thoại phải bật GPS và đứng trong bán kính thì mới quét mặt được. Chưa khai báo vị trí thì chỉ cần khuôn mặt.</p>
        <form method="POST" action="{{ route('admin.lich-lam-viec.dia-diem.store') }}" class="grid md:grid-cols-4 gap-3">
            @csrf
            <input name="address" placeholder="Tên địa điểm" class="rounded-lg border-gray-300" required>
            <input name="latitude" placeholder="Vĩ độ" class="rounded-lg border-gray-300" required>
            <input name="longitude" placeholder="Kinh độ" class="rounded-lg border-gray-300" required>
            <input name="allowed_radius" type="number" value="150" class="rounded-lg border-gray-300" required>
            <button class="md:col-span-4 w-fit px-4 py-2 rounded-lg bg-blue-600 text-white">Thêm vị trí</button>
        </form>
        <ul class="text-sm divide-y">
            @foreach($diaDiems as $diem)
                <li class="py-2 flex justify-between gap-3">
                    <span>{{ $diem->address }} — {{ $diem->latitude }}, {{ $diem->longitude }} ({{ $diem->allowed_radius }} m)</span>
                    <form method="POST" action="{{ route('admin.lich-lam-viec.dia-diem.destroy', $diem->id) }}">@csrf @method('DELETE')<button class="text-red-600">Xóa</button></form>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
