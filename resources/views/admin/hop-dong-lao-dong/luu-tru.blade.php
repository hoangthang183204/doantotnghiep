@extends('layouts.admin')

@section('title', 'Lưu trữ hợp đồng')

@section('content')
<div class="space-y-6 bg-gray-50 dark:bg-[#0f172a] min-h-screen p-6">

    <div class="bg-white dark:bg-gray-800/80 backdrop-blur border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
        <div class="flex justify-between items-center">
            <div><h1 class="text-2xl font-bold text-gray-900 dark:text-white">📦 Lưu trữ hợp đồng</h1><p class="text-gray-500 mt-1">Danh sách hợp đồng đã hủy bỏ hoặc đã tái ký</p></div>
            <a href="{{ route('admin.hop-dong.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-xl">← Danh sách hợp đồng</a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800/80 border rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr class="text-left text-sm text-gray-600">
                        <th class="px-4 py-3">Số hợp đồng</th><th class="px-4 py-3">Nhân viên</th><th class="px-4 py-3">Loại HĐ</th><th class="px-4 py-3">Ngày bắt đầu</th><th class="px-4 py-3">Ngày kết thúc</th><th class="px-4 py-3">Trạng thái</th><th class="px-4 py-3">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hopDongsArchive as $hd)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold">{{ $hd->so_hop_dong }}</td>
                        <td class="px-4 py-3">{{ $hd->hoSoNguoiDung ? ($hd->hoSoNguoiDung->ho . ' ' . $hd->hoSoNguoiDung->ten) : 'N/A' }}</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs bg-blue-100">{{ $hd->loai_hop_dong == 'xac_dinh_thoi_han' ? 'Xác định' : 'Không xác định' }}</span></td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($hd->ngay_bat_dau)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $hd->ngay_ket_thuc ? \Carbon\Carbon::parse($hd->ngay_ket_thuc)->format('d/m/Y') : '---' }}</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-700">{{ $hd->trang_thai_hop_dong == 'huy_bo' ? 'Hủy bỏ' : ($hd->trang_thai_ky == 'tu_choi_ky' ? 'Từ chối ký' : 'Đã tái ký') }}</span></td>
                        <td class="px-4 py-3"><a href="{{ route('admin.hop-dong.show', $hd->id) }}" class="text-blue-600 hover:underline">Xem</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-10 text-gray-500">Không có hợp đồng nào trong lưu trữ</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($hopDongsArchive->hasPages())
        <div class="px-4 py-3 border-t">{{ $hopDongsArchive->links() }}</div>
        @endif
    </div>
</div>
@endsection