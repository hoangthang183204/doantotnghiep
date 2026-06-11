@extends('layouts.admin')

@section('title', 'Danh sách phụ cấp')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* Đảm bảo toàn bộ trang có màu nền sáng, dịu mắt */
body {
    background-color: #f6f7fb !important;
    font-family: "Inter", sans-serif;
}

/* Định dạng Card trắng bo góc hiện đại */
.page-card-bright {
    background: #ffffff !important;
    border-radius: 16px;
    border: 1px solid #eef0f3;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02) !important;
}

/* Định dạng thanh tiêu đề bảng */
.table-header-bright {
    background-color: #f8fafc !important;
    color: #475569 !important;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.05em;
}

/* Nút bấm hành động nhỏ gọn, tinh tế */
.action-btn {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: all 0.2s;
    font-size: 14px;
}
.btn-view { background-color: #f0fdf4; color: #16a34a; }
.btn-view:hover { background-color: #dcfce7; }

.btn-edit { background-color: #eff6ff; color: #3b82f6; }
.btn-edit:hover { background-color: #d0e7ff; }

.btn-delete { background-color: #fef2f2; color: #ef4444; border: none; }
.btn-delete:hover { background-color: #fee2e2; }

/* Toast alert thông báo nổi thành công */
.custom-toast-alert {
    position: fixed;
    top: 24px;
    right: 24px;
    background-color: #ffffff;
    border-left: 4px solid #22c55e;
    padding: 16px 20px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    z-index: 9999;
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 320px;
    animation: slideInRight 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
@keyframes slideInRight {
    from { transform: translateX(120%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
.toast-fade-out {
    opacity: 0;
    transform: translateX(40px);
    transition: all 0.4s ease;
}
</style>

@if(session('success'))
    <div class="custom-toast-alert" id="laravelToastAlert">
        <div style="width: 28px; height: 28px; background-color: #dcfce7; color: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0;">
            <i class="fa-solid fa-check"></i>
        </div>
        <div style="flex-grow: 1;">
            <h4 style="font-weight: 600; color: #1f2937; font-size: 14px; margin: 0;">Thành công!</h4>
            <p style="font-size: 13px; color: #6b7280; margin: 2px 0 0 0;">{{ session('success') }}</p>
        </div>
        <i class="fa-solid fa-xmark" style="cursor: pointer; color: #9ca3af; font-size: 14px; padding: 4px;" onclick="dismissToastAlert()"></i>
    </div>

    <script>
        setTimeout(function() { dismissToastAlert(); }, 3500);
        function dismissToastAlert() {
            const toastElement = document.getElementById('laravelToastAlert');
            if (toastElement) {
                toastElement.classList.add('toast-fade-out');
                setTimeout(() => { toastElement.remove(); }, 400);
            }
        }
    </script>
@endif

<div class="space-y-6">

    <div class="flex justify-between items-center bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-gray-900">
                Danh sách phụ cấp
            </h1>
            <p class="text-gray-500 text-sm mt-0.5">
                Quản lý các loại phụ cấp trong hệ thống
            </p>
        </div>

        <a href="{{ route('admin.phu-cap.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 text-sm font-medium shadow-sm shadow-blue-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Thêm phụ cấp
        </a>
    </div>

    <div class="page-card m-0 page-card-bright overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="table-header-bright border-b border-gray-100">
                        <th class="text-left py-3.5 px-5">Mã</th>
                        <th class="text-left py-3.5 px-5">Tên phụ cấp</th>
                        <th class="text-left py-3.5 px-5">Loại</th>
                        <th class="text-left py-3.5 px-5">Số tiền</th>
                        <th class="text-left py-3.5 px-5">Trạng thái</th>
                        <th class="text-left py-3.5 px-5 text-center">Thao tác</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($phuCaps as $pc)
                    <tr class="hover:bg-slate-50/80 transition-colors text-gray-700">

                        <td class="py-3.5 px-5 font-medium text-gray-900">
                            <span class="bg-slate-100 text-slate-700 px-2 py-1 rounded text-xs font-mono">{{ $pc->ma }}</span>
                        </td>

                        <td class="py-3.5 px-5 font-semibold text-gray-800">
                            {{ $pc->ten }}
                        </td>

                        <td class="py-3.5 px-5 text-gray-500">
                            {{ $pc->loai_phu_cap }}
                        </td>

                        <td class="py-3.5 px-5 font-semibold text-emerald-600">
                            {{ number_format($pc->so_tien_mac_dinh, 0, ',', '.') }} đ
                        </td>

                        <td class="py-3.5 px-5">
                            @if($pc->trang_thai)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full"></span>Hoạt động
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                <span class="w-1.5 h-1.5 mr-1.5 bg-red-500 rounded-full"></span>Ngừng hoạt động
                            </span>
                            @endif
                        </td>

                        <td class="py-3.5 px-5 text-center">
                            <div class="flex items-center justify-center gap-2">

                                <a href="{{ route('admin.phu-cap.show', $pc->id) }}"
                                   class="action-btn btn-view"
                                   title="Xem chi tiết">
                                    <i class="fa-regular fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.phu-cap.edit', $pc->id) }}"
                                   class="action-btn btn-edit"
                                   title="Chỉnh sửa">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>

                                <form action="{{ route('admin.phu-cap.destroy', $pc->id) }}"
                                      method="POST"
                                      class="inline m-0 p-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa phụ cấp này không?')"
                                            class="action-btn btn-delete"
                                            title="Xóa bỏ">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400 font-medium bg-white">
                            <i class="fa-regular fa-folder-open block text-3xl mb-2 text-gray-300"></i>
                            Chưa có dữ liệu phụ cấp nào được tìm thấy
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 bg-white border-t border-gray-50 flex justify-end">
            {{ $phuCaps->links() }}
        </div>
    </div>

</div>
@endsection