@extends('layouts.admin')

@section('title', 'Thêm quyết định')

@section('content')
    <div class="min-h-screen bg-gray-100 dark:bg-slate-900 py-8">

        <div class="max-w-5xl mx-auto px-6">

            {{-- Banner --}}
            <div
                class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white shadow-xl mb-8">

                <div class="absolute right-0 top-0 opacity-10 text-[180px] pr-8 pt-2">
                    <i class="fa-solid fa-award"></i>
                </div>

                <div class="relative flex items-center justify-between px-8 py-8">

                    <div class="flex items-center gap-5">

                        <div
                            class="w-20 h-20 rounded-3xl bg-white/20 backdrop-blur flex items-center justify-center border border-white/30">

                            <i class="fa-solid fa-trophy text-4xl"></i>

                        </div>

                        <div>

                            <h1 class="text-3xl font-bold">
                                Thêm quyết định
                            </h1>

                            <p class="text-blue-100 mt-2">
                                Tạo quyết định khen thưởng hoặc kỷ luật cho nhân viên
                            </p>

                        </div>

                    </div>

                    <a href="{{ route('admin.khen-thuong-ky-luat.index') }}"
                        class="px-5 py-3 rounded-xl bg-white/20 hover:bg-white/30 transition backdrop-blur">

                        <i class="fa-solid fa-arrow-left mr-2"></i>
                        Quay lại

                    </a>

                </div>

            </div>

            @include('layouts.partials.alerts')

            <form action="{{ route('admin.khen-thuong-ky-luat.store') }}" method="POST">

                @csrf

                <div
                    class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-gray-200 dark:border-slate-700 overflow-hidden">

                    {{-- Header --}}
                    <div class="px-8 py-6 border-b border-gray-200 dark:border-slate-700 flex items-center gap-4">

                        <div class="w-14 h-14 rounded-2xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center">

                            <i class="fa-solid fa-file-signature text-blue-600 text-2xl"></i>

                        </div>

                        <div>

                            <h2 class="text-xl font-bold dark:text-white">
                                Thông tin quyết định
                            </h2>

                            <p class="text-gray-500 text-sm">
                                Điền đầy đủ thông tin bên dưới
                            </p>

                        </div>

                    </div>

                    {{-- Body --}}
                    <div class="p-8 space-y-7">

                        <div class="grid md:grid-cols-2 gap-7">

                            {{-- Nhân viên --}}
                            <div>

                                <label class="flex items-center gap-2 mb-2 font-semibold text-gray-700 dark:text-gray-300">

                                    <i class="fa-solid fa-user text-blue-600"></i>

                                    Nhân viên

                                    <span class="text-red-500">*</span>

                                </label>

                                <select name="ho_so_id"
                                    class="
                                    w-full
                                    rounded-2xl
                                    border
                                    border-gray-300
                                    bg-white
                                    dark:bg-slate-900
                                    dark:border-slate-700
                                    dark:text-white
                                    px-4
                                    py-3
                                    placeholder:text-gray-400
                                    focus:border-blue-500
                                    focus:ring-4
                                    focus:ring-blue-500/10
                                    transition">

                                    <option value="">-- Chọn nhân viên --</option>

                                    @foreach ($hoSos as $hs)
                                        <option value="{{ $hs->id }}" @selected(old('ho_so_id') == $hs->id)>

                                            {{ $hs->ma_nhan_vien }} - {{ $hs->ho_ten }}

                                        </option>
                                    @endforeach

                                </select>

                                @error('ho_so_id')
                                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                @enderror

                            </div>

                            {{-- Loại --}}
                            <div>

                                <label class="flex items-center gap-2 mb-2 font-semibold text-gray-700 dark:text-gray-300">

                                    <i class="fa-solid fa-scale-balanced text-purple-600"></i>

                                    Loại quyết định

                                    <span class="text-red-500">*</span>

                                </label>

                                <select id="loai" name="loai"
                                    class="
                                    w-full
                                    rounded-2xl
                                    border
                                    border-gray-300
                                    bg-white
                                    dark:bg-slate-900
                                    dark:border-slate-700
                                    dark:text-white
                                    px-4
                                    py-3
                                    placeholder:text-gray-400
                                    focus:border-blue-500
                                    focus:ring-4
                                    focus:ring-blue-500/10
                                    transition">

                                    <option value="">-- Chọn loại --</option>

                                    <option value="khen_thuong" @selected(old('loai') == 'khen_thuong')>

                                        🏆 Khen thưởng

                                    </option>

                                    <option value="ky_luat" @selected(old('loai') == 'ky_luat')>

                                        ⚠️ Kỷ luật

                                    </option>

                                </select>

                            </div>

                            {{-- Hình thức --}}
                            <div>

                                <label class="flex items-center gap-2 mb-2 font-semibold text-gray-700 dark:text-gray-300">

                                    <i class="fa-solid fa-gift text-pink-500"></i>

                                    Hình thức

                                </label>

                                <input type="text" name="hinh_thuc" value="{{ old('hinh_thuc') }}"
                                    placeholder="Ví dụ: Thưởng tiền, bằng khen, cảnh cáo..."
                                    class="
                                    w-full
                                    rounded-2xl
                                    border
                                    border-gray-300
                                    bg-white
                                    dark:bg-slate-900
                                    dark:border-slate-700
                                    dark:text-white
                                    px-4
                                    py-3
                                    focus:border-blue-500
                                    focus:ring-4
                                    focus:ring-blue-500/10
                                    transition">

                                @error('hinh_thuc')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror

                            </div>

                            {{-- Ngày --}}
                            <div>

                                <label class="flex items-center gap-2 mb-2 font-semibold text-gray-700 dark:text-gray-300">

                                    <i class="fa-solid fa-calendar text-green-600"></i>

                                    Ngày quyết định

                                </label>

                                <input type="date" name="ngay" value="{{ old('ngay', date('Y-m-d')) }}"
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

                            {{-- Người ký --}}
                            <div>

                                <label class="flex items-center gap-2 mb-2 font-semibold text-gray-700 dark:text-gray-300">

                                    <i class="fa-solid fa-signature text-cyan-600"></i>

                                    Người ký

                                </label>

                                <select name="nguoi_ky_id"
                                    class="
                                w-full
                                rounded-2xl
                                border
                                border-gray-300
                                bg-white
                                dark:bg-slate-900
                                dark:border-slate-700
                                dark:text-white
                                px-4
                                py-3
                                focus:border-blue-500
                                focus:ring-4
                                focus:ring-blue-500/10
                                transition">

                                    <option value="">-- Chọn người ký --</option>

                                    @foreach ($nguoiKys as $nguoiKy)
                                        <option value="{{ $nguoiKy->id }}" @selected(old('nguoi_ky_id') == $nguoiKy->id)>

                                            {{ $nguoiKy->ten_dang_nhap }}

                                        </option>
                                    @endforeach

                                </select>

                                @error('nguoi_ky_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror

                            </div>

                            {{-- Tiền --}}
                            <div>

                                <label class="flex items-center gap-2 mb-2 font-semibold text-gray-700 dark:text-gray-300">

                                    <i class="fa-solid fa-money-bill-wave text-yellow-600"></i>

                                    Số tiền

                                </label>

                                <input id="so_tien" type="number" name="so_tien" value="{{ old('so_tien') }}"
                                    min="0" placeholder="0 VNĐ"
                                    class="
                                    w-full
                                    rounded-2xl
                                    border
                                    border-gray-300
                                    bg-white
                                    dark:bg-slate-900
                                    dark:border-slate-700
                                    dark:text-white
                                    px-4
                                    py-3
                                    placeholder:text-gray-400
                                    focus:border-blue-500
                                    focus:ring-4
                                    focus:ring-blue-500/10
                                    transition">
                            </div>

                        </div>

                        {{-- Số quyết định --}}
                        <div>

                            <label class="flex items-center gap-2 mb-2 font-semibold text-gray-700 dark:text-gray-300">

                                <i class="fa-solid fa-hashtag text-indigo-600"></i>

                                Số quyết định

                            </label>

                            <input type="text" name="quyet_dinh_so" value="{{ old('quyet_dinh_so') }}"
                                placeholder="Ví dụ: QD-2026-001"
                                class="
                                w-full
                                rounded-2xl
                                border
                                border-gray-300
                                bg-white
                                dark:bg-slate-900
                                dark:border-slate-700
                                dark:text-white
                                px-4
                                py-3
                                focus:border-blue-500
                                focus:ring-4
                                focus:ring-blue-500/10
                                transition">

                            @error('quyet_dinh_so')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror

                        </div>


                        {{-- Tiêu đề --}}
                        <div>

                            <label class="flex items-center gap-2 mb-2 font-semibold text-gray-700 dark:text-gray-300">

                                <i class="fa-solid fa-heading text-red-500"></i>

                                Tiêu đề

                                <span class="text-red-500">*</span>

                            </label>

                            <input type="text" name="ten" value="{{ old('ten') }}"
                                placeholder="Ví dụ: Thưởng nhân viên xuất sắc quý II"
                                class="
                                w-full
                                rounded-2xl
                                border
                                border-gray-300
                                bg-white
                                dark:bg-slate-900
                                dark:border-slate-700
                                dark:text-white
                                px-4
                                py-3
                                focus:border-blue-500
                                focus:ring-4
                                focus:ring-blue-500/10
                                transition">

                            @error('ten')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror

                        </div>


                        {{-- Nội dung --}}
                        <div>

                            <label class="flex items-center gap-2 mb-2 font-semibold text-gray-700 dark:text-gray-300">

                                <i class="fa-solid fa-file-lines text-indigo-600"></i>

                                Nội dung

                            </label>

                            <textarea rows="7" name="noi_dung" placeholder="Nhập nội dung quyết định..."
                                class="
                                w-full
                                rounded-2xl
                                border
                                border-gray-300
                                bg-white
                                dark:bg-slate-900
                                dark:border-slate-700
                                dark:text-white
                                px-4
                                py-3
                                placeholder:text-gray-400
                                focus:border-blue-500
                                focus:ring-4
                                focus:ring-blue-500/10
                                transition
                                ">{{ old('noi_dung') }}</textarea>

                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="bg-gray-50 dark:bg-slate-900/40 px-8 py-6 border-t border-gray-200 dark:border-slate-700">

                        <div class="flex justify-end gap-4">

                            <a href="{{ route('admin.khen-thuong-ky-luat.index') }}"
                                class="px-6 py-3 rounded-xl border border-gray-300 dark:border-slate-600
                            hover:bg-gray-100 dark:hover:bg-slate-700 dark:text-white">

                                Hủy

                            </a>

                            <button type="submit"
                                class="px-7 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600
                            hover:from-blue-700 hover:to-indigo-700
                            shadow-lg text-white font-semibold transition">

                                <i class="fa-solid fa-floppy-disk mr-2"></i>

                                Lưu quyết định

                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const loai = document.getElementById('loai');
                const soTien = document.getElementById('so_tien');

                function toggleMoney() {

                    if (loai.value === 'ky_luat') {

                        soTien.value = '';
                        soTien.disabled = true;
                        soTien.placeholder = 'Không áp dụng cho kỷ luật';
                        soTien.classList.add('bg-gray-100', 'cursor-not-allowed');

                    } else {

                        soTien.disabled = false;
                        soTien.placeholder = 'Nhập số tiền';
                        soTien.classList.remove('bg-gray-100', 'cursor-not-allowed');

                    }
                }

                toggleMoney();

                loai.addEventListener('change', toggleMoney);

            });
        </script>
    @endpush
@endsection
