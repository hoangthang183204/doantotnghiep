@extends('layouts.admin')

@section('title', 'Gửi Email Phỏng Vấn')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-xl shadow-sm p-5">

        <h1 class="text-xl font-bold text-gray-800">
            Gửi Email Phỏng Vấn
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Chọn ứng viên và nhập thông tin lịch phỏng vấn để gửi email.
        </p>

    </div>

    {{-- FORM --}}
    <div class="bg-white rounded-xl shadow-sm p-5">

        <form method="POST" action="{{ route('admin.ung_vien.email.send') }}"
              class="space-y-5">

            @csrf

            {{-- ỨNG VIÊN --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Ứng viên
                </label>

                <select name="ung_vien_id"
                        required
                        class="w-full border rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500">

                    <option value="">-- Chọn ứng viên --</option>

                    @foreach($ungViens as $uv)
                        <option value="{{ $uv->id }}">
                            {{ $uv->ho }} {{ $uv->ten }} - {{ $uv->email }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- THỜI GIAN --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Thời gian phỏng vấn
                </label>

                <input type="datetime-local"
                       name="thoi_gian"
                       required
                       class="w-full border rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- ĐỊA ĐIỂM --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Địa điểm
                </label>

                <input type="text"
                       name="dia_diem"
                       placeholder="VD: Phòng họp tầng 3 - Công ty ABC"
                       required
                       class="w-full border rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- BUTTON --}}
            <div class="flex justify-end gap-3 border-t pt-4">

                <a href="{{ route('admin.ung_vien.email.index') }}"
                   class="bg-gray-200 text-gray-700 px-5 py-2 rounded-lg">
                    Quay lại
                </a>

                <button type="submit"
                        class="bg-blue-700 text-white px-6 py-2 rounded-lg">
                    📩 Gửi Email
                </button>

            </div>

        </form>

    </div>

</div>

@endsection