@extends('layouts.admin')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* Toàn bộ nền và Font chữ */
body {
    background-color: #f6f7fb !important;
    font-family: "Inter", sans-serif;
}

.form-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1.5rem;
}

/* Card layout đồng bộ */
.page-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #f1f3f7;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
}

/* Header Wrapper */
.header-wrapper {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 24px;
}

.icon-box-gift {
    width: 48px;
    height: 48px;
    background-color: #eff6ff;
    color: #3b82f6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

/* Labels */
.form-label-custom {
    font-weight: 600;
    margin-bottom: 8px;
    color: #374151;
    font-size: 14px;
    display: block;
}

/* Inputs & Selects tinh chỉnh hiện đại */
.form-control-custom {
    display: block;
    width: 100%;
    border-radius: 10px;
    padding: 11px 16px;
    border: 1px solid #d1d5db;
    background-color: #fff;
    color: #1f2937;
    font-size: 14px;
    transition: all 0.2s ease-in-out;
}

.form-control-custom:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
    background-color: #fff;
}

/* Custom Select Arrow */
select.form-control-custom {
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%234b5563' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 16px center;
    background-size: 16px;
    padding-right: 40px;
}

/* Buttons Group */
.btn-submit {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 10px 24px;
    background-color: #3b82f6;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-submit:hover {
    background-color: #2563eb;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 10px 20px;
    background-color: #ffffff;
    border: 1px solid #e5e7eb;
    color: #4b5563;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-back:hover {
    background-color: #f9fafb;
    color: #111827;
    text-decoration: none;
}
</style>

<div class="form-container">

    <div class="mb-3 text-sm" style="font-size: 13px;">
        <span class="text-blue-500 font-medium">Phụ cấp</span> 
        <span class="mx-2 text-gray-400">&gt;</span> 
        <span class="text-gray-400">Sửa phụ cấp</span>
    </div>

    <div class="page-card mb-4 header-wrapper">
        <div class="icon-box-gift">
            <i class="fa-solid fa-gift"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold m-0" style="color: #111827; font-size: 20px;">Sửa phụ cấp</h2>
            <p class="text-gray-500 m-0 mt-1" style="font-size: 13px; color: #6b7280;">Cập nhật thông tin phụ cấp trong hệ thống</p>
        </div>
    </div>

    <div class="page-card p-5">
        <form action="{{ route('admin.phu-cap.update', $phuCap->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- CỘT TRÁI --}}
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label-custom"><i class="fa-regular fa-text-width text-gray-400 me-2"></i> Tên phụ cấp</label>
                        <input type="text" 
                               name="ten" 
                               class="form-control-custom" 
                               value="{{ old('ten', $phuCap->ten) }}" 
                               required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom"><i class="fa-solid fa-hashtag text-gray-400 me-2"></i> Mã phụ cấp</label>
                        <input type="text" 
                               name="ma" 
                               class="form-control-custom" 
                               value="{{ old('ma', $phuCap->ma) }}" 
                               required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom"><i class="fa-solid fa-layer-group text-gray-400 me-2"></i> Loại phụ cấp</label>
                        <input type="text" 
                               name="loai_phu_cap" 
                               class="form-control-custom" 
                               value="{{ old('loai_phu_cap', $phuCap->loai_phu_cap) }}">
                    </div>
                </div>

                {{-- CỘT PHẢI --}}
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label-custom"><i class="fa-solid fa-money-bill-wave text-gray-400 me-2"></i> Số tiền mặc định (đ)</label>
                        <input type="number" 
                               name="so_tien_mac_dinh" 
                               class="form-control-custom" 
                               value="{{ old('so_tien_mac_dinh', $phuCap->so_tien_mac_dinh) }}" 
                               required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom"><i class="fa-regular fa-circle-check text-gray-400 me-2"></i> Trạng thái</label>
                        <select name="trang_thai" class="form-control-custom">
                            <option value="1" {{ $phuCap->trang_thai ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ !$phuCap->trang_thai ? 'selected' : '' }}>Ngừng hoạt động</option>
                        </select>
                    </div>
                </div>
            </div>

            <hr style="border-color: #f3f4f6; margin: 1rem 0 2rem 0;">

            {{-- THANH ACTIONS NÚT BẤM --}}
            <div class="d-flex gap-2">
                <button type="submit" class="btn-submit">
                    <i class="fa-regular fa-floppy-disk"></i> Lưu thay đổi
                </button>

                <a href="{{ route('admin.phu-cap.index') }}" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Quay lại
                </a>
            </div>

        </form>
    </div>
</div>

@endsection