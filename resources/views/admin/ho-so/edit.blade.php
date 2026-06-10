@extends('layouts.admin')

@section('title', 'Chỉnh sửa hồ sơ nhân viên')

@section('content')

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-6">

        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">Chỉnh sửa hồ sơ</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Cập nhật thông tin nhân viên: <strong>{{ $hoSo->ho }} {{ $hoSo->ten }}</strong>
                </p>
            </div>

            <a href="{{ route('admin.ho-so.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                ← Quay lại
            </a>
        </div>

        <form method="POST" action="{{ route('admin.ho-so.update', $hoSo->id) }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">

            @csrf
            @method('PUT')

            {{-- HỌ --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Họ <span class="text-red-500">*</span></label>
                <input type="text" name="ho" value="{{ old('ho', $hoSo->ho) }}" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('ho')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- TÊN --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tên <span class="text-red-500">*</span></label>
                <input type="text" name="ten" value="{{ old('ten', $hoSo->ten) }}" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('ten')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- MÃ NHÂN VIÊN (CHỈ XEM, KHÔNG SỬA) --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Mã nhân viên</label>
                <input type="text" value="{{ $hoSo->ma_nhan_vien }}" readonly disabled
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed">
            </div>

            {{-- EMAIL (LẤY TỪ BẢNG NGUOI_DUNG) --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" value="{{ $hoSo->nguoi_dung->email ?? '---' }}" readonly disabled
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                <p class="text-xs text-gray-400 mt-1">Email không thể sửa ở đây, vui lòng vào mục Tài khoản</p>
            </div>

            {{-- SỐ ĐIỆN THOẠI --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Số điện thoại</label>
                <input type="text" name="so_dien_thoai" value="{{ old('so_dien_thoai', $hoSo->so_dien_thoai) }}" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('so_dien_thoai')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- NGÀY SINH --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ngày sinh</label>
                <input type="date" name="ngay_sinh" value="{{ old('ngay_sinh', $hoSo->ngay_sinh) }}" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('ngay_sinh')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- GIỚI TÍNH --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Giới tính</label>
                <select name="gioi_tinh" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">--- Chọn ---</option>
                    <option value="nam" {{ old('gioi_tinh', $hoSo->gioi_tinh) == 'nam' ? 'selected' : '' }}>Nam</option>
                    <option value="nu" {{ old('gioi_tinh', $hoSo->gioi_tinh) == 'nu' ? 'selected' : '' }}>Nữ</option>
                    <option value="khac" {{ old('gioi_tinh', $hoSo->gioi_tinh) == 'khac' ? 'selected' : '' }}>Khác</option>
                </select>
                @error('gioi_tinh')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ĐỊA CHỈ HIỆN TẠI --}}
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Địa chỉ hiện tại</label>
                <input type="text" name="dia_chi_hien_tai" value="{{ old('dia_chi_hien_tai', $hoSo->dia_chi_hien_tai) }}" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('dia_chi_hien_tai')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ĐỊA CHỈ THƯỜNG TRÚ --}}
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Địa chỉ thường trú</label>
                <input type="text" name="dia_chi_thuong_tru" value="{{ old('dia_chi_thuong_tru', $hoSo->dia_chi_thuong_tru) }}" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('dia_chi_thuong_tru')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- CMND/CCCD --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">CMND/CCCD</label>
                <input type="text" name="cmnd_cccd" value="{{ old('cmnd_cccd', $hoSo->cmnd_cccd) }}" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('cmnd_cccd')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- SỐ HỘ CHIẾU --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Số hộ chiếu</label>
                <input type="text" name="so_ho_chieu" value="{{ old('so_ho_chieu', $hoSo->so_ho_chieu) }}" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('so_ho_chieu')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- TÌNH TRẠNG HÔN NHÂN --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tình trạng hôn nhân</label>
                <select name="tinh_trang_hon_nhan" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">--- Chọn ---</option>
                    <option value="doc_than" {{ old('tinh_trang_hon_nhan', $hoSo->tinh_trang_hon_nhan) == 'doc_than' ? 'selected' : '' }}>Độc thân</option>
                    <option value="da_ket_hon" {{ old('tinh_trang_hon_nhan', $hoSo->tinh_trang_hon_nhan) == 'da_ket_hon' ? 'selected' : '' }}>Đã kết hôn</option>
                    <option value="ly_hon" {{ old('tinh_trang_hon_nhan', $hoSo->tinh_trang_hon_nhan) == 'ly_hon' ? 'selected' : '' }}>Ly hôn</option>
                    <option value="goa" {{ old('tinh_trang_hon_nhan', $hoSo->tinh_trang_hon_nhan) == 'goa' ? 'selected' : '' }}>Góa</option>
                </select>
                @error('tinh_trang_hon_nhan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- LIÊN HỆ KHẨN CẤP - HỌ TÊN --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Liên hệ khẩn cấp (Họ tên)</label>
                <input type="text" name="lien_he_khan_cap" value="{{ old('lien_he_khan_cap', $hoSo->lien_he_khan_cap) }}" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('lien_he_khan_cap')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- LIÊN HỆ KHẨN CẤP - SĐT --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Liên hệ khẩn cấp (SĐT)</label>
                <input type="text" name="sdt_khan_cap" value="{{ old('sdt_khan_cap', $hoSo->sdt_khan_cap) }}" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('sdt_khan_cap')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- LIÊN HỆ KHẨN CẤP - QUAN HỆ --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Liên hệ khẩn cấp (Quan hệ)</label>
                <input type="text" name="quan_he_khan_cap" value="{{ old('quan_he_khan_cap', $hoSo->quan_he_khan_cap) }}" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('quan_he_khan_cap')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ẢNH ĐẠI DIỆN --}}
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-2">Ảnh đại diện</label>

                @if ($hoSo->anh_dai_dien)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $hoSo->anh_dai_dien) }}" alt="Avatar"
                            class="w-24 h-24 rounded-full object-cover border-2 border-gray-300 shadow-sm">
                        <p class="text-xs text-gray-400 mt-1">Ảnh hiện tại</p>
                    </div>
                @endif

                <input type="file" name="anh_dai_dien" accept="image/*" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                <p class="text-xs text-gray-500 mt-1">JPG, PNG, WEBP tối đa 2MB. Để trống nếu không muốn thay đổi.</p>
                @error('anh_dai_dien')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ẢNH CCCD MẶT TRƯỚC --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-2">Ảnh CCCD mặt trước</label>

                @if ($hoSo->anh_cccd_truoc)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $hoSo->anh_cccd_truoc) }}" alt="CCCD mặt trước"
                            class="w-32 h-auto rounded-lg border border-gray-300 shadow-sm">
                    </div>
                @endif

                <input type="file" name="anh_cccd_truoc" accept="image/*" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                <p class="text-xs text-gray-500 mt-1">JPG, PNG, WEBP tối đa 2MB</p>
                @error('anh_cccd_truoc')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ẢNH CCCD MẶT SAU --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-2">Ảnh CCCD mặt sau</label>

                @if ($hoSo->anh_cccd_sau)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $hoSo->anh_cccd_sau) }}" alt="CCCD mặt sau"
                            class="w-32 h-auto rounded-lg border border-gray-300 shadow-sm">
                    </div>
                @endif

                <input type="file" name="anh_cccd_sau" accept="image/*" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                <p class="text-xs text-gray-500 mt-1">JPG, PNG, WEBP tối đa 2MB</p>
                @error('anh_cccd_sau')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- BUTTON --}}
            <div class="md:col-span-2 flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.ho-so.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg transition">
                    Hủy
                </a>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                    💾 Lưu thay đổi
                </button>
            </div>

        </form>

    </div>

@endsection