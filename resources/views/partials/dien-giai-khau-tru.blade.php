{{--
    Khối diễn giải khấu trừ: TRỪ GÌ – TRỪ TRÊN CĂN CỨ NÀO – TRỪ NHƯ THẾ NÀO.
    Dùng chung cho phiếu lương admin và phiếu lương nhân viên.

    Biến truyền vào:
      $luong : App\Models\LuongNhanVien
--}}
@php
    $dg  = $luong->dienGiai();
    $npt = $luong->nguoiPhuThuocTrongKy();
    $tienTe = fn($v) => number_format((float) $v) . ' đ';
    $phanTram = fn($v) => rtrim(rtrim(number_format($v * 100, 1), '0'), '.') . '%';
@endphp

<div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-700">
        <h2 class="font-semibold text-gray-900 dark:text-white">
            <i class="fa-solid fa-scissors mr-2 text-red-500"></i>Diễn giải các khoản khấu trừ
        </h2>
        <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">
            Tất cả các khoản dưới đây được trừ vào <b>Tổng lương (gross)</b> {{ $tienTe($dg['tong_luong']) }}
            để ra <b>Lương thực nhận (net)</b>.
        </p>
    </div>

    <div class="divide-y divide-gray-100 dark:divide-slate-700">

        {{-- ============ A. BẢO HIỂM BẮT BUỘC ============ --}}
        <div class="px-5 py-4">
            <div class="flex justify-between items-start gap-4 mb-3">
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Ⓐ Bảo hiểm bắt buộc (phần người lao động đóng)</p>
                    <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">
                        Căn cứ trừ: <b>Lương cơ bản theo hợp đồng = {{ $tienTe($dg['luong_co_ban']) }}</b>
                        (không tính trên phụ cấp và tăng ca)
                    </p>
                </div>
                <p class="text-lg font-bold text-red-500 whitespace-nowrap">-{{ $tienTe($dg['tong_bao_hiem']) }}</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-slate-900/50 text-gray-500 dark:text-slate-400">
                        <tr>
                            <th class="px-3 py-2 text-left font-medium">Khoản</th>
                            <th class="px-3 py-2 text-right font-medium">Căn cứ</th>
                            <th class="px-3 py-2 text-center font-medium">Tỷ lệ</th>
                            <th class="px-3 py-2 text-right font-medium">Số tiền trừ</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 dark:text-slate-300">
                        @foreach ([
                            ['Bảo hiểm xã hội (BHXH)', $dg['ty_le_bhxh'], $dg['bhxh']],
                            ['Bảo hiểm y tế (BHYT)', $dg['ty_le_bhyt'], $dg['bhyt']],
                            ['Bảo hiểm thất nghiệp (BHTN)', $dg['ty_le_bhtn'], $dg['bhtn']],
                        ] as [$ten, $tyLe, $soTien])
                        <tr class="border-t border-gray-100 dark:border-slate-700">
                            <td class="px-3 py-2">{{ $ten }}</td>
                            <td class="px-3 py-2 text-right font-mono text-xs">{{ $tienTe($dg['luong_co_ban']) }}</td>
                            <td class="px-3 py-2 text-center">{{ $phanTram($tyLe) }}</td>
                            <td class="px-3 py-2 text-right font-medium text-red-500">-{{ $tienTe($soTien) }}</td>
                        </tr>
                        @endforeach
                        <tr class="border-t border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-900/50 font-semibold">
                            <td class="px-3 py-2" colspan="2">Cộng bảo hiểm bắt buộc</td>
                            <td class="px-3 py-2 text-center">{{ $phanTram($dg['ty_le_bhxh'] + $dg['ty_le_bhyt'] + $dg['ty_le_bhtn']) }}</td>
                            <td class="px-3 py-2 text-right text-red-600 dark:text-red-400">-{{ $tienTe($dg['tong_bao_hiem']) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ============ B. GIẢM TRỪ GIA CẢNH ============ --}}
        <div class="px-5 py-4">
            <p class="font-medium text-gray-900 dark:text-white">Ⓑ Giảm trừ gia cảnh</p>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">
                Đây <b>không phải khoản bị trừ vào lương</b> — mà là phần thu nhập được
                <b>miễn thuế</b>, trừ ra trước khi tính thuế TNCN.
                (NQ 110/2025/UBTVQH15, áp dụng từ kỳ tính thuế 2026)
            </p>

            <div class="overflow-x-auto mt-3">
                <table class="w-full text-sm">
                    <tbody class="text-gray-700 dark:text-slate-300">
                        <tr class="border-t border-gray-100 dark:border-slate-700">
                            <td class="px-3 py-2">Giảm trừ bản thân người nộp thuế</td>
                            <td class="px-3 py-2 text-right font-mono text-xs text-gray-400">1 người × {{ $tienTe($dg['giam_tru_ban_than']) }}</td>
                            <td class="px-3 py-2 text-right font-medium text-emerald-600 dark:text-emerald-400">{{ $tienTe($dg['giam_tru_ban_than']) }}</td>
                        </tr>
                        <tr class="border-t border-gray-100 dark:border-slate-700">
                            <td class="px-3 py-2">
                                Giảm trừ người phụ thuộc
                                <span class="text-xs text-gray-400">({{ $dg['so_nguoi_phu_thuoc'] }} người được tính trong kỳ)</span>
                            </td>
                            <td class="px-3 py-2 text-right font-mono text-xs text-gray-400">
                                {{ $dg['so_nguoi_phu_thuoc'] }} người × {{ $tienTe($dg['muc_giam_tru_moi_npt']) }}
                            </td>
                            <td class="px-3 py-2 text-right font-medium text-emerald-600 dark:text-emerald-400">{{ $tienTe($dg['giam_tru_nguoi_phu_thuoc']) }}</td>
                        </tr>
                        <tr class="border-t border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-900/50 font-semibold">
                            <td class="px-3 py-2" colspan="2">Tổng giảm trừ gia cảnh</td>
                            <td class="px-3 py-2 text-right text-emerald-700 dark:text-emerald-400">{{ $tienTe($dg['giam_tru_gia_canh']) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if ($npt->isNotEmpty())
                <div class="mt-3 rounded-lg bg-gray-50 dark:bg-slate-900/50 px-4 py-3">
                    <p class="text-xs font-medium text-gray-600 dark:text-slate-300 mb-2">
                        Người phụ thuộc đang khai báo trên hồ sơ:
                    </p>
                    <ul class="space-y-1">
                        @foreach ($npt as $p)
                            <li class="text-xs text-gray-600 dark:text-slate-400 flex justify-between max-w-xl">
                                <span>
                                    • {{ $p->ho_ten }}
                                    <span class="text-gray-400">({{ $p->quan_he_text }}@if($p->ma_so_thue) — MST {{ $p->ma_so_thue }}@endif)</span>
                                </span>
                                <span class="text-gray-400">
                                    Tính giảm trừ từ {{ $p->ngay_bat_dau ? $p->ngay_bat_dau->format('m/Y') : '—' }}
                                    @if ($p->ngay_ket_thuc) đến {{ $p->ngay_ket_thuc->format('m/Y') }} @endif
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @elseif ($dg['so_nguoi_phu_thuoc'] === 0)
                <p class="mt-3 text-xs text-gray-400">
                    Chưa khai báo người phụ thuộc nào. Khai báo tại <b>Hồ sơ nhân viên → Người phụ thuộc</b>
                    để được giảm trừ {{ $tienTe(\App\Services\TinhLuongService::GIAM_TRU_NGUOI_PHU_THUOC) }}/người/tháng.
                </p>
            @endif
        </div>

        {{-- ============ C. THUẾ TNCN ============ --}}
        <div class="px-5 py-4">
            <div class="flex justify-between items-start gap-4">
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Ⓒ Thuế thu nhập cá nhân</p>
                    <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">
                        Biểu luỹ tiến từng phần 5 bậc (Luật Thuế TNCN 2025, áp dụng từ kỳ tính thuế 2026)
                    </p>
                </div>
                <p class="text-lg font-bold text-red-500 whitespace-nowrap">-{{ $tienTe($dg['thue_tncn']) }}</p>
            </div>

            {{-- C1. Đường đi từ thu nhập tới căn cứ tính thuế --}}
            <div class="overflow-x-auto mt-3">
                <table class="w-full text-sm">
                    <tbody class="text-gray-700 dark:text-slate-300">
                        <tr class="border-t border-gray-100 dark:border-slate-700">
                            <td class="px-3 py-2">① Thu nhập chịu thuế</td>
                            <td class="px-3 py-2 text-xs text-gray-400 font-mono">
                                = Lương theo công + Phụ cấp chịu thuế ({{ $tienTe($dg['phu_cap_chiu_thue']) }}) + Tăng ca
                            </td>
                            <td class="px-3 py-2 text-right font-medium">{{ $tienTe($dg['thu_nhap_chiu_thue']) }}</td>
                        </tr>
                        <tr class="border-t border-gray-100 dark:border-slate-700">
                            <td class="px-3 py-2">② Trừ bảo hiểm bắt buộc (Ⓐ)</td>
                            <td class="px-3 py-2 text-xs text-gray-400 font-mono">BHXH + BHYT + BHTN</td>
                            <td class="px-3 py-2 text-right font-medium text-red-500">-{{ $tienTe($dg['tong_bao_hiem']) }}</td>
                        </tr>
                        <tr class="border-t border-gray-100 dark:border-slate-700">
                            <td class="px-3 py-2">③ Trừ giảm trừ gia cảnh (Ⓑ)</td>
                            <td class="px-3 py-2 text-xs text-gray-400 font-mono">
                                Bản thân {{ $tienTe($dg['giam_tru_ban_than']) }}
                                @if ($dg['so_nguoi_phu_thuoc'] > 0)
                                    + {{ $dg['so_nguoi_phu_thuoc'] }} NPT × {{ $tienTe($dg['muc_giam_tru_moi_npt']) }}
                                @endif
                            </td>
                            <td class="px-3 py-2 text-right font-medium text-red-500">-{{ $tienTe($dg['giam_tru_gia_canh']) }}</td>
                        </tr>
                        <tr class="border-t border-gray-200 dark:border-slate-600 bg-amber-50 dark:bg-amber-950/30 font-semibold">
                            <td class="px-3 py-2" colspan="2">④ THU NHẬP TÍNH THUẾ = ① − ② − ③ (âm thì tính bằng 0)</td>
                            <td class="px-3 py-2 text-right text-amber-700 dark:text-amber-400">{{ $tienTe($dg['thu_nhap_tinh_thue']) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- C2. Bóc tách từng bậc thuế --}}
            @if (empty($dg['chi_tiet_bac_thue']))
                <p class="mt-3 text-sm text-emerald-600 dark:text-emerald-400">
                    <i class="fa-solid fa-circle-check mr-1"></i>
                    Thu nhập tính thuế bằng 0 sau khi giảm trừ → <b>không phải nộp thuế TNCN</b>.
                </p>
            @else
                <p class="mt-4 mb-2 text-xs font-medium text-gray-600 dark:text-slate-300">
                    Áp biểu luỹ tiến từng phần lên {{ $tienTe($dg['thu_nhap_tinh_thue']) }}:
                </p>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-slate-900/50 text-gray-500 dark:text-slate-400">
                            <tr>
                                <th class="px-3 py-2 text-left font-medium">Bậc</th>
                                <th class="px-3 py-2 text-left font-medium">Khoảng thu nhập tính thuế/tháng</th>
                                <th class="px-3 py-2 text-right font-medium">Phần rơi vào bậc</th>
                                <th class="px-3 py-2 text-center font-medium">Thuế suất</th>
                                <th class="px-3 py-2 text-right font-medium">Thuế phải nộp</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-slate-300">
                            @foreach ($dg['chi_tiet_bac_thue'] as $b)
                            <tr class="border-t border-gray-100 dark:border-slate-700">
                                <td class="px-3 py-2">Bậc {{ $b['bac'] }}</td>
                                <td class="px-3 py-2 text-xs font-mono text-gray-500 dark:text-slate-400">
                                    @if ($b['den'] === null)
                                        Trên {{ number_format($b['tu']) }} đ
                                    @else
                                        {{ number_format($b['tu']) }} – {{ number_format($b['den']) }} đ
                                    @endif
                                </td>
                                <td class="px-3 py-2 text-right font-mono text-xs">{{ $tienTe($b['phan_thu_nhap']) }}</td>
                                <td class="px-3 py-2 text-center">{{ $phanTram($b['thue_suat']) }}</td>
                                <td class="px-3 py-2 text-right font-medium text-red-500">-{{ $tienTe($b['thue']) }}</td>
                            </tr>
                            @endforeach
                            <tr class="border-t border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-900/50 font-semibold">
                                <td class="px-3 py-2" colspan="4">Cộng thuế TNCN phải nộp</td>
                                <td class="px-3 py-2 text-right text-red-600 dark:text-red-400">-{{ $tienTe($dg['thue_tncn']) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- ============ D. KHẤU TRỪ KHÁC ============ --}}
        <div class="px-5 py-4">
            <div class="flex justify-between items-start gap-4">
                <div class="flex-1">
                    <p class="font-medium text-gray-900 dark:text-white">Ⓓ Khấu trừ khác</p>
                    @if ($dg['khau_tru_khac']->isEmpty())
                        <p class="text-sm text-gray-400 mt-1">Không có khoản tạm ứng / phạt / bồi thường nào trong kỳ.</p>
                    @else
                        <ul class="mt-2 space-y-1">
                            @foreach ($dg['khau_tru_khac'] as $kt)
                                <li class="text-sm text-gray-600 dark:text-slate-300 flex justify-between gap-4 max-w-xl">
                                    <span>• {{ $kt->ghi_chu ?: $kt->ten_loai }}</span>
                                    <span class="font-medium text-red-500 whitespace-nowrap">-{{ $tienTe($kt->so_tien) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <p class="text-lg font-bold text-red-500 whitespace-nowrap">-{{ $tienTe($dg['tong_khau_tru_khac']) }}</p>
            </div>
        </div>

        {{-- ============ TỔNG HỢP ============ --}}
        <div class="px-5 py-4 bg-gray-50 dark:bg-slate-900/50">
            <div class="flex justify-between items-center gap-4">
                <div>
                    <p class="font-semibold text-gray-900 dark:text-white">TỔNG KHẤU TRỪ</p>
                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-1 font-mono">
                        = Ⓐ {{ number_format($dg['tong_bao_hiem']) }}
                        + Ⓒ {{ number_format($dg['thue_tncn']) }}
                        + Ⓓ {{ number_format($dg['tong_khau_tru_khac']) }}
                        <span class="text-gray-400">(Ⓑ chỉ dùng để tính thuế, không trừ vào lương)</span>
                    </p>
                </div>
                <p class="text-xl font-bold text-red-600 dark:text-red-400 whitespace-nowrap">-{{ $tienTe($dg['tong_khau_tru']) }}</p>
            </div>
        </div>

        <div class="px-5 py-5 bg-blue-50 dark:bg-blue-950/30">
            <div class="flex justify-between items-center gap-4">
                <div>
                    <p class="font-bold text-gray-900 dark:text-white">LƯƠNG THỰC NHẬN (net)</p>
                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-1 font-mono">
                        = Tổng lương {{ number_format($dg['tong_luong']) }} − Tổng khấu trừ {{ number_format($dg['tong_khau_tru']) }}
                    </p>
                </div>
                <p class="text-2xl font-extrabold text-blue-600 dark:text-sky-400 whitespace-nowrap">
                    {{ $tienTe($dg['luong_thuc_nhan']) }}
                </p>
            </div>
        </div>

    </div>
</div>
