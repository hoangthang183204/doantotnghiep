@extends('layouts.employee')

@section('title', 'Bảng lương của tôi')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Bảng lương của tôi
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Danh sách phiếu lương theo tháng
                </p>
            </div>
        </div>
    </div>

    {{-- THÔNG BÁO --}}
    @if (session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 
                    text-green-700 dark:text-green-300 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Mã BL</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Mã NV</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Kỳ lương</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Tổng lương</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Thực nhận</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-200">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $p)
                        <tr class="border-t border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-4 py-3 font-medium text-blue-600">
                                {{ $p->bangLuong->ma_bang_luong ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $p->hoSo->ma_nhan_vien ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ str_pad($p->luong_thang, 2, '0', STR_PAD_LEFT) }}/{{ $p->luong_nam }}
                            </td>

                            <td class="px-4 py-3">
                                {{ number_format($p->tong_luong) }}
                            </td>

                            <td class="px-4 py-3 font-semibold text-green-600">
                                {{ number_format($p->luong_thuc_nhan) }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('employee.bang-luong.show', $p->id) }}"
                                   title="Xem chi tiết"
                                   class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-700 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <p class="text-lg font-semibold mb-1">Danh sách phiếu lương theo tháng</p>
                                    <p class="text-sm">Chưa có dữ liệu bảng lương</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4">
        {{ $payrolls->links() }}
    </div>

</div>

@endsection
