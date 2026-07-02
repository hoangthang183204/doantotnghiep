{{-- resources/views/employee/don-nghi/index.blade.php --}}
@extends('layouts.employee')

@section('title', 'Đơn xin nghỉ phép')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Đơn xin nghỉ phép</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Quản lý các đơn xin nghỉ phép của bạn</p>
        </div>
        <div class="mt-4 md:mt-0 flex items-center space-x-3">
            <a href="{{ route('employee.don-nghi.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tạo đơn nghỉ phép
            </a>
        </div>
    </div>

    {{-- Thông báo --}}
    @if(session('success'))
        <div class="mb-4 p-4 text-sm text-green-800 dark:text-green-100 rounded-lg bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-800/50 flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-green-800 dark:text-green-100 hover:opacity-70">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 text-sm text-red-800 dark:text-red-100 rounded-lg bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800/50 flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-red-800 dark:text-red-100 hover:opacity-70">&times;</button>
        </div>
    @endif

    {{-- Thống kê nhanh --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tổng đơn</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $thongKe['tong'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Chờ duyệt</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $thongKe['cho_duyet'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Đã duyệt</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $thongKe['da_duyet'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Từ chối</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $thongKe['tu_choi'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Đã hủy</p>
                    <p class="text-2xl font-bold text-gray-600 dark:text-gray-400">{{ $thongKe['huy_bo'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Bộ lọc --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <form method="GET" action="{{ route('employee.don-nghi.index') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label for="trang_thai" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Trạng thái</label>
                <select name="trang_thai" id="trang_thai" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tất cả</option>
                    <option value="cho_duyet" {{ request('trang_thai') == 'cho_duyet' ? 'selected' : '' }}>⏳ Chờ duyệt</option>
                    <option value="da_duyet" {{ request('trang_thai') == 'da_duyet' ? 'selected' : '' }}>✅ Đã duyệt</option>
                    <option value="tu_choi" {{ request('trang_thai') == 'tu_choi' ? 'selected' : '' }}>❌ Từ chối</option>
                    <option value="huy_bo" {{ request('trang_thai') == 'huy_bo' ? 'selected' : '' }}>🚫 Đã hủy</option>
                </select>
            </div>
            <div>
                <label for="tu_ngay" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Từ ngày</label>
                <input type="date" name="tu_ngay" id="tu_ngay" value="{{ request('tu_ngay') }}" 
                       class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="den_ngay" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Đến ngày</label>
                <input type="date" name="den_ngay" id="den_ngay" value="{{ request('den_ngay') }}"
                       class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Lọc
                </button>
                <a href="{{ route('employee.don-nghi.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition-colors ml-2">
                    Xóa lọc
                </a>
            </div>
        </form>
    </div>

    {{-- Danh sách đơn nghỉ --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mã đơn</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày tạo</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Từ ngày</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Đến ngày</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lý do</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($danhSachDon as $don)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                #{{ $don->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $don->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($don->ngay_bat_dau)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($don->ngay_ket_thuc)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                {{ $don->ly_do ?: 'Không có lý do' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($don->trang_thai == 'cho_duyet')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">⏳ Chờ duyệt</span>
                                @elseif($don->trang_thai == 'da_duyet')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">✅ Đã duyệt</span>
                                @elseif($don->trang_thai == 'tu_choi')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">❌ Từ chối</span>
                                @elseif($don->trang_thai == 'huy_bo')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400">🚫 Đã hủy</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('employee.don-nghi.show', $don->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @if($don->trang_thai == 'cho_duyet')
                                        <a href="{{ route('employee.don-nghi.edit', $don->id) }}" 
                                           class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('employee.don-nghi.huy', $don->id) }}" class="inline" onsubmit="return confirm('Bạn có chắc muốn hủy đơn này?')">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">Không có dữ liệu đơn xin nghỉ</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Không tìm thấy bản ghi nào phù hợp với điều kiện tìm kiếm</p>
                                    <a href="{{ route('employee.don-nghi.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Tạo đơn nghỉ phép
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($danhSachDon) && method_exists($danhSachDon, 'links'))
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $danhSachDon->links() }}
            </div>
        @endif
    </div>

    {{-- Số dư nghỉ phép và Cảnh báo sắp hết phép --}}
    <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        
        {{-- Khối thông báo cảnh báo động --}}
        @if($soDu['canh_bao_sap_het'])
            <div class="mb-4 p-4 text-sm text-amber-800 dark:text-amber-200 rounded-lg bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-amber-600 dark:text-amber-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span class="font-medium">Cảnh báo:</span>&nbsp;Số dư nghỉ phép của bạn sắp hết (Còn lại {{ $soDu['so_du_con_lai'] }} ngày). Vui lòng cân nhắc kế hoạch nghỉ phép hợp lý.
            </div>
        @endif

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">Chi tiết số dư nghỉ phép năm {{ now()->year }}</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm mt-2">
                        <div class="bg-gray-50 dark:bg-gray-700/40 p-2.5 rounded-lg border border-gray-100 dark:border-gray-700">
                            <p class="text-xs text-gray-400">Phép năm mới</p>
                            <p class="font-semibold text-gray-700 dark:text-gray-300">{{ $soDu['phep_nam_moi'] }} ngày</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/40 p-2.5 rounded-lg border border-gray-100 dark:border-gray-700">
                            <p class="text-xs text-gray-400">Phép cũ chuyển sang</p>
                            <p class="font-semibold text-gray-700 dark:text-gray-300">{{ $soDu['phep_cu_chuyen_sang'] }} ngày</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/40 p-2.5 rounded-lg border border-gray-100 dark:border-gray-700">
                            <p class="text-xs text-gray-400">Đã sử dụng</p>
                            <p class="font-semibold text-red-600 dark:text-red-400">{{ $soDu['so_ngay_da_nghi'] }} ngày</p>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-2.5 rounded-lg border border-blue-100 dark:border-blue-900/30">
                            <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">Số dư khả dụng</p>
                            <p class="font-bold text-blue-700 dark:text-blue-300 text-base">{{ $soDu['so_du_con_lai'] }} ngày</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-right flex-shrink-0">
                <span class="text-xs font-medium text-gray-400 bg-gray-100 dark:bg-gray-700 px-2.5 py-1 rounded-full">
                    Cập nhật: {{ now()->format('d/m/Y H:i') }}
                </span>
            </div>
        </div>

        {{-- Thanh tiến trình (Progress Bar) thay đổi màu động --}}
        <div class="mt-5">
            @php
                $soDuConLai = $soDu['so_du_con_lai'] ?? 0;
                $soNgayPhepNam = $soDu['so_ngay_phep_nam'] ?? 12;
                $phanTram = $soNgayPhepNam > 0 ? ($soDuConLai / $soNgayPhepNam) * 100 : 0;
                
                // Chuyển màu thanh trạng thái dựa vào số ngày phép còn lại
                $colorClass = 'bg-green-500';
                if ($soDuConLai <= 1) {
                    $colorClass = 'bg-red-500';
                } elseif ($soDuConLai <= 3) {
                    $colorClass = 'bg-amber-500';
                }
            @endphp
            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-3 overflow-hidden border border-gray-200/50 dark:border-gray-600">
                <div class="{{ $colorClass }} h-3 rounded-full transition-all duration-500 shadow-inner" 
                     style="width: {{ min(100, $phanTram) }}%"></div>
            </div>
        </div>
    </div>
</div>
@endsection