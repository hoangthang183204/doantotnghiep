@extends('layouts.admin')

@section('title', 'Khấu trừ khác')

@section('content')
<div class="min-h-screen p-6 bg-gray-50 dark:bg-slate-900">
<div class="max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Khấu trừ khác</h1>
            <p class="text-gray-500 dark:text-slate-400 mt-1">
                Tạm ứng, phạt, bồi thường... áp dụng khi tính lương tháng {{ $thang }}/{{ $nam }}
            </p>
        </div>
        <a href="{{ route('admin.khau-tru-khac.create', ['thang' => $thang, 'nam' => $nam]) }}"
           class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition">
            <i class="fa-solid fa-plus"></i> Thêm khoản khấu trừ
        </a>
    </div>

    @include('layouts.partials.alerts')

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
            <label class="block text-xs text-gray-500 dark:text-slate-400 mb-1">Loại</label>
            <select name="loai" class="rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white text-sm">
                <option value="">Tất cả</option>
                @foreach(\App\Models\KhauTruKhac::$loaiLabels as $key => $label)
                    <option value="{{ $key }}" @selected(request('loai') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button class="px-4 py-2 bg-gray-800 dark:bg-slate-700 text-white rounded-lg text-sm">
            <i class="fa-solid fa-filter mr-1"></i> Lọc
        </button>
        <div class="ml-auto text-right">
            <p class="text-xs text-gray-500 dark:text-slate-400">Tổng khấu trừ (hiệu lực)</p>
            <p class="text-lg font-bold text-red-600 dark:text-red-400">-{{ number_format($tongTien) }} đ</p>
        </div>
    </form>

    {{-- TABLE --}}
    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-slate-900 text-gray-500 dark:text-slate-400 text-left">
                    <tr>
                        <th class="p-4 font-medium">Nhân viên</th>
                        <th class="p-4 font-medium">Loại</th>
                        <th class="p-4 font-medium text-right">Số tiền</th>
                        <th class="p-4 font-medium">Lý do</th>
                        <th class="p-4 font-medium text-center">Trạng thái</th>
                        <th class="p-4 font-medium text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($khoanKhauTrus as $kt)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/40 transition">
                        <td class="p-4 font-medium text-gray-900 dark:text-white">
                            {{ trim(($kt->nguoiDung->ho_so->ho ?? '') . ' ' . ($kt->nguoiDung->ho_so->ten ?? '')) ?: ($kt->nguoiDung->ten_dang_nhap ?? 'N/A') }}
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 text-xs rounded-full font-medium {{ $kt->loai_badge }}">{{ $kt->loai_text }}</span>
                        </td>
                        <td class="p-4 text-right font-semibold text-red-600 dark:text-red-400">-{{ number_format($kt->so_tien) }} đ</td>
                        <td class="p-4 text-gray-600 dark:text-slate-300">{{ $kt->ly_do ?: '—' }}</td>
                        <td class="p-4 text-center">
                            @if($kt->trang_thai == 'cho_duyet')
                                <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-700">
                                    Chờ duyệt
                                </span>

                            @elseif($kt->trang_thai == 'hieu_luc')
                                <span class="px-2 py-1 rounded bg-green-100 text-green-700">
                                    Đã duyệt
                                </span>

                            @else
                                <span class="px-2 py-1 rounded bg-red-100 text-red-700">
                                    Đã từ chối
                                </span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex justify-end items-center gap-3">

                                {{-- Chi tiết --}}
                                <a href="{{ route('admin.khau-tru-khac.show', $kt->id) }}"
                                class="text-blue-500 hover:text-blue-700"
                                title="Chi tiết">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                @if($kt->trang_thai == 'huy')

                                    {{-- Duyệt --}}
                                    <form action="{{ route('admin.khau-tru-khac.approve', $kt->id) }}"
                                        method="POST"
                                        class="inline">
                                        @csrf

                                        <button type="submit"
                                                class="text-green-600 hover:text-green-800"
                                                title="Duyệt">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                    </form>

                                    {{-- Xóa --}}
                                    <form action="{{ route('admin.khau-tru-khac.destroy', $kt->id) }}"
                                        method="POST"
                                        class="inline"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa yêu cầu này?')">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="text-red-600 hover:text-red-800"
                                            title="Xóa">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                @else

                                    {{-- Hoàn tác --}}
                                    <form action="{{ route('admin.khau-tru-khac.undo', $kt->id) }}"
                                        method="POST"
                                        class="inline">
                                        @csrf

                                        <button type="submit"
                                                class="text-yellow-500 hover:text-yellow-700"
                                                title="Hoàn tác">
                                            <i class="fa-solid fa-rotate-left"></i>
                                        </button>
                                    </form>

                                @endif

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-gray-500 dark:text-slate-400">
                            <i class="fa-regular fa-folder-open text-2xl mb-2"></i><br>
                            Chưa có khoản khấu trừ nào trong tháng {{ $thang }}/{{ $nam }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100 dark:border-slate-700">
            {{ $khoanKhauTrus->links() }}
        </div>
    </div>

    <p class="text-xs text-gray-400">
        <i class="fa-solid fa-circle-info mr-1"></i>
        Khoản khấu trừ ở trạng thái <b>Hiệu lực</b> sẽ tự động cộng vào tổng khấu trừ khi tính (hoặc tính lại) bảng lương tháng tương ứng.
    </p>

</div>
</div>
@endsection
