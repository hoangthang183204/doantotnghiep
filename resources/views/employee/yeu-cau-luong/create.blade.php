@extends('layouts.employee')

@section('title', 'Yêu cầu xem xét lương')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-xl shadow p-6">

        <h2 class="text-2xl font-bold mb-6">
            Yêu cầu xem xét phiếu lương
        </h2>

        @include('layouts.partials.alerts')

        <div class="mb-6">

            <p>
                <strong>Tháng:</strong>

                {{ $luong->luong_thang }}/{{ $luong->luong_nam }}
            </p>

            <p>
                <strong>Thực nhận:</strong>

                {{ number_format($luong->luong_thuc_nhan) }} đ
            </p>

        </div>

        <form method="POST"
              action="{{ route('employee.yeu-cau-luong.store',$luong->id) }}">

            @csrf

            <label class="font-semibold">
                Lý do xem xét
            </label>

            <textarea
                name="ly_do"
                rows="6"
                class="w-full mt-2 border rounded-lg p-3"
                placeholder="Ví dụ: Tôi bị tính thiếu 2 ngày tăng ca..."
            >{{ old('ly_do') }}</textarea>

            @error('ly_do')
                <p class="text-red-500 mt-2">
                    {{ $message }}
                </p>
            @enderror

            <div class="mt-6 flex gap-3">

                <button
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg">

                    Gửi yêu cầu

                </button>

                <a href="{{ route('employee.bang-luong.show',$luong->id) }}"
                   class="px-5 py-2 bg-gray-200 rounded-lg">

                    Quay lại

                </a>

            </div>

        </form>

    </div>

</div>

@endsection