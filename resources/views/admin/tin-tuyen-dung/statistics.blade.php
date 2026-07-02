@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                📊 Thống kê tuyển dụng
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Tổng quan số lượng ứng viên theo từng tin tuyển dụng và trạng thái
            </p>
        </div>
        <a href="{{ route('admin.tin-tuyen-dung.index') }}" 
           class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Quay lại danh sách
        </a>
    </div>

    <!-- Tổng quan -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-xl bg-blue-100 dark:bg-blue-900/30">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $tongQuan['tong_tin'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Tổng tin tuyển dụng</div>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-xl bg-green-100 dark:bg-green-900/30">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $tongQuan['dang_tuyen'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Đang tuyển</div>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-xl bg-yellow-100 dark:bg-yellow-900/30">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $tongQuan['tam_dung'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Tạm dừng</div>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-xl bg-purple-100 dark:bg-purple-900/30">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $tongQuan['tong_ung_vien'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Tổng ứng viên</div>
                </div>
            </div>
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
                        <th class="px-5 py-4 text-left">Phòng ban</th>
                        <th class="px-5 py-4 text-center">Tổng</th>
                        <th class="px-5 py-4 text-center">Mới nộp</th>
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
                                <a href="{{ route('admin.tin-tuyen-dung.show', $item->id) }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                    {{ $item->tieu_de }}
                                </a>
                            </td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">
                                {{ $item->phongBan?->ten_phong_ban ?? 'Chưa xác định' }}
                            </td>
                            <td class="px-5 py-4 text-center font-bold text-blue-600 dark:text-blue-400">
                                {{ $item->ung_viens_count ?? 0 }}
                            </td>
                            <td class="px-5 py-4 text-center text-gray-600 dark:text-gray-300">
                                {{ $item->ungViens->where('trang_thai', 'moi_nop')->count() }}
                            </td>
                            <td class="px-5 py-4 text-center text-yellow-600 dark:text-yellow-400">
                                {{ $item->ung_viens_count ?? 0 }}
                            </td>
                            <td class="px-5 py-4 text-center text-blue-600 dark:text-blue-400">
                                {{ $item->da_duyet_count ?? 0 }}
                            </td>
                            <td class="px-5 py-4 text-center text-green-600 dark:text-green-400">
                                {{ $item->dat_count ?? 0 }}
                            </td>
                            <td class="px-5 py-4 text-center text-red-600 dark:text-red-400">
                                {{ $item->khong_dat_count ?? 0 }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                @switch($item->trang_thai)
                                    @case('nhap')
                                        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400 text-xs font-semibold">📝 Nháp</span>
                                        @break
                                    @case('dang_tuyen')
                                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs font-semibold">✅ Đang tuyển</span>
                                        @break
                                    @case('tam_dung')
                                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 text-xs font-semibold">⏸️ Tạm dừng</span>
                                        @break
                                    @case('ket_thuc')
                                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 text-xs font-semibold">🔚 Kết thúc</span>
                                        @break
                                    @default
                                        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400 text-xs font-semibold">{{ $item->trang_thai }}</span>
                                @endswitch
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-10 text-gray-500 dark:text-gray-400">
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