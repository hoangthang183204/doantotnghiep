@extends('layouts.employee')

@section('content')

<div class="container mx-auto px-6 py-6">

    <div class="bg-white rounded-xl shadow">

        <div class="border-b px-6 py-4">
            <h2 class="text-xl font-semibold">
                Gửi yêu cầu ứng lương
            </h2>
        </div>

        <form action="{{ route('employee.ung-luong.store') }}"
              method="POST"
              class="p-6">

            @csrf

            <div class="mb-5">

                <label class="block font-medium mb-2">
                    Số tiền muốn ứng
                </label>

                <input
                    type="number"
                    name="so_tien"
                    class="w-full border rounded-lg px-4 py-2"
                    value="{{ old('so_tien') }}"
                    required>

                @error('so_tien')
                    <p class="text-red-500 mt-1 text-sm">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            <div class="mb-5">

                <label class="block font-medium mb-2">
                    Lý do
                </label>

                <textarea
                    name="ly_do"
                    rows="5"
                    class="w-full border rounded-lg px-4 py-2"
                    required>{{ old('ly_do') }}</textarea>

                @error('ly_do')
                    <p class="text-red-500 mt-1 text-sm">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            <div class="flex gap-3">

                <button
                    class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">

                    Gửi yêu cầu

                </button>

                <a href="{{ route('employee.ung-luong.index') }}"
                   class="bg-gray-300 px-5 py-2 rounded-lg">

                    Quay lại

                </a>

            </div>

        </form>

    </div>

</div>

@endsection