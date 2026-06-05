@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

        <h1 class="text-xl font-bold text-gray-800 dark:text-white">
            Quản lý phòng ban
        </h1>

        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Danh sách phòng ban trong hệ thống nhân sự
        </p>

    </div>

    {{-- SUCCESS --}}
    @if (session('success'))
        <div class="px-4 py-3 rounded-lg bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE CARD --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">

        {{-- HEADER TABLE --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">

            <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                DANH SÁCH PHÒNG BAN
            </h2>

            <div class="flex items-center gap-3">

                <span class="text-xs px-2 py-0.5 rounded-full bg-gray-200 dark:bg-gray-600">
                    {{ $phongBans->count() }}
                </span>

                <a href="{{ route('admin.phong-ban.create') }}"
                   class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm">
                    + Thêm
                </a>

            </div>

        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="min-w-full text-sm text-gray-700 dark:text-gray-200">

                <thead>
                    <tr class="text-left border-b border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700">
                        <th class="p-3">MÃ PHÒNG</th>
                        <th class="p-3">TÊN PHÒNG BAN</th>
                        <th class="p-3">NGÂN SÁCH</th>
                        <th class="p-3">TRƯỞNG PHÒNG</th>
                        <th class="p-3">TRẠNG THÁI</th>
                        <th class="p-3 text-center">THAO TÁC</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($phongBans as $pb)
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">

                            {{-- MÃ --}}
                            <td class="p-3 font-medium">
                                {{ $pb->ma_phong_ban }}
                            </td>

                            {{-- TÊN --}}
                            <td class="p-3">
                                {{ $pb->ten_phong_ban }}
                            </td>

                            {{-- NGÂN SÁCH --}}
                            <td class="p-3">
                                {{ number_format($pb->ngan_sach, 0, ',', '.') }} đ
                            </td>

                            {{-- TRƯỞNG PHÒNG --}}
                            <td class="p-3">
                                {{ $pb->truong_phong->ho_ten ?? '---' }}
                            </td>

                            {{-- TRẠNG THÁI --}}
                            <td class="p-3">
                                @if ($pb->trang_thai == 1)
                                    <span class="text-xs px-2 py-1 bg-green-100 text-green-600 rounded-full">
                                        Hoạt động
                                    </span>
                                @else
                                    <span class="text-xs px-2 py-1 bg-red-100 text-red-600 rounded-full">
                                        Tạm dừng
                                    </span>
                                @endif
                            </td>

                            {{-- ACTION --}}
                            <td class="p-3 text-center">

                                <div class="flex justify-center gap-2">

                                    <a href="{{ route('admin.phong-ban.edit', $pb->id) }}"
                                       class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-lg text-xs">
                                        Sửa
                                    </a>

                                    <form method="POST"
                                          action="{{ route('admin.phong-ban.destroy', $pb->id) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                onclick="return confirm('Xóa phòng ban này?')"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs">
                                            Xóa
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center py-10 text-gray-500">
                                Không có phòng ban nào
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
@endsection