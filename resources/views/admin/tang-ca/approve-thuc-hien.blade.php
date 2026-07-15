{{-- resources/views/admin/tang-ca/approve-thuc-hien.blade.php --}}
@extends('layouts.admin')

@section('title', 'Xác nhận hoàn thành tăng ca')

@section('content')
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Xác nhận hoàn thành tăng ca</h1>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Nhân viên đã xác nhận đã làm tăng ca. Vui lòng xác nhận
                        hoàn thành và tính lương.</p>
                </div>
                <a href="{{ route('admin.tang-ca.show', $tangCa->id) }}"
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition text-sm">
                    ← Quay lại
                </a>
            </div>
        </div>

        {{-- THÔNG BÁO --}}
        @if (session('error'))
            <div
                class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg shadow-sm">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div
                class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg shadow-sm">
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
                                $loaiLabels = [
                                    'ngay_thuong' => 'Ngày thường',
                                    'ngay_nghi' => 'Ngày nghỉ',
                                    'le_tet' => 'Lễ / Tết',
                                ];
                            @endphp
                            {{ $loaiLabels[$tangCa->loai_tang_ca] ?? $tangCa->loai_tang_ca }}
                        </p>
                    </div>
                </div>

                {{-- Thông tin lương --}}
                {{-- Phần hiển thị lương trong view --}}
                <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">💰 Thông tin lương</p>
                    <div class="grid grid-cols-3 gap-4 mt-2">
                        <div>
                            <p class="text-xs text-gray-500">Lương cơ bản</p>
                            <p class="font-semibold text-blue-600">
                                {{ number_format($luongCoBan ?? 0, 0) }}đ
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Hệ số tăng ca</p>
                            <p class="font-semibold text-blue-600">
                                @php
                                    $heSo = match ($tangCa->loai_tang_ca) {
                                        'ngay_thuong' => '1.5 (150%)',
                                        'ngay_nghi' => '2.0 (200%)',
                                        'le_tet' => '3.0 (300%)',
                                        default => '1.5 (150%)',
                                    };
                                @endphp
                                {{ $heSo }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Lương theo giờ</p>
                            <p class="font-semibold text-blue-600">
                                {{ number_format($luongTheoGio ?? 0, 0) }}đ/giờ
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Form xác nhận --}}
                <div class="border-t pt-4 mt-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Số giờ thực tế <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="so_gio_tang_ca_thuc_te" id="so_gio_thuc_te"
                            value="{{ old('so_gio_tang_ca_thuc_te', $tangCa->thuc_hien->so_gio_tang_ca_thuc_te ?? $tangCa->so_gio_tang_ca) }}"
                            step="0.5" min="0.5" max="16" onchange="tinhLuong()"
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

                    {{-- Hiển thị lương dự kiến --}}
                    <div
                        class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                        <p class="text-sm text-green-600 dark:text-green-400 font-medium">💰 Lương tăng ca dự kiến</p>
                        <p id="luongDuKien" class="text-2xl font-bold text-green-700 dark:text-green-300">
                            {{ number_format(0, 0) }}đ
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            (*) Lương tăng ca = Số giờ thực tế × Lương theo giờ × Hệ số
                        </p>
                    </div>

                    <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" onclick="return confirm('Xác nhận hoàn thành và tính lương tăng ca?')"
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
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

    @push('scripts')
        <script>
            // ⭐ TÍNH LƯƠNG TĂNG CA TRỰC TIẾP
            function tinhLuong() {
                const soGio = parseFloat(document.getElementById('so_gio_thuc_te').value) || 0;
                const luongTheoGio = {{ $luongTheoGio ?? 0 }};
                const heSo =
                    {{ match ($tangCa->loai_tang_ca) {
                        'ngay_thuong' => 1.5,
                        'ngay_nghi' => 2.0,
                        'le_tet' => 3.0,
                        default => 1.5,
                    } }};

                const luong = soGio * luongTheoGio * heSo;
                document.getElementById('luongDuKien').textContent = new Intl.NumberFormat('vi-VN').format(Math.round(luong)) +
                    'đ';
            }

            // ⭐ TÍNH KHI LOAD TRANG
            document.addEventListener('DOMContentLoaded', function() {
                tinhLuong();
            });
        </script>
    @endpush
@endsection
