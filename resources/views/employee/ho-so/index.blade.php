@extends('layouts.employee')

@section('title', 'Hồ sơ cá nhân')

@section('content')

    <div class="space-y-6 max-w-6xl mx-auto text-gray-900 dark:text-gray-100">

        {{-- ================= FLASH ================= --}}
        @if (session('success'))
            <div
                class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-4 rounded-lg text-green-700 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div
                class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4 rounded-lg text-red-700 dark:text-red-300">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4 rounded-lg">
                <ul class="list-disc ml-6 text-sm text-red-700 dark:text-red-300">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ================= HEADER ================= --}}
        <div
            class="rounded-2xl p-6
    bg-white dark:bg-slate-800
    border border-gray-200 dark:border-slate-700
    shadow-sm
    text-gray-800 dark:text-white">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">

                <div class="flex items-center gap-4">

                    @if ($user->hoSo?->anh_dai_dien)
                        <img src="{{ asset('storage/' . $user->hoSo->anh_dai_dien) }}"
                            class="w-20 h-20 rounded-2xl object-cover border-4 border-gray-400 dark:border-slate-500">
                    @else
                        <div class="w-20 h-20 rounded-2xl bg-white/20 flex items-center justify-center text-2xl font-bold">
                            {{ strtoupper(substr($user->ho_ten, 0, 1)) }}
                        </div>
                    @endif

                    <div>
                        <h1 class="text-2xl font-bold">
                            {{ $user->ho_ten }}
                        </h1>

                        <p class="text-gray-500 dark:text-gray-400">
                            {{ $user->email }}
                        </p>

                        <div class="flex flex-wrap gap-2 mt-2">

                            <span class="px-3 py-1 rounded-full bg-gray-100 dark:bg-slate-700 text-sm">
                                {{ $user->vai_tro?->ten_hien_thi }}
                            </span>

                            <span class="px-3 py-1 rounded-full bg-gray-100 dark:bg-slate-700 text-sm">
                                {{ $user->phong_ban?->ten_phong_ban }}
                            </span>

                        </div>
                    </div>

                </div>

                {{-- ⭐⭐⭐ NÚT QUAY LẠI TRANG SHOW ⭐⭐⭐ --}}
                <a href="{{ route('employee.ho-so.show') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl
            bg-gray-500 text-white hover:bg-gray-600
            transition shadow-sm hover:shadow-md
            border border-gray-400 dark:border-gray-600">
                    <i class="fa-solid fa-arrow-left"></i>
                    Quay lại
                </a>

            </div>

        </div>

        {{-- ================= ACCOUNT INFO ================= --}}
        <div class="profile-card">
            <div class="font-semibold mb-4">Thông Tin Tài Khoản</div>

            <div class="grid md:grid-cols-2 gap-4">

                <input class="input" value="{{ $user->ten_dang_nhap }}" readonly>
                <input class="input" value="{{ $user->email }}" readonly>

                <input class="input" value="{{ $user->phong_ban?->ten_phong_ban }}" readonly>
                <input class="input" value="{{ $user->chuc_vu?->ten }}" readonly>

                <input class="input" value="{{ $user->hoSo?->email_cong_ty ?? $user->email }}" readonly>
                <input class="input" value="{{ $user->hoSo?->ma_nhan_vien ?? 'Chưa cập nhật' }}" readonly>

            </div>
        </div>


        {{-- ================= PASSWORD ================= --}}
        <div class="profile-card">

            <div class="section-header">

                <div class="section-icon">
                    <i class="fa-solid fa-lock"></i>
                </div>

                <div>
                    <h3 class="font-semibold text-lg">
                        Đổi mật khẩu
                    </h3>

                    <p class="text-sm text-gray-500">
                        Cập nhật mật khẩu đăng nhập tài khoản
                    </p>
                </div>

            </div>

            <form action="{{ route('employee.ho-so.change-password') }}" method="POST">
                @csrf

                <div class="grid md:grid-cols-3 gap-4">

                    <div>
                        <label class="form-label">
                            Mật khẩu hiện tại
                        </label>

                        <input type="password" name="current_password" class="input" placeholder="Nhập mật khẩu hiện tại">
                    </div>

                    <div>
                        <label class="form-label">
                            Mật khẩu mới
                        </label>

                        <input type="password" name="new_password" class="input" placeholder="Nhập mật khẩu mới">
                    </div>

                    <div>
                        <label class="form-label">
                            Xác nhận mật khẩu
                        </label>

                        <input type="password" name="new_password_confirmation" class="input"
                            placeholder="Nhập lại mật khẩu">
                    </div>

                </div>

                <div class="flex justify-end mt-4">

                    <button type="submit"
                        class="inline-flex items-center gap-2
                    px-5 py-2.5 rounded-xl
                    bg-blue-600 text-white
                    hover:bg-blue-700
                    transition">

                        <i class="fa-solid fa-key"></i>

                        Đổi mật khẩu

                    </button>

                </div>

            </form>

        </div>

        {{-- ================= PERSONAL INFO ================= --}}
        <div class="profile-card">
            <div class="font-semibold mb-4">Thông Tin Cá Nhân</div>

            <form action="{{ route('employee.ho-so.update') }}" method="POST" enctype="multipart/form-data"
                id="profileForm">
                @csrf
                @method('PUT')

                <div class="grid md:grid-cols-2 gap-4">

                    <input name="ho" class="input" value="{{ $user->hoSo?->ho }}" placeholder="Họ">
                    <input name="ten" class="input" value="{{ $user->hoSo?->ten }}" placeholder="Tên">

                    <input name="so_dien_thoai" class="input" value="{{ $user->hoSo?->so_dien_thoai }}" placeholder="SĐT">

                    <input type="date" name="ngay_sinh" class="input"
                        value="{{ optional($user->hoSo?->ngay_sinh)->format('Y-m-d') }}">

                    <select name="gioi_tinh" class="input">
                        <option value="nam" @selected($user->hoSo?->gioi_tinh == 'nam')>Nam</option>
                        <option value="nu" @selected($user->hoSo?->gioi_tinh == 'nu')>Nữ</option>
                        <option value="khac" @selected($user->hoSo?->gioi_tinh == 'khac')>Khác</option>
                    </select>

                    <select name="tinh_trang_hon_nhan" class="input">
                        <option value="doc_than" @selected($user->hoSo?->tinh_trang_hon_nhan == 'doc_than')>Độc thân</option>
                        <option value="da_ket_hon" @selected($user->hoSo?->tinh_trang_hon_nhan == 'da_ket_hon')>Đã kết hôn</option>
                    </select>

                </div>

                {{-- ================= ADDRESS & ID ================= --}}
                <div class="mt-6">

                    <div class="font-semibold mb-3">Địa chỉ & Giấy tờ</div>

                    <div class="grid md:grid-cols-2 gap-4">

                        <input name="dia_chi_hien_tai" class="input"
                            value="{{ old('dia_chi_hien_tai', $user->hoSo?->dia_chi_hien_tai) }}"
                            placeholder="Chưa cập nhật địa chỉ hiện tại">

                        <input name="dia_chi_thuong_tru" class="input"
                            value="{{ old('dia_chi_thuong_tru', $user->hoSo?->dia_chi_thuong_tru) }}"
                            placeholder="Chưa cập nhật địa chỉ thường trú">

                        <input name="cmnd_cccd" class="input" value="{{ old('cmnd_cccd', $user->hoSo?->cmnd_cccd) }}"
                            placeholder="Chưa cập nhật CCCD / CMND">

                        <input name="so_ho_chieu" class="input"
                            value="{{ old('so_ho_chieu', $user->hoSo?->so_ho_chieu) }}"
                            placeholder="Chưa cập nhật số hộ chiếu">

                    </div>
                </div>

                {{-- ================= EMERGENCY ================= --}}
                <div class="mt-6">
                    <div class="font-semibold mb-4">Liên hệ khẩn cấp</div>

                    <div class="grid md:grid-cols-3 gap-4">
                        <input name="lien_he_khan_cap" class="input" value="{{ $user->hoSo?->lien_he_khan_cap }}"
                            placeholder="Người liên hệ">
                        <input name="sdt_khan_cap" class="input" value="{{ $user->hoSo?->sdt_khan_cap }}"
                            placeholder="SĐT">
                        <input name="quan_he_khan_cap" class="input" value="{{ $user->hoSo?->quan_he_khan_cap }}"
                            placeholder="Quan hệ">
                    </div>
                </div>

                {{-- ================= BANK ================= --}}
                <div class="mt-6">
                    <div class="font-semibold mb-4">
                        🏦 Thông tin ngân hàng
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">

                        <input name="chu_tai_khoan" class="input"
                            value="{{ old('chu_tai_khoan', $user->hoSo?->chu_tai_khoan) }}" placeholder="Chủ tài khoản">

                        <input name="so_tai_khoan" class="input"
                            value="{{ old('so_tai_khoan', $user->hoSo?->so_tai_khoan) }}" placeholder="Số tài khoản">

                        <input name="ten_ngan_hang" class="input"
                            value="{{ old('ten_ngan_hang', $user->hoSo?->ten_ngan_hang) }}" placeholder="Tên ngân hàng">

                        <input name="chi_nhanh_ngan_hang" class="input"
                            value="{{ old('chi_nhanh_ngan_hang', $user->hoSo?->chi_nhanh_ngan_hang) }}"
                            placeholder="Chi nhánh ngân hàng">

                    </div>
                </div>

                {{-- ================= INSURANCE ================= --}}
                <div class="mt-6">
                    <div class="font-semibold mb-4">
                        🛡️ Bảo hiểm & Thuế
                    </div>

                    <div class="grid md:grid-cols-3 gap-4">

                        <input name="so_bhxh" class="input" value="{{ old('so_bhxh', $user->hoSo?->so_bhxh) }}"
                            placeholder="Số BHXH">

                        <input name="ma_so_thue" class="input"
                            value="{{ old('ma_so_thue', $user->hoSo?->ma_so_thue) }}" placeholder="Mã số thuế">

                        <input name="noi_dang_ky_kcb" class="input"
                            value="{{ old('noi_dang_ky_kcb', $user->hoSo?->noi_dang_ky_kcb) }}"
                            placeholder="Nơi đăng ký KCB">

                    </div>
                </div>

                {{-- ================= IMAGES ================= --}}
                <div class="mb-8">

                    <div class="font-semibold mb-6 mt-10">
                        Hình Ảnh & Giấy Tờ
                    </div>

                    <div class="px-2 space-y-10">

                        {{-- ================= AVATAR ================= --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Ảnh đại diện
                                </label>

                                <input type="file" name="anh_dai_dien"
                                    class="mt-2 block w-full text-sm text-gray-500 dark:text-gray-400">

                                <p class="text-xs text-gray-500 mt-2">
                                    JPG, PNG, WEBP - tối đa 2MB
                                </p>
                            </div>

                            <div class="flex justify-center">
                                <div
                                    class="w-32 h-32 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center overflow-hidden shadow-sm">

                                    @if ($user->hoSo?->anh_dai_dien)
                                        <img src="{{ asset('storage/' . $user->hoSo->anh_dai_dien) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <i class="far fa-image text-3xl text-gray-400"></i>
                                    @endif

                                </div>
                            </div>
                        </div>

                        {{-- ================= CCCD FRONT ================= --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    CCCD / CMND (mặt trước)
                                </label>

                                <input type="file" name="anh_cccd_truoc"
                                    class="mt-2 block w-full text-sm text-gray-500 dark:text-gray-400">

                                <p class="text-xs text-gray-500 mt-2">
                                    JPG, PNG, WEBP - tối đa 2MB
                                </p>
                            </div>

                            <div class="flex justify-center">
                                <div
                                    class="w-44 h-28 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center overflow-hidden shadow-sm">

                                    @if ($user->hoSo?->anh_cccd_truoc)
                                        <img src="{{ asset('storage/' . $user->hoSo->anh_cccd_truoc) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <i class="far fa-image text-3xl text-gray-400"></i>
                                    @endif

                                </div>
                            </div>
                        </div>

                        {{-- ================= CCCD BACK ================= --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    CCCD / CMND (mặt sau)
                                </label>

                                <input type="file" name="anh_cccd_sau"
                                    class="mt-2 block w-full text-sm text-gray-500 dark:text-gray-400">

                                <p class="text-xs text-gray-500 mt-2">
                                    JPG, PNG, WEBP - tối đa 2MB
                                </p>
                            </div>

                            <div class="flex justify-center">
                                <div
                                    class="w-44 h-28 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center overflow-hidden shadow-sm">

                                    @if ($user->hoSo?->anh_cccd_sau)
                                        <img src="{{ asset('storage/' . $user->hoSo->anh_cccd_sau) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <i class="far fa-image text-3xl text-gray-400"></i>
                                    @endif

                                </div>
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

                            <p class="text-sm text-gray-500">
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
                            class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 bg-gray-50 dark:bg-gray-900">

                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                                <div class="flex items-center gap-4">

                                    <div
                                        class="w-12 h-12 rounded-xl bg-red-100 text-red-600 flex items-center justify-center">

                                        <i class="fa-solid fa-file-pdf"></i>

                                    </div>

                                    <div>

                                        <div class="font-semibold">
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
                                        class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800">

                                        <i class="fa-solid fa-eye"></i>
                                        Xem CV

                                    </a>

                                    <label
                                        class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800">

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
                                    class="hidden" onchange="showCvFile(this)">

                            </label>

                            <div id="cvFileName" class="mt-4 text-sm text-blue-600 dark:text-blue-400 hidden">
                            </div>

                        </div>

                    @endif

                </div>

                {{-- ================= SKILLS ================= --}}
                <div class="profile-card">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <div class="section-icon">
                                <i class="fa-solid fa-brain"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Kỹ năng chuyên môn</h3>
                                <p class="text-sm text-gray-500">Cập nhật kỹ năng làm việc</p>
                            </div>
                        </div>
                        <button type="button" onclick="addSkill()" class="btn-add">
                            <i class="fa-solid fa-plus"></i> Thêm kỹ năng
                        </button>
                    </div>

                    <div id="skills-container">
                        @php
                            $hoSoData = $user->hoSo?->hoSo;
                            $kyNangList = $hoSoData?->ky_nang ?? [];
                        @endphp

                        @forelse($kyNangList as $skill)
                            <div class="item skill-item border rounded-xl p-4 mb-4" data-id="{{ $skill->id }}">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-sm text-gray-500">#{{ $loop->iteration }}</span>
                                    <button type="button" onclick="removeItem(this, 'skills')" class="btn-remove">
                                        <i class="fa-solid fa-trash"></i> Xóa
                                    </button>
                                </div>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Tên kỹ năng
                                            *</label>
                                        <input class="input" name="skills[{{ $skill->id }}][ten_ky_nang]"
                                            value="{{ $skill->ten_ky_nang }}" placeholder="VD: PHP, Laravel, JavaScript">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Cấp độ</label>
                                        <select class="input" name="skills[{{ $skill->id }}][cap_do]">
                                            <option value="Cơ bản" @selected($skill->cap_do == 'Cơ bản')>Cơ bản</option>
                                            <option value="Trung cấp" @selected($skill->cap_do == 'Trung cấp')>Trung cấp</option>
                                            <option value="Thành thạo" @selected($skill->cap_do == 'Thành thạo')>Thành thạo</option>
                                            <option value="Chuyên gia" @selected($skill->cap_do == 'Chuyên gia')>Chuyên gia</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-gray-500 text-center py-4" id="no-skills">
                                <i class="fa-regular fa-face-frown text-2xl block mb-2"></i>
                                Chưa có kỹ năng nào. Nhấn "Thêm kỹ năng" để tạo mới.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- ================= CERTIFICATE ================= --}}
                <div class="profile-card">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <div class="section-icon">
                                <i class="fa-solid fa-award"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Chứng chỉ</h3>
                                <p class="text-sm text-gray-500">Chứng nhận và bằng cấp chuyên môn</p>
                            </div>
                        </div>
                        <button type="button" onclick="addCertificate()" class="btn-add">
                            <i class="fa-solid fa-plus"></i> Thêm chứng chỉ
                        </button>
                    </div>

                    <div id="certificates-container">
                        @php
                            $chungChiList = $hoSoData?->chung_chi ?? [];
                        @endphp

                        @forelse($chungChiList as $cc)
                            <div class="item certificate-item border rounded-xl p-4 mb-4" data-id="{{ $cc->id }}">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-sm text-gray-500">#{{ $loop->iteration }}</span>
                                    <button type="button" onclick="removeItem(this, 'certificates')" class="btn-remove">
                                        <i class="fa-solid fa-trash"></i> Xóa
                                    </button>
                                </div>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Tên chứng chỉ
                                            *</label>
                                        <input class="input" name="certificates[{{ $cc->id }}][ten_chung_chi]"
                                            value="{{ $cc->ten_chung_chi }}"
                                            placeholder="VD: Chứng chỉ PHP, TOEIC, IELTS">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Tổ chức
                                            cấp</label>
                                        <input class="input" name="certificates[{{ $cc->id }}][to_chuc_cap]"
                                            value="{{ $cc->to_chuc_cap }}"
                                            placeholder="VD: Đại học Bách Khoa, Microsoft">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Năm cấp</label>
                                        <input type="number" class="input"
                                            name="certificates[{{ $cc->id }}][nam_cap]"
                                            value="{{ $cc->nam_cap }}" placeholder="VD: 2023" min="1900"
                                            max="{{ date('Y') }}">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Ngày hết
                                            hạn</label>
                                        <input type="date" class="input"
                                            name="certificates[{{ $cc->id }}][ngay_het_han]"
                                            value="{{ optional($cc->ngay_het_han)->format('Y-m-d') }}">
                                    </div>
                                </div>

                                {{-- ===== PHẦN UPLOAD FILE CHỨNG CHỈ ===== --}}
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                                        <div>
                                            <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">
                                                <i class="fa-solid fa-file-upload mr-1"></i> File đính kèm (PDF, JPG, PNG)
                                            </label>
                                            <input type="file"
                                                class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/20 dark:file:text-blue-300"
                                                name="certificates[{{ $cc->id }}][file_dinh_kem]"
                                                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                            <p class="text-xs text-gray-400 mt-1">Tối đa 5MB - Hỗ trợ: PDF, JPG, PNG, DOC,
                                                DOCX</p>
                                        </div>
                                        <div class="flex justify-center md:justify-end">
                                            @if ($cc->file_dinh_kem)
                                                <div
                                                    class="flex items-center gap-3 bg-gray-50 dark:bg-gray-800 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700">
                                                    @php
                                                        $extension = pathinfo($cc->file_dinh_kem, PATHINFO_EXTENSION);
                                                        $iconClass = 'fa-file text-gray-500';
                                                        $iconColor = 'text-gray-500';

                                                        if (in_array($extension, ['pdf'])) {
                                                            $iconClass = 'fa-file-pdf';
                                                            $iconColor = 'text-red-500';
                                                        } elseif (
                                                            in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])
                                                        ) {
                                                            $iconClass = 'fa-file-image';
                                                            $iconColor = 'text-blue-500';
                                                        } elseif (in_array($extension, ['doc', 'docx'])) {
                                                            $iconClass = 'fa-file-word';
                                                            $iconColor = 'text-blue-600';
                                                        }
                                                    @endphp
                                                    <i
                                                        class="fa-solid {{ $iconClass }} {{ $iconColor }} text-xl"></i>
                                                    <span
                                                        class="text-sm text-gray-600 dark:text-gray-300 truncate max-w-[150px]">
                                                        {{ basename($cc->file_dinh_kem) }}
                                                    </span>
                                                    <a href="{{ asset('storage/' . $cc->file_dinh_kem) }}"
                                                        target="_blank"
                                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                                                        <i class="fa-solid fa-eye"></i> Xem
                                                    </a>
                                                    <span class="text-xs text-gray-400">(hiện tại)</span>
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-400">Chưa có file</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-gray-500 text-center py-4" id="no-certificates">
                                <i class="fa-regular fa-face-frown text-2xl block mb-2"></i>
                                Chưa có chứng chỉ nào. Nhấn "Thêm chứng chỉ" để tạo mới.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- ================= TRAINING ================= --}}
                <div class="profile-card">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <div class="section-icon">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Đào tạo</h3>
                                <p class="text-sm text-gray-500">Khóa học đã tham gia</p>
                            </div>
                        </div>
                        <button type="button" onclick="addTraining()" class="btn-add">
                            <i class="fa-solid fa-plus"></i> Thêm khóa học
                        </button>
                    </div>

                    <div id="trainings-container">
                        @php
                            $daoTaoList = $hoSoData?->dao_tao ?? [];
                        @endphp

                        @forelse($daoTaoList as $dt)
                            <div class="item training-item border rounded-xl p-4 mb-4" data-id="{{ $dt->id }}">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-sm text-gray-500">#{{ $loop->iteration }}</span>
                                    <button type="button" onclick="removeItem(this, 'trainings')" class="btn-remove">
                                        <i class="fa-solid fa-trash"></i> Xóa
                                    </button>
                                </div>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Tên khóa học
                                            *</label>
                                        <input class="input" name="trainings[{{ $dt->id }}][ten_khoa_hoc]"
                                            value="{{ $dt->ten_khoa_hoc }}" placeholder="VD: Khóa học Laravel Nâng cao">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Tổ chức đào
                                            tạo</label>
                                        <input class="input" name="trainings[{{ $dt->id }}][to_chuc]"
                                            value="{{ $dt->to_chuc }}" placeholder="VD: CodeGym, FPT Software">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Kết quả</label>
                                        <input class="input" name="trainings[{{ $dt->id }}][ket_qua]"
                                            value="{{ $dt->ket_qua }}" placeholder="VD: Xuất sắc, Giỏi, Đạt">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Có chứng
                                            chỉ?</label>
                                        <select class="input" name="trainings[{{ $dt->id }}][co_chung_chi]">
                                            <option value="0" @selected($dt->co_chung_chi == false || $dt->co_chung_chi == null)>Không</option>
                                            <option value="1" @selected($dt->co_chung_chi == true)>Có</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Chi phí</label>
                                        <input type="number" class="input"
                                            name="trainings[{{ $dt->id }}][chi_phi]" value="{{ $dt->chi_phi }}"
                                            placeholder="VD: 5000000" min="0" step="1000">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Ngày bắt
                                            đầu</label>
                                        <input type="date" class="input"
                                            name="trainings[{{ $dt->id }}][ngay_bat_dau]"
                                            value="{{ optional($dt->ngay_bat_dau)->format('Y-m-d') }}">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Ngày kết
                                            thúc</label>
                                        <input type="date" class="input"
                                            name="trainings[{{ $dt->id }}][ngay_ket_thuc]"
                                            value="{{ optional($dt->ngay_ket_thuc)->format('Y-m-d') }}">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Ghi chú</label>
                                        <input class="input" name="trainings[{{ $dt->id }}][ghi_chu]"
                                            value="{{ $dt->ghi_chu }}" placeholder="Nhập ghi chú (nếu có)">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-gray-500 text-center py-4" id="no-trainings">
                                <i class="fa-regular fa-face-frown text-2xl block mb-2"></i>
                                Chưa có khóa học đào tạo nào. Nhấn "Thêm khóa học" để tạo mới.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- ================= DEPENDENT ================= --}}
                <div class="profile-card">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <div class="section-icon">
                                <i class="fa-solid fa-people-roof"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Người phụ thuộc</h3>
                                <p class="text-sm text-gray-500">Thông tin giảm trừ gia cảnh</p>
                            </div>
                        </div>
                        <button type="button" onclick="addDependent()" class="btn-add">
                            <i class="fa-solid fa-plus"></i> Thêm người phụ thuộc
                        </button>
                    </div>

                    <div id="dependents-container">
                        @php
                            $nguoiPhuThuocList = $hoSoData?->nguoiPhuThuoc ?? [];
                        @endphp

                        @forelse($nguoiPhuThuocList as $npt)
                            <div class="item dependent-item border rounded-xl p-3 mb-3" data-id="{{ $npt->id }}">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-500">#{{ $loop->iteration }}</span>
                                    <button type="button" onclick="removeItem(this, 'dependents')" class="btn-remove">
                                        <i class="fa-solid fa-trash"></i> Xóa
                                    </button>
                                </div>
                                <div class="grid md:grid-cols-3 gap-3">
                                    <input class="input" name="dependents[{{ $npt->id }}][ho_ten]"
                                        value="{{ $npt->ho_ten }}" placeholder="Họ tên *">
                                    <input type="date" class="input"
                                        name="dependents[{{ $npt->id }}][ngay_sinh]"
                                        value="{{ optional($npt->ngay_sinh)->format('Y-m-d') }}" placeholder="Ngày sinh">
                                    <select class="input" name="dependents[{{ $npt->id }}][quan_he]">
                                        <option value="con" @selected($npt->quan_he == 'con')>Con</option>
                                        <option value="vo" @selected($npt->quan_he == 'vo')>Vợ</option>
                                        <option value="chong" @selected($npt->quan_he == 'chong')>Chồng</option>
                                        <option value="cha" @selected($npt->quan_he == 'cha')>Cha</option>
                                        <option value="me" @selected($npt->quan_he == 'me')>Mẹ</option>
                                        <option value="khac" @selected($npt->quan_he == 'khac')>Khác</option>
                                    </select>
                                    <input class="input" name="dependents[{{ $npt->id }}][ma_so_thue]"
                                        value="{{ $npt->ma_so_thue }}" placeholder="Mã số thuế">
                                    <input type="date" class="input"
                                        name="dependents[{{ $npt->id }}][ngay_bat_dau]"
                                        value="{{ optional($npt->ngay_bat_dau)->format('Y-m-d') }}"
                                        placeholder="Ngày bắt đầu">
                                    <input type="date" class="input"
                                        name="dependents[{{ $npt->id }}][ngay_ket_thuc]"
                                        value="{{ optional($npt->ngay_ket_thuc)->format('Y-m-d') }}"
                                        placeholder="Ngày kết thúc">
                                    <input class="input" name="dependents[{{ $npt->id }}][ghi_chu]"
                                        value="{{ $npt->ghi_chu }}" placeholder="Ghi chú">
                                </div>
                            </div>
                        @empty
                            <div class="text-gray-500 text-center py-4" id="no-dependents">
                                Chưa có dữ liệu người phụ thuộc. Nhấn "Thêm người phụ thuộc" để tạo mới.
                            </div>
                        @endforelse
                    </div>
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

                                <p class="text-sm text-gray-500">
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

                            <p class="text-sm text-gray-500">
                                Lịch sử đánh giá nhân sự
                            </p>
                        </div>

                    </div>

                    @forelse($user->hoSo?->hoSo?->khen_thuong_ky_luat ?? [] as $item)
                        <div class="{{ $item->mau_loai }} rounded-lg p-4 mb-4">

                            <div class="flex justify-between items-start">

                                <div>
                                    <div class="font-semibold text-lg">
                                        {{ $item->ten }}
                                    </div>

                                    <div class="text-sm mt-1">
                                        {{ $item->loai_text }}
                                    </div>
                                </div>

                                <div class="text-sm">
                                    {{ optional($item->ngay)->format('d/m/Y') }}
                                </div>

                            </div>

                            @if ($item->noi_dung)
                                <div class="mt-3">
                                    {{ $item->noi_dung }}
                                </div>
                            @endif

                            <div class="grid md:grid-cols-3 gap-3 mt-4 text-sm">

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

                {{-- ================= HIDDEN INPUTS ================= --}}
                <input type="hidden" name="delete_skills" id="delete_skills" value="">
                <input type="hidden" name="delete_certificates" id="delete_certificates" value="">
                <input type="hidden" name="delete_trainings" id="delete_trainings" value="">
                <input type="hidden" name="delete_dependents" id="delete_dependents" value="">

                <div class="sticky bottom-5 z-20 flex justify-end gap-3 mt-10">

                    <button type="reset" class="px-4 py-2 rounded-xl border bg-white dark:bg-gray-700">
                        Đặt lại
                    </button>

                    <button type="submit" class="px-6 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                        Lưu hồ sơ
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
            }

            .dark .profile-card {
                background: #1f2937;
                border-color: #374151;
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
                width: 38px;
                height: 38px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 15px;
                background: #eff6ff;
                color: #2563eb;
            }

            .dark .section-icon {
                background: rgba(37, 99, 235, .15);
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

            /* ===== BUTTONS ===== */
            .btn-add {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 6px 14px;
                font-size: 13px;
                font-weight: 500;
                border-radius: 10px;
                background: #10b981;
                color: #fff;
                border: none;
                cursor: pointer;
                transition: all 0.2s ease;
                white-space: nowrap;
                height: 36px;
                min-width: 120px;
                justify-content: center;
            }

            .btn-add:hover {
                background: #059669;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            }

            .btn-add:active {
                transform: translateY(0);
            }

            .btn-remove {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                padding: 4px 12px;
                font-size: 12px;
                font-weight: 500;
                border-radius: 8px;
                background: #fee2e2;
                color: #dc2626;
                border: none;
                cursor: pointer;
                transition: all 0.2s ease;
                height: 30px;
            }

            .btn-remove:hover {
                background: #fecaca;
                color: #b91c1c;
            }

            .dark .btn-remove {
                background: rgba(220, 38, 38, 0.15);
                color: #f87171;
            }

            .dark .btn-remove:hover {
                background: rgba(220, 38, 38, 0.25);
                color: #fca5a5;
            }

            .dark .btn-add {
                background: #059669;
            }

            .dark .btn-add:hover {
                background: #047857;
            }

            .deleted {
                display: none !important;
            }
        </style>

        {{-- ================= JAVASCRIPT ================= --}}
        <script>
            // ==========================================
            // DANH SÁCH ID CẦN XÓA
            // ==========================================
            let deleteItems = {
                skills: [],
                certificates: [],
                trainings: [],
                dependents: []
            };

            // ==========================================
            // HÀM XÓA
            // ==========================================
            function removeItem(btn, type) {
                console.log('🔍 removeItem called', {
                    btn,
                    type
                });

                // Tìm item cha - Tìm phần tử có class 'item'
                const item = btn.closest('.item');

                if (!item) {
                    console.error('❌ Cannot find parent item');
                    alert('Không tìm thấy item để xóa!');
                    return;
                }

                const id = item.dataset.id;
                console.log('📌 Found item with ID:', id);

                if (!id) {
                    console.error('❌ No ID found');
                    alert('Không tìm thấy ID!');
                    return;
                }

                // Lấy container (parent element)
                const container = item.parentElement;

                // Kiểm tra container có tồn tại không
                if (!container) {
                    console.error('❌ Container not found');
                    return;
                }

                // Nếu là item mới
                if (String(id).startsWith('new_')) {
                    console.log('🗑️ Removing new item');
                    item.remove();
                    checkEmptyContainer(container, type);
                    return;
                }

                // Thêm vào danh sách xóa
                if (!deleteItems[type].includes(id)) {
                    deleteItems[type].push(id);
                    console.log(`✅ Added ID ${id} to delete list for ${type}`);
                }

                // Cập nhật hidden input
                updateDeleteInput(type);

                // Xóa khỏi DOM
                item.remove();
                checkEmptyContainer(container, type);
            }

            // ==========================================
            // CẬP NHẬT HIDDEN INPUT
            // ==========================================
            function updateDeleteInput(type) {
                const hiddenInput = document.getElementById(`delete_${type}`);
                if (hiddenInput) {
                    const value = deleteItems[type].join(',');
                    hiddenInput.value = value;
                    console.log(`📝 Updated delete_${type}:`, value);
                }
            }

            // ==========================================
            // KIỂM TRA CONTAINER RỖNG
            // ==========================================
            function checkEmptyContainer(container, type) {
                const items = container.querySelectorAll('.item');
                if (items.length === 0) {
                    const messages = {
                        'skills-container': 'Chưa có dữ liệu. Nhấn "Thêm kỹ năng" để tạo mới.',
                        'certificates-container': 'Chưa có dữ liệu. Nhấn "Thêm chứng chỉ" để tạo mới.',
                        'trainings-container': 'Chưa có dữ liệu đào tạo. Nhấn "Thêm khóa học" để tạo mới.',
                        'dependents-container': 'Chưa có dữ liệu người phụ thuộc. Nhấn "Thêm người phụ thuộc" để tạo mới.'
                    };

                    const id = container.id;
                    const msg = messages[id] || 'Chưa có dữ liệu';

                    if (!container.querySelector('[id^="no-"]')) {
                        container.innerHTML = `
                    <div class="text-gray-500 text-center py-4" id="no-${id.split('-')[0]}">
                        ${msg}
                    </div>
                `;
                    }
                }
            }

            // ==========================================
            // THÊM KỸ NĂNG
            // ==========================================
            function addSkill() {
                console.log('➕ Adding skill');
                const container = document.getElementById('skills-container');
                const emptyMsg = container.querySelector('[id^="no-"]');
                if (emptyMsg) emptyMsg.remove();

                const newId = 'new_' + Date.now();
                const html = `
        <div class="item skill-item border rounded-xl p-4 mb-4" data-id="${newId}">
            <div class="flex justify-between items-center mb-3">
                <span class="text-sm text-gray-500">Mới</span>
                <button type="button" onclick="removeItem(this, 'skills')" class="btn-remove">
                    <i class="fa-solid fa-trash"></i> Xóa
                </button>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Tên kỹ năng *</label>
                    <input class="input" name="new_skills[${newId}][ten_ky_nang]" placeholder="VD: PHP, Laravel, JavaScript">
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Cấp độ</label>
                    <select class="input" name="new_skills[${newId}][cap_do]">
                        <option value="Cơ bản">Cơ bản</option>
                        <option value="Trung cấp">Trung cấp</option>
                        <option value="Thành thạo">Thành thạo</option>
                        <option value="Chuyên gia">Chuyên gia</option>
                    </select>
                </div>
            </div>
        </div>
    `;
                container.insertAdjacentHTML('beforeend', html);
            }

            // ==========================================
            // THÊM CHỨNG CHỈ (có upload file)
            // ==========================================
            function addCertificate() {
                console.log('➕ Adding certificate');
                const container = document.getElementById('certificates-container');
                const emptyMsg = container.querySelector('[id^="no-"]');
                if (emptyMsg) emptyMsg.remove();

                const newId = 'new_' + Date.now();
                const html = `
        <div class="item certificate-item border rounded-xl p-4 mb-4" data-id="${newId}">
            <div class="flex justify-between items-center mb-3">
                <span class="text-sm text-gray-500">Mới</span>
                <button type="button" onclick="removeItem(this, 'certificates')" class="btn-remove">
                    <i class="fa-solid fa-trash"></i> Xóa
                </button>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Tên chứng chỉ *</label>
                    <input class="input" name="new_certificates[${newId}][ten_chung_chi]" placeholder="VD: Chứng chỉ PHP, TOEIC">
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Tổ chức cấp</label>
                    <input class="input" name="new_certificates[${newId}][to_chuc_cap]" placeholder="VD: Đại học Bách Khoa">
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Năm cấp</label>
                    <input type="number" class="input" name="new_certificates[${newId}][nam_cap]" placeholder="VD: 2023" min="1900" max="${new Date().getFullYear()}">
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Ngày hết hạn</label>
                    <input type="date" class="input" name="new_certificates[${newId}][ngay_het_han]">
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                    <div>
                        <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">
                            <i class="fa-solid fa-file-upload mr-1"></i> File đính kèm (PDF, JPG, PNG)
                        </label>
                        <input type="file" 
                               class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/20 dark:file:text-blue-300"
                               name="new_certificates[${newId}][file_dinh_kem]"
                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        <p class="text-xs text-gray-400 mt-1">Tối đa 5MB - Hỗ trợ: PDF, JPG, PNG, DOC, DOCX</p>
                    </div>
                    <div class="flex justify-center md:justify-end">
                        <span class="text-sm text-gray-400">Chưa có file</span>
                    </div>
                </div>
            </div>
        </div>
    `;
                container.insertAdjacentHTML('beforeend', html);
            }

            // ==========================================
            // THÊM ĐÀO TẠO
            // ==========================================
            function addTraining() {
                console.log('➕ Adding training');
                const container = document.getElementById('trainings-container');
                const emptyMsg = container.querySelector('[id^="no-"]');
                if (emptyMsg) emptyMsg.remove();

                const newId = 'new_' + Date.now();
                const html = `
        <div class="item training-item border rounded-xl p-4 mb-4" data-id="${newId}">
            <div class="flex justify-between items-center mb-3">
                <span class="text-sm text-gray-500">Mới</span>
                <button type="button" onclick="removeItem(this, 'trainings')" class="btn-remove">
                    <i class="fa-solid fa-trash"></i> Xóa
                </button>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Tên khóa học *</label>
                    <input class="input" name="new_trainings[${newId}][ten_khoa_hoc]" placeholder="VD: Khóa học Laravel Nâng cao">
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Tổ chức đào tạo</label>
                    <input class="input" name="new_trainings[${newId}][to_chuc]" placeholder="VD: CodeGym, FPT Software">
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Kết quả</label>
                    <input class="input" name="new_trainings[${newId}][ket_qua]" placeholder="VD: Xuất sắc, Giỏi, Đạt">
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Có chứng chỉ?</label>
                    <select class="input" name="new_trainings[${newId}][co_chung_chi]">
                        <option value="0">Không</option>
                        <option value="1">Có</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Chi phí</label>
                    <input type="number" class="input" name="new_trainings[${newId}][chi_phi]" placeholder="VD: 5000000" min="0" step="1000">
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Ngày bắt đầu</label>
                    <input type="date" class="input" name="new_trainings[${newId}][ngay_bat_dau]">
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Ngày kết thúc</label>
                    <input type="date" class="input" name="new_trainings[${newId}][ngay_ket_thuc]">
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Ghi chú</label>
                    <input class="input" name="new_trainings[${newId}][ghi_chu]" placeholder="Nhập ghi chú (nếu có)">
                </div>
            </div>
        </div>
    `;
                container.insertAdjacentHTML('beforeend', html);
            }

            // ==========================================
            // THÊM NGƯỜI PHỤ THUỘC
            // ==========================================
            function addDependent() {
                console.log('➕ Adding dependent');
                const container = document.getElementById('dependents-container');
                const emptyMsg = container.querySelector('[id^="no-"]');
                if (emptyMsg) emptyMsg.remove();

                const newId = 'new_' + Date.now();
                const html = `
        <div class="item dependent-item border rounded-xl p-3 mb-3" data-id="${newId}">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm text-gray-500">Mới</span>
                <button type="button" onclick="removeItem(this, 'dependents')" class="btn-remove">
                    <i class="fa-solid fa-trash"></i> Xóa
                </button>
            </div>
            <div class="grid md:grid-cols-3 gap-3">
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Họ tên *</label>
                    <input class="input" name="new_dependents[${newId}][ho_ten]" placeholder="Nhập họ tên">
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Ngày sinh</label>
                    <input type="date" class="input" name="new_dependents[${newId}][ngay_sinh]">
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Quan hệ</label>
                    <select class="input" name="new_dependents[${newId}][quan_he]">
                        <option value="con">Con</option>
                        <option value="vo">Vợ</option>
                        <option value="chong">Chồng</option>
                        <option value="cha">Cha</option>
                        <option value="me">Mẹ</option>
                        <option value="khac">Khác</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Mã số thuế</label>
                    <input class="input" name="new_dependents[${newId}][ma_so_thue]" placeholder="Nhập mã số thuế">
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Ngày bắt đầu</label>
                    <input type="date" class="input" name="new_dependents[${newId}][ngay_bat_dau]">
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Ngày kết thúc</label>
                    <input type="date" class="input" name="new_dependents[${newId}][ngay_ket_thuc]">
                </div>
                <div class="md:col-span-3">
                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Ghi chú</label>
                    <input class="input" name="new_dependents[${newId}][ghi_chu]" placeholder="Nhập ghi chú (nếu có)">
                </div>
            </div>
        </div>
    `;
                container.insertAdjacentHTML('beforeend', html);
            }

            // ==========================================
            // HIỂN THỊ TÊN FILE CV
            // ==========================================
            function showCvFile(input) {
                const fileName = document.getElementById('cvFileName');
                if (input.files && input.files[0]) {
                    fileName.textContent = '📎 Đã chọn: ' + input.files[0].name;
                    fileName.classList.remove('hidden');
                } else {
                    fileName.classList.add('hidden');
                }
            }

            // ==========================================
            // KIỂM TRA FORM TRƯỚC KHI SUBMIT
            // ==========================================
            document.addEventListener('DOMContentLoaded', function() {
                console.log('✅ Script loaded successfully');
                console.log('📌 Functions:', {
                    removeItem: typeof removeItem,
                    addSkill: typeof addSkill,
                    addCertificate: typeof addCertificate,
                    addTraining: typeof addTraining,
                    addDependent: typeof addDependent
                });

                const form = document.getElementById('profileForm');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        // Cập nhật lại tất cả hidden inputs trước khi submit
                        updateDeleteInput('skills');
                        updateDeleteInput('certificates');
                        updateDeleteInput('trainings');
                        updateDeleteInput('dependents');

                        console.log('=== FORM SUBMITTED ===');
                        console.log('delete_skills:', document.getElementById('delete_skills')?.value);
                        console.log('delete_certificates:', document.getElementById('delete_certificates')
                            ?.value);
                        console.log('delete_trainings:', document.getElementById('delete_trainings')?.value);
                        console.log('delete_dependents:', document.getElementById('delete_dependents')?.value);
                    });
                }
            });
        </script>

    @endsection
