@extends('layouts.admin')

@section('title', 'Chi tiết chấm công')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">📋 Chi tiết chấm công</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Thông tin chi tiết bản ghi chấm công</p>
            </div>
            <a href="{{ route('admin.cham-cong.index') }}" 
               class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    {{-- THÔNG TIN NHÂN VIÊN --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="font-semibold text-gray-800 dark:text-white">👤 Thông tin nhân viên</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Họ và tên</p>
                    <p class="font-semibold text-gray-800 dark:text-white">
                        {{ $chamCong->nguoi_dung->hoSo->ho ?? '' }} {{ $chamCong->nguoi_dung->hoSo->ten ?? $chamCong->nguoi_dung->ten_dang_nhap ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Mã nhân viên</p>
                    <p class="font-semibold text-gray-800 dark:text-white">
                        {{ $chamCong->nguoi_dung->hoSo->ma_nhan_vien ?? $chamCong->nguoi_dung->id ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Phòng ban</p>
                    <p class="font-semibold text-gray-800 dark:text-white">
                        {{ $chamCong->nguoi_dung->phongBan->ten_phong_ban ?? 'Chưa có' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- THÔNG TIN CHẤM CÔNG --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="font-semibold text-gray-800 dark:text-white">⏰ Thông tin chấm công</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ngày chấm công</p>
                    <p class="font-semibold text-gray-800 dark:text-white">
                        {{ \Carbon\Carbon::parse($chamCong->ngay_cham_cong)->format('d/m/Y') }}
                    </p>
                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($chamCong->ngay_cham_cong)->locale('vi')->dayName }}</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Giờ vào</p>
                    <p class="font-semibold text-gray-800 dark:text-white text-lg">
                        {{ $chamCong->gio_vao ? date('H:i', strtotime($chamCong->gio_vao)) : '--:--' }}
                    </p>
                    @if(($chamCong->phut_di_muon ?? 0) > 0)
                        <p class="text-xs text-yellow-600 dark:text-yellow-400">(+{{ $chamCong->phut_di_muon }} phút)</p>
                    @endif
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Giờ ra</p>
                    <p class="font-semibold text-gray-800 dark:text-white text-lg">
                        {{ $chamCong->gio_ra ? date('H:i', strtotime($chamCong->gio_ra)) : '--:--' }}
                    </p>
                    @if(($chamCong->phut_ve_som ?? 0) > 0)
                        <p class="text-xs text-yellow-600 dark:text-yellow-400">(-{{ $chamCong->phut_ve_som }} phút)</p>
                    @endif
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Số giờ làm</p>
                    <p class="font-semibold text-gray-800 dark:text-white text-lg">
                        {{ number_format($chamCong->so_gio_lam ?? 0, 1) }} giờ
                    </p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Số công</p>
                    <p class="font-semibold text-gray-800 dark:text-white text-lg">
                        {{ number_format($chamCong->so_cong ?? 0, 2) }}
                    </p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Giờ tăng ca</p>
                    <p class="font-semibold text-gray-800 dark:text-white text-lg">
                        {{ number_format($chamCong->gio_tang_ca ?? 0, 1) }} giờ
                    </p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Trạng thái</p>
                    @php
                        $statusConfig = [
                            'dung_gio' => ['bg-green-100 dark:bg-green-900/30', 'text-green-700 dark:text-green-300', 'Đúng giờ'],
                            'di_muon' => ['bg-yellow-100 dark:bg-yellow-900/30', 'text-yellow-700 dark:text-yellow-300', 'Đi muộn'],
                            've_som' => ['bg-orange-100 dark:bg-orange-900/30', 'text-orange-700 dark:text-orange-300', 'Về sớm'],
                            'khong_cham_cong' => ['bg-red-100 dark:bg-red-900/30', 'text-red-700 dark:text-red-300', 'Không chấm công'],
                        ];
                        $config = $statusConfig[$chamCong->trang_thai] ?? $statusConfig['khong_cham_cong'];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $config[0] }} {{ $config[1] }}">
                        {{ $config[2] }}
                    </span>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Phương thức</p>
                    <p class="font-semibold text-gray-800 dark:text-white">
                        {{ $chamCong->phuong_thuc_cham_cong_text ?? 'Chưa xác định' }}
                    </p>
                    @if($chamCong->dia_chi_ip)
                        <p class="text-xs text-gray-400 mt-1">IP: {{ $chamCong->dia_chi_ip }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- PHÊ DUYỆT --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="font-semibold text-gray-800 dark:text-white">✅ Thông tin phê duyệt</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Người phê duyệt</p>
                    <p class="font-semibold text-gray-800 dark:text-white">
                        @php
                            $nguoiDuyet = $chamCong->nguoi_phe_duyet;
                            if ($nguoiDuyet) {
                                $ho = $nguoiDuyet->hoSo->ho ?? '';
                                $ten = $nguoiDuyet->hoSo->ten ?? $nguoiDuyet->ten_dang_nhap ?? '';
                                echo trim($ho . ' ' . $ten);
                            } else {
                                echo 'Chưa có';
                            }
                        @endphp
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Thời gian phê duyệt</p>
                    <p class="font-semibold text-gray-800 dark:text-white">
                        {{ $chamCong->thoi_gian_phe_duyet ? \Carbon\Carbon::parse($chamCong->thoi_gian_phe_duyet)->format('d/m/Y H:i') : 'Chưa có' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Trạng thái duyệt</p>
                    <div class="mt-1">
                        @if($chamCong->trang_thai_duyet == 1)
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300">
                                ✅ Đã duyệt
                            </span>
                        @elseif($chamCong->trang_thai_duyet == 2)
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300">
                                ❌ Từ chối
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300">
                                ⏳ Chờ duyệt
                            </span>
                        @endif
                    </div>
                </div>
                @if($chamCong->ghi_chu_duyet)
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ghi chú duyệt</p>
                    <p class="mt-1 text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg text-sm">
                        {{ $chamCong->ghi_chu_duyet }}
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- GHI CHÚ --}}
    @if($chamCong->ghi_chu)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="font-semibold text-gray-800 dark:text-white">📝 Ghi chú</h2>
        </div>
        <div class="p-6">
            <p class="text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg text-sm">
                {{ $chamCong->ghi_chu }}
            </p>
        </div>
    </div>
    @endif

    {{-- NÚT HÀNH ĐỘNG --}}
    @if(($chamCong->trang_thai_duyet ?? 0) == 3 || !$chamCong->trang_thai_duyet)
    <div class="flex gap-3">
        <form action="{{ route('admin.cham-cong.phe-duyet', $chamCong->id) }}" method="POST" class="inline">
            @csrf
            <input type="hidden" name="trang_thai_duyet" value="1">
            <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                ✅ Phê duyệt
            </button>
        </form>
        <form action="{{ route('admin.cham-cong.phe-duyet', $chamCong->id) }}" method="POST" class="inline">
            @csrf
            <input type="hidden" name="trang_thai_duyet" value="2">
            <input type="hidden" name="ghi_chu_phe_duyet" value="Từ chối">
            <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                ❌ Từ chối
            </button>
        </form>
    </div>
    @endif

</div>
@endsection