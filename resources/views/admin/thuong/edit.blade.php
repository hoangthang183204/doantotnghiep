@extends('layouts.admin')

@section('title', 'Sửa khoản thưởng')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-slate-900 py-8 px-6">
    <div class="max-w-4xl mx-auto space-y-8">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Sửa khoản thưởng</h1>
                <p class="mt-2 text-gray-500 dark:text-slate-400">{{ $thuong->loaiThuong->ten ?? 'Khoản thưởng' }}</p>
            </div>
            <a href="{{ route('admin.thuong.index') }}"
               class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-100 dark:hover:bg-slate-700 transition shadow-sm">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </a>
        </div>

        @include('layouts.partials.alerts')

        <form action="{{ route('admin.thuong.update', $thuong->id) }}" method="POST"
              class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-gray-200 dark:border-slate-700 p-8 space-y-7">
            @csrf
            @method('PUT')

            {{-- Nhân viên --}}
            <div>
                <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">
                    Nhân viên <span class="text-red-500">*</span>
                </label>
                <select name="nguoi_dung_id" required
                        class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @foreach($nhanViens as $nv)
                        <option value="{{ $nv->id }}" @selected(old('nguoi_dung_id', $thuong->nguoi_dung_id) == $nv->id)>
                            {{ trim(($nv->ho_so->ho ?? '') . ' ' . ($nv->ho_so->ten ?? '')) ?: $nv->ten_dang_nhap }}
                        </option>
                    @endforeach
                </select>
                @error('nguoi_dung_id') <p class="text-sm text-red-500 mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- Hình thức --}}
            <div>
                <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">
                    Hình thức <span class="text-red-500">*</span>
                </label>
                <select name="hinh_thuc" class="js-hinh-thuc w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @foreach(\App\Models\ThuongNhanVien::$hinhThucLabels as $key => $label)
                        <option value="{{ $key }}" @selected(old('hinh_thuc', $thuong->hinh_thuc) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Kỳ áp dụng: 1 lần --}}
            <div class="js-block-mot-lan grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">Tháng áp dụng</label>
                    <select name="thang" class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" @selected(old('thang', $thuong->thang ?? now()->month) == $m)>Tháng {{ $m }}</option>
                        @endfor
                    </select>
                    @error('thang') <p class="text-sm text-red-500 mt-2">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">Năm</label>
                    <input type="number" name="nam" value="{{ old('nam', $thuong->nam ?? now()->year) }}" min="2000" max="2100"
                           class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('nam') <p class="text-sm text-red-500 mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Kỳ áp dụng: định kỳ --}}
            <div class="js-block-dinh-ky grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">Bắt đầu áp dụng từ</label>
                    <input type="date" name="ngay_bat_dau"
                           value="{{ old('ngay_bat_dau', optional($thuong->ngay_bat_dau)->toDateString() ?? now()->startOfMonth()->toDateString()) }}"
                           class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('ngay_bat_dau') <p class="text-sm text-red-500 mt-2">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">Kết thúc (để trống = không thời hạn)</label>
                    <input type="date" name="ngay_ket_thuc" value="{{ old('ngay_ket_thuc', optional($thuong->ngay_ket_thuc)->toDateString()) }}"
                           class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('ngay_ket_thuc') <p class="text-sm text-red-500 mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Loại thưởng / cách tính / giá trị --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">Loại thưởng</label>
                    <select name="loai_thuong_id" required
                            class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        @foreach($loaiThuongs as $lt)
                            <option value="{{ $lt->id }}" @selected(old('loai_thuong_id', $thuong->loai_thuong_id) == $lt->id)>
                                {{ $lt->ten }}@if(!$lt->trang_thai) (ngừng sử dụng)@endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">Cách tính</label>
                    <select name="cach_tinh" class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        @foreach(\App\Models\LoaiThuong::$cachTinhLabels as $key => $label)
                            <option value="{{ $key }}" @selected(old('cach_tinh', $thuong->cach_tinh) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">Giá trị</label>
                    <input type="number" name="gia_tri" value="{{ old('gia_tri', (float) $thuong->gia_tri) }}" min="0" step="0.5" required
                           class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('gia_tri') <p class="text-sm text-red-500 mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Thuế / trạng thái --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">Thuế TNCN</label>
                    @php
                        $chiuThueHienTai = $thuong->chiu_thue === null ? 'mac_dinh' : ($thuong->chiu_thue ? 'co' : 'khong');
                    @endphp
                    <select name="chiu_thue" class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        <option value="mac_dinh" @selected(old('chiu_thue', $chiuThueHienTai) === 'mac_dinh')>Theo cấu hình của loại thưởng</option>
                        <option value="co" @selected(old('chiu_thue', $chiuThueHienTai) === 'co')>Tính vào thu nhập chịu thuế</option>
                        <option value="khong" @selected(old('chiu_thue', $chiuThueHienTai) === 'khong')>Không chịu thuế</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">Trạng thái</label>
                    <select name="trang_thai" class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        @foreach(\App\Models\ThuongNhanVien::$trangThaiLabels as $key => $label)
                            <option value="{{ $key }}" @selected(old('trang_thai', $thuong->trang_thai) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">Lý do / ghi chú</label>
                <input type="text" name="ly_do" value="{{ old('ly_do', $thuong->ly_do) }}" maxlength="255"
                       class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 dark:border-slate-700">
                <a href="{{ route('admin.thuong.index') }}"
                   class="px-6 py-3 rounded-xl bg-gray-200 hover:bg-gray-300 dark:bg-slate-700 dark:hover:bg-slate-600 transition">Huỷ</a>
                <button type="submit"
                        class="px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-lg transition">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Cập nhật
                </button>
            </div>
        </form>

    </div>
</div>

<script>
(function () {
    const select = document.querySelector('.js-hinh-thuc');
    const capNhat = () => {
        const hinhThuc = select?.value ?? 'mot_lan';
        document.querySelectorAll('.js-block-mot-lan').forEach(el => el.classList.toggle('hidden', hinhThuc !== 'mot_lan'));
        document.querySelectorAll('.js-block-dinh-ky').forEach(el => el.classList.toggle('hidden', hinhThuc !== 'dinh_ky'));
    };
    select?.addEventListener('change', capNhat);
    capNhat();
})();
</script>
@endsection
