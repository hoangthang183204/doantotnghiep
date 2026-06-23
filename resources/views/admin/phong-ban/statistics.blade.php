@extends('layouts.admin')

@section('title', 'Thống kê phòng ban')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">📊 Thống kê phòng ban</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Tổng quan về phòng ban và nhân sự</p>
    </div>

    {{-- Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 text-center">
            <p class="text-3xl font-bold text-blue-600">{{ $totalPhongBans }}</p>
            <p class="text-sm text-gray-500">Tổng phòng ban</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 text-center">
            <p class="text-3xl font-bold text-green-600">{{ $activePhongBans }}</p>
            <p class="text-sm text-gray-500">Đang hoạt động</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 text-center">
            <p class="text-3xl font-bold text-red-600">{{ $inactivePhongBans }}</p>
            <p class="text-sm text-gray-500">Tạm dừng</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 text-center">
            <p class="text-3xl font-bold text-purple-600">{{ $totalNhanVien }}</p>
            <p class="text-sm text-gray-500">Tổng nhân viên</p>
        </div>
    </div>

    {{-- Bảng chi tiết --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="p-3 text-left">Phòng ban</th>
                    <th class="p-3 text-center">Số nhân viên</th>
                    <th class="p-3 text-center">Số chức vụ</th>
                    <th class="p-3 text-center">Ngân sách</th>
                    <th class="p-3 text-center">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @foreach($phongBanStats as $pb)
                    <tr class="border-t border-gray-200 dark:border-gray-700">
                        <td class="p-3 font-medium">{{ $pb->ten_phong_ban }}</td>
                        <td class="p-3 text-center">
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-sm">
                                {{ $pb->nguoi_dungs_count }}
                            </span>
                        </td>
                        <td class="p-3 text-center">
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-sm">
                                {{ $pb->chuc_vus_count }}
                            </span>
                        </td>
                        <td class="p-3 text-center">{{ number_format($pb->ngan_sach, 0, ',', '.') }} đ</td>
                        <td class="p-3 text-center">
                            @if($pb->trang_thai)
                                <span class="text-green-600">✅</span>
                            @else
                                <span class="text-red-600">⛔</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection