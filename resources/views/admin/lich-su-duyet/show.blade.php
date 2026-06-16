@extends('layouts.admin')

@section('content')

<div class="p-6 max-w-5xl mx-auto">

    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6">

            <h2 class="text-2xl font-bold text-white">
                Chi tiết lịch sử duyệt
            </h2>

            <p class="text-blue-100 mt-1">
                Theo dõi thông tin phê duyệt đơn nghỉ
            </p>

        </div>

        <div class="p-8">

            <div class="grid md:grid-cols-2 gap-6">

                <div>
                    <label class="text-sm text-gray-500">
                        Mã đơn nghỉ
                    </label>

                    <p class="font-semibold text-lg">
                        {{ $lichSu->donXinNghi->ma_don_nghi }}
                    </p>
                </div>

                <div>
                    <label class="text-sm text-gray-500">
                        Người duyệt
                    </label>

                    <p class="font-semibold">
                        {{ $lichSu->nguoiDuyet?->ho_ten ?? $lichSu->nguoiDuyet?->ten_dang_nhap }}
                    </p>
                </div>

                <div>
                    <label class="text-sm text-gray-500">
                        Cấp duyệt
                    </label>

                    <p class="font-semibold">
                        Cấp {{ $lichSu->cap_duyet }}
                    </p>
                </div>

                <div>
                    <label class="text-sm text-gray-500">
                        Kết quả
                    </label>

                    <div class="mt-1">
                        @if($lichSu->ket_qua == 'da_duyet')
                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">
                                Đã duyệt
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700">
                                Từ chối
                            </span>
                        @endif
                    </div>
                </div>

                <div class="md:col-span-2">

                    <label class="text-sm text-gray-500">
                        Ghi chú
                    </label>

                    <div class="mt-2 bg-gray-50 dark:bg-gray-700 rounded-xl p-4">
                        {{ $lichSu->ghi_chu ?: 'Không có ghi chú' }}
                    </div>

                </div>

                <div class="md:col-span-2">

                    <label class="text-sm text-gray-500">
                        Thời gian duyệt
                    </label>

                    <p class="font-medium mt-1">
                        {{ $lichSu->thoi_gian_duyet->format('d/m/Y H:i:s') }}
                    </p>

                </div>

            </div>

            <div class="mt-8 border-t pt-6">

                <a href="{{ route('admin.lich-su-duyet.index') }}"
                   class="inline-flex items-center px-5 py-3 rounded-xl bg-gray-100 hover:bg-gray-200">
                    ← Quay lại danh sách
                </a>

            </div>

        </div>

    </div>

</div>

@endsection