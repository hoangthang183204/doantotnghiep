@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">

    <div class="max-w-3xl mx-auto">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Sửa lương cơ bản</h1>
            <p class="text-gray-500 mt-1">Cập nhật thông tin lương nhân viên</p>
        </div>

        {{-- Card --}}
        <form method="POST"
              action="{{ route('admin.luong.update', $luong->id) }}"
              class="bg-white shadow-lg rounded-2xl p-6 space-y-5">

            @csrf
            @method('PUT')

            {{-- Nhân viên --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nhân viên
                </label>
                <select name="nguoi_dung_id"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($nhanViens as $nv)
                        <option value="{{ $nv->id }}"
                            {{ $luong->nguoi_dung_id == $nv->id ? 'selected' : '' }}>
                            {{ $nv->ho_ten }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Hợp đồng --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Hợp đồng
                </label>
                <select name="hop_dong_id"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($hopDongs as $hd)
                        <option value="{{ $hd->id }}"
                            {{ $luong->hop_dong_lao_dong_id == $hd->id ? 'selected' : '' }}>
                            {{ $hd->so_hop_dong }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Grid input --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Lương cơ bản
                    </label>
                    <input type="number" name="luong_co_ban"
                           value="{{ $luong->luong_co_ban }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-3
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Phụ cấp
                    </label>
                    <input type="number" name="phu_cap"
                           value="{{ $luong->phu_cap }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-3
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Thưởng
                    </label>
                    <input type="number" name="tien_thuong"
                           value="{{ $luong->tien_thuong }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-3
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tiền phạt
                    </label>
                    <input type="number" name="tien_phat"
                           value="{{ $luong->tien_phat }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-3
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-3 pt-4">

                <a href="{{ route('admin.luong.index') }}"
                   class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700
                          hover:bg-gray-200 transition">
                    Huỷ
                </a>

                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-blue-600 text-white
                               hover:bg-blue-700 transition shadow-md">
                    Cập nhật
                </button>

            </div>

        </form>
    </div>
</div>
@endsection