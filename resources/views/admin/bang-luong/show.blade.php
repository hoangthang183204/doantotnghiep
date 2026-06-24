@extends('layouts.admin')

@section('title', 'Chi tiết bảng lương')

@section('content')
<div class="min-h-screen p-6 bg-gray-50 dark:bg-slate-900">
<div class="max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-wrap justify-between items-start gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Chi tiết bảng lương</h1>
            <p class="text-gray-500 dark:text-slate-400 mt-1">
                Mã: <span class="font-semibold">{{ $bangLuong->ma_bang_luong }}</span>
                — Kỳ lương tháng {{ $bangLuong->thang }}/{{ $bangLuong->nam }}
            </p>
        </div>
        <div class="flex gap-2">
{{-- Gửi tất cả phiếu lương --}}
    <form action="{{ route('admin.bang-luong.gui-tat-ca-email', $bangLuong->id) }}"
          method="POST"
          onsubmit="return confirm('Gửi phiếu lương cho tất cả nhân viên?')">
        @csrf

        <button type="submit"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm">
            <i class="fa-solid fa-envelope mr-1"></i>
            Gửi tất cả mail
        </button>
    </form>


            @if($bangLuong->la_nhap)
            <form action="{{ route('admin.bang-luong.chot', $bangLuong->id) }}" method="POST"
                  onsubmit="return confirm('Chốt bảng lương này? Sau khi chốt sẽ không sửa/xoá được.')">
                @csrf @method('PUT')
                <button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-sm">
                    <i class="fa-solid fa-lock mr-1"></i> Chốt lương
                </button>
            </form>
            @elseif($bangLuong->trang_thai === 'da_chot')
            <form action="{{ route('admin.bang-luong.thanh-toan', $bangLuong->id) }}" method="POST"
                  onsubmit="return confirm('Đánh dấu đã thanh toán?')">
                @csrf @method('PUT')
                <button class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg shadow-sm">
                    <i class="fa-solid fa-money-bill-wave mr-1"></i> Đã thanh toán
                </button>
            </form>
            @endif
            <a href="{{ route('admin.bang-luong.index') }}"
               class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-300 rounded-lg hover:opacity-80">
                Quay lại
            </a>
        </div>
    </div>

    @include('layouts.partials.alerts')

    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400">Tổng nhân viên</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $bangLuong->luongNhanViens->count() }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400">Tổng lương (gross)</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($bangLuong->luongNhanViens->sum('tong_luong')) }} đ</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400">Tổng thực nhận (net)</p>
            <p class="text-2xl font-bold text-blue-600 dark:text-sky-400">{{ number_format($bangLuong->luongNhanViens->sum('luong_thuc_nhan')) }} đ</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400">Trạng thái</p>
            <span class="inline-block mt-2 px-3 py-1 text-xs rounded-full font-medium {{ $bangLuong->trang_thai_badge }}">
                {{ $bangLuong->trang_thai_text }}
            </span>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm whitespace-nowrap">
                <thead class="bg-gray-50 dark:bg-slate-900 text-gray-500 dark:text-slate-400">
                    <tr>
                        <th class="p-3 text-left">STT</th>
                        <th class="p-3 text-left">Nhân viên</th>
                        <th class="p-3 text-center">Ngày công</th>
                        <th class="p-3 text-right">Lương theo công</th>
                        <th class="p-3 text-right">Phụ cấp</th>
                        <th class="p-3 text-right">Tăng ca</th>
                        <th class="p-3 text-right">Tổng lương</th>
                        <th class="p-3 text-right">Khấu trừ</th>
                        <th class="p-3 text-right">Thực nhận</th>
                        <th class="p-3 text-center">Phiếu lương</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @foreach($bangLuong->luongNhanViens as $index => $lnv)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/40 transition">
                        <td class="p-3 text-gray-600 dark:text-slate-300">{{ $index + 1 }}</td>
                        <td class="p-3 font-medium text-gray-900 dark:text-white">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-950 flex items-center justify-center text-blue-600 dark:text-sky-400 text-xs">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div>
                                    <p>{{ trim(($lnv->nguoiDung->ho_so->ho ?? '') . ' ' . ($lnv->nguoiDung->ho_so->ten ?? '')) ?: $lnv->nguoiDung->ten_dang_nhap }}</p>
                                    <p class="text-xs text-gray-400">{{ $lnv->nguoiDung->chuc_vu->ten ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-3 text-center text-gray-600 dark:text-slate-300">
                            {{ rtrim(rtrim(number_format($lnv->so_ngay_cong, 1), '0'), '.') }} / {{ (int) $lnv->so_ngay_cong_chuan }}
                        </td>
                        <td class="p-3 text-right text-gray-700 dark:text-slate-300">{{ number_format($lnv->luong_theo_cong) }}</td>
                        <td class="p-3 text-right text-green-600 dark:text-green-400">{{ number_format($lnv->tong_phu_cap) }}</td>
                        <td class="p-3 text-right text-indigo-600 dark:text-indigo-400">{{ number_format($lnv->tien_tang_ca) }}</td>
                        <td class="p-3 text-right font-semibold text-gray-900 dark:text-white">{{ number_format($lnv->tong_luong) }}</td>
                        <td class="p-3 text-right text-red-500">-{{ number_format($lnv->tong_khau_tru) }}</td>
                        <td class="p-3 text-right font-bold text-blue-600 dark:text-sky-400">{{ number_format($lnv->luong_thuc_nhan) }}</td>
                        <td class="p-3 text-center">
    <div class="flex justify-center gap-2">

        <a href="{{ route('admin.bang-luong.chi-tiet-nhan-vien', [$bangLuong->id, $lnv->id]) }}"
           class="inline-flex items-center gap-1 px-3 py-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg text-xs">
            <i class="fa-solid fa-receipt"></i>
            Xem
        </a>

        <form action="{{ route('admin.bang-luong.gui-email', $lnv->id) }}"
      method="POST">
    @csrf

    <button type="submit"
            class="inline-flex items-center gap-1 px-3 py-1.5 text-green-600 hover:bg-green-50 rounded-lg text-xs">
        <i class="fa-solid fa-envelope"></i>
        Mail
    </button>
</form>

    </div>
</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 dark:bg-slate-900 border-t border-gray-200 dark:border-slate-700 font-semibold">
                    <tr>
                        <td colspan="3" class="p-3 text-right text-gray-700 dark:text-slate-300">Tổng cộng</td>
                        <td class="p-3 text-right text-gray-700 dark:text-slate-300">{{ number_format($bangLuong->luongNhanViens->sum('luong_theo_cong')) }}</td>
                        <td class="p-3 text-right text-green-600 dark:text-green-400">{{ number_format($bangLuong->luongNhanViens->sum('tong_phu_cap')) }}</td>
                        <td class="p-3 text-right text-indigo-600 dark:text-indigo-400">{{ number_format($bangLuong->luongNhanViens->sum('tien_tang_ca')) }}</td>
                        <td class="p-3 text-right text-gray-900 dark:text-white">{{ number_format($bangLuong->luongNhanViens->sum('tong_luong')) }}</td>
                        <td class="p-3 text-right text-red-500">-{{ number_format($bangLuong->luongNhanViens->sum('tong_khau_tru')) }}</td>
                        <td class="p-3 text-right text-blue-600 dark:text-sky-400">{{ number_format($bangLuong->luongNhanViens->sum('luong_thuc_nhan')) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <p class="text-xs text-gray-400">
        Người xử lý: {{ $bangLuong->nguoiXuLy->ten_dang_nhap ?? 'N/A' }}
        @if($bangLuong->thoi_gian_phe_duyet)
            • Chốt lúc: {{ $bangLuong->thoi_gian_phe_duyet->format('d/m/Y H:i') }}
            ({{ $bangLuong->nguoiPheDuyet->ten_dang_nhap ?? 'N/A' }})
        @endif
    </p>

</div>
</div>
@endsection
