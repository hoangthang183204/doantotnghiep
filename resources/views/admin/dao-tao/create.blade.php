@extends('layouts.admin')

@section('title', 'Đăng ký khóa đào tạo')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                        Đăng ký khóa đào tạo
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                        Thêm khóa đào tạo mới cho nhân viên
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.dao-tao.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm transition gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    @include('layouts.partials.alerts')

    {{-- FORM --}}
    <form action="{{ route('admin.dao-tao.store') }}" method="POST">
        @csrf

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

            {{-- 2 CỘT --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Nhân viên --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Nhân viên <span class="text-red-500">*</span>
                    </label>
                    <select name="ho_so_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Chọn nhân viên --</option>
                        @foreach($hoSos as $hs)
                            <option value="{{ $hs->id }}" {{ old('ho_so_id')==$hs->id?'selected':'' }}>
                                {{ $hs->ma_nhan_vien }} - {{ $hs->ho_ten }}
                            </option>
                        @endforeach
                    </select>
                    @error('ho_so_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tên khóa học --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Tên khóa học <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="ten_khoa_hoc" value="{{ old('ten_khoa_hoc') }}"
                        placeholder="VD: Kỹ năng giao tiếp"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('ten_khoa_hoc')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Đơn vị đào tạo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Đơn vị đào tạo
                    </label>
                    <input type="text" name="to_chuc" value="{{ old('to_chuc') }}"
                        placeholder="VD: FPT, Vinschool..."
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Chi phí --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Chi phí
                    </label>
                    <input type="number" min="0" step="1000" name="chi_phi" value="{{ old('chi_phi') }}"
                        placeholder="0"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Ngày bắt đầu --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Ngày bắt đầu <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="ngay_bat_dau" value="{{ old('ngay_bat_dau') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:[color-scheme:dark]">
                    @error('ngay_bat_dau')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ngày kết thúc --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Ngày kết thúc
                    </label>
                    <input type="date" name="ngay_ket_thuc" value="{{ old('ngay_ket_thuc') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:[color-scheme:dark]">
                </div>

            </div>

            {{-- Ghi chú --}}
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Ghi chú
                </label>
                <textarea name="ghi_chu" rows="4" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('ghi_chu') }}</textarea>
            </div>

            {{-- Thông báo màu xanh nhạt (giống ảnh 2) --}}
            <div class="mt-6 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-sm font-medium text-blue-800 dark:text-blue-300">
                        Lưu ý về đào tạo
                    </p>
                    <p class="text-sm text-blue-600 dark:text-blue-400">
                        Sau khi lưu, bạn có thể cập nhật kết quả và chứng chỉ sau khi nhân viên hoàn thành khóa học.
                    </p>
                </div>
            </div>

        </div>

        {{-- FOOTER --}}
        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('admin.dao-tao.index') }}"
                class="px-5 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium transition">
                Hủy
            </a>
            <button type="submit"
                class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition shadow-sm">
                Lưu đăng ký
            </button>
        </div>

    </form>

</div>
@endsection