@extends('layouts.admin')

@section('title', 'Thêm phụ cấp')

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

.form-title {
    font-size: 20px;
    font-weight: 700;
}

.form-subtitle {
    color: #6b7280;
    font-size: 13px;
}

.form-control {
    border-radius: 12px;
    padding: 12px 14px;
    border: 1px solid #e5e7eb;
    transition: 0.2s;
}

.form-control:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
}

.label {
    font-weight: 600;
    margin-bottom: 6px;
    color: #374151;
}

.switch {
    display: flex;
    align-items: center;
    gap: 10px;
}

.switch input {
    width: 40px;
    height: 20px;
}
</style>

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="page-card p-4 mb-4">
        <div class="form-title">Thêm phụ cấp</div>
        <div class="form-subtitle">Tạo mới thông tin phụ cấp trong hệ thống</div>
    </div>

    {{-- FORM --}}
    <div class="page-card p-4">

        <form action="{{ route('admin.phu-cap.store') }}" method="POST">
            @csrf

            <div class="row">

                {{-- LEFT --}}
                <div class="col-md-6">

                    <div class="mb-3">
                        <label class="label">Tên phụ cấp</label>
                        <input type="text" name="ten" class="form-control" placeholder="VD: Phụ cấp ăn trưa" required>
                    </div>

                    <div class="mb-3">
                        <label class="label">Mã phụ cấp</label>
                        <input type="text" name="ma" class="form-control" placeholder="VD: PC001" required>
                    </div>

                    <div class="mb-3">
                        <label class="label">Loại phụ cấp</label>
                        <input type="text" name="loai_phu_cap" class="form-control" placeholder="VD: Cố định / Theo ngày" required>
                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="col-md-6">

                    <div class="mb-3">
                        <label class="label">Số tiền mặc định</label>
                        <input type="number" name="so_tien_mac_dinh" class="form-control" placeholder="VD: 500000" required>
                    </div>

                    {{-- Switch chịu thuế --}}
                    <div class="mb-3 switch">
                        <input type="checkbox" name="chiu_thue" value="1">
                        <label class="label mb-0">Chịu thuế</label>
                    </div>

                    {{-- Trạng thái --}}
                    <div class="mb-3">
                        <label class="label">Trạng thái</label>
                        <select name="trang_thai" class="form-control">
                            <option value="1">Hoạt động</option>
                            <option value="0">Ngừng</option>
                        </select>
                    </div>

                </div>

            </div>

            {{-- BUTTONS --}}
            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-primary px-4">
                    💾 Lưu phụ cấp
                </button>

                <a href="{{ route('admin.phu-cap.index') }}" class="btn btn-light px-4">
                    ← Quay lại
                </a>
            </div>

        </form>

    </div>
</div>

@endsection