@extends('layouts.admin')

@section('content')
    <div class="p-6 max-w-7xl mx-auto space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">

            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Danh sách ứng viên
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">
                    Quản lý hồ sơ ứng viên tuyển dụng trong hệ thống.
                </p>
            </div>

            {{-- BUTTON THÊM --}}
            <a href="{{ route('admin.ung_vien.create') }}"
                class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-sm transition">
                + Thêm ứng viên
            </a>

        </div>

        {{-- FILTER --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-4">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">

                {{-- keyword --}}
                <input type="text" name="keyword" value="{{ request('keyword') }}"
                    placeholder="Tìm tên, email, SĐT, mã hồ sơ..."
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 outline-none">

                {{-- trạng thái --}}
                <select name="trang_thai"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">-- Trạng thái --</option>
                    <option value="moi">Mới</option>
                    <option value="phong_van">Phỏng vấn</option>
                    <option value="dat">Đạt</option>
                    <option value="truot">Trượt</option>
                </select>

                {{-- tin tuyển dụng --}}
                <select name="tin_tuyen_dung_id"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    <option value="">-- Tin tuyển dụng --</option>
                    @foreach ($tinTuyenDungs as $tin)
                        <option value="{{ $tin->id }}">
                            {{ $tin->tieu_de }}
                        </option>
                    @endforeach
                </select>

                {{-- button --}}
                <button class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold transition">
                    Lọc dữ liệu
                </button>

            </form>
        </div>

        {{-- TABLE --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">

            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white">
                    Danh sách ứng viên
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full table-auto">

                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 text-xs uppercase">
                            <th class="px-5 py-4 text-left">Mã HS</th>
                            <th class="px-5 py-4 text-left">Họ tên</th>
                            <th class="px-5 py-4 text-left">Email</th>
                            <th class="px-5 py-4 text-left">Phòng ban</th>
                            <th class="px-5 py-4 text-right">Lương</th>
                            <th class="px-5 py-4 text-center">Trạng thái</th>
                            <th class="px-5 py-4 text-center">Hành động</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">

                        @forelse($ungViens as $item)
                            <tr class="hover:bg-blue-50/40 dark:hover:bg-gray-700/40 transition">

                                <td class="px-5 py-4">
                                    <span
                                        class="px-3 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-xs font-semibold font-semibold text-gray-900 dark:text-white">
                                        {{ $item->ma_ho_so }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white">
                                    {{ $item->ho }} {{ $item->ten }}
                                </td>

                                <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white">{{ $item->email }}</td>



                                <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white">
                                    {{ $item->tinTuyenDung?->phongBan?->ten_phong_ban }}
                                </td>




                                <td class="px-5 py-4 text-right font-semibold font-semibold text-gray-900 dark:text-white">
                                    {{ number_format($item->luong_mong_muon) }}
                                </td>

                                <td class="px-5 py-4 text-center font-semibold text-gray-900 dark:text-white">

                                    @if ($item->trang_thai == 'moi_nop')
                                        <span
                                            class="inline-block min-w-[90px] whitespace-nowrap px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                            Mới
                                        </span>
                                    @elseif($item->trang_thai == 'da_xem')
                                        <span
                                            class="inline-block min-w-[90px] whitespace-nowrap px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                            Đã xem
                                        </span>
                                    @elseif($item->trang_thai == 'phong_van')
                                        <span
                                            class="inline-block min-w-[90px] whitespace-nowrap px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                            Phỏng vấn
                                        </span>
                                    @elseif($item->trang_thai == 'tu_choi')
                                        <span class="inline-block min-w-[90px] whitespace-nowrap px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                            Từ chối
                                        </span>
                                    @endif

                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-center gap-1.5">

                                        {{-- Xem chi tiết --}}
                                        <a href="{{ route('admin.ung_vien.show', $item->id) }}"
                                            class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                            title="Xem chi tiết">

                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                </path>

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>

                                            </svg>

                                        </a>

                                        {{-- Lưu trữ --}}
                                        <form action="{{ route('admin.ung_vien.archive', $item->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Lưu trữ hồ sơ ứng viên này?')">

                                            @csrf

                                            <button type="submit"
                                                class="p-1.5 text-yellow-600 hover:bg-yellow-50 rounded-lg transition"
                                                title="Lưu trữ">

                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                                    </path>

                                                </svg>

                                            </button>

                                        </form>

                                        {{-- Xóa --}}
                                        <form action="{{ route('admin.ung_vien.destroy', $item->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa ứng viên này?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition"
                                                title="Xóa ứng viên">

                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7L5 7M10 11V17M14 11V17M6 7L7 19C7.1 20.1 7.9 21 9 21H15C16.1 21 16.9 20.1 17 19L18 7M9 7V5C9 3.9 9.9 3 11 3H13C14.1 3 15 3.9 15 5V7">
                                                    </path>

                                                </svg>

                                            </button>

                                        </form>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-10 text-gray-500">
                                    Chưa có dữ liệu ứng viên
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="p-4">
                {{ $ungViens->links() }}
            </div>

        </div>

    </div>
@endsection
