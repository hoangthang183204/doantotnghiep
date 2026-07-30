@extends('layouts.admin')

@section('content')
    @php
        $hoSo = $ktkl->hoSo;
        $user = $hoSo?->nguoi_dung;

        $isKhenThuong = $ktkl->loai === 'khen_thuong';

        $badge = $isKhenThuong
            ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300'
            : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300';

        $label = $isKhenThuong ? 'Khen thưởng' : 'Kỷ luật';

        $avatar = $hoSo?->anh_dai_dien;
    @endphp

    <div class="p-6 max-w-7xl mx-auto space-y-6">

        {{-- HEADER --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    {{-- Avatar --}}
                    <div class="flex-shrink-0">
                        @if (!empty($avatar))
                            <img src="{{ asset('storage/' . $avatar) }}"
                                class="w-12 h-12 rounded-full object-cover border border-gray-200 dark:border-gray-700"
                                alt="avatar">
                        @else
                            <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-lg">
                                {{ strtoupper(substr($hoSo?->ho ?? 'U', 0, 1) . substr($hoSo?->ten ?? 'N', 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                                {{ $ktkl->ten }}
                            </h1>
                            <span class="px-2.5 py-0.5 text-xs rounded-full {{ $badge }}">
                                {{ $label }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                            {{ $hoSo?->ho_ten ?? ($user?->ho . ' ' . $user?->ten ?? '---') }}
                            <span class="text-gray-400 mx-1">•</span>
                            {{ $hoSo?->ma_nhan_vien ?? '---' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ url()->previous() }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm transition gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Quay lại
                    </a>
                    <div class="text-right">
                        <div class="text-xs text-gray-500 dark:text-gray-400">Ngày quyết định</div>
                        <div class="text-sm font-medium text-gray-800 dark:text-white">
                            {{ \Carbon\Carbon::parse($ktkl->ngay)->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- BODY --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

            {{-- Thông tin nhân sự --}}
            <div class="mb-8">
                <h2 class="text-base font-semibold text-gray-800 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                    Thông tin nhân sự
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Họ tên</div>
                        <div class="font-medium text-gray-800 dark:text-white">
                            {{ $hoSo?->ho_ten ?? ($user?->ho . ' ' . $user?->ten ?? '---') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Mã nhân viên</div>
                        <div class="font-medium text-gray-800 dark:text-white">
                            {{ $hoSo?->ma_nhan_vien ?? '---' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Phòng ban</div>
                        <div class="font-medium text-gray-800 dark:text-white">
                            {{ $user?->phongBan?->ten_phong_ban ?? '---' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Chức vụ</div>
                        <div class="font-medium text-gray-800 dark:text-white">
                            {{ $user?->chucVu?->ten_chuc_vu ?? '---' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Thông tin quyết định --}}
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                    <h2 class="text-base font-semibold text-gray-800 dark:text-white">
                        Thông tin quyết định
                    </h2>
                    <span class="px-2.5 py-0.5 text-xs rounded-full {{ $badge }}">
                        {{ $label }}
                    </span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Số quyết định</div>
                        <div class="font-medium text-gray-800 dark:text-white">
                            {{ $ktkl->quyet_dinh_so ?? '---' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Hình thức</div>
                        <div class="font-medium text-gray-800 dark:text-white">
                            {{ $ktkl->hinh_thuc ?? '---' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Số tiền</div>
                        <div class="font-medium text-gray-800 dark:text-white">
                            {{ number_format($ktkl->so_tien ?? 0, 0, ',', '.') }} đ
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $isKhenThuong ? 'Mức độ khen thưởng' : 'Mức độ kỷ luật' }}
                        </div>
                        <div class="font-medium text-gray-800 dark:text-white">
                            {{ $ktkl->muc_do_text ?? '---' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Người ký</div>
                        <div class="font-medium text-gray-800 dark:text-white">
                            {{ $ktkl->nguoiKy?->ho_ten ?? ($ktkl->nguoiKy?->ten_dang_nhap ?? '---') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tài liệu khen thưởng --}}
            @if ($isKhenThuong)
            <div class="mb-8">
                <h2 class="text-base font-semibold text-gray-800 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                    Tài liệu khen thưởng
                </h2>
                <div class="space-y-4 text-sm">
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Bằng chứng</div>
                        @if (!empty($ktkl->bang_chung))
                            <a href="{{ Storage::url($ktkl->bang_chung) }}" target="_blank" class="text-blue-600 hover:underline">
                                Xem bằng chứng
                            </a>
                        @else
                            <span class="text-gray-400">Không có</span>
                        @endif
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Quyết định (file)</div>
                        @if (!empty($ktkl->quyet_dinh_file))
                            <a href="{{ Storage::url($ktkl->quyet_dinh_file) }}" target="_blank" class="text-blue-600 hover:underline">
                                Xem quyết định
                            </a>
                        @else
                            <span class="text-gray-400">Không có</span>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Nội dung --}}
            <div>
                <h2 class="text-base font-semibold text-gray-800 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                    Nội dung
                </h2>
                <div class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed p-3 rounded-lg bg-gray-50 dark:bg-gray-900/50">
                    {!! nl2br(e($ktkl->noi_dung)) !!}
                </div>
            </div>

        </div>

        {{-- TIMELINE --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-base font-semibold text-gray-800 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                Timeline
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Ngày quyết định</div>
                    <div class="font-medium text-gray-800 dark:text-white">
                        {{ \Carbon\Carbon::parse($ktkl->ngay)->format('d/m/Y') }}
                    </div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Tạo</div>
                    <div class="font-medium text-gray-800 dark:text-white">
                        {{ $ktkl->created_at?->format('d/m/Y H:i') }}
                    </div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Cập nhật</div>
                    <div class="font-medium text-gray-800 dark:text-white">
                        {{ $ktkl->updated_at?->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection