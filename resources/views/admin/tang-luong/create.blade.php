@extends('layouts.admin')

@section('title', 'Tăng lương cho nhân viên')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">💰 Tăng lương</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">
                    Nhân viên: <strong>{{ $nhanVien->hoSo->ho . ' ' . $nhanVien->hoSo->ten }}</strong>
                    ({{ $nhanVien->hoSo->ma_nhan_vien }})
                </p>
                <p class="text-gray-500 dark:text-gray-400">
                    Hợp đồng: <strong>{{ $hopDong->so_hop_dong }}</strong>
                    - Lương hiện tại: <strong class="text-green-600">{{ number_format($hopDong->luong_co_ban, 0, ',', '.') }}đ</strong>
                </p>
            </div>
            <a href="{{ route('admin.hop-dong.show', $hopDong->id) }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                ← Quay lại
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 text-red-700 px-4 py-3 rounded-xl">{{ session('error') }}</div>
    @endif

    {{-- FORM --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <form action="{{ route('admin.tang-luong.store') }}" method="POST">
            @csrf
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <input type="hidden" name="hop_dong_id" value="{{ $hopDong->id }}">

                    {{-- Lương hiện tại --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Lương hiện tại
                        </label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 dark:bg-gray-700 dark:border-gray-600"
                               value="{{ number_format($hopDong->luong_co_ban, 0, ',', '.') }} đ" readonly>
                    </div>

                    {{-- Lương mới --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Lương mới <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="luong_moi" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                               placeholder="Nhập lương mới..." required>
                    </div>

                    {{-- Ngày áp dụng --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Ngày áp dụng <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="ngay_ap_dung" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                               value="{{ date('Y-m-d') }}" required>
                        <p class="text-xs text-gray-500 mt-1">Lương mới sẽ được áp dụng từ ngày này</p>
                    </div>

                    {{-- Loại thay đổi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Loại thay đổi <span class="text-red-500">*</span>
                        </label>
                        <select name="loai" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" required>
                            <option value="tang_luong">✅ Tăng lương</option>
                            <option value="giam_luong">❌ Giảm lương</option>
                            <option value="dieu_chinh">🔄 Điều chỉnh</option>
                        </select>
                    </div>

                    {{-- Lý do --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Lý do thay đổi
                        </label>
                        <input type="text" name="ly_do" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                               placeholder="Ví dụ: Đánh giá xuất sắc, Lên chức, Điều chỉnh theo thị trường...">
                    </div>

                    {{-- Tùy chọn tái ký --}}
                    <div class="md:col-span-2">
                        <div class="flex items-center gap-3 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                            <input type="checkbox" name="tai_ky" id="tai_ky" value="1" class="w-4 h-4 text-blue-600 rounded">
                            <label for="tai_ky" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                🔄 Tạo hợp đồng mới để tái ký
                            </label>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                (Nên chọn khi thay đổi lương kèm điều khoản mới)
                            </span>
                        </div>
                    </div>

                </div>

                {{-- Buttons --}}
                <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shadow-md hover:shadow-lg"
                            onclick="return confirm('Xác nhận thay đổi lương cho nhân viên?')">
                        💾 Xác nhận
                    </button>
                    <a href="{{ route('admin.hop-dong.show', $hopDong->id) }}" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                        ← Quay lại
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Lịch sử tăng lương --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">📜 Lịch sử thay đổi lương</h3>
        </div>
        <div class="overflow-x-auto p-4">
            @if($lichSuTangLuong->count() > 0)
                <table class="min-w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs text-gray-500 dark:text-gray-400">Ngày áp dụng</th>
                            <th class="px-4 py-3 text-left text-xs text-gray-500 dark:text-gray-400">Lương cũ</th>
                            <th class="px-4 py-3 text-left text-xs text-gray-500 dark:text-gray-400">Lương mới</th>
                            <th class="px-4 py-3 text-left text-xs text-gray-500 dark:text-gray-400">Chênh lệch</th>
                            <th class="px-4 py-3 text-left text-xs text-gray-500 dark:text-gray-400">Loại</th>
                            <th class="px-4 py-3 text-left text-xs text-gray-500 dark:text-gray-400">Lý do</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lichSuTangLuong as $item)
                        <tr class="border-b border-gray-100 dark:border-gray-700">
                            <td class="px-4 py-3">{{ $item->ngay_ap_dung->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ number_format($item->luong_cu, 0, ',', '.') }}đ</td>
                            <td class="px-4 py-3 font-semibold text-green-600">{{ number_format($item->luong_moi, 0, ',', '.') }}đ</td>
                            <td class="px-4 py-3">
                                <span class="{{ $item->chenhLech > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $item->chenhLech > 0 ? '+' : '' }}{{ number_format($item->chenhLech, 0, ',', '.') }}đ
                                    ({{ $item->phanTramTang > 0 ? '+' : '' }}{{ $item->phanTramTang }}%)
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs
                                    {{ $item->loai == 'tang_luong' ? 'bg-green-100 text-green-700' :
                                       ($item->loai == 'giam_luong' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ $item->loai_text }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $item->ly_do ?? '---' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-center text-gray-500 py-6">Chưa có lịch sử thay đổi lương</p>
            @endif
        </div>
    </div>
</div>
@endsection