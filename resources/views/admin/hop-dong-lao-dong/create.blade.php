@extends('layouts.admin')

@section('title', 'Thêm hợp đồng lao động')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Thêm hợp đồng lao động
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
            Nhập thông tin hợp đồng lao động
        </p>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('admin.hop-dong.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="bg-white rounded-lg shadow p-6 space-y-6"
    >
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Nhân viên --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Nhân viên <span class="text-red-500">*</span>
                </label>

                <select
                    name="nguoi_dung_id"
                    class="mt-1 block w-full rounded-lg border-gray-300"
                    required
                >
                    <option value="">-- Chọn nhân viên --</option>

                    @foreach($nguoiDungs as $item)
                        <option
                            value="{{ $item->id }}"
                            {{ old('nguoi_dung_id') == $item->id ? 'selected' : '' }}
                        >
                            {{ $item->ho_ten ?? $item->ten ?? $item->email }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Chức vụ --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Chức vụ <span class="text-red-500">*</span>
                </label>

                <select
                    name="chuc_vu_id"
                    class="mt-1 block w-full rounded-lg border-gray-300"
                    required
                >
                    <option value="">-- Chọn chức vụ --</option>

                    @foreach($chucVus as $item)
                        <option
                            value="{{ $item->id }}"
                            {{ old('chuc_vu_id') == $item->id ? 'selected' : '' }}
                        >
                            {{ $item->ten }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Số hợp đồng --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Số hợp đồng
                </label>

                <input
                    type="text"
                    name="so_hop_dong"
                    value="{{ old('so_hop_dong') }}"
                    class="mt-1 block w-full rounded-lg border-gray-300"
                    required
                >
            </div>

            {{-- Loại hợp đồng --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Loại hợp đồng
                </label>

                <select
                    name="loai_hop_dong"
                    class="mt-1 block w-full rounded-lg border-gray-300"
                    required
                >
                    <option value="thu_viec">Thử việc</option>
                    <option value="xac_dinh_thoi_han">Xác định thời hạn</option>
                    <option value="khong_xac_dinh_thoi_han">Không xác định thời hạn</option>
                    <option value="mua_vu">Mùa vụ</option>
                </select>
            </div>

            {{-- Ngày bắt đầu --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Ngày bắt đầu
                </label>

                <input
                    type="date"
                    name="ngay_bat_dau"
                    value="{{ old('ngay_bat_dau') }}"
                    class="mt-1 block w-full rounded-lg border-gray-300"
                    required
                >
            </div>

            {{-- Ngày kết thúc --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Ngày kết thúc
                </label>

                <input
                    type="date"
                    name="ngay_ket_thuc"
                    value="{{ old('ngay_ket_thuc') }}"
                    class="mt-1 block w-full rounded-lg border-gray-300"
                >
            </div>

            {{-- Lương cơ bản --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Lương cơ bản
                </label>

                <input
                    type="number"
                    step="0.01"
                    name="luong_co_ban"
                    value="{{ old('luong_co_ban') }}"
                    class="mt-1 block w-full rounded-lg border-gray-300"
                    required
                >
            </div>

            {{-- Phụ cấp --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Phụ cấp
                </label>

                <input
                    type="number"
                    step="0.01"
                    name="phu_cap"
                    value="{{ old('phu_cap') }}"
                    class="mt-1 block w-full rounded-lg border-gray-300"
                >
            </div>

            {{-- Hình thức làm việc --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Hình thức làm việc
                </label>

                <input
                    type="text"
                    name="hinh_thuc_lam_viec"
                    value="{{ old('hinh_thuc_lam_viec') }}"
                    class="mt-1 block w-full rounded-lg border-gray-300"
                >
            </div>

            {{-- Địa điểm làm việc --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Địa điểm làm việc
                </label>

                <input
                    type="text"
                    name="dia_diem_lam_viec"
                    value="{{ old('dia_diem_lam_viec') }}"
                    class="mt-1 block w-full rounded-lg border-gray-300"
                >
            </div>

            {{-- Trạng thái hợp đồng --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Trạng thái hợp đồng
                </label>

                <select
                    name="trang_thai_hop_dong"
                    class="mt-1 block w-full rounded-lg border-gray-300"
                >
                    <option value="tao_moi">Tạo mới</option>
                    <option value="chua_hieu_luc">Chưa hiệu lực</option>
                    <option value="hieu_luc">Hiệu lực</option>
                    <option value="het_han">Hết hạn</option>
                    <option value="huy_bo">Hủy bỏ</option>
                </select>
            </div>

            {{-- Trạng thái ký --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Trạng thái ký
                </label>

                <select
                    name="trang_thai_ky"
                    class="mt-1 block w-full rounded-lg border-gray-300"
                >
                    <option value="cho_ky">Chờ ký</option>
                    <option value="da_ky">Đã ký</option>
                    <option value="tu_choi_ky">Từ chối ký</option>
                </select>
            </div>

        </div>

        {{-- Điều khoản --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">
                Điều khoản hợp đồng
            </label>

            <textarea
                name="dieu_khoan"
                rows="5"
                class="mt-1 block w-full rounded-lg border-gray-300"
            >{{ old('dieu_khoan') }}</textarea>
        </div>

        {{-- Ghi chú --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">
                Ghi chú
            </label>

            <textarea
                name="ghi_chu"
                rows="3"
                class="mt-1 block w-full rounded-lg border-gray-300"
            >{{ old('ghi_chu') }}</textarea>
        </div>

        {{-- File hợp đồng --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">
                File hợp đồng
            </label>

            <input
                type="file"
                name="file_hop_dong_da_ky"
                class="mt-1 block w-full"
            >
        </div>

        <div class="flex justify-end gap-3 border-t pt-4">
            <a
                href="{{ route('admin.hop-dong.index') }}"
                class="px-4 py-2 border rounded-lg"
            >
                Hủy
            </a>

            <button
                type="submit"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            >
                Lưu hợp đồng
            </button>
        </div>

    </form>
</div>
@endsection