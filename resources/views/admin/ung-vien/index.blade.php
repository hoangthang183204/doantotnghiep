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
                            <th class="px-5 py-4 text-left">SĐT</th>
                            <th class="px-5 py-4 text-left">Tin tuyển dụng</th>
                            <th class="px-5 py-4 text-left">Phòng ban</th>
                            <th class="px-5 py-4 text-left">Chức vụ</th>
                            <th class="px-5 py-4 text-center">KN</th>
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

                                <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white">{{ $item->so_dien_thoai }}
                                </td>

                                <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white">
                                    {{ $item->tinTuyenDung?->tieu_de }}
                                </td>

                                <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white">
                                    {{ $item->tinTuyenDung?->phongBan?->ten_phong_ban }}
                                </td>


                                <td class="px-5 py-4 text-center font-semibold text-gray-900 dark:text-white">
                                    {{ $item->so_nam_kinh_nghiem }} năm
                                </td>

                                <td class="px-5 py-4 text-right font-semibold font-semibold text-gray-900 dark:text-white">
                                    {{ number_format($item->luong_mong_muon) }}
                                </td>

                                <td class="px-5 py-4 text-center font-semibold text-gray-900 dark:text-white">

                                    @if ($item->trang_thai == 'moi_nop')
                                        <span
                                            class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                            Mới
                                        </span>
                                    @elseif($item->trang_thai == 'da_xem')
                                        <span
                                            class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                            Đã xem
                                        </span>
                                    @elseif($item->trang_thai == 'phong_van')
                                        <span
                                            class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                            Phỏng vẫn
                                        </span>
                                    @elseif($item->trang_thai == 'tu_choi')
                                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                            Từ chối
                                        </span>
                                    @endif

                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-center gap-2">

                                        <a href="{{ route('admin.ung_vien.show', $item->id) }}"
                                            class="px-3 py-1 rounded-lg bg-blue-50 text-blue-600 text-xs">
                                            Xem
                                        </a>


                                        {{-- NÚT XÓA --}}
                                        <form action="{{ route('admin.ung_vien.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa ứng viên này?')"
                                            style="display:inline-block;">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="px-3 py-1 rounded-lg bg-red-50 text-red-600 text-xs hover:bg-red-100">
                                                Xóa
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
