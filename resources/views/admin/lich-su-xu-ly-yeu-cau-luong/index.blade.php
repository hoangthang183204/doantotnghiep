@extends('layouts.admin')

@section('title', 'Lịch sử xử lý yêu cầu lương')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Lịch sử xử lý yêu cầu lương
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Theo dõi toàn bộ thay đổi yêu cầu và phiếu lương
            </p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Bộ lọc tìm kiếm
            </h2>
        </div>
        
        <div class="p-6">
            <form method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Nhân viên --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1.5">
                            Nhân viên
                        </label>
                        <select name="nguoi_dung_id"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition text-sm">
                            <option value="">Tất cả nhân viên</option>
                            @foreach($nhanViens as $nv)
                                <option value="{{ $nv->id }}" {{ request('nguoi_dung_id') == $nv->id ? 'selected' : '' }}>
                                    {{ $nv->ho_so->ho ?? '' }} {{ $nv->ho_so->ten ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Từ ngày --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1.5">
                            Từ ngày
                        </label>
                        <input type="date" name="tu_ngay" value="{{ request('tu_ngay') }}"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition text-sm">
                    </div>

                    {{-- Đến ngày --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1.5">
                            Đến ngày
                        </label>
                        <input type="date" name="den_ngay" value="{{ request('den_ngay') }}"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition text-sm">
                    </div>

                    {{-- Hành động --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1.5">
                            Hành động
                        </label>
                        <select name="hanh_dong"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition text-sm">
                            <option value="">Tất cả</option>
                            <option value="tao" {{ request('hanh_dong') == 'tao' ? 'selected' : '' }}>Tạo</option>
                            <option value="duyet" {{ request('hanh_dong') == 'duyet' ? 'selected' : '' }}>Duyệt</option>
                            <option value="cap_nhat" {{ request('hanh_dong') == 'cap_nhat' ? 'selected' : '' }}>Cập nhật</option>
                            <option value="tu_choi" {{ request('hanh_dong') == 'tu_choi' ? 'selected' : '' }}>Từ chối</option>
                        </select>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex flex-wrap gap-2 mt-4">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                        Tìm kiếm
                    </button>
                    
                    <a href="{{ url()->current() }}"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition">
                        Reset
                    </a>

                    @if(request()->anyFilled(['nguoi_dung_id', 'tu_ngay', 'den_ngay', 'hanh_dong']))
                        <span class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                            Đang lọc kết quả
                        </span>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h3 class="font-medium text-gray-700 dark:text-gray-300">
                Danh sách lịch sử xử lý
            </h3>
            <span class="text-sm text-gray-500 dark:text-gray-400">
                Hiển thị {{ $lichSus->count() }} / {{ $lichSus->total() }} kết quả
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Nhân viên
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Hành động
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Người xử lý
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Thời gian
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Ghi chú
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($lichSus as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            {{-- Nhân viên --}}
                            <td class="px-4 py-3">
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        {{ $item->yeuCau->nguoiDung->ho_so->ho ?? '' }} 
                                        {{ $item->yeuCau->nguoiDung->ho_so->ten ?? '' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        #{{ $item->yeuCau->nguoiDung->id }}
                                    </div>
                                </div>
                            </td>

                            {{-- Hành động --}}
                            <td class="px-4 py-3">
                                @php
                                    $actionColors = [
                                        'tao' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                        'duyet' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                        'cap_nhat' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        'tu_choi' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                    ];
                                    $actionLabels = [
                                        'tao' => 'Tạo mới',
                                        'duyet' => 'Duyệt',
                                        'cap_nhat' => 'Cập nhật',
                                        'tu_choi' => 'Từ chối',
                                    ];
                                @endphp
                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium {{ $actionColors[$item->hanh_dong] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $actionLabels[$item->hanh_dong] ?? strtoupper($item->hanh_dong) }}
                                </span>
                            </td>

                            {{-- Người xử lý --}}
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                {{ $item->nguoiThucHien->ho_so->ho ?? '' }} 
                                {{ $item->nguoiThucHien->ho_so->ten ?? '' }}
                            </td>

                            {{-- Thời gian --}}
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                {{ $item->thoi_gian->format('d/m/Y H:i') }}
                            </td>

                            {{-- Ghi chú --}}
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-xs">
                                {{ $item->ghi_chu ?? 'Không có ghi chú' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                Không có dữ liệu lịch sử xử lý
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Hiển thị <span class="font-medium text-gray-700 dark:text-gray-300">{{ $lichSus->count() }}</span> 
                    / <span class="font-medium text-gray-700 dark:text-gray-300">{{ $lichSus->total() }}</span> bản ghi
                </div>
                <div>
                    {{ $lichSus->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection