@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-4xl mx-auto space-y-6">

    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow">

        <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">
            Chi tiết ứng viên
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <p class="text-gray-500 " >Mã hồ sơ</p>
                <p class="font-semibold dark:text-white">{{ $ungVien->ma_ho_so }}</p>
            </div>

            <div>
                <p class="text-gray-500">Họ tên</p>
                <p class="font-semibold dark:text-white">{{ $ungVien->ho }} {{ $ungVien->ten }}</p>
            </div>

            <div>
                <p class="text-gray-500">Email</p>
                <p class="dark:text-white">{{ $ungVien->email }}</p>
            </div>

            <div>
                <p class="text-gray-500">SĐT</p>
                <p class="dark:text-white">{{ $ungVien->so_dien_thoai }}</p>
            </div>

            <div>
                <p class="text-gray-500">Tin tuyển dụng</p>
                <p class="dark:text-white">{{ $ungVien->tinTuyenDung?->tieu_de }}</p>
            </div>

            <div>
                <p class="text-gray-500">Phòng ban</p>
                <p class="dark:text-white">{{ $ungVien->tinTuyenDung?->phongBan?->ten_phong_ban }}</p>
            </div>

            <div>
                <p class="text-gray-500">Chức vụ</p>
                <p class="dark:text-white">{{ $ungVien->tinTuyenDung?->chucVu?->ten_chuc_vu }}</p>
            </div>

            <div>
                <p class="text-gray-500">Kinh nghiệm</p>
                <p class="dark:text-white">{{ $ungVien->so_nam_kinh_nghiem }} năm</p>
            </div>

            <div>
                <p class="text-gray-500">Lương mong muốn</p>
                <p class="font-semibold text-green-600 dark:text-white">
                    {{ number_format($ungVien->luong_mong_muon) }}
                </p>
            </div>

            <div>
                <p class="text-gray-500">Trạng thái</p>
                <p class="dark:text-white">{{ $ungVien->trang_thai }}</p>
            </div>

        </div>

        <div class="mt-6">
            <a href="{{ route('admin.ung_vien.index') }}"
               class="px-4 py-2 bg-gray-200 rounded-lg ">
                ← Quay lại
            </a>
        </div>

    </div>

</div>
@endsection