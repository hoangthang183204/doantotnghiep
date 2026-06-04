@extends('layouts.admin')

@section('title', 'Chi tiết bảng lương')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Chi tiết bảng lương</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Mã: {{ $bangLuong->ma_bang_luong }} - Tháng {{ $bangLuong->thang }}/{{ $bangLuong->nam }}</p>
        </div>
        <div class="flex gap-2">
            @if($bangLuong->trang_thai == 'dang_tao')
            <form action="{{ route('admin.bang-luong.duyet', $bangLuong->id) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                    Duyệt bảng lương
                </button>
            </form>
            @endif
            <a href="{{ route('admin.bang-luong.index') }}" class="border px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                Quay lại
            </a>
        </div>
    </div>
    
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr class="border-b dark:border-gray-700">
                        <th class="text-left py-3 px-4 text-sm font-medium">STT</th>
                        <th class="text-left py-3 px-4 text-sm font-medium">Họ tên</th>
                        <th class="text-left py-3 px-4 text-sm font-medium">Lương cơ bản</th>
                        <th class="text-left py-3 px-4 text-sm font-medium">Phụ cấp</th>
                        <th class="text-left py-3 px-4 text-sm font-medium">Khấu trừ</th>
                        <th class="text-left py-3 px-4 text-sm font-medium">Thực lĩnh</th>
                        <th class="text-left py-3 px-4 text-sm font-medium">Ngày công</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bangLuong->luongNhanViens as $index => $lnv)
                    <tr class="border-b dark:border-gray-700">
                        <td class="py-3 px-4">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 font-medium">{{ $lnv->nguoiDung->ho_so->ho ?? '' }} {{ $lnv->nguoiDung->ho_so->ten ?? '' }}</td>
                        <td class="py-3 px-4">{{ number_format($lnv->luong_co_ban) }} đ</td>
                        <td class="py-3 px-4 text-green-600">{{ number_format($lnv->tong_phu_cap) }} đ</td>
                        <td class="py-3 px-4 text-red-600">{{ number_format($lnv->tong_khau_tru) }} đ</td>
                        <td class="py-3 px-4 font-semibold text-blue-600">{{ number_format($lnv->luong_thuc_nhan) }} đ</td>
                        <td class="py-3 px-4">{{ $lnv->so_ngay_cong }} / 26</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 dark:bg-gray-800">
                    <tr class="border-t dark:border-gray-700 font-semibold">
                        <td colspan="5" class="py-3 px-4 text-right">Tổng cộng:</td>
                        <td class="py-3 px-4 text-blue-600">{{ number_format($bangLuong->luongNhanViens->sum('luong_thuc_nhan')) }} đ</td>
                        <td class="py-3 px-4"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection