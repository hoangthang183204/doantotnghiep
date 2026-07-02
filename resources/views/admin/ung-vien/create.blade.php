@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.ung_vien.index') }}" 
           class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Thêm ứng viên mới
        </h1>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="text-sm font-semibold text-red-600 dark:text-red-400">Vui lòng kiểm tra lại thông tin:</h4>
                    <ul class="mt-1 text-sm text-red-600 dark:text-red-400 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border border-red-100 dark:border-red-800 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
        <form method="POST" action="{{ route('admin.ung_vien.store') }}" class="space-y-6" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Họ <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="ho" value="{{ old('ho') }}" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                           placeholder="Nhập họ" required>
                    @error('ho')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tên <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="ten" value="{{ old('ten') }}" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                           placeholder="Nhập tên" required>
                    @error('ten')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}" 
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                       placeholder="Nhập email" required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Số điện thoại <span class="text-red-500">*</span>
                </label>
                <input type="text" name="so_dien_thoai" value="{{ old('so_dien_thoai') }}" 
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                       placeholder="Nhập số điện thoại" required>
                @error('so_dien_thoai')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Tin tuyển dụng <span class="text-red-500">*</span>
                </label>
                <select name="tin_tuyen_dung_id" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm" required>
                    <option value="">-- Chọn tin tuyển dụng --</option>
                    @foreach($tinTuyenDungs as $tin)
                        <option value="{{ $tin->id }}" {{ old('tin_tuyen_dung_id') == $tin->id ? 'selected' : '' }}>
                            {{ $tin->tieu_de }}
                        </option>
                    @endforeach
                </select>
                @error('tin_tuyen_dung_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Phòng ban
                </label>
                <select name="phong_ban_id" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">-- Chọn phòng ban --</option>
                    @foreach($phongBans as $phongBan)
                        <option value="{{ $phongBan->id }}" {{ old('phong_ban_id') == $phongBan->id ? 'selected' : '' }}>
                            {{ $phongBan->ten_phong_ban }}
                        </option>
                    @endforeach
                </select>
                @error('phong_ban_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Kinh nghiệm (năm)
                    </label>
                    <input type="number" name="kinh_nghiem" value="{{ old('kinh_nghiem', 0) }}" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                           step="0.5" min="0">
                    @error('kinh_nghiem')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Lương mong muốn (VNĐ)
                    </label>
                    <input type="number" name="luong_mong_muon" value="{{ old('luong_mong_muon') }}" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                           min="0" placeholder="VD: 10000000">
                    @error('luong_mong_muon')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Trạng thái <span class="text-red-500">*</span>
                </label>
                <select name="trang_thai" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm" required>
                    <option value="moi_nop" {{ old('trang_thai') == 'moi_nop' ? 'selected' : '' }}>Mới nộp</option>
                    <option value="cho_duyet" {{ old('trang_thai') == 'cho_duyet' ? 'selected' : '' }}>Chờ duyệt</option>
                    <option value="da_duyet" {{ old('trang_thai') == 'da_duyet' ? 'selected' : '' }}>Đã duyệt</option>
                    <option value="dat" {{ old('trang_thai') == 'dat' ? 'selected' : '' }}>Trúng tuyển</option>
                    <option value="khong_dat" {{ old('trang_thai') == 'khong_dat' ? 'selected' : '' }}>Không đạt</option>
                </select>
                @error('trang_thai')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    CV
                </label>
                <input type="file" name="cv" accept=".pdf,.doc,.docx" 
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Hỗ trợ: PDF, DOC, DOCX (tối đa 5MB)</p>
                @error('cv')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Ghi chú
                </label>
                <textarea name="ghi_chu" rows="3" 
                          class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                          placeholder="Nhập ghi chú...">{{ old('ghi_chu') }}</textarea>
                @error('ghi_chu')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition shadow-sm">
                    Thêm ứng viên
                </button>
                <a href="{{ route('admin.ung_vien.index') }}" 
                   class="px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium transition">
                    Hủy
                </a>
            </div>
        </form>
    </div>
</div>
@endsection