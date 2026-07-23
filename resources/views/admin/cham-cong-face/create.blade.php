@extends('layouts.admin')

@section('title', 'Đăng ký khuôn mặt')

@section('content')
<div class="space-y-6">
    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800/80 backdrop-blur border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">📸 Đăng ký khuôn mặt nhân viên</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Upload ảnh khuôn mặt để đăng ký chấm công bằng khuôn mặt</p>
            </div>
            <a href="{{ route('admin.cham-cong-face.index') }}" 
               class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-xl transition flex items-center gap-2 shadow-sm">
                ← Quay lại
            </a>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    {{-- FORM --}}
    <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
        <form action="{{ route('admin.cham-cong-face.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Chọn nhân viên --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nhân viên <span class="text-red-500">*</span>
                        </label>
                        <select name="nguoi_dung_id" 
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 transition"
                                required>
                            <option value="">-- Chọn nhân viên --</option>
                            @foreach($nhanViens as $nv)
                                <option value="{{ $nv->id }}" {{ old('nguoi_dung_id') == $nv->id ? 'selected' : '' }}>
                                    {{ optional($nv->hoSo)->ho ?? '' }} {{ optional($nv->hoSo)->ten ?? $nv->ten_dang_nhap }}
                                    ({{ optional($nv->hoSo)->ma_nhan_vien ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        @error('nguoi_dung_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Upload ảnh --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Ảnh khuôn mặt <span class="text-red-500">*</span>
                        </label>
                        <input type="file" name="face_image" accept="image/jpeg,image/png,image/jpg"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition"
                               required>
                        <p class="text-xs text-gray-500 mt-1">📌 Chấp nhận JPG, PNG. Tối đa 5MB.</p>
                        <p class="text-xs text-blue-500 mt-1">💡 Ảnh nên rõ mặt, không đeo kính, không che mặt.</p>
                        @error('face_image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Preview ảnh --}}
                <div class="mt-4">
                    <div id="imagePreview" class="hidden">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📷 Xem trước:</p>
                        <img id="previewImg" src="#" alt="Preview" class="w-40 h-40 rounded-lg object-cover border border-gray-200 dark:border-gray-600">
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md hover:shadow-lg transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        💾 Đăng ký khuôn mặt
                    </button>
                    <a href="{{ route('admin.cham-cong-face.index') }}" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-xl transition">
                        ← Hủy
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Preview ảnh khi chọn file
    document.querySelector('input[name="face_image"]').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const img = document.getElementById('previewImg');
        
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(this.files[0]);
        } else {
            preview.classList.add('hidden');
        }
    });
</script>
@endsection