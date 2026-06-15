@extends('layouts.admin')

@section('title', 'Sửa phòng ban')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            Sửa phòng ban
        </h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Cập nhật thông tin phòng ban trong hệ thống
        </p>
    </div>

    {{-- FORM CARD --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

        <form method="POST" action="{{ route('admin.phong-ban.update', $phongBan->id) }}">
            @csrf
            @method('PUT')

            {{-- GRID 2 CỘT --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- MÃ PHÒNG BAN --}}
                <div>
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Mã phòng ban <span class="text-red-500">*</span>
                    </label>

                    <input type="text"
                           name="ma_phong_ban"
                           value="{{ old('ma_phong_ban', $phongBan->ma_phong_ban) }}"
                           placeholder="VD: PB01..."
                           class="w-full rounded-lg border @error('ma_phong_ban') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    
                    @error('ma_phong_ban')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- TÊN PHÒNG BAN --}}
                <div>
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Tên phòng ban <span class="text-red-500">*</span>
                    </label>

                    <input type="text"
                           name="ten_phong_ban"
                           value="{{ old('ten_phong_ban', $phongBan->ten_phong_ban) }}"
                           placeholder="VD: Phòng Kế toán..."
                           class="w-full rounded-lg border @error('ten_phong_ban') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    
                    @error('ten_phong_ban')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- NGÂN SÁCH --}}
                <div>
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Ngân sách
                    </label>

                    <input type="number"
                           name="ngan_sach"
                           value="{{ old('ngan_sach', $phongBan->ngan_sach) }}"
                           placeholder="VD: 10000000"
                           class="w-full rounded-lg border @error('ngan_sach') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    
                    @error('ngan_sach')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- TRƯỞNG PHÒNG --}}
                <div>
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Trưởng phòng
                    </label>

                    <select name="truong_phong_id"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        
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
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Trạng thái
                    </label>

                    <select name="trang_thai"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="1" {{ old('trang_thai', $phongBan->trang_thai) == 1 ? 'selected' : '' }}>
                            ✅ Hoạt động
                        </option>
                        <option value="0" {{ old('trang_thai', $phongBan->trang_thai) == 0 ? 'selected' : '' }}>
                            ⛔ Không hoạt động
                        </option>
                    </select>
                </div>

                {{-- MÔ TẢ (Cho chiếm 2 cột) --}}
                <div class="md:col-span-2">
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Mô tả
                    </label>

                    <textarea name="mo_ta"
                              rows="4"
                              placeholder="Nhập mô tả chi tiết về phòng ban này..."
                              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('mo_ta', $phongBan->mo_ta) }}</textarea>
                </div>

            </div>

            {{-- BUTTON --}}
            <div class="flex gap-3 mt-8">

                <button type="submit"
                        class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shadow-sm">
                    💾 Cập nhật phòng ban
                </button>

                <a href="{{ route('admin.phong-ban.index') }}"
                   class="px-5 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-white rounded-lg transition">
                    Hủy
                </a>

            </div>

        </form>

    </div>

</div>

@endsection