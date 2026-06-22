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
                        <label class="text-sm text-gray-500 dark:text-gray-400">Lương thực tế</label>
                        <p class="font-bold text-blue-600 dark:text-blue-400 text-lg">
                            {{ number_format($chucVu->luong_co_ban * $chucVu->he_so_luong, 0, ',', '.') }} ₫
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

        {{-- Danh sách nhân viên giữ chức vụ --}}
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($chucVu->nguoi_dungs as $index => $user)
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