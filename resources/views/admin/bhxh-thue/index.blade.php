@extends('layouts.admin')
@section('title', 'BHXH và thuế TNCN')
@section('content')
<div class="hr-page space-y-6 text-gray-900 dark:text-gray-100">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Cấu hình BHXH / BHYT / BHTN / thuế TNCN</h1>
        <p class="text-sm text-gray-500 mt-1">Tỷ lệ NLĐ, tỷ lệ doanh nghiệp, giảm trừ gia cảnh và biểu thuế lũy tiến đổi theo từng năm. Bảng lương lấy đúng cấu hình của năm tính lương.</p>
    </div>
    @if(session('success'))<div class="p-3 rounded-lg bg-green-50 text-green-700">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="p-3 rounded-lg bg-red-50 text-red-700">{{ session('error') }}</div>@endif

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-left">
                <tr>
                    <th class="px-4 py-3">Năm</th>
                    <th class="px-4 py-3">NLĐ</th>
                    <th class="px-4 py-3">Doanh nghiệp</th>
                    <th class="px-4 py-3">Giảm trừ bản thân</th>
                    <th class="px-4 py-3">Mỗi NPT</th>
                    <th class="px-4 py-3">Trần đóng</th>
                    <th class="px-4 py-3">Số bậc thuế</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($cauHinhs as $row)
                <tr class="border-t border-gray-100 dark:border-gray-700">
                    <td class="px-4 py-3 font-semibold">{{ $row->nam }}</td>
                    <td class="px-4 py-3">{{ rtrim(rtrim(number_format($row->tyLeNld()*100, 2), '0'), '.') }}%</td>
                    <td class="px-4 py-3">{{ rtrim(rtrim(number_format($row->tyLeDn()*100, 2), '0'), '.') }}%</td>
                    <td class="px-4 py-3">{{ number_format($row->giam_tru_ban_than) }}</td>
                    <td class="px-4 py-3">{{ number_format($row->giam_tru_npt) }}</td>
                    <td class="px-4 py-3">{{ number_format($row->tran_dong_bhxh) }}</td>
                    <td class="px-4 py-3">{{ count($row->bacThue()) }}</td>
                    <td class="px-4 py-3"><a class="text-blue-600" href="{{ route('admin.bhxh-thue.index', ['nam' => $row->nam]) }}">Sửa</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @php $f = $sua; @endphp
    <form method="POST" action="{{ route('admin.bhxh-thue.update') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 grid md:grid-cols-3 gap-4">
        @csrf
        <h2 class="md:col-span-3 font-semibold text-gray-900 dark:text-white">{{ $f ? 'Sửa năm '.$f->nam : 'Thêm năm mới' }}</h2>
        <label class="text-sm">Năm<input name="nam" type="number" value="{{ old('nam', $f->nam ?? now()->year) }}" class="mt-1 w-full rounded-lg border-gray-300" required></label>
        @foreach([
            'ty_le_bhxh_nld' => 'BHXH NLĐ (%)',
            'ty_le_bhyt_nld' => 'BHYT NLĐ (%)',
            'ty_le_bhtn_nld' => 'BHTN NLĐ (%)',
            'ty_le_bhxh_dn' => 'BHXH DN (%)',
            'ty_le_bhyt_dn' => 'BHYT DN (%)',
            'ty_le_bhtn_dn' => 'BHTN DN (%)',
        ] as $name => $label)
        <label class="text-sm">{{ $label }}
            <input name="{{ $name }}" type="number" step="0.01" value="{{ old($name, $f ? $f->$name * 100 : '') }}" class="mt-1 w-full rounded-lg border-gray-300" required>
        </label>
        @endforeach
        <label class="text-sm">Giảm trừ bản thân (đ)<input name="giam_tru_ban_than" type="number" value="{{ old('giam_tru_ban_than', $f->giam_tru_ban_than ?? 11000000) }}" class="mt-1 w-full rounded-lg border-gray-300" required></label>
        <label class="text-sm">Giảm trừ mỗi NPT (đ)<input name="giam_tru_npt" type="number" value="{{ old('giam_tru_npt', $f->giam_tru_npt ?? 4400000) }}" class="mt-1 w-full rounded-lg border-gray-300" required></label>
        <label class="text-sm">Trần mức đóng BHXH (đ)<input name="tran_dong_bhxh" type="number" value="{{ old('tran_dong_bhxh', $f->tran_dong_bhxh ?? 46800000) }}" class="mt-1 w-full rounded-lg border-gray-300" required></label>
        <label class="md:col-span-3 text-sm">Biểu thuế lũy tiến (JSON, trần từng bậc theo tháng, den = null là bậc cuối)
            <textarea name="bac_thue" rows="6" class="mt-1 w-full rounded-lg border-gray-300 font-mono text-xs" required>{{ old('bac_thue', $f ? json_encode($f->bacThue(), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) : '') }}</textarea>
        </label>
        <label class="md:col-span-3 text-sm">Ghi chú<input name="ghi_chu" value="{{ old('ghi_chu', $f->ghi_chu ?? '') }}" class="mt-1 w-full rounded-lg border-gray-300"></label>
        <div class="md:col-span-3"><button class="px-4 py-2 rounded-lg bg-blue-600 text-white">Lưu cấu hình</button></div>
    </form>
</div>
@endsection
