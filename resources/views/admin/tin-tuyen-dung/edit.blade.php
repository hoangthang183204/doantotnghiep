<!-- resources/views/admin/tin-tuyen-dung/edit.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.tin-tuyen-dung.index') }}" 
           class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Chỉnh sửa tin tuyển dụng
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
        <form method="POST" action="{{ route('admin.tin-tuyen-dung.update', $tinTuyenDung->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Mã tin (chỉ hiển thị) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Mã tin
                </label>
                <input type="text" value="{{ $tinTuyenDung->ma }}" 
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white bg-gray-100 dark:bg-gray-700 cursor-not-allowed outline-none text-sm"
                       disabled>
            </div>

            <!-- Tiêu đề -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Tiêu đề <span class="text-red-500">*</span>
                </label>
                <input type="text" name="tieu_de" value="{{ old('tieu_de', $tinTuyenDung->tieu_de) }}" 
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                       placeholder="Nhập tiêu đề tin tuyển dụng" required>
                @error('tieu_de')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phòng ban và Chức vụ -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Phòng ban <span class="text-red-500">*</span>
                    </label>
                    <select name="phong_ban_id" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm" required>
                        <option value="">-- Chọn phòng ban --</option>
                        @foreach($phongBans as $phongBan)
                            <option value="{{ $phongBan->id }}" {{ old('phong_ban_id', $tinTuyenDung->phong_ban_id) == $phongBan->id ? 'selected' : '' }}>
                                {{ $phongBan->ten_phong_ban }}
                            </option>
                        @endforeach
                    </select>
                    @error('phong_ban_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Chức vụ <span class="text-red-500">*</span>
                    </label>
                    <select name="chuc_vu_id" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm" required>
                        <option value="">-- Chọn chức vụ --</option>
                        @foreach($chucVus as $chucVu)
                            <option value="{{ $chucVu->id }}" {{ old('chuc_vu_id', $tinTuyenDung->chuc_vu_id) == $chucVu->id ? 'selected' : '' }}>
                                {{ $chucVu->ten }}
                            </option>
                        @endforeach
                    </select>
                    @error('chuc_vu_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Vai trò và Loại hợp đồng -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Vai trò
                    </label>
                    <select name="vai_tro_id" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">-- Chọn vai trò --</option>
                        @foreach($vaiTros as $vaiTro)
                            <option value="{{ $vaiTro->id }}" {{ old('vai_tro_id', $tinTuyenDung->vai_tro_id) == $vaiTro->id ? 'selected' : '' }}>
                                {{ $vaiTro->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('vai_tro_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Loại hợp đồng <span class="text-red-500">*</span>
                    </label>
                    <select name="loai_hop_dong" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm" required>
                        <option value="thu_viec" {{ old('loai_hop_dong', $tinTuyenDung->loai_hop_dong) == 'thu_viec' ? 'selected' : '' }}>Thử việc</option>
                        <option value="xac_dinh_thoi_han" {{ old('loai_hop_dong', $tinTuyenDung->loai_hop_dong) == 'xac_dinh_thoi_han' ? 'selected' : '' }}>Xác định thời hạn</option>
                        <option value="khong_xac_dinh_thoi_han" {{ old('loai_hop_dong', $tinTuyenDung->loai_hop_dong) == 'khong_xac_dinh_thoi_han' ? 'selected' : '' }}>Không xác định thời hạn</option>
                    </select>
                    @error('loai_hop_dong')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Cấp độ kinh nghiệm và Số lượng -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Cấp độ kinh nghiệm <span class="text-red-500">*</span>
                    </label>
                    <select name="cap_do_kinh_nghiem" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm" required>
                        <option value="intern" {{ old('cap_do_kinh_nghiem', $tinTuyenDung->cap_do_kinh_nghiem) == 'intern' ? 'selected' : '' }}>Thực tập sinh</option>
                        <option value="fresher" {{ old('cap_do_kinh_nghiem', $tinTuyenDung->cap_do_kinh_nghiem) == 'fresher' ? 'selected' : '' }}>Fresher</option>
                        <option value="junior" {{ old('cap_do_kinh_nghiem', $tinTuyenDung->cap_do_kinh_nghiem) == 'junior' ? 'selected' : '' }}>Junior</option>
                        <option value="middle" {{ old('cap_do_kinh_nghiem', $tinTuyenDung->cap_do_kinh_nghiem) == 'middle' ? 'selected' : '' }}>Middle</option>
                        <option value="senior" {{ old('cap_do_kinh_nghiem', $tinTuyenDung->cap_do_kinh_nghiem) == 'senior' ? 'selected' : '' }}>Senior</option>
                    </select>
                    @error('cap_do_kinh_nghiem')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Số lượng cần tuyển <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="so_vi_tri" value="{{ old('so_vi_tri', $tinTuyenDung->so_vi_tri) }}" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                           min="1" required>
                    @error('so_vi_tri')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Kinh nghiệm tối thiểu/tối đa -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Kinh nghiệm tối thiểu (năm)
                    </label>
                    <input type="number" name="kinh_nghiem_toi_thieu" value="{{ old('kinh_nghiem_toi_thieu', $tinTuyenDung->kinh_nghiem_toi_thieu ?? 0) }}" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                           min="0">
                    @error('kinh_nghiem_toi_thieu')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Kinh nghiệm tối đa (năm)
                    </label>
                    <input type="number" name="kinh_nghiem_toi_da" value="{{ old('kinh_nghiem_toi_da', $tinTuyenDung->kinh_nghiem_toi_da ?? 0) }}" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                           min="0">
                    @error('kinh_nghiem_toi_da')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Mức lương -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Lương tối thiểu (VNĐ)
                    </label>
                    <input type="number" name="luong_toi_thieu" value="{{ old('luong_toi_thieu', $tinTuyenDung->luong_toi_thieu) }}" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                           step="1000" min="0" placeholder="VD: 5000000">
                    @error('luong_toi_thieu')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Lương tối đa (VNĐ)
                    </label>
                    <input type="number" name="luong_toi_da" value="{{ old('luong_toi_da', $tinTuyenDung->luong_toi_da) }}" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                           step="1000" min="0" placeholder="VD: 15000000">
                    @error('luong_toi_da')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Trạng thái và Hạn nộp -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Trạng thái <span class="text-red-500">*</span>
                    </label>
                    <select name="trang_thai" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm" required>
                        <option value="nhap" {{ old('trang_thai', $tinTuyenDung->trang_thai) == 'nhap' ? 'selected' : '' }}>Nháp</option>
                        <option value="dang_tuyen" {{ old('trang_thai', $tinTuyenDung->trang_thai) == 'dang_tuyen' ? 'selected' : '' }}>Đăng tuyển</option>
                        <option value="tam_dung" {{ old('trang_thai', $tinTuyenDung->trang_thai) == 'tam_dung' ? 'selected' : '' }}>Tạm dừng</option>
                        <option value="ket_thuc" {{ old('trang_thai', $tinTuyenDung->trang_thai) == 'ket_thuc' ? 'selected' : '' }}>Kết thúc</option>
                    </select>
                    @error('trang_thai')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Hạn nộp hồ sơ <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="han_nop_ho_so" 
                           value="{{ old('han_nop_ho_so', $tinTuyenDung->han_nop_ho_so ? $tinTuyenDung->han_nop_ho_so->format('Y-m-d') : '') }}" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                           required>
                    @error('han_nop_ho_so')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Mô tả công việc -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Mô tả công việc <span class="text-red-500">*</span>
                </label>
                <textarea name="mo_ta_cong_viec" rows="5" 
                          class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                          placeholder="Mô tả chi tiết công việc..." required>{{ old('mo_ta_cong_viec', $tinTuyenDung->mo_ta_cong_viec) }}</textarea>
                @error('mo_ta_cong_viec')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Yêu cầu -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Yêu cầu <span class="text-gray-400 text-xs">(mỗi dòng là 1 yêu cầu)</span>
                </label>
                <textarea name="yeu_cau" rows="4" 
                          class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                          placeholder="Tốt nghiệp Đại học chuyên ngành CNTT&#10;Có ít nhất 2 năm kinh nghiệm&#10;Thành thạo PHP/Laravel">{{ old('yeu_cau', $yeuCau) }}</textarea>
                @error('yeu_cau')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phúc lợi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Phúc lợi <span class="text-gray-400 text-xs">(mỗi dòng là 1 phúc lợi)</span>
                </label>
                <textarea name="phuc_loi" rows="4" 
                          class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                          placeholder="Lương thưởng hấp dẫn&#10;Bảo hiểm đầy đủ&#10;Đào tạo chuyên sâu">{{ old('phuc_loi', $phucLoi) }}</textarea>
                @error('phuc_loi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kỹ năng yêu cầu -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Kỹ năng yêu cầu <span class="text-gray-400 text-xs">(mỗi dòng là 1 kỹ năng)</span>
                </label>
                <textarea name="ky_nang_yeu_cau" rows="3" 
                          class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                          placeholder="PHP&#10;Laravel&#10;MySQL&#10;Git">{{ old('ky_nang_yeu_cau', $kyNang) }}</textarea>
                @error('ky_nang_yeu_cau')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Trình độ học vấn -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Trình độ học vấn
                </label>
                <input type="text" name="trinh_do_hoc_van" value="{{ old('trinh_do_hoc_van', $tinTuyenDung->trinh_do_hoc_van) }}" 
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                       placeholder="VD: Đại học, Cao đẳng,...">
                @error('trinh_do_hoc_van')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tùy chọn -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-300">
                    <input type="checkbox" name="lam_viec_tu_xa" value="1" {{ old('lam_viec_tu_xa', $tinTuyenDung->lam_viec_tu_xa) ? 'checked' : '' }}>
                    <span>Cho phép làm việc từ xa</span>
                </label>
                <label class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-300">
                    <input type="checkbox" name="tuyen_gap" value="1" {{ old('tuyen_gap', $tinTuyenDung->tuyen_gap) ? 'checked' : '' }}>
                    <span>Tuyển gấp</span>
                </label>
            </div>

            <!-- Submit -->
            <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition shadow-sm">
                    Cập nhật
                </button>
                <a href="{{ route('admin.tin-tuyen-dung.index') }}" 
                   class="px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium transition">
                    Hủy
                </a>
            </div>
        </form>
    </div>
</div>
@endsection