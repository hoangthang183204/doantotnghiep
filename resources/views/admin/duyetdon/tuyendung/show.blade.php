@extends('layouts.admin')

@section('content')
    @php
        $statusMap = [
            'cho_duyet' => [
                'label' => 'Chờ duyệt',
                'class' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300',
                'icon' => '⏳'
            ],
            'da_duyet' => [
                'label' => 'Đã duyệt',
                'class' => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300',
                'icon' => '✅'
            ],
            'tu_choi' => [
                'label' => 'Từ chối',
                'class' => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300',
                'icon' => '❌'
            ],
            'dang_tuyen' => [
                'label' => 'Đang tuyển',
                'class' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
                'icon' => '🔍'
            ],
            'ket_thuc' => [
                'label' => 'Kết thúc',
                'class' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                'icon' => '📌'
            ],
        ];
    @endphp

    <style>
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        }
        .dark .card-hover:hover {
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }

        .status-badge {
            transition: all 0.3s ease;
            padding: 6px 18px;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .status-badge:hover {
            transform: scale(1.05);
        }

        .info-card {
            background: rgba(243, 244, 246, 0.5);
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s ease;
            border: 1px solid rgba(229, 231, 235, 0.5);
        }
        .info-card:hover {
            background: rgba(243, 244, 246, 0.8);
            border-color: rgba(59, 130, 246, 0.3);
            transform: translateY(-2px);
        }
        .dark .info-card {
            background: rgba(55, 65, 81, 0.3);
            border-color: rgba(55, 65, 81, 0.5);
        }
        .dark .info-card:hover {
            background: rgba(55, 65, 81, 0.5);
            border-color: rgba(59, 130, 246, 0.3);
        }

        .info-card .label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            display: block;
            margin-bottom: 4px;
        }
        .dark .info-card .label {
            color: #9ca3af;
        }

        .info-card .value {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
        }
        .dark .info-card .value {
            color: #f3f4f6;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 20px;
        }
        .dark .section-title {
            color: #f3f4f6;
            border-bottom-color: #374151;
        }
        .section-title .badge {
            font-size: 0.7rem;
            font-weight: 500;
            padding: 2px 10px;
            border-radius: 9999px;
            background: #dbeafe;
            color: #1d4ed8;
        }
        .dark .section-title .badge {
            background: #1e3a5f;
            color: #60a5fa;
        }

        .content-box {
            background: #f8fafc;
            border-radius: 10px;
            padding: 16px 20px;
            border-left: 4px solid #3b82f6;
            color: #374151;
            line-height: 1.8;
        }
        .dark .content-box {
            background: rgba(31, 41, 55, 0.5);
            border-left-color: #60a5fa;
            color: #d1d5db;
        }

        .action-btn {
            transition: all 0.2s ease;
            padding: 8px 20px;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .action-btn:hover {
            transform: translateY(-2px);
        }

        .grid-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        @media (max-width: 768px) {
            .grid-2col {
                grid-template-columns: 1fr;
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }
        .fade-in:nth-child(1) { animation-delay: 0.05s; }
        .fade-in:nth-child(2) { animation-delay: 0.1s; }
        .fade-in:nth-child(3) { animation-delay: 0.15s; }
        .fade-in:nth-child(4) { animation-delay: 0.2s; }
    </style>

    <div class="container mx-auto p-6 text-gray-900 dark:text-gray-100 transition-colors duration-200">
        <!-- Header -->
        <div class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div class="flex items-center gap-3">
                <div class="rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 p-3 shadow-lg shadow-blue-500/25">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        Chi tiết đơn tuyển dụng
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Thông tin chi tiết của đơn tuyển dụng
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if (isset($item) && $item)
                    @php $st = $item->trang_thai ?? 'cho_duyet'; @endphp
                    @if (isset($statusMap[$st]))
                        <span class="status-badge {{ $statusMap[$st]['class'] }}">
                            {{ $statusMap[$st]['icon'] }}
                            {{ $statusMap[$st]['label'] }}
                        </span>
                    @endif
                    <span class="rounded-lg bg-gray-100 px-3 py-1.5 text-sm text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        #{{ $item->ma_yeu_cau ?? $item->id ?? 'N/A' }}
                    </span>
                @endif
            </div>
        </div>

        @if (isset($item) && $item)
            <div class="space-y-6">
                <!-- Thông tin cơ bản -->
                <div class="card-hover rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 p-5 dark:border-gray-700">
                        <div class="section-title">
                            📋 Thông tin cơ bản
                            <span class="badge">Chi tiết đơn</span>
                        </div>
                        <div class="grid-2col">
                            <div class="info-card fade-in">
                                <span class="label">📌 Mã yêu cầu</span>
                                <span class="value text-blue-600 dark:text-blue-400">
                                    {{ $item->ma_yeu_cau ?? $item->id ?? 'N/A' }}
                                </span>
                            </div>
                            <div class="info-card fade-in">
                                <span class="label">🏢 Phòng ban</span>
                                <span class="value">
                                    {{ optional($item->phongBan)->ten_phong_ban ?? $item->ten_phong_ban ?? '-' }}
                                </span>
                            </div>
                            <div class="info-card fade-in">
                                <span class="label">💼 Chức vụ</span>
                                <span class="value">
                                    {{ optional($item->chucVu)->ten ?? $item->chuc_vu ?? '-' }}
                                </span>
                            </div>
                            <div class="info-card fade-in">
                                <span class="label">👥 Số lượng</span>
                                <span class="value">
                                    {{ $item->so_luong ?? $item->so_vi_tri ?? '-' }}
                                </span>
                            </div>
                            <div class="info-card fade-in">
                                <span class="label">📄 Loại hợp đồng</span>
                                <span class="value">
                                    {{ $item->loai_hop_dong ?? '-' }}
                                </span>
                            </div>
                            <div class="info-card fade-in">
                                <span class="label">📅 Ngày tạo</span>
                                <span class="value">
                                    {{ optional($item->created_at)->format('d/m/Y H:i') ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Yêu cầu ứng viên -->
                <div class="card-hover rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 p-5 dark:border-gray-700">
                        <div class="section-title">
                            🎯 Yêu cầu ứng viên
                            <span class="badge">Tiêu chí</span>
                        </div>
                        <div class="grid-2col">
                            <div class="info-card fade-in">
                                <span class="label">🎓 Trình độ học vấn</span>
                                <span class="value">
                                    {{ $item->trinh_do_hoc_van ?? '-' }}
                                </span>
                            </div>
                            <div class="info-card fade-in">
                                <span class="label">⏳ Kinh nghiệm</span>
                                <span class="value">
                                    {{ $item->kinh_nghiem ?? '-' }}
                                </span>
                            </div>
                            <div class="info-card fade-in" style="grid-column: 1 / -1;">
                                <span class="label">💰 Mức lương</span>
                                <span class="value text-green-600 dark:text-green-400">
                                    @if(isset($item->muc_luong_min) && isset($item->muc_luong_max))
                                        {{ number_format($item->muc_luong_min, 0, ',', '.') }} - 
                                        {{ number_format($item->muc_luong_max, 0, ',', '.') }} VND
                                    @elseif(isset($item->muc_luong))
                                        {{ number_format($item->muc_luong, 0, ',', '.') }} VND
                                    @else
                                        Thỏa thuận
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mô tả chi tiết -->
                <div class="card-hover rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
                    <div class="p-5">
                        <div class="section-title">
                            📝 Mô tả chi tiết
                            <span class="badge">Chi tiết công việc</span>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <div class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    📌 Mô tả công việc
                                </div>
                                <div class="content-box">
                                    {{ $item->mo_ta_cong_viec ?? $item->mo_ta ?? 'Chưa có mô tả' }}
                                </div>
                            </div>
                            <div>
                                <div class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    🛠️ Kỹ năng yêu cầu
                                </div>
                                <div class="content-box">
                                    {{ $item->ky_nang_yeu_cau ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Yêu cầu công việc & Ghi chú -->
                <div class="card-hover rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
                    <div class="p-5">
                        <div class="section-title">
                            📌 Yêu cầu công việc
                            <span class="badge">Thông tin thêm</span>
                        </div>
                        <div class="grid-2col">
                            <div>
                                <div class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    📋 Yêu cầu công việc
                                </div>
                                <div class="content-box">
                                    {{ $item->yeu_cau_cong_viec ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <div class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    📝 Ghi chú
                                </div>
                                <div class="content-box" style="border-left-color: #8b5cf6;">
                                    {{ $item->ghi_chu ?? 'Không có ghi chú' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer actions -->
                <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-gray-200 bg-white p-5 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                    <a href="{{ route('admin.duyetdon.tuyendung.index') }}"
                        class="action-btn border border-gray-300 text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Quay lại danh sách
                    </a>

                    @if($item->trang_thai === 'dang_tuyen')
                        <div class="flex gap-3">
                            <form action="{{ route('admin.duyetdon.tuyendung.duyet', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                    class="action-btn bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg shadow-green-500/30 hover:shadow-green-500/40">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Duyệt đơn
                                </button>
                            </form>
                            <button type="button"
                                onclick="openRejectModal({{ $item->id }})"
                                class="action-btn bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg shadow-red-500/30 hover:shadow-red-500/40">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Từ chối
                            </button>
                        </div>
                    @elseif($item->trang_thai === 'ket_thuc' || $item->trang_thai === 'da_duyet')
                        <div class="flex items-center gap-2 text-sm text-green-600 dark:text-green-400">
                            <span>✅</span>
                            <span class="font-medium">Đã được duyệt</span>
                        </div>
                    @elseif($item->trang_thai === 'tam_dung' || $item->trang_thai === 'tu_choi')
                        <div class="flex items-center gap-2 text-sm text-red-600 dark:text-red-400">
                            <span>❌</span>
                            <span class="font-medium">Đã bị từ chối</span>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="rounded-xl border border-gray-200 bg-white p-12 text-center shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <div class="mb-4 text-6xl">📄</div>
                <div class="text-lg font-medium text-gray-700 dark:text-gray-200">
                    Không tìm thấy đơn tuyển dụng
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Đơn tuyển dụng bạn đang tìm không tồn tại hoặc đã bị xóa.
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.duyetdon.tuyendung.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
                        Quay lại danh sách
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal từ chối -->
    <div id="rejectModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="w-96 rounded-xl border border-gray-200 bg-white p-6 text-gray-900 shadow-2xl dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
            <div class="mb-4 flex items-center gap-3">
                <div class="rounded-full bg-red-100 p-2 dark:bg-red-900/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Từ chối đơn tuyển dụng
                </h3>
            </div>

            <form id="rejectForm" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Ghi chú từ chối
                    </label>
                    <textarea name="ghi_chu"
                        class="w-full rounded-lg border border-gray-300 bg-white p-3 text-gray-900 placeholder-gray-400 focus:border-red-500 focus:ring-2 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 dark:placeholder-gray-500"
                        rows="4"
                        placeholder="Nhập lý do từ chối..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button"
                        onclick="closeRejectModal()"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        Hủy bỏ
                    </button>

                    <button type="submit"
                        class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600">
                        Xác nhận từ chối
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal(id) {
            var modal = document.getElementById('rejectModal');
            var form = document.getElementById('rejectForm');

            form.action = '{{ url("/admin/duyetdon/tuyendung") }}/' + id + '/tuchoi';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeRejectModal() {
            var modal = document.getElementById('rejectModal');

            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Đóng modal khi click ra ngoài
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });
    </script>
@endsection