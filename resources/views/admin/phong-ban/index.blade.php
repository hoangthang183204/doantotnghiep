@extends('layouts.admin')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                        Quản lý phòng ban
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Danh sách phòng ban trong hệ thống nhân sự
                    </p>
                </div>

                <a href="{{ route('admin.phong-ban.create') }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                    + Thêm phòng ban
                </a>
            </div>

            {{-- SEARCH --}}
            <div class="mt-4">
                <form method="GET" action="{{ route('admin.phong-ban.index') }}">
                    <div class="flex gap-3">
                        <input type="text" name="keyword" value="{{ request('keyword') }}"
                            placeholder="Tìm theo mã, tên phòng ban..."
                            class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2bg-white dark:bg-gray-700text-gray-800 dark:text-whiteplaceholder-gray-400 dark:placeholder-gray-300focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                            🔍 Tìm
                        </button>

                        <a href="{{ route('admin.phong-ban.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                            ↻ Reset
                        </a>
                    </div>
                </form>
            </div>

        </div>

        {{-- SUCCESS --}}
        @if (session('success'))
            <div class="px-4 py-3 rounded-lg bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200">
                {{ session('success') }}
            </div>
        @endif

        {{-- ERROR --}}
        @if (session('error'))
            <div class="px-4 py-3 rounded-lg bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200">
                {{ session('error') }}
            </div>
        @endif

        {{-- TABLE --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">

            <div class="overflow-x-auto">

                <table class="min-w-full text-sm text-gray-700 dark:text-gray-200">

                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                            <th class="p-3">MÃ PHÒNG</th>
                            <th class="p-3">TÊN PHÒNG BAN</th>
                            <th class="p-3">NGÂN SÁCH</th>
                            <th class="p-3">TRƯỞNG PHÒNG</th>
                            <th class="p-3">TRẠNG THÁI</th>
                            <th class="p-3 text-center">HÀNH ĐỘNG</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($phongBans as $pb)
                            <tr
                                class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">

                                <td class="p-3 font-medium">
                                    {{ $pb->ma_phong_ban }}
                                </td>

                                <td class="p-3">
                                    {{ $pb->ten_phong_ban }}
                                </td>

                                <td class="p-3">
                                    {{ number_format($pb->ngan_sach, 0, ',', '.') }} đ
                                </td>

                                <td class="p-3">
                                    @if ($pb->truong_phong_id)
                                        @if ($pb->truong_phong && $pb->truong_phong->hoSo)
                                            {{ $pb->truong_phong->hoSo->ho }}
                                            {{ $pb->truong_phong->hoSo->ten }}
                                        @else
                                            {{ $pb->truong_phong->ten_dang_nhap ?? '---' }}
                                        @endif
                                    @else
                                        <span class="text-gray-400 italic">
                                            Chưa cập nhật
                                        </span>
                                    @endif
                                </td>

                                <td class="p-3">
                                    @if ($pb->trang_thai == 1)
                                        <span class="text-xs px-2 py-1 bg-green-100 text-green-600 rounded-full">
                                            ✅ Hoạt động
                                        </span>
                                    @else
                                        <span class="text-xs px-2 py-1 bg-red-100 text-red-600 rounded-full">
                                            ⛔ Tạm dừng
                                        </span>
                                    @endif
                                </td>

                                {{-- ACTION --}}
                                <td class="p-3 text-center">

                                    <div class="relative inline-block text-left">
                                
                                        <button type="button" onclick="toggleDropdown({{ $pb->id }})"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                                            ⋮
                                        </button>
                                
                                        @if ($loop->last)
                                            {{-- Dòng cuối mở lên trên --}}
                                            <div id="dropdown-{{ $pb->id }}"
                                                class="hidden absolute right-0 bottom-full mb-2 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-xl z-[9999] overflow-hidden">
                                
                                                <a href="{{ route('admin.phong-ban.show', $pb->id) }}"
                                                    class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                                    {{-- Icon Con mắt (Xem chi tiết) --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4 mr-2 text-gray-600 dark:text-gray-400">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    Xem chi tiết
                                                </a>
                                
                                                <a href="{{ route('admin.phong-ban.edit', $pb->id) }}"
                                                    class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                                    {{-- Icon Bút chì (Chỉnh sửa) --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4 mr-2 text-yellow-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                    Chỉnh sửa
                                                </a>
                                
                                            </div>
                                        @else
                                            {{-- Các dòng khác mở xuống --}}
                                            <div id="dropdown-{{ $pb->id }}"
                                                class="hidden absolute right-0 top-full mt-2 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-xl z-[9999] overflow-hidden">
                                
                                                <a href="{{ route('admin.phong-ban.show', $pb->id) }}"
                                                    class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                                    {{-- Icon Con mắt (Xem chi tiết) --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4 mr-2 text-gray-600 dark:text-gray-400">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    Xem chi tiết
                                                </a>
                                
                                                <a href="{{ route('admin.phong-ban.edit', $pb->id) }}"
                                                    class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                                    {{-- Icon Bút chì (Chỉnh sửa) --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4 mr-2 text-yellow-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                    Chỉnh sửa
                                                </a>
                                
                                            </div>
                                        @endif
                                
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

            {{-- PAGINATION --}}
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $phongBans->appends(request()->query())->links() }}
            </div>

        </div>

    </div>

    <script>
        function toggleDropdown(id) {

            document.querySelectorAll('[id^="dropdown-"]').forEach(function(item) {

                if (item.id !== 'dropdown-' + id) {
                    item.classList.add('hidden');
                }

            });

            document.getElementById('dropdown-' + id).classList.toggle('hidden');
        }

        document.addEventListener('click', function(event) {

            if (!event.target.closest('.relative')) {

                document.querySelectorAll('[id^="dropdown-"]').forEach(function(item) {
                    item.classList.add('hidden');
                });

            }

        });
    </script>

@endsection
