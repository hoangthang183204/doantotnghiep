@extends('layouts.admin')

@section('content')
    <div class="p-6 max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.ung_vien.index') }}"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Chi tiết ứng viên
                </h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.ung_vien.index') }}"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium transition text-sm">
                    ← Quay lại
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div
                class="p-4 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-100 dark:border-green-800 text-sm">
                {!! session('success') !!}
            </div>
        @endif

        @if (session('error'))
            <div
                class="p-4 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border border-red-100 dark:border-red-800 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Thông tin ứng viên -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Cột trái -->
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm text-gray-500 dark:text-gray-400">Mã hồ sơ</label>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                <span class="px-3 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-sm">
                                    {{ $ungVien->ma_ho_so }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <label class="text-sm text-gray-500 dark:text-gray-400">Họ tên</label>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $ungVien->ho }} {{ $ungVien->ten }}
                            </p>
                        </div>

                        <div>
                            <label class="text-sm text-gray-500 dark:text-gray-400">Email</label>
                            <p class="text-gray-900 dark:text-white">
                                <a href="mailto:{{ $ungVien->email }}" class="text-blue-600 hover:underline">
                                    {{ $ungVien->email }}
                                </a>
                            </p>
                        </div>

                        <div>
                            <label class="text-sm text-gray-500 dark:text-gray-400">SĐT</label>
                            <p class="text-gray-900 dark:text-white">
                                <a href="tel:{{ $ungVien->so_dien_thoai }}" class="text-blue-600 hover:underline">
                                    {{ $ungVien->so_dien_thoai }}
                                </a>
                            </p>
                        </div>
                    </div>

                    <!-- Cột phải -->
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm text-gray-500 dark:text-gray-400">Tin tuyển dụng</label>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ $ungVien->tinTuyenDung?->tieu_de ?? 'Không xác định' }}
                            </p>
                        </div>

                        <div>
                            <label class="text-sm text-gray-500 dark:text-gray-400">Phòng ban</label>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ $ungVien->phongBan?->ten_phong_ban ?? 'Không xác định' }}
                            </p>
                        </div>

                        <div>
                            <label class="text-sm text-gray-500 dark:text-gray-400">Lương mong muốn</label>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ $ungVien->luong_mong_muon ? number_format($ungVien->luong_mong_muon) . ' VNĐ' : 'Thỏa thuận' }}
                            </p>
                        </div>

                        <div>
                            <label class="text-sm text-gray-500 dark:text-gray-400">Trạng thái</label>
                            <div class="mt-1">
                                {!! $ungVien->trang_thai_badge !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin bổ sung -->
        @if ($ungVien->ghi_chu || $ungVien->cv_path)
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Thông tin bổ sung</h3>

                @if ($ungVien->ghi_chu)
                    <div class="mb-4">
                        <label class="text-sm text-gray-500 dark:text-gray-400">Ghi chú</label>
                        <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $ungVien->ghi_chu }}</p>
                    </div>
                @endif

                @if ($ungVien->cv_path)
                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">CV</label>
                        <p>
                            <a href="{{ asset('storage/' . $ungVien->cv_path) }}" target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 2v4a2 2 0 002 2h4m-6 9l-3 3m0 0l-3-3m3 3V8m-5 10h14" />
                                </svg>
                                Xem CV
                            </a>
                        </p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Lịch sử email -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    📧 Lịch sử email đã gửi
                </h3>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Tổng: {{ $ungVien->lichSuEmails->count() ?? 0 }} email
                </span>
            </div>

            @if (isset($ungVien->lichSuEmails) && $ungVien->lichSuEmails->count() > 0)
                <div class="space-y-3">
                    @foreach ($ungVien->lichSuEmails->sortByDesc('created_at') as $email)
                        <div
                            class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $email->tieu_de }}</p>
                                        <span
                                            class="px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                            {{ $email->loai_email_text ?? 'Thông báo' }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1 whitespace-pre-wrap">
                                        {{ $email->noi_dung }}</p>
                                    <div
                                        class="flex items-center gap-4 mt-2 text-xs text-gray-500 dark:text-gray-400 flex-wrap">
                                        <span>📅 Gửi:
                                            {{ $email->thoi_gian_gui ? $email->thoi_gian_gui->format('d/m/Y H:i') : '---' }}</span>
                                        <span>👤 Người gửi:
                                            {{ $email->nguoiGui?->name ?? ($email->nguoiGui?->email ?? '---') }}</span>
                                        {!! $email->trang_thai_badge ??
                                            '<span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-medium">⏳ Đã gửi</span>' !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 text-gray-500 dark:text-gray-400">
                    <p>Chưa có email nào được gửi cho ứng viên này</p>
                </div>
            @endif
        </div>
    </div>
@endsection