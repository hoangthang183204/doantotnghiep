{{-- resources/views/employee/tang-ca/show.blade.php --}}
@extends('layouts.employee')

@section('title', 'Chi tiết tăng ca')

@section('content')
    <div class="space-y-6 max-w-4xl mx-auto">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-file-alt mr-3 text-blue-600"></i>
                    Chi tiết tăng ca
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Thông tin chi tiết</p>
            </div>
            <a href="{{ route('employee.tang-ca.index') }}"
                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                ← Quay lại
            </a>
        </div>

        {{-- THÔNG BÁO --}}
        @if (session('success'))
            <div
                class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg shadow-sm flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-700 dark:text-green-400">×</button>
            </div>
        @endif
        @if (session('error'))
            <div
                class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- THÔNG TIN --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 space-y-4">

                {{-- Trạng thái --}}
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Trạng thái</p>
                        @php
                            $badgeClasses = [
                                'cho_duyet' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                'da_duyet' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                'tu_choi' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                'huy' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                            ];
                            $trangThaiLabels = [
                                'cho_duyet' => '🟡 Chờ duyệt',
                                'da_duyet' => '🟢 Đã duyệt',
                                'tu_choi' => '🔴 Từ chối',
                                'huy' => '🗑️ Đã hủy',
                            ];
                        @endphp
                        <span
                            class="px-3 py-1 rounded-full text-sm font-medium {{ $badgeClasses[$donTangCa->trang_thai] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $trangThaiLabels[$donTangCa->trang_thai] ?? $donTangCa->trang_thai }}
                        </span>
                    </div>
                    <div>
                        @if($donTangCa->loai_tao == 'truong_phong')
                            <span class="text-xs bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 px-3 py-1 rounded-full">
                                <i class="fas fa-user-tie mr-1"></i> Trưởng phòng tạo
                            </span>
                        @else
                            <span class="text-xs bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300 px-3 py-1 rounded-full">
                                <i class="fas fa-user mr-1"></i> Nhân viên tạo
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Loại bản ghi --}}
                <div class="flex items-center gap-2">
                    @php
                        $isKienNghi = is_null($donTangCa->ngay_tang_ca);
                    @endphp
                    @if($isKienNghi)
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                            📝 Kiến nghị tăng ca
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300">
                            📄 Đơn tăng ca
                        </span>
                    @endif
                </div>

                {{-- Thông tin chi tiết --}}
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Ngày</p>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            @if($isKienNghi)
                                {{ Carbon\Carbon::parse($donTangCa->created_at)->format('d/m/Y') }}
                            @else
                                {{ Carbon\Carbon::parse($donTangCa->ngay_tang_ca)->format('d/m/Y') }}
                            @endif
                        </p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Giờ</p>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            @if($isKienNghi)
                                <span class="text-gray-400">---</span>
                            @else
                                {{ $donTangCa->gio_bat_dau }} - {{ $donTangCa->gio_ket_thuc }}
                            @endif
                        </p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Số giờ</p>
                        <p class="font-semibold {{ $isKienNghi ? 'text-gray-400' : 'text-blue-600' }}">
                            {{ $donTangCa->so_gio_tang_ca }} giờ
                        </p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Loại</p>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            @if($isKienNghi)
                                <span class="text-gray-400">---</span>
                            @else
                                @php
                                    $loaiLabels = [
                                        'ngay_thuong' => '📅 Ngày thường (150%)',
                                        'ngay_nghi' => '🎉 Ngày nghỉ (200%)',
                                        'le_tet' => '🎊 Lễ, Tết (300%)',
                                    ];
                                @endphp
                                {{ $loaiLabels[$donTangCa->loai_tang_ca] ?? $donTangCa->loai_tang_ca }}
                            @endif
                        </p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Ngày tạo</p>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            {{ Carbon\Carbon::parse($donTangCa->created_at)->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    @if(!$isKienNghi)
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Người tạo</p>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                @if($donTangCa->loai_tao == 'truong_phong')
                                    <span class="text-blue-600">👤 Trưởng phòng</span>
                                @else
                                    <span class="text-gray-600">👤 Nhân viên</span>
                                @endif
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Lý do --}}
                <div class="pt-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">📝 Lý do</p>
                    <p class="mt-1 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-gray-700 dark:text-gray-300">
                        {{ $donTangCa->ly_do_tang_ca }}
                    </p>
                </div>

                {{-- THÔNG TIN XỬ LÝ --}}
                @if ($donTangCa->thoi_gian_duyet)
                    <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-user-check text-blue-500"></i>
                            <span>Người duyệt: 
                                @php
                                    $nguoiDuyet = $donTangCa->nguoi_duyet;
                                    $ten = 'Chưa có';
                                    if ($nguoiDuyet) {
                                        $hoSo = $nguoiDuyet->hoSo;
                                        $ten = $hoSo ? $hoSo->ho . ' ' . $hoSo->ten : $nguoiDuyet->ten_dang_nhap;
                                    }
                                @endphp
                                <strong class="text-gray-700 dark:text-gray-300">{{ $ten }}</strong>
                            </span>
                            <span class="mx-1">|</span>
                            <i class="fas fa-clock text-blue-500"></i>
                            <span>{{ Carbon\Carbon::parse($donTangCa->thoi_gian_duyet)->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($donTangCa->ly_do_tu_choi)
                            <div class="mt-2 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                <p class="text-sm text-red-600 dark:text-red-400">❌ Lý do từ chối</p>
                                <p class="text-gray-700 dark:text-gray-300">{{ $donTangCa->ly_do_tu_choi }}</p>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- ⭐ ACTION BUTTONS --}}
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-wrap gap-3">

                        {{-- TRƯỜNG HỢP 1: KIẾN NGHỊ ĐANG CHỜ DUYỆT --}}
                        @if($isKienNghi && $donTangCa->trang_thai == 'cho_duyet')
                            <a href="{{ route('employee.tang-ca.edit', $donTangCa->id) }}"
                                class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition flex items-center gap-2">
                                <i class="fas fa-edit"></i>
                                Chỉnh sửa kiến nghị
                            </a>
                            <form action="{{ route('employee.tang-ca.huy', $donTangCa->id) }}" method="POST"
                                onsubmit="return confirm('Bạn có chắc muốn hủy kiến nghị này?')">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition flex items-center gap-2">
                                    <i class="fas fa-times"></i>
                                    Hủy kiến nghị
                                </button>
                            </form>
                        @endif

                        {{-- TRƯỜNG HỢP 2: ĐƠN TĂNG CA DO TRƯỞNG PHÒNG TẠO, ĐÃ DUYỆT, CHƯA THỰC HIỆN --}}
                        @if(!$isKienNghi && $donTangCa->loai_tao == 'truong_phong' && $donTangCa->trang_thai == 'da_duyet' && !$donTangCa->thuc_hien)
                            {{-- Nút Từ chối --}}
                            <button onclick="showTuChoiModal()"
                                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition flex items-center gap-2">
                                <i class="fas fa-times"></i>
                                Từ chối đơn tăng ca
                            </button>

                            {{-- Nút Xác nhận đã làm (nếu có) --}}
                            @php
                                $now = Carbon\Carbon::now();
                                $ngayTangCa = Carbon\Carbon::parse($donTangCa->ngay_tang_ca);
                                $gioBatDau = Carbon\Carbon::parse($donTangCa->gio_bat_dau);
                                $thoiGianBatDau = Carbon\Carbon::parse(
                                    $ngayTangCa->format('Y-m-d') . ' ' . $gioBatDau->format('H:i:s'),
                                );
                                $thoiGianChoPhepSom = $thoiGianBatDau->copy()->subMinutes(30);
                                $coTheXacNhan = $now->gte($thoiGianChoPhepSom);
                            @endphp
                            @if($coTheXacNhan)
                                <form action="{{ route('employee.tang-ca.confirm-thuc-hien', $donTangCa->id) }}" method="POST"
                                    onsubmit="return confirm('Bạn đã hoàn thành giờ tăng ca này?')">
                                    @csrf
                                    <button type="submit"
                                        class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition flex items-center gap-2">
                                        <i class="fas fa-check-circle"></i>
                                        Xác nhận đã làm tăng ca
                                    </button>
                                </form>
                            @else
                                <button disabled
                                    class="px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed flex items-center gap-2">
                                    <i class="fas fa-clock"></i>
                                    Chưa đến giờ tăng ca
                                </button>
                            @endif
                        @endif

                        {{-- Nút Quay lại --}}
                        <a href="{{ route('employee.tang-ca.index') }}"
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2">
                            <i class="fas fa-arrow-left"></i>
                            Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ⭐ MODAL TỪ CHỐI VỚI LÝ DO --}}
    <div id="tuChoiModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
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
            
            <form action="{{ route('employee.tang-ca.tu-choi-don', $donTangCa->id) }}" method="POST" id="tuChoiForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Lý do từ chối <span class="text-red-500">*</span>
                    </label>
                    <textarea name="ly_do_tu_choi" id="lyDoTuChoi" rows="4"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none transition"
                        placeholder="Nhập lý do từ chối (tối thiểu 10 ký tự)..." required></textarea>
                    <div class="flex justify-between mt-2">
                        <span class="text-xs text-gray-400">Tối thiểu 10 ký tự</span>
                        <span id="lyDoTuChoiCount" class="text-xs text-gray-400">0/500</span>
                    </div>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closeTuChoiModal()"
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
    // ⭐ MODAL TỪ CHỐI
    function showTuChoiModal() {
        document.getElementById('tuChoiModal').classList.remove('hidden');
        document.getElementById('tuChoiModal').classList.add('flex');
        document.getElementById('lyDoTuChoi').value = '';
        document.getElementById('lyDoTuChoiCount').textContent = '0/500';
    }

    function closeTuChoiModal() {
        document.getElementById('tuChoiModal').classList.add('hidden');
        document.getElementById('tuChoiModal').classList.remove('flex');
    }

    // Đếm số ký tự lý do từ chối
    document.getElementById('lyDoTuChoi').addEventListener('input', function() {
        const count = this.value.length;
        document.getElementById('lyDoTuChoiCount').textContent = count + '/500';
    });

    // Kiểm tra trước khi submit form từ chối
    document.getElementById('tuChoiForm').addEventListener('submit', function(e) {
        const lyDo = document.getElementById('lyDoTuChoi').value.trim();
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
    document.getElementById('tuChoiModal').addEventListener('click', function(e) {
        if (e.target === this) closeTuChoiModal();
    });

    // ESC to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeTuChoiModal();
    });
</script>
@endpush