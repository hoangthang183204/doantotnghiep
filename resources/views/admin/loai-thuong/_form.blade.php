@php
    /** @var \App\Models\LoaiThuong|null $loaiThuong */
    $lt = $loaiThuong ?? null;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">
            Tên loại thưởng <span class="text-red-500">*</span>
        </label>
        <input type="text" name="ten" value="{{ old('ten', $lt->ten ?? '') }}" required maxlength="255"
               placeholder="VD: Thưởng chuyên cần, Thưởng Tết Nguyên đán..."
               class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
        @error('ten') <p class="text-sm text-red-500 mt-2">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">
            Mã <span class="text-red-500">*</span>
        </label>
        <input type="text" name="ma" value="{{ old('ma', $lt->ma ?? '') }}" required maxlength="50"
               placeholder="VD: CHUYEN_CAN, TET"
               class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white uppercase focus:ring-2 focus:ring-blue-500">
        @error('ma') <p class="text-sm text-red-500 mt-2">{{ $message }}</p> @enderror
    </div>
</div>

<div>
    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">Mô tả</label>
    <textarea name="mo_ta" rows="3" maxlength="1000"
              placeholder="Điều kiện xét thưởng, ghi chú nội bộ..."
              class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">{{ old('mo_ta', $lt->mo_ta ?? '') }}</textarea>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div>
        <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">
            Hình thức mặc định <span class="text-red-500">*</span>
        </label>
        <select name="hinh_thuc_mac_dinh"
                class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
            @foreach(\App\Models\LoaiThuong::$hinhThucLabels as $key => $label)
                <option value="{{ $key }}" @selected(old('hinh_thuc_mac_dinh', $lt->hinh_thuc_mac_dinh ?? 'mot_lan') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <p class="text-xs text-gray-400 mt-1">Chỉ là gợi ý khi tạo khoản thưởng, vẫn đổi được sau.</p>
    </div>

    <div>
        <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">
            Cách tính <span class="text-red-500">*</span>
        </label>
        <select name="cach_tinh"
                class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
            @foreach(\App\Models\LoaiThuong::$cachTinhLabels as $key => $label)
                <option value="{{ $key }}" @selected(old('cach_tinh', $lt->cach_tinh ?? 'so_tien_co_dinh') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-slate-300">
            Giá trị mặc định <span class="text-red-500">*</span>
        </label>
        <input type="number" name="gia_tri_mac_dinh" value="{{ old('gia_tri_mac_dinh', $lt->gia_tri_mac_dinh ?? 0) }}"
               min="0" step="1000" required
               class="w-full h-12 px-4 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
        <p class="text-xs text-gray-400 mt-1">Số tiền (đ) hoặc phần trăm (%) tuỳ cách tính ở trên.</p>
        @error('gia_tri_mac_dinh') <p class="text-sm text-red-500 mt-2">{{ $message }}</p> @enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <label class="flex items-start gap-3 p-4 rounded-xl border border-gray-200 dark:border-slate-700 cursor-pointer">
        <input type="checkbox" name="chiu_thue" value="1" @checked(old('chiu_thue', $lt->chiu_thue ?? true))
               class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
        <span>
            <span class="block font-medium text-gray-900 dark:text-white">Tính vào thu nhập chịu thuế TNCN</span>
            <span class="block text-xs text-gray-500 dark:text-slate-400 mt-0.5">
                Đa số các khoản thưởng đều chịu thuế TNCN. Bỏ chọn với khoản được miễn theo quy định.
            </span>
        </span>
    </label>

    <label class="flex items-start gap-3 p-4 rounded-xl border border-gray-200 dark:border-slate-700 cursor-pointer">
        <input type="checkbox" name="trang_thai" value="1" @checked(old('trang_thai', $lt->trang_thai ?? true))
               class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
        <span>
            <span class="block font-medium text-gray-900 dark:text-white">Đang sử dụng</span>
            <span class="block text-xs text-gray-500 dark:text-slate-400 mt-0.5">
                Chỉ loại thưởng "đang sử dụng" mới xuất hiện khi gán thưởng cho nhân viên.
            </span>
        </span>
    </label>
</div>
