@extends('layouts.admin')

@section('title', 'Chi tiết phụ cấp')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.form-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1.5rem;
}

.header-wrapper {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 24px;
}

.icon-box-gift {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.form-label-custom {
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 14px;
    display: block;
}

/* Các ô thông tin tĩnh (Disabled) sắc nét */
.form-control-static {
    display: block;
    width: 100%;
    border-radius: 10px;
    padding: 12px 16px;
    font-size: 14px;
    font-weight: 500;
    border-width: 2px !important;
    border-style: solid !important;
}

.btn-edit {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 10px 24px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}
.btn-edit:hover { text-decoration: none; }

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}
.btn-back:hover { text-decoration: none; }
</style>

<div class="form-container">

    <div class="mb-3 text-sm text-gray-500 dark:text-slate-400" style="font-size: 13px;">
        <span class="text-blue-600 dark:text-sky-400 font-medium">Phụ cấp</span> 
        <span class="mx-2 text-gray-400 dark:text-slate-600">&gt;</span> 
        <span class="text-gray-400 dark:text-slate-500">Chi tiết phụ cấp</span>
    </div>

    <div class="header-wrapper mb-4 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow-sm rounded-xl">
        <div class="icon-box-gift bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-sky-400">
            <i class="fa-solid fa-gift"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold m-0 text-gray-900 dark:text-white" style="font-size: 20px;">Chi tiết phụ cấp</h2>
            <p class="text-gray-500 dark:text-slate-400 m-0 mt-1" style="font-size: 13px;">Xem thông tin chi tiết của phụ cấp trong hệ thống</p>
        </div>
    </div>

    <div class="p-5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow-sm rounded-xl">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label-custom text-gray-800 dark:text-slate-200">
                        <i class="fa-regular fa-font text-gray-400 dark:text-slate-500 me-2"></i> Tên phụ cấp
                    </label>
                    <div class="form-control-static bg-gray-50 dark:bg-slate-900/50 border-gray-300 dark:border-slate-700 text-gray-900 dark:text-white">
                        {{ $phuCap->ten }}
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label-custom text-gray-800 dark:text-slate-200">
                        <i class="fa-solid fa-hashtag text-gray-400 dark:text-slate-500 me-2"></i> Mã phụ cấp
                    </label>
                    <div class="form-control-static bg-gray-50 dark:bg-slate-900/50 border-gray-300 dark:border-slate-700 text-gray-900 dark:text-white font-mono">
                        {{ $phuCap->ma }}
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label-custom text-gray-800 dark:text-slate-200">
                        <i class="fa-solid fa-layer-group text-gray-400 dark:text-slate-500 me-2"></i> Loại phụ cấp
                    </label>
                    <div class="form-control-static bg-gray-50 dark:bg-slate-900/50 border-gray-300 dark:border-slate-700 text-gray-900 dark:text-white">
                        {{ $phuCap->loai_phu_cap ?? 'Chưa xác định' }}
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label-custom text-gray-800 dark:text-slate-200">
                        <i class="fa-solid fa-money-bill-wave text-gray-400 dark:text-slate-500 me-2"></i> Số tiền mặc định
                    </label>
                    <div class="form-control-static bg-gray-50 dark:bg-slate-900/50 border-gray-300 dark:border-slate-700 text-emerald-600 dark:text-emerald-400 font-bold">
                        {{ number_format($phuCap->so_tien_mac_dinh, 0, ',', '.') }} đ
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label-custom text-gray-800 dark:text-slate-200">
                        <i class="fa-regular fa-circle-check text-gray-400 dark:text-slate-500 me-2"></i> Trạng thái
                    </label>
                    <div class="pt-2">
                        @if($phuCap->trang_thai)
                            <span class="px-3 py-1.5 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-950/50 dark:text-green-400 border border-green-200 dark:border-green-900">
                                <i class="fa-solid fa-circle text-[8px] me-1.5 align-middle"></i>Hoạt động
                            </span>
                        @else
                            <span class="px-3 py-1.5 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-950/50 dark:text-red-400 border border-red-200 dark:border-red-900">
                                <i class="fa-solid fa-circle text-[8px] me-1.5 align-middle"></i>Ngừng hoạt động
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-gray-200 dark:border-slate-700 my-4 mb-5">

        <div class="d-flex gap-2">
            <a href="{{ route('admin.phu-cap.edit', $phuCap->id) }}" class="btn-edit bg-orange-500 hover:bg-orange-600 text-white shadow-sm font-medium">
                <i class="fa-regular fa-pen-to-square"></i> Chỉnh sửa thông tin
            </a>

            <a href="{{ route('admin.phu-cap.index') }}" 
               class="btn-back bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white shadow-sm">
                <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
    </div>
</div>

@endsection