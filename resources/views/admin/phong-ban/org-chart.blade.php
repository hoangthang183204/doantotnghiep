@extends('layouts.admin')

@section('title', 'Sơ đồ phòng ban')

@section('content')
<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">🏢 Sơ đồ phòng ban</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Cơ cấu tổ chức các phòng ban</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($phongBans as $phongBan)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-4 text-white">
                    <h3 class="text-lg font-bold">{{ $phongBan->ten_phong_ban }}</h3>
                    <p class="text-sm text-blue-100">Mã: {{ $phongBan->ma_phong_ban }}</p>
                </div>
                
                {{-- Body --}}
                <div class="p-4">
                    <div class="flex justify-between text-sm mb-3">
                        <span class="text-gray-500 dark:text-gray-400">👤 Trưởng phòng:</span>
                        <span class="font-medium">{{ $phongBan->truong_phong->ten_dang_nhap ?? 'Chưa có' }}</span>
                    </div>
                    <div class="flex justify-between text-sm mb-3">
                        <span class="text-gray-500 dark:text-gray-400">👥 Số nhân viên:</span>
                        <span class="font-medium text-blue-600">{{ $phongBan->nguoi_dungs_count }}</span>
                    </div>
                    <div class="flex justify-between text-sm mb-3">
                        <span class="text-gray-500 dark:text-gray-400">📋 Số chức vụ:</span>
                        <span class="font-medium text-green-600">{{ $phongBan->chucVus->count() }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">💰 Ngân sách:</span>
                        <span class="font-medium">{{ number_format($phongBan->ngan_sach, 0, ',', '.') }} đ</span>
                    </div>
                    
                    {{-- Danh sách chức vụ --}}
                    @if($phongBan->chucVus->isNotEmpty())
                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-xs text-gray-500 mb-2">Chức vụ:</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach($phongBan->chucVus as $cv)
                                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-xs rounded">
                                        {{ $cv->ten }} ({{ $cv->nguoi_dungs_count }})
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection