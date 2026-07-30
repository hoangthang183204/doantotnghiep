@extends('layouts.admin')

@section('title', 'Chi tiết khóa đào tạo')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                        Chi tiết khóa đào tạo
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                        Thông tin chi tiết khóa đào tạo của nhân viên
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.dao-tao.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm transition gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    {{-- BODY --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Mã nhân viên</label>
                <div class="font-medium text-gray-800 dark:text-white">{{ $daoTao->hoSo->ma_nhan_vien }}</div>
            </div>
            <div>
                <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Nhân viên</label>
                <div class="font-medium text-gray-800 dark:text-white">{{ $daoTao->hoSo->ho_ten }}</div>
            </div>
            <div>
                <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Tên khóa học</label>
                <div class="font-medium text-gray-800 dark:text-white">{{ $daoTao->ten_khoa_hoc }}</div>
            </div>
            <div>
                <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Đơn vị đào tạo</label>
                <div class="font-medium text-gray-800 dark:text-white">{{ $daoTao->to_chuc ?? '-' }}</div>
            </div>
            <div>
                <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Ngày bắt đầu</label>
                <div class="font-medium text-gray-800 dark:text-white">{{ $daoTao->ngay_bat_dau->format('d/m/Y') }}</div>
            </div>
            <div>
                <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Ngày kết thúc</label>
                <div class="font-medium text-gray-800 dark:text-white">{{ optional($daoTao->ngay_ket_thuc)->format('d/m/Y') ?? '-' }}</div>
            </div>
            <div>
                <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Kết quả</label>
                <div class="font-medium text-gray-800 dark:text-white">{{ $daoTao->ket_qua ?? '-' }}</div>
            </div>
            <div>
                <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Chứng chỉ</label>
                <div class="font-medium text-gray-800 dark:text-white">
                    @if($daoTao->co_chung_chi)
                        <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300">Có</span>
                    @else
                        <span class="px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300">Không</span>
                    @endif
                </div>
            </div>
            <div>
                <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Chi phí</label>
                <div class="font-medium text-blue-600">{{ number_format($daoTao->chi_phi) }} VNĐ</div>
            </div>
        </div>

        <hr class="my-6 border-gray-200 dark:border-gray-700">

        <div>
            <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Ghi chú</label>
            <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 text-sm">
                {{ $daoTao->ghi_chu ?: 'Không có ghi chú.' }}
            </div>
        </div>
    </div>

</div>
@endsection