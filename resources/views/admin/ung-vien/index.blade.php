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
        <a href="{{ route('admin.ung_vien.create') }}" 
           class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Thêm ứng viên
        </a>
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
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="keyword" value="{{ request('keyword') }}"
                    placeholder="Tìm tên, email, SĐT, mã hồ sơ..."
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
            </div>
            <div>
                <select name="trang_thai" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="" class="dark:text-white">Tất cả trạng thái</option>
                    @foreach($trangThais as $key => $value)
                        <option value="{{ $key }}" {{ request('trang_thai') == $key ? 'selected' : '' }} class="dark:text-white">
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="tin_tuyen_dung_id" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="" class="dark:text-white">Tất cả tin tuyển dụng</option>
                    @foreach($tinTuyenDungs as $tin)
                        <option value="{{ $tin->id }}" {{ request('tin_tuyen_dung_id') == $tin->id ? 'selected' : '' }} class="dark:text-white">
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
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 text-xs uppercase">
                        <th class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-left dark:text-gray-300">Mã HS</th>
                        <th class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-left dark:text-gray-300">Họ tên</th>
                        <th class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-left dark:text-gray-300">Email</th>
                        <th class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-left dark:text-gray-300">Phòng ban</th>
                        <th class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-right dark:text-gray-300">Lương mong muốn</th>
                        <th class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-center dark:text-gray-300">Trạng thái</th>
                        <th class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-center dark:text-gray-300">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ungViens as $item)
                        <tr class="hover:bg-blue-50/40 dark:hover:bg-gray-700/40 transition">
                            <td class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                                <span class="px-3 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white text-xs font-semibold">
                                    {{ $item->ma_ho_so }}
                                </span>
                            </td>
                            <td class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 font-semibold text-gray-900 dark:text-white">
                                {{ $item->ho }} {{ $item->ten }}
                            </td>
                            <td class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300">{{ $item->email }}</td>
                            <td class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300">
                                {{ $item->phongBan?->ten_phong_ban ?? 'Chưa xác định' }}
                            </td>
                            <td class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 text-right font-semibold text-gray-900 dark:text-white">
                                {{ $item->luong_mong_muon ? number_format($item->luong_mong_muon) . ' VNĐ' : 'Thỏa thuận' }}
                            </td>
                            <td class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 text-center">
                                {!! $item->trang_thai_badge !!}
                            </td>
                            <td class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                                <div class="flex flex-wrap justify-center gap-1.5">
                                    <a href="{{ route('admin.ung_vien.show', $item->id) }}" 
                                       class="px-3 py-1.5 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-medium hover:bg-blue-100 dark:hover:bg-blue-900/50 transition">
                                        Xem
                                    </a>

                                    @if($item->trang_thai != 'dat' && $item->trang_thai != 'khong_dat')
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

                                        <form action="{{ route('admin.ung_vien.update-status', $item->id) }}" method="POST" class="inline">
                                            @csrf
                                            <select name="trang_thai" 
                                                    onchange="if(confirm('Bạn có chắc muốn thay đổi trạng thái ứng viên này?')) this.form.submit()"
                                                    class="px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-xs font-medium focus:ring-2 focus:ring-blue-500 outline-none">
                                                <option value="" class="dark:text-white">Chọn trạng thái</option>
                                                @if($item->trang_thai != 'cho_duyet')
                                                    <option value="moi_nop" {{ $item->trang_thai == 'moi_nop' ? 'selected' : '' }} class="dark:text-white">Mới nộp</option>
                                                @endif
                                                <option value="cho_duyet" {{ $item->trang_thai == 'cho_duyet' ? 'selected' : '' }} class="dark:text-white">Chờ duyệt</option>
                                                <option value="da_duyet" {{ $item->trang_thai == 'da_duyet' ? 'selected' : '' }} class="dark:text-white">Đã duyệt</option>
                                                <option value="hen_phong_van" {{ $item->trang_thai == 'hen_phong_van' ? 'selected' : '' }} class="dark:text-white">Hẹn phỏng vấn</option>
                                                <option value="cho_phong_van" {{ $item->trang_thai == 'cho_phong_van' ? 'selected' : '' }} class="dark:text-white">Chờ phỏng vấn</option>
                                                <option value="da_phong_van" {{ $item->trang_thai == 'da_phong_van' ? 'selected' : '' }} class="dark:text-white">Đã phỏng vấn</option>
                                                <option value="dat" {{ $item->trang_thai == 'dat' ? 'selected' : '' }} class="dark:text-white">Trúng tuyển</option>
                                                <option value="khong_dat" {{ $item->trang_thai == 'khong_dat' ? 'selected' : '' }} class="dark:text-white">Không đạt</option>
                                                <option value="da_huy" {{ $item->trang_thai == 'da_huy' ? 'selected' : '' }} class="dark:text-white">Đã hủy</option>
                                                <option value="tam_dung" {{ $item->trang_thai == 'tam_dung' ? 'selected' : '' }} class="dark:text-white">Tạm dừng</option>
                                            </select>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                                Không tìm thấy ứng viên nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $ungViens->links() }}
        </div>
    </div>
</div>
@endsection