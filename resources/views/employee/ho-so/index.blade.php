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
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-5 flex justify-between items-center">

            <div class="flex items-center gap-4">
                @if ($user->hoSo?->anh_dai_dien)
                    <img src="{{ asset('storage/' . $user->hoSo->anh_dai_dien) }}"
                        class="w-14 h-14 rounded-full object-cover border">
                @else
                    <div
                        class="w-14 h-14 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center font-bold">
                        {{ strtoupper(substr($user->ho_ten, 0, 1)) }}
                    </div>
                @endif

                <div>
                    <div class="font-semibold text-lg">{{ $user->ho_ten }}</div>
                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                </div>
            </div>

            <div class="text-right text-sm">
                <div class="font-medium">{{ $user->vai_tro?->ten_hien_thi }}</div>
                <div class="text-gray-500">{{ $user->phong_ban?->ten_phong_ban }}</div>
            </div>
        </div>

        {{-- ================= ACCOUNT INFO ================= --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-5">
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
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-5">
            <div class="font-semibold mb-4">Đổi mật khẩu</div>

            <form action="{{ route('employee.ho-so.change-password') }}" method="POST">
                @csrf

                <div class="grid md:grid-cols-3 gap-4">
                    <input type="password" name="current_password" class="input" placeholder="Mật khẩu hiện tại">
                    <input type="password" name="new_password" class="input" placeholder="Mật khẩu mới">
                    <input type="password" name="new_password_confirmation" class="input" placeholder="Xác nhận">
                </div>

                <div class="text-right mt-4">
                    <button class="btn-yellow">Đổi mật khẩu</button>
                </div>
            </form>
        </div>

        {{-- ================= PERSONAL INFO ================= --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-5">
            <div class="font-semibold mb-4">Thông Tin Cá Nhân</div>

            <form action="{{ route('employee.ho-so.update') }}" method="POST" enctype="multipart/form-data">
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

                {{-- ================= ADDRESS & ID (FIXED ADMIN STYLE) ================= --}}
                <div class="mt-6">

                    <div class="font-semibold mb-3">Địa chỉ & Giấy tờ</div>

                    <div class="grid md:grid-cols-2 gap-4">

                        {{-- Địa chỉ hiện tại --}}
                        <input name="dia_chi_hien_tai" class="input"
                            value="{{ old('dia_chi_hien_tai', $user->hoSo?->dia_chi_hien_tai) }}"
                            placeholder="Chưa cập nhật địa chỉ hiện tại">

                        {{-- Địa chỉ thường trú --}}
                        <input name="dia_chi_thuong_tru" class="input"
                            value="{{ old('dia_chi_thuong_tru', $user->hoSo?->dia_chi_thuong_tru) }}"
                            placeholder="Chưa cập nhật địa chỉ thường trú">

                        {{-- CCCD --}}
                        <input name="cmnd_cccd" class="input" value="{{ old('cmnd_cccd', $user->hoSo?->cmnd_cccd) }}"
                            placeholder="Chưa cập nhật CCCD / CMND">

                        {{-- Hộ chiếu --}}
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

                            {{-- INPUT --}}
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

                            {{-- PREVIEW CARD --}}
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

                <div class="text-right mt-6 flex justify-end gap-3">

                    <button type="reset"
                        class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Đặt lại
                    </button>

                    <button class="btn-blue">
                        Lưu thay đổi
                    </button>

                </div>

            </form>


            {{-- ================= CV ================= --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-5 mt-6">
                <div class="font-semibold mb-4">
                    📄 CV Hồ sơ năng lực
                </div>

                @if ($user->hoSo?->hoSo?->cv)
                    <div class="flex justify-between items-center">

                        <div>
                            {{ basename($user->hoSo->hoSo->cv->duong_dan_file ?? ($user->hoSo->hoSo->cv->tep_tin ?? 'CV')) }}
                        </div>

                        <a href="{{ asset('storage/' . ($user->hoSo->hoSo->cv->duong_dan_file ?? $user->hoSo->hoSo->cv->tep_tin)) }}"
                            target="_blank" class="btn-blue">
                            Xem CV
                        </a>

                    </div>
                @else
                    <div class="text-gray-500">
                        Chưa có CV được tải lên.
                    </div>
                @endif
            </div>

            {{-- ================= SKILLS ================= --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-5">

                <div class="font-semibold mb-4">
                    🛠️ Kỹ năng chuyên môn
                </div>

                @forelse($user->hoSo?->hoSo?->ky_nang ?? [] as $skill)
                    <div class="flex justify-between items-center py-3 border-b">

                        <div class="font-medium">
                            {{ $skill->ten_ky_nang }}
                        </div>

                        <span class="text-sm">
                            {{ $skill->icon_cap_do }}
                            {{ $skill->cap_do }}
                        </span>

                    </div>

                @empty

                    <div class="text-gray-500">
                        Chưa có dữ liệu.
                    </div>
                @endforelse

            </div>

            {{-- ================= CERTIFICATE ================= --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-5">

                <div class="font-semibold mb-4">
                    🏅 Chứng chỉ
                </div>

                @forelse($user->hoSo?->hoSo?->chung_chi ?? [] as $cc)
                    <div class="border-b py-4">

                        <div class="font-medium">
                            {{ $cc->ten_chung_chi }}
                        </div>

                        <div class="text-sm text-gray-500">
                            {{ $cc->to_chuc_cap }}
                        </div>

                        <div class="text-sm">
                            Năm cấp: {{ $cc->nam_cap }}
                        </div>

                        <div class="text-sm">
                            {{ $cc->trang_thai_hien_thi }}
                        </div>

                    </div>

                @empty

                    <div class="text-gray-500">
                        Chưa có chứng chỉ.
                    </div>
                @endforelse

            </div>

            {{-- ================= TRAINING ================= --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-5">

                <div class="font-semibold mb-4">
                    🎓 Đào tạo
                </div>

                @forelse($user->hoSo?->hoSo?->dao_tao ?? [] as $dt)
                    <div class="border-b py-4">

                        <div class="font-medium">
                            {{ $dt->ten_khoa_hoc }}
                        </div>

                        <div class="text-sm text-gray-500">
                            {{ $dt->to_chuc }}
                        </div>

                        <div class="text-sm">
                            {{ optional($dt->ngay_bat_dau)->format('d/m/Y') }}
                            -
                            {{ optional($dt->ngay_ket_thuc)->format('d/m/Y') }}
                        </div>

                        <div class="text-sm">
                            {{ $dt->ket_qua ?? '---' }}
                        </div>

                    </div>

                @empty

                    <div class="text-gray-500">
                        Chưa có dữ liệu đào tạo.
                    </div>
                @endforelse

            </div>

            {{-- ================= DEPENDENT ================= --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-5">

                <div class="font-semibold mb-4">
                    👨‍👩‍👧 Người phụ thuộc
                </div>

                @forelse($user->hoSo?->hoSo?->nguoiPhuThuoc ?? [] as $npt)
                    <div class="border-b py-4">

                        <div class="font-medium">
                            {{ $npt->ho_ten }}
                        </div>

                        <div class="text-sm">
                            {{ $npt->quan_he_text }}
                        </div>

                        <div class="text-sm">
                            MST: {{ $npt->ma_so_thue ?? '---' }}
                        </div>

                    </div>

                @empty

                    <div class="text-gray-500">
                        Chưa có người phụ thuộc.
                    </div>
                @endforelse

            </div>

            {{-- ================= CONTRACT ================= --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-5">

                <div class="font-semibold mb-4">
                    📄 Hợp đồng lao động
                </div>

                @php
                    $hopDong = $user->hoSo?->hoSo?->hop_dong?->where('trang_thai_hop_dong', 'hieu_luc')->first();
                @endphp

                @if ($hopDong)
                    <div class="grid md:grid-cols-2 gap-4">

                        <div>
                            <strong>Số hợp đồng:</strong>
                            {{ $hopDong->so_hop_dong }}
                        </div>

                        <div>
                            <strong>Loại:</strong>
                            {{ $hopDong->ten_loai_hop_dong }}
                        </div>

                        <div>
                            <strong>Ngày bắt đầu:</strong>
                            {{ $hopDong->ngay_bat_dau_format }}
                        </div>

                        <div>
                            <strong>Ngày kết thúc:</strong>
                            {{ $hopDong->ngay_ket_thuc_format }}
                        </div>

                        <div>
                            <strong>Trạng thái:</strong>
                            {{ $hopDong->ten_trang_thai }}
                        </div>

                    </div>
                @else
                    <div class="text-gray-500">
                        Chưa có hợp đồng lao động.
                    </div>
                @endif

            </div>
        </div>

        {{-- ================= REWARD DISCIPLINE ================= --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-5">

            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-lg">
                    🏆 Khen thưởng & Kỷ luật
                </h3>

                <span class="text-sm text-gray-500">
                    Lịch sử đánh giá nhân sự
                </span>
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

    </div>


    {{-- ================= STYLE ================= --}}
    <style>
        .input {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
        }

        .dark .input {
            background: #1f2937;
            border-color: #374151;
            color: white;
        }

        .btn-blue {
            background: #2563eb;
            color: white;
            padding: 10px 16px;
            border-radius: 10px;
        }

        .btn-yellow {
            background: #f59e0b;
            color: white;
            padding: 10px 16px;
            border-radius: 10px;
        }
    </style>

@endsection
