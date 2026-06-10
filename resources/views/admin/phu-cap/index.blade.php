@extends('layouts.admin')

@section('title', 'Danh sách phụ cấp')

@section('content')
<div class="space-y-6">

    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Danh sách phụ cấp
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                Quản lý các loại phụ cấp trong hệ thống
            </p>
        </div>

        <a href="{{ route('admin.phu-cap.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Thêm phụ cấp
        </a>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr class="border-b dark:border-gray-700">
                        <th class="text-left py-3 px-4">Mã</th>
                        <th class="text-left py-3 px-4">Tên phụ cấp</th>
                        <th class="text-left py-3 px-4">Loại</th>
                        <th class="text-left py-3 px-4">Số tiền</th>
                        
                        <th class="text-left py-3 px-4">Trạng thái</th>
                        <th class="text-left py-3 px-4">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($phuCaps as $pc)
                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">

                        <td class="py-3 px-4 text-gray-900 dark:text-white">
                            {{ $pc->ma }}
                        </td>

                        <td class="py-3 px-4 text-gray-900 dark:text-white">
                            {{ $pc->ten }}
                        </td>

                        <td class="py-3 px-4 text-gray-600 dark:text-gray-400">
                            {{ $pc->loai_phu_cap }}
                        </td>

                        <td class="py-3 px-4 text-gray-600 dark:text-gray-400">
                            {{ number_format($pc->so_tien_mac_dinh, 0, ',', '.') }} đ
                        </td>

                        

                        <td class="py-3 px-4">
                            @if($pc->trang_thai)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                Hoạt động
                            </span>
                            @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                Ngừng hoạt động
                            </span>
                            @endif
                        </td>

                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">

                                <a href="{{ route('admin.phu-cap.show',$pc->id) }}"
                                    class="text-blue-600 hover:text-blue-800"
                                    title="Xem">
                                    👁
                                </a>

                                <a href="{{ route('admin.phu-cap.edit',$pc->id) }}"
                                    class="text-yellow-600 hover:text-yellow-800"
                                    title="Sửa">
                                    ✏️
                                </a>

                                <form action="{{ route('admin.phu-cap.destroy',$pc->id) }}"
                                    method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        onclick="return confirm('Bạn có chắc muốn xóa?')"
                                        class="text-red-600 hover:text-red-800"
                                        title="Xóa">
                                        🗑
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7"
                            class="py-8 text-center text-gray-500 dark:text-gray-400">
                            Chưa có dữ liệu phụ cấp
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <div class="p-4">
            {{ $phuCaps->links() }}
        </div>
    </div>

</div>
@endsection