@extends('layouts.admin')

@section('title', 'Thêm kỷ luật')

@section('content')

    <div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-10">

        <div class="max-w-[95%] xl:max-w-[1600px] mx-auto px-10">

            {{-- BANNER --}}
            <div
                class="relative overflow-hidden rounded-3xl shadow-xl mb-10
            bg-gradient-to-r from-red-50 via-slate-100 to-red-50
            dark:from-slate-950 dark:via-slate-900 dark:to-slate-900">

                <div class="absolute right-0 top-0 opacity-10 text-[180px] pr-10 pt-4">
                    <i class="fa-solid fa-gavel"></i>
                </div>

                <div class="relative flex items-center justify-between px-10 py-10">

                    <div class="flex items-center gap-5">

                        <div
                            class="w-20 h-20 rounded-3xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center">
                            <i class="fa-solid fa-scale-balanced text-3xl text-red-600"></i>
                        </div>

                        <div>
                            <h1 class="text-3xl font-bold text-slate-800 dark:text-white">
                                Thêm kỷ luật
                            </h1>

                            <p class="text-slate-500 dark:text-slate-400 mt-2">
                                Tạo quyết định kỷ luật cho nhân viên
                            </p>
                        </div>

                    </div>

                    {{-- BACK --}}
                    <a href="{{ route('admin.khen-thuong-ky-luat.index') }}"
                        class="px-5 py-3 rounded-2xl bg-white dark:bg-slate-900
                   border border-slate-200 dark:border-slate-700
                   text-slate-700 dark:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                        ← Quay lại
                    </a>

                </div>
            </div>

            @include('layouts.partials.alerts')

            {{-- FORM --}}
            <form action="{{ route('admin.khen-thuong-ky-luat.ky-luat.store') }}" method="POST">
                @csrf

                <div
                    class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-800 overflow-hidden">

                    {{-- HEADER --}}
                    <div class="px-10 py-7 border-b border-slate-200 dark:border-slate-800 flex items-center gap-4">

                        <div class="w-14 h-14 rounded-2xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                            <i class="fa-solid fa-file-circle-exclamation text-red-600 text-2xl"></i>
                        </div>

                        <div>
                            <h2 class="text-xl font-bold text-slate-800 dark:text-white">
                                Thông tin kỷ luật
                            </h2>
                            <p class="text-slate-500 dark:text-slate-400 text-sm">
                                Nhập đầy đủ thông tin bên dưới
                            </p>
                        </div>

                    </div>

                    {{-- BODY --}}
                    <div class="p-10 space-y-8">

                        <div class="grid md:grid-cols-2 gap-8">

                            {{-- NHÂN VIÊN --}}
                            <div>
                                <label class="label-ui">Nhân viên *</label>
                                <select name="ho_so_id" class="input-ui">
                                    <option value="">-- Chọn nhân viên --</option>
                                    @foreach ($hoSos as $hs)
                                        <option value="{{ $hs->id }}" @selected(old('ho_so_id') == $hs->id)>
                                            {{ $hs->ma_nhan_vien }} - {{ $hs->ho . ' ' . $hs->ten }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ho_so_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- MỨC ĐỘ --}}
                            <div>
                                <label class="label-ui">Mức độ kỷ luật *</label>
                                <select name="muc_do" class="input-ui">
                                    <option value="canh_cao" @selected(old('muc_do') == 'canh_cao')>Cảnh cáo</option>
                                    <option value="khien_trach" @selected(old('muc_do') == 'khien_trach')>Khiển trách</option>
                                    <option value="sa_thai" @selected(old('muc_do') == 'sa_thai')>Sa thải</option>
                                </select>
                                @error('muc_do')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- NGÀY --}}
                            <div>
                                <label class="label-ui">Ngày quyết định</label>
                                <input type="date" name="ngay" value="{{ old('ngay', date('Y-m-d')) }}"
                                    class="input-ui dark:[color-scheme:dark]">
                            </div>

                            {{-- NGƯỜI KÝ --}}
                            <div>
                                <label class="label-ui">Người ký</label>
                                <select name="nguoi_ky_id" class="input-ui">
                                    <option value="">-- Chọn người ký --</option>
                                    @foreach ($nguoiKys as $nguoiKy)
                                        <option value="{{ $nguoiKy->id }}" @selected(old('nguoi_ky_id') == $nguoiKy->id)>
                                            {{ $nguoiKy->ten_dang_nhap }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div>
                            <label class="label-ui">Hình thức xử lý</label>
                            <input type="text" name="hinh_thuc" value="{{ old('hinh_thuc') }}" class="input-ui"
                                placeholder="VD: Khiển trách bằng văn bản, đình chỉ...">
                            @error('hinh_thuc')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- SỐ QUYẾT ĐỊNH --}}
                        <div>
                            <label class="label-ui">Số quyết định</label>
                            <input type="text" name="quyet_dinh_so" value="{{ old('quyet_dinh_so') }}" class="input-ui">
                        </div>

                        {{-- TIÊU ĐỀ --}}
                        <div>
                            <label class="label-ui">Tiêu đề *</label>
                            <input type="text" name="ten" value="{{ old('ten') }}" class="input-ui">
                        </div>

                        {{-- NỘI DUNG --}}
                        <div>
                            <label class="label-ui">Nội dung</label>
                            <textarea name="noi_dung" rows="6" class="input-ui"></textarea>
                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="px-10 py-7 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-4">

                        <a href="{{ route('admin.khen-thuong-ky-luat.index') }}"
                            class="px-6 py-3 rounded-2xl
           border border-slate-300 dark:border-slate-700
           bg-white dark:bg-slate-900
           text-slate-700 dark:text-slate-200
           hover:bg-slate-100 dark:hover:bg-slate-800
           transition">
                            Hủy
                        </a>

                        <button type="submit"
                            class="px-7 py-3 rounded-2xl
           bg-red-600 hover:bg-red-700
           text-white font-semibold
           transition shadow-lg shadow-red-600/20">
                            Lưu kỷ luật
                        </button>

                    </div>

                </div>
            </form>

        </div>
    </div>

    {{-- STYLE --}}
    <style>
        .label-ui {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #334155;
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
            color: #0f172a;
        }

        .dark .input-ui {
            background: #0f172a;
            border-color: #334155;
            color: white;
        }
    </style>

@endsection
