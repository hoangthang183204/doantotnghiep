@extends('layouts.admin')

@section('title', 'Loại thưởng')

@section('content')
<div class="min-h-screen p-6 bg-gray-50 dark:bg-slate-900">
<div class="max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Loại thưởng</h1>
            <p class="text-gray-500 dark:text-slate-400 mt-1">
                Danh mục các loại thưởng của công ty — tự thêm bao nhiêu loại tuỳ ý
                (chuyên cần, KPI, lương tháng 13, thưởng Tết, thâm niên, sáng kiến...).
            </p>
        </div>
        <a href="{{ route('admin.loai-thuong.create') }}"
           class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition">
            <i class="fa-solid fa-plus"></i> Thêm loại thưởng
        </a>
    </div>

    @include('layouts.partials.alerts')

    {{-- FILTER --}}
    <form method="GET" class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl p-4 flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-xs text-gray-500 dark:text-slate-400 mb-1">Tìm kiếm</label>
            <input type="text" name="tim" value="{{ request('tim') }}" placeholder="Tên hoặc mã loại thưởng"
                   class="w-64 rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 dark:text-slate-400 mb-1">Trạng thái</label>
            <select name="trang_thai" class="rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white text-sm">
                <option value="">Tất cả</option>
                <option value="1" @selected(request('trang_thai') === '1')>Đang sử dụng</option>
                <option value="0" @selected(request('trang_thai') === '0')>Ngừng sử dụng</option>
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
                        <th class="p-4 font-medium">Tên loại thưởng</th>
                        <th class="p-4 font-medium">Mã</th>
                        <th class="p-4 font-medium">Hình thức mặc định</th>
                        <th class="p-4 font-medium text-right">Giá trị mặc định</th>
                        <th class="p-4 font-medium text-center">Chịu thuế TNCN</th>
                        <th class="p-4 font-medium text-center">Đang dùng</th>
                        <th class="p-4 font-medium text-center">Trạng thái</th>
                        <th class="p-4 font-medium text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($loaiThuongs as $lt)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/40 transition">
                        <td class="p-4">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $lt->ten }}</p>
                            @if($lt->mo_ta)
                                <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">{{ \Illuminate\Support\Str::limit($lt->mo_ta, 80) }}</p>
                            @endif
                        </td>
                        <td class="p-4 font-mono text-xs text-gray-600 dark:text-slate-300">{{ $lt->ma }}</td>
                        <td class="p-4 text-gray-600 dark:text-slate-300">{{ $lt->hinh_thuc_text }}</td>
                        <td class="p-4 text-right font-semibold text-green-600 dark:text-green-400">{{ $lt->gia_tri_text }}</td>
                        <td class="p-4 text-center">
                            @if($lt->chiu_thue)
                                <span class="px-2 py-1 text-xs rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">Có</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-slate-300">Không</span>
                            @endif
                        </td>
                        <td class="p-4 text-center text-gray-600 dark:text-slate-300">{{ $lt->thuong_nhan_viens_count }}</td>
                        <td class="p-4 text-center">
                            @if($lt->trang_thai)
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">Đang sử dụng</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-slate-300">Ngừng</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex justify-end items-center gap-3">
                                <a href="{{ route('admin.loai-thuong.edit', $lt->id) }}"
                                   class="text-yellow-500 hover:text-yellow-700" title="Sửa">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.loai-thuong.destroy', $lt->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Xoá loại thưởng này? Nếu đang được sử dụng, hệ thống sẽ chuyển sang Ngừng sử dụng.')">
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
                            Chưa có loại thưởng nào. Hãy tạo loại thưởng đầu tiên của công ty.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100 dark:border-slate-700">
            {{ $loaiThuongs->links() }}
        </div>
    </div>

    <p class="text-xs text-gray-400">
        <i class="fa-solid fa-circle-info mr-1"></i>
        Loại thưởng chỉ là danh mục. Để nhân viên thực sự nhận thưởng, vào
        <a href="{{ route('admin.thuong.index') }}" class="text-blue-500 hover:underline">Thưởng nhân viên</a>
        và gán khoản thưởng theo hình thức <b>định kỳ hàng tháng</b> hoặc <b>áp dụng 1 lần</b>.
    </p>

</div>
</div>
@endsection
