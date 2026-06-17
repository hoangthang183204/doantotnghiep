@extends('layouts.admin')

@section('title', 'Danh sách bảng lương')

@section('content')

<div class="min-h-screen p-6 bg-gray-50 dark:bg-slate-900">

<div class="max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">

        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Danh sách bảng lương
            </h1>
            <p class="text-gray-500 dark:text-slate-400 mt-1">
                Quản lý bảng lương theo tháng
            </p>
        </div>

        <a href="{{ route('admin.bang-luong.create') }}"
           class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition">

            <i class="fa-solid fa-plus"></i>
            Tính lương mới
        </a>

    </div>

    {{-- TABLE CARD --}}
    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                {{-- HEADER --}}
                <thead class="bg-gray-50 dark:bg-slate-900 border-b border-gray-200 dark:border-slate-700">
                    <tr class="text-left text-gray-500 dark:text-slate-400">

                        <th class="p-4 font-medium">Mã bảng lương</th>
                        <th class="p-4 font-medium">Tháng/Năm</th>
                        <th class="p-4 font-medium">Nhân viên</th>
                        <th class="p-4 font-medium">Tổng lương</th>
                        <th class="p-4 font-medium">Trạng thái</th>
                        <th class="p-4 font-medium">Người xử lý</th>
                        <th class="p-4 font-medium text-right">Thao tác</th>

                    </tr>
                </thead>

                {{-- BODY --}}
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">

                    @forelse($bangLuongs as $bl)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/40 transition">

                        {{-- MÃ --}}
                        <td class="p-4 font-semibold text-gray-900 dark:text-white">
                            {{ $bl->ma_bang_luong }}
                        </td>

                        {{-- THÁNG --}}
                        <td class="p-4 text-gray-600 dark:text-slate-300">
                            Tháng {{ $bl->thang }}/{{ $bl->nam }}
                        </td>

                        {{-- NHÂN VIÊN --}}
                        <td class="p-4 text-gray-600 dark:text-slate-300">
                            <div class="flex items-center gap-2">

                                <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-950
                                            flex items-center justify-center text-blue-600 dark:text-sky-400 text-xs">
                                    <i class="fa-solid fa-users"></i>
                                </div>

                                {{ $bl->luongNhanViens->count() }}

                            </div>
                        </td>

                        {{-- TỔNG LƯƠNG --}}
                        <td class="p-4 font-semibold text-green-600 dark:text-green-400">
                            {{ number_format($bl->luongNhanViens->sum('tong_luong')) }} đ
                        </td>

                        {{-- TRẠNG THÁI --}}
                        <td class="p-4">
                            <span class="px-3 py-1 text-xs rounded-full font-medium
                                @if($bl->trang_thai == 'dang_tao')
                                    bg-yellow-100 text-yellow-800
                                @elseif($bl->trang_thai == 'cho_duyet')
                                    bg-blue-100 text-blue-800
                                @elseif($bl->trang_thai == 'da_duyet')
                                    bg-green-100 text-green-800
                                @else
                                    bg-gray-100 text-gray-800
                                @endif">
                                {{ $bl->trang_thai_text }}
                            </span>
                        </td>

                        {{-- NGƯỜI XỬ LÝ --}}
                        <td class="p-4 text-gray-600 dark:text-slate-300">
                            {{ $bl->nguoiXuLy->ten_dang_nhap ?? 'N/A' }}
                        </td>

                        {{-- ACTION --}}
                        <td class="p-4">
                            <div class="flex justify-end items-center gap-2">

                                {{-- VIEW --}}
                                <a href="{{ route('admin.bang-luong.show', $bl->id) }}"
   class="flex items-center gap-1 px-3 py-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg text-sm">

    <i class="fa-regular fa-eye"></i>
    <span>Xem</span>

</a>

                                {{-- DUYỆT --}}
                                @if($bl->trang_thai == 'dang_tao')
                                <form action="{{ route('admin.bang-luong.duyet', $bl->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <button class="p-2 text-green-600 hover:bg-green-50 dark:hover:bg-slate-700 rounded-lg">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>

                                {{-- DELETE --}}
                                <form action="{{ route('admin.bang-luong.destroy', $bl->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button onclick="return confirm('Bạn có chắc muốn xóa?')"
                                            class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-slate-700 rounded-lg">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                                @endif

                            </div>
                        </td>

                    </tr>
                    @empty

                    <tr>
                        <td colspan="7" class="p-10 text-center text-gray-500 dark:text-slate-400">
                            <i class="fa-regular fa-folder-open text-2xl mb-2"></i><br>
                            Chưa có bảng lương nào
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        <div class="p-4 border-t border-gray-100 dark:border-slate-700">
            {{ $bangLuongs->links() }}
        </div>

    </div>

</div>

</div>

@endsection