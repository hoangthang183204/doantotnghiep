@extends('layouts.admin')

@section('title', 'Thêm khen thưởng')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                        Thêm khen thưởng
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                        Tạo quyết định khen thưởng cho nhân viên
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.khen-thuong-ky-luat.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm transition gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    @include('layouts.partials.alerts')

    {{-- FORM --}}
    <form action="{{ route('admin.khen-thuong-ky-luat.khen-thuong.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Nhân viên <span class="text-red-500">*</span>
                    </label>
                    <select name="ho_so_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Chọn nhân viên --</option>
                        @foreach ($hoSos as $hs)
                            <option value="{{ $hs->id }}" @selected(old('ho_so_id') == $hs->id)>
                                {{ $hs->ma_nhan_vien }} - {{ $hs->ho . ' ' . $hs->ten }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Ngày quyết định
                    </label>
                    <input type="date" name="ngay" value="{{ old('ngay', date('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:[color-scheme:dark]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Hình thức
                    </label>
                    <input type="text" name="hinh_thuc" value="{{ old('hinh_thuc') }}" placeholder="VD: Tiền mặt, Bằng khen..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Mức độ khen thưởng <span class="text-red-500">*</span>
                    </label>
                    <select name="muc_do" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">-- Chọn mức độ --</option>
                        <option value="kha" {{ old('muc_do') == 'kha' ? 'selected' : '' }}>Khá</option>
                        <option value="gioi" {{ old('muc_do') == 'gioi' ? 'selected' : '' }}>Giỏi</option>
                        <option value="xuat_sac" {{ old('muc_do') == 'xuat_sac' ? 'selected' : '' }}>Xuất sắc</option>
                    </select>
                    @error('muc_do')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Số tiền
                    </label>
                    <input type="number" name="so_tien" value="{{ old('so_tien') }}" placeholder="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Người ký
                    </label>
                    <select name="nguoi_ky_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Chọn --</option>
                        @foreach ($nguoiKys as $nguoiKy)
                            <option value="{{ $nguoiKy->id }}" @selected(old('nguoi_ky_id') == $nguoiKy->id)>
                                {{ $nguoiKy->ten_dang_nhap }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Số quyết định
                    </label>
                    <input type="text" name="quyet_dinh_so" value="{{ old('quyet_dinh_so') }}" placeholder="VD: QĐ-2025-001" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Tiêu đề <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="ten" value="{{ old('ten') }}" placeholder="Tiêu đề quyết định" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Nội dung
                </label>
                <textarea name="noi_dung" rows="4" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('noi_dung') }}</textarea>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Bằng chứng
                    </label>
                    <input type="file" name="bang_chung" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Quyết định (file)
                    </label>
                    <input type="file" name="quyet_dinh_file" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

        </div>

        {{-- FOOTER --}}
        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('admin.khen-thuong-ky-luat.index') }}" class="px-5 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium transition">
                Hủy
            </a>
            <button type="submit" class="px-6 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-medium transition shadow-sm">
                Lưu khen thưởng
            </button>
        </div>

    </form>

</div>
@endsection