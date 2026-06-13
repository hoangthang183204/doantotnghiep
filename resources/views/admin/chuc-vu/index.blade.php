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
                                    <div class="flex justify-center gap-1.5">
                                        
                                        {{-- Nút Sửa --}}
                                        <a href="{{ route('admin.chuc-vu.edit', $chucVu->id) }}"
                                           class="p-1.5 text-yellow-600 hover:bg-yellow-50 rounded-lg transition" 
                                           title="Sửa">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                
                                        {{-- NÚT ẨN / HIỂN THỊ CHỨC VỤ --}}
                                        <form action="{{ route('admin.chuc-vu.destroy', $chucVu->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                
                                            @if($chucVu->trang_thai == 1)
                                                {{-- Đang hoạt động -> Hiện nút Ẩn (Icon Mắt gạch chéo) --}}
                                                <button type="submit"
                                                        onclick="return confirm('Bạn có chắc muốn ẩn chức vụ này khỏi hệ thống?')"
                                                        class="p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition" 
                                                        title="Ẩn chức vụ">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                                    </svg>
                                                </button>
                                            @else
                                                {{-- Đã ẩn -> Hiện nút Hiển thị lại (Icon Mắt) --}}
                                                <button type="submit"
                                                        onclick="return confirm('Bạn có chắc muốn hiển thị lại chức vụ này?')"
                                                        class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" 
                                                        title="Hiển thị lại">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
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
