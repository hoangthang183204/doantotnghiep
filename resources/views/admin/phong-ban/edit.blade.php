@extends('layouts.admin')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

        <h1 class="text-xl font-bold text-gray-800 dark:text-white">
            Sửa phòng ban
        </h1>

        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Cập nhật thông tin phòng ban trong hệ thống
        </p>

    </div>

    {{-- FORM CARD --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

        <form method="POST"
              action="{{ route('admin.phong-ban.update', $phongBan->id) }}"
              class="space-y-4">

            @csrf
            @method('PUT')

            {{-- MÃ PHÒNG BAN --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Mã phòng ban <span class="text-red-500">*</span>
                </label>
                <input name="ma_phong_ban"
                       value="{{ old('ma_phong_ban', $phongBan->ma_phong_ban) }}"
                       class="mt-1 w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="VD: PB01">
                @error('ma_phong_ban')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- TÊN PHÒNG BAN --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Tên phòng ban <span class="text-red-500">*</span>
                </label>
                <input name="ten_phong_ban"
                       value="{{ old('ten_phong_ban', $phongBan->ten_phong_ban) }}"
                       class="mt-1 w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="VD: Phòng Kế toán">
                @error('ten_phong_ban')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- MÔ TẢ --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Mô tả
                </label>
                <textarea name="mo_ta"
                          rows="4"
                          class="mt-1 w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Mô tả phòng ban...">{{ old('mo_ta', $phongBan->mo_ta) }}</textarea>
            </div>

            {{-- NGÂN SÁCH --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Ngân sách
                </label>
                <input name="ngan_sach"
                       value="{{ old('ngan_sach', $phongBan->ngan_sach) }}"
                       type="number"
                       class="mt-1 w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="VD: 10000000">
                @error('ngan_sach')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- TRƯỞNG PHÒNG --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Trưởng phòng
                </label>
                <select name="truong_phong_id"
                        class="mt-1 w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500">
                    
                    <option value="">-- Chọn trưởng phòng --</option>
                    
                    @foreach($nguoiDungs as $nguoiDung)
                        <option value="{{ $nguoiDung->id }}" 
                            {{ old('truong_phong_id', $phongBan->truong_phong_id) == $nguoiDung->id ? 'selected' : '' }}>
                            @if($nguoiDung->hoSo && $nguoiDung->hoSo->ho)
                                {{ $nguoiDung->hoSo->ho }} {{ $nguoiDung->hoSo->ten }} ({{ $nguoiDung->email }})
                            @else
                                {{ $nguoiDung->ten_dang_nhap }} ({{ $nguoiDung->email }})
                            @endif
                        </option>
                    @endforeach
                    
                </select>
                <p class="text-xs text-gray-500 mt-1">Chọn người quản lý phòng ban này (có thể để trống)</p>
            </div>

            {{-- TRẠNG THÁI --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Trạng thái
                </label>
                <select name="trang_thai"
                        class="mt-1 w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500">

                    <option value="1" {{ old('trang_thai', $phongBan->trang_thai) == 1 ? 'selected' : '' }}>
                        ✅ Hoạt động
                    </option>

                    <option value="0" {{ old('trang_thai', $phongBan->trang_thai) == 0 ? 'selected' : '' }}>
                        ⛔ Không hoạt động
                    </option>

                </select>
            </div>

            {{-- BUTTON --}}
            <div class="flex justify-end gap-3 pt-2">

                <a href="{{ route('admin.phong-ban.index') }}"
                   class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-white transition">
                    Hủy
                </a>

                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow transition">
                    💾 Cập nhật phòng ban
                </button>

            </div>

        </form>

    </div>

</div>

@endsection