@extends('layouts.admin')

@section('title', 'Quản lý chấm công')

@section('content')
    <div class="space-y-6">
        {{-- HEADER --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Quản lý chấm công
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">
                    Theo dõi và tra cứu dữ liệu chấm công nhân viên
                </p>
            </div>
        </div>

        {{-- THỐNG KÊ --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Tổng bản ghi</p>
                <h3 class="text-3xl font-bold text-blue-600 mt-2">{{ $tongSoBanGhi ?? 0 }}</h3>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
                <p class="text-sm text-green-600">Tỷ lệ đúng giờ</p>
                <h3 class="text-3xl font-bold text-green-600 mt-2">{{ $tyLeDungGio ?? 0 }}%</h3>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
                <p class="text-sm text-yellow-600">Hôm nay</p>
                <h3 class="text-3xl font-bold text-yellow-600 mt-2">{{ $homNay ?? 0 }}</h3>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
                <p class="text-sm text-purple-600">Đi muộn hôm nay</p>
                <h3 class="text-3xl font-bold text-purple-600 mt-2">{{ $diMuonHomNay ?? 0 }}</h3>
            </div>
        </div>

        {{-- TÌM KIẾM --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
            <form method="GET" action="{{ route('admin.cham-cong.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">Tìm theo tên</label>
                        <input type="text" name="ten_nhan_vien" value="{{ request('ten_nhan_vien') }}"
                            placeholder="Nhập tên..."
                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">Phòng ban</label>
                        <select name="phong_ban_id"
                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
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
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">Trạng thái</label>
                        <select name="trang_thai"
                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                            <option value="">-- Tất cả trạng thái --</option>
                            <option value="dung_gio" {{ request('trang_thai') == 'dung_gio' ? 'selected' : '' }}>✅ Đúng giờ
                            </option>
                            <option value="di_muon" {{ request('trang_thai') == 'di_muon' ? 'selected' : '' }}>⚠️ Đi muộn
                            </option>
                            <option value="ve_som" {{ request('trang_thai') == 've_som' ? 'selected' : '' }}>🔻 Về sớm
                            </option>
                            <option value="vang_mat" {{ request('trang_thai') == 'vang_mat' ? 'selected' : '' }}>❌ Vắng mặt
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">Ngày chấm công</label>
                        <input type="date" name="ngay_cham_cong" value="{{ request('ngay_cham_cong') }}"
                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">Từ ngày</label>
                        <input type="date" name="tu_ngay" value="{{ request('tu_ngay') }}"
                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">Đến ngày</label>
                        <input type="date" name="den_ngay" value="{{ request('den_ngay') }}"
                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">Tháng</label>
                        <select name="thang"
                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                            <option value="">-- Chọn tháng --</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('thang') == $i ? 'selected' : '' }}>Tháng
                                    {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">Năm</label>
                        <select name="nam"
                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                            <option value="">-- Chọn năm --</option>
                            @for ($year = date('Y'); $year >= date('Y') - 5; $year--)
                                <option value="{{ $year }}" {{ request('nam') == $year ? 'selected' : '' }}>
                                    {{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="mt-5 flex gap-3">
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        🔍 Tìm kiếm
                    </button>
                    <a href="{{ route('admin.cham-cong.index') }}"
                        class="px-5 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                        🔄 Làm mới
                    </a>
                    <button type="button" onclick="exportData()"
                        class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        📊 Xuất Excel
                    </button>
                    <button type="button" onclick="openReportModal()"
                        class="px-5 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        📑 Báo cáo
                    </button>
                </div>
            </form>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div id="alert-success"
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-sm">
                <div class="flex justify-between items-center">
                    <span>{{ session('success') }}</span>
                    <button type="button" onclick="document.getElementById('alert-success').remove()"
                        class="font-bold text-green-700">×</button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div id="alert-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-sm">
                <div class="flex justify-between items-center">
                    <span>{{ session('error') }}</span>
                    <button type="button" onclick="document.getElementById('alert-error').remove()"
                        class="font-bold text-red-700">×</button>
                </div>
            </div>
        @endif

        {{-- ⭐ BỎ BULK ACTIONS (KHÔNG CẦN DUYỆT) --}}

        {{-- TABLE --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Nhân viên</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Ngày</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Giờ vào</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Giờ ra</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Số giờ</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Số công</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Trạng thái</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Lý do</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($chamCongs ?? [] as $cc)
                            @php
                                $nguoiDung = $cc->nguoiDung ?? null;
                                $hoSo = $nguoiDung ? $nguoiDung->hoSo ?? null : null;

                                $hoTen = '';
                                if ($hoSo) {
                                    $hoTen = trim(($hoSo->ho ?? '') . ' ' . ($hoSo->ten ?? ''));
                                }
                                if (empty($hoTen) && $nguoiDung) {
                                    $hoTen = $nguoiDung->ten_dang_nhap ?? 'N/A';
                                }
                                if (empty($hoTen)) {
                                    $hoTen = 'NV#' . ($cc->nguoi_dung_id ?? '?');
                                }

                                $initial = strtoupper(substr($hoTen, 0, 1));
                                $maNV = $hoSo ? $hoSo->ma_nhan_vien ?? 'N/A' : 'N/A';
                                $tenPhongBan =
                                    $nguoiDung && $nguoiDung->phongBan
                                        ? $nguoiDung->phongBan->ten_phong_ban ?? 'N/A'
                                        : 'N/A';
                            @endphp
                            <tr
                                class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                            {{ $initial }}
                                        </div>
                                        <div>
                                            <h6 class="font-semibold">{{ $hoTen }}</h6>
                                            <p class="text-xs text-gray-500">Mã: {{ $maNV }}</p>
                                            <p class="text-xs text-gray-500">Phòng: {{ $tenPhongBan }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    {{ \Carbon\Carbon::parse($cc->ngay_cham_cong)->format('d/m/Y') }}
                                    <br><small
                                        class="text-gray-500">{{ \Carbon\Carbon::parse($cc->ngay_cham_cong)->locale('vi')->dayName }}</small>
                                </td>
                                <td class="px-4 py-4">
                                    <span
                                        class="px-2 py-1 rounded text-sm {{ ($cc->phut_di_muon ?? 0) > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                                        {{ $cc->getGioVaoFormatAttribute() }}
                                    </span>
                                    @if (($cc->phut_di_muon ?? 0) > 0)
                                        <div class="text-xs text-yellow-600">+{{ $cc->phut_di_muon }}p</div>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <span
                                        class="px-2 py-1 rounded text-sm {{ ($cc->phut_ve_som ?? 0) > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                                        {{ $cc->getGioRaFormatAttribute() }}
                                    </span>
                                    @if (($cc->phut_ve_som ?? 0) > 0)
                                        <div class="text-xs text-yellow-600">-{{ $cc->phut_ve_som }}p</div>
                                    @endif
                                </td>
                                <td class="px-4 py-4 font-semibold">{{ number_format($cc->so_gio_lam ?? 0, 1) }}h</td>
                                <td class="px-4 py-4 font-semibold text-blue-600">
                                    {{ number_format($cc->so_cong ?? 0, 2) }}</td>
                                <td class="px-4 py-4">
                                    @php
                                        $statusColors = [
                                            'dung_gio' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
                                            'di_muon' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300',
                                            've_som' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300',
                                            'vang_mat' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
                                        ];
                                        $statusTexts = [
                                            'dung_gio' => '✅ Đúng giờ',
                                            'di_muon' => '⚠️ Đi muộn',
                                            've_som' => '🔻 Về sớm',
                                            'vang_mat' => '❌ Vắng mặt',
                                        ];
                                    @endphp
                                    <span
                                        class="px-2 py-1 rounded text-xs {{ $statusColors[$cc->trang_thai] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $statusTexts[$cc->trang_thai] ?? $cc->trang_thai }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    @if ($cc->ghi_chu)
                                        <button type="button" onclick="showReason('{{ addslashes($cc->ghi_chu) }}')"
                                            class="text-blue-600 hover:underline text-sm">
                                            Xem lý do
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-sm">--</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="relative">
                                        <button onclick="toggleDropdown(this)"
                                            class="p-1.5 text-gray-500 hover:bg-gray-100 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                                </path>
                                            </svg>
                                        </button>
                                        <div
                                            class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 rounded-lg shadow-lg hidden z-10 dropdown-menu border border-gray-200 dark:border-gray-700">
                                            <a href="{{ route('admin.cham-cong.show', $cc->id) }}"
                                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                Xem chi tiết
                                            </a>
                                            @if ($cc->ghi_chu)
                                                <button onclick="showReason('{{ addslashes($cc->ghi_chu) }}')"
                                                    class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                        </path>
                                                    </svg>
                                                    Xem lý do
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-10 text-gray-500">📭 Chưa có dữ liệu chấm công
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PHÂN TRANG --}}
            @if (isset($chamCongs) && $chamCongs->hasPages())
                <div class="p-5 border-t border-gray-200 dark:border-gray-700">
                    {{ $chamCongs->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL LÝ DO --}}
    <div id="reasonModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <h3 class="text-lg font-bold mb-4">Lý do</h3>
            <p id="reasonText" class="mb-4"></p>
            <button onclick="closeReasonModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Đóng</button>
        </div>
    </div>

    {{-- MODAL BÁO CÁO --}}
    <div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <h3 class="text-lg font-bold mb-4">Xuất báo cáo</h3>
            <div>
                <div class="mb-4">
                    <label class="block mb-2">Từ ngày</label>
                    <input type="date" id="report_start_date" class="w-full px-3 py-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label class="block mb-2">Đến ngày</label>
                    <input type="date" id="report_end_date" class="w-full px-3 py-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label class="block mb-2">Định dạng</label>
                    <select id="report_format" class="w-full px-3 py-2 border rounded-lg">
                        <option value="excel">Excel (.xlsx)</option>
                        <option value="pdf">PDF</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeReportModal()"
                        class="px-4 py-2 bg-gray-300 rounded-lg">Đóng</button>
                    <button type="button" onclick="submitReport()"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg">Xuất</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function toggleDropdown(btn) {
            const dropdown = btn.nextElementSibling;
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (menu !== dropdown) menu.classList.add('hidden');
            });
            dropdown.classList.toggle('hidden');
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.relative')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        });

        function showReason(reason) {
            const modal = document.getElementById('reasonModal');
            document.getElementById('reasonText').textContent = reason;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeReasonModal() {
            const modal = document.getElementById('reasonModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openReportModal() {
            const modal = document.getElementById('reportModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeReportModal() {
            const modal = document.getElementById('reportModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function submitReport() {
            const startDate = document.getElementById('report_start_date').value;
            const endDate = document.getElementById('report_end_date').value;
            const format = document.getElementById('report_format').value;

            if (!startDate || !endDate) {
                alert('Vui lòng chọn ngày bắt đầu và kết thúc');
                return;
            }

            let url = `{{ route('admin.cham-cong.export') }}?tu_ngay=${startDate}&den_ngay=${endDate}`;

            if (format === 'pdf') {
                alert('Tính năng PDF đang phát triển');
                return;
            }

            window.open(url, '_blank');
            closeReportModal();
        }

        function exportData() {
            const params = new URLSearchParams(window.location.search);
            window.open(`/admin/cham-cong/export?${params.toString()}`, '_blank');
        }
    </script>
@endpush