@extends('layouts.admin')

@section('title', 'Danh sách Email đã gửi')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-xl shadow-sm p-5">

        <h1 class="text-xl font-bold text-gray-800">
            Quản lý Email đã gửi
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Danh sách email mời phỏng vấn đã gửi cho ứng viên.
        </p>

        {{-- ACTION --}}
        <div class="mt-5 border-t pt-4 flex justify-end">

            <a href="{{ route('admin.ung_vien.email.create') }}"
               class="bg-blue-700 text-white px-5 py-2 rounded-lg">
                + Gửi Email Mới
            </a>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow-sm p-5">

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead>
                    <tr class="text-left text-sm text-gray-600 border-b">
                        <th class="p-3">ỨNG VIÊN</th>
                        <th class="p-3">EMAIL</th>
                        <th class="p-3">THỜI GIAN PV</th>
                        <th class="p-3">ĐỊA ĐIỂM</th>
                        <th class="p-3">NGÀY GỬI</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($emails as $email)

                        <tr class="border-b hover:bg-gray-50">

                            {{-- ỨNG VIÊN --}}
                            <td class="p-3">
                                <div class="flex items-start gap-3">

                                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        👤
                                    </div>

                                    <div class="font-semibold text-gray-800">
                                        {{ $email['ung_vien'] }}
                                    </div>

                                </div>
                            </td>

                            {{-- EMAIL --}}
                            <td class="p-3 text-sm text-blue-600">
                                {{ $email['email'] }}
                            </td>

                            {{-- THỜI GIAN --}}
                            <td class="p-3 text-sm">
                                <span class="px-2 py-1 bg-indigo-100 text-indigo-600 rounded-full text-xs">
                                    {{ $email['thoi_gian'] }}
                                </span>
                            </td>

                            {{-- ĐỊA ĐIỂM --}}
                            <td class="p-3 text-sm">
                                {{ $email['dia_diem'] }}
                            </td>

                            {{-- NGÀY GỬI --}}
                            <td class="p-3 text-sm text-gray-500">
                                {{ $email['ngay_gui'] }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="text-center text-gray-500 py-6">
                                Chưa có email nào được gửi
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection