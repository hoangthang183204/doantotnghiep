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

                <div class="absolute right-0 top-0 opacity-10 text-[180px] pr-8 pt-2">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>

                <div class="relative flex items-center justify-between px-10 py-10">

                    <div class="flex items-center gap-5">

                        <div
                            class="w-20 h-20 rounded-3xl bg-white dark:bg-slate-800 flex items-center justify-center border border-slate-200 dark:border-slate-700">
                            <i class="fa-solid fa-building text-3xl text-slate-600 dark:text-slate-300"></i>
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
                        class="px-5 py-3 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700
                        text-slate-700 dark:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        ← Quay lại
                    </a>

                </div>
            </div>

            {{-- FORM --}}
            <form
                action="{{ $ktkl->loai === 'khen_thuong'
                    ? route('admin.khen-thuong-ky-luat.khen-thuong.update', $ktkl->id)
                    : route('admin.khen-thuong-ky-luat.ky-luat.update', $ktkl->id) }}"
                method="POST" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div
                    class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-800 overflow-hidden">

                    {{-- HEADER --}}
                    <div class="px-10 py-7 border-b border-slate-200 dark:border-slate-800 flex items-center gap-4">

                        <div
                            class="w-14 h-14 rounded-2xl flex items-center justify-center
                            {{ $ktkl->loai === 'khen_thuong' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                            <i class="fa-solid fa-file-pen text-2xl"></i>
                        </div>

                        <div>
                            <h2 class="text-xl font-bold text-slate-800 dark:text-white">
                                {{ $ktkl->loai === 'khen_thuong' ? 'Cập nhật khen thưởng' : 'Cập nhật kỷ luật' }}
                            </h2>
                            <p class="text-slate-500 dark:text-slate-400 text-sm">
                                Chỉnh sửa thông tin quyết định
                            </p>
                        </div>

                    </div>

                    {{-- BODY --}}
                    <div class="p-10 space-y-7">

                        {{-- NHÂN VIÊN --}}
                        <div>
                            <label class="label-ui">
                                <i class="fa-solid fa-user"></i>
                                Nhân viên
                            </label>

                            <select name="ho_so_id" class="input-ui">
                                @foreach ($hoSos as $hs)
                                    <option value="{{ $hs->id }}" @selected(old('ho_so_id', $ktkl->ho_so_id) == $hs->id)>
                                        {{ $hs->ma_nhan_vien }} - {{ $hs->ho_ten }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- LOẠI --}}
                        <div>
                            <label class="label-ui">
                                <i class="fa-solid fa-scale-balanced"></i>
                                Loại quyết định
                            </label>

                            <input type="text" value="{{ $ktkl->loai === 'khen_thuong' ? 'Khen thưởng' : 'Kỷ luật' }}"
                                disabled class="input-ui bg-gray-100 dark:bg-slate-800">
                        </div>

                        {{-- HÌNH THỨC --}}
                        <div>
                            <label class="label-ui">
                                <i class="fa-solid fa-tag"></i>
                                Hình thức / xử lý
                            </label>

                            <input type="text" name="hinh_thuc" value="{{ old('hinh_thuc', $ktkl->hinh_thuc) }}"
                                class="input-ui">
                        </div>

                        {{-- SỐ QUYẾT ĐỊNH --}}
                        <div>
                            <label class="label-ui">
                                <i class="fa-solid fa-hashtag"></i>
                                Số quyết định
                            </label>

                            <input type="text" name="quyet_dinh_so"
                                value="{{ old('quyet_dinh_so', $ktkl->quyet_dinh_so) }}" class="input-ui">
                        </div>

                        {{-- NGÀY --}}
                        <div>
                            <label class="label-ui">
                                <i class="fa-solid fa-calendar"></i>
                                Ngày quyết định
                            </label>

                            <input type="date" name="ngay"
                                value="{{ old('ngay', optional($ktkl->ngay)->format('Y-m-d')) }}"
                                class="input-ui dark:[color-scheme:dark]">
                        </div>

                        {{-- TIÊU ĐỀ --}}
                        <div>
                            <label class="label-ui">
                                <i class="fa-solid fa-heading"></i>
                                Tiêu đề
                            </label>

                            <input type="text" name="ten" value="{{ old('ten', $ktkl->ten) }}" class="input-ui">
                        </div>

                        {{-- CONDITIONAL --}}
                        @if ($ktkl->loai === 'khen_thuong')
                            {{-- BẰNG CHỨNG --}}
                            <div>
                                <label class="label-ui">
                                    <i class="fa-solid fa-paperclip"></i>
                                    Bằng chứng
                                </label>

                                <input type="file" name="bang_chung" class="input-ui">

                                @if ($ktkl->bang_chung)
                                    <a href="{{ Storage::url($ktkl->bang_chung) }}" target="_blank"
                                        class="text-blue-500 text-sm">
                                        Xem hiện tại
                                    </a>
                                @endif
                            </div>

                            {{-- QUYẾT ĐỊNH FILE --}}
                            <div>
                                <label class="label-ui">
                                    <i class="fa-solid fa-file-contract"></i>
                                    Quyết định
                                </label>

                                <input type="file" name="quyet_dinh_file" class="input-ui">

                                @if ($ktkl->quyet_dinh_file)
                                    <a href="{{ Storage::url($ktkl->quyet_dinh_file) }}" target="_blank"
                                        class="text-blue-500 text-sm">
                                        Xem hiện tại
                                    </a>
                                @endif
                            </div>
                        @else
                            {{-- MỨC ĐỘ KỶ LUẬT --}}
                            <div>
                                <label class="label-ui">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    Mức độ kỷ luật
                                </label>

                                <select name="muc_do" class="input-ui">
                                    <option value="canh_cao" @selected(old('muc_do', $ktkl->muc_do) == 'canh_cao')>
                                        Cảnh cáo
                                    </option>

                                    <option value="khien_trach" @selected(old('muc_do', $ktkl->muc_do) == 'khien_trach')>
                                        Khiển trách
                                    </option>

                                    <option value="sa_thai" @selected(old('muc_do', $ktkl->muc_do) == 'sa_thai')>
                                        Sa thải
                                    </option>
                                </select>
                            </div>
                        @endif

                        {{-- NGƯỜI KÝ --}}
                        <div>
                            <label class="label-ui">
                                <i class="fa-solid fa-signature"></i>
                                Người ký
                            </label>

                            <select name="nguoi_ky_id" class="input-ui">
                                @foreach ($nguoiKys as $nk)
                                    <option value="{{ $nk->id }}" @selected(old('nguoi_ky_id', $ktkl->nguoi_ky_id) == $nk->id)>
                                        {{ $nk->ten_dang_nhap }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- NỘI DUNG --}}
                        <div>
                            <label class="label-ui">
                                <i class="fa-solid fa-file-lines"></i>
                                Nội dung
                            </label>

                            <textarea name="noi_dung" rows="5" class="input-ui">{{ old('noi_dung', $ktkl->noi_dung) }}</textarea>
                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="px-10 py-7 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-4">

                        <a href="{{ route('admin.khen-thuong-ky-luat.index') }}"
                            class="px-6 py-3 rounded-xl border border-slate-300 dark:border-slate-700
                            text-slate-700 dark:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                            Hủy
                        </a>

                        <button class="px-7 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                            Cập nhật
                        </button>

                    </div>

                </div>

            </form>

        </div>
    </div>

    <style>
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

        .label-ui {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
            font-weight: 600;
            color: #334155;
        }

        .dark .label-ui {
            color: #cbd5e1;
        }
    </style>

@endsection
