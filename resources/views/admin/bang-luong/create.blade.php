@extends('layouts.admin')

@section('title', 'Tính lương')

@section('content')
<div class="min-h-screen p-6 bg-gray-50 dark:bg-slate-900">
<div class="max-w-5xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tính lương nhân viên</h1>
        <p class="text-gray-500 dark:text-slate-400 mt-1">Chọn kỳ lương và nhân viên cần tính</p>
    </div>

    @include('layouts.partials.alerts')

    {{-- CHỌN KỲ LƯƠNG --}}
    <form method="GET" action="{{ route('admin.bang-luong.create') }}"
          class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl p-4 flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-sm text-gray-600 dark:text-slate-400 mb-1">Tháng</label>
            <select name="thang" class="border border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white rounded-lg px-3 py-2">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $thangTinh == $m ? 'selected' : '' }}>Tháng {{ $m }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label class="block text-sm text-gray-600 dark:text-slate-400 mb-1">Năm</label>
            <select name="nam" class="border border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white rounded-lg px-3 py-2">
                @for($y = now()->year; $y >= now()->year - 3; $y--)
                    <option value="{{ $y }}" {{ $namTinh == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white rounded-lg">
            <i class="fa-solid fa-rotate mr-1"></i> Tải danh sách
        </button>
        @if($exists)
            <span class="ml-auto text-sm px-3 py-2 rounded-lg bg-yellow-50 text-yellow-800 border border-yellow-200">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                Đã có bảng lương tháng {{ $thangTinh }}/{{ $namTinh }} (chỉ các NV chưa tính mới hiển thị)
            </span>
        @endif
    </form>

    {{-- FORM TÍNH LƯƠNG --}}
    <form action="{{ route('admin.bang-luong.tinh') }}" method="POST" id="tinhLuongForm">
        @csrf
        <input type="hidden" name="thang" value="{{ $thangTinh }}">
        <input type="hidden" name="nam" value="{{ $namTinh }}">

        <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl p-6">
            <label class="flex items-center gap-2 mb-4">
                <input type="checkbox" id="checkAll" class="w-4 h-4 rounded border-gray-300 text-blue-600">
                <span class="font-medium text-gray-700 dark:text-gray-300">Chọn tất cả</span>
            </label>

            <div class="grid grid-cols-1 gap-3 max-h-[28rem] overflow-y-auto">
                @forelse($nhanViens as $nv)
                @php $hd = $nv->hop_dongs->sortByDesc('ngay_bat_dau')->first(); @endphp
                <label class="flex items-center gap-3 p-3 border border-gray-200 dark:border-slate-700 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700/40 transition cursor-pointer">
                    <input type="checkbox" name="nhan_vien_ids[]" value="{{ $nv->id }}"
                           class="nhan-vien-checkbox w-4 h-4 rounded border-gray-300 text-blue-600">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900 dark:text-white">
                            {{ trim(($nv->ho_so->ho ?? '') . ' ' . ($nv->ho_so->ten ?? '')) ?: $nv->ten_dang_nhap }}
                        </p>
                        <p class="text-sm text-gray-500">{{ $nv->chuc_vu->ten ?? 'Chưa có chức vụ' }} • {{ $nv->email }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">HĐ: {{ $hd->so_hop_dong ?? 'Chưa có' }}</p>
                        <p class="text-sm font-semibold text-blue-600">{{ number_format($hd->luong_co_ban ?? 0) }} đ</p>
                    </div>
                </label>
                @empty
                <div class="text-center py-8 text-gray-500">
                    Không có nhân viên nào chưa được tính lương cho kỳ này
                </div>
                @endforelse
            </div>

            <div class="flex justify-end gap-3 pt-4 mt-4 border-t border-gray-100 dark:border-slate-700">
                <a href="{{ route('admin.bang-luong.index') }}" class="px-4 py-2 border border-gray-200 dark:border-slate-700 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700/40 dark:text-slate-300">
                    Hủy
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    <i class="fa-solid fa-calculator mr-1"></i> Tính lương
                </button>
            </div>
        </div>
    </form>

</div>
</div>

<script>
    document.getElementById('checkAll')?.addEventListener('change', function (e) {
        document.querySelectorAll('.nhan-vien-checkbox').forEach(cb => cb.checked = e.target.checked);
    });
</script>
@endsection
