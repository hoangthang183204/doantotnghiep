@extends('layouts.admin')

@section('title', 'Xác nhận hoàn thành tăng ca')

@section('content')
<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Xác nhận hoàn thành tăng ca</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Nhân viên đã xác nhận đã làm tăng ca. Vui lòng xác nhận hoàn thành và tính lương.</p>
            </div>
            <a href="{{ route('admin.tang-ca.show', $tangCa->id) }}" 
               class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition text-sm">
                ← Quay lại
            </a>
        </div>
    </div>

    {{-- THÔNG BÁO --}}
    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg shadow-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.tang-ca.employee.tang-ca.approve-thuc-hien', $tangCa->id) }}">
            @csrf

            {{-- Thông tin nhân viên --}}
            <div class="grid grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                <div>
                    <p class="text-xs text-gray-500">Nhân viên</p>
                    <p class="font-semibold">
                        {{ optional($tangCa->nguoi_dung->hoSo)->ho }} {{ optional($tangCa->nguoi_dung->hoSo)->ten }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Mã nhân viên</p>
                    <p class="font-semibold">{{ optional($tangCa->nguoi_dung->hoSo)->ma_nhan_vien ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Ngày tăng ca</p>
                    <p class="font-semibold">{{ $tangCa->ngay_tang_ca->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Giờ đăng ký</p>
                    <p class="font-semibold">{{ $tangCa->gio_bat_dau }} - {{ $tangCa->gio_ket_thuc }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Số giờ đăng ký</p>
                    <p class="font-semibold">{{ $tangCa->so_gio_tang_ca }} giờ</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Loại tăng ca</p>
                    <p class="font-semibold">
                        @php
                            $loaiLabels = ['ngay_thuong' => 'Ngày thường', 'ngay_nghi' => 'Ngày nghỉ', 'le_tet' => 'Lễ / Tết'];
                        @endphp
                        {{ $loaiLabels[$tangCa->loai_tang_ca] ?? $tangCa->loai_tang_ca }}
                    </p>
                </div>
            </div>

            {{-- Form xác nhận --}}
            <div class="border-t pt-4 mt-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Số giờ thực tế <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="so_gio_tang_ca_thuc_te" 
                           value="{{ old('so_gio_tang_ca_thuc_te', $tangCa->thuc_hien->so_gio_tang_ca_thuc_te ?? $tangCa->so_gio_tang_ca) }}"
                           step="0.5" min="0.5" max="16"
                           class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('so_gio_tang_ca_thuc_te')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Công việc đã thực hiện
                    </label>
                    <textarea name="cong_viec_da_thuc_hien" rows="3"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500"
                        placeholder="Nhập công việc đã thực hiện...">{{ old('cong_viec_da_thuc_hien') }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Ghi chú
                    </label>
                    <textarea name="ghi_chu" rows="2"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500"
                        placeholder="Ghi chú thêm...">{{ old('ghi_chu') }}</textarea>
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" 
                            onclick="return confirm('Xác nhận hoàn thành và tính lương tăng ca?')"
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Xác nhận hoàn thành & tính lương
                    </button>
                    <a href="{{ route('admin.tang-ca.show', $tangCa->id) }}" 
                       class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                        Hủy
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection