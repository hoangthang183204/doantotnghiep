@extends('layouts.admin')

@section('content')

<style>
body {
    background: #f6f7fb;
    font-family: "Inter", sans-serif;
}

.page-card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.06);
}

.page-header {
    padding: 24px;
    border-radius: 18px;
}

.page-title {
    font-size: 20px;
    font-weight: 700;
}

.page-subtitle {
    color: #6b7280;
    font-size: 13px;
}

.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
}

.form-control {
    border-radius: 12px;
    padding: 12px 14px;
    border: 1px solid #e5e7eb;
    box-shadow: none !important;
}

.form-control:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.15) !important;
}

.btn-primary {
    background: #4f46e5;
    border: none;
    border-radius: 12px;
    padding: 10px 18px;
}

.btn-secondary {
    border-radius: 12px;
    padding: 10px 18px;
}
</style>

<div class="container-fluid">

    <!-- HEADER -->
    <div class="page-card mb-4 page-header">
        <div>
            <div class="page-title">Sửa phụ cấp</div>
            <div class="page-subtitle">Cập nhật thông tin phụ cấp</div>
        </div>
    </div>

    <!-- FORM -->
    <div class="page-card p-4">

        <form action="{{ route('admin.phu-cap.update', $phuCap->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Tên phụ cấp --}}
            <div class="mb-3">
                <label class="form-label">Tên phụ cấp</label>
                <input type="text"
                       name="ten"
                       class="form-control"
                       value="{{ old('ten', $phuCap->ten) }}"
                       required>
            </div>

            {{-- Mã phụ cấp --}}
            <div class="mb-3">
                <label class="form-label">Mã phụ cấp</label>
                <input type="text"
                       name="ma"
                       class="form-control"
                       value="{{ old('ma', $phuCap->ma) }}"
                       required>
            </div>

            {{-- Số tiền --}}
            <div class="mb-3">
                <label class="form-label">Số tiền mặc định</label>
                <input type="number"
                       name="so_tien_mac_dinh"
                       class="form-control"
                       value="{{ old('so_tien_mac_dinh', $phuCap->so_tien_mac_dinh) }}"
                       required>
            </div>

            {{-- Loại phụ cấp --}}
<div class="mb-3">
    <label class="form-label">Loại phụ cấp</label>
    <input type="text"
           name="loai_phu_cap"
           class="form-control"
           value="{{ old('loai_phu_cap', $phuCap->loai_phu_cap) }}">
</div>



{{-- Trạng thái --}}
<div class="mb-3">
    <label class="form-label">Trạng thái</label>
    <select name="trang_thai" class="form-control">
        <option value="1" {{ $phuCap->trang_thai ? 'selected' : '' }}>Hoạt động</option>
        <option value="0" {{ !$phuCap->trang_thai ? 'selected' : '' }}>Tắt</option>
    </select>
</div>

            {{-- Buttons --}}
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    Lưu thay đổi
                </button>

                <a href="{{ route('admin.phu-cap.index') }}" class="btn btn-secondary">
                    Quay lại
                </a>
            </div>

        </form>

    </div>
</div>

@endsection