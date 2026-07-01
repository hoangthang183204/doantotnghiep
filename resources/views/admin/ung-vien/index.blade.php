@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Danh sách ứng viên
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Quản lý hồ sơ ứng viên tuyển dụng trong hệ thống.
            </p>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-100 dark:border-green-800 text-sm">
            {!! session('success') !!}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border border-red-100 dark:border-red-800 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="p-4 rounded-xl bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400 border border-yellow-100 dark:border-yellow-800 text-sm">
            {{ session('warning') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="keyword" value="{{ request('keyword') }}"
                    placeholder="Tìm tên, email, SĐT, mã hồ sơ..."
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 outline-none text-sm">
            </div>
            <div>
                <select name="trang_thai" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 outline-none text-sm">
                    <option value="">Tất cả trạng thái</option>
                    @foreach($trangThais as $key => $value)
                        <option value="{{ $key }}" {{ request('trang_thai') == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="tin_tuyen_dung_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 outline-none text-sm">
                    <option value="">Tất cả tin tuyển dụng</option>
                    @foreach($tinTuyenDungs as $tin)
                        <option value="{{ $tin->id }}" {{ request('tin_tuyen_dung_id') == $tin->id ? 'selected' : '' }}>
                            {{ $tin->tieu_de }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button class="w-full px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold transition text-sm">
                    Lọc dữ liệu
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 text-xs uppercase">
                        <th class="px-5 py-4 text-left">Mã HS</th>
                        <th class="px-5 py-4 text-left">Họ tên</th>
                        <th class="px-5 py-4 text-left">Email</th>
                        <th class="px-5 py-4 text-left">Phòng ban</th>
                        <th class="px-5 py-4 text-right">Lương mong muốn</th>
                        <th class="px-5 py-4 text-center">Trạng thái</th>
                        <th class="px-5 py-4 text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($ungViens as $item)
                        <tr class="hover:bg-blue-50/40 dark:hover:bg-gray-700/40 transition">
                            <td class="px-5 py-4">
                                <span class="px-3 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-xs font-semibold text-gray-900 dark:text-white">
                                    {{ $item->ma_ho_so }}
                                </span>
                            </td>
                            <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white">
                                {{ $item->ho }} {{ $item->ten }}
                            </td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $item->email }}</td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">
                                {{ $item->phongBan?->ten_phong_ban ?? 'Chưa xác định' }}
                            </td>
                            <td class="px-5 py-4 text-right font-semibold text-gray-900 dark:text-white">
                                {{ $item->luong_mong_muon ? number_format($item->luong_mong_muon) . ' VNĐ' : 'Thỏa thuận' }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                {!! $item->trang_thai_badge !!}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap justify-center gap-2">
                                    <!-- Nút Xem -->
                                    <a href="{{ route('admin.ung_vien.show', $item->id) }}" 
                                       class="px-3 py-1.5 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-medium hover:bg-blue-100 dark:hover:bg-blue-900/50 transition">
                                        Xem
                                    </a>

                                    <!-- Nếu đã trúng tuyển, không hiển thị các nút thao tác -->
                                    @if($item->trang_thai != 'dat' && $item->trang_thai != 'khong_dat')
                                        <!-- Nút Duyệt (Chỉ hiển thị khi trạng thái là chờ duyệt) -->
                                        @if($item->trang_thai == 'cho_duyet')
                                            <form action="{{ route('admin.ung_vien.approve', $item->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="return confirm('Bạn có chắc muốn duyệt ứng viên này?')"
                                                        class="px-3 py-1.5 rounded-lg bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-xs font-medium hover:bg-green-100 dark:hover:bg-green-900/50 transition">
                                                    Duyệt
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.ung_vien.reject', $item->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="return confirm('Bạn có chắc muốn từ chối ứng viên này?')"
                                                        class="px-3 py-1.5 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs font-medium hover:bg-red-100 dark:hover:bg-red-900/50 transition">
                                                    Từ chối
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Dropdown thay đổi trạng thái -->
                                        <form action="{{ route('admin.ung_vien.update-status', $item->id) }}" method="POST" class="inline">
                                            @csrf
                                            <select name="trang_thai" 
                                                    onchange="if(confirm('Bạn có chắc muốn thay đổi trạng thái ứng viên này?')) this.form.submit()"
                                                    class="px-2 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-xs font-medium focus:ring-2 focus:ring-blue-500 outline-none">
                                                <option value="">Chọn trạng thái</option>
                                                <option value="moi_nop" {{ $item->trang_thai == 'moi_nop' ? 'selected' : '' }}>Mới nộp</option>
                                                <option value="cho_duyet" {{ $item->trang_thai == 'cho_duyet' ? 'selected' : '' }}>Chờ duyệt</option>
                                                <option value="da_duyet" {{ $item->trang_thai == 'da_duyet' ? 'selected' : '' }}>Đã duyệt</option>
                                                <option value="hen_phong_van" {{ $item->trang_thai == 'hen_phong_van' ? 'selected' : '' }}>Hẹn phỏng vấn</option>
                                                <option value="cho_phong_van" {{ $item->trang_thai == 'cho_phong_van' ? 'selected' : '' }}>Chờ phỏng vấn</option>
                                                <option value="da_phong_van" {{ $item->trang_thai == 'da_phong_van' ? 'selected' : '' }}>Đã phỏng vấn</option>
                                                <option value="dat" {{ $item->trang_thai == 'dat' ? 'selected' : '' }}>Trúng tuyển</option>
                                                <option value="khong_dat" {{ $item->trang_thai == 'khong_dat' ? 'selected' : '' }}>Không đạt</option>
                                                <option value="da_huy" {{ $item->trang_thai == 'da_huy' ? 'selected' : '' }}>Đã hủy</option>
                                                <option value="tam_dung" {{ $item->trang_thai == 'tam_dung' ? 'selected' : '' }}>Tạm dừng</option>
                                            </select>
                                        </form>
                                    @else
                                        <!-- Nếu đã trúng tuyển hoặc không đạt, hiển thị badge -->
                                        <span class="px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-medium">
                                            Đã xử lý
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-gray-500 dark:text-gray-400">
                                Không tìm thấy ứng viên nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 dark:border-gray-700">
            {{ $ungViens->links() }}
        </div>
    </div>
</div>
@endsection