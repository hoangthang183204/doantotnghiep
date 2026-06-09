@extends('layouts.admin')

@section('title', 'Chi tiết hợp đồng lao động')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Chi tiết hợp đồng lao động
        </h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">
            Hợp đồng số <span class="font-semibold">{{ $hopDong->so_hop_dong }}</span>
        </p>
    </div>

    {{-- CARD --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow p-6 space-y-8">

        {{-- GRID INFO --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div>
                <p class="text-sm text-gray-500">Nhân viên</p>
                <p class="font-semibold text-gray-900 dark:text-white">
                    {{ $hopDong->nguoi_dung->ho_ten ?? '---' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Chức vụ</p>
                <p class="font-semibold text-gray-900 dark:text-white">
                    {{ $hopDong->chuc_vu->ten ?? '---' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Loại hợp đồng</p>
                <p class="font-semibold">
                    {{ ucfirst(str_replace('_',' ', $hopDong->loai_hop_dong)) }}
                </p>
            </div>

            {{-- STATUS --}}
            <div>
                <p class="text-sm text-gray-500">Trạng thái</p>

                @php
                    $status = [
                        'tao_moi' => 'bg-gray-100 text-gray-700',
                        'chua_hieu_luc' => 'bg-yellow-100 text-yellow-700',
                        'hieu_luc' => 'bg-green-100 text-green-700',
                        'het_han' => 'bg-orange-100 text-orange-700',
                        'huy_bo' => 'bg-red-100 text-red-700',
                    ];
                @endphp

                <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-semibold
                    {{ $status[$hopDong->trang_thai_hop_dong] ?? 'bg-gray-100 text-gray-700' }}">
                    {{ ucfirst(str_replace('_',' ', $hopDong->trang_thai_hop_dong)) }}
                </span>
            </div>

            <div>
                <p class="text-sm text-gray-500">Ngày bắt đầu</p>
                <p class="font-semibold">
                    {{ optional($hopDong->ngay_bat_dau)->format('d/m/Y') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Ngày kết thúc</p>
                <p class="font-semibold">
                    {{ optional($hopDong->ngay_ket_thuc)->format('d/m/Y') ?? 'Không xác định' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Lương cơ bản</p>
                <p class="font-semibold text-green-600">
                    {{ number_format($hopDong->luong_co_ban, 0, ',', '.') }} đ
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Phụ cấp</p>
                <p class="font-semibold">
                    {{ number_format($hopDong->phu_cap ?? 0, 0, ',', '.') }} đ
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Hình thức làm việc</p>
                <p class="font-semibold">
                    {{ $hopDong->hinh_thuc_lam_viec ?? '---' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Địa điểm</p>
                <p class="font-semibold">
                    {{ $hopDong->dia_diem_lam_viec ?? '---' }}
                </p>
            </div>

        </div>

        {{-- TERMS --}}
        <div>
            <p class="text-sm text-gray-500 mb-2">Điều khoản hợp đồng</p>
            <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-200 leading-relaxed">
                {!! nl2br(e($hopDong->dieu_khoan)) !!}
            </div>
        </div>

        {{-- FILE --}}
        <div>
            <p class="text-sm text-gray-500 mb-2">File hợp đồng đã ký</p>

            @if($hopDong->file_hop_dong_da_ky)
                <a href="{{ asset($hopDong->file_hop_dong_da_ky) }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 text-blue-600 hover:underline">
                    📄 Xem file hợp đồng
                </a>
            @else
                <p class="text-gray-400">Chưa có file</p>
            @endif
        </div>

        {{-- NOTE --}}
        <div>
            <p class="text-sm text-gray-500 mb-1">Ghi chú</p>
            <p class="font-medium text-gray-700 dark:text-gray-200">
                {{ $hopDong->ghi_chu ?? '---' }}
            </p>
        </div>

        {{-- ACTION --}}
        <div class="flex flex-col md:flex-row md:justify-end gap-3 pt-6 border-t dark:border-gray-800">

            <a href="{{ route('admin.hop-dong.index') }}"
               class="px-5 py-2 border rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                ← Quay lại
            </a>

            <a href="{{ route('admin.hop-dong.edit', $hopDong->id) }}"
               class="px-5 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl transition">
                Sửa
            </a>

            <form action="{{ route('admin.hop-dong.destroy', $hopDong->id) }}"
                  method="POST"
                  onsubmit="return confirm('Xóa hợp đồng này?')">

                @csrf
                @method('DELETE')

                <button class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition">
                    Xóa
                </button>

            </form>

        </div>

    </div>
</div>

@endsection