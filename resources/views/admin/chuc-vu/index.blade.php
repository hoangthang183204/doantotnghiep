@extends('layouts.admin')

@section('title', 'Quản lý chức vụ')

@section('content')

    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

            <div class="flex items-center justify-between">

                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                        Quản lý chức vụ
                    </h1>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Danh sách chức vụ trong hệ thống
                    </p>
                </div>

                <a href="{{ route('admin.chuc-vu.create') }}"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    + Thêm chức vụ
                </a>

            </div>

        </div>

        {{-- THÔNG BÁO --}}
        @if (session('success'))
            <div
                class="bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        {{-- TABLE --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-gray-50 dark:bg-gray-700">

                        <tr>

                            <th class="px-4 py-3 text-left font-semibold text-gray-800 dark:text-gray-100">
                                ID
                            </th>

                            <th class="px-4 py-3 text-left font-semibold text-gray-800 dark:text-gray-100">
                                Tên chức vụ
                            </th>

                            <th class="px-4 py-3 text-left font-semibold text-gray-800 dark:text-gray-100">
                                Mã
                            </th>

                            <th class="px-4 py-3 text-left font-semibold text-gray-800 dark:text-gray-100">
                                Phòng ban
                            </th>

                            <th class="px-4 py-3 text-left font-semibold text-gray-800 dark:text-gray-100">
                                Trạng thái
                            </th>

                            <th class="px-4 py-3 text-center font-semibold text-gray-800 dark:text-gray-100">
                                Thao tác
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($chucVus as $chucVu)
                            <tr
                                class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">

                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                                    {{ $chucVu->id }}
                                </td>

                                <td class="px-4 py-3 font-medium text-gray-800 dark:text-white">
                                    {{ $chucVu->ten }}
                                </td>

                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                    {{ $chucVu->ma }}
                                </td>

                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                    {{ $chucVu->phong_ban->ten_phong_ban ?? '-' }}
                                </td>

                                <td class="px-4 py-3">

                                    @if ($chucVu->trang_thai)
                                        <span
                                            class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300">
                                            Hoạt động
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300">
                                            Ngừng hoạt động
                                        </span>
                                    @endif

                                </td>

                                <td class="px-4 py-3">

                                    <div class="flex items-center justify-center gap-2">

                                        <a href="{{ route('admin.chuc-vu.edit', $chucVu->id) }}"
                                            class="px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm transition">
                                            Sửa
                                        </a>

                                        {{-- NÚT ẨN / HIỂN THỊ CHỨC VỤ --}}
                                        <form action="{{ route('admin.chuc-vu.destroy', $chucVu->id) }}" method="POST">

                                            @csrf
                                            @method('DELETE')

                                            @if($chucVu->trang_thai == 1)
                                                <button type="submit"
                                                    onclick="return confirm('Bạn có chắc muốn ẩn chức vụ này khỏi hệ thống?')"
                                                    class="px-3 py-1.5 bg-slate-600 hover:bg-slate-700 text-white rounded-lg text-sm transition">
                                                    Ẩn
                                                </button>
                                            @else
                                                <button type="submit"
                                                    onclick="return confirm('Bạn có chắc muốn hiển thị lại chức vụ này?')"
                                                    class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm transition">
                                                    Hiển thị
                                                </button>
                                            @endif

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                    Chưa có dữ liệu chức vụ
                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        {{-- PAGINATION --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4">
            {{ $chucVus->links() }}
        </div>

    </div>

@endsection