{{-- resources/views/employee/tang-ca/index.blade.php --}}
@extends('layouts.employee')

@section('title', 'Tăng ca')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-clock mr-3 text-blue-600 dark:text-blue-400"></i>
                    Tăng ca
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Quản lý kiến nghị và đơn tăng ca của bạn</p>
            </div>
            <a href="{{ route('employee.tang-ca.create') }}"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus-circle"></i>
                Gửi kiến nghị
            </a>
        </div>

        {{-- THỐNG KÊ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $thongKe['tong'] ?? 0 }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tổng yêu cầu</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
                <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $thongKe['cho_duyet'] ?? 0 }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">⏳ Chờ duyệt</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $thongKe['da_duyet'] ?? 0 }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">✅ Đã duyệt</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
                <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                    {{ $donTangCa->filter(function ($item) {
                            return $item->trang_thai == 'da_duyet' &&
                                $item->thuc_hien &&
                                $item->thuc_hien->trang_thai == 'quan_ly_xac_nhan';
                        })->count() }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">✅ Hoàn thành</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
                <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $thongKe['tu_choi'] ?? 0 }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">❌ Từ chối</p>
            </div>
        </div>

        {{-- DANH SÁCH --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <h3 class="font-semibold text-gray-900 dark:text-white">📋 Danh sách</h3>
                <span class="text-sm text-gray-500">Tổng: {{ $donTangCa->total() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Giờ</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Số giờ</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($donTangCa as $don)
                            @php
                                $isKienNghi = is_null($don->ngay_tang_ca);
                                $thucHien = $don->thuc_hien;
                                $daXacNhan = $thucHien && $thucHien->trang_thai === 'quan_ly_xac_nhan';
                                $daNhanVienXacNhan = $thucHien && $thucHien->trang_thai === 'nhan_vien_xac_nhan';
                                $loaiLabels = [
                                    'ngay_thuong' => 'Ngày thường',
                                    'ngay_nghi' => 'Ngày nghỉ',
                                    'le_tet' => 'Lễ, Tết',
                                ];
                                $badgeClasses = [
                                    'cho_duyet' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                    'da_duyet' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                    'tu_choi' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                    'huy' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                ];
                                $trangThaiLabels = [
                                    'cho_duyet' => '⏳ Chờ duyệt',
                                    'da_duyet' => '✅ Đã duyệt',
                                    'tu_choi' => '❌ Từ chối',
                                    'huy' => '🗑️ Đã hủy',
                                ];
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-4 py-3">
                                    @if($isKienNghi)
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                            📝 Kiến nghị
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300">
                                            📄 Đơn tăng ca
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                    @if($isKienNghi)
                                        {{ Carbon\Carbon::parse($don->created_at)->format('d/m/Y') }}
                                    @else
                                        {{ Carbon\Carbon::parse($don->ngay_tang_ca)->format('d/m/Y') }}
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    @if($isKienNghi)
                                        <span class="text-gray-400">---</span>
                                    @else
                                        {{ $don->gio_bat_dau }} - {{ $don->gio_ket_thuc }}
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm font-medium {{ $isKienNghi ? 'text-gray-400' : 'text-blue-600 dark:text-blue-400' }}">
                                    {{ $don->so_gio_tang_ca }} giờ
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $loaiLabels[$don->loai_tang_ca] ?? $don->loai_tang_ca }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badgeClasses[$don->trang_thai] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $trangThaiLabels[$don->trang_thai] ?? $don->trang_thai }}
                                    </span>
                                    @if ($don->trang_thai == 'da_duyet')
                                        @if ($daXacNhan)
                                            <span class="ml-1 px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                                ✅ Hoàn thành
                                            </span>
                                        @elseif($daNhanVienXacNhan)
                                            <span class="ml-1 px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                ⏳ Chờ xác nhận
                                            </span>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Nút Xem chi tiết --}}
                                        <a href="{{ route('employee.tang-ca.show', $don->id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 rounded-lg transition"
                                            title="Xem chi tiết">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>

                                        {{-- Nút xác nhận đã làm tăng ca (chỉ cho đơn tăng ca đã duyệt) --}}
                                        @if (!$isKienNghi && $don->trang_thai == 'da_duyet' && !$thucHien)
                                            @php
                                                $now = Carbon\Carbon::now();
                                                $ngayTangCa = Carbon\Carbon::parse($don->ngay_tang_ca);
                                                $gioBatDau = Carbon\Carbon::parse($don->gio_bat_dau);
                                                $thoiGianBatDau = Carbon\Carbon::parse(
                                                    $ngayTangCa->format('Y-m-d') . ' ' . $gioBatDau->format('H:i:s'),
                                                );
                                                $thoiGianChoPhepSom = $thoiGianBatDau->copy()->subMinutes(30);
                                                $coTheXacNhan = $now->gte($thoiGianChoPhepSom);
                                            @endphp

                                            @if ($coTheXacNhan)
                                                <form action="{{ route('employee.tang-ca.confirm-thuc-hien', $don->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-green-50 text-green-600 hover:bg-green-100 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50 rounded-lg transition"
                                                        onclick="return confirm('Bạn đã hoàn thành giờ tăng ca này?')"
                                                        title="Xác nhận đã làm tăng ca">
                                                        <i class="fas fa-check-circle text-sm"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-50 text-gray-400 rounded-lg cursor-not-allowed" title="Chưa đến giờ tăng ca">
                                                    <i class="fas fa-clock text-sm"></i>
                                                </span>
                                            @endif
                                        @endif

                                        {{-- Chỉnh sửa kiến nghị (chỉ khi chờ duyệt và là kiến nghị) --}}
                                        @if ($isKienNghi && $don->trang_thai == 'cho_duyet')
                                            <a href="{{ route('employee.tang-ca.edit', $don->id) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-yellow-50 text-yellow-600 hover:bg-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-400 dark:hover:bg-yellow-900/50 rounded-lg transition"
                                                title="Chỉnh sửa">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                            <form action="{{ route('employee.tang-ca.huy', $don->id) }}" method="POST"
                                                onsubmit="return confirm('Bạn có chắc muốn hủy kiến nghị này?')">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 rounded-lg transition"
                                                    title="Hủy kiến nghị">
                                                    <i class="fas fa-times text-sm"></i>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- ⭐ TỪ CHỐI ĐƠN TĂNG CA - MỞ MODAL --}}
                                        @if (!$isKienNghi && $don->loai_tao == 'truong_phong' && $don->trang_thai == 'da_duyet' && !$thucHien)
                                            <button onclick="showTuChoiModalIndex({{ $don->id }}, '{{ $don->ly_do_tang_ca }}')"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 rounded-lg transition"
                                                title="Từ chối đơn tăng ca">
                                                <i class="fas fa-times text-sm"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-inbox text-2xl block mb-2 text-gray-300 dark:text-gray-600"></i>
                                    Chưa có dữ liệu tăng ca
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($donTangCa->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $donTangCa->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- ⭐ MODAL TỪ CHỐI (CHO INDEX) --}}
    <div id="tuChoiModalIndex" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md p-6 mx-4 animate-scale-up">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-times-circle text-red-500 text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Từ chối đơn tăng ca</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nhập lý do từ chối</p>
                </div>
            </div>
            
            <form action="" method="POST" id="tuChoiFormIndex">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Lý do từ chối <span class="text-red-500">*</span>
                    </label>
                    <textarea name="ly_do_tu_choi" id="lyDoTuChoiIndex" rows="4"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none transition"
                        placeholder="Nhập lý do từ chối (tối thiểu 10 ký tự)..." required></textarea>
                    <div class="flex justify-between mt-2">
                        <span class="text-xs text-gray-400">Tối thiểu 10 ký tự</span>
                        <span id="lyDoTuChoiCountIndex" class="text-xs text-gray-400">0/500</span>
                    </div>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closeTuChoiModalIndex()"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                        Hủy
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-check"></i>
                        Xác nhận từ chối
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .animate-scale-up {
            animation: scaleUp 0.25s ease-out;
        }
        @keyframes scaleUp {
            from {
                transform: scale(0.9);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
@endsection

@push('scripts')
<script>
    // ⭐ MODAL TỪ CHỐI TỪ INDEX
    let currentDonId = null;

    function showTuChoiModalIndex(id, lyDo) {
        currentDonId = id;
        const modal = document.getElementById('tuChoiModalIndex');
        const form = document.getElementById('tuChoiFormIndex');
        
        // Cập nhật action cho form
        form.action = `/employee/tang-ca/${id}/tu-choi-don`;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.getElementById('lyDoTuChoiIndex').value = '';
        document.getElementById('lyDoTuChoiCountIndex').textContent = '0/500';
    }

    function closeTuChoiModalIndex() {
        document.getElementById('tuChoiModalIndex').classList.add('hidden');
        document.getElementById('tuChoiModalIndex').classList.remove('flex');
        currentDonId = null;
    }

    // Đếm số ký tự lý do từ chối (Index)
    document.getElementById('lyDoTuChoiIndex').addEventListener('input', function() {
        const count = this.value.length;
        document.getElementById('lyDoTuChoiCountIndex').textContent = count + '/500';
    });

    // Kiểm tra trước khi submit form từ chối (Index)
    document.getElementById('tuChoiFormIndex').addEventListener('submit', function(e) {
        const lyDo = document.getElementById('lyDoTuChoiIndex').value.trim();
        if (lyDo.length < 10) {
            e.preventDefault();
            alert('⚠️ Lý do từ chối phải có ít nhất 10 ký tự!');
            return false;
        }
        if (!confirm('Bạn có chắc muốn từ chối đơn tăng ca này?')) {
            e.preventDefault();
            return false;
        }
    });

    // Click outside to close
    document.getElementById('tuChoiModalIndex').addEventListener('click', function(e) {
        if (e.target === this) closeTuChoiModalIndex();
    });

    // ESC to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeTuChoiModalIndex();
            closeTuChoiModal();
        }
    });
</script>
@endpush