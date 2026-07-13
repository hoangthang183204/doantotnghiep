@extends('layouts.admin')

@section('title','Chi tiết ứng lương')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-xl shadow p-6">

        <h2 class="text-2xl font-bold mb-6">
            Chi tiết yêu cầu ứng lương
        </h2>

        <table class="table-auto w-full">

            <tr class="border-b">
                <td class="py-3 font-semibold w-52">
                    Nhân viên
                </td>

                <td>
                    {{ trim(($khauTru->nguoiDung->ho_so->ho ?? '').' '.($khauTru->nguoiDung->ho_so->ten ?? '')) }}
                </td>
            </tr>

            <tr class="border-b">
                <td class="py-3 font-semibold">
                    Loại
                </td>

                <td>
                    {{ $khauTru->loai_text }}
                </td>
            </tr>

            <tr class="border-b">
                <td class="py-3 font-semibold">
                    Số tiền
                </td>

                <td class="text-red-600 font-bold">
                    {{ number_format($khauTru->so_tien) }} đ
                </td>
            </tr>

            <tr class="border-b">
                <td class="py-3 font-semibold">
                    Lý do
                </td>

                <td>
                    {{ $khauTru->ly_do }}
                </td>
            </tr>

            <tr class="border-b">
                <td class="py-3 font-semibold">
                    Tháng
                </td>

                <td>
                    {{ $khauTru->thang }}/{{ $khauTru->nam }}
                </td>
            </tr>

            <tr class="border-b">
                <td class="py-3 font-semibold">
                    Trạng thái
                </td>

                <td>

                    @if($khauTru->trang_thai == 'hieu_luc')

                        <span class="text-green-600 font-bold">
                            Đã duyệt
                        </span>

                    @else

                        <span class="text-yellow-600 font-bold">
                            Chờ duyệt
                        </span>

                    @endif

                </td>
            </tr>

        </table>

        <div class="mt-6">

            <a href="{{ route('admin.khau-tru-khac.index') }}"
               class="px-5 py-2 bg-gray-500 text-white rounded-lg">

                Quay lại

            </a>

        </div>

    </div>

</div>

@endsection