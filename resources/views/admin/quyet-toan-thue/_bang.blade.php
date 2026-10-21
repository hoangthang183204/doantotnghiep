<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-x-auto">
    <div class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $tieuDe }}</div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-left">
            <tr>
                <th class="px-3 py-2">Nhân viên</th>
                <th class="px-3 py-2">MST</th>
                <th class="px-3 py-2 text-right">Thu nhập chịu thuế</th>
                <th class="px-3 py-2 text-right">BH NLĐ</th>
                <th class="px-3 py-2 text-right">Giảm trừ</th>
                <th class="px-3 py-2 text-right">Tính thuế</th>
                <th class="px-3 py-2 text-right">Đã khấu trừ</th>
                @if($coChenhLech)<th class="px-3 py-2 text-right">Phải nộp cả năm</th><th class="px-3 py-2 text-right">Chênh lệch</th>@endif
            </tr>
        </thead>
        <tbody>
            @forelse($dong as $row)
            <tr class="border-t border-gray-100 dark:border-gray-700">
                <td class="px-3 py-2">{{ $row['ho_ten'] }}</td>
                <td class="px-3 py-2">{{ $row['mst'] ?: '—' }}</td>
                <td class="px-3 py-2 text-right">{{ number_format($row['thu_nhap_chiu_thue']) }}</td>
                <td class="px-3 py-2 text-right">{{ number_format($row['bao_hiem']) }}</td>
                <td class="px-3 py-2 text-right">{{ number_format($row['giam_tru']) }}</td>
                <td class="px-3 py-2 text-right">{{ number_format($row['thu_nhap_tinh_thue']) }}</td>
                <td class="px-3 py-2 text-right">{{ number_format($row['thue_da_khau_tru']) }}</td>
                @if($coChenhLech)
                    <td class="px-3 py-2 text-right">{{ number_format($row['thue_phai_nop']) }}</td>
                    <td class="px-3 py-2 text-right {{ $row['chenh_lech'] > 0 ? 'text-red-600' : 'text-emerald-600' }}">{{ number_format($row['chenh_lech']) }}</td>
                @endif
            </tr>
            @empty
            <tr><td colspan="9" class="px-3 py-8 text-center text-gray-500">Chưa có phiếu lương trong kỳ này.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
