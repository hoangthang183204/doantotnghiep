@extends('layouts.admin')
@section('title', 'Thông tin tài chính')
@section('content')
<div class="hr-page max-w-xl space-y-4 text-gray-900 dark:text-gray-100">
    <a href="{{ route('admin.ho-so.show', $hoSo->id) }}" class="text-sm text-blue-600">← Hồ sơ</a>
    <h1 class="text-2xl font-bold">Tài khoản và mã số thuế</h1>
    <p class="text-sm text-gray-600">Kế toán chỉ sửa được thông tin phục vụ chi lương và kê khai thuế.</p>
    @if(session('success'))<div class="p-3 rounded-lg bg-green-50 text-green-700">{{ session('success') }}</div>@endif
    <form method="POST" action="{{ route('admin.ho-so.tai-chinh.update', $hoSo->id) }}" class="bg-white dark:bg-gray-800 rounded-xl border p-4 space-y-3">
        @csrf
        @method('PUT')
        <label class="block text-sm">Chủ tài khoản<input name="chu_tai_khoan" value="{{ old('chu_tai_khoan', $hoSo->chu_tai_khoan) }}" class="mt-1 w-full rounded-lg border-gray-300"></label>
        <label class="block text-sm">Số tài khoản<input name="so_tai_khoan" value="{{ old('so_tai_khoan', $hoSo->so_tai_khoan) }}" class="mt-1 w-full rounded-lg border-gray-300"></label>
        <label class="block text-sm">Ngân hàng<input name="ten_ngan_hang" value="{{ old('ten_ngan_hang', $hoSo->ten_ngan_hang) }}" class="mt-1 w-full rounded-lg border-gray-300"></label>
        <label class="block text-sm">Chi nhánh<input name="chi_nhanh_ngan_hang" value="{{ old('chi_nhanh_ngan_hang', $hoSo->chi_nhanh_ngan_hang) }}" class="mt-1 w-full rounded-lg border-gray-300"></label>
        <label class="block text-sm">Mã số thuế<input name="ma_so_thue" value="{{ old('ma_so_thue', $hoSo->ma_so_thue) }}" class="mt-1 w-full rounded-lg border-gray-300"></label>
        <button class="px-4 py-2 rounded-lg bg-blue-600 text-white">Lưu</button>
    </form>
</div>
@endsection
