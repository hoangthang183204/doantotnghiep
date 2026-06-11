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
            <p class="text-sm text-red-600">Chờ phê duyệt</p>
            <h3 class="text-3xl font-bold text-red-600 mt-2">{{ $donDuyet ?? 0 }}</h3>
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
                    <select name="phong_ban_id" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                        <option value="">-- Tất cả phòng ban --</option>
                        @foreach($phongBan ?? [] as $pb)
                        <option value="{{ $pb->id }}" {{ request('phong_ban_id') == $pb->id ? 'selected' : '' }}>
                            {{ $pb->ten_phong_ban }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">Trạng thái</label>
                    <select name="trang_thai" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="binh_thuong" {{ request('trang_thai') == 'binh_thuong' ? 'selected' : '' }}>🟢 Bình thường</option>
                        <option value="di_muon" {{ request('trang_thai') == 'di_muon' ? 'selected' : '' }}>🟡 Đi muộn</option>
                        <option value="ve_som" {{ request('trang_thai') == 've_som' ? 'selected' : '' }}>🟠 Về sớm</option>
                        <option value="vang_mat" {{ request('trang_thai') == 'vang_mat' ? 'selected' : '' }}>🔴 Vắng mặt</option>
                        <option value="nghi_phep" {{ request('trang_thai') == 'nghi_phep' ? 'selected' : '' }}>🔵 Nghỉ phép</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">Trạng thái duyệt</label>
                    <select name="trang_thai_duyet" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                        <option value="">-- Tất cả --</option>
                        <option value="3" {{ request('trang_thai_duyet') == '3' ? 'selected' : '' }}>🟡 Chờ duyệt</option>
                        <option value="1" {{ request('trang_thai_duyet') == '1' ? 'selected' : '' }}>🟢 Đã duyệt</option>
                        <option value="2" {{ request('trang_thai_duyet') == '2' ? 'selected' : '' }}>🔴 Từ chối</option>
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
                    <select name="thang" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                        <option value="">-- Chọn tháng --</option>
                        @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('thang') == $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">Năm</label>
                    <select name="nam" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                        <option value="">-- Chọn năm --</option>
                        @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                        <option value="{{ $year }}" {{ request('nam') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="mt-5 flex gap-3">
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    🔍 Tìm kiếm
                </button>
                <a href="{{ route('admin.cham-cong.index') }}" class="px-5 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    🔄 Làm mới
                </a>
                <button type="button" onclick="exportData()" class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    📊 Xuất Excel
                </button>
                <button type="button" class="px-5 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700" data-bs-toggle="modal" data-bs-target="#reportModal">
                    📑 Báo cáo
                </button>
            </div>
        </form>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
    <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-sm">
        <div class="flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button type="button" onclick="document.getElementById('alert-success').remove()" class="font-bold text-green-700">×</button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div id="alert-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-sm">
        <div class="flex justify-between items-center">
            <span>{{ session('error') }}</span>
            <button type="button" onclick="document.getElementById('alert-error').remove()" class="font-bold text-red-700">×</button>
        </div>
    </div>
    @endif

    {{-- BULK ACTIONS --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4">
        <div class="flex items-center gap-4">
            <small class="text-gray-500">
                <span id="selectedCount">0</span> mục được chọn
            </small>
            <div id="bulkActions" class="flex gap-2" style="display: none;">
                <button type="button" onclick="bulkApprove()" class="px-3 py-1 text-sm bg-green-500 text-white rounded-lg hover:bg-green-600">
                    ✅ Duyệt hàng loạt
                </button>
                <button type="button" onclick="bulkReject()" class="px-3 py-1 text-sm bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                    ❌ Từ chối hàng loạt
                </button>
                <button type="button" onclick="bulkDelete()" class="px-3 py-1 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600">
                    🗑️ Hủy hàng loạt
                </button>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <input type="checkbox" id="check-all" class="rounded border-gray-300">
                        </th>
                        <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Nhân viên</th>
                        <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Ngày</th>
                        <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Giờ vào</th>
                        <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Giờ ra</th>
                        <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Số giờ</th>
                        <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Số công</th>
                        <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Trạng thái</th>
                        <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Lý do</th>
                        <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Phê duyệt</th>
                        <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($chamCong ?? [] as $cc)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-4 py-4">
                            <input type="checkbox" class="row-checkbox rounded border-gray-300" value="{{ $cc->id }}">
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset(($cc->nguoiDung->hoSo->anh_dai_dien ?? 'assets/images/default.png')) }}" 
                                     class="w-10 h-10 rounded-full object-cover"
                                     onerror="this.src='{{ asset('assets/images/default.png') }}'">
                                <div>
                                    <h6 class="font-semibold">{{ $cc->nguoiDung->hoSo->ho ?? '' }} {{ $cc->nguoiDung->hoSo->ten ?? '' }}</h6>
                                    <p class="text-xs text-gray-500">Mã: {{ $cc->nguoiDung->hoSo->ma_nhan_vien ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">Phòng: {{ $cc->nguoiDung->phongBan->ten_phong_ban ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            {{ \Carbon\Carbon::parse($cc->ngay_cham_cong)->format('d/m/Y') }}
                            <br><small class="text-gray-500">{{ \Carbon\Carbon::parse($cc->ngay_cham_cong)->locale('vi')->dayName }}</small>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-2 py-1 rounded text-sm {{ $cc->kiemTraDiMuon() ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                                {{ $cc->gio_vao_format }}
                            </span>
                            @if($cc->phut_di_muon > 0)
                            <div class="text-xs text-yellow-600">+{{ $cc->phut_di_muon }}p</div>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-2 py-1 rounded text-sm {{ $cc->kiemTraVeSom() ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                                {{ $cc->gio_ra_format }}
                            </span>
                            @if($cc->phut_ve_som > 0)
                            <div class="text-xs text-yellow-600">-{{ $cc->phut_ve_som }}p</div>
                            @endif
                        </td>
                        <td class="px-4 py-4 font-semibold">{{ number_format($cc->so_gio_lam, 1) }}h</td>
                        <td class="px-4 py-4 font-semibold text-blue-600">{{ number_format($cc->so_cong, 1) }}</td>
                        <td class="px-4 py-4">
                            @php
                                $statusColors = [
                                    'binh_thuong' => 'bg-green-100 text-green-700',
                                    'di_muon' => 'bg-yellow-100 text-yellow-700',
                                    've_som' => 'bg-orange-100 text-orange-700',
                                    'vang_mat' => 'bg-red-100 text-red-700',
                                    'nghi_phep' => 'bg-blue-100 text-blue-700',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded text-xs {{ $statusColors[$cc->trang_thai] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $cc->trang_thai_text }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            @if($cc->ghi_chu)
                            <button type="button" onclick="showReason('{{ addslashes($cc->ghi_chu) }}')" class="text-blue-600 hover:underline text-sm">
                                Xem lý do
                            </button>
                            @else
                            <span class="text-gray-400 text-sm">--</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            @if($cc->trang_thai_duyet == 3)
                            <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-700">Chờ duyệt</span>
                            @elseif($cc->trang_thai_duyet == 1)
                            <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">Đã duyệt</span>
                            @elseif($cc->trang_thai_duyet == 2)
                            <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700">Từ chối</span>
                            @else
                            <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">Chưa gửi</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <div class="relative">
                                <button onclick="toggleDropdown(this)" class="px-3 py-1 text-sm bg-gray-100 rounded-lg hover:bg-gray-200">
                                    ⋯
                                </button>
                                <div class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg hidden z-10 dropdown-menu">
                                    <a href="{{ route('admin.cham-cong.show', $cc->id) }}" class="block px-4 py-2 text-sm hover:bg-gray-100">👁️ Xem chi tiết</a>
                                    @if($cc->trang_thai_duyet == 3 || !$cc->trang_thai_duyet)
                                    <button onclick="pheDuyet({{ $cc->id }}, 1)" class="block w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-gray-100">✅ Phê duyệt</button>
                                    <button onclick="pheDuyet({{ $cc->id }}, 2)" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">❌ Từ chối</button>
                                    @endif
                                    @if($cc->trang_thai_duyet != 4)
                                    <button onclick="pheDuyet({{ $cc->id }}, 4)" class="block w-full text-left px-4 py-2 text-sm text-gray-600 hover:bg-gray-100">🗑️ Hủy</button>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center py-10 text-gray-500">
                            📭 Chưa có dữ liệu chấm công
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PHÂN TRANG --}}
        @if(isset($chamCong) && $chamCong->hasPages())
        <div class="p-5 border-t border-gray-200 dark:border-gray-700">
            {{ $chamCong->links() }}
        </div>
        @endif
    </div>
</div>

{{-- MODAL PHÊ DUYỆT --}}
<div id="pheDuyetModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
        <h3 class="text-lg font-bold mb-4" id="modalTitle">Phê duyệt chấm công</h3>
        <form id="pheDuyetForm" method="POST">
            @csrf
            <input type="hidden" name="trang_thai_duyet" id="trangThaiDuyet">
            <div class="mb-4">
                <label class="block mb-2">Ghi chú</label>
                <textarea name="ghi_chu_phe_duyet" id="ghiChuPheDuyet" rows="3" class="w-full px-3 py-2 border rounded-lg"></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closePheDuyetModal()" class="px-4 py-2 bg-gray-300 rounded-lg">Hủy</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Xác nhận</button>
            </div>
        </form>
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
        <form id="reportForm">
            @csrf
            <div class="mb-4">
                <label class="block mb-2">Từ ngày</label>
                <input type="date" name="start_date" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block mb-2">Đến ngày</label>
                <input type="date" name="end_date" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block mb-2">Định dạng</label>
                <select name="format" class="w-full px-3 py-2 border rounded-lg">
                    <option value="excel">Excel (.xlsx)</option>
                    <option value="pdf">PDF</option>
                </select>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeReportModal()" class="px-4 py-2 bg-gray-300 rounded-lg">Đóng</button>
                <button type="button" onclick="submitReport()" class="px-4 py-2 bg-green-600 text-white rounded-lg">Xuất</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL XÁC NHẬN --}}
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
        <h3 class="text-lg font-bold mb-4">Xác nhận</h3>
        <p id="confirmMessage" class="mb-4"></p>
        <div class="flex justify-end gap-3">
            <button onclick="closeConfirmModal()" class="px-4 py-2 bg-gray-300 rounded-lg">Hủy</button>
            <button id="confirmBtn" class="px-4 py-2 bg-red-600 text-white rounded-lg">Xác nhận</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// DOM Elements
let confirmCallback = null;
let currentPheDuyetId = null;

// Toggle dropdown
function toggleDropdown(btn) {
    const dropdown = btn.nextElementSibling;
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        if (menu !== dropdown) menu.classList.add('hidden');
    });
    dropdown.classList.toggle('hidden');
}

// Close dropdowns on click outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.relative')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    }
});

// Checkbox handling
const selectAllCheckbox = document.getElementById('check-all');
if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener('change', function() {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = this.checked);
        updateBulkActions();
    });
}

document.querySelectorAll('.row-checkbox').forEach(cb => {
    cb.addEventListener('change', function() {
        updateBulkActions();
        if (selectAllCheckbox) {
            const allChecked = document.querySelectorAll('.row-checkbox:checked').length === document.querySelectorAll('.row-checkbox').length;
            selectAllCheckbox.checked = allChecked;
        }
    });
});

function updateBulkActions() {
    const checked = document.querySelectorAll('.row-checkbox:checked');
    const count = checked.length;
    const selectedCountSpan = document.getElementById('selectedCount');
    const bulkActions = document.getElementById('bulkActions');
    if (selectedCountSpan) selectedCountSpan.textContent = count;
    if (bulkActions) bulkActions.style.display = count > 0 ? 'flex' : 'none';
}

function getSelectedIds() {
    return Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
}

// Bulk actions
function bulkApprove() {
    const ids = getSelectedIds();
    if (ids.length === 0) return alert('Vui lòng chọn ít nhất một bản ghi!');
    showConfirm(`Xác nhận phê duyệt ${ids.length} bản ghi?`, () => bulkAction(ids, 1));
}

function bulkReject() {
    const ids = getSelectedIds();
    if (ids.length === 0) return alert('Vui lòng chọn ít nhất một bản ghi!');
    const reason = prompt('Nhập lý do từ chối:');
    if (reason === null) return;
    showConfirm(`Xác nhận từ chối ${ids.length} bản ghi?`, () => bulkAction(ids, 2, reason));
}

function bulkDelete() {
    const ids = getSelectedIds();
    if (ids.length === 0) return alert('Vui lòng chọn ít nhất một bản ghi!');
    showConfirm(`Xác nhận hủy ${ids.length} bản ghi?`, () => bulkAction(ids, 4));
}





// Reason modal
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

// Report modal
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



// Export data
function exportData() {
    const params = new URLSearchParams(window.location.search);
    window.open(`/cham-cong/export?${params.toString()}`, '_blank');
}

// Confirm modal
function showConfirm(message, callback) {
    const modal = document.getElementById('confirmModal');
    document.getElementById('confirmMessage').textContent = message;
    confirmCallback = callback;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    const confirmBtn = document.getElementById('confirmBtn');
    const oldClick = confirmBtn.onclick;
    confirmBtn.onclick = function() {
        if (confirmCallback) confirmCallback();
        closeConfirmModal();
    };
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    confirmCallback = null;
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateBulkActions();
});
</script>
@endpush