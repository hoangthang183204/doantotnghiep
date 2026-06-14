@extends('layouts.admin')

@section('content')
    <div class="container p-6 text-gray-900 dark:text-gray-100 transition-colors duration-200">
        <h1 class="mb-4 text-2xl font-semibold text-gray-900 dark:text-gray-100">
            Chi tiết đơn tuyển dụng
        </h1>
        <div class="rounded border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-gray-800">
            @if (isset($item) && $item)
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Mã yêu cầu</div>
                        <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ $item->ma ?? ($item->ma_yeu_cau ?? $item->id) }}</div>

                        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">Phòng ban</div>
                        <div class="text-base text-gray-700 dark:text-gray-200">
                            {{ optional($item->phongBan)->ten_phong_ban ?? ($item->ten_phong_ban ?? '-') }}</div>

                        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">Chức vụ</div>
                        <div class="text-base text-gray-700 dark:text-gray-200">
                            {{ optional($item->chucVu)->ten ?? ($item->chuc_vu ?? '-') }}</div>
                    </div>

                    <div class="space-y-2">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Số lượng</div>
                        <div class="text-base text-gray-700 dark:text-gray-200">
                            {{ $item->so_vi_tri ?? ($item->so_luong ?? '-') }}</div>

                        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">Trạng thái</div>
                        <div class="text-base text-gray-700 dark:text-gray-200">{{ $item->trang_thai ?? '-' }}</div>

                        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">Ngày tạo</div>
                        <div class="text-base text-gray-700 dark:text-gray-200">
                            {{ optional($item->created_at)->format('d/m/Y H:i') ?? '-' }}</div>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Mô tả</div>
                    <div class="prose max-w-none mt-2 dark:prose-invert text-gray-700 dark:text-gray-200">
                        {!! nl2br(e($item->mo_ta_cong_viec ?? ($item->mo_ta ?? '-'))) !!}</div>
                </div>

                <div class="mt-6 flex items-center gap-3">
                    <a href="{{ route('admin.duyetdon.tuyendung.index') }}"
                        class="rounded border border-gray-300 px-3 py-1 text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Quay
                        lại</a>
                    @if ($item->trang_thai === 'dang_tuyen')
                        <form action="{{ route('admin.duyetdon.tuyendung.duyet', $item->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="rounded bg-green-600 px-3 py-1 text-white hover:bg-green-700">Duyệt</button>
                        </form>
                    @endif
                </div>
            @else
                <div class="py-12 text-center text-gray-500 dark:text-gray-400">
                    Không tìm thấy chi tiết đơn tuyển dụng.
                </div>
            @endif
        </div>
    </div>
@endsection
