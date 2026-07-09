@extends('layouts.admin')

@section('title', 'Lịch sử tăng lương')

@section('content')
<div class="min-h-screen p-6 bg-gray-50 dark:bg-slate-900">
<div class="max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Lịch sử tăng lương</h1>
            <p class="text-gray-500 dark:text-slate-400 mt-1">
                Toàn bộ lịch sử tăng / giảm / điều chỉnh lương của nhân viên theo hợp đồng.
            </p>
        </div>
    </div>

    @include('layouts.partials.alerts')

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400">Tổng số lần</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $thongKe['tong'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400">Tăng lương</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $thongKe['tang'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400">Giảm lương</p>
            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $thongKe['giam'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400">Điều chỉnh</p>
            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $thongKe['dieuChinh'] }}</p>
        </div>
    </div>

    {{-- FILTER --}}
    <form method="GET" class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl p-4 flex flex-wrap items-end gap-3">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs text-gray-500 dark:text-slate-400 mb-1">Tìm nhân viên</label>
            <input type="text" name="tu_khoa" value="{{ request('tu_khoa') }}" placeholder="Tên / tài khoản / email"
                   class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 dark:text-slate-400 mb-1">Loại</label>
            <select name="loai" class="rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white text-sm">
                <option value="">Tất cả</option>
                <option value="tang_luong" @selected(request('loai') == 'tang_luong')>Tăng lương</option>
                <option value="giam_luong" @selected(request('loai') == 'giam_luong')>Giảm lương</option>
                <option value="dieu_chinh" @selected(request('loai') == 'dieu_chinh')>Điều chỉnh</option>
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 dark:text-slate-400 mb-1">Trạng thái</label>
            <select name="trang_thai" class="rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white text-sm">
                <option value="">Tất cả</option>
                <option value="cho_duyet" @selected(request('trang_thai') == 'cho_duyet')>Chờ duyệt</option>
                <option value="da_duyet" @selected(request('trang_thai') == 'da_duyet')>Đã duyệt</option>
                <option value="tu_choi" @selected(request('trang_thai') == 'tu_choi')>Từ chối</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-gray-800 dark:bg-slate-700 text-white rounded-lg text-sm">
                <i class="fa-solid fa-magnifying-glass mr-1"></i> Lọc
            </button>
            <a href="{{ route('admin.tang-luong.index') }}"
               class="px-4 py-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-200 rounded-lg text-sm">
                Xóa lọc
            </a>
        </div>
    </form>

    {{-- TABLE --}}
    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm whitespace-nowrap">
                <thead class="bg-gray-50 dark:bg-slate-900 text-gray-500 dark:text-slate-400 text-left">
                    <tr>
                        <th class="p-3">Nhân viên</th>
                        <th class="p-3">Hợp đồng</th>
                        <th class="p-3">Ngày áp dụng</th>
                        <th class="p-3 text-right">Lương cũ</th>
                        <th class="p-3 text-right">Lương mới</th>
                        <th class="p-3 text-right">Chênh lệch</th>
                        <th class="p-3 text-center">Loại</th>
                        <th class="p-3 text-center">Trạng thái</th>
                        <th class="p-3">Lý do</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($lichSus as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/40 transition">
                        <td class="p-3 font-medium text-gray-900 dark:text-white">
                            {{ $item->nguoiDung?->ho_ten ?? '—' }}
                        </td>
                        <td class="p-3 text-gray-600 dark:text-slate-300">
                            {{ $item->hopDong?->so_hop_dong ?? '—' }}
                        </td>
                        <td class="p-3 text-gray-600 dark:text-slate-300">
                            {{ $item->ngay_ap_dung ? $item->ngay_ap_dung->format('d/m/Y') : '—' }}
                        </td>
                        <td class="p-3 text-right text-gray-600 dark:text-slate-300">{{ number_format($item->luong_cu) }}</td>
                        <td class="p-3 text-right font-semibold text-gray-900 dark:text-white">{{ number_format($item->luong_moi) }}</td>
                        <td class="p-3 text-right">
                            <span class="{{ $item->chenhLech >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} font-medium">
                                {{ $item->chenhLech > 0 ? '+' : '' }}{{ number_format($item->chenhLech) }}
                                <span class="text-xs text-gray-400">({{ $item->phanTramTang > 0 ? '+' : '' }}{{ $item->phanTramTang }}%)</span>
                            </span>
                        </td>
                        <td class="p-3 text-center">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $item->loai == 'tang_luong' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300' :
                                   ($item->loai == 'giam_luong' ? 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300' :
                                   'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300') }}">
                                {{ $item->loai_text }}
                            </span>
                        </td>
                        <td class="p-3 text-center">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $item->trang_thai == 'da_duyet' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300' :
                                   ($item->trang_thai == 'tu_choi' ? 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300' :
                                   'bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-slate-300') }}">
                                {{ $item->trang_thai_text }}
                            </span>
                        </td>
                        <td class="p-3 text-gray-600 dark:text-slate-300 max-w-[220px] truncate" title="{{ $item->ly_do }}">
                            {{ $item->ly_do ?? '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-10 text-center text-gray-500 dark:text-slate-400">
                            <i class="fa-regular fa-folder-open text-3xl mb-2"></i><br>
                            Chưa có lịch sử tăng / giảm lương nào.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($lichSus->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-slate-700">
                {{ $lichSus->links() }}
            </div>
        @endif
    </div>

</div>
</div>
@endsection
