@extends('layouts.admin')

@section('title', 'Chi tiết tài khoản - ' . $user->ho_ten)

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white text-lg">
                        <i class="fas fa-user"></i>
                    </span>
                    Chi tiết tài khoản
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Thông tin chi tiết của tài khoản {{ $user->ho_ten }}
                </p>
            </div>
            <a href="{{ route('admin.nguoi-dung.index') }}" 
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl text-sm font-medium transition flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Quay lại
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Cột trái - Thông tin cá nhân --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 text-center">
                {{-- Avatar --}}
                <div class="flex justify-center">
                    @if($user->hoSo && $user->hoSo->anh_dai_dien)
                        <img src="{{ asset('storage/' . $user->hoSo->anh_dai_dien) }}" 
                             alt="Avatar" 
                             class="w-24 h-24 rounded-full object-cover border-4 border-blue-500 shadow-lg">
                    @else
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                            {{ strtoupper(substr($user->ho_ten, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <h3 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ $user->ho_ten }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>

                <div class="mt-4 flex justify-center gap-2">
                    @if($user->trang_thai == 1)
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block mr-1.5"></span>
                            Hoạt động
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block mr-1.5"></span>
                            Đã khóa
                        </span>
                    @endif
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3 text-sm">
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                        <p class="text-gray-500 dark:text-gray-400 text-xs">Mã NV</p>
                        <p class="font-semibold text-gray-900 dark:text-white font-mono">{{ $user->hoSo->ma_nhan_vien ?? '---' }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                        <p class="text-gray-500 dark:text-gray-400 text-xs">Vai trò</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $user->vai_tro->ten_hien_thi ?? '---' }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                        <p class="text-gray-500 dark:text-gray-400 text-xs">Phòng ban</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $user->phong_ban->ten_phong_ban ?? '---' }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                        <p class="text-gray-500 dark:text-gray-400 text-xs">Chức vụ</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $user->chuc_vu->ten ?? '---' }}</p>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-center gap-3">
                    <a href="{{ route('admin.nguoi-dung.edit', $user->id) }}" 
                        class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl text-sm font-medium transition">
                        <i class="fas fa-pen mr-1"></i> Sửa
                    </a>
                    <a href="{{ route('admin.ho-so.show', $user->hoSo->id ?? 0) }}" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition">
                        <i class="fas fa-folder-open mr-1"></i> Hồ sơ
                    </a>
                </div>
            </div>
        </div>

        {{-- Cột phải - Thông tin chi tiết --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Thông tin tài khoản --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
                    <h4 class="font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                        Thông tin tài khoản
                    </h4>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Tên đăng nhập</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $user->ten_dang_nhap }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Email</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Vai trò</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $user->vai_tro->ten_hien_thi ?? '---' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Ngày tạo</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Lần đăng nhập cuối</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $user->lan_dang_nhap_cuoi ? $user->lan_dang_nhap_cuoi->format('d/m/Y H:i') : 'Chưa đăng nhập' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">IP đăng nhập cuối</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $user->ip_dang_nhap_cuoi ?? '---' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hồ sơ nhân viên --}}
            @if($user->hoSo)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
                    <h4 class="font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-id-card mr-2 text-green-500"></i>
                        Hồ sơ nhân viên
                    </h4>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Họ và tên</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $user->hoSo->ho }} {{ $user->hoSo->ten }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Mã nhân viên</p>
                            <p class="font-medium text-gray-900 dark:text-white font-mono">{{ $user->hoSo->ma_nhan_vien }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Số điện thoại</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $user->hoSo->so_dien_thoai ?? '---' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Ngày sinh</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $user->hoSo->ngay_sinh ? $user->hoSo->ngay_sinh->format('d/m/Y') : '---' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection