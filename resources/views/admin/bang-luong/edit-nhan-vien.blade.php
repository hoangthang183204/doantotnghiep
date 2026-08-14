@extends('layouts.admin')

@section('title', 'Sửa lương nhân viên')

@php
    $luongNgay = $luong->so_ngay_cong_chuan > 0 ? ($luong->luong_co_ban / $luong->so_ngay_cong_chuan) : 0;
    $luongGio = $luongNgay / 8;
    $ngayHuongLuong = (float) $luong->so_ngay_cong + (float) $luong->ngay_nghi_phep;
    $tienTangCa = (float) $luong->tien_tang_ca;
    $chiTietTangCa = $luong->chiTietTangCa();
    $isLocked = !$bangLuong->la_nhap;
    $tongPhuCapHienTai = (float) ($luong->phuCapLuongs->sum('so_tien') ?? 0);
@endphp

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-slate-900 p-6">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Sửa lương nhân viên</h1>
                <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">
                    {{ $luong->nguoiDung->ho_ten ?? $luong->nguoiDung->ten_dang_nhap }} • Tháng {{ $bangLuong->thang }}/{{ $bangLuong->nam }}
                </p>
            </div>
            <a href="{{ route('admin.bang-luong.chi-tiet-nhan-vien', [$bangLuong->id, $luong->id]) }}"
               class="px-4 py-2 border border-gray-200 dark:border-slate-700 rounded-lg text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800">
                Quay lại
            </a>
        </div>

        @include('layouts.partials.alerts')

        @if($isLocked)
            <div class="rounded-xl border border-amber-200 bg-amber-50 dark:border-amber-700 dark:bg-amber-900/20 p-4 text-sm text-amber-800 dark:text-amber-200">
                Bảng lương đã được chốt, nên phần sửa lương bị khóa. Bạn chỉ có thể xem thông tin và kiểm tra kết quả cuối cùng.
            </div>
        @endif

        <form action="{{ route('admin.bang-luong.update-nhan-vien', [$bangLuong->id, $luong->id]) }}" method="POST"
              class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm p-6 space-y-6 {{ $isLocked ? 'opacity-70 pointer-events-none' : '' }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Ngày công</label>
                    <input type="number" step="0.1" min="0" max="31" name="so_ngay_cong" value="{{ old('so_ngay_cong', $luong->so_ngay_cong) }}" @disabled($isLocked)
                           class="form-edit-input w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-gray-900 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Giờ tăng ca</label>
                    <input type="number" step="0.1" min="0" name="gio_tang_ca" value="{{ old('gio_tang_ca', $luong->gio_tang_ca) }}" @disabled($isLocked)
                           class="form-edit-input w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-gray-900 dark:text-white">
                    <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">
@forelse($chiTietTangCa as $tc)
    {{ $tc['nhan'] }}:
    {{ rtrim(rtrim(number_format($tc['gio'], 2), '0'), '.') }}h
    @if (!$loop->last)
        •
    @endif
@empty
    Không có tăng ca trong kỳ.
@endforelse
                        <br>Khi sửa tổng số giờ, hệ thống giữ nguyên tỷ lệ giữa các loại ngày và tính lại theo hệ số 150% / 200% / 400%.
                    </p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Tổng thưởng</label>
                    <input type="number" step="1000" min="0" name="tong_thuong" value="{{ old('tong_thuong', $luong->tong_thuong) }}" @disabled($isLocked)
                           class="form-edit-input w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-gray-900 dark:text-white">
                    <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">
                        Tổng các khoản thưởng đã áp dụng cho kỳ lương này
                        ({{ $luong->thuongLuongs->count() }} khoản:
                        @forelse($luong->thuongLuongs as $tt){{ $tt->ten }} {{ number_format($tt->so_tien) }}đ@if(!$loop->last), @endif @empty không có @endforelse).
                        Sửa ở đây chỉ đổi tổng tiền của kỳ này; muốn đổi tận gốc hãy sửa trong mục
                        <a href="{{ route('admin.thuong.index') }}" class="text-blue-500 hover:underline">Thưởng nhân viên</a>
                        rồi tính lại lương.
                    </p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Tổng phụ cấp</label>
                    <input type="number" step="1000" min="0" name="tong_phu_cap" value="{{ old('tong_phu_cap', $luong->tong_phu_cap) }}" @disabled($isLocked)
                           class="form-edit-input w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-gray-900 dark:text-white">
                    <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">
                        Đây là tổng của các khoản phụ cấp hiện có. Nếu mục này cao, có nghĩa là nhiều khoản phụ cấp đã được gộp lại.
                    </p>
                </div>

                @if($luong->phuCapLuongs->isNotEmpty())
                    <div class="md:col-span-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/40 p-4">
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-slate-200 mb-3">Chi tiết phụ cấp</h3>
                        <div class="space-y-2">
                            @foreach($luong->phuCapLuongs as $pc)
                                <div class="flex items-center justify-between gap-3 rounded-lg bg-white dark:bg-slate-800 px-3 py-2 border border-gray-200 dark:border-slate-700">
                                    <span class="text-sm text-gray-700 dark:text-slate-300">
                                        {{ $pc->phuCap->ten ?? 'Phụ cấp' }}
                                    </span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ number_format((float) $pc->so_tien, 0, ',', '.') }} ₫
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Ghi chú</label>
                    <textarea name="ghi_chu" rows="3" @disabled($isLocked)
                              class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-gray-900 dark:text-white">{{ old('ghi_chu', $luong->ghi_chu) }}</textarea>
                </div>
            </div>

            <div class="rounded-xl border border-blue-200 bg-blue-50 dark:border-blue-700 dark:bg-blue-900/20 p-4">
                <div class="flex items-center justify-between gap-3 mb-3">
                    <h3 class="text-sm font-semibold text-blue-700 dark:text-blue-200">Kết quả sau khi tính lại</h3>
                    <span class="text-xs text-blue-600 dark:text-blue-300">Cập nhật theo số liệu đang nhập</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="rounded-lg bg-white/80 dark:bg-slate-900/40 p-3 border border-blue-100 dark:border-blue-800">
                        <p class="text-xs text-gray-500 dark:text-slate-400">Tổng lương</p>
                        <p id="previewTongLuong" class="mt-1 text-xl font-bold text-gray-900 dark:text-white">{{ number_format($luong->tong_luong) }} đ</p>
                    </div>
                    <div class="rounded-lg bg-white/80 dark:bg-slate-900/40 p-3 border border-blue-100 dark:border-blue-800">
                        <p class="text-xs text-gray-500 dark:text-slate-400">Khấu trừ</p>
                        <p id="previewKhauTru" class="mt-1 text-xl font-bold text-red-600 dark:text-red-400">{{ number_format($luong->tong_khau_tru) }} đ</p>
                    </div>
                    <div class="rounded-lg bg-white/80 dark:bg-slate-900/40 p-3 border border-blue-100 dark:border-blue-800">
                        <p class="text-xs text-gray-500 dark:text-slate-400">Lương thực nhận</p>
                        <p id="previewThucNhan" class="mt-1 text-xl font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($luong->luong_thuc_nhan) }} đ</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.bang-luong.chi-tiet-nhan-vien', [$bangLuong->id, $luong->id]) }}"
                   class="px-4 py-2 border border-gray-200 dark:border-slate-700 rounded-lg text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800">
                    Hủy
                </a>
                <button type="submit" @disabled($isLocked)
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    Tính lại lương
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
