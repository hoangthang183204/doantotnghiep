@extends('layouts.admin')

@section('title', 'Thêm khen thưởng')

@section('content')

    <div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-10">

        <div class="max-w-[95%] xl:max-w-[1600px] mx-auto px-10">

            {{-- BANNER --}}
            <div
                class="relative overflow-hidden rounded-3xl shadow-xl mb-10
            bg-gradient-to-r from-slate-100 via-slate-200 to-slate-100
            dark:from-slate-950 dark:via-slate-900 dark:to-slate-900">

                <div class="absolute right-0 top-0 opacity-10 text-[180px] pr-10 pt-4">
                    <i class="fa-solid fa-trophy"></i>
                </div>

                <div class="relative flex items-center justify-between px-10 py-10">

                    <div class="flex items-center gap-5">

                        <div
                            class="w-20 h-20 rounded-3xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center">
                            <i class="fa-solid fa-award text-3xl text-slate-600 dark:text-slate-300"></i>
                        </div>

                        <div>
                            <h1 class="text-3xl font-bold text-slate-800 dark:text-white">
                                Thêm khen thưởng
                            </h1>

                            <p class="text-slate-500 dark:text-slate-400 mt-2">
                                Tạo quyết định khen thưởng cho nhân viên
                            </p>
                        </div>

                    </div>

                    <a href="{{ route('admin.khen-thuong-ky-luat.index') }}"
                        class="px-5 py-3 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700
                   text-slate-700 dark:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        ← Quay lại
                    </a>

                </div>
            </div>

            @include('layouts.partials.alerts')

            {{-- FORM --}}
            <form action="{{ route('admin.khen-thuong-ky-luat.khen-thuong.store') }}" method="POST"
                enctype="multipart/form-data">

                @csrf

                <div
                    class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-800 overflow-hidden">

                    {{-- HEADER --}}
                    <div class="px-10 py-7 border-b border-slate-200 dark:border-slate-800 flex items-center gap-4">

                        <div class="w-14 h-14 rounded-2xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                            <i class="fa-solid fa-file-signature text-blue-600 text-2xl"></i>
                        </div>

                        <div>
                            <h2 class="text-xl font-bold text-slate-800 dark:text-white">
                                Thông tin khen thưởng
                            </h2>
                            <p class="text-slate-500 dark:text-slate-400 text-sm">
                                Nhập đầy đủ thông tin bên dưới
                            </p>
                        </div>

                    </div>

                    {{-- BODY --}}
                    <div class="p-10 space-y-8">

                        {{-- NHÂN VIÊN --}}
                        <div>
                            <label class="label-ui">
                                <i class="fa-solid fa-user"></i>
                                Nhân viên *
                            </label>

                            <select name="ho_so_id" class="input-ui">
                                <option value="">-- Chọn nhân viên --</option>
                                @foreach ($hoSos as $hs)
                                    <option value="{{ $hs->id }}" @selected(old('ho_so_id') == $hs->id)>
                                        {{ $hs->ma_nhan_vien }} - {{ $hs->ho . ' ' . $hs->ten }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- NGÀY --}}
                        <div>
                            <label class="label-ui">
                                <i class="fa-solid fa-calendar"></i>
                                Ngày quyết định
                            </label>

                            <input type="date" name="ngay" value="{{ old('ngay', date('Y-m-d')) }}"
                                class="input-ui dark:[color-scheme:dark]">
                        </div>

                        {{-- HÌNH THỨC --}}
                        <div>
                            <label class="label-ui">
                                <i class="fa-solid fa-tag"></i>
                                Hình thức
                            </label>

                            <input type="text" name="hinh_thuc" value="{{ old('hinh_thuc') }}" class="input-ui">
                        </div>

                        {{-- SỐ TIỀN --}}
                        <div>
                            <label class="label-ui">
                                <i class="fa-solid fa-coins"></i>
                                Số tiền
                            </label>

                            <input type="number" name="so_tien" value="{{ old('so_tien') }}" class="input-ui">
                        </div>

                        {{-- NGƯỜI KÝ --}}
                        <div>
                            <label class="label-ui">
                                <i class="fa-solid fa-signature"></i>
                                Người ký
                            </label>

                            <select name="nguoi_ky_id" class="input-ui">
                                <option value="">-- Chọn --</option>
                                @foreach ($nguoiKys as $nguoiKy)
                                    <option value="{{ $nguoiKy->id }}" @selected(old('nguoi_ky_id') == $nguoiKy->id)>
                                        {{ $nguoiKy->ten_dang_nhap }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- SỐ QUYẾT ĐỊNH --}}
                        <div>
                            <label class="label-ui">
                                <i class="fa-solid fa-hashtag"></i>
                                Số quyết định
                            </label>

                            <input type="text" name="quyet_dinh_so" value="{{ old('quyet_dinh_so') }}" class="input-ui">
                        </div>

                        {{-- TIÊU ĐỀ --}}
                        <div>
                            <label class="label-ui">
                                <i class="fa-solid fa-heading"></i>
                                Tiêu đề *
                            </label>

                            <input type="text" name="ten" value="{{ old('ten') }}" class="input-ui">
                        </div>

                        {{-- NỘI DUNG --}}
                        <div>
                            <label class="label-ui">
                                <i class="fa-solid fa-file-lines"></i>
                                Nội dung
                            </label>

                            <textarea name="noi_dung" rows="6" class="input-ui">{{ old('noi_dung') }}</textarea>
                        </div>

                        {{-- 📎 BẰNG CHỨNG --}}
                        <div>
                            <label class="label-ui">
                                <i class="fa-solid fa-paperclip"></i>
                                Bằng chứng
                            </label>

                            <input type="file" name="bang_chung" class="input-ui dark:[color-scheme:dark]">
                        </div>

                        {{-- 📄 QUYẾT ĐỊNH FILE --}}
                        <div>
                            <label class="label-ui">
                                <i class="fa-solid fa-file-contract"></i>
                                Quyết định (file)
                            </label>

                            <input type="file" name="quyet_dinh_file" class="input-ui dark:[color-scheme:dark]">
                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="px-10 py-7 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-4">

                        <a href="{{ route('admin.khen-thuong-ky-luat.index') }}"
                            class="px-6 py-3 rounded-2xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-white">
                            Hủy
                        </a>

                        <button class="px-7 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                            Lưu khen thưởng
                        </button>

                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- STYLE --}}
    <style>
        .label-ui {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
            font-weight: 600;
            color: #334155;
        }

        .label-ui i {
            font-size: 14px;
            color: #64748b;
        }

        .dark .label-ui {
            color: #cbd5e1;
        }

        .input-ui {
            width: 100%;
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            background: white;
        }

        .dark .input-ui {
            background: #0f172a;
            border-color: #334155;
            color: white;
        }
    </style>

@endsection
