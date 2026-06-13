@extends('layouts.admin')

@section('title', 'Thực hiện tăng ca')

@section('content')

    <div class="space-y-6 bg-gray-50 dark:bg-[#0f172a] min-h-screen p-6">

        {{-- HEADER --}}
        <div
            class="bg-white dark:bg-gray-800/80 backdrop-blur border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        Thực hiện tăng ca
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        Quản lý kết quả thực hiện tăng ca của nhân viên
                    </p>
                </div>
                <div>
                    <button type="button" onclick="exportData()"
                        class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Xuất Excel
                    </button>
                </div>
            </div>
        </div>

        {{-- THỐNG KÊ --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Tổng số bản ghi</p>
                <h3 class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-2">{{ $soLuongTangCa ?? 0 }}</h3>
            </div>
            <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Tỷ lệ hoàn thành</p>
                <h3 class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">{{ $tyLeHoanThanh ?? 0 }}%</h3>
            </div>
            <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Tỷ lệ không hoàn thành</p>
                <h3 class="text-3xl font-bold text-red-600 dark:text-red-400 mt-2">{{ $tyLeChuaHoanThanh ?? 0 }}%</h3>
            </div>
            <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Tổng số giờ tăng ca</p>
                <h3 class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-2">{{ $soGioTangCa ?? 0 }} giờ</h3>
            </div>
            <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Tổng số công TC</p>
                <h3 class="text-3xl font-bold text-purple-600 dark:text-purple-400 mt-2">{{ $tongCongTangCa ?? 0 }}</h3>
            </div>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div
                class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="font-bold text-xl">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div
                class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg flex justify-between items-center">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="font-bold text-xl">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div
                class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- FILTER --}}
        <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-5">
            <form method="GET" action="{{ route('admin.thuc-hien-tang-ca.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tên nhân viên</label>
                        <input type="text" name="ten_nhan_vien" value="{{ request('ten_nhan_vien') }}"
                            placeholder="Nhập tên..."
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phòng ban</label>
                        <select name="phong_ban_id"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Tất cả phòng ban --</option>
                            @foreach ($phongBan ?? [] as $pb)
                                <option value="{{ $pb->id }}"
                                    {{ request('phong_ban_id') == $pb->id ? 'selected' : '' }}>
                                    {{ $pb->ten_phong_ban }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Trạng thái</label>
                        <select name="trang_thai"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Tất cả --</option>
                            <option value="chua_lam" {{ request('trang_thai') == 'chua_lam' ? 'selected' : '' }}>🟡 Chưa
                                làm</option>
                            <option value="dang_lam" {{ request('trang_thai') == 'dang_lam' ? 'selected' : '' }}>🔵 Đang
                                làm</option>
                            <option value="hoan_thanh" {{ request('trang_thai') == 'hoan_thanh' ? 'selected' : '' }}>🟢
                                Hoàn thành</option>
                            <option value="khong_hoan_thanh"
                                {{ request('trang_thai') == 'khong_hoan_thanh' ? 'selected' : '' }}>🔴 Không hoàn thành
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ngày tăng ca</label>
                        <input type="date" name="ngay_tang_ca" value="{{ request('ngay_tang_ca') }}"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tháng</label>
                        <select name="thang"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                            <option value="">-- Chọn tháng --</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('thang') == $i ? 'selected' : '' }}>Tháng
                                    {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Năm</label>
                        <select name="nam"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                            <option value="">-- Chọn năm --</option>
                            @for ($year = date('Y'); $year >= date('Y') - 5; $year--)
                                <option value="{{ $year }}" {{ request('nam') == $year ? 'selected' : '' }}>
                                    {{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Từ ngày</label>
                        <input type="date" name="tu_ngay" value="{{ request('tu_ngay') }}"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Đến ngày</label>
                        <input type="date" name="den_ngay" value="{{ request('den_ngay') }}"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                    </div>
                </div>

                <div class="mt-5 flex gap-3 flex-wrap">
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Tìm kiếm
                    </button>
                    <a href="{{ route('admin.thuc-hien-tang-ca.index') }}"
                        class="px-5 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        Làm mới
                    </a>
                </div>
            </form>
        </div>

        {{-- TABLE --}}
        <div
            class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-100 dark:bg-gray-800/60">
                        <tr class="text-left text-sm text-gray-700 dark:text-gray-300">
                            <th class="px-4 py-3">Nhân viên</th>
                            <th class="px-4 py-3">Ngày TC</th>
                            <th class="px-4 py-3">Giờ đăng ký</th>
                            <th class="px-4 py-3">Giờ thực tế</th>
                            <th class="px-4 py-3">Số giờ TT</th>
                            <th class="px-4 py-3">Công TC</th>
                            <th class="px-4 py-3">Trạng thái</th>
                            <th class="px-4 py-3 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($danhSachTangCa ?? [] as $item)
                            @php
                                // Lấy đăng ký tăng ca qua quan hệ dang_ky (đúng với model)
                                $dangKy = $item->dang_ky;
                                $nguoiDung = $dangKy ? $dangKy->nguoi_dung ?? null : null;
                                $hoSo = $nguoiDung ? $nguoiDung->hoSo ?? null : null;

                                $hoTen = '';
                                if ($hoSo) {
                                    $hoTen = trim(($hoSo->ho ?? '') . ' ' . ($hoSo->ten ?? ''));
                                }
                                if (empty($hoTen) && $nguoiDung) {
                                    $hoTen = $nguoiDung->ten_dang_nhap ?? 'N/A';
                                }
                                if (empty($hoTen)) {
                                    $hoTen = 'NV#' . ($dangKy->nguoi_dung_id ?? '?');
                                }

                                $initial = strtoupper(substr($hoTen, 0, 1));
                                $maNV = $hoSo ? $hoSo->ma_nhan_vien ?? 'N/A' : 'N/A';
                                $tenPhongBan =
                                    $nguoiDung && $nguoiDung->phongBan
                                        ? $nguoiDung->phongBan->ten_phong_ban ?? 'N/A'
                                        : 'N/A';

                                $badgeClass = match ($item->trang_thai) {
                                    'chua_lam'
                                        => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300',
                                    'dang_lam' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                                    'hoan_thanh'
                                        => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
                                    'khong_hoan_thanh'
                                        => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                                $badgeText = match ($item->trang_thai) {
                                    'chua_lam' => 'Chưa làm',
                                    'dang_lam' => 'Đang làm',
                                    'hoan_thanh' => 'Hoàn thành',
                                    'khong_hoan_thanh' => 'Không hoàn thành',
                                    default => $item->trang_thai,
                                };
                            @endphp
                            <tr
                                class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                            {{ $initial }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $hoTen }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Mã NV:
                                                {{ $maNV }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Phòng:
                                                {{ $tenPhongBan }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-700 dark:text-gray-300">
                                        {{ $dangKy && $dangKy->ngay_tang_ca ? \Carbon\Carbon::parse($dangKy->ngay_tang_ca)->format('d/m/Y') : 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $dangKy && $dangKy->ngay_tang_ca ? \Carbon\Carbon::parse($dangKy->ngay_tang_ca)->locale('vi')->dayName : '' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                    {{ $dangKy ? substr($dangKy->gio_bat_dau ?? '', 0, 5) . ' - ' . substr($dangKy->gio_ket_thuc ?? '', 0, 5) : 'N/A' }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="space-y-1">
                                        <span
                                            class="px-2 py-1 rounded text-xs bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300">
                                            Vào:
                                            {{ $item->gio_bat_dau_thuc_te ? substr($item->gio_bat_dau_thuc_te, 0, 5) : '--:--' }}
                                        </span>
                                        <br>
                                        <span
                                            class="px-2 py-1 rounded text-xs bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300">
                                            Ra:
                                            {{ $item->gio_ket_thuc_thuc_te ? substr($item->gio_ket_thuc_thuc_te, 0, 5) : '--:--' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-semibold text-blue-600 dark:text-blue-400">
                                        {{ number_format($item->so_gio_tang_ca_thuc_te ?? 0, 1) }}h
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-semibold text-green-600 dark:text-green-400">
                                        {{ number_format($item->so_cong_tang_ca ?? 0, 2) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">
                                        {{ $badgeText }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center gap-1.5">
                                        <a href="{{ route('admin.thuc-hien-tang-ca.show', $item->id) }}"
                                            class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                            title="Xem chi tiết">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.thuc-hien-tang-ca.edit', $item->id) }}"
                                            class="p-1.5 text-yellow-600 hover:bg-yellow-50 rounded-lg transition"
                                            title="Sửa">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-10 text-center text-gray-400 dark:text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Chưa có dữ liệu thực hiện tăng ca.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PHÂN TRANG --}}
            @if (isset($danhSachTangCa) && $danhSachTangCa->hasPages())
                <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $danhSachTangCa->links() }}
                </div>
            @endif
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function exportData() {
            var soLuong = {{ $soLuongTangCa ?? 0 }};
            if (soLuong === 0) {
                alert('Không có dữ liệu để xuất!');
                return;
            }
        }
    </script>
@endpush
