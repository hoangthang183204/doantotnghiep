@extends('layouts.admin')

@section('title', 'Hồ sơ nhân viên')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-xl shadow-sm p-5">

        <h1 class="text-xl font-bold text-gray-800">
            Quản lý danh sách nhân sự
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Thông tin nhân sự được hiển thị bên dưới. Có thể tìm kiếm, xem hoặc chỉnh sửa.
        </p>

        {{-- SEARCH --}}
        <div class="mt-5 border-t pt-4">

            <form method="GET" action="{{ route('admin.ho-so.index') }}">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    <input
                        type="text"
                        name="keyword"
                        value="{{ request('keyword') }}"
                        placeholder="Tìm họ, tên, email..."
                        class="w-full md:w-1/2 border rounded-lg px-3 py-2 outline-none"
                    >

                    <div class="flex gap-3">

                        <button type="submit" class="bg-blue-700 text-white px-5 py-2 rounded-lg">
                            🔍 Tìm kiếm
                        </button>

                        <a href="{{ route('admin.ho-so.index') }}"
                           class="bg-cyan-500 text-white px-5 py-2 rounded-lg">
                            ↻ Làm mới
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow-sm p-5">

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead>
                    <tr class="text-left text-sm text-gray-600 border-b">
                        <th class="p-3">NHÂN VIÊN</th>
                        <th class="p-3">MÃ NV</th>
                        <th class="p-3">NGÀY SINH</th>
                        <th class="p-3">GIỚI TÍNH</th>
                        <th class="p-3">ĐỊA CHỈ</th>
                        <th class="p-3 text-center">THAO TÁC</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($hoSos as $hoSo)

                        @php
                            $trangThai = (int) ($hoSo->trang_thai ?? 1);
                        @endphp

                        <tr class="border-b hover:bg-gray-50 {{ $trangThai === 0 ? 'opacity-60' : '' }}">

                            {{-- NHÂN VIÊN --}}
                            <td class="p-3">
                                <div class="flex items-start gap-3">

                                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        👤
                                    </div>

                                    <div>

                                        <div class="font-semibold text-gray-800">
                                            {{ $hoSo->ho }} {{ $hoSo->ten }}
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            📧 {{ $hoSo->email_cong_ty ?? '---' }}
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            🆔 {{ $hoSo->ma_nhan_vien ?? '---' }}
                                        </div>

                                        {{-- STATUS --}}
                                        <div class="mt-1">
                                            @if ($trangThai === 0)
                                                <span class="text-xs px-2 py-1 bg-red-100 text-red-600 rounded-full">
                                                    ⛔ Đã nghỉ việc
                                                </span>
                                            @else
                                                <span class="text-xs px-2 py-1 bg-green-100 text-green-600 rounded-full">
                                                    ✅ Đang làm việc
                                                </span>
                                            @endif
                                        </div>

                                    </div>

                                </div>
                            </td>

                            {{-- MÃ NV --}}
                            <td class="p-3 text-sm">
                                {{ $hoSo->ma_nhan_vien ?? '---' }}
                            </td>

                            {{-- NGÀY SINH --}}
                            <td class="p-3 text-sm">
                                {{ $hoSo->ngay_sinh ?? '---' }}
                            </td>

                            {{-- GIỚI TÍNH --}}
                            <td class="p-3 text-sm">
                                {{ $hoSo->gioi_tinh == 'nam' ? 'Nam' : 'Nữ' }}
                            </td>

                            {{-- ĐỊA CHỈ --}}
                            <td class="p-3 text-sm">
                                {{ $hoSo->dia_chi_hien_tai ?? '---' }}
                            </td>

                            {{-- ACTION --}}
                            <td class="p-3">
                                <div class="flex justify-center gap-2">

                                    <a href="{{ route('admin.ho-so.show', $hoSo->id) }}"
                                       class="bg-blue-500 text-white p-2 rounded-lg">
                                        👁
                                    </a>

                                    <a href="{{ route('admin.ho-so.edit', $hoSo->id) }}"
                                       class="bg-yellow-400 text-white p-2 rounded-lg">
                                        ✏️
                                    </a>

                                    @if ($trangThai === 1)

                                        <form method="POST" action="{{ route('admin.ho-so.resign', $hoSo->id) }}">
                                            @csrf
                                            <button class="bg-red-500 text-white p-2 rounded-lg">
                                                ⛔
                                            </button>
                                        </form>

                                    @else

                                        <form method="POST" action="{{ route('admin.ho-so.activate', $hoSo->id) }}">
                                            @csrf
                                            <button class="bg-green-500 text-white p-2 rounded-lg">
                                                ✅
                                            </button>
                                        </form>

                                    @endif

                                </div>
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        <div class="mt-5">
            {{ $hoSos->appends(request()->query())->links() }}
        </div>

    </div>

</div>

@endsection