{{-- resources/views/truong-phong/nhan-vien/show.blade.php --}}

@extends('layouts.admin')

@section('title', 'Chi tiết nhân viên')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            <i class="fas fa-user mr-3 text-blue-600"></i>
            Chi tiết nhân viên
        </h1>
        <a href="{{ route('truong-phong.nhan-vien.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition">
            <i class="fas fa-arrow-left mr-1"></i> Quay lại
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        {{-- Thông tin cơ bản --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-4">
                @if($nhanVien->hoSo && $nhanVien->hoSo->anh_dai_dien)
                    <img src="{{ asset('storage/' . $nhanVien->hoSo->anh_dai_dien) }}" 
                         class="w-20 h-20 rounded-full object-cover border-4 border-gray-200 dark:border-gray-600">
                @else
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white text-2xl font-bold">
                        {{ substr($nhanVien->hoSo->ten ?? $nhanVien->ten_dang_nhap, 0, 1) }}
                    </div>
                @endif
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ $nhanVien->hoSo->ho ?? '' }} {{ $nhanVien->hoSo->ten ?? $nhanVien->ten_dang_nhap }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Mã NV: {{ $nhanVien->hoSo->ma_nhan_vien ?? 'N/A' }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Email: {{ $nhanVien->email }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Thông tin chi tiết --}}
        <div class="p-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Phòng ban</p>
                    <p class="font-medium">{{ $nhanVien->phongBan->ten_phong_ban ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Chức vụ</p>
                    <p class="font-medium">{{ $nhanVien->chucVu->ten ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Số điện thoại</p>
                    <p class="font-medium">{{ $nhanVien->hoSo->so_dien_thoai ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ngày sinh</p>
                    <p class="font-medium">{{ $nhanVien->hoSo->ngay_sinh ? \Carbon\Carbon::parse($nhanVien->hoSo->ngay_sinh)->format('d/m/Y') : 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Giới tính</p>
                    <p class="font-medium">{{ $nhanVien->hoSo->gioi_tinh ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Trạng thái</p>
                    <p>
                        @if($nhanVien->trang_thai == 1)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                Đang làm việc
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                <span class="w-1.5 h-1.5 bg-gray-500 rounded-full mr-1.5"></span>
                                Đã nghỉ việc
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection