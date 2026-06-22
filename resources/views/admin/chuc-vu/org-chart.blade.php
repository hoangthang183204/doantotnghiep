@extends('layouts.admin')

@section('title', 'Sơ đồ tổ chức')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    🏢 Sơ đồ tổ chức
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Cơ cấu tổ chức công ty theo phòng ban và chức vụ
                </p>
            </div>
            <a href="{{ route('admin.chuc-vu.index') }}"
               class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                ← Quay lại
            </a>
        </div>
    </div>

    {{-- SƠ ĐỒ --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        @if($phongBans->isEmpty())
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <p class="text-gray-500 dark:text-gray-400">Chưa có phòng ban hoặc chức vụ nào</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($phongBans as $phongBan)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                        {{-- Header phòng ban --}}
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-blue-700 dark:to-blue-800 p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-white">{{ $phongBan->ten_phong_ban }}</h3>
                                    <p class="text-sm text-blue-100">Mã: {{ $phongBan->ma_phong_ban }}</p>
                                </div>
                                <span class="px-3 py-1 bg-white/20 text-white rounded-full text-sm font-semibold">
                                    {{ $phongBan->chucVus->count() }} chức vụ
                                </span>
                            </div>
                        </div>

                        {{-- Danh sách chức vụ --}}
                        <div class="p-4 space-y-3">
                            @foreach($phongBan->chucVus as $chucVu)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition group">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <span class="font-medium text-gray-800 dark:text-white">
                                                    {{ $chucVu->ten }}
                                                </span>
                                                <span class="px-2 py-0.5 bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 rounded text-xs font-mono">
                                                    {{ $chucVu->ma }}
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-4 mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    {{ $chucVu->nguoi_dungs_count }} NV
                                                </span>
                                                @if($chucVu->luong_co_ban)
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v1m0 1v1m0 1v1m0 1v1"></path>
                                                        </svg>
                                                        {{ number_format($chucVu->luong_co_ban, 0, ',', '.') }}₫
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            @if($chucVu->trang_thai)
                                                <span class="w-2 h-2 bg-green-500 rounded-full" title="Đang hoạt động"></span>
                                            @else
                                                <span class="w-2 h-2 bg-red-500 rounded-full" title="Ngừng hoạt động"></span>
                                            @endif
                                            <a href="{{ route('admin.chuc-vu.show', $chucVu->id) }}"
                                               class="opacity-0 group-hover:opacity-100 transition text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- CHÚ THÍCH --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4">
        <div class="flex flex-wrap items-center gap-6 text-sm">
            <span class="font-medium text-gray-700 dark:text-gray-300">Chú thích:</span>
            <span class="flex items-center gap-2">
                <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                <span class="text-gray-600 dark:text-gray-400">Đang hoạt động</span>
            </span>
            <span class="flex items-center gap-2">
                <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                <span class="text-gray-600 dark:text-gray-400">Ngừng hoạt động</span>
            </span>
            <span class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                = Số nhân viên
            </span>
            <span class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v1m0 1v1m0 1v1m0 1v1"></path>
                </svg>
                = Lương cơ bản
            </span>
        </div>
    </div>

</div>
@endsection