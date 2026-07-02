@extends('layouts.admin')

@section('content')
    @php
        $hoSo = $ktkl->hoSo;
        $user = $hoSo?->nguoi_dung;

        $isKhenThuong = $ktkl->loai === 'khen_thuong';

        $gradient = $isKhenThuong
            ? 'from-emerald-500 via-blue-500 to-indigo-600'
            : 'from-red-500 via-rose-500 to-pink-600';

        $badge = $isKhenThuong
            ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300'
            : 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-300';

        $label = $isKhenThuong ? 'Khen thưởng' : 'Kỷ luật';
    @endphp

    <div class="min-h-screen bg-gray-50 dark:bg-slate-950">

        {{-- HERO --}}
        <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900">

            {{-- subtle glow --}}
            <div class="absolute inset-0 opacity-30">
                <div class="absolute -top-20 -left-20 w-72 h-72 bg-indigo-500 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-emerald-500 rounded-full blur-3xl"></div>
            </div>

            <div class="relative px-6 py-8">

                <div class="max-w-6xl mx-auto flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                    {{-- LEFT --}}
                    <div class="flex items-center gap-5">

                        {{-- AVATAR --}}
                        @php
                            $avatar = $hoSo?->anh_dai_dien ?? null;
                        @endphp

                        @if ($avatar)
                            <img src="{{ asset('storage/' . $avatar) }}"
                                class="w-16 h-16 rounded-xl object-cover border border-white/20 shadow">
                        @else
                            <div
                                class="w-16 h-16 rounded-xl bg-white/10 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($user?->ho ?? 'U', 0, 1)) }}
                            </div>
                        @endif

                        {{-- INFO --}}
                        <div>
                            <div class="flex items-center gap-3">
                                <h1 class="text-xl md:text-2xl font-semibold text-white">
                                    {{ $ktkl->ten }}
                                </h1>

                                <span
                                    class="px-2.5 py-1 text-xs rounded-full
                            {{ $isKhenThuong ? 'bg-emerald-500/15 text-emerald-300' : 'bg-rose-500/15 text-rose-300' }}">
                                    {{ $label }}
                                </span>
                            </div>

                            <div class="mt-1 text-sm text-gray-300">
                                {{ $hoSo?->ho_ten ?? ($user?->ho . ' ' . $user?->ten ?? '---') }}
                                <span class="text-gray-500">•</span>
                                {{ $hoSo?->ma_nhan_vien ?? '---' }}
                            </div>
                        </div>

                    </div>

                    {{-- RIGHT --}}
                    <div class="flex items-center gap-3">

                        {{-- BACK --}}
                        <a href="{{ url()->previous() }}"
                            class="px-4 py-2 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-white text-sm transition">
                            ← Quay lại
                        </a>

                        {{-- DATE --}}
                        <div class="text-right">
                            <div class="text-xs text-gray-400">Ngày quyết định</div>
                            <div class="text-white font-medium">
                                {{ \Carbon\Carbon::parse($ktkl->ngay)->format('d/m/Y') }}
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        {{-- BODY --}}
        <div class="max-w-6xl mx-auto px-6 py-8 grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- INFO --}}
                <div
                    class="rounded-2xl bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 p-6 shadow-sm">

                    <h2 class="text-base font-semibold text-gray-800 dark:text-white mb-5">
                        Thông tin nhân sự
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">

                        <div>
                            <div class="text-gray-400 text-xs">Họ tên</div>
                            <div class="font-semibold text-gray-900 dark:text-white">
                                {{ $hoSo?->ho_ten ?? ($user?->ho . ' ' . $user?->ten ?? '---') }}
                            </div>
                        </div>

                        <div>
                            <div class="text-gray-400 text-xs">Mã nhân viên</div>
                            <div class="font-semibold text-gray-900 dark:text-white">
                                {{ $hoSo?->ma_nhan_vien ?? '---' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-gray-400 text-xs">Phòng ban</div>
                            <div class="font-semibold text-gray-900 dark:text-white">
                                {{ $user?->phongBan?->ten_phong_ban ?? '---' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-gray-400 text-xs">Chức vụ</div>
                            <div class="font-semibold text-gray-900 dark:text-white">
                                {{ $user?->chucVu?->ten_chuc_vu ?? '---' }}
                            </div>
                        </div>

                    </div>
                </div>

                {{-- DECISION --}}
                <div
                    class="rounded-2xl bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 p-6 shadow-sm">

                    <div class="flex justify-between items-center mb-5">
                        <h2 class="text-base font-semibold text-gray-800 dark:text-white">
                            Thông tin quyết định
                        </h2>

                        <span class="px-3 py-1 text-xs rounded-full {{ $badge }}">
                            {{ $label }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">

                        <div>
                            <div class="text-gray-400 text-xs">Số quyết định</div>
                            <div class="font-semibold dark:text-white">
                                {{ $ktkl->quyet_dinh_so ?? '---' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-gray-400 text-xs">Hình thức</div>
                            <div class="font-semibold dark:text-white">
                                {{ $ktkl->hinh_thuc ?? '---' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-gray-400 text-xs">Số tiền</div>
                            <div class="font-semibold dark:text-white">
                                {{ number_format($ktkl->so_tien ?? 0, 0, ',', '.') }} đ
                            </div>
                        </div>

                        <div>
                            <div class="text-gray-400 text-xs">Người ký</div>
                            <div class="font-semibold dark:text-white">
                                {{ $ktkl->nguoiKy?->ho_ten ?? ($ktkl->nguoiKy?->ten_dang_nhap ?? '---') }}
                            </div>
                        </div>

                    </div>
                </div>

                {{-- CONTENT --}}
                <div
                    class="rounded-2xl bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 p-6 shadow-sm">

                    <h2 class="text-base font-semibold text-gray-800 dark:text-white mb-4">
                        Nội dung
                    </h2>

                    <div class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                        {!! nl2br(e($ktkl->noi_dung)) !!}
                    </div>

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="space-y-6">

                {{-- TIMELINE --}}
                <div
                    class="rounded-2xl bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 p-6 shadow-sm">

                    <h2 class="text-base font-semibold text-gray-800 dark:text-white mb-5">
                        Timeline
                    </h2>

                    <div class="space-y-4 text-sm">

                        <div>
                            <div class="text-xs text-gray-400">Ngày quyết định</div>
                            <div class="dark:text-white font-medium">
                                {{ \Carbon\Carbon::parse($ktkl->ngay)->format('d/m/Y') }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-400">Tạo hệ thống</div>
                            <div class="dark:text-white font-medium">
                                {{ $ktkl->created_at?->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-400">Cập nhật</div>
                            <div class="dark:text-white font-medium">
                                {{ $ktkl->updated_at?->format('d/m/Y H:i') }}
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
