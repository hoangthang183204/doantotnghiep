{{-- resources/views/truong-phong/bao-cao/attendance-detail.blade.php --}}

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

        {{-- LỊCH CHẤM CÔNG CHI TIẾT --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900 dark:text-white">
                    Chi tiết các ngày làm việc trong tháng
                </h3>
                <div class="flex items-center gap-3 text-xs">
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-green-500"></span> Đủ công</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-teal-500"></span> Nửa công</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-yellow-500"></span> Muộn</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-orange-500"></span> Sớm</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-red-400"></span> Vắng/Chưa chấm</span>
                </div>
            </div>
            
            <div class="p-4">
                <!-- Thu hẹp chiều rộng toàn bộ lịch (max-w-2xl) -->
                <div class="grid grid-cols-7 gap-1.5 max-w-2xl mx-auto">
                    <!-- Tiêu đề các thứ -->
                    @foreach ($thuTrongTuan as $thu)
                        <div class="text-center font-semibold text-xs text-gray-500 dark:text-gray-400 py-1 border-b dark:border-gray-700">
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
            
                            $bgColor = 'bg-gray-50 dark:bg-gray-800/40';
                            $textColor = 'text-gray-400 dark:text-gray-500';
                            $borderColor = 'border-gray-200 dark:border-gray-700';
            
                            if ($isValid && $daCoChamCong) {
                                if ($soCong >= 1) {
                                    $bgColor = 'bg-green-100 dark:bg-green-900/30';
                                    $textColor = 'text-green-700 dark:text-green-300';
                                    $borderColor = 'border-green-300 dark:border-green-700';
                                } elseif ($soCong >= 0.5) {
                                    $bgColor = 'bg-teal-100 dark:bg-teal-900/30';
                                    $textColor = 'text-teal-700 dark:text-teal-300';
                                    $borderColor = 'border-teal-300 dark:border-teal-700';
                                } else {
                                    $bgColor = 'bg-red-100 dark:bg-red-900/30';
                                    $textColor = 'text-red-700 dark:text-red-300';
                                    $borderColor = 'border-red-300 dark:border-red-700';
                                }
            
                                if ($trangThai == 'di_muon') {
                                    $bgColor = 'bg-yellow-100 dark:bg-yellow-900/30';
                                    $textColor = 'text-yellow-700 dark:text-yellow-300';
                                    $borderColor = 'border-yellow-300 dark:border-yellow-700';
                                } elseif ($trangThai == 've_som') {
                                    $bgColor = 'bg-orange-100 dark:bg-orange-900/30';
                                    $textColor = 'text-orange-700 dark:text-orange-300';
                                    $borderColor = 'border-orange-300 dark:border-orange-700';
                                }
                            }
            
                            if ($isToday) {
                                $borderColor = 'border-blue-500 ring-2 ring-blue-500/50';
                            }
                        @endphp
            
                        @if ($i < $thuBatDau)
                            <!-- Ô trống đầu tháng: giảm chiều cao xuống h-16 -->
                            <div class="h-16 rounded-md border border-transparent bg-gray-50/50 dark:bg-gray-800/20"></div>
                        @elseif ($isValid)
                            <!-- Ô ngày: giảm chiều cao h-16, padding p-1.5 -->
                            <div class="h-16 p-1.5 rounded-md border {{ $borderColor }} {{ $bgColor }} flex flex-col justify-between transition-all hover:shadow-sm relative group">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold {{ $textColor }}">
                                        {{ $ngay }}
                                    </span>
                                    @if ($isToday)
                                        <span class="text-[9px] bg-blue-500 text-white px-1 rounded">Hôm nay</span>
                                    @endif
                                </div>
            
                                @if ($daCoChamCong)
                                    <div class="text-center my-auto leading-none">
                                        <span class="text-[11px] font-bold block {{ $textColor }}">
                                            {{ number_format($soCong, 1) }} công
                                        </span>
                                        <span class="text-[9px] text-gray-500 dark:text-gray-400 block mt-0.5">
                                            {{ $record->tong_gio ?? 0 }}h
                                        </span>
                                    </div>
                                @else
                                    <div class="text-center my-auto text-[10px] text-gray-400 dark:text-gray-600">
                                        -
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endfor
                </div>
            </div>
        </div>
    </div>
@endsection