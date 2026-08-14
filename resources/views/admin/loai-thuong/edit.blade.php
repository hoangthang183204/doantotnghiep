@extends('layouts.admin')

@section('title', 'Sửa loại thưởng')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-slate-900 py-8 px-6">
    <div class="max-w-5xl mx-auto space-y-8">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Sửa loại thưởng</h1>
                <p class="mt-2 text-gray-500 dark:text-slate-400">{{ $loaiThuong->ten }}</p>
            </div>
            <a href="{{ route('admin.loai-thuong.index') }}"
               class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-100 dark:hover:bg-slate-700 transition shadow-sm">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </a>
        </div>

        @include('layouts.partials.alerts')

        <form action="{{ route('admin.loai-thuong.update', $loaiThuong->id) }}" method="POST"
              class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-gray-200 dark:border-slate-700 p-8 space-y-7">
            @csrf
            @method('PUT')

            @include('admin.loai-thuong._form', ['loaiThuong' => $loaiThuong])

            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 dark:border-slate-700">
                <a href="{{ route('admin.loai-thuong.index') }}"
                   class="px-6 py-3 rounded-xl bg-gray-200 hover:bg-gray-300 dark:bg-slate-700 dark:hover:bg-slate-600 transition">Huỷ</a>
                <button type="submit"
                        class="px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-lg transition">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Cập nhật
                </button>
            </div>
        </form>

        <p class="text-xs text-gray-400">
            <i class="fa-solid fa-circle-info mr-1"></i>
            Thay đổi ở đây chỉ ảnh hưởng tới các kỳ lương được tính lại sau này. Phiếu lương đã chốt vẫn giữ nguyên
            tên và số tiền thưởng tại thời điểm chốt.
        </p>

    </div>
</div>
@endsection
