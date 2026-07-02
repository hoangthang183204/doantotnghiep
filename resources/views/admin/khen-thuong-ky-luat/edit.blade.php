@extends('layouts.admin')

@section('content')
    @php
        $hoSo = $ktkl->hoSo;
    @endphp

    <div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-8">

        <div class="max-w-[95%] xl:max-w-[1600px] mx-auto px-10">

            {{-- BANNER --}}
            <div
                class="relative overflow-hidden rounded-3xl
            bg-gradient-to-r from-slate-100 via-slate-200 to-slate-100
            dark:from-slate-950 dark:via-slate-900 dark:to-slate-900
            shadow-xl mb-10">

                <div class="absolute right-0 top-0 opacity-10 text-[180px] pr-8 pt-2 text-slate-500">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>

                <div class="relative flex items-center justify-between px-10 py-10">

                    <div class="flex items-center gap-5">

                        {{-- LOGO --}}
                        <div
                            class="w-20 h-20 rounded-3xl bg-white dark:bg-slate-800 flex items-center justify-center border border-slate-200 dark:border-slate-700 overflow-hidden">

                            @if (file_exists(public_path('images/logo.png')))
                                <img src="{{ asset('images/logo.png') }}" class="w-10 h-10 object-contain">
                            @else
                                <i class="fa-solid fa-building text-3xl text-slate-600 dark:text-slate-300"></i>
                            @endif

                        </div>

                        <div>
                            <h1 class="text-3xl font-bold text-slate-800 dark:text-white">
                                Cập nhật quyết định
                            </h1>

                            <p class="text-slate-500 dark:text-slate-400 mt-2">
                                Chỉnh sửa khen thưởng / kỷ luật nhân sự
                            </p>
                        </div>

                    </div>

                    <a href="{{ route('admin.khen-thuong-ky-luat.index') }}"
                        class="px-5 py-3 rounded-2xl bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-white transition">

                        ← Quay lại

                    </a>

                </div>
            </div>

            {{-- FORM --}}
            <form action="{{ route('admin.khen-thuong-ky-luat.update', $ktkl->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div
                    class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-800 overflow-hidden">

                    {{-- HEADER --}}
                    <div class="px-10 py-7 border-b border-slate-200 dark:border-slate-800 flex items-center gap-4">

                        <div class="w-14 h-14 rounded-2xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                            <i class="fa-solid fa-file-pen text-blue-600 text-2xl"></i>
                        </div>

                        <div>
                            <h2 class="text-xl font-bold text-slate-800 dark:text-white">
                                Thông tin quyết định
                            </h2>

                            <p class="text-slate-500 dark:text-slate-400 text-sm">
                                Chỉnh sửa dữ liệu
                            </p>
                        </div>

                    </div>

                    {{-- BODY --}}
                    <div class="p-10 space-y-8">

                        {{-- NHÂN VIÊN --}}
                        <div>
                            <label class="flex items-center gap-2 mb-2 font-semibold text-slate-700 dark:text-slate-300">
                                <i class="fa-solid fa-user text-blue-600"></i>
                                Nhân viên
                            </label>

                            <select name="ho_so_id"
                                class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-white px-4 py-3">

                                @foreach ($hoSos as $hs)
                                    <option value="{{ $hs->id }}" @selected(old('ho_so_id', $ktkl->ho_so_id) == $hs->id)>
                                        {{ $hs->ma_nhan_vien }} - {{ $hs->ho_ten }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        {{-- LOẠI --}}
                        <div>
                            <label class="flex items-center gap-2 mb-2 font-semibold text-slate-700 dark:text-slate-300">
                                <i class="fa-solid fa-scale-balanced text-purple-600"></i>
                                Loại quyết định
                            </label>

                            <select name="loai"
                                class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-white px-4 py-3">

                                <option value="khen_thuong" @selected($ktkl->loai == 'khen_thuong')>🏆 Khen thưởng</option>
                                <option value="ky_luat" @selected($ktkl->loai == 'ky_luat')>⚠️ Kỷ luật</option>

                            </select>
                        </div>

                        {{-- INPUTS --}}
                        @foreach ([['Số quyết định', 'quyet_dinh_so', 'hashtag', 'indigo-600'], ['Hình thức', 'hinh_thuc', 'gift', 'emerald-600'], ['Ngày quyết định', 'ngay', 'calendar', 'green-600'], ['Số tiền', 'so_tien', 'coins', 'yellow-600'], ['Tiêu đề', 'ten', 'heading', 'red-600']] as $f)
                            <div>
                                <label
                                    class="flex items-center gap-2 mb-2 font-semibold text-slate-700 dark:text-slate-300">
                                    <i class="fa-solid fa-{{ $f[2] }} text-{{ $f[3] }}"></i>
                                    {{ $f[0] }}
                                </label>

                                <input type="{{ $f[1] === 'ngay' ? 'date' : 'text' }}" name="{{ $f[1] }}"
                                    value="{{ old($f[1], $f[1] === 'ngay' ? optional($ktkl->ngay)->format('Y-m-d') : $ktkl->{$f[1]} ?? '') }}"
                                    class="w-full rounded-2xl border border-slate-300 dark:border-slate-700
                                bg-white dark:bg-slate-900 text-slate-800 dark:text-white
                                px-4 py-3
                                dark:[color-scheme:dark]">
                            </div>
                        @endforeach

                        {{-- NỘI DUNG --}}
                        <div>
                            <label class="flex items-center gap-2 mb-2 font-semibold text-slate-700 dark:text-slate-300">
                                <i class="fa-solid fa-file-lines text-indigo-600"></i>
                                Nội dung
                            </label>

                            <textarea name="noi_dung" rows="6"
                                class="w-full rounded-2xl border border-slate-300 dark:border-slate-700
                            bg-white dark:bg-slate-900 text-slate-800 dark:text-white px-4 py-3">{{ old('noi_dung', $ktkl->noi_dung) }}</textarea>
                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div
                        class="px-10 py-7 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-4 bg-slate-50 dark:bg-slate-900/40">

                        <a href="{{ route('admin.khen-thuong-ky-luat.index') }}"
                            class="px-6 py-3 rounded-2xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                            Hủy

                        </a>

                        <button type="submit"
                            class="px-7 py-3 rounded-2xl bg-gradient-to-r from-slate-700 to-slate-900 text-white font-semibold">

                            Cập nhật
                        </button>

                    </div>

                </div>

            </form>

        </div>
    </div>
@endsection
