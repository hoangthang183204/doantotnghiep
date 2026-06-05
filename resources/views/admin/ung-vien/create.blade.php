@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-3xl mx-auto">

    <div class="bg-white dark:bg-gray-800 rounded-2xl border p-6 shadow-sm">

        <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">
            Thêm ứng viên
        </h1>

        <form method="POST" action="{{ route('admin.ung_vien.store') }}" class="space-y-4">
            @csrf

            <input name="ho" placeholder="Họ"
                   class="w-full px-4 py-3 rounded-xl border dark:bg-gray-900">

            <input name="ten" placeholder="Tên"
                   class="w-full px-4 py-3 rounded-xl border dark:bg-gray-900">

            <input name="email" placeholder="Email"
                   class="w-full px-4 py-3 rounded-xl border dark:bg-gray-900">

            <input name="so_dien_thoai" placeholder="SĐT"
                   class="w-full px-4 py-3 rounded-xl border dark:bg-gray-900">

            <input name="ma_ho_so" placeholder="Mã hồ sơ"
                   class="w-full px-4 py-3 rounded-xl border dark:bg-gray-900">

            <input name="luong_mong_muon" placeholder="Lương mong muốn"
                   class="w-full px-4 py-3 rounded-xl border dark:bg-gray-900">

            <select name="tin_tuyen_dung_id"
                    class="w-full px-4 py-3 rounded-xl border dark:bg-gray-900">
                <option value="">-- Tin tuyển dụng --</option>
                @foreach($tinTuyenDungs as $tin)
                    <option value="{{ $tin->id }}">{{ $tin->tieu_de }}</option>
                @endforeach
            </select>

            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold">
                Lưu
            </button>

        </form>

    </div>

</div>
@endsection