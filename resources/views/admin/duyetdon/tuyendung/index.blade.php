@extends('layouts.admin')

@section('content')
    @php
        $statusMap = [
            'nhap' => [
                'label' => 'Nháp',
                'class' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                'icon' => '📄'
            ],
            'dang_tuyen' => [
                'label' => 'Đang tuyển',
                'class' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
                'icon' => '🔍'
            ],
            'tam_dung' => [
                'label' => 'Từ chối',
                'class' => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300',
                'icon' => '❌'
            ],
            'ket_thuc' => [
                'label' => 'Đã duyệt',
                'class' => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300',
                'icon' => '✅'
            ],
        ];
    @endphp

    <style>
        /* Full width fix */
        .container {
            max-width: 100% !important;
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
        }

        @media (min-width: 640px) {
            .container {
                padding-left: 2rem !important;
                padding-right: 2rem !important;
            }
        }

        @media (min-width: 1024px) {
            .container {
                padding-left: 3rem !important;
                padding-right: 3rem !important;
            }
        }

        /* Dark mode pagination */
        .dark .recruitment-pagination nav,
        .dark .recruitment-pagination p,
        .dark .recruitment-pagination span,
        .dark .recruitment-pagination a {
            color: #d1d5db !important;
        }

        .dark .recruitment-pagination a,
        .dark .recruitment-pagination span[aria-current] span,
        .dark .recruitment-pagination span[aria-disabled='true'] span {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
        }

        .dark .recruitment-pagination a:hover {
            background-color: #374151 !important;
        }

        /* Animations */
        .status-badge {
            transition: all 0.3s ease;
        }
        .status-badge:hover {
            transform: scale(1.05);
        }

        .action-btn {
            transition: all 0.2s ease;
        }
        .action-btn:hover {
            transform: translateY(-2px);
        }

        .table-row {
            transition: all 0.2s ease;
        }
        .table-row:hover {
            background-color: rgba(59, 130, 246, 0.04);
        }

        .dark .table-row:hover {
            background-color: rgba(59, 130, 246, 0.08);
        }

        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        }
        .dark .card-hover:hover {
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }

        /* Table scroll */
        .table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .table-wrapper::-webkit-scrollbar {
            height: 6px;
        }
        .table-wrapper::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        .table-wrapper::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        .table-wrapper::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        .dark .table-wrapper::-webkit-scrollbar-track {
            background: #374151;
        }
        .dark .table-wrapper::-webkit-scrollbar-thumb {
            background: #4b5563;
        }

        /* Status pulse for "Đang tuyển" */
        @keyframes pulse-status {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .status-pulse {
            animation: pulse-status 2s ease-in-out infinite;
        }
    </style>

    <div class="container mx-auto p-6 text-gray-900 dark:text-gray-100 transition-colors duration-200">
        <!-- Header với icon -->
        <div class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div class="flex items-center gap-3">
                <div class="rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 p-3 shadow-lg shadow-blue-500/25">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5L12 15l-4.5-4.5" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        Quản lý duyệt đơn tuyển dụng
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Quản lý và xử lý các đơn tuyển dụng từ các phòng ban
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2 rounded-lg bg-blue-50 px-3 py-1.5 dark:bg-blue-900/20">
                    <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                        {{ $items->count() }}
                    </span>
                    <span class="text-xs text-blue-500 dark:text-blue-400">đơn tuyển</span>
                </div>
                <div class="flex items-center gap-2 rounded-lg bg-yellow-50 px-3 py-1.5 dark:bg-yellow-900/20">
                    <span class="text-sm font-medium text-yellow-600 dark:text-yellow-400">
                        {{ $items->where('trang_thai', 'dang_tuyen')->count() }}
                    </span>
                    <span class="text-xs text-yellow-500 dark:text-yellow-400">đang tuyển</span>
                </div>
            </div>
        </div>

        <!-- Bảng danh sách -->
        <div class="card-hover rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-center justify-between border-b border-gray-200 p-4 dark:border-gray-700">
                <h2 class="flex items-center gap-2 text-lg font-medium text-gray-900 dark:text-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Danh sách đơn tuyển dụng
                </h2>
                <span class="rounded-full bg-blue-100 px-3 py-1 text-sm text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                    Tổng: {{ $items->count() }}
                </span>
            </div>

            @if ($items->count())
                <div class="table-wrapper p-4">
                    <table class="w-full min-w-[900px] divide-y divide-gray-200 dark:divide-gray-700">
                        <colgroup>
                            <col class="w-[10%]">
                            <col class="w-[15%]">
                            <col class="w-[18%]">
                            <col class="w-[15%]">
                            <col class="w-[12%]">
                            <col class="w-[30%]">
                        </colgroup>

                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50/80 to-gray-100/80 dark:from-gray-700/40 dark:to-gray-800/40">
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    <span class="block truncate">Mã yêu cầu</span>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    <span class="block truncate">Phòng ban</span>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    <span class="block truncate">Chức vụ</span>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    <span class="block truncate">Trạng thái</span>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    <span class="block truncate">Ngày tạo</span>
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    <span class="block truncate">Thao tác</span>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                            @foreach ($items as $item)
                                <tr class="table-row text-gray-900 dark:text-gray-100">
                                    <td class="px-4 py-3 align-middle">
                                        <span class="block truncate font-semibold text-blue-600 dark:text-blue-400" title="{{ $item->ma_yeu_cau ?? $item->id }}">
                                            {{ $item->ma_yeu_cau ?? $item->id }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 align-middle">
                                        @php
                                            $tenPhongBan = optional($item->phongBan)->ten_phong_ban
                                                ?? optional($item->phong_ban)->ten_phong_ban
                                                ?? $item->ten_phong_ban
                                                ?? $item->phong_ban
                                                ?? '-';
                                        @endphp

                                        <span class="block truncate" title="{{ $tenPhongBan }}">
                                            {{ $tenPhongBan }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 align-middle">
                                        <span class="block truncate font-medium" title="{{ optional($item->chucVu)->ten ?? $item->chuc_vu }}">
                                            {{ optional($item->chucVu)->ten ?? $item->chuc_vu }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 align-middle">
                                        @php
                                            $st = $item->trang_thai ?? null;
                                        @endphp

                                        @if ($st && isset($statusMap[$st]))
                                            <span class="status-badge inline-flex max-w-full items-center gap-1.5 rounded-full px-3 py-1 text-sm font-medium {{ $statusMap[$st]['class'] }} {{ $st === 'dang_tuyen' ? 'status-pulse' : '' }}">
                                                <span>{{ $statusMap[$st]['icon'] }}</span>
                                                <span class="block truncate">
                                                    {{ $statusMap[$st]['label'] }}
                                                </span>
                                            </span>
                                        @else
                                            <span class="block truncate text-sm text-gray-700 dark:text-gray-300">
                                                {{ $item->trang_thai ?? '-' }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 align-middle">
                                        <span class="block truncate text-sm text-gray-600 dark:text-gray-400">
                                            {{ $item->created_at->format('d/m/Y') }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 align-middle">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Nút xem -->
                                            <a href="{{ route('admin.duyetdon.tuyendung.show', $item->id) }}"
                                                title="Xem chi tiết"
                                                class="action-btn inline-flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600 transition hover:bg-blue-100 hover:text-blue-700 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/40 dark:hover:text-blue-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            @if ($item->trang_thai === 'dang_tuyen')
                                                <!-- Nút duyệt -->
                                                <form id="approve-form-{{ $item->id }}"
                                                    action="{{ route('admin.duyetdon.tuyendung.duyet', $item->id) }}"
                                                    method="POST"
                                                    class="inline-flex shrink-0">
                                                    @csrf

                                                    <button type="button"
                                                        title="Duyệt đơn"
                                                        onclick="confirmApprove({{ $item->id }})"
                                                        class="action-btn inline-flex h-9 w-9 items-center justify-center rounded-lg bg-green-50 text-green-600 transition hover:bg-green-100 hover:text-green-700 dark:bg-green-900/20 dark:text-green-400 dark:hover:bg-green-900/40 dark:hover:text-green-300">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                </form>

                                                <!-- Nút từ chối -->
                                                <button type="button"
                                                    title="Từ chối đơn"
                                                    onclick="openRejectModal({{ $item->id }})"
                                                    class="action-btn inline-flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600 transition hover:bg-red-100 hover:text-red-700 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40 dark:hover:text-red-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            @endif

                                            @if ($item->trang_thai === 'ket_thuc')
                                                <span class="text-sm text-green-600 dark:text-green-400 font-medium">Đã duyệt</span>
                                            @endif

                                            @if ($item->trang_thai === 'tam_dung')
                                                <span class="text-sm text-red-600 dark:text-red-400 font-medium">Đã từ chối</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-200 p-4 dark:border-gray-700">
                    <div class="recruitment-pagination">
                        {{ $items->links() }}
                    </div>
                </div>
            @else
                <div class="py-20 text-center text-gray-500 dark:text-gray-400">
                    <div class="inline-block rounded-xl bg-gray-50 p-12 dark:bg-gray-700/30">
                        <div class="mb-4 text-6xl">📋</div>
                        <div class="mb-2 text-lg font-medium text-gray-700 dark:text-gray-200">
                            Không có dữ liệu đơn tuyển dụng
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Không tìm thấy bản ghi nào phù hợp với điều kiện tìm kiếm.
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Lịch sử đơn tuyển dụng -->
    <div class="container mx-auto p-6 mt-6 text-gray-900 dark:text-gray-100 transition-colors duration-200">
        <div class="card-hover rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-center justify-between border-b border-gray-200 p-4 dark:border-gray-700">
                <h2 class="flex items-center gap-2 text-lg font-medium text-gray-900 dark:text-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Lịch sử xử lý đơn tuyển dụng
                </h2>
                <span class="rounded-full bg-purple-100 px-3 py-1 text-sm text-purple-700 dark:bg-purple-900/30 dark:text-purple-300">
                    {{ isset($history) ? $history->count() : 0 }} bản ghi
                </span>
            </div>

            @if (isset($history) && $history->count())
                <div class="table-wrapper p-4">
                    <table class="w-full min-w-[800px] divide-y divide-gray-200 dark:divide-gray-700">
                        <colgroup>
                            <col class="w-[14%]">
                            <col class="w-[18%]">
                            <col class="w-[20%]">
                            <col class="w-[18%]">
                            <col class="w-[30%]">
                        </colgroup>

                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50/80 to-gray-100/80 dark:from-gray-700/40 dark:to-gray-800/40">
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    <span class="block truncate">Mã yêu cầu</span>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    <span class="block truncate">Phòng ban</span>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    <span class="block truncate">Chức vụ</span>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    <span class="block truncate">Trạng thái</span>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    <span class="block truncate">Cập nhật</span>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                            @foreach ($history as $h)
                                <tr class="table-row text-gray-900 dark:text-gray-100">
                                    <td class="px-4 py-3 align-middle">
                                        <span class="block truncate font-semibold text-purple-600 dark:text-purple-400" title="{{ $h->ma ?? $h->id }}">
                                            {{ $h->ma ?? $h->id }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 align-middle">
                                        @php
                                            $tenPhongBan = optional($h->phongBan)->ten_phong_ban
                                                ?? optional($h->phong_ban)->ten_phong_ban
                                                ?? $h->ten_phong_ban
                                                ?? $h->phong_ban
                                                ?? '-';
                                        @endphp

                                        <span class="block truncate" title="{{ $tenPhongBan }}">
                                            {{ $tenPhongBan }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 align-middle">
                                        <span class="block truncate font-medium" title="{{ optional($h->chucVu)->ten ?? $h->chuc_vu }}">
                                            {{ optional($h->chucVu)->ten ?? $h->chuc_vu }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 align-middle">
                                        @php
                                            $st = $h->trang_thai ?? null;
                                        @endphp

                                        @if ($st && isset($statusMap[$st]))
                                            <span class="status-badge inline-flex max-w-full items-center gap-1.5 rounded-full px-3 py-1 text-sm font-medium {{ $statusMap[$st]['class'] }}">
                                                <span>{{ $statusMap[$st]['icon'] }}</span>
                                                <span class="block truncate">
                                                    {{ $statusMap[$st]['label'] }}
                                                </span>
                                            </span>
                                        @else
                                            <span class="block truncate text-sm text-gray-700 dark:text-gray-300">
                                                {{ $h->trang_thai ?? '-' }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 align-middle">
                                        <span class="block truncate text-sm text-gray-600 dark:text-gray-400">
                                            {{ $h->updated_at->format('d/m/Y H:i') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-200 p-4 dark:border-gray-700">
                    <div class="recruitment-pagination">
                        {{ $history->links() }}
                    </div>
                </div>
            @else
                <div class="py-10 text-center text-gray-500 dark:text-gray-400">
                    <div class="text-4xl mb-2">📭</div>
                    <p class="text-sm">Chưa có lịch sử xử lý đơn tuyển dụng.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal từ chối -->
    <div id="rejectModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="w-96 max-w-[90%] rounded-xl border border-gray-200 bg-white p-6 text-gray-900 shadow-2xl dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
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

            form.action = '/admin/duyetdon/tuyendung/' + id + '/tuchoi';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeRejectModal() {
            var modal = document.getElementById('rejectModal');

            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function confirmApprove(id) {
            if (confirm('Bạn có chắc chắn muốn duyệt đơn tuyển dụng này?')) {
                document.getElementById('approve-form-' + id).submit();
            }
        }

        // Đóng modal khi click ra ngoài
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });
    </script>
@endsection