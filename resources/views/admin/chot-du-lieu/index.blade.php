@extends('layouts.admin')
@section('title', 'Chốt dữ liệu tháng')
@section('content')
<div class="hr-page space-y-6 text-gray-900 dark:text-gray-100">
    <div>
        <h1 class="text-2xl font-bold">Chốt dữ liệu tháng</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 max-w-3xl">
            HR khóa sổ chấm công, tăng ca, nghỉ phép và thưởng/kỷ luật. Sau khi chốt, kế toán mới được bấm Tính lương. HR không sửa bảng lương.
        </p>
    </div>
    @if(session('success'))<div class="p-3 rounded-lg bg-green-50 text-green-700">{{ session('success') }}</div>@endif
    <form method="POST" action="{{ route('admin.chot-du-lieu.store') }}" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 flex flex-wrap gap-3 items-end">
        @csrf
        <label class="text-sm">Tháng
            <input type="number" name="thang" min="1" max="12" value="{{ now()->month }}" class="block rounded-lg border-gray-300" required>
        </label>
        <label class="text-sm">Năm
            <input type="number" name="nam" min="2000" max="2100" value="{{ $nam }}" class="block rounded-lg border-gray-300" required>
        </label>
        <label class="text-sm flex-1 min-w-[16rem]">Ghi chú
            <input name="ghi_chu" placeholder="Ví dụ: đã đối soát tăng ca" class="block w-full rounded-lg border-gray-300">
        </label>
        <button class="px-4 py-2 rounded-lg bg-blue-600 text-white">Chốt và báo kế toán</button>
    </form>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700/60"><tr>
                <th class="px-3 py-2 text-left">Kỳ</th><th class="text-left">Trạng thái</th><th class="text-left">Người chốt</th><th class="text-left">Lúc</th><th class="text-left">Ghi chú</th>
            </tr></thead>
            <tbody>
                @forelse($ds as $dong)
                    <tr class="border-t">
                        <td class="px-3 py-2">Tháng {{ $dong->thang }}/{{ $dong->nam }}</td>
                        <td>Đã chốt số liệu</td>
                        <td>{{ trim(($dong->nguoiChot->hoSo->ho ?? '').' '.($dong->nguoiChot->hoSo->ten ?? '')) ?: ($dong->nguoiChot->ten_dang_nhap ?? '—') }}</td>
                        <td>{{ $dong->thoi_gian_chot?->format('d/m/Y H:i') }}</td>
                        <td>{{ $dong->ghi_chu }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-3 py-6 text-center text-gray-500">Năm {{ $nam }} chưa có kỳ nào được chốt.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
