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
                                <td class="px-4 py-3">
                                    <div class="flex justify-center gap-1.5">
                                        
                                        {{-- Nút Xem chi tiết (Icon Con mắt) --}}
                                        <a href="{{ route('admin.phong-ban.show', $pb->id) }}" 
                                           class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" 
                                           title="Xem chi tiết">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                
                                        {{-- Nút Sửa (Icon Bút chì) --}}
                                        <a href="{{ route('admin.phong-ban.edit', $pb->id) }}" 
                                           class="p-1.5 text-yellow-600 hover:bg-yellow-50 rounded-lg transition" 
                                           title="Chỉnh sửa">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                
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
