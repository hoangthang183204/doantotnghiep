@extends('layouts.admin')

@section('title', 'Chi tiết đơn tăng ca')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700 dark:hover:text-gray-300">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('admin.tang-ca.index') }}" class="hover:text-gray-700 dark:hover:text-gray-300">Tăng ca</a>
                    <span>/</span>
                    <span class="text-gray-700 dark:text-gray-300">Chi tiết #{{ $tangCa->id }}</span>
                </div>
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">Chi tiết đơn tăng ca</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Xem thông tin và xét duyệt đơn đăng ký tăng ca</p>
            </div>
            <a href="{{ route('admin.tang-ca.index') }}" 
               class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    {{-- THÔNG BÁO --}}
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg shadow-sm flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 dark:text-green-400">×</button>
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg shadow-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- COLUMN LEFT --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- THÔNG TIN NHÂN VIÊN --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Thông tin nhân viên
                    </h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        @php
                            $hoTen = optional($tangCa->nguoi_dung->hoSo)
                                ? $tangCa->nguoi_dung->hoSo->ho . ' ' . $tangCa->nguoi_dung->hoSo->ten
                                : $tangCa->nguoi_dung->ten_dang_nhap ?? 'N/A';
                            $initial = strtoupper(substr($hoTen, 0, 1));
                        @endphp
                        <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-600 dark:text-gray-300 font-bold text-lg">
                            {{ $initial }}
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 dark:text-white">{{ $hoTen }}</h3>
                            <p class="text-xs text-gray-500">Mã NV: {{ optional($tangCa->nguoi_dung->hoSo)->ma_nhan_vien ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">Phòng ban: {{ optional($tangCa->nguoi_dung->phongBan)->ten_phong_ban ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- THÔNG TIN TĂNG CA --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Thông tin tăng ca
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div>
                            <p class="text-xs text-gray-500">Ngày tăng ca</p>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $tangCa->ngay_tang_ca->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-400">{{ ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'][$tangCa->ngay_tang_ca->dayOfWeek] }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Thời gian</p>
                            <p class="font-medium text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($tangCa->gio_bat_dau)->format('H:i') }} - {{ \Carbon\Carbon::parse($tangCa->gio_ket_thuc)->format('H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Số giờ</p>
                            <p class="font-semibold text-blue-600 dark:text-blue-400 text-lg">{{ number_format($tangCa->so_gio_tang_ca, 1) }}<span class="text-sm"> giờ</span></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Loại tăng ca</p>
                            @php
                                $loaiLabels = ['ngay_thuong' => 'Ngày thường', 'ngay_nghi' => 'Ngày nghỉ', 'le_tet' => 'Lễ / Tết'];
                                $loaiColors = ['ngay_thuong' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400', 'ngay_nghi' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400', 'le_tet' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'];
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $loaiColors[$tangCa->loai_tang_ca] ?? 'bg-gray-100' }}">
                                {{ $loaiLabels[$tangCa->loai_tang_ca] ?? $tangCa->loai_tang_ca }}
                            </span>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">📝 Lý do tăng ca</p>
                        <p class="text-gray-700 dark:text-gray-300 text-sm">{{ $tangCa->ly_do_tang_ca }}</p>
                    </div>

                    <div class="mt-4 flex justify-between text-xs text-gray-400">
                        <span>Ngày tạo: {{ $tangCa->created_at->format('d/m/Y H:i') }}</span>
                        <span>Mã đơn: #{{ $tangCa->id }}</span>
                    </div>
                </div>
            </div>

            {{-- KẾT QUẢ THỰC HIỆN --}}
            @if($tangCa->thuc_hien)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Kết quả thực hiện
                    </h2>
                </div>
                <div class="p-6">
                    @php $th = $tangCa->thuc_hien; @endphp
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-xs text-gray-500">Giờ bắt đầu</p>
                            <p class="font-medium text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($th->gio_bat_dau_thuc_te)->format('H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Giờ kết thúc</p>
                            <p class="font-medium text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($th->gio_ket_thuc_thuc_te)->format('H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Số giờ thực tế</p>
                            <p class="font-semibold text-blue-600 dark:text-blue-400">{{ number_format($th->so_gio_tang_ca_thuc_te, 1) }} giờ</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Công tăng ca</p>
                            <p class="font-semibold text-green-600 dark:text-green-400">{{ $th->so_cong_tang_ca }}</p>
                        </div>
                    </div>
                    @if($th->cong_viec_da_thuc_hien)
                        <div class="mt-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3">
                            <p class="text-xs text-gray-500 mb-1">Công việc đã thực hiện</p>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $th->cong_viec_da_thuc_hien }}</p>
                        </div>
                    @endif
                    @if($th->ghi_chu)
                        <div class="mt-2 bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3">
                            <p class="text-xs text-gray-500 mb-1">Ghi chú</p>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $th->ghi_chu }}</p>
                        </div>
                    @endif
                    @if($tangCa->luong_tang_ca)
                        <div class="mt-3 bg-green-50 dark:bg-green-900/20 rounded-lg p-3 border border-green-200 dark:border-green-800">
                            <p class="text-xs text-green-600 dark:text-green-400 font-medium">💰 Lương tăng ca</p>
                            <p class="text-lg font-bold text-green-700 dark:text-green-300">{{ number_format($tangCa->luong_tang_ca, 0) }}đ</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- COLUMN RIGHT --}}
        <div class="space-y-6">
            
            {{-- TRẠNG THÁI DUYỆT --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="font-semibold text-gray-800 dark:text-white">Trạng thái duyệt</h2>
                </div>
                <div class="p-6">
                    @php
                        $statusConfig = [
                            'cho_duyet' => ['bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400', '🟡 Chờ duyệt'],
                            'da_duyet' => ['bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400', '✅ Đã duyệt'],
                            'tu_choi' => ['bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400', '❌ Từ chối'],
                            'huy' => ['bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400', '🗑️ Đã huỷ'],
                        ];
                        $config = $statusConfig[$tangCa->trang_thai] ?? $statusConfig['cho_duyet'];
                    @endphp
                    <div class="rounded-lg p-3 text-center {{ $config[0] }}">
                        <span class="font-medium">{{ $config[1] }}</span>
                    </div>

                    @if($tangCa->nguoi_duyet)
                        <div class="mt-4 space-y-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Người duyệt</span>
                                <span class="font-medium text-gray-700 dark:text-gray-300">
                                    {{ optional($tangCa->nguoi_duyet->hoSo)
                                        ? $tangCa->nguoi_duyet->hoSo->ho . ' ' . $tangCa->nguoi_duyet->hoSo->ten
                                        : $tangCa->nguoi_duyet->ten_dang_nhap }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Thời gian duyệt</span>
                                <span class="text-gray-700 dark:text-gray-300">{{ $tangCa->thoi_gian_duyet?->format('d/m/Y H:i') }}</span>
                            </div>
                            @if($tangCa->ly_do_tu_choi)
                                <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-3 mt-2">
                                    <p class="text-xs text-red-600 dark:text-red-400 font-medium">Lý do từ chối</p>
                                    <p class="text-sm text-red-700 dark:text-red-300 mt-1">{{ $tangCa->ly_do_tu_choi }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- ⭐ HIỂN THỊ TRẠNG THÁI THỰC HIỆN --}}
                    @if($tangCa->thuc_hien)
                        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
                            <p class="text-xs text-gray-500 mb-2">Trạng thái thực hiện</p>
                            @php
                                $thucHienStatus = [
                                    'nhan_vien_xac_nhan' => ['bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400', '👤 Nhân viên đã xác nhận'],
                                    'quan_ly_xac_nhan' => ['bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400', '✅ Quản lý đã xác nhận hoàn thành'],
                                ];
                                $thConfig = $thucHienStatus[$tangCa->thuc_hien->trang_thai] ?? ['bg-gray-100 text-gray-700', $tangCa->thuc_hien->trang_thai];
                            @endphp
                            <div class="rounded-lg p-2 text-center text-sm {{ $thConfig[0] }}">
                                {{ $thConfig[1] }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            @if($tangCa->trang_thai === 'cho_duyet')
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                        <h2 class="font-semibold text-gray-800 dark:text-white">Xét duyệt</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <form method="POST" action="{{ route('admin.tang-ca.duyet', $tangCa->id) }}">
                            @csrf
                            <button type="submit" onclick="return confirm('Xác nhận phê duyệt đơn tăng ca này?')"
                                class="w-full py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition flex items-center justify-center gap-2 text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Phê duyệt
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.tang-ca.tu-choi', $tangCa->id) }}" id="form-tu-choi-detail">
                            @csrf
                            <textarea name="ly_do_tu_choi" rows="3" placeholder="Nhập lý do từ chối..." 
                                class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400 dark:bg-gray-800 dark:text-white"></textarea>
                            <button type="submit" onclick="return xacNhanTuChoi()"
                                class="w-full mt-3 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition flex items-center justify-center gap-2 text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Từ chối
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- ⭐ NÚT XÁC NHẬN HOÀN THÀNH CHO QUẢN LÝ --}}
            @if($tangCa->trang_thai === 'da_duyet' && $tangCa->thuc_hien && $tangCa->thuc_hien->trang_thai === 'nhan_vien_xac_nhan')
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border-2 border-green-200 dark:border-green-800">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-green-50 dark:bg-green-900/20">
                        <h2 class="font-semibold text-green-700 dark:text-green-400 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Xác nhận hoàn thành
                        </h2>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                            Nhân viên đã xác nhận đã làm tăng ca. Vui lòng xác nhận hoàn thành và tính lương.
                        </p>
                        <a href="{{ route('admin.tang-ca.employee.tang-ca.approve-thuc-hien', $tangCa->id) }}" 
                           class="w-full py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition flex items-center justify-center gap-2 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Xác nhận hoàn thành & tính lương
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

@push('scripts')
<script>
    function xacNhanTuChoi() {
        const lyDo = document.querySelector('#form-tu-choi-detail textarea[name="ly_do_tu_choi"]').value.trim();
        if (!lyDo) {
            alert('Vui lòng nhập lý do từ chối.');
            return false;
        }
        return confirm('Xác nhận từ chối đơn tăng ca này?');
    }
</script>
@endpush
@endsection