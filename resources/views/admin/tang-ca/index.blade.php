@extends('layouts.admin')

@section('title', 'Phê duyệt tăng ca')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Phê duyệt tăng ca</h1>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Quản lý và xét duyệt đơn đăng ký tăng ca nhân viên</p>
                </div>
            </div>
        </div>

        {{-- THỐNG KÊ --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Tổng đơn</p>
                <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-2">{{ $thongKe['tong'] ?? 0 }}</h3>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
                <p class="text-sm text-yellow-600">Chờ duyệt</p>
                <h3 class="text-3xl font-bold text-yellow-500 mt-2">{{ $thongKe['cho_duyet'] ?? 0 }}</h3>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
                <p class="text-sm text-green-600">Đã duyệt</p>
                <h3 class="text-3xl font-bold text-green-600 mt-2">{{ $thongKe['da_duyet'] ?? 0 }}</h3>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
                <p class="text-sm text-red-500">Từ chối</p>
                <h3 class="text-3xl font-bold text-red-500 mt-2">{{ $thongKe['tu_choi'] ?? 0 }}</h3>
            </div>
        </div>

        {{-- THÔNG BÁO --}}
        @if (session('success'))
            <div id="alert-success"
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-sm flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button onclick="document.getElementById('alert-success').remove()" class="font-bold">×</button>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-sm">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-sm">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- BỘ LỌC TÌM KIẾM --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
            <form method="GET" action="{{ route('admin.tang-ca.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <div>
                        <label class="block mb-2 font-medium text-sm text-gray-700 dark:text-gray-300">Tên nhân viên</label>
                        <input type="text" name="ten_nhan_vien" value="{{ request('ten_nhan_vien') }}"
                            placeholder="Nhập tên..."
                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-sm text-gray-700 dark:text-gray-300">Phòng ban</label>
                        <select name="phong_ban_id"
                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Tất cả --</option>
                            @foreach ($phongBans ?? [] as $pb)
                                <option value="{{ $pb->id }}"
                                    {{ request('phong_ban_id') == $pb->id ? 'selected' : '' }}>
                                    {{ $pb->ten_phong_ban }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-sm text-gray-700 dark:text-gray-300">Trạng thái</label>
                        <select name="trang_thai"
                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Tất cả --</option>
                            <option value="cho_duyet" {{ request('trang_thai') == 'cho_duyet' ? 'selected' : '' }}>⏳ Chờ
                                duyệt</option>
                            <option value="da_duyet" {{ request('trang_thai') == 'da_duyet' ? 'selected' : '' }}>✅ Đã duyệt
                            </option>
                            <option value="tu_choi" {{ request('trang_thai') == 'tu_choi' ? 'selected' : '' }}>❌ Từ chối
                            </option>
                            <option value="huy" {{ request('trang_thai') == 'huy' ? 'selected' : '' }}>🗑️ Đã hủy
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-sm text-gray-700 dark:text-gray-300">Từ ngày</label>
                        <input type="date" name="tu_ngay" value="{{ request('tu_ngay') }}"
                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-sm text-gray-700 dark:text-gray-300">Đến ngày</label>
                        <input type="date" name="den_ngay" value="{{ request('den_ngay') }}"
                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                            🔍 Tìm kiếm
                        </button>
                        <a href="{{ route('admin.tang-ca.index') }}"
                            class="px-5 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                            🔄 Làm mới
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- BULK ACTIONS TOOLBAR --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4">
            <div class="flex items-center gap-4 flex-wrap">
                <input type="checkbox" id="check-all" class="w-4 h-4 rounded border-gray-300 cursor-pointer">
                <label for="check-all" class="text-sm text-gray-600 dark:text-gray-300 cursor-pointer">Chọn tất cả</label>
                <span class="text-sm text-gray-500">(<span id="selectedCount">0</span> mục được chọn)</span>
                <div id="bulkActions" class="flex gap-2 ml-auto" style="display: none;">
                    <button type="button" onclick="bulkApprove()"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition">
                        ✅ Duyệt hàng loạt
                    </button>
                    <button type="button" onclick="bulkReject()"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition">
                        ❌ Từ chối hàng loạt
                    </button>
                </div>
            </div>
        </div>

        {{-- BẢNG DANH SÁCH --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left w-10"></th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200 text-sm">Nhân viên</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200 text-sm">Ngày tăng ca</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200 text-sm">Giờ bắt đầu</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200 text-sm">Giờ kết thúc</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200 text-sm">Số giờ</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200 text-sm">Loại</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200 text-sm">Trạng thái</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200 text-sm">Lý do</th>
                            <th class="px-4 py-3 text-center text-gray-700 dark:text-gray-200 text-sm">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donTangCa ?? [] as $item)
                            @php
                                $nguoiDung = $item->nguoi_dung ?? null;
                                $hoSo = $nguoiDung ? $nguoiDung->hoSo ?? null : null;

                                $hoTen = '';
                                if ($hoSo) {
                                    $hoTen = trim(($hoSo->ho ?? '') . ' ' . ($hoSo->ten ?? ''));
                                }
                                if (empty($hoTen) && $nguoiDung) {
                                    $hoTen = $nguoiDung->ten_dang_nhap ?? 'N/A';
                                }
                                if (empty($hoTen)) {
                                    $hoTen = 'NV#' . ($item->nguoi_dung_id ?? '?');
                                }

                                $initial = strtoupper(substr($hoTen, 0, 1));
                                $maNV = $hoSo ? $hoSo->ma_nhan_vien ?? 'N/A' : 'N/A';
                                $tenPhongBan =
                                    $nguoiDung && $nguoiDung->phongBan
                                        ? $nguoiDung->phongBan->ten_phong_ban ?? 'N/A'
                                        : 'N/A';

                                $loaiClass = match ($item->loai_tang_ca) {
                                    'ngay_thuong' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                    'ngay_nghi'
                                        => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                    'le_tet' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                    default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                };
                                $loaiLabel = match ($item->loai_tang_ca) {
                                    'ngay_thuong' => 'Ngày thường',
                                    'ngay_nghi' => 'Ngày nghỉ',
                                    'le_tet' => 'Lễ / Tết',
                                    default => $item->loai_tang_ca,
                                };

                                $ttClass = match ($item->trang_thai) {
                                    'cho_duyet'
                                        => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                    'da_duyet'
                                        => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                    'tu_choi' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                    'huy' => 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400',
                                    default => 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400',
                                };
                                $ttLabel = match ($item->trang_thai) {
                                    'cho_duyet' => 'Chờ duyệt',
                                    'da_duyet' => 'Đã duyệt',
                                    'tu_choi' => 'Từ chối',
                                    'huy' => 'Đã hủy',
                                    default => $item->trang_thai,
                                };
                            @endphp
                            <tr
                                class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-4 py-3">
                                    @if ($item->trang_thai === 'cho_duyet')
                                        <input type="checkbox"
                                            class="row-check w-4 h-4 rounded border-gray-300 cursor-pointer"
                                            value="{{ $item->id }}">
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                            {{ $initial }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-800 dark:text-white">{{ $hoTen }}
                                            </div>
                                            <div class="text-xs text-gray-500">Mã NV: {{ $maNV }}</div>
                                            <div class="text-xs text-gray-500">Phòng: {{ $tenPhongBan }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium">
                                        {{ \Carbon\Carbon::parse($item->ngay_tang_ca)->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-400">
                                        {{ ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'][\Carbon\Carbon::parse($item->ngay_tang_ca)->dayOfWeek] }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="px-2 py-1 rounded text-xs bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                        {{ \Carbon\Carbon::parse($item->gio_bat_dau)->format('H:i') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="px-2 py-1 rounded text-xs bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                        {{ \Carbon\Carbon::parse($item->gio_ket_thuc)->format('H:i') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="font-semibold text-blue-600 dark:text-blue-400">{{ number_format($item->so_gio_tang_ca, 1) }}h</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium {{ $loaiClass }}">{{ $loaiLabel }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium {{ $ttClass }}">{{ $ttLabel }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($item->ly_do_tang_ca)
                                        <button type="button"
                                            onclick="showReason('{{ addslashes($item->ly_do_tang_ca) }}')"
                                            class="text-blue-600 hover:underline text-sm">Xem lý do</button>
                                    @else
                                        <span class="text-gray-400 text-sm">--</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center gap-1.5">
                                        <a href="{{ route('admin.tang-ca.show', $item->id) }}"
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
                                        @if ($item->trang_thai === 'cho_duyet')
                                            <button onclick="pheDuyet({{ $item->id }}, 'da_duyet')"
                                                class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg transition"
                                                title="Phê duyệt">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                            <button onclick="pheDuyet({{ $item->id }}, 'tu_choi')"
                                                class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition"
                                                title="Từ chối">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-10 text-center text-gray-400">📭 Không có đơn tăng ca
                                    nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (isset($donTangCa) && $donTangCa->hasPages())
                <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700">{{ $donTangCa->links() }}</div>
            @endif
        </div>
    </div>

    {{-- MODAL LÝ DO --}}
    <div id="reasonModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">📝 Lý do tăng ca</h3>
            <p id="reasonText" class="text-gray-600 dark:text-gray-300 mb-4"></p>
            <div class="flex justify-end">
                <button onclick="closeReasonModal()"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">Đóng</button>
            </div>
        </div>
    </div>

    {{-- MODAL PHÊ DUYỆT / TỪ CHỐI --}}
    <div id="pheDuyetModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6">
            <h3 id="modalTitle" class="text-lg font-bold text-gray-800 dark:text-white mb-4">Phê duyệt tăng ca</h3>
            <form id="pheDuyetForm" method="POST">
                @csrf
                <input type="hidden" name="trang_thai" id="trangThaiDuyet">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ghi chú (nếu có)</label>
                    <textarea name="ly_do_tu_choi" id="ghiChuPheDuyet" rows="4" placeholder="Nhập lý do từ chối hoặc ghi chú..."
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closePheDuyetModal()"
                        class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">Huỷ</button>
                    <button type="submit" id="btnPheDuyet"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">Xác nhận</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL XÁC NHẬN HÀNG LOẠT --}}
    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Xác nhận</h3>
            <p id="confirmMessage" class="text-gray-600 dark:text-gray-300 mb-6"></p>
            <div class="flex justify-end gap-3">
                <button onclick="closeConfirmModal()"
                    class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">Huỷ</button>
                <button id="confirmBtn"
                    class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">Xác nhận</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let confirmCallback = null;
            let currentPheDuyetId = null;

            const modalPheDuyet = document.getElementById('pheDuyetModal');
            const modalReason = document.getElementById('reasonModal');
            const modalConfirm = document.getElementById('confirmModal');

            // ⭐ LẤY BASE URL
            const baseUrl = window.location.origin;

            document.addEventListener('DOMContentLoaded', function() {
                const selectAllCheckbox = document.getElementById('check-all');
                if (selectAllCheckbox) {
                    selectAllCheckbox.addEventListener('change', function() {
                        document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
                        updateBulkActions();
                    });
                }
                document.addEventListener('change', function(e) {
                    if (e.target.classList.contains('row-check')) {
                        updateBulkActions();
                        if (selectAllCheckbox) {
                            const allChecked = document.querySelectorAll('.row-check:checked').length ===
                                document.querySelectorAll('.row-check').length;
                            selectAllCheckbox.checked = allChecked;
                        }
                    }
                });
            });

            function updateBulkActions() {
                const checkedBoxes = document.querySelectorAll('.row-check:checked');
                const count = checkedBoxes.length;
                const selectedCount = document.getElementById('selectedCount');
                const bulkActions = document.getElementById('bulkActions');
                if (selectedCount) selectedCount.textContent = count;
                if (bulkActions) bulkActions.style.display = count > 0 ? 'flex' : 'none';
            }

            function getSelectedIds() {
                return Array.from(document.querySelectorAll('.row-check:checked')).map(cb => cb.value);
            }

            // ⭐ BULK ACTION
            function bulkAction(ids, action, successMessage, reason = null) {
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('ids', JSON.stringify(ids));
                formData.append('action', action);
                if (reason) formData.append('reason', reason);

                const url = `${baseUrl}/admin/tang-ca/duyet-hang-loat`;

                fetch(url, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message || successMessage);
                            location.reload();
                        } else {
                            alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi thực hiện thao tác!');
                    });
            }

            function bulkApprove() {
                const ids = getSelectedIds();
                if (ids.length === 0) {
                    alert('Vui lòng chọn ít nhất một bản ghi!');
                    return;
                }
                showConfirm(`Xác nhận phê duyệt ${ids.length} đơn tăng ca?`, () => bulkAction(ids, 'da_duyet',
                    '✅ Phê duyệt hàng loạt thành công!'));
            }

            function bulkReject() {
                const ids = getSelectedIds();
                if (ids.length === 0) {
                    alert('Vui lòng chọn ít nhất một bản ghi!');
                    return;
                }
                const reason = prompt('Nhập lý do từ chối:');
                if (reason === null) return;
                if (!reason.trim()) {
                    alert('Vui lòng nhập lý do từ chối!');
                    return;
                }
                showConfirm(`Xác nhận từ chối ${ids.length} đơn tăng ca?`, () => bulkAction(ids, 'tu_choi',
                    '❌ Từ chối hàng loạt thành công!', reason));
            }

            // ⭐ PHÊ DUYỆT ĐƠN
            function pheDuyet(id, trangThai) {
                currentPheDuyetId = id;
                const modalTitle = document.getElementById('modalTitle');
                const btnPheDuyet = document.getElementById('btnPheDuyet');
                const trangThaiInput = document.getElementById('trangThaiDuyet');
                const ghiChu = document.getElementById('ghiChuPheDuyet');
                const form = document.getElementById('pheDuyetForm');

                trangThaiInput.value = trangThai;

                if (trangThai === 'da_duyet') {
                    modalTitle.textContent = '✅ Phê duyệt tăng ca';
                    btnPheDuyet.textContent = 'Xác nhận duyệt';
                    btnPheDuyet.className = 'px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition';
                    ghiChu.placeholder = 'Nhập ghi chú (nếu có)...';
                    ghiChu.removeAttribute('required');
                    form.action = `${baseUrl}/admin/tang-ca/${id}/duyet`;
                } else {
                    modalTitle.textContent = '❌ Từ chối tăng ca';
                    btnPheDuyet.textContent = 'Xác nhận từ chối';
                    btnPheDuyet.className = 'px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition';
                    ghiChu.placeholder = 'Nhập lý do từ chối (bắt buộc)...';
                    ghiChu.setAttribute('required', 'required');
                    form.action = `${baseUrl}/admin/tang-ca/${id}/tu-choi`;
                }

                form.reset();
                if (modalPheDuyet) {
                    modalPheDuyet.classList.remove('hidden');
                    modalPheDuyet.classList.add('flex');
                }
            }

            // ⭐ SUBMIT FORM - SỬA LỖI KHÔNG TỰ RELOAD
            document.getElementById('pheDuyetForm')?.addEventListener('submit', function(e) {
                e.preventDefault();

                const trangThai = document.getElementById('trangThaiDuyet').value;
                const lyDo = document.getElementById('ghiChuPheDuyet').value;

                if (trangThai === 'tu_choi' && !lyDo.trim()) {
                    alert('Vui lòng nhập lý do từ chối!');
                    return;
                }

                // Nếu là từ chối, thêm hidden field cho ly_do_tu_choi
                if (trangThai === 'tu_choi') {
                    const oldInput = this.querySelector('input[name="ly_do_tu_choi"]');
                    if (oldInput) oldInput.remove();

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ly_do_tu_choi';
                    input.value = lyDo;
                    this.appendChild(input);
                }

                // ⭐ HIỂN THỊ LOADING
                const btn = document.getElementById('btnPheDuyet');
                const originalText = btn.textContent;
                btn.textContent = '⏳ Đang xử lý...';
                btn.disabled = true;

                const formData = new FormData(this);

                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json; charset=utf-8'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message || '✅ Xử lý thành công!');
                            // ⭐ RELOAD TRANG SAU KHI THÀNH CÔNG
                            window.location.reload();
                        } else {
                            alert(data.message || '❌ Có lỗi xảy ra!');
                            btn.textContent = originalText;
                            btn.disabled = false;
                        }
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        alert('Có lỗi xảy ra: ' + err.message);
                        btn.textContent = originalText;
                        btn.disabled = false;
                    });
            });

            function closePheDuyetModal() {
                if (modalPheDuyet) {
                    modalPheDuyet.classList.add('hidden');
                    modalPheDuyet.classList.remove('flex');
                }
                const form = document.getElementById('pheDuyetForm');
                if (form) {
                    form.reset();
                    form.action = '';
                }
            }

            function showReason(reason) {
                const reasonText = document.getElementById('reasonText');
                if (reasonText) reasonText.textContent = reason;
                if (modalReason) {
                    modalReason.classList.remove('hidden');
                    modalReason.classList.add('flex');
                }
            }

            function closeReasonModal() {
                if (modalReason) {
                    modalReason.classList.add('hidden');
                    modalReason.classList.remove('flex');
                }
            }

            function showConfirm(message, callback) {
                const confirmMessage = document.getElementById('confirmMessage');
                const confirmBtn = document.getElementById('confirmBtn');
                if (confirmMessage) confirmMessage.textContent = message;
                confirmCallback = callback;
                if (modalConfirm) {
                    modalConfirm.classList.remove('hidden');
                    modalConfirm.classList.add('flex');
                }
                if (confirmBtn) {
                    confirmBtn.onclick = function() {
                        if (confirmCallback) confirmCallback();
                        closeConfirmModal();
                    };
                }
            }

            function closeConfirmModal() {
                if (modalConfirm) {
                    modalConfirm.classList.add('hidden');
                    modalConfirm.classList.remove('flex');
                }
                confirmCallback = null;
            }

            // Đóng modal khi click ra ngoài
            if (modalPheDuyet) {
                modalPheDuyet.addEventListener('click', function(e) {
                    if (e.target === this) closePheDuyetModal();
                });
            }
            if (modalReason) {
                modalReason.addEventListener('click', function(e) {
                    if (e.target === this) closeReasonModal();
                });
            }
            if (modalConfirm) {
                modalConfirm.addEventListener('click', function(e) {
                    if (e.target === this) closeConfirmModal();
                });
            }

            // ⭐ XỬ LÝ SUBMIT BẰNG ENTER
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closePheDuyetModal();
                    closeReasonModal();
                    closeConfirmModal();
                }
            });

            // ⭐ HÀM DUYỆT ĐƠN TỪ NÚT TRONG BẢNG
            function duyetDon(id) {
                if (!confirm('Bạn có chắc muốn duyệt đơn này?')) return;

                fetch(`${baseUrl}/admin/tang-ca/${id}/duyet`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json; charset=utf-8'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message || 'Đã xử lý thành công');
                        if (data.success) {
                            location.reload();
                        }
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        alert('Có lỗi xảy ra: ' + err.message);
                    });
            }

            // ⭐ HÀM TỪ CHỐI ĐƠN TỪ NÚT TRONG BẢNG
            function tuChoiDon(id) {
                const lyDo = prompt('Nhập lý do từ chối:');
                if (lyDo === null) return;
                if (!lyDo.trim()) {
                    alert('Vui lòng nhập lý do từ chối');
                    return;
                }

                fetch(`${baseUrl}/admin/tang-ca/${id}/tu-choi`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json; charset=utf-8'
                        },
                        body: JSON.stringify({
                            ly_do_tu_choi: lyDo
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message || 'Đã xử lý thành công');
                        if (data.success) {
                            location.reload();
                        }
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        alert('Có lỗi xảy ra: ' + err.message);
                    });
            }
        </script>
    @endpush
@endsection
