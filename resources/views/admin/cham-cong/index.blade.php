@extends('layouts.admin')

@section('title', 'Quản lý chấm công')

@section('content')
    <div class="space-y-6">
        {{-- HEADER --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                        Quản lý chấm công
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">
                        Theo dõi và tra cứu dữ liệu chấm công nhân viên
                    </p>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                    <a href="{{ route('admin.cham-cong.don-ve-som') }}" 
                       class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition flex items-center gap-2 shadow-sm">
                        <i class="fas fa-file-signature"></i>
                        Đơn xin về sớm
                        @if(($donVeSomChoDuyet ?? 0) > 0)
                            <span class="bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">{{ $donVeSomChoDuyet }}</span>
                        @endif
                    </a>
                    <button type="button" onclick="exportData()"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition flex items-center gap-2 shadow-sm">
                        <i class="fas fa-file-excel"></i>
                        Xuất Excel
                    </button>
                    <button type="button" onclick="openReportModal()"
                        class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition flex items-center gap-2 shadow-sm">
                        <i class="fas fa-chart-bar"></i>
                        Báo cáo
                    </button>
                </div>
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

        {{-- THỐNG KÊ ĐƠN XIN VỀ SỚM --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl shadow-sm p-5 border border-yellow-200 dark:border-yellow-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-yellow-600 dark:text-yellow-400">
                            <i class="fas fa-clock mr-1"></i> Đơn xin về sớm chờ duyệt
                        </p>
                        <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $donVeSomChoDuyet ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-200 dark:bg-yellow-800/50 rounded-full flex items-center justify-center">
                        <i class="fas fa-file-signature text-yellow-600 dark:text-yellow-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-green-50 dark:bg-green-900/20 rounded-xl shadow-sm p-5 border border-green-200 dark:border-green-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-600 dark:text-green-400">
                            <i class="fas fa-check-circle mr-1"></i> Đơn xin về sớm đã duyệt
                        </p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ $donVeSomDaDuyet ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-200 dark:bg-green-800/50 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-double text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- TÌM KIẾM --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
            <form method="GET" action="{{ route('admin.cham-cong.index') }}" id="searchForm">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">Chọn nhân viên</label>
                        <select name="nguoi_dung_id" id="nhanVienSelect" 
                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                            <option value="">-- Tất cả nhân viên --</option>
                            @foreach($nhanViens ?? [] as $nv)
                                <option value="{{ $nv->id }}" {{ request('nguoi_dung_id') == $nv->id ? 'selected' : '' }}>
                                    {{ $nv->ten }} @if($nv->ma_nhan_vien) ({{ $nv->ma_nhan_vien }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">Từ ngày</label>
                        <input type="date" name="tu_ngay" id="tuNgay" value="{{ request('tu_ngay') }}"
                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">Đến ngày</label>
                        <input type="date" name="den_ngay" id="denNgay" value="{{ request('den_ngay') }}"
                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">Tên nhân viên</label>
                        <input type="text" name="ten_nhan_vien" value="{{ request('ten_nhan_vien') }}"
                            placeholder="Nhập tên..."
                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                    </div>
                </div>
                <div class="mt-5 flex gap-3 flex-wrap">
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        🔍 Tìm kiếm
                    </button>
                    <a href="{{ route('admin.cham-cong.index') }}"
                        class="px-5 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                        🔄 Làm mới
                    </a>
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

        {{-- TABLE - Hiển thị mỗi nhân viên 1 dòng --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Nhân viên</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Mã NV</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Phòng ban</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Tổng ngày công</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Ngày chấm gần nhất</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($chamCongs ?? [] as $user)
                            @php
                                $hoSo = $user->hoSo ?? null;
                                $hoTen = $hoSo ? trim(($hoSo->ho ?? '') . ' ' . ($hoSo->ten ?? '')) : ($user->ten_dang_nhap ?? 'N/A');
                                $maNV = $hoSo ? ($hoSo->ma_nhan_vien ?? 'N/A') : 'N/A';
                                $tenPhongBan = $user->phongBan ? ($user->phongBan->ten_phong_ban ?? 'N/A') : 'N/A';
                                
                                $hasAvatar = $hoSo && $hoSo->anh_dai_dien && file_exists(public_path('storage/' . $hoSo->anh_dai_dien));
                                $avatar = $hasAvatar ? asset('storage/' . $hoSo->anh_dai_dien) : null;
                                $initial = strtoupper(substr($hoTen, 0, 1));
                                
                                $lastChamCong = \App\Models\ChamCong::where('nguoi_dung_id', $user->id)
                                    ->orderBy('ngay_cham_cong', 'desc')
                                    ->first();
                                    
                                $tongNgayCong = \App\Models\ChamCong::where('nguoi_dung_id', $user->id)
                                    ->whereMonth('ngay_cham_cong', now()->month)
                                    ->count();
                                    
                                $ngayGanNhat = $lastChamCong ? \Carbon\Carbon::parse($lastChamCong->ngay_cham_cong)->format('d/m/Y') : 'Chưa chấm công';
                            @endphp
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($avatar)
                                            <img src="{{ $avatar }}" alt="{{ $hoTen }}"
                                                class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600 shadow-sm">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                                {{ $initial }}
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="font-semibold">{{ $hoTen }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">{{ $maNV }}</td>
                                <td class="px-4 py-4">{{ $tenPhongBan }}</td>
                                <td class="px-4 py-4 font-semibold text-blue-600">{{ $tongNgayCong }} ngày</td>
                                <td class="px-4 py-4">{{ $ngayGanNhat }}</td>
                                <td class="px-4 py-4">
                                    <button onclick="openNhanVienModal('{{ addslashes($hoTen) }}', {{ $user->id }})" 
                                            class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition" 
                                            title="Xem chi tiết">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-gray-500">📭 Chưa có nhân viên nào chấm công</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PHÂN TRANG --}}
            @if (isset($chamCongs) && method_exists($chamCongs, 'hasPages') && $chamCongs->hasPages())
                <div class="p-5 border-t border-gray-200 dark:border-gray-700">
                    {{ $chamCongs->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL LÝ DO --}}
    <div id="reasonModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6">
            <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">Lý do</h3>
            <p id="reasonText" class="mb-4 text-gray-700 dark:text-gray-300"></p>
            <button onclick="closeReasonModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Đóng</button>
        </div>
    </div>

    {{-- MODAL BÁO CÁO --}}
    <div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6">
            <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">Xuất báo cáo</h3>
            <div>
                <div class="mb-4">
                    <label class="block mb-2 text-gray-700 dark:text-gray-300">Từ ngày</label>
                    <input type="date" id="report_start_date" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600" required>
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-gray-700 dark:text-gray-300">Đến ngày</label>
                    <input type="date" id="report_end_date" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600" required>
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-gray-700 dark:text-gray-300">Định dạng</label>
                    <select id="report_format" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                        <option value="excel">Excel (.xlsx)</option>
                        <option value="pdf">PDF</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeReportModal()" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500">Đóng</button>
                    <button type="button" onclick="submitReport()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Xuất</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL HIỂN THỊ CHẤM CÔNG CỦA NHÂN VIÊN (CÓ PHÂN TRANG) --}}
    <div id="nhanVienModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white" id="modalTitle">
                        📋 Danh sách chấm công
                    </h3>
                    <p class="text-sm text-gray-500 mt-1" id="modalSubtitle"></p>
                </div>
                <button onclick="closeNhanVienModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                    ×
                </button>
            </div>
            <div class="p-6 overflow-auto flex-1">
                <div id="loadingNhanVien" class="text-center py-10">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-3 text-gray-500">Đang tải dữ liệu...</p>
                </div>
                <div id="nhanVienContent" class="hidden">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm text-gray-500">Tổng số: <strong id="totalRecords">0</strong> bản ghi</span>
                        <div class="flex items-center gap-3">
                            <select id="perPageSelect" class="px-3 py-1.5 border rounded-lg text-sm dark:bg-gray-700 dark:border-gray-600">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                            <button onclick="exportNhanVienData()" class="px-3 py-1.5 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
                                <i class="fas fa-file-excel"></i> Xuất Excel
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 dark:border-gray-700 rounded-lg">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Ngày</th>
                                    <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Thứ</th>
                                    <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Giờ vào</th>
                                    <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Giờ ra</th>
                                    <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Số giờ</th>
                                    <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Số công</th>
                                    <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Trạng thái</th>
                                    <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="nhanVienTableBody">
                                <!-- Dữ liệu sẽ được inject bằng JS -->
                            </tbody>
                        </table>
                    </div>
                    {{-- PHÂN TRANG CỦA MODAL --}}
                    <div id="modalPagination" class="flex justify-between items-center mt-4 hidden">
                        <div class="text-sm text-gray-500">
                            Hiển thị <span id="paginationFrom">0</span> - <span id="paginationTo">0</span> trong tổng <span id="paginationTotal">0</span> bản ghi
                        </div>
                        <div class="flex gap-2">
                            <button id="prevPageBtn" onclick="loadPage(currentPage - 1)" 
                                    class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded-lg text-sm hover:bg-gray-300 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                ← Trước
                            </button>
                            <span id="pageInfo" class="px-3 py-1 text-sm">Trang 1 / 1</span>
                            <button id="nextPageBtn" onclick="loadPage(currentPage + 1)" 
                                    class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded-lg text-sm hover:bg-gray-300 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                Sau →
                            </button>
                        </div>
                    </div>
                    <div id="emptyNhanVien" class="text-center py-10 text-gray-500 hidden">
                        📭 Nhân viên này chưa có dữ liệu chấm công
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                <button onclick="closeNhanVienModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Đóng
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Biến toàn cục cho modal
        let currentPage = 1;
        let currentUserId = null;
        let totalPages = 1;
        let perPage = 10;

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
            window.open(`{{ route('admin.cham-cong.export') }}?${params.toString()}`, '_blank');
        }

        // ===== MODAL NHÂN VIÊN =====
        function openNhanVienModal(tenNhanVien, userId) {
            const modal = document.getElementById('nhanVienModal');
            
            // Lưu thông tin
            currentUserId = userId;
            currentPage = 1;
            perPage = parseInt(document.getElementById('perPageSelect').value) || 10;
            modal.dataset.userId = userId;
            modal.dataset.tenNhanVien = tenNhanVien;
            
            // Reset state
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.getElementById('loadingNhanVien').classList.remove('hidden');
            document.getElementById('nhanVienContent').classList.add('hidden');
            document.getElementById('emptyNhanVien').classList.add('hidden');
            document.getElementById('nhanVienTableBody').innerHTML = '';
            document.getElementById('modalPagination').classList.add('hidden');
            
            // Cập nhật tiêu đề
            document.getElementById('modalTitle').textContent = `📋 Danh sách chấm công`;
            document.getElementById('modalSubtitle').textContent = `👤 Đang tải thông tin nhân viên...`;

            // Tải trang đầu tiên
            loadPage(1);
        }

        function loadPage(page) {
            if (page < 1 || page > totalPages) return;
            
            currentPage = page;
            
            // Hiển thị loading
            document.getElementById('loadingNhanVien').classList.remove('hidden');
            document.getElementById('nhanVienContent').classList.add('hidden');
            
            const userId = currentUserId;
            const tuNgay = document.getElementById('tuNgay').value;
            const denNgay = document.getElementById('denNgay').value;

            let url = `{{ route('admin.cham-cong.get-by-nhan-vien') }}?nguoi_dung_id=${userId}&page=${page}&per_page=${perPage}`;
            if (tuNgay) url += `&tu_ngay=${tuNgay}`;
            if (denNgay) url += `&den_ngay=${denNgay}`;

            fetch(url)
                .then(res => res.json())
                .then(response => {
                    document.getElementById('loadingNhanVien').classList.add('hidden');
                    document.getElementById('nhanVienContent').classList.remove('hidden');

                    if (response.employee_info) {
                        const info = response.employee_info;
                        document.getElementById('modalSubtitle').textContent = 
                            `👤 ${info.ho_ten} | Mã: ${info.ma_nhan_vien || 'N/A'} | Phòng: ${info.phong_ban || 'N/A'}`;
                    }

                    if (response.success && response.data.length > 0) {
                        const tbody = document.getElementById('nhanVienTableBody');
                        tbody.innerHTML = '';
                        
                        response.data.forEach(item => {
                            const tr = document.createElement('tr');
                            tr.className = 'border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition';
                            tr.innerHTML = `
                                <td class="px-4 py-3">${item.ngay_cham_cong}</td>
                                <td class="px-4 py-3 text-gray-500 text-sm">${item.thu}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded text-sm ${item.phut_di_muon > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700'}">
                                        ${item.gio_vao}
                                    </span>
                                    ${item.phut_di_muon > 0 ? `<div class="text-xs text-yellow-600">+${item.phut_di_muon}p</div>` : ''}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded text-sm ${item.phut_ve_som > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700'}">
                                        ${item.gio_ra}
                                    </span>
                                    ${item.phut_ve_som > 0 ? `<div class="text-xs text-yellow-600">-${item.phut_ve_som}p</div>` : ''}
                                </td>
                                <td class="px-4 py-3 font-semibold">${item.so_gio_lam}h</td>
                                <td class="px-4 py-3 font-semibold text-blue-600">${item.so_cong}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded text-xs ${item.trang_thai_class}">
                                        ${item.trang_thai_text}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="${item.url_show}" class="text-blue-600 hover:underline text-sm">
                                        Chi tiết
                                    </a>
                                </td>
                            `;
                            tbody.appendChild(tr);
                        });

                        // Cập nhật phân trang
                        totalPages = response.last_page || 1;
                        const total = response.total || 0;
                        const from = ((response.current_page - 1) * response.per_page) + 1;
                        const to = Math.min(response.current_page * response.per_page, total);
                        
                        document.getElementById('totalRecords').textContent = total;
                        document.getElementById('paginationFrom').textContent = total > 0 ? from : 0;
                        document.getElementById('paginationTo').textContent = total > 0 ? to : 0;
                        document.getElementById('paginationTotal').textContent = total;
                        document.getElementById('pageInfo').textContent = `Trang ${response.current_page} / ${response.last_page}`;
                        
                        // Cập nhật nút
                        document.getElementById('prevPageBtn').disabled = response.current_page <= 1;
                        document.getElementById('nextPageBtn').disabled = response.current_page >= response.last_page;
                        
                        document.getElementById('modalPagination').classList.remove('hidden');
                        document.getElementById('emptyNhanVien').classList.add('hidden');
                    } else {
                        document.getElementById('emptyNhanVien').classList.remove('hidden');
                        document.getElementById('emptyNhanVien').innerHTML = '📭 Nhân viên này chưa có dữ liệu chấm công';
                        document.getElementById('modalPagination').classList.add('hidden');
                    }
                })
                .catch(error => {
                    document.getElementById('loadingNhanVien').classList.add('hidden');
                    document.getElementById('nhanVienContent').classList.remove('hidden');
                    document.getElementById('emptyNhanVien').classList.remove('hidden');
                    document.getElementById('emptyNhanVien').innerHTML = '❌ Có lỗi xảy ra khi tải dữ liệu';
                    console.error('Error:', error);
                });
        }

        function closeNhanVienModal() {
            const modal = document.getElementById('nhanVienModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Xuất Excel dữ liệu nhân viên (chỉ xuất của nhân viên đang xem)
        function exportNhanVienData() {
            const modal = document.getElementById('nhanVienModal');
            const userId = modal.dataset.userId;
            
            if (!userId) {
                alert('⚠️ Không có dữ liệu nhân viên để xuất!');
                return;
            }
            
            const tuNgay = document.getElementById('tuNgay').value;
            const denNgay = document.getElementById('denNgay').value;
            
            let url = `{{ route('admin.cham-cong.export') }}?nguoi_dung_id=${userId}`;
            if (tuNgay) url += `&tu_ngay=${tuNgay}`;
            if (denNgay) url += `&den_ngay=${denNgay}`;
            
            window.open(url, '_blank');
        }

        // Đóng modal khi click bên ngoài
        document.getElementById('nhanVienModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeNhanVienModal();
            }
        });

        // Thay đổi số bản ghi mỗi trang
        document.getElementById('perPageSelect')?.addEventListener('change', function() {
            perPage = parseInt(this.value);
            loadPage(1);
        });

        // Enter key to search
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const activeElement = document.activeElement;
                if (activeElement && activeElement.closest('#searchForm')) {
                    e.preventDefault();
                    document.getElementById('searchForm').submit();
                }
            }
        });
    </script>
@endpush