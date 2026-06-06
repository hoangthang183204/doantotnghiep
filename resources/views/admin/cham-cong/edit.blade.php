@extends('layouts.admin')

@section('title', 'Cập nhật chấm công')

@section('content')

    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                Cập nhật chấm công
            </h1>

            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Chỉnh sửa thông tin chấm công nhân viên
            </p>

        </div>

        <form action="{{ route('admin.cham-cong.update', $chamCong->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

                <div class="grid md:grid-cols-2 gap-6">

                    {{-- NHÂN VIÊN --}}
                    <div>
                        <label class="block mb-2 text-gray-700 dark:text-gray-200">
                            Nhân viên
                        </label>

                        <select name="nguoi_dung_id"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-3 bg-white dark:bg-gray-700 dark:text-white">

                            @foreach ($nguoiDungs as $user)
                                <option value="{{ $user->id }}"
                                    {{ $chamCong->nguoi_dung_id == $user->id ? 'selected' : '' }}>

                                    {{ $user->ho_ten }}

                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- NGÀY --}}
                    <div>
                        <label class="block mb-2 text-gray-700 dark:text-gray-200">
                            Ngày chấm công
                        </label>

                        <input type="date" name="ngay_cham_cong"
                            value="{{ old('ngay_cham_cong', $chamCong->ngay_cham_cong?->format('Y-m-d')) }}"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-3 bg-white dark:bg-gray-700 dark:text-white">
                    </div>

                    {{-- GIỜ VÀO --}}
                    <div>
                        <label class="block mb-2 text-gray-700 dark:text-gray-200">
                            Giờ vào
                        </label>

                        <input type="time" name="gio_vao" value="{{ $chamCong->gio_vao }}"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-3 bg-white dark:bg-gray-700 dark:text-white">
                    </div>

                    {{-- GIỜ RA --}}
                    <div>
                        <label class="block mb-2 text-gray-700 dark:text-gray-200">
                            Giờ ra
                        </label>

                        <input type="time" name="gio_ra" value="{{ $chamCong->gio_ra }}"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-3 bg-white dark:bg-gray-700 dark:text-white">
                    </div>

                    {{-- SỐ GIỜ LÀM --}}
                    <div>
                        <label class="block mb-2 text-gray-700 dark:text-gray-200">
                            Số giờ làm
                        </label>

                        <input type="number" step="0.01" name="so_gio_lam" value="{{ $chamCong->so_gio_lam }}"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-3 bg-white dark:bg-gray-700 dark:text-white">
                    </div>

                    {{-- SỐ CÔNG --}}
                    <div>
                        <label class="block mb-2 text-gray-700 dark:text-gray-200">
                            Số công
                        </label>

                        <input type="number" step="0.01" name="so_cong" value="{{ $chamCong->so_cong }}"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-3 bg-white dark:bg-gray-700 dark:text-white">
                    </div>

                    {{-- TĂNG CA --}}
                    <div>
                        <label class="block mb-2 text-gray-700 dark:text-gray-200">
                            Giờ tăng ca
                        </label>

                        <input type="number" step="0.01" name="gio_tang_ca" value="{{ $chamCong->gio_tang_ca }}"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-3 bg-white dark:bg-gray-700 dark:text-white">
                    </div>

                    {{-- TRẠNG THÁI --}}
                    <div>
                        <label class="block mb-2 text-gray-700 dark:text-gray-200">
                            Trạng thái
                        </label>

                        <select name="trang_thai"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-3 bg-white dark:bg-gray-700 dark:text-white">

                            <option value="dung_gio" {{ $chamCong->trang_thai == 'dung_gio' ? 'selected' : '' }}>
                                Đúng giờ
                            </option>

                            <option value="di_muon" {{ $chamCong->trang_thai == 'di_muon' ? 'selected' : '' }}>
                                Đi muộn
                            </option>

                            <option value="ve_som" {{ $chamCong->trang_thai == 've_som' ? 'selected' : '' }}>
                                Về sớm
                            </option>

                            <option value="khong_cham_cong"
                                {{ $chamCong->trang_thai == 'khong_cham_cong' ? 'selected' : '' }}>
                                Không chấm công
                            </option>

                        </select>
                    </div>

                    {{-- NGƯỜI PHÊ DUYỆT --}}
                    <div>
                        <label class="block mb-2 text-gray-700 dark:text-gray-200">
                            Người phê duyệt
                        </label>

                        <select name="nguoi_phe_duyet_id"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-3 bg-white dark:bg-gray-700 dark:text-white">

                            <option value="">
                                Chọn người phê duyệt
                            </option>

                            @foreach ($nguoiPheDuyets as $user)
                                <option value="{{ $user->id }}"
                                    {{ $chamCong->nguoi_phe_duyet_id == $user->id ? 'selected' : '' }}>

                                    {{ $user->ho_ten }}

                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- TRẠNG THÁI DUYỆT --}}
                    <div>
                        <label class="block mb-2 text-gray-700 dark:text-gray-200">
                            Trạng thái duyệt
                        </label>

                        <select name="trang_thai_duyet"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-3 bg-white dark:bg-gray-700 dark:text-white">

                            <option value="0" {{ $chamCong->trang_thai_duyet == 0 ? 'selected' : '' }}>
                                Chờ duyệt
                            </option>

                            <option value="1" {{ $chamCong->trang_thai_duyet == 1 ? 'selected' : '' }}>
                                Đã duyệt
                            </option>

                            <option value="2" {{ $chamCong->trang_thai_duyet == 2 ? 'selected' : '' }}>
                                Từ chối
                            </option>

                        </select>
                    </div>

                </div>

                {{-- GHI CHÚ --}}
                <div class="mt-6">

                    <label class="block mb-2 text-gray-700 dark:text-gray-200">
                        Ghi chú
                    </label>

                    <textarea name="ghi_chu" rows="4"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-3 bg-white dark:bg-gray-700 dark:text-white">{{ $chamCong->ghi_chu }}</textarea>

                </div>

                {{-- BUTTON --}}
                <div class="mt-6 flex justify-end gap-3">

                    <a href="{{ route('admin.cham-cong.index') }}"
                        class="px-5 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">

                        Quay lại

                    </a>

                    <button type="submit" class="px-5 py-3 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg">

                        Cập nhật dữ liệu

                    </button>

                </div>

            </div>

        </form>

    </div>

@endsection
