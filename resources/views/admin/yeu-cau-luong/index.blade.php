@extends('layouts.admin')

@section('title', 'Yêu cầu xem xét lương')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

        <h1 class="text-xl font-bold text-gray-800 dark:text-white">
            Danh sách yêu cầu xem xét lương
        </h1>

        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Quản lý các yêu cầu khi nhân viên khiếu nại phiếu lương
        </p>

    </div>

    {{-- TABLE --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700 text-left text-sm text-gray-600 dark:text-gray-300">
                        <th class="p-3">STT</th>
                        <th class="p-3">Nhân viên</th>
                        <th class="p-3">Kỳ lương</th>
                        <th class="p-3">Lý do</th>
                        <th class="p-3">Ngày gửi</th>
                        <th class="p-3">Trạng thái</th>
                        <th class="p-3 text-center">Thao tác</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($yeuCaus as $index => $yc)

                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                        <td class="p-3">
                            {{ $index + 1 }}
                        </td>

                        <td class="p-3">

                            <div class="font-semibold text-gray-800 dark:text-white">
                                {{ $yc->nguoiDung->ho_ten ?? $yc->nguoiDung->ten_dang_nhap }}
                            </div>

                        </td>

                        <td class="p-3">

                            Tháng
                            {{ $yc->luongNhanVien->luong_thang }}/{{ $yc->luongNhanVien->luong_nam }}

                        </td>

                        <td class="p-3">

                            {{ Str::limit($yc->ly_do,60) }}

                        </td>

                        <td class="p-3">

                            {{ $yc->created_at->format('d/m/Y H:i') }}

                        </td>

                        <td class="p-3">

                            @if($yc->trang_thai=='cho_duyet')

                                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                    Chờ duyệt
                                </span>

                            @elseif($yc->trang_thai=='da_duyet')

                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                    Đã duyệt
                                </span>

                            @else

                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                    Từ chối
                                </span>

                            @endif

                        </td>

                        <td class="p-3">

                            <div class="flex justify-center">

                                <a href="{{ route('admin.yeu-cau-luong.show', $yc->id) }}"
                                   class="p-2 rounded-lg text-blue-600 hover:bg-blue-50"
                                   title="Chi tiết">

                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5
                                                 12 5c4.478 0 8.268 2.943
                                                 9.542 7-1.274 4.057-5.064
                                                 7-9.542 7-4.477
                                                 0-8.268-2.943-9.542-7z"/>

                                    </svg>

                                </a>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7"
                            class="text-center py-10 text-gray-500 dark:text-gray-400">

                            Chưa có yêu cầu nào.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection