@extends('layouts.admin')

@section('title', 'Chi tiết ứng lương')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                    Chi tiết yêu cầu ứng lương
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Thông tin chi tiết của khoản khấu trừ
                </p>
            </div>
            <span class="text-sm text-gray-400 dark:text-gray-500">
                #{{ $khauTru->id }}
            </span>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        
        {{-- Thông tin --}}
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            
            {{-- Nhân viên --}}
            <div class="grid grid-cols-1 md:grid-cols-4 px-6 py-4">
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400 md:col-span-1">
                    Nhân viên
                </div>
                <div class="md:col-span-3 text-gray-900 dark:text-white font-medium">
                    {{ trim(($khauTru->nguoiDung->ho_so->ho ?? '').' '.($khauTru->nguoiDung->ho_so->ten ?? '')) }}
                </div>
            </div>

            {{-- Loại --}}
            <div class="grid grid-cols-1 md:grid-cols-4 px-6 py-4">
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400 md:col-span-1">
                    Loại khấu trừ
                </div>
                <div class="md:col-span-3">
                    <span class="inline-block px-3 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm">
                        {{ $khauTru->loai_text }}
                    </span>
                </div>
            </div>

            {{-- Số tiền --}}
            <div class="grid grid-cols-1 md:grid-cols-4 px-6 py-4">
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400 md:col-span-1">
                    Số tiền
                </div>
                <div class="md:col-span-3 text-lg font-semibold text-gray-900 dark:text-white">
                    {{ number_format($khauTru->so_tien) }} ₫
                </div>
            </div>

            {{-- Lý do --}}
            <div class="grid grid-cols-1 md:grid-cols-4 px-6 py-4">
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400 md:col-span-1 pt-1">
                    Lý do
                </div>
                <div class="md:col-span-3 text-gray-700 dark:text-gray-300">
                    {{ $khauTru->ly_do ?? 'Không có lý do' }}
                </div>
            </div>

            {{-- Tháng --}}
            <div class="grid grid-cols-1 md:grid-cols-4 px-6 py-4">
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400 md:col-span-1">
                    Tháng áp dụng
                </div>
                <div class="md:col-span-3 text-gray-700 dark:text-gray-300">
                    {{ $khauTru->thang }}/{{ $khauTru->nam }}
                </div>
            </div>

            {{-- Trạng thái --}}
            <div class="grid grid-cols-1 md:grid-cols-4 px-6 py-4">
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400 md:col-span-1">
                    Trạng thái
                </div>
                <div class="md:col-span-3">
                    @if($khauTru->trang_thai == 'hieu_luc')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 text-sm font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            Đã duyệt
                        </span>
                    @elseif($khauTru->trang_thai == 'cho_duyet')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400 text-sm font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                            Chờ duyệt
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-sm font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                            Đã hủy
                        </span>
                    @endif
                </div>
            </div>

            {{-- Thời gian tạo --}}
            <div class="grid grid-cols-1 md:grid-cols-4 px-6 py-4">
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400 md:col-span-1">
                    Ngày tạo
                </div>
                <div class="md:col-span-3 text-gray-600 dark:text-gray-400 text-sm">
                    {{ $khauTru->created_at ? \Carbon\Carbon::parse($khauTru->created_at)->format('d/m/Y H:i') : 'N/A' }}
                </div>
            </div>

            {{-- Người tạo --}}
            <div class="grid grid-cols-1 md:grid-cols-4 px-6 py-4">
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400 md:col-span-1">
                    Người tạo
                </div>
                <div class="md:col-span-3 text-gray-600 dark:text-gray-400 text-sm">
                    @if($khauTru->nguoiTao)
                        {{ trim(($khauTru->nguoiTao->ho_so->ho ?? '').' '.($khauTru->nguoiTao->ho_so->ten ?? '')) }}
                    @else
                        <span class="text-gray-400 dark:text-gray-500">Không xác định</span>
                    @endif
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <a href="{{ route('admin.khau-tru-khac.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Quay lại danh sách
                </a>

                <div class="flex gap-2">
                    @if($khauTru->trang_thai == 'cho_duyet')
                        <form action="{{ route('admin.khau-tru-khac.approve', $khauTru->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" 
                                    onclick="return confirm('Xác nhận duyệt khoản khấu trừ này?')"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                                Duyệt
                            </button>
                        </form>
                        <form action="{{ route('admin.khau-tru-khac.reject', $khauTru->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                    onclick="return confirm('Xác nhận từ chối khoản khấu trừ này?')"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">
                                Từ chối
                            </button>
                        </form>
                    @endif

                    @if($khauTru->trang_thai == 'hieu_luc')
                        <form action="{{ route('admin.khau-tru-khac.undo', $khauTru->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                    onclick="return confirm('Hoàn tác khoản khấu trừ này?')"
                                    class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg text-sm font-medium transition">
                                Hoàn tác
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection