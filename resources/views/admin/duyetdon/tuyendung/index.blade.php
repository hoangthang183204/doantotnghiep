@extends('layouts.admin')

@section('content')
    @php
        $statusMap = [
            'nhap' => [
                'label' => 'Nháp',
                'class' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
            ],
            'dang_tuyen' => [
                'label' => 'Đang tuyển',
                'class' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
            ],
            'tam_dung' => [
                'label' => 'Từ chối',
                'class' => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300',
            ],
            'ket_thuc' => [
                'label' => 'Đã duyệt',
                'class' => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300',
            ],
        ];
    @endphp

    <style>
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
    </style>

    <div class="container mx-auto p-6 text-gray-900 dark:text-gray-100 transition-colors duration-200">
        <h1 class="mb-4 text-2xl font-semibold text-gray-900 dark:text-gray-100">
            Quản lý duyệt đơn tuyển dụng
        </h1>

        <div class="rounded border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-gray-800">
            <h2 class="mb-2 text-lg font-medium text-gray-900 dark:text-gray-100">
                Bảng Đơn Tuyển Dụng
            </h2>

            @if ($items->count())
                <div class="w-full overflow-hidden">
                    <table class="w-full table-fixed divide-y divide-gray-200 dark:divide-gray-700">
                        <colgroup>
                            <col class="w-[12%]">
                            <col class="w-[15%]">
                            <col class="w-[23%]">
                            <col class="w-[15%]">
                            <col class="w-[15%]">
                            <col class="w-[20%]">
                        </colgroup>

                        <thead class="bg-gray-50 dark:bg-gray-700/70">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    <span class="block truncate">Mã yêu cầu</span>
                                </th>

                                <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    <span class="block truncate">Phòng ban</span>
                                </th>

                                <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    <span class="block truncate">Chức vụ</span>
                                </th>

                                <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    <span class="block truncate">Trạng thái</span>
                                </th>

                                <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    <span class="block truncate">Ngày tạo</span>
                                </th>

                                <th class="px-3 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    <span class="block truncate">Thao tác</span>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                            @foreach ($items as $item)
                                <tr class="h-14 text-gray-900 hover:bg-gray-50 dark:text-gray-100 dark:hover:bg-gray-700/50">
                                    <td class="px-3 py-4 align-middle">
                                        <span class="block truncate" title="{{ $item->ma_yeu_cau ?? $item->id }}">
                                            {{ $item->ma_yeu_cau ?? $item->id }}
                                        </span>
                                    </td>

                                    <td class="px-3 py-4 align-middle">
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

                                    <td class="px-3 py-4 align-middle">
                                        <span class="block truncate" title="{{ optional($item->chucVu)->ten ?? $item->chuc_vu }}">
                                            {{ optional($item->chucVu)->ten ?? $item->chuc_vu }}
                                        </span>
                                    </td>

                                    <td class="px-3 py-4 align-middle">
                                        @php
                                            $st = $item->trang_thai ?? null;
                                        @endphp

                                        @if ($st && isset($statusMap[$st]))
                                            <span class="inline-flex max-w-full items-center rounded-full px-2.5 py-0.5 text-sm font-medium {{ $statusMap[$st]['class'] }}">
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

                                    <td class="px-3 py-4 align-middle">
                                        <span class="block truncate">
                                            {{ $item->created_at->format('d/m/Y') }}
                                        </span>
                                    </td>

                                    <td class="px-3 py-4 align-middle">
                                        <div class="flex items-center justify-center gap-3 whitespace-nowrap text-sm">
                                            <!-- Icon xem -->
                                            <a href="{{ route('admin.duyetdon.tuyendung.show', $item->id) }}"
                                                title="Xem chi tiết"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-full text-blue-600 transition hover:bg-blue-50 hover:text-blue-700 dark:text-blue-400 dark:hover:bg-blue-900/30 dark:hover:text-blue-300">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75 6.75-9.75 6.75S2.25 12 2.25 12z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </a>

                                            @if ($item->trang_thai === 'dang_tuyen')
                                                <!-- Icon duyệt -->
                                                <form id="approve-form-{{ $item->id }}"
                                                    action="{{ route('admin.duyetdon.tuyendung.duyet', $item->id) }}"
                                                    method="POST"
                                                    class="inline-flex shrink-0"
                                                    style="display:inline">
                                                    @csrf

                                                    <button type="button"
                                                        title="Duyệt"
                                                        onclick="confirmApprove({{ $item->id }})"
                                                        class="inline-flex h-8 w-8 items-center justify-center rounded-full border-0 bg-transparent p-0 text-yellow-500 transition hover:bg-yellow-50 hover:text-yellow-600 dark:text-yellow-400 dark:hover:bg-yellow-900/30 dark:hover:text-yellow-300">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-5 w-5"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                            stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M16.862 4.487l1.651-1.651a2.121 2.121 0 113 3l-9.193 9.193a4.5 4.5 0 01-1.897 1.13L6 17.25l1.091-4.423a4.5 4.5 0 011.13-1.897l8.641-8.443z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M19.5 7.125L16.875 4.5" />
                                                        </svg>
                                                    </button>
                                                </form>

                                                <!-- Icon từ chối -->
                                                <button type="button"
                                                    title="Từ chối"
                                                    onclick="openRejectModal({{ $item->id }})"
                                                    class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-0 bg-transparent p-0 text-red-600 transition hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-900/30 dark:hover:text-red-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0115.916 21H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="recruitment-pagination mt-4">
                    {{ $items->links() }}
                </div>
            @else
                <div class="py-20 text-center text-gray-500 dark:text-gray-400">
                    <div class="inline-block rounded bg-gray-100 p-8 dark:bg-gray-700">
                        <div class="mb-2 text-gray-700 dark:text-gray-200">
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
        <div class="rounded border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-gray-800">
            <h2 class="mb-2 text-lg font-medium text-gray-900 dark:text-gray-100">
                Lịch sử đơn tuyển dụng
            </h2>

            @if (isset($history) && $history->count())
                <div class="w-full overflow-hidden">
                    <table class="w-full table-fixed divide-y divide-gray-200 dark:divide-gray-700">
                        <colgroup>
                            <col class="w-[16%]">
                            <col class="w-[20%]">
                            <col class="w-[28%]">
                            <col class="w-[18%]">
                            <col class="w-[18%]">
                        </colgroup>

                        <thead class="bg-gray-50 dark:bg-gray-700/70">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    <span class="block truncate">Mã yêu cầu</span>
                                </th>

                                <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    <span class="block truncate">Phòng ban</span>
                                </th>

                                <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    <span class="block truncate">Chức vụ</span>
                                </th>

                                <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    <span class="block truncate">Trạng thái</span>
                                </th>

                                <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    <span class="block truncate">Cập nhật</span>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                            @foreach ($history as $h)
                                <tr class="h-14 text-gray-900 hover:bg-gray-50 dark:text-gray-100 dark:hover:bg-gray-700/50">
                                    <td class="px-3 py-4 align-middle">
                                        <span class="block truncate" title="{{ $h->ma ?? $h->id }}">
                                            {{ $h->ma ?? $h->id }}
                                        </span>
                                    </td>

                                    <td class="px-3 py-4 align-middle">
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

                                    <td class="px-3 py-4 align-middle">
                                        <span class="block truncate" title="{{ optional($h->chucVu)->ten ?? $h->chuc_vu }}">
                                            {{ optional($h->chucVu)->ten ?? $h->chuc_vu }}
                                        </span>
                                    </td>

                                    <td class="px-3 py-4 align-middle">
                                        @php
                                            $st = $h->trang_thai ?? null;
                                        @endphp

                                        @if ($st && isset($statusMap[$st]))
                                            <span class="inline-flex max-w-full items-center rounded-full px-2.5 py-0.5 text-sm font-medium {{ $statusMap[$st]['class'] }}">
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

                                    <td class="px-3 py-4 align-middle">
                                        <span class="block truncate">
                                            {{ $h->updated_at->format('d/m/Y H:i') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="recruitment-pagination mt-4">
                    {{ $history->links() }}
                </div>
            @else
                <div class="py-6 text-center text-gray-500 dark:text-gray-400">
                    Chưa có lịch sử xử lý đơn tuyển dụng.
                </div>
            @endif
        </div>
    </div>

    <!-- Modal từ chối -->
    <div id="rejectModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="w-96 rounded border border-gray-200 bg-white p-6 text-gray-900 shadow-lg dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
            <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-gray-100">
                Từ chối đơn tuyển dụng
            </h3>

            <form id="rejectForm" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="block text-sm text-gray-700 dark:text-gray-300">
                        Ghi chú
                    </label>

                    <textarea name="ghi_chu"
                        class="w-full rounded border border-gray-300 bg-white p-2 text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 dark:placeholder-gray-500"
                        rows="4"></textarea>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button"
                        onclick="closeRejectModal()"
                        class="rounded border border-gray-300 px-3 py-1 text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        Hủy
                    </button>

                    <button type="submit"
                        class="rounded bg-red-600 px-3 py-1 text-white hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600">
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
            if (confirm('Bạn có chắc muốn duyệt đơn tuyển dụng này?')) {
                document.getElementById('approve-form-' + id).submit();
            }
        }
    </script>
@endsection