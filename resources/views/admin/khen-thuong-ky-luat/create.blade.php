@extends('layouts.admin')

@section('title', 'Thêm quyết định')

@section('content')

    <div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-10">

        <div class="max-w-[95%] xl:max-w-[1600px] mx-auto px-10">

            {{-- BANNER --}}
            <div
                class="relative overflow-hidden rounded-3xl shadow-xl mb-10
            bg-gradient-to-r from-slate-100 via-slate-200 to-slate-100
            dark:from-slate-950 dark:via-slate-900 dark:to-slate-900">

                <div class="absolute right-0 top-0 opacity-10 text-[180px] pr-10 pt-4">
                    <i class="fa-solid fa-award"></i>
                </div>

                <div class="relative flex items-center justify-between px-10 py-10">

                    <div class="flex items-center gap-5">

                        <div
                            class="w-20 h-20 rounded-3xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center">
                            <i class="fa-solid fa-trophy text-3xl text-slate-600 dark:text-slate-300"></i>
                        </div>

                        <div>
                            <h1 class="text-3xl font-bold text-slate-800 dark:text-white">
                                Thêm quyết định
                            </h1>

                            <p class="text-slate-500 dark:text-slate-400 mt-2">
                                Tạo quyết định khen thưởng hoặc kỷ luật cho nhân viên
                            </p>
                        </div>

                    </div>

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
            <form action="{{ route('admin.khen-thuong-ky-luat.store') }}" method="POST">
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
                                Thông tin quyết định
                            </h2>
                            <p class="text-slate-500 dark:text-slate-400 text-sm">
                                Điền đầy đủ thông tin bên dưới
                            </p>
                        </div>

                    </div>

                    {{-- BODY --}}
                    <div class="p-10 space-y-8">

                        <div class="grid md:grid-cols-2 gap-8">

                            {{-- NHÂN VIÊN --}}
                            <div>
                                <label class="label-ui">
                                    <i class="fa-solid fa-user text-blue-600"></i>
                                    Nhân viên <span class="text-red-500">*</span>
                                </label>

                                <select name="ho_so_id" class="input-ui">
                                    <option value="">-- Chọn nhân viên --</option>
                                    @foreach ($hoSos as $hs)
                                        <option value="{{ $hs->id }}" @selected(old('ho_so_id') == $hs->id)>
                                            {{ $hs->ma_nhan_vien }} - {{ $hs->ho_ten }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('ho_so_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- LOẠI --}}
                            <div>
                                <label class="label-ui">
                                    <i class="fa-solid fa-scale-balanced text-purple-600"></i>
                                    Loại quyết định <span class="text-red-500">*</span>
                                </label>

                                <select id="loai" name="loai" class="input-ui">
                                    <option value="">-- Chọn loại --</option>
                                    <option value="khen_thuong" @selected(old('loai') == 'khen_thuong')>🏆 Khen thưởng</option>
                                    <option value="ky_luat" @selected(old('loai') == 'ky_luat')>⚠️ Kỷ luật</option>
                                </select>
                            </div>

                            {{-- HÌNH THỨC --}}
                            <div>
                                <label class="label-ui">
                                    <i class="fa-solid fa-gift text-pink-500"></i>
                                    Hình thức
                                </label>

                                <input type="text" name="hinh_thuc" value="{{ old('hinh_thuc') }}" class="input-ui">
                            </div>

                            {{-- NGÀY --}}
                            <div>
                                <label class="label-ui">
                                    <i class="fa-solid fa-calendar text-green-600"></i>
                                    Ngày quyết định
                                </label>

                                <input type="date" name="ngay" value="{{ old('ngay', date('Y-m-d')) }}"
                                    class="input-ui
                                    dark:[color-scheme:dark]">
                            </div>

                            {{-- NGƯỜI KÝ --}}
                            <div>
                                <label class="label-ui">
                                    <i class="fa-solid fa-signature text-cyan-600"></i>
                                    Người ký
                                </label>

                                <select name="nguoi_ky_id" class="input-ui">
                                    <option value="">-- Chọn người ký --</option>
                                    @foreach ($nguoiKys as $nguoiKy)
                                        <option value="{{ $nguoiKy->id }}" @selected(old('nguoi_ky_id') == $nguoiKy->id)>
                                            {{ $nguoiKy->ten_dang_nhap }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- SỐ TIỀN --}}
                            <div>
                                <label class="label-ui">
                                    <i class="fa-solid fa-coins text-yellow-600"></i>
                                    Số tiền
                                </label>

                                <input id="so_tien" type="number" name="so_tien" value="{{ old('so_tien') }}"
                                    class="input-ui">
                            </div>

                        </div>

                        {{-- SỐ QUYẾT ĐỊNH --}}
                        <div>
                            <label class="label-ui">
                                <i class="fa-solid fa-hashtag text-indigo-600"></i>
                                Số quyết định
                            </label>

                            <input type="text" name="quyet_dinh_so" value="{{ old('quyet_dinh_so') }}" class="input-ui">
                        </div>

                        {{-- TIÊU ĐỀ --}}
                        <div>
                            <label class="label-ui">
                                <i class="fa-solid fa-heading text-red-500"></i>
                                Tiêu đề <span class="text-red-500">*</span>
                            </label>

                            <input type="text" name="ten" value="{{ old('ten') }}" class="input-ui">
                        </div>

                        {{-- NỘI DUNG --}}
                        <div>
                            <label class="label-ui">
                                <i class="fa-solid fa-file-lines text-indigo-600"></i>
                                Nội dung
                            </label>

                            <textarea rows="7" name="noi_dung" class="input-ui"></textarea>
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
                            Lưu quyết định
                        </button>

                    </div>

                </div>
            </form>

        </div>
    </div>

    {{-- STYLE (KHÔNG DÙNG @apply) --}}
    <style>
        .label-ui {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #334155;
        }

        .dark .label-ui {
            color: #cbd5e1;
        }

        .input-ui {
            width: 100%;
            border-radius: 1rem;
            border: 1px solid #cbd5e1;
            background: white;
            color: #0f172a;
            padding: 0.75rem 1rem;
            transition: 0.2s;
        }

        .dark .input-ui {
            background: #0f172a;
            border-color: #334155;
            color: white;
        }

        .input-ui:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
            outline: none;
        }
    </style>

    {{-- SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loai = document.getElementById('loai');
            const soTien = document.getElementById('so_tien');

            function toggleMoney() {
                if (loai.value === 'ky_luat') {
                    soTien.value = '';
                    soTien.disabled = true;
                    soTien.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    soTien.disabled = false;
                    soTien.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            loai.addEventListener('change', toggleMoney);
            toggleMoney();
        });
    </script>

@endsection
