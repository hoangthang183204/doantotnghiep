@extends('layouts.admin')

@section('title', 'Danh sách phụ cấp')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* Chỉ giữ lại các định dạng cấu trúc khoảng cách, loại bỏ hoàn toàn các thuộc tính màu sắc tĩnh background/color */
.breadcrumb-wrapper {
    font-size: 13px;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Giữ hiệu ứng transition mượt mà khi đổi dòng */
.modern-table tbody tr {
    transition: background-color 0.15s ease;
}

/* Định dạng cấu trúc nút thao tác dạng icon nhỏ gọn */
.icon-action-btn {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: all 0.2s;
    font-size: 13px;
    text-decoration: none !important;
}

/* Định dạng hộp thoại Toast Alert góc màn hình tự thích ứng */
.custom-toast-layout {
    position: fixed;
    top: 24px;
    right: 24px;
    padding: 16px 20px;
    border-radius: 12px;
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
.toast-leave-active { opacity: 0; transform: translateX(40px); transition: all 0.4s ease; }
</style>

@if(session('success'))
    <div class="custom-toast-layout bg-white dark:bg-slate-800 border-l-4 border-emerald-500 shadow-lg dark:shadow-black/30 text-gray-900 dark:text-white" id="globalToastAlert">
        <div class="bg-emerald-100 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 rounded-full w-7 h-7 flex items-center justify-center text-sm flex-shrink: 0;">
            <i class="fa-solid fa-check"></i>
        </div>
        <div class="flex-grow">
            <h4 class="font-semibold text-sm m-0">Thành công!</h4>
            <p class="text-xs text-gray-500 dark:text-slate-400 m-0 mt-0.5">{{ session('success') }}</p>
        </div>
        <i class="fa-solid fa-xmark text-gray-400 hover:text-gray-600 dark:hover:text-slate-200 cursor-pointer text-sm p-1" onclick="hideToastAlert()"></i>
    </div>
    <script>
        setTimeout(function() { hideToastAlert(); }, 3500);
        function hideToastAlert() {
            const toast = document.getElementById('globalToastAlert');
            if (toast) {
                toast.classList.add('toast-leave-active');
                setTimeout(() => { toast.remove(); }, 400);
            }
        }
    </script>
@endif

<div class="container-fluid p-0">
    
    <div class="breadcrumb-wrapper text-gray-500 dark:text-slate-400">
        <a href="#" class="text-blue-600 dark:text-sky-400 font-medium hover:underline">Phụ cấp</a>
        <span>&gt;</span>
        <span class="text-gray-700 dark:text-slate-300">Danh sách phụ cấp</span>
    </div>

    <div class="flex justify-between items-center bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm mb-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white m-0">
                Danh sách phụ cấp
            </h1>
            <p class="text-gray-500 dark:text-slate-400 text-sm mt-1 m-0">
                Quản lý các loại phụ cấp trong hệ thống
            </p>
        </div>

        <a href="{{ route('admin.phu-cap.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 text-sm font-medium shadow-sm">
            <i class="fa-solid fa-plus text-xs"></i> Thêm phụ cấp
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full m-0 border-collapse modern-table">
                <thead>
                    <tr class="bg-gray-50 dark:bg-slate-900 border-b border-gray-200 dark:border-slate-700">
                        <th class="text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-slate-300 py-4 px-5">Mã</th>
                        <th class="text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-slate-300 py-4 px-5">Tên phụ cấp</th>
                        <th class="text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-slate-300 py-4 px-5">Loại</th>
                        <th class="text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-slate-300 py-4 px-5">Số tiền</th>
                        <th class="text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-slate-300 py-4 px-5">Trạng thái</th>
                        <th class="text-center text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-slate-300 py-4 px-5" style="width: 150px;">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($phuCaps as $pc)
                    <tr class="border-b border-gray-100 dark:border-slate-700/60 hover:bg-gray-50/80 dark:hover:bg-slate-700/50">
                        
                        <td class="py-4 px-5">
                            <span class="bg-gray-100 dark:bg-slate-700 text-gray-800 dark:text-slate-200 border border-gray-200 dark:border-slate-600 px-2 py-1 rounded text-xs font-mono font-semibold">
                                {{ $pc->ma }}
                            </span>
                        </td>

                        <td class="py-4 px-5 font-semibold text-gray-900 dark:text-white">
                            {{ $pc->ten }}
                        </td>

                        <td class="py-4 px-5 text-gray-600 dark:text-slate-300">
                            {{ $pc->loai_phu_cap }}
                        </td>

                        <td class="py-4 px-5 font-bold text-emerald-600 dark:text-emerald-400">
                            {{ number_format($pc->so_tien_mac_dinh, 0, ',', '.') }} đ
                        </td>

                        <td class="py-4 px-5">
                            @if($pc->trang_thai)
                                <span class="inline-block bg-green-100 dark:bg-emerald-950/60 text-green-700 dark:text-emerald-400 px-3 py-1 rounded-full text-xs font-semibold">
                                    Hoạt động
                                </span>
                            @else
                                <span class="inline-block bg-red-100 dark:bg-rose-950/60 text-red-700 dark:text-rose-400 px-3 py-1 rounded-full text-xs font-semibold">
                                    Ngừng hoạt động
                                </span>
                            @endif
                        </td>

                        <td class="py-4 px-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.phu-cap.show', $pc->id) }}"
                                   class="icon-action-btn bg-green-50 dark:bg-emerald-950/40 text-green-600 dark:text-emerald-400 hover:bg-green-100 dark:hover:bg-emerald-900/60"
                                   title="Xem chi tiết">
                                    <i class="fa-regular fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.phu-cap.edit', $pc->id) }}"
                                   class="icon-action-btn bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/60"
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
                                            class="icon-action-btn bg-red-50 dark:bg-rose-950/40 text-red-600 dark:text-rose-400 hover:bg-red-100 dark:hover:bg-rose-900/60 border-none"
                                            title="Xóa bỏ">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400 dark:text-slate-500 font-medium">
                            <i class="fa-regular fa-folder-open block text-3xl mb-2 text-gray-300 dark:text-slate-600"></i>
                            Chưa có dữ liệu phụ cấp nào được tìm thấy
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($phuCaps->hasPages())
        <div class="p-4 bg-gray-50 dark:bg-slate-900 border-t border-gray-200 dark:border-slate-700 flex justify-end">
            {{ $phuCaps->links() }}
        </div>
        @endif
    </div>

</div>
@endsection