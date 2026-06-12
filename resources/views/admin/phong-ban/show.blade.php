@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Chi tiết phòng ban
                </h1>

                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Thông tin chi tiết bản ghi phòng ban
                </p>
            </div>

            <div class="flex gap-2">

                <a href="{{ route('admin.phong-ban.index') }}"
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                    ← Quay lại
                </a>

                <a href="{{ route('admin.phong-ban.edit', $phongBan->id) }}"
                    class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition">
                    ✏️ Chỉnh sửa
                </a>

            </div>

        </div>

    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div
            class="p-4 rounded-lg bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- ERROR --}}
    @if(session('error'))
        <div
            class="p-4 rounded-lg bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200">
            {{ session('error') }}
        </div>
    @endif

    {{-- VALIDATION ERROR --}}
    @if($errors->any())
        <div
            class="p-4 rounded-lg bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200">

            <strong>Có lỗi xảy ra:</strong>

            <ul class="list-disc ml-5 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif

    {{-- THÔNG TIN PHÒNG BAN --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">

        <div class="px-6 py-4 bg-blue-600 text-white">
            <h2 class="text-lg font-semibold">
                🏢 Chi tiết phòng ban
            </h2>
        </div>

        <div class="p-6">

            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                        <tr>
                            <th
                                class="py-4 text-left w-1/3 text-gray-500 dark:text-gray-400">
                                Mã phòng ban
                            </th>

                            <td class="py-4">
                                <span
                                    class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-lg">
                                    {{ $phongBan->ma_phong_ban }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <th
                                class="py-4 text-left text-gray-500 dark:text-gray-400">
                                Tên phòng ban
                            </th>

                            <td
                                class="py-4 font-semibold text-gray-800 dark:text-white">
                                {{ $phongBan->ten_phong_ban }}
                            </td>
                        </tr>

                        <tr>
                            <th
                                class="py-4 text-left text-gray-500 dark:text-gray-400">
                                Trưởng phòng
                            </th>

                            <td class="py-4 text-gray-700 dark:text-gray-200">

                                @if($phongBan->truong_phong)

                                    @if($phongBan->truong_phong->hoSo)

                                        {{ $phongBan->truong_phong->hoSo->ho }}
                                        {{ $phongBan->truong_phong->hoSo->ten }}

                                    @else

                                        {{ $phongBan->truong_phong->ten_dang_nhap }}

                                    @endif

                                @else

                                    <span class="text-gray-400 italic">
                                        Chưa cập nhật
                                    </span>

                                @endif

                            </td>
                        </tr>

                        <tr>
                            <th
                                class="py-4 text-left text-gray-500 dark:text-gray-400">
                                Ngân sách
                            </th>

                            <td class="py-4 text-gray-700 dark:text-gray-200">
                                {{ number_format($phongBan->ngan_sach, 0, ',', '.') }} đ
                            </td>
                        </tr>

                        <tr>
                            <th
                                class="py-4 text-left text-gray-500 dark:text-gray-400">
                                Mô tả
                            </th>

                            <td class="py-4 text-gray-700 dark:text-gray-200">
                                {{ $phongBan->mo_ta ?: 'Chưa có mô tả' }}
                            </td>
                        </tr>

                        <tr>
                            <th
                                class="py-4 text-left text-gray-500 dark:text-gray-400">
                                Trạng thái
                            </th>

                            <td class="py-4">

                                @if($phongBan->trang_thai == 1)

                                    <span
                                        class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-full text-sm">
                                        ✅ Hoạt động
                                    </span>

                                @else

                                    <span
                                        class="px-3 py-1 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-full text-sm">
                                        ⛔ Tạm dừng
                                    </span>

                                @endif

                            </td>
                        </tr>

                        <tr>
                            <th
                                class="py-4 text-left text-gray-500 dark:text-gray-400">
                                Ngày tạo
                            </th>

                            <td class="py-4 text-blue-600 dark:text-blue-400">

                                @if($phongBan->created_at)
                                    {{ $phongBan->created_at->format('d/m/Y H:i:s') }}
                                @else
                                    <span class="text-gray-400">
                                        Chưa xác định
                                    </span>
                                @endif

                            </td>
                        </tr>

                        <tr>
                            <th
                                class="py-4 text-left text-gray-500 dark:text-gray-400">
                                Ngày cập nhật
                            </th>

                            <td class="py-4 text-yellow-600 dark:text-yellow-400">

                                @if($phongBan->updated_at)
                                    {{ $phongBan->updated_at->format('d/m/Y H:i:s') }}
                                @else
                                    <span class="text-gray-400">
                                        Chưa cập nhật
                                    </span>
                                @endif

                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    {{-- ACTION --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

        <div class="flex flex-wrap justify-center gap-3">

            <a href="{{ route('admin.phong-ban.index') }}"
                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                ← Quay lại danh sách
            </a>

            <a href="{{ route('admin.phong-ban.edit', $phongBan->id) }}"
                class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition">
                ✏️ Chỉnh sửa
            </a>

            <button
                onclick="window.print()"
                class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg transition">
                🖨️ In
            </button>

        </div>

    </div>

</div>
@endsection