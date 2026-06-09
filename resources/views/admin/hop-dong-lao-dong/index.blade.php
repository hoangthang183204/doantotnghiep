@extends('layouts.admin')

@section('title', 'Quản lý hợp đồng lao động')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Quản lý hợp đồng lao động
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Danh sách hợp đồng lao động trong hệ thống
            </p>
        </div>

        <a href="{{ route('admin.hop-dong.create') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl shadow hover:bg-blue-700 transition">
            + Thêm hợp đồng
        </a>
    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-green-50 text-green-700 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE CARD --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow overflow-hidden">

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">

                {{-- HEADER --}}
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-center">#</th>
                        <th class="px-4 py-3 text-left">Số HĐ</th>
                        <th class="px-4 py-3 text-left">Nhân viên</th>
                        <th class="px-4 py-3 text-left">Chức vụ</th>
                        <th class="px-4 py-3 text-left">Loại HĐ</th>
                        <th class="px-4 py-3 text-left">Ngày bắt đầu</th>
                        <th class="px-4 py-3 text-left">Ngày kết thúc</th>
                        <th class="px-4 py-3 text-left">Lương</th>
                        <th class="px-4 py-3 text-left">Trạng thái</th>
                        <th class="px-4 py-3 text-center">Thao tác</th>
                    </tr>
                </thead>

                {{-- BODY --}}
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                @forelse($hopDongs as $index => $hd)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">

                        <td class="px-4 py-3 text-center text-gray-500">
                            {{ $hopDongs->firstItem() + $index }}
                        </td>

                        <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">
                            {{ $hd->so_hop_dong }}
                        </td>

                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                            {{ $hd->nguoi_dung->ho_ten ?? $hd->nguoi_dung->ten ?? '---' }}
                        </td>

                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                            {{ $hd->chuc_vu->ten ?? '---' }}
                        </td>

                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                            {{ ucfirst(str_replace('_', ' ', $hd->loai_hop_dong)) }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $hd->ngay_bat_dau ? \Carbon\Carbon::parse($hd->ngay_bat_dau)->format('d/m/Y') : '-' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $hd->ngay_ket_thuc ? \Carbon\Carbon::parse($hd->ngay_ket_thuc)->format('d/m/Y') : '---' }}
                        </td>

                        <td class="px-4 py-3 font-semibold text-green-600">
                            {{ number_format($hd->luong_co_ban, 0, ',', '.') }} đ
                        </td>

                        {{-- STATUS --}}
                        <td class="px-4 py-3">
                            @php
                                $status = [
                                    'tao_moi' => 'bg-gray-100 text-gray-700',
                                    'chua_hieu_luc' => 'bg-yellow-100 text-yellow-700',
                                    'hieu_luc' => 'bg-green-100 text-green-700',
                                    'het_han' => 'bg-orange-100 text-orange-700',
                                    'huy_bo' => 'bg-red-100 text-red-700',
                                ];
                            @endphp

                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $status[$hd->trang_thai_hop_dong] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst(str_replace('_', ' ', $hd->trang_thai_hop_dong)) }}
                            </span>
                        </td>

                        {{-- ACTIONS --}}
                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-2">

                                <a href="{{ route('admin.hop-dong.show', $hd->id) }}"
                                   class="px-3 py-1.5 text-xs bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                                    Xem
                                </a>

                                <a href="{{ route('admin.hop-dong.edit', $hd->id) }}"
                                   class="px-3 py-1.5 text-xs bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                                    Sửa
                                </a>

                                <form action="{{ route('admin.hop-dong.destroy', $hd->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa hợp đồng này?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="px-3 py-1.5 text-xs bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                        Xóa
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-12 text-gray-500">
                            Chưa có hợp đồng nào
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="px-4 py-4 border-t dark:border-gray-800">
            {{ $hopDongs->links() }}
        </div>

    </div>
</div>

@endsection