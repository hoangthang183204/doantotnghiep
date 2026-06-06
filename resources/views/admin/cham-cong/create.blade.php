@extends('layouts.admin')

@section('title', 'Thêm chấm công')

@section('content')

<div class="space-y-6">

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            Thêm chấm công
        </h1>

        <p class="text-gray-500 dark:text-gray-400 mt-1">
            Tạo bản ghi chấm công mới
        </p>

    </div>

    <form action="{{ route('admin.cham-cong.store') }}"
          method="POST">

        @csrf

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

            <div class="grid md:grid-cols-2 gap-6">

                <div>
                    <label class="block mb-2 text-gray-700 dark:text-gray-200">
                        Nhân viên
                    </label>

                    <select name="nguoi_dung_id"
                            class="w-full rounded-lg border px-4 py-3 bg-white dark:bg-gray-700 dark:text-white">

                        @foreach($nguoiDungs as $user)

                            <option value="{{ $user->id }}">
                                {{ $user->ho_ten }}
                            </option>

                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-gray-700 dark:text-gray-200">
                        Ngày chấm công
                    </label>

                    <input type="date"
                           name="ngay_cham_cong"
                           class="w-full rounded-lg border px-4 py-3 bg-white dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label class="block mb-2 text-gray-700 dark:text-gray-200">
                        Giờ vào
                    </label>

                    <input type="time"
                           name="gio_vao"
                           class="w-full rounded-lg border px-4 py-3 bg-white dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label class="block mb-2 text-gray-700 dark:text-gray-200">
                        Giờ ra
                    </label>

                    <input type="time"
                           name="gio_ra"
                           class="w-full rounded-lg border px-4 py-3 bg-white dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label class="block mb-2 text-gray-700 dark:text-gray-200">
                        Số giờ làm
                    </label>

                    <input type="number"
                           step="0.01"
                           name="so_gio_lam"
                           value="0"
                           class="w-full rounded-lg border px-4 py-3 bg-white dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label class="block mb-2 text-gray-700 dark:text-gray-200">
                        Số công
                    </label>

                    <input type="number"
                           step="0.01"
                           name="so_cong"
                           value="0"
                           class="w-full rounded-lg border px-4 py-3 bg-white dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label class="block mb-2 text-gray-700 dark:text-gray-200">
                        Tăng ca
                    </label>

                    <input type="number"
                           step="0.01"
                           name="gio_tang_ca"
                           value="0"
                           class="w-full rounded-lg border px-4 py-3 bg-white dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label class="block mb-2 text-gray-700 dark:text-gray-200">
                        Trạng thái
                    </label>

                    <select name="trang_thai"
                            class="w-full rounded-lg border px-4 py-3 bg-white dark:bg-gray-700 dark:text-white">

                        <option value="dung_gio">Đúng giờ</option>
                        <option value="di_muon">Đi muộn</option>
                        <option value="ve_som">Về sớm</option>
                        <option value="khong_cham_cong">Không chấm công</option>

                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-gray-700 dark:text-gray-200">
                        Người phê duyệt
                    </label>

                    <select name="nguoi_phe_duyet_id"
                            class="w-full rounded-lg border px-4 py-3 bg-white dark:bg-gray-700 dark:text-white">

                        <option value="">
                            Chọn người phê duyệt
                        </option>

                        @foreach($nguoiPheDuyets as $user)

                            <option value="{{ $user->id }}">
                                {{ $user->ho_ten }}
                            </option>

                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-gray-700 dark:text-gray-200">
                        Trạng thái duyệt
                    </label>

                    <select name="trang_thai_duyet"
                            class="w-full rounded-lg border px-4 py-3 bg-white dark:bg-gray-700 dark:text-white">

                        <option value="0">Chờ duyệt</option>
                        <option value="1">Đã duyệt</option>
                        <option value="2">Từ chối</option>

                    </select>
                </div>

            </div>

            <div class="mt-6">

                <label class="block mb-2 text-gray-700 dark:text-gray-200">
                    Ghi chú
                </label>

                <textarea
                    name="ghi_chu"
                    rows="4"
                    class="w-full rounded-lg border px-4 py-3 bg-white dark:bg-gray-700 dark:text-white"></textarea>

            </div>

            <div class="mt-6 flex justify-end gap-3">

                <a href="{{ route('admin.cham-cong.index') }}"
                   class="px-5 py-3 bg-gray-500 text-white rounded-lg">
                    Quay lại
                </a>

                <button
                    type="submit"
                    class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">

                    Lưu dữ liệu

                </button>

            </div>

        </div>

    </form>

</div>

@endsection