@extends('layouts.admin')

@section('title', 'Chi tiết đơn tăng ca')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Chi tiết đơn tăng ca #{{ $dangKy->id }}</h1>
            <p class="text-gray-500 mt-1">Xem thông tin và xét duyệt đơn tăng ca</p>
        </div>
        <a href="{{ route('admin.tang-ca.index') }}"
            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition text-sm">
            ← Quay lại
        </a>
    </div>

    {{-- THÔNG BÁO --}}
    @if(session('success'))
        <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex justify-between">
            <span>{{ session('success') }}</span>
            <button onclick="document.getElementById('alert-success').remove()" class="font-bold">×</button>
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">{{ $errors->first() }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- THÔNG TIN ĐĂNG KÝ --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white border-b pb-2">Thông tin đăng ký</h2>

            @php
                $hoTen = optional($dangKy->nguoi_dung->hoSo)
                    ? $dangKy->nguoi_dung->hoSo->ho . ' ' . $dangKy->nguoi_dung->hoSo->ten
                    : $dangKy->nguoi_dung->ten_dang_nhap;
            @endphp

            <div class="flex justify-between">
                <span class="text-gray-500 text-sm">Nhân viên</span>
                <span class="font-medium text-gray-800 dark:text-white">{{ $hoTen }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500 text-sm">Ngày tăng ca</span>
                <span class="font-medium text-gray-800 dark:text-white">
                    {{ $dangKy->ngay_tang_ca->format('d/m/Y') }}
                    ({{ ['CN','T2','T3','T4','T5','T6','T7'][$dangKy->ngay_tang_ca->dayOfWeek] }})
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500 text-sm">Giờ bắt đầu</span>
                <span class="font-medium text-gray-800 dark:text-white">{{ $dangKy->gio_bat_dau }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500 text-sm">Giờ kết thúc</span>
                <span class="font-medium text-gray-800 dark:text-white">{{ $dangKy->gio_ket_thuc }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500 text-sm">Số giờ tăng ca</span>
                <span class="font-semibold text-blue-600 text-lg">{{ $dangKy->so_gio_tang_ca }}h</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500 text-sm">Loại tăng ca</span>
                @php
                    $loaiClass = match($dangKy->loai_tang_ca) {
                        'ngay_thuong' => 'bg-blue-100 text-blue-700',
                        'ngay_nghi'   => 'bg-purple-100 text-purple-700',
                        'le_tet'      => 'bg-red-100 text-red-700',
                        default       => 'bg-gray-100 text-gray-700',
                    };
                @endphp
                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $loaiClass }}">
                    {{ \App\Models\DangKyTangCa::$loaiLabels[$dangKy->loai_tang_ca] ?? $dangKy->loai_tang_ca }}
                </span>
            </div>
            <div>
                <span class="text-gray-500 text-sm">Lý do tăng ca</span>
                <p class="mt-1 text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-gray-700 rounded-lg p-3 text-sm">
                    {{ $dangKy->ly_do_tang_ca }}
                </p>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500 text-sm">Ngày tạo</span>
                <span class="text-gray-700 dark:text-gray-300 text-sm">{{ $dangKy->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        {{-- TRẠNG THÁI & DUYỆT --}}
        <div class="space-y-5">

            {{-- Trạng thái hiện tại --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white border-b pb-2 mb-4">Trạng thái duyệt</h2>

                @php
                    $ttClass = match($dangKy->trang_thai) {
                        'cho_duyet' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
                        'da_duyet'  => 'bg-green-100 text-green-700 border-green-300',
                        'tu_choi'   => 'bg-red-100 text-red-700 border-red-300',
                        'huy'       => 'bg-gray-100 text-gray-600 border-gray-300',
                        default     => 'bg-gray-100 text-gray-600 border-gray-300',
                    };
                @endphp
                <div class="border rounded-lg p-4 {{ $ttClass }} text-center font-semibold text-lg">
                    {{ \App\Models\DangKyTangCa::$trangThaiLabels[$dangKy->trang_thai] ?? $dangKy->trang_thai }}
                </div>

                @if($dangKy->nguoi_duyet)
                    <div class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Người duyệt</span>
                            <span class="font-medium">
                                {{ optional($dangKy->nguoi_duyet->hoSo)
                                    ? $dangKy->nguoi_duyet->hoSo->ho . ' ' . $dangKy->nguoi_duyet->hoSo->ten
                                    : $dangKy->nguoi_duyet->ten_dang_nhap }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Thời gian duyệt</span>
                            <span>{{ $dangKy->thoi_gian_duyet?->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($dangKy->ly_do_tu_choi)
                            <div>
                                <span class="text-gray-500">Lý do từ chối</span>
                                <p class="mt-1 bg-red-50 text-red-700 rounded-lg p-3">{{ $dangKy->ly_do_tu_choi }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- ACTION BUTTONS --}}
            @if($dangKy->trang_thai === 'cho_duyet')
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-3">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white border-b pb-2 mb-4">Xét duyệt</h2>

                    {{-- Duyệt --}}
                    <form method="POST" action="{{ route('admin.tang-ca.duyet', $dangKy->id) }}">
                        @csrf
                        <button type="submit"
                            onclick="return confirm('Xác nhận phê duyệt đơn tăng ca này?')"
                            class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                            ✓ Phê duyệt
                        </button>
                    </form>

                    {{-- Từ chối --}}
                    <form method="POST" action="{{ route('admin.tang-ca.tu-choi', $dangKy->id) }}" id="form-tu-choi-detail">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Lý do từ chối <span class="text-red-500">*</span>
                            </label>
                            <textarea name="ly_do_tu_choi" rows="3"
                                placeholder="Nhập lý do từ chối..."
                                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-400"></textarea>
                        </div>
                        <button type="submit"
                            onclick="return xacNhanTuChoi()"
                            class="w-full py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">
                            ✗ Từ chối
                        </button>
                    </form>
                </div>
            @endif

            {{-- Thực hiện tăng ca --}}
            @if($dangKy->thuc_hien)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white border-b pb-2 mb-4">Kết quả thực hiện</h2>
                    @php $th = $dangKy->thuc_hien; @endphp
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Giờ thực tế</span>
                            <span>{{ $th->gio_bat_dau_thuc_te }} – {{ $th->gio_ket_thuc_thuc_te }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Số giờ thực tế</span>
                            <span class="font-semibold text-blue-600">{{ $th->so_gio_tang_ca_thuc_te }}h</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Công tăng ca</span>
                            <span class="font-semibold text-green-600">{{ $th->so_cong_tang_ca }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Trạng thái</span>
                            <span>{{ \App\Models\ThucHienTangCa::$trangThaiLabels[$th->trang_thai] ?? $th->trang_thai }}</span>
                        </div>
                        @if($th->cong_viec_da_thuc_hien)
                            <div>
                                <span class="text-gray-500">Công việc đã thực hiện</span>
                                <p class="mt-1 bg-gray-50 dark:bg-gray-700 rounded-lg p-3">{{ $th->cong_viec_da_thuc_hien }}</p>
                            </div>
                        @endif
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
