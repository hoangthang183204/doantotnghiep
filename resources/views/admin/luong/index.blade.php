@extends('layouts.admin')

@section('title', 'Danh sách lương cơ bản')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

        <h1 class="text-xl font-bold text-gray-800 dark:text-white">
            Danh sách lương cơ bản
        </h1>

        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Quản lý thông tin lương nhân viên
        </p>

        {{-- ACTION --}}
        <div class="mt-5 border-t border-gray-200 dark:border-gray-700 pt-4 flex gap-3">

            {{-- <a href="#" class="bg-blue-700 hover:bg-blue-800 text-white px-5 py-2 rounded-lg transition">
                + Thêm lương
            </a>

            <a href="#" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg transition">
                Xuất Excel
            </a> --}}

        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

        <div class="overflow-x-auto">

            <table class="min-w-full text-gray-700 dark:text-gray-200">

                <thead>
                    <tr class="text-left text-sm text-gray-600 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">
                        <th class="p-3">STT</th>
                        <th class="p-3">NHÂN VIÊN</th>
                        <th class="p-3">CHỨC VỤ</th>
                        <th class="p-3">HỢP ĐỒNG</th>
                        <th class="p-3">LƯƠNG CƠ BẢN</th>
                        <th class="p-3">PHỤ CẤP</th>
                        <th class="p-3">TỔNG LƯƠNG</th>
                        <th class="p-3">NGÀY TẠO</th>
                        <th class="p-3 text-center">THAO TÁC</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($luongs as $index => $luong)

                    @php
                        $tongLuong =
                            $luong->luong_co_ban +
                            $luong->phu_cap +
                            $luong->tien_thuong -
                            $luong->tien_phat;
                    @endphp

                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                        {{-- STT --}}
                        <td class="p-3 text-sm">
                            {{ $index + 1 }}
                        </td>

                        {{-- NHÂN VIÊN --}}
                        <td class="p-3">
                                


                            <div class="font-semibold text-gray-800 dark:text-white">
                                {{ $luong->nguoiDung->ho_ten ?? '' }}
                            </div>
                        </td>

                        {{-- CHỨC VỤ --}}
                        <td class="p-3 text-sm">
                            
                            {{ $luong->hopDongLaoDong->chucVu->ten ?? '---' }}
                        </td>

                        {{-- HỢP ĐỒNG --}}
                        <td class="p-3 text-sm">
                            {{ $luong->hopDongLaoDong->so_hop_dong ?? '---' }}
                        </td>

                        {{-- LƯƠNG CƠ BẢN --}}
                        <td class="p-3 text-sm text-green-600 font-semibold">
                            {{ number_format($luong->luong_co_ban, 0, ',', '.') }} đ
                        </td>

                        {{-- PHỤ CẤP --}}
                        <td class="p-3 text-sm">
                            {{ number_format($luong->phu_cap, 0, ',', '.') }} đ
                        </td>

                        {{-- TỔNG LƯƠNG --}}
                        <td class="p-3 text-sm font-bold text-blue-600">
                            {{ number_format($tongLuong, 0, ',', '.') }} đ
                        </td>

                        {{-- NGÀY TẠO --}}
                        <td class="p-3 text-sm">
                            {{ optional($luong->created_at)->format('d/m/Y') }}
                        </td>

                        {{-- ACTION --}}
                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-1.5">

                                {{-- XEM --}}
                                <a href="{{ route('admin.luong.show', $luong->id) }}"
                                   class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                   title="Xem">

                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>

                                </a>

                                {{-- SỬA --}}
                                <a href="{{ route('admin.luong.edit', $luong->id) }}"
                                   class="p-1.5 text-yellow-600 hover:bg-yellow-50 rounded-lg transition"
                                   title="Sửa">

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
                        <td colspan="9" class="text-center py-10 text-gray-500 dark:text-gray-400">
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