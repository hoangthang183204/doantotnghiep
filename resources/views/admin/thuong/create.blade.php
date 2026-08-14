@extends('layouts.admin')

@section('title', 'Thêm khoản thưởng')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-slate-900 py-8 px-6">
    <div class="max-w-5xl mx-auto space-y-8">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Thêm khoản thưởng</h1>
                <p class="mt-2 text-gray-500 dark:text-slate-400">
                    Chọn thưởng <b>định kỳ hàng tháng</b> hoặc <b>áp dụng 1 lần</b>, cho một hay nhiều nhân viên.
                </p>
            </div>
            <a href="{{ route('admin.thuong.index', ['thang' => $thang, 'nam' => $nam]) }}"
               class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-100 dark:hover:bg-slate-700 transition shadow-sm">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </a>
        </div>

        @include('layouts.partials.alerts')

        @if($loaiThuongs->isEmpty())
            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-2xl p-6">
                <p class="text-amber-800 dark:text-amber-300">
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                    Chưa có loại thưởng nào đang sử dụng. Hãy
                    <a href="{{ route('admin.loai-thuong.create') }}" class="underline font-semibold">tạo loại thưởng</a>
                    trước khi gán thưởng cho nhân viên.
                </p>
            </div>
        @else
        <form action="{{ route('admin.thuong.store') }}" method="POST"
              class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-gray-200 dark:border-slate-700 p-8 space-y-8">
            @csrf

            {{-- ===== 1. HÌNH THỨC THƯỞNG ===== --}}
            <div>
                <p class="text-sm font-semibold text-gray-700 dark:text-slate-300 mb-3">
                    ① Hình thức thưởng <span class="text-red-500">*</span>
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition
                                  border-gray-200 dark:border-slate-700 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20">
                        <input type="radio" name="hinh_thuc" value="dinh_ky" class="js-hinh-thuc mt-1 text-blue-600 focus:ring-blue-500"
                               @checked(old('hinh_thuc') === 'dinh_ky')>
                        <span>
                            <span class="block font-semibold text-gray-900 dark:text-white">
                                <i class="fa-solid fa-repeat text-indigo-500 mr-1"></i> Thưởng định kỳ hàng tháng
                            </span>
                            <span class="block text-xs text-gray-500 dark:text-slate-400 mt-1">
                                Tự động cộng vào lương mỗi tháng trong khoảng hiệu lực.
                                VD: thưởng chuyên cần, thưởng trách nhiệm.
                            </span>
                        </span>
                    </label>

                    <label class="flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition
                                  border-gray-200 dark:border-slate-700 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20">
                        <input type="radio" name="hinh_thuc" value="mot_lan" class="js-hinh-thuc mt-1 text-blue-600 focus:ring-blue-500"
                               @checked(old('hinh_thuc', 'mot_lan') === 'mot_lan')>
                        <span>
                            <span class="block font-semibold text-gray-900 dark:text-white">
                                <i class="fa-solid fa-star text-amber-500 mr-1"></i> Thưởng áp dụng 1 lần
                            </span>
                            <span class="block text-xs text-gray-500 dark:text-slate-400 mt-1">
                                Chỉ cộng vào đúng 1 kỳ lương. VD: thưởng Tết, thưởng dự án, thưởng lễ 2/9.
                            </span>
                        </span>
                    </label>
                </div>
                @error('hinh_thuc') <p class="text-sm text-red-500 mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- Kỳ áp dụng: 1 lần --}}
            <div class="js-block-mot-lan grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">
                        Tháng áp dụng <span class="text-red-500">*</span>
                    </label>
                    <select name="thang"
                            class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" @selected(old('thang', $thang) == $m)>Tháng {{ $m }}</option>
                        @endfor
                    </select>
                    @error('thang') <p class="text-sm text-red-500 mt-2">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">
                        Năm <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="nam" value="{{ old('nam', $nam) }}" min="2000" max="2100"
                           class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('nam') <p class="text-sm text-red-500 mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Kỳ áp dụng: định kỳ --}}
            <div class="js-block-dinh-ky grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">
                        Bắt đầu áp dụng từ <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="ngay_bat_dau" value="{{ old('ngay_bat_dau', now()->startOfMonth()->toDateString()) }}"
                           class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('ngay_bat_dau') <p class="text-sm text-red-500 mt-2">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">
                        Kết thúc (để trống = không thời hạn)
                    </label>
                    <input type="date" name="ngay_ket_thuc" value="{{ old('ngay_ket_thuc') }}"
                           class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('ngay_ket_thuc') <p class="text-sm text-red-500 mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- ===== 2. LOẠI THƯỞNG & GIÁ TRỊ ===== --}}
            <div class="pt-2 border-t border-gray-200 dark:border-slate-700">
                <p class="text-sm font-semibold text-gray-700 dark:text-slate-300 mb-3 mt-6">
                    ② Loại thưởng & giá trị <span class="text-red-500">*</span>
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">Loại thưởng</label>
                        <select name="loai_thuong_id" id="loai_thuong_id" required
                                class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Chọn loại thưởng --</option>
                            @foreach($loaiThuongs as $lt)
                                <option value="{{ $lt->id }}"
                                        data-cach-tinh="{{ $lt->cach_tinh }}"
                                        data-gia-tri="{{ (float) $lt->gia_tri_mac_dinh }}"
                                        data-hinh-thuc="{{ $lt->hinh_thuc_mac_dinh }}"
                                        @selected(old('loai_thuong_id') == $lt->id)>
                                    {{ $lt->ten }} ({{ $lt->gia_tri_text }})
                                </option>
                            @endforeach
                        </select>
                        @error('loai_thuong_id') <p class="text-sm text-red-500 mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">Cách tính</label>
                        <select name="cach_tinh" id="cach_tinh"
                                class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            @foreach(\App\Models\LoaiThuong::$cachTinhLabels as $key => $label)
                                <option value="{{ $key }}" @selected(old('cach_tinh') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">
                            Giá trị <span class="js-don-vi text-gray-400 font-normal">(VNĐ)</span>
                        </label>
                        <input type="number" name="gia_tri" id="gia_tri" value="{{ old('gia_tri') }}" min="0" step="1000" required
                               class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        @error('gia_tri') <p class="text-sm text-red-500 mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">Thuế TNCN</label>
                        <select name="chiu_thue"
                                class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            <option value="mac_dinh" @selected(old('chiu_thue', 'mac_dinh') === 'mac_dinh')>Theo cấu hình của loại thưởng</option>
                            <option value="co" @selected(old('chiu_thue') === 'co')>Tính vào thu nhập chịu thuế</option>
                            <option value="khong" @selected(old('chiu_thue') === 'khong')>Không chịu thuế</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">Trạng thái</label>
                        <select name="trang_thai"
                                class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            @foreach(\App\Models\ThuongNhanVien::$trangThaiLabels as $key => $label)
                                <option value="{{ $key }}" @selected(old('trang_thai', 'hieu_luc') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">Lý do / ghi chú</label>
                    <input type="text" name="ly_do" value="{{ old('ly_do') }}" maxlength="255"
                           placeholder="VD: Thưởng Tết Nguyên đán 2027, thưởng hoàn thành dự án ABC..."
                           class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            {{-- ===== 3. ÁP DỤNG CHO AI ===== --}}
            <div class="pt-2 border-t border-gray-200 dark:border-slate-700">
                <p class="text-sm font-semibold text-gray-700 dark:text-slate-300 mb-3 mt-6">
                    ③ Áp dụng cho <span class="text-red-500">*</span>
                </p>

                <div class="flex flex-wrap gap-4 mb-5">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="pham_vi" value="nhan_vien" class="js-pham-vi text-blue-600 focus:ring-blue-500"
                               @checked(old('pham_vi', 'nhan_vien') === 'nhan_vien')>
                        <span class="text-sm text-gray-700 dark:text-slate-300">Chọn nhân viên</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="pham_vi" value="phong_ban" class="js-pham-vi text-blue-600 focus:ring-blue-500"
                               @checked(old('pham_vi') === 'phong_ban')>
                        <span class="text-sm text-gray-700 dark:text-slate-300">Cả phòng ban</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="pham_vi" value="toan_cong_ty" class="js-pham-vi text-blue-600 focus:ring-blue-500"
                               @checked(old('pham_vi') === 'toan_cong_ty')>
                        <span class="text-sm text-gray-700 dark:text-slate-300">Toàn công ty ({{ $nhanViens->count() }} nhân viên)</span>
                    </label>
                </div>

                {{-- Chọn nhân viên --}}
                <div class="js-block-nhan-vien">
                    <div class="flex items-center justify-between mb-2">
                        <input type="text" id="tim_nhan_vien" placeholder="Tìm nhân viên..."
                               class="w-64 h-10 px-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white text-sm">
                        <button type="button" id="chon_tat_ca"
                                class="text-sm text-blue-600 hover:underline">Chọn / bỏ chọn tất cả</button>
                    </div>
                    <div class="max-h-72 overflow-y-auto border border-gray-200 dark:border-slate-700 rounded-xl divide-y divide-gray-100 dark:divide-slate-700">
                        @foreach($nhanViens as $nv)
                            @php $ten = trim(($nv->ho_so->ho ?? '') . ' ' . ($nv->ho_so->ten ?? '')) ?: $nv->ten_dang_nhap; @endphp
                            <label class="js-nv-row flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-slate-700/40 cursor-pointer"
                                   data-ten="{{ \Illuminate\Support\Str::lower($ten) }}">
                                <input type="checkbox" name="nguoi_dung_ids[]" value="{{ $nv->id }}"
                                       class="js-nv-check rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                       @checked(in_array($nv->id, (array) old('nguoi_dung_ids', [])))>
                                <span class="flex-1 text-sm text-gray-900 dark:text-white">{{ $ten }}</span>
                                <span class="text-xs text-gray-400">{{ $nv->phongBan->ten_phong_ban ?? '—' }}</span>
                                <span class="text-xs text-gray-400">{{ number_format((float) $nv->luong_co_ban) }} đ</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Chọn phòng ban --}}
                <div class="js-block-phong-ban grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($phongBans as $pb)
                        <label class="flex items-center gap-3 px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700/40 cursor-pointer">
                            <input type="checkbox" name="phong_ban_ids[]" value="{{ $pb->id }}"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                   @checked(in_array($pb->id, (array) old('phong_ban_ids', [])))>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $pb->ten_phong_ban }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="js-block-toan-cong-ty bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                    <p class="text-sm text-blue-800 dark:text-blue-300">
                        <i class="fa-solid fa-users mr-1"></i>
                        Khoản thưởng sẽ được tạo cho <b>tất cả {{ $nhanViens->count() }} nhân viên đang làm việc</b>.
                    </p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 dark:border-slate-700">
                <a href="{{ route('admin.thuong.index', ['thang' => $thang, 'nam' => $nam]) }}"
                   class="px-6 py-3 rounded-xl bg-gray-200 hover:bg-gray-300 dark:bg-slate-700 dark:hover:bg-slate-600 transition">Huỷ</a>
                <button type="submit"
                        class="px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-lg transition">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Lưu khoản thưởng
                </button>
            </div>
        </form>
        @endif

    </div>
