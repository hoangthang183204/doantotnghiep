@extends('layouts.admin')

@section('content')
<div class="grid gap-4">

    @forelse($lichSus as $item)

    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 hover:shadow-md transition">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <div>
                <div class="flex items-center gap-3">

                    <span class="font-bold text-lg text-gray-900 dark:text-white">
                        {{ $item->donXinNghi->ma_don_nghi ?? '-' }}
                    </span>

                    @if($item->ket_qua == 'da_duyet')
                        <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">
                            Đã duyệt
                        </span>
                    @else
                        <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700">
                            Từ chối
                        </span>
                    @endif

                </div>

                <div class="mt-2 text-sm text-gray-500">

                    Người duyệt:
                    <span class="font-medium text-gray-700 dark:text-gray-300">
                        {{ $item->nguoiDuyet?->ho_ten ?? $item->nguoiDuyet?->ten_dang_nhap }}
                    </span>

                    • Cấp duyệt:
                    <span class="font-semibold">
                        {{ $item->cap_duyet }}
                    </span>

                </div>

                @if($item->ghi_chu)
                    <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                        {{ $item->ghi_chu }}
                    </p>
                @endif
            </div>

            <div class="text-right">

                <div class="text-sm text-gray-500 mb-3">
                    {{ $item->thoi_gian_duyet->format('d/m/Y H:i') }}
                </div>

                <a href="{{ route('admin.lich-su-duyet.show',$item->id) }}"
                   class="inline-flex items-center px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm">
                    Xem chi tiết
                </a>

            </div>

        </div>

    </div>

    @empty

    <div class="bg-white dark:bg-gray-800 rounded-2xl p-12 text-center">

        <svg class="w-14 h-14 mx-auto text-gray-300 mb-4"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/>
        </svg>

        <p class="text-gray-500">
            Chưa có lịch sử duyệt nào
        </p>

    </div>

    @endforelse

</div>
@endsection
