@extends('layouts.admin')

@section('content')
@php
    $hoSo = $ktkl->hoSo;
    $user = $hoSo?->nguoi_dung;

    $isKhenThuong = $ktkl->loai === 'khen_thuong';

    $badge = $isKhenThuong
        ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300'
        : 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-300';

    $label = $isKhenThuong ? 'Khen thưởng' : 'Kỷ luật';
@endphp

<div class="min-h-screen bg-slate-50 dark:bg-slate-950">

    {{-- HERO --}}
    <div class="relative overflow-hidden
        bg-gradient-to-r from-slate-100 via-slate-200 to-slate-100
        dark:from-slate-950 dark:via-slate-900 dark:to-slate-900">

        <div class="absolute inset-0 opacity-20">
            <div class="absolute -top-20 -left-20 w-72 h-72 bg-slate-300 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-slate-400 rounded-full blur-3xl"></div>
        </div>

        <div class="relative px-10 py-10">

            <div class="max-w-[1600px] mx-auto flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                {{-- LEFT --}}
                <div class="flex items-center gap-5">

                    @php
                        $avatar = $hoSo?->anh_dai_dien ?? null;
                    @endphp

                    @if ($avatar)
                        <img src="{{ asset('storage/' . $avatar) }}"
                            class="w-16 h-16 rounded-2xl object-cover border border-slate-200 dark:border-slate-800 shadow">
                    @else
                        <div class="w-16 h-16 rounded-2xl bg-slate-200 dark:bg-slate-800 flex items-center justify-center text-slate-700 dark:text-white font-bold">
                            {{ strtoupper(substr($user?->ho ?? 'U', 0, 1)) }}
                        </div>
                    @endif

                    <div>
                        <div class="flex items-center gap-3">
                            <h1 class="text-xl md:text-2xl font-semibold text-slate-800 dark:text-white">
                                {{ $ktkl->ten }}
                            </h1>

                            <span class="px-2.5 py-1 text-xs rounded-full {{ $badge }}">
                                {{ $label }}
                            </span>
                        </div>

                        <div class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                            {{ $hoSo?->ho_ten ?? ($user?->ho . ' ' . $user?->ten ?? '---') }}
                            <span class="text-slate-400">•</span>
                            {{ $hoSo?->ma_nhan_vien ?? '---' }}
                        </div>
                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="flex items-center gap-3">

                    <a href="{{ url()->previous() }}"
                        class="px-4 py-2 rounded-2xl bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-white text-sm transition">
                        ← Quay lại
                    </a>

                    <div class="text-right">
                        <div class="text-xs text-slate-500 dark:text-slate-400">Ngày quyết định</div>
                        <div class="text-slate-800 dark:text-white font-medium">
                            {{ \Carbon\Carbon::parse($ktkl->ngay)->format('d/m/Y') }}
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    {{-- BODY --}}
    <div class="max-w-[95%] xl:max-w-[1600px] mx-auto px-10 py-12
        grid grid-cols-1 xl:grid-cols-12 gap-10">

        {{-- LEFT --}}
        <div class="xl:col-span-8 space-y-10">

            {{-- INFO --}}
            <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-8 shadow-sm hover:shadow-md transition">

                <h2 class="text-base font-semibold text-slate-800 dark:text-white mb-6">
                    Thông tin nhân sự
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">

                    <div>
                        <div class="text-slate-500 text-xs">Họ tên</div>
                        <div class="font-semibold text-slate-800 dark:text-white">
                            {{ $hoSo?->ho_ten ?? ($user?->ho . ' ' . $user?->ten ?? '---') }}
                        </div>
                    </div>

                    <div>
                        <div class="text-slate-500 text-xs">Mã nhân viên</div>
                        <div class="font-semibold text-slate-800 dark:text-white">
                            {{ $hoSo?->ma_nhan_vien ?? '---' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-slate-500 text-xs">Phòng ban</div>
                        <div class="font-semibold text-slate-800 dark:text-white">
                            {{ $user?->phongBan?->ten_phong_ban ?? '---' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-slate-500 text-xs">Chức vụ</div>
                        <div class="font-semibold text-slate-800 dark:text-white">
                            {{ $user?->chucVu?->ten_chuc_vu ?? '---' }}
                        </div>
                    </div>

                </div>
            </div>

            {{-- DECISION --}}
            <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-8 shadow-sm hover:shadow-md transition">

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-base font-semibold text-slate-800 dark:text-white">
                        Thông tin quyết định
                    </h2>

                    <span class="px-3 py-1 text-xs rounded-full {{ $badge }}">
                        {{ $label }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">

                    <div>
                        <div class="text-slate-500 text-xs">Số quyết định</div>
                        <div class="font-semibold text-slate-800 dark:text-white">
                            {{ $ktkl->quyet_dinh_so ?? '---' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-slate-500 text-xs">Hình thức</div>
                        <div class="font-semibold text-slate-800 dark:text-white">
                            {{ $ktkl->hinh_thuc ?? '---' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-slate-500 text-xs">Số tiền</div>
                        <div class="font-semibold text-slate-800 dark:text-white">
                            {{ number_format($ktkl->so_tien ?? 0, 0, ',', '.') }} đ
                        </div>
                    </div>

                    <div>
                        <div class="text-slate-500 text-xs">Người ký</div>
                        <div class="font-semibold text-slate-800 dark:text-white">
                            {{ $ktkl->nguoiKy?->ho_ten ?? ($ktkl->nguoiKy?->ten_dang_nhap ?? '---') }}
                        </div>
                    </div>

                </div>
            </div>

            {{-- CONTENT --}}
            <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-8 shadow-sm hover:shadow-md transition">

                <h2 class="text-base font-semibold text-slate-800 dark:text-white mb-5">
                    Nội dung
                </h2>

                <div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
                    {!! nl2br(e($ktkl->noi_dung)) !!}
                </div>

            </div>

        </div>

        {{-- RIGHT --}}
        <div class="xl:col-span-4 space-y-10">

            {{-- TIMELINE --}}
            <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-8 shadow-sm hover:shadow-md transition">

                <h2 class="text-base font-semibold text-slate-800 dark:text-white mb-6">
                    Timeline
                </h2>

                <div class="space-y-4 text-sm">

                    <div>
                        <div class="text-xs text-slate-500">Ngày quyết định</div>
                        <div class="text-slate-800 dark:text-white font-medium">
                            {{ \Carbon\Carbon::parse($ktkl->ngay)->format('d/m/Y') }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-slate-500">Tạo hệ thống</div>
                        <div class="text-slate-800 dark:text-white font-medium">
                            {{ $ktkl->created_at?->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-slate-500">Cập nhật</div>
                        <div class="text-slate-800 dark:text-white font-medium">
                            {{ $ktkl->updated_at?->format('d/m/Y H:i') }}
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>
@endsection