</div>

<script>
(function () {
    // --- Hiện/ẩn khối theo hình thức thưởng ---
    const hienKhoiTheoHinhThuc = () => {
        const hinhThuc = document.querySelector('.js-hinh-thuc:checked')?.value ?? 'mot_lan';
        document.querySelectorAll('.js-block-mot-lan').forEach(el => el.classList.toggle('hidden', hinhThuc !== 'mot_lan'));
        document.querySelectorAll('.js-block-dinh-ky').forEach(el => el.classList.toggle('hidden', hinhThuc !== 'dinh_ky'));
    };
    document.querySelectorAll('.js-hinh-thuc').forEach(el => el.addEventListener('change', hienKhoiTheoHinhThuc));

    // --- Hiện/ẩn khối theo phạm vi áp dụng ---
    const hienKhoiTheoPhamVi = () => {
        const phamVi = document.querySelector('.js-pham-vi:checked')?.value ?? 'nhan_vien';
        document.querySelectorAll('.js-block-nhan-vien').forEach(el => el.classList.toggle('hidden', phamVi !== 'nhan_vien'));
        document.querySelectorAll('.js-block-phong-ban').forEach(el => el.classList.toggle('hidden', phamVi !== 'phong_ban'));
        document.querySelectorAll('.js-block-toan-cong-ty').forEach(el => el.classList.toggle('hidden', phamVi !== 'toan_cong_ty'));
    };
    document.querySelectorAll('.js-pham-vi').forEach(el => el.addEventListener('change', hienKhoiTheoPhamVi));

    // --- Đơn vị của ô giá trị đổi theo cách tính ---
    const capNhatDonVi = () => {
        const cachTinh = document.getElementById('cach_tinh');
        const gia_tri  = document.getElementById('gia_tri');
        const donVi    = document.querySelector('.js-don-vi');
        if (!cachTinh || !gia_tri || !donVi) return;

        if (cachTinh.value === 'phan_tram_luong_cb') {
            donVi.textContent = '(% lương cơ bản)';
            gia_tri.step = '0.5';
            gia_tri.max  = '100';
        } else {
            donVi.textContent = '(VNĐ)';
            gia_tri.step = '1000';
            gia_tri.removeAttribute('max');
        }
    };
    document.getElementById('cach_tinh')?.addEventListener('change', capNhatDonVi);

    // --- Chọn loại thưởng: điền sẵn cách tính / giá trị / hình thức mặc định ---
    document.getElementById('loai_thuong_id')?.addEventListener('change', function () {
        const opt = this.selectedOptions[0];
        if (!opt || !opt.value) return;

        const cachTinh = document.getElementById('cach_tinh');
        const giaTri   = document.getElementById('gia_tri');

        if (cachTinh) cachTinh.value = opt.dataset.cachTinh;
        if (giaTri && !giaTri.value) giaTri.value = opt.dataset.giaTri;

        const radio = document.querySelector(`.js-hinh-thuc[value="${opt.dataset.hinhThuc}"]`);
        if (radio) radio.checked = true;

        capNhatDonVi();
        hienKhoiTheoHinhThuc();
    });

    // --- Tìm nhanh nhân viên ---
    document.getElementById('tim_nhan_vien')?.addEventListener('input', function () {
        const tu = this.value.trim().toLowerCase();
        document.querySelectorAll('.js-nv-row').forEach(row => {
            row.classList.toggle('hidden', tu !== '' && !row.dataset.ten.includes(tu));
        });
    });

    // --- Chọn / bỏ chọn tất cả nhân viên đang hiển thị ---
    document.getElementById('chon_tat_ca')?.addEventListener('click', function () {
        const rows = [...document.querySelectorAll('.js-nv-row')].filter(r => !r.classList.contains('hidden'));
        const checks = rows.map(r => r.querySelector('.js-nv-check'));
        const batTatCa = checks.some(c => !c.checked);
        checks.forEach(c => c.checked = batTatCa);
    });

    hienKhoiTheoHinhThuc();
    hienKhoiTheoPhamVi();
    capNhatDonVi();
})();
</script>
@endsection
