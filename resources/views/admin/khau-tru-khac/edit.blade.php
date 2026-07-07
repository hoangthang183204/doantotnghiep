@extends('layouts.admin')

@section('title', 'Sửa khấu trừ khác')

@section('content')
<div class="min-h-screen p-6 bg-gray-50 dark:bg-slate-900">
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Sửa khoản khấu trừ khác</h1>
        <a href="{{ route('admin.khau-tru-khac.index', ['thang' => $khauTru->thang, 'nam' => $khauTru->nam]) }}"
           class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-300 rounded-lg hover:opacity-80">
            <i class="fa-solid fa-arrow-left mr-1"></i> Quay lại
        </a>
    </div>

    @include('layouts.partials.alerts')

    <form action="{{ route('admin.khau-tru-khac.update', $khauTru->id) }}" method="POST"
          class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm p-6 space-y-5">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Nhân viên <span class="text-red-500">*</span></label>
            <select name="nguoi_dung_id" required
                    class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white">
                @foreach($nhanViens as $nv)
                    <option value="{{ $nv->id }}" @selected(old('nguoi_dung_id', $khauTru->nguoi_dung_id) == $nv->id)>
                        {{ trim(($nv->ho_so->ho ?? '') . ' ' . ($nv->ho_so->ten ?? '')) ?: $nv->ten_dang_nhap }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Tháng áp dụng <span class="text-red-500">*</span></label>
                <select name="thang" class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" @selected(old('thang', $khauTru->thang) == $m)>Tháng {{ $m }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Năm <span class="text-red-500">*</span></label>
                <input type="number" name="nam" value="{{ old('nam', $khauTru->nam) }}" min="2000" max="2100"
                       class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Loại khấu trừ <span class="text-red-500">*</span></label>
                <select name="loai" class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white">
                    @foreach(\App\Models\KhauTruKhac::$loaiLabels as $key => $label)
                        <option value="{{ $key }}" @selected(old('loai', $khauTru->loai) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Số tiền (đ) <span class="text-red-500">*</span></label>
                <input type="number" name="so_tien" value="{{ old('so_tien', (int) $khauTru->so_tien) }}" min="0" step="1000" required
                       class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Lý do</label>
            <input type="text" name="ly_do" value="{{ old('ly_do', $khauTru->ly_do) }}" maxlength="255"
                   class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Trạng thái</label>
            <select name="trang_thai" class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white">
                <option value="hieu_luc" @selected(old('trang_thai', $khauTru->trang_thai) === 'hieu_luc')>Hiệu lực</option>
                <option value="huy" @selected(old('trang_thai', $khauTru->trang_thai) === 'huy')>Đã huỷ (không áp dụng)</option>
            </select>
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <a href="{{ route('admin.khau-tru-khac.index', ['thang' => $khauTru->thang, 'nam' => $khauTru->nam]) }}"
               class="px-4 py-2 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 rounded-lg">Huỷ</a>
            <button class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm">
                <i class="fa-solid fa-floppy-disk mr-1"></i> Cập nhật
            </button>
        </div>
    </form>

</div>
</div>
@endsection
