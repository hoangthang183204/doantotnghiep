@extends('layouts.admin')

@section('title', 'Sửa khấu trừ khác')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-slate-900 py-8 px-6">
    <div class="max-w-4xl mx-auto space-y-8">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Sửa khoản khấu trừ khác
                </h1>
                <p class="mt-2 text-gray-500 dark:text-slate-400">
                    Cập nhật thông tin khoản khấu trừ của nhân viên.
                </p>
            </div>

            <a href="{{ route('admin.khau-tru-khac.index', ['thang' => $khauTru->thang, 'nam' => $khauTru->nam]) }}"
                class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-100 dark:hover:bg-slate-700 transition shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
                Quay lại
            </a>
        </div>

        @include('layouts.partials.alerts')

        <form action="{{ route('admin.khau-tru-khac.update', $khauTru->id) }}"
            method="POST"
            class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-gray-200 dark:border-slate-700 p-8 space-y-7">

            @csrf
            @method('PUT')

            {{-- Nhân viên --}}
            <div>
                <label
                    class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">
                    Nhân viên <span class="text-red-500">*</span>
                </label>

                <select
                    name="nguoi_dung_id"
                    required
                    class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    @foreach($nhanViens as $nv)
                        <option value="{{ $nv->id }}"
                            @selected(old('nguoi_dung_id', $khauTru->nguoi_dung_id) == $nv->id)>

                            {{ trim(($nv->ho_so->ho ?? '') . ' ' . ($nv->ho_so->ten ?? '')) ?: $nv->ten_dang_nhap }}

                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tháng / Năm --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label
                        class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">
                        Tháng áp dụng <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="thang"
                        class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">

                        @for($m=1;$m<=12;$m++)
                            <option value="{{ $m }}"
                                @selected(old('thang',$khauTru->thang)==$m)>
                                Tháng {{ $m }}
                            </option>
                        @endfor

                    </select>
                </div>

                <div>
                    <label
                        class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">
                        Năm <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="number"
                        name="nam"
                        value="{{ old('nam',$khauTru->nam) }}"
                        min="2000"
                        max="2100"
                        class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                </div>

            </div>

            {{-- Loại / Số tiền --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label
                        class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">
                        Loại khấu trừ <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="loai"
                        class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">

                        @foreach(\App\Models\KhauTruKhac::$loaiLabels as $key=>$label)
                            <option value="{{ $key }}"
                                @selected(old('loai',$khauTru->loai)===$key)>
                                {{ $label }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label
                        class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">
                        Số tiền (VNĐ) <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="number"
                        name="so_tien"
                        value="{{ old('so_tien',(int)$khauTru->so_tien) }}"
                        min="0"
                        step="1000"
                        required
                        class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                </div>

            </div>

            {{-- Lý do --}}
            <div>
                <label
                    class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">
                    Lý do
                </label>

                <input
                    type="text"
                    name="ly_do"
                    value="{{ old('ly_do',$khauTru->ly_do) }}"
                    maxlength="255"
                    class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Trạng thái --}}
            <div>
                <label
                    class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">
                    Trạng thái
                </label>

                <select
                    name="trang_thai"
                    class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">

                    <option value="hieu_luc"
                        @selected(old('trang_thai',$khauTru->trang_thai)=='hieu_luc')>
                        Hiệu lực
                    </option>

                    <option value="huy"
                        @selected(old('trang_thai',$khauTru->trang_thai)=='huy')>
                        Đã huỷ (không áp dụng)
                    </option>

                </select>
            </div>

            {{-- Footer --}}
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 dark:border-slate-700">

                <a href="{{ route('admin.khau-tru-khac.index', ['thang'=>$khauTru->thang,'nam'=>$khauTru->nam]) }}"
                    class="px-6 py-3 rounded-xl bg-gray-200 hover:bg-gray-300 dark:bg-slate-700 dark:hover:bg-slate-600 transition">
                    Huỷ
                </a>

                <button
                    class="px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-lg transition">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>
                    Cập nhật
                </button>

            </div>

        </form>

    </div>
</div>
@endsection