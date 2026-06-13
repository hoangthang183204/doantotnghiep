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
                                
                                        {{-- Nút Sửa (Icon Bút chì - Dạng Solid thu nhỏ) --}}
                                        <a href="{{ route('admin.chuc-vu.edit', $chucVu->id) }}"
                                            class="inline-flex items-center justify-center p-2 text-yellow-500 hover:text-yellow-600 hover:bg-yellow-50 rounded-full transition-all duration-200" 
                                            title="Sửa">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
                                                <path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z" />
                                            </svg>
                                        </a>
                                
                                        {{-- NÚT ẨN / HIỂN THỊ CHỨC VỤ --}}
                                        <form action="{{ route('admin.chuc-vu.destroy', $chucVu->id) }}" method="POST" class="m-0 p-0 flex items-center">
                                            @csrf
                                            @method('DELETE')
                                
                                            @if($chucVu->trang_thai == 1)
                                                {{-- Trạng thái = 1 -> Đang hoạt động -> Hiện nút Ẩn (Icon Mắt gạch chéo - Dạng Solid thu nhỏ) --}}
                                                <button type="submit"
                                                    onclick="return confirm('Bạn có chắc muốn ẩn chức vụ này khỏi hệ thống?')"
                                                    class="inline-flex items-center justify-center p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-200 rounded-full transition-all duration-200" 
                                                    title="Ẩn chức vụ">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                        <path fill-rule="evenodd" d="M3.53 2.47a.75.75 0 00-1.06 1.06l18 18a.75.75 0 101.06-1.06l-18-18zM22.676 12.553a11.249 11.249 0 01-2.631 4.31l-3.099-3.099a5.25 5.25 0 00-6.71-6.71L7.759 4.577a11.217 11.217 0 014.242-1.22c4.981 0 9.07 3.013 10.675 8.163a.75.75 0 010 .533z" clip-rule="evenodd" />
                                                        <path fill-rule="evenodd" d="M1.325 11.467a.75.75 0 000 .533c1.605 5.15 5.694 8.163 10.675 8.163a11.217 11.217 0 004.242-1.22l-3.099-3.099a5.25 5.25 0 01-6.71-6.71L3.956 6.864a11.249 11.249 0 00-2.631 4.603z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            @else
                                                {{-- Trạng thái = 0 -> Đã ẩn -> Hiện nút Hiển thị lại (Icon Mắt - Dạng Solid thu nhỏ) --}}
                                                <button type="submit"
                                                    onclick="return confirm('Bạn có chắc muốn hiển thị lại chức vụ này?')"
                                                    class="inline-flex items-center justify-center p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded-full transition-all duration-200" 
                                                    title="Hiển thị lại">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                        <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                                        <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" clip-rule="evenodd" />
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
