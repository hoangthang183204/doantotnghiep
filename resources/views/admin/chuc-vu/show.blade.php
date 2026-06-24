@extends('layouts.admin')

@section('title', 'Chi tiết chức vụ')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Chi tiết chức vụ
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Thông tin chi tiết của chức vụ
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.chuc-vu.edit', $chucVu->id) }}"
                   class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition">
                    <i class="fas fa-edit mr-1"></i> Sửa
                </a>
                <a href="{{ route('admin.chuc-vu.index') }}"
                   class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                    <i class="fas fa-arrow-left mr-1"></i> Quay lại
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Thông tin cơ bản --}}
            <div class="space-y-4">
                <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                    <label class="text-sm text-gray-500 dark:text-gray-400">ID</label>
                    <p class="font-medium text-gray-800 dark:text-white">{{ $chucVu->id }}</p>
                </div>
                <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                    <label class="text-sm text-gray-500 dark:text-gray-400">Tên chức vụ</label>
                    <p class="font-medium text-gray-800 dark:text-white">{{ $chucVu->ten }}</p>
                </div>
                <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                    <label class="text-sm text-gray-500 dark:text-gray-400">Mã chức vụ</label>
                    <p class="font-medium text-gray-800 dark:text-white">
                        <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-xs font-mono">
                            {{ $chucVu->ma }}
                        </span>
                    </p>
                </div>
                <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                    <label class="text-sm text-gray-500 dark:text-gray-400">Phòng ban</label>
                    <p class="font-medium text-gray-800 dark:text-white">
                        {{ $chucVu->phong_ban->ten_phong_ban ?? '-' }}
                    </p>
                </div>
                <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                    <label class="text-sm text-gray-500 dark:text-gray-400">Trạng thái</label>
                    <p>
                        @if ($chucVu->trang_thai)
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300">
                                Hoạt động
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300">
                                Ngừng hoạt động
                            </span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- Thông tin lương và thống kê --}}
            <div class="space-y-4">
                <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                    <label class="text-sm text-gray-500 dark:text-gray-400">Lương cơ bản</label>
                    <p class="font-medium text-gray-800 dark:text-white">
                        @if($chucVu->luong_co_ban)
                            {{ number_format($chucVu->luong_co_ban, 0, ',', '.') }} ₫
                        @else
                            <span class="text-gray-400">Chưa xác định</span>
                        @endif
                    </p>
                </div>

                <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                    <label class="text-sm text-gray-500 dark:text-gray-400">Hệ số lương</label>
                    <p class="font-medium text-gray-800 dark:text-white">
                        @if($chucVu->he_so_luong)
                            {{ number_format($chucVu->he_so_luong, 2) }}
                        @else
                            <span class="text-gray-400">Chưa xác định</span>
                        @endif
                    </p>
                </div>

                @if($chucVu->luong_co_ban && $chucVu->he_so_luong)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-3 bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
                        <label class="text-sm text-gray-500 dark:text-gray-400">📊 Lương tham khảo (Gross)</label>
                        <p class="font-bold text-blue-600 dark:text-blue-400 text-lg">
                            {{ number_format($chucVu->luong_co_ban * $chucVu->he_so_luong, 0, ',', '.') }} ₫
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            = {{ number_format($chucVu->luong_co_ban, 0, ',', '.') }} ₫ × {{ $chucVu->he_so_luong }}
                        </p>
                        <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">
                            ⚠️ Lương thực tế nhận được phụ thuộc vào ngày công và khấu trừ
                        </p>
                    </div>
                @endif

                {{-- ✅ LƯƠNG THỰC TẾ GẦN NHẤT --}}
                @if(isset($luongGanNhat) && $luongGanNhat)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-3 bg-green-50 dark:bg-green-900/20 p-3 rounded-lg">
                        <label class="text-sm text-gray-500 dark:text-gray-400">💰 Lương thực tế gần nhất</label>
                        <p class="font-bold text-green-600 dark:text-green-400 text-lg">
                            {{ number_format($luongGanNhat->luong_thuc_nhan, 0, ',', '.') }} ₫
                        </p>
                        <p class="text-xs text-gray-400">
                            Tháng {{ $luongGanNhat->luong_thang }}/{{ $luongGanNhat->luong_nam }}
                            • Ngày công: {{ $luongGanNhat->so_ngay_cong }}/{{ $luongGanNhat->so_ngay_cong_chuan }}
                        </p>
                    </div>
                @endif

                <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                    <label class="text-sm text-gray-500 dark:text-gray-400">Số nhân viên</label>
                    <p class="font-medium text-gray-800 dark:text-white">
                        {{ $totalEmployees ?? $chucVu->nguoi_dungs->count() }} người
                    </p>
                </div>

                <div>
                    <label class="text-sm text-gray-500 dark:text-gray-400">Mô tả</label>
                    <p class="text-gray-700 dark:text-gray-300 mt-1">
                        {{ $chucVu->mo_ta ?? 'Không có mô tả' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- CHÚ THÍCH VỀ LƯƠNG --}}
        <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
            <h4 class="text-sm font-semibold text-yellow-800 dark:text-yellow-300 mb-2">📌 Giải thích về lương</h4>
            <ul class="text-xs text-yellow-700 dark:text-yellow-300 space-y-1">
                <li>• <strong>Lương cơ bản</strong>: Mức lương nền tảng theo chức vụ</li>
                <li>• <strong>Hệ số lương</strong>: Hệ số nhân để tính lương Gross</li>
                <li>• <strong>Lương Gross (Tham khảo)</strong> = Lương cơ bản × Hệ số lương</li>
                <li>• <strong>Lương Net (Thực tế)</strong> = Lương theo công + Phụ cấp + Tăng ca - Khấu trừ - Thuế</li>
                <li>• Lương thực tế phụ thuộc vào <strong>số ngày công</strong> và <strong>các khoản khấu trừ</strong> của từng nhân viên</li>
            </ul>
        </div>

        {{-- DANH SÁCH NHÂN VIÊN --}}
        @if($chucVu->nguoi_dungs->isNotEmpty())
            <div class="mt-8">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                    Danh sách nhân viên giữ chức vụ này
                </h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm text-gray-600 dark:text-gray-300">STT</th>
                                <th class="px-4 py-2 text-left text-sm text-gray-600 dark:text-gray-300">Họ tên</th>
                                <th class="px-4 py-2 text-left text-sm text-gray-600 dark:text-gray-300">Email</th>
                                <th class="px-4 py-2 text-left text-sm text-gray-600 dark:text-gray-300">Trạng thái</th>
                                <th class="px-4 py-2 text-left text-sm text-gray-600 dark:text-gray-300">Lương gần nhất</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($chucVu->nguoi_dungs as $index => $user)
                                @php
                                    // ✅ Lấy lương từ biến đã được truyền từ controller
                                    $luongUser = isset($luongNhanViens) ? $luongNhanViens->where('nguoi_dung_id', $user->id)->first() : null;
                                @endphp
                                <tr class="border-t border-gray-200 dark:border-gray-700">
                                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $user->ho_so->ho ?? '' }} {{ $user->ho_so->ten ?? $user->ten_dang_nhap }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $user->email }}</td>
                                    <td class="px-4 py-2 text-sm">
                                        @if($user->trang_thai)
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300">
                                                Đang làm
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300">
                                                Đã nghỉ
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                                        @if($luongUser)
                                            {{ number_format($luongUser->luong_thuc_nhan, 0, ',', '.') }} ₫
                                        @else
                                            <span class="text-gray-400">Chưa có</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>

</div>
@endsection