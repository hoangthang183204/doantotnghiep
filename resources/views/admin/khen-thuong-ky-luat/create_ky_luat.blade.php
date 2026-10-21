@extends('layouts.admin')

@section('title', 'Thêm kỷ luật')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-600 dark:text-red-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                        Thêm kỷ luật
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                        Tạo quyết định kỷ luật cho nhân viên
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
    <form action="{{ route('admin.khen-thuong-ky-luat.ky-luat.store') }}" method="POST">
        @csrf

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Nhân viên --}}
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
                    @error('ho_so_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mức độ kỷ luật --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Mức độ kỷ luật <span class="text-red-500">*</span>
                    </label>
                    <select name="muc_do" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Chọn mức độ --</option>
                        <option value="canh_cao" @selected(old('muc_do') == 'canh_cao')>Cảnh cáo</option>
                        <option value="khien_trach" @selected(old('muc_do') == 'khien_trach')>Khiển trách</option>
                        <option value="sa_thai" @selected(old('muc_do') == 'sa_thai')>Sa thải</option>
                    </select>
                    @error('muc_do')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ngày quyết định --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Ngày quyết định <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="ngay" value="{{ old('ngay', date('Y-m-d')) }}" 
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:[color-scheme:dark]">
                    @error('ngay')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Hình thức xử lý --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Hình thức xử lý
                    </label>
                    <input type="text" name="hinh_thuc" value="{{ old('hinh_thuc') }}" 
                        placeholder="VD: Khiển trách bằng văn bản..." 
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('hinh_thuc')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Số quyết định --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Số quyết định
                    </label>
                    <input type="text" name="quyet_dinh_so" value="{{ old('quyet_dinh_so') }}" 
                        placeholder="VD: QĐ-2025-001" 
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Số tiền phạt --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Số tiền phạt
                    </label>
                    <input type="number" name="so_tien" value="{{ old('so_tien') }}" 
                        placeholder="0" min="0" step="1000"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Nhập số tiền phạt (nếu có)</p>
                    @error('so_tien')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tiêu đề --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Tiêu đề <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="ten" value="{{ old('ten') }}" 
                        placeholder="Tiêu đề quyết định" 
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('ten')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Nội dung --}}
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Nội dung
                </label>
                <textarea name="noi_dung" rows="4" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('noi_dung') }}</textarea>
            </div>

            {{-- Thông tin người ký (tự động) --}}
            <div class="mt-4 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 flex items-center gap-3">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-sm text-blue-800 dark:text-blue-300">
                        <span class="font-medium">Người ký:</span> {{ auth()->user()->ten_dang_nhap }}
                        <span class="text-xs text-blue-600 dark:text-blue-400 ml-2">(Tự động lấy theo người đang đăng nhập)</span>
                    </p>
                </div>
            </div>

        </div>

        {{-- FOOTER --}}
        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('admin.khen-thuong-ky-luat.index') }}" 
                class="px-5 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium transition">
                Hủy
            </a>
            <button type="submit" 
                class="px-6 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition shadow-sm">
                Lưu kỷ luật
            </button>
        </div>

    </form>

</div>
@endsection