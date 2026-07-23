@extends('layouts.employee')

@section('content')
<div class="p-4 max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        📸 Chấm công tự động bằng khuôn mặt
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Đưa mặt vào khung hình để tự động chấm công</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Hôm nay</p>
                    <p class="text-sm font-bold text-gray-900 dark:text-white" id="currentTime">{{ \Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format('H:i:s') }}</p>
                </div>
            </div>
        </div>

        <div class="p-6">

            @if(!$hasFace)
                <div class="p-6 text-center bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                    <svg class="w-16 h-16 mx-auto text-yellow-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-lg font-semibold text-yellow-700 dark:text-yellow-300">{{ $message }}</p>
                    <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-2">Vui lòng liên hệ bộ phận Nhân sự để đăng ký khuôn mặt.</p>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Camera --}}
                    <div class="lg:col-span-2">
                        <div class="relative bg-gray-900 rounded-lg overflow-hidden aspect-video">
                            <video id="video" class="w-full h-full object-cover" autoplay playsinline></video>
                            <canvas id="canvas" class="hidden"></canvas>
                            
                            {{-- Khung hướng dẫn --}}
                            <div id="faceGuide" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <div class="w-48 h-48 border-2 border-dashed border-white/50 rounded-full animate-pulse">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white/60 text-xs bg-black/30 px-3 py-1 rounded-full">
                                    Đưa mặt vào giữa khung để tự động chấm công
                                </div>
                            </div>

                            {{-- Scan line animation --}}
                            <div id="scanLine" class="absolute left-0 right-0 h-0.5 bg-green-400 shadow-lg shadow-green-400/50 hidden" 
                                 style="top: 50%; transform: translateY(-50%);">
                            </div>

                            <div id="overlay" class="absolute inset-0 flex items-center justify-center bg-black/70 hidden">
                                <div class="text-center text-white">
                                    <div class="loader ease-linear rounded-full border-4 border-t-4 border-blue-500 h-12 w-12 mb-4 mx-auto animate-spin"></div>
                                    <p class="text-sm font-medium">Đang xác thực khuôn mặt...</p>
                                    <p class="text-xs text-white/50 mt-1">Vui lòng giữ yên</p>
                                </div>
                            </div>

                            <div id="result" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg text-sm font-semibold hidden z-10"></div>

                            {{-- Nút lật camera --}}
                            <button id="btnFlipCamera" 
                                class="absolute top-4 right-4 p-2.5 bg-black/60 hover:bg-black/80 text-white rounded-full transition z-10 backdrop-blur-sm border border-white/20"
                                title="Lật camera">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </button>

                            {{-- Camera status --}}
                            <div id="cameraStatus" class="absolute top-4 left-4 px-2.5 py-1 bg-black/60 backdrop-blur-sm rounded text-xs text-white/80 border border-white/20">
                                📷 Camera trước
                            </div>
                        </div>

                        {{-- Auto mode toggle --}}
                        <div class="mt-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="autoModeToggle" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                    <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        🤖 Chế độ tự động
                                    </span>
                                </label>
                                <span id="autoStatus" class="text-xs text-green-500 font-medium">(Đang hoạt động)</span>
                            </div>
                            <span id="faceDetectStatus" class="text-xs text-gray-500 dark:text-gray-400">
                                👤 Chờ khuôn mặt...
                            </span>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-3">
                            <button id="btnCheckIn" 
                                class="flex-1 min-w-[100px] px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed font-medium flex items-center justify-center gap-2"
                                {{ $checkedIn ? 'disabled' : '' }}>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                Check-in
                            </button>
                            <button id="btnCheckOut" 
                                class="flex-1 min-w-[100px] px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed font-medium flex items-center justify-center gap-2"
                                {{ $checkedOut || !$checkedIn ? 'disabled' : '' }}>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Check-out
                            </button>
                            <button id="btnCapture" 
                                class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-medium flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Chụp ảnh
                            </button>
                        </div>

                        {{-- Trạng thái --}}
                        <div id="status" class="mt-3 p-3 rounded-lg text-sm text-center
                            {{ $checkedIn && $checkedOut ? 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400 border border-green-200 dark:border-green-800' : 
                               ($checkedIn ? 'bg-yellow-50 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800' : 
                               'bg-gray-50 text-gray-500 dark:bg-gray-700/30 dark:text-gray-400 border border-gray-200 dark:border-gray-700') }}">
                            @if($checkedIn && $checkedOut)
                                ✅ Đã check-in và check-out hôm nay
                                @if(isset($checkInTime))
                                    <span class="block text-xs opacity-70">Check-in: {{ $checkInTime }} | Check-out: {{ $checkOutTime }}</span>
                                @endif
                            @elseif($checkedIn)
                                ⏳ Đã check-in lúc {{ $checkInTime }}, chưa check-out
                            @else
                                ⏰ Chưa check-in hôm nay
                            @endif
                        </div>
                    </div>

                    {{-- Thông tin bên phải --}}
                    <div class="lg:col-span-1 space-y-4">
                        {{-- Hướng dẫn --}}
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                                <span>📋</span> Hướng dẫn
                            </h4>
                            <ol class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                <li class="flex items-start gap-2">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full text-xs font-bold flex-shrink-0">1</span>
                                    <span>Cho phép truy cập camera</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full text-xs font-bold flex-shrink-0">2</span>
                                    <span>Đưa mặt vào giữa khung hình</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full text-xs font-bold flex-shrink-0">3</span>
                                    <span><strong class="text-green-600 dark:text-green-400">Tự động</strong> nhận diện và chấm công</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full text-xs font-bold flex-shrink-0">4</span>
                                    <span>Đợi hệ thống xác thực khuôn mặt (3-5 giây)</span>
                                </li>
                            </ol>
                        </div>

                        {{-- Thông tin bảo mật --}}
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">💡</span>
                                <div>
                                    <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Độ chính xác cao</p>
                                    <p class="text-xs text-blue-600 dark:text-blue-400">Bảo mật tuyệt đối với công nghệ nhận diện khuôn mặt</p>
                                </div>
                            </div>
                        </div>

                        {{-- Thông tin ca làm việc --}}
                        <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-4 border border-indigo-200 dark:border-indigo-800">
                            <p class="text-xs text-indigo-600 dark:text-indigo-400 font-medium">CA LÀM VIỆC HIỆN TẠI</p>
                            @php
                                $now = \Carbon\Carbon::now('Asia/Ho_Chi_Minh');
                                $caHienTai = \App\Models\CaLamViec::where('trang_thai', 1)->get()->first(function($ca) use ($now) {
                                    $start = \Carbon\Carbon::parse($ca->gio_bat_dau);
                                    $end = \Carbon\Carbon::parse($ca->gio_ket_thuc);
                                    return $now->between($start, $end);
                                });
                            @endphp
                            @if($caHienTai)
                                <p class="text-lg font-bold text-indigo-700 dark:text-indigo-300">
                                    {{ $caHienTai->ten }}
                                    <span class="text-sm font-normal text-indigo-500 dark:text-indigo-400">
                                        ({{ \Carbon\Carbon::parse($caHienTai->gio_bat_dau)->format('H:i') }} - {{ \Carbon\Carbon::parse($caHienTai->gio_ket_thuc)->format('H:i') }})
                                    </span>
                                </p>
                            @else
                                <p class="text-lg font-bold text-gray-400">Ngoài giờ làm việc</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Lịch sử chấm công --}}
                <div class="mt-6">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <span>📜</span> Lịch sử chấm công khuôn mặt
                        </h4>
                        <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full">10 bản ghi gần nhất</span>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr class="text-left text-gray-600 dark:text-gray-400">
                                        <th class="px-4 py-2.5 font-medium">Thời gian</th>
                                        <th class="px-4 py-2.5 font-medium">Loại</th>
                                        <th class="px-4 py-2.5 font-medium">Trạng thái</th>
                                        <th class="px-4 py-2.5 font-medium">Độ tin cậy</th>
                                        <th class="px-4 py-2.5 font-medium">IP</th>
                                    </tr>
                                </thead>
                                <tbody id="historyTable" class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @php
                                        $histories = App\Models\ChamCongFace::where('nguoi_dung_id', auth()->id())
                                            ->orderBy('created_at', 'desc')
                                            ->limit(10)
                                            ->get();
                                    @endphp
                                    @forelse($histories as $history)
                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-600/50 transition">
                                            <td class="px-4 py-2.5 text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $history->created_at->format('d/m/Y H:i:s') }}</td>
                                            <td class="px-4 py-2.5">
                                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $history->loai == 'check_in' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                                    {{ $history->loai == 'check_in' ? '✅ Check-in' : '🚪 Check-out' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2.5">
                                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $history->trang_thai == 'thanh_cong' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                                    {{ $history->trang_thai == 'thanh_cong' ? '✅ Thành công' : '❌ Thất bại' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2.5 text-gray-700 dark:text-gray-300">
                                                @if($history->trang_thai == 'thanh_cong')
                                                    <span class="text-green-600 dark:text-green-400 font-medium">{{ round($history->confidence * 100, 2) }}%</span>
                                                @else
                                                    <span class="text-red-500">{{ round($history->confidence * 100, 2) }}%</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2.5 text-xs text-gray-500 dark:text-gray-400">{{ $history->ip_address ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                                <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                Chưa có lịch sử chấm công khuôn mặt
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

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

    // Đồng hồ
    function updateClock() {
        const now = new Date();
        document.getElementById('currentTime').textContent = 
            String(now.getHours()).padStart(2, '0') + ':' +
            String(now.getMinutes()).padStart(2, '0') + ':' +
            String(now.getSeconds()).padStart(2, '0');
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Khởi tạo camera
    async function initCamera(facing = 'user') {
        try {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }

            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: facing,
                    width: { ideal: 640 },
                    height: { ideal: 480 }
                }
            });
            video.srcObject = stream;
            video.onloadedmetadata = () => {
                video.play();
                // Bắt đầu auto scan sau 1 giây
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

    // Auto scan - nhận diện khuôn mặt liên tục
    let scanInterval = null;

    function startAutoScan() {
        if (scanInterval) clearInterval(scanInterval);
        
        scanLine.classList.remove('hidden');
        scanLine.style.display = 'block';
        
        // Animation scan line
        let scanPosition = 20;
        let scanDirection = 1;
        
        scanInterval = setInterval(() => {
            // Di chuyển scan line
            scanPosition += scanDirection * 2;
            if (scanPosition > 80 || scanPosition < 20) {
                scanDirection *= -1;
            }
            scanLine.style.top = scanPosition + '%';
            
            // Check face mỗi 3 giây
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

    // Phát hiện và xử lý khuôn mặt
    async function detectAndProcessFace() {
        if (isProcessing) return;
        
        // Nếu đã check-in và check-out rồi thì dừng
        if (checkInStatus.checkedIn && checkInStatus.checkedOut) {
            faceDetectStatus.textContent = '✅ Đã hoàn thành chấm công hôm nay';
            return;
        }

        // Nếu đã check-in và chưa check-out → auto checkout
        if (checkInStatus.checkedIn && !checkInStatus.checkedOut) {
            faceDetectStatus.textContent = '🚪 Phát hiện khuôn mặt - Tự động Check-out...';
            await authenticateFace('check_out');
            return;
        }

        // Chưa check-in → auto checkin
        if (!checkInStatus.checkedIn) {
            faceDetectStatus.textContent = '✅ Phát hiện khuôn mặt - Tự động Check-in...';
            await authenticateFace('check_in');
            return;
        }
    }

    // Lật camera
    function flipCamera() {
        facingMode = (facingMode === 'user') ? 'environment' : 'user';
        initCamera(facingMode);
        if (isAutoMode) {
            setTimeout(() => startAutoScan(), 1000);
        }
        showResult(`🔄 Đã chuyển sang ${facingMode === 'user' ? 'camera trước' : 'camera sau'}`, true);
    }

    // Chụp ảnh
    function captureImage() {
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth || 320;
        canvas.height = video.videoHeight || 240;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        return canvas.toDataURL('image/jpeg', 0.7);
    }

    // Hiển thị thông báo
    function showResult(message, isSuccess) {
        result.classList.remove('hidden');
        result.className = `absolute bottom-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg text-sm font-semibold z-10 ${isSuccess ? 'bg-green-500 text-white' : 'bg-red-500 text-white'}`;
        result.textContent = message;
        
        setTimeout(() => {
            result.classList.add('hidden');
        }, 4000);
    }

    // Xác thực khuôn mặt
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
            
            const response = await fetch('{{ route("employee.cham-cong-face.authenticate") }}', {
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
                    status.className = 'mt-3 p-3 rounded-lg text-sm text-center bg-yellow-50 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800';
                } else {
                    checkInStatus.checkedOut = true;
                    btnCheckOut.disabled = true;
                    faceDetectStatus.textContent = '✅ Đã Check-out thành công!';
                    status.innerHTML = `✅ Đã check-in và check-out hôm nay<br><span class="text-xs opacity-70">Check-out lúc: ${data.time || '...'}</span>`;
                    status.className = 'mt-3 p-3 rounded-lg text-sm text-center bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400 border border-green-200 dark:border-green-800';
                    // Dừng auto scan
                    stopAutoScan();
                }

                setTimeout(() => {
                    location.reload();
                }, 2000);

            } else {
                showResult(data.message || '❌ Xác thực thất bại', false);
                faceDetectStatus.textContent = '⚠️ ' + (data.message || 'Xác thực thất bại');
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

    // Auto mode toggle
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

    // Khởi tạo
    initCamera('user');

    // Kiểm tra trạng thái định kỳ
    setInterval(async () => {
        try {
            const response = await fetch('{{ route("employee.cham-cong-face.status") }}');
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

<style>
    .loader {
        border-top-color: #3b82f6;
        animation: spinner 0.6s linear infinite;
    }

    @keyframes spinner {
        to { transform: rotate(360deg); }
    }

    #video {
        background: #1a1a2e;
        min-height: 300px;
    }

    #faceGuide {
        transition: opacity 0.5s ease;
    }

    #result {
        transition: all 0.3s ease;
        max-width: 90%;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    #btnFlipCamera:hover {
        transform: scale(1.1);
        background: rgba(0,0,0,0.8);
    }

    #cameraStatus {
        backdrop-filter: blur(8px);
    }

    /* Scan line animation */
    #scanLine {
        transition: top 0.1s ease;
        box-shadow: 0 0 20px rgba(74, 222, 128, 0.5);
        animation: glowPulse 1s ease-in-out infinite;
    }

    @keyframes glowPulse {
        0%, 100% { opacity: 0.5; }
        50% { opacity: 1; }
    }

    /* Toggle switch */
    .peer:checked ~ .peer-checked\:bg-blue-600 {
        background-color: #2563eb;
    }
</style>
@endsection