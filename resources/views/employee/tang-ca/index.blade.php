{{-- resources/views/employee/tang-ca/index.blade.php --}}
@extends('layouts.employee')

@section('title', 'Đơn xin tăng ca')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-clock mr-3 text-blue-600 dark:text-blue-400"></i>
                Đơn xin tăng ca
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Quản lý đơn xin tăng ca của bạn</p>
        </div>
        <a href="{{ route('employee.tang-ca.create') }}" 
           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus-circle"></i>
            Tạo đơn mới
        </a>
    </div>

    {{-- THỐNG KÊ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $thongKe['tong'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">Tổng đơn</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $thongKe['cho_duyet'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">⏳ Chờ duyệt</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $thongKe['da_duyet'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">✅ Đã duyệt</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $thongKe['tu_choi'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">❌ Từ chối</p>
        </div>
    </div>

    {{-- DANH SÁCH --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="font-semibold text-gray-900 dark:text-white">📋 Danh sách đơn tăng ca</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Giờ</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Số giờ</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($donTangCa as $don)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                {{ Carbon\Carbon::parse($don->ngay_tang_ca)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                {{ $don->gio_bat_dau }} - {{ $don->gio_ket_thuc }}
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-blue-600 dark:text-blue-400">
                                {{ $don->so_gio_tang_ca }} giờ
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                @php
                                    $loaiLabels = [
                                        'ngay_thuong' => 'Ngày thường',
                                        'ngay_nghi' => 'Ngày nghỉ',
                                        'le_tet' => 'Lễ / Tết',
                                    ];
                                @endphp
                                {{ $loaiLabels[$don->loai_tang_ca] ?? $don->loai_tang_ca }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $badgeClasses = [
                                        'cho_duyet' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                        'da_duyet' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                        'tu_choi' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                        'huy' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                    ];
                                    $trangThaiLabels = [
                                        'cho_duyet' => 'Chờ duyệt',
                                        'da_duyet' => 'Đã duyệt',
                                        'tu_choi' => 'Từ chối',
                                        'huy' => 'Đã hủy',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badgeClasses[$don->trang_thai] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $trangThaiLabels[$don->trang_thai] ?? $don->trang_thai }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('employee.tang-ca.show', $don->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($don->trang_thai == 'cho_duyet')
                                        <form action="{{ route('employee.tang-ca.huy', $don->id) }}" method="POST" 
                                              onsubmit="return confirm('Bạn có chắc muốn hủy đơn này?')">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm" title="Hủy đơn">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                <i class="fas fa-inbox text-2xl block mb-2 text-gray-300 dark:text-gray-600"></i>
                                Chưa có đơn xin tăng ca nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($donTangCa->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                {{ $donTangCa->links() }}
            </div>
        @endif
    </div>
</div>
@endsection