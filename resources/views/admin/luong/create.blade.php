@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto p-6">

    <h2 class="text-2xl font-bold mb-6">Thêm bảng lương</h2>

    <form method="POST" action="{{ route('admin.luong.store') }}"
          class="bg-white p-6 rounded shadow space-y-4">
        @csrf

        {{-- Nhân viên --}}
        <div>
            <label class="block font-medium">Nhân viên</label>
            <select name="nguoi_dung_id" class="w-full border rounded p-2">
                @foreach($nguoiDungs as $nv)
                    <option value="{{ $nv->id }}">{{ $nv->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Hợp đồng --}}
        <div>
            <label class="block font-medium">Hợp đồng</label>
            <select name="hop_dong_lao_dong_id" class="w-full border rounded p-2">
                @foreach($hopDongs as $hd)
                    <option value="{{ $hd->id }}">{{ $hd->ma_hop_dong ?? 'Hợp đồng #' . $hd->id }}</option>
                @endforeach
            </select>
        </div>

        {{-- Lương cơ bản --}}
        <div>
            <label class="block font-medium">Lương cơ bản</label>
            <input type="number" name="luong_co_ban"
                   class="w-full border rounded p-2">
        </div>

        {{-- Phụ cấp --}}
        <div>
            <label class="block font-medium">Phụ cấp</label>
            <input type="number" name="phu_cap"
                   class="w-full border rounded p-2">
        </div>

        {{-- Thưởng --}}
        <div>
            <label class="block font-medium">Thưởng</label>
            <input type="number" name="tien_thuong"
                   class="w-full border rounded p-2">
        </div>

        {{-- Phạt --}}
        <div>
            <label class="block font-medium">Phạt</label>
            <input type="number" name="tien_phat"
                   class="w-full border rounded p-2">
        </div>

        {{-- Button --}}
        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded">
            Lưu lương
        </button>

    </form>
</div>
@endsection