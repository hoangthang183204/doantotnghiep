@extends('layouts.admin')

@section('title', 'Thống kê hợp đồng')

@section('content')
<div class="space-y-6 bg-gray-50 dark:bg-[#0f172a] min-h-screen p-6">

    <div class="bg-white dark:bg-gray-800/80 backdrop-blur border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
        <div class="flex justify-between items-center">
            <div><h1 class="text-2xl font-bold text-gray-900 dark:text-white">📊 Thống kê hợp đồng lao động</h1><p class="text-gray-500 mt-1">Báo cáo chi tiết về tình hình hợp đồng</p></div>
            <a href="{{ route('admin.hop-dong.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-xl">← Quay lại</a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800/80 border rounded-xl p-6">
        <form method="GET" action="{{ route('admin.hop-dong.thong-ke') }}" class="flex flex-wrap gap-4 items-end">
            <div><label class="block text-sm mb-1">Từ ngày</label><input type="date" name="tu_ngay" class="px-4 py-2 border rounded-lg" value="{{ $tuNgay ?? '' }}"></div>
            <div><label class="block text-sm mb-1">Đến ngày</label><input type="date" name="den_ngay" class="px-4 py-2 border rounded-lg" value="{{ $denNgay ?? '' }}"></div>
            <div><button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg">Tìm kiếm</button></div>
            <div><a href="{{ route('admin.hop-dong.thong-ke') }}" class="px-5 py-2 bg-gray-500 text-white rounded-lg">Làm mới</a></div>
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 text-center"><div class="text-2xl font-bold text-blue-600">{{ number_format($tongHopDong) }}</div><div class="text-sm text-gray-500">Tổng hợp đồng</div></div>
        <div class="bg-white rounded-xl shadow-sm p-4 text-center"><div class="text-2xl font-bold text-green-600">{{ number_format($hopDongHieuLuc) }}</div><div class="text-sm text-gray-500">Đang hiệu lực</div></div>
        <div class="bg-white rounded-xl shadow-sm p-4 text-center"><div class="text-2xl font-bold text-yellow-600">{{ number_format($hopDongChuaHieuLuc) }}</div><div class="text-sm text-gray-500">Chưa hiệu lực</div></div>
        <div class="bg-white rounded-xl shadow-sm p-4 text-center"><div class="text-2xl font-bold text-orange-600">{{ number_format($hopDongTaoMoi) }}</div><div class="text-sm text-gray-500">Tạo mới</div></div>
        <div class="bg-white rounded-xl shadow-sm p-4 text-center"><div class="text-2xl font-bold text-red-600">{{ number_format($hopDongHetHan) }}</div><div class="text-sm text-gray-500">Hết hạn</div></div>
        <div class="bg-white rounded-xl shadow-sm p-4 text-center"><div class="text-2xl font-bold text-gray-600">{{ number_format($hopDongHuyBo) }}</div><div class="text-sm text-gray-500">Đã hủy</div></div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-3">Hợp đồng sắp hết hạn (30 ngày tới)</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100"><tr><th class="px-4 py-2">Số hợp đồng</th><th>Nhân viên</th><th>Ngày kết thúc</th><th>Số ngày còn lại</th></tr></thead>
                <tbody>
                    @forelse($hopDongSapHetHan as $hd)
                    <tr class="border-t"><td class="px-4 py-2">{{ $hd->so_hop_dong }}</td><td>{{ $hd->hoSoNguoiDung ? ($hd->hoSoNguoiDung->ho . ' ' . $hd->hoSoNguoiDung->ten) : 'N/A' }}</td><td class="px-4 py-2">{{ $hd->ngay_ket_thuc->format('d/m/Y') }}</td><td><span class="px-2 py-1 rounded-full text-xs {{ $hd->ngay_ket_thuc->diffInDays(now()) <= 7 ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700' }}">{{ $hd->ngay_ket_thuc->diffInDays(now()) }} ngày</span></td></tr>
                    @empty<tr><td colspan="4" class="text-center py-6 text-gray-500">Không có hợp đồng nào sắp hết hạn</td></tr>@endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection