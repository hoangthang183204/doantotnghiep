@extends('layouts.admin')

@section('title', 'Thêm phụ cấp')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* Cấu trúc khung xương khoảng cách */
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

/* ──── TINH CHỈNH NỔI BẬT THANH VIẾT (INPUT) ──── */
.form-control-custom {
    display: block;
    width: 100%;
    border-radius: 10px;
    padding: 12px 16px;
    font-size: 14px;
    font-weight: 500;
    /* Tăng độ dày border từ 1px lên 2px để nhìn rõ ràng, sắc nét hơn */
    border-width: 2px !important; 
    border-style: solid !important;
    transition: all 0.2s ease-in-out;
}

/* Hiệu ứng khi nhấn chuột vào ô viết (Focus) - Viền xanh đậm và đổ bóng rõ nét */
.form-control-custom:focus {
    outline: none;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.25) !important;
}

select.form-control-custom {
    appearance: none;
    background-repeat: no-repeat;
    background-position: right 16px center;
    background-size: 16px;
    padding-right: 40px;
}

/* Nút bấm */
.btn-submit {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 10px 24px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

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
        <span class="text-gray-400 dark:text-slate-500">Thêm phụ cấp</span>
    </div>

    <div class="header-wrapper mb-4 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow-sm rounded-xl">
        <div class="icon-box-gift bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-sky-400">
            <i class="fa-solid fa-gift"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold m-0 text-gray-900 dark:text-white" style="font-size: 20px;">Thêm phụ cấp</h2>
            <p class="text-gray-500 dark:text-slate-400 m-0 mt-1" style="font-size: 13px;">Tạo mới thông tin phụ cấp trong hệ thống</p>
        </div>
    </div>

    <div class="p-5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow-sm rounded-xl">
        <form action="{{ route('admin.phu-cap.store') }}" method="POST">
            @csrf

            <div class="row">
                {{-- CỘT TRÁI --}}
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label-custom text-gray-800 dark:text-slate-200">
                            <i class="fa-regular fa-font text-gray-400 dark:text-slate-500 me-2"></i> Tên phụ cấp
                        </label>
                        <input type="text" 
                               name="ten" 
                               class="form-control-custom bg-white dark:bg-slate-900 border-gray-400 dark:border-slate-600 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:border-blue-500 dark:focus:border-sky-500" 
                               placeholder="VD: Phụ cấp ăn trưa" 
                               value="{{ old('ten') }}"
                               required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom text-gray-800 dark:text-slate-200">
                            <i class="fa-solid fa-hashtag text-gray-400 dark:text-slate-500 me-2"></i> Mã phụ cấp
                        </label>
                        <input type="text" 
                               name="ma" 
                               class="form-control-custom bg-white dark:bg-slate-900 border-gray-400 dark:border-slate-600 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:border-blue-500 dark:focus:border-sky-500" 
                               placeholder="VD: XANG_XEEE3333" 
                               value="{{ old('ma') }}"
                               required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom text-gray-800 dark:text-slate-200">
                            <i class="fa-solid fa-layer-group text-gray-400 dark:text-slate-500 me-2"></i> Loại phụ cấp
                        </label>
                        <input type="text" 
                               name="loai_phu_cap" 
                               class="form-control-custom bg-white dark:bg-slate-900 border-gray-400 dark:border-slate-600 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:border-blue-500 dark:focus:border-sky-500" 
                               placeholder="VD: theo_cap_bac / cố định" 
                               value="{{ old('loai_phu_cap') }}"
                               required>
                    </div>
                </div>

                {{-- CỘT PHẢI --}}
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label-custom text-gray-800 dark:text-slate-200">
                            <i class="fa-solid fa-money-bill-wave text-gray-400 dark:text-slate-500 me-2"></i> Số tiền mặc định (đ)
                        </label>
                        <input type="number" 
                               name="so_tien_mac_dinh" 
                               class="form-control-custom bg-white dark:bg-slate-900 border-gray-400 dark:border-slate-600 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:border-blue-500 dark:focus:border-sky-500" 
                               placeholder="VD: 1011232" 
                               value="{{ old('so_tien_mac_dinh') }}"
                               required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom text-gray-800 dark:text-slate-200">
                            <i class="fa-regular fa-circle-check text-gray-400 dark:text-slate-500 me-2"></i> Trạng thái
                        </label>
                        <select name="trang_thai" 
                                class="form-control-custom bg-white dark:bg-slate-900 border-gray-400 dark:border-slate-600 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-sky-500"
                                style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%234b5563\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3e%3cpolyline points=\'6 9 12 15 18 9\'%3e%3c/polyline%3e%3c/svg%3e');">
                            <option value="1" class="bg-white dark:bg-slate-900 text-gray-900 dark:text-white">Hoạt động</option>
                            <option value="0" class="bg-white dark:bg-slate-900 text-gray-900 dark:text-white">Ngừng hoạt động</option>
                        </select>
                    </div>
                </div>
            </div>

            <hr class="border-gray-200 dark:border-slate-700 my-4 mb-5">

            {{-- THANH HÀNH ĐỘNG NÚT BẤM --}}
            <div class="d-flex gap-2">
                <button type="submit" class="btn-submit bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm font-medium">
                    <i class="fa-regular fa-floppy-disk"></i> Lưu phụ cấp
                </button>

                <a href="{{ route('admin.phu-cap.index') }}" 
                   class="btn-back bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i> Quay lại
                </a>
            </div>

        </form>
    </div>
</div>

@endsection