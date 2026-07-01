<!-- resources/views/admin/tin-tuyen-dung/statistics.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Thống kê tuyển dụng
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Tổng quan số lượng ứng viên theo từng tin tuyển dụng
            </p>
        </div>
        <a href="{{ route('admin.tin-tuyen-dung.index') }}" 
           class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition shadow-sm">
            Quay lại danh sách
        </a>
    </div>

    <!-- Tổng quan -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $tongQuan['tong_tin'] }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Tổng tin tuyển dụng</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $tongQuan['dang_dang'] }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Đang đăng</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
            <div class="text-2xl font-bold text-gray-600 dark:text-gray-400">{{ $tongQuan['da_dung'] }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Đã dừng</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $tongQuan['tong_ung_vien'] }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Tổng ứng viên</div>
        </div>
    </div>

    <!-- Bảng thống kê chi tiết -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="font-semibold text-gray-900 dark:text-white">Chi tiết theo tin tuyển dụng</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 text-xs uppercase">
                        <th class="px-5 py-4 text-left">Tiêu đề</th>
                        <th class="px-5 py-4 text-center">Tổng</th>
                        <th class="px-5 py-4 text-center">Chờ duyệt</th>
                        <th class="px-5 py-4 text-center">Đã duyệt</th>
                        <th class="px-5 py-4 text-center">Trúng tuyển</th>
                        <th class="px-5 py-4 text-center">Không đạt</th>
                        <th class="px-5 py-4 text-center">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($stats as $item)
                        <tr class="hover:bg-blue-50/40 dark:hover:bg-gray-700/40 transition">
                            <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white">
                                {{ $item->tieu_de }}
                            </td>
                            <td class="px-5 py-4 text-center font-bold text-blue-600 dark:text-blue-400">
                                {{ $item->ung_viens_count }}
                            </td>
                            <td class="px-5 py-4 text-center text-yellow-600 dark:text-yellow-400">
                                {{ $item->ung_viens_count }}
                            </td>
                            <td class="px-5 py-4 text-center text-blue-600 dark:text-blue-400">
                                {{ $item->da_duyet_count }}
                            </td>
                            <td class="px-5 py-4 text-center text-green-600 dark:text-green-400">
                                {{ $item->dat_count }}
                            </td>
                            <td class="px-5 py-4 text-center text-red-600 dark:text-red-400">
                                {{ $item->khong_dat_count }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($item->trang_thai == 'dang_dang')
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs font-semibold">
                                        Đang đăng
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400 text-xs font-semibold">
                                        Đã dừng
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-gray-500 dark:text-gray-400">
                                Không có dữ liệu
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection