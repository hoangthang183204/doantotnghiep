@extends('layouts.employee')

@section('content')
    <div class="p-4 md:p-6 lg:p-8">
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div
                class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            📸 Chấm công tự động bằng khuôn mặt
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Đưa mặt vào khung hình để tự động chấm công</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Hôm nay</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white" id="currentTime">
                            {{ \Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format('H:i:s') }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6">

                @if (!$hasFace)
                    {{-- ⚠️ HIỂN THỊ KHI CHƯA CÓ KHUÔN MẶT - HIỂN THỊ FORM ĐĂNG KÝ --}}
                    <div class="text-center">
                        <div class="text-6xl mb-4">📸</div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Đăng ký khuôn mặt</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            Vui lòng chụp ảnh khuôn mặt của bạn để đăng ký sử dụng chấm công bằng khuôn mặt.
                            <br>
                            <span class="text-sm text-yellow-600 dark:text-yellow-400">⚠️ Đảm bảo ánh sáng đủ và khuôn mặt rõ ràng</span>
                        </p>

                        {{-- Camera đăng ký --}}
                        <div class="max-w-md mx-auto">
                            <div class="relative bg-gray-900 rounded-lg overflow-hidden aspect-video">
                                <video id="registerVideo" class="w-full h-full object-cover" autoplay playsinline></video>
                                <canvas id="registerCanvas" class="hidden"></canvas>

                                {{-- Khung hướng dẫn --}}
                                <div id="registerGuide"
                                    class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <div class="w-48 h-48 border-2 border-dashed border-white/50 rounded-full animate-pulse">
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div
                                        class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white/60 text-xs bg-black/30 px-3 py-1 rounded-full">
                                        Đưa mặt vào giữa khung
                                    </div>
                                </div>

                                <div id="registerOverlay"
                                    class="absolute inset-0 flex items-center justify-center bg-black/70 hidden">
                                    <div class="text-center text-white">
                                        <div
                                            class="loader ease-linear rounded-full border-4 border-t-4 border-blue-500 h-12 w-12 mb-4 mx-auto animate-spin">
                                        </div>
                                        <p class="text-sm font-medium">Đang xử lý...</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Nút điều khiển đăng ký --}}
                            <div class="mt-4 flex flex-wrap gap-3 justify-center">
                                <button id="btnRegister"
                                    class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition font-medium flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    📸 Đăng ký khuôn mặt
                                </button>
                                <button id="btnFlipRegister"
                                    class="px-4 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                                    🔄 Lật camera
                                </button>
                            </div>

                            <div id="registerStatus" class="mt-3 p-3 rounded-lg text-sm text-center hidden">
                            </div>

                            <div class="mt-4 text-left text-sm text-gray-500 dark:text-gray-400">
                                <p class="font-medium text-gray-700 dark:text-gray-300">📋 Lưu ý:</p>
                                <ul class="list-disc list-inside space-y-1 mt-1">
                                    <li>Đảm bảo khuôn mặt ở giữa khung hình</li>
                                    <li>Ánh sáng đủ và không bị chói</li>
                                    <li>Không đeo khẩu trang hoặc kính râm</li>
                                    <li>Ảnh sẽ được lưu để xác thực chấm công</li>
                                </ul>
                            </div>
                        </div>

                        {{-- Nút quay lại --}}
                        <div class="mt-6">
                            <a href="{{ route('employee.cham-cong.index') }}"
                                class="inline-flex items-center px-5 py-2.5 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Quay lại chấm công
                            </a>
                        </div>
                    </div>
                @else
                    {{-- 🟢 HIỂN THỊ KHI CÓ KHUÔN MẶT - GIAO DIỆN CHẤM CÔNG --}}
                    <div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                            {{-- Camera - chiếm 8/12 cột --}}
                            <div class="lg:col-span-8">
                                <div class="relative bg-gray-900 rounded-lg overflow-hidden aspect-video">
                                    <video id="video" class="w-full h-full object-cover" autoplay playsinline></video>
                                    <canvas id="canvas" class="hidden"></canvas>

                                    {{-- Khung hướng dẫn --}}
                                    <div id="faceGuide"
                                        class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                        <div
                                            class="w-48 h-48 border-2 border-dashed border-white/50 rounded-full animate-pulse">
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div
                                            class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white/60 text-xs bg-black/30 px-3 py-1 rounded-full">
                                            Đưa mặt vào giữa khung để tự động chấm công
                                        </div>
                                    </div>

                                    {{-- Scan line animation --}}
                                    <div id="scanLine"
                                        class="absolute left-0 right-0 h-0.5 bg-green-400 shadow-lg shadow-green-400/50 hidden"
                                        style="top: 50%; transform: translateY(-50%);">
                                    </div>

                                    <div id="overlay"
                                        class="absolute inset-0 flex items-center justify-center bg-black/70 hidden">
                                        <div class="text-center text-white">
                                            <div
                                                class="loader ease-linear rounded-full border-4 border-t-4 border-blue-500 h-12 w-12 mb-4 mx-auto animate-spin">
                                            </div>
                                            <p class="text-sm font-medium">Đang xác thực khuôn mặt...</p>
                                            <p class="text-xs text-white/50 mt-1">Vui lòng giữ yên</p>
                                        </div>
                                    </div>

                                    <div id="result"
                                        class="absolute bottom-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg text-sm font-semibold hidden z-10">
                                    </div>

                                    {{-- Nút lật camera --}}
                                    <button id="btnFlipCamera"
                                        class="absolute top-4 right-4 p-2.5 bg-black/60 hover:bg-black/80 text-white rounded-full transition z-10 backdrop-blur-sm border border-white/20"
                                        title="Lật camera">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>

                                    {{-- Camera status --}}
                                    <div id="cameraStatus"
                                        class="absolute top-4 left-4 px-2.5 py-1 bg-black/60 backdrop-blur-sm rounded text-xs text-white/80 border border-white/20">
                                        📷 Camera trước
                                    </div>
                                </div>

                                {{-- Auto mode toggle --}}
                                <div class="mt-3 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" id="autoModeToggle" class="sr-only peer" checked>
                                            <div
                                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                            </div>
                                            <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                🤖 Chế độ tự động
                                            </span>
                                        </label>
                                        <span id="autoStatus" class="text-xs text-green-500 font-medium">(Đang hoạt
                                            động)</span>
                                    </div>
                                    <span id="faceDetectStatus" class="text-xs text-gray-500 dark:text-gray-400">
                                        👤 Chờ khuôn mặt...
                                    </span>
                                </div>

                                {{-- Controls --}}
                                <div class="mt-3 flex flex-wrap gap-3">
                                    <button id="btnCheckIn"
                                        class="flex-1 min-w-[120px] px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed font-medium flex items-center justify-center gap-2"
                                        {{ $checkedIn ? 'disabled' : '' }}>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                        </svg>
                                        Check-in
                                    </button>
                                    <button id="btnCheckOut"
                                        class="flex-1 min-w-[120px] px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed font-medium flex items-center justify-center gap-2"
                                        {{ $checkedOut || !$checkedIn ? 'disabled' : '' }}>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Check-out
                                    </button>
                                    <button id="btnCapture"
                                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-medium flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Chụp ảnh
                                    </button>

                                    {{-- Nút đơn xin về sớm --}}
                                    <button id="btnTaoDonVeSom"
                                        class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition font-medium flex items-center justify-center gap-2 {{ $checkedIn && !$checkedOut ? '' : 'opacity-50 cursor-not-allowed' }}"
                                        {{ $checkedIn && !$checkedOut ? '' : 'disabled' }}>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                        📝 Đơn về sớm
                                    </button>
                                </div>

                                {{-- Trạng thái --}}
                                <div id="status"
                                    class="mt-3 p-3 rounded-lg text-sm text-center
                                {{ $checkedIn && $checkedOut
                                    ? 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400 border border-green-200 dark:border-green-800'
                                    : ($checkedIn
                                        ? 'bg-yellow-50 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800'
                                        : 'bg-gray-50 text-gray-500 dark:bg-gray-700/30 dark:text-gray-400 border border-gray-200 dark:border-gray-700') }}">
                                    @if ($checkedIn && $checkedOut)
                                        ✅ Đã check-in và check-out hôm nay
                                        @if (isset($checkInTime))
                                            <span class="block text-xs opacity-70">Check-in: {{ $checkInTime }} |
                                                Check-out: {{ $checkOutTime }}</span>
                                        @endif
                                    @elseif($checkedIn)
                                        ⏳ Đã check-in lúc {{ $checkInTime }}, chưa check-out
                                    @else
                                        ⏰ Chưa check-in hôm nay
                                    @endif
                                </div>
                            </div>

                            {{-- Thông tin bên phải --}}
                            <div class="lg:col-span-4 space-y-4">
                                {{-- Thông tin bảo mật --}}
                                <div
                                    class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">💡</span>
                                        <div>
                                            <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Độ chính xác
                                                cao</p>
                                            <p class="text-xs text-blue-600 dark:text-blue-400">Bảo mật tuyệt đối với công
                                                nghệ nhận diện khuôn mặt</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Thông tin ca làm việc --}}
                                <div
                                    class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-4 border border-indigo-200 dark:border-indigo-800">
                                    <p class="text-xs text-indigo-600 dark:text-indigo-400 font-medium">CA LÀM VIỆC HIỆN
                                        TẠI</p>
                                    @php
                                        $now = \Carbon\Carbon::now('Asia/Ho_Chi_Minh');
                                        $caHienTai = \App\Models\CaLamViec::where('trang_thai', 1)
                                            ->get()
                                            ->first(function ($ca) use ($now) {
                                                $start = \Carbon\Carbon::parse($ca->gio_bat_dau);
                                                $end = \Carbon\Carbon::parse($ca->gio_ket_thuc);
                                                return $now->between($start, $end);
                                            });
                                    @endphp
                                    @if ($caHienTai)
                                        <p class="text-lg font-bold text-indigo-700 dark:text-indigo-300">
                                            {{ $caHienTai->ten }}
                                            <span class="text-sm font-normal text-indigo-500 dark:text-indigo-400">
                                                ({{ \Carbon\Carbon::parse($caHienTai->gio_bat_dau)->format('H:i') }} -
                                                {{ \Carbon\Carbon::parse($caHienTai->gio_ket_thuc)->format('H:i') }})
                                            </span>
                                        </p>
                                    @else
                                        <p class="text-lg font-bold text-gray-400">Ngoài giờ làm việc</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Lịch sử chấm công 7 ngày gần nhất --}}
                        <div class="mt-6">
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                                <div
                                    class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">
                                        <i class="fas fa-history mr-2 text-blue-600 dark:text-blue-400"></i>
                                        Lịch sử 7 ngày gần nhất
                                    </h3>
                                    <a href="{{ route('employee.cham-cong.history') }}"
                                        class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                        Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                                            <tr>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                                                    Ngày</th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                                                    Ca</th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                                                    Check-in</th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                                                    Check-out</th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                                                    Công</th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                                                    Trạng thái</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                            @php
                                                $lichSu7Ngay = \App\Models\ChamCong::where('nguoi_dung_id', auth()->id())
                                                    ->whereBetween('ngay_cham_cong', [
                                                        \Carbon\Carbon::now()->subDays(7)->startOfDay(),
                                                        \Carbon\Carbon::now()->endOfDay(),
                                                    ])
                                                    ->orderBy('ngay_cham_cong', 'desc')
                                                    ->get()
                                                    ->groupBy('ngay_cham_cong')
                                                    ->map(function ($items) {
                                                        return $items->first();
                                                    })
                                                    ->values();
                                            @endphp

                                            @forelse($lichSu7Ngay as $item)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                                        {{ \Carbon\Carbon::parse($item->ngay_cham_cong)->format('d/m/Y') }}
                                                    </td>
                                                    <td class="px-6 py-4 text-sm">
                                                        @php
                                                            $tenCa = $item->caLamViec ? $item->caLamViec->ten : '--';
                                                            $mauCa =
                                                                'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300';
                                                            if ($tenCa == 'Sáng') {
                                                                $mauCa =
                                                                    'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400';
                                                            } elseif ($tenCa == 'Chiều') {
                                                                $mauCa =
                                                                    'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400';
                                                            } elseif ($tenCa == 'Hành chính') {
                                                                $mauCa =
                                                                    'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400';
                                                            }
                                                        @endphp
                                                        <span class="px-2 py-1 rounded text-xs {{ $mauCa }}">
                                                            {{ $tenCa }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                        {{ $item->gio_vao ? \Carbon\Carbon::parse($item->gio_vao)->format('H:i') : '--' }}
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                        {{ $item->gio_ra ? \Carbon\Carbon::parse($item->gio_ra)->format('H:i') : '--' }}
                                                    </td>
                                                    <td
                                                        class="px-6 py-4 text-sm font-medium text-blue-600 dark:text-blue-400">
                                                        {{ number_format($item->so_cong ?? 0, 2) }}
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        @php
                                                            $soCong = $item->so_cong ?? 0;

                                                            if ($item->gio_vao) {
                                                                $mau =
                                                                    'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300';
                                                                $icon = 'fas fa-check-circle';
                                                                $text = '✅ Đúng giờ';

                                                                if ($item->gio_ra && $item->trang_thai == 'di_muon') {
                                                                    $mau =
                                                                        'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300';
                                                                    $icon = 'fas fa-exclamation-triangle';
                                                                    $text = '⏰ Đi muộn';
                                                                } elseif (
                                                                    $item->gio_ra &&
                                                                    $item->trang_thai == 've_som'
                                                                ) {
                                                                    $mau =
                                                                        'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300';
                                                                    $icon = 'fas fa-exclamation-triangle';
                                                                    $text = '🏠 Về sớm';
                                                                }
                                                            } else {
                                                                $mau =
                                                                    'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400';
                                                                $icon = 'fas fa-minus-circle';
                                                                $text = '⏸️ Chưa chấm công';

                                                                if ($item->trang_thai == 'nghi_phep') {
                                                                    $mau =
                                                                        'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300';
                                                                    $icon = 'fas fa-calendar-check';
                                                                    $text = '📋 Nghỉ phép';
                                                                } elseif ($item->trang_thai == 'vang_mat') {
                                                                    $mau =
                                                                        'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300';
                                                                    $icon = 'fas fa-times-circle';
                                                                    $text = '❌ Vắng mặt';
                                                                }
                                                            }
                                                        @endphp
                                                        <span
                                                            class="px-2.5 py-1 rounded-full text-xs font-medium {{ $mau }}">
                                                            <i class="{{ $icon }} mr-1"></i> {{ $text }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6"
                                                        class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                                        <i
                                                            class="fas fa-inbox text-2xl block mb-2 text-gray-300 dark:text-gray-600"></i>
                                                        Chưa có dữ liệu chấm công trong 7 ngày qua
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ===== MODAL TẠO ĐƠN XIN VỀ SỚM ===== --}}
    <div id="modal-tao-don-ve-som" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6 mx-4">
            <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                <span>📝</span> Đơn xin về sớm
            </h3>
            <p class="text-gray-600 dark:text-gray-300 mb-2">
                Bạn đang về sớm <span id="phut-ve-som-text-modal" class="font-bold text-yellow-600">0</span> phút.
            </p>
            <p class="text-gray-600 dark:text-gray-300 mb-4">Vui lòng tạo đơn xin về sớm để gửi lên HR duyệt.</p>

            <div class="mb-4">
                <label class="block font-medium mb-2 text-gray-700 dark:text-gray-300">Giờ ra dự kiến</label>
                <input type="time" id="gio-ra-du-kien"
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-2 text-gray-700 dark:text-gray-300">Lý do về sớm</label>
                <textarea id="ly-do-ve-som-modal" rows="3"
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600" placeholder="Nhập lý do..."></textarea>
            </div>

            <div class="flex gap-3 justify-end">
                <button onclick="closeModalTaoDonVeSom()"
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-600 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                    Hủy
                </button>
                <button onclick="guiDonVeSom()"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    <i class="fas fa-paper-plane mr-2"></i> Gửi đơn
                </button>
            </div>
        </div>
    </div>

    {{-- ===== MODAL ĐÃ GỬI ĐƠN ===== --}}
    <div id="modal-da-gui-don" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6 mx-4">
            <div class="text-center">
                <i class="fas fa-paper-plane text-6xl text-yellow-500 mb-4"></i>
                <h3 class="text-xl font-bold mb-2">✅ Đã gửi đơn xin về sớm!</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-2">
                    Đơn của bạn đang chờ HR duyệt.
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Bạn sẽ nhận được thông báo khi đơn được duyệt.
                </p>
                <button onclick="closeModalDaGuiDon()"
                    class="mt-4 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    Đóng
                </button>
            </div>
        </div>
    </div>

    {{-- ===== MODAL ĐANG CHỜ DUYỆT ===== --}}
    <div id="modal-cho-duyet" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6 mx-4">
            <div class="text-center">
                <i class="fas fa-clock text-6xl text-yellow-500 mb-4 animate-pulse"></i>
                <h3 class="text-xl font-bold mb-2">⏳ Đang chờ HR duyệt</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-2">
                    Đơn xin về sớm của bạn đang được xử lý.
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Vui lòng đợi HR phê duyệt để hoàn tất check-out.
                </p>
                <button onclick="closeModalChoDuyet()"
                    class="mt-4 px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                    Đóng
                </button>
            </div>
        </div>
    </div>

    {{-- ===== MODAL BỊ TỪ CHỐI ===== --}}
    <div id="modal-tu-choi" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6 mx-4">
            <div class="text-center">
                <i class="fas fa-times-circle text-6xl text-red-500 mb-4"></i>
                <h3 class="text-xl font-bold mb-2">❌ Đơn xin về sớm bị từ chối</h3>
                <p id="ly-do-tu-choi-text" class="text-gray-600 dark:text-gray-300 mb-4">
                    Lý do: ...
                </p>
                <button onclick="closeModalTuChoi()"
                    class="mt-4 px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                    Đóng
                </button>
            </div>
        </div>
    </div>

    {{-- 🔥 SCRIPT ĐỒNG HỒ - LUÔN CHẠY DÙ CÓ HAY KHÔNG CÓ KHUÔN MẶT --}}
    <script>
        // =============================================
        // ĐỒNG HỒ THỜI GIAN THỰC
        // =============================================
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeString = hours + ':' + minutes + ':' + seconds;

            const timeElement = document.getElementById('currentTime');
            if (timeElement) {
                timeElement.textContent = timeString;
            }
        }

        setInterval(updateClock, 1000);
        updateClock();

        // =============================================
        // ĐĂNG KÝ KHUÔN MẶT
        // =============================================
        @if (!$hasFace)
            let registerStream = null;
            let registerFacingMode = 'user';
            let isRegisterProcessing = false;

            async function initRegisterCamera(facing = 'user') {
                try {
                    if (registerStream) {
                        registerStream.getTracks().forEach(track => track.stop());
                    }

                    registerStream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: facing,
                            width: {
                                ideal: 640
                            },
                            height: {
                                ideal: 480
                            }
                        }
                    });
                    const video = document.getElementById('registerVideo');
                    video.srcObject = registerStream;
                    video.onloadedmetadata = () => {
                        video.play();
                    };
                } catch (err) {
                    console.error('Camera error:', err);
                    document.getElementById('registerStatus').textContent = '❌ Không thể truy cập camera. Vui lòng kiểm tra quyền truy cập.';
                    document.getElementById('registerStatus').className =
                        'mt-3 p-3 rounded-lg text-sm text-center bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400';
                    document.getElementById('registerStatus').classList.remove('hidden');
                }
            }

            function captureRegisterImage() {
                const video = document.getElementById('registerVideo');
                const canvas = document.getElementById('registerCanvas');
                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth || 320;
                canvas.height = video.videoHeight || 240;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                return canvas.toDataURL('image/jpeg', 0.7);
            }

            document.getElementById('btnRegister').addEventListener('click', async function() {
                if (isRegisterProcessing) return;

                const status = document.getElementById('registerStatus');
                const overlay = document.getElementById('registerOverlay');

                isRegisterProcessing = true;
                overlay.classList.remove('hidden');
                status.classList.add('hidden');

                try {
                    const imageData = captureRegisterImage();

                    const response = await fetch('{{ route('employee.cham-cong-face.register') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            image: imageData
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        status.textContent = '✅ ' + data.message;
                        status.className =
                            'mt-3 p-3 rounded-lg text-sm text-center bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400 border border-green-200 dark:border-green-800';
                        status.classList.remove('hidden');

                        // Reload trang sau 2 giây
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    } else {
                        status.textContent = '❌ ' + data.message;
                        status.className =
                            'mt-3 p-3 rounded-lg text-sm text-center bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400 border border-red-200 dark:border-red-800';
                        status.classList.remove('hidden');
                    }
                } catch (error) {
                    console.error('Register error:', error);
                    status.textContent = '❌ Lỗi hệ thống: ' + error.message;
                    status.className =
                        'mt-3 p-3 rounded-lg text-sm text-center bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400 border border-red-200 dark:border-red-800';
                    status.classList.remove('hidden');
                } finally {
                    isRegisterProcessing = false;
                    overlay.classList.add('hidden');
                }
            });

            document.getElementById('btnFlipRegister').addEventListener('click', function() {
                registerFacingMode = (registerFacingMode === 'user') ? 'environment' : 'user';
                initRegisterCamera(registerFacingMode);
            });

            // Khởi tạo camera đăng ký
            initRegisterCamera('user');
        @endif

        // =============================================
        // TẠO ĐƠN XIN VỀ SỚM
        // =============================================
        @if ($hasFace)
            function guiDonVeSom() {
                const lyDo = document.getElementById('ly-do-ve-som-modal').value.trim();
                const gioRa = document.getElementById('gio-ra-du-kien').value;

                if (!lyDo) {
                    showResult('⚠️ Vui lòng nhập lý do về sớm!', false);
                    return;
                }

                if (!gioRa) {
                    showResult('⚠️ Vui lòng chọn giờ ra dự kiến!', false);
                    return;
                }

                const btn = document.querySelector('#modal-tao-don-ve-som button:last-child');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Đang gửi...';

                fetch('{{ route('employee.cham-cong-face.tao-don-ve-som') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            ly_do: lyDo,
                            gio_ra_du_kien: gioRa
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Gửi đơn';

                        if (data.success) {
                            closeModalTaoDonVeSom();
                            document.getElementById('modal-da-gui-don').classList.remove('hidden');
                            document.getElementById('modal-da-gui-don').classList.add('flex');
                            showResult('✅ ' + data.message, true);
                        } else {
                            showResult('❌ ' + data.message, false);
                        }
                    })
                    .catch(error => {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Gửi đơn';
                        showResult('❌ Lỗi: ' + error.message, false);
                    });
            }

            function closeModalTaoDonVeSom() {
                document.getElementById('modal-tao-don-ve-som').classList.add('hidden');
                document.getElementById('modal-tao-don-ve-som').classList.remove('flex');
                document.getElementById('ly-do-ve-som-modal').value = '';
            }

            function closeModalDaGuiDon() {
                document.getElementById('modal-da-gui-don').classList.add('hidden');
                document.getElementById('modal-da-gui-don').classList.remove('flex');
            }

            function closeModalChoDuyet() {
                document.getElementById('modal-cho-duyet').classList.add('hidden');
                document.getElementById('modal-cho-duyet').classList.remove('flex');
            }

            function closeModalTuChoi() {
                document.getElementById('modal-tu-choi').classList.add('hidden');
                document.getElementById('modal-tu-choi').classList.remove('flex');
            }

            document.getElementById('btnTaoDonVeSom').addEventListener('click', function() {
                if (!checkInStatus.checkedIn) {
                    showResult('⚠️ Bạn cần Check-in trước!', false);
                    return;
                }
                if (checkInStatus.checkedOut) {
                    showResult('⚠️ Bạn đã Check-out hôm nay rồi!', false);
                    return;
                }

                document.getElementById('modal-tao-don-ve-som').classList.remove('hidden');
                document.getElementById('modal-tao-don-ve-som').classList.add('flex');
                const now = new Date();
                document.getElementById('gio-ra-du-kien').value = now.toTimeString().slice(0, 5);

                fetch('{{ route('employee.cham-cong-face.kiem-tra-ve-som') }}')
                    .then(res => res.json())
                    .then(data => {
                        if (data.so_phut_ve_som > 0) {
                            document.getElementById('phut-ve-som-text-modal').textContent = Math.round(data
                                .so_phut_ve_som);
                        }
                    })
                    .catch(() => {});
            });
        @endif
    </script>

    {{-- 🔥 CHỈ LOAD SCRIPT CAMERA KHI CÓ KHUÔN MẶT --}}
    @if ($hasFace)
        <script>
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const overlay = document.getElementById('overlay');
            const result = document.getElementById('result');
            const faceGuide = document.getElementById('faceGuide');
            const scanLine = document.getElementById('scanLine');
            const btnCheckIn = document.getElementById('btnCheckIn');
            const btnCheckOut = document.getElementById('btnCheckOut');
            const btnCapture = document.getElementById('btnCapture');
            const btnFlipCamera = document.getElementById('btnFlipCamera');
            const status = document.getElementById('status');
            const cameraStatus = document.getElementById('cameraStatus');
            const autoModeToggle = document.getElementById('autoModeToggle');
            const autoStatus = document.getElementById('autoStatus');
            const faceDetectStatus = document.getElementById('faceDetectStatus');

            let stream = null;
            let isProcessing = false;
            let isAutoMode = true;
            let facingMode = 'user';
            let lastProcessedTime = 0;
            let checkInStatus = {
                checkedIn: {{ $checkedIn ? 'true' : 'false' }},
                checkedOut: {{ $checkedOut ? 'true' : 'false' }}
            };

            async function initCamera(facing = 'user') {
                try {
                    if (stream) {
                        stream.getTracks().forEach(track => track.stop());
                    }

                    stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: facing,
                            width: {
                                ideal: 640
                            },
                            height: {
                                ideal: 480
                            }
                        }
                    });
                    video.srcObject = stream;
                    video.onloadedmetadata = () => {
                        video.play();
                        setTimeout(() => {
                            if (isAutoMode) startAutoScan();
                        }, 1000);
                    };

                    const cameraLabel = facing === 'user' ? '📷 Camera trước' : '📷 Camera sau';
                    cameraStatus.textContent = cameraLabel;

                    setTimeout(() => {
                        if (faceGuide) {
                            faceGuide.style.opacity = '0';
                            setTimeout(() => {
                                faceGuide.style.display = 'none';
                            }, 500);
                        }
                    }, 3000);

                } catch (err) {
                    console.error('Camera error:', err);
                    alert('❌ Không thể truy cập camera. Vui lòng kiểm tra quyền truy cập.');
                }
            }

            let scanInterval = null;

            function startAutoScan() {
                if (scanInterval) clearInterval(scanInterval);

                scanLine.classList.remove('hidden');
                scanLine.style.display = 'block';

                let scanPosition = 20;
                let scanDirection = 1;

                scanInterval = setInterval(() => {
                    scanPosition += scanDirection * 2;
                    if (scanPosition > 80 || scanPosition < 20) {
                        scanDirection *= -1;
                    }
                    scanLine.style.top = scanPosition + '%';

                    const now = Date.now();
                    if (now - lastProcessedTime > 3000 && !isProcessing) {
                        lastProcessedTime = now;
                        detectAndProcessFace();
                    }
                }, 50);
            }

            function stopAutoScan() {
                if (scanInterval) {
                    clearInterval(scanInterval);
                    scanInterval = null;
                }
                scanLine.classList.add('hidden');
                scanLine.style.display = 'none';
            }

            async function detectAndProcessFace() {
                if (isProcessing) return;

                if (checkInStatus.checkedIn && checkInStatus.checkedOut) {
                    faceDetectStatus.textContent = '✅ Đã hoàn thành chấm công hôm nay';
                    return;
                }

                if (checkInStatus.checkedIn && !checkInStatus.checkedOut) {
                    faceDetectStatus.textContent = '🚪 Phát hiện khuôn mặt - Tự động Check-out...';
                    await authenticateFace('check_out');
                    return;
                }

                if (!checkInStatus.checkedIn) {
                    faceDetectStatus.textContent = '✅ Phát hiện khuôn mặt - Tự động Check-in...';
                    await authenticateFace('check_in');
                    return;
                }
            }

            function flipCamera() {
                facingMode = (facingMode === 'user') ? 'environment' : 'user';
                initCamera(facingMode);
                if (isAutoMode) {
                    setTimeout(() => startAutoScan(), 1000);
                }
                showResult(`🔄 Đã chuyển sang ${facingMode === 'user' ? 'camera trước' : 'camera sau'}`, true);
            }

            function captureImage() {
                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth || 320;
                canvas.height = video.videoHeight || 240;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                return canvas.toDataURL('image/jpeg', 0.7);
            }

            function showResult(message, isSuccess) {
                result.classList.remove('hidden');
                result.className =
                    `absolute bottom-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg text-sm font-semibold z-10 ${isSuccess ? 'bg-green-500 text-white' : 'bg-red-500 text-white'}`;
                result.textContent = message;

                setTimeout(() => {
                    result.classList.add('hidden');
                }, 4000);
            }

            function showResultWithButton(message, buttonText, onButtonClick) {
                result.classList.remove('hidden');
                result.className =
                    'absolute bottom-4 left-1/2 transform -translate-x-1/2 px-4 py-3 rounded-lg text-sm font-semibold z-10 bg-yellow-500 text-white shadow-lg';
                result.innerHTML = `
                        <div class="flex items-center gap-3">
                            <span>${message}</span>
                            <button onclick="(${onButtonClick.toString()})()" 
                                    class="px-3 py-1 bg-white text-yellow-600 rounded-lg text-xs font-bold hover:bg-gray-100 transition">
                                ${buttonText}
                            </button>
                        </div>
                    `;

                setTimeout(() => {
                    if (!result.classList.contains('hidden')) {
                        result.classList.add('hidden');
                    }
                }, 10000);
            }

            async function authenticateFace(loai) {
                if (isProcessing) {
                    showResult('⏳ Đang xử lý, vui lòng đợi...', false);
                    return;
                }

                if (loai === 'check_in' && checkInStatus.checkedIn) {
                    showResult('❌ Bạn đã Check-in hôm nay rồi!', false);
                    return;
                }
                if (loai === 'check_out' && checkInStatus.checkedOut) {
                    showResult('❌ Bạn đã Check-out hôm nay rồi!', false);
                    return;
                }
                if (loai === 'check_out' && !checkInStatus.checkedIn) {
                    showResult('❌ Bạn chưa Check-in hôm nay!', false);
                    return;
                }

                isProcessing = true;
                overlay.classList.remove('hidden');

                try {
                    const imageData = captureImage();

                    const response = await fetch('{{ route('employee.cham-cong-face.authenticate') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            image: imageData,
                            loai: loai
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        showResult(`${data.message} (Độ tin cậy: ${data.confidence}%)`, true);

                        if (loai === 'check_in') {
                            checkInStatus.checkedIn = true;
                            btnCheckIn.disabled = true;
                            btnCheckOut.disabled = false;
                            faceDetectStatus.textContent = '✅ Đã Check-in thành công!';
                            status.innerHTML = `⏳ Đã check-in lúc ${data.time || '...'}, chưa check-out`;
                            status.className =
                                'mt-3 p-3 rounded-lg text-sm text-center bg-yellow-50 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800';
                        } else {
                            checkInStatus.checkedOut = true;
                            btnCheckOut.disabled = true;
                            faceDetectStatus.textContent = '✅ Đã Check-out thành công!';
                            status.innerHTML =
                                `✅ Đã check-in và check-out hôm nay<br><span class="text-xs opacity-70">Check-out lúc: ${data.time || '...'}</span>`;
                            status.className =
                                'mt-3 p-3 rounded-lg text-sm text-center bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400 border border-green-200 dark:border-green-800';
                            stopAutoScan();
                        }

                        setTimeout(() => {
                            location.reload();
                        }, 2000);

                    } else {
                        if (data.yeu_cau_tao_don) {
                            const phutVeSom = Math.round(data.so_phut_ve_som);
                            showResultWithButton(
                                `⚠️ Bạn đang về sớm ${phutVeSom} phút!`,
                                '📝 Tạo đơn xin về sớm',
                                function() {
                                    document.getElementById('phut-ve-som-text-modal').textContent = phutVeSom;
                                    document.getElementById('modal-tao-don-ve-som').classList.remove('hidden');
                                    document.getElementById('modal-tao-don-ve-som').classList.add('flex');
                                    const now = new Date();
                                    document.getElementById('gio-ra-du-kien').value = now.toTimeString().slice(0,
                                        5);
                                }
                            );
                            faceDetectStatus.textContent = '⚠️ ' + data.message;
                            isProcessing = false;
                            overlay.classList.add('hidden');
                            return;
                        } else if (data.trang_thai_don === 'cho_duyet') {
                            showResult('⏳ Đơn xin về sớm đang chờ HR duyệt!', false);
                            document.getElementById('modal-cho-duyet').classList.remove('hidden');
                            document.getElementById('modal-cho-duyet').classList.add('flex');
                            isProcessing = false;
                            overlay.classList.add('hidden');
                            return;
                        } else if (data.trang_thai_don === 'tu_choi') {
                            document.getElementById('ly-do-tu-choi-text').textContent = 'Lý do: ' + (data.ly_do_tu_choi ||
                                'Không có lý do');
                            document.getElementById('modal-tu-choi').classList.remove('hidden');
                            document.getElementById('modal-tu-choi').classList.add('flex');
                            showResult(data.message, false);
                            isProcessing = false;
                            overlay.classList.add('hidden');
                            return;
                        } else {
                            showResult(data.message || '❌ Xác thực thất bại', false);
                            faceDetectStatus.textContent = '⚠️ ' + (data.message || 'Xác thực thất bại');
                        }
                    }

                } catch (error) {
                    console.error('Lỗi:', error);
                    showResult('❌ Lỗi hệ thống, vui lòng thử lại', false);
                    faceDetectStatus.textContent = '❌ Lỗi hệ thống';
                } finally {
                    setTimeout(() => {
                        isProcessing = false;
                        overlay.classList.add('hidden');
                    }, 1000);
                }
            }

            // Sự kiện
            btnCheckIn.addEventListener('click', () => {
                if (isAutoMode) {
                    stopAutoScan();
                }
                authenticateFace('check_in');
            });

            btnCheckOut.addEventListener('click', () => {
                if (isAutoMode) {
                    stopAutoScan();
                }
                authenticateFace('check_out');
            });

            btnFlipCamera.addEventListener('click', flipCamera);

            btnCapture.addEventListener('click', () => {
                const imageData = captureImage();
                const link = document.createElement('a');
                link.download = 'face_capture_' + Date.now() + '.jpg';
                link.href = imageData;
                link.click();
                showResult('📸 Đã lưu ảnh chụp', true);
            });

            autoModeToggle.addEventListener('change', function() {
                isAutoMode = this.checked;
                if (isAutoMode) {
                    autoStatus.textContent = '(Đang hoạt động)';
                    autoStatus.className = 'text-xs text-green-500 font-medium';
                    startAutoScan();
                    showResult('🤖 Đã bật chế độ tự động', true);
                } else {
                    autoStatus.textContent = '(Đã tắt)';
                    autoStatus.className = 'text-xs text-gray-500 font-medium';
                    stopAutoScan();
                    faceDetectStatus.textContent = '⏸️ Đã tạm dừng tự động';
                    showResult('⏸️ Đã tắt chế độ tự động', false);
                }
            });

            initCamera('user');

            setInterval(async () => {
                try {
                    const response = await fetch('{{ route('employee.cham-cong-face.status') }}');
                    const data = await response.json();
                    if (data) {
                        checkInStatus.checkedIn = data.checked_in;
                        checkInStatus.checkedOut = data.checked_out;
                    }
                } catch (e) {
                    console.log('Check status error:', e);
                }
            }, 10000);
        </script>
    @endif

    <style>
        .loader {
            border-top-color: #3b82f6;
            animation: spinner 0.6s linear infinite;
        }

        @keyframes spinner {
            to {
                transform: rotate(360deg);
            }
        }

        #video,
        #registerVideo {
            background: #1a1a2e;
            min-height: 300px;
        }

        #faceGuide,
        #registerGuide {
            transition: opacity 0.5s ease;
        }

        #result {
            transition: all 0.3s ease;
            max-width: 90%;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            cursor: default;
        }

        #result button {
            cursor: pointer;
            white-space: nowrap;
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        #btnFlipCamera:hover,
        #btnFlipRegister:hover {
            transform: scale(1.1);
            background: rgba(0, 0, 0, 0.8);
        }

        #cameraStatus {
            backdrop-filter: blur(8px);
        }

        #scanLine {
            transition: top 0.1s ease;
            box-shadow: 0 0 20px rgba(74, 222, 128, 0.5);
            animation: glowPulse 1s ease-in-out infinite;
        }

        @keyframes glowPulse {

            0%,
            100% {
                opacity: 0.5;
            }

            50% {
                opacity: 1;
            }
        }

        .peer:checked~.peer-checked\:bg-blue-600 {
            background-color: #2563eb;
        }

        #btnTaoDonVeSom:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        #btnTaoDonVeSom:not(:disabled):hover {
            transform: scale(1.02);
        }
    </style>
@endsection