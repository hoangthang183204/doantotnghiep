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

            <a href="{{ route('admin.ho-so.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                ← Quay lại
            </a>
        </div>

        <form method="POST" action="{{ route('admin.ho-so.update', $hoSo->id) }}" enctype="multipart/form-data"
            class="grid grid-cols-1 md:grid-cols-2 gap-6">

            @csrf
            @method('PUT')

            {{-- ========== CỘT 1: THÔNG TIN CÁ NHÂN ========== --}}

            {{-- HỌ --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Họ <span
                        class="text-red-500">*</span></label>
                <input type="text" name="ho" value="{{ old('ho', $hoSo->ho) }}"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('ho')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- TÊN --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tên <span
                        class="text-red-500">*</span></label>
                <input type="text" name="ten" value="{{ old('ten', $hoSo->ten) }}"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('ten')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- MÃ NHÂN VIÊN (CHỈ XEM) --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Mã nhân viên</label>
                <input type="text" value="{{ $hoSo->ma_nhan_vien }}" readonly disabled
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed">
            </div>

            {{-- EMAIL (TỪ BẢNG NGUOI_DUNG) --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Email <span
                        class="text-red-500">*</span></label>
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
                <input type="date" name="ngay_sinh"
                    value="{{ old('ngay_sinh', $hoSo->ngay_sinh ? $hoSo->ngay_sinh->format('Y-m-d') : '') }}"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('ngay_sinh')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- GIỚI TÍNH --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Giới tính</label>
                <select name="gioi_tinh"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">--- Chọn ---</option>
                    <option value="nam" {{ old('gioi_tinh', $hoSo->gioi_tinh) == 'nam' ? 'selected' : '' }}>Nam</option>
                    <option value="nu" {{ old('gioi_tinh', $hoSo->gioi_tinh) == 'nu' ? 'selected' : '' }}>Nữ</option>
                    <option value="khac" {{ old('gioi_tinh', $hoSo->gioi_tinh) == 'khac' ? 'selected' : '' }}>Khác
                    </option>
                </select>
                @error('gioi_tinh')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- TÌNH TRẠNG HÔN NHÂN --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tình trạng hôn nhân</label>
                <select name="tinh_trang_hon_nhan"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">--- Chọn ---</option>
                    <option value="doc_than"
                        {{ old('tinh_trang_hon_nhan', $hoSo->tinh_trang_hon_nhan) == 'doc_than' ? 'selected' : '' }}>Độc
                        thân</option>
                    <option value="da_ket_hon"
                        {{ old('tinh_trang_hon_nhan', $hoSo->tinh_trang_hon_nhan) == 'da_ket_hon' ? 'selected' : '' }}>Đã
                        kết hôn</option>
                    <option value="ly_hon"
                        {{ old('tinh_trang_hon_nhan', $hoSo->tinh_trang_hon_nhan) == 'ly_hon' ? 'selected' : '' }}>Ly hôn
                    </option>
                    <option value="goa"
                        {{ old('tinh_trang_hon_nhan', $hoSo->tinh_trang_hon_nhan) == 'goa' ? 'selected' : '' }}>Góa
                    </option>
                </select>
                @error('tinh_trang_hon_nhan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ========== CỘT 2: ĐỊA CHỈ & GIẤY TỜ ========== --}}

            {{-- ĐỊA CHỈ HIỆN TẠI --}}
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Địa chỉ hiện tại</label>
                <input type="text" name="dia_chi_hien_tai"
                    value="{{ old('dia_chi_hien_tai', $hoSo->dia_chi_hien_tai) }}"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('dia_chi_hien_tai')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ĐỊA CHỈ THƯỜNG TRÚ --}}
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Địa chỉ thường trú</label>
                <input type="text" name="dia_chi_thuong_tru"
                    value="{{ old('dia_chi_thuong_tru', $hoSo->dia_chi_thuong_tru) }}"
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

            {{-- ========== LIÊN HỆ KHẨN CẤP ========== --}}

            {{-- LIÊN HỆ KHẨN CẤP - HỌ TÊN --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Liên hệ khẩn cấp (Họ
                    tên)</label>
                <input type="text" name="lien_he_khan_cap"
                    value="{{ old('lien_he_khan_cap', $hoSo->lien_he_khan_cap) }}"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('lien_he_khan_cap')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- LIÊN HỆ KHẨN CẤP - SĐT --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Liên hệ khẩn cấp
                    (SĐT)</label>
                <input type="text" name="sdt_khan_cap" value="{{ old('sdt_khan_cap', $hoSo->sdt_khan_cap) }}"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('sdt_khan_cap')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- LIÊN HỆ KHẨN CẤP - QUAN HỆ --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Liên hệ khẩn cấp (Quan
                    hệ)</label>
                <input type="text" name="quan_he_khan_cap"
                    value="{{ old('quan_he_khan_cap', $hoSo->quan_he_khan_cap) }}"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('quan_he_khan_cap')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ========== ẢNH ========== --}}

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

            {{-- ========== ⭐ UPLOAD CV ========== --}}

            {{-- ========== ⭐ UPLOAD CV ========== --}}

            <div class="md:col-span-2 border-t border-gray-200 dark:border-gray-700 pt-4 mt-2">
                <h3 class="text-md font-semibold text-gray-800 dark:text-white mb-3">📄 CV (Hồ sơ năng lực)</h3>
            </div>

            <div class="md:col-span-2">
                {{-- Hiển thị CV hiện tại --}}
                @if ($hoSo->cv)
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-3 border border-blue-200 dark:border-blue-800">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="font-medium text-blue-700 dark:text-blue-300">📎 CV hiện tại</span>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $hoSo->cv->ten_file_goc }}</p>
                                <p class="text-xs text-gray-500">{{ $hoSo->cv->kich_thuoc }} • {{ $hoSo->cv->loai_mime }}
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.ho-so.view-cv', $hoSo->cv->id) }}" target="_blank"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-sm transition">
                                    👁️ Xem
                                </a>
                                <a href="{{ asset('storage/' . $hoSo->cv->duong_dan_file) }}" download
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1.5 rounded-lg text-sm transition">
                                    ⬇️ Tải
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div
                        class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 mb-3 border border-yellow-200 dark:border-yellow-800">
                        <p class="text-sm text-yellow-700 dark:text-yellow-300">
                            ⚠️ Chưa có CV. Vui lòng tải lên file CV mới.
                        </p>
                    </div>
                @endif

                {{-- Upload CV mới --}}
                <div
                    class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-blue-500 transition">
                    <div class="text-4xl mb-2">📄</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <span class="font-semibold text-blue-600 dark:text-blue-400">Tải file CV lên</span>
                        hoặc kéo thả vào đây
                    </p>
                    <input type="file" name="file_cv" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                    <p class="text-xs text-gray-500 mt-2">
                        📌 Hỗ trợ: PDF, DOC, DOCX, JPG, PNG (tối đa 5MB)
                    </p>
                </div>
                @error('file_cv')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ========== ⭐ UPLOAD HỢP ĐỒNG (THÊM MỚI) ========== --}}

            <div class="md:col-span-2 border-t border-gray-200 dark:border-gray-700 pt-4 mt-2">
                <h3 class="text-md font-semibold text-gray-800 dark:text-white mb-3">📄 Hợp đồng lao động</h3>
            </div>

            <div class="md:col-span-2">
                {{-- Hiển thị hợp đồng hiện tại --}}
                @php
                    $hopDongHienTai = $hoSo->hop_dong->where('trang_thai_hop_dong', 'hieu_luc')->first();
                @endphp

                @if ($hopDongHienTai && $hopDongHienTai->file_hop_dong_da_ky)
                    @php
                        $filePath = storage_path('app/public/' . $hopDongHienTai->file_hop_dong_da_ky);
                        $fileExists = file_exists($filePath);
                    @endphp
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-3 border border-blue-200 dark:border-blue-800">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="font-medium text-blue-700 dark:text-blue-300">📎 Hợp đồng hiện tại</span>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $hopDongHienTai->so_hop_dong }} -
                                    {{ basename($hopDongHienTai->file_hop_dong_da_ky) }}
                                </p>
                                @if (!$fileExists)
                                    <p class="text-xs text-red-500">⚠️ File không tồn tại trên server</p>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                @if ($fileExists)
                                    <a href="{{ route('admin.ho-so.view-contract', $hopDongHienTai->id) }}"
                                        target="_blank"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-sm transition">
                                        👁️ Xem
                                    </a>
                                    <a href="{{ asset('storage/' . $hopDongHienTai->file_hop_dong_da_ky) }}" download
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1.5 rounded-lg text-sm transition">
                                        ⬇️ Tải
                                    </a>
                                @else
                                    <span class="text-red-500 text-sm px-2 py-1">⚠️ File bị thiếu</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div
                        class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 mb-3 border border-yellow-200 dark:border-yellow-800">
                        <p class="text-sm text-yellow-700 dark:text-yellow-300">
                            ⚠️ Chưa có file hợp đồng. Vui lòng tải lên file hợp đồng mới.
                        </p>
                    </div>
                @endif

                {{-- Upload hợp đồng mới --}}
                <div
                    class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-blue-500 transition">
                    <div class="text-4xl mb-2">📄</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <span class="font-semibold text-blue-600 dark:text-blue-400">Tải file hợp đồng lên</span>
                        hoặc kéo thả vào đây
                    </p>
                    <input type="file" name="file_hop_dong" accept=".pdf,.doc,.docx"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                    <p class="text-xs text-gray-500 mt-2">
                        📌 Hỗ trợ: PDF, DOC, DOCX (tối đa 5MB)
                    </p>
                </div>
                @error('file_hop_dong')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ========== ⭐ KỸ NĂNG CHUYÊN MÔN (THÊM MỚI) ========== --}}

            <div class="md:col-span-2 border-t border-gray-200 dark:border-gray-700 pt-4 mt-2">
                <h3 class="text-md font-semibold text-gray-800 dark:text-white mb-3">🛠️ Kỹ năng chuyên môn</h3>
            </div>

            <div class="md:col-span-2" id="kyNangContainer">
                {{-- Kỹ năng hiện tại --}}
                @if ($hoSo->ky_nang && $hoSo->ky_nang->count() > 0)
                    @foreach ($hoSo->ky_nang as $index => $item)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3 ky-nang-row">
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tên kỹ
                                    năng</label>
                                <input type="text" name="ky_nang_ten[]" value="{{ $item->ten_ky_nang }}"
                                    placeholder="VD: Python"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div class="flex gap-2 items-end">
                                <div class="flex-1">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Cấp
                                        độ</label>
                                    <select name="ky_nang_cap_do[]"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                        <option value="Cơ bản" {{ $item->cap_do == 'Cơ bản' ? 'selected' : '' }}>🌱 Cơ bản
                                        </option>
                                        <option value="Trung cấp" {{ $item->cap_do == 'Trung cấp' ? 'selected' : '' }}>📚
                                            Trung cấp</option>
                                        <option value="Thành thạo" {{ $item->cap_do == 'Thành thạo' ? 'selected' : '' }}>⚡
                                            Thành thạo</option>
                                        <option value="Chuyên gia" {{ $item->cap_do == 'Chuyên gia' ? 'selected' : '' }}>
                                            🏆 Chuyên gia</option>
                                    </select>
                                </div>
                                <input type="hidden" name="ky_nang_id[]" value="{{ $item->id }}">
                                <button type="button" onclick="this.closest('.ky-nang-row').remove()"
                                    class="text-red-500 hover:text-red-700 px-3 py-2 text-sm font-medium border border-red-300 rounded-lg hover:bg-red-50 transition">
                                    ✕ Xóa
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Mặc định 1 dòng trống --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3 ky-nang-row">
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tên kỹ
                                năng</label>
                            <input type="text" name="ky_nang_ten[]" placeholder="VD: Python"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div class="flex gap-2 items-end">
                            <div class="flex-1">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Cấp
                                    độ</label>
                                <select name="ky_nang_cap_do[]"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                    <option value="Cơ bản">🌱 Cơ bản</option>
                                    <option value="Trung cấp" selected>📚 Trung cấp</option>
                                    <option value="Thành thạo">⚡ Thành thạo</option>
                                    <option value="Chuyên gia">🏆 Chuyên gia</option>
                                </select>
                            </div>
                            <input type="hidden" name="ky_nang_id[]" value="">
                            <button type="button" onclick="this.closest('.ky-nang-row').remove()"
                                class="text-red-500 hover:text-red-700 px-3 py-2 text-sm font-medium border border-red-300 rounded-lg hover:bg-red-50 transition">
                                ✕ Xóa
                            </button>
                        </div>
                    </div>
                @endif

                {{-- Nút thêm kỹ năng --}}
                <button type="button" onclick="addKyNang()"
                    class="mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium border border-blue-300 rounded-lg px-4 py-2 hover:bg-blue-50 transition">
                    ➕ Thêm kỹ năng
                </button>
                <p class="text-xs text-gray-400 mt-2">💡 Nhập kỹ năng và cấp độ tương ứng</p>
            </div>

            {{-- ========== ⭐ CHỨNG CHỈ (THÊM MỚI) ========== --}}

            <div class="md:col-span-2 border-t border-gray-200 dark:border-gray-700 pt-4 mt-2">
                <h3 class="text-md font-semibold text-gray-800 dark:text-white mb-3">🏅 Chứng chỉ</h3>
            </div>

            <div class="md:col-span-2" id="chungChiContainer">
                {{-- Chứng chỉ hiện tại --}}
                @if ($hoSo->chung_chi && $hoSo->chung_chi->count() > 0)
                    @foreach ($hoSo->chung_chi as $index => $item)
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3 chung-chi-row">
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tên chứng
                                    chỉ</label>
                                <input type="text" name="chung_chi_ten[]" value="{{ $item->ten_chung_chi }}"
                                    placeholder="VD: AWS Certified Developer"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tổ chức
                                    cấp</label>
                                <input type="text" name="chung_chi_to_chuc[]" value="{{ $item->to_chuc_cap }}"
                                    placeholder="VD: Amazon"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Năm
                                    cấp</label>
                                <input type="number" name="chung_chi_nam[]" value="{{ $item->nam_cap }}"
                                    placeholder="2025"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div class="flex gap-2 items-end">
                                <div class="flex-1">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ngày hết
                                        hạn</label>
                                    <input type="date" name="chung_chi_het_han[]"
                                        value="{{ $item->ngay_het_han ? $item->ngay_het_han->format('Y-m-d') : '' }}"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <input type="hidden" name="chung_chi_id[]" value="{{ $item->id }}">
                                <button type="button" onclick="this.closest('.chung-chi-row').remove()"
                                    class="text-red-500 hover:text-red-700 px-3 py-2 text-sm font-medium border border-red-300 rounded-lg hover:bg-red-50 transition">
                                    ✕ Xóa
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Mặc định 1 dòng trống --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3 chung-chi-row">
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tên chứng
                                chỉ</label>
                            <input type="text" name="chung_chi_ten[]" placeholder="VD: AWS Certified Developer"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tổ chức
                                cấp</label>
                            <input type="text" name="chung_chi_to_chuc[]" placeholder="VD: Amazon"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Năm cấp</label>
                            <input type="number" name="chung_chi_nam[]" placeholder="2025"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div class="flex gap-2 items-end">
                            <div class="flex-1">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ngày hết
                                    hạn</label>
                                <input type="date" name="chung_chi_het_han[]"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <input type="hidden" name="chung_chi_id[]" value="">
                            <button type="button" onclick="this.closest('.chung-chi-row').remove()"
                                class="text-red-500 hover:text-red-700 px-3 py-2 text-sm font-medium border border-red-300 rounded-lg hover:bg-red-50 transition">
                                ✕ Xóa
                            </button>
                        </div>
                    </div>
                @endif

                {{-- Nút thêm chứng chỉ --}}
                <button type="button" onclick="addChungChi()"
                    class="mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium border border-blue-300 rounded-lg px-4 py-2 hover:bg-blue-50 transition">
                    ➕ Thêm chứng chỉ
                </button>
                <p class="text-xs text-gray-400 mt-2">💡 Để trống ngày hết hạn nếu chứng chỉ có hiệu lực vĩnh viễn</p>
            </div>

            {{-- ========== ⭐ DỰ ÁN ĐÃ THAM GIA (THÊM MỚI) ========== --}}

            <div class="md:col-span-2 border-t border-gray-200 dark:border-gray-700 pt-4 mt-2">
                <h3 class="text-md font-semibold text-gray-800 dark:text-white mb-3">🚀 Dự án đã tham gia</h3>
            </div>

            <div class="md:col-span-2" id="duAnContainer">
                {{-- Dự án hiện tại --}}
                @if ($hoSo->du_an && $hoSo->du_an->count() > 0)
                    @foreach ($hoSo->du_an as $index => $item)
                        <div
                            class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-3 du-an-row border border-gray-200 dark:border-gray-600">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tên dự
                                        án</label>
                                    <input type="text" name="du_an_ten[]" value="{{ $item->ten_du_an }}"
                                        placeholder="VD: Hệ thống HRM"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Vai
                                        trò</label>
                                    <input type="text" name="du_an_vai_tro[]" value="{{ $item->vai_tro }}"
                                        placeholder="VD: Lead Developer"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ngày bắt
                                        đầu</label>
                                    <input type="date" name="du_an_bat_dau[]"
                                        value="{{ $item->ngay_bat_dau->format('Y-m-d') }}"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ngày kết
                                        thúc</label>
                                    <input type="date" name="du_an_ket_thuc[]"
                                        value="{{ $item->ngay_ket_thuc ? $item->ngay_ket_thuc->format('Y-m-d') : '' }}"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                <div class="md:col-span-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Mô
                                        tả</label>
                                    <textarea name="du_an_mo_ta[]" rows="2" placeholder="Mô tả ngắn về dự án..."
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">{{ $item->mo_ta }}</textarea>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Trạng
                                        thái</label>
                                    <div class="flex gap-2">
                                        <select name="du_an_trang_thai[]"
                                            class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                            <option value="Đang thực hiện"
                                                {{ $item->trang_thai == 'Đang thực hiện' ? 'selected' : '' }}>🔄 Đang thực
                                                hiện</option>
                                            <option value="Hoàn thành"
                                                {{ $item->trang_thai == 'Hoàn thành' ? 'selected' : '' }}>✅ Hoàn thành
                                            </option>
                                            <option value="Tạm dừng"
                                                {{ $item->trang_thai == 'Tạm dừng' ? 'selected' : '' }}>⏸️ Tạm dừng
                                            </option>
                                        </select>
                                        <input type="hidden" name="du_an_id[]" value="{{ $item->id }}">
                                        <button type="button" onclick="this.closest('.du-an-row').remove()"
                                            class="text-red-500 hover:text-red-700 px-3 py-2 text-sm font-medium border border-red-300 rounded-lg hover:bg-red-50 transition">
                                            ✕ Xóa
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Mặc định 1 dòng trống --}}
                    <div
                        class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-3 du-an-row border border-gray-200 dark:border-gray-600">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tên dự
                                    án</label>
                                <input type="text" name="du_an_ten[]" placeholder="VD: Hệ thống HRM"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Vai
                                    trò</label>
                                <input type="text" name="du_an_vai_tro[]" placeholder="VD: Lead Developer"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ngày bắt
                                    đầu</label>
                                <input type="date" name="du_an_bat_dau[]"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ngày kết
                                    thúc</label>
                                <input type="date" name="du_an_ket_thuc[]"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div class="md:col-span-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Mô
                                    tả</label>
                                <textarea name="du_an_mo_ta[]" rows="2" placeholder="Mô tả ngắn về dự án..."
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Trạng
                                    thái</label>
                                <div class="flex gap-2">
                                    <select name="du_an_trang_thai[]"
                                        class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                        <option value="Đang thực hiện">🔄 Đang thực hiện</option>
                                        <option value="Hoàn thành" selected>✅ Hoàn thành</option>
                                        <option value="Tạm dừng">⏸️ Tạm dừng</option>
                                    </select>
                                    <input type="hidden" name="du_an_id[]" value="">
                                    <button type="button" onclick="this.closest('.du-an-row').remove()"
                                        class="text-red-500 hover:text-red-700 px-3 py-2 text-sm font-medium border border-red-300 rounded-lg hover:bg-red-50 transition">
                                        ✕ Xóa
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Nút thêm dự án --}}
                <button type="button" onclick="addDuAn()"
                    class="mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium border border-blue-300 rounded-lg px-4 py-2 hover:bg-blue-50 transition">
                    ➕ Thêm dự án
                </button>
                <p class="text-xs text-gray-400 mt-2">💡 Mỗi dự án là một block riêng biệt, có thể thêm nhiều dự án</p>
            </div>

            {{-- ========== ⭐ NGƯỜI PHỤ THUỘC (THÊM MỚI) ========== --}}

            <div class="md:col-span-2 border-t border-gray-200 dark:border-gray-700 pt-4 mt-2">
                <h3 class="text-md font-semibold text-gray-800 dark:text-white mb-3">👨‍👩‍👦 Người phụ thuộc (Giảm trừ
                    thuế TNCN)</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">💡 Mỗi người phụ thuộc được giảm trừ 6.200.000
                    VNĐ/tháng (NQ 110/2025, áp dụng từ 2026)</p>
            </div>

            <div class="md:col-span-2" id="nguoiPhuThuocContainer">
                {{-- Người phụ thuộc hiện tại --}}
                @if ($hoSo->nguoiPhuThuoc && $hoSo->nguoiPhuThuoc->count() > 0)
                    @foreach ($hoSo->nguoiPhuThuoc as $index => $item)
                        <div
                            class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3 nguoi-phu-thuoc-row bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Họ
                                    tên</label>
                                <input type="text" name="npt_ho_ten[]" value="{{ $item->ho_ten }}"
                                    placeholder="VD: Nguyễn Văn A"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ngày
                                    sinh</label>
                                <input type="date" name="npt_ngay_sinh[]"
                                    value="{{ $item->ngay_sinh ? $item->ngay_sinh->format('Y-m-d') : '' }}"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Quan
                                    hệ</label>
                                <select name="npt_quan_he[]"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                    <option value="con" {{ $item->quan_he == 'con' ? 'selected' : '' }}>Con</option>
                                    <option value="vo" {{ $item->quan_he == 'vo' ? 'selected' : '' }}>Vợ</option>
                                    <option value="chong" {{ $item->quan_he == 'chong' ? 'selected' : '' }}>Chồng
                                    </option>
                                    <option value="cha" {{ $item->quan_he == 'cha' ? 'selected' : '' }}>Cha</option>
                                    <option value="me" {{ $item->quan_he == 'me' ? 'selected' : '' }}>Mẹ</option>
                                    <option value="khac" {{ $item->quan_he == 'khac' ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>
                            <div class="flex gap-2 items-end">
                                <div class="flex-1">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Mã số
                                        thuế</label>
                                    <input type="text" name="npt_ma_so_thue[]" value="{{ $item->ma_so_thue }}"
                                        placeholder="VD: 1234567890-2"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <input type="hidden" name="npt_id[]" value="{{ $item->id }}">
                                <button type="button" onclick="this.closest('.nguoi-phu-thuoc-row').remove()"
                                    class="text-red-500 hover:text-red-700 px-3 py-2 text-sm font-medium border border-red-300 rounded-lg hover:bg-red-50 transition">
                                    ✕ Xóa
                                </button>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tính giảm trừ từ</label>
                                <input type="date" name="npt_ngay_bat_dau[]"
                                    value="{{ $item->ngay_bat_dau ? $item->ngay_bat_dau->format('Y-m-d') : '' }}"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                <p class="text-xs text-gray-400 mt-1">Tháng phát sinh nghĩa vụ nuôi dưỡng</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Kết thúc giảm trừ</label>
                                <input type="date" name="npt_ngay_ket_thuc[]"
                                    value="{{ $item->ngay_ket_thuc ? $item->ngay_ket_thuc->format('Y-m-d') : '' }}"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                <p class="text-xs text-gray-400 mt-1">Bỏ trống nếu vẫn đang giảm trừ</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Mặc định 1 dòng trống --}}
                    <div
                        class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3 nguoi-phu-thuoc-row bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Họ tên</label>
                            <input type="text" name="npt_ho_ten[]" placeholder="VD: Nguyễn Văn A"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ngày
                                sinh</label>
                            <input type="date" name="npt_ngay_sinh[]"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Quan hệ</label>
                            <select name="npt_quan_he[]"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="con">Con</option>
                                <option value="vo">Vợ</option>
                                <option value="chong">Chồng</option>
                                <option value="cha">Cha</option>
                                <option value="me">Mẹ</option>
                                <option value="khac">Khác</option>
                            </select>
                        </div>
                        <div class="flex gap-2 items-end">
                            <div class="flex-1">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Mã số
                                    thuế</label>
                                <input type="text" name="npt_ma_so_thue[]" placeholder="VD: 1234567890-2"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <input type="hidden" name="npt_id[]" value="">
                            <button type="button" onclick="this.closest('.nguoi-phu-thuoc-row').remove()"
                                class="text-red-500 hover:text-red-700 px-3 py-2 text-sm font-medium border border-red-300 rounded-lg hover:bg-red-50 transition">
                                ✕ Xóa
                            </button>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tính giảm trừ từ</label>
                            <input type="date" name="npt_ngay_bat_dau[]"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            <p class="text-xs text-gray-400 mt-1">Tháng phát sinh nghĩa vụ nuôi dưỡng</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Kết thúc giảm trừ</label>
                            <input type="date" name="npt_ngay_ket_thuc[]"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            <p class="text-xs text-gray-400 mt-1">Bỏ trống nếu vẫn đang giảm trừ</p>
                        </div>
                    </div>
                @endif

                {{-- Nút thêm người phụ thuộc --}}
                <button type="button" onclick="addNguoiPhuThuoc()"
                    class="mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium border border-blue-300 rounded-lg px-4 py-2 hover:bg-blue-50 transition">
                    ➕ Thêm người phụ thuộc
                </button>
                <p class="text-xs text-gray-400 mt-2">💡 Mỗi người phụ thuộc giảm trừ 6.200.000 VNĐ/tháng (NQ 110/2025, áp dụng từ 2026)</p>
            </div>

            {{-- ========== ⭐ ĐÀO TẠO (THÊM MỚI) ========== --}}

            <div class="md:col-span-2 border-t border-gray-200 dark:border-gray-700 pt-4 mt-2">
                <h3 class="text-md font-semibold text-gray-800 dark:text-white mb-3">🎓 Đào tạo đã tham gia</h3>
            </div>

            <div class="md:col-span-2" id="daoTaoContainer">
                {{-- Đào tạo hiện tại --}}
                @if ($hoSo->dao_tao && $hoSo->dao_tao->count() > 0)
                    @foreach ($hoSo->dao_tao as $index => $item)
                        <div
                            class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-3 dao-tao-row border border-gray-200 dark:border-gray-600">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tên khóa
                                        học</label>
                                    <input type="text" name="dt_ten_khoa_hoc[]" value="{{ $item->ten_khoa_hoc }}"
                                        placeholder="VD: AWS Cloud Practitioner"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tổ chức
                                        đào tạo</label>
                                    <input type="text" name="dt_to_chuc[]" value="{{ $item->to_chuc }}"
                                        placeholder="VD: Amazon Web Services"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ngày bắt
                                        đầu</label>
                                    <input type="date" name="dt_ngay_bat_dau[]"
                                        value="{{ $item->ngay_bat_dau ? $item->ngay_bat_dau->format('Y-m-d') : '' }}"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ngày kết
                                        thúc</label>
                                    <input type="date" name="dt_ngay_ket_thuc[]"
                                        value="{{ $item->ngay_ket_thuc ? $item->ngay_ket_thuc->format('Y-m-d') : '' }}"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Kết
                                        quả</label>
                                    <input type="text" name="dt_ket_qua[]" value="{{ $item->ket_qua }}"
                                        placeholder="VD: Đạt 92%"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Chi
                                        phí</label>
                                    <input type="number" name="dt_chi_phi[]" value="{{ $item->chi_phi }}"
                                        placeholder="VD: 5000000"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Có chứng
                                        chỉ</label>
                                    <select name="dt_co_chung_chi[]"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                        <option value="1" {{ $item->co_chung_chi ? 'selected' : '' }}>✅ Có</option>
                                        <option value="0" {{ !$item->co_chung_chi ? 'selected' : '' }}>❌ Không
                                        </option>
                                    </select>
                                </div>
                                <div class="flex gap-2 items-end">
                                    <div class="flex-1">
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ghi
                                            chú</label>
                                        <input type="text" name="dt_ghi_chu[]" value="{{ $item->ghi_chu }}"
                                            placeholder="Ghi chú..."
                                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                    </div>
                                    <input type="hidden" name="dt_id[]" value="{{ $item->id }}">
                                    <button type="button" onclick="this.closest('.dao-tao-row').remove()"
                                        class="text-red-500 hover:text-red-700 px-3 py-2 text-sm font-medium border border-red-300 rounded-lg hover:bg-red-50 transition">
                                        ✕ Xóa
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Mặc định 1 dòng trống --}}
                    <div
                        class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-3 dao-tao-row border border-gray-200 dark:border-gray-600">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tên khóa
                                    học</label>
                                <input type="text" name="dt_ten_khoa_hoc[]" placeholder="VD: AWS Cloud Practitioner"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tổ chức đào
                                    tạo</label>
                                <input type="text" name="dt_to_chuc[]" placeholder="VD: Amazon Web Services"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ngày bắt
                                    đầu</label>
                                <input type="date" name="dt_ngay_bat_dau[]"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ngày kết
                                    thúc</label>
                                <input type="date" name="dt_ngay_ket_thuc[]"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Kết
                                    quả</label>
                                <input type="text" name="dt_ket_qua[]" placeholder="VD: Đạt 92%"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Chi
                                    phí</label>
                                <input type="number" name="dt_chi_phi[]" placeholder="VD: 5000000"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Có chứng
                                    chỉ</label>
                                <select name="dt_co_chung_chi[]"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                    <option value="1">✅ Có</option>
                                    <option value="0" selected>❌ Không</option>
                                </select>
                            </div>
                            <div class="flex gap-2 items-end">
                                <div class="flex-1">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ghi
                                        chú</label>
                                    <input type="text" name="dt_ghi_chu[]" placeholder="Ghi chú..."
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <input type="hidden" name="dt_id[]" value="">
                                <button type="button" onclick="this.closest('.dao-tao-row').remove()"
                                    class="text-red-500 hover:text-red-700 px-3 py-2 text-sm font-medium border border-red-300 rounded-lg hover:bg-red-50 transition">
                                    ✕ Xóa
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Nút thêm đào tạo --}}
                <button type="button" onclick="addDaoTao()"
                    class="mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium border border-blue-300 rounded-lg px-4 py-2 hover:bg-blue-50 transition">
                    ➕ Thêm khóa đào tạo
                </button>
            </div>

            {{-- ========== ⭐ KHEN THƯỞNG & KỶ LUẬT (THÊM MỚI) ========== --}}

            <div class="md:col-span-2 border-t border-gray-200 dark:border-gray-700 pt-4 mt-2">
                <h3 class="text-md font-semibold text-gray-800 dark:text-white mb-3">⚖️ Khen thưởng & Kỷ luật</h3>
            </div>

            <div class="md:col-span-2" id="khenThuongContainer">
                {{-- Khen thưởng hiện tại --}}
                @if ($hoSo->khen_thuong_ky_luat && $hoSo->khen_thuong_ky_luat->count() > 0)
                    @foreach ($hoSo->khen_thuong_ky_luat as $index => $item)
                        <div
                            class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-3 khen-thuong-row border border-gray-200 dark:border-gray-600">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Loại</label>
                                    <select name="ktkl_loai[]"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                        <option value="khen_thuong" {{ $item->loai == 'khen_thuong' ? 'selected' : '' }}>
                                            🏆 Khen thưởng</option>
                                        <option value="ky_luat" {{ $item->loai == 'ky_luat' ? 'selected' : '' }}>⚠️ Kỷ
                                            luật</option>
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tên</label>
                                    <input type="text" name="ktkl_ten[]" value="{{ $item->ten }}"
                                        placeholder="VD: Nhân viên xuất sắc"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ngày</label>
                                    <input type="date" name="ktkl_ngay[]"
                                        value="{{ $item->ngay ? $item->ngay->format('Y-m-d') : '' }}"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Số
                                        tiền</label>
                                    <input type="number" name="ktkl_so_tien[]" value="{{ $item->so_tien }}"
                                        placeholder="VD: 5000000"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Nội
                                        dung</label>
                                    <input type="text" name="ktkl_noi_dung[]" value="{{ $item->noi_dung }}"
                                        placeholder="Mô tả ngắn..."
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Hình
                                        thức</label>
                                    <input type="text" name="ktkl_hinh_thuc[]" value="{{ $item->hinh_thuc }}"
                                        placeholder="VD: Tiền mặt, Giấy khen..."
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Quyết
                                        định số</label>
                                    <input type="text" name="ktkl_quyet_dinh_so[]"
                                        value="{{ $item->quyet_dinh_so }}" placeholder="VD: QD-2025-001"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div class="flex gap-2 items-end">
                                    <div class="flex-1">
                                        <label
                                            class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Người
                                            ký</label>
                                        <select name="ktkl_nguoi_ky_id[]"
                                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                            <option value="">-- Chọn --</option>
                                            @foreach ($nguoiKys as $nguoiKy)
                                                <option value="{{ $nguoiKy->id }}"
                                                    {{ $item->nguoi_ky_id == $nguoiKy->id ? 'selected' : '' }}>
                                                    {{ $nguoiKy->ho }} {{ $nguoiKy->ten }} ({{ $nguoiKy->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="ktkl_id[]" value="{{ $item->id }}">
                                    <button type="button" onclick="this.closest('.khen-thuong-row').remove()"
                                        class="text-red-500 hover:text-red-700 px-3 py-2 text-sm font-medium border border-red-300 rounded-lg hover:bg-red-50 transition">
                                        ✕ Xóa
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Mặc định 1 dòng trống --}}
                    <div
                        class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-3 khen-thuong-row border border-gray-200 dark:border-gray-600">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Loại</label>
                                <select name="ktkl_loai[]"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                    <option value="khen_thuong" selected>🏆 Khen thưởng</option>
                                    <option value="ky_luat">⚠️ Kỷ luật</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tên</label>
                                <input type="text" name="ktkl_ten[]" placeholder="VD: Nhân viên xuất sắc"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ngày</label>
                                <input type="date" name="ktkl_ngay[]"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Số
                                    tiền</label>
                                <input type="number" name="ktkl_so_tien[]" placeholder="VD: 5000000"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Nội
                                    dung</label>
                                <input type="text" name="ktkl_noi_dung[]" placeholder="Mô tả ngắn..."
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Hình
                                    thức</label>
                                <input type="text" name="ktkl_hinh_thuc[]" placeholder="VD: Tiền mặt, Giấy khen..."
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Quyết định
                                    số</label>
                                <input type="text" name="ktkl_quyet_dinh_so[]" placeholder="VD: QD-2025-001"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div class="flex gap-2 items-end">
                                <div class="flex-1">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Người
                                        ký</label>
                                    <select name="ktkl_nguoi_ky_id[]"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                                        <option value="">-- Chọn --</option>
                                        @foreach ($nguoiKys as $nguoiKy)
                                            <option value="{{ $nguoiKy->id }}">
                                                {{ $nguoiKy->ho }} {{ $nguoiKy->ten }} ({{ $nguoiKy->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" name="ktkl_id[]" value="">
                                <button type="button" onclick="this.closest('.khen-thuong-row').remove()"
                                    class="text-red-500 hover:text-red-700 px-3 py-2 text-sm font-medium border border-red-300 rounded-lg hover:bg-red-50 transition">
                                    ✕ Xóa
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Nút thêm khen thưởng --}}
                <button type="button" onclick="addKhenThuong()"
                    class="mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium border border-blue-300 rounded-lg px-4 py-2 hover:bg-blue-50 transition">
                    ➕ Thêm khen thưởng / kỷ luật
                </button>
            </div>

            {{-- ========== THÔNG TIN NGÂN HÀNG ========== --}}

            <div class="md:col-span-2 border-t border-gray-200 dark:border-gray-700 pt-4 mt-2">
                <h3 class="text-md font-semibold text-gray-800 dark:text-white mb-3">🏦 Thông tin ngân hàng (Chi trả lương)
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Thông tin này dùng để chuyển lương hàng tháng cho
                    nhân viên</p>
            </div>

            {{-- CHỦ TÀI KHOẢN --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Chủ tài khoản</label>
                <input type="text" name="chu_tai_khoan"
                    value="{{ old('chu_tai_khoan', $hoSo->chu_tai_khoan ?? '') }}"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('chu_tai_khoan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- SỐ TÀI KHOẢN --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Số tài khoản</label>
                <input type="text" name="so_tai_khoan" value="{{ old('so_tai_khoan', $hoSo->so_tai_khoan ?? '') }}"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('so_tai_khoan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- TÊN NGÂN HÀNG --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tên ngân hàng</label>
                <select name="ten_ngan_hang"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">--- Chọn ngân hàng ---</option>
                    @php
                        $banks = [
                            'Vietcombank',
                            'BIDV',
                            'VietinBank',
                            'Agribank',
                            'Techcombank',
                            'ACB',
                            'Sacombank',
                            'VPBank',
                            'TPBank',
                            'MB Bank',
                            'OCB',
                            'SHB',
                            'Eximbank',
                            'DongA Bank',
                            'Nam A Bank',
                            'PVcomBank',
                            'SeABank',
                            'MSB',
                            'KienLong Bank',
                            'Bac A Bank',
                            'VietCapital Bank',
                        ];
                    @endphp
                    @foreach ($banks as $bank)
                        <option value="{{ $bank }}"
                            {{ old('ten_ngan_hang', $hoSo->ten_ngan_hang ?? '') == $bank ? 'selected' : '' }}>
                            {{ $bank }}
                        </option>
                    @endforeach
                </select>
                @error('ten_ngan_hang')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- CHI NHÁNH --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Chi nhánh / PGD</label>
                <input type="text" name="chi_nhanh_ngan_hang"
                    value="{{ old('chi_nhanh_ngan_hang', $hoSo->chi_nhanh_ngan_hang ?? '') }}"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('chi_nhanh_ngan_hang')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ========== BẢO HIỂM & THUẾ ========== --}}

            <div class="md:col-span-2 border-t border-gray-200 dark:border-gray-700 pt-4 mt-2">
                <h3 class="text-md font-semibold text-gray-800 dark:text-white mb-3">🛡️ Bảo hiểm & Thuế</h3>
            </div>

            {{-- SỐ SỔ BHXH --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Số sổ BHXH</label>
                <input type="text" name="so_bhxh" value="{{ old('so_bhxh', $hoSo->so_bhxh ?? '') }}"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('so_bhxh')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- MÃ SỐ THUẾ TNCN --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Mã số thuế TNCN</label>
                <input type="text" name="ma_so_thue" value="{{ old('ma_so_thue', $hoSo->ma_so_thue ?? '') }}"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('ma_so_thue')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- NƠI ĐĂNG KÝ KCB --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Nơi đăng ký KCB ban
                    đầu</label>
                <input type="text" name="noi_dang_ky_kcb"
                    value="{{ old('noi_dang_ky_kcb', $hoSo->noi_dang_ky_kcb ?? '') }}"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                @error('noi_dang_ky_kcb')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ========== BUTTON ========== --}}
            <div class="md:col-span-2 flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.ho-so.index') }}"
                    class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg transition">
                    Hủy
                </a>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                    💾 Lưu thay đổi
                </button>
            </div>

        </form>

    </div>

@endsection

{{-- ============================================================ --}}
{{-- JAVASCRIPT THÊM/XÓA DÒNG --}}
{{-- ============================================================ --}}
<script>
    // ========== THÊM KỸ NĂNG ==========
    function addKyNang() {
        const html = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3 ky-nang-row">
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tên kỹ năng</label>
                <input type="text" name="ky_nang_ten[]" placeholder="VD: Python" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="flex gap-2 items-end">
                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Cấp độ</label>
                    <select name="ky_nang_cap_do[]" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="Cơ bản">🌱 Cơ bản</option>
                        <option value="Trung cấp" selected>📚 Trung cấp</option>
                        <option value="Thành thạo">⚡ Thành thạo</option>
                        <option value="Chuyên gia">🏆 Chuyên gia</option>
                    </select>
                </div>
                <input type="hidden" name="ky_nang_id[]" value="">
                <button type="button" onclick="this.closest('.ky-nang-row').remove()" 
                    class="text-red-500 hover:text-red-700 px-3 py-2 text-sm font-medium border border-red-300 rounded-lg hover:bg-red-50 transition">
                    ✕ Xóa
                </button>
            </div>
        </div>
    `;
        document.getElementById('kyNangContainer').insertBefore(
            document.createRange().createContextualFragment(html),
            document.querySelector('#kyNangContainer .mt-2')
        );
    }

    // ========== THÊM CHỨNG CHỈ ==========
    function addChungChi() {
        const html = `
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3 chung-chi-row">
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tên chứng chỉ</label>
                <input type="text" name="chung_chi_ten[]" placeholder="VD: AWS Certified Developer" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tổ chức cấp</label>
                <input type="text" name="chung_chi_to_chuc[]" placeholder="VD: Amazon" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Năm cấp</label>
                <input type="number" name="chung_chi_nam[]" placeholder="2025" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="flex gap-2 items-end">
                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ngày hết hạn</label>
                    <input type="date" name="chung_chi_het_han[]" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <input type="hidden" name="chung_chi_id[]" value="">
                <button type="button" onclick="this.closest('.chung-chi-row').remove()" 
                    class="text-red-500 hover:text-red-700 px-3 py-2 text-sm font-medium border border-red-300 rounded-lg hover:bg-red-50 transition">
                    ✕ Xóa
                </button>
            </div>
        </div>
    `;
        document.getElementById('chungChiContainer').insertBefore(
            document.createRange().createContextualFragment(html),
            document.querySelector('#chungChiContainer .mt-2')
        );
    }

    // ========== THÊM DỰ ÁN ==========
    function addDuAn() {
        const html = `
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-3 du-an-row border border-gray-200 dark:border-gray-600">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tên dự án</label>
                    <input type="text" name="du_an_ten[]" placeholder="VD: Hệ thống HRM" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Vai trò</label>
                    <input type="text" name="du_an_vai_tro[]" placeholder="VD: Lead Developer" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ngày bắt đầu</label>
                    <input type="date" name="du_an_bat_dau[]" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ngày kết thúc</label>
                    <input type="date" name="du_an_ket_thuc[]" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Mô tả</label>
                    <textarea name="du_an_mo_ta[]" rows="2" placeholder="Mô tả ngắn về dự án..." 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Trạng thái</label>
                    <div class="flex gap-2">
                        <select name="du_an_trang_thai[]" class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="Đang thực hiện">🔄 Đang thực hiện</option>
                            <option value="Hoàn thành" selected>✅ Hoàn thành</option>
                            <option value="Tạm dừng">⏸️ Tạm dừng</option>
                        </select>
                        <input type="hidden" name="du_an_id[]" value="">
                        <button type="button" onclick="this.closest('.du-an-row').remove()" 
                            class="text-red-500 hover:text-red-700 px-3 py-2 text-sm font-medium border border-red-300 rounded-lg hover:bg-red-50 transition">
                            ✕ Xóa
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
        document.getElementById('duAnContainer').insertBefore(
            document.createRange().createContextualFragment(html),
            document.querySelector('#duAnContainer .mt-2')
        );
    }

    // ========== THÊM NGƯỜI PHỤ THUỘC ==========
    function addNguoiPhuThuoc() {
        const html = `
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3 nguoi-phu-thuoc-row bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Họ tên</label>
                <input type="text" name="npt_ho_ten[]" placeholder="VD: Nguyễn Văn A" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ngày sinh</label>
                <input type="date" name="npt_ngay_sinh[]" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Quan hệ</label>
                <select name="npt_quan_he[]" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="con">Con</option>
                    <option value="vo">Vợ</option>
                    <option value="chong">Chồng</option>
                    <option value="cha">Cha</option>
                    <option value="me">Mẹ</option>
                    <option value="khac">Khác</option>
                </select>
            </div>
            <div class="flex gap-2 items-end">
                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Mã số thuế</label>
                    <input type="text" name="npt_ma_so_thue[]" placeholder="VD: 1234567890-2" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <input type="hidden" name="npt_id[]" value="">
                <button type="button" onclick="this.closest('.nguoi-phu-thuoc-row').remove()"
                    class="text-red-500 hover:text-red-700 px-3 py-2 text-sm font-medium border border-red-300 rounded-lg hover:bg-red-50 transition">
                    ✕ Xóa
                </button>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tính giảm trừ từ</label>
                <input type="date" name="npt_ngay_bat_dau[]"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                <p class="text-xs text-gray-400 mt-1">Tháng phát sinh nghĩa vụ nuôi dưỡng</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Kết thúc giảm trừ</label>
                <input type="date" name="npt_ngay_ket_thuc[]"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                <p class="text-xs text-gray-400 mt-1">Bỏ trống nếu vẫn đang giảm trừ</p>
            </div>
        </div>
    `;
        document.getElementById('nguoiPhuThuocContainer').insertBefore(
            document.createRange().createContextualFragment(html),
            document.querySelector('#nguoiPhuThuocContainer .mt-2')
        );
    }

    // ========== THÊM ĐÀO TẠO ==========
    function addDaoTao() {
        const html = `
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-3 dao-tao-row border border-gray-200 dark:border-gray-600">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tên khóa học</label>
                    <input type="text" name="dt_ten_khoa_hoc[]" placeholder="VD: AWS Cloud Practitioner" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tổ chức đào tạo</label>
                    <input type="text" name="dt_to_chuc[]" placeholder="VD: Amazon Web Services" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ngày bắt đầu</label>
                    <input type="date" name="dt_ngay_bat_dau[]" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ngày kết thúc</label>
                    <input type="date" name="dt_ngay_ket_thuc[]" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Kết quả</label>
                    <input type="text" name="dt_ket_qua[]" placeholder="VD: Đạt 92%" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Chi phí</label>
                    <input type="number" name="dt_chi_phi[]" placeholder="VD: 5000000" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Có chứng chỉ</label>
                    <select name="dt_co_chung_chi[]" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="1">✅ Có</option>
                        <option value="0" selected>❌ Không</option>
                    </select>
                </div>
                <div class="flex gap-2 items-end">
                    <div class="flex-1">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ghi chú</label>
                        <input type="text" name="dt_ghi_chu[]" placeholder="Ghi chú..." 
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <input type="hidden" name="dt_id[]" value="">
                    <button type="button" onclick="this.closest('.dao-tao-row').remove()" 
                        class="text-red-500 hover:text-red-700 px-3 py-2 text-sm font-medium border border-red-300 rounded-lg hover:bg-red-50 transition">
                        ✕ Xóa
                    </button>
                </div>
            </div>
        </div>
    `;
        document.getElementById('daoTaoContainer').insertBefore(
            document.createRange().createContextualFragment(html),
            document.querySelector('#daoTaoContainer .mt-2')
        );
    }

    // ========== THÊM KHEN THƯỞNG & KỶ LUẬT ==========
    function addKhenThuong() {
        const html = `
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-3 khen-thuong-row border border-gray-200 dark:border-gray-600">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Loại</label>
                    <select name="ktkl_loai[]" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="khen_thuong" selected>🏆 Khen thưởng</option>
                        <option value="ky_luat">⚠️ Kỷ luật</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Tên</label>
                    <input type="text" name="ktkl_ten[]" placeholder="VD: Nhân viên xuất sắc" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Ngày</label>
                    <input type="date" name="ktkl_ngay[]" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Số tiền</label>
                    <input type="number" name="ktkl_so_tien[]" placeholder="VD: 5000000" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Nội dung</label>
                    <input type="text" name="ktkl_noi_dung[]" placeholder="Mô tả ngắn..." 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Hình thức</label>
                    <input type="text" name="ktkl_hinh_thuc[]" placeholder="VD: Tiền mặt, Giấy khen..." 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Quyết định số</label>
                    <input type="text" name="ktkl_quyet_dinh_so[]" placeholder="VD: QD-2025-001" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div class="flex gap-2 items-end">
                    <div class="flex-1">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-1">Người ký</label>
                        <select name="ktkl_nguoi_ky_id[]" 
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">-- Chọn --</option>
                            @foreach ($nguoiKys as $nguoiKy)
                                <option value="{{ $nguoiKy->id }}">
                                    {{ $nguoiKy->ho }} {{ $nguoiKy->ten }} ({{ $nguoiKy->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="ktkl_id[]" value="">
                    <button type="button" onclick="this.closest('.khen-thuong-row').remove()" 
                        class="text-red-500 hover:text-red-700 px-3 py-2 text-sm font-medium border border-red-300 rounded-lg hover:bg-red-50 transition">
                        ✕ Xóa
                    </button>
                </div>
            </div>
        </div>
    `;
        document.getElementById('khenThuongContainer').insertBefore(
            document.createRange().createContextualFragment(html),
            document.querySelector('#khenThuongContainer .mt-2')
        );
    }
</script>
