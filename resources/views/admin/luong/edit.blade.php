@extends('layouts.admin')

@section('content')

<div class="min-h-screen p-6 bg-gray-50 dark:bg-slate-900">

<div class="max-w-4xl mx-auto">

    {{-- BREADCRUMB --}}
    <div class="mb-3 text-sm text-gray-500 dark:text-slate-400">
        <span class="text-blue-600 dark:text-sky-400 font-medium">Lương</span>
        <span class="mx-2">&gt;</span>
        <span>Sửa lương</span>
    </div>

    {{-- HEADER --}}
    <div class="mb-4 flex items-center gap-3 p-4 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm">

        <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-950 flex items-center justify-center text-blue-600 dark:text-sky-400">
            <i class="fa-solid fa-money-bill-wave"></i>
        </div>

        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                Sửa lương cơ bản
            </h2>
            <p class="text-sm text-gray-500 dark:text-slate-400">
                Cập nhật thông tin lương nhân viên
            </p>
        </div>

    </div>

    {{-- FORM --}}
    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm p-5">

        <form method="POST" action="{{ route('admin.luong.update', $luong->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- NHÂN VIÊN --}}
                <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-slate-200">
                        Nhân viên
                    </label>

                    <select name="nguoi_dung_id"
                        class="w-full rounded-lg border border-gray-300 dark:border-slate-600
                               bg-white dark:bg-slate-900 text-gray-900 dark:text-white
                               px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-sky-500">

                        @foreach($nhanViens as $nv)
                            <option value="{{ $nv->id }}"
                                {{ $luong->nguoi_dung_id == $nv->id ? 'selected' : '' }}>
                                {{ $nv->ho_ten }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- HỢP ĐỒNG --}}
                <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-slate-200">
                        Hợp đồng
                    </label>

                    <select name="hop_dong_lao_dong_id"
                        class="w-full rounded-lg border border-gray-300 dark:border-slate-600
                               bg-white dark:bg-slate-900 text-gray-900 dark:text-white
                               px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-sky-500">

                        @foreach($hopDongs as $hd)
                            <option value="{{ $hd->id }}"
                                {{ $luong->hop_dong_lao_dong_id == $hd->id ? 'selected' : '' }}>
                                {{ $hd->so_hop_dong }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- INPUT --}}
                @foreach([
                    'luong_co_ban' => 'Lương cơ bản',
                    'phu_cap' => 'Phụ cấp',
                    'tien_thuong' => 'Thưởng',
                    'tien_phat' => 'Tiền phạt'
                ] as $field => $label)

                <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-slate-200">
                        {{ $label }}
                    </label>

                    <input type="number"
                        name="{{ $field }}"
                        value="{{ $luong->$field }}"
                        class="w-full rounded-lg border border-gray-300 dark:border-slate-600
                               bg-white dark:bg-slate-900 text-gray-900 dark:text-white
                               px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-sky-500">
                </div>

                @endforeach

            </div>

            {{-- BUTTON --}}
            <div class="flex justify-end gap-3 mt-6">

                <a href="{{ route('admin.luong.index') }}"
                   class="px-4 py-2 rounded-lg bg-gray-100 dark:bg-slate-900 text-gray-700 dark:text-slate-300 hover:opacity-80">
                    Huỷ
                </a>

                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">
                    Lưu thay đổi
                </button>

            </div>

        </form>

    </div>

</div>

</div>

@endsection