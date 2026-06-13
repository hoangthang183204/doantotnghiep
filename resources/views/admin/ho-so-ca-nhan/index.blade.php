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
            <div class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-3 rounded-t-md font-semibold text-sm flex items-center mb-4">
                <i class="fas fa-user w-5 text-center mr-2"></i> Thông Tin Tài Khoản
            </div>
            <div class="px-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tên đăng nhập: <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-900 text-gray-500 cursor-not-allowed sm:text-sm" value="{{ $user->ten_dang_nhap }}" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email: <span class="text-red-500">*</span></label>
                        <input type="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-900 text-gray-500 cursor-not-allowed sm:text-sm" value="{{ $user->email }}" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phòng ban: <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-900 text-gray-500 cursor-not-allowed sm:text-sm" value="{{ $user->phong_ban->ten_phong_ban ?? 'Chưa cập nhật' }}" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Chức vụ: <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-900 text-gray-500 cursor-not-allowed sm:text-sm" value="{{ $user->chuc_vu->ten ?? 'Chưa cập nhật' }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        {{-- KHỐI FORM 1: ĐỔI MẬT KHẨU --}}
        <form action="{{ route('admin.ho-so-ca-nhan.change-password') }}" method="POST">
            @csrf
            <div class="mb-8">
                <div class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-3 rounded-t-md font-semibold text-sm flex items-center mb-4">
                    <i class="fas fa-lock w-5 text-center mr-2"></i> Đổi Mật Khẩu
                </div>
                <div class="px-2">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mật khẩu hiện tại <span class="text-red-500">*</span></label>
                            <input type="password" name="current_password" class="w-full px-3 py-2 border @error('current_password') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror dark:bg-gray-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mật khẩu mới <span class="text-red-500">*</span></label>
                            <input type="password" name="new_password" class="w-full px-3 py-2 border @error('new_password') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror dark:bg-gray-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('new_password')
                                <p class="text-red-500 text-xs mt-1 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Xác nhận mật khẩu <span class="text-red-500">*</span></label>
                            <input type="password" name="new_password_confirmation" class="w-full px-3 py-2 border @error('new_password_confirmation') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror dark:bg-gray-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('new_password_confirmation')
                                <p class="text-red-500 text-xs mt-1 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                    <div class="flex justify-end mt-4">
                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md text-sm transition font-medium">
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
                <div class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-3 rounded-t-md font-semibold text-sm flex items-center mb-4">
                    <i class="fas fa-id-badge w-5 text-center mr-2"></i> Thông Tin Cá Nhân
                </div>
                <div class="px-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mã nhân viên</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-900 text-gray-500 cursor-not-allowed sm:text-sm" value="{{ $user->hoSo->ma_nhan_vien ?? '' }}" readonly>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Họ <span class="text-red-500">*</span></label>
                                <input type="text" name="ho" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ $user->hoSo->ho ?? '' }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tên <span class="text-red-500">*</span></label>
                                <input type="text" name="ten" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ $user->hoSo->ten ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email công ty</label>
                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 bg-gray-50 text-gray-500 sm:text-sm dark:bg-gray-900 dark:border-gray-600 cursor-not-allowed" value="{{ $user->hoSo->email_cong_ty ?? $user->email }}" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Số điện thoại <span class="text-red-500">*</span></label>
                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300"><i class="fas fa-phone-alt"></i></span>
                                <input type="text" name="so_dien_thoai" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600" value="{{ $user->hoSo->so_dien_thoai ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ngày sinh</label>
                            <input type="date" name="ngay_sinh" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ isset($user->hoSo->ngay_sinh) ? $user->hoSo->ngay_sinh->format('Y-m-d') : '' }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Giới tính</label>
                            <select name="gioi_tinh" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="nam" {{ ($user->hoSo->gioi_tinh ?? '') == 'nam' ? 'selected' : '' }}>Nam</option>
                                <option value="nu" {{ ($user->hoSo->gioi_tinh ?? '') == 'nu' ? 'selected' : '' }}>Nữ</option>
                                <option value="khac" {{ ($user->hoSo->gioi_tinh ?? '') == 'khac' ? 'selected' : '' }}>Khác</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tình trạng hôn nhân</label>
                            <select name="tinh_trang_hon_nhan" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="da_ket_hon" {{ ($user->hoSo->tinh_trang_hon_nhan ?? '') == 'da_ket_hon' ? 'selected' : '' }}>Đã kết hôn</option>
                                <option value="doc_than" {{ ($user->hoSo->tinh_trang_hon_nhan ?? '') == 'doc_than' ? 'selected' : '' }}>Độc thân</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <div class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-3 rounded-t-md font-semibold text-sm flex items-center mb-4">
                    <i class="fas fa-map-marker-alt w-5 text-center mr-2"></i> Thông Tin Địa Chỉ & Giấy Tờ
                </div>
                <div class="px-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Địa chỉ hiện tại</label>
                            <input type="text" name="dia_chi_hien_tai" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ $user->hoSo->dia_chi_hien_tai ?? '' }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Địa chỉ thường trú</label>
                            <input type="text" name="dia_chi_thuong_tru" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ $user->hoSo->dia_chi_thuong_tru ?? '' }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CMND/CCCD<span class="text-red-500">*</span></label>
                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300"><i class="far fa-id-card"></i></span>
                                <input type="text" name="cmnd_cccd" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600" value="{{ $user->hoSo->cmnd_cccd ?? '' }}">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Số hộ chiếu</label>
                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300"><i class="fas fa-passport"></i></span>
                                <input type="text" name="so_ho_chieu" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600" value="{{ $user->hoSo->so_ho_chieu ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <div class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-3 rounded-t-md font-semibold text-sm flex items-center mb-4">
                    <i class="fas fa-heartbeat w-5 text-center mr-2"></i> Liên Hệ Khẩn Cấp
                </div>
                <div class="px-2">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Người liên hệ</label>
                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300"><i class="fas fa-user"></i></span>
                                <input type="text" name="lien_he_khan_cap" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600" value="{{ $user->hoSo->lien_he_khan_cap ?? '' }}">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">SĐT khẩn cấp</label>
                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300"><i class="fas fa-phone-alt"></i></span>
                                <input type="text" name="sdt_khan_cap" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600" value="{{ $user->hoSo->sdt_khan_cap ?? '' }}">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quan hệ</label>
                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300"><i class="fas fa-users"></i></span>
                                <input type="text" name="quan_he_khan_cap" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600" value="{{ $user->hoSo->quan_he_khan_cap ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <div class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-3 rounded-t-md font-semibold text-sm flex items-center mb-4">
                    <i class="fas fa-image w-5 text-center mr-2"></i> Upload Ảnh
                </div>
                <div class="px-2 flex flex-col md:flex-row gap-8">
                    <div class="flex-grow">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Chọn ảnh đại diện mới</label>
                        <input type="file" name="avatar" onchange="previewImage(this, 'avatarPreview', 'avatarIcon')" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-200" accept="image/png, image/jpeg, image/gif">
                        <p class="text-xs text-gray-500 mt-2">Định dạng: JPG, PNG, GIF. Kích thước tối đa: 2MB</p>
                    </div>
                    <div class="w-32 flex-shrink-0">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 text-center">Ảnh hiện tại</label>
                        <div class="w-24 h-24 mx-auto border border-dashed border-gray-300 dark:border-gray-600 rounded-md flex items-center justify-center bg-gray-50 dark:bg-gray-800">
                            <img id="avatarPreview" src="{{ isset($user->hoSo->anh_dai_dien) ? asset('storage/' . $user->hoSo->anh_dai_dien) : '#' }}" alt="Avatar" class="w-full h-full object-cover rounded-md {{ isset($user->hoSo->anh_dai_dien) ? '' : 'hidden' }}">
                            <i id="avatarIcon" class="far fa-image text-gray-400 text-2xl {{ isset($user->hoSo->anh_dai_dien) ? 'hidden' : '' }}"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mb-8">
                <div class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-3 rounded-t-md font-semibold text-sm flex items-center mb-4">
                    <i class="far fa-id-card w-5 text-center mr-2"></i> Ảnh CCCD (Mặt trước)
                </div>
                <div class="px-2 flex flex-col md:flex-row gap-8">
                    <div class="flex-grow">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Chọn ảnh mặt trước mới</label>
                        <input type="file" name="anh_cccd_truoc" onchange="previewImage(this, 'cccdTruocPreview', 'cccdTruocIcon')" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-200" accept="image/png, image/jpeg, image/gif">
                        <p class="text-xs text-gray-500 mt-2">Định dạng: JPG, PNG, GIF. Kích thước tối đa: 2MB</p>
                    </div>
                    <div class="w-48 flex-shrink-0">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 text-center">Ảnh hiện tại</label>
                        <div class="w-40 h-24 mx-auto border border-dashed border-gray-300 dark:border-gray-600 rounded-md flex items-center justify-center bg-gray-50 dark:bg-gray-800">
                            <img id="cccdTruocPreview" src="{{ isset($user->hoSo->anh_cccd_truoc) ? asset('storage/' . $user->hoSo->anh_cccd_truoc) : '#' }}" alt="CCCD Trước" class="w-full h-full object-cover rounded-md {{ isset($user->hoSo->anh_cccd_truoc) ? '' : 'hidden' }}">
                            <i id="cccdTruocIcon" class="far fa-image text-gray-400 text-2xl {{ isset($user->hoSo->anh_cccd_truoc) ? 'hidden' : '' }}"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <div class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-3 rounded-t-md font-semibold text-sm flex items-center mb-4">
                    <i class="far fa-id-card w-5 text-center mr-2"></i> Ảnh CCCD (Mặt sau)
                </div>
                <div class="px-2 flex flex-col md:flex-row gap-8">
                    <div class="flex-grow">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Chọn ảnh mặt sau mới</label>
                        <input type="file" name="anh_cccd_sau" onchange="previewImage(this, 'cccdSauPreview', 'cccdSauIcon')" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-200" accept="image/png, image/jpeg, image/gif">
                        <p class="text-xs text-gray-500 mt-2">Định dạng: JPG, PNG, GIF. Kích thước tối đa: 2MB</p>
                    </div>
                    <div class="w-48 flex-shrink-0">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 text-center">Ảnh hiện tại</label>
                        <div class="w-40 h-24 mx-auto border border-dashed border-gray-300 dark:border-gray-600 rounded-md flex items-center justify-center bg-gray-50 dark:bg-gray-800">
                            <img id="cccdSauPreview" src="{{ isset($user->hoSo->anh_cccd_sau) ? asset('storage/' . $user->hoSo->anh_cccd_sau) : '#' }}" alt="CCCD Sau" class="w-full h-full object-cover rounded-md {{ isset($user->hoSo->anh_cccd_sau) ? '' : 'hidden' }}">
                            <i id="cccdSauIcon" class="far fa-image text-gray-400 text-2xl {{ isset($user->hoSo->anh_cccd_sau) ? 'hidden' : '' }}"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-8 pt-5 border-t border-gray-200 dark:border-gray-700">
                <button type="reset" class="px-5 py-2 border border-gray-300 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition">Đặt lại</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition shadow-sm font-medium">
                    Lưu thay đổi
                </button>
            </div>
        </form>

    </div>
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