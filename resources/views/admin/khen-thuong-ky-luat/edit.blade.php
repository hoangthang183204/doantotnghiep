@extends('layouts.admin')

@section('content')
    @php
        $hoSo = $ktkl->hoSo;
    @endphp

    <div class="min-h-screen bg-gray-100 dark:bg-slate-900 py-8">

        <div class="max-w-5xl mx-auto px-6">

            {{-- BANNER (GIỐNG CREATE) --}}
            <div
                class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white shadow-xl mb-8">

                <div class="absolute right-0 top-0 opacity-10 text-[180px] pr-8 pt-2">
                    <i class="fa-solid fa-award"></i>
                </div>

                <div class="relative flex items-center justify-between px-8 py-8">

                    <div class="flex items-center gap-5">

                        {{-- LOGO --}}
                        <div
                            class="w-20 h-20 rounded-3xl bg-white/20 backdrop-blur flex items-center justify-center border border-white/30 overflow-hidden">

                            @if (file_exists(public_path('images/logo.png')))
                                <img src="{{ asset('images/logo.png') }}" class="w-10 h-10 object-contain">
                            @else
                                <i class="fa-solid fa-building text-4xl"></i>
                            @endif

                        </div>

                        <div>

                            <h1 class="text-3xl font-bold">
                                Cập nhật quyết định
                            </h1>

                            <p class="text-blue-100 mt-2">
                                Chỉnh sửa khen thưởng / kỷ luật nhân sự
                            </p>

                        </div>

                    </div>

                    <a href="{{ route('admin.khen-thuong-ky-luat.index') }}"
                        class="px-5 py-3 rounded-xl bg-white/20 hover:bg-white/30 transition backdrop-blur">

                        ← Quay lại

                    </a>

                </div>
            </div>

            <form action="{{ route('admin.khen-thuong-ky-luat.update', $ktkl->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div
                    class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-gray-200 dark:border-slate-700 overflow-hidden">

                    {{-- HEADER --}}
                    <div class="px-8 py-6 border-b border-gray-200 dark:border-slate-700 flex items-center gap-4">

                        <div class="w-14 h-14 rounded-2xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center">
                            <i class="fa-solid fa-file-pen text-blue-600 text-2xl"></i>
                        </div>

                        <div>
                            <h2 class="text-xl font-bold dark:text-white">
                                Thông tin quyết định
                            </h2>

                            <p class="text-gray-500 text-sm">
                                Chỉnh sửa dữ liệu
                            </p>
                        </div>

                    </div>

                    {{-- BODY --}}
                    <div class="p-8 space-y-7">

                        {{-- NHÂN VIÊN --}}
                        <div>

                            <label class="flex items-center gap-2 mb-2 font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-user text-blue-600"></i>
                                Nhân viên
                            </label>

                            <select name="ho_so_id"
                                class="w-full rounded-2xl border border-gray-300 bg-white dark:bg-slate-900 dark:border-slate-700 dark:text-white px-4 py-3">

                                @foreach ($hoSos as $hs)
                                    <option value="{{ $hs->id }}" @selected(old('ho_so_id', $ktkl->ho_so_id) == $hs->id)>
                                        {{ $hs->ma_nhan_vien }} - {{ $hs->ho_ten }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        {{-- LOẠI --}}
                        <div>

                            <label class="flex items-center gap-2 mb-2 font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-scale-balanced text-purple-600"></i>
                                Loại quyết định
                            </label>

                            <select name="loai"
                                class="w-full rounded-2xl border border-gray-300 bg-white dark:bg-slate-900 dark:border-slate-700 dark:text-white px-4 py-3">

                                <option value="khen_thuong" @selected($ktkl->loai == 'khen_thuong')>
                                    🏆 Khen thưởng
                                </option>

                                <option value="ky_luat" @selected($ktkl->loai == 'ky_luat')>
                                    ⚠️ Kỷ luật
                                </option>

                            </select>
                        </div>

                        {{-- SỐ QUYẾT ĐỊNH --}}
                        <div>

                            <label class="flex items-center gap-2 mb-2 font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-hashtag text-indigo-600"></i>
                                Số quyết định
                            </label>

                            <input type="text" name="quyet_dinh_so"
                                value="{{ old('quyet_dinh_so', $ktkl->quyet_dinh_so) }}"
                                class="w-full rounded-2xl border border-gray-300 bg-white dark:bg-slate-900 dark:border-slate-700 dark:text-white px-4 py-3">
                        </div>

                        {{-- HÌNH THỨC --}}
                        <div>

                            <label class="flex items-center gap-2 mb-2 font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-gift text-emerald-600"></i>
                                Hình thức
                            </label>

                            <input type="text" name="hinh_thuc" value="{{ old('hinh_thuc', $ktkl->hinh_thuc) }}"
                                class="w-full rounded-2xl border border-gray-300 bg-white dark:bg-slate-900 dark:border-slate-700 dark:text-white px-4 py-3">
                        </div>

                        {{-- NGÀY --}}
                        <div>

                            <label class="flex items-center gap-2 mb-2 font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-calendar text-green-600"></i>
                                Ngày quyết định
                            </label>

                            <input type="date" name="ngay"
                                value="{{ old('ngay', \Carbon\Carbon::parse($ktkl->ngay)->format('Y-m-d')) }}"
                                class="
                                    w-full
                                    rounded-2xl
                                    border
                                    border-gray-300
                                    bg-white
                                    dark:bg-slate-900
                                    dark:border-slate-700
                                    text-gray-900
                                    dark:text-white
                                    px-4
                                    py-3
                                    focus:border-blue-500
                                    focus:ring-4
                                    focus:ring-blue-500/10
                                    transition
                                    dark:[color-scheme:dark]
                                    ">
                        </div>

                        {{-- SỐ TIỀN --}}
                        <div>

                            <label class="flex items-center gap-2 mb-2 font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-coins text-yellow-600"></i>
                                Số tiền
                            </label>

                            <input id="so_tien" type="number" name="so_tien" value="{{ old('so_tien', $ktkl->so_tien) }}"
                                class="w-full rounded-2xl border border-gray-300 bg-white dark:bg-slate-900 dark:border-slate-700 dark:text-white px-4 py-3">
                        </div>

                        {{-- TIÊU ĐỀ --}}
                        <div>

                            <label class="flex items-center gap-2 mb-2 font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-heading text-red-600"></i>
                                Tiêu đề
                            </label>

                            <input type="text" name="ten" value="{{ old('ten', $ktkl->ten) }}"
                                class="w-full rounded-2xl border border-gray-300 bg-white dark:bg-slate-900 dark:border-slate-700 dark:text-white px-4 py-3">
                        </div>

                        {{-- NỘI DUNG --}}
                        <div>

                            <label class="flex items-center gap-2 mb-2 font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-file-lines text-indigo-600"></i>
                                Nội dung
                            </label>

                            <textarea name="noi_dung" rows="6"
                                class="w-full rounded-2xl border border-gray-300 bg-white dark:bg-slate-900 dark:border-slate-700 dark:text-white px-4 py-3">{{ old('noi_dung', $ktkl->noi_dung) }}</textarea>
                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="bg-gray-50 dark:bg-slate-900/40 px-8 py-6 border-t border-gray-200 dark:border-slate-700">

                        <div class="flex justify-end gap-4">

                            <a href="{{ route('admin.khen-thuong-ky-luat.index') }}"
                                class="px-6 py-3 rounded-xl border border-gray-300 dark:border-slate-600 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700">

                                Hủy

                            </a>

                            <button type="submit"
                                class="px-7 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold">

                                Cập nhật

                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>
@endsection
