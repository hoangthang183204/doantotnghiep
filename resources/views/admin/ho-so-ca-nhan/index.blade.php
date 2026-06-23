@extends('layouts.admin') {{-- Hoặc tên file layout chính xác của bạn --}}

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Hồ Sơ Cá Nhân</h2>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

            {{-- THÔNG BÁO THÀNH CÔNG --}}
            @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 font-medium">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- THÔNG TIN TÀI KHOẢN (Chỉ đọc, không cần nằm trong form submit) --}}
            <div class="mb-8">
                <div
                    class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-3 rounded-t-md font-semibold text-sm flex items-center mb-4">
                    <i class="fas fa-user w-5 text-center mr-2"></i> Thông Tin Tài Khoản
                </div>
                <div class="px-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tên đăng nhập:
                                <span class="text-red-500">*</span></label>
                            <input type="text"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-900 text-gray-500 cursor-not-allowed sm:text-sm"
                                value="{{ $user->ten_dang_nhap }}" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email: <span
                                    class="text-red-500">*</span></label>
                            <input type="email"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-900 text-gray-500 cursor-not-allowed sm:text-sm"
                                value="{{ $user->email }}" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phòng ban: <span
                                    class="text-red-500">*</span></label>
                            <input type="text"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-900 text-gray-500 cursor-not-allowed sm:text-sm"
                                value="{{ $user->phong_ban->ten_phong_ban ?? 'Chưa cập nhật' }}" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Chức vụ: <span
                                    class="text-red-500">*</span></label>
                            <input type="text"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-900 text-gray-500 cursor-not-allowed sm:text-sm"
                                value="{{ $user->chuc_vu->ten ?? 'Chưa cập nhật' }}" readonly>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KHỐI FORM 1: ĐỔI MẬT KHẨU --}}
            <form action="{{ route('admin.ho-so-ca-nhan.change-password') }}" method="POST">
                @csrf
                <div class="mb-8">
                    <div
                        class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-3 rounded-t-md font-semibold text-sm flex items-center mb-4">
                        <i class="fas fa-lock w-5 text-center mr-2"></i> Đổi Mật Khẩu
                    </div>
                    <div class="px-2">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mật khẩu hiện
                                    tại <span class="text-red-500">*</span></label>
                                <input type="password" name="current_password"
                                    class="w-full px-3 py-2 border @error('current_password') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror dark:bg-gray-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('current_password')
                                    <p class="text-red-500 text-xs mt-1 font-medium"><i
                                            class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mật khẩu mới
                                    <span class="text-red-500">*</span></label>
                                <input type="password" name="new_password"
                                    class="w-full px-3 py-2 border @error('new_password') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror dark:bg-gray-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('new_password')
                                    <p class="text-red-500 text-xs mt-1 font-medium"><i
                                            class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Xác nhận mật
                                    khẩu <span class="text-red-500">*</span></label>
                                <input type="password" name="new_password_confirmation"
                                    class="w-full px-3 py-2 border @error('new_password_confirmation') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror dark:bg-gray-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('new_password_confirmation')
                                    <p class="text-red-500 text-xs mt-1 font-medium"><i
                                            class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="submit"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md text-sm transition font-medium">
                                Đổi mật khẩu
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <hr class="my-8 border-gray-200 dark:border-gray-700">

            {{-- KHỐI FORM 2: CẬP NHẬT THÔNG TIN VÀ ẢNH --}}
            <form action="{{ route('admin.ho-so-ca-nhan.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-8">
                    <div
                        class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-3 rounded-t-md font-semibold text-sm flex items-center mb-4">
                        <i class="fas fa-id-badge w-5 text-center mr-2"></i> Thông Tin Cá Nhân
                    </div>
                    <div class="px-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mã nhân
                                    viên</label>
                                <input type="text"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-900 text-gray-500 cursor-not-allowed sm:text-sm"
                                    value="{{ $user->hoSo->ma_nhan_vien ?? '' }}" readonly>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Họ <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="ho"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        value="{{ $user->hoSo->ho ?? '' }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tên <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="ten"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        value="{{ $user->hoSo->ten ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email công
                                    ty</label>
                                <div class="flex rounded-md shadow-sm">
                                    <span
                                        class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300"><i
                                            class="fas fa-envelope"></i></span>
                                    <input type="email"
                                        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 bg-gray-50 text-gray-500 sm:text-sm dark:bg-gray-900 dark:border-gray-600 cursor-not-allowed"
                                        value="{{ $user->hoSo->email_cong_ty ?? $user->email }}" readonly>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Số điện
                                    thoại <span class="text-red-500">*</span></label>
                                <div class="flex rounded-md shadow-sm">
                                    <span
                                        class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300"><i
                                            class="fas fa-phone-alt"></i></span>
                                    <input type="text" name="so_dien_thoai"
                                        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600"
                                        value="{{ $user->hoSo->so_dien_thoai ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ngày
                                    sinh</label>
                                <input type="date" name="ngay_sinh"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    value="{{ isset($user->hoSo->ngay_sinh) ? $user->hoSo->ngay_sinh->format('Y-m-d') : '' }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Giới
                                    tính</label>
                                <select name="gioi_tinh"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="nam"
                                        {{ ($user->hoSo->gioi_tinh ?? '') == 'nam' ? 'selected' : '' }}>Nam</option>
                                    <option value="nu" {{ ($user->hoSo->gioi_tinh ?? '') == 'nu' ? 'selected' : '' }}>
                                        Nữ</option>
                                    <option value="khac"
                                        {{ ($user->hoSo->gioi_tinh ?? '') == 'khac' ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tình trạng
                                    hôn nhân</label>
                                <select name="tinh_trang_hon_nhan"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="da_ket_hon"
                                        {{ ($user->hoSo->tinh_trang_hon_nhan ?? '') == 'da_ket_hon' ? 'selected' : '' }}>Đã
                                        kết hôn</option>
                                    <option value="doc_than"
                                        {{ ($user->hoSo->tinh_trang_hon_nhan ?? '') == 'doc_than' ? 'selected' : '' }}>Độc
                                        thân</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <div
                        class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-3 rounded-t-md font-semibold text-sm flex items-center mb-4">
                        <i class="fas fa-map-marker-alt w-5 text-center mr-2"></i> Thông Tin Địa Chỉ & Giấy Tờ
                    </div>
                    <div class="px-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Địa chỉ hiện
                                    tại</label>
                                <input type="text" name="dia_chi_hien_tai"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    value="{{ $user->hoSo->dia_chi_hien_tai ?? '' }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Địa chỉ
                                    thường trú</label>
                                <input type="text" name="dia_chi_thuong_tru"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    value="{{ $user->hoSo->dia_chi_thuong_tru ?? '' }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CMND/CCCD<span
                                        class="text-red-500">*</span></label>
                                <div class="flex rounded-md shadow-sm">
                                    <span
                                        class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300"><i
                                            class="far fa-id-card"></i></span>
                                    <input type="text" name="cmnd_cccd"
                                        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600"
                                        value="{{ $user->hoSo->cmnd_cccd ?? '' }}">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Số hộ
                                    chiếu</label>
                                <div class="flex rounded-md shadow-sm">
                                    <span
                                        class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300"><i
                                            class="fas fa-passport"></i></span>
                                    <input type="text" name="so_ho_chieu"
                                        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600"
                                        value="{{ $user->hoSo->so_ho_chieu ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <div
                        class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-3 rounded-t-md font-semibold text-sm flex items-center mb-4">
                        <i class="fas fa-heartbeat w-5 text-center mr-2"></i> Liên Hệ Khẩn Cấp
                    </div>
                    <div class="px-2">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Người liên
                                    hệ</label>
                                <div class="flex rounded-md shadow-sm">
                                    <span
                                        class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300"><i
                                            class="fas fa-user"></i></span>
                                    <input type="text" name="lien_he_khan_cap"
                                        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600"
                                        value="{{ $user->hoSo->lien_he_khan_cap ?? '' }}">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">SĐT khẩn
                                    cấp</label>
                                <div class="flex rounded-md shadow-sm">
                                    <span
                                        class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300"><i
                                            class="fas fa-phone-alt"></i></span>
                                    <input type="text" name="sdt_khan_cap"
                                        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600"
                                        value="{{ $user->hoSo->sdt_khan_cap ?? '' }}">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quan
                                    hệ</label>
                                <div class="flex rounded-md shadow-sm">
                                    <span
                                        class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300"><i
                                            class="fas fa-users"></i></span>
                                    <input type="text" name="quan_he_khan_cap"
                                        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600"
                                        value="{{ $user->hoSo->quan_he_khan_cap ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <div
                        class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-3 rounded-t-md font-semibold text-sm flex items-center mb-4">
                        <i class="fas fa-image w-5 text-center mr-2"></i> Upload Ảnh
                    </div>
                    <div class="px-2 flex flex-col md:flex-row gap-8">
                        <div class="flex-grow">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Chọn ảnh đại
                                diện mới</label>
                            <input type="file" name="avatar"
                                onchange="previewImage(this, 'avatarPreview', 'avatarIcon')"
                                class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-200"
                                accept="image/png, image/jpeg, image/gif">
                            <p class="text-xs text-gray-500 mt-2">Định dạng: JPG, PNG, GIF. Kích thước tối đa: 2MB</p>
                        </div>
                        <div class="w-32 flex-shrink-0">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 text-center">Ảnh
                                hiện tại</label>
                            <div
                                class="w-24 h-24 mx-auto border border-dashed border-gray-300 dark:border-gray-600 rounded-md flex items-center justify-center bg-gray-50 dark:bg-gray-800">
                                <img id="avatarPreview"
                                    src="{{ isset($user->hoSo->anh_dai_dien) ? asset('storage/' . $user->hoSo->anh_dai_dien) : '#' }}"
                                    alt="Avatar"
                                    class="w-full h-full object-cover rounded-md {{ isset($user->hoSo->anh_dai_dien) ? '' : 'hidden' }}">
                                <i id="avatarIcon"
                                    class="far fa-image text-gray-400 text-2xl {{ isset($user->hoSo->anh_dai_dien) ? 'hidden' : '' }}"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <div
                        class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-3 rounded-t-md font-semibold text-sm flex items-center mb-4">
                        <i class="far fa-id-card w-5 text-center mr-2"></i> Ảnh CCCD (Mặt trước)
                    </div>
                    <div class="px-2 flex flex-col md:flex-row gap-8">
                        <div class="flex-grow">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Chọn ảnh mặt
                                trước mới</label>
                            <input type="file" name="anh_cccd_truoc"
                                onchange="previewImage(this, 'cccdTruocPreview', 'cccdTruocIcon')"
                                class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-200"
                                accept="image/png, image/jpeg, image/gif">
                            <p class="text-xs text-gray-500 mt-2">Định dạng: JPG, PNG, GIF. Kích thước tối đa: 2MB</p>
                        </div>
                        <div class="w-48 flex-shrink-0">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 text-center">Ảnh
                                hiện tại</label>
                            <div
                                class="w-40 h-24 mx-auto border border-dashed border-gray-300 dark:border-gray-600 rounded-md flex items-center justify-center bg-gray-50 dark:bg-gray-800">
                                <img id="cccdTruocPreview"
                                    src="{{ isset($user->hoSo->anh_cccd_truoc) ? asset('storage/' . $user->hoSo->anh_cccd_truoc) : '#' }}"
                                    alt="CCCD Trước"
                                    class="w-full h-full object-cover rounded-md {{ isset($user->hoSo->anh_cccd_truoc) ? '' : 'hidden' }}">
                                <i id="cccdTruocIcon"
                                    class="far fa-image text-gray-400 text-2xl {{ isset($user->hoSo->anh_cccd_truoc) ? 'hidden' : '' }}"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <div
                        class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-3 rounded-t-md font-semibold text-sm flex items-center mb-4">
                        <i class="far fa-id-card w-5 text-center mr-2"></i> Ảnh CCCD (Mặt sau)
                    </div>
                    <div class="px-2 flex flex-col md:flex-row gap-8">
                        <div class="flex-grow">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Chọn ảnh mặt sau
                                mới</label>
                            <input type="file" name="anh_cccd_sau"
                                onchange="previewImage(this, 'cccdSauPreview', 'cccdSauIcon')"
                                class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-200"
                                accept="image/png, image/jpeg, image/gif">
                            <p class="text-xs text-gray-500 mt-2">Định dạng: JPG, PNG, GIF. Kích thước tối đa: 2MB</p>
                        </div>
                        <div class="w-48 flex-shrink-0">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 text-center">Ảnh
                                hiện tại</label>
                            <div
                                class="w-40 h-24 mx-auto border border-dashed border-gray-300 dark:border-gray-600 rounded-md flex items-center justify-center bg-gray-50 dark:bg-gray-800">
                                <img id="cccdSauPreview"
                                    src="{{ isset($user->hoSo->anh_cccd_sau) ? asset('storage/' . $user->hoSo->anh_cccd_sau) : '#' }}"
                                    alt="CCCD Sau"
                                    class="w-full h-full object-cover rounded-md {{ isset($user->hoSo->anh_cccd_sau) ? '' : 'hidden' }}">
                                <i id="cccdSauIcon"
                                    class="far fa-image text-gray-400 text-2xl {{ isset($user->hoSo->anh_cccd_sau) ? 'hidden' : '' }}"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ================= CV ================= --}}
                <div class="section-card">

                    <div class="section-header">

                        <div class="section-icon">
                            <i class="fa-solid fa-file-pdf"></i>
                        </div>

                        <div>
                            <h3 class="font-semibold text-lg">
                                Hồ sơ năng lực (CV)
                            </h3>

                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Cập nhật và quản lý CV cá nhân
                            </p>
                        </div>

                    </div>

                    @if ($user->hoSo?->hoSo?->cv)
                        @php
                            $cv = $user->hoSo->hoSo->cv;
                            $filePath = $cv->duong_dan_file ?? $cv->tep_tin;
                            $fileName = basename($filePath);
                        @endphp

                        <div
                            class="border border-gray-200 dark:border-gray-700
                            rounded-xl p-4
                            bg-gray-50 dark:bg-gray-800">

                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                                <div class="flex items-center gap-4">

                                    <div
                                        class="w-12 h-12 rounded-xl bg-red-100 text-red-600 flex items-center justify-center">

                                        <i class="fa-solid fa-file-pdf"></i>

                                    </div>

                                    <div>

                                        <div class="font-semibold text-gray-900 dark:text-white">
                                            {{ $fileName }}
                                        </div>

                                        <div class="text-sm text-gray-500">
                                            CV hiện tại trên hệ thống
                                        </div>

                                        @if ($cv->updated_at)
                                            <div class="text-xs text-gray-400 mt-1">
                                                Cập nhật:
                                                {{ $cv->updated_at->format('d/m/Y H:i') }}
                                            </div>
                                        @endif

                                    </div>

                                </div>

                                <div class="flex flex-wrap gap-2">

                                    <a href="{{ asset('storage/' . $filePath) }}" target="_blank"
                                        class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-xl
                                        border border-gray-300 dark:border-gray-600
                                        text-gray-700 dark:text-gray-200
                                        hover:bg-gray-100 dark:hover:bg-gray-800">

                                        <i class="fa-solid fa-eye"></i>
                                        Xem CV
                                    </a>

                                    <label
                                        class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-xl
                                        border border-gray-300 dark:border-gray-600
                                        text-gray-700 dark:text-gray-200
                                        hover:bg-gray-100 dark:hover:bg-gray-800">

                                        <i class="fa-solid fa-upload"></i>
                                        Chọn CV mới

                                        <input id="cv_file" type="file" name="cv_file" accept=".pdf,.doc,.docx"
                                            class="hidden" onchange="showCvFile(this)">
                                    </label>

                                </div>

                            </div>

                            <div id="cvFileName" class="mt-3 text-sm text-blue-600 dark:text-blue-400 hidden">
                            </div>

                        </div>
                    @else
                        <div
                            class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center">

                            <div class="text-5xl text-gray-300 mb-4">
                                <i class="fa-regular fa-file"></i>
                            </div>

                            <div class="font-medium text-gray-600 dark:text-gray-300">
                                Chưa có CV được tải lên
                            </div>

                            <div class="text-sm text-gray-500 mt-2 mb-5">
                                Tải CV PDF hoặc Word để hoàn thiện hồ sơ
                            </div>

                            <label
                                class="cursor-pointer inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">

                                <i class="fa-solid fa-upload"></i>
                                Chọn CV

                                <input id="cv_file" type="file" name="cv_file" accept=".pdf,.doc,.docx"
                                    class="hidden">

                            </label>

                            <div id="cvFileName" class="mt-4 text-sm text-blue-600 dark:text-blue-400 hidden">
                            </div>

                        </div>
                    @endif

                </div>

                {{-- ================= SKILLS ================= --}}
                <div class="profile-card">

                    <div class="flex items-center gap-3 mb-5">
                        <div class="section-icon">
                            <i class="fa-solid fa-brain"></i>
                        </div>

                        <div>
                            <h3 class="font-semibold text-lg">
                                Kỹ năng chuyên môn
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Cập nhật kỹ năng làm việc
                            </p>
                        </div>
                    </div>

                    @forelse($user->hoSo?->hoSo?->ky_nang ?? [] as $skill)
                        <div class="border rounded-xl p-4 mb-4">

                            <div class="grid md:grid-cols-2 gap-4">

                                <input class="input" name="skills[{{ $skill->id }}][ten_ky_nang]"
                                    value="{{ $skill->ten_ky_nang }}">

                                <select class="input" name="skills[{{ $skill->id }}][cap_do]">

                                    <option value="Cơ bản" @selected($skill->cap_do == 'Cơ bản')>
                                        Cơ bản
                                    </option>

                                    <option value="Trung cấp" @selected($skill->cap_do == 'Trung cấp')>
                                        Trung cấp
                                    </option>

                                    <option value="Thành thạo" @selected($skill->cap_do == 'Thành thạo')>
                                        Thành thạo
                                    </option>

                                    <option value="Chuyên gia" @selected($skill->cap_do == 'Chuyên gia')>
                                        Chuyên gia
                                    </option>

                                </select>

                            </div>

                        </div>

                    @empty
                        <div class="text-gray-500">
                            Chưa có dữ liệu.
                        </div>
                    @endforelse

                </div>

                {{-- ================= CERTIFICATE ================= --}}
                <div class="profile-card">

                    <div class="section-header">

                        <div class="section-icon">
                            <i class="fa-solid fa-award"></i>
                        </div>

                        <div>
                            <h3 class="font-semibold text-lg">
                                Chứng chỉ
                            </h3>

                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Chứng nhận và bằng cấp chuyên môn
                            </p>
                        </div>

                    </div>

                    @foreach ($user->hoSo?->hoSo?->chung_chi ?? [] as $cc)
                        <div class="border rounded-xl p-3 mb-3">

                            <div class="grid md:grid-cols-4 gap-3">

                                <input class="input" name="certificates[{{ $cc->id }}][ten_chung_chi]"
                                    value="{{ $cc->ten_chung_chi }}" placeholder="Tên chứng chỉ">

                                <input class="input" name="certificates[{{ $cc->id }}][to_chuc_cap]"
                                    value="{{ $cc->to_chuc_cap }}" placeholder="Tổ chức">

                                <input type="number" class="input" name="certificates[{{ $cc->id }}][nam_cap]"
                                    value="{{ $cc->nam_cap }}" placeholder="Năm">

                                <input type="date" class="input"
                                    name="certificates[{{ $cc->id }}][ngay_het_han]"
                                    value="{{ optional($cc->ngay_het_han)->format('Y-m-d') }}">

                            </div>

                        </div>
                    @endforeach

                </div>

                {{-- ================= TRAINING ================= --}}
                <div class="profile-card">

                    <div class="section-header">

                        <div class="section-icon">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>

                        <div>
                            <h3 class="font-semibold text-lg">
                                Đào tạo
                            </h3>

                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Khóa học đã tham gia
                            </p>
                        </div>

                    </div>

                    @foreach ($user->hoSo?->hoSo?->dao_tao ?? [] as $dt)
                        <div class="border rounded-xl p-3 mb-3">

                            <div class="grid md:grid-cols-5 gap-3">

                                <input class="input" name="trainings[{{ $dt->id }}][ten_khoa_hoc]"
                                    value="{{ $dt->ten_khoa_hoc }}" placeholder="Khóa học">

                                <input class="input" name="trainings[{{ $dt->id }}][to_chuc]"
                                    value="{{ $dt->to_chuc }}" placeholder="Tổ chức">

                                <input class="input" name="trainings[{{ $dt->id }}][ket_qua]"
                                    value="{{ $dt->ket_qua }}" placeholder="Kết quả">

                                <input type="date" class="input" name="trainings[{{ $dt->id }}][ngay_bat_dau]"
                                    value="{{ optional($dt->ngay_bat_dau)->format('Y-m-d') }}">

                                <input type="date" class="input"
                                    name="trainings[{{ $dt->id }}][ngay_ket_thuc]"
                                    value="{{ optional($dt->ngay_ket_thuc)->format('Y-m-d') }}">

                            </div>

                        </div>
                    @endforeach

                </div>

                {{-- ================= DEPENDENT ================= --}}
                <div class="profile-card">

                    <div class="section-header">

                        <div class="section-icon">
                            <i class="fa-solid fa-people-roof"></i>
                        </div>

                        <div>
                            <h3 class="font-semibold text-lg">
                                Người phụ thuộc
                            </h3>

                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Thông tin giảm trừ gia cảnh
                            </p>
                        </div>

                    </div>

                    @foreach ($user->hoSo?->hoSo?->nguoiPhuThuoc ?? [] as $npt)
                        <div class="border rounded-xl p-3 mb-3">

                            <div class="grid md:grid-cols-3 gap-3">

                                <input class="input" name="dependents[{{ $npt->id }}][ho_ten]"
                                    value="{{ $npt->ho_ten }}" placeholder="Họ tên">

                                <select class="input" name="dependents[{{ $npt->id }}][quan_he]">

                                    <option value="con" @selected($npt->quan_he == 'con')>
                                        Con
                                    </option>

                                    <option value="vo" @selected($npt->quan_he == 'vo')>
                                        Vợ
                                    </option>

                                    <option value="chong" @selected($npt->quan_he == 'chong')>
                                        Chồng
                                    </option>

                                    <option value="cha" @selected($npt->quan_he == 'cha')>
                                        Cha
                                    </option>

                                    <option value="me" @selected($npt->quan_he == 'me')>
                                        Mẹ
                                    </option>

                                    <option value="khac" @selected($npt->quan_he == 'khac')>
                                        Khác
                                    </option>

                                </select>

                                <input class="input" name="dependents[{{ $npt->id }}][ma_so_thue]"
                                    value="{{ $npt->ma_so_thue }}" placeholder="Mã số thuế">

                            </div>

                        </div>
                    @endforeach

                </div>

                {{-- ================= CONTRACT ================= --}}
                @php
                    $hopDong = $user->hoSo?->hoSo?->hop_dong?->first();
                @endphp

                @if ($hopDong)
                    <div class="profile-card">

                        <div class="section-header">

                            <div class="section-icon">
                                <i class="fa-solid fa-file-signature"></i>
                            </div>

                            <div>
                                <h3 class="font-semibold text-lg">
                                    Hợp đồng lao động
                                </h3>

                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Thông tin hợp đồng hiện tại
                                </p>
                            </div>

                        </div>

                        <div class="grid md:grid-cols-3 gap-4">

                            <div class="info-item">
                                <span>Số hợp đồng</span>
                                <strong>{{ $hopDong->so_hop_dong }}</strong>
                            </div>

                            <div class="info-item">
                                <span>Loại hợp đồng</span>
                                <strong>{{ $hopDong->ten_loai_hop_dong }}</strong>
                            </div>

                            <div class="info-item">
                                <span>Trạng thái</span>

                                <div>
                                    <span class="px-3 py-1 rounded-full text-xs {{ $hopDong->mau_trang_thai }}">
                                        {{ $hopDong->ten_trang_thai }}
                                    </span>
                                </div>
                            </div>

                            <div class="info-item">
                                <span>Ngày bắt đầu</span>
                                <strong>{{ $hopDong->ngay_bat_dau_format }}</strong>
                            </div>

                            <div class="info-item">
                                <span>Ngày kết thúc</span>
                                <strong>{{ $hopDong->ngay_ket_thuc_format }}</strong>
                            </div>

                            <div class="info-item">
                                <span>Hình thức</span>
                                <strong>{{ $hopDong->hinh_thuc_lam_viec ?? '---' }}</strong>
                            </div>

                            <div class="md:col-span-3 info-item">
                                <span>Địa điểm làm việc</span>
                                <strong>{{ $hopDong->dia_diem_lam_viec ?? '---' }}</strong>
                            </div>

                        </div>

                        @if ($hopDong->file_dinh_kem || $hopDong->duong_dan_file)
                            <div class="mt-5">

                                <a href="{{ asset('storage/' . ($hopDong->file_dinh_kem ?? $hopDong->duong_dan_file)) }}"
                                    target="_blank"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">

                                    <i class="fa-solid fa-paperclip"></i>
                                    Xem hợp đồng

                                </a>

                            </div>
                        @endif

                    </div>
                @else
                    <div class="profile-card">

                        <div class="text-center py-8 text-gray-500">
                            Chưa có hợp đồng lao động.
                        </div>

                    </div>
                @endif

                {{-- ================= REWARD DISCIPLINE ================= --}}
                <div class="profile-card">

                    <div class="section-header">

                        <div class="section-icon">
                            <i class="fa-solid fa-trophy"></i>
                        </div>

                        <div>
                            <h3 class="font-semibold text-lg">
                                Khen thưởng & Kỷ luật
                            </h3>

                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Lịch sử đánh giá nhân sự
                            </p>
                        </div>

                    </div>

                    @forelse($user->hoSo?->hoSo?->khen_thuong_ky_luat ?? [] as $item)
                        <div class="{{ $item->mau_loai }} rounded-lg p-4 mb-4">

                            <div class="flex justify-between items-start">

                                <div>
                                    <div class="font-semibold text-lg text-gray-900 dark:text-white">
                                        {{ $item->ten }}
                                    </div>

                                    <div class="text-sm mt-1 text-gray-600 dark:text-gray-400">
                                        {{ $item->loai_text }}
                                    </div>

                                </div>

                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ optional($item->ngay)->format('d/m/Y') }}
                                </div>

                            </div>

                            @if ($item->noi_dung)
                                <div class="mt-3 text-gray-700 dark:text-gray-300">
                                    {{ $item->noi_dung }}
                                </div>
                            @endif

                            <div class="grid md:grid-cols-3 gap-3 mt-4 text-sm text-gray-700 dark:text-gray-300">

                                <div>
                                    <strong>Hình thức:</strong><br>
                                    {{ $item->hinh_thuc ?? '---' }}
                                </div>

                                <div>
                                    <strong>Quyết định số:</strong><br>
                                    {{ $item->quyet_dinh_so ?? '---' }}
                                </div>

                                <div>
                                    <strong>Số tiền:</strong><br>
                                    @if ($item->so_tien)
                                        {{ number_format($item->so_tien, 0, ',', '.') }} VNĐ
                                    @else
                                        ---
                                    @endif
                                </div>

                            </div>

                            @if ($item->nguoiKy)
                                <div class="mt-3 text-sm text-gray-500">
                                    Người ký:
                                    {{ $item->nguoiKy->ten_dang_nhap ?? '#' . $item->nguoi_ky_id }}
                                </div>
                            @endif

                        </div>

                    @empty

                        <div class="text-center py-10 text-gray-500">
                            Chưa có dữ liệu khen thưởng hoặc kỷ luật.
                        </div>
                    @endforelse

                </div>

                <div class="flex justify-end space-x-3 mt-8 pt-5 border-t border-gray-200 dark:border-gray-700">
                    <button type="reset"
                        class="px-5 py-2 border border-gray-300 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition">Đặt
                        lại</button>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition shadow-sm font-medium">
                        Lưu thay đổi
                    </button>
                </div>
            </form>

        </div>
        {{-- ================= STYLE ================= --}}
        <style>
            .profile-card {
                background: #fff;
                border: 1px solid #e5e7eb;
                border-radius: 18px;
                padding: 20px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
            }

            .dark .profile-card {
                background: #1f2937;
                border-color: #374151;
                box-shadow: none;
            }

            .section-title {
                font-size: 16px;
                font-weight: 700;
                margin-bottom: 16px;
            }

            .form-label {
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 600;
                color: #6b7280;
            }

            .section-card {
                background: #fff;
                border: 1px solid #e5e7eb;
                border-radius: 18px;
                padding: 24px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
            }

            .dark .section-card {
                background: #1f2937;
                border-color: #374151;
            }

            .section-header {
                display: flex;
                align-items: center;
                gap: 12px;
                margin-bottom: 16px;
            }

            .section-icon {
                width: 42px;
                height: 42px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
                background: linear-gradient(135deg, #3b82f6, #2563eb);
                color: #fff;
                flex-shrink: 0;
                box-shadow: 0 4px 12px rgba(37, 99, 235, .25);
            }

            .dark .section-icon {
                background: linear-gradient(135deg, #2563eb, #1d4ed8);
                color: #fff;
            }

            .form-card {
                border: 1px solid #e5e7eb;
                border-radius: 14px;
                padding: 16px;
                transition: .2s;
            }

            .form-card:hover {
                box-shadow: 0 8px 20px rgba(0, 0, 0, .06);
            }

            .dark .form-card {
                border-color: #374151;
            }

            .input {
                width: 100%;
                height: 40px;
                padding: 0 12px;
                border: 1px solid #d1d5db;
                border-radius: 10px;
                font-size: 13px;
            }

            .input:hover {
                border-color: #93c5fd;
            }

            .input:focus {
                outline: none;
                border-color: #2563eb;
                background: #fff;
                box-shadow: 0 0 0 4px rgba(37, 99, 235, .12);
            }

            .dark .input {
                background: #111827;
                border-color: #374151;
                color: #f3f4f6;
            }

            .dark .input:focus {
                border-color: #3b82f6;
            }

            .badge-status {
                padding: 6px 12px;
                border-radius: 999px;
                font-size: 12px;
                font-weight: 600;
            }

            .info-item {
                background: #f9fafb;
                border: 1px solid #e5e7eb;
                border-radius: 12px;
                padding: 12px;
            }

            .info-item span {
                display: block;
                font-size: 12px;
                color: #6b7280;
                margin-bottom: 4px;
            }

            .info-item strong {
                font-size: 14px;
                color: #111827;
            }

            .dark .info-item {
                background: #111827;
                border-color: #374151;
            }

            .dark .info-item strong {
                color: #f3f4f6;
            }

            .dark input[type="date"]::-webkit-calendar-picker-indicator {
                filter: invert(1);
                cursor: pointer;
            }

            .profile-card h3,
            .section-card h3 {
                color: #111827;
            }

            .dark .profile-card h3,
            .dark .section-card h3 {
                color: #f9fafb;
            }

            .cv-box {
                background: #f9fafb;
                border: 1px solid #e5e7eb;
                border-radius: 12px;
            }

            .dark .cv-box {
                background: #111827;
                border-color: #374151;
            }
        </style>
    </div>

    <script>
        function previewImage(input, previewImgId, iconId) {
            if (input.files && input.files[0]) {
                const url = URL.createObjectURL(input.files[0]);
                const imgPreview = document.getElementById(previewImgId);
                const icon = document.getElementById(iconId);

                imgPreview.src = url;
                imgPreview.classList.remove('hidden');

                if (icon) {
                    icon.classList.add('hidden');
                }

                imgPreview.onload = function() {
                    URL.revokeObjectURL(imgPreview.src);
                }
            }
        }
    </script>
@endsection
