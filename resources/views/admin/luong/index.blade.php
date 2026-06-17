@extends('layouts.admin')

@section('content')
<div class="p-6">

    <div class="flex justify-between items-center mb-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            Danh sách lương cơ bản
        </h1>
        <p class="text-gray-500 mt-1">
            Quản lý thông tin lương nhân viên
        </p>
    </div>

    <div class="flex gap-2">

        {{-- EXPORT nếu có --}}
        {{-- <a href="{{ route('luong.export') ?? '#' }}"
           class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
            ⬇ Xuất Excel
        </a> --}}

        {{-- THÊM MỚI --}}
        {{-- <a href="{{ route('admin.luong.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            + Thêm lương
        </a> --}}

    </div>

</div>


    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-4 py-3">STT</th>
                        <th class="px-4 py-3">Họ và tên</th>
                        <th class="px-4 py-3">Chức vụ</th>
                        <th class="px-4 py-3">Số hợp đồng</th>
                        <th class="px-4 py-3">Lương cơ bản</th>
                        <th class="px-4 py-3">Phụ cấp</th>
                        <th class="px-4 py-3">Tổng lương</th>
                        <th class="px-4 py-3">Ngày tạo</th>
                        <th class="px-4 py-3">Ngày hiệu lực</th>
                        <th class="px-4 py-3 text-center">Thao tác</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse($luongs as $index => $luong)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                {{ $index + 1 }}
                            </td>

                            <td class="px-4 py-3 font-medium">
                            {{ $luong->nguoiDung->ho_ten ?? '' }}
                        </td>

                            <td class="px-4 py-3">
                                {{ $luong->hopDongLaoDong->chucVu->ten ?? 'N/A' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $luong->hopDongLaoDong->so_hop_dong ?? 'N/A' }}
                            </td>

                            <td class="px-4 py-3 text-green-600 font-semibold">
                                {{ number_format($luong->luong_co_ban, 0, ',', '.') }} đ
                            </td>

                            <td class="px-4 py-3">
                                {{ number_format($luong->phu_cap, 0, ',', '.') }} đ
                            </td>

                            <td class="px-4 py-3 font-bold text-blue-600">
                                {{ number_format(
                                    $luong->luong_co_ban +
                                    $luong->phu_cap +
                                    $luong->tien_thuong -
                                    $luong->tien_phat,
                                    0,
                                    ',',
                                    '.'
                                ) }} đ
                            </td>

                            <td class="px-4 py-3">
                                {{ optional($luong->created_at)->format('d/m/Y') }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $luong->hopDongLaoDong->ngay_bat_dau ?? '' }}
                            </td>

                            <td class="px-4 py-3">
    <div class="flex justify-center gap-2">

        {{-- XEM --}}
        <a href="{{ route('admin.luong.show', $luong->id) }}"
           class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
           title="Xem chi tiết">

            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
        </a>

        {{-- SỬA --}}
        <a href="{{ route('admin.luong.edit', $luong->id) }}"
           class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition"
           title="Chỉnh sửa">

            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        </a>

    </div>
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-8 text-gray-500">
                                Chưa có dữ liệu lương
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection