{{-- resources/views/truong-phong/tang-ca/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Chi tiết đơn tăng ca')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-file-alt mr-3 text-blue-600"></i>
                Chi tiết đơn tăng ca
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Mã đơn: <span class="font-medium text-blue-600">#{{ $tangCa->id }}</span>
            </p>
        </div>
        <a href="{{ route('truong-phong.tang-ca.index') }}" 
            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    {{-- THÔNG TIN ĐƠN --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6 space-y-6">
            
            {{-- Thông tin nhân viên --}}
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                    <i class="fas fa-user mr-2 text-blue-500"></i>
                    Thông tin nhân viên
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400">Họ tên</p>
                        <p class="font-medium text-gray-900 dark:text-white">
                            {{ optional($tangCa->nguoi_dung->hoSo)->ho }} {{ optional($tangCa->nguoi_dung->hoSo)->ten }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Mã nhân viên</p>
                        <p class="font-medium text-gray-900 dark:text-white">
                            {{ optional($tangCa->nguoi_dung->hoSo)->ma_nhan_vien ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Phòng ban</p>
                        <p class="font-medium text-gray-900 dark:text-white">
                            {{ optional($tangCa->nguoi_dung->phongBan)->ten_phong_ban ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Chức vụ</p>
                        <p class="font-medium text-gray-900 dark:text-white">
                            {{ optional($tangCa->nguoi_dung->chucVu)->ten ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700"></div>

            {{-- Thông tin tăng ca --}}
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                    <i class="fas fa-clock mr-2 text-blue-500"></i>
                    Thông tin tăng ca
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400">Ngày tăng ca</p>
                        <p class="font-medium text-gray-900 dark:text-white">
                            {{ $tangCa->ngay_tang_ca ? Carbon\Carbon::parse($tangCa->ngay_tang_ca)->format('d/m/Y') : '---' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Giờ bắt đầu</p>
                        <p class="font-medium text-gray-900 dark:text-white">
                            {{ $tangCa->gio_bat_dau ?? '---' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Giờ kết thúc</p>
                        <p class="font-medium text-gray-900 dark:text-white">
                            {{ $tangCa->gio_ket_thuc ?? '---' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Số giờ</p>
                        <p class="font-medium text-blue-600 dark:text-blue-400">
                            {{ $tangCa->so_gio_tang_ca ?? 0 }} giờ
                        </p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700"></div>

            {{-- Loại tăng ca --}}
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                    <i class="fas fa-tag mr-2 text-blue-500"></i>
                    Loại tăng ca
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400">Loại tăng ca</p>
                        <p class="font-medium text-gray-900 dark:text-white">
                            {{ $tangCa->loai_tang_ca ? \App\Helpers\OvertimeHelper::getLoaiLabel($tangCa->loai_tang_ca) : '---' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Người tạo</p>
                        <p class="font-medium text-gray-900 dark:text-white">
                            @if($tangCa->loai_tao == 'truong_phong')
                                <span class="text-blue-600">Trưởng phòng</span>
                            @else
                                <span class="text-green-600">Nhân viên</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700"></div>

            {{-- Lý do --}}
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                    <i class="fas fa-pen mr-2 text-blue-500"></i>
                    Lý do tăng ca
                </h3>
                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4">
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
                        {{ $tangCa->ly_do_tang_ca ?? 'Không có lý do' }}
                    </p>
                </div>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700"></div>

            {{-- Trạng thái --}}
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                    Trạng thái
                </h3>
                <div class="flex items-center gap-3">
                    @php
                        $statusColors = [
                            'cho_duyet' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                            'da_duyet' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                            'tu_choi' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                            'huy' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                        ];
                        $statusLabels = [
                            'cho_duyet' => '⏳ Chờ duyệt',
                            'da_duyet' => '✅ Đã duyệt',
                            'tu_choi' => '❌ Từ chối',
                            'huy' => '🗑️ Đã hủy',
                        ];
                    @endphp
                    <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $statusColors[$tangCa->trang_thai] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabels[$tangCa->trang_thai] ?? $tangCa->trang_thai }}
                    </span>
                    @if($tangCa->nguoi_duyet)
                        <span class="text-sm text-gray-500">
                            bởi {{ optional($tangCa->nguoi_duyet->hoSo)->ho }} {{ optional($tangCa->nguoi_duyet->hoSo)->ten }}
                            lúc {{ $tangCa->thoi_gian_duyet ? Carbon\Carbon::parse($tangCa->thoi_gian_duyet)->format('d/m/Y H:i') : 'N/A' }}
                        </span>
                    @endif
                </div>
                @if($tangCa->ly_do_tu_choi)
                    <div class="mt-2 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <p class="text-sm text-red-600 dark:text-red-400">
                            <strong>Lý do từ chối:</strong> {{ $tangCa->ly_do_tu_choi }}
                        </p>
                    </div>
                @endif
            </div>

            {{-- Nút hành động --}}
            @if($tangCa->trang_thai == 'cho_duyet')
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 flex gap-3">
                    {{-- Kiểm tra nếu là kiến nghị (ngay_tang_ca = null) --}}
                    @if(is_null($tangCa->ngay_tang_ca))
                        {{-- Duyệt kiến nghị --}}
                        <form action="{{ route('truong-phong.tang-ca.duyet-kien-nghi', $tangCa->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition shadow-sm hover:shadow-md flex items-center gap-2"
                                onclick="return confirm('Duyệt kiến nghị tăng ca này?')">
                                <i class="fas fa-check"></i> Duyệt kiến nghị
                            </button>
                        </form>
                        {{-- Từ chối kiến nghị --}}
                        <button onclick="showTuChoiModal({{ $tangCa->id }}, true)"
                            class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition shadow-sm hover:shadow-md flex items-center gap-2">
                            <i class="fas fa-times"></i> Từ chối
                        </button>
                    @else
                        {{-- Duyệt đơn tăng ca --}}
                        <form action="{{ route('truong-phong.tang-ca.duyet', $tangCa->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition shadow-sm hover:shadow-md flex items-center gap-2"
                                onclick="return confirm('Duyệt đơn tăng ca này?')">
                                <i class="fas fa-check"></i> Duyệt
                            </button>
                        </form>
                        {{-- Từ chối đơn tăng ca --}}
                        <button onclick="showTuChoiModal({{ $tangCa->id }}, false)"
                            class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition shadow-sm hover:shadow-md flex items-center gap-2">
                            <i class="fas fa-times"></i> Từ chối
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ⭐ MODAL TỪ CHỐI --}}
<div id="tuChoiModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md p-6 mx-4 animate-scale-up">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-times-circle text-red-500 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Từ chối</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Nhập lý do từ chối</p>
            </div>
        </div>
        
        <form action="" method="POST" id="tuChoiForm">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Lý do từ chối <span class="text-red-500">*</span>
                </label>
                <textarea name="ly_do_tu_choi" id="lyDoTuChoi" rows="4"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none transition"
                    placeholder="Nhập lý do từ chối..." required></textarea>
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
    // ⭐ LƯU ROUTES VÀO BIẾN JAVASCRIPT
    const ROUTES = {
        tuChoiDon: '{{ route("truong-phong.tang-ca.tu-choi", ["id" => ":id"]) }}',
        tuChoiKienNghi: '{{ route("truong-phong.tang-ca.tu-choi-kien-nghi", ["id" => ":id"]) }}',
    };

    let currentId = null;
    let isKienNghi = false;

    function showTuChoiModal(id, isKienNghiFlag) {
        currentId = id;
        isKienNghi = isKienNghiFlag;
        const modal = document.getElementById('tuChoiModal');
        const form = document.getElementById('tuChoiForm');
        
        // ⭐ Cập nhật action cho form bằng cách thay thế :id
        let url = isKienNghi ? ROUTES.tuChoiKienNghi : ROUTES.tuChoiDon;
        url = url.replace(':id', id);
        form.action = url;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.getElementById('lyDoTuChoi').value = '';
        document.getElementById('lyDoTuChoiCount').textContent = '0/500';
    }

    function closeTuChoiModal() {
        document.getElementById('tuChoiModal').classList.add('hidden');
        document.getElementById('tuChoiModal').classList.remove('flex');
        currentId = null;
    }

    // Đếm số ký tự
    document.getElementById('lyDoTuChoi').addEventListener('input', function() {
        const count = this.value.length;
        document.getElementById('lyDoTuChoiCount').textContent = count + '/500';
    });

    // Kiểm tra trước khi submit
    document.getElementById('tuChoiForm').addEventListener('submit', function(e) {
        const lyDo = document.getElementById('lyDoTuChoi').value.trim();
        if (lyDo.length < 10) {
            e.preventDefault();
            alert('⚠️ Lý do từ chối phải có ít nhất 10 ký tự!');
            return false;
        }
        if (!confirm('Bạn có chắc muốn từ chối đơn này?')) {
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