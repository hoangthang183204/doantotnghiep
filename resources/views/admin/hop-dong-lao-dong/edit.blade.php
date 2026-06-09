@extends('layouts.admin')

@section('title', 'Sửa hợp đồng lao động')

@section('content')

<div class="space-y-6">

<div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
        Sửa hợp đồng lao động
    </h1>
    <p class="text-gray-600 dark:text-gray-400 mt-1">
        Chỉnh sửa thông tin hợp đồng số {{ $hopDong->so_hop_dong }}
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
    action="{{ route('admin.hop-dong.update', $hopDong->id) }}"
    method="POST"
    enctype="multipart/form-data"
    class="card p-6 space-y-6"
>
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div>
            <label class="block text-sm font-medium text-gray-700">
                Nhân viên
            </label>

            <select
                name="nguoi_dung_id"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                required
            >
                @foreach($nguoiDungs as $item)
                    <option
                        value="{{ $item->id }}"
                        {{ old('nguoi_dung_id', $hopDong->nguoi_dung_id) == $item->id ? 'selected' : '' }}
                    >
                        {{ $item->ho_ten ?? $item->ten ?? $item->email }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">
                Chức vụ
            </label>

            <select
                name="chuc_vu_id"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                required
            >
                @foreach($chucVus as $item)
                    <option
                        value="{{ $item->id }}"
                        {{ old('chuc_vu_id', $hopDong->chuc_vu_id) == $item->id ? 'selected' : '' }}
                    >
                        {{ $item->ten }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">
                Số hợp đồng
            </label>

            <input
                type="text"
                name="so_hop_dong"
                value="{{ old('so_hop_dong', $hopDong->so_hop_dong) }}"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                required
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">
                Loại hợp đồng
            </label>

            <select
                name="loai_hop_dong"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                required
            >
                <option value="thu_viec" {{ $hopDong->loai_hop_dong == 'thu_viec' ? 'selected' : '' }}>Thử việc</option>
                <option value="xac_dinh_thoi_han" {{ $hopDong->loai_hop_dong == 'xac_dinh_thoi_han' ? 'selected' : '' }}>Xác định thời hạn</option>
                <option value="khong_xac_dinh_thoi_han" {{ $hopDong->loai_hop_dong == 'khong_xac_dinh_thoi_han' ? 'selected' : '' }}>Không xác định thời hạn</option>
                <option value="mua_vu" {{ $hopDong->loai_hop_dong == 'mua_vu' ? 'selected' : '' }}>Mùa vụ</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">
                Ngày bắt đầu
            </label>

            <input
                type="date"
                name="ngay_bat_dau"
                value="{{ old('ngay_bat_dau', \Carbon\Carbon::parse($hopDong->ngay_bat_dau)->format('Y-m-d')) }}"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                required
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">
                Ngày kết thúc
            </label>

            <input
                type="date"
                name="ngay_ket_thuc"
                value="{{ old('ngay_ket_thuc', $hopDong->ngay_ket_thuc ? \Carbon\Carbon::parse($hopDong->ngay_ket_thuc)->format('Y-m-d') : '') }}"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">
                Lương cơ bản
            </label>

            <input
                type="number"
                step="0.01"
                name="luong_co_ban"
                value="{{ old('luong_co_ban', $hopDong->luong_co_ban) }}"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                required
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">
                Phụ cấp
            </label>

            <input
                type="number"
                step="0.01"
                name="phu_cap"
                value="{{ old('phu_cap', $hopDong->phu_cap) }}"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">
                Hình thức làm việc
            </label>

            <input
                type="text"
                name="hinh_thuc_lam_viec"
                value="{{ old('hinh_thuc_lam_viec', $hopDong->hinh_thuc_lam_viec) }}"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">
                Địa điểm làm việc
            </label>

            <input
                type="text"
                name="dia_diem_lam_viec"
                value="{{ old('dia_diem_lam_viec', $hopDong->dia_diem_lam_viec) }}"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">
                Trạng thái hợp đồng
            </label>

            <select
                name="trang_thai_hop_dong"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
            >
                <option value="tao_moi" {{ $hopDong->trang_thai_hop_dong == 'tao_moi' ? 'selected' : '' }}>Tạo mới</option>
                <option value="chua_hieu_luc" {{ $hopDong->trang_thai_hop_dong == 'chua_hieu_luc' ? 'selected' : '' }}>Chưa hiệu lực</option>
                <option value="hieu_luc" {{ $hopDong->trang_thai_hop_dong == 'hieu_luc' ? 'selected' : '' }}>Hiệu lực</option>
                <option value="het_han" {{ $hopDong->trang_thai_hop_dong == 'het_han' ? 'selected' : '' }}>Hết hạn</option>
                <option value="huy_bo" {{ $hopDong->trang_thai_hop_dong == 'huy_bo' ? 'selected' : '' }}>Hủy bỏ</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">
                Trạng thái ký
            </label>

            <select
                name="trang_thai_ky"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
            >
                <option value="cho_ky" {{ $hopDong->trang_thai_ky == 'cho_ky' ? 'selected' : '' }}>Chờ ký</option>
                <option value="da_ky" {{ $hopDong->trang_thai_ky == 'da_ky' ? 'selected' : '' }}>Đã ký</option>
                <option value="tu_choi_ky" {{ $hopDong->trang_thai_ky == 'tu_choi_ky' ? 'selected' : '' }}>Từ chối ký</option>
            </select>
        </div>

    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">
            Điều khoản hợp đồng
        </label>

        <textarea
            name="dieu_khoan"
            rows="4"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
        >{{ old('dieu_khoan', $hopDong->dieu_khoan) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">
            File hợp đồng đã ký
        </label>

        <input
            type="file"
            name="file_hop_dong_da_ky"
            class="mt-1 block w-full"
        >

        @if($hopDong->file_hop_dong_da_ky)
            <p class="mt-2 text-sm">
                File hiện tại:
                <a
                    href="{{ asset('storage/'.$hopDong->file_hop_dong_da_ky) }}"
                    target="_blank"
                    class="text-blue-600 hover:underline"
                >
                    Xem file
                </a>
            </p>
        @endif
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">
            Ghi chú
        </label>

        <textarea
            name="ghi_chu"
            rows="3"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
        >{{ old('ghi_chu', $hopDong->ghi_chu) }}</textarea>
    </div>

    <div class="flex justify-end gap-3 pt-4 border-t">
        <a
            href="{{ route('admin.hop-dong.index') }}"
            class="px-4 py-2 border rounded-lg hover:bg-gray-50"
        >
            Quay lại
        </a>

        <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg"
        >
            Cập nhật hợp đồng
        </button>
    </div>

</form>

</div>
@endsection
