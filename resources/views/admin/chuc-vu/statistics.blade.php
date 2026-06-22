@extends('layouts.admin')

@section('title', 'Thống kê chức vụ')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    📊 Thống kê chức vụ
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Tổng quan về chức vụ và nhân sự trong hệ thống
                </p>
            </div>
            <a href="{{ route('admin.chuc-vu.index') }}"
               class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                ← Quay lại
            </a>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tổng chức vụ</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalChucVus }}</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Đang hoạt động</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $activeChucVus }}</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ngừng hoạt động</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $inactiveChucVus }}</p>
                </div>
                <div class="p-3 bg-red-100 dark:bg-red-900 rounded-full">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tỷ lệ hoạt động</p>
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                        {{ $totalChucVus > 0 ? round(($activeChucVus / $totalChucVus) * 100, 1) : 0 }}%
                    </p>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- TOP CHỨC VỤ CÓ NHIỀU NHÂN VIÊN --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                🏆 Top chức vụ có nhiều nhân viên nhất
            </h2>
            <div class="space-y-3">
                @forelse($topChucVus as $index => $chucVu)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">
                                @if($index == 0) 🥇
                                @elseif($index == 1) 🥈
                                @elseif($index == 2) 🥉
                                @else {{ $index + 1 }}.
                                @endif
                            </span>
                            <div>
                                <p class="font-medium text-gray-800 dark:text-white">{{ $chucVu->ten }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $chucVu->phong_ban->ten_phong_ban ?? 'Chưa phân phòng' }}
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-full text-sm font-semibold">
                            {{ $chucVu->nguoi_dungs_count }} nhân viên
                        </span>
                    </div>
                @empty
                    <p class="text-center text-gray-500 dark:text-gray-400 py-4">Chưa có dữ liệu</p>
                @endforelse
            </div>
        </div>

        {{-- PHÂN BỐ CHỨC VỤ THEO PHÒNG BAN --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                📋 Phân bố chức vụ theo phòng ban
            </h2>
            <div class="space-y-3">
                @forelse($phongBanStats as $pb)
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-medium text-gray-800 dark:text-white">{{ $pb->ten_phong_ban }}</span>
                            <span class="px-2 py-1 bg-gray-200 dark:bg-gray-600 rounded text-sm">
                                {{ $pb->chuc_vus_count }} chức vụ
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-1">
                            @foreach($pb->chucVus as $cv)
                                <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded text-xs">
                                    {{ $cv->ten }} ({{ $cv->nguoi_dungs_count }})
                                </span>
                            @endforeach
                            @if($pb->chucVus->isEmpty())
                                <span class="text-xs text-gray-400">Chưa có chức vụ</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 dark:text-gray-400 py-4">Chưa có dữ liệu</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- BIỂU ĐỒ ĐƠN GIẢN --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
            📈 Số lượng chức vụ theo phòng ban
        </h2>
        <div class="space-y-2">
            @foreach($phongBanStats as $pb)
                <div>
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="text-gray-700 dark:text-gray-300">{{ $pb->ten_phong_ban }}</span>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $pb->chuc_vus_count }}</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                        <div class="bg-blue-600 dark:bg-blue-500 h-4 rounded-full transition-all duration-500"
                             style="width: {{ $totalChucVus > 0 ? ($pb->chuc_vus_count / $totalChucVus) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection