@extends('layouts.admin')

@section('title', 'Danh sách bảng lương')

@section('content')
<div class="min-h-screen p-6 bg-gray-50 dark:bg-slate-900">
<div class="max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Bảng lương theo tháng</h1>
            <p class="text-gray-500 dark:text-slate-400 mt-1">
                Tổng hợp & chốt lương hàng tháng cho nhân viên
            </p>
        </div>
        <a href="{{ route('admin.bang-luong.create') }}"
           class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition">
            <i class="fa-solid fa-calculator"></i> Tính lương tháng
        </a>
    </div>

    @include('layouts.partials.alerts')

    {{-- TABLE CARD --}}
    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-slate-900 border-b border-gray-200 dark:border-slate-700">
                    <tr class="text-left text-gray-500 dark:text-slate-400">
                        <th class="p-4 font-medium">Mã bảng lương</th>
                        <th class="p-4 font-medium">Kỳ lương</th>
                        <th class="p-4 font-medium text-center">Số NV</th>
                        <th class="p-4 font-medium text-right">Tổng thực nhận</th>
                        <th class="p-4 font-medium">Trạng thái</th>
                        <th class="p-4 font-medium">Người xử lý</th>
                        <th class="p-4 font-medium text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($bangLuongs as $bl)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/40 transition">
                        <td class="p-4 font-semibold text-gray-900 dark:text-white">{{ $bl->ma_bang_luong }}</td>
                        <td class="p-4 text-gray-600 dark:text-slate-300">Tháng {{ $bl->thang }}/{{ $bl->nam }}</td>
                        <td class="p-4 text-center text-gray-600 dark:text-slate-300">
                            <span class="inline-flex items-center gap-1">
                                <i class="fa-solid fa-users text-blue-500"></i> {{ $bl->luong_nhan_viens_count }}
                            </span>
                        </td>
                        <td class="p-4 text-right font-semibold text-green-600 dark:text-green-400">
                            {{ number_format($bl->luong_nhan_viens_sum_luong_thuc_nhan ?? 0) }} đ
                        </td>
                        <td class="p-4">
                            <span class="px-3 py-1 text-xs rounded-full font-medium {{ $bl->trang_thai_badge }}">
                                {{ $bl->trang_thai_text }}
                            </span>
                        </td>
                        <td class="p-4 text-gray-600 dark:text-slate-300">{{ $bl->nguoiXuLy->ten_dang_nhap ?? 'N/A' }}</td>
                        <td class="p-4">
                            <div class="flex justify-end items-center gap-1">
                                <a href="{{ route('admin.bang-luong.show', $bl->id) }}"
                                   class="flex items-center gap-1 px-3 py-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg text-sm">
                                    <i class="fa-regular fa-eye"></i> <span>Xem</span>
                                </a>

                                <a href="{{ route('admin.bang-luong.export', $bl->id) }}"
   class="p-2 text-green-600 hover:bg-green-50 dark:hover:bg-slate-700 rounded-lg"
   title="Xuất Excel">
    <i class="fa-solid fa-file-excel"></i>
</a>

                                @if($bl->la_nhap)
                                    <form action="{{ route('admin.bang-luong.chot', $bl->id) }}" method="POST"
                                          onsubmit="return confirm('Chốt bảng lương này? Sau khi chốt sẽ không sửa/xoá được.')">
                                        @csrf @method('PUT')
                                        <button class="p-2 text-green-600 hover:bg-green-50 dark:hover:bg-slate-700 rounded-lg" title="Chốt lương">
                                            <i class="fa-solid fa-lock"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.bang-luong.destroy', $bl->id) }}" method="POST"
                                          onsubmit="return confirm('Bạn có chắc muốn xoá?')">
                                        @csrf @method('DELETE')
                                        <button class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-slate-700 rounded-lg" title="Xoá">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                @elseif($bl->trang_thai === 'da_chot')
                                    <form action="{{ route('admin.bang-luong.thanh-toan', $bl->id) }}" method="POST"
                                          onsubmit="return confirm('Đánh dấu đã thanh toán bảng lương này?')">
                                        @csrf @method('PUT')
                                        <button class="p-2 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-slate-700 rounded-lg" title="Đánh dấu đã trả">
                                            <i class="fa-solid fa-money-bill-wave"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-10 text-center text-gray-500 dark:text-slate-400">
                            <i class="fa-regular fa-folder-open text-2xl mb-2"></i><br>
                            Chưa có bảng lương nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100 dark:border-slate-700">
            {{ $bangLuongs->links() }}
        </div>
    </div>

</div>
</div>
@endsection
