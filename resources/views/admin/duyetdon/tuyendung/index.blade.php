@extends('layouts.admin')

@section('content')
    @php
        $statusMap = [
            'nhap' => ['label' => 'Nháp', 'class' => 'bg-gray-100 text-gray-800'],
            'dang_tuyen' => ['label' => 'Đang tuyển', 'class' => 'bg-blue-100 text-blue-800'],
            'tam_dung' => ['label' => 'Từ chối', 'class' => 'bg-red-100 text-red-800'],
            'ket_thuc' => ['label' => 'Đã duyệt', 'class' => 'bg-green-100 text-green-800'],
        ];
    @endphp
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-semibold mb-4">Quản lý duyệt đơn tuyển dụng</h1>

        <div class="bg-white rounded shadow p-4">
            <h2 class="text-lg font-medium mb-2">Bảng Đơn Tuyển Dụng</h2>
            @if ($items->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã
                                    yêu cầu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Phòng ban</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Chức vụ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Trạng thái</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ngày tạo</th>
                                <th class="px-6 py-3">Thao tác</th>
                            </tr>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->ma_yeu_cau ?? $item->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ optional($item->phongBan)->ten ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ optional($item->chucVu)->ten ?? $item->chuc_vu }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusMap = [
                                                'nhap' => ['label' => 'Nháp', 'class' => 'bg-gray-100 text-gray-800'],
                                                'dang_tuyen' => [
                                                    'label' => 'Đang tuyển',
                                                    'class' => 'bg-blue-100 text-blue-800',
                                                ],
                                                'tam_dung' => [
                                                    'label' => 'Từ chối',
                                                    'class' => 'bg-red-100 text-red-800',
                                                ],
                                                'ket_thuc' => [
                                                    'label' => 'Đã duyệt',
                                                    'class' => 'bg-green-100 text-green-800',
                                                ],
                                            ];
                                            $st = $item->trang_thai ?? null;
                                        @endphp
                                        @if ($st && isset($statusMap[$st]))
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $statusMap[$st]['class'] }}">{{ $statusMap[$st]['label'] }}</span>
                                        @else
                                            <span class="text-sm">{{ $item->trang_thai ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('admin.duyetdon.tuyendung.show', $item->id) }}"
                                            class="text-blue-600 hover:text-blue-900">Xem</a>
                                        @if ($item->trang_thai === 'dang_tuyen')
                                            |
                                            <form id="approve-form-{{ $item->id }}"
                                                action="{{ route('admin.duyetdon.tuyendung.duyet', $item->id) }}"
                                                method="POST" style="display:inline">
                                                @csrf
                                                <button type="button" onclick="confirmApprove({{ $item->id }})"
                                                    class="text-green-600 hover:text-green-900 bg-transparent border-0 p-0">Duyệt</button>
                                            </form>
                                            |
                                            <button type="button"
                                                class="text-red-600 hover:text-red-900 bg-transparent border-0 p-0"
                                                onclick="openRejectModal({{ $item->id }})">Từ chối</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $items->links() }}
                </div>
            @else
                <div class="text-center py-20 text-gray-500">
                    <div class="inline-block p-8 bg-gray-100 rounded">
                        <div class="mb-2">Không có dữ liệu đơn tuyển dụng</div>
                        <div class="text-sm">Không tìm thấy bản ghi nào phù hợp với điều kiện tìm kiếm.</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Lịch sử đơn tuyển dụng -->
    <div class="container mx-auto p-6 mt-6">
        <div class="bg-white rounded shadow p-4">
            <h2 class="text-lg font-medium mb-2">Lịch sử đơn tuyển dụng</h2>
            @if (isset($history) && $history->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mã yêu cầu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Phòng ban</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Chức vụ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Trạng thái</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cập nhật</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($history as $h)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $h->ma ?? $h->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ optional($h->phongBan)->ten ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ optional($h->chucVu)->ten ?? $h->chuc_vu }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $st = $h->trang_thai ?? null;
                                        @endphp
                                        @if ($st && isset($statusMap[$st]))
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $statusMap[$st]['class'] }}">{{ $statusMap[$st]['label'] }}</span>
                                        @else
                                            <span class="text-sm">{{ $h->trang_thai ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $h->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $history->links() }}
                </div>
            @else
                <div class="text-center py-6 text-gray-500">Chưa có lịch sử xử lý đơn tuyển dụng.</div>
            @endif
        </div>
    </div>

    <!-- Modal từ chối -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded p-6 w-96">
            <h3 class="text-lg font-medium mb-2">Từ chối đơn tuyển dụng</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm">Ghi chú</label>
                    <textarea name="ghi_chu" class="w-full border rounded p-2" rows="4"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeRejectModal()" class="px-3 py-1 border rounded">Hủy</button>
                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">Xác nhận từ chối</button>
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
