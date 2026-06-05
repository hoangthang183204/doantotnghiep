@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-3xl mx-auto">

    <div class="bg-white dark:bg-gray-800 rounded-2xl border p-6 shadow-sm">

        <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">
            Sửa ứng viên
        </h1>

        <form method="POST" action="{{ route('admin.ung_vien.update', $ungVien->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <input name="ho" value="{{ $ungVien->ho }}"
                   class="w-full px-4 py-3 rounded-xl border dark:bg-gray-900">

            <input name="ten" value="{{ $ungVien->ten }}"
                   class="w-full px-4 py-3 rounded-xl border dark:bg-gray-900">

            <input name="email" value="{{ $ungVien->email }}"
                   class="w-full px-4 py-3 rounded-xl border dark:bg-gray-900">

            <input name="so_dien_thoai" value="{{ $ungVien->so_dien_thoai }}"
                   class="w-full px-4 py-3 rounded-xl border dark:bg-gray-900">

            <input name="ma_ho_so" value="{{ $ungVien->ma_ho_so }}"
                   class="w-full px-4 py-3 rounded-xl border dark:bg-gray-900">

            <input name="luong_mong_muon" value="{{ $ungVien->luong_mong_muon }}"
                   class="w-full px-4 py-3 rounded-xl border dark:bg-gray-900">

            <button class="w-full bg-amber-500 hover:bg-amber-600 text-white py-3 rounded-xl font-semibold">
                Cập nhật
            </button>

        </form>

    </div>

</div>
@endsection