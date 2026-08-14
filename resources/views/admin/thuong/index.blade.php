@extends('layouts.admin')

@section('title', 'Thưởng nhân viên')

@section('content')
<div class="min-h-screen p-6 bg-gray-50 dark:bg-slate-900">
<div class="max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Thưởng nhân viên</h1>
            <p class="text-gray-500 dark:text-slate-400 mt-1">
                Các khoản thưởng áp dụng cho kỳ lương tháng {{ $thang }}/{{ $nam }} —
                gồm thưởng <b>định kỳ hàng tháng</b> và thưởng <b>áp dụng 1 lần</b>.
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.loai-thuong.index') }}"
               class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-300 rounded-lg hover:opacity-80 transition">
                <i class="fa-solid fa-tags"></i> Loại thưởng
            </a>
            <a href="{{ route('admin.thuong.create', ['thang' => $thang, 'nam' => $nam]) }}"
               class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition">
                <i class="fa-solid fa-plus"></i> Thêm khoản thưởng
            </a>
        </div>
    </div>

    @include('layouts.partials.alerts')

    {{-- TỔNG QUAN --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400"><i class="fa-solid fa-gift text-green-500 mr-1"></i> Tổng thưởng kỳ này</p>
            <p class="text-xl font-bold text-green-600 dark:text-green-400 mt-1">+{{ number_format($tongTien) }} đ</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400"><i class="fa-solid fa-repeat text-indigo-500 mr-1"></i> Khoản định kỳ</p>
            <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $tongDinhKy }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400"><i class="fa-solid fa-star text-amber-500 mr-1"></i> Khoản 1 lần</p>
            <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $tongMotLan }}</p>
        </div>
    </div>

    {{-- FILTER --}}
    <form method="GET" class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl p-4 flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-xs text-gray-500 dark:text-slate-400 mb-1">Tháng</label>
            <select name="thang" class="rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white text-sm">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" @selected($m == $thang)>Tháng {{ $m }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 dark:text-slate-400 mb-1">Năm</label>
            <input type="number" name="nam" value="{{ $nam }}" min="2000" max="2100"
                   class="w-28 rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 dark:text-slate-400 mb-1">Hình thức</label>
            <select name="hinh_thuc" class="rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white text-sm">
                <option value="">Tất cả</option>
                @foreach(\App\Models\ThuongNhanVien::$hinhThucLabels as $key => $label)
                    <option value="{{ $key }}" @selected(request('hinh_thuc') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 dark:text-slate-400 mb-1">Loại thưởng</label>
            <select name="loai_thuong_id" class="rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white text-sm">
                <option value="">Tất cả</option>
                @foreach($loaiThuongs as $lt)
                    <option value="{{ $lt->id }}" @selected(request('loai_thuong_id') == $lt->id)>{{ $lt->ten }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 dark:text-slate-400 mb-1">Trạng thái</label>
            <select name="trang_thai" class="rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white text-sm">
                <option value="">Tất cả</option>
                @foreach(\App\Models\ThuongNhanVien::$trangThaiLabels as $key => $label)
                    <option value="{{ $key }}" @selected(request('trang_thai') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button class="px-4 py-2 bg-gray-800 dark:bg-slate-700 text-white rounded-lg text-sm">
            <i class="fa-solid fa-filter mr-1"></i> Lọc
        </button>
    </form>

    {{-- TABLE --}}
    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-slate-900 text-gray-500 dark:text-slate-400 text-left">
                    <tr>
                        <th class="p-4 font-medium">Nhân viên</th>
                        <th class="p-4 font-medium">Loại thưởng</th>
                        <th class="p-4 font-medium">Hình thức</th>
                        <th class="p-4 font-medium">Phạm vi áp dụng</th>
                        <th class="p-4 font-medium text-right">Giá trị</th>
                        <th class="p-4 font-medium text-right">Thành tiền kỳ này</th>
                        <th class="p-4 font-medium text-center">Trạng thái</th>
                        <th class="p-4 font-medium text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($khoanThuongs as $tt)
                    @php $soTien = $tt->tinhSoTien((float) ($tt->nguoiDung->luong_co_ban ?? 0)); @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/40 transition">
                        <td class="p-4 font-medium text-gray-900 dark:text-white">
                            {{ trim(($tt->nguoiDung->ho_so->ho ?? '') . ' ' . ($tt->nguoiDung->ho_so->ten ?? '')) ?: ($tt->nguoiDung->ten_dang_nhap ?? 'N/A') }}
                        </td>
                        <td class="p-4 text-gray-600 dark:text-slate-300">
                            {{ $tt->loaiThuong->ten ?? '—' }}
                            @if($tt->ly_do)
                                <span class="block text-xs text-gray-400">{{ $tt->ly_do }}</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 text-xs rounded-full font-medium {{ $tt->hinh_thuc_badge }}">{{ $tt->hinh_thuc_text }}</span>
                        </td>
                        <td class="p-4 text-gray-600 dark:text-slate-300">{{ $tt->pham_vi_text }}</td>
                        <td class="p-4 text-right text-gray-600 dark:text-slate-300">{{ $tt->gia_tri_text }}</td>
                        <td class="p-4 text-right font-semibold text-green-600 dark:text-green-400">+{{ number_format($soTien) }} đ</td>
                        <td class="p-4 text-center">
                            <span class="px-2.5 py-1 text-xs rounded-full font-medium {{ $tt->trang_thai_badge }}">{{ $tt->trang_thai_text }}</span>
                        </td>
                        <td class="p-4">
                            <div class="flex justify-end items-center gap-3">
                                <a href="{{ route('admin.thuong.show', $tt->id) }}" class="text-blue-500 hover:text-blue-700" title="Chi tiết">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.thuong.edit', $tt->id) }}" class="text-yellow-500 hover:text-yellow-700" title="Sửa">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                @if($tt->trang_thai === 'hieu_luc')
                                    <form action="{{ route('admin.thuong.tam-dung', $tt->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="text-orange-500 hover:text-orange-700" title="Tạm dừng">
                                            <i class="fa-solid fa-pause"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.thuong.kich-hoat', $tt->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="text-green-600 hover:text-green-800" title="Kích hoạt">
                                            <i class="fa-solid fa-play"></i>
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.thuong.destroy', $tt->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Xoá khoản thưởng này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:text-red-800" title="Xoá">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-10 text-center text-gray-500 dark:text-slate-400">
                            <i class="fa-regular fa-folder-open text-2xl mb-2"></i><br>
                            Chưa có khoản thưởng nào áp dụng cho kỳ lương {{ $thang }}/{{ $nam }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100 dark:border-slate-700">
            {{ $khoanThuongs->links() }}
        </div>
    </div>

    <p class="text-xs text-gray-400">
        <i class="fa-solid fa-circle-info mr-1"></i>
        Khoản thưởng ở trạng thái <b>Hiệu lực</b> sẽ tự động cộng vào tổng lương khi tính (hoặc tính lại) bảng lương
        tháng tương ứng. Thưởng <b>định kỳ</b> lặp lại mỗi tháng cho tới khi hết hạn hoặc bị tạm dừng;
        thưởng <b>1 lần</b> chỉ áp dụng đúng kỳ lương đã chọn.
    </p>

</div>
</div>
@endsection
