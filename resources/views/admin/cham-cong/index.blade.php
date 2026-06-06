@extends('layouts.admin')

@section('title', 'Danh sách chấm công')

@section('content')

    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                        Quản lý chấm công
                    </h1>

                    <p class="text-gray-500 dark:text-gray-400 mt-1">
                        Theo dõi và quản lý dữ liệu chấm công nhân viên
                    </p>
                </div>

                <a href="{{ route('admin.cham-cong.create') }}"
                    class="inline-flex items-center px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>

                    Thêm chấm công
                </a>

            </div>

        </div>

        {{-- THỐNG KÊ --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Tổng bản ghi
                </p>

                <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-2">
                    {{ $chamCongs->total() }}
                </h3>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
                <p class="text-sm text-green-600">
                    Đúng giờ
                </p>

                <h3 class="text-3xl font-bold text-green-600 mt-2">
                    {{ \App\Models\ChamCong::where('trang_thai', 'dung_gio')->count() }}
                </h3>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
                <p class="text-sm text-yellow-600">
                    Đi muộn
                </p>

                <h3 class="text-3xl font-bold text-yellow-600 mt-2">
                    {{ \App\Models\ChamCong::where('trang_thai', 'di_muon')->count() }}
                </h3>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
                <p class="text-sm text-red-600">
                    Không chấm công
                </p>

                <h3 class="text-3xl font-bold text-red-600 mt-2">
                    {{ \App\Models\ChamCong::where('trang_thai', 'khong_cham_cong')->count() }}
                </h3>
            </div>

        </div>

        {{-- TÌM KIẾM --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">

            <form method="GET">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    {{-- Tên nhân viên --}}
                    <div>
                        <label class="block mb-2 font-medium">
                            Tìm theo tên
                        </label>

                        <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Nhập tên..."
                            class="w-full px-4 py-2 border rounded-lg">
                    </div>

                    {{-- Trạng thái --}}
                    <div>
                        <label class="block mb-2 font-medium">
                            Trạng thái
                        </label>

                        <select name="trang_thai" class="w-full px-4 py-2 border rounded-lg">

                            <option value="">-- Tất cả trạng thái --</option>

                            <option value="dung_gio" {{ request('trang_thai') == 'dung_gio' ? 'selected' : '' }}>
                                Đúng giờ
                            </option>

                            <option value="di_muon" {{ request('trang_thai') == 'di_muon' ? 'selected' : '' }}>
                                Đi muộn
                            </option>

                            <option value="ve_som" {{ request('trang_thai') == 've_som' ? 'selected' : '' }}>
                                Về sớm
                            </option>

                            <option value="khong_cham_cong"
                                {{ request('trang_thai') == 'khong_cham_cong' ? 'selected' : '' }}>
                                Không chấm công
                            </option>

                        </select>
                    </div>

                    {{-- Ngày chấm công --}}
                    <div>
                        <label class="block mb-2 font-medium">
                            Ngày chấm công
                        </label>

                        <input type="date" name="ngay_cham_cong" value="{{ request('ngay_cham_cong') }}"
                            class="w-full px-4 py-2 border rounded-lg">
                    </div>

                    {{-- Từ ngày --}}
                    <div>
                        <label class="block mb-2 font-medium">
                            Từ ngày
                        </label>

                        <input type="date" name="tu_ngay" value="{{ request('tu_ngay') }}"
                            class="w-full px-4 py-2 border rounded-lg">
                    </div>

                    {{-- Đến ngày --}}
                    <div>
                        <label class="block mb-2 font-medium">
                            Đến ngày
                        </label>

                        <input type="date" name="den_ngay" value="{{ request('den_ngay') }}"
                            class="w-full px-4 py-2 border rounded-lg">
                    </div>

                </div>

                <div class="mt-5 flex gap-3">

                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg">

                        Tìm kiếm
                    </button>

                    <a href="{{ route('admin.cham-cong.index') }}" class="px-5 py-2 bg-gray-500 text-white rounded-lg">

                        Làm mới
                    </a>

                    <a href="{{ route('admin.cham-cong.export', request()->query()) }}"
                        class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">

                        Xuất Excel
                    </a>

                </div>

            </form>

        </div>

        {{-- THÔNG BÁO --}}
        @if (session('success'))
            <div id="alert-success"
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-sm">

                <div class="flex justify-between items-center">

                    <span>
                        {{ session('success') }}
                    </span>

                    <button type="button" onclick="document.getElementById('alert-success').remove()"
                        class="font-bold text-green-700">

                        ×
                    </button>

                </div>

            </div>
        @endif

        @if (session('error'))
            <div id="alert-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-sm">

                <div class="flex justify-between items-center">

                    <span>
                        {{ session('error') }}
                    </span>

                    <button type="button" onclick="document.getElementById('alert-error').remove()"
                        class="font-bold text-red-700">

                        ×
                    </button>

                </div>

            </div>
        @endif

        {{-- TABLE --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">

            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead class="bg-gray-100 dark:bg-gray-700">

                        <tr>

                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">
                                ID
                            </th>

                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">
                                Nhân viên
                            </th>

                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">
                                Ngày
                            </th>

                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">
                                Giờ vào
                            </th>

                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">
                                Giờ ra
                            </th>

                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">
                                Số giờ
                            </th>

                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200">
                                Trạng thái
                            </th>

                            <th class="px-4 py-3 text-center text-gray-700 dark:text-gray-200">
                                Thao tác
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($chamCongs as $item)
                            <tr
                                class="
                        border-b
                        border-gray-200
                        dark:border-gray-700
                        hover:bg-gray-50
                        dark:hover:bg-gray-700
                        transition">

                                <td class="px-4 py-4 text-gray-800 dark:text-gray-100">
                                    {{ $item->id }}
                                </td>

                                <td class="px-4 py-4 text-gray-800 dark:text-gray-100 font-medium">
                                    {{ $item->nguoi_dung->hoSo
                                        ? $item->nguoi_dung->hoSo->ho . ' ' . $item->nguoi_dung->hoSo->ten
                                        : $item->nguoi_dung->ten_dang_nhap }}
                                </td>

                                <td class="px-4 py-4 text-gray-800 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($item->ngay_cham_cong)->format('d/m/Y') }}
                                </td>

                                <td class="px-4 py-4 text-gray-800 dark:text-gray-100">
                                    {{ $item->gio_vao ?? '--' }}
                                </td>

                                <td class="px-4 py-4 text-gray-800 dark:text-gray-100">
                                    {{ $item->gio_ra ?? '--' }}
                                </td>

                                <td class="px-4 py-4 text-gray-800 dark:text-gray-100">
                                    {{ $item->so_gio_lam }}
                                </td>

                                <td class="px-4 py-4">

                                    @if ($item->trang_thai == 'dung_gio')
                                        <span
                                            class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300">
                                            Đúng giờ
                                        </span>
                                    @elseif($item->trang_thai == 'di_muon')
                                        <span
                                            class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300">
                                            Đi muộn
                                        </span>
                                    @elseif($item->trang_thai == 've_som')
                                        <span
                                            class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300">
                                            Về sớm
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300">
                                            Không chấm công
                                        </span>
                                    @endif

                                </td>

                                <td class="px-4 py-4">

                                    <div class="flex justify-center gap-2">

                                        <a href="{{ route('admin.cham-cong.show', $item->id) }}"
                                            class="px-3 py-2 text-sm rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300">

                                            Xem
                                        </a>

                                        <a href="{{ route('admin.cham-cong.edit', $item->id) }}"
                                            class="px-3 py-2 text-sm rounded-lg bg-yellow-100 text-yellow-700 hover:bg-yellow-200 dark:bg-yellow-900 dark:text-yellow-300">

                                            Sửa
                                        </a>

                                        <form action="{{ route('admin.cham-cong.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa bản ghi này?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="px-3 py-2 text-sm rounded-lg bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900 dark:text-red-300">

                                                Xóa

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8" class="text-center py-10 text-gray-500 dark:text-gray-400">

                                    Chưa có dữ liệu chấm công

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- PHÂN TRANG --}}
            <div class="p-5 border-t border-gray-200 dark:border-gray-700">

                {{ $chamCongs->links() }}

            </div>

        </div>

    </div>

@endsection
