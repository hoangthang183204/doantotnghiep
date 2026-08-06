@extends('layouts.admin')

@section('title', 'Lịch chấm công chi tiết')

@php
    use Carbon\Carbon;
@endphp

@section('content')
    <div class="space-y-6">
        {{-- HEADER & NÚT QUAY LẠI --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-between gap-4">
            <div>
                <a href="{{ route('truong-phong.bao-cao.attendance', ['thang' => $thang, 'nam' => $nam]) }}" 
                   class="inline-flex items-center text-sm text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 mb-2 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách
                </a>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    <i class="fas fa-calendar-alt mr-3 text-blue-600"></i>
                    Lịch chấm công: {{ ($selectedNhanVien->hoSo->ho ?? '') . ' ' . ($selectedNhanVien->hoSo->ten ?? $selectedNhanVien->ten_dang_nhap) }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">
                    Mã NV: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $selectedNhanVien->hoSo->ma_nhan_vien ?? 'N/A' }}</span> 
                    - Tháng {{ $thang }}/{{ $nam }}
                </p>
            </div>

            {{-- ĐỔI THÁNG / NĂM --}}
            <form method="GET" action="{{ route('truong-phong.bao-cao.attendance.detail') }}" class="flex items-center gap-2">
                <input type="hidden" name="nhan_vien_id" value="{{ $selectedNhanVien->id }}">
                <select name="thang" onchange="this.form.submit()" class="px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $thang == $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
                    @endfor
                </select>
                <select name="nam" onchange="this.form.submit()" class="px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    @for ($i = date('Y') - 3; $i <= date('Y'); $i++)
                        <option value="{{ $i }}" {{ $nam == $i ? 'selected' : '' }}>Năm {{ $i }}</option>
                    @endfor
                </select>
            </form>
        </div>

        {{-- ================== LỊCH CHẤM CÔNG (PHONG CÁCH HISTORY - CÓ SỐ CÔNG) ================== --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900 dark:text-white">
                    Lịch chấm công tháng {{ $thang }}/{{ $nam }}
                </h3>
                <div class="flex items-center gap-3 text-xs">
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-green-500"></span> Full công</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-teal-500"></span> Nửa công</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-yellow-500"></span> Muộn</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-orange-500"></span> Sớm</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-gray-300 dark:bg-gray-600"></span> Chưa</span>
                </div>
            </div>
            
            <div class="p-4 flex justify-center">
                {{-- Dùng max-w-[400px] để tạo lịch hình vuông gọn gàng --}}
                <div class="grid grid-cols-7 gap-1.5 max-w-[400px] w-full">
                    <!-- Tiêu đề các thứ -->
                    @foreach ($thuTrongTuan as $thu)
                        <div class="text-center font-semibold text-[10px] text-gray-500 dark:text-gray-400 py-1 border-b dark:border-gray-700">
                            {{ $thu }}
                        </div>
                    @endforeach
            
                    <!-- Các ngày trong tháng -->
                    @for ($i = 0; $i < $soNgayTrongThang + $thuBatDau; $i++)
                        @php
                            $ngay = $i - $thuBatDau + 1;
                            $isValid = $ngay >= 1 && $ngay <= $soNgayTrongThang;
                            $ngayHienTai = $isValid ? Carbon::create($nam, $thang, $ngay) : null;
                            $isToday = $ngayHienTai && $ngayHienTai->isToday();
            
                            $record = $isValid ? ($chamCongTrongThang[$selectedNhanVien->id][$ngay] ?? null) : null;
                            $daCoChamCong = $record !== null;
                            $soCong = floatval($record->so_cong ?? 0);
                            $trangThai = $record->trang_thai ?? null;
                            
                            // Default (Chưa chấm công)
                            $bgColor = 'bg-gray-100/80 dark:bg-gray-800/40';
                            $textColor = 'text-gray-400 dark:text-gray-500';
                            $borderColor = 'border-gray-200 dark:border-gray-700';

                            if ($isValid && $daCoChamCong) {
                                // 1. Xác định màu nền dựa trên số Công TRƯỚC
                                if ($soCong >= 1) {
                                    $bgColor = 'bg-green-100/80 dark:bg-green-900/30';
                                    $textColor = 'text-green-700 dark:text-green-300';
                                } elseif ($soCong > 0) {
                                    $bgColor = 'bg-teal-100/80 dark:bg-teal-900/30';
                                    $textColor = 'text-teal-700 dark:text-teal-300';
                                } else {
                                    $bgColor = 'bg-red-100/80 dark:bg-red-900/30';
                                    $textColor = 'text-red-700 dark:text-red-300';
                                }
                                $borderColor = 'border-gray-200 dark:border-gray-700';

                                // 2. Ghi đè (Override) màu nếu có Trạng thái đặc biệt (Muộn, Sớm)
                                if ($trangThai == 'di_muon') {
                                    $bgColor = 'bg-yellow-100/80 dark:bg-yellow-900/30';
                                    $textColor = 'text-yellow-700 dark:text-yellow-300';
                                    $borderColor = 'border-yellow-300 dark:border-yellow-700';
                                } elseif ($trangThai == 've_som') {
                                    $bgColor = 'bg-orange-100/80 dark:bg-orange-900/30';
                                    $textColor = 'text-orange-700 dark:text-orange-300';
                                    $borderColor = 'border-orange-300 dark:border-orange-700';
                                }
                            }

                            if ($isToday) {
                                $borderColor = 'border-blue-500 ring-2 ring-blue-500/50';
                            }
                        @endphp
            
                        @if ($i < $thuBatDau)
                            <!-- Ô trống đầu tháng -->
                            <div class="aspect-square rounded-md border border-transparent bg-gray-50/50 dark:bg-gray-800/20"></div>
                        @elseif ($isValid)
                            {{-- Ô NGÀY HÌNH VUÔNG --}}
                            <div class="aspect-square p-1 rounded-md border {{ $borderColor }} {{ $bgColor }} flex flex-col justify-between transition-all hover:shadow-sm relative group">
                                {{-- Số ngày ở trên --}}
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold {{ $textColor }}">
                                        {{ $ngay }}
                                    </span>
                                </div>
            
                                {{-- Giữa ô: Hiển thị số công hoặc dấu --}}
                                <div class="flex justify-center items-center my-auto">
                                    @if ($daCoChamCong)
                                        <span class="text-[11px] font-bold {{ $textColor }}">
                                            {{ number_format($soCong, 1) }}
                                        </span>
                                    @else
                                        <span class="text-[10px] text-gray-400 dark:text-gray-600">--</span>
                                    @endif
                                </div>
                                
                                {{-- Badge "Hôm nay" ở dưới cùng --}}
                                @if ($isToday)
                                    <span class="text-[8px] bg-blue-500 text-white px-1 rounded mx-auto text-center">Hôm nay</span>
                                @endif
                            </div>
                        @endif
                    @endfor
                </div>
            </div>
        </div>

        {{-- ================== BẢNG DANH SÁCH CHI TIẾT CÓ PHÂN TRANG ================== --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900 dark:text-white">
                    <i class="fas fa-list mr-2 text-blue-600"></i>
                    Danh sách chấm công chi tiết
                </h3>
                <span class="text-sm bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full text-gray-600 dark:text-gray-300">
                    Tháng {{ $thang }}/{{ $nam }}
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700/50 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3 w-[60px]">STT</th>
                            <th class="px-4 py-3">Ngày</th>
                            <th class="px-4 py-3">Ca làm việc</th>
                            <th class="px-4 py-3">Check-in</th>
                            <th class="px-4 py-3">Check-out</th>
                            <th class="px-4 py-3 text-center">Số công</th>
                            <th class="px-4 py-3">Trạng thái</th>
                            <th class="px-4 py-3">Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($danhSachChiTiet as $index => $item)
                            @php
                                $soCong = floatval($item->so_cong ?? 0);
                            @endphp
                            <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-4 py-3 text-gray-500">{{ $danhSachChiTiet->firstItem() + $index }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                    {{ Carbon::parse($item->ngay_cham_cong)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($item->caLamViec)
                                        <span class="px-2 py-1 rounded text-xs {{ $item->caLamViec->ten == 'Sáng' ? 'bg-yellow-100 text-yellow-700' : 'bg-indigo-100 text-indigo-700' }}">
                                            {{ $item->caLamViec->ten }}
                                        </span>
                                    @else
                                        --
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $item->gio_vao ? Carbon::parse($item->gio_vao)->format('H:i') : '--' }}</td>
                                <td class="px-4 py-3">{{ $item->gio_ra ? Carbon::parse($item->gio_ra)->format('H:i') : '--' }}</td>
                                <td class="px-4 py-3 text-center font-bold {{ $soCong >= 1 ? 'text-green-600' : ($soCong >= 0.5 ? 'text-teal-600' : ($soCong > 0 ? 'text-pink-600' : 'text-red-500')) }}">
                                    {{ number_format($soCong, 2) }}
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        // 🟢 LOGIC MỚI: BỎ HẲN TĂNG CA. DỰA VÀO SỐ CÔNG TRƯỚC
                                        if ($soCong > 0) {
                                            // Nếu có công => Mặc định là Đúng giờ
                                            $mau = 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300';
                                            $icon = 'fas fa-check-circle';
                                            $text = '✅ Đúng giờ';

                                            // Chỉ đổi thành Muộn hoặc Sớm nếu trạng thái đặc biệt tương ứng
                                            if ($item->trang_thai == 'di_muon') {
                                                $mau = 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300';
                                                $icon = 'fas fa-exclamation-triangle';
                                                $text = '⏰ Đi muộn';
                                            } elseif ($item->trang_thai == 've_som') {
                                                $mau = 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300';
                                                $icon = 'fas fa-exclamation-triangle';
                                                $text = '🏠 Về sớm';
                                            }
                                        } else {
                                            // Nếu không có công => Xét các trạng thái đặc biệt
                                            $mau = 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400';
                                            $icon = 'fas fa-minus-circle';
                                            $text = '⏸️ Chưa chấm công';

                                            if ($item->trang_thai == 'di_muon') {
                                                $mau = 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300';
                                                $icon = 'fas fa-exclamation-triangle';
                                                $text = '⏰ Đi muộn';
                                            } elseif ($item->trang_thai == 've_som') {
                                                $mau = 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300';
                                                $icon = 'fas fa-exclamation-triangle';
                                                $text = '🏠 Về sớm';
                                            } elseif ($item->trang_thai == 'den_som') {
                                                $mau = 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300';
                                                $icon = 'fas fa-arrow-up';
                                                $text = '🌟 Đến sớm';
                                            } elseif ($item->trang_thai == 'nghi_phep') {
                                                $mau = 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300';
                                                $icon = 'fas fa-calendar-check';
                                                $text = '📋 Nghỉ phép';
                                            } elseif ($item->trang_thai == 'vang_mat') {
                                                $mau = 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300';
                                                $icon = 'fas fa-times-circle';
                                                $text = '❌ Vắng mặt';
                                            }
                                        }
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $mau }}">
                                        <i class="{{ $icon }} mr-1"></i> {{ $text }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400 truncate max-w-[150px]">
                                    {{ $item->ghi_chu ?? '--' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-calendar-times text-4xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                    <p class="font-medium">Không có dữ liệu chấm công chi tiết trong tháng này.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Phân trang --}}
            @if ($danhSachChiTiet->hasPages())
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        Hiển thị <b>{{ $danhSachChiTiet->firstItem() }}</b> - <b>{{ $danhSachChiTiet->lastItem() }}</b> / <b>{{ $danhSachChiTiet->total() }}</b> kết quả
                    </div>
                    <div>
                        {{ $danhSachChiTiet->links() }}
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection